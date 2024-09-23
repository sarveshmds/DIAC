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
                                <div class="">
                                    <?php echo form_open(null, array('class' => 'wfst', 'id' => 'form_add_cause_list', 'enctype' => "multipart/form-data")); ?>
                                    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                                    <input type="hidden" id="cause_list_op_type" name="op_type" value="ADD_CAUSE_LIST_FORM">
                                    <input type="hidden" id="cause_list_hidden_id" name="hidden_id" value="">
                                    <input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_cause_list'); ?>">

                                    <div class="row">
                                        <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                            <label class="control-label">Date of Hearing:</label>
                                            <div class="input-group date_of_hearing">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <input type="text" class="form-control" id="date_of_hearing" name="date" autocomplete="off" readonly="true" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                            <div class="bootstrap-timepicker">
                                                <label for="" class="control-label">Time (From):</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                                    <input type="text" class="form-control timepicker" id="time_from" name="time_from" autocomplete="off" readonly="true" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
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
                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
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
                                                        <option value="">Select</option>
                                                        <?php foreach ($all_case_list as $case_list) : ?>
                                                            <option value="<?= $case_list['slug'] ?>"><?= $case_list['case_title'] . ' - ' . get_full_case_number($case_list) ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Case No:</label>
                                                    <input type="text" class="form-control str_to_uppercase" id="case_no" name="case_no" autocomplete="off" maxlength="50" />
                                                </div>

                                                <div class="form-group col-md-6 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Title of Case:</label>
                                                    <input type="text" class="form-control" id="title_of_case" name="title_of_case" autocomplete="off" maxlength="250" />
                                                </div>

                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                                    <label class="control-label">Arbitrator's Name:</label>
                                                    <p id="arbitrator_name">

                                                    </p>
                                                    <!-- <select class="form-control" id="arbitrator_name" name="arbitrator_name">
                                                        <option value="">Select</option>
                                                    </select> -->
                                                    <!-- <input type="text" class="form-control" id="arbitrator_name" name="arbitrator_name"  autocomplete="off" maxlength="150"/> -->
                                                </div>

                                                <div class="form-group col-md-4 col-sm-6 col-xs-12 required">
                                                    <label for="" class="control-label">Purpose</label>
                                                    <select class="form-control" id="purpose" name="purpose">
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($purpose_category as $puc) : ?>
                                                            <option value="<?= $puc['id'] ?>"><?= $puc['category_name'] ?></option>
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