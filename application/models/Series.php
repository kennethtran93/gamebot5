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
		if (($handle = fopen("http://botcards.jlparry.com/data/series", "r")) !== FALSE)
		{
			fgetcsv($handle, ",");
			while (($data = fgetcsv($handle, ",")) !== FALSE)
			{
				print_r($data);
			}
			fclose($handle);
		}
	}
	
//	$this->series->getGameData();

}
