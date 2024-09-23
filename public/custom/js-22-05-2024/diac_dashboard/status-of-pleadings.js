// Function to enable the submit button
function enable_submit_btn(button){
	$('#'+button).attr('disabled',false);
}


// =======================================================================================
/*
* Funcitons and events for status of pleadings form
*/



// check for the counter claim
// If yes then enable the counter claim other options
$('input[type=radio][name=counter_claim]').on('change', function(){
	var cc = $(this).val();
	if(cc == 'yes'){
		$('.sop_cc_disabled').attr('disabled', false);
	}
	else{
		$('.sop_cc_disabled').attr('disabled', true);
	}
	
});

$('input[type=radio][name=sop_cc_p_of_limitation]').on('change', function(){
	var value = $(this).val();
	if(value == 'yes'){
		$('#sop_cc_no_of_dod').attr('disabled' , false);
	}
	else{
		$('#sop_cc_no_of_dod').attr('disabled' , true);
	}
})

$('input[type=radio][name=sop_app_act]').on('change', function(){
	var value = $(this).val();
	if(value == 'yes'){
		$('.aac_dof_app_disabled').attr('disabled', false);
	}
	else{
		$('.aac_dof_app_disabled').attr('disabled', true);
	}
})

$('input[type=radio][name=sop_app_act_16]').on('change', function(){
	var value = $(this).val();
	if(value == 'yes'){
		$('.aac_dof_app_disabled_16').attr('disabled', false);
	}
	else{
		$('.aac_dof_app_disabled_16').attr('disabled', true);
	}
})

$('input[type=radio][name=sod_p_of_limitation]').on('change', function(){
	var value = $(this).val();
	if(value == 'yes'){
		$('#sod_no_of_dod').attr('disabled', false);
	}
	else{
		$('#sod_no_of_dod').attr('disabled', true);
	}
});


// Status of pleedings form
$('#sop_form').on('submit',function(e){
		e.preventDefault();
		var form_inputs = {};

		form_inputs.csrf_case_form_token = $('input[type="hidden"][name="csrf_case_form_token"]').val();
		form_inputs.op_type = $('#sop_op_type').val();
		form_inputs.hidden_case_no = $('#hidden_case_no').val();

		// Check if the sop id is set
		// If set then form is for edit
		hidden_sop_id = $('#hidden_sop_id').val();
		if(hidden_sop_id){
			form_inputs.hidden_sop_id = hidden_sop_id;
		}

		form_inputs.claim_invited_on = $('#claim_invited_on').val();
		form_inputs.rem_to_claim = $('#rem_to_claim').val();
		form_inputs.rem_to_claim_2 = $('#rem_to_claim_2').val();
		form_inputs.claim_filed_on = $('#claim_filed_on').val();
		form_inputs.res_served_on = $('#res_served_on').val();
		form_inputs.sod_filed_on = $('#sod_filed_on').val();

		form_inputs.sod_p_of_limitation = ($('.sod_p_of_limitation').prop("checked"))? $('input[type=radio][name=sod_p_of_limitation]:checked').val(): '';
		form_inputs.sod_no_of_dod = '';
		if(form_inputs.sod_p_of_limitation == 'yes'){
			form_inputs.sod_no_of_dod = $('#sod_no_of_dod').val();
		}
		
		form_inputs.sop_rejoin_sod_filed_on = $('#sop_rejoin_sod_filed_on').val();
		
		// Counter claims field
		form_inputs.counter_claim = ($('.counter_claim').prop("checked"))? $('input[type=radio][name=counter_claim]:checked').val(): '';
		
		form_inputs.sop_dof_cc = '';
		form_inputs.sop_rt_cc_filed_on = '';
		form_inputs.sop_cc_p_of_limitation = '';
		form_inputs.sop_cc_no_of_dod = '';
		form_inputs.sop_rej_cc_filed_on = '';

		if(form_inputs.counter_claim == 'yes'){
			form_inputs.sop_dof_cc = $('#sop_dof_cc').val();
			form_inputs.sop_rt_cc_filed_on = $('#sop_rt_cc_filed_on').val();
			form_inputs.sop_cc_p_of_limitation = ($('.sop_cc_p_of_limitation').prop("checked"))? $('input[type=radio][name=sop_cc_p_of_limitation]:checked').val(): '';
			form_inputs.sop_cc_no_of_dod = $('#sop_cc_no_of_dod').val();
			form_inputs.sop_rej_cc_filed_on = $('#sop_rej_cc_filed_on').val();
		}
		
		// Applcation act fields
		form_inputs.sop_app_act = ($('.sop_app_act').prop("checked"))? $('input[type=radio][name=sop_app_act]:checked').val(): '';

		form_inputs.sop_dof_app = '';
		form_inputs.sop_rep_app_filed_on = '';
		if(form_inputs.sop_app_act == 'yes'){
			form_inputs.sop_dof_app = $('#sop_dof_app').val();
			form_inputs.sop_rep_app_filed_on = $('#sop_rep_app_filed_on').val();
		}

		// Applcation act 16 fields
		form_inputs.sop_app_act_16 = ($('.sop_app_act_16').prop("checked"))? $('input[type=radio][name=sop_app_act]:checked').val(): '';

		form_inputs.sop_dof_app_16 = '';
		form_inputs.sop_rep_app_filed_on_16 = '';
		if(form_inputs.sop_app_act_16 == 'yes'){
			form_inputs.sop_dof_app_16 = $('#sop_dof_app_16').val();
			form_inputs.sop_rep_app_filed_on_16 = $('#sop_rep_app_filed_on_16').val();
		}
		
		form_inputs.sop_remarks = $('#sop_remarks').val();

		// Disabled the button
		$('#btn_submit').attr('disabled','disabled');
		// Url
		var url = base_url+"service/add_case_operation";

		$.ajax({
			url: url,
			type: 'post',
			data: form_inputs,
			cache: false,
			success : function(response){
				try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	// Enable the submit button
	                	enable_submit_btn('sop_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('sop_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{

						enable_submit_btn('sop_btn_submit');
	                	swal({
	                		title: 'Success',
	                		text: obj.msg,
	                		type: 'success',
	                		html: true
	                	}, function(){
	                		window.location.reload();
	                	});

	                }
	            } catch (e) {
	                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
});