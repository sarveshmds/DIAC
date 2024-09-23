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
        <div class="box wrapper-box">
            <div class="box-body p-0">
                <div class="row">
                    <div class="col-md-8 col-12 pr-0">
                        <div class="box box-warning mb-0">
                            <div class="box-body">
                                <div class="alert alert-danger">
                                    <strong>Note: </strong> It will re-schedule the tagged cases (if any) cause list also.
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12 form-group">
                                            <label for="">Current Date of Hearing:</label>
                                            <p class="mb-0">
                                                <?= formatReadableDate($causelist_details['date']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 form-group">
                                            <label for="">Timings:</label>
                                            <p class="mb-0">
                                                <?= $causelist_details['time_from'] ?> - <?= $causelist_details['time_to'] ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6 col-xs-12 form-group">
                                            <label for="">Mode of hearing:</label>
                                            <p class="mb-0">
                                                <?= $causelist_details['mode_of_hearing'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <?php echo form_open(null, array('class' => 'wfst', 'id' => 'form_reschedule_cause_list', 'enctype' => "multipart/form-data")); ?>
                                    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                                    <input type="hidden" id="cause_list_op_type" name="op_type" value="RESCHEDULE_CAUSE_LIST_FORM">
                                    <input type="hidden" id="cause_list_hidden_id" name="cause_list_hidden_id" value="<?= $causelist_details['id'] ?>">
                                    <input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_cause_list'); ?>">

                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                            <label class="control-label">Date of Hearing:</label>
                                            <div class="input-group date_of_hearing">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" class="form-control" id="date_of_hearing" name="date" autocomplete="off" readonly="true" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                            <label for="" class="control-label">Mode of hearing:</label>
                                            <select class="form-control" id="mode_of_hearing" name="mode_of_hearing">
                                                <option value="">Select</option>
                                                <option value="PHYSICAL">Physical</option>
                                                <option value="ONLINE">Online</option>
                                                <option value="HYBRID">Hybrid</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                            <div class="bootstrap-timepicker">
                                                <label for="" class="control-label">Time (From):</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                    <input type="text" class="form-control timepicker" id="time_from" name="time_from" autocomplete="off" readonly="true" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                            <div class="bootstrap-timepicker">
                                                <label for="" class="control-label">Time (To):</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                    <input type="text" class="form-control timepicker" id="time_to" name="time_to" autocomplete="off" />
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-xs-12 text-center form-group">
                                            <button type="button" class="btn btn-primary" id="btn_check_availability">
                                                <i class="fa fa-check"></i> Check Availability
                                            </button>
                                        </div>

                                        <div class="col-xs-12 hearing_further_details_col" style="display: none;">
                                            <div class="row">
                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required room_no_col">
                                                    <label for="" class="control-label">Room No.</label>
                                                    <select name="room_no" id="room_no" class="form-control">
                                                        <option value="">Select Room</option>
                                                        <?php foreach ($all_rooms_list as $list) : ?>
                                                            <option value="<?= $list['id'] ?>"><?= $list['room_name'] . ' (' . $list['room_no'] . ')' ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-12 col-xs-12 required">
                                                    <label class="control-label">Cases List</label>
                                                    <select class="js-example-basic-single case_list" name="case_list" id="case_list">
                                                        <?php foreach ($alloted_cases_list as $case_list) : ?>
                                                            <?php if ($case_list['slug'] == $case_details['slug']) : ?>
                                                                <option value="<?= $case_list['slug'] ?>" selected><?= $case_list['case_title'] . ' - ' . get_full_case_number($case_list) ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Case No:</label>
                                                    <input type="text" class="form-control str_to_uppercase" id="case_no" name="case_no" autocomplete="off" maxlength="50" value="<?= get_full_case_number($case_details) ?>" />
                                                </div>

                                                <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Title of Case:</label>
                                                    <input type="text" class="form-control" id="title_of_case" name="title_of_case" autocomplete="off" maxlength="250" value="<?= $case_details['case_title'] ?>" />
                                                </div>

                                                <div class="form-group col-md-12 col-xs-12 required hearing_meeting_link_col" style="display: none;">
                                                    <label class="control-label">Hearing Meeting Link:</label>
                                                    <input type="text" class="form-control" id="hearing_meeting_link" name="hearing_meeting_link" autocomplete="off" maxlength="500" value="<?= $causelist_details['hearing_meeting_link'] ?>" />
                                                </div>

                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Arbitrator's Name:</label>
                                                    <p id="arbitrator_name">
                                                        <?php foreach ($arbitrators as $key => $arb) : ?>
                                                            <?= $arb['name_of_arbitrator'] ?> (<?= $arb['category_name'] ?>)
                                                            <?= ($key < count($arbitrators)) ? ',' : ''  ?>
                                                        <?php endforeach; ?>
                                                    </p>
                                                </div>

                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                                    <label for="" class="control-label">Purpose</label>
                                                    <select class="form-control" id="purpose" name="purpose">
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($purpose_category as $puc) : ?>
                                                            <option value="<?= $puc['id'] ?>" <?= ($causelist_details['purpose_cat_id'] == $puc['id']) ? 'selected' : '' ?>><?= $puc['category_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                                    <label class="control-label">Remarks:</label>
                                                    <input type="text" class="form-control" id="remarks" name="remarks" autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                                                <button type="submit" class="btn btn-custom" id="cause_list_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
                                            </div>
                                        </div>

                                    </div>

                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 pl-0">
                        <div class="box box-warning" style="box-shadow: unset;margin-left: 5px;">
                            <div class="box-header">
                                <h4 class="box-title">Slots</h4>
                            </div>
                            <div class="box-body" style="max-height: calc(100vh - 158px); overflow-y: auto;">
                                <div id="hearings_list_wrapper"><i class="fa fa-info-circle"></i> Please select date to check hearings for that date.</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/cause-list.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/cause-list-cancel.php'); ?>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<?php if (in_array($this->session->userdata('role'), array('CAUSE_LIST_MANAGER', 'DIAC', 'ADMIN', 'DEPUTY_COUNSEL'))) : ?>
    <script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/cause-list.js') ?>"></script>
<?php else : ?>
    <script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/cause-list-view-mode.js') ?>"></script>
<?php endif; ?>