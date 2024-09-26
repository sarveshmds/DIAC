<h2 class="case-details-heading text-center mb-10">
	<u>
		<b><?= $case_data['case_data']['case_no'] ?></b>
	</u>
</h2>
<h3 class="case-details-heading text-center mb-10">
	<u>
		<b><?= $case_data['case_data']['case_title'] ?></b>
	</u>
</h3>

<div class="case-details-wrappers mt-10">
	<div class="cd-col">
		<b>Date of reference order: </b>
		<?= formatReadableDate($case_data['case_data']['reffered_on']) ?>
	</div>

	<div class="cd-col">
		<b>Mode of reference: </b>
		<?= $case_data['case_data']['reffered_by_desc'] ?>
	</div>
	<div class="cd-col">
		<b>Name of Judge: </b>
		<?= $case_data['case_data']['reffered_by_judge'] ?>
	</div>

	<div class="cd-col">
		<b>Reference No. : </b>
		<?= $case_data['case_data']['arbitration_petition'] ?>
	</div>

	<div class="cd-col">
		<b>Name of Court/Department: </b>
		<?= $case_data['case_data']['name_of_court'] ?>
	</div>

	<div class="cd-col">
		<b>Name of arbitrator: </b>
		<?= $case_data['case_data']['name_of_judge'] ?>
	</div>

	<div class="cd-col">
		<b>Received On: </b>
		<?= formatReadableDate($case_data['case_data']['recieved_on']) ?>
	</div>

	<div class="cd-col">
		<b>Ref. Registered On: </b>
		<?= formatReadableDate($case_data['case_data']['registered_on']) ?>
	</div>

	<div class="cd-col">
		<b>Type of arbitration: </b>
		<?= $case_data['case_data']['type_of_arbitration_desc'] ?>
	</div>

	<div class="cd-col">
		<b>Case Status: </b>
		<?= $case_data['case_data']['case_status_dec'] ?>
	</div>

	<div class="cd-col">
		<b>Remarks: </b>
		<?= $case_data['case_data']['remarks'] ?>
	</div>

	<!-- Claimants -->
	<h4 class="mb-10 mt-10">
		<b><u>Claimants:</u></b>
	</h4>
	<div class="mb-10">
		<table class="table table-bordered common-case-details-table" cellspacing="0">

			<tr>
				<th width="10%">S. No.</th>
				<th width="15%">Name</th>
				<th width="20%">Email Id</th>
				<th width="15%">Phone No.</th>
				<th width="15%">Removed or not</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['claimant_data'] as $cl_data): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $cl_data['name'].' ('.$cl_data['count_number'].')' ?></td>
				<td><?= $cl_data['email'] ?></td>
				<td><?= $cl_data['contact'] ?></td>
				<td><?= $cl_data['removed_desc'] ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
	</div>


	<!-- Respondant -->
	<h4 class="mb-10">
		<b><u>Respondents</u></b>
	</h4>
	<div class="mb-10">
		<table class="table table-bordered common-case-details-table" cellspacing="0">

			<tr>
				<th width="10%">S. No.</th>
				<th width="15%">Name</th>
				<th width="20%">Email Id</th>
				<th width="15%">Phone No.</th>
				<th width="15%">Removed or not</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['respondant_data'] as $res_data): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $res_data['name'].' ('.$res_data['count_number'].')' ?></td>
				<td><?= $res_data['email'] ?></td>
				<td><?= $res_data['contact'] ?></td>
				<td><?= $res_data['removed_desc'] ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
	</div>

	<div class="cd-col">
		<b>Claim Invited On: </b>
		<?= (isset($case_data['sop_data']['claim_invited_on']))? $case_data['sop_data']['claim_invited_on']:'' ?>
	</div>

	<div class="cd-col">
		<b>Reminder to claim: </b>
		<?= (isset($case_data['sop_data']['rem_to_claim_on']))? $case_data['sop_data']['rem_to_claim_on']:'' ?>
	</div>

	<?php if(isset($case_data['sop_data']['rem_to_claim_on_2']) && !empty($case_data['sop_data']['rem_to_claim_on_2'])): ?>
	<div class="col-md-3 col-sm-6 col-xs-12 form-group">
		<b>Reminder to claim (2): </b>
		<?= $case_data['sop_data']['rem_to_claim_on_2'] ?>
	</div>
	<?php endif; ?>

	<div class="cd-col">
		<b>Claim filed On: </b>
		<?= (isset($case_data['sop_data']['claim_filed_on']))? $case_data['sop_data']['claim_filed_on']:'' ?>
	</div>

	<div class="cd-col">
		<b>Respondent served on: </b>
		<?= (isset($case_data['sop_data']['res_served_on']))? $case_data['sop_data']['res_served_on']:'' ?>
	</div>


	<div class="cd-col">
		<b>Statement of defence filed on: </b>
		<?= (isset($case_data['sop_data']['sod_filed_on']))? $case_data['sop_data']['sod_filed_on']:'' ?>
	</div>

	<div class="cd-col">
		<b>Whether filed beyond period of limitation: </b>
		<?= (isset($case_data['sop_data']['sod_pol']))? $case_data['sop_data']['sod_pol']:'' ?>
	</div>

	<div class="cd-col">
		<b>Number of days of delay: </b>
		<?= (isset($case_data['sop_data']['sod_dod']))? $case_data['sop_data']['sod_dod']:'' ?>
	</div>

	<div class="cd-col">
		<b>Rejoinder to Statement of Defence filed on: </b>
		<?= (isset($case_data['sop_data']['rej_stat_def_filed_on']))? $case_data['sop_data']['rej_stat_def_filed_on']:'' ?>
	</div>

	<!-- Counter Claim -->
	<div class="cd-col">
		<b>Counter Claim: </b>
		<?= (isset($case_data['sop_data']['counter_claim']))? $case_data['sop_data']['counter_claim']:'' ?>
	</div>

	<div class="cd-col">
		<b>Date of filing of Counter Claim: </b>
		<?= (isset($case_data['sop_data']['dof_counter_claim']))? $case_data['sop_data']['dof_counter_claim']:'' ?>
	</div>

	<div class="cd-col">
		<b>Reply to Counter Claim filed on: </b>
		<?= (isset($case_data['sop_data']['reply_counter_claim_on']))? $case_data['sop_data']['reply_counter_claim_on']:'' ?>
	</div>

	<div class="cd-col">
		<b>Whether filed beyond period of limitation: </b>
		<?= (isset($case_data['sop_data']['reply_counter_claim_pol']))? $case_data['sop_data']['reply_counter_claim_pol']:'' ?>
	</div>

	<div class="cd-col">
		<b>Number of days of delay: </b>
		<?= (isset($case_data['sop_data']['reply_counter_claim_dod']))? $case_data['sop_data']['reply_counter_claim_dod']:'' ?>
	</div>

	<div class="cd-col">
		<b>Rejoinder to Reply of Counter Claim filed on: </b>
		<?= (isset($case_data['sop_data']['rej_reply_counter_claim_on']))? $case_data['sop_data']['rej_reply_counter_claim_on']:'' ?>
	</div>


	<!-- Application act 17 -->
	<div class="cd-col">
		<b>Application under Section 17 of A&C Act: </b>
		<?= (isset($case_data['sop_data']['app_section']))? $case_data['sop_data']['app_section']:'' ?>
	</div>

	<div class="cd-col">
		<b>Date of filing of Application (17): </b>
		<?= (isset($case_data['sop_data']['dof_app']))? $case_data['sop_data']['dof_app']:'' ?>
	</div>

	<div class="cd-col">
		<b>Reply to the Application filed on (17): </b>
		<?= (isset($case_data['sop_data']['reply_app_on']))? $case_data['sop_data']['reply_app_on']:'' ?>
	</div>

	<!-- Application act 16 -->
	<div class="cd-col">
		<b>Application under Section 16 of A&C Act: </b>
		<?= (isset($case_data['sop_data']['app_section_16']))? $case_data['sop_data']['app_section_16']:'' ?>
	</div>

	<div class="cd-col">
		<b>Date of filing of Application (16): </b>
		<?= (isset($case_data['sop_data']['dof_app_16']))? $case_data['sop_data']['dof_app_16']:'' ?>
	</div>

	<div class="cd-col">
		<b>Reply to the Application filed on (16): </b>
		<?= (isset($case_data['sop_data']['reply_app_on_16']))? $case_data['sop_data']['reply_app_on_16']:'' ?>
	</div>

	<div class="cd-col">
		<b>Remarks: </b>
		<?= (isset($case_data['sop_data']['remarks']))? $case_data['sop_data']['remarks']:'' ?>
	</div>

	<!-- Other Pleadings -->
	<h4 class="mb-10 mt-10">
		<b><u>Other Pleadings:</u></b>
	</h4>
	<div class="mb-10">
		<table class="table  table-bordered common-case-details-table" cellspacing="0">
			<tr>
				<th width="10%">S. No.</th>
				<th width="60%">Details</th>
				<th width="15%">Date of filing</th>
				<th width="15%">Filed By</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['sop_op_data'] as $op_data): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $op_data['details'] ?></td>
				<td><?= $op_data['date_of_filing'] ?></td>
				<td><?= $op_data['filed_by'] ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<div class="counsels">
		<h4 class="mb-10">
			<b><u>Counsels:</u></b>
		</h4>
		<table class="table  table-bordered common-case-details-table mb-10" cellspacing="0">

			<tr>
				<th width="10%">S. No.</th>
				<th width="15%">Name</th>
				<th width="15%">Enrollment No.</th>
				<th width="10%">Appearing for</th>
				<th width="10%">Email Id</th>
				<th width="10%">Phone No.</th>
				<th width="10%">Address</th>
				<th width="10%">Discharged</th>
				<th width="10%">Discharge Date</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['counsels'] as $counsel): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $counsel['name'] ?></td>
				<td><?= $counsel['enrollment_no'] ?></td>
				<td><?= $counsel['cl_res_name'].'-'.$counsel['cl_res_count_number'] ?></td>
				<td>
					<?= $counsel['email'] ?>
				</td>
				<td><?= $counsel['phone'] ?></td>
				<td><?= $counsel['address'] ?></td>
				<td><?= $counsel['discharged'] ?></td>
				<td><?= $counsel['date_of_discharge'] ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
	</div>

	<div class="arbitral_tribunal">
		<h4 class="mb-10">
			<b><u>Arbitral Tribunal:</u></b>
		</h4>
		<table class="table  table-bordered common-case-details-table mb-10" cellspacing="0">

			<tr>
				<th width="8%">S. No.</th>
				<th width="12%">Name</th>
				<th width="10%">Weather On Panel</th>
				<th width="10%">Category</th>
				<th width="10%">Appointed By</th>
				<th width="10%">Date Of Appointment</th>
				<th width="10%">Date Of Declaration</th>
				<th width="10%">Terminated</th>
				<th width="10%">Date Of Termination</th>
				<th width="10%">Reason Of Termination</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['arbitral_tribunal'] as $at): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $at['name_of_arbitrator'] ?></td>
				<td><?= $at['whether_on_panel_desc'] ?></td>
				<td><?= $at['category_name'] ?></td>
				<td>
					<?= $at['appointed_by_desc'] ?>
				</td>
				<td><?= $at['date_of_appointment'] ?></td>
				<td><?= $at['date_of_declaration'] ?></td>
				<td><?= $at['arb_terminated_desc'] ?></td>
				<td><?= $at['date_of_termination'] ?></td>
				<td><?= $at['reason_of_termination'] ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
	</div>


	<div class="notings">
		<h4 class="mb-10">
			<b><u>Notings:</u></b>
		</h4>

		<table class="table  table-bordered common-case-details-table mb-10" cellspacing="0">

			<tr>
				<th width="8%">S. No.</th>
				<th width="47%">Noting</th>
				<th width="15%">Noting Date</th>
				<th width="15%">Marked By</th>
				<th width="15%">Marked To/File Sent</th>
			</tr>
			<?php $count = 1; ?>
			<?php foreach($case_data['notings'] as $nt): ?>
			<tr>
				<td><?= $count++ ?></td>
				<td><?= $nt['noting'] ?></td>
				<td><?= $nt['noting_date'] ?></td>
				<td>
					<?= $nt['marked_by_user'] ?>
				</td>
				<td><?= $nt['marked_to_user'] ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
	</div>


	<div class="fee-cost-details">
		<h4 class="mb-10">
			<b><u>Fee Details:</u></b>
		</h4>
		<div class="fee-details-content">
			<div class="cd-col">
				<b>Whether Claims or Counter Claims assessed separately: </b>
				<?= (isset($case_data['fee_cost_data']['c_cc_asses_sep_desc']))? $case_data['fee_cost_data']['c_cc_asses_sep_desc']:'' ?>
			</div>

			<?php if(isset($case_data['fee_cost_data']['c_cc_asses_sep']) && $case_data['fee_cost_data']['c_cc_asses_sep'] == 'yes'): ?>
			<div class="cd-col">
				<b>Sum in Dispute (Claims) (Rs.): </b>
				<?= $case_data['fee_cost_data']['sum_in_dispute_claim'] ?>
			</div>

			<div class="cd-col">
				<b>Sum in Dispute (Counter Claims) (Rs.): </b>
				<?= $case_data['fee_cost_data']['sum_in_dispute_cc'] ?>
			</div>
			<?php elseif(isset($case_data['fee_cost_data']['c_cc_asses_sep']) && $case_data['fee_cost_data']['c_cc_asses_sep'] == 'no'): ?>
			<div class="cd-col">
				<b>Sum in Dispute (Rs.): </b>
				<?= $case_data['fee_cost_data']['sum_in_dispute'] ?>
			</div>
			<?php endif; ?>

			<div class="cd-col">
				<b>As per provisional assessment dated: </b>
				<?= (isset($case_data['fee_cost_data']['asses_date']))? $case_data['fee_cost_data']['asses_date']:'' ?>
			</div>


			<div class="cd-col">
				<b>Total Arbitrators Fees (Rs.): </b>
				<?= (isset($case_data['fee_cost_data']['total_arb_fees']))? $case_data['fee_cost_data']['total_arb_fees']:'' ?>
			</div>
		</div>

		<div class="claimant-respondant-share-col mt-10">
			<div class="claimant-share-col mb-10">
				<h4 class="mb-10"><b><u>Claimant's Share:</u></b></h4>
				<div class="form-group">
					<b>Arbitrators Fees (Rs.): </b>
					<?= (isset($case_data['fee_cost_data']['cs_arb_fees']))? $case_data['fee_cost_data']['cs_arb_fees']:'' ?>
				</div>

				<div class="form-group">
					<b>Administrative Expenses (Rs.): </b>
					<?= (isset($case_data['fee_cost_data']['cs_adminis_fees']))? $case_data['fee_cost_data']['cs_adminis_fees']:'' ?>
				</div>
			</div>

			<div class="respondant-share-col">

				<h4 class="mb-10"><b><u>Respondent's Share:</u></b></h4>
				<div class="form-group">
					<b>Arbitrators Fees (Rs.): </b>
					<?= (isset($case_data['fee_cost_data']['rs_arb_fees']))? $case_data['fee_cost_data']['rs_arb_fees']:'' ?>
				</div>

				<div class="form-group">
					<b>Administrative Expenses (Rs.): </b>
					<?= (isset($case_data['fee_cost_data']['rs_adminis_fee']))? $case_data['fee_cost_data']['rs_adminis_fee']:'' ?>
				</div>

			</div>

		</div>

		<!-- Fee Deposit -->
		<h4 class="mb-10 mt-10"><b><u>Fee Deposit:</u></b></h4>
		<div class="">
			<table class="table  table-bordered common-case-details-table mb-10" cellspacing="0">
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
				<?php $count = 1; ?>
				<?php foreach($case_data['fee_deposit'] as $fd): ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= $fd['date_of_deposit'] ?></td>
					<td><?= $fd['cl_res_name'].' ('.$fd['cl_res_count_number'].')' ?></td>
					<td><?= $fd['name_of_depositor'] ?></td>
					<td><?= $fd['dep_by_description'] ?></td>
					<td><?= $fd['mod_description'] ?></td>
					<td><?= $fd['amount'] ?></td>
					<td><?= $fd['details_of_deposit'] ?></td>
				</tr>
				<?php endforeach; ?>

			</table>

			<div class="mb-10">
				<div>
					<b>Total Fee: </b>
					<?= (empty($case_data['amount_n_balance']['total_amount']))?'N/A': 'Rs. '.$case_data['amount_n_balance']['total_amount'] ?>
				</div>

				<div>
					<b>Fee Deposited: </b>
					<?= (empty($case_data['amount_n_balance']['deposit_amount']))?'N/A': 'Rs. '.$case_data['amount_n_balance']['deposit_amount'] ?>
				</div>

				<div>
					<b>Balance: </b>
					<?= 'Rs. '.$case_data['amount_n_balance']['balance'] ?>
				</div>
			</div>
		</div>

		<!-- Cost Deposit -->
		<h4 class="mb-10"><b><u>Cost Deposit:</u></b></h4>
		<div class="mb-10">
			<table class="table  table-bordered common-case-details-table" cellspacing="0">
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

				<?php $count = 1; ?>
				<?php foreach($case_data['cost_deposit'] as $cd): ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= $cd['date_of_deposit'] ?></td>
					<td><?= $cd['cl_res_name'].' ('.$cd['cl_res_count_number'].')' ?></td>
					<td><?= $cd['name_of_depositor'] ?></td>
					<td><?= $cd['cost_imposed_dated'] ?></td>
					<td><?= $cd['mod_description'] ?></td>
					<td><?= $cd['amount'] ?></td>
					<td><?= $cd['details_of_deposit'] ?></td>
				</tr>
				<?php endforeach; ?>
			</table>

		</div>

		<!-- Fee Refund -->
		<h4 class="mb-10"><b><u>Fee Refund:</u></b></h4>
		<div class="mb-10">
			<table class="table  table-bordered common-case-details-table" cellspacing="0"> 
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
				<?php $count = 1; ?>
				<?php foreach($case_data['fee_refund'] as $fr): ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= $fr['date_of_refund'] ?></td>
					<td><?= $fr['cl_res_name'].' ('.$fr['cl_res_count_number'].')' ?></td>
					<td><?= $fr['name_of_party'] ?></td>
					<td><?= $fr['refunded_towards_description'] ?></td>
					<td><?= $fr['mod_refund'] ?></td>
					<td><?= $fr['amount'] ?></td>
					<td><?= $fr['details_of_refund'] ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>

		<!-- Fee Released -->
		<h4 class="mb-10"><b><u>Fee Released:</u></b></h4>
		<div class="mb-10">
			<table class="table  table-bordered common-case-details-table" cellspacing="0">
				<tr>
					<th>S. No.</th>
					<th>Date of fee released</th>
					<th>Released to</th>
					<th>Mode of payment</th>
					<th>Amount</th>
					<th>Details of payment</th>
				</tr>
				<?php $count = 1; ?>
				<?php foreach($case_data['fee_released'] as $frel): ?>
				<tr>
					<td><?= $count++ ?></td>
					<td><?= $frel['date_of_fee_released'] ?></td>
					<td><?= $frel['released_to'] ?></td>
					<td><?= $frel['mop_description'] ?></td>
					<td><?= $frel['amount'] ?></td>
					<td><?= $frel['details_of_fee_released'] ?></td>
				</tr>
				<?php endforeach; ?>
			</table>

		</div>
	</div>


	<div class="award_termination">
		<h4 class="mb-10"><b><u>Award/Termination:</u></b></h4>
		<div class="mb-10">

			<?php if(isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award'): ?>
			<div class="col-md-12 form-group">
				<b>Award Status: </b>Case Awarded
			</div>
			<?php elseif(isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type']): ?>
			<div class="col-md-12 form-group">
				<b>Termination Status: </b>Case Terminated
			</div>
			<?php else: ?>
			<div class="col-md-12 form-group">
				<b>Case Processing: </b>Case is under process
			</div>
			<?php endif; ?>

			<?php if(isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'award'): ?>

			<div class="cd-col">
				<b>Date of Award: </b>
				<?= (isset($case_data['award_term_data']['date_of_award']))? $case_data['award_term_data']['date_of_award']:'' ?>
			</div>

			<div class="cd-col">
				<b>Nature of Award: </b>
				<?= (isset($case_data['award_term_data']['nature_of_award']))? $case_data['award_term_data']['nature_of_award']:'' ?>
			</div>

			<div class="cd-col">
				<b>Addendum Award, if any: </b>
				<?= (isset($case_data['award_term_data']['addendum_award']))? $case_data['award_term_data']['addendum_award']:'' ?>
			</div>

			<div class="cd-col">
				<b>Award served to Claimant on: </b>
				<?= (isset($case_data['award_term_data']['award_served_claimaint_on']))? $case_data['award_term_data']['award_served_claimaint_on']:'' ?>
			</div>

			<div class="cd-col">
				<b>Award served to Respondent on: </b>
				<?= (isset($case_data['award_term_data']['award_served_respondent_on']))? $case_data['award_term_data']['award_served_respondent_on']:'' ?>
			</div>

			<?php elseif(isset($case_data['award_term_data']['type']) && $case_data['award_term_data']['type'] == 'termination'): ?>
			<div class="cd-col">
				<b>Date of Termination: </b>
				<?= $case_data['award_term_data']['date_of_termination'] ?>
			</div>

			<div class="cd-col">
				<b>Reason for Termination: </b>
				<?= $case_data['award_term_data']['reason_for_termination'] ?>
			</div>

			<?php endif; ?>

			<div class="cd-col">
				<b>Factsheet prepared: </b>
				<?= (isset($case_data['award_term_data']['factsheet_prepared_desc']))? $case_data['award_term_data']['factsheet_prepared_desc']:'' ?>
			</div>
		</div>

	</div>
</div>