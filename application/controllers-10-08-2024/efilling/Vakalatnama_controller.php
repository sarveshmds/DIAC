<?php defined('BASEPATH') or exit('No direct script access allowed');

class Vakalatnama_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'vakalatnama_model', 'case_model']);
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
            'ADVOCATE' => array('vakalatnama', 'add_vakalatnama', 'store_vakalatnama', 'getDatatableList', 'edit_vakalatnama', 'update_vakalatnama'),
            'ADVOCATE_ARBITRATOR' => array('vakalatnama', 'add_vakalatnama', 'store_vakalatnama', 'getDatatableList', 'edit_vakalatnama', 'update_vakalatnama'),
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

    public function vakalatnama()
    {

        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Vakalatnama';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/vakalatnama/vakalatnama');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function add_vakalatnama()
    {
        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Add Vakalatnama';
            $this->load->model('application_model');
            $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $data['behalf_of'] = $this->getter_model->get(['gen_code_group' => 'BEHALF_OF'], 'GET_GENCODE_DESC');

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/vakalatnama/add_vakalatnama', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function store_vakalatnama()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {
                // Validation Code
                // Validation Code ===============
                $this->form_validation->set_rules('already_case_reg', 'Already case registered', 'required');

                if ($this->input->post('already_case_reg') && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('case_no', 'Case No.', 'required');
                }

                if ($this->input->post('already_case_reg') && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('case_no_text', 'Case No.', 'required');
                    $this->form_validation->set_rules('case_title_text', 'Case Title', 'required');
                }

                $this->form_validation->set_rules('behalf_of', 'Behalf Of', 'required');

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT' && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('claimant_id', 'Claimant Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT' && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('claimant_text_name', 'Claimant Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT' && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('respondant_id', 'Respondent Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT' && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('respondant_text_name', 'Respondent Name', 'required');
                }

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $rowCount = $this->vakalatnama_model->checkCount();
                    $diaryNumber = generateDiaryNumber($rowCount, 'VA');

                    $data_arr = array(
                        'v_code' => generateCode(),
                        'diary_number' => $diaryNumber,
                        'user_code' => $this->session->userdata('user_code'),
                        'case_no' => '',
                        'case_no_text' => '',
                        'case_title_text' => '',
                        'behalf' => $this->input->post('behalf_of'),
                        'case_already_registered' => $this->input->post('already_case_reg'),
                        'claimant_respondent_id' => '',
                        'claimant_respondent_text_name' => '',
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 2, // 2 for pending
                        'created_by' => $this->session->userdata('user_code'),
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp(),
                    );

                    if ($this->input->post('already_case_reg') == 1) {
                        $data_arr['case_no'] = $this->input->post('case_no');  // Dropdown

                        if ($this->input->post('behalf_of') == 'CLAIMANT') {
                            $data_arr['claimant_respondent_id'] = $this->input->post('claimant_id'); // Dropdown
                        }
                        if ($this->input->post('behalf_of') == 'RESPONDENT') {
                            $data_arr['claimant_respondent_id'] = $this->input->post('respondant_id'); // Dropdown
                        }
                    }
                    if ($this->input->post('already_case_reg') == 2) {
                        $data_arr['case_no_text'] = $this->input->post('case_no_text'); // Text Inputs
                        $data_arr['case_title_text'] = $this->input->post('case_title_text'); // Text Inputs


                        if ($this->input->post('behalf_of') == 'CLAIMANT') {
                            $data_arr['claimant_respondent_text_name'] = $this->input->post('claimant_text_name'); // Text Inputs
                        }
                        if ($this->input->post('behalf_of') == 'RESPONDENT') {
                            $data_arr['claimant_respondent_text_name'] = $this->input->post('respondant_text_name'); // Text Inputs
                        }
                    }

                    // Upload the file ==============================================
                    if ($_FILES['document']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $document_result = $this->fileupload->uploadSingleFile($_FILES['document'], [
                            'raw_file_name' => 'document',
                            'file_name' => 'VAKALATNAMA_' . time(),
                            'file_move_path' => EFILING_VAKALATNAMA_UPLOADS_FOLDER,
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


                    $result = $this->vakalatnama_model->insert($data_arr);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Vakalatnama uploaded and sent successfully.'
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

        $this->db->select('eat.*, cdt.case_no as case_no_desc, cdt.case_title as case_title_desc, ccrdt.name as claimant_respondent_name, gcd.description as behalf_of_desc');
        $this->db->from('efiling_vakalatnama_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.claimant_respondent_id", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.behalf AND gcd.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->where('eat.user_code', $this->session->userdata('user_code'));
        $this->db->order_by('eat.id', 'DESC');

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

    public function edit_vakalatnama()
    {
        $data['menu_item'] = 'Vakalatnama';
        $data['menu_group'] = 'Vakalatnama';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Edit Vakalatnama';

            $data['vakalatnama'] = $this->vakalatnama_model->get_single_request_using_id($this->input->get('id'));

            $data['claimant_respondents'] = $this->case_model->get(['slug' => $data['vakalatnama']['case_no']], 'GET_ALL_CLAIMANT_RESPONDENT_USING_CASE_SLUG');

            $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $data['application_types'] = $this->getter_model->get(['gen_code_group' => 'APPLICATION_TYPE'], 'GET_GENCODE_DESC');

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/vakalatnama/add_vakalatnama', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function update_vakalatnama()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {
                // Validation Code
                // Validation Code ===============
                $this->form_validation->set_rules('already_case_reg', 'Already case registered', 'required');

                if ($this->input->post('already_case_reg') && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('case_no', 'Case No.', 'required');
                }

                if ($this->input->post('already_case_reg') && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('case_no_text', 'Case No.', 'required');
                    $this->form_validation->set_rules('case_title_text', 'Case Title', 'required');
                }

                $this->form_validation->set_rules('behalf_of', 'Behalf Of', 'required');

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT' && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('claimant_id', 'Claimant Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'CLAIMANT' && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('claimant_text_name', 'Claimant Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT' && $this->input->post('already_case_reg') == 1) {
                    $this->form_validation->set_rules('respondant_id', 'Respondent Name', 'required');
                }

                if ($this->input->post('behalf_of') && $this->input->post('behalf_of') == 'RESPONDENT' && $this->input->post('already_case_reg') == 2) {
                    $this->form_validation->set_rules('respondant_text_name', 'Respondent Name', 'required');
                }

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_id');

                    $data_arr = array(
                        'case_no' => '',
                        'case_no_text' => '',
                        'case_title_text' => '',
                        'behalf' => $this->input->post('behalf_of'),
                        'case_already_registered' => $this->input->post('already_case_reg'),
                        'claimant_respondent_id' => '',
                        'claimant_respondent_text_name' => '',
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 2, // 2 for pending
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp(),
                    );

                    if ($this->input->post('already_case_reg') == 1) {
                        $data_arr['case_no'] = $this->input->post('case_no');  // Dropdown

                        if ($this->input->post('behalf_of') == 'CLAIMANT') {
                            $data_arr['claimant_respondent_id'] = $this->input->post('claimant_id'); // Dropdown
                        }
                        if ($this->input->post('behalf_of') == 'RESPONDENT') {
                            $data_arr['claimant_respondent_id'] = $this->input->post('respondant_id'); // Dropdown
                        }
                    }
                    if ($this->input->post('already_case_reg') == 2) {
                        $data_arr['case_no_text'] = $this->input->post('case_no_text'); // Text Inputs
                        $data_arr['case_title_text'] = $this->input->post('case_title_text'); // Text Inputs


                        if ($this->input->post('behalf_of') == 'CLAIMANT') {
                            $data_arr['claimant_respondent_text_name'] = $this->input->post('claimant_text_name'); // Text Inputs
                        }
                        if ($this->input->post('behalf_of') == 'RESPONDENT') {
                            $data_arr['claimant_respondent_text_name'] = $this->input->post('respondant_text_name'); // Text Inputs
                        }
                    }

                    // Upload the file ==============================================
                    if ($_FILES['document']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $document_result = $this->fileupload->uploadSingleFile($_FILES['document'], [
                            'raw_file_name' => 'document',
                            'file_name' => 'VAKALATNAMA_' . time(),
                            'file_move_path' => EFILING_VAKALATNAMA_UPLOADS_FOLDER,
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

                    $result = $this->vakalatnama_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Vakalatnama uploaded and sent successfully.'
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
}
