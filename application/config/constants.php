<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('NO_OPERATION', 'No Operation Found!');
define('OP_SUCCESS', 'Operation success');
define('OP_FAILED', 'Operation failed!');
define('LOGIN_URL', 'https://dhcdiac.nic.in/webapp/login');

define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);

// ==============================================
//constants used in JWT
// ==============================================
define('KEY', 'GS_JWT_KEY');
define('ISS', 'localhost'); //Issuer Server Name
define('AUD', 'localhost');
define('IAT', time()); //Isseued at time()
define('NBF', IAT + 10); //Not Before IAT + 10 second
define('EXPIRE', NBF + 60); //Adding 60 second

// DIAC Details ==========================================================
define('DEPARTMENT_NAME', 'Delhi International Arbitration Centre (DIAC)');
define('DEPARTMENT_ADDRESS', 'Delhi High Court Campus, S- Block, Zakir Hussain Marg, New Delhi- 110503');
define('DEPARTMENT_PHONE', '011-23386492');
define('DEPARTMENT_FAX', '011-23386493');
define('DEPARTMENT_WEBSITE', 'http://dhcdiac.nic.in/');
define('DEPARTMENT_COMMON_EMAIL', 'delhiarbitrationcentre@gmail.com');
define('DEPARTMENT_EFILING_EMAIL', 'delhiarbitrationcentre@gmail.com');

define('DEPARTMENT_ACC_NO', '15530210000663');
define('DEPARTMENT_IFSC', 'UCBA0001553');
define('DEPARTMENT_BANK_NAME', 'UCO Bank');
define('DEPARTMENT_MICR_CODE', '110028026');
define('DEPARTMENT_BANK_BRANCH', 'Delhi High Court, Sher Shah Road, New Delhi-110003');

define('NEW_FILING_AMOUNT', 10000);

define('ARB_FEES_SHARE_PAYABLE_PERCENT', 25);

define('CONSTANTS_LIST', [
    'EFILING' => 'E-filing',
    'REFERRALS_REQUESTS' => 'Referral/Requests',
    'DIRECT_ORDERS' => 'Direct Orders',
    'NEW_REFERENCE' => 'New Reference',
    'MISCELLANEOUS' => 'Miscellaneous',
    'DOCUMENT_PLEADINGS' => 'Document/Pleadings',
    'APPLICATION' => 'Application',
]);

define('ARBITRAL_TRIBUNAL_STRENGTH', [
    1 => 1,
    3 => 3,
    5 => 5
]);

define('CC_MAIL_ID', 'manojkryadav95@gmail.com');
define('BCC_MAIL_ID', 'manojkryadav95@gmail.com');

define('FULL_ACCESS_ROLES', array('DIAC', 'HEAD_COORDINATOR', 'CASE_FILER', 'ACCOUNTS', 'HEAD_COORDINATOR', 'DEO', 'STENO'));
define('CMS_ROLES', array('DIAC', 'COORDINATOR', 'CASE_FILER', 'ACCOUNTS', 'DEPUTY_COUNSEL', 'CASE_MANAGER', 'POA_MANAGER', 'HEAD_COORDINATOR', 'DEO', 'STENO'));
define('ALLOTMENT_ROLES', array('COORDINATOR', 'DEPUTY_COUNSEL', 'CASE_MANAGER'));

// ============================================================
// INPUT FILES VALIDATIONS
// ============================================================

define('MSG_FILE_MAX_SIZE', '10 MB');
define('MSG_CASE_FILE_FORMATS_ALLOWED', '.jpg, .pdf, .jpeg');
define('MSG_NOTING_FILE_FORMATS_ALLOWED', '.pdf, .doc, .docx');

define('FILE_MAX_SIZE', '10240');
define('FILE_FORMATS_ALLOWED', 'pdf');
define('CASE_FILE_FORMATS_ALLOWED', 'pdf|jpg|jpeg');

