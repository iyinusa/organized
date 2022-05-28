<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
		$this->load->library('form_validation');
		$this->load->helper('url');
		
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
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$data['err_msg'] = '';
		
		$allusers = $this->users->query_all_user();
		if(!empty($allusers)){
			$data['allusers'] = $allusers;
		}
		
		$data['title'] = 'Manage Accounts | '.app_name;
		$data['page_active'] = 'accounts';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/accounts', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function edit(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		//check for edit role
		$get_user_id = $this->input->get('user');
		if($get_user_id != ''){
			$data['rec_role'] = $get_user_id;
		} else {
			$data['rec_role'] = '';
		}
		
		if($_POST){
			$edit_id = $_POST['edit_id'];
			$role = $_POST['role'];
			$email = '';
			
			//get user email
			$gemail = $this->users->query_single_user_id($edit_id);
			if(!empty($gemail)){
				foreach($gemail as $umail){
					$email = $umail->email;	
					$firstname = $umail->firstname;
					$lastname = $umail->lastname;
				}
			}
			
			$update_data = array(
				'role' => $role
			);
			
			if($this->users->update_user($edit_id, $update_data) > 0){
				//email notification processing
				$this->email->clear(); //clear initial email variables
				$this->email->to($email);
				$this->email->from(app_email, app_name);
				$this->email->subject('Account Role Changed');
				
				//compose html body of mail
				$mail_subhead = 'Account Role Changed';
				$body_msg = '
					Please be notified that your Account Role has been changed to <b>'.$role.'</b> on '.app_name.'.<br /><br />Thanks
				';
				
				$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
				$this->email->set_mailtype("html"); //use HTML format
				$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);

				$this->email->message($mail_design);
				
				if($this->email->send()) {
					/////////////////////////////////////////////////////////////////////////////////////
					//send notification mail to admin
					$this->email->clear(); //clear initial email variables
					$this->email->to(app_email);
					$this->email->from($email,app_name);
					$this->email->subject('User Account Role Notification');
					
					//compose html body of mail
					$mail_subhead = 'User Account Role Notification';
					$body_msg = '
						This is to notify you that '.$firstname.' '.$lastname.' Account Role is now '.$role.' on '.app_name.'.<br /><br />Thanks
					';
					
					$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
					$this->email->set_mailtype("html"); //use HTML format
					$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
 
					$this->email->message($mail_design);
					
					if($this->email->send()) {}						
				}
				
				$data['err_msg'] = '<h5 class="alert alert-success">Role Changed. User already notified</h5>';	
			} else {
				$data['err_msg'] = '<h5 class="alert alert-info">No changes made</h5>';	
			}
		}
		
		$allusers = $this->users->query_all_user();
		if(!empty($allusers)){
			$data['allusers'] = $allusers;
		}
		
		$data['title'] = 'Manage Accounts | '.app_name;
		$data['page_active'] = 'accounts';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/accounts', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function remove(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		//check for edit role
		$get_user_id = $this->input->get('user');
		if($get_user_id != ''){
			$data['rec_del'] = $get_user_id;
		} else {
			$data['rec_del'] = '';
		}
		
		if($_POST){
			if(isset($_POST['cancel'])){
				redirect(base_url('admin/accounts'), 'refresh');
			} else {
				$del_id = $_POST['del_id'];
				
				if($del_id){
					$gq = $this->users->delete_user($del_id);
					redirect(base_url('admin/accounts'), 'refresh');
				}
			}
		}
		
		$allusers = $this->users->query_all_user();
		if(!empty($allusers)){
			$data['allusers'] = $allusers;
		}
		
		$data['title'] = 'Manage Accounts | '.app_name;
		$data['page_active'] = 'accounts';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/accounts', $data);
	  	$this->load->view('designs/footer', $data);
	}
}