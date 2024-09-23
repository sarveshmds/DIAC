<!-- Modal start -->
<div id="on_content__modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;">
                    <?= $sub_title ?>

                </h4>
            </div>
            <div class="modal-body">

                <div id="fr_wrapper">
                    <?php if (isset($new_reference)) : ?>
                        <?php require_once(APPPATH . 'views/templates/components/ims_new_ref_view.php'); ?>
                    <?php endif; ?>

                    <?php if (isset($misc_data)) : ?>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="type_of_arbitration">Submitted By</label>
                                <p class="mb-0">
                                    <?= $misc_data['name'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Person's Phone Number</label>
                                <p class="mb-0">
                                    <?= $misc_data['phone_number'] ?>
                                </p>
                            </div>

                            <div class="col-sm-12 col-xs-12 form-group">
                                <label for="case_type">Message/Remarks (Added by case filer)</label>
                                <p class="mb-0">
                                    <?= $misc_data['message'] ?>
                                </p>
                            </div>

                            <div class="col-sm-12 col-xs-12 form-group">
                                <label for="upload_document">Documents/Orders</label>

                                <div class="mb-0">
                                    <?php $files = json_decode($misc_data['document']); ?>
                                    <?php if (count($files) > 0) : ?>
                                        <?php $fileBtn = ''; ?>
                                        <div class="misc_files_wrapper">
                                            <?php foreach ($files as $key => $file) : ?>
                                                <div class="misc_files_wrapper_col">
                                                    <object data="<?= base_url('public/upload/miscellaneous_documents/' . $file) ?>" type="application/pdf"></object>
                                                    <a href="<?= base_url('public/upload/miscellaneous_documents/' . $file) ?>" target="_BLANK" class="btn btn-custom btn-sm">View Document <?= $key + 1 ?></a> &nbsp;
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($document_pleadings)) : ?>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="type_of_arbitration">Diary No.</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['diary_number'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Case No.</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['case_no_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Case Title</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['case_title'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Type of Document</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['type_of_document_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Behalf Of</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['behalf_of_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Name</label>
                                <p class="mb-0">
                                    <?= $document_pleadings['name'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="upload_document">Document</label>
                                <p class="mb-0">
                                    <a href="<?= base_url('public/upload/efiling/documents/' . $document_pleadings['upload']) ?>" class="btn btn-custom" target="_BLANK">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($application)) : ?>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="type_of_arbitration">Diary No.</label>
                                <p class="mb-0">
                                    <?= $application['diary_number'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Case No.</label>
                                <p class="mb-0">
                                    <?= $application['case_no_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Case Title</label>
                                <p class="mb-0">
                                    <?= $application['case_title'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Application Type</label>
                                <p class="mb-0">
                                    <?= $application['app_type_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Filing Type</label>
                                <p class="mb-0">
                                    <?= $application['filing_type_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Behalf Of</label>
                                <p class="mb-0">
                                    <?= $application['behalf_of_desc'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="case_type">Name</label>
                                <p class="mb-0">
                                    <?= $application['name'] ?>
                                </p>
                            </div>

                            <div class="col-sm-6 col-xs-12 form-group">
                                <label for="upload_document">Document</label>
                                <p class="mb-0">
                                    <a href="<?= base_url('public/upload/efiling/documents/' . $application['upload']) ?>" class="btn btn-custom" target="_BLANK">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal end -->