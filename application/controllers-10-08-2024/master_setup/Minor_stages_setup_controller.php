<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Minor_stages_setup_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'common_model', 'category_model', 'getter_model', 'master_setup/minor_stages_setup_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('index', 'add', 'get_datatable_data', 'update', 'fetch_using_major_stage'),
            'CASE_MANAGER' => array('fetch_using_major_stage'),
            'DEPUTY_COUNSEL' => array('fetch_using_major_stage'),
            'COORDINATOR' => array('fetch_using_major_stage'),
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
        $sidebar['menu_item'] = 'Minor Stages Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
        $data['approved_arb'] = $this->getter_model->get('', 'GET_APPROVED_ARB');
        $data['unapproved_arb'] = $this->getter_model->get('', 'GET_UNAPPROVED_ARB');
        $data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
        // print_r($data['countries']);
        // die;

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Minor Stages Setup';

            $this->load->view('master_setup/minor_stages_setup', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add()
    {

        $this->form_validation->set_rules('major_stage_code', 'Major Stage', 'required|xss_clean');
        $this->form_validation->set_rules('sl_no', 'Serial No.', 'required|xss_clean');
        $this->form_validation->set_rules('code_of_stage', 'Code of Stage', 'required|xss_clean');
        $this->form_validation->set_rules('name_of_stage', 'Name of Stage', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {

                // Check if the code is already added or not
                $check = $this->minor_stages_setup_model->checkForUniqueCode($this->input->post('code_of_stage'));
                if (count($check) > 0) {
                    failure_response("Code is already exist.", false, true);
                }

                $data = array(
                    'major_stage_code' => $this->input->post('major_stage_code'),
                    'sl_no' => $this->input->post('sl_no'),
                    'code' => $this->input->post('code_of_stage'),
                    'name' => $this->input->post('name_of_stage'),
                    'record_status' => 1,
                    'created_by' => $this->user_code,
                    'created_at' => currentDateTimeStamp(),
                    'created_ip_address' => $this->input->ip_address()
                );

                // Insert panel category
                $result = $this->db->insert('master_minor_stages_table', $data);

                if ($result) {
                    success_response("Record saved successfully", false, true);
                } else {
                    failure_response("Error while saving data", false, true);
                }
            } catch (Exception $e) {
                failure_response("Internal server error, Error while saving data", false, false);
            }
        } else {
            $dbstatus = 'validationerror';
            $dbmessage = validation_errors();
        }
        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
    }

    public function update()
    {
        $this->form_validation->set_rules('mcs_hidden_id', 'Id', 'required|xss_clean');
        $this->form_validation->set_rules('sl_no', 'Serial No.', 'required|xss_clean');
        // $this->form_validation->set_rules('code_of_stage', 'Code of Stage', 'required|xss_clean');
        $this->form_validation->set_rules('name_of_stage', 'Name of Stage', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {

                $data = array(
                    'major_stage_code' => $this->input->post('major_stage_code'),
                    'sl_no' => $this->input->post('sl_no'),
                    'name' => $this->input->post('name_of_stage'),
                    'record_status' => 1,
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp(),
                    'updated_ip_address' => $this->input->ip_address()
                );

                // Insert panel category
                $result = $this->db->where('id', $this->input->post('mcs_hidden_id'))->update('master_minor_stages_table', $data);

                if ($result) {
                    success_response("Record updated successfully", false, true);
                } else {
                    failure_response("Error while saving data", false, true);
                }
            } catch (Exception $e) {
                failure_response("Internal server error, Error while saving data", false, false);
            }
        } else {
            $dbstatus = 'validationerror';
            $dbmessage = validation_errors();
        }
        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
    }

    // public function delete()
    // {
    //     $this->form_validation->set_rules('id', 'ID', 'required|xss_clean');

    //     if ($this->form_validation->run()) {
    //         $this->db->trans_begin();
    //         try {
    //             $id = $this->input->post('id');

    //             if (isset($id) && !empty($id)) {
    //                 $data = array(
    //                     'record_status' => 0,
    //                     'updated_by' => $this->user_code,
    //                     'updated_at' => currentDateTimeStamp(),
    //                     'updated_ip_address' => $this->input->ip_address()
    //                 );

    //                 // Insert panel category
    //                 $this->db->where('id', $id);
    //                 $result = $this->db->update('master_minor_stages_table', $data);

    //                 if ($result) {

    //                     success_response("Record deleted successfully", false, true);
    //                 } else {
    //                     failure_response("Error while deleting data", false, true);
    //                 }
    //             }
    //         } catch (Exception $e) {
    //             failure_response("Internal server error, error while deleting data", false, false);
    //         }
    //     } else {
    //         $dbstatus = 'validationerror';
    //         $dbmessage = validation_errors();
    //     }
    //     echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
    // }

    public function get_datatable_data()
    {
        $select_column = array('code', 'name', null, null, null);
        $order_column = array('code', 'name', null, null, null);

        $this->db->select("*");
        $this->db->from('master_minor_stages_table');
        $this->db->where('record_status', 1);
        $this->db->order_by('sl_no', 'ASC');

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('master_minor_stages_table')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    public function fetch_using_major_stage()
    {
        if ($this->input->post('major_case_stage')) {
            $data = $this->minor_stages_setup_model->get_stages_using_major_stage($this->input->post('major_case_stage'));
            echo json_encode([
                'status' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Please provide major case stage.'
            ]);
        }
    }
}
