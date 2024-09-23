<!-- Cost deposit Modal start -->
<div id="addCDListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title cd-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'cd_form')) ?>

				<input type="hidden" id="cd_op_type" name="op_type" value="ADD_CASE_COST_DEPOSIT">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="cd_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_cd_id" name="hidden_cd_id" value="">

				<input type="hidden" name="csrf_cd_form_token" value="<?php echo generateToken('case_cd_form'); ?>">

				<div id="cd_wrapper">
					<fieldset class="fieldset cd_fieldset" id="cd_fieldset">
						<legend class="car_legend_head">Cost Deposit</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Date of deposit:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="cd_date_of_deposit" name="cd_date_of_deposit" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Deposited by:</label>
									<select name="cd_deposited_by" id="cd_deposited_by" class="form-control cd_deposited_by">
										<option value="">Select Option</option>
										<?php foreach ($claim_res_data as $cr_data) : ?>
											<option value="<?= $cr_data['id'] ?>"><?= $cr_data['name'] . ' (' . $cr_data['type'] . ' - ' . $cr_data['count_number'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Name of depositor:</label>
									<input type="text" class="form-control" id="cd_name_of_depositor" name="cd_name_of_depositor" autocomplete="off" maxlength="100" value="">

								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12">
									<label class="control-label">Cost imposed vide order dated:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control <?php echo (in_array($this->session->userdata('role'), ['ACCOUNTS'])) ? '' : 'custom-all-date' ?>" id="cd_cost_imposed_dated" name="cd_cost_imposed_dated" autocomplete="off" readonly="true" value="" />
									</div>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12">
									<label class="control-label">Cost Imposed:</label>
									<input type="text" class="form-control" id="cd_cost_imposed" name="cd_cost_imposed" autocomplete="off" maxlength="30" value="<?= (isset($fee_cost_data['cost_imposed'])) ? $fee_cost_data['cost_imposed'] : ''; ?>" <?php echo (in_array($this->session->userdata('role'), ['ACCOUNTS'])) ? 'readonly' : '' ?>>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Amount:</label>
									<input type="number" data-help-block-id="help_cd_amount" class="form-control amount_field" id="cd_amount" name="cd_amount" autocomplete="off" maxlength="30" value="<?= (isset($fee_cost_data['sum_in_dispute'])) ? $fee_cost_data['sum_in_dispute'] : ''; ?>">
									<p id="help_cd_amount" class="amount_help_block"></p>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Mode of Deposit:</label>
									<select name="cd_mode_of_deposit" id="cd_mode_of_deposit" class="form-control cd_mode_of_deposit">
										<option value="">Select Option</option>
										<?php foreach ($get_mop_list as $mode) : ?>
											<option value="<?= $mode['gen_code'] ?>"><?= $mode['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
									<label class="control-label">Details of deposit:</label>
									<textarea class="form-control customized-summernote" id="cd_details_of_deposit" name="cd_details_of_deposit" autocomplete="off" rows="4"></textarea>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom cd_btn_submit" id="cd_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Cost deposit Modal end -->