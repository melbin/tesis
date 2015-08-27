<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends CI_Controller {

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
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['vista_name'] = "sistema/index";
			$data['logo'] = $this->regional_model->get_parametro("logo");
			$data['titulo']="Menu de Sistema";
			$data['menu_sistema']=true;

		 	// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

			$this->__cargarVista($data);
		}
	}

	function reporte_existencias()
    {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {

            $data['logo'] = $this->regional_model->get_parametro("logo");
			$data["titulo"] ="Reporte de existencias";
			$data['vista_name'] = "reportes/reporte_existencias";

			// All your code goes here
			$data['detalle']=$this->regional_model->get_existencias2();
			
			// Obtener los link del panel Izquierdo.
			$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
			$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
		 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);
		 	
			$this->__cargarVista($data);
        }
    }// End reporte de Existencias

    function por_departamento()
    {
    	if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else {

			$data["titulo"] ="Inventario por departamento";
			$data['vista_name'] = "reportes/por_departamento";


			$this->__cargarVista($data);
        }
    } // End reporte por departamento

    function print_existencia($tipo=1)
    {
        //$tipo=1 (excel), $tipo=2 (pdf)
        $cantidad=0;
        $total=0;
        $data = $this->regional_model->get_existencias2();
        //print_r($this->db->last_query());exit();
        $rows="<b>REPORTE DE EXISTENCIAS</b>";
        if($data){
            $cadena='<meta http-equiv="content-type" content="text/html; charset=utf-8"><table class="tabla" style="border:1px solid black;">';
            $cadena.='<tr style="background-color: #F0F0F0;">';
            $cadena.='<th style="text-align:center; font-weight:bold;">Bodega</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Categoría</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Código</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Nombre</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">U.M</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Existencia</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Costo</th>';
            $cadena.='<th style="text-align:center; font-weight:bold;">Total</th>';
            $cadena.='</tr>';
            $suma_exis=0;
            $suma_costo=0;
            $suma_total = 0;
            foreach ( $data as $sld ) {
                $suma_exis += $sld['cantidad'];
                $suma_costo += $sld['precio'];
                $suma_total += ($sld['precio']*$sld['cantidad']);

                $cadena.='<tr>';
                $cadena.='<td style="text-align:center;">'.$sld['bodega'].'</td>';
                $cadena.='<td style="text-align:center;">'.$sld['linea'].'</td>';
                $cadena.='<td style="text-align:center;">'.$sld['codigo'].'</td>';
                $cadena.='<td style="text-align:center;">'.$sld['nombre'].'</td>';
                $cadena.='<td style="text-align:center;">'.$sld['UM'].'</td>';
                $cadena.='<td style="text-align:center;">'.number_format($sld['cantidad'],2).'</td>';
                $cadena.='<td>'."$ ".number_format($sld['precio'],2).'</td>';
                $cadena.='<td>'."$ ".number_format($sld['precio']*$sld['cantidad'],2).'</td>';
                $cadena.='</tr>';
                $cantidad+=(int)$sld['cantidad'];
                $total+=(int)$sld['total'];
            } // End foreach
            $cadena.='<tr style="background-color: #C0C0C0;" ><td colspan="5"><b>Total:</b></td><td style="text-align: center;"><b>'.number_format($cantidad,2).'</b></td><td style="text-align: right;"></td><td style="text-align: right;"><b>$'.number_format($suma_total,2).'</b></td></tr>';
            $cadena.='</table>';
        }
        else{
            $cadena.='<tr>';
            $cadena.='<td>0</td>';
            $cadena.='<td colspan="7" text-align="center">Cero Registros Encontrados</td>';
            $cadena.='<td class="center"></td>';
            $cadena.='<td></td>';
            $cadena.='<td></td>';
            $cadena.='<td></td>';
            $cadena.='<td></td>';
            $cadena.='<td></td>';
            $cadena.='</tr>';
            $cadena.='</table>';
        }
        //Nombre del archivo 
        $filename = 'rpt_'.date('dmY').'_'.substr(uniqid(md5(rand()), true), 0, 7);
        
        //Obtener datos y construir secciones del reporte
        $data['table_header']     = $rows;
        $data['table_tbody']      = $cadena;
        $data['filename']         = $filename;

        if($tipo==1){
            $this->load->view('reportes/reporte_excel.php', $data);
        }
        else if($tipo==2){
            $this->load->library('pdf'); //libreria pdf
            $this->pdf->reportePDF('reportes/reporte_existencia_pdf', $data, 'Existencias');
        }
	}

    function __cargarVista($data=0)
	{	
	    $data['logo'] = $this->regional_model->get_parametro("logo");
		
		// Obtener los link del panel Izquierdo.
		$info['info_padre'] = $this->sistema_model->get_registro('sio_sistema_opcion',array('sio_estado'=>1,'sio_menu'=>1));
		$info['menu_principal'] = $this->sistema_model->get_menu('sic_sistema_catalogo',6);
	 	$data['menus'] = $this->load->view('menu/opciones_menu',$info, true);

		$vista=$data['vista_name'];
		$this->masterpage->setMasterpage('/pages/masterpage');
		$this->masterpage->addContentPage($vista,'content',$data);
		$this->masterpage->show();
	}
} // End class reportes












