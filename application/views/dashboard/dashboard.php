<?php include(APPPATH.'libraries/inc.php'); ?>
<?php include(APPPATH.'views/logics/recent_birthday.php'); ?>
<?php include(APPPATH.'views/logics/recent_blog.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <?php include(APPPATH.'views/logics/dashboard_logics.php'); ?>
            <!-- END eCommerce Dashboard Header -->

            <!-- Mini Top Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-6">
                    <!-- Widget -->
                    <a href="<?php echo base_url('dashboard'); ?>" class="widget widget-hover-effect1">
                        <div class="widget-simple">
                            <div class="widget-icon pull-left themed-background-spring animation-fadeIn">
                                <i class="gi gi-database_plus"></i>
                            </div>
                            <h3 class="widget-content text-right animation-pullDown">
                                <?php echo $cur_currency; ?><strong><?php if(!empty($sales_today)){echo number_format($sales_today);}else{echo '0.00';} ?></strong><br>
                                <small><b>Sales Today</b></small>
                            </h3>
                        </div>
                    </a>
                    <!-- END Widget -->
                </div>
                <div class="col-sm-6 col-lg-6">
                    <!-- Widget -->
                    <a href="<?php echo base_url('dashboard'); ?>" class="widget widget-hover-effect1">
                        <div class="widget-simple">
                            <div class="widget-icon pull-left themed-background-fire animation-fadeIn">
                                <i class="gi gi-money"></i>
                            </div>
                            <h3 class="widget-content text-right animation-pullDown">
                                <?php echo $cur_currency; ?><strong><?php if(!empty($expenses_today)){echo number_format($expenses_today);}else{echo '0.00';} ?></strong><br>
                                <small><b>Expenses Today</b></small>
                            </h3>
                        </div>
                    </a>
                    <!-- END Widget -->
                </div>
            </div>
            <!-- END Mini Top Stats Row -->

            <!-- Widgets Row -->
            <div class="row">
                <div class="col-md-6">
                    <!-- Charts Widget -->
                    <div class="widget">
                        <div class="widget-advanced widget-advanced-alt">
                            <!-- Widget Header -->
                            <div class="widget-header text-center themed-background">
                                <div class="row">
                                	<div class="col-lg-12">
                                        <h3 class="widget-content-light text-left pull-left animation-pullDown">
                                            <strong>Sales</strong> &amp; <strong>Expenses</strong><br>
                                            <small>The Year of <?php echo date('Y'); ?></small>
                                        </h3>
                                    </div>
                                </div>
                                <div id="chart-classic" class="chart"></div>
                            </div>
                            <!-- END Widget Header -->

                            <!-- Widget Main -->
                            <div class="widget-main">
                                <div class="row text-center">
                                    <div class="col-xs-6">
                                        <h3 class="animation-hatch"><?php echo $cur_currency; ?><strong><?php if(!empty($total_sales)){echo number_format($total_sales, 2);} else {echo '0.00';} ?></strong><br><small>Overall Sales<br/><span style="font-size:small;"><i>(Products + Services)</i></span></small></h3>
                                    </div>
                                    <div class="col-xs-6">
                                        <h3 class="animation-hatch"><?php echo $cur_currency; ?><strong><?php if(!empty($total_earning)){echo number_format($total_earning, 2);} else {echo '0.00';} ?></strong><br><small>Overall Earnings<br/><span style="font-size:small;"><i>(Sales - Expenses)</i></span></small></h3>
                                    </div>
                                </div>
                            </div>
                            <!-- END Widget Main -->
                        </div>
                    </div>
                    <!-- END Charts Widget -->
                    
                    <!-- Birthday Widget -->
                    <div class="widget">
                        <div class="widget-extra themed-background-info">
                            <h3 class="widget-content-light">
                                Today's <strong>Birthdays</strong>
                                <!--<small><a href="javascript:void(0)"><strong>View all</strong></a></small>-->
                            </h3>
                        </div>
                        <div class="widget-extra">
                            <div class="timeline">
                                <?php 
									if(!empty($rb_all_item)){
										echo $rb_all_item;
									} else {
										echo '<h3 class="text-muted text-center">No Birthday List Today</h3>';
									}
								?>
                            </div>
                        </div>
                    </div>
                    <!-- END Birthday Widget -->
                </div>
                <div class="col-md-6">
                    <!-- Latest News Widget -->
                    <div class="widget">
                        <div class="widget-extra themed-background-dark">
                            <?php if($log_user_role == 'administrator'){ ?>
                            <div class="widget-options">
                                <div class="btn-group btn-group-xs">
                                    <a href="<?php echo base_url('admin/blogs/manage'); ?>" class="btn btn-default" data-toggle="tooltip" title="Add Post"><i class="fa fa-plus"></i> Add Post</a>
                                </div>
                            </div>
                            <?php } ?>
                            <h3 class="widget-content-light">
                                Latest <strong>News</strong>
                                <small><a href="<?php echo base_url('blogs'); ?>"><strong>View all (<?php if(!empty($rn_count)){echo $rn_count;} else {echo '0';} ?>)</strong></a></small>
                            </h3>
                        </div>
                        <div class="widget-extra">
                            <div class="timeline">
                                <!--<ul class="timeline-list">
                                    <li class="active">
                                        <div class="timeline-icon"><i class="gi gi-airplane"></i></div>
                                        <div class="timeline-time"><small>just now</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Jordan Carter</strong></a></p>
                                            <p class="push-bit">The trip was an amazing and a life changing experience!!</p>
                                            <p class="push-bit"><a href="page_ready_article.html" class="btn btn-xs btn-primary"><i class="fa fa-file"></i> Read the article</a></p>
                                            <div class="row push">
                                                <div class="col-sm-6 col-md-4">
                                                    <a href="img/placeholders/photos/photo1.jpg" data-toggle="lightbox-image">
                                                        <img src="img/placeholders/photos/photo1.jpg" alt="image">
                                                    </a>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <a href="img/placeholders/photos/photo22.jpg" data-toggle="lightbox-image">
                                                        <img src="img/placeholders/photos/photo22.jpg" alt="image">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="active">
                                        <div class="timeline-icon themed-background-fire themed-border-fire"><i class="fa fa-file-text"></i></div>
                                        <div class="timeline-time"><small>5 min ago</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Administrator</strong></a></p>
                                            <strong>Free courses</strong> for all our customers at A1 Conference Room - 9:00 <strong>am</strong> tomorrow!
                                        </div>
                                    </li>
                                    <li class="active">
                                        <div class="timeline-icon"><i class="gi gi-drink"></i></div>
                                        <div class="timeline-time"><small>3 hours ago</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Ella Winter</strong></a></p>
                                            <p class="push-bit"><strong>Happy Hour!</strong> Free drinks at <a href="javascript:void(0)">Cafe-Bar</a> all day long!</p>
                                            <div id="gmap-timeline" class="gmap"></div>
                                        </div>
                                    </li>
                                    <li class="active">
                                        <div class="timeline-icon"><i class="fa fa-cutlery"></i></div>
                                        <div class="timeline-time"><small>yesterday</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Patricia Woods</strong></a></p>
                                            <p class="push-bit">Today I had the lunch of my life! It was delicious!</p>
                                            <div class="row push">
                                                <div class="col-sm-6 col-md-4">
                                                    <a href="img/placeholders/photos/photo23.jpg" data-toggle="lightbox-image">
                                                        <img src="img/placeholders/photos/photo23.jpg" alt="image">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="active">
                                        <div class="timeline-icon themed-background-fire themed-border-fire"><i class="fa fa-smile-o"></i></div>
                                        <div class="timeline-time"><small>2 days ago</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Administrator</strong></a></p>
                                            To thank you all for your support we would like to let you know that you will receive free feature updates for life! You are awesome!
                                        </div>
                                    </li>
                                    <li class="active">
                                        <div class="timeline-icon"><i class="fa fa-pencil"></i></div>
                                        <div class="timeline-time"><small>1 week ago</small></div>
                                        <div class="timeline-content">
                                            <p class="push-bit"><a href="page_ready_user_profile.html"><strong>Nicole Ward</strong></a></p>
                                            <p class="push-bit">Consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor. Vestibulum ullamcorper, odio sed rhoncus imperdiet, enim elit sollicitudin orci, eget dictum leo mi nec lectus. Nam commodo turpis id lectus scelerisque vulputate.</p>
                                            Integer sed dolor erat. Fusce erat ipsum, varius vel euismod sed, tristique et lectus? Etiam egestas fringilla enim, id convallis lectus laoreet at. Fusce purus nisi, gravida sed consectetur ut, interdum quis nisi. Quisque egestas nisl id lectus facilisis scelerisque? Proin rhoncus dui at ligula vestibulum ut facilisis ante sodales! Suspendisse potenti. Aliquam tincidunt sollicitudin sem nec ultrices. Sed at mi velit. Ut egestas tempor est, in cursus enim venenatis eget! Nulla quis ligula ipsum.
                                        </div>
                                    </li>
                                    <li class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-xs btn-default">View more..</a>
                                    </li>
                                </ul>-->
                                <?php 
									if(!empty($rn_all_item)){
										echo $rn_all_item;
									} else {
										echo '<h3 class="text-muted text-center">No News Feed Yet</h3>';
									}
								?>
                            </div>
                        </div>
                    </div>
                    <!-- END Latest News Widget -->
                </div>
            </div>
            <!-- END Widgets Row -->
        </div>
        <!-- END Page Content -->