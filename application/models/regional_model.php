<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author	Melbin
 */
class Regional_model extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();

	}

    function detalle_sol($id_sol=NUL)
    {
        $query = $this->db->select()
                    ->from('sol_solicitud')
                    ->join('dpi_departamento_interno','dpi_id=sol_dpi_id','left')
                    ->join('ali_almacen_inv','ali_id=sol_ali_id','left')
                    ->join('soe_solicitud_envio','soe_id=sol_soe_id','left')
                    ->join('tps_tipo_solicitud','tps_id=sol_tps_id','left')    
                    ->join('des_detalle_solicitud','des_sol_id=sol_id','left')
                    ->join('cat_catalogo','cat_id = des_cat_id','left')
                    ->join('ets_estado_solicitud','ets_id=des_ets_id','left')
                    ->join('fon_fondo','fon_id=des_fon_id','left')
                    ->where('sol_id',$id_sol);
                    ;

        $result = $this->db->get()->result_array();               
        return $result;             
    }

    function detalle_sol_productos($id_sol=NULL)
    {
        $query = $this->db->select()
                 ->from('pxs_productoxsolicitud')
                 ->join('pro_producto', 'pro_producto.pro_id = pxs_pro_id')
                 ->join('uni_unidad_medida', 'uni_id = pro_uni_id')
                 ->where('pxs_sol_id',$id_sol);
                 ;
        $result = $this->db->get()->result_array();               
        return $result;                         
    }

    function get_detalle_especificos()
    {
        $query = $this->db->select()
                    ->from('esp_especifico')
                    ->join('det_detalle_especifico', 'det_esp_id = esp_id','left')
                    ->join('fon_fondo', 'fon_id = det_fondo_id','left')
                    ->where('det_estado',1)
                    ;
        $result = $this->db->get()->result_array();
        return $result;            
    }

    function get_especificos($fondo_id, $saldo_minimo=0)
    {
        $query = $this->db->select('esp_id, esp_nombre, det_saldo, det_saldo_congelado')
                    ->from('esp_especifico')
                    ->join('det_detalle_especifico', 'det_esp_id = esp_id', 'left')
                    ->where('det_estado',1)
                    ->where('det_saldo >=',$saldo_minimo)
                    ->where('det_fondo_id',$fondo_id)
                    ;
        $result = $this->db->get()->result_array();
        return $result;               
    }

    function get_productosxcategoria($cat_id = NULL)
    {
        $query = "
                SELECT  pro_id, pro_nombre, pro_codigo FROM sub_subcatalogo
                INNER JOIN pro_producto ON pro_producto.pro_sub_id = sub_subcatalogo.sub_id
                WHERE sub_cat_id = ".$cat_id." and sub_estado = 1
                ";
             
        $resultado = $this->db->query($query)->result_array();
        return $resultado;
    }

    function cargar_productosxsubcategoria($sub_id=NULL)
    {
        $query = "
                SELECT  pro_id, pro_nombre, pro_codigo FROM sub_subcatalogo
                INNER JOIN pro_producto ON pro_producto.pro_sub_id = sub_subcatalogo.sub_id
                WHERE pro_sub_id = ".$sub_id." and sub_estado = 1
                ";
             
        $resultado = $this->db->query($query)->result_array();
        return $resultado;   
    }

	function get_parametro($parametro){
		$this->db->select();
		$this->db->from('par_parametro');
		$this->db->where('par_nombre',$parametro);
		$query = $this->db->get()->row_array();

		return $query['par_valor'];
	}

    function get_existencias2()
    {
        $query = "
        SELECT
        uni_nombre AS UM ,cat_nombre as linea, pro_codigo AS codigo, pro_nombre as nombre, SUM(sar_cantidad) AS cantidad,
        sar_precio AS precio, (SUM(sar_cantidad) * sar_precio) AS total, ali_nombre AS bodega                     
      FROM 
      sar_saldo_articulo
      INNER JOIN ali_almacen_inv ON ali_id= sar_ali_id
      INNER JOIN pro_producto ON pro_id=sar_pro_id
                            INNER JOIN sub_subcatalogo ON sub_id= pro_sub_id
      INNER JOIN cat_catalogo ON cat_id = sub_cat_id
      INNER JOIN uni_unidad_medida ON uni_id = pro_uni_id
    GROUP BY 
          ali_nombre, pro_codigo, pro_nombre, sar_precio
    HAVING 
      SUM(sar_cantidad)>0
    ORDER BY 
      linea, codigo
        ";
        $detalle=$this->db->query($query)->result_array();
        //print_r($this->db->last_query());exit()
        return $detalle;
    }

    function get_existencias($where)
    {
        $this->db->select()
                 ->from('sar_saldo_articulo')
                 ->where($where)
                 ->join('ali_almacen_inv','ali_almacen_inv.ali_id = sar_ali_id')
                 ->limit(1)
                 ;

        $query = $this->db->get()->row_array();
        if($query>0){
            return $query;
        }         

    }

	function insertar_registro($tabla, $data){
		$this->db->insert($tabla, $data);
		$last_id = $this->db->insert_id();
    	return $last_id;
	}

    function actualizar_registro($tabla, $data, $where){
        $this->db->where($where)
                 ->update($tabla, $data)
        ;
        $rows = $this->db->affected_rows();
       return $rows;
    }

    function borrado_general($tabla, $id_sol)
    {
        $this->db->delete($tabla, array('pxs_sol_id'=>$id_sol));
        $rows = $this->db->affected_rows();
        return $rows;   
    }

    function detalle_solicitud($where=NULL){
        $query = $this->db->select()
                    ->from('sol_solicitud')
                    ->join('dpi_departamento_interno','sol_dpi_id=dpi_id','left')
                    ->join('ali_almacen_inv','sol_ali_id = ali_id','left')
                    ->join('des_detalle_solicitud','des_sol_id=sol_id','left')
                    ->join('ets_estado_solicitud','des_ets_id = ets_id','left')
                    ->where('sol_estado',1)
                    ;
          if(!empty($where)){
            $this->db->where($where);
          }          

        $result = $this->db->get()->result_array();               
        return $result; 
    }

    function detalle_sol_abastecimiento($where=NULL){
        $query = $this->db->select()
                    ->from('sol_solicitud')
                    ->join('dpi_departamento_interno','sol_dpi_id=dpi_id','left')
                    ->join('ali_almacen_inv','sol_ali_id = ali_id','left')
                    ->join('des_detalle_solicitud','des_sol_id=sol_id','left')
                    ->join('ets_estado_solicitud','des_ets_id = ets_id','left')
                    ->where('sol_estado',1)
                    ->where_in('des_ets_id',array('1','2','4'));
                    ;
          if(!empty($where)){
            $this->db->where($where);
          }          

        $result = $this->db->get()->result_array();               
        return $result; 
    }

    function detalle_sol_financiero($where=NULL){
        $query = $this->db->select()
                    ->from('sol_solicitud')
                    ->join('dpi_departamento_interno','sol_dpi_id=dpi_id','left')
                    ->join('ali_almacen_inv','sol_ali_id = ali_id','left')
                    ->join('des_detalle_solicitud','des_sol_id=sol_id','left')
                    ->join('ets_estado_solicitud','des_ets_id = ets_id','left')
                    ->where('sol_estado',1)
                    ->where_in('des_ets_id',array('6','7')); // Financiero vera nada mas las aprobadas por Abastecimiento
                    ;
          if(!empty($where)){
            $this->db->where($where);
          }          

        $result = $this->db->get()->result_array();               
        return $result; 
    }

	function get_tabla($tabla, $where=null){
        if($where)
		$this->db->where($where);
		$query = $this->db->get($tabla);

		return $query->result_array();
	}

    function get_asignaciones($detalles)
    {
        $query = $this->db->select('det_esp_id, det_id, dpi_nombre as depto, SUM(axd_cantidad) as suma, det_saldo as total')
                    ->from('det_detalle_especifico')
                    ->join('axd_asignacionxdetalle_especifico', 'axd_det_id = det_id','left')
                    ->join('dpi_departamento_interno', 'dpi_id = axd_depto_id','left')
                    ->where('axd_estado',1)
                    ->where_in('axd_det_id',$detalles)
                    ;
        $result = $this->db->get()->result_array();
        return $result;            
    }

    function get_detalles_especificos($id_det)
    {
        $query = $this->db->select()
                    ->from('det_detalle_especifico')
                    ->join('esp_especifico','esp_id = det_esp_id')
                    ->join('fon_fondo', 'fon_id = det_fondo_id')
                    ->where('det_id',$id_det)
                    ;
        $result = $this->db->get()->row_array();
        return $result;            
    }

    function get_asignaciones_detalle($id_det)
    {
        $query = $this->db->select()
                    ->from('axd_asignacionxdetalle_especifico')
                    ->join('dpi_departamento_interno', 'dpi_id = axd_depto_id')
                    ->where('axd_det_id', $id_det)
                    ->where('axd_estado',1)
                    ;
        $result = $this->db->get()->result_array();
        return $result;            
    }

	function get_dropdown($tabla, $display = '', $name = '', $where = '', $selected = null
                            , $extras = '', $primary = '', $soloOpciones = false)
    {
        if(!$tabla && !is_string($tabla)){
            return 'Error: Sin Tabla.';
        }
        
        //obtenemos la llave primaria
        if( $primary == '' ){
            foreach($this->db->field_data($tabla) as $field){
                if($field->primary_key){
                    $primary = $field->name;
                }
            }
            
            if($primary == ''){
                return "Error: $tabla no tiene LLave Primaria.";
            }
        }
        
        $select = "$tabla.$primary";
        
        //pre consulta
        if(strstr($display,'{'))
    	{
    		$display = str_replace(" ", "&nbsp;", $display);
    		$select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$display))."') as valor ";
    	}
        else if($display == '')
        {
            $select .= ", $tabla.$primary as valor";
        }
    	else
    	{
	    	$select .= ", $tabla.$display as valor";
    	}
        
        //Consulta
        $this->db->select($select, false);
        if($where) $this->db->where($where);
        $this->db->order_by('valor');
        $result = $this->db->get($tabla);
        //echo $this->db->last_query(); die();
        
        //Clasificador
        $return = array();
        
        if( $result->num_rows() > 0 ){
            $return[0] = 'Seleccione';
            foreach ($result->result() as $row){
                $return[$row->$primary] = $row->valor;       
            }
        }else{
            $return[0] = 'Sin Registros';
        }
            
        
        if($name == ''){
            $name = $primary;
        }
        
        $selected = set_value($name, $selected);
        
        if( $soloOpciones ){
            return $this->get_dropdown_options($return, $selected);   
        }
        $extras = ($extras=='')? 'class="nostyle" style="display:inline-block; width:480px;"':$extras;
        
        return form_dropdown($name, $return, $selected, $extras);
    }

    function get_dropdown_options( $options = array() , $selected = null)
    {
        if ( ! is_array($selected))
		{
			$selected = array($selected);
		}
        
        $form = "";
        
        foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}
        
        return $form;
        
    }
}


