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
                            <a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
                                Claimant & Respondent Details
                            </a>
                        </li>

                        <li class="">
                            <a href="#tab_3" class="rc_nav_link" data-toggle="tab" aria-expanded="false" id="btn_counsel_nav_tab">
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

                                <div class="row">

                                    <div class="form-group col-md-12 col-xs-12 required">
                                        <label class="control-label">Case Title:</label>
                                        <input type="text" class="form-control" id="cd_case_title" name="cd_case_title" autocomplete="off" value="<?= (isset($case_data['case_title'])) ? $case_data['case_title'] : '' ?>" />
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
                                        <input type="text" name="cd_reffered_by_judge" id="cd_reffered_by_judge" class="form-control" value="<?= (isset($case_data['reffered_by_judge'])) ? $case_data['reffered_by_judge'] : '' ?>">
                                        <small class="text-danger">
                                            Enter comma (,) separated names
                                        </small>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Name of Authority:</label>
                                        <input type="text" name="cd_name_of_court" id="cd_name_of_court" class="form-control" value="<?= (isset($case_data['name_of_court'])) ? $case_data['name_of_court'] : ''; ?>" maxlength="250">
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Reference No.:</label>
                                        <input type="text" class="form-control" id="cd_case_arb_pet" name="cd_case_arb_pet" autocomplete="off" maxlength="100" value="<?= (isset($case_data['arbitration_petition'])) ? $case_data['arbitration_petition'] : '' ?>" />
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
                                        <label for="" class="control-label">Case Type:</label>
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

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 arbitral-tribunal-stength-col required" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == 1) ? 'selected' : 'style="display: none;"' ?>>
                                        <label for="" class="control-label">Arbitral Tribunal Strength:</label>
                                        <select name="cd_arbitral_tribunal_strength" id="cd_arbitral_tribunal_strength" class="form-control">
                                            <option value="">Select Option</option>
                                            <option value="1">Sole</option>
                                            <option value="3">3</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>

                                    <div class="col-xs-12 apponted_arb_details_wrapper" <?= (isset($case_data['arbitrator_status']) && $case_data['arbitrator_status'] == 1) ? 'selected' : 'style="display: none;"' ?>>
                                        <fieldset class="fieldset apponted_arb_details_1">
                                            <legend class="legend">Arbitral Tribunal Details</legend>
                                            <div class="fieldset-content-box">
                                                <div class="row">
                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                                        <label for="" class="control-label">Is Arbitrator Empanelled:</label>
                                                        <select name="cd_arbitrator_is_empanelled_1" id="cd_arbitrator_is_empanelled_1" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <?php foreach ($arbitrator_is_empanelled as $toa) : ?>
                                                                <option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['arbitrator_is_empanelled']) && $toa['gen_code'] == $case_data['arbitrator_is_empanelled']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 emp_arbitrator_list_col_1 required" <?= (isset($case_data['arbitrator_is_empanelled']) && $case_data['arbitrator_is_empanelled'] == 1) ? '' : 'style="display: none;"' ?>>
                                                        <label for="" class="control-label">Arbitrator:</label>
                                                        <select name="cd_empanelled_arbitrator" id="cd_empanelled_arbitrator" class="form-control">
                                                            <option value="">Select Option</option>
                                                            <?php foreach ($emapnelled_arbitrators as $emp_arb) : ?>
                                                                <option value="<?= $emp_arb['code'] ?>" <?= (isset($case_data['empanelled_arbitrator_code']) && $emp_arb['code'] == $case_data['empanelled_arbitrator_code']) ? 'selected' : '' ?>><?= $emp_arb['name_of_arbitrator'] . ' (' . $emp_arb['category_name'] . ')' ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <!-- ----------------------------------------- -->
                                                    <!-- Not empanelled arbitrator wrapper -->
                                                    <!-- ----------------------------------------- -->
                                                    <div class="col-xs-12 form-group not_empanelled_arb_wrapper" <?= (isset($case_data['arbitrator_is_empanelled']) && $case_data['arbitrator_is_empanelled'] == 2) ? '' : 'style="display: none;"' ?>>
                                                        <fieldset class="fieldset">
                                                            <legend class="legend">
                                                                Arbitrator Details
                                                            </legend>
                                                            <div class="fieldset-content-box">
                                                                <div class="row">
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
                                                                        <label class="control-label">Name of Arbitrator:</label>
                                                                        <input type="text" name="at_new_arb_name" id="at_new_arb_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label class="control-label">Email Id:</label>
                                                                        <input type="text" class="form-control" id="at_arb_email" name="at_arb_email" autocomplete="off" />
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label class="control-label">Contact Number:</label>
                                                                        <input type="text" class="form-control" id="at_arb_contact" name="at_arb_contact" autocomplete="off" maxlength="20" value="">
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label for="" class="control-label">Category:</label>
                                                                        <select name="at_category" id="at_category" class="form-control at_category">
                                                                            <option value="">Select Option</option>
                                                                            <?php foreach ($panel_category as $pc) : ?>
                                                                                <option value="<?= $pc['code'] ?>"><?= $pc['category_name'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label for="" class="control-label">Appointed by:</label>
                                                                        <select name="at_appointed_by" id="at_appointed_by" class="at_appointed_by form-control">
                                                                            <option value="">Select Option</option>
                                                                            <?php foreach ($get_appointed_by_list as $apl) : ?>
                                                                                <option value="<?= $apl['gen_code'] ?>"><?= $apl['description'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label class="control-label">Date of Appointment:</label>
                                                                        <div class="input-group date">
                                                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                                            <input type="text" class="form-control custom-all-date at_doa" id="at_doa" name="at_doa" autocomplete="off" readonly="true" value="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                        <label class="control-label">Date of Consent:</label>
                                                                        <div class="input-group date">
                                                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                                            <input type="text" class="form-control custom-all-date at_dod" id="at_dod" name="at_dod" autocomplete="off" readonly="true" value="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 arb_col">
                                                                        <label class="control-label">Arbitrator Type:</label>
                                                                        <select class="form-control at_arb_type" id="at_arb_type" name="at_arb_type" autocomplete="off">
                                                                            <option value="">Select Type</option>
                                                                            <?php foreach ($arbitrator_types as $type) : ?>
                                                                                <option value="<?= $type['gen_code'] ?>"><?= $type['description'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-xs-12">
                                                                        <fieldset class="fieldset">
                                                                            <legend class="legend">Permanent Address</legend>
                                                                            <div class="fieldset-content-box">
                                                                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                                    <label class="control-label">Address 1</label>
                                                                                    <input type="text" name="permanent_address_1" id="permanent_address_1" class="form-control" value="">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                                    <label class="control-label">Address 2</label>
                                                                                    <input type="text" name="permanent_address_2" id="permanent_address_2" class="form-control" value="">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                                    <label class="control-label">Country</label>
                                                                                    <select class="form-control" name="permanent_country" id="permanent_country">
                                                                                        <option value="">Select Country</option>
                                                                                        <?php foreach ($countries as $country) : ?>
                                                                                            <option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
                                                                                        <?php endforeach ?>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                                    <label class="control-label">State</label>
                                                                                    <select class="form-control" name="permanent_state" id="permanent_state">
                                                                                        <option value="">Select State</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                                                    <label class="control-label">Pincode</label>
                                                                                    <input type="text" name="permanent_pincode" id="permanent_pincode" class="form-control" value="">
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <!-- ----------------------------------------- -->
                                                    <!-- Not empanelled arbitrator wrapper end -->
                                                    <!-- ----------------------------------------- -->
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12"></div>

                                    <div class="form-group col-md-6 col-xs-12">
                                        <label class="control-label">Supporting Documents:</label>
                                        <input type="file" class="form-control documents-input" id="refferal_req_documents" name="refferal_req_documents[]" autocomplete="off" multiple accept=".pdf, .docx, .doc" value="" multiple />
                                        <input type="hidden" name="hidden_refferal_req_documents" class="multiple_refferal_req_initial_preview" value='<?= ((isset($miscellaneous['document']))) ? $miscellaneous['document'] : '' ?>'>

                                        <?php if (isset($case_supporting_docs) && count($case_supporting_docs) > 0) : ?>
                                            <div class="card card-body">
                                                <p class="mb-0">
                                                    <label for="">
                                                        Uploaded Supporting Documents
                                                    </label>
                                                </p>
                                                <?php foreach ($case_supporting_docs as $doc) : ?>
                                                    <a href="<?= VIEW_CASE_FILE_UPLOADS_FOLDER . $doc['file_name'] ?>" class="btn btn-primary btn-sm">
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


                                <div class="row text-center mt-25">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-custom refferal_req_btn_submit" id="btn_save"><i class='fa fa-paper-plane'></i> Save Details</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Claimant Details Tab -->
                        <div class="tab-pane" id="tab_2">
                            <?php if (isset($case_data['m_code']) && $case_data['m_code'] != "") : ?>
                                <div>
                                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <li class="active">
                                            <a class="nav-item nav-link" id="nav-sop-tab" data-toggle="tab" href="#nav-sop" role="tab" aria-controls="nav-sop" aria-selected="true">Claimant Details</a>
                                        </li>

                                        <li>
                                            <a class="nav-item nav-link" id="nav-op-tab" data-toggle="tab" href="#nav-op" role="tab" aria-controls="nav-op" aria-selected="false">Respondent Details</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="nav-tabContent">
                                        <!-- Claimant Start -->
                                        <div class="tab-pane fade active" id="nav-sop" role="tabpanel" aria-labelledby="nav-sop-tab">
                                            <div class="tab-content-col">
                                                <div>
                                                    <table id="dataTableClaimantList" class="table table-condensed table-striped table-bordered" data-page-size="10">
                                                        <input type="hidden" id="csrf_cl_trans_token" value="<?php echo generateToken('dataTableClaimantList'); ?>">

                                                        <?php if (isset($case_data)) : ?>
                                                            <input type="hidden" id="cl_case_no" value="<?php echo $case_data['slug']; ?>">
                                                        <?php endif; ?>

                                                        <thead>
                                                            <tr>
                                                                <th style="width:8%;">S. No.</th>
                                                                <th>Claimant Name</th>
                                                                <th>Claimant Number</th>
                                                                <th>Email Id</th>
                                                                <th>Phone Number</th>
                                                                <th>Removed</th>
                                                                <th style="width:15%;">Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Claimant End -->

                                        <!-- ====================================================== -->
                                        <!-- Respondent Start -->
                                        <div class="tab-pane fade" id="nav-op" role="tabpanel" aria-labelledby="nav-op-tab">
                                            <div class="tab-content-col">
                                                <div>
                                                    <table id="dataTableRespondantList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                                        <input type="hidden" id="csrf_res_trans_token" value="<?php echo generateToken('dataTableRespondantList'); ?>">

                                                        <?php if (isset($case_data)) : ?>
                                                            <input type="hidden" id="res_case_no" value="<?php echo $case_data['slug']; ?>">
                                                        <?php endif; ?>

                                                        <thead>
                                                            <tr>
                                                                <th style="width:8%;">S. No.</th>
                                                                <th>Respondent Name</th>
                                                                <th>Respondent Number</th>
                                                                <th>Email Id</th>
                                                                <th>Phone Number</th>
                                                                <th>Counter Claimant</th>
                                                                <th>Removed</th>
                                                                <th style="width:15%;">Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Respondent end -->

                                    </div>

                                </div>

                            <?php else : ?>
                                <div class="alert alert-custom">
                                    Please add general information first, then you will be able to fill other details
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Counsels Details -->
                        <div class="tab-pane" id="tab_3">
                            <?php if (isset($case_data['m_code']) && $case_data['m_code'] != "") : ?>
                                <div>
                                    <table id="dataTableCounselsList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                        <input type="hidden" id="csrf_counsels_trans_token" value="<?php echo generateToken('dataTableCounselsList'); ?>">

                                        <?php if (isset($case_no)) : ?>
                                            <input type="hidden" id="counsels_case_no" value="<?php echo $case_no; ?>">
                                        <?php endif; ?>

                                        <thead>
                                            <tr>
                                                <th style="width:7%;">S. No.</th>
                                                <th>Appearing for</th>
                                                <th>Name of counsels</th>
                                                <th>Enrollment No.</th>
                                                <th>E-mail</th>
                                                <th>Contact Number</th>
                                                <th>Discharged</th>
                                                <th>Date of discharge</th>
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

                <?php if (isset($case_data['m_code']) && $case_data['m_code'] != "") : ?>
                    <hr class="custom-hr">
                    <fieldset class="fieldset" style="margin: 10px 10px 10px 10px;">
                        <legend class="legend">Submit your application</legend>
                        <div class="fieldset-content-box">
                            <form class="wfst" id="refferal_req_final_submit_form">
                                <input type="hidden" name="csrf_refferal_req_form_token" value="<?= generateToken('refferal_req_final_submit_form'); ?>">
                                <input type="hidden" name="hidden_refferal_req_code" value="<?= ((isset($case_data['m_code']))) ? $case_data['m_code'] : '' ?>">

                                <div class="col-xs-12 form-group">
                                    <p class="mb-0">
                                        <strong>Note: </strong> Now you can submit the application, after final submit, it will be sent to coordinator for approval.
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
<?php require_once(APPPATH . 'views/modals/diac-admin/claimant-respondant.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/counsels.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/address.php'); ?>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/claimant-respondant.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/counsels.js') ?>"></script>