// function engineerCol(emp_cat_type) {
// 	$("#engineer_govt_emp_col").css("display", "none");
// 	if (emp_cat_type == "ENG") {
// 		$("#engineer_govt_emp_col").css("display", "block");
// 	}
// }

function hideBoxes() {
	// $("#disciplinary_complaint_details_box").hide();
	// $("#member_of_tribual_box").hide();
	// $("#empanel_arb_other_box").hide();
	$("#former_judges_bureaucrat_row").hide();
	$("#advocate_enroll_col").hide();
	$(".academic_qualification_details_box").hide();
	$("#net_income_col_for_22").hide();
	$("#net_income_col_for_23").hide();
	$("#other_cat_col").hide();
}

function showFields(emp_cat_type) {
	// Show and hide Former Judges and Bureaucrat Information box
	if (
		emp_cat_type == "F_JUDGE" ||
		emp_cat_type == "RET_BEU" ||
		emp_cat_type == "PROF"
	) {
		$("#former_judges_bureaucrat_row").show();
	} else {
		$("#former_judges_bureaucrat_row").hide();
	}

	// Show and hide ITR fileds and Documents for Former Judges/Senior Advocate/Professors and Bureaucrat
	if (
		emp_cat_type == "F_JUDGE" ||
		emp_cat_type == "S_ADV" ||
		emp_cat_type == "RET_BEU" ||
		emp_cat_type == "PROF"
	) {
		// $("#itr_columns").hide();
		// $("#itr_doc_first_col").hide();
		// $("#itr_doc_second_col").hide();
	} else {
		// $("#itr_columns").show();
		// $("#itr_doc_first_col").show();
		// $("#itr_doc_second_col").show();
	}

	// // show and hide Advocate Enrollment No.
	if ((professional_emp_cat = "ADV")) {
		$("#advocate_enroll_col").show();
	} else {
		$("#advocate_enroll_col").hide();
	}

	if (emp_cat_type === "ENG" && eng_govt_emp === "yes") {
		// $("#itr_columns").hide();
		// $("#itr_doc_first_col").hide();
		// $("#itr_doc_second_col").hide();
		$("#former_judges_bureaucrat_row").show();
	}
	if (emp_cat_type === "ENG" && eng_govt_emp === "no") {
		// $("#itr_columns").show();
		// $("#itr_doc_first_col").show();
		// $("#itr_doc_second_col").show();
		$("#former_judges_bureaucrat_row").show();
	}
}

