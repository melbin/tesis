<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestion_personal extends CI_Controller {

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
			$data['vista_name'] = "gestion_personal/index";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$data['titulo']="Menu de Inventario";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>7));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',7, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	public function personas()
	{

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('per_persona');
			$crud->set_subject('persona');
			
			$columns = array(
				'per_nombre',
				'per_apellido',
				'per_fecha_nac',
				'per_telefono',
				'per_correo'
				);

			$add_fields = array(
				'per_nombre',
				'per_apellido',
				'per_edad',
				'per_fecha_nac',
				'per_telefono',
				'per_cargo',
				'per_correo',
				'per_mun_id',
				'per_gen_id',
				'per_estado',
				'per_usu_mod',
				'per_fecha_mod'
				); 	

			$requeridos = array(
					'per_nombre',
					'per_apellido',
					'per_correo',
					'per_mun_id'
				);

			$alias = array(
					'per_nombre'=>'Nombre',
					'per_apellido'=>'Apellido',
					'per_edad'=>'Edad',
					'per_fecha_nac'=>'Fecha de nacimiento',
					'per_telefono'=>'Teléfono',
					'per_correo'=>'Correo',
					'per_mun_id'=>'Municipio',
					'per_gen_id'=>'Sexo',
					'per_cargo' => 'Cargo',
					'per_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columns);
			$crud-> fields($add_fields);
			$crud->display_as($alias);
			$crud->set_rules('per_correo','correo','required|valid_email');
			$crud->set_relation('per_mun_id','mun_municipio','mun_nombre');
			$crud->set_relation('per_gen_id','gen_genero','gen_nombre');

			$crud->field_type('per_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('per_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('per_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

		// Datos generales de la pagina	
			$data['titulo']="Gestión de Personas";

		// Guardar esta persona como un empleado
			// $crud->callback_after_insert(array($this, 'add_empleado'));	

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['vista_name']='bancos/index';
		 	$data['texto'] = $this->load->view('recurso_humano/empleados', $output, true); 

		 	$this->__cargarVista($data);	 	 
	  }
	}

	function add_empleado($post_array, $primary_key)
	{
	    $empleado_array = array(
	        "emp_per_id" => $primary_key
	    );
	 
	    $this->db->insert('emp_empleado',$empleado_array);
	 
	    return true;
	}

		function __cargarVista($data=0)
	{	
		// Datos generales de la pagina	
		$data['menu_sistema']=true;
		$user_id	= $this->tank_auth->get_user_id();
		$data['logo'] = $this->regional_model->get_parametro("logo");
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>7));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',7, $user_id);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */