<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->library('image_lib');
		
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
		if($this->session->userdata('logged_in') == TRUE){
			
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		//check for variables
		$get_id = $this->input->get('user'); //user ID
		
		//get customer info
		if($get_id){
			$user_id = $get_id;
		} else {
			$user_id = $log_user_id;
		}
		
		$csu = $this->users->query_single_user_id($user_id);
		if(!empty($csu)){
			foreach($csu as $us){
				$data['user_id'] = $user_id;
				$data['social_title'] = ucwords($us->title);
				$data['firstname'] = ucwords($us->firstname);
				$data['lastname'] = ucwords($us->lastname);
				$data['email'] = $us->email;
				$data['dob'] = $us->dob;
				$data['sex'] = $us->sex;
				$data['phone'] = $us->phone;
				$data['address'] = $us->address;
				$data['city'] = $us->city;
				$data['state'] = $us->state;
				$data['country'] = $us->country;
				$data['acc_activate'] = $us->activate;
				$data['user_status'] = $us->user_status;
				$data['reg_date'] = $us->reg_date;
				$data['user_lastlog'] = $us->user_lastlog;
				
				if($us->pics_small=='' || file_exists(FCPATH.$us->pics_small)==FALSE){$data['pics_small']='img/icon120.png';}else{$data['pics_small']=$us->pics_small;}
			}
		}
		
		$data['title'] = 'Profile Page | '.app_name;
		$data['page_active'] = 'profile';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('profile', $data);
	  	$this->load->view('designs/footer', $data);
	}
}