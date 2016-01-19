<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Localidad extends CI_Controller {

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
			$data['vista_name'] = "sistema/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Menu de Sistema";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>2));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',2, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	function pais(){
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('pai_pais');
			$crud->set_subject('Paises');
			$crud->required_fields('pai_nombre');
			$crud->columns('pai_nombre','pai_estado');
			$crud->display_as('pai_nombre','pais');
			$crud->display_as('pai_estado','estado');			
			$crud->field_type('pai_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('pai_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('pai_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='sistema/index';
			$data['titulo']="Paises";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>2));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',2, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		// 	var_dump($info['info_padre']); die();
		
		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('sistema/pais', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function departamento(){
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('dep_departamento');
			$crud->set_subject('Departamentos');
			$crud->required_fields('dep_nombre');
			$crud->required_fields('dep_pais_id');
			$crud->columns('dep_nombre','dep_pais_id','dep_estado');
			$crud->display_as('dep_pais_id','pais');
			$crud->display_as('dep_nombre','departamento');
			 	 $crud->display_as('dep_estado','estado');			
			$crud->field_type('dep_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('dep_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('dep_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos.
			$crud->set_relation('dep_pais_id','pai_pais','pai_nombre');

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='bancos/index';
			$data['titulo']="Departamentos";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>2));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',2, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('sistema/departamento', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function municipio(){
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('mun_municipio');
			$crud->set_subject('Municipios');
			$crud->required_fields('mun_nombre');
			$crud->required_fields('mun_dep_id');

			$crud->columns('mun_nombre','mun_dep_id','mun_estado');
			$crud->display_as('mun_nombre','municipio');
			$crud->display_as('mun_dep_id','departamento');
			$crud->display_as('mun_estado','estado');			
			$crud->field_type('mun_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('mun_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('mun_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos.
			$crud->set_relation('mun_dep_id','dep_departamento','dep_nombre');

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='sistema/index';
			$data['titulo']="Municipios";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>2));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',2, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('sistema/municipio', $output, true); 
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