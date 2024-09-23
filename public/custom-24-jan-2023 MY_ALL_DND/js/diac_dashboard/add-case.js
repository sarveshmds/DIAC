/*
 * Common Functions
 */

// On changing the reffered by jugde
$("#cd_reffered_by").on("change", function () {
	var value = $(this).val();
	$(".reffered-judge-name-col").hide();
	$(".reffered-other-name-col").hide();
	if (value == "COURT") {
		$(".reffered-judge-name-col").show();
	}
	if (value == "OTHER") {
		$(".reffered-other-name-col").show();
	}
});

// On changing the type of arbitration
$("#cd_toa").on("change", function () {
	var value = $(this).val();
	if (value) {
		$(".di-type-of-arb-col").show();
	} else {
		$(".di-type-of-arb-col").hide();
	}
});

// check if the case no is re-enetered then remove the error message
$("#cd_case_no").on("keyup", function () {
	var case_no = $(this).val();
	if (case_no) {
		$("#case_no_verification").text("");
	}
});
// Check if the case is already registered or not
$("#cd_case_no").on("blur", function () {
	var case_no = $(this).val();
	if (case_no) {
		$.ajax({
			url: base_url + "/service/check_case_number_already_entered",
			method: "POST",
			data: {
				case_no: case_no,
			},
			complete: function () {
				enable_submit_btn("btn_submit");
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						$("#case_no_verification").text(obj.msg);
						swal({
							title: "Warning",
							text: obj.msg,
							type: "warning",
							html: true,
						});
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("Unable to save");
			},
		});
	}
});

// Generate multiple arbitrator name inputs
var noaTrRowCount = 1;
var noaRowCountArray = [];
// Check if the arbitrator is appointed or not
$("#cd_arbitrator_status").on("change", function () {
	var arbitrator_status = $(this).val();
	$(".name-of-arb-col").hide();
	if (arbitrator_status == 1) {
		$(".name-of-arb-col").show();
		generateNOATr(noaTrRowCount);
	}
});

function getArbitratorCategories() {
	var categories = [];
	$.ajax({
		url: base_url + "service/get_arbitrator_categories",
		type: "POST",
		data: {},
		async: false,
		success: function (response) {
			try {
				categories = JSON.parse(response);
			} catch (e) {
				swal("Sorry", "Unable to Save.Please Try Again !", "error");
			}
		},
		error: function (error) {
			toastr.error("Something went wrong.");
		},
	});
	return categories;
}

function generateNOATr(noaTrRowCount) {
	var categories = getArbitratorCategories();

	var options = "<option value=''>Select Category</option>";
	$.each(categories, function (index, value) {
		options += `<option value='${value.id}'>${value.category_name}</option>`;
	});
	var categorySelect = `<select class="form-control ml-2" name="cd_name_of_judge_category_${noaTrRowCount}">${options}</select>`;

	var tr = `
		<div class="noj-col-row" id="tr-${noaTrRowCount}">
			<input type="text" class="form-control" name="cd_name_of_judge_${noaTrRowCount}" value="" maxlength="255">
      ${categorySelect}
			<button type="button" class="btn btn-danger btn-sm btn-remove-row" id="btn-remove-row-${noaTrRowCount}" data-row-number="${noaTrRowCount}" style="padding: 7px 10px;"><i class="fa fa-minus"></i></button>
		</div>
	`;

	noaRowCountArray.push(noaTrRowCount);
	$(".noj-col-wrapper").append(tr);
}

// On clicking add row button
$("#btn-add-noj").on("click", function () {
	noaTrRowCount += 1;
	generateNOATr(noaTrRowCount);
});

// On clicking remove row button
$(document).on("click", ".btn-remove-row", function () {
	if (noaRowCountArray.length > 1) {
		var rowNumber = $(this).data("row-number");
		$("#tr-" + rowNumber).remove();

		noaRowCountArray = noaRowCountArray.filter(function (item) {
			return item !== rowNumber;
		});
	} else {
		toastr.error("You cannot remove last remaining row from table.");
	}
});

