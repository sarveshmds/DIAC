<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<style>
	.pdf_button {
		background-color: red;
		color: white;
	}
</style>
<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">

						<?php require_once(APPPATH . 'views/templates/components/case-filters.php'); ?>
						<?php require_once(APPPATH . 'views/templates/components/case-sorting-filters.php'); ?>

						<div>
							<table id="dataTableCaseRegistered" class="table table-condensed table-striped table-bordered" data-page-size="10">
								<input type="hidden" id="csrf_crl_trans_token" value="<?php echo generateToken('dataTableCaseRegisteredList'); ?>" />
								<thead>
									<tr>
										<th style="width:8%;">S. No.</th>
										<th>DIAC Registration No.</th>
										<th>Case Title.</th>
										<th>Date of reference order</th>
										<th>Mode of reference</th>
										<th>Ref. Received On</th>
										<th>Date of registration</th>
										<th>Status</th>
										<th style="width:10%;">Action</th>
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
<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/all-cases.js') ?>"></script>