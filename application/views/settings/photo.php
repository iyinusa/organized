<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <div class="row">
                <div class="col-lg-12">
                    <!-- Meta Data Block -->
                    <div class="block">
                        <!-- Meta Data Title -->
                        <div class="block-title">
                            <h2><i class="gi gi-film"></i>&nbsp;Change Profile Picture
                        </div>
                        <!-- END Meta Data Title -->
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('settings/photo', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Update Profile</label>
                                <div class="col-md-9">
                                    <a href="<?php echo base_url('settings/account'); ?>" class="btn btn-default btn-sm"><i class="fa fa-user"></i> Update Profile Details</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="current">Current Picture</label>
                                <div class="col-md-9">
                                    <img alt="" src="<?php echo base_url($log_user_pics); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="pics">Browse Picture</label>
                                <div class="col-md-9">
                                    <input type="file" id="pics" name="pics" class="form-control"><br />
                                    <b>Note:</b> Picture must not less than 400px in Width
                                </div>
                            </div>
                            <div class="form-group form-actions">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Upload/Save</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <!-- END Meta Data Content -->
                    </div>
                    <!-- END Meta Data Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->