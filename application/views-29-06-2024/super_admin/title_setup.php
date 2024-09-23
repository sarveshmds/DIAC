<div class="content-wrapper" id="root">
	<section class="content-header">
      	<h1>Title Setup</h1>
      	<ol class="breadcrumb">
      		<li><a href="#"><i class="fa fa-gears"></i> Setting</a></li>
        	<li class="active"><a href="#"><i class="fa fa-server"></i> Title Setup</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12">
        		<div class="box">
            		<div class="box-body">
						<div class="col-lg-12">
			              	<table id="dtbl_title_setup" class="table table-bordered table-hover">
			              		<input type="hidden" id="csrf_title_token" name="csrf_title_token" value="<?php echo generateToken('dtbl_title_setup'); ?>">
				                <thead>
					                <tr>
					                  	<th> # </th>
					                 	<th>Title Name</th>
					                 	<th>Title Desc.</th>
										<th>Image</th>
										<th>Status</th>
										<th>Action</th>
					                </tr>
				                </thead>
			              	</table>
            			</div>
            		</div>
            	</div>
          	</div>
          	<div class="modal fade" id="title_setup_modal" role="dialog" >
				<div class="modal-dialog">
			      	<div class="modal-content">
			      		<div class='modal-header'>
				          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
				          	<h4 class='modal-title' style='text-align: center;'><span id="span_title" >Add Title Setup</span></h4>
				        </div>
				        <?php echo form_open(null, array('id'=>'frm_title_setup' ,'enctype'=>"multipart/form-data")); ?>
					        <div class='modal-body' style="height: auto">
		                     	<div class="box-body">
			            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
				             		<input type="hidden" id="op_title_type" name="op_title_type" value="add_title_setup">
						            <input type="hidden" id="title_code" name="title_code">
						            <input type="hidden" name="csrf_title_setup_token" value="<?php echo generateToken('frm_title_setup'); ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label >Title Name :</label>
												<input type="text" class="form-control" name="txt_title_name" id="txt_title_name" placeholder="Title Name" autocomplete="off">
											</div>
											<div class="form-group col-md-6">
												<label >Title Image:</label>
												<input type="file" class="form-control filestyle" name="txt_title_img" id="txt_title_img"  autocomplete="off">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label >Title Description:</label>
												<textarea type="text" class="form-control" name="txt_desc" id="txt_desc" placeholder="Title Description"></textarea>
											</div>
											<div class="form-group col-md-6">
												<label >Status:</label>
												<select class="form-control" id="title_status" name="title_status">
								                 	<option value="">select</option>
								                  	<?php foreach ($this->config->item('status') as $key=>$status): ?>
														<option value="<?php echo $key; ?>"><?php echo $status; ?></option>
													<?php endforeach; ?>
								                </select>
											</div>
										</div>
										
										<div class="box-footer with-border">
								          	<div class="box-tools pull-right">
								          		<button type="submit" class="btn bg-olive btn-flat" id="btn_submit"><i class='fa fa-paper-plane'></i> Add</button>
								          		<button type="button" class="btn btn-default" id="button_reset" onclick="form_reset();"><i class="fa fa-refresh"></i> Reset</button>
								          	</div>
								        </div>
									</div>
								</div>
					        </div>
				    	<?php echo form_close(); ?>  
			      	</div>
			    </div>
			</div>
      	</div>
	</section>
</div>
<script> base_url = "<?php echo base_url(); ?>"; </script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/super_admin/title_setup.js"></script>