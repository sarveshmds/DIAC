<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="">

        <?php require_once(APPPATH . 'views/formats/response_format/common_header.php') ?>

        <div style="text-align: justify;">
            <p>To,</p>

            <?php require_once(APPPATH . 'views/formats/response_format/common_to_address.php') ?>

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
                Please be informed that <u>as per assessment (provisional) of Claim</u>, total Arbitrator’s fee comes to Rs. ___________ /- and each party’s share comes to Rs. ___________ /- towards Arbitrator’s fee & Rs. <b>10,000/-</b> for Misc. expenses (Assessment sheet dated ___________ is attached for your reference) .
            </p>

            <p style="text-align: center;">
                As per records, the status of fee deposited by the parties, as on date, is as follows:
            </p>

            <table class="table" style="width:100%;" cellspacing="0">
                <thead style="background-color: #f1f1f1; color: black;">
                    <tr>
                        <th width="25%">Party</th>
                        <th width="25%">Fee Payable</th>
                        <th width="25%">Fee Deposited</th>
                        <th width="25%">Deficient Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">Claimant</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                    </tr>
                    <tr>
                        <td class="text-center">Respondent</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                        <td class="text-center">Rs.</td>
                    </tr>
                </tbody>
            </table>

            <p>
                <b>
                    <u>
                        You are, therefore, required to deposit Rs. ___________ /- towards your share of Arbitrator’s fee & misl. expenses, at the earliest.
                    </u>
                </b>
            </p>

            <p>
                <b>Note:- </b> Payments can be made in favour of the <u>Delhi International Arbitration Centre</u>, through RTGS / NEFT,Account No: 15530210000663, IFSC Code: UCBA0001553 [UCO Bank], MICR Code: 110028026, Branch: Delhi High Court, Sher Shah Road, New Delhi-110003 with intimation to DIAC. <q>DIAC deducts TDS while disbursing payment(s) to the Ld. Arbitrator(s), so parties are not to deduct any TDS while making payment (s) of Arbitral Fees/ Administrative Costs to DIAC.</q>
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
                    <div style="margin-top: 15px; margin-bottom: 6px;">
                        <p style="margin-bottom: 5px; margin-top: 5px;">
                            <strong>Copy to Arbitrators:</strong>
                        </p>
                        <ol style="padding-left: 15px;">
                            <?php foreach ($arbitrators as $key => $arbitrator) : ?>
                                <li>
                                    <p style="margin-bottom: 2px; margin-top: 5px;"><?= $arbitrator['name_of_arbitrator'] ?></p>
                                    <?php if (isset($arbitrator['email']) && !empty($arbitrator['email'])) : ?>
                                        <p style="margin-bottom: 2px;">
                                            <?= $arbitrator['email'] ?>
                                        </p>
                                    <?php endif; ?>

                                </li>
                            <?php endforeach; ?>
                        </ol>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>