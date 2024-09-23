<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>

    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body p-0">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true">
                                General Information
                            </a>
                        </li>

                        <li class="">
                            <a href="#tab_4" class="rc_nav_link" data-toggle="tab" aria-expanded="false" id="btn_arbitrator_nav_tab" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == '2') ? 'style="display: none;"' : '' ?>>
                                Arbitrators
                            </a>
                        </li>

                        <li class="">
                            <a href="#tab_5" class="rc_nav_link" data-toggle="tab" aria-expanded="false" id="btn_counsel_nav_tab">
                                Counsels
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- General Information Tab -->
                        <div class="tab-pane active" id="tab_1">
                            <form class="wfst" id="refferal_req_form">

                                <input type="hidden" id="refferal_req_op_type" name="op_type" value="<?= ((isset($case_data['m_code']))) ? 'EDIT_MISCELLANEOUS' : 'ADD_MISCELLANEOUS' ?>">

                                <?php if (isset($case_data['m_code'])) : ?>
                                    <input type="hidden" id="hidden_refferal_req_code" name="hidden_refferal_req_code" value="<?= $case_data['m_code'] ?>">
                                    <input type="hidden" id="hidden_refferal_req_case_code" name="hidden_refferal_req_case_code" value="<?= $case_data['slug'] ?>">
                                <?php endif; ?>

                                <input type="hidden" name="csrf_refferal_req_form_token" value="<?= generateToken('refferal_req_form'); ?>">

                                <input type="hidden" name="csrf_cl_trans_token" id="csrf_cl_trans_token" value="<?= generateToken('dataTableClaimantList'); ?>">
                                <input type="hidden" name="csrf_res_trans_token" id="csrf_res_trans_token" value="<?= generateToken('dataTableRespondantList'); ?>">

                                <div class="box mb-3">
                                    <div class="box-header">
                                        <h3 class="box-title">Claimant Details</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" id="add-claimant" class="btn btn-custom btn-sm">
                                                <i class="fa fa-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-bordered" id="claimants-table">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Claimant Name</th>
                                                    <th colspan="2" class="text-center" width="38%">Address</th>
                                                    <th width="20%">E-mail</th>
                                                    <th width="15%">Mobile No.</th>
                                                    <th class="text-center" width="7%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-class-claimant">
                                                <?php if (isset($claimants_list)) : ?>
                                                    <?php foreach ($claimants_list as $cl) : ?>
                                                        <tr>
                                                            <td><?= $cl['name'] . ' - ' . $cl['count_number'] ?></td>
                                                            <td colspan="2">
                                                                <p class="mb-1">
                                                                    <?= $cl['perm_address_1'] . ', ' . $cl['perm_address_2'] ?>
                                                                </p>
                                                                <p class="mb-1">
                                                                    <?= $cl['perm_city'] ?>
                                                                </p>
                                                                <p class="mb-1">
                                                                    <?= strtoupper($cl['state_name']) ?>
                                                                </p>
                                                                <p class="mb-1">
                                                                    <?= strtoupper($cl['country_name']) . ' - ' . $cl['perm_pincode'] ?>
                                                                </p>
                                                            </td>
                                                            <td><?= $cl['email'] ?></td>
                                                            <td><?= $cl['contact'] ?></td>
                                                            <td class="text-center">
                                                                <div class="mb-1">
                                                                    <button type="button" data-code="<?= $cl['code'] ?>" class="btn btn-warning btn-sm btn_edit_claimant_respondent" data-type="claimant">
                                                                        <i class="fa fa-times"></i> Edit
                                                                    </button>
                                                                </div>

                                                                <div>
                                                                    <button type="button" data-code="<?= $cl['code'] ?>" class="btn btn-danger btn-sm btn_delete_claimant_respondent">
                                                                        <i class="fa fa-times"></i> Remove
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="box mb-3">
                                    <div class="box-header">
                                        <h3 class="box-title">Respondent Details</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" id="add-respondent" class="btn btn-custom btn-sm">
                                                <i class="fa fa-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive p-0">
                                            <table class="table table-bordered" id="respondents-table">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Respondent Name</th>
                                                        <th colspan="2" class="text-center" width="38%">Address</th>
                                                        <th width="20%">E-mail</th>
                                                        <th width="15%">Mobile No.</th>
                                                        <th class="text-center" width="7%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-class-respondant">
                                                    <?php if (isset($respondants_list)) : ?>
                                                        <?php foreach ($respondants_list as $res) : ?>
                                                            <tr>
                                                                <td><?= $res['name'] . ' - ' . $res['count_number'] ?></td>
                                                                <td colspan="2">
                                                                    <p class="mb-1">
                                                                        <?= $res['perm_address_1'] . ', ' . $res['perm_address_2'] ?>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <?= $res['perm_city'] ?>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <?= strtoupper($res['country_name']) ?>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <?= strtoupper($res['state_name']) . ' - ' . $res['perm_pincode'] ?>
                                                                    </p>
                                                                </td>
                                                                <td><?= $res['email'] ?></td>
                                                                <td><?= $res['contact'] ?></td>
                                                                <td class="text-center">
                                                                    <div class="mb-1">
                                                                        <button type="button" data-code="<?= $res['code'] ?>" class="btn btn-warning btn-sm btn_edit_claimant_respondent" data-type="respondant">
                                                                            <i class="fa fa-times"></i> Edit
                                                                        </button>
                                                                    </div>

                                                                    <div>
                                                                        <button type="button" data-code="<?= $res['code'] ?>" class="btn btn-danger btn-sm btn_delete_claimant_respondent" data-type="respondant">
                                                                            <i class="fa fa-times"></i> Remove
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-md-12 col-xs-12 required">
                                        <label class="control-label">Case Title:</label>
                                        <input type="text" class="form-control uppercase" id="cd_case_title" name="cd_case_title" autocomplete="off" value="<?= (isset($case_data['case_title'])) ? $case_data['case_title'] : '' ?>" />
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label for="" class="control-label">Mode of reference:</label>
                                        <select name="cd_reffered_by" id="cd_reffered_by" class="form-control">

                                            <option value="">Select Option</option>
                                            <?php foreach ($reffered_by as $rb) : ?>
                                                <option value="<?= $rb['gen_code'] ?>" <?= (isset($case_data['reffered_by']) && $rb['gen_code'] == $case_data['reffered_by']) ? 'selected' : '' ?>><?= $rb['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Date of reference:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control custom-all-previous-dates" id="cd_reffered_on" name="cd_reffered_on" autocomplete="off" readonly="true" value="<?= (isset($case_data['reffered_on'])) ? formatDatepickerDate($case_data['reffered_on']) : date('d-m-Y'); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Court:</label>

                                        <select name="cd_reffered_by_court" id="cd_reffered_by_court" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php foreach ($courts_list as $court) : ?>
                                                <option value="<?= $court['code'] ?>" <?= (isset($case_data['reffered_by_court']) && $court['code'] == $case_data['reffered_by_court']) ? 'selected' : '' ?>><?= $court['court_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Name of Judges:</label>
                                        <input type="text" name="cd_reffered_by_judge" id="cd_reffered_by_judge" class="form-control uppercase" value="<?= (isset($case_data['reffered_by_judge'])) ? $case_data['reffered_by_judge'] : '' ?>">
                                        <small class="text-danger">
                                            Enter comma (,) separated names
                                        </small>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Name of Authority:</label>
                                        <input type="text" name="cd_name_of_court" id="cd_name_of_court" class="form-control uppercase" value="<?= (isset($case_data['name_of_court'])) ? $case_data['name_of_court'] : ''; ?>" maxlength="250">
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Case Type:</label>

                                        <select name="cd_reffered_by_court_case_type" id="cd_reffered_by_court_case_type" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php foreach ($case_types as $ct) : ?>
                                                <option value="<?= $ct['type_code'] ?>" <?= (isset($case_data['reffered_by_court_case_type']) && $ct['type_code'] == $case_data['reffered_by_court_case_type']) ? 'selected' : '' ?>><?= $ct['type_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Reference No.:</label>
                                        <input type="text" class="form-control" id="cd_case_arb_pet" name="cd_case_arb_pet" autocomplete="off" maxlength="100" value="<?= (isset($case_data['arbitration_petition'])) ? $case_data['arbitration_petition'] : '' ?>" />
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Year:</label>

                                        <?php
                                        $year = date('Y');
                                        $endYear = 1920;
                                        ?>
                                        <select name="cd_reffered_by_court_case_year" id="cd_reffered_by_court_case_year" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php while ($year >= $endYear) : ?>
                                                <option value="<?= $year ?>" <?= (isset($case_data['reffered_by_court_case_year']) && $year == $case_data['reffered_by_court_case_year']) ? 'selected' : '' ?>><?= $year-- ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Ref. Received On:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control custom-all-date" id="cd_recieved_on" name="cd_recieved_on" autocomplete="off" readonly="true" value="<?= (isset($case_data['recieved_on'])) ? formatDatepickerDate($case_data['recieved_on']) : date('d-m-Y'); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Date of registration:</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control custom-all-previous-dates" id="cd_registered_on" name="cd_registered_on" autocomplete="off" readonly="true" value="<?= (isset($case_data['registered_on'])) ? formatDatepickerDate($case_data['registered_on']) : date('d-m-Y'); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label for="" class="control-label">Nature of arbitration:</label>
                                        <select name="cd_toa" id="cd_toa" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php foreach ($type_of_arbitration as $toa) : ?>
                                                <option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['type_of_arbitration']) && $toa['gen_code'] == $case_data['type_of_arbitration']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
                                        <label for="" class="control-label">Type of Arbitration:</label>
                                        <select name="cd_di_toa" id="cd_di_toa" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php foreach ($di_type_of_arbitration as $toa) : ?>
                                                <option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['di_type_of_arbitration']) && $toa['gen_code'] == $case_data['di_type_of_arbitration']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
                                        <label for="" class="control-label">Arbitrator Status:</label>
                                        <select name="cd_arbitrator_status" id="cd_arbitrator_status" class="form-control">
                                            <option value="">Select Option</option>
                                            <?php foreach ($arbitrator_status as $toa) : ?>
                                                <option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['arbitrator_status']) && $toa['gen_code'] == $case_data['arbitrator_status']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitral_tribunal_stength_col required" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == 1) ? 'selected' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Arbitral Tribunal Strength:</label>
                                        <select name="cd_arbitral_tribunal_strength" id="cd_arbitral_tribunal_strength" class="form-control">
                                            <option value="">Select Option</option>
                                            <option value="1" <?= (isset($case_data['arbitral_tribunal_strength']) && $case_data['arbitral_tribunal_strength'] == 1) ? 'selected' : '' ?>>Sole</option>
                                            <option value="3" <?= (isset($case_data['arbitral_tribunal_strength']) && $case_data['arbitral_tribunal_strength'] == 3) ? 'selected' : '' ?>>3</option>
                                            <option value="5" <?= (isset($case_data['arbitral_tribunal_strength']) && $case_data['arbitral_tribunal_strength'] == 5) ? 'selected' : '' ?>>5</option>
                                        </select>
                                    </div>


                                    <div class="col-xs-12"></div>

                                    <div class="form-group col-md-6 col-xs-12">
                                        <label class="control-label">Supporting Documents:</label>
                                        <input type="file" class="form-control documents-input" id="refferal_req_documents" name="refferal_req_documents[]" autocomplete="off" multiple accept=".pdf, .docx, .doc" value="" multiple />
                                        <input type="hidden" name="hidden_refferal_req_documents" class="multiple_refferal_req_initial_preview" value='<?= ((isset($miscellaneous['document']))) ? $miscellaneous['document'] : '' ?>'>

                                        <?php if (isset($case_supporting_docs) && count($case_supporting_docs) > 0) : ?>
                                            <div class="box box-body mt-10">
                                                <p class="mb-0">
                                                    <label for="">
                                                        Uploaded Supporting Documents
                                                    </label>
                                                </p>
                                                <?php foreach ($case_supporting_docs as $doc) : ?>
                                                    <a href="<?= base_url(VIEW_CASE_FILE_UPLOADS_FOLDER . $doc['file_name']) ?>" class="btn btn-primary btn-sm" target="_BLANK">
                                                        <i class="fa fa-eye"></i> View</a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-6 col-xs-12 ">
                                        <label class="control-label">Remarks (Optional):</label>
                                        <textarea class="form-control" id="refferal_req_message" name="refferal_req_message" autocomplete="off" rows="4"><?= ((isset($case_data['remarks']))) ? $case_data['remarks'] : '' ?></textarea>
                                    </div>

                                    <!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Submitted By (Name):</label>
                                        <input type="text" class="form-control" id="refferal_req_name" name="refferal_req_name" autocomplete="off" maxlength="200" value="<?= ((isset($miscellaneous['name']))) ? $miscellaneous['name'] : '' ?>" />
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Person's Phone Number:</label>
                                        <input type="text" class="form-control" id="refferal_req_phone_number" name="refferal_req_phone_number" autocomplete="off" maxlength="10" value="<?= ((isset($miscellaneous['phone_number']))) ? $miscellaneous['phone_number'] : '' ?>" />
                                    </div> -->

                                </div>

                                <?php if (isset($case_data['is_submitted']) && $case_data['is_submitted'] == 1) : ?>
                                    <div class="mt-25">
                                        <div class="alert alert-info">
                                            Case is already final submitted.
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row text-center mt-25">
                                        <div class="col-xs-12">
                                            <button type="submit" class="btn btn-custom refferal_req_btn_submit" id="btn_refferal_request_save"><i class='fa fa-paper-plane'></i> Save Details</button>
                                        </div>
                                    </div>

                                <?php endif; ?>
                            </form>
                        </div>

                        <!-- Arbitrator Details -->
                        <div class="tab-pane" id="tab_4" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == '2') ? 'style="display: none;"' : '' ?>>
                            <?php if (isset($case_data['m_code']) && $case_data['m_code'] != "") : ?>
                                <div class="table-responsive">
                                    <table id="dataTableArbTriList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                                        <input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('dataTableArbTriList'); ?>">

                                        <?php if (isset($case_no)) : ?>
                                            <input type="hidden" id="at_case_no" value="<?php echo $case_no; ?>">
                                        <?php endif; ?>

                                        <thead>
                                            <tr>
                                                <th style="width:8%;">S. No.</th>
                                                <th>Is Empanelled</th>
                                                <th>Name of Arbitrator</th>
                                                <th>Arbitrator Type</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Category</th>
                                                <th>Appointed By</th>
                                                <th>Date Of Appointment</th>
                                                <th>Date Of Consent</th>
                                                <th>Terminated</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-custom">
                                    Please add general information first, then you will be able to fill other details
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Counsel Details -->
                        <div class="tab-pane" id="tab_5">
                            <?php if (isset($case_data['m_code']) && $case_data['m_code'] != "") : ?>
                                <div class="table-responsive">
                                    <table id="dataTableCounselListForNR" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                                        <input type="hidden" id="csrf_counsels_trans_token" value="<?php echo generateToken('dataTableCounselsList'); ?>">

                                        <?php if (isset($case_no)) : ?>
                                            <input type="hidden" id="counsels_case_no" value="<?php echo $case_no; ?>">
                                        <?php endif; ?>

                                        <thead>
                                            <tr>
                                                <th style="width:7%;">S. No.</th>
                                                <th>Name of counsels</th>
                                                <th>Enrollment No.</th>
                                                <th>Appearing for (Claimant/Respondent)</th>
                                                <th>E-mail</th>
                                                <th>Contact Number</th>
                                                <th style="width:15%;">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-custom">
                                    Please add general information first, then you will be able to fill other details
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <?php if ((isset($case_data['m_code']) && $case_data['m_code'] != "") && $case_data['is_submitted'] == 0) : ?>
                    <hr class="custom-hr">
                    <fieldset class="fieldset" style="margin: 10px 10px 10px 10px;">
                        <legend class="legend">Submit your application</legend>
                        <div class="fieldset-content-box">
                            <form class="wfst" id="refferal_req_final_submit_form">
                                <input type="hidden" name="csrf_refferal_req_form_token" value="<?= generateToken('refferal_req_final_submit_form'); ?>">
                                <input type="hidden" name="hidden_refferal_req_code" value="<?= ((isset($case_data['m_code']))) ? $case_data['m_code'] : '' ?>">

                                <div class="col-xs-12 form-group">
                                    <p class="mb-0">
                                        <strong>Note: </strong> Final submit the case. After final submit, it will generate the DIAC registration number and allocate the case to respective deputy counsel, case manager and additional coordinator.
                                    </p>
                                </div>
                                <!-- Final Submit -->
                                <div class="col-xs-12 form-group text-center">
                                    <button class="btn btn-success" type="submit" id="btn_ref_req_final_submit">
                                        <i class="fa fa-paper-plane"></i> Final Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </fieldset>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/claimant-respondant-for-refferal-request.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/arbitral-tribunal.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/address.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/counsels_for_refferal_req.php'); ?>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/miscellaneous.js')) ?>"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/claimant-respondant.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/claimant-respondant.js')) ?>"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/add-arbitral-tribunal.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/add-arbitral-tribunal.js')) ?>"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/counsels_for_refferal_req.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/counsels_for_refferal_req.js')) ?>"></script>