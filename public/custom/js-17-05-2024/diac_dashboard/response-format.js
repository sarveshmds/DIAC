$(".response-summernote-text-editor").summernote({ height: 800 });

$("#btnSaveAndDownload").on("click", function () {
	$("#hidden_option").val("SAVE_AND_DOWNLOAD");
	submitForm();
});

$("#btnSaveAndSend").on("click", function () {
	$("#hidden_option").val("SAVE_AND_SEND");
	submitForm();
});

function submitForm() {
	$.ajax({
		url: base_url + "service/operation_response_format",
		method: "POST",
		data: new FormData(document.getElementById("response_format_form")),
		contentType: false,
		processData: false,
		beforeSend: function () {
			$("#btnSaveAndDownload").attr("disabled", true);
			$("#btnSaveAndSend").attr("disabled", true);
		},
		success: function (response) {
			try {
				var obj = JSON.parse(response);
				if (obj.status == true) {
					if (obj.redirect_link) {
						window.open(obj.redirect_link, "_BLANK");
					}

					swal(
						{
							title: "Success",
							text: obj.msg,
							type: "success",
							html: true,
						},
						function () {
							window.location.href = base_url + "response-format";
						}
					);
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
						text: "Something went wrong",
						type: "error",
						html: true,
					});
				}
			} catch (e) {
				swal({
					title: "Oops! Error Occured",
					text: "Something went wrong. Please try again or contact support.",
					type: "error",
					html: true,
				});
			}
		},
		error: function (err) {
			toastr.error(
				"Server responds with error. Please try again or contact support team."
			);
		},
		complete: function () {
			$("#btnSaveAndDownload").attr("disabled", false);
			$("#btnSaveAndSend").attr("disabled", false);
		},
	});
}

// ============================================================
// Datatable initialization
var datatableResponseFormat = $("#datatableResponseFormat").DataTable({
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
		url: base_url + "service/get_all_response_format",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
			d.case_no = $("#response_case_no").val();
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
		{ data: "subject" },
		{ data: "email_to" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let status = "<span class='badge bg-default'>Pending</span>";
				if (data.response_status == 1) {
					status = "<span class='badge bg-green'>Email Sent</span>";
				}
				if (data.response_status == 2) {
					status = "<span class='badge bg-blue'>Drafted</span>";
				}

				return status;
			},
		},
		{ data: "created_at_date" },
		{
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return '<button type="button" class="btn btn-danger btn-sm" onclick="formatResponse(event)"><i class="fa fa-file-pdf-o"></i></button>';
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
});

$("#response_filter_btn").on("click", function () {
	datatableResponseFormat.ajax.reload();
});

function formatResponse(event) {
	var oTable = $("#datatableResponseFormat").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "I")
		row = event.target.parentNode.parentNode.parentNode;

	var caseNo = oTable.fnGetData(row)["case_no"];
	var responseId = oTable.fnGetData(row)["id"];

	window.open(
		base_url +
			"pdf/response-format?case_no=" +
			caseNo +
			"&response_id=" +
			responseId,
		"_BLANK"
	);
}
