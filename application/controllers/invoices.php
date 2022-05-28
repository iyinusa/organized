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

	public function debtors()
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
		
		$data['title'] = 'Debtors | '.app_name;
		$data['page_active'] = 'invoice';
		
		$this->load->view('designs/header', $data);
		$this->load->view('invoices/debtors', $data);
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
				$data['e_paid'] = $item->paid;
				$data['e_balance'] = $item->balance;
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
			$vat = $_POST['vat'];
			$sub_amt = $_POST['SubTotal'];
			$amt = $_POST['SumTotal'];
			$sumvat = $_POST['sumvat'];
			$status = $_POST['status'];	
			$details = $_POST['details'];
			$start_date = $_POST['start_date'];
			$due_date = $_POST['due_date'];
			$i = 0;
			
			$paid_date = date('Y-m-d');
			$now = date("Y-m-d H:i:s");

			$paid = 0;
			$balance = 0;

			if($status == 'Unpaid'){
				$paid = 0;
				$balance = 0;
			}

			if($status == 'Paid' || $status == 'Partially Paid'){
				if($status == 'Paid') {
					$paid = $amt;
				} else {
					$paid = $this->input->post('partial_pay');
				}
				$balance = $amt - $paid;
				if($balance <= 0) {
					$status = 'Paid';
				}
			}
			
			//check update post
			if($invoice_id != ''){
				$upd_data['store_id'] = $store_id;
				$upd_data['client_id'] = $client_id;
				$upd_data['status'] = $status;
				$upd_data['amt'] = $amt;
				$upd_data['vat'] = $sumvat;
				$upd_data['paid'] = $paid;
				$upd_data['balance'] = $balance;
				$upd_data['details'] = $details;
				$upd_data['due_date'] = $due_date;
				$upd_data['upd_date'] = $now;
				if($status == 'Paid' || $status == 'Partially Paid' || $status == 'Credit'){
					$upd_data['pay_date'] = $paid_date;
				}
				
				$upd_id = $this->m_invoices->update_invoice($invoice_id, $upd_data);
				if($upd_id){
					//register each product
					for($i>=0; $i<count($pdt_id); $i++){
						//determine product or services id
						$ps_id = explode('|', $pdt_id[$i]);
						if($ps_id[0] == 0){$product_id = $ps_id[1]; $service_id = 0;}else{$service_id = $ps_id[1]; $product_id = 0;}

						// compute vat
						$vat_value = $sub_amt[$i] * ($vat[$i] / 100);
						
						//check if item is new among updates
						$inv_item_check = $this->m_invoices->check_invoice_item($item_id[$i]);
						if(count($inv_item_check) <= 0){
							//add new item in upadate to database
							$n_data['invoice_id'] = $invoice_id;
							$n_data['pdt_id'] = $product_id;
							$n_data['service_id'] = $service_id;
							$n_data['qty'] = $qty[$i];
							$n_data['amt'] = $price[$i];
							$n_data['discount'] = $discount[$i];
							$n_data['type'] = $type[$i];
							$n_data['vat'] = $vat[$i];
							$n_data['vat_value'] = $vat_value;
							$n_data['sub_total'] = $sub_amt[$i];
							$this->m_invoices->reg_insert_item($n_data);
						} else {
							//just update item
							$item_data['pdt_id'] = $product_id;
							$item_data['service_id'] = $service_id;
							$item_data['qty'] = $qty[$i];
							$item_data['amt'] = $price[$i];
							$item_data['discount'] = $discount[$i];
							$item_data['type'] = $type[$i];
							$item_data['vat'] = $vat[$i];
							$item_data['vat_value'] = $vat_value;
							$item_data['sub_total'] = $sub_amt[$i];
							$this->m_invoices->update_invoice_item($item_id[$i], $item_data);
						}
					}
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('invoices/'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data['store_id'] = $store_id;
				$insert_data['client_id'] = $client_id;
				$insert_data['status'] = $status;
				$insert_data['amt'] = $amt;
				$insert_data['vat'] = $sumvat;
				$insert_data['paid'] = $paid;
				$insert_data['balance'] = $balance;
				$insert_data['details'] = $details;
				$insert_data['reg_date'] = $now;
				$insert_data['start_date'] = $start_date;
				$insert_data['due_date'] = $due_date;
				$insert_data['upd_date'] = $now;
				if($status == 'Paid' || $status == 'Partially Paid' || $status == 'Credit'){
					$insert_data['pay_date'] = $paid_date;
				}
				
				$insert_id = $this->m_invoices->reg_insert($insert_data);
				
				if($insert_id){
					//register each product
					for($i>=0; $i<count($pdt_id); $i++){
						//determine product or services id
						$ps_id = explode('|', $pdt_id[$i]);
						if($ps_id[0] == 0){$product_id = $ps_id[1]; $service_id = 0;}else{$service_id = $ps_id[1]; $product_id = 0;}

						// compute vat
						$vat_value = $sub_amt[$i] * ($vat[$i] / 100);

						$item_data['invoice_id'] = $insert_id;
						$item_data['pdt_id'] = $product_id;
						$item_data['service_id'] = $service_id;
						$item_data['qty'] = $qty[$i];
						$item_data['amt'] = $price[$i];
						$item_data['discount'] = $discount[$i];
						$item_data['type'] = $type[$i];
						$item_data['vat'] = $vat[$i];
						$item_data['vat_value'] = $vat_value;
						$item_data['sub_total'] = $sub_amt[$i];
						$this->m_invoices->reg_insert_item($item_data);
						
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
		$store_img = '';
		
		//get customer info
		if(!$get_id || !$get_s_id || !$get_u_id){
			redirect(base_url('invoices/'), 'refresh'); //redirect back to invoice list
		} else {
			//get invoice details
			$allinv = $this->m_invoices->query_invoice_id($get_id, $get_s_id);
			if(!empty($allinv)){
				foreach($allinv as $inv){
					$data['invoice_amt'] = (float)$inv->amt;
					$data['invoice_vat'] = (float)$inv->vat;
					$data['invoice_paid'] = (float)$inv->paid;
					$data['invoice_balance'] = (float)$inv->balance;
					$data['invoice_details'] = $inv->details;
					$data['invoice_status'] = $inv->status;
				}
				
				$data['invoice_id'] = $get_id;
				$data['inv_store_id'] = $get_s_id;
				$data['inv_cus_id'] = $get_u_id;
				
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
						$store_img = 'img/icon57.png';
					} else {
						$gti = $this->users->query_img_id($sd->img_id);
						foreach($gti as $gtimg){
							if($gtimg->pics_square=='' || file_exists(FCPATH.$gtimg->pics_square)==FALSE){$store_img='img/icon120.png';}else{$store_img=$gtimg->pics_square;}	
						}
					}
					
					$data['store_img'] = $store_img;
					
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
					$data['c_company'] = $us->company;
					$data['email'] = $us->email;
					$data['phone'] = $us->phone;
					$data['address'] = $us->address;
					$data['state'] = $us->state;
					$data['country'] = $us->country;
					
					//get user profile pics
					$upics = $this->users->query_single_user_id($get_u_id);
					if(!empty($upics)){
						foreach($upics as $pics){
							if($pics->pics_small=='' || file_exists(FCPATH.$pics->pics_small)==FALSE){$data['pics_small']=$store_img;}else{$data['pics_small']=$pics->pics_small;}
						}
					} else {$data['pics_small']=$store_img;}
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
	
	public function send_invoice() {
		$log_user_email = $this->session->userdata('org_user_email');
		
		$invoice_id = $_POST['invoice_id'];
		$invoice_store_id = $_POST['invoice_store_id'];
		$invoice_cus_id = $_POST['invoice_cus_id'];
		$cus_email = $_POST['cus_email'];
		$store_name = $_POST['store_name'];
		
		if($invoice_id == '' && $invoice_store_id == '' && $invoice_cus_id == '' && $cus_email == '' && $store_name == ''){
			echo '<h5 class="alert alert-warning">There is problem this time. Please try later</h5>';
		} else {
			//format invoice
			$inv_layout = '';
			$allinv = $this->m_invoices->query_invoice_id($invoice_id, $invoice_store_id);
			if(!empty($allinv)){
				foreach($allinv as $inv){
					$invoice_amt = $inv->amt;
					$invoice_vat = $inv->vat;
					$invoice_paid = $inv->paid;
					$invoice_balance = $inv->balance;
					$invoice_details = $inv->details;
					$invoice_status = $inv->status;
					$invoice_date = $inv->reg_date;
					$invoice_due = $inv->due_date;
				}
				
				//get store data
				$gsd = $this->m_stores->query_store_id($invoice_store_id);
				if(!empty($gsd)){
					foreach($gsd as $sd){
						$store_address = $sd->address;
						$store_city = $sd->city;
						$store_state = $sd->state;
						$store_country = $sd->country;
						
						//store logo
						if($sd->img_id == 0){
							$store_img = 'img/icon57.png';
						} else {
							$gti = $this->users->query_img_id($sd->img_id);
							foreach($gti as $gtimg){
								if($gtimg->pics_square=='' || file_exists(FCPATH.$gtimg->pics_square)==FALSE){$store_img='img/icon120.png';}else{$store_img=$gtimg->pics_square;}	
							}
						}
						
						//get store owner details
						$sowner = $this->users->query_single_user_id($sd->user_id);
						if(!empty($sowner)){
							foreach($sowner as $owner){
								$store_phone = $owner->phone;
							}
						} else {$store_phone = '(000) 000 000 0000';}
					}
				}
				
				//get customer data
				$csu = $this->m_customers->query_customer_store_user($invoice_store_id, $invoice_cus_id);
				if(!empty($csu)){
					foreach($csu as $us){
						$firstname = $us->firstname;
						$lastname = $us->lastname;
						$email = $us->email;
						$phone = $us->phone;
						$address = $us->address;
						$state = $us->state;
						$country = $us->country;
					}
				}
				
				//get invoice items
				$item_list = '';
				$invitems = $this->m_invoices->query_invoice_item_id($invoice_id);
				if(!empty($invitems)){
					$item_no = 1; $sub = 0; $each_amt = 0; $each_discount = 0; $discount_note = '';
					foreach($invitems as $item){
						if($item->pdt_id != 0){
							$pdt_ser = $this->m_products->query_product_id($item->pdt_id); //get product
						} else {
							$pdt_ser = $this->m_services->query_service_id($item->service_id);	//get service
						}
						
						if(!empty($pdt_ser)){
							foreach($pdt_ser as $ps){
								$ps_name = $ps->name;
								$ps_price = $ps->price;
								$ps_details = $ps->details;	
							}
						}
						
						if($ps_details != ''){$ps_details = '<br />'.$ps_details;}
						
						$sub += $item->sub_total;
						$each_amt = $item->sub_total;

						if($item->type == 'Percent') {
							$each_discount = $each_amt * ($item->discount / 100);
						} else {
							$each_discount = $item->discount;
						}

						if($each_discount > 0) {
							$discount_note = '<br/><small><i class="small">Discount: &#8358;'.number_format($each_discount,2).'</i></small>';
						} else {$discount_note = '';}

						// check vat
						$vat = $item->vat; $vat_value = $item->vat_value;
						if($vat && $vat_value) {
							$vat_note = '<br/><small><i class="small">VAT ('.$vat.'%): &#8358;'.number_format($vat_value,2).'</i></small>';
						} else {$vat_note = '';}
						
						$item_list .= '
							<tr style="background:rgba(237, 237, 237, 0.3);">
								<td style="padding:5px;">'.$item_no.'</div>
								<td style="padding:5px;"><b>'.$ps_name.'</b> <small>'.$ps_details.' '.$discount_note.' '.$vat_note.'</small></td>
								<td align="center" style="padding:5px;">'.$item->qty.'</td>
								<td align="center" style="padding:5px;">&#8358;'.number_format((float)$item->amt,2).'</td>
								<td align="right" style="padding:5px;"><b>&#8358;'.number_format((float)$each_amt,2).'</b></td>
							</tr>
						';
						
						$item_no += 1;
					}
				}

				if($invoice_status == 'Partially Paid') {
					$tb_left = '
						<div style="padding:5px;">
							<b>PAID:</b>
						</div>
						<div style="padding:5px;">
							<b>BALANCE:</b>
						</div>
					';

					$tb_right = '
						<div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
							&#8358;'.number_format($invoice_paid,2).'
						</div>
						<div style="background:rgba(237, 237, 237, 0.4); padding:5px;">
							<b>&#8358;'.number_format($invoice_balance,2).'</b>
						</div>
					';
				} else {
					$tb_left = ''; $tb_right = '';
				}
				
				$inv_layout = '
					<div style="width:100%;">
						<table style="width:100%;">
							<tr>
								<td valign="top" width="60%">
									<img alt="" src="'.base_url($store_img).'" style="float:left; height:25px; margin-right: 5px;" />
									<span style="font-size:large;">'.strtoupper($store_name).'</span><br /><br />
									'.$store_address.' '.$store_city.' '.$store_state.' '.$store_country.'<br />
									'.$store_phone.'
								</td>
								<td valign="top" align="right">
									<span style="font-size:large;"><b>INVOICE:</b> '.$invoice_status.'</span><br /><br />
									DATE: '.$invoice_date.'<br />
									INVOICE : #'.$invoice_id.'<br />
									DUE DATE: '.$invoice_due.'
								</td>
							</tr>
						</table>

						<br />
						
						<div>
							<div>
								BILL TO:
							</div>
							<div>
								<b>'.$firstname.' '.$lastname.'</b><br />
								'.$state.' '.$country.'<br />
								'.$phone.' | '.$email.'
							</div>
						</div>
						
						<br /><br />
						<table style="width:100%;">
							<thead style="background-color:#DDD; color:#000;">
								<tr>
									<td style="padding:5px;"></td>
									<td style="padding:5px;">PRODUCTS/SERVICES</td>
									<td align="center" style="padding:5px;">QTY</td>
									<td align="center" style="padding:5px;">UNIT PRICE</td>
									<td align="right" style="padding:5px;">AMOUNT</td>
								</tr>
							</thead>
							<tbody>
								'.$item_list.'
							<tbody>
						</table>

						<table style="width:100%;">
							<td colspan="3">
								<div class="h4" style="padding:5px 0px; vertical-align:top;">
									<b>NOTE:</b><br /><br/>
									'.$invoice_details.'
								</div>
							</td>
							<td align="right">
								<div style="padding:5px;">
									<b>SUB-TOTAL:</b>
								</div>

								<div style="padding:5px;">
									<b>VAT:</b>
								</div>

								<div style="padding:5px;">
									<b>TOTAL:</b>
								</div>

								'.$tb_left.'
							</td>
							<td align="right">
								<div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
									&#8358;'.number_format($sub,2).'
								</div>

								<div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
									&#8358;'.number_format($invoice_vat,2).'
								</div>

								<div style="background:rgba(237, 237, 237, 0.4); padding:5px;">
									<b>&#8358;'.number_format($invoice_amt,2).'</b>
								</div>

								'.$tb_right.'
							</td>
						</table>
					</div>
				';
			}
			
			//email notification processing
			$this->email->clear(); //clear initial email variables
			$this->email->to($cus_email);
			$this->email->from(app_email, app_name);
			$this->email->subject('[INVOICE] '.strtoupper($store_name));
			
			//compose html body of mail
			$mail_subhead = 'You have an Invoice from '.ucwords($store_name);
			$body_msg = $inv_layout.'<br />';
			
			$mail_data = array('message'=>$body_msg, 'subhead'=>$mail_subhead);
			$this->email->set_mailtype("html"); //use HTML format
			$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);

			$this->email->message($mail_design);
			
			if($this->email->send()) {
				echo '<h5 class="alert alert-success">Invoice sent to '.$cus_email.'.</h5>';
			} else {
				echo '<h5 class="alert alert-warning">There is problem this time. Please try later</h5>';
			}
		} exit;
	}

	public function get_customer($store_id, $client_id='') {
		$lists = array();
		
		if($store_id) {
			// list customers
			$cust_list = '';
			$allcust = $this->crud->read_single('store_id', $store_id, 'customer');
			if(!empty($allcust)){
				foreach($allcust as $cust){
					if(!empty($client_id)){
						if($client_id == $cust->user_id){
							$c_sel = 'selected="selected"';	
						} else {$c_sel = '';}
					} else {$c_sel = '';}

					if($cust->company) {$company = '('.$cust->company.')';} else {$company = '';}
					
					$cust_list .= '<option value="'.$cust->user_id.'" '.$c_sel.'>'.$cust->firstname.' '.$cust->lastname.' '.$company.'</option>';
				}
			}

			// list products
			$prod_list = '';
			$allprod = $this->crud->read_single('store_id', $store_id, 'product_cat');
			if(!empty($allprod)){
				foreach($allprod as $prod){
					$allprods = $this->crud->read_single('cat_id', $prod->id, 'product');
					if(!empty($allprods)){
						foreach($allprods as $pdt){
							$prod_list .= '<option value="0|'.$pdt->id.'">'.$pdt->name.'</option>';
						}
					}
				}
			}

			// list services
			$serv_list = '';
			$allserv = $this->crud->read_single('store_id', $store_id, 'service_cat');
			if(!empty($allserv)){
				foreach($allserv as $serv){
					$allservs = $this->crud->read_single('cat_id', $serv->id, 'service');
					if(!empty($allservs)){
						foreach($allservs as $ser){
							$serv_list .= '<option value="1|'.$ser->id.'">'.$ser->name.'</option>';
						}
					}
				}
			}

			$lists['cust'] = '
				<select id="client_id" name="client_id" class="select-chosen" data-placeholder="Select Client/Customer" required>
					<option></option>	
					'.$cust_list.'
				</select>
			';

			$lists['product'] = $prod_list;

			$lists['service'] = $serv_list;
		}

		echo json_encode($lists);
	}

	public function get_invoice_items($id) {
		if($id) {
			$invitem = $this->m_invoices->query_invoice_item_id($id);
			if(!empty($invitem)){
				$item_no = 1; $others = '';
				foreach($invitem as $invi){
					$e_item = $invi->id;
					$e_pdt_id = 0; $e_service_id = 0;
					if($invi->pdt_id != 0){$e_pdt_id = $invi->pdt_id;} else {$e_service_id = $invi->service_id;}
					$e_qty = $invi->qty;
					$e_price = $invi->amt;
					$e_discount = $invi->discount;
					$e_type = $invi->type;
					$e_vat = $invi->vat;
					$e_vat_value = $invi->vat_value;
					$e_sub = $invi->sub_total;
					$e_sub_vat = $e_sub - $e_vat_value;

					if(!empty($e_type)){
						if($e_type=='Amount'){$t1='selected="selected"';}else{$t1='';}
						if($e_type=='Percent'){$t2='selected="selected"';}else{$t2='';}
					} else {$t1=''; $t2='';}

					if($item_no == 1) {$cloning = 'disabled';} else {$cloning = '';}

					$store_id = $this->crud->read_field('id', $id, 'invoice', 'store_id');

					// list products
					$prod_list = '';
					$allprod = $this->crud->read_single('store_id', $store_id, 'product_cat');
					if(!empty($allprod)){
						foreach($allprod as $prod){
							$allprods = $this->crud->read_single('cat_id', $prod->id, 'product');
							if(!empty($allprods)){
								foreach($allprods as $pdt){
									if($e_pdt_id == $pdt->id){$p_sel = 'selected="selected"';} else {$p_sel = '';}
									$prod_list .= '<option value="0|'.$pdt->id.'" '.$p_sel.'>'.$pdt->name.'</option>';
								}
							}
						}
					}

					// list services
					$serv_list = '';
					$allserv = $this->crud->read_single('store_id', $store_id, 'service_cat');
					if(!empty($allserv)){
						foreach($allserv as $serv){
							$allservs = $this->crud->read_single('cat_id', $serv->id, 'service');
							if(!empty($allservs)){
								foreach($allservs as $ser){
									if($e_service_id == $ser->id){$s_sel = 'selected="selected"';} else {$s_sel = '';}
									$serv_list .= '<option value="1|'.$ser->id.'" '.$s_sel.'>'.$ser->name.'</option>';
								}
							}
						}
					}

					$others .= '
						<tr class="tbChild">
							<td class="text-center sno">'.$item_no.'</td>
							<td>
								<input type="hidden" name="item_id[]" value="'.$e_item.'">
								<select name="products[]" class="form-control tbSelect2 tbCloneSelect2" data-placeholder="Select Product/Services">
									<option>Select Product/Services</option>
									
									<optgroup class="product_list" label="Products">
										'.$prod_list.'
									</optgroup>
									<optgroup class="service_list" label="Services">
										'.$serv_list.'
									</optgroup>
								</select>
							</td>
							<td class="text-center">
								<input type="text" name="qty[]" class="form-control text-center tbEvent" value="'.$e_qty.'">
							</td>
							<td class="text-right">
								<input type="text" name="price[]" class="form-control text-center required tbEvent" autocomplete="off" value="'.$e_price.'">
							</td>
							<td class="text-center">
								<input type="text" name="discount[]" class="form-control text-center tbEvent" placeholder="10" value="'.$e_discount.'">
							</td>
							<td class="text-center">
								<select name="type[]" class="form-control tbEvent" data-placeholder="Select Type">
									<option></option>
									<option value="Amount" '.$t1.'>Amount</option>
									<option value="Percent" '.$t2.'>Percent</option>
								</select>
							</td>
							<td class="text-center">
								<input type="text" name="vat[]" class="form-control text-center required tbEvent" autocomplete="off" placeholder="5" value="'.$e_vat.'">
							</td>
							<td class="text-right">
								<h4 class="pull-right">
									<span class="tbSubTotal">'.$e_sub.'</span>
									<span style="display:none;" class="tbSubTotalNoVat">'.$e_sub_vat.'</span>
									<input type="hidden" name="SubTotal[]" id="SubTotal" value="'.$e_sub.'" />
								</h4>
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-danger '.$cloning.' removeClone" data-id="'.$e_item.'"><i class="fa fa-minus"></i></button>
							</td>
						</tr>
					';

					$item_no += 1;
				}

				echo $others;
			}
		}
	}

	public function delete_item() {
		if($_POST) {
			$id = $this->input->post('id');
			$del_id = $this->crud->delete('id', $id, 'invoice_item');
			echo $del_id;
		}
	}
}