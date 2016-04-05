<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financiero extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->database();
		$this->load->library('masterpage');
		$this->load->model('email_model');
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
			$data['titulo']="Procesar Solicitudes";
			$data['vista_name'] = "financiero/procesar_solicitudes";

				// All your code goes here
			$data['financiero'] = 1;
			$data['solicitudes'] = $this->regional_model->detalle_sol_financiero();
			$data['html'] = $this->load->view('solicitudes/cargar_tabla',$data,true);
			$this->__cargarVista($data);
		}

	}

	public function aprobar_solicitud()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			//die(print_r($_POST,true));
			$id_solicitud = $this->input->post('solicitud');
			$observacion  = $this->input->post('observacion');

			$historial_sol = $this->regional_model->seguimiento_solicitud($id_solicitud);
			
			// Actualizar la Solicitud
			$sol_datos = array(
					'des_ets_id'		=> 4,
					'des_fecha_mod' 	=> date('Y-m-d H:i:s'),
					'des_observacion'	=> (!empty($observacion))? $observacion:null,
					'des_usu_mod'		=> $this->tank_auth->get_user_id()
				);

			$sol_registo = $this->regional_model->actualizar_registro('des_detalle_solicitud', $sol_datos, array('des_sol_id'=>$id_solicitud));

			if($sol_registo>0){

			// Actualizar el Log
            	$tmp_array = array(
            			'emh_sol_id'	=> $id_solicitud,
            			'emh_descripcion'	=> 'Aprobación de fondos por San Salvador',
            			'emh_fecha'			=> date('Y-m-d H:i:s'),
            			'emh_sol_monto'		=> NULL,
            			'emh_estado'		=> 1,
            			'emh_usu_crea'		=> $this->tank_auth->get_user_id(),
            			'emh_usu_mod'		=> $this->tank_auth->get_user_id(),
            			'emh_fecha_mod'		=> date('Y-m-d H:i:s')	
            		);
            	$emh_id = $this->regional_model->insertar_registro('emh_empleado_historial', $tmp_array);

				// Actualizacion del det_detalle_especifico
				$monto_solicitud = floatval($historial_sol['des_total']);
				$det_devengado   = floatval($historial_sol['det_saldo_devengado']);
				$det_saldo_ejecutado = floatval($historial_sol['det_saldo_ejecutado']);
				$subtotal = 0;
				if($monto_solicitud <= $det_devengado){
					$subtotal = $det_devengado - $monto_solicitud; // Nuevo det_saldo_devengado
				}
				
				$total = $det_saldo_ejecutado + $monto_solicitud; // Total saldo ejecutado

				$det_datos = array(
						'det_saldo_devengado'	=> $subtotal,
						'det_saldo_ejecutado'	=> $total,
						'det_usu_mod'			=> $this->tank_auth->get_user_id(),
						'det_fecha_mod'			=> date('Y-m-d H:i:s')
					);

				$det_registro = $this->regional_model->actualizar_registro('det_detalle_especifico', $det_datos, array('det_id'=> $historial_sol['det_id']));
				
				$cantidad = (floatval($historial_sol['axd_cantidad']) - floatval($historial_sol['des_total']));
				$reserva  = (floatval($historial_sol['axd_reserva']) - floatval($historial_sol['des_total']));
				$axd_array = array(
						'axd_cantidad'	=> ($cantidad>=0)? $cantidad:0,
						'axd_reserva'	=> ($reserva>=0)? $reserva:0,
						'axd_usu_mod'	=> $this->tank_auth->get_user_id(),
						'axd_fecha_mod'	=> date('Y-m-d H:i:s')
					);					

				$axd_registro = $this->regional_model->actualizar_registro('axd_asignacionxdetalle_especifico', $axd_array, array('axd_id'=> $historial_sol['axd_id']));

				// Enviar correo a Solicitante
					$mail_solicititante_flag = $this->regional_model->get_parametro('mail_to_solicitante');
					if($mail_solicititante_flag){
						$to = $this->regional_model->get_correo_solicitante($id_solicitud);
						$from = $this->regional_model->get_parametro('regional_mail');
						$subjet = "Seguimiento de Solicitud";
						$message  = "<b>Solicitud aprobada por San Salvador</b><br><br>";
						$message .= "Su solicitud se encuentra en Negociación con los Proveedores.<br><br>";
						$message .= "<i>Solicitud No:</i> <b>$id_solicitud</b>";
						
						if(!empty($to))
							@$this->email_model->sendEmail($from, $to, $subjet, $message);
					}

				// No llevaria Movimiento financiero. 
			$alerta=array('registro'=>$registro,'tipo_alerta'=> 'success','titulo_alerta'=>"Proceso Exitoso",'texto_alerta'=>"Se envió la solicitud a abastecimiento.");	
			} else {  // Solicitud actualizada 
				$alerta=array('tipo_alerta'=> 'error','titulo_alerta'=>"Acción no procesada",'texto_alerta'=>"No se pudo efectuar la tarea anterior.");
			}

			$this->session->set_flashdata($alerta);       
			redirect('home/financiero/procesar_solicitudes');										
		}
	} // End aprobar solicitud

	// function send_mail($to, $subject=NULL, $message, $from)
	// {
	
	// 	$headers ="From:<$from> \r\n";
	// 	$headers.="MIME-version: 1.0 \r\n";
	// 	$headers.="Content-type: text/html; charset= iso-8859-1\r\n";

	// 	mail($to, $subject, $message, $headers);

	// }

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
