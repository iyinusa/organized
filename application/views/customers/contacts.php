<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Customer Content -->
            <div class="row">
                <div class="col-lg-4">
                    <!-- Add Contact Block -->
                    <div class="block">
                        <!-- Add Contact Title -->
                        <div class="block-title">
                            <h2><i class="gi gi-parents"></i>&nbsp;&nbsp;Add Contact</h2>
                        </div>
                        <!-- END Add Contact Title -->
    
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
                        
                        <!-- Add Contact Content -->
                        <?php echo form_open_multipart('contacts', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <input type="hidden" name="customer_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                            
                            <?php if(!empty($e_id)){ ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="new">As New</label>
                                <div class="col-md-9">
                                    <label class="switch switch-primary"><input name="new" type="checkbox"><span></span></label><br /><small class="text-muted">Activate to save <b>Contact</b> as <b>New For Outlet</b></small>
                                </div>
                            </div>
                            <?php } ?>
                            
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
                                <label class="col-md-3 control-label" for="group_id">Group</label>
                                <div class="col-md-9">
                                    <?php
                                        $group_list='';
                                        if(!empty($allgroup)){
                                            foreach($allgroup as $group){
                                                if(!empty($e_group_id)){
													if($e_group_id == $group->id){
														$g_sel = 'selected="selected"';	
													} else {$g_sel = '';}
												} else {$g_sel = '';}
												$group_list .= '<option value="'.$group->id.'" '.$g_sel.'>'.$group->name.'</option>';
                                            }
                                        }
                                    ?>
                                    <select id="group_id" name="group_id" class="select-chosen" data-placeholder="Select Group" required>
                                        <option></option>
                                        <?php echo $group_list; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="company">Company</label>
                                <div class="col-xs-9">
                                    <input type="text" id="company" name="company" class="form-control" placeholder="Enter company (if any)" value="<?php if(!empty($e_company)){echo $e_company;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="firstname">First Name</label>
                                <div class="col-xs-9">
                                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name" value="<?php if(!empty($e_firstname)){echo $e_firstname;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="lastname">Last Name</label>
                                <div class="col-xs-9">
                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name" value="<?php if(!empty($e_lastname)){echo $e_lastname;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_email">Email</label>
                                <div class="col-xs-9">
                                    <input type="email" id="c_email" name="c_email" class="form-control" placeholder="Enter email address" value="<?php if(!empty($e_email)){echo $e_email;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_phone">Phone</label>
                                <div class="col-xs-9">
                                    <input type="text" id="c_phone" name="c_phone" class="form-control" placeholder="Enter phone" value="<?php if(!empty($e_phone)){echo $e_phone;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_sex">Sex</label>
                                <div class="col-xs-9">
                                    <?php
                                        if(!empty($e_sex)){
                                            if($e_sex == 'Male'){$s1 = 'selected="selected"';} else {$s1 = '';}
											if($e_sex == 'Female'){$s2 = 'selected="selected"';} else {$s2 = '';}
                                        } else {$s1 = ''; $s2 = '';}
                                    ?>
                                    <select id="c_sex" name="c_sex" class="select-chosen" data-placeholder="Select Sex">
                                        <option></option>
                                        <option value="Male" <?php echo $s1; ?>>Male</option>
                                        <option value="Female" <?php echo $s2; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="c_dob">DOB</label>
                                <div class="col-md-9">
                                    <input type="text" id="c_dob" name="c_dob" class="form-control input-datepicker" data-date-format="mm/dd/yyyy" placeholder="mm/dd/yyyy" value="<?php if(!empty($e_dob)){echo $e_dob;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_address">Address</label>
                                <div class="col-xs-9">
                                    <textarea id="c_address" name="c_address" class="form-control"><?php if(!empty($e_address)){echo $e_address;} ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_state">State</label>
                                <div class="col-xs-9">
                                    <input type="text" id="c_state" name="c_state" class="form-control" placeholder="Enter state" value="<?php if(!empty($e_state)){echo $e_state;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-3 control-label" for="c_country">Country</label>
                                <div class="col-xs-9">
                                    <?php
                                        if(!empty($e_country)){
                                            if($e_country == 'Nigeria'){$c1 = 'selected="selected"';} else {$c1 = '';}
                                        } else {$c1 = '';}
                                    ?>
                                    <select id="c_country" name="c_country" class="select-chosen" data-placeholder="Select Country">
                                        <option></option>
                                        <option value="Nigeria" <?php echo $c1; ?>>Nigeria</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="active">Active</label>
                                <div class="col-md-9">
                                    <?php
                                        if(!empty($e_active)){
                                            if($e_active==1){
                                                $s_chk = 'checked';
                                            } else {$s_chk = '';}
                                        } else {$s_chk = '';}
                                    ?>
                                    <label class="switch switch-success"><input name="active" type="checkbox" <?php echo $s_chk;?>><span></span></label><br /><small class="text-muted">Activate to save <b>Contact</b> as <b>Customer/Supplier</b></small>
                                </div>
                            </div>
                            <div class="form-group form-actions" <?php echo $command; ?>>
                                <div class="col-xs-9 col-xs-offset-3">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Save</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <!-- END Add Contact Content -->
                    </div>
                    <!-- END Add Contact Block -->
                </div>
                
                <div class="col-lg-8">
                	<?php
						$dir_list='';
						if(!empty($allstores)){
							foreach($allstores as $stores){
								$allstore = $this->m_stores->query_store_id($stores->store_id);
								if(!empty($allstore)){
									foreach($allstore as $store){
										$get_contact = $this->m_customers->query_customer($store->id);
										if(!empty($get_contact)){
											foreach($get_contact as $contact){
												//get group name
												$get_group = $this->m_customers->query_group_id($contact->group_id);
												if(!empty($get_group)){
													foreach($get_group as $g){
														$contact_group = $g->name;	
													}
												} else {
													$contact_group = '';
												}
												
												$dir_list .= '
													<div class="col-sm-6 col-lg-6">
														<div class="widget">
															<div class="widget-simple">
																<a href="'.base_url('customers/view?s='.$store->id.'&u='.$contact->user_id.'').'">
																	<img src="'.base_url('img/icon76.png').'" alt="avatar" class="widget-image img-circle pull-left animation-fadeIn">
																</a>
																<h4 class="widget-content text-right">
																	<a href="'.base_url('customers/view?s='.$store->id.'&u='.$contact->user_id.'').'"><strong>'.$contact->firstname.' '.$contact->lastname.'</strong></a><br>
																	<span class="btn-group">
																		<a href="javascript:void(0)" class="btn btn-xs btn-default" data-toggle="tooltip" title="Group Category">'.substr($store->store,0,15).'..., '.$contact_group.'</a>
																		<a href="'.base_url().'contacts?r='.$contact->id.'&s='.$store->id.'&u='.$contact->user_id.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
																	</span>
																</h4>
															</div>
														</div>
													</div>
												';	
											}
										}
									}
								}
							}
						}
					?>
                
                    <!-- Contacts Block -->
                    <div class="block">
                        <!-- Contacts Title -->
                        <div class="block-title">
                            <div class="block-options text-center">
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-primary" onclick="filter_contact(0);">All</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(1);">A</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(2);">B</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(3);">C</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(4);">D</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(5);">E</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(6);">F</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(7);">G</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(8);">H</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(9);">I</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(10);">J</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(11);">K</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(12);">L</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(13);">M</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(14);">N</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(15);">O</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(16);">P</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(17);">Q</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(18);">R</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(19);">S</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(20);">T</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(21);">V</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(22);">U</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(23);">W</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(24);">X</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(25);">Y</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="filter_contact(26);">Z</a>
                            </div>
                            
                            <script type="text/javascript">
								function filter_contact(id){
									var hr = new XMLHttpRequest();
									var alpha;
									
									if(id==1){
										alpha='A';
									} else if(id==2){
										alpha='B';
									} else if(id==3){
										alpha='C';
									} else if(id==4){
										alpha='D';
									} else if(id==5){
										 alpha='E';
									} else if(id==6){
										alpha='F';
									} else if(id==7){
										alpha='G';
									} else if(id==8){
										alpha='H';
									} else if(id==9){
										alpha='I';
									} else if(id==10){
										alpha='J';
									} else if(id==11){
										alpha='K';
									} else if(id==12){
										alpha='L';
									} else if(id==13){
										alpha='M';
									} else if(id==14){
										alpha='N';
									} else if(id==15){
										alpha='O';
									} else if(id==16){
										alpha='P';
									} else if(id==17){
										alpha='Q';
									} else if(id==18){
										alpha='R';
									} else if(id==19){
										alpha='S';
									} else if(id==20){
										alpha='T';
									} else if(id==21){
										alpha='U';
									} else if(id==22){
										alpha='V';
									} else if(id==23){
										alpha='W';
									} else if(id==24){
										alpha='X';
									} else if(id==25){
										alpha='Y';
									} else if(id==26){
										alpha='Z';
									} else {
										alpha='All';
									}
									
									var c_vars = "alpha="+alpha;
									hr.open("POST", "<?php echo base_url('contacts/filter'); ?>", true);
									hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									hr.onreadystatechange = function() {
										if(hr.readyState == 4 && hr.status == 200) {
											var return_data = hr.responseText;
											document.getElementById("contacts_list").innerHTML = return_data;
									   }
									}
									hr.send(c_vars);
									document.getElementById("contacts_list").innerHTML = "<h3 class='text-center'><i class=\"fa fa-spinner fa-spin fa-2x\"></i> fetching...</h3>";
								}
							</script>
                        </div>
                        <!-- END Contacts Title -->
    
                        <!-- Contacts Content -->
                        <div id="contacts_list" class="row style-alt">
    						<?php
								if($dir_list == ''){
									$dir_list = '<h3 class="text-center text-muted">No Contacts</h3>';
								}
								
								echo $dir_list;
							?>
                        </div>
                        <!-- END Contacts Content -->
                    </div>
                    <!-- END Contacts Block -->
                </div>
                
            </div>
            <!-- END Customer Content -->
            
        </div>
        <!-- END Page Content -->