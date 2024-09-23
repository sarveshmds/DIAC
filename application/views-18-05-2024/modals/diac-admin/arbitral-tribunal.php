<!-- Arbitral Tribunal Modal -->
<div id="addArbTriListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title at-modal-title" style="text-align: center;"><span class="fa fa-plus"></span> Add Arbitrator</h4>
			</div>
			<div class="modal-body">
				<?= form_open(null, array('class' => 'wfst', 'id' => 'at_form')) ?>
				<input type="hidden" id="at_op_type" name="op_type" value="ADD_CASE_ARBITRAL_TRIBUNAL">
				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>
				<input type="hidden" id="hidden_at_id" name="hidden_at_id" value="">
				<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('case_det_form'); ?>">
				<div id="at_arbs_wrapper">
					<fieldset class="fieldset at_fieldset" id="at_fieldset">
						<legend>Arbitrator</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Is Empanelled: <span class="text-danger">*</span></label>
									<select name="at_whe_on_panel" id="at_whe_on_panel" class="form-control at_whe_on_panel">
										<option value="">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
									</select>
								</div>
							</div>

							<div class="is_emp_col" style="display: none;">
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12 required" id="select_arb_col">
										<label class="control-label">Select Arbitrator: <span class="text-danger">*</span></label>
										<select class="js-example-basic-single at_arb_name" name="at_arb_name" id="at_arb_name">
											<option value="">Select</option>
											<!-- <option value="OTHER">Other</option> -->
											<?php foreach ($arbitrators_name as $arb) : ?>
												<option value="<?= $arb['code'] ?>"><?= $arb['name_of_arbitrator'] ?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-3 col-sm-6 col-xs-12 arb_col required">
									<label class="control-label">Name of Arbitrator <span class="text-danger">*</span></label>
									<input type="text" name="at_new_arb_name" id="at_new_arb_name" class="form-control">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Email Id: <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="at_arb_email" name="at_arb_email" autocomplete="off" />
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Contact Number: <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="at_arb_contact" name="at_arb_contact" autocomplete="off" maxlength="20" value="">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label for="" class="control-label">Category: <span class="text-danger">*</span></label>
									<select name="at_category" id="at_category" class="form-control at_category">
										<option value="">Select Option</option>
										<?php foreach ($panel_category as $pc) : ?>
											<option value="<?= $pc['code'] ?>"><?= $pc['category_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<fieldset class="fieldset">
										<legend class="legend">Permanent Address</legend>
										<div class="fieldset-content-box">
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Address 1 </label>
												<input type="text" name="permanent_address_1" id="permanent_address_1" class="form-control" value="">
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Address 2</label>
												<input type="text" name="permanent_address_2" id="permanent_address_2" class="form-control" value="">
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Country </label>
												<select class="form-control" name="permanent_country" id="permanent_country">
													<option value="">Select Country</option>
													<?php foreach ($countries as $country) : ?>
														<option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">State </label>
												<select class="form-control" name="permanent_state" id="permanent_state">
													<option value="">Select State</option>
												</select>
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Pincode </label>
												<input type="text" name="permanent_pincode" id="permanent_pincode" class="form-control" value="">
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<fieldset class="fieldset">
										<legend class="legend">Correspondence Address</legend>
										<div class="fieldset-content-box">
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Address 1 </label>
												<input type="text" name="corr_address_1" id="arb_tri_corr_address_1" class="form-control" value="">
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Address 2</label>
												<input type="text" name="corr_address_2" id="arb_tri_corr_address_2" class="form-control" value="">
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Country </label>
												<select class="form-control" name="corr_country" id="arb_tri_corr_country">
													<option value="">Select Country</option>
													<?php foreach ($countries as $country) : ?>
														<option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">State </label>
												<select class="form-control" name="corr_state" id="arb_tri_corr_state">
													<option value="">Select State</option>
												</select>
											</div>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Pincode </label>
												<input type="text" name="corr_pincode" id="arb_tri_corr_pincode" class="form-control" value="">
											</div>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label for="" class="control-label">Appointed by: <span class="text-danger">*</span></label>
									<select name="at_appointed_by" id="at_appointed_by" class="at_appointed_by form-control">
										<option value="">Select Option</option>
										<?php foreach ($get_appointed_by_list as $apl) : ?>
											<option value="<?= $apl['gen_code'] ?>"><?= $apl['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of Appointment: <span class="text-danger">*</span></label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date at_doa" id="at_doa" name="at_doa" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of Consent:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date at_dod" id="at_dod" name="at_dod" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12 arb_col">
									<label class="control-label">Arbitrator Type: <span class="text-danger">*</span></label>
									<select class="form-control at_arb_type" id="at_arb_type" name="at_arb_type" autocomplete="off">
										<option value="">Select Type</option>
										<?php foreach ($arbitrator_types as $type) : ?>
											<option value="<?= $type['gen_code'] ?>"><?= $type['description'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Terminated: <span class="text-danger">*</span></label>
									<div>
										Yes: <input type="radio" name="at_terminated" value="yes" class="at_terminated">
										No: <input type="radio" name="at_terminated" value="no" class="at_terminated" checked>
									</div>
								</div>
								<div class="col-xs-12 at_termination_col" style="display: none;">
									<fieldset class="fieldset at_fieldset" id="at_termination_fieldset">
										<legend>Termination</legend>
										<div class="fieldset-content-box">
											<div class="row">
												<div class="form-group col-md-4 col-sm-6 col-xs-12">
													<label for="" class="control-label">Termination by:</label>
													<select name="at_termination_by" id="at_termination_by" class="at_termination_by form-control">
														<option value="">Select Option</option>
														<?php foreach ($get_termination_by_list as $atl) : ?>
															<option value="<?= $atl['gen_code'] ?>"><?= $atl['description'] ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="form-group col-md-4 col-sm-6 col-xs-12">
													<label class="control-label">Date of Termination:</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i>
														</div>
														<input type="text" class="form-control custom-all-date at_term_disabled at_dot" id="at_dot" name="at_dot" autocomplete="off" readonly="true" value="" disabled />
													</div>
												</div>
												<div class="form-group col-md-4 col-sm-6 col-xs-12">
													<label for="" class="">Upload File (If any)</label>
													<input type="file" class="form-control filestyle" name="cd_termination_file[]" id="cd_termination_file" accept=".jpg, .pdf" multiple>
													<small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
													<input type="hidden" name="hidden_termination_file_name" id="hidden_termination_file_name" value="">
												</div>
												<div class="form-group col-md-6 col-sm-12 col-xs-12">
													<label class="control-label">Reason for termination:</label>
													<textarea class="form-control at_term_disabled at_rot" id="at_rot" name="at_rot" rows="3" disabled></textarea>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom at_btn_submit" id="at_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>
			</div>
		</div>
	</div>
</div>
<!-- Arbitral tribunal modal end -->
<script type="text/javascript">
	// 	$(document).ready(function() {
	//     $('.js-example-basic-single').select2();


	// });
</script>