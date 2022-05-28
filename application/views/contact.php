<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-envelope"></i> <strong>Contact Us</strong></h1>
        <h2 class="h3 text-center animation-slideUp">We will be happy to answer all your questions and get any feedback!</h2>
    </div>
</section>
<!-- END Intro -->

<!-- Google Map -->
<section class="site-content">
    <!-- Gmaps.js (initialized in js/pages/contact.js), for more examples you can check out http://hpneo.github.io/gmaps/examples.html -->
    <div id="gmap" style="height: 350px;"></div>
</section>
<!-- END Google Map -->

<!-- Support Links -->
<section class="site-content site-section">
    <div class="container">
        <div class="row row-items text-center">
            <div class="col-sm-3 animation-fadeIn">
                <a href="javascript:void(0)" class="circle themed-background">
                    <i class="gi gi-life_preserver"></i>
                </a>
                <h4>Open a <strong>ticket</strong></h4>
            </div>
            <div class="col-sm-3 animation-fadeIn">
                <a href="javascript:void(0)" class="circle themed-background">
                    <i class="gi gi-envelope"></i>
                </a>
                <h4><strong>Email</strong> Us</h4>
            </div>
            <div class="col-sm-3 animation-fadeIn">
                <a href="javascript:void(0)" class="circle themed-background">
                    <i class="fa fa-comments"></i>
                </a>
                <h4><strong>Chat</strong> Live</h4>
            </div>
            <div class="col-sm-3 animation-fadeIn">
                <a href="javascript:void(0)" class="circle themed-background">
                    <i class="fa fa-twitter"></i>
                </a>
                <h4><strong>Tweet</strong> Us</h4>
            </div>
        </div>
        <hr>
    </div>
</section>
<!-- END Support Links -->

<!-- Contact -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 site-block">
                <div class="site-block">
                    <h3 class="h2 site-heading"><strong>Organized</strong></h3>
                    <address>
                        Plot 2, House 8/10, Road B,<br/>
                        Ogunshe Estate<br>
                        Ikorodu<br>
                        Lagos, 23401<br><br>
                        <i class="fa fa-phone"></i> (234) 813-4665-041<br>
                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">info@organized.com.ng</a>
                    </address>
                </div>
                <div class="site-block">
                    <h3 class="h2 site-heading"><strong>About</strong> Us</h3>
                    <p class="remove-margin">
                        Organized is a software as a software as a service product (SaaS) – A tool that is being designed & developed to help both business owners & managers manage the day to day running of their business through the use of technology.<br/>Organized is a cross platform application thereby breaking the limitation of time and space through our cloud hosting technology.
                    </p>
                </div>
            </div>
            <div class="col-sm-6 col-md-8 site-block">
                <h3 class="h2 site-heading"><strong>Contact</strong> Form</h3>
                <div class="form-group">
                    <label for="contact-name">Name</label>
                    <input type="text" id="contact-name" name="contact-name" class="form-control input-lg" placeholder="Your name..">
                </div>
                <div class="form-group">
                    <label for="contact-email">Email</label>
                    <input type="text" id="contact-email" name="contact-email" class="form-control input-lg" placeholder="Your email..">
                </div>
                <div class="form-group">
                    <label for="contact-message">Message</label>
                    <textarea id="contact-message" name="contact-message" rows="10" class="form-control input-lg" placeholder="Let us know how we can assist.."></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary" onclick="contact_me();">Send Message</button>
                </div>
                <div id="contact_reply"></div>
                
                <script type="text/javascript">
					function contact_me(){
						var hr = new XMLHttpRequest();
						var name = document.getElementById('contact-name').value;
						var email = document.getElementById('contact-email').value;
						var msg = document.getElementById('contact-message').value;
						var c_vars = "name="+name+"&email="+email+"&msg="+msg;
						hr.open("POST", "<?php echo base_url('contact'); ?>", true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						hr.onreadystatechange = function() {
							if(hr.readyState == 4 && hr.status == 200) {
								var return_data = hr.responseText;
								document.getElementById("contact_reply").innerHTML = return_data;
						   }
						}
						hr.send(c_vars);
						document.getElementById("contact_reply").innerHTML = "<i class=\"icon-spin4 animate-spin loader\"></i>";
					}
				</script>
            </div>
        </div>
    </div>
</section>
<!-- END Contact -->