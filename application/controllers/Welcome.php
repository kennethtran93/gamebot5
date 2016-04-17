<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Application {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 * 	- or -
	 * 		http://example.com/index.php/welcome/index
	 * 	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->data['pagebody'] = 'home'; // this is the view we want shown
		// Add Page-specific style to load
		$this->pageStyles[] = "home";
		$this->pageStyles[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.css";

		// Add Page-specific scripts to load
		$this->pageScripts[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.js";
		$this->pageScripts[] = "welcome";

		//get the data from all tables
		$players = $this->players->all_summary($this->getStatus()['round']);
		$cards = $this->collections->all();
		$table = $this->series->all();

		if (count($players))
		{
			$playersTable = array();
			foreach ($players as $player)
			{
				$pRow = array(
					//calling the columns from the database players column
					'link'		 => $this->data['appRoot'] . "/player/" . $player->Player,
					'Player'	 => $player->Player,
					'Peanuts'	 => $player->Peanuts,
					'Equity'	 => (count($this->collections->some('Player', $player->Player)) + $player->Peanuts)
				);

				$playersTable[] = $pRow;
			}

			// Obtain a list of columns
			foreach ($playersTable as $key => $row)
			{
				$equity[$key] = $row['Equity'];
				$name[$key] = $row['Player'];
			}

			// Sort the data with equity descending, player name ascending
			// Add $playersTable as the last parameter, to sort by the common key
			array_multisort($equity, SORT_DESC, $name, SORT_ASC, $playersTable);

			$PlayerSummary['Players'] = $playersTable;
			$this->data['playerInfo'] = $this->parser->parse('_playerinfo1', $PlayerSummary, true);
		} else
		{
			// No Recent/active players
			$this->data['playerInfo'] = "";
		}

		$series = array();
		foreach ($table as $type)
		{
			$row = array(
				//calling the columns from the database series column
				'Series'		 => $type->Series,
				'Description'	 => $type->Description,
				'Frequency'		 => $type->Frequency,
				'Value'			 => $type->Value,
				'Quantity'		 => 0
			);
			$series[] = $row;
		}

		// Get all cards in db
		foreach ($cards as $card)
		{
			$key = array_search(substr($card->Piece, 0, 2), array_column($series, 'Series'));
			$series[$key]['Quantity'] ++;
		}

		// prep for ci parser
		$summary['collection'] = $series;
		$this->data['botPieceSummary'] = $this->parser->parse('_pieceSummary', $summary, true);

		$transactions = csv_to_assoc($this->agent->get('server_URL')->value . "/data/transactions/10");
		$this->data['serverLatestActivity'] = $this->parser->parse('_transactions_global', array('transactions' => $transactions), true);
		
		$agent_transactions = $this->getLatestTransactions();
		$this->data['agentLatestActivity'] = $this->parser->parse('_transactions_agent', array('transactions' => $agent_transactions), true);


		$this->render();
	}

	// Get Player Activities (all)
	function getLatestTransactions()
	{
		$tableTransactions = $this->transactions->trailing(10);
		$history = array();
		if (count($tableTransactions))
		{
			foreach ($tableTransactions as $type)
			{
				// Translate from object into parsing array
				$row['Timestamp'] = $type->DateTime;
				$row['Player'] = $type->Player;
				$row['Trans'] = $type->Trans;
				switch ($type->Trans)
				{
					case "buy":
						$row['Peanuts'] = "(-20)";
						$row['Action'] = "Purchased a card pack.";
						break;
					case "sell":
						if (!is_null($type->Series))
						{
							$row['Peanuts'] = "+" . $this->series->get($type->Series)->Value;
							$row['Action'] = "Sold an assembled bot (a completed set of Bot Series " . $type->Series . ").";
						} else
						{
							$row['Peanuts'] = "+5";
							$row['Action'] = "Sold an assembled bot (mismatched pieces).";
						}
						break;
					default:
						$row['Peanuts'] = "???";
						$row['Action'] = "Unknown Transaction Type.";
						break;
				}
				// Add transaction history to array
				$history[] = $row;
			}

			// Sort by most recent
			array_multisort($history, SORT_DESC);
		}
		return $history;
	}

}
