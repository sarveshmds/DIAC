<footer class="footer py-3 d-print-none bg-dark">
    <div class="container">
        <ul class="list-inline list-inline-dots mb-0 d-flex justify-content-center">
            <li class="list-inline-item text-white">
                Copyright &copy; <?= date('Y') ?>
                <a href="#" class="link-warning">Delhi International Arbitration Centre</a>.
                All rights reserved.
            </li>
        </ul>
    </div>
    </div>
    </div>
    </div>
</footer>
</div>
</div>
<!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
<!--   Core JS Files   -->
<script src="<?= base_url('public/') ?>efiling_assets/frontend/js/core/jquery.min.js"></script>
<script src="<?= base_url('public/') ?>efiling_assets/frontend/js/core/popper.min.js"></script>
<script src="<?= base_url('public/') ?>efiling_assets/frontend/js/core/bootstrap.min.js"></script>
<!-- <script src="<?= base_url('public/') ?>efiling_assets/frontend/js/plugins/perfect-scrollbar.min.js"></script> -->
<!-- <script src="<?= base_url('public/') ?>efiling_assets/frontend/js/plugins/smooth-scrollbar.min.js"></script> -->
<!-- <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script> -->
<!-- jquery and validation -->
<script src="<?= base_url('public/') ?>efiling_assets/plugins/jquery-validation/jquery.validate.min.js"></script>

<script src="<?= base_url('public/') ?>efiling_assets/backend/template/dist/js/tabler.min.js?1695847769" defer=""></script>

<script src="<?= base_url('public/') ?>efiling_assets/plugins/sweetalert2/sweetalert.min.js"></script>
<!-- Github buttons -->
<script script async defer src="https://buttons.github.io/buttons.js">
</script>
<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>
<link href="<?php echo base_url() ?>public/bower_components/toastr/toastr.min.css" rel="stylesheet">

<!-- Bootstrap Datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/md5_5034.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/profile_sha.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/sha512.js">
</script>

<!-- the main fileinput plugin script JS file -->
<script src="<?= base_url('public/') ?>efiling_assets/plugins/file-input/fileinput.min.js"></script>

<!-- following theme script is needed to use the Font Awesome 5.x theme (fa5). Uncomment if needed. -->
<script src="<?= base_url('public/') ?>efiling_assets/plugins/file-input/theme.min.js"></script>


<script>
    // $(":file").filestyle({btnClass: "btn-danger"});

    var base_url = '<?php echo base_url(); ?>';

    $(document)
        .ajaxStart(function() {
            $(".site-loader").show();
        })
        .ajaxStop(function() {
            $(".site-loader").hide();
        });

    $(document).on('click', '.job_title', function() {

        $("#ef_empanellment_no_col").hide(); // unchecked
        $("#ef_enrollment_no_col").hide(); // unchecked

        if ($("#job_title_arbitrator").is(':checked')) {
            $("#ef_empanellment_no_col").show(); // checked
        }
        if ($("#job_title_advocate_arbitrator").is(':checked')) {
            $("#ef_enrollment_no_col").show(); // checked
            $("#ef_empanellment_no_col").show(); // checked
        }

        if ($("#job_title_advocate").is(':checked')) {

            $("#ef_enrollment_no_col").show(); // checked
        }
    })

    function reloadCaptcha(element_id = 'captcha_img') {
        $.ajax({
            url: base_url + 'refresh_captcha',
            dataType: "text",
            type: "POST",
            cache: false,
            success: function(data) {
                $('#' + element_id).html('');
                $('#' + element_id).append(data);
            },
            error: function(data) {
                alert("Unable to refresh");
            }
        });
    }

    // Reload captcha
    $('#btnReload').click(function(event) {
        event.preventDefault();
        reloadCaptcha();
    });

    $('#reg_frm').validate({
        errorClass: 'is-invalid',
        rules: {
            name: {
                required: true
            },
            job_title: {
                required: true
            },
            salutation: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            phone_number: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            pincode: {
                required: true,
                number: true,
                minlength: 6,
                maxlength: 6
            },
            captcha: {
                required: true,
            },

        },
        submitHandler: function(form, event) {
            event.preventDefault();
            $.ajax({
                url: base_url + 'efiling/register/store',
                data: $('#reg_frm').serialize(),
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
                                window.location.href = base_url + 'efiling/login';
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

    $(document).ready(function() {
        $('#check-address').click(function() {
            if ($('#check-address').is(':checked')) {
                $('#permanent_address').val(($('#address').val()));
                $('#permanent_address_pincode').val(($('#pincode').val()));
            } else {
                $('#permanent_address').val('');
                $('#permanent_address_pincode').val('');
            }
        })
    })

    // ===============================================================
    // Login Process
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
            url: base_url + 'efiling_service/login',
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: response.msg
                    })
                } else if (response.status == false) {
                    toastr.error(response.msg);
                } else {
                    toastr.error('Something went wrong. Please try again.')
                }

                reloadCaptcha();
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

    // -------------------------------------------------
    // internship 
    // -------------------------------------------------
    var file_options = {
        theme: "fa5",
        showPreview: true,
        browseClass: "btn btn-danger",
        previewFileType: "image",
        browseLabel: "Upload Photo",
        removeLabel: "Remove",
        maxFileSize: 512,
        allowedFileTypes: 'image',
        maxFileCount: 1,
        allowedPreviewExtensions: ['image/jpg', 'image/jpeg', 'image/png'],
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        filetype: ['image/jpg', 'image/jpeg', 'image/png'],
        showCancel: false,
        showUpload: false,
    }

    // Profile photo
    $("#profile_photo").fileinput(file_options).on('change', function(e, params) {
        var filename = $("#profile_photo").val().split('\\').pop();;
        let matches = filename.match(/\./g);
        if (matches != null && matches.length >= 2) {
            $('#profile_photo').fileinput('destroy');
            $('#profile_photo').val('');

            // Error message
            Swal.fire({
                icon: "error",
                title: "File Error",
                text: 'Invalid file: file should not contain names with more than one dot. File not accepted',
            });
            $('#profile_photo').fileinput(file_options)
            return false;
        }
    });

    // Signature
    $("#signature").fileinput(file_options).on('change', function(e, params) {
        var filename = $("#signature").val().split('\\').pop();;
        let matches = filename.match(/\./g);
        if (matches != null && matches.length >= 2) {
            $('#signature').fileinput('destroy');
            $('#signature').val('');

            // Error message
            Swal.fire({
                icon: "error",
                title: "File Error",
                text: 'Invalid file: file should not contain names with more than one dot. File not accepted',
            });
            $('#signature').fileinput(file_options)
            return false;
        }
    });

    // Assuming dateOfBirth is the date of birth in the format "YYYY-MM-DD"
    function getAge(dateOfBirth) {
        var today = new Date();
        var birthDate = new Date(dateOfBirth);
        var age = today.getFullYear() - birthDate.getFullYear();
        return age;
    }
</script>
</body>

</html>