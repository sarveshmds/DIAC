<form id="emp_professional_info_form" class="emp_professional_info_form" method="POST">
    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_prof_info_form'); ?>">

    <input type="hidden" name="hidden_arb_id" value="<?= $arb_id ?>">

    <?php if (isset($arb_proffessional_info['id']) && !empty($arb_proffessional_info['id'])) : ?>
        <input type="hidden" name="hidden_id" value="<?= $arb_proffessional_info['id'] ?>">
        <input type="hidden" name="form_type" value="EDIT">
    <?php else : ?>
        <input type="hidden" name="form_type" value="ADD">
    <?php endif; ?>

    <input type="hidden" id="emp_cat_type" value="<?= (isset($arb_personal_info) && isset($arb_personal_info['empanellment_category'])) ? $arb_personal_info['empanellment_category'] : ''; ?>">

    <input type="hidden" id="is_engineer_govt_emp" value="<?= isset($arb_personal_info['is_engineer_govt_employee']) ? $arb_personal_info['is_engineer_govt_employee'] : '' ?>">


    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Category:</label>
                        <input type="hidden" name="professional_emp_cat" id="professional_emp_cat" value="<?= $arb_personal_info['empanellment_category'] ?>">
                        <input type="text" placeholder="<?= $arb_personal_info['empanellment_category_name'] ?>" class="form-control" disabled />
                    </div>
                </div>

                <div class="col-12"></div>
                <div class="col-md-4 col-sm-12 col-12">
                    <div class="mb-3">
                        <label class="form-label">Current Occupation</label>
                        <textarea class="form-control" rows="3" name="current_prof_occup" id="current_prof_occup"><?= isset($arb_proffessional_info['current_occupation']) ? $arb_proffessional_info['current_occupation'] : '' ?></textarea>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-12">
                    <div class="mb-3">
                        <label class="form-label">Professional History </label>
                        <textarea class="form-control" rows="3" name="professional_history" id="current_prof_occup" maxlength="1000"><?= isset($arb_proffessional_info['professional_history']) ? $arb_proffessional_info['professional_history'] : '' ?></textarea>
                        <small class="text-danger">
                            (Max 1000 characters length)
                        </small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-12">
                    <div class="mb-3">
                        <label class="form-label">Academic Qualifications</label>

                        <div class="row">
                            <!-- Graduate -->
                            <div class="col-md-6 col-12">
                                <input type="radio" name="academic_qua" id="academic_qua" value="GRADUATE" class="form-check-input mb-2" <?= isset($arb_proffessional_info['academic_qualification']) && $arb_proffessional_info['academic_qualification'] == 'GRADUATE' ? 'checked' : '' ?>>
                                <label for="" class="form-check-lebel">Graduate</label>
                            </div>

                            <!-- Post graduation -->
                            <div class="col-md-6 col-12">
                                <input type="radio" name="academic_qua" id="academic_qua" value="POST_GRADUATE" class="form-check-input mb-2" <?= isset($arb_proffessional_info['academic_qualification']) && $arb_proffessional_info['academic_qualification'] == 'POST_GRADUATE' ? 'checked' : '' ?>>
                                <label for="" class="form-check-lebel">Post Graduate</label>
                            </div>

                            <!-- Doctrate -->
                            <div class="col-md-6 col-12">
                                <input type="radio" name="academic_qua" id="academic_qua" value="DOCTRATE" class="form-check-input mb-2" <?= isset($arb_proffessional_info['academic_qualification']) && $arb_proffessional_info['academic_qualification'] == 'DOCTRATE' ? 'checked' : '' ?>>
                                <label for="" class="form-check-lebel">Doctrate</label>
                            </div>

                            <!-- Other -->
                            <div class="col-md-6 col-12">
                                <input type="radio" name="academic_qua" id="academic_qua" value="OTHER" class="form-check-input mb-2" <?= isset($arb_proffessional_info['academic_qualification']) && $arb_proffessional_info['academic_qualification'] == 'OTHER' ? 'checked' : '' ?>>
                                <label for="" class="form-check-lebel">Other</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-12 col-12 academic_qualification_details_box">
                    <div class="mb-3">
                        <label for="" class="form-label"> Academic Qualification Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" name="highest_quali" id="highest_quali" maxlength="200"><?= isset($arb_proffessional_info['highest_qualification']) ? $arb_proffessional_info['highest_qualification'] : '' ?></textarea>
                        <small class="text-danger">
                            (Max 200 characters length)
                        </small>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Total Experiance in Arbitration</label>
                        <input type="text" class="form-control" name="arb_exp" id="arb_exp" value="<?= isset($arb_proffessional_info['arbitration_experiance']) ? $arb_proffessional_info['arbitration_experiance'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Any Other Experiance?</label>
                        <input type="text" class="form-control" name="other_exp" id="other_exp" value="<?= isset($arb_proffessional_info['other_experiance']) ? $arb_proffessional_info['other_experiance'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Enter Other Experience Details</label>
                        <textarea class="form-control" rows="3" name="other_exp_details" id="other_exp_details"><?= isset($arb_proffessional_info['other_experiance_details']) ? $arb_proffessional_info['other_experiance_details'] : '' ?></textarea>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Area of Expertise</label>
                        <textarea class="form-control" rows="3" name="expertise_area" id="expertise_area"><?= isset($arb_proffessional_info['expertise_area']) ? $arb_proffessional_info['expertise_area'] : '' ?></textarea>
                    </div>
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

                <!-- ENROLMENT/REGISTRATION DETAILS -->
                <div class="row" id="advocate_enroll_col">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label"><?= $enr_reg_number_text ?> <span class="text-danger">*</span></label>
                            <input type="text" name="advocate_enrol_no" id="advocate_enrol_no" class="form-control" value="<?= isset($arb_proffessional_info['advocate_enrol_no']) ? $arb_proffessional_info['advocate_enrol_no'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label"><?= $enr_reg_date_text ?> <span class="text-danger">*</span></label>
                            <input type="date" name="advocate_date_of_enroll" id="advocate_date_of_enroll" class="form-control" value="<?= isset($arb_proffessional_info['advocate_date_of_enroll']) ? $arb_proffessional_info['advocate_date_of_enroll'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label"><?= $enr_reg_org_name_text ?> <span class="text-danger">*</span></label>
                            <input type="text" name="advocate_name_of_bar_council" id="advocate_name_of_bar_council" class="form-control" value="<?= isset($arb_proffessional_info['advocate_name_of_bar_council']) ? $arb_proffessional_info['advocate_name_of_bar_council'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label"><?= $enr_reg_year_text ?> <span class="text-danger">*</span></label>
                            <input type="number" name="advocate_years_standing_at_bar" id="advocate_years_standing_at_bar" class="form-control" value="<?= isset($arb_proffessional_info['advocate_years_standing_at_bar']) ? $arb_proffessional_info['advocate_years_standing_at_bar'] : '' ?>" required maxlength="2" />
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-lg-10 col-md-9 col-12">
                                    <label for="" class="form-label text-justify">Whether having minimum ten years of practice experience as an advocate as on date of application? <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-lg-2 col-md-3 col-12 d-flex flex-row">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input adv_have_ten_year_exp" type="radio" name="adv_have_ten_year_exp" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['advocate_have_ten_years_exp']) && $arb_proffessional_info['advocate_have_ten_years_exp'] == 'yes' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input adv_have_ten_year_exp" type="radio" name="adv_have_ten_year_exp" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['advocate_have_ten_years_exp']) && $arb_proffessional_info['advocate_have_ten_years_exp'] == 'no' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">No</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-lg-10 col-md-9 col-12">
                                    <label for="" class="form-label text-justify">Whether appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment ? <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-2 col-md-3 col-12 d-flex flex-row">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input adv_whether_appearance_before_court" type="radio" name="adv_whether_appearance_before_court" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['advocate_whether_appearance_before_court']) && $arb_proffessional_info['advocate_whether_appearance_before_court'] == 'yes' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input adv_whether_appearance_before_court" type="radio" name="adv_whether_appearance_before_court" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['advocate_whether_appearance_before_court']) && $arb_proffessional_info['advocate_whether_appearance_before_court'] == 'no' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="flexRadioDefault2">No</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ITR - NET PROFESSIONAL INCOME -->
                <div id="itr_columns">
                    <div class="card mt-3">
                        <div class="card-status-top bg-red"></div>
                        <div class="card-header bg-red py-2">
                            <h4 class="card-title text-white">Net professional income (only last 2 years)</h4>
                        </div>
                        <div class="card-body">
                            <!-- ITR - 1 -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-lg-10 col-md-9 col-12">
                                        <label for="" class="form-label">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-12">
                                        <div class="d-flex flex-row">
                                            <div class="form-check mx-3">
                                                <input class="form-check-input net_income_for_22" type="radio" name="net_income_for_22" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['is_itr_declare_for_first_year']) && $arb_proffessional_info['is_itr_declare_for_first_year'] == 'yes' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input net_income_for_22" type="radio" name="net_income_for_22" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['is_itr_declare_for_first_year']) && $arb_proffessional_info['is_itr_declare_for_first_year'] == 'no' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="col-md-6 col-sm-6 col-12" id="net_income_col_for_22">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Enter Amount</label>
                                            <input type="text" class="form-control" id="net_prof_income_for_22" name="net_prof_income_for_22" value="<?= isset($arb_proffessional_info['net_prof_income_for_22']) ? $arb_proffessional_info['net_prof_income_for_22'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ITR - 2 -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-lg-10 col-md-9 col-12">
                                        <label for="" class="form-label">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 1) . '-' . date('Y') ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-12">
                                        <div class="mb-3 d-flex flex-row">
                                            <div class="form-check mx-3">
                                                <input class="form-check-input net_income_for_23" type="radio" name="net_income_for_23" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['is_itr_declare_for_second_year']) && $arb_proffessional_info['is_itr_declare_for_second_year'] == 'yes' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input net_income_for_23" type="radio" name="net_income_for_23" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['is_itr_declare_for_second_year']) && $arb_proffessional_info['is_itr_declare_for_second_year'] == 'no' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="col-md-6 col-sm-6 col-12" id="net_income_col_for_23">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Enter Amount</label>
                                            <input type="text" id="net_prof_income_for_23" class="form-control" name="net_prof_income_for_23" value="<?= isset($arb_proffessional_info['net_prof_income_for_23']) ? $arb_proffessional_info['net_prof_income_for_23'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'YES'))) : ?>

                <!-- PROFESSIONAL INFORMATION FOR RETIRED PERSONS -->
                <div class="card mt-3" id="former_judges_bureaucrat_row">
                    <div class="card-status-top bg-red"></div>
                    <div class="card-header bg-red py-2">
                        <h4 class="card-title text-white">Professional Information for Retired Judges, Bureaucrats and Govt. Engineer</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Date of Superannuation <span class="text-danger"></span></label>
                                    <input type="date" id="retired_judg_bure_date_of_sup_annuation" class="form-control" name="retired_judg_bure_date_of_sup_annuation" value="<?= isset($arb_proffessional_info['retired_judg_bure_date_of_sup_annuation']) ? $arb_proffessional_info['retired_judg_bure_date_of_sup_annuation'] : '' ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Last Designation Held <span class="text-danger"></span></label>
                                    <input type="text" id="retired_judg_bure_last_desig" class="form-control" name="retired_judg_bure_last_desig" value="<?= isset($arb_proffessional_info['retired_judg_bure_last_designation']) ? $arb_proffessional_info['retired_judg_bure_last_designation'] : '' ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Name of Department/Court from retired <span class="text-danger"></span></label>
                                    <input type="text" id="retired_judg_bure_department_name" class="form-control" name="retired_judg_bure_department_name" value="<?= isset($arb_proffessional_info['retired_judg_bure_department_name']) ? $arb_proffessional_info['retired_judg_bure_department_name'] : '' ?>" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CRIMINAL PROCEEDINGS -->
            <div class="row my-4">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="d-flex flex-row">
                        <label class="form-label">Any criminal proceeding pending against you?</label>
                        <div class="form-check mx-3">
                            <input class="form-check-input disciplinary_complaint" type="radio" name="disciplinary_complaint" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['any_criminal_proceeding']) && $arb_proffessional_info['any_criminal_proceeding'] == 'yes' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input disciplinary_complaint" type="radio" name="disciplinary_complaint" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['any_criminal_proceeding']) && $arb_proffessional_info['any_criminal_proceeding'] == 'no' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="flexRadioDefault2">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12 " id="disciplinary_complaint_details_box" <?= isset($arb_proffessional_info['criminal_proceeding_details']) && $arb_proffessional_info['criminal_proceeding_details'] !== '' ? 'style="display: block;"' : 'style="display: none;"' ?>>
                    <div class="mb-3">
                        <label class="form-label">Fill Criminal Proceeding Details <span class="text-danger"></span></label>
                        <textarea class="form-control" rows="3" name="criminal_proc_details" id="criminal_proc_details"><?= isset($arb_proffessional_info['criminal_proceeding_details']) ? $arb_proffessional_info['criminal_proceeding_details'] : '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- CURRENTLY ACTING AS OF ANY TRIBUNAL AUTHORITY -->
            <div class="row">
                <div class="col-12 d-flex flex-row">
                    <label for="" class="form-label">Whether currently acting as a member of any Tribunal/Authority?</label>
                    <div class="form-check mx-3">
                        <input class="form-check-input member_of_tribual" type="radio" name="member_of_tribual" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['is_employed_as_MOT']) && $arb_proffessional_info['is_employed_as_MOT'] == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input member_of_tribual" type="radio" name="member_of_tribual" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['is_employed_as_MOT']) && $arb_proffessional_info['is_employed_as_MOT'] == 'no' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div id="member_of_tribual_box" <?= isset($arb_proffessional_info['MOT_auth_name']) && $arb_proffessional_info['MOT_auth_name'] !== '' ? 'style="display: block;"' : 'style="display: none;"' ?> class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Name of authority <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="MOT_auth_name" id="MOT_auth_name" value="<?= isset($arb_proffessional_info['MOT_auth_name']) ? $arb_proffessional_info['MOT_auth_name'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="MOT_designation" id="MOT_designation" value="<?= isset($arb_proffessional_info['MOT_designation']) ? $arb_proffessional_info['MOT_designation'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Date of Appointment <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="MOT_appoint_date" id="MOT_appoint_date" value="<?= isset($arb_proffessional_info['MOT_appoint_date']) ? $arb_proffessional_info['MOT_appoint_date'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Total Tenure <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="MOT_total_tenure" id="MOT_total_tenure" value="<?= isset($arb_proffessional_info['MOT_total_tenure']) ? $arb_proffessional_info['MOT_total_tenure'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Date of Retirement </label>
                            <input type="date" class="form-control" name="MOT_retire_date" id="MOT_retire_date" value="<?= isset($arb_proffessional_info['MOT_retire_date']) ? $arb_proffessional_info['MOT_retire_date'] : '' ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row py-4">
                <div class="col-12 d-flex flex-row">
                    <label for="" class="form-label">Whether empanelled as an arbitrator with any other institution?</label>
                    <div class="form-check mx-3">
                        <input class="form-check-input empanel_arb_other" type="radio" name="empanel_arb_other" id="flexRadioDefault1" value="yes" <?= isset($arb_proffessional_info['is_already_empanelled_arb']) && $arb_proffessional_info['is_already_empanelled_arb'] == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input empanel_arb_other" type="radio" name="empanel_arb_other" id="flexRadioDefault2" value="no" <?= isset($arb_proffessional_info['is_already_empanelled_arb']) && $arb_proffessional_info['is_already_empanelled_arb'] == 'no' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="card card-body" id="empanel_arb_other_box" <?= isset($arb_proffessional_info['is_already_empanelled_arb']) && $arb_proffessional_info['is_already_empanelled_arb'] == 'yes' ? 'style="display: block;"' : 'style="display: none;"' ?>>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-danger" id="empanel_arb_add_more_btn">Add More</button>
                </div>
                <?php if (isset($arb_proffessional_info['is_already_empanelled_arb']) && $arb_proffessional_info['is_already_empanelled_arb'] == 'yes') : ?>
                    <div class="row mb-3">
                        <div class="col-md-8 col-sm-12 col-12">
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>Name of Institute:</th>
                                    <th>Date of Empanellment:</th>
                                    <th></th>
                                </tr>
                                <?php foreach ($empanel_with_other as $ewo) : ?>
                                    <tr>
                                        <td> <?= $ewo['name_of_institute'] ?></td>
                                        <td> <?= $ewo['date_of_empanelment'] ?></td>
                                        <td><span class="btn btn-sm btn-danger removeOtherInstituteBtn" data-id="<?= $ewo['id'] ?>"><i class="fa fa-trash"></i></span></td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                    </div>
                <?php endif;  ?>
                <div class="row" id="empanel_arb_other_col">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Name of the Institution</label>
                            <input type="text" class="form-control" name="emp_arb_other_NOI[]" id="emp_arb_other_NOI" value="">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Date of Empanellment</label>
                            <input type="date" class="form-control" name="emp_arb_other_DOE[]" id="emp_arb_other_DOE" value="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row py-4">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="mb-3">
                        <!-- <label class="form-label">Number of Arbitration matters conducted by you as an Arbitrator (as sole arbitrator or otherwise)</label> -->
                        <label for="" class="form-label">Number of cases conducted by the applicant as an arbitrator</label>
                        <input type="number" class="form-control" name="NOM_as_arb" value="<?= isset($arb_proffessional_info['NOM_as_arb']) ? $arb_proffessional_info['NOM_as_arb'] : '' ?>">
                    </div>
                </div>

                <div class="col-12"></div>

                <div class="col-md-6 col-sm-6 col-12">
                    <div class="mb-3">
                        <!-- <label class="form-label">Details of Academic Achievements/Publications/Articles in the Area of Arbitration (along with the name of journal/book/publication)</label> -->
                        <label for="" class="form-label">Particulars of articles relating to arbitration, if any, published in any journal/book/publication</label>
                        <textarea class="form-control" rows="4" name="academic_details_as_arb" id="academic_details_as_arb"><?= isset($arb_proffessional_info['academic_details_as_arb']) ? $arb_proffessional_info['academic_details_as_arb'] : '' ?></textarea>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Please share any other information/details which you may like to furnish</label>
                        <textarea class="form-control mt-1" rows="4" name="prof_other_info" id="prof_other_info"><?= isset($arb_proffessional_info['prof_other_info']) ? $arb_proffessional_info['prof_other_info'] : '' ?></textarea>
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
        </div>
    </div>

    <div class="text-center mb-3 mt-3">
        <?php if ($arb_reg_data['step'] >= 1) : ?>
            <a href="<?= base_url('efiling/empanellment/personal-information?id=' . $arb_reg_data['id']) ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Prev Step</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-custom" id="emp_submit">
            <i class="fa fa-save"></i> Save & Next
        </button>
        <?php if ($arb_reg_data['step'] >= 2) : ?>
            <a href="<?= base_url('efiling/empanellment/documents?id=' . $arb_reg_data['id']) ?>" class="btn btn-secondary">Next Step <i class="fa fa-arrow-right"></i> </a>
        <?php endif; ?>
    </div>
</form>