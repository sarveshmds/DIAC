$(".site-loader").show();

$(document).ready(function () {
	$(".site-loader").hide();
});

// Pending Application: Datatable initialization
var dataTable = $("#dataTableMiscellaneous").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url: base_url + "referral-requests/get-for-datatable",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
			d.status = "PENDING";
		},
		dataSrc: function (response) {
			if (response.status == false) {
				toastr.error(response.msg);
				return false;
			} else {
				return response.data;
			}
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return (
					data.diary_number_prefix +
					"/" +
					data.diary_number +
					"/" +
					data.diary_number_year
				);
			},
		},
		{ data: "case_title" },
		{ data: "created_at" },
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.is_submitted == 1) {
					return '<span class="badge bg-green">Yes</span>';
				}
				return '<span class="badge bg-blue">No</span>';
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.is_approved == 1) {
					return '<span class="badge bg-green">Approved</span>';
				}
				if (data.is_approved == 2) {
					return '<span class="badge bg-red">Rejected</span>';
				}
				return '<span class="badge bg-blue">Pending</span>';
			},
		},
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var buttons = "";

				if (role_code == "CASE_FILER") {
					buttons =
						'<a href="' +
						base_url +
						"referral-requests/add?code=" +
						data.m_code +
						'" class="btn btn-warning" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></a>';
				}
				if (role_code == "HEAD_COORDINATOR") {
					buttons =
						'<a href="' +
						base_url +
						"referral-requests/approval?code=" +
						data.m_code +
						'" class="btn btn-primary" data-tooltip="tooltip" title="View & Approval"><span class="fa fa-paper-plane"></span></a>';
				}

				return buttons;
			},
		},
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Approved Application: Datatable initialization
// var dataTableApprovedNotRegMiscellaneous = $(
// 	"#dataTableApprovedNotRegMiscellaneous"
// ).DataTable({
// 	processing: true,
// 	serverSide: true,
// 	autoWidth: false,
// 	responsive: true,
// 	searching: false,
// 	order: [],
// 	ajax: {
// 		url: base_url + "referral-requests/get-for-datatable",
// 		type: "POST",
// 		data: function (d) {
// 			d.csrf_trans_token = $("#csrf_trans_token").val();
// 			d.status = "APPROVED_NOT_REG";
// 		},
// 		dataSrc: function (response) {
// 			if (response.status == false) {
// 				toastr.error(response.msg);
// 				return false;
// 			} else {
// 				return response.data;
// 			}
// 		},
// 	},
// 	columns: [
// 		{
// 			data: null,
// 			render: function (data, type, row, meta) {
// 				return meta.row + meta.settings._iDisplayStart + 1;
// 			},
// 		},
// 		{
// 			data: null,
// 			render: function (data, type, row, meta) {
// 				return data.diary_number_prefix + "/" + data.diary_number;
// 			},
// 		},
// 		{ data: "case_title" },
// 		{ data: "created_at" },
// 		{
// 			data: null,
// 			render: function (data, type, row, meta) {
// 				if (data.is_registered == 1) {
// 					return '<span class="badge bg-green">Yes</span>';
// 				}
// 				if (data.is_registered == 0) {
// 					return '<span class="badge bg-red">No</span>';
// 				}
// 				return '<span class="badge bg-blue"></span>';
// 			},
// 		},
// 		{
// 			data: null,
// 			render: function (data, type, row, meta) {
// 				if (data.is_approved == 1) {
// 					return '<span class="badge bg-green">Approved</span>';
// 				}
// 				if (data.is_approved == 2) {
// 					return '<span class="badge bg-red">Rejected</span>';
// 				}
// 				return '<span class="badge bg-blue">Pending</span>';
// 			},
// 		},
// 		{ data: "remarks" },
// 		{
// 			data: null,
// 			sWidth: "15%",
// 			sClass: "alignCenter",
// 			render: function (data, type, row, meta) {
// 				var buttons = "";

// 				buttons =
// 					'<a href="' +
// 					base_url +
// 					"referral-requests/view?code=" +
// 					data.m_code +
// 					'" class="btn btn-warning" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a> ';

