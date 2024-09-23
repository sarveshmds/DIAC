<!-- Noting Modal start -->
<div id="addNotingListModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title noting-modal-title" style="text-align: center;"></h4>
			</div>
			<div class="modal-body">

				<?= form_open(null, array('class' => 'wfst', 'id' => 'noting_form')) ?>

				<input type="hidden" id="noting_op_type" name="op_type" value="ADD_CASE_COUNSEL">

				<?php if (isset($case_no)) : ?>
					<input type="hidden" id="noting_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
				<?php endif; ?>

				<input type="hidden" id="hidden_noting_id" name="hidden_noting_id" value="">

				<input type="hidden" name="csrf_noting_form_token" value="<?php echo generateToken('case_noting_form'); ?>">

				<div id="fr_wrapper">
					<fieldset class="fieldset noting_fieldset" id="noting_fieldset">
						<legend class="noting_legend_head">Noting Details</legend>
						<div class="fieldset-content-box">
							<div class="row">

								<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
									<label class="control-label">Noting:</label>
									<textarea class="form-control customized-summernote" id="noting_text" name="noting_text"></textarea>
								</div>

								<div class="form-group col-xs-12">
									<div class="row">
										<div class="form-group col-md-12 col-sm-6 col-xs-12 required">
											<label for="">Select Noting Text</label>
											<select name="noting_text_code" id="noting_text_code" class="form-control select2">
												<option value="">Select Noting Text</option>
												<?php foreach ($noting_texts as $text) : ?>
													<option value="<?= $text['gen_code'] ?>">* <?= $text['description'] ?></option>
												<?php endforeach; ?>

											</select>
										</div>
									</div>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Marked to/File sent:</label>
									<select name="noting_marked_to" id="noting_marked_to" class="form-control noting_appearing_for select2">
										<option value="">Select Option</option>
										<?php foreach ($case_users as $user) : ?>
											<?php if ($user['user_code'] != $this->session->userdata('user_code')) : ?>
												<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'], ' (' . $user['job_title'] . ')' ?></option>
											<?php endif; ?>
										<?php endforeach; ?>

									</select>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
									<label class="control-label">Date of noting:</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control" id="noting_date" name="noting_date" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y') ?>" />
									</div>
								</div>

								<div class="form-group col-md-4 col-sm-6 col-xs-12">
									<label class="control-label">Next Date:</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control custom-all-date" id="noting_next_date" name="noting_next_date" autocomplete="off" readonly="true" value="" data-date-start-date="0d" />
									</div>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-xs-12">
									<label for="" class="">Upload File (If any)</label>
									<input type="file" class="form-control documents-input" name="cd_noting_file[]" id="cd_noting_file" accept=".pdf, .doc, .docx" multiple>
									<small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_NOTING_FILE_FORMATS_ALLOWED ?> are allowed.</small>
									<input type="hidden" id="hidden_noting_file_name" name="hidden_noting_file_name" value=''>
								</div>

							</div>
						</div>
					</fieldset>

				</div>

				<div class="row text-center mt-25">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						<button type="submit" class="btn btn-custom noting_btn_submit" id="noting_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
					</div>
				</div>
				<?= form_close() ?>

			</div>
		</div>
	</div>
</div>
<!-- Noting Modal end -->