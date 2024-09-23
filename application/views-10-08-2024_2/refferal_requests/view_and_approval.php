<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>

    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body p-0">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true" data-case-type="GENERAL">
                                General Information
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="EMERGENCY">
                                Claimant Details
                            </a>
                        </li>

                        <li class="">
                            <a href="#tab_3" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
                                Counsels
                            </a>
                        </li>

                        <li class="">
                            <a href="#tab_4" class="rc_nav_link" data-toggle="tab" aria-expanded="false" id="btn_counsel_nav_tab">
                                Arbitrators
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- General Information Tab -->
                        <div class="tab-pane active" id="tab_1">

                            <?php if (isset($refferal_req['is_approved']) && $refferal_req['is_approved'] == 0) : ?>
                                <div class="alert alert-custom text-red">
                                    <i class="fa fa-info"></i> Pending for approval
                                </div>
                            <?php endif; ?>

                            <?php require_once(APPPATH . 'views/refferal_requests/view_common_comp.php') ?>

                        </div>

                        <!-- Claimant Details Tab -->
                        <div class="tab-pane" id="tab_2">
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

                                                    <?php if (isset($refferal_req)) : ?>
                                                        <input type="hidden" id="cl_case_no" value="<?php echo $refferal_req['slug']; ?>">
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

                                                    <?php if (isset($refferal_req)) : ?>
                                                        <input type="hidden" id="res_case_no" value="<?php echo $refferal_req['slug']; ?>">
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
                        </div>

                        <!-- Counsels Details -->
                        <div class="tab-pane" id="tab_3">
                            <div>
                                <table id="dataTableCounselsList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                    <input type="hidden" id="csrf_counsels_trans_token" value="<?php echo generateToken('dataTableCounselsList'); ?>">

                                    <?php if (isset($refferal_req['slug'])) : ?>
                                        <input type="hidden" id="counsels_case_no" value="<?php echo $refferal_req['slug']; ?>">
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
                        </div>

                        <!-- Arbitrator Details -->
                        <div class="tab-pane" id="tab_4">
                            <div class="table-responsive">
                                <table id="dataTableArbTriList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                                    <input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('dataTableArbTriList'); ?>">

                                    <?php if (isset($refferal_req['slug'])) : ?>
                                        <input type="hidden" id="at_case_no" value="<?php echo $refferal_req['slug']; ?>">
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
                        </div>

                    </div>

                </div>

                <hr class="custom-hr">

                <form class="wfst" id="refferal_req_approval_form">

                    <input type="hidden" name="csrf_refferal_req_form_token" value="<?= generateToken('refferal_req_approval_form'); ?>">

                    <input type="hidden" id="hidden_refferal_req_code" name="hidden_refferal_req_code" value="<?= $refferal_req['m_code'] ?>">

                    <fieldset class="fieldset">
                        <legend class="legend">
                            Approval/Rejection
                        </legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group required">
                                    <label for="" class="control-label">Approval/Rejection</label>
                                    <select name="approval_rejection_status" id="approval_rejection_status" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                    </select>
                                </div>

                                <div class="col-xs-12"></div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="" class="control-label">Remarks</label>
                                    <textarea name="approval_rejection_remarks" id="approval_rejection_remarks" rows="6" class="form-control"></textarea>
                                </div>

                                <div class="col-xs-12"></div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group text-center">
                                    <button class="btn btn-custom" id="btn_app_rej_submit" type="submit">
                                        <i class="fa fa-paper-plane"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/claimant-respondant.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/counsels.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/add-arbitral-tribunal.js') ?>"></script>