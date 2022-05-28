<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php //include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Invoice Block -->
            <div id="send_inv_reply" class="text-center"></div>
            <div class="block full">
                <!-- Invoice Title -->
                <div class="block-title" style="">
                    <div class="block-options pull-right btn-group" style="padding-top:10px; padding-right:10px; font-weight:bolder;">
                        
                        <a href="javascript:void(0)" class="btn btn-default btn-xs" onclick="send_invoice();"><i class="fa fa-envelope"></i> EMAIL INVOICE</a>
                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="App.pagePrint();"><i class="fa fa-print"></i> PRINT</a>
                    </div>
                    <h4><span style="font-size:80%;"><strong>INVOICE ID:</strong> #<?php if(!empty($invoice_id)){echo $invoice_id;} ?></span></h4>
                    <input type="hidden" id="invoice_id" value="<?php if(!empty($invoice_id)){echo $invoice_id;} ?>" />
                    <input type="hidden" id="invoice_store_id" value="<?php if(!empty($inv_store_id)){echo $inv_store_id;} ?>" />
                    <input type="hidden" id="invoice_cus_id" value="<?php if(!empty($inv_cus_id)){echo $inv_cus_id;} ?>" />
                    <input type="hidden" id="store_name" value="<?php if(!empty($store_name)){echo $store_name;} ?>" />
                </div>
                <!-- END Invoice Title -->

                <!-- Invoice Content -->
                <!-- 2 Column grid -->
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <span class="alert alert-<?php echo $invoice_status == 'Paid' ? 'success' : 'danger'; ?>"><b><?php if(!empty($invoice_status)){echo strtoupper($invoice_status);} ?></b></span>
                    </div>
                </div>
                <hr/>

                <div class="row block-section">
                    <!-- Company Info -->
                    <div class="col-sm-8" style="">
                        <div class="col-xs-3 text-right">
                            <img src="<?php if(!empty($store_img)){echo base_url($store_img);} ?>" alt="photo" class="img-square" height="100">
                        </div>
                        <div class="col-xs-9">
                            <h4><span style="text-transform:uppercase; font-weight:900;"><?php if(!empty($store_name)){echo $store_name;} ?></span></h4>
                            <address>
                                <?php if(!empty($store_address)){echo $store_address;} ?><br>
                                <?php if(!empty($store_city)){echo $store_city;} ?> <?php if(!empty($store_state)){echo $store_state;} ?><br>
                                <?php if(!empty($store_country)){echo $store_country;} ?><br>
                                <i class="fa fa-phone"></i> <?php if(!empty($store_phone)){echo $store_phone;} ?> |
                                <i class="fa fa-envelope-o"></i> <a href="mailto:<?php if(!empty($log_user_email)){echo $log_user_email;} ?>"><?php if(!empty($log_user_email)){echo $log_user_email;} ?></a>
                            </address>
                        </div>
                    </div>
                    <!-- END Company Info -->

                    <!-- Client Info -->
                    <div class="col-sm-4 text-right" style="">
                        <div class="col-xs-11 text-right">
                            <?php if(!empty($c_company)){ ?>
                            <h4><span style="text-transform:uppercase; font-weight:900;"><?php if(!empty($c_company)){echo $c_company;} ?></span></h4>
                            <span style="font-weight:300;"><?php if(!empty($firstname)){echo '<b>Contact:</b> '.$firstname;} ?> <?php if(!empty($lastname)){echo $lastname;} ?></span>
                            <?php } else { ?>
                            <h4><span style="text-transform:uppercase; font-weight:900;"><?php if(!empty($firstname)){echo $firstname;} ?> <?php if(!empty($lastname)){echo $lastname;} ?></span></h4>
                            <?php } ?>
                            <address>
                                <?php if(!empty($address)){echo $address;} ?><br>
                                <?php if(!empty($state)){echo $state;} ?><br /><?php if(!empty($country)){echo $country;} ?><br>
                                <i class="fa fa-phone-square"></i> <?php if(!empty($phone)){echo $phone;} ?> |
                                <i class="fa fa-envelope-square"></i> <a href="mailto:<?php if(!empty($email)){echo $email;} ?>"><?php if(!empty($email)){echo $email;} ?></a>
                                <input type="hidden" id="cus_email" value="<?php if(!empty($email)){echo $email;} ?>" />
                            </address>
                        </div>
                        
                    </div>
                    <!-- END Client Info -->
                </div>
                <!-- END 2 Column grid -->

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-condensed inv_table" style="width:100%;">
                        <thead style=text-transform:uppercase;">
                            <tr>
                                <th style="padding:5px;"></th>
                                <th style="padding:5px;"><h4><b><span style="font-size:85%;">Product/Services</span></b></h4></th>
                                <th class="text-center" style="padding:5px;"><h4><b><span style="font-size:85%;">Qty</span></b></h4></th>
                                <th class="text-center" style="padding:5px;"><h4><b><span style="font-size:85%;">Unit Price</span></b></h4></th>
                                <th class="text-right" style="padding:5px;"><h4><b><span style="font-size:85%; padding-right:10px;">Amount</span></b></h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $item_no = 1; $sub = 0; $each_amt = 0; $each_discount = 0; $discount_note = ''; ?>
                            <?php if(!empty($invoice_items)){ ?>
                            	<?php foreach($invoice_items as $item){ ?>
                                	<?php
										if($item->pdt_id != 0){
											$pdt_ser = $this->m_products->query_product_id($item->pdt_id); //get product
										} else {
											$pdt_ser = $this->m_services->query_service_id($item->service_id);	//get service
										}
										
										if(!empty($pdt_ser)){
											foreach($pdt_ser as $ps){
												$ps_name = $ps->name;
												$ps_price = $ps->price;
												$ps_details = $ps->details;	
											}
										}
										$sub += $item->sub_total;
                                        $each_amt = $item->sub_total;

                                        if($item->type == 'Percent') {
                                            $each_discount = $each_amt * ($item->discount / 100);
                                        } else {
                                            $each_discount = $item->discount;
                                        }

                                        if($each_discount > 0) {
                                            $discount_note = '<br/><small><i class="small">Discount: &#8358;'.number_format($each_discount,2).'</i></small>';
                                        } else {$discount_note = '';}

                                        // check vat
                                        $vat = $item->vat; $vat_value = $item->vat_value;
                                        if($vat && $vat_value) {
                                            $vat_note = '<br/><small><i class="small">VAT ('.$vat.'%): &#8358;'.number_format($vat_value,2).'</i></small>';
                                        } else {$vat_note = '';}
									?>
                                    
                                    <tr style="border: 5px solid #fff; background:rgba(237, 237, 237, 0.3);">
                                        <td class="text-center" style="padding:5px;"><?php if(!empty($item_no)){echo $item_no;} ?></td>
                                        <td style="padding:5px;">
                                            <h4><?php if(!empty($ps_name)){echo $ps_name;} ?><br /><small><?php if(!empty($ps_details)){echo $ps_details;} ?></small><?php echo $discount_note; ?><?php echo $vat_note; ?></h4>
                                        </td>
                                        <td class="text-center" style="padding:5px;"><strong>x <span class="badge"><?php echo $item->qty; ?></span></strong></td>
                                        <td class="text-center" style="padding:5px;">&#8358;<?php if(!empty($ps_price)){echo number_format($ps_price,2);} ?></td>
                                        <td class="text-right" style="padding:5px;"><strong>&#8358;<?php echo number_format($each_amt,2); ?></strong></td>
                                    </tr>
                                    
                                    <?php $item_no += 1; ?>
                                <?php } ?>
                            <?php } ?>
                            
                            <tr class="">
                                <td colspan="3">
                                	<div class="h4" style="padding:5px 0px; vertical-align:top;">
                                    	<b>NOTE:</b><br /><br/>
										<?php echo $invoice_details; ?>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div style="padding:5px;">
                                        <h4>SUB-TOTAL:</h4>
                                    </div>

                                    <div style="padding:5px;">
                                        <h4>VAT:</h4>
                                    </div>

                                    <div style="padding:5px;">
                                        <h4>TOTAL:</h4>
                                    </div>

                                    <?php if($invoice_status == 'Partially Paid'){ ?>
                                    <div style="padding:5px;">
                                        <h4>PAID:</h4>
                                    </div>

                                    <div style="padding:5px;">
                                        <h4>BALANCE:</h4>
                                    </div>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
                                        <h4>&#8358;<?php echo number_format($sub,2); ?></h4>
                                    </div>

                                    <div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
                                        <h4>&#8358;<?php echo number_format($invoice_vat,2); ?></h4>
                                    </div>

                                    <div style="background:rgba(237, 237, 237, 0.4); padding:5px;">
                                        <h4><b>&#8358;<?php echo number_format($invoice_amt,2); ?></b></h4>
                                    </div>

                                    <?php if($invoice_status == 'Partially Paid'){ ?>
                                    <div style="background:rgba(237, 237, 237, 0.4); padding:5px; border-bottom:1px solid #ddd;">
                                        <h4>&#8358;<?php echo number_format($invoice_paid,2); ?></h4>
                                    </div>

                                    <div style="background:rgba(237, 237, 237, 0.4); padding:5px;">
                                        <h4><b>&#8358;<?php echo number_format($invoice_balance,2); ?></b></h4>
                                    </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END Table -->

                <!--<div class="clearfix">
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0)" onclick="App.pagePrint();" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Save</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm"><i class="fa fa-angle-right"></i> Send Invoice</a>
                    </div>
                </div>-->
                <!-- END Invoice Content -->
                <script type="text/javascript">
					function send_invoice(){
						var hr = new XMLHttpRequest();
						var invoice_id = document.getElementById('invoice_id').value;
						var invoice_store_id = document.getElementById('invoice_store_id').value;
						var invoice_cus_id = document.getElementById('invoice_cus_id').value;
						var cus_email = document.getElementById('cus_email').value;
						var store_name = document.getElementById('store_name').value;
						var c_vars = "invoice_id="+invoice_id+"&invoice_store_id="+invoice_store_id+"&invoice_cus_id="+invoice_cus_id+"&cus_email="+cus_email+"&store_name="+store_name;
						hr.open("POST", "<?php echo base_url('invoices/send_invoice'); ?>", true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						hr.onreadystatechange = function() {
							if(hr.readyState == 4 && hr.status == 200) {
								var return_data = hr.responseText;
								document.getElementById("send_inv_reply").innerHTML = return_data;
						   }
						}
						hr.send(c_vars);
						document.getElementById("send_inv_reply").innerHTML = "<span class='text-center'><i class=\"fa fa-spinner fa-spin fa-2x\"></i> sending...</span>";
					}
				</script>
            </div>
            <!-- END Invoice Block -->
            
        </div>
        <!-- END Page Content -->