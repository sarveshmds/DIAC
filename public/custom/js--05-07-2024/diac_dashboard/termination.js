
// check for the award/termination option
// If selected then enable the award/terminated options according to there selected option
$('#award_term_select').on('change', function(){
	var at = $(this).val();
	$('.award_cols_wrapper').hide();
	$('.other_cols_wrapper').hide();

	if(at == 'award'){
		$('.award_cols_wrapper').show();
	}
	else if(at == 'other'){
		$('.other_cols_wrapper').show();
	}
});

$('#award_term_factsheet').on('change', function(){
	var atf = $(this).val();
	$('.factsheet_cols').hide();

	if(atf == 'yes'){
		$('.factsheet_cols').show();
	}
});

// Function to enable the submit button
function enable_submit_btn(button){
	$('#'+button).attr('disabled',false);
}


// Add case arbitral tribunal list form
$('#award_term_form').bootstrapValidator({
    message: 'This value is not valid',
	submitButtons: 'button[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		$('#award_term_btn_submit').attr('disabled','disabled');
		var formData = new FormData(document.getElementById('award_term_form'));
		var urls = base_url+"service/add_case_operation";

		$.ajax({
			url: urls,
			type: 'POST',
			data: formData,
			contentType: false,
	        processData: false,
			success : function(response){
				// try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	// Enable the submit button
	                	enable_submit_btn('award_term_btn_submit');

	                	swal({
	                		title: 'Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else if(obj.status === 'validationerror'){
	                	// Enable the submit button
	                	enable_submit_btn('award_term_btn_submit');

	                	swal({
	                		title: 'Validation Error',
	                		text: obj.msg,
	                		type: 'error',
	                		html: true
	                	});
	                }else{

	                	// Enable the submit button
	                	enable_submit_btn('award_term_btn_submit');

	                	swal({
	                		title: 'Success',
	                		text: obj.msg,
	                		type: 'success',
	                		html: true
	                	}, function(){
	                		window.location.reload();
	                	});
	                }
	            // } catch (e) {
	            //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
	            // }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
    },
    fields: {
        
    }
})
