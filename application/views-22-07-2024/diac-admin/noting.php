<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>

	<?php if (count($response_formats) > 0) : ?>
		<div class="content-header" style="margin-bottom: 10px;">
			<!-- Show the generated response formats -->
			<div class="box mb-0">
				<div class="box-body">
					<h4 class="case-title mb-2" style="margin-top: 0px;">
						Draft letters generated in the case
					</h4>
					<ul class="list-unstyled ">
						<?php foreach ($response_formats as $response) : ?>
							<li>
								<a href="<?= base_url('response-format/editable?type=' . $response['letter_type'] . '&case_no=' . $response['case_no'] . '&code=' . $response['code']) ?>" class="btn btn-warning" target="_BLANK"><i class="fa fa-paper-plane-o"></i> <?= (array_key_exists($response['letter_type'], RESPONSE_FORMAT_TYPES)) ? RESPONSE_FORMAT_TYPES[$response['letter_type']] : 'No Name Defined' ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="content-header">
		<!-- Show the case status and stages -->
		<div class="box mb-0">
			<div class="box-body">
				<div class="row">
					<?php if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL'])) : ?>
						<div class="col-md-6 col-xs-12">
							<form action="" id="case_stage_change_form">

								<input type="hidden" id="mcs_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
								<input type="hidden" name="csrf_mcs_form_token" value="<?php echo generateToken('case_stage_change_form'); ?>">

								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12 form-group">
										<label for="">Case Hearing <span class="text-danger">*</span></label>
										<select id="major_case_stage" name="major_case_stage" class="form-control">
											<option value="">Select</option>
											<?php foreach ($major_case_status as $mcs) : ?>
												<option value="<?= $mcs['gen_code'] ?>" <?= (isset($case_details['case_status']) && $case_details['case_status'] == $mcs['gen_code']) ? 'selected' : '' ?>><?= $mcs['description'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="col-md-6 col-sm-6 col-xs-12 form-group">
										<label for="">Case Status <span class="text-danger">*</span></label>
										<select id="minor_case_stage" name="minor_case_stage" class="form-control">
											<option value="">Select</option>
											<?php foreach ($minor_case_status as $mcs) : ?>
												<?php if ($case_details['case_status'] == $mcs['major_stage_code']) : ?>
													<option value="<?= $mcs['code'] ?>" <?= (isset($case_details['minor_case_status']) && $case_details['minor_case_status'] == $mcs['code']) ? 'selected' : '' ?>><?= $mcs['name'] ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="col-xs-12 form-group">
										<label for="">Status Changed On <span class="text-danger">*</span></label>
										<input type="text" name="status_changed_on" id="mcs_status_changed_on" class="form-control custom-all-date" />
									</div>

									<div class="col-xs-12 form-group">
										<label for="">Remarks (If Any)</label>
										<textarea name="remarks" id="mcs_remarks" class="form-control" rows="2"></textarea>
									</div>

									<div class="col-xs-12 text-center">
										<button class="btn btn-custom" type="submit" id="mcs_change_status">
											<i class="fa fa-paper-plane"></i> Change Status
										</button>
									</div>
								</div>
							</form>
						</div>
					<?php endif; ?>
					<div class="col-md-6 col-xs-12">
						<h4 style="display: flex; align-items: center; justify-content: space-between;">
							<strong>Case Status:</strong>
							<button class="btn btn-primary btn-sm" type="button" id="btn_view_status_timeline">
								<i class="fa fa-eye"></i> View Status Timeline
							</button>
						</h4>
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th width="33%">
										<strong>Case Hearing:</strong>
									</th>
									<td>
										<span class="badge bg-green"><?= $case_details['case_status_desc'] ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<strong>Case Status:</strong>
									</th>
									<td>
										<span class="badge bg-green"><?= $case_details['minor_case_status_name'] ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<strong>Status Changed On:</strong>
									</th>
									<td>
										<span><?= (isset($case_details['status_changed_on']) && !empty($case_details['status_changed_on'])) ? formatReadableDate($case_details['status_changed_on']) : '' ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<strong>Remarks:</strong>
									</th>
									<td>
										<span><?= $case_details['case_status_remarks'] ?></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section class="content">
		<div class="box wrapper-box">
			<div class="box-body p-0">
				<div class="row">

					<div class="col-md-8 col-xs-12 pr-0">
						<div class="box box-warning mb-0">
							<div class="box-header with-border">
								<h4 class="box-title">Notings: </h4>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<table id="dataTableNotingList" class="table table-condensed table-bordered" data-page-size="10">
										<input type="hidden" id="csrf_noting_trans_token" value="<?php echo generateToken('dataTableNotingList'); ?>">

										<?php if (isset($case_no)) : ?>
											<input type="hidden" id="noting_case_no" value="<?php echo $case_no; ?>">
										<?php endif; ?>

										<thead>
											<tr>
												<th style="width:7%;">S. No.</th>
												<th style="width: 30%">Noting</th>
												<th>Marked by</th>
												<th>Marked to/File sent</th>
												<th>Date of noting</th>
												<th>Next Date</th>
												<th>Attachment</th>
												<?php // if (in_array($this->session->userdata('role'), ['DIAC', 'DEPUTY_COUNSEL', 'CASE_MANAGER', 'ACCOUNTS', 'COORDINATOR'])) : 
												?>
												<!-- <th></th> -->
												<?php // endif; 
												?>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4 col-xs-12 pl-0">
						<div class="box box-warning mb-0">
							<div class="box-header with-border">
								<h4 class="box-title">Noting Form: </h4>
							</div>
							<div class="box-body">
								<?php require_once(APPPATH . 'views/forms/noting_form.php') ?>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<?php //require_once(APPPATH . 'views/modals/diac-admin/noting.php'); 
?>

<?php require_once(APPPATH . 'views/modals/diac-admin/case_status_timeline_modal.php');  ?>


<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
	var user_name = "<?php echo $this->session->userdata('user_name'); ?>";
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/noting.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/noting.js')) ?>"></script>