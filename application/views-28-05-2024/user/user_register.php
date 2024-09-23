<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>User | Register</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('public/custom/photos/logo.png') ?>">

		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrapValidator.css" />
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/Ionicons/css/ionicons.min.css">
	  	<!-- Theme style -->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/AdminLTE.min.css">
	  	<!-- iCheck -->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/plugins/iCheck/square/blue.css">
	  	<!--Bootstrap Date Picker -->
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!--Sweet Alert-->
	  	<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert/sweetalert.css">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
       	<![endif]-->
  		<!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
			<div class="login-logo">
			    <a><b>User</b>Register</a>
			</div>
  			<div class="login-box-body">
    			<?php echo form_open(null, array('class'=>'loginForm','id'=>'user_register_form', 'name'=>'user_register_form' ,'enctype'=>"multipart/form-data")); ?>
			      	<div id="errorlog" style="display: none; color: red; font-size: 9px;"></div>
			      	<input type="hidden" id="hidCsrfToken" name="hidCsrfToken" value="<?php echo generateToken('user_register_form'); ?>">
			      	<div class="form-group has-feedback">
			        	<input type="text" class="form-control" placeholder="First Name" name="txtFirstName" id="txtFirstName" autocomplete="off" required="">
			        	<span class="glyphicon glyphicon-user form-control-feedback"></span>
			      	</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="Last Name" name="txtLastName" id="txtLastName" autocomplete="off">
        				<span class="glyphicon glyphicon-user form-control-feedback"></span>
      				</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="Eg : test@gmail.com" name="txtEmailId" id="txtEmailId" autocomplete="off">
        				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      				</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="Enter your 10 digit mobile Number" name="txtMobileNo" id="txtMobileNo" maxlength="10" autocomplete="off">
        				<span class="glyphicon glyphicon-phone form-control-feedback"></span>
      				</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="select Date of Birth" name="txtDOB" id="txtDOB" autocomplete="off">
        				<span class="glyphicon glyphicon-calendar form-control-feedback"></span>
      				</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="Enter Pan card Number" name="txtPanCard" id="txtPanCard" autocomplete="off">
        				<span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
      				</div>
      				<div class="form-group has-feedback">
        				<input type="text" class="form-control" placeholder="Enter Aadhaar Number" name="txtAadhaarNumber" id="txtAadhaarNumber" autocomplete="off">
        				<span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
      				</div>
      				
                	<div class="clearfix"></div>
      				<div class="row">
        				<div class="col-xs-4">
          					<button type="submit" class="btn btn-primary btn-block btn-flat" id="btnRegister" >Register</button>
        				</div>
      				</div>
    			<?php echo form_close(); ?>
    			<div class="row">
    				<div class="col-xs-12" align="right">Already have an account?  
      					<a href="<?php echo base_url(); ?>login">Sign In</a>
    				</div>
      			</div>
  			</div>
		</div>
    </body>
    <script>base_url = '<?php echo base_url(); ?>';</script>
    <!-- jQuery 3 -->
	<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?php echo base_url();?>public/bower_components/jquery-ui/jquery-ui.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrapValidator.js"></script>
	<!-- iCheck -->
	<script src="<?php echo base_url();?>public/plugins/iCheck/icheck.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="<?php echo base_url();?>public/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Sweet Alert Plugin -->
	<script src="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.min.js"></script>
	<!--custom Js-->
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
			$('#txtDOB').datepicker({
				format: 'dd-mm-yyyy',
			   	todayHighlight: true,
			    autoclose: true
			}).on('changeDate', function (selected) {
		        $('#user_register_form').data('bootstrapValidator').updateStatus('txtDOB', 'VALID', null);
		    });
		});
	  	$(function () {
	    	$('input').iCheck({
	      		checkboxClass: 'icheckbox_square-blue',
	      		radioClass: 'iradio_square-blue',
	      		increaseArea: '20%'
	    	});
	  	});
	  	/*$('#btnReload').click(function(event){
			event.preventDefault();
			$.ajax({
				url:base_url+'user/refresh_captcha',
				dataType: "text",
				type:"POST" , 
				cache:false,
				success:function(data){
					$('#captcha_img').html( '');
					$('#captcha_img').append( data);
				},error:function(data){
					alert("Unable to refresh");
				}	
			});
		});*/
		$('#user_register_form').bootstrapValidator({
			message: 'This value is not valid',
		    feedbackIcons: 
		    {
		        valid: 'glyphicon glyphicon-ok',
		        invalid: 'glyphicon glyphicon-remove',
		        validating: 'glyphicon glyphicon-refresh'
		    },
			submitButtons: 'button[type="submit"]',
			
			submitHandler: function(validator,form,submitButton) 
			{
				$("#btnRegister").html('<i class="fa fa-gear fa-spin"></i> Loading...');
				txtUserName = $('#txtEmailId').val();
				txtpassword = 'password';
				var encSaltSHAPass = encryptShaPassCode(txtUserName,txtpassword);
				var formData = new FormData(document.getElementById("user_register_form"));
					formData.append('secreatecode', encSaltSHAPass);
					formData.append('user_id', txtUserName);
				urls =base_url+"user_service/user_register";
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
			                	$('#errorlog').html('');
			                	$('#errorlog').hide();
			                	$("#btnRegister").html("Register");
			                    sweetAlert("USER",obj.msg, "error");
			                }else if(obj.status === 'validationerror'){
			                	$('#errorlog').html(obj.msg);
			                	$('#errorlog').show();
			                	$("#btnRegister").html("Register");
			                } else {
			                	sweetAlert("USER",obj.msg, "success");
			                	$("#btnRegister").html("Register");
			                	$('#errorlog').html('');
			                	$('#errorlog').hide();
								$('#user_register_form').data('bootstrapValidator').resetForm(true);//Reseting user form
			                }
			            } catch (e) {
			                sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
			            }
					},error: function(err){
						toastr.error("unable to save");
					}
				});
			},
			//live: 'enabled',
		    fields:
		    {
		        txtFirstName: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                }
		            }
		        },
		        txtLastName: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                }
		            }
		        },
		        txtEmailId: {							//form input type name
		            validators: {
		            	notEmpty: {
		                    message: 'Required'
		                },
		                emailAddress: {
		                    message: 'The value is not a valid email address'
		                }
		            }
		        },
		        txtMobileNo: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Mobile Number Required'
		                },
		                regexp: {
	                        regexp: /^[1-9][0-9]{0,9}$/,
	                        message: "Invalid Mobile Number"
	            		}
		            }
		        },
		        txtDOB: {
	                validators: {
		                notEmpty: {
		                    message: 'Required'
		                },
		                date: {
	      					format: 'dd-mm-yyyy',
	                        message: 'The date is not valid'
	                    }
		            }
	            },
		        txtPanCard: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                }
		            }
		        },
		        txtAadhaarNumber: {							//form input type name
		            validators: {
		                notEmpty: {
		                    message: 'Required'
		                }
		            }
		        }
			}	
		});
	</script>
</html>
