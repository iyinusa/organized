<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SMS extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_phones'); //load MODEL
		
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
		
		$data['title'] = 'SMS History | '.app_name;
		$data['page_active'] = 'sms';
		
		$this->load->view('designs/header', $data);
		$this->load->view('sms/sms', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function directory(){
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
		
		//check for category update
		$get_cat_id = $this->input->get('cat_e');
		$get_cat_s_id = $this->input->get('cat_s');
		if($get_cat_id != '' && $get_cat_s_id != ''){
			$gq = $this->m_phones->query_single_phone_cat($get_cat_id, $get_cat_s_id);
			foreach($gq as $item){
				$data['cat_e_id'] = $item->id;
				$data['e_cat_store_id'] = $item->store_id;
				$data['e_cat_cat'] = $item->cat;	
			}
		}
		
		//check for category delete
		$get_cat_id = $this->input->get('cat_r');
		$get_cat_s_id = $this->input->get('cat_s');
		if($get_cat_id != '' && $get_cat_s_id != ''){
			$data['rec_cat_del'] = $get_cat_id;
			$data['rec_cat_del_s'] = $get_cat_s_id;
			
			if(isset($_POST['cat_cancel'])){
				redirect(base_url('sms/directory'), 'refresh');
			} else if(isset($_POST['cat_delete'])) {
				$del_cat_id = $_POST['del_cat_id'];
				$del_cat_s_id = $_POST['del_cat_s_id'];
				if($del_id && $del_s_id){
					$gq = $this->m_phones->delete_phone_cat($del_cat_id, $del_cat_s_id);
					redirect(base_url('sms/directory'), 'refresh');
				}
			}
		} else {
			$data['rec_cat_del'] = '';
			$data['rec_cat_del_s'] = '';
		}
		
		//check category posting
		if(isset($_POST['cat_submit'])){
			$cat_id = $_POST['cat_id'];
			$store_id = $_POST['store_id'];
			$cat = $_POST['cat'];
			
			if($store_id == '' || $cat == ''){
				$data['err_msg'] = '<h5 class="alert alert-info">Store and Category Name are required</h5>';
			} else {
				//check update post
				if($cat_id != ''){
					$upd_data = array(
						'store_id' => $store_id,
						'cat' => $cat
					);
					
					if($this->m_phones->update_phone_cat($cat_id, $upd_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
					}
				} else {
					$insert_data = array(
						'store_id' => $store_id,
						'cat' => $cat
					);
					
					if($this->m_phones->reg_phone_cat($insert_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
					}	
				}
			}
		}
		
		//check for move to phonebook
		$chk_id = $this->input->get('e');
		$chk_s_id = $this->input->get('s');
		$chk_m = $this->input->get('m');
		if($chk_id != '' && $chk_s_id != '' && $chk_m == 1){
			$data['dir_act'] = TRUE;
			
			//get record from customer
			$getq = $this->db->get_where('org_customer', array('id' => $chk_id, 'store_id' => $chk_s_id));
			$getq = $getq->result();
			if(!empty($getq)){
				foreach($getq as $itm){
					$data['e_cust_id'] = $itm->id;
					$data['e_name'] = $itm->firstname.' '.$itm->lastname;
					$data['e_no'] = $itm->phone;	
				}
			}
		}
		
		//check for update
		$get_id = $this->input->get('e');
		$get_c_id = $this->input->get('c');
		if($get_id != '' && $get_c_id != ''){
			$data['dir_act'] = TRUE;
			$gq = $this->m_phones->query_single_phone($get_id, $get_c_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_cat_id'] = $item->cat_id;
				$data['e_name'] = $item->name;
				$data['e_no'] = $item->no;	
			}
		}
		
		//check for delete
		$get_id = $this->input->get('r');
		$get_c_id = $this->input->get('c');
		if($get_id != '' && $get_c_id != ''){
			$data['rec_del'] = $get_id;
			$data['rec_del_c'] = $get_c_id;
			$data['dir_act'] = TRUE;
			
			if(isset($_POST['cancel'])){
				redirect(base_url('sms/directory'), 'refresh');
			} else if(isset($_POST['delete'])) {
				$del_id = $_POST['del_id'];
				$del_c_id = $_POST['del_c_id'];
				if($del_id && $del_c_id){
					$gq = $this->m_phones->delete_phone($del_id, $del_c_id);
					redirect(base_url('sms/directory'), 'refresh');
				}
			}
		} else {
			$data['rec_del'] = '';
			$data['rec_del_c'] = '';
		}
		
		//check phonebook posting
		if(isset($_POST['submit'])){
			$phone_id = $_POST['phone_id'];
			$cust_id = $_POST['cust_id'];
			$category_id = $_POST['category_id'];
			$name = $_POST['name'];
			$no = $_POST['no'];
			
			if($category_id == '' || $name == '' || $no == ''){
				$data['err_msg'] = '<h5 class="alert alert-info">All fields are required</h5>';
			} else {
				//check update post
				if($phone_id != ''){
					$upd_data = array(
						'cat_id' => $category_id,
						'name' => $name,
						'no' => $no
					);
					
					if($this->m_phones->update_phone($phone_id, $upd_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
						$data['dir_act'] = TRUE;
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
						$data['dir_act'] = TRUE;
					}
				} else {
					if($cust_id == ''){
						$insert_data = array(
							'cat_id' => $category_id,
							'name' => $name,
							'no' => $no
						);
					} else {
						$insert_data = array(
							'cat_id' => $category_id,
							'name' => $name,
							'no' => $no,
							'cust_id' => $cust_id
						);
					}
					
					if($this->m_phones->reg_phone($insert_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
						$data['dir_act'] = TRUE;
					} else {
						$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
						$data['dir_act'] = TRUE;
					}	
				}
			}
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'SMS Directory | '.app_name;
		$data['page_active'] = 'sms';
		
		$this->load->view('designs/header', $data);
		$this->load->view('sms/directory', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function compose(){
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
		
		$data['title'] = 'Compose SMS | '.app_name;
		$data['page_active'] = 'sms';
		
		$this->load->view('designs/header', $data);
		$this->load->view('sms/compose', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function buy(){
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
		
		$data['title'] = 'Buy SMS | '.app_name;
		$data['page_active'] = 'sms';
		
		$this->load->view('designs/header', $data);
		$this->load->view('sms/buy', $data);
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
			$gq = $this->m_services->query_single_service_cat($get_id, $get_s_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
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
				redirect(base_url('services/add_cat'), 'refresh');
			} else if(isset($_POST['delete'])) {
				$del_id = $_POST['del_id'];
				$del_s_id = $_POST['del_s_id'];
				if($del_id && $del_s_id){
					$gq = $this->m_services->delete_service_cat($del_id, $del_s_id);
					redirect(base_url('services/add_cat'), 'refresh');
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
			$cat = $_POST['cat'];	
			
			if($store_id=='' || $cat==''){
				$data['err_msg'] = '';
			} else {
				//check update post
				if($cat_id != ''){
					$upd_data = array(
						'store_id' => $store_id,
						'cat' => $cat
					);
					
					if($this->m_services->update_service_cat($cat_id, $upd_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
					}
				} else {
					$insert_data = array(
						'store_id' => $store_id,
						'cat' => $cat
					);
					
					if($this->m_services->reg_service_cat($insert_data) > 0){
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
		
		
		$data['title'] = 'Manage Service | '.app_name;
		$data['page_active'] = 'service';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('services/add_cat', $data);
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
			$gq = $this->m_services->query_single_service($get_id, $get_c_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_cat_id'] = $item->cat_id;
				$data['e_name'] = $item->name;
				$data['e_price'] = $item->price;
				$data['e_details'] = $item->details;
				$data['e_status'] = $item->status;
				$data['e_img_id'] = $item->img_id;	
			}
		}
		
		//check for posting
		if($_POST){
			$service_id = $_POST['service_id'];	
			$cat_id = $_POST['s_cat_id'];	
			$name = $_POST['s_name'];	
			$price = $_POST['s_price'];	
			$details = $_POST['s_details'];
			if($_POST['logo']!=''){$logo = $_POST['logo'];}else{$logo = 0;}		
			
			//check image upload
			if(isset($_FILES['pics']['name'])){
				$log_user_id = $this->session->userdata('org_id');
				$stamp = time();
				
				$path = 'img/pictures/'.$log_user_id;
				 
				if (!is_dir($path))
					mkdir($path, 0755);
	
				$pathMain = './img/pictures/'.$log_user_id;
				if (!is_dir($pathMain))
					mkdir($pathMain, 0755);
	
				$result = $this->do_upload("pics", $pathMain);
	
				if (!$result['status']){
					$data['err_msg'] ='<div class="alert alert-info"><h5>Can not upload photo, try another</h5></div>';
				} else {
					$save_path = $path . '/' . $result['upload_data']['file_name'];
					
					//if size not up to 400px above
					if($result['image_width'] >= 400){
						if($result['image_width'] >= 400 || $result['image_height'] >= 400) {
							if($this->resize_image($pathMain . '/' . $result['upload_data']['file_name'], $stamp .'-400.gif','400','400', $result['image_width'], $result['image_height'])){
								$resize400 = $pathMain . '/' . $stamp.'-400.gif';
								$resize400_dest = $resize400;
								
								if($this->crop_image($resize400, $resize400_dest,'400','220')){
									$save_path400 = $path . '/' . $stamp .'-400.gif';
								}
							}
						}
							
						if($result['image_width'] >= 200 || $result['image_height'] >= 200){
							if($this->resize_image($pathMain . '/' . $result['upload_data']['file_name'], $stamp .'-150.gif','200','200', $result['image_width'], $result['image_height'])){
								$resize100 = $pathMain . '/' . $stamp.'-150.gif';
								$resize100_dest = $resize100;	
								
								if($this->crop_image($resize100, $resize100_dest,'150','150')){
									$save_path100 = $path . '/' . $stamp .'-150.gif';
								}
							}
						}
						
						//save picture in system
						$insert_data = array(
							'user_id' => $log_user_id,
							'pics' => $save_path,
							'pics_small' => $save_path400,
							'pics_square' => $save_path100
						);
						$insert_id = $this->users->reg_img($insert_data);
						//save id in logo
						$logo = $insert_id;
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>Must be at least 400px in Width</h5></div>';
					}
				}
			}
			
			//check update post
			if($service_id != ''){
				$upd_data = array(
					'cat_id' => $cat_id,
					'name' => $name,
					'price' => $price,
					'details' => $details,
					'img_id' => $logo
				);
				
				if($this->m_services->update_service($service_id, $cat_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data = array(
					'cat_id' => $cat_id,
					'name' => $name,
					'price' => $price,
					'details' => $details,
					'status' => 1,
					'img_id' => $logo
				);
				
				$now = date("Y-m-d H:i:s");
				$new_id = $this->m_services->reg_service($insert_data);
				if($new_id > 0){
					//get store id
					$sc = $this->m_services->query_service_cat_id($cat_id);
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
					
					$content = 'New Service added to '.$store_name;
					
					//try register activity
					$reg_activity = array(
						'type' => 'new_service',
						'store_id' => $store_id,
						'p_id' => $new_id,
						's_id' => $new_id,
						'content' => $content,
						'reg_date' => $now
					);
					
					$this->users->reg_activity($reg_activity);
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('services/'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time. Please try later</h5></div>';
				}	
			}
		}
		
		
		$data['title'] = 'Manage Service | '.app_name;
		$data['page_active'] = 'service';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('services/add', $data);
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
			redirect(base_url('services/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_s_id = $_POST['del_s_id'];
			if($del_id && $del_s_id){
				$gq = $this->m_services->delete_service_cat($del_id, $del_s_id);
				redirect(base_url('services/'), 'refresh');
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
			redirect(base_url('services/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_c_id = $_POST['del_c_id'];
			if($del_id && $del_c_id){
				$gq = $this->m_services->delete_service($del_id, $del_c_id);
				redirect(base_url('services/'), 'refresh');
			}
		}
	}
}