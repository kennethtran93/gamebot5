<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Players" table.
 *
 */
class Players extends MY_Model
{

	// constructor
	function __construct()
	{
		parent::__construct('players', 'Player');
	}

	function buy($team, $token, $player)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://botcards.jlparry.com/buy");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$xml = simplexml_load_string($result);

		foreach ($xml->certificate as $certificate)
		{
			$timestamp = date(DATE_ATOM, (int) $certificate->datetime);
			$data = array(
				'token' => (string) $certificate->token,
				'piece' => (string) $certificate->piece,
				'broker' => (string) $certificate->broker,
				'player' => (string) $certificate->player,
				'datetime' => $timestamp
			);
			$this->db->insert('collections', $data); // insert into database
		}
	}

}
