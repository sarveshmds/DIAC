<div class="row">
    <div class="form-group col-md-12 col-xs-12 ">
        <div class="box mb-3">
            <div class="box-header">
                <!-- <h3 class="box-title">Claimant Details</h3> -->
                <!-- <div class="box-tools pull-right">
                    <button type="button" id="add-claimant" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> Add More
                    </button>
                </div> -->
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20%">Claimant Name</th>
                            <th colspan="2" class="text-center" width="30%">Address</th>
                            <th width="20%">E-mail</th>
                            <th width="20%">Mobile No.</th>

                        </tr>
                    </thead>
                    <tbody class="table-class-claimant">
                        <?php if (isset($claimants_list)) : ?>
                            <?php foreach ($claimants_list as $cl) : ?>
                                <tr>
                                    <td><?= $cl['name'] ?></td>
                                    <td colspan="2">
                                        <p class="mb-1">
                                            <?= $cl['perm_address_1'] . ', ' . $cl['perm_address_2'] ?>
                                        </p>
                                        <p class="mb-1">
                                            <?= $cl['state_name'] ?>
                                        </p>
                                        <p class="mb-1">
                                            <?= $cl['country_name'] . ' - ' . $cl['perm_pincode'] ?>
                                        </p>
                                    </td>
                                    <td><?= $cl['email'] ?></td>
                                    <td><?= $cl['contact'] ?></td>

                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box mb-3">
            <div class="box-header">
                <!-- <h3 class="box-title">Respondant Details</h3> -->
                <!-- <div class="box-tools pull-right">
                    <button type="button" id="add-respondent" class="btn btn-custom btn-sm">
                    <i class="fa fa-plus"></i> Add More
                    </button>
                </div> -->
            </div>
            <div class="box-body">
                <div class="table-responsive p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="20%">Respondent Name</th>
                                <th colspan="2" class="text-center" width="30%">Address</th>
                                <th width="20%">E-mail</th>
                                <th width="20%">Mobile No.</th>
                                <!-- <th class="text-center" width="10%"></th> -->
                            </tr>
                        </thead>
                        <tbody class="table-class-respondant">
                            <?php if (isset($respondants_list)) : ?>
                                <?php foreach ($respondants_list as $res) : ?>
                                    <tr>
                                        <td><?= $res['name'] ?></td>
                                        <td colspan="2">
                                            <p class="mb-1">
                                                <?= $res['perm_address_1'] . ', ' . $res['perm_address_2'] ?>
                                            </p>
                                            <p class="mb-1">
                                                <?= $res['state_name'] ?>
                                            </p>
                                            <p class="mb-1">
                                                <?= $res['country_name'] . ' - ' . $res['perm_pincode'] ?>
                                            </p>
                                        </td>
                                        <td><?= $res['email'] ?></td>
                                        <td><?= $res['contact'] ?></td>
                                        <!-- <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm">
                                    <i class="fa fa-times"></i>
                                    </button>
                                </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12 col-xs-12 required">
        <label class="control-label">Diary Number:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['diary_number_prefix'])) ? $refferal_req['diary_number_prefix'] : '' ?>/<?= (isset($refferal_req['diary_number'])) ? $refferal_req['diary_number'] : '' ?>/<?= (isset($refferal_req['diary_number_year'])) ? $refferal_req['diary_number_year'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-12 col-xs-12 required">
        <label class="control-label">Case Title:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['case_title'])) ? $refferal_req['case_title'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
        <label for="" class="control-label">Mode of reference:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['reffered_by'])) ? $refferal_req['reffered_by_desc'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12">
        <label class="control-label">Date of reference:</label>

        <p class="mb-0">
            <?= (isset($refferal_req['reffered_on'])) ? formatReadableDate($refferal_req['reffered_on']) : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($refferal_req['reffered_by']) && !empty($refferal_req['reffered_by']) && $refferal_req['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
        <label for="" class="control-label">Court:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['reffered_by_court'])) ? $refferal_req['court_name'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($refferal_req['reffered_by']) && !empty($refferal_req['reffered_by']) && $refferal_req['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
        <label for="" class="control-label">Name of Judges:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['reffered_by_judge'])) ? $refferal_req['reffered_by_judge'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($refferal_req['reffered_by']) && !empty($refferal_req['reffered_by']) && $refferal_req['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
        <label for="" class="control-label">Name of Authority:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['name_of_court'])) ? $refferal_req['name_of_court'] : '' ?>
        </p>

    </div>

    <?php if (isset($refferal_req['reffered_by']) && $refferal_req['reffered_by'] == 'COURT') : ?>
        <div class="form-group col-md-3 col-sm-6 col-xs-12">
            <label class="control-label">Case Type:</label>
            <p class="mb-0">
                <?= (isset($refferal_req['reffered_by_court_case_type'])) ? $refferal_req['reffered_by_court_case_type'] : '' ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="form-group col-md-3 col-sm-6 col-xs-12">
        <label class="control-label">Reference No.:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['arbitration_petition'])) ? $refferal_req['arbitration_petition'] : '' ?>
        </p>
    </div>

    <?php if (isset($refferal_req['reffered_by']) && $refferal_req['reffered_by'] == 'COURT') : ?>
        <div class="form-group col-md-3 col-sm-6 col-xs-12">
            <label class="control-label">Year:</label>
            <p class="mb-0">
                <?= (isset($refferal_req['reffered_by_court_case_year'])) ? $refferal_req['reffered_by_court_case_year'] : '' ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="form-group col-md-3 col-sm-6 col-xs-12">
        <label class="control-label">Ref. Received On:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['recieved_on'])) ? formatReadableDate($refferal_req['recieved_on']) : '' ?>
        </p>

    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12">
        <label class="control-label">Date of registration:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['registered_on'])) ? formatReadableDate($refferal_req['registered_on']) : '' ?>
        </p>

    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
        <label for="" class="control-label">Nature of arbitration:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['type_of_arbitration'])) ? $refferal_req['type_of_arbitration_desc'] : '' ?>
        </p>
    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
        <label for="" class="control-label">Type of Arbitration:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['di_type_of_arbitration'])) ? $refferal_req['di_type_of_arbitration_desc'] : '' ?>
        </p>

    </div>

    <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
        <label for="" class="control-label">Arbitrator Status:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['arbitrator_status'])) ? $refferal_req['arbitrator_status_desc'] : '' ?>
        </p>
    </div>

    <?php if (isset($refferal_req['arbitrator_status']) && $refferal_req['arbitrator_status'] == 1) : ?>
        <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
            <label for="" class="control-label">Arbitral Tribunal Strength:</label>
            <p class="mb-0">
                <?php if (isset($refferal_req['arbitral_tribunal_strength'])) : ?>
                    <?php if ($refferal_req['arbitral_tribunal_strength'] == 1) : ?>
                        Sole
                    <?php else : ?>
                        $refferal_req['arbitral_tribunal_strength']
                    <?php endif; ?>
                <?php else : ?>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="form-group col-xs-12">
        <label class="control-label">Uploaded Supporting Documents:</label>
        <?php if (isset($case_supporting_docs) && count($case_supporting_docs) > 0) : ?>
            <div class="">
                <?php foreach ($case_supporting_docs as $doc) : ?>
                    <a href="<?= base_url(VIEW_CASE_FILE_UPLOADS_FOLDER . $doc['file_name']) ?>" class="btn btn-primary btn-sm" target="_BLANK">
                        <i class="fa fa-eye"></i> View</a>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="mb-0">
                No Document
            </p>
        <?php endif; ?>
    </div>

    <div class="form-group col-md-6 col-sm-6 col-xs-12 ">
        <label class="control-label">Remarks:</label>
        <p class="mb-0">
            <?= (isset($refferal_req['remarks'])) ? $refferal_req['remarks'] : '' ?>"
        </p>
    </div>

</div>