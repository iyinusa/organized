<?php
    $sales_today = 0; $expenses_today = 0; $invoice_today = 0; $unpaid_today = 0; $cancel_today = 0; $service_today = 0; $product_today = 0; $total_product = 0; $total_service = 0; $total_week_sales = 0; $total_sales = 0; $total_expenses = 0; $total_earning = 0;
	
	if(!empty($allstores)){
		foreach($allstores as $stores){
			$allstore = $this->m_stores->query_store_id($stores->store_id);
			if(!empty($allstore)){
				foreach($allstore as $store){
					//========== INVOICES ============
					$get_inv = $this->m_invoices->query_user_invoice($stores->store_id);
					if(!empty($get_inv)){
						foreach($get_inv as $inv){
							//get today's stat
							if(date('d M Y') == date('d M Y', strtotime($inv->pay_date))){
								if($inv->status == 'Paid' || $inv->status == 'Partially Paid' || $inv->status == 'Credit'){
									if($inv->status == 'Credit') {
										$sales_today += $inv->amt;
									} else {
										$sales_today += $inv->paid;
									}
								}
								
								if($inv->status == 'Unpaid'){
									$unpaid_today += $inv->amt;
								}
								
								if($inv->status == 'Cancelled'){
									$cancel_today += $inv->amt;
								} else {
									$invoice_today += $inv->amt;	
								}
							}
							
							//get invoice items
							$get_inv_item = $this->m_invoices->query_invoice_item_id($inv->id);
							if(!empty($get_inv_item)){
								foreach($get_inv_item as $inv_item){
									$damt = $inv_item->sub_total + $inv_item->vat_value;

									if(date('d M Y') == date('d M Y', strtotime($inv->pay_date))){
										if($inv_item->pdt_id == 0){
											$service_today += $damt;
										} else {
											$product_today += $damt;	
										}
									}
									
									//get total week sales
									if(date('W') == date('W', strtotime($inv->pay_date))){
										$total_week_sales += $damt;
									}
									
									//total products and services
									if($inv_item->pdt_id == 0){
										if($inv->status == 'Paid' || $inv->status == 'Partially Paid' || $inv->status == 'Credit') {
											$total_service += $damt;
										}
									} else {
										if($inv->status == 'Paid' || $inv->status == 'Partially Paid' || $inv->status == 'Credit') {
											$total_product += $damt;	
										}
									}
								}
							}
						}
					}
					//========== END INVOICES ============
					
					
					//========== EXPENSES ============
					$get_exp_cat = $this->m_expenses->query_expense_cat($store->id);
					if(!empty($get_exp_cat)){
						foreach($get_exp_cat as $exp_cat){
							$get_exp = $this->m_expenses->query_expense($exp_cat->id);
							if(!empty($get_exp)){
								foreach($get_exp as $exp){
									//get today's stat
									if(date('d M Y') == date('d M Y', strtotime($exp->exp_date))){
										$expenses_today += $exp->price;
									}
									
									//get total expenses
									$total_expenses += $exp->price;
								}
							}
						}
					}
					//========== END EXPENSES ============
				}
			}
		}
	}
	
	//get total sales and earning
	$total_sales = $total_product + $total_service;
	$total_earning = ($total_product + $total_service) - $total_expenses;
?>