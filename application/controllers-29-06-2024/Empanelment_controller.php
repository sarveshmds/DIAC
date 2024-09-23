<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empanelment_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');

        $this->load->model(['superadmin_model', 'common_model', 'getter_model', 'empanellment_model', 'category_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('index', 'get_datatable_data', 'view', 'get_initiated_list_datatable_data', 'get_approved_list_datatable_data', 'get_rejected_list_datatable_data', 'change_arbitrator_empanelment_status', 'generate_arb_emp_single_pdf'),
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
        $sidebar['menu_item'] = 'Arbitrator Empanelment';
        $sidebar['menu_group'] = 'Arbitrator Empanelment';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Arbitrator Empanelment';

            $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
            $this->load->view('diac-admin/arbitrator_empanellment/arbitrator_empanelment', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to get all the submitted datatable data
     */
    public function get_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("emt_reg.id, emt_reg.diary_number,emt_reg.email_id,emt_reg.phone_number, emt_reg.is_submitted, emt_reg.step, emt_reg.approved, DATE_FORMAT(emt_reg.created_at, '%d-%m-%Y'), emt_pers.first_name, emt_pers.last_name, CONCAT(emt_pers.first_name,' ', emt_pers.last_name) as full_name, pct.category_name as category, emt_pers.resident_add_1,emt_pers.resident_add_2,ct.name as resident_country_name,st.name as resident_state_name,emt_pers.resident_city,emt_pers.resident_pincode, emt_reg.created_at");
        $this->db->from('arb_emp_registration_tbl as emt_reg');
        $this->db->join('empanelment_personal_info as emt_pers', 'emt_pers.arb_id = emt_reg.id', 'left');
        $this->db->join('panel_category_tbl as pct', 'pct.category_code = emt_pers.empanellment_category', 'left');
        $this->db->join('countries as ct', 'ct.iso2 = emt_pers.resident_country', 'left');
        $this->db->join('states as st', 'st.id = emt_pers.resident_state', 'left');
        // $this->db->join('countries as ct_2', 'ct_2.iso2 = emt_pers.corr_country', 'left');
        // $this->db->join('states as st_2', 'st_2.id = emt_pers.corr_state', 'left');
        $this->db->where('emt_reg.approved', 0);
        $this->db->where('emt_reg.is_submitted', 1);
        $this->db->where('emt_reg.record_status', 1);

        if ($this->input->post('arb_emp_name')) {
            $this->db->like('emt_pers.first_name', trim($this->input->post('arb_emp_name')));
            $this->db->or_like('emt_pers.middle_name', trim($this->input->post('arb_emp_name')));
            $this->db->or_like('emt_pers.last_name', trim($this->input->post('arb_emp_name')));
        }

        if ($this->input->post('arb_emp_category')) {
            $this->db->where('emt_pers.empanellment_category', $this->input->post('arb_emp_category'));
        }

        if ($this->input->post('arb_emp_phone')) {
            $this->db->where('emt_reg.phone_number', $this->input->post('arb_emp_phone'));
        }

        if ($this->input->post('arb_emp_email')) {
            $this->db->where('emt_reg.email_id', $this->input->post('arb_emp_email'));
        }

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('arb_emp_registration_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    /**
     * Function to get all the inititated datatable data
     */
    public function get_initiated_list_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("emt_reg.id, emt_reg.diary_number,emt_reg.email_id,emt_reg.phone_number, emt_reg.is_submitted, emt_reg.step, emt_reg.approved, DATE_FORMAT(emt_reg.created_at, '%d-%m-%Y'), emt_pers.first_name, emt_pers.last_name, CONCAT(emt_pers.first_name,' ', emt_pers.last_name) as full_name, pct.category_name as category, emt_pers.resident_add_1,emt_pers.resident_add_2,ct.name as resident_country_name,st.name as resident_state_name,emt_pers.resident_city,emt_pers.resident_pincode");
        $this->db->from('arb_emp_registration_tbl as emt_reg');
        $this->db->join('empanelment_personal_info as emt_pers', 'emt_pers.arb_id = emt_reg.id', 'left');
        $this->db->join('panel_category_tbl as pct', 'pct.category_code = emt_pers.empanellment_category', 'left');
        $this->db->join('countries as ct', 'ct.iso2 = emt_pers.resident_country', 'left');
        $this->db->join('states as st', 'st.id = emt_pers.resident_state', 'left');
        // $this->db->join('countries as ct_2', 'ct_2.iso2 = emt_pers.corr_country', 'left');
        // $this->db->join('states as st_2', 'st_2.id = emt_pers.corr_state', 'left');
        $this->db->where('emt_reg.is_submitted', 0);
        $this->db->where('emt_reg.record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('arb_emp_registration_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    /* ----------  Function to get approved application datatable data  ----------- */

    public function get_approved_list_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("emt_reg.id, emt_reg.diary_number,emt_reg.email_id,emt_reg.phone_number, emt_reg.is_submitted, emt_reg.step, emt_reg.approved, DATE_FORMAT(emt_reg.created_at, '%d-%m-%Y'), emt_pers.first_name, emt_pers.last_name, CONCAT(emt_pers.first_name,' ', emt_pers.last_name) as full_name, pct.category_name as category, emt_pers.resident_add_1,emt_pers.resident_add_2,ct.name as resident_country_name,st.name as resident_state_name,emt_pers.resident_city,emt_pers.resident_pincode");
        $this->db->from('arb_emp_registration_tbl as emt_reg');
        $this->db->join('empanelment_personal_info as emt_pers', 'emt_pers.arb_id = emt_reg.id', 'left');
        $this->db->join('panel_category_tbl as pct', 'pct.category_code = emt_pers.empanellment_category', 'left');
        $this->db->join('countries as ct', 'ct.iso2 = emt_pers.resident_country', 'left');
        $this->db->join('states as st', 'st.id = emt_pers.resident_state', 'left');
        // $this->db->join('countries as ct_2', 'ct_2.iso2 = emt_pers.corr_country', 'left');
        // $this->db->join('states as st_2', 'st_2.id = emt_pers.corr_state', 'left');
        $this->db->where('emt_reg.approved', 1);
        $this->db->where('emt_reg.is_submitted', 1);
        $this->db->where('emt_reg.record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('arb_emp_registration_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }


    /* ----------  Function for get the data of Rejected applications datatable data  ----------- */

    public function get_rejected_list_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("emt_reg.id, emt_reg.diary_number,emt_reg.email_id,emt_reg.phone_number, emt_reg.is_submitted, emt_reg.step, emt_reg.approved, DATE_FORMAT(emt_reg.created_at, '%d-%m-%Y'), emt_pers.first_name, emt_pers.last_name, CONCAT(emt_pers.first_name,' ', emt_pers.last_name) as full_name, pct.category_name as category, emt_pers.resident_add_1,emt_pers.resident_add_2,ct.name as resident_country_name,st.name as resident_state_name,emt_pers.resident_city,emt_pers.resident_pincode");
        $this->db->from('arb_emp_registration_tbl as emt_reg');
        $this->db->join('empanelment_personal_info as emt_pers', 'emt_pers.arb_id = emt_reg.id', 'left');
        $this->db->join('panel_category_tbl as pct', 'pct.category_code = emt_pers.empanellment_category', 'left');
        $this->db->join('countries as ct', 'ct.iso2 = emt_pers.resident_country', 'left');
        $this->db->join('states as st', 'st.id = emt_pers.resident_state', 'left');
        // $this->db->join('countries as ct_2', 'ct_2.iso2 = emt_pers.corr_country', 'left');
        // $this->db->join('states as st_2', 'st_2.id = emt_pers.corr_state', 'left');
        $this->db->where('emt_reg.approved', 2);
        $this->db->where('emt_reg.is_submitted', 1);
        $this->db->where('emt_reg.record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('arb_emp_registration_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }


    public function view()
    {
        $data['arb_id'] = $this->input->get('id');

        // CHECK IF THE ID IS AVAILABLE OR NOT
        if (!$data['arb_id']) {
            return redirect('arbitrator-empanelment');
        }

        // FETCH THE DATA USING ID
        $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
        if (!$data['arb_reg_data']) {
            return redirect('arbitrator-empanelment');
        }

        $sidebar['menu_item'] = 'Arbitrator Empanelment';
        $sidebar['menu_group'] = 'Arbitrator Empanelment';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['arb_info'] = $this->empanellment_model->get_arb_info($data['arb_id']);
            $data['personal_information'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);
            $data['professional_information'] = $this->empanellment_model->get_arb_prof_info($data['arb_id']);
            $data['document_files'] = $this->empanellment_model->get_arb_doc_info($data['arb_id']);
            // Other empanelment details
            $data['arb_empanel_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

            $data['page_title'] = 'View Arbitrator Empanellment Details';

            $this->load->view('diac-admin/arbitrator_empanellment/view_arbitrator_empanelment', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function change_arbitrator_empanelment_status()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'emp_appr_form')) {
                $this->form_validation->set_rules('hidden_id', 'ID', 'required');
                if ($this->form_validation->run() == TRUE) {
                    $data = [
                        'approved' => $this->input->post('application_status'),
                        'approved_on' => now(),
                        'approved_by' => $this->session->userdata('user_code'),
                        'updated_at' => now(),
                        'updated_by' => $this->session->userdata('user_code'),
                    ];

                    $result = $this->empanellment_model->update_arb_emp_registration($data, $this->input->post('hidden_id'));

                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => ($this->input->post('application_status') == 1) ? 'Application approved successfully' : 'Application rejected successfully'
                        ]);
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data, please try again or contact support team.'
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
                'status' => 'FALSE',
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function generate_arb_emp_single_pdf()
    {

        try {
            ini_set('max_execution_time', 1200);

            $data['arb_id'] = $this->input->get('id');

            // CHECK IF THE ID IS AVAILABLE OR NOT
            if (!$data['arb_id']) {
                return redirect('arbitrator-empanelment');
            }

            // FETCH THE DATA USING ID
            $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
            if (!$data['arb_reg_data']) {
                return redirect('arbitrator-empanelment');
            }

            $data['arb_info'] = $this->empanellment_model->get_arb_info($data['arb_id']);
            $data['personal_information'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);
            $data['professional_information'] = $this->empanellment_model->get_arb_prof_info($data['arb_id']);
            $data['document_files'] = $this->empanellment_model->get_arb_doc_info($data['arb_id']);
            // Other empanelment details
            $data['arb_empanel_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

            $data['page_title'] = 'Arbitrator Empanellment Application';

            // $farmer_id_list = json_decode($this->input->post('farmers_id_list'));
            $print_data = array();

            $pdf_data = $this->load->view('system/templates/header', $data, true);
            $pdf_data .= $this->load->view('system/arbitrator_empanellment_application', $data, true);
            $pdf_data .= $this->load->view('system/templates/footer', $data, true);

            $this->load->library('dom_pdf');
            $this->dom_pdf->convert_into_pdf($pdf_data, 'Arbitrator Empanellment Application', 'Portrait');
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'msg' => 'Server failed while generating PDF.'
            ]);
        }
    }
}

/* End of file Empanelment_controller.php */
/* Location: ./application/controllers/Empanelment_controller.php */