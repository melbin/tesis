<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificos extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('masterpage');
		$this->load->model('regional_model');
		$this->load->model('sistema/sistema_model');
	}
	// Al hacer una peticion a esta pagina, es porque se quiere acceder al menu de sistema.
	// Por eso no es necesario jalar el id de sistema.

	public function index()
	{	
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$user_id	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "inventario/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Menu de Inventario";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	
	function especifico(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('esp_especifico');
			$crud->set_subject('Especifico');
			
			$columnas = array(
				'esp_nombre',
				'esp_concepto',
				'esp_codigo',
				'esp_fecha',
				'esp_lnt_id',
				'esp_estado'
				);

			$requeridos = array(
					'esp_nombre'
				);

			$alias = array(
					'esp_nombre'=>'Nombre',
					'esp_concepto'=>'Concepto',
					'esp_codigo'=>'Código',
					'esp_fecha'=>'Fecha',
					'esp_lnt_id'=>'Línea de trabajo',
					'esp_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->fields($columnas);
			$crud->display_as($alias);
			$crud->set_rules('esp_codigo', 'Código','trim|xss_clean|campo_unico[esp_especifico.esp_codigo]');	
			$crud->set_rules('esp_nombre', 'Nombre','trim|required|xss_clean|campo_unico[esp_especifico.esp_nombre]');
			$crud->set_relation('esp_lnt_id','lnt_linea_trabajo','lnt_nombre');

			$crud->field_type('esp_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('esp_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('esp_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['titulo']="Específicos";

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('bancos/especificos/especifico', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function linea_de_trabajo(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('lnt_linea_trabajo');
			$crud->set_subject('Línea de Trabajo');
			
			$columnas = array(
				'lnt_codigo',
				'lnt_nombre',
				'lnt_descripcion',
				'lnt_fecha',
				'lnt_estado'
				);

			$requeridos = array(
					'lnt_nombre'
				);

			$alias = array(
					'lnt_nombre'=>'Nombre',
					'lnt_descripcion'=>'Descripción',
					'lnt_codigo'=>'Código',
					'lnt_fecha'=>'Fecha',
					'lnt_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->fields($columnas);
			$crud->display_as($alias);
			$crud->set_rules('lnt_codigo', 'Código','trim|xss_clean|campo_unico[lnt_linea_trabajo.lnt_codigo]');	
			$crud->set_rules('lnt_nombre', 'Nombre','trim|required|xss_clean|campo_unico[lnt_linea_trabajo.lnt_nombre]');

			$crud->field_type('lnt_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('lnt_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('lnt_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['titulo']="Línea de Trabajo";

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('bancos/especificos/linea_trabajo', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	public function detalle_especifico()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['titulo']="Detalle de específicos";
			$data['vista_name'] = "bancos/especificos/detalle_especifico";

				// All your code goes here
			// Suponiendo en este momento que no habran axd_estado = 0.
			$data['esp_detalles'] = $this->regional_model->get_detalle_especificos();
			//die(print_r($data['esp_detalles'],true));
			// $det_array = array();
			// foreach ($esp_detalles as $key => $value) {
			// 	$det_array[] = $value['det_id'];
			// }
			// $data['monto_asignado'] = $this->regional_model->get_asignaciones($det_array);
			
			$data['html'] = $this->load->view('bancos/especificos/tabla_especifico',$data,true);
			$this->__cargarVista($data);
		}
	}

	public function detalle_especifico_editar($id_det)
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here

			$det_detalles = $this->regional_model->get_detalles_especificos($id_det);
			$detalles['det_asignaciones'] = $this->regional_model->get_asignaciones_detalle($id_det);
			$detalles['especifico'] = $det_detalles['esp_nombre'];
			$detalles['total'] = $det_detalles['det_saldo'];
			$detalles['det_detalles'] = $det_detalles;
			$data['tabla_asignaciones'] = $this->load->view('bancos/especificos/tabla_asignaciones',$detalles, true);
			// die(print_r($data['tabla_asignaciones'],true));

			$opciones="<option value='0' saldo='0'>Seleccione</option>";	
			$fondos = $this->regional_model->get_tabla('fon_fondo', array('fon_estado'=>1));
			$selected = '';
			foreach ($fondos as $key => $value) {
				if($det_detalles['det_fondo_id']==$value['fon_id']){
					$selected = 'selected';
				}
				$opciones .= "<option value=".$value['fon_id']." saldo=".$value['fon_cantidad']." $selected > ".$value['fon_nombre']."</option>";
				$selected='';
			}

			//$data['fondo'] = $this->regional_model->get_dropdown('fon_fondo', '{fon_nombre}','',array('fon_estado'=>1), null, '','fon_id',true);
			$data['det_detalles'] = $det_detalles;
		//	$data['det_asignaciones'] = $det_asignaciones;
			$data['fondo'] = $opciones;
			$data['especificos'] = $this->regional_model->get_dropdown('esp_especifico','{esp_nombre}','',array('esp_estado'=>1),$det_detalles['esp_id'],'','esp_id',true);		
			$data['departamentos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', '{dpi_nombre}','',array('dpi_estado'=>1),null, '','dpi_id', true);

			$data['titulo']="Detalle específico editar";
			$data['vista_name'] = "bancos/especificos/detalle_especifico_editar";

			$this->__cargarVista($data);	
		}
	}

	public function crear_detalle_especifico()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$opciones="<option value='0' saldo='0' selected>Seleccione</option>";	
			$fondos = $this->regional_model->get_tabla('fon_fondo', array('fon_estado'=>1));
			foreach ($fondos as $key => $value) {
				$opciones .= "<option value=".$value['fon_id']." saldo=".$value['fon_cantidad']."> ".$value['fon_nombre']."</option>";
			}


	
			$data['fondo'] = $opciones;
			$data['especificos'] = $this->regional_model->get_dropdown('esp_especifico','{esp_nombre}','',array('esp_estado'=>1),null,'','esp_id',true);		
			$data['departamentos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', '{dpi_nombre}','',array('dpi_estado'=>1),null, '','dpi_id', true);

			$data['titulo']="Detalle de específicos";
			$data['vista_name'] = "bancos/especificos/crear_detalle_especifico";			
			$this->__cargarVista($data);
		}
	}

	function guardar_detalle_especifico()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here.
			// die(print_r($_POST,true));

			$det_especifico = array(
					'det_esp_id' 		=> $this->input->post('especifico'),
					'det_fondo_id' 		=> $this->input->post('fondo'),
					'det_saldo_votado' 	=> $this->input->post('saldo'),
					'det_saldo'			=> $this->input->post('saldo'),
					'det_descripcion'	=> $this->input->post('descripcion'),
					'det_fecha' 		=> date('Y-m-d', strtotime($_POST['fecha_registro'])),
					'det_estado' 		=> 1,
					'det_usu_mod' 		=> $this->tank_auth->get_user_id(),
					'det_fecha_mod' 	=> date('Y-m-d H:i:s')
				);
			$det_esp_id = $this->regional_model->insertar_registro('det_detalle_especifico', $det_especifico);

			// Actualizar fondo
			$cantidad_fondo = $this->sistema_model->get_campo('fon_fondo','fon_cantidad',array('fon_id'=>$_POST['fondo']));
			$diferencia = floatval($cantidad_fondo) - floatval($_POST['saldo']);

			$array = array(
						'fon_cantidad' 	=> (is_numeric($diferencia))? $diferencia:'0.00',
						'fon_usu_mod'  	=> $this->tank_auth->get_user_id(),
						'fon_fecha_mod' => date('Y-m-d H:i:s')
					);
			$row_afected = $this->sistema_model->actualizar_registro('fon_fondo', $array, array('fon_id'=>$_POST['fondo']));	
			
			// Crear el movimiento financiero
			$movimiento = array(
					'fin_fondo_id' => $this->input->post('fondo'),
					'fin_pro_id'   => 4,
					'fin_cantidad' => $this->input->post('saldo'),
					'fin_fecha'	   => date('Y-m-d H:i:s'),
					'fin_estado'   => 1,
					'fin_usu_mod'  => $this->tank_auth->get_user_id(),
					'fin_fecha_mod'=> date('Y-m-d H:i:s')
				);
			$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

			if($mov_id>0){
				// Insertar el detalle Financiero
				$det_financiero = array(
					'fid_fin_id'	  => $mov_id,
					'fid_esp_id'	  => $this->input->post('especifico'),
					'fid_cantidad'	  => $this->input->post('saldo'),
					'fid_descripcion' => $this->input->post('descripcion'),
					'fid_fecha'		  => date('Y-m-d H:i:s'),
					'fid_estado'	  => 1,
					'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
					'fid_fecha_mod'	  => date('Y-m-d H:i:s')
				);
				$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);
			}

			// Realizar las asignaciones
			$cantidad = $this->input->post('cantidad_depto');

			if($det_esp_id>0){
				foreach ($this->input->post('departamentos') as $key => $value) {
					$detalle = array(
						'axd_det_id' 	=> $det_esp_id,
						'axd_depto_id'	=> $value,
						'axd_cantidad'	=> $cantidad[$key],
						'axd_fecha'		=> date('Y-m-d H:i:s'),
						'axd_estado'	=> 1,
						'axd_usu_mod'	=> $this->tank_auth->get_user_id(),
						'axd_fecha_mod'	=> date('Y-m-d H:i:s')
					);
					$this->regional_model->insertar_registro('axd_asignacionxdetalle_especifico', $detalle);
				}	
			}

			if($det_esp_id>0)
			{
				$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Detalle ingresado con exito.");
			} else
			{
				$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"El detalle no pudo ser ingresado.");
			}
		
			$this->session->set_flashdata($alerta);       
			redirect('bancos/especificos/detalle_especifico');

		} // Cierre Else
	}	// Cierre de Metodo

	function guardar_detalle_especifico_editar()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here.
		//	die(var_dump($_POST));	
			$get_detalles = $this->regional_model->get_tabla('det_detalle_especifico', array('det_id'=>$_POST['det_id'], 'det_estado'=>1));
			//die(print_r($get_detalles[0],true));
			$saldo = ($get_detalles[0]['det_saldo_ejecutado']>0)? floatval($this->input->post('saldo')) + floatval($get_detalles[0]['det_saldo_ejecutado']) : $this->input->post('saldo');
			$det_especifico = array(
					'det_saldo'			=> $saldo,
					'det_descripcion'	=> $this->input->post('descripcion'),
					'det_estado' 		=> 1,
					'det_usu_mod' 		=> $this->tank_auth->get_user_id(),
					'det_fecha_mod' 	=> date('Y-m-d H:i:s')
				);
			$det_esp_id = $this->regional_model->actualizar_registro('det_detalle_especifico', $det_especifico, array('det_id'=>$get_detalles[0]['det_id']));

			// Actualizar fondo
			$fondo_saldo = 0;

			if(floatval($get_detalles[0]['det_saldo']) > floatval($saldo)){
				$fondo_saldo = (floatval($get_detalles[0]['det_saldo']) - floatval($saldo));
			} else 
			if(floatval($get_detalles[0]['det_saldo']) < floatval($saldo)){
				$fondo_saldo = (floatval($saldo) - floatval($get_detalles[0]['det_saldo']))*(-1);
			}	
			
			$cantidad_fondo = $this->sistema_model->get_campo('fon_fondo','fon_cantidad',array('fon_id'=>$get_detalles[0]['det_fondo_id']));
			$diferencia = floatval($cantidad_fondo) + floatval($fondo_saldo);
			
			$array = array(
						'fon_cantidad' 	=> (is_numeric($diferencia))? number_format($diferencia,2,'.',''):'0.00',
						'fon_usu_mod'  	=> $this->tank_auth->get_user_id(),
						'fon_fecha_mod' => date('Y-m-d H:i:s')
					);
			
			$row_afected = $this->sistema_model->actualizar_registro('fon_fondo', $array, array('fon_id'=>$get_detalles[0]['det_fondo_id']));	
			
			// Crear el movimiento financiero
			if($fondo_saldo>0){
			$movimiento = array(
					'fin_fondo_id' => $get_detalles[0]['det_fondo_id'],
					'fin_pro_id'   => ($fondo_saldo>0)? 4:3,
					'fin_cantidad' => number_format($fondo_saldo,2),
					'fin_fecha'	   => date('Y-m-d H:i:s'),
					'fin_estado'   => 1,
					'fin_usu_mod'  => $this->tank_auth->get_user_id(),
					'fin_fecha_mod'=> date('Y-m-d H:i:s')
				);
			$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

			if($mov_id>0){
				// Insertar el detalle Financiero
				$det_financiero = array(
					'fid_fin_id'	  => $mov_id,
					'fid_esp_id'	  => $get_detalles[0]['det_esp_id'],
					'fid_cantidad'	  => number_format($fondo_saldo,2),
					'fid_descripcion' => 'Movimiento debido a edición del detalle',
					'fid_fecha'		  => date('Y-m-d H:i:s'),
					'fid_estado'	  => 1,
					'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
					'fid_fecha_mod'	  => date('Y-m-d H:i:s')
				);
				$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);
			}
			} // End if fondo_Saldo > 0

			// Realizar las asignaciones
			// Arreglar desde aca

			// asignacion = axd_id
			// cantidades = axd_cantidad

			$cantidades = $this->input->post('cantidad_depto');
			$registros_base = array();
			$asignacion = $_POST['axd_asignaciones'];
			$departamentos = $_POST['departamentos'];
		//	die(print_r($_POST,true));		

			// Actualizar los registros existentes
			if(!empty($_POST['axd_asignaciones'])){

				$get_detalle_asignacion = $this->db->query('SELECT axd_id FROM axd_asignacionxdetalle_especifico WHERE axd_det_id = '.$_POST["det_id"].' AND axd_estado = 1')->result_array();
				
				foreach ($get_detalle_asignacion as $row => $field) {
					array_push($registros_base, $field['axd_id']);
				}
			} 

			$array_mayor = (count($_POST['departamentos']) > count($registros_base))? $_POST['departamentos']: $registros_base;
			
			foreach ($array_mayor as $key => $value) {

			// Buscar en get_detalle_asignacion el $value, si existe, editarlo, de lo contrario. Crearlo.
			$existe = isset($registros_base[$key])? in_array($registros_base[$key], $asignacion):false;
				
				if($existe){
					
					$indice = array_search($registros_base[$key], $asignacion);

					$detalle = array(
						'axd_cantidad'  => isset($cantidades[$indice])? $cantidades[$indice]:0,
						'axd_usu_mod'	=> $this->tank_auth->get_user_id(),
						'axd_fecha_mod'	=> date('Y-m-d H:i:s')
					);

					$this->sistema_model->actualizar_registro('axd_asignacionxdetalle_especifico', $detalle, array('axd_id'=>$registros_base[$key]));
				} else {

					if(isset($registros_base[$key])){
						//	Eliminar registro de la base
						$this->regional_model->borrar_registro('axd_asignacionxdetalle_especifico', array('axd_id'=> $registros_base[$key]));

					} else {

						// Agregalo como nuevo
						$detalle = array(
								'axd_cantidad'  => isset($cantidades[$key])? $cantidades[$key]:0,
								'axd_det_id'	=> $get_detalles[0]['det_id'],
								'axd_depto_id'  => $departamentos[$key],
								'axd_fecha'		=> date('Y-m-d H:i:s'),
								'axd_estado'	=> 1,
								'axd_usu_mod'	=> $this->tank_auth->get_user_id(),
								'axd_fecha_mod' => date('Y-m-d H:i:s')
							);
					
						$this->regional_model->insertar_registro('axd_asignacionxdetalle_especifico', $detalle);		
					}
				}			
			} // End foreach

			if($det_esp_id>0)
			{
				$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Detalle ingresado con exito.");
			} else
			{
				$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"El detalle no pudo ser ingresado.");
			}
		
			$this->session->set_flashdata($alerta);       
			redirect('bancos/especificos/detalle_especifico');

		} // Cierre Else
	}	// Cierre de Guardar detalle especifico editar

	function asignar_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			if($_POST){
				//die(print_r($_POST,true));
				$cantidad = $this->input->post('cantidad');
				$fondo_valor = $this->sistema_model->get_campo('fon_fondo','fon_cantidad', array('fon_id'=>$_POST['fondo'], 'fon_estado'=>1));
				$total = floatval($fondo_valor) -  floatval($cantidad);
				if($total>0){
					$array_fondo = array(
						'fon_cantidad'	=> $total,
						'fon_usu_mod'	=> $this->tank_auth->get_user_id(),
						'fon_fecha_mod'	=> date('Y-m-d H:i:s')
						);
					$this->sistema_model->actualizar_registro('fon_fondo',$array_fondo, array('fon_id'=>$_POST['fondo']));
				}

				$existe_detalle = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$_POST['especifico'], 'det_fondo_id'=>$_POST['fondo'], 'det_estado'=>1));
				
				if(count($existe_detalle)>0){
					$total = floatval($existe_detalle['det_saldo']) + floatval($cantidad);
					$det_array = array(
						'det_saldo' 		=> $total,
						'det_descripcion' 	=> $_POST['descripcion'],
						'det_usu_mod'		=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'		=> date('Y-m-d H:i:s'),
				 	);
					$this->sistema_model->actualizar_registro('det_detalle_especifico',$det_array, array('det_id'=>$existe_detalle['det_id']));
					$alerta=array('tipo_alerta'=> 'alert','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Se editó el detalle correctamente.<br>Le sugerimos que reasigne fondos a los departamentos.");
				} else {
					$det_array = array(
						'det_esp_id'		=> $_POST['especifico'],
						'det_fondo_id'		=> $_POST['fondo'],
						'det_saldo_votado'	=> $cantidad,
						'det_saldo'			=> $cantidad,
						'det_descripcion' 	=> $_POST['descripcion'],
						'det_estado'		=> 1,
						'det_fecha'			=> date('Y-m-d H:i:s'),
						'det_usu_mod'		=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'		=> date('Y-m-d H:i:s')
				 	);
				 	$this->regional_model->insertar_registro('det_detalle_especifico', $det_array);
				 	$alerta=array('tipo_alerta'=> 'alert','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Se creó el detalle con éxito. <br>Favor asignar fondos a los departamentos.");
				}

				// Crear el movimiento financiero
				$movimiento = array(
						'fin_fondo_id' => $this->input->post('fondo'),
						'fin_pro_id'   => 3,
						'fin_cantidad' => $cantidad,
						'fin_fecha'	   => date('Y-m-d H:i:s'),
						'fin_estado'   => 1,
						'fin_usu_mod'  => $this->tank_auth->get_user_id(),
						'fin_fecha_mod'=> date('Y-m-d H:i:s')
					);
				$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

				if($mov_id>0){
					// Insertar el detalle Financiero
					$det_financiero = array(
						'fid_fin_id'	  => $mov_id,
						'fid_esp_id'	  => $this->input->post('especifico'),
						'fid_cantidad'	  => $cantidad,
						'fid_descripcion' => $this->input->post('descripcion'),
						'fid_fecha'		  => date('Y-m-d H:i:s'),
						'fid_estado'	  => 1,
						'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
						'fid_fecha_mod'	  => date('Y-m-d H:i:s')
					);
					$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);
				}				

				$this->session->set_flashdata($alerta);       
				redirect('bancos/especificos/detalle_especifico');
				
			} else {
				$opciones="<option value='0' saldo='0' selected>Seleccione</option>";	
				$fondos = $this->regional_model->get_tabla('fon_fondo', array('fon_estado'=>1));
				foreach ($fondos as $key => $value) {
					$opciones .= "<option value=".$value['fon_id']." saldo=".$value['fon_cantidad']."> ".$value['fon_nombre']."</option>";
				}	
				
				$data['fondo'] = $opciones;			
				$data['especificos'] = $this->regional_model->get_dropdown('esp_especifico','{esp_nombre}','',array('esp_estado'=>1),null,'','esp_id',true);		

				$data['titulo']="Asignación de fondos";
				$data['vista_name'] = "bancos/especificos/asignar_fondos";			
				$this->__cargarVista($data);
			}
		}
	} // End asignar_fondos


	public function traspaso_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if($_POST){

				// Actualizar el det_detalle del especifico origen
				$cantidad = $this->input->post('cantidad');
				$det_saldo = $this->sistema_model->get_campo('det_detalle_especifico','det_saldo', array('det_esp_id'=>$_POST['especifico_origen'], 'det_estado'=>1));
				$total = floatval($det_saldo) -  floatval($cantidad);
				
				if($total>=0){
					$array_detalle = array(
						'det_saldo'	=> $total,
						'det_usu_mod'	=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'	=> date('Y-m-d H:i:s')
						);
					$this->sistema_model->actualizar_registro('det_detalle_especifico',$array_detalle, array('det_esp_id'=>$_POST['especifico_origen']));
				}

				// Crear o Actualizar el det del especifico destino
				$det_destino = $this->sistema_model->get_registro('det_detalle_especifico', array('det_esp_id'=>$_POST['especifico_destino'], 'det_estado'=>1));

				if(count($det_destino)==0){
					// Codigo para Crear el detalle
					$det_array = array(
						'det_esp_id'		=> $_POST['especifico_destino'],
						'det_fondo_id'		=> $_POST['fondo'],
						'det_saldo_votado'	=> $cantidad,
						'det_saldo'			=> $cantidad,
						// 'det_descripcion' 	=> $_POST['descripcion'],
						'det_fecha'			=> date('Y-m-d H:i:s'),
						'det_estado'		=> 1,
						'det_usu_mod'		=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'		=> date('Y-m-d H:i:s')
				 	);
				 	$this->regional_model->insertar_registro('det_detalle_especifico', $det_array);

				} else {
					// Codigo para Actualizar el detalle
					$total = floatval($det_destino['det_saldo']) + floatval($cantidad);
					$det_array = array(
						'det_saldo' 		=> $total,
						// 'det_descripcion' 	=> $_POST['descripcion'],
						'det_usu_mod'		=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'		=> date('Y-m-d H:i:s'),
				 	);

					$this->sistema_model->actualizar_registro('det_detalle_especifico',$det_array, array('det_esp_id'=>$det_destino['det_esp_id']));
				}

				// Crear el movimiento financiero
				$movimiento = array(
						'fin_fondo_id' => $this->input->post('fondo'),
						'fin_pro_id'   => 7,
						'fin_cantidad' => $cantidad,
						'fin_fecha'	   => date('Y-m-d H:i:s'),
						'fin_estado'   => 1,
						'fin_usu_mod'  => $this->tank_auth->get_user_id(),
						'fin_fecha_mod'=> date('Y-m-d H:i:s')
					);
				$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

				if($mov_id>0){
					// Insertar el detalle Financiero

					$detalles = array('0'=>$_POST['especifico_origen'], '1'=>$_POST['especifico_destino']);
					foreach ($detalles as $key => $value) {
						$det_financiero = array(
						'fid_fin_id'	  => $mov_id,
						'fid_esp_id'	  => $value,
						'fid_cantidad'	  => ($key==0)? $cantidad*(-1):$cantidad,
						'fid_descripcion' => $this->input->post('descripcion'),
						'fid_fecha'		  => date('Y-m-d H:i:s'),
						'fid_estado'	  => 1,
						'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
						'fid_fecha_mod'	  => date('Y-m-d H:i:s')
					);
					$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);	
					}	
				}

			if($mov_id>0)
			{
				$alerta=array('tipo_alerta'=> 'alert','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Traspaso realizado con éxito. <br>Le suguerimos redistribuir los nuevos fondos.");		
			} else
			{
				$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"El detalle no pudo ser ingresado.");
			}				

				$this->session->set_flashdata($alerta);       
				redirect('bancos/especificos/detalle_especifico');

			} else {
			
			$data['titulo']="Traspaso de Fondos";
			$data['vista_name'] = "bancos/especificos/traspaso_fondos";

			$opciones="<option value='0' saldo='0' selected>Seleccione</option>";	
				$fondos = $this->regional_model->get_tabla('fon_fondo', array('fon_estado'=>1));
				foreach ($fondos as $key => $value) {
					$opciones .= "<option value=".$value['fon_id']." saldo=".$value['fon_cantidad']."> ".$value['fon_nombre']."</option>";
				}	
				
				$data['fondo'] = $opciones;			
				$data['especifico_destino'] = $this->regional_model->get_dropdown('esp_especifico','{esp_nombre}','',array('esp_estado'=>1),null,'','esp_id',true);		

			$this->__cargarVista($data);

			}
		}		
	} // End traspaso_fondos

	function congelar_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if($_POST){
			//	die(print_r($_POST,true));

				/* 
					-Modificar el det_detalle_especifico
					-Con el esp_id y el fon_id obtengo el det 
					- Modificare el det_saldo_congelado
				*/
				$esp_id = $this->input->post('especifico');	
				$fondo_id = $this->input->post('fondo');
				$cantidad = $this->input->post('cantidad');
				
				$det_especifico = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$esp_id, 'det_fondo_id'=>$fondo_id, 'det_estado'=>1));

				$total_congelado = floatval(!empty($det_especifico['det_saldo_congelado'])?$det_especifico['det_saldo_congelado']:0) + floatval($cantidad);
				$this->sistema_model->actualizar_registro('det_detalle_especifico',array('det_saldo_congelado'=>$total_congelado), array('det_id'=>$det_especifico['det_id']));

				// Agregar registro a foc_fondo_congelado
				$foc_array = array(
					'foc_det_id'		=> $det_especifico['det_id'],
					'foc_cantidad'		=> (!empty($cantidad))? $cantidad:0,
					'foc_descripcion' 	=> $this->input->post('descripcion'),
					'foc_fecha'			=> date('Y-m-d H:i:s'),
					'foc_estado'		=> 1,
					'foc_usu_mod'		=> $this->tank_auth->get_user_id(),
					'foc_fecha_mod'		=> date('Y-m-d H:i:s')
					);

				$this->regional_model->insertar_registro('foc_fondo_congelado', $foc_array);	

				// Cambiar las solicitudes que hayan sido echas con este fondo y este especifico a estado 2.
				$this->sistema_model->actualizar_registro('des_detalle_solicitud',array('des_estado'=>2), array('des_fon_id'=>$fondo_id, 'des_esp_id'=>$esp_id));

				// Obtener las solicitudes del des
				$solicitudes = $this->regional_model->get_tabla('des_detalle_solicitud', array('des_fon_id'=>$fondo_id, 'des_esp_id'=>$esp_id));
				if($solicitudes>0){
					foreach ($solicitudes as $key => $value) {
					$array = array(
						'soc_sol_id'	=> $value['des_sol_id'],
						'soc_fecha'		=> date('Y-m-d H:i:s'),
						'soc_descripcion'	=> $this->input->post('descripcion'),
						'soc_estado'		=> 1,
						'soc_usu_mod'		=> $this->tank_auth->get_user_id(),
						'soc_fecha_mod'		=> date('Y-m-d H:i:s')
						);

					$this->regional_model->insertar_registro('soc_solicitud_congelada', $array);	
					}	
				}
				
				// Crear el movimiento financiero
				$movimiento = array(
						'fin_fondo_id' => $fondo_id,
						'fin_pro_id'   => 5,
						'fin_cantidad' => $cantidad,
						'fin_fecha'	   => date('Y-m-d H:i:s'),
						'fin_estado'   => 1,
						'fin_usu_mod'  => $this->tank_auth->get_user_id(),
						'fin_fecha_mod'=> date('Y-m-d H:i:s')
					);

				$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

				if($mov_id>0){
					// Insertar el detalle Financiero
						$det_financiero = array(
						'fid_fin_id'	  => $mov_id,
						'fid_esp_id'	  => $esp_id,
						'fid_cantidad'	  => $cantidad,
						'fid_descripcion' => $this->input->post('descripcion'),
						'fid_fecha'		  => date('Y-m-d H:i:s'),
						'fid_estado'	  => 1,
						'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
						'fid_fecha_mod'	  => date('Y-m-d H:i:s')
					);
					$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);	
					}	
				// Generar la alerta
				if($solicitudes>0)
				{
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Proceso realizado con éxito.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La transacción no pudo ser efectuada.");
				}				

				$this->session->set_flashdata($alerta);       
				redirect('bancos/bancos');
				

				
				// 0 a N registros en soc_solicitud_congelada
				// Actualizar el des_detalle solicitud de cada solicitud congelada
				// Hacer los registros de Movimiento


			} else {
				// All your code goes here
			$data['titulo']="Congelar fondos";
			$data['vista_name'] = "bancos/especificos/congelar_fondos";
			$data['fondo'] = $this->regional_model->get_dropdown('fon_fondo','{fon_nombre}','',array('fon_estado'=>1),null,'','fon_id',true);			
			$this->__cargarVista($data);
			}
		}		
	}

	function get_especifico_fondo()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			//die(print_r("En progreso",true));
			$fondo_id = $this->input->post('fondo');
			$reactivar = $this->input->post('reactivar');
			$saldo_minimo = $this->regional_model->get_parametro('traspasar_saldo');
			if(!empty($reactivar) && $reactivar==1){
				$this->db->where('det_saldo_congelado >',0);
			}
			$especificos = $this->regional_model->get_especificos($fondo_id, $saldo_minimo);

			$opciones="<option value='0' saldo='0' selected>Seleccione</option>";	

			foreach ($especificos as $key => $value) {
					$saldo = floatval($value['det_saldo']) - floatval($value['det_saldo_ejecutado']) - floatval(!empty($value['det_saldo_congelado'])? $value['det_saldo_congelado']:0);
					$opciones .= "<option value=".$value['esp_id']." saldo=".$saldo." > ".$value['esp_nombre']."</option>";
				}	
			
			$result = array('especificos_origen'=>$opciones);	
			echo json_encode($result);
		}
	}

	function get_departamento_asignaciones()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$esp_id = $this->input->post('esp_id');
			$fondo_id = $this->input->post('fondo_id');
			$dpi_id = $this->input->post('dpi_id');
			$selected = '';

			$det_registro = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$esp_id, 'det_fondo_id'=>$fondo_id, 'det_estado'=>1));
			if(floatval($det_registro['det_saldo_congelado'])>1){
				echo json_encode(array('congelado'=>1));
			} else {

				$asignaciones = $this->regional_model->get_asignaciones_detalle($det_registro['det_id']);
				//die(print_r($asignaciones,true));
				$option = "<option value='0'>Seleccione</option>";
				foreach ($asignaciones as $key => $value) {
				if(!empty($dpi_id) && $dpi_id>0 && $dpi_id == $value['axd_depto_id']){
					$selected='selected';
				}
					$option .= "<option value='".$value['axd_depto_id']."' axd_id='".$value['axd_id']."' ".$selected.">".strtolower($value['dpi_nombre'])."</option>";
					$selected='';
				}
				echo json_encode(array('depto_asignaciones'=>$option));
			}
		}
	}

	function get_saldo_dpi_asignado()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$axd_id = $this->input->post('axd_id');
			$axd_registro = $this->sistema_model->get_registro('axd_asignacionxdetalle_especifico', array('axd_id'=>$axd_id, 'axd_estado'=>1));
			$total = floatval($axd_registro['axd_cantidad']);
			if(!empty($axd_registro['axd_reserva'])){
				$total -= floatval($axd_registro['axd_reserva']); 
			}
			$respuesta = array(
				'monto' => $total,
				'axd_id'=> $axd_registro['axd_id']
				);			
			echo json_encode($respuesta);
		}
	}


	function get_especifico_detalle()
	{
		$id_esp = $this->input->post('id_esp');
		$id_fondo = $this->input->post('id_fondo');
		$tabla_detalles = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$id_esp, 'det_fondo_id'=>$id_fondo, 'det_estado'=>1));

		$resultado = array(
			'det_saldo_votado'	  => $tabla_detalles['det_saldo_votado'],
			'det_saldo'			  => ($tabla_detalles['det_saldo'] - $tabla_detalles['det_saldo_ejecutado'] - $tabla_detalles['det_saldo_congelado']),
			'det_saldo_congelado' => $tabla_detalles['det_saldo_congelado'],
			);
		echo json_encode($resultado);
	}

	function reactivar_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if($_POST){
				//die(print_r($_POST));

				$esp_id = $this->input->post('especifico');	
				$fondo_id = $this->input->post('fondo');
				$cantidad = $this->input->post('cantidad');
				
				$det_especifico = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$esp_id, 'det_fondo_id'=>$fondo_id, 'det_estado'=>1));

				$total_congelado = floatval(!empty($det_especifico['det_saldo_congelado'])?$det_especifico['det_saldo_congelado']:0) - floatval($cantidad);
				$det_id = $this->sistema_model->actualizar_registro('det_detalle_especifico',array('det_saldo_congelado'=>$total_congelado), array('det_id'=>$det_especifico['det_id']));

				if($total_congelado<1){
					// Todas las actualizaciones aca
					// Agregar registro a foc_fondo_congelado
					$foc_array = array(
						'foc_estado'		=> 0,
						'foc_usu_mod'		=> $this->tank_auth->get_user_id(),
						'foc_fecha_mod'		=> date('Y-m-d H:i:s')
					);

				$this->sistema_model->actualizar_registro('foc_fondo_congelado', $foc_array, array('foc_det_id'=>$det_especifico['det_id']));	

				// Cambiar las solicitudes que hayan sido echas con este fondo y este especifico a estado 1= Activas.
				$this->sistema_model->actualizar_registro('des_detalle_solicitud',array('des_estado'=>1), array('des_fon_id'=>$fondo_id, 'des_esp_id'=>$esp_id));

				// Obtener las solicitudes del des_detalle_solicitud
				$solicitudes = $this->regional_model->get_tabla('des_detalle_solicitud', array('des_fon_id'=>$fondo_id, 'des_esp_id'=>$esp_id));

				if(count($solicitudes)>0){
					foreach ($solicitudes as $key => $value) {
					$soc_array = array(
						'soc_estado' => 0,
						'soc_usu_mod' => $this->tank_auth->get_user_id(),
						'soc_fecha_mod' => date('Y-m-d H:i:s')
						);
					$this->sistema_model->actualizar_registro('soc_solicitud_congelada', $soc_array, array('soc_sol_id'=>$value['des_sol_id']));	
					}
				 }	
				} // Total devuelto

				// Crear el movimiento financiero
				$movimiento = array(
					'fin_fondo_id' => $fondo_id,
					'fin_pro_id'   => 6,
					'fin_cantidad' => $cantidad,
					'fin_fecha'	   => date('Y-m-d H:i:s'),
					'fin_estado'   => 1,
					'fin_usu_mod'  => $this->tank_auth->get_user_id(),
					'fin_fecha_mod'=> date('Y-m-d H:i:s')
				);

				$mov_id = $this->regional_model->insertar_registro('fin_financiero_movimiento', $movimiento);

				if($mov_id>0){
				// Insertar el detalle Financiero
					$det_financiero = array(
						'fid_fin_id'	  => $mov_id,
						'fid_esp_id'	  => $esp_id,
						'fid_cantidad'	  => $cantidad,
						'fid_descripcion' => $this->input->post('descripcion'),
						'fid_fecha'		  => date('Y-m-d H:i:s'),
						'fid_estado'	  => 1,
						'fid_usu_mod'	  => $this->tank_auth->get_user_id(),
						'fid_fecha_mod'	  => date('Y-m-d H:i:s')
					);
					$this->regional_model->insertar_registro('fid_financiero_detalle_mov', $det_financiero);	
				}	

				// Generar la alerta
				if($det_id>0)
				{
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Proceso realizado con éxito.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La transacción no pudo ser efectuada.");
				}				

				$this->session->set_flashdata($alerta);       
				redirect('bancos/bancos');

			} else {
				// All your code goes here
				$data['titulo']="Reactivar fondos";
				$data['vista_name'] = "bancos/especificos/reactivar_fondos";
				$data['fondo'] = $this->regional_model->get_dropdown('fon_fondo','{fon_nombre}','',array('fon_estado'=>1),null,'','fon_id',true);			
				$this->__cargarVista($data);
			}
		}
	}

	function verificar_detalles()
	{
		$id_fondo = $_POST['id_fondo'];
		$id_esp	  = $_POST['id_esp'];
		$det_id = $this->sistema_model->get_registro('det_detalle_especifico',array('det_esp_id'=>$id_esp, 'det_fondo_id'=>$id_fondo, 'det_estado'=>1));
		
		if(!empty($det_id)){
			echo json_encode(array('existe'=>1));
		} else {
			echo json_encode(array('existe'=>0));
		}
	}

	
	function __cargarVista($data=0)
	{	
		// Datos generales de la pagina	
		$data['menu_sistema']=true;
		$user_id	= $this->tank_auth->get_user_id();
		$data['logo'] = $this->regional_model->get_parametro("logo");
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>5));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',5, $user_id);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */