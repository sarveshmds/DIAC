$("#counsel_dis_check").on("change", function () {
	var cdc = $(this).val();
	if (cdc == 1) {
		$("#counsel_dodis").prop("disabled", false);
		$("#counsel_dis_date").show();
	} else {
		$("#counsel_dodis").prop("disabled", true);
		$("#counsel_dis_date").hide();
	}
});
$(document).ready(function () {
	$(".js-example-basic-single").select2();
	$("#counsel_dis_date").hide();
});

let counselDtButtons = [];
if (role_code == "DEPUTY_COUNSEL" || role_code == "DEO") {
	counselDtButtons = [
		{
			text: '<span class="fa fa-plus"></span> Add New Counsel',
			className: "btn btn-custom",
			init: function (api, node, config) {
				$(node).removeClass("dt-button");
			},
			action: function (e, dt, node, config) {
				// if (role_code == "CASE_FILER") {
				// 	add_counsel_list_modal_open();
				// } else {
				// 	var case_no = $("#counsels_case_no").val();

				// 	if (case_no) {
				// 		window.location.href =
				// 			base_url + "add-counsels?case_code=" + case_no;
				// 	} else {
				// 		toastr.error("Please provide case no to proceed.");
				// 	}
				// }

				if (role_code == "DEPUTY_COUNSEL" || role_code == "DEO") {
					var case_no = $("#counsels_case_no").val();

					if (case_no) {
						window.location.href =
							base_url + "add-counsels?case_code=" + case_no;
					} else {
						toastr.error("Please provide case no to proceed.");
					}
				}
			},
		},
	];
}

// ================================================
// Datatable initialization
// ================================================
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
		// {
		// 	data: null,
		// 	render: function (data, type, row, meta) {
		// 		return (
		// 			data.cl_res_name +
		// 			" (" +
		// 			data.cl_res_count_number +
		// 			") - " +
		// 			data.cl_res_type
		// 		);
		// 	},
		// },
		{ data: "name" },
		{ data: "enrollment_no" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				let cl_res_type = "";
				if (data.cl_res_type == "claimant") {
					cl_res_type = "Claimant";
				}
				if (data.cl_res_type == "respondant") {
					cl_res_type = "Respondant";
				}
				return (
					data.appearing_name +
					" (" +
					cl_res_type +
					"-" +
					data.cl_res_count_number +
					")"
				);
			},
		},
		{ data: "email" },
		{ data: "phone_number" },
		{ data: "discharged" },
		{ data: "date_of_discharge" },
		{
			data: null,
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				$button =
					'<button class="btn btn-success btn-sm" onclick="viewCounselForm(event)" data-tooltip="tooltip" title="View Details"><span class="fa fa-eye"></span></button> ';

				// $button +=
				// 	"<a href=" +
				// 	base_url +
				// 	"edit-counsels?code=" +
				// 	data.code +
				// 	"&case=" +
				// 	data.case_no +
				// 	' class="btn btn-warning btn-sm" data-tooltip="tooltip" title="Edit Details"><span class="fa fa-edit"></span></a> ';

				$button +=
					'<button class="btn btn-danger btn-sm" onclick="btnDeleteCounselList(' +
					data.code +
					')" data-tooltip="tooltip" title="Delete"><span class="fa fa-trash"></span></button>';

				return $button;
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
	buttons: counselDtButtons,
	drawCallback: function () {
		$("body").tooltip({ selector: '[data-tooltip="tooltip"]' });
	},
});

// Open the modal to add cause list
function add_counsel_list_modal_open() {
	$("#select_enrol_col").show();
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

	$("#counsels_list").val(null).trigger("change");

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
	var phone = oTable.fnGetData(row)["phone_number"];
	var discharged = oTable.fnGetData(row)["discharged"];
	var date_of_discharge = oTable.fnGetData(row)["date_of_discharge"];
	date_of_discharge = date_of_discharge ? date_of_discharge : "";
	var perm_address_1 = oTable.fnGetData(row)["perm_address_1"];
	var perm_address_2 = oTable.fnGetData(row)["perm_address_2"];
	var perm_country = oTable.fnGetData(row)["perm_country_name"];
	var perm_state = oTable.fnGetData(row)["perm_state_name"];
	var perm_pincode = oTable.fnGetData(row)["perm_pincode"];
	var corr_address_1 = oTable.fnGetData(row)["corr_address_1"];
	var corr_address_2 = oTable.fnGetData(row)["corr_address_2"];
	var corr_country = oTable.fnGetData(row)["corr_country_name"];
	var corr_state = oTable.fnGetData(row)["corr_state_name"];
	var corr_pincode = oTable.fnGetData(row)["corr_pincode"];

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

	table +=
		"<table class='table table-responsive table-bordered table-striped'>";
	table += `<thead>
		<tr>
			<th class='text-center' colspan='5'>Permanent Address</th>
		</tr>
		<tr>
			<th>Address 1</th>
			<th>Address 2</th>
			<th>State</th>
			<th>Country</th>
			<th>Pincode</th>
		</tr>
		</thead>
		<tbody>`;

	table += `
				<tr class="">
					<td>
						${perm_address_1}
					</td>
					<td>
						${perm_address_2}
					</td>
					<td>
						${perm_state}
					</td>
					<td>
						${perm_country}
					</td>
					<td>
						${perm_pincode}
					</td>
				</tr>
			`;

	table += "</tbody></table>";

	table +=
		"<table class='table table-responsive table-bordered table-striped'>";
	table += `<thead>
		<tr>
			<th class='text-center' colspan='5'>Correspondence Address</th>
		</tr>
		<tr>
			<th>Address 1</th>
			<th>Address 2</th>
			<th>State</th>
			<th>Country</th>
			<th>Pincode</th>
		</tr>
		</thead>
		<tbody>`;

	table += `
				<tr class="">
					<td>
						${corr_address_1}
					</td>
					<td>
						${corr_address_2}
					</td>
					<td>
						${corr_state}
					</td>
					<td>
						${corr_country}
					</td>
					<td>
						${corr_pincode}
					</td>
				</tr>
			`;

	table += "</tbody></table>";

	// Set the data in common modal to show the data
	$(".common-modal-title").html(
		'<span class="fa fa-file"></span> Counsel Details'
	);
	$(".common-modal-body").html(table);
	$("#common-modal").modal("show");
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
				// Enable the submit button
				enable_submit_btn("counsel_btn_submit");

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
					toastr.error(
						"Something went wrong, please refresh your browser or contact support team."
					);
				}
			},
			error: function (err) {
				// Enable the submit button
				enable_submit_btn("counsel_btn_submit");
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

$(document).on("change", "#counsels_list", function () {
	var counsel_code = $(this).val();

	// $("#counsel_form").trigger("reset");
	// $("#counsel_form").data("bootstrapValidator").resetForm(true);
	// console.log(counsel_code)
	if (counsel_code != "OTHER" && counsel_code != "" && counsel_code != null) {
		$.ajax({
			url: base_url + "service/get_counsel_details",
			type: "POST",
			data: { counsel_code: counsel_code },
			success: function (response) {
				var response = JSON.parse(response);

				if (response.status == true) {
					$("#counsel_name").val(response.data.name);
					$("#counsels_code").val(response.data.code);
					$("#counsel_enroll_no").val(response.data.enrollment_no);
					$("#counsel_email").val(response.data.email);
					$("#counsel_additional_email").val(response.data.additional_email);
					$("#counsel_contact").val(response.data.phone_number);
					$("#counsel_additional_contact").val(
						response.data.additional_phone_number
					);
					$("#counsel_permanent_address_1").val(response.data.perm_address_1);
					$("#counsel_permanent_address_2").val(response.data.perm_address_2);
					$(
						'#counsel_permanent_country option[value="' +
							response.data.perm_country +
							'"]'
					).prop("selected", true);
					get_states(
						response.data.perm_country,
						response.data.perm_state,
						"#counsel_permanent_state"
					);
					$("#counsel_permanent_pincode").val(response.data.perm_pincode);
					$("#counsel_corr_address_1").val(response.data.corr_address_1);
					$("#counsel_corr_address_2").val(response.data.corr_address_2);
					$(
						'#counsel_corr_country option[value="' +
							response.data.corr_country +
							'"]'
					).prop("selected", true);
					get_states(
						response.data.corr_country,
						response.data.corr_state,
						"#counsel_corr_state"
					);
					$("#counsel_corr_pincode").val(response.data.corr_pincode);

					// $(
					// 	'#counsel_appearing_for option[value="' +
					// 		response.data.appearing_for +
					// 		'"]'
					// ).prop("selected", true);
					// console.log(response.data.appearing_for);
					fieldsDisable();
				} else if (response.status == false) {
					toastr.error(response.msg);
				} else {
					toastr.error(
						"Something went wrong, please refresh your browser or try again."
					);
				}
			},
		});
	} else {
		$("#counsel_name").val("");
		$("#counsels_code").val("");
		$("#counsel_enroll_no").val("");
		$("#counsel_email").val("");
		$("#counsel_contact").val("");
		$("#counsel_permanent_address_1").val("");
		$("#counsel_permanent_address_2").val("");
		$("#counsel_permanent_country option").prop("selected", false);
		$("#counsel_permanent_state").val("");
		$("#counsel_permanent_pincode").val("");
		$("#counsel_corr_address_1").val("");
		$("#counsel_corr_address_2").val("");
		$("#counsel_corr_country").val("");
		$("#counsel_corr_state").val("");
		$("#counsel_corr_pincode").val("");
		// $("#counsel_appearing_for").val("");
		$("#counsel_dodis").val("");
		fieldsEnable();
	}
});

// function for get state for permanent address
$(document).on("change", "#counsel_permanent_country", function () {
	var country_code = $(this).val();

	get_states(country_code, "", "#counsel_permanent_state");
});

// function for get state for correspondence address
$(document).on("change", "#counsel_corr_country", function () {
	var country_code = $(this).val();

	get_states(country_code, "", "#counsel_corr_state");
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

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

function fieldsEnable() {
	$("#counsel_name").attr("readonly", false);
	$("#counsel_enroll_no").attr("readonly", false);
	$("#counsel_email").attr("readonly", false);
	$("#counsel_contact").attr("readonly", false);
	$("#counsel_permanent_address_1").attr("readonly", false);
	$("#counsel_permanent_address_2").attr("readonly", false);
	$("#counsel_permanent_country").attr("disabled", false);
	$("#counsel_permanent_state").attr("disabled", false);
	$("#counsel_permanent_pincode").attr("readonly", false);
	$("#counsel_corr_address_1").attr("readonly", false);
	$("#counsel_corr_address_2").attr("readonly", false);
	$("#counsel_corr_country").attr("disabled", false);
	$("#counsel_corr_state").attr("disabled", false);
	$("#counsel_corr_pincode").attr("readonly", false);
}
function fieldsDisable() {
	$("#counsel_name").attr("readonly", true);
	$("#counsel_enroll_no").attr("readonly", true);
	$("#counsel_email").attr("readonly", true);
	$("#counsel_contact").attr("readonly", true);
	$("#counsel_permanent_address_1").attr("readonly", true);
	$("#counsel_permanent_address_2").attr("readonly", true);
	$("#counsel_permanent_country").prop("disabled", true);
	$("#counsel_permanent_state").attr("disabled", true);
	$("#counsel_permanent_pincode").attr("readonly", true);
	$("#counsel_corr_address_1").attr("readonly", true);
	$("#counsel_corr_address_2").attr("readonly", true);
	$("#counsel_corr_country").attr("disabled", true);
	$("#counsel_corr_state").attr("disabled", true);
	$("#counsel_corr_pincode").attr("readonly", true);
}
$(document).ready(function () {
	fieldsDisable();
});
