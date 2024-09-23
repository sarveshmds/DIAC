// Datatable initialization
var dataTable = $("#dataTableAllottedCaseList").DataTable({
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
		url: base_url + "service/get_all_allotted_case/ALLOTTED_CASE_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_ac_trans_token").val(),
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
		{ data: "registered_on" },
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return '<span class="badge btn-info">' + data.case_status + "</span>";
			},
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<span class="badge ' +
					(data.arbitrator_status == 1 ? "badge-success" : "badge-primary") +
					'">' +
					data.arbitrator_status_desc +
					"</span>"
				);
			},
		},
		{
			data: null,
			sWidth: "10%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return `
			<div class="btn-group dropleft">
				<button type="button" class="btn btn-custom btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-chevron-down"></i></button>
				<ul class="dropdown-menu" role="menu" style="right: 100%;
				left: auto;
				bottom: 0;
				top: -177px;">
        <li><a href="${base_url}status-of-pleadings/${data.case_slug}"><i class="fa fa-pencil"></i> Status Of Pleadings</a></li>
        <li><a href="${base_url}claimant-respondant-details/${data.case_slug}"><i class="fa fa-users"></i> Claimant & Respondent</a></li>
        <li><a href="${base_url}counsels/${data.case_slug}"><i class="fa fa-retweet"></i> Counsels</a></li>
        <li><a href="${base_url}arbitral-tribunal/${data.case_slug}"><i class="fa fa-sticky-note-o"></i> Arbitral Tribunal</a></li>
        <li><a href="${base_url}termination/${data.case_slug}"><i class="fa fa-trophy"></i> Termination</a></li>
        <li><a href="${base_url}noting/${data.case_slug}"><i class="fa fa-pencil-square-o"></i> Noting</a></li>
        <li><a href="${base_url}view-fees-details/${data.case_slug}" class="btn_view_fees"><i class="fa fa-money"></i> View Fees</a></li>
				</ul>
			</div>`;
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
});
