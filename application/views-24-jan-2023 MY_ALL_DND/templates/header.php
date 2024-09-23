<?php
header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keyword" content="<?php echo $title['title_name'] ?>">
	<meta name="description" content="<?php echo $title['title_desc'] ?>">
	<title>DIAC</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('public/custom/photos/logo.png') ?>">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrapValidator.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap-select.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap-multiselect.css" />

	<!--Sweet Alert-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/font-awesome/css/font-awesome.min.css">

	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/Ionicons/css/ionicons.min.css">

	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">

	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/select2/select2.min.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/dist/css/AdminLTE.min.css">

	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/iCheck/all.css" />

	<!-- AdminLTE Skins. Choose a skin from the css/skins
	    folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/dist/css/skins/_all-skins.min.css">

	<!-- Date Picker -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

	<!-- Bootstrap TimePicker -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/timepicker/bootstrap-timepicker.min.css">

	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-daterangepicker/daterangepicker.css">

	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

	<!--Tooltips-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/bower_components/tooltip/tooltipster.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/bower_components/tooltip/tooltipster-punk.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.css" />

	<!-- Summernote -->
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<!-- custom Css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/custom/css/admin.css">


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	<!-- jQuery -->
	<script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- jQuery UI -->
	<script src="<?php echo base_url(); ?>public/bower_components/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button);
	</script>
	<!-- Sweet Alert Plugin -->
	<script src="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>public/plugins/select2/select2.full.min.js"></script>

	<!-- Summernote -->
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrapValidator.js"></script>

	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap-multiselect.js"></script>

	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/bower_components/bootstrap-filestyle/bootstrap-filestyle.js"></script>

	<!-- DataTables -->
	<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

	<!-- daterangepicker -->
	<script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

	<!-- datepicker -->
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

	<!-- Bootstrap WYSIHTML5 -->
	<script src="<?php echo base_url(); ?>public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

	<!-- Slimscroll -->
	<script src="<?php echo base_url(); ?>public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

	<!-- FastClick -->
	<script src="<?php echo base_url(); ?>public/bower_components/fastclick/lib/fastclick.js"></script>

	<!-- AdminLTE App -->
	<script src="<?php echo base_url(); ?>public/dist/js/adminlte.min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/timepicker/bootstrap-timepicker.min.js"></script>

	<!-- Flot -->
	<script src="<?php echo base_url(); ?>public/bower_components/Flot/jquery.flot.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/Flot/jquery.flot.resize.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/Flot/jquery.flot.pie.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/Flot/jquery.flot.categories.js"></script>

	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo base_url(); ?>public/dist/js/demo.js"></script>

	<!-- Tooltips Plugin -->
	<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/tooltip/jquery.tooltipster.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>

	<!-- the fileinput plugin styling CSS file -->
	<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

	<!-- the main fileinput plugin script JS file -->
	<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/fileinput.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.2.7/themes/fa/theme.min.js"></script>

	<!--custom Js Link-->
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/md5_5034.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/profile_sha.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/plugins/js/sha512.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>public/custom/js/common.js"></script>


</head>

<body class="hold-transition skin-blue fixed sidebar-mini" id="root">
	<!-- Site Loader -->
	<div class="site-loader">
		<div class="site-loader-col">
			<div class="site-loader-image">

				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
					<g>
						<circle cx="60" cy="50" r="4" fill="#e15b64">
							<animate attributeName="cx" repeatCount="indefinite" dur="1s" values="95;35" keyTimes="0;1" begin="-0.67s"></animate>
							<animate attributeName="fill-opacity" repeatCount="indefinite" dur="1s" values="0;1;1" keyTimes="0;0.2;1" begin="-0.67s"></animate>
						</circle>
						<circle cx="60" cy="50" r="4" fill="#e15b64">
							<animate attributeName="cx" repeatCount="indefinite" dur="1s" values="95;35" keyTimes="0;1" begin="-0.33s"></animate>
							<animate attributeName="fill-opacity" repeatCount="indefinite" dur="1s" values="0;1;1" keyTimes="0;0.2;1" begin="-0.33s"></animate>
						</circle>
						<circle cx="60" cy="50" r="4" fill="#e15b64">
							<animate attributeName="cx" repeatCount="indefinite" dur="1s" values="95;35" keyTimes="0;1" begin="0s"></animate>
							<animate attributeName="fill-opacity" repeatCount="indefinite" dur="1s" values="0;1;1" keyTimes="0;0.2;1" begin="0s"></animate>
						</circle>
					</g>
					<g transform="translate(-15 0)">
						<path d="M50 50L20 50A30 30 0 0 0 80 50Z" fill="#f8b26a" transform="rotate(90 50 50)"></path>
						<path d="M50 50L20 50A30 30 0 0 0 80 50Z" fill="#f8b26a">
							<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;45 50 50;0 50 50" keyTimes="0;0.5;1"></animateTransform>
						</path>
						<path d="M50 50L20 50A30 30 0 0 1 80 50Z" fill="#f8b26a">
							<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;-45 50 50;0 50 50" keyTimes="0;0.5;1"></animateTransform>
						</path>
					</g>
				</svg>
			</div>
			<h1 class="site-loader-text">PROCESSING...</h1>
		</div>
	</div>
	<div class="wrapper">
		<header class="main-header">
			<a href="#" class="logo">
				<span class="logo-mini"><i class="fa fa-xing" aria-hidden="true"></i></span>
				<span class="logo-lg" style="color: #fff"><b style="color: #fff">DIAC:</b>DELHI</span>
			</a>

			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li>
							<?php $reminders = getTodaysReminders(); ?>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-clock-o"></i>
								<span class="label label-primary"><?= count($reminders) ?></span>
							</a>
						</li>
						<?php if (in_array($this->session->userdata('role'), $this->config->item('notification_check_roles'))) : ?>

							<li class="dropdown notifications-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-bell-o"></i>
									<span class="label label-warning" id="notification_count"></span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<!-- inner menu: contains the actual data -->
										<ul class="menu" id="notification_menu_list">
										</ul>
									</li>
									<li class="footer"><a href="<?= base_url('notifications') ?>"><strong>View all</strong></a></li>
								</ul>
							</li>
						<?php endif; ?>

						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url($this->session->userdata('user_logo')); ?>" class="user-image" alt="User Image">
								<span class="hidden-xs"><?php echo $this->session->userdata('user_display_name') ?></span>
							</a>
							<ul class="dropdown-menu">

								<li class="user-header">
									<img src="<?php echo base_url($this->session->userdata('user_logo')); ?>" class="img-circle" alt="User Image">
									<p>
										<?php echo $this->session->userdata('user_display_name') ?>
										<small><?php echo ($this->session->has_userdata('job_title')) ? $this->session->userdata('job_title') : $this->session->userdata('role_name'); ?></small>
									</p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?php echo site_url('my-account'); ?>" class="btn btn-warning"><i class="fa fa-user-o"></i> My Account</a>
									</div>
									<div class="pull-right">
										<a href="<?php echo site_url('user/logout'); ?>" class="btn btn-danger"><i class="fa fa-power-off"></i> Log out</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>