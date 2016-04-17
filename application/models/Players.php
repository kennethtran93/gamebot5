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

	function resetPeanuts()
	{
		$this->db->update($this->_tableName, array('Peanuts' => 100));
	}

	function all_summary($round = 0) {
		$this->db->select('Player, Peanuts');
		$this->db->where('last_active_round', $round);
		$query = $this->db->get($this->_tableName);
		return $query->result();
	}
}
