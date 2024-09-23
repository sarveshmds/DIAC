	<div class="content-wrapper" >
		<section class="content-header">
		      	<h1>SMS Setup</h1>
		      	<ol class="breadcrumb">
		      		<li><a href="#"><i class="fa fa-wrench"></i> Configuration</a></li>
		        	<li class="active"><a href="#"><i class="fa fa-mobile fa-lg"></i> SMS Setup</a></li>
		        </ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="nav-tabs-custom">
						<!-- Tabs within a box -->
						<ul class="nav nav-tabs" role="tablist" id="myTab">
							<li id="GroupTab" class="active"><a href="#tabProviderSetup" data-toggle='tab'>Provider Setup</a></li>
							<li id="RoleGroupTab"><a href="#tabEmailSetup" data-toggle='tab'>SMS Setup</a></li>
						</ul>
			            <!-- /.box-header -->
			            <div class="tab-content no-padding">
							<!-- Group Tab -->
						 		<div class="chart tab-pane active" id="tabProviderSetup" style="position: relative;min-height:770px; ">
					 				<div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 15px;">
						 				<div class="row">
							        		<div class="col-xs-12 col-sm-12 col-md-12">
							          			<div class="box box-primary">
								            		<div class="box-body">
								            			<div class="col-lg-12 col-xs-12">
										            		<input type="hidden" id="hidsmsCsrfToken" name="hidsmsCsrfToken" value="<?php echo generateToken('dtblsmsmaster'); ?>">
									           				<table id="dtblsmsmaster" class="table table-condensed table-striped table-bordered  display nowrap" >
												                <thead>
											                        <tr>
											                            <th>#</th>
											                            <th hidden>provider_id</th>
											                            <th>Provider</th>
											                            <th>SMS Url</th>
											                           	<th>User Name</th>
											                           	<th>Password</th>
											                           	<th>Sender</th>
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
									</div>
					    		</div>
						    <!-- End Group tab -->
						    <!-- Role Group Tab -->
					 		<div class="chart tab-pane" id="tabEmailSetup" style="position: relative; min-height:550px; ">
						 		<div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 15px;">
							 		<div class="row">
								        <div class="col-xs-12">
								          	<div class="box box-primary">
									            <div class="box-body">
									            	<div class="col-lg-12 col-xs-12 col-md-12">
									            		<input type="hidden" id="hidsmsSetupCsrfToken" name="hidsmsSetupCsrfToken" value="<?php echo generateToken('dtblsmssetup'); ?>">
								           				<table id="dtblsmssetup" class="table table-condensed table-striped table-bordered  display nowrap">
											                <thead>
										                        <tr>
										                            <th>Sl No</th>
										                            <th hidden>sms_setup_id</th>
										                            <th>SMS Type</th>
										                            <th>Subject</th>
										                            <th>Content</th>
										                           	<th>Provider</th>
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
								</div>
				    		</div>
						</div>
					</div>
	    		</div>
	    	</div>
		</section>
		
	</div
	<!--provider Modal-->
	<div class="modal fade" id="modal_sms_provider_setup" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="spanaddprovider">  Add Records</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'frm_smsprovider' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto" id="manage_group">
                     	<div class="box-body row">
	            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
			                <input type="hidden" id="op_type" name="op_type" value="add_smsprovider">
			                <input type="hidden" value="" name="hidesms_provider_id" id="hidesms_provider_id">
			                <input type="hidden"  name="csrf_smsprovider_token" value="<?php echo generateToken('frm_smsprovider'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Provider :</label>
									<input type="text" class="form-control" name="txtProviderName" id="txtProviderName" placeholder="Eg:Web SMS,SandeshLive.." value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >SMS Url:</label>
									<textarea class="form-control" name="txtSMSUrl" id="txtSMSUrl" placeholder="HTTP API Url" value="" autocomplete="off"></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >User Name:</label>
									<input type="text" class="form-control" name="txt_UserName" id="txt_UserName" placeholder="User Name" value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Password:</label>
									<input type="password" class="form-control" name="txt_password" id="txt_password" placeholder="SMS Password" value="" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Sender:</label>
									<input type="text" class="form-control" name="txt_Sender" id="txt_Sender" placeholder="Sender Name" value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Status:</label>
									<select class="form-control" id="cmbSMSProviderstatus" name="cmbSMSProviderstatus" >
										<option value="">Select</option>
										<?php foreach ($this->config->item('status') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="box-footer with-border">
				          	<div class="box-tools pull-right">
				          		<button type="submit" class="btn bg-olive btn-flat" id="btn_sms"><i class='fa fa-paper-plane'></i> Add</button>
				          		<button type="button" class="btn btn-default" id="btn_provider_reset" name="btn_provider_reset"><i class="fa fa-refresh"></i> Reset</button>
				          	</div>
			        	</div>
	      			</div>
	      		<?php echo form_close();?>  
	   		 </div>
		</div>
	</div>
	<!--Sms Setup Modal-->
	<div class="modal fade" id="modal_sms_setup" role="dialog" >
		<div class="modal-dialog">
		    <div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="spanaddsms">  Add Records</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'frm_sms_setup' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto" id="manage_group">
                     	<div class="box-body row">
	            			<div id="errorlogsms" style="display: none; color: red; font-size: 9px;"></div>
			                <input type="hidden" id="op_type_sms" name="op_type_sms" value="add_smssetup">
			                <input type="hidden" value="" name="sms_setup_id" id="sms_setup_id">
			                <input type="hidden"  name="csrf_smssetup_token" value="<?php echo generateToken('frm_sms_setup'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >SMS Type:</label>
									<input type="text" class="form-control" name="txtSMSType" id="txtSMSType" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Subject:</label>
									<input type="text" class="form-control" name="txtSubject" id="txtSubject" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Content:</label>
									<textarea class="form-control" name="txtSMSContent" id="txtSMSContent" autocomplete="off"></textarea>
								</div>
								<div class="form-group col-md-6">
									<label >Provider:</label>
									<input type="text" class="form-control" name="txtSMSProvider" id="txtSMSProvider"  autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Status:</label>
									<select class="form-control" id="cmbSMSstatus" name="cmbSMSstatus" >
										<option value="">Select</option>
										<?php foreach ($this->config->item('status') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="box-footer with-border">
				          	<div class="box-tools pull-right">
				          		<button type="submit" class="btn bg-olive btn-flat" id="btn_sms_setup"><i class='fa fa-paper-plane'></i> Add</button>
				          		<button type="reset" class="btn btn-default" id="btn_sms_setup_reset" ><i class="fa fa-refresh"></i> Reset</button>
				          	</div>
					    </div>
					</div>
			    <?php echo form_close();?>  
		      		
		    </div>
		</div>
	</div>
	
<script> var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/sms_setup.js"></script>
	