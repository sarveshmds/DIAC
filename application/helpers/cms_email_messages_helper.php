<?php

/**
 * Function to send the email to everyone related to case
 */
function case_registered_info_to_all($email_ids, $subject, $email_body, $smtp_email_id = '')
{

    $to_email_id = $email_ids;
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = $subject;
    $body = $email_body;

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $smtp_email_id);
    return $sendEmailStatus;
}

/**
 * Function to send the email to everyone related to case
 */
function hearing_notice_info_to_all($email_ids, $subject, $email_body, $smtp_email_id = '')
{

    $to_email_id = $email_ids;
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = $subject;
    $body = $email_body;

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $smtp_email_id);
    return $sendEmailStatus;
}

/* 
    // function for send email to everyone about common hearing notice
*/

function  common_email_sending_info_to_all($email_ids, $subject, $email_body, $smtp_email_id = '')
{
    $to_email_id = $email_ids;
    $cc_email_id = '';
    $bcc_email_id = '';
    $subject = $subject;
    $body = $email_body;

    $sendEmailStatus = sendEmails($to_email_id, $subject, $body, $smtp_email_id);
    return $sendEmailStatus;
}
