<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bancos extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('masterpage');
		$this->load->model('regional_model');
		$this->load->model('sistema/sistema_model');
	//	$this->load->model('bancos/banco_model');
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
			$data['vista_name'] = "bancos/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Bancos";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>5));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',5, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
		}
	}


	public function fondos()
	{	

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('fon_fondo');
			$crud->set_subject('fondo');
			
			$columnas = array(
				'fon_nombre',
				'fon_cantidad',
				'fon_fecha',
				'fon_estado'
				);

			$fields = array(
				'fon_nombre',
				'fon_cantidad',
				'fon_fecha',
				'fon_estado',
				'fon_usu_mod',
				'fon_fecha_mod'
				);

			$alias = array(
					'fon_nombre'	=>'Nombre',
					'fon_cantidad'	=> 'Cantidad ($)',
					'fon_fecha' 	=> 'Fecha',
					'fon_estado'	=>'Estado'
				);

			$crud->required_fields($columnas);
			$crud->columns($columnas);
			$crud->fields($fields);
			$crud->display_as($alias);

			$crud->field_type('fon_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('fon_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('fon_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));
			$crud->callback_column('fon_cantidad',array($this,'_formato_dinero'));

		// Datos generales de la pagina	
			$data['titulo']="Gestión de Fondos";

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('bancos/bancos/fondos', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	
	}
	
	function _formato_dinero($value, $row)
	{
		return number_format($value,2,'.',',');
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