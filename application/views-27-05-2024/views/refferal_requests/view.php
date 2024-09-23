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
                                Arbitrators
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- General Information Tab -->
                        <div class="tab-pane active" id="tab_1">
                            <?php require_once(APPPATH.'views/refferal_requests/view_common_comp.php') ?>
                        </div>

                        <!-- Claimant Details Tab -->
                        <div class="tab-pane" id="tab_2" <?= (isset($refferal_req['arbitrator_status']) && $refferal_req['arbitrator_status'] == '2') ? 'style="display: none;"' : '' ?>>
                            
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="datatableArbList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
                                        <input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('datatableArbList'); ?>">
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
                                                    <!-- <th width="20%">Action</th> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                            </div>
                        </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js') ?>"></script>