// Url
var urls =
	base_url + "service/get_all_registered_list/ALL_REGISTERED_CASE_LIST";

// Edit page url
var edit_url = base_url + "add-new-case/";

function generate_buttons(data) {
	var fileViewBtn = "";
	if (data.case_file) {
		var files = JSON.parse(data.case_file);
		if (files.length > 0) {
			fileViewBtn +=
				'<div class="btn-group dropleft">\
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="View Files"><span class="fa fa-file"></span></button>\
						<ul class="dropdown-menu" role="menu">';
			files.forEach((element, index) => {
				fileViewBtn +=
					'<li><a href="' +
					base_url +
					"public/upload/case_files/" +
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

	// <li><a href="${base_url}/add-new-case/${data.cdt_slug}"><span class="fa fa-edit"></span> Edit Details</a></li>

	var actionBtn = `<div class="btn-group dropleft">
					<button type="button" class="btn btn-warning dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false" data-tooltip="tooltip" title="Action"><i class="fa fa-bars"></i></button>
					<ul class="dropdown-menu" role="menu">
						
						<li><a href="${base_url}claimant-respondant-details/${data.cdt_slug}"><i class="fa fa-users"></i> Claimant & Respondent</a></li>
						<li><a href="${base_url}arbitral-tribunal/${data.cdt_slug}"><i class="fa fa-sticky-note-o"></i> Arbitral Tribunal</a></li>
					</ul>
				</div>`;
	return (
		'<a href="' +
		base_url +
		"all-registered-case/view?case_no=" +
		data.cdt_slug +
		'" class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a> ' +
		fileViewBtn +
		'  <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Delete Case" onclick="btnDeleteCase(' +
		data.cdt_id +
		')"><span class="fa fa-trash"></span></button> ' +
		actionBtn
	);
}

// Datatable initialization
// General Cases ============================================================
var dataTableGeneralCases = $("#dataTableCaseform").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url: urls,
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
			d.case_no = $("#ac_f_case_no").val();
			d.case_title = $("#ac_f_case_title").val();
			d.arbitration_petition = $("#ac_f_arb_pet").val();
			d.toa = $("#ac_f_toa").val();
			d.registered_on_from = $("#ac_f_registered_on_from").val();
			d.registered_on_to = $("#ac_f_registered_on_to").val();
			d.date_of_award_from = $("#ac_f_date_of_award_from").val();
			d.date_of_award_to = $("#ac_f_date_of_award_to").val();
			d.reffered_by = $("#ac_f_reffered_by").val();
			d.reffered_by_judge = $("#ac_f_reffered_by_judge").val();
			d.referred_on = $("#ac_f_referred_on").val();
			d.name_of_arbitrator = $("#ac_f_name_of_arbitrator").val();
			d.name_of_counsel = $("#ac_f_name_of_counsel").val();
			d.case_status = $("#ac_f_status").val();
			d.sorting_by = $("#sorting_by").val();
			d.sort_to = $("#sort_to").val();
			d.case_type = "GENERAL";
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
				return data.case_no_desc;
			},
		},
		{ data: "case_title" },
		{ data: "reffered_by_desc" },
		{ data: "type_of_arbitration" },
		{ data: "di_type_of_arbitration" },
		{ data: "registered_on" },
		{ data: "case_status_dec" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return generate_buttons(data);
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
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Emergency Cases =====================================================

var dataTableEmergency = $("#dataTableEmergencyCases").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url: urls,
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
			d.case_no = $("#ac_f_case_no").val();
			d.case_title = $("#ac_f_case_title").val();
			d.arbitration_petition = $("#ac_f_arb_pet").val();
			d.toa = $("#ac_f_toa").val();
			d.registered_on_from = $("#ac_f_registered_on_from").val();
			d.registered_on_to = $("#ac_f_registered_on_to").val();
			d.date_of_award_from = $("#ac_f_date_of_award_from").val();
			d.date_of_award_to = $("#ac_f_date_of_award_to").val();
			d.reffered_by = $("#ac_f_reffered_by").val();
			d.reffered_by_judge = $("#ac_f_reffered_by_judge").val();
			d.referred_on = $("#ac_f_referred_on").val();
			d.name_of_arbitrator = $("#ac_f_name_of_arbitrator").val();
			d.name_of_counsel = $("#ac_f_name_of_counsel").val();
			d.case_status = $("#ac_f_status").val();
			d.sorting_by = $("#sorting_by").val();
			d.sort_to = $("#sort_to").val();
			d.case_type = "EMERGENCY";
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
		{ data: "case_no_desc" },
		{ data: "case_title" },
		{ data: "reffered_by_desc" },
		{ data: "type_of_arbitration" },
		{ data: "di_type_of_arbitration" },
		{ data: "registered_on" },
		{ data: "case_status_dec" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return generate_buttons(data);
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
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Fasttrack Cases =========================================================
// var dataTableFasttrack = $("#dataTableFastTrackCases").DataTable({
// 	processing: true,
// 	serverSide: true,
// 	autoWidth: false,
// 	responsive: true,
// 	searching: false,
// 	order: [],
// 	ajax: {
// 		url: urls,
// 		type: "POST",
// 		data: function (d) {
// 			d.csrf_trans_token = $("#csrf_trans_token").val();
// 			d.case_no = $("#ac_f_case_no").val();
// 			d.case_title = $("#ac_f_case_title").val();
// 			d.arbitration_petition = $("#ac_f_arb_pet").val();
// 			d.toa = $("#ac_f_toa").val();
// 			d.registered_on_from = $("#ac_f_registered_on_from").val();
// 			d.registered_on_to = $("#ac_f_registered_on_to").val();
// 			d.date_of_award_from = $("#ac_f_date_of_award_from").val();
// 			d.date_of_award_to = $("#ac_f_date_of_award_to").val();
// 			d.reffered_by = $("#ac_f_reffered_by").val();
// 			d.reffered_by_judge = $("#ac_f_reffered_by_judge").val();
// 			d.referred_on = $("#ac_f_referred_on").val();
// 			d.name_of_arbitrator = $("#ac_f_name_of_arbitrator").val();
// 			d.name_of_counsel = $("#ac_f_name_of_counsel").val();
// 			d.case_status = $("#ac_f_status").val();
// 			d.sorting_by = $("#sorting_by").val();
// 			d.sort_to = $("#sort_to").val();
// 			d.case_type = "FASTTRACK";
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
// 		{ data: "diary_number" },
// 		{ data: "case_no" },
// 		{ data: "case_title" },
// 		{ data: "reffered_by_desc" },
// 		{ data: "type_of_arbitration" },
// 		{ data: "di_type_of_arbitration" },
// 		{ data: "registered_on" },
// 		{ data: "case_status_dec" },
// 		{
// 			data: null,
// 			sWidth: "15%",
// 			sClass: "alignCenter",
// 			render: function (data, type, row, meta) {
// 				return generate_buttons(data);
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
// 		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'>>" +
// 		"<'row'<'col-sm-12'tr>>" +
// 		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
// 	drawCallback: function () {
// 		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
// 	},
// });

function viewRegisteredData(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableCaseform").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var case_slug = oTable.fnGetData(row)["cdt_slug"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var case_title = oTable.fnGetData(row)["case_title"];
	var reffered_on = oTable.fnGetData(row)["reffered_on"];
	var reffered_by_desc = oTable.fnGetData(row)["reffered_by_desc"];

	var reffered_by_judge = oTable.fnGetData(row)["reffered_by_judge"];
	var arbitration_petition = oTable.fnGetData(row)["arbitration_petition"];
	var name_of_court = oTable.fnGetData(row)["name_of_court"];

	var recieved_on = oTable.fnGetData(row)["recieved_on"];
	var registered_on = oTable.fnGetData(row)["registered_on"];
	var case_status_dec = oTable.fnGetData(row)["case_status_dec"];
	var arbitrator_status_desc = oTable.fnGetData(row)["arbitrator_status_desc"];
	var type_of_arbitration_desc =
		oTable.fnGetData(row)["type_of_arbitration_desc"];
	var di_type_of_arbitration_desc =
		oTable.fnGetData(row)["di_type_of_arbitration_desc"];

	var remarks = oTable.fnGetData(row)["remarks"];
	var arbNames = "";
	// get all arbitrators list
	$.ajax({
		url: base_url + "service/get_case_arbitrators_list",
		type: "POST",
		data: {
			case_slug: case_slug,
			csrf_trans_token: $("#csrf_trans_token").val(),
		},
		async: false,
		success: function (response) {
			try {
				var obj = JSON.parse(response);

				if (obj.status == true) {
					var data = obj.data;
					var count = 1;
					$.each(data, function (index, value) {
						arbNames += value.name_of_arbitrator;
						if (count < data.length) {
							arbNames += ", ";
						}
						count++;
					});
				} else {
					toastr.error("Something went wrong. Please try again.");
				}
			} catch (e) {
				swal("Sorry", "Unable to Save.Please Try Again !", "error");
			}
		},
		error: function (error) {
			toastr.error("Something went wrong.");
		},
	});

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Case No.: </th> <td>" +
		checkIfNull(case_no) +
		"</td></tr>";
	table +=
		"<tr><th>Case Title: </th> <td>" + checkIfNull(case_title) + "</td></tr>";
	table +=
		"<tr><th>Date of reference order: </th> <td>" +
		checkIfNull(reffered_on) +
		"</td></tr>";
	table +=
		"<tr><th>Mode of reference: </th> <td>" +
		checkIfNull(reffered_by_desc) +
		"</td></tr>";

	table +=
		"<tr><th>Referred By (Judge/Justice): </th> <td>" +
		checkIfNull(reffered_by_judge) +
		"</td></tr>";
	table +=
		"<tr><th>Name of Court/Department: </th> <td>" +
		checkIfNull(name_of_court) +
		"</td></tr>";
	table +=
		"<tr><th>Reference No.: </th> <td>" +
		checkIfNull(arbitration_petition) +
		"</td></tr>";

	table +=
		"<tr><th>Ref. Received On: </th> <td>" +
		checkIfNull(recieved_on) +
		"</td></tr>";
	table +=
		"<tr><th>Date of registration: </th> <td>" +
		checkIfNull(registered_on) +
		"</td></tr>";
	table +=
		"<tr><th>Type Of Arbitration: </th> <td>" +
		checkIfNull(type_of_arbitration_desc) +
		"</td></tr>";
	table +=
		"<tr><th>Which Type of arbitration: </th> <td>" +
		checkIfNull(di_type_of_arbitration_desc) +
		"</td></tr>";
	table +=
		"<tr><th>Arbitrator Status: </th> <td>" +
		checkIfNull(arbitrator_status_desc) +
		"</td></tr>";
	table +=
		"<tr><th>Name of arbitrators: </th> <td>" +
		checkIfNull(arbNames) +
		"</td></tr>";
	table +=
		"<tr><th>Case Status: </th> <td>" +
		checkIfNull(case_status_dec) +
		"</td></tr>";
	table += "<tr><th>Remarks: </th> <td>" + checkIfNull(remarks) + "</td></tr>";
	table += "</table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Registered Case: ' + case_no
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Delete function for cause list
function btnDeleteCase(id) {
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
						url: base_url + "service/delete_registered_case",
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

									dataTableCaseform = $("#dataTableCaseform").DataTable();
									dataTableCaseform.draw();
									dataTableCaseform.clear();
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
	dataTableGeneralCases.ajax.reload();
	dataTableEmergency.ajax.reload();
	dataTableFasttrack.ajax.reload();
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

	dataTableGeneralCases.ajax.reload();
	dataTableEmergency.ajax.reload();
	dataTableFasttrack.ajax.reload();
});

// Case Sorting ==================================================================
$("#btn_rg_case_sorting").on("click", function () {
	if ($("#sorting_by").val() && $("#sort_to").val()) {
		dataTableGeneralCases.ajax.reload();
	} else {
		toastr.error("Both fields are required to sort the data");
	}
});

$("#btn_rg_case_reset_sorting").on("click", function () {
	$("#sorting_by option").prop("selected", false);
	$("#sort_to option").prop("selected", false);
	dataTableGeneralCases.ajax.reload();
});

// ================================================================================
// On click nav tab link button
$(".rc_nav_link").on("click", function () {
	var case_type = $(this).data("case-type");
	console.log(case_type);
	if (case_type) {
		if (case_type == "GENERAL") {
			dataTableGeneralCases.ajax.reload();
		}
		if (case_type == "EMERGENCY") {
			dataTableEmergency.ajax.reload();
		}
		if (case_type == "FASTTRACK") {
			dataTableFasttrack.ajax.reload();
		}
	}
});
