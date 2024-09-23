<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />

<div class="content-wrapper" id="root">
	<section class="content-header">
      	<h1>User Role Assign</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-user-secret"></i>User Role Assign</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12">
        		<div class="box">
            		<div class="box-body">
						<div class="col-lg-12">
			              	<table id="user_role_assign_table" class="table table-condensed table-striped table-bordered  display nowrap">
			              		<input type="hidden" id="csrf_user_role_assign_token" name="csrf_user_role_assign_token" value="<?php echo generateToken('user_role_assign_table'); ?>">
				                <thead>
					                <tr>
					                  	<th>S.No.</th>
					                 	<th>Username</th>
					                 	<th>Name</th>
					                 	<th>User Role</th>
										<th style="width: 17%;">Action</th>
					                </tr>
				                </thead>
				                <tbody>
					               
					           </tbody>
			              	</table>
            			</div>
            		</div>
            	</div>
          	</div>
      	</div>
	</section>
</div>

<!-- Modal -->
<div class="modal fade" id="user_role_assign_modal" role="dialog" >
	<div class="modal-dialog">
      	<div class="modal-content">
      		<div class='modal-header'>
	          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
	          	<h4 class='modal-title urg-modal-title' style='text-align: center;'><span id="spanuser" >Add User Role</span></h4>
	        </div>
	        <?php echo form_open(null, array('id'=>'user_role_assign_form' ,'enctype'=>"multipart/form-data")); ?>
		        <div class='modal-body' style="height: auto">
                 	<div class="box-body">
            			
	             		<input type="hidden" id="urg_op_type" name="op_type" value="">
			            <input type="hidden" id="urg_hidden_id" name="urg_hidden_id">
			            <input type="hidden" name="csrf_case_form_token" id="csrf_urg_form_token" value="<?php echo generateToken('user_role_assign_form'); ?>">
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >User Role Group:</label>
									<select class="form-control" id="urg_role_group" name="urg_role_group">
										<option value="">Select User Role Group</option>
										<?php foreach($all_role_data as $rd): ?>
											<?php if(!in_array($rd['role_code'], ['ADMIN', 'SUPERADMIN'])): ?>
												<option value="<?= $rd['role_group_code'] ?>"><?= $rd['name'] ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label >User :</label>
									<select class="form-control" id="urg_user_code" name="urg_user_code">
										<option value="">Select User</option>
										<?php foreach($all_users_data as $ud): ?>
											<?php if(!in_array($ud['primary_role'], ['ADMIN', 'SUPERADMIN'])): ?>
												<option value="<?= $ud['user_code'] ?>"><?= $ud['user_display_name'].' ('.$ud['job_title'].')' ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
								
							</div>
							
							<div class="box-footer with-border">
					          	<div class="box-tools text-center">
					          		<button type="submit" class="btn bg-olive btn-flat" id="urg_btn_submit"><i class='fa fa-paper-plane'></i> Add</button>
					          	</div>
					        </div>
						</div>
					</div>
		        </div>
	    	</form>  
      	</div>
    </div>
</div>

<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>

<script> base_url = "<?php echo base_url(); ?>"; </script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/user_role_assign.js"></script>