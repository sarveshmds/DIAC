<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="box wrapper-box">
					<div class="box-body">
						<?php if (in_array($this->session->userdata('role'), ['DEPUTY_COUNSEL', 'DEO'])) : ?>
							<p class="">
								Send notification about arbitral tribunal to each persons in the matter. Click on the button to send the mail & message.
								<button class="btn btn-primary" type="button" id="btn_send_arb_tri_notification" data-case-no="<?= $case_no ?>">
									<i class="fa fa-send"></i> Send Notification
								</button>
							</p>
							<hr>
						<?php endif; ?>
						<div class="table-responsive">

							<table id="dataTableArbTriList" class="table table-condensed table-striped table-bordered dt-responsive nowrap" data-page-size="10">
								<input type="hidden" id="csrf_at_trans_token" value="<?php echo generateToken('dataTableArbTriList'); ?>">

								<?php if (isset($case_no)) : ?>
									<input type="hidden" id="at_case_no" value="<?php echo $case_no; ?>">
								<?php endif; ?>

								<thead>
									<tr>
										<th style="width:8%;">S. No.</th>
										<th>Wether on panel</th>
										<th>Name of Arbitrator</th>
										<th>Arbitrator Type</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Category</th>
										<th>Appointed By</th>
										<th>Date Of Appointment</th>
										<th>Date Of Consent</th>
										<th>Terminated</th>
										<th width="20%">Action</th>
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

<!-- =============================================================================== -->
<?php require_once(APPPATH . 'views/modals/diac-admin/arbitral-tribunal.php'); ?>

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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/add-arbitral-tribunal.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/add-arbitral-tribunal.js')) ?>"></script>