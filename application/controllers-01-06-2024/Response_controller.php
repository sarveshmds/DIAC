<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Response_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'Refferal_request_model', 'arbitral_tribunal_model', 'response_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');
    }

    /*
	*	purpose : to check whether the method is correct or not
	*/

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'CASE_MANAGER' => array('response_format', 'add_response_format', 'editable_response_format', 'generate_and_download_response_format'),
            'DEPUTY_COUNSEL' => array('response_format', 'add_response_format', 'editable_response_format', 'generate_and_download_response_format'),
            'COORDINATOR' => array('response_format', 'add_response_format', 'editable_response_format', 'generate_and_download_response_format'),
            'HEAD_COORDINATOR' => array('generate_and_download_response_format'),
            'ACCOUNT' => array('generate_and_download_response_format'),
            'DIAC' => array('generate_and_download_response_format'),
        );

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('logout');
        } else {
            if (in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
                $uri = $this->uri->segment_array();
                unset($uri[1]);
                unset($uri[2]);
                call_user_func_array(array($this, $method), $uri);
            } else {
                return redirect('page-not-found');
            }
        }
    }

    public function response_format()
    {
        $sidebar['menu_item'] = 'Draft Letters';
        $sidebar['menu_group'] = 'Case Management';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        $data['page_title'] = 'Draft Letters';
        $data['cases'] = $this->getter_model->get(null, 'GET_CASE_NUMBERS_LIST_FOR_FORMAT_RESPONSE');

        if ($page_status != 0) {
            $this->load->view('diac-admin/response-format', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add_response_format()
    {
        $sidebar['menu_item'] = 'Draft Letters';
        $sidebar['menu_group'] = 'Case Management';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {

            // Get uri segment
            $case_no = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

            if (isset($case_no) && !empty($case_no)) {
                $data['page_title'] = 'Create Draft Letter';
                $data['case_no'] = $case_no;
                $data['slug'] = $case_no;
                $data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
                $data['case_details'] = $this->case_model->get($data, 'GET_CASE_BASIC_DATA');

                $check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

                if ($check_case_allotment) {
                    $this->load->view('diac-admin/add-response-format.php', $data);
                } else {
                    redirect('dashboard');
                }
            } else {
                redirect('dashboard');
            }
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function editable_response_format()
    {
        // Get uri segment
        $case_no = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        if (!$case_no) {
            return redirect('page-not-found');
        }

        $type = $this->input->get('type');
        $data['letter_type'] = $type;

        if (!$type) {
            return redirect('page-not-found');
        }
        // claiming-deficient-fee-from-parties
        // inviting-claim-and-seeking-names-of-arbitrator
        // inviting-claim-when-arbitrator-already-appointed
        // seeking-consent-from-arbitrator
        // echo $this->input->get('type');
        // die;

        $types = [
            'SOC_FORMAT',
            'REMINDER_SOC_FORMAT',
            'FINAL_REMINDER_SOC_FORMAT',
            'CLOSED_DRAFT',
            'NAMES_TO_OPPOSITE_PARTY',
            'FEE_DRAFT',
            'CLAIMING_DEFICIENT_FEE_FROM_PARTY',
            'INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR',
            'INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED',
            'SEEKING_CONSENT_FROM_ARBITRATOR',
        ];

        if (!in_array($type, $types)) {
            return redirect('page-not-found');
        }

        $types_and_formats = [
            'SOC_FORMAT' => 'soc_format.php',
            'REMINDER_SOC_FORMAT' => 'reminder_soc_format.php',
            'FINAL_REMINDER_SOC_FORMAT' => 'final_reminder_soc_format.php',
            'CLOSED_DRAFT' => 'closed_draft.php',
            'NAMES_TO_OPPOSITE_PARTY' => 'names_to_opposite_party.php',
            'FEE_DRAFT' => 'fee_draft.php',
            'CLAIMING_DEFICIENT_FEE_FROM_PARTY' => 'claiming_deficient_fee_from_parties.php',
            'INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR' => 'inviting_claim_seeking_names_of_arbitrator.php',
            'INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED' => 'inviting_claim_when_arbitrator_already_appointed.php',
            'SEEKING_CONSENT_FROM_ARBITRATOR' => 'seeking_consent_from_arbitrator.php',
        ];

        $types_and_names = RESPONSE_FORMAT_TYPES;

        $sidebar['menu_item'] = 'Alloted Case';
        $sidebar['menu_group'] = 'Case Management';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {

            if (isset($case_no) && !empty($case_no)) {
                $data['page_title'] = 'Draft Letters';
                $data['case_no'] = $case_no;
                $data['slug'] = $case_no;


                $data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');

                $data['case_details'] = $this->case_model->get($data, 'VIEW_REGISTER_CASE_DATA');
                $data['arbitrators'] = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no);

                $data['claimant_counsels'] = $this->common_model->get_counsels_of_case($case_no, CLAIMANT_TYPE_CONSTANT);
                $data['respondent_counsels'] = $this->common_model->get_counsels_of_case($case_no, RESPONDANT_TYPE_CONSTANT);

                $data['case_data'] = $data['case_details'];
                $data['parties'] = $data['claim_res_data'];

                // echo '<pre>';
                // print_r($data['claimant_counsels']);
                // die;

                // ================================================
                $data['coordinator_name'] = $this->common_model->get_person_details_of_alloted_case($case_no, 'COORDINATOR');

                $data['format_name'] = $types_and_names[$type];

                // ================================================
                if ($type == 'REMINDER_SOC_FORMAT') {
                    $data['soc_details'] = $this->response_model->get_response_format_using_case_type($case_no, 'SOC_FORMAT');
                }
                // ================================================
                if ($type == 'FINAL_REMINDER_SOC_FORMAT') {
                    $data['soc_details'] = $this->response_model->get_response_format_using_case_type($case_no, 'REMINDER_SOC_FORMAT');
                    // print_r($data['soc_details']['created_at']);
                    // die;
                }
                // ================================================
                if ($type == 'CLOSED_DRAFT') {
                    $data['final_reminder_details'] = $this->response_model->get_response_format_using_case_type($case_no, 'FINAL_REMINDER_SOC_FORMAT');
                    // print_r($data['soc_details']['created_at']);
                    // die;
                }

                // ================================================
                // Check if the draft letter for the desired letter type is already created in the case
                $data['created_response_format'] = $this->response_model->get_response_format_using_case_type($case_no, $type);

                $check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

                // print_r($check_case_allotment);
                // die;

                // ================================================
                // If it is in edit format
                if ($this->input->get('code')) {
                    $code = $this->input->get('code');
                    // echo $code;
                    // die;
                    $data['response_format'] = $this->response_model->get_response_format_using_code($code);

                    if (!$data['response_format']) {
                        redirect(base_url('allotted-case'));
                    }

                    $data['format'] = $data['response_format']['body'];

                    // echo '<pre>';
                    // print_r($data['format']);
                    // die;
                } else {
                    $data['format'] = $this->load->view('formats/response_format/' . $types_and_formats[$type], $data, true);
                }

                if ($check_case_allotment) {
                    $this->load->view('diac-admin/editable-response-format', $data);
                } else {
                    redirect('dashboard');
                }
            } else {
                redirect('dashboard');
            }
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function generate_and_download_response_format()
    {
        $code = $this->input->get('code');

        if (!$code) {
            return redirect('page-not-found');
        }

        $data['response_format'] = $this->response_model->get_response_format_using_code($code);

        if (!$data['response_format']) {
            return redirect('page-not-found');
        }

        $body = $data['response_format']['body'];
        $file_name = $data['response_format']['letter_type'];

        // Load dom pdf library =====================
        $this->load->library('dom_pdf');
        // Make PDF Format
        $data['page_title'] = 'Draft Letters';
        $pdf_data = $this->load->view('system/templates/header_for_templates', $data, true);
        $pdf_data .= $body;
        $pdf_data .= $this->load->view('system/templates/footer', $data, true);
        // Save pdf to folder
        $this->dom_pdf->generate_and_download_pdf($pdf_data, $file_name . '_LETTER', '', '');
        // ==================================================
    }

    public function claiming_deficient_fee_from_parties()
    {
        $data['page_title'] = 'Claiming deficient fee from the parties - Draft Letters';
        $caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        $data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
        $data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

        $print_data = $this->load->view('system/templates/header', $data, true);
        $print_data .= $this->load->view('system/response_format/claiming_deficient_fee_from_parties_pdf', $data, true);
        $print_data .= $this->load->view('system/templates/footer', $data, true);
        $this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
    }

    public function inviting_claim_and_seeking_names_of_arbitrator()
    {
        $data['page_title'] = 'Inviting claim and seeking names of Arbitrator - Draft Letters';
        $caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        $data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
        $data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

        $print_data = $this->load->view('system/templates/header', $data, true);
        $print_data .= $this->load->view('system/response_format/inviting_claim_seeking_names_of_arbitrator_pdf', $data, true);
        $print_data .= $this->load->view('system/templates/footer', $data, true);
        $this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
    }

    public function inviting_claim_when_arbitrator_already_appointed()
    {
        $data['page_title'] = 'Inviting claim when Arbitrator already appointed by Court - Draft Letters';
        $caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        $data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
        $data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

        $print_data = $this->load->view('system/templates/header', $data, true);
        $print_data .= $this->load->view('system/response_format/inviting_claim_when_arbitrator_already_appointed_pdf', $data, true);
        $print_data .= $this->load->view('system/templates/footer', $data, true);
        $this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
    }

    public function seeking_consent_from_arbitrator()
    {
        $data['page_title'] = 'Seeking consent from Ld. Arbitrator - Draft Letters';
        $caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        $data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
        $data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

        $print_data = $this->load->view('system/templates/header', $data, true);
        $print_data .= $this->load->view('system/response_format/seeking_consent_from_arbitrator_pdf', $data, true);
        $print_data .= $this->load->view('system/templates/footer', $data, true);
        $this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
    }
}
