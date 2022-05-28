<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_expense = 0;
				$top_expense = 0;
				if(!empty($allstores)){
					foreach($allstores as $stores){
						$allstore = $this->m_stores->query_store_id($stores->store_id);
						if(!empty($allstore)){
							foreach($allstore as $store){
								$qc = $this->m_expenses->query_expense_cat($store->id);
								if(!empty($qc)){
									foreach($qc as $c){
										$qp = $this->m_expenses->query_expense($c->id);
										if(!empty($qp)){
											foreach($qp as $p){
                                                $total_expense += 1; //count expenses
                                                $type_name = '';
                                                $type_name = $this->crud->read_field('id', $p->cat_id, 'expense_cat', 'type');
                                                if($type_name) {$type_name = '('.$type_name.')';}
												
												$dir_list .= '
													<tr>
														<td>'.$p->exp_date.'</td>
														<td class="hidden-xs">'.$store->store.'</td>
														<td>'.$c->cat.' '.$type_name.'</td>
														<td>'.$p->details.'</td>
														<td class="text-right">'.number_format($p->price,2).'</td>
														<td class="text-center">
															<div class="btn-group btn-group-xs">
																<a href="'.base_url('expenses/add?e='.$p->id.'&c='.$p->cat_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																<a href="'.base_url('expenses/?r='.$p->id.'&c='.$p->cat_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
				}
			?>
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-6 col-lg-4">
                    <a href="<?php echo base_url('expenses/add'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Add New</strong> Expense</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Top</strong> Expenses</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $top_expense; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Expenses</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_expense; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All expenses Block -->
            <div class="block full">
                <!-- All expenses Title -->
                <div class="block-title">
                    <h2><i class="gi gi-tablet"></i> <strong>All</strong> expenses</h2>
                </div>
                <!-- END All expenses Title -->
                
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('expenses/delete'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <input type="hidden" name="del_c_id" value="<?php echo $rec_del_c; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <!-- All expenses Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th width="70px">Date</th>
                            <th class="hidden-xs">Outlet</th>
                            <th>Category</th>
                            <th>Remark</th>
                            <th class="hidden-xs text-right">Amount (&#8358;)</th>
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