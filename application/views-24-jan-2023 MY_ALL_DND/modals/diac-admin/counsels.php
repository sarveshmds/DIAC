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

				<input type="hidden" id="counsel_op_type" name="op_type" value="ADD_CASE_COUNSEL">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="counsel_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_counsel_id" name="hidden_counsel_id" value="">
				<input type="hidden" id="hidden_counsel_code" name="hidden_counsel_code" value="">

				<input type="hidden" name="csrf_counsel_form_token" value="<?php echo generateToken('case_counsel_form'); ?>">

				<div id="fr_wrapper">
					<fieldset class="fieldset counsel_fieldset" id="counsel_fieldset">
						<legend class="counsel_legend_head">Counsel Details</legend>
						<div class="fieldset-content-box">
							<div class="row">

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Name:</label>
									<input type="text" class="form-control" id="counsel_name" name="counsel_name" autocomplete="off" maxlength="100" value="">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Enrollment No.:</label>
									<input type="text" class="form-control" id="counsel_enroll_no" name="counsel_enroll_no" autocomplete="off" maxlength="100" value="">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Appearing for:</label>
									<select name="counsel_appearing_for" id="counsel_appearing_for" class="form-control counsel_appearing_for">
										<option value="">Select Option</option>
										<?php foreach ($claim_res_data as $cr_data) : ?>
											<option value="<?= $cr_data['id'] ?>"><?= $cr_data['name'] . '(' . $cr_data['count_number'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Reg. Email Id:</label>
									<input type="text" class="form-control" id="counsel_email" name="counsel_email" autocomplete="off" />
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Additional Email Ids:</label>
									<input type="text" class="form-control" id="counsel_additional_email" name="counsel_additional_email" autocomplete="off" />
									<small class="text-muted">(,) Comma seperated values</small>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Reg. Contact Number:</label>
									<input type="text" class="form-control" id="counsel_contact" name="counsel_contact" autocomplete="off" maxlength="20" value="">
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Additional Contact Number:</label>
									<input type="text" class="form-control" id="counsel_additional_contact" name="counsel_additional_contact" autocomplete="off" maxlength="20" value="">
									<small class="text-muted">(,) Comma seperated values</small>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Discharge or not:</label>
									<select class="form-control" name="counsel_dis_check" id="counsel_dis_check">
										<option value="1">Yes</option>
										<option value="0" selected="selected">No</option>
									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of discharge (If yes):</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="counsel_dodis" name="counsel_dodis" autocomplete="off" readonly="true" value="" />
									</div>
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
					</fieldset>

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