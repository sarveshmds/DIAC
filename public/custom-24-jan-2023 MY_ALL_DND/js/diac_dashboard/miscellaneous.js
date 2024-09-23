// Datatable initialization
var dataTable = $("#dataTableMiscellaneous").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url: base_url + "service/get_all_miscellaneous_list/ALL_MISCELLANEOUS_LIST",
		type: "POST",
		data: function (d) {
			d.csrf_trans_token = $("#csrf_trans_token").val();
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
		{ data: "diary_number" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var fileViewBtn = "";
				if (data.document) {
					var files = JSON.parse(data.document);
					if (files.length > 0) {
						fileViewBtn +=
							'<div class="btn-group dropleft">\
                              <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="View Documents"><span class="fa fa-file"></span> Documents</button>\
                              <ul class="dropdown-menu" role="menu">';
						files.forEach((element, index) => {
							fileViewBtn +=
								'<li><a href="' +
								base_url +
								"public/upload/miscellaneous_documents/" +
								element +
								'" target="_BLANK" >Document ' +
								(index + 1) +
								"</a></li>";
						});
						fileViewBtn += "</ul>\
                              </div>";
					}
				}
				return fileViewBtn;
			},
		},
		{ data: "message" },
		{ data: "name" },
		{ data: "phone_number" },
		{
			data: null,
			sClass: "text-center",
			render: function (data, type, row, meta) {
				return (
					"<a href='" +
					base_url +
					"ims/other-notings?noting_group=" +
					customURIEncode("DIRECT_ORDERS") +
					"&type_code=" +
					customURIEncode(data.m_code) +
					"&type_name=" +
					customURIEncode("REFERRALS_REQUESTS") +
					"' class='btn bg-maroon btn-sm'> Click <i class='fa fa-arrow-right'></i></a>"
				);
			},
		},
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var buttons = "";
				// <a
				// 	href="${base_url}referral-requests-reply?miscellaneous_id=${data.id}"
				// 	class="btn btn-success btn-sm"
				// 	data-tooltip="tooltip"
				// 	title="Reply"
				// >
				// 	<span class="fa fa-mail-reply"></span>
				// </a>;
				var replyButton = ` `;

				if (role_code == "COORDINATOR") {
					buttons = "";
				} else {
					buttons =
						replyButton +
						'<a href="' +
						base_url +
						"add-referral-requests?id=" +
						data.id +
						'" class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></a>';
				}

				return buttons;
				// <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Delete Case" onclick="btnDeleteMiscellaneous(' +
				// 		data.id +
				// 		')"><span class="fa fa-trash"></span></button>
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
	buttons: [],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

$(document).ready(function () {
	if (role_code != "COORDINATOR") {
		$("#dataTableMiscellaneous_wrapper .dt-custom-btn-col .dt-buttons").html(
			'<a class="btn btn-custom" type="button" href="' +
				base_url +
				'add-referral-requests"><span class="fa fa-plus"></span> Add Referrals/Requests</a>'
		);
	}
});

// Delete function for cause list
function btnDeleteMiscellaneous(id) {
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
						url: base_url + "service/delete_miscellaneous",
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

									dataTable.ajax.reload();
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
function enable_submit_btn() {
	$("#btn_submit").attr("disabled", false);
}

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

// ==========================================================================================
// Add Miscellaneous
$("#miscellaneous_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("miscellaneous_form"));

		$.ajax({
			url: base_url + "service/add_miscellaneous_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("btn_submit");
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
								window.location.href = obj.redirect_url;
							}
						);
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
							text: obj.msg,
							type: "error",
							html: true,
						});
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {},
});

// ======================================================
var dataTableMiscellaneousReply = $("#dataTableMiscellaneousReply").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	searching: false,
	order: [],
	ajax: {
		url:
			base_url +
			"service/get_all_miscellaneous_replies_list/ALL_MISCELLANEOUS_REPLIES_LIST",
		type: "POST",
		data: function (d) {
			d.miscellaneous_id = $("#hidden_miscellaneous_id").val();
			d.csrf_trans_token = $("#csrf_trans_token").val();
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
				return parseInt(data.serial_no) + 1;
			},
		},
		{ data: "reply" },
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.reply_from_user) {
					return data.reply_from_user + " (" + data.reply_from_job_title + ")";
				}
				return "";
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				if (data.reply_to_user) {
					return data.reply_to_user + " (" + data.reply_to_job_title + ")";
				}
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
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		{
			text: '<span class="fa fa-mail-reply"></span> Add Reply',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_miscellaneous_reply_list_modal_open();
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
					base_url +
						"pdf/miscellaneous-reply/" +
						$("#hidden_miscellaneous_id").val(),
					"_BLANK"
				);
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_miscellaneous_reply_list_modal_open() {
	$("#miscellaneous_reply_modal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#miscellaneous_reply_modal").on("hidden.bs.modal", function (e) {
	$("#miscellaneous_reply_form").trigger("reset");
	$(".miscellaneous_reply-modal-title").html("");
	$("#hidden_miscellaneous_reply_id").val("");
	// $("#miscellaneous_reply_op_type").val("");

	$("#miscellaneous_reply_to").select2("val", "");
	$("#miscellaneous_reply_text").summernote("code", "");
	$("#miscellaneous_reply_form").data("bootstrapValidator").resetForm(true);
});

// Add Miscellaneous Reply
$("#miscellaneous_reply_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#btn_submit").attr("disabled", "disabled");
		var formData = new FormData(
			document.getElementById("miscellaneous_reply_form")
		);

		$.ajax({
			url: base_url + "service/miscellaneous_reply_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				// Enable the submit button
				enable_submit_btn("btn_submit");
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
								$("#miscellaneous_reply_modal").modal("hide");
								dataTableMiscellaneousReply.ajax.reload();
							}
						);
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
							text: obj.msg,
							type: "error",
							html: true,
						});
					}
				} catch (e) {
					sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
				}
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {},
});
