<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$m_obj =& get_instance();
	$m_obj->load->model('users');
	$m_obj->load->model('m_stores');
	$m_obj->load->model('m_customers');
	$m_obj->load->model('m_products');
	$m_obj->load->model('m_services');
	$m_obj->load->model('m_invoices');
	$m_obj->load->model('m_expenses');
	$m_obj->load->model('m_blogs');
	$m_obj->load->model('m_phones');
	
	$org_setting_page = base_url().'settings/';
	$org_notification_page = base_url().'notifications/';
	
	$log_user = $this->session->userdata('logged_in');
	
	$org_view_nicename = $this->session->userdata('org_view_nicename');
	$org_view_id = $this->session->userdata('org_view_id');
	
	$org_nairatodollar = 199;
	
	if($log_user == FALSE) {
		$log_user_id = '';
		$log_user_title = '';
		$log_user_firstname = '';
		$log_user_lastname = '';
		$log_user_email = '';
		$log_user_dob = '';
		$log_user_sex = '';
		$log_user_address = '';
		$log_user_city = '';
		$log_user_state = '';
		$log_user_country = '';
		$log_user_phone = '';
		$log_user_pics = '';
		$log_user_pics_small = '';
		$log_user_role = '';
		$log_user_reg_date = '';
		$log_user_pics_lastlog = '';
		$log_user_pics_status = '';
		$log_user_theme = '';
		$log_user_industry_id = '';
	} else {
		$log_user_id = $this->session->userdata('org_id');
		$log_user_title = ucwords($this->session->userdata('org_user_title'));
		$log_user_firstname = ucwords($this->session->userdata('org_user_firstname'));
		$log_user_lastname = ucwords($this->session->userdata('org_user_lastname'));
		$log_user_email = $this->session->userdata('org_user_email');
		$log_user_dob = $this->session->userdata('org_user_dob');
		$log_user_sex = $this->session->userdata('org_user_sex');
		$log_user_address = $this->session->userdata('org_user_address');
		$log_user_city = $this->session->userdata('org_user_city');
		$log_user_state = $this->session->userdata('org_user_state');
		$log_user_country = $this->session->userdata('org_user_country');
		$log_user_phone = $this->session->userdata('org_user_phone');
		$log_user_pics = $this->session->userdata('org_user_pics');
		$log_user_pics_small = $this->session->userdata('org_user_pics_small');
		$log_user_role = strtolower($this->session->userdata('org_user_role'));
		$log_user_reg_date = $this->session->userdata('org_user_reg_date');
		$log_user_pics_lastlog = $this->session->userdata('org_user_lastlog');
		$log_user_pics_status = $this->session->userdata('org_user_status');
		$log_user_theme = strtolower($this->session->userdata('org_user_theme'));
		$log_user_industry_id = $this->session->userdata('org_user_industry_id');
		
		if($log_user_pics=='' || file_exists(FCPATH.$log_user_pics)==FALSE){$log_user_pics='img/icon120.png';}
		if($log_user_pics_small=='' || file_exists(FCPATH.$log_user_pics_small)==FALSE){$log_user_pics_small='img/icon72.png';}
		
		$cur_currency = '&#8358;';
		if($log_user_country == 'Nigeria'){
			$cur_currency = '&#8358;';	
		} else if($log_user_country == 'Ghana'){
			$cur_currency = 'Gh₵';
		}
		
		//save records in session
		$curr_update = array('cur_currency' => $cur_currency);
		$this->session->set_userdata($curr_update);
	}