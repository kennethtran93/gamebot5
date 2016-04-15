<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Transactions" table.
 *
 */
class Transactions extends MY_Model2 {

	// constructor
	function __construct()
	{
		parent::__construct('transactions', 'DateTime', 'Player');
	}

}
