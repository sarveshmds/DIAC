<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="">

        <div style="text-align: justify;">
            <p>To,</p>

            <div>
                <div style="width: 50%; float: left;">
                    <?php foreach ($parties as $key => $party) : ?>
                        <?php if ($party['type'] == 'claimant') : ?>
                            <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= $party['name'] ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= $party['perm_address_1'] ?>
                                </p>
                                <?php if ((isset($party['perm_address_2']) && $party['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $party['perm_address_2'] ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ((isset($party['perm_city']) && $party['perm_city'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $party['perm_city'] ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= $party['email'] ?></p>
                                <p style="margin-bottom: 1px;"><?= $party['contact'] ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div style="width:40%; float: right; vertical-align: bottom;">
                    <strong>...Claimant</strong>
                </div>
            </div>

            <?php if (count($claimant_counsels) > 0) : ?>
                <div style="margin-bottom:0px;clear:both;">
                    <div style="width: 50%; float: left;">
                        <?php foreach ($claimant_counsels as $key => $counsel) : ?>
                            <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= $counsel['name'] ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= $counsel['perm_address_1'] ?>
                                </p>
                                <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $counsel['perm_address_2'] ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= $counsel['email'] ?></p>
                                <p style="margin-bottom: 1px;"><?= $counsel['phone_number'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="width:40%; float: right; vertical-align: bottom;">
                        <strong>...Counsel</strong>
                    </div>
                </div>
            <?php endif; ?>

            <div style="clear:both; margin: 10px 0px 4px 0px;">
                <strong>VS</strong>
            </div>

            <div>
                <div style="width: 50%; float: left;">
                    <?php foreach ($parties as $key => $party) : ?>
                        <?php if ($party['type'] == 'respondant') : ?>
                            <div>
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= $party['name'] ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= $party['perm_address_1'] ?>
                                </p>
                                <?php if ((isset($party['perm_address_2']) && $party['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $party['perm_address_2'] ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ((isset($party['perm_city']) && $party['perm_city'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $party['perm_city'] ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= $party['email'] ?></p>
                                <p style="margin-bottom: 1px;"><?= $party['contact'] ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div style="width:40%; float: right; vertical-align: bottom;">
                    <strong>...Respondent</strong>
                </div>
            </div>

            <?php if (count($respondent_counsels) > 0) : ?>
                <div style="margin-bottom:0px;clear:both;">
                    <div style="width: 50%; float: left;">
                        <?php foreach ($respondent_counsels as $key => $counsel) : ?>
                            <div <?= ($key > 1) ? 'style = "margin-top: 6px;"' : '' ?>>
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= $counsel['name'] ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= $counsel['perm_address_1'] ?>
                                </p>
                                <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= $counsel['perm_address_2'] ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= $counsel['email'] ?></p>
                                <p style="margin-bottom: 1px;"><?= $counsel['phone_number'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="width:40%; float: right; vertical-align: bottom;">
                        <strong>...Counsel</strong>
                    </div>
                </div>
            <?php endif; ?>

            <div style="clear:both;"></div>

            <p>
                <strong><u>In the matter of Arbitration between:</u></strong>
            </p>
            <p style="margin-bottom: 2px;">
                <strong><?= $case_data['case_title'] ?></strong>
            </p>

            <?php if (isset($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') : ?>
                <p style="margin-bottom: 2px;">
                    <strong>Arb. No. <?= $case_data['reffered_by_court_case_type'] ?>/<?= $case_data['arbitration_petition'] ?>/<?= $case_data['reffered_by_court_case_year'] ?></strong>
                </p>
            <?php endif; ?>
            <?php if (isset($case_data['reffered_by']) && $case_data['reffered_by'] == 'OTHER') : ?>
                <p style="margin-bottom: 2px;">
                    <strong>Arb. No. <?= $case_data['arbitration_petition'] ?></strong>
                </p>
            <?php endif; ?>

            <p>
                <strong>
                    Case Ref. No. : <?= get_full_case_number($case_data) ?> (Quote this reference no. for future communications with DIAC).
                </strong>
            </p>

            <p>
                Sir/Madam,
            </p>
            <p>
                The above mentioned matter has been referred to Delhi International Arbitration Centre passed by <?= $case_data['reffered_by_desc'] ?>.
            </p>
            <p>
                <strong>A.</strong> You are required to file a request in writing along with tentative claim amount (preferably within 15 days) in accordance with Rule 4 of DIAC(Arbitration Proceedings) Rules, 2023 (hereinafter called “the Request”) to initiate the arbitration. Party making request for arbitration may also file Statement of Claim along with the request. Our detailed rules are available on our website - dhcdiac.nic.in
            </p>

            <p>
                The request/statement of claim and the documents (if accompanied therewith) should be in [quadruplicate/duplicate] along with one soft copy and should be accompanied with proof of service to the opposite party(s) alongwith documentary proof of date of delivery. All copies must be duly signed. Entire filing is to be on A-4 size paper and contents should be typed on both sides of the paper.
            </p>
            <p>
                <strong>
                    B. <u>Administrative Costs, Arbitrator’s Fees & Miscellaneous expenses.</u>
                </strong>
            </p>
            <p style="margin-bottom: 0px;">
                As notified by the Arbitration Committee of High Court of Delhi, the revised Administrative Costs (w.e.f 17.11.2020), are as under :-
            </p>

            <table class="table w-100 table-bordered" cellspacing="0">
                <thead style="background-color: #f1f1f1; color: black; text-align: center;">
                    <tr>
                        <th width="10%">Srl. No.</th>
                        <th width="50%">Sum of amount of claim/counter claim (in Rs.)</th>
                        <th width="40%">Total Misc./Administrative Expenses (in Rs.) each Party</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">i)</td>
                        <td>Upto 20,00,000</td>
                        <td>10,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">ii)</td>
                        <td>20,00,001 to 1,00,00,000</td>
                        <td>20,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">iii)</td>
                        <td>1,00,000,01 to 5,00,00,000</td>
                        <td>40,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">iv)</td>
                        <td>5,00,00,001 and above</td>
                        <td>50,000</td>
                    </tr>
                </tbody>
            </table>

            <p style="margin-bottom: 0px;">
                It be noted that where arbitration has been commenced on or after 01.12.2022, in terms of Section 21 of the Arbitration & conciliation Act, 1996. The Administrative Costs have been revised as under :-
            </p>
            <table class="table w-100 table-bordered" cellspacing="0">
                <thead style="background-color: #f1f1f1; color: black; text-align: center;">
                    <tr>
                        <th width="10%">Srl. No.</th>
                        <th width="50%">Sum of amount of claim/counter claim (in Rs.)</th>
                        <th width="40%">Total Misc./Administrative Expenses (in Rs. Payable by each side) </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1)</td>
                        <td>Upto 20,00,000</td>
                        <td>10,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">2)</td>
                        <td>20,00,001 to 1,00,00,000</td>
                        <td>20,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">3)</td>
                        <td>1,00,000,01 to 5,00,00,000</td>
                        <td>40,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">4)</td>
                        <td>5,00,00,001 to 10,00,00,000</td>
                        <td>75,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">5)</td>
                        <td>10,00,00,001 to 50,00,00,000</td>
                        <td>1,00,000</td>
                    </tr>
                    <tr>
                        <td class="text-center">6)</td>
                        <td>50,00,00,001</td>
                        <td>2,50,000</td>
                    </tr>
                </tbody>
            </table>

            <p>
                Your attention is invited to Schedule B of DIAC (Administrative Costs & Arbitrator’s Fees) Rules, 2018.<strong><u> You are requested to compute the fees as per your tentative claim and deposit 50% of the same alongwith administrative cost/miscellaneous expenses.</u></strong>
            </p>

            <p>
                <strong>Mode of Payment:</strong> Payments can be made either by Pay Order/Demand Draft/Cheque drawn in favour of the “below mentioned account numbers”, payable at New Delhi along with a covering letter containing details of the case or pay through RTGS / NEFT, Account No. : <?= DEPARTMENT_ACC_NO ?>, IFSC Code: <?= DEPARTMENT_IFSC ?> [<?= DEPARTMENT_BANK_NAME ?>], MICR Code: <?= DEPARTMENT_MICR_CODE ?>, Branch: <?= DEPARTMENT_BANK_BRANCH ?> with intimation to DIAC
            </p>

            <p>
                The fees, initially deposited would be provisional. After filing of claim and reply/counter claim, DIAC would re-assess the fees. In case of any deficiency, the same will have to be deposited as and when demanded. Each claim (including interest, if any) should be properly valued and quantified. Where the amount of Claim or the Counter Claim is not quantifiable/ cannot be valued in monetary terms, Rule 33 of DIAC (Arbitration Proceedings) Rules, 2023, would apply.
            </p>
            <?php if ($case_data['arbitrator_status'] != 1) : ?>
                <p>
                    <strong>
                        C. Appointment of Arbitrator(s)
                    </strong>
                </p>
                <p>
                    You may suggest five names of Arbitrators within 15 days for appointment of arbitrator as per DIAC Rules or arrive at consensus on one common name of the arbitrator, failing which, Centre will appoint the Arbitrator on its own as per the rules. The panel of Arbitrators maintained by the Centre is available on our <strong>website :- <?= DEPARTMENT_WEBSITE ?></strong>.
                </p>
            <?php endif; ?>

            <p>
                Any communication made to the Counsel shall be deemed to be made to the concerned party.
            </p>

            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <p style="margin-bottom: 2px;">
                <strong><?= $coordinator_name['user_display_name']  ?></strong>
            </p>
            <p style="margin-bottom: 2px;">(<?= $coordinator_name['job_title']  ?>)</p>

            <?php if ($case_data['arbitrator_status'] == 1) : ?>
                <?php if (count($arbitrators) > 0) : ?>
                    <p style="margin-top: 15px; margin-bottom: 6px;">
                        <strong>Copy to Arbitrators:</strong>
                    </p>
                    <?php foreach ($arbitrators as $key => $arbitrator) : ?>
                        <p style="margin-bottom: 5px;"><?= (count($arbitrators) > 1) ? ($key + 1) . '. ' : '' ?><?= $arbitrator['name_of_arbitrator'] ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>