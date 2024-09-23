<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $page_title ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12 text-center">
								<div class="well">
									<div style="width: 280px; display: inline-flex;">
										<select name="response_case_no" id="response_case_no" class="response_case_no select2">
											<option value="">Select DIAC Registration No.</option>
											<?php foreach ($cases as $case) : ?>
												<option value="<?= $case['slug'] ?>"><?= '*' . $case['case_no_desc'] . ' (' . $case['case_title'] . ')' ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<button class="btn btn-info" id="response_filter_btn"><i class="fa fa-filter"></i> Filter</button>
									<div>
										<small class="text-muted">Note: Only alloted case will be visible here.</small>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<table id="datatableResponseFormat" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
								<input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('datatableResponseFormat'); ?>">
								<thead>
									<tr>
										<th style="width:7%;">S. No.</th>
										<th>Case No.</th>
										<th style="width: 30%">Subject</th>
										<th>Email to</th>
										<th>Status</th>
										<th style="width:20%;">Created At</th>
										<th style="width:15%;">Action</th>
									</tr>
								</thead>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<?php require_once(APPPATH . 'views/modals/diac-admin/noting.php'); ?>

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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/response-format.js') ?>"></script>