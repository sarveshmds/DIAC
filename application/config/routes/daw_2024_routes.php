<?php

$route['daw-2024/all-registrations']     = 'daw/2024/daw_controller/all_registrations';
$route['daw-2024/approved-registrations']     = 'daw/2024/daw_controller/approved_registrations';
$route['daw-2024/rejected-registrations']     = 'daw/2024/daw_controller/rejected_registrations';
$route['daw-2024/get-all-registrations'] = 'daw/2024/daw_ajax_controller/get_all_registrations';
$route['daw-2024/registration/view'] = 'daw/2024/daw_controller/view_registration_details';

$route['daw-2024/pending-registrations']     = 'daw/2024/daw_controller/pending_registrations';
$route['daw-2024/payment-pending-registrations']     = 'daw/2024/daw_controller/payment_pending_registrations';
$route['daw-2024/payment-received']     = 'daw/2024/daw_controller/payment_received';

$route['daw-2024/registration/generate-excel'] = 'daw/2024/daw_controller/generate_registrations_excel';
$route['daw-2024/registration/generate-pdf'] = 'daw/2024/daw_controller/generate_registrations_pdf';
// $route['daw-2024/registration/generate-single-pdf/(:any)'] = 'daw/2024/daw_controller/generate_registrations_single_pdf/$1';

$route['daw-2024-sessions']     = 'daw/2024/daw_controller/daw_sessions';
$route['daw-2024/get-all-live-sessions'] = 'daw/2024/daw_ajax_controller/get_daw_all_live_sessions';
$route['daw-2024/live-sessions/store'] = 'daw/2024/daw_ajax_controller/store_live_sessions';

// ===========================================================================
// ===========================================================================

// Daw api controller
$route['api/daw-2024/register'] = 'daw/2024/daw_api_controller/store_registration';

$route['api/daw-2024/get-countries'] = 'daw/2024/daw_api_controller/get_countries';
$route['api/daw-2024/get-states'] = 'daw/2024/daw_api_controller/get_states_using_country';
$route['api/daw-2024/get-registrations-select-options-data'] = 'daw/2024/daw_api_controller/get_registrations_select_options_data';

$route['api/daw-2024/get-live-sessions-data'] = 'daw/2024/daw_api_controller/get_all_live_session_data';

// ===========================================================================
// ===========================================================================
// daw public controller
$route['daw-2024/person/(:any)'] = 'daw/2024/daw_public_controller/show_person_details/$1';
$route['daw-2024/registration/generate-public-single-pdf/(:any)'] = 'daw/2024/daw_public_controller/generate_public_single_pdf/$1';

$route['daw-2024/registration/approval'] = 'daw/2024/daw_ajax_controller/registration_approval';
