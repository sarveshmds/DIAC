<?php


// ==========================================================================
// IMS Efiling Controller

$route['ims/efiling/consent'] = 'ims_efiling_controller/consent';
$route['ims/efiling/consent/get-datatable-list'] = 'ims_efiling_controller/consent_datatable_list';
$route['ims/efiling/consent/view'] = 'ims_efiling_controller/view_consent';

$route['ims/efiling/document-pleadings'] = 'ims_efiling_controller/document_pleadings';
$route['ims/efiling/document-pleadings/get-datatable-list'] = 'ims_efiling_controller/document_pleadings_datatable_list';
$route['ims/efiling/application'] = 'ims_efiling_controller/application';
$route['ims/efiling/vakalatnama'] = 'ims_efiling_controller/vakalatnama';
$route['ims/efiling/vakalatnama/get-datatable-list'] = 'ims_efiling_controller/vakalatnama_datatable_list';

$route['diac/ims/efiling/new-references'] = 'ims_efiling_controller/new_references';

$route['ims/efiling/new_reference/get-datatable-list'] = 'ims_efiling_controller/new_reference_datatable_list';

$route['ims/efiling/application/get-datatable-list'] = 'ims_efiling_controller/application_datatable_list';
$route['ims/efiling/new-references'] = 'ims_efiling_controller/new_references';
$route['ims/new-reference/view'] = 'ims_efiling_controller/new_reference_view';
$route['ims/efiling/requests'] = 'ims_efiling_controller/requests';
$route['ims/efiling/requests/get-datatable-list'] = 'ims_efiling_controller/requests_datatable_list';

$route['change_document_status'] = 'ims_efiling_controller/change_document_status';
$route['change_application_status'] = 'ims_efiling_controller/change_application_status';
$route['change_vakalatnama_status'] = 'ims_efiling_controller/change_vakalatnama_status';
$route['change_request_status'] = 'ims_efiling_controller/change_request_status';
$route['change_consent_status'] = 'ims_efiling_controller/change_consent_status';
$route['change_new_reference_status'] = 'ims_efiling_controller/change_new_reference_status';

$route['ims/other-notings'] = 'ims_efiling_controller/other_notings';


// ==========================================================================
// User Controller
$route['login']             = 'user/login';
$route['logout']             = 'user/logout';
$route['set-password']         = 'user/set_password';
$route['forgot-password']     = 'user/forgot_password';

// ==========================================================================
// Main Controller
$route['show/display-board'] = 'main_controller/display_board';

// ==========================================================================
// Dashboard Controller
$route['superadmin-dashboard']     = 'dashboard_controller/superadmin_dashboard';
$route['admin-dashboard']         = 'dashboard_controller/admin_dashboard';
$route['diac-admin-dashboard'] = 'dashboard_controller/diac_dashboard';

// ==========================================================================
// Miscellaneous Controller
$route['referral-requests']     = 'miscellaneous_controller/all_miscellaneous';
$route['add-referral-requests']     = 'miscellaneous_controller/add_miscellaneous';
$route['referral-requests-reply']     = 'miscellaneous_controller/miscellaneous_reply';

// ==========================================================================
// Case Controller
$route['view-case/(:any)'] = 'case_controller/view_case/$1';
$route['all-cases'] = 'case_controller/all_cases';

$route['view/case-uploaded-file'] = 'case_controller/viewCaseUploadedFile';
$route['all-registered-case'] = 'case_controller/all_registered_case';
$route['all-registered-case/view'] = 'case_controller/view_registered_case';

$route['add-new-case'] = 'case_controller/add_new_case';
$route['add-new-case/(:any)'] = 'case_controller/add_new_case/$1';

$route['status-of-pleadings'] = 'case_controller/status_of_pleadings';
$route['status-of-pleadings/(:any)'] = 'case_controller/status_of_pleadings/$1';

$route['claimant-respondant-details'] = 'case_controller/claimant_respondant_details';
$route['claimant-respondant-details/(:any)'] = 'case_controller/claimant_respondant_details/$1';

$route['counsels'] = 'case_controller/counsels';
$route['counsels/(:any)'] = 'case_controller/counsels/$1';

$route['arbitral-tribunal'] = 'case_controller/arbitral_tribunal';
$route['arbitral-tribunal/(:any)'] = 'case_controller/arbitral_tribunal/$1';

$route['case-fees-details'] = 'case_controller/case_fees_details';
$route['case-fees-details/(:any)'] = 'case_controller/case_fees_details/$1';
$route['view-fees-details/(:any)'] = 'case_controller/view_fees_details/$1';

$route['case-fees-details/add-assessment/(:any)'] = 'case_controller/add_case_fee_assessment';

$route['case-all-files/(:any)'] = 'case_controller/case_all_files/$1';

$route['termination'] = 'case_controller/termination';
$route['termination/(:any)'] = 'case_controller/termination/$1';

$route['noting'] = 'case_controller/noting';
$route['noting/(:any)'] = 'case_controller/noting/$1';

$route['case-allotment'] = 'case_controller/case_allotment';
$route['allotted-case'] = 'case_controller/allotted_case';

// ==========================================================================
// Response Controller
$route['response-format'] = 'response_controller/response_format';
$route['response-format/editable'] = 'response_controller/editable_response_format';

