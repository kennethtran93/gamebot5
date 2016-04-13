<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Agent" table.
 * This is a bit of an overkill just to store one row of data, but whatever.
 *
 */
class Agent extends MY_Model {

	// constructor
	function __construct()
	{
		parent::__construct('agent', 'auth_token');
	}

}
