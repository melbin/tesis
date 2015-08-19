<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abastecimiento extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('masterpage');
		$this->load->model('regional_model');
		$this->load->model('sistema/sistema_model');
		$this->load->model('inventario/productos_model','pro_model');
		$this->load->library('session');
	}
	// Al hacer una peticion a esta pagina, es porque se quiere acceder al menu de sistema.
	// Por eso no es necesario jalar el id de sistema.

	public function index()
	{	
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "sistema/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Menu de Sistema";
			$data['menu_sistema']=true;

		 	// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	function salida_de_articulos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		
		} else {

			if($_POST){
				// print_r($_POST); exit();

				$sar_registro = $this->input->post('sar_registro');  // Id del articulo
				$cantidad_inv = $this->input->post('cantidad');
				$cantidad_salida = $this->input->post('cantidad_salida');
				$descripcion = $this->input->post('descripcion'); // Puede venir Null

				if(!empty($sar_registro)) {
				// Registrar el movimiento en moi_movimiento_inv
					$movimiento = array(
							'moi_ali_id'	=> 	$this->input->post('bodega'),
							'moi_pro_id'	=> 	$this->input->post('salida'),
							'moi_fecha'		=> 	date('Y-m-d H:i:s'),
							'moi_estado'	=>	1,
							'moi_fecha_mod' =>	date('Y-m-d H:i:s'),
							'moi_usu_mod'	=>	$this->tank_auth->get_user_id()
						);

					$moi_id = $this->regional_model->insertar_registro('moi_movimiento_inv', $movimiento);
				} // End if 

				foreach ($sar_registro as $key => $value) {

					// Realizar salida de Inventario.
					$sar_actualizar = array(
						'sar_cantidad' 	=> $cantidad_inv[$key],
						'sar_estado' 	=> ($cantidad_inv[$key]==0)? 0:1,
						'sar_usu_mod'	=> $this->tank_auth->get_user_id(),
						'sar_fecha_mod' => date('Y-m-d H:i:s', strtotime($_POST['fecha_salida'].date('H:i:s'))),
					);	
				
				$row_afected = $this->pro_model->actualizar_registro('sar_saldo_articulo', $sar_actualizar, $value);	
				

				// Guardar detalle
				$detalle = array(
						'dee_sar_id' => $value, // Este es el id del registro en sar_saldo_articulo
						'dee_moi_id' => $moi_id,
						'dee_cantidad' => $cantidad_salida[$key],
						'dee_descripcion' => $descripcion[$key],
						'dee_estado' => 1,
						'dee_fecha_mod' => date("Y-m-d H:i:s"),
						'dee_usu_mod' => $this->tank_auth->get_user_id()
					);

				$detalle_id = $this->regional_model->insertar_registro('dee_detalle_mov', $detalle);

				}

				if($row_afected>0 && $moi_id>0 && $detalle_id>0)
				{
					$alerta=array('registro'=>$registro,'tipo_alerta'=> 'success','titulo_alerta'=>"Proceso Exitoso",'texto_alerta'=>"Salida Exitosa.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Salida No efectuada",'texto_alerta'=>"La salida no pudo ser efectuada.");
				}
				
				$this->session->set_flashdata($alerta);       
				redirect('home/abastecimiento/salida_de_articulos');								

			} else
			{
				$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "abastecimiento/salida_de_articulos";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Salida de artículos";

			// Obtenemos los valores de los Select
			$data['articulos'] = $this->regional_model->get_dropdown('ali_almacen_inv','ali_nombre','',array('ali_estado'=>1),null,'','ali_id',true);
			$data['procesos'] = $this->regional_model->get_dropdown('pro_proceso','pro_nombre','',array('pro_salida'=>1,'pro_estado'=>1),null,'','pro_id',true);
			$data['productos'] = $this->regional_model->get_dropdown('pro_producto','{pro_codigo}::{pro_nombre}','',array('pro_estado'=>1),null,'','pro_id',true);
			$data['categoria'] = $this->regional_model->get_dropdown('cat_catalogo','{cat_nombre}::{cat_codigo}','',array('cat_estado'=>1),null,'','cat_id',true);

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
			}
		}	
	}

	function entrada_de_articulos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		
		} else {

			if($_POST){
				//die(print_r($_POST)); 
				// Insertarmos en sar_saldo_articulo 

				$productos = $this->input->post('productos');
				$precios = $this->input->post('precios');
				$cantidad = $this->input->post('cantidad');
				$descripcion = $this->input->post('descripcion'); // Puede venir Null

				// Registramos el movimiento en moi_movimiento_inv
				if(!empty($productos)){
					$movimiento = array(
						'moi_ali_id' => ($_POST['bodega']!=0)? $_POST['bodega'] : NULL,
						'moi_prv_id' => ($_POST['proveedor']!=0)? $_POST['proveedor']: NULL,
						'moi_pro_id' => ($_POST['entrada']!=0)? $_POST['entrada']: NULL,
						'moi_fecha' => 	date("Y-m-d H:i:s", strtotime($_POST['fecha_registro'].date('H:i:s'))),
						'moi_estado' => 1,
						'moi_fecha_mod' => date("Y-m-d H:i:s"),
						'moi_usu_mod' => $this->tank_auth->get_user_id()
					);

				$moi_id = $this->regional_model->insertar_registro('moi_movimiento_inv', $movimiento);	
				} // End if
				
				foreach ($productos as $key => $value) {
					$articulos = array(
						'sar_pro_id' 	=> $value,
						'sar_ali_id' 	=> ($_POST['bodega']!=0)? $_POST['bodega'] : NULL,
						'sar_cantidad' 	=> $cantidad[$key],
						'sar_precio' 	=> $precios[$key],
						'sar_fecha' 	=>  date("Y-m-d H:i:s", strtotime($_POST['fecha_registro'].date('H:i:s'))),
						'sar_estado' 	=> 1,
						'sar_usu_mod' 	=> $this->tank_auth->get_user_id(),
						'sar_fecha_mod' => date("Y-m-d H:i:s")
					);

				$sar_id = $this->regional_model->insertar_registro('sar_saldo_articulo', $articulos);

				$detalle = array(
						'dee_sar_id' => $sar_id,
						'dee_moi_id' => $moi_id,
						'dee_cantidad' => $cantidad[$key],
						'dee_descripcion' => ($descripcion[$key] !='')? $descripcion[$key]: NULL,
						'dee_estado' => 1,
						'dee_fecha_mod' => date("Y-m-d H:i:s"),
						'dee_usu_mod' => $this->tank_auth->get_user_id()
					);

				$detalle_id = $this->regional_model->insertar_registro('dee_detalle_mov', $detalle);

				} // End foreach


				if($sar_id>0 && $moi_id>0 && $detalle_id>0)
				{
					$alerta=array('registro'=>$registro,'tipo_alerta'=> 'success','titulo_alerta'=>"Registro ingresado",'texto_alerta'=>"El registro se ha ingresado correctamente.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Registro no ingresado",'texto_alerta'=>"El registro no pudo ser ingresado.");
				}
				
				$this->session->set_flashdata($alerta);       
				redirect('home/abastecimiento/entrada_de_articulos');				

			} else {

			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "abastecimiento/entrada_de_articulos";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Entrada de artículos";

			// Obtenemos los valores de los Select
			$data['articulos'] = $this->regional_model->get_dropdown('ali_almacen_inv','ali_nombre','',array('ali_estado'=>1),null,'','ali_id',true);
			$data['proveedores'] = $this->regional_model->get_dropdown('prv_proveedor','prv_nombre','',array('prv_estado'=>1),null,'','prv_id',true);
			$data['procesos'] = $this->regional_model->get_dropdown('pro_proceso','pro_nombre','',array('pro_entrada'=>1,'pro_estado'=>1),null,'','pro_id',true);
			$data['productos'] = $this->regional_model->get_dropdown('pro_producto','{pro_codigo}::{pro_nombre}','',array('pro_estado'=>1),null,'','pro_id',true);
			$data['categoria'] = $this->regional_model->get_dropdown('cat_catalogo','{cat_nombre}::{cat_codigo}','',array('cat_estado'=>1),null,'','cat_id',true);

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
			}
		}
	}

	function cargar_subcategorias()
	{
		$id_cat = $this->input->post('id');
		$subcategoria = $this->regional_model->get_dropdown('sub_subcatalogo','{sub_nombre}::{sub_codigo}','',array('sub_cat_id'=>$id_cat,'sub_estado'=>1),null,'','sub_id',true);
		$arreglo = array('drop'=>$subcategoria);
		echo json_encode($arreglo);
	}

		function cargar_productosxcategoria()
	{
		$id_cat = $this->input->post('id');
		$subcategoria = $this->regional_model->get_productosxcategoria($id_cat);
		//echo $this->db->last_query();
		$html="<option value='0' selected>Seleccione</option>";
		foreach ($subcategoria as $key => $value) {
			$html .= "<option value= ".$value['pro_id']." > ".$value['pro_nombre'].'::'.$value['pro_codigo']."</option>";
		}

		$arreglo = array('drop'=>$html);
		echo json_encode($arreglo);
	}

		function cargar_productosxsubcategoria()
	{
		$id_sub = $this->input->post('id');
		$productos = $this->regional_model->cargar_productosxsubcategoria($id_sub);
		
		$html="<option value='0' selected>Seleccione</option>";
		foreach ($productos as $key => $value) {
			$html .= "<option value= ".$value['pro_id']." > ".$value['pro_nombre'].'::'.$value['pro_codigo']."</option>";
		}

		$arreglo = array('drop'=>$html);
		echo json_encode($arreglo);
	}

	function cargar_articulos()
	{
		$id_bodega = $this->input->post('id_bod');
		$articulos = $this->pro_model->get_articulos($id_bodega);
		$html="";
		foreach ($articulos as $key => $value) {
			$html .= '<option value="'.$value['sar_id'].'">'.$value['pro_nombre'].'::'.$value['sar_cantidad'].'</option>';
		}

		$arreglo = array(
			'articulos' => $html
			);

		echo json_encode($arreglo);
	}

	function rechazar_solicitud()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if($_POST){
				//die(print_r($_POST));

				$sol = $this->input->post('solicitud');
				$motivo = $this->input->post('motivo');
				$depto = $this->input->post('departamento');
				if($depto==1){
					$motivo .= "<br><br><b>Att: Departamento de Abastecimiento</b>";
				} else {
					$motivo .= "<br><br><b>Att: Departamento Financiero</b>";
				}
				
				$array = array(
						'res_sol_id' => $sol,
						'res_descripcion' => $motivo,
						'res_fecha' => date('Y-m-d H:i:s'),
						'res_estado'=>1, 
						'res_usu_mod' => $this->tank_auth->get_user_id(),
						'res_fecha_mod' => date('Y-m-d H:i:s')
					);

				$sol_rechazada = $this->regional_model->insertar_registro('res_rechazo_solicitud', $array);

				if($sol_rechazada>0){
				
				$where = array('des_sol_id'=>$sol);
				$array = array(
					'des_ets_id'=>3,
					'des_fecha_mod' => date('Y-m-d H:i:s')
				);
				
				$row_afected = $this->sistema_model->actualizar_registro('des_detalle_solicitud', $array, $where);	
				
				if($row_afected>0)
				{
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Transacción Exitosa",'texto_alerta'=>"Se rechazo la solicitud de manera exitosa.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La solicitud no pudo ser aprobada.");
				}
			} else { $alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La solicitud no pudo ser aprobada."); }
	
			$this->session->set_flashdata($alerta);       
			redirect('home/abastecimiento/procesar_solicitudes');

			}
		}	
	}

	function procesar_solicitudes()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Solicitudes pendientes";
			$data['vista_name'] = "solicitudes/procesar_solicitudes";

				// All your code goes here
			$data['abastecimiento'] = 1;
			$data['solicitudes'] = $this->regional_model->detalle_sol_abastecimiento();
			$data['html'] = $this->load->view('solicitudes/cargar_tabla',$data,true);
			// die(print_r($this->db->last_query()));
				// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
		}
	}

	function ver_solicitudes_edit($id=NULL,$financiero=NULL)
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			if($_POST){
				//die(print_r($_POST));
				$id_sol = $this->input->post('id_sol');
				$act_solicitud = array(
					'sol_dpi_id'		=> 	$this->input->post('dpi_interno'),
					'sol_num_entregas'	=> 	$this->input->post('numero_entrega'),
					'sol_soe_id'		=>	1, // Pendiente
					'sol_usu_mod'		=>	$this->tank_auth->get_user_id(),
					'sol_fecha_mod'		=>	date('Y-m-d H:i:s')
				);
				$row_affected = $this->regional_model->actualizar_registro('sol_solicitud',$act_solicitud,array('sol_id'=>$id_sol));
			
				if($row_affected==1){
					$detalle = array(
						'des_total'		=> $this->input->post('total'),
						'des_fon_id'	=> $this->input->post('fondo'),
						'des_plazo_entrega'	=>	$this->input->post('plazo_entrega'),
						'des_direccion'	=> $this->input->post('lugar_entrega'),
						'des_usu_mod'	=>	$this->tank_auth->get_user_id(),
						'des_fecha_mod'	=>	date('Y-m-d H:i:s')		
						);				
					$this->regional_model->actualizar_registro('des_detalle_solicitud', $detalle, array('des_sol_id'=>$id_sol));

					// Obtener datos del formulario
					$productos = $this->input->post('productos');
					$categoria = $this->input->post('categoria');
					$descripcion = $this->input->post('descripcion');
					$cantidad = $this->input->post('cantidad');
					$precios = $this->input->post('precios');
					
					// Hacer un borrado general de los detalles
					$articulos_borrados = $this->regional_model->borrado_general('pxs_productoxsolicitud',$id_sol);	
					
					// Ingresar los detalles de los productos
					if($articulos_borrados>0){
						foreach ($productos as $key => $value) {
							$pro_solicitud = array(
								'pxs_sol_id'	=> $id_sol,
								'pxs_pro_id'	=> $value,
								'pxs_cantidad'	=> $cantidad[$key],
								'pxs_precio'	=> $precios[$key],
								'pxs_descripcion'=> $descripcion[$key],
								'pxs_estado'	=> 1,
								'pxs_usu_mod'	=>	$this->tank_auth->get_user_id(),
								'pxs_fecha_mod'	=>	date('Y-m-d H:i:s')			 
							);
							$pro_sol = $this->regional_model->insertar_registro('pxs_productoxsolicitud', $pro_solicitud);
					 	}
					}

					// crear alerta OK
					$alerta=array('registro'=>$row_affected,'tipo_alerta'=> 'success','titulo_alerta'=>"Proceso Exitoso",'texto_alerta'=>"Edición de solicitud exitosa.");
				} else {
					// crear alerta FAIL
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Solicitud No editada",'texto_alerta'=>"Por favor, revisar datos ingresados.");
				}
				$this->session->set_flashdata($alerta);       
				redirect('home/abastecimiento/ver_solicitudes_edit/'.$id_sol);
			} // End POST

			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Solicitudes pendientes";
			$data['vista_name'] = "solicitudes/ver_solicitudes_edit";

			// All your code goes here
			$this->session->set_flashdata('id_sol', $id);
			// Consulta para obtener todos los detalles de la solicitud
			$detalle_solicitud = $this->regional_model->detalle_sol($id);
			$data['detalle_sol'] = $detalle_solicitud;
			$data['dep_internos'] = $this->regional_model->get_dropdown('dpi_departamento_interno','dpi_nombre','',array('dpi_estado'=>1),$detalle_solicitud[0]['dpi_id'],'','dpi_id',true);
			$data['bodega'] = $this->regional_model->get_dropdown('ali_almacen_inv','ali_nombre','',array('ali_estado'=>1),$detalle_solicitud[0]['ali_id'],'','ali_id',true);	
			$data['categoria'] = $this->regional_model->get_dropdown('cat_catalogo','{cat_nombre}::{cat_codigo}','',array('cat_estado'=>1),$detalle_solicitud[0]['des_cat_id'],'','cat_id',true);
			$data['fondo'] = $this->regional_model->get_dropdown('fon_fondo','fon_nombre','',array('fon_estado'=>1),$detalle_solicitud[0]['fon_id'],'','fon_id',true);
			$table['info_general'] = $detalle_solicitud;
			$table['solicitud'] = $this->regional_model->detalle_sol_productos($id);
			$data['detalle_articulos'] = $this->load->view('solicitudes/cargar_datatable', $table,true);
			$data['financiero'] = $financiero;
			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);	
		}
	}

	function aprobar_solicitud()
	{
		if($_POST){
			$sol = $this->input->post('solicitud');
			$where = array('des_sol_id'=>$sol);
			$array = array(
				'des_ets_id'=>6,
				'des_fecha_mod' => date('Y-m-d H:i:s')
				);
			$row_afected = $this->sistema_model->actualizar_registro('des_detalle_solicitud', $array, $where);	
			if($row_afected>0)
				{
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Aprobación Exitosa",'texto_alerta'=>"Se aprobo la solicitud de manera exitosa.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La solicitud no pudo ser aprobada.");
				}
				$this->session->set_flashdata($alerta);       

			redirect('home/abastecimiento/procesar_solicitudes');								
		}
	}

	function aprobar_solicitud2()
	{
		if($_POST){
			$sol = $this->input->post('solicitud');
			$where = array('des_sol_id'=>$sol);
			$array = array(
				'des_ets_id'=>7,
				'des_fecha_mod' => date('Y-m-d H:i:s')
				);
			$row_afected = $this->sistema_model->actualizar_registro('des_detalle_solicitud', $array, $where);	
			if($row_afected>0)
				{
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Aprobación Exitosa",'texto_alerta'=>"Se aprobo la solicitud de manera exitosa.");
				} else
				{
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Transaccion no realizada",'texto_alerta'=>"La solicitud no pudo ser aprobada.");
				}
				$this->session->set_flashdata($alerta);       

			redirect('home/financiero/procesar_solicitudes');								
		}
	}


	function __cargarVista($data=0)
	{	
		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */