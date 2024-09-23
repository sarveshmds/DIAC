<!-- Noting Modal start -->
<div id="miscellaneous_reply_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;">Miscellaneous Reply</h4>
            </div>
            <div class="modal-body">

                <?= form_open(null, array('class' => 'wfst', 'id' => 'miscellaneous_reply_form')) ?>

                <input type="hidden" id="miscellaneous_reply_op_type" name="op_type" value="ADD_MISCELLANEOUS_REPLIES">
                <input type="hidden" id="hidden_miscellaneous_id" name="hidden_miscellaneous_id" value="<?= $miscellaneous_id ?>">

                <input type="hidden" name="csrf_miscellaneous_reply_form_token" value="<?php echo generateToken('miscellaneous_reply_form'); ?>">

                <div id="fr_wrapper">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 required">
                            <label class="control-label">Reply:</label>
                            <textarea class="form-control customized-summernote" id="miscellaneous_reply" name="miscellaneous_reply"></textarea>
                        </div>

                        <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                            <label class="control-label">Reply To:</label>
                            <select name="miscellaneous_reply_to" id="miscellaneous_reply_to" class="form-control miscellaneous_reply_appearing_for select2">
                                <option value="">Select Option</option>
                                <?php foreach ($reply_to_user as $user) : ?>
                                    <?php if ($user['user_code'] != $this->session->userdata('user_code')) : ?>
                                        <option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'], ' (' . $user['job_title'] . ')' ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </select>
                        </div>

                    </div>
                </div>

                <div class="row text-center mt-25">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom miscellaneous_reply_btn_submit" id="miscellaneous_reply_btn_submit"><i class='fa fa-paper-plane'></i> Send Reply</button>
                    </div>
                </div>
                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>
<!-- Noting Modal end -->