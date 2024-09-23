<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/requests') ?>" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body">
                <form action="" id="frm3" class="frm3" method="post">
                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm3'); ?>">
                    <input type="hidden" name="hidden_id" value="<?= (isset($request['id']) ? $request['id'] : '') ?>">
                    <input type="hidden" name="op_type" id="op_type" value="<?= (isset($request['id']) ? 'EDIT' : 'ADD') ?>">
                    <div class="row">

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Request Title.</label>
                            <input type="text" name="request_title" id="request_title" class="form-control" value="<?= (isset($request['request_title']) ? $request['request_title'] : '') ?>">
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Case Number (If any)</label>
                            <input type="text" name="case_number" id="case_number" class="form-control" value="<?= (isset($request['case_number']) ? $request['case_number'] : '') ?>">
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="upload_document">Upload Document (If any)</label>
                            <input type="file" name="upload_document" id="upload_document" class="form-control" accept=".pdf" />
                            <?php if (isset($request['document']) && $request['document']) : ?>
                                <input type="hidden" name="hidden_upload_document" value="<?= $request['document'] ?>">
                            <?php endif; ?>
                            <small class="text-muted text-xs">
                                Only .pdf files are allowed. Max 25 MB
                            </small>
                        </div>

                        <div class="col-md-12 col-12 mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="5"><?= (isset($request['message']) ? $request['message'] : '') ?></textarea>
                        </div>

                        <div class="col-12 text-center mb-3">
                            <button type="submit" class="btn btn-custom">
                                <i class="fa fa-save"></i> <?= (isset($request['id']) ? 'Update Details' : 'Submit Details') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#frm3').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                request_title: {
                    required: true
                },
                message: {
                    required: true
                },
                messages: {
                    request_title: 'Please provide the request title.',
                    message: 'Please provide the message.',
                }

            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/request/update' : base_url + 'efiling/request/store ';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: new FormData(document.getElementById('frm3')),
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.msg
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = base_url + 'efiling/requests';
                                }
                            })

                        } else if (response.status == 'validation_error') {
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
    })
</script>