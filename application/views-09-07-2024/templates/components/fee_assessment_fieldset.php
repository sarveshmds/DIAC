<div class="box box-danger mt-10 collapsed-box">
    <div class="box-header with-border bg-danger">
        <h4 class="box-title flex-with-space-between">
            <div>
                Assessment - <?= $key + 1 ?>
                <?= (count($fee_cost_data) == $key + 1) ? '<span class="badge bg-green">Latest</span>' : '' ?>
            </div>
            <div>
                <small class="text-dark mr-10">(Created On: <?= formatReadableDateTime($fc['created_at']) ?>)</small>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool btn-box-expand white" data-widget="collapse"><i class="fa fa-arrow-down"></i>
                    </button>
                </div>
            </div>
        </h4>
    </div>
    <div class="box-body fees-assessment-fixed-body">

        <?php if ($this->session->userdata('role') == 'DEPUTY_COUNSEL') : ?>
            <?php if (isset($assessment_editable) && $assessment_editable == true) : ?>
                <div class="text-right mb-15">

                    <!-- DOMESTIC CASES PDF SHEET -->
                    <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
                        <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>
                            <!-- PDF Download Button -->
                            <a href="<?= base_url('pdf/case-fees-assessment-separately/' . $case_no . '?id=' . customURIEncode($fc['code'])) ?>" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        <?php else : ?>
                            <!-- PDF Download Button -->
                            <a href="<?= base_url('pdf/case-fees-assessment/' . $case_no . '?id=' . customURIEncode($fc['code'])) ?>" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- INTERNATIONAL CASES PDF SHEET -->
                    <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                        <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>
                            <!-- PDF Download Button -->
                            <a href="<?= base_url('pdf/case-fees-assessment-separately/international/' . $case_no . '?id=' . customURIEncode($fc['code'])) ?>" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        <?php else : ?>
                            <!-- PDF Download Button -->
                            <a href="<?= base_url('pdf/case-fees-assessment/international/' . $case_no . '?id=' . customURIEncode($fc['code'])) ?>" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ((count($fee_cost_data) == $key + 1)) : ?>

                        <a href="<?= base_url('case-fees-details/add-assessment/' . $case_no . '?id=' . customURIEncode($fc['code'])) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>

                        <button class="btn btn-danger btn-sm btn_delete_assessment" data-fee-id="<?= $fc['id'] ?>"><i class="fa fa-trash"></i> Delete</button>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($this->session->userdata('role') == 'COORDINATOR') : ?>
            <?php if ($fc['assessment_approved'] == 0) : ?>
                <div>
                    <strong>Assessment is pending for approval:</strong>
                    <button type="button" class="btn btn-custom btn-sm"><i class="fa fa-paper-plane"></i> Click to Approve/Reject</button>
                </div>
                <hr>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row flex-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">

                <label class="control-label">Arbitration Type:</label>
                <div>
                    <?= (isset($case_data['type_of_arbitration'])) ? ucfirst($case_data['type_of_arbitration']) : ''; ?>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">

                <label class="control-label">Arbitral Tribunal Strength:</label>
                <div>
                    <?= (isset($fc['arbitral_tribunal_strength'])) ? ucfirst($fc['arbitral_tribunal_strength']) : ''; ?>
                </div>
            </div>

            <?php if ($case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                <div class="form-group col-md-3 col-sm-6 col-xs-12">

                    <label class="control-label">Dollar Price in Rupyee (Used):</label>
                    <div>
                        <?= (isset($fc['current_dollar_price'])) ? ucfirst($fc['current_dollar_price']) : ''; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">

                <label class="control-label">Whether Claims or Counter Claims assessed separately:</label>
                <div>
                    <?= (isset($fc['c_cc_asses_sep'])) ? ucfirst($fc['c_cc_asses_sep']) : ''; ?>
                </div>
            </div>

            <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>

                <div class="form-group col-md-3  col-sm-6 col-xs-12">
                    <label class="control-label">Sum in Dispute (Claims):</label>
                    <div>
                        <?= (isset($fc['sum_in_dispute'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['sum_in_dispute']) : ''  ?>
                    </div>
                </div>

                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                    <label class="control-label">Sum in Dispute (Counter Claims):</label>
                    <div>
                        <?= (isset($fc['sum_in_dispute_cc'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['sum_in_dispute_cc']) : ''  ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                    <label class="control-label">Sum in Dispute:</label>
                    <div>
                        <?= (isset($fc['sum_in_dispute'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['sum_in_dispute']) : ''  ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">As per provisional assessment dated:</label>
                <div>
                    <?= (isset($fc['asses_date'])) ? formatReadableDate($fc['asses_date']) : ''  ?>
                </div>
            </div>

            <!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Assessment Approved:</label>
                <div>
                    <?php if (isset($fc['assessment_approved']) && $fc['assessment_approved'] == 1) : ?>
                        <span class="badge bg-green">Approved</span>
                    <?php elseif (isset($fc['assessment_approved']) && $fc['assessment_approved'] == 2) : ?>
                        <span class="badge bg-red">Rejected</span>
                    <?php else : ?>
                        <span class="badge bg-blue">Pending</span>
                    <?php endif; ?>
                </div>
            </div> -->

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Assessmemt Supporting Document:</label>
                <div>
                    <?= (isset($fc['assessment_supporting_doc']) && !empty($fc['assessment_supporting_doc'])) ? '<a href="' . base_url(VIEW_ASSESSMENT_SUPPORTING_DOC_UPLOADS_FOLDER . $fc['assessment_supporting_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                </div>
            </div>

            <div class="form-group col-xs-12">
                <label class="control-label">Remarks:</label>
                <div>
                    <?= (isset($fc['remarks']) && !empty($fc['remarks'])) ? htmlspecialchars_decode($fc['remarks']) : ''  ?>
                </div>
            </div>

            <!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Assessmemt Sheet:</label>
                <div>
                    <?= (isset($fc['assessment_sheet_doc']) && !empty($fc['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $fc['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                </div>
            </div> -->
        </div>

        <div class="row">
            <div class="col-xs-12">
                <fieldset class="fieldset">
                    <legend class="legend">
                        Prayers
                    </legend>

                    <div class="fieldset-content-box">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label for="">Sum in Dispute (Claim) (Rs.):
                                </label>
                                <?php if (isset($fee_prayers['claim_prayers']) && count($fee_prayers['claim_prayers']) > 0) : ?>
                                    <div>
                                        <?php $claim_total_amount = 0; ?>
                                        <table class="table table-responsive table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="12%">Type</th>
                                                    <th width="35%">Prayer Name</th>
                                                    <th>Whether Applicable?</th>
                                                    <th>Is Quantified?</th>
                                                    <th>Prayers Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($fee_prayers['claim_prayers'] as $cp) : ?>
                                                    <?php if ($cp['assessment_code'] == $fc['code']) : ?>
                                                        <tr>
                                                            <td><?= $cp['type'] ?></td>
                                                            <td><?= $cp['prayer_name'] ?></td>
                                                            <td><?= ($cp['applicable']) == 1 ? 'Yes' : 'No' ?></td>
                                                            <td><?= ($cp['is_quantified']) == 1 ? 'Yes' : 'No' ?></td>
                                                            <td><?= (isset($cp['prayer_amount']) && !empty($cp['prayer_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $cp['prayer_amount']) : '' ?></td>
                                                        </tr>
                                                        <?php $claim_total_amount += (int) $cp['prayer_amount'] ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>


                                        <div class="claimant_sum_in_dispute">
                                            <strong>Total: </strong> <?= symbol_checker($case_data['type_of_arbitration'], $claim_total_amount) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>
                                <div class="col-md-12 col-xs-12">
                                    <label for="">Sum in Dispute (Counter Claim) (Rs.):
                                    </label>

                                    <?php if (isset($fee_prayers)) : ?>
                                        <?php if (isset($fee_prayers['cc_claim_prayers']) && count($fee_prayers['cc_claim_prayers']) > 0) : ?>
                                            <table class="table table-responsive table-bordered">
                                                <?php $cc_claim_total_amount = 0; ?>
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Prayer Name</th>
                                                        <th>Whether Applicable?</th>
                                                        <th>Is Quantified?</th>
                                                        <th>Prayers Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($fee_prayers['cc_claim_prayers'] as $cp) : ?>

                                                        <?php if ($cp['assessment_code'] == $fc['code']) : ?>
                                                            <tr>
                                                                <td><?= $cp['type'] ?></td>
                                                                <td><?= $cp['prayer_name'] ?></td>
                                                                <td><?= ($cp['applicable']) == 1 ? 'Yes' : 'No' ?></td>
                                                                <td><?= ($cp['is_quantified']) == 1 ? 'Yes' : 'No' ?></td>
                                                                <td><?= (isset($cp['prayer_amount']) && !empty($cp['prayer_amount'])) ? $cp['prayer_amount'] : '' ?></td>
                                                            </tr>
                                                            <?php $cc_claim_total_amount += (int) $cp['prayer_amount'] ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                            <div class="respondent_sum_in_dispute">
                                                <strong>Total: </strong> <?= symbol_checker($case_data['type_of_arbitration'], $cc_claim_total_amount) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <fieldset class="fieldset">
                    <legend class="legend">
                        Calculated Fees
                    </legend>

                    <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="form-group col-md-12 col-xs-12">
                                    <label class="control-label">Total Arbitrators Fees:</label>
                                    <div>
                                        <?= (isset($fc['total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['total_arb_fees']) : ''  ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php  // Claimant Share 
                                    ?>
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Claimant Share</h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label class="control-label">Arbitrators Fees:</label>
                                                <div>
                                                    <?= (isset($fc['cs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['cs_arb_fees']) : ''  ?>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 col-xs-12">
                                                <label class="control-label">Administrative Expenses:</label>
                                                <div>
                                                    <?= (isset($fc['cs_adminis_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['cs_adminis_fees']) : ''  ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') :  ?>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Total Arbitrators Fees (Counter Claim):</label>
                                        <div>
                                            <?= (isset($fc['cc_total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['cc_total_arb_fees']) : ''  ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php  // Respondent Share 
                                    ?>
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Respondent Share</h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Arbitrators Fees:</label>
                                                    <div>
                                                        <?= (isset($fc['rs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['rs_arb_fees']) : ''  ?>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Administrative Expenses:</label>
                                                    <div>
                                                        <?= (isset($fc['rs_adminis_fee'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['rs_adminis_fee']) : ''  ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="form-group col-md-12 col-xs-12">
                                    <label class="control-label">Total Arbitrators Fees:</label>
                                    <div>
                                        <p>
                                            <?= (isset($fc['int_arb_total_fees_dollar'])) ? dollarMoneyFormat($fc['int_arb_total_fees_dollar']) : ''  ?>
                                        </p>
                                        <p>
                                            <?= (isset($fc['int_arb_total_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_total_fees_rupee']) : ''  ?>
                                        </p>

                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-xs-12">
                                    <label class="control-label">Total Administrative Charges:</label>
                                    <div>
                                        <p>
                                            <?= (isset($fc['int_total_adm_charges_dollar'])) ? dollarMoneyFormat($fc['int_total_adm_charges_dollar']) : ''  ?>
                                        </p>
                                        <p>
                                            <?= (isset($fc['int_total_adm_charges_rupee'])) ? INDMoneyFormat($fc['int_total_adm_charges_rupee']) : ''  ?>
                                        </p>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php  // Claimant Share 
                                    ?>
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Claimant Share</h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                <label class="control-label">Arbitrators Fees:</label>
                                                <div>
                                                    <p>
                                                        <?= (isset($fc['int_arb_claim_share_fees_dollar'])) ? dollarMoneyFormat($fc['int_arb_claim_share_fees_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($fc['int_arb_claim_share_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_claim_share_fees_rupee']) : ''  ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                <label class="control-label">Administrative Expenses:</label>
                                                <div>
                                                    <p>
                                                        <?= (isset($fc['int_claim_adm_charges_dollar'])) ? dollarMoneyFormat($fc['int_claim_adm_charges_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($fc['int_claim_adm_charges_rupee'])) ? INDMoneyFormat($fc['int_claim_adm_charges_rupee']) : ''  ?>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php  // Respondent Share 
                                    ?>

                                    <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') :  ?>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label class="control-label">Total Arbitrators Fees:</label>
                                                <div>
                                                    <p>
                                                        <?= (isset($fc['cc_int_arb_total_fees_dollar'])) ? dollarMoneyFormat($fc['cc_int_arb_total_fees_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($fc['cc_int_arb_total_fees_rupee'])) ? INDMoneyFormat($fc['cc_int_arb_total_fees_rupee']) : ''  ?>
                                                    </p>

                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 col-xs-12">
                                                <label class="control-label">Total Administrative Charges:</label>
                                                <div>
                                                    <p>
                                                        <?= (isset($fc['cc_int_total_adm_charges_dollar'])) ? dollarMoneyFormat($fc['cc_int_total_adm_charges_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($fc['cc_int_total_adm_charges_rupee'])) ? INDMoneyFormat($fc['cc_int_total_adm_charges_rupee']) : ''  ?>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Respondent Share</h4>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Arbitrators Fees:</label>
                                                    <div>
                                                        <p>
                                                            <?= (isset($fc['int_arb_res_share_fees_dollar'])) ? dollarMoneyFormat($fc['int_arb_res_share_fees_dollar']) : ''  ?>
                                                        </p>
                                                        <p>
                                                            <?= (isset($fc['int_arb_res_share_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_res_share_fees_rupee']) : ''  ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Administrative Expenses:</label>
                                                    <div>
                                                        <p>
                                                            <?= (isset($fc['int_res_adm_charges_dollar'])) ? dollarMoneyFormat($fc['int_res_adm_charges_dollar']) : ''  ?>
                                                        </p>
                                                        <p>
                                                            <?= (isset($fc['int_res_adm_charges_rupee'])) ? INDMoneyFormat($fc['int_res_adm_charges_rupee']) : ''  ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                </fieldset>
            </div>

            <div class="col-md-6 col-xs-12">
                <fieldset class="fieldset">
                    <legend class="legend">Assessment Details</legend>
                    <div class="fieldset-content-box">
                        <div id="show_calculated_fees_div_<?= $fc['id'] ?>" style="display: none;" class="calculated_fees_content_div">
                            <div id="calculated_fees_content_div_<?= $fc['id'] ?>">
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

    </div>
</div>


<!-- EDIT_CASE_FEE_COST_DETAILS -->
<?php if (isset($fc['id'])) : ?>
    <script>
        var id = '<?= $fc['id'] ?>';
        var assessment_sheet_temp_data_id = '<?= $fc['assessment_sheet_data'] ?>';
        // console.log('ID', assessment_sheet_temp_data_id);

        if (assessment_sheet_temp_data_id) {
            let assessment_sheet_decoded_data_id = JSON.parse(assessment_sheet_temp_data_id);
            let assessment_sheet_data_final_id = assessment_sheet_decoded_data_id[0];
            console.log(assessment_sheet_data_final_id)

            let calc_div_element_ID_id = 'calculated_fees_content_div_' + id;
            let show_calc_div_element_ID_id = 'show_calculated_fees_div_' + id;

            if (assessment_sheet_data_final_id.claim_assessed_seperately === 'yes') {
                let {
                    type_of_arbitration,
                    claim_amount,
                    counter_claim_amount,
                    data,
                    claim_assessed_seperately
                } = assessment_sheet_data_final_id;
                generate_assessment_view(type_of_arbitration, data, claim_amount, counter_claim_amount, claim_assessed_seperately, calc_div_element_ID_id, show_calc_div_element_ID_id);
            }
            if (assessment_sheet_data_final_id.claim_assessed_seperately === 'no') {
                let {
                    type_of_arbitration,
                    claim_amount,
                    data,
                    claim_assessed_seperately
                } = assessment_sheet_data_final_id;
                generate_assessment_view(type_of_arbitration, data, claim_amount, 0, claim_assessed_seperately, calc_div_element_ID_id, show_calc_div_element_ID_id);
            }

            delete id;
        }
    </script>
<?php endif; ?>