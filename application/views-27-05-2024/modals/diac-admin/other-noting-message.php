<!-- message Modal start -->
<div id="onm_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title message-modal-title" style="text-align: center;">Send Message</h4>
            </div>
            <div class="modal-body">

                <?= form_open(null, array('class' => 'wfst', 'id' => 'message_form')) ?>

                <input type="hidden" id="message_op_type" name="op_type" value="ADD_ON_MESSAGE">

                <?php if (isset($on_code)) : ?>
                    <input type="hidden" id="message_hidden_on_code" name="hidden_on_code" value="<?php echo $on_code; ?>">
                <?php endif; ?>

                <input type="hidden" name="csrf_message_form_token" value="<?php echo generateToken('case_message_form'); ?>">

                <div id="fr_wrapper">
                    <div class="row">

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 required">
                            <label class="control-label">Message:</label>
                            <textarea class="form-control customized-summernote" id="message_text" name="message_text"></textarea>
                        </div>

                        <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                            <label class="control-label">Send to:</label>
                            <select name="send_to" id="send_to" class="form-control select2">
                                <option value="">Select Option</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'], ' (' . $user['job_title'] . ')' ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                    </div>

                </div>

                <div class="row text-center mt-25">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom message_btn_submit" id="message_btn_submit"><i class='fa fa-paper-plane'></i> Send</button>
                    </div>
                </div>
                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>
<!-- message Modal end -->