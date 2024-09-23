<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body ">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Arbitration Type:</p>
								<!-- <p><?= $asses_details['assessment_sheet_data'] ?></p> -->
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Arbitral Tribunal Strength:</p>
								<p><?= $asses_details['arbitral_tribunal_strength'] ?></p>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Whether Claims or Counter Claims assessed separately:</p>
								<p><?= $asses_details['c_cc_asses_sep'] ?></p>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Sum in Dispute (Claims):</p>
								<p><?= $asses_details['sum_in_dispute_claim'] ?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Sum in Dispute (Counter Claims):</p>
								<p><?= $asses_details['sum_in_dispute_cc'] ?></p>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>As per provisional assessment dated:</p>
								<p><?= $asses_details['asses_date'] ?></p>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<p>Assessment Approved:</p>
								<p><?= $asses_details['assessment_approved'] ?></p>
							</div>
							<div class="form-group col-md-3 col-sm-6 col-xs-12">
				                <label class="control-label">Assessmemt Sheet (If any):</label>
				                <div>
				                    <?= (isset($asses_details['assessment_sheet_doc']) && !empty($asses_details['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $asses_details['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
				                </div>
				            </div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<fieldset class="fieldset">
									<legend>Prayers</legend>
									<div class="fieldset-content-box">
										<div class="row">
											<div class="col-md-6 col-xs-12">
												<label>Sum in Dispute (Claim) (Rs.):</label>
												<?php if (isset($prayers_details) && count($prayers_details) > 0) : ?>
												<div class="claimant_claim_amount_det">
													<?php $claim_total_amount = 0; ?>
													<?php foreach ($prayers_details as $cp) : ?>
													<div class="claimant_claim_amount_list_col">
														<div class="claimant_claim_amount_list_row">
															<p class="mb-0">
																<strong><?= $cp['prayer_name'] ?>:</strong>
																<?= $cp['prayer_amount'] ?>
															</p>
														</div>
													</div>
													<?php $claim_total_amount += (int) $cp['prayer_amount'] ?>
													<?php endforeach; ?>
													<div class="claimant_sum_in_dispute">
														<strong>Total: </strong> <?= $claim_total_amount ?>
													</div>
												</div>
												<?php endif; ?>
											</div>
											<div class="col-md-6 col-xs-12">
												<label for="">Sum in Dispute (Counter Claim) (Rs.):
												</label>
												<?php if (isset($cc_prayers_details) && count($cc_prayers_details) > 0) : ?>
												<div class="respondent_claim_amount_det">
													<?php $cc_claim_total_amount = 0; ?>
													<?php foreach ($cc_prayers_details as $cp) : ?>
													<div class="respondent_claim_amount_list_col">
														<div class="respondent_claim_amount_list_row">
															<p class="mb-0">
																<strong><?= $cp['prayer_name'] ?>:</strong>
																<?= $cp['prayer_amount'] ?>
															</p>
														</div>
													</div>
													<?php $cc_claim_total_amount += (int) $cp['prayer_amount'] ?>
													<?php endforeach; ?>
													<div class="respondent_sum_in_dispute">
														<strong>Total: </strong> <?= $cc_claim_total_amount ?>
													</div>
												</div>
											</div>
											<?php endif; ?>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
							<div class="col-md-6 col-xs-12">
								<fieldset class="fieldset">
									<legend class="legend">
										Calculated Fees
									</legend>
									<?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'DOMESTIC') : ?>
									<div class="fieldset-content-box">
										<div class="row">
											<div class="form-group col-md-12 col-xs-12">
												<label class="control-label">Total Arbitrators Fees:</label>
												<div>
													<?= (isset($asses_details['total_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $asses_details['total_arb_fees']) : ''  ?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<?php  // Claimant Share
												?>
												<div class="box box-warning">
													<div class="box-header with-border">
														<h4 class="box-title">Claimant Share</h4>
													</div>
													<div class="box-body">
														<div class="form-group col-md-12 col-xs-12">
															<label class="control-label">Arbitrators Fees:</label>
															<div>
																<?= (isset($asses_details['cs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $asses_details['cs_arb_fees']) : ''  ?>
															</div>
														</div>
														<div class="form-group col-md-12 col-xs-12">
															<label class="control-label">Administrative Expenses:</label>
															<div>
																<?= (isset($asses_details['cs_adminis_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $asses_details['cs_adminis_fees']) : ''  ?>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<?php  // Respondent Share
												?>
												<div class="box box-warning">
													<div class="box-header with-border">
														<h4 class="box-title">Respondent Share</h4>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="form-group col-md-12 col-xs-12 col-xs-12">
																<label class="control-label">Arbitrators Fees:</label>
																<div>
																	<?= (isset($asses_details['rs_arb_fees'])) ? symbol_checker($case_data['type_of_arbitration'], $asses_details['rs_arb_fees']) : ''  ?>
																</div>
															</div>
															<div class="form-group col-md-12 col-xs-12 col-xs-12">
																<label class="control-label">Administrative Expenses:</label>
																<div>
																	<?= (isset($asses_details['rs_adminis_fee'])) ? symbol_checker($case_data['type_of_arbitration'], $asses_details['rs_adminis_fee']) : ''  ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php endif; ?>
									<?php if (isset($case_data['type_of_arbitration']) && $case_data['type_of_arbitration'] == 'INTERNATIONAL') : ?>
									<div class="fieldset-content-box">
										<div class="row">
											<div class="form-group col-md-12 col-xs-12">
												<label class="control-label">Total Arbitrators Fees:</label>
												<div>
													<p>
														<?= (isset($asses_details['int_arb_total_fees_dollar'])) ? dollarMoneyFormat($asses_details['int_arb_total_fees_dollar']) : ''  ?>
													</p>
													<p>
														<?= (isset($asses_details['int_arb_total_fees_rupee'])) ? INDMoneyFormat($asses_details['int_arb_total_fees_rupee']) : ''  ?>
													</p>
												</div>
											</div>
											<div class="form-group col-md-12 col-xs-12">
												<label class="control-label">Total Administrative Charges:</label>
												<div>
													<p>
														<?= (isset($asses_details['int_total_adm_charges_dollar'])) ? dollarMoneyFormat($asses_details['int_total_adm_charges_dollar']) : ''  ?>
													</p>
													<p>
														<?= (isset($asses_details['int_total_adm_charges_rupee'])) ? INDMoneyFormat($asses_details['int_total_adm_charges_rupee']) : ''  ?>
													</p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<?php  // Claimant Share
												?>
												<div class="box box-warning">
													<div class="box-header with-border">
														<h4 class="box-title">Claimant Share</h4>
													</div>
													<div class="box-body">
														<div class="form-group col-md-12 col-xs-12 col-xs-12">
															<label class="control-label">Arbitrators Fees:</label>
															<div>
																<p>
																	<?= (isset($asses_details['int_arb_claim_share_fees_dollar'])) ? dollarMoneyFormat($asses_details['int_arb_claim_share_fees_dollar']) : ''  ?>
																</p>
																<p>
																	<?= (isset($asses_details['int_arb_claim_share_fees_rupee'])) ? INDMoneyFormat($asses_details['int_arb_claim_share_fees_rupee']) : ''  ?>
																</p>
															</div>
														</div>
														<div class="form-group col-md-12 col-xs-12 col-xs-12">
															<label class="control-label">Administrative Expenses:</label>
															<div>
																<p>
																	<?= (isset($asses_details['int_claim_adm_charges_dollar'])) ? dollarMoneyFormat($asses_details['int_claim_adm_charges_dollar']) : ''  ?>
																</p>
																<p>
																	<?= (isset($asses_details['int_claim_adm_charges_rupee'])) ? INDMoneyFormat($asses_details['int_claim_adm_charges_rupee']) : ''  ?>
																</p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<?php  // Respondent Share
												?>
												<div class="box box-warning">
													<div class="box-header with-border">
														<h4 class="box-title">Respondent Share</h4>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="form-group col-md-12 col-xs-12 col-xs-12">
																<label class="control-label">Arbitrators Fees:</label>
																<div>
																	<p>
																		<?= (isset($asses_details['int_arb_res_share_fees_dollar'])) ? dollarMoneyFormat($asses_details['int_arb_res_share_fees_dollar']) : ''  ?>
																	</p>
																	<p>
																		<?= (isset($asses_details['int_arb_res_share_fees_rupee'])) ? INDMoneyFormat($asses_details['int_arb_res_share_fees_rupee']) : ''  ?>
																	</p>
																</div>
															</div>
															<div class="form-group col-md-12 col-xs-12 col-xs-12">
																<label class="control-label">Administrative Expenses:</label>
																<div>
																	<p>
																		<?= (isset($asses_details['int_res_adm_charges_dollar'])) ? dollarMoneyFormat($asses_details['int_res_adm_charges_dollar']) : ''  ?>
																	</p>
																	<p>
																		<?= (isset($asses_details['int_res_adm_charges_rupee'])) ? INDMoneyFormat($asses_details['int_res_adm_charges_rupee']) : ''  ?>
																	</p>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</fieldset>
							</div>
							<div class="col-md-6 col-xs-12">
								<fieldset class="fieldset">
									<legend class="legend">Assessment Details</legend>
									<div class="fieldset-content-box">
										<div id="show_calculated_fees_div_<?= $asses_details['id'] ?>" style="display: none;" class="calculated_fees_content_div">
											<div id="calculated_fees_content_div_<?= $asses_details['id'] ?>">
											</div>
										</div>
									</div>
								</fieldset>
							</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<fieldset class="fieldset">
										<legend class="legend">Change Status</legend>
										<div class="fieldset-content-box">
											
											<form id="fees_asses_form" method="POST">
												<input type="hidden" name="csrf_trans_token" value="<?php echo generateToken('fees_asses_token'); ?>">
												<div class="form-group col-md-6">
													<input type="hidden" name="asses_code" id="asses_code" value="<?= $asses_details['code'] ?>">
													<label class="control-label">Status:</label>
													<select class="form-control" name="appr_status" id="appr_status">
														<option>Select Option</option>
														<option value="1"<?= isset($asses_details['assessment_approved']) && $asses_details['assessment_approved'] == 1 ? 'selected' : '' ?>>Approved</option>
														<option value="2" <?= isset($asses_details['assessment_approved']) && $asses_details['assessment_approved'] == 2 ? 'selected' : '' ?>>Reject</option>
													</select>
												</div>
												<div class="form-group col-md-6 remarks_col">
													<label class="control-label">Remarks:</label>
													<textarea class="form-control" name="remarks" id="remarks"></textarea>
												</div>
												<div class="col-md-12 col-xs-12 text-center">
													<button type="submit" class="btn btn-custom " id="asses_submit_btn">Update</button>
												</div>
											</form>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
	<script>
	var base_url 	= "<?php echo base_url(); ?>";
	var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
	</script>
	<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/fees-assessment.js') ?>"></script>
	<!-- EDIT_CASE_FEE_COST_DETAILS -->
	<?php if (isset($asses_details['id'])) : ?>
	<script>
	{
	let id = '<?= $asses_details['id'] ?>';
	let assessment_sheet_temp_data_id = '<?= $asses_details['assessment_sheet_data'] ?>';
	console.log(assessment_sheet_temp_data_id);
	if (assessment_sheet_temp_data_id) {
	let assessment_sheet_decoded_data_id = JSON.parse(assessment_sheet_temp_data_id);
	let assessment_sheet_data_final_id = assessment_sheet_decoded_data_id[0];
	console.log(assessment_sheet_data_final_id)
	let calc_div_element_ID_id = 'calculated_fees_content_div_' + id;
	let show_calc_div_element_ID_id = 'show_calculated_fees_div_' + id;
	if (assessment_sheet_data_final_id.claim_assessed_seperately === 'yes') {
	let {
	type_of_arbitration,
	claim_amount,
	counter_claim_amount,
	data,
	claim_assessed_seperately
	} = assessment_sheet_data_final_id;
	generate_assessment_view(type_of_arbitration, data, claim_amount, counter_claim_amount, claim_assessed_seperately, calc_div_element_ID_id, show_calc_div_element_ID_id);
	}
	if (assessment_sheet_data_final_id.claim_assessed_seperately === 'no') {
	let {
	type_of_arbitration,
	claim_amount,
	data,
	claim_assessed_seperately
	} = assessment_sheet_data_final_id;
	generate_assessment_view(type_of_arbitration, data, claim_amount, 0, claim_assessed_seperately, calc_div_element_ID_id, show_calc_div_element_ID_id);
	}
	delete id;
	}
	}

	
	</script>
	<?php endif; ?>