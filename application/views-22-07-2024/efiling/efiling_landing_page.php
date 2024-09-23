<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<style>
    .page-wrapper {
        background-image: linear-gradient(to right, #ffffffdb, #ffffffbf), url('<?= base_url('public/efiling_assets/frontend/img/back.jpg') ?>');
        background-size: 100%;
    }
</style>
<main class="main-content mt-0" style="flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="bg-white rounded shadow">
                    <div class="row">
                        <div class="col-md-7 col-12">
                            <div class="py-5 px-5">
                                <h1 class="display-6 fw-bolder text-body-emphasis lh-1 mb-3 text-left">Welcome in DIAC E-filing PORTAL</h1>
                                <p class="lead mx-auto text-justify">E-filing of court cases has revolutionized the legal landscape, streamlining the process and increasing accessibility to justice. With this digital innovation, litigants and legal professionals can submit and manage their cases electronically, reducing paperwork, time, and costs. E-filing offers a convenient platform for submitting documents, tracking case statuses, and receiving notifications, enhancing transparency and efficiency within the judicial system</p>
                                <h3>Steps to file new case:</h3>
                                <ul>
                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                    <li>Proin finibus justo quis ante ultricies sagittis.</li>
                                    <li>Nam non leo molestie, maximus orci quis, rutrum nulla.</li>
                                    <li>Aenean sit amet tortor ut erat rutrum ultrices quis blandit quam.</li>
                                </ul>
                                <div class="text-left">
                                    <a href="<?= base_url('efiling/login') ?>" class="btn btn-warning fs-2 px-4 py-3 me-md-2 rounded-pill shadow-lg"><i class="fas fa-paper-plane"></i> Login Here</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-12">
                            <img src="<?= base_url('public/efiling_assets/frontend/img/1.jpg') ?>" alt="Image" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script>
</script>