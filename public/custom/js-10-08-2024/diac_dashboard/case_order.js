// $("#counsel_dis_check").on("change", function () {
// 	var cdc = $(this).val();
// 	if (cdc == 1) {
// 		$("#counsel_dodis").prop("disabled", false);
// 	} else {
// 		$("#counsel_dodis").prop("disabled", true);
// 	}
// });

var dtButtons = [];
if (role_code == "DEPUTY_COUNSEL" || role_code == "STENO") {
	dtButtons.push({
		text: '<span class="fa fa-plus"></span> Add Case Order',
		className: "btn btn-custom",
		init: function (api, node, config) {
			$(node).removeClass("dt-button");
		},
		action: function (e, dt, node, config) {
			add_case_order_modal_open();
		},
	});
}

// Datatable initialization
var dataTable = $("#dataTableCaseOrder").DataTable({
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
		url: base_url + "service/get_case_orders/CASE_ORDER_LIST",
		type: "POST",
		data: {
			csrf_case_order_token: $("#csrf_case_order_token").val(),
			case_code: $("#case_code").val(),
		},
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
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return data.order_given_on;
			},
		},
		// { data: "document" },
		{
			data: "document",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<a class="btn btn-primary btn-sm" href="' +
					base_url +
					"public/upload/case_orders/" +
					data +
					'" target="_BLANK"><span class="fa fa-file"></span> View Document</a>'
				);
			},
		},
		{ data: "created_at" },

		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let btn_view =
					'<button class="btn btn-success btn-sm" onclick="viewCounselForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button>';
				let btn_edit =
					'<button class="btn btn-warning btn-sm" onclick="btnEditCounselForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button>';
				let btn_delete =
					'<button class="btn btn-danger btn-sm" onclick="btnDeleteCounselList(' +
					data.code +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>';

				return btn_view + " " + btn_edit + " " + btn_delete;
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
	buttons: dtButtons,
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_case_order_modal_open() {
	$("#counsel_dodis").prop("disabled", true);
	$(".case-order-modal-title").html(
		'<span class="fa fa-plus"></span> Add Case Order'
	);
	$("#addCaseOrderModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addCaseOrderModal").on("hidden.bs.modal", function (e) {
	$("#case-order-form").trigger("reset");
	$(".case-order-modal-title").html("");
	// $("#hidden_counsel_id").val("");
	$("#case_order_op_type").val("ADD_CASE_ORDER");
	$(".edit-address-wrapper").hide();
	$("#case_order_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#case-order-form").data("bootstrapValidator").resetForm(true);
});

// View the counsels data
function viewCounselForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableCaseOrder").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var order_given_on = oTable.fnGetData(row)["order_given_on"];
	var document = oTable.fnGetData(row)["document"];
	var created_at = oTable.fnGetData(row)["created_at"];
	var code = oTable.fnGetData(row)["code"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Order given by: </th> <td>" +
		order_given_on +
		"</td></tr>";
	table += "<tr><th>Document: </th> <td>" + document + "</td></tr>";
	table += "<tr><th>Created At: </th> <td>" + created_at + "</td></tr>";
	// table += "<tr><th>Created At: </th> <td>" + code + "</td></tr>";
	table += "</table>";
	table += "</tbody></table>";
	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Other Pleadings Detail'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit form to open the modal with edit form
function btnEditCounselForm(event) {
	$(".case-order-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Case Order Details'
	);

	// Change the submit button to edit
	$("#case_order_btn_submit").attr("disabled", false);
	$("#case_order_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#case_order_form").trigger("reset");

	// Change the op type
	$("#case-order-form input[name='op_type']").val("EDIT_CASE_ORDER");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCaseOrder").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var order_given_on = oTable.fnGetData(row)["order_given_on"];
	var document = oTable.fnGetData(row)["document"];
	var code = oTable.fnGetData(row)["code"];
	// $("#hidden_code").val(order_given_on);

	// var case_no = oTable.fnGetData(row)["case_no"];
	// var counsel_name = oTable.fnGetData(row)["name"];

	// var addresses = getAllAddresses(code, "COUNSEL");
	var tr = "";
	$("#order_given_on").val(order_given_on);
	$("#hidden_code").val(code);

	// $("#case_order_doc").val(document);
	//

	$(".table-edit-address tbody").html(tr);
	$(".edit-address-wrapper").show();

	// Open the modal
	$("#addCaseOrderModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Add case counsel form
$("#case-order-form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		// $("#case_order_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("case-order-form"));
		// formData.set("row_count_array", JSON.stringify(rowCountArray));
		// formData.append('file',$('#case_order_doc')[0].files)
		$.ajax({
			url: base_url + "service/add_case_order_operation",
			method: "POST",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				try {
					var obj = JSON.parse(response);
					if (obj.status == false) {
						// Enable the submit button
						// enable_submit_btn("case_order_btn_submit");

						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else if (obj.status === "validationerror") {
						// Enable the submit button
						// enable_submit_btn("case_order_btn_submit");

						swal({
							title: "Validation Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						//Reseting user form
						$("#case-order-form").trigger("reset");
						toastr.success(obj.msg);

						// Redraw the datatable
						dataTableCauseList = $("#dataTableCaseOrder").DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();

						$("#addCaseOrderModal").modal("hide");
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
	fields: {
		order_given_on: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		// case_order_doc: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
	},
});

// // Delete function for case counsel details
function btnDeleteCounselList(code) {
	if (code) {
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
						url: base_url + "service/delete_case_order/DELETE_CASE_ORDER",
						type: "POST",
						data: {
							code: code,
							csrf_trans_token: $("#csrf_case_order_token").val(),
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

									dataTableCauseList = $("#dataTableCaseOrder").DataTable();
									dataTableCauseList.draw();
									dataTableCauseList.clear();
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
