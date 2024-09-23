<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="card mt-4 mb-4 bg-white rounded shadow">
                    <div class="card-header pt-4 flex-column align-items-start">
                        <h5 class="card-title text-danger">Feedback Form</h5>
                        <p class="text-lead text-dark mt-1">(To be filled only by the authorized Counsel)</p>
                        <p class="text-lead text-dark mt-1">The purpose of this feedback form is to assess overall functioning of the centre. The information provided in this Form may be usefull to the Arbitrator, the Centre and to persons intending to work with the Centre in future.</p>
                    </div>
                    <div class="card-body">
                        <form id="feedback_form" class="feedback_form" method="POST">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_feedback_form'); ?>">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name of Arbitrator: <span class="text-danger">*</span></label>
                                        <input type="text" name="name_of_arb" id="name_of_arb" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">DIAC Case Number: <span class="text-danger">*</span></label>
                                        <input type="text" name="diac_case_no" id="diac_case_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name of the Advocate: <span class="text-danger">*</span></label>
                                        <input type="text" name="name_of_adv" id="name_of_adv" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Enrolment Number of the Advocate: <span class="text-danger">*</span></label>
                                        <input type="text" name="enroll_no_of_adv" id="enroll_no_of_adv" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile: <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" id="mobile" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email: <span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card card-plain">
                                    <div class="card-body">
                                        <div class="row">
                                            <p>Please provide your response (1 = low; 5 = high)</p>
                                            <h3>Feedback on the Arbitrator(s)</h3>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">1. Understanding of the dispute <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="understanding_dispute" id="inlineRadio1" value="1">
                                                    <label class="form-check-label" for="inlineRadio1">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="understanding_dispute" id="inlineRadio2" value="2">
                                                    <label class="form-check-label" for="inlineRadio2">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="understanding_dispute" id="inlineRadio3" value="3">
                                                    <label class="form-check-label" for="inlineRadio3">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="understanding_dispute" id="inlineRadio4" value="4">
                                                    <label class="form-check-label" for="inlineRadio4">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="understanding_dispute" id="inlineRadio5" value="5">
                                                    <label class="form-check-label" for="inlineRadio5">5</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">2. Management of the arbitration process <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="manage_arb_process" id="inlineRadio11" value="1">
                                                    <label class="form-check-label" for="inlineRadio11">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="manage_arb_process" id="inlineRadio12" value="2">
                                                    <label class="form-check-label" for="inlineRadio12">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="manage_arb_process" id="inlineRadio13" value="3">
                                                    <label class="form-check-label" for="inlineRadio13">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="manage_arb_process" id="inlineRadio14" value="4">
                                                    <label class="form-check-label" for="inlineRadio14">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="manage_arb_process" id="inlineRadio15" value="5">
                                                    <label class="form-check-label" for="inlineRadio15">5</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">3. Expertise in procedural and substantive law <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="substantive_law" id="inlineRadio31" value="1">
                                                    <label class="form-check-label" for="inlineRadio31">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="substantive_law" id="inlineRadio32" value="2">
                                                    <label class="form-check-label" for="inlineRadio32">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="substantive_law" id="inlineRadio33" value="3">
                                                    <label class="form-check-label" for="inlineRadio33">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="substantive_law" id="inlineRadio34" value="4">
                                                    <label class="form-check-label" for="inlineRadio34">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="substantive_law" id="inlineRadio35" value="5">
                                                    <label class="form-check-label" for="inlineRadio35">5</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">4. Equal Treatment of the parties <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="treatment_of_party" id="inlineRadio41" value="1">
                                                    <label class="form-check-label" for="inlineRadio41">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="treatment_of_party" id="inlineRadio42" value="2">
                                                    <label class="form-check-label" for="inlineRadio42">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="treatment_of_party" id="inlineRadio43" value="3">
                                                    <label class="form-check-label" for="inlineRadio43">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="treatment_of_party" id="inlineRadio44" value="4">
                                                    <label class="form-check-label" for="inlineRadio44">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="treatment_of_party" id="inlineRadio45" value="5">
                                                    <label class="form-check-label" for="inlineRadio45">5</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row py-3">
                                <div class="card card-plain">
                                    <div class="card-body">
                                        <div class="row">
                                            <h3>Feedback on DIAC</h3>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label">1. Ambience, cleanliness and overall upkeep of the Centre <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio1" value="1">
                                                    <label class="form-check-label" for="diac_feed_Radio1">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio2" value="2">
                                                    <label class="form-check-label" for="diac_feed_Radio2">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio3" value="3">
                                                    <label class="form-check-label" for="diac_feed_Radio3">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio4" value="4">
                                                    <label class="form-check-label" for="diac_feed_Radio4">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio5" value="5">
                                                    <label class="form-check-label" for="diac_feed_Radio5">5</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_ambience_clean" id="diac_feed_Radio6" value="0">
                                                    <label class="form-check-label" for="diac_feed_Radio6">Did Not Use</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">2. Assistance rendered by Deputy Counsels/Case Managers in case management <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_21" value="1">
                                                    <label class="form-check-label" for="diac_feed_21">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_22" value="2">
                                                    <label class="form-check-label" for="diac_feed_22">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_23" value="3">
                                                    <label class="form-check-label" for="diac_feed_23">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_24" value="4">
                                                    <label class="form-check-label" for="diac_feed_24">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_25" value="5">
                                                    <label class="form-check-label" for="diac_feed_25">5</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_dc_cm" id="diac_feed_26" value="0">
                                                    <label class="form-check-label" for="diac_feed_26">Did Not Use</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">3. Assistance rendered by stenographers and other DIAC staff <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_31" value="1">
                                                    <label class="form-check-label" for="diac_feed_31">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_32" value="2">
                                                    <label class="form-check-label" for="diac_feed_32">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_33" value="3">
                                                    <label class="form-check-label" for="diac_feed_33">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_34" value="4">
                                                    <label class="form-check-label" for="diac_feed_34">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_35" value="5">
                                                    <label class="form-check-label" for="diac_feed_35">5</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_assist_st_other" id="diac_feed_36" value="0">
                                                    <label class="form-check-label" for="diac_feed_36">Did Not Use</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 mb-4 checkbox_radio_error_wrapper">
                                                <label class="form-label mb-2">4. Functioning of Information Technology infrastructure at the Centre <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="diac_feed_41" value="1">
                                                    <label class="form-check-label" for="diac_feed_41">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="diac_feed_42" value="2">
                                                    <label class="form-check-label" for="diac_feed_42">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="diac_feed_43" value="3">
                                                    <label class="form-check-label" for="diac_feed_43">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="diac_feed_44" value="4">
                                                    <label class="form-check-label" for="diac_feed_44">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="45" value="5">
                                                    <label class="form-check-label" for="45">5</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diac_it_infra" id="diac_feed_46" value="0">
                                                    <label class="form-check-label" for="diac_feed_46">Did Not Use</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
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
    $('#feedback_form').validate({
        errorClass: 'is-invalid text-danger',
        rules: {
            name_of_arb: {
                required: true
            },
            diac_case_no: {
                required: true
            },
            name_of_adv: {
                required: true
            },
            enroll_no_of_adv: {
                required: true
            },
            mobile: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            email: {
                required: true,
                email: true
            },
            understanding_dispute: {
                required: true
            },
            manage_arb_process: {
                required: true
            },
            substantive_law: {
                required: true
            },
            treatment_of_party: {
                required: true
            },
            diac_ambience_clean: {
                required: true
            },
            diac_assist_dc_cm: {
                required: true
            },
            diac_assist_st_other: {
                required: true
            },
            diac_it_infra: {
                required: true
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var url = base_url + "efiling/feedback/save";
            $.ajax({
                url: url,
                data: new FormData(document.getElementById("feedback_form")),
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    var response = JSON.parse(response);
                    if (response.status == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.msg,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = base_url + "efiling/feedback";
                            }
                        });
                    } else if (response.status == "validation_error") {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            html: response.msg,
                        })
                    } else if (response.status == false) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.msg,
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Server Error",
                        });
                    }
                },
                error: function (response) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Server Error, please try again",
                    });
                },
            });
        },
    })
</script>