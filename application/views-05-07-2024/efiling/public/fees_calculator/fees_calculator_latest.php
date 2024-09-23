<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="card mt-4 mb-4 bg-white rounded shadow">
                    <div class="card-header pt-4 flex-column align-items-start">
                        <h5 class="card-title text-danger">Fees Calulator Latest</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 RightSection">
                                <div class="col-xs-12 mb-55 mt-30">
                                    <h2 class="text-uppercase">Calculate <span class="color_red">Your Fees</span></h2>
                                    <p>(It will show only estimated fees.)</p>
                                    <div class="line_1"></div>
                                    <div class="line_2"></div>
                                    <div class="line_3"></div>
                                </div>
                                <div class="row mb-40">
                                    <div id="exTab2" class="container">
                                        <ul class="nav nav-pills" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="fees-tab" data-bs-toggle="tab" data-bs-target="#fees-tab-pane" type="button" role="tab" aria-controls="fees-tab-pane" aria-selected="true">Fees Calculation</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency-tab-pane" type="button" role="tab" aria-controls="emergency-tab-pane" aria-selected="false">Emergency</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="false">Summary</button>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="fees-tab-pane" role="tabpanel" aria-labelledby="fees-tab" tabindex="0">
                                                <div class="p-2 mt-1 fee_calc_col_border">
                                                    <form action="" id="fees_calculator_form">
                                                        <div class="row py-4">
                                                            <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                                                <label for="" class="form-label">Type of arbitration <span class="text-danger">*</span></label>
                                                                <select name="type_of_arbitration" id="type_of_arbitration" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="DOMESTIC">Domestic</option>
                                                                    <option value="INTERNATIONAL">International</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                                                <label for="" class="form-label">Claim Amount <span class="text-danger">*</span> <span id="claim_amount_symbol"></span></label>
                                                                <input type="text" name="claim_amount" id="claim_amount" class="form-control" />
                                                                <p class="mb-0">
                                                                    <small class="text-danger" id="claim_amount_info"></small>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                                                <label for="" class="form-label">Case Type <span class="text-danger">*</span></label>
                                                                <select name="case_type" id="case_type" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="GENERAL" selected>General</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-xs-12 form-group text-center mt-3 mb-3">
                                                                <button class="btn btn-danger" type="submit" id="btn_calculate">
                                                                    Calculate
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div id="show_calculated_fees_div" style="display: none;">
                                                        <div id="calculated_fees_content_div">
                                                        </div>
                                                        <div class="">
                                                            <table class="table" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                <tr>
                                                                    <th>Disclaimer: The Arbitrators fees is provisionally assessed by DIAC
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Pay order/D.D. should be in favour of Delhi International Arbitration Centre.
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-danger">The Account details for payment of fees are as under:</th>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong>Account No.:</strong> 15530210000663 <br>
                                                                        <strong>IFSC:</strong> UCBA0001553 (UCO BANK) <br>
                                                                        <strong>MICR Code:</strong> 110028026 <br>
                                                                        <strong>Bank & Branch:</strong> UCO Bank, Delhi High Court, Sher Shah Road, New Delhi-110003
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="emergency-tab-pane" role="tabpanel" aria-labelledby="emergency-tab" tabindex="0">
                                                <div class="mt-5 p-3 fee_calc_col_border">
                                                    <p align="center"><strong><u>SCHEDULE A</u></strong></p>
                                                    <p align="center"><strong><u>Administrative Costs</u></strong></p>
                                                    <p align="center"><strong><u>Emergency Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <p>Fixed Fee&nbsp; <strong>Rs. 5,00,000/-</strong></p>
                                                    <p><strong>Note:&nbsp;&nbsp;</strong>Air fare and cost of stay in hotel of the member(s) of the Arbitral Tribunal are excluded, which are to be equally borne by the parties.</p>
                                                    <p>In addition to the foregoing, the parties shall be required to pay a sum of Rs. 3,500/- per day for use of facilities of the DIAC on the days the Arbitral Tribunal holds its sittings. (International Arbitration and Emergency Arbitration)</p>
                                                    <p>&nbsp;</p>
                                                    <p align="center"><strong><u>SCHEDULE E </u></strong></p>
                                                    <p align="center"><strong><u>Arbitrators Fee in Emergency Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <p><strong>Fixed Fee&nbsp; &nbsp;</strong>15% of the fees payable to the Arbitrator in accordance with the fee structure in Schedule B or D as the case may be.</p>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                                                <div class="mt-5 p-3 fee_calc_col_border">
                                                    <p align="center"><strong><u>SCHEDULE 'C'</u></strong></p>
                                                    <p align="center"><strong><u>Arbitrator’s fees in Summary Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <table class="table table-striped table-bordered dataTable no-footer">
                                                        <tbody>
                                                            <tr>
                                                                <th>Sum in dispute (in Rs.)</th>
                                                                <th>Fees</th>
                                                            </tr>
                                                            <tr>
                                                                <td>Upto Rs. 10,00,000/-</td>
                                                                <td> Rs. 25,000/-</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Above Rs. 10,00,000/-</td>
                                                                <td>As per Schedule B</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    /**
     * Function to show the claim amount information like Rupyee or dollar
     */
    function show_claim_amount_info(type) {
        if (type == "DOMESTIC") {
            $('#claim_amount_symbol').text('(Rs.)')
            $('#claim_amount_info').text('Enter amount in rupees')
        }
        if (type == "INTERNATIONAL") {
            $('#claim_amount_symbol').text('($)')
            $('#claim_amount_info').text('Enter amount in dollars')
        }
    }


    function formatToIndianNumberingSystem(number) {
        let formatter = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR'
        });
        return formatter.format(number);
    }


    $('#type_of_arbitration').on('change', function() {
        var value = $(this).val();
        $('#claim_amount_symbol').text('')
        $('#claim_amount_info').text('')

        show_claim_amount_info(value);
    })

    // -------------------------------------
    $('#claim_amount').on('keyup', function() {
        let claim_amount = $(this).val();
        let type_of_arbitration = $('#type_of_arbitration').val();

        show_claim_amount_info(type_of_arbitration)

        if (claim_amount != '') {
            $('#claim_amount_info').text('')
        }
    })

    $('#fees_calculator_form').on('submit', function(e) {
        e.preventDefault();
        $('#calculated_fees_content_div').html('');
        $('#show_calculated_fees_div').hide();

        var claim_amount = $('#claim_amount').val();
        var type_of_arbitration = $('#type_of_arbitration').val();
        var case_type = $('#case_type').val();
        // var base_url = <?= base_url() ?>;
        // console.log(base_url);
        // var arbitral_tribunal_strength = $('#arbitral_tribunal_strength').val();

        if (claim_amount && type_of_arbitration && case_type) {
            $.ajax({
                url: base_url + '/api/calculate-fees/new-reference',
                type: 'POST',
                data: {
                    claim_amount: claim_amount,
                    type_of_arbitration: type_of_arbitration,
                    case_type: case_type,
                    arbitral_tribunal_strength: 1
                },
                beforeSend: function() {
                    $('#btn_calculate').html('Processing...');
                    $('#btn_calculate').prop('disabled', true);
                },
                complete: function() {
                    $('#btn_calculate').html('Calculate');
                    $('#btn_calculate').prop('disabled', false);
                },
                error: function() {
                    $('#btn_calculate').html('Calculate');
                    $('#btn_calculate').prop('disabled', false);
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    if (response.status == true) {
                        var data = response.data;
                        console.log(response);
                        let content = '';

                        if (type_of_arbitration == 'DOMESTIC') {
                            if (claim_amount > 500000) {

                                let basic_arbitrator_fees = data.basic_arbitrator_fees;
                                let excess_amount_percent = data.excess_amount_percent_details.excess_amount_percent;

                                var tr_ex = '';
                                if (data.excess && data.excess.ceiling_text) {
                                    tr_excess_amount_details = `
                                        <tr class=" fc_border_top  fc_border_bottom">
                                            <th>Total:</th>
                                            <td>${formatToIndianNumberingSystem(data.total_with_basic_plus_excess)}</td>
                                        </tr>
                                        <tr>
                                            <th>${data.excess.ceiling_text}:</th>
                                            <td>${formatToIndianNumberingSystem(data.excess.total_with_basic_plus_excess_ceiling)}</td>
                                        </tr>
                                    `;
                                } else {
                                    tr_excess_amount_details = `
                                                <tr class=" fc_border_top">
                                                    <th>Total:</th>
                                                    <td>${formatToIndianNumberingSystem(data.total_with_basic_plus_excess)}</td>
                                                </tr>
                                        `;
                                }

                                content = `
                                            <table class="table" style="border: 1px solid lightgrey;">
                                                <tr class="  bg-dark text-white">
                                                    <th width="70%">Total Sum in Dispute:</th>
                                                    <td class="text-right">${formatToIndianNumberingSystem(claim_amount)}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Basic Arbitrator’s Fees:</th>
                                                    <td>${formatToIndianNumberingSystem(data.basic_arbitrator_fees)}</td>
                                                </tr>

                                                <tr>
                                                    <th>${data.excess_amount_percent_details.text}:</th>
                                                    <td>${formatToIndianNumberingSystem(excess_amount_percent)}</td>
                                                </tr>

                                                ${tr_excess_amount_details}

                                                <tr class="fc_border_bottom">
                                                    <th>${data.additional_arb_fees_details.text}:</th>
                                                    <td>${formatToIndianNumberingSystem(data.additional_arb_fees_details.aaf_total_fee_percent)}</td>
                                                </tr>
                                                <tr class="fc_border_bottom">
                                                    <th>Total Arbitrator's Fees payable:</th>
                                                    <td>${formatToIndianNumberingSystem(data.total_arbitrator_fees)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party’s Share of Arbitrator's Fees:</th>
                                                    <td>${formatToIndianNumberingSystem(data.each_party_share_in_arb_fees)}</td>
                                                </tr>
                                                <tr class="fc_border_bottom">
                                                    <th>Each Party’s Share of Administrative Costs:</th>
                                                    <td>${formatToIndianNumberingSystem(data.administrative_charges)}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party’s Total Share payable:</th>
                                                    <td>${formatToIndianNumberingSystem(data.each_party_payable)}</td>
                                                </tr>
                                                
                                            </table>
                                        `;
                            }

                            if (claim_amount <= 500000) {
                                content = `
                                        <table class="table" style="border: 1px solid lightgrey;">
                                            <tr style="border-bottom: 4px double grey;">
                                                <td class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</td>
                                                <td>${formatToIndianNumberingSystem(claim_amount)}</td>
                                            </tr>

                                            <tr>
                                                <td>Basic Arbitrator's Fees:</td>
                                                <td>${formatToIndianNumberingSystem(data.basic_arbitrator_fees)}</td>
                                            </tr>

                                            <tr class="fc_border_bottom">
                                                <td>Additional Arbitrator's Fees @ 25% of the above:</td>
                                                <td>${formatToIndianNumberingSystem(data.additional_arb_fees_details.aaf_total_fee_percent)}</td>
                                            </tr>
                                            
                                            <tr class="fc_border_bottom">
                                                <td>Total Arbitrator's Fees payable:</td>
                                                <td>${formatToIndianNumberingSystem(data.total_arbitrator_fees)}</td>
                                            </tr>
                                            <tr>
                                                <td>Each Party’s Share of Arbitrator's Fees:</td>
                                                <td>${formatToIndianNumberingSystem(data.each_party_share_in_arb_fees)}</td>
                                            </tr>
                                            <tr class="fc_border_bottom">
                                                <td>Each Party’s Share of Administrative Costs:</td>
                                                <td>${formatToIndianNumberingSystem(data.administrative_charges)}</td>
                                            </tr>
                                            <tr class="fc_border_bottom">
                                                <td>Each Party’s Total Share payable:</td>
                                                <td>${formatToIndianNumberingSystem(data.each_party_payable)}</td>
                                            </tr>
                                            
                                        </table>
                                    `;
                            }
                        }

                        // IF TYPE OF ARBITRATION IS INTERNATIONAL =========
                        if (type_of_arbitration == 'INTERNATIONAL') {
                            content += `<table class="table" style="border: 1px solid lightgrey;">`;
                            if (claim_amount >= 50000) {
                                content += `
                                                <tr>
                                                    <th colspan="2" style="background-color: #9f2f32; color: white;">Arbitrator Fees</th>
                                                </tr>
                                                <tr>
                                                    <th width="70%">Total Sum in Dispute:</th>
                                                    <td>$${claim_amount}</td>
                                                </tr>
                                                <tr>
                                                    <th>Basic Amount:</th>
                                                    <td>$${data.basic_amount}</td>
                                                </tr>
                                                <tr>
                                                    <th>Excess Amount:</th>
                                                    <td>$${data.excess_amount}</td>
                                                </tr>
                                                <tr>
                                                    <th>Basic Arbitrator’s Fees:</th>
                                                    <td>$${data.basic_arbitrator_fees}</td>
                                                </tr>
                                                <tr>
                                                    <th>${data.excess_amount_percent_details.text}:</th>
                                                    <td>$${data.excess_amount_percent_details.excess_amount_percent}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Arbitrator’s Fees:</th>
                                                    <td>$${data.total_arbitrator_fees_dollar}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party Share in arbitrator fees:</th>
                                                    <td>$${data.each_party_share_in_arb_fees_dollar}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party Payable:</th>
                                                    <td>$${data.each_party_payable_dollar}</td>
                                                </tr>
                                        `;
                            } else {
                                content += `
                                                <tr>
                                                    <th colspan="2" style="background-color: #9f2f32; color: white;">Arbitrator Fees</th>
                                                </tr>
                                                <tr>
                                                    <th width="70%">Total Sum in Dispute:</th>
                                                    <td>$${claim_amount}</td>
                                                </tr>
                                                <tr>
                                                    <th>Arbitrator’s Fees:</th>
                                                    <td>$${data.total_arbitrator_fees_dollar}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party Share in arbitrator fees:</th>
                                                    <td>$${data.each_party_share_in_arb_fees_dollar}</td>
                                                </tr>
                                                <tr>
                                                    <th>Each Party Payable:</th>
                                                    <td>$${data.each_party_payable_dollar}</td>
                                                </tr>
                                        `;
                            }

                            if (data.administrative_charges_details.claim_amount_rupee > 1000000) {
                                content += `<tr>
                                            <th colspan="2" style="background-color: #9f2f32; color: white;">Administrative Cost</th>
                                        </tr>
                                        
                                        <tr>
                                            <th width="70%">Total Sum in Dispute:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.claim_amount_rupee)}</td>
                                        </tr>
                                        <tr>
                                            <th>Basic Amount:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.basic_amount)}</td>
                                        </tr>
                                        <tr>
                                            <th>Excess Amount:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.excess_amount)}</td>
                                        </tr>
                                        <tr>
                                            <th>Basic Administrative Cost:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.basic_administrative_cost)}</td>
                                        </tr>
                                        <tr>
                                            <th>Additional Administrative Cost @${data.administrative_charges_details.excess_amount_percent}% of Excess Amount:	:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.aaf_excess_amount_percent)}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Administrative Cost:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.total_administrative_cost)}</td>
                                        </tr>
                                        <tr>
                                            <th>Each Party Share:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.each_party_share)}</td>
                                        </tr>
                                        <tr>
                                            <th>Each Party Payable:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.each_party_payable)}</td>
                                        </tr>`;
                            } else {
                                content += `<tr>
                                            <th colspan="2" style="background-color: #9f2f32; color: white;">Administrative Cost</th>
                                        </tr>
                                        
                                        <tr>
                                            <th width="70%">Total Sum in Dispute:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.claim_amount_rupee)}</td>
                                        </tr>
                                        <tr>
                                            <th>Administrative Cost:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.total_administrative_cost)}</td>
                                        </tr>
                                        <tr>
                                            <th>Each Party Share:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.each_party_share)}</td>
                                        </tr>
                                        <tr>
                                            <th>Each Party Payable:</th>
                                            <td>Rs.${Math.ceil(data.administrative_charges_details.each_party_payable)}</td>
                                        </tr>`;
                            }

                            content += `</table>`;
                        }


                        $('#calculated_fees_content_div').html(content);
                        $('#show_calculated_fees_div').show();


                    } else if (response.status == 'validation_error') {
                        toastr.error(response.msg)
                    } else {
                        toastr.error('Something went wrong, please try again or contact support team.')
                    }
                }
            })
        } else {
            toastr.error('All fields are required to calculate fees.');
        }
    })
</script>