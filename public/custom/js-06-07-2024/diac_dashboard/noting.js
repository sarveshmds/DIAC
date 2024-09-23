// Datatable initialization
var dataTableNotingList = $("#dataTableNotingList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	lengthMenu: [
		[100, 250, 500, "All"],
		[100, 250, 500, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_noting_list/CASE_NOTING_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_noting_trans_token").val(),
			case_no: $("#noting_case_no").val(),
		},
		global: false,
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{
			data: null,
			sWidth: "30%",
			render: function (data, type, row, meta) {
				return data.noting + (data.noting_text ? data.noting_text : "");
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.marked_by_user + " (" + data.marked_by_job_title + ")";
			},
		},
		{
			data: null,
			render: function (data, type, row, meta) {
				var text_class = "text-default";
				if (user_name == data.marked_to) {
					text_class = "text-danger font-weight-bold";
				}
				return (
					"<span class='" +
					text_class +
					"'>" +
					data.marked_to_user +
					" (" +
					data.marked_to_job_title +
					")" +
					"</span>"
				);
			},
		},
		{ data: "noting_date" },
		{ data: "next_date" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var fileViewBtn = "N/A";
				if (data.noting_file) {
					var files = JSON.parse(data.noting_file);
					if (files.length > 0) {
						fileViewBtn =
							'<div class="btn-group dropleft">\
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="View Files"><span class="fa fa-file"></span></button>\
						<ul class="dropdown-menu" role="menu">';
						files.forEach((element, index) => {
							fileViewBtn +=
								'<li><a href="' +
								base_url +
								"public/upload/noting_files/" +
								element +
								'" target="_BLANK" >File ' +
								(index + 1) +
								"</a></li>";
						});
						fileViewBtn +=
							"</ul>\
						</div>";
					}
				}
				return fileViewBtn;
			},
		},
		{
			data: null,
			sWidth: "10%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var buttons = "";

				if (role_code == "DIAC") {
					// buttons =
					// 	'<button class="btn btn-warning btn-sm" onclick="btnEditNotingForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button>';
				}

				if (
					role_code == "DEPUTY_COUNSEL" ||
					role_code == "CASE_MANAGER" ||
					role_code == "ACCOUNTS" ||
					role_code == "COORDINATOR"
				) {
					if (data.task_done == 0) {
						buttons +=
							'<button class="btn btn-success btn-sm btn_mark_task_done" data-tooltip="tooltip" title="Mark Done" data-case-code="' +
							data.case_no +
							'" data-id="' +
							data.id +
							'" data-task-done="1"><span class="fa fa-check"></span></button>';
					} else {
						// If date exceed from one day then do not show to cancel the task done status
						let date = data.task_done_on;
						let date_temp = new Date(date);

						let current_date = new Date();

						// Calculate the difference in milliseconds
						let difference = current_date - date_temp;

						// Check if the difference exceeds 24 hours
						let closing_date = 24 * 60 * 60 * 1000;

						if (difference < closing_date) {
							buttons +=
								'<button class="btn btn-warning btn-sm btn_mark_task_done" data-tooltip="tooltip" title="Remove Mark Done" data-case-code="' +
								data.case_no +
								'" data-id="' +
								data.id +
								'" data-task-done="0"><span class="fa fa-times"></span></button>';
						} else {
							return "<span class='badge bg-green'>Done</span>";
						}
					}
				}

				// <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Delete" onclick="btnDeleteNotingList(' +
				// 		data +
				// 		')"><span class="fa fa-trash"></span></button>

				return buttons;
			},
		},
	],
	columnDefs: [
		{
			targets: ["_all"],
			sorting: false,
			orderable: false,
		},
	],
	dom:
		"<'row'<'col-sm-6'l><'col-sm-6 dt-custom-btn-col'B>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		// {
		// 	text: '<span class="fa fa-plus"></span> Add',
		// 	className: "btn btn-custom",
		// 	init: function (api, node, config) {
		// 		$(node).removeClass("dt-button");
		// 	},
		// 	action: function (e, dt, node, config) {
		// 		add_noting_list_modal_open();
		// 	},
		// },
		{
			text: '<span class="fa fa-refresh"></span> Refresh',
			className: "btn btn-primary",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				dataTableNotingList.ajax.reload();
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
					base_url + "pdf/notings/" + $("#noting_case_no").val(),
					"_BLANK"
				);
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
	initComplete: function (settings, json) {
		if (
			role_code == "DIAC" ||
			role_code == "DEPUTY_COUNSEL" ||
			role_code == "CASE_MANAGER" ||
			role_code == "ACCOUNTS" ||
			role_code == "COORDINATOR"
		) {
			dataTableNotingList.column(7).visible(true);
		} else {
			dataTableNotingList.column(7).visible(false);
		}
	},
	createdRow: function (row, data, dataIndex) {
		if (data.marked_to == user_name) {
			$(row).addClass("bg-warning");
		}
	},
});

setInterval(function () {
	dataTableNotingList.ajax.reload();
}, 1000 * 60 * 10);

// * 60 * 5

