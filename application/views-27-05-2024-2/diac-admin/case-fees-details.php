<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body p-0">
						<div>

							<ul class="nav nav-tabs" id="nav-tab" role="tablist">
								<li class="active">
									<a class="nav-item nav-link" id="nav-sop-tab" data-toggle="tab" href="#nav-sop" role="tab" aria-controls="nav-sop" aria-selected="true">Fees Assessments</a>
								</li>

								<li>
									<a class="nav-item nav-link" id="nav-op-tab" data-toggle="tab" href="#nav-op" role="tab" aria-controls="nav-op" aria-selected="false">Fee Deposit</a>
								</li>
								<li>
									<a class="nav-item nav-link" id="nav-oc-tab" data-toggle="tab" href="#nav-oc" role="tab" aria-controls="nav-oc" aria-selected="false">Cost Deposit</a>
								</li>

								<li>
									<a class="nav-item nav-link" id="nav-fr-tab" data-toggle="tab" href="#nav-fr" role="tab" aria-controls="nav-fr" aria-selected="false">Fee Refund</a>
								</li>

								<li>
									<a class="nav-item nav-link" id="nav-aw-fr-tab" data-toggle="tab" href="#nav-aw-fr" role="tab" aria-controls="nav-aw-fr" aria-selected="false">Fee Released</a>
								</li>
							</ul>

							<div class="tab-content" id="nav-tabContent">
								<!-- Fee and cost Start -->
								<div class="tab-pane fade active" id="nav-sop" role="tabpanel" aria-labelledby="nav-sop-tab">
									<div class="tab-content-col">

										<?php if ($this->session->userdata('role') == 'DEPUTY_COUNSEL') : ?>
											<div class="text-right">
												<!-- <button type="button" id="btn_add_fee" class="btn btn-custom"><i class="fa fa-plus"></i> Add Assessment</button> -->

												<a href="<?= base_url('case-fees-details/add-assessment/' . $case_no) ?>" class="btn btn-custom"><i class="fa fa-plus"></i> Create New Assessment</a>
											</div>
										<?php endif; ?>

										<?php if (count($fee_cost_data) > 0) : ?>
											<?php foreach ($fee_cost_data as $key => $fc) : ?>
												<?php require(APPPATH . 'views/templates/components/fee_assessment_fieldset.php'); ?>
											<?php endforeach; ?>
										<?php else : ?>
											<div class="alert alert-danger mt-10 mb-0">
												<h5 class="text-white"><i class="fa fa-close"></i> No assessment is added.</h5>
											</div>
										<?php endif; ?>

									</div>

								</div>
								<!-- Fee and cost End -->

								<!-- ====================================================== -->
								<!-- Fee Deposit Start -->
								<div class="tab-pane fade" id="nav-op" role="tabpanel" aria-labelledby="nav-op-tab">
									<div class="tab-content-col">

										<fieldset class="fieldset">
											<legend>Fee Deposit</legend>
											<div class="fieldset-content-box">
												<table id="dataTableFeeDepositList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
													<input type="hidden" id="csrf_fd_trans_token" value="<?php echo generateToken('dataTableFDList'); ?>">

													<?php if (isset($case_no)) : ?>
														<input type="hidden" id="fd_case_no" value="<?php echo $case_no; ?>">
													<?php endif; ?>

													<thead>
														<tr>
															<th style="width:8%;">S. No.</th>
															<th>Date of deposit</th>
															<th>Deposited by</th>
															<th>Name of depositor</th>
															<th>Deposited towards</th>
															<th>Mode of deposit</th>
															<th>DIAC Txn. ID</th>
															<th>Details</th>
															<th>Amount</th>
															<th>Cheque Bounce</th>
															<th style="width:15%;">Action</th>
														</tr>
													</thead>
												</table>
											</div>
										</fieldset>

									</div>
								</div>
								<!-- Fee Deposit end -->

								<!-- ====================================================== -->
								<!-- Cost deposit Start -->
								<div class="tab-pane fade" id="nav-oc" role="tabpanel" aria-labelledby="nav-oc-tab">
									<div class="tab-content-col">

										<fieldset class="fieldset">
											<legend>Cost Deposit</legend>
											<div class="fieldset-content-box">
												<table id="dataTableCostDepositList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
													<input type="hidden" id="csrf_cd_trans_token" value="<?php echo generateToken('dataTableCDList'); ?>">

													<?php if (isset($case_no)) : ?>
														<input type="hidden" id="cd_case_no" value="<?php echo $case_no; ?>">
													<?php endif; ?>

													<thead>
														<tr>
															<th style="width:8%;">S. No.</th>
															<th>Date of deposit</th>
															<th>Deposited by</th>
															<th>Name of depositor</th>
															<th>Mode of deposit</th>
															<th>Cost Imposed</th>
															<th>Amount</th>
															<th>Details Of Deposit</th>
															<th style="width:15%;">Action</th>
														</tr>
													</thead>
												</table>
											</div>
										</fieldset>

									</div>
								</div>
								<!-- Cost deposit end -->


								<!-- ====================================================== -->
								<!-- Fee refund Start -->
								<div class="tab-pane fade" id="nav-fr" role="tabpanel" aria-labelledby="nav-fr-tab">
									<div class="tab-content-col">

										<fieldset class="fieldset">
											<legend>Fee Refund</legend>
											<div class="fieldset-content-box">
												<table id="dataTableFeeRefundList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
													<input type="hidden" id="csrf_fee_ref_trans_token" value="<?php echo generateToken('dataTableFeeRefList'); ?>">

													<?php if (isset($case_no)) : ?>
														<input type="hidden" id="fee_ref_case_no" value="<?php echo $case_no; ?>">
													<?php endif; ?>

													<thead>
														<tr>
															<th style="width:8%;">S. No.</th>
															<th style="">Date of refund</th>
															<th style="">Refunded to</th>
															<th style="">Name of party</th>
															<th style="">Refunded towards</th>
															<th style="">Mode of refund</th>
															<th>Details</th>
															<th style="">Amount</th>
															<th style="width:15%;">Action</th>
														</tr>
													</thead>
												</table>
											</div>
										</fieldset>

									</div>
								</div>
								<!-- Fee refund end -->


								<!-- ====================================================== -->
								<!-- Fee Released Start -->
								<div class="tab-pane fade" id="nav-aw-fr" role="tabpanel" aria-labelledby="nav-aw-fr-tab">
									<div class="tab-content-col">

										<fieldset class="fieldset">
											<legend>Fee Released</legend>
											<div class="fieldset-content-box">
												<table id="dataTableFeeReleasedList" class="table table-condensed table-striped table-bordered" data-page-size="10">
													<input type="hidden" id="csrf_fr_trans_token" value="<?php echo generateToken('dataTableFRList'); ?>">

													<?php if (isset($case_no)) : ?>
														<input type="hidden" id="fr_case_no" value="<?php echo $case_no; ?>">
													<?php endif; ?>

													<thead>
														<tr>
															<th style="width:8%;">S. No.</th>
															<th>Nature of award</th>
															<th>Date of fee released</th>
															<th>Released to</th>
															<th>Mode</th>
															<th>Details</th>
															<th>Date of fee released note</th>
															<th>Amount</th>
															<th style="width:15%;">Action</th>
														</tr>
													</thead>
												</table>
											</div>
										</fieldset>

									</div>
								</div>
								<!-- Fee Released Start -->

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/fee.php'); ?>
<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/fee-deposit.php'); ?>
<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/cost-deposit.php'); ?>
<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/fee-refund.php'); ?>
<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/fee-released.php'); ?>


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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/fee-deposit-cost.js') ?>"></script>

