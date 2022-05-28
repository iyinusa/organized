<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
		
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
		
		$data['title'] = 'Invoices | '.app_name;
		$data['page_active'] = 'invoice';
		
		$this->load->view('designs/header', $data);
		$this->load->view('invoices/invoices', $data);
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
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		//check for update
		$get_id = $this->input->get('e');
		$get_s_id = $this->input->get('s');
		if($get_id != '' && $get_s_id != ''){
			$gq = $this->m_invoices->query_invoice_id($get_id, $get_s_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_client_id'] = $item->client_id;
				$data['e_status'] = $item->status;
				$data['e_amount'] = $item->amt;
				$data['e_details'] = $item->details;
				$data['e_reg_date'] = $item->reg_date;
				$data['e_start_date'] = $item->start_date;
				$data['e_due_date'] = $item->due_date;
				$data['e_upd_date'] = $item->upd_date;	
			}
		}
		
		//check for posting
		if($_POST){
			$invoice_id = $_POST['invoice_id'];
			$item_id = $_POST['item_id'];
			$store_id = $_POST['store_id'];
			$client_id = $_POST['client_id'];
			$pdt_id = $_POST['products'];
			$price = $_POST['price'];
			$qty = $_POST['qty'];
			$discount = $_POST['discount'];
			$type = $_POST['type'];
			$sub_amt = $_POST['SubTotal'];
			$amt = $_POST['tbTotal'];	
			$status = $_POST['status'];	
			$details = $_POST['details'];
			$start_date = $_POST['start_date'];
			$due_date = $_POST['due_date'];
			$i = 0;
			
			$paid_date = date('d M Y');
			$now = date("Y-m-d H:i:s");
			
			//check update post
			if($invoice_id != ''){
				if($status == 'Paid'){
					$upd_data = array(
						'store_id' => $store_id,
						'client_id' => $client_id,
						'status' => $status,
						'amt' => $amt,
						'details' => $details,
						'due_date' => $due_date,
						'upd_date' => $now,
						'pay_date' => $paid_date
					);
				} else {
					$upd_data = array(
						'store_id' => $store_id,
						'client_id' => $client_id,
						'status' => $status,
						'amt' => $amt,
						'details' => $details,
						'due_date' => $due_date,
						'upd_date' => $now
					);
				}
				
				$upd_id = $this->m_invoices->update_invoice($invoice_id, $upd_data);
				if($upd_id){
					//register each product
					for($i>=0; $i<count($pdt_id); $i++){
						//determine product or services id
						$ps_id = explode('|', $pdt_id[$i]);
						if($ps_id[0] == 0){$product_id = $ps_id[1]; $service_id = 0;}else{$service_id = $ps_id[1]; $product_id = 0;}
						
						//check if item is new among updates
						$inv_item_check = $this->m_invoices->check_invoice_item($item_id[$i]);
						if(count($inv_item_check) <= 0){
							//add new item in upadate to database
							$n_data = array(
								'invoice_id' => $invoice_id,
								'pdt_id' => $product_id,
								'service_id' => $service_id,
								'qty' => $qty[$i],
								'amt' => $price[$i],
								'discount' => $discount[$i],
								'type' => $type[$i],
								'sub_total' => $sub_amt[$i]
							);
							
							if($this->m_invoices->reg_insert_item($n_data)){}
						} else {
							//just update item
							$item_data = array(
								'pdt_id' => $product_id,
								'service_id' => $service_id,
								'qty' => $qty[$i],
								'amt' => $price[$i],
								'discount' => $discount[$i],
								'type' => $type[$i],
								'sub_total' => $sub_amt[$i]
							);
							
							if($this->m_invoices->update_invoice_item($item_id[$i], $item_data)){}
						}
					}
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data = array(
					'store_id' => $store_id,
					'client_id' => $client_id,
					'status' => $status,
					'amt' => $amt,
					'details' => $details,
					'reg_date' => $now,
					'start_date' => $start_date,
					'due_date' => $due_date,
					'upd_date' => $now,
					'pay_date' => $paid_date
				);
				
				$insert_id = $this->m_invoices->reg_insert($insert_data);
				
				if($insert_id){
					//register each product
					for($i>=0; $i<count($pdt_id); $i++){
						//determine product or services id
						$ps_id = explode('|', $pdt_id[$i]);
						if($ps_id[0] == 0){$product_id = $ps_id[1]; $service_id = 0;}else{$service_id = $ps_id[1]; $product_id = 0;}
						
						$item_data = array(
							'invoice_id' => $insert_id,
							'pdt_id' => $product_id,
							'service_id' => $service_id,
							'qty' => $qty[$i],
							'amt' => $price[$i],
							'discount' => $discount[$i],
							'type' => $type[$i],
							'sub_total' => $sub_amt[$i]
						);
						
						if($this->m_invoices->reg_insert_item($item_data)){}
						
						//try deduct quantity from stock
						if($status == 'Paid' && $product_id){
							//remove product
							$rem_data = array(
								'store_id' => $store_id,
								'pdt_id' => $product_id,
								'distribute' => 2,
								'from_store_id' => 0,
								'qty' => $qty[$i],
								'deduct' => 1,
								'reg_date' => $now
							);
							if($this->m_products->reg_product_stock($rem_data) > 0){}	
						}
					}
					
					//get store name
					$getstore = $this->m_stores->query_store_id($store_id);
					if(!empty($getstore)){
						foreach($getstore as $store){
							$store_name = $store->store;
						}
					}
					
					$content = 'Invoice created from '.$store_name.' with '.$status.' status';
					
					//try register activity
					$reg_activity = array(
						'type' => 'new_invoice',
						'store_id' => $store_id,
						'p_id' => $insert_id,
						's_id' => $insert_id,
						'content' => $content,
						'reg_date' => $now
					);
					
					$this->users->reg_activity($reg_activity);
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('invoices/'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
				}	
			}
		}
		
		
		$data['title'] = 'Manage Invoice | '.app_name;
		$data['page_active'] = 'invoice';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('invoices/add', $data);
	  	$this->load->view('designs/footer', $data);
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
		$get_id = $this->input->get('v'); //store invoice ID
		$get_s_id = $this->input->get('s'); //store ID
		$get_u_id = $this->input->get('u'); //customer ID
		
		//get customer info
		if(!$get_id || !$get_s_id || !$get_u_id){
			redirect(base_url('invoices/'), 'refresh'); //redirect back to invoice list
		} else {
			//get invoice details
			$allinv = $this->m_invoices->query_invoice_id($get_id, $get_s_id);
			if(!empty($allinv)){
				foreach($allinv as $inv){
					$data['invoice_amt'] = $inv->amt;
				}
				
				$data['invoice_id'] = $get_id;
				
				//get invoice items
				$invitems = $this->m_invoices->query_invoice_item_id($get_id);
				if(!empty($invitems)){
					$data['invoice_items'] = $invitems;
				}
			}
			
			//get store data
			$gsd = $this->m_stores->query_store_id($get_s_id);
			if(!empty($gsd)){
				foreach($gsd as $sd){
					$data['store_name'] = $sd->store;
					$data['store_address'] = $sd->address;
					$data['store_city'] = $sd->city;
					$data['store_state'] = $sd->state;
					$data['store_country'] = $sd->country;
					
					//store logo
					if($sd->img_id == 0){
						$data['store_img'] = 'img/icon57.png';
					} else {
						$gti = $this->users->query_img_id($sd->img_id);
						foreach($gti as $gtimg){
							if($gtimg->pics_square=='' || file_exists(FCPATH.$gtimg->pics_square)==FALSE){$data['store_img']='img/icon120.png';}else{$data['store_img']=$gtimg->pics_square;}	
						}
					}
					
					//get store owner details
					$sowner = $this->users->query_single_user_id($sd->user_id);
					if(!empty($sowner)){
						foreach($sowner as $owner){
							$data['store_phone'] = $owner->phone;
						}
					} else {$data['store_phone'] = '(000) 000 000 0000';}
				}
			}
			
			//get customer data
			$csu = $this->m_customers->query_customer_store_user($get_s_id, $get_u_id);
			if(!empty($csu)){
				foreach($csu as $us){
					$data['firstname'] = $us->firstname;
					$data['lastname'] = $us->lastname;
					$data['email'] = $us->email;
					$data['phone'] = $us->phone;
					$data['address'] = $us->address;
					$data['state'] = $us->state;
					$data['country'] = $us->country;
					
					//get user profile pics
					$upics = $this->users->query_single_user_id($get_u_id);
					if(!empty($upics)){
						foreach($upics as $pics){
							if($pics->pics_small=='' || file_exists(FCPATH.$pics->pics_small)==FALSE){$data['pics_small']='img/icon120.png';}else{$data['pics_small']=$pics->pics_small;}
						}
					} else {$data['pics_small']='img/icon120.png';}
				}
			}
		}
		
		$data['title'] = 'Invoice | '.app_name;
		$data['page_active'] = 'invoice';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('invoices/view', $data);
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
			redirect(base_url('invoices/'), 'refresh');
		} else {
			$del_id = $_POST['del_id'];
			$del_s_id = $_POST['del_s_id'];
			if($del_id && $del_s_id){
				$gq = $this->m_invoices->delete_invoice($del_id, $del_s_id);
				redirect(base_url('invoices/'), 'refresh');
			}
		}
	}
	
	public function display_amt(){
		$pdt_id = explode('|', $_POST['id']);
		$price = 0;
		
		if($pdt_id[0] == 0){
			$query = $this->m_products->query_product_id($pdt_id[1]);
		} else {
			$query = $this->m_services->query_service_id($pdt_id[1]);
		}
		
		if(!empty($query)){
			foreach($query as $item){
				$price = $item->price;
			}
		}
		
		echo $price;
		exit;
	}
}