<!-- Page content -->
<div id="page-content">
	<div class="block full">
		<div class="row">
			<div class="col-lg-12">
				<strong>FILTER STATEMENT</strong>
				<div class="pull-right">
					<a href="javascript:void(0)" class="btn btn-sm btn-alt btn-danger" onclick="App.pagePrint();"><i class="fa fa-print"></i> Print Statement</a>
				</div>
				<div class="pull-right">
					<a href="javascript:void(0)" class="btn btn-sm btn-alt btn-default" onclick="vat_payment();"><i class="fa fa-money"></i> VAT Payments</a>&nbsp;&nbsp;
				</div>&nbsp;
				<hr />
			</div>

			<div class="col-lg-12">
				<?php
					$store_list=''; $vatpayment='';
					if(!empty($allstores)){
						foreach($allstores as $stores){
							$allstore = $this->m_stores->query_store_id($stores->store_id);
							if(!empty($allstore)){
								foreach($allstore as $store){
									$store_list .= '<option value="'.$store->id.'">'.$store->store.'</option>';

									// load VAT Payments
									$allvats = $this->crud->read_single('store_id', $store->id, 'vat_payment');
									if(!empty($allvats)) {
										foreach($allvats as $av) {
											$store_name = $this->crud->read_field('id', $av->store_id, 'store', 'store');
											$vatpayment .= '
												<tr>
													<td>'.date('d M, Y', strtotime($av->pay_date)).'</td>
													<td>'.$store_name.'</td>
													<td align="right">'.number_format((float)$av->amount,2).'</td>
													<td>'.$av->remark.'</td>
												</tr>
											';
										}
									}
								}
							}
						}
					}
				?>
				<div class="form-group col-md-4">
					<select id="store" name="store" class="select-chosen" data-placeholder="Select Outlet">
						<option style="color:#000;" value="0">All Outlets/Branches</option>
						<?php echo $store_list; ?>
					</select>
				</div>
				<div class="form-group col-sm-8">
					<span class="btn-group input-group input-daterange" data-date-format="mm/dd/yyyy">
						<input id="start_date" name="start_date" class="btn btn-default btn-sm text-center" data-toggle="modal" data-target="" placeholder="START DATE">
						<input type="text" id="end_date" name="end_date" aria-expanded="false" class="dropdown-toggle btn btn-default btn-sm text-right" placeholder="END DATE">
						<button type="submit" class="btn btn-sm btn-primary pull-right" onclick="gen_statement();"><i class="gi gi-charts"></i> GENERATE STATEMENT</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div id="vatpayment" class="block full" style="display:none;">
		<div class="row">
			<div class="col-lg-12">
				<h3><b>VAT Payments</b></h3><hr />
			</div>

			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-4">
						<div class="col-lg-12"><h5><b>VAT Remittance</b></h5><hr /></div>
						<div class="col-lg-12">
							<div class="form-group">
								<label class="control-label" for="vatstore">Outlet</label>
								<select id="vatstore" name="vatstore" class="select-chosen" data-placeholder="Select Outlet">
									<option></option>
									<?php echo $store_list; ?>
								</select>
							</div>

							<div class="form-group">
								<label class="control-label" for="vatamount">Amount (&#8358;)</label>
								<input type="text" id="vatamount" name="vatamount" class="form-control" placeholder="25000">
							</div>

							<div class="form-group">
								<label class="control-label" for="vatdate">Payment Date</label>
								<input id="vatdate" name="vatdate" class="form-control input-datepicker" data-date-format="mm/dd/yyyy" placeholder="mm/dd/yyyy">
							</div>

							<div class="form-group">
								<label class="control-label" for="vatremark">Remark</label>
								<textarea id="vatremark" name="vatremark" class="form-control" rows="2"></textarea>
							</div>

							<div id="vatresponse"></div>

							<div class="form-group form-actions">
                                <div class="col-md-12 text-center">
                                    <a href="javascript:;" class="btn btn-sm btn-primary" onclick="pay_vat();"><i class="fa fa-floppy-o"></i> Save</a>
                                </div>
                            </div>
						</div>
					</div>

					<div class="col-sm-8">
						<div class="col-lg-12"><h5><b>VAT Remitted</b></h5></div>
						<div class="col-lg-12">
							<table id="ecom-products" class="table table-bordered table-striped table-vcenter">
								<thead>
									<tr>
										<th width="70px">Date</th>
										<th>Outlet</th>
										<th align="right">Amount (&#8358;)</th>
										<th>Remark</th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($vatpayment)){echo $vatpayment;} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="statement_response"></div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function(event) {
		var start_date = '01/01/<?php echo date("Y"); ?>';
		var end_date = '12/31/<?php echo date("Y"); ?>';
		$('#start_date').val(start_date);
		$('#end_date').val(end_date);
		gen_statement();
	});

	function gen_statement() {
		$('#statement_response').html('<div class="row"><div class="col-lg-12 text-center text-muted"><h2><i class="fa fa-spin fa-spinner fa-2x"></i><br/>Generating report...</h2></div></div>');

		var store = $('#store').val();
		var start = $('#start_date').val();
		var end = $('#end_date').val();

		$.ajax({
			url: '<?php echo base_url(); ?>statements/vat_filter',
			type: 'post',
			data: {store:store, start:start, end:end},
			success: function(data) {
				// var dt = JSON.parse(data);
				$('#statement_response').html(data);
			},
			// complete: function () { selecting(); }
		});
	}

	function vat_payment() {
		$('#vatpayment').toggle(500);
	}

	function pay_vat() {
		$('#vatresponse').html('<div class="row"><div class="col-lg-12 text-center text-muted"><i class="fa fa-spin fa-spinner"></i> Please wait...</div></div>');
		
		var store = $('#vatstore').val();
		var amount = $('#vatamount').val();
		var date = $('#vatdate').val();
		var remark = $('#vatremark').val();

		$.ajax({
			url: '<?php echo base_url(); ?>statements/pay_vat',
			type: 'post',
			data: {store:store, amount:amount, date:date, remark:remark},
			success: function(data) {
				var dt = JSON.parse(data);
				$('#vatresponse').html(dt.msg);
				if(dt.type == 'success') {
					$('#vatstore').html('');
					$('#vatamount').html('');
					$('#vatdate').html('');
					$('#vatremark').html('');
				}
			}
		});
	}

	function selecting() {
		$('.select-chosen').chosen({width: "100%"});
	}
</script>