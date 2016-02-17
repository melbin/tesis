<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'tcpdf/tcpdf.php';
class Pdf extends TCPDF
{
    public function __construct()
    {
         parent::__construct();
    }

    public function printPDF($html)
    {
		
		// set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
	    $this->SetSubject('Reporte');
	    $this->SetKeywords('Reporte, '.'');
	    // set font
	    $this->SetFont('dejavusans', '', 8);
	    $this->SetMargins(10,10, 10);
	    $this->setPrintHeader(false);

	    // set auto page breaks  (Dejarlo por pruebas)
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor  (Pruebas)
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
	    // add a page
	    $this->AddPage('L','Letter');
	    $this->writeHTML($html, true, false, false, false, '');

	    // output some RTL HTML content
		if (ob_get_contents()) ob_end_clean();
		$this->Output('solicitud_'.date('d-m-Y').'.pdf', 'I');
	} //Fin printPDF

	   public function reportePDF($vista, $data, $titulo=null, $orientacion='', $header=null)
    {
        $CI =& get_instance();
        $reporte = '';
        if($titulo!='' && $titulo!=null)
        {
         $reporte .= $CI->load->view('reportes/header_unico', array('titulo'=> $titulo), true);
        }
        
        $reporte .= $CI->load->view($vista, $data, true);

        $this->makePDF($reporte, $orientacion, $titulo);  
	} // End reportePDF

	    public function makePDF($output, $orientacion='', $reporte='',$formato='Letter',$salida='I')
    {

       // set document information$titulo,$header,$body,$generado
        $this->setPrintHeader(false);
        $this->SetSubject('Reporte');
        $this->SetKeywords('Reporte, '.$reporte);
        // set font
        $this->SetFont('dejavusans', '', 8);
        $this->SetMargins(10,10, 10);
        // add a page
        $this->AddPage($orientacion,$formato);
        
        $this->writeHTML($output, true, false, false, false, '');

        if (ob_get_contents()) ob_end_clean();
        $this->Output('Reporte_'.$reporte.'_'.date('Y-m-d_H-i-s').'.pdf', $salida);
    }// End makePDF
}    








