<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <div class="row">
                <div class="col-lg-12">
                    <!-- Meta Data Block -->
                    <div class="block" style="overflow:auto; min-height:850px;">
                        <!-- Meta Data Title -->
                        <div class="block-title">
                            <h2><i class="gi gi-cargo"></i>&nbsp;Manage Product Stocks
                        </div>
                        <!-- END Meta Data Title -->
                        
                        <?php 
							if($module_access != TRUE){
								echo '<a href="'.base_url('premium').'"><h2 class="alert alert-info"><i class="fa fa-magnet"></i> Upgrade Account To Unlock Module</h2></a>';
								$command = 'style="display:none;"';
							} else {$command = '';}
						?>
    
                        <!-- Meta Data Content -->
                        <?php if($rec_del!='' && $rec_del_s!=''){$edit='?r='.$rec_del.'&s='.$rec_del_s.'';}else{$edit='';} ?>
                        <?php echo form_open_multipart('products/stock'.$edit, array('class'=>'form-horizontal form-bordered')); ?>
                            <?php if(!empty($err_msg)){echo $err_msg;} ?>
                            <?php if($rec_del!='' && $rec_del_s!=''){ ?>
                                <div class="col-lg-12 bg-info">
                                    <h3>Are you sure? - Record will be totally remove from the system</h3>
                                    <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                                    <input type="hidden" name="del_s_id" value="<?php echo $rec_del_s; ?>" />
                                    <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                                    <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                                </div>
                            <?php } ?>
                            
                            <div class="col-lg-4">
                            	<?php
									$store_list='';
									$to_store_list='';
									$pdt_lists='';
									$pdt_lists2='';
									$dir_list='';
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
													
													if(!empty($e_store_id)){
														if($e_store_id == $store->id){
															$t_s_sel = 'selected="selected"';	
														} else {$t_s_sel = '';}
													} else {$t_s_sel = '';}
													$to_store_list .= '<option value="'.$store->id.'" '.$t_s_sel.'>'.$store->store.'</option>';
												}	
											}
											
											//query product
											$allpdtcat = $this->m_products->query_product_cat($stores->store_id);
											if(!empty($allpdtcat)){
												foreach($allpdtcat as $pdtcat){
													$pdt_list='';
													$pdt_list2='';
													$store_name = '';
													
													//get store name
													$sname = $this->m_stores->query_store_id($stores->store_id);
													if(!empty($sname)){
														foreach($sname as $name){
															$store_name = $name->store;
														}	
													}
													
													$allpdt = $this->m_products->query_product($pdtcat->id);
													if(!empty($allpdt)){
														foreach($allpdt as $pdt){
															if(!empty($e_pdt_id)){
																if($e_pdt_id == $pdt->id){
																	$p_sel = 'selected="selected"';	
																} else {$p_sel = '';}
															} else {$p_sel = '';}
															$pdt_list .= '<option value="'.$pdt->id.'" '.$p_sel.'>'.$pdt->name.'</option>';	
															$pdt_list2 .= '<option value="'.$pdt->id.'" '.$p_sel.'>'.$pdt->name.'</option>';	
														}
													}
													
													$pdt_lists .= '<optgroup label="'.$pdtcat->cat.' ['.substr($store_name,0,10).'...]">'.$pdt_list.'</optgroup>';
													$pdt_lists2 .= '<optgroup label="'.$pdtcat->cat.' ['.substr($store_name,0,10).'...]">'.$pdt_list.'</optgroup>';
												}
											}
											
											//query stock
											$allstock = $this->m_products->query_stock($stores->store_id);
											if(!empty($allstock)){
												foreach($allstock as $stock){
													$pdt_name='';
													$getpdt = $this->m_products->query_product_id($stock->pdt_id);
													if(!empty($getpdt)){
														foreach($getpdt as $gpdt){
															$pdt_name = $gpdt->name;	
														}
													}
													
													//get remark
													$remark = '';
													if($stock->distribute == 0){
														$gs = $this->m_stores->query_store_id($stock->store_id);
														if(!empty($gs)){
															foreach($gs as $s){
																$remark = '<span class="label label-success">Added to '.$s->store.'</span>';	
															}
														}
													} else if($stock->distribute == 1){
														$ds = $this->m_stores->query_store_id($stock->from_store_id);
														if(!empty($ds)){
															foreach($ds as $dss){
																$deliver_store = $dss->store;	
															}
														}
														
														if($stock->deduct == 1){
															$remark = '<span class="label label-primary">Moved to '.$deliver_store.'</span>';
														} else {
															$remark = '<span class="label label-info">Moved from '.$deliver_store.'</span>';
														}
													} else if($stock->distribute == 2 && $stock->deduct == 1){
														$ds = $this->m_stores->query_store_id($stock->from_store_id);
														if(!empty($ds)){
															foreach($ds as $dss){
																$remark = '<span class="label label-success">Sales from '.$dss->store.'</span>';		
															}
														}
													}
													
													
													$dir_list .= '
														<tr>
															<td>'.date('d M Y', strtotime($stock->reg_date)).'</td>
															<td>'.$pdt_name.'</td>
															<td class="text-center">'.$stock->qty.'</td>
															<td>'.$remark.'</td>
															<td class="text-center">
																<div class="btn-group btn-group-xs">
																	<!--<a href="'.base_url('products/stock?e='.$stock->id.'&s='.$stores->store_id.'').'" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>-->
																	<a href="'.base_url('products/stock?r='.$stock->id.'&s='.$stores->store_id.'').'" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
																</div>
															</td>
														</tr>
													';
												}
											}
										}
									}
								?>
								
								<style>
									#div1 { display:none; }
								</style>
								<div class="form-group">
									<div class="btn-group btn-group-justified">
										<div class="btn-group">
										  <button type="button" id="div2_click" class="btn btn-primary active" data-toggle="button">Add</button>
										</div>
								
										<div class="btn-group">
										   <button type="button" id="div1_click" class="btn btn-primary">Distribute</button>
										</div>
									</div>
								</div>
                                <input type="hidden" name="stock_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="store_id">Outlet</label>
                                    <div class="col-md-9">
                                        <select id="store_id" name="store_id" class="select-chosen" data-placeholder="Select Outlet" onchange="get_pdt();">
                                            <option></option>
                                            <?php echo $store_list; ?>
                                        </select>
                                        <script type="text/javascript">
											function get_pdt(){
												var hr = new XMLHttpRequest();
												var store_id = document.getElementById('store_id').value;
												var c_vars = "store_id="+store_id;
												hr.open("POST", "<?php echo base_url('products/get_product'); ?>", true);
												hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
												hr.onreadystatechange = function() {
													if(hr.readyState == 4 && hr.status == 200) {
														var return_data = hr.responseText;
														document.getElementById("pdt").innerHTML = return_data;
												   }
												}
												hr.send(c_vars);
												document.getElementById("pdt").innerHTML = "listing...";
											}
										</script>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="pdt">Product</label>
                                    <div class="col-md-9">
                                        <select id="pdt" name="pdt" class="form-control" data-placeholder="Select Product">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="div2">
                                    
                                </div>
                                
                                <div id="div1">
                                	<div class="form-group">
                                        <label class="col-md-3 control-label" for="to_store_id">To</label>
                                        <div class="col-md-9">
                                            <select id="to_store_id" name="to_store_id" class="select-chosen" data-placeholder="Select Store" onchange="get_pdt2();">
                                                <option></option>
                                                <?php echo $store_list; ?>
                                            </select>
                                            <script type="text/javascript">
												function get_pdt2(){
													var hr = new XMLHttpRequest();
													var to_store_id = document.getElementById('to_store_id').value;
													var c_vars = "store_id="+to_store_id;
													hr.open("POST", "<?php echo base_url('products/get_product'); ?>", true);
													hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
													hr.onreadystatechange = function() {
														if(hr.readyState == 4 && hr.status == 200) {
															var return_data = hr.responseText;
															document.getElementById("pdt2").innerHTML = return_data;
													   }
													}
													hr.send(c_vars);
													document.getElementById("pdt2").innerHTML = "listing...";
												}
											</script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="pdt2">Product</label>
                                        <div class="col-md-9">
                                            <select id="pdt2" name="pdt2" class="form-control" data-placeholder="Select Product">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="qty">Quatity</label>
                                    <div class="col-md-9">
                                        <input type="text" id="qty" name="qty" class="form-control" placeholder="100" value="<?php if(!empty($e_qty)){echo $e_qty;} ?>">
                                    </div>
                                </div>
                                
                                <input type="hidden" id="dist" name="dist" />
                                
                                <div class="form-group form-actions" <?php echo $command; ?>>
                                    <div class="col-md-9 col-md-offset-3">
                                        <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-8">
                            	<!-- All Products Content -->
								<table id="ecom-products" class="table table-bordered table-striped table-vcenter">
									<thead>
										<tr>
											<th>Date</th>
											<th>Product</th>
                                            <th class="text-center">Qty</th>
                                            <th>Remark</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php echo $dir_list; ?>
									</tbody>
								</table><br />
								<!-- END All Products Content -->
                            </div>
                            
                        <?php echo form_close(); ?>
                        <!-- END Meta Data Content -->
                    </div>
                    <!-- END Meta Data Block -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->