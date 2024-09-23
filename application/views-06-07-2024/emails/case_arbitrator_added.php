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
        This is in reference to the case no. <?= $full_case_no ?> titled as <?= $case_details_data['case_title'] ?> registered with DIAC.
    </p>
    <p>
        Please be informed that this matter stands assigned by DIAC to the below named Deputy Counsel for all further communications regarding this matter:
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

    <p>
        The Ld. Arbitral Tribunal appointed by DIAC is as under: -
    </p>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <th colspan="2" style="text-align: center;">
                <strong>DETAILS OF ARBITRATOR(S)</strong>
            </th>
            <?php foreach ($persons['arbitrators'] as $key => $arbitrator) : ?>
                <tr>
                    <td style="width: 15%;"><?= $key + 1 ?></td>
                    <td>
                        <p style="margin-bottom: 1px; margin-top: 5px;"><?= strtoupper($arbitrator['name_of_arbitrator']) ?></p>

                        <?php if (isset($arbitrator['perm_address_1']) && !empty($arbitrator['perm_address_1'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['perm_address_1'] ?>
                            </p>
                        <?php endif; ?>
                        <?php if (isset($arbitrator['perm_address_2']) && !empty($arbitrator['perm_address_2'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['perm_address_2'] ?>
                            </p>
                        <?php endif; ?>
                        <?php if (isset($arbitrator['state_name']) && !empty($arbitrator['state_name'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['state_name'] ?>
                            </p>
                        <?php endif; ?>

                        <?php if (isset($arbitrator['country_name']) && !empty($arbitrator['country_name'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['country_name'] ?>
                                <?php if (isset($arbitrator['perm_pincode']) && !empty($arbitrator['perm_pincode'])) : ?>
                                    - <?= $arbitrator['perm_pincode'] ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>

                        <?php if (isset($arbitrator['email']) && !empty($arbitrator['email'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['email'] ?>
                            </p>
                        <?php endif; ?>
                        <?php if (isset($arbitrator['contact_no']) && !empty($arbitrator['contact_no'])) : ?>
                            <p style="margin-bottom: 1px;">
                                <?= $arbitrator['contact_no'] ?>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <?php if ($case_details_data['arbitrator_status'] == 1 && count($persons['arbitrators']) > 0) :  ?>
        <div style="text-align: center; margin: 10px 0px;">
            <strong>INFORMATION FOR THE ARBITRAL TRIBUNAL</strong>
        </div>

        <div>
            <p>
                The Ld. Arbitral Tribunal is requested to send by email to DIAC a duly filled in <q>Declaration of Acceptance and Statement of Independence</q> form, which is available on the following link: <br>
                <a href="https://dhcdiac.nic.in/wp-content/uploads/2020/01/arbitrators-declaration-729163-pG6qND75.pdf" target="_BLANK">https://dhcdiac.nic.in/wp-content/uploads/2020/01/arbitrators-declaration-729163-pG6qND75.pdf</a>
            </p>
            <p>
                The Ld. Arbitral Tribunal may go through the DIAC Rules for Release of Fee of the Arbitrator on the following link: <br>
                <a href="https://dhcdiac.nic.in/wp-content/uploads/2024/05/schedule-f-358602-ydAXD5fz.pdf" target="_BLANK">https://dhcdiac.nic.in/wp-content/uploads/2024/05/schedule-f-358602-ydAXD5fz.pdf</a>
            </p>
        </div>

    <?php endif; ?>

    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <tr>
                <th colspan="2" style="text-align: center;">
                    <strong>DETAILS OF CLAIMANT(S)</strong>
                </th>
            </tr>
            <?php if (count($persons['claimants']) > 0) : ?>
                <?php foreach ($persons['claimants'] as $key => $claimant) : ?>
                    <tr>
                        <td style="width: 15%;"><?= $key + 1 ?></td>
                        <td>
                            <p style="margin-bottom: 1px; font-weight: bold;">
                                <?= $claimant['name'] ?> (Claimant <?= $claimant['count_number'] ?>)
                            </p>
                            <?php if ((isset($claimant['perm_address_1']) && $claimant['perm_address_1'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $claimant['perm_address_1'] ?>
                                </p>
                            <?php endif; ?>

                            <?php if ((isset($claimant['perm_address_2']) && $claimant['perm_address_2'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $claimant['perm_address_2'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($claimant['perm_city']) && $claimant['perm_city'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $claimant['perm_city'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($claimant['state_name']) && $claimant['state_name'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $claimant['state_name'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($claimant['country_name']) && $claimant['country_name'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $claimant['country_name'] ?>
                                    <?php if ((isset($claimant['perm_pincode']) && $claimant['perm_pincode'])) : ?>
                                        - <?= $claimant['perm_pincode']  ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if (isset($claimant['email']) && !empty($claimant['email'])) : ?>
                                <p style="margin-bottom: 1px;"><?= $claimant['email'] ?></p>
                            <?php endif; ?>
                            <?php if (isset($claimant['contact']) && !empty($claimant['contact'])) : ?>
                                <p style="margin-bottom: 1px;"><?= $claimant['contact'] ?></p>
                            <?php endif; ?>
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


    <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
        <tbody>
            <tr>
                <th colspan="2" style="text-align: center;">
                    <strong>DETAILS OF RESPONDENT(S)</strong>
                </th>
            </tr>
            <?php if (count($persons['respondants']) > 0) : ?>
                <?php foreach ($persons['respondants'] as $key => $respondant) : ?>
                    <tr>
                        <td style="width: 15%;"><?= $key + 1 ?></td>
                        <td>
                            <p style="margin-bottom: 1px; font-weight: bold;">
                                <?= $respondant['name'] ?> (Respondent <?= $respondant['count_number'] ?>)
                            </p>
                            <?php if ((isset($respondant['perm_address_1']) && $respondant['perm_address_1'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $respondant['perm_address_1'] ?>
                                </p>
                            <?php endif; ?>

                            <?php if ((isset($respondant['perm_address_2']) && $respondant['perm_address_2'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $respondant['perm_address_2'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($respondant['perm_city']) && $respondant['perm_city'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $respondant['perm_city'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($respondant['state_name']) && $respondant['state_name'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $respondant['state_name'] ?>
                                </p>
                            <?php endif; ?>
                            <?php if ((isset($respondant['country_name']) && $respondant['country_name'])) : ?>
                                <p style="margin-bottom: 1px;">
                                    <?= $respondant['country_name'] ?>
                                    <?php if ((isset($respondant['perm_pincode']) && $respondant['perm_pincode'])) : ?>
                                        - <?= $respondant['perm_pincode']  ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if (isset($respondant['email']) && !empty($respondant['email'])) : ?>
                                <p style="margin-bottom: 1px;"><?= $respondant['email'] ?></p>
                            <?php endif; ?>
                            <?php if (isset($respondant['contact']) && !empty($respondant['contact'])) : ?>
                                <p style="margin-bottom: 1px;"><?= $respondant['contact'] ?></p>
                            <?php endif; ?>
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

    <!-- Claimant Counsel -->
    <?php if (count($persons['case_claimants_counsels']) > 0) : ?>
        <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
            <tbody>
                <tr>
                    <th colspan="2" style="text-align: center;">
                        <strong>DETAILS OF COUNSEL FOR THE CLAIMANT(S)</strong>
                    </th>
                </tr>

                <?php foreach ($persons['case_claimants_counsels'] as $key => $counsel) : ?>
                    <tr>
                        <td style="width: 15%;"><?= $key + 1 ?></td>
                        <td>
                            <p style="margin-bottom: 0px;"><?= $counsel['name'] ?></p>
                            <?php if (isset($counsel['email']) && !empty($counsel['email'])) : ?>
                                <p style="margin-bottom: 0px;"><?= $counsel['email'] ?></p>
                            <?php endif; ?>
                            <?php if (isset($counsel['phone_number']) && !empty($counsel['phone_number'])) : ?>
                                <p style="margin-bottom: 0px;"><?= $counsel['phone_number'] ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Respondent Counsel -->
    <?php if (count($persons['case_respondants_counsels']) > 0) : ?>
        <table cellspacing="0" cellpadding="0" class="table table-bordered mb-10">
            <tbody>
                <tr>
                    <th colspan="2" style="text-align: center;">
                        <strong>DETAILS OF COUNSEL FOR THE RESPONDENT(S)</strong>
                    </th>
                </tr>

                <?php foreach ($persons['case_respondants_counsels'] as $key => $counsel) : ?>
                    <tr>
                        <td style="width: 15%;"><?= $key + 1 ?></td>
                        <td>
                            <p style="margin-bottom: 0px;"><?= $counsel['name'] ?></p>
                            <?php if (isset($counsel['email']) && !empty($counsel['email'])) : ?>
                                <p style="margin-bottom: 0px;"><?= $counsel['email'] ?></p>
                            <?php endif; ?>
                            <?php if (isset($counsel['phone_number']) && !empty($counsel['phone_number'])) : ?>
                                <p style="margin-bottom: 0px;"><?= $counsel['phone_number'] ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div>
        <p>
            DIAC set of Rules may be accessed at <a href="https://dhcdiac.nic.in" target="_BLANK">https://dhcdiac.nic.in</a>
        </p>
        <p>
            <strong>In case of any concerns, please write to us at - <u>admin@diac.ind.in</u>.</strong>
        </p>
    </div>

    <br>
    <p>
        <strong>Regards</strong> <br>
        <strong><?= DEPARTMENT_NAME ?></strong> <br>
        <?= DEPARTMENT_ADDRESS ?> <br>
        <?= DEPARTMENT_PHONE ?>
    </p>
</body>

</html>