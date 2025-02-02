
$('#btn_dept_edit').click(function(){
	$('#frm_profile_modification').data('bootstrapValidator').resetForm(true);//Reseting user form
	var user_code = $(this).attr('data-user_code');
	$.ajax({
		url:base_url+"service/get_account_details/"+user_code,
		type:"POST",
		async:false,
		success:function(response){
			try{
				var res = JSON.parse(response);
				$("#dept_modification_modal").modal();
				$('#hid_user_code').val(res[0].user_code);
				$('#dept_desc').val(res[0].primary_role);
				$('#txt_dept_address').val(res[0].address);
				$('#txt_email').val(res[0].email);
				$('#txt_contact').val(res[0].phone_number);
			}catch(e){
				sweetAlert("Sorry",'Unable to Get Data.Please Try Again !', "error");
			}
		},error:function(){
		   toastr.error('Unable to process please contact support');
		}
 	}); 
});

$('#frm_profile_modification').bootstrapValidator({
	message: 'This value is not valid',
	submitButtons: 'input[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		var formData = new FormData(document.getElementById("frm_profile_modification"));
		urls =base_url+"service/operation_profile_details";
		$.ajax({
			url : urls,
			method : 'POST',
			data:formData,
			cache: false,
	        contentType: false,
	        processData: false,
			success : function(response)
			{
				try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	$('#errorlog_dept').html('');
	                	$('#errorlog_dept').hide();
	                    sweetAlert("USER",obj.msg, "error");
	                }else if(obj.status === 'validationerror'){
	                	$('#errorlog_dept').html(obj.msg);
	                	$('#errorlog_dept').show();
	                } else {
	                	sweetAlert("USER",obj.msg, "success");
	                	$('#errorlog_dept').html('');
	                	$('#errorlog_dept').hide();
						$('#frm_profile_modification').data('bootstrapValidator').resetForm(true);//Reseting user form
						document.getElementById("frm_profile_modification").reset();
						location.reload();
	                }
	            } catch (e) {
	                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	},
	fields:{
        txt_email: {							
            validators: {
            	notEmpty: {
                    message: 'email address is required'
                },
                emailAddress: {
                    message: 'The value is not a valid email address'
                }
            }
        },
        txt_contact: {							
            validators: {
                notEmpty: {
                    message: 'phone number is required'
                },
                regexp: {
                    regexp: /^[798]\d{9}$/,
                    message: 'The value is not a valid phone number'
                }
            }
        },
        txt_Logo: {
            validators: {
                file: {
                    extension: 'jpeg,png,jpg',
                    type: 'image/jpeg,image/png,image/jpg',
                    maxSize: 2048 * 1024,
                    message: 'The selected file is not valid'
                }
            }
        }
	}	
});	
$('#btn_change_password').click(function(){
	$('#frm_change_password').data('bootstrapValidator').resetForm(true);//Reseting user form
	$('#hid_user_code_cp').val($(this).attr('data-user_code'));
	$('#hid_user_name').val($(this).attr('data-user_name'));
	$("#change_password_modal").modal();
});
$('#frm_change_password').bootstrapValidator({
	message: 'This value is not valid',
	submitButtons: 'input[type="submit"]',
	submitHandler: function(validator, form, submitButton) {
		var encSaltSHAOldPass = encryptShaPassCode($('#hid_user_name').val(),$('#txt_old_password').val());
		var encSaltSHAConPass = encryptShaPassCode($('#hid_user_name').val(),$('#txt_confrim_password').val());

		var encTxtOldPassword = encryptShaPassCode($('#hid_user_name').val(),$('#txt_old_password').val());

		var encTxtNewPassword = encryptShaPassCode($('#hid_user_name').val(),$('#txt_new_password').val());

		var encTxtCofirmPassword = encryptShaPassCode($('#hid_user_name').val(),$('#txt_confrim_password').val());

		$('#hid_old_password').val(encSaltSHAOldPass);
		$('#hid_password').val(encSaltSHAConPass);
		
		var formData = new FormData(document.getElementById("frm_change_password"));
		formData.set('txt_old_password', encTxtOldPassword);
		formData.set('txt_new_password', encTxtNewPassword);
		formData.set('txt_confrim_password', encTxtCofirmPassword);

		urls =base_url+"/service/operation_change_password";
		$.ajax({
			url : urls,
			method : 'POST',
			data:formData,
			cache: false,
	        contentType: false,
	        processData: false,
			success : function(response)
			{
				try {
	                var obj = JSON.parse(response);
	                if (obj.status == false) {
	                	$('#errorlog_cp').html('');
	                	$('#errorlog_cp').hide();
	                    sweetAlert("USER",obj.msg, "error");
	                }else if(obj.status === 'validationerror'){
	                	$('#errorlog_cp').html(obj.msg);
	                	$('#errorlog_cp').show();
	                } else if(obj.status === 101){
						$('#errorlog_cp').html(obj.msg);
	                	$('#errorlog_cp').show();
					}else{
	                	sweetAlert("USER",obj.msg, "success");
	                	$('#errorlog_cp').html('');
	                	$('#errorlog_cp').hide();
						$('#frm_change_password').data('bootstrapValidator').resetForm(true);//Reseting user form
						location.reload();
	                }
	            } catch (e) {
	                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
	            }
			},error: function(err){
				toastr.error("unable to save");
			}
		});
	},
	fields:{
		txt_old_password: {							
            validators: {
            	notEmpty: {
                    message: 'Enter Old Password'
                }
            }
        },
		txt_new_password: {
	        validators: {
	        	notEmpty: {
                    message: 'The password is required and cannot be empty'
                },
                stringLength: {
		           min: 8,
		           max: 25,
		           message: 'Password Should be between 8 to 25 characters'
        		},
	        	callback: {
	               message: 'Password should contain at least 1 upper case,1 lower case,1 number',
	               callback: function(value, validator, $field) {
	                   	if (value === '') {
	                       return true;
	                   	}
	                   	// Check the password strength
	                   	if (value.length < 8) {
	                       return false;
	                   	}
	                   	// The password doesn't contain any uppercase character
	                   	if (value === value.toLowerCase()) {
	                       return false;
	                   	}
	                   	// The password doesn't contain any uppercase character
	                   	if (value === value.toUpperCase()) {
	                       return false;
	                   	}
	                   	// The password doesn't contain any digit
	                   	if (value.search(/[0-9]/) < 0) {
	                       return false;
	                   	}
	                   	return true;
	               	}
	        	},
	        	callback:{
	        		message: 'Password should not contain username',
	        		callback: function(value, validator, $field){
	        			if(value.indexOf($('#hid_user_name').val()) !== -1){
	        				return false;
	        			}
	        			else{
	        				return true;
	        			}
	        		}
	        	},
	            identical: {
	                field: 'txt_confrim_password',
	                message: 'The password and its confirm must be the same'
	            }
	        }
	    },
        txt_confrim_password: {
            validators: {
            	notEmpty: {
                    message: 'The confirm password is required and cannot be empty'
                },
                callback:{
	        		message: 'Password should not contain username',
	        		callback: function(value, validator, $field){
	        			if(value.indexOf($('#hid_user_name').val()) !== -1){
	        				return false;
	        			}
	        			else{
	        				return true;
	        			}
	        		}
	        	},
                identical: {
                    field: 'txt_new_password',
                    message: 'The password and its confirm must be the same'
                }
                
            }
        }
	}
});
