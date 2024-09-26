<div class="content-wrapper">
    <div class="content">
        <div class="content-heading text-center">
            <h4 class="content-title">
                Arbitrator Empanelment  Application
            </h4>
        </div>

        <div class="content-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="w-25">
                            <label class="form-label">Category: </label>
                        </td>
                        <td colspan="2">
                            <p><?= isset($personal_information['empanellment_category_name']) ? $personal_information['empanellment_category_name'] : '' ?></p>
                        </td>
                        <td rowspan="4" class="text-center">
                            <?php if (!empty($document_files['profile_photo'])) :
                            ?>
                                <img src="<?= base_url('public/upload/efiling/empanelment/PROFILE/' . $document_files['profile_photo']) ?>" alt="Image" style="width: 100px;" />
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25">
                            <label class="form-label">Name:</label>
                        </td>
                        <td colspan="2">
                            <p>
                                <?= isset($personal_information['sal_title']) ? $personal_information['sal_title'] : '' ?>
                                <?= isset($personal_information['first_name']) ? $personal_information['first_name'] : '' ?>
                                <?= isset($personal_information['middle_name']) ? $personal_information['middle_name'] : '' ?>
                                <?= isset($personal_information['last_name']) ? $personal_information['last_name'] : '' ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25">
                            <label class="form-label">Email ID:</label>
                        </td>
                        <td colspan="2">
                            <p><?= isset($arb_info['email_id']) ? $arb_info['email_id'] : '' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25">
                            <label class="form-label">Mobile Number: </label>
                        </td>
                        <td colspan="2">
                            <p><?= isset($arb_info['phone_number']) ? $arb_info['phone_number'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="w-25">
                            <label class="form-label">Alternate Mobile Number</label>
                        </td>
                        <td colspan="3">
                            <p><?= (isset($personal_information['alternate_mobile']) && $personal_information['alternate_mobile']) ? $personal_information['alternate_mobile'] : 'Not provided' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-25">
                            <label class="form-label">Languages Known: </label>
                        </td>
                        <td colspan="3">
                            <p><?= isset($personal_information['languages_known']) ? $personal_information['languages_known'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="w-25">
                            <label class="form-label">Date of Birth</label>
                        </td>
                        <td class="w-25">
                            <p><?= isset($personal_information['dob']) ? $personal_information['dob'] : '' ?></p>
                        </td>
                        <td class="w-25">
                            <label class="form-label">Nationality: </label>
                        </td>
                        <td class="w-25">
                            <p><?= isset($personal_information['nationality_name']) ? $personal_information['nationality_name'] : '' ?></p>
                        </td>
                    </tr>

                    <!-- Resident Address -->
                    <tr>
                        <th colspan="4">
                            Resident Address
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Address </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['resident_add_1']) ? $personal_information['resident_add_1'] : '' ?></p>
                            <p><?= isset($personal_information['resident_add_2']) ? $personal_information['resident_add_2'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">City </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['resident_city']) ? $personal_information['resident_city'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="form-label">State </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['resident_state_name']) ? $personal_information['resident_state_name'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">Country </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['resident_country_name']) ? $personal_information['resident_country_name'] : '' ?> - <?= isset($personal_information['resident_pincode']) ? $personal_information['resident_pincode'] : '' ?></p>
                        </td>
                    </tr>

                    <!-- Office details and address -->
                    <tr>
                        <th colspan="4">
                            Office Details & Address
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Company/Firm Name: </label>
                        </td>
                        <td colspan="3">
                            <p><?= isset($personal_information['office_company_name']) ? $personal_information['office_company_name'] : '' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Address </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['office_add_1']) ? $personal_information['office_add_1'] : '' ?></p>
                            <p><?= isset($personal_information['office_add_2']) ? $personal_information['office_add_2'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">City </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['office_city']) ? $personal_information['office_city'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="form-label">State </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['office_state_name']) ? $personal_information['office_state_name'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">Country </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['office_country_name']) ? $personal_information['office_country_name'] : '' ?> - <?= isset($personal_information['office_pincode']) ? $personal_information['office_pincode'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="form-label">Office Contact Number </label>
                        </td>
                        <td colspan="3">
                            <p><?= isset($personal_information['office_phone_number']) ? $personal_information['office_phone_number'] : '' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Website </label>
                        </td>
                        <td colspan="3">
                            <p><?= isset($personal_information['office_website']) ? $personal_information['office_website'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="form-label">Email IDs </label>
                        </td>
                        <td colspan="3">
                            <p><?= isset($personal_information['office_email_1']) ? $personal_information['office_email_1'] : '' ?> <?= isset($personal_information['office_email_2']) ? ',' . $personal_information['office_email_2'] : '' ?></p>
                        </td>
                    </tr>

                    <!-- Correspondence Address -->
                    <tr>
                        <th colspan="4">
                        Correspondence Address  
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Address </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['correspondance_add_1']) ? $personal_information['correspondance_add_1'] : '' ?></p>
                            <p><?= isset($personal_information['correspondance_add_2']) ? $personal_information['correspondance_add_2'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">City </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['correspondance_city']) ? $personal_information['correspondance_city'] : '' ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label class="form-label">State </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['correspondance_state_name']) ? $personal_information['correspondance_state_name'] : '' ?></p>
                        </td>
                        <td>
                            <label class="form-label">Country </label>
                        </td>
                        <td>
                            <p><?= isset($personal_information['correspondance_country_name']) ? $personal_information['correspondance_country_name'] : '' ?> - <?= isset($personal_information['correspondance_pincode']) ? $personal_information['correspondance_pincode'] : '' ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="page-break"></div>

            <!-- Professional Information -->
            <div class="content-heading text-center">
                <h4 class="content-title">
                    Professional Information
                </h4>
            </div>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="30%">
                            <label class="form-label">Current Occupation:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['current_occupation'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Professional History:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['professional_history'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Academic Qualifications:</label>
                        </td>
                        <td>
                            <p><?php echo ucfirst(str_replace('_', ' ', $professional_information['academic_qualification'])); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Academic Qualification Details:</label>
                        </td>
                        <td>
                            <p><?= (isset($professional_information['highest_qualification'])) ? $professional_information['highest_qualification'] : '' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Total Experience in Arbitration:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['arbitration_experiance'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Any Other Experiance?:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['other_experiance'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Other Experience Details:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['other_experiance_details'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">Area of Expertise:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['expertise_area'] ?></p>
                        </td>
                    </tr>

                    <?php if (isset($personal_information['empanellment_category']) && (in_array($personal_information['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($personal_information['empanellment_category'] == 'ENG' && $personal_information['is_engineer_govt_employee'] == 'NO'))) : ?>

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

                        <tr>
                            <td>
                                <label class="form-label h4"><?= $enr_reg_number_text ?></label>
                            </td>
                            <td>
                                <p><?= $professional_information['advocate_enrol_no'] ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label h4"><?= $enr_reg_date_text ?></label>
                            </td>
                            <td>
                                <p><?= $professional_information['advocate_date_of_enroll'] ?></p>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <label class="form-label h4"><?= $enr_reg_org_name_text ?></label>
                            </td>
                            <td>
                                <p><?= $professional_information['advocate_name_of_bar_council'] ?></p>
                            </td>

                        </tr>
                        <tr>

                            <td>
                                <label class="form-label h4"><?= $enr_reg_year_text ?></label>
                            </td>
                            <td>
                                <p><?= $professional_information['advocate_years_standing_at_bar'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <label class="form-label h4">Whether having minimum ten years of practice experience as an advocate as on date of application?</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?= $professional_information['advocate_have_ten_years_exp'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <label class="form-label h4">Whether appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment?</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?= $professional_information['advocate_whether_appearance_before_court'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">
                                <label class="form-label h4">Net professional income (only last 2 years)</label>
                            </th>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <label class="form-label h4">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?= $professional_information['is_itr_declare_for_first_year'] ?></p>
                            </td>
                        </tr>

                        <?php if ($professional_information['is_itr_declare_for_first_year'] == 'yes') : ?>
                            <tr>
                                <td>
                                    <label class="form-label h4">ITR Amount</label>
                                </td>
                                <td>
                                    <p><?= $professional_information['net_prof_income_for_22'] ?></p>
                                </td>
                            </tr>
                        <?php endif ?>

                        <tr>
                            <td colspan="2">
                                <label class="form-label h4">Whether net professional income as declared in the income tax returns for the <?= (date('Y') - 1) . '-' . date('Y') ?> years immediately preceding the date of application is Rs. 6 lakhs per annum or more?</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?= $professional_information['is_itr_declare_for_second_year'] ?></p>
                            </td>
                        </tr>

                        <?php if ($professional_information['is_itr_declare_for_second_year'] == 'yes') : ?>
                            <tr>
                                <td>
                                    <label class="form-label h4">ITR Amount</label>
                                </td>
                                <td>
                                    <p><?= $professional_information['net_prof_income_for_23'] ?></p>
                                </td>
                            </tr>
                        <?php endif ?>

                    <?php endif ?>

                    <?php if (isset($personal_information['empanellment_category']) && (in_array($personal_information['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($personal_information['empanellment_category'] == 'ENG' && $personal_information['is_engineer_govt_employee'] == 'YES'))) : ?>

                        <tr>
                            <th colspan="2">
                                Professional Information for Retired Judges, Bureaucrats and Govt. Engineer
                            </th>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label h4">Date of Superannuation:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['retired_judg_bure_date_of_sup_annuation'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label h4">Last Designation Held:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['retired_judg_bure_last_designation'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label h4">Name of Department/Court from retired:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['retired_judg_bure_department_name'] ?></p>
                            </td>
                        </tr>
                    <?php endif ?>

                    <tr>
                        <td colspan="2">
                            <label class="form-label">Any criminal proceeding pending against you?</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php if (empty($professional_information['criminal_proceeding_details'])) : ?>
                                <p>No</p>
                            <?php else : ?>
                                <p><?= $professional_information['criminal_proceeding_details'] ?></p>
                            <?php endif ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label class="form-label">Are you employed as member of any Tribunal/Authority/Quasi-Judicial body in Government or in Autonomous body?</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php if (isset($professional_information['is_employed_as_MOT']) && $professional_information['is_employed_as_MOT'] == 'yes') : ?>
                                <p>Yes</p>
                            <?php else : ?>
                                <p>No</p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if (isset($professional_information['is_employed_as_MOT']) && $professional_information['is_employed_as_MOT'] == 'yes') : ?>

                        <tr>
                            <td>
                                <label class="form-label">Name of authority:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['MOT_auth_name'] ?></p>

                            </td>
                        </tr>

                        <tr>

                            <td>
                                <label class="form-label">Designation:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['MOT_designation'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">Date of Appointment:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['MOT_appoint_date'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">Total Tenure:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['MOT_total_tenure'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">Date of Retirement:</label>
                            </td>
                            <td>
                                <p><?= $professional_information['MOT_retire_date'] ?></p>
                            </td>
                        </tr>

                    <?php endif; ?>

                    <tr>
                        <td colspan="2">
                            <label class="form-label">Whether empanelled as an arbitrator with any other institution?</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><?= $professional_information['is_already_empanelled_arb'] ?></p>
                        </td>

                    </tr>

                    <?php if ($professional_information['is_already_empanelled_arb'] == 'yes') : ?>
                        <tr class="bg-dark text-white">
                            <th>Name of Institute:</th>
                            <th>Date of Empanelment:</th>
                        </tr>
                        <?php foreach ($arb_empanel_other as $key => $arb_other) : ?>
                            <tr>
                                <td> <?= $arb_other['name_of_institute'] ?></td>
                                <td> <?= $arb_other['date_of_empanelment'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>

                    <tr>
                        <td>
                            <label class="form-label">Number of cases conducted by the applicant as an arbitrator:</label>
                        </td>
                        <td>
                            <p><?= $professional_information['NOM_as_arb'] ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label class="form-label">Particulars of articles relating to arbitration, if any, published in any journal/book/publication:</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><?= $professional_information['academic_details_as_arb'] ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label class="form-label">Please share any other information/details which you may like to furnish:</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p><?= $professional_information['prof_other_info'] ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>