<?php defined('BASEPATH') or exit('No direct script access allowed');

class Document_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        # helpers
        $this->load->helper(array('form'));

        # libraries
        $this->load->library('encryption');

        # models
        $this->load->model(['superadmin_model', 'document_model', 'application_model', 'case_model']);
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'ADVOCATE' => array('document', 'add_document', 'edit_document', 'getDatatableList', 'store_document', 'update_document'),
            'PARTY' => array('document', 'add_document', 'edit_document', 'getDatatableList', 'store_document', 'update_document'),
            'ADVOCATE_ARBITRATOR' => array('document', 'add_document', 'edit_document', 'getDatatableList', 'store_document', 'update_document'),
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

    public function document()
    {
        $data['menu_item'] = 'Document/Pleadings';
        $data['menu_group'] = 'Document/Pleadings';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Document / Pleadings';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/document/document');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function add_document()
    {
        $data['menu_item'] = 'Document/Pleadings';
        $data['menu_group'] = 'Document/Pleadings';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Add Document / Pleadings';
            $this->load->model('application_model');
            // $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $data['cases'] = [];
            $data['document_types'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_DOCUMENT'], 'GET_GENCODE_DESC');
            $data['behalf_of'] = $this->getter_model->get(['gen_code_group' => 'BEHALF_OF'], 'GET_GENCODE_DESC');

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/document/add_document', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function store_document()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('case_no', 'Case Number', 'required');
                $this->form_validation->set_rules('behalf_of', 'Behalf Of', 'required');
                $this->form_validation->set_rules('type_of_document', 'Type Of Document', 'required');

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT') {
                    $this->form_validation->set_rules('claimant', 'Claimant', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT') {
                    $this->form_validation->set_rules('respondant', 'Respondant', 'required');
                }

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    // Check the row count from table
                    $rowCount = $this->document_model->checkCount();
                    $diaryNumber = generateDiaryNumber($rowCount, 'DO');

                    // Get the name of on behalf of
                    $name_on_behalf_of = '';
                    if ($this->input->post('behalf_of') == 'CLAIMANT') {
                        $name_on_behalf_of = $this->input->post('claimant');
                    }
                    if ($this->input->post('behalf_of') == 'RESPONDENT') {
                        $name_on_behalf_of = $this->input->post('respondant');
                    }

                    $data_arr = array(
                        'd_code' => generateCode(),
                        'user_code' => $this->session->userdata('user_code'),
                        'diary_number' => $diaryNumber,
                        'case_no' => $this->input->post('case_no'),
                        'type_of_document' => $this->input->post('type_of_document'),
                        'behalf_of' => $this->input->post('behalf_of'),
                        'name_on_behalf_of' => $name_on_behalf_of,
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 2, // 2 for pending
                        'created_by' => $this->session->userdata('user_code'),
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp(),
                    );

                    // Upload the file ==============================================
                    if ($_FILES['document']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $document_result = $this->fileupload->uploadSingleFile($_FILES['document'], [
                            'raw_file_name' => 'document',
                            'file_name' => 'DOCUMENT_' . time(),
                            'file_move_path' => EFILING_DOCUMENTS_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($document_result['status'] == false) {
                            $this->db->trans_rollback();
                            return $document_result;
                        } else {
                            $data_arr['upload'] = $document_result['file'];
                        }
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Please upload file before submitting form.'
                        ]);
                        return;
                    }

                    $result = $this->document_model->insert($data_arr);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                    } else {
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

    public function edit_document()
    {
        $data['menu_item'] = 'Document/Pleadings';
        $data['menu_group'] = 'Document/Pleadings';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Edit Document';
            $data['document'] = $this->document_model->get_single_request_using_id($this->input->get('id'));

            if ($data['document']) {
                // Get the claimant and respondent of case
                $data['claimant_respondents'] = $this->case_model->get(['slug' => $data['document']['case_no']], 'GET_ALL_CLAIMANT_RESPONDENT_USING_CASE_SLUG');

                $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');

                $data['document_types'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_DOCUMENT'], 'GET_GENCODE_DESC');
                $data['behalf_of'] = $this->getter_model->get(['gen_code_group' => 'BEHALF_OF'], 'GET_GENCODE_DESC');


                $this->load->view('templates/efiling/efiling-header', $data);
                $this->load->view('efiling/document/add_document', $data);
                $this->load->view('templates/efiling/efiling-footer');
            } else {
                return redirect('page-not-found');
            }
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function update_document()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_id', 'ID', 'required');
                $this->form_validation->set_rules('case_no', 'Case Number', 'required');
                $this->form_validation->set_rules('behalf_of', 'Behalf Of', 'required');
                $this->form_validation->set_rules('type_of_document', 'Type Of Document', 'required');

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT') {
                    $this->form_validation->set_rules('claimant', 'Claimant', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT') {
                    $this->form_validation->set_rules('respondant', 'Respondant', 'required');
                }

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_id');

                    $name_on_behalf_of = '';
                    if ($this->input->post('behalf_of') == 'CLAIMANT') {
                        $name_on_behalf_of = $this->input->post('claimant');
                    }
                    if ($this->input->post('behalf_of') == 'RESPONDENT') {
                        $name_on_behalf_of = $this->input->post('respondant');
                    }


                    $data_arr = array(
                        'case_no' => $this->input->post('case_no'),
                        'type_of_document' => $this->input->post('type_of_document'),
                        'behalf_of' => $this->input->post('behalf_of'),
                        'name_on_behalf_of' => $name_on_behalf_of,
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 2, // 2 for pending
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp(),
                    );

                    // Upload the file ==============================================
                    if ($_FILES['document']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $document_result = $this->fileupload->uploadSingleFile($_FILES['document'], [
                            'raw_file_name' => 'document',
                            'file_name' => 'DOCUMENT_' . time(),
                            'file_move_path' => EFILING_DOCUMENTS_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($document_result['status'] == false) {
                            $this->db->trans_rollback();
                            return $document_result;
                        } else {
                            $data_arr['upload'] = $document_result['file'];
                        }
                    }

                    $result = $this->document_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                    } else {
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

    public function getDatatableList()
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

        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, gcd.description as type_of_document_desc, gcd2.description as behalf_of_desc, cdt.case_title');
        $this->db->from('efiling_document_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.name_on_behalf_of", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.type_of_document AND gcd.gen_code_group = 'TYPE_OF_DOCUMENT'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf_of AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->order_by('eat.id', 'DESC');
        $this->db->where('eat.user_code', $this->session->userdata('user_code'));

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
}
