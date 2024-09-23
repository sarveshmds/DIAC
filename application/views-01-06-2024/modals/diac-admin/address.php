<!-- Claimant and respondent modal start -->
<div id="addressFormModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">
    	<!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title address-modal-title" style="text-align: center;"></h4>
	      </div>
	      <div class="modal-body">

	        <?= form_open(null, array('class' => 'wfst', 'id' => 'address_form')) ?>

	  			<input type="hidden" id="address_op_type" name="op_type" value="">
                <input type="hidden" id="address_hidden_case_no" name="hidden_case_no" value="">

    			<input type="hidden" id="hidden_address_id" name="hidden_address_id" value="">

    			<input type="hidden" name="csrf_address_form_token" value="<?php echo generateToken('address_form'); ?>">

    			<div id="car_wrapper">
					<div class="row">
						<div class="form-group col-xs-12 required">
							<label class="control-label">Address 1:</label>
							<input type="text" class="form-control" id="address_address_one" name="address_address_one"  autocomplete="off" maxlength="200" value="" />
						</div>
						<div class="form-group col-xs-12 required">
							<label class="control-label">Address 2:</label>
							<input type="text" class="form-control" id="address_address_two" name="address_address_two"  autocomplete="off" maxlength="200" value="" />
							<p class="help-block" id="address_address_two_help"></p>
						</div>

                        <div class="form-group col-xs-12">
                            <label class="control-label">Country:</label>
                            <select class="form-control select2" name="address_country" id="address_country">
                                <option value="">Select Country</option>
                                <?php foreach($countries as $country): ?>
                                    <option value="<?= $country['iso2'] ?>"><?= $country['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

						<div class="form-group col-xs-12">
							<label class="control-label">State:</label>
							<select class="form-control" name="address_state" id="address_state">
							</select>
						</div>


                        <div class="form-group col-xs-12">
							<label class="control-label">Pincode:</label>
							<input type="number" class="form-control" id="address_pincode" name="address_pincode"  autocomplete="off" value="">
						</div>

					</div>

				</div>

				<div class="row text-center mt-25">
		          	<div class="col-xs-12">
						  <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
						  <button type="submit" class="btn btn-custom" id="add-address-btn"><i class='fa fa-paper-plane'></i> Save Details</button>
		          	</div>
        		</div>
			<?= form_close() ?>

	      </div>
	    </div>
  	</div>
</div>
<!-- Claimant and respondent modal end -->