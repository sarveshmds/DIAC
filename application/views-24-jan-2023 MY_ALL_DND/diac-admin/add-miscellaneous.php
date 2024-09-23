<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <div>

                            <?= form_open(null, array('class' => 'wfst', 'id' => 'miscellaneous_form')) ?>

                            <input type="hidden" id="miscellaneous_op_type" name="op_type" value="<?= ((isset($id))) ? 'EDIT_MISCELLANEOUS' : 'ADD_MISCELLANEOUS' ?>">

                            <?php if (isset($id)) : ?>
                                <input type="hidden" id="hidden_miscellaneous_id" name="hidden_miscellaneous_id" value="<?= $id ?>">
                            <?php endif; ?>
                            <input type="hidden" name="csrf_miscellaneous_form_token" value="<?php echo generateToken('miscellaneous_form'); ?>">

                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <label class="control-label">Documents:</label>
                                    <input type="file" class="form-control documents-input" id="miscellaneous_documents" name="miscellaneous_documents[]" autocomplete="off" multiple accept=".pdf, .docx, .doc" value="" multiple />
                                    <input type="hidden" name="hidden_miscellaneous_documents" class="multiple_miscellaneous_initial_preview" value='<?= ((isset($miscellaneous['document']))) ? $miscellaneous['document'] : '' ?>'>
                                </div>

                                <div class="form-group col-md-12 required col-sm-12 col-xs-12">
                                    <label class="control-label">Message:</label>
                                    <textarea class="form-control" id="miscellaneous_message" name="miscellaneous_message" autocomplete="off" rows="4"><?= ((isset($miscellaneous['message']))) ? $miscellaneous['message'] : '' ?></textarea>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                    <label class="control-label">Marked To:</label>
                                    <select class="form-control select2" id="miscellaneous_marked_to" name="miscellaneous_marked_to[]" multiple>
                                        <?php foreach ($users as $user) : ?>
                                            <?php if (count($miscellaneous_marked_to) > 0) : ?>
                                                <option value="<?php echo $user['user_code']; ?>" <?= (in_array($user['user_code'], array_column($miscellaneous_marked_to, 'user_code'))) ? 'selected' : ''; ?>><?php echo $user['user_display_name']; ?></option>
                                            <?php else : ?>
                                                <option value="<?php echo $user['user_code']; ?>"><?php echo $user['user_display_name']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">Submitted By (Name):</label>
                                    <input type="text" class="form-control" id="miscellaneous_name" name="miscellaneous_name" autocomplete="off" maxlength="200" value="<?= ((isset($miscellaneous['name']))) ? $miscellaneous['name'] : '' ?>" />
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">Person's Phone Number:</label>
                                    <input type="text" class="form-control" id="miscellaneous_phone_number" name="miscellaneous_phone_number" autocomplete="off" maxlength="10" value="<?= ((isset($miscellaneous['phone_number']))) ? $miscellaneous['phone_number'] : '' ?>" />
                                </div>

                            </div>

                            <div class="row text-center mt-25">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-custom miscellaneous_btn_submit" id="btn_save"><i class='fa fa-paper-plane'></i> Save Details</button>
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
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/miscellaneous.js') ?>"></script>