$(document).ready(function () {
	hideBoxes();
	let emp_cat_type = $("#emp_cat_type").val();
	let professional_emp_cat = $("#professional_emp_cat").val();
	// console.log(emp_cat_type);

	// ifGovtEngineer(emp_cat_type, eng_govt_emp);
	// engineerCol($("#empanellment_category").val());

	// let eng_govt_emp = $("#is_engineer_govt_emp").val();
	// console.log(eng_govt_emp);

	if (emp_cat_type) {
		showFields(emp_cat_type);
	}

	if (professional_emp_cat) {
		showFields(professional_emp_cat);
	}

	// to show speacify other column
	if ($("#empanellment_category").val() == "OTHER") {
		$("#other_cat_col").show();
	}

	if ($("input[name='net_income_for_22']:checked").val() == "yes") {
		$("#net_income_col_for_22").show();
	}
	if ($("input[name='net_income_for_23']:checked").val() == "yes") {
		$("#net_income_col_for_23").show();
	}
	if ($("input[name='academic_qua']:checked").val()) {
		$(".academic_qualification_details_box").show();
	}

	// set resident state in edit
	var hidden_res_country = $("#hidden_resident_country").val();
	var hidden_res_state = $("#hidden_resident_state").val();
	if (hidden_res_country && hidden_res_state !== "") {
		get_states(hidden_res_country, hidden_res_state, "#resident_state");
	}

	// set office state in edit
	var hidden_off_country = $("#hidden_office_country").val();
	var hidden_off_state = $("#hidden_office_state").val();
	if (hidden_off_country && hidden_off_state !== "") {
		get_states(hidden_off_country, hidden_off_state, "#office_state");
	}

	// set corr state in edit
	var hidden_corr_country = $("#hidden_correspondance_country").val();
	var hidden_corr_state = $("#hidden_correspondance_state").val();
	if (hidden_corr_country && hidden_corr_state !== "") {
		get_states(hidden_corr_country, hidden_corr_state, "#correspondance_state");
	}

	$(document).on("click", "#final_submit", function (e) {
		e.preventDefault;
		var diaryNo = $("#diary_no").val();
		var url = base_url + "efiling/empanellment/final-info";
		Swal.fire({
			title: "Are you sure?",
			text: "You want to save this details!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, Final save!",
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: url,
					data: new FormData(document.getElementById("emp_final_form")),
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					success: function (response) {
						var response = JSON.parse(response);
						if (response.status) {
							Swal.fire({
								icon: "success",
								title: "Success",
								text: response.msg,
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = response.redirect_url;
								}
							});
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
			}
		});
	});

	/**
	 * On change of nationality
	 */
	// $(document).on("change", "#nationality", function () {
	// 	let nationality = $(this).val();
	// 	$(".nri_country_col").hide();

	// 	if (nationality == "NRI") {
	// 		$(".nri_country_col").show();
	// 	}
	// });

	// 	Function for set Business address as Residential address
	$("#resident_add_for_office").click(function () {
		if ($("#resident_add_for_office").is(":checked")) {
			$("#office_add_1").val($("#resident_add_1").val());
			$("#office_add_2").val($("#resident_add_2").val());
			$("#office_country").val(
				$("#resident_country").prop("selected", true).val()
			);
			// $("#office_state").val($("#resident_state").prop("selected", true).val());
			let resState = $("#resident_state").val();
			let resCountry = $("#resident_country").val();
			get_states(resCountry, resState, "#office_state");
			$("#office_city").val($("#resident_city").prop("selected", true).val());
			$("#office_pincode").val($("#resident_pincode").val());
		} else {
			$("#office_add_1").val("");
			$("#office_add_2").val("");
			$("#office_country").val("");
			$("#office_state").val("");
			$("#office_city").val("");
			$("#office_pincode").val("");
		}
	});

	// 	Function for set Correspondance address as Residential address
	$("#resident_add_for_corr").click(function () {
		$("#office_add_for_corr").prop("checked", false);

		if ($("#resident_add_for_corr").is(":checked")) {
			$("#correspondance_add_1").val($("#resident_add_1").val());
			$("#correspondance_add_2").val($("#resident_add_2").val());
			$("#correspondance_country").val(
				$("#resident_country").prop("selected", true).val()
			);
			// $("#correspondance_state").val(
			// 	$("#resident_state").prop("selected", true).val()
			// );
			let resState = $("#resident_state").val();
			let resCountry = $("#resident_country").val();
			get_states(resCountry, resState, "#correspondance_state");
			$("#correspondance_city").val(
				$("#resident_city").prop("selected", true).val()
			);
			$("#correspondance_pincode").val($("#resident_pincode").val());
		} else {
			$("#correspondance_add_1").val("");
			$("#correspondance_add_2").val("");
			$("#correspondance_country").val("");
			$("#correspondance_state").val("");
			$("#correspondance_city").val("");
			$("#correspondance_pincode").val("");
		}
	});

	// 	Function for set Correspondance address as office address

	$("#office_add_for_corr").click(function () {
		// alert('working')
		$("#resident_add_for_corr").prop("checked", false);

		if ($("#office_add_for_corr").is(":checked")) {
			$("#correspondance_add_1").val($("#office_add_1").val());
			$("#correspondance_add_2").val($("#office_add_2").val());
			$("#correspondance_country").val(
				$("#office_country").prop("selected", true).val()
			);
			let offState = $("#office_state").val();
			let offCountry = $("#office_country").val();
			get_states(offCountry, offState, "#correspondance_state");
			$("#correspondance_city").val(
				$("office_city").prop("selected", true).val()
			);
			$("#correspondance_pincode").val($("#resident_pincode").val());
		} else {
			$("#correspondance_add_1").val("");
			$("#correspondance_add_2").val("");
			$("#correspondance_country").val("");
			$("#correspondance_state").val("");
			$("#correspondance_city").val("");
			$("#correspondance_pincode").val("");
		}
	});
});

