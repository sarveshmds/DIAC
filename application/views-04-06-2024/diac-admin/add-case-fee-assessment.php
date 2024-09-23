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
                            <?php if (isset($assessment['code'])) : ?>
                                <input type="hidden" id="hidden_fee_cost_id" name="hidden_assessment_code" value="<?= customURIEncode($assessment['code']) ?>">
                            <?php endif; ?>

                            <input type="hidden" name="csrf_assessment_form_token" value="<?php echo generateToken('assessment_form'); ?>">

                            <div class="row">
                                <div class="col-xs-12">
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
                                    </div>
                                </div>

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
                                    <label class="control-label">Claims or Counter Claims assessed separately?</label>
                                    <?php $c_cc_asses_sep = (isset($assessment['c_cc_asses_sep'])) ? $assessment['c_cc_asses_sep'] : ''; ?>
                                    <select class="form-control" name="fc_c_cc_assessed_sep" id="fc_c_cc_assessed_sep">
                                        <option value="">Select</option>
                                        <option value="yes" <?= edit_selected($assessment, $c_cc_asses_sep, 'yes') ?>>Yes</option>
                                        <option value="no" <?= edit_selected($assessment, $c_cc_asses_sep, 'no') ?>>No</option>
                                    </select>
                                </div>

                                <div class="col-xs-12 prayers_fieldset" <?= (isset($assessment['c_cc_asses_sep']) && ($assessment['c_cc_asses_sep'] == 'no' || $assessment['c_cc_asses_sep'] == 'yes')) ? '' : 'style="display: none;"' ?>>
                                    <fieldset class="fieldset" style="
                                        border: solid 1px #da6365;
                                        margin-bottom: 15px;
                                        padding-top: 15px;
                                        background-color: #ffffff;
                                        border-radius: 4px;
                                        padding-bottom: 10px;
                                        box-shadow: 0px 0px 10px 0px lightgrey;
                                    ">
                                        <legend class="legend">Prayers</legend>
                                        <div class="fieldset-content-box">
                                            <div class="row">
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12 fc_sum_despute_no_col" <?= (isset($assessment['c_cc_asses_sep']) && ($assessment['c_cc_asses_sep'] == 'no' || $assessment['c_cc_asses_sep'] == 'yes')) ? '' : 'style="display: none;"' ?>>
                                                    <label class="control-label">
                                                        Sum in Dispute (Claim) (<?= $currency ?>):
                                                    </label>
                                                    <input type="hidden" name="fc_sum_despute" id="fc_sum_despute" />

                                                    <div id="claimant_claim_amount_det" class="claimant_claim_amount_det">
                                                        <!-- Claimant prayer list -->
                                                        <div class="claimant_claim_amount_list_col">
                                                        </div>
                                                        <!-- Show the total amount -->
                                                        <div class="claimant_sum_in_dispute"></div>

                                                        <!-- Claimant prayer form -->
                                                        <div class="claimant_claim_amount_form_col">
                                                            <div class="row">

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Type:</label>
                                                                    <select name="claimant_prayer_type" id="claimant_prayer_type" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="CLAIM">Claim</option>
                                                                        <option value="COUNTER_CLAIM">Counter Claim</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Prayers Name:</label>
                                                                    <input type="text" name="claimant_prayer_name" id="claimant_prayer_name" class="form-control" placeholder="Prayer Name" />
                                                                </div>

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Whether Applicable?:</label>
                                                                    <select name="claimant_prayer_applicable" id="claimant_prayer_applicable" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="1">Yes</option>
                                                                        <option value="2">No</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Is Quantified:</label>
                                                                    <select name="is_quantified" id="is_quantified" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="1">Yes</option>
                                                                        <option value="2">No</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Prayers Amount:</label>
                                                                    <input type="number" name="claimant_prayer_amount" id="claimant_prayer_amount" class="form-control" placeholder="Prayer Amount" disabled />
                                                                </div>


                                                                <div class="col-xs-12 text-center form-group">
                                                                    <button class="btn btn-success" type="button" id="btn_claimant_claim_save">
                                                                        <i class="fa fa-plus"></i> Add
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12  col-sm-12 col-xs-12 fc_sum_despute_yes_col" <?= (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'yes') ? '' : 'style="display: none;"' ?>>

                                                    <hr>

                                                    <label class="control-label">
                                                        Sum in Dispute (Counter Claim) (<?= $currency ?>):
                                                    </label>

                                                    <input type="hidden" name="fc_sum_despute_cc" id="fc_sum_despute_cc" />

                                                    <div id="respondent_claim_amount_det" class="respondent_claim_amount_det">
                                                        <!-- Respondent prayer list -->
                                                        <div class="respondent_claim_amount_list_col">
                                                        </div>
                                                        <!-- Show the total amount -->
                                                        <div class="respondent_sum_in_dispute"></div>

                                                        <!-- Respondent prayer form -->
                                                        <div>
                                                            <div class="row">

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Type:</label>
                                                                    <select name="respondent_prayer_type" id="respondent_prayer_type" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="CLAIM">Claim</option>
                                                                        <option value="COUNTER_CLAIM">Counter Claim</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Prayers Name:</label>
                                                                    <input type="text" name="respondent_prayer_name" id="respondent_prayer_name" class="form-control" placeholder="Prayer Name" />
                                                                </div>

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Whether Applicable?:</label>
                                                                    <select name="respondent_prayer_applicable" id="respondent_prayer_applicable" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="1">Yes</option>
                                                                        <option value="2">No</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Is Quantified:</label>
                                                                    <select name="res_is_quantified" id="res_is_quantified" class="form-control" disabled>
                                                                        <option value="">Select</option>
                                                                        <option value="1">Yes</option>
                                                                        <option value="2">No</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                                    <label class="control-label">Prayers Amount:</label>
                                                                    <input type="number" name="respondent_prayer_amount" id="respondent_prayer_amount" class="form-control" placeholder="Prayer Amount" disabled />
                                                                </div>


                                                                <div class="col-xs-12 text-center form-group">
                                                                    <button class="btn btn-success" type="button" id="btn_respondent_claim_save">
                                                                        <i class="fa fa-plus"></i> Add
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>


                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label for="" class="">Assessmemt Supporting Document (If any)</label>

                                    <input type="file" class="form-control filestyle" name="fc_assesment_supporting_doc" id="fc_assesment_supporting_doc" accept=".jpg, .pdf">

                                    <small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>

                                    <input type="hidden" name="hidden_fc_assesment_supporting_file_name" id="hidden_fc_assesment_supporting_file_name" value="<?= (isset($assessment['assessment_sheet_doc']) && !empty($assessment['assessment_sheet_doc'])) ? $assessment['assessment_sheet_doc'] : '' ?>">

                                    <?php if (isset($assessment['assessment_supporting_doc']) && !empty($assessment['assessment_supporting_doc'])) : ?>
                                        <div><a href="<?= base_url(FEE_FILE_UPLOADS_FOLDER . $assessment['assessment_supporting_doc']) ?>" target="_BLANK" class=""><strong><i class="fa fa-eye"></i> View File</strong></a></div>
                                    <?php endif; ?>
                                </div>

                                <?php if (isset($assessment['assessment_sheet_doc']) && $assessment['assessment_sheet_doc']) : ?>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Assessmemt Sheet:</label>
                                        <div>
                                            <?= (isset($assessment['assessment_sheet_doc']) && !empty($assessment['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $assessment['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-xs-12 form-group text-center">
                                    <button class="btn btn-success" id="btn_calculate_fees" type="button">
                                        <i class="fa fa-calculator"></i> Calculate Fees
                                    </button>
                                </div>

                            </div>

                            <div class="calculation_col" <?= (isset($case_data['type_of_arbitration']) && ($case_data['type_of_arbitration'] == 'DOMESTIC' || $case_data['type_of_arbitration'] == 'INTERNATIONAL') && !empty($assessment)) ? '' : 'style="display: none;"'; ?>>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <fieldset class=" fieldset">
                                            <legend class="legend">Calculated Fees</legend>
                                            <div class="fieldset-content-box">
                                                <div class="domestic_calculation_col" <?= (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') ? '' : 'style="display: none;"' ?>>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                                    <label class="control-label">Total Arbitrators Fees (Rs.):</label>
                                                                    <input type="number" data-help-block-id="help_fc_total_arb_fees" class="form-control amount_field" rows="5" id="fc_total_arb_fees" name="fc_total_arb_fees" autocomplete="off" maxlength="200" value="<?= edit_value($assessment, 'total_arb_fees') ?>" readonly>
                                                                    <p id="help_fc_total_arb_fees" class="amount_help_block"></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <?php  // Claimant Share 
                                                            ?>
                                                            <div class="box box-warning">
                                                                <div class="box-header with-border">
                                                                    <h4 class="box-title">
                                                                        Claimant Share:
                                                                    </h4>
                                                                </div>
                                                                <div class="box-body">
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
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <?php  // Respondent Share 
                                                            ?>
                                                            <div class="box box-warning">
                                                                <div class="box-header with-border">
                                                                    <h4 class="box-title">
                                                                        Respondent Share
                                                                    </h4>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="">
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
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="international_calculation_col" <?= (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') ? '' : 'style="display: none;"' ?>>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="col-md-12 col-xs-12">
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

                                                            <div class="col-md-12 col-xs-12">
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

                                                        <div class="col-md-12 col-sm-12 col-xs-12">
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
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
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
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <fieldset class="fieldset">
                                            <legend class="legend">Assessment Details</legend>
                                            <div class="fieldset-content-box">
                                                <div id="show_calculated_fees_div" style="display: none;" class="calculated_fees_content_div">
                                                    <div id="calculated_fees_content_div">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>

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

<input type="hidden" name="prayers_details" id="prayers_details" value="<?php echo (isset($prayers_details) && count($prayers_details) > 0) ? json_encode($prayers_details) : json_encode([]) ?>">
<input type="hidden" name="cc_prayers_details" id="cc_prayers_details" value="<?php echo (isset($cc_prayers_details) && count($cc_prayers_details) > 0) ? json_encode($cc_prayers_details) : json_encode([]) ?>">


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
    // Declare the variable to store the assessment sheet data
    let assessment_sheet_data = '';
    let fees_calculated = false;

    $(document).ready(function() {

        // check for wheather Claims or Counter Claims assessed separately
        // If selected then enable the options according to there selected option
        $("#fc_c_cc_assessed_sep").on("change", function() {
            var value = $(this).val();
            $(".fc_sum_despute_no_col").hide();
            $(".fc_sum_despute_yes_col").hide();
            $('.calculation_col').hide();
            $('.domestic_calculation_col').hide();
            $('.prayers_fieldset').hide()

            if (value == "yes") {
                $('.prayers_fieldset').show()
                $(".fc_sum_despute_yes_col").show();
                $(".fc_sum_despute_no_col").show();

            } else if (value == "no") {
                $('.prayers_fieldset').show()
                $(".fc_sum_despute_no_col").show();
            }
        });

        let claimant_sum_in_dispute = 0;
        let respondent_sum_in_dispute = 0;
        let claimant_prayers = '<?php echo (isset($prayers_details) && count($prayers_details) > 0) ? json_encode($prayers_details) : json_encode([]) ?>';
        let respondent_prayers = '<?php echo (isset($cc_prayers_details) && count($cc_prayers_details) > 0) ? json_encode($cc_prayers_details) : json_encode([]) ?>';


        if ($('#hidden_fee_cost_id').val() !== '') {
            if (claimant_prayers) {
                console.log(claimant_prayers)
                claimant_prayers = JSON.parse(claimant_prayers)
            }

            if (respondent_prayers) {
                console.log('RP', respondent_prayers)
                respondent_prayers = JSON.parse(respondent_prayers)
            }
        }

        console.log('CP', claimant_prayers)
        console.log('CCP', respondent_prayers)

        function calculate_claimant_total_amount(claimant_prayers) {
            claimant_sum_in_dispute = 0;
            if (claimant_prayers.length > 0) {
                claimant_prayers.forEach((cp) => {
                    // Add the amount
                    claimant_sum_in_dispute += parseInt(cp.amount);
                })
            }

            $('.claimant_sum_in_dispute').html('<strong>Total:</strong> ' + claimant_sum_in_dispute)
            $('#fc_sum_despute').val(claimant_sum_in_dispute)
        }

        function generate_claimant_prayers(claimant_prayers) {

            if (claimant_prayers.length > 0) {
                let cp_html = '<table class="table table-bordered w-100">';
                cp_html += `
                    <thead>
                        <tr>
                            <td width="10%">S.No.</td>
                            <td  width="15%">Type</td>
                            <td>Prayers Name</td>
                            <td width="15%">Whether Applicable?</td>
                            <td  width="15%">Is Quantified</td>
                            <td>Prayers Amount</td>
                            <td width="10%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                `;

                let quantified;
                let applicable;
                let prayer_amount;

                claimant_prayers.forEach((cp, index) => {
                    quantified = '';
                    applicable = '';
                    prayer_amount = 0;

                    // Set quantified based on applicable condition
                    if (cp.is_quantified == 1) {
                        quantified = `Yes`;
                    } else if (cp.is_quantified == 2) {
                        quantified = `No`;
                    } else {
                        quantified = 'N/A';
                    }

                    // Set quantified based on applicable condition
                    if (cp.applicable == 1) {
                        applicable = `Yes`;
                    } else if (cp.applicable == 2) {
                        applicable = `No`;
                    } else {
                        applicable = 'N/A';
                    }

                    // Set quantified based on applicable condition
                    if (cp.applicable == 1 && cp.is_quantified == 1) {
                        prayer_amount += cp.amount;
                    } else {
                        prayer_amount = 'N/A';
                    }

                    cp_html += `
                        <tr>
                            <td>${index+1}</td>
                            <td>${cp.type}</td>
                            <td>${cp.name}</td>
                            <td>${applicable}</td>
                            <td>${quantified}</td>
                            <td>${prayer_amount}</td>
                            <td><button type="button" class="btn btn-danger btn-sm btn_claimant_prayer_remove" data-id="${cp.id}"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `;
                })

                cp_html += `</tbody><table>`;

                $('.claimant_claim_amount_list_col').html(cp_html);
            } else {
                $('.claimant_claim_amount_list_col').html('');
            }

            calculate_claimant_total_amount(claimant_prayers)
        }


        function calculate_respondent_total_amount(respondent_prayers) {
            respondent_sum_in_dispute = 0;
            if (respondent_prayers.length > 0) {
                respondent_prayers.forEach((cp) => {
                    // Add the amount
                    respondent_sum_in_dispute += parseInt(cp.amount);
                })
            }

            $('.respondent_sum_in_dispute').html('<strong>Total:</strong> ' + respondent_sum_in_dispute)
            $('#fc_sum_despute_cc').val(respondent_sum_in_dispute)
        }


        function generate_respondent_prayers(respondent_prayers) {

            if (respondent_prayers.length > 0) {
                let cp_html = '<table class="table table-bordered w-100">';
                cp_html += `
                    <thead>
                        <tr>
                            <td width="10%">S.No.</td>
                            <td  width="15%">Type</td>
                            <td>Prayers Name</td>
                            <td width="15%">Whether Applicable?</td>
                            <td  width="15%">Is Quantified</td>
                            <td>Prayers Amount</td>
                            <td width="10%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                `;

                let quantified;
                let applicable;
                let prayer_amount;

                respondent_prayers.forEach((cp, index) => {
                    quantified = '';
                    applicable = '';
                    prayer_amount = 0;

                    // Set quantified based on applicable condition
                    if (cp.is_quantified == 1) {
                        quantified = `Yes`;
                    } else if (cp.is_quantified == 2) {
                        quantified = `No`;
                    } else {
                        quantified = 'N/A';
                    }

                    // Set quantified based on applicable condition
                    if (cp.applicable == 1) {
                        applicable = `Yes`;
                    } else if (cp.applicable == 2) {
                        applicable = `No`;
                    } else {
                        applicable = 'N/A';
                    }

                    // Set quantified based on applicable condition
                    if (cp.applicable == 1 && cp.is_quantified == 1) {
                        prayer_amount += cp.amount;
                    } else {
                        prayer_amount = 'N/A';
                    }

                    cp_html += `
                        <tr>
                            <td>${index+1}</td>
                            <td>${cp.type}</td>
                            <td>${cp.name}</td>
                            <td>${applicable}</td>
                            <td>${quantified}</td>
                            <td>${prayer_amount}</td>
                            <td><button type="button" class="btn btn-danger btn-sm btn_claimant_prayer_remove" data-id="${cp.id}"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `;
                })

                cp_html += `</tbody><table>`;

                $('.respondent_claim_amount_list_col').html(cp_html);
            } else {
                $('.respondent_claim_amount_list_col').html('');
            }

            calculate_respondent_total_amount(respondent_prayers)
        }

        /**  
         * On Change claimant prayers whether applicable (claimant_prayer_applicable)
         */
        $(document).on('change', '#claimant_prayer_applicable', function() {
            if ($(this).val() == 1) {
                $('#is_quantified').prop('disabled', false);
                // $('#claimant_prayer_amount').prop('disabled', false);
            } else {
                $('#is_quantified option').prop('selected', false);
                $('#is_quantified').prop('disabled', true);
                $('#claimant_prayer_amount').val('');
                $('#claimant_prayer_amount').prop('disabled', true);
            }
        })

        /**  
         * On Change claimant prayers is quantified (is_quantified)
         */
        $(document).on('change', '#is_quantified', function() {
            if ($(this).val() == 1) {
                $('#claimant_prayer_amount').prop('disabled', false);
            } else {
                $('#claimant_prayer_amount').val('');
                $('#claimant_prayer_amount').prop('disabled', true);
            }
        })

        // ======================================================
        // On saving the claimant claim amount
        $('#btn_claimant_claim_save').on('click', function() {
            let claimant_prayer_type = $('#claimant_prayer_type').val();
            let claimant_prayer_name = $('#claimant_prayer_name').val();
            let claimant_prayer_applicable = $('#claimant_prayer_applicable').val();

            let is_quantified = ($('#is_quantified').val()) ? $('#is_quantified').val() : '';
            let claimant_prayer_amount = ($('#claimant_prayer_amount').val()) ? parseInt($('#claimant_prayer_amount').val()) : 0;

            if (claimant_prayer_type && claimant_prayer_name && claimant_prayer_applicable) {

                if (claimant_prayer_applicable == 1 && is_quantified == '') {
                    toastr.error('Is quantified field is required.');
                    return;
                }

                if (claimant_prayer_applicable == 1 && is_quantified == 1 && claimant_prayer_amount == '') {
                    toastr.error('Amount field is required.');
                    return;
                }

                claimant_prayers.push({
                    id: Math.ceil(Math.random() * 100) + '_' + new Date().getTime(),
                    type: claimant_prayer_type,
                    name: claimant_prayer_name,
                    applicable: claimant_prayer_applicable,
                    is_quantified: is_quantified,
                    amount: claimant_prayer_amount,
                });

                generate_claimant_prayers(claimant_prayers)

                $('#claimant_prayer_type option').prop('selected', false);
                $('#claimant_prayer_name').val('');
                $('#claimant_prayer_applicable option').prop('selected', false);

                $('#is_quantified option').prop('selected', false);
                $('#is_quantified').prop('disabled', true);

                $('#claimant_prayer_amount').val('');
                $('#claimant_prayer_amount').prop('disabled', true);
            } else {
                toastr.error('Prayer type, name, and whether applicable both fields are required.')
            }
        })

        // Remove the claimant prayer
        $(document).on('click', '.btn_claimant_prayer_remove', function() {
            let id = $(this).data('id');
            if (id) {
                let new_claimant_prayers = claimant_prayers.filter((cp) => {
                    return cp.id != id;
                })

                claimant_prayers = new_claimant_prayers;
                generate_claimant_prayers(claimant_prayers)
                calculate_claimant_total_amount(claimant_prayers)
            }
        })


        // ===========================================================================
        /**  
         * On Change respondant prayers whether applicable (respondent_prayer_applicable)
         */
        // ===========================================================================
        $(document).on('change', '#respondent_prayer_applicable', function() {
            if ($(this).val() == 1) {
                $('#res_is_quantified').prop('disabled', false);
                // $('#respondent_prayer_amount').prop('disabled', false);
            } else {
                $('#res_is_quantified option').prop('selected', false);
                $('#res_is_quantified').prop('disabled', true);
                $('#respondent_prayer_amount').val('');
                $('#respondent_prayer_amount').prop('disabled', true);
            }
        })

        /**  
         * On Change respondant prayers is quantified (is_quantified)
         */
        $(document).on('change', '#res_is_quantified', function() {
            if ($(this).val()) {
                $('#respondent_prayer_amount').prop('disabled', false);
            } else {
                $('#respondent_prayer_amount').val('');
                $('#respondent_prayer_amount').prop('disabled', true);
            }
        })

        // =========================================================
        // On saving the respondent claim amount
        $('#btn_respondent_claim_save').on('click', function() {
            let respondent_prayer_type = $('#respondent_prayer_type').val();
            let respondent_prayer_name = $('#respondent_prayer_name').val();
            let respondent_prayer_applicable = $('#respondent_prayer_applicable').val();

            let res_is_quantified = ($('#res_is_quantified').val()) ? $('#res_is_quantified').val() : '';
            let respondent_prayer_amount = ($('#respondent_prayer_amount').val()) ? parseInt($('#respondent_prayer_amount').val()) : 0;

            if (respondent_prayer_type && respondent_prayer_name && respondent_prayer_applicable) {
                if (respondent_prayer_applicable == 1 && res_is_quantified == '') {
                    toastr.error('Is quantified field is required.');
                    return;
                }

                if (respondent_prayer_applicable == 1 && res_is_quantified == 1 && respondent_prayer_amount == '') {
                    toastr.error('Amount field is required.');
                    return;
                }

                respondent_prayers.push({
                    id: Math.ceil(Math.random() * 100) + '_' + new Date().getTime(),
                    type: respondent_prayer_type,
                    name: respondent_prayer_name,
                    applicable: respondent_prayer_applicable,
                    is_quantified: res_is_quantified,
                    amount: respondent_prayer_amount,
                })

                generate_respondent_prayers(respondent_prayers)

                $('#respondent_prayer_type option').prop('selected', false);
                $('#respondent_prayer_name').val('');
                $('#respondent_prayer_applicable option').prop('selected', false);

                $('#res_is_quantified option').prop('selected', false);
                $('#res_is_quantified').prop('disabled', true);

                $('#respondent_prayer_amount').val('');
                $('#respondent_prayer_amount').prop('disabled', true);

            } else {
                toastr.error('Prayer type, name, and whether applicable both fields are required.')
            }

            calculate_respondent_total_amount(respondent_prayers)
        })

        // Remove the claimant prayer
        $(document).on('click', '.btn_respondent_prayer_remove', function() {
            let id = $(this).data('id');
            if (id) {
                let new_respondent_prayers = respondent_prayers.filter((cp) => {
                    return cp.id != id;
                })

                // console.log(new_respondent_prayers)
                // console.log(respondent_prayers)
                respondent_prayers = new_respondent_prayers;
                generate_respondent_prayers(respondent_prayers)
                calculate_respondent_total_amount(respondent_prayers)
            }
        })

        // ==========================================================
        $(document).ready(function() {
            generate_claimant_prayers(claimant_prayers)
            generate_respondent_prayers(respondent_prayers)

            calculate_claimant_total_amount(claimant_prayers)
            calculate_respondent_total_amount(respondent_prayers)
        })

        // ============================================================
        // ======================================================
        // Calculate fees
        $('#btn_calculate_fees').on('click', function() {
            $('.calculation_col').hide();
            $('.domestic_calculation_col').hide();
            $('.international_calculation_col').hide();

            fees_calculated = true;

            var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();
            var fc_c_cc_assessed_sep = $('#fc_c_cc_assessed_sep').val();

            if (fc_c_cc_assessed_sep == 'yes') {

                var claim_amount = $('#fc_sum_despute').val();
                var counter_claim_amount = $('#fc_sum_despute_cc').val();

                var type_of_arbitration = $('#type_of_arbitration').val();
                var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();

                calculate_seperate_assessed_fees(claim_amount, counter_claim_amount, arbitral_tribunal_strength, type_of_arbitration)
            } else if (fc_c_cc_assessed_sep == 'no') {
                var claim_amount = $('#fc_sum_despute').val();
                var type_of_arbitration = $('#type_of_arbitration').val();
                var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();

                calculate_fees(claim_amount, arbitral_tribunal_strength, type_of_arbitration)
            } else {
                toastr.error('Fill all the required fields to calculate the fees')
            }
        })

        // Assessment form submission  =================================
        $("#assessment_form").on("submit", async function(e) {
            e.preventDefault();

            // Generate the pdf of content and return the blob
            // let pdf_file_blob = await convertToPDF('show_calculated_fees_div');

            // Disabled the button
            $("#fee_cost_btn_submit").attr("disabled", "disabled");

            // Url
            $("#fd_btn_submit").attr("disabled", "disabled");
            var formData = new FormData(document.getElementById("assessment_form"));

            // Append some new key values in form data
            // if (assessment_sheet_data) {
            //     formData.append('assessment_sheet_data', JSON.stringify(assessment_sheet_data));
            // }

            formData.append('claimant_prayers', JSON.stringify(claimant_prayers))

            // Check if the claims assessed seperately
            if ($('#fc_c_cc_assessed_sep').val() == 'yes') {
                formData.append('respondent_prayers', JSON.stringify(respondent_prayers))
            }

            // Append the assessment PDF
            // formData.append('assessment_pdf_file', pdf_file_blob, 'generated.pdf');

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
                                    window.location.href = base_url + 'case-fees-details/' + $('#fee_cost_case_no').val();
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
    })
</script>

<!-- EDIT_CASE_FEE_COST_DETAILS -->
<?php if (isset($assessment['id'])) : ?>
    <script>
        let assessment_sheet_temp_data = '<?= $assessment['assessment_sheet_data'] ?>';
        let assessment_sheet_decoded_data = JSON.parse(assessment_sheet_temp_data);
        let assessment_sheet_data_final = assessment_sheet_decoded_data[0];
        console.log(assessment_sheet_data_final)

        let calc_div_element_ID = 'calculated_fees_content_div';
        let show_calc_div_element_ID = 'show_calculated_fees_div';

        if (assessment_sheet_data_final.claim_assessed_seperately === 'yes') {
            let {
                type_of_arbitration,
                claim_amount,
                counter_claim_amount,
                data,
                claim_assessed_seperately
            } = assessment_sheet_data_final;
            generate_assessment_view(type_of_arbitration, data, claim_amount, counter_claim_amount, claim_assessed_seperately, calc_div_element_ID, show_calc_div_element_ID);
        }
        if (assessment_sheet_data_final.claim_assessed_seperately === 'no') {
            let {
                type_of_arbitration,
                claim_amount,
                data,
                claim_assessed_seperately
            } = assessment_sheet_data_final;
            generate_assessment_view(type_of_arbitration, data, claim_amount, 0, claim_assessed_seperately, calc_div_element_ID, show_calc_div_element_ID);
        }
    </script>
<?php endif; ?>