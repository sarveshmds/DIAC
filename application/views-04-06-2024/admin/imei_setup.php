	<div class="content-wrapper">
		<section class="content-header">
			<h1>IMEI Setup</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-bars"></i> Master</a></li>
				<li class="active">Admin Department</li>
			</ol>
		</section>
		<section class="content">
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12">
        			<div class="box">
	            		<div class="box-body">
							<div class="col-lg-12">
								<input type="hidden"  id="csrf_imeiToken" value="<?php echo generateToken('dataTableIMEI')?>"/>
				              	<table id="dataTableIMEI" class="table table-bordered table-hover">
				                    <thead>
				                        <tr>
											<th>Sl No</th>
											<th>User Name</th>
											<th>Role</th>
											<th>IMEI One</th>
											<th>IMEI Two</th>
											<th>Status</th>
				                            <th>Action</th>
				                        </tr>
				                    </thead>
				                </table>
	            			</div>
	            		</div>
	            	</div>
	          	</div>
		    </div>
		</section>
		<!---Add IMEI Setup--->
		<div class="modal fade" id="add_imeiSetup_modal" role="dialog" >
			<div class="modal-dialog">
		      	<div class="modal-content">
		      		<div class='modal-header'>
			          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
			          	<h4 class='modal-title' style='text-align: center;'><span id="spanIMEI" >Add IMEI Details</span></h4>
			        </div>
         			<?php echo form_open(null, array('class'=>'wfst','id'=>'form_imei_setup' ,'enctype'=>"multipart/form-data")); ?>
         				<div class='modal-body' style="height: auto">
         					<div class="box-body">
		            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
		            			<input type="hidden" id="op_type" name="op_type" value="ADD_IMEI_SETUP">
		            			<input type="hidden" id="hidden_userid" name="hidden_userid" >
		            			<input type="hidden" name="csrf_imei_setup" value="<?php echo generateToken('form_imei_setup'); ?>">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group col-md-6 required">
											<label  class="control-label">Role:</label>
											<select class="form-control" name="cmb_role" id="cmb_role">
												<option value="">Select</option>
												<?php if(isset($role_code)): foreach($role_code as $role):?>
													<option value="<?= $role['role_code'];?>"><?= $role['role_name'];?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
										<div class="form-group col-md-6 required">
											<label class="control-label">User Name:</label>
											<select class="form-control" name="cmb_username" id="cmb_username">
												<option value="">Select</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group col-md-6 required">
											<label  class="control-label">IMEI 1:</label>
											<input type="text" class="form-control" name="txt_imeiOne" id="txt_imeiOne"  value="" required="" autocomplete="off"  maxlength="15">
										</div>
										<div class="form-group col-md-6">
											<label >IMEI 2:</label>
											<input type="text" class="form-control" name="txt_imeiTwo" id="txt_imeiTwo"  value="" required="" autocomplete="off"  maxlength="15">
										</div>
									</div>
									<div class="col-md-12" style="display: none" id="rec_status">
										<div class="form-group col-md-6 required">
											<label class="control-label">Status:</label>
											<select class="form-control" name="cmb_recStatus" id="cmb_recStatus">
												<option value="">Select</option>
												<?php  foreach($this->config->item('status') as $key => $status):?>
													<option value="<?= $key;?>"><?= $status;?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="box-footer with-border">
							          	<div class="box-tools pull-right">
							          		<button type="submit" class="btn btn-success" id="btn_submit_imei"><i class='fa fa-paper-plane'></i> Submit</button>
							          		<button type="reset" class="btn btn-default" onclick="form_reset()"><i class="fa fa-refresh"></i> Reset</button>
							          	</div>
							        </div>
							    </div>
							</div>
						</div>
					<?php echo form_close();?>
				</div>
        	</div>
        </div>
	</div>
	<script> base_url = "<?php echo base_url(); ?>"; </script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/imei_setup.js"></script>
