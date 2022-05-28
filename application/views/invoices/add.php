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
                            <h2><i class="gi gi-database_plus"></i>&nbsp;Manage Sales</h2>
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <h4><b>PLEASE NOTE:</b> All fields are required, except Discount and its Type which are optional</h4><br />
                        
                        <?php
							$store_list='';
							$store_name = '';
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
                                        <label class="col-md-3 control-label" for="store_id">Select Outlet</label>
                                        <div class="col-md-9">
                                            <select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Outlet" onchange="get_customer(this.value, 0);" required>
                                                <option></option>
                                                <?php echo $store_list; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="client_id">Client/Customer</label>
                                        <div class="col-md-9">
											<div id="clients_list"></div>
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
                                                    if($e_status=='Credit'){$s4='selected="selected"';}else{$s4='';}
                                                    if($e_status=='Cancelled'){$s5='selected="selected"';}else{$s5='';}
                                                } else {$s1=''; $s2=''; $s3=''; $s4=''; $s5='';}
                                            ?>
                                            <select id="status" name="status" class="select-chosen" data-placeholder="Select Status" onchange="partial_payment();" required>
                                                <option></option>
                                                <option value="Unpaid" <?php echo $s1; ?>>Unpaid</option>
                                                <option value="Partially Paid" <?php echo $s2; ?>>Partially Paid</option>
                                                <option value="Paid" <?php echo $s3; ?>>Paid</option>
                                                <option value="Credit" <?php echo $s4; ?>>Credit</option>
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
                                                <th style="width:70px;" class="text-center">VAT(%)</th>
                                                <th class="text-center">Sub-Total</th>
                                                <th style="width: 150px;" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbParent">
                                            <tr class="tbChild">
                                                <td class="text-center sno">1</td>
                                                <td>
                                                    <input type="hidden" name="item_id[]">
                                                    <select name="products[]" class="form-control tbSelect2 tbCloneSelect2" data-placeholder="Select Product/Services">
                                                        <option>Select Product/Services</option>
                                                        
                                                        <optgroup class="product_list" label="Products">
                                                            <?php //echo $pdt_list; ?>
                                                        </optgroup>
                                                        <optgroup class="service_list" label="Services">
                                                            <?php //echo $serv_list; ?>
                                                        </optgroup>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" name="qty[]" class="form-control text-center tbEvent">
                                                </td>
                                                <td class="text-right">
                                                	<input type="text" name="price[]" class="form-control text-center required tbEvent" autocomplete="off">
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" name="discount[]" class="form-control text-center tbEvent" placeholder="10">
                                                </td>
                                                <td class="text-center">
													<select name="type[]" class="form-control tbEvent" data-placeholder="Select Type">
														<option></option>
														<option value="Amount">Amount</option>
														<option value="Percent">Percent</option>
													</select>
                                                </td>
                                                <td class="text-center">
                                                	<input type="text" name="vat[]" class="form-control text-center required tbEvent" autocomplete="off" placeholder="5">
                                                </td>
                                                <td class="text-right">
                                                	<h4 class="pull-right">
                                                        <span class="tbSubTotal">0.00</span>
                                                        <span style="display:none;" class="tbSubTotalNoVat">0.00</span>
                                                        <input type="hidden" name="SubTotal[]" id="SubTotal" />
                                                    </h4>
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
									<div id="partial_box" class="col-md-12" style="display:none;">
										<div class="form-group">
											<h4 class="text-primary"><u><b>PARTIAL PAYMENT</b></u></h4>
											<div class="row">
												<div class="col-sm-6">
													<label class="control-label" for="partial_pay" style="text-align:left;">Amount Recieved:</label><br />
													<input type="text" id="partial_pay" name="partial_pay" class="form-control text-center" placeholder="10000" value="<?php if(!empty($e_paid)){echo $e_paid;} ?>" oninput="get_balance();">
												</div>
												<div class="col-sm-6">
													<label class="control-label" for="partial_balance" style="text-align:left;">Balance Payable:</label><br />
													<input type="text" id="partial_balance" name="partial_balance" class="form-control text-center" placeholder="Balance" value="<?php if(!empty($e_balance)){echo '&#8358;'.number_format((float)$e_balance,2);} ?>" readonly>
												</div>
											</div>
										</div>
									</div>

                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="details" style="text-align:left;">Note:</label><br />
                                        <div class="col-md-12">
                                            <textarea id="details" name="details" class="form-control" rows="4" placeholder="Enter note"><?php if(!empty($e_details)){echo $e_details;} ?></textarea>
                                        </div>
                                    </div><br />
                                </div>
                                
                                <div class="col-lg-6">
									<div class="form-group">
                                        <label class="col-md-4 control-label"><h5><b>Sub-Total:</b></h5></label>
                                        <div class="col-md-8">
                                            <div class="text-right">
                                                <h5>
                                                    &#8358;<span class="tbSumSubTotal"><?php if(!empty($e_amount)){echo $e_amount;} else {echo '0.00';} ?></span>
                                                    <input type="hidden" id="tbSumSubTotal" name="SumSubTotal" />
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label"><h5><b>VAT:</b></h5></label>
                                        <div class="col-md-8">
                                            <div class="text-right">
                                                <h5>
                                                    &#8358;<span class="tbVat"><?php if(!empty($e_amount)){echo $e_amount;} else {echo '0.00';} ?></span>
                                                    <input type="hidden" name="sumvat" id="tbVat" />
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><h4><b>Total:</b></h4></label>
                                        <div class="col-md-8">
                                            <div class="text-right">
                                                <h4>
                                                    &#8358;<span class="tbSumTotal"><?php if(!empty($e_amount)){echo $e_amount;} else {echo '0.00';} ?></span>
                                                    <input type="hidden" name="SumTotal" id="tbSumTotal" />
                                                </h4>
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

		<script>
			document.addEventListener("DOMContentLoaded", function(event) {
				var invoice_id = '<?php if(!empty($e_id)){echo $e_id;} ?>';
				var store_id = '<?php if(!empty($e_store_id)){echo $e_store_id;} ?>';
				var client_id = '<?php if(!empty($e_client_id)){echo $e_client_id;} ?>';

				if(store_id != '' && client_id != '') {
					get_customer(store_id, client_id);
				}

				if(invoice_id != '') {
					get_invoice_items(invoice_id);
					partial_payment();
				}
			});

			function get_customer(x, y) {
				//check if it's edit
				var invoice_id = '<?php if(!empty($e_id)){echo $e_id;} ?>';

				$.ajax({
					url: '<?php echo base_url(); ?>invoices/get_customer/' + x + '/' + y,
					success: function(data) {
						var dt = JSON.parse(data);
						$('#clients_list').html(dt.cust);

						// only load product and service if new record
						if(invoice_id == '') {
							$('.product_list').html(dt.product);
							$('.service_list').html(dt.service);
						}
					},
					complete: function () { selecting(); }
				});
			}

			function get_invoice_items(x) {
				$('.tbParent').html('<h3 class="text-center text-muted"><i class="fa fa-spin fa-spinner"></i></h3>');

				$.ajax({
					url: '<?php echo base_url(); ?>invoices/get_invoice_items/' + x,
					success: function(data) {
						$('.tbParent').html(data);
					},
					complete: function () { compute_invoice(); }
				});
			}

			function partial_payment() {
				var status = $('#status').val();
				if(status == 'Partially Paid') {
					$('#partial_box').show();
				} else {
					$('#partial_box').hide();
				}
			}

			function get_balance() {
				var total = $('#tbSumTotal').val();
				var paid = $('#partial_pay').val();
				var bal = 0;

				bal = total - paid;
				$('#partial_balance').val('₦' + formatNumber((bal).toFixed(2)));
			}

			function selecting() {
				$('.select-chosen').chosen({width: "100%"});
			}
		</script>