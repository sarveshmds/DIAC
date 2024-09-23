<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<?= form_open(null, array('class' => 'wfst', 'id' => 'counsel_form')) ?>

						<input type="hidden" id="counsel_op_type" name="op_type" value="<?= isset($edit_form) && $edit_form == true ? 'UPDATE_CASE_COUNSEL' : 'ADD_CASE_COUNSEL'; ?>">

						<?php if (isset($case_no)) : ?>
							<input type="hidden" id="counsel_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
						<?php endif; ?>

						<input type="hidden" id="hidden_counsel_id" name="hidden_counsel_id" value="<?= isset($counsels_data['id']) ? $counsels_data['id'] : '' ?>">
						<input type="hidden" id="hidden_counsel_code" name="hidden_counsel_code" value="<?= isset($counsels_code) ? $counsels_code : '' ?>">

						<input type="hidden" name="csrf_counsel_form_token" value="<?php echo generateToken('case_counsel_form'); ?>">

						<div id="fr_wrapper">
							<div class="row">

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required" id="select_enrol_col">
									<label class="control-label">Counsels List</label>
									<select class="form-control" name="counsels_list" id="counsels_list">
										<option value="">Select</option>
										<option value="OTHER">Other</option>
										<?php foreach ($all_counsels_list as $counsels_list) : ?>
											<option value="<?= $counsels_list['code'] ?>" <?= isset($counsels_data['name_code']) && $counsels_data['name_code'] == $counsels_list['code'] ? 'selected' : '' ?>><?= $counsels_list['name'] . " (" . $counsels_list['enrollment_no'] . ")"; ?></option>
										<?php endforeach ?>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Name:</label>
									<input type="text" class="form-control" id="counsel_name" name="counsel_name" autocomplete="off" maxlength="100" value="<?= isset($counsels_data['name']) ? $counsels_data['name'] : '' ?>">
									<input type="hidden" name="counsels_code" id="counsels_code" value="<?= isset($counsels_data['name_code']) ? $counsels_data['name_code'] : '' ?>">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Enrollment No.:</label>
									<input type="text" class="form-control" id="counsel_enroll_no" name="counsel_enroll_no" autocomplete="off" maxlength="100" value="<?= isset($counsels_data['enrollment_no']) ? $counsels_data['enrollment_no'] : '' ?>">
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Reg. Email Id:</label>
									<input type="text" class="form-control" id="counsel_email" name="counsel_email" autocomplete="off" value="<?= isset($counsels_data['email']) ? $counsels_data['email'] : '' ?>" />
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Reg. Contact Number:</label>
									<input type="text" class="form-control" id="counsel_contact" name="counsel_contact" autocomplete="off" maxlength="20" value="<?= isset($counsels_data['phone_number']) ? $counsels_data['phone_number'] : '' ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<fieldset class="fieldset">
										<legend class="legend">Permanent Address</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Address 1</label>
													<input type="text" name="permanent_address_1" id="counsel_permanent_address_1" class="form-control" value="<?= isset($counsels_data['perm_address_1']) ? $counsels_data['perm_address_1'] : '' ?>">
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Address 2</label>
													<input type="text" name="permanent_address_2" id="counsel_permanent_address_2" class="form-control" value="<?= isset($counsels_data['perm_address_2']) ? $counsels_data['perm_address_2'] : '' ?>">
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Country</label>
													<select class="form-control" name="permanent_country" id="counsel_permanent_country">
														<option value="">Select Country</option>
														<?php foreach ($countries as $country) : ?>
															<option value="<?= $country['iso2'] ?>" <?= isset($counsels_data['perm_country_code']) && $country['iso2'] == $counsels_data['perm_country_code'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">State</label>
													<select class="form-control" name="permanent_state" id="counsel_permanent_state">
														<option value="">Select State</option>
														<?php if ($counsels_data['perm_state_code'] && $counsels_data['perm_state_code'] !== '') : ?>
															<?php foreach ($get_perm_state as $state) : ?>
																<option value="<?= $state['id'] ?>" <?= isset($counsels_data['perm_state_code']) && $counsels_data['perm_state_code'] == $state['id'] ? 'selected' : '' ?>><?= $state['name'] ?></option>
															<?php endforeach ?>
														<?php endif ?>
													</select>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Pincode</label>
													<input type="text" class="form-control" id="counsel_permanent_pincode" name="permanent_pincode" value="<?= isset($counsels_data['perm_pincode']) ? $counsels_data['perm_pincode'] : '' ?>">
												</div>
											</div>
										</div>
									</fieldset>
								</div>

								<div class="col-xs-12">
									<fieldset class="fieldset">
										<legend class="legend">Correspondence Address</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Address 1</label>
													<input type="text" name="corr_address_1" id="counsel_corr_address_1" class="form-control" value="<?= isset($counsels_data['corr_address_1']) ? $counsels_data['corr_address_1'] : '' ?>">
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Address 2</label>
													<input type="text" name="corr_address_2" id="counsel_corr_address_2" class="form-control" value="<?= isset($counsels_data['corr_address_2']) ? $counsels_data['corr_address_2'] : '' ?>">
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Country</label>
													<select class="form-control" name="corr_country" id="counsel_corr_country">
														<option value="">Select Country</option>
														<?php foreach ($countries as $country) : ?>
															<option value="<?= $country['iso2'] ?>" <?= isset($counsels_data['corr_country_code']) && $country['iso2'] == $counsels_data['corr_country_code'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">State</label>
													<select class="form-control" name="corr_state" id="counsel_corr_state">
														<option value="">Select State</option>
														<?php if ($counsels_data['corr_state_code'] && $counsels_data['corr_state_code'] !== '') : ?>
															<?php foreach ($get_corr_state as $state) : ?>
																<option value="<?= $state['id'] ?>" <?= isset($counsels_data['corr_state_code']) && $counsels_data['corr_state_code'] == $state['id'] ? 'selected' : '' ?>><?= $state['name'] ?></option>
															<?php endforeach ?>
														<?php endif ?>
													</select>
												</div>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Pincode</label>
													<input type="text" class="form-control" id="counsel_corr_pincode" name="corr_pincode" value="<?= isset($counsels_data['corr_pincode']) ? $counsels_data['corr_pincode'] : '' ?>">
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Appearing for:</label>
									<select name="counsel_appearing_for" id="counsel_appearing_for" class="form-control counsel_appearing_for">
										<option value="">Select Option</option>
										<?php foreach ($claim_res_data as $cr_data) : ?>
											<option value="<?= $cr_data['code'] ?>" <?= isset($counsels_data['appearing_for']) && $counsels_data['appearing_for'] == $cr_data['code'] ? 'selected' : '' ?>><?= $cr_data['name'] . '(' . $cr_data['count_number'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Discharge or not:</label>
									<select class="form-control" name="counsel_dis_check" id="counsel_dis_check">
										<option value="0" <?= isset($counsels_data['discharge']) && $counsels_data['discharge'] == 0 ? 'selected' : '' ?>>No</option>
										<option value="1" <?= isset($counsels_data['discharge']) && $counsels_data['discharge'] == 1 ? 'selected' : '' ?>>Yes</option>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12" id="counsel_dis_date">
									<label class="control-label">Date of discharge (If yes):</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="counsel_dodis" name="counsel_dodis" autocomplete="off" readonly="true" value="<?= isset($counsels_data['date_of_discharge']) ? $counsels_data['date_of_discharge'] : '' ?>" />
									</div>
								</div>
							</div>

						</div>

						<div class="row text-center mt-25">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
								<button type="submit" class="btn btn-custom counsel_btn_submit" id="counsel_btn_submit"><i class='fa fa-paper-plane'></i> <?= isset($edit_form) && $edit_form == true ? 'Update Details' : 'Save Details'; ?></button>
							</div>
						</div>
						<?= form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/counsels.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/counsels.js')) ?>"></script>