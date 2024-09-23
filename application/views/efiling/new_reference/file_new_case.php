<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="page-title">
                        <?= $page_title ?>
                    </h2>
                    <div class="page-pretitle">
                        All (<span class="text-danger">*</span>) fields are mandatory
                    </div>
                </div>
                <a href="<?= base_url('efiling/new-references') ?>" class="btn">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->
        <div class="alert alert-info">
            <strong>Note:</strong> In registered mobile number field, please provide the mobile number which is active and used for account registration. <br>
            The case will be shown in your panel based on the mobile number with which you have registered the account and the same is provided during filing of new reference.
        </div>
        <form action="" id="file_new_case_form" method="post">

            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-title">Claimant Details</h3>
                        <p class="card-subtitle">
                            Enter the claimant details here.
                        </p>
                    </div>
                    <button type="button" id="add-claimant" class="btn btn-custom btn-sm">
                        <i class="fa fa-plus"></i> Add More
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="claimants-table">
                        <thead>
                            <tr>
                                <th>Claimant Name</th>
                                <th colspan="2" class="text-center">Address</th>

                                <th>E-mail</th>
                                <th>Registered Mobile No.</th>
                                <th class="text-center">

                                </th>
                            </tr>
                        </thead>
                        <tbody class="table-class-claimant">
                            <?php if (isset($new_reference_claimant)) : ?>
                                <?php foreach ($new_reference_claimant as $cl) : ?>
                                    <tr>
                                        <td><?= $cl['name'] ?></td>
                                        <td>
                                            <p class="mb-1">
                                                <?= $cl['address_one'] . ', ' . $cl['address_two'] ?>
                                            </p>
                                            <p class="mb-1">
                                                <?= $cl['state_name'] ?>
                                            </p>
                                            <p class="mb-1">
                                                <?= $cl['country_name'] . ' - ' . $cl['pincode'] ?>
                                            </p>
                                        </td>
                                        <td><?= $cl['email_id'] ?></td>
                                        <td><?= $cl['phone_number'] ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <strong>Note:</strong> Email ID & mobile number will be used for future communications.
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header  d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-title">Respondant Details</h3>
                        <p class="card-subtitle">
                            Enter the respondent details here.
                        </p>
                    </div>
                    <button type="button" id="add-respondent" class="btn btn-custom btn-sm">
                        <i class="fa fa-plus"></i> Add More
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table" id="respondents-table">
                            <thead>
                                <tr>
                                    <th>Respondant Name</th>
                                    <th colspan="2" class="text-center">Address</th>
                                    <th>E-mail</th>
                                    <th>Registered Mobile No.</th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-class-respondant">
                                <?php if (isset($new_reference_respondants)) : ?>
                                    <?php foreach ($new_reference_respondants as $res) : ?>
                                        <tr>
                                            <td><?= $res['name'] ?></td>
                                            <td>
                                                <p class="mb-1">
                                                    <?= $res['address_one'] . ', ' . $res['address_two'] ?>
                                                </p>
                                                <p class="mb-1">
                                                    <?= $res['state_name'] ?>
                                                </p>
                                                <p class="mb-1">
                                                    <?= $res['country_name'] . ' - ' . $res['pincode'] ?>
                                                </p>
                                            </td>
                                            <td><?= $res['email_id'] ?></td>
                                            <td><?= $res['phone_number'] ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <strong>Note:</strong> Email ID & mobile number will be used for future communications.
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('file_new_case_form'); ?>">

                    <?php if (isset($edit_form) && $edit_form == true) : ?>
                        <input type="hidden" name="hidden_id" value="<?= $new_reference['id'] ?>">
                        <input type="hidden" name="op_type" id="op_type" value="EDIT">
                    <?php endif; ?>
                    <div class="row">

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="type_of_arbitration">Type of arbitration <span class="text-danger">*</span></label>
                            <select name="type_of_arbitration" id="type_of_arbitration" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($type_of_arbitration as $toa) : ?>
                                    <option value="<?= $toa['gen_code'] ?>" <?= (isset($new_reference['type_of_arbitration']) && $toa['gen_code'] == $new_reference['type_of_arbitration']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="case_type">Case Type <span class="text-danger">*</span></label>
                            <select name="case_type" id="case_type" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($di_type_of_arbitration as $toa) : ?>
                                    <?php // Remove the fasttrack condition when implement the fasttrack case process. 
                                    ?>
                                    <?php if ($toa['gen_code'] != 'FASTTRACK') : ?>
                                        <option value="<?= $toa['gen_code'] ?>" <?= (isset($new_reference['case_type']) && $toa['gen_code'] == $new_reference['case_type']) ? 'selected' : '' ?>><?= $toa['description'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="arbitral_tribunal_strength">Arbitral Tribunal Strength <span class="text-danger">*</span> </label>
                            <select name="arbitral_tribunal_strength" id="arbitral_tribunal_strength" class="form-control">
                                <option value="">Select</option>
                                <?php foreach (ARBITRAL_TRIBUNAL_STRENGTH as $key => $ats) : ?>
                                    <option value="<?= $key ?>" <?= (isset($new_reference['arbitral_tribunal_strength']) && $key == $new_reference['arbitral_tribunal_strength']) ? 'selected' : '' ?>><?= $ats ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                            <label class="form-label" for="case_document">Case Document (If Any)</label>
                            <input type="file" name="case_document" id="case_document" class="form-control" accept=".pdf" />
                            <?php if (isset($new_reference['document']) && $new_reference['document']) : ?>
                                <input type="hidden" name="hidden_case_document" value="<?= $new_reference['document'] ?>">
                            <?php endif; ?>
                            <span class="text-danger text-sm">
                                (Only .pdf files are allowed. Max 25 MB)
                            </span>
                        </div>

                        <div class="col-12 mb-3" id="emergency_case_details_col" style="display: none;">
                            <fieldset class="fieldset">
                                <legend class="legend">Emergency Case Details</legend>
                                <div class="fieldset-content-col">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="soc_document">SOC <span class="text-danger">*</span></label>
                                            <input type="file" name="soc_document" id="soc_document" class="form-control" accept=".pdf" />
                                            <?php if (isset($new_reference['soc_document']) && $new_reference['soc_document']) : ?>
                                                <input type="hidden" name="hidden_soc_document" value="<?= $new_reference['soc_document'] ?>">
                                            <?php endif; ?>
                                            <span class="text-danger text-sm">
                                                (Only .pdf files are allowed. Max 25 MB)
                                            </span>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="urgency_app_document">Urgency Application <span class="text-danger">*</span></label>
                                            <input type="file" name="urgency_app_document" id="urgency_app_document" class="form-control" accept=".pdf" />
                                            <?php if (isset($new_reference['urgency_app_document']) && $new_reference['urgency_app_document']) : ?>
                                                <input type="hidden" name="hidden_urgency_app_document" value="<?= $new_reference['urgency_app_document'] ?>">
                                            <?php endif; ?>
                                            <span class="text-danger text-sm">
                                                (Only .pdf files are allowed. Max 25 MB)
                                            </span>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="proof_of_service_doc">Proof of Service <span class="text-danger">*</span></label>
                                            <input type="file" name="proof_of_service_doc" id="proof_of_service_doc" class="form-control" accept=".pdf" />
                                            <?php if (isset($new_reference['proof_of_service_doc']) && $new_reference['proof_of_service_doc']) : ?>
                                                <input type="hidden" name="hidden_proof_of_service_doc" value="<?= $new_reference['proof_of_service_doc'] ?>">
                                            <?php endif; ?>
                                            <span class="text-danger text-sm">
                                                (Only .pdf files are allowed. Max 25 MB)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-12 mb-3">
                            <fieldset class="fieldset">
                                <legend class="legend">Claim Amount</legend>
                                <div class="fieldset-content-col">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="claim_amount">Claim Amount <span class="text-danger">*</span> <span id="claim_amount_symbol"></span></label>
                                            <input type="number" name="claim_amount" id="claim_amount" class="form-control" value="<?= (isset($new_reference['claim_amount'])) ? $new_reference['claim_amount'] : '' ?>">
                                        </div>
                                        <div class="col-md-8 col-sm-6 col-12 mb-3 d-flex align-items-end">
                                            <button class="btn btn-success" id="caluclate_fees" type="button">
                                                Calculate Fees
                                            </button>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <a href="http://dhcdiac.nic.in/diac-administrative-cost-arbitrators-fees-rules-2018/" class="fw-bold text-danger text-decoration-underline" target="_BLANK">Click Here to view the Fee Rules </a>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Domestic Calculated Fees -->

            <div class="card domestic-fees-wrapper-col mt-3" style="display: none;">
                <div class="card-status-top bg-warning"></div>
                <div class="card-header">
                    <h3 class="card-title">Calculated Fees (Domestic)</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>
                                    Arbitrator Fees (Rs.)
                                </th>
                                <td>
                                    <input type="number" name="arb_total_fees" id="arb_total_fees" value="<?= (isset($new_reference['arb_total_fees'])) ? $new_reference['arb_total_fees'] : '' ?>" class="form-control" readonly />
                                    <small class="text-muted">(Total) (Based on Arbitral Tribunal Strength)</small>
                                </td>
                                <td>
                                    <input type="number" name="arb_your_share_fees" id="arb_your_share_fees" value="<?= (isset($new_reference['arb_your_share_fees'])) ? $new_reference['arb_your_share_fees'] : '' ?>" class="form-control" readonly />
                                    <small class="text-muted">(Your Share)</small>
                                </td>
                            </tr>
                            <tr class="domestic_adm_charges_row">
                                <th>
                                    Administrative Charges (Rs.)
                                </th>
                                <td>

                                </td>
                                <td>
                                    <input type="number" name="adm_charges" id="adm_charges" value="<?= (isset($new_reference['adm_charges'])) ? $new_reference['adm_charges'] : '' ?>" class="form-control" readonly />
                                    <small class="text-muted">(Each Party)</small>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Your share (Rs.)
                                </th>
                                <td>

                                </td>
                                <td>
                                    <input type="number" name="your_share" id="your_share" value="<?= (isset($new_reference['your_share'])) ? $new_reference['your_share'] : '' ?>" readonly class="form-control" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Your share payable (Rs.)
                                    <div>
                                        <small>You need to pay this amount to register your case.</small>
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <small>( <span class="arb_fees_percentage"></span> % of Arbitrator Fees + Administrative Charges)</small>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="your_payable_share" id="your_payable_share" value="<?= (isset($new_reference['your_payable_share'])) ? $new_reference['your_payable_share'] : '' ?>" readonly class="form-control" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- International Calculated Fees -->
            <div class="card international-fees-wrapper-col mt-3" style="display: none;">
                <div class="card-status-top bg-warning"></div>
                <div class="card-header">
                    <h3 class="card-title">Calculated Fees (International)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="" class="form-label">Current Dollar Price in Rupees:</label>
                            <p class="mb-0 text-danger">
                                <?= current_dollar_price_text() ?>
                            </p>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>
                                    Arbitrator Fees
                                </th>
                                <td>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_arb_total_fees_dollar" id="int_arb_total_fees_dollar" value="<?= (isset($new_reference['int_arb_total_fees_dollar'])) ? $new_reference['int_arb_total_fees_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_arb_total_fees_rupee" id="int_arb_total_fees_rupee" value="<?= (isset($new_reference['int_arb_total_fees_rupee'])) ? $new_reference['int_arb_total_fees_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>


                                    <small class="text-muted">(Total) (Based on Arbitral Tribunal Strength)</small>
                                </td>
                                <td>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_arb_your_share_fees_dollar" id="int_arb_your_share_fees_dollar" value="<?= (isset($new_reference['int_arb_your_share_fees_dollar'])) ? $new_reference['int_arb_your_share_fees_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_arb_your_share_fees_rupee" id="int_arb_your_share_fees_rupee" value="<?= (isset($new_reference['int_arb_your_share_fees_rupee'])) ? $new_reference['int_arb_your_share_fees_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <small class="text-muted">(Your Share)</small>
                                </td>
                            </tr>
                            <tr class="international_adm_charges_row">
                                <th>
                                    Total Administrative Charges
                                </th>
                                <td>

                                </td>
                                <td>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_total_adm_charges_dollar" id="int_total_adm_charges_dollar" value="<?= (isset($new_reference['int_total_adm_charges_dollar'])) ? $new_reference['int_total_adm_charges_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_total_adm_charges_rupee" id="int_total_adm_charges_rupee" value="<?= (isset($new_reference['int_total_adm_charges_rupee'])) ? $new_reference['int_total_adm_charges_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <small class="text-muted">(Total)</small>
                                </td>
                            </tr>
                            <tr class="international_adm_charges_row">
                                <th>
                                    Each Party Administrative Charges
                                </th>
                                <td>

                                </td>
                                <td>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_each_party_adm_charges_dollar" id="int_each_party_adm_charges_dollar" value="<?= (isset($new_reference['int_each_party_adm_charges_dollar'])) ? $new_reference['int_each_party_adm_charges_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_each_party_adm_charges_rupee" id="int_each_party_adm_charges_rupee" value="<?= (isset($new_reference['int_each_party_adm_charges_rupee'])) ? $new_reference['int_each_party_adm_charges_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <small class="text-muted">(Each Party)</small>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Your share
                                </th>
                                <td>

                                </td>
                                <td>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_your_share_dollar" id="int_your_share_dollar" value="<?= (isset($new_reference['int_your_share_dollar'])) ? $new_reference['int_your_share_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_your_share_rupee" id="int_your_share_rupee" value="<?= (isset($new_reference['int_your_share_rupee'])) ? $new_reference['int_your_share_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Your share payable
                                    <div>
                                        <small>You need to pay this amount to register your case.</small>
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <small>( <span class="arb_fees_percentage"></span> of Arbitrator Fees + Administrative Charges)</small>
                                    </div>
                                </td>
                                <td>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            $
                                        </span>
                                        <input type="number" name="int_your_payable_share_dollar" id="int_your_payable_share_dollar" value="<?= (isset($new_reference['int_your_payable_share_dollar'])) ? $new_reference['int_your_payable_share_dollar'] : '' ?>" class="form-control" readonly />
                                    </div>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text">
                                            <i class="fas fa-rupee-sign"></i>
                                        </span>
                                        <input type="number" name="int_your_payable_share_rupee" id="int_your_payable_share_rupee" value="<?= (isset($new_reference['int_your_payable_share_rupee'])) ? $new_reference['int_your_payable_share_rupee'] : '' ?>" class="form-control" readonly />
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-body mt-3">
                <div class="col-12 text-center ">
                    <button type="submit" class="btn btn-custom" disabled id="btn_submit">
                        <i class="fa fa-save"></i> Save & Proceed
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var claimantRowCount = 1; //Initial field counter is 1
    var trRowCount = 1;
    var claimantRowCountArray = [];

    var respondentRowCount = 1; //Initial field counter is 1
    var trRowCount = 1;
    var respondentRowCountArray = [];

    function getCountries() {
        var countries = [];
        $.ajax({
            url: base_url + "service/get_all_countries",
            type: "POST",
            data: {},
            async: false,
            success: function(response) {
                try {
                    countries = JSON.parse(response);
                } catch (e) {
                    toastr.error(
                        "Server faild while fetching countries. Please try again."
                    );
                }
            },
            error: function(errors) {
                toastr.error(errors);
            },
        });

        return countries;
    }

    function getStatesUsingCountryCode(country_code) {
        if (country_code) {
            var states = [];
            $.ajax({
                url: base_url + "service/get_states_using_country_code",
                method: "POST",
                data: {
                    country_code: country_code,
                },
                async: false,
                success: function(response) {
                    try {
                        states = JSON.parse(response);
                    } catch (e) {
                        sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                    }
                },
                error: function(err) {
                    toastr.error("unable to save");
                },
            });

            return states;
        }
    }

    $(document).on("change", ".cl_address_country", function() {
        var country_code = $(this).val();
        var rowCount = $(this).data("row-count");
        if (country_code) {
            var states = getStatesUsingCountryCode(country_code);

            var options = '<option value="">Select State</option>';
            $.each(states, function(index, state) {
                options +=
                    '<option value="' + state.id + '">' + state.name + "</option>";
            });
            $("#cl_state_" + rowCount).html(options);
            $("#cl_state_" + rowCount).select2();
        }
    });

    function generateClaimantRow(claimantRowCount) {
        var countries = getCountries();

        var countrySelect = `<select class="form-control select2 cl_address_country mb-1" name="cl_country_${claimantRowCount}" id="cl_country_${claimantRowCount}" data-row-count="${claimantRowCount}"><option value="">Select Country *</option>`;
        $.each(countries, function(index, country) {
            countrySelect +=
                '<option value="' + country.iso2 + '">' + country.name + "</option>";
        });
        countrySelect += "</select>";


        var tr = `
            <tr class="claimants-row" id="cl-tr-${claimantRowCount}">
                <td>
                    <input type="text" class="form-control" id="cl_name_${claimantRowCount}" name="cl_name_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Full Name *">
                </td>
                <td>
                    <input type="text" class="form-control mb-1" id="cl_address_one_${claimantRowCount}" name="cl_address_one_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 1 *">
                    <input type="text" class="form-control" id="cl_address_two_${claimantRowCount}" name="cl_address_two_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 2">
                    
                </td>
                <td>
                    <div class="mb-1">
                        ${countrySelect}
                    </div>

                    <div class="mb-1">
                        <select class="form-control select2 mb-1" id="cl_state_${claimantRowCount}" name="cl_state_${claimantRowCount}">
                            <option>Select State *</option>
                        </select>
                    </div>
                    <input type="number" class="form-control" id="cl_pincode_${claimantRowCount}" name="cl_pincode_${claimantRowCount}"  autocomplete="off" maxlength="6" value="" placeholder="Pin Code *">
                </td>
                <td>
                    <input type="email" class="form-control" id="cl_email_${claimantRowCount}" name="cl_email_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Email ID *">
                </td>
                <td>
                    <input type="number" class="form-control" id="cl_mobile_number_${claimantRowCount}" name="cl_mobile_number_${claimantRowCount}"  autocomplete="off" minlength="10" maxlength="10" value="" placeholder="Mobile Number *">
                </td>
                <td class="text-center">
                    <button type="button" class="btn-remove-claimant-row btn btn-danger btn-sm" id="btn_${claimantRowCount}" data-row-number="${claimantRowCount}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        claimantRowCountArray.push(claimantRowCount);
        $("#claimants-table tbody").append(tr);
        $(".select2").select2();
    }

    $(document).on("change", ".res_address_country", function() {
        var country_code = $(this).val();
        var rowCount = $(this).data("row-count");
        if (country_code) {
            var states = getStatesUsingCountryCode(country_code);

            var options = '<option value="">Select State</option>';
            $.each(states, function(index, state) {
                options +=
                    '<option value="' + state.id + '">' + state.name + "</option>";
            });
            $("#res_state_" + rowCount).html(options);
            $("#res_state_" + rowCount).select2();
        }
    });

    function generateRespondentRow(respondentRowCount) {
        var countries = getCountries();

        var countrySelect = `<select class="form-control select2 res_address_country mb-1" name="res_country_${respondentRowCount}" id="res_country_${respondentRowCount}" data-row-count="${respondentRowCount}"><option value="">Select Country *</option>`;
        $.each(countries, function(index, country) {
            countrySelect +=
                '<option value="' + country.iso2 + '">' + country.name + "</option>";
        });
        countrySelect += "</select>";

        var tr = `
            <tr class="claimants-row" id="res-tr-${respondentRowCount}">
                <td>
                    <input type="text" class="form-control" id="res_name_${respondentRowCount}" name="res_name_${respondentRowCount}"  autocomplete="off" maxlength="255" placeholder="Full Name *">
                </td>
                <td>
                    <input type="text" class="form-control mb-1" id="res_address_one_${respondentRowCount}" name="res_address_one_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 1 *">
                    <input type="text" class="form-control" id="res_address_two_${respondentRowCount}" name="res_address_two_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 2">
                </td>
                <td>
                    <div class="mb-1">
                        ${countrySelect}
                    </div>

                    <div class="mb-1">
                        <select class="form-control select2 mb-1" id="res_state_${respondentRowCount}" name="res_state_${respondentRowCount}">
                            <option>Select State *</option>
                        </select>
                    </div>
                    <input type="number" class="form-control" id="res_pincode_${respondentRowCount}" name="res_pincode_${respondentRowCount}"  autocomplete="off" maxlength="6" value="" placeholder="Pin Code *">
                </td>
                <td>
                    <input type="email" class="form-control" id="res_email_${respondentRowCount}" name="res_email_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Email ID *">
                </td>
                <td>
                    <input type="number" class="form-control" id="res_mobile_number_${respondentRowCount}" name="res_mobile_number_${respondentRowCount}"  autocomplete="off" minlength="10" maxlength="10" value="" placeholder="Mobile Number *">
                </td>
                <td class="text-center">
                    <button type="button" class="btn-remove-respondent-row btn btn-danger btn-sm" id="btn_${respondentRowCount}" data-row-number="${respondentRowCount}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        respondentRowCountArray.push(respondentRowCount);
        $("#respondents-table tbody").append(tr);
        $(".select2").select2();
    }


    // On clicking add row button
    $('#add-claimant').on('click', function() {
        claimantRowCount += 1;
        generateClaimantRow(claimantRowCount)
    })

    // On clicking add row button
    $('#add-respondent').on('click', function() {
        respondentRowCount += 1;
        generateRespondentRow(respondentRowCount)
    })

    $(document).ready(function() {
        generateClaimantRow(claimantRowCount);
        generateRespondentRow(respondentRowCount);

        // On clicking remove row button
        $(document).on('click', '.btn-remove-claimant-row', function() {
            if (claimantRowCountArray.length > 1) {
                var rowNumber = $(this).data('row-number');
                $('#cl-tr-' + rowNumber).remove();

                claimantRowCountArray = claimantRowCountArray.filter(function(item) {
                    return item !== rowNumber
                })
            } else {
                toastr.error('You cannot remove last remaining row from table.')
            }
        })

        // On clicking remove row button
        $(document).on('click', '.btn-remove-respondent-row', function() {
            if (respondentRowCountArray.length > 1) {
                var rowNumber = $(this).data('row-number');
                $('#res-tr-' + rowNumber).remove();

                respondentRowCountArray = respondentRowCountArray.filter(function(item) {
                    return item !== rowNumber
                })
            } else {
                toastr.error('You cannot remove last remaining row from table from respondant table.')
            }
        })
    })
</script>

<script>
    function reset_domestic_international_data() {

        $('#arb_total_fees').val('')
        $('#arb_your_share_fees').val('')
        $('#adm_charges').val('')
        $('#your_share').val('')
        $('#your_payable_share').val('')


        $('#int_arb_total_fees_rupee').val('')
        $('#int_arb_total_fees_dollar').val('')

        $('#int_arb_your_share_fees_rupee').val('');
        $('#int_arb_your_share_fees_dollar').val('');

        $('#int_total_adm_charges_dollar').val('');
        $('#int_total_adm_charges_rupee').val('');

        $('#int_each_party_adm_charges_dollar').val('');
        $('#int_each_party_adm_charges_rupee').val('');

        $('#int_your_share_rupee').val('')
        $('#int_your_share_dollar').val('')

        $('#int_your_payable_share_rupee').val('')
        $('#int_your_payable_share_dollar').val('');

        $('.domestic-fees-wrapper-col').hide();
        $('.international-fees-wrapper-col').hide();

        $('#btn_submit').prop('disabled', true)

    }

    // On change case type
    $(document).on('change', '#case_type', function() {
        let case_type = $(this).val();
        console.log(case_type)
        $('#emergency_case_details_col').hide()
        if (case_type == 'EMERGENCY') {
            $('#emergency_case_details_col').show()
        }
    })

    $('#claim_amount').on('keyup', function() {
        $('#btn_submit').prop('disabled', true)
        $('.domestic-fees-wrapper-col').hide();
        $('.international-fees-wrapper-col').hide();

        reset_domestic_international_data();
    })

    $('#type_of_arbitration').on('change', function() {
        $('.domestic-fees-wrapper-col').hide();
        $('.international-fees-wrapper-col').hide();

        $('#claim_amount_symbol').text('');
        if ($(this).val() == 'DOMESTIC') {
            $('#claim_amount_symbol').text('(Rs.)');
        }
        if ($(this).val() == 'INTERNATIONAL') {
            $('#claim_amount_symbol').text('($)');
        }
        reset_domestic_international_data();
    })

    $('#arbitral_tribunal_strength').on('change', function() {

        $('.domestic-fees-wrapper-col').hide();
        $('.international-fees-wrapper-col').hide();

        reset_domestic_international_data();
    })

    // $('#adm_charges').on('keyup', function() {
    //     var adm_charges = $(this).val();

    //     $('#your_payable_share').val('');
    //     if (adm_charges) {
    //         $('#your_payable_share').val(parseInt($('#arb_your_share_fees').val()) + parseInt(adm_charges));
    //     }
    // })

    // ======================================================
    // Calculate fees
    $('#caluclate_fees').on('click', function() {
        let claim_amount = $('#claim_amount').val();
        let arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();
        let type_of_arbitration = $('#type_of_arbitration').val();
        let case_type = $('#case_type').val();

        // Generate the arbitrator percentage by getting the percentage based on case type =================================================
        console.log(case_type)
        let arb_fees_percentage = generate_arb_fees_percentage(case_type);
        $('.arb_fees_percentage').text(arb_fees_percentage);

        // Reset the domestice international data =================
        reset_domestic_international_data();
        $('.domestic-fees-wrapper-col').hide();
        $('.international-fees-wrapper-col').hide();

        // Check for the condition ================================
        if (!arbitral_tribunal_strength || !claim_amount || !type_of_arbitration || !case_type) {
            toastr.error('Please select the arbitral tribunal strength, case type, claim amount and type of arbitration.');
            return;
        }

        $.ajax({
            url: base_url + 'calculate-fees/new-reference',
            type: 'POST',
            data: {
                claim_amount: claim_amount,
                arbitral_tribunal_strength: arbitral_tribunal_strength,
                type_of_arbitration: type_of_arbitration,
                case_type: case_type
            },
            success: function(response) {
                var response = JSON.parse(response);

                if (response.status == true) {
                    var data = response.data;

                    if (type_of_arbitration == 'DOMESTIC') {
                        $('.domestic_adm_charges_row').show();

                        $('#arb_total_fees').val(response.data.total_arbitrator_fees);
                        $('#arb_your_share_fees').val(response.data.each_party_share_in_arb_fees);
                        $('#adm_charges').val(response.data.administrative_charges);
                        $('#your_share').val(response.data.each_party_payable);
                        $('#your_payable_share').val(response.data.your_payable_share);

                        $('.domestic-fees-wrapper-col').show();
                    }
                    if (type_of_arbitration == 'INTERNATIONAL') {
                        $('.international_adm_charges_row').show();

                        $('#int_arb_total_fees_rupee').val(response.data.total_arbitrator_fees_rupee);
                        $('#int_arb_total_fees_dollar').val(response.data.total_arbitrator_fees_dollar);

                        $('#int_arb_your_share_fees_rupee').val(response.data.each_party_share_in_arb_fees_rupee);
                        $('#int_arb_your_share_fees_dollar').val(response.data.each_party_share_in_arb_fees_dollar);

                        $('#int_total_adm_charges_dollar').val(response.data.administrative_charges_dollar);
                        $('#int_total_adm_charges_rupee').val(response.data.administrative_charges_rupee);

                        $('#int_each_party_adm_charges_dollar').val(response.data.each_party_administrative_charges_dollar);
                        $('#int_each_party_adm_charges_rupee').val(response.data.each_party_administrative_charges_rupee);

                        $('#int_your_share_rupee').val(response.data.each_party_payable_rupee);
                        $('#int_your_share_dollar').val(response.data.each_party_payable_dollar);

                        $('#int_your_payable_share_rupee').val(response.data.your_payable_share_rupee);
                        $('#int_your_payable_share_dollar').val(response.data.your_payable_share_dollar);

                        $('.international-fees-wrapper-col').show();
                    }

                    $('#btn_submit').prop('disabled', false)
                } else if (response.status == 'validation_error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: response.msg
                    })
                } else if (response.status == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.msg
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Server Error'
                    })
                }
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Server Error, please try again'
                })
            }
        });
    })

    $(document).ready(function() {
        // $('#case_no_dropdown').trigger("change");

        $('#file_new_case_form').validate({
            errorClass: 'is-invalid text-danger',
            rules: {
                type_of_arbitration: {
                    required: true
                },

                case_type: {
                    required: true
                },

                claim_amount: {
                    required: true
                },
                arb_total_fees: {
                    required: true
                },
                arb_your_share_fees: {
                    required: true
                },
                adm_charges: {
                    required: true
                },
                your_share: {
                    required: true
                },
                your_payable_share: {
                    required: true
                },

            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('file_new_case_form'));
                formData.append('claimant_row_count_array', JSON.stringify(claimantRowCountArray))
                formData.append('respondant_row_count_array', JSON.stringify(respondentRowCountArray))

                var url = ($('#op_type').val() == 'EDIT') ? base_url + 'efiling/new-reference/update' : base_url + 'efiling/new-reference/store ';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        var response = JSON.parse(response);
                        if (response.status == true) {
                            window.location.href = response.redirect_link;

                        } else if (response.status == 'validation_error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: response.msg
                            })
                        } else if (response.status == false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.msg
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Server Error'
                            })
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Server Error, please try again'
                        })
                    }
                });
            }
        })
    })
</script>