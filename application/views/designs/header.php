<?php include(APPPATH.'libraries/inc.php'); ?>
<?php include(APPPATH.'views/logics/recent_birthday.php'); ?>
<?php include(APPPATH.'views/logics/recent_activity.php'); ?>
<?php include(APPPATH.'views/logics/recent_chat.php'); ?>
<?php
    //get active menu
	if($page_active=='dashboard'){$dash_act='active';}else{$dash_act='';}
	if($page_active=='store' || $page_active=='staff'){$store_act='active';}else{$store_act='';}
	if($page_active=='customer'){$client_act='active';}else{$client_act='';}
	if($page_active=='product'){$product_act='active';}else{$product_act='';}
	if($page_active=='service'){$service_act='active';}else{$service_act='';}
	if($page_active=='invoice'){$invoice_act='active';}else{$invoice_act='';}
	if($page_active=='expense'){$expense_act='active';}else{$expense_act='';}
	if($page_active=='contact'){$contact_act='active';}else{$contact_act='';}
	if($page_active=='sms'){$sms_act='active';}else{$sms_act='';}
	if($page_active=='statement'){$statement_act='active';}else{$statement_act='';}
	
	//get theme
	if(!empty($log_user_theme)){$acc_theme='themes/'.$log_user_theme;}else{$acc_theme='themes/themes';}
	
	//get notification count in title
	if($log_user == TRUE){
		$top_obj =& get_instance();
		$top_obj->load->model('users');
		//$gen_note_count = count($top_obj->users->query_notify_user_unread($log_user_id));
		//if($gen_note_count > 0){
			//$gen_note='('.$gen_note_count.') ';
		//} else {
			//$gen_note='';
		//}
	} //else {$gen_note='';}
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php //echo $gen_note.$title; ?><?php echo $title; ?></title>

        <meta name="description" content="Your business in your palm. Better approach to organizing your products and services with customers satisfaction">
        <meta name="author" content="<?php echo app_name; ?>">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>img/favicon.png">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/themes.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/<?php echo $acc_theme; ?>.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) & Respond.js (enables responsive CSS code on browsers that don't support it, eg IE8) -->
        <script src="<?php echo base_url(); ?>js/vendor/modernizr-respond.min.js"></script>
        <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-69161235-1', 'auto');
		  ga('send', 'pageview');
		
		</script>
    </head>
    <body>
        <!-- Page Wrapper -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
        <div id="page-wrapper" class="page-loading">
            <!-- Preloader -->
            <!-- Preloader functionality (initialized js/app in.js) - pageLoading() -->
            <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
            
            <div class="preloader themed-background">
                <h1 class="push-top-bottom text-light text-center"><strong><img alt="" src="<?php echo base_url('landing/img/logo70.png') ?>" style="max-width:100%;" /></strong></h1>
                <div class="inner">
                    <h3 class="text-light visible-lt-ie9 visible-lt-ie10"><strong>Loading..</strong></h3>
                    <div class="preloader-spinner hidden-lt-ie9 hidden-lt-ie10"></div>
                </div>
            </div>
            
            <!-- END Preloader -->

            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
                <!-- Alternative Sidebar -->
                <div id="sidebar-alt">
                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-alt-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Chat -->
                            <!-- Chat demo functionality initialized in js/app.js -> chatUi() -->
                            <a href="javascript:void(0)" class="sidebar-title">
                                <i class="gi gi-conversation pull-right"></i> <strong>Chat</strong>
                            </a>
                            <!-- Chat Users -->
                            <ul class="chat-users clearfix">
                                <?php if(!empty($chat_all_user)){echo $chat_all_user;}else{echo '<h5 class="text-muted">No Staff Yet</h5>';} ?>
                            </ul>
                            <!-- END Chat Users -->

                            <!-- Chat Talk -->
                            <?php if(!empty($chat_all_item_list)){echo $chat_all_item_list;} ?>
                            <!--  END Chat Talk -->
                            <!-- END Chat -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
                <!-- END Alternative Sidebar -->

                <!-- Main Sidebar -->
                <div id="sidebar">
                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Brand -->
                            <a href="<?php echo base_url('dashboard'); ?>" class="sidebar-brand">
                                <img alt="" src="<?php echo base_url('landing/img/logo50.png') ?>" style="max-width:100%;" />
                            </a>
                            <a href="javascript:void(0)" class="sidebar-brand text-align" style="background-color:#096;" onclick="$('#modal-feedback').modal('show');">
                                <i class="fa fa-paper-plane-o"></i> FeedUs Back
                            </a>
                            <!-- END Brand -->

                            <!-- User Info -->
                            <div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
                                <div class="sidebar-user-avatar">
                                    <a href="<?php echo base_url('profile'); ?>">
                                        <img src="<?php echo base_url($log_user_pics_small); ?>" alt="avatar">
                                    </a>
                                </div>
                                <div class="sidebar-user-name"><?php echo $log_user_firstname; ?></div>
                                <div class="sidebar-user-links">
                                    <a href="<?php echo base_url('profile'); ?>" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="gi gi-user"></i></a>
                                    <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                                    <a href="javascript:void(0)" class="enable-tooltip" data-placement="bottom" title="Settings" onclick="$('#modal-user-settings').modal('show');"><i class="gi gi-cogwheels"></i></a>
                                    <a href="<?php echo base_url('auth/logout/'); ?>" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="gi gi-exit"></i></a>
                                </div>
                            </div>
                            <!-- END User Info -->

                            <!-- Sidebar Navigation -->
                            <ul class="sidebar-nav">
                                <li class="<?php echo $dash_act; ?>">
                                    <a href="<?php echo base_url('dashboard'); ?>"><i class="gi gi-dashboard sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
                                </li>
                                <li class="<?php echo $store_act; ?>">
                                    <a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-shopping_cart sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Outlets</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('stores/add'); ?>">Add Outlet</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('stores/'); ?>">Manage Outlets</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('staff/add'); ?>">Add Staff</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('staff/'); ?>">Manage Staff</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $client_act; ?>">
                                    <a href="javascript:void(0)" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-address_book sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Supplier/Customer</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('contacts'); ?>">Add Contact</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('customers/'); ?>">Manage Contacts</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $product_act; ?>">
                                    <a href="<?php echo base_url('products/'); ?>" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-cargo sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Products/Stocks</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('products/add_cat'); ?>">Category</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('products/add'); ?>">Add Product</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('products/'); ?>">Manage Products</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('products/stock'); ?>">Product Stocks</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $service_act; ?>">
                                    <a href="<?php echo base_url('services/'); ?>" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-tablet sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Services</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('services/add_cat'); ?>">Category</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('services/add'); ?>">Add Service</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('services/'); ?>">Manage Services</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $invoice_act; ?>">
                                     <a href="<?php echo base_url('invoices/'); ?>" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-database_plus sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Sales</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('invoices/add'); ?>">Add Sales</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('invoices/'); ?>">Manage Sales</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('invoices/debtors'); ?>">Debtors</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $expense_act; ?>">
                                    <a href="<?php echo base_url('expenses/'); ?>" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Expenses</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('expenses/add_cat'); ?>">Category</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('expenses/add'); ?>">Add Expense</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('expenses/'); ?>">Manage Expenses</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php echo $statement_act; ?>">
                                    <a href="<?php echo base_url('statements/'); ?>" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-charts sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Reporting</span></a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url('statements/vat'); ?>">VAT Report</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('statements/'); ?>">Income (Profit/Loss)</a>
                                        </li>
                                        <!--<li>
                                            <a href="<?php echo base_url('statements/cashflow'); ?>">Cash Flow</a>
                                        </li>-->
                                    </ul>
                                </li>
                            </ul>
                            <!-- END Sidebar Navigation -->

                            <!-- Sidebar Notifications -->
                            <div class="sidebar-header sidebar-nav-mini-hide">
                                <span class="sidebar-header-options clearfix">
                                    <a href="<?php echo base_url('dashboard'); ?>" data-toggle="tooltip" title="All Today's Birthdays"><i class="gi gi-birthday_cake"></i></a>
                                </span>
                                <span class="sidebar-header-title">Today's Birthdays</span>
                            </div>
                            <div class="sidebar-section sidebar-nav-mini-hide">
                                <?php if(!empty($rb_item)){echo $rb_item;} else {echo '<div class="alert alert-primary">No Birthday List Today</div>';} ?>
                                <a href="<?php echo base_url('dashboard'); ?>" class="pull-right"><i class="gi gi-bithday_cake"></i> All Birthdays (<?php if(!empty($rb_count)){echo $rb_count;} else {echo '0';} ?>)</a><br />
                            </div>
                            <!-- Birthday ends here -->
                            
                            <!-- Activities starts here -->
                            <div class="sidebar-header sidebar-nav-mini-hide">
                                <span class="sidebar-header-options clearfix">
                                    <a href="<?php echo base_url('activity'); ?>" data-toggle="tooltip" title="All Activities"><i class="gi gi-bullhorn"></i></a>
                                </span>
                                <span class="sidebar-header-title">Activity</span>
                            </div>
                            <div class="sidebar-section sidebar-nav-mini-hide">
                                <?php if(!empty($ra_item)){echo $ra_item;} ?>
                                <a href="<?php echo base_url('activity'); ?>" class="pull-right"><i class="gi gi-bullhorn"></i> All Activities</a>
                            </div>
                            <!-- END Sidebar Notifications -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
                <!-- END Main Sidebar -->
                
                <!-- Main Container -->
                <div id="main-container">
                    <!-- Header -->
                    <header class="navbar navbar-default">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');
                                        this.blur();">
                                    <i class="fa fa-bars fa-fw"></i>
                                </a>
                            </li>
                            <li>
                            	<div class="text-primary" style="padding:15px 10px;"><b><i class="fa fa-calendar"></i> <?php echo date("l, j F, Y"); ?></b></div>
                            </li>
                            <!-- END Main Sidebar Toggle Button -->
                        </ul>
                        <!-- END Left Header Navigation -->

                        <!-- Search Form -->
                        <!--<form action="page_ready_search_results.html" method="post" class="navbar-form-custom" role="search">
                            <div class="form-group">
                                <input type="text" id="top-search" name="top-search" class="form-control" placeholder="Search..">
                            </div>
                        </form>-->
                        <!-- END Search Form -->

                        <!-- Right Header Navigation -->
                        <ul class="nav navbar-nav-custom pull-right">
                            <?php if($log_user_role == 'administrator'){ ?>
                            <li>
                            	<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="gi gi-home"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li class="dropdown-header text-center">Admin Panel</li>
                                    <li>
                                        <a href="<?php echo base_url('admin/accounts'); ?>">
                                            <i class="gi gi-user fa-fw"></i>
                                            Manage Accounts
                                        </a>
                                        <a href="<?php echo base_url('admin/stores'); ?>">
                                            <i class="gi gi-shopping_cart fa-fw"></i>
                                            Manage Outlets
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url('admin/industry'); ?>">
                                            <i class="gi gi-building fa-fw"></i>
                                            Manage Industry
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url('admin/blogs/manage'); ?>">
                                            <i class="gi gi-blog fa-fw"></i>
                                            Manage Blogs
                                        </a>
                                    </li>
                            	</ul>
                            </li>
                            <?php } ?>
                            <!-- Alternative Sidebar Toggle Button -->
                            <li>
                                <!-- If you do not want the main sidebar to open when the alternative sidebar is closed, just remove the second parameter: App.sidebar('toggle-sidebar-alt'); -->
                                <!--<a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt', 'toggle-other');
                                        this.blur();">
                                    <i class="gi gi-conversation"></i>
                                    <span class="label label-primary label-indicator animation-floating">0</span>
                                </a>-->
                            </li>
                            <!-- END Alternative Sidebar Toggle Button -->

                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo base_url($log_user_pics_small); ?>" alt="avatar"> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li class="dropdown-header text-center">Account</li>
                                    <li>
                                        <a href="<?php echo base_url('profile'); ?>">
                                            <i class="fa fa-user fa-fw"></i>
                                            Profile
                                        </a>
                                        <a href="#modal-user-settings" data-toggle="modal">
                                            <i class="fa fa-cog fa-fw"></i>
                                            Settings
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url('settings/photo'); ?>">
                                            <i class="fa fa-film fa-fw"></i>
                                            Profile Picture
                                        </a>
                                        <a href="<?php echo base_url('settings/account'); ?>">
                                            <i class="fa fa-cog fa-fw"></i>
                                            Update Account
                                        </a>
                                        <a href="<?php echo base_url('settings/password'); ?>">
                                            <i class="fa fa-key fa-fw"></i>
                                            Change Password
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url('premium'); ?>"><i class="fa fa-magnet fa-fw"></i>
                                            Upgrade Premium
                                        </a>
                                        <a href="<?php echo base_url(); ?>"><i class="fa fa-question fa-fw"></i>
                                            FAQ
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url('auth/logout'); ?>"><i class="fa fa-lock fa-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END User Dropdown -->
                        </ul>
                        <!-- END Right Header Navigation -->
                    </header>
                    <!-- END Header -->