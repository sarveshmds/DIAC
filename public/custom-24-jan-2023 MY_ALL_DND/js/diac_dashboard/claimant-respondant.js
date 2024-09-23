// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

// On closing the modal reset the form
$("#addCarListModal").on("hidden.bs.modal", function (e) {
	$("#car_form").trigger("reset");
	$("#car_btn_submit")[0].innerHTML =
		"<i class='fa fa-paper-plane'></i> Save Details";
	$("#hidden_car_id").val("");
	$("#car_op_type").val("");
	$("#car_number_help").text("");
	$(".counter_claimant_col").hide();
	$(".edit-address-wrapper").hide();
	$("#car_form").data("bootstrapValidator").resetForm(true);
});

// ===========================================================================
// Datatable initialization
var dataTable = $("#dataTableClaimantList").DataTable({
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
		url: base_url + "service/get_all_claimant_list/CASE_CLAIMANT_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_cl_trans_token").val(),
			case_no: $("#cl_case_no").val(),
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
		{ data: "count_number" },
		{ data: "email" },
		{ data: "contact" },
		{ data: "removed_desc" },
		{
			data: "code",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewClaimantData(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnClaimantEditForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteClaimant(' +
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
			text: '<span class="fa fa-plus"></span> Add New Claimant',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_claimant_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_claimant_list_modal_open() {
	// Change the title of add form
	$(".car-modal-title").html('<span class="fa fa-plus"></span> Add Claimant');

	$("#hidden_car_type").val("claimant");
	$("#car_number_help").text("Ex: 1,2,3 etc.");

	// Set op type
	$("#car_op_type").val("ADD_CLAIMANT_DETAILS");

	// Open the modal
	$("#addCarListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Add claimant list form
$("#car_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#car_btn_submit").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("car_form"));
		formData.set("row_count_array", JSON.stringify(rowCountArray));

		urls = base_url + "service/add_car_operation";

		$.ajax({
			url: urls,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (response) {
				// try {
				var obj = JSON.parse(response);
				if (obj.status == false) {
					// Enable the submit button
					enable_submit_btn("car_btn_submit");

					swal({
						title: "Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else if (obj.status === "validationerror") {
					// Enable the submit button
					enable_submit_btn("car_btn_submit");

					swal({
						title: "Validation Error",
						text: obj.msg,
						type: "error",
						html: true,
					});
				} else {
					var car_type = $("#hidden_car_type").val();

					if (car_type == "claimant") {
						// Redraw the datatable
						dataTableCauseList = $("#dataTableClaimantList").DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();
					} else if (car_type == "respondant") {
						// Redraw the datatable
						dataTableCauseList = $("#dataTableRespondantList").DataTable();
						dataTableCauseList.draw();
						dataTableCauseList.clear();
					}

					//Reseting user form
					$("#car_form").trigger("reset");
					toastr.success(obj.msg);

					$("#addCarListModal").modal("hide");
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
		car_name: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		car_number: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
		car_contact_no: {
			validators: {
				regexp: {
					regexp: /^[0-9+-]+$/,
					message: "Only numbers, -, + are allowed.",
				},
			},
		},
		car_email: {
			validators: {
				emailAddress: {
					message: "Invalid email address",
				},
			},
		},
	},
});

// View the claimant data
function viewClaimantData(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableClaimantList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];
	var name = oTable.fnGetData(row)["name"];
	var count_number = oTable.fnGetData(row)["count_number"];
	var contact = oTable.fnGetData(row)["contact"];
	var email = oTable.fnGetData(row)["email"];
	var additonal_contact = oTable.fnGetData(row)["additonal_contact"];
	var additional_email = oTable.fnGetData(row)["additional_email"];

	var address = "";
	var flat_no = oTable.fnGetData(row)["flat_no"]
		? oTable.fnGetData(row)["flat_no"] + ", "
		: "";
	var locality = oTable.fnGetData(row)["locality"]
		? oTable.fnGetData(row)["locality"] + ", "
		: "";
	var district = oTable.fnGetData(row)["district"]
		? "<b>District:</b> " + oTable.fnGetData(row)["district"] + ", "
		: "";
	var city = oTable.fnGetData(row)["city"]
		? oTable.fnGetData(row)["city"] + ", "
		: "";
	var state = oTable.fnGetData(row)["state"]
		? oTable.fnGetData(row)["state"] + ", "
		: "";
	var country = oTable.fnGetData(row)["country"]
		? oTable.fnGetData(row)["country_name"] + ""
		: "";
	var pin = oTable.fnGetData(row)["pin"]
		? " - " + oTable.fnGetData(row)["pin"]
		: "";

	var address = flat_no + locality + district + city + state + country + pin;

	var removed = oTable.fnGetData(row)["removed"];
	var removed_desc = oTable.fnGetData(row)["removed_desc"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Name: </th> <td>" +
		name +
		" - " +
		count_number +
		"</td></tr>";
	table += "<tr><th>Contact Number: </th> <td>" + contact + "</td></tr>";
	table +=
		"<tr><th>Additional Contact Number: </th> <td>" +
		additonal_contact +
		"</td></tr>";
	table += "<tr><th>Email: </th> <td>" + email + "</td></tr>";
	table +=
		"<tr><th>Additional Email: </th> <td>" + additional_email + "</td></tr>";
	table += "<tr><th>Removed: </th> <td>" + removed_desc + "</td></tr>";
	table += "</table>";

	// get all the address
	var addresses = getAllAddresses(code, "CLAIMANT");

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
		'<span class="fa fa-file"></span> Claimant Detail: ' + name
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit claimant form to open the modal with edit form
function btnClaimantEditForm(event) {
	// Change the title of edit form
	$(".car-modal-title").html('<span class="fa fa-edit"></span> Edit Claimant');
	$("#car_number_help").text("Ex: 1,2,3 etc.");

	// Change the submit button to edit
	$("#car_btn_submit").attr("disabled", false);
	$("#car_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#car_form").trigger("reset");

	// Change the op type
	$("#car_form input[name='op_type']").val("EDIT_CLAIMANT_DETAILS");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableClaimantList").dataTable();
	var row;

	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var type = oTable.fnGetData(row)["type"];
	var name = oTable.fnGetData(row)["name"];
	var count_number = oTable.fnGetData(row)["count_number"];
	var contact = oTable.fnGetData(row)["contact"];
	var email = oTable.fnGetData(row)["email"];
	var additonal_contact = oTable.fnGetData(row)["additonal_contact"];
	var additional_email = oTable.fnGetData(row)["additional_email"];
	var removed = oTable.fnGetData(row)["removed"];

	// get all the address
	var addresses = getAllAddresses(code, "CLAIMANT");
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

	$("#car_hidden_case_no").val(case_no);
	$("#hidden_car_id").val(id);
	$("#hidden_car_code").val(code);
	$("#hidden_car_type").val(type);

	$("#car_name").val(name);
	$("#car_number").val(count_number);
	$("#car_email").val(email);
	$("#car_contact_no").val(contact);
	$("#car_additional_contact_no").val(additonal_contact);
	$("#car_additional_email").val(additional_email);
	$('#car_removed option[value = "' + removed + '"]').prop("selected", true);

	// Open the modal after filling all the details
	$("#addCarListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Delete function for claimant
function btnDeleteClaimant(code) {
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
						url: base_url + "service/delete_claimant/DELETE_CLAIMANT",
						type: "POST",
						data: {
							code: code,
							csrf_trans_token: $("#csrf_cl_trans_token").val(),
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

									dataTableCauseList = $("#dataTableClaimantList").DataTable();
									dataTableCauseList.draw();
									dataTableCauseList.clear();
								}
							} catch (e) {
								swal("Sorry", "Unable to delete.Please Try Again !", "error");
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

// dataTableRespondantList
// ================================================================================
// Respondant

// Datatable initialization
var dataTable = $("#dataTableRespondantList").DataTable({
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
		url: base_url + "service/get_all_respondant_list/CASE_RESPONDANT_LIST",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_res_trans_token").val(),
			case_no: $("#res_case_no").val(),
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
		{ data: "count_number" },
		{ data: "email" },
		{ data: "contact" },
		{
			data: null,
			sWidth: "15%",
			render: function (data, type, row, meta) {
				if (data.counter_claimant) {
					return (
						data.counter_claimant.charAt(0).toUpperCase() +
						data.counter_claimant.slice(1)
					);
				}
				return "";
			},
		},
		{ data: "removed_desc" },
		{
			data: "code",
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<button class="btn btn-success btn-sm" onclick="viewRespondentData(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> <button class="btn btn-warning btn-sm" onclick="btnRespondantEditForm(event)" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></button> <button class="btn btn-danger btn-sm" onclick="btnDeleteRespondant(' +
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
			text: '<span class="fa fa-plus"></span> Add New Respondant',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				add_respondant_list_modal_open();
			},
		},
	],
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add for respondant
function add_respondant_list_modal_open() {
	// Change the title of add form
	$(".car-modal-title").html('<span class="fa fa-plus"></span> Add Respondant');

	$("#hidden_car_type").val("respondant");
	$("#car_number_help").text("Ex: 1,2,3 etc.");

	// Set op type
	$("#car_op_type").val("ADD_RESPONDANT_DETAILS");

	$(".counter_claimant_col").show();
	// Open the modal
	$("#addCarListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// View the respondent data
function viewRespondentData(event) {
	// Get data table instance to get the data through row
	var oTable = $("#dataTableRespondantList").dataTable();
	var row;
	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];
	var name = oTable.fnGetData(row)["name"];
	var count_number = oTable.fnGetData(row)["count_number"];
	var contact = oTable.fnGetData(row)["contact"];
	var email = oTable.fnGetData(row)["email"];
	var additonal_contact = oTable.fnGetData(row)["additonal_contact"];
	var additional_email = oTable.fnGetData(row)["additional_email"];

	var address = "";
	var flat_no = oTable.fnGetData(row)["flat_no"]
		? oTable.fnGetData(row)["flat_no"] + ", "
		: "";
	var locality = oTable.fnGetData(row)["locality"]
		? oTable.fnGetData(row)["locality"] + ", "
		: "";
	var district = oTable.fnGetData(row)["district"]
		? "<b>District:</b> " + oTable.fnGetData(row)["district"] + ", "
		: "";
	var city = oTable.fnGetData(row)["city"]
		? oTable.fnGetData(row)["city"] + ", "
		: "";
	var state = oTable.fnGetData(row)["state"]
		? oTable.fnGetData(row)["state"] + ", "
		: "";
	var country = oTable.fnGetData(row)["country"]
		? oTable.fnGetData(row)["country_name"] + " "
		: "";
	var pin = oTable.fnGetData(row)["pin"]
		? " - " + oTable.fnGetData(row)["pin"]
		: "";

	var address = flat_no + locality + district + city + state + country + pin;

	var removed_desc = oTable.fnGetData(row)["removed_desc"];

	// Set data into table
	var table =
		"<table class='table table-responsive table-bordered table-striped'>";
	table +=
		"<tr><th width='30%'>Name: </th> <td>" +
		name +
		" - " +
		count_number +
		"</td></tr>";
	table += "<tr><th>Contact Number: </th> <td>" + contact + "</td></tr>";
	table +=
		"<tr><th>Additional Contact Number: </th> <td>" +
		additonal_contact +
		"</td></tr>";
	table += "<tr><th>Email: </th> <td>" + email + "</td></tr>";
	table +=
		"<tr><th>Additional Email: </th> <td>" + additional_email + "</td></tr>";
	table += "<tr><th>Address: </th> <td>" + address + "</td></tr>";
	table += "<tr><th>Removed: </th> <td>" + removed_desc + "</td></tr>";
	table += "</table>";

	// get all the address
	var addresses = getAllAddresses(code, "RESPONDENT");

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
		'<span class="fa fa-file"></span> Respondent Detail: ' + name
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
}

// Button edit respondant form to open the modal with edit form
function btnRespondantEditForm(event) {
	// Change the title of edit form
	$(".car-modal-title").html(
		'<span class="fa fa-edit"></span> Edit Respondant'
	);
	$("#car_number_help").text("Ex: 1,2,3 etc.");

	// Change the submit button to edit
	$("#car_btn_submit").attr("disabled", false);
	$("#car_btn_submit")[0].innerHTML =
		"<i class='fa fa-edit'></i> Update Details";

	// Reset the form
	$("#car_form").trigger("reset");

	// Change the op type
	$("#car_form input[name='op_type']").val("EDIT_RESPONDANT_DETAILS");

	// Get data table instance to get the data through row
	var oTable = $("#dataTableRespondantList").dataTable();
	var row;

	if (event.target.tagName == "BUTTON")
		row = event.target.parentNode.parentNode;
	else if (event.target.tagName == "SPAN")
		row = event.target.parentNode.parentNode.parentNode;

	var id = oTable.fnGetData(row)["id"];
	var code = oTable.fnGetData(row)["code"];
	var case_no = oTable.fnGetData(row)["case_no"];
	var type = oTable.fnGetData(row)["type"];
	var name = oTable.fnGetData(row)["name"];
	var count_number = oTable.fnGetData(row)["count_number"];
	var contact = oTable.fnGetData(row)["contact"];
	var email = oTable.fnGetData(row)["email"];
	var additonal_contact = oTable.fnGetData(row)["additonal_contact"];
	var additional_email = oTable.fnGetData(row)["additional_email"];
	var flat_no = oTable.fnGetData(row)["flat_no"];
	var locality = oTable.fnGetData(row)["locality"];
	var district = oTable.fnGetData(row)["district"];
	var city = oTable.fnGetData(row)["city"];
	var state = oTable.fnGetData(row)["state"];
	var country = oTable.fnGetData(row)["country"];
	var pin = oTable.fnGetData(row)["pin"];
	var removed = oTable.fnGetData(row)["removed"];
	var counter_claimant = oTable.fnGetData(row)["counter_claimant"];

	$("#car_hidden_case_no").val(case_no);
	$("#hidden_car_id").val(id);
	$("#hidden_car_code").val(code);
	$("#hidden_car_type").val(type);

	$("#car_name").val(name);
	$("#car_number").val(count_number);
	$("#car_email").val(email);
	$("#car_contact_no").val(contact);
	$("#car_additional_contact_no").val(additonal_contact);
	$("#car_additional_email").val(additional_email);
	$('#car_removed option[value = "' + removed + '"]').prop("selected", true);
	$('#car_counter_claimant option[value = "' + counter_claimant + '"]').prop(
		"selected",
		true
	);

	$(".counter_claimant_col").show();

	// get all the address
	var addresses = getAllAddresses(code, "RESPONDENT");
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
						<button type="button" class="btn btn-warning btn-sm btn-edit-address" data-id="${address.id}"><i class="fa fa-edit"></i></button>
						<button type="button" class="btn btn-danger btn-sm btn-delete-address" data-id="${address.id}"><i class="fa fa-trash"></i></button>
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

	// Open the modal after filling all the details
	$("#addCarListModal").modal({
		backdrop: "static",
		keyboard: false,
	});
}

// Delete function for respondant
function btnDeleteRespondant(code) {
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
						url: base_url + "service/delete_respondant/DELETE_RESPONDANT",
						type: "POST",
						data: {
							code: code,
							csrf_trans_token: $("#csrf_res_trans_token").val(),
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

									dataTableCauseList = $(
										"#dataTableRespondantList"
									).DataTable();
									dataTableCauseList.draw();
									dataTableCauseList.clear();
								}
							} catch (e) {
								swal("Sorry", "Unable to delete.Please Try Again !", "error");
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
