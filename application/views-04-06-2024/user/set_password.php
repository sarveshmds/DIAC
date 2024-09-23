<?php require_once(APPPATH . '/views/templates/auth-header.php') ?>
<style>
	#setPasswordForm .form-control {
		border-radius: 20px;
	}

	#setPasswordForm .form-control:focus {
		outline: 1px;
		border: 1px solid #d9d9d9 !important;
	}

	#setPasswordForm .has-error .help-block {
		color: #bc0b0b;
	}
</style>
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
								<a><img src="<?= base_url() . 'public/custom/photos/logo.png'; ?>" class="login-logo"></a>
							</div>
						</div>
						<!-- /.login-logo -->

						<div class="card login-card">
							<div class="card-body login-card-body">
								<div class="wrap-login100">
									<h5 class="login-card-title-text">Set Password</h5>

									<?php echo form_open(null, array('id' => 'setPasswordForm', 'name' => 'setPasswordForm', 'enctype' => "multipart/form-data", 'class' => 'loginForm login100-form')); ?>
									<input id="txtUserCode" name="txtUserCode" type="hidden" value="<?php echo $this->session->userdata('user_code'); ?>">
									<input id="txtUserName" name="txtUserName" type="hidden" value="<?php echo $this->session->userdata('user_name'); ?>">
									<input type="hidden" name="csrf_setpassword_token" value="<?php echo generateToken('setPasswordForm'); ?>">

									<div id="errorlog" style="display: none; color: red; font-size: 15px;"></div>
									<div class="form-group has-feedback">
										<input name="txtPassword" type="password" id="txtPassword" class="form-control" placeholder="Enter password" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />
										<span class="glyphicon glyphicon-lock form-control-feedback"></span>
									</div>
									<div class="form-group has-feedback">
										<input name="txtConPassword" type="password" id="txtConPassword" class="form-control" placeholder="Enter confrim password" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />
										<span class="glyphicon glyphicon-lock form-control-feedback"></span>
									</div>
									<div class="clearfix"></div>
									<p></p>
									<div class="form-group">
										<div class="row">
											<div class="col-12 text-center">
												<button type="submit" name="btnSignIn" id="btnSignIn" class="btn login-page-btn set-password-submit-btn"><i class="fa  fa-send fa-lg"> </i>&nbsp;Submit</button>
											</div>
											<div class="col-md-12">
												<a class="txt1 pull-right mt-10" href="<?php echo base_url('login'); ?>"><span class="fa fa-user-o"></span> Log In</a>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

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


<?php require_once(APPPATH . '/views/templates/auth-footer-scripts.php') ?>

<script>
	$('#setPasswordForm').bootstrapValidator({
		message: 'This value is not valid',
		submitButtons: 'button[type="submit"]',
		submitHandler: function(validator, form, submitButton) {
			txtUserName = $('#txtUserName').val();
			txtpassword = $('#txtPassword').val();
			mobpassword = $('#txtPassword').val();
			txtConPassword = $('#txtConPassword').val();
			txtUserCode = $('#txtUserCode').val();

			var encSaltSHAPass = encryptShaPassCode(txtUserName, txtpassword);
			var encSaltSHAConPass = encryptShaPassCode(txtUserName, txtConPassword);

			var encMobPassword = encryptShaPassCode(txtUserName, mobpassword);

			$('#txtPassword').val(encSaltSHAPass);
			$('#txtConPassword').val(encSaltSHAConPass);

			var formData = new FormData(document.getElementById("setPasswordForm"));
			formData.append('mobpassword', encMobPassword);

			urls = base_url + "user_service/set_password";
			$.ajax({
				url: urls,
				method: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				complete: function() {
					$('#btnSignIn').attr('disabled', false);
					$('#txtPassword').val('');
					$('#txtConPassword').val('');
				},
				success: function(response) {
					try {
						var obj = JSON.parse(response);
						if (obj.status == false) {
							swal({
								type: 'error',
								title: 'Error',
								text: obj.msg,
								html: true
							});
						} else if (obj.status === 'validationerror') {
							swal({
								type: 'error',
								title: 'Validation Error',
								text: obj.msg,
								html: true
							});
						} else {
							sweetAlert("USER", obj.msg, "success");
							document.getElementById("setPasswordForm").reset();
							window.location.href = base_url + "login";
						}
					} catch (e) {
						sweetAlert("Sorry", 'Unable to Save.Please Try Again !', "error");
					}
				},
				error: function(err) {
					toastr.error('Server is not responding. Please try again.');
				}
			});

		},
		fields: {
			txtPassword: {
				validators: {
					notEmpty: {
						message: 'The password is required and cannot be empty'
					},
					stringLength: {
						min: 8,
						max: 25,
						message: 'Password Should be between 8 to 25 characters'
					},
					callback: {
						message: 'Password should contain at least 1 upper case,1 lower case,1 number',
						callback: function(value, validator, $field) {
							if (value === '') {
								return true;
							}
							// Check the password strength
							if (value.length < 8) {
								return false;
							}
							// The password doesn't contain any uppercase character
							if (value === value.toLowerCase()) {
								return false;
							}
							// The password doesn't contain any uppercase character
							if (value === value.toUpperCase()) {
								return false;
							}
							// The password doesn't contain any digit
							if (value.search(/[0-9]/) < 0) {
								return false;
							}
							return true;
						}
					},
					identical: {
						field: 'txtConPassword',
						message: 'The password and its confirm must be the same'
					}
				}
			},
			txtConPassword: {
				validators: {
					notEmpty: {
						message: 'The confirm password is required and cannot be empty'
					},
					identical: {
						field: 'txtPassword',
						message: 'The password and its confirm must be the same'
					}
				}
			}
		}
	});
</script>
<!-- End Scripts -->

</body>

</html>