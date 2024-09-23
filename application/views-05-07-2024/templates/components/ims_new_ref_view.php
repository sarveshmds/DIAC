<div class="row">
    <div class="col-xs-12 form-group">


        <fieldset class="fieldset">
            <legend class="legend">Claimant Details</legend>
            <div class="fieldset-content-box">
                <div class="table-responsive p-0">
                    <table class="table table-condensed table-striped table-bordered" id="claimants-table">
                        <thead>
                            <tr>
                                <th>Claimant Name</th>
                                <th colspan="2" width="40%">Address</th>
                                <th>E-mail</th>
                                <th>Registered Mobile No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($new_reference_claimant)) : ?>
                                <?php foreach ($new_reference_claimant as $cl) : ?>
                                    <tr>
                                        <td><?= $cl['name'] ?></td>
                                        <td width="20%">
                                            <p>
                                                <?= $cl['address_one'] ?>
                                            </p>
                                            <p>
                                                <?= $cl['address_two'] ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>State:</strong> <?= $cl['state_name'] ?>
                                            </p>
                                            <p>
                                                <strong>Country:</strong> <?= $cl['country_name'] ?>
                                            </p>
                                            <p>
                                                <strong>Pincode:</strong> <?= $cl['pincode'] ?>
                                            </p>
                                        </td>
                                        <td><?= $cl['email_id'] ?></td>
                                        <td><?= $cl['phone_number'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-xs-12 form-group">
        <fieldset class="fieldset">
            <legend class="legend">Respondant Details</legend>
            <div class="fieldset-content-box">
                <div class="table-responsive p-0">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Respondant Name</th>
                                <th colspan="2" width="40%">Address</th>
                                <th>E-mail</th>
                                <th>Registered Mobile No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($new_reference_respondants)) : ?>
                                <?php foreach ($new_reference_respondants as $res) : ?>
                                    <tr>
                                        <td><?= $res['name'] ?></td>
                                        <td width="20%">
                                            <p>
                                                <?= $res['address_one'] ?>
                                            </p>
                                            <p>
                                                <?= $res['address_two'] ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>State:</strong> <?= $res['state_name'] ?>
                                            </p>
                                            <p>
                                                <strong>Country:</strong> <?= $res['country_name'] ?>
                                            </p>
                                            <p>
                                                <strong>Pincode:</strong> <?= $res['pincode'] ?>
                                            </p>
                                        </td>
                                        <td><?= $res['email_id'] ?></td>
                                        <td><?= $res['phone_number'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="type_of_arbitration">Type of arbitration</label>
        <p class="mb-0">
            <?= $new_reference['type_of_arbitration_desc'] ?>
        </p>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="case_type">Case Type</label>
        <p class="mb-0">
            <?= $new_reference['case_type_desc'] ?>
        </p>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label class="form-label" for="case_type">Arbitral Tribunal Strength</label>
        <p class="mb-0">
            <?= ARBITRAL_TRIBUNAL_STRENGTH[$new_reference['arbitral_tribunal_strength']] ?>
        </p>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="claim_amount">Claim Amount (Rs.)</label>
        <p class="mb-0">
            <?= INDMoneyFormat($new_reference['claim_amount']) ?>
        </p>
    </div>

    <div class="col-xs-12 mb-3" id="emergency_case_details_col" <?= ($new_reference['case_type'] == 'EMERGENCY') ? '' : 'style="display: none;"' ?>>
        <fieldset class="fieldset">
            <legend class="legend">Emergency Case Details</legend>
            <div class="fieldset-content-box">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                        <label class="form-label" for="soc_document">SOC <span class="text-danger">*</span></label>
                        <p class="mb-0">
                            <?php if (!empty($new_reference['soc_document'])) : ?>
                                <a href="<?= base_url(VIEW_EFILING_NF_SOC_UPLOADS_FOLDER . $new_reference['soc_document']) ?>" class="btn btn-custom" target="_BLANK">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            <?php else : ?>
                                <span class="mb-0 badge bg-danger">No Document</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                        <label class="form-label" for="urgency_app_document">Urgency Application <span class="text-danger">*</span></label>
                        <p class="mb-0">
                            <?php if (!empty($new_reference['urgency_app_document'])) : ?>
                                <a href="<?= base_url(VIEW_EFILING_NF_URGENCY_DOC_UPLOADS_FOLDER . $new_reference['urgency_app_document']) ?>" class="btn btn-custom" target="_BLANK">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            <?php else : ?>
                                <span class="mb-0 badge bg-danger">No Document</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                        <label class="form-label" for="proof_of_service_doc">Proof of Service <span class="text-danger">*</span></label>
                        <p class="mb-0">
                            <?php if (!empty($new_reference['proof_of_service_doc'])) : ?>
                                <a href="<?= base_url(VIEW_EFILING_NF_POS_UPLOADS_FOLDER . $new_reference['proof_of_service_doc']) ?>" class="btn btn-custom" target="_BLANK">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            <?php else : ?>
                                <span class="mb-0 badge bg-danger">No Document</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <!-- Domestic Fees Calculation -->
    <?php if ($new_reference['type_of_arbitration'] == 'DOMESTIC') : ?>
        <div class="col-xs-12 mb-3">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Arbitrator Fees (Rs.)
                        </th>
                        <td>
                            <p class="mb-0">
                                <?= INDMoneyFormat($new_reference['arb_total_fees']) ?>
                            </p>
                            <small class="text-muted">(Total)</small>
                        </td>
                        <td width="30%" class="text-center">
                            <p class="mb-0">
                                <?= INDMoneyFormat($new_reference['arb_your_share_fees']) ?>
                            </p>
                            <small class="text-muted">(Your Share)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Administrative Charges (Rs.)
                        </th>
                        <td>

                        </td>
                        <td class="text-center">
                            <p class="mb-0">
                                <?= INDMoneyFormat($new_reference['adm_charges']) ?>
                            </p>
                            <small class="text-muted">(Each Party)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Your share (Rs.)
                        </th>
                        <td>

                        </td>
                        <td class="text-center">
                            <p class="mb-0">
                                <?= INDMoneyFormat($new_reference['your_share']) ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Your share payable (Rs.)
                            <div>
                                <small>You need to pay this amount to register your case.</small>
                            </div>
                        </th>
                        <td>
                            <div class="text-center">
                                <small>(<?= ARB_FEES_SHARE_PAYABLE_PERCENT ?>% of Arbitrator Fees + Administrative Charges)</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <h5 class="mb-0 text-danger">
                                <?= INDMoneyFormat($new_reference['your_payable_share']) ?>
                            </h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <!-- International Calculated Fees -->
    <?php if ($new_reference['type_of_arbitration'] == 'INTERNATIONAL') : ?>
        <div class="col-xs-12 mb-3">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>
                            Arbitrator Fees
                        </th>
                        <td>
                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_arb_total_fees_dollar'])) ? $new_reference['int_arb_total_fees_dollar'] : '' ?>
                                </p>

                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_arb_total_fees_rupee'])) ? INDMoneyFormat($new_reference['int_arb_total_fees_rupee']) : '' ?>
                            </div>


                            <small class="text-muted">(Total) (Based on Arbitral Tribunal Strength)</small>
                        </td>
                        <td>

                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_arb_your_share_fees_dollar'])) ? $new_reference['int_arb_your_share_fees_dollar'] : '' ?>
                                </p>
                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_arb_your_share_fees_rupee'])) ? INDMoneyFormat($new_reference['int_arb_your_share_fees_rupee']) : '' ?>
                            </div>

                            <small class="text-muted">(Your Share)</small>
                        </td>
                    </tr>
                    <tr class="international_adm_charges_row">
                        <th>
                            Total Administrative Charges
                        </th>
                        <td>

                        </td>
                        <td>
                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_total_adm_charges_dollar'])) ? $new_reference['int_total_adm_charges_dollar'] : '' ?>
                                </p>
                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_total_adm_charges_rupee'])) ? INDMoneyFormat($new_reference['int_total_adm_charges_rupee']) : '' ?>
                            </div>

                            <small class="text-muted">(Total)</small>
                        </td>
                    </tr>
                    <tr class="international_adm_charges_row">
                        <th>
                            Each Party Administrative Charges
                        </th>
                        <td>

                        </td>
                        <td>
                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_each_party_adm_charges_dollar'])) ? $new_reference['int_each_party_adm_charges_dollar'] : '' ?>
                                </p>
                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_each_party_adm_charges_rupee'])) ? INDMoneyFormat($new_reference['int_each_party_adm_charges_rupee']) : '' ?>
                            </div>

                            <small class="text-muted">(Each Party)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Your share
                        </th>
                        <td>

                        </td>
                        <td>

                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_your_share_dollar'])) ? $new_reference['int_your_share_dollar'] : '' ?>
                                </p>
                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_your_share_rupee'])) ? INDMoneyFormat($new_reference['int_your_share_rupee']) : '' ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Your share payable
                            <div>
                                <small>You need to pay this amount to register your case.</small>
                            </div>
                        </th>
                        <td>
                            <div>
                                <small>(25 % of Arbitrator Fees + Administrative Charges)</small>
                            </div>
                        </td>
                        <td>

                            <div class="input-group mb-2">
                                <p class="input-group-text">
                                    $ <?= (isset($new_reference['int_your_payable_share_dollar'])) ? $new_reference['int_your_payable_share_dollar'] : '' ?>
                                </p>
                            </div>

                            <div class="input-group mb-2">
                                <?= (isset($new_reference['int_your_payable_share_rupee'])) ? INDMoneyFormat($new_reference['int_your_payable_share_rupee']) : '' ?>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <div class="col-md-4 col-sm-6 col-12 mb-3">
        <label class="form-label" for="upload_document">Case Document</label>
        <p class="mb-0">
            <?php if (!empty($new_reference['document'])) : ?>
                <a href="<?= base_url(VIEW_EFILING_NEW_REFERENCE_UPLOADS_FOLDER . $new_reference['document']) ?>" class="btn btn-custom" target="_BLANK">
                    <i class="fa fa-eye"></i> View
                </a>
            <?php else : ?>
                <span class="mb-0 badge bg-danger">No Document</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="remarks">Department Remarks</label>
        <p class="mb-0">
            <?= $new_reference['remarks'] ?>
        </p>
    </div>

    <div class="col-xs-12 mb-3">
        <fieldset class="fieldset">
            <legend class="legend">Transaction Details</legend>
            <div class="fieldset-content-box">
                <div class="table-responsive p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Txn Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($transaction)) : ?>
                                <tr>
                                    <td><?= $transaction['txn_id'] ?></td>
                                    <td><?= INDMoneyFormat($transaction['amount']) ?></td>
                                    <td><?= formatReadableDate($transaction['txn_date']) ?></td>
                                    <td><?= $transaction['payment_status_desc'] ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </div>


</div>