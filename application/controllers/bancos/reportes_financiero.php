<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes_financiero extends CI_Controller {

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
			$data['vista_name'] = "bancos/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Bancos";
			$data['menu_sistema']=true;

			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>5));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',5, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
		}
	}

	function reporte_fondos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$data['bodegas'] = $this->regional_model->get_dropdown('ali_almacen_inv', '{ali_nombre}','',array('ali_estado'=>1),null, '','ali_id', true);
            $data["titulo"] ="Reporte de distribución por fondo";
			$data['vista_name'] = "bancos/reportes_financiero/reporte_fondos";
			$this->__cargarVista($data);
		}		
	}

	function reporte_especifico()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$data['fondos'] = $this->regional_model->get_dropdown('fon_fondo', '{fon_nombre}','',array('fon_estado'=>1),null, '','fon_id', true);
            $data["titulo"] ="Reporte solicitud por específico";
			$data['vista_name'] = "bancos/reportes_financiero/reporte_especifico";
			$this->__cargarVista($data);
		}		
	}

	function reporte_saldos_congelados()
	{

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$data['bodegas'] = $this->regional_model->get_dropdown('ali_almacen_inv', '{ali_nombre}','',array('ali_estado'=>1),null, '','ali_id', true);
            $data["titulo"] ="Reporte de saldos congelados";
			$data['vista_name'] = "bancos/reportes_financiero/reporte_saldos_congelados";
			$this->__cargarVista($data);
		}		
	
	}

	function imprimir_especifico()
	{
		die(print_r("En desarollo..."));
	}

	function get_detalle_especifico($excel=null)
	{
		//die(print_r($_POST,true));
		$id_fondo = $this->input->post('id_fondo');
		$id_esp   = $this->input->post('id_especifico');
		$fecha_in = !empty($_POST['fecha_in'])? date('Y-m-d', strtotime($_POST['fecha_in'])) : date('Y-m-01');
		$fecha_out= !empty($_POST['fecha_out'])? date('Y-m-d', strtotime($_POST['fecha_out'])) : date('Y-m-t');
		$data['detalle'] = $this->regional_model->get_especifico_detalle($id_esp);
		$data['solicitudes'] = $this->regional_model->solicitudes_especifico($id_fondo, $id_esp, $fecha_in, $fecha_out);
		$data['html'] = $this->load->view('reportes/reporte_tabla_especifico',$data,true);

		 if($excel==1){
            $filename = 'reporte_detalle_especifico'.date('dmY').'_'.substr(uniqid(md5(rand()), true), 0, 7);
            // ob_end_clean();
            // ob_start();
            header("Content-Type: application/vnd.ms-excel");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("content-disposition: attachment;filename=" . $filename . ".xls");

            echo $data['html'];
        }
        else
        if($excel==2) {
                $this->load->library('pdf'); //libreria pdf
                $this->pdf->reportePDF('reportes/reporte_pdf', $data, 'Detalle por específico');
        } else {
            echo json_encode(array('drop'=>$data['html'])); // Mostrar los resultados en una GRID
        }
	}	
		

	function __cargarVista($data=0)
	{	
		// Datos generales de la pagina	
		$data['menu_sistema']=true; 	
		$user_id	= $this->tank_auth->get_user_id();
		$data['logo'] = $this->regional_model->get_parametro("logo");
		
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_id'=>5));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',5, $user_id);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */