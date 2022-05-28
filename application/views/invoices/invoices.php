<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-6 col-lg-4">
                    <a href="<?php echo base_url('invoices/add'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Create</strong> Sales</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <?php
					$dir_list = '';
					$client = '';
					$pdt_serv = '';
					$paid_count = 0;
					$unpaid_count = 0;
					$all_count = 0;
					if(!empty($allstores)){
						foreach($allstores as $stores){
							$stores_id = $stores->store_id;
							
							//query stores
							$allstore = $this->m_stores->query_store_id($stores_id);
							if(!empty($allstore)){
								foreach($allstore as $store){
									//get invoices
									$allinv = $this->m_invoices->query_user_invoice($store->id);
									if(!empty($allinv)){
										foreach($allinv as $inv){
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
											
											//get product/service information
											//if($inv->pdt_id==0){
//												$gps = $this->m_products->query_product_id($inv->pdt_id);
//											} else {
//												$gps = $this->m_services->query_service_id($inv->service_id);
//											}
//											if(!empty($gps)){
//												foreach($gps as $ps){
//													$pdt_serv = $ps->name;
//												}
//											}
										
											if($inv->status == 'Paid'){
												$status_label = 'label label-success';
												$paid_count += 1;
											} else if($inv->status == 'Unpaid' || $inv->status == 'Credit'){
												if($inv->status == 'Unpaid') {
													$status_label = 'label label-default';
												}
												$unpaid_count += 1;
											} else if($inv->status == 'Partially Paid'){
												$status_label = 'label label-info';
											} else if($inv->status == 'Overdue'){
												$status_label = 'label label-warning';
											} else if($inv->status == 'Cancelled' || $inv->status == 'Credit'){
												$status_label = 'label label-danger';
											} else {
												$status_label = '';
											}
											
											$all_count += 1;
											
											$dir_list .= '
												<tr>
													<td>'.date('d M, Y', strtotime($inv->reg_date)).'</td>
													<td>'.$store->store.'</td>
													<td><a href="'.base_url('customers/view?s='.$inv->store_id.'&u='.$client_id.'').'" data-toggle="tooltip" title="View">'.$client.'</a></td>
													<!--<td>'.$pdt_serv.'</td>-->
													<td class="text-right">'.number_format((float)$inv->amt,2).'</td>
													<td class="hidden-xs text-center"><span class="'.$status_label.'">'.$inv->status.'</span></td>
													<td class="text-center">
														<div class="btn-group btn-group-xs">
															<a href="'.base_url('invoices/add?e='.$inv->id.'&s='.$inv->store_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
															<a href="'.base_url('invoices/view?v='.$inv->id.'&s='.$inv->store_id.'&u='.$inv->client_id.'').'" data-toggle="tooltip" title="View" class="btn btn-success"><i class="fa fa-eye"></i></a>
															<a href="'.base_url('invoices/?r='.$inv->id.'&s='.$inv->store_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
														</div>
													</td>
												</tr>
											';
											
											$status_label = '';
										}
									}	
								}
							}
						}
					}
				?>
                <div class="col-sm-6 col-lg-4">
                    <a href="<?php echo base_url('invoices/debtors'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-danger">
                            <h4 class="widget-content-light"><strong>Unpaid</strong> Invoices</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-danger animation-expandOpen"><?php echo $unpaid_count; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Invoices</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $all_count; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All Products Block -->
            <div class="block full">
                <!-- All Products Title -->
                <div class="block-title">
                    <h2><i class="gi gi-database_plus"></i> <strong>All</strong> Invoices</h2>
                </div>
                <!-- END All Products Title -->
        
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('invoices/delete'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <input type="hidden" name="del_s_id" value="<?php echo $rec_del_s; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <!-- All Products Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Outlet</th>
                            <th>Client</th>
                            <!--<th>Services</th>-->
                            <th class="text-right">Amount (&#8358;)</th>
                            <th class="hidden-xs">Status</th>
                            <th class="text-center">Action</th>
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