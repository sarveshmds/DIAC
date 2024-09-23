
<?php defined('BASEPATH') or exit('No direct script access allowed');

class New_reference_controller extends CI_Controller
{
    public $user_code;

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        # helpers
        $this->load->helper(array('form'));

        # libraries
        $this->load->library('encryption');

        # models
        $this->load->model(['superadmin_model', 'getter_model', 'common_model', 'new_reference_model']);

        $this->user_code = $this->session->userdata('user_code');
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
            'ADVOCATE' => array('filed_cases', 'file_new_case', 'store_new_reference', 'check_and_verify_new_reference', 'get_new_ref_datatable_list', 'proceed_payment', 'edit_new_reference', 'update_new_reference', 'payment_success', 'view_new_reference'),
            'PARTY' => array('filed_cases', 'file_new_case', 'store_new_reference', 'check_and_verify_new_reference', 'get_new_ref_datatable_list', 'proceed_payment', 'edit_new_reference', 'update_new_reference', 'payment_success', 'view_new_reference'),
            'ARBITRATOR' => array(''),
            'ADVOCATE_ARBITRATOR' => array('filed_cases', 'file_new_case', 'store_new_reference', 'check_and_verify_new_reference', 'get_new_ref_datatable_list', 'proceed_payment', 'edit_new_reference', 'update_new_reference', 'payment_success', 'view_new_reference'),
        );

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('efiling/logout');
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


    public function filed_cases()
    {
        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'New References';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/filed_cases');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function get_new_ref_datatable_list()
    {
        $order = '';
        $Ocolumn = '';
        $Odir = '';
        $order = $this->input->post('order');
        if ($order) {
            foreach ($order as $row) {
                $Ocolumn = $row['column'];
                $Odir = $row['dir'];
            }
            $this->db->order_by($Ocolumn, $Odir);
        } else {
            $this->db->order_by(1, "ASC");
        }
        $search = $this->input->post('search');
        $header = array('app_type'); //search filter will work on this column
        if ($search['value'] != '') {
            for ($i = 0; $i < count($header); $i++) {
                $this->db->or_like($header[$i], $search['value']);
            }
        }
        $iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
        $iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

        $this->db->select('ert.*, gcd.description as type_of_arbitration_desc, gcd2.description as case_type_desc, gcd3.description as payment_status_desc, cdt.case_no');
        $this->db->from('efiling_new_reference_tbl ert');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = ert.payment_status AND gcd3.gen_code_group = 'PAYMENT_STATUS'", 'left');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.reference_code = ert.nr_code AND cdt.case_type = 'NEW_REFERENCE'", 'left');
        $this->db->where('ert.user_code', $this->session->userdata('user_code'));
        $this->db->order_by('ert.id', 'DESC');

        $tempDb = clone $this->db;
        $this->db->limit($iDisplayLength, $iDisplayStart);

        $res = $this->db->get();
        $query = $res->result_array();
        $output = array("aaData" => array());

        /*----FOR PAGINATION-----*/
        $res1 = $tempDb->get();
        $output["draw"] = intval($this->input->post('draw'));
        $output['iTotalRecords'] = $res1->num_rows();
        $output['iTotalDisplayRecords'] = $res1->num_rows();
        $slno = $iDisplayStart + 1;

        foreach ($query as $aRow) {
            $row[0] = $slno;
            $row['sl_no'] = $slno;
            $i = 1;
            foreach ($aRow as $key => $value) {

                $row[$i] = $value;
                $row[$key] = $value;
                $i++;
            }
            $output['aaData'][] = $row;
            $slno++;
            unset($row);
        }
        echo json_encode($output);
        die;
    }

    public function file_new_case()
    {
        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'File New Reference';

            $data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
            $data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/file_new_case');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function edit_new_reference()
    {
        if (!$this->input->get('id')) {
            return redirect(base_url('efiling/new-references'));
        }

        // Get the id
        $id = $this->input->get('id');

        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Edit New Reference';
            $data['edit_form'] = true;

            $data['new_reference'] = $this->new_reference_model->get_single_new_reference_data_using_id($id);
            $data['new_reference_claimant'] = $this->new_reference_model->get_new_reference_claimants_using_id($id);
            $data['new_reference_respondants'] = $this->new_reference_model->get_new_reference_respondants_using_id($id);

            $data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
            $data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/file_new_case');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function store_new_reference()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'file_new_case_form')) {

                // Validation Code ===============
                $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required');
                $this->form_validation->set_rules('case_type', 'Case Type', 'required');
                $this->form_validation->set_rules('arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required');

                $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required');

                if ($this->input->post('type_of_arbitration') && $this->input->post('type_of_arbitration') == 'DOMESTIC') {
                    $this->form_validation->set_rules('arb_total_fees', 'Arbitrator Fees', 'required');
                    $this->form_validation->set_rules('arb_your_share_fees', 'Arbitrator Fees (Your share)', 'required');
                    $this->form_validation->set_rules('adm_charges', 'Administrative Charges', 'required');
                    $this->form_validation->set_rules('your_share', 'Your share', 'required');
                    $this->form_validation->set_rules('your_payable_share', 'Your payable share', 'required');
                }

                if ($this->input->post('type_of_arbitration') && $this->input->post('type_of_arbitration') == 'INTERNATIONAL') {
                    $this->form_validation->set_rules('int_arb_total_fees_dollar', 'Total Arbitrator Fees (dollar)', 'required');
                    $this->form_validation->set_rules('int_arb_total_fees_rupee', 'Total Arbitrator Fees (rupee)', 'required');

                    $this->form_validation->set_rules('int_arb_your_share_fees_dollar', 'Arbitrator fees your share (dollar)', 'required');
                    $this->form_validation->set_rules('int_arb_your_share_fees_rupee', 'Arbitrator fees your share (rupee)', 'required');

                    $this->form_validation->set_rules('int_total_adm_charges_dollar', 'Total administrative charges (dollar)', 'required');
                    $this->form_validation->set_rules('int_total_adm_charges_rupee', 'Total administrative charges (rupee)', 'required');

                    $this->form_validation->set_rules('int_each_party_adm_charges_dollar', 'Each party administrative charges (dollar)', 'required');
                    $this->form_validation->set_rules('int_each_party_adm_charges_rupee', 'Each party administrative charges (rupee)', 'required');
                    $this->form_validation->set_rules('int_your_share_dollar', 'Your share dollar', 'required');
                    $this->form_validation->set_rules('int_your_share_rupee', 'Your share rupee', 'required');

                    $this->form_validation->set_rules('int_your_payable_share_dollar', 'Your payable share dollar', 'required');
                    $this->form_validation->set_rules('int_your_payable_share_rupee', 'Your payable share rupee', 'required');
                }

                // Check for the emergency case files
                if ($this->input->post('case_type') == 'EMERGENCY') {
                    if ($_FILES['soc_document']['name'] == '') {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Please upload soc document before submitting form.'
                        ]);
                        die;
                    }
                    if ($_FILES['urgency_app_document']['name'] == '') {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Please upload urgency application document before submitting form.'
                        ]);
                        die;
                    }
                    if ($_FILES['proof_of_service_doc']['name'] == '') {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Please upload proof of service document before submitting form.'
                        ]);
                        die;
                    }
                }


                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {
                    $claimant_row_count_array = json_decode($this->security->xss_clean($this->input->post('claimant_row_count_array')));

                    $respondant_row_count_array = json_decode($this->security->xss_clean($this->input->post('respondant_row_count_array')));

                    if (count($claimant_row_count_array) < 1 || count($respondant_row_count_array) < 1) {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Claimant and respondant are required.'
                        ]);
                        die;
                    }

                    // Begin the transaction ===================
                    $this->db->trans_begin();

                    // Check the row count from table
                    $rowCount = $this->new_reference_model->checkCount();
                    $diaryNumber = generateDiaryNumber($rowCount, 'NR');

                    // Calculate Fees Library ===================
                    $this->load->library('calculate_fees');

                    $fees = $this->calculate_fees->new_reference_caluclate_fees($this->input->post('claim_amount'), $this->input->post('arbitral_tribunal_strength'), $this->input->post('type_of_arbitration'), $this->input->post('case_type'));

                    if ($fees == false) {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Invalid amount provided'
                        ]);
                        die;
                    }
                    $nr_code = generateCode();
                    $data_arr = array(
                        'nr_code' => $nr_code,
                        'user_code' => $this->session->userdata('user_code'),
                        'diary_number' => $diaryNumber,
                        'type_of_arbitration' => $this->input->post('type_of_arbitration'),
                        'case_type' => $this->input->post('case_type'),
                        'arbitral_tribunal_strength' => $this->input->post('arbitral_tribunal_strength'),
                        'claim_amount' => $this->input->post('claim_amount'),
                        'arb_total_fees' => '',
                        'arb_your_share_fees' => '',
                        'adm_charges' => '',
                        'your_share' => '',
                        'arb_your_share_fees_payable' => '',
                        'your_payable_share' => '',
                        'int_arb_total_fees_dollar' => '',
                        'int_arb_total_fees_rupee' => '',
                        'int_arb_your_share_fees_dollar' => '',
                        'int_arb_your_share_fees_rupee' => '',
                        'int_total_adm_charges_dollar' => '',
                        'int_total_adm_charges_rupee' => '',
                        'int_each_party_adm_charges_dollar' => '',
                        'int_each_party_adm_charges_rupee' => '',
                        'int_your_share_dollar' => '',
                        'int_your_share_rupee' => '',
                        'int_your_payable_share_dollar' => '',
                        'int_your_payable_share_rupee' => '',
                        'current_dollar_price' => '',
                        'document' => '',
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 0, // 0 for initiated
                        'created_by' => $this->session->userdata('user_code'),
                        'created_at' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_at' => currentDateTimeStamp(),
                    );

                    if ($this->input->post('type_of_arbitration') == 'DOMESTIC') {
                        $data_arr['arb_total_fees'] = $fees['total_arbitrator_fees'];
                        $data_arr['arb_your_share_fees'] = $fees['each_party_share_in_arb_fees'];
                        $data_arr['adm_charges'] = $fees['administrative_charges'];
                        $data_arr['your_share'] = $fees['each_party_payable'];
                        $data_arr['arb_your_share_fees_payable'] = $fees['arb_your_share_fees_payable'];
                        $data_arr['your_payable_share'] = $fees['your_payable_share'];
                    }

                    if ($this->input->post('type_of_arbitration') == 'INTERNATIONAL') {
                        $data_arr['int_arb_total_fees_dollar'] = $fees['total_arbitrator_fees_dollar'];
                        $data_arr['int_arb_total_fees_rupee'] = $fees['total_arbitrator_fees_rupee'];
                        $data_arr['int_arb_your_share_fees_dollar'] = $fees['each_party_share_in_arb_fees_dollar'];
                        $data_arr['int_arb_your_share_fees_rupee'] = $fees['each_party_share_in_arb_fees_rupee'];
                        $data_arr['int_total_adm_charges_dollar'] = $fees['administrative_charges_dollar'];
                        $data_arr['int_total_adm_charges_rupee'] = $fees['administrative_charges_rupee'];
                        $data_arr['int_each_party_adm_charges_dollar'] = $fees['each_party_administrative_charges_dollar'];
                        $data_arr['int_each_party_adm_charges_rupee'] = $fees['each_party_administrative_charges_rupee'];
                        $data_arr['int_your_share_rupee'] = $fees['each_party_payable_rupee'];
                        $data_arr['int_your_share_dollar'] = $fees['each_party_payable_dollar'];
                        $data_arr['int_your_payable_share_dollar'] = $fees['your_payable_share_dollar'];
                        $data_arr['int_your_payable_share_rupee'] = $fees['your_payable_share_rupee'];
                        $data_arr['current_dollar_price'] = current_dollar_price();
                    }

                    // Load the fileupload library
                    $this->load->library('fileupload');

                    // Upload the file ==============================================
                    if ($_FILES['case_document']['name'] != '') {

                        // Upload files ==============
                        $case_document_result = $this->fileupload->uploadSingleFile($_FILES['case_document'], [
                            'raw_file_name' => 'case_document',
                            'file_name' => 'CASE_DOC_' . time(),
                            'file_move_path' => EFILING_NEW_REFERENCE_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($case_document_result['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode($case_document_result);
                            die;
                        } else {
                            $data_arr['document'] = $case_document_result['file'];
                        }
                    }


                    // Upload the soc document ==============================================
                    if ($_FILES['soc_document']['name'] != '') {

                        // Upload files ==============
                        $soc_document_result = $this->fileupload->uploadSingleFile($_FILES['soc_document'], [
                            'raw_file_name' => 'soc_document',
                            'file_name' => 'SOC_DOC_' . time(),
                            'file_move_path' => EFILING_NF_SOC_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($soc_document_result['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode($soc_document_result);
                            die;
                        } else {
                            $data_arr['soc_document'] = $soc_document_result['file'];
                        }
                    }

                    // Upload the urgency application document ==============================================
                    if ($_FILES['urgency_app_document']['name'] != '') {

                        // Upload files ==============
                        $urgency_app_document_result = $this->fileupload->uploadSingleFile($_FILES['urgency_app_document'], [
                            'raw_file_name' => 'urgency_app_document',
                            'file_name' => 'URG_APP_DOC_' . time(),
                            'file_move_path' => EFILING_NF_URGENCY_DOC_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($urgency_app_document_result['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode($urgency_app_document_result);
                            die;
                        } else {
                            $data_arr['urgency_app_document'] = $urgency_app_document_result['file'];
                        }
                    }

                    // Upload the proof of service document ==============================================
                    if ($_FILES['proof_of_service_doc']['name'] != '') {

                        // Upload files ==============
                        $proof_of_service_doc_result = $this->fileupload->uploadSingleFile($_FILES['proof_of_service_doc'], [
                            'raw_file_name' => 'proof_of_service_doc',
                            'file_name' => 'PROOF_OF_SER_' . time(),
                            'file_move_path' => EFILING_NF_POS_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($proof_of_service_doc_result['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode($proof_of_service_doc_result);
                            die;
                        } else {
                            $data_arr['proof_of_service_doc'] = $proof_of_service_doc_result['file'];
                        }
                    }

                    // Insert the data into table
                    $result = $this->new_reference_model->insert($data_arr);

                    if ($result) {

                        // get last generated id
                        $new_record = $this->db->from('efiling_new_reference_tbl')
                            ->where('diary_number', $diaryNumber)
                            ->get()
                            ->row_array();


                        // Insert claimant & respondent
                        // Type = 1 for claimant
                        // Type = 2 for respondent
                        $cl_result = $this->new_reference_model->insert_new_ref_claimant($claimant_row_count_array, $_POST, $nr_code, 1);

                        if (!$cl_result['status']) {
                            echo  json_encode($cl_result);
                            return;
                        }

                        $res_result = $this->new_reference_model->insert_new_ref_respondant($respondant_row_count_array, $_POST, $nr_code, 2);

                        if (!$res_result['status']) {
                            echo  json_encode($res_result);
                            return;
                        }

                        $this->db->trans_commit();
                        echo  json_encode([
                            'status' => true,
                            'redirect_link' => base_url('efiling/new-reference/check-verify?id=' . urlencode(base64_encode($new_record['id'])))
                        ]);
                        return;
                    } else {
                        $this->db->trans_rollback();
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function check_and_verify_new_reference()
    {
        if (!$this->input->get('id')) {
            return redirect(base_url('efiling/new-references'));
        }

        // Get the id
        $id = base64_decode(urldecode($this->input->get('id')));

        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'New Reference - (Check & Verify)';
            $data['new_reference'] = $this->new_reference_model->get_single_new_reference_data_using_id($id);
            $data['new_reference_claimant'] = $this->new_reference_model->get_new_reference_claimants_using_code($data['new_reference']['nr_code']);
            $data['new_reference_respondants'] = $this->new_reference_model->get_new_reference_respondants_using_code($data['new_reference']['nr_code']);

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/check_verify_new_reference');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function update_new_reference()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'file_new_case_form')) {

                // Validation Code ===============
                $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required');
                $this->form_validation->set_rules('case_type', 'Case Type', 'required');
                $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required');
                // $this->form_validation->set_rules('arb_total_fees', 'Arbitrator Fees', 'required');
                // $this->form_validation->set_rules('arb_your_share_fees', 'Arbitrator Fees (Your share)', 'required');
                // $this->form_validation->set_rules('adm_charges', 'Administrative Charges', 'required');
                // $this->form_validation->set_rules('your_payable_share', 'Your payable share', 'required');

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {
                    $claimant_row_count_array = json_decode($this->security->xss_clean($this->input->post('claimant_row_count_array')));
                    $respondant_row_count_array = json_decode($this->security->xss_clean($this->input->post('respondant_row_count_array')));

                    if (count($claimant_row_count_array) < 1 || count($respondant_row_count_array) < 1) {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Claimant and respondant are required.'
                        ]);
                        return;
                    }

                    // Calculate Fees Library ===================
                    $this->load->library('calculate_fees');
                    $fees = $this->calculate_fees->new_reference_caluclate_fees($this->input->post('claim_amount'), $this->input->post('arbitral_tribunal_strength'), $this->input->post('type_of_arbitration'));

                    if ($fees == false) {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Invalid amount provided'
                        ]);
                        return;
                    }

                    $this->db->trans_begin();

                    $data_arr = array(
                        'type_of_arbitration' => $this->input->post('type_of_arbitration'),
                        'case_type' => $this->input->post('case_type'),
                        'claim_amount' => $this->input->post('claim_amount'),
                        'arb_total_fees' => '',
                        'arb_your_share_fees' => '',
                        'adm_charges' => '',
                        'your_share' => '',
                        'arb_your_share_fees_payable' => '',
                        'your_payable_share' => '',
                        'int_arb_total_fees_dollar' => '',
                        'int_arb_total_fees_rupee' => '',
                        'int_arb_your_share_fees_dollar' => '',
                        'int_arb_your_share_fees_rupee' => '',
                        'int_total_adm_charges_dollar' => '',
                        'int_total_adm_charges_rupee' => '',
                        'int_each_party_adm_charges_dollar' => '',
                        'int_each_party_adm_charges_rupee' => '',
                        'int_your_share_dollar' => '',
                        'int_your_share_rupee' => '',
                        'int_your_payable_share_dollar' => '',
                        'int_your_payable_share_rupee' => '',
                        'document' => '',
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 0, // 0 for initiated
                        'created_by' => $this->session->userdata('user_code'),
                        'created_at' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_at' => currentDateTimeStamp(),
                    );

                    if ($this->input->post('type_of_arbitration') == 'DOMESTIC') {
                        $data_arr['arb_total_fees'] = $fees['total_arbitrator_fees'];
                        $data_arr['arb_your_share_fees'] = $fees['each_party_share_in_arb_fees'];
                        $data_arr['adm_charges'] = $fees['administrative_charges'];
                        $data_arr['your_share'] = $fees['each_party_payable'];
                        $data_arr['arb_your_share_fees_payable'] = $fees['arb_your_share_fees_payable'];
                        $data_arr['your_payable_share'] = $fees['your_payable_share'];
                    }

                    if ($this->input->post('type_of_arbitration') == 'INTERNATIONAL') {
                        $data_arr['int_arb_total_fees_dollar'] = $fees['total_arbitrator_fees_dollar'];
                        $data_arr['int_arb_total_fees_rupee'] = $fees['total_arbitrator_fees_rupee'];
                        $data_arr['int_arb_your_share_fees_dollar'] = $fees['each_party_share_in_arb_fees_dollar'];
                        $data_arr['int_arb_your_share_fees_rupee'] = $fees['each_party_share_in_arb_fees_rupee'];
                        $data_arr['int_total_adm_charges_dollar'] = $fees['administrative_charges_dollar'];
                        $data_arr['int_total_adm_charges_rupee'] = $fees['administrative_charges_rupee'];
                        $data_arr['int_each_party_adm_charges_dollar'] = $fees['each_party_administrative_charges_dollar'];
                        $data_arr['int_each_party_adm_charges_rupee'] = $fees['each_party_administrative_charges_rupee'];
                        $data_arr['int_your_share_rupee'] = $fees['each_party_payable_rupee'];
                        $data_arr['int_your_share_dollar'] = $fees['each_party_payable_dollar'];
                        $data_arr['int_your_payable_share_dollar'] = $fees['your_payable_share_dollar'];
                        $data_arr['int_your_payable_share_rupee'] = $fees['your_payable_share_rupee'];
                    }

                    // Upload the file ==============================================
                    if ($_FILES['upload_document']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $upload_document_result = $this->fileupload->uploadSingleFile($_FILES['upload_document'], [
                            'raw_file_name' => 'upload_document',
                            'file_name' => 'NEW_REFERENCE_' . time(),
                            'file_move_path' => EFILING_NEW_REFERENCE_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($upload_document_result['status'] == false) {
                            $this->db->trans_rollback();
                            return $upload_document_result;
                        } else {
                            $data_arr['document'] = $upload_document_result['file'];
                        }
                    } else {
                        if ($this->input->post('hidden_upload_document')) {
                            $data_arr['document'] = $this->input->post('hidden_upload_document');
                        } else {
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Please upload document before submitting form.'
                            ]);
                            return;
                        }
                    }

                    $result = $this->new_reference_model->update($data_arr, $this->input->post('hidden_id'));
                    if ($result) {

                        // get last generated id
                        $new_record = $this->db->from('efiling_new_reference_tbl')
                            ->where('id', $this->input->post('hidden_id'))
                            ->get()
                            ->row_array();


                        // Insert claimant & respondent
                        // Type = 1 for claimant
                        // Type = 2 for respondent
                        if (count($claimant_row_count_array) > 0) {
                            $cl_result = $this->new_reference_model->insert_new_ref_claimant($claimant_row_count_array, $_POST, $new_record['id'], 1);

                            if (!$cl_result['status']) {
                                echo  json_encode($cl_result);
                                return;
                            }
                        }

                        if (count($respondant_row_count_array) > 0) {
                            $res_result = $this->new_reference_model->insert_new_ref_respondant($respondant_row_count_array, $_POST, $new_record['id'], 2);

                            if (!$res_result['status']) {
                                echo  json_encode($res_result);
                                return;
                            }
                        }


                        $this->db->trans_commit();
                        echo  json_encode([
                            'status' => true,
                            'redirect_link' => base_url('efiling/new-reference/check-verify?id=' . urlencode(base64_encode($new_record['id'])))
                        ]);
                        return;
                    } else {
                        $this->db->trans_rollback();
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function proceed_payment()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken == '') {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }

        if (!checkToken($inputCsrfToken, 'file_new_case_verify_form')) {
            $data = array(
                'status' => false,
                'msg' => 'Invalid Security Token'
            );
            echo json_encode($data);
            die;
        }

        // Validation Code ===============
        $this->form_validation->set_rules('hidden_code', 'Code', 'required');
        if ($this->form_validation->run() != TRUE) {
            echo  json_encode([
                'status' => 'validation_error',
                'msg' => validation_errors()
            ]);
            die;
        }

        // Begin transaction
        $this->db->trans_begin();

        $new_ref_data = $this->new_reference_model->get_single_new_reference_data_using_code($this->input->post('hidden_code'));

        // Fetch new reference claimant and respondent data
        $data['new_reference_claimant'] = $this->new_reference_model->get_new_reference_claimants_using_code($new_ref_data['nr_code']);

        $data['new_reference_respondant'] = $this->new_reference_model->get_new_reference_respondants_using_code($new_ref_data['nr_code']);

        // Register the new reference as a fresh case directly for emergency cases
        if ($new_ref_data['case_type'] == 'EMERGENCY') {

            // Insert into cs_claimant_respondant_details_tbl table
            $case_no = generate_diac_reg_number();
            $md5_case_no = md5($case_no);

            $cr_data = [];
            $cr_address_data = [];
            $first_claimant_code = '';
            $first_respondant_code = '';
            $first_claimant_name = '';
            $first_respondant_name = '';

            foreach ($data['new_reference_claimant'] as $key => $claimant) {
                $c_code = generateCode();

                if ($key == 0) {
                    $first_claimant_code = $c_code;
                    $first_claimant_name = $claimant['name'];
                }

                array_push($cr_data, [
                    'code' => $c_code,
                    'type' => 'claimant',
                    'case_no' => $md5_case_no,
                    'name' => $claimant['name'],
                    'count_number' => $key + 1,
                    'counter_claimant' => null,
                    'contact' => $claimant['phone_number'],
                    'email' => $claimant['email_id'],
                    'status' => 1,
                    'created_by' => $this->session->userdata('user_code'),
                    'created_on' => currentDateTimeStamp(),
                    'updated_by' => $this->session->userdata('user_code'),
                    'updated_on' => currentDateTimeStamp(),
                ]);

                array_push($cr_address_data, [
                    'person_type' => 'CLAIMANT',
                    'type_code' => $c_code,
                    'address_one' => $claimant['address_one'],
                    'address_two' => $claimant['address_two'],
                    'state' => $claimant['state'],
                    'country' => $claimant['country'],
                    'pincode' => $claimant['pincode'],
                    'status' => 1,
                    'created_by' => $this->session->userdata('user_code'),
                    'created_at' => currentDateTimeStamp(),
                    'updated_by' => $this->session->userdata('user_code'),
                    'updated_at' => currentDateTimeStamp(),
                ]);
            }

            foreach ($data['new_reference_respondant'] as $key => $respondant) {
                $r_code = generateCode();

                if ($key == 0) {
                    $first_respondant_code = $r_code;
                    $first_respondant_name = $respondant['name'];
                }

                array_push($cr_data, [
                    'code' => $r_code,
                    'type' => 'respondant',
                    'case_no' => $md5_case_no,
                    'name' => $respondant['name'],
                    'count_number' => $key + 1,
                    'counter_claimant' => null,
                    'contact' => $respondant['phone_number'],
                    'email' => $respondant['email_id'],
                    'status' => 1,
                    'created_by' => $this->session->userdata('user_code'),
                    'created_on' => currentDateTimeStamp(),
                    'updated_by' => $this->session->userdata('user_code'),
                    'updated_on' => currentDateTimeStamp(),
                ]);

                array_push($cr_address_data, [
                    'person_type' => 'RESPONDENT',
                    'type_code' => $r_code,
                    'address_one' => $respondant['address_one'],
                    'address_two' => $respondant['address_two'],
                    'state' => $respondant['state'],
                    'country' => $respondant['country'],
                    'pincode' => $respondant['pincode'],
                    'status' => 1,
                    'created_by' => $this->session->userdata('user_code'),
                    'created_at' => currentDateTimeStamp(),
                    'updated_by' => $this->session->userdata('user_code'),
                    'updated_at' => currentDateTimeStamp(),
                ]);
            }

            // Generate case title
            $case_title = $first_claimant_name . ' vs ' . $first_respondant_name;


            $case_data = [
                'case_type' => 'NEW_REFERENCE',
                'reference_code' => $new_ref_data['nr_code'],
                'case_no' => $case_no,
                'slug' => $md5_case_no,
                'case_title' => $case_title,
                'case_title_claimant' => $first_claimant_code,
                'case_title_respondent' => $first_respondant_code,
                'reffered_by' => 'DIRECT',
                'recieved_on' => currentDate(),
                'registered_on' => currentDate(),
                'type_of_arbitration' => $new_ref_data['type_of_arbitration'],
                'di_type_of_arbitration' => $new_ref_data['case_type'],
                'case_status' => 'UNDER_PROCESS',
                'status' => 1, // This should be one after payment completion
                'created_by' => $this->session->userdata('user_code'),
                'created_on' => currentDateTimeStamp(),
                'updated_by' => $this->session->userdata('user_code'),
                'updated_on' => currentDateTimeStamp(),
            ];

            // print_r($cr_data);
            // die;
            $case_result = $this->db->insert('cs_case_details_tbl', $case_data);
            $cr_result = $this->db->insert_batch('cs_claimant_respondant_details_tbl', $cr_data);
            $address_result = $this->db->insert_batch('cs_addresses_tbl', $cr_address_data);

            if (!$case_result || !$cr_result || !$address_result) {
                $this->db->trans_rollback();
                echo  json_encode([
                    'status' => false,
                    'msg' => 'Error while saving emergency case data'
                ]);
                die;
            }

            // For now until payment gateway implemented,
            // Allot the emergency case automatically from here.

            // Allot the case to respective case managers and deputy counsels
            $al_result = automatic_case_allotment($case_no, 'DEPUTY_COUNSEL');
            $al_result2 = automatic_case_allotment($case_no, 'CASE_MANAGER');

            $dc_allotment_user_code = $al_result['user_code'];
            $cm_allotment_user_code = $al_result2['user_code'];

            $allotment_data = [];

            array_push($allotment_data, [
                'case_no' => $md5_case_no,
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
                'case_no' => $md5_case_no,
                'alloted_by' => $this->user_code,
                'alloted_to' => $cm_allotment_user_code,
                'user_role' => 'CASE_MANAGER',
                'status' => 1,
                'created_at' => currentDateTimeStamp(),
                'created_by' => $this->user_code,
                'updated_at' => currentDateTimeStamp(),
                'updated_by' => $this->user_code,
            ]);

            $allotment_res = $this->db->insert_batch('cs_case_allotment_tbl', $allotment_data);

            if (!$allotment_res) {
                $this->db->trans_rollback();
                return array(
                    'status' => false,
                    'msg' => 'Error while automatic case allotment. ' . SERVER_ERROR
                );
            }

            $application_status = 1;
        }

        $data_arr = array(
            'payment_status' => 1,
            'application_status' => (isset($application_status)) ? $application_status : 2, // 2 for pending
            'updated_by' => $this->session->userdata('user_code'),
            'updated_at' => currentDateTimeStamp(),
        );

        $result = $this->new_reference_model->update_using_code($data_arr, $this->input->post('hidden_code'));

        if (!$result) {
            $this->db->trans_rollback();
            echo  json_encode([
                'status' => false,
                'msg' => 'Server failed while saving data'
            ]);
            die;
        }

        $type = 'NEW_REFERENCE';
        $type_code = $new_ref_data['nr_code'];

        // Update the transaction table
        $txn_data = [
            't_code' => generateCode(),
            'txn_id' => generate_txn_id(),
            'type' => $type,
            'type_code' => $type_code,
            'amount' => $new_ref_data['your_payable_share'],
            'txn_date' => currentDateTimeStamp(),
            'payment_status' => 1,
            'record_status' => 1,
            'created_by' => $this->user_code,
            'created_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
        ];
        $txn_result = $this->common_model->insert_transaction($txn_data);
        if (!$txn_result) {
            $this->db->trans_rollback();
            echo  json_encode([
                'status' => false,
                'msg' => 'Server failed while saving transaction data'
            ]);
            return;
        }

        // Send email ===================
        foreach ($data['new_reference_claimant'] as $claimant) :
            if ($claimant['email_id']) :
                new_ref_registered_claimant($new_ref_data, $claimant);
            endif;
        endforeach;



        $this->db->trans_commit();
        echo  json_encode([
            'status' => true,
            'redirect_link' => base_url('efiling/new-reference/payment-success')
        ]);
        return;
    }

    public function payment_success()
    {
        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Payment Sucess';

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/new_reference_payment_success');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function view_new_reference()
    {
        if (!$this->input->get('id')) {
            return redirect(base_url('efiling/new-references'));
        }

        // Get the id
        $id = base64_decode(urldecode($this->input->get('id')));

        $data['menu_item'] = 'New References';
        $data['menu_group'] = 'New References';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'View New References';
            $data['new_reference'] = $this->new_reference_model->get_single_new_reference_data_using_id($id);
            $data['new_reference_claimant'] = $this->new_reference_model->get_new_reference_claimants_using_code($data['new_reference']['nr_code']);
            $data['new_reference_respondants'] = $this->new_reference_model->get_new_reference_respondants_using_code($data['new_reference']['nr_code']);

            $data['transaction'] = $this->common_model->fetch_transaction_using_type('NEW_REFERENCE', $data['new_reference']['nr_code']);

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/new_reference/view_new_reference');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
}
