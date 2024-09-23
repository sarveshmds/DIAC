<?php require_once(APPPATH . '/views/templates/auth-header.php') ?>
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
							</h1>
							<h1 class="login-left-title-2">DIAC Case Management</h1>
							<p class="login-left-text">
								Manage the <q>Delhi International Arbitration Center</q> case management system.
							</p>

							<div class="login-left-btn-col">
								<a href="http://dhcdiac.nic.in/" class="btn login-page-btn login-left-btn" target="_BLANK"><span class="fa fa-globe"></span> View Website</a>
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
						<div class="login-logo">
							<div class="login-logo-img-col">
								<a><img src="<?= base_url() . 'public/custom/photos/logo.png'; ?>" class="login-logo"></a>
							</div>
						</div>
						<!-- /.login-logo -->

						<div class="card login-card">
							<div class="card-body login-card-body">
								<div class="wrap-login100">
									<h5 class="login-card-title-text">Login</h5>
									<?php echo form_open(null, array('class' => 'login100-form validate-form loginForm', 'name' => 'login_form', 'enctype' => "multipart/form-data", 'id' => 'loginForm')); ?>

									<div>

										<?php if ($this->session->flashdata('info')) : ?>
											<div class="txt1" style="color: #007bff;font-size: 14px;" align="center"><?php echo $this->session->flashdata('info'); ?></div>
										<?php endif; ?>
										<?php if ($this->session->flashdata('error')) : ?>
											<div class="txt1" style="color: red;font-size: 12px;" align="center"><?php echo $this->session->flashdata('error'); ?></div>
										<?php endif; ?>

										<input type="hidden" name="csrf_login_form_token" id="csrf_login_form_token" value="<?php echo generateToken('login_form'); ?>">

										<div class="wrap-input100 validate-input" data-validate="Enter User Id">
											<input class="input100" type="text" name="txtUsername" id="txtUsername" placeholder="User Id" autocomplete="off">
											<span class="focus-input100" data-placeholder="&#xf207;"></span>
										</div>

										<div class="wrap-input100 validate-input" data-validate="Enter password">
											<input class="input100" type="password" name="txtPassword" id="txtPassword" placeholder="Password" autocomplete="off" style="padding-right: 30px;">
											<span class="focus-input100" data-placeholder="&#xf191;"></span>
											<span id="togglePassword" data-toggle="tooltip" data-placement="top" title="Show Password" data-title="show">
												<i class="fa fa-eye"></i>
											</span>
										</div>

										<div class="form-group has-feedback captcha-wrap">
											<span id="captcha_img"><?php echo $image; // this will show the captcha image
																	?></span>
											<button class="login-reload-captcha-btn" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh fa-lg"></i></button>
										</div>

										<div class="wrap-input100 has-feedback">
											<input type="text" class="input100" name="txt_captcha" id="txt_captcha" autocomplete="off" placeholder="Captcha" oncopy="return false" onpaste="return false" oncut="return false" />
											<span class="focus-input100" data-placeholder="&#xf18f;"></span>
										</div>
										<div class="clearfix"></div>
										<div class="row">

											<div class="col-md-12">
												<div class="container-login100-form-btn">
													<button class="btn login-page-btn login-submit-btn" type="submit" onclick="login(event)"><span class="fa fa-paper-plane"></span> Log In</button>
												</div>
											</div>

											<div class="col-md-12">
												<div class="p-t-10">
													<a class="txt1 pull-right mt-10" href="<?php echo base_url('forgot-password'); ?>">Forgot Password?</a>
												</div>
											</div>
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
<?php require_once(APPPATH . '/views/templates/auth-footer-scripts.php') ?>

<!-- ========================================================================= -->
<script>
	function login(event) {
		event.preventDefault();
		md5KeyValue = "<?= $this->session->userdata('key'); ?>";

		var txtUsername = "";
		txtUsername = document.login_form.txtUsername.value;
		txtCaptcha = document.login_form.txt_captcha.value;
		var txtPassword = document.login_form.txtPassword.value;

		if (txtPassword != '') {
			var encSaltSHAPass = encryptSha2LoginPassword(md5KeyValue, txtUsername, txtPassword);
			document.login_form.txtPassword.value = encSaltSHAPass;
		}

		var formData = new FormData(document.getElementById('loginForm'))

		$.ajax({
			url: base_url + 'user_service/login',
			type: 'POST',
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			complete: function() {
				$('#txtPassword').val(txtPassword);
			},
			success: function(response) {
				var response = JSON.parse(response);
				if (response.status == true) {
					toastr.success(response.msg);
					window.location.href = base_url + response.index_page;
				} else if (response.status == 'validationerror') {
					swal({
						type: 'error',
						title: 'Validation Error',
						text: response.msg,
						html: true
					});
				} else if (response.status == false) {
					toastr.error(response.msg);
				} else {
					toastr.error('Something went wrong. Please try again.')
				}
			},
			error: function(error) {
				toastr.error('Server is not responding. Please try again.');
			}
		})
	}

	$('#togglePassword').on('click', function() {
		if ($('#txtPassword').val()) {
			var title = $(this).data('title');
			if (title == 'show') {
				$('#txtPassword').attr('type', 'text');
				$('#togglePassword').html('<i class="fa fa-eye-slash"></i>')
				$('#togglePassword').data('title', 'hide')
				$('#togglePassword').tooltip('hide')
					.attr('data-original-title', 'Hide Password')
					.tooltip('show');
			} else {
				$('#txtPassword').attr('type', 'password');
				$('#togglePassword').html('<i class="fa fa-eye"></i>')
				$('#togglePassword').data('title', 'show')
				$('#togglePassword').tooltip('hide')
					.attr('data-original-title', 'Show Password')
					.tooltip('show');
			}
		}
	})
</script>