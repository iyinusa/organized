<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
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
		$get_u_id = $this->input->get('u');
		if($get_id != '' && $get_s_id != '' && $get_u_id != ''){
			$data['rec_del'] = $get_id;
			$data['rec_del_s'] = $get_s_id;
			$data['rec_del_u'] = $get_u_id;
		} else {
			$data['rec_del'] = '';
			$data['rec_del_s'] = '';
			$data['rec_del_u'] = '';	
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Customers | '.app_name;
		$data['page_active'] = 'customer';
		
		$this->load->view('designs/header', $data);
		$this->load->view('customers/customers', $data);
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
			$gq = $this->m_customers->query_single_customer_id($get_id, $get_sid, $get_uid);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_user_id'] = $item->user_id;
			}
		}
		
		//check for posting
		if($_POST){
			$store_customer_id = $_POST['store_customer_id'];	
			$store_id = $_POST['store_id'];
			if(isset($_POST['user_id'])){$user_id = $_POST['user_id'];}
			if(isset($_POST['reg_email'])){$reg_email = $_POST['reg_email'];}else{$reg_email='';}
			if(isset($_POST['firstname'])){$firstname = $_POST['firstname'];}else{$firstname='';}
			if(isset($_POST['lastname'])){$lastname = $_POST['lastname'];}else{$lastname='';}
			if(isset($_POST['email'])){$email = $_POST['email'];}else{$email='';}
			if(isset($_POST['password'])){$password = $_POST['password'];}else{$password='';}
			$role = 'User';
			$insert_id = '';
			
			//check update post
			if($store_customer_id != ''){
				$upd_data = array(
					'store_id' => $store_id
				);
				
				if($this->m_customers->update_store_customer($store_customer_id, $user_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
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
							$password = 'null';
						}
					}
				}
				
				//register new customer and assign to store
				if(!$firstname || !$lastname || !$email || !$password){
					$data['err_msg'] = '<div class="alert alert-info"><h5>You must supply all fields</h5></div>';
				} else {
					if(($this->users->check_by_email($email) > 0) && !$reg_email) {
						$data['err_msg'] = '<div class="alert alert-info">Customer already exist with this email address</div>';
					} else {
						$time = time();
						$now = date("Y-m-d H:i:s");
						
						/////check to assign based on registed or new account
						if($reg_email){
							///////now assign registered customer to store
							$insert_staff_data = array(
								'store_id' => $store_id,
								'user_id' => $insert_id,
								'reg_date' => $now
							);
							
							if($this->m_customers->reg_store_customer($insert_staff_data) > 0){
								$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully. Kindly inform customer/client to Activate Email Address and Update Profile.</h5></div>';
								redirect(base_url('customers/'), 'refresh');
							} else {
								$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
							}
						} else {
							///////now register and assign customer to store
							$password = md5($password); //hash password here
						
							$reg_data = array(
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'password' => $password,
								'role' => $role,
								'reg_date' => $now,
								'regstamp' => $time,
								'activate' => 0,
								'status' => 0
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
								You have been register by a Company on Orgnized.<br /><br />
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
								
								///////now assign customer to store
								$insert_customer_data = array(
									'store_id' => $store_id,
									'user_id' => $insert_id,
									'reg_date' => $now
								);
								
								if($this->m_customers->reg_store_customer($insert_customer_data) > 0){
									$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully. Kindly inform customer/client to Activate Email Address and Update Profile.</h5></div>';
									redirect(base_url('customers/'), 'refresh');
								} else {
									$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
								}				
							} else {
								$data['err_msg'] = '<div class="alert alert-danger">Problem sending email this time. You will need to try again with valid Email Address.</div>';
							}	
						}
					}	
				}
			}
		}
		
		
		$data['title'] = 'Manage Customer | '.app_name;
		$data['page_active'] = 'customer';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('customers/add', $data);
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
			redirect(base_url('customers/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_store_id = $_POST['del_store_id'];
			$del_user_id = $_POST['del_user_id'];
			if($del_id && $del_store_id && $del_user_id){
				$gq = $this->m_customers->delete_store_customer($del_id, $del_store_id, $del_user_id);
				redirect(base_url('customers/'), 'refresh');
			}
		}
	}
	
	public function view(){
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
		
		//check for variables
		//$get_id = $this->input->get('v'); //store customer ID
		$get_s_id = $this->input->get('s'); //store ID
		$get_u_id = $this->input->get('u'); //customer ID
		
		//get customer info
		if(!$get_s_id || !$get_u_id){
			redirect(base_url('customers/'), 'refresh'); //redirect back to customer list
		} else {
			$csu = $this->m_customers->query_customer_store_user($get_s_id, $get_u_id);
			if(!empty($csu)){
				foreach($csu as $us){
					$data['firstname'] = $us->firstname;
					$data['lastname'] = $us->lastname;
					$data['sex'] = $us->sex;
					$data['phone'] = $us->phone;
					$data['email'] = $us->email;
					$data['dob'] = $us->dob;
					$data['address'] = $us->address;
					$data['state'] = $us->state;
					$data['country'] = $us->country;
					$data['reg_date'] = $us->reg_date;
					
					//get user profile pics
					$upics = $this->users->query_single_user_id($get_u_id);
					if(!empty($upics)){
						foreach($upics as $pics){
							if($pics->pics_small=='' || file_exists(FCPATH.$pics->pics_small)==FALSE){$data['pics_small']='img/icon120.png';}else{$data['pics_small']=$pics->pics_small;}
						}
					} else {$data['pics_small']='img/icon120.png';}
					
					//get invoices stat
					$total_invoice_amt = 0;
					$invoice_list = '';
					$total_invoice = 0;
					$total_paid = 0;
					$total_unpaid = 0;
					$total_partial = 0;
					$total_outstanding = 0;
					$total_overdue = 0;
					$total_cancel = 0;
					$getinv = $this->m_invoices->query_invoice_client($get_u_id);
					if(!empty($getinv)){
						foreach($getinv as $inv){
							//get total invoices
							$total_invoice += 1;
							$total_invoice_amt += $inv->amt;
							
							//get invoice items
							$invitem_count = 0;
							$invitem = $this->m_invoices->query_invoice_item_id($inv->id);
							if(!empty($invitem)){
								foreach($invitem as $item){
									if($inv->status == 'Paid'){
										$total_paid += $inv->amt;
									} else if($inv->status == 'Unpaid'){
										$total_unpaid += $inv->amt;
									} else if($inv->status == 'Partially Paid' || $inv->status == 'Credit'){
										$total_partial += $inv->paid;
										if($inv->status == 'Credit') {
											$total_outstanding += $inv->amt;
										} else {
											$total_outstanding += $inv->balance;
										}
									} else if($inv->status == 'Overdue'){
										$total_overdue += $inv->amt;
									} else if($inv->status == 'Cancel'){
										$total_cancel += $inv->amt;
									}
									
									$invitem_count += 1;
								}
							}
							
							if($inv->status == 'Paid'){
								$status_label = 'label label-success';
							} else if($inv->status == 'Unpaid'){
								$status_label = 'label label-primary';
							} else if($inv->status == 'Partially Paid'){
								$status_label = 'label label-info';
							} else if($inv->status == 'Overdue'){
								$status_label = 'label label-warning';
							} else if($inv->status == 'Cancelled' || $inv->status == 'Credit'){
								$status_label = 'label label-danger';
							} else {
								$status_label = '';
							}
							
							$invoice_list .= '
								<tr>
                                    <td class="text-center" style="width:100px;"><a href="'.base_url('invoices/view?v='.$inv->id.'&s='.$inv->store_id.'&u='.$inv->client_id.'').'"><strong>#'.$inv->id.'</strong></a></td>
                                    <td class="text-center">'.$invitem_count.'</td>
                                    <td class="text-right"><strong>&#8358;'.number_format($inv->amt).'</strong></td>
                                    <td class="text-center"><span class="'.$status_label.'">'.$inv->status.'</span></td>
                                    <td class="text-center">'.date('d M, Y', strtotime($inv->reg_date)).'</td>
                                    <td class="text-center" style="width:70px;">
                                        <div class="btn-group btn-group-xs">
                                            <a href="'.base_url('invoices/view?v='.$inv->id.'&s='.$inv->store_id.'&u='.$inv->client_id.'').'" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="View Invoice"><i class="fa fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
							';
						}
					}
					
					$data['total_invoice_amt'] = $total_invoice_amt;
					$data['invoice_list'] = $invoice_list;
					$data['total_invoice'] = $total_invoice;
					$data['total_paid'] = $total_paid;
					$data['total_unpaid'] = $total_unpaid;
					$data['total_partial'] = $total_partial;
					$data['total_outstanding'] = $total_outstanding;
					$data['total_overdue'] = $total_overdue;
					$data['total_cancel'] = $total_cancel;
				}
			}
		}
		
		$data['title'] = 'Customer | '.app_name;
		$data['page_active'] = 'customer';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('customers/view', $data);
	  	$this->load->view('designs/footer', $data);
	}
}