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

							<table id="dataTableNotingList" class="table table-condensed table-bordered dt-responsive" data-page-size="10">
								<input type="hidden" id="csrf_noting_trans_token" value="<?php echo generateToken('dataTableNotingList'); ?>">

								<?php if (isset($case_no)) : ?>
									<input type="hidden" id="noting_case_no" value="<?php echo $case_no; ?>">
								<?php endif; ?>

								<thead>
									<tr>
										<th style="width:7%;">S. No.</th>
										<th style="width: 30%">Noting</th>
										<th>Marked by</th>
										<th>Marked to/File sent</th>
										<th>Date of noting</th>
										<th>Next Date</th>
										<th>Attachment</th>
										<?php if ($this->session->userdata('role') == 'DIAC') : ?>
											<th>Action</th>
										<?php endif; ?>
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
	var user_name = "<?php echo $this->session->userdata('user_name'); ?>";
</script>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/noting.js') ?>"></script>