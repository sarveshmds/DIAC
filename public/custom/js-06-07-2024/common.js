/**
 * Declare functions here...
 */
function get_district(state_code, appendDistId, selected_dist = "") {
	$.ajax({
		url: base_url + "service/get_dist_dropdown",
		type: "POST",
		data: { op_type: "get_dist_name", state_code: state_code },
		success: function (response) {
			var options = "<option value=''>Select</option>";
			var res1 = JSON.parse(response);
			for (var i = 0; i < res1.length; i++) {
				if (selected_dist == res1[i].district_code) {
					selected_dist_check = "selected";
				} else {
					selected_dist_check = "";
				}
				options =
					options +
					"<option value='" +
					res1[i].district_code +
					"' " +
					selected_dist_check +
					">" +
					res1[i].district_name +
					"</option>";
			}
			$("#" + appendDistId).html("");
			$("#" + appendDistId).append(options);
		},
		error: function () {
			toastr.error("Unable to process please contact support");
		},
	});
}
// function get_block(dist_code, appendBlockId, selected_block = "") {
// 	$.ajax({
// 		url: base_url + "service/get_block_dropdown",
// 		type: "POST",
// 		data: { op_type: "get_block_name", dist_code: dist_code },
// 		success: function (response) {
// 			var options = "<option value=''>Select</option>";
// 			var res1 = JSON.parse(response);
// 			for (var i = 0; i < res1.length; i++) {
// 				if (selected_block == res1[i].block_code) {
// 					selected_block = "selected";
// 				} else {
// 					selected_block = "";
// 				}
// 				options =
// 					options +
// 					"<option value='" +
// 					res1[i].block_code +
// 					"' " +
// 					selected_block +
// 					">" +
// 					res1[i].block_name +
// 					"</option>";
// 			}
// 			$("#" + appendBlockId).html("");
// 			$("#" + appendBlockId).append(options);
// 		},
// 		error: function () {
// 			toastr.error("Unable to process please contact support");
// 		},
// 	});
// }

// function get_circle(block_code, appendCircleId, selected_circle = "") {
// 	$.ajax({
// 		url: base_url + "service/get_circle_dropdown",
// 		type: "POST",
// 		data: { op_type: "get_circle_name", block_code: block_code },
// 		success: function (response) {
// 			var options = "<option value=''>Select</option>";
// 			var res1 = JSON.parse(response);
// 			for (var i = 0; i < res1.length; i++) {
// 				if (selected_circle == res1[i].circle_code) {
// 					selected_circle = "selected";
// 				} else {
// 					selected_circle = "";
// 				}
// 				options =
// 					options +
// 					"<option value='" +
// 					res1[i].circle_code +
// 					"' " +
// 					selected_circle +
// 					">" +
// 					res1[i].circle_name +
// 					"</option>";
// 			}
// 			$("#" + appendCircleId).html("");
// 			$("#" + appendCircleId).append(options);
// 		},
// 		error: function () {
// 			toastr.error("Unable to process please contact support");
// 		},
// 	});
// }
// function get_approval_status(appendStatusId) {
// 	$.ajax({
// 		url: base_url + "service/get_approval_status",
// 		type: "POST",
// 		data: { op_type: "get_approval_status" },
// 		success: function (response) {
// 			var options = "<option value=''>Select</option>";
// 			var res1 = JSON.parse(response);
// 			for (var i = 0; i < res1.length; i++) {
// 				options =
// 					options +
// 					"<option value='" +
// 					res1[i].gen_code +
// 					"'>" +
// 					res1[i].description +
// 					"</option>";
// 			}
// 			$("#" + appendStatusId).html("");
// 			$("#" + appendStatusId).append(options);
// 		},
// 		error: function () {
// 			toastr.error("Unable to process please contact support");
// 		},
// 	});
// }
// function get_area_type(appendStatusId) {
// 	$.ajax({
// 		url: base_url + "service/get_area_type",
// 		type: "POST",
// 		data: { op_type: "get_area_type" },
// 		success: function (response) {
// 			var options = "<option value=''>Select</option>";
// 			var res1 = JSON.parse(response);
// 			for (var i = 0; i < res1.length; i++) {
// 				options =
// 					options +
// 					"<option value='" +
// 					res1[i].gen_code +
// 					"'>" +
// 					res1[i].description +
// 					"</option>";
// 			}
// 			$("#" + appendStatusId).html("");
// 			$("#" + appendStatusId).append(options);
// 		},
// 		error: function () {
// 			toastr.error("Unable to process please contact support");
// 		},
// 	});
// }
// function upload_image(formData) {
// 	urls = base_url + "service/applicant_image_upload";
// 	$.ajax({
// 		url: urls,
// 		method: "POST",
// 		data: formData,
// 		cache: false,
// 		contentType: false,
// 		processData: false,
// 		success: function (response) {
// 			try {
// 				var obj = JSON.parse(response);
// 				if (obj.status == false) {
// 					toastr.error(obj.msg);
// 				} else if (obj.status == 101) {
// 					toastr.warning(obj.msg);
// 				} else if (obj.status == 102) {
// 					toastr.warning(obj.msg);
// 				} else if (obj.status == 103) {
// 					toastr.info(obj.msg);
// 				} else {
// 					toastr.success(obj.msg);
// 				}
// 			} catch (e) {
// 				toastr.error(e);
// 			}
// 		},
// 		error: function (err) {
// 			toastr.error(err);
// 		},
// 	});
// }

