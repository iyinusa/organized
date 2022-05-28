<!-- Page content -->
<div id="page-content">
	<div class="block full">
		<div class="row">
			<div class="col-lg-12">
				<strong>FILTER STATEMENT</strong>
				<div class="pull-right">
					<a href="javascript:void(0)" class="btn btn-sm btn-alt btn-danger" onclick="App.pagePrint();"><i class="fa fa-print"></i> Print Statement</a>
				</div>
				<hr />
			</div>

			<div class="col-lg-12">
				<?php
					$store_list='';
					if(!empty($allstores)){
						foreach($allstores as $stores){
							$allstore = $this->m_stores->query_store_id($stores->store_id);
							if(!empty($allstore)){
								foreach($allstore as $store){
									$store_list .= '<option value="'.$store->id.'">'.$store->store.'</option>';
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
			url: '<?php echo base_url(); ?>statements/filter',
			type: 'post',
			data: {store:store, start:start, end:end},
			success: function(data) {
				// var dt = JSON.parse(data);
				$('#statement_response').html(data);
			},
			// complete: function () { selecting(); }
		});
	}

	function selecting() {
		$('.select-chosen').chosen({width: "100%"});
	}
</script>