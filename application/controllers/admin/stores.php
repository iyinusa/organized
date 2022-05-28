<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stores extends CI_Controller {

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
		
		$allstores = $this->m_stores->query_all_store();
		if(!empty($allstores)){
			$data['allstores'] = $allstores;
		}
		
		$data['title'] = 'Manage Stores | '.app_name;
		$data['page_active'] = 'stores';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/stores', $data);
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
		
		//check for edit
		$get_user_id = $this->input->get('user');
		if($get_user_id != ''){
			$data['active_edit'] = TRUE;
			$data['get_user'] = $get_user_id;
			$getpre = $this->users->query_premium_user($get_user_id);
			if(!empty($getpre)){
				foreach($getpre as $pre){
					$data['e_id'] = $pre->id;
					$data['e_user_id'] = $pre->user_id;
					$data['e_type'] = $pre->type;
					
					if($pre->trial == 0){
						$data['e_start_date'] = $pre->main_start;
						$data['e_due_date'] = $pre->main_end;
					} else {
						$data['e_start_date'] = $pre->trial_start;
						$data['e_due_date'] = $pre->trial_end;
					}
					
					if($pre->status == 0){
						$data['e_status'] = 'Expired';
					} else {
						$data['e_status'] = 'Activate';	
					}
				}
			}	
		}
		
		if($_POST){
			$premium_id = $_POST['premium_id'];
			$user_id = $_POST['user_id'];
			$type = $_POST['type'];
			$start = $_POST['start_date'];
			$due = $_POST['due_date'];
			$status = $_POST['status'];
			
			$update_id = 0;
			$insert_id = 0;
			$email = '';
			
			if($type == 'Free'){
				$trial = 1;
				$trial_start = $start;
				$trial_end = $due;
				$main_start = '';
				$main_end = '';
			} else {
				$trial = 0;
				$trial_start = '';
				$trial_end = '';
				$main_start = $start;
				$main_end = $due;
			}
			
			//get user email
			$gemail = $this->users->query_single_user_id($user_id);
			if(!empty($gemail)){
				foreach($gemail as $umail){
					$email = $umail->email;	
					$firstname = $umail->firstname;
					$lastname = $umail->lastname;
				}
			}
			
			//check update or insert
			if($premium_id){
				$update_data = array(
					'type' => $type,
					'trial' => $trial,
					'trial_start' => $trial_start,
					'trial_end' => $trial_end,
					'main_start' => $main_start,
					'main_end' => $main_end,
					'status' => $status,
				);
				
				$update_id = $this->users->update_premium_user($premium_id, $user_id, $update_data);
				if($update_id > 0){
					$data['err_msg'] = '<h5 class="alert alert-success">Subscription Updated!!! Client is notified about this</h5>';	
				} else {
					$data['err_msg'] = '<h5 class="alert alert-info">No Changes Made</h5>';
				}
			} else {
				//check if user already a subscriber
				if($this->users->check_premium($user_id) > 0){
					$data['err_msg'] = '<h5 class="alert alert-info">Client\'s Already Subscriber</h5>';
				} else {
					$insert_data = array(
						'user_id' => $user_id,
						'type' => $type,
						'trial' => $trial,
						'trial_start' => $trial_start,
						'trial_end' => $trial_end,
						'main_start' => $main_start,
						'main_end' => $main_end,
						'status' => $status,
					);
					
					$insert_id = $this->users->reg_premium($insert_data);
					if($insert_id > 0){
						$data['err_msg'] = '<h5 class="alert alert-success">Subscription Created!!! Client is notified about this</h5>';
						
						//make account Client, but first check if its User role
						$chkac = $this->users->query_single_user_id($user_id);
						if(!empty($chkac)){
							foreach($chkac as $ac){
								if($ac->role == 'User'){
									$uprole_data = array(
										'role' => 'Client'
									);
									if($this->users->update_user($user_id, $uprole_data) > 0){}
								}
							}
						}
					} else {
						$data['err_msg'] = '<h5 class="alert alert-danger">There is problem this time. Please try again</h5>';
					}
				}
			}
			
			if($update_id > 0 || $insert_id > 0){
				if($status == 0){
					$msg = 'Please be notified that your Account Subscription is now <b><i>Expired</i></b>. To continue enjoying '.app_name.', kindly visit <a href="'.app_site.'/premium">Upgrade Premium</a>.<br /><br />Thanks for choosing '.app_name.', hope to see you soon again or <a href="'.app_site.'/contact">Feed Us Back</a> on our better we can serve you.';
				} else {
					if($update_id){
						$msg = 'Please be notified that your Account Subscription is now <b><i>Updated as '.$type.'</i></b> and valid from <b>'.$start.'</b> to <b>'.$due.'</b>.<br/><br/>Thanks for choosing '.app_name.', if you have a suggestion on how best to serve you, kindly <a href="'.app_site.'/contact">Feed Us Back</a>.';
					} else {
						$msg = 'Please be notified that your Account Subscription is now <b><i>Created as '.$type.'</i></b> and valid from <b>'.$start.'</b> to <b>'.$due.'</b>.<br/><br/>Thanks for choosing '.app_name.', if you have a suggestion on how best to serve you, kindly <a href="'.app_site.'/contact">Feed Us Back</a>.';
					}
				}
				
				//email notification processing
				$this->email->clear(); //clear initial email variables
				$this->email->to($email);
				$this->email->from(app_email, app_name);
				$this->email->subject('Subscription Notification');
				
				//compose html body of mail
				$mail_subhead = 'Subscription Notification';
				$body_msg = $msg;
				
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
					$this->email->subject('Account Subscription Notification');
					
					//compose html body of mail
					$mail_subhead = 'Account Subscription Notification';
					$body_msg = '<h5>'.$firstname.' '.$lastname.' Account Subscription:</h5><br/><br/>'.$msg;
					
					$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
					$this->email->set_mailtype("html"); //use HTML format
					$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
 
					$this->email->message($mail_design);
					
					if($this->email->send()) {}						
				}	
			}
		}
		
		$allstores = $this->m_stores->query_all_store();
		if(!empty($allstores)){
			$data['allstores'] = $allstores;
		}
		
		$data['title'] = 'Manage Stores | '.app_name;
		$data['page_active'] = 'stores';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/stores', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function remove(){
		//check for edit role
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
		
		$get_store_id = $this->input->get('store');
		$get_user_id = $this->input->get('user');
		if($get_store_id != '' && $get_user_id != ''){
			$data['rec_del'] = $get_store_id;
			$data['rec_del_u'] = $get_user_id;
		} else {
			$data['rec_del'] = '';
			$data['rec_del_u'] = '';
		}
		
		if($_POST){
			if(isset($_POST['cancel'])){
				redirect(base_url('admin/stores'), 'refresh');
			} else {
				$del_id = $_POST['del_id'];
				$del_user_id = $_POST['del_u_id'];
				
				if($del_id && $del_user_id){
					$gq = $this->m_stores->delete_store($del_id, $del_user_id);
					redirect(base_url('admin/stores'), 'refresh');
				}
			}
		}
		
		$allstores = $this->m_stores->query_all_store();
		if(!empty($allstores)){
			$data['allstores'] = $allstores;
		}
		
		$data['title'] = 'Manage Stores | '.app_name;
		$data['page_active'] = 'stores';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/stores', $data);
	  	$this->load->view('designs/footer', $data);
	}
}