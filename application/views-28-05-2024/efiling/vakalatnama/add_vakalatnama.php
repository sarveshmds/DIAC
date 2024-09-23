<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/vakalatnama') ?>" class="btn btn-secondary btn-sm">
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
                    <input type="hidden" name="hidden_id" value="<?= (isset($vakalatnama['id']) ? $vakalatnama['id'] : '') ?>">
                    <input type="hidden" name="op_type" id="op_type" value="<?= (isset($vakalatnama['id']) ? 'EDIT' : 'ADD') ?>">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-2">
                            <label class="form-label">Do you know your case number ?</label>
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-start">
                            <div class="form-check mb-3 me-3">
                                <input class="form-check-input already_case_reg" type="radio" name="already_case_reg" id="case_reg_yes" value="1" <?php echo (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == 1) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="case_reg_yes">Yes</label>
                            </div>
                            <div class="form-check mb-3 me-3">
                                <input class="form-check-input already_case_reg" type="radio" name="already_case_reg" id="case_reg_no" value="2" <?php echo (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == 2) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="case_reg_no">No</label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 case_no_dropdown" <?= (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == '1') ? '' : 'style="display: none;"'; ?>>
                            <label class="form-label">Find your case</label>
                            <select name="case_no" id="case_no_dropdown" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($cases as $case) : ?>
                                    <option value="<?php echo $case['slug'] ?>" <?= (isset($vakalatnama['case_no']) && $vakalatnama['case_no'] == $case['slug']) ? 'selected' : '' ?>><?php echo $case['case_no_desc'] . ' (' . $case['case_title'] . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-3 case_no_text" <?= (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == '2') ? '' : 'style="display: none;"'; ?>>
                            <label class="form-label">Case No.</label>
                            <!-- <input size="19"> -->
                            <input type="text" value="<?php echo (isset($vakalatnama['case_no_text']) ? $vakalatnama['case_no_text'] : '') ?>" name="case_no_text" id="case_no_text" class="form-control" placeholder="DIAC/____/__-__" data-slots="_" data-accept="\d" />
                            <small class="text-muted">
                                Type your case number here.
                            </small>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3 case_no_text" <?= (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == '2') ? '' : 'style="display: none;"'; ?>>
                            <label class="form-label">Case Title</label>
                            <input type="text" value="<?php echo (isset($vakalatnama['case_title_text']) ? $vakalatnama['case_title_text'] : '') ?>" name="case_title_text" id="case_title_text" class="form-control" placeholder="Enter Case Title" />
                            <small class="text-muted">
                                Type your case title here.
                            </small>
                        </div>

                        <div class="col-12 mb-3">
                            <fieldset class="fieldset">
                                <legend class="legend">On Behalf Of (Parties)</legend>
                                <div class="fieldset-content-col">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label">On Behalf of</label>
                                            <select name="behalf_of" id="behalf_of" class="form-control">
                                                <option value="">Select</option>
                                                <option value="CLAIMANT" <?php echo (isset($vakalatnama['behalf']) && $vakalatnama['behalf'] == 'CLAIMANT') ? 'selected' : '' ?>>Claimant</option>
                                                <option value="RESPONDENT" <?php echo (isset($vakalatnama['behalf']) && $vakalatnama['behalf'] == 'RESPONDENT') ? 'selected' : '' ?>>Respondant</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 mb-3 claimant_dropdown_col" <?= (isset($vakalatnama['behalf']) && $vakalatnama['case_already_registered'] == '1' && $vakalatnama['behalf'] == 'CLAIMANT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Claimant Name</label>
                                            <select name="claimant_id" id="claimant" class="form-control">
                                                <option value="">Select</option>
                                                <?php if (isset($vakalatnama['behalf'])) : ?>
                                                    <?php foreach ($claimant_respondents as $claimant) : ?>
                                                        <?php if ($claimant['type'] == 'claimant') : ?>
                                                            <option value="<?= $claimant['id'] ?>" <?= (isset($vakalatnama['claimant_respondent_id']) && $vakalatnama['claimant_respondent_id'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 mb-3 claimant_text_col" <?= (isset($vakalatnama['behalf']) && $vakalatnama['case_already_registered'] == '2' && $vakalatnama['behalf'] == 'CLAIMANT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Claimant Name</label>
                                            <input name="claimant_text_name" value="<?php echo (isset($vakalatnama['claimant_respondent_text_name']) && $vakalatnama['case_already_registered'] == 2 && $vakalatnama['behalf'] == 'CLAIMANT') ? $vakalatnama['claimant_respondent_text_name'] : '' ?>" id="clai_input" class="form-control"></input>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-12 mb-3 respondant_dropdown_col" <?= (isset($vakalatnama['behalf']) && $vakalatnama['case_already_registered'] == '1' && $vakalatnama['behalf'] == 'RESPONDENT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Respondant Name</label>
                                            <select name="respondant_id" id="respondant" class="form-control">
                                                <option value="">Select</option>
                                                <?php if (isset($vakalatnama['behalf'])) : ?>
                                                    <?php foreach ($claimant_respondents as $claimant) : ?>
                                                        <?php if ($claimant['type'] == 'respondant') : ?>
                                                            <option value="<?= $claimant['id'] ?>" <?= (isset($vakalatnama['claimant_respondent_id']) && $vakalatnama['claimant_respondent_id'] == $claimant['id']) ? 'selected' : '' ?>><?= $claimant['name'] ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12 mb-3 respondant_text_col" <?= (isset($vakalatnama['behalf']) && $vakalatnama['case_already_registered'] == '2' && $vakalatnama['behalf'] == 'RESPONDENT') ? '' : 'style="display: none;"'; ?>>
                                            <label class="form-label">Respondant Name</label>
                                            <input name="respondant_text_name" value="<?php echo (isset($vakalatnama['claimant_respondent_text_name']) && $vakalatnama['case_already_registered'] == 2 && $vakalatnama['behalf'] == 'RESPONDENT') ? $vakalatnama['claimant_respondent_text_name'] : '' ?>" id="clai_input" class="form-control"> </input>
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
                            <textarea name="remarks" id="remarks" class="form-control"><?php echo (isset($vakalatnama['remarks']) ? $vakalatnama['remarks'] : '') ?></textarea>
                        </div>

                        <div class="col-12 text-center mb-3">
                            <button type="submit" class="btn btn-custom">
                                <i class="fa fa-save"></i> <?= (isset($vakalatnama['id']) ? 'Update Details' : 'Submit Details') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $('.already_case_reg').on('click', function() {
        $('.case_no_dropdown').hide();
        $('.case_no_text').hide();

        // behlaf of ki value unset krdo, rest hide the cols
        $('#behalf_of option').prop('selected', false)

        if ($(this).val() == 1) {
            $('.case_no_dropdown').show();
        }
        if ($(this).val() == 2) {
            $('.case_no_text').show();
        }
    })

    $('#behalf_of').on('change', function() {

        $('.claimant_dropdown_col').hide();
        $('.claimant_text_col').hide();
        $('.respondant_dropdown_col').hide();
        $('.respondant_text_col').hide();

        var behalfOf = $(this).val();
        var caseReg = $('input[name="already_case_reg"]:checked').val();

        console.log($(caseReg).val())

        if (behalfOf == 'CLAIMANT' && caseReg == 1) {
            $('.claimant_dropdown_col').show();
        }
        if (behalfOf == 'CLAIMANT' && caseReg == 2) {
            $('.claimant_text_col').show();
        }
        if (behalfOf == 'RESPONDENT' && caseReg == 1) {
            $('.respondant_dropdown_col').show();
        }
        if (behalfOf == 'RESPONDENT' && caseReg == 2) {
            $('.respondant_text_col').show();
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

                }

            })
        }
    })


    $(document).ready(function() {
        $('#frm').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                case_no: {
                    required: true
                },

                already_case_reg: {
                    required: true
                },

                behalf_of: {
                    required: true
                },

                remarks: {
                    required: true
                },
                messages: {
                    case_no: 'Please select case no.',
                    behalf: 'Please select on behalf of.',
                    remarks: 'Please enter remarks.',
                    document: 'Please upload document.',
                }

            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/vakalatnama/update' : base_url + 'efiling/vakalatnama/store';
                $.ajax({
                    url: url,
                    data: new FormData(document.getElementById('frm')),
                    type: 'POST',
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
                                    window.location.href = base_url + 'efiling/vakalatnama';
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

    // Mask
    document.addEventListener('DOMContentLoaded', () => {
        for (const el of document.querySelectorAll("[placeholder][data-slots]")) {
            const pattern = el.getAttribute("placeholder"),
                slots = new Set(el.dataset.slots || "_"),
                prev = (j => Array.from(pattern, (c, i) => slots.has(c) ? j = i + 1 : j))(0),
                first = [...pattern].findIndex(c => slots.has(c)),
                accept = new RegExp(el.dataset.accept || "\\d", "g"),
                clean = input => {
                    input = input.match(accept) || [];
                    return Array.from(pattern, c =>
                        input[0] === c || slots.has(c) ? input.shift() || c : c
                    );
                },
                format = () => {
                    const [i, j] = [el.selectionStart, el.selectionEnd].map(i => {
                        i = clean(el.value.slice(0, i)).findIndex(c => slots.has(c));
                        return i < 0 ? prev[prev.length - 1] : back ? prev[i - 1] || first : i;
                    });
                    el.value = clean(el.value).join ``;
                    el.setSelectionRange(i, j);
                    back = false;
                };
            let back = false;
            el.addEventListener("keydown", (e) => back = e.key === "Backspace");
            el.addEventListener("input", format);
            el.addEventListener("focus", format);
            el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
        }
    });
</script>