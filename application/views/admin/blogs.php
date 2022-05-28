<?php include(APPPATH.'libraries/inc.php'); ?>
        <!-- Page content -->
        <div id="page-content">
            <!-- eCommerce Dashboard Header -->
            <?php include(APPPATH.'views/logics/dashboard_menu.php'); ?>
            <!-- END eCommerce Dashboard Header -->
            
            <?php
				$dir_list = '';
				
				//query all blog
				if(!empty($allblogs)){
					if(!empty($allblogs)){
						foreach($allblogs as $blogs){
							//get blog image
							$blog_img = $blogs->img_id;
							if($blog_img==0){
								$blog_img='img/icon57.png';
							} else {
								$gti = $this->users->query_img_id($blog_img);
								foreach($gti as $gtimg){
									$blog_img = $gtimg->pics_square;	
								}
							}
							
							//get industry name
							$get_indus = $this->m_blogs->query_blog_cat($blogs->cat_id);
							if(!empty($get_indus)){
								foreach($get_indus as $indus){
									$industry = $indus->industry;
								}
							} else {$industry = '';}
							
							if($log_user_role == 'administrator'){
								$role_btn = '<a href="'.base_url('admin/blogs/manage?blog='.$blogs->id.'').'" data-toggle="tooltip" title="Edit Blog" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
								$del_btn = '<a href="'.base_url('admin/blogs/remove?blog='.$blogs->id.'').'" data-toggle="tooltip" title="Delete Blog" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>';
							} else {
								$role_btn = '';
								$del_btn = '';
							}
							
							$dir_list .= '
								<tr>
									<td>'.date('d M, Y', strtotime($blogs->reg_date)).'</td>
									<td class="hidden-xs text-center"><img alt="" src='.base_url($blog_img).' width="50px" class="img-circle" /></td>
									<td>'.ucwords($blogs->title).'</td>
									<td>'.ucwords($industry).'</td>
									<td class="text-center">'.$blogs->view.'</td>
									<td class="text-center">
										<div class="btn-group btn-group-xs">
											'.$role_btn.'
											<a href="'.base_url('blog/'.$blogs->slug.'').'" target="_blank" data-toggle="tooltip" title="View Blog" class="btn btn-success"><i class="fa fa-eye"></i></a>
											'.$del_btn.'
										</div>
									</td>
								</tr>
							';
						}
					}
				}
			?>
            
            <!-- All expenses Block -->
            <div class="block full">
                <!-- All expenses Title -->
                <div class="block-title">
                    <h2><i class="gi gi-blog"></i> <strong>Manage</strong> Blogs</h2>
                </div>
                <!-- END All expenses Title -->
                
                <?php if(!empty($rec_del)){ ?>
                	<?php echo form_open_multipart('admin/blogs/remove'); ?>
                    	<div class="col-lg-12 bg-info">
                        	<h3>Are you sure? - Record will be totally remove from the system</h3>
                            <input type="hidden" name="del_id" value="<?php echo $rec_del; ?>" />
                            <button type="submit" name="cancel" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Cancel</button>
                            <button type="submit" name="delete" class="btn btn-sm btn-primary"><i class="fa fa-trash"></i> Remove</button><br /><br />
                        </div>
                    <?php echo form_close(); ?>
                <?php } ?>
				<?php echo form_open_multipart('admin/blogs/manage'); ?>
                    <div class="col-lg-12">
                        <h3>Post/Update</h3><br />
                        <input type="hidden" name="blog_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="type">Select Industry</label>
                            <?php
								$cat_list = '';
								$get_indus = $this->users->query_industry();
								if(!empty($get_indus)){
									foreach($get_indus as $ind){
										if(!empty($e_cat_id)){
											if($e_cat_id == $ind->id){
												$cat_sel = 'selected="selected"';
											} else {$cat_sel = '';}
										} else {$cat_sel = '';}
										
										$cat_list .= '<option value="'.$ind->id.'" '.$cat_sel.'>'.$ind->industry.'</option>';
									}
								}
							?>
                            <div class="col-md-9">
                                 <select id="cat_id" name="cat_id" class="select-chosen" data-placeholder="Select Industry">
                                    <option></option>
                                    <option value="0">All</option>
                                    <?php echo $cat_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">Title</label>
                            <div class="col-md-9">
                                <input type="text" id="title" name="title" class="form-control" placeholder="Title" value="<?php if(!empty($e_title)){echo $e_title;} ?>" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="details">Details</label>
                            <div class="col-md-9">
                                <textarea id="details" name="details" class="ckeditor"><?php if(!empty($e_details)){echo $e_details;} ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="status">Status</label>
                            <div class="col-md-9">
                                 <?php
                                    if(!empty($e_status)){
                                        if($e_status == 'Not Published'){
                                            $s1 = 'selected="selected"';
                                            $s2 = '';
                                        } else if($e_status == 'Published') {
                                            $s1 = '';
                                            $s2 = 'selected="selected"';
                                        }
                                    } else {$s1 = ''; $s2 = '';}
                                 ?>
                                 <select id="status" name="status" class="select-chosen" data-placeholder="Select Status">
                                    <option></option>
                                    <option value="0" <?php echo $s1; ?>>Not Published</option>
                                    <option value="1" <?php echo $s2; ?>>Published</option>
                                </select><br /><br />
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="col-md-3 control-label" for="logo">Photo</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="logo" value="<?php if(!empty($e_img_id)){echo $e_img_id;} ?>"/>
                                    <input type="file" id="pics" name="pics" class="form-control">
                                </div>
                            </div>
                        <div class="form-group form-actions">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
                
                <?php if(!empty($err_msg)){echo $err_msg;} ?>
                
                <!-- All expenses Content -->
                <table id="ecom-products" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th width="60px">Date</th>
                            <th width="60px" class="hidden-xs text-center">Img</th>
                            <th>Title</th>
                            <th>Industry</th>
                            <th class="text-center">Views</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php echo $dir_list; ?>
                    </tbody>
                </table>
                <!-- END All Products Content -->
            </div>
            <!-- END All Products Block -->
        </div>
        <!-- END Page Content -->