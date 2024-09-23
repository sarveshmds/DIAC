<?php
	header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Reset Password</title>
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>public/upload/title/Reset-Password.png">
		<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  	
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrapValidator.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/Ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/AdminLTE.min.css">
		<!--Sweet Alert-->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert/sweetalert.css">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
		    	<a><b>Reset</b>Password</a>
			</div>
        	<div class="form login-form login-box-body" id="login">
        		<?php echo form_open(null, array('id'=>'resetPasswordForm', 'name'=>'resetPasswordForm' ,'enctype'=>"multipart/form-data")); ?>
                	<input id="txtUserCode" name="txtUserCode" type="hidden"  value="<?php echo $user_code ?>">
					<input id="txtUserName" name="txtUserName" type="hidden" value="<?php echo $user_name ?>">
                	<div class="form-group has-feedback"> 
	                   	<input type="password" id="txtPass" name="txtPass" class="form-control" placeholder="enter new password"  autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />
		        		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                	</div>
                	<div class="form-group has-feedback"> 
	                    <input name="txtConPassword" type="password" id="txtConPassword" class="form-control" placeholder="enter new confirm password"  autocomplete="off" oncopy="return false" onpaste="return false" oncut="return false" />
		        		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                	</div>
                	<div class="clearfix"></div>
                	<div class="row">
                    	<div class="col-md-4" style="float: right;">
                    		<button type="submit" name="btnSignIn"  id="btnSignIn" class="btn btn-success" ><i class="fa  fa-send fa-lg"> </i>&nbsp;Submit</button>
                    	</div>
                	</div>
                	<div class="clearfix"></div>
            	<?php echo form_close();?>
        	</div>
    	</div>
		<script> base_url = "<?php echo base_url(); ?>"; </script>
		<!-- jQuery 3 -->
		<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrapValidator.js"></script>
		<!-- Sweet Alert Plugin -->
		<script src="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/md5_5034.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/profile_sha.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/sha512.js"></script>
		<script>
			document.onkeydown = function(e) {
			    if(e.keyCode == 123) {
			     	return false;
			    }
			    if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
			     	return false;
			    }
			    if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
			     	return false;
			    }
			    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
			     	return false;
			    }
			    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
			     	return false;
			    }      
	 		}
	 		$(document).ready( function() {
			    $('body').bind('cut copy paste', function (e) {
			        e.preventDefault();
			    });
			    $("body").on("contextmenu",function(e){
			        return false;
			    });
			});
		    $('#resetPasswordForm').bootstrapValidator({
				message: 'This value is not valid',
				submitButtons: 'button[type="submit"]',
				submitHandler: function(validator, form, submitButton) {
					txtUserName = $('#txtUserName').val();
					txtpassword = $('#txtPass').val();
					mob_password = $('#txtPass').val();
					txtConPassword = $('#txtConPassword').val();
					txtUserCode = $('#txtUserCode').val();

					var encSaltSHAPass = encryptShaPassCode(txtUserName,txtpassword);
					var encSaltSHAConPass = encryptShaPassCode(txtUserName,txtConPassword);
					var encMobPassword = encryptShaPassCode(txtUserName,mob_password);

					$('#txtPass').val(encSaltSHAPass);
					$('#txtConPassword').val(encSaltSHAConPass);
					
					var formData = new FormData(document.getElementById("resetPasswordForm"));
					
					formData.append('txtUserCode', txtUserCode);
					formData.append('mob_password', encMobPassword);
					
					urls =base_url+"user_service/reset_password";
					$.ajax({
						url : urls,
						method : 'POST',
						data:formData,
						cache: false,
				        contentType: false,
				        processData: false,
						success : function(response){
							try{
				                var obj = JSON.parse(response);
				                if (obj.status == false) {
				                    sweetAlert("USER",obj.msg, "error");
				                }else if(obj.status === 'validationerror'){
				                	$('#errorlog').html(obj.msg);
				                	$('#errorlog').show();
	                			}else{
				                	sweetAlert("USER",obj.msg, "success");
				                	document.getElementById("resetPasswordForm").reset();
				                	window.location.href = base_url+"login";
				                }
				            } catch (e) {
				                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
				            }
						},error: function(err){
							sweetAlert("USER",err, "error");
						}
					});
					
				},
				fields:{
					txtPass: {
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
				        			if(value.indexOf($('#txtUserName').val()) !== -1){
				        				return false;
				        			}
				        			else{
				        				return true;
				        			}
				        		}
				        	},
				            identical: {
				                field: 'txtConPassword',
				                message: 'The password and its confirm must be the same'
				            }
				        }
				    },
			        txtConPassword: {
			            validators: {
			            	notEmpty: {
			                    message: 'The confirm password is required and cannot be empty'
			                },
			                identical: {
			                    field: 'txtPass',
			                    message: 'The password and its confirm must be the same'
			                },
			                callback:{
				        		message: 'Password should not contain username',
				        		callback: function(value, validator, $field){
				        			if(value.indexOf($('#txtUserName').val()) !== -1){
				        				return false;
				        			}
				        			else{
				        				return true;
				        			}
				        		}
				        	}
			            }
			        }
				}
		    });
		</script>
	</body>
</html>
