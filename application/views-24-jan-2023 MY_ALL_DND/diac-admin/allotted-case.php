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

							<?php require_once(APPPATH.'views/templates/components/case-filters.php'); ?>

							<div>
				              	<table id="dataTableAllottedCaseList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
				                    <input type="hidden" id="csrf_ac_trans_token" value="<?php echo generateToken('dataTableAllottedCaseList'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:10%;">S. No.</th>
											<th>DIAC Registration No.</th>
											<th>Case Title</th>
											<th>Date of registration</th>
											<th>Case Status</th>
											<th>Arbitrator Status</th>
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
	
	<?php if($this->session->userdata('role') == 'CASE_MANAGER'): ?>
		<!-- =============================================================================== -->
		<?php require_once(APPPATH.'views/modals/diac-admin/cm-fees-status.php'); ?>
	<?php endif; ?>	
	
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
	
	<script type="text/javascript">
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
	</script>

	<?php if($this->session->userdata('role') == 'CASE_MANAGER'): ?>
		<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/allotted-case-case-manager.js') ?>"></script>
	<?php else: ?>
		<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/allotted-case.js') ?>"></script>
	<?php endif; ?>
