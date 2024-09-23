// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

// check for wheather Claims or Counter Claims assessed separately
// If selected then enable the options according to there selected option
$("#fc_c_cc_assessed_sep").on("change", function () {
	var value = $(this).val();
	$(".fc_sum_despute_no_col").hide();
	$(".fc_sum_despute_yes_col").hide();

	if (value == "yes") {
		$(".fc_sum_despute_yes_col").show();
	} else if (value == "no") {
		$(".fc_sum_despute_no_col").show();
	}
});

// ==================================================================================
/*
 * Details of fees and cost start
 */

$(".btn_edit_assessment").on("click", function () {
	var fee_id = $(this).data("fee-id");
	if (fee_id) {
		$.ajax({
			url: base_url + "service/get_single_assessment_fee_details",
			type: "post",
			data: {
				fee_id: fee_id,
			},
			success: function (response) {
				try {
					var response = JSON.parse(response);
					console.log(response);

					$("#hidden_fee_cost_id").val(response.id);
					$("#fee_cost_op_type").val("EDIT_CASE_FEE_COST_DETAILS");

					$(
						'#fc_c_cc_assessed_sep option[value="' +
							response.c_cc_asses_sep +
							'"]'
					).prop("selected", true);

					if (response.c_cc_asses_sep == "yes") {
						$("#fc_sum_despute_claim").val(response.sum_in_dispute_claim);
						$("#fc_sum_despute_cc").val(response.sum_in_dispute_cc);
						$(".fc_sum_despute_yes_col").show();
					}

					if (response.c_cc_asses_sep == "no") {
						$("#fc_sum_despute").val(response.sum_in_dispute);
						$(".fc_sum_despute_no_col").show();
					}

					$("#fc_pro_assesment").val(response.asses_date);
					$("#fc_total_arb_fees").val(response.total_arb_fees);
					$("#fc_cs_arb_fees").val(response.cs_arb_fees);
					$("#fc_cs_adm_fees").val(response.cs_adminis_fees);
					$("#fc_rs_arb_fees").val(response.rs_arb_fees);
					$("#fc_rs_adm_fees").val(response.rs_adminis_fee);
					$("#hidden_fc_assesment_sheet_file_name").val(
						response.assessment_sheet_doc
					);

					$(
						'#fc_assesment_approved option[value="' +
							response.assessment_approved +
							'"]'
					).prop("selected", true);
					$("#addFeeListModal").modal("show");
				} catch (e) {
					toastr.error("Something went wrong while fetching data.");
				}
			},
			error: function (err) {
				toastr.error("Unable to save");
			},
		});
	}
});

$(".btn_delete_assessment").on("click", function () {
	var fee_id = $(this).data("fee-id");
	if (fee_id) {
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
						url: base_url + "service/delete_assessment_fee_details",
						type: "post",
						data: {
							fee_id: fee_id,
						},
						success: function (response) {
							var response = JSON.parse(response);
							window.location.reload();
						},
						error: function (err) {
							toastr.error("Unable to save");
						},
					});
				} else {
					swal.close();
				}
			}
		);
	}
});

$("#btn_add_fee").on("click", function () {
	$("#hidden_fee_cost_id").val("");
	$("#fee_cost_op_type").val("ADD_CASE_FEE_COST_DETAILS");
	$(".fc_sum_despute_yes_col").hide();
	$(".fc_sum_despute_no_col").hide();
	$("#case_fee_cost_form").trigger("reset");
	$("#addFeeListModal").modal("show");
});

$("#case_fee_cost_form").on("submit", function (e) {
	e.preventDefault();

	// Disabled the button
	$("#fee_cost_btn_submit").attr("disabled", "disabled");

	// Url
	$("#fd_btn_submit").attr("disabled", "disabled");
	var formData = new FormData(document.getElementById("case_fee_cost_form"));

	$.ajax({
		url: base_url + "service/add_case_fee_cost_operation",
		type: "post",
		data: formData,
		cache: false,
		processData: false,
		contentType: false,
		success: function (response) {
			try {
				console.log(response);
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("fee_cost_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("fee_cost_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					enable_submit_btn("fee_cost_btn_submit");

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
				}
			} catch (e) {
				sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
			}
		},
		error: function (err) {
			toastr.error("unable to save");
		},
	});
});

/*
 * Details of fees and cost end
 */
// ===============================================================================
/*
 * Fee Deposit Start +++++++++++++++++++++
 */
// Datatable initialization
var dataTable = $("#dataTableFeeDepositList").DataTable({
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
		url: base_url + "service/get_all_fee_deposit_list/CASE_FEE_DEPOSIT_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_fd_trans_token").val(),
			case_no: $("#fd_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "date_of_deposit" },
		{ data: "cl_res_name" },
		{ data: "name_of_depositor" },
		{ data: "dep_by_description" },
		{ data: "mod_description" },
		{ data: "details_of_deposit" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return toIndianCurrency(data.amount);
			},
		},
		{ data: "check_bounce" },
		{
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewFDForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditFDForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteFDList(' +
					data +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				);
			},
		},
	],
	columnDefs: [
		{
			targets: [0, 2, 3, 4, 5, 6, 7, 8],
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
			text: '<span class="fa fa-plus"></span> Add',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_fd_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add fee deposit
function add_fd_list_modal_open() {
	$(".fd-modal-title").html('<span class="fa fa-plus"></span> Add Fee Deposit');
	$("#addFDListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addFDListModal").on("hidden.bs.modal", function (e) {
	$("#fd_form").trigger("reset");
	$("#hidden_fd_id").val("");
	$(".fd-modal-title").html();
	$("#fd_op_type").val("ADD_CASE_FEE_DEPOSIT");
	$("#fd_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$(".check_bounce_col").hide();
	$("#fd_details_of_deposit").summernote("code", "");
	$("#fd_form").data("bootstrapValidator").resetForm(true);
});

// On changing the mode of deposit
$("#fd_mode_of_deposit").on("change", function () {
	var value = $(this).val();
	if (value == "CHEQUE") {
		$(".check_bounce_col").show();
	} else {
		$("#fd_check_bounce option").prop("selected", false);
		$(".check_bounce_col").hide();
	}
});

// View the fee deposit data
function viewFDForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeDepositList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var date_of_deposit = oTable.fnGetData(row)["date_of_deposit"];
	var cl_res_name = oTable.fnGetData(row)["cl_res_name"];
	var cl_res_count_number = oTable.fnGetData(row)["cl_res_count_number"];
	var name_of_depositor = oTable.fnGetData(row)["name_of_depositor"];
	var dep_by_description = oTable.fnGetData(row)["dep_by_description"];
	var mod_description = oTable.fnGetData(row)["mod_description"];
	var amount = oTable.fnGetData(row)["amount"];
	var details_of_deposit = oTable.fnGetData(row)["details_of_deposit"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Date of deposit: </th> <td>" +
		date_of_deposit +
		"</td></tr>";
	table +=
		"<tr><th>Deposited by: </th> <td>" +
		cl_res_name +
		" (" +
		cl_res_count_number +
		")" +
		"</td></tr>";
	table +=
		"<tr><th>Name of depositor: </th> <td>" + name_of_depositor + "</td></tr>";
	table +=
		"<tr><th>Deposited towards: </th> <td>" + dep_by_description + "</td></tr>";
	table +=
		"<tr><th>Mode of deposit: </th> <td>" + mod_description + "</td></tr>";
	table += "<tr><th>Amount: </th> <td>Rs. " + amount + "</td></tr>";
	table +=
		"<tr><th>Details of deposit: </th> <td>" +
		details_of_deposit +
		"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Fee Deposit Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditFDForm(event) {
	$(".fd-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Fee Deposit'
	);
	$("#addFDListModal").modal({
		backdrop: "static",
		keyboard: false,
	});

	// Change the submit button to edit
	$("#fd_btn_submit").attr("disabled", false);
	$("#fd_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#fd_form").trigger("reset");

	// Change the op type
	$("#fd_form input[name='op_type']").val("EDIT_CASE_FEE_DEPOSIT");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeDepositList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var fd_date_of_deposit = oTable.fnGetData(row)["date_of_deposit"];
	var fd_deposited_by = oTable.fnGetData(row)["deposited_by"];
	var fd_name_of_depositor = oTable.fnGetData(row)["name_of_depositor"];
	var fd_deposited_towards = oTable.fnGetData(row)["deposited_towards"];
	var fd_mode_of_deposit = oTable.fnGetData(row)["mode_of_deposit"];
	var fd_details_of_deposit = oTable.fnGetData(row)["details_of_deposit"];
	var fd_check_bounce = oTable.fnGetData(row)["check_bounce"];
	var fd_amount = oTable.fnGetData(row)["amount"];

	if (fd_mode_of_deposit == "CHEQUE") {
		$('#fd_check_bounce option[value="' + fd_check_bounce + '"]').prop(
			"selected",
			true
		);
		$(".check_bounce_col").show();
	}

	$("#hidden_fd_id").val(id);
	$("#fd_hidden_case_no").val(case_no);
	$("#fd_date_of_deposit").val(fd_date_of_deposit);

	$('#fd_deposited_by option[value="' + fd_deposited_by + '"]').prop(
		"selected",
		true
	);
	$("#fd_name_of_depositor").val(fd_name_of_depositor);
	$('#fd_deposited_towards option[value="' + fd_deposited_towards + '"]').prop(
		"selected",
		true
	);
	$("#fd_amount").val(fd_amount);
	$('#fd_mode_of_deposit option[value="' + fd_mode_of_deposit + '"]').prop(
		"selected",
		true
	);

	$("#fd_details_of_deposit").summernote("code", fd_details_of_deposit);
}

// Add case fee deposit form
$("#fd_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#fd_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("fd_form"));
		urls = base_url + "service/add_case_fd_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("fd_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("fd_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#fd_form").trigger("reset");
					toastr.success(obj.msg);

					// Redraw the datatable
					dataTableCauseList = $("#dataTableFeeDepositList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addFDListModal").modal("hide");
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		fd_name_of_depositor: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fd_deposited_by: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fd_deposited_towards: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fd_amount: {
			validators: {
				notEmpty: {
					message: "Required",
				},
				regexp: {
					regexp: /^[0-9]+$/,
					message: "Only numbers are allowed.",
				},
			},
		},
		fd_mode_of_deposit: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fd_details_of_deposit: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for case fee deposit
function btnDeleteFDList(id) {
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
							base_url + "service/delete_case_fd_list/DELETE_CASE_FEE_DEPOSIT",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_fd_trans_token").val(),
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
										"#dataTableFeeDepositList"
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

/*
 * Fee Deposit End +++++++++++++++++++++
 */
// ===========================================================================

// ===============================================================================
/*
 * Amount Details Start +++++++++++++++++++++
 */

// ===========================================================================
/*
 * Cost Deposit Start +++++++++++++++++++++
 */

// Datatable initialization
var dataTable = $("#dataTableCostDepositList").DataTable({
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
		url: base_url + "service/get_all_cost_deposit_list/CASE_COST_DEPOSIT_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_cd_trans_token").val(),
			case_no: $("#cd_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "date_of_deposit" },
		{ data: "cl_res_name" },
		{ data: "name_of_depositor" },
		{ data: "mod_description" },
		{ data: "cost_imposed" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return toIndianCurrency(data.amount);
			},
		},
		{ data: "details_of_deposit" },
		{
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewCDForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditCDForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteCDList(' +
					data +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				);
			},
		},
	],
	columnDefs: [
		{
			targets: [0, 2, 3, 4, 5, 6, 7],
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
			text: '<span class="fa fa-plus"></span> Add',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_cd_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cost deposit
function add_cd_list_modal_open() {
	$(".cd-modal-title").html(
		'<span class="fa fa-plus"></span> Add Cost Deposit'
	);
	$("#addCDListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addCDListModal").on("hidden.bs.modal", function (e) {
	$("#cd_form").trigger("reset");
	$("#hidden_cd_id").val("");
	$(".cd-modal-title").html();
	$("#cd_op_type").val("ADD_CASE_COST_DEPOSIT");
	$("#cd_details_of_deposit").summernote("code", "");
	$("#cd_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#cd_form").data("bootstrapValidator").resetForm(true);
});

// View the cost deposit data
function viewCDForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableCostDepositList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var date_of_deposit = oTable.fnGetData(row)["date_of_deposit"];
	var cl_res_name = oTable.fnGetData(row)["cl_res_name"];
	var cl_res_count_number = oTable.fnGetData(row)["cl_res_count_number"];
	var name_of_depositor = oTable.fnGetData(row)["name_of_depositor"];
	var cost_imposed_dated = oTable.fnGetData(row)["cost_imposed_dated"];
	var cost_imposed = oTable.fnGetData(row)["cost_imposed"];
	var mod_description = oTable.fnGetData(row)["mod_description"];
	var amount = oTable.fnGetData(row)["amount"];
	var details_of_deposit = oTable.fnGetData(row)["details_of_deposit"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Date of deposit: </th> <td>" +
		date_of_deposit +
		"</td></tr>";
	table +=
		"<tr><th>Deposited by: </th> <td>" +
		cl_res_name +
		" (" +
		cl_res_count_number +
		")" +
		"</td></tr>";
	table +=
		"<tr><th>Name of depositor: </th> <td>" + name_of_depositor + "</td></tr>";
	table +=
		"<tr><th>Cost imposed vide order dated: </th> <td>" +
		cost_imposed_dated +
		"</td></tr>";
	table += "<tr><th>Cost imposed: </th> <td>" + cost_imposed + "</td></tr>";
	table +=
		"<tr><th>Mode of deposit: </th> <td>" + mod_description + "</td></tr>";
	table += "<tr><th>Amount: </th> <td>Rs. " + amount + "</td></tr>";
	table +=
		"<tr><th>Details of deposit: </th> <td>" +
		details_of_deposit +
		"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Cost Deposit Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditCDForm(event) {
	$(".cd-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Cost Deposit'
	);
	$("#addCDListModal").modal({
		backdrop: "static",
		keyboard: false,
	});

	// Change the submit button to edit
	$("#cd_btn_submit").attr("disabled", false);
	$("#cd_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#cd_form").trigger("reset");

	// Change the op type
	$("#cd_form input[name='op_type']").val("EDIT_CASE_COST_DEPOSIT");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCostDepositList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var cd_date_of_deposit = oTable.fnGetData(row)["date_of_deposit"];
	var cd_deposited_by = oTable.fnGetData(row)["deposited_by"];
	var cd_name_of_depositor = oTable.fnGetData(row)["name_of_depositor"];
	var cd_cost_imposed_dated = oTable.fnGetData(row)["cost_imposed_dated"];
	var cd_cost_imposed = oTable.fnGetData(row)["cost_imposed"];
	var cd_mode_of_deposit = oTable.fnGetData(row)["mode_of_deposit"];
	var cd_details_of_deposit = oTable.fnGetData(row)["details_of_deposit"];
	var cd_amount = oTable.fnGetData(row)["amount"];

	$("#hidden_cd_id").val(id);
	$("#cd_hidden_case_no").val(case_no);
	$("#cd_date_of_deposit").val(cd_date_of_deposit);

	$('#cd_deposited_by option[value="' + cd_deposited_by + '"]').prop(
		"selected",
		true
	);
	$("#cd_name_of_depositor").val(cd_name_of_depositor);
	$("#cd_cost_imposed_dated").val(cd_cost_imposed_dated);
	$("#cd_amount").val(cd_amount);
	$("#cd_cost_imposed").val(cd_cost_imposed);
	$('#cd_mode_of_deposit option[value="' + cd_mode_of_deposit + '"]').prop(
		"selected",
		true
	);
	$("#cd_details_of_deposit").summernote("code", cd_details_of_deposit);
}

// Add case cost deposit form
$("#cd_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#cd_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("cd_form"));
		urls = base_url + "service/add_case_cd_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("cd_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("cd_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#cd_form").trigger("reset");
					toastr.success(obj.msg);

					// Enable the submit button
					enable_submit_btn("cd_btn_submit");

					// Redraw the datatable
					dataTableCauseList = $("#dataTableCostDepositList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addCDListModal").modal("hide");
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		cd_name_of_depositor: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_deposited_by: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_amount: {
			validators: {
				notEmpty: {
					message: "Required",
				},
				regexp: {
					regexp: /^[0-9]+$/,
					message: "Only numbers are allowed.",
				},
			},
		},
		cd_mode_of_deposit: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		cd_details_of_deposit: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for case cost deposit
function btnDeleteCDList(id) {
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
							base_url + "service/delete_case_cd_list/DELETE_CASE_COST_DEPOSIT",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_cd_trans_token").val(),
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
										"#dataTableCostDepositList"
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

/*
 * Cost Deposit End +++++++++++++++++++++
 */

// ===============================================================================
/*
 * Fee Refund Start +++++++++++++++++++++
 */

// Datatable initialization
var dataTable = $("#dataTableFeeRefundList").DataTable({
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
		url: base_url + "service/get_all_fee_refund_list/CASE_FEE_REFUND_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_fee_ref_trans_token").val(),
			case_no: $("#fd_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "date_of_refund" },
		{ data: "cl_res_name" },
		{ data: "name_of_party" },
		{ data: "dep_by_description" },
		{ data: "mod_refund" },
		{ data: "details_of_refund" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return toIndianCurrency(data.amount);
			},
		},
		{
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewFeeRefForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditFeeRefForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteFeeRefList(' +
					data +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				);
			},
		},
	],
	columnDefs: [
		{
			targets: [0, 2, 3, 4, 5, 6, 7, 8],
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
			text: '<span class="fa fa-plus"></span> Add',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_fee_ref_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add fee refund
function add_fee_ref_list_modal_open() {
	$(".fee-ref-modal-title").html(
		'<span class="fa fa-plus"></span> Add Fee Refund'
	);
	$("#addFeeRefListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addFeeRefListModal").on("hidden.bs.modal", function (e) {
	$("#fee_ref_form").trigger("reset");
	$("#hidden_fee_ref_id").val("");
	$(".fee-ref-modal-title").html();
	$("#fee_ref_op_type").val("ADD_CASE_FEE_REFUND");
	$("#fee_ref_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#fee_ref_form").data("bootstrapValidator").resetForm(true);
});

// View the fee refund data
function viewFeeRefForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeRefundList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var date_of_refund = oTable.fnGetData(row)["date_of_refund"];
	var cl_res_name = oTable.fnGetData(row)["cl_res_name"];
	var cl_res_count_number = oTable.fnGetData(row)["cl_res_count_number"];
	var name_of_party = oTable.fnGetData(row)["name_of_party"];
	var dep_by_description = oTable.fnGetData(row)["dep_by_description"];
	var mod_refund = oTable.fnGetData(row)["mod_refund"];
	var amount = oTable.fnGetData(row)["amount"];
	var details_of_refund = oTable.fnGetData(row)["details_of_refund"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Date of refund: </th> <td>" +
		date_of_refund +
		"</td></tr>";
	table +=
		"<tr><th>Refunded to: </th> <td>" +
		cl_res_name +
		" (" +
		cl_res_count_number +
		")" +
		"</td></tr>";
	table += "<tr><th>Name of party: </th> <td>" + name_of_party + "</td></tr>";
	table +=
		"<tr><th>Refunded towards: </th> <td>" + dep_by_description + "</td></tr>";
	table += "<tr><th>Mode of deposit: </th> <td>" + mod_refund + "</td></tr>";
	table += "<tr><th>Amount: </th> <td>Rs. " + amount + "</td></tr>";
	table +=
		"<tr><th>Details of refund: </th> <td>" + details_of_refund + "</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Fee Refund Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditFeeRefForm(event) {
	$(".fee-ref-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Fee Refund'
	);
	$("#addFeeRefListModal").modal({
		backdrop: "static",
		keyboard: false,
	});

	// Change the submit button to edit
	$("#fee_ref_btn_submit").attr("disabled", false);
	$("#fee_ref_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#fee_ref_form").trigger("reset");

	// Change the op type
	$("#fee_ref_form input[name='op_type']").val("EDIT_CASE_FEE_REFUND");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeRefundList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var fee_ref_date_of_refund = oTable.fnGetData(row)["date_of_refund"];
	var fee_ref_refunded_to = oTable.fnGetData(row)["refunded_to"];
	var fee_ref_name_of_party = oTable.fnGetData(row)["name_of_party"];
	var fee_ref_refunded_towards = oTable.fnGetData(row)["refunded_towards"];
	var fee_ref_mode_of_refund = oTable.fnGetData(row)["mode_of_refund"];
	var fee_ref_details_of_refund = oTable.fnGetData(row)["details_of_refund"];
	var fee_ref_amount = oTable.fnGetData(row)["amount"];

	$("#hidden_fee_ref_id").val(id);
	$("#fee_ref_hidden_case_no").val(case_no);
	$("#fee_ref_date_of_refund").val(fee_ref_date_of_refund);

	$('#fee_ref_refunded_to option[value="' + fee_ref_refunded_to + '"]').prop(
		"selected",
		true
	);
	$("#fee_ref_name_of_party").val(fee_ref_name_of_party);
	$(
		'#fee_ref_refunded_towards option[value="' + fee_ref_refunded_towards + '"]'
	).prop("selected", true);
	$("#fee_ref_amount").val(fee_ref_amount);
	$(
		'#fee_ref_mode_of_refund option[value="' + fee_ref_mode_of_refund + '"]'
	).prop("selected", true);
	$("#fee_ref_details_of_refund").val(fee_ref_details_of_refund);
}

// Add case fee refund form
$("#fee_ref_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#fd_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("fee_ref_form"));
		urls = base_url + "service/add_case_fee_ref_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("fee_ref_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("fee_ref_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#fd_form").trigger("reset");
					toastr.success(obj.msg);

					// Enable the submit button
					enable_submit_btn("fee_ref_btn_submit");

					// Redraw the datatable
					dataTableCauseList = $("#dataTableFeeRefundList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addFeeRefListModal").modal("hide");
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		fee_ref_name_of_party: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fee_ref_refunded_to: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fee_ref_amount: {
			validators: {
				notEmpty: {
					message: "Required",
				},
				regexp: {
					regexp: /^[0-9]+$/,
					message: "Only numbers are allowed.",
				},
			},
		},
		fee_ref_mode_of_refund: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fee_ref_details_of_refund: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for case fee refund
function btnDeleteFeeRefList(id) {
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
							base_url +
							"service/delete_case_fee_ref_list/DELETE_CASE_FEE_REFUND",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_fee_ref_trans_token").val(),
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

									dataTableCauseList = $("#dataTableFeeRefundList").DataTable();
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

/*
 * Fee Refund End +++++++++++++++++++++
 */
// ===========================================================================

// =======================================================================================
/*
 * Fee Released
 */

// Datatable initialization
var dataTable = $("#dataTableFeeReleasedList").DataTable({
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
		url: base_url + "service/get_all_fee_released_list/CASE_FEE_RELEASED_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_fr_trans_token").val(),
			case_no: $("#fr_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "nature_of_award_desc" },
		{ data: "date_of_fee_released" },
		{ data: "released_to" },
		{ data: "mop_description" },
		{ data: "details_of_fee_released" },
		{ data: "date_of_fee_released_note" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return toIndianCurrency(data.amount);
			},
		},
		{
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewFRForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditFRForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteFRList(' +
					data +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				);
			},
		},
	],
	columnDefs: [
		{
			targets: [0, 2, 3, 4, 5, 6],
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
			text: '<span class="fa fa-plus"></span> Add',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_fr_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_fr_list_modal_open() {
	$(".fr-modal-title").html(
		'<span class="fa fa-plus"></span> Add Fee Released'
	);

	$("#addFRListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addFRListModal").on("hidden.bs.modal", function (e) {
	$("#fr_form").trigger("reset");
	$("#hidden_fr_id").val("");
	$("#fr_op_type").val("ADD_CASE_FEE_RELEASED");
	$("#fr_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#fr_form").data("bootstrapValidator").resetForm(true);
});

// View the fee released data
function viewFRForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeReleasedList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var nature_of_award_desc = oTable.fnGetData(row)["nature_of_award_desc"];
	var date_of_fee_released = oTable.fnGetData(row)["date_of_fee_released"];
	var released_to = oTable.fnGetData(row)["released_to"];
	var mop_description = oTable.fnGetData(row)["mop_description"];
	var amount = oTable.fnGetData(row)["amount"];
	var details_of_fee_released =
		oTable.fnGetData(row)["details_of_fee_released"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Nature of award: </th> <td>" +
		nature_of_award_desc +
		"</td></tr>";
	table +=
		"<tr><th width='30%'>Date of fee released: </th> <td>" +
		date_of_fee_released +
		"</td></tr>";
	table += "<tr><th>Released to: </th> <td>" + released_to + "</td></tr>";
	table +=
		"<tr><th>Mode of payment: </th> <td>" + mop_description + "</td></tr>";
	table += "<tr><th>Amount: </th> <td>Rs. " + amount + "</td></tr>";
	table +=
		"<tr><th>Details of payment: </th> <td>" +
		details_of_fee_released +
		"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Fee Refund Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditFRForm(event) {
	$(".fr-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Fee Released'
	);
	$("#addFRListModal").modal({
		backdrop: "static",
		keyboard: false,
	});

	// Change the submit button to edit
	$("#fr_btn_submit").attr("disabled", false);
	$("#fr_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#fr_form").trigger("reset");

	// Change the op type
	$("#fr_form input[name='op_type']").val("EDIT_CASE_FEE_RELEASED");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableFeeReleasedList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var fr_nature_of_award = oTable.fnGetData(row)["nature_of_award"];
	var fr_dofr = oTable.fnGetData(row)["date_of_fee_released"];
	var fr_dofr_note = oTable.fnGetData(row)["date_of_fee_released_note"];
	var fr_released_to = oTable.fnGetData(row)["released_to"];
	var fr_mode = oTable.fnGetData(row)["mode_of_payment"];
	var fr_details = oTable.fnGetData(row)["details_of_fee_released"];
	var fr_amount = oTable.fnGetData(row)["amount"];

	$("#hidden_fr_id").val(id);
	$("#fr_hidden_case_no").val(case_no);
	$("#fr_dofr").val(fr_dofr);
	$("#fr_dofr_note").val(fr_dofr_note);

	$("#fr_released_to").val(fr_released_to);

	$('#fr_nature_of_award option[value = "' + fr_nature_of_award + '"]').prop(
		"selected",
		true
	);
	$('#fr_mode option[value = "' + fr_mode + '"]').prop("selected", true);

	$("#fr_details").summernote("code", fr_details);
	$("#fr_amount").val(fr_amount);
}

// Add case fee released form
$("#fr_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#at_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("fr_form"));
		urls = base_url + "service/add_case_fr_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("fr_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("fr_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#fr_form").trigger("reset");
					toastr.success(obj.msg);

					// Redraw the datatable
					dataTableCauseList = $("#dataTableFeeReleasedList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addFRListModal").modal("hide");
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		fr_nature_of_award: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fr_released_to: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fr_mode: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		fr_amount: {
			validators: {
				notEmpty: {
					message: "Required",
				},
				regexp: {
					regexp: /^[0-9]+$/,
					message: "Only numbers are allowed.",
				},
			},
		},
		fr_details: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for case fee released
function btnDeleteFRList(id) {
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
							base_url + "service/delete_case_fr_list/DELETE_CASE_FEE_RELEASED",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_fr_trans_token").val(),
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
										"#dataTableFeeReleasedList"
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
