<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-header.php') ?>
<main class="page-wrapper">
    <div class="container">

        <div class="card mt-4 bg-white rounded shadow">

            <div class="card-header pt-4 flex-column align-items-start">
                <h5 class="card-title text-danger">Check & Submit</h5>
                <p class="text-lead text-dark mb-0">Verify your details and submit the form</p>
            </div>
            <div class="card-body">
                <div class="mt-0">
                    <div class="card">
                        <div class="card-header bg-warning py-2">
                            <h4 class="card-title text-white">Personal Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Category:</label>
                                        <p><?= isset($personal_information['empanellment_category_name']) ? $personal_information['empanellment_category_name'] : '' ?></p>
                                    </div>
                                </div>
                                <?php if ($personal_information['empanellment_category'] == 'OTHER') : ?>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Please Specify</label>
                                            <p><?= isset($personal_information['specify_other_cat']) ? $personal_information['specify_other_cat'] : '' ?></p>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if ($personal_information['empanellment_category'] == 'ENG') : ?>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Have you been a Goverment Employee?</label>
                                            <p><?= isset($personal_information['is_engineer_govt_employee']) ? $personal_information['is_engineer_govt_employee'] : '' ?></p>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Salutation </label>

                                        <p><?= isset($personal_information['sal_title']) ? $personal_information['sal_title'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">First Name </label>

                                        <p><?= isset($personal_information['first_name']) ? $personal_information['first_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Middle Name </label>

                                        <p><?= isset($personal_information['middle_name']) ? $personal_information['last_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Last Name </label>

                                        <p><?= isset($personal_information['last_name']) ? $personal_information['last_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Email ID:</label>
                                        <p><?= isset($arb_info['email_id']) ? $arb_info['email_id'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Mobile Number: </label>
                                        <p><?= isset($arb_info['phone_number']) ? $arb_info['phone_number'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Alternate Mobile Number</label>
                                        <p><?= (isset($personal_information['alternate_mobile']) && $personal_information['alternate_mobile']) ? $personal_information['alternate_mobile'] : '<span class="badge badge-default">Not provided</span>' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Date of Birth </label>
                                        <p><?= isset($personal_information['dob']) ? $personal_information['dob'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Languages Known</label>

                                        <p><?= isset($personal_information['languages_known']) ? $personal_information['languages_known'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label for="" class="form-label">Nationality</label>

                                        <p><?= isset($personal_information['nationality_name']) ? $personal_information['nationality_name'] : '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Resident Address -->
                    <div class="card mt-3">
                        <div class="card-status-top bg-red"></div>
                        <div class="card-header bg-red py-2">
                            <h4 class="card-title text-white">Resident Address</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 1 </label>
                                        <p><?= isset($personal_information['resident_add_1']) ? $personal_information['resident_add_1'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 2 (Optional) </label>
                                        <p><?= isset($personal_information['resident_add_2']) ? $personal_information['resident_add_2'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Country </label>

                                        <p><?= isset($personal_information['resident_country_name']) ? $personal_information['resident_country_name'] : '' ?></p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">State </label>
                                        <p><?= isset($personal_information['resident_state_name']) ? $personal_information['resident_state_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">City </label>
                                        <p><?= isset($personal_information['resident_city']) ? $personal_information['resident_city'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Pin Code </label>
                                        <p><?= isset($personal_information['resident_pincode']) ? $personal_information['resident_pincode'] : '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Office Address -->
                    <div class="card mt-3">
                        <div class="card-status-top bg-red"></div>
                        <div class="card-header bg-red py-2">
                            <h3 class="card-title text-white">
                                Office Details & Address
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Company/Firm Name:</label>
                                        <p><?= isset($personal_information['office_company_name']) ? $personal_information['office_company_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 1 </label>
                                        <p><?= isset($personal_information['office_add_1']) ? $personal_information['office_add_1'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 2 (Optional) </label>
                                        <p><?= isset($personal_information['office_add_2']) ? $personal_information['office_add_2'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Country </label>
                                        <p><?= isset($personal_information['office_country_name']) ? $personal_information['office_country_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">State </label>

                                        <p><?= isset($personal_information['office_state_name']) ? $personal_information['office_state_name'] : '' ?></p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">City </label>

                                        <p><?= isset($personal_information['office_city']) ? $personal_information['office_city'] : '' ?></p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Pin Code </label>
                                        <p><?= isset($personal_information['office_pincode']) ? $personal_information['office_pincode'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Office Number </label>

                                        <p><?= isset($personal_information['office_phone_number']) ? $personal_information['office_phone_number'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Website </label>

                                        <p><?= isset($personal_information['office_website']) ? $personal_information['office_website'] : '' ?></p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Office Email ID 1</label>

                                        <p><?= isset($personal_information['office_email_1']) ? $personal_information['office_email_1'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Office Email ID 2</label>

                                        <p><?= isset($personal_information['office_email_2']) ? $personal_information['office_email_2'] : '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Correspondance Address -->
                    <div class="card mt-3">
                        <div class="card-status-top bg-red"></div>
                        <div class="card-header bg-red py-2">
                            <h4 class="card-title text-white">Correspondance Address</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 1 </label>
                                        <p><?= isset($personal_information['correspondance_add_1']) ? $personal_information['correspondance_add_1'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Address Line 2 (Optional)</label>
                                        <p><?= isset($personal_information['correspondance_add_2']) ? $personal_information['correspondance_add_2'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Country </label>

                                        <p><?= isset($personal_information['correspondance_country_name']) ? $personal_information['correspondance_country_name'] : '' ?></p>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">State </label>
                                        <?= isset($personal_information['resident_state_name']) ? $personal_information['resident_state_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">City </label>
                                        <?= isset($personal_information['correspondance_city']) ? $personal_information['correspondance_city'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Pin Code </label>

                                        <p><?= isset($personal_information['correspondance_pincode']) ? $personal_information['correspondance_pincode'] : '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="card">
                        <div class="card-header bg-warning py-2">
                            <h4 class="card-title text-white">Professional Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Current Occupation:</label>
                                        <p><?= $professional_information['current_occupation'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Professional History:</label>
                                        <p><?= $professional_information['professional_history'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Academic Qualifications:</label>
                                        <p><?php echo ucfirst(str_replace('_', ' ', $professional_information['academic_qualification'])); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Academic Qualification Details:</label>
                                        <p><?= (isset($professional_information['highest_qualification'])) ? $professional_information['highest_qualification'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Total Experiance in Arbitration:</label>
                                        <p><?= $professional_information['arbitration_experiance'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Any Other Experiance?:</label>
                                        <p><?= $professional_information['other_experiance'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Enter Other Experience Details:</label>
                                        <p><?= $professional_information['other_experiance_details'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label h4">Area of Expertise:</label>
                                        <p><?= $professional_information['expertise_area'] ?></p>
                                    </div>
                                </div>


                                <?php if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'NO'))) : ?>

                                    <?php
                                    $enr_reg_number_text = 'Registration Number';
                                    $enr_reg_date_text = 'Date of Registration';
                                    $enr_reg_org_name_text = 'Name of the Authority';
                                    $enr_reg_year_text = 'Years of Experience';

                                    if ($arb_personal_info['empanellment_category'] == 'ADV') {
                                        $enr_reg_number_text = "Enrolment Number";
                                        $enr_reg_date_text = "Date of Enrolment";
                                        $enr_reg_org_name_text = "Name of Bar Council";
                                        $enr_reg_year_text = "Years of Standing at the bar";
                                    }
                                    ?>

                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4"><?= $enr_reg_number_text ?></label>
                                            <p><?= $professional_information['advocate_enrol_no'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4"><?= $enr_reg_date_text ?></label>
                                            <p><?= $professional_information['advocate_date_of_enroll'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4"><?= $enr_reg_org_name_text ?></label>
                                            <p><?= $professional_information['advocate_name_of_bar_council'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4"><?= $enr_reg_year_text ?></label>
                                            <p><?= $professional_information['advocate_years_standing_at_bar'] ?></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4">Whether having minimum ten years of practice experience as an advocate as on date of application?</label>
                                            <p><?= $professional_information['advocate_have_ten_years_exp'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label h4">Whether appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment?</label>
                                            <p><?= $professional_information['advocate_whether_appearance_before_court'] ?></p>
                                        </div>
                                    </div>


                                    <div class="row py-2">
                                        <fieldset class="fieldset">
                                            <legend class="legend">
                                                Net professional income (only last 2 years)
                                            </legend>
                                            <div class="fieldset-content-box">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label h4">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                                            <p><?= $professional_information['is_itr_declare_for_first_year'] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php if ($professional_information['is_itr_declare_for_first_year'] == 'yes') : ?>
                                                        <div class="col-md-6 col-sm-6 col-12">
                                                            <div class="mb-3 view_col">
                                                                <label class="form-label h4">Enter Amount:</label>
                                                                <p><?= $professional_information['net_prof_income_for_22'] ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label h4">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 1) . '-' . date('Y') ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                                            <p><?= $professional_information['is_itr_declare_for_second_year'] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php if ($professional_information['is_itr_declare_for_second_year'] == 'yes') : ?>
                                                        <div class="col-md-6 col-sm-6 col-12">
                                                            <div class="mb-3 view_col">
                                                                <label class="form-label h4">Enter Amount:</label>
                                                                <p><?= $professional_information['net_prof_income_for_23'] ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                <?php endif ?>

                                <?php if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'YES'))) : ?>

                                    <div class="row py-4">
                                        <fieldset class="fieldset">
                                            <legend class="legend">
                                                Professional Information for Retired Judges, Bureaucrats and Govt. Engineer
                                            </legend>
                                            <div class="fieldset-content-box">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label h4">Date of Superannuation:</label>
                                                            <p><?= $professional_information['retired_judg_bure_date_of_sup_annuation'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label h4">Last Designation Held:</label>
                                                            <p><?= $professional_information['retired_judg_bure_last_designation'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label h4">Name of Department/Court from retired:</label>
                                                            <p><?= $professional_information['retired_judg_bure_department_name'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php endif ?>

                                <div class="row py-4 mb-3" style="border:1px solid lightgrey;">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Any criminal proceeding pending against you?</label>
                                            <p><?= $professional_information['any_criminal_proceeding'] ?></p>
                                        </div>
                                    </div>
                                    <?php if ($professional_information['any_criminal_proceeding'] == 'yes') : ?>
                                        <div class="col-md-8 col-sm-6 col-12">
                                            <div class="mb-3 view_col">
                                                <label class="form-label">Fill Criminal Proceeding Details</label>
                                                <p><?= $professional_information['criminal_proceeding_details'] ?></p>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Whether currently acting as a member of any Tribunal/Authority?</label>
                                            <p><?= $professional_information['is_employed_as_MOT'] ?></p>
                                        </div>
                                    </div>
                                    <?php if ($professional_information['is_employed_as_MOT'] == 'yes') : ?>
                                        <div class="col-md-12 col-sm-12 col-12">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Name of authority:</label>
                                                            <p><?= $professional_information['MOT_auth_name'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Designation:</label>
                                                            <p><?= $professional_information['MOT_designation'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Date of Appointment:</label>
                                                            <p><?= $professional_information['MOT_appoint_date'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Total Tenure:</label>
                                                            <p><?= $professional_information['MOT_total_tenure'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Date of Retirement:</label>
                                                            <p><?= $professional_information['MOT_retire_date'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <div class="row py-4" style="border:1px solid lightgrey;">
                                    <div class="col-md-12 col-sm-12 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Whether empanelled as an arbitrator with any other institution?</label>
                                            <p><?= $professional_information['is_already_empanelled_arb'] ?></p>
                                            <?php if ($professional_information['is_already_empanelled_arb'] == 'yes') : ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr class="bg-dark text-white">
                                                            <th width="15%">S.No.</th>
                                                            <th>Name of Institute:</th>
                                                            <th>Date of Empanellment:</th>
                                                        </tr>
                                                        <?php foreach ($arb_empanel_other as $key => $arb_other) : ?>
                                                            <tr>
                                                                <td><?= $key + 1 ?></td>
                                                                <td> <?= $arb_other['name_of_institute'] ?></td>
                                                                <td> <?= $arb_other['date_of_empanelment'] ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </table>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Number of cases conducted by the applicant as an arbitrator:</label>
                                            <p><?= $professional_information['NOM_as_arb'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Particulars of articles relating to arbitration, if any, published in any journal/book/publication:</label>
                                            <p><?= $professional_information['academic_details_as_arb'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3 view_col">
                                            <label class="form-label">Please share any other information/details which you may like to furnish:</label>
                                            <p><?= $professional_information['prof_other_info'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="card">
                            <div class="card-header bg-warning py-2">
                                <h4 class="card-title text-white">Documents</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3 view_col">
                                    <label class="form-label">CV/Portfolio:</label> -->
                                <?php //if (isset($document_files['cv_portfolio']) && !empty($document_files['cv_portfolio'])) {
                                ?>

                                <!-- <a href="<?= base_url('public/upload/efiling/empanelment/CV_PORTFOLIO/' . $document_files['cv_portfolio']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a> -->

                                <?php //} 
                                ?>
                                <!-- </div>
                            </div> -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Certificate of Enrolment:</label>
                                        <p>
                                            <?php if (!empty($document_files['enrollment_certificate'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/CERT_OF_ENROLLMENT/' . $document_files['enrollment_certificate']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Certificate of Practice:</label>
                                        <p>
                                            <?php if (!empty($document_files['practice_certificate'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/CERT_OF_PRACTICE/' . $document_files['practice_certificate']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">In case the applicant is a retired government employee, a vigilance clearance certificate from the concerned department.:</label>
                                        <p>
                                            <?php if (!empty($document_files['vigilance_clearance'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/VILIGANCE_CLEARANCE/' . $document_files['vigilance_clearance']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Details of orders/judgments w.r.t. appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment. (Copies of the orders/judgments shall be required to be produced as and when required.) [See Rule I(4) DIAC Empanelment Rules, 2020]:</label>
                                        <p>
                                            <?php if (!empty($document_files['conducted_cases'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/LIST_OF_COND_CASES/' . $document_files['conducted_cases']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Copy of complete ITRs for <?= (date('Y') - 1) . '-' . date('Y') ?> years:</label>
                                        <p>
                                            <?php if (!empty($document_files['first_year_itr'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/FIRST_INCOME_TEXT_RETURN/' . $document_files['first_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Copy of complete ITRs for <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years:</label>
                                        <p>
                                            <?php if (!empty($document_files['second_year_itr'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/SECOND_INCOME_TEXT_RETURN/' . $document_files['second_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3 view_col">
                                    <label class="form-label">List of Arbitration matters:</label>
                                    <p>
                                        <?php if (!empty($document_files['arbitration_matters'])) :
                                        ?>
                                            <a href="<?= base_url('public/upload/efiling/empanelment/LIST_OF_ARB_MATTERS/' . $document_files['arbitration_matters']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                        <?php else : ?>
                                            <span class="badge bg-danger text-white">Not Provided</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div> -->
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Copy of articles relating to arbitration published in any book/journal (if any):</label>
                                        <p>
                                            <?php if (!empty($document_files['academic_achievements'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/ACADEMIC_ACHIEVEMENTS/' . $document_files['academic_achievements']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge bg-danger text-white">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Profile Photo:</label>
                                        <div>
                                            <?php if (!empty($document_files['profile_photo'])) :
                                            ?>
                                                <img src="<?= base_url('public/upload/efiling/empanelment/PROFILE/' . $document_files['profile_photo']) ?>" alt="Image" class="w-100 img-thumbnail" />
                                                <div class="text-center mt-2">
                                                    <a href="<?= base_url('public/upload/efiling/empanelment/PROFILE/' . $document_files['profile_photo']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Photo</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2 col-sm-6 col-12">
                                <div class="mb-3 view_col">
                                    <label class="form-label">Signature:</label>
                                    <div>
                                        <?php if (!empty($document_files['signature'])) :
                                        ?>
                                            <img src="<?= base_url('public/upload/efiling/empanelment/SIGNATURE/' . $document_files['signature']) ?>" alt="Image" class="w-100 img-thumbnail" />
                                            <div class="text-center mt-2">
                                                <a href="<?= base_url('public/upload/efiling/empanelment/SIGNATURE/' . $document_files['signature']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Signature</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div> -->
                            </div>
                        </div>
                        <div class="text-center mb-3 mt-3">
                            <form id="emp_final_form" class="emp_final_form" method="POST">
                                <input type="hidden" name="diary_no" id="diary_no" value="<?= $arb_info['diary_number'] ?>">
                                <input type="hidden" name="hidden_id" id="hidden_id" value="<?= $arb_info['id'] ?>">
                                <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('emp_final_form'); ?>">
                                <?php if ($arb_reg_data['step'] >= 3) : ?>
                                    <a href="<?= base_url('efiling/empanellment/documents?id=' . $arb_reg_data['id']) ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Edit Details</a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-custom" id="final_submit"><i class="fa fa-save"></i> Final Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</main>
<?php require_once(APPPATH . '/views/templates/efiling/efiling-auth-footer.php') ?>
<script type="text/javascript" src="<?= base_url('public/custom/js/efiling/empanellment-form-js.js') ?>"></script>