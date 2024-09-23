// Datatable initialization
var dataTable = $('#fees_asses_tbl').DataTable({
	"processing": true,
	"serverSide": true,
	"autoWidth": false,
	"responsive": true,
	"order": [],
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"ajax": {
		url: base_url+"service/fees_assessment/GET_FEES_ASSESSMENT",
		type: 'POST',
		data: function(d){
			d.csrf_trans_token = $('#csrf_trans_token').val();
		}
	},
	"columns": [
		{
			data: null,
			render: function (data, type, row, meta) {
		        return meta.row + meta.settings._iDisplayStart + 1;
		    }
			
		},
		{data: 'case_no'},
		{data: 'case_title'},
		{data: 'sum_in_dispute'},
		{data: 'asses_date'},
		{data: null,
			render: function(data, type, row, meta){
				return (data.assessment_approved == 2)? '<span class="badge badge-danger">'+data.assessment_approved_desc+'</span>': '<span class="badge badge-primary">'+data.assessment_approved_desc+'</span>';
			}
		},
		{
			data: 'case_code',
			sWidth: "15%",
			sClass: "alignCenter",
			render: function (data, type, row, meta) {
				return (
					'<a href="'+base_url+'view-fees-assessment/'+data+'"  class="btn btn-success btn-sm"><span class="fa fa-eye"></span></a>'
					);
			},
		},
		
	],
	"columnDefs": [
		{
			"targets": ['_all'],
			"orderable": false,
			"sorting": false
		}
	]
});

// $(document).ready(function() {
// 	$('.remarks_col').hide();
// });
// $(document).on('change', '#appr_status', function(e) {
// 	let select_col = $(this).val();
// 	if (select_col == 2) {
// 		$('.remarks_col').show();
// 	}
// 	else
// 	{
// 		$('.remarks_col').hide();
// 	}
// });


$("#fees_asses_form").bootstrapValidator({
	message: "This value is not valid",
	submitButtons: 'button[type="submit"]',
	submitHandler: function (validator, form, submitButton) {
		$("#asses_submit_btn").attr("disabled", "disabled");
		var formData = new FormData(document.getElementById("fees_asses_form"));
		// formData.set("row_count_array", JSON.stringify(rowCountArray));
		$.ajax({
			url: base_url + "service/fees_assessment_update/UPDATE_FEES_ASSES",
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
						// enable_submit_btn("asses_submit_btn");

						swal({
							title: "Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else if (obj.status === "validationerror") {
						// Enable the submit button
						// enable_submit_btn("asses_submit_btn");

						swal({
							title: "Validation Error",
							text: obj.msg,
							type: "error",
							html: true,
						});
					} else {
						//Reseting user form
						$("#fees_asses_form").trigger("reset");
						toastr.success(obj.msg);
						history.back();

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
		appr_status: {
			validators: {
				notEmpty: {
					message: "Required",
				},
			},
		},
	},
});