// function for get state for permanent address
$(document).on("change", "#resident_country", function () {
	var country_code = $(this).val();
	get_states(
		country_code,
		$("#hidden_resident_state").val(),
		"#resident_state"
	);
});

// function for get state for correspondence address
$(document).on("change", "#correspondance_country", function () {
	var country_code = $(this).val();
	get_states(
		country_code,
		$("#hidden_correspondance_state").val(),
		"#correspondance_state"
	);
});

// function for get state for business address
$(document).on("change", "#office_country", function () {
	var country_code = $(this).val();
	get_states(country_code, $("#hidden_office_state").val(), "#office_state");
});

// -------------------------------------------
// TRIGGER THE CHANGE
// -------------------------------------------
// $("#resident_country").trigger("change");
// $("#correspondance_country").trigger("change");
// $("#office_country").trigger("change");

function get_states(country_code, select = "", select_ele) {
	$.ajax({
		url: base_url + "states/get-using-country-id",
		type: "POST",
		data: { country_code: country_code },
		success: function (response) {
			var response = JSON.parse(response);
			if (response.status == true) {
				var options = '<option value="">Select State</option>';
				$.each(response.data, function (index, state) {
					if (select == "") {
						options +=
							'<option value="' + state.id + '">' + state.name + "</option>";
					} else {
						if (select == state.id) {
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
					}
				});
				$(select_ele).html(options);
			} else {
				toastr.error(
					response.msg
						? response.msg
						: "Something went wrong, please try again."
				);
			}
		},
	});
}

$(document).on(
	"change",
	"#empanellment_category,.disciplinary_complaint,.member_of_tribual,.empanel_arb_other",
	function (event) {
		event.preventDefault();
		let empCategoryVal = $(this).val();
		// alert(empCategoryVal);
		let empCategoryName = $("#empanellment_category option:selected").text();
		$("#professional_emp_cat").val(
			$("#empanellment_category").prop("selected", true).val()
		);

		let disciplinary_complaint = $(
			"input[name='disciplinary_complaint']:checked"
		).val();
		let member_of_tribual = $("input[name='member_of_tribual']:checked").val();
		let empanel_arb_other = $("input[name='empanel_arb_other']:checked").val();
		// console.log(empanel_arb_other)
		// console.log(empCategoryName)

		// Add column for Engineer is on Govt panel or not
		if (empCategoryName == "Engineers") {
			// $("#emp_cat_col,#emp_profile_col").removeClass("col-md-6");
			// $("#emp_cat_col,#emp_profile_col").addClass("col-md-4");
			// $("#engineer_govt_emp_col").css("display", "block");
		} else {
			// $("#emp_cat_col,#emp_profile_col").removeClass("col-md-4");
			// $("#emp_cat_col,#emp_profile_col").addClass("col-md-6");
			// $("#engineer_govt_emp_col").css("display", "none");
		}

		// for disciplinary/complaint radio button
		if (disciplinary_complaint == "yes") {
			$("#disciplinary_complaint_details_box").show();
		} else {
			$("#disciplinary_complaint_details_box").hide();
		}

		// for member of any Tribunal/Authority/Quasi-Judicial radio button
		if (member_of_tribual == "yes") {
			$("#member_of_tribual_box").show();
		} else {
			$("#member_of_tribual_box").hide();
		}

		// for already empanelled as an Arbitrator radio button
		if (empanel_arb_other == "yes") {
			$("#empanel_arb_other_box").show();
		} else {
			$("#empanel_arb_other_box").hide();
		}

		// show and hide specify field for Other category
		if (empCategoryVal == "OTHER") {
			$("#other_cat_col").show();
		} else {
			$("#other_cat_col").hide();
		}
	}
);

$(document).on("click", "#empanel_arb_add_more_btn", () => {
	$("#empanel_arb_other_box").append($("#empanel_arb_other_col").clone());
});

$(document).on("click", ".removeOtherInstituteBtn", function () {
	let id = $(this).data("id");
	let Url = base_url + "efilling/empanellment_controller/remove-other-insitute";
	$.ajax({
		url: Url,
		type: "POST",
		data: { id: id },
		dataType: "json",
		success: function (response) {
			if (response && response.status == true) {
				Swal.fire({
					icon: "success",
					title: "Success",
					text: response.msg,
				});
				window.location.reload();
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: response ? response.msg : "Invalid JSON response",
				});
			}
		},
		error: function () {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "An error occurred during the AJAX request.",
			});
		},
	});
});

