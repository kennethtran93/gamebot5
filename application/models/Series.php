<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Series" table.
 *
 */
class Series extends MY_Model
{

	// constructor
	function __construct()
	{
		parent::__construct('series', 'series');
	}

	function getGameData()
	{
		if (($handle = fopen("http://ken-botcards.azurewebsites.net/data/series", "r")) !== FALSE)
		{
			fgetcsv($handle, ",");
			while (($data = fgetcsv($handle, ",")) !== FALSE)
			{
				print_r($data);

				//update local database from the csv
				// Token changed.  Update Database accordingly.
				// Delete Record / Truncate Table
				$this->series->truncate();

				fclose($handle);
			}
		}
	}
}
	