define('CASE_FILE_ALLOWED_MIME_TYPES', array('application/pdf', 'image/jpeg', 'image/jpeg'));

define('NOTING_FILE_MAX_SIZE', '10240');
define('NOTING_FILE_MAX_SIZE_MSG', '10MB');
define('NOTING_FILE_FORMATS_ALLOWED', 'pdf|doc|docx');
define('NOTING_FILE_ALLOWED_MIME_TYPES', array(
    'application/pdf', 'application/octet-stream', 'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
));

// Case Documents Validation
define('CASE_DOCUMENTS_SIZE_ALLOWED', 1024 * 50);
define('CASE_DOCUMENTS_FORMAT_ALLOWED', 'pdf');
define('CASE_DOCUMENTS_MIME_ALLOWED', array('application/pdf'));
define('CASE_DOCUMENTS_SIZE_MSG', 'Max size 10MB for each document. Max 5 files allowed');
define('CASE_DOCUMENTS_FORMAT_MSG', 'Only .pdf file is allowed');
define('CASE_DOCUMENTS_ACCEPT_EXT', '.pdf');

// ============================================================
// CMS file uploads path ======================================
// ============================================================
define('CASE_FILE_UPLOADS_FOLDER', './public/upload/case_files/');
define('VIEW_CASE_FILE_UPLOADS_FOLDER', 'public/upload/case_files/');

define('NOTING_FILE_UPLOADS_FOLDER', './public/upload/noting_files/');
define('VIEW_NOTING_FILE_UPLOADS_FOLDER', 'public/upload/noting_files/');

define('AT_TERMINATION_FILE_UPLOADS_FOLDER', './public/upload/arbitral_tribunal/');

define('AWARD_FILE_UPLOADS_FOLDER', './public/upload/award_termination_files/award/');
define('VIEW_AWARD_FILE_UPLOADS_FOLDER', 'public/upload/award_termination_files/award/');

define('FACTSHEET_FILE_UPLOADS_FOLDER', './public/upload/award_termination_files/factsheet/');
define('VIEW_FACTSHEET_FILE_UPLOADS_FOLDER', 'public/upload/award_termination_files/factsheet/');

define('FEE_FILE_UPLOADS_FOLDER', './public/upload/fees_files/');
define('VIEW_FEE_FILE_UPLOADS_FOLDER', 'public/upload/fees_files/');

define('ASSESSMENT_SUPPORTING_DOC_UPLOADS_FOLDER', './public/upload/assessment_supporting_doc/');
define('VIEW_ASSESSMENT_SUPPORTING_DOC_UPLOADS_FOLDER', 'public/upload/assessment_supporting_doc/');

define('MISCELLANEOUS_FILE_UPLOADS_FOLDER', './public/upload/miscellaneous_documents/');
define('VIEW_MISCELLANEOUS_FILE_UPLOADS_FOLDER', 'public/upload/miscellaneous_documents/');

define('CASE_ORDER_FILE_UPLOADS_FOLDER', './public/upload/case_orders');
define('VIEW_CASE_ORDER_FILE_UPLOADS_FOLDER', 'public/upload/case_orders');

// Efiling upload paths ====================================================
define('EFILING_DOCUMENTS_UPLOADS_FOLDER', './public/upload/efiling/documents/');
define('VIEW_EFILING_DOCUMENTS_UPLOADS_FOLDER', 'public/upload/efiling/documents/');

define('EFILING_APPLICATIONS_UPLOADS_FOLDER', './public/upload/efiling/applications/');
define('VIEW_EFILING_APPLICATIONS_UPLOADS_FOLDER', 'public/upload/efiling/applications/');

define('EFILING_VAKALATNAMA_UPLOADS_FOLDER', './public/upload/efiling/vakalatnamas/');
define('VIEW_EFILING_VAKALATNAMA_UPLOADS_FOLDER', 'public/upload/efiling/vakalatnamas/');