// 				if (role_code == "CASE_FILER") {
// 					buttons +=
// 						'<button type="button" data-m-code="' +
// 						data.m_code +
// 						'" class="btn btn-success btnRegisterAndAllocate" data-tooltip="tooltip" title="Register the case"><span class="fa fa-check"></span></button>';
// 				}

// 				return buttons;
// 			},
// 		},
// 	],
// 	columnDefs: [
// 		{
// 			targets: ["_all"],
// 			orderable: false,
// 			sorting: false,
// 		},
// 	],
// 	dom:
// 		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
// 		"<'row'<'col-sm-12'tr>>" +
// 		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
// 	buttons: [],
// 	drawCallback: function () {
// 		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
// 	},
// });

// Approved Application: Datatable initialization
var dataTable = $("#dataTableApprovedMiscellaneous").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url: base_url + "referral-requests/get-for-datatable",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
			d.status = "APPROVED_AND_REG";
		},
		dataSrc: function (response) {
			if (response.status == false) {
				toastr.error(response.msg);
				return false;
			} else {
				return response.data;
			}
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return (
					data.diary_number_prefix +
					"/" +
					data.diary_number +
					"/" +
					data.diary_number_year
				);
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return (
					data.case_no_prefix + "/" + data.case_no + "/" + data.case_no_year
				);
			},
		},
		{ data: "case_title" },
		{ data: "created_at" },
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.is_registered == 1) {
					return '<span class="badge bg-green">Yes</span>';
				}
				if (data.is_registered == 0) {
					return '<span class="badge bg-red">No</span>';
				}
				return '<span class="badge bg-blue"></span>';
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.is_approved == 1) {
					return '<span class="badge bg-green">Approved</span>';
				}
				if (data.is_approved == 2) {
					return '<span class="badge bg-red">Rejected</span>';
				}
				return '<span class="badge bg-blue">Pending</span>';
			},
		},
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var buttons = "";

				buttons =
					'<a href="' +
					base_url +
					"referral-requests/view?code=" +
					data.m_code +
					'" class="btn btn-warning" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>';

				return buttons;
			},
		},
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

$(document).ready(function () {
	if (role_code == "CASE_FILER") {
		$("#dataTableMiscellaneous_wrapper .dt-custom-btn-col .dt-buttons").html(
			'<a class="btn btn-custom" type="button" href="' +
				base_url +
				'referral-requests/add"><span class="fa fa-plus"></span> Add New</a>'
		);
	}
});

// Delete function for cause list
function btnDeleteMiscellaneous(id) {
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
						url: base_url + "service/delete_miscellaneous",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_trans_token").val(),
						},
						success: function (response) {
							try {
								var obj = JSON.parse(response);

								if (obj.status == false) {
									$("#errorlog").html("");
									$("#errorlog").hide();
									toastr.error(obj.msg);
								} else if (obj.status === "validationerror") {
									swal({
										title: "Validation Error",
										text: obj.msg,
										type: "error",
										html: true,
									});
								} else {
									$("#errorlog").html("");
									$("#errorlog").hide();
									toastr.success(obj.msg);

									dataTable.ajax.reload();
								}
							} catch (e) {
								swal("Sorry", "Unable to Save.Please Try Again !", "error");
							}
						},
						error: function (error) {
							toastr.error("Something went wrong.");
						},
					});
				} else {
					swal.close();
				}
			}
		);
	}
}

// Function to enable the submit button
function enable_submit_btn() {
	$("#btn_submit").attr("disabled", false);
}

// ==================================================================================
// Filter the case list

$("#ac_f_btn").on("click", function () {
	dataTable.ajax.reload();
});

$("#ac_f_reset_btn").on("click", function () {
	$("#ac_f_case_no").val("");
	$("#ac_f_case_title").val("");
	$("#ac_f_arb_pet").val("");
	$("#ac_f_toa option").prop("selected", false);
	$("#ac_f_registered_on_from").val("");
	$("#ac_f_registered_on_to").val("");
	$("#ac_f_date_of_award_from").val("");
	$("#ac_f_date_of_award_to").val("");
	$("#ac_f_referred_on").val("");
	$("#ac_f_reffered_by option").prop("selected", false);
	$("#ac_f_reffered_by_judge").val("");
	$("#ac_f_name_of_arbitrator").val("");
	$("#ac_f_name_of_counsel").val("");
	$("#ac_f_status option").prop("selected", false);
	dataTable.ajax.reload();
});

