<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('users'); //load model
		$this->load->library('form_validation'); //load form validate rules
		$this->load->helper('cookie');
		
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
		$data['host_name'] = php_uname('n');
		$redir = $this->session->userdata('org_redirect');
		//check if already logged in
		if($this->session->userdata('logged_in')==TRUE){
			if($redir==''){$redir = 'dashboard/';}
			redirect(base_url($redir), 'refresh');
		}
		
		//get industry
		$get_ind = $this->users->query_industry();
		if(!empty($get_ind)){
			$data['allind'] = $get_ind;	
		}
		
		$this->form_validation->set_rules('login-email','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('login-password','Password','trim|required|min_length[4]|max_length[32]|xss_clean|md5');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>'); //error delimeter
		
		if ($this->form_validation->run() == FALSE) {
			$data['err_msg'] = '';
		} else {
			//check if ready for post
			if($_POST) {
				$email = $_POST['login-email'];
				$password = $_POST['login-password'];
				if(isset($_POST['login-remember-me'])){$remind='';}else{$remind='';}
				
				if($this->users->check_user($email, $password) <= 0) {
					$data['err_msg'] = '<div class="alert alert-danger">Invalid email address or password.</div>';		
				} else {
					$query = $this->users->query_user($email, $password);
					if(!empty($query)) {
						foreach($query as $row) {
							//update status
							$first_log = $row->user_lastlog; //to check first time user
							if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
								$ip = $_SERVER['HTTP_CLIENT_IP'];
							} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
								$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
							} else {
								$ip = $_SERVER['REMOTE_ADDR'];
							}
							
							$host_name = php_uname('n');
							
							$now = date("Y-m-d H:i:s");
							$status_update = array('user_status'=>1, 'user_lastlog'=>$now, 'ip_lastlog'=>$ip, 'hostname_lastlog'=>$host_name);
							$this->users->update_user($row->id,$status_update);
							
							//add data to session
							$s_data = array (
								'org_id' => $row->id,
								'org_user_title' => $row->title,
								'org_user_email' => $row->email,
								'org_user_lastlog' => $row->user_lastlog,
								'org_user_status' => $row->user_status,
								'org_user_firstname' => $row->firstname,
								'org_user_lastname' => $row->lastname,
								'org_user_dob' => $row->dob,
								'org_user_sex' => $row->sex,
								'org_user_phone' => $row->phone,
								'org_user_address' => $row->address,
								'org_user_city' => $row->city,
								'org_user_state' => $row->state,
								'org_user_country' => $row->country,
								'org_user_pics' => $row->pics,
								'org_user_pics_small' => $row->pics_small,
								'org_user_role' => $row->role,
								'org_user_reg_date' => $row->reg_date,
								'org_user_lastlog' => $row->user_lastlog,
								'org_user_status' => $row->user_status,
								'org_user_theme' => $row->theme,
								'org_user_industry_id' => $row->industry_id,
								'org_user_theme' => $row->theme,
								'logged_in' => TRUE
							);
						}
						
						$check = $this->session->set_userdata($s_data);
						
						//redirect
						redirect(base_url('dashboard/'), 'refresh');
					}
				}
			}
		}

		$data['title'] = 'Login | '.app_name;
		$data['page_active'] = 'login';

	  	$this->load->view('login', $data);
		
	}
	
	public function register(){
		//check if already logged in
		If($this->session->userdata('logged_in')==TRUE){redirect(base_url('dashboard/'), 'refresh');}
		
		//set form input rules 
		$this->form_validation->set_rules('register-firstname','First name','required|xss_clean');
		$this->form_validation->set_rules('register-lastname','Last name','trim|required|xss_clean');
		$this->form_validation->set_rules('register-phone','Phone number','trim|required|xss_clean');
		$this->form_validation->set_rules('ind_id','Industry','trim|required|xss_clean');
		$this->form_validation->set_rules('register-email','Email Address','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('register-password','Password','trim|required|xss_clean|md5');
		$this->form_validation->set_rules('register-password-verify','Confirm Password','trim|required|matches[register-password]|xss_clean');
		$this->form_validation->set_rules('country','Country','trim|required|xss_clean');
		
		$this->form_validation->set_error_delimiters('<div id="pass-info" class="alert alert-danger">', '</div>'); //error delimeter
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['err_msg'] = '';
		}
		else
		{
			//check if ready for post
			if($_POST) {
				$firstname = $_POST['register-firstname'];
				$lastname = $_POST['register-lastname'];
				$phone = $_POST['register-phone'];
				$company = $_POST['register-company'];
				$email = $_POST['register-email'];
				$password = $_POST['register-password'];
				$ind_id = $_POST['ind_id'];
				$country = $_POST['country'];
				$role = 'User';
				
				if($this->users->check_by_email($email) > 0) {
					$data['err_msg'] = '<div class="alert alert-danger">Member already exist with this username or email address</div>';
				} else {
					$time = time();
					$now = date("Y-m-d H:i:s");
					
					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					
					$host_name = php_uname('n');
					
					$reg_data = array(
						'firstname' => $firstname,
						'lastname' => $lastname,
						'email' => $email,
						'phone' => $phone,
						'reg_company' => $company,
						'password' => $password,
						'role' => $role,
						'reg_date' => $now,
						'regstamp' => $time,
						'reg_ip' => $ip,
						'reg_hostname' => $host_name,
						'industry_id' => $ind_id,
						'country' => $country,
						'activate' => 0,
						'status' => 0
					);
					
					//email notification processing
					$this->email->clear(); //clear initial email variables
					$this->email->to($email);
					$this->email->from(app_email, app_name);
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
						$data['err_msg'] = '<div class="alert alert-success">Please activate your Email Address to complete registration. Click on link sent to '.$email.' (NB: Check SPAM if not in INBOX)</div>';
												
						$insert_id = $this->users->reg_insert($reg_data); //save records in database
						
						/////////////////////////////////////////////////////////////////////////////////////
						//send notification mail to admin
						$this->email->clear(); //clear initial email variables
						$this->email->to(app_email);
						$this->email->from($email,app_name);
						$this->email->subject('New '.app_name.' Account');
						
						//compose html body of mail
						$mail_stamp = $time;
						$mail_subhead = 'New Account Creation';
						$body_msg = '
							This is to notify you that '.app_name.' now has new member '.$firstname.' '.$lastname.' ('.$phone.') of '.$company.'.<br /><br />Thanks
						';
						
						$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
						$this->email->set_mailtype("html"); //use HTML format
						$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
	 
						$this->email->message($mail_design);
						
						if($this->email->send()) {}						
					} else {
						$data['err_msg'] = '<div class="alert alert-danger">Problem sending email this time. You will need to try again with valid Email Address.</div>';
					}
				}
			}
		}
		
		$data['title'] = 'Register | '.app_name;
		$data['page_active'] = 'login';

	  	$this->load->view('login', $data);
	}
	
	public function logout(){
		$data['err_msg'] = '';
		$org_id = $this->session->userdata('org_id');
		
		$status_update = array('user_status'=>0);
		if($this->users->update_user($org_id,$status_update) > 0){
			$newdata = array(
				'org_id' => '',
				'org_user_email' => '',
				'org_user_lastlog' => '',
				'org_user_status' => '',
				'org_user_firstname' => '',
				'org_user_lastname' => '',
				'org_user_pics' => '',
				'org_user_pics_small' => '',
				'org_user_role' => '',
				'org_user_reg_date' => '',
				'org_user_lastlog' => '',
				'org_user_status' => '',
				'org_user_theme' => '',
				'org_user_industry_id' => '',
				'org_user_country' => '',
				'org_redirect' => '',
				'logged_in' => TRUE
			);
			$this->session->unset_userdata($newdata);
			//unset($this->session->userdata); 
			$this->session->sess_destroy();
			delete_cookie( config_item('sess_cookie_name') );
			
			$data['err_msg'] = '<div class="alert alert-success">Successfully LogOut.</div>';
		}
		
		$data['title'] = 'Logout | '.app_name;
		$data['page_active'] = 'login';
		redirect(base_url('auth/'), 'refresh');
		$this->load->view('login', $data);
	}
	
	public function forgot(){
		$stamp = $this->input->get('stamp');
		$email = $this->input->get('email');
		
		$data['stamp'] = $stamp;
		$data['email'] = $email;
		
		if($stamp=='' || $email==''){
			//set form input rules 
			$this->form_validation->set_rules('reminder-email','Email Address','trim|required|valid_email|xss_clean');
			
			$this->form_validation->set_error_delimiters('<div id="pass-info" class="alert alert-danger">', '</div>'); //error delimeter
			
			if ($this->form_validation->run() == FALSE)
			{
				$data['err_msg'] = '';
			}
			else
			{
				//check if ready for post
				if($_POST) {
					$email = $_POST['reminder-email'];
					
					if($this->users->check_by_email($email) < 0) {
						$data['err_msg'] = '<h5 class="alert alert-danger">Email address not exist</h5>';
					} else {
						$time = time();
						
						$reg_data = array(
							'reset' => 1,
							'reset_stamp' => $time
						);
						
						//$this->users->activate($email, $reg_data); 
						
						//email notification processing
						$this->email->clear(); //clear initial email variables
						$this->email->to($email);
						$this->email->from(app_email,app_name);
						$this->email->subject('Password Reset');
						
						//compose html body of mail
						$mail_stamp = $time;
						$mail_subhead = 'Password Reset';
						$body_msg = '
							You requested for password reset on '.app_name.'.<br /><br />
							<a class="email_btn" href="'.base_url().'auth/forgot?stamp='.$mail_stamp.'&amp;email='.$email.'">Reset Password</a><br /><br />Thanks
						';
						
						$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
						$this->email->set_mailtype("html"); //use HTML format
						$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
	 
						$this->email->message($mail_design);
						
						if($this->email->send()) {
							$data['err_msg'] = '<h5 class="alert alert-success">Please check your Email Address for LINK to reset your password. (NB: Check SPAM if not in INBOX)</h5>';
													
							$this->users->activate($email, $reg_data); //update records
							
						} else {
							$data['err_msg'] = '<h5 class="alert alert-danger">Problem sending email this time. You will need to try again.</h5>';
						}
					}
				}
			}
			
			$data['title'] = 'Reset Password | '.app_name;
			$data['page_active'] = 'login';
			
			$this->load->view('login', $data);
		} else {
			//check reset link
			$ch = $this->users->check_reset($stamp, $email);
			if(empty($ch)){
				$data['err_msg'] = '<h5 class="alert alert-danger">Reset link already expired!</h5>';
			} else {
				//check if post else prepare reset
				//set form input rules 
				$this->form_validation->set_rules('new','New password','trim|required|xss_clean|md5');
				$this->form_validation->set_rules('confirm','Confirm password','trim|required|matches[new]|xss_clean');
				
				//error delimeter
				$this->form_validation->set_error_delimiters('<div id="pass-info" class="alert alert-danger">', '</div>');
				
				if ($this->form_validation->run() == FALSE){
					$data['err_msg'] = '';
				} else {
					//check if ready for post
					if(isset($_POST)) {
						$new = $_POST['new'];
						$confirm = $_POST['confirm'];
						
						$update_data = array(
							'password' => $new,
							'reset' => 0,
							'reset_stamp' => ''
						);
						
						if($this->users->activate($email, $update_data) > 0){
							$data['err_msg'] = '<h5 class="alert alert-success">Password reset. <a href="'.base_url().'auth/">Sign In</a></h5>';
						} else {
							$data['err_msg'] = '<h5 class="alert alert-danger">There is problem this time. Try later</h5>';
						}
					}
				}
			}
			
			$data['title'] = 'Reset Password | '.app_name;
			$data['page_active'] = 'login';
			
			$this->load->view('forgot', $data);
		}
		
	}
}