$("#counsel_dis_check").on("change", function () {
	var cdc = $(this).val();
	if (cdc == 1) {
		$("#counsel_dodis").prop("disabled", false);
	} else {
		$("#counsel_dodis").prop("disabled", true);
	}
});

// Datatable initialization
var dataTable = $("#dataTableCounselsList").DataTable({
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
		url: base_url + "service/get_all_counsels_list/CASE_COUNSELS_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_counsels_trans_token").val(),
			case_no: $("#counsels_case_no").val(),
		},
	},
	columns: [
		{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
		},
		{ data: "name" },
		{ data: "enrollment_no" },
		{
			data: null,
			render: function (data, type, row, meta) {
				return data.cl_res_name + " (" + data.cl_res_count_number + ")";
			},
		},
		{ data: "email" },
		{ data: "phone" },
		{ data: "discharged" },
		{ data: "date_of_discharge" },
		{
			data: "code",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewCounselForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnEditCounselForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteCounselList(' +
					data +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>'
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
			text: '<span class="fa fa-plus"></span> Add New Counsel',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_counsel_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_counsel_list_modal_open() {
	$("#counsel_dodis").prop("disabled", true);
	$(".counsel-modal-title").html(
		'<span class="fa fa-plus"></span> Add Counsel Details'
	);
	$("#addCounselListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// On closing the modal reset the form
$("#addCounselListModal").on("hidden.bs.modal", function (e) {
	$("#counsel_form").trigger("reset");
	$(".counsel-modal-title").html("");
	$("#hidden_counsel_id").val("");
	$("#counsel_op_type").val("ADD_CASE_COUNSEL");
	$(".edit-address-wrapper").hide();
	$("#counsel_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#counsel_form").data("bootstrapValidator").resetForm(true);
});

// View the counsels data
function viewCounselForm(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableCounselsList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];
	var name = oTable.fnGetData(row)["name"];
	var enrollment_no = oTable.fnGetData(row)["enrollment_no"];
	var cl_res_name = oTable.fnGetData(row)["cl_res_name"];
	var cl_res_count_number = oTable.fnGetData(row)["cl_res_count_number"];
	var email = oTable.fnGetData(row)["email"];
	var phone = oTable.fnGetData(row)["phone"];
	var discharged = oTable.fnGetData(row)["discharged"];
	var date_of_discharge = oTable.fnGetData(row)["date_of_discharge"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table += "<tr><th width='30%'>Name: </th> <td>" + name + "</td></tr>";
	table += "<tr><th>Enrollment No.: </th> <td>" + enrollment_no + "</td></tr>";
	table +=
		"<tr><th>Appearing For: </th> <td>" +
		cl_res_name +
		" (" +
		cl_res_count_number +
		")" +
		"</td></tr>";
	table += "<tr><th>Email: </th> <td>" + email + "</td></tr>";
	table += "<tr><th>Phone Number: </th> <td>" + phone + "</td></tr>";
	table += "<tr><th>Discharge: </th> <td>" + discharged + "</td></tr>";
	table +=
		"<tr><th>Date of Discharge: </th> <td>" + date_of_discharge + "</td></tr>";
	table += "</table>";

	// get all the address
	var addresses = getAllAddresses(code, "COUNSEL");

	table +=
		"<table class='table table-responsive table-bordered table-striped'>";
	table += `<thead>
		<tr>
			<th>Address 1</th>
			<th>Address 2</th>
			<th>State</th>
			<th>Country</th>
			<th>Pincode</th>
		</tr>
	</thead>
	<tbody>`;
	if (typeof addresses != "undefined" && addresses.length > 0) {
		$.each(addresses, function (index, address) {
			table += `
				<tr class="edit-address-row">
					<td>
						${address.address_one}
					</td>
					<td>
						${address.address_two}
					</td>
					<td>
						${address.state_name}
					</td>
					<td>
						${address.country_name}
					</td>
					<td>
						${address.pincode}
					</td>
				</tr>
			`;
		});
	} else {
		table += `
			<tr class="edit-address-row">
				<td colspan="5">
					No address is added
				</td>
			</tr>
		`;
	}
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
	$(".counsel-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Counsel Details'
	);

	// Change the submit button to edit
	$("#counsel_btn_submit").attr("disabled", false);
	$("#counsel_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#counsel_form").trigger("reset");

	// Change the op type
	$("#counsel_form input[name='op_type']").val("EDIT_CASE_COUNSEL");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableCounselsList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];

	var case_no = oTable.fnGetData(row)["case_no"];
	var counsel_name = oTable.fnGetData(row)["name"];
	var counsel_enroll_no = oTable.fnGetData(row)["enrollment_no"];
	var counsel_appearing_for = oTable.fnGetData(row)["appearing_for"];
	var counsel_email = oTable.fnGetData(row)["email"];
	var counsel_contact = oTable.fnGetData(row)["phone"];
	var counsel_address = oTable.fnGetData(row)["address"];
	var counsel_dis_check = oTable.fnGetData(row)["discharge"];
	var counsel_dodis = oTable.fnGetData(row)["date_of_discharge"];

	$("#hidden_counsel_id").val(id);
	$("#counsel_hidden_case_no").val(case_no);
	$("#counsel_name").val(counsel_name);
	$("#counsel_enroll_no").val(counsel_enroll_no);

	$(
		'#counsel_appearing_for option[value="' + counsel_appearing_for + '"]'
	).prop("selected", true);

	$("#counsel_email").val(counsel_email);
	$("#counsel_contact").val(counsel_contact);
	$("#counsel_dodis").val(counsel_dodis);

	if (counsel_dis_check == 1) {
		$("#counsel_dodis").prop("disabled", false);
	} else {
		$("#counsel_dodis").prop("disabled", true);
	}
	$('#counsel_dis_check option[value="' + counsel_dis_check + '"]').prop(
		"selected",
		true
	);

	// get all the address
	var addresses = getAllAddresses(code, "COUNSEL");
	var tr = "";

	if (typeof addresses != "undefined" && addresses.length > 0) {
		$.each(addresses, function (index, address) {
			tr += `
				<tr class="edit-address-row">
					<td>
						${address.address_one}
					</td>
					<td>
						${address.address_two}
					</td>
					<td>
						${address.state_name}
					</td>
					<td>
						${address.country_name}
					</td>
					<td>
						${address.pincode}
					</td>
					<td>
						<button type="button" class="btn btn-warning btn-sm btn-edit-address" data-id="${address.id}" title="Edit"><i class="fa fa-edit"></i></button>
						<button type="button" class="btn btn-danger btn-sm btn-delete-address" data-id="${address.id}" title="Delete"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			`;
		});
	} else {
		tr += `
			<tr class="edit-address-row">
				<td colspan="6">
					No address is added
				</td>
			</tr>
		`;
	}
	$(".table-edit-address tbody").html(tr);
	$(".edit-address-wrapper").show();

	// Open the modal
	$("#addCounselListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Add case counsel form
$("#counsel_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#counsel_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("counsel_form"));
		formData.set("row_count_array", JSON.stringify(rowCountArray));

		$.ajax({
			url: base_url + "service/add_case_counsel_operation",
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("counsel_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("counsel_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					//Reseting user form
					$("#counsel_form").trigger("reset");
					toastr.success(obj.msg);

					// Redraw the datatable
					dataTableCauseList = $("#dataTableCounselsList").DataTable();
					dataTableCauseList.draw();
					dataTableCauseList.clear();

					$("#addCounselListModal").modal("hide");
				}
				// } catch (e) {
				//     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				// }
			},
			error: function (err) {
				toastr.error("unable to save");
			},
		});
	},
	fields: {
		counsel_name: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		counsel_appearing_for: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		counsel_contact: {
			validators: {
				regexp: {
					regexp: /^[0-9+-]+$/,
					message: "Only numbers, -, + are allowed.",
				},
			},
		},
		counsel_email: {
			validators: {
				emailAddress: {
					message: "Invalid email address",
				},
			},
		},
	},
});

// Delete function for case counsel details
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
						url: base_url + "service/delete_case_counsel/DELETE_CASE_COUNSEL",
						type: "POST",
						data: {
							code: code,
							csrf_trans_token: $("#csrf_counsels_trans_token").val(),
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

									dataTableCauseList = $("#dataTableCounselsList").DataTable();
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

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}
