<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
	<style>
		.pdf_button{
			background-color:red;
			color:white;
		}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?= $page_title ?></h1>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box wrapper-box">
            			<div class="box-body ">

							<div>
				              	<table id="dataTableHTList" class="table table-condensed table-striped table-bordered dt-responsive" data-page-size="10">
				                    <input type="hidden" id="csrf_trans_token" value="<?php echo generateToken('dataTableHTList'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:7%;">S. No.</th>
											<th>Case No.</th>
											<th>Title of Case</th>
											<th>Arbitrator Name</th>
											<th>Purpose</th>
											<th>Date</th>
											<th>Time</th>
											<th>Room No.</th>
											<th>Remarks</th>
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
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";
		
		// Timepicker initialization
		$('.timepicker').timepicker({
			showInputs: false
		});
	</script>
	<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/hearings-today.js') ?>"></script>
	