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
}