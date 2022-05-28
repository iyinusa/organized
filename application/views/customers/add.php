<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <div class="row">
                <div class="col-lg-12">
                    <!-- Meta Data Block -->
                    <div class="block" style="overflow:auto;">
                        <!-- Meta Data Title -->
                        <div class="block-title">
                            <h2><i class="gi gi-address_book"></i>&nbsp;Manage Customer/Client
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							$command = '';
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-danger text-center"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								if(empty($e_id)){$command = 'style="display:none;"';}
							}
						?>
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('customers/add', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <div class="col-lg-6">
                            	<?php if(!empty($e_user_id)){ ?>
                                	<?php
										//query customer data
										$qu = $this->users->query_single_user_id($e_user_id);
										if(!empty($qu)){
											foreach($qu as $quser){
												$s_user_firstname = $quser->firstname;
												$s_user_lastname = $quser->lastname;
												$s_user_email = $quser->email;
												$s_user_pics_small = $quser->pics_small;
												
												if($s_user_pics_small=='' || file_exists(FCPATH.$s_user_pics_small)==FALSE){$s_user_pics_small='img/icon120.png';}
											}
										}
									?>
                                    <div class="form-group">
                                        <input type="hidden" name="user_id" value="<?php echo $e_user_id; ?>" />
                                        <label class="col-md-3 control-label" for="photo">Photo</label>
                                        <div class="col-md-9">
                                            <img alt="" src="<?php echo base_url($s_user_pics_small); ?>" width="70%" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="firstname">Customer</label>
                                        <div class="col-md-9">
                                            <?php echo $s_user_firstname.' '.$s_user_lastname; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="firstname">Email</label>
                                        <div class="col-md-9">
                                            <?php echo $s_user_email; ?>
                                        </div>
                                    </div>
                                    
                                <?php } else { ?>
                                
                                	<style>
										#div1 { display:none; }
									</style>
                                    <div class="form-group">
                                        <div class="btn-group btn-group-justified">
                                            <div class="btn-group">
                                              <button type="button" id="div2_click" class="btn btn-primary active" data-toggle="button">New Account</button>
                                            </div>
                                    
                                            <div class="btn-group">
                                               <button type="button" id="div1_click" class="btn btn-primary">Registered</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="div1">
                                    	<div class="form-group">
                                            <label class="col-md-3 control-label" for="reg_email">Email Address</label>
                                            <div class="col-md-9">
                                                <input type="email" id="reg_email" name="reg_email" class="form-control" placeholder="Registered account email">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div2">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="firstname">First name</label>
                                            <div class="col-md-9">
                                                <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="lastname">Last name</label>
                                            <div class="col-md-9">
                                                <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="email">Email Address</label>
                                            <div class="col-md-9">
                                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="password">Password</label>
                                            <div class="col-md-9">
                                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                               <br />
                            </div>
                            
                            <div class="col-lg-6">
                            	<input type="hidden" name="store_customer_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="store_id">Store</label>
                                    <div class="col-md-9">
                                        <?php
											$store_list='';
											if(!empty($allstores)){
												foreach($allstores as $stores){
													$allstore = $this->m_stores->query_store_id($stores->store_id);
													if(!empty($allstore)){
														foreach($allstore as $store){
															if(!empty($e_store_id)){
																if($e_store_id == $store->id){
																	$s_sel = 'selected="selected"';	
																} else {$s_sel = '';}
															} else {$s_sel = '';}
															$store_list .= '<option value="'.$store->id.'" '.$s_sel.'>'.$store->store.'</option>';
														}
													}
												}
											}
										?>
                                        <select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Store" required>
                                            <option></option>
                                            <?php echo $store_list; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-actions" <?php echo $command; ?>>
                                    <div class="col-md-9 col-md-offset-3">
                                        <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                    </div>
                                </div><br/><br/>
                            </div>
                            
                        <?php echo form_close(); ?>
                        <!-- END Meta Data Content -->
                    </div>
                    <!-- END Meta Data Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->