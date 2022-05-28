<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_users = 0;
				$total_stores = 0;
				$industry = '';
				if(!empty($allusers)){
					if(!empty($allusers)){
						foreach($allusers as $users){
							$total_users += 1; //count users
							
							$ustore = $this->m_stores->query_user_store($users->id);
							if(!empty($ustore)){
								$total_stores += 1;
							}
							
							$user_pics_small = $users->pics_small;
							if($user_pics_small=='' || file_exists(FCPATH.$user_pics_small)==FALSE){$user_pics_small='img/icon57.png';}
									
							//get industry
							$uindus = $this->users->query_user_industry($users->industry_id);
							if(!empty($uindus)){
								foreach($uindus as $indus){
									$industry = $indus->industry;	
								}
							}
							
							if($log_user_role == 'administrator'){
								$role_btn = '<a href="'.base_url('admin/accounts/edit?user='.$users->id.'').'" data-toggle="tooltip" title="Edit Role" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
								$del_btn = '<a href="'.base_url('admin/accounts/remove?user='.$users->id.'').'" data-toggle="tooltip" title="Delete Account" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>';
							} else {
								$role_btn = '';
								$del_btn = '';
							}
							
							if($users->role == 'Administrator'){
								$bck_colour = 'class="alert alert-success"';	
							} else if($users->role == 'Support'){
								$bck_colour = 'class="alert alert-info"';	
							} else if($users->role == 'Client'){
								$bck_colour = 'class="alert alert-warning"';	
							} else if($users->role == 'Client Staff'){
								$bck_colour = 'class="alert alert-danger"';	
							} else {
								$bck_colour = '';
							}
							
							if($users->reg_hostname != ''){$reg_hostname = '('.$users->reg_hostname.')';}else{$reg_hostname='';}
							if($users->hostname_lastlog != ''){$hostname_lastlog = '('.$users->hostname_lastlog.')';}else{$hostname_lastlog='';}
							
							$dir_list .= '
								<tr '.$bck_colour.'>
									<td>'.date('d M, Y', strtotime($users->reg_date)).'</td>
									<td class="hidden-xs text-center"><img alt="" src='.base_url($user_pics_small).' width="30px" class="img-circle" /></td>
									<td>'.ucwords($users->title).' '.ucwords($users->firstname).' '.ucwords($users->lastname).'</td>
									<!--<td>'.$users->sex.'</td>-->
									<td>'.$users->reg_company.'</td>
									<td class="hidden-xs">'.$industry.'</td>
									<!--<td class="text-center hidden-xs">'.$users->reg_ip.$reg_hostname.'<hr/>'.$users->ip_lastlog.$hostname_lastlog.'</td>-->
									<td>'.$users->phone.'</td>
									<td>'.$users->email.'</td>
									<td>'.$users->role.'</td>
									<td class="text-center">
										<div class="btn-group btn-group-xs">
											'.$role_btn.'
											<a href="'.base_url('profile?user='.$users->id.'').'" data-toggle="tooltip" title="View Profile" class="btn btn-success"><i class="fa fa-eye"></i></a>
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
                <div class="col-sm-6 col-lg-6">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Store</strong> Owners</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_stores; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Accounts</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_users; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All expenses Block -->
            <div class="block full">
                <!-- All expenses Title -->
                <div class="block-title">
                    <h2><i class="gi gi-user"></i> <strong>Manage</strong> Accounts</h2>
                </div>
                <!-- END All expenses Title -->
                
                <?php if(!empty($rec_del)){ ?>
                	<?php echo form_open_multipart('admin/accounts/remove'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } else if(!empty($rec_role)){ ?>
                	<?php echo form_open_multipart('admin/accounts/edit'); ?>
                    	<div class="col-lg-12">
                        	<h3>Change Account Role</h3>
                            <input type="hidden" name="edit_id" value="<?php echo $rec_role; ?>" />
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="role">Select Role</label>
                                <div class="col-md-9">
                                     <select id="role" name="role" class="select-chosen" data-placeholder="Select Role" required>
                                        <option></option>
                                        <option value="User">User</option>
                                        <option value="Client">Client</option>
                                        <option value="Client Staff">Client Staff</option>
                                        <option value="Support">Support</option>
                                        <option value="Administrator">Administrator</option>
                                    </select>
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
                <table id="ecom-products" class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th width="70px">Date</th>
                            <th width="40px" class="hidden-xs text-center">Pics</th>
                            <th>Name</th>
                            <!--<th>Sex</th>-->
                            <th>Company</th>
                            <th class="hidden-xs">Industry</th>
                            <!--<th class="text-center hidden-xs">IP's</th>-->
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                    	<?php echo $dir_list; ?>
                    </tbody>
                </table>
                <!-- END All Products Content -->
            </div>
            <!-- END All Products Block -->
        </div>
        <!-- END Page Content -->