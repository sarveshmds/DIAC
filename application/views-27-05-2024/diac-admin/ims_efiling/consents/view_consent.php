<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">

                <div class="row flex-row">

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Diary Number <span class="text-danger">*</span> </label>
                            <p class="mb-0"><?= $consent['diary_number'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Case No. <span class="text-danger">*</span> </label>
                            <p class="mb-0"><?= $consent['case_no_desc'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Case Title <span class="text-danger">*</span> </label>
                            <p class="mb-0"><?= $consent['case_title'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Name</label>
                            <p class="mb-0"><?= $consent['name'] ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Mobile No.</label>
                            <p class="mb-0"><?= $consent['mobile_number'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Email ID</label>
                            <p class="mb-0"><?= $consent['email_id'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">P.S./Secretary</label>
                            <p class="mb-0"><?= $consent['secretary'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Prior Experience (Including experience with arbitration)</label>
                            <p class="mb-0"><?= $consent['prior_experience'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Number of ongoing Arbitrations</label>
                            <p class="mb-0"><?= $consent['ongoing_arbitrations'] ?></p>
                        </div>
                    </div>

                    <div class="col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Circumstances disclosing any past or present relationship with or interest in any of the parties or in relation to the subject-matter in dispute, whether financial, business, professional or other kind, which is likely to give rise to justifiable doubts as to your independence or impartiality (List Out)</label>
                            <p class="mb-0"><?= $consent['ongoing_arbitrations'] ?></p>
                        </div>
                    </div>

                    <div class="col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Circumstances which are likely to affect your ability to devote sufficient time to the arbitration and in particular your ability to finish the entire arbitration within twelve month (List Out)</label>
                            <p class="mb-0"><?= $consent['circumstances_affect_ability'] ?></p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Upload Signature</label>
                            <div>
                                <a href="#" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-paper-plane"></i> View</a>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                        <div class="view_col">
                            <label for="" class="form-label">Remarks (If Any)</label>
                            <p class="mb-0"><?= $consent['remarks'] ?></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>