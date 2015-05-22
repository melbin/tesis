<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

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

	function catalogo(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('cat_catalogo');
			$crud->set_subject('Catalogos');
			$crud->required_fields('cat_nombre');
			$crud->required_fields('cat_codigo ');
			$crud->columns('cat_nombre','cat_codigo','cat_fecha','cat_estado');
			$crud->display_as('cat_nombre','catalogo');
			$crud->display_as('cat_codigo','codigo');
			$crud->display_as('cat_fecha','fecha');
			$crud->display_as('cat_estado','estado');			
			$crud->field_type('cat_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('cat_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('cat_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$data['vista_name']='inventario/index';
			$data['titulo']="Catalogos";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/catalogos', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function subcatalogos(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('sub_subcatalogo');
			$crud->set_subject('Subcatalogos');
			
			$columnas = array(
				'sub_codigo',
				'sub_nombre',
				'sub_cat_id',
				'sub_fecha',
				'sub_estado'
				);

			$requeridos = array(
					'sub_nombre',
					'sub_codigo',
					'sub_fecha',
					'sub_cat_id'
				);

			$alias = array(
					'sub_nombre'=>'nombre',
					'sub_codigo'=>'codigo',
					'sub_fecha'=>'fecha',
					'sub_cat_id'=>'catalogo',
					'sub_descripcion'=>'descripcion',
					'sub_estado'=>'estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			// Relacion de 1 a muchos. Un catalogo puede tener muchos Subcatalogos
			$crud->set_relation('sub_cat_id','cat_catalogo','cat_nombre');
			
			$crud->field_type('sub_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('sub_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('sub_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


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
		 	$data['texto'] = $this->load->view('inventario/catalogos/subcatalogos', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function almacenes(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('ali_almacen_inv');
			$crud->set_subject('Almacenes');
			
			$columnas = array(
				'ali_id',
				'ali_nombre',
				'ali_direccion',
				'ali_estado'
				);

			$requeridos = array(
					'ali_nombre',
					'ali_direccion'
				);

			$alias = array(
					'ali_nombre'=>'nombre',
					'ali_id'=>'codigo',
					'ali_direccion'=>'direccion',
					'sub_estado'=>'estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('ali_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('ali_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('ali_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Almacenes";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/almacenes', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function unidades_de_medida(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('uni_unidad_medida');
			$crud->set_subject('Unidades de Medida');
			$crud->required_fields('uni_nombre','uni_valor');
			
			$crud->columns('uni_nombre','uni_valor','uni_estado');
			$crud->display_as('uni_nombre','nombre');
			$crud->display_as('uni_estado','estado');
			$crud->display_as('uni_valor','valor');
			$crud->field_type('uni_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('uni_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('uni_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$data['vista_name']='inventario/index';
			$data['titulo']="Unidades de Medida";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/unidades_de_medida', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function productos(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('pro_producto');
			$crud->set_subject('Articulos');
			
			$columnas = array(
				'pro_codigo',
				'pro_nombre',
				'pro_sub_id',
				'pro_esp_id',
				'pro_uni_id',
				'pro_tip_id',
				'pro_descripcion',
				'pro_estado'
				);

			$requeridos = array(
					'pro_codigo',
					'pro_nombre',
					'pro_sub_id',
					'pro_esp_id',
					'pro_tip_id',
					'pro_saldo'
				);

			$alias = array(
					'pro_codigo' => 'codigo',
					'pro_nombre' => 'nombre',
					'pro_sub_id' => 'subcatalogo',
					'pro_descripcion' => 'descripcion',
					'pro_esp_id' => 'especifico',
					'pro_uni_id' => 'unidad/medida',
					'pro_tip_id' => 'tipo',
					'pro_estado'  => 'estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);
			
			$crud->field_type('pro_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('pro_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('pro_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos. 
			$crud->set_relation('pro_esp_id','esp_especifico','esp_nombre');
			$crud->set_relation('pro_uni_id','uni_unidad_medida','uni_valor');
			$crud->set_relation('pro_tip_id','tip_tipo_producto','tip_nombre');
			$crud->set_relation('pro_sub_id','sub_subcatalogo','sub_nombre');

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$data['vista_name']='inventario/index';
			$data['titulo']="Articulos";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/productos', $output, true); 
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