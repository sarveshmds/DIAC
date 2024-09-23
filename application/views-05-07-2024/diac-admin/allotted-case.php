<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
	<?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<!-- <div class="box wrapper-box">
					<div class="box-body">
						<?php //require_once(APPPATH . 'views/templates/components/case-filters.php'); 
						?>
					</div>
				</div> -->
				<div class="box wrapper-box">
					<div class="box-body">
						<fieldset class="fieldset">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group col-md-3">
										<label class="control-label">DIAC Registration No.:</label>
										<select class="form-control select2" id="filter_allotted_case_list" name="filter_allotted_case_list">
											<option value="">Select</option>
											<?php foreach ($alloted_cases_list as $case) : ?>
												<option value="<?= $case['slug'] ?>"><?= get_full_case_number($case) ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group col-md-3">
										<button type="button" class="btn btn-default" id="allotted_case_f_btn_reset" name="allotted_case_f_btn_reset" style="margin-top: 24px;"><i class='fa fa-refresh'></i> Reset</button>
										<button type="submit" class="btn btn-info" id="allotted_case_f_btn_submit" name="allotted_case_f_btn_submit" style="margin-top: 24px;"><i class='fa fa-filter'></i> Filter</button>
									</div>
								</div>
							</div>
							<div>
							</div>
						</fieldset>
						<div>
							<table id="dataTableAllottedCaseList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
								<input type="hidden" id="csrf_ac_trans_token" value="<?php echo generateToken('dataTableAllottedCaseList'); ?>">
								<thead>
									<tr>
										<th style="width:6%;">S. No.</th>
										<th>DIAC Registration No.</th>
										<th>Case Title</th>
										<th>Arbitration Type</th>
										<th>Date of registration</th>
										<th>Case Hearing</th>
										<th>Case Status</th>
										<th>Arbitrator Status</th>
										<th>Arbitrators</th>
										<th style="text-align: center;">Action</th>
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

<?php if ($this->session->userdata('role') == 'CASE_MANAGER') : ?>
	<!-- =============================================================================== -->
	<?php require_once(APPPATH . 'views/modals/diac-admin/cm-fees-status.php'); ?>
<?php endif; ?>

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

<?php if ($this->session->userdata('role') == 'TEST') : ?>
	<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/allotted-case-case-manager.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/allotted-case-case-manager.js')) ?>"></script>

<?php else : ?>
<?php endif; ?>

<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/allotted-case.js?v=' . filemtime(FCPATH . 'public/custom/js/diac_dashboard/allotted-case.js')) ?>"></script>