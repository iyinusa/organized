<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Customer Content -->
            <div class="row">
                <div class="col-lg-4">
                    <!-- Customer Info Block -->
                    <div class="block">
                        <!-- Customer Info Title -->
                        <div class="block-title">
                            <h2><i class="fa fa-file-o"></i> <strong>Client</strong> Info</h2>
                        </div>
                        <!-- END Customer Info Title -->

                        <!-- Customer Info -->
                        <div class="block-section text-center">
                            <a href="javascript:void(0)">
                                <img alt="" src="<?php if(!empty($pics_small)){echo base_url($pics_small);} ?>" class="img-circle">
                            </a>
                            <h3>
                                <strong><?php if(!empty($firstname) && !empty($lastname)){echo $firstname.' '.$lastname;} ?></strong><br><small></small>
                            </h3>
                        </div>
                        <table class="table table-borderless table-striped table-vcenter">
                            <tbody>
                                <tr>
                                    <td class="text-right" style="width: 50%;"><strong>Sex</strong></td>
                                    <td><?php if(!empty($sex)){echo $sex;} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Birthdate</strong></td>
                                    <td><?php if(!empty($dob)){echo date('d M',strtotime($dob));} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Registration</strong></td>
                                    <td><?php if(!empty($reg_date)){echo date('d M Y',strtotime($reg_date));} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Last Login</strong></td>
                                    <td><?php if(!empty($user_lastlog)){echo $user_lastlog;} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Email</strong></td>
                                    <td><?php if(!empty($email)){echo $email;} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Status</strong></td>
                                    <td>
                                    	<?php 
											if(!empty($acc_activate)){
												if($acc_activate==0){
													echo '<span class="label label-warning"><i class="fa fa-times"></i> Pending</span>';		
												} else {
													echo '<span class="label label-success"><i class="fa fa-check"></i> Active</span>';	
												}
											}
										?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Online</strong></td>
                                    <td>
                                    	<?php 
											if(!empty($user_status)){
												if($user_status==0){
													echo '<span class="label label-default"><i class="fa fa-times"></i> Offline</span>';		
												} else {
													echo '<span class="label label-success"><i class="fa fa-check"></i> Online</span>';	
												}
											}
										?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- END Customer Info -->
                    </div>
                    <!-- END Customer Info Block -->

                    <!-- Quick Stats Block -->
                    <div class="block">
                        <!-- Quick Stats Title -->
                        <div class="block-title">
                            <h2><i class="fa fa-line-chart"></i> <strong>Quick</strong> Stats</h2>
                        </div>
                        <!-- END Quick Stats Title -->

                        <!-- Quick Stats Content -->
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background">
                                    <i class="gi gi-database_plus"></i>
                                </div>
                                <h4 class="text-left">
                                    <strong><?php if(!empty($total_invoice)){echo $total_invoice;} else {echo 0;} ?></strong><br><small>Invoices in Total</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-success">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <h4 class="text-left text-success">
                                    <strong>&#8358;<?php if(!empty($total_paid)){echo number_format($total_paid);} else {echo 0;} ?></strong><br><small>Invoices Paid</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-warning">
                                    <i class="gi gi-database_minus"></i>
                                </div>
                                <h4 class="text-left text-warning">
                                    <strong>&#8358;<?php if(!empty($total_unpaid)){echo number_format($total_unpaid);} else {echo 0;} ?></strong><br><small>Invoices Unpaid</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-info">
                                    <i class="gi gi-database_plus"></i>
                                </div>
                                <h4 class="text-left text-info">
                                    <strong>&#8358;<?php if(!empty($total_partial)){echo number_format($total_partial);} else {echo 0;} ?></strong><br><small>Invoices Partially Paid</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-danger">
                                    <i class="gi gi-database_plus"></i>
                                </div>
                                <h4 class="text-left text-info">
                                    <strong class="text-danger">&#8358;<?php if(!empty($total_outstanding)){echo number_format($total_outstanding);} else {echo 0;} ?></strong><br><small>Invoices Outstandings</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-warning">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <h4 class="text-left text-warning">
                                    <strong>&#8358; <?php if(!empty($total_overdue)){echo number_format($total_overdue);} else {echo 0;} ?></strong><br><small>Invoices Overdues</small>
                                </h4>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                            <div class="widget-simple">
                                <div class="widget-icon pull-right themed-background-danger">
                                    <i class="gi gi-database_minus"></i>
                                </div>
                                <h4 class="text-left text-danger">
                                    <strong>&#8358;<?php if(!empty($total_cancel)){echo number_format($total_cancel);} else {echo 0;} ?></strong><br><small>Invoices Cancelled</small>
                                </h4>
                            </div>
                        </a>
                        <!-- END Quick Stats Content -->
                    </div>
                    <!-- END Quick Stats Block -->
                </div>
                <div class="col-lg-8">
                    <div class="block">
                        <!-- Customer Addresses Title -->
                        <div class="block-title">
                            <h2><i class="fa fa-building-o"></i> <strong>Contacts</strong></h2>
                        </div>
                        <!-- END Customer Addresses Title -->

                        <!-- Customer Addresses Content -->
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Billing Address Block -->
                                <div class="block">
                                    <!-- Billing Address Title -->
                                    <div class="block-title">
                                        <h2>Address</h2>
                                    </div>
                                    <!-- END Billing Address Title -->

                                    <!-- Billing Address Content -->
                                    <address>
                                        <?php if(!empty($address)){echo $address;} ?><br>
                                        <?php if(!empty($city)){echo $city;} ?> <?php if(!empty($state)){echo $state;} ?><br>
                                        <?php if(!empty($country)){echo $country;} ?>
                                    </address>
                                    <!-- END Billing Address Content -->
                                </div>
                                <!-- END Billing Address Block -->
                            </div>
                            <div class="col-lg-6">
                                <!-- Shipping Address Block -->
                                <div class="block">
                                    <!-- Shipping Address Title -->
                                    <div class="block-title">
                                        <h2>Instant Contacts</h2>
                                    </div>
                                    <!-- END Shipping Address Title -->

                                    <address>
                                        <i class="fa fa-phone"></i> <?php if(!empty($phone)){echo $phone;} ?><br>
                                        <i class="fa fa-envelope-o"></i> <a href="mailto:<?php if(!empty($email)){echo $email;} ?>"><?php if(!empty($email)){echo $email;} ?></a>
                                    </address>
                                    <!-- END Shipping Address Content -->
                                </div>
                                <!-- END Shipping Address Block -->
                            </div>
                        </div>
                        <!-- END Customer Addresses Content -->
                    </div>
                    
                    <!-- Orders Block -->
                    <div class="block">
                        <!-- Orders Title -->
                        <div class="block-title">
                            <div class="block-options pull-right">
                                <span class="label label-success"><strong>&#8358;<?php if(!empty($total_invoice_amt)){echo number_format($total_invoice_amt);} else {echo 0;} ?></strong></span>
                            </div>
                            <h2><i class="gi gi-database_plus"></i> <strong>Invoices</strong> (<?php if(!empty($total_invoice)){echo $total_invoice;} else {echo 0;} ?>)</h2>
                        </div>
                        <!-- END Orders Title -->
                        
                        <!-- Orders Content -->
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            	<tr>
                                	<td class="text-center" style="width: 100px;">ID</td>
                                    <td class="text-center">Items</td>
                                    <td class="text-right">Amount</td>
                                    <td class="text-center">Status</td>
                                    <td class="text-center">Date</td>
                                    <td class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                               <?php if(!empty($invoice_list)){echo $invoice_list;} ?>
                            </tbody>
                        </table>
                        <!-- END Orders Content -->
                    </div>
                    <!-- END Orders Block -->
                </div>
            </div>
            <!-- END Customer Content -->
            
        </div>
        <!-- END Page Content -->