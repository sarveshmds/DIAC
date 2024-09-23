<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>

<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="bg-white rounded shadow">
                    <div class="row">
                        <div class="col-md-7 col-12">
                            <div class="py-5 px-5">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left bg-transparent" style="border: none !important;">
                                        <h3 class="font-weight-bolder text-danger text-gradient text-center">Login into your account</h3>
                                    </div>
                                    <div class="card-body" style="padding-top: 5px !important;">
                                        <form role="form" id="loginForm" name="login_form">
                                            <input type="hidden" name="csrf_login_form_token" id="csrf_login_form_token" value="<?php echo generateToken('login_form'); ?>">
                                            <label class="form-label">User Name</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="txtUsername" id="txtUsername" placeholder="User Name" aria-label="txtUsername" aria-describedby="email-addon">
                                            </div>
                                            <label class="form-label">Password</label>
                                            <div class="input-group mb-3">

                                                <input type="password" class="form-control" name="txtPassword" id="txtPassword" placeholder="Password" aria-label="Password" aria-describedby="password-addon">

                                                <button class="btn btn-outline-secondary mb-0" type="button" id="togglePassword" data-toggle="tooltip" data-placement="top" title="Show Password" data-title="show" style="padding: 10px 10px; border-left: none;border-color: #d2d6da; box-shadow: none !important; border-radius: 0;"><i class="fa fa-eye"></i></button>

                                            </div>
                                            <div class="mb-2 d-flex align-items-center justify-content-center">
                                                <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                                <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                            </div>
                                            <label class="form-label">Captcha</label>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" onclick="login(event)" class="btn btn-warning w-100 mb-0">Sign in</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center pt-3 px-lg-2 px-1">
                                        <p class="mb-2 text-sm mx-auto">
                                            Forget Password ?
                                            <a href="<?= base_url('efiling/forgot-password') ?>" class="text-info font-weight-bold">Click Here</a>
                                        </p>

                                        <p class="mb-1 text-sm mx-auto">
                                            Don't have an account?
                                            <a href="<?= base_url('efiling/register') ?>" class="text-info text-gradient font-weight-bold">Register Here</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-12">
                            <img src="<?= base_url('public/efiling_assets/frontend/img/1.jpg') ?>" alt="Image" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script>
    // function login(event) {
    //     event.preventDefault();
    //     md5KeyValue = "<?= $this->session->userdata('key'); ?>";

    //     var txtUsername = "";
    //     txtUsername = document.login_form.txtUsername.value;
    //     txtCaptcha = document.login_form.txt_captcha.value;
    //     var txtPassword = document.login_form.txtPassword.value;

    //     if (txtPassword != '') {
    //         var encSaltSHAPass = encryptSha2LoginPassword(md5KeyValue, txtUsername, txtPassword);
    //         document.login_form.txtPassword.value = encSaltSHAPass;
    //     }

    //     var formData = new FormData(document.getElementById('loginForm'))

    //     $.ajax({
    //         url: base_url + 'efiling_service/login',
    //         type: 'POST',
    //         data: formData,
    //         cache: false,
    //         processData: false,
    //         contentType: false,
    //         complete: function() {
    //             $('#txtPassword').val(txtPassword);
    //         },
    //         success: function(response) {
    //             var response = JSON.parse(response);
    //             if (response.status == true) {
    //                 toastr.success(response.msg);
    //                 window.location.href = base_url + response.index_page;
    //             } else if (response.status == 'validationerror') {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Validation Error',
    //                     html: response.msg
    //                 })
    //             } else if (response.status == false) {
    //                 toastr.error(response.msg);
    //             } else {
    //                 toastr.error('Something went wrong. Please try again.')
    //             }
    //         },
    //         error: function(error) {
    //             toastr.error('Server is not responding. Please try again.');
    //         }
    //     })
    // }

    // $('#togglePassword').on('click', function() {
    //     if ($('#txtPassword').val()) {
    //         var title = $(this).data('title');
    //         if (title == 'show') {
    //             $('#txtPassword').attr('type', 'text');
    //             $('#togglePassword').html('<i class="fa fa-eye-slash"></i>')
    //             $('#togglePassword').data('title', 'hide')
    //             $('#togglePassword').tooltip('hide')
    //                 .attr('data-original-title', 'Hide Password')
    //                 .tooltip('show');
    //         } else {
    //             $('#txtPassword').attr('type', 'password');
    //             $('#togglePassword').html('<i class="fa fa-eye"></i>')
    //             $('#togglePassword').data('title', 'show')
    //             $('#togglePassword').tooltip('hide')
    //                 .attr('data-original-title', 'Show Password')
    //                 .tooltip('show');
    //         }
    //     }
    // })
</script>