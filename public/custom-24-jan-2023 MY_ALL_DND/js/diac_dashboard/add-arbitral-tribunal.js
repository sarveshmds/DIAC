// check for the terminated
// If yes then enable the terminated other options
$("input[type=radio][name=at_terminated]").on("change", function () {
	var at = $(this).val();
	if (at == "yes") {
		$(".at_termination_col").show();
		$(".at_term_disabled").attr("disabled", false);
	} else {
		$(".at_termination_col").hide();
		$(".at_term_disabled").attr("disabled", true);
	}
});

// Datatable initialization
var dataTable = $("#dataTableArbTriList").DataTable({
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
		url: base_url + "service/get_all_case_at_list/CASE_ARBITRAL_TRIBUNAL_LIST",
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
		{ data: "name_of_arbitrator" },
		{ data: "arbitrator_type_desc" },
		{ data: "email" },
		{ data: "phone" },
		{ data: "whether_on_panel_desc" },
		{ data: "category_name" },
		{ data: "appointed_by_desc" },
		{ data: "date_of_appointment" },
		{ data: "date_of_declaration" },
		{ data: "arb_terminated_desc" },
		{
			data: null,
			sWidth: "20%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var fileViewBtn = "";
				if (data.termination_files) {
					var files = JSON.parse(data.termination_files);
					if (files.length > 0) {
						fileViewBtn +=
							'<div class="btn-group dropleft">\
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="View Files"><span class="fa fa-file"></span></button>\
						<ul class="dropdown-menu" role="menu">';
						files.forEach((element, index) => {
							fileViewBtn +=
								'<li><a href="' +
								base_url +
								"public/upload/arbitral_tribunal/" +
								element +
								'" target="_BLANK" >File ' +
								(index + 1) +
								"</a></li>";
						});
						fileViewBtn +=
							"</ul>\
						</div>";
					}
				}

				return (
					'<button class="btn btn-primary btn-sm" onclick="uploadConsentForm(event)" data-tooltip="tooltip" title="Upload consent form"><span class="fa fa-upload"></span></button> <button class="btn btn-success btn-sm" onclick="viewATForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> ' +
					fileViewBtn +
					'  <button class="btn btn-warning btn-sm" onclick="btnEditATForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteATForm(' +
					data.id +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
				);
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
			text: '<span class="fa fa-plus"></span> Add New Arbitrator',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_arb_tri_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add arbitral tribunal
function add_arb_tri_list_modal_open() {
	$(".at-modal-title").html('<span class="fa fa-plus"></span> Add Arbitrator');
	$("#addArbTriListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addArbTriListModal").on("hidden.bs.modal", function (e) {
	$("#at_form").trigger("reset");
	$("#at_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#at_op_type").val("ADD_CASE_ARBITRAL_TRIBUNAL");
	$("#hidden_at_id").val("");
	$("#at_arb_type option").prop("selected", false);
	$("#at_form").data("bootstrapValidator").resetForm(true);
});

// View the arbitral tribunal data
function viewATForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableArbTriList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var name_of_arbitrator = oTable.fnGetData(row)["name_of_arbitrator"];
	var email = oTable.fnGetData(row)["email"];
	var phone = oTable.fnGetData(row)["phone"];
	var whether_on_panel_desc = oTable.fnGetData(row)["whether_on_panel_desc"];
	var category_name = oTable.fnGetData(row)["category_name"];
	var appointed_by_desc = oTable.fnGetData(row)["appointed_by_desc"];
	var date_of_appointment = oTable.fnGetData(row)["date_of_appointment"];
	var date_of_declaration = oTable.fnGetData(row)["date_of_declaration"];
	var date_of_termination = oTable.fnGetData(row)["date_of_termination"];
	var arb_terminated_desc = oTable.fnGetData(row)["arb_terminated_desc"];
	var reason_of_termination = oTable.fnGetData(row)["reason_of_termination"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Name: </th> <td>" + name_of_arbitrator + "</td></tr>";
	table += "<tr><th width='30%'>Email: </th> <td>" + email + "</td></tr>";
	table +=
		"<tr><th width='30%'>Phone Number: </th> <td>" + phone + "</td></tr>";
	table +=
		"<tr><th>Wheather On Panel: </th> <td>" +
		whether_on_panel_desc +
		"</td></tr>";
	table += "<tr><th>Category: </th> <td>" + category_name + "</td></tr>";
	table +=
		"<tr><th>Appointed By: </th> <td>" + appointed_by_desc + "</td></tr>";
	table +=
		"<tr><th>Date Of Appointment: </th> <td>" +
		date_of_appointment +
		"</td></tr>";
	table +=
		"<tr><th>Date Of Consent: </th> <td>" + date_of_declaration + "</td></tr>";
	table +=
		"<tr><th>Terminated: </th> <td>" + arb_terminated_desc + "</td></tr>";
	table +=
		"<tr><th>Date Of Termination: </th> <td>" +
		checkIfNull(date_of_termination) +
		"</td></tr>";
	table +=
		"<tr><th>Reason For Termination: </th> <td>" +
		checkIfNull(reason_of_termination) +
		"</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Arbitrator Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditATForm(event) {
	$(".at-modal-title").html('<span class="fa fa-edit"></span> Edit Arbitrator');
	$("#addArbTriListModal").modal({
		backdrop: "static",
		keyboard: false,
	});

	// Change the submit button to edit
	$("#at_btn_submit").attr("disabled", false);
	$("#at_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#at_form").trigger("reset");

	// Change the op type
	$("#at_form input[name='op_type']").val("EDIT_CASE_ARBITRAL_TRIBUNAL");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableArbTriList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var name_of_arbitrator = oTable.fnGetData(row)["name_of_arbitrator"];
	var email = oTable.fnGetData(row)["email"];
	var phone = oTable.fnGetData(row)["phone"];
	var arbitrator_type = oTable.fnGetData(row)["arbitrator_type"];
	var whether_on_panel = oTable.fnGetData(row)["whether_on_panel"];
	var at_cat_id = oTable.fnGetData(row)["at_cat_id"];
	var appointed_by = oTable.fnGetData(row)["appointed_by"];
	var date_of_appointment = oTable.fnGetData(row)["date_of_appointment"];
	var date_of_declaration = oTable.fnGetData(row)["date_of_declaration"];
	var arb_terminated = oTable.fnGetData(row)["arb_terminated"];
	var at_termination_by = oTable.fnGetData(row)["at_termination_by"];
	var date_of_termination = oTable.fnGetData(row)["date_of_termination"];
	var reason_of_termination = oTable.fnGetData(row)["reason_of_termination"];

	$("#hidden_at_id").val(id);
	$("#at_arb_name").val(name_of_arbitrator);
	$("#at_doa").val(date_of_appointment);
	$("#at_dod").val(date_of_declaration);
	$("#at_arb_email").val(email);
	$("#at_arb_contact").val(phone);

	$('#at_arb_type option[value = "' + arbitrator_type + '"]').prop(
		"selected",
		true
	);
	$('#at_whe_on_panel option[value = "' + whether_on_panel + '"]').prop(
		"selected",
		true
	);
	$('#at_category option[value = "' + at_cat_id + '"]').prop("selected", true);
	$('#at_appointed_by option[value = "' + appointed_by + '"]').prop(
		"selected",
		true
	);

	// Check for the terminated to be checked or not
	if (arb_terminated == "yes") {
		$(".at_terminated[value='yes']").prop("checked", true);
		$(".at_termination_col").show();

		// If yes then enable the other fields of termination
		$(".at_term_disabled").attr("disabled", false);
	} else {
		$(".at_terminated[value='no']").prop("checked", true);
		$(".at_termination_col").hide();
		$(".at_term_disabled").attr("disabled", true);
	}

	$('#at_termination_by option[value = "' + at_termination_by + '"]').prop(
		"selected",
		true
	);
	$("#at_dot").val(date_of_termination);
	$("#at_rot").val(reason_of_termination);
}

// Add case arbitral tribunal list form
$("#at_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#at_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("at_form"));
		urls = base_url + "service/add_case_operation";

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
					enable_submit_btn("at_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("at_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#at_form").trigger("reset");
					toastr.success(obj.msg);

					// Redraw the datatable
					dataTableCauseList = $("#dataTableArbTriList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addArbTriListModal").modal("hide");
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
		at_arb_name: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for arbitral tribunal
function btnDeleteATForm(id) {
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
						url: base_url + "service/delete_case_at_list",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_at_trans_token").val(),
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

									dataTableCauseList = $("#dataTableArbTriList").DataTable();
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

// Function to download the PDF
function btnFormPdf(event) {
	var oTable = $("#dataTableCauseList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;
	var id = oTable.fnGetData(row)["id"];
	window.open(base_url + "op-print/print_complaint_form_pdf/" + id);
}

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

// ===================================================
// Upload Consent form
function uploadConsentForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableArbTriList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var at_code = oTable.fnGetData(row)["at_code"];
	console.log(at_code);
}
