<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ims_efiling_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        header("Access-Control-Allow-Origin: domain.com");

        # models
        $this->load->model(array('common_model', 'case_model', 'document_model', 'vakalatnama_model',  'application_model', 'request_model', 'category_model', 'fees_model', 'notification_model', 'miscellaneous_model', 'consent_model', 'new_reference_model', 'ims_efiling_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
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
            'CASE_FILER' => array(
                'document_pleadings', 'application',  'document_pleadings_datatable_list', 'application_datatable_list', 'new_references', 'requests', 'requests_datatable_list', 'document_view', 'application_view', 'vakalatnama', 'vakalatnama_datatable_list', 'vakalatnama_view', 'change_document_status', 'change_application_status', 'change_vakalatnama_status', 'change_request_status', 'consent', 'consent_datatable_list', 'new_reference_datatable_list', 'change_new_reference_status', 'new_reference_view', 'other_notings'
            ),
            'DEPUTY_COUNSEL' => array(
                'vakalatnama', 'vakalatnama_datatable_list', 'vakalatnama_view', 'change_vakalatnama_status', 'consent', 'consent_datatable_list', 'change_consent_status', 'view_consent'
            ),
            'COORDINATOR' => array(
                'document_pleadings', 'application',  'document_pleadings_datatable_list', 'application_datatable_list', 'new_references', 'requests', 'requests_datatable_list', 'document_view', 'application_view', 'vakalatnama', 'vakalatnama_datatable_list', 'vakalatnama_view', 'change_document_status', 'change_application_status', 'change_vakalatnama_status', 'change_request_status', 'consent', 'consent_datatable_list', 'new_reference_datatable_list', 'change_new_reference_status', 'new_reference_view', 'other_notings'
            ),
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

    public function document_pleadings()
    {
        $sidebar['menu_item'] = 'Document / Pleadings';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Document/Pleadings';
            $this->load->view('diac-admin/ims_efiling/document_pleadings', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function document_view()
    {
        $sidebar['menu_item'] = 'Document / Pleadings';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);


        if ($page_status != 0) {
            $data['page_title'] = 'View Document / Pleadings';
            $data['document'] = $this->document_model->get_single_request_using_id($this->input->get('id'));
            $data['claimant_respondents'] = $this->case_model->get(['slug' => $data['document']['case_no']], 'GET_ALL_CLAIMANT_RESPONDENT_USING_CASE_SLUG');

            $this->load->view('diac-admin/ims_efiling/edit_document_pleadings', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function change_document_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_id', 'ID', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_beneficiary_id');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'department_remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->document_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }

    public function document_pleadings_datatable_list()
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

        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, cdt.case_title as case_title_desc, gcd.description as type_of_document_desc, gcd2.description as behalf_of_desc');
        $this->db->from('efiling_document_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.name_on_behalf_of", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.type_of_document AND gcd.gen_code_group = 'TYPE_OF_DOCUMENT'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf_of AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
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

    public function application()
    {
        $sidebar['menu_item'] = 'Applications';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Application';
            $this->load->view('diac-admin/ims_efiling/application', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function application_view()
    {
        $sidebar['menu_item'] = 'Applications';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);


        if ($page_status != 0) {
            $data['page_title'] = 'View Application';
            $data['application'] = $this->application_model->get_single_request_using_id($this->input->get('id'));
            $data['claimant_respondents'] = $this->case_model->get(['slug' => $data['application']['case_no']], 'GET_ALL_CLAIMANT_RESPONDENT_USING_CASE_SLUG');

            $this->load->view('diac-admin/ims_efiling/view_application', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }


    public function application_datatable_list()
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

        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, cdt.case_title as case_title_desc, gcd.description as filing_type_desc, gcd2.description as behalf_of_desc, gcd3.description as app_type_desc');
        $this->db->from('efiling_application_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.claimant_respondent_id", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.filing_type AND gcd.gen_code_group = 'FILING_TYPE'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = eat.app_type AND gcd3.gen_code_group = 'APPLICATION_TYPE'", 'left');
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

    public function change_application_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_id', 'ID', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_beneficiary_id');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->application_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }

    public function vakalatnama()
    {
        $sidebar['menu_item'] = 'Vakalatnama';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Vakalatnama';
            $this->load->view('diac-admin/ims_efiling/vakalatnama', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function vakalatnama_view()
    {
        $sidebar['menu_item'] = 'Document / Pleadings';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);


        if ($page_status != 0) {
            $data['page_title'] = 'Vakalatnama View';
            $data['vakalatnama'] = $this->vakalatnama_model->get_single_request_using_id($this->input->get('id'));

            $this->load->view('diac-admin/ims_efiling/view_valakatnama', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function vakalatnama_datatable_list()
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
    public function change_vakalatnama_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_id', 'ID', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_beneficiary_id');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->vakalatnama_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }

    public function consent()
    {
        $sidebar['menu_item'] = 'Consent';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Consents';
            $this->load->view('diac-admin/ims_efiling/consents/consent', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function consent_datatable_list()
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

    public function view_consent()
    {
        if (!$this->input->get('code')) {
            return redirect('page-not-found');
        }

        $data['consent'] = $this->consent_model->get_single_consent_using_code($this->input->get('code'));
        // print_r($data['consent']);
        // die;

        if (!$data['consent']) {
            return redirect('page-not-found');
        }

        $sidebar['menu_item'] = 'Consent';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'View Consents';
            $this->load->view('diac-admin/ims_efiling/consents/view_consent', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function change_consent_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_id', 'ID', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_beneficiary_id');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->consent_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }

    public function new_references()
    {
        $sidebar['menu_item'] = 'New References';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'New References';
            $this->load->view('diac-admin/ims_efiling/new_references', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function new_reference_datatable_list()
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


        $this->db->select('ert.*, gcd.description as type_of_arbitration_desc,  gcd2.description as case_type_desc, cdt.case_no');
        $this->db->from('efiling_new_reference_tbl ert');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.reference_code = ert.nr_code AND cdt.case_type = 'NEW_REFERENCE'", 'left');
        $this->db->order_by('ert.id', 'DESC');

        $this->db->where_in('ert.application_status', [1, 2, 3]);

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

    public function new_reference_view()
    {
        $sidebar['menu_item'] = 'New References';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $nr_code = base64_decode(urldecode($this->input->get('nr_code')));

        if ($page_status != 0) {
            $data['page_title'] = 'View New Reference';
            $data['new_reference'] = $this->new_reference_model->get_single_new_reference_data_using_code($nr_code);

            $data['new_reference_claimant'] = $this->new_reference_model->get_new_reference_claimants_using_code($data['new_reference']['nr_code']);
            $data['new_reference_respondants'] = $this->new_reference_model->get_new_reference_respondants_using_code($data['new_reference']['nr_code']);

            $data['transaction'] = $this->common_model->fetch_transaction_using_type('NEW_REFERENCE', $data['new_reference']['nr_code']);

            $this->load->view('diac-admin/ims_efiling/view_new_reference', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function change_new_reference_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_code', 'Code', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $code = $this->input->post('hidden_beneficiary_code');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->new_reference_model->update_using_code($data_arr, $code);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Status changed successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }
    public function requests()
    {
        $sidebar['menu_item'] = 'Requests';
        $sidebar['menu_group'] = 'E-filing';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Requests';
            $this->load->view('diac-admin/ims_efiling/requests', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function requests_datatable_list()
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

        $this->db->from('efiling_requests_tbl eat');
        $this->db->select('eat.*');
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
    public function change_request_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {

                // Validation Code ===============
                $this->form_validation->set_rules('hidden_beneficiary_id', 'ID', 'required');
                $this->form_validation->set_rules('application_status', 'Application Status', 'required');
                if ($this->input->post('application_status') == '3') {
                    $this->form_validation->set_rules('remarks', 'Remarks', 'required');
                }
                // Check if the validation passed or not
                if ($this->form_validation->run() == TRUE) {

                    $id = $this->input->post('hidden_beneficiary_id');

                    $data_arr = array(
                        'application_status' => $this->input->post('application_status'),
                        'remarks' => $this->input->post('remarks'),
                        'updated_by' => $this->session->userdata('role'),
                    );
                    $result = $this->request_model->update($data_arr, $id);
                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Data saved successfully.'
                        ]);
                        die;
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                        die;
                    }
                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                    die;
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
                die;
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
            die;
        }
    }

    public function other_notings()
    {
        if (!$this->input->get('noting_group') || !$this->input->get('type_code') || !$this->input->get('type_name')) {
            echo 'error';
            die;
        }

        $noting_group = customURIDecode($this->input->get('noting_group'));
        $type_code = customURIDecode($this->input->get('type_code'));
        $type_name = customURIDecode($this->input->get('type_name'));

        // Check the details in other notings table
        $count_result = $this->ims_efiling_model->check_noting_availability($noting_group, $type_code, $type_name);

        if ($count_result == 0) {
            // Insert the new noting details
            $insert_result = $this->ims_efiling_model->insert_other_noting($noting_group, $type_code, $type_name);
        }

        // Fetch details
        $fetch_result = $this->ims_efiling_model->fetch_other_noting_using_types($noting_group, $type_code, $type_name);

        if ($fetch_result) {
            $data['sub_title'] = '';
            $data['diary_number'] = '';

            if ($fetch_result['noting_group'] == 'EFILING' && $fetch_result['type_name'] == 'NEW_REFERENCE') {
                $data['new_reference'] = $this->new_reference_model->get_single_new_reference_data_using_code($fetch_result['type_code']);

                $data['new_reference_claimant'] =
                    $this->new_reference_model->get_new_reference_claimants_using_code($data['new_reference']['nr_code']);
                $data['new_reference_respondants'] =
                    $this->new_reference_model->get_new_reference_respondants_using_code($data['new_reference']['nr_code']);

                $data['transaction'] = $this->common_model->fetch_transaction_using_type('NEW_REFERENCE', $data['new_reference']['nr_code']);

                $data['sub_title'] = 'New Reference';
                $data['diary_number'] = $data['new_reference']['diary_number'];
            }

            if ($fetch_result['noting_group'] == 'DIRECT_ORDERS' && $fetch_result['type_name'] == 'REFERRALS_REQUESTS') {
                $data['misc_data'] = $this->miscellaneous_model->get($fetch_result['type_code'], 'GET_MISCELLANEOUS_DATA_USING_CODE');

                $data['sub_title'] = 'Referrals/Requests';
                $data['diary_number'] = $data['misc_data']['diary_number'];
                // print_r($data['new_reference']);
                // die;
            }

            if ($fetch_result['noting_group'] == 'EFILING' && $fetch_result['type_name'] == 'DOCUMENT_PLEADINGS') {

                $data['document_pleadings'] = $this->document_model->get_single_doc_data_using_code($fetch_result['type_code']);

                $data['sub_title'] = 'Document/Pleadings';
                $data['diary_number'] = $data['document_pleadings']['diary_number'];
            }

            if ($fetch_result['noting_group'] == 'EFILING' && $fetch_result['type_name'] == 'APPLICATION') {

                $data['application'] = $this->application_model->get_single_application_using_code($fetch_result['type_code']);

                $data['sub_title'] = 'Application';
                $data['diary_number'] = $data['application']['diary_number'];
            }

            $data['on_code'] = $fetch_result['code'];

            // Update the message to read ============
            $ms_result = $this->common_model->other_noting_message_set_to_read($data['on_code']);

            $sidebar['menu_item'] = 'Dashboard';
            $sidebar['menu_group'] = 'Dashboard';
            $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

            $this->load->view('templates/side_menu', $sidebar);
            $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

            if ($page_status != 0) {
                $data['page_title'] = $data['sub_title'] . ' - Noting ' . (($data['diary_number']) ? '(' . $data['diary_number'] . ')' : '');

                if ($this->session->userdata('role') == 'COORDINATOR') {
                    $data['users'] = $this->common_model->get_rolewise_users_list('CASE_FILER');
                }
                if ($this->session->userdata('role') == 'CASE_FILER') {
                    $data['users'] = $this->common_model->get_rolewise_users_list('COORDINATOR');
                }

                $this->load->view('diac-admin/other-noting/messages', $data);
            } else {
                $this->load->view('templates/page_maintenance');
            }
            $this->load->view('templates/footer');
        } else {
            return redirect('page-not-found');
        }
    }
}
