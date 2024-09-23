<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="main-content">
    <div class="container">
        <div class="card mt-4 mb-4 bg-white rounded shadow">
            <div class="card-header pt-4 flex-column align-items-start">
                <h5 class="card-title text-danger">Empanelment Registration</h5>
                <p class="text-lead text-dark mb-0">Fill this form for empanellment registration.</p>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div>
                            <form id="start_emp_form" class="start_emp_form" method="POST">
                                <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('start_emp_form'); ?>">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Email ID <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email ID" aria-label="Email ID">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Mobile Number" aria-label="Mobile Number">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="text-center mb-3">
                                            <button type="submit" class="btn btn-warning w-100"><i class="fa fa-paper-plane" id="emp_submit"></i> Submit & Proceed</button>
                                        </div>

                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="mb-1">Apply for Empanelment as an Arbitrator</h2>
                                <p class="fs-3 text-muted">
                                    You can apply in 3 easy steps.
                                </p>
                                <ul class="steps steps-vertical">
                                    <li class="step-item">
                                        <div class="h3 m-0">Step-1 - Personal Information</div>
                                        <div class="text-secondary">You need to enter personal information like Full name, Contact Information, Address etc.</div>
                                    </li>
                                    <li class="step-item">
                                        <div class="h3 m-0">Step-2 - Professional Information</div>
                                        <div class="text-secondary">You need to enter information like Education qualification, Experience etc.</div>
                                    </li>
                                    <li class="step-item">
                                        <div class="h3 m-0">Step-3 - Document Details</div>
                                        <div class="text-secondary">Details like applicant's Signature, Certificates, Educational & Professional Documents etc.</div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</main>


<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>

<script type="text/javascript" src="<?= base_url('public/custom/js/efiling/empanellment-form-js.js') ?>"></script>