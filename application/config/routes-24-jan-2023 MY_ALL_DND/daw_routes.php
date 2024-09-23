<?php

$route['daw-dashboard']     = 'daw/daw_controller/daw_dashboard';
$route['daw/all-registrations']     = 'daw/daw_controller/all_registrations';
$route['daw/approved-registrations']     = 'daw/daw_controller/approved_registrations';
$route['daw/rejected-registrations']     = 'daw/daw_controller/rejected_registrations';
$route['daw/get-all-registrations'] = 'daw/daw_ajax_controller/get_all_registrations';
$route['daw/registration/view'] = 'daw/daw_controller/view_registration_details';

$route['daw/pending-registrations']     = 'daw/daw_controller/pending_registrations';
$route['daw/payment-pending-registrations']     = 'daw/daw_controller/payment_pending_registrations';

$route['daw/registration/generate-excel'] = 'daw/daw_controller/generate_registrations_excel';
$route['daw/registration/generate-pdf'] = 'daw/daw_controller/generate_registrations_pdf';
// $route['daw/registration/generate-single-pdf/(:any)'] = 'daw/daw_controller/generate_registrations_single_pdf/$1';


// Daw api controller
$route['api/daw/register'] = 'daw/daw_api_controller/store_registration';

$route['api/daw/get-countries'] = 'daw/daw_api_controller/get_countries';
$route['api/daw/get-states'] = 'daw/daw_api_controller/get_states_using_country';
$route['api/daw/get-registrations-select-options-data'] = 'daw/daw_api_controller/get_registrations_select_options_data';

// daw public controller
$route['daw/person/(:any)'] = 'daw/daw_public_controller/show_person_details/$1';
$route['daw/registration/generate-public-single-pdf/(:any)'] = 'daw/daw_public_controller/generate_public_single_pdf/$1';

$route['daw/registration/approval'] = 'daw/daw_ajax_controller/registration_approval';
