<!-- Filters Start -->
<div>
    <div class="mb-15">
        <button class="btn btn_show_hide_filters" id="show_filters" type="button">Show/Hide Filters <i class="fa fa-caret-down"></i></button>
    </div>
    <fieldset class="fieldset" id="filters-fieldset" style="display: none;">
        <div class="flex-row">

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label>DIAC Registration No.</label>
                <input type="text" class="form-control" id="ac_f_case_no" name="ac_f_case_no" autocomplete="off" maxlength="100" placeholder="DIAC Registration No." />
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label>Case Title</label>
                <input type="text" class="form-control" id="ac_f_case_title" name="ac_f_case_title" autocomplete="off" maxlength="100" placeholder="Enter Case Title" />
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label>Reference No.:</label>
                <input type="text" class="form-control" id="ac_f_arb_pet" name="ac_f_arb_pet" autocomplete="off" maxlength="100" placeholder="Enter Reference No." />
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Type of arbitration:</label>
                <select name="ac_f_toa" id="ac_f_toa" class="form-control">
                    <option value="">Select Option</option>
                    <?php foreach ($type_of_arbitration as $toa) : ?>
                        <option value="<?= $toa['gen_code'] ?>"><?= $toa['description'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label class="control-label">Date of registration:</label>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <small class="d-block">From:</small>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control custom-all-date" id="ac_f_registered_on_from" name="ac_f_registered_on_from" autocomplete="off" readonly="true" value="" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <small class="d-block">To:</small>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control custom-all-date" id="ac_f_registered_on_to" name="ac_f_registered_on_to" autocomplete="off" readonly="true" value="" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label class="control-label">Date of award:</label>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <small class="d-block">From:</small>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control custom-all-date" id="ac_f_date_of_award_from" name="ac_f_date_of_award_from" autocomplete="off" readonly="true" value="" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <small class="d-block">To:</small>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control custom-all-date" id="ac_f_date_of_award_to" name="ac_f_date_of_award_to" autocomplete="off" readonly="true" value="" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Mode of reference:</label>
                <select name="ac_f_reffered_by" id="ac_f_reffered_by" class="form-control">
                    <option value="">Select Option</option>
                    <?php foreach ($reffered_by as $rb) : ?>
                        <option value="<?= $rb['gen_code'] ?>"><?= $rb['description'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Referred By (Judge/Justice):</label>
                <input type="text" name="ac_f_reffered_by_judge" id="ac_f_reffered_by_judge" class="form-control" value="" placeholder="Enter Referred By (Judge/Justice):">
            </div>

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Date of reference:</label>
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input type="text" class="form-control custom-all-date" id="ac_f_referred_on" name="ac_f_referred_on" autocomplete="off" readonly="true" value="" />
                </div>
            </div>

            <!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Name of arbitrator</label>
                <input type="text" class="form-control" name="ac_f_name_of_arbitrator" id="ac_f_name_of_arbitrator" maxlength="255" placeholder="Name of arbitrator">
            </div> -->

            <!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Name of counsel</label>
                <input type="text" class="form-control" name="ac_f_name_of_counsel" id="ac_f_name_of_counsel" maxlength="255" placeholder="Name of counsel">
            </div> -->

            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="" class="control-label">Case Status:</label>
                <select name="ac_f_status" id="ac_f_status" class="form-control">
                    <option value="">Select Option</option>
                    <?php foreach ($get_case_status as $cs) : ?>
                        <option value="<?= $cs['gen_code'] ?>"><?= $cs['description'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-12 col-sm-12 col-xs-12 mt-10 text-center">
                <a href="Javascript:void(0)" class="text-black font-weight-bold mr-10" id="ac_f_reset_btn"><span class="fa fa-refresh"></span> Reset</a>
                <button class="btn btn-custom" id="ac_f_btn"><span class="fa fa-search"></span> Search</button>
            </div>
        </div>
    </fieldset>
</div>
<!-- Filters End -->