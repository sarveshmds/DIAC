<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Refferal_request_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());

        # models
        $this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'refferal_request_model', 'master_setup/arbitrator_setup_model', 'panel_category_model', 'master_setup/courts_setup_model', 'master_setup/country_model', 'arbitral_tribunal_model', 'master_setup/case_types_model', 'claimant_respondent_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
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
            'DIAC' => array('index', 'get_for_datatable', 'view'),
            'CASE_FILER' => array('index', 'get_for_datatable', 'view', 'add', 'store', 'final_submit', 'update', 'register_and_allocate_case'),
            'COORDINATOR' => array('index', 'get_for_datatable', 'view'),
            'HEAD_COORDINATOR' => array('index', 'get_for_datatable', 'view', 'view_and_approval', 'change_status'),
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

    public function index()
    {
        $sidebar['menu_item'] = 'All List';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'All Referrals/Requests';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            // Update the notings notifications
            $this->notification_model->post([
                'type_table' => 'miscellaneous_noting_tbl',
            ], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');

            $this->load->view('refferal_requests/index', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    // Miscelleneous
    public function get_for_datatable()
    {
        $type = $this->uri->segment(3);
        $inputCsrfToken = $_POST['csrf_trans_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'dataTableMiscellaneous')) {
                $select_column = array("document", "m.remarks", 'cdt.case_title');
                $order_column = array("document", null, null, null, null);

                $this->db->select("m.*, DATE_FORMAT(m.created_at, '%d-%M-%Y') as created_at, cdt.case_title, cdt.is_registered, cdt.case_no_prefix, cdt.case_no, cdt.case_no_year");
                $this->db->from('miscellaneous_tbl as m');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.reference_code = m.m_code AND cdt.case_type = "REFFERAL_REQUESTS"', 'left');

                if (isset($_POST["search"]["value"])) {
                    $count = 1;
                    $search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

                    $like_clause = "(";
                    foreach ($select_column as $sc) {
                        if ($count == 1) {
                            $like_clause .= $sc . " LIKE '%" . $search_value . "%'";
                        } else {
                            $like_clause .= " OR " . $sc . " LIKE '%" . $search_value . "%'";
                        }
                        $count++;
                    }
                    $like_clause .= ")";
                    $this->db->where($like_clause);
                }

                if ($this->input->post('status') == 'PENDING') {
                    $this->db->where('m.is_approved', 0);
                }

                if ($this->input->post('status') == 'APPROVED_NOT_REG') {
                    $this->db->where('cdt.is_registered', 0);
                    $this->db->where('m.is_approved', 1);
                }

                if ($this->input->post('status') == 'APPROVED_AND_REG') {
                    $this->db->where('m.is_approved', 1);
                    $this->db->where('cdt.is_registered', 1);
                }

                if ($this->session->userdata('role') == 'HEAD_COORDINATOR') {
                    $this->db->where('m.is_submitted', 1);
                }

                $this->db->where('m.record_status', 1);

                // Clone the db instance
                $tempDb = clone $this->db;

                // if (isset($_POST["order"])) {
                //     $this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
                // } else {
                //     $this->db->order_by('m.id', 'DESC');
                // }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $query = $this->db->get();
                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = $tempDb->count_all_results();

                // Records total
                $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('miscellaneous_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );

                echo json_encode($output);
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => 'FALSE',
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function add()
    {

        $sidebar['menu_item'] = 'Add New';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Add New Referrals/Requests';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {

            // Check if the code is available in the url
            // Then the form is of edit type
            if ($this->input->get('code')) {
                $data['code'] = $this->input->get('code');
                $data['case_data'] = $this->refferal_request_model->getRefferalReqDataUsingCode($data['code']);

                if (count($data['case_data']) < 1) {
                    return redirect('page-not-found');
                }

                $data['case_no'] = $data['case_data']['slug'];

                $data['all_counsels_list'] = $this->getter_model->get(null, 'GET_ALL_COUNSELS_LIST');
                $data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');

                // Get the case files
                $data['case_supporting_docs'] = $this->common_model->get_case_supporting_doc_using_case_code($data['case_no']);

                // Get case arbitrator list
                // $data['arbitrators'] = $this->arbitral_tribunal_model->getCaseArbitratorsUsingCaseCode($data['case_no']);
                $data['arbitrators_name'] = $this->getter_model->get([
                    'is_empanelled' => 1
                ], 'get_arbitrators_name');
                $data['not_emp_arbitrators_name'] = $this->getter_model->get([
                    'is_empanelled' => 2
                ], 'get_arbitrators_name');

                $data['claimants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($data['case_data']['slug'], 'claimant');
                $data['respondants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($data['case_data']['slug'], 'respondant');
            }

            // $data['users'] = $this->case_model->get('', 'ALL_DIAC_COORDINATORS');
            // Case types
            $data['case_types'] = $this->case_types_model->getAllCaseTypes();

            // Reffered By
            $data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
            // Type of arbitrations
            $data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
            // Type of arbitration
            $data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
            // Arbitrator Status
            $data['arbitrator_status'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_STATUS'], 'GET_GENCODE_DESC');
            // Arbitrator is empanelled
            $data['arbitrator_is_empanelled'] = $this->getter_model->get(['gen_code_group' => 'ARB_IS_EMPANELLED'], 'GET_GENCODE_DESC');

            // Empanelled arbitrator list
            $data['emapnelled_arbitrators'] = $this->arbitrator_setup_model->getAllEmpanelledArbitrator();


            $data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');
            $data['arbitrator_types'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_TYPE'], 'GET_GENCODE_DESC');

            $data['panel_category'] = $this->panel_category_model->getAllPanelCategory();
            $data['courts_list'] = $this->courts_setup_model->getAllCourtsList();

            $data['countries'] = $this->country_model->getAllCountries();

            $this->load->view('refferal_requests/add', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to perform create refferal request
     */
    public function store()
    {

        try {
            $inputCsrfToken = $_POST['csrf_refferal_req_form_token'];
            if ($inputCsrfToken != '') {
                if (checkToken($inputCsrfToken, 'refferal_req_form')) {

                    $this->form_validation->set_rules('cd_case_title', 'Message', 'required|xss_clean');
                    $this->form_validation->set_rules('cd_reffered_by', 'Mode of reference', 'required|xss_clean');
                    $this->form_validation->set_rules('cd_toa', 'Nature of arbitration', 'required|xss_clean');
                    $this->form_validation->set_rules('cd_di_toa', 'Type of Arbitration', 'required|xss_clean');

                    $this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'required|xss_clean');

                    if ($this->input->post('cd_arbitrator_status') == 1) {
                        $this->form_validation->set_rules('cd_arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
                    }

                    if ($this->input->post('cd_reffered_by') == 'COURT') {
                        $this->form_validation->set_rules('cd_reffered_by_court_case_type', 'Case Type', 'required|xss_clean');
                        $this->form_validation->set_rules('cd_reffered_by_court_case_year', 'Year', 'required|xss_clean');
                        $this->form_validation->set_rules('cd_case_arb_pet', 'Reference No.', 'required|xss_clean');
                    }


                    if ($this->form_validation->run()) {
                        $this->db->trans_begin();

                        try {

                            $maxDiaryNumber = $this->refferal_request_model->getHighestDiaryNumber();

                            $diary_number_prefix = generateDiaryNumberPrefix();
                            $diary_number = generateDiaryNumberDigits($maxDiaryNumber);
                            $diary_number_year = date('Y');

                            // $max_m_code = $this->refferal_request_model->getHighestCode();

                            $ref_code = generateCode();

                            $mtData = array(
                                'm_code' => $ref_code,
                                'user_code' => $this->user_code,
                                'diary_number_prefix' => $diary_number_prefix,
                                'diary_number' => $diary_number,
                                'diary_number_year' => $diary_number_year,
                                'record_status' => 1,
                                'created_by' => $this->user_code,
                                'created_at' => $this->date,
                                'updated_by' => $this->user_code,
                                'updated_at' => $this->date
                            );

                            $result = $this->refferal_request_model->store($mtData);

                            if ($result) {

                                // Update the diary number
                                $dno_result = $this->db->where('status', 1)->update('cs_diary_numbers_tbl', [
                                    'diary_number_seq' => $diary_number
                                ]);

                                if (!$dno_result) {
                                    failure_response('Error while updating diary number', false, true);
                                }

                                // ============================================
                                // Store the data into case details table
                                // ============================================
                                $slug = md5($diary_number_prefix . $diary_number . $ref_code);

                                $case_details_data = array(
                                    'slug' => $slug,
                                    'case_type' => 'REFFERAL_REQUESTS',
                                    'reference_code' => $ref_code,
                                    'case_no' => '',
                                    'case_title' => $this->input->post('cd_case_title'),
                                    // 'case_title_claimant' => $claimant_table_id,
                                    // 'case_title_respondent' => $respondent_table_id,
                                    'reffered_by' => $this->security->xss_clean($this->input->post('cd_reffered_by')),
                                    'reffered_on' => formatDate($this->security->xss_clean($this->input->post('cd_reffered_on'))),
                                    'arbitration_petition' => $this->security->xss_clean($this->input->post('cd_case_arb_pet')),
                                    'recieved_on' => formatDate($this->security->xss_clean($this->input->post('cd_recieved_on'))),
                                    'registered_on' => formatDate($this->security->xss_clean($this->input->post('cd_registered_on'))),
                                    'type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_toa')),
                                    'di_type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_di_toa')),

                                    'reffered_by_judge' => '',
                                    'reffered_by_court' => '',
                                    'name_of_court' => '',
                                    'reffered_by_court_case_type' => '',
                                    'reffered_by_court_case_year' => '',
                                    'arbitrator_status' => $this->security->xss_clean($this->input->post('cd_arbitrator_status')),
                                    'arbitral_tribunal_strength' => '',

                                    'is_registered' => 0,
                                    'case_status' => 'INITIATED',
                                    'remarks' => $this->security->xss_clean($this->input->post('refferal_req_message')),
                                    'created_by' => $this->user_code,
                                    'created_on' => $this->date
                                );

                                if ($this->input->post('cd_reffered_by') == 'COURT') {
                                    $case_details_data['reffered_by_judge'] = $this->security->xss_clean($this->input->post('cd_reffered_by_judge'));
                                    $case_details_data['reffered_by_court'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court'));

                                    $case_details_data['reffered_by_court_case_type'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_type'));
                                    $case_details_data['reffered_by_court_case_year'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_year'));
                                }

                                if ($this->input->post('cd_reffered_by') == 'OTHER') {
                                    // name_of_court = name_of_authority 
                                    $case_details_data['name_of_court'] = $this->security->xss_clean($this->input->post('cd_name_of_court'));
                                }

                                if ($this->input->post('cd_arbitrator_status') == 1) {
                                    $case_details_data['arbitral_tribunal_strength'] = $this->input->post('cd_arbitral_tribunal_strength');
                                }

                                // Insert case details
                                $cs_result = $this->db->insert('cs_case_details_tbl', $case_details_data);

                                if (!$cs_result) {
                                    failure_response('Error while saving data into case', false, true);
                                }


                                // ============================================
                                // Upload the documents if any
                                // ============================================
                                if (!empty(array_filter($_FILES['refferal_req_documents']['name']))) {
                                    $this->load->library('fileupload');

                                    $file_result = $this->fileupload->uploadMultipleFiles($_FILES['refferal_req_documents'], [
                                        'file_name' => 'CASE_FILES_' . time(),
                                        'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
                                        'allowed_file_types' => CASE_DOCUMENTS_FORMAT_ALLOWED,
                                        'allowed_mime_types' => CASE_DOCUMENTS_MIME_ALLOWED,
                                        'max_size' => CASE_DOCUMENTS_SIZE_ALLOWED
                                    ]);

                                    // After getting result of file upload
                                    if ($file_result['status'] == false) {
                                        failure_response('Documents: ' . $file_result['msg'], false, true);
                                    } else {
                                        $caseFileName = $file_result['files'];

                                        if (count($caseFileName) > 0) {
                                            foreach ($caseFileName as $file) {
                                                $csFileResult = $this->db->insert('cs_case_supporting_documents_tbl', [
                                                    'case_code' => $slug,
                                                    'file_name' => $file,
                                                    'record_status' => 1,
                                                    'created_by' => $this->user_code,
                                                    'created_at' => $this->date,
                                                    'updated_by' => $this->user_code,
                                                    'updated_at' => $this->date,
                                                ]);

                                                if (!$csFileResult) {
                                                    failure_response($file . ': Error while saving document', false, true);
                                                }
                                            }
                                        } else {
                                            failure_response('The file you are trying to upload is not uploaded', false, true);
                                        }
                                    }
                                }

                                // ============================================
                                // Insert claimant & respondent
                                // ============================================
                                $claimant_row_count_array = json_decode($this->security->xss_clean($this->input->post('claimant_row_count_array')));

                                $respondant_row_count_array = json_decode($this->security->xss_clean($this->input->post('respondant_row_count_array')));

                                if (count($claimant_row_count_array) < 1 || count($respondant_row_count_array) < 1) {
                                    failure_response('Claimant and respondant are required', false, true);
                                }


                                // Claimant details
                                $cl_result = $this->refferal_request_model->insert_claimant_details($claimant_row_count_array, $_POST, $slug, CLAIMANT_TYPE_CONSTANT);

                                if (!$cl_result['status']) {
                                    failure_response($cl_result['msg'], false, true);
                                }

                                // Respondant details
                                $res_result = $this->refferal_request_model->insert_respondant_details($respondant_row_count_array, $_POST, $slug, RESPONDANT_TYPE_CONSTANT);

                                if (!$res_result['status']) {
                                    failure_response($res_result['msg'], false, true);
                                }

                                // ============================================
                                // Commit the changes
                                success_response_with_data('Case details saved successfully. Fill other details or proceed for final submit.', [
                                    'm_code' => $ref_code
                                ], false, true);
                            } else {
                                failure_response('Error while saving data', false, true);
                            }
                        } catch (Exception $e) {
                            failure_response('Something went wrong, please try again or contact support team', false, true);
                        }
                    } else {
                        failure_response(validation_errors(), false, false);
                    }
                } else {
                    failure_response('Invalid Security Token', false, false);
                }
            } else {
                failure_response('Empty Security Token', false, false);
            }
        } catch (Exception $e) {
            failure_response('Server failed while processing request, please try again or contact team.' . $e->getMessage(), false, true);
        }
    }

    /**
     * Function to perform update refferal request
     */
    public function update()
    {
        $inputCsrfToken = $_POST['csrf_refferal_req_form_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'refferal_req_form')) {

                $this->form_validation->set_rules('cd_case_title', 'Message', 'required|xss_clean');
                $this->form_validation->set_rules('cd_reffered_by', 'Mode of reference', 'required|xss_clean');
                $this->form_validation->set_rules('cd_toa', 'Nature of arbitration', 'required|xss_clean');
                $this->form_validation->set_rules('cd_di_toa', 'Type of Arbitration', 'required|xss_clean');

                $this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'required|xss_clean');

                if ($this->input->post('cd_arbitrator_status') == 1) {
                    $this->form_validation->set_rules('cd_arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
                }

                if ($this->input->post('cd_reffered_by') == 'COURT') {
                    $this->form_validation->set_rules('cd_reffered_by_court_case_type', 'Case Type', 'required|xss_clean');
                    $this->form_validation->set_rules('cd_reffered_by_court_case_year', 'Year', 'required|xss_clean');
                    $this->form_validation->set_rules('cd_case_arb_pet', 'Reference No.', 'required|xss_clean');
                }


                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    try {
                        $slug = $this->input->post('hidden_refferal_req_case_code');

                        // ============================================
                        // update the data into case details table
                        // ============================================

                        $case_details_data = array(
                            'case_title' => $this->input->post('cd_case_title'),
                            'reffered_by' => $this->security->xss_clean($this->input->post('cd_reffered_by')),
                            'reffered_on' => formatDate($this->security->xss_clean($this->input->post('cd_reffered_on'))),
                            'arbitration_petition' => $this->security->xss_clean($this->input->post('cd_case_arb_pet')),
                            'recieved_on' => formatDate($this->security->xss_clean($this->input->post('cd_recieved_on'))),
                            'registered_on' => formatDate($this->security->xss_clean($this->input->post('cd_registered_on'))),
                            'type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_toa')),
                            'di_type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_di_toa')),

                            'reffered_by_judge' => '',
                            'reffered_by_court' => '',
                            'name_of_court' => '',
                            'reffered_by_court_case_type' => '',
                            'reffered_by_court_case_year' => '',
                            'arbitrator_status' => $this->security->xss_clean($this->input->post('cd_arbitrator_status')),
                            'arbitral_tribunal_strength' => '',

                            'is_registered' => 0,
                            'case_status' => 'INITIATED',
                            'remarks' => $this->security->xss_clean($this->input->post('refferal_req_message')),
                            'updated_by' => $this->user_code,
                            'updated_on' => $this->date
                        );

                        if ($this->input->post('cd_reffered_by') == 'COURT') {
                            $case_details_data['reffered_by_judge'] = $this->security->xss_clean($this->input->post('cd_reffered_by_judge'));
                            $case_details_data['reffered_by_court'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court'));

                            $case_details_data['reffered_by_court_case_type'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_type'));
                            $case_details_data['reffered_by_court_case_year'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_year'));
                        }

                        if ($this->input->post('cd_reffered_by') == 'OTHER') {
                            // name_of_court = name_of_authority 
                            $case_details_data['name_of_court'] = $this->security->xss_clean($this->input->post('cd_name_of_court'));
                        }

                        if ($this->input->post('cd_arbitrator_status') == 1) {
                            $case_details_data['arbitral_tribunal_strength'] = $this->input->post('cd_arbitral_tribunal_strength');
                        }

                        // Insert case details
                        $cs_result = $this->db->where([
                            'slug' => $this->input->post('hidden_refferal_req_case_code'),
                            'case_type' => 'REFFERAL_REQUESTS',
                            'reference_code' => $this->input->post('hidden_refferal_req_code'),
                        ])->update('cs_case_details_tbl', $case_details_data);

                        if (!$cs_result) {
                            failure_response('Error while saving data into case', false, true);
                        }


                        // ============================================
                        // Upload the documents if any
                        // ============================================
                        if (!empty(array_filter($_FILES['refferal_req_documents']['name']))) {
                            $this->load->library('fileupload');

                            $file_result = $this->fileupload->uploadMultipleFiles($_FILES['refferal_req_documents'], [
                                'file_name' => 'CASE_FILES_' . time(),
                                'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
                                'allowed_file_types' => CASE_DOCUMENTS_FORMAT_ALLOWED,
                                'allowed_mime_types' => CASE_DOCUMENTS_MIME_ALLOWED,
                                'max_size' => CASE_DOCUMENTS_SIZE_ALLOWED
                            ]);

                            // After getting result of file upload
                            if ($file_result['status'] == false) {
                                failure_response('Documents: ' . $file_result['msg'], false, true);
                            } else {
                                $caseFileName = $file_result['files'];

                                if (count($caseFileName) > 0) {
                                    foreach ($caseFileName as $file) {
                                        $csFileResult = $this->db->insert('cs_case_supporting_documents_tbl', [
                                            'case_code' => $slug,
                                            'file_name' => $file,
                                            'record_status' => 1,
                                            'created_by' => $this->user_code,
                                            'created_at' => $this->date,
                                            'updated_by' => $this->user_code,
                                            'updated_at' => $this->date,
                                        ]);

                                        if (!$csFileResult) {
                                            failure_response($file . ': Error while saving document', false, true);
                                        }
                                    }
                                } else {
                                    failure_response('The file you are trying to upload is not uploaded', false, true);
                                }
                            }
                        }

                        // ============================================
                        // Insert claimant & respondent
                        // ============================================
                        $claimant_row_count_array = json_decode($this->security->xss_clean($this->input->post('claimant_row_count_array')));

                        $respondant_row_count_array = json_decode($this->security->xss_clean($this->input->post('respondant_row_count_array')));

                        if (count($claimant_row_count_array) < 1 || count($respondant_row_count_array) < 1) {
                            failure_response('Claimant and respondant are required', false, true);
                        }


                        // Claimant details
                        $cl_result = $this->refferal_request_model->insert_claimant_details($claimant_row_count_array, $_POST, $slug, 'claimant', "UPDATE");

                        if (!$cl_result['status']) {
                            failure_response($cl_result['msg'], false, true);
                        }

                        // Respondant details
                        $res_result = $this->refferal_request_model->insert_respondant_details($respondant_row_count_array, $_POST, $slug, 'respondant', "UPDATE");

                        if (!$res_result['status']) {
                            failure_response($res_result['msg'], false, true);
                        }

                        // ============================================
                        // If arbitrator status is to be appointed : then remove all the arbitrators if added
                        // ============================================
                        if ($this->input->post('cd_arbitrator_status') == 2) {
                            $arb_data = [
                                'status' => 0
                            ];

                            $result2 = $this->db->where([
                                'case_no' => $this->input->post('hidden_refferal_req_case_code')
                            ])->update('cs_arbitral_tribunal_tbl', $arb_data);

                            if (!$result2) {
                                failure_response('Error while saving arbitrator data', false, true);
                            }
                        }

                        // ============================================
                        // Commit the changes
                        success_response_with_data('Data updated successfully.', [
                            'm_code' => $this->input->post('hidden_refferal_req_code')
                        ], false, true);
                    } catch (Exception $e) {
                        failure_response('Something went wrong, please try again or contact support team', false, true);
                    }
                } else {
                    failure_response(validation_errors(), false, false);
                }
            } else {
                failure_response('Invalid Security Token', false, false);
            }
        } else {
            failure_response('Empty Security Token', false, false);
        }
    }

    /**
     * Final submit
     */

    public function final_submit()
    {
        try {
            $inputCsrfToken = $_POST['csrf_refferal_req_form_token'];
            if ($inputCsrfToken != '') {
                if (checkToken($inputCsrfToken, 'refferal_req_final_submit_form')) {

                    $this->form_validation->set_rules('hidden_refferal_req_code', 'Code', 'required|xss_clean');

                    if ($this->form_validation->run()) {
                        $this->db->trans_begin();

                        try {

                            $mtData = array(
                                'is_approved' => 1,
                                'is_submitted' => 1,
                                'updated_by' => $this->user_code,
                                'updated_at' => $this->date
                            );

                            // Condition to update the record
                            $condition = [
                                'm_code' => $this->input->post('hidden_refferal_req_code')
                            ];

                            $result = $this->refferal_request_model->update($mtData, $condition);

                            if ($result) {

                                $case_details_data = $this->refferal_request_model->getRefferalReqDataUsingCode($this->input->post('hidden_refferal_req_code'));

                                if (!$case_details_data) {
                                    failure_response('No data found for this record', false, false);
                                }

                                // ============================================
                                // Generate the diac registration no.
                                $case_no = generate_diac_reg_number();
                                $case_no_year = date('m-y');

                                if ($case_details_data['type_of_arbitration'] == 'INTERNATIONAL') {
                                    $case_no_prefix = generate_diac_int_reg_number_prefix();
                                } else {
                                    $case_no_prefix = generate_diac_reg_number_prefix();
                                }

                                $full_case_no = $case_no_prefix . '/' . $case_no . '/' . $case_no_year;

                                $case_no_slug = $case_details_data['slug'];

                                // Update the case number
                                $csResult = $this->db->where([
                                    'slug' => $case_no_slug
                                ])->update('cs_case_details_tbl', [
                                    'case_no_prefix' => $case_no_prefix,
                                    'case_no' => $case_no,
                                    'case_no_year' => $case_no_year,
                                    'is_registered' => 1,
                                    'case_status' => 'UNDER_PROCESS'
                                ]);

                                if (!$csResult) {
                                    failure_response('Error while updating case number. ' . SERVER_ERROR, false, true);
                                }

                                // Allot the case to respective case managers and deputy counsels
                                $deputy_counsel = automatic_case_allotment($case_no, 'DEPUTY_COUNSEL');
                                $case_manager = automatic_case_allotment($case_no, 'CASE_MANAGER');
                                $additional_coordinator = automatic_case_allotment($case_no, 'COORDINATOR');

                                $dc_allotment_user_code = $deputy_counsel['user_code'];
                                $cm_allotment_user_code = $case_manager['user_code'];
                                $coordinator_allotment_user_code = $additional_coordinator['user_code'];

                                $allotment_data = [];

                                array_push($allotment_data, [
                                    'case_no' => $case_no_slug,
                                    'alloted_by' => $this->user_code,
                                    'alloted_to' => $dc_allotment_user_code,
                                    'user_role' => 'DEPUTY_COUNSEL',
                                    'status' => 1,
                                    'created_at' => currentDateTimeStamp(),
                                    'created_by' => $this->user_code,
                                    'updated_at' => currentDateTimeStamp(),
                                    'updated_by' => $this->user_code,
                                ]);

                                array_push($allotment_data, [
                                    'case_no' => $case_no_slug,
                                    'alloted_by' => $this->user_code,
                                    'alloted_to' => $cm_allotment_user_code,
                                    'user_role' => 'CASE_MANAGER',
                                    'status' => 1,
                                    'created_at' => currentDateTimeStamp(),
                                    'created_by' => $this->user_code,
                                    'updated_at' => currentDateTimeStamp(),
                                    'updated_by' => $this->user_code,
                                ]);

                                array_push($allotment_data, [
                                    'case_no' => $case_no_slug,
                                    'alloted_by' => $this->user_code,
                                    'alloted_to' => $coordinator_allotment_user_code,
                                    'user_role' => 'COORDINATOR',
                                    'status' => 1,
                                    'created_at' => currentDateTimeStamp(),
                                    'created_by' => $this->user_code,
                                    'updated_at' => currentDateTimeStamp(),
                                    'updated_by' => $this->user_code,
                                ]);

                                $allotment_res = $this->db->insert_batch('cs_case_allotment_tbl', $allotment_data);

                                if (!$allotment_res) {
                                    failure_response('Error while automatic case allotment. ' . SERVER_ERROR, false, true);
                                }

                                // ============================================
                                // Get the claimant and respondent data
                                $case_claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, CLAIMANT_TYPE_CONSTANT);

                                $claimants = array_filter($case_claimants, function ($claimant) {
                                    return !empty($claimant['email']);
                                });
                                $claimants_emails_temp = array_column($claimants, 'email');

                                // ============================================
                                // Get the COUNSELS details of claimants
                                $case_claimants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, CLAIMANT_TYPE_CONSTANT);

                                $claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
                                    return !empty($counsel['email']);
                                });
                                $claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

                                // ================================================
                                // Respondents =====================
                                $case_respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, RESPONDANT_TYPE_CONSTANT);
                                $respondants = array_filter($case_respondants, function ($claimant) {
                                    return !empty($claimant['email']);
                                });

                                $respondants_emails_temp = array_column($respondants, 'email');

                                // ============================================
                                // Get the COUNSELS details of claimants
                                $case_respondants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, RESPONDANT_TYPE_CONSTANT);

                                $respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
                                    return !empty($counsel['email']);
                                });
                                $respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

                                // ================================================
                                // Get arbitrators of the case
                                $case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);

                                // print_r($arbitrators);
                                // die;
                                $arbitrators = array_filter($case_arbitrators, function ($arbitrator) {
                                    return !empty($arbitrator['email']);
                                });
                                $arbitrators_emails_temp = array_column($arbitrators, 'email');

                                // ================================================
                                // Get all the email ids
                                // ==================================
                                // Merge all the emails
                                // Additional email arrays
                                $additional_emails = [];
                                array_push($additional_emails, $deputy_counsel['email']);
                                array_push($additional_emails, $case_manager['email']);
                                array_push($additional_emails, $additional_coordinator['email']);

                                // Merge all email arrays into a single array
                                $all_emails = array_merge(
                                    $claimants_emails_temp,
                                    $claimant_counsels_emails_temp,
                                    $respondants_emails_temp,
                                    $respondant_counsels_emails_temp,
                                    $arbitrators_emails_temp,
                                    $additional_emails
                                );

                                // Implode the merged array with commas
                                $all_emails_string = implode(',', $all_emails);

                                // ========================================================
                                // Create an array of all the persons who are involved in the case
                                $persons = [
                                    'claimants' => $case_claimants,
                                    'case_claimants_counsels' => $case_claimants_counsels,
                                    'respondants' => $case_respondants,
                                    'case_respondants_counsels' => $case_respondants_counsels,
                                    'arbitrators' => $case_arbitrators,
                                    'deputy_counsel' => $deputy_counsel,
                                    'case_manager' => $case_manager,
                                    'additional_coordinator' => $additional_coordinator,
                                ];

                                // Load the data
                                $view_data['persons'] = $persons;
                                $view_data['case_details_data'] = $case_details_data;
                                $view_data['full_case_no'] = $full_case_no;

                                // Send emails to everyone
                                $subject = 'Registration of fresh case with DIAC';

                                $email_html_body = $this->load->view('emails/case_registered_info', $view_data, true);

                                $deputy_counsel_smtp_email = (isset($deputy_counsel['email']) && !empty($deputy_counsel['email'])) ? $deputy_counsel['email'] : DEFAULT_SMTP_EMAIL_ID;

                                // echo strtolower($all_emails_string);
                                // die;
                                $email_send_result = case_registered_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);
                                // $email_send_result = [
                                //     'status' => true,
                                // ];

                                // $this->db->trans_rollback();
                                // print_r($email_send_result);
                                // die;

                                // Email log data
                                $insert_mail_log = [
                                    "transaction_id"     => $case_no_slug,
                                    'mail_type'         => 'CASE_REGISTERED',
                                    "to_mail"             => strtolower($all_emails_string),
                                    "subject"             => $subject,
                                    "body"                 => $email_html_body,
                                    "status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                    "created_by"         => $this->session->userdata('user_code'),
                                    "created_at"         => currentDateTimeStamp()
                                ];
                                $result_mail = $this->db->insert('support_mail_record', $insert_mail_log);

                                // die;

                                if ($email_send_result['status']) {
                                    // ============================================
                                    // Commit the changes
                                    success_response('Case registered and allocated to respective persons successfully. DIAC registeration number is:' . $full_case_no, false, true);
                                } else {
                                    // ============================================
                                    // Commit the changes with the send email failed error
                                    success_response('Case registered and allocated to respective persons successfully but email sending failed due to SMTP server availability. DIAC registeration number is:' . $full_case_no, false, true);
                                }
                            } else {
                                failure_response('Error while saving data', false, true);
                            }
                        } catch (Exception $e) {
                            failure_response('Something went wrong, please try again or contact support team', false, true);
                        }
                    } else {
                        failure_response(validation_errors(), false, false);
                    }
                } else {
                    failure_response('Invalid Security Token', false, false);
                }
            } else {
                failure_response('Empty Security Token', false, false);
            }
        } catch (Exception $e) {
            failure_response('Server failed while processing request, please try again or contact team.' . $e->getMessage(), false, true);
        }
    }

    // Delete the miscellaneous
    public function delete_miscellaneous()
    {
        $inputCsrfToken = $_POST['csrf_trans_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'dataTableMiscellaneous')) {
                $this->form_validation->set_rules('id', 'Id', 'required|xss_clean');

                if ($this->form_validation->run()) {
                    $id = $this->security->xss_clean($data['id']);

                    $result = $this->db->where('id', $id)->update('miscellaneous_tbl', [
                        'record_status' => 0,
                        'updated_at' => $this->date,
                        'updated_by' => $this->user_code
                    ]);

                    if ($result) {
                        success_response('Referrals/Requests deleted successfully', false, true);
                    } else {
                        failure_response('Error while deleting data', false, true);
                    }
                } else {
                    failure_response(validation_errors(), false, false);
                }
            } else {
                failure_response('Invalid Security Token', false, false);
            }
        } else {
            failure_response('Empty Security Token', false, false);
        }
    }

    /**
     * Function to view the details
     */
    public function view()
    {
        $code = $this->input->get('code');

        if (!$code) {
            return redirect('page-not-found');
        }

        $data['refferal_req'] = $this->refferal_request_model->getRefferalReqDataUsingCode($code);

        $case_slug = $data['refferal_req']['slug'];

        // get claimants and respondants details 
        $data['claimants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($case_slug, 'claimant');
        $data['respondants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($case_slug, 'respondant');

        // get arbitrators details
        if ($data['refferal_req']['arbitrator_status'] == 1) {
            $data['arbitrator_list'] = $this->arbitrator_setup_model->getArbitratorsLists($case_slug);
        }

        // echo "<pre>";
        // print_r($data['arbitrator_list']);
        // die;

        if (!$data['refferal_req']) {
            return redirect('page-not-found');
        }

        $sidebar['menu_item'] = 'All List';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'View Details - Referrals/Requests: ' . $data['refferal_req']['case_no_prefix'] . '/' . $data['refferal_req']['case_no'] . '/' . $data['refferal_req']['case_no_year'];

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            // Get case arbitrator list
            $data['arbitrators'] = $this->arbitral_tribunal_model->getCaseArbitratorsUsingCaseCode($data['refferal_req']['slug']);

            // Get the case files
            $data['case_supporting_docs'] = $this->common_model->get_case_supporting_doc_using_case_code($data['refferal_req']['slug']);

            // print_r($data['arbitrators']);
            // die;

            $this->load->view('refferal_requests/view', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to view and change the status of the case
     */
    public function view_and_approval()
    {
        $code = $this->input->get('code');

        if (!$code) {
            return redirect('page-not-found');
        }

        $data['refferal_req'] = $this->refferal_request_model->getRefferalReqDataUsingCode($code);

        // echo '<pre>';
        // print_r($data['refferal_req']);
        // die;

        if (!$data['refferal_req']) {
            return redirect('page-not-found');
        }

        $sidebar['menu_item'] = 'All List';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'View and Approval - Referrals/Requests';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            // Get case arbitrator list
            $data['arbitrators'] = $this->arbitral_tribunal_model->getCaseArbitratorsUsingCaseCode($data['refferal_req']['slug']);

            // Get the case files
            $data['case_supporting_docs'] = $this->common_model->get_case_supporting_doc_using_case_code($data['refferal_req']['slug']);

            // print_r($data['arbitrators']);
            // die;

            $this->load->view('refferal_requests/view_and_approval', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }


    /**
     * Function to change the status of refferal requests
     */
    public function change_status()
    {
        $inputCsrfToken = $_POST['csrf_refferal_req_form_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'refferal_req_approval_form')) {

                $this->form_validation->set_rules('hidden_refferal_req_code', 'Code', 'required|xss_clean');
                $this->form_validation->set_rules('approval_rejection_status', 'Status', 'required|xss_clean');
                // $this->form_validation->set_rules('approval_rejection_remarks', 'Remarks', 'required|xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    try {

                        $mtData = array(
                            'is_approved' => $this->input->post('approval_rejection_status'),
                            'remarks' => $this->input->post('approval_rejection_remarks'),
                            'updated_by' => $this->user_code,
                            'updated_at' => $this->date
                        );

                        // Condition to update the record
                        $condition = [
                            'm_code' => $this->input->post('hidden_refferal_req_code')
                        ];

                        $result = $this->refferal_request_model->update($mtData, $condition);

                        if ($result) {

                            $case_status = 'PENDING';
                            if ($this->input->post('approval_rejection_status') == 1) {
                                $case_status = 'APPROVED';
                            }
                            if ($this->input->post('approval_rejection_status') == 2) {
                                $case_status = 'REJECTED';
                            }

                            $case_details_data = array(
                                // 'is_registered' => $this->input->post('approval_rejection_status'),
                                'case_status' => $case_status,
                                'remarks' => $this->input->post('approval_rejection_remarks'),
                                'created_by' => $this->user_code,
                                'created_on' => $this->date
                            );

                            // Condition to update the record
                            $condition = [
                                'case_type' => 'REFFERAL_REQUESTS',
                                'reference_code' => $this->input->post('hidden_refferal_req_code')
                            ];

                            // Insert case details
                            $cs_result = $this->db->where($condition)->update('cs_case_details_tbl', $case_details_data);

                            if (!$cs_result) {
                                failure_response('Error while saving data into case', false, true);
                            }

                            $message = 'Status of refferal/request application changed successfully';
                            if ($this->input->post('approval_rejection_status') == 1) {
                                $message = 'Case approved and sent back successfully for registration';
                            }
                            if ($this->input->post('approval_rejection_status') == 2) {
                                $message = 'Case rejected successfully';
                            }

                            // ============================================
                            // Commit the changes
                            success_response($message, false, true);
                        } else {
                            failure_response('Error while saving data', false, true);
                        }
                    } catch (Exception $e) {
                        failure_response('Something went wrong, please try again or contact support team', false, true);
                    }
                } else {
                    failure_response(validation_errors(), false, false);
                }
            } else {
                failure_response('Invalid Security Token', false, false);
            }
        } else {
            failure_response('Empty Security Token', false, false);
        }
    }

    /**
     * Function to register and allocate the case to respective persons
     */
    public function register_and_allocate_case()
    {

        if (!$this->input->post('m_code')) {
            failure_response('Invalid operation performed', false, false);
        }

        $case_details_data = $this->refferal_request_model->getRefferalReqDataUsingCode($this->input->post('m_code'));

        if (!$case_details_data) {
            failure_response('No data found for this record', false, false);
        }

        // Generate the diac registration no.
        $case_no_prefix = generate_diac_reg_number_prefix();
        $case_no = generate_diac_reg_number();

        // Update the case number
        $csResult = $this->db->where([
            'slug' => $case_details_data['slug']
        ])->update('cs_case_details_tbl', [
            'case_no_prefix' => $case_no_prefix,
            'case_no' => $case_no,
            'is_registered' => 1,
            'case_status' => 'UNDER_PROCESS'
        ]);

        if (!$csResult) {
            failure_response('Error while updating case number. ' . SERVER_ERROR, false, true);
        }

        // Allot the case to respective case managers and deputy counsels
        $al_result = automatic_case_allotment($case_no, 'DEPUTY_COUNSEL');
        $al_result2 = automatic_case_allotment($case_no, 'CASE_MANAGER');
        $al_result3 = automatic_case_allotment($case_no, 'COORDINATOR');

        $dc_allotment_user_code = $al_result['user_code'];
        $cm_allotment_user_code = $al_result2['user_code'];
        $coordinator_allotment_user_code = $al_result3['user_code'];

        $allotment_data = [];

        array_push($allotment_data, [
            'case_no' => $case_details_data['slug'],
            'alloted_by' => $this->user_code,
            'alloted_to' => $dc_allotment_user_code,
            'user_role' => 'DEPUTY_COUNSEL',
            'status' => 1,
            'created_at' => currentDateTimeStamp(),
            'created_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
        ]);

        array_push($allotment_data, [
            'case_no' => $case_details_data['slug'],
            'alloted_by' => $this->user_code,
            'alloted_to' => $cm_allotment_user_code,
            'user_role' => 'CASE_MANAGER',
            'status' => 1,
            'created_at' => currentDateTimeStamp(),
            'created_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
        ]);

        array_push($allotment_data, [
            'case_no' => $case_details_data['slug'],
            'alloted_by' => $this->user_code,
            'alloted_to' => $coordinator_allotment_user_code,
            'user_role' => 'COORDINATOR',
            'status' => 1,
            'created_at' => currentDateTimeStamp(),
            'created_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
        ]);

        $allotment_res = $this->db->insert_batch('cs_case_allotment_tbl', $allotment_data);

        if (!$allotment_res) {
            failure_response('Error while automatic case allotment. ' . SERVER_ERROR, false, true);
        }

        success_response('DIAC number allocated successfully and case allotted to respective counsels and case managers', false, true);
    }

    public function miscellaneous_reply()
    {
        $sidebar['menu_item'] = 'Referrals/Requests';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Referrals/Requests Reply';

        if ($page_status != 0) {

            if (!$this->input->get('miscellaneous_id')) {
                return redirect('page-not-found');
            }
            $data['miscellaneous_id'] = $this->input->get('miscellaneous_id');

            // Update the notings notifications
            $this->notification_model->post([
                'type_table' => 'miscellaneous_replies_tbl',
                'reference_id' => $data['miscellaneous_id']
            ], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');

            $data['miscellaneous'] = $this->refferal_request_model->get(['id' => $data['miscellaneous_id']], 'GET_MISCELLANEOUS_DATA_USING_ID');
            if (count($data['miscellaneous']) < 1) {
                return redirect('page-not-found');
            }
            $data['miscellaneous_marked_to'] = $this->refferal_request_model->get(['id' => $data['miscellaneous_id']], 'GET_MISCELLANEOUS_MARKED_TO_USING_ID');

            $data['reply_to_user'] = $this->refferal_request_model->get(['miscellaneous_id' => $data['miscellaneous_id']], 'ALL_MISCELLANEOUS_MARKED_USERS');
            $this->load->view('diac-admin/miscellaneous-reply', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }
}
