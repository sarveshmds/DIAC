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
	// $("#car_number_help").text("");
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
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let address = ``;
				if (data.perm_address_1) {
					address += data.perm_address_1;
				}
				if (data.perm_address_2) {
					address += ", <br />" + data.perm_address_2;
				}
				if (data.perm_city) {
					address += ", <br />" + data.perm_city;
				}
				if (data.perm_state) {
					address += ", <br />" + data.perm_state_name;
				}
				if (data.perm_country) {
					address +=
						", <br />" + data.perm_country_name + "-" + data.perm_pincode;
				}
				return address;
			},
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let address = ``;
				if (data.corr_address_1) {
					address += data.corr_address_1;
				}
				if (data.corr_address_2) {
					address += ", <br />" + data.corr_address_2;
				}
				if (data.corr_city) {
					address += ", <br />" + data.corr_city;
				}
				if (data.corr_state) {
					address += ", <br />" + data.corr_state_name;
				}
				if (data.corr_country) {
					address +=
						", <br />" + data.corr_country_name + "-" + data.corr_pincode;
				}
				return address;
			},
		},
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
	// $("#car_number_help").text("Ex: 1,2,3 etc.");

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
				// Enable the submit button
				enable_submit_btn("car_btn_submit");
				// try {
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
				// Enable the submit button
				enable_submit_btn("car_btn_submit");
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
		// car_number: {
		// 	validators: {
		// 		notEmpty: {
		// 			message: "Required",
		// 		},
		// 	},
		// },
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
	var removed_desc = oTable.fnGetData(row)["removed_desc"];

	// Permanent address
	var perm_address_1 = oTable.fnGetData(row)["perm_address_1"];
	var perm_address_2 = oTable.fnGetData(row)["perm_address_2"];
	var perm_city = oTable.fnGetData(row)["perm_city"];
	var perm_country = oTable.fnGetData(row)["perm_country_name"];
	var perm_state = oTable.fnGetData(row)["perm_state_name"];
	var perm_pincode = oTable.fnGetData(row)["perm_pincode"];

	// Correspondance address
	var corr_address_1 = oTable.fnGetData(row)["corr_address_1"];
	var corr_address_2 = oTable.fnGetData(row)["corr_address_2"];
	var corr_city = oTable.fnGetData(row)["corr_city"];
	var corr_country = oTable.fnGetData(row)["corr_country_name"];
	var corr_state = oTable.fnGetData(row)["corr_state_name"];
	var corr_pincode = oTable.fnGetData(row)["corr_pincode"];

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

	table += "<tr><th colspan='2'>Permanent Address</th></tr>";
	table += "<tr><th>Address Line 1: </th> <td>" + perm_address_1 + "</td></tr>";
	table += "<tr><th>Address Line 2: </th> <td>" + perm_address_2 + "</td></tr>";
	table += "<tr><th>City: </th> <td>" + perm_city + "</td></tr>";
	table += "<tr><th>Country: </th> <td>" + perm_country + "</td></tr>";
	table += "<tr><th>State: </th> <td>" + perm_state + "</td></tr>";
	table += "<tr><th>Pincode: </th> <td>" + perm_pincode + "</td></tr>";

	table += "<tr><th colspan='2'>Correspondance Address</th></tr>";
	table += "<tr><th>Address Line 1: </th> <td>" + corr_address_1 + "</td></tr>";
	table += "<tr><th>Address Line 2: </th> <td>" + corr_address_2 + "</td></tr>";
	table += "<tr><th>City: </th> <td>" + corr_city + "</td></tr>";
	table += "<tr><th>Country: </th> <td>" + corr_country + "</td></tr>";
	table += "<tr><th>State: </th> <td>" + corr_state + "</td></tr>";
	table += "<tr><th>Pincode: </th> <td>" + corr_pincode + "</td></tr>";

	table += "<tr><th>Removed: </th> <td>" + removed_desc + "</td></tr>";
	table += "</table>";

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
	// $("#car_number_help").text("Ex: 1,2,3 etc.");

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

	// Permanent address
	var perm_address_1 = oTable.fnGetData(row)["perm_address_1"];
	var perm_address_2 = oTable.fnGetData(row)["perm_address_2"];
	var perm_city = oTable.fnGetData(row)["perm_city"];
	var perm_country = oTable.fnGetData(row)["perm_country"];
	var perm_state = oTable.fnGetData(row)["perm_state"];
	var perm_pincode = oTable.fnGetData(row)["perm_pincode"];

	// Correspondance address
	var corr_address_1 = oTable.fnGetData(row)["corr_address_1"];
	var corr_address_2 = oTable.fnGetData(row)["corr_address_2"];
	var corr_city = oTable.fnGetData(row)["corr_city"];
	var corr_country = oTable.fnGetData(row)["corr_country"];
	var corr_state = oTable.fnGetData(row)["corr_state"];
	var corr_pincode = oTable.fnGetData(row)["corr_pincode"];

	$("#car_hidden_case_no").val(case_no);
	$("#hidden_car_id").val(id);
	$("#hidden_car_code").val(code);
	$("#hidden_car_type").val(type);

	$("#car_name").val(name);
	// $("#car_number").val(count_number);
	$("#car_email").val(email);
	$("#car_contact_no").val(contact);
	$("#car_additional_contact_no").val(additonal_contact);
	$("#car_additional_email").val(additional_email);
	$('#car_removed option[value = "' + removed + '"]').prop("selected", true);

	$("#perm_address1").val(perm_address_1);
	$("#perm_address2").val(perm_address_2);
	$("#perm_city").val(perm_city);
	$("#perm_country").val(perm_country);
	// $("#perm_state").val(perm_state);
	getStatesUsingCountryCodeAndAppend(perm_country, "perm_state", perm_state);

	$("#perm_pincode").val(perm_pincode);

	$("#corr_address1").val(corr_address_1);
	$("#corr_address2").val(corr_address_2);
	$("#corr_city").val(corr_city);
	$("#corr_country").val(corr_country);
	getStatesUsingCountryCodeAndAppend(corr_country, "corr_state", corr_state);
	// $("#corr_state").val(corr_state);
	$("#corr_pincode").val(corr_pincode);

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
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let address = ``;
				if (data.perm_address_1) {
					address += data.perm_address_1;
				}
				if (data.perm_address_2) {
					address += ", <br />" + data.perm_address_2;
				}
				if (data.perm_city) {
					address += ", <br />" + data.perm_city;
				}
				if (data.perm_state) {
					address += ", <br />" + data.perm_state_name;
				}
				if (data.perm_country) {
					address +=
						", <br />" + data.perm_country_name + "-" + data.perm_pincode;
				}
				return address;
			},
		},
		{
			data: null,
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let address = ``;
				if (data.corr_address_1) {
					address += data.corr_address_1;
				}
				if (data.corr_address_2) {
					address += ", <br />" + data.corr_address_2;
				}
				if (data.corr_city) {
					address += ", <br />" + data.corr_city;
				}
				if (data.corr_state) {
					address += ", <br />" + data.corr_state_name;
				}
				if (data.corr_country) {
					address +=
						", <br />" + data.corr_country_name + "-" + data.corr_pincode;
				}
				return address;
			},
		},
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
	// $("#car_number_help").text("Ex: 1,2,3 etc.");

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
	var removed_desc = oTable.fnGetData(row)["removed_desc"];

	// Permanent address
	var perm_address_1 = oTable.fnGetData(row)["perm_address_1"];
	var perm_address_2 = oTable.fnGetData(row)["perm_address_2"];
	var perm_city = oTable.fnGetData(row)["perm_city"];
	var perm_country = oTable.fnGetData(row)["perm_country_name"];
	var perm_state = oTable.fnGetData(row)["perm_state_name"];
	var perm_pincode = oTable.fnGetData(row)["perm_pincode"];

	// Correspondance address
	var corr_address_1 = oTable.fnGetData(row)["corr_address_1"];
	var corr_address_2 = oTable.fnGetData(row)["corr_address_2"];
	var corr_city = oTable.fnGetData(row)["corr_city"];
	var corr_country = oTable.fnGetData(row)["corr_country_name"];
	var corr_state = oTable.fnGetData(row)["corr_state_name"];
	var corr_pincode = oTable.fnGetData(row)["corr_pincode"];

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

	table += "<tr><th colspan='2'>Permanent Address</th></tr>";
	table += "<tr><th>Address Line 1: </th> <td>" + perm_address_1 + "</td></tr>";
	table += "<tr><th>Address Line 2: </th> <td>" + perm_address_2 + "</td></tr>";
	table += "<tr><th>City: </th> <td>" + perm_city + "</td></tr>";
	table += "<tr><th>Country: </th> <td>" + perm_country + "</td></tr>";
	table += "<tr><th>State: </th> <td>" + perm_state + "</td></tr>";
	table += "<tr><th>Pincode: </th> <td>" + perm_pincode + "</td></tr>";

	table += "<tr><th colspan='2'>Correspondance Address</th></tr>";
	table += "<tr><th>Address Line 1: </th> <td>" + corr_address_1 + "</td></tr>";
	table += "<tr><th>Address Line 2: </th> <td>" + corr_address_2 + "</td></tr>";
	table += "<tr><th>City: </th> <td>" + corr_city + "</td></tr>";
	table += "<tr><th>Country: </th> <td>" + corr_country + "</td></tr>";
	table += "<tr><th>State: </th> <td>" + corr_state + "</td></tr>";
	table += "<tr><th>Pincode: </th> <td>" + corr_pincode + "</td></tr>";

	table += "<tr><th>Removed: </th> <td>" + removed_desc + "</td></tr>";
	table += "</table>";

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
	// $("#car_number_help").text("Ex: 1,2,3 etc.");

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
	// var flat_no = oTable.fnGetData(row)["flat_no"];
	// var locality = oTable.fnGetData(row)["locality"];
	// var district = oTable.fnGetData(row)["district"];
	// var city = oTable.fnGetData(row)["city"];
	// var state = oTable.fnGetData(row)["state"];
	// var country = oTable.fnGetData(row)["country"];
	// var pin = oTable.fnGetData(row)["pin"];
	var removed = oTable.fnGetData(row)["removed"];
	var counter_claimant = oTable.fnGetData(row)["counter_claimant"];

	// Permanent address
	var perm_address_1 = oTable.fnGetData(row)["perm_address_1"];
	var perm_address_2 = oTable.fnGetData(row)["perm_address_2"];
	var perm_city = oTable.fnGetData(row)["perm_city"];
	var perm_country = oTable.fnGetData(row)["perm_country"];
	var perm_state = oTable.fnGetData(row)["perm_state"];
	var perm_pincode = oTable.fnGetData(row)["perm_pincode"];

	// Correspondance address
	var corr_address_1 = oTable.fnGetData(row)["corr_address_1"];
	var corr_address_2 = oTable.fnGetData(row)["corr_address_2"];
	var corr_city = oTable.fnGetData(row)["corr_city"];
	var corr_country = oTable.fnGetData(row)["corr_country"];
	var corr_state = oTable.fnGetData(row)["corr_state"];
	var corr_pincode = oTable.fnGetData(row)["corr_pincode"];

	$("#car_hidden_case_no").val(case_no);
	$("#hidden_car_id").val(id);
	$("#hidden_car_code").val(code);
	$("#hidden_car_type").val(type);

	$("#car_name").val(name);
	// $("#car_number").val(count_number);
	$("#car_email").val(email);
	$("#car_contact_no").val(contact);
	$("#car_additional_contact_no").val(additonal_contact);
	$("#car_additional_email").val(additional_email);
	$('#car_removed option[value = "' + removed + '"]').prop("selected", true);

	$(".counter_claimant_col").show();
	$('#car_counter_claimant option[value = "' + counter_claimant + '"]').prop(
		"selected",
		true
	);

	$("#perm_address1").val(perm_address_1);
	$("#perm_address2").val(perm_address_2);
	$("#perm_city").val(perm_city);
	$("#perm_country").val(perm_country);
	$("#perm_state").val(perm_state);
	getStatesUsingCountryCodeAndAppend(perm_country, "perm_state", perm_state);
	$("#perm_pincode").val(perm_pincode);

	$("#corr_address1").val(corr_address_1);
	$("#corr_address2").val(corr_address_2);
	$("#corr_city").val(corr_city);
	$("#corr_country").val(corr_country);
	getStatesUsingCountryCodeAndAppend(corr_country, "corr_state", corr_state);
	// $("#corr_state").val(corr_state);
	$("#corr_pincode").val(corr_pincode);

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

// function for get state for permanent address
$(document).on("change", "#perm_country", function () {
	var country_code = $(this).val();

	getStatesUsingCountryCodeAndAppend(country_code, "perm_state", "");
});

// function for get state for correspondence address
$(document).on("change", "#corr_country", function () {
	var country_code = $(this).val();

	getStatesUsingCountryCodeAndAppend(country_code, "corr_state", "");
});

function get_states(country_code, select = "", select_ele) {
	$.ajax({
		url: base_url + "service/get_states_using_country_code",
		type: "POST",
		data: { country_code: country_code },
		success: function (response) {
			var response = JSON.parse(response);
			var options = '<option value="">Select State</option>';
			$.each(response, function (index, state) {
				if (select == "") {
					options +=
						'<option value="' + state.id + '">' + state.name + "</option>";
				} else {
					if (select == state.id) {
						options +=
							'<option value="' +
							state.id +
							'" selected>' +
							state.name +
							"</option>";
					} else {
						options +=
							'<option value="' + state.id + '">' + state.name + "</option>";
					}
				}
			});
			$(select_ele).html(options);
		},
	});
}
