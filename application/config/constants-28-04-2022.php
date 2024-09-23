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
define('LOGIN_URL', 'http://sgov.stlindia.com/staging1/disc_web_app/');


define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);


//constants used in JWT

define('KEY', 'GS_JWT_KEY');
define('ISS', 'localhost'); //Issuer Server Name
define('AUD', 'localhost');
define('IAT', time()); //Isseued at time()
define('NBF', IAT + 10); //Not Before IAT + 10 second
define('EXPIRE', NBF + 60); //Adding 60 second

define('MSG_FILE_MAX_SIZE', '10 MB');
define('MSG_CASE_FILE_FORMATS_ALLOWED', '.jpg, .pdf, .jpeg');
define('MSG_NOTING_FILE_FORMATS_ALLOWED', '.pdf, .doc, .docx');

define('FILE_MAX_SIZE', '10240');
define('CASE_FILE_FORMATS_ALLOWED', 'pdf|jpg|jpeg');
define('NOTING_FILE_FORMATS_ALLOWED', 'pdf|doc|docx');

define('CASE_FILE_ALLOWED_MIME_TYPES', array('application/pdf', 'image/jpeg', 'image/jpeg'));
define('NOTING_FILE_ALLOWED_MIME_TYPES', array('application/msword', 'application/vnd.ms-office', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip', 'application/pdf'));

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

define('MISCELLANEOUS_FILE_UPLOADS_FOLDER', './public/upload/miscellaneous_documents/');
define('VIEW_MISCELLANEOUS_FILE_UPLOADS_FOLDER', 'public/upload/miscellaneous_documents/');

define('FULL_ACCESS_ROLES', array('DIAC', 'COORDINATOR', 'CASE_FILER', 'ACCOUNTS'));

// DIAC Details ==========================================================
define('DEPARTMENT_NAME', 'Delhi International Arbitration Center (DIAC)');
define('DEPARTMENT_ADDRESS', 'Delhi High Court Campus, Shershah Road, New Delhi - 110503');
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
