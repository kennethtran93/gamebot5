<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assemble extends Application {

	/**
	 * Assemble page where users get to put together pieces of robots
	 * There will be three drop down's beside three image's that display
	 * selected part of robot
	 */
	public function index()
	{
		$this->data['pageTitle'] = 'Assemble and Trade-In Your Bot'; // Page Title
		$this->pageScripts[] = "assemble"; // Add Page-specific script to load
		$this->pageStyles[] = "assemble"; // Add Page-specific style to load
		// Grab username from Session if any
		$player = $this->session->username;

		// Check if username is set
		if (is_null($player) || $player == "")
		{
			// username not set; not logged in
			// Display static message and not load actual page
			$this->data['staticMessage'] = "This page is only visible to players that are logged in.  Please login using the form in the navigation.";
			$this->data['staticMessageType'] = "staticError";
		} else
		{
			$status = $this->getStatus(false);
			if ($status['state'] == 3)
			{
				if (!is_null($this->input->post('btnAssemble')))
				{
					// Assemble Button Clicked
					$top = null;
					$middle = null;
					$bottom = null;

					if (!empty($this->input->post('top')))
					{
						$top = substr($this->input->post('top'), 6);
					}
					if (!empty($this->input->post('middle')))
					{
						$middle = substr($this->input->post('middle'), 6);
					}
					if (!empty($this->input->post('bottom')))
					{
						$bottom = substr($this->input->post('bottom'), 6);
					}

					if (!is_null($top) && !is_null($middle) && !is_null($bottom))
					{
						// all three pieces have tokens ready for trading
						$result = $this->sell($top, $middle, $bottom);
					} else
					{
						$this->session->statusMessage = "Whoops, there seems to be one or more missing bot piece(s) in the assembly.  Cannot process the sell request.";
					}
				}
				// username set; player logged in
				// this is the view we want shown 
				$this->data['pagebody'] = 'assemble';

				$this->data['topPiece'] = "";
				$this->data['middlePiece'] = "";
				$this->data['bottomPiece'] = "";

				if (!empty($this->input->post('topPiece')))
				{
					$this->data['topPiece'] = "Last selected top piece: " . $this->input->post('topPiece');
				}
				if (!empty($this->input->post('middlePiece')))
				{
					$this->data['middlePiece'] = "Last selected middle piece: " . $this->input->post('middlePiece');
				}
				if (!empty($this->input->post('bottomPiece')))
				{
					$this->data['bottomPiece'] = "Last selected bottom piece: " . $this->input->post('bottomPiece');
				}

				// Grab all cards that player owns
				$playerCollection = $this->collections->some('Player', $player);

				// Check if player actually own any cards first
				if (!empty($playerCollection))
				{
					// Player owns at least one card
					// Initialize the dropdown container for assembly page dropdowns
					$dropdowns = array();

					// Iterate through the object returned from collections
					foreach ($playerCollection as $row)
					{
						$piece = $row->Piece; // Grab value in Piece column
						$series = substr($piece, 0, 2); // Extract first two numbers
						$type = strtoupper(substr($piece, 2, 1)); // Extract the letter after series
						$part = substr($piece, -1, 1); // Extract the last number for part
						$opt = "Series " . $series . " Type " . $type;  // For a human-readable selection.
						switch ($part)
						{
							case 0:
								// Top Part Card
								$dropdowns['top'][] = array(
									'opt'	 => $opt . " (TOP)",
									'value'	 => $piece . "|" . $row->Token);
								break;
							case 1:
								// Middle Part Card
								$dropdowns['middle'][] = array(
									'opt'	 => $opt . " (MIDDLE)",
									'value'	 => $piece . "|" . $row->Token);
								break;
							case 2:
								// Bottom Part Card
								$dropdowns['bottom'][] = array(
									'opt'	 => $opt . " (BOTTOM)",
									'value'	 => $piece . "|" . $row->Token);
								break;
						}
					}

					array_multisort($dropdowns['top']);
					array_multisort($dropdowns['middle']);
					array_multisort($dropdowns['bottom']);

					// Prepare for parsing through CI
					$options = array();
					$options['top']['options'] = $dropdowns['top'];
					$options['middle']['options'] = $dropdowns['middle'];
					$options['bottom']['options'] = $dropdowns['bottom'];

					// Parse the data for each dropdown and return string html result
					$this->data['topOptions'] = $this->parser->parse('_assembleOption1', $options['top'], true);
					$this->data['middleOptions'] = $this->parser->parse('_assembleOption1', $options['middle'], true);
					$this->data['bottomOptions'] = $this->parser->parse('_assembleOption1', $options['bottom'], true);
				} else
				{
					// Player does not own any cards at the moment.  Display message
					$this->data['staticMessage'] = "You currently don't have any cards in your collection in our system.  Purchase a card pack from your portfolio page first!";
					$this->data['staticMessageType'] = "staticError";
				}
			} else
			{
				// Game State not OPEN
				$this->data['staticMessage'] = "Round state is not open.  Please try again when it's open.";
				$this->data['staticMessageType'] = "staticError";
			}
		}

		// Render the page!
		$this->render();
	}

	function sell($top, $middle, $bottom)
	{
		$status = $this->getStatus(false);

		// Check agent status
		if ($this->agent->get('agent_online')->value)
		{

			//check if status is open
			if ($status['state'] == 3)
			{
				$this->agentRegister();
				// Check if round is current
				if ($this->agent->get('last_active_round')->value == $status['round'])
				{
					// Agent is still in its current round
					// Get Player
					$player = strtolower($this->session->userdata('username'));

					$sellInfo = array(
						'team'	 => $this->agent->get('code')->value,
						'token'	 => $this->agent->get('auth_token')->value,
						'player' => $player,
						'top'	 => $top,
						'middle' => $middle,
						'bottom' => $bottom
					);

					$string = http_build_query($sellInfo);

					//send post request to BCC/buy 
					$posturl = curl_init($this->serverURL . '/sell');
					curl_setopt($posturl, CURLOPT_POST, true);
					curl_setopt($posturl, CURLOPT_POSTFIELDS, $string);
					curl_setopt($posturl, CURLOPT_RETURNTRANSFER, true);

					$response = curl_exec($posturl);
					curl_close($posturl);
					
					$xml = simplexml_load_string($response);

					if (!empty($xml->message))
					{
						// Oh my, despite the careful checks, something went past it and the server returned a booboo.
						$this->data['staticMessage'] = "Oh my, the server returned an error:  " . $xml->message;
						$this->data['staticMessageType'] = "staticError";

						$this->session->statusMessage = "Whoops, something went wrong while processing your sell request.";
					} else
					{

						$transaction = array(
							'DateTime'	 => date('Y-m-d H:i:s'),
							'Player'	 => $player,
							'Series'	 => NULL,
							'Trans'		 => 'sell'
						);

						$series = $this->series->all();
						foreach ($series as $one)
						{
							if ($one->Value == $xml->price)
							{
								$transaction['Series'] = $one->Series;
								break;
							}
						}

						$this->transactions->add($transaction); // Add Transaction Record

						$updatePlayer = array(
							'Player'			 => $player,
							'Peanuts'			 => $xml->balance,
							'last_active_round'	 => $status['round']
						);

						$this->players->update($updatePlayer);
						
						// Remove used cards from the collection
						$this->collections->delete($top);
						$this->collections->delete($middle);
						$this->collections->delete($bottom);

						$this->session->statusMessage = "Congratuations!  The sell request has been successful.  You now have an additional " . $xml->price . " peanuts in your bank.  Your current balance is: " . $xml->balance . " peanuts.";
					}
				} else
				{
					// Current agent round does not match server current round
					$this->session->statusMessage = "Unable to process the sell request.  The server's current round has been updated.  All existing cards and transactions have been removed.";
					redirect($_SERVER['REQUEST_URI'], "refresh");
				}
			} else
			{
				// Game State NOT OPEN
				$this->session->statusMessage = "Cannot sell items at this time.  Please try again when the round state is OPEN!";
			}
		} else
		{
			// Agent is offline.
			$this->session->statusMessage = "This agent is currently offline.  You can't sell anything right now.";
		}
	}

}
