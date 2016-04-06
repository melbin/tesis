<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consultas extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('masterpage');
		$this->load->model('regional_model');
		$this->load->model('sistema/sistema_model');
		$this->load->model('inventario/productos_model','pro_model');
		$this->load->library('session');
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
			$data['titulo']="Menu de Sistema";
			$data['menu_sistema']=true;

		 	// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	public function reporte_solicitud_rechazo()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$data['deptos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', '{dpi_nombre}','',array('dpi_estado'=>1),null, '','dpi_id', true);
            $data["titulo"] ="Solicitudes Rechazadas";
			$data['vista_name'] = "reportes/solicitudes_rechazadas";
			$this->__cargarVista($data);
		}	
	}

	public function reporte_solicitud_finalizada()
	{

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$data['deptos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', '{dpi_nombre}','',array('dpi_estado'=>1),null, '','dpi_id', true);
            $data["titulo"] ="Solicitudes Finalizadas";
			$data['vista_name'] = "reportes/reporte_solicitud_finalizada";
			$this->__cargarVista($data);
		}	
	
	}

		public function reporte_movimiento_solicitud()
	{

		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// All your code goes here
			$solicitudes = $this->regional_model->detalle_sol();
			$html="<option value='0' selected>Seleccione</option>";
			foreach ($solicitudes as $key => $value) {
			$html .= "<option value= ".$value['sol_id']." > ".$value['sol_id'].'::'.$value['dpi_nombre'].'::'.'$'.number_format($value['des_total'],2,',','.')."</option>";
		}
			$data['solicitudes'] = $html;
            $data["titulo"] ="Movimiento de Solicitud";
			$data['vista_name'] = "reportes/reporte_movimiento_solicitud";
			$this->__cargarVista($data);
		}	
	
	}

	public function imprimir_solicitud_rechazo($excel=null)
	{
		//die(print_r($_POST,true));
		$id_depto = !empty($_POST['id_depto'])? $this->input->post('id_depto'): null;
		$fecha_in = !empty($_POST['fecha_in'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_in'])) : date('Y-m-01');
		$fecha_out= !empty($_POST['fecha_out'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_out'].date('H:i:s'))) : date('Y-m-t');
		$data['solicitudes'] = $this->regional_model->get_solicitudes_rechazadas($id_depto, $fecha_in, $fecha_out);
		
		$data['html'] = $this->load->view('reportes/tabla_solicitud_rechazada',$data,true);

		 if($excel==1){
            $filename = 'reporte_solicitud_rechazo_'.date('dmY').'_'.substr(uniqid(md5(rand()), true), 0, 7);
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
                $this->pdf->SetTitle('Regional - '.date('d-m-Y'));
                $this->pdf->SetAuthor('Melbin Cruz');
                $this->pdf->setPrintHeader(false);
                $this->pdf->setPrintFooter(false);
                $this->pdf->reportePDF('reportes/reporte_pdf', $data, 'Solicitudes Rechazadas');
        } else {
            echo json_encode(array('drop'=>$data['html'])); // Mostrar los resultados en una GRID
        }
	}

	public function imprimir_solicitud_finalizada($excel=null)
	{

		$id_depto = !empty($_POST['id_depto'])? $this->input->post('id_depto'): null;
		$fecha_in = !empty($_POST['fecha_in'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_in'])) : date('Y-m-01');
		$fecha_out= !empty($_POST['fecha_out'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_out'].date('H:i:s'))) : date('Y-m-t');
		$data['solicitudes'] = $this->regional_model->get_solicitudes_finalizadas($id_depto, $fecha_in, $fecha_out);
		
		$data['html'] = $this->load->view('reportes/tabla_solicitud_finalizada',$data,true);

		 if($excel==1){
            $filename = 'reporte_solicitud_finalizada_'.date('dmY').'_'.substr(uniqid(md5(rand()), true), 0, 7);
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
                $this->pdf->SetTitle('Regional - '.date('d-m-Y'));
                $this->pdf->SetAuthor('Melbin Cruz');
                $this->pdf->setPrintHeader(false);
                $this->pdf->setPrintFooter(false);
                $this->pdf->reportePDF('reportes/reporte_pdf', $data, 'Solicitudes Finalizadas');
        } else {
            echo json_encode(array('drop'=>$data['html'])); // Mostrar los resultados en una GRID
        }
	
	}

		public function imprimir_movimiento_solicitud($excel=null)
	{

		$id_solicitud = !empty($_POST['sol_id'])? $this->input->post('sol_id'): null;
		$fecha_in = !empty($_POST['fecha_in'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_in'])) : date('Y-m-01');
		$fecha_out= !empty($_POST['fecha_out'])? date('Y-m-d H:i:s', strtotime($_POST['fecha_out'].date('H:i:s'))) : date('Y-m-t');
		$data['solicitudes'] = $this->regional_model->get_seguimiento_solicitud($id_solicitud, $fecha_in, $fecha_out);
		$data['pdf'] = $excel;	
		$data['html'] = $this->load->view('reportes/tabla_seguimiento_solicitud',$data,true);

		 if($excel==1){
            $filename = 'reporte_movimiento_solicitud_'.date('dmY').'_'.substr(uniqid(md5(rand()), true), 0, 7);
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
                $this->pdf->SetTitle('Regional - '.date('d-m-Y'));
                $this->pdf->SetAuthor('Melbin Cruz');
				$this->pdf->setPrintHeader(false);
				$this->pdf->setPrintFooter(false);
				$this->pdf->SetMargins(-10,10, 10);
				
                $this->pdf->reportePDF('reportes/reporte_pdf', $data, 'Movimiento de Solicitud','L');
        } else {
            echo json_encode(array('drop'=>$data['html'])); // Mostrar los resultados en una GRID
        }
	
	}

    function __cargarVista($data=0)
	{	
	    $data['logo'] = $this->regional_model->get_parametro("logo");
		
		// Obtener los link del panel Izquierdo.
        $user_id    = $this->tank_auth->get_user_id();
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6, $user_id);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
} // End class reportes