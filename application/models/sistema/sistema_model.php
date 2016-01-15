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

	function get_menu($tabla=null, $sio_id, $user_id)
	{
		$this->db->select()
			->from('uxr_usuarioxrol')
			->join('oxr_opcionxrol',' oxr_id_rol = uxr_rol_id')
			->join('sic_sistema_catalogo', 'sic_id = oxr_id_sic')
			->where('uxr_usuario_id',$user_id)
			->where('sic_estado',1)
			->where('sic_sio_id',$sio_id)
			->group_by('oxr_id_sic')
			->order_by('oxr_id')
		;	
	
		$query = $this->db->get()->result_array();

		return $query;
	}

	function get_menu_sistema($user_id)
	{
		$this->db->select('sio_id, sio_nombre, sio_controlador, sio_icono')
			->from('uxr_usuarioxrol')
			->join('oxr_opcionxrol',' oxr_id_rol = uxr_rol_id')
			->join('sic_sistema_catalogo', 'sic_id = oxr_id_sic')
			->join('sio_sistema_opcion', 'sio_id = sic_sio_id')
			->where('uxr_usuario_id',$user_id)
			->where('sic_estado',1)
			->where('sio_estado',1)
			->where('sio_menu',2)
			->group_by('sio_nombre')
			->order_by('sio_id')
		;	
		$query = $this->db->get()->result_array();

		return $query;
	}

	//
		public function cargar_menus($id,$nivel,$padre=0)
   {
   		$where=array('opc_nivel'=>$nivel);
   		if($padre!=0)
   			$where=array('opc_padre'=>$padre);
		$this->db->select()
			->where('uxr_id_usu',$id)
			->from('uxr_usuarioxrol')
			->join('oxr_opcionxrol','oxr_id_rol=uxr_id_rol')
			->join('opc_opcion','opc_id=oxr_id_opc')
		    ->group_by('oxr_id_opc')
		    ->order_by('oxr_id')
		    ->where('opc_estado',1)
			->where($where);
		$query=$this->db->get();
		return $query->result_array();
   }
	//

	function get_menu2($tabla, $where){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where();
		$query = $this->db->get()->result_array();

		return $query;
	}

	function actualizar_registro($tabla,$datos,$where)
	{
		$this->db->where($where);
		$this->db->update($tabla,$datos);
		$rows = $this->db->affected_rows();
    	return $rows;
	}

	function get_registro($tabla, $where){
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where($where);

		$query = $this->db->get()->row_array();
		if($query>0){
			return $query;
		} else {
			return 0;
		}
	}

	function get_campo($tabla, $campo ,$where=NULL){
		$query = $this->db->select()
				 	->from($tabla)
				 	->where($where)
				 	->limit(1)
				 	;

		$result = $this->db->get()->row_array();

		if(!empty($result)){
			foreach ($result as $key => $value) {
				if($key == $campo){
					return $value;
				}
			}
		} 
		return null;
	}

	function get_tabla($tabla, $where=null){
		$this->db->select()->from($tabla);
		if($where){
			$this->db->where($where);
		}
		$query=$this->db->get();				
		return $query->result_array();   	
	}

	public function cargar_opciones($rol)
   {   		
   		$this->db->select()
   		->from('oxr_opcionxrol')
		->join('sic_sistema_catalogo','sic_id = oxr_id_sic')
		->where('oxr_id_rol',$rol)
		->where('sic_estado',1)
		->order_by('oxr_id')
		->group_by('oxr_id_sic');
		$query=$this->db->get();				
		return $query->result_array();   	
   }

  public function add_opc($rol,$opc)
	{
		$this->db->select('sic_id, sic_nombre, sic_padre')
			->from('sic_sistema_catalogo')
			->where('sic_id',$opc);
			
		$query1=$this->db->get()->row_array();
			
			if(empty($query1['sic_padre'])){
				
				$this->db->select('sic_id, sic_nombre, sic_padre')
						->from('sic_sistema_catalogo')
						->where('sic_id',$opc)
						->or_where('sic_padre',$opc);

				$query2=$this->db->get()->result_array();

				if(count($query2)>0){
					foreach ($query2 as $key) {
						$this->db->insert('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_sic'=>$key['sic_id']));
					}
				}
			}else{
				$this->db->insert('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_sic'=>$opc));
			}

		return $this->db->affected_rows();
	}

	   public function del_opc($rol,$opc)
	{	
		$this->db->select('sic_id, sic_nombre, sic_padre')
			->from('sic_sistema_catalogo')
			->where('sic_id',$opc);
			
		$query1=$this->db->get()->row_array();
			
			if(empty($query1['sic_padre'])){
				
				$this->db->select('sic_id, sic_nombre, sic_padre')
						->from('sic_sistema_catalogo')
						->where('sic_id',$opc)
						->or_where('sic_padre',$opc);

				$query2=$this->db->get()->result_array();

				if(count($query2)>0){
					foreach ($query2 as $key) {
						$this->db->delete('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_sic'=>$key['sic_id']));
					}
				}
			}else{
				$this->db->delete('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_sic'=>$opc));
			}

		return $this->db->affected_rows();
	}


 } // End Sistema_model





 