<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends Application {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/player
	 * 	- or -
	 * 		http://example.com/index.php/player/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/player/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($name = null)
	{
		// Check if player name was passed in or not
		if (is_null($name) || $name == "")
		{
			// Check if username exist
			if (!is_null($this->session->username))
			{
				// Username exists; user logged in
				$name = $this->session->username;

				// Did user hit buy button?
				if (!is_null($this->input->post('buyCardPack')))
				{
					$this->buy();
				}
			}
		} else
		{
			// Username passed through index parameter
			// Check if username exists in db AND is logged in
			if (!$this->players->exists($name) || is_null($this->session->username))
			{
				// send back to initial player page
				redirect('/player');
			}
		}

		$this->data['pageTitle'] = 'Player Portfolios'; // Page Title
		$this->data['pagebody'] = 'player'; // this is the view we want shown
		// Add Page-specific style to load
		$this->pageStyles[] = "player";
		// Add Page-specific script to load
		$this->pageScripts[] = "player";

		if (is_null($name))
		{
			// Display message instead of regular page
			$this->data['staticMessage'] = "We're sorry, but at this time only registered users can view other player details.  Simply register/login in the navigation bar to continue.";
			$this->data['staticMessageType'] = "staticError";
		} else
		{
			// Username exists and in db
			// Add Page-specifc style to load
			$this->pageStyles[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.css";

			// Add Page-specific scripts to load
			$this->pageScripts[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.js";

			$this->data['playerName'] = $name;
			$this->data['players'] = $this->parser->parse('_playerSelect1', $this->getPlayers(), true);
			$this->data['avatar'] = $this->getAvatar($name);
			$this->data['peanuts'] = $this->getPeanuts($name);
			$this->data['cards'] = $this->getCardCount($name);
			$this->data['buyButton'] = "";

			if ($this->session->username == $name)
			{
				// Only provide the buy button if they are viewing their own portfolio
				$this->data['buyButton'] = $this->parser->parse('_buyButton', array(), true);
			}
			$this->data['playerCards'] = $this->parser->parse('_playerCard1', $this->getPlayerCollection($name), true);
			$this->data['playerLatestActivity'] = $this->parser->parse('_transactions', $this->getLatestActivity($name), true);
		}

		// Render Page!
		$this->render();
	}

	//Gets all the players and transform it for CI to parse into a dropdown template
	function getPlayers()
	{
		// Get all players from db
		$tablePlayers = $this->players->all();

		$options = array();
		foreach ($tablePlayers as $player)
		{
			// Set one dropdown option
			$option['player'] = $player->Player;
			$option['link'] = $this->data['appRoot'] . "/player/" . $player->Player;

			// Check for selected/disabled
			$check0 = $_SERVER['PATH_INFO'] == ("/player");
			$check1 = $_SERVER['PATH_INFO'] == ("/player/" . $player->Player);
			$check2 = $this->session->username == ucfirst($player->Player);

			if ($check0 && $check2)
			{
				// no specific player is listed, and user is logged in and is the currently generating name
				$option['selected'] = "selected=\"selected\" disabled=\"disabled\"";
			} elseif ($check1)
			{
				// Specific player specified in URL and is the currently generating name
				$option['selected'] = "selected=\"selected\" disabled=\"disabled\"";
			} else
			{
				// Otherwise, replace the parser {selected} with an empty string
				$option['selected'] = "";
			}
			// add a player's dropdown option to overall list
			$options[] = $option;
		}

		// Make it ready for CI parser
		$players['options'] = $options;

		return $players;
	}

	//Retrive a user's Avatar filename from the DB 
	function getAvatar($name = null)
	{
		$avatarPath = $this->data['appRoot'] . "/assets/images/avatar/";

		if (is_null($name) || $name == "" || !$this->players->exists($name))
		{
			// Unregistered user
			return $avatarPath . "generic_photo.png";
		} else
		{
			// Checks if the file for the player exists in our filesystem.  else output generic image
			return (file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath . $this->players->get($name)->Avatar) ? ($avatarPath . $this->players->get($name)->Avatar) : ($avatarPath . 'generic_photo.png'));
		}
	}

	//Get Player Peanut count
	function getPeanuts($name = null)
	{
		// Check if username is provided, and if they exist in the db
		if (is_null($name) || $name == "" || !$this->players->exists($name))
		{
			// Unregistered user
			return "0";
		} else
		{
			// Name is given and exists, grab that player's peanut count
			return $this->players->get($name)->Peanuts;
		}
	}

	function getCardCount($name = null)
	{
		// Check if username is provided, and if they exist in the db
		if (is_null($name) || $name == "" || !$this->players->exists($name))
		{
			// Unregistered user
			return "0";
		} else
		{
			// Name is given and exists, grab that player's card count
			return count($this->collections->some('Player', $name));
		}
	}

	// Get all player cards
	function getPlayerCollection($name = null)
	{
		if (is_null($name) || $name == "" || !$this->players->exists($name))
		{
			// Unregistered user
			return "N/A";
		} else
		{
			// Name is given and exists, grab that player's card collection
			$tableCollections = $this->collections->some('Player', $name);
			$collection = array();
			foreach ($tableCollections as $type)
			{
				// Just extract each Piece into a new array (may contain duplicates)
				$collection[] = $type->Piece;
			}

			// Count all values.  Unique Piece key with quantity as value
			$partCount = array_count_values($collection);
			// An attempt at sorting the array by key
			ksort($partCount, SORT_NATURAL);

			$cardList = array();
			foreach ($partCount as $key => $value)
			{
				// Extract Bot Card Data
				$row['Series'] = substr($key, 0, 2);
				$row['Type'] = substr($key, 2, 1);
				switch (substr($key, -1, 1))
				{
					case 0:
						$row['Part'] = "Top";
						break;
					case 1:
						$row['Part'] = "Middle";
						break;
					case 2:
						$row['Part'] = "Bottom";
						break;
				}
				// Quantity of this card
				$row['Quantity'] = $value;
				$cardList[] = $row;
			}

			// prep for CI parser
			$result['pCards'] = $cardList;

			return $result;
		}
	}

	// Get Player Activities (all)
	function getLatestActivity($name = null)
	{
		if (is_null($name) || $name == "" || !$this->players->exists($name))
		{
			// Unregistered user
			return "N/A";
		} else
		{
			// Name is given and exists, grab that player's transaction history
			$tableTransactions = $this->transactions->some('Player', $name);

			$history = array();
			foreach ($tableTransactions as $type)
			{
				// Translate from object into parsing array
				$row['Timestamp'] = $type->DateTime;
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

			// prep for CI parser
			$result['transactions'] = $history;

			return $result;
		}
	}

	function buy()
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
					$player = $this->players->get($this->session->userdata('username'));

					// Check peanut count first
					if ($player->Peanuts >= 20)
					{
						// Enough Peanuts for a card pack
						//calling the columns from the database players column
						$team = $this->agent->get('code')->value;
						$token = $this->agent->get('auth_token')->value;
						$name = $player->Player;

						$buyInfo = array(
							'team'	 => $team,
							'token'	 => $token,
							'player' => $name);

						$string = http_build_query($buyInfo);

						//send post request to BCC/buy 
						$posturl = curl_init($this->serverURL . '/buy');
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
						}

						$cards = array();
						foreach ($xml->certificate as $certificate)
						{
							$timestamp = date('Y-m-d H:i:s', (int) $certificate->datetime);
							$record = array(
								'token'		 => (string) $certificate->token,
								'piece'		 => (string) $certificate->piece,
								'player'	 => (string) $certificate->player,
								'datetime'	 => $timestamp
							);
							$cards[] = $record;
						}

						$this->collections->add_batch($cards); // Insert batch of cards into db

						$transactions = array(
							'DateTime'	 => date('Y-m-d H:i:s'),
							'Player'	 => $name,
							'Series'	 => NULL,
							'Trans'		 => 'buy'
						);

						$this->transactions->add($transactions); // Add Transaction Record

						$updatePlayer = array(
							'Player'			 => $name,
							'Peanuts'			 => $xml['balance'],
							'last_active_round'	 => $status['round']
						);

						$this->players->update($updatePlayer);

						$this->session->statusMessage = "Successfully exchanged 20 peanuts for a card pack.  Your balance remaining has been updated!";
					} else
					{
						// Peanut Count less than 20 - can't purchase card stack
						$this->session->statusMessage = "Not Enough Peanuts to purchase card pack!";
					}
				} else
				{
					// Current agent round does not match server current round
					$this->session->statusMessage = "Unable to process the purchase request.  The server's current round has been updated.  All existing cards and transactions have been removed.";
					redirect($_SERVER['REQUEST_URI']);
				}
			} else
			{
				// Game State NOT OPEN
				$this->session->statusMessage = "Cannot purchase items at this time.  Please try again when the round state is OPEN!";
			}
		} else
		{
			// Agent is offline.
			$this->session->statusMessage = "This agent is currently offline.  You can't purchase anything right now.";
		}
	}

}
