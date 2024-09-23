<div class="content-wrapper">
    <section class="content-header">
        <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
        <?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div>
                            <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Miscellaneous Files</legend>
                                <div class="fieldset-content-box">
                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if ($case_files['misc_files'] && count($case_files['misc_files']) > 0) : ?>
                                                <?php $files = json_decode($case_files['misc_files']['document']);
                                                ?>
                                                <?php if ($files && count($files) > 0) : ?>
                                                    <?php foreach ($files as $key => $file) : ?>
                                                        <a href="<?= base_url() . VIEW_MISCELLANEOUS_FILE_UPLOADS_FOLDER . $file ?>" class="btn btn-custom" target="_BLANK">
                                                            <i class="fa fa-eye"></i> View File <?= $key + 1 ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <p>No files are available.</p>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <p>No files are available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Case Registration Files</legend>
                                <div class="fieldset-content-box">
                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if (count($case_files['case_reg_file']) > 0) : ?>
                                                <?php $files = json_decode($case_files['case_reg_file']['case_file']);
                                                ?>
                                                <?php if ($files && count($files) > 0) : ?>
                                                    <?php foreach ($files as $key => $file) : ?>
                                                        <a href="<?= base_url() . VIEW_CASE_FILE_UPLOADS_FOLDER . $file ?>" class="btn btn-custom" target="_BLANK">
                                                            <i class="fa fa-eye"></i> View File <?= $key + 1 ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <p>No files are available.</p>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <p>No files are available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Noting Files</legend>
                                <div class="fieldset-content-box">
                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if (count($case_files['noting_files']) > 0) : ?>
                                                <?php $fileCount = 1; ?>
                                                <?php foreach ($case_files['noting_files'] as $key => $file) : ?>
                                                    <?php $files = json_decode($file['noting_file']); ?>
                                                    <?php if ($files && count($files) > 0) : ?>
                                                        <?php foreach ($files as $key2 => $file) : ?>
                                                            <a href="<?= base_url() . VIEW_NOTING_FILE_UPLOADS_FOLDER . $file ?>" class="btn btn-custom" target="_BLANK">
                                                                <i class="fa fa-eye"></i> View File <?= $fileCount++ ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <p>No files are available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Arbitrator's Termination Files</legend>
                                <div class="fieldset-content-box">

                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if (count($case_files['arbitrator_files']) > 0) : ?>
                                                <?php $fileCount = 1; ?>
                                                <?php foreach ($case_files['arbitrator_files'] as $key => $file) : ?>
                                                    <?php $files = json_decode($file['termination_files']); ?>
                                                    <?php foreach ($files as $key2 => $file) : ?>
                                                        <a href="<?= base_url() . VIEW_NOTING_FILE_UPLOADS_FOLDER . $file ?>" class="btn btn-custom" target="_BLANK">
                                                            <i class="fa fa-eye"></i> View File <?= $fileCount++ ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <p>No files are available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </fieldset> -->
                            <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Assessment Sheets</legend>
                                <div class="fieldset-content-box">
                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if (count($case_files['assessment_files']) > 0) : ?>
                                                <?php $fileCount = 1; ?>
                                                <?php foreach ($case_files['assessment_files'] as $key => $file) : ?>
                                                    <a href="<?= base_url() . VIEW_FEE_FILE_UPLOADS_FOLDER . $file['assessment_sheet_doc'] ?>" class="btn btn-custom" target="_BLANK">
                                                        <i class="fa fa-eye"></i> Assessment Sheet <?= $fileCount++ ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <p>No files are available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset mb-15 fee-fieldset">
                                <legend>Termination Files</legend>
                                <div class="fieldset-content-box">
                                    <div class="row mb-15">
                                        <div class="col-xs-12">
                                            <?php if ($case_files['award_term_files']) : ?>
                                                <?php if ($case_files['award_term_files']['award_term_award_file']) : ?>
                                                    <a href="<?= base_url() . VIEW_AWARD_FILE_UPLOADS_FOLDER . $case_files['award_term_files']['award_term_award_file'] ?>" class="btn btn-custom" target="_BLANK">
                                                        <i class="fa fa-eye"></i> View File <?= $key + 1 ?>
                                                    </a>
                                                <?php else : ?>
                                                    <p class="mb-15">
                                                        <strong>
                                                            No award file available.
                                                        </strong>
                                                    </p>
                                                <?php endif; ?>

                                                <?php if ($case_files['award_term_files']['factsheet_file']) : ?>
                                                    <a href="<?= base_url() . VIEW_FACTSHEET_FILE_UPLOADS_FOLDER . $case_files['award_term_files']['factsheet_file'] ?>" class="btn btn-custom" target="_BLANK">
                                                        <i class="fa fa-eye"></i> View File <?= $key + 1 ?>
                                                    </a>
                                                <?php else : ?>
                                                    <p class="mb-15" style="margin-top: 10px;">
                                                        No factsheet file available.
                                                    </p>
                                                <?php endif; ?>

                                            <?php else : ?>
                                                <p class="mb-15">
                                                    No files are available.
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script type="text/javascript"></script>