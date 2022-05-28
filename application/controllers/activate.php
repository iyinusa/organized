<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activate extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->helper('url');
    }
	
	public function index() 
	{
		$stamp = $this->input->get('stamp');
		$email = $this->input->get('email');
		
		if($stamp=='' || $email==''){redirect(base_url().'activate/?stamp=nil&email=nil', 'refresh');}
		
		if($stamp=='' || $email==''){
			$data['err_msg'] = '<div class="alert alert-danger"><h5>Invalid activation link</h5></div>';
		} else {
			//check activation link
			$ch = $this->users->check_activation($stamp, $email);
			if(empty($ch)){
				$data['err_msg'] = '<div class="alert alert-danger"><h5>Invalid link or account already activated</h5></div>';
			} else {
				//activate email
				$reg_data = array(
					'activate' => 1
				);
				
				if(!$this->users->activate($email, $reg_data)){
					$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time. Please try later</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-success"><h5>Email activated.</h5></div>';
				}
			}
		}
		
		$data['title'] = 'Activate Email | '.app_name;
		$data['page_active'] = 'login';

	  	$this->load->view('login', $data);
	}
}