// Datatable initialization
function getNotifications() {
	$.ajax({
		url: base_url + "service/get_all_notifications/GET_NOTIFICATIONS",
		type: "POST",
		data: {
			csrf_trans_token: $("#csrf_notifications_trans_token").val(),
		},
		success: function (response) {
			var response = JSON.parse(response);
			var data = response.data;

			var tbody = "";
			for (var i = 0; i < data.length; i++) {
				tbody += `<tr>
					<td class="text-center">
						<input type="checkbox" name="ids[]" class="notification_ids" value="${
							data[i].id
						}" />
					</td>
					<td>${i + 1}</td>
					<td><a href="${data[i].reference_link}" style="color: #191919;">${
					data[i].text
				}</a></td>
					<td class="text-center">
					<button class="btn btn-danger btn-sm" onclick="btnDeleteNotificationsList('${
						data[i].id
					}')"><span class="fa fa-trash"></span></button>
					</td>
				</tr>`;
			}
			$("#dataTableNotificationsList tbody").html(tbody);
			$("#dataTableNotificationsList").DataTable();
		},
		error: function (error) {
			toastr.error("Something went wrong. While fetching notifications.");
		},
	});
}

$(document).ready(function () {
	getNotifications();
});

// var dataTable = $("#dataTableNotificationsList").DataTable({
// 	processing: true,
// 	serverSide: false,
// 	autoWidth: false,
// 	sorting: false,
// 	ordering: false,
// 	responsive: true,
// 	order: [],
// 	lengthMenu: [
// 		[10, 25, 50, -1],
// 		[10, 25, 50, "All"],
// 	],
// 	ajax: {
// 		url: base_url + "service/get_all_notifications/GET_NOTIFICATIONS",
// 		type: "POST",
// 		data: {
// 			csrf_trans_token: $("#csrf_notifications_trans_token").val(),
// 		},
// 		dataSrc: function (json) {
// 			return json.data[0];
// 		},
// 	},
// 	columns: [
// 		{
// 			data: null,
// 			render: function (data, type, row, meta) {
// 				return meta.row + meta.settings._iDisplayStart + 1;
// 			},
// 		},
// 		{
// 			data: "text",
// 		},
// 		{
// 			data: "id",
// 			sWidth: "15%",
// 			sClass: "alignCenter",
// 			render: function (data, type, row, meta) {
// 				return (
// 					'<button class="btn btn-danger btn-sm" onclick="btnDeleteNotificationsList(' +
// 					data +
// 					')"><span class="fa fa-trash"></span></button>'
// 				);
// 			},
// 		},
// 	],
// 	columnDefs: [
// 		{
// 			target: [0, 1],
// 			orderable: false,
// 			sorting: false,
// 		},
// 	],
// });

// Delete function for notifications
function btnDeleteNotificationsList(id) {
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
						url: base_url + "service/delete_notification/DELETE_NOTIFICATION",
						type: "POST",
						data: {
							id: id,
							csrf_trans_token: $("#csrf_notifications_trans_token").val(),
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

									window.location.reload();
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

// Delete all notifications
$(document).on("click", "#btn_delete_all_notification", function () {
	swal(
		{
			type: "error",
			title: "Are you sure?",
			text: "You want to delete all the notifications.",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Delete",
			cancelButtonText: "Cancel",
		},
		function (isConfirm) {
			if (isConfirm) {
				$.ajax({
					url:
						base_url +
						"service/delete_all_notification/DELETE_ALL_NOTIFICATION",
					type: "POST",
					data: {
						csrf_trans_token: $("#csrf_notifications_trans_token").val(),
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

								window.location.reload();
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
});

// Delete selected notifications
$(document).on("click", "#btn_delete_selected_notification", function () {
	swal(
		{
			type: "error",
			title: "Are you sure?",
			text: "You want to delete all the selected notifications.",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Delete",
			cancelButtonText: "Cancel",
		},
		function (isConfirm) {
			if (isConfirm) {
				var ids = [];
				$(".notification_ids:checked").each(function (i, v) {
					ids.push($(v).val());
				});

				if (ids.length > 0) {
					$.ajax({
						url:
							base_url +
							"service/delete_notification/DELETE_SELECTED_NOTIFICATION",
						type: "POST",
						data: {
							ids: ids,
							csrf_trans_token: $("#csrf_notifications_trans_token").val(),
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

									window.location.reload();
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
					toastr.error("Please select records to delete.");
				}
			} else {
				swal.close();
			}
		}
	);
});
