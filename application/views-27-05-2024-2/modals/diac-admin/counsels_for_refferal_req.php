<!-- Counsel Modal start -->
<div id="addCounselListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title counsel-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'counsel_form')) ?>

				<input type="hidden" id="counsel_op_type" name="op_type" value="ADD_CASE_COUNSEL_FOR_REF_REQ">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="counsel_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_counsel_id" name="hidden_counsel_id" value="">
				<input type="hidden" id="hidden_counsel_code" name="hidden_counsel_code" value="">

				<input type="hidden" name="csrf_counsel_form_token" value="<?php echo generateToken('case_counsel_form'); ?>">

				<div id="fr_wrapper">
					<div class="row">

						<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Appearing for:</label>
							<select name="counsel_appearing_for" id="counsel_appearing_for" class="form-control counsel_appearing_for">
								<option value="">Select Option</option>
								<?php foreach ($claim_res_data as $cr_data) : ?>
									<option value="<?= $cr_data['code'] ?>"><?= $cr_data['name'] . ' (' . $cr_data['count_number'] . ') ' . $cr_data['type'] ?></option>
								<?php endforeach; ?>

							</select>
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12 required" id="select_enrol_col">
							<label class="control-label">Counsels List</label>
							<select class="js-example-basic-single" name="counsels_list" id="counsels_list">
								<option value="">Select</option>
								<option value="OTHER">Other</option>
								<?php foreach ($all_counsels_list as $counsels_list) : ?>
									<option value="<?= $counsels_list['code'] ?>"><?= $counsels_list['name'] . " (" . $counsels_list['enrollment_no'] . ")"; ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Name:</label>
							<input type="text" class="form-control" id="counsel_name" name="counsel_name" autocomplete="off" maxlength="100" value="">
							<input type="hidden" name="counsels_code" id="counsels_code" value="">
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Enrollment No.:</label>
							<input type="text" class="form-control" id="counsel_enroll_no" name="counsel_enroll_no" autocomplete="off" maxlength="100" value="">
						</div>
						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Reg. Email Id:</label>
							<input type="text" class="form-control" id="counsel_email" name="counsel_email" autocomplete="off" value="" />
						</div>
						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Reg. Contact Number:</label>
							<input type="text" class="form-control" id="counsel_contact" name="counsel_contact" autocomplete="off" maxlength="20" value="">
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
											<input type="text" name="permanent_address_1" id="counsel_permanent_address_1" class="form-control" value="">
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Address 2</label>
											<input type="text" name="permanent_address_2" id="counsel_permanent_address_2" class="form-control" value="">
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Country</label>
											<select class="form-control" name="permanent_country" id="counsel_permanent_country">
												<option value="">Select Country</option>
												<?php foreach ($countries as $country) : ?>
													<option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">State</label>
											<select class="form-control" name="permanent_state" id="counsel_permanent_state">
												<option value="">Select State</option>
											</select>
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Pincode</label>
											<input type="text" class="form-control" id="counsel_permanent_pincode" name="permanent_pincode" value="">
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
											<input type="text" name="corr_address_1" id="counsel_corr_address_1" class="form-control" value="">
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Address 2</label>
											<input type="text" name="corr_address_2" id="counsel_corr_address_2" class="form-control" value="">
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Country</label>
											<select class="form-control" name="corr_country" id="counsel_corr_country">
												<option value="">Select Country</option>
												<?php foreach ($countries as $country) : ?>
													<option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">State</label>
											<select class="form-control" name="corr_state" id="counsel_corr_state">
												<option value="">Select State</option>
											</select>
										</div>
										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Pincode</label>
											<input type="text" class="form-control" id="counsel_corr_pincode" name="corr_pincode" value="">
										</div>
									</div>
								</div>
							</fieldset>
						</div>

					</div>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom counsel_btn_submit" id="counsel_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Counsel Modal end -->