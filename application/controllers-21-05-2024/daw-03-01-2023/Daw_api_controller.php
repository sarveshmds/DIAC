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

    public function generate_daw_reg_no()
    {
        $last_data = $this->daw_model->get_last_registration_data();
        return 'DAW/' . date('Y-m') . '/' . rand(999, 9999) . (1000 + $last_data['id'] + 1);
    }

    public function store_registration()
    {
        // Function to check post request
        $check = check_post_request();
        if ($check == false) {
            return failure_message('Invalid method');
        }

        // Get the data
        $data = [
            'code' => generateCode(),
            'reg_number' => $this->generate_daw_reg_no(),
            'title' => $this->input->post('title'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'nick_name' => $this->input->post('nick_name'),
            'email_address' => $this->input->post('email_id'),
            'registrant_category' => $this->input->post('registrant_category'),
            'organization' => $this->input->post('organization'),
            'country' => $this->input->post('country'),
            'state' => $this->input->post('state'),
            'city' => $this->input->post('city'),
            'address_line_1' => $this->input->post('address_line_1'),
            'address_line_2' => $this->input->post('address_line_2'),
            'address_line_3' => $this->input->post('address_line_3'),
            'pincode' => $this->input->post('pincode'),
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
                return failure_message($file_result['msg']);
            } else {
                $data['profile_photo'] = $file_result['file'];
            }
        } else {
            return failure_message('Photo is required');
        }

        $result = $this->db->insert('daw_registrations_tbl', $data);
        if (!$result) {
            return failure_message('Server failed while saving data');
        }

        // print_r($_FILES);
        // die;

        // Send email confirmation =================================
        $email_status = send_daw_registration_success_mail($data);

        return success_message('Your registration done successfully.');
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
}
