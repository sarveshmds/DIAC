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
									<a class="nav-item nav-link" id="nav-sop-tab" data-toggle="tab" href="#nav-sop" role="tab" aria-controls="nav-sop" aria-selected="true">Claimant Details</a>
								</li>

								<li>
									<a class="nav-item nav-link" id="nav-op-tab" data-toggle="tab" href="#nav-op" role="tab" aria-controls="nav-op" aria-selected="false">Respondent Details</a>
								</li>
							</ul>

							<div class="tab-content" id="nav-tabContent">
								<!-- Claimant Start -->
								<div class="tab-pane fade active" id="nav-sop" role="tabpanel" aria-labelledby="nav-sop-tab">
									<div class="tab-content-col">
										<div>
											<table id="dataTableClaimantList" class="table table-condensed table-striped table-bordered" data-page-size="10">
												<input type="hidden" id="csrf_cl_trans_token" value="<?php echo generateToken('dataTableClaimantList'); ?>">

												<?php if (isset($case_no)) : ?>
													<input type="hidden" id="cl_case_no" value="<?php echo $case_no; ?>">
												<?php endif; ?>

												<thead>
													<tr>
														<th style="width:8%;">S. No.</th>
														<th>Claimant Name</th>
														<th>Claimant Number</th>
														<th>Email Id</th>
														<th>Phone Number</th>
														<th>Removed</th>
														<th style="width:15%;">Action</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>

								</div>
								<!-- Claimant End -->

								<!-- ====================================================== -->
								<!-- Respondent Start -->
								<div class="tab-pane fade" id="nav-op" role="tabpanel" aria-labelledby="nav-op-tab">
									<div class="tab-content-col">
										<div>
											<table id="dataTableRespondantList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
												<input type="hidden" id="csrf_res_trans_token" value="<?php echo generateToken('dataTableRespondantList'); ?>">

												<?php if (isset($case_no)) : ?>
													<input type="hidden" id="res_case_no" value="<?php echo $case_no; ?>">
												<?php endif; ?>

												<thead>
													<tr>
														<th style="width:8%;">S. No.</th>
														<th>Respondent Name</th>
														<th>Respondent Number</th>
														<th>Email Id</th>
														<th>Phone Number</th>
														<th>Counter Claimant</th>
														<th>Removed</th>
														<th style="width:15%;">Action</th>
													</tr>
												</thead>
											</table>
										</div>

									</div>
								</div>
								<!-- Respondent end -->

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/claimant-respondant.php'); ?>
<?php require_once(APPPATH . 'views/modals/diac-admin/address.php'); ?>

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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/claimant-respondant.js') ?>"></script>