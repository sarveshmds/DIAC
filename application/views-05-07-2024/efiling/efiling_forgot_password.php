<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent" style="border: none !important;">
                                <h3 class="font-weight-bolder text-info text-gradient">Forgot Password</h3>
                            </div>
                            <div class="card-body" style="padding-top: 5px !important;">
                                <form role="form" id="fp_form" name="fp_form">
                                    <input type="hidden" name="csrf_fp_form_token" id="csrf_fp_form_token" value="<?php echo generateToken('fp_form'); ?>">
                                    <label>User Name</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="txtUsername" id="txtUsername" placeholder="User Name" aria-label="txtUsername" aria-describedby="email-addon">
                                    </div>

                                    <div class="mb-2 d-flex align-items-center justify-content-center">
                                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                        <button class="login-reload-captcha-btn btn bg-gradient-secondary" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <label>Captcha</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-warning w-100 mb-0">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">

                                <p class="mb-1 text-sm mx-auto">
                                    Already know your password?
                                    <a href="<?= base_url('efiling/login') ?>" class="text-info text-gradient font-weight-bold">Click Here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('<?= base_url('public/') ?>efiling_assets/frontend/img/curved-images/curved9.jpg')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script>
    $('#fp_form').validate({
        errorClass: 'is-invalid',
        rules: {
            txtUsername: {
                required: true
            },
            captcha: {
                required: true,
            },

        },
        submitHandler: function(form, event) {
            event.preventDefault();
            $.ajax({
                url: base_url + 'efiling/forgot-password/verify',
                data: $('#fp_form').serialize(),
                type: 'POST',
                complete: function() {
                    reloadCaptcha();
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    if (response.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.msg
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = response.redirect_url;
                            }
                        })

                    } else if (response.status == 'validation_error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: response.msg,
                        })
                    } else if (response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Server Error'
                        })
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Server Error, please try again'
                    })
                }
            });
        }
    })
</script>