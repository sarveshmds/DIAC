$(document).ready(function($) {
	hideBoxes();

	// 	Function for set Business address as Residential address	
	$("#resident_add_for_office").click(function () {
		if ($("#resident_add_for_office").is(":checked")) {
			$("#office_add_1").val($("#resident_add_1").val());
			$("#office_add_2").val($("#resident_add_2").val());
			$("#office_country").val($("#resident_country").prop("selected", true).val());
			$("#office_state").val($("#resident_state").prop("selected", true).val());
			$("#office_city").val($("#resident_city").prop("selected", true).val());
			$("#office_pincode").val($("#resident_pincode").val());
			
		} else {
			$("#office_add_1").val('');
			$("#office_add_2").val('');
			$("#office_country").val('');
			$("#office_state").val('');
			$("#office_city").val('');
			$("#office_pincode").val('');
		}
	});

	// 	Function for set Correspondance address as Residential address	
	$("#resident_add_for_corr").click(function () {
		if ($("#resident_add_for_corr").is(":checked")) {
			$("#correspondance_add_1").val($("#resident_add_1").val());
			$("#correspondance_add_2").val($("#resident_add_2").val());
			$("#correspondance_country").val($("#resident_country").prop("selected", true).val());
			$("#correspondance_state").val($("#resident_state").prop("selected", true).val());
			$("#correspondance_city").val($("#resident_city").prop("selected", true).val());
			$("#correspondance_pincode").val($("#resident_pincode").val());
			
		} else {
			$("#correspondance_add_1").val('');
			$("#correspondance_add_2").val('');
			$("#correspondance_country").val('');
			$("#correspondance_state").val('');
			$("#correspondance_city").val('');
			$("#correspondance_pincode").val('');
		}
	});

	// 	Function for set Correspondance address as Residential address	
	$("#office_add_for_corr").click(function () {
		if ($("#office_add_for_corr").is(":checked")) {
			$("#correspondace_add_1").val($("#office_add_1").val());
			$("#correspondace_add_2").val($("#office_add_2").val());
			$("#correspondace_country").val($("#office_country").prop("selected", true).val());
			$("#correspondace_state").val($("#office_state").prop("selected", true).val());
			$("#correspondace_city").val($("#office_city").prop("selected", true).val());
			$("#correspondace_pincode").val($("#office_pincode").val());
			
		} else {
			$("#correspondace_add_1").val('');
			$("#correspondace_add_2").val('');
			$("#correspondace_country").val('');
			$("#correspondace_state").val('');
			$("#correspondace_city").val('');
			$("#correspondace_pincode").val('');
		}
	});
});


$(document).on('change','#empanellment_category,.disciplinary_complaint,.member_of_tribual,.empanel_arb_other', function(event) {
	event.preventDefault();
	let empCategoryVal = $(this).val();
	let empCategoryName = $( "#empanellment_category option:selected" ).text();
	$("#professional_emp_cat").val($("#empanellment_category").prop("selected", true).val());

	let disciplinary_complaint = $("input[name='disciplinary_complaint']:checked").val();
	let member_of_tribual = $("input[name='member_of_tribual']:checked").val();
	let empanel_arb_other = $("input[name='empanel_arb_other']:checked").val();
	// console.log(empanel_arb_other)
	// console.log(empCategoryName)

	// Show and hide Former Judges and Bureaucrat Information box
	if (empCategoryVal == 'F_CJI' || empCategoryVal == 'F_CJHC' 
		|| empCategoryVal == 'F_JSCI' || empCategoryVal == 'J_HC' 
		|| empCategoryVal == 'F_DSJ_JM' || empCategoryVal == 'F_AD_SJ'
		|| empCategoryVal == 'F_JM_CAT' 
		|| empCategoryVal == 'RET_BEU' || empCategoryVal == 'PROF'
		)
	{

        $('#former_judges_bureaucrat_row').show();
    }
    else
    {
        $('#former_judges_bureaucrat_row').hide();
    }

    // Show and hide ITR fileds and Documents for Former Judges/Senior Advocate/Professors and Bureaucrat
	if (empCategoryVal == 'F_CJI' || empCategoryVal == 'F_CJHC' 
		|| empCategoryVal == 'F_JSCI' || empCategoryVal == 'J_HC' 
		|| empCategoryVal == 'F_DSJ_JM' || empCategoryVal == 'F_AD_SJ'
		|| empCategoryVal == 'F_JM_CAT' || empCategoryVal == 'S_ADV'
		|| empCategoryVal == 'RET_BEU' || empCategoryVal == 'PROF'
		)
	{

        $('#itr_columns').hide();
        $('#itr_doc_first_col').hide();
		$('#itr_doc_second_col').hide();
    }
    else
    {
        $('#itr_columns').show();
        $('#itr_doc_first_col').show();
		$('#itr_doc_second_col').show();
    }

    // show and hide Advocate Enrollment No.
	if (empCategoryVal == 'ADV' || empCategoryVal=='S_ADV') {
		$('#advocate_enroll_col').show();
	}else{
		$('#advocate_enroll_col').hide();
	}

	// Add column for Engineer is on Govt panel or not 
	if (empCategoryName == 'Engineers') {
		$("#emp_cat_col,#emp_profile_col").removeClass("col-md-6");
		$("#emp_cat_col,#emp_profile_col").addClass("col-md-4");
		$("#engineer_govt_emp_col").css("display", "block");
	}
	else
	{
		$("#emp_cat_col,#emp_profile_col").removeClass("col-md-4");
		$("#emp_cat_col,#emp_profile_col").addClass("col-md-6");
		$("#engineer_govt_emp_col").css("display", "none");
	}

	// for disciplinary/complaint radio button
	if (disciplinary_complaint == 'yes') {
		$('#disciplinary_complaint_details_box').show();
	}else
	{
		$('#disciplinary_complaint_details_box').hide();
	}

	// for member of any Tribunal/Authority/Quasi-Judicial radio button 
	if (member_of_tribual == 'yes') {
		$('#member_of_tribual_box').show();
	}
	else
	{
		$('#member_of_tribual_box').hide();
	}

	// for already empanelled as an Arbitrator radio button
	if (empanel_arb_other == 'yes') {
		$('#empanel_arb_other_box').show();	
	}
	else
	{
		$('#empanel_arb_other_box').hide();
	}
});

