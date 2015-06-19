<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

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

	function obtener_precio()
	{
		$id_pro = $this->input->post('id');
		$precio = '';
		if($id_pro !=0){
			//$precio = $this->sistema_model->get_campo('sar_saldo_articulo','sar_cantidad', array('sar_pro_id'=>$id_pro, 'sar_estado'=>1));	
			$existencias = $this->regional_model->get_existencias(array('sar_pro_id'=>$id_pro));
			if(!empty($existencias)){
				$precio = $existencias['sar_precio'];
			}
		}

		$html = '';
		if(!empty($existencias)){
			$unidades = 'Unidad';
			$existen = 'Hay';
			if($existencias['sar_cantidad']>1) { $unidades = 'Unidades'; $existen='Existen';}
			$html = $existen .' '.$existencias['sar_cantidad'].' '.$unidades .' en la bodega '.$existencias['ali_nombre'] .'.';
		}

		$arreglo = array("drop"=>$precio, "existencias"=>$html);
		echo json_encode($arreglo);
	}

	function cargar_subcategorias()
	{
		$id_cat = $this->input->post('id');
		$subcategoria = $this->regional_model->get_dropdown('sub_subcatalogo','{sub_nombre}::{sub_codigo}','',array('sub_cat_id'=>$id_cat,'sub_estado'=>1),null,'','sub_id',true);
		//echo $this->db->last_query();
		$arreglo = array('drop'=>$subcategoria);
		echo json_encode($arreglo);
	}

	function cargar_productos()
	{
		$id = $this->input->post('id');
		$productos = $this->regional_model->get_dropdown('sub_subcatalogo','{sub_nombre}::{sub_codigo}','',array('sub_id'=>$id_cat,'sub_estado'=>1),null,'','sub_id',true);
	}

	function cargar_direccion()
	{
		$id_bodega = $this->input->post('id');
		$bodega = $this->sistema_model->get_registro('ali_almacen_inv', array('ali_id'=>$id_bodega, 'ali_estado'=>1));
		//echo print_r($bodega);
		$arreglo=array('drop'=>$bodega);
		echo json_encode($arreglo);
	}

	function crear_solicitud(){
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "solicitudes/crear_solicitud";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Crear solicitud";

			// Cargar los datos para las solicitudes
			$data['dep_internos'] = $this->regional_model->get_dropdown('dpi_departamento_interno','dpi_nombre','',array('dpi_estado'=>1),null,'','dpi_id',true);
			$data['bodega'] = $this->regional_model->get_dropdown('ali_almacen_inv','ali_nombre','',array('ali_estado'=>1),null,'','ali_id',true);
			$data['categoria'] = $this->regional_model->get_dropdown('cat_catalogo','{cat_nombre}::{cat_codigo}','',array('cat_estado'=>1),null,'','cat_id',true);
			$data['fondo'] = $this->regional_model->get_dropdown('fon_fondo','fon_nombre','',array('fon_estado'=>1),null,'','fon_id',true);
			$data['unidad_medida'] = $this->regional_model->get_dropdown('uni_unidad_medida','uni_valor','',array('uni_estado'=>1),null,'','uni_id',true);

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		 	
			$this->__cargarVista($data);
		}
	}

	function entrada_solicitud()
	{
		if($_POST){
			//print_r($_POST); die();

		// Regristar la solicitud
		$solicitud = array(
				'sol_dpi_id'		=> 	$this->input->post('dpi_interno'),
				'sol_fecha'			=> 	date('Y-m-d H:i:s',strtotime($_POST['fecha_entrega'].date('H:i:s'))),
				'sol_num_entregas'	=> 	$this->input->post('numero_entrega'),
				'sol_ali_id'		=>	$this->input->post('bodega'),
				'sol_soe_id'		=>	1,
				'sol_tps_id'		=> 	NULL,
				'sol_estado'		=>	1,
				'sol_usu_mod'		=>	$this->tank_auth->get_user_id(),
				'sol_fecha_mod'		=>	date('Y-m-d H:i:s')
			);

		$sol_id = $this->regional_model->insertar_registro('sol_solicitud', $solicitud);
		}

		if($sol_id>0){
			$detalle = array(
					'des_fecha' 	=> date('Y-m-d H:i:s'),
					'des_total'		=> $this->input->post('total'),
					'des_fon_id'	=> $this->input->post('fondo'),
					'des_plazo_entrega'	=>	$this->input->post('plazo_entrega'),
					'des_direccion'	=> $this->input->post('lugar_entrega'),
					'des_sol_id'	=> $sol_id,
					'des_ets_id'	=> 1,
					'des_estado'	=> 1,
					'des_usu_mod'	=>	$this->tank_auth->get_user_id(),
					'des_fecha_mod'	=>	date('Y-m-d H:i:s')		
				);
			$des_id = $this->regional_model->insertar_registro('des_detalle_solicitud', $detalle);
		

			// Insertar articulo asociado a solicitud
			$productos = $this->input->post('productos');
			$categoria = $this->input->post('categoria');
			$descripcion = $this->input->post('descripcion');
			$cantidad = $this->input->post('cantidad');
			$unidad_medida = $this->input->post('unidad_med');
			//$financiamiento = $this->input->post('financiamiento');
			$precios = $this->input->post('precios');

			foreach ($productos as $key => $value) {
				$pro_solicitud = array(
					'pxs_sol_id'	=> $sol_id,
					'pxs_cat_id'	=> $categoria[$key],
					'pxs_pro_id'	=> $value,
					'pxs_uni_id'	=> $unidad_medida[$key],
					'pxs_cantidad'	=> $cantidad[$key],
					'pxs_precio'	=> $precios[$key],
					'pxs_descripcion'=> $descripcion[$key],
					'pxs_estado'	=> 1,
					'pxs_usu_mod'	=>	$this->tank_auth->get_user_id(),
					'pxs_fecha_mod'	=>	date('Y-m-d H:i:s')			 
				);
				$pro_sol = $this->regional_model->insertar_registro('pxs_productoxsolicitud', $pro_solicitud);
			}
			// crear alerta OK
			$alerta=array('registro'=>$registro,'tipo_alerta'=> 'success','titulo_alerta'=>"Proceso Exitoso",'texto_alerta'=>"Creacion de solicitud exitosa.");
		} else {
			// crear alerta FAIL
			$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Solicitud no procesada",'texto_alerta'=>"Por favor, revisar datos ingresados.");
		}
		$this->session->set_flashdata($alerta);       
		redirect('welcome');									
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