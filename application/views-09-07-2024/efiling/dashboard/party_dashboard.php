<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Dashboard
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card card-md mb-3">
            <div class="card-stamp card-stamp-lg">
                <div class="card-stamp-icon bg-primary">
                    <!-- Download SVG icon from http://tabler-icons.io/i/ghost -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 11a7 7 0 0 1 14 0v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-7"></path>
                        <line x1="10" y1="10" x2="10.01" y2="10"></line>
                        <line x1="14" y1="10" x2="14.01" y2="10"></line>
                        <path d="M10 14a3.5 3.5 0 0 0 4 0"></path>
                    </svg>
                </div>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h3 class="h1">Welcome To DIAC E-Filing Portal</h3>
                        <div class="markdown text-muted">
                            <p class="mb-1">
                                You can file new reference by clicking the below button.
                            </p>
                            <p>
                                For any query regarding filing new reference <a href="#">Click Here</a>
                            </p>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('efiling/file-new-case') ?>" class="btn btn-custom" rel="noopener">File New Reference <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-status-start bg-danger"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= $dashboard_data['total_documents_count'] ?>
                                </div>
                                <div class="text-dark fs-3">
                                    Documents & Pleadings
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-status-start bg-success"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= $dashboard_data['total_applications_count'] ?>
                                </div>
                                <div class="text-dark fs-3">
                                    Applications
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-status-start bg-info"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= $dashboard_data['total_new_reference_count'] ?>
                                </div>
                                <div class="text-dark fs-3">
                                    New Reference
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-status-start bg-primary"></div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= $dashboard_data['total_requests_count'] ?>
                                </div>
                                <div class="text-dark fs-3">
                                    Requests
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>