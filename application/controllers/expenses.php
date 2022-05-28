<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_expenses'); //load MODEL
		
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
		$get_r_id = $this->input->get('r');
		$get_c_id = $this->input->get('c');
		if($get_r_id != '' && $get_c_id != ''){
			$data['rec_del'] = $get_r_id;
			$data['rec_del_c'] = $get_c_id;
		} else {
			$data['rec_del'] = '';
			$data['rec_del_c'] = '';
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Daily Expenses | '.app_name;
		$data['page_active'] = 'expense';
		
		$this->load->view('designs/header', $data);
		$this->load->view('expenses/expenses', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function add_cat(){
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
		
		//check for update
		$get_id = $this->input->get('e');
		$get_s_id = $this->input->get('s');
		if($get_id != '' && $get_s_id != ''){
			$gq = $this->m_expenses->query_single_expense_cat($get_id, $get_s_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_type'] = $item->type;
				$data['e_cat'] = $item->cat;	
			}
		}
		
		//check for delete
		$get_id = $this->input->get('r');
		$get_s_id = $this->input->get('s');
		if($get_id != '' && $get_s_id != ''){
			$data['rec_del'] = $get_id;
			$data['rec_del_s'] = $get_s_id;
			
			if(isset($_POST['cancel'])){
				redirect(base_url('expenses/add_cat'), 'refresh');
			} else if(isset($_POST['delete'])) {
				$del_id = $_POST['del_id'];
				$del_s_id = $_POST['del_s_id'];
				if($del_id && $del_s_id){
					$gq = $this->m_expenses->delete_expense_cat($del_id, $del_s_id);
					redirect(base_url('expenses/add_cat'), 'refresh');
				}
			}
		} else {
			$data['rec_del'] = '';
			$data['rec_del_s'] = '';
		}
		
		//check for posting
		if($_POST){
			$cat_id = $_POST['cat_id'];
			$store_id = $_POST['store_id'];	
			$type = $_POST['type'];	
			$cat = $_POST['cat'];	
			
			if($store_id=='' || $cat==''){
				$data['err_msg'] = '';
			} else {
				//check update post
				if($cat_id != ''){
					$upd_data = array(
						'store_id' => $store_id,
						'type' => $type,
						'cat' => $cat
					);
					
					if($this->m_expenses->update_expense_cat($cat_id, $upd_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
					}
				} else {
					$insert_data = array(
						'store_id' => $store_id,
						'type' => $type,
						'cat' => $cat
					);
					
					if($this->m_expenses->reg_expense_cat($insert_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
					}	
				}	
			}
		}	
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		
		$data['title'] = 'Manage Expenses Categories | '.app_name;
		$data['page_active'] = 'expense';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('expenses/add_cat', $data);
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
		$get_c_id = $this->input->get('c');
		if($get_id != '' && $get_c_id != ''){
			$gq = $this->m_expenses->query_single_expense($get_id, $get_c_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_cat_id'] = $item->cat_id;
				$data['e_supply_id'] = $item->supplier_id;
				$data['e_date'] = $item->exp_date;
				$data['e_price'] = $item->price;
				$data['e_details'] = $item->details;
				$data['e_status'] = $item->status;	
			}
		}
		
		//check for posting
		if($_POST){
			$expense_id = $_POST['expense_id'];	
			$store_id = $_POST['s_store_id'];
			$cat_id = $_POST['s_cat_id'];
			$s_sup = $_POST['s_sup'];
			$date = $_POST['s_date'];		
			$price = $_POST['s_price'];	
			$details = $_POST['s_details'];
			$now = date("Y-m-d H:i:s");
			
			//check update post
			if($expense_id != ''){
				$upd_data = array(
					'store_id' => $store_id,
					'cat_id' => $cat_id,
					'supplier_id' => $s_sup,
					'exp_date' => $date,
					'price' => $price,
					'details' => $details
				);
				
				if($this->crud->update('id', $expense_id, 'expense', $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data = array(
					'store_id' => $store_id,
					'cat_id' => $cat_id,
					'supplier_id' => $s_sup,
					'exp_date' => $date,
					'price' => $price,
					'details' => $details,
					'status' => 1,
					'reg_date' => $now
				);
				
				$new_id = $this->m_expenses->reg_expense($insert_data);
				if($new_id > 0){
					//get store id
					$sc = $this->m_expenses->query_expense_cat_id($cat_id);
					if(!empty($sc)){
						foreach($sc as $c){
							$store_id = $c->store_id;
						}
					}
					
					//get store name
					$getstore = $this->m_stores->query_store_id($store_id);
					if(!empty($getstore)){
						foreach($getstore as $store){
							$store_name = $store->store;
						}
					}
					
					$content = 'New Expenses recorded for '.$store_name;
					
					//try register activity
					$reg_activity = array(
						'type' => 'new_expense',
						'store_id' => $store_id,
						'p_id' => $new_id,
						's_id' => $new_id,
						'content' => $content,
						'reg_date' => $now
					);
					
					$this->users->reg_activity($reg_activity);
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('expenses/'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time. Please try later</h5></div>';
				}	
			}
		}
		
		
		$data['title'] = 'Manage Daily Expenses | '.app_name;
		$data['page_active'] = 'expense';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('expenses/add', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function delete_cat(){
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
			redirect(base_url('expenses/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_s_id = $_POST['del_s_id'];
			if($del_id && $del_s_id){
				$gq = $this->m_expenses->delete_expense_cat($del_id, $del_s_id);
				redirect(base_url('expenses/'), 'refresh');
			}
		}
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
			redirect(base_url('expenses/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_c_id = $_POST['del_c_id'];
			if($del_id && $del_c_id){
				$gq = $this->m_expenses->delete_expense($del_id, $del_c_id);
				redirect(base_url('expenses/'), 'refresh');
			}
		}
	}

	public function get_category($store_id, $item_id='') {
		$lists = array();
		
		if($store_id) {
			// list expenses category
			$cat_list = '';
			$allcat = $this->crud->read_single('store_id', $store_id, 'expense_cat');
			if(!empty($allcat)){
				foreach($allcat as $cat){
					if(!empty($item_id)){
						$category_id = $this->crud->read_field('id', $item_id, 'expense', 'cat_id');
						if($category_id == $cat->id){
							$c_sel = 'selected="selected"';	
						} else {$c_sel = '';}
					} else {$c_sel = '';}

					if($cat->type) {$type = '('.$cat->type.')';} else {$type = '';}
					
					$cat_list .= '<option value="'.$cat->id.'" '.$c_sel.'>'.$cat->cat.' '.$type.'</option>';
				}
			}

			// list suppliers
			$supply_list = '';
			$allsupply = $this->crud->read_single('store_id', $store_id, 'customer');
			if(!empty($allsupply)){
				foreach($allsupply as $asup){
					$group_name = $this->crud->read_field('id', $asup->group_id, 'customer_group', 'name');
					if($group_name == 'Supplier') {
						if(!empty($item_id)){
							$supplier_id = $this->crud->read_field('id', $item_id, 'expense', 'supplier_id');
							if($supplier_id == $asup->id){
								$s_sel = 'selected="selected"';	
							} else {$s_sel = '';}
						} else {$s_sel = '';}

						if($asup->company) {$company = '('.$asup->company.')';} else {$company = '';}

						$supply_list .= '<option value="'.$asup->id.'" '.$s_sel.'>'.$asup->firstname.' '.$asup->lastname.' '.$company.'</option>';
					}
				}
			}
			

			$lists['category'] = '
				<select id="s_cat_id" name="s_cat_id" class="select-chosen" data-placeholder="Select Category" required>
					<option></option>	
					'.$cat_list.'
				</select>
			';

			$lists['supplier'] = '
				<select id="s_sup" name="s_sup" class="select-chosen" data-placeholder="Assign Supplier">
					<option value="0"></option>	
					'.$supply_list.'
				</select>
			';
		}

		echo json_encode($lists);
		die;
	}
}