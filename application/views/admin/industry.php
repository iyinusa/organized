<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_industry = 0;
				$unused_industry = 0;
				if(!empty($allindustry)){
					if(!empty($allindustry)){
						foreach($allindustry as $industry){
							$total_industry += 1; //count industry
							
							if($log_user_role == 'administrator'){
								$role_btn = '<a href="'.base_url('admin/industry?upd='.$industry->id.'').'" data-toggle="tooltip" title="Edit Industry" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
								$del_btn = '<a href="'.base_url('admin/industry/remove?id='.$industry->id.'').'" data-toggle="tooltip" title="Delete Industry" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>';
							} else {
								$role_btn = '';
								$del_btn = '';
							}
							
							$dir_list .= '
								<tr>
									<td>'.$industry->industry.'</td>
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
                <div class="col-sm-12 col-lg-12">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Industries</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_industry; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All expenses Block -->
            <div class="block full">
                <!-- All expenses Title -->
                <div class="block-title">
                    <h2><i class="gi gi-building"></i> <strong>Manage</strong> Industries</h2>
                </div>
                <!-- END All expenses Title -->
                
                <?php if(!empty($rec_del)){ ?>
                	<?php echo form_open_multipart('admin/industry/remove'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <div >
                	<?php echo form_open_multipart('admin/industry/'); ?>
                    	<div class="col-lg-12">
                        	<h3>Add/Update Industry</h3>
                            <input type="hidden" name="industry_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="industry">Industry Name</label>
                                <div class="col-md-9">
                                     <input type="text" id="industry" name="industry" class="form-control" value="<?php if(!empty($e_industry)){echo $e_industry;} ?>" />
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
                </div>
                
                <!-- All expenses Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Industry</th>
                            <th class="text-center" width="70px">Action</th>
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