<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<?php require_once(APPPATH . 'views/templates/components/case-links.php') ?>

	<?php if (count($response_formats) > 0) : ?>
		<div class="content-header" style="margin-bottom: 10px;">
			<!-- Show the generated response formats -->
			<div class="box mb-0">
				<div class="box-body">
					<h4 class="case-title mb-2" style="margin-top: 0px;">
						Draft letters generated in the case
					</h4>
					<ul class="list-unstyled ">
						<?php foreach ($response_formats as $response) : ?>
							<li>
								<a href="<?= base_url('response-format/editable?type=' . $response['letter_type'] . '&case_no=' . $response['case_no'] . '&code=' . $response['code']) ?>" class="btn btn-warning" target="_BLANK"><i class="fa fa-paper-plane-o"></i> <?= (array_key_exists($response['letter_type'], RESPONSE_FORMAT_TYPES)) ? RESPONSE_FORMAT_TYPES[$response['letter_type']] : 'No Name Defined' ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<section class="content">
		<div class="box wrapper-box">
			<div class="box-body p-0">
				<div class="row">

					<div class="col-md-8 pr-0">
						<div class="box box-warning mb-0">
							<div class="box-header with-border">
								<h4 class="box-title">Notings: </h4>
							</div>
							<div class="box-body">
								<div class="table-responsive">
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

					<div class="col-md-4 pl-0">
						<div class="box box-warning mb-0">
							<div class="box-header with-border">
								<h4 class="box-title">Noting Form: </h4>
							</div>
							<div class="box-body">
								<?php require_once(APPPATH . 'views/forms/noting_form.php') ?>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
	</section>
</div>

<!-- ================================================================================ -->
<?php //require_once(APPPATH . 'views/modals/diac-admin/noting.php'); 
?>

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

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/noting.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/noting.js')) ?>"></script>