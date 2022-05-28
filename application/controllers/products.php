<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_products'); //load MODEL
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
			$gsubs = $this->crud->subs($log_user_id, 'product');
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
		
		$data['title'] = 'Products | '.app_name;
		$data['page_active'] = 'product';
		
		$this->load->view('designs/header', $data);
		$this->load->view('products/products', $data);
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
			$gq = $this->m_products->query_single_product_cat($get_id, $get_s_id);
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
				redirect(base_url('products/add_cat'), 'refresh');
			} else if(isset($_POST['delete'])) {
				$del_id = $_POST['del_id'];
				$del_s_id = $_POST['del_s_id'];
				if($del_id && $del_s_id){
					$gq = $this->m_products->delete_product_cat($del_id, $del_s_id);
					redirect(base_url('products/add_cat'), 'refresh');
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
					
					if($this->m_products->update_product_cat($cat_id, $upd_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					} else {
						$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
					}
				} else {
					$insert_data = array(
						'store_id' => $store_id,
						'cat' => $cat
					);
					
					if($this->m_products->reg_product_cat($insert_data) > 0){
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
		
		
		$data['title'] = 'Manage Product | '.app_name;
		$data['page_active'] = 'product';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('products/add_cat', $data);
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
			$gq = $this->m_products->query_single_product($get_id, $get_c_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_cat_id'] = $item->cat_id;
				$data['e_name'] = $item->name;
				$data['e_price'] = $item->price;
				$data['e_qty'] = $item->qty;
				$data['e_rep'] = $item->rep;
				$data['e_details'] = $item->details;
				$data['e_status'] = $item->status;
				$data['e_img_id'] = $item->img_id;	
			}
		}
		
		//check for posting
		if($_POST){
			$pdt_id = $_POST['pdt_id'];	
			$cat_id = $_POST['pdt_cat_id'];	
			$name = $_POST['pdt_name'];	
			$price = $_POST['pdt_price'];	
			$qty = $_POST['pdt_qty'];
			$rep = $_POST['pdt_rep'];	
			$details = $_POST['pdt_details'];
			if($_POST['logo']!=''){$logo = $_POST['logo'];}else{$logo = 0;}	
			if(isset($_POST['new'])){$as_new=1;}else{$as_new=0;}
			$store_id = '';
			$store_name = '';
			$now = date("Y-m-d H:i:s");
			
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
			if($pdt_id != '' && $as_new == 0){
				$upd_data = array(
					'cat_id' => $cat_id,
					'name' => $name,
					'price' => $price,
					'qty' => $qty,
					'rep' => $rep,
					'details' => $details,
					'img_id' => $logo
				);
				
				if($this->m_products->update_product($pdt_id, $cat_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				//check if As New
				if($as_new == 1){
					$store_id = $_POST['store_id'];
					
					if($store_id == ''){
						$data['err_msg'] = '<div class="alert alert-info"><h5>Must Select Store To Replicate Product</h5></div>';
					} else {
						//get category name
						$get_catname = $this->m_products->query_product_cat_id($cat_id);
						if(!empty($get_catname)){
							foreach($get_catname as $catname){
								$cat_name = $catname->cat;
							}
						}
						
						//register new category
						$insert_data = array(
							'store_id' => $store_id,
							'cat' => $cat_name
						);
						
						$cat_id = $this->m_products->reg_product_cat($insert_data);
						if($cat_id > 0){}
					}
				}
				
				$insert_data = array(
					'cat_id' => $cat_id,
					'name' => $name,
					'price' => $price,
					'qty' => $qty,
					'rep' => $rep,
					'details' => $details,
					'status' => 1,
					'img_id' => $logo
				);
				
				$new_id = $this->m_products->reg_product($insert_data);
				
				if($new_id > 0){
					//add quatity to stock
					if($qty > 0){
						//get store id
						$pc = $this->m_products->query_product_cat_id($cat_id);
						if(!empty($pc)){
							foreach($pc as $c){
								$store_id = $c->store_id;
								$st_data = array(
									'store_id' => $c->store_id,
									'pdt_id' => $new_id,
									'distribute' => 0,
									'from_store_id' => 0,
									'qty' => $qty,
									'reg_date' => $now
								);
								if($this->m_products->reg_product_stock($st_data) > 0){}
							}
						}
					}
					
					//get store name
					$getstore = $this->m_stores->query_store_id($store_id);
					if(!empty($getstore)){
						foreach($getstore as $store){
							$store_name = $store->store;
						}
					}
					
					$content = 'New Product added to '.$store_name;
					
					//try register activity
					$reg_activity = array(
						'type' => 'new_product',
						'store_id' => $store_id,
						'p_id' => $new_id,
						's_id' => $new_id,
						'content' => $content,
						'reg_date' => $now
					);
					
					$this->users->reg_activity($reg_activity);
					
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('products/'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time. Please try later</h5></div>';
				}	
			}
		}
		
		
		$data['title'] = 'Manage Product | '.app_name;
		$data['page_active'] = 'product';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('products/add', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function stock(){
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
			$gq = $this->m_products->query_single_product_stock($get_id, $get_s_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store_id'] = $item->store_id;
				$data['e_pdt_id'] = $item->pdt_id;	
				$data['e_distribute'] = $item->distribute;	
				$data['e_from_store_id'] = $item->from_store_id;	
				$data['e_qty'] = $item->qty;	
			}
		}
		
		//check for delete
		$get_id = $this->input->get('r');
		$get_s_id = $this->input->get('s');
		if($get_id != '' && $get_s_id != ''){
			$data['rec_del'] = $get_id;
			$data['rec_del_s'] = $get_s_id;
			
			if(isset($_POST['cancel'])){
				redirect(base_url('products/stock'), 'refresh');
			} else if(isset($_POST['delete'])) {
				$del_id = $_POST['del_id'];
				$del_s_id = $_POST['del_s_id'];
				if($del_id && $del_s_id){
					$gq = $this->m_products->delete_product_stock($del_id, $del_s_id);
					redirect(base_url('products/stock'), 'refresh');
				}
			}
		} else {
			$data['rec_del'] = '';
			$data['rec_del_s'] = '';
		}
		
		//check for posting
		if($_POST){
			$stock_id = $_POST['stock_id'];
			$store_id = $_POST['store_id'];
			$to_store_id = $_POST['to_store_id'];
			$pdt_id = $_POST['pdt'];
			$pdt_id2 = $_POST['pdt2'];
			$qty = $_POST['qty'];	
			$dist = $_POST['dist'];	
			$now = date("Y-m-d H:i:s");
			
			if($dist==0 || $dist==''){$dist = 0; $to_store_id = 0;}
			
			if($dist==1 && $to_store_id==''){
				$data['err_msg'] = '<div class="alert alert-info"><h5>From and To Stores are required for distribution</h5></div>';
			} else if($stock_id != ''){ //check update post
				$upd_data = array(
					'store_id' => $store_id,
					'pdt_id' => $pdt_id,
					'distribute' => 0,
					'from_store_id' => 0,
					'qty' => $qty
				);
				
				if($this->m_products->update_product_stock($stock_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				//distribution logic
				if($dist==1){
					//get total quantity from product to remove
					$qty_count = 0;
					$getpdt = $this->m_products->query_product_stock_id($pdt_id, $store_id);
					if(!empty($getpdt)){
						foreach($getpdt as $gpdt){
							$qty_count += $gpdt->qty;	
						}
					}
					
					if($qty > $qty_count){
						$data['err_msg'] = '<div class="alert alert-info"><h5>Total Quantity in Product stock is low</h5></div>';
					} else {
						//move product quatity
						$move_data = array(
							'store_id' => $to_store_id,
							'pdt_id' => $pdt_id2,
							'distribute' => $dist,
							'from_store_id' => $store_id,
							'qty' => $qty,
							'reg_date' => $now
						);
						
						$move_id = $this->m_products->reg_product_stock($move_data);
						if($move_id > 0){
							//remove product
							$rem_data = array(
								'store_id' => $store_id,
								'pdt_id' => $pdt_id,
								'distribute' => $dist,
								'from_store_id' => $to_store_id,
								'qty' => $qty,
								'deduct' => 1,
								'reg_date' => $now
							);
							if($this->m_products->reg_product_stock($rem_data) > 0){}
							//get from store name
							$getstore = $this->m_stores->query_store_id($store_id);
							if(!empty($getstore)){
								foreach($getstore as $store){
									$store_name = $store->store;
								}
							}
							
							//get to store name
							$gettostore = $this->m_stores->query_store_id($to_store_id);
							if(!empty($gettostore)){
								foreach($gettostore as $tostore){
									$to_store_name = $tostore->store;
								}
							}
							
							$content = $qty.' Stock moved from '.$store_name.' to '.$to_store_name;
							
							//try register activity
							$reg_activity = array(
								'type' => 'move_stock',
								'store_id' => $store_id,
								'p_id' => $move_id,
								's_id' => $move_id,
								'content' => $content,
								'reg_date' => $now
							);
							
							$this->users->reg_activity($reg_activity);
							
							$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
						} else {
							$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
						}
					}
				} else {
					//logic without distribution
					$insert_data = array(
						'store_id' => $store_id,
						'pdt_id' => $pdt_id,
						'distribute' => $dist,
						'from_store_id' => $to_store_id,
						'qty' => $qty,
						'reg_date' => $now
					);
					
					$new_id = $this->m_products->reg_product_stock($insert_data);
					if($new_id > 0){
						//get store name
						$getstore = $this->m_stores->query_store_id($store_id);
						if(!empty($getstore)){
							foreach($getstore as $store){
								$store_name = $store->store;
							}
						}
						
						$content = $qty.' Stock added to '.$store_name;
						
						//try register activity
						$reg_activity = array(
							'type' => 'new_stock',
							'store_id' => $store_id,
							'p_id' => $new_id,
							's_id' => $new_id,
							'content' => $content,
							'reg_date' => $now
						);
						
						$this->users->reg_activity($reg_activity);
						
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
		
		
		$data['title'] = 'Manage Stock | '.app_name;
		$data['page_active'] = 'product';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('products/stock', $data);
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
			redirect(base_url('products/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_s_id = $_POST['del_s_id'];
			if($del_id && $del_s_id){
				$gq = $this->m_products->delete_product_cat($del_id, $del_s_id);
				redirect(base_url('products/'), 'refresh');
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
			redirect(base_url('products/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			$del_c_id = $_POST['del_c_id'];
			if($del_id && $del_c_id){
				$gq = $this->m_products->delete_product($del_id, $del_c_id);
				redirect(base_url('products/'), 'refresh');
			}
		}
	}
	
	public function get_product(){
		if($_POST){
			$store_id = $_POST['store_id'];
			$pdt_lists = '';
			
			//query product
			if($store_id){
				$allpdtcat = $this->m_products->query_product_cat($store_id);
				if(!empty($allpdtcat)){
					foreach($allpdtcat as $pdtcat){
						$pdt_list='';
						
						$allpdt = $this->m_products->query_product($pdtcat->id);
						if(!empty($allpdt)){
							foreach($allpdt as $pdt){
								$pdt_list .= '<option value="'.$pdt->id.'">'.$pdt->name.'</option>';	
							}
						}
						
						$pdt_lists .= '<optgroup label="'.$pdtcat->cat.'">'.$pdt_list.'</optgroup>';
					}
				}
			}
			
			echo $pdt_lists;
		} exit;
	}
	
	function do_upload($htmlFieldName, $path)
    {
        $config['file_name'] = time();
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|tif';
        $config['max_size'] = '10000';
        $config['max_width'] = '6000';
        $config['max_height'] = '6000';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        unset($config);
        if (!$this->upload->do_upload($htmlFieldName))
        {
            return array('error' => $this->upload->display_errors(), 'status' => 0);
        } else
        {
            $up_data = $this->upload->data();
			return array('status' => 1, 'upload_data' => $this->upload->data(), 'image_width' => $up_data['image_width'], 'image_height' => $up_data['image_height']);
        }
    }
	
	function resize_image($sourcePath, $desPath, $width = '500', $height = '500', $real_width, $real_height)
    {
        $this->image_lib->clear();
		$config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
		$config['width'] = $width;
        $config['height'] = $height;
		
		$dim = (intval($real_width) / intval($real_height)) - ($config['width'] / $config['height']);
		$config['master_dim'] = ($dim > 0)? "height" : "width";
		
		$this->image_lib->initialize($config);
 
        if ($this->image_lib->resize())
            return true;
        return false;
    }
	
	function crop_image($sourcePath, $desPath, $width = '320', $height = '320')
    {
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
		$config['x_axis'] = '20';
		$config['y_axis'] = '20';
        
		$this->image_lib->initialize($config);
 
        if ($this->image_lib->crop())
            return true;
        return false;
    }
}