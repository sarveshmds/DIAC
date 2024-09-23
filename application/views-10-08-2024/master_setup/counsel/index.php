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
                        <fieldset class="fieldset">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group col-md-3">
                                        <label class="control-label">Counsel Name:</label>
                                        <input type="text" name="f_counsel_name" id="f_counsel_name" class="form-control" />
                                    </div>

                                    <div class="form-group col-md-3">
                                        <button type="button" class="btn btn-default" id="f_cl_btn_reset" name="f_cl_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>

                                        <button type="submit" class="btn btn-info" id="f_cl_btn_submit" name="f_cl_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <div class="table-responsive">
                            <table id="counsel_setup_datatable_appr" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
                                <input type="hidden" name="csrf_trans_token" id="arb_form_token" value="<?php echo generateToken('counsel_setup_datatable'); ?>">
                                <thead>
                                    <tr>
                                        <th style="width:8%;">S. No.</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Email</th>
                                        <th width="10%">Contact</th>
                                        <th width="10%">Enrollment No.</th>
                                        <th>Permanent Address</th>
                                        <th>Correspondance Address</th>
                                        <th style="width:15%;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url('public/custom/js/admin/counsel_master.js?v=' . filemtime(FCPATH . 'public/custom/js/admin/counsel_master.js')); ?>"></script>