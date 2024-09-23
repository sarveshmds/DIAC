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
                Claimant / Respondent has suggested five names from DIAC Panel of Arbitrator’s for appointment of a Sole Arbitrator in the above mentioned matter. The names are as under:-
            </p>
            <ol>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ol>

            <p>
                <u>
                    You are required to either concur upon a common name suggested by the claimant / respondent or suggest any five names of your choice for appointment of a Sole Arbitrator. In case of non-consensus, the Centre shall proceed for appointment of arbitrator on its own, as per our Rules.
                </u>
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