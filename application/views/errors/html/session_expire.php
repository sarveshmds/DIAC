<!DOCTYPE html>
<html>
	<head>
  		<meta charset="utf-8">
  		<meta http-equiv="X-UA-Compatible" content="IE=edge">
  		<title>AdminLTE 2 | 500 Error</title>
  		<!-- Tell the browser to be responsive to screen width -->
  		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  		<!-- Bootstrap 3.3.6 -->
  		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  		<!-- Font Awesome -->
  		<link rel="stylesheet" href=href="<?php echo base_url();?>public/bower_components/font-awesome/css/font-awesome.min.css">
  		<!-- Ionicons -->
  		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/Ionicons/css/ionicons.min.css">
  		<!-- Theme style -->
  		<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/AdminLTE.min.css">
  		<!-- AdminLTE Skins. Choose a skin from the css/skins
       	folder instead of downloading all of them to reduce the load. -->
  		<link rel="stylesheet" href="<?php echo base_url();?>public/dist/css/skins/_all-skins.min.css">
	</head>
	<body style="background: linear-gradient(to bottom, #33ccff 0%, #4952fa 100%);">
		<div class="wrapper">
	    	<section class="content" style="padding-top: 120px;">
	      		<div class="error-page">
		        	<h2 class="headline text-red">500</h2>
		        	<div class="error-content" style="padding-top: 30px; color: #fff;font-weight: bold;">
		          		<h3><i class="fa fa-warning text-red"></i> Oops! Session Expired.</h3>
			          	<p>We will work on fixing that right away.Meanwhile, you may <a href="<?php echo base_url()?>login" style="color: red;font-size: 12pt;"><u>Login</u></a> </p>
		        	</div>
	      		</div>
	      	</section>
		</div>
		<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/fastclick/lib/fastclick.js"></script>
		<script src="<?php echo base_url();?>public/dist/js/app.min.js"></script>
		<script src="<?php echo base_url();?>public/dist/js/demo.js"></script>
	</body>
</html>