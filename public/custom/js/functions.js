/**
 * Define variables here
 */
// Address variables
var trRowCount = 1;
var rowCountArray = [];

// Convert Format
function convert_ymd_to_dmy(date) {
	return date.split("-").reverse().join("-");
}

// Check if the data is null
function checkIfNull(data) {
	if (data == null) {
		return "";
	}
	if (data == undefined) {
		return "";
	}
	return data;
}

// Function to get all the addresses
function getAllAddresses(type_code, person_type) {
	var addresses = [];
	$.ajax({
		url: base_url + "service/get_addresses",
		method: "POST",
		data: {
			type_code: type_code,
			person_type: person_type,
		},
		async: false,
		success: function (response) {
			try {
				var response = JSON.parse(response);
				addresses = response;
			} catch (e) {
				sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
			}
		},
		error: function (err) {
			toastr.error("unable to save");
		},
	});

	return addresses;
}

// Function to get all the single address
function getSingleAddress(id) {
	if (id) {
		var address = [];
		$.ajax({
			url: base_url + "service/get_single_address",
			method: "POST",
			data: {
				id: id,
			},
			async: false,
			success: function (response) {
				try {
					var response = JSON.parse(response);
					address = response;
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});

		return address;
	}

	return false;
}

// Get the countries
function getCountries() {
	var countries = [];
	$.ajax({
		url: base_url + "service/get_all_countries",
		type: "POST",
		data: {},
		async: false,
		success: function (response) {
			try {
				countries = JSON.parse(response);
			} catch (e) {
				toastr.error(
					"Server faild while fetching countries. Please try again."
				);
			}
		},
		error: function (errors) {
			toastr.error(errors);
		},
	});

	return countries;
}

// Get the states using country code
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
			success: function (response) {
				try {
					states = JSON.parse(response);
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});

		return states;
	}
}

