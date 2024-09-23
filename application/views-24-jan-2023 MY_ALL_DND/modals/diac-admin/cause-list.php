<div id="addCauseListModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title cause-list-modal-title" style="text-align: 	center;"></h4>
	      </div>
	      <div class="modal-body">
	      	
			<?php echo form_open(null, array('class'=>'wfst','id'=>'form_add_cause_list','enctype'=>"multipart/form-data")); ?>
    		    <div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
    			<input type="hidden" id="cause_list_op_type" name="op_type" value="">
    			<input type="hidden" id="cause_list_hidden_id" name="hidden_id" value="">	
    			<input type="hidden" name="csrf_case_form_token" value="<?php echo generateToken('form_add_cause_list'); ?>">	
    		    	
    		    	<fieldset>
    		    		<legend>Cause List</legend>
    		    		<div class="col-md-12">
    		    			<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
								<label class="control-label">Case No:</label>
								<input type="text" class="form-control str_to_uppercase" id="case_no" name="case_no"  autocomplete="off" maxlength="50"/>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
								<label class="control-label">Title of Case:</label>
								<input type="text" class="form-control" id="title_of_case" name="title_of_case"  autocomplete="off" maxlength="250"/>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
								<label class="control-label">Arbitrator's Name:</label>
								<input type="text" class="form-control" id="arbitrator_name" name="arbitrator_name"  autocomplete="off" maxlength="150"/>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
								<label class="control-label">Date:</label>
								<div class="input-group date">
									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									<input type="text" class="form-control custom-all-date" id="date" name="date" autocomplete="off" readonly="true" value="<?php echo date('d-m-Y');?>" data-date-start-date="0d" />
								</div>
							</div>

							
							<div class="form-group col-md-4 col-sm-6 col-xs-12">
								<div class="bootstrap-timepicker">
									<label for="" class="control-label">Time (From):</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
										<input type="text" class="form-control timepicker" id="time_from" name="time_from" autocomplete="off" readonly="true" />
									</div>
								</div>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12">
								<div class="bootstrap-timepicker">
									<label for="" class="control-label">Time (To):</label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
										<input type="text" class="form-control timepicker" id="time_to" name="time_to" autocomplete="off" />
									</div>

								</div>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12 required">
								<label for="" class="control-label">Purpose</label>
								<select class="form-control" id="purpose" name="purpose">
									<option value="">Select Category</option>
									<?php foreach($purpose_category as $puc): ?>
										<option value="<?= $puc['id'] ?>"><?= $puc['category_name'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12">
								<label for="" class="control-label">Room No.</label>
								<select name="room_no" id="room_no" class="form-control">
									<option value="">Select Room</option>
									<?php foreach($all_rooms_list as $list): ?>
										<option value="<?= $list['id'] ?>"><?= $list['room_name'].' ('.$list['room_no'].')' ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group col-md-4 col-sm-6 col-xs-12">
								<label class="control-label">Remarks:</label>
								<input type="text" class="form-control" id="remarks" name="remarks"  autocomplete="off" />
							</div>

						</div>
    		    	</fieldset>

					<div class="box-footer with-border text-center">
			          	<div class="box-tools">
							  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
							  <button type="submit" class="btn btn-custom" id="cause_list_btn_submit"><i class='fa fa-paper-plane'></i> Submit</button>
			          	</div>
	        		</div>
	      	<?php echo form_close();?>
	      </div>
	    </div>
  	</div>
</div>