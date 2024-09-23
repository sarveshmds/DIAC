<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Email Setup</h1>
      	<ol class="breadcrumb">
      		<li><a href="#"><i class="fa fa-wrench"></i> Configuration</a></li>
        	<li class="active"><a href="#"><i class="fa fa-envelope"></i> Email Setup</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<!-- Tabs within a box -->
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="GroupTab" class="active"><a href="#tabProviderSetup" data-toggle='tab'>Provider Setup</a></li>
						<li id="RoleGroupTab"><a href="#tabEmailSetup" data-toggle='tab'>Email Setup</a></li>
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
								            		<input type="hidden" id="hidemailCsrfToken" name="hidemailCsrfToken" value="<?php echo generateToken('dtblprovidermaster'); ?>">
							           				<table id="dtblprovidermaster" class="table table-condensed table-striped table-bordered  display nowrap">
										                <thead>
									                        <tr>
									                            <th>#</th>
									                            <th hidden>provider_id</th>
									                            <th>Provider</th>
									                            <th>Host</th>
									                           	<th>Port</th>
									                           	<th>Mail ID</th>
									                           	<th>Password</th>
									                           	<th>SMTP Auth</th>
									                           	<th>SMTP Secur</th>
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
								            	<div class="col-lg-12 col-xs-12">
							           				<table id="dtblemailsetup" class="table table-condensed table-striped table-bordered  display nowrap">
							           					<input type="hidden" id="hidemailsetupCsrfToken" name="hidemailsetupCsrfToken" value="<?php echo generateToken('dtblemailsetup'); ?>">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th hidden>Email_setup_id</th>
									                            <th>Mail Type</th>
									                            <th>Subject</th>
									                            <th>Content</th>
									                           	<th>Provider</th>
									                           	<th>Institute_code</th>
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
	<!--provider Modal-->
	<div class="modal fade" id="provider_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="spanaddprovider">  Add Provider</span></h4>
		        </div>
		       <?php echo form_open(null, array('id'=>'frm_provider' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                    		<input type="hidden" id="op_type" name="op_type" value="add_provider">
							<input type="hidden" value="" name="hideemail_provider_id" id="hideemail_provider_id">
                    		<input type="hidden"  name="csrf_provider_token" value="<?php echo generateToken('frm_provider'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Provider :</label>
									<input type="text" class="form-control" name="txtProvidername" id="txtProvidername" placeholder="Eg:Gmail,Yahoo.."  autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Host :</label>
									<input type="text" class="form-control" name="txtHostName" id="txtHostName" placeholder="Eg:smtp.gmail.com" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Port :</label>
									<input type="text" class="form-control" name="txtPort" id="txtPort" placeholder="Eg:465" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Email Id:</label>
									<input type="text" class="form-control" name="txt_Email" id="txt_Email" placeholder="Eg:xxxxxxx@xxx.com"  autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Password:</label>
									<input type="password" class="form-control" name="txt_password" id="txt_password" placeholder="Email Password" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >SMPT Auth :</label>
									<select class="form-control" id="cmb_smptauth" name="cmb_smptauth" >
										<option value="">Select</option>
										<?php foreach ($this->config->item('smtp_auth') as $key=>$smtp_auth): ?>
											<option value="<?php echo $key; ?>"><?php echo $smtp_auth; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >SMTP Secure :</label>
									<select class="form-control" id="cmb_smptsecure" name="cmb_smptsecure" >
										<option value="">Select</option>
										<option value="ssl">SSL</option>
										<option value="tsl">TSL</option>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label >Status:</label>
									<select class="form-control" id="cmbStatus" name="cmbStatus" >
										<option value="">Select</option>
										<?php foreach ($this->config->item('status') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="btn_provider"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default" id="btn_providerreset" name="btn_providerreset"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
				        	</div>
						</div>
			        </div>
		    	</form>  
	      	</div>
	    </div>
	</div>
	<!--Email Modal-->
	<div class="modal fade" id="email_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="spanaddemail">  Add Role</span></h4>
		        </div>
		       <?php echo form_open(null, array('id'=>'frm_email' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                    		<input type="hidden" id="op_type_email" name="op_type_email" value="add_email">
							<input type="hidden" value="" name="hidemail_setup_id" id="hidemail_setup_id">
                    		<input type="hidden"  name="csrf_email_token" value="<?php echo generateToken('frm_email'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Mail Type:</label>
									<input type="text" class="form-control" name="txtMailType" id="txtMailType" placeholder="Eg:Gmail,Yahoo.." value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Subject:</label>
									<input type="text" class="form-control" name="txtSubject" id="txtSubject" placeholder="Eg:Gmail,Yahoo.." value="" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Content:</label>
									<textarea class="form-control" name="txtContent" id="txtContent" placeholder="Eg:Gmail,Yahoo.." value="" autocomplete="off"></textarea>
								</div>
								<div class="form-group col-md-6">
									<label >Provider:</label>
									<input type="text" class="form-control" name="txtProvider" id="txtProvider" placeholder="Eg:Gmail,Yahoo.." value="" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Status:</label>
									<select class="form-control" id="cmbEmailStatus" name="cmbEmailStatus" >
										<option value="">Select</option>
										<?php foreach ($this->config->item('status') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="btn_submit_email"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default" id="btn_emailreset" name="btn_emailreset"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
				        	</div>
						</div>
			        </div>
		    	</form>  
	      	</div>
	    </div>
	</div>
</div>
<script> var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/email_setup.js"></script>
	