define('EFILING_CONSENT_UPLOADS_FOLDER', './public/upload/efiling/consent/');
define('VIEW_EFILING_CONSENT_UPLOADS_FOLDER', 'public/upload/efiling/consent/');

define('EFILING_NEW_REFERENCE_UPLOADS_FOLDER', './public/upload/efiling/new_reference/case_doc/');
define('VIEW_EFILING_NEW_REFERENCE_UPLOADS_FOLDER', 'public/upload/efiling/new_reference/case_doc/');

define('EFILING_NF_SOC_UPLOADS_FOLDER', './public/upload/efiling/new_reference/soc_doc/');
define('VIEW_EFILING_NF_SOC_UPLOADS_FOLDER', 'public/upload/efiling/new_reference/soc_doc/');

define('EFILING_NF_POS_UPLOADS_FOLDER', './public/upload/efiling/new_reference/proof_of_service_doc/');
define('VIEW_EFILING_NF_POS_UPLOADS_FOLDER', 'public/upload/efiling/new_reference/proof_of_service_doc/');

define('EFILING_NF_URGENCY_DOC_UPLOADS_FOLDER', './public/upload/efiling/new_reference/urgency_app_doc/');
define('VIEW_EFILING_NF_URGENCY_DOC_UPLOADS_FOLDER', 'public/upload/efiling/new_reference/urgency_app_doc/');

define('EFILING_REQUEST_UPLOADS_FOLDER', './public/upload/efiling/requests/');
define('VIEW_EFILING_REQUEST_UPLOADS_FOLDER', 'public/upload/efiling/requests/');
define('EFILING_EMPANELMENT_CERTIFICATE', 'public/upload/efiling/empanelment/');


// Empanellment documents of Efilling  
define('CV_PORTFOLIO', 'public/upload/efiling/empanelment/CV_PORTFOLIO');
define('CERT_OF_ENROLLMENT', 'public/upload/efiling/empanelment/CERT_OF_ENROLLMENT');
define('CERT_OF_PRACTICE', 'public/upload/efiling/empanelment/CERT_OF_PRACTICE');
define('HIGHEST_QUALIFICATION', 'public/upload/efiling/empanelment/HIGHEST_QUALIFICATION');
define('VILIGANCE_CLEARANCE', 'public/upload/efiling/empanelment/VILIGANCE_CLEARANCE');
define('LIST_OF_COND_CASES', 'public/upload/efiling/empanelment/LIST_OF_COND_CASES');
define('FIRST_INCOME_TEXT_RETURN', 'public/upload/efiling/empanelment/FIRST_INCOME_TEXT_RETURN');
define('SECOND_INCOME_TEXT_RETURN', 'public/upload/efiling/empanelment/SECOND_INCOME_TEXT_RETURN');
define('LIST_OF_ARB_MATTERS', 'public/upload/efiling/empanelment/LIST_OF_ARB_MATTERS');
define('ACADEMIC_ACHIEVEMENTS', 'public/upload/efiling/empanelment/ACADEMIC_ACHIEVEMENTS');
define('PROFILE', 'public/upload/efiling/empanelment/PROFILE');
define('SIGNATURE', 'public/upload/efiling/empanelment/SIGNATURE');


define('INTERN_PROFILE_PICTURE', './public/upload/efiling/internship/profile_picture/');
define('INTERN_SIGNATURE_PICTURE', './public/upload/efiling/internship/signature_picture/');
define('INTERN_CV', './public/upload/efiling/internship/CV_document/');
define('INTERN_COVER_LETTER', './public/upload/efiling/internship/cover_letter/');
define('INTERN_REFERENCE_LETTER', './public/upload/efiling/internship/reference_letter/');


// DAW =======================================
define('DAW_PHOTO_UPLOADS_FOLDER', './public/upload/daw/profile_photo/');
define('VIEW_DAW_PHOTO_UPLOADS_FOLDER', 'public/upload/daw/profile_photo/');

define('DAW_REG_MAIL_PDF_STORAGE_PATH', './public/upload/daw/reg_mail_pdf/');
define('DAW_REG_MAIL_PDF_VIEW_PATH', '/public/upload/daw/reg_mail_pdf/');


// define('COPIES_OF_AWARD_BEFORE_ARB', 'public/upload/efiling/empanelment/copies_of_award_before_arb');
// define('LIST_OF_COND_CASES_BEFORE_COURT', 'public/upload/efiling/empanelment/list_of_cond_cases_before_court');
// define('COPIES_OF_AWARD_BEFORE_COURT', 'public/upload/efiling/empanelment/copies_of_award_before_court');

// -----------------------------------------------
// Internship Document of Efilling
define('INTERN_PROFILE_FORMAT', 'png|jpg|jpeg');
define('INTERN_DOC_FORMAT', 'pdf');

define('INTERN_PROFILE_SIZE', '500');
define('INTERN_DOC_SIZE', '2048');
define('DAW_FILE_FORMATS_ALLOWED', 'png|jpg|jpeg');


// ====================================================
// Errors and Messages Start ---------------------------------------
// ====================================================

// CAPTCHA ERROR
define('INVALID_CAPTCHA', "Invalid captcha provided, please try again.");

// ====================================================
// Token Errors 
// ====================================================
define('EMPTY_TOKEN_ERROR', 'Empty Security Token');
define('INVALID_TOKEN_ERROR', 'Invalid Security Token');

// ====================================================
// Common Error 
// ====================================================
define("COMMON_ERROR", 'Something went wrong, please refresh your browser or try again or contact support team.');

// ====================================================
// Server Errors
// ====================================================
define('SERVER_ERROR', 'Server failed, please try again.');
define('SERVER_SAVING_ERROR', 'Server failed while saving the data, please try again.');

// ====================================================
// Email Errors
// ====================================================
define('EMAIL_ERROR', 'Error while sending mail. Please try again later.');

// ====================================================
// User Creation Message
// ====================================================
define('USER_CREATED_PASSWORD_SENT', 'User created successfully and password sent on your email ID.');

// ====================================================
// Messages
// ====================================================
define('SAVE_MESSAGE', 'Record saved successfully.');
define('UPDATE_MESSAGE', 'Record updated successfully.');
define('DELETE_MESSAGE', 'Record deleted successfully.');

// ====================================================
// Errors and Messages End ---------------------------------------
// ====================================================
define('CLAIMANT_TYPE_CONSTANT', 'claimant');
define('RESPONDANT_TYPE_CONSTANT', 'respondant');

define('CLAIMANT_RESPONDENT_TYPE', [
    'claimant' => 'Claimant',
    'respondant' => 'Respondent'
]);

// ========================================================
// RESPONSE FORMATS/ DRAFT LETTER TYPES
define('RESPONSE_FORMAT_TYPES', [
    'SOC_FORMAT' => 'Statement of Claim',
    'REMINDER_SOC_FORMAT' => 'Reminder of Statement of Claim',
    'FINAL_REMINDER_SOC_FORMAT' => 'Final Reminder of Statement of Claim',
    'CLOSED_DRAFT' => 'Administratively Closed Draft',
    'NAMES_TO_OPPOSITE_PARTY' => 'Names to opposite party',
    'FEE_DRAFT' => 'Fee Draft',
    'CLAIMING_DEFICIENT_FEE_FROM_PARTY' => 'Claiming Deficient Fee From Parties',
    'INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR' => 'Inviting claim seeking names of arbitrator',
    'INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED' => 'Inviting claim when arbitrator already appointed',
    'SEEKING_CONSENT_FROM_ARBITRATOR' => 'Seeking consent from arbitrator',
    'DOH_LETTER' => 'DOH Letter'
]);


// =============================================
// Active Status 
define('ACTIVE_STATUS', [
    1 => 'Active',
    0 => 'Inactive'
]);


//==================================
define('DEFAULT_SMTP_EMAIL_ID', 'noreply@diac.ind.in');
