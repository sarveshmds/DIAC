<form id="emp_documents_form" class="emp_documents_form" method="POST">
    <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_emp_document_form'); ?>">
    <input type="hidden" name="hidden_arb_id" value="<?= $arb_id ?>">

    <?php if (isset($arb_doc_info['id']) && !empty($arb_doc_info['id'])) : ?>
        <input type="hidden" name="hidden_id" value="<?= $arb_doc_info['id'] ?>">
        <input type="hidden" name="form_type" value="EDIT">
    <?php else : ?>
        <input type="hidden" name="form_type" value="ADD">
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="row">

                <!-- CERTIFICATE OF ENROLMENT -->
                <div class="col-12 bg-light p-2 mb-3">
                    <div class=" emp-doc-wrapper">
                        <h4 class="emp-doc-title">Certificate of Enrolment/Registration
                        </h4>
                        <div class="emp-doc-file-col">
                            <input type="file" name="enrollment_certificate_doc" id="enrollment_certificate_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                            <div>
                                <small class="text-danger">
                                    Only .pdf allowed (Max. Size 2MB)
                                </small>
                            </div>

                            <?php if (!empty($arb_doc_info['enrollment_certificate'])) {
                            ?>
                                <a href="<?= base_url('public/upload/efiling/empanelment/CERT_OF_ENROLLMENT/' . $arb_doc_info['enrollment_certificate']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- CERTIFICATE OF PRACTICE -->
                <div class="col-12 bg-light p-2 mb-3">
                    <div class=" emp-doc-wrapper">
                        <h4 class="emp-doc-title">Certificate of Practice.
                        </h4>
                        <div class="emp-doc-file-col">
                            <input type="file" name="practice_certificate_doc" id="practice_certificate_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                            <div>
                                <small class="text-danger">
                                    Only .pdf allowed (Max. Size 2MB)
                                </small>
                            </div>

                            <?php if (!empty($arb_doc_info['practice_certificate'])) {
                            ?>
                                <a href="<?= base_url('public/upload/efiling/empanelment/CERT_OF_PRACTICE/' . $arb_doc_info['practice_certificate']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'YES'))) : ?>
                    <!-- VIGILANCE CERTIFICATE -->
                    <div class="col-12 bg-light p-2 mb-3">
                        <div class=" emp-doc-wrapper">
                            <h4 class="emp-doc-title">In case the applicant is a retired government employee, a vigilance clearance certificate from the concerned department. <span class="text-danger">*</span>
                            </h4>
                            <div class="emp-doc-file-col">
                                <input type="file" name="vigilance_clearance_doc" id="vigilance_clearance_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                                <div>
                                    <small class="text-danger">
                                        Only .pdf allowed (Max. Size 2MB)
                                    </small>
                                </div>
                                <?php if (!empty($arb_doc_info['vigilance_clearance'])) {
                                ?>
                                    <a href="<?= base_url('public/upload/efiling/empanelment/VILIGANCE_CLEARANCE/' . $arb_doc_info['vigilance_clearance']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- DETAILS OF ORDER/JUDGEMENTS -->
                <div class="col-12 bg-light p-2 mb-3">
                    <div class=" emp-doc-wrapper">
                        <h4 class="emp-doc-title">Details of orders/judgments w.r.t. appearances before Hon’ble Supreme Court of India or Hon’ble High Court(s) or in Arbitral Proceedings or having acted as an Arbitrator or in combination, in atleast 10(ten) Commercial Cases during the two years immediately preceding the date of application for empanelment. (Copies of the orders/judgments shall be required to be produced as and when required.) [See Rule I(4) DIAC Empanelment Rules, 2020].
                        </h4>
                        <div class="emp-doc-file-col">
                            <input type="file" name="conducted_cases_doc" id="conducted_cases_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                            <div>
                                <small class="text-danger">
                                    Only .pdf allowed (Max. Size 2MB)
                                </small>
                            </div>
                            <?php if (!empty($arb_doc_info['conducted_cases'])) {
                            ?>
                                <a href="<?= base_url('public/upload/efiling/empanelment/LIST_OF_COND_CASES/' . $arb_doc_info['conducted_cases']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>


                <?php if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'NO'))) : ?>

                    <!-- ITR - 1 -->
                    <div class="col-12 bg-light p-2 mb-3" id="itr_doc_second_col">
                        <div class=" emp-doc-wrapper">
                            <h4 class="emp-doc-title">Copy of complete ITRs for <?= (date('Y') - 2) . '-' . (date('Y') - 1) ?> <span class="text-danger">*</span>
                            </h4>
                            <div class="emp-doc-file-col">
                                <input type="file" name="itr_second_doc" id="itr_second_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                                <div>
                                    <small class="text-danger">
                                        Only .pdf allowed (Max. Size 5MB)
                                    </small>
                                </div>
                                <?php if (!empty($arb_doc_info['second_year_itr'])) {
                                ?>
                                    <a href="<?= base_url('public/upload/efiling/empanelment/SECOND_INCOME_TEXT_RETURN/' . $arb_doc_info['second_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- ITR - 2 -->
                    <div class="col-12 bg-light p-2 mb-3" id="itr_doc_first_col">
                        <div class=" emp-doc-wrapper">
                            <h4 class="emp-doc-title">Copy of complete ITR for the year <?= (date('Y') - 1) . '-' . date('Y') ?> <span class="text-danger">*</span>
                            </h4>
                            <div class="emp-doc-file-col">
                                <input type="file" name="itr_first_doc" id="itr_first_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                                <div>
                                    <small class="text-danger">
                                        Only .pdf allowed (Max. Size 5MB)
                                    </small>
                                </div>
                                <?php if (!empty($arb_doc_info['first_year_itr'])) {
                                ?>
                                    <a href="<?= base_url('public/upload/efiling/empanelment/FIRST_INCOME_TEXT_RETURN/' . $arb_doc_info['first_year_itr']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 bg-light p-2 mb-3">
                    <div class=" emp-doc-wrapper">
                        <h4 class="emp-doc-title">Copy of articles relating to arbitration published in any book/journal (if any)</small>
                        </h4>
                        <div class="emp-doc-file-col">
                            <input type="file" name="academic_achieve_doc" id="academic_achieve_doc" class="filestyle" data-btnClass="btn-danger" accept=".pdf">
                            <div>
                                <small class="text-danger">
                                    Only .pdf allowed (Max. Size 5MB)
                                </small>
                            </div>
                            <?php if (!empty($arb_doc_info['academic_achievements'])) {
                            ?>
                                <a href="<?= base_url('public/upload/efiling/empanelment/ACADEMIC_ACHIEVEMENTS/' . $arb_doc_info['academic_achievements']) ?>" target="_BLANK" class="btn btn-orange btn-sm"><span class="fa fa-eye"></span> View Document</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-12 mb-3 d-md-flex gap-5 justify-content-center" style="border-right: 1px solid lightgrey;">

                    <!-- PROFILE PHOTO -->
                    <div class="w-50">
                        <label for="" class="form-label">Profile Photo <span class="text-danger">*</span></label>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept=".jpg, .jpeg,.png" />
                        <small class="text-danger d-block mt-2">
                            Only .jpg, .png, .jpeg (Max Size: 500KB)
                        </small>

                        <?php if (!empty($arb_doc_info['profile_photo'])) {
                        ?>
                            <div class="mt-3">
                                <img src="<?= base_url('public/upload/efiling/empanelment/PROFILE/' . $arb_doc_info['profile_photo']) ?>" alt="Image" class="w-100 img-thumbnail" />
                            </div>
                        <?php } ?>
                    </div>

                </div>

                <div class="offset-md-1 col-md-5 col-12">
                    <div class="">
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="submitted_date" id="submitted_date" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Place <span class="text-danger">*</span></label>
                            <input type="text" name="submitted_place" id="submitted_place" class="form-control" value="<?= (isset($arb_doc_info['submitted_place'])) ? $arb_doc_info['submitted_place'] : '' ?>">
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
                    <label class="form-label">Captcha</label>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mb-3 mt-3">
        <?php if ($arb_reg_data['step'] >= 2) : ?>
            <a href="<?= base_url('efiling/empanellment/professional-information?id=' . $arb_reg_data['id']) ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Prev Step</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-warning"><i class="fa fa-save" id="emp_submit"></i> Save & Preview</button>
    </div>
</form>