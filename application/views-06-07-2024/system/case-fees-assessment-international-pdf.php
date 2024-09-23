<style>
    .table-bordered tr td,
    .table-bordered tr th {
        border: 1px solid #191919;
    }
</style>

<div class="content-wrapper">
    <div class="content">
        <div class="content-body">
            <!-- Claim Fees Calculation Details -->
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
                                        <?= (isset($cp['prayer_amount']) && !empty($cp['prayer_amount'])) ? symbol_checker($case_data['type_of_arbitration'], $cp['prayer_amount']) : '' ?>
                                    </td>
                                </tr>
                                <?php $claim_total_amount += (int) $cp['prayer_amount'] ?>
                            <?php endif; ?>
                        <?php endforeach; ?>

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
                            <?= (isset($assessment['int_arb_total_fees_dollar'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['int_arb_total_fees_dollar']) : ''  ?>
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
                            <?= (isset($assessment['int_arb_claim_share_fees_dollar'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['int_arb_claim_share_fees_dollar']) : ''  ?>

                            <?php $claimant_share += $assessment['int_arb_claim_share_fees_dollar']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="70%">
                            <strong>Administrative Expenses payable by each party: </strong>
                        </td>
                        <td>
                            <?= (isset($assessment['int_claim_adm_charges_dollar'])) ? symbol_checker($case_data['type_of_arbitration'], $assessment['int_claim_adm_charges_dollar']) : ''  ?>
                            <?php $claimant_share += $assessment['int_claim_adm_charges_dollar']; ?>
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

                <div>
                    <p>
                        Calculation has been made on the basis of Claim amount mentioned in Annexure P-5 to the application under Section 11(6) and 11(12) of the Arbitration and Conciliation Act filed before the Hon’ble Supreme Court of India
                    </p>

                    <?php if (isset($assessment['remarks']) && !empty($assessment['remarks'])) : ?>
                        <?= htmlspecialchars_decode($assessment['remarks']) ?>
                    <?php endif; ?>
                </div>

                <!-- Administrative Charges of Claim -->
                <div>
                    <h4 style="text-align: center; margin-top:10px; margin-bottom:10px;">
                        ADMINISTRATIVE COST AS PER SCHEDULE A OF DIAC (ADMINISTRATIVE COSTS AND ARBITRATORS’ FEES) RULES, 2018
                    </h4>

                    <!-- NOTE: Symbol of administrative cost will come in Rs. so pass the type of arbitration : DOMESTIC -->

                    <table class="table table-responsive table-bordered" style="margin-bottom: 0px;">
                        <tr>
                            <td width="70%">
                                <strong>Fixed fee (to be paid alongwith the request for arbitration): </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['adm_basic_amount'])) ? INDMoneyFormat($assessment['adm_basic_amount']) : ''  ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="70%">
                                <strong>Basic Fee as per Schedule: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['adm_basic_adm_fees'])) ? INDMoneyFormat($assessment['adm_basic_adm_fees']) : ''  ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="70%">
                                <strong>Additional Fees@ <?= $assessment['adm_excess_percentage'] ?>% of excess amount: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['adm_actual_excess_amount'])) ? INDMoneyFormat($assessment['adm_actual_excess_amount']) : ''  ?>
                            </td>
                        </tr>

                        <tr style="background-color: yellow;">
                            <td width="70%">
                                <strong>Total Administrative Cost: </strong>
                            </td>
                            <td>
                                <?= (isset($assessment['int_total_adm_charges_rupee'])) ? INDMoneyFormat($assessment['int_total_adm_charges_rupee']) : ''  ?>
                            </td>
                        </tr>
                    </table>

                </div>

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