<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>

<body>
    <p>Greetings from DIAC!!,</p>
    <p>
        This is to inform that a case with following details has been registered with DIAC:
    </p>
    <p style="margin-bottom: 5px;">
        <strong>Case Title:</strong> <?= $full_case_no ?>
    </p>
    <p style="margin-bottom: 5px;">
        <strong>DIAC Registration No.:</strong> <?= $full_case_no ?>
    </p>

    <p style="margin-bottom: 5px;">
        <strong>Arbitrators:</strong>
    </p>
    <ul>
        <?php if (count($persons['arbitrators']) > 0) : ?>
            <?php foreach ($persons['arbitrators'] as $arbitrator) : ?>
                <li>
                    <?= $arbitrator['name_of_arbitrator'] ?>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>
                Arbitrator is not appointed in the case, please contact DIAC for any query
            </li>
        <?php endif; ?>
    </ul>

    <p style="margin-bottom: 5px;">
        <strong>Claimants:</strong>
    </p>
    <ul>
        <?php if (count($persons['claimants']) > 0) : ?>
            <?php foreach ($persons['claimants'] as $claimant) : ?>
                <li>
                    <?= $claimant['name'] ?> (Claimant <?= $claimant['count_number'] ?>)
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>
                No claimants data available, please contact DIAC for any query.
            </li>
        <?php endif; ?>
    </ul>

    <p style="margin-bottom: 5px;">
        <strong>Respondents:</strong>
    </p>
    <ul>
        <?php if (count($persons['respondants']) > 0) : ?>
            <?php foreach ($persons['respondants'] as $respondant) : ?>
                <li>
                    <?= $respondant['name'] ?> (Respondent <?= $respondant['count_number'] ?>)
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>
                No respondents data available, please contact DIAC for any query.
            </li>
        <?php endif; ?>
    </ul>

    <p>
        Please be informed that the following Deputy Counsel has been assigned by DIAC to attend to your matter.
    </p>

    <?php if (isset($persons['deputy_counsel']) && $persons['deputy_counsel']) : ?>
        <p style="margin-bottom: 4px;">
            <strong>Name:</strong> <?= (isset($persons['deputy_counsel']['user_display_name'])) ? $persons['deputy_counsel']['user_display_name'] : '' ?>
        </p>
        <p style="margin-bottom: 4px;">
            <strong>Mob.:</strong> <?= (isset($persons['deputy_counsel']['phone_number'])) ? $persons['deputy_counsel']['phone_number'] : '' ?>
        </p>
        <p style="margin-bottom: 4px;">
            <strong>Dedicated email ID:</strong> <?= (isset($persons['deputy_counsel']['email'])) ? $persons['deputy_counsel']['email'] : '' ?>
        </p>
    <?php else : ?>
        <p style="margin-bottom: 0px;">No deputy counsel is appointed in the case, please contact DIAC for any query</p>
    <?php endif; ?>

    <p>
        All further communcation may be made by you on above dedicated email with the Deputy Counsel assigned.
    </p>

    <p>
        <strong>Case Manager:</strong>
    </p>
    <ul>
        <?php if (isset($persons['case_manager']) && $persons['case_manager']) : ?>
            <li>
                <?= (isset($persons['case_manager']['user_display_name'])) ? $persons['case_manager']['user_display_name'] : '' ?>
            </li>
        <?php else : ?>
            <li>
                No case manager is appointed in the case, please contact DIAC for any query
            </li>
        <?php endif; ?>
    </ul>

    <p>
        <strong>Additional Coordinator:</strong>
    </p>
    <ul>
        <?php if (isset($persons['additional_coordinator']) && $persons['additional_coordinator']) : ?>
            <li>
                <?= (isset($persons['additional_coordinator']['user_display_name'])) ? $persons['additional_coordinator']['user_display_name'] : '' ?>
            </li>
        <?php else : ?>
            <li>
                No additional coordinator is appointed in the case, please contact DIAC for any query
            </li>
        <?php endif; ?>
    </ul>

    <p>
        <strong>Thanks & Regards</strong> <br>
        <strong><?= DEPARTMENT_NAME ?></strong> <br>
        <?= DEPARTMENT_ADDRESS ?> <br>
        <?= DEPARTMENT_PHONE ?>
    </p>
</body>

</html>