<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		if($_POST){
			$sub_email = $_POST['email'];
			
			//check if already subscribed
			if($sub_email == ''){
				echo '<div class="alert alert-warning">Email address is required</div>';
			} else {
				$check = $this->subscribe->check_subscriber($sub_email);
				if(!empty($check)){
					echo '<div class="alert alert-success">Thanks!!! We already have you listed</div>';
				} else {
					//prepare to insert
					$now = date('l, j F, Y H:m');
					$reg_data = array(
						'email' => $sub_email,
						'name' => '',
						'reg_date' => $now
					);
					
					//save records in database
					$insert_id = $this->subscribe->reg_insert($reg_data);
					
					if($insert_id) {
						//get total subscriptions
						$total = count($this->subscribe->all_subscriber());
						
						//email notification processing
						$this->email->clear(); //clear initial email variables
						$this->email->to($sub_email);
						$this->email->from(app_email, app_name);
						$this->email->subject('Request SignUp to '.app_name);
						
						//compose html body of mail
						$mail_subhead = 'Request SignUp to '.app_name;
						$body_msg = '
							Thanks for requesting signup with '.app_name.'. Our assigned staff work you through the full sign up process.
						';
						
						$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
						$this->email->set_mailtype("html"); //use HTML format
						$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
	 
						$this->email->message($mail_design);
						
						if($this->email->send()) {
							echo '<div class="alert alert-success">Successful! You will here from us within 24hours</div>';
							
							//copy admin as well
							$this->email->clear(); //clear initial email variables
							$this->email->to(app_email);
							$this->email->from($sub_email, app_name);
							$this->email->subject('New SignUp Request at '.app_name);
							
							//compose html body of mail
							$mail_subhead = 'New SignUp Request';
							$body_msg = '
								<h2>'.$total.' SignUp Request and Growing...</h2>
								Congrats!!! - We now have new SignUp Request to '.app_name.' platform with email address ('.$sub_email.').
							';
							
							$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
							$this->email->set_mailtype("html"); //use HTML format
							$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
		 
							$this->email->message($mail_design);
							
							if($this->email->send()) {} //mail sent to admin		
						} else {
							echo '<div class="alert alert-danger">There is problem this time. Please try later</div>';
						}
						
					} else {
						echo '<div class="alert alert-warning">There is problem this time. Please try later</div>';
					}
				}
			}
			
			exit;
		}
		
		$data['title'] = app_name.' | Your Business In Your Palm';
		$data['page_active'] = 'welcome';

		$this->load->view('designs/hm_header', $data);
		$this->load->view('welcome', $data);
		$this->load->view('designs/hm_footer', $data);
	}
}