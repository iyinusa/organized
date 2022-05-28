<?php include(APPPATH.'libraries/inc.php'); ?>
<?php
	//get friends
	
?>
<section id="content" class="content-sidebar bg-white"> <!-- .sidebar -->
  <aside class="sidebar bg-lighter sidebar">
    <div class="text-center clearfix"> 
    	<img src="<?php echo base_url().$log_user_pics; ?>" width="320">
    </div>
    <div class="bg-light padder padder-v"> 
        <span class="h4"><?php echo $log_display_name; ?></span> 
        <small class="block m-t-mini">
			<?php echo $log_user_country; ?> - <?php echo $log_user_sex; ?><br />
            <i class="text-muted"><?php echo $log_user_bio; ?></i>
        </small>
    </div>
    <div class="list-group list-normal m-b-none">
    	<?php if($itc_view_nicename == $log_user_nicename){ ?> 
            <a href="<?php echo $itc_setting_page; ?>photo" class="list-group-item">
            	<i class="fa fa-camera"></i> Photo
            </a>
            <a href="<?php echo $itc_setting_page; ?>account" class="list-group-item">
            	<i class="fa fa-cogs"></i> Account
            </a> 
            <a href="<?php echo $itc_setting_page; ?>password" class="list-group-item">
            	<i class="fa fa-key"></i> Password
            </a> 
            <a href="<?php echo $itc_setting_page; ?>privacy" class="list-group-item">
            	<i class="fa fa-legal"></i> Privacy
            </a>
        <?php }; ?>
    </div>
  </aside>
  <!-- /.sidebar --> <!-- .sidebar -->
  <section class="main">
    <div class="page-title"><h4><?php echo $page_title; ?></h4></div>
    <div class="row padder">
    	<section class="panel" style="margin:15px;">
          <div class="panel-body">
            <?php echo $err_msg; ?>
			<?php echo form_open($itc_setting_page.'privacy', array('class'=>'form-horizontal', 'method'=>'post', 'data-validate'=>'parsley')) ?>
              Your privacy is as important to us as to you, kindly clearfully set your security and privacy<br /><br />
              <div class="form-group">
                <ul class="list-group" style="margin:0px 10px;">
                  <li class="list-group-item">
                    <div class="btn-group pull-right m-t-n-mini m-r-n-mini" data-toggle="buttons">
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle"> <span class="dropdown-label"><i class="fa fa-globe"></i></span> <span class="caret"></span> </button>
                        <ul class="dropdown-menu dropdown-select">
                          <li class="active"><a href="#">
                            <input type="radio" name="d-s-r" checked="">
                            <i class="fa fa-globe"></i> Everyone</a></li>
                          <li><a href="#">
                            <input type="radio" name="d-s-r">
                            <i class="fa fa-users"></i> Friends</a></li>
                          <li><a href="#">
                            <input type="radio" name="d-s-r">
                            <i class="fa fa-user"></i> Only me</a></li>
                        </ul>
                      </div>
                    </div>
                    <i class="fa fa-fw fa-gift"></i> Birthday
                  </li>
                </ul>
              </div>
              <div class="form-group">
                <div class="col-lg-12" style="text-align:right;">
                  <button type="submit" class="btn btn-info">Save changes</button>
                </div>
              </div>
            <?php echo form_close() ?>
          </div>
        </section>
    </div>
  </section>
  
  <!-- /.sidebar --> <!-- .sidebar -->
  <aside id="listgroupgeeks" class="sidebar bg text-small">
    <div class="padder padder-v">
      
    </div>
    <ul class="list-group list-normal m-b list">
      
    </ul>
  </aside>
  <!-- /.sidebar --> </section>