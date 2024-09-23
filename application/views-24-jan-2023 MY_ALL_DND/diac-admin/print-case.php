<!DOCTYPE html>
<html>
    <head>
	  	<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<meta name="keyword" content="">
  		<meta name="description" content="">
  		<link rel="icon" type="image/png" sizes="96x96" href="">
  		<title>DIAC</title>
	  	<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  	<!-- Bootstrap 3.3.7 -->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
	  	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrapValidator.css" />
	  	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap-select.css" />
	  	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap-multiselect.css" />
	  	
	  	
	  	<!-- Font Awesome -->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/font-awesome/css/font-awesome.min.css">
	  	
	  	<!-- Theme style -->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/AdminLTE.min.css">
	  	
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/skins/_all-skins.min.css">
		
		<!-- custom Css -->
		<link rel="stylesheet" href="<?php echo base_url();?>public/custom/css/admin.css">
		
	  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		
	  	<!-- jQuery 3 -->
		<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="<?php echo base_url();?>public/bower_components/jquery-ui/jquery-ui.min.js"></script>
		
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		
		
		<!-- AdminLTE App -->
		<script src="<?php echo base_url();?>public/dist/js/adminlte.min.js"></script>
				<script src="<?php echo base_url();?>public/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="<?php echo base_url();?>public/dist/js/demo.js"></script>
		
		<style type="text/css">

			thead {
			    background: #efefef;
			    color: #191919;
			}
			.table{
				border: 2px solid #d8d8d8;
				padding: 10px;
				width: 100%;
			}
			.table tr th{
				background-color: #efefef;
				border: 1px solid #D8D8D8;
				font-weight: bold;
			}
			.table tr td{
				border: 0.5px solid #D8D8D8;
			}
			.print-btn-col{
				text-align: center;
				margin-top: 30px;
			}
			.ct-heading{
			  	text-align: center;
			  	margin-top: 10px;
			  	font-size: 25px;
			  	margin-bottom: 20px;
			}

			.overflow-x{
				overflow-x: scroll;
			}
			

			@media print {
			  .ct-heading{
			  	text-align: center;
			  }
			  .print-btn-col{
			    visibility: hidden;
			  }
			}
		</style>
	</head>
    <body class="hold-transition skin-blue fixed sidebar-mini" id="root">
        
        	<h1 class="ct-heading"><u>
				<b><?= $case_data['case_data']['case_title'] ?></b>
			</u></h1>
