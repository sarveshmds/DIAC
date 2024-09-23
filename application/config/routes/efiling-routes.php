<?php

// ----------------------------------
// AUTH CONTROLLER
// ----------------------------------
$route['efiling']     = 'efilling/auth_controller/index';
$route['efiling/login']     = 'efilling/auth_controller/efiling_login';
$route['efiling/register']     = 'efilling/auth_controller/efiling_register';
$route['efiling_service/login']     = 'efilling/auth_controller/login';
$route['efiling/logout']     = 'efilling/auth_controller/efiling_logout';

$route['efiling/forgot-password']     = 'efilling/auth_controller/efiling_forgot_password';
$route['efiling/forgot-password/verify']     = 'efilling/auth_controller/efiling_forgot_password_verify';

$route['efiling/forgot-password/otp-verification']     = 'efilling/auth_controller/efiling_fp_otp_verification';
$route['efiling/forgot-password/otp-verify']     = 'efilling/auth_controller/verify_forgot_password_otp';

$route['set-new-password']         = 'efilling/auth_controller/set_new_password';
$route['efiling/set-password'] = 'efilling/auth_controller/set_password';


// ----------------------------------
// EFILING CONTROLLER
// ----------------------------------
// DASHBOARDS ================================================================
// $route['efiling/dashboard']     = 'efilling/efiling_controller/dashboard';
$route['efiling/advocate-dashboard']     = 'efilling/efiling_controller/advocate_dashboard';
$route['efiling/party-dashboard']     = 'efilling/efiling_controller/party_dashboard';
$route['efiling/arbitrator-dashboard']     = 'efilling/efiling_controller/arbitrator_dashboard';
$route['efiling/advocate-arbitrator-dashboard']     = 'efilling/efiling_controller/advocateandarbitrator_dashboard';
$route['efiling/user/profile']     = 'efilling/efiling_controller/my_profile';

// ----------------------------------
// NEW REFERENCE CONTROLLER
// ----------------------------------
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

// --------------------------------------------------------
// APPLICATION CONTROLLER
// --------------------------------------------------------
$route['efiling/application']     = 'efilling/application_controller/application';
$route['efiling/application/get-datatable-list']     = 'efilling/application_controller/getDatatableList';
$route['efiling/application/add']     = 'efilling/application_controller/add_application';
$route['efiling/application/store']     = 'efilling/application_controller/store_application';
$route['efiling/application/update']     = 'efilling/application_controller/update_application';
$route['efiling/application/edit']     = 'efilling/application_controller/edit_application';

// --------------------------------------------------------
// DOCUMENT CONTROLLER
// --------------------------------------------------------
$route['efiling/document-pleadings']     = 'efilling/document_controller/document';
$route['efiling/document/add']     = 'efilling/document_controller/add_document';
$route['efiling/document/get-datatable-list'] = 'efilling/document_controller/getDatatableList';
$route['efiling/document/store']     = 'efilling/document_controller/store_document';
$route['efiling/document/edit']     = 'efilling/document_controller/edit_document';
$route['efiling/document/update']     = 'efilling/document_controller/update_document';

// --------------------------------------------------------
// CONSENT CONTROLLER
// --------------------------------------------------------
$route['efiling/consents']     = 'efilling/consent_controller/consents';
$route['efiling/consents/add']     = 'efilling/consent_controller/add_consents';
$route['efiling/consent/get-datatable-list']     = 'efilling/consent_controller/getDatatableList';
$route['efiling/consent/store']     = 'efilling/consent_controller/store_consent';
$route['efiling/consent/edit']     = 'efilling/consent_controller/edit_consent';
$route['efiling/consent/update']     = 'efilling/consent_controller/update_consent';
$route['efiling/consent/view']     = 'efilling/consent_controller/view_consent';

// --------------------------------------------------------
// VAKALATNAMA CONTROLLER
// --------------------------------------------------------
$route['efiling/vakalatnama']     = 'efilling/vakalatnama_controller/vakalatnama';
$route['efiling/vakalatnama/add']     = 'efilling/vakalatnama_controller/add_vakalatnama';
$route['efiling/vakalatnama/get-datatable-list'] = 'efilling/vakalatnama_controller/getDatatableList';
$route['efiling/vakalatnama/store']     = 'efilling/vakalatnama_controller/store_vakalatnama';
$route['efiling/vakalatnama/edit']   = 'efilling/vakalatnama_controller/edit_vakalatnama';
$route['efiling/vakalatnama/update'] = 'efilling/vakalatnama_controller/update_vakalatnama';

