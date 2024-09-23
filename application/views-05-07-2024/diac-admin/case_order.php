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
							<?php //echo $case_no 
							?>

							<table id="dataTableCaseOrder" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
								<input type="hidden" id="csrf_case_order_token" value="<?php echo generateToken('dataTableCaseOrder'); ?>">

								<?php if (isset($case_no)) : ?>
									<input type="hidden" id="case_code" value="<?php echo $case_no; ?>">
								<?php endif; ?>
								<thead>
									<tr>
										<th style="width:7%;">S. No.</th>
										<th>Order Given On:</th>
										<th>Order Document.</th>
										<th>Created At</th>
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
<?php require_once(APPPATH . 'views/modals/diac-admin/case-order.php'); ?>


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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/case_order.js') ?>"></script>