<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $page_title ?>

        </h1>
        <!-- <a href="<?= base_url('arbitrator-empanelment'); ?>" class="btn btn-default float-end"><span class="fa fa-backward"></span> Go Back</a> -->
    </section>
    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Professional Information</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Documents</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Category:</label>
                                        <p><?= isset($personal_information['empanellment_category_name']) ? $personal_information['empanellment_category_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Salutation </label>

                                        <p><?= isset($personal_information['sal_title']) ? $personal_information['sal_title'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">First Name </label>

                                        <p><?= isset($personal_information['first_name']) ? $personal_information['first_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Middle Name </label>

                                        <p><?= isset($personal_information['middle_name']) ? $personal_information['last_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Last Name </label>

                                        <p><?= isset($personal_information['last_name']) ? $personal_information['last_name'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Email ID:</label>
                                        <p><?= isset($arb_info['email_id']) ? $arb_info['email_id'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Mobile Number: </label>
                                        <p><?= isset($arb_info['phone_number']) ? $arb_info['phone_number'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Alternate Mobile Number</label>
                                        <p><?= (isset($personal_information['alternate_mobile']) && $personal_information['alternate_mobile']) ? $personal_information['alternate_mobile'] : '<span class="badge badge-default">Not provided</span>' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Date of Birth </label>
                                        <p><?= isset($personal_information['dob']) ? $personal_information['dob'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Languages Known</label>

                                        <p><?= isset($personal_information['languages_known']) ? $personal_information['languages_known'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label for="" class="form-label">Nationality</label>

                                        <p><?= isset($personal_information['nationality_name']) ? $personal_information['nationality_name'] : '' ?></p>
                                    </div>
                                </div>


                                <div class="col-xs-12">
                                    <!-- Resident Address -->
                                    <div class="card form-group">
                                        <div class="card-header bg-red ">
                                            <h4 class="card-title ">Resident Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 1 </label>
                                                        <p><?= isset($personal_information['resident_add_1']) ? $personal_information['resident_add_1'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 2 (Optional) </label>
                                                        <p><?= isset($personal_information['resident_add_2']) ? $personal_information['resident_add_2'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Country </label>

                                                        <p><?= isset($personal_information['resident_country_name']) ? $personal_information['resident_country_name'] : '' ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">State </label>
                                                        <p><?= isset($personal_information['resident_state_name']) ? $personal_information['resident_state_name'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">City </label>
                                                        <p><?= isset($personal_information['resident_city']) ? $personal_information['resident_city'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Pin Code </label>
                                                        <p><?= isset($personal_information['resident_pincode']) ? $personal_information['resident_pincode'] : '' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Office Address -->
                                    <div class="card form-group">
                                        <div class="card-header bg-red ">
                                            <h3 class="card-title">
                                                Office Details & Address
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Company/Firm Name:</label>
                                                        <p><?= isset($personal_information['office_company_name']) ? $personal_information['office_company_name'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 1 </label>
                                                        <p><?= isset($personal_information['office_add_1']) ? $personal_information['office_add_1'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 2 (Optional) </label>
                                                        <p><?= isset($personal_information['office_add_2']) ? $personal_information['office_add_2'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Country </label>
                                                        <p><?= isset($personal_information['office_country_name']) ? $personal_information['office_country_name'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">State </label>

                                                        <p><?= isset($personal_information['office_state_name']) ? $personal_information['office_state_name'] : '' ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">City </label>

                                                        <p><?= isset($personal_information['office_city']) ? $personal_information['office_city'] : '' ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Pin Code </label>
                                                        <p><?= isset($personal_information['office_pincode']) ? $personal_information['office_pincode'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Office Number </label>

                                                        <p><?= isset($personal_information['office_phone_number']) ? $personal_information['office_phone_number'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Website </label>

                                                        <p><?= isset($personal_information['office_website']) ? $personal_information['office_website'] : '' ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Office Email ID 1</label>

                                                        <p><?= isset($personal_information['office_email_1']) ? $personal_information['office_email_1'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Office Email ID 2</label>

                                                        <p><?= isset($personal_information['office_email_2']) ? $personal_information['office_email_2'] : '' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Correspondance Address -->
                                    <div class="card form-group">
                                        <div class="card-status-top bg-red"></div>
                                        <div class="card-header bg-red ">
                                            <h4 class="card-title ">Correspondance Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 1 </label>
                                                        <p><?= isset($personal_information['correspondance_add_1']) ? $personal_information['correspondance_add_1'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Address Line 2 (Optional)</label>
                                                        <p><?= isset($personal_information['correspondance_add_2']) ? $personal_information['correspondance_add_2'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Country </label>

                                                        <p><?= isset($personal_information['correspondance_country_name']) ? $personal_information['correspondance_country_name'] : '' ?></p>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">State </label>
                                                        <?= isset($personal_information['resident_state_name']) ? $personal_information['resident_state_name'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">City </label>
                                                        <?= isset($personal_information['correspondance_city']) ? $personal_information['correspondance_city'] : '' ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group view_col">
                                                        <label class="form-label">Pin Code </label>

                                                        <p><?= isset($personal_information['correspondance_pincode']) ? $personal_information['correspondance_pincode'] : '' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional details -->
                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Current Occupation:</label>
                                        <p><?= $professional_information['current_occupation'] ?></p>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Professional History:</label>
                                        <p><?= $professional_information['professional_history'] ?></p>
                                    </div>
                                </div>

                                <!-- Spacer Start -->
                                <div class="col-xs-12"></div>
                                <!-- Spacer End -->

                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Academic Qualifications:</label>
                                        <p><?php echo ucfirst(str_replace('_', ' ', $professional_information['academic_qualification'])); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-12">
                                    <div class="mb-3 view_col">
                                        <label class="form-label">Academic Qualification Details:</label>
                                        <p><?= (isset($professional_information['highest_qualification'])) ? $professional_information['highest_qualification'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Total Experiance in Arbitration:</label>
                                        <p><?= $professional_information['arbitration_experiance'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Any Other Experiance?:</label>
                                        <p><?= $professional_information['other_experiance'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Other Experience Details:</label>
                                        <p><?= $professional_information['other_experiance_details'] ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Area of Expertise:</label>
                                        <p><?= $professional_information['expertise_area'] ?></p>
                                    </div>
                                </div>

                                <?php if (isset($personal_information['empanellment_category']) && (in_array($personal_information['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($personal_information['empanellment_category'] == 'ENG' && $personal_information['is_engineer_govt_employee'] == 'NO'))) : ?>

                                    <div class="col-xs-12">


                                        <?php
                                        $enr_reg_number_text = 'Registration Number';
                                        $enr_reg_date_text = 'Date of Registration';
                                        $enr_reg_org_name_text = 'Name of the Authority';
                                        $enr_reg_year_text = 'Years of Experience';

                                        if ($personal_information['empanellment_category'] == 'ADV') {
                                            $enr_reg_number_text = "Enrolment Number";
                                            $enr_reg_date_text = "Date of Enrolment";
                                            $enr_reg_org_name_text = "Name of Bar Council";
                                            $enr_reg_year_text = "Years of Standing at the bar";
                                        }
                                        ?>

                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label"><?= $enr_reg_number_text ?></label>
                                                    <p><?= $professional_information['advocate_enrol_no'] ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label"><?= $enr_reg_date_text ?></label>
                                                    <p><?= $professional_information['advocate_date_of_enroll'] ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label"><?= $enr_reg_org_name_text ?></label>
                                                    <p><?= $professional_information['advocate_name_of_bar_council'] ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label"><?= $enr_reg_year_text ?></label>
                                                    <p><?= $professional_information['advocate_years_standing_at_bar'] ?></p>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label">Whether having minimum ten years of practice experience as an advocate as on date of application?</label>
                                                    <p><?= $professional_information['advocate_have_ten_years_exp'] ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12">
                                                <div class="mb-3 view_col">
                                                    <label class="form-label">Whether appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment?</label>
                                                    <p><?= $professional_information['advocate_whether_appearance_before_court'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 py-2">
                                        <fieldset class="fieldset">
                                            <legend class="legend">
                                                Net professional income (only last 2 years)
                                            </legend>
                                            <div class="fieldset-content-box">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                                            <p><?= $professional_information['is_itr_declare_for_first_year'] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php if ($professional_information['is_itr_declare_for_first_year'] == 'yes') : ?>
                                                        <div class="col-md-6 col-sm-6 col-12">
                                                            <div class="mb-3 view_col">
                                                                <label class="form-label">Enter Amount:</label>
                                                                <p><?= $professional_information['net_prof_income_for_22'] ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 1) . '-' . date('Y') ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                                                            <p><?= $professional_information['is_itr_declare_for_second_year'] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php if ($professional_information['is_itr_declare_for_second_year'] == 'yes') : ?>
                                                        <div class="col-md-6 col-sm-6 col-12">
                                                            <div class="mb-3 view_col">
                                                                <label class="form-label">Enter Amount:</label>
                                                                <p><?= $professional_information['net_prof_income_for_23'] ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                <?php endif ?>


                                <?php if (isset($personal_information['empanellment_category']) && (in_array($personal_information['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($personal_information['empanellment_category'] == 'ENG' && $personal_information['is_engineer_govt_employee'] == 'YES'))) : ?>

                                    <div class="row py-4">
                                        <fieldset class="fieldset">
                                            <legend class="legend">
                                                Professional Information for Retired Judges, Bureaucrats and Govt. Engineer
                                            </legend>
                                            <div class="fieldset-content-box">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Date of Superannuation:</label>
                                                            <p><?= $professional_information['retired_judg_bure_date_of_sup_annuation'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Last Designation Held:</label>
                                                            <p><?= $professional_information['retired_judg_bure_last_designation'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="mb-3 view_col">
                                                            <label class="form-label">Name of Department/Court from retired:</label>
                                                            <p><?= $professional_information['retired_judg_bure_department_name'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php endif ?>

                                <div class="col-xs-12">
                                    <div class="row  form-group">
                                        <div class="col-xs-12">
                                            <div class="form-group view_col">
                                                <label class="form-label">Any criminal proceeding pending against you?</label>
                                                <?php if (empty($professional_information['criminal_proceeding_details'])) : ?>
                                                    <p>No</p>
                                                <?php else : ?>
                                                    <p><?= $professional_information['criminal_proceeding_details'] ?></p>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="form-label">Are you employed as member of any Tribunal/Authority/Quasi-Judicial body in Goverment or in Autonomous body?</label>
                                                <?php if (empty($professional_information['MOT_auth_name'])) : ?>
                                                    <p>No</p>
                                                <?php else : ?>
                                                    <div class="row">
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group view_col">
                                                                <label class="form-label">Name of authority:</label>
                                                                <p><?= $professional_information['MOT_auth_name'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group view_col">
                                                                <label class="form-label">Designation:</label>
                                                                <p><?= $professional_information['MOT_designation'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group view_col">
                                                                <label class="form-label">Date of Appointment:</label>
                                                                <p><?= $professional_information['MOT_appoint_date'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group view_col">
                                                                <label class="form-label">Total Tenure:</label>
                                                                <p><?= $professional_information['MOT_total_tenure'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group view_col">
                                                                <label class="form-label">Date of Retirement:</label>
                                                                <p><?= $professional_information['MOT_retire_date'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="row ">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group view_col">
                                                <label class="form-label">Are you already empanelled as an Arbitrator with any other Institution?</label>
                                                <?php if (empty($professional_information['emp_arb_other_NOI'])) : ?>
                                                    <p>No</p>
                                                <?php else : ?>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="">
                                                                <label class="form-label">Name of the Institution:</label>
                                                                <p><?= $professional_information['emp_arb_other_NOI'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="">
                                                                <label class="form-label">Date of Empanellment:</label>
                                                                <p><?= $professional_information['emp_arb_other_DOE'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group view_col">
                                                <label class="form-label">Number of Arbitration matters conducted by you as an Arbitrator (as sole arbitrator or otherwise):</label>
                                                <p><?= $professional_information['NOM_as_arb'] ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group view_col">
                                                <label class="form-label">Details of Academic Achievements/Publications/Articles in the Area of Arbitration (along with the name of journal/book/publication):</label>
                                                <p><?= $professional_information['academic_details_as_arb'] ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group view_col">
                                                <label class="form-label">Please share any other information/details which you may like to furnish:</label>
                                                <p><?= $professional_information['prof_other_info'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_3">
                            <div class="row">
                                <!-- <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group view_col">
                                            <label class="form-label">CV/Portfolio:</label> -->
                                <?php //if (isset($document_files['cv_portfolio']) && !empty($document_files['cv_portfolio'])) {
                                ?>

                                <!-- <a href="<?= base_url('public/upload/efiling/empanelment/CV_PORTFOLIO/' . $document_files['cv_portfolio']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a> -->

                                <?php //} 
                                ?>
                                <!-- </div>
                                    </div> -->
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Certificate of Enrolment/Registration with the relevant institute:</label>
                                        <p>
                                            <?php if (!empty($document_files['enrollment_certificate'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/CERT_OF_ENROLLMENT/' . $document_files['enrollment_certificate']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Highest Academic Qualification Document:</label>
                                        <p>
                                            <?php if (!empty($document_files['highest_qaulification'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/HIGHEST_QUALIFICATION/' . $document_files['highest_qaulification']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Vigilance Clearance Certificate from the concerned department:</label>
                                        <p>
                                            <?php if (!empty($document_files['vigilance_clearance'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/VILIGANCE_CLEARANCE/' . $document_files['vigilance_clearance']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">List of cases conducted:</label>
                                        <p>
                                            <?php if (!empty($document_files['conducted_cases'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/LIST_OF_COND_CASES/' . $document_files['conducted_cases']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Copy of complete ITRs for <?= (date('Y') - 1) . '-' . date('Y') ?> years immediately preceding the date of application for empanelment:</label>
                                        <p>
                                            <?php if (!empty($document_files['first_year_itr'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/FIRST_INCOME_TEXT_RETURN/' . $document_files['first_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Copy of complete ITRs for <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years immediately preceding the date of application for empanelment:</label>
                                        <p>
                                            <?php if (!empty($document_files['second_year_itr'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/SECOND_INCOME_TEXT_RETURN/' . $document_files['second_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">List of Arbitration matters:</label>
                                        <p>
                                            <?php if (!empty($document_files['arbitration_matters'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/LIST_OF_ARB_MATTERS/' . $document_files['arbitration_matters']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
                                        <label class="form-label">Details of academic achievements, publication, articles/writings:</label>
                                        <p>
                                            <?php if (!empty($document_files['academic_achievements'])) :
                                            ?>
                                                <a href="<?= base_url('public/upload/efiling/empanelment/ACADEMIC_ACHIEVEMENTS/' . $document_files['academic_achievements']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                            <?php else : ?>
                                                <span class="badge badge-danger ">Not Provided</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
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
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group view_col">
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
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <?php if (isset($arb_reg_data) && $arb_reg_data['approved'] == 0) : ?>
                    <!-- Approval Form -->
                    <div>
                        <hr>

                        <form action="" method="post" id="emp_appr_form">
                            <input type="hidden" name="hidden_id" value="<?= $arb_reg_data['id'] ?>">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('emp_appr_form'); ?>">

                            <div class="alert alert-custom">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="application_status">Application Status <span class="text-danger">*</span></label>
                                            <select name="application_status" id="application_status" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1">Approve</option>
                                                <option value="2">Reject</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 form-group">
                                        <p class="">
                                            A confirmation mail will be sent to user.
                                        </p>
                                    </div>

                                    <div class="col-xs-12 text-center form-group">
                                        <button type="submit" class="btn btn-success" id="btn_btn_arb_emp_submit">
                                            <i class="fa fa-check"></i> Submit
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </section>
</div>

<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";

    $("#emp_appr_form").bootstrapValidator({
        message: "This value is not valid",
        submitButtons: 'button[type="submit"]',
        submitHandler: function(validator, form, submitButton) {
            $("#btn_arb_emp_submit").attr("disabled", "disabled");
            var formData = new FormData(document.getElementById("emp_appr_form"));
            urls = base_url + "arbitrator-empanelment/change-status";

            $.ajax({
                url: urls,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Enable the submit button
                    enable_submit_btn("btn_arb_emp_submit");

                    // try {
                    var obj = JSON.parse(response);
                    if (obj.status == true) {
                        swal({
                                title: "Success",
                                text: obj.msg,
                                type: "success",
                                html: true,
                            },
                            function() {
                                window.location.reload();
                            }
                        );

                    } else if (obj.status == false) {
                        swal({
                            title: "Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else if (obj.status === "validationerror") {
                        swal({
                            title: "Validation Error",
                            text: obj.msg,
                            type: "error",
                            html: true,
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: "Something went wrong, please contact support",
                            type: "error",
                            html: true,
                        });
                    }
                    // } catch (e) {
                    //     sweetAlert("Sorry",'Unable to Save.Please Try Again !', "error");
                    // }
                },
                error: function(err) {
                    toastr.error("unable to save");
                },
            });
        },
        fields: {
            approved_status: {
                validators: {
                    notEmpty: {
                        message: "Required",
                    },
                },
            },
        },
    });
</script>