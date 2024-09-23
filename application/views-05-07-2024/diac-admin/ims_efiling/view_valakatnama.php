<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
    <section class="content-header">

        <h1 style="display: inline-block;"><?php echo (isset($edit_form) && $edit_form == true) ? '<span class="fa fa-edit"></span> ' : '<span class="fa fa-plus"></span> '; ?><?= $page_title ?></h1>

        <a href="Javascript:void(0)" onclick="history.back()" class="btn btn-default pull-right"><span class="fa fa-backward"></span> Go Back</a>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">

                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="">
                                <label for="">Already Case Registered ?</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <input class="form-check-input already_case_reg" type="radio" name="already_case_reg" id="case_reg_yes" value="1" <?php echo (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="case_reg_yes">Yes</label>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <input class="form-check-input already_case_reg" type="radio" name="already_case_reg" id="case_reg_no" value="2" <?php echo (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == 2) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="case_reg_no">No</label>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required" <?= (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == '1') ? '' : 'style="display: none;"'; ?>>
                                        <label class="control-label">Case No</label>
                                        <p><?= $vakalatnama['case_no'] ?></p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12 required" <?= (isset($vakalatnama['case_already_registered']) && $vakalatnama['case_already_registered'] == '2') ? '' : 'style="display: none;"'; ?>>
                                        <label class="control-label">Case No</label>
                                        <p><?= $vakalatnama['case_no_text'] ?></p>
                                    </div>

                                    <div id='behalf_of' class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">On behalf of </label>
                                        <p><?= $vakalatnama['behalf'] ?></p>
                                    </div>

                                    <div id="claimant" class="form-group col-md-3 col-sm-6 col-xs-12 required" <?= (isset($vakalatnama['behalf']) && $vakalatnama['behalf'] == 'CLAIMANT') ? '' : 'style="display: none;"'; ?>>
                                        <label for="" class="control-label">Claimant Name</label>
                                        <p>DIAC</p>
                                    </div>

                                    <div id="respondant" class="form-group col-md-3 col-sm-6 col-xs-12 required" <?= (isset($vakalatnama['behalf']) && $vakalatnama['behalf'] == 'RESPONDENT') ? '' : 'style="display: none;"'; ?>>
                                        <label for="" class="control-label">Respondant Name</label>
                                        <p>Diac</p>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label">Uploaded Document</label>
                                        <a class='action-icon tooltipTable tooltipTable btn btn btn-success btn-sm' href="public/upload/efiling/applications/"><i class='fa fa-edit'></i> View</a>
                                    </div>

                                    <div class=" form-group col-md-3 col-sm-6 col-xs-12 required">
                                        <label for="" class="control-label">Remarks</label>
                                        <p><?php echo $vakalatnama['remarks'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </section>
</div>

<!-- ================================================================================ -->
<!-- Modals Start -->



<!-- Modals End -->
<!-- ================================================================================ -->

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";

    // Timepicker initialization
    $('.timepicker').timepicker({
        Inputs: false
    });

    // $(document).ready(function() {

    //     if ($('$behalf_of').val() == 'CLAIMANT') {
    //         $('#claimant').show();
    //     }
    //     if ($('$behalf_of').val() == 'RESPONDENT') {
    //         $('#claimant').show();
    //     }
    // })
</script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/add-case.js') ?>"></script>