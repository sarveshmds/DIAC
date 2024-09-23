
<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Gencode Setup</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-cog"></i> Master Setup</a></li>
        	<li class="active"><a href="#"><i class="fa fa-modx"></i>Gencode Setup</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12">
        		<div class="box">
            		<div class="box-body">
						<div class="col-lg-12">
			              	<table id="gen_code_table" class="table table-bordered table-hover">
			              		<input type="hidden" id="csrf_gencode_token" name="csrf_gencode_token" value="<?php echo generateToken('gen_code_table'); ?>">
				                <thead>
					                <tr>
					                  	<th> # </th>
					                  	<th hidden>Id</th>
					                 	<th>Gen Code Group</th>
					                 	<th>Gen Code</th>
					                 	<th>Description</th>
					                 	<th>Sl No</th>
										<th>Status</th>
										<th style="width: 17%;">Action</th>
					                </tr>
				                </thead>
			              	</table>
            			</div>
            		</div>
            	</div>
          	</div>
          	<div class="modal fade" id="manage_gencode_modal" role="dialog" >
				<div class="modal-dialog">
			      	<div class="modal-content">
			      		<div class='modal-header'>
				          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
				          	<h4 class='modal-title' style='text-align: center;'><span id="spanuser" >Add Gen Code</span></h4>
				        </div>
	         			<?php echo form_open(null, array('id'=>'frm_gen_code' ,'enctype'=>"multipart/form-data")); ?>
	         				<div class='modal-body' style="height: auto">
	         					<div class="box-body">
			            			<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
			            			<input type="hidden" id="op_type" name="op_type" value="add_gen_code">
			            			<input type="hidden" name="hid_id" id="hid_id">
			            			<input type="hidden" name="hid_gen_code_group" id="hid_gen_code_group">
			            			<input type="hidden" name="csrf_op_gencode_token" value="<?php echo generateToken('frm_gen_code'); ?>">
									<div class="row">
										<div class="col-md-12">
					            			<div class="form-group col-md-6">
												<label >GenCode Group</label>
												<select class="form-control" id="gen_code_group" name="gen_code_group">
													<option value="">select</option>
													<?php
														if(!empty($get_gencode_group)):
															foreach($get_gencode_group as $gencode_group):
													?>
														<option value="<?php echo $gencode_group['gen_code_group']?>"><?php echo $gencode_group['gen_code_group']?></option>
													<?php
															endforeach;
														endif;
													?>
												</select> 
											</div>
											<label style="padding-top: 25px;">
												<a href="#" class="btn btn-warning btn tooltipTable" id="new_gc_grp_btn"  title="Add New GenCode Group" >
			          							<span class="glyphicon glyphicon-plus"></span>&nbsp;New</a>
											</label>
										</div>
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label >Gen Code :</label>
												<input type="text" class="form-control" name="gen_code" id="gen_code" placeholder="Gen Code" value="" required="" autocomplete="off">
											</div>
											<div class="form-group col-md-6">
												<label >Description :</label>
												<input type="text" class="form-control" name="description" id="description" placeholder="Description" value="" required="" autocomplete="off">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label >Serial No. :</label>
												<input type="number" class="form-control" name="sl_no" id="sl_no" placeholder="Serial" value="" required="" autocomplete="off">
											</div>
											<div class="form-group col-md-6">
												<label >Status:</label>
												<select class="form-control" id="status" name="status">
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
						</form>
					</div>
            	</div>
            </div>
      	</div>
	</section>
</div>
<div class="modal fade" id="addGenCodeGrp" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Gen Code Group</h4>
			</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" name="new_gc_grp" id="new_gc_grp" placeholder="New Gen Code Group" autocomplete="off" autofocus="" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="add_new_gc" class="btn btn-primary" >Add</button>
					<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				</div>
		</div>
	</div>
</div>
<script> base_url = "<?php echo base_url(); ?>"; </script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/gen_code_setup.js"></script>