<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <div class="block full">
                <div class="row">
                    <div class="col-lg-12">
                    	<h2>
							<?php if(!empty($blog_title)){echo $blog_title;} ?><br />
                            <small>
                            	Posted: <b><?php if(!empty($blog_reg_date)){echo $blog_reg_date.' ago';} ?></b> | Updated: <b><?php if(!empty($blog_upd_date)){echo $blog_upd_date.' ago';} ?></b> | Posted by: <a href="<?php echo base_url('profile?user='.$blog_user_id.''); ?>"><b><?php if(!empty($user_firstname)){echo $user_firstname;} ?> <?php if(!empty($user_lastname)){echo $user_lastname;} ?></b></a>
                            </small>
                        </h2>
                        
                    </div>
                    <div class="col-lg-12">
                    	<?php if(!empty($blog_img)){ ?>
                        	<img alt="" src="<?php echo base_url($blog_img); ?>" style="max-width:100%;" /><hr />
                        <?php } ?>
                        <?php if(!empty($blog_details)){echo $blog_details.'<hr />';} ?>
                    </div>
                    <div class="col-lg-12">
                    	<h4 class="text-muted">
							<i class="gi gi-eye_open"></i> <?php if(!empty($blog_view)){echo $blog_view;} else {echo '0';} ?> Views&nbsp;
                        	<i class="gi gi-chat"></i> <?php if(!empty($comment_count)){echo $comment_count;} else {echo '0';} ?> Comments
                        </h4><hr />
                    </div>
                    <div class="col-lg-12">
                    	<?php if(!empty($comments)){echo $comments;} else {echo '<h3>Be the first to comment</h3>';} ?>
                    </div>
                    <div class="col-lg-12">
                    	<?php if($log_user == TRUE){ ?>
							<?php echo form_open_multipart('blogs/comment', array('class'=>'form-horizontal form-bordered')); ?>
                            	<input type="hidden" name="blog_id" value="<?php if(!empty($blog_id)){echo $blog_id;} ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $log_user_id; ?>" />
                                <input type="hidden" name="blog_slug" value="<?php if(!empty($blog_slug)){echo $blog_slug;} ?>" />
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="com_msg">
                                    	<img alt="<?php echo $log_user_firstname; ?>" src="<?php echo base_url($log_user_pics_small); ?>" class="img-circle" />
                                    </label>
                                    <div class="col-md-9">
                                        <h3><?php echo $log_user_firstname; ?> <?php echo $log_user_lastname; ?></h3>
                                        <textarea id="com_msg" name="com_msg" class="form-control" rows="4" placeholder="Post your comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-actions">
                                    <div class="col-md-9 col-md-offset-3">
                                    	<button type="submit" class="btn btn-sm btn-primary"><i class="gi gi-chat"></i> Post Comment</button>
                                  	</div>
                                </div>
                            <?php echo form_close(); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- END Page Content -->