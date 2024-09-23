<?php

defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");

class Daw_api_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api_helper');
        $this->load->model(array('daw_model'));
    }

    public function generate_daw_reg_no($reg_category)
    {
        $reg_category_array = [
            1 => 'JUD',
            2 => 'FP',
            3 => 'FJ',
            4 => 'SA',
            5 => 'STU',
            6 => 'OTH',
            7 => 'ADV',
            8 => 'GCLH',
        ];

        $last_data = $this->daw_model->get_last_registration_data();
        $last_id = ($last_data && $last_data['id']) ? $last_data['id'] : 0;

        return 'DAW/' . date('Y') . '/' . $reg_category_array[$reg_category] . '/' . rand(999, 9999) . (1000 + $last_id + 1);
    }

    public function store_registration()
    {
        // return failure_message('Registration Closed');
        // die;
        // ini_set('post_max_size', '40M');
        try {
            $this->db->trans_begin();

            // Function to check post request
            $check = check_post_request();
            if ($check == false) {
                return failure_message('Invalid method');
            }

            // print_r($_POST);
            // print_r($_FILES);

            // echo ini_get('post_max_size');
            // echo ini_get('memory_limit');
            // echo ini_get('max_input_vars');

            // die;

            // Check for the validation
            $this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
            $this->form_validation->set_rules(
                'email_id',
                'Email Address',
                'required|xss_clean|is_unique[daw_registrations_tbl.email_address]',
                array(
                    'required' => 'The entered email address field is required.',
                    'is_unique' => 'Email: The provided email address is already registered. Please use a different email address.'
                )
            );
            $this->form_validation->set_rules(
                're_enter_email_id',
                'Re-enter Email Address',
                'required|xss_clean|is_unique[daw_registrations_tbl.email_address]',
                array(
                    'required' => 'The Re-enter Email Address field is required.',
                    'is_unique' => 'Re-enter Email: The provided email address is already registered. Please use a different email address.'
                )
            );
            $this->form_validation->set_rules('organization', 'Organization', 'required|xss_clean');
            $this->form_validation->set_rules('designation', 'Designation', 'required|xss_clean');
            $this->form_validation->set_rules('registrant_category', 'Registrant Category', 'required|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'required|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'required|xss_clean');

            $this->form_validation->set_rules('city', 'City', 'required|xss_clean');
            $this->form_validation->set_rules('address_line_1', 'Address Line 1', 'required|xss_clean');
            $this->form_validation->set_rules('pincode', 'Zip Code/Postal Code', 'required|xss_clean');
            $this->form_validation->set_rules(
                'mobile_number',
                'Mobile Number',
                'required|xss_clean|is_unique[daw_registrations_tbl.mobile_no]',
                array(
                    'required' => 'The Re-enter mobile number field is required.',
                    'is_unique' => 'The provided mobile number is already registered. Please use a different mobile number.'
                )
            );
            $this->form_validation->set_rules('mobile_no_country_code', 'Mobile Number Country Code', 'required|xss_clean');
            $this->form_validation->set_rules('authorization', 'Authorization', 'required|xss_clean');
            $this->form_validation->set_rules('hear_about_event', 'How did you hear about this Delhi Aritration Weekend?', 'required|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $this->db->trans_rollback();
                return failure_message(validation_errors());
            }

            $registration_number = $this->generate_daw_reg_no($this->input->post('registrant_category'));

            // Get the data
            $data = [
                'code' => generateCode(),
                'reg_number' => $registration_number,
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'nick_name' => $this->input->post('nick_name'),
                'email_address' => $this->input->post('email_id'),
                'registrant_category' => $this->input->post('registrant_category'),
                'designation' => $this->input->post('designation'),
                'organization' => $this->input->post('organization'),
                'country' => $this->input->post('country'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'address_line_1' => $this->input->post('address_line_1'),
                'address_line_2' => $this->input->post('address_line_2'),
                'address_line_3' => $this->input->post('address_line_3'),
                'pincode' => $this->input->post('pincode'),
                'mobile_no_country_code' => $this->input->post('mobile_no_country_code'),
                'mobile_no' => $this->input->post('mobile_number'),
                'telephone' => $this->input->post('telephone'),
                'remarks' => $this->input->post('remarks'),
                'hear_about_event' => $this->input->post('hear_about_event'),
                'authorization' => $this->input->post('authorization'),
                'created_at' => currentDateTimeStamp(),
                'updated_at' => currentDateTimeStamp(),
            ];

            // Upload profile photo
            if ($_FILES['profile_photo']['name'] != '') {
                $this->load->library('fileupload');
                // Upload files ==============
                $file_result = $this->fileupload->uploadSingleFile($_FILES['profile_photo'], [
                    'raw_file_name' => 'profile_photo',
                    'file_name' => 'DAW_PP_' . time(),
                    'file_move_path' => DAW_PHOTO_UPLOADS_FOLDER,
                    'allowed_file_types' => DAW_FILE_FORMATS_ALLOWED,
                    'allowed_mime_types' => array('image/png', 'image/jpg', 'image/jpeg')
                ]);

                // After getting result of file upload
                if ($file_result['status'] == false) {
                    $this->db->trans_rollback();
                    return failure_message($file_result['msg']);
                } else {
                    $data['profile_photo'] = $file_result['file'];
                }
            } else {
                // $this->db->trans_rollback();
                // return failure_message('Photo is required');
                $data['profile_photo'] = 'no-image.png';
            }

            $result = $this->db->insert('daw_registrations_tbl', $data);
            if (!$result) {
                $this->db->trans_rollback();
                return failure_message('Server failed while saving data');
            }

            // print_r($_FILES);
            // die;

            // Send email confirmation =================================
            // $email_status = send_daw_registration_success_mail($data);

            $this->db->trans_commit();
            return success_message('Your application is submitted successfully. Please save the registration number for all future communications. Your registration ID is: ' . $registration_number);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return failure_message('Server failed while saving data. Please try again.');
        }
    }

    public function get_countries()
    {
        // Function to check post request
        $check = check_post_request();
        if ($check == false) {
            return failure_message('Invalid method');
        }

        $countries = $this->daw_model->get_countries();
        return success_data($countries);
    }

    public function get_states_using_country()
    {
        // Function to check post request
        $check = check_post_request();
        if ($check == false) {
            return failure_message('Invalid method');
        }

        $country_id = $this->input->post('country_id');

        if ($country_id) {
            $states = $this->daw_model->get_states_using_country($country_id);
            return success_data($states);
        } else {
            return failure_message('Country is required to get states');
        }
    }

    public function get_registrations_select_options_data()
    {
        // Function to check post request
        $check = check_post_request();
        if ($check == false) {
            return failure_message('Invalid method');
        }

        $data = [
            'salutations' => $this->daw_model->get_gen_code_data('SALUTATION'),
            'registrant_categories' => $this->daw_model->get_gen_code_data('REGISTRANT_CATEGORY'),
            'hear_about_event' => $this->daw_model->get_gen_code_data('HEAR_ABOUT_EVENT')
        ];

        return success_data($data);
    }

    public function get_all_live_session_data()
    {
        // Function to check post request
        $check = check_post_request();
        if ($check == false) {
            return failure_message('Invalid method');
        }

        $ls_data = [];
        $ds_categories = $this->db->from('daw_session_dates_category')->where('record_status', 1)->get()->result_array();

        foreach ($ds_categories as $key => $cat) {
            $ds_data = $this->db->from('daw_live_sessions_details_tbl')->where([
                'session_date_code' => $cat['code'],
                'record_status' => 1
            ])->get()->result_array();

            array_push($ls_data, [
                'date' => $cat['session_date'],
                'date_month' => $cat['date_sup'],
                'date_sup' => $cat['date_month'],
                'sessions' => $ds_data
            ]);
        }

        return success_data($ls_data);
    }
}
