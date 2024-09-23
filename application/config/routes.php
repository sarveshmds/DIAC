<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'user/index';

// Include efiling routes =======================
require_once(APPPATH . 'config/routes/efiling-routes.php');

// Include ims routes =======================
require_once(APPPATH . 'config/routes/ims-routes.php');


// Include daw routes =======================
require_once(APPPATH . 'config/routes/daw_routes.php');

// Include daw 2024 routes =======================
require_once(APPPATH . 'config/routes/daw_2024_routes.php');

// Include API routes =======================
require_once(APPPATH . 'config/routes/api_routes.php');


// Ajax Controller ================================================================
$route['get-claimant-respondent-separately']     = 'ajax_controller/get_claimant_respondent_separately';
$route['insert'] = 'Ajax_controller/insertApplication ';


// ==========================================================================
// Calculate Fees Controller
$route['calculate-fees/new-reference'] = 'calculate_fees_controller/new_reference_caluclate_fees';
$route['calculate-fees/case-fee'] = 'calculate_fees_controller/case_caluclate_fees';
$route['calculate-fees/case-fee-seperate-assessed'] = 'calculate_fees_controller/case_caluclate_fees_seperate_assessed';


// ---------------------------------------
// CAPTCHA CONTROLLER
// ---------------------------------------
$route['refresh_captcha'] = 'captcha_controller/refresh_captcha';

// ==========================================================================
// System Controller
$route['data-logs'] = 'system_controller/data_logs';
$route['page-not-found'] = 'error_controller/page_not_found';
$route['404_override'] = 'error_controller/page_not_found';
$route['translate_uri_dashes'] = FALSE;
