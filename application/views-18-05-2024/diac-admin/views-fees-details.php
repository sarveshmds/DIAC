<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
    <?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box p-0" style="background-color: transparent; box-shadow: none;">
                    <div class="box-body p-0">
                        <div>
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        Assessments:
                                    </h4>
                                </div>
                                <div class="box-body">
                                    <?php if (count($fee_cost_data) > 0) : ?>
                                        <?php foreach ($fee_cost_data as $key => $fc) : ?>
                                            <?php require_once(APPPATH . 'views/templates/components/fee_assessment_fieldset.php'); ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="">
                                            <h5 class="text-danger"><i class="fa fa-close"></i> No assessment is added.</h5>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="box box-warning mb-0">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        Fee Deposited:
                                    </h4>
                                </div>
                                <div class="box-body">
                                    <?php if (count($fee_deposited_data) > 0) : ?>
                                        <fieldset class="fieldset mb-15 fee-fieldset">
                                            <legend>Fee Deposited</legend>
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
                                            <fieldset class="fieldset mt-25">
                                                <legend>Deficiency & Excess</legend>
                                                <div class="fieldset-content-box">
                                                    <p>
                                                        As per the latest assessment.
                                                    </p>
                                                    <div>
                                                        <table class="table table-condensed table-striped table-bordered dt-responsive">
                                                            <thead>
                                                                <th width="15%"></th>
                                                                <th class="text-center">Claimant Arbitrators Fees</th>
                                                                <th class="text-center">Claimant Administrative Expenses</th>
                                                                <th class="text-center">Respondent Arbitrators Fees</th>
                                                                <th class="text-center">Respondent Administrative Expenses</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th>Total Amount</th>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['cs_arb_cs_tot']) ?><br>

                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['cs_arb_cs_tot'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['cs_adm_tot']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['cs_adm_tot'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['rs_arb_tot']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['rs_arb_tot'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['rs_adm_tot']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['rs_adm_tot'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Paid Amount</th>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['ARB_CS']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['ARB_CS'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['ADM_CS']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['ADM_CS'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['ARB_RS']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['ARB_RS'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['ADM_RS']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['ADM_RS'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Deficiency</th>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['arb_cs_remaining']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['arb_cs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['adm_cs_remaining']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['adm_cs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['arb_rs_remaining']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['arb_rs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['adm_rs_remaining']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['adm_rs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Excess</th>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['excess_arb_cs']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['excess_arb_cs'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['excess_adm_cs']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['excess_adm_cs'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['excess_arb_rs']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['excess_arb_rs'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= INDMoneyFormat($fees_deficiency['excess_adm_rs']) ?>
                                                                        <br>
                                                                        <span class="badge bg-orange"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['excess_adm_rs'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div>
                                                            <div class="box box-body flex-row">
                                                                <div style="    margin-right: 20px;border-right: 2px solid #c1c1c1;padding-right: 20px;">
                                                                    <h5 class="box-title">Total Claimant Deficiency: </h5>
                                                                    <p>
                                                                        <span class="font-weight-bold" style="font-size: 16px !important;"><?= INDMoneyFormat($fees_deficiency['arb_cs_remaining'] + $fees_deficiency['adm_cs_remaining']) ?>
                                                                        </span>

                                                                        <br>

                                                                        <span class="badge bg-red" style="margin-top: 4px;font-size: 14px !important;"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['arb_cs_remaining'] + $fees_deficiency['adm_cs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </p>
                                                                </div>

                                                                <div>
                                                                    <h5 class="box-title">Total Respondant Deficiency: </h5>
                                                                    <p>
                                                                        <span class="font-weight-bold" style="font-size: 16px !important;">
                                                                            <?= INDMoneyFormat($fees_deficiency['arb_rs_remaining'] + $fees_deficiency['adm_rs_remaining']) ?>
                                                                        </span>

                                                                        <br>

                                                                        <span class="badge bg-red" style="margin-top: 4px;font-size: 14px !important;"><?= dollar_converstion_if_international($case_data['type_of_arbitration'], $fees_deficiency['arb_rs_remaining'] + $fees_deficiency['adm_rs_remaining'], $fee_cost_data[count($fee_cost_data) - 1]['current_dollar_price']) ?></span>
                                                                    </p>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>
                                            </fieldset>
                                        <?php endif; ?>
                                        <!-- Amount Details end -->
                                    <?php else : ?>
                                        <div class="">
                                            <h5 class="text-danger"><i class="fa fa-close"></i> No fee is deposited in this case.</h5>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>