<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<div>

							<ul class="nav nav-tabs" id="nav-tab" role="tablist">
								<li class="active">
									<a class="nav-item nav-link" id="nav-sop-tab" data-toggle="tab" href="#nav-sop" role="tab" aria-controls="nav-sop" aria-selected="true">Status of Pleadings</a>
								</li>

								<li>
									<a class="nav-item nav-link" id="nav-op-tab" data-toggle="tab" href="#nav-op" role="tab" aria-controls="nav-op" aria-selected="false">Miscellaneous</a>
								</li>
								<!-- <li>
								    	<a class="nav-item nav-link" id="nav-oc-tab" data-toggle="tab" href="#nav-oc" role="tab" aria-controls="nav-oc" aria-selected="false">Other Correspondance</a>
								    </li> -->
							</ul>

							<div class="tab-content" id="nav-tabContent">
								<!-- Status  of Pleedings Start -->
								<div class="tab-pane fade active" id="nav-sop" role="tabpanel" aria-labelledby="nav-sop-tab">
									<div class="tab-content-col">
										<?= form_open(null, array('class' => 'wfst', 'id' => 'sop_form')) ?>
										<input type="hidden" id="sop_op_type" name="sop_op_type" value="<?php echo (isset($sop_data['id'])) ? 'EDIT_CASE_STATUS_PLEADINGS' : 'ADD_CASE_STATUS_PLEADINGS' ?>">

										<?php if (isset($case_no)) : ?>
											<input type="hidden" id="hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">
										<?php endif; ?>

										<?php if (isset($sop_data['id'])) : ?>
											<input type="hidden" id="hidden_sop_id" name="hidden_sop_id" value="<?php echo $sop_data['id']; ?>">
										<?php endif; ?>

										<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('case_det_form'); ?>">

										<div class="row">
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Claim Invited On:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="claim_invited_on" name="claim_invited_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['claim_invited_on'])) ? $sop_data['claim_invited_on'] : '' ?>" />
												</div>
											</div>

											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Reminder to claim:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="rem_to_claim" name="rem_to_claim" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['rem_to_claim_on'])) ? $sop_data['rem_to_claim_on'] : '' ?>" />
												</div>
											</div>

											<?php if (isset($sop_data['rem_to_claim_on']) && !empty($sop_data['rem_to_claim_on'])) : ?>
												<div class="form-group col-md-3 col-sm-6 col-xs-12">
													<label class="control-label">Reminder to claim (2):</label>
													<div class="input-group date">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input type="text" class="form-control custom-all-date" id="rem_to_claim_2" name="rem_to_claim_2" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['rem_to_claim_on_2'])) ? $sop_data['rem_to_claim_on_2'] : '' ?>" />
													</div>
												</div>
											<?php endif; ?>

											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Claim filed On:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="claim_filed_on" name="claim_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['claim_filed_on'])) ? $sop_data['claim_filed_on'] : '' ?>" />
												</div>
											</div>

											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Respondent served on:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="res_served_on" name="res_served_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['res_served_on'])) ? $sop_data['res_served_on'] : '' ?>" />
												</div>
											</div>

											<div class="form-group col-md-3 col-sm-6 col-xs-12 ">
												<label class="control-label">Statement of defence filed on:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="sod_filed_on" name="sod_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['sod_filed_on'])) ? $sop_data['sod_filed_on'] : '' ?>" />
												</div>
											</div>
											<!-- 
													<div class="form-group col-md-3 col-sm-6 col-xs-12 ">
														<label class="control-label">Whether filed beyond period of limitation:</label>
														Yes: <input type="radio" name="sod_p_of_limitation" value="yes" class="sod_p_of_limitation" <?php echo (isset($sop_data['sod_pol']) && $sop_data['sod_pol'] == 'yes') ? 'checked' : '' ?>>
														No: <input type="radio" name="sod_p_of_limitation" value="no" class="sod_p_of_limitation" <?php echo (isset($sop_data['sod_pol']) && $sop_data['sod_pol'] == 'no') ? 'checked' : '' ?>>
													</div>

													<?php // If yes then take number of days 
													?>
													<div class="form-group col-md-3 col-sm-6 col-xs-12 ">
														<label class="control-label">Number of days of delay:</label>
														<input type="text" class="form-control" id="sod_no_of_dod" name="sod_no_of_dod"  autocomplete="off" maxlength="20" onkeypress="return event.charCode >=47 && event.charCode <= 57" value="<?php echo (isset($sop_data['sod_dod']) && $sop_data['sod_dod'] != '') ? $sop_data['sod_dod'] : '' ?>" <?php echo (isset($sop_data['sod_pol']) && $sop_data['sod_pol'] == 'yes') ? '' : 'disabled' ?>>
													</div> -->

											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Rejoinder to Statement of Defence filed on:</label>
												<div class="input-group date">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" class="form-control custom-all-date" id="sop_rejoin_sod_filed_on" name="sop_rejoin_sod_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['rej_stat_def_filed_on'])) ? $sop_data['rej_stat_def_filed_on'] : '' ?>" />
												</div>
											</div>

											<!-- ======================================================= -->
											<div class="col-xs-12">
												<fieldset class="fieldset">
													<legend>Counter Claim</legend>
													<div class="fieldset-content-box">
														<div class="form-group col-md-3 col-sm-6 col-xs-12 ">
															<label class="control-label">Counter Claim:</label>
															<div>
																Yes: <input type="radio" name="counter_claim" value="yes" class=" counter_claim" <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? 'checked' : '' ?>> &nbsp;&nbsp;&nbsp;
																No: <input type="radio" name="counter_claim" value="no" class=" counter_claim" <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'no') ? 'checked' : '' ?>>
															</div>
														</div>

														<?php // If yes 
														?>
														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Date of filing of Counter Claim:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date sop_cc_disabled" id="sop_dof_cc" name="sop_dof_cc" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['dof_counter_claim'])) ? $sop_data['dof_counter_claim'] : '' ?>" <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? '' : 'disabled' ?> />
															</div>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Reply to Counter Claim filed on:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date sop_cc_disabled" id="sop_rt_cc_filed_on" name="sop_rt_cc_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['reply_counter_claim_on'])) ? $sop_data['reply_counter_claim_on'] : '' ?>" <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? '' : 'disabled' ?> />
															</div>
														</div>

														<!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
																	<label class="control-label">Whether filed beyond period of limitation:</label>
																	<div>
																		Yes: <input type="radio" name="sop_cc_p_of_limitation" value="yes" class="sop_cc_p_of_limitation sop_cc_disabled" <?php echo (isset($sop_data['reply_counter_claim_pol']) && $sop_data['reply_counter_claim_pol'] == 'yes') ? 'checked' : '' ?> <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? '' : 'disabled' ?>>

																		No: <input type="radio" name="sop_cc_p_of_limitation" value="no" class="sop_cc_p_of_limitation sop_cc_disabled" <?php echo (isset($sop_data['reply_counter_claim_pol']) && $sop_data['reply_counter_claim_pol'] == 'no') ? 'checked' : '' ?> <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? '' : 'disabled' ?>>
																	</div>
																</div>

																<?php // If yes then take number of days 
																?>
																<div class="form-group col-md-3 col-sm-6 col-xs-12">
																	<label class="control-label">Number of days of delay:</label>
																	<input type="text" class="form-control" id="sop_cc_no_of_dod" name="sop_cc_no_of_dod"  autocomplete="off" maxlength="20" onkeypress="return event.charCode >=47 && event.charCode <= 57" value="<?php echo (isset($sop_data['reply_counter_claim_dod'])) ? $sop_data['reply_counter_claim_dod'] : '' ?>" <?php echo (isset($sop_data['reply_counter_claim_pol']) && $sop_data['reply_counter_claim_pol'] == 'yes') ? '' : 'disabled' ?>>
																</div> -->

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Rejoinder to Reply of Counter Claim filed on:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date sop_cc_disabled" id="sop_rej_cc_filed_on" name="sop_rej_cc_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['rej_reply_counter_claim_on'])) ? $sop_data['rej_reply_counter_claim_on'] : '' ?>" <?php echo (isset($sop_data['counter_claim']) && $sop_data['counter_claim'] == 'yes') ? '' : 'disabled' ?> />
															</div>
														</div>
													</div>
												</fieldset>
											</div>
											<!-- ================================================================ -->
											<div class="col-xs-12">
												<fieldset class="fieldset">
													<legend>Application under Section 17 of A&C Act</legend>
													<div class="fieldset-content-box">
														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Application under Section 17 of A&C Act:</label>
															Yes: <input type="radio" name="sop_app_act" value="yes" class="sop_app_act" <?php echo (isset($sop_data['app_section']) && $sop_data['app_section'] == 'yes') ? 'checked' : '' ?>>
															No: <input type="radio" name="sop_app_act" value="no" class="sop_app_act" <?php echo (isset($sop_data['app_section']) && $sop_data['app_section'] == 'no') ? 'checked' : '' ?>>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Date of filing of Application:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date aac_dof_app_disabled" id="sop_dof_app" name="sop_dof_app" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['dof_app'])) ? $sop_data['dof_app'] : '' ?>" <?php echo (isset($sop_data['app_section']) && $sop_data['app_section'] == 'yes') ? '=' : 'disabled' ?> />
															</div>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Date of Decision:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date aac_dof_app_disabled" id="sop_rep_app_filed_on" name="sop_rep_app_filed_on" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['reply_app_on'])) ? $sop_data['reply_app_on'] : '' ?>" <?php echo (isset($sop_data['app_section']) && $sop_data['app_section'] == 'yes') ? '=' : 'disabled' ?> />
															</div>
														</div>
													</div>
												</fieldset>

												<!-- Application under section 16 -->
												<fieldset class="fieldset">
													<legend>Application under Section 16 of A&C Act</legend>
													<div class="fieldset-content-box">
														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Application under Section 16 of A&C Act:</label>
															Yes: <input type="radio" name="sop_app_act_16" value="yes" class="sop_app_act_16" <?php echo (isset($sop_data['app_section_16']) && $sop_data['app_section_16'] == 'yes') ? 'checked' : '' ?>>
															No: <input type="radio" name="sop_app_act_16" value="no" class="sop_app_act_16" <?php echo (isset($sop_data['app_section_16']) && $sop_data['app_section_16'] == 'no') ? 'checked' : '' ?>>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Date of filing of Application:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date aac_dof_app_disabled_16" id="sop_dof_app_16" name="sop_dof_app_16" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['dof_app_16'])) ? $sop_data['dof_app_16'] : '' ?>" <?php echo (isset($sop_data['app_section_16']) && $sop_data['app_section_16'] == 'yes') ? '=' : 'disabled' ?> />
															</div>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Date of Decision:</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control custom-all-date aac_dof_app_disabled_16" id="sop_rep_app_filed_on_16" name="sop_rep_app_filed_on_16" autocomplete="off" readonly="true" value="<?php echo (isset($sop_data['reply_app_on_16'])) ? $sop_data['reply_app_on_16'] : '' ?>" <?php echo (isset($sop_data['app_section_16']) && $sop_data['app_section_16'] == 'yes') ? '=' : 'disabled' ?> />
															</div>
														</div>
													</div>
												</fieldset>

												<div class="form-group col-md-12 col-sm-12 col-xs-12">
													<label class="control-label">Remarks:</label>
													<textarea class="form-control" rows="3" id="sop_remarks" name="sop_remarks"><?php echo (isset($sop_data['remarks'])) ? $sop_data['remarks'] : '' ?></textarea>

												</div>
											</div>

										</div>
										<div class="row text-center mt-25">
											<div class="col-xs-12">
												<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
												<button type="submit" class="btn btn-custom" id="sop_btn_submit"><i class='fa fa-paper-plane'></i><?php echo (isset($sop_data['id'])) ? 'Update Details' : 'Save Details'; ?></button>
											</div>
										</div>
										<?= form_close() ?>

									</div>

								</div>
								<!-- Status  of Pleedings End -->

								<!-- ====================================================== -->
								<!-- Other pleadinds Start -->
								<div class="tab-pane fade" id="nav-op" role="tabpanel" aria-labelledby="nav-op-tab">
									<div class="tab-content-col">
										<div>
											<table id="dataTableOtherPleadingList" class="table table-condensed table-striped table-bordered dt-responsive display" data-page-size="10">
												<input type="hidden" id="csrf_op_trans_token" value="<?php echo generateToken('dataTableOPList'); ?>">

												<?php if (isset($case_no)) : ?>
													<input type="hidden" id="op_case_no" value="<?php echo $case_no; ?>">
												<?php endif; ?>

												<thead>
													<tr>
														<th style="width:8%;">S. No.</th>
														<th style="">Details</th>
														<th style="width: 20%;">Date of filing</th>
														<th style="width: 20%;">Filed by</th>
														<th style="width:15%;">Action</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
								<!-- Other pleadings end -->

								<!-- ====================================================== -->
								<!-- Other Correspondance Start -->
								<!-- <div class="tab-pane fade" id="nav-oc" role="tabpanel" aria-labelledby="nav-oc-tab">
									  	<div class="tab-content-col">
											<fieldset class="fieldset">
												<legend>Other Correspondance</legend>
												<div class="fieldset-content-box">
													<table id="dataTableOtherCorrespondanceList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
									                    <input type="hidden" id="csrf_oc_trans_token" value="<?php echo generateToken('dataTableOCList'); ?>">
									                    
									                    <?php if (isset($case_no)) : ?>
									                    <input type="hidden" id="oc_case_no" value="<?php echo $case_no; ?>">
										                <?php endif; ?>

									                    <thead>
									                        <tr>
																<th style="width:8%;">S. No.</th>
																<th style="">Details</th>
																<th style="width: 10%;">Date of Correspondance</th>
																<th style="width: 10%;">Send by</th>
																<th style="width: 10%;">Sent to</th>
									                            <th style="width:15%;">Action</th>
									                        </tr>
									                    </thead>
									                </table>
									            </div>
									        </fieldset>
									  	</div>
									</div> -->
								<!-- Other Correspondance end -->
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/other-pleadings.php'); ?>
<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/other-correspondance.php'); ?>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/status-of-pleadings.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/other-pleadings.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/other-correspondance.js') ?>"></script>