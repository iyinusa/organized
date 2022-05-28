<?php
    //get active menu
	if($page_active=='dashboard'){$d_dash_act='active';}else{$d_dash_act='';}
	if($page_active=='store' || $page_active=='staff'){$d_store_act='active';}else{$d_store_act='';}
	if($page_active=='customer' || $page_active=='contact'){$d_client_act='active';}else{$d_client_act='';}
	if($page_active=='product'){$d_product_act='active';}else{$d_product_act='';}
	if($page_active=='service'){$d_service_act='active';}else{$d_service_act='';}
	if($page_active=='invoice'){$d_invoice_act='active';}else{$d_invoice_act='';}
	if($page_active=='expense'){$d_expense_act='active';}else{$d_expense_act='';}
?>

<div class="content-header">
    <ul class="nav-horizontal text-center">
        <li class="<?php echo $d_dash_act; ?>">
            <a href="<?php echo base_url('dashboard/'); ?>"><i class="gi gi-dashboard"></i> Dashboard</a>
        </li>
        <li class="<?php echo $d_store_act; ?>">
            <a href="<?php echo base_url('stores/'); ?>"><i class="gi gi-shopping_cart"></i> Outlets</a>
        </li>
        <li class="<?php echo $d_client_act; ?>">
            <a href="<?php echo base_url('customers/'); ?>"><i class="gi gi-address_book"></i> Contacts</a>
        </li>
        <li class="<?php echo $d_product_act; ?>">
            <a href="<?php echo base_url('products/'); ?>"><i class="gi gi-cargo"></i> Products</a>
        </li>
        <li class="<?php echo $d_service_act; ?>">
            <a href="<?php echo base_url('services/'); ?>"><i class="gi gi-tablet"></i> Services</a>
        </li>
        <li class="<?php echo $d_invoice_act; ?>">
            <a href="<?php echo base_url('invoices/'); ?>"><i class="gi gi-database_plus"></i> Sales</a>
        </li>
        <li class="<?php echo $d_expense_act; ?>">
            <a href="<?php echo base_url('expenses/'); ?>"><i class="gi gi-money"></i> Expenses</a>
        </li>
    </ul>
</div>