<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="card mt-4 mb-4 bg-white rounded shadow">
                    <div class="card-header pt-4 flex-column align-items-start">
                        <h5 class="card-title text-danger">Apply For Internship</h5>
                    </div>
                    <div class="card-body">
                        <!-- <div class="row">
                        <div class="col-md-4">
                            <div class="input-icon mb-2">
                                <input class="form-control " placeholder="Select a date" id="datepicker-icon" value="">
                            </div>
                        </div>
                    </div> -->
                        <form id="internship_form" class="internship_form" method="POST" enctype=multipart/form-data>
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_internship_form'); ?>">
                            <div class="row ">
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
                                        <small class="text-danger d-block mt-2">
                                            Only .jpg, .png, .jpeg (Max Size: 500KB)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Name of Applicant: <span class="text-danger">*</span></label>
                                                <input type="text" name="name_of_applicant" id="name_of_applicant" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Date of Birth: <span class="text-danger">*</span></label>
                                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Father's Name: <span class="text-danger">*</span></label>
                                                <input type="text" name="father_name" id="father_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mother's Name: <span class="text-danger">*</span></label>
                                                <input type="text" name="mother_name" id="mother_name" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Name of the Institute/College: <span class="text-danger">*</span></label>
                                                <input type="text" name="name_of_college_institute" id="name_of_college_institute" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Programme Duration: <small> (year)</small><span class="text-danger">*</span></label>
                                                <select name="programme_duration" id="programme_duration" class="form-control">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                </select>
                                                <!-- <input type="text" name="programme_duration" id="programme_duration" class="form-control"> -->
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Semester Pursuing: <span class="text-danger">*</span></label>
                                                <select name="semester_pursuing" id="semester_pursuing" class="form-control">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>

                                                <!-- <input type="text" name="semester_pursuing" id="semester_pursuing" class="form-control"> -->
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Period of Internship: <span class="text-danger">*</span></label>
                                                <div class="d-flex ">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">From</span>
                                                        <input type="date" name="prefrred_period_internship_from" id="prefrred_period_internship_from" class="form-control">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">To</span>
                                                        <input type="date" name="prefrred_period_internship_to" id="prefrred_period_internship_to" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email: <span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Number: <span class="text-danger">*</span></label>
                                        <input type="number" name="mobile" id="mobile" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Gender: <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option>Select</option>
                                            <?php foreach ($gender as $gender) : ?>
                                                <option value="<?= $gender['gen_code'] ?>"><?= $gender['description'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Permanent Address: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="permanent_address" id="permanent_address" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address During Internship: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address_during_internship" id="address_during_internship" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <fieldset class="fieldset">
                                    <legend class="legend">
                                        Details of Institution
                                    </legend>
                                    <div class="fieldset-content-box">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address:</label>
                                                    <textarea class="form-control" rows="5" name="institute_address" id="institute_address"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person of Institution:</label>
                                                    <input type="text" name="contact_person_of_institute" id="contact_person_of_institute" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number of Contact Person:</label>
                                                    <input type="text" name="contact_person_no_of_institute" id="contact_person_no_of_institute" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Upload CV: <span class="text-danger">*</span></label>
                                                    <input type="file" name="cv" id="cv" class="form-control" accept=".pdf">
                                                    <small class="text-danger">Only .pdf file allowed (max size: 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Cover Letter/Statement of Interest: <span class="text-danger">*</span></label>
                                                    <input type="file" name="cover_letter" id="cover_letter" class="form-control" accept=".pdf">
                                                    <small class="text-danger">Only .pdf file allowed (max size: 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Letter of Reference: <span class="text-danger">*</span></label>
                                                    <input type="file" name="reference_letter" id="reference_letter" class="form-control" accept=".pdf">
                                                    <small class="text-danger">Only .pdf file allowed (max size: 2MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row py-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="mb-2 d-flex align-items-center justify-content-center">
                                        <span id="captcha_img" class="me-2"><?php echo $image; ?></span>
                                        <button class="login-reload-captcha-btn btn btn-default border-0 shadow-none" type="button" value="" name="btnReload" id="btnReload"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <label class="form-label">Captcha</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="txt_captcha" placeholder="Captcha" aria-label="Captcha" aria-describedby="captcha-addon">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger">Apply</button>
                                    </div>
                                </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $('#internship_form').validate({
        errorClass: 'is-invalid text-danger',
        rules: {
            // profile_photo: {
            //     required:true,
            // },
            name_of_applicant: {
                required: true
            },
            date_of_birth: {
                required: true
            },
            name_of_college_institute: {
                required: true
            },
            programme_duration: {
                required: true
            },
            semester_pursuing: {
                required: true
            },
            prefrred_period_internship_from: {
                required: true
            },
            prefrred_period_internship_to: {
                required: true
            },
            father_name: {
                required: true
            },
            mother_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            gender: {
                required: true,
            },
            permanent_address: {
                required: true
            },
            address_during_internship: {
                required: true
            },
            // cv: {
            //     required: true
            // },
            // cover_letter: {
            //     required: true
            // },
            // reference_letter: {
            //     required: true
            // },
            txt_captcha: {
                required: true
            }
        },

        submitHandler: function(form, event) {
            event.preventDefault();

            // Confirmation
            Swal.fire({
                icon: "warning",
                title: "Do you want to submit the form?",
                showCancelButton: true,
                confirmButtonText: "Confirm",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    var url = base_url + "efiling/apply-internship/store";
                    $.ajax({
                        url: url,
                        data: new FormData(document.getElementById("internship_form")),
                        type: "POST",
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            var response = JSON.parse(response);
                            console.log(response)

                            if (response.status) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    html: response.msg,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = base_url + "efiling/apply-internship";
                                    }
                                });
                            } else if (response.status == "validation_error") {
                                Swal.fire({
                                    icon: "error",
                                    title: "Validation Error",
                                    text: response.msg,
                                });
                                reloadCaptcha();
                            } else if (response.status == false) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    html: response.msg,
                                });
                                reloadCaptcha();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Server Error",
                                });
                                reloadCaptcha();
                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Server Error, please try again",
                            });
                            reloadCaptcha();
                        },
                    });
                }
            });

        },
    })
</script>
