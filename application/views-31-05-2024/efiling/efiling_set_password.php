<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient">Change Password</h3>
                                <p class="mb-0">Set your new Password carefully !</p>
                            </div>
                            <div class="card-body">
                                <form role="form" id="setPasswordForm" name="setPasswordForm">

                                    <input id="txtUserCode" name="txtUserCode" type="hidden" value="<?php echo $this->session->userdata('user_data')['user_code']; ?>">
                                    <input id="txtUserName" name="txtUserName" type="hidden" value="<?php echo $this->session->userdata('user_data')['user_name']; ?>">

                                    <input type="hidden" name="csrf_setpassword_token" value="<?php echo generateToken('setPasswordForm'); ?>">
                                    <label>Enter Password</label>

                                    <div class="input-group mb-3">

                                        <input name="txtPassword" type="password" name="txtPassword" id="txtPassword" class="form-control" placeholder="Enter password" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />

                                        <button class="btn btn-outline-secondary mb-0" type="button" id="togglePassword" data-toggle="tooltip" data-placement="top" title="Show Password" data-title="show" style="padding: 10px 10px; border-left: none;border-color: #d2d6da; box-shadow: none !important; border-radius: 0;"><i class="fa fa-eye"></i></button>

                                    </div>

                                    <label>Confirm Password</label>

                                    <div class="input-group mb-3">

                                        <input name="txtConPassword" type="password" name="txtConPassword" id="txtConPassword" class="form-control" placeholder="Enter confrim password" autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />

                                        <button class="btn btn-outline-secondary mb-0" type="button" id="togglePassword" data-toggle="tooltip" data-placement="top" title="Show Password" data-title="show" style="padding: 10px 10px; border-left: none;border-color: #d2d6da; box-shadow: none !important; border-radius: 0;"><i class="fa fa-eye"></i></button>

                                    </div>

                                    <div class="text-center">
                                        <button type="submit" name="btnSignIn" id="btnSignIn" class="btn bg-gradient-info w-100 mt-4 mb-0">Set Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('<?= base_url('public/') ?>efiling_assets/frontend/img/curved-images/curved7.jpg')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script>
    $(document).ready(function() {
        $('#setPasswordForm').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                txtPassword: {
                    required: true
                },
                txtConPassword: {
                    required: true
                },
            },
            errorPlacement: function(error, element) {
                // Setted empty, so that no error text will be shown on page.
            },
            submitHandler: function(form, event) {
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

                urls = base_url + "set-new-password";
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
                            var response = JSON.parse(response);
                            if (response.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.msg
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.href = base_url + 'efiling/login';
                                    }
                                })

                            } else if (response.status === 'validationerror') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: response.msg
                                })
                            } else if (response.status == false) {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.msg
                                })
                            } else {
                                toastr.error('Something went wrong. Please try again.')
                            }
                        } catch (e) {
                            sweetAlert("Sorry", 'Unable to Save.Please Try Again !', "error");
                        }
                    },
                    error: function(err) {
                        toastr.error('Server is not responding. Please try again.');
                    }
                });
            }
        })

    })
</script>