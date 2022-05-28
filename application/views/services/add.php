<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <div class="row">
                <div class="col-lg-12">
                    <!-- Meta Data Block -->
                    <div class="block">
                        <!-- Meta Data Title -->
                        <div class="block-title">
                            <h2><i class="gi gi-tablet"></i>&nbsp;Manage Service
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('services/add', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <input type="hidden" name="service_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_cat_id">Category</label>
                                <div class="col-md-9">
									<?php
                                        $cat_list='';
                                        if(!empty($allstores)){
                                            foreach($allstores as $stores){
                                                $allstore = $this->m_stores->query_store_id($stores->store_id);
												if(!empty($allstore)){
													foreach($allstore as $store){
														//query categories
														$qc = $this->m_services->query_service_cat($store->id);
														if(!empty($qc)){
															foreach($qc as $c){
																if(!empty($e_cat_id)){
																	if($e_cat_id == $c->id){
																		$c_sel = 'selected="selected"';	
																	} else {$c_sel = '';}
																} else {$c_sel = '';}
																$cat_list .= '<option value="'.$c->id.'" '.$c_sel.'>'.$c->cat.' ['.$store->store.']</option>';
															}
														}
													}
												}
                                            }
                                        }
                                    ?>
                                    <select id="s_cat_id" name="s_cat_id" class="select-chosen" data-placeholder="Select Category" required>
                                        <option></option>
                                        <?php echo $cat_list; ?>
                                    </select>
                            	</div>
                           	</div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_name">Service Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="s_name" name="s_name" class="form-control" placeholder="Enter service name" required="required" value="<?php if(!empty($e_name)){echo $e_name;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_details">Description</label>
                                <div class="col-md-9">
                                    <textarea id="s_details" name="s_details" class="form-control" rows="4" placeholder="Enter service description" required="required"><?php if(!empty($e_details)){echo $e_details;} ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_price">Price</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8358;</i></span>
                                        <input type="text" id="s_price" name="s_price" class="form-control" placeholder="1000" value="<?php if(!empty($e_price)){echo $e_price;} ?>">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="logo">Photo</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="logo" value="<?php if(!empty($e_img_id)){echo $e_img_id;} ?>"/>
                                    <input type="file" id="pics" name="pics" class="form-control">
                                </div>
                            </div>
                            <div class="form-group form-actions" <?php echo $command; ?>>
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <!-- END Meta Data Content -->
                    </div>
                    <!-- END Meta Data Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->