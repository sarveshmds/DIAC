<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="card mt-4 mb-4 bg-white rounded shadow">
                    <div class="card-header pt-4 flex-column align-items-start">
                        <h5 class="card-title text-danger">Fees Calulator Old</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="row row-eq-height">


                                <!--Right Section-->
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
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="domestic-tab" data-bs-toggle="tab" data-bs-target="#domestic-tab-pane" type="button" role="tab" aria-controls="domestic-tab-pane" aria-selected="true">Domestic</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="international-tab" data-bs-toggle="tab" data-bs-target="#international-tab-pane" type="button" role="tab" aria-controls="international-tab-pane" aria-selected="false">International</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency-tab-pane" type="button" role="tab" aria-controls="emergency-tab-pane" aria-selected="false">Emergency</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="false">Summary</button>
                                                </li>
                                                <!-- <li class="active"><a href="#1" data-toggle="tab">Domestic </a></li>
                                            <li><a href="#2" data-toggle="tab">International</a></li>
                                            <li><a href="#3" data-toggle="tab">Emergency</a></li>
                                            <li><a href="#4" data-toggle="tab">Summary</a></li> -->
                                            </ul>

                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="domestic-tab-pane" role="tabpanel" aria-labelledby="domestic-tab" tabindex="0">
                                                    <div class="col-md-12 col-sm-12 col-xs-12  select-form fee_calc_col_border mt-4 p-2">
                                                        <form class="fee_calc_form" method="POST" action="" id="fee_calc_form">
                                                            <div class="row py-4">
                                                                <div class="mb-3 col-md-4">
                                                                    <label class="form-label" for="fees_type">Select Amount Category</label>
                                                                    <select class="fees_type form-control" id="fees_type">
                                                                        <option>Choose Amount</option>
                                                                        <option value="upto5lakh">Upto Rs. 5 lakhs</option>
                                                                        <option value="5lakhsto20lakhs">Rs. 5 lakhs to 20 lakhs</option>
                                                                        <option value="20lakhsto1crore">Rs. 20 lakhs to 1 Crore</option>
                                                                        <option value="1croreto10crore">Rs. 1 Crore to 10 Crore</option>
                                                                        <option value="10croreto20crore">Rs. 10 Crore to 20 Crore
                                                                        </option>
                                                                        <option value="above20crore">Above Rs. 20 Crore</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3 col-md-4">
                                                                    <label class="form-label" for="claim_amount">Enter Claim Amount</label>
                                                                    <input type="text" name="claim_amount" id="claim_amount" class="form-control claim_amount">
                                                                </div>

                                                                <div class="mb-3 col-md-4">
                                                                    <button type="submit" class="btn btn-danger" id="calc_fees_btn" style="margin-top: 25px; border: none;">Calculate</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- Changes 1) start -> -->
                                                    <div class="row">
                                                        <div class="col-xs-12">

                                                            <!-- Upto 5 lakhs Start -->
                                                            <div class="upto5lakh box fee_calc_col_border" id="upto5lakh_box" style="display: none; margin: 15px auto;">
                                                                <div id="calculated_fees_content_div">

                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <th class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</th>
                                                                                <td><input class="form-control" type="text" name="upto5lakh_totaldispute" id="upto5lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Basic Arbitrator’s Fees: </th>
                                                                                <td>Rs. 45,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator's Fees @ 25% of the above: </th>
                                                                                <td>Rs. 11,250</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Total Arbitrator's Fees payable: </th>
                                                                                <td>Rs. 56,250</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Arbitrator's Fees:</th>
                                                                                <td>Rs. 28,125</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Administrative Costs:</th>
                                                                                <td>Rs. 10,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Total Share payable:</th>
                                                                                <td>38,125</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Note: Administrative Expenses of DIAC as revised
                                                                                    w.e.f 17.11.2020</th>
                                                                                <td></td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                    <tr>
                                                                        <th>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</th>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</th>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </th>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- Upto 5 lakhs End -->

                                                            <!-- 5 Lakhs to 20 Lakhs Start -->
                                                            <div class="5lakhsto20lakhs box fee_calc_col_border" style="display: none;  margin: 15px auto;" id="5lakhsto20lakhs_box">
                                                                <div id="calculated_fees_content_div">
                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <td class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="5lakhsto20lakhs_totaldispute" id="5lakhsto20lakhs_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <!-- <tr>
                                                                            <td>Basic Amount: </td>
                                                                            <td><span class="5lakhsto20lakhs_basic_amount"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Excess Amount:</td>
                                                                            <td><span class="5lakhsto20lakhs_excess_amount"></span>
                                                                            </td>
                                                                        </tr> -->
                                                                            <tr>
                                                                                <th>Basic Arbitrator’s Fees:</th>
                                                                                <td><span class="5lakhsto20lakhs_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator's Fees @ 3.5% of Excess Amount:
                                                                                </th>
                                                                                <td><span class="5lakhsto20lakhs_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom fc_border_top">
                                                                                <th>Total:</th>
                                                                                <td class="5lakhsto20lakhs_totalAmount"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator's Fees @ 25% of the above:</th>
                                                                                <td><span class="5lakhsto20lakhs_aarf_total_fees_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom">
                                                                                <th>Total Arbitrator's Fees payable:</th>
                                                                                <td><span class="5lakhsto20lakhs_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Arbitrator's Fees:</th>
                                                                                <td><span class="5lakhsto20lakhs_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Administrative Costs:</th>
                                                                                <td><span class="5lakhsto20lakhs_miscellaneous_expenses"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Total Share payable:</th>
                                                                                <td><span class="5lakhsto20lakhs_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">

                                                                    <tr>
                                                                        <th>Note: Administrative Expenses of DIAC as revised
                                                                            w.e.f 17.11.2020</th>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</th>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</th>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </th>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- 5 Lakhs to 20 Lakhs End -->


                                                            <!-- 20 Lakhs to 1 crore Start -->
                                                            <div class="20lakhsto1crore box fee_calc_col_border" style="display: none;  margin: 15px auto;" id="20lakhsto1crore_box">
                                                                <div id="calculated_fees_content_div">
                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <th class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</th>
                                                                                <td><input class="form-control" type="text" name="20lakhsto1crore_totaldispute" id="20lakhsto1crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <!-- <tr>
                                                                            <td>Basic Amount: </td>
                                                                            <td><span class="20lakhsto1crore_basic_amount"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Excess Amount:</td>
                                                                            <td><span class="20lakhsto1crore_excess_amount"></span>
                                                                            </td>
                                                                        </tr> -->
                                                                            <tr>
                                                                                <th>Basic Arbitrator’s Fees:</th>
                                                                                <td><span class="20lakhsto1crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator’s Fees @3% of Excess Amount:
                                                                                </th>
                                                                                <td><span class="20lakhsto1crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom fc_border_top">
                                                                                <th>Total:</th>
                                                                                <td class="20lakhsto1crore_totalAmount"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator's Fees @ 25% of the above:</th>
                                                                                <td><span class="20lakhsto1crore_aarf_total_fees_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom">
                                                                                <th>Total Arbitrator's Fees payable:</th>
                                                                                <td><span class="20lakhsto1crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Arbitrator's Fees:</th>
                                                                                <td><span class="20lakhsto1crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Share of Administrative Costs:</th>
                                                                                <td><span class="20lakhsto1crore_miscellaneous_expenses"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party’s Total Share payable:</th>
                                                                                <td><span class="20lakhsto1crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                    <tr>
                                                                        <td>Note: Administrative Expenses of DIAC as revised
                                                                            w.e.f 17.11.2020</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- 5 Lakhs to 20 Lakhs End -->


                                                            <!-- 1 Crore to 10 Crore Start -->
                                                            <div class="1croreto10crore box fee_calc_col_border" style="display: none;  margin: 15px auto;" id="1croreto10crore_box">
                                                                <div id="calculated_fees_content_div">
                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <td class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="1croreto10crore_totaldispute" id="1croreto10crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <!-- <tr>
                                                                            <td>Basic Amount: </td>
                                                                            <td><span class="1croreto10crore_basic_amount"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Excess Amount:</td>
                                                                            <td><span class="1croreto10crore_excess_amount"></span>
                                                                            </td>
                                                                        </tr> -->
                                                                            <tr>
                                                                                <td>Basic Arbitrator's Fees:</td>
                                                                                <td><span class="1croreto10crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 1% of Excess Amount:
                                                                                </td>
                                                                                <td><span class="1croreto10crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom fc_border_top">
                                                                                <td>Total:</td>
                                                                                <td class="1croreto10crore_totalAmount"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 25% of the above:</td>
                                                                                <td><span class="1croreto10crore_aarf_total_fees_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom">
                                                                                <td>Total Arbitrator's Fees payable:</td>
                                                                                <td><span class="1croreto10crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Arbitrator's Fees:</td>
                                                                                <td><span class="1croreto10crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Administrative Costs:</td>
                                                                                <td><span class="1croreto10crore_miscellaneous_expenses"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Total Share payable:</td>
                                                                                <td><span class="1croreto10crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                    <tr>
                                                                        <td>Note: Administrative Expenses of DIAC as revised
                                                                            w.e.f 17.11.2020</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- 1 Crore to 10 Crore End -->

                                                            <!-- 10 Crore to 20 Crore Start -->
                                                            <div class="10croreto20crore box fee_calc_col_border" style="display: none; margin: 15px auto;" id="10croreto20crore_box">
                                                                <div id="calculated_fees_content_div">
                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <td class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="10croreto20crore_totaldispute" id="10croreto20crore_totaldispute" readonly>
                                                                                </td>
                                                                            </tr>
                                                                            <!-- <tr>
                                                                            <td>Basic Amount: </td>
                                                                            <td><span class="10croreto20crore_basic_amount"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Excess Amount:</td>
                                                                            <td><span class="10croreto20crore_excess_amount"></span>
                                                                            </td>
                                                                        </tr> -->
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="10croreto20crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 0.75% of Excess Amount:</td>
                                                                                <td><span class="10croreto20crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom fc_border_top">
                                                                                <td>Total:</td>
                                                                                <td class="10croreto20crore_totalAmount"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 25% of the above:</td>
                                                                                <td><span class="10croreto20crore_aarf_total_fees_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom">
                                                                                <td>Total Arbitrator's Fees payable:</td>
                                                                                <td><span class="10croreto20crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Arbitrator's Fees:</td>
                                                                                <td><span class="10croreto20crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Administrative Costs:</td>
                                                                                <td><span class="10croreto20crore_miscellaneous_expenses"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Total Share payable:</td>
                                                                                <td><span class="10croreto20crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                    <tr>
                                                                        <td>Note: Administrative Expenses of DIAC as revised
                                                                            w.e.f 17.11.2020</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- 10 Crore to 20 Crore End -->

                                                            <!-- Above 20 Crore Start -->
                                                            <div class="above20crore box fee_calc_col_border" style="display: none; margin: 15px auto;" id="above20crore_box">
                                                                <div id="calculated_fees_content_div">
                                                                    <table class="property-d-table table" cellpadding="6" style="border:1px solid grey">
                                                                        <tbody>
                                                                            <tr class="bg-dark">
                                                                                <td class="fc_border_bottom bg-dark text-white">Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="above20crore_totaldispute" id="above20crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <!-- <tr>
                                                                            <td>Basic Amount: </td>
                                                                            <td><span class="above20crore_basic_amount"></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Excess Amount:</td>
                                                                            <td><span class="above20crore_excess_amount"></span>
                                                                            </td>
                                                                        </tr> -->
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="above20crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 0.5% of Excess Amount:
                                                                                </td>
                                                                                <td><span class="above20crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom fc_border_top">
                                                                                <td>Total:</td>
                                                                                <td class="above20crore_totalAmount"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator's Fees @ 25% of the above:</td>
                                                                                <td><span class="above20crore_aarf_total_fees_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fc_border_bottom">
                                                                                <td>Total Arbitrator's Fees payable:</td>
                                                                                <td><span class="above20crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Arbitrator's Fees:</td>
                                                                                <td><span class="above20crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Share of Administrative Costs:</td>
                                                                                <td><span class="above20crore_miscellaneous_expenses"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party’s Total Share payable:</td>
                                                                                <td><span class="above20crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                    </table>
                                                                </div>
                                                                <table class="property-d-table table" cellpadding="6" style="border: 1px solid lightgrey; margin-top: 20px;">
                                                                    <tr>
                                                                        <td>Note: Administrative Expenses of DIAC as revised
                                                                            w.e.f 17.11.2020</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Disclaimer: The Arbitrators fees is provisionally
                                                                            assessed by DIAC</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pay order/D.D. should be in favour of Delhi
                                                                            International Arbitration Centre.</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <p class="note">The Account details for payment of
                                                                                fees are as under:</p>
                                                                            <strong>Account No.: </strong> 15530210000663 <br />
                                                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                            <br />
                                                                            <strong>MICR Code: </strong> 110028026 <br />
                                                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                            High Court, Sher Shah Road, New Delhi-110003 <br />
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- Above 20 Crore End -->

                                                        </div>
                                                    </div>
                                                    <!-- Changes 1) End -> -->

                                                </div>
                                                <div class="tab-pane fade" id="international-tab-pane" role="tabpanel" aria-labelledby="international-tab" tabindex="0">
                                                    <div class="col-md-12 col-sm-12 col-xs-12  select-form fee_calc_col_border mt-4 p-2">
                                                        <form class="int_arb_fee_calc_form" method="POST" action="" id="int_arb_fee_calc_form">
                                                            <div class="row mt-4 p-2">
                                                                <div class="mb-3 col-md-4">
                                                                    <label class="form-label" for="int_arb_fees_type">Select Cost Type</label>
                                                                    <select class="int_cost_type form-control" id="int_cost_type">
                                                                        <option>Select Cost Type</option>
                                                                        <option value="international_cost">International Arbitrators'
                                                                            Fees</option>
                                                                        <option value="administrative_cost">Administrative Cost</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3 col-md-4 int_select_amount_box" style="display: none;">

                                                                    <div class="int_cost_amount_box" style="display: none;">
                                                                        <!-- International Cost Amount -->
                                                                        <label class="form-label" for="int_cost_fees_type">Select Amount</label>
                                                                        <select class="int_cost_fees_type form-control" id="int_cost_fees_type">
                                                                            <option>Choose Amount</option>
                                                                            <option value="upto50_thous">Upto $50,000</option>
                                                                            <option value="50_thous_to_1_lakh">$50,000 to $1,00,000
                                                                            </option>
                                                                            <option value="1_lakh_to_5_lakh">$1,00,000 to $5,00,000
                                                                            </option>
                                                                            <option value="5_lakh_to_10_lakh">$5,00,000 to $10,00,000
                                                                            </option>
                                                                            <option value="10_lakh_to_20_lakh">$10,00,000 to $20,00,000
                                                                            </option>
                                                                            <option value="20_lakh_to_50_lakh">$20,00,000 to $50,00,000
                                                                            </option>
                                                                            <option value="50_lakh_to_1_crore">$50,00,000 to
                                                                                $1,00,00,000</option>
                                                                            <option value="1_crore_to_5_crore">$1,00,00,000 to
                                                                                $5,00,00,000</option>
                                                                            <option value="5_crore_to_8_crore">$5,00,00,000 to
                                                                                $8,00,00,000</option>
                                                                            <option value="8_crore_to_10_crore">$8,00,00,000 to
                                                                                $10,00,00,000</option>
                                                                            <option value="10_crore_to_above">$10,00,00,000 and above
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="int_adminis_cost_amount_box" style="display: none;">
                                                                        <!-- Administrative Cost Amount -->
                                                                        <label class="form-label" for="int_arb_arb_fees_type">Select Amount</label>
                                                                        <select class="int_arb_arb_fees_type form-control" id="int_arb_arb_fees_type">
                                                                            <option>Choose Amount</option>
                                                                            <option value="upto10lakh">Upto Rs. 10 lakhs</option>
                                                                            <option value="10lakhsto50lakhs">Rs. 10 lakhs to 50 lakhs
                                                                            </option>
                                                                            <option value="50lakhsto1crore">Rs. 50 lakhs to 1 Crore
                                                                            </option>
                                                                            <option value="1croreto10crore">Rs. 1 Crore to 10 Crore
                                                                            </option>
                                                                            <option value="above10crore">Above Rs. 10 Crore</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3 col-md-4">
                                                                    <label class="form-label" for="int_arb_arb_claim_amount" class="amount_type">Enter
                                                                        Claim Amount</label>
                                                                    <input class="form-control" type="text" name="int_arb_arb_claim_amount" id="int_arb_arb_claim_amount" class="form-control claim_amount">
                                                                </div>

                                                                <div class="mb-3 text-center">
                                                                    <button type="submit" class="btn btn-danger" id="int_arb_arb_calc_fees_btn" style="margin-top: 25px; border: none;">Calculate</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <section class="int_af_cost_container" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-xs-12">

                                                                <!-- Upto 50,000 Start -->
                                                                <div class="int_af_cost_upto50000 box fee_calc_col_border" id="int_af_cost_upto50000_box" style="display: none; background-color: lightgrey; margin: 15px auto;">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_upto50000_totaldispute" id="int_af_cost_upto50000_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Administrative Cost: </td>
                                                                                <td>$ 30,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share Of Arbitrator’s Fees:</td>
                                                                                <td>$ 2,250</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td>$ 2,250</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <span class="int_af_cost_note">
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- Upto 50,000 End -->

                                                                <!-- 50,000 to 1,00,000 Start -->
                                                                <div class="int_af_cost_50_to_1_lakh box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_50_to_1_lakh_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_50_to_1_lakh_totaldispute" id="int_af_cost_50_to_1_lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @6% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_50_to_1_lakh_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 50,000 to 1,00,000 End -->


                                                                <!-- 1,00,000 to 5,00,000 Start -->
                                                                <div class="int_af_cost_1_to_5_lakh box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_1_to_5_lakh_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_1_to_5_lakh_totaldispute" id="int_af_cost_1_to_5_lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @3.5% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees (in USD):</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_1_to_5_lakh_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 1,00,000 to 5,00,000 End -->


                                                                <!-- 5,00,000 to 10,00,000 Start -->
                                                                <div class="int_af_cost_5_to_10_lakh box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_5_to_10_lakh_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input type="text" name="int_af_cost_5_to_10_lakh_totaldispute" id="int_af_cost_5_to_10_lakh_totaldispute" readonly class="form-control"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @2.5% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_5_to_10_lakh_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 5,00,000 to 10,00,000 End -->


                                                                <!-- 10,00,000 to 20,00,000 Start -->
                                                                <div class="int_af_cost_10_to_20_lakh box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_10_to_20_lakh_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_10_to_20_lakh_totaldispute" id="int_af_cost_10_to_20_lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @1.5% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_10_to_20_lakh_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 10,00,000 to 20,00,000 End -->


                                                                <!-- 20,00,000 to 50,00,000 Start -->
                                                                <div class="int_af_cost_20_to_50_lakh box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_20_to_50_lakh_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_20_to_50_lakh_totaldispute" id="int_af_cost_20_to_50_lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.75% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_20_to_50_lakh_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 20,00,000 to 50,00,000 End -->


                                                                <!-- 50,00,000 to 1,00,00,000 Start -->
                                                                <div class="int_af_cost_50_to_1_crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_50_to_1_crore_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_50_to_1_crore_totaldispute" id="int_af_cost_50_to_1_crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.35% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_50_to_1_crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 50,00,000 to 1,00,00,000 End -->

                                                                <!-- 1,00,00,000 to 5,00,00,000 Start -->
                                                                <div class="int_af_cost_1_to_5_crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_1_to_5_crore_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_1_to_5_crore_totaldispute" id="int_af_cost_1_to_5_crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.15% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_1_to_5_crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 1,00,00,000 to 5,00,00,000 End -->


                                                                <!-- 5,00,00,000 to 8,00,00,000 Start -->
                                                                <div class="int_af_cost_5_to_8_crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_5_to_8_crore_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_5_to_8_crore_totaldispute" id="int_af_cost_5_to_8_crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.075% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_5_to_8_crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 5,00,00,000 to 8,00,00,000 End -->


                                                                <!-- 8,00,00,000 to 10,00,00,000 Start -->
                                                                <div class="int_af_cost_8_to_10_crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_8_to_10_crore_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_8_to_10_crore_totaldispute" id="int_af_cost_8_to_10_crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.03% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_8_to_10_crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 8,00,00,000 to 10,00,00,000 End -->


                                                                <!-- 10,00,00,000 and above Start -->
                                                                <div class="int_af_cost_10_to_above box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_af_cost_10_to_above_box">
                                                                    <table class="int_af_cost_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_10_to_above_totaldispute" id="int_af_cost_10_to_above_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_10_to_above_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_10_to_above_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_10_to_above_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.02% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_10_to_above_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_10_to_above_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_10_to_above_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_10_to_above_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 8,00,00,000 to 10,00,00,000 End -->


                                                                <!-- Above 10 Crore Start -->
                                                                <div class="int_af_cost_above10crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_arb_arb_above10crore_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_af_cost_above10crore_totaldispute" id="int_af_cost_above10crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_af_cost_above10crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_af_cost_above10crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_above10crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.15% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_af_cost_above10crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_af_cost_above10crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_af_cost_above10crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_af_cost_above10crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="int_af_cost_note"></span>
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- Above 20 Crore End -->

                                                            </div>
                                                        </div>
                                                    </section>
                                                    <!-- International Cost End -->

                                                    <!-- International Administrative Cost Start -->

                                                    <section class="int_adminis_container" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-xs-12">

                                                                <!-- Upto 10 lakhs Start -->
                                                                <div class="int_arb_arb_upto10lakh box fee_calc_col_border" id="int_arb_arb_upto10lakh_box" style="display: none; background-color: lightgrey; margin: 15px auto;">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_arb_arb_upto10lakh_totaldispute" id="int_arb_arb_upto10lakh_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Administrative Cost: </td>
                                                                                <td>Rs. 30,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share Of Arbitrator’s Fees:</td>
                                                                                <td>Rs. 15,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td>Rs. 15,000</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">Pay order/D.D. should be in favour
                                                                                    of Delhi International Arbitration Centre.</td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <p class="note">Fixed Fee: Rs. 30,000/- (To be
                                                                                        paid along with the request for arbitration)
                                                                                    </p>
                                                                                    <p class="note">The Account details for payment
                                                                                        of fees are as under:</p>
                                                                                    <strong>Account No.: </strong> 15530210000663
                                                                                    <br />
                                                                                    <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                                    <br />
                                                                                    <strong>MICR Code: </strong> 110028026 <br />
                                                                                    <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                                    High Court, Sher Shah Road, New Delhi-110003
                                                                                    <br />
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- Upto 10 lakhs End -->

                                                                <!-- 10 Lakhs to 50 Lakhs Start -->
                                                                <div class="int_arb_arb_10lakhsto50lakhs box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_arb_arb_10lakhsto50lakhs_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_arb_arb_10lakhsto50lakhs_totaldispute" id="int_arb_arb_10lakhsto50lakhs_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @1% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_arb_arb_10lakhsto50lakhs_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pay order/D.D. should be in favour of Delhi
                                                                                    International Arbitration Centre.</td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <p class="note">Fixed Fee: Rs. 30,000/- (To be
                                                                                        paid along with the request for arbitration)
                                                                                    </p>
                                                                                    <p class="note">The Account details for payment
                                                                                        of fees are as under:</p>
                                                                                    <strong>Account No.: </strong> 15530210000663
                                                                                    <br />
                                                                                    <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                                    <br />
                                                                                    <strong>MICR Code: </strong> 110028026 <br />
                                                                                    <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                                    High Court, Sher Shah Road, New Delhi-110003
                                                                                    <br />
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 10 Lakhs to 50 Lakhs End -->


                                                                <!-- 50 Lakhs to 1 crore Start -->
                                                                <div class="int_arb_arb_50lakhsto1crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_arb_arb_50lakhsto1crore_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_arb_arb_50lakhsto1crore_totaldispute" id="int_arb_arb_50lakhsto1crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.5% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_arb_arb_50lakhsto1crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pay order/D.D. should be in favour of Delhi
                                                                                    International Arbitration Centre.</td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <p class="note">Fixed Fee: Rs. 30,000/- (To be
                                                                                        paid along with the request for arbitration)
                                                                                    </p>
                                                                                    <p class="note">The Account details for payment
                                                                                        of fees are as under:</p>
                                                                                    <strong>Account No.: </strong> 15530210000663
                                                                                    <br />
                                                                                    <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                                    <br />
                                                                                    <strong>MICR Code: </strong> 110028026 <br />
                                                                                    <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                                    High Court, Sher Shah Road, New Delhi-110003
                                                                                    <br />
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 50 Lakhs to 1 Crore End -->


                                                                <!-- 1 Crore to 10 Crore Start -->
                                                                <div class="int_arb_arb_1croreto10crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_arb_arb_1croreto10crore_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Total Sum in Dispute:</td>
                                                                                <td><input class="form-control" type="text" name="int_arb_arb_1croreto10crore_totaldispute" id="int_arb_arb_1croreto10crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Amount: </td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Excess Amount:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Basic Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Additional Arbitrator’s Fees @0.25% of Excess
                                                                                    Amount:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Arbitrator’s Fees:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Share:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Each Party Payable:</td>
                                                                                <td><span class="int_arb_arb_1croreto10crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pay order/D.D. should be in favour of Delhi
                                                                                    International Arbitration Centre.</td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <p class="note">Fixed Fee: Rs. 30,000/- (To be
                                                                                        paid along with the request for arbitration)
                                                                                    </p>
                                                                                    <p class="note">The Account details for payment
                                                                                        of fees are as under:</p>
                                                                                    <strong>Account No.: </strong> 15530210000663
                                                                                    <br />
                                                                                    <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                                    <br />
                                                                                    <strong>MICR Code: </strong> 110028026 <br />
                                                                                    <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                                    High Court, Sher Shah Road, New Delhi-110003
                                                                                    <br />
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- 1 Crore to 10 Crore End -->



                                                                <!-- Above 10 Crore Start -->
                                                                <div class="int_arb_arb_above10crore box fee_calc_col_border" style="display: none; background-color: lightgrey; margin: 15px auto;" id="int_arb_arb_above10crore_box">
                                                                    <table class="int_arb_arb_property-d-table table" cellpadding="6">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th>Total Sum in Dispute:</th>
                                                                                <td><input class="form-control" type="text" name="int_arb_arb_above10crore_totaldispute" id="int_arb_arb_above10crore_totaldispute" readonly></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Basic Amount: </th>
                                                                                <td><span class="int_arb_arb_above10crore_basic_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Excess Amount:</th>
                                                                                <td><span class="int_arb_arb_above10crore_excess_amount"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Basic Arbitrator’s Fees:</th>
                                                                                <td><span class="int_arb_arb_above10crore_basic_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Additional Arbitrator’s Fees @0.15% of Excess
                                                                                    Amount:</th>
                                                                                <td><span class="int_arb_arb_above10crore_aarf_excess_amount_percent"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Total Arbitrator’s Fees:</th>
                                                                                <td><span class="int_arb_arb_above10crore_total_arbitrator_fees"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party Share:</th>
                                                                                <td><span class="int_arb_arb_above10crore_each_party_share"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Each Party Payable:</th>
                                                                                <td><span class="int_arb_arb_above10crore_each_party_payable"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Pay order/D.D. should be in favour of Delhi
                                                                                    International Arbitration Centre.</th>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    <p class="note">Fixed Fee: Rs. 30,000/- (To be
                                                                                        paid along with the request for arbitration)
                                                                                    </p>
                                                                                    <p class="note">The Account details for payment
                                                                                        of fees are as under:</p>
                                                                                    <strong>Account No.: </strong> 15530210000663
                                                                                    <br />
                                                                                    <strong>IFSC: </strong> UCBA0001553 (UCO BANK)
                                                                                    <br />
                                                                                    <strong>MICR Code: </strong> 110028026 <br />
                                                                                    <strong>Bank & Branch: </strong> UCO Bank, Delhi
                                                                                    High Court, Sher Shah Road, New Delhi-110003
                                                                                    <br />
                                                                                </th>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- Above 20 Crore End -->

                                                            </div>
                                                        </div>
                                                    </section>

                                                    <!-- International Administrative Cost End -->

                                                </div>
                                                <div class="tab-pane fade" id="emergency-tab-pane" role="tabpanel" aria-labelledby="emergency-tab" tabindex="0">
                                                    <p align="center"><strong><u>SCHEDULE A</u></strong></p>
                                                    <p align="center"><strong><u>Administrative Costs</u></strong></p>
                                                    <p align="center"><strong><u>Emergency Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <p>Fixed Fee&nbsp; <strong>Rs. 5,00,000/-</strong></p>
                                                    <p><strong>Note:&nbsp;&nbsp;</strong>Air fare and cost of stay in hotel of the
                                                        member(s) of the Arbitral Tribunal are excluded, which are to be equally
                                                        borne by the parties.</p>
                                                    <p>In addition to the foregoing, the parties shall be required to pay a sum of
                                                        Rs. 3,500/- per day for use of facilities of the DIAC on the days the
                                                        Arbitral Tribunal holds its sittings. (International Arbitration and
                                                        Emergency Arbitration)</p>
                                                    <p>&nbsp;</p>
                                                    <p align="center"><strong><u>SCHEDULE E </u></strong></p>
                                                    <p align="center"><strong><u>Arbitrators Fee in Emergency
                                                                Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <p><strong>Fixed Fee&nbsp; &nbsp;</strong>15% of the fees payable to the
                                                        Arbitrator in accordance with the fee structure in Schedule B or D as the
                                                        case may be.</p>

                                                </div>
                                                <div class="tab-pane fade" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                                                    <p align="center"><strong><u>SCHEDULE 'C'</u></strong></p>
                                                    <p align="center"><strong><u>Arbitrator’s fees in Summary
                                                                Arbitration</u></strong></p>
                                                    <p>&nbsp;</p>
                                                    <table class="table table-striped table-bordered dataTable no-footer">
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
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                                <!--./Right Section-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    // Convert amount in money format
    function money_format(amount) {

        var format = new Intl.NumberFormat('en-IN');

        return format.format(amount);
    }

    // Hide all the boxes
    // Show 5 lakhs to 10 lakhs box
    function hide_boxes() {
        $('#upto5lakh_box').css({
            "display": "none"
        })

        $('#5lakhsto20lakhs_box').css({
            "display": "none"
        })

        $('#20lakhsto1crore_box').css({
            "display": "none"
        })

        $('#1croreto10crore_box').css({
            "display": "none"
        })

        $('#10croreto20crore_box').css({
            "display": "none"
        })

        $('#above20crore_box').css({
            "display": "none"
        })
    }

    // Changes 2) start ->
    // =====================================================================
    // Function for 5 lakhs to 20 lakhs
    function calc_5_lakhs_to_20_lakhs() {
        var total_dispute = $('#5lakhsto20lakhs_totaldispute').val();
        var basic_amount = 500000; // 5 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 45000 // Rs. 45000

        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 3.5) / 100;

        // new col Total
        var totalAmount = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Additional Arbitrator’s Fees of 25% of the total fee
        var aaf_total_fee_percent = ((basic_arbitrator_fees + aaf_excess_amount_percent) * 25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent + aaf_total_fee_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        var miscellaneous_expenses = 10000;
        // if(total_dispute > 1000000){
        //     miscellaneous_expenses = 10000; // Rs. 10000
        // }
        // else{
        //     miscellaneous_expenses = 5000; // Rs. 5000
        // }

        // Each party payable 
        var each_party_payable = each_party_share + miscellaneous_expenses;

        /*
            ======== Each party UI share =============
        */

        $('.5lakhsto20lakhs_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.5lakhsto20lakhs_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.5lakhsto20lakhs_basic_arbitrator_fees').text('Rs. ' + money_format(basic_arbitrator_fees));
        $('.5lakhsto20lakhs_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.5lakhsto20lakhs_aarf_total_fees_percent').text('Rs. ' + money_format(aaf_total_fee_percent));
        $('.5lakhsto20lakhs_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.5lakhsto20lakhs_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.5lakhsto20lakhs_miscellaneous_expenses').text('Rs. ' + money_format(miscellaneous_expenses));
        $('.5lakhsto20lakhs_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // new 
        $('.5lakhsto20lakhs_totalAmount').text('Rs. ' + money_format(totalAmount));

        // Hide all the boxes first
        hide_boxes();

        // Show the particular box
        $('#5lakhsto20lakhs_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 20 lakhs to 1 crore
    function calc_20_lakhs_to_1_crore() {
        var total_dispute = $('#20lakhsto1crore_totaldispute').val();
        var basic_amount = 2000000; // 20 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 97500 // Rs. 97500

        // Additional Arbitrator’s Fees @3% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 3) / 100;

        // new col Total
        var totalAmount = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Additional Arbitrator’s Fees of 25% of the total fee
        var aaf_total_fee_percent = ((basic_arbitrator_fees + aaf_excess_amount_percent) * 25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent + aaf_total_fee_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        var miscellaneous_expenses = 20000 // Rs. 20000

        // Each party payable 
        var each_party_payable = each_party_share + miscellaneous_expenses;

        /*
            ======== Each party UI share =============
        */

        $('.20lakhsto1crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.20lakhsto1crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.20lakhsto1crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_arbitrator_fees));
        $('.20lakhsto1crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.20lakhsto1crore_aarf_total_fees_percent').text('Rs. ' + money_format(aaf_total_fee_percent));
        $('.20lakhsto1crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.20lakhsto1crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.20lakhsto1crore_miscellaneous_expenses').text('Rs. ' + money_format(miscellaneous_expenses));
        $('.20lakhsto1crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // new 
        $('.20lakhsto1crore_totalAmount').text('Rs. ' + money_format(totalAmount));

        // Hide all the boxes first
        hide_boxes();

        // Show the particular box
        $('#20lakhsto1crore_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 1 Crore to 10 Crore
    function calc_1_crore_to_10_crore() {
        var total_dispute = $('#1croreto10crore_totaldispute').val();
        var basic_amount = 10000000; // 1 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 337500 // Rs. 337500

        // Additional Arbitrator’s Fees @1% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 1) / 100;

        // new col Total
        var totalAmount = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Additional Arbitrator’s Fees of 25% of the total fee
        var aaf_total_fee_percent = ((basic_arbitrator_fees + aaf_excess_amount_percent) * 25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent + aaf_total_fee_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        var miscellaneous_expenses = 40000 // Rs. 40000
        if (total_dispute > 50000000) {
            miscellaneous_expenses = 50000;
        }

        // Each party payable 
        var each_party_payable = each_party_share + miscellaneous_expenses;

        /*
            ======== Each party UI share =============
        */

        $('.1croreto10crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.1croreto10crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.1croreto10crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_arbitrator_fees));
        $('.1croreto10crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.1croreto10crore_aarf_total_fees_percent').text('Rs. ' + money_format(aaf_total_fee_percent));
        $('.1croreto10crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.1croreto10crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.1croreto10crore_miscellaneous_expenses').text('Rs. ' + money_format(miscellaneous_expenses));
        $('.1croreto10crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // new Col
        $('.1croreto10crore_totalAmount').text('Rs. ' + money_format(totalAmount));

        // Hide all the boxes first
        hide_boxes();

        // Show the particular box
        $('#1croreto10crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 10 Crore to 20 Crore
    function calc_10_crore_to_20_crore() {
        var total_dispute = $('#10croreto20crore_totaldispute').val();
        var basic_amount = 100000000; // 10 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 1237500 // Rs. 1237500

        // Additional Arbitrator’s Fees @0.75% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.75) / 100;

        // new col Total
        var totalAmount = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Additional Arbitrator’s Fees of 25% of the total fee
        var aaf_total_fee_percent = ((basic_arbitrator_fees + aaf_excess_amount_percent) * 25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent + aaf_total_fee_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        var miscellaneous_expenses = 50000 // Rs. 50000

        // Each party payable 
        var each_party_payable = each_party_share + miscellaneous_expenses;

        /*
            ======== Each party UI share =============
        */

        $('.10croreto20crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.10croreto20crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.10croreto20crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_arbitrator_fees));
        $('.10croreto20crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.10croreto20crore_aarf_total_fees_percent').text('Rs. ' + money_format(aaf_total_fee_percent));
        $('.10croreto20crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.10croreto20crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.10croreto20crore_miscellaneous_expenses').text('Rs. ' + money_format(miscellaneous_expenses));
        $('.10croreto20crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // New col
        $('.10croreto20crore_totalAmount').text('Rs. ' + money_format(totalAmount));

        // Hide all the boxes first
        hide_boxes();

        // Show the particular box
        $('#10croreto20crore_box').css({
            "display": "block"
        })
    }


    /*
     * Replace this function // 09 December 2019
     */

    // ===========================================================================
    // Function for above 20 Crore
    function calc_above_20_crore() {
        var total_dispute = $('#above20crore_totaldispute').val();
        var basic_amount = 200000000; // 10 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 1987500 // Rs. 1987500

        // Additional Arbitrator’s Fees @0.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.5) / 100;

        if (aaf_excess_amount_percent > 3000000) {
            aaf_excess_amount_percent = 3000000;
        }

        // Total Arbitrator’s Fees
        // var baf_aeap = basic_arbitrator_fees + aaf_excess_amount_percent;



        var aaf_total_fee_percent = ((basic_arbitrator_fees + aaf_excess_amount_percent) * 25) / 100;
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent + aaf_total_fee_percent;

        // if(baf_aeap > 3000000){
        //     total_arbitrator_fees = 3000000;

        //     // Additional Arbitrator’s Fees of 25% of the total fee
        //     var aaf_total_fee_percent = (total_arbitrator_fees * 25)/ 100;
        // }
        // else{

        //     // Additional Arbitrator’s Fees of 25% of the total fee
        //     var aaf_total_fee_percent = (baf_aeap * 25)/ 100;

        //     total_arbitrator_fees = baf_aeap + aaf_total_fee_percent;   
        // }

        // new col Total
        var totalAmount = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Miscellaneous Expenses
        var miscellaneous_expenses = 50000 // Rs. 50000

        // Each party payable 
        var each_party_payable = each_party_share + miscellaneous_expenses;

        /*
            ======== Each party UI share =============
        */

        $('.above20crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.above20crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.above20crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_arbitrator_fees));
        $('.above20crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.above20crore_aarf_total_fees_percent').text('Rs. ' + money_format(aaf_total_fee_percent));
        $('.above20crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.above20crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.above20crore_miscellaneous_expenses').text('Rs. ' + money_format(miscellaneous_expenses));
        $('.above20crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));


        $('.above20crore_totalAmount').text('Rs. ' + money_format(totalAmount));

        // Hide all the boxes first
        hide_boxes();

        // Show the particular box
        $('#above20crore_box').css({
            "display": "block"
        })
    }

    // ========================================================================
    // Domestic Fees Calculation End
    // ========================================================================
    // Changes 2) End ->


    // =====================================================================
    // International Fees Calculation Start
    // =====================================================================

    // Convert amount in money format
    function int_money_format(amount) {

        var format = new Intl.NumberFormat('en-IN');

        return format.format(amount);
    }

    // Hide all the boxes
    // Show 5 lakhs to 10 lakhs box
    function int_hide_boxes() {
        $('#int_upto10lakh_box').css({
            "display": "none"
        })

        $('#int_10lakhsto50lakhs_box').css({
            "display": "none"
        })

        $('#int_50lakhsto1crore_box').css({
            "display": "none"
        })

        $('#int_1croreto10crore_box').css({
            "display": "none"
        })

        $('#int_above10crore_box').css({
            "display": "none"
        })
    }

    // =====================================================================
    // Function for 10 lakhs to 50 lakhs
    function int_calc_10_lakhs_to_50_lakhs() {
        var total_dispute = $('#int_10lakhsto50lakhs_totaldispute').val();
        var basic_amount = 1000000; // 10 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 30000 // Rs. 30000

        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 1) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_10lakhsto50lakhs_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_10lakhsto50lakhs_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_10lakhsto50lakhs_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_10lakhsto50lakhs_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_10lakhsto50lakhs_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_10lakhsto50lakhs_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_10lakhsto50lakhs_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_hide_boxes();

        // Show the particular box
        $('#int_10lakhsto50lakhs_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 50 lakhs to 1 crore
    function int_calc_50_lakhs_to_1_crore() {
        var total_dispute = $('#int_50lakhsto1crore_totaldispute').val();
        var basic_amount = 5000000; // 50 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 7000000 // Rs. 70,00,000

        // Additional Arbitrator’s Fees @3% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.5) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;


        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_50lakhsto1crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_50lakhsto1crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_50lakhsto1crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_50lakhsto1crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_50lakhsto1crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_50lakhsto1crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_50lakhsto1crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_hide_boxes();

        // Show the particular box
        $('#int_50lakhsto1crore_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 1 Crore to 10 Crore
    function int_calc_1_crore_to_10_crore() {
        var total_dispute = $('#int_1croreto10crore_totaldispute').val();
        var basic_amount = 10000000; // 1 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 95000 // Rs. 95000

        // Additional Arbitrator’s Fees @1% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_1croreto10crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_1croreto10crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_1croreto10crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_1croreto10crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_1croreto10crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_1croreto10crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_1croreto10crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_hide_boxes();

        // Show the particular box
        $('#int_1croreto10crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for above 10 Crore
    function int_calc_above_10_crore() {
        var total_dispute = $('#int_above10crore_totaldispute').val();
        var basic_amount = 100000000; // 10 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 320000 // Rs. 320000

        // Additional Arbitrator’s Fees @0.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.15) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_above10crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_above10crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_above10crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_above10crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_above10crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_above10crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_above10crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_hide_boxes();

        // Show the particular box
        $('#int_above10crore_box').css({
            "display": "block"
        })
    }

    // ========================================================================
    // International Fees Calculation End
    // ========================================================================

    // =====================================================================
    // International Fees Calculation Start
    // =====================================================================

    // Convert amount in money format
    function int_arb_arb_money_format(amount) {

        var format = new Intl.NumberFormat('en-IN');

        return format.format(amount);
    }

    // =======================================================================
    // International Administrative cost calculation Start

    // Show international administrative cost section
    function show_int_adminis_sec() {
        $('.int_adminis_container').css({
            "display": "block"
        })
    }

    // Hide all the boxes
    // Show 5 lakhs to 10 lakhs box
    function int_arb_arb_hide_boxes() {
        $('#int_arb_arb_upto10lakh_box').css({
            "display": "none"
        })

        $('#int_arb_arb_10lakhsto50lakhs_box').css({
            "display": "none"
        })

        $('#int_arb_arb_50lakhsto1crore_box').css({
            "display": "none"
        })

        $('#int_arb_arb_1croreto10crore_box').css({
            "display": "none"
        })

        $('#int_arb_arb_above10crore_box').css({
            "display": "none"
        })

        $('.int_adminis_container').css({
            "display": "none"
        })

        $('.int_af_cost_container').css({
            "display": "none"
        })
    }

    // ==========================================================================
    // Function for 10 lakhs to 50 lakhs
    function int_arb_arb_calc_10_lakhs_to_50_lakhs() {
        var total_dispute = $('#int_arb_arb_10lakhsto50lakhs_totaldispute').val();
        var basic_amount = 1000000; // 10 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 30000 // Rs. 30000

        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 1) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_arb_arb_10lakhsto50lakhs_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_arb_arb_10lakhsto50lakhs_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_arb_arb_10lakhsto50lakhs_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_arb_arb_10lakhsto50lakhs_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_arb_arb_10lakhsto50lakhs_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_arb_arb_10lakhsto50lakhs_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_arb_arb_10lakhsto50lakhs_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show the particular box
        $('#int_arb_arb_10lakhsto50lakhs_box').css({
            "display": "block"
        });

        $('.int_adminis_container').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 50 lakhs to 1 crore
    function int_arb_arb_calc_50_lakhs_to_1_crore() {
        var total_dispute = $('#int_arb_arb_50lakhsto1crore_totaldispute').val();
        var basic_amount = 5000000; // 50 lakh
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 7000000 // Rs. 70,00,000

        // Additional Arbitrator’s Fees @3% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.5) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;


        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_arb_arb_50lakhsto1crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_arb_arb_50lakhsto1crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_arb_arb_50lakhsto1crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_arb_arb_50lakhsto1crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_arb_arb_50lakhsto1crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_arb_arb_50lakhsto1crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_arb_arb_50lakhsto1crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show the particular box
        $('#int_arb_arb_50lakhsto1crore_box').css({
            "display": "block"
        })

        $('.int_adminis_container').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 1 Crore to 10 Crore
    function int_arb_arb_calc_1_crore_to_10_crore() {
        var total_dispute = $('#int_arb_arb_1croreto10crore_totaldispute').val();
        var basic_amount = 10000000; // 1 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 95000 // Rs. 95000

        // Additional Arbitrator’s Fees @1% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.25) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_arb_arb_1croreto10crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_arb_arb_1croreto10crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_arb_arb_1croreto10crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_arb_arb_1croreto10crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_arb_arb_1croreto10crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_arb_arb_1croreto10crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_arb_arb_1croreto10crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show the particular box
        $('#int_arb_arb_1croreto10crore_box').css({
            "display": "block"
        })

        $('.int_adminis_container').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for above 10 Crore
    function int_arb_arb_calc_above_10_crore() {
        var total_dispute = $('#int_arb_arb_above10crore_totaldispute').val();
        var basic_amount = 100000000; // 10 crore
        var excess_amount = total_dispute - basic_amount;

        var basic_administrative_cost = 320000 // Rs. 320000

        // Additional Arbitrator’s Fees @0.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.15) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_administrative_cost + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_arb_arb_above10crore_basic_amount').text('Rs. ' + money_format(basic_amount));
        $('.int_arb_arb_above10crore_excess_amount').text('Rs. ' + money_format(excess_amount));
        $('.int_arb_arb_above10crore_basic_arbitrator_fees').text('Rs. ' + money_format(basic_administrative_cost));
        $('.int_arb_arb_above10crore_aarf_excess_amount_percent').text('Rs. ' + money_format(aaf_excess_amount_percent));
        $('.int_arb_arb_above10crore_total_arbitrator_fees').text('Rs. ' + money_format(total_arbitrator_fees));
        $('.int_arb_arb_above10crore_each_party_share').text('Rs. ' + money_format(each_party_share));
        $('.int_arb_arb_above10crore_each_party_payable').text('Rs. ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show the particular box
        $('#int_arb_arb_above10crore_box').css({
            "display": "block"
        })

        $('.int_adminis_container').css({
            "display": "block"
        })
    }

    // International Administrative Cost End
    // ============================================================================



    /// ============================================================================



    // ============================================================================
    // International Arbitration Cost calculation Start

    // Show international arbitration fees section
    function show_int_af_sec() {
        $('.int_af_cost_container').css({
            "display": "block"
        })
    }

    // Hide all the boxes
    function int_af_cost_hide_boxes() {

        $('#int_af_cost_upto50000_box').css({
            "display": "none"
        })

        $('#int_af_cost_50_to_1_lakh_box').css({
            "display": "none"
        })

        $('#int_af_cost_1_to_5_lakh_box').css({
            "display": "none"
        })

        $('#int_af_cost_5_to_10_lakh_box').css({
            "display": "none"
        })

        $('#int_af_cost_10_to_20_lakh_box').css({
            "display": "none"
        })

        $('#int_af_cost_20_to_50_lakh_box').css({
            "display": "none"
        })

        $('#int_af_cost_50_to_1_crore_box').css({
            "display": "none"
        })

        $('#int_af_cost_1_to_5_crore_box').css({
            "display": "none"
        })

        $('#int_af_cost_5_to_8_crore_box').css({
            "display": "none"
        })

        $('#int_af_cost_8_to_10_crore_box').css({
            "display": "none"
        })

        $('#int_af_cost_10_to_above_box').css({
            "display": "none"
        })

        $('.int_af_cost_container').css({
            "display": "none"
        })

        $('.int_adminis_cost_container').css({
            "display": "none"
        })
    }

    // ==========================================================================
    // Function for 50 thousand to 1 lakhs
    function int_af_cost_calc_50_to_1_lakh() {
        var total_dispute = $('#int_af_cost_50_to_1_lakh_totaldispute').val();
        var basic_amount = 50000; // 50,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 30000 // Rs. 30000

        // Additional Arbitrator’s Fees @6% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 6) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_50_to_1_lakh_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_50_to_1_lakh_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_50_to_1_lakh_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_50_to_1_lakh_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_50_to_1_lakh_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_50_to_1_lakh_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_50_to_1_lakh_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_50_to_1_lakh_box').css({
            "display": "block"
        });
    }


    // ===========================================================================
    // Function for 1 to 5 
    function int_af_cost_calc_1_to_5_lakh() {
        var total_dispute = $('#int_af_cost_1_to_5_lakh_totaldispute').val();
        var basic_amount = 100000; // 1,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 7500 // Rs. 7,500

        // Additional Arbitrator’s Fees @3.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 3.5) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;


        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_1_to_5_lakh_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_1_to_5_lakh_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_1_to_5_lakh_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_1_to_5_lakh_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_1_to_5_lakh_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_1_to_5_lakh_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_1_to_5_lakh_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_arb_arb_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_1_to_5_lakh_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 5,00,000 to 10,00,000
    function int_af_cost_calc_5_to_10_lakh() {
        var total_dispute = $('#int_af_cost_5_to_10_lakh_totaldispute').val();
        var basic_amount = 500000; // 5,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 21000 // Rs. 21,000

        // Additional Arbitrator’s Fees @2.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 2.5) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_5_to_10_lakh_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_5_to_10_lakh_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_5_to_10_lakh_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_5_to_10_lakh_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_5_to_10_lakh_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_5_to_10_lakh_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_5_to_10_lakh_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_5_to_10_lakh_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 10,00,000 to 20,00,000
    function int_af_cost_calc_10_to_20_lakh() {
        var total_dispute = $('#int_af_cost_10_to_20_lakh_totaldispute').val();
        var basic_amount = 1000000; // 10,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 33500 // Rs. 33,500

        // Additional Arbitrator’s Fees @1.5% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 1.5) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_10_to_20_lakh_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_10_to_20_lakh_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_10_to_20_lakh_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_10_to_20_lakh_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_10_to_20_lakh_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_10_to_20_lakh_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_10_to_20_lakh_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_10_to_20_lakh_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 20,00,000 to 50,00,000
    function int_af_cost_calc_20_to_50_lakh() {
        var total_dispute = $('#int_af_cost_20_to_50_lakh_totaldispute').val();
        var basic_amount = 2000000; // 20,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 48500 // Rs. 48,500

        // Additional Arbitrator’s Fees @0.75% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.75) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_20_to_50_lakh_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_20_to_50_lakh_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_20_to_50_lakh_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_20_to_50_lakh_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_20_to_50_lakh_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_20_to_50_lakh_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_20_to_50_lakh_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_20_to_50_lakh_box').css({
            "display": "block"
        })
    }

    // ===========================================================================
    // Function for 50,00,000 to 1,00,00,000
    function int_af_cost_calc_50_to_1_crore() {
        var total_dispute = $('#int_af_cost_50_to_1_crore_totaldispute').val();
        var basic_amount = 5000000; //50,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 71000 // Rs. 71,000

        // Additional Arbitrator’s Fees @0.35% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.35) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_50_to_1_crore_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_50_to_1_crore_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_50_to_1_crore_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_50_to_1_crore_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_50_to_1_crore_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_50_to_1_crore_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_50_to_1_crore_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_50_to_1_crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 1,00,00,000 to 5,00,00,000
    function int_af_cost_calc_1_to_5_crore() {
        var total_dispute = $('#int_af_cost_1_to_5_crore_totaldispute').val();
        var basic_amount = 10000000; // 1,00,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 88500 // Rs. 88,500

        // Additional Arbitrator’s Fees @0.15% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.15) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_1_to_5_crore_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_1_to_5_crore_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_1_to_5_crore_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_1_to_5_crore_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_1_to_5_crore_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_1_to_5_crore_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_1_to_5_crore_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_1_to_5_crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 5,00,00,000 to 8,00,00,000
    function int_af_cost_calc_5_to_8_crore() {
        var total_dispute = $('#int_af_cost_5_to_8_crore_totaldispute').val();
        var basic_amount = 50000000; //5,00,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 94500 // Rs. 94,500

        // Additional Arbitrator’s Fees @0.075% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.075) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_5_to_8_crore_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_5_to_8_crore_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_5_to_8_crore_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_5_to_8_crore_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_5_to_8_crore_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_5_to_8_crore_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_5_to_8_crore_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_5_to_8_crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 8,00,00,000 to 10,00,00,000
    function int_af_cost_calc_8_to_10_crore() {
        var total_dispute = $('#int_af_cost_8_to_10_crore_totaldispute').val();
        var basic_amount = 80000000; //8,00,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 117000 // Rs. 1,17,000

        // Additional Arbitrator’s Fees @0.03% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.03) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_8_to_10_crore_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_8_to_10_crore_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_8_to_10_crore_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_8_to_10_crore_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_8_to_10_crore_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_8_to_10_crore_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_8_to_10_crore_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_8_to_10_crore_box').css({
            "display": "block"
        })
    }


    // ===========================================================================
    // Function for 10,00,00,000 to above
    function int_af_cost_calc_10_to_above() {
        var total_dispute = $('#int_af_cost_10_to_above_totaldispute').val();
        var basic_amount = 100000000; // 10,00,00,000
        var excess_amount = total_dispute - basic_amount;

        var basic_arbitrator_fees = 123000 // Rs. 1,23,000

        // Additional Arbitrator’s Fees @0.02% of Excess Amount
        var aaf_excess_amount_percent = (excess_amount * 0.02) / 100;

        // Total Arbitrator’s Fees
        var total_arbitrator_fees = basic_arbitrator_fees + aaf_excess_amount_percent;

        // Each party share 
        var each_party_share = total_arbitrator_fees / 2;

        // Each party payable 
        var each_party_payable = each_party_share;

        /*
            ======== Each party UI share =============
        */

        $('.int_af_cost_10_to_above_basic_amount').text('$ ' + money_format(basic_amount));
        $('.int_af_cost_10_to_above_excess_amount').text('$ ' + money_format(excess_amount));
        $('.int_af_cost_10_to_above_basic_arbitrator_fees').text('$ ' + money_format(basic_arbitrator_fees));
        $('.int_af_cost_10_to_above_aarf_excess_amount_percent').text('$ ' + money_format(aaf_excess_amount_percent));
        $('.int_af_cost_10_to_above_total_arbitrator_fees').text('$ ' + money_format(total_arbitrator_fees));
        $('.int_af_cost_10_to_above_each_party_share').text('$ ' + money_format(each_party_share));
        $('.int_af_cost_10_to_above_each_party_payable').text('$ ' + money_format(each_party_payable));

        // Hide all the boxes first
        int_af_cost_hide_boxes();

        // Show international arbitration fees section
        show_int_af_sec();

        // Show the particular box
        $('#int_af_cost_10_to_above_box').css({
            "display": "block"
        })
    }

    // International Cost End
    // ============================================================================

    // ============================================================================
    // International Fees Calculation End
    // ============================================================================


    $(document).ready(function() {

        // Domestic Fees Calculation Form
        $('#fee_calc_form').on('submit', function(e) {
            e.preventDefault();

            var fees_type = $('#fees_type').val();
            var claim_amount = $('#claim_amount').val();

            int_af_cost_hide_boxes();

            if (fees_type == '' || claim_amount == '') {
                alert('Both fields are required.');
            } else {
                if (fees_type == 'upto5lakh') {
                    if (claim_amount <= 500000) {
                        $('#upto5lakh_totaldispute').val(claim_amount);

                        // Hide all the boxes first
                        hide_boxes();

                        // Show the particular box
                        $('#upto5lakh_box').css({
                            "display": "block"
                        })
                    } else {
                        alert('Claim amount should be less than or equal to 5 lakhs');
                    }
                } else if (fees_type == '5lakhsto20lakhs') {
                    if (claim_amount > 500000 && claim_amount <= 2000000) {
                        $('#5lakhsto20lakhs_totaldispute').val(claim_amount);

                        // Calculate amount and show them
                        calc_5_lakhs_to_20_lakhs();
                    } else {
                        alert('Claim amount should be in between 5 lakhs to 20 lakhs');
                    }

                } else if (fees_type == '20lakhsto1crore') {
                    if (claim_amount > 2000000 && claim_amount <= 10000000) {
                        $('#20lakhsto1crore_totaldispute').val(claim_amount);

                        // Calculate amount and show them
                        calc_20_lakhs_to_1_crore();
                    } else {
                        alert('Claim amount should be in between 20 lakhs to 1 crore');
                    }
                } else if (fees_type == '1croreto10crore') {
                    if (claim_amount > 10000000 && claim_amount <= 100000000) {
                        $('#1croreto10crore_totaldispute').val(claim_amount);

                        // Calculate amount and show them
                        calc_1_crore_to_10_crore();
                    } else {
                        alert('Claim amount should be in between 1 crore to 10 crore');
                    }
                } else if (fees_type == '10croreto20crore') {
                    if (claim_amount > 100000000 && claim_amount <= 200000000) {
                        $('#10croreto20crore_totaldispute').val(claim_amount);

                        // Calculate amount and show them
                        calc_10_crore_to_20_crore();
                    } else {
                        alert('Claim amount should be in between 10 crore to 20 crore');
                    }
                } else if (fees_type == 'above20crore') {
                    if (claim_amount > 200000000) {
                        $('#above20crore_totaldispute').val(claim_amount);

                        // Calculate amount and show them
                        calc_above_20_crore();
                    } else {
                        alert('Claim amount should be above 20 crore');
                    }
                } else {
                    alert('Invalid value provided');
                }
            }
        })


        // ========================================================================
        // International Fees Calculation
        $('#int_arb_fee_calc_form').on('submit', function(e) {
            e.preventDefault();

            // Check for the international cost type
            var int_cost_type = $('#int_cost_type').val();

            if (int_cost_type == 'international_cost') {
                var int_cost_fees_type = $('#int_cost_fees_type').val();
                var int_arb_arb_claim_amount = $('#int_arb_arb_claim_amount').val();

                if (int_cost_fees_type == '' || int_arb_arb_claim_amount == '') {
                    alert('All (*) marked fields are required.');
                } else {
                    // Changes 3) start ->
                    // Note for international cost
                    $int_af_cost_note = '<ul>\
                                    <li>Conversion rate as on date of deposit shall be applicable</li>\
                                    <li>Deposit in INR only.</li>\
                                    <li>\
                                        <p>\
                                            <p class="note">The Account details for payment of fees are as under: </p>\
                                            <strong>Account No.: </strong> 15530210000663 <br />\
                                            <strong>IFSC: </strong> UCBA0001553 (UCO BANK) <br />\
                                            <strong>MICR Code: </strong> 110028026 <br />\
                                            <strong>Bank & Branch: </strong> UCO Bank, Delhi High Court, Sher Shah Road, New Delhi-110003 <br />\
                                        </p>\
                                    </li>\
                                </ul>';
                    // Changes 3) End ->
                    $('.int_af_cost_note').html($int_af_cost_note);

                    if (int_cost_fees_type == 'upto50_thous') {
                        if (int_arb_arb_claim_amount <= 50000) {
                            $('#int_af_cost_upto50000_totaldispute').val(int_arb_arb_claim_amount);

                            // Hide all the boxes first
                            int_af_cost_hide_boxes();

                            // Show the particular box
                            $('#int_af_cost_upto50000_box').css({
                                "display": "block"
                            })

                            // Show international arbitration fees section
                            show_int_af_sec();

                        } else {
                            alert('Claim amount should be less than or equal to 50 thousand');
                        }
                    } else if (int_cost_fees_type == '50_thous_to_1_lakh') {
                        if (int_arb_arb_claim_amount > 50000 && int_arb_arb_claim_amount <= 100000) {
                            $('#int_af_cost_50_to_1_lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_50_to_1_lakh();

                        } else {
                            alert('Claim amount should be in between 50 thousand to 1 lakhs');
                        }

                    } else if (int_cost_fees_type == '1_lakh_to_5_lakh') {
                        if (int_arb_arb_claim_amount > 100000 && int_arb_arb_claim_amount <= 500000) {
                            $('#int_af_cost_1_to_5_lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_1_to_5_lakh();
                        } else {
                            alert('Claim amount should be in between 1 lakh to 5 lakhs');
                        }
                    } else if (int_cost_fees_type == '5_lakh_to_10_lakh') {
                        if (int_arb_arb_claim_amount > 500000 && int_arb_arb_claim_amount <= 1000000) {
                            $('#int_af_cost_5_to_10_lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_5_to_10_lakh();
                        } else {
                            alert('Claim amount should be in between 5 lakhs to 10 lakhs');
                        }
                    } else if (int_cost_fees_type == '10_lakh_to_20_lakh') {
                        if (int_arb_arb_claim_amount > 1000000 && int_arb_arb_claim_amount <= 2000000) {
                            $('#int_af_cost_10_to_20_lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_10_to_20_lakh();
                        } else {
                            alert('Claim amount should be in between 10 lakhs to 20 lakhs');
                        }
                    } else if (int_cost_fees_type == '20_lakh_to_50_lakh') {
                        if (int_arb_arb_claim_amount > 2000000 && int_arb_arb_claim_amount <= 5000000) {
                            $('#int_af_cost_20_to_50_lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_20_to_50_lakh();
                        } else {
                            alert('Claim amount should be in between 20 lakhs to 50 lakhs');
                        }
                    } else if (int_cost_fees_type == '50_lakh_to_1_crore') {
                        if (int_arb_arb_claim_amount > 5000000 && int_arb_arb_claim_amount <= 10000000) {
                            $('#int_af_cost_50_to_1_crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_50_to_1_crore();
                        } else {
                            alert('Claim amount should be in between 50 lakhs to 1 crore');
                        }
                    } else if (int_cost_fees_type == '1_crore_to_5_crore') {
                        if (int_arb_arb_claim_amount > 10000000 && int_arb_arb_claim_amount <= 50000000) {
                            $('#int_af_cost_1_to_5_crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_1_to_5_crore();
                        } else {
                            alert('Claim amount should be in between 1 crore to 5 crores');
                        }
                    } else if (int_cost_fees_type == '5_crore_to_8_crore') {
                        if (int_arb_arb_claim_amount > 50000000 && int_arb_arb_claim_amount <= 80000000) {
                            $('#int_af_cost_5_to_8_crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_5_to_8_crore();
                        } else {
                            alert('Claim amount should be in between 5 crores to 8 crores');
                        }
                    } else if (int_cost_fees_type == '8_crore_to_10_crore') {
                        if (int_arb_arb_claim_amount > 80000000 && int_arb_arb_claim_amount <= 100000000) {
                            $('#int_af_cost_8_to_10_crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_8_to_10_crore();
                        } else {
                            alert('Claim amount should be in between 8 crores to 10 crores');
                        }
                    } else if (int_cost_fees_type == '10_crore_to_above') {
                        if (int_arb_arb_claim_amount > 100000000) {
                            $('#int_af_cost_10_to_above_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_af_cost_calc_10_to_above();
                        } else {
                            alert('Claim amount should be in 10 crores or above.');
                        }
                    } else {
                        alert('Invalid value provided');
                    }
                }
            } else if (int_cost_type == 'administrative_cost') {
                var int_arb_arb_fees_type = $('#int_arb_arb_fees_type').val();
                var int_arb_arb_claim_amount = $('#int_arb_arb_claim_amount').val();

                if (int_arb_arb_fees_type == '' || int_arb_arb_claim_amount == '') {
                    alert('Both fields are required.');
                } else {
                    if (int_arb_arb_fees_type == 'upto10lakh') {
                        if (int_arb_arb_claim_amount <= 1000000) {
                            $('#int_arb_arb_upto10lakh_totaldispute').val(int_arb_arb_claim_amount);

                            // Hide all the boxes first
                            int_arb_arb_hide_boxes();

                            // Show the particular box
                            $('#int_arb_arb_upto10lakh_box').css({
                                "display": "block"
                            })
                            $('.int_adminis_container').css({
                                "display": "block"
                            })
                        } else {
                            alert('Claim amount should be less than or equal to 10 lakhs');
                        }
                    } else if (int_arb_arb_fees_type == '10lakhsto50lakhs') {
                        if (int_arb_arb_claim_amount > 1000000 && int_arb_arb_claim_amount <= 5000000) {
                            $('#int_arb_arb_10lakhsto50lakhs_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_arb_arb_calc_10_lakhs_to_50_lakhs();
                        } else {
                            alert('Claim amount should be in between 10 lakhs to 50 lakhs');
                        }

                    } else if (int_arb_arb_fees_type == '50lakhsto1crore') {
                        if (int_arb_arb_claim_amount > 5000000 && int_arb_arb_claim_amount <= 10000000) {
                            $('#int_arb_arb_50lakhsto1crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_arb_arb_calc_50_lakhs_to_1_crore();
                        } else {
                            alert('Claim amount should be in between 50 lakhs to 1 crore');
                        }
                    } else if (int_arb_arb_fees_type == '1croreto10crore') {
                        if (int_arb_arb_claim_amount > 10000000 && int_arb_arb_claim_amount <= 100000000) {
                            $('#int_arb_arb_1croreto10crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_arb_arb_calc_1_crore_to_10_crore();
                        } else {
                            alert('Claim amount should be in between 1 crore to 10 crore');
                        }
                    } else if (int_arb_arb_fees_type == 'above10crore') {
                        if (int_arb_arb_claim_amount > 100000000) {
                            $('#int_arb_arb_above10crore_totaldispute').val(int_arb_arb_claim_amount);

                            // Calculate amount and show them
                            int_arb_arb_calc_above_10_crore();
                        } else {
                            alert('Claim amount should be above 10 crore');
                        }
                    } else {
                        alert('Invalid value provided');
                    }
                }
            } else {
                alert('Please select cost type to calculate international cost.');
            }

        });



        // On change cost type in international fees calculation
        $('#int_cost_type').on('change', function() {
            var int_cost_type = $(this).val();

            if (int_cost_type == 'international_cost') {
                $('.int_select_amount_box').css({
                    "display": "block"
                });

                $('.int_cost_amount_box').css({
                    'display': 'block'
                });

                $('.int_adminis_cost_amount_box').css({
                    'display': 'none'
                });
            } else if (int_cost_type == 'administrative_cost') {
                $('.int_select_amount_box').css({
                    "display": "block"
                });

                $('.int_adminis_cost_amount_box').css({
                    'display': 'block'
                });

                $('.int_cost_amount_box').css({
                    'display': 'none'
                });
            } else {
                $('.int_select_amount_box').css({
                    "display": "none"
                });

                $('.int_cost_amount_box').css({
                    'display': 'none'
                });

                $('.int_adminis_cost_amount_box').css({
                    'display': 'none'
                });
            }

        })

        $('.claim_amount').on('keypress', function(e) {
            return e.charCode >= 47 && e.charCode <= 57;
        });
    });
    $(document).ready(function() {
        $('#int_cost_type').on('change', function() {
            console.log(this.value);
            $optionvalue = this.value;
            if ($optionvalue == 'international_cost') {
                $('.amount_type').text('Enter Claim Amount($)');
                int_af_cost_hide_boxes();

            } else if ($optionvalue == 'administrative_cost') {
                $('.amount_type').text('Enter Claim Amount(Rs.)');
            } else {
                $('.amount_type').text('Enter Claim Amount');
            }
        });
    });
</script>