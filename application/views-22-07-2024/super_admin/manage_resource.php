<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Manage Resource</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-server"></i> Manage Resource</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="resourceTab" class="active"><a href="#tabResource" data-toggle='tab'>Resource </a></li>
						<li id="roleTab"><a href="#tabRole" data-toggle='tab'>Role</a></li>
						<li id="menuTab"><a href="#tabMenu" data-toggle='tab'>Menu</a></li>
					</ul>
		            <div class="tab-content no-padding">
					 	<div class="chart tab-pane active" id="tabResource" style="position: relative; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 5px;">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="hidCsrfToken" name="hidCsrfToken" value="<?php echo generateToken('dtblresourcemaster'); ?>">
							           				<table id="dtblresourcemaster" class="table table-condensed table-striped table-bordered  display nowrap">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th hidden="hidden">Code</th>
									                            <th>Link</th>
									                            <th>Name</th>
									                            <th hidden="hidden">ID</th>
									                            <th>Is Maintenance</th>
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
					    <!-- End Resource tab -->
					    <!-- Role Tab -->
					 	<div class="chart tab-pane" id="tabRole" style="position: relative; ">
					 		
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="hidroleCsrfToken" name="hidroleCsrfToken" value="<?php echo generateToken('dtblrolemaster'); ?>">
							           				<table id="dtblrolemaster" class="table table-condensed table-striped table-bordered  display nowrap">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th>Code</th>
									                            <th>Name</th>
									                            <th>Landing Page</th>
									                            <th hidden="hidden">Landing Page</th>
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
					    </div> <!-- End Role tab -->
					    <!-- Menu Tab -->
					 	<div class="chart tab-pane" id="tabMenu" style="position: relative; ">
					 		<?php echo form_open(null, array('name'=>'frmMenu', 'id'=>'frmMenu','enctype'=>"multipart/form-data")); ?>

						 		<div class="col-xs-12 col-sm-12 col-md-9" style="padding-top: 15px;">
						          	<div class="box box-primary">
							            <div class="box-body">
						             		<div class="row">
								             	<input type="hidden" id="op_type" name="op_type" value="add_menu">
						            			<label class="control-label col-sm-2">Role :</label>
						            			<div class="col-sm-3">
						            				<select class="form-control "  id="cmbMenuRole" name="cmbMenuRole" data-target = "#cmbMenuParent" data-type = "GET_LINK_URL" >
						            				</select>
						            			</div>
						            			<label class="control-label col-sm-2">Copy To :</label>
						            			<div class="col-sm-3">
						            				<select class="form-control " id="cmbMenuCopyTo" name="cmbMenuCopyTo"  >
						            				
						            				</select>
						            			</div>
						            			<div class="col-sm-2">
						            				<button class="btn  btn-primary  btn-circle tooltipTable" title="Copy Menu"  type="button" id="btnMenuCopy" name="btnMenuCopy"><i class="fa fa-copy"></i></button>
						            				<button class="btn  btn-success  btn-circle tooltipTable" title="Menu preview" type="button" id="btnMenuPreview" name="btnMenuPreview"><i class="fa fa-eye"></i></button>
						            			</div>
						            		</div>
						            		<div class="table-responsive" style="padding-top: 2%;">
							            		<input type="hidden" id="hidmenuCsrfToken" name="hidmenuCsrfToken" value="<?php echo generateToken('dtblMenu'); ?>">
							            		<table id="dtblMenu" class="table table-condensed table-striped table-bordered  display nowrap">
									                <thead>
								                        <tr>
								                            <th>#</th>
								                            <th>Role</th>
								                            <th>Link Text</th>
								                            <th>Resource Name</th>
								                            <th hidden>Parent id</th>
								                            <th>Parent</th>
								                            <th>Menu Sl No</th>
								                            <th>Has Child</th>
								                            <th>Is Last Child</th>
								                            <th>Access</th>
								                            <th>Action</th>
								                        </tr>
								                    </thead>
								               </table>
								            </div>
									    </div>
					  				</div>
						        </div>
						       	<div class="col-xs-12 col-sm-12 col-md-3 " style="padding-top: 15px;">
						        	<div class="box box-danger">
							            <div class="box-body">
							            	<h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
					                            <span id="spanMenu" >Add Menu</span>
					                        </h4>
					                        <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
					                        <div class="form-group">
												<label>Link Text</label>
												<input type="text" class="form-control" name="txtmenulinktext" id="txtmenulinktext" placeholder="Link Text"   data-bv-field="txtmenulinktext" autocomplete="off">
												<input type="hidden" value="" name="hidmenulinktext" id="hidmenulinktext">
												<input type="hidden" id="hidmenuInsertCsrfToken" name="hidmenuInsertCsrfToken" value="<?php echo generateToken('frmMenu'); ?>">
											</div>
											<div class="form-group">
												<label>Link Url</label>
												<select class="form-control" id="cmbMenuLinkURL" name="cmbMenuLinkURL">
												
					            				</select>
					            				<input type="hidden" value="" name="hidMenuId" id="hidMenuId">
											</div>
											<div class="form-group">
												<label>Parent</label>
												<select class="form-control" id="cmbMenuParent" name="cmbMenuParent" ></select>
					            				<input type="hidden" value="" name="hidParent" id="hidParent">
											</div>
											<div class="form-group">
												<label>Menu Sl No</label>
												<input type="text" class="form-control" name="txtMenuslno" id="txtMenuslno" placeholder="Menu Sl No"  data-bv-field="txtMenuslno" autocomplete="off">
												<input type="hidden"  name="hidParent" id="hidParent">
											</div>
											<div class="form-group">
												<label  class="col-sm-6">Has Child</label>
												<input type="checkbox" class="flat-red" name="txtHaschild" id="txtHaschild" value="Yes">
											</div>
											<div class="form-group">
												<label class="col-sm-6">Is Last Child</label>
												<input type="checkbox" class="flat-red" name="txtislastchild" id="txtislastchild" value="Yes" >
											</div>
											<div class="form-group">
												<label>Open in new Window</label>
												<select class="form-control" id="txtNewWindow" name="txtNewWindow" >
					            					<option value="_self">Parent Window</option>
					            					<option value="_blank">New Window</option>
					            				</select>
											</div>
											<div class="form-group">
												<label>Icon class</label>
												<input type="text" class="form-control" name="txtIconClass" id="txtIconClass" title="Icon Class" placeholder="Icon Class" autocomplete="off">
											</div>
											<div class="form-group">
												<label>Access Type</label>
												<select class="form-control" id="cmbMenuAccess" name="cmbMenuAccess" >
					            					<option value="">----SELECT----</option>
					            					<option value="R">Read</option>
					            					<option value="W">Write</option>
					            				</select>
					            				<input type="hidden" name="hidMenuAccess" id="hidMenuAccess">
											</div>
										</div>
										<div class="box-footer with-border">
											<div class="form-action box-tools pull-right">
												<div class="row">
													<button type="submit" class="btn bg-olive btn-flat" id="btnMenuSubmit" name="btnMenuSubmit"><i class="fa fa-paper-plane"></i> Add</button>
													<button type="button"  class="btn btn-default" id="btnMenuReset" name="btnMenuReset"><i class="fa fa-refresh"></i> Reset</button>
												</div>
											</div>
							            </div>
							        </div>
						        </div>
						   <?php echo form_close();?>
					    </div><!-- End Menu tab -->
					</div>
				</div>
	    	</div>
	    </div>
	</section>
	<!--Resourse Modal-->
	<div class="modal fade" id="resource_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_addresource" >Add Resource</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'frm_resource' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog_resorce" style="display: none; color: red; font-size: 9px;"></div>
		             		<input type="hidden" value="add_resource" name="op_type_resource" id="op_type_resource">
							<input type="hidden" value="" name="resource_code" id="resource_code">
							<input type="hidden"  name="csrf_token" value="<?php echo generateToken('frm_resource'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Link</label>
									<input type="text" class="form-control" name="txtresourcelink" id="txtresourcelink" placeholder="Resource Link" value="<?php echo set_value('txtresourcelink'); ?>"  data-bv-field="txtresourcelink" autocomplete="off">
									<input type="hidden" value="" name="hidtxtresourcelink" id="hidtxtresourcelink">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Name</label>
									<input type="text" class="form-control" name="txtresourceName" id="txtresourceName" placeholder="Resource Name" value="<?php echo set_value('txtresourceName'); ?>"  data-bv-field="txtresourceName" autocomplete="off">
									<input type="hidden" value="" name="hidtxtroleName" id="hidtxtroleName">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label>Is Maintenance</label>
									<select class="form-control" id="is_maintenance" name="is_maintenance">
					                 	<option value="">select</option>
					                  	<?php foreach ($this->config->item('is_maintenance') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
					                </select>
								</div>
								<div class="form-group col-md-6">
									<label>Status</label>
									<select class="form-control" id="resource_status" name="resource_status">
					                 	<option value="">select</option>
					                  	<?php foreach ($this->config->item('status') as $key=>$status): ?>
											<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
										<?php endforeach; ?>
					                </select>
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="resource_btn"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default" id="resource_reset"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
				        	</div>
						</div>
			        </div>
		    	 <?php echo form_close(); ?>
	      	</div>
	    </div>
	</div>
	<!--Role Modal-->
	<div class="modal fade" id="role_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_addrole">  Add Role</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'frm_role' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
                    		<input type="hidden" value="add_role" name="op_type_role" id="op_type_role">
                    		<input type="hidden"  name="csrf_role_token" value="<?php echo generateToken('frm_role'); ?>">
							<div class="form-group">
								<label>Code :</label>
								<input type="text" class="form-control" name="txtrolecode" id="txtrolecode" placeholder="Role Code" value=""  onblur="this.value=this.value.toUpperCase()" data-bv-field="txtrolecode" autocomplete="off">
								<input type="hidden" value="" name="hidtxtrolecode" id="hidtxtrolecode">
							</div>
							<div class="form-group">
								<label>Name :</label>
								<input type="text" class="form-control" name="txtroleName" id="txtroleName" placeholder="Role Name" value=""  data-bv-field="txtroleName" autocomplete="off">
								<input type="hidden" value="" name="hidtxtroleName" id="hidtxtroleName">
							</div>
							<div class="form-group">
								<label> Landing Page :</label>
								<select class="form-control " id="txtLandingPage" name="txtLandingPage" ></select>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="role_btn"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default" id="role_reset" ><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
						    </div>
						</div>
			        </div>
		    	</form>  
	      	</div>
	    </div>
	</div>
	<!--Role Modal-->
	
</div>
<script> var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/manage_resource.js"></script>