// ==========================================================================================
// Add Miscellaneous
// ==========================================================================================
$("#refferal_req_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_refferal_request_save").attr("disabled", "disabled");

		var formData = new FormData(document.getElementById("refferal_req_form"));

		formData.append(
			"claimant_row_count_array",
			JSON.stringify(claimantRowCountArray)
		);
		formData.append(
			"respondant_row_count_array",
			JSON.stringify(respondentRowCountArray)
		);

		let operationType = $("#refferal_req_op_type").val();

		let url = base_url + "referral-requests/store";

		if (operationType == "EDIT_MISCELLANEOUS") {
			url = base_url + "referral-requests/update";
		}

		$.ajax({
			url: url,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("btn_refferal_request_save");
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								let m_code = obj.data.m_code;
								window.location.href =
									base_url + "referral-requests/add?code=" + m_code;
							}
						);
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
							text: obj.msg,
							type: "error",
							html: true,
						});
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				// Enable the submit button
				enable_submit_btn("btn_refferal_request_save");
				toastr.error("unable to save");
			},
		});
	},
	fields: {},
});

// ==========================================================================================

// Final submit form submission
// ==========================================================================================
$("#refferal_req_final_submit_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		swal(
			{
				title: "Are you sure, you want to final submit the case?",
				text: "Once confirmed, the case will be allocated to deputy counsel, case manager and additional coordinator. And also DIAC Registration No. will be generated and communication will be send to parties and arbitrators. the action cannot be undone!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, Confirm!",
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					$("#btn_ref_req_final_submit").attr("disabled", "disabled");

					var formData = new FormData(
						document.getElementById("refferal_req_final_submit_form")
					);

					$.ajax({
						url: base_url + "referral-requests/final-submit",
						method: "POST",
						data: formData,
						contentType: false,
						processData: false,
						cache: false,
						success: function (response) {
							// Enable the submit button
							enable_submit_btn("btn_ref_req_final_submit");
							try {
								var obj = JSON.parse(response);
								if (obj.status == true) {
									swal(
										{
											title: "Success",
											text: obj.msg,
											type: "success",
											html: true,
										},
										function () {
											window.location.href = base_url + "referral-requests";
										}
									);
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
										text: obj.msg,
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
							// Enable the submit button
							enable_submit_btn("btn_ref_req_final_submit");
							toastr.error("unable to save");
						},
					});
				} else {
					// Enable the submit button
					enable_submit_btn("btn_ref_req_final_submit");
					// Handle the cancel action if necessary
					swal.close();
				}
			}
		);
	},
	fields: {},
});

