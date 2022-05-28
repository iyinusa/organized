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
													$cust_list .= '<option value="'.$custname->id.'" '.$c_sel.'>'.$custname->title.' '.$custname->firstname.' '.$custname->lastname.'</option>';
												}
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
													$pdt_list .= '<option value="'.$pdt->id.'" '.$p_sel.'>'.$pdt->name.'</option>';
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
													$serv_list .= '<option value="'.$ser->id.'" '.$ser_sel.'>'.$ser->name.'</option>';
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
                                    </div>
                                    
                                    <script type="text/javascript">
                                        function get_pdt_amt(pid){
                                            var hr = new XMLHttpRequest();
                                            var id = document.getElementById('pdt_id').value;
                                            var qty = document.getElementById('qty').value;
                                            var discount = document.getElementById('discount').value;
                                            var type = document.getElementById('type').value;
                                            var c_vars = "id="+id+"&qty="+qty+"&discount="+discount+"&type="+type+"&tb="+pid;
                                            hr.open("POST", "<?php echo base_url() ?>invoices/get_amt", true);
                                            hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                            hr.onreadystatechange = function() {
                                                if(hr.readyState == 4 && hr.status == 200) {
                                                    var return_data = hr.responseText;
                                                    document.getElementById("amt_display").innerHTML = return_data;
                                               }
                                            }
                                            hr.send(c_vars);
                                            document.getElementById("amt_display").innerHTML = "<i class=\"fa fa-spinner fa-spin fa-2x\"></i>";
                                        }
                                        
                                        function cal_pdt_amt(){
                                            var hr = new XMLHttpRequest();
                                            var qty = document.getElementById('qty').value;
                                            var discount = document.getElementById('discount').value;
                                            var type = document.getElementById('type').value;
                                            var c_vars = "qty="+qty+"&discount="+discount+"&type="+type;
                                            hr.open("POST", "<?php echo base_url() ?>invoices/cal_amt", true);
                                            hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                            hr.onreadystatechange = function() {
                                                if(hr.readyState == 4 && hr.status == 200) {
                                                    var return_data = hr.responseText;
                                                    document.getElementById("amt_display").innerHTML = return_data;
                                               }
                                            }
                                            hr.send(c_vars);
                                            document.getElementById("amt_display").innerHTML = "<i class=\"fa fa-spinner fa-spin fa-2x\"></i>";
                                        }
                                    </script><br />
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="start_date">Validity</label>
                                        <div class="col-md-8">
                                            <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
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
                                                <th style="width:80px;"></th>
                                                <th>Pdt./Serv.</th>
                                                <th style="width:50px;" class="text-center">Qty.</th>
                                                <th class="text-center">Price</th>
                                                <th style="width:50px;" class="text-center">Discount</th>
                                                <th style="width:50px;" class="text-center">Type</th>
                                                <th class="text-center">Sub-Total</th>
                                                <th style="width: 150px;" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbParent">
                                            <tr class="tbChild">
                                                <td class="text-center sno">1</td>
                                                <td class="text-center">
                                                	<style>
														#div2 { display:none; }
													</style>
													<?php
														if(empty($e_pdt_id) && empty($e_service_id)){
															$pdt_act='active';$ser_act='';
														} else {
															if($e_pdt_id==1){$pdt_act='active';$ser_act='';}else{$pdt_act='';$ser_act='active';}
														}
													?>
                                                    <div class="form-group">
                                                        <div class="btn-group btn-group-justified btn-group-xs">
                                                            <div class="btn-group btn-group-xs">
                                                              <button type="button" id="div1_click" class="btn btn-primary btn-xs <?php echo $pdt_act; ?>" data-toggle="button"><i class="gi gi-cargo"></i></button>
                                                            </div>
                                                    
                                                            <div class="btn-group btn-group-xs">
                                                               <button type="button" id="div2_click" class="btn btn-primary btn-xs <?php echo $ser_act; ?>"><i class="gi gi-tablet"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
													<div id="div1">
                                                        <select id="pdt_id" name="pdt_id[]" class="form-control solsoSelect2 solsoCloneSelect2" data-placeholder="Select Product" onchange="get_pdt_amt(0);">
                                                            <option></option>
                                                            <?php echo $pdt_list; ?>
                                                        </select>
                                                    </div>
                                                    <div id="div2">
                                                    	<select id="service_id" name="service_id[]" class="select-chosen" data-placeholder="Select Service" onchange="get_pdt_amt(1);">
                                                            <option></option>
                                                            <?php echo $serv_list; ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" id="qty" name="qty[]" class="form-control text-center" value="<?php if(!empty($e_qty)){echo $e_qty;} ?>" onchange="cal_pdt_amt();">
                                                </td>
                                                <td class="text-right">
                                                	0.00
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" id="discount" name="discount[]" class="form-control text-center" value="<?php if(!empty($e_discount)){echo $e_discount;} ?>" onchange="cal_pdt_amt();">
                                                </td>
                                                <td class="text-center">
                                                    <?php
														if(!empty($e_type)){
															if($e_type=='Amount'){$t1='selected="selected"';}else{$t1='';}
															if($e_type=='Percent'){$t2='selected="selected"';}else{$t2='';}
														} else {$t1=''; $t2='';}
													?>
													<select id="type" name="type[]" class="select-chosen" data-placeholder="Select Type" onchange="cal_pdt_amt();">
														<option></option>
														<option value="Amount" <?php echo $t1; ?>>Amount</option>
														<option value="Percent" <?php echo $t2; ?>>Percent</option>
													</select>
                                                </td>
                                                <td class="text-right">
                                                	<span class="tbSubTotal">0.00</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger disabled removeClone"><i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
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
                                        <label class="col-md-4 control-label" for="amt">Total</label>
                                        <div class="col-md-8">
                                            <div class="text-right">
                                                <h2>
                                                    &#8358;
                                                    <span id="amt_display"><?php if(!empty($e_amt)){echo $e_amt;}else{echo '0.00';} ?></span>
                                                    <input type="hidden" name="amt" id="amt" />
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group form-actions">
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