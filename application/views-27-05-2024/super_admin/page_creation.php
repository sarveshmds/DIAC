<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Page Creation</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-server"></i>Page Creation</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs" role="tablist" id="myTab">
						<li id="ControllerTab" class="active"><a href="#tabController" data-toggle='tab'>Controller</a></li>
						<li id="ModelTab"><a href="#tabModel" data-toggle='tab'>Model</a></li>
						<li id="ViewTab"><a href="#tabView" data-toggle='tab'>View</a></li>
					</ul>
		            <div class="tab-content no-padding">
					 	<div class="chart tab-pane active" id="tabController" style="position: relative; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12" style="padding-top: 5px;">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="hidControllerCsrfToken" name="hidControllerCsrfToken" value="<?php echo generateToken('dtblControllerMaster'); ?>">
							           				<table id="dtblControllerMaster" class="table table-bordered table-hover">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th>Controller Code</th>
									                            <th>Controller Name</th>
									                            <th>Time</th>
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
					 	<div class="chart tab-pane" id="tabModel" style="position: relative; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="hidmodelCsrfToken" name="hidmodelCsrfToken" value="<?php echo generateToken('dtblmodelmaster'); ?>">
							           				<table id="dtblmodelmaster" class="table table-bordered table-hover">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th>Model Code</th>
									                            <th>Model Name</th>
									                            <th>Time</th>
									                        </tr>
									                    </thead>
										            </table>
												</div>
								           	</div>
							          	</div>
							        </div>
							    </div>
							</div>
					    </div> <!-- End Model tab -->
					    <!-- View Tab -->
					    <div class="chart tab-pane" id="tabView" style="position: relative; ">
					 		<div class="col-xs-12 col-sm-12 col-md-12">
						 		<div class="row">
							        <div class="col-xs-12 col-sm-12 col-md-12">
							          	<div class="box box-primary">
								            <div class="box-body">
								            	<div class="col-lg-12 col-xs-12">
								            		<input type="hidden" id="hidViewCsrfToken" name="hidViewCsrfToken" value="<?php echo generateToken('dtblviewmaster'); ?>">
							           				<table id="dtblviewmaster" class="table table-bordered table-hover">
										                <thead>
									                        <tr>
									                            <th>Sl No</th>
									                            <th>View Code</th>
									                            <th>View Name</th>
									                            <th>View Path</th>
									                            <th>Time</th>
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
					 	<!-- End View tab -->
					</div>
				</div>
	    	</div>
	    </div>
	</section>
	<!--Controller Modal-->
	<div class="modal fade" id="controller_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_controller" >Add Controller</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'form_controller' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog_controller" style="display: none; color: red; font-size: 9px;"></div>
		             		<input type="hidden" value="add_controller" name="op_controller" id="op_controller">
							<input type="hidden"  name="csrf_controller_token" value="<?php echo generateToken('form_controller'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Controller Code</label>
									<input type="text" class="form-control" name="txt_controller_code" id="txt_controller_code" placeholder="Eg:C0001"  autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Controller Name </label>
									<input type="text" class="form-control" name="txt_controller_name" id="txt_controller_name" placeholder="Controller Name" autocomplete="off">
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="controller_btn"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default"  onclick="Controllerreset();"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
				        	</div>
						</div>
			        </div>
		    	 <?php echo form_close(); ?>
	      	</div>
	    </div>
	</div>
	<!--Model Modal-->
	<div class="modal fade" id="model_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_addmodel">Add Model</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'form_model' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlogmodel" style="display: none; color: red; font-size: 9px;"></div>
                    		<input type="hidden" value="add_model" name="op_type_model" id="op_type_model">
                    		<input type="hidden"  name="csrf_model_token" value="<?php echo generateToken('form_model'); ?>">
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Model Code :</label>
									<input type="text" class="form-control" name="txt_model_code" id="txt_model_code" placeholder="Eg:M0001" value=""  autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>Model Name :</label>
									<input type="text" class="form-control" name="txtModelName" id="txtModelName" placeholder="Eg:Test_model" value=""  autocomplete="off">
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="model_btn"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default"  onclick="ModelReset();"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
						    </div>
						</div>
			        </div>
		    	 <?php echo form_close(); ?>
	      	</div>
	    </div>
	</div>
	<!--Model Modal End-->
	<!--View Modal-->
	<div class="modal fade" id="view_modal" role="dialog" >
		<div class="modal-dialog">
	      	<div class="modal-content">
	      		<div class='modal-header'>
		          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
		          	<h4 class='modal-title' style='text-align: center;'><span id="span_view" >Add View</span></h4>
		        </div>
		        <?php echo form_open(null, array('id'=>'form_view' ,'enctype'=>"multipart/form-data")); ?>
			        <div class='modal-body' style="height: auto">
                     	<div class="box-body">
	            			<div id="errorlog_view" style="display: none; color: red; font-size: 9px;"></div>
		             		<input type="hidden" value="add_view" name="op_view" id="op_view">
							<input type="hidden"  name="csrf_view_token" value="<?php echo generateToken('form_view'); ?>">
							<div class="col-md-12">
		            			<div class="form-group col-md-6">
									<label >View Folder</label>
									<select class="form-control" id="cmb_view_path" name="cmb_view_path">
										<option value="">select</option>
										<?php
											if(!empty($get_view_folder_details)):
												foreach($get_view_folder_details as $view_folder):
										?>
											<option value="<?php echo $view_folder['view_path']?>"><?php echo $view_folder['view_path']?></option>
										<?php
												endforeach;
											endif;
										?>
									</select> 
								</div>
								<label style="padding-top: 25px;">
									<a href="#" class="btn btn-warning btn tooltipTable" id="new_view_folder_btn"  title="Add View Folder" >
          							<span class="glyphicon glyphicon-plus"></span>&nbsp;New</a>
								</label>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>View Code</label>
									<input type="text" class="form-control" name="txt_view_code" id="txt_view_code" placeholder="Eg:V0001"  autocomplete="off">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group col-md-12">
									<label>View Name </label>
									<input type="text" class="form-control" name="txt_view_name" id="txt_view_name" placeholder="View Name" autocomplete="off">
								</div>
							</div>
							<div class="box-footer with-border">
					          	<div class="box-tools pull-right">
					          		<button type="submit" class="btn bg-olive btn-flat" id="view_btn"><i class='fa fa-paper-plane'></i> Add</button>
					          		<button type="button" class="btn btn-default"  onclick="ViewReset();"><i class="fa fa-refresh"></i> Reset</button>
					          	</div>
				        	</div>
						</div>
			        </div>
		    	 <?php echo form_close(); ?>
	      	</div>
	    </div>
	</div>
	<div class="modal fade" id="add_view_folder" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New View Path</h4>
				</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" class="form-control" name="new_view_path" id="new_view_path" placeholder="New View Path" autocomplete="off" autofocus="" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="add_new_view_path" class="btn btn-primary" >Add</button>
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					</div>
			</div>
		</div>
	</div>
	
</div>
<script> var base_url = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/page_creation.js"></script>
