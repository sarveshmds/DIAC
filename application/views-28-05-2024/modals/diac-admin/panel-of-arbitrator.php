<div id="addPOAListModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title poa-modal-title" style="text-align: 	center;"></h4>
	      </div>
	      <div class="modal-body">
			<?php echo form_open(null, array('class'=>'wfst','id'=>'form_add_poa','method'=>"GET")); ?>
    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
    			<input type="hidden" id="poa_op_type" name="op_type" value="">
    			<input type="hidden" id="poa_hidden_id" name="poa_hidden_id" value="">	
    			<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_poa'); ?>">	
    		    	
    		    	<fieldset>
    		    		<legend>Panel of Arbitrator</legend>
    		    		<div class="col-md-12">
    		    			
							<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
								<label class="control-label">Name:</label>
								<input type="text" class="form-control" id="poa_name" name="poa_name"  autocomplete="off" maxlength="100"/>
							</div>

							<div class="form-group col-md-3 col-sm-6 col-xs-12 required">
								<label class="control-label">Category:</label>
								<select name="poa_category" id="poa_category" class="form-control">
									<option value="">Select Category</option>
									<?php foreach($panel_category as $pc): ?>
										<option value="<?= $pc['id'] ?>"><?= $pc['category_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group col-md-3 col-sm-6 col-xs-12">
								<label class="control-label">Experience</label>
								<input type="text" class="form-control" id="poa_experience" name="poa_experience"  autocomplete="off" maxlength="50"/>
								<p class="help-block">Ex.: 10 years</p>
							</div>

							<div class="form-group col-md-3 col-sm-6 col-xs-12">
								<label for="" class="control-label">Enrollment No. (If any)</label>
								<input type="text" name="poa_enrollment_no" id="poa_enrollment_no" class="form-control">
							</div>

							<div class="form-group col-md-6 col-sm-12 col-xs-12">
								<label for="" class="control-label">Contact Details</label>
								<textarea class="form-control textarea-editor" rows="4" name="poa_contact_details" id="poa_contact_details"></textarea>
							</div>

							<div class="form-group col-md-6 col-sm-12 col-xs-12">
								<label for="" class="control-label">Email Details</label>
								<textarea class="form-control textarea-editor" rows="4" name="poa_email_details" id="poa_email_details"></textarea>
							</div>

							<div class="form-group col-md-6 col-sm-12 col-xs-12">
								<label for="" class="control-label">Address Details</label>
								<textarea class="form-control textarea-editor" rows="4" name="poa_address_details" id="poa_address_details"></textarea>
							</div>

							<div class="form-group col-md-6 col-sm-12 col-xs-12">
								<label for="" class="control-label">Remarks</label>
								<textarea class="form-control textarea-editor" rows="4" name="poa_remarks" id="poa_remarks"></textarea>
							</div>

						</div>
    		    	</fieldset>

					<div class="box-footer with-border text-center">
			          	<div class="box-tools">
							  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
							  <button type="submit" class="btn btn-custom" id="poa_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
			          	</div>
	        		</div>
	      	<?php echo form_close();?>
	      </div>
	    </div>
  	</div>
</div>