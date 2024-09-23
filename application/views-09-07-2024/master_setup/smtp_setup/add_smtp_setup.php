<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">
                <form action="" id="add_form">
                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('add_form'); ?>">
                    <input type="hidden" name="hidden_provider_id" id="hidden_provider_id" value="<?= (isset($email_setup['provider_id'])) ? $email_setup['provider_id'] : '' ?>">
                    <input type="hidden" name="op_type" id="op_type" value="<?= (isset($email_setup['provider_id'])) ? 'EDIT' : 'ADD' ?>">

                    <div class="row">

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Provider Name <span class="text-danger">*</span></label>
                                <input type="text" name="provider_name" id="provider_name" class="form-control" value="<?= (isset($email_setup['provider_name'])) ? $email_setup['provider_name'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Host Name <span class="text-danger">*</span></label>
                                <input type="text" name="host_name" id="host_name" class="form-control" value="<?= (isset($email_setup['host_name'])) ? $email_setup['host_name'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Port No <span class="text-danger">*</span></label>
                                <input type="number" name="port_no" id="port_no" class="form-control" value="<?= (isset($email_setup['port_no'])) ? $email_setup['port_no'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Email ID <span class="text-danger">*</span></label>
                                <input type="email" name="email_id" id="email_id" class="form-control" value="<?= (isset($email_setup['email_id'])) ? $email_setup['email_id'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= (isset($email_setup['username'])) ? $email_setup['username'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" value="<?= (isset($email_setup['password'])) ? $email_setup['password'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">SMTP Auth <span class="text-danger">*</span></label>
                                <select name="smtp_auth" id="smtp_auth" class="form-control" required>
                                    <option value="TRUE" <?= (isset($email_setup['smtp_auth']) && $email_setup['smtp_auth'] == 'TRUE') ? 'selected' : '' ?>>TRUE</option>
                                    <option value="FALSE" <?= (isset($email_setup['smtp_auth']) && $email_setup['smtp_auth'] == 'FALSE') ? 'selected' : '' ?>>FALSE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">SMTP Secure <span class="text-danger">*</span></label>
                                <select name="smtp_secure" id="smtp_secure" class="form-control" required>
                                    <option value="ssl" <?= (isset($email_setup['smtp_secure']) && $email_setup['smtp_secure'] == 'ssl') ? 'selected' : '' ?>>SSL</option>
                                    <option value="tls" <?= (isset($email_setup['smtp_secure']) && $email_setup['smtp_secure'] == 'tls') ? 'selected' : '' ?>>TLS</option>
                                    <option value="" <?= (empty($email_setup['smtp_secure'])) ? 'selected' : '' ?>>None</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">CC Email ID <span class="text-danger">*</span></label>
                                <input type="email" name="cc_email_id" id="cc_email_id" class="form-control" value="<?= (isset($email_setup['cc_email_id'])) ? $email_setup['cc_email_id'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Select</option>
                                    <?php foreach (ACTIVE_STATUS as $key => $value) : ?>
                                        <option value="<?= $key ?>" <?= (isset($email_setup['record_status']) && $email_setup['record_status'] == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="text-center">
                        <button class="btn btn-custom" type="submit" id="btn_submit">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    // ================================================
    // IWN form
    $("#add_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#btn_submit").attr("disabled", "disabled");
            var formData = new FormData(document.getElementById("add_form"));
            var url = '';
            if ($('#op_type').val() == 'EDIT') {
                url = base_url + "smtp-setup/update";
            } else {
                url = base_url + "smtp-setup/store";
            }

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                complete: function() {
                    $("#btn_submit").removeAttr("disabled");
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == true) {
                        swal({
                                title: "Success",
                                text: obj.msg,
                                type: "success",
                                html: true,
                            },
                            function() {
                                window.location.href = obj.redirect_url;
                            }
                        );
                    } else if (obj.status === "validationerror") {
                        swal({
                            title: "Validation Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });

                    }

                },
                error: function(err) {
                    toastr.error("unable to save");
                },
            });
        },
    });
</script>