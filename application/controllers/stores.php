<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stores extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
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
		if($get_id != ''){
			$data['rec_del'] = $get_id;
		} else {
			$data['rec_del'] = '';	
		}
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Stores | '.app_name;
		$data['page_active'] = 'store';
		
		$this->load->view('designs/header', $data);
		$this->load->view('stores/stores', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function add(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_role = $this->session->userdata('org_user_role');
			$log_user_id = $this->session->userdata('org_id');
			$gsubs = $this->crud->subs($log_user_id, 'outlet');
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
		$get_id = $this->input->get('e');
		if($get_id != ''){
			$gq = $this->m_stores->query_single_store_id($get_id, $log_user_id);
			foreach($gq as $item){
				$data['e_id'] = $item->id;
				$data['e_store'] = $item->store;
				$data['e_store_no'] = $item->store_no;
				$data['e_tin'] = $item->tin;
				$data['e_tin_office'] = $item->tin_office;
				$data['e_address'] = $item->address;
				$data['e_city'] = $item->city;
				$data['e_state'] = $item->state;
				$data['e_country'] = $item->country;
				$data['e_img_id'] = $item->img_id;	
			}
		}
		
		//check for posting
		if($_POST){
			$store_id = $_POST['store_id'];	
			$store = $_POST['store_name'];
			$store_no = $_POST['store_no'];
			$tin = $_POST['tin'];
			$tin_office = $_POST['tin_office'];	
			$address = $_POST['address'];	
			$city = $_POST['city'];	
			$state = $_POST['state'];	
			$country = $_POST['country'];
			if($_POST['logo']!=''){$logo = $_POST['logo'];}else{$logo = 0;}	
			
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
					$data['err_msg'] ='<div class="alert alert-info"><h5>Can not upload Logo, try another</h5></div>';
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
			if($store_id != ''){
				$upd_data = array(
					'store' => $store,
					'store_no' => $store_no,
					'tin' => $tin,
					'tin_office' => $tin_office,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'country' => $country,
					'img_id' => $logo
				);
				
				if($this->m_stores->update_store($store_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data = array(
					'user_id' => $log_user_id,
					'store' => $store,
					'store_no' => $store_no,
					'tin' => $tin,
					'tin_office' => $tin_office,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'country' => $country,
					'img_id' => $logo
				);
				
				$int_store_id = $this->m_stores->reg_insert($insert_data);
				if($int_store_id > 0){
					///////now assign owner as registered staff to store
					$insert_staff_data = array(
						'store_id' => $int_store_id,
						'owner_id' => $log_user_id,
						'user_id' => $log_user_id,
						'role' => 'Admin',
						'emp_date' => '',
						'emp_id' => '',
						'status' => 1,
						'reg_date' => $now
					);
					
					if($this->m_stores->reg_store_staff($insert_staff_data) > 0){
						$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
						redirect(base_url('stores/'), 'refresh');
					} else {
						$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time, please try later</h5></div>';
					}
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>No Changes Made</h5></div>';
				}	
			}
		}
		
		
		$data['title'] = 'Manage Store | '.app_name;
		$data['page_active'] = 'store';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('stores/add', $data);
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
			redirect(base_url('stores/'), 'refresh');
		} else {
			$log_user_id = $this->session->userdata('org_id');
			$del_id = $_POST['del_id'];
			if($del_id){
				$gq = $this->m_stores->delete_store($del_id, $log_user_id);
				redirect(base_url('stores/'), 'refresh');
			}
		}
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