<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_expenses'); //load MODEL
		$this->load->helper('text'); //for content limiter
		$this->load->library('form_validation'); //load form validate rule
    }
	
	public function index()
	{
		if($this->session->userdata('logged_in')==FALSE){
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');	
		}
		
		$log_user_id = $this->session->userdata('org_id');
		$data['err_msg'] = '';
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Dashboard | '.app_name;
		$data['page_active'] = 'dashboard';
		
		$this->load->view('designs/header', $data);
		$this->load->view('dashboard/dashboard', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function setup(){
		$log_user_id = $this->session->userdata('org_id');
		
		$this->form_validation->set_rules('user-settings-firstname','Firstname','trim|required|xss_clean');
		$this->form_validation->set_rules('user-settings-lastname','Lastname','trim|required|xss_clean');
		$this->form_validation->set_rules('user-settings-email','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>'); //error delimeter
		
		if ($this->form_validation->run() == FALSE) {
			$data['err_msg'] = '';
		} else {
			if($_POST){
				$new_firstname = $_POST['user-settings-firstname'];
				$new_lastname = $_POST['user-settings-lastname'];
				$new_email = $_POST['user-settings-email'];
				$new_theme = $_POST['user-settings-theme'];
				
				$upd_new = array(
					'firstname' => $new_firstname,
					'lastname' => $new_lastname,
					'email' => $new_email,
					'theme' => $new_theme,
				);
				
				if($this->users->update_user($log_user_id, $upd_new) > 0){
					$new_s_data = array(
						'org_user_email' => $new_email,
						'org_user_firstname' => $new_firstname,
						'org_user_lastname' => $new_lastname,
						'org_user_theme' => $new_theme,
					);
					$this->session->set_userdata($new_s_data);
				}
			}
			
			redirect(base_url('dashboard'), 'refresh');
		}
	}
	
	public function send_sms(){
		if($_POST){
			$s_phone = $_POST['s_phone'];
			$s_msg = $_POST['s_msg'];
			
			// redirect('http://www.gileadsms.com/components/com_spc/smsapi.php?username=organized&password=organized001&sender=BaseSME&recipient='.$s_phone.'&message='.$s_msg.'');	
		}
	}
}