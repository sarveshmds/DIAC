<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title . ': ' . ucwords($format_name) ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="card" style="margin-bottom: 15px;">
                                <div class="card-body flex-row">
                                    <h5 id="case-number" style="margin-right: 20px;"><strong>Case Number:</strong> <?= $case_details['case_no'] ?></h5>
                                    <h5 id="case-title"><strong>Case Title:</strong> <?= $case_details['case_title'] ?></h5>
                                </div>
                            </div>

                            <?= form_open(null, array('class' => 'wfst', 'id' => 'response_format_form')) ?>

                            <input type="hidden" id="response_op_type" name="op_type" value="OPERATION_RESPONSE_FORMAT">

                            <input type="hidden" id="hidden_case_no" name="hidden_case_no" value="<?= $case_details['cdt_slug'] ?>">

                            <input type="hidden" id="hidden_response_id" name="hidden_response_id" value="">
                            <input type="hidden" id="hidden_option" name="hidden_option" value="">

                            <input type="hidden" name="csrf_response_form_token" value="<?php echo generateToken('response_format_form'); ?>">

                            <div id="response_wrapper">
                                <fieldset class="fieldset response_fieldset" id="response_fieldset">
                                    <legend class="response_legend_head">Response Format</legend>
                                    <div class="fieldset-content-box">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="control-label">To:</label>
                                                <input type="text" class="form-control" id="response_to" name="response_to" autocomplete="off" value="<?= $to_email_ids ?>" />
                                            </div>
                                           <!--  <div class="form-group col-md-4">
                                                <label class="control-label">Cc:</label>
                                                <input type="text" class="form-control" id="response_cc" name="response_cc" autocomplete="off" value="<?= $cc_email_ids ?>" />
                                                <small class="text-muted">Enter comma seperated e-mail ids if more than one.</small>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="control-label">Bcc:</label>
                                                <input type="text" class="form-control" id="response_bcc" name="response_bcc" autocomplete="off" value="<?= $bcc_email_ids ?>" />
                                                <small class="text-muted">Enter comma seperated e-mail ids if more than one.</small>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-12 required">
                                                <label class="control-label">Subject:</label>
                                                <input type="text" class="form-control" id="response_subject" name="response_subject" autocomplete="off" maxlength="200" value="<?= $subject ?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-12 required">
                                                <label for="">Body</label>
                                                <textarea type="text" class="form-control response-summernote-text-editor" id="response_body" name="response_body" autocomplete="off" value=""><?= $format ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>

                            <div class="row text-center mt-25">
                                <div class="col-xs-12">
                                    <button type="reset" class="btn btn-danger" id="btnSaveAndDownload"><i class="fa fa-save"></i> Draft & Download</button>
                                    <!-- <button type="submit" class="btn btn-custom response_btn_submit" id="btnSaveAndSend"><i class='fa fa-paper-plane'></i> Send Mail</button> -->
                                </div>
                            </div>
                            <?= form_close() ?>

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
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/response-format.js') ?>"></script>