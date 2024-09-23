<!-- Fee Released Modal start -->
<div id="addFRListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title fr-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'fr_form')) ?>

				<input type="hidden" id="fr_op_type" name="op_type" value="ADD_CASE_FEE_RELEASED">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="fr_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_fr_id" name="hidden_fr_id" value="">

				<input type="hidden" name="csrf_fr_form_token" value="<?php echo generateToken('case_fr_form'); ?>">

				<div id="fr_wrapper">
					<fieldset class="fieldset fr_fieldset" id="fr_fieldset">
						<legend class="car_legend_head">Fee Released</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Nature of award:</label>
									<select name="fr_nature_of_award" id="fr_nature_of_award" class="form-control fr_nature_of_award">
										<option value="">Select Option</option>
										<?php foreach ($nature_of_awards as $nature) : ?>
											<option value="<?= $nature['gen_code'] ?>"><?= $nature['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Date of fee released:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="fr_dofr" name="fr_dofr" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Released to:</label>
									<select class="form-control" id="fr_released_to" name="fr_released_to">
										<option>Select Option</option>
										<?php foreach ($release_arbitrators_list as $release_list): ?>
											<option value="<?= $release_list['at_code'] ?>"><?= $release_list['name_of_arbitrator']. '('.$release_list['category_name'].')'  ?></option>
										<?php endforeach ?>
									</select>
									<!-- <input type="text" class="form-control" id="fr_released_to" name="fr_released_to" autocomplete="off" maxlength="100" value=""> -->
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Mode of payment:</label>
									<select name="fr_mode" id="fr_mode" class="form-control fr_mode">
										<option value="">Select Option</option>
										<?php foreach ($get_mop_list as $mode) : ?>
											<option value="<?= $mode['gen_code'] ?>"><?= $mode['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
									<label class="control-label">Amount:</label>
									<input type="number" data-help-block-id="help_fr_amount" class="form-control amount_field" id="fr_amount" name="fr_amount" autocomplete="off" maxlength="30" value="<?= (isset($fee_cost_data['sum_in_dispute'])) ? $fee_cost_data['sum_in_dispute'] : ''; ?>">
									<p id="help_fr_amount" class="amount_help_block"></p>
								</div>


								<div class="form-group col-md-3 col-sm-6 col-xs-12">
									<label class="control-label">Date of fee released note:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="fr_dofr_note" name="fr_dofr_note" autocomplete="off" readonly="true" value="" />
									</div>
								</div>

								<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
									<label class="control-label">Details of fee released:</label>
									<textarea class="form-control customized-summernote" id="fr_details" name="fr_details" autocomplete="off" rows="4"></textarea>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom fr_btn_submit" id="fr_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Fee Released Modal end -->