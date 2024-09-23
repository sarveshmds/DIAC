<!-- Fee Refund Modal start -->
<div id="addFeeRefListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title fee-ref-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'fee_ref_form')) ?>

				<input type="hidden" id="fee_ref_op_type" name="op_type" value="ADD_CASE_FEE_REFUND">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="fee_ref_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_fee_ref_id" name="hidden_fee_ref_id" value="">

				<input type="hidden" name="csrf_fee_ref_form_token" value="<?php echo generateToken('case_fee_ref_form'); ?>">

				<div id="fee_ref_wrapper">
					<fieldset class="fieldset fee_ref_fieldset" id="fee_ref_fieldset">
						<legend class="fee_ref_legend_head">Fee Refund</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Date of refund:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="fee_ref_date_of_refund" name="fee_ref_date_of_refund" autocomplete="off" readonly="true" value="" />
									</div>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Refunded to:</label>
									<select name="fee_ref_refunded_to" id="fee_ref_refunded_to" class="form-control fee_ref_refunded_to">
										<option value="">Select Option</option>
										<?php foreach ($claim_res_data as $cr_data) : ?>
											<option value="<?= $cr_data['id'] ?>"><?= $cr_data['name'] . '(' . $cr_data['count_number'] . ')' ?></option>
										<?php endforeach; ?>

									</select>
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Name of party to whom refunded:</label>
									<input type="text" class="form-control" id="fee_ref_name_of_party" name="fee_ref_name_of_party" autocomplete="off" maxlength="250" value="">
								</div>
								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Refunded towards:</label>
									<select name="fee_ref_refunded_towards" id="fee_ref_refunded_towards" class="form-control fee_ref_refunded_towards">
										<option value="">Select Option</option>
										<?php foreach ($get_dt_gencode_group as $key => $dt) : ?>
											<option value="<?= $dt['gen_code'] ?>"><?= $dt['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Amount:</label>
									<input type="number" data-help-block-id="help_fee_ref_amount" class="form-control amount_field" id="fee_ref_amount" name="fee_ref_amount" autocomplete="off" maxlength="30" value="<?= (isset($fee_cost_data['sum_in_dispute'])) ? $fee_cost_data['sum_in_dispute'] : ''; ?>">
									<p id="help_fee_ref_amount" class="amount_help_block"></p>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Mode of Refund:</label>
									<select name="fee_ref_mode_of_refund" id="fee_ref_mode_of_refund" class="form-control fee_ref_mode_of_refund">
										<option value="">Select Option</option>
										<?php foreach ($get_mop_list as $mode) : ?>
											<option value="<?= $mode['gen_code'] ?>"><?= $mode['description'] ?></option>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
									<label class="control-label">Details of refund:</label>
									<textarea class="form-control customized-summernote" id="fee_ref_details_of_refund" name="fee_ref_details_of_refund" autocomplete="off" rows="4"></textarea>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refdesh"></i> Reset</button>
						<button type="submit" class="btn btn-custom fee_ref_btn_submit" id="fee_ref_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Fee Refund Modal end -->