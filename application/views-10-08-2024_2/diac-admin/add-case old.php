<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<section class="content-header">

		<h1 style="display: inline-block;"><?php echo (isset($edit_form) && $edit_form == true) ? '<span class="fa fa-edit"></span> ' : '<span class="fa fa-plus"></span> '; ?><?= $page_title ?></h1>

		<a href="Javascript:void(0)" onclick="history.back()" class="btn btn-default pull-right"><span class="fa fa-backward"></span> Go Back</a>

	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">

					<div class="box-body">
						<div class="col-lg-12">
							<?= form_open_multipart(null, array('class' => 'wfst', 'id' => 'case_det_form')) ?>

							<input type="hidden" id="op_type" name="op_type" value="<?php echo (isset($edit_form) && $edit_form == true) ? 'EDIT_CASE_DETAILS' : 'ADD_CASE_DETAILS'; ?>">
							<input type="hidden" id="hidden_case" name="hidden_case" value="<?= (isset($case_data['cdt_slug'])) ? $case_data['cdt_slug'] : '' ?>">
							<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('case_det_form'); ?>">

							<div class="row">

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label for="" class="control-label">Case Type:</label>

									<select name="cd_case_type" id="cd_case_type" class="form-control">
										<option value="">Select</option>
										<?php foreach ($case_types as $cs) : ?>
											<option value="<?= $cs['gen_code'] ?>" <?= (isset($case_data['case_type']) && $cs['gen_code'] == $case_data['case_type']) ? 'selected' : '' ?>><?= $cs['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label for="" class="control-label">Diary No.:</label>
									<input type="hidden" name="hidden_reference_code" id="hidden_reference_code" value="<?= (isset($case_data['reference_code'])) ? $case_data['reference_code'] : '' ?>">
									<select name="cd_diary_number" id="cd_diary_number" class="form-control select2">
										<!-- <option value="">Select Option</option> -->
									</select>
								</div>

								<!-- <div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">DIAC Registration No:</label> -->
								<!-- <input type="text" class="form-control str_to_uppercase" id="cd_case_no" name="cd_case_no" autocomplete="off" maxlength="30" value="<?= (isset($case_data['case_no'])) ? $case_data['case_no'] : '' ?>" <?php echo (isset($case_data['case_no'])) ? 'readonly' : ''; ?> onkeyup="stringToUpperCase('cd_case_no')" /> -->

								<!-- <input type="text" id="cd_case_no" name="cd_case_no" autocomplete="off" maxlength="30" value="<?= (isset($case_data['case_no'])) ? $case_data['case_no'] : '' ?>" <?php echo (isset($case_data['case_no'])) ? 'readonly' : ''; ?> onkeyup="stringToUpperCase('cd_case_no')" class="form-control str_to_uppercase" placeholder="DIAC/____/__-__" data-slots="_" data-accept="\d" />

									<small class="d-block text-danger" id="case_no_verification"></small> -->
								<!-- </div> -->

								<div class="form-group col-md-6 col-sm-6 col-xs-12 required">
									<label class="control-label">Case Title:</label>
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<input type="text" class="form-control" id="cd_case_title_claimant" placeholder="Enter claimant name" name="cd_case_title_claimant" autocomplete="off" maxlength="250" value="<?= (isset($case_data['case_title_claimant_name'])) ? $case_data['case_title_claimant_name'] : '' ?>" />
											<small class="text-muted d-block">Claimant Name</small>
										</div>
										<div class="col-sm-6 col-xs-12">
											<input type="text" class="form-control" id="cd_case_title_respondent" name="cd_case_title_respondent" autocomplete="off" maxlength="250" value="<?php if (isset($case_data['case_title_respondent_name'])) {
																																																echo $case_data['case_title_respondent_name'];
																																															} ?>" placeholder="Enter respondent name" />
											<small class="text-muted d-block">Respondent Name</small>
										</div>
									</div>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of reference:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="cd_reffered_on" name="cd_reffered_on" autocomplete="off" readonly="true" value="<?php echo (isset($case_data['reffered_on'])) ? formatDatepickerDate($case_data['reffered_on']) : date('d-m-Y'); ?>" />
									</div>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label for="" class="control-label">Mode of reference:</label>
									<select name="cd_reffered_by" id="cd_reffered_by" class="form-control">

										<option value="">Select Option</option>
										<?php foreach ($reffered_by as $rb) : ?>
											<option value="<?= $rb['gen_code'] ?>" <?= (isset($case_data['reffered_by']) && $rb['gen_code'] == $case_data['reffered_by']) ? 'selected' : '' ?>><?= $rb['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-judge-name-col" <?=
																											(isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"'
																											?>>
									<label for="" class="control-label">Name of Judges:</label>
									<input type="text" name="cd_reffered_by_judge" id="cd_reffered_by_judge" class="form-control" value="
									<?php if (isset($case_data['reffered_by_judge'])) {
										echo $case_data['reffered_by_judge'];
									} ?>">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($case_data['reffered_by']) && !empty($case_data['reffered_by']) && $case_data['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
									<label for="" class="control-label">Name of Court/Department:</label>
									<input type="text" name="cd_name_of_court" id="cd_name_of_court" class="form-control" value="<?php echo (isset($case_data['name_of_court'])) ? $case_data['name_of_court'] : ''; ?>" maxlength="250">
								</div>

								<!-- If option is court -->
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Reference No.:</label>
									<input type="text" class="form-control" id="cd_case_arb_pet" name="cd_case_arb_pet" autocomplete="off" maxlength="100" value="<?php if (isset($case_data['arbitration_petition'])) {
																																										echo $case_data['arbitration_petition'];
																																									} ?>" />
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Ref. Received On:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="cd_recieved_on" name="cd_recieved_on" autocomplete="off" readonly="true" value="<?php echo (isset($case_data['recieved_on'])) ? formatDatepickerDate($case_data['recieved_on']) : date('d-m-Y'); ?>" />
									</div>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of registration:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="cd_registered_on" name="cd_registered_on" autocomplete="off" readonly="true" value="<?php echo (isset($case_data['registered_on'])) ? formatDatepickerDate($case_data['registered_on']) : date('d-m-Y'); ?>" />
									</div>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label for="" class="control-label">Nature of arbitration:</label>
									<select name="cd_toa" id="cd_toa" class="form-control">
										<option value="">Select Option</option>
										<?php foreach ($type_of_arbitration as $toa) : ?>
											<option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['type_of_arbitration']) && $toa['gen_code'] == $case_data['type_of_arbitration']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
									<label for="" class="control-label">Case Type:</label>
									<select name="cd_di_toa" id="cd_di_toa" class="form-control">
										<option value="">Select Option</option>
										<?php foreach ($di_type_of_arbitration as $toa) : ?>
											<option value="<?= $toa['gen_code'] ?>" <?= (isset($case_data['di_type_of_arbitration']) && $toa['gen_code'] == $case_data['di_type_of_arbitration']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>


								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label for="" class="control-label">Case Status:</label>
									<select name="cd_status" id="cd_status" class="form-control">
										<option value="">Select Option</option>
										<?php foreach ($get_case_status as $cs) : ?>
											<option value="<?= $cs['gen_code'] ?>" <?= (isset($case_data['case_status']) && $cs['gen_code'] == $case_data['case_status']) ? 'selected' : '' ?>><?= $cs['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label for="" class="">Upload File (If any)</label>
									<input type="file" class="form-control filestyle" name="cd_case_file[]" id="cd_case_file" accept=".jpg, .pdf" multiple>
									<small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
									<input type="hidden" name="hidden_case_file_name" value="<?php echo (isset($case_data['case_file']) && !empty($case_data['case_file'])) ? $case_data['case_file'] : ''; ?>">
								</div>

								<div class="form-group col-md-3 col-sm-12 col-xs-12">
									<label for="" class="control-label">Remarks (If any):</label>
									<textarea class="form-control" name="cd_remarks" id="cd_remarks" rows="3"><?php echo (isset($case_data['remarks'])) ? $case_data['remarks'] : ''; ?></textarea>
								</div>

							</div>

							<div class="row text-center mt-25">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-custom" id="btn_submit"><i class='fa fa-paper-plane'></i> <?php echo (isset($edit_form) && $edit_form == true) ? 'Update Details' : 'Save Details'; ?></button>
								</div>
							</div>
							<?= form_close() ?>
							<!-- Case Details End -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<!-- Modals Start -->



<!-- Modals End -->
<!-- ================================================================================ -->

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
		Inputs: false
	});
</script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/add-case.js') ?>"></script>