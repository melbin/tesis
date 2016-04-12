<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

    function __construct() {
        parent:: __construct();
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $this->load->library('masterpage');
        $this->load->library('enLetras');
        $this->load->model('email_model');
        $this->load->model('regional_model');
        $this->load->model('sistema/sistema_model');
        $this->load->model('inventario/productos_model');
    }

    // Al hacer una peticion a esta pagina, es porque se quiere acceder al menu de sistema.
    // Por eso no es necesario jalar el id de sistema.

    public function index() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            $user_id = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['vista_name'] = "sistema/index";
            $data['logo'] = $this->regional_model->get_parametro("logo");
            $data['titulo'] = "Menu de Sistema";
            $data['menu_sistema'] = true;

            // Obtener los link del panel Izquierdo.
            $info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion', array('sio_estado' => 1, 'sio_menu' => 1));
            $info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo', 6, $user_id);
            $data['menus'] = $this->load->view('menu/opciones_menu', $info, true);

            $this->__cargarVista($data);
        }
    }

    function cargar_productosxcategoria() {
        $id_cat = $this->input->post('id');
        $subcategoria = $this->regional_model->get_productosxcategoria($id_cat);
        //echo $this->db->last_query();
        $html = "<option value='0' selected>Seleccione</option>";
        foreach ($subcategoria as $key => $value) {
            $html .= "<option value= " . $value['pro_id'] . " > " . $value['pro_nombre'] . '::' . $value['pro_codigo'] . "</option>";
        }

        $arreglo = array('drop' => $html);
        echo json_encode($arreglo);
    }

    function cargar_productosxsubcategoria() {
        $id_sub = $this->input->post('id');
        $productos = $this->regional_model->cargar_productosxsubcategoria($id_sub);

        $html = "<option value='0' selected>Seleccione</option>";
        foreach ($productos as $key => $value) {
            $html .= "<option value= " . $value['pro_id'] . " > " . $value['pro_nombre'] . '::' . $value['pro_codigo'] . "</option>";
        }

        $arreglo = array('drop' => $html);
        echo json_encode($arreglo);
    }

    function obtener_precio() {
        $id_pro = $this->input->post('id');
        $precio = '';
        if ($id_pro != 0) {
            //$precio = $this->sistema_model->get_campo('sar_saldo_articulo','sar_cantidad', array('sar_pro_id'=>$id_pro, 'sar_estado'=>1));	
            $existencias = $this->regional_model->get_existencias(array('sar_pro_id' => $id_pro, 'sar_estado' => 1));
            //die(print_r($existencias));
            if (!empty($existencias)) {
                $precio = $existencias['sar_precio'];
            }
        }

        $html = '';
        if (!empty($existencias)) {
            $unidades = 'Unidad';
            $existen = 'Hay';
            if ($existencias['sar_cantidad'] > 1) {
                $unidades = 'Unidades';
                $existen = 'Existen';
            }
            $html = $existen . ' ' . $existencias['sar_cantidad'] . ' ' . $unidades . ' en la bodega ' . $existencias['ali_nombre'] . '.';
        }

        // Obtener Unidad de Medida
        $um = $this->productos_model->get_um(array('pro_id' => $id_pro));

        // Obtener la descripcion del articulo
        $descripcion = $this->sistema_model->get_campo('pro_producto', 'pro_descripcion', array('pro_id' => $id_pro));

        $arreglo = array("drop" => $precio, "existencias" => $html, 'um' => $um['uni_valor'], 'descripcion' => $descripcion);
        echo json_encode($arreglo);
    }

    function cargar_subcategorias() {
        $id_cat = $this->input->post('id');
        $subcategoria = $this->regional_model->get_dropdown('sub_subcatalogo', '{sub_nombre}::{sub_codigo}', '', array('sub_cat_id' => $id_cat, 'sub_estado' => 1), null, '', 'sub_id', true);
        //echo $this->db->last_query();
        $arreglo = array('drop' => $subcategoria);
        echo json_encode($arreglo);
    }

    function cargar_productos() {
        $id = $this->input->post('id');
        $productos = $this->regional_model->get_dropdown('sub_subcatalogo', '{sub_nombre}::{sub_codigo}', '', array('sub_id' => $id_cat, 'sub_estado' => 1), null, '', 'sub_id', true);
    }

    function cargar_direccion() {
        $id_bodega = $this->input->post('id');
        $bodega = $this->sistema_model->get_registro('ali_almacen_inv', array('ali_id' => $id_bodega, 'ali_estado' => 1));
        //echo print_r($bodega);
        $arreglo = array('drop' => $bodega);
        echo json_encode($arreglo);
    }

    function filtrar_solicitudes() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            // All your code goes here.
            if ($_POST) {
                //die(print_r($_POST));

                $where = array();
                if (!empty($_POST['tipo_req'])) {
                    $where['des_ets_id'] = $this->input->post('tipo_req');
                }
                if ($this->input->post('filtro') == 'sol_dpi_id') {
                    $where['sol_dpi_id'] = $this->input->post('valor');
                } else
                if ($this->input->post('filtro') == 'sol_ali_id') {
                    $where['sol_ali_id'] = $this->input->post('valor');
                } else {
                    if ($this->input->post('filtro') == 'sol_fecha') {
                        $fechas = explode('#', $this->input->post('valor'));
                        $where["date_format(sol_fecha,'%Y-%m-%d')  >="] = date('Y-m-d', strtotime($fechas[0]));
                        $where["date_format(sol_fecha,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($fechas[1]));
                    }
                }
                //die(print_r($where,true));
                $data['solicitudes'] = $this->regional_model->detalle_solicitud($where);
                $data['usuario_final'] = 1;
                $html = $this->load->view('solicitudes/cargar_tabla', $data, true);
                echo json_encode(array('drop' => $html));
            }
        }
    }

    function cargar_alerta_rechazada() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            $id_sol = $this->input->post('id');
            $etiqueta = $this->sistema_model->get_registro('res_rechazo_solicitud', array('res_sol_id' => $id_sol, 'res_estado' => 1));
            echo json_encode(array('drop' => $etiqueta['res_descripcion']));
        }
    }

    function imprimir_pdf($id_sol) {
        $data['id'] = $id_sol;

        $html = $this->load->view('solicitudes/imprimir_solicitud_pdf', $data, true);

        $this->load->library('pdf'); //libreria pdf
        $this->pdf->printPDF($html);
    }

    function imprimir_excel($id_sol, $imprime = null) {

        // prueba de fecha en spanish

        $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        //die(print_r($dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y')));	 
        //Salida: Viernes 24 de Febrero del 2012
        $detalle_sol = $this->regional_model->detalle_sol($id_sol);
        $data['detalle_sol'] = $detalle_sol[0];
        $data["valor_letras"] = strtoupper($this->enletras->ValorEnLetras($detalle_sol[0]['des_total'], 'DÓLARES')) . ' DE LOS ESTADOS UNIDOS DE AMÉRICA';
        $fecha = $detalle_sol[0]['sol_fecha'];

        $data['dia'] = date('d', strtotime($fecha));
        $data['mes'] = $meses[date('n', strtotime($fecha)) - 1];
        $data['anio'] = date('Y', strtotime($fecha));

        // Aca iran los detalles de los articulos a comprar
        $productos = $this->regional_model->detalle_sol_productos($id_sol);
        $data['productos'] = $productos;

        // Parametros de Involucrados en la Solicitud
        $data['coordinador_abastecimiento'] = $this->regional_model->get_parametro('coordinador_abastecimiento');
        $data['coordinador_primer_nivel'] = $this->regional_model->get_parametro('coordinador_primer_nivel');
        $data['director_regional'] = $this->regional_model->get_parametro('director_regional');
        $data['jefe_ufi'] = $this->regional_model->get_parametro('jefe_ufi');
        $data['solicitante'] = $this->sistema_model->datos_persona($this->tank_auth->get_user_id());

        $html = $this->load->view('solicitudes/imprimir_solicitud_excel', $data, true);

        if ($imprime == 1)
            die(print_r($html, true));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=solicitudes_" . date('m-d-Y') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $html;
    }

    function ver_solicitudes() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {

            $data['logo'] = $this->regional_model->get_parametro("logo");
            $data['titulo'] = "Seguimiento de Solicitudes";
            $data['vista_name'] = "solicitudes/ver_solicitudes";

            // All your code goes here
            $data['solicitudes'] = $this->regional_model->detalle_solicitud();
            $data['usuario_final'] = 1;
            $data['html'] = $this->load->view('solicitudes/cargar_tabla', $data, true);

            $data['departamentos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', '{dpi_nombre}', '', array('dpi_estado' => 1), null, '', 'dpi_id', true);
            $data['estado'] = $this->regional_model->get_dropdown('ets_estado_solicitud', '{ets_nombre}', '', array('ets_estado' => 1), null, '', 'ets_id', true);
            $data['bodegas'] = $this->regional_model->get_dropdown('ali_almacen_inv', '{ali_nombre}', '', array('ali_estado' => 1), null, '', 'ali_id', true);
            //print_r($this->db->last_query()); die();
            //print_r($data['html']); die();
            // Obtener los link del panel Izquierdo.
            $user_id = $this->tank_auth->get_user_id();
            $info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion', array('sio_estado' => 1, 'sio_menu' => 1));
            $info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo', 6, $user_id);
            $data['menus'] = $this->load->view('menu/opciones_menu', $info, true);

            $this->__cargarVista($data);
        }
    }

    function crear_solicitud() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {

            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['username'] = $this->tank_auth->get_username();
            $data['vista_name'] = "solicitudes/crear_solicitud";
            $data['logo'] = $this->regional_model->get_parametro("logo");
            $data['titulo'] = "Crear Solicitud";

            // Cargar los datos para las solicitudes
            //$data['dep_internos'] = $this->regional_model->get_dropdown('dpi_departamento_interno','dpi_nombre','',array('dpi_estado'=>1),null,'','dpi_id',true);
            $data['bodega'] = $this->regional_model->get_dropdown('ali_almacen_inv', 'ali_nombre', '', array('ali_estado' => 1), null, '', 'ali_id', true);
            $data['categoria'] = $this->regional_model->get_dropdown('cat_catalogo', '{cat_nombre}::{cat_codigo}', '', array('cat_estado' => 1), null, '', 'cat_id', true);
            $data['fondo'] = $this->regional_model->get_dropdown('fon_fondo', 'fon_nombre', '', array('fon_estado' => 1), null, '', 'fon_id', true);
            $data['unidad_medida'] = $this->regional_model->get_dropdown('uni_unidad_medida', 'uni_valor', '', array('uni_estado' => 1), null, '', 'uni_id', true);

            // Obtener los link del panel Izquierdo.
            $user_id = $this->tank_auth->get_user_id();
            $info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion', array('sio_estado' => 1, 'sio_menu' => 1));
            $info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo', 6, $user_id);
            $data['menus'] = $this->load->view('menu/opciones_menu', $info, true);

            $this->__cargarVista($data);
        }
    }

    function entrada_solicitud() {
        if ($_POST) {
            //		print_r($_POST); die();
            // Regristar la solicitud
            $solicitud = array(
                'sol_dpi_id' => $this->input->post('dpi_interno'),
                'sol_fecha' => date('Y-m-d H:i:s'), //date('Y-m-d H:i:s',strtotime($_POST['fecha_entrega'].date('H:i:s'))),
                'sol_num_entregas' => $this->input->post('numero_entrega'),
                'sol_ali_id' => $this->input->post('bodega'),
                'sol_soe_id' => 1,
                'sol_tps_id' => NULL,
                'sol_estado' => 1,
                'sol_usu_mod' => $this->tank_auth->get_user_id(),
                'sol_usu_crea' => $this->tank_auth->get_user_id(),
                'sol_fecha_mod' => date('Y-m-d H:i:s')
            );

            $sol_id = $this->regional_model->insertar_registro('sol_solicitud', $solicitud);
        }

        if ($sol_id > 0) {
            $detalle = array(
                'des_fecha' => date('Y-m-d H:i:s'),
                'des_total' => $this->input->post('total'),
                'des_fon_id' => $this->input->post('fondo'),
                'des_cat_id' => $this->input->post('categoria'),
                'des_esp_id' => $this->input->post('especifico'),
                'des_plazo_entrega' => $this->input->post('plazo_entrega'),
                'des_direccion' => $this->input->post('lugar_entrega'),
                'des_sol_id' => $sol_id,
                'des_ets_id' => 1,
                'des_estado' => 1,
                'des_usu_mod' => $this->tank_auth->get_user_id(),
                'des_fecha_mod' => date('Y-m-d H:i:s')
            );
            $des_id = $this->regional_model->insertar_registro('des_detalle_solicitud', $detalle);

            // Actualizar el Log
            if($des_id > 0 ){
            	$tmp_array = array(
            			'emh_sol_id'	=> $sol_id,
            			'emh_descripcion'	=> 'Crear solicitud',
            			'emh_fecha'			=> date('Y-m-d H:i:s'),
            			'emh_sol_monto'		=> $this->input->post('total'),
            			'emh_estado'		=> 1,
            			'emh_usu_crea'		=> $this->tank_auth->get_user_id(),
            			'emh_usu_mod'		=> $this->tank_auth->get_user_id(),
            			'emh_fecha_mod'		=> date('Y-m-d H:i:s')	
            		);
            	$emh_id = $this->regional_model->insertar_registro('emh_empleado_historial', $tmp_array);
            }


            // Insertar articulo asociado a solicitud
            $productos = $this->input->post('productos');
            $categoria = $this->input->post('categoria');
            $descripcion = $this->input->post('descripcion');
            $cantidad = $this->input->post('cantidad');
            $unidad_medida = $this->input->post('unidad_med');
            //$financiamiento = $this->input->post('financiamiento');
            $precios = $this->input->post('precios');

            foreach ($productos as $key => $value) {
                $pro_solicitud = array(
                    'pxs_sol_id' => $sol_id,
                    //  'pxs_cat_id'	=> $categoria[$key],
                    'pxs_pro_id' => $value,
                    //  'pxs_uni_id'	=> $unidad_medida[$key],
                    'pxs_cantidad' => $cantidad[$key],
                    'pxs_precio' => $precios[$key],
                    'pxs_descripcion' => $descripcion[$key],
                    'pxs_estado' => 1,
                    'pxs_usu_mod' => $this->tank_auth->get_user_id(),
                    'pxs_fecha_mod' => date('Y-m-d H:i:s')
                );
                $pro_sol = $this->regional_model->insertar_registro('pxs_productoxsolicitud', $pro_solicitud);
            }

            // Actualizar axd_asignacionxdetalle_especifico
            // Si hay fondos en reserva, obtenerlos
            $fondo_reserva = $this->sistema_model->get_campo('axd_asignacionxdetalle_especifico', 'axd_reserva', array('axd_id' => $_POST['axd_id']));
            $total = floatval($_POST['total']);
            if (!empty($fondo_reserva)) {
                $total += floatval($fondo_reserva);
            }

            $this->sistema_model->actualizar_registro('axd_asignacionxdetalle_especifico', array('axd_reserva' => $total, 'axd_usu_mod' => $this->tank_auth->get_user_id(), 'axd_fecha_mod' => date('Y-m-d')), array('axd_id' => $_POST['axd_id']));

            // Actualizar el det_detalle_especifico
            $det_registro = $this->sistema_model->get_registro('det_detalle_especifico', array('det_esp_id' => $_POST['especifico'], 'det_fondo_id' => $_POST['fondo']));

            $total = floatval($_POST['total']);
            if (!empty($det_registro['det_saldo_devengado']) && $det_registro['det_saldo_devengado'] > 0) {
                $total += floatval($det_registro['det_saldo_devengado']);
            }

            $this->sistema_model->actualizar_registro('det_detalle_especifico', array('det_saldo_devengado' => $total, 'det_usu_mod' => $this->tank_auth->get_user_id(), 'det_fecha_mod' => date('Y-m-d')), array('det_id' => $det_registro['det_id']));

            // crear alerta OK
            $alerta = array('registro' => $registro, 'tipo_alerta' => 'success', 'titulo_alerta' => "Proceso Exitoso", 'texto_alerta' => "Creación de solicitud exitosa.");
        } else {
            // crear alerta FAIL
            $alerta = array('tipo_alerta' => 'error', 'titulo_alerta' => "Solicitud no procesada", 'texto_alerta' => "Por favor, revisar datos ingresados.");
        }
        $this->session->set_flashdata($alerta);
        redirect('welcome');
    }

    function registrar_compra($id_solicitud = 0) {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {
            if ($_POST) {
                // Procesar formulario

                $proveedor = $this->input->post('proveedor');
                $contrato = $this->input->post('contrato');
                $monto = $this->input->post('monto');
                $retencion = $this->input->post('retencion');
                $descripcion = $this->input->post('descripcion');
                $renta = $this->input->post('renta');
                $nit = $this->input->post('nit');
                $solicitud = $this->regional_model->detalle_sol($_POST['sol_id']);
                
                // Actualizar el estado de la solicitud
                $this->sistema_model->actualizar_registro('des_detalle_solicitud', array('des_ets_id' => 5, 'des_usu_mod' => $this->tank_auth->get_user_id(), 'des_fecha_mod' => date('Y-m-d H:i:s')), array('des_sol_id' => $_POST['sol_id']));

                foreach ($proveedor as $key => $value) {

                    $array_contratista = array(
                        'con_prv_id' => $value,
                        'con_contrato' => isset($contrato[$key]) ? $contrato[$key] : null,
                        'con_monto' => isset($monto[$key]) ? $monto[$key] : null,
                        'con_retencion' => isset($retencion[$key]) ? $retencion[$key] : null,
                        'con_renta' => isset($renta[$key]) ? $renta[$key] : null,
                        'con_nit' => isset($nit[$key]) ? $nit[$key] : null,
                        'con_descripcion' => isset($descripcion[$key]) ? $descripcion[$key] : null,
                        'con_estado' => 1,
                        'con_usu_mod' => $this->tank_auth->get_user_id(),
                        'con_fecha_mod' => date('Y-m-d H:i:s')
                    );

                    $id_contratista = $this->regional_model->insertar_registro('con_contratista', $array_contratista);

                    if($id_contratista > 0){
                    	// Actualizar el Log
		            	$tmp_array = array(
		            			'emh_sol_id'	=> $_POST['sol_id'],
		            			'emh_descripcion'	=> "Registrar compra (Contrato = $contrato[$key], Monto = \$$monto[$key])",
		            			'emh_fecha'			=> date('Y-m-d H:i:s'),
		            			'emh_sol_monto'		=> NULL,
		            			'emh_estado'		=> 1,
		            			'emh_usu_crea'		=> $this->tank_auth->get_user_id(),
		            			'emh_usu_mod'		=> $this->tank_auth->get_user_id(),
		            			'emh_fecha_mod'		=> date('Y-m-d H:i:s')	
		            		);
		            	$emh_id = $this->regional_model->insertar_registro('emh_empleado_historial', $tmp_array);
                    }

                    if ($id_contratista > 0) {
                        $detalle_array = array(
                            'cxs_con_id' => $id_contratista,
                            'cxs_sol_id' => $_POST['sol_id'],
                            'cxs_estado' => 1,
                            'cxs_usu_mod' => $this->tank_auth->get_user_id(),
                            'cxs_fecha_mod' => date('Y-m-d H:i:s')
                        );
                        $this->regional_model->insertar_registro('cxs_contratistaxsolicitud', $detalle_array);
                    }
                }

                if (isset($id_contratista) && $id_contratista > 0) {

                	// Enviar correo a Solicitante
					$mail_solicititante_flag = $this->regional_model->get_parametro('mail_to_solicitante');
					
					if($mail_solicititante_flag && $solicitud[0]['des_ets_id']!=5){

						$to = $this->regional_model->get_correo_solicitante($this->input->post('sol_id'));
						$from = $this->regional_model->get_parametro('regional_mail');
						$subjet = "Solicitud (Finalizada) - Regional";
						$message = "Su solicitud ya fue procesada satisfactoriamente.<br>";
						$message .= "Puede pasar a la bodega: <b>".$solicitud[0]['ali_nombre']."</b> a retirar sus productos.<br><br>";
						$message .= "<i>Solicitud No:</i> <b>".$this->input->post('sol_id')."</b>";

						if(!empty($to))
							@$this->email_model->sendEmail($from, $to, $subjet, $message);

					}

                    // crear alerta OK
                    $alerta = array('registro' => $registro, 'tipo_alerta' => 'success', 'titulo_alerta' => "Registro ingresado", 'texto_alerta' => "Registro ingresado exitosamente.");
                } else {
                    // crear alerta FAIL
                    $alerta = array('tipo_alerta' => 'error', 'titulo_alerta' => "Error de ingreso", 'texto_alerta' => "Por favor, revisar datos ingresados.");
                }
                $this->session->set_flashdata($alerta);
                redirect('home/abastecimiento/entrada_de_articulos');
            } else {
                // Llenar formulario
                $sol_array = $this->regional_model->detalle_sol($id_solicitud);
                $data['sol_array'] = $sol_array[0];
                $data['user_id'] = $this->tank_auth->get_user_id();
                $data['username'] = $this->tank_auth->get_username();
                $data['vista_name'] = "solicitudes/registrar_compra";
                $data['logo'] = $this->regional_model->get_parametro("logo");
                $data['titulo'] = "Registrar compra";

                // Cargar la informacion principal de la solicitud
                $data['dep_internos'] = $this->regional_model->get_dropdown('dpi_departamento_interno', 'dpi_nombre', '', array('dpi_estado' => 1), (!empty($sol_array[0]['sol_dpi_id'])) ? $sol_array[0]['sol_dpi_id'] : null, '', 'dpi_id', true);
                $data['clase_suministro'] = $this->regional_model->get_dropdown('cat_catalogo', 'cat_nombre', '', array('cat_estado' => 1), (!empty($sol_array[0]['des_cat_id'])) ? $sol_array[0]['des_cat_id'] : null, '', 'cat_id', true);
                $data['bodega'] = $this->regional_model->get_dropdown('ali_almacen_inv', 'ali_nombre', '', array('ali_estado' => 1), (!empty($sol_array[0]['sol_ali_id'])) ? $sol_array[0]['sol_ali_id'] : null, '', 'ali_id', true);
                $data['fondo'] = $this->regional_model->get_dropdown('fon_fondo', 'fon_nombre', '', array('fon_estado' => 1), (!empty($sol_array[0]['des_fon_id'])) ? $sol_array[0]['des_fon_id'] : null, '', 'fon_id', true);
                $data['proveedor'] = $this->regional_model->get_dropdown('prv_proveedor', '{prv_nombre} {prv_apellido}', '', array('prv_estado' => 1), null, '', 'prv_id', true);

                $datos['contratistas'] = $this->sistema_model->get_contratistas($id_solicitud);
                $data['html'] = $this->load->view('solicitudes/cargar_tabla_contratistas', $datos, true);

                // Obtener los link del panel Izquierdo.
                $user_id = $this->tank_auth->get_user_id();
                $info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion', array('sio_estado' => 1, 'sio_menu' => 1));
                $info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo', 6, $user_id);
                $data['menus'] = $this->load->view('menu/opciones_menu', $info, true);


                $this->__cargarVista($data);
            }
        }
    }

    function send_mail($to, $subject=NULL, $message, $from)
	{
	
		$headers ="From:<$from> \r\n";
		$headers.="MIME-version: 1.0 \r\n";
		$headers.="Content-type: text/html; charset= iso-8859-1\r\n";

		mail($to, $subject, $message, $headers);

	}

    function __cargarVista($data = 0) {
        $vista = $data['vista_name'];
        $this->masterpage->setMasterpage('/pages/masterpage');
        $this->masterpage->addContentPage($vista, 'content', $data);
        $this->masterpage->show();
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */