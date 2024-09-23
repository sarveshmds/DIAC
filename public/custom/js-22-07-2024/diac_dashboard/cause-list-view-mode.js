// Datatable initialization
var dataTableTodaysCauseList = $("#dataTableTodaysCauseList").DataTable({
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
				return data.room_name ? data.room_name + " " + data.room_no : "";
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
			data: "id",
			sWidth: "10%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
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
	buttons: [
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
	responsive: true,
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
				return data.room_name ? data.room_name + " " + data.room_no : "";
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
			data: "id",
			sWidth: "10%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
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
	buttons: [],
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
	responsive: true,
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
				return data.room_name ? data.room_name + " " + data.room_no : "";
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
// ==================================================================================
// Filter the cause list

$("#f_cl_btn_submit").on("click", function () {
	dataTable.ajax.reload();
});

$("#f_cl_btn_reset").on("click", function () {
	$("#f_cl_case_no").val(null).trigger("change");
	$("#f_cl_date").val("");
	$("#f_cl_room_no option").prop("selected", false);
	dataTable.ajax.reload();
});

// Filter the user cause list

$("#f_user_cl_btn_submit").on("click", function () {
	dataTableYoursCauseList.ajax.reload();
});

$("#f_user_cl_btn_reset").on("click", function () {
	$("#f_user_cl_case_no").val(null).trigger("change");
	$("#f_user_cl_date").val("");
	dataTableYoursCauseList.ajax.reload();
});
