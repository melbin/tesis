<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificos extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('masterpage');
		$this->load->model('Regional_model');
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
			$data['vista_name'] = "inventario/index";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$data['titulo']="Menu de Inventario";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
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
			$crud->set_subject('Especificos');
			
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
			$crud->display_as($alias);
			
			$crud->field_type('esp_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('esp_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('esp_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$data['vista_name']='inventario/index';
			$data['titulo']="Subcatalogos";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('bancos/especificos/especifico', $output, true); 
		 	$this->__cargarVista($data);	 	 
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