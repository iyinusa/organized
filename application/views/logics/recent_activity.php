<?php
    $ra_indicator = '';
	$ra_item = '';
	$ra_all_item = '';
	$ra_count = 0;
		
	$a_all_stores = $this->m_stores->query_user_stores($log_user_id);
	if(!empty($a_all_stores)) {
		foreach($a_all_stores as $a_stores){
			$a_allstore = $this->m_stores->query_store_id($a_stores->store_id);
			if(!empty($a_allstore)){
				foreach($a_allstore as $a_store){
					$r_act = $this->users->query_store_activity($a_store->id);
					if(!empty($r_act)){
						foreach($r_act as $ract){
							$ra_type = $ract->type;
							$ra_store_id = $ract->store_id;
							$ra_p_id = $ract->p_id;
							$ra_s_id = $ract->s_id;
							$ra_content = $ract->content;
							$ra_reg_date = $ract->reg_date;
							
							$ra_reg_date_ago = timespan(strtotime($ra_reg_date), time());
							$ra_reg_date_ago = explode(', ',$ra_reg_date_ago);
							$ra_reg_date_ago = $ra_reg_date_ago[0];
							
							if($ra_type == 'new_product'){
								$ra_icon = 'gi gi-cargo';
								$ra_indicate = 'success';
								$ra_title = 'Product';
							} else if($ra_type == 'new_service'){
								$ra_icon = 'gi gi-tablet';
								$ra_indicate = 'info';
								$ra_title = 'Service';
							} else if($ra_type == 'new_invoice'){
								$ra_icon = 'gi gi-database_plus';
								$ra_indicate = 'warning';
								$ra_title = 'Invoice';
							} else if($ra_type == 'new_expense'){
								$ra_icon = 'gi gi-money';
								$ra_indicate = 'danger';
								$ra_title = 'Expenses';
							} else if($ra_type == 'new_stock'){
								$ra_icon = 'gi gi-cargo';
								$ra_indicate = 'success';
								$ra_title = 'Stock';
							} else {
								$ra_icon = 'fa fa-thumbs-up';
								$ra_indicate = 'primary';
								$ra_title = 'Organized';
							}
							
							if($ra_count < 5){
								$ra_item .= '
									<div class="alert alert-'.$ra_indicate.' alert-alt">
										<small>'.$ra_reg_date_ago.' ago</small><br>
										<i class="'.$ra_icon.' fa-fw"></i> '.$ra_content.'
									</div>
								';
							}
							
							$ra_all_item .= '
								<li class="alert alert-'.$ra_indicate.'">
									<div class="timeline-icon"><i class="'.$ra_icon.'"></i></div>
									<div class="timeline-time">'.$ra_reg_date_ago.' ago</div>
									<div class="timeline-content">
										<p class="push-bit"><strong>'.$ra_title.'</strong></p>
										'.$ra_content.'
									</div>
								</li>
							';
							
							$ra_count += 1;
						}
					}
				}
			}
		}
	}
?>