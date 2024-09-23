<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <?= $page_title ?>
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <!-- Content here -->

        <div class="card">
            <div class="card-body text-center">
                <img src="https://www.freeiconspng.com/uploads/success-icon-10.png" alt="" style="width: 60px;" class="mb-3">
                <h2 class="mb-2">
                    Payment done successfully.
                </h2>
                <p class="mb-0">
                    Your case is pending with DIAC. Please wait for approval.
                </p>
                <p class="mb-2">
                    You will receive confirmation about your case very soon.
                </p>
                <div class="">
                    <a href="<?= base_url('efiling/new-references') ?>" class="btn btn-custom">
                        <i class="fas fa-paper-plane"></i>
                        New References
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>