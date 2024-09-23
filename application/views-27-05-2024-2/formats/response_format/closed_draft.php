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
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= strtoupper($counsel['name']) ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= strtoupper($counsel['perm_address_1']) ?>
                                </p>
                                <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= strtoupper($counsel['perm_address_2']) ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= strtoupper($counsel['email']) ?></p>
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
                <strong>Vs.</strong>
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
                                <p style="margin-bottom: 1px; font-weight: bold;"><?= strtoupper($counsel['name']) ?></p>
                                <p style="margin-bottom: 1px;">
                                    <?= strtoupper($counsel['perm_address_1']) ?>
                                </p>
                                <?php if ((isset($counsel['perm_address_2']) && $counsel['perm_address_2'])) : ?>
                                    <p style="margin-bottom: 1px;">
                                        <?= strtoupper($counsel['perm_address_2']) ?>
                                    </p>
                                <?php endif; ?>
                                <p style="margin-bottom: 1px;"><?= strtoupper($counsel['email']) ?></p>
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
                    <strong><?= $case_data['reffered_by_court_case_type'] ?>/<?= $case_data['arbitration_petition'] ?>/<?= $case_data['reffered_by_court_case_year'] ?></strong>
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
                In continuation of our final reminder dated <?= (isset($final_reminder_details) && $final_reminder_details['created_at']) ? formatReadableDate($final_reminder_details['created_at']) : '______' ?> , please be informed that you have failed to file request for arbitration/Statement of Claim, despite reminder.
            </p>
            <p>
                <strong>In view of the non filing of statement of claim despite reminder, the proceedings in the matter in the Centre are <u>administratively CLOSED</u>.</strong>
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