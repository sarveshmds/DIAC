<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<div>
							<?= form_open(null, array('class' => 'wfst', 'id' => 'award_term_form')) ?>

							<input type="hidden" id="award_term_op_type" name="op_type" value="<?php echo (isset($award_term_data['id'])) ? 'EDIT_CASE_AWARD_TERMINATION' : 'ADD_CASE_AWARD_TERMINATION'; ?>">

							<?php if (isset($case_no)) : ?>
								<input type="hidden" id="hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
							<?php endif; ?>

							<input type="hidden" id="hidden_award_term_id" name="hidden_award_term_id" value="<?php echo (isset($award_term_data['id']) && !empty($award_term_data['id'])) ? $award_term_data['id'] : ''; ?>">

							<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('case_det_form'); ?>">

							<div class="row">
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Termination By</label>
									<select class="form-control award_term_select" id="award_term_select" name="award_term_select">
										<option value="">Select</option>
										<option value="award" <?php echo (isset($award_term_data['type']) && $award_term_data['type'] == 'award') ? 'selected' : ''; ?>>Award</option>
										<option value="other" <?php echo (isset($award_term_data['type']) && $award_term_data['type'] == 'other') ? 'selected' : ''; ?>>Other</option>
									</select>
								</div>

								<!-- Award -->
								<div class="col-xs-12 award_cols_wrapper" <?= (isset($award_term_data['type']) && $award_term_data['type'] == 'award') ? 'style="display: block;"' : 'style="display: none;"' ?>>
									<fieldset class="fieldset at_fieldset">
										<legend>Award</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Date of Award:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="award_term_doa" name="award_term_doa" autocomplete="off" readonly="true" value="<?= (isset($award_term_data['date_of_award']) && !empty($award_term_data['date_of_award'])) ? formatDatepickerDate($award_term_data['date_of_award']) : '' ?>" data-date-end-date="0d" />
													</div>
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Nature of Award:</label>
													<select name="award_term_nature" id="award_term_nature" class="form-control award_term_nature">
														<option value="">Select Option</option>
														<?php foreach ($term_nature_of_awards as $nature) : ?>
															<option value="<?= $nature['gen_code'] ?>" <?= (isset($award_term_data['nature_of_award']) && $award_term_data['nature_of_award'] == $nature['gen_code']) ? 'selected' : '' ?>><?= $nature['description'] ?></option>
														<?php endforeach; ?>

													</select>
												</div>


												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Addendum Award, if any:</label>
													<input type="text" class="form-control" rows="5" id="award_term_addendum" name="award_term_addendum" autocomplete="off" value="<?= (isset($award_term_data['addendum_award']) && !empty($award_term_data['addendum_award'])) ? $award_term_data['addendum_award'] : '' ?>">
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Award served to Claimant on:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="award_term_served_claimant" name="award_term_served_claimant" autocomplete="off" readonly="true" value="<?= (isset($award_term_data['award_served_claimaint_on'])) ? formatDatepickerDate($award_term_data['award_served_claimaint_on']) : '' ?>" />
													</div>
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Award served to Respondent on:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="award_term_served_res" name="award_term_served_res" autocomplete="off" readonly="true" value="<?= (isset($award_term_data['award_served_respondent_on']) && !empty($award_term_data['award_served_respondent_on'])) ? formatDatepickerDate($award_term_data['award_served_respondent_on']) : '' ?>" />
													</div>
												</div>

												<div class="form-group col-md-4 col-sm-6 col-xs-12">
													<label for="" class="">Upload File (If any)</label>
													<input type="file" class="form-control filestyle" name="award_term_award_file" id="award_term_award_file" accept=".jpg, .pdf" multiple>
													<small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
													<input type="hidden" name="hidden_award_file_name" id="hidden_award_file_name" value="<?= (isset($award_term_data['award_term_award_file']) && !empty($award_term_data['award_term_award_file'])) ? $award_term_data['award_term_award_file'] : '' ?>">
												</div>

												<?php if (isset($award_term_data['award_term_award_file']) && !empty($award_term_data['award_term_award_file'])) : ?>
													<div class="form-group col-md-4 col-sm-6 col-xs-12">
														<label for="" class="">Uploaded File</label>
														<div>
															<a href="<?= base_url(VIEW_AWARD_FILE_UPLOADS_FOLDER) . $award_term_data['award_term_award_file'] ?>" class="btn btn-custom" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</fieldset>
								</div>

								<!-- Other -->
								<div class="col-xs-12 other_cols_wrapper" <?= (isset($award_term_data['type']) && $award_term_data['type'] == 'other') ? 'style="display: block;"' : 'style="display: none;"' ?>>
									<fieldset class="fieldset">
										<legend>Other</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Date:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="award_term_dot" name="award_term_dot" autocomplete="off" readonly="true" value="<?php echo (isset($award_term_data['date_of_termination']) && !empty($award_term_data['date_of_termination'])) ? formatDatepickerDate($award_term_data['date_of_termination']) : ''; ?>" data-date-end-date="0d" />
													</div>
												</div>

												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Reason:</label>
													<select name="award_term_rft" id="award_term_rft" class="award_term_rft form-control">
														<option value="">Select Option</option>
														<?php foreach ($termination_reasons as $tr) : ?>
															<option value="<?= $tr['gen_code'] ?>" <?php echo (isset($award_term_data['reason_for_termination']) && $award_term_data['reason_for_termination'] == $tr['gen_code']) ? 'selected' : ''; ?>><?= $tr['description'] ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
								</div>

								<!-- Factsheet -->
								<div class="col-xs-12 factsheet_cols_wrapper">
									<fieldset class="fieldset">
										<legend>Factsheet</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Factsheet prepared:</label>
													<select class="form-control award_term_factsheet" id="award_term_factsheet" name="award_term_factsheet">
														<option value="">Select</option>
														<option value="yes" <?php echo (isset($award_term_data['factsheet_prepared']) && $award_term_data['factsheet_prepared'] == 'yes') ? 'selected' : ''; ?>>Yes</option>
														<option value="no" <?php echo (isset($award_term_data['factsheet_prepared']) && $award_term_data['factsheet_prepared'] == 'no') ? 'selected' : ''; ?>>No</option>
													</select>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12 factsheet_cols" <?= (isset($award_term_data['factsheet_prepared']) && $award_term_data['factsheet_prepared'] == 'yes') ? 'style="display: block;"' : 'style="display: none;"' ?>>
													<label class="control-label">Date of factsheet prepared:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="award_term_dofp" name="award_term_dofp" autocomplete="off" readonly="true" value="<?php echo (isset($award_term_data['date_of_factsheet']) && !empty($award_term_data['date_of_factsheet'])) ? formatDatepickerDate($award_term_data['date_of_factsheet']) : ''; ?>" />
													</div>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12 factsheet_cols" <?= (isset($award_term_data['factsheet_prepared']) && $award_term_data['factsheet_prepared'] == 'yes') ? 'style="display: block;"' : 'style="display: none;"' ?>>
													<label for="" class="">Upload File (If any)</label>
													<input type="file" class="form-control filestyle" name="award_term_factsheet_file" id="award_term_factsheet_file" accept=".jpg, .pdf" multiple>
													<small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
													<input type="hidden" name="hidden_factsheet_file_name" id="hidden_factsheet_file_name" value="<?= (isset($award_term_data['factsheet_file']) && !empty($award_term_data['factsheet_file'])) ? $award_term_data['factsheet_file'] : '' ?>">
												</div>

												<?php if (isset($award_term_data['factsheet_file']) && !empty($award_term_data['factsheet_file'])) : ?>
													<div class="form-group col-md-3 col-sm-6 col-xs-12">
														<label for="" class="">Uploaded File</label>
														<div>
															<a href="<?= base_url(VIEW_FACTSHEET_FILE_UPLOADS_FOLDER) . $award_term_data['factsheet_file'] ?>" class="btn btn-custom" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
														</div>
													</div>
												<?php endif; ?>

											</div>
										</div>
									</fieldset>
								</div>

								<div class="col-xs-12">
									<label class="control-label">Note:</label>
									<textarea class="form-control" rows="5" id="note" name="note" autocomplete="off"> <?= (isset($award_term_data['note']) && !empty($award_term_data['note'])) ? $award_term_data['note'] : '' ?></textarea>
								</div>
							</div>

							<div class="row text-center mt-25">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
									<button type="submit" class="btn btn-custom" id="award_term_btn_submit"><i class='fa fa-paper-plane'></i> <?php echo (isset($award_term_data['id'])) ? 'Update Details' : 'Save Details'; ?></button>
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
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/termination.js') ?>"></script>