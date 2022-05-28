			<!-- Testimonials -->
            <section class="site-content site-section site-slide-content">
                <div class="container">
                    <!-- Testimonials Carousel -->
                    <div id="testimonials-carousel" class="carousel slide carousel-html" data-ride="carousel" data-interval="4000">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#testimonials-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#testimonials-carousel" data-slide-to="1"></li>
                            <li data-target="#testimonials-carousel" data-slide-to="2"></li>
                        </ol>
                        <!-- END Indicators -->
            
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner text-center" style="color:#e67c1f;">
                            <div class="active item">
                                <p>
                                    <img src="<?php echo base_url(); ?>landing/img/icon76.png" alt="Avatar" class="img-circle">
                                </p>
                                <blockquote class="no-symbol">
                                    <p>Amazing application, I can't believe this is coming from Nigeria</p>
                                    <footer class="label label-default"><strong>Nicole Myers</strong></footer>
                                </blockquote>
                            </div>
                            <div class="item">
                                <p>
                                    <img src="<?php echo base_url(); ?>landing/img/icon76.png" alt="Avatar" class="img-circle">
                                </p>
                                <blockquote class="no-symbol">
                                    <p>Exceptional Customer service, They are so out of this world.</p>
                                    <footer class="label label-default"><strong>Mark Cox</strong></footer>
                                </blockquote>
                            </div>
                            <div class="item">
                                <p>
                                    <img src="<?php echo base_url(); ?>landing/img/icon76.png" alt="Avatar" class="img-circle">
                                </p>
                                <blockquote class="no-symbol">
                                    <p>Business management never gets any better, I don't have to be in the office to know how many products are sold.</p>
                                    <footer class="label label-default"><strong>Netta Brown</strong></footer>
                                </blockquote>
                            </div>
                        </div>
                        <!-- END Wrapper for slides -->
                    </div>
                    <!-- END Testimonials Carousel -->
                </div>
            </section>
            <!-- END Testimonials -->

            <!-- Quick Stats -->
            <section class="site-content site-section site-slide-content">
                <div class="container">
                    <!-- Stats Row -->
                    <!-- CountTo (initialized in js/app.js), for more examples you can check out https://github.com/mhuggins/jquery-countTo -->
                    <div class="row" id="counters">
                        <div class="col-sm-4">
                            <div class="counter site-block">
                                <span data-toggle="countTo" data-to="10" data-after="+"></span>
                                <small>Days after launch</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="counter site-block">
                                <span data-toggle="countTo" data-to="20" data-after="+"></span>
                                <small>Happy Customers</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="counter site-block">
                                <span data-toggle="countTo" data-to="280" data-after="+"></span>
                                <small>New Accounts to go</small>
                            </div>
                        </div>
                    </div>
                    <!-- END Stats Row -->
                </div>
            </section>
            <!-- END Quick Stats -->
            
            <!-- Sign Up Action -->
            <section class="site-contentsignup site-section site-slide-content">
                <div class="container">
                    <h3 class="site-heading text-center" style="color:#fff;"><strong>Sign Up for a year</strong> and receive <strong>10% discount</strong>!</h3>
                    <div class="site-block text-center">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <label class="sr-only" for="register-email">Your Email</label>
                                <div class="input-group input-group-lg">
                                    <input type="email" id="register-email" name="register-email" class="form-control" placeholder="Your Email..">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-primary" onclick="subscribe();"><i class="fa fa-plus"></i> Sign Up</button>
                                    </div>
                                </div>
                                <div id="subscribe_reply"></div>
                                <br />
                            </div>
                        </div>
                        
                        <script type="text/javascript">
							function subscribe(){
								var hr = new XMLHttpRequest();
								var email = document.getElementById('register-email').value;
								var c_vars = "email="+email;
								hr.open("POST", "<?php echo base_url('welcome'); ?>", true);
								hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								hr.onreadystatechange = function() {
									if(hr.readyState == 4 && hr.status == 200) {
										var return_data = hr.responseText;
										document.getElementById("subscribe_reply").innerHTML = return_data;
								   }
								}
								hr.send(c_vars);
								document.getElementById("subscribe_reply").innerHTML = "<i class=\"icon-spin4 animate-spin loader\"></i>";
							}
						</script>
                    </div>
                </div>
            </section>
            <!-- END Sign Up Action -->

            <!-- Footer -->
            <footer class="site-footer site-section">
                <div class="container">
                    <!-- Footer Links -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <h4 class="footer-heading">About Us</h4>
                            <ul class="footer-nav list-inline">
                                <li><a href="<?php echo base_url(); ?>">Company</a></li>
                                <li><a href="<?php echo base_url('contact'); ?>">Contact</a></li>
                                <li><a href="<?php echo base_url('contact'); ?>">Support</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h4 class="footer-heading">Legal</h4>
                            <ul class="footer-nav list-inline">
                                <li><a href="javascript:void(0)">Licensing</a></li>
                                <li><a href="javascript:void(0)">Privacy Policy</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h4 class="footer-heading">Follow Us</h4>
                            <ul class="footer-nav footer-nav-social list-inline">
                                <li><a href="javascript:void(0)"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="fa fa-rss"></i></a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <h4 class="footer-heading"><span id="year-copy">2015</span> &copy; <a href="<?php echo base_url(); ?>"> Organized</a></h4>
                            <ul class="footer-nav list-inline">
                                <li>Made with <i class="fa fa-heart text-danger"></i> in Nigeria</li>
                            </ul>
                        </div>
                    </div>
                    <!-- END Footer Links -->
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

        <!-- Include jQuery library from Google's CDN but if something goes wrong get jQuery from local file -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-1.11.2.min.js"%3E%3C/script%3E'));</script>

        <!-- Bootstrap.js, jQuery plugins and Custom JS code -->
        <script src="<?php echo base_url(); ?>landing/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>landing/js/plugins.js"></script>
        <script src="<?php echo base_url(); ?>landing/js/app.js"></script>
        <script src="<?php echo base_url(); ?>landing/js/pages/contact.js"></script>
    </body>
</html>