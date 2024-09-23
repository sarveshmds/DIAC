<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Response_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'Refferal_request_model', 'arbitral_tribunal_model'));

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
        $sidebar['menu_item'] = 'Response Format';
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
        $data['page_title'] = 'Response Format';
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
        $sidebar['menu_item'] = 'Response Format';
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
                $data['page_title'] = 'Add Response Format';
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
        // claiming-deficient-fee-from-parties
        // inviting-claim-and-seeking-names-of-arbitrator
        // inviting-claim-when-arbitrator-already-appointed
        // seeking-consent-from-arbitrator
        // echo $this->input->get('type');
        // die;

        $type = $this->input->get('type');

        $types = [
            'SOC_FORMAT',
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
            'CLAIMING_DEFICIENT_FEE_FROM_PARTY' => 'claiming_deficient_fee_from_parties.php',
            'INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR' => 'inviting_claim_seeking_names_of_arbitrator.php',
            'INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED' => 'inviting_claim_when_arbitrator_already_appointed.php',
            'SEEKING_CONSENT_FROM_ARBITRATOR' => 'seeking_consent_from_arbitrator.php',
        ];

        $types_and_names = [
            'SOC_FORMAT' => 'State of Claim',
            'CLAIMING_DEFICIENT_FEE_FROM_PARTY' => 'Claiming Deficient Fee From Parties',
            'INVITING_CLAIM_SEEKING_NAME_OF_ARBITRATOR' => 'Inviting claim seeking names of arbitrator',
            'INVITING_CLAIM_WHEN_ARBITRATOR_ALREADY_APPOINTED' => 'Inviting claim when arbitrator already appointed',
            'SEEKING_CONSENT_FROM_ARBITRATOR' => 'Seeking consent from arbitrator',
        ];

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

            // Get uri segment
            $case_no = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

            if (isset($case_no) && !empty($case_no)) {
                $data['page_title'] = 'Response Format';
                $data['case_no'] = $case_no;
                $data['slug'] = $case_no;

                $data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
                $data['case_details'] = $this->case_model->get($data, 'VIEW_REGISTER_CASE_DATA');
                $data['arbitrators'] = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no);

                $data['case_data'] = $data['case_details'];
                $data['parties'] = $data['claim_res_data'];

                // print_r($data['case_data']);
                // die;

                $data['coordinator_name'] = $this->common_model->get_person_details_of_alloted_case($case_no, 'COORDINATOR');

                $data['format_name'] = $types_and_names[$type];

                $data['format'] = $this->load->view('formats/response_format/' . $types_and_formats[$type], $data, true);

                $data['subject'] = $data['format_name'] . ' for case number: ' . $data['case_data']['case_no'];

                // Email IDS
                $data['to_email_ids'] = implode(',', array_column($data['claim_res_data'], 'email'));
                $data['cc_email_ids'] = CC_MAIL_ID;
                $data['bcc_email_ids'] = BCC_MAIL_ID;

                $check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

                if ($check_case_allotment) {
                    $this->load->view('diac-admin/editable-response-format.php', $data);
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
        $body = $_POST['response_body'];

        // Load dom pdf library =====================
        $this->load->library('dom_pdf');
        // Make PDF Format
        $data['page_title'] = 'Response Format';
        $pdf_data = $this->load->view('system/templates/header_for_templates', $data, true);
        $pdf_data .= $body;
        $pdf_data .= $this->load->view('system/templates/footer', $data, true);
        // Save pdf to folder
        $this->dom_pdf->generate_and_download_pdf($pdf_data, 'response_format', '', '');
        // ==================================================
    }

    public function claiming_deficient_fee_from_parties()
    {
        $data['page_title'] = 'Claiming deficient fee from the parties - Response Format';
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
        $data['page_title'] = 'Inviting claim and seeking names of Arbitrator - Response Format';
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
        $data['page_title'] = 'Inviting claim when Arbitrator already appointed by Court - Response Format';
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
        $data['page_title'] = 'Seeking consent from Ld. Arbitrator - Response Format';
        $caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

        $data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
        $data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

        $print_data = $this->load->view('system/templates/header', $data, true);
        $print_data .= $this->load->view('system/response_format/seeking_consent_from_arbitrator_pdf', $data, true);
        $print_data .= $this->load->view('system/templates/footer', $data, true);
        $this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
    }
}
