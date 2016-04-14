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
		$team = $this->agents->team_id; // team name
		$token = $this->agents->auth_token; // token, team must have been registered
		$player = $this->session->userdata('username'); // current playername stored in session data
		$success = $this->players->buy($team, $token, $player);

		if ($success)
		{
			echo 'The card pack is purchased.';
		}
	}
}
