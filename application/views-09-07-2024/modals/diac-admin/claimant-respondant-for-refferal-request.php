<!-- Claimant and respondent modal start -->
<div id="addCarListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" style="width: 95%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title car-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'car_refferal_request_form')) ?>

				<input type="hidden" id="car_op_type" name="op_type" value="">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="car_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_car_id" name="hidden_car_id" value="">
				<input type="hidden" id="hidden_car_code" name="hidden_car_code" value="">
				<input type="hidden" id="hidden_car_type" name="hidden_car_type" value="">

				<input type="hidden" name="csrf_car_form_token" value="<?php echo generateToken('car_refferal_request_form'); ?>">

				<div id="car_wrapper">
					<div class="row">
						<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Name:</label>
							<input type="text" class="form-control uppercase" id="car_name" name="car_name" autocomplete="off" maxlength="200" value="" />
						</div>
						<!-- <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Number:</label>
							<input type="text" class="form-control" id="car_number" name="car_number" autocomplete="off" maxlength="200" value="" />
							<p class="help-block" id="car_number_help"></p>
						</div> -->

						<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Mobile Number:</label>
							<input type="text" class="form-control" id="car_contact_no" name="car_contact_no" autocomplete="off" maxlength="20" value="">
						</div>

						<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Email Id:</label>
							<input type="email" class="form-control uppercase" id="car_email" name="car_email" autocomplete="off" maxlength="100" value="">
						</div>


						<div class="form-group col-xs-12">
							<fieldset class="fieldset">
								<legend class="legend">
									Permanent Address
								</legend>
								<div class="fieldset-content-box">
									<div class="row">
										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">Address Line 1:</label>
											<textarea class="form-control uppercase" id="perm_address1" name="perm_address1" autocomplete="off"></textarea>
										</div>
										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">Address Line 2:</label>
											<textarea class="form-control uppercase" id="perm_address2" name="perm_address2" autocomplete="off"></textarea>
										</div>
										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">City:</label>
											<input type="text" class="form-control uppercase" id="perm_city" name="perm_city" autocomplete="off" maxlength="100" value="" />
										</div>

										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">Country:</label>
											<select class="form-control" name="perm_country" id="perm_country">
												<option value="">Select Country</option>
												<?php foreach ($countries as $country) : ?>
													<option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">State:</label>
											<select class="form-control" name="perm_state" id="perm_state">
												<option value="">Select State</option>
											</select>
										</div>
										<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">Pincode:</label>
											<input type="text" class="form-control" id="perm_pincode" name="perm_pincode" autocomplete="off" maxlength="20" value="" />
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
						<button type="submit" class="btn btn-custom car_btn_submit" id="car_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Claimant and respondent modal end -->