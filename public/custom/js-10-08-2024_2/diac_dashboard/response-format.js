$(document).ready(function () {
	// Initialize an array to store CKEditor instances
	var editors = [];

	// Check if the element exists
	if ($(".response_format_text_editor").length) {
		// Loop through each element with the class 'response_format_text_editor'
		$(".response_format_text_editor").each(function (index) {
			// Create CKEditor instance for the current element
			ClassicEditor.create(this, {
				fontSize: {
					options: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
				},
				htmlSupport: {
					allow: [
						{
							name: /.*/,
							attributes: true,
							classes: true,
							styles: true,
						},
					],
				},
			})
				.then((editor) => {
					console.log(editor);
					editors.push(editor); // Store the editor instance

					// Set the height dynamically or perform other operations
					editor.editing.view.change((writer) => {
						writer.setStyle(
							"height",
							"550px",
							editor.editing.view.document.getRoot()
						);
					});
				})
				.catch((error) => {
					console.error(error);
				});
		});
	}

	$("#response_format_form").on("submit", function (e) {
		e.preventDefault();

		// Update the textarea with the CKEditor content
		editors.forEach((editor) => {
			editor.updateSourceElement();
		});

		$.ajax({
			url: base_url + "service/operation_response_format",
			method: "POST",
			data: new FormData(document.getElementById("response_format_form")),
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function () {
				$("#btnSaveDraftLetter").attr("disabled", true);
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == true) {
						swal(
							{
								title: "Success",
								text: obj.msg,
								type: "success",
								html: true,
							},
							function () {
								window.location.href = obj.redirect_link;
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
				$("#btnSaveDraftLetter").attr("disabled", false);
			},
		});
	});
});

// CKEDITOR END
// $(".response-summernote-text-editor").summernote({ height: 800 });

// $("#btnSaveAndDownload").on("click", function () {
// 	$("#hidden_option").val("SAVE_AND_DOWNLOAD");
// 	// submitForm();
// });

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
		{ data: "letter_type" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return data.user_display_name + " (" + data.job_title + ")";
			},
		},
		{ data: "created_at_date" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					data.updated_user_display_name + " (" + data.updated_job_title + ")"
				);
			},
		},
		{ data: "updated_at_date" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return `<a href="${base_url}response-format/editable?type=${data.letter_type}&case_no=${data.case_no}&code=${data.code}" class="btn btn-warning btn-sm" target="_BLANK"><i class="fa fa-edit" ></i></a> <a href="${base_url}response-format/generate-and-download?code=${data.code}" class="btn btn-danger btn-sm" target="_BLANK"><i class="fa fa-file-pdf-o"></i></a>`;
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
