<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">

						<?php require_once(APPPATH . 'views/templates/components/case-filters.php'); ?>

						<div>
							<div class="row flex-row" style="align-items: flex-end;">
								<div class="col-md-8">
									<?php require_once(APPPATH . 'views/templates/components/case-sorting-filters.php'); ?>
								</div>
								<div class="col-md-4">

									<div class="text-right" style="margin-bottom: 5px;">
										<!-- <a href="<?= base_url('add-new-case') ?>" class="btn btn-custom"><i class="fa fa-plus"></i> Add New Case</a> -->
										<a href="<?= base_url('excel/registered-cases-list') ?>" class="btn btn-success" target="_BLANK"><i class="fa fa-file-excel-o"></i> Excel</a>
										<a href="<?= base_url('pdf/registered-cases-list') ?>" class="btn btn-danger" target="_BLANK"><i class="fa fa-file-pdf-o"></i> PDF</a>
									</div>
								</div>
							</div>

							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab_1" class="rc_nav_link" data-toggle="tab" aria-expanded="true" data-case-type="GENERAL">
											General Cases
											<span class="badge bg-green">
												<?= $cases_counts['general_cases_count'] ?>
											</span>
										</a></li>
									<li class=""><a href="#tab_2" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="EMERGENCY">
											Emergency Cases
											<span class="badge bg-green">
												<?= $cases_counts['emergency_cases_count'] ?>
											</span>
										</a></li>
									<!-- <li class=""><a href="#tab_3" class="rc_nav_link" data-toggle="tab" aria-expanded="false" data-case-type="FASTTRACK">Fasttrack Cases</a></li> -->
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_1">
										<table id="dataTableCaseform" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
											<input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCaseform'); ?>">
											<thead>
												<tr>
													<th style="width:8%;">S. No.</th>
													<th>DIAC Registration No.</th>
													<th>Case Title.</th>
													<th>Mode of reference</th>
													<th>Type of Arbitration</th>
													<th>Case Type</th>
													<th>Date of registration</th>
													<th>Status</th>
													<th style="width:10%;">Action</th>
												</tr>
											</thead>
										</table>
									</div>

									<div class="tab-pane" id="tab_2">
										<table id="dataTableEmergencyCases" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
											<input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCaseform'); ?>">
											<thead>
												<tr>
													<th style="width:8%;">S. No.</th>
													<th>DIAC Registration No.</th>
													<th>Case Title.</th>
													<th>Mode of reference</th>
													<th>Type of Arbitration</th>
													<th>Case Type</th>
													<th>Date of registration</th>
													<th>Status</th>
													<th style="width:10%;">Action</th>
												</tr>
											</thead>
										</table>
									</div>

									<!-- <div class="tab-pane" id="tab_3">
										<table id="dataTableFastTrackCases" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
											<input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableCaseform'); ?>">
											<thead>
												<tr>
													<th style="width:8%;">S. No.</th>
													<th>Diary No.</th>
													<th>DIAC Registration No.</th>
													<th>Case Title.</th>
													<th>Mode of reference</th>
													<th>Type of Arbitration</th>
													<th>Case Type</th>
													<th>Date of registration</th>
													<th>Status</th>
													<th style="width:10%;">Action</th>
												</tr>
											</thead>
										</table>
									</div> -->

								</div>

							</div>
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
	var base_url = "<?php echo base_url(); ?>";
	var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/all-registered-cases.js') ?>"></script>