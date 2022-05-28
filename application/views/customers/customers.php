<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_supplier = 0;
				$total_customer = 0;
				if(!empty($allstores)){
					foreach($allstores as $stores){
						$allstore = $this->m_stores->query_store_id($stores->store_id);
						if(!empty($allstore)){
							foreach($allstore as $store){
								$store_id = $store->id;
								
								//query store customer
								$qs = $this->m_customers->query_store_customer($store_id);
								if(!empty($qs)){
									foreach($qs as $qstore){
										$s_user_id = $qstore->user_id;
										$s_reg_date = $qstore->reg_date;
										
										//query total suppliers and customers
										$qsc = $this->m_customers->query_customer($qstore->store_id);
										if(!empty($qsc)){
											foreach($qsc as $qscr){
												if($qscr->active == 1){
													$qcont = $this->m_customers->query_group_id($qscr->group_id);
													if(!empty($qcont)){
														foreach($qcont as $cont){
															if($cont->name == 'Supplier'){$total_supplier += 1;}
															if($cont->name == 'Customer'){$total_customer += 1;}									
														}
													}
												}
											}
										}
											
										//query customer data
										$qu = $this->users->query_single_user_id($s_user_id);
										if(!empty($qu)){
											foreach($qu as $quser){
												$s_user_firstname = $quser->firstname;
												$s_user_lastname = $quser->lastname;
												$s_user_pics_small = $quser->pics_small;
												
												if($s_user_pics_small=='' || file_exists(FCPATH.$s_user_pics_small)==FALSE){$s_user_pics_small='img/icon57.png';}
											}
										} else {
											$s_user_firstname = '';
											$s_user_lastname = '';
											$s_user_pics_small = 'img/icon57.png';	
										}
										
										//get contact if for edit
										$qcont = $this->m_customers->query_customer_store_user($store->id, $s_user_id);
										if(!empty($qcont)){
											foreach($qcont as $cont){
												$contact_id = $cont->id;	
											}
										} else {$contact_id = '';}
										
										$dir_list .= '
											<tr>
												<td class="text-center">
													<img alt="" src='.base_url($s_user_pics_small).' width="50px" class="img-circle" />
												</td>
												<td>'.$store->store.'</td>
												<td><a href="'.base_url('customers/view?s='.$store_id.'&u='.$s_user_id.'').'" data-toggle="tooltip" title="View">'.$s_user_firstname.' '.$s_user_lastname.'</a></td>
												<td class="text-center hidden-xs">'.date('d M Y',strtotime($s_reg_date)).'</td>
												<td class="text-center">
													<div class="btn-group btn-group-xs">
														<a href="'.base_url('contacts?r='.$contact_id.'&s='.$store->id.'&u='.$s_user_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
														<a href="'.base_url('customers/view?s='.$store_id.'&u='.$s_user_id.'').'" data-toggle="tooltip" title="View" class="btn btn-success"><i class="fa fa-eye"></i></a>
														<a href="'.base_url('customers/?r='.$qstore->id.'&s='.$store_id.'&u='.$s_user_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
			?>
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-6 col-lg-4">
                    <a href="<?php echo base_url('contacts'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Add New</strong> Supplier/Customer</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Total</strong> Suppliers</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-danger animation-expandOpen"><?php echo $total_supplier; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>Total</strong> Customers</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_customer; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All Products Block -->
            <div class="block full">
                <!-- All Products Title -->
                <div class="block-title">
                    <h2><i class="gi gi-address_book"></i> <strong>All</strong> Customers/Clients</h2>
                </div>
                <!-- END All Products Title -->
                
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('customers/delete'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <input type="hidden" name="del_store_id" value="<?php echo $rec_del_s; ?>" />
                            <input type="hidden" name="del_user_id" value="<?php echo $rec_del_u; ?>" />
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
                            <th class="text-center" style="width: 70px;">Photo</th>
                            <th>Store</th>
                            <th>Customer</th>
                            <th class="text-center hidden-xs">Reg</th>
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