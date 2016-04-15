<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Series" table.
 *
 */
class Series extends MY_Model {

	// constructor
	function __construct()
	{
		parent::__construct('series', 'series');
	}

	function getBotSeries($serverURL)
	{
		$response = csv_to_assoc($serverURL . "/data/series");
		$result = array();
		foreach ($response as $one)
		{
			$record = array();
			foreach ($one as $key => $value)
			{
				if ($key == 'code')
				{
					$key = 'series';
				}
				$record[ucfirst($key)] = $value;
			}
			$result[] = $record;
		}

		// Get current series known in agent
		$current = $this->all();
		$compare = array();
		foreach ($current as $one)
		{
			$compare[] = get_object_vars($one);
		}

		$different = false;
		// Check if equal records
		if (count($result) == count($compare))
		{

			for ($x = 0; $x < count($result); $x++)
			{
				$check = !empty(array_diff_assoc($result[$x], $compare[$x]));
				if ($check)
				{
					$different = true;
					break;
				}
			}
		} else
		{
			$different = true;
		}

		if ($different)
		{
			// Delete all records from series
			$this->truncate();

			// Add records from server
			$this->add_batch($result);
		}
	}

}
