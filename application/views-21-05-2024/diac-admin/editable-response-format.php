<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title . ': ' . ucwords($format_name) ?></h1>
    </section>
    <section class="content">
        <div class="">
            <div class="box" style="margin-bottom: 15px;">
                <div class="box-body flex-row">
                    <h5 id="case-number" style="margin-right: 20px;"><strong>Case Number:</strong> <?= get_full_case_number($case_details) ?></h5>
                    <h5 id="case-title"><strong>Case Title:</strong> <?= $case_details['case_title'] ?></h5>
                </div>
            </div>

            <?php if (isset($response_format) && $response_format['code']) : ?>
                <!-- Give URL to view the draft letter -->
                <div class="box box-body" style="margin-bottom: 15px;">
                    <strong>URL for draft letter PDF:</strong> <a href="<?= base_url('response-format/generate-and-download?code=' . $response_format['code']) ?>" target="_BLANK"><?= base_url('response-format/generate-and-download?code=' . $response_format['code']) ?></a>
                </div>
            <?php endif; ?>

            <?php if ((isset($created_response_format) && $created_response_format) && (!isset($response_format))) : ?>
                <div class="box box-body" style="margin-bottom: 15px;">
                    <strong class="text-red">ALERT:</strong> <strong>Draft letter for this is already created, please go to edit the letter in case you want to change anything.</strong>
                    <a href="<?= base_url('response-format/editable?type=' . $created_response_format['letter_type'] . '&case_no=' . $created_response_format['case_no'] . '&code=' . $created_response_format['code']) ?>" class="text-blue" target="_BLANK"><u>Click here to go to edit.</u></a>
                </div>
            <?php endif; ?>

            <div class="box">
                <div class="box-body">

                    <!-- <?php base_url('response-format/generate-and-download') ?> -->
                    <form action="" class="wfst" id="response_format_form">
                        <input type="hidden" id="response_op_type" name="op_type" value="OPERATION_RESPONSE_FORMAT">

                        <input type="hidden" id="hidden_case_no" name="hidden_case_no" value="<?= $case_details['cdt_slug'] ?>">

                        <?php if (isset($response_format) && $response_format['code']) : ?>
                            <input type="hidden" id="hidden_response_code" name="hidden_response_code" value="<?= $response_format['code'] ?>">
                        <?php endif; ?>

                        <input type="hidden" id="hidden_option" name="hidden_option" value="<?= (isset($response_format['code'])) ? 'UPDATE_DRAFT_LETTER' : 'SAVE_DRAFT_LETTER' ?>">

                        <input type="hidden" id="letter_type" name="letter_type" value="<?= $letter_type ?>">

                        <input type="hidden" name="csrf_response_form_token" value="<?php echo generateToken('response_format_form'); ?>">

                        <div id="response_wrapper">
                            <div class="row">
                                <div class="form-group col-xs-12 required">
                                    <textarea type="text" class="form-control response_format_text_editor" id="response_body" name="response_body" autocomplete="off" value=""><?= $format ?></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="row text-center mt-25">
                            <div class="col-xs-12">
                                <!-- <button type="submit" class="btn btn-danger" id="btnSaveAndDownload"><i class="fa fa-save"></i> Draft & Download</button> -->

                                <button type="submit" class="btn btn-custom response_btn_submit" id="btnSaveDraftLetter"><i class='fa fa-paper-plane'></i> Save Draft Letter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</div>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/response-format.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/response-format.js')) ?>"></script>