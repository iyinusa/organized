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
                            <h2><i class="fa fa-user"></i> <strong>Profile</strong> Page</h2>
                            <?php if($log_user_id == $user_id){ ?>
                            <a href="<?php echo base_url('settings/account'); ?>" class="btn btn-default pull-right">Edit Profile</a>
                            <?php } ?>
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
                                    <td class="text-right" style="width: 50%;"><strong>Title</strong></td>
                                    <td><?php if(!empty($title)){echo $social_title;} ?></td>
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
                                    <td class="text-right"><strong>Sex</strong></td>
                                    <td><?php if(!empty($sex)){echo $sex;} ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Status</strong></td>
                                    <td>
                                    	<?php 
											if($acc_activate==0){
												echo '<span class="label label-warning"><i class="fa fa-times"></i> Pending</span>';		
											} else {
												echo '<span class="label label-success"><i class="fa fa-check"></i> Active</span>';	
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
                </div>
                <div class="col-lg-8">
                	<!-- Customer Addresses Block -->
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
                    <!-- END Customer Addresses Block -->

                    <!-- Private Notes Block -->
                    <div class="block full">
                        <!-- Private Notes Title -->
                        <div class="block-title">
                            <h2><i class="fa fa-file-text-o"></i> <strong>Private</strong> Notes</h2>
                        </div>
                        <!-- END Private Notes Title -->

                        <!-- Private Notes Content -->
                        <div class="alert alert-info">
                            <i class="fa fa-fw fa-info-circle"></i> This note will be displayed to all employees but not to customers.
                        </div>
                        <form action="" method="post" onsubmit="return false;">
                            <textarea id="private-note" name="private-note" class="form-control" rows="4" placeholder="Your note.."></textarea>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Note</button>
                        </form>
                        <!-- END Private Notes Content -->
                    </div>
                    <!-- END Private Notes Block -->
                </div>
            </div>
            <!-- END Customer Content -->
            
        </div>
        <!-- END Page Content -->