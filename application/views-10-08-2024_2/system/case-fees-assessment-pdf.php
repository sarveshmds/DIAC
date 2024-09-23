<style>
    .table-bordered tr td,
    .table-bordered tr th {
        border: 1px solid #191919;
    }

    ol,
    ul {
        padding-left: 25px;
        margin-top: 10px;
    }

    ol li,
    ul li {
        /* line-height: 17px; */
        margin-bottom: 10px;
    }
</style>

<div class="content-wrapper">
    <div class="content">
        <div class="content-body">
            <div>
                <table class="table table-responsive table-bordered">
                    <tr style="background-color: yellow;">
                        <td class="text-center">
                            <strong>
                                Provisional Assessment of Arbitrators' Fee & Administrative Expenses
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <strong>
                                <?= get_full_case_number($case_data) ?>
                                <p class="mb-0">
                                    <?= $case_data['case_title'] ?>
                                </p>
                            </strong>
                        </td>
                    </tr>
                </table>

                <?php if (isset($prayers_details) && count($prayers_details) > 0) : ?>
                    <?php $claim_total_amount = 0; ?>
                    <table class="table table-responsive table-bordered" style="margin-bottom: 0px;">

                        <?php foreach ($prayers_details as $cp) : ?>
                            <?php if ($cp['assessment_code'] == $assessment['code']) : ?>
                                <tr>
                                    <td width="70%"><?= $cp['prayer_name'] ?></td>
                                    <td>
                                        <?php if (isset($cp['applicable']) && $cp['applicable'] == 1) : ?>
                                            <?php if (isset($cp['is_quantified']) && $cp['is_quantified'] == 1) : ?>
                                                <?= (isset($cp['prayer_amount']) && !empty($cp['prayer_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $cp['prayer_amount']) : 'N/A' ?>
                                            <?php else : ?>
                                                <span>Not Quantified</span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <span>Not Applicable</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $claim_total_amount += (int) $cp['prayer_amount'] ?>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ((isset($assessment['c_cc_asses_sep']) && $assessment['c_cc_asses_sep'] == 'no') && (isset($cc_prayers_details) && count($cc_prayers_details) > 0)) : ?>
                            <?php foreach ($cc_prayers_details as $ccp) : ?>
                                <?php if ($ccp['assessment_code'] == $assessment['code']) : ?>
                                    <tr>
                                        <td width="70%"><?= $ccp['prayer_name'] ?></td>
                                        <td>
                                            <?php if (isset($ccp['applicable']) && $ccp['applicable'] == 1) : ?>
                                                <?php if (isset($ccp['is_quantified']) && $ccp['is_quantified'] == 1) : ?>
                                                    <?= (isset($ccp['prayer_amount']) && !empty($ccp['prayer_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $ccp['prayer_amount']) : 'N/A' ?>
                                                <?php else : ?>
                                                    <span>Not Quantified</span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <span>Not Applicable</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $claim_total_amount += (int) $ccp['prayer_amount'] ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <tr style="background-color: yellow;">
                            <td width="70%">
                                <strong>Total Sum in Dispute: </strong>
                            </td>
                            <td>
                                <?= symbol_checker($case_data['type_of_arbitration'], $claim_total_amount) ?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>

                <?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
                    <table class="table table-responsive table-bordered" style="margin-bottom: 0px;">
                        <tr>
                            <td width="70%">
                                <strong>Basic Amount: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['basic_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['basic_amount']) : ''  ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="70%">
                                <strong>Excess Amount: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['actual_excess_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['actual_excess_amount']) : ''  ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="70%" style="padding: 10px;">
                            </td>
                            <td>
                            </td>
                        </tr>

                        <tr>
                            <td width="70%">
                                <strong>Basic Arbitrators' Fees: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['basic_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['basic_arb_fees']) : ''  ?>
                            </td>
                        </tr>

                        <?php if ($assessment['arbitral_tribunal_strength'] == 1) : ?>
                            <tr>
                                <td width="70%">
                                    <strong>Additional Arbitrators' Fees @<?= $assessment['excess_percentage'] ?>% of Excess Amount: </strong>
                                </td>
                                <td>
                                    <?= (isset($assessment['excess_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['excess_amount']) : ''  ?>
                                </td>
                            </tr>

                            <tr>
                                <td width="70%">
                                    <strong>Additional Arbitrators' Fees of <?= $assessment['additional_arb_percentage'] ?>% of the total fee: </strong>
                                </td>
                                <td>
                                    <?= (isset($assessment['additional_arb_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['additional_arb_amount']) : ''  ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr style="background-color: yellow;">
                            <td width="70%">
                                <strong>Total Arbitrators Fees: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['total_arb_fees']) : ''  ?>
                            </td>
                        </tr>
                    </table>

                    <?php $claimant_share = 0; ?>
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td width="70%">
                                <strong>Each Party Share of Arbitrator's fees: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['cs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['cs_arb_fees']) : ''  ?>

                                <?php $claimant_share += $assessment['cs_arb_fees']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="70%">
                                <strong>Administrative Expenses payable by each party: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['cs_adminis_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['cs_adminis_fees']) : ''  ?>
                                <?php $claimant_share += $assessment['cs_adminis_fees']; ?>
                            </td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td width="70%">
                                <strong>Total Share Payable by Each Party: </strong>
                            </td>
                            <td>
                                <?= ($claimant_share) ? symbol_checker($case_data['type_of_arbitration'], $claimant_share) : ''  ?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>

                <?php if (isset($assessment['remarks']) && !empty($assessment['remarks'])) : ?>
                    <?= htmlspecialchars_decode($assessment['remarks']) ?>
                <?php endif; ?>

                <br>
                <br>
                <br>
                <br>
                <br>

                <div>
                    <strong>(<?= $deputy_counsel['user_display_name'] ?>)</strong><br>
                    <strong><?= $deputy_counsel['job_title'] ?></strong><br>
                    <strong><?= formatDatepickerDate($assessment['asses_date']) ?></strong>
                </div>

                <br>
                <br>
                <br>
                <br>
                <br>

                <strong><?= $coordinator['user_display_name'] ?></strong><br>
                <strong><?= $coordinator['job_title'] ?></strong>

            </div>
        </div>
    </div>
</div>