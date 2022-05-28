<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-6 col-lg-4">
                    <a href="<?php echo base_url('staff/add'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Add New</strong> Staff</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-danger">
                            <h4 class="widget-content-light"><strong>Unassigned</strong> Staff</h4>
                        </div>
                        <div class="widget-extra-full">
                            <span class="h2 text-danger animation-expandOpen">
                            	<?php
									$banned_count = 0;
									$all_store_count = 0;
									$owner_store_count = $this->m_stores->query_owner_store($log_user_id);
									if(!empty($owner_store_count)){
										foreach($owner_store_count as $store_count){
											if($store_count->owner_id != $store_count->user_id){ //remove owner from staff
												$all_store_count += 1;
											}
											
											if($store_count->status == 0){
												$banned_count += 1;
											}
										}
									} 
									echo $banned_count;
								?>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Staff</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $all_store_count; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All Products Block -->
            <div class="block full">
                <!-- All Products Title -->
                <div class="block-title">
                    <h2><i class="gi gi-user"></i> <strong>All</strong> Staff</h2>
                </div>
                <!-- END All Products Title -->
                
                <?php
					$dir_list = '';
					if(!empty($allstores)){
						foreach($allstores as $stores){
							$allstore = $this->m_stores->query_store_id($stores->store_id);
							if(!empty($allstore)){
								foreach($allstore as $store){
									$store_id = $store->id;
							
									//query store staff
									$qs = $this->m_stores->query_store_staff($store_id);
									if(!empty($qs)){
										foreach($qs as $qstore){
											if($qstore->owner_id != $qstore->user_id){
												$s_user_id = $qstore->user_id;
												$s_role = $qstore->role;
												$s_emp_date = $qstore->emp_date;
												$s_emp_id = $qstore->emp_id;
												$s_status = $qstore->status;
												$s_reg_date = $qstore->reg_date;
												
												if($s_status==0){$s_status = 'Unassigned';}else{$s_status = 'Assigned';}
												
												//query staff data
												if($s_user_id != 0){
													$qu = $this->users->query_single_user_id($s_user_id);
													if(!empty($qu)){
														foreach($qu as $quser){
															$s_user_firstname = $quser->firstname;
															$s_user_lastname = $quser->lastname;
															$s_user_pics_small = $quser->pics_small;
															
															if($s_user_pics_small=='' || file_exists(FCPATH.$s_user_pics_small)==FALSE){$s_user_pics_small='img/icon57.png';}
														}
													}
												} else {
													$s_user_firstname = $qstore->firstname;
													$s_user_lastname = $qstore->lastname;
													$s_user_pics_small = 'img/icon57.png';
												}
												
												$dir_list .= '
													<tr>
														<td class="text-center">
															<img alt="" src='.base_url($s_user_pics_small).' width="50px" class="img-circle" />
														</td>
														<td>'.$store->store.'</td>
														<td>'.$s_user_firstname.' '.$s_user_lastname.'</td>
														<td>'.$s_role.'</td>
														<td class="hidden-xs">'.$s_status.'</td>
														<td class="text-center">
															<div class="btn-group btn-group-xs">
																<a href="'.base_url('staff/add?e='.$qstore->id.'&s='.$store_id.'&u='.$s_user_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																<a href="'.base_url('staff/?r='.$qstore->id.'&s='.$store_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
        
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('staff/delete'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <input type="hidden" name="del_store_id" value="<?php echo $rec_del_s; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <!-- All Products Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px;">Photo</th>
                            <th>Outlet</th>
                            <th>Staff</th>
                            <th>Role</th>
                            <th class="hidden-xs">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php echo $dir_list; ?>
                    </tbody>
                </table>
                <!-- END All Products Content -->
            </div>
            <!-- END All Products Block -->
        </div>
        <!-- END Page Content -->