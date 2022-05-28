<?php
    $n_obj =& get_instance();
	$n_obj->load->model('users');
	
	$rn_list = '';
	$rn_count = 0;
	$r_not = $n_obj->users->query_notify_fan($log_user_id);
	$rnew_count = count($n_obj->users->query_notify_fan_unread($log_user_id));
	if(!empty($r_not)){
		foreach($r_not as $rnot){
			if($rn_count<5){
				$rn_id = $rnot->id;
				$rn_type = $rnot->type;
				$rn_fan_id = $rnot->fan_id;
				$rn_p_id = $rnot->p_id;
				$rn_s_id = $rnot->s_id;
				$rn_content = $rnot->content;
				$rn_status = $rnot->status;
				$rn_reg_date = $rnot->reg_date;
				
				$rn_reg_date_ago = timespan(strtotime($rn_reg_date), time());
				$rn_reg_date_ago = explode(', ',$rn_reg_date_ago);
				$rn_reg_date_ago = $rn_reg_date_ago[0];
				
				//get fan details
				$rn_gf = $n_obj->users->query_single_user_id($rn_fan_id);
				foreach($rn_gf as $rnf){
					$rn_fan_nicename = $rnf->user_nicename;
					$rn_fan_display_name = ucwords($rnf->display_name);
					$rn_fan_pics_small = $rnf->pics_small;
					if($rn_fan_pics_small=='' || file_exists(FCPATH.$rn_fan_pics_small)==FALSE){$rn_fan_pics_small='img/avatar.jpg';}		
				}
				
				//check type
				if($rn_type=='moot' || $rn_type=='moot reply'){
					if($rn_type=='moot'){
						$rn_sticker = '<span class="label label-success pull-right"><i class="ti-thought"></i> New Moot</span>';
						$rn_content = character_limiter($rn_content,45);
						$rn_link = base_url('notifications/?n='.$rn_id.'&t='.$rn_type.'&l='.$rn_p_id);
					} else {
						$rn_sticker = '<span class="label label-default pull-right"><i class="ti-thought"></i> Replied Moot</span>';
						$rn_content = character_limiter($rn_content,45);
						$rn_link = base_url('notifications/?n='.$rn_id.'&t='.$rn_type.'&l='.$rn_p_id.'-'.$rn_s_id);
					}
				} else if($rn_type=='join club'){
					$rn_sticker = '<span class="label label-primary pull-right"><i class="fa fa-trophy"></i> Joined Club</span>';
					$rn_content = character_limiter($rn_content,45);
					$rn_link = base_url('notifications/?n='.$rn_id.'&t='.$rn_type.'&l='.$rn_p_id);
				} else if($rn_type=='leave club'){
					$rn_sticker = '<span class="label label-danger pull-right"><i class="fa fa-trophy"></i> Decamped</span>';
					$rn_content = character_limiter($rn_content,45);
					$rn_link = base_url('notifications/?n='.$rn_id.'&t='.$rn_type.'&l='.$rn_p_id);
				}
				
				if($rn_status==0){
					$bg_shade = 'bg-primary';
				} else {
					$bg_shade = '';
				}
				
				$rn_list .= '
					<li class="list-group-item '.$bg_shade.'">
						<a href="'.$rn_link.'">
							<span class="pull-left mt5 mr15">
								<img src="'.base_url($rn_fan_pics_small).'" class="avatar avatar-sm img-circle" alt="">
							</span>
							<div class="m-body">
								<div class="">
									<small><b>'.$rn_fan_display_name.'</b></small>
									'.$rn_sticker.'
								</div>
								<span>'.$rn_content.'</span>
								<span class="time small text-muted">'.$rn_reg_date_ago.' ago</span>
							</div>
						</a>
					</li>
				';
				
				$rn_count += 1;
			}
		}
	}
?>