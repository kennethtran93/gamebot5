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
		$this->data['gameStatus'] = $this->getStatus();
		// Add Page-specific style to load
		$this->pageStyles[] = "home";
		$this->pageStyles[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.css";

		// Add Page-specific scripts to load
		$this->pageScripts[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.js";
		$this->pageScripts[] = "welcome";

		//get the data from all tables
		$players = $this->players->all();
		$cards = $this->collections->all();
		$table = $this->series->all();

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

		$this->render();
	}

}
