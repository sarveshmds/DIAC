<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/document-pleadings') ?>" class="btn btn-secondary btn-sm">
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
                <form action="" id="frm" class="frm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm'); ?>">
                    <input type="hidden" name="hidden_id" value="<?= (isset($document['id']) ? $document['id'] : '') ?>">
                    <input type="hidden" name="op_type" id="op_type" value="<?= (isset($document['id']) ? 'EDIT' : 'ADD') ?>">
                    <div class="row">

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Case No.</label>
                            <select name="case_no" id="case_no_dropdown" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($cases as $case) : ?>
                                    <option value="<?php echo $case['slug'] ?>" <?= (isset($document['case_no']) && $document['case_no'] == $case['slug']) ? 'selected' : ''; ?>><?php echo $case['case_no_desc'] . ' (' . $case['case_title'] . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Type of document</label>
                            <select name="type_of_document" id="type_of_document" class="form-control">
                                <option>Select</option>
                                <?php foreach ($document_types as $type) : ?>
                                    <option value="<?= $type['gen_code'] ?>" <?= (isset($document['type_of_document']) && $document['type_of_document'] == $type['gen_code']) ? 'selected' : ''; ?>><?= $type['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <fieldset class="fieldset">
                                <legend class="legend">On Behalf Of (Parties)</legend>
                                <div class="fieldset-content-col">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label">On Behalf of</label>
                                            <select name="behalf_of" id="behalf_of" class="form-control selection">

                                                <option>Select</option>
                                                <?php foreach ($behalf_of as $behalf) : ?>
                                                    <option value="<?= $behalf['gen_code'] ?>" <?= (isset($document['behalf_of']) && $document['behalf_of'] == $behalf['gen_code']) ? 'selected' : ''; ?>><?= $behalf['description'] ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 mb-3 claimant_dropdown" <?= (isset($document['behalf_of']) && $document['behalf_of'] == 'CLAIMANT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Claimant Name</label>
                                            <select name="claimant" id="claimant" class="form-control">
                                                <option value="">Select</option>
                                                <?php if (isset($document['behalf_of'])) : ?>
                                                    <?php foreach ($claimant_respondents as $claimant) : ?>
                                                        <?php if ($claimant['type'] == 'claimant') : ?>
                                                            <option value="<?= $claimant['id'] ?>" <?= (isset($document['name_on_behalf_of']) && $document['name_on_behalf_of'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 mb-3 respondant_dropdown" <?= (isset($document['behalf_of']) && $document['behalf_of'] == 'RESPONDENT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Respondant Name</label>
                                            <select name="respondant" id="respondant" class="form-control">
                                                <option value="">Select</option>
                                                <?php if (isset($document['behalf_of'])) : ?>
                                                    <?php foreach ($claimant_respondents as $claimant) : ?>
                                                        <?php if ($claimant['type'] == 'respondant') : ?>
                                                            <option value="<?= $claimant['id'] ?>" <?= (isset($document['name_on_behalf_of']) && $document['name_on_behalf_of'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Upload Document</label>
                            <input type="file" name="document" id="document" class="form-control" accept=".pdf" />
                            <small class="text-muted text-xs">
                                Only .pdf files are allowed. Max 25 MB
                            </small>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control"><?= (isset($document['remarks']) ? $document['remarks'] : '') ?></textarea>
                        </div>

                        <div class="col-12 text-center mb-3">
                            <button type="submit" class="btn btn-custom">
                                <i class="fa fa-save"></i> <?= (isset($document['id']) ? 'Update Details' : 'Submit Details') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $('.selection').on('change', function() {
        $('.claimant_dropdown').hide();
        $('.respondant_dropdown').hide();

        if ($(this).val() == 'CLAIMANT') {
            $('.claimant_dropdown').show();
        }
        if ($(this).val() == 'RESPONDENT') {
            $('.respondant_dropdown').show();
        }

    })


    $('#case_no_dropdown').on('change', function() {
        var case_no = $(this).val();
        if (case_no) {
            $.ajax({
                url: base_url + 'get-claimant-respondent-separately',
                data: {
                    case_no: $(this).val()
                },
                type: 'post',
                success: function(response) {
                    console.log(response)
                    var response = JSON.parse(response);
                    var claimant = response.claimants;
                    var respodent = response.respondents;

                    var claimantOption = '<option value="">Select</option>';
                    $.each(claimant, function(index, cl) {
                        claimantOption += '<option value="' + cl.id + '">' + cl.name + '</option>'
                    })
                    var respodentOption = "<option value=''>Select</option>";
                    $.each(respodent, function(index, rs) {
                        respodentOption += '<option value="' + rs.id + '">' + rs.name + '</option>'
                    })

                    $('#claimant').html(claimantOption);
                    $('#respondant').html(respodentOption);

                    // Check if the claimant or respondent is already set or not
                    if ($('#behalf_of').val() == 'CLAIMANT') {
                        $('.claimant_dropdown').show();
                    }
                    if ($('#behalf_of').val() == 'RESPONDENT') {
                        $('.respondant_dropdown').show();
                    }

                }

            })
        }
    })


    $(document).ready(function() {
        // $('#case_no_dropdown').trigger("change");

        $('#frm').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                case_no: {
                    required: true
                },

                type_of_document: {
                    required: true
                },

                behalf_of: {
                    required: true
                },
                claimant: {
                    required: true
                },
                respondant: {
                    required: true
                },
                messages: {
                    case_no: 'Please select case no.',
                    behalf: 'Please select on behalf of.',
                    document: 'Please upload document.',
                }

            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/document/update' : base_url + 'efiling/document/store ';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: new FormData(document.getElementById('frm')),
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
                                    window.location.href = base_url + 'efiling/document-pleadings';
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