/**
 * When document is ready
 */
$(document).ready(function () {
	$(".custom-all-date").datepicker({
		format: "dd-mm-yyyy",
		todayHighlight: true,
		clearBtn: true,
		autoclose: true,
	});

	$(".custom-all-previous-dates").datepicker({
		format: "dd-mm-yyyy",
		todayHighlight: true,
		clearBtn: true,
		autoclose: true,
		endDate: "0d", // Disable future dates
	});

	$(".custom-date").datepicker({
		format: "dd-mm-yyyy",
		startDate: "-0d",
		todayHighlight: true,
		autoclose: true,
		maxDate: 0,
	});

	$(".summernote-text-editor").summernote({ height: 100 });
	$(".customized-summernote").summernote({
		height: 150,
		toolbar: [
			// [groupName, [list of button]]
			["style", ["bold", "italic", "underline", "clear"]],
			["font", ["strikethrough", "superscript", "subscript"]],
			["fontsize", ["fontsize"]],
			["color", ["color"]],
			["para", ["ul", "ol", "paragraph"]],
			["height", ["height"]],
		],
	});

	//bootstrap WYSIHTML5 - text editor
	$(".textarea-editor").wysihtml5();

	$(".tooltipTable").tooltipster({
		theme: "tooltipster-punk",
		animation: "grow",
		delay: 200,
		touchDevices: false,
		trigger: "hover",
	});

	$(".select2").select2();
	$(".multiselect").multiselect({
		enableFiltering: true,
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true,
		numberDisplayed: 1,
		buttonWidth: "250px",
		minHeight: 100,
		nonSelectedText: "-- ALL --",
		maxHeight: 250,
	});

	$(".select2").one("select2:open", function (e) {
		$("input.select2-search__field").prop("placeholder", "Type here...");
	});

	$(".yearpicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
	});
	$(".monthpicker").datepicker({
		format: "MM",
		viewMode: "months",
		minViewMode: "months",
	});

	// In changing the country in address
	$(document).on("change", "#address_country", function () {
		var country_code = $(this).val();
		if (country_code) {
			var states = getStatesUsingCountryCode(country_code);

			var options = '<option value="">Select State</option>';
			$.each(states, function (index, state) {
				options +=
					'<option value="' + state.id + '">' + state.name + "</option>";
			});
			$("#address_state").html(options);
			$("#address_state").select2();
		}
	});

	/**
	 * Generate multiple addresses fields
	 * This is common in multiple forms please change name wisely
	 */
	// Generate multiple address fields

	generateTrOfTable(trRowCount);

	// On clicking add row button
	$("#btn-add-address").on("click", function () {
		trRowCount += 1;
		generateTrOfTable(trRowCount);
	});

	// On clicking remove row button
	$(document).on("click", ".btn-remove-address-row", function () {
		if (rowCountArray.length > 1) {
			var rowNumber = $(this).data("row-number");
			$("#tr-" + rowNumber).remove();

			rowCountArray = rowCountArray.filter(function (item) {
				return item !== rowNumber;
			});
		} else {
			toastr.error("You cannot remove last remaining row from table.");
		}
	});

	$(document).on("change", ".common_address_country", function () {
		var country_code = $(this).val();
		var rowCount = $(this).data("row-count");
		if (country_code) {
			var states = getStatesUsingCountryCode(country_code);

			var options = '<option value="">Select State</option>';
			$.each(states, function (index, state) {
				options +=
					'<option value="' + state.id + '">' + state.name + "</option>";
			});
			$("#common_address_state_" + rowCount).html(options);
			$("#common_address_state_" + rowCount).select2();
		}
	});

	// ==============================================
	/**
	 * Edit address
	 * This is common for all. Please made any change wisely
	 */

	$(document).on("click", ".btn-edit-address", function () {
		var id = $(this).data("id");
		if (id) {
			var address = getSingleAddress(id);

			$(".address-modal-title").html('<i class="fa fa-edit"></i> Edit Address');
			$("#address_op_type").val("EDIT_ADDRESS");
			$("#hidden_address_id").val(address.id);
			$("#address_address_one").val(address.address_one);
			$("#address_address_two").val(address.address_two);
			$('#address_country option[value = "' + address.country + '"]').prop(
				"selected",
				true
			);
			$("#address_pincode").val(address.pincode);

			if (address.country) {
				var states = getStatesUsingCountryCode(address.country);

				var options = '<option value="">Select State</option>';
				$.each(states, function (index, state) {
					options +=
						'<option value="' + state.id + '">' + state.name + "</option>";
				});
				$("#address_state").html(options);
				$('#address_state option[value = "' + address.state + '"]').prop(
					"selected",
					true
				);
				$("#address_state").select2();
			}

			$("#add-address-btn").prop("disabled", false);
			$("#addressFormModal").modal("show");
			$("#address_country").select2({
				dropdownParent: $("#addressFormModal"),
			});
		}
	});

	// ==============================================
	/**
	 * Delete address
	 * This is common for all. Please made any change wisely
	 */

	$(document).on("click", ".btn-delete-address", function () {
		var id = $(this).data("id");
		if (id) {
			swal(
				{
					type: "error",
					title: "Are you sure?",
					text: "You want to delete the record.",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Delete",
					cancelButtonText: "Cancel",
				},
				function (isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: base_url + "service/delete_address",
							method: "POST",
							data: {
								id: id,
								csrf_trans_token: $("#address_csrf_token").val(),
							},
							success: function (response) {
								try {
									var obj = JSON.parse(response);
									if (obj.status == true) {
										toastr.success(obj.msg);
										setTimeout(function () {
											window.location.reload();
										}, 1000);
									} else if (obj.status === "validationerror") {
										swal({
											title: "Validation Error",
											text: obj.msg,
											type: "error",
											html: true,
										});
									} else {
										swal({
											title: "Error",
											text: "Something went wrong. Please try again",
											type: "error",
											html: true,
										});
									}
								} catch (e) {
									sweetAlert(
										"Sorry",
										"Unable to Save.Please Try Again !",
										"error"
									);
								}
							},
							error: function (err) {
								toastr.error("unable to save");
							},
						});
					} else {
						swal.close();
					}
				}
			);
		}
	});

	$("#address_form").bootstrapValidator({
		message: "This value is not valid",
		submitButtons: 'button[type="submit"]',
		submitHandler: function (validator, form, submitButton) {
			$.ajax({
				url: base_url + "service/address_operation",
				method: "POST",
				data: new FormData(document.getElementById("address_form")),
				contentType: false,
				processData: false,
				success: function (response) {
					try {
						var obj = JSON.parse(response);
						if (obj.status == true) {
							$("#address_form").trigger("reset");
							toastr.success(obj.msg);
							$(".modal").modal("hide");
						} else if (obj.status === "validationerror") {
							swal({
								title: "Validation Error",
								text: obj.msg,
								type: "error",
								html: true,
							});
						} else {
							swal({
								title: "Error",
								text: "Something went wrong. Please try again",
								type: "error",
								html: true,
							});
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
		fields: {},
	});

	$(document).on("keyup", ".amount_field", function () {
		var amount = $(this).val();
		var helpBlockId = $(this).data("help-block-id");
		console.log(helpBlockId);
		newAmount = toIndianCurrency(amount);
		$("#" + helpBlockId).text(newAmount);
	});
});
