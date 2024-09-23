// Datatable initialization
var dataTableGroupWise = $("#dataTableCaseAllotmentGroupWiseList").DataTable({
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
		url:
			base_url + "service/get_all_case_allotment/CASE_ALLOTMENT_LIST_GROUPWISE",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_ca_trans_token").val();
			d.case_no = $("#ca_f_case_no").val();
			d.alloted_to = $("#ca_f_allotted_to").val();
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "case_no_desc" },
		{ data: "case_title" },
		{ data: "deputy_counsel" },
		{ data: "case_manager" },
		{ data: "coordinator" },
		{ data: "alloted_on" },
	],
	columnDefs: [
		{
			targets: [0, 2, 3, 4],
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
			text: '<span class="fa fa-plus"></span> Add New Allotment',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_ca_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Datatable initialization
var dataTable;

function individual_case_allotment() {
	dataTable = $("#dataTableCaseAllotmentList").DataTable({
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
			url: base_url + "service/get_all_case_allotment/CASE_ALLOTMENT_LIST",
			type: "POST",
			data: function (d) {
				d.csrf_trans_token = $("#csrf_ca_trans_token").val();
				d.case_no = $("#ca_f_case_no").val();
				d.alloted_to = $("#ca_f_allotted_to").val();
			},
		},
		columns: [
			{
				data: null,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				},
			},
			{ data: "case_no_desc" },
			{ data: "case_title" },
			{ data: "alloted_to_name" },
			{
				data: null,
				sWidth: "15%",
				sClass: "alignCenter",
				render: function (data, type, row, meta) {
					var span = ``;
					if (data.user_role == "DEPUTY_COUNSEL") {
						span = `<span class="badge bg-green">${data.user_role_desc}</span>`;
					}
					if (data.user_role == "CASE_MANAGER") {
						span = `<span class="badge bg-blue">${data.user_role_desc}</span>`;
					}
					if (data.user_role == "COORDINATOR") {
						span = `<span class="badge bg-yellow">${data.user_role_desc}</span>`;
					}
					return span;
				},
			},
			{ data: "alloted_on" },
			{
				data: "id",
				sWidth: "15%",
				sClass: "alignCenter",
				render: function (data, type, row, meta) {
					var buttons =
						'<button class="btn btn-warning btn-sm" onclick="btnEditCAForm(event)" data-tooltip="tooltip" title="Edit"><span class="fa fa-edit"></span></button> ';

					if (role_code != "COORDINATOR") {
						buttons +=
							' <button class="btn btn-danger btn-sm" onclick="btnDeleteCAList(' +
							data +
							')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>';
					}
					return buttons;
				},
			},
		],
		columnDefs: [
			{
				targets: [0, 2, 3, 4],
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
				text: '<span class="fa fa-plus"></span> Add New Allotment',
				className: "btn btn-custom",
				init: function (api, node, config) {
					$(node).removeClass("dt-button");
				},
				action: function (e, dt, node, config) {
					add_ca_list_modal_open();
				},
			},
		],
		drawCallback: function () {
			$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
		},
	});
}

$("#nav_case_allotment_general").on("click", function () {
	dataTableGroupWise.ajax.reload();
});

$("#nav_case_allotment_individual").on("click", function () {
	// dataTable.ajax.reload();
	individual_case_allotment();
});

