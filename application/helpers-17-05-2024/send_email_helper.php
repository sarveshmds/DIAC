<?php

require_once APPPATH . "third_party/phpmailer/src/PHPMailer.php";
require_once APPPATH . "third_party/phpmailer/src/Exception.php";
require_once APPPATH . "third_party/phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "")
{
	try {
		//$mail->Username   = "diacservice@gmail.com";
		//$mail->Password   = "DIACp@ssw0rd"; 

		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0;
		// enable SMTP authentication
		$mail->SMTPAuth   = true;
		// sets the prefix to the server
		$mail->SMTPSecure = 'tls';
		// sets GMAIL as the SMTP server
		$mail->Host       = 'email-smtp.ap-south-1.amazonaws.com';
		// set the SMTP port for the GMAIL server 25 OR 465 OR 587
		$mail->Port       = 587;
		// GMAIL username 
		$mail->Username   = 'AKIAXP347YP57WRUQXOO';
		// GMAIL password  
		$mail->Password   = 'BFmM+D1/x8j9s1dx9sFvCuh6t6U5iNa5VWgfQ7KbBaAR';
		$mail->setFrom('contact@stlindia.com');
		$mail->Subject    = $subject;
		$mail->msgHTML($body);

		$emailAddHolder = explode(",", $to_email_id);

		if ($cc_email_id != "" || $cc_email_id != null) {
			$ccEmailAddHolder = explode(",", $cc_email_id);
		} else {
			$ccEmailAddHolder = array();
		}

		if ($bcc_email_id != "" || $bcc_email_id != null) {
			$bccEmailAddHolder = explode(",", $bcc_email_id);
		} else {
			$bccEmailAddHolder = array();
		}

		for ($index = 0; $index < count($emailAddHolder); $index++) {
			$mail->AddAddress($emailAddHolder[$index]);
		}
		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}
		for ($index = 0; $index < count($bccEmailAddHolder); $index++) {
			$mail->AddBCC($bccEmailAddHolder[$index]);
		}
		if (!$mail->send()) {
			return array('status' => false, 'msg' => "Mailer Error: " . $mail->ErrorInfo);
		} else {
			return array('status' => true, 'msg' => "Email Sent Successfully");
		}
	} catch (Exception $e) {
		return array('status' => false, 'msg' => "Mailer failed while sending mail.");
	}
}

/**
 * This function is using to send the plain emails without attachments
 */
function sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "")
{

	try {

		// $email_data = get_email_data();

		// $smtpFromName = 'Delhi International Arbitration Centre (DIAC)';
		$smtpFromAddress = 'noreply@diac.ind.in';
		$smtpSecure = 'tls';

		$smtpHost = 'smtp.gmail.com';
		$smtpPort = 587;

		$smtpUsername = 'noreply@diac.ind.in';
		$smtpPassword = 'Noreplydiac1!';

		$cc_email_id = (isset($email_data['cc_email_id']) && !empty($email_data['cc_email_id'])) ? $email_data['cc_email_id'] : "";


		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0;
		// enable SMTP authentication
		$mail->SMTPAuth   = true;
		// sets the prefix to the server
		$mail->SMTPSecure = $smtpSecure;
		// sets GMAIL as the SMTP server
		$mail->Host       = $smtpHost;
		// set the SMTP port for the GMAIL server 587 OR 465 OR 587
		$mail->Port       = $smtpPort;
		// GMAIL username 
		$mail->Username   = $smtpUsername;
		// GMAIL password  
		$mail->Password   = $smtpPassword;
		$mail->setFrom($smtpFromAddress);
		$mail->Subject    = $subject;
		$mail->MsgHTML($body);

		$emailAddHolder = explode(",", $to_email_id);

		// ===============================================
		if (!empty($cc_email_id)) {
			$ccEmailAddHolder = explode(",", $cc_email_id);
			$ccEmailAddHolder = array_map('trim', $ccEmailAddHolder);
		} else {
			$ccEmailAddHolder = array();
		}

		if ($bcc_email_id != "" || $bcc_email_id != null) {
			$bccEmailAddHolder = explode(",", $bcc_email_id);
		} else {
			$bccEmailAddHolder = array();
		}

		// ===============================================
		for ($index = 0; $index < count($emailAddHolder); $index++) {
			$mail->AddAddress($emailAddHolder[$index]);
		}

		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}

		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}

		for ($index = 0; $index < count($bccEmailAddHolder); $index++) {
			$mail->AddBCC($bccEmailAddHolder[$index]);
		}

		if ($mail->send()) {
			return array('status' => true, 'msg' => "Email Sent Successfully");
		} else {
			return array('status' => false, 'msg' => "Mailer Error: " . $mail->ErrorInfo);
		}
	} catch (Exception $e) {
		return array('status' => false, 'msg' => "Mailer failed while sending mail.");
	}
}

