<div id="cancelCauseListModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">
    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title cancel-cause-list-modal-title" style="text-align: center;">Cancel Cause List</h4>
	      </div>
	      <div class="modal-body">
	      	
			<?php echo form_open(null, array('class'=>'wfst','id'=>'form_cancel_cause_list','enctype'=>"multipart/form-data")); ?>
    			<input type="hidden" id="cancel_cause_list_hidden_id" name="hidden_id" value="">
    			<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_cancel_cause_list'); ?>">	
    		    	
    		    	<fieldset>
    		    		<legend>Cancel Cause List</legend>
    		    		<div class="col-md-12">
							<div class="form-group col-xs-12">
								<h3>Do you want to cancel the <span class="text-danger" id="cancel_case_number"></span> case?</h3>
							</div>
							<div class="form-group col-xs-12">
								<label class="control-label">Remarks: <span class="text-danger">*</span></label>
								<textarea class="form-control" id="cancel_remarks" name="cancel_remarks"  autocomplete="off" maxlength="200"></textarea>
								<small class="help-block">Max 200 Words</small>
							</div>

						</div>
    		    	</fieldset>

					<div class="box-footer with-border text-center">
			          	<div class="box-tools">
							  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
							  <button type="submit" class="btn btn-custom" id="cause_list_cancel_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
			          	</div>
	        		</div>
	      	<?php echo form_close();?>
	      </div>
	    </div>
  	</div>
</div>