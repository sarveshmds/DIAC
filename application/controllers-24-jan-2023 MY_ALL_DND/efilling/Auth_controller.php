
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'efiling_login_model', 'efiling_model']);

        $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

        if ($this->session->userdata('key') == '')
            $this->session->set_userdata('key', uniqid());
    }

    /**
     *	purpose : Entry point of user controller
     */
    public function index()
    {
        redirect('login');
    }
    //This function is used for creating captcha//
    private function create_captcha()
    {
        // we will first load the helper. We will not be using autoload because we only need it here
        $this->load->helper('captcha');
        $capache_config = array(
            'word' => rand(99999, 999999),
            'img_path' => 'public/upload/captcha/',
            'img_url' => base_url() . 'public/upload/captcha/',
            'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
            'img_width' => '200',
        );
        // we will set all the variables needed to create the captcha image
        /*$options = array('img_path'=>FCPATH.'captcha/','img_url'=>site_url().'captcha/','img_width'=>'150','img_height'=>'40','expiration'=>7200,'word_length' => 6,'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ','font_size' => 46,'colors' => array( 'background' => array(255,255,255), 'border' => array(153,102,102), 'text' => array(204,153,153), 'grid' => array(255,182,182)) );*/
        //now we will create the captcha by using the helper function create_captcha()
        $cap = create_captcha($capache_config);
        // we will store the image html code in a variable
        $image = $cap['image'];

        // ...and store the captcha word in a session
        $this->session->set_userdata('captchaword', $cap['word']);
        // we will return the image html code
        return $image;
    }

    //This Function is used to refresh the Captcha //
    public function refresh_captcha()
    {
        $this->load->helper('captcha');
        // Captcha configuration
        $capache_config = array(
            'word' => rand(99999, 999999),
            'img_path' => 'public/upload/captcha/',
            'img_url' => base_url() . 'public/upload/captcha/',
            'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
            'img_width' => '218',
        );
        $captcha = create_captcha($capache_config);
        $image = $captcha['image'];

        // Unset previous captcha and store new captcha word
        $this->session->unset_userdata('captchaword', $captcha['word']);
        $this->session->set_userdata('captchaword', $captcha['word']);

        // Display captcha image
        echo  $image;
    }

    public function efiling_login()
    {
        $data['image'] = $this->create_captcha();
        $this->load->view('efiling/efiling_login', $data);
    }

    public function login()
    {
        $inputCsrfToken = $_POST['csrf_login_form_token'];
        if ($inputCsrfToken != '') {

            if (checkToken($inputCsrfToken, 'login_form')) {
                $config = array(
                    array(
                        'field'   => 'txtUsername',
                        'label'   => 'Username',
                        'rules'   => 'required|xss_clean'
                    ),
                    array(
                        'field'   => 'txtPassword',
                        'label'   => 'Password',
                        'rules'   => 'required|xss_clean'
                    ),
                    array(
                        'field'   => 'txt_captcha',
                        'label'   => 'Captcha',
                        'rules'   => 'required|xss_clean'
                    ),
                );

                $this->form_validation->set_rules($config);
                if ($this->form_validation->run() == FALSE) {
                    $data = array(
                        'status' => 'validationerror',
                        'msg' => validation_errors()
                    );
                    echo json_encode($data);
                } else {
                    echo json_encode($this->efiling_login_model->login());
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

    public function set_password()
    {
        if ($this->session->has_userdata('user_data')) {
            $this->load->view('efiling/efiling_set_password');
        } else {
            return redirect('efiling/login');
        }
    }


    public function set_new_password()
    {
        if (!$this->session->has_userdata('user_data')) {
            $data = array(
                'status' => false,
                'msg' => 'Invalid action performed. Please try again or contact support team.'
            );
            echo json_encode($data);
            die;
        }

        $temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
        if (hasXSS($_POST, $temp_rule)) {
            $data = array(
                'status' => false,
                'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
            );
            echo json_encode($data);
        } else {
            $input_csrf_token = $_POST['csrf_setpassword_token'];
            if (!empty($input_csrf_token)) {
                if (checkToken($input_csrf_token, 'setPasswordForm')) {
                    $config = array(
                        array(
                            'field'   => 'txtUserCode',
                            'label'   => 'User Code',
                            'rules'   => 'required|xss_clean'
                        ),
                        array(
                            'field'   => 'txtUserName',
                            'label'   => 'Username',
                            'rules'   => 'required|xss_clean'
                        ),
                        array(
                            'field'   => 'txtPassword',
                            'label'   => 'Password',
                            'rules'   => 'required|xss_clean'
                        ),
                        array(
                            'field'   => 'txtConPassword',
                            'label'   => 'Confirm Password',
                            'rules'   => 'required|matches[txtPassword]|xss_clean'
                        )
                    );
                    $this->form_validation->set_rules($config);
                    if ($this->form_validation->run() == FALSE) {
                        $data = array(
                            'status' => 'validationerror',
                            'msg' => validation_errors()
                        );
                        echo json_encode($data);
                    } else {
                        echo json_encode($this->efiling_model->set_new_user_password());
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


    public function efiling_logout()
    {
        $this->load->model('user_model');

        $result = $this->user_model->logout();
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_code');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_display_name');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('role_name');
        $this->session->unset_userdata('group_code');
        $this->session->unset_userdata('dept_code');
        $this->session->unset_userdata('captchaword');
        $this->session->unset_userdata('sess_id');
        $this->session->unset_userdata('state_code');
        $this->session->sess_destroy();
        // redirect to login
        $this->session->set_flashdata('info', 'User logout');
        redirect('efiling/login');
    }

    public function efiling_register()
    {
        $data['image'] = $this->create_captcha();
        $this->load->view('efiling/efiling_register', $data);
    }

    public function efiling_forgot_password()
    {
        $data['image'] = $this->create_captcha();
        $this->load->view('efiling/efiling_forgot_password', $data);
    }

    public function efiling_forgot_password_verify()
    {

        $inputCsrfToken = $_POST['csrf_fp_form_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'fp_form')) {

                // Validation
                $this->load->library('form_validation');
                $this->form_validation->set_rules('txtUsername', 'User Name', 'required');
                $this->form_validation->set_rules('txt_captcha', 'Captcha', 'required');

                if ($this->form_validation->run() == TRUE) {

                    // Check captcha
                    if ($this->input->post('txt_captcha') == $this->session->userdata('captchaword')) {
                        $this->db->trans_begin();

                        $user_name = $this->input->post('txtUsername');
                        // Check if the user exist or not
                        $user_data = $this->efiling_model->check_user_exist($user_name);

                        $email = $user_data['email'];
                        $phone_number = $user_data['phone_number'];


                        // Check if primary role is set or not
                        if (!in_array($user_data['primary_role'], ['ARBITRATOR', 'ADVOCATE', 'PARTY'])) {
                            $this->db->trans_rollback();
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Invalid user name provided for e-filing forgot password.'
                            ]);
                            die;
                        }


                        // Generate otp
                        $otp = rand(99999, 999999);

                        $data = [
                            'otp' => $otp,
                            'expiration_time' => otp_expiration_time(),
                            'username' => $user_name,
                            'status' => 1,
                            'created_by' => $user_name,
                            'created_at' => now(),
                            'updated_by' => $user_name,
                            'updated_at' => now(),
                        ];

                        // Store otp in database
                        if ($this->db->insert('cs_otp_verification_tbl', $data)) {

                            // Send the otp
                            // If email id is provided
                            if ($email) {

                                // Send email
                                $result = forget_password_otp($user_data, $otp);

                                if ($result['status']) {
                                    $this->session->set_userdata('otp_verification', true);
                                    $this->session->set_userdata('user_data', $user_data);

                                    $this->db->trans_commit();
                                    echo json_encode([
                                        'status' => true,
                                        'msg' => 'OTP sent on your email id and phone number',
                                        'redirect_url' => base_url() . 'efiling/forgot-password/otp-verification'
                                    ]);
                                    die;
                                } else {
                                    $this->db->trans_rollback();
                                    echo json_encode([
                                        'status' => false,
                                        'msg' => 'Server failed while sending OTP'
                                    ]);
                                    die;
                                }
                            } else {
                                $this->db->trans_rollback();
                                echo json_encode([
                                    'status' => false,
                                    'msg' => 'Invalid reqest, please try again.'
                                ]);
                                die;
                            }
                        } else {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Invalid reqest, please try again.'
                            ]);
                            die;
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

    public function efiling_fp_otp_verification()
    {

        if ($this->session->has_userdata('otp_verification') && $this->session->userdata('otp_verification') == true) {

            $data['image'] = $this->create_captcha();
            $this->load->view('efiling/efiling_fp_otp_verification', $data);
        } else {
            return redirect('efiling/forgot-password');
        }
    }

    public function verify_forgot_password_otp()
    {
        $inputCsrfToken = $_POST['csrf_fp_otp_form_token'];
        if ($inputCsrfToken != '') {

            if (checkToken($inputCsrfToken, 'fp_otp_form')) {
                $config = array(
                    array(
                        'field'   => 'otp',
                        'label'   => 'OTP',
                        'rules'   => 'required|xss_clean'
                    ),
                    array(
                        'field'   => 'txt_captcha',
                        'label'   => 'Captcha',
                        'rules'   => 'required|xss_clean'
                    ),
                );

                $this->form_validation->set_rules($config);
                if ($this->form_validation->run() == FALSE) {
                    $data = array(
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    );
                    echo json_encode($data);
                } else {

                    if ($this->input->post('txt_captcha') != $this->session->userdata('captchaword')) {
                        $data = array(
                            'status' => false,
                            'msg' => "Invalid captcha value"
                        );
                        echo json_encode($data);
                        die;
                    }

                    echo json_encode($this->efiling_model->verify_forgot_password_otp());
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
}
