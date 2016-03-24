<?php
class Email_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

        public function sendEmail($from = null, $to = null, $subject = null, $message = null){

            $this->load->library('email');

            // $config['protocol'] = 'sendmail';
            // $config['mailpath'] = '/usr/sbin/sendmail';
            // $config['charset'] = 'utf-8';
            // $config['mailtype'] = 'html';
            // $config['wordwrap'] = TRUE;

            // $this->email->initialize($config);

            $this->email->from($from, 'Regional de Salud');

            $this->email->to($to);

            $this->email->subject($subject);

            $this->email->message($message);

            $this->email->send();

        }
}