// --------------------------------------------------------
// MY CASE CONTROLLER
// --------------------------------------------------------
$route['efiling/my-cases']     = 'efilling/mycases_controller/my_cases';
$route['efiling/view-case-details/(:any)']     = 'efilling/mycases_controller/view_case_details';
$route['efiling/view-case-fees/(:any)']     = 'efilling/mycases_controller/view_case_fees';
$route['efiling/mycases/get-datatable-list']     = 'efilling/mycases_controller/getDatatableList';

$route['efiling/case/hearings']     = 'efilling/mycases_controller/case_hearings';
$route['efiling/mycases/get-case-hearings']     = 'efilling/mycases_controller/get_case_hearings';

// --------------------------------------------------------
// REQUEST CONTROLLER
// --------------------------------------------------------
$route['efiling/request/get-datatable-list']     = 'efilling/request_controller/getDatatableList';
$route['efiling/request/store']     = 'efilling/request_controller/store_request';
$route['efiling/requests']     = 'efilling/request_controller/requests';
$route['efiling/requests/add']     = 'efilling/request_controller/add_requests';
$route['efiling/requests/edit']     = 'efilling/request_controller/edit_requests';
$route['efiling/request/update']     = 'efilling/request_controller/update_request';

// --------------------------------------------------------
// REGISTER CONTROLLER
// --------------------------------------------------------
$route['efiling/register/store']     = 'efilling/register_controller/register';

// --------------------------------------------------------
// PROFILE CONTROLLER
// --------------------------------------------------------
$route['efiling/profile/update'] = 'efilling/profile_controller/profileUpdate';

// ----------------------------------
// EMPANELLMENT CONTROLLER
// ----------------------------------
$route['efiling/empanellment/start']     = 'efilling/public/empanellment_controller/start_empanellment';
$route['efiling/empanellment/store-start']     = 'efilling/public/empanellment_controller/store_start_empanellment';
$route['efiling/empanellment/personal-information']     = 'efilling/public/empanellment_controller/personal_information';
$route['efiling/empanellment/personal-information/store']     = 'efilling/public/empanellment_controller/store_personal_information';

$route['efiling/empanellment/professional-information']     = 'efilling/public/empanellment_controller/professional_information';
$route['efiling/empanellment/documents']     = 'efilling/public/empanellment_controller/documents';

// routes created by ameen
$route['efiling/empanellment/professional-information/store']     = 'efilling/public/empanellment_controller/store_professional_information';

$route['efiling/empanellment/documents/store']     = 'efilling/public/empanellment_controller/store_documents';
$route['efiling/empanellment/final_preview'] = 'efilling/public/empanellment_controller/final_preview';
$route['efiling/empanellment/final-info'] = 'efilling/public/empanellment_controller/final_info';


$route['efiling/empanellment/new-start'] = 'efilling/public/empanellment_controller/newStartEmpanellment';
// $route['efiling/empanellment/new-form'] = 'efilling/public/empanellment_controller/newForm';

$route['efilling/empanellment_controller/remove-other-insitute'] = 'efilling/public/empanellment_controller/remove_other_institute';


// ------------------------------------------------------
// Fees Calculators
$route['efiling/fees-calculators'] = 'efilling/public/fees_calculator_controller/fees_calculator_view';
$route['efiling/fees-calculators/calculator-nov-2022'] = 'efilling/public/fees_calculator_controller/fees_calculator_nov_2022';
$route['efiling/fees-calculators/calculator-latest'] = 'efilling/public/fees_calculator_controller/fees_calculator_latest';

// Grevance Controller
$route['efiling/grievance'] = 'efilling/public/grievance_controller/index';
$route['efiling/grievance/store'] = 'efilling/public/grievance_controller/store_grievance_form';

// Feedback Controller
$route['efiling/feedback'] = 'efilling/public/feedback_controller/index';
$route['efiling/feedback/save'] = 'efilling/public/feedback_controller/feedback_save';

// Internship Controller
$route['efiling/apply-internship'] = 'efilling/public/internship_controller/index';
$route['efiling/apply-internship/store'] = 'efilling/public/internship_controller/internshipStore';
