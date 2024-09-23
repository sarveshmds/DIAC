// Datatable initialization
var dataTable = $("#dataTableNotingList").DataTable({
	processing: true,
	serverSide: true,
	autoWidth: false,
	responsive: true,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: base_url + "service/get_all_noting_list/CASE_NOTING_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_noting_trans_token").val(),
			case_no: $("#noting_case_no").val(),
		},
	},
	columns: [
		{
			data: "serial_no",
		},
		{
			data: null,
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
				var fileViewBtn = "No Attachment";
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
			data: "id",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				var buttons = "";

				if (role_code == "DIAC") {
					// buttons =
					// 	'<button class="btn btn-warning btn-sm" onclick="btnEditNotingForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button>';
				}

				return buttons;
				// <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Delete" onclick="btnDeleteNotingList(' +
				// 		data +
				// 		')"><span class="fa fa-trash"></span></button>
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
		if (role_code != "DIAC") {
			dataTable.column(7).visible(false);
		}
	},
	createdRow: function (row, data, dataIndex) {
		if (data.marked_to == user_name) {
			$(row).addClass("bg-warning");
		}
	},
});

function reset_noting_form() {
	$("#noting_form").trigger("reset");
	$(".noting-modal-title").html("");
	$("#hidden_noting_id").val("");
	$("#noting_op_type").val("");
	$("#noting_text").summernote("code", "");
	$("#noting_marked_to option").prop("selected", false);
	$("#noting_marked_to").select2();
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
						toastr.success(obj.msg);

						// Redraw the datatable
						// dataTableCauseList = $("#dataTableNotingList").DataTable();
						// dataTableCauseList.draw();
						// dataTableCauseList.clear();

						// reset_noting_form();
						window.location.reload();
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

// Open the modal to add cause list
// function add_noting_list_modal_open() {
// 	$("#noting_op_type").val("ADD_CASE_NOTING");
// 	$("#noting_dodis").prop("disabled", true);
// 	$(".noting-modal-title").html(
// 		'<span class="fa fa-plus"></span> Add Noting Details'
// 	);
// 	$("#addNotingListModal").modal({
// 		backdrop: "static",
// 		keyboard: false,
// 	});
// }

// On closing the modal reset the form
// $("#addNotingListModal").on("hidden.bs.modal", function (e) {
// 	$("#noting_form").trigger("reset");
// 	$(".noting-modal-title").html("");
// 	$("#hidden_noting_id").val("");
// 	$("#noting_op_type").val("");
// 	$("#noting_text").summernote("code", "");
// 	$("#noting_marked_to option").prop("selected", false);
// 	$("#noting_marked_to").select2();
// 	$("#noting_btn_submit")[0].innerHTML =
// 		"<i class='fa fa-paper-plane'></i> Save Details";
// 	$("#noting_form").data("bootstrapValidator").resetForm(true);
// });

// Button edit form to open the modal with edit form
// function btnEditNotingForm(event) {
// 	$(".noting-modal-title").html(
// 		'<span class="fa fa-edit"></span> Edit Noting Details'
// 	);
// 	$("#addNotingListModal").modal({
// 		backdrop: "static",
// 		keyboard: false,
// 	});

// 	// Change the submit button to edit
// 	$("#noting_btn_submit").attr("disabled", false);
// 	$("#noting_btn_submit")[0].innerHTML =
// 		"<i class='fa fa-edit'></i> Update Details";

// 	// Reset the form
// 	$("#noting_form").trigger("reset");

// 	// Change the op type
// 	$("#noting_op_type").val("EDIT_CASE_NOTING");

// 	// Get data table instance to get the data through row
// 	var oTable = $("#dataTableNotingList").dataTable();
// 	var row;
// 	if (event.target.tagName == "BUTTON")
// 		row = event.target.parentNode.parentNode;
// 	else if (event.target.tagName == "SPAN")
// 		row = event.target.parentNode.parentNode.parentNode;

// 	var id = oTable.fnGetData(row)["id"];
// 	var case_no = oTable.fnGetData(row)["case_no"];
// 	var noting = oTable.fnGetData(row)["noting"];
// 	var noting_date = oTable.fnGetData(row)["noting_date"];
// 	var next_date = oTable.fnGetData(row)["next_date"];
// 	var marked_to = oTable.fnGetData(row)["marked_to"];
// 	var noting_file = oTable.fnGetData(row)["noting_file"];

// 	$("#hidden_noting_id").val(id);
// 	$("#noting_hidden_case_no").val(case_no);
// 	$("#noting_text").summernote("code", noting);
// 	$("#noting_date").val(noting_date);
// 	$("#noting_next_date").val(next_date);
// 	$("#hidden_noting_file_name").val();

// 	$('#noting_marked_to option[value="' + marked_to + '"]').prop(
// 		"selected",
// 		true
// 	);
// 	$("#noting_marked_to").select2();
// 	$("#hidden_noting_file_name").val(noting_file);
// }

// Delete function for case noting details
// function btnDeleteNotingList(id) {
// 	if (id) {
// 		swal(
// 			{
// 				type: "error",
// 				title: "Are you sure?",
// 				text: "You want to delete the record.",
// 				showCancelButton: true,
// 				confirmButtonColor: "#DD6B55",
// 				confirmButtonText: "Delete",
// 				cancelButtonText: "Cancel",
// 			},
// 			function (isConfirm) {
// 				if (isConfirm) {
// 					$.ajax({
// 						url: base_url + "service/delete_case_noting/DELETE_CASE_NOTING",
// 						type: "POST",
// 						data: {
// 							id: id,
// 							csrf_trans_token: $("#csrf_noting_trans_token").val(),
// 						},
// 						success: function (response) {
// 							try {
// 								var obj = JSON.parse(response);

// 								if (obj.status == false) {
// 									$("#errorlog").html("");
// 									$("#errorlog").hide();
// 									toastr.error(obj.msg);
// 								} else if (obj.status === "validationerror") {
// 									swal({
// 										title: "Validation Error",
// 										text: obj.msg,
// 										type: "error",
// 										html: true,
// 									});
// 								} else {
// 									$("#errorlog").html("");
// 									$("#errorlog").hide();
// 									toastr.success(obj.msg);

// 									dataTableCauseList = $("#dataTableNotingList").DataTable();
// 									dataTableCauseList.draw();
// 									dataTableCauseList.clear();
// 								}
// 							} catch (e) {
// 								swal("Sorry", "Unable to Save.Please Try Again !", "error");
// 							}
// 						},
// 						error: function (error) {
// 							toastr.error("Something went wrong.");
// 						},
// 					});
// 				} else {
// 					swal.close();
// 				}
// 			}
// 		);
// 	}
// }

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}
