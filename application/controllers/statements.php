<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statements extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('users'); //load MODEL
		$this->load->model('m_stores'); //load MODEL
		$this->load->model('m_invoices'); //load MODEL
		$this->load->model('m_products'); //load MODEL
		$this->load->model('m_services'); //load MODEL
		$this->load->model('m_expenses'); //load MODEL
		
		//mail config settings
		$this->load->library('email'); //load email library
		//$config['protocol'] = 'sendmail';
		//$config['mailpath'] = '/usr/sbin/sendmail';
		//$config['charset'] = 'iso-8859-1';
		//$config['wordwrap'] = TRUE;
		
		//$this->email->initialize($config);
    }
	
	public function index()
	{
		if($this->session->userdata('logged_in') == TRUE){
			$log_role = $this->session->userdata('org_user_role');
			if(strtolower($log_role) == 'user'){
				$data['module_access'] = FALSE;
			} else {
				$data['module_access'] = TRUE;	
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'Income Statements | '.app_name;
		$data['page_active'] = 'statement';
		
		$this->load->view('designs/header', $data);
		$this->load->view('statements/statements', $data);
		$this->load->view('designs/footer', $data);
	}

	public function vat()
	{
		if($this->session->userdata('logged_in') == TRUE){
			$log_role = $this->session->userdata('org_user_role');
			if(strtolower($log_role) == 'user'){
				$data['module_access'] = FALSE;
			} else {
				$data['module_access'] = TRUE;	
			}
		} else {
			//register redirect page
			$s_data = array ('org_redirect' => uri_string());
			$this->session->set_userdata($s_data);
			redirect(base_url('auth/'), 'refresh');
		}
		
		$log_user_id = $this->session->userdata('org_id');
		
		$all_stores = $this->m_stores->query_user_stores($log_user_id);
		if(!empty($all_stores)) {
			$data['allstores'] = $all_stores;
		}
		
		$data['title'] = 'VAT Statements | '.app_name;
		$data['page_active'] = 'statement';
		
		$this->load->view('designs/header', $data);
		$this->load->view('statements/vat', $data);
		$this->load->view('designs/footer', $data);
	}

	public function pay_vat() {
		if($_POST) {
			$resp = array();

			$store = $this->input->post('store');
			$amount = $this->input->post('amount');
			$date = $this->input->post('date');
			$remark = $this->input->post('remark');

			if(!$store || !$amount || !$date) {
				$resp['type'] = 'error';
				$resp['msg'] = $this->crud->msg('danger', 'Outlet, Amount and Date are required');
			} else {
				$reg_data['store_id'] = $store;
				$reg_data['amount'] = $amount;
				$reg_data['pay_date'] = date('Y-m-d H:i:s', strtotime($date));
				$reg_data['remark'] = $remark;
				$reg_data['reg_date'] = date('Y-m-d H:i:s');
				if($this->crud->create('vat_payment', $reg_data) > 0) {
					$resp['type'] = 'success';
					$resp['msg'] = $this->crud->msg('success', 'VAT Remitted - please refresh for changes');
				} else {
					$resp['type'] = 'error';
					$resp['msg'] = $this->crud->msg('danger', 'Please try later');
				}
			}

			echo json_encode($resp);
		}
	}

	public function filter() {
		if($_POST) {
			$store = $this->input->post('store');
			$start = $this->input->post('start');
			$end = $this->input->post('end');

			$log_user_id = $this->session->userdata('org_id');
			$report_box = '';

			if($store) {
				$all_stores = $this->m_stores->query_user_stores_id($store);
			} else {
				$all_stores = $this->m_stores->query_user_stores($log_user_id);
			}

			if(!empty($all_stores)) {
				foreach($all_stores as $st) {
					if($start && $end) {
						$income_note = 'For the Period Between <b>'.date('d M Y', strtotime($start)).'</b> to <b>'.date('d M Y', strtotime($end)).'</b>';
					} else {
						$income_note = 'For Period Ending 31 Dec '.date('Y');
					}

					if($store) {
						$store_id = $store;
					} else {
						$store_id = $st->store_id;
					}

					// statement heading
					$company_name = strtoupper($this->crud->read_field('id', $store_id, 'store', 'store'));
					$company = '
						<table width="100%">
							<tr>
								<td style="border:none;"><h4><b>'.$company_name.'</b></h4><small>'.date('d M Y').'</small></td>
								<td width="30%" align="right" style="border:none;"><h4>INCOME STATEMENT</h4><small>'.$income_note.'</small></td>
							</tr>
						</table>
					';

					// product array 
					$products = array();
					$prodcat = $this->crud->read_single('store_id', $store_id, 'product_cat');
					if(!empty($prodcat)) {
						foreach($prodcat as $pcat) {
							$prod = $this->crud->read_single('cat_id', $pcat->id, 'product');
							if(!empty($prod)) {
								foreach($prod as $p) {
									$products[] = $p->id;
								}
							}
						}
					}

					// service array 
					$services = array();
					$servcat = $this->crud->read_single('store_id', $store_id, 'service_cat');
					if(!empty($servcat)) {
						foreach($servcat as $scat) {
							$serv = $this->crud->read_single('cat_id', $scat->id, 'service');
							if(!empty($serv)) {
								foreach($serv as $s) {
									$services[] = $s->id;
								}
							}
						}
					}

					// incomes
					$vat_sum = 0; $sales_sum = 0; $product_sales = array(); $service_sales = array();
					if($start && $end) {
						$incomes = $this->m_invoices->query_user_invoice_by_dates($store_id, $start, $end);
					} else {
						$incomes = $this->m_invoices->query_user_invoice_by_year($store_id);
					}
					if(!empty($incomes)) {
						foreach($incomes as $ic) {
							$ic_status = $ic->status;

							if($ic->status == 'Paid' || $ic->status == 'Credit') {
								$vat_sum += $ic->vat;

								// by products/services
								$invoiceitems = $this->crud->read_single('invoice_id', $ic->id, 'invoice_item');
								if(!empty($invoiceitems)) {
									foreach($invoiceitems as $iv) {
										$iv_subtotal = (float)$iv->sub_total + (float)$iv->vat_value;
										$iv_subtotal += ($iv_subtotal * 0.05);
										
										if(in_array($iv->pdt_id, $products)) {
											$good = $iv->pdt_id;
											$pdt_key = array_search($iv->pdt_id, $products);
											if(empty($product_sales[$pdt_key])) {
												$product_sales[$pdt_key] = $iv_subtotal;
											} else {
												$product_sales[$pdt_key] += $iv_subtotal;
											}	
										}
										if(in_array($iv->service_id, $services)) {
											$serv_key = array_search($iv->service_id, $services);
											if(empty($service_sales[$serv_key])) {
												$service_sales[$serv_key] = $iv_subtotal;
											} else {
												$service_sales[$serv_key] += $iv_subtotal;
											}
										}
										$sales_sum += $iv_subtotal;;
									}
								}
							}
						}
					}

					$product_income = '';
					if(!empty($products)) {
						foreach($products as $key=>$value) {
							$product_name = $this->crud->read_field('id', $value, 'product', 'name');
							$pdt_amt = 0;
							if(!empty($product_sales[$key])) {$pdt_amt = $product_sales[$key];}

							$product_income .= '
								<tr>
									<td>'.$product_name.'</td>
									<td width="150px" align="right">&#8358;'.number_format($pdt_amt,2).'</td>
								</tr>
							';
						}
					}

					$service_income = '';
					if(!empty($services)) {
						foreach($services as $key=>$value) {
							$service_name = $this->crud->read_field('id', $value, 'service', 'name');
							$serv_amt = 0;
							if(!empty($service_sales[$key])) {$serv_amt = $service_sales[$key];}

							$service_income .= '
								<tr>
									<td>'.$service_name.'</td>
									<td width="150px" align="right">&#8358;'.number_format($serv_amt,2).'</td>
								</tr>
							';
						}
					}

					// expenses
					$exp_sum = 0; $cos_sum = 0; $oe_sum = 0; $cost_of_sales = ''; $operating_expenses = '';
					$catall = $this->crud->read_single('store_id', $store_id, 'expense_cat');
					if(!empty($catall)) {
						foreach($catall as $ccat) {
							$cat_id = $ccat->id;
							$cat_name = $ccat->cat;
							$cat_type = $ccat->type;

							if($start && $end) {
								$expenses = $this->m_expenses->query_expense_dates($cat_id, $start, $end);
							} else {
								$expenses = $this->m_expenses->query_expense_by_year($cat_id);
							}

							$add_cos = 0; $add_oe = 0;
							if(!empty($expenses)) {
								foreach($expenses as $exp) {
									$exp_price = (float)$exp->price;
									if($cat_type == 'Cost of Sales') {
										$add_cos += $exp_price;
										$cos_sum += $exp_price;
									} else {
										$add_oe += $exp_price;
										$oe_sum += $exp_price;
									}
								}
							}

							if($cat_type == 'Cost of Sales') {
								$cost_of_sales .= '
									<tr>
										<td>'.$cat_name.'</td>
										<td width="150px" align="right">&#8358;'.number_format($add_cos,2).'</td>
									</tr>
								';
							} else {
								$operating_expenses .= '
									<tr>
										<td>'.$cat_name.'</td>
										<td width="150px" align="right">&#8358;'.number_format($add_oe,2).'</td>
									</tr>
								';
							}
						}
					}
					$exp_sum = $cos_sum + $oe_sum;

					// compute total
					$gross = 0; $net = 0; $net_vat = 0;
					$gross = $sales_sum - $cos_sum;
					$net = $gross - $oe_sum;
					$net_vat = $net - $vat_sum;

					if($gross < 0) {
						$gross = ltrim($gross,'-');
						$gross = '<span style="color:red;">(&#8358;'.number_format($gross,2).')</span>';
					} else {
						$gross = '<span style="color:green;">&#8358;'.number_format($gross,2).'</span>';
					}

					if($net < 0) {
						$net = ltrim($net,'-');
						$net = '<span style="color:red;">(&#8358;'.number_format($net,2).')</span>';
					} else {
						$net = '<span style="color:green;">&#8358;'.number_format($net,2).'</span>';
					}

					if($net_vat < 0) {
						$net_vat = ltrim($net_vat,'-');
						$net_vat = '<span style="color:red;">(&#8358;'.number_format($net_vat,2).')</span>';
					} else {
						$net_vat = '<span style="color:green;">&#8358;'.number_format($net_vat,2).'</span>';
					}

					$report_box .= '
						<style>
							table {width:100%;}
							table td {border:1px solid #ddd; padding:5px;}
							table tr.st_head {background-color:#efefef; text-transform:uppercase; font-size:18px; font-weight:bold;}
							table tr.st_subhead {text-transform:uppercase; font-size:12px; font-weight:bold;}
							table tr.st_subtotal {border-top:3px double #ddd; font-weight:bold;}
							table tr.st_subtotal td {text-transform:uppercase; font-size:14px;}
							table tr.st_total {border-top:3px double #ddd; font-weight:bold;}
							table tr.st_total td {text-transform:uppercase; font-size:16px;}
							table tr.st_exptotal {border-top:2px double #ddd; font-weight:bold;}
							table tr.st_exptotal td {text-transform:uppercase; font-size:10px; color:#999;}
						</style>

						<div class="block full">
							<div class="row">
								<div class="col-lg-12">
									'.$company.'<br />
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_head"><td>Revenue</td></tr>
									</table>

									<table width="100%">
										<tr class="st_subhead"><td colspan="2">Products</td></tr>
										'.$product_income.'
									</table>

									<table width="100%">
										<tr class="st_subhead"><td colspan="2">Services</td></tr>
										'.$service_income.'
									</table>

									<table width="100%">
										<tr class="st_subtotal">
											<td>Total Revenue</td>
											<td width="150px" align="right">&#8358;'.number_format($sales_sum,2).'</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_head"><td>Expenses</td></tr>
									</table>

									<table width="100%">
										<tr class="st_subhead"><td colspan="2">Cost of Sales</td></tr>
										'.$cost_of_sales.'
										<tr class="st_exptotal">
											<td>Total Cost of Sales</td>
											<td width="150px" align="right">&#8358;'.number_format($cos_sum,2).'</td>
										</tr>
									</table>

									<table width="100%">
										<tr class="st_total" style="background-color:#efefef;">
											<td>Gross Profit <span style="color:red;">(Loss)</span></td>
											<td width="150px" align="right">'.$gross.'</td>
										</tr>
									</table>

									<table width="100%">
										<tr class="st_subhead"><td colspan="2">Operating Expenses</td></tr>
										'.$operating_expenses.'
										<tr class="st_exptotal">
											<td>Total Operating Expenses</td>
											<td width="150px" align="right">&#8358;'.number_format($oe_sum,2).'</td>
										</tr>
									</table>

									<table width="100%">
										<tr class="st_subtotal">
											<td>Total Expenses</td>
											<td width="150px" align="right">&#8358;'.number_format($exp_sum,2).'</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_total" style="background-color:#efefef;">
											<td>Net Profit <span style="color:red;">(Loss)</span> Before TAX</td>
											<td width="150px" align="right">'.$net_vat.'</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					';
				}
			}

			echo $report_box;
		}
	}

	public function vat_filter() {
		if($_POST) {
			$store = $this->input->post('store');
			$start = $this->input->post('start');
			$end = $this->input->post('end');

			$log_user_id = $this->session->userdata('org_id');
			$report_box = '';

			if($store) {
				$all_stores = $this->m_stores->query_user_stores_id($store);
			} else {
				$all_stores = $this->m_stores->query_user_stores($log_user_id);
			}

			if(!empty($all_stores)) {
				foreach($all_stores as $st) {
					if($start && $end) {
						$income_note = 'For the Period Between <b>'.date('d M Y', strtotime($start)).'</b> to <b>'.date('d M Y', strtotime($end)).'</b>';
					} else {
						$income_note = 'For Period Ending 31 Dec '.date('Y');
					}

					if($store) {
						$store_id = $store;
					} else {
						$store_id = $st->store_id;
					}

					// statement heading
					$company_name = strtoupper($this->crud->read_field('id', $store_id, 'store', 'store'));
					$company = '
						<table width="100%">
							<tr>
								<td style="border:none;"><h4><b>'.$company_name.'</b></h4><small>'.date('d M Y').'</small></td>
								<td width="30%" align="right" style="border:none;"><h4>VAT STATEMENT</h4><small>'.$income_note.'</small></td>
							</tr>
						</table>
					';

					
					$jan_p=''; $feb_p=''; $mar_p=''; $apr_p=''; $may_p=''; $jun_p=''; $jul_p=''; $aug_p=''; $sep_p=''; $oct_p=''; $nov_p=''; $dec_p='';

					$jan_r=''; $feb_r=''; $mar_r=''; $apr_r=''; $may_r=''; $jun_r=''; $jul_r=''; $aug_r=''; $sep_r=''; $oct_r=''; $nov_r=''; $dec_r='';

					$vat_payables = ''; $vat_payables_sum = 0; $vat_remitted = ''; $vat_remitted_sum = 0; $net_vat = 0;

					// get vat payables
					if($start && $end) {
						$payables = $this->crud->query_invoice_by_dates($store_id, $start, $end);
					} else {
						$payables = $this->crud->query_invoice_by_year($store_id);
					}
					if(!empty($payables)) {
						foreach($payables as $pay) {
							$inv_pay_date = $pay->pay_date;
							$inv_paydate = date('d M, Y', strtotime($inv_pay_date));
							$inv_status = $pay->status;
							$inv_vat = $pay->vat;

							if($inv_status == 'Paid') {
								$vat_payables_sum += $inv_vat;
								if(date('M', strtotime($inv_pay_date)) == 'Jan') {
									$jan_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Feb') {
									$feb_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Mar') {
									$mar_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Apr') {
									$apr_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'May') {
									$may_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Jun') {
									$jun_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Jul') {
									$jul_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Aug') {
									$aug_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Sep') {
									$sep_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Oct') {
									$oct_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Nov') {
									$nov_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
								if(date('M', strtotime($inv_pay_date)) == 'Dec') {
									$dec_p .= '
										<tr>
											<td>'.$inv_paydate.'</td>
											<td width="150px" align="right">&#8358;'.number_format($inv_vat,2).'</td>
										</tr>
									';
								}
							}
						}
					}

					// get vat remitted
					if($start && $end) {
						$remitted = $this->crud->query_vat_by_dates($store_id, $start, $end);
					} else {
						$remitted = $this->crud->query_vat_by_year($store_id);
					}
					if(!empty($remitted)) {
						foreach($remitted as $pay) {
							$rem_pay_date = $pay->pay_date;
							$rem_paydate = date('d M, Y', strtotime($rem_pay_date));
							$rem_vat = $pay->amount;

							$vat_remitted_sum += $rem_vat;
							if(date('M', strtotime($rem_pay_date)) == 'Jan') {
								$jan_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Feb') {
								$feb_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Mar') {
								$mar_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Apr') {
								$apr_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'May') {
								$may_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Jun') {
								$jun_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Jul') {
								$jul_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Aug') {
								$aug_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Sep') {
								$sep_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Oct') {
								$oct_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Nov') {
								$nov_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
							if(date('M', strtotime($rem_pay_date)) == 'Dec') {
								$dec_r .= '
									<tr>
										<td>'.$rem_paydate.'</td>
										<td width="150px" align="right">&#8358;'.number_format($rem_vat,2).'</td>
									</tr>
								';
							}
						}
					}

					// format payable items
					if(!empty($jan_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">JANUARY</td></tr>
							'.$jan_p.'
						';
					}
					if(!empty($feb_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">FEBRUARY</td></tr>
							'.$feb_p.'
						';
					}
					if(!empty($mar_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">MARCH</td></tr>
							'.$mar_p.'
						';
					}
					if(!empty($apr_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">APRIL</td></tr>
							'.$apr_p.'
						';
					}
					if(!empty($may_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">MAY</td></tr>
							'.$may_p.'
						';
					}
					if(!empty($jun_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">JUNE</td></tr>
							'.$jun_p.'
						';
					}
					if(!empty($jul_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">JULY</td></tr>
							'.$jul_p.'
						';
					}
					if(!empty($aug_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">AUGUST</td></tr>
							'.$aug_p.'
						';
					}
					if(!empty($sep_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">SEPTEMBER</td></tr>
							'.$sep_p.'
						';
					}
					if(!empty($oct_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">OCTOBER</td></tr>
							'.$oct_p.'
						';
					}
					if(!empty($nov_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">NOVEMBER</td></tr>
							'.$nov_p.'
						';
					}
					if(!empty($dec_p)) {
						$vat_payables .= '
							<tr class="st_subhead"><td colspan="2">DECEMBER</td></tr>
							'.$dec_p.'
						';
					}

					// format remitted items
					if(!empty($jan_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">JANUARY</td></tr>
							'.$jan_r.'
						';
					}
					if(!empty($feb_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">FEBRUARY</td></tr>
							'.$feb_r.'
						';
					}
					if(!empty($mar_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">MARCH</td></tr>
							'.$mar_r.'
						';
					}
					if(!empty($apr_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">APRIL</td></tr>
							'.$apr_r.'
						';
					}
					if(!empty($may_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">MAY</td></tr>
							'.$may_r.'
						';
					}
					if(!empty($jun_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">JUNE</td></tr>
							'.$jun_r.'
						';
					}
					if(!empty($jul_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">JULY</td></tr>
							'.$jul_r.'
						';
					}
					if(!empty($aug_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">AUGUST</td></tr>
							'.$aug_r.'
						';
					}
					if(!empty($sep_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">SEPTEMBER</td></tr>
							'.$sep_r.'
						';
					}
					if(!empty($oct_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">OCTOBER</td></tr>
							'.$oct_r.'
						';
					}
					if(!empty($nov_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">NOVEMBER</td></tr>
							'.$nov_r.'
						';
					}
					if(!empty($dec_r)) {
						$vat_remitted .= '
							<tr class="st_subhead"><td colspan="2">DECEMBER</td></tr>
							'.$dec_r.'
						';
					}

					$net_vat = $vat_payables_sum - $vat_remitted_sum;

					$report_box .= '
						<style>
							table {width:100%;}
							table td {border:1px solid #ddd; padding:5px;}
							table tr.st_head {background-color:#efefef; text-transform:uppercase; font-size:18px; font-weight:bold;}
							table tr.st_subhead {text-transform:uppercase; font-size:12px; font-weight:bold;}
							table tr.st_subtotal {border-top:3px double #ddd; font-weight:bold;}
							table tr.st_subtotal td {text-transform:uppercase; font-size:14px;}
							table tr.st_total {border-top:3px double #ddd; font-weight:bold;}
							table tr.st_total td {text-transform:uppercase; font-size:16px;}
							table tr.st_exptotal {border-top:2px double #ddd; font-weight:bold;}
							table tr.st_exptotal td {text-transform:uppercase; font-size:10px; color:#999;}
						</style>

						<div class="block full">
							<div class="row">
								<div class="col-lg-12">
									'.$company.'<br />
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_head"><td>VAT PAYABLE</td></tr>
									</table>

									<table width="100%">
										'.$vat_payables.'
									</table>

									<table width="100%">
										<tr class="st_subtotal">
											<td>Total VAT Payable</td>
											<td width="150px" align="right">&#8358;'.number_format($vat_payables_sum,2).'</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_head"><td>VAT REMITTED</td></tr>
									</table>

									<table width="100%">
										'.$vat_remitted.'
									</table>

									<table width="100%">
										<tr class="st_subtotal">
											<td>Total VAT REMITTED</td>
											<td width="150px" align="right">&#8358;'.number_format($vat_remitted_sum,2).'</td>
										</tr>
									</table>
								</div>

								<div class="col-lg-12">
									<table width="100%">
										<tr class="st_total" style="background-color:#efefef;">
											<td>Net VAT</td>
											<td width="150px" align="right">&#8358;'.number_format($net_vat,2).'</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					';
				}
			}

			echo $report_box;
		}
	}
}