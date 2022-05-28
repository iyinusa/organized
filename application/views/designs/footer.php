<?php include(APPPATH.'libraries/inc.php'); ?>
<?php include(APPPATH.'views/logics/recent_birthday.php'); ?>
					<!-- Footer -->
                    <footer class="clearfix">
                        <div class="pull-right">
                            Designed with <i class="fa fa-heart text-danger"></i> in Nigeria
                        </div>
                        <div class="pull-left">
                            <span id="year-copy"></span> &copy; <a href="<?php echo base_url('dashboard/'); ?>"><?php echo app_name; ?></a>
                        </div>
                    </footer>
                    <!-- END Footer -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
        <div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="gi gi-cogwheels"></i> Settings</h2>
                    </div>
                    <!-- END Modal Header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="<?php echo base_url('dashboard/setup'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return true;">
                            <fieldset>
                                <legend>Account Setup | <a href="<?php echo base_url('settings/account'); ?>">Update Details</a></legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Account Name</label>
                                    <div class="col-md-8">
                                        <div class="form-inline">
                                        	<input type="text" id="user-settings-firstname" name="user-settings-firstname" class="form-control" value="<?php echo $log_user_firstname; ?>" required="required">
                                            <input type="text" id="user-settings-lastname" name="user-settings-lastname" class="form-control" value="<?php echo $log_user_lastname ?>" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-email">Account Email</label>
                                    <div class="col-md-8">
                                        <input type="email" id="user-settings-email" name="user-settings-email" class="form-control" value="<?php if(!empty($log_user_email)){echo $log_user_email;} ?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-notifications">Account Theme</label>
                                    <div class="col-md-8">
                                        <?php
											$tins =& get_instance();
											$tins->load->model('users');
											$gtt = $tins->users->query_all_theme();
											$theme_list = '';
											foreach($gtt as $gtheme){
												if($gtheme->slug == $log_user_theme){
													$th_sel = 'selected="selected"';
												} else {
													$th_sel = '';
												}
												
												$theme_list .= '<option value="'.$gtheme->slug.'" class="themed-background-dark-'.$gtheme->slug.' themed-border-'.$gtheme->slug.'" style="color:#fff;" '.$th_sel.'>'.$gtheme->name.'</option>';
											}
										?>
                                        
                                        <select name="user-settings-theme" class="form-control">
                                        	<option value="">Choose Account Theme</option>
                                            <?php echo $theme_list; ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group form-actions">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END Modal Body -->
                </div>
            </div>
        </div>
        <!-- END User Settings -->
        
        <!-- Birthday wish here -->
        <div id="modal-birthday-wish" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="gi gi-birthday_cake"></i> Wish Happy Birthday</h2>
                    </div>
                    <!-- END Modal Header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                    	<div class="form-horizontal form-bordered">
                            <fieldset>
                                <legend>Select Customer/Contact To Send Birthday Mail</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-notifications">Select Contact</label>
                                    <div class="col-md-8">
                                        <select id="wish_email" name="wish_email" class="select-chosen">
                                            <option value="">Select Contact</option>
                                            <?php if(!empty($rb_item_sel)){echo $rb_item_sel;} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="wish_subject">Subject</label>
                                    <div class="col-md-8">
                                        <input type="text" id="wish_subject" name="wish_subject" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="wish_msg">Message</label>
                                    <div class="col-md-8">
                                        <textarea id="wish_msg" name="wish_msg" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                                <div id="wish_reply" class="text-center"></div>
                            </fieldset>
                            <div class="form-group form-actions">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="send_wish();">Save Changes</button>
                                </div>
                            </div>
                            
                            <script type="text/javascript">
								function send_wish(){
									var hr = new XMLHttpRequest();
									var wish_email = document.getElementById('wish_email').value;
									var wish_subject = document.getElementById('wish_subject').value;
									var wish_msg = document.getElementById('wish_msg').value;
									var c_vars = "wish_email="+wish_email+"&wish_subject="+wish_subject+"&wish_msg="+wish_msg;
									hr.open("POST", "<?php echo base_url('contacts/birthday_wish'); ?>", true);
									hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									hr.onreadystatechange = function() {
										if(hr.readyState == 4 && hr.status == 200) {
											var return_data = hr.responseText;
											document.getElementById("wish_reply").innerHTML = return_data;
									   }
									}
									hr.send(c_vars);
									document.getElementById("wish_reply").innerHTML = "<span class='text-center'><i class=\"fa fa-spinner fa-spin fa-2x\"></i> sending...</span>";
								}
							</script>
                        </div>
                    </div>
                    <!-- END Modal Body -->
                </div>
            </div>
        </div>
        <!-- END Birthday wish here -->
        
        <!-- Feedback wish here -->
        <div id="modal-feedback" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-paper-plane-o"></i> FeedUs Back - Help Make It Better</h2>
                    </div>
                    <!-- END Modal Header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                    	<div class="form-horizontal form-bordered">
                            <fieldset>
                                <legend>What did you think we need to improve on?</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="feed_title">Title</label>
                                    <div class="col-md-8">
                                        <input type="text" id="feed_title" name="feed_title" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="feed_msg">Observation</label>
                                    <div class="col-md-8">
                                        <textarea id="feed_msg" name="feed_msg" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                                <div id="feed_reply" class="text-center"></div>
                            </fieldset>
                            <div class="form-group form-actions">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="send_feedback();">Save Changes</button>
                                </div>
                            </div>
                            
                            <script type="text/javascript">
								function send_feedback(){
									var hr = new XMLHttpRequest();
									var feed_title = document.getElementById('feed_title').value;
									var feed_msg = document.getElementById('feed_msg').value;
									var c_vars = "feed_title="+feed_title+"&feed_msg="+feed_msg;
									hr.open("POST", "<?php echo base_url('contacts/feedback'); ?>", true);
									hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									hr.onreadystatechange = function() {
										if(hr.readyState == 4 && hr.status == 200) {
											var return_data = hr.responseText;
											document.getElementById("feed_reply").innerHTML = return_data;
									   }
									}
									hr.send(c_vars);
									document.getElementById("feed_reply").innerHTML = "<span class='text-center'><i class=\"fa fa-spinner fa-spin fa-2x\"></i> sending...</span>";
								}
							</script>
                        </div>
                    </div>
                    <!-- END Modal Body -->
                </div>
            </div>
        </div>
        <!-- END Feedback wish here -->

        <!-- Remember to include excanvas for IE8 chart support -->
        <!--[if IE 8]><script src="js/helpers/excanvas.min.js"></script><![endif]-->

        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.2.min.js"%3E%3C/script%3E'));</script>

        <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
        <script src="<?php echo base_url(); ?>js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/plugins.js"></script>
        <script src="<?php echo base_url(); ?>js/app.js"></script>

        <!-- Load and execute javascript code used only in this page -->
        <?php if($page_active=='dashboard'){ ?>
        <script src="//maps.google.com/maps/api/js?sensor=true"></script>
        <script src="<?php echo base_url(); ?>js/helpers/gmaps.min.js"></script>
        <!--<script src="<?php echo base_url(); ?>js/pages/index.js"></script>
        <script>$(function(){ Index.init(); });</script>-->
        
        <?php
			////////////////////////////// ==== CHART LOGIC === //////////////////////////////
			$chart_total_sales = 0; $jan_s = 0; $feb_s = 0; $mar_s = 0; $apr_s = 0; $may_s = 0; $jun_s = 0; $jul_s = 0; $aug_s = 0; $sep_s = 0; $oct_s = 0; $nov_s = 0; $dec_s = 0;
			$chart_total_expenses = 0; $jan_e = 0; $feb_e = 0; $mar_e = 0; $apr_e = 0; $may_e = 0; $jun_e = 0; $jul_e = 0; $aug_e = 0; $sep_e = 0; $oct_e = 0; $nov_e = 0; $dec_e = 0;
			if(!empty($allstores)){
				foreach($allstores as $stores){
					$allstore = $this->m_stores->query_store_id($stores->store_id);
					if(!empty($allstore)){
						foreach($allstore as $store){
							//========== START SALES ============
							$get_inv = $this->m_invoices->query_user_invoice($stores->store_id);
							if(!empty($get_inv)){
								foreach($get_inv as $inv){
									//get monthly stat to populate chart
									if(date('Y', strtotime($inv->pay_date)) == date('Y')){
										if($inv->status == 'Paid' || $inv->status == 'Partially Paid' || $inv->status == 'Credit'){
											if(date('M', strtotime($inv->pay_date)) == 'Jan'){
												if($inv->status == 'Credit') {
													$jan_s += $inv->amt;
												} else {
													$jan_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Feb'){
												if($inv->status == 'Credit') {
													$feb_s += $inv->amt;
												} else {
													$feb_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Mar'){
												if($inv->status == 'Credit') {
													$mar_s += $inv->amt;
												} else {
													$mar_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Apr'){
												if($inv->status == 'Credit') {
													$apr_s += $inv->amt;
												} else {
													$apr_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'May'){
												if($inv->status == 'Credit') {
													$may_s += $inv->amt;
												} else {
													$may_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Jun'){
												if($inv->status == 'Credit') {
													$jun_s += $inv->amt;
												} else {
													$jun_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Jul'){
												if($inv->status == 'Credit') {
													$jul_s += $inv->amt;
												} else {
													$jul_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Aug'){
												if($inv->status == 'Credit') {
													$aug_s += $inv->amt;
												} else {
													$aug_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Sep'){
												if($inv->status == 'Credit') {
													$sep_s += $inv->amt;
												} else {
													$sep_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Oct'){
												if($inv->status == 'Credit') {
													$oct_s += $inv->amt;
												} else {
													$oct_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Nov'){
												if($inv->status == 'Credit') {
													$nov_s += $inv->amt;
												} else {
													$nov_s += $inv->paid;
												}
											} else if(date('M', strtotime($inv->pay_date)) == 'Dec'){
												if($inv->status == 'Credit') {
													$dec_s += $inv->amt;
												} else {
													$dec_s += $inv->paid;
												}
											}
										}
									}
								}
							}
							//========== END SALES ============
							
							//========== START EXPENSES ============
							$get_exp_cat = $this->m_expenses->query_expense_cat($store->id);
							if(!empty($get_exp_cat)){
								foreach($get_exp_cat as $exp_cat){
									$get_exp = $this->m_expenses->query_expense($exp_cat->id);
									if(!empty($get_exp)){
										foreach($get_exp as $exp){
											//get monthly stat to populate chart
											if(date('Y', strtotime($exp->exp_date)) == date('Y')){
												if(date('M', strtotime($exp->exp_date)) == 'Jan'){
													$jan_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Feb'){
													$feb_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Mar'){
													$mar_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Apr'){
													$apr_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'May'){
													$may_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Jun'){
													$jun_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Jul'){
													$jul_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Aug'){
													$aug_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Sep'){
													$sep_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Oct'){
													$oct_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Nov'){
													$nov_e += $exp->price;
												} else if(date('M', strtotime($exp->exp_date)) == 'Dec'){
													$dec_e += $exp->price;
												}
											}
										}
									}
								}
							}
							//========== END EXPENSES ============
						}
					}
				}
			}
			
			//sales
			$jan_s = array($jan_s); $feb_s = array($feb_s); $mar_s = array($mar_s); $apr_s = array($apr_s); $may_s = array($may_s); $jun_s = array($jun_s); $jul_s = array($jul_s); $aug_s = array($aug_s); $sep_s = array($sep_s); $oct_s = array($oct_s); $nov_s = array($nov_s); $jan_s = array($dec_s);
			
			//expenses
			$jan_e = array($jan_e); $feb_e = array($feb_e); $mar_e = array($mar_e); $apr_e = array($apr_e); $may_e = array($may_e); $jun_e = array($jun_e); $jul_e = array($jul_e); $aug_e = array($aug_e); $sep_e = array($sep_e); $oct_e = array($oct_e); $nov_e = array($nov_e); $jan_e = array($dec_e);
			////////////////////////////// ==== CHART LOGIC === //////////////////////////////
		?>
		<script type="text/javascript">
			var currValue = '<?php echo $cur_currency; ?>';
			var sales_array = [[1, <?php echo json_encode($jan_s); ?>], [2, <?php echo json_encode($feb_s); ?>], [3, <?php echo json_encode($mar_s); ?>], [4, <?php echo json_encode($apr_s); ?>], [5, <?php echo json_encode($may_s); ?>], [6, <?php echo json_encode($jun_s); ?>], [7, <?php echo json_encode($jul_s); ?>], [8, <?php echo json_encode($aug_s); ?>], [9, <?php echo json_encode($sep_s); ?>], [10, <?php echo json_encode($oct_s); ?>], [11, <?php echo json_encode($nov_s); ?>], [12, <?php echo json_encode($dec_s); ?>]];
			var expenses_array = [[1, <?php echo json_encode($jan_e); ?>], [2, <?php echo json_encode($feb_e); ?>], [3, <?php echo json_encode($mar_e); ?>], [4, <?php echo json_encode($apr_e); ?>], [5, <?php echo json_encode($may_e); ?>], [6, <?php echo json_encode($jun_e); ?>], [7, <?php echo json_encode($jul_e); ?>], [8, <?php echo json_encode($aug_e); ?>], [9, <?php echo json_encode($sep_e); ?>], [10, <?php echo json_encode($oct_e); ?>], [11, <?php echo json_encode($nov_e); ?>], [12, <?php echo json_encode($dec_e); ?>]];
		</script>
        
        <script src="<?php echo base_url(); ?>js/pages/compCharts.js"></script>
        <script>$(function(){ CompCharts.init(); });</script>
        <?php } ?>
        
        <?php if($page_active=='store' || $page_active=='staff' || $page_active=='customer' || $page_active=='product' || $page_active=='service' || $page_active=='invoice' || $page_active=='expense' || $page_active=='accounts' || $page_active=='stores' || $page_active=='industry' || $page_active=='blogs' || $page_active=='sms' || $page_active=='statement'){ ?>
        <script src="<?php echo base_url(); ?>js/pages/ecomProducts.js"></script>
        <script>$(function(){ EcomProducts.init(); });</script>
        <?php } ?>
        
        <script src="<?php echo base_url(); ?>js/helpers/ckeditor/ckeditor.js"></script>
        
        <?php if($page_active=='store' || $page_active=='staff' || $page_active=='customer' || $page_active=='invoice' || $page_active=='product'){ ?>
		<script type="text/javascript">
			$('#switch-divs .btn').on('click', function() {
				$('#switch-divs .btn').removeClass('active');
				$(this).addClass('active');
			});
			$('#div1_click').on('click', function() {
				$('#div2').hide();
				$('#div1').show();
				$('#dist').val(1);
			});
			$('#div2_click').on('click', function() {
				$('#div1').hide();
				$('#div2').show();
				$('#dist').val(0);
			});
			
			$( document ).on('change', '.OrgSelectPdt', function() {
				inputPrice = $(this).closest('tr').find("[name='price[]']");
				
				$.ajax({
					url: "<?php echo base_url(); ?>" + "invoices/display_amt",
					type: 'POST',
					/*dataType: 'json',*/
					data: { id: $(this).val() },
					success:function(data) {
						inputPrice.val(data);
					}
				});
			});
		</script>
        <?php } ?>
        
        <?php if($page_active=='invoice'){ ?>
        <script src="<?php echo base_url(); ?>js/pages/tablesGeneral.js"></script>
        <script>$(function(){ TablesGeneral.init(); });</script>
        <script>
			/* === CLONE ROW === */
			$('#createClone').on('click', function(e) {
				$( '.tbParent' )
					.append( '<tr>' + $( 'tr.tbChild' ).html()  + '</tr>' );
			
				$( '.sno' ).each(function( index ) {
					$( this ).text(index+1);
					
					if (index > 0) {
						$( this ).parent().find( '.removeClone' ).removeClass('disabled');
					}
				});
				
				$( ".tbSubTotal" ).last().text('0.00');
	
				return false;
			});
			
			$( document ).on('click', '.removeClone', function() {
				$(this).parents().eq(1).remove();
				
				$( '.sno' ).each(function( index ) {
					$( this ).text(index+1);
				});	
				
				compute_invoice();
				
				if ( $(this).attr('data-id').length ) {
					$.ajax({
						url: "<?php echo base_url(); ?>" + "invoices/delete_item",
						type: 'post',
						dataType: 'json',
						data: { id: $(this).attr('data-id') },
						success:function(data) {
						},
					});
				}
			});
			
			$( document ).on('change', '.tbCloneSelect2', function() {
				inputPrice = $(this).closest('tr').find("[name='price[]']");
				
				$.ajax({
					url: "<?php echo base_url(); ?>" + "invoices/display_amt",
					type: 'POST',
					/*dataType: 'json',*/
					data: { id: $(this).val() },
					success:function(data) {
						inputPrice.val(data);
					}
				});
			});
			
			$( document ).on("click change paste keyup", ".tbEvent", function() {
				var qty			= $(this).closest('tr').find("[name='qty[]']").val();
				var price		= $(this).closest('tr').find("[name='price[]']").val();
				var discount	= $(this).closest('tr').find("[name='discount[]']").val();	
				var type		= $(this).closest('tr').find("[name='type[]']").val();
				var vat			= $(this).closest('tr').find("[name='vat[]']").val();	
				var subTotal	= 0;
				var sumSub		= 0;
				var sumSubNoVat	= 0;
				var sumVat		= 0;
				var total		= 0;
				
				$( '.tbTotalE' ).text( '' );
				
				itemQty			= parseFloat(qty)  > 0 		? parseFloat(qty).toFixed(2) 		: 0;
				itemPrice		= parseFloat(price)  > 0 	? parseFloat(price).toFixed(2) 		: 0;
				itemDiscount	= parseFloat(discount) > 0	? parseFloat(discount).toFixed(2)	: 0;
				
				tbValue 		= itemQty * itemPrice;
				tbPrice			= tbValue;
				tbDiscount		= itemDiscount;
				
				if ( type == 'Percent' ) {
					tbDiscount	= tbPrice * (itemDiscount / 100);
				}
				
				subTotal		= tbPrice - tbDiscount;

				var subTotalNoVat = subTotal;
				
				vat = parseFloat(vat);
				if(vat > 0){
					vat = vat / 100;
					subTotal += (subTotal * vat);
				}
				
				$(this).closest('tr').find(".tbSubTotal").text( subTotal.toFixed(2) );
				$(this).closest('tr').find(".tbSubTotalNoVat").text( subTotalNoVat.toFixed(2) );
				
				$( '.tbSubTotalNoVat' ).each(function() {
					$(this).parent().find('#SubTotal').val(parseFloat($(this).text()));
					sumSubNoVat += parseFloat($(this).text());
					sumSub += parseFloat($(this).text());
					var selfVat = $(this).closest('tr').find("[name='vat[]']").val();
					selfVat = parseFloat(selfVat);
					if(selfVat > 0){
						selfVat = selfVat / 100;
						var toVat = parseFloat($(this).text());
						sumVat += (toVat * selfVat);
					}
				});

				$( '.tbSubTotal' ).each(function() {
					subTotal = parseFloat($(this).text());
				});

				total = sumSub + sumVat;

				$( '.tbSumSubTotal' ).text(formatNumber((sumSubNoVat).toFixed(2)));
				$('#tbSumSubTotal').val(sumSubNoVat);

				$( '.tbVat' ).text(formatNumber((sumVat).toFixed(2)));
				$('#tbVat').val(sumVat);
				
				$( '.tbSumTotal' ).text(formatNumber((total).toFixed(2)));
				$('#tbSumTotal').val(total);
			});
			/* === END CLONE ROW === */

			function formatNumber(num) {
				return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
			}

			function compute_invoice() { $('.tbEvent').click(); }
		</script>
        <?php } ?>
    </body>
</html>