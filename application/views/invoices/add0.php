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
                            <h2><i class="gi gi-database_plus"></i>&nbsp;Manage Invoice
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
                        
                        <h4><b>PLEASE NOTE:</b> All fields are required, except Discount and its Type which are optional</h4><br />
                        
                        <?php
							$store_list='';
							$cust_list='';
							$pdt_list='';
							$serv_list='';
							if(!empty($allstores)){
								foreach($allstores as $stores){
									
									//query stores
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
									
									//query customers
									$allcust = $this->m_customers->query_store_customer($stores->store_id);
									if(!empty($allcust)){
										foreach($allcust as $cust){
											if(!empty($e_client_id)){
												if($e_client_id == $cust->id){
													$c_sel = 'selected="selected"';	
												} else {$c_sel = '';}
											} else {$c_sel = '';}
											
											//get customer name
											$allcustname = $this->users->query_single_user_id($cust->user_id);
											if(!empty($allcustname)){
												foreach($allcustname as $custname){
													$cust_list .= '<option value="'.$cust->user_id.'" '.$c_sel.'>'.$custname->title.' '.$custname->firstname.' '.$custname->lastname.'</option>';
												}
											}
										}
									}
									
									//query invoice items
									if(!empty($e_id)){
										$invitem = $this->m_invoices->query_invoice_item_id($e_id);
										if(!empty($invitem)){
											$item_no = 1; $others = '';
											foreach($invitem as $invi){
												if($item_no == 1){
													$e_item = $invi->id;
													if($invi->pdt_id != 0){$e_pdt_id = $invi->pdt_id;} else {$e_service_id = $invi->service_id;}
													$e_qty = $invi->qty;
													$e_price = $invi->amt;
													$e_discount = $invi->discount;
													$e_type = $invi->type;
													$e_sub = $invi->sub_total;
												} else {
													$others .= '
														<tr class="tbChild">
															<td class="text-center sno">'.$item_no.'</td>
															<td>
																<input type="hidden" name="item_id[]" value="'.$invi->id.'">
																<select name="products[]" class="form-control tbSelect2 tbCloneSelect2" data-placeholder="Select Product/Services">
																	<option>Select Product/Services</option>
																	<optgroup label="Products">
																		'.$pdt_list.'
																	</optgroup>
																	<optgroup label="Services">
																		'.$serv_list.'
																	</optgroup>
																</select>
															</td>
															<td class="text-center">
																<input type="text" name="qty[]" class="form-control text-center tbEvent" value="'.$invi->qty.'">
															</td>
															<td class="text-right">
																<input type="text" name="price[]" class="form-control text-center required tbEvent" autocomplete="off" value="'.$invi->amt.'">
															</td>
															<td class="text-center">
																<input type="text" name="discount[]" class="form-control text-center tbEvent" value="'.$invi->discount.'">
															</td>
															<td class="text-center">
																<select name="type[]" class="form-control tbEvent" data-placeholder="Select Type">
																	<option></option>
																	<option value="'.$invi->type.'">'.$invi->type.'</option>
																	<option value="Amount">Amount</option>
																	<option value="Percent">Percent</option>
																</select>
															</td>
															<td class="text-right">
																<h4 class="pull-right">
																	<span class="tbSubTotal">'.$invi->sub_total.'</span>
																	<input type="hidden" name="SubTotal[]" id="SubTotal" />
																</h4>
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger disabled removeClone"><i class="fa fa-minus"></i></button>
															</td>
														</tr>
													';
												}
												$item_no += 1;
											}
										}
									}
									
									//query products
									$allpdtcat = $this->m_products->query_product_cat($stores->store_id);
									if(!empty($allpdtcat)){
										foreach($allpdtcat as $pdtcat){
											$allpdt = $this->m_products->query_product($pdtcat->id);
											if(!empty($allpdt)){
												foreach($allpdt as $pdt){
													if(!empty($e_pdt_id)){
														if($e_pdt_id == $pdt->id){
															$p_sel = 'selected="selected"';	
														} else {$p_sel = '';}
													} else {$p_sel = '';}
													$pdt_list .= '<option value="0|'.$pdt->id.'" '.$p_sel.'>'.$pdt->name.'</option>';
												}
											}
										}
									}
									
									//query services
									$allsercat = $this->m_services->query_service_cat($stores->store_id);
									if(!empty($allsercat)){
										foreach($allsercat as $sercat){
											$allser = $this->m_services->query_service($sercat->id);
											if(!empty($allser)){
												foreach($allser as $ser){
													if(!empty($e_service_id)){
														if($e_service_id == $ser->id){
															$ser_sel = 'selected="selected"';	
														} else {$ser_sel = '';}
													} else {$ser_sel = '';}
													$serv_list .= '<option value="1|'.$ser->id.'" '.$ser_sel.'>'.$ser->name.'</option>';
												}
											}
										}
									}
								}
							}
						?>
    
                        <!-- Meta Data Content -->
                        <?php echo form_open_multipart('invoices/add', array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <input type="hidden" name="invoice_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="store_id">Select Store</label>
                                        <div class="col-md-9">
                                            <select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Store" required>
                                                <option></option>
                                                <?php echo $store_list; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="client_id">Client/Customer</label>
                                        <div class="col-md-9">
                                            <select id="client_id" name="client_id" class="select-chosen" data-placeholder="Select Client/Customer" required>
                                                <option></option>
                                                <?php echo $cust_list; ?>
                                            </select>
                                        </div>
                                    </div><br />
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="start_date">Validity</label>
                                        <div class="col-md-8">
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
                                                    if($e_status=='Unpaid'){$s1='selected="selected"';}else{$s1='';}
                                                    if($e_status=='Partially Paid'){$s2='selected="selected"';}else{$s2='';}
                                                    if($e_status=='Paid'){$s3='selected="selected"';}else{$s3='';}
                                                    if($e_status=='Overdue'){$s4='selected="selected"';}else{$s4='';}
                                                    if($e_status=='Cancelled'){$s5='selected="selected"';}else{$s5='';}
                                                } else {$s1=''; $s2=''; $s3=''; $s4=''; $s5='';}
                                            ?>
                                            <select id="status" name="status" class="select-chosen" data-placeholder="Select Status" required>
                                                <option></option>
                                                <option value="Unpaid" <?php echo $s1; ?>>Unpaid</option>
                                                <option value="Partially Paid" <?php echo $s2; ?>>Partially Paid</option>
                                                <option value="Paid" <?php echo $s3; ?>>Paid</option>
                                                <option value="Overdue" <?php echo $s4; ?>>Overdue</option>
                                                <option value="Cancelled" <?php echo $s5; ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div><br/>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="table-responsive" style="display:block; overflow-x:hidden; overflow-y:hidden">
                                    <table id="general-table" class="table table-striped table-hover table-vcenter">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;" class="text-center">S/N</th>
                                                <th>Products/Services</th>
                                                <th style="width:50px;" class="text-center">Qty.</th>
                                                <th style="width:100px;" class="text-center">Price</th>
                                                <th style="width:50px;" class="text-center">Discount</th>
                                                <th style="width:100px;" class="text-center">Type</th>
                                                <th class="text-center">Sub-Total</th>
                                                <th style="width: 150px;" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbParent">
                                            <tr class="tbChild">
                                                <td class="text-center sno">1</td>
                                                <td>
                                                    <input type="hidden" name="item_id[]" value="<?php if(!empty($e_item)){echo $e_item;} ?>">
                                                    <select name="products[]" class="form-control tbSelect2 tbCloneSelect2" data-placeholder="Select Product/Services">
                                                        <option>Select Product/Services</option>
                                                        <?php if(!empty($pdt_list)){ ?>
                                                        <optgroup label="Products">
                                                            <?php echo $pdt_list; ?>
                                                        </optgroup>
                                                        <?php } ?>
                                                        <?php if(!empty($serv_list)){ ?>
                                                        <optgroup label="Services">
                                                            <?php echo $serv_list; ?>
                                                        </optgroup>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" name="qty[]" class="form-control text-center tbEvent" value="<?php if(!empty($e_qty)){echo $e_qty;} ?>">
                                                </td>
                                                <td class="text-right">
                                                	<input type="text" name="price[]" class="form-control text-center required tbEvent" autocomplete="off" value="<?php if(!empty($e_price)){echo $e_price;} ?>">
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" name="discount[]" class="form-control text-center tbEvent" value="<?php if(!empty($e_discount)){echo $e_discount;} ?>">
                                                </td>
                                                <td class="text-center">
                                                    <?php
														if(!empty($e_type)){
															if($e_type=='Amount'){$t1='selected="selected"';}else{$t1='';}
															if($e_type=='Percent'){$t2='selected="selected"';}else{$t2='';}
														} else {$t1=''; $t2='';}
													?>
													<select name="type[]" class="form-control tbEvent" data-placeholder="Select Type">
														<option></option>
														<option value="Amount" <?php echo $t1; ?>>Amount</option>
														<option value="Percent" <?php echo $t2; ?>>Percent</option>
													</select>
                                                </td>
                                                <td class="text-right">
                                                	<h4 class="pull-right">
                                                        <span class="tbSubTotal"><?php if(!empty($e_sub)){echo $e_sub.'.00';}else{echo '0.00';} ?></span>
                                                        <input type="hidden" name="SubTotal[]" id="SubTotal" />
                                                    </h4>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger disabled removeClone"><i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
                                            <?php //if(!empty($others)){echo $others;} ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="form-group col-md-12 top20 text-center">
                                    <button type="button" class="btn btn-primary btn-lg" id="createClone"><i class="fa fa-plus"></i> Add Product/Services</button>
                                </div>
                          	</div>
                            
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="details">Details</label>
                                        <div class="col-md-9">
                                            <textarea id="details" name="details" class="form-control" rows="3" placeholder="Enter details"><?php if(!empty($e_details)){echo $e_details;} ?></textarea>
                                        </div>
                                    </div><br />
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Total</label>
                                        <div class="col-md-8">
                                            <div class="text-right">
                                                <h2>
                                                    &#8358;
                                                    <span class="tbTotal"><?php if(!empty($e_amount)){echo $e_amount.'.00';} ?></span>
                                                    <input type="hidden" class="tbTotal" name="tbTotal" id="tbTotal" />
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group form-actions" <?php echo $command; ?>>
                                        <div class="col-md-9 col-md-offset-3">
                                            <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                        </div>
                                    </div><br/><br/>
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