<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal extends CI_Controller {

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
		}

	}

	public function usuarios()
	{
		
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('users');
			$crud->set_subject('Usuario');
			
			$columns = array(
				'username',
				'email',
				'rol'
				);

			$add_fields = array(
				'username',
				'password',
				'email',
				'activated',
				'created',
				'modified',
				'rol',
				'per_id'
				); 	

			$requeridos = array(
					'username',
					'email',
					'activated',
					'rol',
					'per_id'
				);

			$alias = array(
					'username'=>'Nombre',
					'email'=>'Correo',
					'activated'=>'Estado',
					'rol'=>'Rol',
					'per_id'=>'Persona'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columns);
			$crud-> fields($add_fields);
			$crud->display_as($alias);
			$crud->set_rules('email','correo','required|valid_email');
			
			$crud->set_relation_n_n('rol', 'uxr_usuarioxrol', 'rol_rol', 'uxr_usuario_id', 'uxr_rol_id', 'rol_nombre');
			$crud->set_relation('per_id','per_persona','{per_nombre} {per_apellido}'); //'per_id IN (SELECT emp_per_id FROM emp_empleado)'
			
			//$crud->field_type('per_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('password','hidden');	
			$crud->field_type('created', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('modified', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('activated','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

		// Datos generales de la pagina	
			$data['titulo']="Gestión de usuarios";

			$crud->callback_before_insert(array($this,'_add_password'));
			$crud->callback_before_update(array($this,'_add_password'));
		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('sistema/personal/usuarios', $output, true); 

		 	$this->__cargarVista($data);	 	 
	  }
	
	}

	public function roles()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('rol_rol');
			$crud->set_subject('Rol');
			
			$columnas = array(
				'rol_nombre',
				'rol_descripcion',
				'rol_estado'
				);

			$requeridos = array(
					'rol_nombre',
					'rol_estado'
				);

			$alias = array(
					'rol_nombre'=>'Nombre',
					'rol_descripcion'=>'Descripción',
					'rol_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);

			$crud->field_type('rol_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	$data['titulo']="Roles";
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('sistema/personal/roles', $output, true); 

		 	$this->__cargarVista($data);	 	 
	 
		}
	}

	function _add_password($post_array)
	{

		$hasher = new PasswordHash(
		    $this->config->item('phpass_hash_strength', 'tank_auth'),
		    $this->config->item('phpass_hash_portable', 'tank_auth')
		);

		$post_array['password'] = $hasher->HashPassword($post_array['username']);
		 
		return $post_array;
	}

	function permisos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {	
			$data['rol']= $this->regional_model->get_tabla('rol_rol', array('rol_estado'=>1));
			$data['opc']= $this->regional_model->get_tabla('sio_sistema_opcion', array('sio_estado'=>1));

			//$data['user_id']	= $this->tank_auth->get_user_id();
			//$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "sistema/personal/permisos";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Permisos";
			$this->__cargarvista($data);
		}
	}

// Me he quedado aca
	function opciones()
	{
		//die(print_r($_POST,true));
		$data['oxr']=$this->sistema_model->cargar_opciones($_POST['rol']);
		$data['nivel']= $_POST['opc'];
		$where['sic_estado'] = 1;
		if(!empty($_POST['opc'])) $where['sic_sio_id']= $_POST['opc'];

		$data['opciones']=$this->sistema_model->get_tabla('sic_sistema_catalogo',$where);
		
		$this->load->view('sistema/personal/opciones',$data);

	}

	function addopc()
	{
		$this->sistema_model->add_opc($_POST['rol'],$_POST['opc']);
	}

	function delopc()
	{
		$this->sistema_model->del_opc($_POST['rol'],$_POST['opc']);
	}


	function __cargarVista($data=0)
	{	
		// Datos generales de la pagina	
		$data['menu_sistema']=true;
		$user_id = $this->tank_auth->get_user_id();
		$data['logo'] = $this->regional_model->get_parametro("logo");
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>2));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',2, $user_id);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */