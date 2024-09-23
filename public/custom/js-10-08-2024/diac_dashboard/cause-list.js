// Datatable initialization
var dataTableTodaysCauseList = $("#dataTableTodaysCauseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: false,
	order: [],
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_cause_list/USER_TODAYS_CAUSE_LIST",
		type: "POST",
		data: function (d) {
			d.case_no = $("#f_cl_case_no").val();
			d.date = $("#f_cl_date").val();
			d.room_no = $("#f_cl_room_no").val();
			d.time_from = $("#f_cl_time_from").val();
			d.time_to = $("#f_cl_time_to").val();
			d.csrf_trans_token = $("#csrf_trans_token").val();
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "case_no" },
		{ data: "title_of_case" },
		{ data: "amt_name_of_arbitrator" },
		{ data: "purpose_category_name" },
		{ data: "date", sWidth: "12%" },
		{
			data: null,
			render: function (data, type, row, meta) {
				var time_to = data.time_to ? " - " + data.time_to : "";
				return data.time_from + time_to;
			},
		},
		{ data: "mode_of_hearing" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.room_no ? data.room_no : "";
			},
		},
		{ data: "hearing_meeting_link" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.active_status == 2
					? '<span class="badge badge-danger">' +
							data.active_status_desc +
							"</span>"
					: '<span class="badge badge-success">' +
							data.active_status_desc +
							"</span>";
			},
		},
		{ data: "remarks" },
		// {
		// 	data: "id",
		// 	sWidth: "10%",
		// 	sClass: "alignCenter",
		// 	render: function (data, type, row, meta) {
		// 		// <button class="btn btn-primary btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Case"><span class="fa fa-times"></span></button> <button class="btn btn-info btn-sm" onclick="btnDuplicateForm(event)" data-tooltip="tooltip" title="Duplicate cause list"><span class="fa fa-copy"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditForm(event)" data-tooltip="tooltip" title="Edit cause list"><span class="fa fa-edit"></span></button>

		// 		return '<button class="btn btn-danger btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Hearing"><span class="fa fa-times"></span></button>';
		// 	},
		// },
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	buttons: [
		{
			text: '<span class="fa fa-plus"></span> Add New Hearing',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				// add_cause_list_modal_open();
				window.location.href = base_url + "cause-list/add";
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
					base_url + "pdf/cause-list?type=" + btoa("today"),
					"_BLANK"
				);
			},
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Datatable initialization
var dataTableYoursCauseList = $("#dataTableYoursCauseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: false,
	order: [],
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_cause_list/USER_ALL_CAUSE_LIST",
		type: "POST",
		data: function (d) {
			d.case_no = $("#f_user_cl_case_no").val();
			d.date = $("#f_user_cl_date").val();
			d.csrf_trans_token = $("#csrf_trans_token").val();
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "case_no" },
		{ data: "title_of_case" },
		{ data: "amt_name_of_arbitrator" },
		{ data: "purpose_category_name" },
		{ data: "date", sWidth: "12%" },
		{
			data: null,
			render: function (data, type, row, meta) {
				var time_to = data.time_to ? " - " + data.time_to : "";
				return data.time_from + time_to;
			},
		},
		{ data: "mode_of_hearing" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.room_no ? data.room_no : "";
			},
		},
		{ data: "hearing_meeting_link" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.active_status == 2
					? '<span class="badge badge-danger">' +
							data.active_status_desc +
							"</span>"
					: '<span class="badge badge-success">' +
							data.active_status_desc +
							"</span>";
			},
		},
		{ data: "remarks" },
		{
			data: null,
			sWidth: "10%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				// <button class="btn btn-primary btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Case"><span class="fa fa-times"></span></button> <button class="btn btn-info btn-sm" onclick="btnDuplicateForm(event)" data-tooltip="tooltip" title="Duplicate cause list"><span class="fa fa-copy"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditForm(event)" data-tooltip="tooltip" title="Edit cause list"><span class="fa fa-edit"></span></button>

				$button = "";
				if (data.active_status != 2) {
					$button +=
						'<button class="btn btn-danger btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Hearing"><span class="fa fa-times"></span></button>';
				}

				return $button;
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
	buttons: [
		// {
		// 	text: '<span class="fa fa-plus"></span> Add New Hearing',
		// 	className: "btn btn-custom",
		// 	init: function (api, node, config) {
		// 		$(node).removeClass("dt-button");
		// 	},
		// 	action: function (e, dt, node, config) {
		// 		// add_cause_list_modal_open();
		// 		window.location.href = base_url + "cause-list/add";
		// 	},
		// },
		// {
		// 	text: '<span class="fa fa-file-pdf-o"></span> PDF',
		// 	className: "btn btn-danger",
		// 	init: function (api, node, config) {
		// 		$(node).removeClass("dt-button");
		// 	},
		// 	action: function (e, dt, node, config) {
		// 		window.open(base_url + "pdf/cause-list", "_BLANK");
		// 	},
		// },
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Datatable initialization
var dataTable = $("#dataTableCauseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: false,
	order: [],
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_cause_list/ALL_CAUSE_LIST",
		type: "POST",
		data: function (d) {
			d.case_no = $("#f_cl_case_no").val();
			d.date = $("#f_cl_date").val();
			d.room_no = $("#f_cl_room_no").val();
			d.time_from = $("#f_cl_time_from").val();
			d.time_to = $("#f_cl_time_to").val();
			d.csrf_trans_token = $("#csrf_trans_token").val();
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "case_no" },
		{ data: "title_of_case" },
		{ data: "amt_name_of_arbitrator" },
		{ data: "purpose_category_name" },
		{ data: "date", sWidth: "12%" },
		{
			data: null,
			render: function (data, type, row, meta) {
				var time_to = data.time_to ? " - " + data.time_to : "";
				return data.time_from + time_to;
			},
		},
		{ data: "mode_of_hearing" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.room_no ? data.room_no : "";
			},
		},
		{ data: "hearing_meeting_link" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.active_status == 2
					? '<span class="badge badge-danger">' +
							data.active_status_desc +
							"</span>"
					: '<span class="badge badge-success">' +
							data.active_status_desc +
							"</span>";
			},
		},
		{ data: "remarks" },
		// {
		// 	data: "id",
		// 	sWidth: "10%",
		// 	sClass: "alignCenter",
		// 	render: function (data, type, row, meta) {
		// 		// <button class="btn btn-primary btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Case"><span class="fa fa-times"></span></button> <button class="btn btn-info btn-sm" onclick="btnDuplicateForm(event)" data-tooltip="tooltip" title="Duplicate cause list"><span class="fa fa-copy"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditForm(event)" data-tooltip="tooltip" title="Edit cause list"><span class="fa fa-edit"></span></button>

		// 		return (
		// 			'<button class="btn btn-danger btn-sm" onclick="btnCancelCauseList(event)" data-tooltip="tooltip" title="Cancel Hearing"><span class="fa fa-times"></span></button>'
		// 		);
		// 	},
		// },
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
	],
	buttons: [
		// {
		// 	text: '<span class="fa fa-plus"></span> Add New Hearing',
		// 	className: "btn btn-custom",
		// 	init: function (api, node, config) {
		// 		$(node).removeClass("dt-button");
		// 	},
		// 	action: function (e, dt, node, config) {
		// 		// add_cause_list_modal_open();
		// 		window.location.href = base_url + "cause-list/add";
		// 	},
		// },
		{
			text: '<span class="fa fa-file-pdf-o"></span> PDF',
			className: "btn btn-danger",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				let f_cl_case_no = $("#f_cl_case_no").val();
				let f_cl_date = $("#f_cl_date").val();
				let f_cl_room_no = $("#f_cl_room_no").val();
				window.open(
					`${base_url}pdf/cause-list?case_no=${btoa(f_cl_case_no)}&date=${btoa(
						f_cl_date
					)}&room_no=${btoa(f_cl_room_no)}`,
					"_BLANK"
				);
			},
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

$(document).ready(function () {
	$(".js-example-basic-single").select2();

	$(".timepicker").timepicker({
		placement: "top",
		align: "left",
		showInputs: true,
		minuteStep: 30, // Set the minute step to 30 minutes
		showMeridian: true, // Display time in 24-hour format
		defaultTime: "10:00", // Do not set a default time
	});

	$("#date_of_hearing").datepicker({
		format: "dd-mm-yyyy",
		todayHighlight: false,
		clearBtn: true,
		autoclose: true,
		startDate: "0d", // Set the minimum selectable date to today
		defaultViewDate: null, // Prevent any date from being selected by default
	});

	// Click on check availability
	$("#btn_check_availability").on("click", function () {
		let date_of_hearing = $("#date_of_hearing").val();
		let time_from = $("#time_from").val();
		let time_to = $("#time_to").val();
		let mode_of_hearing = $("#mode_of_hearing").val();

		$(".hearing_further_details_col").hide();
		$(".hearing_meeting_link_col").hide();

		if (date_of_hearing && time_from && time_to) {
			// Check and fetch the available rooms
			$.ajax({
				url: base_url + "cause-list/check-availability",
				type: "POST",
				data: {
					date: date_of_hearing,
					mode_of_hearing: mode_of_hearing,
					time_from: time_from,
					time_to: time_to,
				},
				success: function (response) {
					let obj = JSON.parse(response);
					if (obj.status == true) {
						if (obj.msg) {
							toastr.success(obj.msg);
						}

						if (mode_of_hearing == "PHYSICAL" || mode_of_hearing == "HYBRID") {
							let available_rooms = obj.available_rooms;
							$(".room_no_col").show();

							if (available_rooms.length > 0) {
								let ar_options = `<option value="">Select</option>`;
								available_rooms.forEach((ar) => {
									ar_options += `<option value="${ar.id}}">${ar.room_name} - ${ar.room_no}</option>`;
								});

								$("#room_no").html(ar_options);
								$(".hearing_further_details_col").show();
							} else {
								swal({
									title: "Rooms Not Available",
									text: obj.msg,
									type: "error",
									html: true,
								});
							}

							if (mode_of_hearing == "HYBRID") {
								$(".hearing_meeting_link_col").show();
							}
						} else {
							$(".room_no_col").hide();
							$(".hearing_further_details_col").show();
							$(".hearing_meeting_link_col").show();
						}
					} else if (obj.status == false) {
						swal({
							title: "Slot Not Available",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						swal({
							title: "Error",
							text: "Something went wrong, please try again or refresh your browser",
							type: "error",
							html: true,
						});
					}
				},
			});
		} else {
			toastr.error(
				"Please select date, time from and time to field to check availability"
			);
		}
	});

	$(document).on("change", "#time_from", function () {
		$(".hearing_further_details_col").hide();
	});
	$(document).on("change", "#time_to", function () {
		$(".hearing_further_details_col").hide();
	});

	$(document).on("change", "#date_of_hearing", function () {
		let date_of_hearing = $(this).val();
		$(".hearing_further_details_col").hide();

		if (date_of_hearing) {
			// Fetch the hearings for the selected date
			$.ajax({
				url: base_url + "cause-list/fetch-hearings",
				type: "POST",
				data: {
					date: date_of_hearing,
				},
				success: function (response) {
					let result = JSON.parse(response);
					let hearings = result.hearings;
					if (hearings.length > 0) {
						let hearing_div = ``;
						hearings.forEach((hearing) => {
							hearing_div += `
							<div class="box box-body form-group p-10">
								<h5 class="mb-0">
									<strong><span class="text-warning">${
										hearing.mode_of_hearing == "PHYSICAL" ||
										hearing.mode_of_hearing == "HYBRID"
											? "Conference Room " + hearing.room_no
											: "Meeting is Online"
									}</span></strong> - <span class="badge ${
								hearing.active_status == 2 ? "bg-red" : "bg-green"
							}">${hearing.time_from} - ${hearing.time_to}</span>
								</h5>
								<h5 class="">${hearing.case_no_desc} (${hearing.case_title})</h5>
								<p class="mb-0">
									<strong>Mode of hearing:</strong> ${hearing.mode_of_hearing}
								</p>
								<p class="mb-0">
									<strong>Arb.:</strong> ${hearing.amt_name_of_arbitrator}
								</p>
								${
									hearing.active_status == 2
										? '<p><strong class="text-danger">Cancelled: </strong>' +
										  hearing.remarks +
										  "</p>"
										: ""
								}
							</div>
							`;
						});

						$("#hearings_list_wrapper").html(hearing_div);
					} else {
						$("#hearings_list_wrapper").html(
							"<i class='fa fa-info-circle'></i> No hearings are scheduled for " +
								date_of_hearing
						);
					}
				},
			});
		} else {
			$("#hearings_list_wrapper").html(
				"Please select date to check hearings for that date."
			);
		}
	});
});

// Open the modal to add cause list
function add_cause_list_modal_open() {
	$(".cause-list-modal-title").html(
		'<i class="fa fa-plus"></i> Add Cause List'
	);
	$("#cause_list_op_type").val("ADD_CAUSE_LIST_FORM");

	$("#addCauseListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addCauseListModal").on("hidden.bs.modal", function (e) {
	$(".cause-list-modal-title").html("");
	$("#cause_list_op_type").val("");
	$("#cause_list_hidden_id").val("");

	$("#form_add_cause_list").trigger("reset");
	$("#cause_list_btn_submit").html(
		"<i class='fa fa-paper-plane'></i> Save Details"
	);
	$("#form_add_cause_list").data("bootstrapValidator").resetForm(true);
});

// Button edit form to open the modal with edit form
function btnEditForm(event) {
	// Change the submit button to edit
	$("#cause_list_btn_submit").attr("disabled", false);
	$("#cause_list_btn_submit").html("<i class='fa fa-edit'></i> Update Details");

	// Reset the form
	$("#form_add_cause_list").trigger("reset");

	// Change the op type
	$("#cause_list_op_type").val("EDIT_CAUSE_LIST_FORM");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCauseList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var title_of_case = oTable.fnGetData(row)["title_of_case"];
	// var arbitrator_name = oTable.fnGetData(row)["arbitrator_name"];
	var purpose_cat_id = oTable.fnGetData(row)["purpose_cat_id"];
	var date = oTable.fnGetData(row)["date"];
	var time_from = oTable.fnGetData(row)["time_from"];
	var time_to = oTable.fnGetData(row)["time_to"];
	var room_no = oTable.fnGetData(row)["room_no"];
	var room_no_id = oTable.fnGetData(row)["room_no_id"];
	var remarks = oTable.fnGetData(row)["remarks"];

	$("#cause_list_hidden_id").val(id);
	$("#case_no").val(case_no);
	$("#title_of_case").val(title_of_case);
	// $("#arbitrator_name").val(arbitrator_name);
	$('#purpose option[value="' + purpose_cat_id + '"]').prop("selected", true);
	$("#date").val(date);
	$("#time_from").val(time_from);
	$("#time_to").val(time_to);

	$('#room_no option[value = "' + room_no_id + '"]').prop("selected", true);

	$("#remarks").val(remarks);

	$(".cause-list-modal-title").html(
		'<i class="fa fa-edit"></i> Edit Cause List'
	);
	$("#addCauseListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Button duplicate form to open the modal with duplicate form form
function btnDuplicateForm(event) {
	// Change the submit button to edit
	$("#cause_list_btn_submit").attr("disabled", false);
	$("#cause_list_btn_submit").html(
		"<i class='fa fa-paper-plane'></i> Save Details"
	);

	// Reset the form
	$("#form_add_cause_list").trigger("reset");

	// Change the op type
	$("#form_add_cause_list input[name='op_type']").val("ADD_CAUSE_LIST_FORM");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCauseList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var title_of_case = oTable.fnGetData(row)["title_of_case"];
	// var arbitrator_name = oTable.fnGetData(row)["arbitrator_name"];
	var purpose_cat_id = oTable.fnGetData(row)["purpose_cat_id"];
	var date = oTable.fnGetData(row)["date"];
	var time_from = oTable.fnGetData(row)["time_from"];
	var time_to = oTable.fnGetData(row)["time_to"];
	var room_no = oTable.fnGetData(row)["room_no"];
	var room_no_id = oTable.fnGetData(row)["room_no_id"];
	var remarks = oTable.fnGetData(row)["remarks"];

	$("#case_no").val(case_no);
	$("#title_of_case").val(title_of_case);
	// $("#arbitrator_name").val(arbitrator_name);

	$('#purpose option[value="' + purpose_cat_id + '"]').prop("selected", true);

	$("#date").val(date);
	$("#time_from").val(time_from);
	$("#time_to").val(time_to);

	$('#room_no option[value = "' + room_no_id + '"]').prop("selected", true);

	$("#remarks").val(remarks);

	$(".cause-list-modal-title").html(
		'<i class="fa fa-plus"></i> Add Cause List'
	);
	$("#addCauseListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Add cause list form
$("#form_add_cause_list").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#cause_list_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("form_add_cause_list"));
		urls = base_url + "service/cause_list_operation";
		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("cause_list_btn_submit");
				// try {
				var obj = JSON.parse(response);
				console.log(obj);
				if (obj.status == true) {
					//Reseting user form
					// $("#form_add_cause_list").trigger("reset");
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

					// // Redraw the datatable
					// dataTableCauseList = $("#dataTableCauseList").DataTable();
					// dataTableCauseList.draw();
					// dataTableCauseList.clear();

					// $("#cancelCauseListModal").modal("hide");
				} else if (obj.status == false) {
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
					swal({
						title: "Error",
						text: "Something is went wrong, please try again.",
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
				enable_submit_btn("cause_list_btn_submit");
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		case_list: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		// title_of_case: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
		// arbitrator_name: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
		purpose: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		room_no: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		time_from: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		time_to: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// Delete function for cause list
function btnDeleteCauseList(id) {
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
						url: base_url + "service/delete_cause_list",
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

									dataTableCauseList = $("#dataTableCauseList").DataTable();
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
function enable_submit_btn(id) {
	$("#" + id).attr("disabled", false);
}

// Button duplicate form to open the modal with duplicate form form
function btnCancelCauseList(event) {
	// Change the submit button to edit
	$("#cause_list_btn_submit").attr("disabled", false);
	$("#cause_list_btn_submit").html(
		"<i class='fa fa-paper-plane'></i> Save Details"
	);

	// Reset the form
	$("#form_cancel_cause_list").trigger("reset");

	// Change the op type
	// $("#form_add_cause_list input[name='op_type']").val("ADD_CAUSE_LIST_FORM");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableYoursCauseList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var slug = oTable.fnGetData(row)["slug"];

	$("#cancel_cause_list_hidden_id").val(id);
	$("#cancel_case_number").html(case_no);
	$("#case_slug").val(slug);

	$("#cancelCauseListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Cancel cause list form
$("#form_cancel_cause_list").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#cause_list_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(
			document.getElementById("form_cancel_cause_list")
		);
		urls = base_url + "service/cancel_cause_list_operation";
		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			complete: function () {
				// Enable the submit button
				enable_submit_btn("cause_list_cancel_btn_submit");
			},
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == true) {
					//Reseting user form
					// $("#form_add_cause_list").trigger("reset");
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

					// // Redraw the datatable
					// dataTableCauseList = $("#dataTableCauseList").DataTable();
					// dataTableCauseList.draw();
					// dataTableCauseList.clear();

					// $("#cancelCauseListModal").modal("hide");
				} else if (obj.status == false) {
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
					swal({
						title: "Error",
						text: "Something went wrong, please try again.",
						type: "error",
						html: true,
					});
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
		cancel_remarks: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

// ==================================================================================
// Filter the cause list

$("#f_cl_btn_submit").on("click", function () {
	dataTable.ajax.reload();
});

$("#f_cl_btn_reset").on("click", function () {
	$("#f_cl_case_no").val(null).trigger("reset");
	$("#f_cl_date").val("");
	$("#f_cl_room_no option").prop("selected", false);
	dataTable.ajax.reload();
});

// Filter the user cause list

$("#f_user_cl_btn_submit").on("click", function () {
	dataTableYoursCauseList.ajax.reload();
});

$("#f_user_cl_btn_reset").on("click", function () {
	$("#f_user_cl_case_no").val(null).trigger("reset");
	$("#f_user_cl_date").val("");
	dataTableYoursCauseList.ajax.reload();
});

$(document).on("change", "#case_list", function () {
	let case_type = $(this).val();
	// $("#form_add_cause_list").data("bootstrapValidator").resetForm(true);

	if (case_type) {
		$.ajax({
			url: base_url + "service/get_case_list_details",
			type: "POST",
			data: { case_type: case_type },
			success: function (response) {
				var response = JSON.parse(response);
				if (response.status == true) {
					$("#case_no")
						.val(getDIACCaseNumber(response.data))
						.attr("readonly", true);
					$("#title_of_case")
						.val(response.data.case_title)
						.attr("readonly", true);

					let arbitrators = response.arbitrators;

					if (arbitrators.length > 0) {
						let options = ``;
						arbitrators.forEach((arbitrator, i) => {
							options += `<span>${arbitrator.name_of_arbitrator} - (${
								arbitrator.category_name
							}) ${i + 1 < arbitrators.length ? "," : ""}</span>`;
						});
						$("#arbitrator_name").html(options);
					} else {
						$("#arbitrator_name").html("Not available");
					}
				} else if (response.status == false) {
					toastr.error(response.msg);
				} else {
					toastr.error(
						"Something went wrong, please refresh your browser or try again."
					);
				}
			},
		});
	} else {
		$("#case_no").val("").attr("readonly", false);
		$("#title_of_case").val("").attr("readonly", false);
	}
});
