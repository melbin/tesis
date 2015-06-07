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

	function get_parametro($parametro){
		$this->db->select();
		$this->db->from('par_parametro');
		$this->db->where('par_nombre',$parametro);
		$query = $this->db->get()->row_array();

		return $query['par_valor'];
	}

	function insertar_registro($tabla, $data){
		$this->db->insert($tabla, $data);
		$last_id = $this->db->insert_id();
    	return $last_id;
	}

	function get_tabla($tabla, $where){
		$this->db->where($where);
		$query = $this->db->get($tabla);

		return $query->result_array();
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


