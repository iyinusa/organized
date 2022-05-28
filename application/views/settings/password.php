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
                            <h2><i class="gi gi-keys"></i>&nbsp;Change Password
                        </div>
                        <!-- END Meta Data Title -->
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('settings/password', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="old">Old Password</label>
                                <div class="col-md-9">
                                    <input type="password" id="old" name="old" class="form-control" placeholder="Enter old password" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="new">New Password</label>
                                <div class="col-md-9">
                                    <input type="password" id="new" name="new" class="form-control" placeholder="Enter new password" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="confirm">Confirm New Password</label>
                                <div class="col-md-9">
                                    <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Enter confirm new password" required="required">
                                </div>
                            </div>
                            <div class="form-group form-actions">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
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