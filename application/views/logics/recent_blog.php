<?php
   	$rn_all_item = '';
	$rn_all_item_list = '';
	$rn_count = 0;
	
	if($log_user_role == 'administrator'){
		$get_r_blog = $this->m_blogs->query_all_blog();
	} else {
		$get_r_blog = $this->m_blogs->query_blog_industry($log_user_industry_id);
	}
	if(!empty($get_r_blog)){
		foreach($get_r_blog as $r_blog){
			$rn_user_id = $r_blog->user_id;
			$rn_title = $r_blog->title;
			$rn_slug = $r_blog->slug;
			$rn_details = $r_blog->details;
			$rn_view = $r_blog->view;
			$rn_img_id = $r_blog->img_id;
			$rn_reg_date = $r_blog->reg_date;
			$rn_upd_date = $r_blog->upd_date;
			$rn_status = $r_blog->status;
			$rn_count += 1;
			
			$rn_reg_date = timespan(strtotime($rn_reg_date), time());
			$rn_reg_date = explode(', ',$rn_reg_date);
			$rn_reg_date = $rn_reg_date[0];
			
			$rn_upd_date = timespan(strtotime($rn_upd_date), time());
			$rn_upd_date = explode(', ',$rn_upd_date);
			$rn_upd_date = $rn_upd_date[0];
			
			//get blog image
			if($rn_img_id==0){
				$rn_blog_img='img/icon152.png';
			} else {
				$gti = $this->users->query_img_id($rn_img_id);
				foreach($gti as $gtimg){
					$rn_blog_img = $gtimg->pics_square;	
				}
			}
			
			//query user data
			$rn_qu = $this->users->query_single_user_id($rn_user_id);
			if(!empty($rn_qu)){
				foreach($rn_qu as $rquser){
					$rn_user_firstname = $rquser->firstname;
					$rn_user_lastname = $rquser->lastname;
				}
			}
			
			//get total comments
			$rn_comments = 0;
			$rn_get_comments = $this->m_blogs->query_blog_comment($r_blog->id);
			if(!empty($rn_get_comments)){
				foreach($rn_get_comments as $rget_comments){
					$rn_comments += 1;	
				}
			}
			
			//only list Published Blogs or if its Posted Users Session
			if($rn_status == 1 || $rn_user_id == $log_user_id){
				if($rn_count <= 5){ //only recents 5
					$rn_all_item_list .= '
						<div class="col-lg-12" style="padding:10px; border-bottom:1px solid #eee; overflow:auto;">
							<img alt="" src="'.base_url($rn_blog_img).'" style="float:left; margin-right:10px; max-width:150px;" />
							<div style="border-bottom:1px solid #eee; padding-bottom:10px;">
								<small>
									Posted: <b>'.$rn_reg_date.' ago</b> | Updated: <b>'.$rn_upd_date.' ago</b><br />Posted by: <a href="'.base_url('profile?user='.$rn_user_id.'').'"><b>'.$rn_user_firstname.' '.$rn_user_lastname.'</b></a>
								</small>
							</div>
							<h4>'.ucwords($rn_title).'</h4>
							<a href="'.base_url('blog/'.$rn_slug).'" class="btn btn-primary pull-right">
								<small>
									<i class="gi gi-chat"></i> '.$rn_comments.'&nbsp;
									<i class="gi gi-eye_open"></i> '.$rn_view.'&nbsp;
									<i class="gi gi-blog"></i> Read More >>
								</small>
							</a>
						</div>
					';
				}
			}
		}
		
		$rn_all_item = '<div class="row">'.$rn_all_item_list.'</div>';
	}
?>