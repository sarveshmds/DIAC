<?php defined('BASEPATH') or exit('No direct script access allowed');

class consent_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'application_model', 'consent_model']);
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'ADVOCATE_ARBITRATOR' => array('consents', 'add_consents', 'edit_consent', 'store_consent', 'getDatatableList', 'update_consent'),
            'ARBITRATOR' => array('consents', 'add_consents', 'edit_consent', 'store_consent', 'getDatatableList', 'update_consent', 'view_consent'),
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

    public function consents()
    {
        $data['menu_item'] = 'Consent';
        $data['menu_group'] = 'Consent';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Consent/Declaration';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/consent/concent');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
    public function add_consents()
    {
        $data['menu_item'] = 'Consent';
        $data['menu_group'] = 'Consent';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Add Consent/Declaration';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->model('application_model');
            $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $this->load->view('efiling/consent/add_consents', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function view_consent()
    {
        if (!$this->input->get('id')) {
            return redirect('page-not-found');
        }

        $data['consent'] = $this->consent_model->get_single_consent_using_id($this->input->get('id'));
        // print_r($data['consent']);
        // die;

        if (!$data['consent']) {
            return redirect('page-not-found');
        }

        $data['menu_item'] = 'Consent';
        $data['menu_group'] = 'Consent';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'View Consent';
            $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/consent/view_consents', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function edit_consent()
    {
        $data['menu_item'] = 'Consent';
        $data['menu_group'] = 'Consent';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Edit Consent';
            $data['consent'] = $this->consent_model->get_single_consent_using_id($this->input->get('id'));
            $this->load->model('application_model');
            $data['cases'] = $this->getter_model->get('', 'get_current_efiling_user_case_list');
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/consent/add_consents', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function store_consent()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm2')) {

                // Validation Code ===============
                $this->form_validation->set_rules('case_no', 'Case No.', 'required');
                $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_rules('mobile_number', 'Mobile No.', 'required');
                $this->form_validation->set_rules('email_id', 'Email ID', 'required');
                $this->form_validation->set_rules('secretary', 'P.S./Secretary', 'required');
                $this->form_validation->set_rules('prior_experience', 'Prior Experience (Including experience with arbitration)', 'required');
                $this->form_validation->set_rules('ongoing_arbitrations', 'Number of ongoing Arbitrations', 'required');
                $this->form_validation->set_rules('circumstances_disclosing_matter', 'Circumstances Disclosing Matter', 'required');
                $this->form_validation->set_rules('circumstances_affect_ability', 'Circumstances Affect Ability', 'required');

                $rowCount = $this->application_model->checkCount();
                $diaryNumber = generateDiaryNumber($rowCount, 'CO');

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {
                    $data_arr = array(
                        'c_code' => generateCode(),
                        'user_code' => $this->session->userdata('user_code'),
                        'diary_number' => $diaryNumber,
                        'case_no' => $this->input->post('case_no'),
                        'name' => $this->input->post('name'),
                        'mobile_number' => $this->input->post('mobile_number'),
                        'email_id' => $this->input->post('email_id'),
                        'secretary' => $this->input->post('secretary'),
                        'prior_experience' => $this->input->post('prior_experience'),
                        'ongoing_arbitrations' => $this->input->post('ongoing_arbitrations'),
                        'circumstances_disclosing_matter' => $this->input->post('circumstances_disclosing_matter'),
                        'circumstances_affect_ability' => $this->input->post('circumstances_affect_ability'),
                        'remarks' => $this->input->post('remarks'),
                        'application_status' => 2, // 2 for pending
                        'created_by' => $this->session->userdata('user_code'),
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp(),
                    );

                    // Upload the file ==============================================
                    if ($_FILES['signature']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $signature_result = $this->fileupload->uploadSingleFile($_FILES['signature'], [
                            'raw_file_name' => 'signature',
                            'file_name' => 'CONSENT_SIGNATURE_' . time(),
                            'file_move_path' => EFILING_CONSENT_UPLOADS_FOLDER,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($signature_result['status'] == false) {
                            $this->db->trans_rollback();
                            return $signature_result;
                        } else {
                            $data_arr['signature_file'] = $signature_result['file'];
                        }
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Please upload signature before submitting form.'
                        ]);
                        return;
                    }

                    $result = $this->consent_model->insert($data_arr);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Consent sent successfully.'
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

    public function update_consent()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm2')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_id', 'ID', 'required');
                $this->form_validation->set_rules('case_no', 'Case No.', 'required');

                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {
                    $id = $this->input->post('hidden_id');

                    $data_arr = array(
                        'case_no' => $this->input->post('case_no'),
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
                            'file_move_path' => EFILING_CONSENT_UPLOADS_FOLDER,
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

                    $result = $this->consent_model->update($data_arr, $id);
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
                    echo json_encode(array('status' => 'validation_error', 'message' => validation_errors()));
                    die;
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

        $this->db->select('eat.*, cdt.case_no as case_no_desc, cdt.case_title');
        $this->db->from('efiling_consent_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
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
}
