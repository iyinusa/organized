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
			
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$data['err_msg'] = '';
		$log_user_industry_id = $this->session->userdata('org_user_industry_id');
		$log_user_role = strtolower($this->session->userdata('org_user_role'));
		
		if($log_user_role == 'administrator'){
			$allblogs = $this->m_blogs->query_all_blog();
		} else {
			$allblogs = $this->m_blogs->query_blog_industry($log_user_industry_id);
		}
		
		if(!empty($allblogs)){
			$data['allblogs'] = $allblogs;
		}
		
		$data['title'] = 'Manage Blogs | '.app_name;
		$data['page_active'] = 'blogs';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('blogs/blogs', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function view($slug){
		//check for login
		if($this->session->userdata('logged_in') == TRUE){
			
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		$log_user_role = strtolower($this->session->userdata('org_user_role'));
		$blog_title = '';
		
		if(!$slug || $slug == ''){
			//redirect to all blogs
			redirect(base_url('blogs'), 'refresh');
		} else {
			$get_blog = $this->m_blogs->query_blog_slug($slug);
			if(!empty($get_blog)){
				$new_view = 0;
				$blog_id = 0;
				foreach($get_blog as $blog){
					if($blog->status == 1 || $blog->user_id == $log_user_id){
						$blog_id = $blog->id;
						$blog_title = ucwords($blog->title);
						
						$data['blog_id'] = $blog->id;
						$data['blog_user_id'] = $blog->user_id;
						$data['blog_title'] = $blog->title;
						$data['blog_slug'] = $blog->slug;
						$data['blog_details'] = $blog->details;
						$data['blog_view'] = $blog->view;
						
						$reg_date = timespan(strtotime($blog->reg_date), time());
						$reg_date = explode(', ',$reg_date);
						$reg_date = $reg_date[0];
						
						$upd_date = timespan(strtotime($blog->upd_date), time());
						$upd_date = explode(', ',$upd_date);
						$upd_date = $upd_date[0];
						
						$data['blog_reg_date'] = $reg_date;
						$data['blog_upd_date'] = $upd_date;
						
						//increament view by 1
						$new_view = $blog->view + 1;
						
						//get blog image
						$blog_img = $blog->img_id;
						if($blog_img==0){
							$data['blog_img'] = '';
						} else {
							$gti = $this->users->query_img_id($blog_img);
							foreach($gti as $gtimg){
								$data['blog_img'] = $gtimg->pics;	
							}
						}
						
						//get industry name
						$get_indus = $this->m_blogs->query_blog_cat($blog->cat_id);
						if(!empty($get_indus)){
							foreach($get_indus as $indus){
								$data['blog_industry'] = $indus->industry;
							}
						} else {$data['blog_industry'] = '';}
						
						//query user data
						$qu = $this->users->query_single_user_id($blog->user_id);
						if(!empty($qu)){
							foreach($qu as $u_user){
								$data['user_firstname'] = $u_user->firstname;
								$data['user_lastname'] = $u_user->lastname;
							}
						}
						
						//query comments
						$comment_count = 0;
						$comm_list = '';
						$get_comment = $this->m_blogs->query_blog_comment($blog->id);
						if(!empty($get_comment)){
							foreach($get_comment as $comment){
								$comment_count += 1;
								$c_user_id = $comment->user_id;
								$c_comment = $comment->comment;
								$c_status = $comment->status;
								$c_reg_date = $comment->reg_date;
								
								$c_reg_date = timespan(strtotime($c_reg_date), time());
								$c_reg_date = explode(', ',$c_reg_date);
								$c_reg_date = $c_reg_date[0];
								
								//query comment user data
								$cqu = $this->users->query_single_user_id($c_user_id);
								if(!empty($cqu)){
									foreach($cqu as $cu_user){
										$cu_user_firstname = $cu_user->firstname;
										$cu_user_lastname = $cu_user->lastname;
										$cu_user_pics_small = $cu_user->pics_small;
										if($cu_user_pics_small=='' || file_exists(FCPATH.$cu_user_pics_small)==FALSE){$cu_user_pics_small='img/icon57.png';}
									}
								}
								
								//put delete button
								if($log_user_role == 'administrator' || $c_user_id == $log_user_id){
									$del_btn = '<a href="'.base_url('blogs/remove?comment='.$comment->id.'&slug='.$blog->slug).'" class="btn btn-primary"><i class="gi gi-bin"></i> Remove</a>';
								} else {
									$del_btn = '';
								}
								
								if($c_status == 1 || $c_user_id == $log_user_id){
									$comm_list .= '
										<div class="col-lg-12" style="padding:10px; border-bottom:1px solid #eee; background-color:#eee; margin-bottom:15px;">
											<span class="pull-right">'.$del_btn.'</span>
											<img alt="" src="'.base_url($cu_user_pics_small).'" style="float:left; margin-right:10px; max-width:60px;" class="img-circle" />
											<h4 class="text-muted">'.ucwords($cu_user_firstname).' '.ucwords($cu_user_lastname).'</h4>
											<div style="border-bottom:1px solid #eee; padding-bottom:10px;">
												<small class="text-muted">
													'.$c_reg_date.' ago
												</small>
											</div>
											<h4>'.$c_comment.'</h4>
										</div>
									';
								}
							}
						}
						
						$data['comment_count'] = $comment_count;
						$data['comments'] = $comm_list;
					}
				}
				
				//update views
				$upd_data = array(
					'view' => $new_view
				);
				
				if($new_view!=0){
					if($this->m_blogs->update_blog($blog_id, $upd_data) > 0){}
				}
			}
		}
		
		if($blog_title == ''){$blog_title = 'Blogs';}
		
		$data['title'] = $blog_title.' | '.app_name;
		$data['page_active'] = 'blogs';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('blogs/view', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function comment(){
		if($_POST){
			$date = date('d M Y');
			$blog_slug = $_POST['blog_slug'];
			$blog_id = $_POST['blog_id'];
			$user_id = $_POST['user_id'];
			$msg = $_POST['com_msg'];
			
			if($blog_id != '' && $user_id != '' && $msg != ''){
				$insert_data = array(
					'blog_id' => $blog_id,
					'user_id' => $user_id,
					'comment' => $msg,
					'status' => 1,
					'reg_date' => $date
				);
				
				$new_id = $this->m_blogs->reg_insert_comment($insert_data);
				if($new_id > 0){}
			}
		}
		
		redirect(base_url('blog/'.$blog_slug), 'refresh');
	}
	
	public function remove(){
		//check for edit role
		if($this->session->userdata('logged_in') == TRUE){
			
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		$log_user_role = strtolower($this->session->userdata('org_user_role'));
		
		$get_slug = $this->input->get('slug');
		$get_id = $this->input->get('comment');
		if($get_id != ''){
			//first check if its users or admin
			$chk_com = $this->m_blogs->query_blog_comment_id($get_id);
			if(!empty($chk_com)){
				foreach($chk_com as $com){
					if($com->user_id == $log_user_id || $log_user_role == 'administrator'){
						//now delete comment
						$this->m_blogs->delete_blog_comment($get_id);
					}
				}
			}
		}
		
		redirect(base_url('blog/'.$get_slug), 'refresh');
	}
}