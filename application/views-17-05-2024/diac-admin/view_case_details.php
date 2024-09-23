<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
    <section class="content-header">

        <div class="row">

            <div class="col-md-6 col-sm-12 col-xs-12">
                <h3 style="display: inline-block; margin-top: 5px; margin-bottom: 0px;"> <?= $page_title ?></h3>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="pull-right vc-header-btn-col">
                    <a href="<?= base_url('all-registered-case'); ?>" class="btn btn-default "><span class="fa fa-backward"></span> Go Back</a>
                </div>
            </div>

        </div>

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box">

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>DIAC Registration No: </label>
                                    <p class="mb-0">
                                        <?= $case_data['case_no'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Case Title: </label>
                                    <p class="mb-0">
                                        <?= $case_data['case_title'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Date of reference order: </label>
                                    <p class="mb-0">
                                        <?= formatDatepickerDate($case_data['reffered_on']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Mode of reference: </label>
                                    <p class="mb-0">
                                        <?= $case_data['reffered_by_desc'] ?>
                                    </p>
                                </div>
                            </div>

                            <?php if (isset($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') : ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                    <div class="view_col">
                                        <label>Name of Judge: </label>
                                        <p class="mb-0">
                                            <?= $case_data['reffered_by_judge'] ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($case_data['reffered_by']) && $case_data['reffered_by'] == 'OTHER') : ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                    <div class="view_col">
                                        <label>Name of Court/Department: </label>
                                        <p class="mb-0">
                                            <?= $case_data['name_of_court'] ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Reference No. : </label>
                                    <p class="mb-0">
                                        <?= $case_data['arbitration_petition'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
                                <div class="view_col">
                                    <label>Ref. Received On: </label>
                                    <p class="mb-0">
                                        <?= formatDatepickerDate($case_data['recieved_on']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Date of registration: </label>
                                    <p class="mb-0">
                                        <?= formatDatepickerDate($case_data['registered_on']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Type of arbitration: </label>
                                    <p class="mb-0">
                                        <?= $case_data['type_of_arbitration_desc'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Case Type: </label>
                                    <p class="mb-0">
                                        <?= $case_data['di_type_of_arbitration_desc'] ?>
                                    </p>
                                </div>
                            </div>

                            <?php if ($case_data['di_type_of_arbitration'] == 'EMERGENCY') : ?>
                                <div class="col-xs-12">
                                    <fieldset class="fieldset bg-white">
                                        <legend class="legend">Emergency Case Details</legend>
                                        <div class="fieldset-content-box">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                    <div class="view_col">
                                                        <label>SOC: </label>
                                                        <p class="mb-0">
                                                            <?php if (!empty($case_data['soc_document'])) : ?>
                                                                <a href="<?= base_url(VIEW_EFILING_NF_SOC_UPLOADS_FOLDER . $case_data['soc_document']) ?>" class="btn btn-custom" target="_BLANK">
                                                                    <i class="fa fa-eye"></i> View File
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="mb-0 badge bg-danger">No Document</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                    <div class="view_col">
                                                        <label>Urgency Application: </label>
                                                        <p class="mb-0">
                                                            <?php if (!empty($case_data['urgency_app_document'])) : ?>
                                                                <a href="<?= base_url(VIEW_EFILING_NF_URGENCY_DOC_UPLOADS_FOLDER . $case_data['urgency_app_document']) ?>" class="btn btn-custom" target="_BLANK">
                                                                    <i class="fa fa-eye"></i> View File
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="mb-0 badge bg-danger">No Document</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                                    <div class="view_col">
                                                        <label>Proof of Service: </label>
                                                        <p class="mb-0">
                                                            <?php if (!empty($case_data['proof_of_service_doc'])) : ?>
                                                                <a href="<?= base_url(VIEW_EFILING_NF_POS_UPLOADS_FOLDER . $case_data['proof_of_service_doc']) ?>" class="btn btn-custom" target="_BLANK">
                                                                    <i class="fa fa-eye"></i> View File
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="mb-0 badge bg-danger">No Document</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Arbitrator Status: </label>
                                    <p class="mb-0">
                                        <?= $case_data['arbitration_status_desc'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Case Status: </label>
                                    <p class="mb-0">
                                        <?= $case_data['case_status_dec'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Case Document (Uploaded by Party): </label>
                                    <p class="mb-0">
                                        <?php if (!empty($case_data['nr_case_document'])) : ?>
                                            <a href="<?= base_url(VIEW_EFILING_NEW_REFERENCE_UPLOADS_FOLDER . $case_data['nr_case_document']) ?>" class="btn btn-custom" target="_BLANK">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        <?php else : ?>
                                            <span class="mb-0 badge bg-danger">No Document</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Case Files (Uploaded by DIAC): </label>
                                    <p class="mb-0">
                                        <?php if (!empty($case_data['case_file'])) : ?>

                                            <?php $files = json_decode($case_data['case_file']);
                                            ?>
                                            <?php if ($files && count($files) > 0) : ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn bg-navy dropdown-toggle" data-toggle="dropdown">View Files</button>

                                        <button type="button" class="btn bg-navy dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php foreach ($files as $key => $file) : ?>
                                                <li>
                                                    <a href="<?= base_url() . VIEW_CASE_FILE_UPLOADS_FOLDER . $file ?>" target="_BLANK">
                                                        <i class="fa fa-eye"></i> View File <?= $key + 1 ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                <?php else : ?>
                                    <p>No files are available.</p>
                                <?php endif; ?>

                            <?php else : ?>
                                <span class="mb-0 badge bg-danger">No Document</span>
                            <?php endif; ?>
                            </p>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 form-group">
                                <div class="view_col">
                                    <label>Remarks: </label>
                                    <p class="mb-0">
                                        <?= $case_data['remarks'] ?>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    $(function() {
        $("#accordion").accordion();
    });

    // Common Datatable for all tables
    var cm_dataTable = $('.common-data-table').dataTable({
        sorting: false,
        ordering: false,
        responsive: false,
        searching: false,
        lengthChange: false
    });
</script>