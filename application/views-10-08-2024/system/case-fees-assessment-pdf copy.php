<div class="content-wrapper">
    <div class="content">
        <div class="content-body">
            <div>
                <table class="table table-responsive table-bordered">
                    <tr>
                        <td class="text-center">
                            Provisional Assessment of Arbitrators' Fee & Administrative Expenses
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <?= get_full_case_number($case_data) ?>
                            <p class="mb-0">
                                <?= $case_data['case_title'] ?>
                            </p>
                        </td>
                    </tr>
                </table>
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
                            <?= (isset($assessment['arbitral_tribunal_strength'])) ? ucfirst($assessment['arbitral_tribunal_strength']) : ''; ?>
                        </div>
                    </div>

                    <?php if ($case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                        <div class="form-group col-md-3 col-sm-6 col-xs-12">

                            <label class="control-label">Dollar Price in Rupyee (Used):</label>
                            <div>
                                <?= (isset($assessment['current_dollar_price'])) ? ucfirst($assessment['current_dollar_price']) : ''; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group col-md-3 col-sm-6 col-xs-12">

                        <label class="control-label">Whether Claims or Counter Claims assessed separately:</label>
                        <div>
                            <?= (isset($assessment['c_cc_asses_sep'])) ? ucfirst($assessment['c_cc_asses_sep']) : ''; ?>
                        </div>
                    </div>

                    <?php if (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'yes') : ?>

                        <div class="form-group col-md-3  col-sm-6 col-xs-12">
                            <label class="control-label">Sum in Dispute (Claims):</label>
                            <div>
                                <?= (isset($assessment['sum_in_dispute_claim'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['sum_in_dispute_claim']) : ''  ?>
                            </div>
                        </div>

                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label">Sum in Dispute (Counter Claims):</label>
                            <div>
                                <?= (isset($assessment['sum_in_dispute_cc'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['sum_in_dispute_cc']) : ''  ?>
                            </div>
                        </div>

                    <?php else : ?>
                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label">Sum in Dispute:</label>
                            <div>
                                <?= (isset($assessment['sum_in_dispute'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['sum_in_dispute']) : ''  ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label">As per provisional assessment dated:</label>
                        <div>
                            <?= (isset($assessment['asses_date'])) ? formatReadableDate($assessment['asses_date']) : ''  ?>
                        </div>
                    </div>

                </div>

                <div>
                    <label for="">Sum in Dispute (Claim):
                    </label>
                    <?php if (isset($prayers_details) && count($prayers_details) > 0) : ?>
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
                                    <?php foreach ($prayers_details as $cp) : ?>
                                        <?php if ($cp['assessment_code'] == $assessment['code']) : ?>
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

                            <div>
                                <strong>Total: </strong> <?= $claim_total_amount ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'yes') : ?>
                    <div>
                        <label for="">Sum in Dispute (Counter Claim) (Rs.):
                        </label>

                        <?php if (isset($cc_prayers_details) && count($cc_prayers_details) > 0) : ?>
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
                                    <?php foreach ($cc_prayers_details as $cp) : ?>

                                        <?php if ($cp['assessment_code'] == $assessment['code']) : ?>
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
                                <strong>Total: </strong> <?= $cc_claim_total_amount ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div>
                    <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="form-group col-md-12 col-xs-12">
                                    <label class="control-label">Total Arbitrators Fees:</label>
                                    <div>
                                        <?= (isset($assessment['total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['total_arb_fees']) : ''  ?>
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
                                                    <?= (isset($assessment['cs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['cs_arb_fees']) : ''  ?>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 col-xs-12">
                                                <label class="control-label">Administrative Expenses:</label>
                                                <div>
                                                    <?= (isset($assessment['cs_adminis_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['cs_adminis_fees']) : ''  ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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
                                                        <?= (isset($assessment['rs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['rs_arb_fees']) : ''  ?>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Administrative Expenses:</label>
                                                    <div>
                                                        <?= (isset($assessment['rs_adminis_fee'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['rs_adminis_fee']) : ''  ?>
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
                                            <?= (isset($assessment['int_arb_total_fees_dollar'])) ? dollarMoneyFormat($assessment['int_arb_total_fees_dollar']) : ''  ?>
                                        </p>
                                        <p>
                                            <?= (isset($assessment['int_arb_total_fees_rupee'])) ? INDMoneyFormat($assessment['int_arb_total_fees_rupee']) : ''  ?>
                                        </p>

                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-xs-12">
                                    <label class="control-label">Total Administrative Charges:</label>
                                    <div>
                                        <p>
                                            <?= (isset($assessment['int_total_adm_charges_dollar'])) ? dollarMoneyFormat($assessment['int_total_adm_charges_dollar']) : ''  ?>
                                        </p>
                                        <p>
                                            <?= (isset($assessment['int_total_adm_charges_rupee'])) ? INDMoneyFormat($assessment['int_total_adm_charges_rupee']) : ''  ?>
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
                                                        <?= (isset($assessment['int_arb_claim_share_fees_dollar'])) ? dollarMoneyFormat($assessment['int_arb_claim_share_fees_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($assessment['int_arb_claim_share_fees_rupee'])) ? INDMoneyFormat($assessment['int_arb_claim_share_fees_rupee']) : ''  ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                <label class="control-label">Administrative Expenses:</label>
                                                <div>
                                                    <p>
                                                        <?= (isset($assessment['int_claim_adm_charges_dollar'])) ? dollarMoneyFormat($assessment['int_claim_adm_charges_dollar']) : ''  ?>
                                                    </p>
                                                    <p>
                                                        <?= (isset($assessment['int_claim_adm_charges_rupee'])) ? INDMoneyFormat($assessment['int_claim_adm_charges_rupee']) : ''  ?>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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
                                                        <p>
                                                            <?= (isset($assessment['int_arb_res_share_fees_dollar'])) ? dollarMoneyFormat($assessment['int_arb_res_share_fees_dollar']) : ''  ?>
                                                        </p>
                                                        <p>
                                                            <?= (isset($assessment['int_arb_res_share_fees_rupee'])) ? INDMoneyFormat($assessment['int_arb_res_share_fees_rupee']) : ''  ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12 col-xs-12 col-xs-12">
                                                    <label class="control-label">Administrative Expenses:</label>
                                                    <div>
                                                        <p>
                                                            <?= (isset($assessment['int_res_adm_charges_dollar'])) ? dollarMoneyFormat($assessment['int_res_adm_charges_dollar']) : ''  ?>
                                                        </p>
                                                        <p>
                                                            <?= (isset($assessment['int_res_adm_charges_rupee'])) ? INDMoneyFormat($assessment['int_res_adm_charges_rupee']) : ''  ?>
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

                </div>

            </div>
        </div>
    </div>
</div>