// ==========================================================================================
// Add cause list form
$("#case_det_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("case_det_form"));
		if ($("#cd_arbitrator_status").val() == 1) {
			formData.set("row_count_array", JSON.stringify(noaRowCountArray));
		}

		$.ajax({
			url: base_url + "service/add_case_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == false) {
						// Enable the submit button
						enable_submit_btn("btn_submit");

						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else if (obj.status === "validationerror") {
						// Enable the submit button
						enable_submit_btn("btn_submit");

						swal({
							title: "Validation Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						// Enable the submit button
						enable_submit_btn("btn_submit");

						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								window.location.href = obj.redirect_url;
							}
						);
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		// cd_case_no: {
		// 	//form input type name
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
		cd_case_type: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_diary_number: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_case_title_claimant: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_case_title_respondent: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_reffered_by: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_toa: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_status: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_arbitrator_status: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_toa: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_di_toa: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

function get_diary_numbers(type) {
	if (type) {
		$.ajax({
			url: base_url + "service/get_diary_number",
			method: "POST",
			data: {
				type: type,
			},
			complete: function () {
				// Enable the submit button
				enable_submit_btn("btn_submit");
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						var results = obj.results;
						var hidden_reference_code = $("#hidden_reference_code").val()
							? $("#hidden_reference_code").val()
							: "";

						var option = "<option value=''>Select Diary Number</option>";
						results.forEach((row) => {
							var selected =
								hidden_reference_code && hidden_reference_code == row.code
									? "selected"
									: "";
							option +=
								'<option value="' +
								row.code +
								'" ' +
								selected +
								">" +
								row.diary_number +
								"</option>";
						});

						$("#cd_diary_number").html(option);
						$("#cd_diary_number").select2();
					} else if (obj.status === false) {
						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								window.location.href = obj.redirect_url;
							}
						);
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	}
}

// On changing case type
$("#cd_case_type").on("change", function () {
	var type = $(this).val();
	$("#cd_diary_number").html("");
	$("#cd_case_title_claimant").val("");
	$("#cd_case_title_respondent").val("");
	$("#cd_toa option").prop("selected", false);
	$("#cd_toa").trigger("change");
	$("#cd_diary_number").select2("destroy");

	if (type) {
		get_diary_numbers(type);
	}
});

$(document).ready(function () {
	(function () {
		var case_type = $("#cd_case_type").val();
		get_diary_numbers(case_type);
	})();
});

$("#cd_diary_number").on("change", function () {
	var diary_number = $(this).val();
	var type = $("#cd_case_type").val();

	$("#cd_case_title_claimant").val("");
	$("#cd_case_title_respondent").val("");
	$("#cd_toa option").prop("selected", false);
	$("#cd_toa").trigger("change");

	if (diary_number && type && type == "NEW_REFERENCE") {
		$.ajax({
			url: base_url + "service/get_new_reference_parties",
			method: "POST",
			data: {
				diary_number: diary_number,
				type: type,
			},
			complete: function () {
				// Enable the submit button
				enable_submit_btn("btn_submit");
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						var new_reference = obj.results["new_reference"];

						$("#cd_case_title_claimant").val(obj.results["claimant_name"]);
						$("#cd_case_title_respondent").val(obj.results["respondent_name"]);
						$(
							"#cd_toa option[value='" +
								new_reference.type_of_arbitration +
								"']"
						).prop("selected", true);

						$("#cd_toa").trigger("change");
					} else if (obj.status === false) {
						("");
						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								window.location.href = obj.redirect_url;
							}
						);
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	}
});

// Mask ====
document.addEventListener("DOMContentLoaded", () => {
	for (const el of document.querySelectorAll("[placeholder][data-slots]")) {
		const pattern = el.getAttribute("placeholder"),
			slots = new Set(el.dataset.slots || "_"),
			prev = ((j) =>
				Array.from(pattern, (c, i) => (slots.has(c) ? (j = i + 1) : j)))(0),
			first = [...pattern].findIndex((c) => slots.has(c)),
			accept = new RegExp(el.dataset.accept || "\\d", "g"),
			clean = (input) => {
				input = input.match(accept) || [];
				return Array.from(pattern, (c) =>
					input[0] === c || slots.has(c) ? input.shift() || c : c
				);
			},
			format = () => {
				const [i, j] = [el.selectionStart, el.selectionEnd].map((i) => {
					i = clean(el.value.slice(0, i)).findIndex((c) => slots.has(c));
					return i < 0
						? prev[prev.length - 1]
						: back
						? prev[i - 1] || first
						: i;
				});
				el.value = clean(el.value).join``;
				el.setSelectionRange(i, j);
				back = false;
			};
		let back = false;
		el.addEventListener("keydown", (e) => (back = e.key === "Backspace"));
		el.addEventListener("input", format);
		el.addEventListener("focus", format);
		el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
	}
});