// function for hide and show academic qualification details box
$(document).on("change", 'input[name="academic_qua"]', (event) => {
	event.preventDefault();
	let box = $("input[name='academic_qua']:checked").val();
	if (box) {
		$(".academic_qualification_details_box").show();
	} else {
		$(".academic_qualification_details_box").hide();
	}
});

// Function for hide and show itr fields for years 21-22
$(document).on("change", ".net_income_for_22", () => {
	let net_income_box = $("input[name='net_income_for_22']:checked").val();
	if (net_income_box == "yes") {
		$("#net_income_col_for_22").show();
	} else {
		$("#net_income_col_for_22").hide();
	}
});

// Function for hide and show itr fields for years 23-24
$(document).on("change", ".net_income_for_23", () => {
	let net_income_box = $("input[name='net_income_for_23']:checked").val();
	if (net_income_box == "yes") {
		$("#net_income_col_for_23").show();
	} else {
		$("#net_income_col_for_23").hide();
	}
});

/**
 * EMPANELMENT CATEGORY: On change
 */
$("#empanellment_category").on("change", function () {
	let empanellment_category = $(this).val();
	console.log(empanellment_category);
	$("#engineer_govt_emp_col").hide();

	if (empanellment_category == "ENG") {
		$("#engineer_govt_emp_col").show();
	}
});

