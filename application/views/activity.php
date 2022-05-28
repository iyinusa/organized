<?php include(APPPATH.'libraries/inc.php'); ?>
<?php include(APPPATH.'views/logics/recent_activity.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Customer Content -->
            <div class="row">
                <div class="block full">
                    <!-- Timeline Style Title -->
                    <div class="block-title">
                        <h2><i class="gi gi-bullhorn"></i> <strong>All</strong> Activities</h2>
                    </div>
                    <!-- END Timeline Style Title -->
    
                    <!-- Timeline Style Content -->
                    <div class="timeline">
                        <ul class="timeline-list timeline-hover">
                            <?php if(!empty($ra_all_item)){echo $ra_all_item;} ?>
                        </ul>
                    </div>
                    <!-- END Timeline Style Content -->
                </div>
            </div>
            <!-- END Customer Content -->
            
        </div>
        <!-- END Page Content -->