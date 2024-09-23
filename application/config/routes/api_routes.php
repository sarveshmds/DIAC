<?php
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

// Fees calculator api routes ======================
$route['api/calculate-fees/new-reference'] = 'calculate_fees_controller/new_reference_caluclate_fees';