// ======================================================
var dataTableMiscellaneousReply = $("#dataTableMiscellaneousReply").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url:
			base_url +
			"service/get_all_miscellaneous_replies_list/ALL_MISCELLANEOUS_REPLIES_LIST",
		type: "POST",
		data: function (d) {
			d.miscellaneous_id = $("#hidden_miscellaneous_id").val();
			d.csrf_trans_token = $("#csrf_trans_token").val();
		},
		dataSrc: function (response) {
			if (response.status == false) {
				toastr.error(response.msg);
				return false;
			} else {
				return response.data;
			}
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return parseInt(data.serial_no) + 1;
			},
		},
		{ data: "reply" },
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.reply_from_user) {
					return data.reply_from_user + " (" + data.reply_from_job_title + ")";
				}
				return "";
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.reply_to_user) {
					return data.reply_to_user + " (" + data.reply_to_job_title + ")";
				}
				return "";
			},
		},
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		{
			text: '<span class="fa fa-mail-reply"></span> Add Reply',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_miscellaneous_reply_list_modal_open();
			},
		},
		{
			text: '<span class="fa fa-file-pdf-o"></span> PDF',
			className: "btn btn-danger",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				window.open(
					base_url +
						"pdf/miscellaneous-reply/" +
						$("#hidden_miscellaneous_id").val(),
					"_BLANK"
				);
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_miscellaneous_reply_list_modal_open() {
	$("#miscellaneous_reply_modal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#miscellaneous_reply_modal").on("hidden.bs.modal", function (e) {
	$("#miscellaneous_reply_form").trigger("reset");
	$(".miscellaneous_reply-modal-title").html("");
	$("#hidden_miscellaneous_reply_id").val("");
	// $("#miscellaneous_reply_op_type").val("");

	$("#miscellaneous_reply_to").select2("val", "");
	$("#miscellaneous_reply_text").summernote("code", "");
	$("#miscellaneous_reply_form").data("bootstrapValidator").resetForm(true);
});

// Add Miscellaneous Reply
$("#miscellaneous_reply_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_submit").attr("disabled", "disabled");
		var formData = new FormData(
			document.getElementById("miscellaneous_reply_form")
		);

		$.ajax({
			url: base_url + "service/miscellaneous_reply_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("btn_submit");
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								$("#miscellaneous_reply_modal").modal("hide");
								dataTableMiscellaneousReply.ajax.reload();
							}
						);
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
							text: obj.msg,
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

/**
 * =======================================================
 */

// On changing the reffered by jugde
$("#cd_reffered_by").on("change", function () {
	var value = $(this).val();
	$(".reffered_by_court_details").hide();
	$(".reffered-other-name-col").hide();
	console.log(value);
	if (value == "COURT") {
		$(".reffered_by_court_details").show();
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

$("#cd_arbitrator_status").on("change", function () {
	let cd_arbitrator_status = $(this).val();
	$(".arbitral_tribunal_stength_col").hide();
	$("#btn_arbitrator_nav_tab").hide();
	$("#tab_4").hide();

	if (cd_arbitrator_status == 1) {
		$(".arbitral_tribunal_stength_col").show();
		$("#btn_arbitrator_nav_tab").show();
		$("#tab_4").show();
	}
});

$("#cd_arbitrator_is_empanelled").on("change", function () {
	let cd_arbitrator_is_empanelled = $(this).val();
	$(".emp_arbitrator_list_col").hide();
	$(".not_empanelled_arb_wrapper").hide();

	if (cd_arbitrator_is_empanelled == 1) {
		$(".emp_arbitrator_list_col").show();
	}
	if (cd_arbitrator_is_empanelled == 2) {
		$(".not_empanelled_arb_wrapper").show();
	}
});

$("#permanent_country").on("change", function () {
	let permanent_country = $(this).val();
	if (permanent_country) {
		var states = getStatesUsingCountryCode(permanent_country);

		var options = '<option value="">Select State</option>';
		$.each(states, function (index, state) {
			options += '<option value="' + state.id + '">' + state.name + "</option>";
		});
		$("#permanent_state").html(options);
	}
});

// ==========================================================================================
// ==========================================================================================
// Approval Rejection Form
$("#refferal_req_approval_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_app_rej_submit").attr("disabled", "disabled");

		var formData = new FormData(
			document.getElementById("refferal_req_approval_form")
		);

		$.ajax({
			url: base_url + "referral-requests/change-status",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("btn_app_rej_submit");
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								window.location.href = base_url + "referral-requests";
							}
						);
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
							text: obj.msg,
							type: "error",
							html: true,
						});
					}
				} catch (e) {
					// Enable the submit button
					enable_submit_btn("btn_app_rej_submit");
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				// Enable the submit button
				enable_submit_btn("btn_app_rej_submit");
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		approval_rejection_status: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		// approval_rejection_remarks: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
	},
});

// ===============================================
// Approve and register th case as well as allocate the case
$(document).on("click", ".btnRegisterAndAllocate", function () {
	let m_code = $(this).data("m-code");
	if (m_code) {
		swal(
			{
				title: "Are you sure?",
				text: "You want to allocate the DIAC registration number and allocate the case to respective persons.",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#33dc0b",
				confirmButtonText: "Confirm",
				closeOnConfirm: false,
			},
			function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: base_url + "referral-requests/register-and-allocate-case",
						method: "POST",
						data: {
							m_code: m_code,
						},
						success: function (response) {
							var obj = JSON.parse(response);
							if (obj.status == true) {
								swal(
									{
										title: "Success",
										text: obj.msg,
										type: "success",
										html: true,
									},
									function () {
										window.location.reload();
									}
								);
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
									text: obj.msg,
									type: "error",
									html: true,
								});
							}
						},
						error: function (err) {
							toastr.error("unable to save");
						},
					});
				}
			}
		);
	}
});

// =============================================================================
// =============================================================================

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

$(document).on("change", ".cl_address_country", function () {
	var country_code = $(this).val();
	var rowCount = $(this).data("row-count");
	if (country_code) {
		var states = getStatesUsingCountryCode(country_code);

		var options = '<option value="">Select State</option>';
		$.each(states, function (index, state) {
			options += '<option value="' + state.id + '">' + state.name + "</option>";
		});
		$("#cl_state_" + rowCount).html(options);
		$("#cl_state_" + rowCount).select2();
	}
});

function generateClaimantRow(claimantRowCount) {
	var countries = getCountries();

	var countrySelect = `<select class="form-control select2 cl_address_country mb-1" name="cl_country_${claimantRowCount}" id="cl_country_${claimantRowCount}" data-row-count="${claimantRowCount}"><option value="">Select Country *</option>`;
	$.each(countries, function (index, country) {
		countrySelect +=
			'<option value="' + country.iso2 + '">' + country.name + "</option>";
	});
	countrySelect += "</select>";

	var tr = `
            <tr class="claimants-row" id="cl-tr-${claimantRowCount}">
                <td>
                    <input type="text" class="form-control claimant_name_input uppercase" id="cl_name_${claimantRowCount}" name="cl_name_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Full Name *">
                </td>
                <td>
                    <input type="text" class="form-control mb-1 uppercase" id="cl_address_one_${claimantRowCount}" name="cl_address_one_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 1 *">
                    <input type="text" class="form-control uppercase mb-1" id="cl_address_two_${claimantRowCount}" name="cl_address_two_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 2">
                    
					<div class="mb-1">
						<input type="text" class="form-control uppercase" id="cl_city_${claimantRowCount}" name="cl_city_${claimantRowCount}"  autocomplete="off" maxlength="100" value="" placeholder="City *">
					</div>
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
                    <input type="email" class="form-control uppercase" id="cl_email_${claimantRowCount}" name="cl_email_${claimantRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Email ID *">
                </td>
                <td>
                    <input type="number" class="form-control" id="cl_mobile_number_${claimantRowCount}" name="cl_mobile_number_${claimantRowCount}"  autocomplete="off" minlength="10" maxlength="10" value="" placeholder="Mobile Number *">
                </td>
                <td class="text-center">
                    <button type="button" class="btn-remove-claimant-row btn btn-danger btn-sm" id="btn_${claimantRowCount}" data-row-number="${claimantRowCount}">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
	claimantRowCountArray.push(claimantRowCount);
	$("#claimants-table tbody").append(tr);
	$(".select2").select2();
}

$(document).on("change", ".res_address_country", function () {
	var country_code = $(this).val();
	var rowCount = $(this).data("row-count");
	if (country_code) {
		var states = getStatesUsingCountryCode(country_code);

		var options = '<option value="">Select State</option>';
		$.each(states, function (index, state) {
			options += '<option value="' + state.id + '">' + state.name + "</option>";
		});
		$("#res_state_" + rowCount).html(options);
		$("#res_state_" + rowCount).select2();
	}
});

function generateRespondentRow(respondentRowCount) {
	var countries = getCountries();

	var countrySelect = `<select class="form-control select2 res_address_country mb-1" name="res_country_${respondentRowCount}" id="res_country_${respondentRowCount}" data-row-count="${respondentRowCount}"><option value="">Select Country *</option>`;
	$.each(countries, function (index, country) {
		countrySelect +=
			'<option value="' + country.iso2 + '">' + country.name + "</option>";
	});
	countrySelect += "</select>";

	var tr = `
            <tr class="claimants-row" id="res-tr-${respondentRowCount}">
                <td>
                    <input type="text" class="form-control respondant_name_input uppercase" id="res_name_${respondentRowCount}" name="res_name_${respondentRowCount}"  autocomplete="off" maxlength="255" placeholder="Full Name *">
                </td>
                <td>
                    <input type="text" class="form-control mb-1 uppercase" id="res_address_one_${respondentRowCount}" name="res_address_one_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 1 *">
                    <input type="text" class="form-control uppercase mb-1" id="res_address_two_${respondentRowCount}" name="res_address_two_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Address Line 2">

					<div class="mb-1">
						<input type="text" class="form-control uppercase" id="res_city_${respondentRowCount}" name="res_city_${respondentRowCount}"  autocomplete="off" maxlength="100" value="" placeholder="City *">
					</div>
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
                    <input type="email" class="form-control uppercase" id="res_email_${respondentRowCount}" name="res_email_${respondentRowCount}"  autocomplete="off" maxlength="255" value="" placeholder="Email ID *">
                </td>
                <td>
                    <input type="number" class="form-control" id="res_mobile_number_${respondentRowCount}" name="res_mobile_number_${respondentRowCount}"  autocomplete="off" minlength="10" maxlength="10" value="" placeholder="Mobile Number *">
                </td>
                <td class="text-center">
                    <button type="button" class="btn-remove-respondent-row btn btn-danger btn-sm" id="btn_${respondentRowCount}" data-row-number="${respondentRowCount}">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
	respondentRowCountArray.push(respondentRowCount);
	$("#respondents-table tbody").append(tr);
	$(".select2").select2();
}

