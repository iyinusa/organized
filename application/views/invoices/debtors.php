<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Quick Stats -->
            <div class="row text-center">
				<?php 
					$dir_list = ''; $outstandings = 0;

					if(!empty($allstores)){
						foreach($allstores as $stores){
							$stores_id = $stores->store_id;
							
							//query stores
							$allstore = $this->m_stores->query_store_id($stores_id);
							if(!empty($allstore)){
								foreach($allstore as $store){
									//get invoices
									$allinv = $this->crud->read_single('store_id', $store->id, 'invoice');
									if(!empty($allinv)){
										foreach($allinv as $inv){
											if($inv->status == 'Partially Paid' || $inv->status == 'Credit') {
												if($inv->status == 'Credit') {
													$outstandings += $inv->amt;
													$each_outstanding = $inv->amt;
												} else {
													$outstandings += $inv->balance;
													$each_outstanding = $inv->balance;
												}

												//get client information
												$allcust = $this->m_customers->query_store_customer($store->id);
												if(!empty($allcust)){
													foreach($allcust as $cust){
														$gc = $this->users->query_single_user_id($inv->client_id);
														if(!empty($gc)){
															foreach($gc as $c){
																$client_id = $c->id;
																$client = $c->title.' '.$c->firstname.' '.$c->lastname;
															}
														}
													}
												}

												$dir_list .= '
													<tr>
														<td>'.date('d M, Y', strtotime($inv->reg_date)).'</td>
														<td>'.$store->store.'</td>
														<td><a href="'.base_url('customers/view?s='.$inv->store_id.'&u='.$inv->client_id.'').'" data-toggle="tooltip" title="View">'.$client.'</a></td>
														<td class="text-right">'.number_format((float)$each_outstanding,2).'</td>
														<td class="text-center">
															<div class="btn-group btn-group-xs">
																<a href="'.base_url('invoices/view?v='.$inv->id.'&s='.$inv->store_id.'&u='.$inv->client_id.'').'" data-toggle="tooltip" title="View" class="btn btn-success"><i class="fa fa-eye"></i></a>
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
					}
				?>
                <div class="col-sm-6">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-danger">
                            <h4 class="widget-content-light"><strong>All</strong> Outstandings</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-danger animation-expandOpen">&#8358;<?php echo number_format($outstandings,2); ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All Products Block -->
            <div class="block full">
                <!-- All Products Title -->
                <div class="block-title">
                    <h2><i class="gi gi-database_plus"></i> <strong>All</strong> Outstandings</h2>
                </div>
                <!-- END All Products Title -->
                
                <!-- All Products Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Outlet</th>
                            <th>Client</th>
                            <!--<th>Services</th>-->
                            <th class="text-right">Amount (&#8358;)</th>
                            <th class="text-center">Invoice</th>
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