<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->helper('text'); //for content limiter
		$this->load->library('form_validation'); //load form validate rule
		$this->load->library('image_lib'); //load image library
		
		//$config['image_library'] = 'gd2';
//		$config['source_image']	= '/path/to/image/mypic.jpg';
//		$config['create_thumb'] = TRUE;
//		$config['maintain_ratio'] = TRUE;
//		$config['width']	= 320;
//		$config['height']	= 320;
		//$this->image_lib->initialize($config); //load image library
    }
	
	public function index()
	{
		if($this->session->userdata('logged_in')==FALSE){
			//register redirect page
			$s_data = array ('sfh_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('login'), 'refresh');		
		}
		
		$log_user_id = $this->session->userdata('sfh_id');
		
		//check notification variables
		$n = $this->input->get('n');
		$t = $this->input->get('t');
		$l = $this->input->get('l');
		
		if($n!='' && $t!='' && $l!=''){
			//update notification
			$upd_note = array(
				'status' => 1
			);
			
			$this->users->update_notification($n, $upd_note);	
			
			if($t=='moot reply'){
				$l = str_replace('-','#',$l);	
			}
			
			//re-direct page
			if($t=='moot' || $t=='moot reply' || $t=='join club' || $t=='leave club'){
				redirect(base_url('moot/'.$l), 'refresh');
			}
		}
		
		$data['notify'] = $this->users->query_notify_group_date($log_user_id);
		
		$data['title'] = 'Notifications | '.app_name;
		$data['page_active'] = 'notification';
		
		$this->load->view('designs/header', $data);
		$this->load->view('notifications/notification', $data);
		$this->load->view('designs/footer', $data);
	}
}