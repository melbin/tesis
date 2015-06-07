<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author	Melbin
 */
class Sistema_model extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();

	}

	function get_menu($tabla, $id){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where(array('sic_sio_id'=>$id,'sic_estado'=>1));
		$query = $this->db->get()->result_array();

		return $query;
	}

	function get_menu2($tabla, $where){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where();
		$query = $this->db->get()->result_array();

		return $query;
	}

	function get_registro($tabla, $where){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where($where);
		$query = $this->db->get()->row_array();
		if($query>0){
			return $query;
		}

	}

	function get_tabla($tabla, $where){
		$this->db->where($where);
		$query = $this->db->get($tabla);

		return $query->result_array();
	}
}