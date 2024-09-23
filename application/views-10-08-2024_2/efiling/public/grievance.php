<main class="main-content mt-0">
    <section class="">
        <div class="page-body">
            <div class="container">
                <div class="card mt-4 mb-4 bg-white rounded shadow">
                    <div class="card-header pt-4 flex-column align-items-start">
                        <h5 class="card-title text-danger">Grievance Redressal Form</h5>
                    </div>
                    <div class="card-body">
                        <form id="grievance_form" class="grievance_form" method="POST">
                            <input type="hidden" name="csrf_frm_token" value="<?php echo generateToken('csrf_grievance_form'); ?>">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Name of Arbitrator: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name_of_arb" id="name_of_arb">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">DIAC Case Number: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="diac_case_no" id="diac_case_no">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Name of person filling the form: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name_of_form_filled_by" id="name_of_form_filled_by">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Whether you have participated in arbitration as a counsel or party: <span class="text-danger">*</span></label>
                                        <select name="participated_arb_counsel" id="" class="form-control">
                                            <option value="">Select an option</option>
                                            <option value="0">As Counsel</option>
                                            <option value="1">As Party</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Phone: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" id="phone">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Email: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                                <div class="row">
                                    <p>Grievance with respect to (please tick the appropriate field):</p>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">1. Arbitrator's: <span class="text-danger">*</span></label>
                                            <?php foreach ($arb_opt as $arb_opt): ?>
                                                <div class="form-check mx-3">
                                                    <input class="form-check-input" name="arb_grievance_opt[]" type="checkbox" value="<?= $arb_opt['gen_code'] ?>" id="<?= $arb_opt['gen_code'] ?>">
                                                    <label class="form-check-label" for="<?= $arb_opt['gen_code'] ?>">
                                                       <?= $arb_opt['description'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">2. Deputy Counsel’s/Case Manager’s/Stenographers: <span class="text-danger">*</span></label>
                                            <?php foreach ($dc_cm_st_opt as $dc_opt): ?>
                                                <div class="form-check mx-3">
                                                    <input class="form-check-input" name="grievance_dc_cm_st[]" type="checkbox" value="<?= $dc_opt['gen_code'] ?>" id="<?= $dc_opt['gen_code'] ?>">
                                                    <label class="form-check-label" for="<?= $dc_opt['gen_code'] ?>">
                                                    <?= $dc_opt['description'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">3. Pantry and other staff: <span class="text-danger">*</span></label>
                                            <?php foreach ($pantry_others_opt as $pan_opt): ?>
                                                <div class="form-check mx-3">
                                                    <input class="form-check-input" name="grievance_pantry_other[]" type="checkbox" value="<?= $pan_opt['gen_code'] ?>" id="<?= $pan_opt['gen_code'] ?>">
                                                    <label class="form-check-label" for="<?= $pan_opt['gen_code'] ?>">
                                                    <?= $pan_opt['description'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Please provide your comments? <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="grievance_comments" id="grievance_comments" rows="7"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!--  -->
<script>
     $('#grievance_form').validate({
        errorClass: 'is-invalid',
        rules: {
            name_of_arb: {
                required: true
            },
            diac_case_no: {
                required: true
            },
            name_of_form_filled_by: {
                required: true
            },
            participated_arb_counsel: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            arb_grievance_opt: {
                required: true,
            },
            grievance_dc_cm_st: {
                required: true,
                minlength: 1
            },
            grievance_dc_cm_st: {
                required: true,
                minlength: 1
            },
            grievance_comments: {
                required: true,
                minlength: 1
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var url = base_url + "efiling/grievance/store";
            $.ajax({
                url: url,
                data: new FormData(document.getElementById("grievance_form")),
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {
                    var response = JSON.parse(response);
                    if (response.status) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.msg,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = base_url + "efiling/grievance";
                            }
                        });
                    } else if (response.status == "validation_error") {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: response.msg,
                        });
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
 