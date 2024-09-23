<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body p-0">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true" data-case-type="GENERAL">
                                        Pending Applications List
                                    </a>
                                </li>
                                <!-- <li class="">
                                    <a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="EMERGENCY">
                                        Approved & Not Registred
                                    </a>
                                </li> -->
                                <li class="">
                                    <a href="#tab_3" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="EMERGENCY">
                                        Registred List
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Pending Applicaiton Tab -->
                                <div class="tab-pane active" id="tab_1">
                                    <div>
                                        <table id="dataTableMiscellaneous" class="table  table-striped table-bordered dt-responsive" data-page-size="10">
                                            <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableMiscellaneous'); ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:7%;">S. No.</th>
                                                    <th>Diary No.</th>
                                                    <th>Case Title</th>
                                                    <th width="15%">Created On</th>
                                                    <th>Is Submitted</th>
                                                    <th width="15%">Status</th>
                                                    <th style="width:10%;">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <!-- Approved and not registered Applications Tab -->
                                <!-- <div class="tab-pane" id="tab_2">
                                    <div>
                                        <table id="dataTableApprovedNotRegMiscellaneous" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                            <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableMiscellaneous'); ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:7%;">S. No.</th>
                                                    <th>Diary No.</th>
                                                    <th>Case Title</th>
                                                    <th>Created On</th>
                                                    <th>Registered</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th style="width:10%;">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div> -->

                                <!-- Approved and Registered Applications Tab -->
                                <div class="tab-pane" id="tab_3">
                                    <div>
                                        <table id="dataTableApprovedMiscellaneous" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                            <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableMiscellaneous'); ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:7%;">S. No.</th>
                                                    <th>Diary No.</th>
                                                    <th>Case No.</th>
                                                    <th>Case Title</th>
                                                    <th>Created On</th>
                                                    <th>Registered</th>
                                                    <th>Status</th>
                                                    <th style="width:10%;">Action</th>
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

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js') ?>"></script>