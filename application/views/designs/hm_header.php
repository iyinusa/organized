<?php
	if($page_active=='welcome'){$home_act = 'active';} else {$home_act = '';}
	if($page_active=='features'){$feature_act = 'active';} else {$feature_act = '';}
	if($page_active=='contact'){$contact_act = 'active';} else {$contact_act = '';}
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php echo $title; ?></title>

        <meta name="description" content="Your business in your palm. Better approach to organizing your products and services with customers satisfaction">
        <meta name="author" content="organized">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>landing/img/favicon.png">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>landing/img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>landing/css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>landing/css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>landing/css/main.css">

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>landing/css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) & Respond.js (enables responsive CSS code on browsers that don't support it, eg IE8) -->
        <script src="<?php echo base_url(); ?>landing/js/vendor/modernizr-respond.min.js"></script>
    </head>
    <body>
        <!-- Page Container -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!-- 'boxed' class for a boxed layout -->
        <div id="page-container">
            <!-- Site Header -->
            <header>
                <div class="container">
                    <!-- Site Logo -->
                    <a href="<?php echo base_url(); ?>" class="site-logo">
                        <img alt="" src="<?php echo base_url('landing/img/logo50.png') ?>" />
                    </a>
                    <!-- Site Logo -->

                    <!-- Site Navigation -->
                    <nav>
                        <!-- Menu Toggle -->
                        <!-- Toggles menu on small screens -->
                        <a href="javascript:void(0)" class="btn btn-default site-menu-toggle visible-xs visible-sm">
                            <i class="fa fa-bars"></i>
                        </a>
                        <!-- END Menu Toggle -->

                        <!-- Main Menu -->
                        <ul class="site-nav">
                            <!-- Toggles menu on small screens -->
                            <li class="visible-xs visible-sm">
                                <a href="javascript:void(0)" class="site-menu-toggle text-center">
                                    <i class="fa fa-times"></i>
                                </a>
                            </li>
                            <!-- END Menu Toggle -->
                            <li class="<?php echo $home_act; ?>">
                                <a href="<?php echo base_url(); ?>">Home</a>
                            </li>
                            <li class="<?php echo $feature_act; ?>">
                                <a href="<?php echo base_url('features'); ?>">Features</a>
                            </li>
                            <li class="<?php echo $contact_act; ?>">
                                <a href="<?php echo base_url('contact'); ?>">Contact</a>
                            </li>
                            <?php if($this->session->userdata('logged_in') == FALSE){ ?>
                            <li>
                                <a href="<?php echo base_url('auth/'); ?>" class="btn btn-primary"><i class="fa fa-key"></i> Log In</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('auth/#register'); ?>" class="btn btn-success"><i class="fa fa-user"></i> Sign Up</a>
                            </li>
                            <?php } else { ?>
                            <li>
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-success"><i class="gi gi-dashboard"></i> Dashboard</a>
                            </li>
                            <?php } ?>
                        </ul>
                        <!-- END Main Menu -->
                    </nav>
                    <!-- END Site Navigation -->
                </div>
            </header>
            <!-- END Site Header -->