<!DOCTYPE html style="width:100%; margin:auto;">
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<html lang="en">
<!--<![endif]-->
<head>
  	<meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <style>
    	.e_header {
			border-bottom:1px solid #CCC; padding:10px;
			overflow:auto;
		}
		.e_sub_header {
			padding:10px; margin-bottom:10px; border-bottom:1px solid #CCC;
		}
		.e_body {
			padding:10px;	
		}
		.e_footer {
			border-top:1px solid #CCC; padding:10px; margin-top:10px;
		}
		.email_btn {
			text-decoration:none;
			padding:10px 35px;
			background-color:#CCC;
			text-align:center;
			font-weight:bold;
			color:#000;
		}
    </style>
    
</head>
<body>
	<div style="width:95%; background-color:#eee; margin:auto; padding:10px;">
        <div style="background-color:#fff; border:1px solid #999; margin:auto;">
            <div class="e_header">
                <a href="<?php base_url(); ?>"><img alt="<?php echo app_name; ?> " src="<?php echo base_url(); ?>landing/img/logo50.png" /></a>
            </div>
            <div class="e_sub_header">
                <strong><?php echo $subhead; ?></strong>
            </div>
            <div class="e_body">
                <?php echo $message; ?>
            </div>
            <div class="e_footer">
                <strong><?php echo app_name; ?> Team</strong><br />
                <small>
                	<i><?php echo app_email; ?></i><br />
                    <i><?php echo app_site; ?></i><br />
                </small>
            </div>
        </div>
    </div>
</body>
</html>