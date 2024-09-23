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

function sendEmails($to_email_id, $subject, $body, $cc_email_id = "", $bcc_email_id = "")
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
		$mail->SMTPSecure = 'STARTLS';
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


function sendEmailsWithAttachment($to_email_id, $subject, $body, $attachment, $cc_email_id = "", $bcc_email_id = "")
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
