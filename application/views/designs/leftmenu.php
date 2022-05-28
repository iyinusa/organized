<?php include(APPPATH.'libraries/inc.php'); ?>
        <section class="layout">
            <!-- main content -->
            <section class="main-content">
                <!-- content wrapper -->
                <div class="content-wrap">
                    <!-- inner content wrapper -->
                    <div class="wrapper">
                    	<div class="row">
                            <div class="col-md-4">
                                <!-- profile information sidebar -->
                                <div class="panel overflow-hidden no-b profile p15">
                                    <div class="row mb25">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-8">
                                                    <h4 class="mb0"><?php echo $fan_display_name; ?></h4>
                                                    <small><?php echo $fan_nicename.' ('.$fan_sex.')'; ?></small>

                                                    <ul class="user-meta">
                                                        <li>
                                                            <i class="ti-email mr5"></i>
                                                            <span><?php echo $fan_email; ?></span>
                                                        </li>
                                                        <li>
                                                            <i class="ti-location-pin mr5"></i>
                                                            <span><?php echo $fan_location; ?></span>
                                                        </li>
                                                        <li>
                                                            <i class="ti-settings mr5"></i>
                                                            <a href="javascript:;">Edit Profile</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 text-center">
                                                    <figure>
                                                        <img src="<?php echo base_url($fan_pics_small); ?>" alt="" class="avatar avatar-lg img-circle avatar-bordered">
                                                        <div class="small mt10">Fan Strength</div>
                                                        <div class="progress progress-xs mt5 mb5">
                                                            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $fan_quota; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $fan_quota; ?>%">
                                                            </div>
                                                        </div>
                                                        <small><?php echo $fan_quota_value; ?> / <?php echo $all_quota_value; ?> (<?php echo $fan_quota; ?>%)</small>
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 mt25 text-center bt">
                                            <div class="col-xs-12 col-sm-4">
                                                <h2 class="mb0"><b>23,8K</b></h2> 
                                                <small>Mentioned</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <h2 class="mb0"><b>569</b></h2> 
                                                <small>Contributions</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <h2 class="mb0"><b>67</b></h2> 
                                                <small>Posts</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb15">
                                        <div class="col-xs-12">
                                            <h6 class="heading-font">About <?php echo $fan_display_name; ?></h6>
                                            <p><?php echo $fan_bio; ?></p>
                                        </div>

                                        <div class="col-xs-12 mt15">
                                            <h6 class="heading-font">Social Profiles</h6>
                                            <div class="mt10 mb10">
                                                <a href="<?php echo $fan_fb_page; ?>" target="_blank" class="btn btn-social btn-xs btn-facebook mr5"><i class="fa fa-facebook"></i>Facebook </a>
                                                <a href="<?php echo $fan_twitter_page; ?>" target="_blank" class="btn btn-social btn-xs btn-twitter mr5"><i class="fa fa-twitter"></i>Twitter </a>
                                                <a href="<?php echo $fan_linkedin_page; ?>" target="_blank" class="btn btn-social btn-xs btn-linkedin mr5"><i class="fa fa-linkedin"></i>LinkedIn </a>
                                            </div>
                                        </div>

                                    </div>

                                    <a href="<?php echo $fan_website; ?>" target="_blank" class="text-muted">
                                        <i class="fa fa-globe mr15"></i><?php echo $fan_website; ?></a>
                                </div>
                                <!-- /profile information sidebar -->
                            </div>