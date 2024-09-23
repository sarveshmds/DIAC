<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="">

        <div class="text-center" style="margin-bottom: 40px;">
            <h4 style="margin-bottom: 3px; font-size: 18px; letter-spacing: 1px; margin-top: 0px; font-weight: bold; text-transform: uppercase;">
                <?= DEPARTMENT_NAME ?>
            </h4>
            <p style="margin-bottom: 3px; font-weight: bold; font-size: 14px; margin-top: 0px;">
                <?= DEPARTMENT_ADDRESS ?>
            </p>
            <p style="margin-bottom: 3px; font-weight: bold; font-size: 14px; margin-top: 0px;">
                (Ph: <?= DEPARTMENT_PHONE ?>, Fax-<?= DEPARTMENT_FAX ?>, Website: <?= DEPARTMENT_WEBSITE ?>)
            </p>
            <p style="margin-bottom: 3px; font-weight: bold; font-size: 14px; margin-top: 0px;">
                E-mail: <?= DEPARTMENT_COMMON_EMAIL ?>
            </p>
        </div>

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
                <?= $soc_details['created_at'] ?>
                In continuation of our email / letter dated <?= (isset($soc_details) && $soc_details['created_at']) ? formatReadableDate($soc_details['created_at']) : '______' ?> <u><b>you are reminded to file request for arbitration /statement of claim alongwith arbitrator’s fee and misc. expenses within 15 days </b></u> of receipt of this email/letter, in accordance with Rule 4 of DIAC (Arbitration Proceedings) Rules, 2023. Our detailed rules are available on our website - dhcdiac.nic.in
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