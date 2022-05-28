<?php include(APPPATH.'libraries/inc.php'); ?>
        <section class="layout">
            <!-- main content -->
            <section class="main-content">
                <!-- content wrapper -->
                <div class="content-wrap">
                    <!-- inner content wrapper -->
                    <div class="wrapper">
                    	<div class="row">
                            <div class="col-md-8">
                            	<div class="col-lg-12">
                                	<h2><i class="ti-bell"></i> Notifications</h2>
                                </div>
                                
                                <div class="col-lg-12 mb25">
                                	<?php
										$note_obj =& get_instance();
										$note_obj->load->model('users');
										$note_all_list = '';
										
										if(!empty($notify)){
											foreach($notify as $note){
												$date_group = $note->date_group;
												$note_list = '';
												
												$note_it = $note_obj->users->query_notify_by_date($log_user_id, $date_group);
												if(!empty($note_it)){
													foreach($note_it as $nit){
														$note_id = $nit->id;
														$note_type = $nit->type;
														$note_fan_id = $nit->fan_id;
														$note_receive_id = $nit->receive_id;
														$note_p_id = $nit->p_id;
														$note_s_id = $nit->s_id;	
														$note_content = $nit->content;	
														$note_status = $nit->status;	
														$note_reg_date = $nit->reg_date;
														
														$note_reg_date_ago = timespan(strtotime($note_reg_date), time());
														$note_reg_date_ago = explode(', ',$note_reg_date_ago);
														$note_reg_date_ago = $note_reg_date_ago[0];
														
														//get fan details
														$note_gf = $note_obj->users->query_single_user_id($note_fan_id);
														foreach($note_gf as $rnotef){
															$note_fan_nicename = $rnotef->user_nicename;
															$note_fan_display_name = ucwords($rnotef->display_name);
															$note_fan_pics_small = $rnotef->pics_small;
															if($note_fan_pics_small=='' || file_exists(FCPATH.$note_fan_pics_small)==FALSE){$note_fan_pics_small='img/avatar.jpg';}		
														}
														
														if($note_status==0){
															$note_shade = 'bg-primary';
														} else {
															$note_shade = '';
														}
														
														//check type
														if($note_type=='moot' || $note_type=='moot reply'){
															if($note_type=='moot'){
																$note_sticker = '<span class="label label-success pull-right"><i class="ti-thought"></i> New Moot</span>';
																$note_content = character_limiter($note_content,50);
																$note_link = base_url('notifications/?n='.$note_id.'&t='.$note_type.'&l='.$note_p_id);
															} else {
																$note_sticker = '<span class="label label-default pull-right"><i class="ti-thought"></i> Replied Moot</span>';
																$note_content = character_limiter($note_content,50);
																$note_link = base_url('notifications/?n='.$note_id.'&t='.$note_type.'&l='.$note_p_id.'-'.$note_s_id);
															}
														} else if($note_type=='join club'){
															$note_sticker = '<span class="label label-primary pull-right"><i class="fa fa-trophy"></i> Joined Club</span>';
															$note_content = character_limiter($note_content,50);
															$note_link = base_url('notifications/?n='.$note_id.'&t='.$note_type.'&l='.$note_p_id);
														} else if($note_type=='leave club'){
															$note_sticker = '<span class="label label-danger pull-right"><i class="fa fa-trophy"></i> Decamped</span>';
															$note_content = character_limiter($note_content,50);
															$note_link = base_url('notifications/?n='.$note_id.'&t='.$note_type.'&l='.$note_p_id);
														}
														
														$note_list .= '
															<div class="list-group-item '.$note_shade.'">
																<a href="'.$note_link.'">
																	<span class="pull-left mt5 mr15">
																		<img src="'.base_url($note_fan_pics_small).'" class="avatar avatar-sm img-circle" alt="">
																	</span>
																	<div class="m-body">
																		<div class="">
																			<small><b>'.$note_fan_display_name.'</b></small>
																			'.$note_sticker.'
																		</div>
																		<span>'.$note_content.'</span>
																		<span class="time small text-muted">'.$note_reg_date_ago.' ago</span>
																	</div>
																</a>
															</div>
														';
														
														//try mark notification
														//$upd_note = array(
//															'status' => 1
//														);
//														
//														$this->users->update_notification($note_id, $upd_note);	
													}
												}
												
												$note_all_list .= '
													<dt>
														<a href="javascript:;"><b>'.$date_group.'</b></a>
													</dt>
													<dd>
														'.$note_list.'
													</dd>
												';
											}
										}
									?>
                                    
                                    <section class="">
                                        <dl class="accordion">
                                            <?php echo $note_all_list; ?>
                                        </dl>
                                    </section>
                               	</div>
                                
                            </div>
                            
                            <div class="col-md-4">