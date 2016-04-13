<?php

class Buy extends Application
{
	/*
	 * Contructor
	 */

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->purchaseCard();
	}

	private function purchaseCard()
	{

		$team = "A05"; // team name
		$token = "6d5d68ace9f79b2eb469f486b651a40d"; // token, team must have been registered
		$player = $this->session->userdata('username'); // current playername stored in session data
		$success = $this->players->buy($team, $token, $player);

		if ($success)
		{
			echo 'The card pack is purchased.';
		}
	}

}
