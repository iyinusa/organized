<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
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
			$log_role = $this->session->userdata('org_user_role');
			if(strtolower($log_role) == 'user'){
				$data['module_access'] = FALSE;
			} else {
				$data['module_access'] = TRUE;	
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		//check for delete
		$get_id = $this->input->get('r');
		$get_s_id = $this->input->get('s');
		if($get_id != '' && $get_s_id != ''){
			$data['rec_del'] = $get_id;
			$data['rec_del_s'] = $get_s_id;
		} else {
			$data['rec_del'] = '';
			$data['rec_del_s'] = '';	
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Staff | '.app_name;
		$data['page_active'] = 'staff';
		
		$this->load->view('designs/header', $data);
		$this->load->view('staff/staff', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function add(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_role = $this->session->userdata('org_user_role');
			if(strtolower($log_role) == 'user'){
				$data['module_access'] = FALSE;
			} else {
				$data['module_access'] = TRUE;	
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		//get all stores
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		//check for update
		$get_id = $this->input->get('e');
		$get_sid = $this->input->get('s');
		$get_uid = $this->input->get('u');
		if($get_id != '' && $get_sid != '' && $get_uid != ''){
			$gq = $this->m_stores->query_single_store_staff_id($get_id, $get_sid, $get_uid);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_user_id'] = $item->user_id;
				$data['e_store_role'] = $item->role;
				$data['e_emp_date'] = $item->emp_date;
				$data['e_emp_id'] = $item->emp_id;
				$data['e_status'] = $item->status;
				$data['e_firstname'] = $item->firstname;
				$data['e_lastname'] = $item->lastname;
				$data['e_email'] = $item->email;
				$data['e_phone'] = $item->phone;
				$data['e_address'] = $item->address;
				$data['e_dob'] = $item->dob;
			}
		}
		
		//check for posting
		if($_POST){
			$store_staff_id = $_POST['store_staff_id'];	
			$store_id = $_POST['store_id'];
			if(isset($_POST['user_id'])){$user_id = $_POST['user_id'];}	
			$store_role = $_POST['store_role'];
			$emp_date = $_POST['emp_date'];	
			$emp_id = $_POST['emp_id'];
			if(isset($_POST['reg_email'])){$reg_email = $_POST['reg_email'];}else{$reg_email='';}
			if(isset($_POST['status'])){$staff_status=1;}else{$staff_status=0;}
			if(isset($_POST['firstname'])){$firstname = $_POST['firstname'];}else{$firstname='';}
			if(isset($_POST['lastname'])){$lastname = $_POST['lastname'];}else{$lastname='';}
			if(isset($_POST['email'])){$email = $_POST['email'];}else{$email='';}
			if(isset($_POST['phone'])){$phone = $_POST['phone'];}else{$phone='';}
			if(isset($_POST['dob'])){$dob = $_POST['dob'];}else{$dob='';}
			if(isset($_POST['address'])){$address = $_POST['address'];}else{$address='';}
			if(isset($_POST['password'])){$password = $_POST['password'];}else{$password='';}
			$role = 'Client Staff';
			$insert_id = '';
			$log_user_industry_id = $this->session->userdata('org_user_industry_id');
			
			//check update post
			if($store_staff_id != ''){
				$upd_data = array(
					'store_id' => $store_id,
					'role' => $store_role,
					'emp_date' => $emp_date,
					'emp_id' => $emp_id,
					'status' => $staff_status
				);
				
				if($this->m_stores->update_store_staff($store_staff_id, $user_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successful</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				//check if its new account or registered
				if($reg_email){
					$chk_acc = $this->users->query_user_by_email($reg_email);
					if(!empty($chk_acc)){
						foreach($chk_acc as $acc){
							$insert_id = $acc->id;
							$firstname = $acc->firstname;
							$lastname = $acc->lastname;
							$email = $acc->email;
							$phone = $acc->phone;
							$dob = $acc->dob;
							$address = $acc->address;
							$password = 'null';
						}
					}
				}
				
				//register new staff and assign to store
				if(!$firstname || !$lastname || !$email || !$password){
					$data['err_msg'] = '<div class="alert alert-info"><h5>You must supply all fields</h5></div>';
				} else {
					if(($this->users->check_by_email($email) > 0) && !$reg_email) {
						$data['err_msg'] = '<div class="alert alert-info">Staff already exist with this email address</div>';
					} else {
						$time = time();
						$now = date("Y-m-d H:i:s");
						
						/////check to assign based on registed or new account
						if($reg_email){
							///////now assign registered staff to store
							$insert_staff_data = array(
								'store_id' => $store_id,
								'owner_id' => $log_user_id,
								'user_id' => $insert_id,
								'role' => $store_role,
								'emp_date' => $emp_date,
								'emp_id' => $emp_id,
								'status' => $staff_status,
								'reg_date' => $now
							);
							
							if($this->m_stores->reg_store_staff($insert_staff_data) > 0){
								$data['err_msg'] = '<div class="alert alert-success"><h5>Successful. Kindly inform staff to Activate Email Address and Update Profile.</h5></div>';
								redirect(base_url('staff/'), 'refresh');
							} else {
								$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
							}
						} else if ($store_role != 'Staff') { //if role is staff, don't create login access
							///////now register and assign staff to store
							$password = md5($password); //hash password here
						
							$reg_data = array(
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'phone' => $phone,
								'dob' => $dob,
								'address' => $address,
								'password' => $password,
								'role' => $role,
								'reg_date' => $now,
								'regstamp' => $time,
								'activate' => 0,
								'status' => 0,
								'industry_id' => $log_user_industry_id
							);
							
							//email notification processing
							$this->email->clear(); //clear initial email variables
							$this->email->to($email);
							$this->email->from(app_email,app_name);
							$this->email->subject('Activate Account');
							
							//compose html body of mail
							$mail_stamp = $time;
							$mail_subhead = 'Email Activation';
							$body_msg = '
								Thanks for registering on '.app_name.'.<br /><br />
								Kindly <a href="'.base_url().'activate?stamp='.$mail_stamp.'&amp;email='.$email.'">Activate your account</a><br /><br />Thanks
							';
							
							$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
							$this->email->set_mailtype("html"); //use HTML format
							$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
		 
							$this->email->message($mail_design);
							
							if($this->email->send()) {
								$insert_id = $this->users->reg_insert($reg_data); //save records in database
								
								/////////////////////////////////////////////////////////////////////////////////////
								//send notification mail to admin
								$this->email->clear(); //clear initial email variables
								$this->email->to(app_email);
								$this->email->from($email,app_name);
								$this->email->subject('[Store] New '.app_name.' Account');
								
								//compose html body of mail
								$mail_stamp = $time;
								$mail_subhead = 'New Account Creation';
								$body_msg = '
									This is to notify you that '.app_name.' now has new member '.$firstname.' '.$lastname.' created by a Store owner.<br /><br />Thanks
								';
								
								$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
								$this->email->set_mailtype("html"); //use HTML format
								$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
			 
								$this->email->message($mail_design);
								
								if($this->email->send()) {}
								
								///////now assign staff to store
								$insert_staff_data = array(
									'store_id' => $store_id,
									'owner_id' => $log_user_id,
									'user_id' => $insert_id,
									'role' => $store_role,
									'emp_date' => $emp_date,
									'emp_id' => $emp_id,
									'status' => $staff_status,
									'reg_date' => $now
								);
								
								if($this->m_stores->reg_store_staff($insert_staff_data) > 0){
									$data['err_msg'] = '<div class="alert alert-success"><h5>Successful. Kindly inform staff to Activate Email Address and Update Profile.</h5></div>';
									redirect(base_url('staff/'), 'refresh');
								} else {
									$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
								}				
							} else {
								$data['err_msg'] = '<div class="alert alert-danger">Problem sending email this time. You will need to try again with valid Email Address.</div>';
							}	
						} else {
							//Only if role is staff (will not be able to login into portal)
							$time = time();
							$now = date("Y-m-d H:i:s");
							$insert_staff_data = array(
								'store_id' => $store_id,
								'owner_id' => $log_user_id,
								'user_id' => 0,
								'role' => $store_role,
								'emp_date' => $emp_date,
								'emp_id' => $emp_id,
								'status' => $staff_status,
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'phone' => $phone,
								'dob' => $dob,
								'address' => $address,
								'reg_date' => $now
							);
							
							if($this->m_stores->reg_store_staff($insert_staff_data) > 0){
								$data['err_msg'] = '<div class="alert alert-success"><h5>Successful. Kindly inform staff to Activate Email Address and Update Profile.</h5></div>';
								redirect(base_url('staff/'), 'refresh');
							} else {
								$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
							}
						}
					}	
				}
			}
		}
		
		
		$data['title'] = 'Manage Staff | '.app_name;
		$data['page_active'] = 'staff';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('staff/add', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function delete(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_role = $this->session->userdata('org_user_role');
			if(strtolower($log_role) == 'user'){
				$data['module_access'] = FALSE;
			} else {
				$data['module_access'] = TRUE;	
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		if(isset($_POST['cancel'])){
			redirect(base_url('staff/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_store_id = $_POST['del_store_id'];
			if($del_id && $del_store_id){
				$gq = $this->m_stores->delete_store_staff($del_id, $log_user_id, $del_store_id);
				redirect(base_url('staff/'), 'refresh');
			}
		}
	}
}