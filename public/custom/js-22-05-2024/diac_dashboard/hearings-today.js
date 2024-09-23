// Datatable initialization
var dataTable = $("#dataTableHTList").DataTable({
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
		url: base_url + "service/get_hearings_today_list/HEARINGS_TODAY_LIST",
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
		{ data: "remarks" },
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
});
