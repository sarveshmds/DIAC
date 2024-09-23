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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?= base_url('public/') ?>efiling_assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <link href="<?= base_url('public/') ?>efiling_assets/frontend/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url('public/') ?>efiling_assets/frontend/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
    <link href="<?= base_url('public/') ?>efiling_assets/custom-style.css" rel="stylesheet" />
    <style>
        .text-gradient.text-info {
            background-image: linear-gradient(310deg, #f53939 0%, #fbcf33 100%);
        }
    </style>
</head>

<body class="">
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

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid pe-0">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
                            E-filing (DIAC)
                        </a>
                        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon mt-2">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navigation">
                            <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="http://dhcdiac.nic.in/" target="_BLANK">
                                        <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                                        Website
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="http://dhcdiac.nic.in/contact/" target="_BLANK">
                                        <i class="fa fa-user opacity-6 text-dark me-1"></i>
                                        Contact Us
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="#">
                                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                        FAQ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="me-2 btn btn-round mb-0 btn-outline-dark" href="<?= base_url('efiling/register') ?>">
                                        <i class="fa fa-user-plus"></i> Register
                                    </a>
                                </li>
                                <li class="nav-item d-flex align-items-center">
                                    <a class="btn btn-round mb-0 bg-gradient-warning" href="<?= base_url('efiling/login') ?>"><i class="fa fa-sign-in"></i> Login</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
        </div>
    </div>