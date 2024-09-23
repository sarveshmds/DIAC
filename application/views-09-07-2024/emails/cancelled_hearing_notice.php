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
        The hearing scheduled for <?= formatReadableDate($hearing_data['date']) ?> in the case no.: <?= get_full_case_number($case_details_data) ?> has been cancelled.
    </p>
    <?php if (isset($hearing_data['cancel_hearing_remarks']) && !empty($hearing_data['cancel_hearing_remarks'])) : ?>
        <p>
            <strong>Remarks:</strong> <br>
            <?= $hearing_data['cancel_hearing_remarks'] ?>
        </p>
    <?php endif; ?>
    <p>
        This is for your information.
    </p>

    <br>
    <p>
        <strong>Regards</strong> <br>
        <strong><?= DEPARTMENT_NAME ?></strong> <br>
        <?= DEPARTMENT_ADDRESS ?> <br>
        <?= DEPARTMENT_PHONE ?>
    </p>
</body>

</html>