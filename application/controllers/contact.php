<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {

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
			$name = $_POST['name'];
			$email = $_POST['email'];	
			$msg = $_POST['msg'];
			
			if($name == '' || $email == '' || $msg == ''){
				echo '<div class="alert alert-warning">All fields are required</div>';	
			} else {
				//email notification processing
				$this->email->clear(); //clear initial email variables
				$this->email->to($email);
				$this->email->from(app_email, app_name);
				$this->email->subject(app_name.' | Thanks for contact us');
				
				//compose html body of mail
				$mail_subhead = app_name.' | Thanks for contact us';
				$body_msg = '
					Thanks for contacting '.app_name.'. Our assigned staff get back to you within 24hours.
				';
				
				$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
				$this->email->set_mailtype("html"); //use HTML format
				$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);

				$this->email->message($mail_design);
				
				if($this->email->send()) {
					//copy admin as well
					$this->email->clear(); //clear initial email variables
					$this->email->to(app_email);
					$this->email->from($email, app_name);
					$this->email->subject('Contact from '.app_name.' Visitor');
					
					//compose html body of mail
					$mail_subhead = 'Contact from '.app_name.' Visitor';
					$body_msg = '
						<h2>New Enquiry:</h2>
						'.$name.' with email address '.$email.' said:<br /><br />
						'.$msg.'
					';
					
					$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
					$this->email->set_mailtype("html"); //use HTML format
					$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
 
					$this->email->message($mail_design);
					
					if($this->email->send()) {
						echo '<div class="alert alert-success">Successful! You will here from us within 24hours</div>';
					} //mail sent to admin		
				} else {
					echo '<div class="alert alert-danger">There is problem this time. Please try later</div>';
				}
			}exit;
		} 
		
		$data['title'] = 'Contact | '.app_name;
		$data['page_active'] = 'contact';

		$this->load->view('designs/hm_header', $data);
		$this->load->view('contact', $data);
		$this->load->view('designs/hm_footer', $data);
	}
}