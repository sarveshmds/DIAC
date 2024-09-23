<!-- Other Correspondance modal start -->
<div id="addOCListModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    	<!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title oc-modal-title" style="text-align: center;"></h4>
	      </div>
	      <div class="modal-body">

	        <?= form_open(null, array('class' => 'wfst', 'id' => 'oc_form')) ?>

	  			<input type="hidden" id="oc_op_type" name="op_type" value="ADD_CASE_OTHER_CORRESPONDANCE">

	    		<?php if(isset($case_no)): ?>
	    			<input type="hidden" id="oc_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">	
	    		<?php endif; ?>

    			<input type="hidden" id="hidden_oc_id" name="hidden_oc_id" value="">

    			<input type="hidden" name="csrf_oc_form_token" value="<?php echo generateToken('case_oc_form'); ?>">

    			<div id="oc_wrapper">
	    			<fieldset class="fieldset oc_fieldset" id="oc_fieldset">
						<legend class="oc_legend_head">Other Correspondance</legend>
							<div class="fieldset-content-box">
								<div class="row">
									<div class="form-group col-md-12 col-sm-12 col-xs-12 required">
										<label class="control-label">Details:</label>
										<textarea class="form-control textarea-editor" id="oc_details" name="oc_details"  autocomplete="off" rows="7"></textarea>
									</div>
									<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
										<label class="control-label">Date of Correspondance:</label>
										<div class="input-group date">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
											<input type="text" class="form-control custom-all-date" id="oc_doc" name="oc_doc" autocomplete="off" readonly="true" value="<?= date('d-m-Y') ?>"/>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
										<label class="control-label">Sent By:</label>
										<input type="text" class="form-control" id="oc_send_by" name="oc_send_by"  autocomplete="off" maxlength="100" value="">
									</div>

									<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
										<label class="control-label">Sent to:</label>
										<input type="text" class="form-control" id="oc_sent_to" name="oc_sent_to"  autocomplete="off" maxlength="100" value="">
									</div>
									
								</div>
							</div>
						</fieldset>

					</div>

				<div class="row text-center mt-25">
		          	<div class="col-xs-12">
						  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						  <button type="submit" class="btn btn-custom oc_btn_submit" id="oc_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
		          	</div>
        		</div>
			<?= form_close() ?>

	      </div>
	    </div>
  	</div>
</div>
<!-- Other correspondance modal end -->