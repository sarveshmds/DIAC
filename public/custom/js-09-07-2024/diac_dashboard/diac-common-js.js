$(document)
	.ajaxStart(function () {
		$(".site-loader").show();
	})
	.ajaxStop(function () {
		$(".site-loader").hide();
	});

function getDIACCaseNumber(case_details) {
	return `${case_details.case_no_prefix}/${case_details.case_no}/${case_details.case_no_year}`;
}

// Function to enable the submit button
function enable_submit_btn(button) {
	$("#" + button).attr("disabled", false);
}

// Covert string to uppercase
function stringToUpperCase(id) {
	$("#" + id).val(
		$("#" + id)
			.val()
			.toUpperCase()
	);
}

function checkNotifications() {
	// send an ajax request
	$.ajax({
		url: base_url + "service/check_notification",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_token_universal").val(),
		},
		global: false,
		success: function (response) {
			var response = JSON.parse(response);
			var notificationPopUp = false;

			// set notification count
			var notification_count = response.count;
			$("#notification_count").text(notification_count);

			if (response.status == true) {
				var li = "";
				var notification_data = response.notification_data;
				if (notification_data.length > 0) {
					$(notification_data).each(function (index, value) {
						li +=
							'<li>\
							<a href="' +
							value.reference_link +
							'">\
							  <i class="fa fa-info-circle text-aqua"></i>' +
							value.text +
							"\
							</a>\
						  </li>";

						if (value.popup == 0) {
							// Show notification alert
							toastr.info(value.text, "Notification", {
								timeOut: 0,
								extendedTimeOut: 0,
								closeButton: true,
								preventDuplicates: true,
								onclick: function () {
									toastr.clear();
								},
							});

							notificationPopUp = true;
						}
					});
				} else {
					li =
						'<li><a href="#"><i class="fa fa-info-circle text-aqua"></i> No new notification.</a></li>';
				}

				$("#notification_menu_list").html(li);
				$(".db-notificaiton-list").html(li);

				// Reload the datatables
				// if (typeof dataTable !== "undefined") {
				// 	dataTable.ajax.reload();
				// }
				if (typeof dataTableAllottedCaseList !== "undefined") {
					dataTableAllottedCaseList.ajax.reload();
				}

				// if (typeof dataTableMiscellaneousReply !== "undefined") {
				// 	dataTableMiscellaneousReply.ajax.reload();
				// }

				if (notificationPopUp) {
					// Marked the notification pop up to true
					markNotificationPopUp();
				}
			} else {
				li =
					'<li><a href="#"><i class="fa fa-info-circle text-aqua"></i> No new notification.</a></li>';
				$("#notification_menu_list").html(li);
				$(".db-notificaiton-list").html(li);
			}
		},
		error: function (error) {
			toastr.error("Network is not responding. Please try again.");
		},
	});
}

function markNotificationPopUp() {
	// send an ajax request
	$.ajax({
		url: base_url + "service/mark_notification_popup",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_token_universal").val(),
		},
		success: function (response) {},
		error: function (error) {
			toastr.error(
				"Something went wrong with notifications. Please refresh browser or contact support"
			);
		},
	});
}

$(document).ready(function () {
	checkNotifications();

	// Check for the notification for particular users or all users
	// on every 5 minutes check for the notification
	setInterval(function () {
		checkNotifications();
	}, 300000);

	// Convert the input value into uppercase
	$(".str_to_uppercase").on("keyup", function () {
		var value = $(this).val();
		$(this).val(value.toUpperCase());
	});

	// On closing the common modal reset the modal data
	$("#common-modal").on("hidden.bs.modal", function (e) {
		$(".common-modal-title").html("");
		$(".common-modal-body").html("");
	});

	// Responsive datatable even when tables are in tabs
	$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
		$($.fn.dataTable.tables(true))
			.DataTable()
			.columns.adjust()
			.responsive.recalc();
	});

	// Reminder ===========================================================
	$("#reminderModalBtn").on("click", function () {
		$("#reminder-modal").modal("show");
	});

	// Add Reminder form
	$("#reminder_form").bootstrapValidator({
		message: "This value is not valid",
		submitButtons: 'button[type="submit"]',
		submitHandler: function (validator, form, submitButton) {
			$("#add_reminder_btn").attr("disabled", "disabled");
			var formData = new FormData(document.getElementById("reminder_form"));
			urls = base_url + "service/add_reminder_operation";
			$.ajax({
				url: urls,
				method: "POST",
				data: formData,
				contentType: false,
				processData: false,
				cache: false,
				complete: function () {
					// Enable the submit button
					enable_submit_btn("add_reminder_btn");
				},
				success: function (response) {
					try {
						var obj = JSON.parse(response);
						if (obj.status == true) {
							$("#reminder_form").trigger("reset");
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
								"Something went wrong on server. Please try again or contact support."
							);
						}
					} catch (e) {
						sweetAlert("Sorry", "Unable to Save. Please Try Again !", "error");
					}
				},
				error: function (err) {
					toastr.error("Unable to save. Server failed to respond.");
				},
			});
		},
		fields: {
			reminder_date: {
				validators: {
					notEmpty: {
						message: "Required",
					},
				},
			},
			reminder_note: {
				validators: {
					notEmpty: {
						message: "Required",
					},
				},
			},
		},
	});
});

// Hide and show filters
$("#show_filters").on("click", function () {
	$("#filters-fieldset").slideToggle();
});

// =========================================================
// Case sortings
$("#btn_case_sorting").on("click", function () {
	if ($("#sorting_by").val() && $("#sort_to").val()) {
		dataTable.ajax.reload();
	} else {
		toastr.error("Both fields are required to sort the data");
	}
});

$("#btn_case_reset_sorting").on("click", function () {
	$("#sorting_by option").prop("selected", false);
	$("#sort_to option").prop("selected", false);
	dataTable.ajax.reload();
});

// =============================================
$(document).ready(function () {
	// Define the makeUppercase function
	$.fn.makeUppercase = function () {
		this.on("input", function () {
			$(this).val($(this).val().toUpperCase());
		});
		return this; // Allow chaining
	};

	// Apply makeUppercase to elements
	$(".uppercase").makeUppercase();
});

$(document).on("keyup", ".uppercase", function () {
	$(this).val($(this).val().toUpperCase());
});

/**
 * Show pending work status modal
 */
$("#btn_show_pending_work_calender_modal").on("click", function () {
	$("#pending_work_status_calender_modal").modal("show");
});
