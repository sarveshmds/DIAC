<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('public/') ?>efiling_assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= base_url('public/custom/photos/logo.png') ?>">
    <title>
        Efiling - DIAC
    </title>
    <!--     Fonts and icons     -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/') ?>efiling_assets/backend/template/core/bootstrap-icons.css" />

    <!-- Font Awesome Icons -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/fontawesome.js"></script>

    <!-- CSS Files -->
    <link href="<?= base_url('public/') ?>efiling_assets/backend/template/dist/css/tabler.min.css" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/backend/template/dist/css/tabler-flags.min.css" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/backend/template/dist/css/tabler-payments.min.css" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/backend/template/dist/css/tabler-vendors.min.css" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/backend/template/dist/css/demo.min.css" rel="stylesheet" />

    <!-- Toastr -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.css" />

    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/select2/select2.min.css">

    <!-- Bootstrap Datepicker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <link href="<?= base_url('public/') ?>efiling_assets/custom-style.css" rel="stylesheet" />

    <!-- the fileinput plugin styling CSS file -->
    <link href="<?= base_url('public/') ?>efiling_assets/plugins/file-input/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

    <!--   Core JS Files   -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/jquery.min.js"></script>
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/popper.min.js"></script>
    <!-- <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/bootstrap.min.js"></script> -->

    <!-- Tabler Core -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/dist/js/tabler.min.js" defer></script>
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/dist/js/demo.min.js" defer></script>

    <!-- JQUERY VALIDATE  -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/jquery-validate.min.js"></script>

    <!-- SWEETALERT -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/sweetalert2.min.js"></script>

    <!-- Toastr -->
    <script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>

    <!-- Select 2 -->
    <script src="<?php echo base_url(); ?>public/plugins/select2/select2.full.min.js"></script>


    <!-- Custom function helper -->
    <script src="<?php echo base_url(); ?>public/custom/js/constants.js"></script>
    <script src="<?php echo base_url(); ?>public/custom/js/helpers/custom_function.js"></script>

    <!-- Filestyle -->
    <script type="text/javascript" src="<?php echo base_url(); ?>public/efiling_assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js"> </script>


    <style>
        .page-wrapper {
            background-image: linear-gradient(to right, #ffffffdb, #ffffffbf), url('<?= base_url('public/efiling_assets/frontend/img/back.jpg') ?>');
            background-size: 100%;
            min-height: calc(100vh - 160px);
            overflow-y: auto;
            padding-bottom: 53px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* .main-content{
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    } */
    </style>

</head>

<body>
    <!-- Site Loader -->
    <!-- <div class="site-loader">
		<div class="site-loader-col">
			<div class="site-loader-container">
              <span></span>
              <span></span>
              <span></span>
            </div>
		</div>
	</div> -->
    <?php require_once(APPPATH . 'views/templates/components/common/site_loader.php') ?>
    <div class="page">
        <div class="sticky-top">
            <header class="navbar navbar-expand-md navbar-light sticky-top ">
                <div class="container-xl">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                        <a href="#" class="d-flex align-items-center">
                            <img src="<?= base_url('public/custom/photos/logo.png') ?>" class="d_header_logo">
                        </a>
                    </h1>
                    <div class="navbar-nav flex-row order-md-last">

                        <div class="d-none d-md-flex">

                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="4" />
                                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                            </a>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="<?= base_url('efiling/user/profile') ?>" class="nav-link d-flex lh-1 text-reset p-0">
                                <img src="https://cracku.in/latest-govt-jobs/wp-content/uploads/2019/08/delhi-high-court-Logo.jpg" alt="High Court of Delhi" style="height: 80px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <div class="navbar-expand-md shadow-sm ef_menu">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="navbar navbar-light auth-navbar">
                        <div class="container-xl">

                            <ul class="navbar-nav" data-widget="tree">
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fas fa-home text-white"></i></span> <span class='nav-link-title'>Home</span>
                                    </a>
                                </li>
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/login') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fas fa-user text-white"></i></span> <span class='nav-link-title'>Login</span>
                                    </a>
                                </li>
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/register') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fas fa-user-plus text-white"></i></span> <span class='nav-link-title'>Register</span>
                                    </a>
                                </li>

                                <!-- <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/empanellment/start') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fas fa-file text-white"></i></span> <span class='nav-link-title'>Empanellment Form</span>
                                    </a>
                                </li> -->

                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/empanellment/new-start') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fas fa-file text-white"></i></span> <span class='nav-link-title'>Empanellment Form</span>
                                    </a>
                                </li>

                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/fees-calculators') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fa fa-money text-white"></i></span> <span class='nav-link-title'>Fees Calculator</span>
                                    </a>
                                </li>
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/grievance') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fa fa-file-text text-white"></i></span> <span class='nav-link-title'>Grievance Redressal</span>
                                    </a>
                                </li>
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/feedback') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fa fa-commenting text-white"></i></span> <span class='nav-link-title'>Feedback</span>
                                    </a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="fa fa-list text-white"></i></span>
                                        <span class="nav-link-title">
                                            More
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?= base_url('/efiling/apply-internship') ?>">
                                            Apply for internship
                                        </a>
                                        <a class="dropdown-item" href="https://dhcdiac.nic.in/" target="_BLANK">
                                            Website
                                        </a>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="page-wrapper">