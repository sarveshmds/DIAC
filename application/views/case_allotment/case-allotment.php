<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body p-0">

						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab_1" class="rc_nav_link" id="nav_case_allotment_general" data-toggle="tab" aria-expanded="true">
										General
									</a>
								</li>
								<li class="">
									<a href="#tab_2" id="nav_case_allotment_individual" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
										Individual Entries
									</a>
								</li>
								<li class="">
									<a href="#tab_3" id="nav_case_re_allotment_bulk" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
										Bulk Re-allocation
									</a>
								</li>
								<li class="">
									<a href="#tab_4" id="nav_case_re_allotment_userwise" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
										User Wise Re-allocation
									</a>
								</li>
								<li class="">
									<a href="#tab_5" id="nav_case_re_allotment_multiple_userwise" class="rc_nav_link" data-toggle="tab" aria-expanded="false">
										User Wise Multiple Re-allocation
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<!-- Pending Applicaiton Tab -->
								<div class="tab-pane active" id="tab_1">
									<div class="table-responsive">

										<table id="dataTableCaseAllotmentGroupWiseList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
											<input type="hidden" id="csrf_ca_trans_token" value="<?php echo generateToken('dataTableCaseAllotmentList'); ?>">

											<?php if (isset($case_no)) : ?>
												<input type="hidden" id="noting_case_no" value="<?php echo $case_no; ?>">
											<?php endif; ?>

											<thead>
												<tr>
													<th style="width:7%;">S. No.</th>
													<th style="width: 20%">DIAC Registration No.</th>
													<th>Case Title</th>
													<th>Deputy Counsel</th>
													<th>Case Manager</th>
													<th>Coordinator</th>
													<th>Allotted On</th>
												</tr>
											</thead>
										</table>

									</div>
								</div>

								<!-- Approved and not registered Applications Tab -->
								<div class="tab-pane" id="tab_2">
									<!-- Filter Box -->
									<div class="row">
										<div class="col-md-12">
											<fieldset class="fieldset">
												<div class="col-md-12">
													<div class="row">

														<div class="col-md-12">
															<div class="form-group col-md-3 col-sm-6 col-xs-12">
																<label class="control-label">DIAC Registration No.:</label>
																<input type="text" class="form-control" id="ca_f_case_no" name="ca_f_case_no" autocomplete="off" maxlength="100" placeholder="Enter Case Number" />
															</div>

															<div class="form-group col-md-3 col-sm-6 col-xs-12">
																<label class="control-label">Allotted to:</label>
																<select name="ca_f_allotted_to" id="ca_f_allotted_to" class="form-control ca_allotted_to select2">
																	<option value="">Select Option</option>
																	<?php foreach ($all_diac_users as $user) : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endforeach; ?>

																</select>
															</div>

															<div class="form-group col-md-3 col-sm-6 col-xs-12 mt-24">
																<button type="button" class="btn btn-default" id="ca_reset_btn" name="ca_f_btn"><i class='fa fa-refresh'></i> Reset</button>
																<button type="submit" class="btn btn-info" id="ca_f_btn" name="ca_f_btn"><i class='fa fa-filter'></i> Filter</button>
															</div>

														</div>
													</div>
												</div>
											</fieldset>
										</div>
									</div>

									<div class="table-responsive">

										<table id="dataTableCaseAllotmentList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
											<input type="hidden" id="csrf_ca_trans_token" value="<?php echo generateToken('dataTableCaseAllotmentList'); ?>">

											<?php if (isset($case_no)) : ?>
												<input type="hidden" id="noting_case_no" value="<?php echo $case_no; ?>">
											<?php endif; ?>

											<thead>
												<tr>
													<th style="width:7%;">S. No.</th>
													<th style="width: 20%">DIAC Registration No.</th>
													<th>Case Title</th>
													<th>Allotted To</th>
													<th>User Role</th>
													<th>Allotted On</th>
													<th style="width:15%;">Action</th>
												</tr>
											</thead>
										</table>

									</div>
								</div>

								<!-- Bulk Re-allocation of case -->
								<div class="tab-pane" id="tab_3">
									<input type="hidden" name="case_re_allocation_token" value="<?= generateToken('case_re_allocation_value') ?>" id="case_re_allocation_token">

									<!-- Case Manager Re-allocation -->
									<div class="box">
										<div class="box-header">
											<h4 class="box-title">
												Case Managers Re-allocations:
											</h4>
										</div>
										<div class="box-body">
											<p>
												Case Managers Re-Allocations of all the cases which are in Pre-Hearing and Under Hearing stage.
											</p>
											<button type="button" class="btn btn-custom btn_re_allocations" id="btn_cm_re_allocations" data-type="CM_CASE_REF_NO" data-role="CASE_MANAGER">
												<i class="fa fa-paper-plane"></i> Re-allocate
											</button>
										</div>
									</div>

									<!-- Deputy Counsel Re-allocation -->
									<div class="box">
										<div class="box-header">
											<h4 class="box-title">
												Deputy Counsel Re-allocations:
											</h4>
										</div>
										<div class="box-body">
											<p>
												Deputy Counsel Re-Allocations of all the cases which are in Pre-Hearing and Under Hearing stage.
											</p>
											<button class="btn btn-custom btn_re_allocations" id="btn_dc_re_allocations" data-type="DC_CASE_REF_NO" data-role="DEPUTY_COUNSEL">
												<i class="fa fa-paper-plane"></i> Re-allocate
											</button>
										</div>
									</div>

									<!-- Additioal Co-ordinators Re-allocation -->
									<div class="box">
										<div class="box-header">
											<h4 class="box-title">
												Additioal Co-ordinators Re-allocations:
											</h4>
										</div>
										<div class="box-body">
											<p>
												Additioal Co-ordinators Re-Allocations of all the cases which are in Pre-Hearing and Under Hearing stage.
											</p>
											<button class="btn btn-custom btn_re_allocations" id="btn_coordinators_re_allocations" data-type="COORDINATOR_CASE_REF_NO" data-role="COORDINATOR">
												<i class="fa fa-paper-plane"></i> Re-allocate
											</button>
										</div>
									</div>
								</div>

								<!-- User wise re-allocation -->
								<div class="tab-pane" id="tab_4">
									<div class="row flex-row">
										<div class="col-md-6 col-xs-12">
											<div class="box">
												<div class="box-header">
													<h4>
														Allocate the case from on deputy counsel to another deputy counsel:
													</h4>
												</div>

												<div class="box-body">

													<input type="hidden" name="csrf_transfer_token" id="csrf_transfer_token" value="<?= generateToken('transfer_case_token'); ?>">

													<div class="row flex-row">
														<div class="col-md-6 col-xs-12 form-group">
															<label for="">DC User (From)</label>
															<select class="form-control" name="user_from" id="dc_user_from">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'DEPUTY_COUNSEL') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-md-6 col-xs-12 form-group">
															<label for="">DC User (To)</label>
															<select class="form-control" name="user_to" id="dc_user_to">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'DEPUTY_COUNSEL') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-xs-12 text-center">
															<button type="button" class="btn btn-custom" id="btn_dc_transfer_case">
																<i class="fa fa-paper-plane"></i> Transfer Cases
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 col-xs-12">
											<div class="box">
												<div class="box-header">
													<h4>
														Allocate the case from on case manager to another case manager:
													</h4>
												</div>

												<div class="box-body">

													<input type="hidden" name="csrf_transfer_token" id="csrf_transfer_token" value="<?= generateToken('transfer_case_token'); ?>">

													<div class="row flex-row">
														<div class="col-md-6 col-xs-12 form-group">
															<label for="">Case Manager User (From)</label>
															<select class="form-control" name="user_from" id="cm_user_from">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'CASE_MANAGER') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-md-6 col-xs-12 form-group">
															<label for="">Case Manager User (To)</label>
															<select class="form-control" name="user_to" id="cm_user_to">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'CASE_MANAGER') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-xs-12 text-center">
															<button type="button" class="btn btn-custom" id="btn_cm_transfer_case">
																<i class="fa fa-paper-plane"></i> Transfer Cases
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 col-xs-12">
											<div class="box">
												<div class="box-header">
													<h4>
														Allocate the case from on additional coordinator to another additional coordinator:
													</h4>
												</div>

												<div class="box-body">

													<input type="hidden" name="csrf_transfer_token" id="csrf_transfer_token" value="<?= generateToken('transfer_case_token'); ?>">

													<div class="row flex-row">
														<div class="col-md-6 col-xs-12 form-group">
															<label for="">Coordinator User (From)</label>
															<select class="form-control" name="user_from" id="coordinator_user_from">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'COORDINATOR') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-md-6 col-xs-12 form-group">
															<label for="">Coordinator User (To)</label>
															<select class="form-control" name="user_to" id="coordinator_user_to">
																<option value="">Select</option>
																<?php foreach ($all_diac_users as $user) : ?>
																	<?php if ($user['primary_role'] == 'COORDINATOR') : ?>
																		<option value="<?= $user['user_code'] ?>"><?= $user['user_display_name'] . ' (' . $user['job_title'] . ')' ?></option>
																	<?php endif; ?>
																<?php endforeach; ?>
															</select>
														</div>

														<div class="col-xs-12 text-center">
															<button type="button" class="btn btn-custom" id="btn_coordinator_transfer_case">
																<i class="fa fa-paper-plane"></i> Transfer Cases
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- User wise multiple selection case re-allocation -->
								<div class="tab-pane" id="tab_5">
									<div class="box">
										<div class="box-header">
											<h4>
												Case Re-allocations from one user to another
											</h4>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-4 col-sm-6 col-xs-12 from-group">
													<label for="">User Type</label>
													<select name="user_type" id="mul_user_type">
														<option value="">Select</option>
														<option value="DEPUTY_COUNSEL">Deputy Counsel</option>
														<option value="CASE_MANAGER">Case Manager</option>
														<option value="COORDINATOR">Coordinator</option>
													</select>
												</div>

												<div class="col-md-4 col-sm-6 col-xs-12 from-group">
													<label for="">User (From)</label>
													<select name="user_type" id="mul_user_type">
														<option value="">Select</option>
													</select>
												</div>

												<div class="col-md-4 col-sm-6 col-xs-12 from-group">
													<label for="">User (From)</label>
													<select name="user_type" id="mul_user_type">
														<option value="">Select</option>
													</select>
												</div>

												<div class="col-md-4 col-sm-6 col-xs-12 from-group text-center">
													<button class="btn btn-custom">
														<i class="fa fa-paper-plane"></i> Transfer Cases
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>


					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/case-allotment.php'); ?>

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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/case-allotment.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/case-allotment.js')) ?>"></script>