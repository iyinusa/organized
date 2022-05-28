<?php include(APPPATH.'libraries/inc.php'); ?>
		<section class="layout">
            <!-- main content -->
            <section class="main-content">
                <!-- content wrapper -->
                <div class="content-wrap">
                    <!-- inner content wrapper -->
                    <div class="wrapper">
                    	<div class="row">
                            <div class="col-md-2">
                                <!-- profile information sidebar -->
                                <div class="panel overflow-hidden no-b profile p15">
                                    <div class="row mb25">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                    <img src="<?php echo base_url($club_pics); ?>" alt="" class="avatar avatar-lg img-circle avatar-bordered">
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                    <figure>
                                                        <h3><?php echo $club_name; ?></h3>
                                                        <small class="text-muted"><?php echo $club_slogan; ?></small>
                                                        <br />
                                                        <?php echo $club_league; ?><br />
                                                        <?php
															if($log_user == TRUE){
																if($log_user_club_id != $club_id){
																	echo '
																		<a class="btn btn-primary" href="'.base_url('club/'.$club_slug.'?join='.$club_id).'"><i class="ti-check-box"></i> Join Club</a>'.$club_msg.'
																	';
																} else {
																	echo '
																		<a class="btn btn-danger" href="'.base_url('club/'.$club_slug.'?leave='.$club_id).'"><i class="ti-close"></i> Leave Club</a>
																	';	
																}
															}
														?>
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 mt25 text-center bt">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <h2 class="mb0"><b><?php if($club_moot_count >= 100000){echo number_format(99999).'+';}else{echo number_format($club_moot_count);} ?></b></h2> 
                                                <small>Club Moots</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <h2 class="mb0"><b><?php if($club_moot_reply_count >= 100000){echo number_format(99999).'+';}else{echo number_format($club_moot_reply_count);} ?></b></h2> 
                                                <small>Club Trends</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <h2 class="mb0"><b><?php if($club_moot_all >= 100000){echo number_format(99999).'+';}else{echo number_format($club_moot_all);} ?></b></h2> 
                                                <small>Global Moots</small>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row mb15">
                                        <div class="col-xs-12">
                                            <h5 class="heading-font"><b>About <?php echo $club_name; ?></b></h5>
                                            <p><?php echo $club_bio; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /profile information sidebar -->
                            </div>
                            
                            <div class="col-md-6 mb25">
								<div class="box-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                        	<a href="#moots" data-toggle="tab"><i class="ti-thought"></i> Moot</a>
                                        </li>
                                        <li>
                                        	<a href="#fans" data-toggle="tab"><i class="fa fa-users"></i> Fans</a>
                                        </li>
                                        <li>
                                        	<a href="#clubfacts" data-toggle="tab"><i class="ti-book"></i> Facts</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in" id="moots">
                                            <div class="mb25">
												<?php if($log_user!=FALSE || $log_user_club_id!=0 || $log_user_club_ban!=0){ ?>
                                                    <?php if($club_id == $log_user_club_id){ ?>
                                                        <section class="panel bordered">
                                                            <form method="post" action="<?php echo base_url(); ?>moots/" id="postmoot">
                                                                <textarea id="moot" name="moot" placeholder="What's your moot?" rows="2" class="form-control no-b"></textarea>
                                                                <div class="panel-footer clearfix no-b">
                                                                    <!--<div class="pull-left">
                                                                        <button class="btn bg-none btn-sm" type="button">
                                                                            <i class="ti-camera"></i>
                                                                        </button>
                                                                        <button class="btn bg-none btn-sm" type="button">
                                                                            <i class="ti-video-camera"></i>
                                                                        </button>
                                                                        <button class="btn bg-none btn-sm" type="button">
                                                                            <i class="ti-time"></i>
                                                                        </button>
                                                                    </div>-->
                                                                    <div class="pull-right">
                                                                        <button type="submit" id="submit-moot" class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;<i class="ti-thought"></i> Moot Now&nbsp;&nbsp;&nbsp;</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </section>
                                                    <?php } ?>
                                                <?php } ?>
                                                
                                                <section id="sfhscroll" class="panel bordered  post-comments">
                
                                                    <?php include(APPPATH.'views/logics/moot_list.php'); ?>
                                                    
                                                    <div id="moot-msg"></div>
                                                    
                                                    <?php echo $moot_list; ?>
                                                    
                                                </section>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="fans">
                                            <div class="text-center mb25">
                                            	<?php
													if(!empty($all_club_fans)){
														foreach($all_club_fans as $cfan){
															if($cfan->pics_small=='' || file_exists(FCPATH.$cfan->pics_small)==FALSE){$cfan_pics='img/avatar.jpg';} else {$cfan_pics=$cfan->pics_small;}
															
															echo '
																<a href="'.base_url('fan/'.$cfan->user_nicename).'" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
																	<img alt="" src="'.base_url($cfan_pics).'" class="avatar img-circle" /> '.$cfan->display_name.'<br /><small class="text-muted">'.$cfan->country.'</small>
																</a>
															';	
														}
													} else {
														echo '<h3 class="text-center text-muted">'.$club_name.' has no Fan yet</h3>';	
													}
												?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="clubfacts">
                                            <div class="mb25">
                                            	<?php
													echo '<h4 class="text-muted">'.$club_name.' Facts History</h4>';
													if(!empty($allclubfact)){
														foreach($allclubfact as $clubfact){
															echo '
																<div class="col-lg-12 bt p10">
																	<div class="col-lg-4">
																		<b>'. $clubfact->fact_year.'</b>
																	</div>
																	<div class="col-lg-8">
																		<a href="'.base_url('fact/'.$clubfact->id).'">
																			'.character_limiter($clubfact->fact_details,100).'
																		</a>
																	</div>
																</div>
															';	
														}
													} else {
														echo '<h3 class="text-center text-muted">'.$club_name.' has no Fact yet</h3>';	
													}
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-4">

                            