// On clicking add row button
$("#add-claimant").on("click", function () {
	claimantRowCount += 1;
	generateClaimantRow(claimantRowCount);
	generateCaseTitle();
});

// On clicking add row button
$("#add-respondent").on("click", function () {
	respondentRowCount += 1;
	generateRespondentRow(respondentRowCount);
	generateCaseTitle();
});

$(document).ready(function () {
	$(".site-loader").show();

	generateClaimantRow(claimantRowCount);
	generateRespondentRow(respondentRowCount);

	$(".site-loader").hide();

	// On clicking remove row button
	$(document).on("click", ".btn-remove-claimant-row", function () {
		if (claimantRowCountArray.length > 1) {
			var rowNumber = $(this).data("row-number");
			$("#cl-tr-" + rowNumber).remove();

			claimantRowCountArray = claimantRowCountArray.filter(function (item) {
				return item !== rowNumber;
			});
			generateCaseTitle();
		} else {
			toastr.error("You cannot remove last remaining row from table.");
		}
	});

	// On clicking remove row button
	$(document).on("click", ".btn-remove-respondent-row", function () {
		if (respondentRowCountArray.length > 1) {
			var rowNumber = $(this).data("row-number");
			$("#res-tr-" + rowNumber).remove();

			respondentRowCountArray = respondentRowCountArray.filter(function (item) {
				return item !== rowNumber;
			});
			generateCaseTitle();
		} else {
			toastr.error(
				"You cannot remove last remaining row from table from respondant table."
			);
		}
	});
});

function generateCaseTitle() {
	if ($("#refferal_req_op_type").val() == "ADD_MISCELLANEOUS") {
		var caseTitle = "";

		if (claimantRowCountArray.length == 1) {
			caseTitle += $("#cl_name_" + claimantRowCountArray[0]).val() + " vs ";
		}

		if (claimantRowCountArray.length == 2) {
			caseTitle +=
				$("#cl_name_" + claimantRowCountArray[0]).val() + " & Anr. vs ";
		}

		if (claimantRowCountArray.length > 2) {
			caseTitle +=
				$("#cl_name_" + claimantRowCountArray[0]).val() + " & Ors. vs ";
		}

		if (respondentRowCountArray.length == 1) {
			caseTitle += $("#res_name_" + respondentRowCountArray[0]).val() + " ";
		}

		if (respondentRowCountArray.length == 2) {
			caseTitle +=
				$("#res_name_" + respondentRowCountArray[0]).val() + " & Anr.";
		}

		if (respondentRowCountArray.length > 2) {
			caseTitle +=
				$("#res_name_" + respondentRowCountArray[0]).val() + " & Ors.";
		}

		$("#cd_case_title").val(caseTitle.toUpperCase());
	}
}

