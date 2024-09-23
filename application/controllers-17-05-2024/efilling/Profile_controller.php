<?php defined('BASEPATH') or exit('No direct script access allowed');

class profile_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'application_model', 'consent_model', 'Profile_model']);
    }

    public function profileUpdate()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm')) {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_rules('address', 'Address', 'required');
                $this->form_validation->set_rules('pincode', 'Pincode', 'required');
                $this->form_validation->set_rules('alternate_mobile_number', 'Alternate Mobile Number', 'required');
                if ($this->form_validation->run() == TRUE) {

                    $this->db->trans_begin();

                    $id = $this->input->post('hidden_id');
                    $data_arr = array(
                        'user_display_name' => $this->input->post('name'),
                        'updated_by' => 'USER',
                        'updated_on' => now(),
                    );
                    $this->session->set_userdata('user_display_name', $this->input->post('name'));
                    $result = $this->Profile_model->updateUser_master($data_arr, $id);

                    if ($result) {
                        $user_code = $this->input->post('fk_user_code');
                        $data_arr2 = array(
                            'address' => $this->input->post('address'),
                            'pincode' => $this->input->post('pincode'),
                            'alternate_mobile_number' => $this->input->post('alternate_mobile_number'),
                            'updated_by' => 'USER',
                            'updated_on' => now(),
                        );
                        $result2 = $this->Profile_model->updateUser_details_master($data_arr2, $user_code);

                        if ($result2) {
                            $this->db->trans_commit();
                            echo  json_encode([
                                'status' => true,
                                'msg' => 'Details updated successfully.'
                            ]);
                        } else {
                            $this->db->trans_rollback();
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Server failed while saving user data'
                            ]);
                        }
                    } else {
                        $this->db->trans_rollback();
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving user data'
                        ]);
                    }
                } else {
                    $this->db->trans_rollback();
                    $data = array(
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    );
                    echo json_encode($data);
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
