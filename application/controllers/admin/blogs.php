<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blogs extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_blogs'); //load MODEL
		$this->load->library('form_validation');
		$this->load->helper('url');
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
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$data['err_msg'] = '';
		
		$allblogs = $this->m_blogs->query_all_blog();
		if(!empty($allblogs)){
			$data['allblogs'] = $allblogs;
		}
		
		$data['title'] = 'Manage Blogs | '.app_name;
		$data['page_active'] = 'blogs';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/blogs', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function manage(){
		if($this->session->userdata('logged_in') == TRUE){
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		//check for edit
		$get_blog_id = $this->input->get('blog');
		if($get_blog_id != ''){
			$data['get_blog_id'] = $get_blog_id;
			$getpre = $this->m_blogs->query_blog_id($get_blog_id);
			if(!empty($getpre)){
				foreach($getpre as $pre){
					$data['e_id'] = $pre->id;
					$data['e_cat_id'] = $pre->cat_id;
					$data['e_user_id'] = $pre->user_id;
					$data['e_title'] = $pre->title;
					$data['e_slug'] = $pre->slug;
					$data['e_details'] = $pre->details;
					$data['e_view'] = $pre->view;
					$data['e_img_id'] = $pre->img_id;
					
					if($pre->status == 0){
						$data['e_status'] = 'Not Published';
					} else {
						$data['e_status'] = 'Published';	
					}
				}
			}	
		}
		
		if($_POST){
			$blog_id = $_POST['blog_id'];
			$cat_id = $_POST['cat_id'];
			$title = $_POST['title'];
			$details = $_POST['details'];
			
			$log_user_id = $this->session->userdata('org_id');
			
			//===get nicename and convert to seo friendly====
			$nicename = strtolower($title);
			$nicename = preg_replace("/[^a-z0-9_\s-]/", "", $nicename);
			$nicename = preg_replace("/[\s-]+/", " ", $nicename);
			$nicename = preg_replace("/[\s_]/", "-", $nicename);
			//================================================
			
			if($_POST['status']!=''){$status = $_POST['status'];}else{$status = 0;}	
			if($_POST['logo']!=''){$logo = $_POST['logo'];}else{$logo = 0;}	
			
			$date = date('d M Y');	
			
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
			if($blog_id != ''){
				$upd_data = array(
					'cat_id' => $cat_id,
					'title' => $title,
					'slug' => $nicename,
					'details' => $details,
					'status' => $status,
					'img_id' => $logo,
					'upd_date' => $date
				);
				
				if($this->m_blogs->update_blog($blog_id, $upd_data) > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
				} else {
					$data['err_msg'] = '<div class="alert alert-info"><h5>No Changes Made</h5></div>';
				}
			} else {
				$insert_data = array(
					'cat_id' => $cat_id,
					'user_id' => $log_user_id,
					'title' => $title,
					'slug' => $nicename,
					'details' => $details,
					'status' => $status,
					'view' => 0,
					'img_id' => $logo,
					'reg_date' => $date,
					'upd_date' => $date
				);
				
				$new_id = $this->m_blogs->reg_insert($insert_data);
				if($new_id > 0){
					$data['err_msg'] = '<div class="alert alert-success"><h5>Successfully</h5></div>';
					redirect(base_url('admin/blogs/manage'), 'refresh');
				} else {
					$data['err_msg'] = '<div class="alert alert-danger"><h5>There is problem this time. Please try later</h5></div>';
				}	
			}
		}
		
		$allblogs = $this->m_blogs->query_all_blog();
		if(!empty($allblogs)){
			$data['allblogs'] = $allblogs;
		}
		
		$data['title'] = 'Manage Blogs | '.app_name;
		$data['page_active'] = 'blogs';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/blogs', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function remove(){
		//check for edit role
		if($this->session->userdata('logged_in') == TRUE){
			$log_user_role = strtolower($this->session->userdata('org_user_role'));
			if($log_user_role != 'administrator' && $log_user_role != 'support'){
				redirect(base_url('dashboard'), 'refresh');
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$get_blog_id = $this->input->get('blog');
		if($get_blog_id != ''){
			$data['rec_del'] = $get_blog_id;
		} else {
			$data['rec_del'] = '';
		}
		
		if($_POST){
			if(isset($_POST['cancel'])){
				redirect(base_url('admin/blogs/manage'), 'refresh');
			} else {
				$del_id = $_POST['del_id'];
				
				if($del_id){
					$gq = $this->m_blogs->delete_blog($del_id);
					redirect(base_url('admin/blogs/manage'), 'refresh');
				}
			}
		}
		
		$allblogs = $this->m_blogs->query_all_blog();
		if(!empty($allblogs)){
			$data['allblogs'] = $allblogs;
		}
		
		$data['title'] = 'Manage Blogs | '.app_name;
		$data['page_active'] = 'blogs';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/blogs', $data);
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