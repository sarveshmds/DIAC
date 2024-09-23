<?php

// Function to send the mail when user created in efiling

function email_efiling_user_created_success($user_data, $password)
{

    $to_email_id = $user_data['email'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'E-filing DIAC: User created successfully';
    $body = "Respected " . $user_data['user_display_name'] . ",<br>
                            <p>Your account has been created in e-filing DIAC. Below is your user id and password</p> 
                            <p>User ID: " . $user_data['phone_number'] . "</p>
                            <p>Password: " . $password['password'] . "</p>
                            <p>Note: Do not this detail with anyone.</p>";

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}

function new_ref_registered_claimant($new_reference, $claimant)
{

    $to_email_id = $claimant['email_id'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'New Reference Registered in E-filing(DIAC)';
    $body = '
        <p>Respected ' . $claimant['name'] . '</p>
        <p>A new reference is registered on e-filing. Please wait for approval. We are reviewing your application, it will take few days.</p>
        <p>Diary Number: ' . $new_reference['diary_number'] . '</p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}


function case_registered_claimant($case_details_data, $claimant)
{

    $to_email_id = $claimant['email_id'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'Case Registered Successfully - DIAC';
    $body = '
        <p>Respected ' . $claimant['name'] . ',</p>
        <p>Your case is successfully registered in DIAC.</p>
        <p><strong>DIAC Registration No.: ' . $case_details_data['case_no'] . '</strong></p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}


function case_registered_respondant($case_details_data, $respondant)
{

    $to_email_id = $respondant['email_id'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'Case Registered Against You In DIAC';
    $body = '
        <p>Respected ' . $respondant['name'] . '</p>
        <p>A case is registered in DIAC against you.</p>
        <p><strong>DIAC Registration No.: ' . $case_details_data['case_no'] . '</strong></p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}


function forget_password_otp($user_data, $otp)
{

    $to_email_id = $user_data['email'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'One time password for forget password in DIAC.';
    $body = '
        <p>Dear ' . $user_data['user_display_name'] . '</p>
        <p>Below is your one time password.</p>
        <p>OTP: <b>' . $otp . '</b></p>
        <p>This otp is only valid for 2 hours.</p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}


function send_response_format($data)
{

    $to_email_id = $data['email_to'];
    $cc_email_id = $data['cc'];
    $bcc_email_id = $data['bcc'];
    $attachment = $data['file_path'];
    $subject = $data['subject'];

    $body = '
        <p>This mail is regarding your case in DIAC.</p>
        <p>Please find attached document.</p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmailsWithAttachment($to_email_id, $subject, $body, $attachment, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}


function send_arbitrator_for_consent($at_data, $case_data = '')
{

    $to_email_id = $at_data['email'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'Provide consent regarding the case in DIAC-';
    $body = '
        <p>Respected ' . $at_data['name_of_arbitrator'] . '</p>
        <p>You are appointed as a arbitrator in DIAC case number.</p>
        <p>Please provide us the consent for the following case.</p>
        <p><strong>Note:</strong></p>
        <p><small>If you want to submit the consent online then create your account in our efiling portal with your registered email id and mobile number which is ' . $at_data['email'] . ' & ' . $at_data['contact_no'] . '</small></p>
        <p><small>If already have an account then submit your consent with that credentials.</small></p>
        <p>
            Thanks & Regards <br />
            ' . DEPARTMENT_NAME . ' <br />
            ' . DEPARTMENT_ADDRESS . ' <br />
            ' . DEPARTMENT_PHONE . ' <br />
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "");
    return $sendEmailStatus;
}


// DAW ======================================================

// Send daw registration success mail after submitting the register form
function send_daw_registration_success_mail($data)
{

    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'DAW - 2023 registration submitted successfully.';

    $body = '
        <p>Respected Sir/Ma\'am </p>
        <p>Thank you for your interest in our upcoming event <b>DAW 2023</b>. We have your contact details and shall get back to you, shortly.</p>
        <p>Please note that submitting an Application for Registration via our webpage does not confirm any registration for the event and the same if any shall be sent at a later stage.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2023
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}


// Send confirmation mail
function send_daw_registration_approval_mail($data, $attachment)
{

    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $attachment = $attachment;
    $subject = 'DAW - 2023 your Application for Registration has been confirmed';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>Congratulations, your Application for Registration has been confirmed!</p>
        <p>Kindly refer to the attachment in this email which should mention your details along with a unique QR code required at the time of entry for our event DAW 2023. In case of a discrepancy and issues please refer to the contact details mentioned herein. Please note that this does not guarantee confirmation for a particular Session or a Day since they are on a first come first basis only.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2023
        </p>
    ';

    $sendEmailStatus = sendEmailsWithAttachment($to_email_id, $subject, $body, $attachment, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}


// Send daw registration rejection mail
function send_daw_registration_rejection_mail($data)
{

    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'DAW - 2023 your Application for Registration has been rejected';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>We regret to inform that your Application for Registration could not be confirmed having reached a maximum capacity of people for our event, DAW 2023. However, you can still attend the event as it would be live streamed details of which would be available on our website <a href="http://dhcdiac.nic.in" target="_BLANK">http://dhcdiac.nic.in</a>.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2023
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}


// =============================================================
// =============================================================
// ================= Daw Email functions for 2024 ==============
// =============================================================
// =============================================================


// Send confirmation mail
function send_daw_registration_approval_mail_2024($data, $attachment)
{

    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $attachment = $attachment;
    $subject = 'DAW - 2024 your Application for Registration has been confirmed';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>Congratulations, your Application for Registration has been confirmed!</p>
        <p>Kindly refer to the attachment in this email which should mention your details along with a unique QR code required at the time of entry for our event DAW 2024. In case of a discrepancy and issues please refer to the contact details mentioned herein. Please note that this does not guarantee confirmation for a particular Session or a Day since they are on a first come first basis only.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2024
        </p>
    ';

    $sendEmailStatus = sendEmailsWithAttachment($to_email_id, $subject, $body, $attachment, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}


// Send daw registration rejection mail
function send_daw_registration_rejection_mail_2024($data)
{

    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'DAW - 2024 your Application for Registration has been rejected';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>We regret to inform that your Application for Registration could not be confirmed having reached a maximum capacity of people for our event, DAW 2024. However, you can still attend the event as it would be live streamed details of which would be available on our website <a href="http://dhcdiac.nic.in" target="_BLANK">http://dhcdiac.nic.in</a>.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2024
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}

// Send Daw Payment Pending mail 
function send_daw_payment_pending_mail_2024($data)
{
    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'DAW - 2024 your Application for Registration is in Payment Pending';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>Payment pending <a href="http://dhcdiac.nic.in" target="_BLANK">http://dhcdiac.nic.in</a>.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2024
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}

// Send Daw Payment Received Mail 
function send_daw_payment_received_mail_2024($data)
{
    $to_email_id = $data['email_address'];
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = 'DAW-2024: Payment received for your payment';

    $body = '
        <p>Respected Sir/Ma\'am</p>
        <p>Payment received <a href="http://dhcdiac.nic.in" target="_BLANK">http://dhcdiac.nic.in</a>.</p>
        <p>
            Regards <br />
            Working committee <br />
            DAW 2024
        </p>
    ';

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $cc_email_id, $bcc_email_id);
    return $sendEmailStatus;
}
