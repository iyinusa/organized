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
                            <h2><i class="gi gi-money"></i>&nbsp;Manage Dialy Expenses
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('expenses/add', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <input type="hidden" name="expense_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_store_id">Outlet</label>
                                <div class="col-md-9">
									<?php
                                        $store_list='';
                                        $cat_list='';
										$cus_list='';
                                        if(!empty($allstores)){
                                            foreach($allstores as $stores){
                                                $allstore = $this->m_stores->query_store_id($stores->store_id);
												if(!empty($allstore)){
													foreach($allstore as $store){
                                                        if(!empty($e_store_id)){
                                                            if($e_store_id == $store->id){
                                                                $st_sel = 'selected="selected"';	
                                                            } else {$st_sel = '';}
                                                        } else {$st_sel = '';}
                                                        $store_list .= '<option value="'.$store->id.'" '.$st_sel.'>'.$store->store.'</option>';
														
														//query contacts
														$get_contact = $this->m_customers->query_customer($store->id);
														if(!empty($get_contact)){
															foreach($get_contact as $contact){
																//get group name
																$get_group = $this->m_customers->query_group_id($contact->group_id);
																if(!empty($get_group)){
																	foreach($get_group as $g){
																		$contact_group = $g->name;	
																	}
																} else { $contact_group = ''; }
																
																if($contact_group == 'Supplier'){
																	if(!empty($e_supply_id)){
																		if($e_supply_id == $contact->supplier_id){
																			$sup_sel = 'selected="selected"';
																		} else {$sup_sel = '';}
																	} else {$sup_sel = '';}
																	
																	$cus_list .= '<option value="'.$contact->id.'" '.$sup_sel.'>'.$contact->firstname.' '.$contact->lastname.' ['.$store->store.']</option>';
																}
															}
														}
													}
												}
                                            }
                                        }
                                    ?>
                                    <select id="s_store_id" name="s_store_id" class="select-chosen" data-placeholder="Select Outlet" onchange="get_category(this.value, 0);" required>
                                        <option></option>
                                        <?php echo $store_list; ?>
                                    </select>
                            	</div>
                           	</div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_cat_id">Category</label>
                                <div id="cat_list" class="col-md-9">
                                    <select id="s_cat_id" name="s_cat_id" class="select-chosen" data-placeholder="Select Outlet First">
                                        <option></option>
                                    </select>
                            	</div>
                           	</div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_date">Assign Supplier (if applicable)</label>
                                <div id="supplier_list" class="col-md-9">
                                    <select id="s_sup" name="s_sup" class="select-chosen" data-placeholder="Select Outlet First">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_date">Expense Date</label>
                                <div class="col-md-9">
                                    <input type="text" id="s_date" name="s_date" class="form-control input-datepicker" data-date-format="mm/dd/yyyy" placeholder="mm/dd/yyyy" value="<?php if(!empty($e_date)){echo $e_date;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_details">Details/Remarks</label>
                                <div class="col-md-9">
                                    <textarea id="s_details" name="s_details" class="form-control" rows="4" placeholder="Enter expense description" required="required"><?php if(!empty($e_details)){echo $e_details;} ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="s_price">Amount</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8358;</i></span>
                                        <input type="text" id="s_price" name="s_price" class="form-control" placeholder="1000" value="<?php if(!empty($e_price)){echo $e_price;} ?>">
                                        <span class="input-group-addon">.00</span>
                                    </div>
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

        <script>
			document.addEventListener("DOMContentLoaded", function(event) {
				var expense_id = '<?php if(!empty($e_id)){echo $e_id;} ?>';
				var store_id = '<?php if(!empty($e_store_id)){echo $e_store_id;} ?>';

				if(store_id != '' && expense_id != '') {
					get_category(store_id, expense_id);
				}
			});

			function get_category(x, y) {
				$.ajax({
					url: '<?php echo base_url(); ?>expenses/get_category/' + x + '/' + y,
					success: function(data) {
						var dt = JSON.parse(data);
						$('#cat_list').html(dt.category);
                        $('#supplier_list').html(dt.supplier);
					},
					complete: function () { selecting(); }
				});
			}

			function selecting() {
				$('.select-chosen').chosen({width: "100%"});
			}
		</script>