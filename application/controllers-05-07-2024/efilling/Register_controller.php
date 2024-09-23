<?php defined('BASEPATH') or exit('No direct script access allowed');

class Register_controller extends CI_Controller
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
        $this->load->model(['register_model', 'efiling_model']);
    }
    public function register()
    {

        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken == '') {
            failure_response(EMPTY_TOKEN_ERROR, false, false);
        }

        if (!checkToken($inputCsrfToken, 'frm4')) {
            failure_response(INVALID_TOKEN_ERROR, false, false);
        }

        // Validation
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('job_title', 'Name', 'required');
        $this->form_validation->set_rules('salutation', 'Salutation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user_master.email]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|min_length[10]|max_length[10]|is_unique[user_master.phone_number]');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required');

        if ($this->form_validation->run() == TRUE) {

            // Check captcha
            if ($this->input->post('captcha') == $this->session->userdata('captchaword')) {
                $this->db->trans_begin();

                $user_name = $this->input->post('phone_number');
                $password = randomPassword($user_name);

                $primary_role = '';
                $empanellment_no = '';
                $enrollment_no = '';

                if ($this->input->post('job_title') == 'Party') {
                    $primary_role = 'PARTY';
                }

                if ($this->input->post('job_title') == 'Advocate') {
                    $primary_role = 'ADVOCATE';
                    $enrollment_no = $this->input->post('enrollment_no');
                }

                if ($this->input->post('job_title') == 'Arbitrator') {
                    $primary_role = 'ARBITRATOR';
                    $empanellment_no = $this->input->post('empanellment_no');
                }

                if ($this->input->post('job_title') == 'Advocate & Arbitrator') {
                    $primary_role = 'ADVOCATE_ARBITRATOR';
                    $empanellment_no = $this->input->post('empanellment_no');
                    $enrollment_no = $this->input->post('enrollment_no');
                }

                // Check if primary role is set or not
                if (!$primary_role) {
                    failure_response('Please select valid job title or contact support team.', false, true);
                }

                $user_code = generateUserCode();

                $basic_details_data_arr = array(
                    'user_code' => $user_code,
                    'salutation' => $this->input->post('salutation'),
                    'user_display_name' => $this->input->post('name'),
                    'job_title' => $this->input->post('job_title'),
                    'user_name' => $this->input->post('phone_number'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'password' => $password['secreatepassword'],
                    'primary_role' => $primary_role,
                    'created_by' => 'USER',
                    'created_on' => now(),
                    'updated_by' => 'USER',
                    'updated_on' => now(),
                    'record_status' => 1,
                    'is_new_record' => 0,
                );

                $result = $this->register_model->insertUser_master($basic_details_data_arr);
                if ($result) {
                    $data_arr2 = array(
                        'fk_user_code' => $user_code,
                        'alternate_mobile_number' => $this->input->post('alternate_phone_number'),
                        'address' => $this->input->post('address'),
                        'pincode' => $this->input->post('pincode'),
                        'permanent_address' => $this->input->post('permanent_address'),
                        'permanent_address_pincode' => $this->input->post('permanent_address_pincode'),
                        'empanellment_no' => $empanellment_no,
                        'enrollment_no' => $enrollment_no,
                        'created_by' => 'USER',
                        'created_on' => now(),
                        'updated_by' => 'USER',
                        'updated_on' => now(),
                    );

                    $result2 = $this->register_model->insertUser_details_master($data_arr2);

                    if ($result2) {

                        // Send confirmation email ====================
                        // $emailStatus = email_efiling_user_created_success($basic_details_data_arr,  $password);

                        // if ($emailStatus['status'] == false) {
                        //     failure_response(EMAIL_ERROR, false, true);
                        // }

                        success_response(USER_CREATED_PASSWORD_SENT, false, true);
                    } else {
                        failure_response(SERVER_SAVING_ERROR, false, true);
                    }
                } else {
                    failure_response(SERVER_SAVING_ERROR, false, true);
                }
            } else {
                failure_response(INVALID_CAPTCHA, false, true);
            }
        } else {
            failure_response(validation_errors(), false, true);
        }
    }
}
