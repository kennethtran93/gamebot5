<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Agent" table.
 *
 */
class Agent extends MY_Model {

	// constructor
	function __construct()
	{
		parent::__construct('agent', 'property');
	}

	// This overrides the data being returned to an array instead
	function all()
	{
		$agent = parent::all();
		
		$properties = array();
		
		foreach ($agent as $property) {
			$properties[$property->property] = $property->value;
		}
		
		return $properties;
	} 
	
	// Clear value from property (set to empty string). column names expected
	function clear($data)
	{
		if (is_array($data))
		{
			// multiple properties to clear
			$records = array();
			foreach ($data as $key)
			{
				$records[$key] = '';

				$this->update($records);
			}
		} else
		{
			// single property to clear
			$this->update($data, '');
		}
	}

	// Get array of properties, or two parameters for a single record.
	// Overrides and manually call the parent method
	function update($data, $value = '')
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$record = array(
					'property'	 => $key,
					'value'		 => $value
				);
				parent::update($record);
			}
		} else
		{
			$record = array(
				'property'	 => $data,
				'value'		 => $value
			);
			parent::update($record);
		}
	}

}