$(document).on("keyup", ".claimant_name_input", function () {
	var claimantName = $(this).val();

	if (claimantName) {
		if ($("#refferal_req_op_type").val() == "ADD_MISCELLANEOUS") {
			generateCaseTitle();
		}
	}
});

$(document).on("keyup", ".respondant_name_input", function () {
	var respondantName = $(this).val();

	if (respondantName) {
		if ($("#refferal_req_op_type").val() == "ADD_MISCELLANEOUS") {
			generateCaseTitle();
		}
	}
});

// ====================================================
// Edit claimant or respondent
$(document).on("click", ".btn_edit_claimant_respondent", function () {
	let cl_res_code = $(this).data("code");
	let cl_res_type = $(this).data("type");

	console.log(cl_res_code);
	if (cl_res_code && cl_res_type) {
		$.ajax({
			url: base_url + "service/fetch_claimant_respondant_det_using_code_type",
			type: "POST",
			data: {
				code: cl_res_code,
				type: cl_res_type,
			},
			success: function (response) {
				// try {
				var obj = JSON.parse(response);

				if (obj.status == true) {
					let cl_res_data = obj.data;

					// Change the title of edit form
					$(".car-modal-title").html('<span class="fa fa-edit"></span> Edit');
					// $("#car_number_help").text("Ex: 1,2,3 etc.");

					// Change the submit button to edit
					$("#car_btn_submit").attr("disabled", false);
					$("#car_btn_submit")[0].innerHTML =
						"<i class='fa fa-edit'></i> Update Details";

					// Reset the form
					$("#car_form").trigger("reset");

					// ==============================================
					if (cl_res_type == "claimant") {
						// Change the op type
						$("#car_op_type").val("EDIT_CLAIMANT_DETAILS_FOR_REFFERAL_REQUEST");
					}
					if (cl_res_type == "respondant") {
						// Change the op type
						$("#car_op_type").val(
							"EDIT_RESPONDANT_DETAILS_FOR_REFFERAL_REQUEST"
						);
					}

					// =============================================
					var id = cl_res_data.id;
					var code = cl_res_data.code;
					var case_no = cl_res_data.case_no;
					var type = cl_res_data.type;
					var name = cl_res_data.name;
					var contact = cl_res_data.contact;
					var email = cl_res_data.email;

					var perm_address_1 = cl_res_data.perm_address_1;
					var perm_address_2 = cl_res_data.perm_address_2;
					var perm_city = cl_res_data.perm_city;
					var perm_country = cl_res_data.perm_country;
					var perm_pincode = cl_res_data.perm_pincode;
					var perm_state = cl_res_data.perm_state;

					var counter_claimant = cl_res_data.counter_claimant;

					$("#car_hidden_case_no").val(case_no);
					$("#hidden_car_id").val(id);
					$("#hidden_car_code").val(code);
					$("#hidden_car_type").val(type);

					$("#car_name").val(name);
					// $("#car_number").val(count_number);
					$("#car_email").val(email);
					$("#car_contact_no").val(contact);

					$("#perm_address1").val(perm_address_1);
					$("#perm_address2").val(perm_address_2);
					$("#perm_city").val(perm_city);

					$('#perm_country option[value = "' + perm_country + '"]').prop(
						"selected",
						true
					);
					getStatesUsingCountryCodeAndAppend(
						perm_country,
						"perm_state",
						perm_state
					);
					$("#perm_pincode").val(perm_pincode);

					// Open the modal after filling all the details
					$("#addCarListModal").modal({
						backdrop: "static",
						keyboard: false,
					});
				} else if (obj.status === "validationerror") {
					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					toastr.error(
						obj.msg
							? obj.msg
							: "Something went wrong, please contact support team."
					);
				}
				// } catch (e) {
				// 	swal(
				// 		"Sorry",
				// 		"Unable to process request. Please Try Again !" + e.getMessage(),
				// 		"error"
				// 	);
				// }
			},
			error: function (error) {
				toastr.error("Something went wrong.");
			},
		});
	}
});

