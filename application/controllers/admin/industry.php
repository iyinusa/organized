<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Industry extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_customers'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
		$this->load->library('form_validation');
		$this->load->helper('url');
		
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
		
		//check for edit
		$get_id = $this->input->get('upd');
		if($get_id != ''){
			$getind = $this->users->query_user_industry($get_id);
			if(!empty($getind)){
				foreach($getind as $ind){
					$data['e_id'] = $ind->id;
					$data['e_industry'] = $ind->industry;	
				}
			}
		}
		
		if($_POST){
			$industry_id = $_POST['industry_id'];
			$industry = $_POST['industry'];
			
			//check if update
			if($industry_id){
				$update_data = array(
					'industry' => $industry
				);
				
				if($this->users->update_industry($industry_id, $update_data) > 0){
					$data['err_msg'] = '<h5 class="alert alert-success">Record Updated</h5>';	
				} else {
					$data['err_msg'] = '<h5 class="alert alert-info">No changes made</h5>';	
				}
			} else {
				//check if Industry already exist
				if($this->users->check_industry_name($industry) > 0){
					$data['err_msg'] = '<h5 class="alert alert-info">Already Exist In Database</h5>';
				} else {
					$in_data = array(
						'industry' => $industry
					);
					
					if($this->users->reg_industry($in_data) > 0){
						$data['err_msg'] = '<h5 class="alert alert-success">Record Added Successfully</h5>';	
					} else {
						$data['err_msg'] = '<h5 class="alert alert-danger">There is problem this time. Please try later</h5>';	
					}
				}
			}
		}
		
		$allindustry = $this->users->query_industry();
		if(!empty($allindustry)){
			$data['allindustry'] = $allindustry;
		}
		
		$data['title'] = 'Manage Industry | '.app_name;
		$data['page_active'] = 'industry';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/industry', $data);
	  	$this->load->view('designs/footer', $data);
	}
	
	public function remove(){
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
		
		//check for edit role
		$get_id = $this->input->get('id');
		if($get_id != ''){
			$data['rec_del'] = $get_id;
		} else {
			$data['rec_del'] = '';
		}
		
		if($_POST){
			if(isset($_POST['cancel'])){
				redirect(base_url('admin/industry'), 'refresh');
			} else {
				$del_id = $_POST['del_id'];
				
				if($del_id){
					$gq = $this->users->delete_industry($del_id);
					redirect(base_url('admin/industry'), 'refresh');
				}
			}
		}
		
		$allindustry = $this->users->query_industry();
		if(!empty($allindustry)){
			$data['allindustry'] = $allindustry;
		}
		
		$data['title'] = 'Manage Industry | '.app_name;
		$data['page_active'] = 'industry';

	  	$this->load->view('designs/header', $data);
	  	$this->load->view('admin/industry', $data);
	  	$this->load->view('designs/footer', $data);
	}
}