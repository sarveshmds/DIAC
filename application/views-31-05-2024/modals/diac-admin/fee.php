<!-- Fee Modal start -->
<div id="addFeeListModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title counsel-modal-title" style="text-align: center;"></h4>
            </div>
            <div class="modal-body">

                <?= form_open(null, array('class' => 'wfst', 'id' => 'case_fee_cost_form')) ?>
                <input type="hidden" name="hidden_case_no" id="fee_cost_case_no" value="<?php echo $case_no; ?>">

                <input type="hidden" id="fee_cost_op_type" name="op_type" value="ADD_CASE_FEE_COST_DETAILS">

                <!-- EDIT_CASE_FEE_COST_DETAILS -->
                <input type="hidden" id="hidden_fee_cost_id" name="hidden_id" value="">

                <input type="hidden" name="csrf_case_fee_cost_form_token" value="<?php echo generateToken('case_fee_cost_form'); ?>">

                <div class="row">
                    <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_assesment_approved_col">
                        <label class="control-label">Whether Claims or Counter Claims assessed separately:</label>
                        <select class="form-control" name="fc_c_cc_assessed_sep" id="fc_c_cc_assessed_sep">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_sum_despute_no_col" style="display: none;">
                        <label class="control-label">Sum in Dispute (Rs.):</label>
                        <input type="number" data-help-block-id="help_fc_sum_despute" class="form-control amount_field" id="fc_sum_despute" name="fc_sum_despute" autocomplete="off" maxlength="200" value="">
                        <p id="help_fc_sum_despute" class="amount_help_block"></p>
                    </div>

                    <div class="form-group col-md-4  col-sm-6 col-xs-12 fc_sum_despute_yes_col" style="display: none;">
                        <label class="control-label">Sum in Dispute (Claims) (Rs.):</label>
                        <input type="number" data-help-block-id="help_fc_sum_despute_claim" class="form-control amount_field" id="fc_sum_despute_claim" name="fc_sum_despute_claim" autocomplete="off" maxlength="200" value="">
                        <p id="help_fc_sum_despute_claim" class="amount_help_block"></p>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_sum_despute_yes_col" style="display: none;">
                        <label class="control-label">Sum in Dispute (Counter Claims) (Rs.):</label>
                        <input type="number" data-help-block-id="help_fc_sum_despute_cc" class="form-control amount_field" id="fc_sum_despute_cc" name="fc_sum_despute_cc" autocomplete="off" maxlength="200" value="">
                        <p id="help_fc_sum_despute_cc" class="amount_help_block"></p>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label">As per provisional assessment dated:</label>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control custom-all-date" id="fc_pro_assesment" name="fc_pro_assesment" autocomplete="off" readonly="true" value="" />
                        </div>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                        <label for="" class="">Assesment Sheet (If any)</label>
                        <input type="file" class="form-control filestyle" name="fc_assesment_sheet" id="fc_assesment_sheet" accept=".jpg, .pdf">
                        <small class="text-muted">Max size <?= MSG_FILE_MAX_SIZE ?> & only <?= MSG_CASE_FILE_FORMATS_ALLOWED ?> are allowed.</small>
                        <input type="hidden" name="hidden_fc_assesment_sheet_file_name" id="hidden_fc_assesment_sheet_file_name" value="">
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label">Total Arbitrators Fees (Rs.):</label>
                        <input type="number" data-help-block-id="help_fc_total_arb_fees" class="form-control amount_field" rows="5" id="fc_total_arb_fees" name="fc_total_arb_fees" autocomplete="off" maxlength="200" value="">
                        <p id="help_fc_total_arb_fees" class="amount_help_block"></p>
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-12 fc_assesment_approved_col">
                        <label class="control-label">Assesment Approved:</label>
                        <select class="form-control" name="fc_assesment_approved" id="fc_assesment_approved">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php  // Claimant Share 
                        ?>
                        <fieldset class="fieldset">
                            <legend class="inner-legend">Claimant Share</legend>
                            <div class="fieldset-content-box">
                                <div class="form-group col-sm-12 col-xs-12">
                                    <label class="control-label">Arbitrators Fees (Rs.):</label>
                                    <input type="number" data-help-block-id="help_fc_cs_arb_fees" class="form-control amount_field" rows="5" id="fc_cs_arb_fees" name="fc_cs_arb_fees" autocomplete="off" maxlength="200" value="">
                                    <p id="help_fc_cs_arb_fees" class="amount_help_block"></p>
                                </div>

                                <div class="form-group col-sm-12 col-xs-12">
                                    <label class="control-label">Administrative Expenses (Rs.):</label>
                                    <input type="number" data-help-block-id="help_fc_cs_adm_fees" class="form-control amount_field" rows="5" id="fc_cs_adm_fees" name="fc_cs_adm_fees" autocomplete="off" maxlength="200" value="">
                                    <p id="help_fc_cs_adm_fees" class="amount_help_block"></p>
                                </div>

                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php  // Respondent Share 
                        ?>
                        <fieldset class="fieldset">
                            <legend class="inner-legend">Respondent Share</legend>
                            <div class="fieldset-content-box">
                                <div class="row">
                                    <div class="form-group col-sm-12 col-xs-12">
                                        <label class="control-label">Arbitrators Fees (Rs.):</label>
                                        <input type="number" data-help-block-id="help_fc_rs_arb_fees" class="form-control amount_field" rows="5" id="fc_rs_arb_fees" name="fc_rs_arb_fees" autocomplete="off" maxlength="200" value="">
                                        <p id="help_fc_rs_arb_fees" class="amount_help_block"></p>
                                    </div>

                                    <div class="form-group col-sm-12 col-xs-12">
                                        <label class="control-label">Administrative Expenses (Rs.):</label>
                                        <input type="number" data-help-block-id="help_fc_rs_adm_fees" class="form-control amount_field" rows="5" id="fc_rs_adm_fees" name="fc_rs_adm_fees" autocomplete="off" maxlength="200" value="">
                                        <p id="help_fc_rs_adm_fees" class="amount_help_block"></p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                </div>
                <div class="row text-center">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-custom" id="fee_cost_btn_submit"><i class='fa fa-paper-plane'></i> Save Details</button>
                    </div>
                </div>
                <?= form_close() ?>

            </div>
        </div>
    </div>
</div>
<!-- Fee Modal end -->