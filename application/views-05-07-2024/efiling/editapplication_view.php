<div class="card mb-4">
    <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h4><?= $page_title ?></h4>
        <a href="<?= base_url('efiling/application') ?>" class="btn bg-gradient-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Go Back
        </a>
    </div>
    <div class="card-body">
        <form action="" id="frm" method="post">
            <div class="row">

                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <label for="">Case No.</label>
                    <select name="case_no" id="case_no" class="form-control">
                        <option value="">Select</option>
                        <?php foreach ($application as $case) : ?>
                            <option value="<?php echo $case->slug ?>"><?php echo $case->case_no ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <label for="">Filing Type</label>
                    <select name="filing_type" id="filing_type" class="form-control">
                        <option value="">Select</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Ordinary">Ordinary</option>
                    </select>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <label for="">Application Type</label>
                    <select name="app_type" id="app_type" class="form-control">
                        <option value="">Select</option>
                        <option value="Normal Application">Normal Application</option>
                        <option value="Evidence">Evidence</option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <fieldset class="fieldset">
                        <legend class="legend">On Behalf Of (Parties)</legend>
                        <div class="fieldset-content-col">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label for="">On Behalf of</label>
                                    <select name="behalf" id="behalf" class="form-control">
                                        <option value="">Select</option>
                                        <option value="claimant">Claimant</option>
                                        <option value="respondent">Respondant</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3 claimant_col" style="display: none;">
                                    <label for="">Claimant Name</label>
                                    <select name="claimant" id="claimant" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12 mb-3 respondent_col" style="display: none;">
                                    <label for="">Respondant Name</label>
                                    <select name="respondent" id="respondant" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <label for="">Upload Document</label>
                    <input type="file" name="upload" id="upload" class="form-control" accept=".pdf" />
                    <small class="text-muted text-xs">
                        Only .pdf files are allowed. Max 25 MB
                    </small>
                </div>

                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <label for="">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control"></textarea>
                </div>

                <div class="col-12 text-center mb-3">
                    <button type="submit" class="btn bg-gradient-info">
                        <i class="fa fa-save"></i> Save & Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#behalf').on('change', function() {
        $('.claimant_col').hide();
        $('.respondent_col').hide();

        if ($(this).val() == 'claimant') {
            $('.claimant_col').show();
        }
        if ($(this).val() == 'respondent') {
            $('.respondent_col').show();
        }
    })
</script>
<script>
    $('#case_no').on('change', function() {
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
    // validation and
</script>