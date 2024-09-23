<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper" id="root">
	<section class="content-header">
      	<h1>Audit Logs</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-server"></i>Login Details</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12">
        		<div class="box">
            		<div class="box-body">
						<div class="col-lg-12">
			              	<table id="dtblLoginDetails" class="table table-condensed table-striped table-bordered  display nowrap">
			              		<input type="hidden" id="csrf_login_details_token" name="csrf_login_details_token" value="<?php echo generateToken('dtblLoginDetails'); ?>">
				                <thead>
					                <tr>
					                  	<th> # </th>
					                 	<th>Display Name</th>
					                 	<th>Role</th>
										<th>IP Address</th>
										<th>Date</th>
										<th>Message</th>
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
<script> base_url = "<?php echo base_url(); ?>"; </script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/login_details.js"></script>