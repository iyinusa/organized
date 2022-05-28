<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Meta Data Block -->
            <div class="block" style="overflow:auto; min-height:500px;">
                <!-- Meta Data Title -->
                <div class="block-title">
                    <h2><i class="gi gi-iphone"></i>&nbsp;SMS Contact Directory</h2>
                </div>
                <!-- END Meta Data Title -->
                
                <?php 
                    if($module_access != TRUE){
                        echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
                        $command = 'style="display:none;"';
                    } else {$command = '';}
                ?>
                
                <?php if($rec_cat_del!='' && $rec_cat_del_s!=''){$edit='?r='.$rec_cat_del.'&s='.$rec_cat_del_s.'';}else{$edit='';} ?>
                <?php if($rec_del!='' && $rec_del_c!=''){$edit='?r='.$rec_del.'&c='.$rec_del_c.'';}else{$edit='';} ?>
                <?php echo form_open_multipart('sms/directory'.$edit); ?>
                    <?php if(!empty($err_msg)){echo $err_msg;} ?>
                    <?php if(!empty($e_cust_id)){echo '<div class="alert alert-info">You must select Category to Move Phone to</div>';} ?>
                    <input type="hidden" name="cat_id" value="<?php if(!empty($cat_e_id)){echo $cat_e_id;} ?>" />
                    <input type="hidden" name="phone_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                    <input type="hidden" name="cust_id" value="<?php if(!empty($e_cust_id)){echo $e_cust_id;} ?>" />
                    <?php
						$store_list='';
						$cat_list = '';
						$cat_dir_list = '';
						$dir_list = '';
						if(!empty($allstores)){
							foreach($allstores as $stores){
								$allstore = $this->m_stores->query_store_id($stores->store_id);
								if(!empty($allstore)){
									foreach($allstore as $store){
										if(!empty($e_cat_store_id)){
											if($e_cat_store_id == $store->id){
												$sc_sel = 'selected="selected"';	
											} else {$sc_sel = '';}
										} else {$sc_sel = '';}
										$store_list .= '<option value="'.$store->id.'" '.$sc_sel.'>'.$store->store.'</option>';
										
										//get sms contact category
										$gcont = $this->m_phones->query_phone_cat($store->id);
										if(!empty($gcont)){
											foreach($gcont as $cont){
												if(!empty($e_cat_id)){
													if($e_cat_id == $cont->id){
														$s_sel = 'selected="selected"';	
													} else {$s_sel = '';}
												} else {$s_sel = '';}
												$cat_list .= '<option value="'.$cont->id.'" '.$s_sel.'>'.$cont->cat.' ['.$store->store.']</option>';
												
												$cat_dir_list .= '
													<tr>
														<td>'.$store->store.'</td>
														<td>'.$cont->cat.'</td>
														<td class="text-center">
															<div class="btn-group btn-group-xs">
																<a href="'.base_url('sms/directory?cat_e='.$cont->id.'&cat_s='.$store->id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																<a href="'.base_url('sms/directory?cat_r='.$cont->id.'&cat_s='.$store->id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
															</div>
														</td>
													</tr>
												';
												
												//query sms contacts
												$gsmscont = $this->m_phones->query_phone($cont->id);
												if(!empty($gsmscont)){
													foreach($gsmscont as $smscont){
														$dir_list .= '
															<tr>
																<td>'.$store->store.'</td>
																<td>'.$cont->cat.'</td>
																<td>'.$smscont->name.'</td>
																<td>'.$smscont->no.'</td>
																<td class="text-center">
																	<div class="btn-group btn-group-xs">
																		<a href="'.base_url('sms/directory?e='.$smscont->id.'&c='.$cont->id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																		<a href="'.base_url('sms/directory?r='.$smscont->id.'&c='.$cont->id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
																	</div>
																</td>
															</tr>
														';
													}
												}
											}
										}
										
										//query phonebook from customers
										$gccont = $this->m_customers->query_customer($store->id);
										if(!empty($gccont)){
											foreach($gccont as $ccont){
												//check if number not in phonebook
												$chkno = $this->m_phones->check_phone_cust($ccont->id);
												if($chkno <= 0){
													//get group name
													$getgroup = $this->m_customers->query_group_id($ccont->group_id);
													if(!empty($getgroup)){
														foreach($getgroup as $group){
															$group_name = $group->name;
														}
													} else {$group_name = '';}
													
													$dir_list .= '
														<tr>
															<td>'.$store->store.'</td>
															<td>'.$group_name.'</td>
															<td>'.$ccont->firstname.' '.$ccont->lastname.'</td>
															<td>'.$ccont->phone.'</td>
															<td class="text-center">
																<div class="btn-group btn-group-xs">
																	<a href="'.base_url('sms/directory?e='.$ccont->id.'&s='.$store->id.'&m=1').'" data-toggle="tooltip" title="Move To Phonebook" class="btn btn-success"><i class="fa fa-eyedropper"></i> Move</a>
																</div>
															</td>
														</tr>
													';
												}
											}
										}
									}
								}
							}
						}
					?>
                    
                    <?php
						//check tab active
						if(isset($dir_act)){
							$act_dir = 'active';
							$act_add = '';
						} else {$act_add = 'active'; $act_dir = '';}
					?>
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Default Tabs -->
                            <ul class="nav nav-tabs push" data-toggle="tabs">
                                <li class="<?php echo $act_add; ?>"><a href="#add-phone"><i class="gi gi-plus"></i> Add To Phonebook</a></li>
                                <li class="<?php echo $act_dir; ?>"><a href="#phone-directory"><i class="gi gi-iphone"></i> Directory</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane <?php echo $act_add; ?>" id="add-phone">
                                    <h2>Add To Phonebook</h2><hr />
                                    <div class="col-lg-6">
                                        <?php if($rec_cat_del!='' && $rec_cat_del_s!=''){ ?>
                                            <div class="col-lg-12 bg-info">
                                                <h3>Are you sure? - Record will be totally remove from the system</h3>
                                                <input type="hidden" name="del_cat_id" value="<?php echo $rec_cat_del; ?>" />
                                                <input type="hidden" name="del_cat_s_id" value="<?php echo $rec_cat_del_s; ?>" />
                                                <button type="submit" name="cat_cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                                                <button type="submit" name="cat_delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                                            </div><hr />
                                        <?php } ?>
                                        <div class="form-horizontal form-bordered">
                                        	<div class="form-group">
                                            	<select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Store">
                                                    <option></option>
                                                    <?php echo $store_list; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" id="cat" name="cat" class="form-control" placeholder="Enter category name" value="<?php if(!empty($e_cat_cat)){echo $e_cat_cat;} ?>">
                                            </div>
                                            <div class="form-group form-actions" <?php echo $command; ?>>
                                                <div class="col-md-9 col-md-offset-3">
                                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                                    <button type="submit" name="cat_submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                    	<hr />
                                        <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Store</th>
                                                    <th>Category</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $cat_dir_list; ?>
                                            </tbody>
                                        </table><br />
                                    </div>
                                </div>
                                
                                <div class="tab-pane <?php echo $act_dir; ?>" id="phone-directory">
                                    <h2>Phonebook Directory</h2><hr />
                                    <div class="col-lg-12">
                                    	<?php if($rec_del!='' && $rec_del_c!=''){ ?>
                                            <div class="col-lg-12 bg-info">
                                                <h3>Are you sure? - Record will be totally remove from the system</h3>
                                                <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                                                <input type="hidden" name="del_c_id" value="<?php echo $rec_del_c; ?>" />
                                                <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                                                <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                                            </div><hr />
                                        <?php } ?>
                                        <div class="form-horizontal form-bordered">
                                        	<div class="form-group">
                                            	<div class="form-inline">
                                                    <select id="category_id" name="category_id" class="select-chosen" data-placeholder="Select category">
                                                        <option></option>
                                                        <?php echo $cat_list; ?>
                                                    </select>
                                                    <input type="text" id="name" name="name" class="form-control" placeholder="Full name" value="<?php if(!empty($e_name)){echo $e_name;} ?>" />
                                                    <input type="text" id="no" name="no" class="form-control" placeholder="Phone number" value="<?php if(!empty($e_no)){echo $e_no;} ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group form-actions" <?php echo $command; ?>>
                                                <div class="col-md-9 col-md-offset-3">
                                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                                    <button type="submit" name="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                    	<hr />
                                        <table id="ecom-products2" class="table table-bordered table-striped table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Store</th>
                                                    <th>Category</th>
                                                    <th>Name</th>
                                                    <th>Number</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $dir_list; ?>
                                            </tbody>
                                        </table><br />
                                    </div>
                                </div>
                            </div>
                            <!-- END Default Tabs -->
                        </div>
                    </div>  
                <?php echo form_close(); ?>
                <!-- END Meta Data Content -->
            </div>
            <!-- END Meta Data Block -->
        </div>
        <!-- END Page Content -->