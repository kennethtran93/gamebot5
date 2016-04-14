<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data access wrapper for "Players" table.
 *
 */
class Players extends MY_Model {

	// constructor
	function __construct()
	{
		parent::__construct('players', 'Player');
	}

	function show()
	{
		$this->db->select('Player, Avatar, AccessLevel, DateRegistered, LastUpdated');
		$this->db->order_by($this->_keyField, 'asc');
		$query = $this->db->get($this->_tableName);
		return $query->result();
	}

	function showOne($name = '')
	{
		$this->db->select('Player, Avatar, AccessLevel, DateRegistered, LastUpdated');
		$this->db->where($this->_keyField, $name);
		$query = $this->db->get($this->_tableName);
		if ($query->num_rows() < 1)
			return null;
		return $query->row();
	}

}
