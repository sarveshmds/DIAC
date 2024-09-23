<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/css/buttons.dataTables.min.css" />
<style>
    .pdf_button {
        background-color: red;
        color: white;
    }

    .daw_profile_photo_col {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border: 1px solid #d5d5d5;
        /* padding: 12px; */
        border-radius: 4px;
        overflow: hidden;
    }

    .daw_profile_photo {
        object-fit: contain;
        width: 100%;
    }
</style>
<div class="content-wrapper">
    <?php require_once(APPPATH . 'views/templates/components/content-header.php') ?>
    <section class="content">
        <div class="box wrapper-box">
            <div class="box-body">
                <div class="text-right">
                    <form action="<?= base_url('daw-2024/registration/generate-pdf') ?>" method="post" target="_BLANK">
                        <input type="hidden" name="filter_data" value='<?= json_encode(['filter_data' => [
                                                                            'f_reg_number' => $registration_data['reg_number']
                                                                        ]]) ?>'>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-download"></i> Download
                        </button>
                    </form>
                </div>
                <div class="">
                    <fieldset class="fieldset bg-white">
                        <legend class="legend">Personal Information</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-3 col-xs-12">
                                    <div class="daw_profile_photo_col">
                                        <img src="<?= base_url() . VIEW_DAW_PHOTO_UPLOADS_FOLDER . $registration_data['profile_photo'] ?>" alt="" class="daw_profile_photo">
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Title: </b>
                                            <p><?= $registration_data['salutation_desc'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>First Name: </b>
                                            <p><?= $registration_data['first_name'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Last Name: </b>
                                            <p><?= $registration_data['last_name'] ?></p>
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <b>Nickname/Preferred Name: </b>
                                            <p><?= $registration_data['nick_name'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Email ID: </b>
                                            <p><?= $registration_data['email_address'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Registrant Category: </b>
                                            <p><?= $registration_data['registrant_category_desc'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b> Registered for Gar Live: </b>
                                            <p>
                                            <?php 
                                                if($registration_data['registration_for_gar_live'] == 1){
                                                    echo "Yes";
                                                }elseif($registration_data['registration_for_gar_live'] == 0){
                                                    echo 'No';
                                                } 
                                            ?>
                                            </p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Organization: </b>
                                            <p><?= $registration_data['organization'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Designation: </b>
                                            <p><?= $registration_data['designation'] ?></p>
                                        </div>

                                        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Date of registration: </b>
                                            <p><?= formatReadableDate($registration_data['created_at']) ?></p>
                                        </div>
                                         <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                            <b>Registered For Training Session: </b>
                                            <p>
                                                <?php 
                                                if($registration_data['registration_for_training_session'] == 'session_i'){
                                                    echo "Session I";
                                                }elseif($registration_data['registration_for_training_session'] == 'session_ii'){
                                                    echo 'Session II';
                                                }else {
                                                    {
                                                        echo 'Not Registered';
                                                    }
                                                } 
                                            ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </fieldset>


                    <fieldset class="fieldset bg-white">
                        <legend class="legend">Address</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Country: </b>
                                    <p><?= $registration_data['country_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>State : </b>
                                    <p><?= $registration_data['state_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>City : </b>
                                    <p><?= $registration_data['city'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>Address Line 1: </b>
                                    <p><?= $registration_data['address_line_1'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Address Line 2: </b>
                                    <p><?= $registration_data['address_line_2'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Address Line 3: </b>
                                    <p><?= $registration_data['address_line_3'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Pincode: </b>
                                    <p><?= $registration_data['pincode'] ?></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset bg-white">
                        <legend class="legend">Contact Details</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Mobile Number: </b>
                                    <p><?= (($registration_data['mobile_no_country_code_desc']) ? $registration_data['mobile_no_country_code_desc'] . '-' : '') . $registration_data['mobile_no'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Telephone: </b>
                                    <p><?= $registration_data['telephone'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Additional Information/Remarks: </b>
                                    <p><?= $registration_data['remarks'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>How did you hear about this event?: </b>
                                    <p><?= $registration_data['hear_about_event_desc'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>Application Status: </b>
                                    <p><?php
                                        if ($registration_data['application_status'] == 1) {
                                            echo '<span class="badge bg-green">Approved</span>';
                                        } elseif ($registration_data['application_status'] == 2) {
                                            echo '<span class="badge bg-red">Rejected</span>';
                                        } elseif ($registration_data['application_status'] == 3) {
                                            echo '<span class="badge bg-blue">Payment Pending</span>';
                                        } elseif ($registration_data['application_status'] == 4) {
                                            echo '<span class="badge bg-olive">Payment Received</span>';
                                        } else {
                                            echo '<span class="badge">Pending</span>';
                                        }
                                        ?></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <hr>

                    <form action="" method="post" id="approval_form">
                        <input type="hidden" name="hidden_id" value="<?= $registration_data['id'] ?>">
                        <div class="alert alert-custom">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="application_status">Application Status <span class="text-danger">*</span></label>
                                        <select name="application_status" id="application_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="3">Payment Pending</option>
                                            <option value="4">Payment Received</option>
                                            <option value="1">Approve</option>
                                            <option value="2">Reject</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 form-group">
                                    <p class="">
                                        A confirmation mail will be sent to user.
                                        Along with the registration pdf.
                                    </p>
                                </div>

                                <div class="col-xs-12 text-center form-group">
                                    <button type="submit" class="btn btn-success" id="btn_daw_approve">
                                        <i class="fa fa-check"></i> Submit
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>



<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-excelexport/js/buttons.html5.min.js"></script>
<script>
    var base_url = "<?php echo base_url(); ?>";
    var role_code = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script>
    $('#approval_form').on('submit', function(event) {
        event.preventDefault();

        swal({
                title: "Are you sure?",
                text: "Confirmation mail will be send based on application status selected by you.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#18d116",
                confirmButtonText: "Yes, Confirm!",
                cancelButtonText: "No, cancel please!",
                closeOnCancel: true,
                closeOnConfirm: false,
            },
            function(isConfirm) {
                if (isConfirm) {

                    var formData = new FormData(document.getElementById('approval_form'))

                    $.ajax({
                        url: base_url + "daw-2024/registration/approval",
                        method: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            try {
                                var obj = JSON.parse(response);
                                if (obj.status == true) {

                                    swal({
                                        title: "Success",
                                        text: obj.msg,
                                        type: "success",
                                        html: true,
                                    }, function() {
                                        window.location.reload();
                                    });

                                } else if (obj.status === false) {
                                    swal({
                                        title: "Error",
                                        text: obj.msg,
                                        type: "error",
                                        html: true,
                                    });
                                } else {
                                    toastr.error('Something went wrong. Please try again or contact support.')
                                }
                            } catch (e) {
                                sweetAlert("Sorry", "Unable to Save.Please Try Again !", "error");
                            }
                        },
                        error: function(err) {
                            toastr.error("Server error, please try again or contact support.");
                        },
                    });
                } else {
                    swal.close()
                }
            });
    })
</script>