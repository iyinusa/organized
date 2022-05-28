<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
		$this->load->helper('text'); //for content limiter
		$this->load->library('form_validation'); //load form validate rule
		$this->load->library('image_lib'); //load image library
		
		//$config['image_library'] = 'gd2';
//		$config['source_image']	= '/path/to/image/mypic.jpg';
//		$config['create_thumb'] = TRUE;
//		$config['maintain_ratio'] = TRUE;
//		$config['width']	= 320;
//		$config['height']	= 320;
		//$this->image_lib->initialize($config); //load image library
    }
	
	public function index()
	{
		if($this->session->userdata('logged_in')==FALSE){redirect(base_url().'auth','refresh');}
		
		$this->account();
	}
	
	public function photo(){
		if($this->session->userdata('logged_in')==FALSE){redirect(base_url().'auth','refresh');}
		$log_user_id = $this->session->userdata('org_id');
		$data['err_msg'] = '';
		$save_path = '';
		$save_path100 = '';
		$save_path400 = '';
		
		//check image upload
		if(isset($_FILES['pics']['name'])){
			$stamp = time();
			
			$path = 'img/pictures/'.$log_user_id;
			 
			if (!is_dir($path))
				mkdir($path, 0755);

			$pathMain = './img/pictures/'.$log_user_id;
			if (!is_dir($pathMain))
				mkdir($pathMain, 0755);

			$result = $this->do_upload("pics", $pathMain);

			if (!$result['status']){
				$data['err_msg'] ='<div class="alert alert-info"><h5>Can not upload Picture, try another</h5></div>';
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
		
		//prepare insert record
		if($save_path=='' && $save_path400=='' && $save_path100==''){
			$data['err_msg'] = '';
		} else {
			//check for update
			$upd_data = array(
				'pics' => $save_path400,
				'pics_small' => $save_path100
			);
			
			if($this->users->update_user($log_user_id, $upd_data) > 0){
				//add data to session
				$s_data = array (
					'org_user_pics' => $save_path400,
					'org_user_pics_small' => $save_path100
				);
					
				$check = $this->session->set_userdata($s_data);
				
				$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
			} else {
				$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
			}
		}
		
		$data['title'] = 'Profile Photo | '.app_name;
		$data['page_active'] = 'setting';
		
		$this->load->view('designs/header', $data);
		$this->load->view('settings/photo', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function account(){
		if($this->session->userdata('logged_in')==FALSE){redirect(base_url().'auth','refresh');}
		
		//account setting page
		$log_user_id = $this->session->userdata('org_id');
		$log_user_email = $this->session->userdata('org_user_email');
		
		//set form input rules 
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('firstname','First Name','required|xss_clean');
		$this->form_validation->set_rules('lastname','Last Name','required|xss_clean');
		$this->form_validation->set_rules('sex','Sex','required|xss_clean');
		$this->form_validation->set_rules('country','Country','required|xss_clean');
		
		//error delimeter
		$this->form_validation->set_error_delimiters('<h5 id="pass-info" class="alert alert-info">', '</h5>');
		
		if ($this->form_validation->run() == FALSE){
			$data['err_msg'] = '';
		} else {
			//check if ready for post
			if($_POST) {
				$email = $_POST['email'];
				$stitle = $_POST['stitle'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$dob = $_POST['dob'];
				$sex = $_POST['sex'];
				$city = $_POST['city'];
				$state = $_POST['state'];
				$country = $_POST['country'];
				$phone = $_POST['phone'];
				$address = $_POST['address'];
				
				if($log_user_email != $email) {
					if(($this->users->check_by_email($email) > 0)) {
						$data['err_msg'] = '<h5 class="alert alert-info">Email address already registered, try another</h5>';
					}
				} else {
					$update_data = array(
						'title' => $stitle,
						'firstname' => $firstname,
						'lastname' => $lastname,
						'email' => $email,
						'dob' => $dob,
						'sex' => $sex,
						'city' => $city,
						'state' => $state,
						'country' => $country,
						'phone' => $phone,
						'address' => $address
					);
					
					if($this->users->update_user($log_user_id, $update_data) > 0){
						$data['err_msg'] = '<h5 class="alert alert-success">Account update successful</h5>';
						
						//update session records
						$session_data = array (
							'org_user_title' => $stitle,
							'org_user_firstname' => $firstname,
							'org_user_lastname' => $lastname,
							'org_user_email' => $email,
							'org_user_dob' => $dob,
							'org_user_sex' => $sex,
							'org_user_city' => $city,
							'org_user_state' => $state,
							'org_user_phone' => $phone,
							'org_user_country' => $country,
							'org_user_address' => $address
						);
						$this->session->set_userdata($session_data);
					} else {
						$data['err_msg'] = '<h5 class="alert alert-info">No changes made</h5>';	
					}
				}
			}
		}
		
		$data['title'] = 'Update Profile | '.app_name;
		$data['page_active'] = 'setting';
		
		$this->load->view('designs/header', $data);
		$this->load->view('settings/account', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function password(){
		if($this->session->userdata('logged_in')==FALSE){redirect(base_url().'auth','refresh');}
		$log_user_id = $this->session->userdata('org_id');
		$log_user_email = $this->session->userdata('org_user_email');
		
		//set form input rules 
		$this->form_validation->set_rules('old','Old password','trim|required|min_length[4]|max_length[32]|xss_clean|md5');
		$this->form_validation->set_rules('new','New password','trim|required|min_length[4]|max_length[32]|xss_clean|md5');
		$this->form_validation->set_rules('confirm','Confirm password','trim|required|matches[new]|xss_clean');
		
		//error delimeter
		$this->form_validation->set_error_delimiters('<h5 id="pass-info" class="alert alert-info">', '</h5>');
		
		if ($this->form_validation->run() == FALSE){
			$data['err_msg'] = '';
		} else {
			//check if ready for post
			if($_POST) {
				$old = $_POST['old'];
				$new = $_POST['new'];
				$confirm = $_POST['confirm'];
				
				if($this->users->check_user($log_user_email, $old) <= 0) {
					$data['err_msg'] = '<h5 class="alert alert-danger">Password not associated to your account</h5>';		
				} else {
					$update_data = array(
						'password' => $new
					);
					
					if($this->users->update_user($log_user_id, $update_data) > 0){
						$data['err_msg'] = '<h5 class="alert alert-success">Password changed successfully</h5>';
					} else {
						$data['err_msg'] = '<h5 class="alert alert-info">No changes made</h5>';
					}
				}
			}
		}
		
		$data['title'] = 'Change Password | '.app_name;
		$data['page_active'] = 'setting';
		
		$this->load->view('designs/header', $data);
		$this->load->view('settings/password', $data);
		$this->load->view('designs/footer', $data);
	}
	
	public function privacy($page='privacy'){
		if($this->session->userdata('logged_in')==FALSE){redirect(base_url().'auth','refresh');}
		
		//privacy setting page
		$data['title'] = 'Privacy Settings | '.app_name;
		$data['page_active'] = 'setting';
		
		$data['err_msg'] = '';
		
		$this->load->view('designs/header', $data);
		$this->load->view('settings/'.$page, $data);
		$this->load->view('designs/footer', $data);
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