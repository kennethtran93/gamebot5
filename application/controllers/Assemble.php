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
		$this->data['pageTitle'] = 'Assemble Your Bot'; // Page Title
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
			// username set; player logged in
			// this is the view we want shown 
			$this->data['pagebody'] = 'assemble';

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
								'value'	 => $piece);
							break;
						case 1:
							// Middle Part Card
							$dropdowns['middle'][] = array(
								'opt'	 => $opt . " (MIDDLE)",
								'value'	 => $piece);
							break;
						case 2:
							// Bottom Part Card
							$dropdowns['bottom'][] = array(
								'opt'	 => $opt . " (BOTTOM)",
								'value'	 => $piece);
							break;
					}
				}

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
				$this->data['staticMessage'] = "You currently don't have any cards in your collection in our system.  Purchase a card pack first!";
				$this->data['staticMessageType'] = "staticError";
			}
		}

		// Render the page!
		$this->render();
	}

}
