<?php require_once(APPPATH.'/views/templates/auth-header.php') ?>

<div class="login-page">

	<div class="login-page-wrapper">
		
		<div class="login-grids-wrapper">
			
			<div class="row login-grids-row">

				<div class="col-lg-8 col-md-7 login-left-box">
					
					<div class="login-left-box-wrapper">
						<div class="login-left-icon-box">
							<i class="fa fa-book"></i>
						</div>
						<div class="login-left-content-box">
							<h1 class="login-left-title-1">Welcome to</h1>
							<h1 class="login-left-title-2">DIAC Case Management</h1>
							<p class="login-left-text">
								Manage the <q>Delhi International Arbitration Center</q> case management system.
							</p>

							<div class="login-left-btn-col">
								<a href="http://sgov.stlindia.com/website/diacdelhi/" class="btn login-page-btn login-left-btn" target="_BLANK"><span class="fa fa-globe"></span> View Website</a>
							</div>
						</div>
						<!-- Login Left Content Box -->

						<div class="login-left-footer">
							<p class="left-footer-cr-text">
								&copy; <?= date('Y') ?> copyrights reserved; powered & developed by NIC
							</p>
						</div>
						<!-- Login Left Footer End -->

					</div>
					<!-- Login Left Box Wrapper End -->

				</div>
				<!-- Login Left Box End -->

				<div class="col-lg-4 col-md-5 login-right-box">
					<div class="login-box">
						<div class="login-logo forget-password-logo">
							<div class="login-logo-img-col forget-password-img-logo-col">
								<a><img src="<?= base_url().'public/custom/photos/logo.png';?>" class="login-logo"></a>
							</div>
						</div>
						<!-- /.login-logo -->
						
						<div class="card login-card">
							<div class="card-body login-card-body">
								<div class="wrap-login100">
									<h5 class="login-card-title-text">Forgot Password</h5>

									<?php echo form_open(null, array('id'=>'ForgotPasswordForm', 'name'=>'ForgotPasswordForm' ,'enctype'=>"multipart/form-data", 'class' => 'login100-form validate-form loginForm')); ?>
										<div id="errorlog" style="display: none; color: red; font-size: 15px;margin-bottom: 12px;text-align: left;"></div>

										<div class="wrap-input100 validate-input" data-validate = "Enter User Id">
											<input class="input100" type="text" name="txtUsername" id="txtUsername" placeholder="User Id" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false">
											<span class="focus-input100" data-placeholder="&#xf207;"></span>
										</div>

										<div class="wrap-input100 validate-input" data-validate = "Enter User Id">
											<input class="input100" type="text" name="txtEmailId" id="txtEmailId" placeholder="Eg:xxx@gmail.com" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false">
											<span class="focus-input100" data-placeholder="&#xf15a;"></span>
										</div>

										<div class="form-group has-feedback captcha-wrap">
											<span id="captcha_img"><?php echo $image; ?></span> 
											<button class="login-reload-captcha-btn" type="button"  value="" name="btnReload" id="btnReload"><i class="fa fa-refresh fa-lg"></i></button>
										</div>
										
										<div class="wrap-input100 has-feedback">
											<input  type="text" class="input100" name="txt_captcha" id="txt_captcha" autocomplete="off" placeholder="Captcha" oncopy="return false" onpaste="return false" oncut="return false" />
											<span class="focus-input100" data-placeholder="&#xf18f;"></span>
										</div>
												

										<div class="clearfix"></div>
										<p></p>
										<div class="row">
											<div class="col-md-12 col-xs-12">
												<button type="submit" name="btnSend"  id="btnSend" class="btn login-page-btn forget-password-submit-btn" ><i class="fa  fa-send fa-lg"> </i>&nbsp;Send</button>
											</div>

											<div class="col-md-12">
												<a class="txt1 pull-right mt-10" href="<?php echo base_url('login');?>"><span class="fa fa-user-o"></span> Log In</a>
											</div>
										</div>
									<?php echo form_close(); ?>

								</div>
								<!-- Wrap Login100 End -->

								<div class="login-footer-links-col">
									<ul class="login-footer-ul">
										<li>
											<a href="">Privacy</a>
										</li>
										<li>
											<a href="">About</a>
										</li>
										<li>
											<a href="">Contact</a>
										</li>
									</ul>
								</div>
								<!-- Login Footer Links Col End -->

							</div>
							<!-- /.login-card-body -->

						</div>
						<!-- Login Card End -->

					</div>
					<!-- Login Box End -->

				</div>
				<!-- Login Right Box End -->

			</div>
			<!-- Login Grid Row End -->

		</div>
		<!-- Login Grid Wrapper End -->

	</div>
	<!-- Login Page Wrapper End -->

</div>
<!-- Login Page End -->

<?php require_once(APPPATH.'/views/templates/auth-footer-scripts.php') ?>

<script>
	$('#ForgotPasswordForm').bootstrapValidator({
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButtons){
			
			$('#errorlog').hide();
			$("#btnSend").html('<i class="fa fa-gear fa-spin"></i> Sending...');
			$("#btnSend").attr('disabled', true);
			var formData = new FormData(document.getElementById("ForgotPasswordForm"));
			
			urls =base_url+"user_service/forgot_password";
			
			$.ajax({
				url : urls,
				method : 'POST',
				data:formData,
				cache: false,
				contentType: false,
				processData: false,
				success : function(response){
					try {
						// Reload the captcha after each request
						reload_captcha();
						$('#txt_captcha').val('');

						// Get the response
						var obj = JSON.parse(response);
						if (obj.status == false) {
							$("#btnSend").html('<i class="fa  fa-send fa-lg"></i> &nbsp;Send');
							$("#btnSend").removeAttr('disabled');
							sweetAlert("USER",obj.msg, "error");

						}
						else if(obj.status === 'validationerror'){
							$("#btnSend").html('<i class="fa  fa-send fa-lg"></i> &nbsp;Send');
							$("#btnSend").removeAttr('disabled');
							$('#errorlog').html(obj.msg);
							$('#errorlog').show();
						}
						else{
							$("#btnSend").html('<i class="fa  fa-send fa-lg"></i> &nbsp;Send');
							$("#btnSend").removeAttr('disabled');
							sweetAlert("USER",obj.msg, "success");
							document.getElementById("ForgotPasswordForm").reset();
						}
					} catch (e) {
						sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
						$("#btnSend").html('<i class="fa  fa-send fa-lg"></i> &nbsp;Send');
						$("#btnSend").removeAttr('disabled');
					}
				},error: function(err){
					sweetAlert("Sorry",'Unable to Save. Something went wrong.', "error");
				}
			});
		},
		
	});
</script>
<!-- End Scripts -->
	</body>
</html>