// ====================================================
// Delete claimant or respondent
$(document).on("click", ".btn_delete_claimant_respondent", function () {
	let code = $(this).data("code");
	if (code) {
		swal(
			{
				title: "Are you sure?",
				text: "Once confirmed, the action cannot be undone!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, Confirm!",
				closeOnConfirm: false,
			},
			function () {
				$.ajax({
					url: base_url + "service/delete_claimant/DELETE_CLAIMANT",
					type: "POST",
					data: {
						code: code,
						csrf_trans_token: $("#csrf_cl_trans_token").val(),
					},
					success: function (response) {
						try {
							var obj = JSON.parse(response);

							if (obj.status == true) {
								swal(
									{
										title: "Success",
										text: obj.msg,
										type: "success",
										html: true,
									},
									function () {
										window.location.reload();
									}
								);
							} else if (obj.status === "validationerror") {
								swal({
									title: "Validation Error",
									text: obj.msg,
									type: "error",
									html: true,
								});
							} else {
								toastr.error(
									obj.msg
										? obj.msg
										: "Something went wrong, please contact support team."
								);
							}
						} catch (e) {
							swal("Sorry", "Unable to delete.Please Try Again !", "error");
						}
					},
					error: function (error) {
						toastr.error("Something went wrong.");
					},
				});
			}
		);
	}
});

// ====================================================
// Add claimant list form
$("#car_refferal_request_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#car_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(
			document.getElementById("car_refferal_request_form")
		);
		formData.set("row_count_array", JSON.stringify(rowCountArray));

		urls = base_url + "service/add_car_operation_for_refferal_request";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("car_btn_submit");
				// try {
				var obj = JSON.parse(response);
				if (obj.status == true) {
					swal(
						{
							title: "Success",
							text: obj.msg,
							type: "success",
							html: true,
						},
						function () {
							window.location.reload();
						}
					);
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
						text: obj.msg
							? obj.msg
							: "Something went wrong, please try again or contact support team.",
						type: "error",
						html: true,
					});
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				// Enable the submit button
				enable_submit_btn("car_btn_submit");
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		car_name: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		// car_number: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
		car_contact_no: {
			validators: {
				regexp: {
					regexp: /^[0-9+-]+$/,
					message: "Only numbers, -, + are allowed.",
				},
			},
		},
		car_email: {
			validators: {
				emailAddress: {
					message: "Invalid email address",
				},
			},
		},
	},
});

// ======================================================================
/**
 * For View Page
 */

/**
 * Datatable of arbitral tribunal for view page refferal and request
 */
var viewDataTable = $("#datatableArbList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: false,
	order: [],
	// lengthMenu: [
	// 	[10, 25, 50, -1],
	// 	[10, 25, 50, "All"],
	// ],
	ajax: {
		url: base_url + "service/get_all_arb_case_list/CASE_ARBITRAL_TRIBUNAL_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_at_trans_token").val(),
			case_no: $("#at_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},

		{
			data: null,
			render: function (data, type, row, meta) {
				return data.whether_on_panel == 1 ? "Yes" : "No";
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.amt_name_of_arbitrator;
			},
		},
		{ data: "arbitrator_type_desc" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.amt_email;
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.amt_contact_no;
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.amt_category_name;
			},
		},
		{ data: "appointed_by_desc" },
		{ data: "date_of_appointment" },
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

/**
 * Datatable of counsels for view page refferal and request
 */

// ================================================
// Datatable initialization for counsels
// ================================================
var viewDataTableCounselListForNR = $(
	"#dataTableViewCounselListForNR"
).DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	order: [],
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_counsels_list/CASE_COUNSELS_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_counsels_trans_token").val(),
			case_no: $("#counsels_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "name" },
		{ data: "enrollment_no" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let cl_res_type = "";
				if (data.cl_res_type == "claimant") {
					cl_res_type = "Claimant";
				}
				if (data.cl_res_type == "respondant") {
					cl_res_type = "Respondant";
				}
				return (
					data.appearing_name +
					" (" +
					cl_res_type +
					"-" +
					data.cl_res_count_number +
					")"
				);
			},
		},
		{ data: "email" },
		{ data: "phone_number" },
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});