$(document).on('change', '#is_engineer_govt_emp', function(event) {
    event.preventDefault();

    // Show and hide ITR fields and Documents for Govt Engineers
    // let empCategoryValue = String($("#empanellment_category").val()); // Convert to string if needed
    let empCategoryName = $( "#empanellment_category option:selected" ).text();
    let engineerGovtEmp = $("#is_engineer_govt_emp").val();

    if (empCategoryName === 'Engineers' && engineerGovtEmp === 'yes') {
        $('#itr_columns').hide();
        $('#itr_doc_first_col').hide();
		$('#itr_doc_second_col').hide();
		$('#former_judges_bureaucrat_row').show();
    } else {
        $('#itr_columns').show();
        $('#itr_doc_first_col').show();
		$('#itr_doc_second_col').show();
		$('#former_judges_bureaucrat_row').hide();
    }
});

$(document).on('click','#empanel_arb_add_more_btn',()=>{
	$("#empanel_arb_other_box").append($("#empanel_arb_other_col").clone());
})



function hideBoxes() {
	$('#disciplinary_complaint_details_box').hide();
	$('#member_of_tribual_box').hide();
	$('#empanel_arb_other_box').hide();	
	$('#former_judges_bureaucrat_row').hide();
	$('#advocate_enroll_col').hide();	
}


// START EMPANELEMENT FORM
$("#start_emp_form").validate({
	errorClass: "is-invalid text-danger",
	rules: {
		email: {
			required: true,
		},

		phone_number: {
			required: true,
		},

		messages: {
			email: "Please enter your email address.",
			phone_number: "Please enter phone number.",
		},
	},
	submitHandler: function (form, event) {
		event.preventDefault();
		var url = base_url + "efiling/empanellment/store-start";
		$.ajax({
			url: url,
			data: new FormData(document.getElementById("start_emp_form")),
			type: "POST",
			contentType: false,
			processData: false,
			cache: false,
			success: function (response) {
				var response = JSON.parse(response);
				if (response.status) {
					Swal.fire({
						icon: "success",
						title: "Success",
						text: response.msg,
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = response.redirect_url;
						}
					});
				} else if (response.status == "validation_error") {
					Swal.fire({
						icon: "error",
						title: "Validation Error",
						text: response.msg,
					});
				} else if (response.status == false) {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: response.msg,
					});
				} else {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: "Server Error",
					});
				}
			},
			error: function (response) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Server Error, please try again",
				});
			},
		});
	},
});


