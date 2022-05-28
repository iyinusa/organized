<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php //include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Invoice Block -->
            <div class="block full">
                <!-- Invoice Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="App.pagePrint();"><i class="fa fa-print"></i> Print</a>
                    </div>
                    <h2><strong>Invoice ID:</strong> #<?php if(!empty($invoice_id)){echo $invoice_id;} ?></h2>
                </div>
                <!-- END Invoice Title -->

                <!-- Invoice Content -->
                <!-- 2 Column grid -->
                <div class="row block-section">
                    <!-- Company Info -->
                    <div class="col-xs-6">
                        <img src="<?php if(!empty($store_img)){echo base_url($store_img);} ?>" alt="photo" class="img-circle" height="100">
                        <hr>
                        <h2><strong><?php if(!empty($store_name)){echo $store_name;} ?></strong></h2>
                        <address>
                            <?php if(!empty($store_address)){echo $store_address;} ?><br>
                            <?php if(!empty($store_city)){echo $store_city;} ?> <?php if(!empty($store_state)){echo $store_state;} ?><br>
                            <?php if(!empty($store_country)){echo $store_country;} ?><br><br>
                            <i class="fa fa-phone"></i> <?php if(!empty($store_phone)){echo $store_phone;} ?><br>
                            <i class="fa fa-envelope-o"></i> <a href="mailto:<?php if(!empty($log_user_email)){echo $log_user_email;} ?>"><?php if(!empty($log_user_email)){echo $log_user_email;} ?></a>
                        </address>
                    </div>
                    <!-- END Company Info -->

                    <!-- Client Info -->
                    <div class="col-xs-6 text-right">
                        <img src="<?php if(!empty($pics_small)){echo base_url($pics_small);} ?>" alt="photo" class="img-circle" height="100">
                        <hr>
                        <h2><strong><?php if(!empty($firstname)){echo $firstname;} ?> <?php if(!empty($lastname)){echo $lastname;} ?></strong></h2>
                        <address>
                            <?php if(!empty($address)){echo $address;} ?><br>
                            <?php if(!empty($state)){echo $state;} ?><br /><?php if(!empty($country)){echo $country;} ?><br><br>
                            <?php if(!empty($phone)){echo $phone;} ?> <i class="fa fa-phone"></i><br>
                            <a href="mailto:<?php if(!empty($email)){echo $email;} ?>"><?php if(!empty($email)){echo $email;} ?></a> <i class="fa fa-envelope-o"></i>
                        </address>
                    </div>
                    <!-- END Client Info -->
                </div>
                <!-- END 2 Column grid -->

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-vcenter" style="width:100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product/Services</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-right">Amount</th>
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
                                    
                                    <tr>
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
                            
                            <tr class="active">
                                <td colspan="4" class="text-right"><span class="h4">SUBTOTAL</span></td>
                                <td class="text-right"><span class="h4">&#8358; <?php echo number_format($sub,2); ?></span></td>
                            </tr>
                            <!--<tr class="active">
                                <td colspan="4" class="text-right"><span class="h4">VAT RATE</span></td>
                                <td class="text-right"><span class="h4">25%</span></td>
                            </tr>
                            <tr class="active">
                                <td colspan="4" class="text-right"><span class="h4">VAT DUE</span></td>
                                <td class="text-right"><span class="h4">&#8358; 5.750</span></td>
                            </tr>-->
                            <tr class="active">
                                <td colspan="4" class="text-right"><span class="h3"><strong>TOTAL DUE</strong></span></td>
                                <td class="text-right"><span class="h3"><strong>&#8358; <?php if(!empty($invoice_amt)){echo number_format($invoice_amt,2);} ?></strong></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END Table -->

                <div class="clearfix">
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0)" onclick="App.pagePrint();" class="btn btn-default"><i class="fa fa-print"></i> Save</a>
                        <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-angle-right"></i> Send Invoice</a>
                    </div>
                </div>
                <!-- END Invoice Content -->
            </div>
            <!-- END Invoice Block -->
            
        </div>
        <!-- END Page Content -->