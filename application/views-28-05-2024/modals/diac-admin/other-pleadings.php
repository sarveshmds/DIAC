<!-- Other Pleading modal start -->
<div id="addOPListModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    	<!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title op-modal-title" style="text-align: center;"></h4>
	      </div>
	      <div class="modal-body">

	        <?= form_open(null, array('class' => 'wfst', 'id' => 'op_form')) ?>

	  			<input type="hidden" id="op_op_type" name="op_type" value="ADD_CASE_OTHER_PLEADINGS">
	  			
	    		<?php if(isset($case_no)): ?>
	    			<input type="hidden" id="op_hidden_case_no" name="hidden_case_no" value="<?php echo $case_no; ?>">	
	    		<?php endif; ?>

    			<input type="hidden" id="hidden_op_id" name="hidden_op_id" value="">

    			<input type="hidden" name="csrf_op_form_token" value="<?php echo generateToken('case_op_form'); ?>">

    			<div id="op_wrapper">
	    			<fieldset class="fieldset op_fieldset" id="op_fieldset">
						<legend class="car_legend_head">Miscellaneous</legend>
							<div class="fieldset-content-box">
								<div class="row">
									<div class="form-group col-md-12 col-xs-12 col-sm-12 required">
										<label class="control-label">Details:</label>
										<textarea class="form-control customized-summernote" id="op_details" name="op_details"  autocomplete="off" rows="7"></textarea>
									</div>
									<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
										<label class="control-label">Date of filing:</label>
										<div class="input-group date">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
											<input type="text" class="form-control custom-all-date" id="op_dof" name="op_dof" autocomplete="off" readonly="true" value="<?= date('d-m-Y') ?>"/>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
										<label class="control-label">Filed By:</label>
										<input type="text" class="form-control" id="op_filed_by" name="op_filed_by"  autocomplete="off" maxlength="100" value="">
									</div>
									
								</div>
							</div>
						</fieldset>

					</div>

				<div class="row text-center mt-25">
		          	<div class="col-xs-12">
						  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						  <button type="submit" class="btn btn-custom op_btn_submit" id="op_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
		          	</div>
        		</div>
			<?= form_close() ?>

	      </div>
	    </div>
  	</div>
</div>
<!-- Other pleadings modal end -->