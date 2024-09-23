<!-- Reminder Modal to show the same structered data -->
<div id="reminder-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title common-modal-title" style="text-align: center;"> Add Reminder</h4>
            </div>
            <div class="modal-body common-modal-body">
                <?= form_open(null, array('class' => 'wfst', 'id' => 'reminder_form')) ?>
                    <input type="hidden" name="reminder_op_type" id="reminder_op_type" value="ADD_REMINDER">
                    <input type="hidden" name="csrf_reminder_form_token" value="<?php echo generateToken('reminder_form'); ?>">

                    <div class="row">

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control custom-all-date" id="reminder_date" name="reminder_date" autocomplete="off" readonly="true" value=""/>
                            </div>
                        </div>

                        <div class="form-group col-xs-12 required">
                            <label class="control-label">Note:</label>
                            <textarea type="text" class="form-control" id="reminder_note" name="reminder_note"  autocomplete="off"></textarea>
                        </div>

                    </div>

                    <div class="row text-center mt-25">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-custom" id="add_reminder_btn"><i class='fa fa-paper-plane'></i> Save</button>
                        </div>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- Reminder Modal End -->