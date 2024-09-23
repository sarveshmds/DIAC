<!-- Claimant and respondent modal start -->
<div id="addCarListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title car-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'car_form')) ?>

				<input type="hidden" id="car_op_type" name="op_type" value="">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="car_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_car_id" name="hidden_car_id" value="">
				<input type="hidden" id="hidden_car_code" name="hidden_car_code" value="">
				<input type="hidden" id="hidden_car_type" name="hidden_car_type" value="">

				<input type="hidden" name="csrf_car_form_token" value="<?php echo generateToken('case_car_form'); ?>">

				<div id="car_wrapper">
					<div class="row">
						<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Name:</label>
							<input type="text" class="form-control" id="car_name" name="car_name" autocomplete="off" maxlength="200" value="" />
						</div>
						<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
							<label class="control-label">Number:</label>
							<input type="text" class="form-control" id="car_number" name="car_number" autocomplete="off" maxlength="200" value="" />
							<p class="help-block" id="car_number_help"></p>
						</div>
						<div class="form-group col-md-3 col-sm-6 col-xs-12 counter_claimant_col" style="display: none;">
							<label class="control-label">Counter Claimant:</label>
							<select class="form-control" name="car_counter_claimant" id="car_counter_claimant">
								<option value="">Select</option>
								<option value="yes">Yes</option>
								<option value="no">No</option>
							</select>
						</div>
						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Reg. Mobile Number:</label>
							<input type="text" class="form-control" id="car_contact_no" name="car_contact_no" autocomplete="off" maxlength="20" value="">
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Additional Mobile Number:</label>
							<input type="text" class="form-control" id="car_additional_contact_no" name="car_additional_contact_no" autocomplete="off" maxlength="200" value="">
							<small class="text-muted">(,) Comma seperated values</small>
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Reg. Email Id:</label>
							<input type="email" class="form-control" id="car_email" name="car_email" autocomplete="off" maxlength="100" value="">
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Additional Email Id:</label>
							<input type="text" class="form-control" id="car_additional_email" name="car_additional_email" autocomplete="off" maxlength="200" value="">
							<small class="text-muted">(,) Comma seperated values</small>
						</div>

						<div class="form-group col-md-3 col-sm-6 col-xs-12">
							<label class="control-label">Remove:</label>
							<select class="form-control" name="car_removed" id="car_removed">
								<option value="1">Yes</option>
								<option value="0" selected="selected">No</option>
							</select>
						</div>

						<div class="col-xs-12">
							<div class="edit-address-wrapper" style="display: none;">
								<?php require_once(APPPATH . 'views/templates/components/edit-address-fieldset.php') ?>
							</div>

							<div class="">
								<?php require_once(APPPATH . 'views/templates/components/add-address-fieldset.php') ?>
							</div>
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