// ========================================================
$(document).ready(function () {
	// START EMPANELEMENT FORM
	$("#start_emp_form").validate({
		errorClass: "is-invalid text-danger",
		rules: {
			email: {
				required: true,
			},
			phone_number: {
				required: true,
			},
			txt_captcha: {
				required: true,
			},
			messages: {
				txt_captcha: "Please enter captcha.",
				email: "Please enter your email address.",
				phone_number: "Please enter phone number.",
			},
		},
		submitHandler: function (form, event) {
			event.preventDefault();
			var url = base_url + "efiling/empanellment/store-start";
			$.ajax({
				url: url,
				data: new FormData(document.getElementById("start_emp_form")),
				type: "POST",
				contentType: false,
				processData: false,
				cache: false,
				success: function (response) {
					var response = JSON.parse(response);
					if (response.status == true) {
						Swal.fire({
							icon: "success",
							title: "Success",
							text: response.msg,
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = response.redirect_url;
							}
						});
					} else if (response.status == "ALREADY_SUBMITTED") {
						Swal.fire({
							icon: "success",
							title: "Already Submitted",
							html: response.msg,
						});
						window.location.reload();
					} else if (response.status == "validation_error") {
						Swal.fire({
							icon: "error",
							title: "Validation Error",
							html: response.msg,
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
		},
	});

	// Store Personal info
	$("#empanelment_personal_info_form").validate({
		errorClass: "is-invalid text-danger",
		rules: {
			empanellment_category: {
				required: true,
			},
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
			email: {
				required: true,
				email: true,
			},
			mobile: {
				required: true,
				number: true,
				minlength: 10,
				maxlength: 10,
			},
			dob: {
				required: true,
			},
			languages_known: {
				required: true,
			},
			nationality: {
				required: true,
			},
			resident_add_1: {
				required: true,
			},
			resident_country: {
				required: true,
			},
			resident_state: {
				required: true,
			},
			resident_city: {
				required: true,
			},
			resident_pincode: {
				required: true,
			},
			office_add_1: {
				required: true,
			},
			office_country: {
				required: true,
			},
			office_state: {
				required: true,
			},
			office_city: {
				required: true,
			},
			office_pincode: {
				required: true,
			},
			// office_company_name: {
			// 	required: true,
			// },
			// office_phone_number: {
			// 	required: true,
			// },
			// office_webite: {
			// 	required: true,
			// },
			correspondace_add_1: {
				required: true,
			},
			correspondace_country: {
				required: true,
			},
			correspondace_state: {
				required: true,
			},
			correspondace_city: {
				required: true,
			},
			correspondace_pincode: {
				required: true,
			},
		},

		submitHandler: function (form, event) {
			event.preventDefault();

			// Confirmation
			Swal.fire({
				icon: "warning",
				title: "Do you want to submit the form?",
				showCancelButton: true,
				confirmButtonText: "Confirm",
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					var url =
						base_url + "efiling/empanellment/personal-information/store";
					$.ajax({
						url: url,
						data: new FormData(
							document.getElementById("empanelment_personal_info_form")
						),
						type: "POST",
						contentType: false,
						processData: false,
						cache: false,
						success: function (response) {
							var response = JSON.parse(response);
							if (response.status == true) {
								Swal.fire({
									icon: "success",
									title: "Success",
									html: response.msg,
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = response.redirect_url;
									}
								});
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
									html: response.msg,
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
				}
			});
		},
	});

	// Store Professional info
	$("#emp_professional_info_form").validate({
		errorClass: "is-invalid text-danger",
		rules: {},

		submitHandler: function (form, event) {
			event.preventDefault();

			// Confirmation
			Swal.fire({
				icon: "warning",
				title: "Do you want to submit the form?",
				showCancelButton: true,
				confirmButtonText: "Confirm",
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					var url =
						base_url + "efiling/empanellment/professional-information/store";
					$.ajax({
						url: url,
						data: new FormData(
							document.getElementById("emp_professional_info_form")
						),
						type: "POST",
						contentType: false,
						processData: false,
						cache: false,
						success: function (response) {
							var response = JSON.parse(response);
							if (response.status == true) {
								Swal.fire({
									icon: "success",
									title: "Success",
									html: response.msg,
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = response.redirect_url;
									}
								});
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
									html: response.msg,
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
				}
			});
		},
	});

	// Submit Documents
	$("#emp_documents_form").validate({
		errorClass: "is-invalid text-danger",
		rules: {
			hidden_arb_id: {
				required: true,
			},
		},
		submitHandler: function (form, event) {
			event.preventDefault();
			var url = base_url + "efiling/empanellment/documents/store";
			$.ajax({
				url: url,
				data: new FormData(document.getElementById("emp_documents_form")),
				type: "POST",
				contentType: false,
				processData: false,
				cache: false,
				success: function (response) {
					var response = JSON.parse(response);
					if (response.status == true) {
						Swal.fire({
							icon: "success",
							title: "Success",
							text: response.msg,
						}).then((result) => {
							if (result.isConfirmed) {
								window.location.href = response.redirect_url;
							}
						});
					} else if (response.status == "validation_error") {
						Swal.fire({
							icon: "error",
							title: "Validation Error",
							html: response.msg,
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
		},
	});
});

/**
 * DOB: Initialize datepicker
 */

$(document).ready(function () {
	// Get the current year
	var currentYear = new Date().getFullYear();

	$("#arb_emp_date_of_birth").datepicker({
		endDate: "-24y",
		format: "dd-mm-yyyy",
		autoclose: true, // Close the datepicker on selecting
	});
});