function getStatesUsingCountryCodeAndAppend(
	country_code,
	element_id,
	selected = ""
) {
	if (country_code) {
		var states = [];
		$.ajax({
			url: base_url + "service/get_states_using_country_code",
			method: "POST",
			data: {
				country_code: country_code,
			},
			success: function (response) {
				states = JSON.parse(response);
				console.log(states);

				var options = '<option value="">Select State</option>';
				$.each(states, function (index, state) {
					if (state.id == selected) {
						options +=
							'<option value="' +
							state.id +
							'" selected>' +
							state.name +
							"</option>";
					} else {
						options +=
							'<option value="' + state.id + '">' + state.name + "</option>";
					}
				});
				$(`#${element_id}`).html(options);
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});

		// return states;
	}
}

// Generate the TR of table for address
function generateTrOfTable(trRowCount) {
	var countries = getCountries();

	var countrySelect = `<select class="form-control select2 common_address_country" name="common_address_country_${trRowCount}" id="common_address_country_${trRowCount}" data-row-count="${trRowCount}"><option value="">Select Country</option>`;
	$.each(countries, function (index, country) {
		countrySelect +=
			'<option value="' + country.iso2 + '">' + country.name + "</option>";
	});
	countrySelect += "</select>";

	var tr = `
	<tr class="car-address-row" id="tr-${trRowCount}">
		<td>
			<input type="text" class="form-control" id="common_address_one_${trRowCount}" name="common_address_one_${trRowCount}"  autocomplete="off" maxlength="255" value="">
		</td>
		<td>
			<input type="text" class="form-control" id="common_address_two_${trRowCount}" name="common_address_two_${trRowCount}"  autocomplete="off" maxlength="255" value="">
		</td>
		<td>
			${countrySelect}
		</td>
		<td>
			<select class="form-control select2" id="common_address_state_${trRowCount}" name="common_address_state_${trRowCount}"></select>
		</td>
		<td>
			<input type="text" class="form-control" id="common_address_pincode_${trRowCount}" name="common_address_pincode_${trRowCount}"  autocomplete="off" maxlength="255" value="">
		</td>
		<td>
			<button type="button" class="btn btn-danger btn-sm btn-remove-address-row" id="btn-remove-row-${trRowCount}" data-row-number="${trRowCount}"><i class="fa fa-minus"></i></button>
		</td>
	</tr>
	`;

	rowCountArray.push(trRowCount);
	$(".add-address-table tbody").append(tr);
	$(".select2").select2();
}

function toIndianCurrency(amount) {
	var curr = new Intl.NumberFormat("en-IN", {
		style: "currency",
		currency: "INR",
	}).format(amount);
	return curr;
}

function toUSCurrency(amount) {
	var curr = new Intl.NumberFormat("en-US", {
		style: "currency",
		currency: "USD",
	}).format(amount);
	return curr;
}

function customURIEncode(data) {
	return encodeURI(btoa(data));
}

// ===============================================
// Fees Functions
// Function: This is used to generate the content based on the fees calculation
function generate_assessment_content(type_of_arbitration, claim_amount, data) {
	// ============================================
	// ============================================
	// Show assessment of calculated fees
	// ============================================
	// ============================================
	let content = "";

	if (type_of_arbitration == "DOMESTIC") {
		if (claim_amount >= 500000) {
			content = `
                    <table class="table" style="border: 1px solid lightgrey; margin-bottom: 10px;">
					<tr class="assessment-tr-heading">
						<th width="70%">Total Sum in Dispute:</th>
						<td>${toIndianCurrency(claim_amount)}</td>
					</tr>
					<tr>
						<th>Arbitrator’s Fees:</th>
						<td>${toIndianCurrency(data.total_arbitrator_fees)}</td>
					</tr>
					<tr>
						<th>Basic Arbitrator’s Fees:</th>
						<td>${toIndianCurrency(data.basic_arbitrator_fees)}</td>
					</tr>
					<tr>
						<th>${data.excess_amount_percent_details.text}:</th>
						<td>${toIndianCurrency(
							data.excess_amount_percent_details.excess_amount_percent
						)}</td>
					</tr>
					<tr>
						<th>${data.additional_arb_fees_details.text}:</th>
						<td>${toIndianCurrency(
							data.additional_arb_fees_details.aaf_total_fee_percent
						)}</td>
					</tr>
					<tr>
						<th>Total Arbitrator’s Fees:</th>
						<td>${toIndianCurrency(data.total_arbitrator_fees)}</td>
					</tr>
					<tr>
						<th>Each Party Share in arbitrator fees:</th>
						<td>${toIndianCurrency(data.each_party_share_in_arb_fees)}</td>
					</tr>
					<tr>
						<th>Miscellaneous Expenses:</th>
						<td>${toIndianCurrency(data.administrative_charges)}</td>
					</tr>
					<tr>
						<th>Each Party Payable:</th>
						<td>${toIndianCurrency(data.each_party_payable)}</td>
					</tr>
					
				</table>
			`;
		}
		if (claim_amount < 500000) {
			content = `
				<table class="table" style="border: 1px solid lightgrey; margin-bottom: 10px;">
					<tr class="assessment-tr-heading">
						<td>Total Sum in Dispute:</td>
						<td>${toIndianCurrency(claim_amount)}</td>
					</tr>
					<tr>
						<td>Arbitrator’s Fees:</td>
						<td>${toIndianCurrency(data.total_arbitrator_fees)}</td>
					</tr>
					<tr>
						<td>Each Party Share Of Arbitrator’s Fees:</td>
						<td>${toIndianCurrency(data.each_party_share_in_arb_fees)}</td>
					</tr>
					<tr>
						<td>Miscellaneous Expenses:</td>
						<td>${toIndianCurrency(data.administrative_charges)}</td>
					</tr>
					<tr>
						<td>Each Party Payable::</td>
						<td>${toIndianCurrency(data.each_party_payable)}</td>
					</tr>
					
				</table>
			`;
		}
	}

	// IF TYPE OF ARBITRATION IS INTERNATIONAL =========
	if (type_of_arbitration == "INTERNATIONAL") {
		content += `<table class="table" style="border: 1px solid lightgrey; margin-bottom: 10px;">`;
		if (claim_amount >= 50000) {
			content += `
					<tr>
						<th colspan="2" style="background-color: #9f2f32; color: white;">Arbitrator Fees</th>
					</tr>
					<tr class="assessment-tr-heading">
						<th width="70%">Total Sum in Dispute:</th>
						<td>${toUSCurrency(claim_amount)}</td>
					</tr>
					<tr>
						<th>Basic Amount:</th>
						<td>${toUSCurrency(data.basic_amount)}</td>
					</tr>
					<tr>
						<th>Excess Amount:</th>
						<td>${toUSCurrency(data.excess_amount)}</td>
					</tr>
					<tr>
						<th>Basic Arbitrator’s Fees:</th>
						<td>${toUSCurrency(data.basic_arbitrator_fees)}</td>
					</tr>
					<tr>
						<th>${data.excess_amount_percent_details.text}:</th>
						<td>${toUSCurrency(
							data.excess_amount_percent_details.excess_amount_percent
						)}</td>
					</tr>
					<tr>
						<th>Total Arbitrator’s Fees:</th>
						<td>${toUSCurrency(data.total_arbitrator_fees_dollar)}</td>
					</tr>
					<tr>
						<th>Each Party Share in arbitrator fees:</th>
						<td>${toUSCurrency(data.each_party_share_in_arb_fees_dollar)}</td>
					</tr>
					<tr>
						<th>Each Party Payable:</th>
						<td>${toUSCurrency(data.each_party_payable_dollar)}</td>
					</tr>
			`;
		} else {
			content += `
					<tr>
						<th colspan="2" style="background-color: #9f2f32; color: white;">Arbitrator Fees</th>
					</tr>
					<tr class="assessment-tr-heading">
						<th width="70%">Total Sum in Dispute:</th>
						<td>${toUSCurrency(claim_amount)}</td>
					</tr>
					<tr>
						<th>Arbitrator’s Fees:</th>
						<td>${toUSCurrency(data.total_arbitrator_fees_dollar)}</td>
					</tr>
					<tr>
						<th>Each Party Share in arbitrator fees:</th>
						<td>${toUSCurrency(data.each_party_share_in_arb_fees_dollar)}</td>
					</tr>
					<tr>
						<th>Each Party Payable:</th>
						<td>${toUSCurrency(data.each_party_payable_dollar)}</td>
					</tr>
			`;
		}

		if (data.administrative_charges_details.claim_amount_rupee > 1000000) {
			content += `<tr>
				<th colspan="2" style="background-color: #9f2f32; color: white;">Administrative Cost</th>
			</tr>
			
			<tr>
				<th width="70%">Total Sum in Dispute:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.claim_amount_rupee
				)}</td>
			</tr>
			<tr>
				<th>Basic Amount:</th>
				<td>${toIndianCurrency(data.administrative_charges_details.basic_amount)}</td>
			</tr>
			<tr>
				<th>Excess Amount:</th>
				<td>${toIndianCurrency(data.administrative_charges_details.excess_amount)}</td>
			</tr>
			<tr>
				<th>Basic Administrative Cost:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.basic_administrative_cost
				)}</td>
			</tr>
			<tr>
				<th>Additional Administrative Cost @${
					data.administrative_charges_details.excess_amount_percent
				}% of Excess Amount:	:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.aaf_excess_amount_percent
				)}</td>
			</tr>
			<tr>
				<th>Total Administrative Cost:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.total_administrative_cost
				)}</td>
			</tr>
			<tr>
				<th>Each Party Share:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.each_party_share
				)}</td>
			</tr>
			<tr>
				<th>Each Party Payable:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.each_party_payable
				)}</td>
			</tr>`;
		} else {
			content += `<tr>
				<th colspan="2" style="background-color: #9f2f32; color: white;">Administrative Cost</th>
			</tr>
			
			<tr>
				<th width="70%">Total Sum in Dispute:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.claim_amount_rupee
				)}</td>
			</tr>
			<tr>
				<th>Administrative Cost:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.total_administrative_cost
				)}</td>
			</tr>
			<tr>
				<th>Each Party Share:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.each_party_share
				)}</td>
			</tr>
			<tr>
				<th>Each Party Payable:</th>
				<td>${toIndianCurrency(
					data.administrative_charges_details.each_party_payable
				)}</td>
			</tr>`;
		}

		content += `</table>`;
	}

	return content;
}

// Function: This function is used to generate the assessment view
function generate_assessment_view(
	type_of_arbitration,
	data,
	claim_amount,
	counter_claim_amount = 0,
	claim_assessed_seperately = "no",
	calc_div_element_ID,
	show_calc_div_element_ID
) {
	let content = "";

	if (claim_assessed_seperately == "no") {
		content += generate_assessment_content(
			type_of_arbitration,
			claim_amount,
			data
		);
	}
	if (claim_assessed_seperately == "yes") {
		content += generate_assessment_content(
			type_of_arbitration,
			claim_amount,
			data.claim_fees
		);
		content += generate_assessment_content(
			type_of_arbitration,
			counter_claim_amount,
			data.counter_fees
		);
	}

	$("#" + calc_div_element_ID).html(content);
	$("#" + show_calc_div_element_ID).show();
}

// Function is used to calculate the fees of claim (without counter claim)
function calculate_fees(
	claim_amount,
	arbitral_tribunal_strength,
	type_of_arbitration
) {
	if (
		claim_amount &&
		claim_amount > 1000 &&
		arbitral_tribunal_strength &&
		type_of_arbitration
	) {
		$.ajax({
			url: base_url + "calculate-fees/case-fee",
			type: "POST",
			data: {
				claim_amount: claim_amount,
				arbitral_tribunal_strength: arbitral_tribunal_strength,
				type_of_arbitration: type_of_arbitration,
			},
			success: function (response) {
				var response = JSON.parse(response);
				console.log(response);

				if (response.status == true) {
					var data = response.data;

					// Basic Amount
					$("#fc_basic_amount").val(data.basic_amount);

					// Excess Amount
					$("#fc_actual_excess_amount").val(data.excess_amount);

					// Basic Arbitrator Fees
					$("#fc_basic_arb_fees").val(data.basic_arbitrator_fees);

					// Excess Amount
					$("#fc_excess_amount").val(
						data.excess_amount_percent_details.excess_amount_percent
					);
					$("#fc_excess_percentage").val(
						data.excess_amount_percent_details.excess_amount_percentage
					);

					// Additional Arbitrator Fees (Only if arbitral tribunal strength is 1)
					if (arbitral_tribunal_strength == 1) {
						$("#fc_additional_arb_amount").val(
							data.additional_arb_fees_details.aaf_total_fee_percent
						);
						$("#fc_additional_arb_percentage").val(
							data.additional_arb_fees_details.aaf_total_fee_percentage
						);
					} else {
						$("#fc_additional_arb_amount").val(0);
						$("#fc_additional_arb_percentage").val(0);
					}

					if (type_of_arbitration == "DOMESTIC") {
						$("#fc_total_arb_fees").val(response.data.total_arbitrator_fees);

						$("#fc_cs_arb_fees").val(
							response.data.each_party_share_in_arb_fees
						);
						$("#fc_rs_arb_fees").val(
							response.data.each_party_share_in_arb_fees
						);

						$("#fc_cs_adm_fees").val(response.data.administrative_charges);
						$("#fc_rs_adm_fees").val(response.data.administrative_charges);

						$(".calculation_col").show();
						$(".domestic_calculation_col").show();
					}

					if (type_of_arbitration == "INTERNATIONAL") {
						// SET THE ADMINISTRATIVE CHARGES CALCUALTION DETAILS FOR INTERNATIONAL CASES

						// Basic Amount
						$("#fc_adm_basic_amount").val(
							data.administrative_charges_details.basic_amount
						);

						// Excess Amount
						$("#fc_adm_actual_excess_amount").val(
							data.administrative_charges_details.excess_amount
						);

						// Basic Administrative Charges
						$("#fc_adm_basic_adm_fees").val(
							data.administrative_charges_details.basic_administrative_cost
						);

						// Excess Amount
						$("#fc_adm_excess_amount").val(
							data.administrative_charges_details.aaf_excess_amount_percent
						);
						$("#fc_adm_excess_percentage").val(
							data.administrative_charges_details.excess_amount_percent
						);

						// =======================================
						$("#int_arb_total_fees_dollar").val(
							response.data.total_arbitrator_fees_dollar
						);
						$("#int_arb_total_fees_rupee").val(
							response.data.total_arbitrator_fees_rupee
						);

						$("#int_total_adm_charges_dollar").val(
							response.data.administrative_charges_dollar
						);
						$("#int_total_adm_charges_rupee").val(
							response.data.administrative_charges_rupee
						);

						$("#int_arb_claim_share_fees_dollar").val(
							response.data.each_party_share_in_arb_fees_dollar
						);
						$("#int_arb_claim_share_fees_rupee").val(
							response.data.each_party_share_in_arb_fees_rupee
						);

						$("#int_claim_adm_charges_dollar").val(
							response.data.each_party_administrative_charges_dollar
						);
						$("#int_claim_adm_charges_rupee").val(
							response.data.each_party_administrative_charges_rupee
						);

						$("#int_arb_res_share_fees_dollar").val(
							response.data.each_party_share_in_arb_fees_dollar
						);
						$("#int_arb_res_share_fees_rupee").val(
							response.data.each_party_share_in_arb_fees_rupee
						);

						$("#int_res_adm_charges_dollar").val(
							response.data.each_party_administrative_charges_dollar
						);
						$("#int_res_adm_charges_rupee").val(
							response.data.each_party_administrative_charges_rupee
						);

						$(".calculation_col").show();
						$(".international_calculation_col").show();
					}

					assessment_sheet_data = [
						{
							type_of_arbitration: type_of_arbitration,
							data: data,
							claim_amount: claim_amount,
							claim_assessed_seperately: "no",
						},
					];

					// Generate assessment view
					generate_assessment_view(
						type_of_arbitration,
						data,
						claim_amount,
						0,
						"no",
						"calculated_fees_content_div",
						"show_calculated_fees_div"
					);
				} else if (response.status == "validation_error") {
					Swal.fire({
						icon: "error",
						title: "Validation Error",
						text: response.msg,
					});
				} else if (response.status == false) {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response.msg,
					});
				} else {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: "Server Error",
					});
				}
			},
			error: function (response) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Server Error, please try again",
				});
			},
		});
	} else {
		$("#btn_submit").prop("disabled", true);
		$(".fees-wrapper-col").hide();
		toastr.error(
			"Claim amount, arbitral tribunal strength and type of arbitration is required to calculate the fees"
		);
	}
}

