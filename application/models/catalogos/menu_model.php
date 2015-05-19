<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author	Melbin
 */
class Menu_model extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();

	}

	function get_menu($tabla, $id){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where('sic_sio_id',$id);
		$query = $this->db->get()->result_array();

		return $query;
	}

	function get_tabla($tabla, $where){
		$this->db->where($where);
		$query = $this->db->get($tabla);

		return $query->result_array();
	}
}