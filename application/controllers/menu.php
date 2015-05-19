<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

	function __construct(){
		parent:: __construct();
		//$this->load->database();
		$this->load->helper('url');
		$this->load->library('masterpage');
		$this->load->model('catalogos/Menu_model');
	}

	public function index()
	{	
		$datos['vista_name']='regional/sistema.php';
		$datos['titulo']="Sistema";
		$datos['logo'] = $this->Regional_model->get_parametro("logo");
		$datos['menu_sistema']=true;
		$datos['opcion_menu'] = $this->Regional_model->get_tabla('sio_sistema_opcion', array('sio_estado'=>1));
//		$data['menu_principal'] = $this->Menu_model->get_menu('sic_sistema_catalogo',$id_boton);
	//	$datos['menus'] = $this->load->view('menu/opciones_menu',$data, true);
	//	var_dump($datos['botones']); die();
		$this->__cargarVista($datos);
	}

	public function cargar_menu(){
		$id_boton = $this->input->post("id");
		//echo print_r($id_boton);
			$data['menu_principal'] = $this->Menu_model->get_menu('sic_sistema_catalogo',$id_boton);
		 //echo 	print_r($data);
		 $menus = $this->load->view('menu/opciones_menu',$data, true);
		 	
		 	echo $menus;
		
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