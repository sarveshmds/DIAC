<!-- Case Order Modal start -->
<div id="addCaseOrderModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title case-order-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'case-order-form', 'enctype' => 'multipart/form-data')) ?>

				<input type="hidden" id="case_order_op_type" name="op_type" value="ADD_CASE_ORDER">

				<!-- <input type="hidden" id="hidden_counsel_id" name="hidden_counsel_id" value=""> -->
				<input type="hidden" id="case_code" name="case_code" value="<?= $case_no ?>">

				<input type="hidden" name="csrf_case_order_form_token" value="<?php echo generateToken('case_order_form'); ?>">

				<div id="fr_wrapper">
					<fieldset class="fieldset case_order_fieldset" id="case_order_fieldset">
						<legend class="case_order_legend_head">Case Order Details</legend>
						<div class="fieldset-content-box">
							<div class="row">
								<input type="hidden" id="hidden_code" name="hidden_code" value="">

								<div class="form-group col-xs-12">
									<label class="control-label">Document:</label>
									<input type="file" class="form-control pdf-documents-input" id="case_order_doc" name="case_order_doc" autocomplete="off" maxlength="100" value="" accept=".pdf">
								</div>

								<div class="form-group col-md-6 col-xs-12 required">
									<label class="control-label">Order Given On:</label>
									<input type="text" class="form-control custom-all-date" id="order_given_on" name="order_given_on" autocomplete="off" maxlength="100" value="<?= date('d-m-Y') ?>">
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom case_order_btn_submit" id="case_order_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Case Order Modal end -->