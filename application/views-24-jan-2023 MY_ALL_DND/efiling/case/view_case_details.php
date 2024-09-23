<style>
    .accordion-button {
        background: #083366 !important;
        color: white !important;
    }

    .accordion-button::after {
        filter: invert(1);
    }
</style>
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
                <a href="<?= base_url('efiling/my-cases') ?>" class="btn btn-secondary btn-sm">
                    <i class="fa fa-angle-left"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body">
                <div class="accordion" id="accordionRental">
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingOne">
                            <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Basic Information
                            </button>
                        </h5>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionRental" style="">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>DIAC Registration No: </b>
                                        <?= $case_data['case_data']['case_no'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Case Title: </b>
                                        <?= $case_data['case_data']['case_title'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Date of reference order: </b>
                                        <?= formatDatepickerDate($case_data['case_data']['reffered_on']) ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Mode of reference: </b>
                                        <?= $case_data['case_data']['reffered_by_desc'] ?>
                                    </div>
                                    <?php if (isset($case_data['case_data']['reffered_by']) && $case_data['case_data']['reffered_by'] == 'COURT') : ?>
                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Name of Judge: </b>
                                            <?= $case_data['case_data']['reffered_by_judge'] ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($case_data['case_data']['reffered_by']) && $case_data['case_data']['reffered_by'] == 'OTHER') : ?>
                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Name of Court/Department: </b>
                                            <?= $case_data['case_data']['name_of_court'] ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Reference No. : </b>
                                        <?= $case_data['case_data']['arbitration_petition'] ?>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <b>Ref. Received On: </b>
                                        <?= formatDatepickerDate($case_data['case_data']['recieved_on']) ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Date of registration: </b>
                                        <?= formatDatepickerDate($case_data['case_data']['registered_on']) ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Type of arbitration: </b>
                                        <?= $case_data['case_data']['type_of_arbitration_desc'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Which type of arbitration: </b>
                                        <?= $case_data['case_data']['di_type_of_arbitration_desc'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Arbitrator Status: </b>
                                        <?= $case_data['case_data']['arbitration_status_desc'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Case Status: </b>
                                        <?= $case_data['case_data']['case_status_dec'] ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Remarks: </b>
                                        <?= $case_data['case_data']['remarks'] ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingTwo">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Claimants & Respondants
                            </button>
                        </h5>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">

                                <fieldset class="fieldset mb-3">
                                    <legend class="legend">Claimants</legend>
                                    <div class="fieldset-content-box">
                                        <table class="table  table-bordered common-data-table" id="cl_data_table">
                                            <thead>
                                                <tr>
                                                    <th>S. No.</th>
                                                    <th width="25%">Name</th>
                                                    <th>Email Id</th>
                                                    <th>Phone No.</th>
                                                    <th>Removed or not</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php $count = 1; ?>
                                                <?php foreach ($case_data['claimant_data'] as $cl_data) : ?>
                                                    <tr>
                                                        <td><?= $count++ ?></td>
                                                        <td><?= $cl_data['name'] . ' (' . $cl_data['count_number'] . ')' ?></td>
                                                        <td><?= $cl_data['email'] ?></td>
                                                        <td><?= $cl_data['contact'] ?></td>
                                                        </td>
                                                        <td><?= $cl_data['removed_desc'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>

                                <fieldset class="fieldset mb-3">
                                    <legend class="legend">Respondants</legend>
                                    <div class="fieldset-content-box">
                                        <table class="table  table-bordered common-data-table" id="res_data_table">
                                            <thead>
                                                <tr>
                                                    <th>S. No.</th>
                                                    <th width="25%">Name</th>
                                                    <th>Email Id</th>
                                                    <th>Phone No.</th>
                                                    <th>Removed or not</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php $count = 1; ?>
                                                <?php foreach ($case_data['respondant_data'] as $res_data) : ?>
                                                    <tr>
                                                        <td><?= $count++ ?></td>
                                                        <td><?= $res_data['name'] . ' (' . $res_data['count_number'] . ')' ?></td>
                                                        <td><?= $res_data['email'] ?></td>
                                                        <td><?= $res_data['contact'] ?></td>
                                                        <td><?= $res_data['removed_desc'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingThree">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Status of Pleadings
                            </button>
                        </h5>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Claim Invited On: </b>
                                        <?= (isset($case_data['sop_data']['claim_invited_on'])) ? $case_data['sop_data']['claim_invited_on'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Reminder to claim: </b>
                                        <?= (isset($case_data['sop_data']['rem_to_claim_on'])) ? $case_data['sop_data']['rem_to_claim_on'] : '' ?>
                                    </div>

                                    <?php if (isset($case_data['sop_data']['rem_to_claim_on_2']) && !empty($case_data['sop_data']['rem_to_claim_on_2'])) : ?>
                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Reminder to claim (2): </b>
                                            <?= (isset($case_data['sop_data']['rem_to_claim_on_2'])) ? $case_data['sop_data']['rem_to_claim_on_2'] : '' ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Claim filed On: </b>
                                        <?= (isset($case_data['sop_data']['claim_filed_on'])) ? $case_data['sop_data']['claim_filed_on'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Respondent served on: </b>
                                        <?= (isset($case_data['sop_data']['res_served_on'])) ? $case_data['sop_data']['res_served_on'] : '' ?>
                                    </div>


                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Statement of defence filed on: </b>
                                        <?= (isset($case_data['sop_data']['sod_filed_on'])) ? $case_data['sop_data']['sod_filed_on'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Whether filed beyond period of limitation: </b>
                                        <?= (isset($case_data['sop_data']['sod_pol'])) ? $case_data['sop_data']['sod_pol'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Number of days of delay: </b>
                                        <?= (isset($case_data['sop_data']['sod_dod'])) ? $case_data['sop_data']['sod_dod'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Rejoinder to Statement of Defence filed on: </b>
                                        <?= (isset($case_data['sop_data']['rej_stat_def_filed_on'])) ? $case_data['sop_data']['rej_stat_def_filed_on'] : '' ?>
                                    </div>

                                    <!-- Counter Claim -->
                                    <div class="col-md-3 form-group">
                                        <b>Counter Claim: </b>
                                        <?= (isset($case_data['sop_data']['counter_claim'])) ? $case_data['sop_data']['counter_claim'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Date of filing of Counter Claim: </b>
                                        <?= (isset($case_data['sop_data']['dof_counter_claim'])) ? $case_data['sop_data']['dof_counter_claim'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Reply to Counter Claim filed on: </b>
                                        <?= (isset($case_data['sop_data']['reply_counter_claim_on'])) ? $case_data['sop_data']['reply_counter_claim_on'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Whether filed beyond period of limitation: </b>
                                        <?= (isset($case_data['sop_data']['reply_counter_claim_pol'])) ? $case_data['sop_data']['reply_counter_claim_pol'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Number of days of delay: </b>
                                        <?= (isset($case_data['sop_data']['reply_counter_claim_dod'])) ? $case_data['sop_data']['reply_counter_claim_dod'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Rejoinder to Reply of Counter Claim filed on: </b>
                                        <?= (isset($case_data['sop_data']['rej_reply_counter_claim_on'])) ? $case_data['sop_data']['rej_reply_counter_claim_on'] : '' ?>
                                    </div>


                                    <!-- Application act 17 -->
                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Application under Section 17 of A&C Act: </b>
                                        <?= (isset($case_data['sop_data']['app_section'])) ? $case_data['sop_data']['app_section'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Date of filing of Application (17): </b>
                                        <?= (isset($case_data['sop_data']['dof_app'])) ? $case_data['sop_data']['dof_app'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Reply to the Application filed on (17): </b>
                                        <?= (isset($case_data['sop_data']['reply_app_on'])) ? $case_data['sop_data']['reply_app_on'] : '' ?>
                                    </div>

                                    <!-- Application act 16 -->
                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Application under Section 16 of A&C Act: </b>
                                        <?= (isset($case_data['sop_data']['app_section'])) ? $case_data['sop_data']['app_section'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Date of filing of Application (16): </b>
                                        <?= (isset($case_data['sop_data']['dof_app'])) ? $case_data['sop_data']['dof_app'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Reply to the Application filed on (16): </b>
                                        <?= (isset($case_data['sop_data']['reply_app_on'])) ? $case_data['sop_data']['reply_app_on'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Remarks: </b>
                                        <?= (isset($case_data['sop_data']['remarks'])) ? $case_data['sop_data']['remarks'] : '' ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFour">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Counsels
                            </button>
                        </h5>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <table class="table  table-bordered common-data-table" id="res_data_table">
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th width="15%">Name</th>
                                            <th>Enrollment No.</th>
                                            <th>Appearing for</th>
                                            <th>Email Id</th>
                                            <th>Phone No.</th>
                                            <th>Discharged</th>
                                            <th>Date of discharge</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($case_data['counsels'] as $counsel) : ?>
                                            <tr>
                                                <td><?= $count++ ?></td>
                                                <td><?= $counsel['name'] ?></td>
                                                <td><?= $counsel['enrollment_no'] ?></td>
                                                <td><?= $counsel['cl_res_name'] . '-' . $counsel['cl_res_count_number'] ?></td>
                                                <td>
                                                    <?= $counsel['email'] ?>
                                                </td>
                                                <td><?= $counsel['phone'] ?></td>
                                                <td><?= $counsel['discharged'] ?></td>
                                                <td><?= formatReadableDate($counsel['date_of_discharge']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFifth">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifth" aria-expanded="false" aria-controls="collapseFifth">
                                Arbitral Tribunals
                            </button>
                        </h5>
                        <div id="collapseFifth" class="accordion-collapse collapse" aria-labelledby="headingFifth" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="table-responsive">

                                    <table class="table  table-bordered common-data-table" id="res_data_table">
                                        <thead>
                                            <tr>
                                                <th>S. No.</th>
                                                <th width="12%">Name</th>
                                                <th>Arbitrator Type</th>
                                                <th>Wheather On Panel</th>
                                                <th>Category</th>
                                                <th>Appointed By</th>
                                                <th>Date Of Appointment</th>
                                                <th>Date Of Declaration</th>
                                                <th>Terminated</th>
                                                <th>Date Of Termination</th>
                                                <th>Reason Of Termination</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $count = 1; ?>
                                            <?php foreach ($case_data['arbitral_tribunal'] as $at) : ?>
                                                <tr>
                                                    <td><?= $count++ ?></td>
                                                    <td><?= $at['name_of_arbitrator'] ?></td>
                                                    <td><?= $at['arbitrator_type_desc'] ?></td>
                                                    <td><?= $at['whether_on_panel_desc'] ?></td>
                                                    <td><?= $at['category_name'] ?></td>
                                                    <td>
                                                        <?= $at['appointed_by_desc'] ?>
                                                    </td>
                                                    <td><?= formatReadableDate($at['date_of_appointment']) ?></td>
                                                    <td><?= formatReadableDate($at['date_of_declaration']) ?></td>
                                                    <td><?= $at['arb_terminated_desc'] ?></td>
                                                    <td><?= formatReadableDate($at['date_of_termination']) ?></td>
                                                    <td><?= $at['reason_of_termination'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filed Document -->
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFifth-f-doc">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifth-f-doc" aria-expanded="false" aria-controls="collapseFifth-f-doc">
                                Filed Documents
                            </button>
                        </h5>
                        <div id="collapseFifth-f-doc" class="accordion-collapse collapse" aria-labelledby="headingFifth-f-doc" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered common-data-table" id="res_data_table">
                                        <thead>
                                            <tr>
                                                <th class="">S. No.</th>
                                                <th class="">Diary No.</th>
                                                <th class="">Type of document</th>
                                                <th class="">On Behalf Of</th>
                                                <th>Name</th>
                                                <th class="">Remarks</th>
                                                <th>Document</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php $count = 1; ?>
                                            <?php foreach ($case_data['e_documents'] as $d) : ?>
                                                <tr>
                                                    <td><?= $count++ ?></td>
                                                    <td><?= $d['diary_number'] ?></td>
                                                    <td><?= $d['type_of_document_desc'] ?></td>
                                                    <td><?= $d['behalf_of_desc'] ?></td>
                                                    <td><?= $d['name'] ?></td>
                                                    <td><?= $d['remarks'] ?></td>
                                                    <td>
                                                        <a href="<?= base_url('public/upload/efiling/documents/' . $d['upload']) ?>" target="_BLANK" class="btn btn-custom"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filed Applications -->
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFifth-f-app">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifth-f-app" aria-expanded="false" aria-controls="collapseFifth-f-app">
                                Filed Applications
                            </button>
                        </h5>
                        <div id="collapseFifth-f-app" class="accordion-collapse collapse" aria-labelledby="headingFifth-f-app" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered common-data-table" id="res_data_table">
                                        <thead>
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Diary No.</th>
                                                <th>Application Type</th>
                                                <th>Filed Type</th>
                                                <th>On Behalf Of</th>
                                                <th>Name</th>
                                                <th>Remarks</th>
                                                <th>Document</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $count = 1; ?>
                                            <?php foreach ($case_data['e_applications'] as $d) : ?>
                                                <tr>
                                                    <td><?= $count++ ?></td>
                                                    <td><?= $d['diary_number'] ?></td>
                                                    <td><?= $d['app_type_desc'] ?></td>
                                                    <td><?= $d['filing_type_desc'] ?></td>
                                                    <td><?= $d['behalf_of_desc'] ?></td>
                                                    <td><?= $d['name'] ?></td>
                                                    <td><?= $d['remarks'] ?></td>
                                                    <td>
                                                        <a href="<?= base_url('public/upload/efiling/applications/' . $d['upload']) ?>" target="_BLANK" class="btn btn-custom"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filed Vakalatnama -->
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFifth-f-vak">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifth-f-vak" aria-expanded="false" aria-controls="collapseFifth-f-vak">
                                Filed Vakalatnama
                            </button>
                        </h5>
                        <div id="collapseFifth-f-vak" class="accordion-collapse collapse" aria-labelledby="headingFifth-f-vak" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered common-data-table" id="res_data_table">
                                        <thead>
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Diary No.</th>
                                                <th>On Behalf Of</th>
                                                <th>Name (On Behalf Of)</th>
                                                <th>Remarks</th>
                                                <th>Document</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php $count = 1; ?>
                                            <?php foreach ($case_data['e_vakalatnamas'] as $d) : ?>
                                                <tr>
                                                    <td><?= $count++ ?></td>
                                                    <td><?= $d['diary_number'] ?></td>
                                                    <td><?= $d['behalf_of_desc'] ?></td>
                                                    <td><?php

                                                        if ($d['case_already_registered'] == 2) {
                                                            echo $d['claimant_respondent_text_name'];
                                                        }

                                                        if ($d['case_already_registered'] == 1) {
                                                            echo $d['claimant_respondent_name'];
                                                        }
                                                        ?></td>
                                                    <td><?= $d['remarks'] ?></td>
                                                    <td>
                                                        <a href="<?= base_url('public/upload/efiling/vakalatnamas/' . $d['upload']) ?>" target="_BLANK" class="btn btn-custom"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filed Consents -->
                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="headingFifth-f-con">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifth-f-con" aria-expanded="false" aria-controls="collapseFifth-f-con">
                                Filed Consents
                            </button>
                        </h5>
                        <div id="collapseFifth-f-con" class="accordion-collapse collapse" aria-labelledby="headingFifth-f-con" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="table-responsive">

                                    <table class="table table-striped table-bordered common-data-table" id="res_data_table">
                                        <thead>
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Diary No.</th>
                                                <th>Remarks</th>
                                                <th>Documents</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $count = 1; ?>
                                            <?php foreach ($case_data['e_concents'] as $d) : ?>
                                                <tr>
                                                    <td><?= $count++ ?></td>
                                                    <td><?= $d['diary_number'] ?></td>
                                                    <td><?= $d['remarks'] ?></td>
                                                    <td>
                                                        <a href="<?= base_url('public/upload/efiling/consent/' . $d['upload']) ?>" target="_BLANK" class="btn btn-custom"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="heading6">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                Case Fee
                            </button>
                        </h5>
                        <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <?php if (count($case_data['fee_cost_data']) > 0) : ?>
                                    <?php foreach ($case_data['fee_cost_data'] as $key => $fc) : ?>
                                        <fieldset class="fieldset mb-15 fee-fieldset">
                                            <legend>Assessment - <?= $key + 1 ?></legend>
                                            <div class="fieldset-content-box">
                                                <div class="row flex-row">
                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">

                                                        <label class="form-label">Whether Claims or Counter Claims assessed separately:</label>
                                                        <div>
                                                            <?= (isset($fc['c_cc_asses_sep'])) ? ucfirst($fc['c_cc_asses_sep']) : ''; ?>
                                                        </div>
                                                    </div>

                                                    <?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>

                                                        <div class="form-group col-md-3  col-sm-6 col-xs-12">
                                                            <label class="form-label">Sum in Dispute (Claims) (Rs.):</label>
                                                            <div>
                                                                <?= (isset($fc['sum_in_dispute_claim'])) ? $fc['sum_in_dispute_claim'] : ''  ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                            <label class="form-label">Sum in Dispute (Counter Claims) (Rs.):</label>
                                                            <div>
                                                                <?= (isset($fc['sum_in_dispute_cc'])) ? $fc['sum_in_dispute_cc'] : ''  ?>
                                                            </div>
                                                        </div>

                                                    <?php else : ?>
                                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                            <label class="form-label">Sum in Dispute (Rs.):</label>
                                                            <div>
                                                                <?= (isset($fc['sum_in_dispute'])) ? $fc['sum_in_dispute'] : ''  ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                        <label class="form-label">As per provisional assessment dated:</label>
                                                        <div>
                                                            <?= (isset($fc['asses_date'])) ? formatReadableDate($fc['asses_date']) : ''  ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                        <label class="form-label">Total Arbitrators Fees (Rs.):</label>
                                                        <div>
                                                            <?= (isset($fc['total_arb_fees'])) ? $fc['total_arb_fees'] : ''  ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                        <label class="form-label">Assessment Approved (Rs.):</label>
                                                        <div>
                                                            <?= (isset($fc['assessment_approved'])) ? ucfirst($fc['assessment_approved']) : ''; ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                        <label class="form-label">Assessmemt Sheet:</label>
                                                        <div>
                                                            <?= (isset($fc['assessment_sheet_doc']) && !empty($fc['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $fc['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <?php  // Claimant Share 
                                                        ?>
                                                        <fieldset class="fieldset">
                                                            <legend class="inner-legend">Claimant Share</legend>
                                                            <div class="fieldset-content-box">
                                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                    <label class="form-label">Arbitrators Fees (Rs.):</label>
                                                                    <div>
                                                                        <?= (isset($fc['cs_arb_fees'])) ? $fc['cs_arb_fees'] : ''  ?>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                    <label class="form-label">Administrative Expenses (Rs.):</label>
                                                                    <div>
                                                                        <?= (isset($fc['cs_adminis_fees'])) ? $fc['cs_adminis_fees'] : ''  ?>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <?php  // Respondent Share 
                                                        ?>
                                                        <fieldset class="fieldset">
                                                            <legend class="inner-legend">Respondent Share</legend>
                                                            <div class="fieldset-content-box">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                        <label class="form-label">Arbitrators Fees (Rs.):</label>
                                                                        <div>
                                                                            <?= (isset($fc['rs_arb_fees'])) ? $fc['rs_arb_fees'] : ''  ?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                        <label class="form-label">Administrative Expenses (Rs.):</label>
                                                                        <div>
                                                                            <?= (isset($fc['rs_adminis_fee'])) ? $fc['rs_adminis_fee'] : ''  ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                </div>
                                            </div>
                                        </fieldset>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div>
                                        <h5 class="text-danger"><i class="fa fa-close"></i> No assessment is added.</h5>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h5 class="accordion-header" id="heading7">
                            <button class="accordion-button border-bottom font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                Termination
                            </button>
                        </h5>
                        <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7" data-bs-parent="#accordionRental">
                            <div class="accordion-body text-sm opacity-8">
                                <div class="row flex-row">

                                    <?php if (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award') : ?>
                                        <div class="col-md-12 col-12 mb-3">
                                            <b>Award Status: </b>Case Awarded
                                        </div>
                                    <?php elseif (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'termination') : ?>
                                        <div class="col-md-12 col-12 mb-3">
                                            <b>Other Status: </b>Case Terminated
                                        </div>
                                    <?php else : ?>
                                        <div class="col-md-12 col-12 mb-3">
                                            <b>Case Processing: </b>Case is under process
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award') : ?>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Date of Award: </b>
                                            <?= (isset($case_data['award_term_data']['date_of_award'])) ? formatReadableDate($case_data['award_term_data']['date_of_award']) : '' ?>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Nature of Award: </b>
                                            <?= (isset($case_data['award_term_data']['nature_of_award'])) ? $case_data['award_term_data']['nature_of_award_desc'] : '' ?>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Addendum Award, if any: </b>
                                            <?= (isset($case_data['award_term_data']['addendum_award'])) ? $case_data['award_term_data']['addendum_award'] : '' ?>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Award served to Claimant on: </b>
                                            <?= (isset($case_data['award_term_data']['award_served_claimaint_on'])) ? formatReadableDate($case_data['award_term_data']['award_served_claimaint_on']) : '' ?>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Award served to Respondent on: </b>
                                            <?= (isset($case_data['award_term_data']['award_served_respondent_on'])) ? formatReadableDate($case_data['award_term_data']['award_served_respondent_on']) : '' ?>
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label for="" class="">Award File: </label>
                                            <?php if (isset($case_data['award_term_data']['award_term_award_file']) && !empty($case_data['award_term_data']['award_term_award_file'])) : ?>
                                                <a href="<?= base_url(VIEW_AWARD_FILE_UPLOADS_FOLDER) . $case_data['award_term_data']['award_term_award_file'] ?>" class="btn btn-custom btn-xs" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
                                            <?php else : ?>
                                                <p>No file is available</p>
                                            <?php endif; ?>
                                        </div>

                                    <?php elseif (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'other') : ?>
                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Date of Termination: </b>
                                            <?= (isset($case_data['award_term_data']['date_of_termination'])) ? formatReadableDate($case_data['award_term_data']['date_of_termination']) : '' ?>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12 mb-3">
                                            <b>Reason for Termination: </b>
                                            <?= (isset($case_data['award_term_data']['reason_for_termination'])) ? $case_data['award_term_data']['termination_reason_desc'] : '' ?>
                                        </div>

                                    <?php endif; ?>


                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <b>Factsheet prepared: </b>
                                        <?= (isset($case_data['award_term_data']['factsheet_prepared_desc'])) ? $case_data['award_term_data']['factsheet_prepared_desc'] : '' ?>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <label class="form-label">Date of factsheet prepared:</label>
                                        <?php echo (isset($case_data['award_term_data']['date_of_factsheet']) && !empty($case_data['award_term_data']['date_of_factsheet'])) ? formatReadableDate($case_data['award_term_data']['date_of_factsheet']) : ''; ?>
                                    </div>

                                    <?php if (isset($case_data['award_term_data']['factsheet_file']) && !empty($case_data['award_term_data']['factsheet_file'])) : ?>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label for="" class="">Factsheet File: </label>
                                            <a href="<?= base_url(VIEW_FACTSHEET_FILE_UPLOADS_FOLDER) . $case_data['award_term_data']['factsheet_file'] ?>" class="btn btn-custom btn-xs" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-xs-12">
                                        <label class="form-label">Note:</label>
                                        <?= (isset($case_data['award_term_data']['note'])) ? $case_data['award_term_data']['note'] : '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.common-data-table').DataTable()
</script>