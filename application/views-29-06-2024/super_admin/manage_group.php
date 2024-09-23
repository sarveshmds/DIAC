<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Manage Group</h1>
      	<ol class="breadcrumb">
      		<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-server"></i> Manage Group</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<!-- Tabs within a box -->
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="GroupTab" class="active"><a href="#tabGroup" data-toggle='tab'>Group Setup</a></li>
						<li id="RoleGroupTab"><a href="#tabRoleGroup" data-toggle='tab'>Role-Group Mapping</a></li>
						<li id="UserRolegroupTab"><a href="#tabUserRolegroup" data-toggle='tab'>User-Role-group Mapping</a></li>
					</ul>
		            <!-- /.box-header -->
		            <div class="tab-content no-padding">
					 	<div class="chart tab-pane active" id="tabGroup" style="position: relative;min-height:770px; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 15px;">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="csrf_group_setup_token" name="csrf_group_setup_token" value="<?php echo generateToken('dtblgroupmaster'); ?>">
							           				<table id="dtblgroupmaster" class="table table-condensed table-striped table-bordered  display nowrap">
										                <thead>
									                        <tr>
									                            <th>#</th>
									                            <th hidden>Group Code</th>
									                            <th>Group Name</th>
									                           	<th>Table Name</th>
									                           	<th hidden>Table Code</th>
									                           	<th >Operation Table</th>
									                           	<th >Operation Column</th>
									                           	<th >Exicution Column</th>
									                           	<th>Action</th>
									                        </tr>
									                    </thead>
										            </table>
												</div>
								           	</div>
							          	</div>
							        </div>
							    </div>
							    <!--MODAL TO SHOW MAP DATA START-->
								<div class="modal fade" id="mapdata" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop = "static">
							     	<div class="modal-dialog">
							     	<div class="modal-content">
						            	<div class="modal-header">
							             	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							              	 &times;
							             	 </button>
							             	 <h4 class="modal-title" id="myModalLabel">Mapping Data</h4>
						            	</div>
						            	<div class="modal-body">
							             	<div class="row">
							                   	<div class="col-md-12 col-sm-12 col-xs-12">
									            	<table id="dtblgroupmapping" class="table table-condensed table-striped table-bordered  display nowrap"  >
										                <input type="hidden" id="csrf_group_mapping_token" name="csrf_group_mapping_token" value="<?php echo generateToken('dtblgroupmapping'); ?>">
										                <thead>
									                        <tr>
									                            <th>#</th>
									                            <th>Group Name</th>
									                           	<th>value</th>
									                           	<th hidden="true">code</th>
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
								<!--MODAL TO SHOW MAP DATA END-->
							</div>
					    </div>
					    <!-- End Group tab -->
					    
					     <!-- Role Group Tab -->
					 	<div class="chart tab-pane" id="tabRoleGroup" style="position: relative; min-height:550px; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 15px;">
						 		<div class="row">
							        <div class="col-xs-12">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
							           				<table id="dtblrolegroupmapping" class="table table-condensed table-striped table-bordered  display nowrap">
							           				<input type="hidden" id="csrf_role_group_mapping_token" name="csrf_role_group_mapping_token" value="<?php echo generateToken('dtblrolegroupmapping'); ?>">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th>Role Group Name</th>
									                            <th>Role Name</th>
									                            <th>Group Name</th>
									                           	<th hidden="hidden">Action</th>
									                           	<th hidden="hidden">Action</th>
									                           	<th hidden="hidden">Action</th>
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
					    </div> <!-- End Role Group tab -->
					    <!-- User Rolegroup Tab -->
					 	<div class="chart tab-pane" id="tabUserRolegroup" style="position: relative; min-height:550px ">
					 		<?php echo form_open(null, array('name'=>'frm_userrolegroup', 'id'=>'frm_userrolegroup','enctype'=>"multipart/form-data")); ?>
						 		<div class="col-xs-12 col-sm-12 col-md-9" style="padding-top: 15px;">
						          	<div class="box box-primary">
							            <div class="box-body">
						             		<div class="row">
								             	<input type="hidden" id="op_type_user_role_group" name="op_type_user_role_group" value="add_user_role_group">
						            			<label class="control-label col-sm-3">User Role-Group Name:</label>
						            			<div class="col-sm-3">
						            				<select class="form-control" id="cmbrolegroup" name="cmbrolegroup">
						            				</select>
						            			</div>
						            		</div>
						            		<div class="table-responsive" style="padding-top: 2%;">
							            		<table id="dtblUser_Role_group_mapping" class="table table-condensed table-striped table-bordered  display nowrap">
							            			<input type="hidden" id="csrf_userrole_group_mapping_token" name="csrf_userrole_group_mapping_token" value="<?php echo generateToken('dtblUser_Role_group_mapping'); ?>">
									                <thead>
								                        <tr>
								                            <th>#</th>
								                            <th hidden="hidden">Access</th>
								                            <th hidden="hidden">Access</th>
								                            <th>User Name</th>
								                            <th>Role-Group Name</th>
								                            <th hidden="hidden">User Role-Group Code</th>
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
					                            <span id="spanuser_rolegroup" >Add User Role-Group </span>
					                        </h4>
					                        <div id="errorloguser_rolegroup" style="display: none; color: red; font-size: 9px;"></div>
					                        <input type="hidden" value="" name="txtrole_group" id="txtrole_group">
					                        <input type="hidden" name="csrf_userrolegroup_token" value="<?php echo generateToken('frm_userrolegroup'); ?>">
					                        <div class="form-group">
												<label>Role-Group Name</label>
												<input type="text" class="form-control" name="txtrole_name" id="txtrole_name" placeholder="Role-Group Name"   readonly="true" data-bv-field="txtrole_group" autocomplete="off">
											</div>
											<div class="form-group">
												<label>User Name </label><br>
												<select class="form-control multiselect-ui" id="cmbuser_name" name="cmbuser_name[]" multiple="multiple">
					            					
							            				<?php if (isset($all_user_data) && !empty($all_user_data)):
		                                    				foreach ($all_user_data as $row):
		                                    			?> 
	                                        			<option data-info='' value="<?php echo $row['user_code']; ?>"><?php echo $row['user_name'] ?></option>
					                                <?php
					                                    endforeach;
					                                endif;
					                                ?>
					            				</select>
					            				<input type="hidden" value="" name="hidMenuId" id="hidMenuId">
											</div>
											
										</div>
										<div class="box-footer with-border">
											<div class="form-action box-tools pull-right">
												<div class="row">
													<button type="submit" class="btn bg-olive btn-flat" id="btnuser_rolegroup" name="btnuser_rolegroup"><i class="fa fa-paper-plane"></i> Add</button>
													<button type="button"  class="btn btn-default" id="btnMenuReset" name="btnMenuReset"><i class="fa fa-refresh"></i> Reset</button>
												</div>
											</div>
							            </div>
							        </div>
						        </div>
						   <?php echo form_close();?>
					    </div><!-- End User Rolegroup tab -->
					</div>
				</div>
	    	</div>
	    </div>
	</section>
	<!--Resourse Modal-->
	<div class="modal fade" id="group_setup_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="spangroup">  Add Group</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'frm_group' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body row">
	            			<div id="errorlog_group" style="display: none; color: red; font-size: 9px;"></div>
                			<input type="hidden" value="add_group" name="op_type_group" id="op_type_group">
                			<input type="hidden" value="" name="hidgroupcode" id="hidgroupcode">
							<input type="hidden" name="csrf_add_group_token" value="<?php echo generateToken('frm_group'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Group Name :</label>
									<input type="text" class="form-control" name="txtGroupName" id="txtGroupName" placeholder="Group Name" value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Table Name :</label>
									<select class="form-control" id="cmbtable" name="cmbtable" >
										<option value="">Select Table</option>
										<?php foreach($table_data as $row){ ?>
											<option value="<?php echo $row['table_code']?>"><?php echo $row['table_name']?></option>
										<?php	} ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Operational Table :</label>
									<input type="text" class="form-control" name="txtoperTable" id="txtoperTable" placeholder="Operational Table" value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Operational Column :</label>
									<input type="text" class="form-control" name="txtoperColumn" id="txtoperColumn" placeholder="Operational Column" value="" autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-6">
									<label >Exicution Column :</label>
									<input type="text" class="form-control" name="txtExColumn" id="txtExColumn" placeholder="Exicution Column" value="" autocomplete="off">
								</div>
								<div class="form-group col-md-6">
									<label >Value :</label>
									<select class="form-control" id="cmbtablevalue" name="cmbtablevalue[]" multiple="multiple" autocomplete="off" style="width: 60%"></select>
								</div>
							</div>
			            </div>
			            <div class="box-footer with-border">
				          	<div class="box-tools pull-right">
				          		<button type="submit" class="btn bg-olive btn-flat" id="btn_groupsubmit"><i class='fa fa-paper-plane'></i> Add</button>
				          		<button type="button" class="btn btn-default" id="btn_groupreset" name="btn_groupreset"><i class="fa fa-refresh"></i> Reset</button>
				          	</div>
			        	</div>
					</div>
		    	</form>  
	      	</div>
	    </div>
	</div>
	<!--Role Group Mapping-->
	<div class="modal fade" id="role_group_mapping_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_addrolegroup">  Add Role-Group </span></h4>
		        </div>
		       <?php echo form_open(null, array('id'=>'frm_role_group' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlogrolegroup" style="display: none; color: red; font-size: 9px;"></div>
		                    <input type="hidden" value="add_role_group" name="op_type_role_group" id="op_type_role_group">
		                    <input type="hidden" value="" name="hidtxtRole_group_code" id="hidtxtRole_group_code">
		                    <input type="hidden" value="" name="hidtxtRole_code" id="hidtxtRole_code">
		                    <input type="hidden" value="" name="hidtxtGroup_code" id="hidtxtGroup_code">
		                    <input type="hidden" name="csrf_role_group_token" value="<?php echo generateToken('frm_role_group'); ?>">
							<div class="form-group">
								<div class="form-group">
									<label> Role Group Name :</label>
									<input type="text" class="form-control" name="txtRoleGroup" id="txtRoleGroup" placeholder="Role Group Name" value="" data-bv-field="txtRoleGroup" autocomplete="off">
									<input type="hidden" value="" name="hidtxtRoleGroup" id="hidtxtRoleGroup">
								</div>
								<label>Role Name :</label>
								<select class="form-control " id="cmbrolecode" name="cmbrolecode" >
		        					<option value="">----SELECT----</option>
		        					<?php  foreach( $all_role_data as $row ) { ?>
		        					<option value="<?= $row['role_code']?>"><?= $row['role_name']?></option>
		        					<?php } ?>
		        				</select>
		        				<input type="hidden" value="" name="hidtxtrolecode" id="hidtxtrolecode">
							</div>
							<div class="form-group">
								<label> Group Name :</label>
								<select class="form-control " id="cmbgroupcode" name="cmbgroupcode" >
		        					<option value="">----SELECT----</option>
		            			</select>
		            			<input type="hidden" value="" name="hidtxtgroupcode" id="hidtxtgroupcode">
							</div>
			            </div>
			            <div class="box-footer with-border">
				          	<div class="box-tools pull-right">
				          		<button type="submit" class="btn bg-olive btn-flat" id="role_group_btn"><i class='fa fa-paper-plane'></i> Add</button>
				          		<button type="button" class="btn btn-default" id="role_group_reset" ><i class="fa fa-refresh"></i> Reset</button>
				          	</div>
					    </div>
					</div>
		    	<?php echo form_close();?>  
	      	</div>
	    </div>
	</div>
</div>
<script> var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript"  src="<?php echo base_url(); ?>public/custom/js/super_admin/manage_group.js?v=3.2.1"></script>