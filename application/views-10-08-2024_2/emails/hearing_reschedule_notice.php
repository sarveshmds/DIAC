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
    <p>Respected Sir/Ma'am,</p>
    <p>
        The hearing is re-scheduled for the case no.: <?= get_full_case_number($case_details_data) ?> (<?= $case_details_data['case_title'] ?>)
    </p>
    <p>
        <u><strong>BELOW ARE THE DETAILS:</strong></u>
    </p>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tr>
            <th WIDTH="40%">
                DATE:
            </th>
            <td>
                <?= formatReadableDate($hearing_data['date']) ?>
            </td>
        </tr>
        <tr>
            <th>
                TIME:
            </th>
            <td>
                <?= $hearing_data['time_from'] ?> - <?= $hearing_data['time_to'] ?>
            </td>
        </tr>
        <?php if (in_array($hearing_data['mode_of_hearing'], ['PHYSICAL', 'HYBRID'])) : ?>
            <tr>
                <th>
                    ROOM No.:
                </th>
                <td>
                    <?= $room_info['room_name'] ?> - <?= $room_info['room_no'] ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th WIDTH="40%">
                MODE OF HEARING:
            </th>
            <td>
                <?= $hearing_data['mode_of_hearing'] ?>
            </td>
        </tr>
        <?php if (in_array($hearing_data['mode_of_hearing'], ['HYBRID', 'ONLINE'])) : ?>
            <tr>
                <th WIDTH="40%">
                    HEARING MEETING LINK:
                </th>
                <td>
                    <?= $hearing_data['hearing_meeting_link'] ?>
                </td>
            </tr>
        <?php endif; ?>

        <?php if (isset($hearing_data['remarks']) && !empty($hearing_data['remarks'])) : ?>
            <tr>
                <th WIDTH="40%">
                    REMARKS:
                </th>
                <td>
                    <?= $hearing_data['remarks'] ?>
                </td>
            </tr>
        <?php endif; ?>

    </table>

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

    <br>
    <p>
        <strong>Regards</strong> <br>
        <strong><?= DEPARTMENT_NAME ?></strong> <br>
        <?= DEPARTMENT_ADDRESS ?> <br>
        <?= DEPARTMENT_PHONE ?>
    </p>
</body>

</html>