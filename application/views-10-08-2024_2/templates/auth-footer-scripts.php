<script src="<?php echo base_url(); ?>public/login_plugin/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/animsition/js/animsition.min.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/bootstrap/js/popper.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrapValidator.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/vendor/countdowntime/countdowntime.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>public/login_plugin/js/main.js"></script>
<!--Custom Plugin-->
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/md5_5034.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/profile_sha.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/sha512.js"></script>
<script>
    var base_url = '<?php echo base_url(); ?>';
    document.onkeydown = function(e) {
        if (e.keyCode == 123) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
            return false;
        }

    }

    $('body').bind('cut copy paste', function(e) {
        e.preventDefault();
    });

    $("body").on("contextmenu", function(e) {
        return false;
    });

    $(".field-wrapper .field-placeholder").on("click", function() {
        $(this).closest(".field-wrapper").find("input").focus();
    });

    $(".field-wrapper input").on("keyup", function() {
        var value = $.trim($(this).val());
        if (value) {
            $(this).closest(".field-wrapper").addClass("hasValue");
        } else {
            $(this).closest(".field-wrapper").removeClass("hasValue");
        }
    });

    // Reload captcha
    $('#btnReload').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: base_url + 'user/refresh_captcha',
            dataType: "text",
            type: "POST",
            cache: false,
            success: function(data) {
                $('#captcha_img').html('');
                $('#captcha_img').append(data);
            },
            error: function(data) {
                alert("Unable to refresh");
            }
        });
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>