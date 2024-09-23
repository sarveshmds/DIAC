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
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm4')) {


                // Validation
                $this->load->library('form_validation');
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
                            $this->db->trans_rollback();
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Please select valid job title or contact support team.'
                            ]);
                            die;
                        }

                        $user_code = time() . rand(999, 99999);

                        $data_arr = array(
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

                        $result = $this->register_model->insertUser_master($data_arr);
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

                                // Send mail
                                $subject  = "E-filing DIAC: User created successfully";
                                $body    = "Dear " . $this->input->post('name') . ",<br>
								<p style='margin-left:100px'>Your account has been created in e-filing DIAC. Below is your user id and password</p> 
                                <p>User ID: " . $this->input->post('phone_number') . "</p>
                                <p>Password: " . $password['password'] . "</p>
								<p>Note: Do not this detail with anyone.</p>";

                                $this->load->helper('send_email');
                                $emailStatus = sendEmails($this->input->post('email'),  $subject, $body, '', '');

                                if ($emailStatus['status'] == false) {
                                    $this->db->trans_rollback();
                                    echo  json_encode([
                                        'status' => false,
                                        'msg' => 'Error while sending mail. Please try again later.'
                                    ]);
                                    die;
                                }

                                $this->db->trans_commit();
                                echo  json_encode([
                                    'status' => true,
                                    'msg' => 'User created successfully and password sent on your email ID.'
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
                                'msg' => 'Server failed while saving data'
                            ]);
                        }
                    } else {
                        $this->db->trans_rollback();
                        $data = array(
                            'status' => false,
                            'msg' => 'Wrong Captcha! Please try again.'
                        );
                        echo json_encode($data);
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
