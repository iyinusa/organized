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
                <div class="block-title" style="background-color:#025E89; color:#fff;">
                    <div class="block-options pull-right btn-group" style="padding-top:10px; padding-right:10px; font-weight:bolder;">
                        <a href="javascript:void(0)" onclick="App.pagePrint();" class="btn btn-default btn-xs"><i class="fa fa-archive"></i></i> SAVE</a>
                        <a href="javascript:void(0)" class="btn btn-info btn-xs" onclick="send_invoice();"><i class="fa fa-angle-right"></i> SEND INVOICE</a>
                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="App.pagePrint();"><i class="fa fa-print"></i> PRINT</a>
                    </div>
                    <h4><span style="font-size:80%;"><strong>INVOICE ID:</strong> #<?php if(!empty($invoice_id)){echo $invoice_id;} ?></span></h4>
                    <input type="hidden" id="invoice_id" value="<?php if(!empty($invoice_id)){echo $invoice_id;} ?>" />
                </div>
                <!-- END Invoice Title -->

                <!-- Invoice Content -->
                <!-- 2 Column grid -->
                <div class="row block-section">
                    <!-- Company Info -->
                    <div class="col-xs-6" style="">
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
                    <div class="col-xs-6 text-right" style="">
                        <div class="col-xs-11 text-right">
                            <h4><span style="text-transform:uppercase; font-weight:900;"><?php if(!empty($firstname)){echo $firstname;} ?> <?php if(!empty($lastname)){echo $lastname;} ?></span></h4>
                        <address>
                            <?php if(!empty($address)){echo $address;} ?><br>
                            <?php if(!empty($state)){echo $state;} ?><br /><?php if(!empty($country)){echo $country;} ?><br>
                            <i class="fa fa-phone-square"></i> <?php if(!empty($phone)){echo $phone;} ?> |
                            <i class="fa fa-envelope-square"></i> <a href="mailto:<?php if(!empty($email)){echo $email;} ?>"><?php if(!empty($email)){echo $email;} ?></a>
                        </address>
                        </div>
                        <!--<div class="col-xs-3">
                            <img src="<?php if(!empty($pics_small)){echo base_url($pics_small);} ?>" alt="photo" class="img-square" height="100">
                        </div>-->
                        
                    </div>
                    <!-- END Client Info -->
                </div>
                <!-- END 2 Column grid -->

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-condensed inv_table" style="width:100%;">
                        <thead style="background-color:#025E89; color:#fff; text-transform:uppercase;">
                            <tr>
                                <th></th>
                                <th><h4><b><span style="font-size:85%;">Product/Services</span></b></h4></th>
                                <th class="text-center"><h4><b><span style="font-size:85%;">Qty</span></b></h4></th>
                                <th class="text-center"><h4><b><span style="font-size:85%;">Unit Price</span></b></h4></th>
                                <th class="text-right"><h4><b><span style="font-size:85%; padding-right:10px;">Amount</span></b></h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($invoice_items)){ ?>
                            	<?php $item_no = 1; $sub = 0; $each_amt = 0; ?>
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
										$each_amt = $item->qty * $item->amt;
									?>
                                    
                                    <tr style="border: 10px solid #fff; background-color:rgba(0, 125, 255, 0.1);">
                                        <td class="text-center"><?php if(!empty($item_no)){echo $item_no;} ?></td>
                                        <td>
                                            <h4><?php if(!empty($ps_name)){echo $ps_name;} ?><br /><small><?php if(!empty($ps_details)){echo $ps_details;} ?></small></h4>
                                        </td>
                                        <td class="text-center"><strong>x <span class="badge"><?php echo $item->qty; ?></span></strong></td>
                                        <td class="text-center"><strong>&#8358; <?php if(!empty($ps_price)){echo number_format($ps_price,2);} ?></strong></td>
                                        <td class="text-right"><span class="label label-primary">&#8358; <?php echo number_format($each_amt,2); ?></span></td>
                                    </tr>
                                    
                                    <?php $item_no += 1; ?>
                                <?php } ?>
                            <?php } ?>
                            
                            <tr class="">
                                <td colspan="3">
                                	<span class="h4" style="border-top:2px solid #025E89; display:block; padding:5px 0px; font-weight:bolder; font-size:90%;">
                                    	<b>DETAILS:</b><br /><br/>
										<?php echo $invoice_details; ?>
                                    </span>
                                </td>
                                <td class="text-right"><span class="h4" style="border-top:2px solid #025E89; border-bottom:2px solid #025E89; display:block; padding:5px 0px; font-weight:bolder; font-size:90%;">SUBTOTAL</span></td>
                                <td class="text-right"><span class="h4" style="border-top:2px solid #025E89; border-bottom:2px solid #025E89; display:block; padding:5px 0px; font-weight:bolder; font-size:90%;">&#8358; <?php echo number_format($sub,2); ?></span></td>
                            </tr>
                            <!--<tr class="active">
                                <td colspan="4" class="text-right"><span class="h4">VAT RATE</span></td>
                                <td class="text-right"><span class="h4">25%</span></td>
                            </tr>
                            <tr class="active">
                                <td colspan="4" class="text-right"><span class="h4">VAT DUE</span></td>
                                <td class="text-right"><span class="h4">&#8358; 5.750</span></td>
                            </tr>-->
                            <tr style=" background-color:#025E89; color:#fff;">
                                <td colspan="3"></td>
                                <td class="text-right"><span class="h4" style="font-weight:bolder; font-size:14px;"><strong>TOTAL DUE</strong></span></td>
                                <td class="text-right"><span class="h4" style=""><strong>&#8358; <?php if(!empty($invoice_amt)){echo number_format($invoice_amt,2);} ?></strong></span></td>
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
						var c_vars = "invoice_id="+invoice_id;
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