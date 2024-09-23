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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css" />

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

    <!-- Custom Style -->
    <link href="<?= base_url('public/') ?>efiling_assets/custom-style.css" rel="stylesheet" />

    <!--   Core JS Files   -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/popper.min.js"></script>
    <!-- <script src="<?= base_url('public/') ?>efiling_assets/backend/template/core/bootstrap.min.js"></script> -->

    <!-- Tabler Core -->
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/dist/js/tabler.min.js" defer></script>
    <script src="<?= base_url('public/') ?>efiling_assets/backend/template/dist/js/demo.min.js" defer></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--  -->
    <!--  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
    <!-- Toastr -->
    <script type="text/javascript" src="<?php echo base_url(); ?>public/bower_components/toastr/toastr.min.js"></script>
    <!-- Select 2 -->
    <script src="<?php echo base_url(); ?>public/plugins/select2/select2.full.min.js"></script>

    <!-- Custom function helper -->
    <script src="<?php echo base_url(); ?>public/custom/js/constants.js"></script>
    <script src="<?php echo base_url(); ?>public/custom/js/helpers/custom_function.js"></script>

</head>

<body>
    <!-- Site Loader -->
	<div class="site-loader">
		<div class="site-loader-col">
			<div class="site-loader-image">
				<?xml version="1.0" encoding="utf-8"?>
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
					</g><g transform="translate(-15 0)">
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
    
    <div class="page">
        <div class="sticky-top">
            <header class="navbar navbar-expand-md navbar-light sticky-top  d-print-none">
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
                        <!-- <div class="nav-item d-none d-md-flex me-3">
                            <div class="btn-list">
                                <a href="https://github.com/sponsors/codecalm" class="btn btn-custom" target="_blank" rel="noreferrer">
                                    <i class="fas fa-paper-plane"></i>
                                    Website
                                </a>
                            </div>
                        </div> -->
                        <div class="d-none d-md-flex">
                            
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="4" />
                                    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                            </a>
                            <div class="nav-item dropdown d-none d-md-flex me-3">
                                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                    </svg>
                                    <span class="badge bg-red"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Notification</h3>
                                        </div>
                                        <div class="list-group list-group-flush list-group-hoverable">
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Example 1</a>
                                                        <div class="d-block text-muted text-truncate mt-n1">
                                                            Change deprecated html tags to text decoration classes (#29604)
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="list-group-item-actions">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="<?= base_url('efiling/user/profile') ?>" class="nav-link d-flex lh-1 text-reset p-0">
                                <span class="avatar avatar-sm" style="background-image: url(<?= base_url('public/efiling_assets/backend/images/faces/2.jpg') ?>)"></span>
                                <div class="d-none d-xl-block ps-2">
                                    <div><?= $this->session->userdata('user_display_name') ?></div>
                                    <div class="mt-1 small text-muted"><?= $this->session->userdata('job_title') ?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <div class="navbar-expand-md shadow-sm ef_menu">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="navbar navbar-light">
                        <div class="container-xl">

                            <ul class="navbar-nav" data-widget="tree">
                                <?php foreach ($sidebar as $obj) {
                                    $dbmenuid = $obj['menu_id'];
                                    $dblinktext = $obj['menu_name'];
                                    $dblinkurl = $obj['resource_link'];
                                    $dbparentid = $obj['parent_id'];
                                    $dbslno = $obj['sl_no'];
                                    $dbhaschild = $obj['has_child'];
                                    $dbislastchild = $obj['is_last_child'];
                                    $dbiconclass = $obj['icon_class'];
                                    $state = $menu_group == $obj['menu_name'] ? "groupactive active" : "";
                                    $menuitemstate = ($menu_item == $obj['menu_name'] ? "active bold" : "");
                                    $dblinkurl = isset($dblinkurl) ? site_url($dblinkurl) : '#';
                                    if ($dbparentid == 0 && $dbhaschild) {
                                        echo "<li class=\"nav-item  dropdown $state\">			
                                        <a href='" . $dblinkurl . "' class='nav-link dropdown-toggle' data-bs-toggle='dropdown' data-bs-auto-close='outside' role='button' aria-expanded='false'>
                                            <span class='nav-link-icon d-md-none d-lg-inline-block'> <i class=\"$dbiconclass\"></i></span> <span class='nav-link-title'>$dblinktext</span>
                                        </a>
							                <ul class='dropdown-menu'>";
                                    } else if ($dbparentid == 0 && !$dbhaschild) {
                                        echo "<li class='nav-item " . $state . "'>
                                        <a href='" . $dblinkurl . "' class='nav-link'>
                                            <span class='nav-link-icon d-md-none d-lg-inline-block'><i class=\"$dbiconclass\"></i></span> <span class='nav-link-title'>$dblinktext</span>
                                        </a>
						            </li>";
                                    } else if ($dbparentid != 0 && !$dbislastchild) {
                                        echo    "<li class='nav-item " . $menuitemstate . "'>
                                            <a href='" . $dblinkurl . "' class='dropdown-item'>
                                                <span class='nav-link-icon d-md-none d-lg-inline-block'><i class=\"$dbiconclass\"></i></span> <span class='nav-link-title'>$dblinktext</span>
                                            </a>
                                        </li>";
                                    } else if ($dbparentid != 0 && $dbislastchild) {
                                        echo    "<li class='nav-item " . $menuitemstate . "'>
                                            <a href='" . $dblinkurl . "' class='dropdown-item'>
                                                <span class='nav-link-icon d-md-none d-lg-inline-block'><i class=\"$dbiconclass\"></i></span> <span class='nav-link-title'>$dblinktext</span>
                                            </a>
                                        </li>
                                    </ul>
					            </li>";
                                    }
                                } ?>
                                <li class='nav-item'>
                                    <a href='<?= base_url('/efiling/logout') ?>' class='nav-link'>
                                        <span class='nav-link-icon d-md-none d-lg-inline-block'><i class="bi bi-box-arrow-in-right"></i></span> <span class='nav-link-title'>Logout</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="page-wrapper">