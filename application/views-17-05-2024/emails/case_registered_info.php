<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>

    <style>
        p {
            margin-top: 0px;
        }

        .table {
            width: 100%;
        }

        .table tr td,
        .table tr th {
            border: 1px solid lightgrey;
            padding: 8px 12px;
        }

        .table tr th {
            text-align: left;
            background-color: #f4f4f4;
        }

        .table-bordered {
            border: 1px solid lightgrey;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <p style="margin-bottom: 5px;">Greetings from DIAC..!!</p>
    <p style="margin-bottom: 5px;">
        This is to inform that a case with following details has been registered with DIAC:
    </p>
    <p style="margin-bottom: 5px;">
        <strong>CASE TITLE:</strong> <?= $case_details_data['case_title'] ?>
    </p>
    <p style="margin-bottom: 5px;">
        <strong>DIAC REGISTRATION No.:</strong> <?= $full_case_no ?>
    </p>

    <p style="margin-bottom: 5px;">
        <strong>ARBITRATORS:</strong>
    </p>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <?php if (count($persons['arbitrators']) > 0) : ?>
                <tr>
                    <th width="12%">Sl. No</th>
                    <th>
                        NAME OF ARBITRATOR
                    </th>
                </tr>
                <?php foreach ($persons['arbitrators'] as $key => $arbitrator) : ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <?= strtoupper($arbitrator['name_of_arbitrator']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td>Yet to be appointed</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <p style="margin-bottom: 5px;">
        <strong>CLAIMANTS:</strong>
    </p>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <?php if (count($persons['claimants']) > 0) : ?>
                <tr>
                    <th width="12%">Sl. No</th>
                    <th>
                        NAME OF CLAIMANT
                    </th>
                </tr>
                <?php foreach ($persons['claimants'] as $key => $claimant) : ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <?= $claimant['name'] ?> (Claimant <?= $claimant['count_number'] ?>)
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td>No claimants data available, please contact DIAC for any query.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p style="margin-bottom: 5px;">
        <strong>RESPONDENTS:</strong>
    </p>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <?php if (count($persons['respondants']) > 0) : ?>
                <tr>
                    <th width="12%">Sl. No</th>
                    <th>
                        NAME OF RESPONDENT
                    </th>
                </tr>
                <?php foreach ($persons['respondants'] as $key => $respondant) : ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                            <?= $respondant['name'] ?> (Respondent <?= $respondant['count_number'] ?>)
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td>No respondents data available, please contact DIAC for any query.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p>
        Please be informed that the following Deputy Counsel has been assigned by DIAC to attend to your matter:
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
        <p style="margin-bottom: 0px;">No Deputy Counsel is appointed in the case, please contact DIAC for any query</p>
    <?php endif; ?>

    <br>

    <h3>
        Further communication regarding the registered matter be made with the Deputy Counsel assigned on the dedicated email address. Concerns may be raised with the DIAC at admin@diac.ind.in.
    </h3>

    <br>

    <p>
        <strong>Regards</strong> <br>
        <strong><?= DEPARTMENT_NAME ?></strong> <br>
        <?= DEPARTMENT_ADDRESS ?> <br>
        <?= DEPARTMENT_PHONE ?>
    </p>
</body>

</html>