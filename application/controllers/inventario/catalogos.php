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

	function categoria(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('cat_catalogo');
			$crud->set_subject('categoría');
			$crud->required_fields('cat_nombre');
			$crud->required_fields('cat_codigo');
			$crud->required_fields('cat_esp_id');
			$crud->columns('cat_nombre','cat_esp_id','cat_codigo','cat_fecha','cat_estado');
			$crud->fields('cat_nombre','cat_esp_id','cat_codigo','cat_fecha','cat_estado');
			$crud->display_as('cat_nombre','Nombre');
			$crud->display_as('cat_codigo','Código');
			$crud->display_as('cat_esp_id','Específico');
			$crud->display_as('cat_descripcion','Descripción');
			$crud->display_as('cat_fecha','Fecha');
			$crud->display_as('cat_estado','Estado');			
			$crud->set_rules('cat_codigo', 'Código','trim|required|xss_clean|campo_unico[cat_catalogo.cat_codigo]');	
			$crud->set_rules('cat_nombre', 'Nombre','trim|required|xss_clean|campo_unico[cat_catalogo.cat_nombre]');	

			$crud->set_relation('cat_esp_id','esp_especifico','esp_nombre');

			$crud->field_type('cat_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('cat_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('cat_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index';
			$data['titulo']="Categorías";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/catalogos', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

	function subcategoria(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$crud->set_table('sub_subcatalogo');
			$crud->set_subject('Subcategoría');
			
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
					'sub_nombre'=>'Nombre',
					'sub_codigo'=>'Código',
					'sub_fecha'=>'Fecha',
					'sub_cat_id'=>'Categoría',
					'sub_descripcion'=>'Descripción',
					'sub_estado'=>'Estado'
				);
			
			$crud->set_rules('sub_codigo', 'Código','trim|required|xss_clean|campo_unico[sub_subcatalogo.sub_codigo]');	
			$crud->set_rules('sub_nombre', 'Nombre','trim|required|xss_clean|campo_unico[sub_subcatalogo.sub_nombre]');	

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
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index';
			$data['titulo']="Subcategorías";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
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
			$crud->set_subject('Almacen');
			
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
					'ali_nombre'=>'Nombre',
					'ali_id'=>'Código',
					'ali_direccion'=>'Dirección',
					'ali_estado'=>'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);
			$crud->set_rules('ali_nombre', 'Nombre','trim|required|xss_clean|campo_unico[ali_almacen_inv.ali_nombre]');	

			$crud->field_type('ali_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('ali_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('ali_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));


		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index'; // Cuando se necesite usar javascript, venir aca.
			$data['titulo']="Almacenes";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
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
			$crud->set_subject('Unidad de Medida');
			$crud->required_fields('uni_nombre','uni_valor');
			
			$crud->columns('uni_nombre','uni_valor','uni_estado');
			$crud->display_as('uni_nombre','Nombre');
			$crud->display_as('uni_estado','Estado');
			$crud->display_as('uni_valor','Valor');
			$crud->field_type('uni_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('uni_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('uni_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));
			$crud->set_rules('uni_nombre', 'Nombre','trim|required|xss_clean|campo_unico[uni_unidad_medida.uni_nombre]');
			$crud->set_rules('uni_valor', 'Valor','trim|required|xss_clean|campo_unico[uni_unidad_medida.uni_valor]');

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index';
			$data['titulo']="Unidades de Medida";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/unidades_de_medida', $output, true); 
		 	$this->__cargarVista($data);	 	 
	 }
	}

function procesos(){

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables'); // Al comentar esta linea, le pones otro estilo a la tabla.
			$query_padres="SELECT DISTINCT pro_padre FROM pro_proceso";
		     $padres=$this->db->query($query_padres)->result_array();

		      $cadena_in="";
		      foreach ($padres as $key => $value) {
		        if($value['pro_padre'] != ""){
		          $cadena_in.=$value['pro_padre'].","; 
		        }

		      }
		      $cadena_final=substr($cadena_in,0,-1);
		      if($cadena_final == '')
		      {
		        $cadena_final=0;
		      }

			$crud->set_table('pro_proceso');
			$crud->set_subject('Proceso');
			$crud->required_fields('pro_nombre');
			$crud->columns('pro_nombre','pro_padre','pro_descripcion','pro_estado');
						
			$crud->display_as('pro_nombre','Nombre');
			$crud->display_as('pro_descripcion','Descripción');
			$crud->display_as('pro_entrada','Entrada');
			$crud->display_as('pro_salida','Salida');
			$crud->display_as('pro_estado','Estado');
			$crud->display_as('pro_padre','Padre');
			$crud->display_as('pro_financiero','Financiero');
			$crud->display_as('pro_fecha','Fecha');
			$crud->set_rules('pro_nombre', 'Nombre','trim|required|xss_clean|campo_unico[pro_proceso.pro_nombre]');	
			$crud->field_type('pro_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('pro_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('pro_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));
			$state = $crud->getState();
			$state_info = $crud->getStateInfo();

			if( $state == 'edit')
			{


			$cadena_final = $state_info->primary_key;
			$query_no_tienen_hijos="SELECT pro_id FROM pro_proceso WHERE pro_id NOT IN (".$cadena_final.")";
		      $no_tienen_hijos=$this->db->query($query_no_tienen_hijos)->result_array();
		      $cadena_in2="";
		      foreach ($no_tienen_hijos as $key2 => $value2) {
		        if($value2['pro_id'] != ""){
		          $cadena_in2.=$value2['pro_id'].","; 
		        }

		      }


		      $cadena_final2=substr($cadena_in2,0,-1);
		      if($cadena_final2 == '')
		      {
		        $cadena_final2=0;
		      }

		      $crud->set_relation('pro_padre','pro_proceso','pro_nombre', "pro_id IN({$cadena_final2})");
		    }  else {
		      $crud->set_relation('pro_padre','pro_proceso','pro_nombre');		
		    }  
			

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index';
			$data['titulo']="Procesos";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		// 	Estas tres lineas son principales cuando se desea imprimir un Grocery Crud en el sistema
		 	$crud->unset_jquery(); // No llama al jQuery del Grocery Crud
		 	$output = $crud->render();
		 	//$this->load->view('sistema/pais',$output);
		 	$data['texto'] = $this->load->view('inventario/catalogos/catalogos', $output, true); 
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
			$crud->set_subject('Artículo');
			
			$columnas = array(
				'pro_codigo',
				'pro_nombre',
				'pro_sub_id',
				// 'pro_esp_id',
				'pro_uni_id',
				'pro_tip_id',
				'pro_descripcion',
				'pro_estado'
				);

			$requeridos = array(
					'pro_codigo',
					'pro_nombre',
					'pro_sub_id',
					// 'pro_esp_id',
					'pro_tip_id',
					'pro_saldo'
				);

			$alias = array(
					'pro_codigo' => 'Código',
					'pro_nombre' => 'Nombre',
					'pro_codigo_nac' => 'Cod. Naciones Unidas',
					'pro_sub_id' => 'Subcatálogo',
					'pro_descripcion' => 'Descripción',
					// 'pro_esp_id' => 'Específico',
					'pro_uni_id' => 'Unidad/Medida',
					'pro_tip_id' => 'Tipo',
					'pro_estado'  => 'Estado'
				);

			$crud->required_fields($requeridos);
			$crud->columns($columnas);
			$crud->display_as($alias);
			$crud->edit_fields($columnas);
			$crud->add_fields($columnas);
			$crud->set_rules('pro_codigo', 'Código','trim|required|xss_clean|campo_unico[pro_producto.pro_codigo]');	
			$crud->field_type('pro_usu_mod', 'hidden', $this->tank_auth->get_user_id());
			$crud->field_type('pro_fecha_mod', 'hidden', date('Y-m-d H:i:s'));
			$crud->field_type('pro_estado','dropdown', array('1'=>'Activo','0'=>'Inactivo'));

			// Relacion de 1 a muchos. 
			// $crud->set_relation('pro_esp_id','esp_especifico','esp_nombre');
			$crud->set_relation('pro_uni_id','uni_unidad_medida','uni_valor');
			$crud->set_relation('pro_tip_id','tip_tipo_producto','tip_nombre');
			$crud->set_relation('pro_sub_id','sub_subcatalogo','sub_nombre');

		// Datos generales de la pagina	
			$data['menu_sistema']=true;
			$user_id	= $this->tank_auth->get_user_id();
			$data['vista_name']='inventario/index';
			$data['titulo']="Artículos";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',1, $user_id);
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