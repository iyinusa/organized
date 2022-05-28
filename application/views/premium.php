<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Customer Content -->
            <div class="block full">
                <h2 class="alert text-center"><?php echo app_name; ?> Pricing</h2>
                <div class="table-responsive">
                    <table class="table table-borderless table-pricing">
                        <thead>
                            <tr>
                                <th>Starter</th>
                                <th class="table-featured"><i class="fa fa-briefcase"></i> Business</th>
                                <th>Pro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1</strong> Outlet</td>
                                <td class="table-featured"><strong>3</strong> Outlets</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>25</strong> Contacts</td>
                                <td class="table-featured"><strong>75</strong> Contacts</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>15</strong> Services</td>
                                <td class="table-featured"><strong>50</strong> Services</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>15</strong> Products</td>
                                <td class="table-featured"><strong>50</strong> Products</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="table-price">
                                    <h2>&#8358;2,500<br><small>per month</small></h2>
                                </td>
                                <td class="table-price table-featured">
                                    <h2>&#8358;5,000<br><small>per month</small></h2>
                                </td>
                                <td class="table-price">
                                    <h2>Request<br><small>PRO VERSION</small></h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-angle-right"></i> Get Started <i class="fa fa-angle-left"></i></a>
                                </td>
                                <td class="table-featured">
                                    <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-angle-right"></i> Get Started <i class="fa fa-angle-left"></i></a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-angle-right"></i> Get Started <i class="fa fa-angle-left"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END Customer Content -->
            
        </div>
        <!-- END Page Content -->