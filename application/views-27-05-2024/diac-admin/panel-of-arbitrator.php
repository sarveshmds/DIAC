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

            				<!-- Filters Start -->
            				<div>
		        				<fieldset class="fieldset">
		        					<div class="col-xs-12">

		        						<div class="form-group col-md-3">
		        							<label>Name: </label>
											<input type="text" class="form-control" id="poa_f_name" name="poa_f_name"  autocomplete="off" maxlength="100" placeholder="Enter Name" />
										</div>

		        						<div class="form-group col-md-3">
		        							<label>Category: </label>
											<select name="poa_f_category" id="poa_f_category" class="form-control">
												<option value="">Select Category</option>
												<?php foreach($panel_category as $pc): ?>
													<option value="<?= $pc['id'] ?>"><?= $pc['category_name'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>

										<div class="form-group col-md-3 mt-24">
											<button class="btn btn-info" id="poa_f_btn"><span class="fa fa-filter"></span> Filter</button>
										</div>
		        					</div>
		        				</fieldset>
		        			</div>
		        			<!-- Filters End -->

							<div>
				              	<table id="dataTablePOAList" class="table table-striped table-bordered" data-page-size="10">
				                    <input type="hidden" id="csrf_poa_trans_token" value="<?php echo generateToken('dataTablePOAList'); ?>">
				                    <thead>
				                        <tr>
											<th style="width:10%;">S. No.</th>
											<th style="width:25%;">Name</th>
											<th style="">Category</th>
											<th hidden>Experience</th>
											<th hidden>Enrollment No.</th>
											<th hidden>Contacts Details</th>
											<th hidden>Email Details</th>
											<th hidden>Address</th>
											<th hidden>Remarks</th>
				                            <th style="width:18%;">Action</th>
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
	<?php require_once(APPPATH.'views/modals/diac-admin/panel-of-arbitrator.php'); ?>

	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
	<script>
		var base_url 	= "<?php echo base_url(); ?>";
		var role_code 	= "<?php echo $this->session->userdata('role'); ?>";

		// ==================================================================================
		// Filter the panel of arbitrator

		$('#poa_f_btn').on('click', function(){
			dataTable.ajax.reload();
		});
	</script>

	<?php if(in_array($this->session->userdata('role'), array('POA_MANAGER', 'DIAC', 'ADMIN'))): ?>
		<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/panel-of-arbitrator.js') ?>"></script>
	<?php else: ?>
		<script type="text/javascript" src="<?= base_url('public/custom/js/diac_dashboard/panel-of-arbitrator-view-mode.js') ?>"></script>
	<?php endif; ?>