<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Features extends CI_Controller {

	function __construct(){
        parent::__construct();
		//$this->load->model('m_users'); //load MODEL
		$this->load->model('subscribe'); //load MODEL
		
		//mail config settings
		$this->load->library('email'); //load email library
		//$config['protocol'] = 'sendmail';
		//$config['mailpath'] = '/usr/sbin/sendmail';
		//$config['charset'] = 'iso-8859-1';
		//$config['wordwrap'] = TRUE;
		
		//$this->email->initialize($config);
    }
	
	public function index()
	{
		
		$data['title'] = 'Features | '.app_name;
		$data['page_active'] = 'features';

		$this->load->view('designs/hm_header', $data);
		$this->load->view('features', $data);
		$this->load->view('designs/hm_footer', $data);
	}
}