
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Empanellment_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('getter_model', 'category_model', 'empanellment_model', 'master_setup/salutation_model', 'master_setup/country_model', 'master_setup/Nationality_model'));
    }

    // public function start_empanellment()
    // {
    //     // Generate the captcha
    //     $this->load->library('captcha_generator');
    //     $data['image'] = $this->captcha_generator->create_captcha();

    //     $this->load->view('efiling/empanellment/start_empanellment_form', $data);
    // }

    public function newStartEmpanellment()
    {
        $this->load->library('captcha_generator');
        $data['image'] = $this->captcha_generator->create_captcha();

        $this->load->view('efiling/empanellment/new_start_empanellment_form', $data);
    }

    public function store_start_empanellment()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'start_emp_form')) {
                // Validation 
                $this->form_validation->set_rules('txt_captcha', 'Captcha', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|min_length[10]|max_length[12]');

                if ($this->form_validation->run() == TRUE) {

                    // Check if captcha is valid or not
                    if ($this->session->userdata('captchaword') != $this->input->post('txt_captcha')) {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Invalid captcha provided, please enter correct captcha',
                            'redirect_url' => ''
                        ]);
                        die;
                    }

                    $email = $this->input->post('email');
                    $phone = $this->input->post('phone_number');

                    // ==========================================
                    // Check using email id 

                    // check empanellment form submitted or not 
                    $emp_details_using_email = $this->db->select('*')
                        ->from('arb_emp_registration_tbl')
                        ->where('email_id', $email)
                        // ->where('phone_number', $phone)
                        ->where('record_status', 1)
                        ->get()
                        ->row_array();

                    if ($emp_details_using_email) {
                        if ($emp_details_using_email['phone_number'] != $phone) {
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Phone number you have provided is not matched with the attached phone number with this email id.',
                                'redirect_url' => ""
                            ]);
                            die;
                        }
                    }

                    // ==========================================
                    // Check using phone number 
                    $emp_details_using_phone = $this->db->select('*')
                        ->from('arb_emp_registration_tbl')
                        // ->where('email_id', $email)
                        ->where('phone_number', $phone)
                        ->where('record_status', 1)
                        ->get()
                        ->row_array();

                    if ($emp_details_using_phone) {
                        if ($emp_details_using_phone['phone_number'] != $phone) {
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Email Id you have provided is not matched with the attached email id with this phone number.',
                                'redirect_url' => ""
                            ]);
                            die;
                        }
                    }

                    if ($emp_details_using_email && $emp_details_using_phone) {

                        $emp_details = $emp_details_using_email;

                        if ($emp_details['is_submitted'] == 0) {
                            if ($emp_details['step'] == null) {
                                echo  json_encode([
                                    'status' => true,
                                    'msg' => 'You have already registered please fill personal information of your Empanellment form !',
                                    'redirect_url' => base_url('efiling/empanellment/personal-information?id=' . $emp_details['id'])
                                ]);
                                die;
                            }
                            if ($emp_details['step'] == 1) {
                                echo  json_encode([
                                    'status' => true,
                                    'msg' => 'You have already filled personal information of your Empanellment form !',
                                    'redirect_url' => base_url('efiling/empanellment/personal-information?id=' . $emp_details['id'])
                                ]);
                                die;
                            }
                            if ($emp_details['step'] == 2) {
                                echo  json_encode([
                                    'status' => true,
                                    'msg' => 'You have already filled professional information of your Empanellment form !',
                                    'redirect_url' => base_url('efiling/empanellment/professional-information?id=' . $emp_details['id'])
                                ]);
                                die;
                            }
                            if ($emp_details['step'] == 3) {
                                echo  json_encode([
                                    'status' => true,
                                    'msg' => 'You have submitted documents of your Empanellment form !',
                                    'redirect_url' => base_url('efiling/empanellment/documents?id=' . $emp_details['id'])
                                ]);
                                die;
                            }
                        }

                        if ($emp_details['is_submitted'] == 1) {
                            echo  json_encode([
                                'status' => 'ALREADY_SUBMITTED',
                                'msg' => 'Your Empanellment form is already submitted. Contact DIAC, in case of any information.',
                                'redirect_url' => base_url('efiling/empanellment/new-start')
                            ]);
                            die;
                        }
                    }


                    // ========================================
                    $row_count = $this->db->count_all('arb_emp_registration_tbl');

                    $diary_number = generateDiaryNumber($row_count, 'AE');

                    $data = array(
                        'diary_number' => $diary_number,
                        'email_id' => $email,
                        'phone_number' => $phone,
                        'created_by' => $email,
                        'updated_by' => $email,
                    );

                    $result_or_id = $this->empanellment_model->store_arb_emp_registration($data);

                    if ($result_or_id) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Registration process started. Move to step 1 (personal information) to fill the form.',
                            'redirect_url' => base_url('efiling/empanellment/personal-information?id=' . $result_or_id)
                        ]);
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
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
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function personal_information()
    {
        $data['arb_id'] = $this->input->get('id');
        // echo $data['arb_id'];
        // die;
        // CHECK IF THE ID IS AVAILABLE OR NOT
        if (!$data['arb_id']) {
            return redirect('efiling/empanellment/new-start');
        }

        // FETCH THE DATA USING ID
        $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
        if (!$data['arb_reg_data']) {
            return redirect('efiling/empanellment/new-start');
        }
        // print_r($data['arb_reg_data']);
        // die;

        // Arbitrator personal information
        $data['arb_personal_info'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);

        // Arbitrator professional informations
        $data['arb_proffessional_info'] = $this->empanellment_model->fetch_arb_professional_information_using_arb_id($data['arb_id']);

        // Arbitrator documents
        $data['arb_doc_info'] = $this->empanellment_model->fetch_arb_documents_information_using_arb_id($data['arb_id']);

        // Arbitrator other empanellment data
        $data['empanel_with_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

        // Captcha
        $this->load->library('captcha_generator');
        $data['image'] = $this->captcha_generator->create_captcha();
        $data['countries'] = $this->country_model->getAllCountries();
        $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
        $data['salutations'] = $this->salutation_model->getAllSalutations();

        $data['nationality'] = $this->country_model->getAllCountriesWithNationality();
        // echo "<pre>";
        // print_r($data['arb_personal_info']);
        // die;

        $data['current_step'] = 1;

        $this->load->view('efiling/empanellment/empanellment_form', $data);
    }

    public function store_personal_information()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'csrf_emp_personal_info_form')) {
                // Validation 
                $this->form_validation->set_rules('hidden_arb_id', 'Arb. Reg. ID', 'required');
                $this->form_validation->set_rules('salutation', 'Salutation', 'required');
                $this->form_validation->set_rules('first_name', 'First Name', 'required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required');
                $this->form_validation->set_rules('empanellment_category', 'Empanellment Category', 'required');

                if ($this->input->post('empanellment_category') == 'OTHER') {
                    $this->form_validation->set_rules('other_cat_specify', 'Specify Other Category', 'required');
                }

                // $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                // $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[10]');
                $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
                $this->form_validation->set_rules('languages_known', 'Languages Known', 'required');
                $this->form_validation->set_rules('nationality', 'Nationality', 'required');

                // if ($this->input->post('nationality') == 'NRI') {
                //     $this->form_validation->set_rules('nri_country', 'NRI Country', 'required');
                // }

                $this->form_validation->set_rules('resident_add_1', 'Resident Address 1', 'required');
                $this->form_validation->set_rules('resident_country', 'Resident Country', 'required');
                $this->form_validation->set_rules('resident_state', 'Resident State', 'required');
                $this->form_validation->set_rules('resident_city', 'Resident State', 'required');
                $this->form_validation->set_rules('resident_pincode', 'Resident Pincode', 'required');

                $this->form_validation->set_rules('office_add_1', 'Office Address 1', 'trim', 'required');
                $this->form_validation->set_rules('office_country', 'Office Country', 'trim', 'required');
                $this->form_validation->set_rules('office_state', 'Office State', 'trim', 'required');
                $this->form_validation->set_rules('office_city', 'Office City', 'trim', 'required');
                $this->form_validation->set_rules('office_pincode', 'Office Pincode', 'trim', 'required');
                $this->form_validation->set_rules('office_company_name', 'Office Name', 'trim', 'required');
                $this->form_validation->set_rules('office_phone_number', 'Office Phone Number', 'trim', 'required');
                $this->form_validation->set_rules('office_website', 'Office Name', 'trim', 'required');

                $this->form_validation->set_rules('correspondance_add_1', 'Correspondance Address 1', 'required');
                $this->form_validation->set_rules('correspondance_country', 'Correspondance Country', 'required');
                $this->form_validation->set_rules('correspondance_state', 'Correspondance State', 'required');
                $this->form_validation->set_rules('correspondance_city', 'Correspondance City', 'required');
                $this->form_validation->set_rules('correspondance_pincode', 'Correspondance Pincode', 'required');

                $this->form_validation->set_rules('txt_captcha', 'Captcha', 'required');

                if ($this->form_validation->run() == TRUE) {
                    if ($this->session->userdata('captchaword') == $this->input->post('txt_captcha')) {
                        $data = array(
                            'code' => generateCode(),
                            'arb_id' => $this->input->post('hidden_arb_id'),
                            'empanellment_category' => $this->input->post('empanellment_category'),

                            'salutation' => $this->input->post('salutation'),
                            'first_name' => $this->input->post('first_name'),
                            'middle_name' => $this->input->post('middle_name'),
                            'last_name' => $this->input->post('last_name'),

                            'alternate_mobile' => $this->input->post('alternate_mobile'),
                            'dob' => formatDate($this->input->post('dob')),
                            'languages_known' => $this->input->post('languages_known'),
                            'nationality' => $this->input->post('nationality'),
                            // 'nri_country' => ($this->input->post('nationality') == 'NRI') ? $this->input->post('nri_country') : '',

                            'resident_add_1' => $this->input->post('resident_add_1'),
                            'resident_add_2' => $this->input->post('resident_add_2'),
                            'resident_country' => $this->input->post('resident_country'),
                            'resident_state' => $this->input->post('resident_state'),
                            'resident_city' => $this->input->post('resident_city'),
                            'resident_pincode' => $this->input->post('resident_pincode'),

                            'office_add_1' => $this->input->post('office_add_1'),
                            'office_add_2' => $this->input->post('office_add_2'),
                            'office_country' => $this->input->post('office_country'),
                            'office_state' => $this->input->post('office_state'),
                            'office_city' => $this->input->post('office_city'),
                            'office_pincode' => $this->input->post('office_pincode'),
                            'office_company_name' => $this->input->post('office_company_name'),
                            'office_phone_number' => $this->input->post('office_phone_number'),
                            'office_website' => $this->input->post('office_website'),
                            'office_email_1' => $this->input->post('office_email_1'),
                            'office_email_2' => $this->input->post('office_email_2'),

                            'correspondance_add_1' => $this->input->post('correspondance_add_1'),
                            'correspondance_add_2' => $this->input->post('correspondance_add_2'),
                            'correspondance_country' => $this->input->post('correspondance_country'),
                            'correspondance_state' => $this->input->post('correspondance_state'),
                            'correspondance_city' => $this->input->post('correspondance_city'),
                            'correspondance_pincode' => $this->input->post('correspondance_pincode'),
                        );
                        if ($this->input->post('empanellment_category') == 'ENG') {
                            $data = ['is_engineer_govt_employee' => $this->input->post('is_engineer_govt_emp')];
                        }
                        if ($this->input->post('empanellment_category') == 'OTHER') {
                            $data = ['specify_other_cat' => $this->input->post('other_cat_specify')];
                        }

                        if ($this->input->post('form_type') != 'ADD') {
                            $result = $this->empanellment_model->update_personal_information($data, $this->input->post('hidden_id'));
                        } else {
                            $result = $this->empanellment_model->insert_personal_information($data);
                            $steps = ['step' => 1];
                            $this->empanellment_model->update_form_steps($steps, $this->input->post('hidden_arb_id'));
                        }

                        if ($result) {
                            echo  json_encode([
                                'status' => true,
                                'msg' => 'Personal information addded successfully. Move to next step (professional information) to fill the professional information.',
                                'redirect_url' => base_url('efiling/empanellment/professional-information?id=' . $this->input->post('hidden_arb_id'))
                            ]);
                            die;
                            // echo  json_encode([
                            //     'status' => true,
                            //     'msg' => 'Personal information addded successfully. Move to next step (professional information) to fill the professional information.',
                            //     'redirect_url' => base_url('efiling/empanellment/new-form')
                            // ]);
                        } else {
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Server failed while saving data'
                            ]);
                            die;
                        }
                    } else {
                        $data = array(
                            'status' => false,
                            'msg' => 'Invalid Captcha.Please Try Again!!!'
                        );
                        echo json_encode($data);
                        die;
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
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function professional_information()
    {
        $data['arb_id'] = $this->input->get('id');

        // CHECK IF THE ID IS AVAILABLE OR NOT
        if (!$data['arb_id']) {
            return redirect('efiling/empanellment/new-start');
        }

        // FETCH THE DATA USING ID
        $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
        if (!$data['arb_reg_data']) {
            return redirect('efiling/empanellment/new-start');
        }

        if ($data['arb_reg_data']['step'] < 1) {
            return redirect('efiling/empanellment/personal-information?id=' . $data['arb_reg_data']['id']);
        }

        // Arbitrator personal information
        $data['arb_personal_info'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);

        // Arbitrator professional informations
        $data['arb_proffessional_info'] = $this->empanellment_model->fetch_arb_professional_information_using_arb_id($data['arb_id']);

        // Arbitrator documents
        $data['arb_doc_info'] = $this->empanellment_model->fetch_arb_documents_information_using_arb_id($data['arb_id']);

        // Arbitrator other empanellment data
        $data['empanel_with_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

        // echo "<pre>";
        // print_r($data['arb_proffessional_info']);
        // echo "</pre>";
        // die;

        // Captcha
        $this->load->library('captcha_generator');
        $data['image'] = $this->captcha_generator->create_captcha();

        $data['countries'] = $this->country_model->getAllCountries();
        $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
        $data['salutations'] = $this->salutation_model->getAllSalutations();

        $data['current_step'] = 2;

        $this->load->view('efiling/empanellment/empanellment_form', $data);
    }

    public function store_professional_information()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'csrf_emp_prof_info_form')) {
                // Validation 
                $this->form_validation->set_rules('hidden_arb_id', 'Arb. Reg. ID', 'required');
                // $this->form_validation->set_rules('current_prof_occup', 'current occupation', 'required');

                if ($this->form_validation->run() == TRUE) {
                    if ($this->session->userdata('captchaword') == $this->input->post('txt_captcha')) {

                        // Fetch the arb emp details
                        $arb_emp_cat_result = $this->empanellment_model->get_arb_personal_info($this->input->post('hidden_arb_id'));
                        // print_r($arb_emp_cat_result['empanellment_category']);
                        // die;

                        $data = array(
                            // 'empanelment_category' => $this->input->post('professional_emp_cat'),
                            'arb_id' => $this->input->post('hidden_arb_id'),
                            'current_occupation' => $this->input->post('current_prof_occup'),
                            'professional_history' => $this->input->post('professional_history'),
                            'academic_qualification' => $this->input->post('academic_qua'),
                            'highest_qualification' => $this->input->post('highest_quali'),
                            'arbitration_experiance' => $this->input->post('arb_exp'),
                            'other_experiance' => $this->input->post('other_exp'),
                            'other_experiance_details' => $this->input->post('other_exp_details'),
                            'expertise_area' => $this->input->post('expertise_area'),
                            'advocate_enrol_no' => $this->input->post(''),
                            'advocate_date_of_enroll' => $this->input->post(''),
                            'advocate_name_of_bar_council' => $this->input->post(''),
                            'advocate_years_standing_at_bar' => $this->input->post(''),
                            'advocate_have_ten_years_exp' => $this->input->post(''),
                            'advocate_whether_appearance_before_court' => $this->input->post(''),
                            'is_itr_declare_for_first_year' => $this->input->post('net_income_for_22'),
                            'net_prof_income_for_22' => $this->input->post(''),
                            'is_itr_declare_for_second_year' => $this->input->post('net_income_for_23'),
                            'net_prof_income_for_23' => $this->input->post(''),
                            'retired_judg_bure_date_of_sup_annuation' => $this->input->post('retired_judg_bure_date_of_sup_annuation'),
                            'retired_judg_bure_last_designation' => $this->input->post('retired_judg_bure_last_desig'),
                            'retired_judg_bure_department_name' => $this->input->post('retired_judg_bure_department_name'),

                            'any_criminal_proceeding' => $this->input->post('disciplinary_complaint'),
                            'criminal_proceeding_details' => '',

                            'is_employed_as_MOT' => $this->input->post('member_of_tribual'),
                            'MOT_auth_name' => $this->input->post(''),
                            'MOT_designation' => $this->input->post(''),
                            'MOT_appoint_date' => $this->input->post(''),
                            'MOT_total_tenure' => $this->input->post(''),
                            'MOT_retire_date' => $this->input->post(''),

                            'is_already_empanelled_arb' => $this->input->post('empanel_arb_other'),
                            // 'emp_arb_other_NOI' => $this->input->post('emp_arb_other_NOI'),
                            // 'emp_arb_other_DOE' => $this->input->post('emp_arb_other_DOE'),
                            'NOM_as_arb' => $this->input->post('NOM_as_arb'),
                            'academic_details_as_arb' => $this->input->post('academic_details_as_arb'),
                            'prof_other_info' => $this->input->post('prof_other_info'),
                        );

                        if ($arb_emp_cat_result['empanellment_category'] == 'ADV') {
                            $data['advocate_enrol_no'] = $this->input->post('advocate_enrol_no');
                            $data['advocate_date_of_enroll'] = $this->input->post('advocate_date_of_enroll');
                            $data['advocate_name_of_bar_council'] = $this->input->post('advocate_name_of_bar_council');
                            $data['advocate_years_standing_at_bar'] = $this->input->post('advocate_years_standing_at_bar');
                            $data['advocate_have_ten_years_exp'] = $this->input->post('adv_have_ten_year_exp');
                            $data['advocate_whether_appearance_before_court'] = $this->input->post('adv_whether_appearance_before_court');
                        }

                        if ($this->input->post('net_income_for_22') == 'yes') {
                            $data['net_prof_income_for_22'] = $this->input->post('net_prof_income_for_22');
                        }

                        if ($this->input->post('net_income_for_23') == 'yes') {
                            $data['net_prof_income_for_23'] = $this->input->post('net_prof_income_for_23');
                        }

                        if ($this->input->post('disciplinary_complaint') == 'yes') {
                            $data['criminal_proceeding_details'] = $this->input->post('criminal_proc_details');
                        }

                        if ($this->input->post('member_of_tribual') == 'yes') {
                            $data['MOT_auth_name'] = $this->input->post('MOT_auth_name');
                            $data['MOT_designation'] = $this->input->post('MOT_designation');
                            $data['MOT_appoint_date'] = $this->input->post('MOT_appoint_date');
                            $data['MOT_total_tenure'] = $this->input->post('MOT_total_tenure');
                            $data['MOT_retire_date'] = $this->input->post('MOT_retire_date');
                        }

                        if ($this->input->post('empanel_arb_other') == 'yes') {
                            // Assuming input values are arrays
                            $arb_ids = $this->input->post('hidden_arb_id');
                            $name_of_institute = $this->input->post('emp_arb_other_NOI');
                            $date_of_empanellment = $this->input->post('emp_arb_other_DOE');

                            // Check if $arb_ids is an array
                            if (is_array($name_of_institute) && isset($name_of_institute[0]) && $name_of_institute[0] != "") {
                                // Prepare an array to hold multiple rows
                                $emp_data = array();

                                // Assuming all arrays have the same length
                                for ($i = 0; $i < count($name_of_institute); $i++) {
                                    $emp_data[] = array(
                                        'arb_id' => $arb_ids,
                                        'name_of_institute' => $name_of_institute[$i],
                                        'date_of_empanelment' => $date_of_empanellment[$i],
                                    );
                                }
                            } else {
                                if ($this->input->post('form_type') == 'ADD') {
                                    echo  json_encode([
                                        'status' => false,
                                        'msg' => 'Empanellment Arbitrator with Other Institutes is required'
                                    ]);
                                    die;
                                }
                            }
                        }


                        if ($this->input->post('form_type') != 'ADD') {
                            // Update
                            $result = $this->empanellment_model->update_professional_information($data, $this->input->post('hidden_arb_id'));

                            // Store other emp data
                            if (isset($emp_data) && count($emp_data) > 0) {
                                $other_emp_data = $this->empanellment_model->insert_arb_other_institute($emp_data);
                            }
                        } else {
                            // Insert
                            $result = $this->empanellment_model->insert_professional_information($data);

                            // Store other emp data
                            if (isset($emp_data) && count($emp_data) > 0) {
                                $other_emp_data = $this->empanellment_model->insert_arb_other_institute($emp_data);
                            }
                        }

                        // Update the steps
                        // Fetch the registration info data
                        $arbRegData = $this->empanellment_model->fetch_arb_registration_information($this->input->post('hidden_arb_id'));

                        if ($arbRegData && $arbRegData['step'] == 1) {
                            $steps = ['step' => 2];
                            $this->empanellment_model->update_form_steps($steps, $this->input->post('hidden_arb_id'));
                        }


                        if ($result) {

                            echo  json_encode([
                                'status' => true,
                                'msg' => 'Professional information addded successfully. Move to next step ( Documents ) to uploads your documents.',
                                'redirect_url' => base_url('efiling/empanellment/documents?id=' . $this->input->post('hidden_arb_id'))
                            ]);
                        } else {
                            echo  json_encode([
                                'status' => false,
                                'msg' => 'Server failed while saving data'
                            ]);
                        }
                    } else {
                        $data = array(
                            'status' => false,
                            'msg' => 'Invalid Captcha.Please Try Again!!!'
                        );
                        echo json_encode($data);
                        die;
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
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function documents()
    {
        $data['arb_id'] = $this->input->get('id');
        // CHECK IF THE ID IS AVAILABLE OR NOT
        if (!$data['arb_id']) {
            return redirect('efiling/empanellment/new-start');
        }

        // FETCH THE DATA USING ID
        $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
        if (!$data['arb_reg_data']) {
            return redirect('efiling/empanellment/new-start');
        }

        if ($data['arb_reg_data']['step'] < 2) {
            return redirect('efiling/empanellment/professional-information?id=' . $data['arb_reg_data']['id']);
        }

        // Arbitrator personal information
        $data['arb_personal_info'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);

        // Arbitrator professional informations
        $data['arb_proffessional_info'] = $this->empanellment_model->fetch_arb_professional_information_using_arb_id($data['arb_id']);

        // Arbitrator documents
        $data['arb_doc_info'] = $this->empanellment_model->fetch_arb_documents_information_using_arb_id($data['arb_id']);

        // Arbitrator other empanellment data
        $data['empanel_with_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

        // Captcha
        $this->load->library('captcha_generator');
        $data['image'] = $this->captcha_generator->create_captcha();

        $data['countries'] = $this->country_model->getAllCountries();
        $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
        $data['salutations'] = $this->salutation_model->getAllSalutations();
        $data['current_step'] = 3;

        $this->load->view('efiling/empanellment/empanellment_form', $data);
    }
    public function store_documents()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'csrf_emp_document_form')) {
                // Validation 
                $this->form_validation->set_rules('hidden_arb_id', 'Arb. Reg. ID', 'required');
                $this->form_validation->set_rules('submitted_place', 'Place ', 'required');

                if ($this->form_validation->run() == TRUE) {

                    // Fetch the data
                    // Arbitrator personal information
                    $arb_personal_info = $this->empanellment_model->get_arb_personal_info($this->input->post('hidden_arb_id'));

                    $data = array(
                        'arb_id' => $this->input->post('hidden_arb_id'),
                        'submitted_date' => date('Y-m-d'),
                        'submitted_place' => $this->input->post('submitted_place'),
                    );

                    // Upload CV file
                    // if ($_FILES['cv_doc']['name'] != '') {
                    //     $this->load->library('fileupload');
                    //     // Upload files ==============
                    //     $cv_doc = $this->fileupload->uploadSingleFile($_FILES['cv_doc'], [
                    //         'raw_file_name' => 'cv_doc',
                    //         'file_name' => 'CV_PORTFOLIO' . time(),
                    //         'file_move_path' => CV_PORTFOLIO,
                    //         'allowed_file_types' => FILE_FORMATS_ALLOWED,
                    //         'allowed_mime_types' => array('application/pdf')
                    //     ]);

                    //     // After getting result of file upload
                    //     if ($cv_doc['status'] == false) {
                    //         $this->db->trans_rollback();

                    //         return $cv_doc;
                    //     } else {
                    //         $data['cv_portfolio'] = $cv_doc['file'];
                    //     }
                    // }

                    // Upload Enroll Certificate file
                    if (isset($_FILES['enrollment_certificate_doc']['name']) && $_FILES['enrollment_certificate_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $enrollment_certificate = $this->fileupload->uploadSingleFile($_FILES['enrollment_certificate_doc'], [
                            'raw_file_name' => 'enrollment_certificate_doc',
                            'file_name' => 'CERT_OF_ENROLLMENT' . time(),
                            'file_move_path' => CERT_OF_ENROLLMENT,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($enrollment_certificate['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Enrolment/Registration Certificate: ' . $enrollment_certificate['msg']
                            ]);
                            die;
                        } else {
                            $data['enrollment_certificate'] = $enrollment_certificate['file'];
                        }
                    }

                    // Upload Practice Certificate file
                    if (isset($_FILES['practice_certificate_doc']['name']) && $_FILES['practice_certificate_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $practice_certificate = $this->fileupload->uploadSingleFile($_FILES['practice_certificate_doc'], [
                            'raw_file_name' => 'practice_certificate_doc',
                            'file_name' => 'CERT_OF_PRACTICE' . time(),
                            'file_move_path' => CERT_OF_PRACTICE,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($practice_certificate['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Certificate of Practice: ' . $practice_certificate['msg']
                            ]);
                            die;
                        } else {
                            $data['practice_certificate'] = $practice_certificate['file'];
                        }
                    }

                    // // Upload Highest Qualification file
                    // if ($_FILES['highest_qualification_doc']['name'] != '') {
                    //     $this->load->library('fileupload');
                    //     // Upload files ==============
                    //     $highest_qaulification = $this->fileupload->uploadSingleFile($_FILES['highest_qualification_doc'], [
                    //         'raw_file_name' => 'highest_qualification_doc',
                    //         'file_name' => 'HIGHEST_QUALIFICATION' . time(),
                    //         'file_move_path' => HIGHEST_QUALIFICATION,
                    //         'allowed_file_types' => FILE_FORMATS_ALLOWED,
                    //         'allowed_mime_types' => array('application/pdf')
                    //     ]);

                    //     // After getting result of file upload
                    //     if ($highest_qaulification['status'] == false) {
                    //         $this->db->trans_rollback();
                    //         return $highest_qaulification;
                    //     } else {
                    //         $data['highest_qaulification'] = $highest_qaulification['file'];
                    //     }
                    // }



                    // Upload Viligance Clearance Certificate file
                    if (isset($_FILES['vigilance_clearance_doc']['name']) && $_FILES['vigilance_clearance_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $vigilance_clearance = $this->fileupload->uploadSingleFile($_FILES['vigilance_clearance_doc'], [
                            'raw_file_name' => 'vigilance_clearance_doc',
                            'file_name' => 'VILIGANCE_CERTIFICATE' . time(),
                            'file_move_path' => VILIGANCE_CLEARANCE,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($vigilance_clearance['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Vigilance Clearance Certificate: ' . $vigilance_clearance['msg']
                            ]);
                            die;
                        } else {
                            $data['vigilance_clearance'] = $vigilance_clearance['file'];
                        }
                    } else {
                        if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['F_JUDGE', 'S_ADV', 'BEU', 'IND_LEGAL_SER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'YES'))) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Vigilance Clearance Certificate is required '
                            ]);
                            die;
                        }
                    }


                    // Upload Conducted Cases Certificate file
                    if (isset($_FILES['conducted_cases_doc']['name']) && $_FILES['conducted_cases_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $conducted_cases = $this->fileupload->uploadSingleFile($_FILES['conducted_cases_doc'], [
                            'raw_file_name' => 'conducted_cases_doc',
                            'file_name' => 'LIST_OF_COND_CASES' . time(),
                            'file_move_path' => LIST_OF_COND_CASES,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($conducted_cases['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Details of orders/judgments: ' . $conducted_cases['msg']
                            ]);
                            die;
                        } else {
                            $data['conducted_cases'] = $conducted_cases['file'];
                        }
                    }

                    // Upload Fisrt income Tax Return Certificate file
                    if (isset($_FILES['itr_first_doc']['name']) && $_FILES['itr_first_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $first_income_tax_returns = $this->fileupload->uploadSingleFile($_FILES['itr_first_doc'], [
                            'raw_file_name' => 'itr_first_doc',
                            'file_name' => 'FISRT_INC_TAX' . time(),
                            'file_move_path' => FIRST_INCOME_TEXT_RETURN,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($first_income_tax_returns['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Copy of ITR - 1: ' . $first_income_tax_returns['msg']
                            ]);
                            die;
                        } else {
                            $data['first_year_itr'] = $first_income_tax_returns['file'];
                        }
                    } else {
                        if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'NO'))) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Copy of  ITR - 1 copy is required '
                            ]);
                            die;
                        }
                    }


                    // Upload Second Income Tax Return Certificate file
                    if (isset($_FILES['itr_second_doc']['name']) && $_FILES['itr_second_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $second_income_tax_returns = $this->fileupload->uploadSingleFile($_FILES['itr_second_doc'], [
                            'raw_file_name' => 'itr_second_doc',
                            'file_name' => 'SECOND_INC_TAX' . time(),
                            'file_move_path' => SECOND_INCOME_TEXT_RETURN,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($second_income_tax_returns['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Copy of ITR - 2: ' . $second_income_tax_returns['msg']
                            ]);
                            die;
                        } else {
                            $data['second_year_itr'] = $second_income_tax_returns['file'];
                        }
                    } else {
                        if (isset($arb_personal_info['empanellment_category']) && (in_array($arb_personal_info['empanellment_category'], ['ADV', 'CA', 'COMP_SECR', 'C_W_ACC', 'OTHER_PROF', 'LEGAL_PROF', 'OTHER']) || ($arb_personal_info['empanellment_category'] == 'ENG' && $arb_personal_info['is_engineer_govt_employee'] == 'NO'))) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Copy of ITR - 2 copy is required '
                            ]);
                            die;
                        }
                    }

                    // Upload Academic Achievements Certificate file
                    if (isset($_FILES['academic_achieve_doc']['name']) && $_FILES['academic_achieve_doc']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $academic_achievements = $this->fileupload->uploadSingleFile($_FILES['academic_achieve_doc'], [
                            'raw_file_name' => 'academic_achieve_doc',
                            'file_name' => 'BEFORE_COND_CASES' . time(),
                            'file_move_path' => ACADEMIC_ACHIEVEMENTS,
                            'allowed_file_types' => FILE_FORMATS_ALLOWED,
                            'allowed_mime_types' => array('application/pdf')
                        ]);

                        // After getting result of file upload
                        if ($academic_achievements['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Copy of articles: ' . $academic_achievements['msg']
                            ]);
                            die;
                        } else {
                            $data['academic_achievements'] = $academic_achievements['file'];
                        }
                    }

                    // Upload Profile Photo Certificate file
                    if (isset($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['name'] != '') {
                        $this->load->library('fileupload');
                        // Upload files ==============
                        $profile_photo = $this->fileupload->uploadSingleFile($_FILES['profile_photo'], [
                            'raw_file_name' => 'profile_photo',
                            'file_name' => 'PROFILE_PHOTO' . time(),
                            'file_move_path' => PROFILE,
                            'allowed_file_types' => INTERN_PROFILE_FORMAT,
                            'allowed_mime_types' => array('image/jpg', 'image/jpeg', 'image/png')
                        ]);

                        // After getting result of file upload
                        if ($profile_photo['status'] == false) {
                            $this->db->trans_rollback();
                            echo json_encode([
                                'status' => false,
                                'msg' => 'Profile photo: ' . $profile_photo['msg']
                            ]);
                            die;
                        } else {
                            $data['profile_photo'] = $profile_photo['file'];
                        }
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode([
                            'status' => false,
                            'msg' => 'Profile photo is required '
                        ]);
                        die;
                    }

                    // final
                    if ($this->input->post('form_type') != 'ADD') {
                        $result = $this->empanellment_model->update_documents_information($data, $this->input->post('hidden_id'));
                    } else {
                        $result = $this->empanellment_model->insert_documents_information($data);
                        $steps = ['step' => 3];
                        $this->empanellment_model->update_form_steps($steps, $this->input->post('hidden_arb_id'));
                    }

                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Documents addded successfully. Preview your details and final submit your application.',
                            'redirect_url' => base_url('efiling/empanellment/final_preview?id=' . $this->input->post('hidden_arb_id'))
                        ]);
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
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
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function final_preview()
    {
        $data['arb_id'] = $this->input->get('id');
        // CHECK IF THE ID IS AVAILABLE OR NOT
        if (!$data['arb_id']) {
            return redirect('efiling/empanellment/new-start');
        }

        // FETCH THE DATA USING ID
        $data['arb_reg_data'] = $this->empanellment_model->fetch_arb_registration_information($data['arb_id']);
        if (!$data['arb_reg_data']) {
            return redirect('efiling/empanellment/new-start');
        }

        $data['arb_info'] = $this->empanellment_model->get_arb_info($data['arb_id']);

        // Arbitrator personal information
        $data['personal_information'] = $this->empanellment_model->get_arb_personal_info($data['arb_id']);
        $data['arb_personal_info'] = $data['personal_information'];

        // Professional information
        $data['professional_information'] = $this->empanellment_model->get_arb_prof_info($data['arb_id']);

        // Document files
        $data['document_files'] = $this->empanellment_model->get_arb_doc_info($data['arb_id']);

        // Other empanelment details
        $data['arb_empanel_other'] = $this->empanellment_model->fetch_arb_other_empanel_info($data['arb_id']);

        $data['step'] = 'DOCUMENTS_INFORMATION';
        $this->load->view('efiling/empanellment/empanellment_final_view', $data);
    }

    public function final_info()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'emp_final_form')) {
                // Validation 
                $this->form_validation->set_rules('hidden_id', 'ID', 'required');

                if ($this->form_validation->run() == TRUE) {
                    $diary_number = $this->input->post('diary_no');
                    $data = array(
                        'is_submitted' => 1,
                    );

                    $result =  $this->empanellment_model->final_submit($data, $this->input->post('hidden_id'));
                    // if ($this->input->post('form_type') != 'ADD') {
                    //     $result = $this->empanellment_model->update_professional_information($data, $this->input->post('hidden_id'));
                    // } else {
                    //     $result = $this->empanellment_model->insert_professional_information($data);
                    //     $steps = ['step' => 2];
                    //     $this->empanellment_model->update_form_steps($steps,$this->input->post('hidden_arb_id'));
                    // }

                    if ($result) {
                        echo  json_encode([
                            'status' => true,
                            'msg' => 'Your empanellment form data has been submitted and your Diary Number is ' . $diary_number,
                            'redirect_url' => base_url('efiling/empanellment/new-start')
                        ]);
                    } else {
                        echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
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
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    // public function get_states_using_country_code()
    // {
    //     echo json_encode($this->getter_model->get($_POST, 'GET_ALL_STATES'));
    // }

    // public function newForm()
    // {
    //     $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
    //     $this->load->library('captcha_generator');
    //     $data['image'] = $this->captcha_generator->create_captcha();

    //     $this->load->view('efiling/empanellment/new_empanelment_form', $data);
    // }

    public function remove_other_institute()
    {
        $id = $this->input->post('id');
        $result = $this->db->where('id', $id)->update('already_empanelled_as_arb_tbl', array('record_status' => 0));
        if ($result) {
            echo  json_encode([
                'status' => true,
                'msg' => 'Record remove'
            ]);
        } else {
            echo  json_encode([
                'status' => false,
                'msg' => 'Record remove'
            ]);
        }
    }
}
