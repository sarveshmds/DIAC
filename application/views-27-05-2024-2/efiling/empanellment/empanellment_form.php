<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="page-wrapper">
    <div class="container">
        <div class="card mt-4 mb-4 bg-white rounded shadow">
            <div class="card-header pt-4 flex-column align-items-start">
                <h5 class="card-title text-danger">Application for Empanellment as an Arbitrator.</h5>
                <!-- <p class="text-lead text-dark mb-0">Fill this form for empanellment registration.</p> -->
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3 emp-form-nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <?php if ($current_step == 1) : ?>
                            <button class="nav-link active" id="pills-home-tab">Personal Information</button>
                        <?php else : ?>
                            <a href="<?= base_url('efiling/empanellment/personal-information?id=' . $arb_id) ?>" class="nav-link">Personal Information</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item" role="presentation">
                        <?php if ($current_step == 2) : ?>
                            <button class="nav-link active" id="pills-profile-tab">Professional Information</button>
                        <?php else : ?>
                            <a href="<?= base_url('efiling/empanellment/professional-information?id=' . $arb_id) ?>" class="nav-link">Professional Information</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item" role="presentation">
                        <?php if ($current_step == 3) : ?>
                            <button class="nav-link active" id="pills-contact-tab">Documents</button>
                        <?php else : ?>
                            <a href="<?= base_url('efiling/empanellment/documents?id=' . $arb_id) ?>" class="nav-link">Documents</a>
                        <?php endif; ?>
                    </li>
                </ul>
                <div>

                    <!-- PERSONAL INFORMATION -->
                    <?php if ($current_step == 1) : ?>
                        <div class="card shadow-none border-0">
                            <?php require_once(APPPATH . 'views/efiling/empanellment/forms/personal_details_form.php') ?>
                        </div>
                    <?php endif; ?>


                    <!-- PROFESSIONAL INFORMATION -->
                    <?php if ($current_step == 2) : ?>
                        <div class="card shadow-none border-0">
                            <?php require_once(APPPATH . 'views/efiling/empanellment/forms/professional_details_form.php') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Documents -->
                    <?php if ($current_step == 3) : ?>
                        <div class="card shadow-none border-0">
                            <?php require_once(APPPATH . 'views/efiling/empanellment/forms/documents_form.php') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script type="text/javascript" src="<?= base_url('public/custom/js/efiling/empanellment-form-js.js') ?>"></script>