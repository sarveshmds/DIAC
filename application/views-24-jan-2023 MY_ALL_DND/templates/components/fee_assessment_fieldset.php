<fieldset class="fieldset mb-15 fee-fieldset">
    <legend>Assessment - <?= $key + 1 ?></legend>
    <div class="fieldset-content-box">

        <?php if ($this->session->userdata('role') != 'ACCOUNTS') : ?>
            <?php if (isset($assessment_editable) && $assessment_editable == true) : ?>
                <div class="text-right mb-15">
                    <!-- <button class="btn btn-warning btn-sm btn_edit_assessment" data-fee-id="<?= $fc['id'] ?>"><i class="fa fa-edit"></i> Edit</button> -->
                    <a href="<?= base_url('case-fees-details/add-assessment/' . $case_no . '?id=' . customURIEncode($fc['id'])) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                    <!-- <button class="btn btn-danger btn-sm btn_delete_assessment" data-fee-id="<?= $fc['id'] ?>"><i class="fa fa-trash"></i> Delete</button> -->
                </div>
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
                        <?= (isset($fc['sum_in_dispute_claim'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['sum_in_dispute_claim']) : ''  ?>
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

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Assessment Approved:</label>
                <div>
                    <?= (isset($fc['assessment_approved'])) ? ucfirst($fc['assessment_approved']) : ''; ?>
                </div>
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Assessmemt Sheet:</label>
                <div>
                    <?= (isset($fc['assessment_sheet_doc']) && !empty($fc['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $fc['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                </div>
            </div>
        </div>

        <fieldset class="fieldset">
            <legend class="legend">
                Calculated Fees
            </legend>

            <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
                <div class="fieldset-content-box">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label">Total Arbitrators Fees:</label>
                            <div>
                                <?= (isset($fc['total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['total_arb_fees']) : ''  ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php  // Claimant Share 
                            ?>
                            <fieldset class="fieldset bg-white">
                                <legend class="inner-legend">Claimant Share</legend>
                                <div class="fieldset-content-box">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">Arbitrators Fees:</label>
                                        <div>
                                            <?= (isset($fc['cs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['cs_arb_fees']) : ''  ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">Administrative Expenses:</label>
                                        <div>
                                            <?= (isset($fc['cs_adminis_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['cs_adminis_fees']) : ''  ?>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php  // Respondent Share 
                            ?>
                            <fieldset class="fieldset bg-white">
                                <legend class="inner-legend">Respondent Share</legend>
                                <div class="fieldset-content-box">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Arbitrators Fees:</label>
                                            <div>
                                                <?= (isset($fc['rs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['rs_arb_fees']) : ''  ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Administrative Expenses:</label>
                                            <div>
                                                <?= (isset($fc['rs_adminis_fee'])) ? symbol_checker($case_data['type_of_arbitration'], $fc['rs_adminis_fee']) : ''  ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                <div class="fieldset-content-box">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
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

                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
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
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php  // Claimant Share 
                            ?>
                            <fieldset class="fieldset  bg-white">
                                <legend class="inner-legend">Claimant Share</legend>
                                <div class="fieldset-content-box">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
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

                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
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
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php  // Respondent Share 
                            ?>
                            <fieldset class="fieldset  bg-white">
                                <legend class="inner-legend">Respondent Share</legend>
                                <div class="fieldset-content-box">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
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

                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
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
                            </fieldset>
                        </div>

                    </div>
                </div>
            <?php endif; ?>
        </fieldset>


    </div>
</fieldset>