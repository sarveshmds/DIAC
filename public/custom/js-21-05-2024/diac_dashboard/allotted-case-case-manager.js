// Datatable initialization
var dataTableAllottedCaseList = $("#dataTableAllottedCaseList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	searching: false,
	responsive: true,
	ordering: false,
	order: [],
	lengthMenu: [
		[25, 50, 100, -1],
		[25, 50, 100, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_allotted_case/ALLOTTED_CASE_LIST",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_ac_trans_token").val();
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
		},
	},
	columns: [
		{
			data: null,
			sWidth: "8%",
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "case_no_desc" },
		{ data: "case_title" },
		{ data: "type_of_arbitration" },
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
				var badgeClass =
					data.arbitrator_status == 1 ? "badge-success" : "badge-primary";
				return `<span class="badge ${badgeClass}">${data.arbitrator_status_desc}</span>`;
			},
		},
		{
			data: null,
			sWidth: "20%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				// <li><a href="${base_url}response-format/editable?type=CLAIMING_DEFICIENT_FEE_FROM_PARTY&case_no=${data.case_slug}" ><i class="fa fa-money"></i> Claiming deficient fee from the parties.</a></li>
				// <li><a href="${base_url}response-format/editable?type=INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR&case_no=${data.case_slug}" ><i class="fa fa-pencil"></i> Inviting claim and seeking names of Arbitrator</a></li>
				// 		<li><a href="${base_url}response-format/editable?type=INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED&case_no=${data.case_slug}" ><i class="fa fa-users"></i> Inviting claim when Arbitrator already appointed by Court</a></li>
				// 		<li><a href="${base_url}response-format/editable?type=SEEKING_CONSENT_FROM_ARBITRATOR&case_no=${data.case_slug}" ><i class="fa fa-users"></i> Seeking consent from Ld. Arbitrator</a></li>
				return `

				<a href="${base_url}view-case/${data.case_slug}" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></a>
				
				<div class="btn-group dropleft">
					<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Draft Letters <i class="fa fa-chevron-down"></i></button>
					<ul class="dropdown-menu" role="menu" style="right: 100%;
					left: auto;
					bottom: 0;
					top: 0px;">
						<li><a href="${base_url}response-format/editable?type=SOC_FORMAT&case_no=${data.case_slug}" target="_BLANK"><i class="fa fa-money"></i> Statement of Claim.</a></li>
					</ul>
				</div>
				
			<div class="btn-group dropleft">
				<button type="button" class="btn btn-custom btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-chevron-down"></i></button>
				<ul class="dropdown-menu" role="menu" style="right: 100%;
				left: auto;
				bottom: 0;
				top: -210px;">
					<li><a href="${base_url}view-fees-details/${data.case_slug}" class="btn_view_fees"><i class="fa fa-money"></i> View Fees</a></li>
					<li><a href="${base_url}status-of-pleadings/${data.case_slug}"><i class="fa fa-pencil"></i> Status Of Pleadings</a></li>
					<li><a href="${base_url}claimant-respondant-details/${data.case_slug}"><i class="fa fa-users"></i> Claimant & Respondent</a></li>
					<li><a href="${base_url}counsels/${data.case_slug}"><i class="fa fa-retweet"></i> Counsels</a></li>
					<li><a href="${base_url}arbitral-tribunal/${data.case_slug}"><i class="fa fa-sticky-note-o"></i> Arbitral Tribunal</a></li>
					<li><a href="${base_url}termination/${data.case_slug}"><i class="fa fa-trophy"></i> Termination</a></li>
					<li><a href="${base_url}noting/${data.case_slug}"><i class="fa fa-pencil-square-o"></i> Noting</a></li>
				</ul>
			</div>`;
			},
		},
	],
	columnDefs: [
		{
			targets: [0, 1, 2, 3, 4, 5, 6],
			orderable: false,
			sorting: false,
		},
	],
});

// $(document).on('click', '.btn_view_fees', function(){
// 	var caseNo = $(this).data('case-no');
// 	var caseNoDesc = $(this).data('case-no-desc');
// 	var caseTitle = $(this).data('case-title');

// 	$.ajax({
// 		url: base_url+'service/get_case_fee_deposit_details',
// 		type: 'POST',
// 		data: {
// 			case_no: caseNo,
// 			csrf_fees_status_trans_token: $('#csrf_fees_status_trans_token').val()
// 		},
// 		success: function(response){
// 			var response = JSON.parse(response);
// 			if(response.status == true){
// 				var data = response.data;

// 				$('#fs_total_cs_arb_fees').text((data.caseFeesDetails)?data.caseFeesDetails.cs_arb_fees:'');
// 				$('#fs_total_cs_admin_fees').text((data.caseFeesDetails)?data.caseFeesDetails.cs_adminis_fees:'');
// 				$('#fs_total_rs_arb_fees').text((data.caseFeesDetails)?data.caseFeesDetails.rs_arb_fees:'');
// 				$('#fs_total_rs_admin_fees').text((data.caseFeesDetails)?data.caseFeesDetails.rs_adminis_fee:'');

// 				$('#fs_deposited_cs_arb_fees').text(data.shareWiseDepositedFees.tot_amt_ARB_CS);
// 				$('#fs_deposited_cs_admin_fees').text(data.shareWiseDepositedFees.tot_amt_ADM_CS);
// 				$('#fs_deposited_rs_arb_fees').text(data.shareWiseDepositedFees.tot_amt_ARB_RS);
// 				$('#fs_deposited_rs_admin_fees').text(data.shareWiseDepositedFees.tot_amt_ADM_RS);

// 				$('#fs_case_no').text(caseNoDesc);
// 				$('#fs_case_title').text(caseTitle);
// 				$('#fs_total_arb_fees').text((data.caseFeesDetails)?data.caseFeesDetails.total_arb_fees:'');

// 				$('#feesStatusModal').modal('show');
// 			}
// 			else if(response.status == false){
// 				swal({
// 					title: 'Error',
// 					icon: 'error',
// 					text: response.msg,
// 					html: true
// 				});
// 			}
// 			else{
// 				toastr.error('Server is not responding. Please try again.')
// 			}
// 		},
// 		error: function(error){
// 			toastr.error('Error while making request. Please try again.')
// 		}
// 	})
// })

// ==================================================================================
// Filter the case list

$("#ac_f_btn").on("click", function () {
	dataTableAllottedCaseList.ajax.reload();
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
	dataTableAllottedCaseList.ajax.reload();
});
