<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="main-content  mt-0">
    <section class="min-vh-100 mb-5">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('<?= base_url('public/') ?>efiling_assets/frontend/img/curved-images/curved5-small.jpg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">E-filing Registration</h1>
                        <p class="text-lead text-white">Register here for e-filing and viewing your case details.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10">
                <div class="col-xl-10 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <h5>Register with</h5>
                        </div>

                        <div class="card-body">
                            <form id="reg_frm" class="reg_frm" method="POST">
                                <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('frm4'); ?>">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-start">
                                        <div class="form-check mb-3 me-3">
                                            <input class="form-check-input job_title" type="radio" value="Advocate" name="job_title" id="job_title_advocate">
                                            <label class="custom-control-label" for="job_title_advocate">Advocate</label>
                                        </div>
                                        <div class="form-check mb-3 me-3">
                                            <input class="form-check-input job_title" type="radio" name="job_title" value="Party" id="job_title_party">
                                            <label class="custom-control-label" for="job_title_party">Party</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input job_title" type="radio" value="Arbitrator" name="job_title" id="job_title_arbitrator">
                                            <label class="custom-control-label" for="job_title_arbitrator">Arbitrator</label>
                                        </div>
                                        <!-- <div style="margin-left: 10px;" class="form-check mb-3">
                                            <input class="form-check-input job_title" type="radio" name="job_title" value="Advocate & Arbitrator" id="job_title_advocate_arbitrator">
                                            <label class="custom-control-label" for="job_title_advocate_arbitrator">Advocate & Arbitrator</label>
                                        </div> -->

                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label for="">Salutation</label>
                                        <div class="d-flex align-items-start justify-content-start">
                                            <div class="form-check mb-3 me-3">
                                                <input class="form-check-input" type="radio" name="salutation" value="Mr." id="salutation">
                                                <label class="custom-control-label" for="salutation_mr">Mr.</label>
                                            </div>
                                            <div class="form-check mb-3 me-3">
                                                <input class="form-check-input" type="radio" name="salutation" value="Mrs." id="salutation">
                                                <label class="custom-control-label" for="salutation_mrs">Ms.</label>
                                            </div>
                                            <div class="form-check mb-3 me-3">
                                                <input class="form-check-input" type="radio" name="salutation" value="Rather Not Say" id="salutation">
                                                <label class="custom-control-label" for="salutation_mrs">Rather Not Say</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Name" aria-label="Name" aria-describedby="email-addon">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="">Email ID</label>
                                            <input type="text" class="form-control" name="email" placeholder="Email ID" aria-label="Email ID" aria-describedby="email-addon">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="">Mobile Number</label>
                                            <input type="text" class="form-control" name="phone_number" placeholder="Registered Mobile Number" aria-label="Registered Mobile Number" aria-describedby="phone-number-addon">
                                            <small class="text-primary" style="font-size: 11px;">
                                                (To be registered for future use / communication)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="">Alternate Mobile Number</label>
                                            <input type="text" class="form-control" name="alternate_phone_number" id="alternate_phone_number" placeholder="Alternate Mobile Number" aria-label="Alternate Mobile Number" aria-describedby="email-addon">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-12" id="ef_empanellment_no_col" style="display: none;">
                                        <div class="mb-3">
                                            <label for="">Empanelment No.</label>
                                            <input type="text" class="form-control" name="empanellment_no" placeholder="Empanellment No." aria-label="Empanellment No." aria-describedby="email-addon">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-12" id="ef_enrollment_no_col" style="display: none;">
                                        <div class="mb-3">
                                            <label for="">Enrollment No.</label>
                                            <input type="text" class="form-control" name="enrollment_no" placeholder="Enrollment No." aria-label="enrollment No." aria-describedby="email-addon">
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="">Gender</label>
                                            <select class="form-control" aria-label="Gender" aria-describedby="gender-addon">
                                                <option value="">Select</option>
                                                <option value="">Male</option>
                                                <option value="">Female</option>
                                                <option value="">Other</option>
                                            </select>
                                        </div>
                                    </div> -->

                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <fieldset class="fieldset">
                                            <legend class="legend">Correspondance Address</legend>
                                            <div class="fieldset-content-col">

                                                <div class="mb-3">
                                                    <label for="">Address</label>
                                                    <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="address" id="address" aria-describedby="email-addon"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Pin Code</label>
                                                    <input type="text" name="pincode" id="pincode" class="form-control" name="pincode" placeholder="Pin Code" aria-label="Pin Code" aria-describedby="email-addon" />
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <fieldset class="fieldset">
                                            <legend class="legend">Permanent Address</legend>
                                            <div class="fieldset-content-col">
                                                <div class="mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="check-address" type="checkbox" value="" id="check-address">
                                                        <label class="custom-control-label" for="arbitrator_fees_checked">Same as correspondance address</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Address</label>
                                                    <textarea class="form-control" name="permanent_address" id="permanent_address" placeholder="Type Address Here..." aria-label="Type Address Here..." aria-describedby="email-addon"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Pin Code</label>
                                                    <input type="text" class="form-control" name="permanent_address_pincode" id="permanent_address_pincode" placeholder="Pin Code" aria-label="Pin Code" aria-describedby="email-addon" />
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <div class="mb-2 d-flex align-items-center justify-content-center">
                                            <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                            <button class="login-reload-captcha-btn btn bg-gradient-secondary" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                        </div>
                                        <label>Captcha</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-warning my-4 mb-2"><i class="fa fa-user-plus"></i> Register</button>
                                </div>
                                <p class="text-sm mt-3 mb-0">Already have an account? <a type="" href="<?= base_url('efiling/register') ?>" class="text-dark font-weight-bolder"> Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>