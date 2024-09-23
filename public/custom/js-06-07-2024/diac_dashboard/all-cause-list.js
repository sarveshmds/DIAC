// Datatable initialization
var dataTable = $("#dataTableCauseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
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
		{ data: "date" },
		{
			data: null,
			render: function (data, type, row, meta) {
				var time_to = data.time_to ? " - " + data.time_to : "";
				return data.time_from + time_to;
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.room_name + " " + data.room_no;
			},
		},
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
		{ data: null },
	],
	columnDefs: [
		{
			targets: ["_all"],
			orderable: false,
			sorting: false,
		},
		{
			targets: [10],
			visible: false,
		},
	],
});

// ==================================================================================
// Filter the cause list

$("#f_cl_btn_submit").on("click", function () {
	dataTable.ajax.reload();
});

$("#f_cl_btn_reset").on("click", function () {
	$("#f_cl_case_no").val("").trigger("change");
	$("#f_cl_date").val("");
	$("#f_cl_room_no option").prop("selected", false);
	dataTable.ajax.reload();
});
