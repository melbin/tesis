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
        $query = $this->db->select("*, IFNULL(SUM(axd_cantidad),0) AS suma, det_saldo AS total",false)
                    ->from('esp_especifico')
                    ->join('det_detalle_especifico', 'det_esp_id = esp_id','left')
                    ->join('fon_fondo', 'fon_id = det_fondo_id','left')
                    ->join('axd_asignacionxdetalle_especifico', 'axd_det_id = det_id', 'left')
                    ->join('dpi_departamento_interno', 'dpi_id = axd_depto_id', 'left')
                    ->where('det_estado',1)
                    // ->where('axd_estado',1)
                    ->group_by('det_id')
                    ->order_by('esp_nombre')
                    ;
        $result = $this->db->get()->result_array();
        return $result;            
    }

    function get_especificos($fondo_id, $saldo_minimo=0)
    {
        $query = $this->db->select('esp_id, esp_nombre, det_saldo, det_saldo_congelado, det_saldo_ejecutado')
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

    function get_productos_depto($sub_id=null, $id_bodega=null, $saldo=null)
    {

        $existencias =  ($saldo==1)? ' >= '.$saldo: ' = 0';
        $query = "
            SELECT 
        pro_id,  uni_nombre AS UM ,cat_nombre as categoria, pro_codigo AS codigo, pro_nombre as nombre,
                sub_id,
                (SELECT SUM(dee.dee_cantidad) 
                        FROM  sar_saldo_articulo sar02
                        INNER JOIN dee_detalle_mov dee ON dee.dee_sar_id =  sar02.sar_id
                        INNER JOIN moi_movimiento_inv moi01 ON moi01.moi_id = dee.dee_moi_id AND moi01.moi_pro_id = 1
                    WHERE sar02.sar_pro_id = sar.sar_pro_id
                    AND  sar02.sar_ali_id = ali_id
                ) AS entradas,
                (SELECT SUM(dee.dee_cantidad) 
                        FROM  sar_saldo_articulo sar02
                        INNER JOIN dee_detalle_mov dee ON dee.dee_sar_id =  sar02.sar_id
                        INNER JOIN moi_movimiento_inv moi01 ON moi01.moi_id = dee.dee_moi_id AND moi01.moi_pro_id = 2
                    WHERE sar02.sar_pro_id = sar.sar_pro_id
                    AND  sar02.sar_ali_id = ali_id
                ) AS salidas,
            SUM(sar.sar_cantidad) AS saldo,
            sar.sar_precio AS precio                
      FROM 
      sar_saldo_articulo sar
          INNER JOIN dee_detalle_mov ON dee_sar_id = sar.sar_id
     INNER JOIN moi_movimiento_inv ON moi_id = dee_moi_id
      INNER JOIN ali_almacen_inv ON ali_id= sar_ali_id
      INNER JOIN pro_producto ON pro_id=sar_pro_id
       INNER JOIN sub_subcatalogo ON sub_id= pro_sub_id
      INNER JOIN cat_catalogo ON cat_id = sub_cat_id
      INNER JOIN uni_unidad_medida ON uni_id = pro_uni_id
        WHERE moi_ali_id= ".$id_bodega."
            AND sub_id = ".$sub_id."
            AND sar.sar_cantidad ".$existencias."
    GROUP BY 
                            pro_id
    
    ORDER BY 
       pro_id ASC
        ";

      $detalle=$this->db->query($query)->result_array();
      return $detalle;  

    }

    function get_especifico_fondo($esp_id, $fon_id, $fecha_in, $fecha_out)
    {
        $query = "
            SELECT
                NULL AS entrada,
                axd.axd_fecha AS fecha_asignacion,
                dpi.dpi_nombre,
                fon.fon_nombre,
                axd.axd_depto_id,
                axd.axd_id,
              axd.axd_cantidad AS salida,
                det.det_saldo - det.det_saldo_ejecutado - IFNULL(
                    (
                        SELECT
                            SUM(axd_cantidad)
                        FROM
                            axd_asignacionxdetalle_especifico axd02
                        WHERE
                            axd02.axd_id <= axd.axd_id
                        AND axd02.axd_det_id = det.det_id
                    ),
                    0
                ) AS total
            FROM
                (
                    (
                        det_detalle_especifico det
                        INNER JOIN axd_asignacionxdetalle_especifico axd ON axd.axd_det_id = det.det_id
                    )
                    INNER JOIN esp_especifico esp ON det.det_esp_id = esp.esp_id
                )
                INNER JOIN dpi_departamento_interno dpi ON dpi.dpi_id = axd.axd_depto_id
                INNER JOIN fon_fondo fon ON fon.fon_id = det.det_fondo_id
            WHERE
                det.det_fondo_id = ".$fon_id."
            AND det.det_esp_id = ".$esp_id."
            AND det.det_fecha BETWEEN '".$fecha_in."' AND '".$fecha_out."'
        ";

        $detalle=$this->db->query($query)->result_array();
        return $detalle;
    }

    function get_productos_proveedor($id_proveedor=null)
    {
        $query = "
            SELECT
    pro_id,
    uni_nombre AS UM,
    cat_nombre AS categoria,
    pro_codigo AS codigo,
    pro_nombre AS nombre,
    sub_id,
prv_nombre AS proveedor,
    (
        SELECT
            SUM(dee.dee_cantidad)
        FROM
            sar_saldo_articulo sar02
        INNER JOIN dee_detalle_mov dee ON dee.dee_sar_id = sar02.sar_id
        INNER JOIN moi_movimiento_inv moi01 ON moi01.moi_id = dee.dee_moi_id
        
        WHERE
            sar02.sar_pro_id = sar.sar_pro_id
            AND moi01.moi_pro_id = 1
            AND moi01.moi_prv_id = prv.prv_id
    ) AS entradas,
    (
        SELECT
            SUM(dee.dee_cantidad)
        FROM
            sar_saldo_articulo sar02
        INNER JOIN dee_detalle_mov dee ON dee.dee_sar_id = sar02.sar_id
        INNER JOIN moi_movimiento_inv moi01 ON moi01.moi_id = dee.dee_moi_id
        
        WHERE
            sar02.sar_pro_id = sar.sar_pro_id
            AND moi01.moi_pro_id = 2
            AND moi01.moi_prv_id = prv.prv_id
    ) AS salidas,
    SUM(sar.sar_cantidad) AS existencias,
    sar.sar_precio AS precio
FROM
    sar_saldo_articulo sar
INNER JOIN dee_detalle_mov ON dee_sar_id = sar.sar_id
INNER JOIN moi_movimiento_inv moi ON moi.moi_id = dee_moi_id
INNER JOIN prv_proveedor prv ON prv.prv_id = moi.moi_prv_id
INNER JOIN ali_almacen_inv ON ali_id = sar_ali_id
INNER JOIN pro_producto ON pro_id = sar_pro_id
INNER JOIN sub_subcatalogo ON sub_id = pro_sub_id
INNER JOIN cat_catalogo ON cat_id = sub_cat_id
INNER JOIN uni_unidad_medida ON uni_id = pro_uni_id
WHERE
    prv.prv_id = ".$id_proveedor."
GROUP BY
    pro_id
ORDER BY
    pro_id ASC
        ";
    
    $detalle=$this->db->query($query)->result_array();
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

    function borrar_registro($tabla, $where)
    {
        $this->db->delete($tabla, $where);
        $rows = $this->db->affected_rows();
        return $rows;   
    }


    function detalle_solicitud($where=NULL){
        $query = $this->db->select('*, (
                            SELECT
                                det_saldo_congelado
                            FROM
                                det_detalle_especifico
                            WHERE
                                det_esp_id = des_esp_id
                            AND det_fondo_id = des_fon_id
                        ) AS saldo_congelado')
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
        $query = $this->db->select('*, (
                            SELECT
                                det_saldo_congelado
                            FROM
                                det_detalle_especifico
                            WHERE
                                det_esp_id = des_esp_id
                            AND det_fondo_id = des_fon_id
                        ) AS saldo_congelado')
                    ->from('sol_solicitud')
                    ->join('dpi_departamento_interno','sol_dpi_id=dpi_id','left')
                    ->join('ali_almacen_inv','sol_ali_id = ali_id','left')
                    ->join('des_detalle_solicitud','des_sol_id=sol_id','left')
                    ->join('ets_estado_solicitud','des_ets_id = ets_id','left')
                    ->where('sol_estado',1)
                    ->where_in('des_ets_id',array('1','2','4','5'));
                    ;
          if(!empty($where)){
            $this->db->where($where);
          }          

        $result = $this->db->get()->result_array();               
        return $result; 
    }

    function detalle_sol_financiero($where=NULL){
        $query = $this->db->select('*, (
                            SELECT
                                det_saldo_congelado
                            FROM
                                det_detalle_especifico
                            WHERE
                                det_esp_id = des_esp_id
                            AND det_fondo_id = des_fon_id
                        ) AS saldo_congelado')
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
                    ->group_by('det_id')
                    ->order_by('dpi_nombre')
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

    // Obtener los datos para actualizar
    function seguimiento_solicitud($id_solicitud=0)
    {

        $query = $this->db->query('
            SELECT * 
            FROM 
                des_detalle_solicitud AS des
            INNER JOIN   sol_solicitud sol ON sol_id = des.des_sol_id
            INNER JOIN det_detalle_especifico det ON  det.det_esp_id = des.des_esp_id AND  det.det_fondo_id = des.des_fon_id
            INNER JOIN axd_asignacionxdetalle_especifico  axd ON  axd.axd_det_id = det.det_id AND axd.axd_depto_id = sol.sol_dpi_id
            WHERE des_sol_id = '.$id_solicitud.'
        ')->row_array();

        return $query;
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

    function solicitudes_especifico($id_fondo, $id_especifico, $fecha_in, $fecha_out)
    {
        $query = "
        SELECT
    des.des_id,
    des.des_sol_id sol_id,
    dpi.dpi_nombre nombre,
    cat.cat_nombre categoria,
    des.des_fecha fecha,
    des.des_total cantidad,
    ets.ets_nombre,
    (
        SELECT
            det_saldo
        FROM
            det_detalle_especifico det2
        WHERE
            det2.det_fondo_id = des.des_fon_id
        AND det2.det_esp_id = des.des_esp_id
    ) - (
        SELECT
            SUM(des_total)
        FROM
            des_detalle_solicitud des2
        WHERE
            des2.des_id <= des.des_id
        AND des2.des_fon_id = des.des_fon_id
        AND des2.des_esp_id = des.des_esp_id
        AND des2.des_ets_id != 3 
    ) AS total
FROM
    des_detalle_solicitud des
INNER JOIN sol_solicitud sol ON sol.sol_id = des.des_sol_id
INNER JOIN dpi_departamento_interno dpi ON dpi.dpi_id = sol.sol_dpi_id
INNER JOIN cat_catalogo cat ON cat.cat_id = des.des_cat_id
INNER JOIN ets_estado_solicitud ets ON ets.ets_id = des.des_ets_id
WHERE
    des.des_ets_id != 3 
AND    
    des.des_fon_id = ".$id_fondo."
AND des.des_esp_id = ".$id_especifico."
AND des.des_fecha BETWEEN '".$fecha_in."'
AND '".$fecha_out."'
ORDER BY
    des_id
        ";
        
      $detalle=$this->db->query($query)->result_array();
      return $detalle;  
    }

    function get_especifico_detalle($id_especifico)
{
        $this->db->select()
                ->from('esp_especifico')
                ->join('det_detalle_especifico',' esp_id = det_esp_id') 
                ->where('esp_id',$id_especifico)
                ; 

        $query = $this->db->get()->row_array();
        return $query;          
    }

    function get_especifico_saldo($id_fondo)
    {
        $this->db->select()
                ->from('esp_especifico')
                ->join('det_detalle_especifico', 'det_esp_id = esp_id')
                ->where('det_fondo_id', $id_fondo)
                ;
        $query = $this->db->get()->result_array();        
        return $query;
    }

    function get_saldos_congelados($fondo = null, $especifico = null, $fecha_in, $fecha_out)
    {
        $this->db->select()
                ->from('foc_fondo_congelado')
                ->join('det_detalle_especifico', 'det_id = foc_det_id')
                ->join('esp_especifico', 'esp_id = det_esp_id')
                ->join('fon_fondo', 'fon_id = det_fondo_id')
                ->where('foc_fecha >=',$fecha_in)
                ->where('foc_fecha <=',$fecha_out)
                ->where('foc_estado',1)
            ;
            if($fondo)      $this->db->where('det_fondo_id', $fondo);
            if($especifico) $this->db->where('det_esp_id', $especifico);

            $query = $this->db->get()->result_array();
            return $query;
    }

    function get_solicitudes_rechazadas($depto =null, $fecha_in, $fecha_out)
    {
        $this->db->select()
            ->from('res_rechazo_solicitud')
            ->join('sol_solicitud', 'sol_id = res_sol_id')
            ->join('dpi_departamento_interno', 'dpi_id = sol_dpi_id')
            ->join('des_detalle_solicitud', 'des_sol_id = sol_id')
            ->join('esp_especifico', 'esp_id = des_esp_id')
            ->where('res_estado',1)
            ->where('res_fecha >=', $fecha_in)
            ->where('res_fecha <=', $fecha_out)
        ;

        if($depto) $this->db->where('dpi_id',$depto);
        
        $query = $this->db->get()->result_array();
        return $query;    
    }

    function get_solicitudes_finalizadas($depto=null, $fecha_in, $fecha_out)
    {

        $this->db->select()
            ->from('sol_solicitud')
            ->join('des_detalle_solicitud', 'des_sol_id = sol_id')
            ->join('dpi_departamento_interno', 'dpi_id = sol_dpi_id')
            ->join('esp_especifico', 'esp_id = des_esp_id')
            ->join('fon_fondo', 'fon_id = des_fon_id')
            ->where('des_ets_id',5) // Estado finalizado
            ->where('sol_fecha >=', $fecha_in)
            ->where('sol_fecha <=', $fecha_out)
        ;

        if($depto) $this->db->where('dpi_id',$depto);
        
        $query = $this->db->get()->result_array();
        return $query;    
    
    }

    public function get_categorias()
    {
        $this->db->select()
            ->from('sub_subcatalogo')
            ->join('cat_catalogo', 'cat_id = sub_cat_id')
            ->where('sub_estado',1)
        ;
        
        $query = $this->db->get()->result_array();
        return $query;    
    }

}


