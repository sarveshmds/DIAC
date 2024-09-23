<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="page-wrapper">
    <div class="container">
        <div class="card mt-4 mb-4 bg-white rounded shadow">
            <div class="card-header pt-4 flex-column align-items-start">
                <h5 class="card-title text-danger">Empanellment Form</h5>
                <p class="text-lead text-dark mb-0">Fill this form for empanellment registration.</p>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Personal Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Professional Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Documents</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                        <!-- Personal Information -->
                        <form id="empanelment_personal_info_form" name="empanelment_personal_info_form" method="POST">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_personal_info_form'); ?>">
                            <!-- <input type="hidden" name="hidden_arb_id" value="<?= $arb_id ?>"> -->
                            <!-- <?php if (isset($form_type) && $form_type == 'EDIT') : ?>
                            <input type="hidden" name="hidden_id" value="<?= $arb_personal_info['id'] ?>">
                            <?php endif; ?>
                            <input type="hidden" name="form_type" value="<?= $form_type ?>"> -->
                            <div class="row">
                                <h1>Personal Information</h1>
                                <div class="col-md-6 col-sm-6 col-12" id="emp_cat_col">
                                    <div class="mb-3">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-control valid" name="empanellment_category" id="empanellment_category" aria-label="Empanellment Category" aria-invalid="false">
                                            <option>Select</option>
                                            <?php foreach ($panel_category as $pc) : ?>
                                                <option value="<?= $pc['category_code'] ?>"><?= $pc['category_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12" id="engineer_govt_emp_col" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Have you been a Goverment Employee?</label>
                                        <select class="form-control" name="is_engineer_govt_emp" id="is_engineer_govt_emp">
                                            <option>Select one option</option>
                                            <option value="yes">Retired</option>
                                            <option value="no">Professional</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12" id="emp_profile_col">
                                    <div class="mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" id="last_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="Number" name="mobile" id="mobile" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Alternate Phone Number</label>
                                        <input type="Number" name="alternate_mobile" id="alternate_mobile" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="dob" id="dob" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                        <select class="form-control" id="nationality" name="nationality">
                                            <option>Select one option</option>
                                            <option>India</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-status-top bg-red"></div>
                                <div class="card-header bg-red py-2">
                                    <h4 class="card-title text-white">Residential Address</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="resident_add_1" id="resident_add_1"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2 (Optional) </label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="resident_add_2" id="resident_add_2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                                <select class="form-control" name="resident_country" id="resident_country">
                                                    <option value="">Select country</option>
                                                    <option>India</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <select class="form-control" name="resident_state" id="resident_state">
                                                    <option value="">Select State</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <select class="form-control" name="resident_city" id="resident_city">
                                                    <option value="">Select city</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                                <input type="text" name="resident_pincode" id="resident_pincode" class="form-control" placeholder="Pin Code" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-status-top bg-red"></div>
                                <div class="card-header bg-red py-2">
                                    <h4 class="card-title text-white">Office Address</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-12 d-flex flex-row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="resident_add_for_office">
                                                <label class="form-check-label" for="resident_add_for_office">
                                                    Same as Residential Address
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="office_add_1" id="office_add_1"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2 (Optional) </label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="office_add_2" id="office_add_2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                                <select class="form-control" name="office_country" id="office_country">
                                                    <option value="">Select country</option>
                                                    <option>India</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <select class="form-control" name="office_state" id="office_state">
                                                    <option value="">Select State</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <select class="form-control" name="office_city" id="office_city">
                                                    <option value="">Select city</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                                <input type="text" name="office_pincode" id="office_pincode" class="form-control" placeholder="Pin Code" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Firm/Company Name</label>
                                                <input type="text" name="office_company_name" id="office_company_name" class="form-control" placeholder="Firm/Company name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Office Phone Number</label>
                                                <input type="text" name="office_phone_number" id="office_phone_number" class="form-control" placeholder="Office phone number" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Website</label>
                                                <input type="text" name="office_webite" id="office_webite" class="form-control" placeholder="Website" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Email Address 1 (optional)</label>
                                                <input type="email" name="office_email_1" id="office_email_1" class="form-control" placeholder="Firm/Company name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Email Address 2 (optional)</label>
                                                <input type="email" name="office_email_2" id="office_email_2" class="form-control" placeholder="Firm/Company name" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-status-top bg-red"></div>
                                <div class="card-header bg-red py-2">
                                    <h4 class="card-title text-white">Correspondance Address</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-12 d-flex flex-row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="resident_add_for_corr">
                                                <label class="form-check-label" for="resident_add_for_corr">
                                                    Same as Residential Address
                                                </label>
                                            </div>
                                            <div class="form-check mx-4">
                                                <input class="form-check-input" type="checkbox" value="" id="office_add_for_corr">
                                                <label class="form-check-label" for="office_add_for_corr">
                                                    Same as Bussiness Address
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="correspondance_add_1" id="correspondance_add_1"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2 (Optional) </label>
                                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="correspondance_add_2" id="correspondance_add_2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                                <select class="form-control" name="correspondance_country" id="correspondance_country">
                                                    <option value="">Select country</option>
                                                    <option>India</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <select class="form-control" name="correspondance_state" id="correspondance_state">
                                                    <option value="">Select State</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <select class="form-control" name="correspondance_city" id="correspondance_city">
                                                    <option value="">Select city</option>
                                                    <option>Delhi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                                <input type="text" name="correspondance_pincode" id="correspondance_pincode" class="form-control" placeholder="Pin Code" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="mb-2 d-flex align-items-center justify-content-center">
                                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                        <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <div class="text-start">
                                        <label class="form-label">Captcha</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-danger">Save & Next</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                        <!-- Professional Information -->
                        <form id="empanelment_professinal_info_form" name="empanelment_professinal_info_form" mathod="POST">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_prof_info_form'); ?>">
                            <div class="row">
                                <h1>Professional Information</h1>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Category:</label>
                                        <select class="form-control" name="professional_emp_cat" id="professional_emp_cat" disabled>
                                            <option>Select</option>
                                            <?php foreach ($panel_category as $pc) : ?>
                                                <option value="<?= $pc['category_code'] ?>"><?= $pc['category_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Current Professional Occupation</label>
                                        <textarea class="form-control" rows="3" name="current_prof_occup" id="current_prof_occup"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Highest qualification</label>
                                        <textarea class="form-control" rows="3" name="highest_quali" id="highest_quali"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Total Experiance in Arbitration</label>
                                        <input type="text" class="form-control" name="arb_exp" id="arb_exp">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Any Other Experiance?</label>
                                        <input type="text" class="form-control" name="other_exp" id="other_exp">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Details</label>
                                        <input type="text" class="form-control" placeholder="Please enter details" name="other_exp_details" id="other_exp_details">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Area of Expertise</label>
                                        <textarea class="form-control" rows="3" name="expertise_area" id="expertise_area"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12" id="advocate_enroll_col">
                                    <div class="mb-3">
                                        <label class="form-label">Enrollment Number</label>
                                        <input type="text" name="advocate_enrol_no" id="advocate_enrol_no" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div id="itr_columns">
                                <div class="card mt-3">
                                    <div class="card-status-top bg-red"></div>
                                    <div class="card-header bg-red py-2">
                                        <h4 class="card-title text-white">Net professional income (only last 2 years)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Net professional income for year 2021-22</label>
                                                    <input type="text" class="form-control" id="net_prof_income_for_22" name="">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Net professional income for year 2022-23</label>
                                                    <input type="text" id="net_prof_income_for_23" class="form-control" name="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3" id="former_judges_bureaucrat_row">
                                <div class="card-status-top bg-red"></div>
                                <div class="card-header bg-red py-2">
                                    <h4 class="card-title text-white">Professional Information for Retired Judges, Bureaucrats and Govt. Engineer</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Date of Superannuation:</label>
                                                <input type="date" id="retired_judg_bure_date_of_sup_annuation" class="form-control" name="retired_judg_bure_date_of_sup_annuation">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Last Designation Held</label>
                                                <input type="text" id="retired_judg_bure_last_desig" class="form-control" name="retired_judg_bure_last_desig">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Name of Department/Court from retired</label>
                                                <input type="text" id="retired_judg_bure_department_name" class="form-control" name="retired_judg_bure_department_name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 d-flex flex-row">
                                        <label class="form-label">Any criminal proceeding pending against you?</label>
                                        <div class="form-check mx-3">
                                            <input class="form-check-input disciplinary_complaint" type="radio" name="disciplinary_complaint" id="flexRadioDefault1" value="yes">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input disciplinary_complaint" type="radio" name="disciplinary_complaint" id="flexRadioDefault2" value="no">
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12 " id="disciplinary_complaint_details_box">
                                    <div class="mb-3">
                                        <label class="form-label">Fill Details</label>
                                        <textarea class="form-control" rows="3" name="criminal_proc_details" id="criminal_proc_details"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex flex-row">
                                    <label class="form-label">Are you employed as member of any Tribunal/Authority/Quasi-Judicial body in Goverment or in Autonomous body?</label>
                                    <div class="form-check mx-3">
                                        <input class="form-check-input member_of_tribual" type="radio" name="member_of_tribual" id="flexRadioDefault1" value="yes">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input member_of_tribual" type="radio" name="member_of_tribual" id="flexRadioDefault2" value="no">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="member_of_tribual_box">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name of authority</label>
                                        <input type="text" class="form-control" name="MOT_auth_name" id="MOT_auth_name">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" class="form-control" name="MOT_designation" id="MOT_designation">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Appointment</label>
                                        <input type="date" class="form-control" name="MOT_appoint_date" id="MOT_appoint_date">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Total Tenure</label>
                                        <input type="text" class="form-control" name="MOT_total_tenure" id="MOT_total_tenure">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Retirement</label>
                                        <input type="date" class="form-control" name="MOT_retire_date" id="MOT_retire_date">
                                    </div>
                                </div>
                            </div>
                            <div class="row py-4">
                                <div class="col-12 d-flex flex-row">
                                    <label class="form-label">Are you already empanelled as an Arbitrator with any other Institution?</label>
                                    <div class="form-check mx-3">
                                        <input class="form-check-input empanel_arb_other" type="radio" name="empanel_arb_other" id="flexRadioDefault1" value="yes">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input empanel_arb_other" type="radio" name="empanel_arb_other" id="flexRadioDefault2" value="no">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" id="empanel_arb_other_box">
                                <div class="mb-2">
                                    <button class="btn btn-sm btn-danger" id="empanel_arb_add_more_btn">Add More</button>
                                </div>
                                <div class="row" id="empanel_arb_other_col">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name of the Institution</label>
                                            <input type="text" class="form-control" name="emp_arb_other_NOI" id="emp_arb_other_NOI">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Empanellment</label>
                                            <input type="date" class="form-control" name="emp_arb_other_DOE" id="emp_arb_other_DOE">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-4">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Number of Arbitration matters conducted by you as an Arbitrator (as sole arbitrator or otherwise)</label>
                                        <input type="number" class="form-control" name="NOM_as_arb">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Details of Academic Achievements/Publications/Articles in the Area of Arbitration (along with the name of journal/book/publication)</label>
                                        <textarea class="form-control" rows="4" name="academic_details_as_arb" id="academic_details_as_arb"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Please share any other information/details which you may like to furnish</label>
                                        <textarea class="form-control mt-1" rows="4" name="prof_other_info" id="prof_other_info"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="mb-2 d-flex align-items-center justify-content-center">
                                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                        <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <label class="form-label">Captcha</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-danger">Save & Next</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                        <!-- Documents -->
                        <h1>Documents</h1>
                        <form name="empanelment_documents_form" id="empanelment_documents_form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_document_form'); ?>">
                            <div class="row">
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">CV/Portfolio
                                            <div class="float-end">
                                                <input type="file" name="cv_doc" id="cv_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Certificate of Enrolment/Registration with the relevant institute
                                            <div class="float-end">
                                                <input type="file" name="enrollment_certificate_doc" id="enrollment_certificate_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Highest Educational Qualification Document
                                            <div class="float-end">
                                                <input type="file" name="highest_qualification_doc" id="highest_qualification_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Vigilance Clearance Certificate from the concerned department<br><small class="text-muted">(only applicable for retired goverment employee)</small>
                                            <div class="float-end">
                                                <input type="file" name="vigilance_clearance_doc" id="vigilance_clearance_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">List of cases conducted<br><small class="text-muted">(as an Arbitrator/member of Arbitral Tribunal)</small>
                                            <div class="float-end">
                                                <input type="file" name="conducted_cases_doc" id="conducted_cases_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3" id="itr_doc_first_col">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Income tax returns for the year 2021-22<br><small class="text-muted">(for applicants other than Former Judges, Senior Advocate,Retired Govt. officers and International Arbitrator,Professor)</small>
                                            <div class="float-end">
                                                <input type="file" name="itr_first_doc" id="itr_first_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3" id="itr_doc_second_col">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Income tax returns for the year 2022-23<br><small class="text-muted">(for applicants other than Former Judges, Senior Advocate,Retired Govt. officers and International Arbitrator,Professor)</small>
                                            <div class="float-end">
                                                <input type="file" name="itr_second_doc" id="itr_second_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">List of Arbitration matters<br><small class="text-muted">(Conducted by you before Courts & Tribunal)</small>
                                            <div class="float-end">
                                                <input type="file" name="arb_matters_doc" id="arb_matters_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                                <!-- <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">List of Arbitration-related matters<br><small class="text-muted">(conducted by you before Courts)</small>
                                        <button class="btn btn-danger float-end">Upload Document</button>
                                        </h4>
                                    </div>
                                </div> -->
                                <div class="col-12 bg-light p-2 mb-3">
                                    <div class="mb-2 p-2">
                                        <h4 class="">Details of academic achievements, publication, articles/writings<br><small class="text-muted">(in the area of Arbitration)</small>
                                            <div class="float-end">
                                                <input type="file" name="academic_achieve_doc" id="academic_achieve_doc" class="filestyle" data-btnClass="btn-danger">
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3 d-md-flex gap-5 justify-content-center">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Profile Photo <span class="text-danger">*</span></label>
                                        <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept=".jpg, .jpeg,.png" />
                                        <small class="text-danger d-block mt-2">
                                            Only .jpg, .png, .jpeg (Max Size: 500KB)
                                        </small>
                                    </div>
                                    <div class="">
                                        <label for="" class="form-label">Signature <span class="text-danger">*</span></label>
                                        <input type="file" name="signature" id="signature" class="form-control" accept=".jpg, .jpeg,.png" />
                                        <small class="text-danger d-block mt-2">Only .jpg, .png, .jpeg (Max Size: 500KB)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-6 col-sm-6 col-12 p-4" style="border-left: 1px solid lightgrey;">
                                    <div class="mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="submitted_date" id="submitted_date" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Place</label>
                                        <input type="text" name="submitted_place" id="submitted_place" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="mb-2 d-flex align-items-center justify-content-center">
                                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                        <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <label class="form-label">Captcha</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-danger">Save & Preview</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script type="text/javascript" src="<?= base_url('public/custom/js/efiling/new_empanelment.js') ?>"></script>
<style>
    #sign_btn {
        position: relative;
        overflow: hidden;
    }

    #sign_input {
        position: absolute;
        font-size: 50px;
        opacity: 0;
        right: 0;
        top: 0;
    }
</style>