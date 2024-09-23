<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<section class="content-header">

		<div class="row">

			<div class="col-md-6 col-sm-12 col-xs-12">
				<h3 style="display: inline-block; margin-top: 5px; margin-bottom: 0px;"> <?= $page_title ?></h3>
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="pull-right vc-header-btn-col">

					<!-- <a href="<?= base_url('pdf/case-details/') . $case_data['case_data']['cdt_slug'] ?>" class="btn btn-danger " style="margin-right: 4px;" target="_BLANK"><span class="fa fa-download"></span> Download</a> -->

					<a href="<?= base_url('all-cases'); ?>" class="btn btn-default "><span class="fa fa-backward"></span> Go Back</a>
				</div>
			</div>

		</div>

	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box">

					<div class="box-body">
						<div class="col-lg-12">
							<div id="accordion">
								<h3 class="accordion-header"><i class="fa fa-file"></i> Case Details

									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="row">
										<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">Diary Number:</label>
											<p class="mb-0">
												<?= (isset($case_details['diary_number_prefix'])) ? $case_details['diary_number_prefix'] : '' ?>/<?= (isset($case_details['diary_number'])) ? $case_details['diary_number'] : '' ?>/<?= (isset($case_details['diary_number_year'])) ? $case_details['diary_number_year'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
											<label class="control-label">DIAC Registration No.:</label>
											<p class="mb-0">
												<?= get_full_case_number($case_details) ?>
											</p>
										</div>

										<div class="form-group col-md-6 col-sm-6 col-xs-12 required">
											<label class="control-label">Case Title:</label>
											<p class="mb-0">
												<?= (isset($case_details['case_title'])) ? $case_details['case_title'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
											<label for="" class="control-label">Mode of reference:</label>
											<p class="mb-0">
												<?= (isset($case_details['reffered_by'])) ? $case_details['reffered_by_desc'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Date of reference:</label>

											<p class="mb-0">
												<?= (isset($case_details['reffered_on'])) ? formatReadableDate($case_details['reffered_on']) : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
											<label for="" class="control-label">Court:</label>
											<p class="mb-0">
												<?= (isset($case_details['reffered_by_court'])) ? $case_details['court_name'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered_by_court_details" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') ? 'style="display: block;"' : 'style="display: none;"' ?>>
											<label for="" class="control-label">Name of Judges:</label>
											<p class="mb-0">
												<?= (isset($case_details['reffered_by_judge'])) ? $case_details['reffered_by_judge'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 reffered-other-name-col" <?= (isset($case_details['reffered_by']) && !empty($case_details['reffered_by']) && $case_details['reffered_by'] == 'OTHER') ? 'style="display: block;"' : 'style="display: none;"' ?>>
											<label for="" class="control-label">Name of Authority:</label>
											<p class="mb-0">
												<?= (isset($case_details['name_of_court'])) ? $case_details['name_of_court'] : '' ?>
											</p>

										</div>

										<?php if (isset($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') : ?>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Case Type:</label>
												<p class="mb-0">
													<?= (isset($case_details['reffered_by_court_case_type'])) ? $case_details['reffered_by_court_case_type'] : '' ?>
												</p>
											</div>
										<?php endif; ?>

										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Reference No.:</label>
											<p class="mb-0">
												<?= (isset($case_details['arbitration_petition'])) ? $case_details['arbitration_petition'] : '' ?>
											</p>
										</div>

										<?php if (isset($case_details['reffered_by']) && $case_details['reffered_by'] == 'COURT') : ?>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label class="control-label">Year:</label>
												<p class="mb-0">
													<?= (isset($case_details['reffered_by_court_case_year'])) ? $case_details['reffered_by_court_case_year'] : '' ?>
												</p>
											</div>
										<?php endif; ?>

										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Ref. Received On:</label>
											<p class="mb-0">
												<?= (isset($case_details['recieved_on'])) ? formatReadableDate($case_details['recieved_on']) : '' ?>
											</p>

										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12">
											<label class="control-label">Date of registration:</label>
											<p class="mb-0">
												<?= (isset($case_details['registered_on'])) ? formatReadableDate($case_details['registered_on']) : '' ?>
											</p>

										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
											<label for="" class="control-label">Nature of arbitration:</label>
											<p class="mb-0">
												<?= (isset($case_details['type_of_arbitration'])) ? $case_details['type_of_arbitration_desc'] : '' ?>
											</p>
										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 di-type-of-arb-col required">
											<label for="" class="control-label">Type of Arbitration:</label>
											<p class="mb-0">
												<?= (isset($case_details['di_type_of_arbitration'])) ? $case_details['di_type_of_arbitration_desc'] : '' ?>
											</p>

										</div>

										<div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
											<label for="" class="control-label">Arbitrator Status:</label>
											<p class="mb-0">
												<?= (isset($case_details['arbitrator_status'])) ? $case_details['arbitrator_status_desc'] : '' ?>
											</p>
										</div>

										<?php if (isset($case_details['arbitrator_status']) && $case_details['arbitrator_status'] == 1) : ?>
											<div class="form-group col-md-3 col-sm-6 col-xs-12 arbitrator-status-col required">
												<label for="" class="control-label">Arbitral Tribunal Strength:</label>
												<p class="mb-0">
													<?php if (isset($case_details['arbitral_tribunal_strength'])) : ?>
														<?php if ($case_details['arbitral_tribunal_strength'] == 1) : ?>
															Sole
														<?php else : ?>
															<?= $case_details['arbitral_tribunal_strength'] ?>
														<?php endif; ?>
													<?php else : ?>
													<?php endif; ?>
												</p>
											</div>
										<?php endif; ?>

										<div class="form-group col-xs-12">
											<label class="control-label">Uploaded Supporting Documents:</label>
											<?php if (isset($case_supporting_docs) && count($case_supporting_docs) > 0) : ?>
												<div class="">
													<?php foreach ($case_supporting_docs as $doc) : ?>
														<a href="<?= base_url(VIEW_CASE_FILE_UPLOADS_FOLDER . $doc['file_name']) ?>" class="btn btn-primary btn-sm" target="_BLANK">
															<i class="fa fa-eye"></i> View</a>
													<?php endforeach; ?>
												</div>
											<?php else : ?>
												<p class="mb-0">
													No Document
												</p>
											<?php endif; ?>
										</div>

										<div class="form-group col-md-6 col-sm-6 col-xs-12 ">
											<label class="control-label">Remarks:</label>
											<p class="mb-0">
												<?= (isset($case_details['remarks'])) ? $case_details['remarks'] : '' ?>
											</p>
										</div>
									</div>

								</div>

								<!-- ================================================== -->
								<!-- Claimant and respondent -->
								<h3 class="accordion-header"><i class="fa fa-users"></i> Claimants & Respondents
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">


									<fieldset class="fieldset">
										<legend>Claimants</legend>
										<div class="fieldset-content-box">
											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="cl_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th width="25%">Name</th>
															<th>Email Id</th>
															<th>Phone No.</th>
															<th>Removed or not</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['claimant_data'] as $cl_data) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= $cl_data['name'] . ' (' . $cl_data['count_number'] . ')' ?></td>
																<td><?= $cl_data['email'] ?></td>
																<td><?= $cl_data['contact'] ?></td>
																</td>
																<td><?= $cl_data['removed_desc'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</fieldset>

									<fieldset class="fieldset">
										<legend>Respondents</legend>
										<div class="fieldset-content-box">
											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="res_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th width="25%">Name</th>
															<th>Email Id</th>
															<th>Phone No.</th>
															<th>Removed or not</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['respondant_data'] as $res_data) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= $res_data['name'] . ' (' . $res_data['count_number'] . ')' ?></td>
																<td><?= $res_data['email'] ?></td>
																<td><?= $res_data['contact'] ?></td>
																<td><?= $res_data['removed_desc'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</fieldset>
								</div>

								<!-- ================================================== -->
								<!-- Status Of Pleadings -->
								<h3 class="accordion-header"><i class="fa fa-pencil"></i> Status of Pleadings
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="row">
										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Claim Invited On: </b>
											<?= (isset($case_data['sop_data']['claim_invited_on'])) ? $case_data['sop_data']['claim_invited_on'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Reminder to claim: </b>
											<?= (isset($case_data['sop_data']['rem_to_claim_on'])) ? $case_data['sop_data']['rem_to_claim_on'] : '' ?>
										</div>

										<?php if (isset($case_data['sop_data']['rem_to_claim_on_2']) && !empty($case_data['sop_data']['rem_to_claim_on_2'])) : ?>
											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Reminder to claim (2): </b>
												<?= (isset($case_data['sop_data']['rem_to_claim_on_2'])) ? $case_data['sop_data']['rem_to_claim_on_2'] : '' ?>
											</div>
										<?php endif; ?>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Claim filed On: </b>
											<?= (isset($case_data['sop_data']['claim_filed_on'])) ? $case_data['sop_data']['claim_filed_on'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Respondent served on: </b>
											<?= (isset($case_data['sop_data']['res_served_on'])) ? $case_data['sop_data']['res_served_on'] : '' ?>
										</div>


										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Statement of defence filed on: </b>
											<?= (isset($case_data['sop_data']['sod_filed_on'])) ? $case_data['sop_data']['sod_filed_on'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Whether filed beyond period of limitation: </b>
											<?= (isset($case_data['sop_data']['sod_pol'])) ? $case_data['sop_data']['sod_pol'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Number of days of delay: </b>
											<?= (isset($case_data['sop_data']['sod_dod'])) ? $case_data['sop_data']['sod_dod'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Rejoinder to Statement of Defence filed on: </b>
											<?= (isset($case_data['sop_data']['rej_stat_def_filed_on'])) ? $case_data['sop_data']['rej_stat_def_filed_on'] : '' ?>
										</div>

										<!-- Counter Claim -->
										<div class="col-md-3 form-group">
											<b>Counter Claim: </b>
											<?= (isset($case_data['sop_data']['counter_claim'])) ? $case_data['sop_data']['counter_claim'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Date of filing of Counter Claim: </b>
											<?= (isset($case_data['sop_data']['dof_counter_claim'])) ? $case_data['sop_data']['dof_counter_claim'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Reply to Counter Claim filed on: </b>
											<?= (isset($case_data['sop_data']['reply_counter_claim_on'])) ? $case_data['sop_data']['reply_counter_claim_on'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Whether filed beyond period of limitation: </b>
											<?= (isset($case_data['sop_data']['reply_counter_claim_pol'])) ? $case_data['sop_data']['reply_counter_claim_pol'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Number of days of delay: </b>
											<?= (isset($case_data['sop_data']['reply_counter_claim_dod'])) ? $case_data['sop_data']['reply_counter_claim_dod'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Rejoinder to Reply of Counter Claim filed on: </b>
											<?= (isset($case_data['sop_data']['rej_reply_counter_claim_on'])) ? $case_data['sop_data']['rej_reply_counter_claim_on'] : '' ?>
										</div>


										<!-- Application act 17 -->
										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Application under Section 17 of A&C Act: </b>
											<?= (isset($case_data['sop_data']['app_section'])) ? $case_data['sop_data']['app_section'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Date of filing of Application (17): </b>
											<?= (isset($case_data['sop_data']['dof_app'])) ? $case_data['sop_data']['dof_app'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Reply to the Application filed on (17): </b>
											<?= (isset($case_data['sop_data']['reply_app_on'])) ? $case_data['sop_data']['reply_app_on'] : '' ?>
										</div>

										<!-- Application act 16 -->
										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Application under Section 16 of A&C Act: </b>
											<?= (isset($case_data['sop_data']['app_section'])) ? $case_data['sop_data']['app_section'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Date of filing of Application (16): </b>
											<?= (isset($case_data['sop_data']['dof_app'])) ? $case_data['sop_data']['dof_app'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Reply to the Application filed on (16): </b>
											<?= (isset($case_data['sop_data']['reply_app_on'])) ? $case_data['sop_data']['reply_app_on'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Remarks: </b>
											<?= (isset($case_data['sop_data']['remarks'])) ? $case_data['sop_data']['remarks'] : '' ?>
										</div>

									</div>

									<!-- Other Pleadings -->
									<fieldset class="fieldset">
										<legend>Miscellaneous</legend>
										<div class="fieldset-content-box">

											<table class="table table-striped table-bordered common-data-table" id="op_data_table">
												<thead>
													<tr>
														<th>S. No.</th>
														<th width="65%">Details</th>
														<th>Date of filing</th>
														<th>Filed By</th>
													</tr>
												</thead>

												<tbody>
													<?php $count = 1; ?>
													<?php foreach ($case_data['sop_op_data'] as $op_data) : ?>
														<tr>
															<td><?= $count++ ?></td>
															<td><?= $op_data['details'] ?></td>
															<td><?= $op_data['date_of_filing'] ?></td>
															<td><?= $op_data['filed_by'] ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>

										</div>
									</fieldset>

								</div>

								<!-- =============================================== -->
								<!-- Counsels -->
								<h3 class="accordion-header"><i class="fa fa-retweet"></i> Counsels

									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered common-data-table" id="res_data_table">
											<thead>
												<tr>
													<th>S. No.</th>
													<th width="15%">Name</th>
													<th>Enrollment No.</th>
													<th>Appearing for</th>
													<th>Email Id</th>
													<th>Phone No.</th>
													<th>Discharged</th>
													<th>Date of discharge</th>
												</tr>
											</thead>

											<tbody>
												<?php $count = 1; ?>
												<?php foreach ($case_data['counsels'] as $counsel) : ?>
													<tr>
														<td><?= $count++ ?></td>
														<td><?= $counsel['name'] ?></td>
														<td><?= $counsel['enrollment_no'] ?></td>
														<td><?= (isset($counsel['appearing_for']) && !empty($counsel['appearing_for'])) ? $counsel['cl_res_name'] . '-' . $counsel['cl_res_count_number'] : '' ?></td>
														<td>
															<?= $counsel['email'] ?>
														</td>
														<td><?= $counsel['phone_number'] ?></td>
														<td><?= $counsel['discharged'] ?></td>
														<td><?= formatReadableDate($counsel['date_of_discharge']) ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>

								</div>

								<!-- =============================================== -->
								<!-- Arbitral Tribunals -->
								<h3 class="accordion-header"><i class="fa fa-sticky-note-o"></i> Arbitral Tribunals
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered common-data-table" id="res_data_table">
											<thead>
												<tr>
													<th>S. No.</th>
													<th width="12%">Name</th>
													<th>Email</th>
													<th>Phone Number</th>
													<th>Arbitrator Type</th>
													<th>Wheather On Panel</th>
													<th>Category</th>
													<th>Appointed By</th>
													<th>Date Of Appointment</th>
													<th>Date Of Declaration</th>
													<th>Terminated</th>
													<th>Date Of Termination</th>
													<th>Reason Of Termination</th>
												</tr>
											</thead>

											<tbody>

												<?php $count = 1; ?>
												<?php foreach ($case_data['arbitral_tribunal'] as $at) : ?>
													<tr>
														<td><?= $count++ ?></td>
														<td><?= $at['amt_name_of_arbitrator'] ?></td>
														<td><?= $at['amt_email'] ?></td>
														<td><?= $at['amt_contact_no'] ?></td>
														<td><?= $at['arbitrator_type_desc'] ?></td>
														<td><?= $at['whether_on_panel'] == 1 ? "Yes" : "No" ?></td>
														<td><?= $at['amt_category_name'] ?></td>
														<td>
															<?= $at['appointed_by_desc'] ?>
														</td>
														<td><?= formatReadableDate($at['date_of_appointment']) ?></td>
														<td><?= formatReadableDate($at['date_of_declaration']) ?></td>
														<td><?= $at['arb_terminated_desc'] ?></td>
														<td><?= formatReadableDate($at['date_of_termination']) ?></td>
														<td><?= $at['reason_of_termination'] ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>

								<!-- =============================================== -->
								<!-- Notings -->
								<h3 class="accordion-header"><i class="fa fa-pencil-square-o"></i> Notings
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered common-data-table" id="res_data_table">
											<thead>
												<tr>
													<th>S. No.</th>
													<th width="40%">Noting</th>
													<th>Noting Date</th>
													<th>Marked By</th>
													<th>Marked To</th>
												</tr>
											</thead>

											<tbody>
												<?php $count = 1; ?>
												<?php foreach ($case_data['notings'] as $nt) : ?>
													<tr>
														<td><?= $count++ ?></td>
														<td>
															<?= $nt['noting'] ?>
															<?= $nt['noting_text'] ?>
														</td>
														<td><?= formatReadableDateTime($nt['noting_date']) ?></td>
														<td>
															<?= $nt['marked_by_user'] ?>
														</td>
														<td><?= $nt['marked_to_user'] ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>

								<!-- =============================================== -->
								<!-- Fee details -->
								<h3 class="accordion-header"><i class="fa fa-money"></i> Fee Details

									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<?php if (count($case_data['fee_cost_data']) > 0) : ?>
										<?php foreach ($case_data['fee_cost_data'] as $key => $fc) : ?>
											<fieldset class="fieldset mb-15 fee-fieldset">
												<legend>Assessment - <?= $key + 1 ?></legend>
												<div class="fieldset-content-box">

													<div class="row flex-row">
														<div class="form-group col-md-3 col-sm-6 col-xs-12">

															<label class="control-label">Arbitration Type:</label>
															<div>
																<?= (isset($case_data['case_data']['type_of_arbitration'])) ? ucfirst($case_data['case_data']['type_of_arbitration']) : ''; ?>
															</div>
														</div>
														<div class="form-group col-md-3 col-sm-6 col-xs-12">

															<label class="control-label">Arbitral Tribunal Strength:</label>
															<div>
																<?= (isset($fc['arbitral_tribunal_strength'])) ? ucfirst($fc['arbitral_tribunal_strength']) : ''; ?>
															</div>
														</div>

														<?php if ($case_data['case_data']['type_of_arbitration'] == 'INTERNATIONAL') : ?>
															<div class="form-group col-md-3 col-sm-6 col-xs-12">

																<label class="control-label">Dollar Price in Rupyee:</label>
																<div>
																	<?= (isset($fc['current_dollar_price'])) ? ucfirst($fc['current_dollar_price']) : ''; ?>
																</div>
															</div>
														<?php endif; ?>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">

															<label class="control-label">Whether Claims or Counter Claims assessed separately:</label>
															<div>
																<?= (isset($fc['c_cc_asses_sep'])) ? ucfirst($fc['c_cc_asses_sep']) : ''; ?>
															</div>
														</div>

														<?php if (isset($fc['c_cc_asses_sep']) && $fc['c_cc_asses_sep'] == 'yes') : ?>

															<div class="form-group col-md-3  col-sm-6 col-xs-12">
																<label class="control-label">Sum in Dispute (Claims):</label>
																<div>
																	<?= (isset($fc['sum_in_dispute_claim'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['sum_in_dispute_claim']) : ''  ?>
																</div>
															</div>

															<div class="form-group col-md-3 col-sm-6 col-xs-12">
																<label class="control-label">Sum in Dispute (Counter Claims):</label>
																<div>
																	<?= (isset($fc['sum_in_dispute_cc'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['sum_in_dispute_cc']) : ''  ?>
																</div>
															</div>

														<?php else : ?>
															<div class="form-group col-md-3 col-sm-6 col-xs-12">
																<label class="control-label">Sum in Dispute:</label>
																<div>
																	<?= (isset($fc['sum_in_dispute'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['sum_in_dispute']) : ''  ?>

																</div>
															</div>
														<?php endif; ?>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">As per provisional assessment dated:</label>
															<div>
																<?= (isset($fc['asses_date'])) ? formatReadableDate($fc['asses_date']) : ''  ?>
															</div>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Assessment Approved:</label>
															<div>
																<?= (isset($fc['assessment_approved'])) ? ucfirst($fc['assessment_approved']) : ''; ?>
															</div>
														</div>

														<div class="form-group col-md-3 col-sm-6 col-xs-12">
															<label class="control-label">Assessmemt Sheet:</label>
															<div>
																<?= (isset($fc['assessment_sheet_doc']) && !empty($fc['assessment_sheet_doc'])) ? '<a href="' . base_url(VIEW_FEE_FILE_UPLOADS_FOLDER . $fc['assessment_sheet_doc']) . '" class="btn btn-custom btn-sm" target="_BLANK"><i class="fa fa-eye"></i> View File</a>' : 'No attachment'  ?>
															</div>
														</div>
													</div>

													<fieldset class="fieldset">
														<legend class="legend">
															Calculated Fees
														</legend>

														<?php if (isset($case_data['case_data']['type_of_arbitration']) && $case_data['case_data']['type_of_arbitration'] == 'DOMESTIC') : ?>
															<div class="fieldset-content-box">
																<div class="row">
																	<div class="form-group col-md-3 col-sm-6 col-xs-12">
																		<label class="control-label">Total Arbitrators Fees:</label>
																		<div>
																			<?= (isset($fc['total_arb_fees'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['total_arb_fees']) : ''  ?>
																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<?php  // Claimant Share 
																		?>
																		<fieldset class="fieldset">
																			<legend class="inner-legend">Claimant Share</legend>
																			<div class="fieldset-content-box">
																				<div class="form-group col-md-6 col-sm-6 col-xs-12">
																					<label class="control-label">Arbitrators Fees:</label>
																					<div>
																						<?= (isset($fc['cs_arb_fees'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['cs_arb_fees']) : ''  ?>
																					</div>
																				</div>

																				<div class="form-group col-md-6 col-sm-6 col-xs-12">
																					<label class="control-label">Administrative Expenses:</label>
																					<div>
																						<?= (isset($fc['cs_adminis_fees'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['cs_adminis_fees']) : ''  ?>
																					</div>
																				</div>

																			</div>
																		</fieldset>
																	</div>
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<?php  // Respondent Share 
																		?>
																		<fieldset class="fieldset">
																			<legend class="inner-legend">Respondent Share</legend>
																			<div class="fieldset-content-box">
																				<div class="row">
																					<div class="form-group col-md-6 col-sm-6 col-xs-12">
																						<label class="control-label">Arbitrators Fees:</label>
																						<div>
																							<?= (isset($fc['rs_arb_fees'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['rs_arb_fees']) : ''  ?>
																						</div>
																					</div>

																					<div class="form-group col-md-6 col-sm-6 col-xs-12">
																						<label class="control-label">Administrative Expenses:</label>
																						<div>
																							<?= (isset($fc['rs_adminis_fee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['rs_adminis_fee']) : ''  ?>
																						</div>
																					</div>
																				</div>
																			</div>
																		</fieldset>
																	</div>

																</div>
															</div>
														<?php endif; ?>

														<?php if (isset($case_data['case_data']['type_of_arbitration']) && $case_data['case_data']['type_of_arbitration'] == 'INTERNATIONAL') : ?>
															<div class="fieldset-content-box">
																<div class="row">
																	<div class="form-group col-md-3 col-sm-6 col-xs-12">
																		<label class="control-label">Total Arbitrators Fees:</label>
																		<div>
																			<p>
																				<?= (isset($fc['int_arb_total_fees_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_total_fees_dollar']) : ''  ?>
																			</p>
																			<p>
																				<?= (isset($fc['int_arb_total_fees_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_total_fees_rupee']) : ''  ?>
																			</p>

																		</div>
																	</div>

																	<div class="form-group col-md-3 col-sm-6 col-xs-12">
																		<label class="control-label">Total Administrative Charges:</label>
																		<div>
																			<p>
																				<?= (isset($fc['int_total_adm_charges_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_total_adm_charges_dollar']) : ''  ?>
																			</p>
																			<p>
																				<?= (isset($fc['int_total_adm_charges_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_total_adm_charges_rupee']) : ''  ?>
																			</p>

																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<?php  // Claimant Share 
																		?>
																		<fieldset class="fieldset">
																			<legend class="inner-legend">Claimant Share</legend>
																			<div class="fieldset-content-box">
																				<div class="form-group col-md-6 col-sm-6 col-xs-12">
																					<label class="control-label">Arbitrators Fees:</label>
																					<div>
																						<p>
																							<?= (isset($fc['int_arb_claim_share_fees_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_claim_share_fees_dollar']) : ''  ?>
																						</p>
																						<p>
																							<?= (isset($fc['int_arb_claim_share_fees_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_claim_share_fees_rupee']) : ''  ?>
																						</p>
																					</div>
																				</div>

																				<div class="form-group col-md-6 col-sm-6 col-xs-12">
																					<label class="control-label">Administrative Expenses:</label>
																					<div>
																						<p>
																							<?= (isset($fc['int_claim_adm_charges_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_claim_adm_charges_dollar']) : ''  ?>
																						</p>
																						<p>
																							<?= (isset($fc['int_claim_adm_charges_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_claim_adm_charges_rupee']) : ''  ?>
																						</p>
																					</div>
																				</div>

																			</div>
																		</fieldset>
																	</div>
																	<div class="col-md-6 col-sm-12 col-xs-12">
																		<?php  // Respondent Share 
																		?>
																		<fieldset class="fieldset">
																			<legend class="inner-legend">Respondent Share</legend>
																			<div class="fieldset-content-box">
																				<div class="row">
																					<div class="form-group col-md-6 col-sm-6 col-xs-12">
																						<label class="control-label">Arbitrators Fees:</label>
																						<div>
																							<p>
																								<?= (isset($fc['int_arb_res_share_fees_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_res_share_fees_dollar']) : ''  ?>
																							</p>
																							<p>
																								<?= (isset($fc['int_arb_res_share_fees_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_arb_res_share_fees_rupee']) : ''  ?>
																							</p>
																						</div>
																					</div>

																					<div class="form-group col-md-6 col-sm-6 col-xs-12">
																						<label class="control-label">Administrative Expenses:</label>
																						<div>
																							<p>
																								<?= (isset($fc['int_res_adm_charges_dollar'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_res_adm_charges_dollar']) : ''  ?>
																							</p>
																							<p>
																								<?= (isset($fc['int_res_adm_charges_rupee'])) ? symbol_checker($case_data['case_data']['type_of_arbitration'], $fc['int_res_adm_charges_rupee']) : ''  ?>
																							</p>
																						</div>
																					</div>
																				</div>
																			</div>
																		</fieldset>
																	</div>

																</div>
															</div>
														<?php endif; ?>
													</fieldset>


												</div>
											</fieldset>
										<?php endforeach; ?>
									<?php else : ?>
										<div>
											<h5 class="text-danger"><i class="fa fa-close"></i> No assessment is added.</h5>
										</div>
									<?php endif; ?>

									<!-- Fee Deposit -->
									<fieldset class="fieldset">
										<legend>Fee Deposit</legend>
										<div class="fieldset-content-box">

											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="op_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th>Date of deposit</th>
															<th>Deposited by</th>
															<th>Name of depositor</th>
															<th>Deposited towards</th>
															<th>Mode of deposit</th>
															<th>Amount</th>
															<th>Details of deposit</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['fee_deposit'] as $fd) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= formatReadableDate($fd['date_of_deposit']) ?></td>
																<td><?= $fd['cl_res_name'] . ' (' . $fd['cl_res_count_number'] . ')' ?></td>
																<td><?= $fd['name_of_depositor'] ?></td>
																<td><?= $fd['dep_by_description'] ?></td>
																<td><?= $fd['mod_description'] ?></td>
																<td><?= $fd['amount'] ?></td>
																<td><?= $fd['details_of_deposit'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>

											<div class="box">
												<div class="box-body my-box-body">
													<div class="row">
														<div class="col-xs-12">
															<h5><b>Total Fee: </b>
																<?= (empty($case_data['amount_n_balance']['total_amount'])) ? 'N/A' : 'Rs. ' . $case_data['amount_n_balance']['total_amount'] ?>
															</h5>
														</div>

														<div class="col-xs-12">
															<h5>
																<b>Fee Deposited: </b>
																<?= (empty($case_data['amount_n_balance']['deposit_amount'])) ? 'N/A' : 'Rs. ' . $case_data['amount_n_balance']['deposit_amount'] ?>
															</h5>
														</div>

														<div class="col-xs-12">
															<h5>
																<b>Balanace: </b>
																<?= 'Rs. ' . $case_data['amount_n_balance']['balance'] ?>
															</h5>
														</div>
													</div>
												</div>
											</div>

										</div>
									</fieldset>

									<!-- Cost Deposit -->
									<fieldset class="fieldset">
										<legend>Cost Deposit</legend>
										<div class="fieldset-content-box">

											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="op_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th>Date of deposit</th>
															<th>Deposited by</th>
															<th>Name of depositor</th>
															<th>Cost imposed vide order dated</th>
															<th>Mode of deposit</th>
															<th>Amount</th>
															<th>Details of deposit</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['cost_deposit'] as $cd) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= formatReadableDate($cd['date_of_deposit']) ?></td>
																<td><?= $cd['cl_res_name'] . ' (' . $cd['cl_res_count_number'] . ')' ?></td>
																<td><?= $cd['name_of_depositor'] ?></td>
																<td><?= $cd['cost_imposed_dated'] ?></td>
																<td><?= $cd['mod_description'] ?></td>
																<td><?= $cd['amount'] ?></td>
																<td><?= $cd['details_of_deposit'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>

										</div>
									</fieldset>

									<!-- Fee Refund -->
									<fieldset class="fieldset">
										<legend>Fee Refund</legend>
										<div class="fieldset-content-box">

											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="op_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th>Date of refund</th>
															<th>Refunded to</th>
															<th>Name of party to whom refunded</th>
															<th>Refunded towards</th>
															<th>Mode of refund</th>
															<th>Amount</th>
															<th>Details of refund</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['fee_refund'] as $fr) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= formatReadableDate($fr['date_of_refund']) ?></td>
																<td><?= $fr['cl_res_name'] . ' (' . $fr['cl_res_count_number'] . ')' ?></td>
																<td><?= $fr['name_of_party'] ?></td>
																<td><?= $fr['refunded_towards_description'] ?></td>
																<td><?= $fr['mod_refund'] ?></td>
																<td><?= $fr['amount'] ?></td>
																<td><?= $fr['details_of_refund'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>

										</div>
									</fieldset>

									<!-- Fee Released -->
									<fieldset class="fieldset">
										<legend>Fee Released</legend>
										<div class="fieldset-content-box">

											<div class="table-responsive">
												<table class="table table-striped table-bordered common-data-table" id="op_data_table">
													<thead>
														<tr>
															<th>S. No.</th>
															<th>Date of fee released</th>
															<th>Released to</th>
															<th>Mode of payment</th>
															<th>Amount</th>
															<th>Details of payment</th>
														</tr>
													</thead>

													<tbody>
														<?php $count = 1; ?>
														<?php foreach ($case_data['fee_released'] as $frel) : ?>
															<tr>
																<td><?= $count++ ?></td>
																<td><?= formatReadableDate($frel['date_of_fee_released']) ?></td>
																<td><?= $frel['arbitrator'] ?></td>
																<td><?= $frel['mop_description'] ?></td>
																<td><?= $frel['amount'] ?></td>
																<td><?= $frel['details_of_fee_released'] ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>

										</div>
									</fieldset>
								</div>

								<!-- =============================================== -->
								<!-- Case Orders -->
								<h3 class="accordion-header"><i class="fa fa-pencil-square-o"></i> Case Orders
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="table-responsive">
										<table class="table table-striped table-bordered common-data-table" id="res_data_table">
											<thead>
												<tr>
													<th style="width:7%;">S. No.</th>
													<th>Order Given On:</th>
													<th>Order Document.</th>
													<th>Created At</th>
												</tr>
											</thead>

											<tbody>
												<?php $count = 1; ?>
												<?php foreach ($case_data['case_orders'] as $order) : ?>
													<tr>
														<td><?= $count++ ?></td>
														<td>
															<?= formatReadableDate($order['order_given_on']) ?>
														</td>
														<td>
															<a class="btn btn-primary btn-sm" href="<?= base_url('public/upload/case_orders/' . $order['document']) ?>" target="_BLANK"><span class="fa fa-file"></span> View Document</a>
														</td>
														<td>
															<?= formatReadableDateTime($order['created_at']) ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>


								<!-- =================================================== -->
								<!-- Award & Termination -->
								<h3 class="accordion-header"><i class="fa fa-trophy"></i> Termination
									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">

									<div class="row flex-row">

										<?php if (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award') : ?>
											<div class="col-md-12 col-xs-12 form-group">
												<b>Award Status: </b>Case Awarded
											</div>
										<?php elseif (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'termination') : ?>
											<div class="col-md-12 col-xs-12 form-group">
												<b>Other Status: </b>Case Terminated
											</div>
										<?php else : ?>
											<div class="col-md-12 col-xs-12 form-group">
												<b>Case Processing: </b>Case is under process
											</div>
										<?php endif; ?>

										<?php if (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award') : ?>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Date of Award: </b>
												<?= (isset($case_data['award_term_data']['date_of_award'])) ? formatReadableDate($case_data['award_term_data']['date_of_award']) : '' ?>
											</div>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Nature of Award: </b>
												<?= (isset($case_data['award_term_data']['nature_of_award'])) ? $case_data['award_term_data']['nature_of_award_desc'] : '' ?>
											</div>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Addendum Award, if any: </b>
												<?= (isset($case_data['award_term_data']['addendum_award'])) ? $case_data['award_term_data']['addendum_award'] : '' ?>
											</div>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Award served to Claimant on: </b>
												<?= (isset($case_data['award_term_data']['award_served_claimaint_on'])) ? formatReadableDate($case_data['award_term_data']['award_served_claimaint_on']) : '' ?>
											</div>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Award served to Respondent on: </b>
												<?= (isset($case_data['award_term_data']['award_served_respondent_on'])) ? formatReadableDate($case_data['award_term_data']['award_served_respondent_on']) : '' ?>
											</div>

											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label for="" class="">Award File: </label>
												<?php if (isset($case_data['award_term_data']['award_term_award_file']) && !empty($case_data['award_term_data']['award_term_award_file'])) : ?>
													<a href="<?= base_url(VIEW_AWARD_FILE_UPLOADS_FOLDER) . $case_data['award_term_data']['award_term_award_file'] ?>" class="btn btn-custom btn-xs" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
												<?php else : ?>
													<p>No file is available</p>
												<?php endif; ?>
											</div>

										<?php elseif (isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'other') : ?>
											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Date of Termination: </b>
												<?= (isset($case_data['award_term_data']['date_of_termination'])) ? formatReadableDate($case_data['award_term_data']['date_of_termination']) : '' ?>
											</div>

											<div class="col-md-3 col-sm-6 col-xs-12 form-group">
												<b>Reason for Termination: </b>
												<?= (isset($case_data['award_term_data']['reason_for_termination'])) ? $case_data['award_term_data']['termination_reason_desc'] : '' ?>
											</div>

										<?php endif; ?>


										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<b>Factsheet prepared: </b>
											<?= (isset($case_data['award_term_data']['factsheet_prepared_desc'])) ? $case_data['award_term_data']['factsheet_prepared_desc'] : '' ?>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-12 form-group">
											<label class="control-label">Date of factsheet prepared:</label>
											<?php echo (isset($case_data['award_term_data']['date_of_factsheet']) && !empty($case_data['award_term_data']['date_of_factsheet'])) ? formatReadableDate($case_data['award_term_data']['date_of_factsheet']) : ''; ?>
										</div>

										<?php if (isset($case_data['award_term_data']['factsheet_file']) && !empty($case_data['award_term_data']['factsheet_file'])) : ?>
											<div class="form-group col-md-3 col-sm-6 col-xs-12">
												<label for="" class="">Factsheet File: </label>
												<a href="<?= base_url(VIEW_FACTSHEET_FILE_UPLOADS_FOLDER) . $case_data['award_term_data']['factsheet_file'] ?>" class="btn btn-custom btn-xs" target="_BLANK"><i class="fa fa-eye"></i> View File</a>
											</div>
										<?php endif; ?>

										<div class="col-xs-12">
											<label class="control-label">Note:</label>
											<?= (isset($case_data['award_term_data']['note'])) ? $case_data['award_term_data']['note'] : '' ?>
										</div>
									</div>

								</div>
								<!-- Hearings -->
								<h3 class="accordion-header"><i class="fa fa-map-o"></i> Hearings

									<span class="fa fa-plus pull-right"></span>
								</h3>
								<div class="accordion-content">
									<div class="table-responsive">
										<table class="table table-striped table-bordered common-data-table" id="res_data_table">
											<thead>
												<tr>
													<th>S. No.</th>
													<th width="15%">Case No</th>
													<th>Title of Case</th>
													<th>Arbitrator Name</th>
													<th>Purpose</th>
													<th>Date</th>
													<th>Time</th>
													<th>Mode of hearing</th>
													<th>Room No.</th>
													<th>Meeting Link</th>
													<th>Status</th>
												</tr>
											</thead>

											<tbody>
												<?php $count = 1; ?>
												<?php foreach ($case_data['hearings'] as $hearings) : ?>
													<tr>
														<td><?= $count++ ?></td>
														<td><?= $hearings['case_no'] ?></td>
														<td><?= $hearings['title_of_case'] ?></td>
														<td><?= $hearings['amt_name_of_arbitrator'] ?>
														</td>
														<td>
															<?= $hearings['purpose_category_name'] ?>
														</td>
														<td>
															<?= $hearings['date'] ?>
														</td>
														<td><?= $hearings['time_to'] . '-' . $hearings['time_from'] ?></td>
														<td><?= $hearings['mode_of_hearing'] ?></td>
														<td><?= $hearings['room_name'] . ' - ' . $hearings['room_no'] ?></td>
														<td><?= (isset($hearings['hearing_meeting_link']) && !empty($hearings['hearing_meeting_link'])) ? $hearings['hearing_meeting_link'] : '' ?></td>
														<td>
															<?php if ($hearings['active_status'] == 1) : ?>
																<span class="badge badge-success"><?= $hearings['active_status_desc'] ?></span>
															<?php else : ?>
																<span class="badge badge-danger"><?= $hearings['active_status_desc'] ?>
																<?php endif ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
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


<script type="text/javascript">
	$(function() {
		$("#accordion").accordion();
	});

	// Common Datatable for all tables
	var cm_dataTable = $('.common-data-table').dataTable({
		sorting: false,
		ordering: false,
		responsive: false,
		searching: false,
		lengthChange: false
	});
</script>