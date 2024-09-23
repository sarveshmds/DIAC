<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
    <?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div class="">

                            <?= form_open(null, array('class' => 'wfst', 'id' => 'assessment_form')) ?>
                            <input type="hidden" name="hidden_case_no" id="fee_cost_case_no" value="<?php echo $case_no; ?>">

                            <input type="hidden" id="fee_cost_op_type" name="op_type" value="ADD_CASE_FEE_COST_DETAILS">

                            <!-- EDIT_CASE_FEE_COST_DETAILS -->
                            <?php if (isset($assessment['id'])) : ?>
                                <input type="hidden" id="hidden_fee_cost_id" name="hidden_id" value="<?= customURIEncode($assessment['id']) ?>">
                            <?php endif; ?>

                            <input type="hidden" name="csrf_assessment_form_token" value="<?php echo generateToken('assessment_form'); ?>">

                            <div class="row">

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">Type of arbitration:</label>
                                    <span>
                                        <?php echo $case_data['type_of_arbitration']; ?>
                                    </span>
                                    <input type="hidden" name="type_of_arbitration" id="type_of_arbitration" value="<?= $case_data['type_of_arbitration'] ?>">
                                </div>

                                <?php if ($case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                        <label class="control-label">Current Dollar Price:</label>
                                        <span>
                                            <?php echo current_dollar_price_text(); ?>
                                        </span>
                                        <input type="hidden" name="current_dollar_price" id="current_dollar_price" value="<?= current_dollar_price() ?>">
                                    </div>
                                    <?php if (!$assessment == false) : ?>
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                            <label class="control-label">Dollar Price in Rupyee (Used):</label>
                                            <span>
                                                <?= (isset($assessment['current_dollar_price'])) ? $assessment['current_dollar_price'] : ''; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                <?php endif; ?>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label class="form-label" for="arbitral_tribunal_strength">Arbitral Tribunal Strength</label>
                                    <?php $arbitral_tribunal_strength = (isset($assessment['arbitral_tribunal_strength'])) ? $assessment['arbitral_tribunal_strength'] : ''; ?>
                                    <select name="arbitral_tribunal_strength" id="arbitral_tribunal_strength" class="form-control">
                                        <option value="">Select</option>
                                        <?php foreach (ARBITRAL_TRIBUNAL_STRENGTH as $key => $ats) : ?>
                                            <option value="<?= $key ?>" <?= edit_selected(ARBITRAL_TRIBUNAL_STRENGTH, $arbitral_tribunal_strength, $key) ?>><?= $ats ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_assesment_approved_col">
                                    <label class="control-label">Whether Claims or Counter Claims assessed separately:</label>
                                    <?php $c_cc_asses_sep = (isset($assessment['c_cc_asses_sep'])) ? $assessment['c_cc_asses_sep'] : ''; ?>
                                    <select class="form-control" name="fc_c_cc_assessed_sep" id="fc_c_cc_assessed_sep">
                                        <option value="">Select</option>
                                        <option value="yes" <?= edit_selected($assessment, $c_cc_asses_sep, 'yes') ?>>Yes</option>
                                        <option value="no" <?= edit_selected($assessment, $c_cc_asses_sep, 'no') ?>>No</option>
                                    </select>
                                </div>


                                <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_sum_despute_no_col" <?= (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'no') ? '' : 'style="display: none;"' ?>>
                                    <label class="control-label">Sum in Dispute (<?= $currency ?>):</label>
                                    <input type="number" data-help-block-id="help_fc_sum_despute" class="form-control amount_field" id="fc_sum_despute" name="fc_sum_despute" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'sum_in_dispute') ?>">
                                    <p id="help_fc_sum_despute" class="amount_help_block"></p>
                                </div>

                                <div class="form-group col-md-4  col-sm-6 col-xs-12 fc_sum_despute_yes_col" <?= (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'yes') ? '' : 'style="display: none;"' ?>>
                                    <label class="control-label">Sum in Dispute (Claims) (<?= $currency ?>):</label>
                                    <input type="number" data-help-block-id="help_fc_sum_despute_claim" class="form-control amount_field" id="fc_sum_despute_claim" name="fc_sum_despute_claim" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'sum_in_dispute_claim') ?>">
                                    <p id="help_fc_sum_despute_claim" class="amount_help_block"></p>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_sum_despute_yes_col" <?= (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'yes') ? '' : 'style="display: none;"' ?>>
                                    <label class="control-label">Sum in Dispute (Counter Claims) (<?= $currency ?>):</label>
                                    <input type="number" data-help-block-id="help_fc_sum_despute_cc" class="form-control amount_field" id="fc_sum_despute_cc" name="fc_sum_despute_cc" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'sum_in_dispute_cc') ?>">
                                    <p id="help_fc_sum_despute_cc" class="amount_help_block"></p>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">As per provisional assessment dated:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control custom-all-date" id="fc_pro_assesment" name="fc_pro_assesment" autocomplete="off" readonly="true" value="<?= (isset($assessment['asses_date'])) ? formatDatepickerDate(edit_value($assessment, 'asses_date')) : '' ?>" />
                                    </div>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_assesment_approved_col">
                                    <label class="control-label">Assesment Approved:</label>
                                    <?php $assessment_approved = (isset($assessment['assessment_approved'])) ? $assessment['assessment_approved'] : ''; ?>
                                    <select class="form-control" name="fc_assesment_approved" id="fc_assesment_approved">
                                        <option value="">Select</option>
                                        <option value="yes" <?= edit_selected($assessment, $assessment_approved, 'yes') ?>>Yes</option>
                                        <option value="no" <?= edit_selected($assessment, $assessment_approved, 'no') ?>>No</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label for="" class="">Assesment Sheet (If any)</label>
                                    <input type="file" class="form-control filestyle" name="fc_assesment_sheet" id="fc_assesment_sheet" accept=".jpg, .pdf">
                                    <small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
                                    <input type="hidden" name="hidden_fc_assesment_sheet_file_name" id="hidden_fc_assesment_sheet_file_name" value="<?= (isset($assessment['assessment_sheet_doc']) && !empty($assessment['assessment_sheet_doc'])) ? $assessment['assessment_sheet_doc'] : '' ?>">

                                    <?php if (isset($assessment['assessment_sheet_doc']) && !empty($assessment['assessment_sheet_doc'])) : ?>
                                        <div><a href="<?= base_url(FEE_FILE_UPLOADS_FOLDER . $assessment['assessment_sheet_doc']) ?>" target="_BLANK" class=""><strong><i class="fa fa-eye"></i> View File</strong></a></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-xs-12 form-group text-center">
                                    <button class="btn btn-success" id="btn_calculate_fees" type="button">
                                        <i class="fa fa-calculator"></i> Calculate
                                    </button>
                                </div>

                            </div>

                            <div class="calculation_col" <?= (isset($case_data['type_of_arbitration']) && ($case_data['type_of_arbitration'] == 'DOMESTIC' || $case_data['type_of_arbitration'] == 'INTERNATIONAL')) ? '' : 'style="display: none;"'; ?>>
                                <fieldset class=" fieldset">
                                    <legend class="legend">Calculated Fees</legend>
                                    <div class="fieldset-content-box">
                                        <div class="domestic_calculation_col" <?= (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') ? '' : 'style="display: none;"' ?>>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                            <label class="control-label">Total Arbitrators Fees (Rs.):</label>
                                                            <input type="number" data-help-block-id="help_fc_total_arb_fees" class="form-control amount_field" rows="5" id="fc_total_arb_fees" name="fc_total_arb_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'total_arb_fees') ?>" readonly>
                                                            <p id="help_fc_total_arb_fees" class="amount_help_block"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <?php  // Claimant Share 
                                                    ?>
                                                    <fieldset class="fieldset">
                                                        <legend class="inner-legend">Claimant Share</legend>
                                                        <div class="fieldset-content-box">
                                                            <div class="form-group col-sm-12 col-xs-12">
                                                                <label class="control-label">Arbitrators Fees (Rs.):</label>
                                                                <input type="number" data-help-block-id="help_fc_cs_arb_fees" class="form-control amount_field" rows="5" id="fc_cs_arb_fees" name="fc_cs_arb_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'cs_arb_fees') ?>" readonly>
                                                                <p id="help_fc_cs_arb_fees" class="amount_help_block"></p>
                                                            </div>

                                                            <div class="form-group col-sm-12 col-xs-12">
                                                                <label class="control-label">Administrative Expenses (Rs.):</label>
                                                                <input type="number" data-help-block-id="help_fc_cs_adm_fees" class="form-control amount_field" rows="5" id="fc_cs_adm_fees" name="fc_cs_adm_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'cs_adminis_fees') ?>" readonly>
                                                                <p id="help_fc_cs_adm_fees" class="amount_help_block"></p>
                                                            </div>

                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <?php  // Respondent Share 
                                                    ?>
                                                    <fieldset class="fieldset">
                                                        <legend class="inner-legend">Respondent Share</legend>
                                                        <div class="fieldset-content-box">
                                                            <div class="row">
                                                                <div class="form-group col-sm-12 col-xs-12">
                                                                    <label class="control-label">Arbitrators Fees (Rs.):</label>
                                                                    <input type="number" data-help-block-id="help_fc_rs_arb_fees" class="form-control amount_field" rows="5" id="fc_rs_arb_fees" name="fc_rs_arb_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'rs_arb_fees') ?>" readonly>
                                                                    <p id="help_fc_rs_arb_fees" class="amount_help_block"></p>
                                                                </div>

                                                                <div class="form-group col-sm-12 col-xs-12">
                                                                    <label class="control-label">Administrative Expenses (Rs.):</label>
                                                                    <input type="number" data-help-block-id="help_fc_rs_adm_fees" class="form-control amount_field" rows="5" id="fc_rs_adm_fees" name="fc_rs_adm_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'rs_adminis_fee') ?>" readonly>
                                                                    <p id="help_fc_rs_adm_fees" class="amount_help_block"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="international_calculation_col" <?= (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') ? '' : 'style="display: none;"' ?>>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <label for="">
                                                            Total Arbitrator Fees
                                                        </label>
                                                        <div class="input-group form-group">
                                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                            <input type="number" name="int_arb_total_fees_dollar" id="int_arb_total_fees_dollar" value="<?= edit_value($assessment, 'int_arb_total_fees_dollar') ?>" class="form-control" readonly />
                                                        </div>

                                                        <div class="input-group form-group">
                                                            <span class="input-group-addon">Rs.</span>
                                                            <input type="number" name="int_arb_total_fees_rupee" id="int_arb_total_fees_rupee" value="<?= edit_value($assessment, 'int_arb_total_fees_rupee') ?>" class="form-control" readonly />
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <label for="">
                                                            Total Administrative Charges
                                                        </label>
                                                        <div class="input-group form-group">
                                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                            <input type="number" name="int_total_adm_charges_dollar" id="int_total_adm_charges_dollar" value="<?= edit_value($assessment, 'int_total_adm_charges_dollar') ?>" class="form-control" readonly />
                                                        </div>

                                                        <div class="input-group form-group">
                                                            <span class="input-group-addon">Rs.</span>
                                                            <input type="number" name="int_total_adm_charges_rupee" id="int_total_adm_charges_rupee" value="<?= edit_value($assessment, 'int_total_adm_charges_rupee') ?>" class="form-control" readonly />
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <?php  // Claimant Share 
                                                    ?>
                                                    <fieldset class="fieldset">
                                                        <legend class="inner-legend">Claimant Share</legend>
                                                        <div class="fieldset-content-box">
                                                            <div class="form-group col-sm-12 col-xs-12">
                                                                <label class="control-label">Arbitrators Fees :</label>

                                                                <div class="input-group form-group">
                                                                    <span class="input-group-addon">$</span>
                                                                    <input type="number" name="int_arb_claim_share_fees_dollar" id="int_arb_claim_share_fees_dollar" value="<?= edit_value($assessment, 'int_arb_claim_share_fees_dollar') ?>" class="form-control" readonly />
                                                                </div>

                                                                <div class="input-group form-group">
                                                                    <span class="input-group-addon">Rs.</span>
                                                                    <input type="number" name="int_arb_claim_share_fees_rupee" id="int_arb_claim_share_fees_rupee" value="<?= edit_value($assessment, 'int_arb_claim_share_fees_rupee') ?>" class="form-control" readonly />
                                                                </div>

                                                            </div>

                                                            <div class="form-group col-sm-12 col-xs-12">
                                                                <label class="control-label">Administrative Expenses:</label>

                                                                <div class="input-group form-group">
                                                                    <span class="input-group-addon">$</span>
                                                                    <input type="number" name="int_claim_adm_charges_dollar" id="int_claim_adm_charges_dollar" value="<?= edit_value($assessment, 'int_claim_adm_charges_dollar') ?>" class="form-control" readonly />
                                                                </div>

                                                                <div class="input-group form-group">
                                                                    <span class="input-group-addon">Rs.</span>
                                                                    <input type="number" name="int_claim_adm_charges_rupee" id="int_claim_adm_charges_rupee" value="<?= edit_value($assessment, 'int_claim_adm_charges_rupee') ?>" class="form-control" readonly />
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <?php  // Respondent Share 
                                                    ?>
                                                    <fieldset class="fieldset">
                                                        <legend class="inner-legend">Respondent Share</legend>
                                                        <div class="fieldset-content-box">
                                                            <div class="row">
                                                                <div class="form-group col-sm-12 col-xs-12">
                                                                    <label class="control-label">Arbitrators Fees:</label>

                                                                    <div class="input-group form-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="number" name="int_arb_res_share_fees_dollar" id="int_arb_res_share_fees_dollar" value="<?= edit_value($assessment, 'int_arb_res_share_fees_dollar') ?>" class="form-control" readonly />
                                                                    </div>

                                                                    <div class="input-group form-group">
                                                                        <span class="input-group-addon">Rs.</span>
                                                                        <input type="number" name="int_arb_res_share_fees_rupee" id="int_arb_res_share_fees_rupee" value="<?= edit_value($assessment, 'int_arb_res_share_fees_rupee') ?>" class="form-control" readonly />
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-sm-12 col-xs-12">
                                                                    <label class="control-label">Administrative Expenses (Rs.):</label>

                                                                    <div class="input-group form-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="number" name="int_res_adm_charges_dollar" id="int_res_adm_charges_dollar" value="<?= edit_value($assessment, 'int_res_adm_charges_dollar') ?>" class="form-control" readonly />
                                                                    </div>

                                                                    <div class="input-group form-group">
                                                                        <span class="input-group-addon">Rs.</span>
                                                                        <input type="number" name="int_res_adm_charges_rupee" id="int_res_adm_charges_rupee" value="<?= edit_value($assessment, 'int_res_adm_charges_rupee') ?>" class="form-control" readonly />
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </fieldset>

                                <div class="row text-center">
                                    <div class="col-xs-12">
                                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                                        <button type="submit" class="btn btn-custom" id="fee_cost_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
                                    </div>
                                </div>
                            </div>

                            <?= form_close() ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script>
    function calculate_fees(claim_amount, arbitral_tribunal_strength, type_of_arbitration) {

        if (claim_amount) {
            $.ajax({
                url: base_url + 'calculate-fees/case-fee',
                type: 'POST',
                data: {
                    claim_amount: claim_amount,
                    arbitral_tribunal_strength: arbitral_tribunal_strength,
                    type_of_arbitration: type_of_arbitration
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    console.log(response)

                    if (response.status == true) {
                        var data = response.data;

                        if (type_of_arbitration == 'DOMESTIC') {
                            $('#fc_total_arb_fees').val(response.data.total_arbitrator_fees);

                            $('#fc_cs_arb_fees').val(response.data.each_party_share_in_arb_fees);
                            $('#fc_rs_arb_fees').val(response.data.each_party_share_in_arb_fees);

                            $('#fc_cs_adm_fees').val(response.data.administrative_charges);
                            $('#fc_rs_adm_fees').val(response.data.administrative_charges);

                            $('.calculation_col').show();
                            $('.domestic_calculation_col').show();
                        }

                        if (type_of_arbitration == 'INTERNATIONAL') {
                            $('#int_arb_total_fees_dollar').val(response.data.total_arbitrator_fees_dollar);
                            $('#int_arb_total_fees_rupee').val(response.data.total_arbitrator_fees_rupee);

                            $('#int_total_adm_charges_dollar').val(response.data.administrative_charges_dollar);
                            $('#int_total_adm_charges_rupee').val(response.data.administrative_charges_rupee);

                            $('#int_arb_claim_share_fees_dollar').val(response.data.each_party_share_in_arb_fees_dollar);
                            $('#int_arb_claim_share_fees_rupee').val(response.data.each_party_share_in_arb_fees_rupee);

                            $('#int_claim_adm_charges_dollar').val(response.data.each_party_administrative_charges_dollar);
                            $('#int_claim_adm_charges_rupee').val(response.data.each_party_administrative_charges_rupee);

                            $('#int_arb_res_share_fees_dollar').val(response.data.each_party_share_in_arb_fees_dollar);
                            $('#int_arb_res_share_fees_rupee').val(response.data.each_party_share_in_arb_fees_rupee);

                            $('#int_res_adm_charges_dollar').val(response.data.each_party_administrative_charges_dollar);
                            $('#int_res_adm_charges_rupee').val(response.data.each_party_administrative_charges_rupee);

                            $('.calculation_col').show();
                            $('.international_calculation_col').show();
                        }

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
        } else {
            $('#btn_submit').prop('disabled', true)
            $('.fees-wrapper-col').hide();
            toastr.error('Please enter claim amount')
        }
    }

    function calculate_seperate_assessed_fees(claim_amount, counter_claim_amount, arbitral_tribunal_strength, type_of_arbitration) {
        if (claim_amount) {
            $.ajax({
                url: base_url + 'calculate-fees/case-fee-seperate-assessed',
                type: 'POST',
                data: {
                    claim_amount: claim_amount,
                    counter_claim_amount: counter_claim_amount,
                    arbitral_tribunal_strength: arbitral_tribunal_strength,
                    type_of_arbitration: type_of_arbitration
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    console.log(response)
                    if (response.status == true) {
                        var data = response.data;

                        if (type_of_arbitration == 'DOMESTIC') {
                            $('#fc_total_arb_fees').val(response.data.total_arbitrator_fees);

                            $('#fc_cs_arb_fees').val(response.data.claimant_share_in_arb_fees);
                            $('#fc_rs_arb_fees').val(response.data.respondant_share_in_arb_fees);

                            $('#fc_cs_adm_fees').val(response.data.claimant_administrative_charges);
                            $('#fc_rs_adm_fees').val(response.data.respondant_administrative_charges);

                            $('.calculation_col').show();
                            $('.domestic_calculation_col').show();
                        }

                        if (type_of_arbitration == 'INTERNATIONAL') {
                            $('#int_arb_total_fees_dollar').val(response.data.total_arbitrator_fees_dollar);
                            $('#int_arb_total_fees_rupee').val(response.data.total_arbitrator_fees_rupee);

                            $('#int_total_adm_charges_dollar').val(response.data.int_total_adm_charges_dollar);
                            $('#int_total_adm_charges_rupee').val(response.data.int_total_adm_charges_rupee);

                            $('#int_arb_claim_share_fees_dollar').val(response.data.claimant_share_in_arb_fees_dollar);
                            $('#int_arb_claim_share_fees_rupee').val(response.data.claimant_share_in_arb_fees_rupee);

                            $('#int_claim_adm_charges_dollar').val(response.data.claimant_share_in_administrative_charges_dollar);
                            $('#int_claim_adm_charges_rupee').val(response.data.claimant_share_in_administrative_charges_rupee);

                            $('#int_arb_res_share_fees_dollar').val(response.data.respondant_share_in_arb_fees_dollar);
                            $('#int_arb_res_share_fees_rupee').val(response.data.respondant_share_in_arb_fees_rupee);

                            $('#int_res_adm_charges_dollar').val(response.data.respondant_share_in_administrative_charges_dollar);
                            $('#int_res_adm_charges_rupee').val(response.data.respondant_share_in_administrative_charges_rupee);

                            $('.calculation_col').show();
                            $('.international_calculation_col').show();
                        }

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
        } else {
            $('#btn_submit').prop('disabled', true)
            $('.fees-wrapper-col').hide();
            toastr.error('Please enter claim amount')
        }
    }

    // ======================================================
    // Calculate fees
    $('#btn_calculate_fees').on('click', function() {
        $('.calculation_col').hide();
        $('.domestic_calculation_col').hide();
        $('.international_calculation_col').hide();

        var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();
        var fc_c_cc_assessed_sep = $('#fc_c_cc_assessed_sep').val();

        if (fc_c_cc_assessed_sep == 'yes') {
            var claim_amount = $('#fc_sum_despute_claim').val();
            var counter_claim_amount = $('#fc_sum_despute_cc').val();
            var type_of_arbitration = $('#type_of_arbitration').val();
            var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();

            calculate_seperate_assessed_fees(claim_amount, counter_claim_amount, arbitral_tribunal_strength, type_of_arbitration)
        }
        if (fc_c_cc_assessed_sep == 'no') {
            var claim_amount = $('#fc_sum_despute').val();
            var type_of_arbitration = $('#type_of_arbitration').val();
            var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();

            calculate_fees(claim_amount, arbitral_tribunal_strength, type_of_arbitration)
        }
    })

    // $('#fc_sum_despute').on('keyup', function() {
    //     var claim_amount = $(this).val();

    //     if (claim_amount.length > 2) {
    //         calculate_fees(claim_amount)
    //     }
    // })

    // // Seperate assessment ====================================
    // $('#fc_sum_despute_claim').on('keyup', function() {
    //     var claim_amount = $('#fc_sum_despute_claim').val();
    //     var counter_claim_amount = $('#fc_sum_despute_cc').val();

    //     if (claim_amount && counter_claim_amount) {
    //         calculate_seperate_assessed_fees(claim_amount, counter_claim_amount)
    //     }
    // })

    // $('#fc_sum_despute_cc').on('keyup', function() {
    //     var claim_amount = $('#fc_sum_despute_claim').val();
    //     var counter_claim_amount = $('#fc_sum_despute_cc').val();

    //     if (claim_amount && counter_claim_amount) {
    //         calculate_seperate_assessed_fees(claim_amount, counter_claim_amount)
    //     }
    // })


    // check for wheather Claims or Counter Claims assessed separately
    // If selected then enable the options according to there selected option
    $("#fc_c_cc_assessed_sep").on("change", function() {
        var value = $(this).val();
        $(".fc_sum_despute_no_col").hide();
        $(".fc_sum_despute_yes_col").hide();
        $('.calculation_col').hide();
        $('.domestic_calculation_col').hide();

        if (value == "yes") {
            $(".fc_sum_despute_yes_col").show();
        } else if (value == "no") {
            $(".fc_sum_despute_no_col").show();
        }
    });



    $("#assessment_form").on("submit", function(e) {
        e.preventDefault();

        // Disabled the button
        $("#fee_cost_btn_submit").attr("disabled", "disabled");

        // Url
        $("#fd_btn_submit").attr("disabled", "disabled");
        var formData = new FormData(document.getElementById("assessment_form"));

        $.ajax({
            url: base_url + "service/add_case_assessment",
            type: "post",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            complete: function() {
                // Enable the submit button
                enable_submit_btn("fee_cost_btn_submit");
            },
            success: function(response) {
                try {
                    var obj = JSON.parse(response);
                    if (obj.status == true) {
                        swal({
                                title: "Success",
                                text: obj.msg,
                                type: "success",
                                html: true,
                            },
                            function() {
                                window.location.reload();
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
                            text: (obj.msg) ? obj.msg : '',
                            type: "error",
                            html: true,
                        });
                    }
                } catch (e) {
                    sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                }
            },
            error: function(err) {
                toastr.error("unable to save");
            },
        });
    });
</script>