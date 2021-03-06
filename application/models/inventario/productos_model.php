<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author	Melbin
 */
class Productos_model extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();

	}

	function get_articulos($id_bodega)
	{
		$query ="
				SELECT pro_id, pro_nombre, sar_cantidad, sar_precio, sar_id  FROM pro_producto
				INNER JOIN sar_saldo_articulo ON sar_saldo_articulo.sar_pro_id = pro_producto.pro_id
				WHERE sar_estado = 1 AND sar_ali_id=".$id_bodega;

		$articulos=$this->db->query($query)->result_array();
      	return $articulos;
	}

	function actualizar_registro($tabla,$datos,$id)
	{
		$this->db->where('sar_id',$id);
		$this->db->update($tabla,$datos);
		$rows = $this->db->affected_rows();
    	return $rows;
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

	function get_um($where)
	{
        $this->db->select()
         ->from('pro_producto')
         ->where($where)
         ->join('uni_unidad_medida', 'uni_unidad_medida.uni_id = pro_uni_id')
         ->limit(1)
         ;

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