<!-- Case Allotment Modal start -->
<div id="addCAListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title ca-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'ca_form')) ?>

				<input type="hidden" id="ca_op_type" name="op_type" value="ADD_CASE_ALLOTMENT">

				<input type="hidden" id="hidden_ca_id" name="hidden_ca_id" value="">

				<input type="hidden" name="csrf_ca_form_token" value="<?php echo generateToken('case_ca_form'); ?>">

				<div id="fr_wrapper">
					<fieldset class="fieldset ca_fieldset" id="noting_fieldset">
						<legend class="ca_legend_head">Case Allotment</legend>
						<div class="fieldset-content-box">
							<div class="row">

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Case Number:</label>
									<select name="ca_case_no" id="ca_case_no" class="form-control ca_case_no select2">
										<option value="">Select Option</option>
										<?php foreach ($all_registered_cases as $rc) : ?>
											<option value="<?= $rc['slug'] ?>"><?= $rc['case_no'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required allotted_to_cm_col">
									<label class="control-label">Allotted to (Case Managers):</label>
									<select name="ca_allotted_to_cm[]" id="ca_allotted_to_cm" class="form-control ca_allotted_to_cm select2" multiple>
										<?php foreach ($all_cms as $user) : ?>
											<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required allotted_to_dc_col">
									<label class="control-label">Allotted to (Deputy Counsel):</label>
									<select name="ca_allotted_to_dc[]" id="ca_allotted_to_dc" class="form-control ca_allotted_to_dc select2" multiple>
										<?php foreach ($all_dcs as $user) : ?>
											<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required allotted_to_coord_col">
									<label class="control-label">Allotted to (Coordinator):</label>
									<select name="ca_allotted_to_coord[]" id="ca_allotted_to_coord" class="form-control ca_allotted_to_coord select2" multiple>
										<?php foreach ($all_coordinators as $user) : ?>
											<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom ca_btn_submit" id="ca_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Case Allotment Modal end -->