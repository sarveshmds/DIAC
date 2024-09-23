<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">

						<!-- Filter Box -->
						<div class="row">
							<div class="col-md-12">
								<fieldset class="fieldset">
									<div class="col-md-12">
										<div class="row">

											<div class="col-md-12">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">DIAC Registration No.:</label>
													<input type="text" class="form-control" id="ca_f_case_no" name="ca_f_case_no" autocomplete="off" maxlength="100" placeholder="Enter Case Number" />
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Allotted to:</label>
													<select name="ca_f_allotted_to" id="ca_f_allotted_to" class="form-control ca_allotted_to select2">
														<option value="">Select Option</option>
														<?php foreach ($all_diac_users as $user) : ?>
															<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
														<?php endforeach; ?>

													</select>
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12 mt-24">
													<button type="button" class="btn btn-default" id="ca_reset_btn" name="ca_f_btn"><i class='fa fa-refresh'></i> Reset</button>
													<button type="submit" class="btn btn-info" id="ca_f_btn" name="ca_f_btn"><i class='fa fa-filter'></i> Filter</button>
												</div>

											</div>
										</div>
									</div>
								</fieldset>
							</div>
						</div>

						<div>

							<table id="dataTableCaseAllotmentList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
								<input type="hidden" id="csrf_ca_trans_token" value="<?php echo generateToken('dataTableCaseAllotmentList'); ?>">

								<?php if (isset($case_no)) : ?>
									<input type="hidden" id="noting_case_no" value="<?php echo $case_no; ?>">
								<?php endif; ?>

								<thead>
									<tr>
										<th style="width:7%;">S. No.</th>
										<th style="width: 20%">DIAC Registration No.</th>
										<th>Case Title</th>
										<th>Allotted To</th>
										<th>User Role</th>
										<th>Allotted On</th>
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

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/case-allotment.php'); ?>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/case-allotment.js') ?>"></script>