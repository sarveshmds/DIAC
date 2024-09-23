<div class="fc_card_body">
    <div class="fc_body_content">
        <div class="">
            <table style="width: 100%;">
                <tr>
                    <td>
                        <img src="<?= DAW_PHOTO_UPLOADS_FOLDER . $person['profile_photo'] ?>" alt="" style="width: 100px;">
                    </td>
                    <td width="40%" style="text-align: right;">
                        <img src="<?= $person_qr_code ?>" alt="">
                    </td>
                </tr>
            </table>
            <fieldset class="fieldset mb-10" style="margin-top: 10px;">
                <legend class="legend">Personal Information</legend>
                <div class="fieldset-content-box">
                    <table class="table person_info_tbl">
                        <tr>
                            <td class="data_col">
                                <b>Reg. No.: </b>
                                <p><?= $person['reg_number'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Full Name: </b>
                                <p><?= $person['salutation_desc'] . ' ' . $person['first_name'] . ' ' . $person['last_name'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Nickname/Preferred Name: </b>
                                <p><?= $person['nick_name'] ?></p>
                            </td>
                        </tr>

                        <tr>

                            <td class="data_col">
                                <b>Email ID: </b>
                                <p><?= $person['email_address'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Registrant Category: </b>
                                <p><?= $person['registrant_category_desc'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Organization: </b>
                                <p><?= $person['organization'] ?></p>
                            </td>

                        </tr>

                        <tr>

                            <td class="data_col">
                                <b>Designation: </b>
                                <p><?= $person['designation'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Date of registration: </b>
                                <p><?= formatReadableDate($person['created_at']) ?></p>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </fieldset>


            <fieldset class="fieldset mb-10">
                <legend class="legend">Address</legend>
                <div class="fieldset-content-box">
                    <table class="table person_info_tbl">
                        <tr>
                            <td class="data_col">
                                <b>Country: </b>
                                <p><?= $person['country_name'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>State : </b>
                                <p><?= $person['state_name'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>City : </b>
                                <p><?= $person['city'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td class="data_col">
                                <b>Address Line 1: </b>
                                <p><?= $person['address_line_1'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Address Line 2: </b>
                                <p><?= $person['address_line_2'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Address Line 3: </b>
                                <p><?= $person['address_line_3'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td class="data_col">
                                <b>Pincode: </b>
                                <p><?= $person['pincode'] ?></p>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </fieldset>

            <fieldset class="fieldset">
                <legend class="legend">Contact Details</legend>
                <div class="fieldset-content-box">
                    <table class="table">
                        <tr>
                            <td class="data_col">
                                <b>Mobile Number: </b>
                                <p><?= (($person['mobile_no_country_code_desc']) ? $person['mobile_no_country_code_desc'] . '-' : '') . $person['mobile_no'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Telephone: </b>
                                <p><?= $person['telephone'] ?></p>
                            </td>

                            <td class="data_col">
                                <b>Additional Information/Remarks: </b>
                                <p><?= $person['remarks'] ?></p>
                            </td>
                        </tr>

                        <tr>
                            <td class="data_col">
                                <b>How did you hear about this event?: </b>
                                <p><?= $person['hear_about_event_desc'] ?></p>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </fieldset>

        </div>
    </div>
</div>