/**
 * This function is using to send email with single attachments
 */
function sendEmailsWithAttachment($to_email_id, $subject, $body, $attachment, $cc_email_id = "", $bcc_email_id = "")
{

	try {
		$email_data = get_email_data();

		$smtpFromAddress = $email_data['email_id'];
		$smtpSecure = $email_data['smtp_secure'];

		$smtpHost = $email_data['host_name'];
		$smtpPort = $email_data['port_no'];

		$smtpUsername = $email_data['username'];
		$smtpPassword = $email_data['password'];


		$cc_email_id = (isset($email_data['cc_email_id']) && !empty($email_data['cc_email_id'])) ? $email_data['cc_email_id'] : "";

		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0;
		// enable SMTP authentication
		$mail->SMTPAuth   = true;
		// sets the prefix to the server
		$mail->SMTPSecure = $smtpSecure;
		// sets GMAIL as the SMTP server
		$mail->Host       = $smtpHost;
		// set the SMTP port for the GMAIL server 587 OR 465 OR 587
		$mail->Port       = $smtpPort;
		// GMAIL username 
		$mail->Username   = $smtpUsername;
		// GMAIL password  
		$mail->Password   = $smtpPassword;
		$mail->setFrom($smtpFromAddress);
		$mail->Subject    = $subject;
		$mail->MsgHTML($body);

		$emailAddHolder = explode(",", $to_email_id);

		if ($cc_email_id != "" || $cc_email_id != null) {
			$ccEmailAddHolder = explode(",", $cc_email_id);
		} else {
			$ccEmailAddHolder = array();
		}

		if ($bcc_email_id != "" || $bcc_email_id != null) {
			$bccEmailAddHolder = explode(",", $bcc_email_id);
		} else {
			$bccEmailAddHolder = array();
		}

		for ($index = 0; $index < count($emailAddHolder); $index++) {
			$mail->AddAddress($emailAddHolder[$index]);
		}
		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}
		for ($index = 0; $index < count($bccEmailAddHolder); $index++) {
			$mail->AddBCC($bccEmailAddHolder[$index]);
		}

		//Attachments
		$mail->addAttachment($attachment);

		if (!$mail->send()) {
			return array('status' => false, 'msg' => "Mailer Error: " . $mail->ErrorInfo);
		} else {
			return array('status' => true, 'msg' => "Email Sent Successfully");
		}
	} catch (Exception $e) {
		return array('status' => false, 'msg' => "Mailer failed while sending mail.");
	}
}

/**
 * This function is using to send email with multiple attachments
 */
