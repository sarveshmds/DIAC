<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<style>
    .pdf_button {
        background-color: red;
        color: white;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box wrapper-box">
                    <div class="box-body">
                        <?= form_open_multipart(null, array('class' => 'wfst', 'id' => 'counsel_master_form')) ?>
                        <input type="hidden" name="crn_hidden_id" id="crn_hidden_id" value="<?= isset($counsel_details['id']) ? $counsel_details['id'] : '' ?>">
                        <input type="hidden" id="op_type" name="op_type" value="<?php echo (isset($edit_form) && $edit_form == true) ? 'EDIT_COUNSEL_MASTER' : 'ADD_COUNSEL_MASTER'; ?>">
                        <input type="hidden" name="csrf_counsel_master_token" value="<?php echo generateToken('counsel_master_form'); ?>">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Name:</label>
                                <input type="text" name="name" id="name" value="<?= isset($counsel_details['name']) ? $counsel_details['name'] : '' ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Enrollment No:</label>
                                <input type="text" name="enrollment_no" id="enrollment_no" value="<?= isset($counsel_details['enrollment_no']) ? $counsel_details['enrollment_no'] : '' ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Email Address:</label>
                                <input type="email" name="email" id="email" value="<?= isset($counsel_details['email']) ? $counsel_details['email'] : '' ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Phone Number:</label>
                                <input type="text" name="phone_number" id="phone_number" value="<?= isset($counsel_details['phone_number']) ? $counsel_details['phone_number'] : '' ?>" class="form-control">
                            </div>
                            <div class="col-xs-12">
                                <fieldset>
                                    <legend>Permanent Address</legend>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Address 1:</label>
                                        <input type="text" name="perm_add_1" id="perm_add_1" value="<?= isset($counsel_details['perm_address_1']) ? $counsel_details['perm_address_1'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Address 2:</label>
                                        <input type="text" name="perm_add_2" id="perm_add_2" value="<?= isset($counsel_details['perm_address_2']) ? $counsel_details['perm_address_2'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Country:</label>
                                        <select class="form-control" name="perm_country" id="perm_country">
                                            <option value="">Select Country</option>
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?= $country['iso2'] ?>" <?= isset($counsel_details['perm_country']) && $country['iso2'] == $counsel_details['perm_country'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Select State</label>
                                        <select class="form-control" name="perm_state" id="perm_state">
                                            <option>Select State</option>
                                            <?php if ($counsel_details['perm_state'] && $counsel_details['perm_state'] !== '') : ?>
                                                <?php foreach ($get_perm_state as $state) : ?>
                                                    <option value="<?= $state['id'] ?>" <?= isset($counsel_details['perm_state']) && $counsel_details['perm_state'] == $state['id'] ? 'selected' : '' ?>><?= $state['name'] ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Pincode:</label>
                                        <input type="text" name="perm_pincode" id="perm_pincode" value="<?= isset($counsel_details['perm_pincode']) ? $counsel_details['perm_pincode'] : '' ?>" class="form-control">
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Correspondence Address</legend>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Address 1:</label>
                                        <input type="text" name="corr_add_1" id="corr_add_1" value="<?= isset($counsel_details['corr_address_1']) ? $counsel_details['corr_address_1'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Address 2:</label>
                                        <input type="text" name="corr_add_2" id="corr_add_2" value="<?= isset($counsel_details['corr_address_2']) ? $counsel_details['corr_address_2'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Country:</label>
                                        <select class="form-control" name="corr_country" id="corr_country">
                                            <option value="">Select Country</option>
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?= $country['iso2'] ?>" <?= isset($counsel_details['corr_country']) && $country['iso2'] == $counsel_details['corr_country'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Select State</label>
                                        <select class="form-control" name="corr_state" id="corr_state">
                                            <option>Select State</option>
                                            <?php if ($counsel_details['corr_state'] && $counsel_details['corr_state'] !== '') : ?>
                                                <?php foreach ($get_corr_state as $state) : ?>
                                                    <option value="<?= $state['id'] ?>" <?= isset($counsel_details['corr_state']) && $counsel_details['corr_state'] == $state['id'] ? 'selected' : '' ?>><?= $state['name'] ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-6">
                                        <label class="control-label">Pincode:</label>
                                        <input type="text" name="corr_pincode" id="corr_pincode" value="<?= isset($counsel_details['corr_pincode']) ? $counsel_details['corr_pincode'] : '' ?>" class="form-control">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Approved:</label>
                                <select class="form-control" name="approved" id="approved">
                                    <option value="">Select</option>
                                    <option value="1" <?= isset($counsel_details['approved']) && $counsel_details['approved'] == 1 ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= isset($counsel_details['approved']) && $counsel_details['approved'] == 0 ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="row text-center mt-25">
                            <div class="col-xs-12">
                                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                                <button type="submit" class="btn btn-custom counsel_master_btn_submit" id="counsel_master_btn_submit"><i class='fa fa-paper-plane'></i> <?= isset($edit_form) && $edit_form == true ? 'Update Details' : 'Save Details'; ?></button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url(); ?>public/custom/js/admin/counsel_master.js"></script>