// Store Personal info 
$('#empanelment_personal_info_form').validate({
	errorClass: 'is-invalid text-danger',
	rules: {
		empanellment_category: {
			required: true
		},
		first_name: {
			required: true
		},
		last_name: {
			required: true
		},
		email: {
			required: true,
			email: true
		},
		mobile: {
			required: true,
			number: true,
			minlength: 10,
			maxlength: 10
		},
		dob: {
			required: true
		},
		nationality: {
			required: true
		},
		resident_add_1: {
			required: true
		},
		resident_country: {
			required: true
		},
		resident_state: {
			required: true
		},
		resident_city: {
			required: true
		},
		resident_pincode: {
			required: true
		},
		office_add_1: {
			required: true
		},
		office_country: {
			required: true
		},
		office_state: {
			required: true
		},
		office_city: {
			required: true
		},
		office_pincode: {
			required: true
		},
		office_company_name: {
			required: true
		},
		office_phone_number: {
			required: true
		},
		office_webite: {
			required: true
		},
		correspondace_add_1: {
			required: true
		},
		correspondace_country: {
			required: true
		},
		correspondace_state: {
			required: true
		},
		correspondace_city: {
			required: true
		},
		correspondace_pincode: {
			required: true
		}
	},

	submitHandler: function(form, event) {
		event.preventDefault();

		// Confirmation
		Swal.fire({
			icon: "warning",
			title: "Do you want to submit the form?",
			showCancelButton: true,
			confirmButtonText: "Confirm",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				var url = base_url + "efiling/empanellment/personal-information/store";
				$.ajax({
					url: url,
					data: new FormData(document.getElementById("empanelment_personal_info_form")),
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					success: function(response) {
						var response = JSON.parse(response);
						if (response.status==true) {
							Swal.fire({
								icon: "success",
								title: "Success",
								html: response.msg,
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = base_url + "efiling/empanellment/new-form";
								}
							});
						} else if (response.status == "validation_error") {
							Swal.fire({
								icon: "error",
								title: "Validation Error",
								text: response.msg,
							});
							
						} else if (response.status == false) {
							Swal.fire({
								icon: "error",
								title: "Error",
								html: response.msg,
							});
							
						} else {
							Swal.fire({
								icon: "error",
								title: "Error",
								text: "Server Error",
							});
							
						}
					},
					error: function(response) {
						Swal.fire({
							icon: "error",
							title: "Error",
							text: "Server Error, please try again",
						});
						
					},
				});
			}
		});

	},
})


// Store Professional info 
$('#empanelment_professinal_info_form').validate({
	errorClass: 'is-invalid text-danger',
	rules: {
		professional_emp_cat: {
			required: true
		}
	},

	submitHandler: function(form, event) {
		event.preventDefault();

		// Confirmation
		Swal.fire({
			icon: "warning",
			title: "Do you want to submit the form?",
			showCancelButton: true,
			confirmButtonText: "Confirm",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				var url = base_url + "efiling/empanellment/professional-information/store";
				$.ajax({
					url: url,
					data: new FormData(document.getElementById("empanelment_professinal_info_form")),
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					success: function(response) {
						var response = JSON.parse(response);
						if (response.status==true) {
							Swal.fire({
								icon: "success",
								title: "Success",
								html: response.msg,
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = base_url + "efiling/empanellment/new-form";
								}
							});
						} else if (response.status == "validation_error") {
							Swal.fire({
								icon: "error",
								title: "Validation Error",
								text: response.msg,
							});
							
						} else if (response.status == false) {
							Swal.fire({
								icon: "error",
								title: "Error",
								html: response.msg,
							});
							
						} else {
							Swal.fire({
								icon: "error",
								title: "Error",
								text: "Server Error",
							});
							
						}
					},
					error: function(response) {
						Swal.fire({
							icon: "error",
							title: "Error",
							text: "Server Error, please try again",
						});
						
					},
				});
			}
		});

	},
})

// Store Documents info 
$('#empanelment_documents_form').validate({
	errorClass: 'is-invalid text-danger',
	rules: {
		// professional_emp_cat: {
		// 	required: true
		// }
	},

	submitHandler: function(form, event) {
		event.preventDefault();

		// Confirmation
		Swal.fire({
			icon: "warning",
			title: "Do you want to submit the form?",
			showCancelButton: true,
			confirmButtonText: "Confirm",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				var url = base_url + "efiling/empanellment/documents/store";
				$.ajax({
					url: url,
					data: new FormData(document.getElementById("empanelment_documents_form")),
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					success: function(response) {
						var response = JSON.parse(response);
						if (response.status==true) {
							Swal.fire({
								icon: "success",
								title: "Success",
								html: response.msg,
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = base_url + "efiling/empanellment/new-form";
								}
							});
						} else if (response.status == "validation_error") {
							Swal.fire({
								icon: "error",
								title: "Validation Error",
								text: response.msg,
							});
							
						} else if (response.status == false) {
							Swal.fire({
								icon: "error",
								title: "Error",
								html: response.msg,
							});
							
						} else {
							Swal.fire({
								icon: "error",
								title: "Error",
								text: "Server Error",
							});
							
						}
					},
					error: function(response) {
						Swal.fire({
							icon: "error",
							title: "Error",
							text: "Server Error, please try again",
						});
						
					},
				});
			}
		});

	},
})

