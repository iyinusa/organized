<?php
   	$rb_item = '';
	$rb_item_sel = '';
	$rb_all_item = '';
	$rb_all_item_list = '';
	$rb_count = 0;
		
	$b_all_stores = $this->m_stores->query_user_stores($log_user_id);
	if(!empty($b_all_stores)) {
		foreach($b_all_stores as $b_stores){
			$b_allstore = $this->m_stores->query_store_id($b_stores->store_id);
			if(!empty($b_allstore)){
				foreach($b_allstore as $b_store){
					$b_act = $this->m_customers->query_customer($b_store->id);
					if(!empty($b_act)){
						foreach($b_act as $bact){
							if(date('d M') == date('d M', strtotime($bact->dob))){
								if($rb_count < 3){
									$rb_item .= '
										<div class="alert alert-info alert-alt">
											<i class="gi gi-birthday_cake"></i> 
											<a href="javascript:void(0)" class="enable-tooltip" data-placement="top" title="Send Birthday Wish" onclick="$(\'#modal-birthday-wish\').modal(\'show\');">
												'.$bact->firstname.' '.$bact->lastname.' ('.substr($b_store->store,0,5).'...)
											</a>
										</div>
									';
								}
								
								$rb_all_item_list .= '
									<div class="alert alert-info alert-alt">
										<i class="gi gi-birthday_cake"></i> 
										<a href="javascript:void(0)" class="enable-tooltip" data-placement="top" title="Send Birthday Wish" onclick="$(\'#modal-birthday-wish\').modal(\'show\');">
											'.$bact->firstname.' '.$bact->lastname.' ('.$b_store->store.')
										</a>
									</div>
								';
								
								$rb_count += 1;
								
								$rb_item_sel .= '<option value="'.$bact->email.'">'.$bact->firstname.' '.$bact->lastname.' ('.$b_store->store.')</option>';
							}
						}
					}
					
					//group by store
					if($rb_all_item_list == ''){
						$rb_all_item_list = '<h5 class="text-muted">&nbsp;&nbsp;&nbsp;No Birthday List Today</h5>';	
					}
					
					$rb_all_item .= '
						<h4>'.$b_store->store.'</h4>
						'.$rb_all_item_list.'
					';
					$rb_all_item_list = '';
				}
			}
		}
	}
?>