// $route['add-response-format'] = 'case_controller/add_response_format';

// ==========================================================================
// Cause List Controller
$route['cause-list'] = 'causelist_controller/cause_list';
$route['hearings-today'] = 'causelist_controller/hearings_today';
$route['rooms-list'] = 'causelist_controller/rooms_list';
$route['display-board'] = 'causelist_controller/display_board';

// ==========================================================================
// Category Controller
$route['panel-category'] = 'category_controller/panel_category';
$route['purpose-category'] = 'category_controller/purpose_category';

// ==========================================================================
// Notification Controller
$route['notifications'] = 'notification_controller/notifications';

// ==========================================================================
// Superadmin Controller
$route['manage-resource']         = 'superadmin_controller/manage_resource';
$route['manage-user']             = 'superadmin_controller/manage_user';
$route['manage-group']             = 'superadmin_controller/manage_group';
$route['title-setup']             = 'superadmin_controller/title_setup';
$route['my-account']             = 'superadmin_controller/my_account';
$route['audit-logs']         = 'superadmin_controller/audit_logs';
$route['page-creation']         = 'superadmin_controller/page_creation';
$route['approval-user-create']     = 'superadmin_controller/approval_user_create';

// ==========================================================================
// Admin Controller
$route['gen-code']             = 'admin_controller/gen_code_master';
$route['country-master']     = 'admin_controller/country_master';
$route['country-preview']     = 'admin_controller/country_preview';
$route['state-master']         = 'admin_controller/state_master';
$route['state-preview']     = 'admin_controller/state_preview';
$route['district-master']     = 'admin_controller/district_master';
$route['district-preview']     = 'admin_controller/district_preview';
$route['block-master']         = 'admin_controller/block_master';
$route['block-preview']     = 'admin_controller/block_preview';
$route['gp-master']         = 'admin_controller/gp_master';
$route['gp-preview']         = 'admin_controller/gp_preview';
$route['village-master']     = 'admin_controller/village_master';
$route['village-preview']     = 'admin_controller/village_preview';
$route['email-setup']         = 'admin_controller/email_setup';
$route['sms-setup']         = 'admin_controller/sms_setup';
$route['imei-setup']         = 'admin_controller/imei_setup';

// ==============================================================================
// Export Controller
$route['pdf/registered-cases-list'] = 'export_controller/generateRegisteredCasePdf';
$route['excel/registered-cases-list'] = 'export_controller/generateRegisteredCaseExcel';
$route['excel/all-cases-list'] = 'export_controller/generateAllCasesExcel';
$route['pdf/all-cases-list'] = 'export_controller/generateAllCasesPdf';
$route['pdf/other-pleadings/(:any)'] = 'export_controller/generateOtherPleadingsPdf';
$route['pdf/other-correspondance/(:any)'] = 'export_controller/generateOtherCorrespondancePdf';
$route['pdf/notings/(:any)'] = 'export_controller/generateNotingsPdf';
$route['pdf/panel-of-arbitrators'] = 'export_controller/generatePanelOfArbitratorsPdf';
$route['pdf/cause-list'] = 'export_controller/generateCauseListPdf';
$route['pdf/case-details/(:any)'] = 'export_controller/generateCaseDetailsPdf';
$route['print-case/(:any)'] = 'export_controller/print_case/$1';
$route['export-pdf/(:any)'] = 'export_controller/export_pdf/$1';

$route['pdf/miscellaneous-reply/(:any)'] = 'export_controller/generateMiscellaneousRepliesPdf';

$route['response-format/claiming-deficient-fee-from-parties'] = 'export_controller/claiming_deficient_fee_from_parties';
$route['response-format/inviting-claim-and-seeking-names-of-arbitrator'] = 'export_controller/inviting_claim_and_seeking_names_of_arbitrator';
$route['response-format/inviting-claim-when-arbitrator-already-appointed'] = 'export_controller/inviting_claim_when_arbitrator_already_appointed';
$route['response-format/seeking-consent-from-arbitrator'] = 'export_controller/seeking_consent_from_arbitrator';

$route['pdf/response-format'] = 'export_controller/generateResponseFormat';

// Tag cases controller ===========================================
$route['tag-cases'] = 'tag_cases_controller/all_tag_cases';
$route['tag-cases/all-tagged-case-datatable'] = 'tag_cases_controller/all_tagged_case_datatable';
$route['tag-cases/store'] = 'tag_cases_controller/store_tagged_case';
$route['tag-cases/update'] = 'tag_cases_controller/update_tagged_case';
$route['tag-cases/get-single-details'] = 'tag_cases_controller/get_single_tagged_case_details';
$route['tag-cases/delete'] = 'tag_cases_controller/delete_tagged_case';

// =============================================
// Case Ref. Controller
$route['case-ref-no-setup'] = 'master_setup/case_ref_controller/all_case_ref_no';
$route['case-ref-no-setup/get-datatable-data'] = 'master_setup/case_ref_controller/get_datatable_data';
$route['case-ref-no-setup/get-list'] = 'master_setup/case_ref_controller/get_case_ref_no_list';
$route['case-ref-no-setup/store'] = 'master_setup/case_ref_controller/store_case_ref_no';
