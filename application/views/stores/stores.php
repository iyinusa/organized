<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				$out_of_profit = 0;
				$top_store = 0;
				$total_store = 0;
				if(!empty($allstores)){
					foreach($allstores as $stores){
						$allstore = $this->m_stores->query_store_id($stores->store_id);
						if(!empty($allstore)){
							foreach($allstore as $store){
								$store_img = $store->img_id;
								if($store_img==0){
									$store_img='img/icon57.png';
								} else {
									$gti = $m_obj->users->query_img_id($store_img);
									foreach($gti as $gtimg){
										$store_img = $gtimg->pics_square;	
									}
								}
								
								$total_store += 1 ;
								
								$dir_list .= '
									<tr>
										<td class="text-center">
											<img alt="" src='.base_url($store_img).' width="50px" class="img-circle" />
										</td>
										<td>'.$store->store.'</td>
										<td class="hidden-xs">'.$store->address.'</td>
										<td>'.$store->city.'</td>
										<td class="hidden-xs">'.$store->country.'</td>
										<td class="text-center">
											<div class="btn-group btn-group-xs">
												<a href="'.base_url('stores/add?e='.$store->id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
												<a href="'.base_url('stores/?r='.$store->id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
											</div>
										</td>
									</tr>
								';
							}
						}
					}
				}
			?>
            
            <!-- Quick Stats -->
            <div class="row text-center">
                <div class="col-sm-6 col-lg-3">
                    <a href="<?php echo base_url('stores/add'); ?>" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-success">
                            <h4 class="widget-content-light"><strong>Add New</strong> Outlet</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-success animation-expandOpen"><i class="fa fa-plus"></i></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-danger">
                            <h4 class="widget-content-light"><strong>Out of</strong> Profit</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 text-danger animation-expandOpen"><?php echo $out_of_profit; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-default">
                            <h4 class="widget-content-light"><strong>Top</strong> Outlets</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $top_store; ?></span></div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a href="javascript:void(0)" class="widget widget-hover-effect2">
                        <div class="widget-extra themed-background-dark">
                            <h4 class="widget-content-light"><strong>All</strong> Outlets</h4>
                        </div>
                        <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $total_store; ?></span></div>
                    </a>
                </div>
            </div>
            <!-- END Quick Stats -->
        
            <!-- All Products Block -->
            <div class="block full">
                <!-- All Products Title -->
                <div class="block-title">
                    <h2><i class="gi gi-shopping_cart"></i> <strong>All</strong> Outlets</h2>
                </div>
                <!-- END All Products Title -->
                
                <?php if($rec_del!=''){ ?>
                	<?php echo form_open_multipart('stores/delete'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
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
                            <th class="text-center" style="width: 70px;">Logo</th>
                            <th>Outlets</th>
                            <th class="hidden-xs">Address</th>
                            <th>City</th>
                            <th class="hidden-xs">Country</th>
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