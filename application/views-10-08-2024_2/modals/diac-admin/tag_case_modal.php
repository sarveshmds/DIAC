<!-- Tag case modal start -->
<div id="tag_case_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title op-modal-title" id="tc_modal_title" style="text-align: center;"></h4>
            </div>
            <div class="modal-body">

                <?= form_open(null, array('class' => 'wfst', 'id' => 'tag_case_form')) ?>

                <input type="hidden" id="tc_op_type" name="op_type" value="ADD_TAG_CASE">

                <input type="hidden" id="hidden_tc_id" name="hidden_tc_id" value="">

                <input type="hidden" name="csrf_tc_form_token" value="<?php echo generateToken('tag_case_form'); ?>">

                <div class="row">
                    <div class="form-group  col-xs-12 required">
                        <label class="control-label">Master Case:</label>
                        <select name="tc_master_case" id="tc_master_case" class="form-control select2">
                            <option value="">Select</option>
                            <?php foreach ($cases_list as $case) : ?>
                                <option value="<?= $case['slug'] ?>"><?= get_full_case_number($case) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group  col-xs-12 required">
                        <label class="control-label">Tagged Cases:</label>
                        <select name="tc_tagged_case[]" id="tc_tagged_case" class="form-control select2" multiple>
                            <?php foreach ($cases_list as $case) : ?>
                                <option value="<?= $case['slug'] ?>"><?= get_full_case_number($case) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="row text-center mt-25">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom tc_btn_submit" id="tc_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
                    </div>
                </div>
                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>
<!-- Tag case modal end -->