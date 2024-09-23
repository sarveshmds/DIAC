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
			<div class="box-body ">
				<div>
					<fieldset class="fieldset">
						<div class="row">

							<div class="col-md-12">
								<div class="form-group col-md-3">
									<label class="control-label">DIAC Registration No.:</label>
									<select class="form-control select2" id="f_cl_case_no" name="f_cl_case_no">
										<option value="">Select</option>
										<?php foreach ($cases_list as $case) : ?>
											<option value="<?= $case['slug'] ?>"><?= get_full_case_number($case) ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3">
									<label class="control-label">Date:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="f_cl_date" name="f_cl_date" autocomplete="off" readonly="true" value="" />
									</div>
								</div>

								<div class="form-group col-md-3">
									<label for="" class="control-label">Room No.</label>
									<select name="f_cl_room_no" id="f_cl_room_no" class="form-control">
										<option value="">Select Room</option>
										<?php foreach ($all_rooms_list as $list) : ?>
											<option value="<?= $list['id'] ?>"><?= $list['room_name'] . ' ' . $list['room_no'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3">
									<button type="button" class="btn btn-default" id="f_cl_btn_reset" name="f_cl_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>

									<button type="submit" class="btn btn-info" id="f_cl_btn_submit" name="f_cl_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
								</div>

							</div>
						</div>
						<div>
						</div>
					</fieldset>
				</div>
				<div class="table-responsive">
					<table id="dataTableCauseList" class="table table-condensed table-striped table-bordered" data-page-size="10">
						<input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCauseList'); ?>">
						<thead>
							<tr>
								<th style="width:7%;">S. No.</th>
								<th>Case No.</th>
								<th>Title of Case</th>
								<th>Arbitrator Name</th>
								<th>Purpose</th>
								<th>Date</th>
								<th>Time</th>
								<th>Room No.</th>
								<th>Status</th>
								<th>Remarks</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
</div>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/all-cause-list.js') ?>"></script>