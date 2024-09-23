<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>

    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="completed_work_status_datatable" class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th style="width:7%;">S. No.</th>
                                <th>Case No.</th>
                                <th>Marked By</th>
                                <th>Noting Date</th>
                                <th>Noting</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($works_list as $key => $wl) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= get_full_case_number($wl) ?></td>
                                    <td><?= $wl['marked_by_display_name'] . '<br />(' . $wl['marked_by_job_title'] . ')' ?></td>
                                    <td><?= formatReadableDateTime($wl['noting_date']) ?></td>
                                    <td>
                                        <?php if (!empty($wl['noting'])) : ?>
                                            <?= $wl['noting'] ?>
                                        <?php endif; ?>
                                        <?php if (!empty($wl['noting_text'])) : ?>
                                            <p class="mb-0">
                                                <?= $wl['noting_text'] ?>
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('noting/' . $wl['case_no_slug']) ?>" class="btn btn-custom btn-sm" target="_BLANK">
                                            <i class="fa fa-paper-plane"></i> Go to noting
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
    var user_name = "<?php echo $this->session->userdata('user_name'); ?>";
</script>

<script>
    $('#completed_work_status_datatable').DataTable({
        ordering: false,
        lengthMenu: [
            [50, 100, 150, 500, 1000],
            [50, 100, 150, 500, 1000],
        ]
    });
</script>