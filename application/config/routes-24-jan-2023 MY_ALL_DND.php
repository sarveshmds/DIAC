<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'user/login';

// Include efiling routes =======================
require_once(APPPATH . 'config/routes/efiling-routes.php');

// Include ims routes =======================
require_once(APPPATH . 'config/routes/ims-routes.php');


// Include daw routes =======================
require_once(APPPATH . 'config/routes/daw_routes.php');


// Ajax Controller ================================================================
$route['get-claimant-respondent-separately']     = 'ajax_controller/get_claimant_respondent_separately';
$route['insert'] = 'Ajax_controller/insertApplication ';

// ==========================================================================
// Api Controller
$route['api/resend-otp'] = 'api_login_controller/resend_otp';
$route['api/verify-otp'] = 'api_login_controller/verify_otp';
$route['api/login'] = 'api_login_controller/login';

$route['api/get/case-list'] = 'api_controller/get_case_list';
$route['api/get/cause-list'] = 'api_controller/get_cause_list';
$route['api/get/todays-cause-list'] = 'api_controller/get_todays_hearings';
$route['api/get/display-board'] = 'api_controller/get_display_board';

$route['api/user/case-detail'] = 'api_user_controller/get_case_detail';
$route['api/user/case-list'] = 'api_user_controller/get_case_list';
$route['api/user/cause-list'] = 'api_user_controller/get_cause_list';


// ==========================================================================
// Calculate Fees Controller
$route['calculate-fees/new-reference'] = 'calculate_fees_controller/new_reference_caluclate_fees';
$route['calculate-fees/case-fee'] = 'calculate_fees_controller/case_caluclate_fees';
$route['calculate-fees/case-fee-seperate-assessed'] = 'calculate_fees_controller/case_caluclate_fees_seperate_assessed';


// ==========================================================================
// System Controller
$route['data-logs'] = 'system_controller/data_logs';
$route['page-not-found'] = 'error_controller/page_not_found';
$route['404_override'] = 'error_controller/page_not_found';
$route['translate_uri_dashes'] = FALSE;