// Open the modal to add case allotment
function add_ca_list_modal_open() {
	$("#ca_op_type").val("ADD_CASE_ALLOTMENT");
	$(".ca-modal-title").html(
		'<span class="fa fa-plus"></span> Add Case Allottment'
	);
	$(".allotted_to_cm_col").show();
	$(".allotted_to_dc_col").show();
	$(".allotted_to_coord_col").show();

	$("#ca_allotted_to_cm").select2("val", "");
	$("#ca_allotted_to_dc").select2("val", "");

	$("#ca_allotted_to_cm").attr("multiple", "multiple");
	$("#ca_allotted_to_dc").attr("multiple", "multiple");

	$(".select2").select2();
	$("#addCAListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addCAListModal").on("hidden.bs.modal", function (e) {
	$("#ca_form").trigger("reset");
	$(".ca-modal-title").html("");
	$("#hidden_ca_id").val("");
	$("#ca_op_type").val("");
	$("#ca_allotted_to").prop("multiple", true);
	$("#ca_allotted_to option").prop("selected", false);
	$(".select2").select2();
	$("#ca_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#ca_form").data("bootstrapValidator").resetForm(true);
});

// Button edit form to open the modal with edit form
function btnEditCAForm(event) {
	$("#ca_allotted_to").prop("multiple", false);
	$(".ca-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Case Allotment'
	);
	// Change the submit button to edit
	$("#ca_btn_submit").attr("disabled", false);
	$("#ca_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#ca_form").trigger("reset");

	// Change the op type
	$("#ca_op_type").val("EDIT_CASE_ALLOTMENT");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCaseAllotmentList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var alloted_to = oTable.fnGetData(row)["alloted_to"];
	var user_role = oTable.fnGetData(row)["user_role"];

	var alloted_date = oTable.fnGetData(row)["alloted_date"];
	// Load users based on role
	// loadUsers(user_role);

	$("#ca_allotted_to_cm").removeAttr("multiple");
	$("#ca_allotted_to_dc").removeAttr("multiple");
	$("#ca_allotted_to_coord").removeAttr("multiple");

	$("#ca_allotted_to_cm").select2("val", "");
	$("#ca_allotted_to_dc").select2("val", "");
	$("#ca_allotted_to_coord").select2("val", "");

	$(".allotted_to_cm_col").hide();
	$(".allotted_to_dc_col").hide();
	$(".allotted_to_coord_col").hide();

	if (user_role == "CASE_MANAGER") {
		$(".allotted_to_cm_col").show();
		$('#ca_allotted_to_cm option[value="' + alloted_to + '"]').prop(
			"selected",
			true
		);
	}

	if (user_role == "DEPUTY_COUNSEL") {
		$(".allotted_to_dc_col").show();
		$('#ca_allotted_to_dc option[value="' + alloted_to + '"]').prop(
			"selected",
			true
		);
	}

	if (user_role == "COORDINATOR") {
		$(".allotted_to_coord_col").show();
		$('#ca_allotted_to_coord option[value="' + alloted_to + '"]').prop(
			"selected",
			true
		);
	}

	$("#hidden_ca_id").val(id);
	$('#ca_case_no option[value="' + case_no + '"]').prop("selected", true);

	$(".select2").select2();
	$("#addCAListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Add case allotment form
$("#ca_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#noting_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("ca_form"));
		console.log(formData);

		$.ajax({
			url: base_url + "service/add_case_allotment_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			complete: function () {
				// Enable the submit button
				enable_submit_btn("ca_btn_submit");
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == false) {
						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else if (obj.status === "validationerror") {
						swal({
							title: "Validation Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						//Reseting user form
						$("#noting_form").trigger("reset");
						toastr.success(obj.msg);

						// Redraw the datatable
						dataTableCauseList = $("#dataTableCaseAllotmentList").DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$("#addCAListModal").modal("hide");
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

// Delete function for case allotment
function btnDeleteCAList(id) {
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
						url:
							base_url + "service/delete_case_allotment/DELETE_CASE_ALLOTMENT",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_ca_trans_token").val(),
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

									dataTableCauseList = $(
										"#dataTableCaseAllotmentList"
									).DataTable();
									dataTableCauseList.draw();
									dataTableCauseList.clear();
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
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

// ==================================================================================
// Filter the case allotment list

function loadUsers(role) {
	$.ajax({
		url: base_url + "/service/get_rolewise_users_list",
		method: "POST",
		data: {
			role: role,
		},
		async: false,
		success: function (response) {
			try {
				var response = JSON.parse(response);
				var option = "";
				$.each(response, function (index, value) {
					option +=
						'<option value="' +
						value.user_name +
						'">' +
						value.user_display_name +
						"</option>";
				});
				$("#ca_allotted_to").html(option);
				$("#ca_allotted_to").select2();
			} catch (e) {
				sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
			}
		},
		error: function (err) {
			toastr.error("unable to save");
		},
	});
}

$("#ca_f_btn").on("click", function () {
	dataTable.ajax.reload();
});

$("#ca_reset_btn").on("click", function () {
	$("#ca_f_case_no").val("");
	$("#ca_f_allotted_to").val(null).trigger("change");
	dataTable.ajax.reload();
});

// Get the rolewise users list
$("#ar_role").on("change", function () {
	var role = $(this).val();
	if (role) {
		loadUsers(role);
	}
});

/**
 * Re-allocations of the case managers
 */

let re_allocation_msg =
	"You want to re-allcoate the case, it will re-allocate all the case with Pre-Hearing, Under Hearing and Award/Termination status.";

$(document).on("click", ".btn_re_allocations", function () {
	let type = $(this).data("type");
	let role = $(this).data("role");

	if (type && role) {
		swal(
			{
				type: "warning",
				title: "Are you sure?",
				text: re_allocation_msg,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			},
			function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: base_url + "service/re_allocate_all_case",
						type: "POST",
						data: {
							type: type,
							role: role,
							csrf_trans_token: $("#case_re_allocation_token").val(),
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
								} else if (obj.status == false) {
									swal({
										title: "Error",
										text: obj.msg,
										type: "error",
										html: true,
									});
								} else {
									swal({
										title: "Error",
										text: obj.msg
											? obj.msg
											: "Something went wrong, please try again or refresh your browser or contact support team",
										type: "error",
										html: true,
									});
								}
							} catch (e) {
								swal(
									"Sorry",
									"Something went wrong, please try again or refresh your browser or contact support team",
									"error"
								);
							}
						},
						error: function (error) {
							toastr.error(
								"Something went wrong, please try again or refresh your browser or contact support team"
							);
						},
					});
				} else {
					swal.close();
				}
			}
		);
	} else {
		toastr.error("Type and role both fields are required.");
	}
});

/**
 * FUnction to send the ajax request to transfer the case from one user to another
 */
function transfer_case(user_from, user_to, user_type) {
	$.ajax({
		url: base_url + "service/ransfer_case_from_one_user_to_other",
		type: "POST",
		data: {
			user_from,
			user_to,
			user_type,
			csrf_trans_token: $("#csrf_transfer_token").val(),
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
				} else if (obj.status == false) {
					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					swal({
						title: "Error",
						text: obj.msg
							? obj.msg
							: "Something went wrong, please try again or refresh your browser or contact support team",
						type: "error",
						html: true,
					});
				}
			} catch (e) {
				swal(
					"Sorry",
					"Something went wrong, please try again or refresh your browser or contact support team",
					"error"
				);
			}
		},
		error: function (error) {
			toastr.error(
				"Something went wrong, please try again or refresh your browser or contact support team"
			);
		},
	});
}

/**
 * On click dc trasfer case button
 */

$(document).on("click", "#btn_dc_transfer_case", function () {
	let user_from = $("#dc_user_from").val();
	let user_to = $("#dc_user_to").val();
	let user_type = "DEPUTY_COUNSEL";

	let transfer_msg =
		"You want to transfer the case, it will re-allocate all the case from one user (DC) to another (DC).";

	if (user_from && user_to) {
		swal(
			{
				type: "warning",
				title: "Are you sure?",
				text: transfer_msg,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			},
			function (isConfirm) {
				if (isConfirm) {
					transfer_case(user_from, user_to, user_type);
				} else {
					swal.close();
				}
			}
		);
	} else {
		toastr.error("User from and user to both fields are required.");
	}
});

/**
 * On click cm trasfer case button
 */

$(document).on("click", "#btn_cm_transfer_case", function () {
	let user_from = $("#cm_user_from").val();
	let user_to = $("#cm_user_to").val();
	let user_type = "CASE_MANAGER";

	let transfer_msg =
		"You want to transfer the case, it will re-allocate all the case from one user (CM) to another (CM).";

	if (user_from && user_to) {
		swal(
			{
				type: "warning",
				title: "Are you sure?",
				text: transfer_msg,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			},
			function (isConfirm) {
				if (isConfirm) {
					transfer_case(user_from, user_to, user_type);
				} else {
					swal.close();
				}
			}
		);
	} else {
		toastr.error("User from and user to both fields are required.");
	}
});

/**
 * On click coordinator trasfer case button
 */

$(document).on("click", "#btn_coordinator_transfer_case", function () {
	let user_from = $("#coordinator_user_from").val();
	let user_to = $("#coordinator_user_to").val();
	let user_type = "COORDINATOR";

	let transfer_msg =
		"You want to transfer the case, it will re-allocate all the case from one user (coordinator) to another (coordinator).";

	if (user_from && user_to) {
		swal(
			{
				type: "warning",
				title: "Are you sure?",
				text: transfer_msg,
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			},
			function (isConfirm) {
				if (isConfirm) {
					transfer_case(user_from, user_to, user_type);
				} else {
					swal.close();
				}
			}
		);
	} else {
		toastr.error("User from and user to both fields are required.");
	}
});