// Function is used to calculate the fees of claim (with counter claim)
function calculate_seperate_assessed_fees(
	claim_amount,
	counter_claim_amount,
	arbitral_tribunal_strength,
	type_of_arbitration
) {
	if (
		claim_amount &&
		claim_amount > 1000 &&
		counter_claim_amount &&
		counter_claim_amount > 1000 &&
		arbitral_tribunal_strength &&
		type_of_arbitration
	) {
		$.ajax({
			url: base_url + "calculate-fees/case-fee-seperate-assessed",
			type: "POST",
			data: {
				claim_amount: claim_amount,
				counter_claim_amount: counter_claim_amount,
				arbitral_tribunal_strength: arbitral_tribunal_strength,
				type_of_arbitration: type_of_arbitration,
			},
			success: function (response) {
				var response = JSON.parse(response);

				if (response.status == true) {
					var data = response.data;

					// Additional Arbitrator Fees (Only if arbitral tribunal strength is 1)
					if (arbitral_tribunal_strength == 1) {
						$("#fc_additional_arb_amount").val(
							data.claim_fees.additional_arb_fees_details
								? data.claim_fees.additional_arb_fees_details
										.aaf_total_fee_percent
								: 0
						);
						$("#fc_cc_additional_arb_amount").val(
							data.counter_fees.additional_arb_fees_details
								? data.counter_fees.additional_arb_fees_details
										.aaf_total_fee_percent
								: 0
						);

						// Additional Arbitrator Fees
						$("#fc_additional_arb_percentage").val(
							data.claim_fees.additional_arb_fees_details
								? data.claim_fees.additional_arb_fees_details
										.aaf_total_fee_percentage
								: 0
						);
						$("#fc_cc_additional_arb_percentage").val(
							data.counter_fees.additional_arb_fees_details
								? data.counter_fees.additional_arb_fees_details
										.aaf_total_fee_percentage
								: 0
						);
					} else {
						$("#fc_additional_arb_amount").val(0);
						$("#fc_cc_additional_arb_amount").val(0);

						$("#fc_additional_arb_percentage").val(0);
						$("#fc_cc_additional_arb_percentage").val(0);
					}

					// Basic Amount
					$("#fc_basic_amount").val(data.claim_fees.basic_amount);
					$("#fc_cc_basic_amount").val(data.counter_fees.basic_amount);

					// Excess Amount
					$("#fc_actual_excess_amount").val(
						data.claim_fees.excess_amount ? data.claim_fees.excess_amount : 0
					);
					$("#fc_cc_actual_excess_amount").val(
						data.counter_fees.excess_amount
							? data.counter_fees.excess_amount
							: 0
					);

					// Basic Arbitrator Fees
					$("#fc_basic_arb_fees").val(data.claim_fees.basic_arbitrator_fees);
					$("#fc_cc_basic_arb_fees").val(
						data.counter_fees.basic_arbitrator_fees
					);

					// Excess Amount
					$("#fc_excess_amount").val(
						data.claim_fees.excess_amount_percent_details
							? data.claim_fees.excess_amount_percent_details
									.excess_amount_percent
							: 0
					);
					$("#fc_cc_excess_amount").val(
						data.counter_fees.excess_amount_percent_details
							? data.counter_fees.excess_amount_percent_details
									.excess_amount_percent
							: 0
					);

					$("#fc_excess_percentage").val(
						data.claim_fees.excess_amount_percent_details
							? data.claim_fees.excess_amount_percent_details
									.excess_amount_percentage
							: 0
					);
					$("#fc_cc_excess_percentage").val(
						data.counter_fees.excess_amount_percent_details
							? data.counter_fees.excess_amount_percent_details
									.excess_amount_percentage
							: 0
					);

					if (type_of_arbitration == "DOMESTIC") {
						// Total arbitrator fees
						$("#fc_total_arb_fees").val(
							response.data.claim_fees.total_arbitrator_fees
						);
						$(".cc_total_arb_fees_col").show();
						$("#fc_cc_total_arb_fees").val(
							response.data.counter_fees.total_arbitrator_fees
						);

						// ===============================================
						// Claimant Share
						// ===============================================
						$("#fc_cs_arb_fees").val(
							response.data.claim_fees.each_party_share_in_arb_fees
						);
						$("#fc_cs_adm_fees").val(
							response.data.claim_fees.administrative_charges
						);

						// ===============================================
						// Respondent share
						// ===============================================
						$("#fc_rs_arb_fees").val(
							response.data.counter_fees.each_party_share_in_arb_fees
						);
						$("#fc_rs_adm_fees").val(
							response.data.counter_fees.administrative_charges
						);

						$(".calculation_col").show();
						$(".domestic_calculation_col").show();
					}

					/**
					 * International ============================================
					 */
					// WE NEED TO FIX THIS
					if (type_of_arbitration == "INTERNATIONAL") {
						// Basic Amount
						$("#fc_adm_basic_amount").val(
							response.data.claim_fees.administrative_charges_details
								.basic_amount
						);

						// Excess Amount
						$("#fc_adm_actual_excess_amount").val(
							response.data.claim_fees.administrative_charges_details
								.excess_amount
						);

						// Basic Administrative Charges
						$("#fc_adm_basic_adm_fees").val(
							response.data.claim_fees.administrative_charges_details
								.basic_administrative_cost
						);

						// Excess Amount
						$("#fc_adm_excess_amount").val(
							response.data.claim_fees.administrative_charges_details
								.aaf_excess_amount_percent
						);
						$("#fc_adm_excess_percentage").val(
							response.data.claim_fees.administrative_charges_details
								.excess_amount_percent
						);

						// Total arbitrator fees (claim + counter claim)
						$("#int_arb_total_fees_dollar").val(
							response.data.claim_fees.total_arbitrator_fees_dollar
						);
						$("#int_arb_total_fees_rupee").val(
							response.data.claim_fees.total_arbitrator_fees_rupee
						);

						// Total administrative charges (claim + counter claim)
						$("#int_total_adm_charges_dollar").val(
							response.data.claim_fees.administrative_charges_dollar
						);
						$("#int_total_adm_charges_rupee").val(
							response.data.claim_fees.administrative_charges_rupee
						);

						// =======================================================
						$(".cc_international_total_arb_adm_fees_col").show();

						// Basic Amount
						$("#fc_cc_adm_basic_amount").val(
							response.data.counter_fees.administrative_charges_details
								.basic_amount
						);

						// Excess Amount
						$("#fc_cc_adm_actual_excess_amount").val(
							response.data.counter_fees.administrative_charges_details
								.excess_amount
						);

						// Basic Administrative Charges
						$("#fc_cc_adm_basic_adm_fees").val(
							response.data.counter_fees.administrative_charges_details
								.basic_administrative_cost
						);

						// Excess Amount
						$("#fc_cc_adm_excess_amount").val(
							response.data.counter_fees.administrative_charges_details
								.aaf_excess_amount_percent
						);
						$("#fc_cc_adm_excess_percentage").val(
							response.data.counter_fees.administrative_charges_details
								.excess_amount_percent
						);

						$("#cc_int_arb_total_fees_dollar").val(
							response.data.counter_fees.total_arbitrator_fees_dollar
						);

						$("#cc_int_arb_total_fees_rupee").val(
							response.data.counter_fees.total_arbitrator_fees_rupee
						);

						$("#cc_int_total_adm_charges_dollar").val(
							response.data.counter_fees.administrative_charges_dollar
						);

						$("#cc_int_total_adm_charges_rupee").val(
							response.data.counter_fees.administrative_charges_rupee
						);

						// ===============================================
						// Claimant arbitrator fees
						// ===============================================
						$("#int_arb_claim_share_fees_dollar").val(
							response.data.claim_fees.each_party_share_in_arb_fees_dollar
						);
						$("#int_arb_claim_share_fees_rupee").val(
							response.data.claim_fees.each_party_share_in_arb_fees_rupee
						);

						// Claimant administrative charges
						$("#int_claim_adm_charges_dollar").val(
							response.data.claim_fees.each_party_administrative_charges_dollar
						);
						$("#int_claim_adm_charges_rupee").val(
							response.data.claim_fees.each_party_administrative_charges_rupee
						);

						// ===============================================
						// Respondent arbitrator fees
						// ===============================================
						$("#int_arb_res_share_fees_dollar").val(
							response.data.counter_fees.each_party_share_in_arb_fees_dollar
						);
						$("#int_arb_res_share_fees_rupee").val(
							response.data.counter_fees.each_party_share_in_arb_fees_rupee
						);

						// Respondent administrative charges
						$("#int_res_adm_charges_dollar").val(
							response.data.counter_fees
								.each_party_administrative_charges_dollar
						);
						$("#int_res_adm_charges_rupee").val(
							response.data.counter_fees.each_party_administrative_charges_rupee
						);

						$(".calculation_col").show();
						$(".international_calculation_col").show();
					}

					assessment_sheet_data = [
						{
							type_of_arbitration: type_of_arbitration,
							data: data,
							claim_amount: claim_amount,
							counter_claim_amount: counter_claim_amount,
							claim_assessed_seperately: "yes",
						},
					];

					// Generate assessment view
					generate_assessment_view(
						type_of_arbitration,
						data,
						claim_amount,
						counter_claim_amount,
						"yes",
						"calculated_fees_content_div",
						"show_calculated_fees_div"
					);
				} else if (response.status == "validation_error") {
					Swal.fire({
						icon: "error",
						title: "Validation Error",
						text: response.msg,
					});
				} else if (response.status == false) {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response.msg,
					});
				} else {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: "Server Error",
					});
				}
			},
			error: function (response) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Server Error, please try again",
				});
			},
		});
	} else {
		$("#btn_submit").prop("disabled", true);
		$(".fees-wrapper-col").hide();
		toastr.error(
			"Claim amount (min: 1000), counter claim amount(min: 1000), arbitral tribunal strength and type of arbitration both amounts are reuiqred to calculate the fees"
		);
	}
}

// Function to convert HTML to PDF
async function convertToPDF(element_id) {
	// Create a new jsPDF instance
	// Create a new jsPDF instance
	window.jsPDF = window.jspdf.jsPDF;
	window.html2canvas = html2canvas;

	const jsPDFObject = new jsPDF({
		format: "a4",
		orientation: "portrait",
	});

	// Get the content of the div to be converted
	const divContent = document.getElementById(element_id);
	let fileBlob;

	// Convert divContent to a canvas using html2canvas
	await html2canvas(divContent, {
		scale: 2,
	}) // Adjust scale as needed
		.then((canvas) => {
			// Convert the canvas to an image data URL
			const imgData = canvas.toDataURL("image/jpeg", 1.0);

			// Add the image data to the PDF
			jsPDFObject.addImage(
				imgData,
				"JPEG",
				10,
				10,
				jsPDFObject.internal.pageSize.getWidth() - 20,
				0
			); // Adjust margins and dimensions

			// Save the PDF
			// jsPDFObject.save(new Date().getTime() + ".pdf");

			// Generate a unique filename
			const filename = new Date().getTime() + ".pdf";

			// Save the PDF using Blob and URL.createObjectURL
			fileBlob = jsPDFObject.output("blob");
		});

	return fileBlob;
}
