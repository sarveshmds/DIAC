<?php

// Efilling ================================================================
$route['efiling/register']     = 'efilling/auth_controller/efiling_register';
$route['efiling/login']     = 'efilling/auth_controller/efiling_login';
$route['efiling_service/login']     = 'efilling/auth_controller/login';
$route['efiling']     = 'efilling/auth_controller/efiling_login';
$route['efiling/logout']     = 'efilling/auth_controller/efiling_logout';

$route['efiling/forgot-password']     = 'efilling/auth_controller/efiling_forgot_password';
$route['efiling/forgot-password/verify']     = 'efilling/auth_controller/efiling_forgot_password_verify';

$route['efiling/forgot-password/otp-verification']     = 'efilling/auth_controller/efiling_fp_otp_verification';
$route['efiling/forgot-password/otp-verify']     = 'efilling/auth_controller/verify_forgot_password_otp';

$route['set-new-password']         = 'efilling/auth_controller/set_new_password';
$route['efiling/set-password'] = 'efilling/auth_controller/set_password';


// All dashboards ================================================================
// $route['efiling/dashboard']     = 'efilling/efiling_controller/dashboard';
$route['efiling/advocate-dashboard']     = 'efilling/efiling_controller/advocate_dashboard';
$route['efiling/party-dashboard']     = 'efilling/efiling_controller/party_dashboard';
$route['efiling/arbitrator-dashboard']     = 'efilling/efiling_controller/arbitrator_dashboard';
$route['efiling/advocate-arbitrator-dashboard']     = 'efilling/efiling_controller/advocateandarbitrator_dashboard';
$route['efiling/user/profile']     = 'efilling/efiling_controller/my_profile';

// New reference controller
$route['efiling/new-references']     = 'efilling/new_reference_controller/filed_cases';
$route['efiling/new-references/get-datatable-list']     = 'efilling/new_reference_controller/get_new_ref_datatable_list';
$route['efiling/new-reference/view']     = 'efilling/new_reference_controller/view_new_reference';


$route['efiling/file-new-case']     = 'efilling/new_reference_controller/file_new_case';
$route['efiling/new-reference/store']     = 'efilling/new_reference_controller/store_new_reference';
$route['efiling/new-reference/check-verify']     = 'efilling/new_reference_controller/check_and_verify_new_reference';
$route['efiling/new-reference/proceed-payment']     = 'efilling/new_reference_controller/proceed_payment';
$route['efiling/new-reference/payment-success']     = 'efilling/new_reference_controller/payment_success';

$route['efiling/new-reference/edit']     = 'efilling/new_reference_controller/edit_new_reference';
$route['efiling/new-reference/update']     = 'efilling/new_reference_controller/update_new_reference';

// Efiling Application Controller ================================================================
$route['efiling/application']     = 'efilling/application_controller/application';
$route['efiling/application/get-datatable-list']     = 'efilling/application_controller/getDatatableList';
$route['efiling/application/add']     = 'efilling/application_controller/add_application';
$route['efiling/application/store']     = 'efilling/application_controller/store_application';
$route['efiling/application/update']     = 'efilling/application_controller/update_application';
$route['efiling/application/edit']     = 'efilling/application_controller/edit_application';

// Document Controller
$route['efiling/document-pleadings']     = 'efilling/document_controller/document';
$route['efiling/document/add']     = 'efilling/document_controller/add_document';
$route['efiling/document/get-datatable-list'] = 'efilling/document_controller/getDatatableList';
$route['efiling/document/store']     = 'efilling/document_controller/store_document';
$route['efiling/document/edit']     = 'efilling/document_controller/edit_document';
$route['efiling/document/update']     = 'efilling/document_controller/update_document';

// Consent Controller
$route['efiling/consents']     = 'efilling/consent_controller/consents';
$route['efiling/consents/add']     = 'efilling/consent_controller/add_consents';
$route['efiling/consent/get-datatable-list']     = 'efilling/consent_controller/getDatatableList';
$route['efiling/consent/store']     = 'efilling/consent_controller/store_consent';
$route['efiling/consent/edit']     = 'efilling/consent_controller/edit_consent';
$route['efiling/consent/update']     = 'efilling/consent_controller/update_consent';
$route['efiling/consent/view']     = 'efilling/consent_controller/view_consent';

// Vakalatnama Controller
$route['efiling/vakalatnama']     = 'efilling/vakalatnama_controller/vakalatnama';
$route['efiling/vakalatnama/add']     = 'efilling/vakalatnama_controller/add_vakalatnama';
$route['efiling/vakalatnama/get-datatable-list'] = 'efilling/vakalatnama_controller/getDatatableList';
$route['efiling/vakalatnama/store']     = 'efilling/vakalatnama_controller/store_vakalatnama';
$route['efiling/vakalatnama/edit']   = 'efilling/vakalatnama_controller/edit_vakalatnama';
$route['efiling/vakalatnama/update'] = 'efilling/vakalatnama_controller/update_vakalatnama';

// My Case Controller
$route['efiling/my-cases']     = 'efilling/mycases_controller/my_cases';
$route['efiling/view-case-details/(:any)']     = 'efilling/mycases_controller/view_case_details';
$route['efiling/view-case-fees/(:any)']     = 'efilling/mycases_controller/view_case_fees';
$route['efiling/mycases/get-datatable-list']     = 'efilling/mycases_controller/getDatatableList';

$route['efiling/case/hearings']     = 'efilling/mycases_controller/case_hearings';
$route['efiling/mycases/get-case-hearings']     = 'efilling/mycases_controller/get_case_hearings';

// Request Controller
$route['efiling/request/get-datatable-list']     = 'efilling/request_controller/getDatatableList';
$route['efiling/request/store']     = 'efilling/request_controller/store_request';
$route['efiling/requests']     = 'efilling/request_controller/requests';
$route['efiling/requests/add']     = 'efilling/request_controller/add_requests';
$route['efiling/requests/edit']     = 'efilling/request_controller/edit_requests';
$route['efiling/request/update']     = 'efilling/request_controller/update_request';

// Register Controller
$route['efiling/register/store']     = 'efilling/register_controller/register';

// Profile controller
$route['efiling/profile/update'] = 'efilling/profile_controller/profileUpdate';
