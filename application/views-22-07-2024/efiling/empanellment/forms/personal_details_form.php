<form id="empanelment_personal_info_form" class="empanelment_personal_info_form" method="POST">
    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_personal_info_form'); ?>">
    <input type="hidden" name="hidden_arb_id" value="<?= $arb_id ?>">

    <?php if (isset($arb_personal_info['id']) && !empty($arb_personal_info['id'])) : ?>
        <input type="hidden" name="hidden_id" value="<?= $arb_personal_info['id'] ?>">
        <input type="hidden" name="form_type" value="EDIT">
    <?php else : ?>
        <input type="hidden" name="form_type" value="ADD">
    <?php endif; ?>


    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12" id="emp_cat_col">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control valid" name="empanellment_category" id="empanellment_category" aria-label="Empanellment Category" aria-invalid="false">
                            <option>Select</option>
                            <?php foreach ($panel_category as $pc) : ?>
                                <option value="<?= $pc['category_code'] ?>" <?= isset($arb_personal_info['empanellment_category']) && $pc['category_code'] == $arb_personal_info['empanellment_category'] ? 'selected' : '' ?>><?= $pc['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12" id="other_cat_col">
                    <div class="mb-3">
                        <label class="form-label">Please Specify<span class="text-danger">*</span></label>
                        <input type="text" name="other_cat_specify" id="other_cat_specify" class="form-control" value="<?= isset($arb_personal_info['specify_other_cat']) ? $arb_personal_info['specify_other_cat'] : '' ?>">
                    </div>
                </div>

                <!-- IF CATEGORY IS ENGINEER -->
                <div class="col-md-4 col-sm-6 col-12" id="engineer_govt_emp_col" <?= (isset($arb_personal_info['empanellment_category']) && $arb_personal_info['empanellment_category'] == 'ENG') ? '' : 'style="display: none;"' ?>>
                    <div class="mb-3">
                        <label class="form-label">Have you been a Goverment Employee? <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_engineer_govt_emp" id="is_engineer_govt_emp">
                            <option>Select one option</option>
                            <option value="YES" <?= isset($arb_personal_info['is_engineer_govt_employee']) && $arb_personal_info['is_engineer_govt_employee'] == 'YES' ? 'selected' : '' ?>>Retired</option>
                            <option value="NO" <?= isset($arb_personal_info['is_engineer_govt_employee']) && $arb_personal_info['is_engineer_govt_employee'] == 'NO' ? 'selected' : '' ?>>Professional</option>
                        </select>
                    </div>
                </div>

                <div class="col-12"></div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Salutation <span class="text-danger">*</span></label>
                        <select name="salutation" id="salutation" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($salutations as $sal) : ?>
                                <option value="<?= $sal['id'] ?>" <?= (isset($arb_personal_info['salutation']) && $arb_personal_info['salutation'] == $sal['id']) ? 'selected' : '' ?>><?= $sal['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12" id="emp_profile_col">
                    <div class="mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="<?= isset($arb_personal_info['first_name']) ? $arb_personal_info['first_name'] : '' ?>">
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?= isset($arb_personal_info['middle_name']) ? $arb_personal_info['middle_name'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="<?= isset($arb_personal_info['last_name']) ? $arb_personal_info['last_name'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $arb_reg_data['email_id'] ?>" disabled>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="number" name="mobile" id="mobile" class="form-control" value="<?= $arb_reg_data['phone_number'] ?>" disabled>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Alternate Phone Number</label>
                        <input type="number" name="alternate_mobile" id="alternate_mobile" class="form-control" value="<?= isset($arb_personal_info['alternate_mobile']) ? $arb_personal_info['alternate_mobile'] : '' ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="text" name="dob" id="arb_emp_date_of_birth" class="form-control " value="<?= isset($arb_personal_info['dob']) ? $arb_personal_info['dob'] : '' ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Languages Known <span class="text-danger">*</span></label>
                        <input type="text" name="languages_known" id="languages_known" class="form-control" value="<?= isset($arb_personal_info['languages_known']) ? $arb_personal_info['languages_known'] : '' ?>" maxlength="100">
                        <small class="text-danger">Enter (,) comma seperated values</small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Nationality <span class="text-danger">*</span></label>
                        <select class="form-control" id="nationality" name="nationality">
                            <option>Select one option</option>
                            <?php foreach ($nationality as $n_data) : ?>
                                <option value="<?= $n_data['iso2'] ?>" <?= isset($arb_personal_info['nationality']) && $n_data['iso2'] == $arb_personal_info['nationality'] ? 'selected' : '' ?>><?= $n_data['nationality'] ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

            </div>
            <div class="card mt-3">
                <div class="card-status-top bg-red"></div>
                <div class="card-header card-header-light">
                    <h3 class="card-title">Residential Address</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="resident_add_1" id="resident_add_1"><?= isset($arb_personal_info['resident_add_1']) ? $arb_personal_info['resident_add_1'] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 2 (Optional) </label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="resident_add_2" id="resident_add_2"><?= isset($arb_personal_info['resident_add_2']) ? $arb_personal_info['resident_add_2'] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control" name="resident_country" id="resident_country">
                                    <option>Select country</option>
                                    <?php foreach ($countries as $country) : ?>
                                        <option value="<?= $country['iso2'] ?>" <?= isset($arb_personal_info['resident_country']) && $country['iso2'] == $arb_personal_info['resident_country'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="hidden_resident_country" name="hidden_resident_country" value="<?= (isset($arb_personal_info['resident_country'])) ? $arb_personal_info['resident_country'] : '' ?>">
                        <input type="hidden" id="hidden_resident_state" name="hidden_resident_state" value="<?= (isset($arb_personal_info['resident_state'])) ? $arb_personal_info['resident_state'] : '' ?>">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-control" name="resident_state" id="resident_state">
                                    <option>Select State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="resident_city" id="resident_city" value="<?= isset($arb_personal_info['resident_city']) ? $arb_personal_info['resident_city'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                <input type="text" name="resident_pincode" id="resident_pincode" class="form-control" placeholder="Pin Code" value="<?= isset($arb_personal_info['resident_pincode']) ? $arb_personal_info['resident_pincode'] : '' ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-status-top bg-red"></div>
                <div class="card-header card-header-light">
                    <h3 class="card-title">Office Address</h3>
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
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="office_add_1" id="office_add_1"><?= isset($arb_personal_info['office_add_1']) ? $arb_personal_info['office_add_1'] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 2 (Optional) </label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="office_add_2" id="office_add_2"><?= isset($arb_personal_info['office_add_2']) ? $arb_personal_info['office_add_2'] : '' ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="hidden_office_country" name="hidden_office_country" value="<?= (isset($arb_personal_info['office_country'])) ? $arb_personal_info['office_country'] : '' ?>">
                        <input type="hidden" id="hidden_office_state" name="hidden_office_state" value="<?= (isset($arb_personal_info['office_state'])) ? $arb_personal_info['office_state'] : '' ?>">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control" name="office_country" id="office_country">
                                    <option>Select country</option>
                                    <?php foreach ($countries as $country) : ?>

                                        <option value="<?= $country['iso2'] ?>" <?= isset($arb_personal_info['office_country']) && $country['iso2'] == $arb_personal_info['office_country'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-control" name="office_state" id="office_state">
                                    <option>Select State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="office_city" id="office_city" value="<?= isset($arb_personal_info['office_city']) ? $arb_personal_info['office_city'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                <input type="text" name="office_pincode" id="office_pincode" class="form-control" placeholder="Pin Code" value="<?= isset($arb_personal_info['office_pincode']) ? $arb_personal_info['office_pincode'] : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Firm/Company Name</label>
                                <input type="text" name="office_company_name" id="office_company_name" class="form-control" placeholder="Firm/Company name" value="<?= isset($arb_personal_info['office_company_name']) ? $arb_personal_info['office_company_name'] : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Office Phone Number</label>
                                <input type="text" name="office_phone_number" id="office_phone_number" class="form-control" placeholder="Office phone number" value="<?= isset($arb_personal_info['office_phone_number']) ? $arb_personal_info['office_phone_number'] : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" name="office_website" id="office_website" class="form-control" placeholder="Website" value="<?= isset($arb_personal_info['office_website']) ? $arb_personal_info['office_website'] : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Email Address 1</label>
                                <input type="email" name="office_email_1" id="office_email_1" class="form-control" placeholder="Firm/Company name" value="<?= isset($arb_personal_info['office_email_1']) ? $arb_personal_info['office_email_1'] : '' ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Email Address 2</label>
                                <input type="email" name="office_email_2" id="office_email_2" class="form-control" placeholder="Firm/Company name" value="<?= isset($arb_personal_info['office_email_2']) ? $arb_personal_info['office_email_2'] : '' ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-status-top bg-red"></div>
                <div class="card-header card-header-light">
                    <h3 class="card-title">Correspondance Address</h3>
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
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="correspondance_add_1" id="correspondance_add_1"><?= isset($arb_personal_info['correspondance_add_1']) ? $arb_personal_info['correspondance_add_1'] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Address Line 2 (Optional) </label>
                                <textarea class="form-control" placeholder="Type Address Here..." aria-label="Type Address Here..." name="correspondance_add_2" id="correspondance_add_2"><?= isset($arb_personal_info['correspondance_add_2']) ? $arb_personal_info['correspondance_add_2'] : '' ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="hidden_correspondance_country" name="hidden_correspondance_country" value="<?= (isset($arb_personal_info['correspondance_country'])) ? $arb_personal_info['correspondance_country'] : '' ?>">
                        <input type="hidden" id="hidden_correspondance_state" name="hidden_correspondance_state" value="<?= (isset($arb_personal_info['correspondance_state'])) ? $arb_personal_info['correspondance_state'] : '' ?>">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control" name="correspondance_country" id="correspondance_country">
                                    <option>Select country</option>
                                    <?php foreach ($countries as $country) : ?>

                                        <option value="<?= $country['iso2'] ?>" <?= isset($arb_personal_info['correspondance_country']) && $country['iso2'] == $arb_personal_info['correspondance_country'] ? 'selected' : '' ?>><?= $country['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select class="form-control" name="correspondance_state" id="correspondance_state">
                                    <option>Select State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="correspondance_city" id="correspondance_city" value="<?= isset($arb_personal_info['correspondance_city']) ? $arb_personal_info['correspondance_city'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                                <input type="text" name="correspondance_pincode" id="correspondance_pincode" class="form-control" placeholder="Pin Code" value="<?= isset($arb_personal_info['correspondance_pincode']) ? $arb_personal_info['correspondance_pincode'] : '' ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <div class="col-md-4 mx-auto">
                    <div class="mb-3 d-flex align-items-center justify-content-center">
                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                        <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                    </div>
                    <div class="text-start">
                        <label class="form-label">Captcha <span class="text-danger">*</span></label>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-2">
                <button type="submit" class="btn btn-custom"><i class="fa fa-save"></i> Save & Next</button>
                <?php if ($arb_reg_data['step'] > 0) : ?>
                    <a href="<?= base_url('efiling/empanellment/professional-information?id=' . $arb_reg_data['id']) ?>" class="btn btn-secondary">Next Step <i class="fa fa-arrow-right ms-1"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>