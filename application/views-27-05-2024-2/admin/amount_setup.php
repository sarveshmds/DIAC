
<div class="content-wrapper" >
	<section class="content-header">
      	<h1>Amount Setup</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-cog"></i> Master Setup</a></li>
        	<li class="active"><a href="#"><i class="fa fa-inr"></i>Amount Setup</a></li>
        </ol>
	</section>
	<section class="content">
		<div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12">
        		<div class="box">
            		<div class="box-body">
						<div class="col-lg-12">
			              	<table id="amount_setup_table" class="table table-bordered table-hover">
			              		<input type="hidden" id="csrf_amount_setup_token" name="csrf_amount_setup_token" value="<?php echo generateToken('amount_setup_table'); ?>">
				                <thead>
					                <tr>
					                  	<th> # </th>
					                  	<th>Licence For</th>
					                 	<th>Application Type</th>
					                 	<th>Applied For</th>
					                 	<th>Area Type</th>
					                 	<th>Amount</th>
					                 	<th>Status</th>
										<th style="width: 30%;">Action</th>
					                </tr>
				                </thead>
			              	</table>
            			</div>
            		</div>
            	</div>
          	</div>
          	<div class="modal fade" id="amount_setup_modal" role="dialog" >
				<div class="modal-dialog">
			      	<div class="modal-content">
			      		<div class='modal-header'>
				          	<button type='button' class='close' data-dismiss='modal'>&times;</button>
				          	<h4 class='modal-title' style='text-align: center;'><span id="span_setup" >Add Amount Setup</span></h4>
				        </div>
	         			<?php echo form_open(null, array('id'=>'form_amt_setup','name'=>'form_amt_setup','enctype'=>"multipart/form-data")); ?>
	         				<div class='modal-body' style="height: auto">
	         					<div class="box-body">
			            			<div id="errorlog" style="display: none; color: red; font-size: 12px;"></div>
			            			<input type="hidden" id="op_type" name="op_type" value="add_amount_setup">
			            			
			            			<input type="hidden" id="hidden_licence_for" name="hidden_licence_for" >
			            			<input type="hidden" id="hidden_appl_type" name="hidden_appl_type" >
			            			<input type="hidden" id="hidden_applied_for" name="hidden_applied_for" >
			            			<input type="hidden" id="hidden_area_type" name="hidden_area_type" >
			            			
			            			<input type="hidden" name="csrf_op_gencode_token" value="<?php echo generateToken('form_amt_setup'); ?>">
									<div class="row">
										<div class="col-md-12">
					            			<div class="form-group col-md-6">
												<label >Licence For</label>
												<select class="form-control" id="cmb_licence_for" name="cmb_licence_for">
													<option value="">select</option>
													<?php
														if(!empty($licence_for)):
															foreach($licence_for as $lf):
													?>
														<option value="<?php echo $lf['gen_code']?>"><?php echo $lf['description']?></option>
													<?php
															endforeach;
														endif;
													?>
												</select> 
											</div>
											<div class="form-group col-md-6">
												<label>Application Type</label>
												<select class="form-control" id="cmb_appl_type" name="cmb_appl_type">
													<option value="">select</option>
													<?php
														if(!empty($appl_type)):
															foreach($appl_type as $at):
													?>
														<option value="<?php echo $at['gen_code']?>"><?php echo $at['description']?></option>
													<?php
															endforeach;
														endif;
													?>
												</select> 
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label>Applied For</label>
												<select class="form-control" id="cmb_applied_for" name="cmb_applied_for">
													<option value="">select</option>
													<?php
														if(!empty($application_for)):
															foreach($application_for as $appl_for):
													?>
														<option value="<?php echo $appl_for['wf_code']?>"><?php echo $appl_for['wf_name']?></option>
													<?php
															endforeach;
														endif;
													?>
												</select> 
											</div>
											<div class="form-group col-md-6">
												<label>Area Type</label>
												<select class="form-control" id="cmb_area_type" name="cmb_area_type">
													<option value="">select</option>
													<?php
														if(!empty($area_type)):
															foreach($area_type as $at):
													?>
														<option value="<?php echo $at['gen_code']?>"><?php echo $at['description']?></option>
													<?php
															endforeach;
														endif;
													?>
												</select> 
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group col-md-6">
												<label >Amount :</label>
												<input type="text" class="form-control" name="txt_amount" id="txt_amount" placeholder="eg 2000.00"  autocomplete="off">
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
						<?php echo form_close();?>
					</div>
            	</div>
            </div>
      	</div>
	</section>
</div>
<script> base_url = "<?php echo base_url(); ?>"; </script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/admin/amount_setup.js"></script>