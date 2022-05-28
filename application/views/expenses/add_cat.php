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
                            <h2><i class="gi gi-money"></i>&nbsp;Manage Expense Category
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
    
                        <!-- Meta Data Content -->
                        <?php if($rec_del!='' && $rec_del_s!=''){$edit='?r='.$rec_del.'&s='.$rec_del_s.'';}else{$edit='';} ?>
                        <?php echo form_open_multipart('expenses/add_cat'.$edit, array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <?php if($rec_del!='' && $rec_del_s!=''){ ?>
                                <div class="col-lg-12 bg-info">
                                    <h3>Are you sure? - Record will be totally remove from the system</h3>
                                    <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                                    <input type="hidden" name="del_s_id" value="<?php echo $rec_del_s; ?>" />
                                    <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                                    <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                                </div>
                            <?php } ?>
                            
                            <div class="col-sm-4">
                            	<input type="hidden" name="cat_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                                <div class="form-group">
                                    <label class="control-label" for="store_name">Outlet</label><br />
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
								<div class="form-group">
                                    <label class="control-label" for="type">Type</label><br />
									<?php $etypes = array('Cost of Sales', 'Operating Expenses'); ?>
									<select id="type" name="type" class="select-chosen" data-placeholder="Select Type" required>
										<option></option>
										<?php 
											foreach($etypes as $key=>$value){ 
												if(!empty($e_type)) {
													if($e_type == $value) {$et_sel = 'selected="selected"';} else {$et_sel = '';}
												} else {$et_sel = '';}
												echo '<option value="'.$value.'" '.$et_sel.'>'.$value.'</option>';
											}
										?>
									</select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="cat">Category</label><br/>
                                    <input type="text" id="cat" name="cat" class="form-control" placeholder="Enter category" value="<?php if(!empty($e_cat)){echo $e_cat;} ?>">
                                </div>
                                <div class="form-group form-actions" <?php echo $command; ?>>
                                    <div class="col-md-9 col-md-offset-3">
                                        <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-8">
                            	<?php
									$dir_list = '';
									if(!empty($allstores)){
										foreach($allstores as $stores){
											$allstore = $this->m_stores->query_store_id($stores->store_id);
											if(!empty($allstore)){
												foreach($allstore as $store){
													//query category
													$qc = $this->m_expenses->query_expense_cat($store->id);
													if(!empty($qc)){
														foreach($qc as $c){
															$dir_list .= '
																<tr>
																	<td>'.$store->store.'</td>
																	<td>'.$c->type.'</td>
																	<td>'.$c->cat.'</td>
																	<td class="text-center">
																		<div class="btn-group btn-group-xs">
																			<a href="'.base_url('expenses/add_cat?e='.$c->id.'&s='.$store->id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																			<a href="'.base_url('expenses/add_cat?r='.$c->id.'&s='.$store->id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
								?>
								
                                <!-- All Products Content -->
								<table id="ecom-products" class="table table-bordered table-striped table-vcenter">
									<thead>
										<tr>
											<th>Outlet</th>
											<th>Type</th>
											<th>Category</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php echo $dir_list; ?>
									</tbody>
								</table><br />
								<!-- END All Products Content -->
                            </div>
                            
                        <?php echo form_close(); ?>
                        <!-- END Meta Data Content -->
                    </div>
                    <!-- END Meta Data Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->