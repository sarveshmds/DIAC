<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/my-cases') ?>" class="btn btn-secondary btn-sm">
                    <i class="fa fa-angle-left"></i> Go Back
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
                <div>
                    <?php if (count($fee_cost_data) > 0) : ?>
                        <?php foreach ($fee_cost_data as $key => $fc) : ?>
                            <fieldset class="fieldset mb-3 fee-fieldset pb-3 bg-white">
                                <legend class="legend" style="background-color: black;">Assessment - <?= $key + 1 ?></legend>
                                <div class="fieldset-content-box">
                                    <div class="row flex-row">
                                        <div class="form-group col-md-3 col-sm-6 col-12">

                                            <label class="form-label">Arbitration Type:</label>
                                            <div>
                                                <?= (isset($case_detail['type_of_arbitration'])) ? ucfirst($case_detail['type_of_arbitration']) : ''; ?>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-12">

                                            <label class="form-label">Arbitral Tribunal Strength:</label>
                                            <div>
                                                <?= (isset($fc['arbitral_tribunal_strength'])) ? ucfirst($fc['arbitral_tribunal_strength']) : ''; ?>
                                            </div>
                                        </div>

                                        <?php if ($case_detail['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                                            <div class="form-group col-md-3 col-sm-6 col-12">

                                                <label class="form-label">Dollar Price in Rupyee (Used):</label>
                                                <div>
                                                    <?= (isset($fc['current_dollar_price'])) ? ucfirst($fc['current_dollar_price']) : ''; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group col-md-3 col-sm-6 col-12">

                                            <label class="form-label">Whether Claims or Counter Claims assessed separately:</label>
                                            <div>
                                                <?= (isset($fc['c_cc_asses_sep'])) ? ucfirst($fc['c_cc_asses_sep']) : ''; ?>
                                            </div>
                                        </div>

                                        <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>

                                            <div class="form-group col-md-3  col-sm-6 col-12">
                                                <label class="form-label">Sum in Dispute (Claims):</label>
                                                <div>
                                                    <?= (isset($fc['sum_in_dispute_claim'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['sum_in_dispute_claim']) : ''  ?>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3 col-sm-6 col-12">
                                                <label class="form-label">Sum in Dispute (Counter Claims) (Rs.):</label>
                                                <div>
                                                    <?= (isset($fc['sum_in_dispute_cc'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['sum_in_dispute_cc']) : ''  ?>
                                                </div>
                                            </div>

                                        <?php else : ?>
                                            <div class="form-group col-md-3 col-sm-6 col-12">
                                                <label class="form-label">Sum in Dispute (Rs.):</label>
                                                <div>
                                                    <?= (isset($fc['sum_in_dispute'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['sum_in_dispute']) : ''  ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group col-md-3 col-sm-6 col-12">
                                            <label class="form-label">As per provisional assessment dated:</label>
                                            <div>
                                                <?= (isset($fc['asses_date'])) ? formatReadableDate($fc['asses_date']) : ''  ?>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group col-md-3 col-sm-6 col-12">
                                            <label class="form-label">Total Arbitrators Fees (Rs.):</label>
                                            <div>
                                                <?= (isset($fc['total_arb_fees'])) ? INDMoneyFormat($fc['total_arb_fees']) : ''  ?>
                                            </div>
                                        </div> -->

                                        <div class="form-group col-md-3 col-sm-6 col-12">
                                            <label class="form-label">Assessment Approved:</label>
                                            <div>
                                                <?= (isset($fc['assessment_approved'])) ? ucfirst($fc['assessment_approved']) : ''; ?>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 col-12">
                                            <label class="form-label">Assessmemt Sheet:</label>
                                            <div>
                                                <?= (isset($fc['assessment_sheet_doc']) && !empty($fc['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $fc['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3 pb-3">
                                        <div class="card-header bg-dark  py-2">
                                            <h3 class="card-title">Calculated Fees</h3>
                                        </div>

                                        <div class="ribbon ribbon-top bg-yellow">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                            </svg>
                                        </div>

                                        <div class="card-body">

                                            <?php if (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'DOMESTIC') : ?>
                                                <div class="fieldset-content-col">
                                                    <div class="row">
                                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                            <label class="form-label">Total Arbitrators Fees:</label>
                                                            <div>
                                                                <?= (isset($fc['total_arb_fees'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['total_arb_fees']) : ''  ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <?php  // Claimant Share 
                                                            ?>
                                                            <fieldset class="fieldset">
                                                                <legend class="legend">Claimant Share</legend>
                                                                <div class="fieldset-content-box">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Arbitrators Fees:</label>
                                                                            <div>
                                                                                <?= (isset($fc['cs_arb_fees'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['cs_arb_fees']) : ''  ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Administrative Expenses:</label>
                                                                            <div>
                                                                                <?= (isset($fc['cs_adminis_fees'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['cs_adminis_fees']) : ''  ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <?php  // Respondent Share 
                                                            ?>
                                                            <fieldset class="fieldset">
                                                                <legend class="legend">Respondent Share</legend>
                                                                <div class="fieldset-content-box">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Arbitrators Fees:</label>
                                                                            <div>
                                                                                <?= (isset($fc['rs_arb_fees'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['rs_arb_fees']) : ''  ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Administrative Expenses:</label>
                                                                            <div>
                                                                                <?= (isset($fc['rs_adminis_fee'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['rs_adminis_fee']) : ''  ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>

                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                                                <div class="fieldset-content-box">
                                                    <div class="row">
                                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                            <label class="form-label">Total Arbitrators Fees:</label>
                                                            <div>
                                                                <p>
                                                                    <?= (isset($fc['int_arb_total_fees_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_arb_total_fees_dollar']) : ''  ?>
                                                                </p>
                                                                <p>
                                                                    <?= (isset($fc['int_arb_total_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_total_fees_rupee']) : ''  ?>
                                                                </p>

                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                            <label class="form-label">Total Administrative Charges:</label>
                                                            <div>
                                                                <p>
                                                                    <?= (isset($fc['int_total_adm_charges_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_total_adm_charges_dollar']) : ''  ?>
                                                                </p>
                                                                <p>
                                                                    <?= (isset($fc['int_total_adm_charges_rupee'])) ? INDMoneyFormat($fc['int_total_adm_charges_rupee']) : ''  ?>
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                                            <?php  // Claimant Share 
                                                            ?>
                                                            <fieldset class="fieldset">
                                                                <legend class="legend">Claimant Share</legend>
                                                                <div class="fieldset-content-box">
                                                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                        <label class="form-label">Arbitrators Fees:</label>
                                                                        <div>
                                                                            <p>
                                                                                <?= (isset($fc['int_arb_claim_share_fees_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_arb_claim_share_fees_dollar']) : ''  ?>
                                                                            </p>
                                                                            <p>
                                                                                <?= (isset($fc['int_arb_claim_share_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_claim_share_fees_rupee']) : ''  ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                        <label class="form-label">Administrative Expenses:</label>
                                                                        <div>
                                                                            <p>
                                                                                <?= (isset($fc['int_claim_adm_charges_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_claim_adm_charges_dollar']) : ''  ?>
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
                                                            <fieldset class="fieldset">
                                                                <legend class="legend">Respondent Share</legend>
                                                                <div class="fieldset-content-box">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Arbitrators Fees:</label>
                                                                            <div>
                                                                                <p>
                                                                                    <?= (isset($fc['int_arb_res_share_fees_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_arb_res_share_fees_dollar']) : ''  ?>
                                                                                </p>
                                                                                <p>
                                                                                    <?= (isset($fc['int_arb_res_share_fees_rupee'])) ? INDMoneyFormat($fc['int_arb_res_share_fees_rupee']) : ''  ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="form-label">Administrative Expenses:</label>
                                                                            <div>
                                                                                <p>
                                                                                    <?= (isset($fc['int_res_adm_charges_dollar'])) ? symbol_checker($case_detail['type_of_arbitration'], $fc['int_res_adm_charges_dollar']) : ''  ?>
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
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div>
                            <h3 class="text-danger"><i class="fa fa-close"></i> No assessment is added.</h3>
                        </div>
                    <?php endif; ?>

                    <?php if (count($fee_deposited_data) > 0) : ?>

                        <fieldset class="fieldset mb-3  bg-white">
                            <legend class="legend">Fee Deposited</legend>
                            <div class="fieldset-content-box">
                                <table id="dataTableFeeDepositList" class="table table-condensed table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width:8%;">S. No.</th>
                                            <th>Date of deposit</th>
                                            <th>Deposited by</th>
                                            <th>Name of depositor</th>
                                            <th>Deposited towards</th>
                                            <th>Mode of deposit</th>
                                            <th>Details</th>
                                            <th>Amount</th>
                                            <th>Cheque Bounce</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fee_deposited_data as $key2 => $fd) : ?>
                                            <tr>
                                                <td><?= $key2 + 1 ?></td>
                                                <td><?= formatReadableDate($fd['date_of_deposit']) ?></td>
                                                <td><?= $fd['cl_res_name'] ?></td>
                                                <td><?= $fd['name_of_depositor'] ?></td>
                                                <td><?= $fd['dep_by_description'] ?></td>
                                                <td><?= $fd['mod_description'] ?></td>
                                                <td><?= $fd['details_of_deposit'] ?></td>
                                                <td><?= INDMoneyFormat($fd['amount']) ?></td>
                                                <td><?= $fd['check_bounce'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <?php if (count($fee_cost_data) > 0) : ?>
                            <fieldset class="fieldset bg-white">
                                <legend class="legend">Deficiency & Excess</legend>
                                <div class="fieldset-content-box">
                                    <p>
                                        As per the latest assessment.
                                    </p>
                                    <div class="table-responsive mb-2">

                                        <?php
                                        $claimant_arb_deficiency = '';
                                        $claimant_adm_deficiency = '';
                                        $respondant_arb_deficiency = '';
                                        $respondant_adm_deficiency = '';
                                        ?>

                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <th width="20%"></th>
                                                <th class="text-center" width="20%">Claimant Arbitrators Fees</th>
                                                <th class="text-center" width="20%">Claimant Administrative Expenses</th>
                                                <th class="text-center" width="20%">Respondent Arbitrators Fees</th>
                                                <th class="text-center" width="20%">Respondent Administrative Expenses</th>
                                            </thead>
                                            <tbody>
                                                <?php if ($case_detail['type_of_arbitration'] == 'DOMESTIC') : ?>
                                                    <tr>
                                                        <th>Total Amount</th>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['latest_assessment']['cs_arb_fees']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['latest_assessment']['cs_adminis_fees']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['latest_assessment']['rs_arb_fees']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['latest_assessment']['rs_adminis_fee']) ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Paid Amount</th>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['claimant_arb_fees']['fee_deposit']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['claimant_adm_fees']['fee_deposit']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['respondent_arb_fees']['fee_deposit']) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat($balance_amount_data['respondent_adm_fees']['fee_deposit']) ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="bg-danger">
                                                        <th>Deficiency</th>
                                                        <td class="text-center">
                                                            <?php $claimant_arb_deficiency = calculateBalance($balance_amount_data['latest_assessment']['cs_arb_fees'], $balance_amount_data['claimant_arb_fees']['fee_deposit']); ?>
                                                            <?= INDMoneyFormat($claimant_arb_deficiency) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php $claimant_adm_deficiency = calculateBalance($balance_amount_data['latest_assessment']['cs_adminis_fees'], $balance_amount_data['claimant_adm_fees']['fee_deposit']); ?>

                                                            <?= INDMoneyFormat($claimant_adm_deficiency) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_arb_deficiency = calculateBalance($balance_amount_data['latest_assessment']['rs_arb_fees'], $balance_amount_data['respondent_arb_fees']['fee_deposit']); ?>

                                                            <?= INDMoneyFormat($respondant_arb_deficiency) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_adm_deficiency = calculateBalance($balance_amount_data['latest_assessment']['rs_adminis_fee'], $balance_amount_data['respondent_adm_fees']['fee_deposit']); ?>

                                                            <?= INDMoneyFormat($respondant_adm_deficiency) ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Excess</th>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat(calculateExcess($balance_amount_data['latest_assessment']['cs_arb_fees'], $balance_amount_data['claimant_arb_fees']['fee_deposit'])) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat(calculateExcess($balance_amount_data['latest_assessment']['cs_adminis_fees'], $balance_amount_data['claimant_adm_fees']['fee_deposit'])) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat(calculateExcess($balance_amount_data['latest_assessment']['rs_arb_fees'], $balance_amount_data['respondent_arb_fees']['fee_deposit'])) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= INDMoneyFormat(calculateExcess($balance_amount_data['latest_assessment']['rs_adminis_fee'], $balance_amount_data['respondent_adm_fees']['fee_deposit'])) ?>
                                                        </td>
                                                    </tr>

                                                <?php endif; ?>
                                                <?php if ($case_detail['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                                                    <tr>
                                                        <th>Total Amount</th>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat($balance_amount_data['latest_assessment']['int_arb_claim_share_fees_dollar']) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['latest_assessment']['int_arb_claim_share_fees_rupee']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat($balance_amount_data['latest_assessment']['int_claim_adm_charges_dollar']) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['latest_assessment']['int_claim_adm_charges_rupee']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat($balance_amount_data['latest_assessment']['int_arb_res_share_fees_dollar']) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['latest_assessment']['int_arb_res_share_fees_rupee']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat($balance_amount_data['latest_assessment']['int_res_adm_charges_dollar']) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['latest_assessment']['int_res_adm_charges_rupee']) ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Paid Amount</th>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($balance_amount_data['claimant_arb_fees']['fee_deposit'])) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['claimant_arb_fees']['fee_deposit']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($balance_amount_data['claimant_adm_fees']['fee_deposit'])) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['claimant_adm_fees']['fee_deposit']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($balance_amount_data['respondent_arb_fees']['fee_deposit'])) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['respondent_arb_fees']['fee_deposit']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($balance_amount_data['respondent_adm_fees']['fee_deposit'])) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($balance_amount_data['respondent_adm_fees']['fee_deposit']) ?>

                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr class="bg-danger">
                                                        <th>Deficiency</th>
                                                        <td class="text-center">
                                                            <?php $claimant_arb_deficiency = calculateBalance($balance_amount_data['latest_assessment']['int_arb_claim_share_fees_rupee'], $balance_amount_data['claimant_arb_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($claimant_arb_deficiency)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($claimant_arb_deficiency) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php $claimant_adm_deficiency = calculateBalance($balance_amount_data['latest_assessment']['int_claim_adm_charges_rupee'], $balance_amount_data['claimant_adm_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($claimant_adm_deficiency)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($claimant_adm_deficiency) ?>
                                                            </p>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_arb_deficiency = calculateBalance($balance_amount_data['latest_assessment']['int_arb_res_share_fees_rupee'], $balance_amount_data['respondent_arb_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($respondant_arb_deficiency)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($respondant_arb_deficiency) ?>
                                                            </p>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_adm_deficiency = calculateBalance($balance_amount_data['latest_assessment']['int_res_adm_charges_rupee'], $balance_amount_data['respondent_adm_fees']['fee_deposit']); ?>

                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($respondant_adm_deficiency)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($respondant_adm_deficiency) ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Excess</th>

                                                        <td class="text-center">
                                                            <?php $claimant_arb_excess = calculateExcess($balance_amount_data['latest_assessment']['int_arb_claim_share_fees_rupee'], $balance_amount_data['claimant_arb_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($claimant_arb_excess)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($claimant_arb_excess) ?>
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php $claimant_adm_excess = calculateExcess($balance_amount_data['latest_assessment']['int_claim_adm_charges_rupee'], $balance_amount_data['claimant_adm_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($claimant_adm_excess)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($claimant_adm_excess) ?>
                                                            </p>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_arb_excess = calculateExcess($balance_amount_data['latest_assessment']['int_arb_res_share_fees_rupee'], $balance_amount_data['respondent_arb_fees']['fee_deposit']); ?>
                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($respondant_arb_excess)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($respondant_arb_excess) ?>
                                                            </p>

                                                        </td>
                                                        <td class="text-center">
                                                            <?php $respondant_adm_excess = calculateExcess($balance_amount_data['latest_assessment']['int_res_adm_charges_rupee'], $balance_amount_data['respondent_adm_fees']['fee_deposit']); ?>

                                                            <p>
                                                                <?= dollarMoneyFormat(rupee_to_dollar($respondant_adm_excess)) ?>
                                                            </p>
                                                            <p>
                                                                <?= INDMoneyFormat($respondant_adm_excess) ?>
                                                            </p>
                                                        </td>
                                                    </tr>


                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                        <!-- Total Deficiency - Domestic -->
                                        <?php if ($case_detail['type_of_arbitration'] == 'DOMESTIC') : ?>
                                            <div class="mt-3">
                                                <?php $claimant_deficient_amount = calculateBalance($balance_amount_data['latest_assessment']['cs_arb_fees'], $balance_amount_data['claimant_arb_fees']['fee_deposit']) + calculateBalance($balance_amount_data['latest_assessment']['cs_adminis_fees'], $balance_amount_data['claimant_adm_fees']['fee_deposit']); ?>
                                                <p class="mb-1">

                                                    <strong>Total Claimant Deficiency: </strong>
                                                </p>

                                                <p class="text-danger fw-bold">
                                                    <?= INDMoneyFormat($claimant_deficient_amount) ?>
                                                </p>

                                                <?php $respondent_deficient_amount = calculateBalance($balance_amount_data['latest_assessment']['rs_arb_fees'], $balance_amount_data['respondent_arb_fees']['fee_deposit']) + calculateBalance($balance_amount_data['latest_assessment']['rs_adminis_fee'], $balance_amount_data['respondent_adm_fees']['fee_deposit']); ?>

                                                <p class="mb-1">

                                                    <strong>Total Respondent Deficiency: </strong>
                                                </p>
                                                <p class="text-danger fw-bold">
                                                    <?= INDMoneyFormat($respondent_deficient_amount) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Total Deficiency - International -->
                                        <?php if ($case_detail['type_of_arbitration'] == 'INTERNATIONAL') : ?>
                                            <div class="mt-3">
                                                <?php $claimant_deficient_amount = calculateBalance($balance_amount_data['latest_assessment']['int_arb_claim_share_fees_rupee'], $balance_amount_data['claimant_arb_fees']['fee_deposit']) + calculateBalance($balance_amount_data['latest_assessment']['int_claim_adm_charges_rupee'], $balance_amount_data['claimant_adm_fees']['fee_deposit']); ?>

                                                <p class="mb-1">

                                                    <strong>Total Claimant Deficiency: </strong>
                                                </p>
                                                <p class="text-danger fw-bold">
                                                    <?= dollarMoneyFormat(rupee_to_dollar($claimant_deficient_amount)) ?>
                                                </p>
                                                <p class="text-danger fw-bold">
                                                    <?= INDMoneyFormat($claimant_deficient_amount) ?>
                                                </p>


                                                <?php $respondent_deficient_amount = calculateBalance($balance_amount_data['latest_assessment']['int_arb_res_share_fees_rupee'], $balance_amount_data['respondent_arb_fees']['fee_deposit']) + calculateBalance($balance_amount_data['latest_assessment']['int_res_adm_charges_rupee'], $balance_amount_data['respondent_adm_fees']['fee_deposit']); ?>


                                                <p class="mb-1">
                                                    <strong>Total Respondent Deficiency: </strong>
                                                </p>
                                                <p class="text-danger fw-bold">
                                                    <?= dollarMoneyFormat(rupee_to_dollar($respondent_deficient_amount)) ?>
                                                </p>
                                                <p class="text-danger fw-bold">
                                                    <?= INDMoneyFormat($respondent_deficient_amount) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($case_detail['type'] == 'claimant' || ($case_detail['type'] == 'Advocate' && isset($appearing_for) && $appearing_for['type'] == 'claimant')) : ?>
                                        <table class="table table-condensed table-striped table-bordered dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" colspan="4">
                                                        Pay Deficiency Fees (Claimant)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Arbitrator Fees Deficiency</th>
                                                    <td>
                                                        <?= (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'INTERNATIONAL') ? '<p class="mb-2">' . dollarMoneyFormat(rupee_to_dollar($claimant_arb_deficiency)) . '</p>' : '' ?>
                                                        <?= INDMoneyFormat($claimant_arb_deficiency) ?>
                                                    </td>
                                                    <td>

                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-rupee-sign"></i>
                                                            </span>
                                                            <input type="text" name="int_your_payable_share_dollar" id="int_your_payable_share_dollar" value="<?= $claimant_arb_deficiency ?>" class="form-control" />
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <?php if ($claimant_arb_deficiency > 0) : ?>
                                                            <button class="btn btn-custom" type="button">
                                                                <i class="fas fa-paper-plane"></i> Pay Now
                                                            </button>
                                                        <?php endif; ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Administrative Fees Deficiency</th>
                                                    <td>
                                                        <?= (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'INTERNATIONAL') ? '<p class="mb-2">' . dollarMoneyFormat(rupee_to_dollar($claimant_adm_deficiency)) . '</p>' : '' ?>
                                                        <?= INDMoneyFormat($claimant_adm_deficiency) ?>
                                                    </td>
                                                    <td>

                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-rupee-sign"></i>
                                                            </span>
                                                            <input type="text" value="<?= $claimant_adm_deficiency ?>" class="form-control" />
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <?php if ($claimant_adm_deficiency > 0) : ?>
                                                            <button class="btn btn-custom" type="button">
                                                                <i class="fas fa-paper-plane"></i> Pay Now
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>

                                    <?php if ($case_detail['type'] == 'respondant' || ($case_detail['type'] == 'Advocate' && isset($appearing_for) && $appearing_for['type'] == 'respondant')) : ?>
                                        <table class="table table-condensed table-striped table-bordered dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" colspan="4">
                                                        Pay Deficiency Fees (Respondent)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Arbitrator Fees Deficiency</th>
                                                    <td>
                                                        <?= (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'INTERNATIONAL') ? '<p class="mb-2">' . dollarMoneyFormat(rupee_to_dollar($respondant_arb_deficiency)) . '</p>' : '' ?>
                                                        <?= INDMoneyFormat($respondant_arb_deficiency) ?>
                                                    </td>
                                                    <td>

                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-rupee-sign"></i>
                                                            </span>
                                                            <input type="text" value="<?= $respondant_arb_deficiency ?>" class="form-control" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($respondant_adm_deficiency > 0) : ?>
                                                            <button class="btn btn-custom" type="button">
                                                                <i class="fas fa-paper-plane"></i> Pay Now
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Administrative Fees Deficiency</th>
                                                    <td>
                                                        <?= (isset($case_detail['type_of_arbitration']) && $case_detail['type_of_arbitration'] == 'INTERNATIONAL') ? '<p class="mb-2">' . dollarMoneyFormat(rupee_to_dollar($respondant_adm_deficiency)) . '</p>' : '' ?>
                                                        <?= INDMoneyFormat($respondant_adm_deficiency) ?>
                                                    </td>
                                                    <td>

                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-rupee-sign"></i>
                                                            </span>
                                                            <input type="text" value="<?= $respondant_adm_deficiency ?>" class="form-control" />
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <?php if ($respondant_adm_deficiency > 0) : ?>
                                                            <button class="btn btn-custom" type="button">
                                                                <i class="fas fa-paper-plane"></i> Pay Now
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>

                                </div>
                            </fieldset>
                        <?php endif; ?>
                        <!-- Amount Details end -->
                    <?php else : ?>
                        <div>
                            <h3 class="text-danger"><i class="fa fa-close"></i> No fee is deposited in this case.</h3>
                        </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#datatable_my_cases').DataTable()
</script>