function sendEmailsWithMultipleAttachment($to_email_id, $subject, $body, $attachments, $cc_email_id = "", $bcc_email_id = "")
{

	try {

		$email_data = get_email_data();

		$smtpFromAddress = $email_data['email_id'];
		$smtpSecure = $email_data['smtp_secure'];

		$smtpHost = $email_data['host_name'];
		$smtpPort = $email_data['port_no'];

		$smtpUsername = $email_data['username'];
		$smtpPassword = $email_data['password'];


		$cc_email_id = (isset($email_data['cc_email_id']) && !empty($email_data['cc_email_id'])) ? $email_data['cc_email_id'] : "";

		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0;
		// enable SMTP authentication
		$mail->SMTPAuth   = true;
		// sets the prefix to the server
		$mail->SMTPSecure = $smtpSecure;
		// sets GMAIL as the SMTP server
		$mail->Host       = $smtpHost;
		// set the SMTP port for the GMAIL server 587 OR 465 OR 587
		$mail->Port       = $smtpPort;
		// GMAIL username 
		$mail->Username   = $smtpUsername;
		// GMAIL password  
		$mail->Password   = $smtpPassword;
		$mail->setFrom($smtpFromAddress);
		$mail->Subject    = $subject;
		$mail->MsgHTML($body);

		$emailAddHolder = explode(",", $to_email_id);

		if ($cc_email_id != "" || $cc_email_id != null) {
			$ccEmailAddHolder = explode(",", $cc_email_id);
		} else {
			$ccEmailAddHolder = array();
		}

		if ($bcc_email_id != "" || $bcc_email_id != null) {
			$bccEmailAddHolder = explode(",", $bcc_email_id);
		} else {
			$bccEmailAddHolder = array();
		}

		for ($index = 0; $index < count($emailAddHolder); $index++) {
			$mail->AddAddress($emailAddHolder[$index]);
		}
		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}
		for ($index = 0; $index < count($bccEmailAddHolder); $index++) {
			$mail->AddBCC($bccEmailAddHolder[$index]);
		}

		//Attachments
		if (count($attachments) > 0) {
			for ($i = 0; $i < count($attachments); $i++) {
				$mail->addAttachment($attachments[$i]);
			}
		}

		if (!$mail->send()) {
			return array('status' => false, 'msg' => "Mailer Error: " . $mail->ErrorInfo);
		} else {
			return array('status' => true, 'msg' => "Email Sent Successfully");
		}
	} catch (Exception $e) {
		return array('status' => false, 'msg' => "Mailer failed while sending mail.");
	}
}


// ---------------------------------------------------------
// ---------------------------------------------------------
// FOR DAW 2024
// ---------------------------------------------------------
// ---------------------------------------------------------
function sendEmailsForDaw2024(
	$to_email_id,
	$subject,
	$body,
	$cc_email_id = "",
	$bcc_email_id = ""
) {
	try {
		//$mail->Username   = "diacservice@gmail.com";
		//$mail->Password   = "DIACp@ssw0rd"; 

		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = 0;
		// enable SMTP authentication
		$mail->SMTPAuth   = true;
		// sets the prefix to the server
		$mail->SMTPSecure = 'tls';
		// sets GMAIL as the SMTP server
		$mail->Host       = 'smtp-relay.gmail.com';
		// set the SMTP port for the GMAIL server 25 OR 465 OR 587
		$mail->Port       = 465;
		// GMAIL username 
		$mail->Username   = 'no-reply@daw.ind.in';
		// GMAIL password  
		$mail->Password   = 'VJy7EjXJjFAG7yY*';
		$mail->setFrom('no-reply@daw.ind.in');
		$mail->Subject    = $subject;
		$mail->msgHTML($body);

		$emailAddHolder = explode(",", $to_email_id);

		if ($cc_email_id != "" || $cc_email_id != null) {
			$ccEmailAddHolder = explode(",", $cc_email_id);
		} else {
			$ccEmailAddHolder = array();
		}

		if (
			$bcc_email_id != "" || $bcc_email_id != null
		) {
			$bccEmailAddHolder = explode(",", $bcc_email_id);
		} else {
			$bccEmailAddHolder = array();
		}

		for ($index = 0; $index < count($emailAddHolder); $index++) {
			$mail->AddAddress($emailAddHolder[$index]);
		}
		for ($index = 0; $index < count($ccEmailAddHolder); $index++) {
			$mail->AddCC($ccEmailAddHolder[$index]);
		}
		for ($index = 0; $index < count($bccEmailAddHolder); $index++) {
			$mail->AddBCC($bccEmailAddHolder[$index]);
		}
		if (!$mail->send()) {
			return array('status' => false, 'msg' => "Mailer Error: " . $mail->ErrorInfo);
		} else {
			return array('status' => true, 'msg' => "Email Sent Successfully");
		}
	} catch (Exception $e) {
		return array('status' => false, 'msg' => "Mailer failed while sending mail.");
	}
}