function reset_noting_form() {
	$("#noting_form").trigger("reset");
	$(".noting-modal-title").html("");
	$("#hidden_noting_id").val("");
	$("#noting_op_type").val("ADD_CASE_NOTING");
	$("#noting_text").summernote("code", "");
	$("#noting_text_code").val(null).trigger("change");
	// $("#noting_marked_to option").prop("selected", false);
	$("#noting_marked_to").val(null).trigger("change");
	$("#noting_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#noting_form").data("bootstrapValidator").resetForm(true);
}

// Add case noting form
$("#noting_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#noting_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("noting_form"));
		urls = base_url + "service/add_case_noting_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			complete: function () {
				// Enable the submit button
				enable_submit_btn("noting_btn_submit");
			},
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == false) {
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
						//Reseting user form
						// $("#noting_form").trigger("reset");
						swal({
							title: "Success",
							text: obj.msg,
							type: "success",
							html: true,
						});

						// Redraw the datatable
						// dataTableNotingList = $("#dataTableNotingList").DataTable();
						// dataTableNotingList.draw();
						// dataTableNotingList.clear();

						dataTableNotingList.ajax.reload();
						reset_noting_form();

						// reset_noting_form();
						// window.location.reload();
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

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

/**
 * Function to fetch the minor case stages using major case stage
 */
function fetchMinorCaseStages(major_case_stage) {
	if (major_case_stage) {
		$.ajax({
			url: base_url + "minor-stages-setup/fetch-using-major-stage",
			method: "POST",
			data: {
				major_case_stage,
			},
			success: function (response) {
				var obj = JSON.parse(response);
				if (obj.status == true) {
					let stages = obj.data;

					let options = '<option value="">Select</option>';
					stages.forEach((stage) => {
						options += `<option value="${stage.code}">${stage.name}</option>`;
					});
					$("#minor_case_stage").html(options);
				} else {
					swal({
						title: "Error",
						text: obj.msg
							? obj.msg
							: "Something went wrong. Please refresh your browser or contact support team",
						type: "error",
						html: true,
					});
				}
				try {
				} catch (e) {
					sweetAlert(
						"Internal Server Error",
						"Something went wrong. Please refresh your browser or contact support team",
						"error"
					);
				}
			},
			error: function (err) {
				toastr.error(
					"Server failed, Something went wrong. Please refresh your browser or contact support team"
				);
			},
		});
	}
}

/**
 * On change the major stage
 */

$(document).on("change", "#major_case_stage", function () {
	let major_case_stage = $(this).val();

	if (major_case_stage) {
		fetchMinorCaseStages(major_case_stage);
	}
});

/**
 * Form submission of change case stage
 */

// Add case noting form
$("#case_stage_change_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		swal(
			{
				type: "warning",
				title: "Are you sure?",
				text: "You want to change the stage of the case!",
				showCancelButton: true,
				confirmButtonColor: "#00a616",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			},
			function (isConfirm) {
				if (isConfirm) {
					$("#mcs_change_status").attr("disabled", "disabled");
					var formData = new FormData(
						document.getElementById("case_stage_change_form")
					);
					urls = base_url + "service/change_case_status";

					$.ajax({
						url: urls,
						method: "POST",
						data: formData,
						contentType: false,
						processData: false,
						complete: function () {
							// Enable the submit button
							enable_submit_btn("mcs_change_status");
						},
						success: function (response) {
							// Enable the submit button
							enable_submit_btn("mcs_change_status");
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
											window.location.reload();
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
										text: obj.msg
											? obj.msg
											: "Something went wrong. Please refresh your browser or contact support team",
										type: "error",
										html: true,
									});
								}
							} catch (e) {
								sweetAlert(
									"Sorry",
									"Internal Server Error, Something went wrong. Please refresh your browser or contact support team",
									"error"
								);
							}
						},
						error: function (err) {
							// Enable the submit button
							enable_submit_btn("mcs_change_status");
							toastr.error(
								"Server Error, Something went wrong. Please refresh your browser or contact support team"
							);
						},
					});
				} else {
					swal.close();
				}
			}
		);
	},
	fields: {
		major_case_stage: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		minor_case_stage: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});

/**
 * On click the view status timeline , open the modal
 */

$("#btn_view_status_timeline").on("click", function () {
	$("#case_status_timeline_modal").modal("show");
});

/**
 * Function to mark done the task
 */

$(document).on("click", ".btn_mark_task_done", function () {
	let case_code = $(this).data("case-code");
	let id = $(this).data("id");
	let task_done = $(this).data("task-done");
	if (case_code && id) {
		swal(
			{
				title: "Are you sure?",
				text: "You want to change the task status.",
				type: "success",
				showCancelButton: true,
				confirmButtonText: "Yes",
				cancelButtonText: "No",
			},
			function (isConfirmed) {
				if (isConfirmed) {
					$.ajax({
						url: base_url + "service/noting_task_done",
						type: "POST",
						data: {
							case_code: case_code,
							id: id,
							task_done: task_done,
							csrf_trans_token: $("#csrf_noting_trans_token").val(),
						},
						success: function (response) {
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
										dataTableNotingList.ajax.reload();
									}
								);
							} else {
								toastr.error(
									obj.msg ? obj.msg : "Something went wrong, please try again."
								);
							}
						},
						error: function (error) {
							toastr.error(
								"Something went wrong, please try again or contact support team."
							);
						},
					});
				} else {
					swal.close();
				}
			}
		);
	}
});
