<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_account = 0;
				$total_stores = 0;
				$paid_stores = 0;
				
				//get total paid account
				$tacc = $this->users->query_premium_user_group();
				if(!empty($tacc)){
					foreach($tacc as $acc){
						if($acc->type != '' && $acc->type != 'Free'){$total_account += 1;}
					}
				}
				
				//query all store
				if(!empty($allstores)){
					if(!empty($allstores)){
						foreach($allstores as $stores){
							$total_stores += 1; //count stores
							
							//get store image
							$store_img = $stores->img_id;
							if($store_img==0){
								$store_img='img/icon57.png';
							} else {
								$gti = $this->users->query_img_id($store_img);
								foreach($gti as $gtimg){
									$store_img = $gtimg->pics_square;	
								}
							}
							
							//get store owner
							$store_owner = $this->users->query_single_user_id($stores->user_id);
							if(!empty($store_owner)){
								foreach($store_owner as $owner){
									$owner_name = $owner->title.' '.$owner->firstname.' '.$owner->lastname;
								}
							} else {$owner_name = '';}
							
							//get premium details
							$user_prem = $this->users->query_premium_user($stores->user_id);
							if(!empty($user_prem)){
								foreach($user_prem as $prem){
									//get type
									$ptype = $prem->type;
									if($ptype == ''){$ptype = 'Free';}
									$owner_premium = $ptype;
									
									if($ptype != '' && $ptype != 'Free' && $prem->status != 0){$paid_stores += 1;}
									
									//get status
									$pstatus = 	$prem->status;
									if($pstatus == 0){$pstatus = 'Expired';}else{$pstatus = 'Active';}
									
									//get duration
									if($prem->trial == 0){
										$pstart = $prem->main_start;
										$pend = $prem->main_end;
									} else {
										$pstart = $prem->trial_start;
										$pend = $prem->trial_end;
									}
								}
							} else {$ptype = 'Free'; $pstatus = 'Undefined'; $pstart = 'Undefined'; $pend = 'Undefined';}
							
							if($log_user_role == 'administrator'){
								$role_btn = '<a href="'.base_url('admin/stores/edit?user='.$stores->user_id.'').'" data-toggle="tooltip" title="Edit Store Account" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
								$del_btn = '<a href="'.base_url('admin/stores/remove?store='.$stores->id.'&user='.$stores->user_id.'').'" data-toggle="tooltip" title="Delete Store" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>';
							} else {
								$role_btn = '';
								$del_btn = '';
							}
							
							if($ptype == 'Free'){
								$bck_colour = 'class="alert alert-primary"';	
							} else {
								$bck_colour = '';
							}
							
							$dir_list .= '
								<tr '.$bck_colour.'>
									<td class="hidden-xs text-center"><img alt="" src='.base_url($store_img).' width="50px" class="img-circle" /></td>
									<td>'.ucwords($stores->store).'</td>
									<td>'.ucwords($owner_name).'</td>
									<td>'.$ptype.'</td>
									<td>'.date('d M, Y', strtotime($pstart)).'</td>
									<td>'.date('d M, Y', strtotime($pend)).'</td>
									<td>'.$pstatus.'</td>
									<td class="text-center">
										<div class="btn-group btn-group-xs">
											'.$role_btn.'
											'.$del_btn.'
										</div>
									</td>
								</tr>
							';
						}
					}
				}
			?>
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-4 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Premium</strong> Accounts</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_account; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-4 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Premium</strong> Stores</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $paid_stores; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-4 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Stores</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_stores; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All expenses Block -->
            <div class="block full">
                <!-- All expenses Title -->
                <div class="block-title">
                    <h2><i class="gi gi-shopping_cart"></i> <strong>Manage</strong> Stores</h2>
                </div>
                <!-- END All expenses Title -->
                
                <?php if(!empty($rec_del) && !empty($rec_del_u)){ ?>
                	<?php echo form_open_multipart('admin/stores/remove'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <input type="hidden" name="del_u_id" value="<?php echo $rec_del_u; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } else if(!empty($active_edit)){ ?>
                	<?php echo form_open_multipart('admin/stores/edit'); ?>
                    	<div class="col-lg-12">
                        	<h3>Manage Store Premium Account</h3><br />
                            <input type="hidden" name="premium_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                            <input type="hidden" name="user_id" value="<?php if(!empty($e_user_id)){echo $e_user_id;}else{echo $get_user;} ?>" />
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="type">Select Subscription</label>
                                <div class="col-md-9">
                                    <?php $types = array('Free', 'Starter', 'Business', 'Pro'); ?>
                                     <select id="type" name="type" class="select-chosen" data-placeholder="Select Subscription">
                                        <option></option>
                                        <?php
                                            foreach($types as $key=>$value) {
                                                if(!empty($e_type)) {
                                                    if($e_type == $value) {
                                                        $t_sel = 'selected="selected"';
                                                    } else {$t_sel = '';}
                                                } else {$t_sel = '';}
                                                echo '<option value="'.$value.'" '.$t_sel.'>'.$value.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="start_date">Validity</label>
                                <div class="col-md-9">
                                    <div class="input-group input-daterange" data-date-format="mm/dd/yyyy">
                                        <input type="text" id="start_date" name="start_date" class="form-control text-center" placeholder="Start date" value="<?php if(!empty($e_start_date)){echo $e_start_date;} ?>" required="required">
                                        <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                        <input type="text" id="due_date" name="due_date" class="form-control text-center" placeholder="Due date" value="<?php if(!empty($e_due_date)){echo $e_due_date;} ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="status">Status</label>
                                <div class="col-md-9">
                                     <?php
									 	if(!empty($e_status)){
											if($e_status == 'Expired'){
												$s1 = 'selected="selected"';
												$s2 = '';
											} else if($e_status == 'Activate') {
												$s1 = '';
												$s2 = 'selected="selected"';
											}
										} else {$s1 = ''; $s2 = '';}
									 ?>
                                     <select id="status" name="status" class="select-chosen" data-placeholder="Select Status">
                                        <option></option>
                                        <option value="0" <?php echo $s1; ?>>Expired</option>
                                        <option value="1" <?php echo $s2; ?>>Activate</option>
                                    </select><br /><br />
                                </div>
                            </div>
                            <div class="form-group form-actions">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <!-- All expenses Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th width="60px" class="hidden-xs text-center">Logo</th>
                            <th>Store</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Starts</th>
                            <th>Ends</th>
                            <th>Status</th>
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