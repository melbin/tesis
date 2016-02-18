<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suministros extends CI_Controller {

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
			$user_id	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "inventario/index";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$data['titulo']="Menu de Inventario";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}


	function rubro(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('rub_rubro');
			$crud->set_subject('Rubro');
			
			$columnas = array(
				'rub_id',
				'rub_nombre',
				'rub_estado'
				);

			$requeridos = array(
					'rub_nombre'
				);

			$alias = array(
					'rub_nombre'=>'Nombre',
					'rub_id'=>'Código',
					'rub_descripcion'=>'Descripción',
					'rub_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('rub_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('rub_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('rub_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Rubros";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/suministros/rubro', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function empresas(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('emp_empresa');
			$crud->set_subject('Empresa');
			
			$columnas = array(
				'emp_id',
				'emp_nombre',
				'emp_rubro_id',
				'emp_mun_id',
				'emp_direccion',
				'emp_estado'
				);

			$requeridos = array(
					'emp_nombre',
					'emp_mun_id',
					'emp_rubro_id',
					'emp_direccion'
				);

			$alias = array(
					'emp_rubro_id'=>'Rubro',
					'emp_mun_id'=>'Municipio',
					'emp_nombre'=>'Nombre',
					'emp_descripcion'=>'Descripción',
					'emp_id'=>'Código',
					'emp_direccion'=>'Dirección',
					'emp_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('emp_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('emp_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('emp_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos. 
			$crud->set_relation('emp_rubro_id','rub_rubro','rub_nombre');
			$crud->set_relation('emp_mun_id','mun_municipio','mun_nombre');


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Empresas";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/suministros/empresas', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function proveedores(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('prv_proveedor');
			$crud->set_subject('Proveedor');
			
			$columnas = array(
				'prv_id',
				'prv_nombre',
				'prv_empresa_id',
				'prv_correo',
				'prv_telefono',
				'prv_estado'
				);

			$requeridos = array(
					'prv_nombre',
					'prv_empresa_id',
					'prv_telefono'
				);

			$alias = array(
					'prv_id'=>'ID',
					'prv_empresa_id'=>'Empresa',
					'prv_nombre'=>'Nombre',
					'prv_apellido'=>'Apellido',
					'prv_correo'=>'Correo',
					'prv_descripcion'=>'Descripción',
					'prv_telefono'=>'Teléfono',
					'prv_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('prv_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('prv_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('prv_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos. 
			$crud->set_relation('prv_empresa_id','emp_empresa','emp_nombre');

			// Reglas para los Formularios
			$crud->set_rules('prv_correo','correo','required|valid_email|campo_unico[users.email]');
			
		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Proveedores";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/suministros/proveedores', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function contratistas(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('con_contratista');
			$crud->set_subject('Contratistas');
			
			$columnas = array(
				'con_prv_id',
				'con_contrato',
				'con_monto',
				'con_retencion',
				'con_renta',
				'con_nit',
				'con_estado'
				);

			$requeridos = array(
					'con_prv_id',
					'con_contrato', // Dependera de como trabajen en la Regional.
					'con_monto'
				);

			$alias = array(
					'con_prv_id'=>'Proveedor',
					'con_contrato'=>'Contrato',
					'con_monto'=>'Monto',
					'con_retencion'=>'Retención',
					'con_renta'=>'Renta',
					'con_nit'=>'Nit',
					'con_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('con_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('con_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('con_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos. 
			$crud->set_relation('con_prv_id','prv_proveedor','prv_nombre');

			// Reglas para los Formularios
			$crud->set_rules('con_monto','monto','numeric');
			$crud->set_rules('con_retencion','retencion','numeric');
			$crud->set_rules('con_renta','renta','numeric');
			// el " NIT" no se que tipo sera

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Contratistas";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/suministros/contratistas', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function calificaciones(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('cct_calificacion_contratista');
			$crud->set_subject('Calificación por pedido');

			// Relacion de 1 a muchos. 
			$crud->set_relation_n_n('contratista', 'con_contratista', 'prv_proveedor', 'con_id', 'con_prv_id', 'prv_nombre');
			//$crud->unset_columns('cct_con_id');

			$requeridos = array(
					'contratista',
					'cct_sol_id', // Dependera de como trabajen en la Regional.
					'cct_nota',
					'cct_fecha'
				);

			$alias = array(
					'cct_sol_id'=>'Solicitud',
					'cct_nota'=>'Nota',
					'cct_descripcion'=>'Descripción',
					'cct_fecha'=>'Fecha'
				);

			$crud->add_fields('contratista','cct_sol_id','cct_nota','cct_descripcion','cct_fecha');
			$crud->edit_fields('cct_sol_id','cct_nota','cct_descripcion','cct_fecha');
			$crud->required_fields($requeridos);
			//$crud->columns($columnas);
			$crud->display_as($alias);
			
			// Reglas para los Formularios
			$crud->set_rules('cct_nota','nota','numeric');
		

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Calificaciones";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1,'sio_estado'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/suministros/calificaciones', $output, true); 
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