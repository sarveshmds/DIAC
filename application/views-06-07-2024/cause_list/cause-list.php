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

				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true">
								Today's Cause List
							</a></li>
						<li class="">
							<a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
								Overall Cause List
							</a>
						</li>
						<li class="">
							<a href="#tab_3" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
								DIAC Cause List
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="table-responsive">
								<table id="dataTableTodaysCauseList" class="table table-condensed table-striped table-bordered">
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
											<th>Mode of hearing</th>
											<th>Room No.</th>
											<th>Meeting Link</th>
											<th>Status</th>
											<th>Remarks</th>
											<!-- <th>Action</th> -->
										</tr>
									</thead>
								</table>
							</div>
						</div>

						<div class="tab-pane" id="tab_2">

							<!-- Filter Box -->
							<div>
								<fieldset class="fieldset">
									<div class="row">

										<div class="col-md-12">
											<div class="form-group col-md-3">
												<label class="control-label">DIAC Registration No.:</label>
												<select class="form-control select2" id="f_user_cl_case_no" name="f_user_cl_case_no">
													<option value="">Select</option>
													<?php foreach ($alloted_cases_list as $case) : ?>
														<option value="<?= $case['slug'] ?>"><?= get_full_case_number($case) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group col-md-3">
												<label class="control-label">Date:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="f_user_cl_date" name="f_user_cl_date" autocomplete="off" readonly="true" value="" />
												</div>
											</div>

											<div class="form-group col-md-3">
												<button type="button" class="btn btn-default" id="f_user_cl_btn_reset" name="f_cl_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>

												<button type="submit" class="btn btn-info" id="f_user_cl_btn_submit" name="f_cl_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
											</div>

										</div>
									</div>
									<div>
									</div>
								</fieldset>
							</div>


							<div class="table-responsive">
								<table id="dataTableYoursCauseList" class="table table-condensed table-striped table-bordered">
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
											<th>Mode of hearing</th>
											<th>Room No.</th>
											<th>Meeting Link</th>
											<th>Status</th>
											<th>Remarks</th>
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>

						<div class="tab-pane" id="tab_3">
							<!-- Filter Box -->
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

								</fieldset>
							</div>

							<div class="table-responsive">
								<table id="dataTableCauseList" class="table table-striped table-bordered">
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
											<th>Mode of hearing</th>
											<th>Room No.</th>
											<th>Meeting Link</th>
											<th>Status</th>
											<th>Remarks</th>
											<!-- <th>Action</th> -->
										</tr>
									</thead>
								</table>
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

	// Timepicker initialization
	$('.timepicker').timepicker({
		showInputs: false
	});
</script>

<?php if (in_array($this->session->userdata('role'), array('CAUSE_LIST_MANAGER', 'DIAC', 'ADMIN', 'DEPUTY_COUNSEL'))) : ?>
	<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/cause-list.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/cause-list.js')) ?>"></script>
<?php else : ?>
	<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/cause-list-view-mode.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/cause-list-view-mode.js')) ?>"></script>
<?php endif; ?>