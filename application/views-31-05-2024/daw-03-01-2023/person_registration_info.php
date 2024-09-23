<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!--Sweet Alert-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/Ionicons/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/dist/css/AdminLTE.min.css">

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Sweet Alert Plugin -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert/sweetalert.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <style>
        .wrapper_section {
            padding: 20px 0px;
        }

        .fieldset {
            border: 1px solid lightgrey;
            padding: 5px 20px;
            margin-bottom: 10px;
        }

        .legend {
            background-color: black;
            color: white;
            border-radius: 6px;
            width: auto;
            padding: 6px 13px;
            font-size: 14px;
        }

        .data_container {
            box-shadow: 0px 0px 10px 0px lightgray;
        }

        @media(max-width: 400px) {
            .wrapper_section {
                width: 400px;
                overflow-x: scroll;
            }
        }

        .download_container {
            margin-bottom: 10px;
        }

        .btn_download {
            box-shadow: 0px 0px 4px 0px darkred;
            background-image: linear-gradient(to right top, #f11218, #f20d24, #f20a2f, #f20a38, #f20d41);
            padding: 8px 18px;
        }
    </style>
</head>

<body>
    <section class="wrapper_section">
        <div class="container download_container text-right">
            <a href="<?= base_url('daw/registration/generate-public-single-pdf/' . $encrypted_id) ?>" class="btn btn-danger btn_download" target="_BLANK">
                <i class="fa fa-download"></i> Download
            </a>
        </div>
        <div class="container data_container">
            <div class="box-body">
                <div>
                    <table style="width: 100%;">
                        <tr>
                            <td width="200px">
                                <img src="<?= DAW_PHOTO_UPLOADS_FOLDER . $person['profile_photo'] ?>" alt="" style="width: 100%;">
                            </td>
                            <td width="50%">
                                <h4 style="margin-bottom: 5px;"><strong>Reg. No.:</strong></h4>
                                <h3 style="margin-top: 5px;"><strong><?= $person['reg_number'] ?></strong></h3>
                            </td>
                            <td width="30%" style="text-align: right;">
                                <img src="<?= base_url() . $person_qr_code ?>" alt="QR Code">
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="">
                    <fieldset class="fieldset">
                        <legend class="legend">Personal Information</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Title: </b>
                                    <p><?= $person['salutation_desc'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>First Name: </b>
                                    <p><?= $person['first_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Last Name: </b>
                                    <p><?= $person['last_name'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>Nickname/Preferred Name: </b>
                                    <p><?= $person['nick_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Email ID: </b>
                                    <p><?= $person['email_address'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Registrant Category: </b>
                                    <p><?= $person['registrant_category_desc'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Organization: </b>
                                    <p><?= $person['organization'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Date of registration: </b>
                                    <p><?= formatReadableDate($person['created_at']) ?></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>


                    <fieldset class="fieldset">
                        <legend class="legend">Address</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Country: </b>
                                    <p><?= $person['country_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>State : </b>
                                    <p><?= $person['state_name'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>City : </b>
                                    <p><?= $person['city'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>Address Line 1: </b>
                                    <p><?= $person['address_line_1'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Address Line 2: </b>
                                    <p><?= $person['address_line_2'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Address Line 3: </b>
                                    <p><?= $person['address_line_3'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Pincode: </b>
                                    <p><?= $person['pincode'] ?></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="legend">Contact Details</legend>
                        <div class="fieldset-content-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Mobile Number: </b>
                                    <p><?= $person['mobile_no'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Telephone: </b>
                                    <p><?= $person['telephone'] ?></p>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-12 form-group">
                                    <b>Additional Information/Remarks: </b>
                                    <p><?= $person['remarks'] ?></p>
                                </div>

                                <div class="col-md-4 form-group">
                                    <b>How did you hear about this event?: </b>
                                    <p><?= $person['hear_about_event_desc'] ?></p>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
            </div>
        </div>
    </section>
</body>

</html>