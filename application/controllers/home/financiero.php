<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financiero extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
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
			$data['titulo']="Módulo Financiero";
			$data['menu_sistema']=true;

		 	// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	public function presupuesto()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
		 // All your code goes here
			$data['titulo']="Presupuesto por Departamento";
			$data['vista_name'] = "financiero/presupuesto_departamento";	

			$this->__cargarVista($data);
		}	
	}

		public function congelar_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
		 // All your code goes here
			$data['titulo']="Congelar fondos por específico";
			$data['vista_name'] = "financiero/congelar_fondos";	
			
			$this->__cargarVista($data);
		}	
	}

	public function procesar_solicitudes()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['titulo']="Solicitudes pendientes";
			$data['vista_name'] = "financiero/procesar_solicitudes";

				// All your code goes here
			$data['financiero'] = 1;
			$data['solicitudes'] = $this->regional_model->detalle_sol_financiero();
			$data['html'] = $this->load->view('solicitudes/cargar_tabla',$data,true);
			$this->__cargarVista($data);
		}

	}
		function __cargarVista($data=0)
	{	
		$vista=$data['vista_name'];
		$user_id	= $this->tank_auth->get_user_id();
		$data['logo'] = $this->regional_model->get_parametro("logo");
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
		 $data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
 } //Fin del Controller
