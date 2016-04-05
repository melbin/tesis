<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('masterpage');
		$this->load->model('Regional_model');
		$this->load->model('sistema/sistema_model');
	}

	public function index()
	{	
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$user_id	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "pages/blank";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
			
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	

			$this->__cargarVista($data);
		}
	}

	public function perfil_usuario()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// Procesar el POST
			if($_POST){

				$hasher = new PasswordHash(
			    	$this->config->item('phpass_hash_strength', 'tank_auth'),
			    	$this->config->item('phpass_hash_portable', 'tank_auth')
				);

				$user_array = array(
						'username' => (!empty($_POST['nombre']))? $_POST['nombre']:null,
						'password' => $hasher->HashPassword($_POST['password']), 
						'email'	   => (!empty($_POST['correo']))? $_POST['correo']:null,
						'modified' => date('Y-m-d H:i:s')
					);

				$row_modified = $this->Regional_model->actualizar_registro('users', $user_array, array('id'=>$_POST['user_id']));
				if($row_modified>0){
					// crear alerta OK
					$alerta=array('tipo_alerta'=> 'success','titulo_alerta'=>"Proceso Exitoso",'texto_alerta'=>"Edición exitosa de usuario.");
				} else {
					// crear alerta FAIL
					$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Fallo de edición",'texto_alerta'=>"Hubo un error en la edición.");
				}
				$this->session->set_flashdata($alerta);       
				redirect('welcome');

			} else {
				// Datos del formulario

				$data['usuario_array'] = $this->sistema_model->get_registro('users', array('id'=>$this->tank_auth->get_user_id()));

				// Informacion de la pagina	
				$data['logo'] = $this->Regional_model->get_parametro("logo");
				$user_id	= $this->tank_auth->get_user_id();
				$data['username']	= $this->tank_auth->get_username();
				$data['vista_name']='pages/perfil_usuario.php';
			 	$data['titulo']="Perfil de Usuario";

				 	// Obtener los link del panel Izquierdo.
				$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
				$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
				
			 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			 $this->__cargarVista($data);	 	 	
			}
		}
	}

	function blank_page(){
		// $datos['contenido'] = $this->load->view('pages/login.html');
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name']='pages/login.html';
		 	$data['titulo']="Requisicion";
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