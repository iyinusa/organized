<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller {

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
			$log_role = $this->session->userdata('org_user_role');
			$log_user_id = $this->session->userdata('org_id');
			$gsubs = $this->crud->subs($log_user_id, 'contact');
			$subs_status = $gsubs->status;
			$subs_msg = $gsubs->msg;
			if($subs_msg == 'Subscription Expired') {redirect(base_url('premium/?expire=true'), 'refresh');}
			$data['subs_status'] = $subs_status;
			$data['subs_msg'] = $subs_msg;
			
			if(strtolower($log_role) == 'user' || $subs_status == 'false'){
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
		
		//check for update
		$get_id = $this->input->get('r');
		$get_s_id = $this->input->get('s');
		$get_u_id = $this->input->get('u');
		if($get_id != '' && $get_s_id != '' && $get_u_id != ''){
			$get_cust = $this->m_customers->query_customer_id($get_id, $get_s_id, $get_u_id);
			if(!empty($get_cust)){
				foreach($get_cust as $cust){
					$data['e_id'] = $cust->id;	
					$data['e_store_id'] = $cust->store_id;
					$data['e_group_id'] = $cust->group_id;
					$data['e_company'] = $cust->company;
					$data['e_firstname'] = $cust->firstname;
					$data['e_lastname'] = $cust->lastname;
					$data['e_sex'] = $cust->sex;
					$data['e_email'] = $cust->email;
					$data['e_phone'] = $cust->phone;
					$data['e_dob'] = $cust->dob;
					$data['e_address'] = $cust->address;
					$data['e_state'] = $cust->state;
					$data['e_country'] = $cust->country;
					$data['e_active'] = $cust->active;
				}
			}
		}
		
		//POST
		if($_POST){
			$customer_id = $_POST['customer_id'];
			$store_id = $_POST['store_id'];
			$group_id = $_POST['group_id'];
			$company = $_POST['company'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$c_email = $_POST['c_email'];
			$c_phone = $_POST['c_phone'];
			$c_sex = $_POST['c_sex'];
			$c_dob = $_POST['c_dob'];
			$c_address = $_POST['c_address'];
			$c_state = $_POST['c_state'];
			$c_country = $_POST['c_country'];
			if(isset($_POST['active'])){$active=1;}else{$active=0;}
			if(isset($_POST['new'])){$as_new=1;}else{$as_new=0;}
			
			$time = time();
			$now = date("Y-m-d H:i:s");
			$insert_id = 0;
			
			//check if email exist
			$chk_email = $this->users->query_user_by_email($c_email);
			if(!empty($chk_email)){
				foreach($chk_email as $uemail){
					$insert_id = $uemail->id;	
				}
			} else {
				//register user
				$reg_user = array(
					'reg_company' => $company,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $c_email,
					'phone' => $c_phone,
					'sex' => $c_sex,
					'dob' => $c_dob,
					'address' => $c_address,
					'state' => $c_state,
					'country' => $c_country,
					'password' => 'nil',
					'role' => 'User',
					'reg_date' => $now,
					'regstamp' => $time,
					'industry_id' => 0,
					'activate' => 0,
					'status' => 0
				);
				
				$insert_id = $this->users->reg_insert($reg_user);	
			}
			
			//register contact
			if($customer_id != '' && $as_new == 0){
				$upd_cust = array(
					'store_id' => $store_id,
					'user_id' => $insert_id,
					'group_id' => $group_id,
					'company' => $company,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $c_email,
					'phone' => $c_phone,
					'sex' => $c_sex,
					'dob' => $c_dob,
					'address' => $c_address,
					'state' => $c_state,
					'country' => $c_country,
					'active' => $active
				);
				
				if($this->m_customers->update_customer($customer_id, $insert_id, $upd_cust) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Contact Database Updated</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';	
				}
			} else {
				//check if contact already in store
				$chk_cust = $this->m_customers->query_customer_store_user($store_id, $insert_id);
				if(!empty($chk_cust)){
					$data['err_msg'] = '<div class="alert alert-info"><h5>Contact Already In Store</h5></div>';
				} else {
					$reg_cust = array(
						'store_id' => $store_id,
						'user_id' => $insert_id,
						'group_id' => $group_id,
						'company' => $company,
						'firstname' => $firstname,
						'lastname' => $lastname,
						'email' => $c_email,
						'phone' => $c_phone,
						'sex' => $c_sex,
						'dob' => $c_dob,
						'address' => $c_address,
						'state' => $c_state,
						'country' => $c_country,
						'reg_date' => $now,
						'active' => $active
					);
					
					if($this->m_customers->reg_customer($reg_cust) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Contact Database Updated</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';	
					}
				}
			}
			
			//check if active to decide to register as customer or not
			if($active == 1){
				//check if customer already exist
				$cexit = $this->m_customers->query_single_store_customer($store_id, $insert_id);
				if(empty($cexit)){
					$insert_cus_data = array(
						'store_id' => $store_id,
						'user_id' => $insert_id,
						'reg_date' => $now
					);
					
					$this->m_customers->reg_store_customer($insert_cus_data);
				}
			} else {
				//check if customer already exist
				$cexit = $this->m_customers->query_single_store_customer($store_id, $insert_id);
				if(!empty($cexit)){
					//get customer store id
					foreach($cexit as $exit){
						$del_id = $exit->id;
					}
					$this->m_customers->delete_store_customer($del_id, $store_id, $insert_id);
				}
			}
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$all_group = $this->m_customers->query_customer_group();
		if(!empty($all_group)) {
			$data['allgroup'] = $all_group;
		}
		
		$data['title'] = 'Contacts | '.app_name;
		$data['page_active'] = 'customer';
		
		$this->load->view('designs/header', $data);
		$this->load->view('customers/contacts', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function filter(){
		if($_POST){
			$alpha = $_POST['alpha'];
			$dir_list = '';
			
			$log_user_id = $this->session->userdata('org_id');
			
			$all_stores = $this->m_stores->query_user_stores($log_user_id);
			if(!empty($all_stores)) {
				foreach($all_stores as $stores){
					$allstore = $this->m_stores->query_store_id($stores->store_id);
					if(!empty($allstore)){
						foreach($allstore as $store){
							if($alpha == 'All'){
								$get_contact = $this->m_customers->query_customer($store->id);
							} else {
								$get_contact = $this->m_customers->query_customer_alpha($store->id, $alpha);
							}
							if(!empty($get_contact)){
								foreach($get_contact as $contact){
									//get group name
									$get_group = $this->m_customers->query_group_id($contact->group_id);
									if(!empty($get_group)){
										foreach($get_group as $g){
											$contact_group = $g->name;	
										}
									} else {
										$contact_group = '';
									}
									
									$dir_list .= '
										<div class="col-sm-6 col-lg-6">
											<div class="widget">
												<div class="widget-simple">
													<a href="page_ready_user_profile.html">
														<img src="'.base_url('img/icon72.png').'" alt="avatar" class="widget-image img-circle pull-left animation-fadeIn">
													</a>
													<h4 class="widget-content text-right">
														<a href="'.base_url('customers/view?s='.$store->id.'&u='.$alpha.'').'"><strong>'.$contact->firstname.' '.$contact->lastname.'</strong></a><br>
														<span class="btn-group">
															<a href="javascript:void(0)" class="btn btn-xs btn-default" data-toggle="tooltip" title="Group Category">'.$contact_group.'</a>
															<a href="'.base_url().'contacts?r='.$contact->id.'&s='.$store->id.'&u='.$contact->user_id.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
														</span>
													</h4>
												</div>
											</div>
										</div>
									';	
								}
							}
						}
					}
				}
			}
			
			if($dir_list == ''){
				$dir_list = '<h3 class="text-center text-muted">No Contacts</h3>';
			}
			
			echo $dir_list;
		} exit;
	}
	
	public function birthday_wish(){
		if($_POST){
			$wish_email = $_POST['wish_email'];
			$wish_subject = $_POST['wish_subject'];
			$wish_msg = $_POST['wish_msg'];
			
			if($wish_email == '' || $wish_subject == '' || $wish_msg == ''){
				echo '<h5 class="alert alert-warning">All fields are required</h5>';
			} else {
				//email notification processing
				$this->email->clear(); //clear initial email variables
				$this->email->to($wish_email);
				$this->email->from(app_email, app_name);
				$this->email->subject('Happy Birthday');
				
				//compose html body of mail
				$mail_subhead = 'Happy Birthday';
				$body_msg = '
					You have a Birthday Message:<br /><br />
					<b><u>'.$wish_subject.'</u></b><br />
					'.$wish_msg.'<br />
				';
				
				$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
				$this->email->set_mailtype("html"); //use HTML format
				$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);

				$this->email->message($mail_design);
				
				if($this->email->send()) {
					echo '<h5 class="alert alert-success">Birthday Wish Sent Successfully</h5>';
				} else {
					echo '<h5 class="alert alert-warning">There is problem this time. Please try later</h5>';
				}
			}
		} exit;
	}
	
	public function feedback(){
		if($_POST){
			$log_user_firstname = ucwords($this->session->userdata('org_user_firstname'));
			$log_user_lastname = ucwords($this->session->userdata('org_user_lastname'));
			$log_user_email = $this->session->userdata('org_user_email');
			
			$feed_title = $_POST['feed_title'];
			$feed_msg = $_POST['feed_msg'];
			
			if($feed_title == '' || $feed_msg == ''){
				echo '<h5 class="alert alert-warning">Please Title and suggest your Observation</h5>';
			} else {
				//email notification processing
				$this->email->clear(); //clear initial email variables
				$this->email->to(app_email);
				$this->email->from($log_user_email, app_name);
				$this->email->subject('[FeedBack] '.$feed_title);
				
				//compose html body of mail
				$mail_subhead = '[FeedBack] '.$feed_title;
				$body_msg = '
					Feedback from '.$log_user_firstname.' '.$log_user_lastname.': <b>'.$feed_title.'</b><br /><br />
					'.$feed_msg.'<br />
				';
				
				$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
				$this->email->set_mailtype("html"); //use HTML format
				$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);

				$this->email->message($mail_design);
				
				if($this->email->send()) {
					echo '<h5 class="alert alert-success">Thanks for the FeedBack! We will act appropriately.</h5>';
				} else {
					echo '<h5 class="alert alert-warning">There is problem this time. Please try later</h5>';
				}
			}
		} exit;
	}
}