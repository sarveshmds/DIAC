	<div class="content-wrapper" >
		<section class="content-header">
	      	<h1>Approval User Create</h1>
	      	<ol class="breadcrumb">
	        	<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
	        	<li class="active"><a href="#"><i class="fa fa-try"></i> Approval User Create</a></li>
	        </ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs" role="tablist" id="myTab">
							<li id="tabDepartmentClick" class="active"><a href="#tabDepartment" data-toggle='tab'>Department</a></li>
							<li id="tabVendorClick"><a href="#tabVendor" data-toggle='tab'>Vendor</a></li>
							<li id="tabTechnicianClick"><a href="#tabTechnician" data-toggle='tab'>Technician</a></li>
						</ul>
			            <div class="tab-content no-padding">
						 	<div class="chart tab-pane active" id="tabDepartment" style="position: relative; ">
						 		<div class="col-xs-12 col-sm-12 col-md-12">
							 		<div class="row">
								        <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 5px;">
								          	<div class="box box-primary">
									            <div class="box-body">
									            	<div class="col-lg-12 col-xs-12">
									            		<input type="hidden" id="hidDeptCsrfToken"  value="<?php echo generateToken('dtblDepartmentMaster'); ?>">
								           				<table id="dtblDepartmentMaster" class="table table-bordered table-hover">
											                <thead>
										                        <tr>
										                            <th>Sl No</th>
										                            <th>Department</th>
										                            <th>Dept. Code</th>
										                            <th>Login Status</th>
										                           	<th>Action</th>
										                        </tr>
										                    </thead>
											            </table>
													</div>
									           	</div>
								          	</div>
								        </div>
								    </div>
								</div>
						    </div>
						    <!-- End Department tab -->
						    <!-- Vendor Tab -->
						 	<div class="chart tab-pane" id="tabVendor" style="position: relative;">
						 		<div class="col-xs-12 col-sm-12 col-md-12">
							 		<div class="row">
								        <div class="col-xs-12 col-sm-12 col-md-12">
								          	<div class="box box-primary">
									            <div class="box-body">
									            	<div class="col-lg-12 col-xs-12">
									            		<input type="hidden" id="hidVendorCsrfToken" value="<?php echo generateToken('dtblVendorMaster'); ?>">
								           				<table id="dtblVendorMaster" class="table table-bordered table-hover">
											                <thead>
										                        <tr>
										                            <th>Sl No</th>
										                            <th>Vendor Code</th>
										                            <th>Vendor Name</th>
										                            <th>Login Status</th>
										                           	<th>Action</th>
										                        </tr>
										                    </thead>
											            </table>
													</div>
									           	</div>
								          	</div>
								        </div>
								    </div>
								</div>
						    </div>
						    <div class="chart tab-pane" id="tabTechnician" style="position: relative;">
						 		<div class="col-xs-12 col-sm-12 col-md-12">
							 		<div class="row">
								        <div class="col-xs-12 col-sm-12 col-md-12">
								          	<div class="box box-primary">
									            <div class="box-body">
									            	<div class="col-lg-12 col-xs-12">
									            		<input type="hidden" id="hidTechCsrfToken" value="<?php echo generateToken('dtblTechnicianMaster'); ?>">
								           				<table id="dtblTechnicianMaster" class="table table-bordered table-hover">
											                <thead>
										                        <tr>
										                            <th>Sl No</th>
										                            <th>Technician Name</th>
										                            <th>Phone No</th>
										                            <th>Login Status</th>
										                           	<th>Action</th>
										                        </tr>
										                    </thead>
											            </table>
													</div>
									           	</div>
								          	</div>
								        </div>
								    </div>
								</div>
						    </div>
						</div>
					</div>
		    	</div>
		    </div>
		</section>
	</div>
	<script> var base_url = '<?php echo base_url(); ?>';</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/approval_user_create.js"></script>
