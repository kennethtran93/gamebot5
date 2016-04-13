<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Agents" table.
 *
 */
class Agents extends MY_Model {

	// constructor
	function __construct()
	{
		parent::__construct('agents', 'auth_token');
	}

}
