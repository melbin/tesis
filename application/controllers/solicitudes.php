<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

	function __construct(){
		parent:: __construct();
		//$this->load->database();
		$this->load->helper('url');
		$this->load->library('masterpage');
		$this->load->model('Regional_model');

	}

	public function index()
	{	
		$datos['vista_name']='regional/solicitudes.php';
		$datos['titulo']="Solicitudes";
		$datos['logo'] = $this->Regional_model->get_parametro("logo");
		$datos['menu_sistema']=false;
		$this->__cargarVista($datos);
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