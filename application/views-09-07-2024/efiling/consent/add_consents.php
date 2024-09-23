<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="Javascript:void(0)" class="btn btn-default" id="btn_go_back"><i class="fa fa-arrow-left"></i> Go Back</a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body">
                <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>
                <form action="" id="frm2" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm2'); ?>">
                    <input type="hidden" name="hidden_id" value="<?= (isset($consent['id']) ? $consent['id'] : '') ?>">
                    <input type="hidden" name="op_type" id="op_type" value="<?= (isset($consent['id']) ? 'EDIT' : 'ADD') ?>">
                    <div class="row">

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Case No. <span class="text-danger">*</span> </label>
                            <select name="case_no" id="case_no" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($cases as $case) : ?>
                                    <option value="<?php echo $case['slug'] ?>" <?= (isset($consent['case_no']) && $consent['case_no'] == $case['slug']) ? 'selected' : '' ?>><?php echo $case['case_no_desc'] . ' (' . $case['case_title'] . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control" />
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Email ID <span class="text-danger">*</span></label>
                            <input type="text" name="email_id" id="email_id" class="form-control" />
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">P.S./Secretary <span class="text-danger">*</span></label>
                            <input type="text" name="secretary" id="secretary" class="form-control" />
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Prior Experience (Including experience with arbitration) <span class="text-danger">*</span></label>
                            <input type="text" name="prior_experience" id="prior_experience" class="form-control" />
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Number of ongoing Arbitrations <span class="text-danger">*</span></label>
                            <input type="text" name="ongoing_arbitrations" id="ongoing_arbitrations" class="form-control" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Circumstances disclosing any past or present relationship with or interest in any of the parties or in relation to the subject-matter in dispute, whether financial, business, professional or other kind, which is likely to give rise to justifiable doubts as to your independence or impartiality (List Out) <span class="text-danger">*</span></label>
                            <textarea name="circumstances_disclosing_matter" id="circumstances_disclosing_matter" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="form-label">Circumstances which are likely to affect your ability to devote sufficient time to the arbitration and in particular your ability to finish the entire arbitration within twelve month (List Out) <span class="text-danger">*</span></label>
                            <textarea name="circumstances_affect_ability" id="circumstances_affect_ability" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Upload Signature <span class="text-danger">*</span></label>
                            <input type="file" name="signature" id="signature" class="form-control" accept=".pdf" />
                            <small class="text-muted text-xs">
                                Only .pdf files are allowed. Max 25 MB
                            </small>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label for="" class="form-label">Remarks (If Any)</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4"><?= (isset($consent['remarks']) ? $consent['remarks'] : '') ?></textarea>
                        </div>

                        <div class="col-12 text-center mb-3">
                            <button type="submit" class="btn btn-custom">
                                <i class="fa fa-save"></i> <?= (isset($consent['id']) ? 'Update Details' : 'Submit Details') ?>
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
        $('#frm2').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                case_no: {
                    required: true
                },
                messages: {
                    case_no: 'Please select case no.',
                }

            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/consent/update' : base_url + 'efiling/consent/store ';
                $.ajax({
                    url: url,
                    data: new FormData(document.getElementById('frm2')),
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.msg
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = base_url + 'efiling/consents';
                                }
                            })

                        } else if (response.status == 'validation_error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: response.msg
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