<script>
	function calculate_fees(claim_amount) {
		if (claim_amount) {
			$.ajax({
				url: base_url + 'calculate-fees/case-fee',
				type: 'POST',
				data: {
					claim_amount: claim_amount
				},
				success: function(response) {
					var response = JSON.parse(response);
					console.log(response)
					if (response.status == true) {
						var data = response.data;
						$('#fc_total_arb_fees').val(response.data.total_arbitrator_fees);

						$('#fc_cs_arb_fees').val(response.data.each_party_share_in_arb_fees);
						$('#fc_rs_arb_fees').val(response.data.each_party_share_in_arb_fees);

						$('#fc_cs_adm_fees').val(response.data.administrative_charges);
						$('#fc_rs_adm_fees').val(response.data.administrative_charges);

					} else if (response.status == 'validation_error') {
						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							text: response.msg
						})
					} else if (response.status == false) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: response.msg
						})
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Server Error'
						})
					}
				},
				error: function(response) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Server Error, please try again'
					})
				}
			});
		} else {
			$('#btn_submit').prop('disabled', true)
			$('.fees-wrapper-col').hide();
			toastr.error('Please enter claim amount')
		}
	}

	function calculate_seperate_assessed_fees(claim_amount, counter_claim_amount) {
		if (claim_amount) {
			$.ajax({
				url: base_url + 'calculate-fees/case-fee-seperate-assessed',
				type: 'POST',
				data: {
					claim_amount: claim_amount,
					counter_claim_amount: counter_claim_amount
				},
				success: function(response) {
					var response = JSON.parse(response);
					console.log(response)
					if (response.status == true) {
						var data = response.data;
						$('#fc_total_arb_fees').val(response.data.total_arbitrator_fees);

						$('#fc_cs_arb_fees').val(response.data.claimant_share_in_arb_fees);
						$('#fc_rs_arb_fees').val(response.data.respondant_share_in_arb_fees);

						$('#fc_cs_adm_fees').val(response.data.claimant_administrative_charges);
						$('#fc_rs_adm_fees').val(response.data.respondant_administrative_charges);

					} else if (response.status == 'validation_error') {
						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							text: response.msg
						})
					} else if (response.status == false) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: response.msg
						})
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Server Error'
						})
					}
				},
				error: function(response) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Server Error, please try again'
					})
				}
			});
		} else {
			$('#btn_submit').prop('disabled', true)
			$('.fees-wrapper-col').hide();
			toastr.error('Please enter claim amount')
		}
	}
	// ======================================================
	// Calculate fees
	$('#fc_sum_despute').on('keyup', function() {
		var claim_amount = $(this).val();

		if (claim_amount.length > 2) {
			calculate_fees(claim_amount)
		}
	})

	// Seperate assessment ====================================
	$('#fc_sum_despute_claim').on('keyup', function() {
		var claim_amount = $('#fc_sum_despute_claim').val();
		var counter_claim_amount = $('#fc_sum_despute_cc').val();

		if (claim_amount && counter_claim_amount) {
			calculate_seperate_assessed_fees(claim_amount, counter_claim_amount)
		}
	})

	$('#fc_sum_despute_cc').on('keyup', function() {
		var claim_amount = $('#fc_sum_despute_claim').val();
		var counter_claim_amount = $('#fc_sum_despute_cc').val();

		if (claim_amount && counter_claim_amount) {
			calculate_seperate_assessed_fees(claim_amount, counter_claim_amount)
		}
	})
</script>