<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$total_service = 0;
				$top_service = 0;
				if(!empty($allstores)){
					foreach($allstores as $stores){
						$allstore = $this->m_stores->query_store_id($stores->store_id);
						if(!empty($allstore)){
							foreach($allstore as $store){
								$qc = $this->m_services->query_service_cat($store->id);
								if(!empty($qc)){
									foreach($qc as $c){
										$qp = $this->m_services->query_service($c->id);
										if(!empty($qp)){
											foreach($qp as $p){
												$pdt_img = $p->img_id;
												
												if($pdt_img==0){
													$pdt_img='img/icon57.png';
												} else {
													$gti = $m_obj->users->query_img_id($pdt_img);
													foreach($gti as $gtimg){
														$pdt_img = $gtimg->pics_square;	
													}
												}
												
												$total_service += 1; //count services
												
												$dir_list .= '
													<tr>
														<td class="text-center">
															<img alt="" src='.base_url($pdt_img).' width="50px" class="img-circle" />
														</td>
														<td class="hidden-xs">'.$store->store.'</td>
														<td>'.$c->cat.'</td>
														<td>'.$p->name.'</td>
														<td class="text-right">'.number_format($p->price,2).'</td>
														<td class="text-center">
															<div class="btn-group btn-group-xs">
																<a href="'.base_url('services/add?e='.$p->id.'&c='.$p->cat_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
																<a href="'.base_url('services/?r='.$p->id.'&c='.$p->cat_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
                    <a href="<?php echo base_url('services/add'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Add New</strong> Service</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Top</strong> Services</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $top_service; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Services</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_service; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All services Block -->
            <div class="block full">
                <!-- All services Title -->
                <div class="block-title">
                    <h2><i class="gi gi-tablet"></i> <strong>All</strong> services</h2>
                </div>
                <!-- END All services Title -->
                
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('services/delete'); ?>
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
                
                <!-- All services Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px;">Photo</th>
                            <th class="hidden-xs">Store</th>
                            <th>Category</th>
                            <th>Service</th>
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