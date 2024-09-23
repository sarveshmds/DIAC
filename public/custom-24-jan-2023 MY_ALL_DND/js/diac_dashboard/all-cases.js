// Datatable initialization
var dataTable = $("#dataTableCaseRegistered").DataTable({
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
		url: base_url + "service/get_all_case_list/ALL_CASE_LIST",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_crl_trans_token").val();
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
		{ data: "case_no" },
		{ data: "case_title" },
		{ data: "reffered_on" },
		{ data: "reffered_by_desc" },
		{ data: "recieved_on" },
		{ data: "registered_on" },
		{ data: "case_status_dec" },
		{
			data: null,
			sWidth: "14%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var fileViewBtn = "";
				if (data.case_file) {
					var files = JSON.parse(data.case_file);
					if (files.length > 0) {
						fileViewBtn +=
							'<div class="btn-group dropleft">\
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"><span class="fa fa-file"></span></button>\
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
						fileViewBtn += "</ul>\
						</div>";
					}
				}
				return (
					'<a href="' +
					base_url +
					"view-fees-details/" +
					data.cdt_slug +
					'"><button class="btn btn-success btn-sm" data-tooltip="tooltip" title="View Fees"><span class="fa fa-money"></span></button></a> <a href="' +
					base_url +
					"view-case/" +
					data.cdt_slug +
					'"><button class="btn btn-warning btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button></a> ' +
					fileViewBtn +
					' <a href="' +
					base_url +
					"case-all-files/" +
					data.cdt_slug +
					'"><button class="btn btn-info btn-sm" data-tooltip="tooltip" title="Case All Files"><span class="fa fa-folder"></span></button></a>'
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
			text: '<span class="fa fa-file-pdf-o"></span> PDF',
			className: "btn btn-danger",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				window.open(base_url + "pdf/all-cases-list", "_BLANK");
			},
		},
		{
			text: '<span class="fa fa-file-excel-o"></span> Excel',
			className: "btn btn-success",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				window.open(base_url + "excel/all-cases-list", "_BLANK");
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// ==================================================================================
// Filter the case list

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
