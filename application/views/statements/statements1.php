<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <?php
				$dir_list = '';
				$store_name = '';
				$total_income_cash = 0;
				$total_income = 0;
				if(!empty($allstores)){
					foreach($allstores as $stores){
						$allstore = $this->m_stores->query_store_id($stores->store_id);
						if(!empty($allstore)){
							foreach($allstore as $store){
								$store_name = $store->store;
								
								//query incomes for the year
								$income_item = '';
								$income_total = 0;
								$income_partial_total = 0;
								$qi = $this->m_invoices->query_user_invoice($store->id);
								if(!empty($qi)){
									foreach($qi as $i){
										if(date('Y') == date('Y', strtotime($i->pay_date))){
											if($i->status == 'Paid'){
												$income_total += $i->amt;
												$inv_date = $i->pay_date;	
											} else {
												$income_partial_total += $i->amt;
												$inv_date = date('d M Y', strtotime($i->start_date));
											}
											
											$inv_amt = 0;
											$inv_amt += $i->amt;
											
											$income_item .= '
												<tr>
													<td>'.$inv_date.' - '.$i->status.'</td>
													<td align="right">&#8358;'.number_format($inv_amt).'</td>
												</tr>
											';
										}
									}
								}
								
								//query expenses for the year
								$exp_item = '';
								$exp_item_list = '';
								$exp_total = 0;
								$qc = $this->m_expenses->query_expense_cat($store->id);
								if(!empty($qc)){
									foreach($qc as $c){
										$cat_name = $c->cat;
										
										$qp = $this->m_expenses->query_expense($c->id);
										if(!empty($qp)){
											foreach($qp as $p){
												$exp_total += $p->price;
												
												if(date('Y') == date('Y', strtotime($p->exp_date))){
													$exp_item_list .= '
														<tr>
															<td>'.date('d M Y', strtotime($p->exp_date)).' - '.$p->details.'</td>
															<td align="right">&#8358;'.number_format($p->price).'</td>
														</tr>
													';
												}
											}
										}
										
										$exp_item .= '
											<tr>
												<td colspan="2">
													<h5 style="background-color:#eee; padding:5px;"><b>'.ucwords($cat_name).'</b></h5>
												</td>
											</tr>
											'.$exp_item_list.'
										';
										
										$exp_item_list = '';
									}
								}
								
								//calculate total
								$total_income_cash = $income_total - $exp_total;
								$total_income = ($income_total + $income_partial_total) - $exp_total;
								
								if($total_income_cash < 0){$inc_c_color = 'danger';} else {$inc_c_color = '';}
								if($total_income < 0){$inc_color = 'danger';} else {$inc_color = '';}
								
								$dir_list .= '
                                    <span style="color:#777; font-weight:bolder;">OUTLET NAME</span>
									<h4><b><span style="text-transform: uppercase; color:#222;">'.ucwords($store_name).'</span></b></h4>
                                    <table id="border" class="table-condensed display" style="background-color:#3F5872; width:100%; color:#fff;">
                                        <tr>
                                            <td>INCOME CATEGORIES</td>
                                        </tr>
                                    </table>
									<table class="table-condensed display table-bordered" style="width:100%; color:#4b4b4b;">
										<tr>
											<td>
												
													'.$income_item.'
												
											</td>
											<td></td>
										</tr>
                                        <tr style="border-top:3px double #3F5872; border-bottom:3px solid #3F5872; color:#222 !important;">
											<td><h4><b><span style="font-size:80%">TOTAL INCOME</span></b></h4></td>
											<td align="right"><h4>&#8358;'.number_format($income_total,2).'</h4></td>
										</tr>
                                        <tr>
                                        <td></td>
                                        </tr>
                                        </table>
										
										<table class="table-condensed display" style="background-color:#3F5872; width:100%; color:#fff;">
                                        <tr>
                                            <td>EXPENSES CATEGORIES</td>
                                        </tr>
                                    </table>
                                        <table class="table-condensed display table-bordered" style="width:100%; color:#4b4b4b;">
										<tr>
											<td>
												
													'.$exp_item.'
												
											</td>
											<td></td>
										</tr>
                                        <tr style="border-top:3px double #3F5872; border-bottom:3px solid #3F5872; color:#222;">
											<td><h4><b><span style="font-size:80%">TOTAL EXPENSES</span></b></h4></td>
											<td align="right"><h4>&#8358;'.number_format($exp_total,2).'</h4></td>
										</tr>
									</table>
									<h4 style="background-color:#3F5872; padding:0px 10px; border-top:5px solid #2A3F54; border-bottom:1px solid #2A3F54;">
										<table style="width:100%; color:#fff;" class="table-condensed display ">
											<tr>
												<td><h4><b><span style="text-transform: uppercase;">GRAND TOTAL</span></b></h4></td>
												<td align="right"><span style="font-size:65%"><u><b>Income</b></u></span><br/><span style="font-size:85%" class="text-'.$inc_c_color.'">&#8358;'.number_format($total_income_cash,2).'</span></td>
												<td align="right"><span style="font-size:65%"><u><b>Income + Unpaid</b></u></span><br/><span style="font-size:85%" class="text-'.$inc_color.'">&#8358;'.number_format($total_income,2).'</span></td>
											</tr>
										</table>
									</h4><br/><br/>
								';
							}
						}	
					}
				}
			?>
        	
            <div class="block full" style="background-color:#ffffff; color:#333333;">
            	<div class="row">
                    <div class="col-lg-10">
                            <h5><strong>FILTER BY DATE RANGE</strong></h5>
                            <?php
								$store_list='';
								if(!empty($allstores)){
									foreach($allstores as $stores){
										$allstore = $this->m_stores->query_store_id($stores->store_id);
										if(!empty($allstore)){
											foreach($allstore as $store){
												if(!empty($e_store_id)){
													if($e_store_id == $store->id){
														$s_sel = 'selected="selected"';	
													} else {$s_sel = '';}
												} else {$s_sel = '';}
												$store_list .= '<option style="color:#000;" value="'.$store->id.'" '.$s_sel.'>'.$store->store.'</option>';
											}
										}
									}
								}
							?>
                            <div class="form-group col-lg-12">
                            	<select id="store" name="store" class="select-chosen" data-placeholder="Select Outlet">
                                    <option style="color:#000;" value="0">All Outlets</option>
                                    <?php echo $store_list; ?>
                                </select>
                                <span class="btn-group input-group input-daterange" data-date-format="mm/dd/yyyy">
                                    <input id="start_date" name="start_date" class="btn btn-primary btn-sm text-center" data-toggle="modal" data-target="" placeholder="START DATE">
                                    <input type="text" id="end_date" name="end_date" aria-expanded="false" class="dropdown-toggle btn btn-warning btn-sm text-right" placeholder="END DATE">
                                    <button type="submit" class="btn btn-sm btn-default pull-right" onclick="gen_date();"><i class="gi gi-charts"></i> GENERATE STATEMENT</button>
                            </div>
                            <script type="text/javascript">
                                function gen_date(){
                                    var hr = new XMLHttpRequest();
                                    var start_date = document.getElementById('start_date').value;
                                    var end_date = document.getElementById('end_date').value;
									var store = document.getElementById('store').value;
                                    var c_vars = "start_date="+start_date+"&end_date="+end_date+"&store="+store;
                                    hr.open("POST", "<?php echo base_url('statements/filter_date'); ?>", true);
                                    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                    hr.onreadystatechange = function() {
                                        if(hr.readyState == 4 && hr.status == 200) {
                                            var return_data = hr.responseText;
                                            document.getElementById("statement_reply").innerHTML = return_data;
                                       }
                                    }
                                    hr.send(c_vars);
                                    document.getElementById("statement_reply").innerHTML = "<div class='text-center'><i class=\"fa fa-spinner fa-spin fa-5x\"></i> Generating Statements...</div>";
                                }
                            </script>
                        </div>
                        
                    <div class="col-lg-2 clearfix" style="padding-top:40px;">
                        <div class="pull-right">
                            <a href="javascript:void(0)" class="btn btn-sm btn-alt btn-danger" onclick="App.pagePrint();"><i class="fa fa-print"></i> Print Statement</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="statement_reply" class="col-lg-12">
                        <div>
                            <h4 class="text-center"><strong>INCOME STATEMENT (PROFIT/LOSS)</strong><br/><small>For The Year <b><?php echo date('Y'); ?></b></small></h4>
                            <div class="block full">
                                <div class="row">
                                	<?php echo $dir_list; ?>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->