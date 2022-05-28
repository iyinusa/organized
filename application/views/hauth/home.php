<?php
	// Output the profiles of each logged in service
	foreach ($providers as $provider => $d){
		if (!empty($d['user_profile'])){
			$profile[$provider] = (array)$d['user_profile'];
			
			if(!empty($d['user_profile']->identifier)){
				$social_user_identifier = $d['user_profile']->identifier;
			} else {
				$social_user_identifier = '';	
			}
			
			if(!empty($d['user_profile']->displayName)){
				$social_display_name = $d['user_profile']->displayName;	
			} else {
				$social_display_name = '';	
			}
			
			if(!empty($d['user_profile']->firstName)){
				$social_first_name = $d['user_profile']->firstName;	
			} else {
				$social_first_name = '';	
			}
			
			if(!empty($d['user_profile']->lastName)){
				$social_last_name = $d['user_profile']->lastName;	
			} else {
				$social_last_name = '';	
			}
			
			if( !empty($d['user_profile']->profileURL) ){
				$social_user_pics = $d['user_profile']->profileURL;
			} else {
				$social_user_pics = 'img/avatar.jpg';	
			}
			
			if(!empty($d['user_profile']->profileURL)){
				$social_user_url = $d['user_profile']->profileURL;
			} else {
				$social_user_url = '';
			}
			
			if(!empty($d['user_profile']->about)){
				$social_user_about_me = $d['user_profile']->about;
			} else {
				$social_user_about_me = '';	
			}
			
			if(!empty($d['user_profile']->gender)){
				$social_user_sex = $d['user_profile']->gender;
			} else {
				$social_user_sex = '';
			}
			
			if(!empty($d['user_profile']->email)){
				$social_user_email = $d['user_profile']->email;	
			} else {
				$social_user_email = '';
			}
			
			if(!empty($d['user_profile']->country)){
				$social_user_country = $d['user_profile']->country;	
			} else {
				$social_user_country = '';	
			}
			
			//add data to session
			////////////////////////////////////////////////////////////////////////////////////////////////
			$s_data = array (
				'social_user_identifier' => $social_user_identifier,
				'social_display_name' => $social_display_name,
				'social_first_name' => $social_first_name,
				'social_last_name' => $social_last_name,
				'social_user_pics' => $social_user_pics,
				'social_user_url' => $social_user_url,
				'social_user_about_me' => $social_user_about_me,
				'social_user_sex' => $social_user_sex,
				'social_user_email' => $social_user_email,
				'social_user_country' => $social_user_country,
				'social_log_in' => TRUE
			);
			
			$this->session->set_userdata($s_data);
			
			//redirect 
			redirect(base_url().'login/social', 'location');
		}
	}
	
	redirect('/', 'refresh');
?>

