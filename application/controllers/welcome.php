<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('masterpage');
		$this->load->model('Regional_model');
	}

	public function index()
	{	
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "pages/blank";
			$data['logo'] = $this->Regional_model->get_parametro("logo");
			$data['menu_sistema']=false;
	//		var_dump($data['logo']); die();
			$this->__cargarVista($data);
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