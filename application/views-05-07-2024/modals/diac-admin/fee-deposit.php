<!-- Fee deposit Modal start -->
<div id="addFDListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title fd-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'fd_form')) ?>

				<input type="hidden" id="fd_op_type" name="op_type" value="ADD_CASE_FEE_DEPOSIT">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="fd_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_fd_id" name="hidden_fd_id" value="">

				<input type="hidden" name="csrf_fd_form_token" value="<?php echo generateToken('case_fd_form'); ?>">

				<div id="fd_wrapper">
					<fieldset class="fieldset fd_fieldset" id="fd_fieldset">
						<legend class="car_legend_head">Fee Deposit</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Date of deposit:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="fd_date_of_deposit" name="fd_date_of_deposit" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Deposited by:</label>
									<select name="fd_deposited_by" id="fd_deposited_by" class="form-control fd_deposited_by">
										<option value="">Select Option</option>
										<?php foreach ($claim_res_data as $cr_data) : ?>
											<option value="<?= $cr_data['id'] ?>"><?= $cr_data['name'] . ' (' . $cr_data['type'] . ' - ' . $cr_data['count_number'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Name of depositor:</label>
									<input type="text" class="form-control" id="fd_name_of_depositor" name="fd_name_of_depositor" autocomplete="off" maxlength="100" value="">
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Deposited towards:</label>
									<select name="fd_deposited_towards" id="fd_deposited_towards" class="form-control fd_deposited_towards">
										<option value="">Select Option</option>
										<?php foreach ($get_dt_gencode_group as $key => $dt) : ?>
											<option value="<?= $dt['gen_code'] ?>"><?= $dt['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Amount:</label>
									<input type="number" data-help-block-id="help_fd_amount" class="form-control amount_field" id="fd_amount" name="fd_amount" autocomplete="off" maxlength="30" value="<?= (isset($fee_cost_data['sum_in_dispute'])) ? $fee_cost_data['sum_in_dispute'] : ''; ?>">
									<p id="help_fd_amount" class="amount_help_block"></p>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Mode of Deposit:</label>
									<select name="fd_mode_of_deposit" id="fd_mode_of_deposit" class="form-control fd_mode_of_deposit">
										<option value="">Select Option</option>
										<?php foreach ($get_mop_list as $mode) : ?>
											<option value="<?= $mode['gen_code'] ?>"><?= $mode['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12">
									<label class="control-label">DIAC Txn. Id:</label>
									<input type="text" class="form-control" id="fd_diac_txn_id" name="fd_diac_txn_id" autocomplete="off" maxlength="80" value="">
								</div>


								<div class="form-group col-md-4 col-sm-6 col-xs-12 required check_bounce_col" style="display: none;">
									<label class="control-label">Cheque Bounce:</label>
									<select name="fd_check_bounce" id="fd_check_bounce" class="form-control fd_check_bounce">
										<option value="">Select Option</option>
										<?php foreach ($this->config->item('check_bounce') as $key => $value) : ?>
											<option value="<?= $key ?>"><?= $value ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
									<label class="control-label">Details of deposit:</label>
									<textarea class="form-control customized-summernote" id="fd_details_of_deposit" name="fd_details_of_deposit" autocomplete="off" rows="4"></textarea>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refdesh"></i> Reset</button>
						<button type="submit" class="btn btn-custom fd_btn_submit" id="fd_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Fee deposit Modal end -->