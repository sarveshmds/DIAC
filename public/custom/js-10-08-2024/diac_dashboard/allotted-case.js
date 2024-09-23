// Datatable initialization
var dataTable = $("#dataTableAllottedCaseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	order: [[1, "desc"]],
	ajax: {
		url: base_url + "service/get_all_allotted_case/ALLOTTED_CASE_LIST",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_ac_trans_token").val();
			d.case_no = $("#filter_allotted_case_list").val();
			d.case_status = $("#filter_case_status").val();
			d.minor_case_status = $("#filter_minor_case_status").val();
			d.arbitrator_status = $("#filter_arbitrator_status").val();
		},
	},
	columns: [
		{
			data: null,
			name: "case_no",
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
			sorting: false,
		},
		{
			data: "case_no_desc",
			name: "case_no",
		},
		{ data: "case_title", sorting: false },
		{ data: "type_of_arbitration", sorting: false },
		{ data: "registered_on", sorting: false },
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return '<span class="badge btn-info">' + data.case_status + "</span>";
			},
			sorting: false,
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return data.minor_case_status_name ? data.minor_case_status_name : "";
			},
			sorting: false,
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let arbitrator_status = "";
				if (data.arbitrator_status == 1) {
					arbitrator_status = `<span class="badge badge-success">${data.arbitrator_status_desc}</span>`;
				}
				if (data.arbitrator_status == 2) {
					arbitrator_status = `<span class="badge badge-primary">${data.arbitrator_status_desc}</span>`;
				}
				return arbitrator_status;
			},
			sorting: false,
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return data.amt_name_of_arbitrator;
			},
			sorting: false,
		},
		{
			data: null,
			sWidth: "20%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return `
				<a href="${base_url}view-case/${data.case_slug}" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>

				<div class="btn-group dropleft">
					<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Draft Letters <i class="fa fa-chevron-down"></i></button>
					<ul class="dropdown-menu" role="menu" style="right: 100%;
					left: auto;
					bottom: 0;
					top: 0px;">
						<li><a href="${base_url}response-format/editable?type=SOC_FORMAT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Statement of Claim</a></li>
						<li><a href="${base_url}response-format/editable?type=REMINDER_SOC_FORMAT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Reminder of S.O.C.</a></li>
						<li><a href="${base_url}response-format/editable?type=FINAL_REMINDER_SOC_FORMAT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Final Reminder of S.O.C.</a></li>
						<li><a href="${base_url}response-format/editable?type=CLOSED_DRAFT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Administratively Closed Draft</a></li>
						<li><a href="${base_url}response-format/editable?type=NAMES_TO_OPPOSITE_PARTY&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Names to opposite party</a></li>
						<li><a href="${base_url}response-format/editable?type=FEE_DRAFT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> Fee Draft</a></li>
						<li><a href="${base_url}response-format/editable?type=DOH_LETTER&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-book"></i> DOH Letter</a></li>
					</ul>
				</div>

			<div class="btn-group dropleft">
				<button type="button" class="btn btn-custom btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-chevron-down"></i></button>
				<ul class="dropdown-menu" role="menu" style="right: 100%;
				left: auto;
				bottom: 0;
				top: -177px;">
					<li><a href="${base_url}edit-case-details/${data.case_slug}"><i class="fa fa-edit"></i> Edit Details</a></li>
					<li><a href="${base_url}status-of-pleadings/${data.case_slug}"><i class="fa fa-pencil"></i> Status Of Pleadings</a></li>
					<li><a href="${base_url}claimant-respondant-details/${data.case_slug}"><i class="fa fa-users"></i> Claimant & Respondent</a></li>
					<li><a href="${base_url}counsels/${data.case_slug}"><i class="fa fa-retweet"></i> Counsels</a></li>
					<li><a href="${base_url}arbitral-tribunal/${data.case_slug}"><i class="fa fa-sticky-note-o"></i> Arbitral Tribunal</a></li>
					<li><a href="${base_url}termination/${data.case_slug}"><i class="fa fa-trophy"></i> Termination</a></li>
					<li><a href="${base_url}noting/${data.case_slug}"><i class="fa fa-pencil-square-o"></i> Noting</a></li>
					<li><a href="${base_url}case-fees-details/${data.case_slug}"><i class="fa fa-money"></i> Case Fee</a></li>
					
					<li><a href="${base_url}view-fees-details/${data.case_slug}" class="btn_view_fees"><i class="fa fa-money"></i> View Fees</a></li>
				</ul>
			</div>`;
			},
			sorting: false,
		},
	],
	columnDefs: [
		{
			targets: ["_all"],
		},
	],
});

// Filter for allotted case

$("#allotted_case_f_btn_submit").on("click", function () {
	dataTable.ajax.reload();
});

$("#allotted_case_f_btn_reset").on("click", function () {
	$("#filter_allotted_case_list").val("").trigger("change");
	$("#filter_case_status").val("").trigger("change");
	$("#filter_minor_case_status").val("").trigger("change");
	$("#filter_arbitrator_status").val("").trigger("change");

	dataTable.ajax.reload();
});
