<?php

if (!defined('APPPATH'))
	exit('No direct script access allowed');

/**
 *  Convert a CSV string (returned from a CSV file read completely)
 * into an associative array, using the first row as keys
 * 
 * @param string $data CSV contents
 */
if (!function_exists('csv_to_assoc'))
{

	function csv_to_assoc($source)
	{
		$result = array();
		// Open handle
		$handle = fopen($source, "r");
		// Get first row from handle and treat as keys to an array
		$keys = fgetcsv($handle);
		while ($row = fgetcsv($handle)) {
			// combine keys with row to make associative array
			$result[] = array_combine($keys, $row);
		}
		fclose($handle);
		
		return $result;
	}

}