<div class="content-wrappers">
	<section class="content">

    			<div class="row">
						<div class="col-lg-12 overflow-x">
							
							<div class="row">
								<div class="col-md-3 form-group">
									<b>DIAC Case No.: </b>
									<?= $case_data['case_data']['case_no'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Referred On: </b>
									<?= $case_data['case_data']['reffered_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Referred By (Direct/Court): </b>
									<?= $case_data['case_data']['reffered_by_desc'] ?>
								</div>
								<div class="col-md-3 form-group">
									<b>Referred By (Judge/Justice): </b>
									<?= $case_data['case_data']['reffered_by_judge'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Petition/Reference No. : </b>
									<?= $case_data['case_data']['arbitration_petition'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Name of Court/Department: </b>
									<?= $case_data['case_data']['name_of_court'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Name of arbitrator: </b>
									<?= $case_data['case_data']['name_of_judge'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Received On: </b>
									<?= $case_data['case_data']['recieved_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Registered On: </b>
									<?= $case_data['case_data']['registered_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Type of arbitration: </b>
									<?= $case_data['case_data']['type_of_arbitration_desc'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Status: </b>
									<?= $case_data['case_data']['case_status_dec'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Remarks: </b>
									<?= $case_data['case_data']['remarks'] ?>
								</div>

							</div>
							<!-- Claimants -->
							<h4><b><u>Claimants:</u></b></h4>
							<div class="">
								<table class="table table-bordered common-data-table" id="cl_data_table">
									
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
							<h4><b><u>Respondants</u></b></h4>
							<div class="">
								<table class="table table-bordered common-data-table" id="res_data_table">
									
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
					  	

					  		<div class="row">
								<div class="col-md-3 form-group">
									<b>Claim Invited On: </b>
									<?= $case_data['sop_data']['claim_invited_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Reminder to claim: </b>
									<?= $case_data['sop_data']['rem_to_claim_on'] ?>
								</div>

								<?php if(isset($case_data['sop_data']['rem_to_claim_on_2']) && !empty($case_data['sop_data']['rem_to_claim_on_2'])): ?>
								<div class="col-md-3 col-sm-6 col-xs-12 form-group">
									<b>Reminder to claim (2): </b>
									<?= $case_data['sop_data']['rem_to_claim_on_2'] ?>
								</div>					
								<?php endif; ?>

								<div class="col-md-3 form-group">
									<b>Claim filed On: </b>
									<?= $case_data['sop_data']['claim_filed_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Respondent served on: </b>
									<?= $case_data['sop_data']['res_served_on'] ?>
								</div>


								<div class="col-md-3 form-group">
									<b>Statement of defence filed on: </b>
									<?= $case_data['sop_data']['sod_filed_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Whether filed beyond period of limitation: </b>
									<?= $case_data['sop_data']['sod_pol'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Number of days of delay: </b>
									<?= $case_data['sop_data']['sod_dod'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Rejoinder to Statement of Defence filed on: </b>
									<?= $case_data['sop_data']['rej_stat_def_filed_on'] ?>
								</div>

								<!-- Counter Claim -->
								<div class="col-md-3 form-group">
									<b>Counter Claim: </b>
									<?= $case_data['sop_data']['counter_claim'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Date of filing of Counter Claim: </b>
									<?= $case_data['sop_data']['dof_counter_claim'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Reply to Counter Claim filed on: </b>
									<?= $case_data['sop_data']['reply_counter_claim_on'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Whether filed beyond period of limitation: </b>
									<?= $case_data['sop_data']['reply_counter_claim_pol'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Number of days of delay: </b>
									<?= $case_data['sop_data']['reply_counter_claim_dod'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Rejoinder to Reply of Counter Claim filed on: </b>
									<?= $case_data['sop_data']['rej_reply_counter_claim_on'] ?>
								</div>


								<!-- Application act -->
								<div class="col-md-3 form-group">
									<b>Application under Section 17 of A&C Act: </b>
									<?= $case_data['sop_data']['app_section'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Date of filing of Application (17): </b>
									<?= $case_data['sop_data']['dof_app'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Reply to the Application filed on (17): </b>
									<?= $case_data['sop_data']['reply_app_on'] ?>
								</div>

								<!-- Application act -->
								<div class="col-md-3 form-group">
									<b>Application under Section 16 of A&C Act: </b>
									<?= $case_data['sop_data']['app_section_16'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Date of filing of Application (16): </b>
									<?= $case_data['sop_data']['dof_app_16'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Reply to the Application filed on (16): </b>
									<?= $case_data['sop_data']['reply_app_on_16'] ?>
								</div>

								<div class="col-md-3 form-group">
									<b>Remarks: </b>
									<?= $case_data['sop_data']['remarks'] ?>
								</div>
							</div>
							<!-- Other Pleadings -->
							<h4><b><u>Miscelleneous:</u></b></h4>
							<div class="">
								<table class="table  table-bordered common-data-table" id="op_data_table">
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
								<h4><b><u>Counsels:</u></b></h4>
								<table class="table  table-bordered common-data-table" id="res_data_table">
									
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
								<h4><b><u>Arbitral Tribunal:</u></b></h4>
								<table class="table  table-bordered common-data-table" id="res_data_table">
									
										<tr>
											<th width="8%">S. No.</th>
											<th width="12%">Name</th>
											<th width="10%">Wheather On Panel</th>
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
								<h4><b><u>Notings:</u></b></h4>

								<table class="table  table-bordered common-data-table" id="res_data_table">
									
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
					    		<h4><b><u>Fee Details:</u></b></h4>
								<div class="row">
									<div class="col-md-3 form-group">
										<b>Whether Claims or Counter Claims assessed separately: </b>
										<?= $case_data['fee_cost_data']['c_cc_asses_sep_desc'] ?>
									</div>

									<?php if($case_data['fee_cost_data']['c_cc_asses_sep'] == 'yes'): ?>
										<div class="col-md-3 form-group">
											<b>Sum in Dispute (Claims) (Rs.): </b>
											<?= $case_data['fee_cost_data']['sum_in_dispute_claim'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Sum in Dispute (Counter Claims) (Rs.): </b>
											<?= $case_data['fee_cost_data']['sum_in_dispute_cc'] ?>
										</div>
									<?php elseif($case_data['fee_cost_data']['c_cc_asses_sep'] == 'no'): ?>
										<div class="col-md-3 form-group">
											<b>Sum in Dispute (Rs.): </b>
											<?= $case_data['fee_cost_data']['sum_in_dispute'] ?>
										</div>
									<?php endif; ?>

									<div class="col-md-3 form-group">
										<b>As per provisional assessment dated: </b>
										<?= $case_data['fee_cost_data']['asses_date'] ?>
									</div>


									<div class="col-md-3 form-group">
										<b>Total Arbitrators Fees (Rs.): </b>
										<?= $case_data['fee_cost_data']['total_arb_fees'] ?>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
										<h4><b><u>Claimant's Share:</u></b></h4>
										<div class="form-group">
											<b>Arbitrators Fees (Rs.): </b>
											<?= $case_data['fee_cost_data']['cs_arb_fees'] ?>
										</div>

										<div class="form-group">
											<b>Administrative Expenses (Rs.): </b>
											<?= $case_data['fee_cost_data']['cs_adminis_fees'] ?>
										</div>
									</div>

									<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
										
										<h4><b><u>Respondant's Share:</u></b></h4>
										<div class="form-group">
											<b>Arbitrators Fees (Rs.): </b>
											<?= $case_data['fee_cost_data']['rs_arb_fees'] ?>
										</div>

										<div class="form-group">
											<b>Administrative Expenses (Rs.): </b>
											<?= $case_data['fee_cost_data']['rs_adminis_fee'] ?>
										</div>
										
									</div>

								</div>

								<!-- Fee Deposit -->
								<h4><b><u>Fee Deposit:</u></b></h4>
								<div class="">

									<table class="table  table-bordered common-data-table" id="op_data_table">
										
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

									<div class="row">
										<div class="col-xs-12">
											<b>Total Fee: </b>
											<?= (empty($case_data['amount_n_balance']['total_amount']))?'N/A': 'Rs. '.$case_data['amount_n_balance']['total_amount'] ?>
											
										</div>

										<div class="col-xs-12">
											
												<b>Fee Deposited: </b>
											<?= (empty($case_data['amount_n_balance']['deposit_amount']))?'N/A': 'Rs. '.$case_data['amount_n_balance']['deposit_amount'] ?>
											
										</div>

										<div class="col-xs-12">
											
												<b>Balanace: </b>
											<?= 'Rs. '.$case_data['amount_n_balance']['balance'] ?>
											
										</div>
									</div>
									<br>
										

								</div>

								<!-- Cost Deposit -->
								<h4><b><u>Cost Deposit:</u></b></h4>
								<div class="">

									<table class="table  table-bordered common-data-table" id="op_data_table">
										
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
								<h4><b><u>Fee Refund:</u></b></h4>
								<div class="">

									<table class="table  table-bordered common-data-table" id="op_data_table">
										
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
								<h4><b><u>Fee Released:</u></b></h4>
								<div class="">

									<table class="table  table-bordered common-data-table" id="op_data_table">
										
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
								<h4><b><u>Award/Termination:</u></b></h4>
								<div class="row">
									
									<?php if($case_data['award_term_data']['type'] == 'award'): ?>
										<div class="col-md-12 form-group">
											<b>Award Status: </b>Case Awarded
										</div>
									<?php elseif($case_data['award_term_data']['type']): ?>
										<div class="col-md-12 form-group">
											<b>Termination Status: </b>Case Terminated
										</div>
									<?php else: ?>
										<div class="col-md-12 form-group">
											<b>Case Processing: </b>Case is under process
										</div>
									<?php endif; ?>

									<?php if($case_data['award_term_data']['type'] == 'award'): ?>

										<div class="col-md-3 form-group">
											<b>Date of Award: </b>
											<?= $case_data['award_term_data']['date_of_award'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Nature of Award: </b>
											<?= $case_data['award_term_data']['nature_of_award'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Addendum Award, if any: </b>
											<?= $case_data['award_term_data']['addendum_award'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Award served to Claimant on: </b>
											<?= $case_data['award_term_data']['award_served_claimaint_on'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Award served to Respondent on: </b>
											<?= $case_data['award_term_data']['award_served_respondent_on'] ?>
										</div>

									<?php elseif($case_data['award_term_data']['type'] == 'termination'): ?>
										<div class="col-md-3 form-group">
											<b>Date of Termination: </b>
											<?= $case_data['award_term_data']['date_of_termination'] ?>
										</div>

										<div class="col-md-3 form-group">
											<b>Reason for Termination: </b>
											<?= $case_data['award_term_data']['reason_for_termination'] ?>
										</div>

									<?php endif; ?>


									<div class="col-md-3 form-group">
										<b>Factsheet prepared: </b>
										<?= $case_data['award_term_data']['factsheet_prepared_desc'] ?>
									</div>
								</div>

							</div>

							<?php if(isset($export_pdf) && $export_pdf == false): ?>
								<div class="print-btn-col">
									
									<button onclick="window.print()" class="btn btn-info"><span class="fa fa-print"></span> Print Details</button>
								</div>
							<?php endif; ?>

            			</div>
        			
        		</div>
      		
	</section>
</div>
	
		<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/diac-common-js.js') ?>"></script>
    </body>
</html>