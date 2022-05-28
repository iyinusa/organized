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
                            <h2><i class="gi gi-user"></i>&nbsp;Manage Outlet Staff
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('staff/add', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <div class="col-lg-6">
                            	<?php if(!empty($e_user_id)){ ?>
                                	<?php
										//query staff data
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
                                        <label class="col-md-3 control-label" for="firstname">Photo</label>
                                        <div class="col-md-9">
                                            <img alt="" src="<?php echo base_url($s_user_pics_small); ?>" width="70%" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="firstname">Staff Name</label>
                                        <div class="col-md-9">
                                            <?php echo $s_user_firstname.' '.$s_user_lastname; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="firstname">Staff Email</label>
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
                                                <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name" value="<?php if(!empty($e_firstname)){echo $e_firstname;} ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="lastname">Last name</label>
                                            <div class="col-md-9">
                                                <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name" value="<?php if(!empty($e_lastname)){echo $e_lastname;} ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="email">Email</label>
                                            <div class="col-md-9">
                                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" value="<?php if(!empty($e_email)){echo $e_email;} ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="phone">Phone</label>
                                            <div class="col-md-9">
                                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number" value="<?php if(!empty($e_phone)){echo $e_phone;} ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="address">Address</label>
                                            <div class="col-md-9">
                                                <textarea id="address" name="address" class="form-control" placeholder="Enter staff address"><?php if(!empty($e_address)){echo $e_address;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="emp_date">Date of Birth</label>
                                            <div class="col-md-9">
                                                <input type="text" id="dob" name="dob" class="form-control input-datepicker" placeholder="Enter date of birth" data-date-format="dd/mm/yyyy" value="<?php if(!empty($e_dob)){echo $e_dob;} ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                      <label class="col-md-3 control-label" for="password">Password</label>
                                            <div class="col-md-9">
                                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"><br /><small class="text-muted">Only Staff with login access</small>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="status">Assign</label>
                                    <div class="col-md-9">
                                        <?php
                                            if(!empty($e_status)){
                                                if($e_status==1){
                                                    $s_chk = 'checked';
                                                } else {$s_chk = '';}
                                            } else {$s_chk = '';}
                                        ?>
                                        <label class="switch switch-success"><input name="status" type="checkbox" <?php echo $s_chk;?>><span></span></label> <small class="text-muted">Activate login</small>
                                    </div>
                                </div><br />
                            </div>
                            
                            <div class="col-lg-6">
                            	<input type="hidden" name="store_staff_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="store_id">Outlet</label>
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
                                        <select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Outlet" required>
                                            <option></option>
                                            <?php echo $store_list; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="emp_id">Employee ID</label>
                                    <div class="col-md-9">
                                        <input type="text" id="emp_id" name="emp_id" class="form-control" placeholder="Enter employee id" value="<?php if(!empty($e_emp_id)){echo $e_emp_id;} ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="emp_date">Date Employed</label>
                                    <div class="col-md-9">
                                        <input type="text" id="emp_date" name="emp_date" class="form-control input-datepicker" placeholder="Enter employed date" data-date-format="dd/mm/yyyy" value="<?php if(!empty($e_emp_date)){echo $e_emp_date;} ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="store_role">Access Role</label>
                                    <div class="col-md-9">
                                        <?php
											if(!empty($e_store_role)){
												if($e_store_role=='Manager'){$r1='selected="selected"';}else{$r1='';}
												if($e_store_role=='Sales'){$r2='selected="selected"';}else{$r2='';}
												if($e_store_role=='Staff'){$r3='selected="selected"';}else{$r3='';}
											} else {$r1=''; $r2=''; $r3='';}
										?>
                                        <select id="store_role" name="store_role" class="select-chosen" data-placeholder="Select Store Role" required>
                                            <option></option>
                                            <option value="Manager" <?php echo $r1; ?>>Manager</option>
                                            <option value="Sales" <?php echo $r2; ?>>Sales</option>
                                            <option value="Staff" <?php echo $r3; ?>>Staff</option>
                                        </select>
                                        <br /><small class="text-muted">Staff role will not be able to login</small>
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