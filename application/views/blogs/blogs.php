<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$blog_count = 0;
				
				if(!empty($allblogs)){
					foreach($allblogs as $blogs){
						$rn_user_id = $blogs->user_id;
						$rn_title = $blogs->title;
						$rn_slug = $blogs->slug;
						$rn_details = $blogs->details;
						$rn_view = $blogs->view;
						$rn_img_id = $blogs->img_id;
						$rn_reg_date = $blogs->reg_date;
						$rn_upd_date = $blogs->upd_date;
						$rn_status = $blogs->status;
						$blog_count += 1;
						
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
						$rn_get_comments = $this->m_blogs->query_blog_comment($blogs->id);
						if(!empty($rn_get_comments)){
							foreach($rn_get_comments as $rget_comments){
								$rn_comments += 1;	
							}
						}
						
						//only list Published Blogs or if its Posted Users Session
						if($rn_status == 1 || $rn_user_id == $log_user_id){
							$dir_list .= '
								<div class="block col-xs-12 col-sm-12  col-md-6 col-lg-6" style="padding:10px; border-bottom:1px solid #eee;">
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
			?>
            
            <div class="row">
            	<?php if(!empty($dir_list)){echo $dir_list;} else {echo '<h2 class="text-muted text-center"><i class="gi gi-blog"></i> No Latest News Yet.</h2>';} ?>
            </div>
        </div>
        <!-- END Page Content -->