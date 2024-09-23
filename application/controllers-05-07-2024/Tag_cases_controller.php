<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tag_cases_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'getter_model', 'notification_model', 'tag_cases_model'));

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
            'DIAC' => array('all_tag_cases', 'all_tagged_case_datatable', 'store_tagged_case', 'get_single_tagged_case_details', 'update_tagged_case', 'delete_tagged_case'),
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

    public function all_tag_cases()
    {
        $sidebar['menu_item'] = 'Tag Cases';
        $sidebar['menu_group'] = 'Case Management';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Tagged Cases';

        if ($page_status != 0) {
            $data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'CASE_STATUS'], 'GET_GENCODE_DESC');
            $data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
            $data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');

            $data['cases_list'] = $this->common_model->get_all_alloted_case_list();
            $this->load->view('diac-admin/all_tag_cases', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function all_tagged_case_datatable()
    {
        echo json_encode($this->tag_cases_model->get_all_other_noting_messages_datatable());
    }

    public function store_tagged_case()
    {
        $inputCsrfToken = $_POST['csrf_tc_form_token'];
        if ($inputCsrfToken == '') {
            $data = array(
                'status' => false,
                'msg' => EMPTY_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        if (!checkToken($inputCsrfToken, 'tag_case_form')) {
            $data = array(
                'status' => false,
                'msg' => INVALID_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        // Form Validation
        $this->form_validation->set_rules('tc_master_case', 'Master Case', 'required|xss_clean');
        $this->form_validation->set_rules('tc_tagged_case[]', 'Tagged Case', 'required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'status' => 'validationerror',
                'msg' => validation_errors()
            );
            echo json_encode($data);
            die;
        }


        // Check if the master case is in tagged case
        if (in_array($this->input->post('tc_master_case'), $this->input->post('tc_tagged_case'))) {
            $data = array(
                'status' => false,
                'msg' => "You can't tag master case with master case"
            );
            echo json_encode($data);
            die;
        }

        // Check if the case is already tagged with master case or not
        $check_status = $this->tag_cases_model->check_tag_cases();
        if ($check_status['status'] == false) {
            echo json_encode($check_status);
            die;
        }

        $result = $this->tag_cases_model->insert_tagged_case();

        if ($result) {
            echo json_encode(array(
                'status' => true,
                'msg' => 'Tagged cases saved successfully.'
            ));
            die;
        }

        echo json_encode(array(
            'status' => false,
            'msg' => 'Server failed while saving data'
        ));
        die;
    }

    public function get_single_tagged_case_details()
    {
        // Form Validation
        $this->form_validation->set_rules('master_case', 'Master Case', 'required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'status' => 'validationerror',
                'msg' => validation_errors()
            );
            echo json_encode($data);
            die;
        }

        echo json_encode([
            'status' => true,
            'data' => $this->tag_cases_model->get_tagged_case_details_using_master_case($this->input->post('master_case'))
        ]);
    }

    public function update_tagged_case()
    {
        $inputCsrfToken = $_POST['csrf_tc_form_token'];
        if ($inputCsrfToken == '') {
            $data = array(
                'status' => false,
                'msg' => EMPTY_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        if (!checkToken($inputCsrfToken, 'tag_case_form')) {
            $data = array(
                'status' => false,
                'msg' => INVALID_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        // Form Validation
        $this->form_validation->set_rules('tc_master_case', 'Master Case', 'required|xss_clean');
        $this->form_validation->set_rules('tc_tagged_case[]', 'Tagged Case', 'required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'status' => 'validationerror',
                'msg' => validation_errors()
            );
            echo json_encode($data);
            die;
        }


        // Check if the master case is in tagged case
        if (in_array($this->input->post('tc_master_case'), $this->input->post('tc_tagged_case'))) {
            $data = array(
                'status' => false,
                'msg' => "You can't tag master case with master case"
            );
            echo json_encode($data);
            die;
        }

        // Begin the transaction ======================
        $this->db->trans_begin();

        // Delete the previous tagged cases
        $delete_result = $this->tag_cases_model->delete_tagged_cases_using_master_case($this->input->post('tc_master_case'));

        if (!$delete_result) {
            $this->db->trans_rollback();
            $data = array(
                'status' => false,
                'msg' => "Error while updating."
            );
            echo json_encode($data);
            die;
        }


        // Check if the case is already tagged with master case or not
        $check_status = $this->tag_cases_model->check_tag_cases(true);

        if ($check_status['status'] == false) {
            $this->db->trans_rollback();
            echo json_encode($check_status);
            die;
        }

        $result = $this->tag_cases_model->insert_tagged_case();

        if ($result) {
            $this->db->trans_commit();
            echo json_encode(array(
                'status' => true,
                'msg' => 'Tagged cases updated successfully.'
            ));
            die;
        }

        $this->db->trans_rollback();
        echo json_encode(array(
            'status' => false,
            'msg' => 'Server failed while saving data'
        ));
        die;
    }

    public function delete_tagged_case()
    {
        $inputCsrfToken = $_POST['csrf_trans_token'];
        if ($inputCsrfToken == '') {
            $data = array(
                'status' => false,
                'msg' => EMPTY_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        if (!checkToken($inputCsrfToken, 'datatable_tagged_cases')) {
            $data = array(
                'status' => false,
                'msg' => INVALID_TOKEN_ERROR
            );
            echo json_encode($data);
            die;
        }

        // Form Validation
        $this->form_validation->set_rules('master_case', 'Master Case', 'required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'status' => 'validationerror',
                'msg' => validation_errors()
            );
            echo json_encode($data);
            die;
        }

        // Begin the transaction ======================
        $this->db->trans_begin();

        // Delete the previous tagged cases
        $delete_result = $this->tag_cases_model->delete_tagged_cases_using_master_case($this->input->post('master_case'));

        if ($delete_result) {
            $this->db->trans_commit();
            echo json_encode(array(
                'status' => true,
                'msg' => 'Tagged cases deleted successfully.'
            ));
            die;
        }

        $this->db->trans_rollback();
        echo json_encode(array(
            'status' => false,
            'msg' => 'Server failed while saving data'
        ));
        die;
    }
}
