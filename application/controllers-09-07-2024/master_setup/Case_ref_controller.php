
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Case_ref_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'common_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('all_case_ref_no', 'get_datatable_data', 'store_case_ref_no', 'get_case_ref_no_list', 'update_case_ref_no', 'delete_case_ref_no'),
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

    public function all_case_ref_no()
    {
        $sidebar['menu_item'] = 'Case Ref. No. Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'All Case Ref. No.';
            $this->load->view('master_setup/all_case_ref_no', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function get_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("*");
        $this->db->from('master_case_ref_no_tbl');
        $this->db->where('record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('type', 'ASC');
        $this->db->order_by('start_index', 'ASC');

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('master_case_ref_no_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    public function store_case_ref_no()
    {
        // Panel Category
        $this->form_validation->set_rules('type', 'Category name', 'required|xss_clean');
        $this->form_validation->set_rules('start_index', 'Category name', 'required|xss_clean');
        $this->form_validation->set_rules('end_index', 'Category name', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {

                $data = array(
                    'code' => generateCode(),
                    'type' => $this->input->post('type'),
                    'start_index' => $this->input->post('start_index'),
                    'end_index' => $this->input->post('end_index'),
                    'record_status' => 1,
                    'created_by' => $this->user_code,
                    'created_at' => currentDateTimeStamp(),
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                // Insert panel category
                $result = $this->db->insert('master_case_ref_no_tbl', $data);

                if ($result) {

                    $table_id = $this->db->insert_id();

                    $table_name = 'master_case_ref_no_tbl';
                    $message = 'A new case ref number  is added.';
                    $this->common_model->update_data_logs($table_name, $table_id, $message);

                    $this->db->trans_commit();
                    $dbstatus = true;
                    $dbmessage = "Record saved successfully";
                } else {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Something went wrong.";
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $dbstatus = false;
                $dbmessage = 'Something went wrong';
            }
        } else {
            $dbstatus = 'validationerror';
            $dbmessage = validation_errors();
        }
        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
    }

    public function update_case_ref_no()
    {
        // Panel Category
        $this->form_validation->set_rules('crn_hidden_id', 'ID', 'required|xss_clean');
        $this->form_validation->set_rules('type', 'Category name', 'required|xss_clean');
        $this->form_validation->set_rules('start_index', 'Category name', 'required|xss_clean');
        $this->form_validation->set_rules('end_index', 'Category name', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {

                $data = array(
                    'type' => $this->input->post('type'),
                    'start_index' => $this->input->post('start_index'),
                    'end_index' => $this->input->post('end_index'),
                    'record_status' => 1,
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                // Insert panel category
                $result = $this->db->where('id', $this->input->post('crn_hidden_id'))->update('master_case_ref_no_tbl', $data);

                if ($result) {

                    $table_id = $this->db->insert_id();

                    $table_name = 'master_case_ref_no_tbl';
                    $message = 'Case ref number is updated for id ' . $this->input->post('crn_hidden_id') . '.';
                    $this->common_model->update_data_logs($table_name, $table_id, $message);

                    $this->db->trans_commit();
                    $dbstatus = true;
                    $dbmessage = "Record saved successfully";
                } else {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Something went wrong.";
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $dbstatus = false;
                $dbmessage = 'Something went wrong';
            }
        } else {
            $dbstatus = 'validationerror';
            $dbmessage = validation_errors();
        }
        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
        die;
    }

    // Get case refrence no for user automatic case allotment
    public function get_case_ref_no_list()
    {
        $role = $this->input->post('role');
        if ($role) {
            $type = '';

            if ($role == 'DEPUTY_COUNSEL') {
                $type = 'DC_CASE_REF_NO';
            }
            if ($role == 'CASE_MANAGER') {
                $type = 'CM_CASE_REF_NO';
            }
            if ($role == 'COORDINATOR') {
                $type = 'COORDINATOR_CASE_REF_NO';
            }

            if ($type) {
                echo json_encode($this->getter_model->get(['type' => $type], 'get_case_ref_no_list'));
            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Invalid role provided.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Role is required.'
            ]);
        }
    }

    // Delete
    public function delete_case_ref_no()
    {
        // Panel Category
        $this->form_validation->set_rules('id', 'ID', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {

                $data = array(
                    'record_status' => 0,
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                // Insert panel category
                $result = $this->db->where('code', $this->input->post('id'))->update('master_case_ref_no_tbl', $data);

                if ($result) {

                    $table_id = $this->db->insert_id();

                    $table_name = 'master_case_ref_no_tbl';
                    $message = 'Case ref number is deleted for code ' . $this->input->post('crn_hidden_id') . '.';
                    $this->common_model->update_data_logs($table_name, $table_id, $message);

                    $this->db->trans_commit();
                    $dbstatus = true;
                    $dbmessage = "Record deleted successfully";
                } else {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Something went wrong.";
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $dbstatus = false;
                $dbmessage = 'Something went wrong';
            }
        } else {
            $dbstatus = 'validationerror';
            $dbmessage = validation_errors();
        }
        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
        die;
    }
}
