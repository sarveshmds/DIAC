
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Efiling_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'getter_model', 'common_model', 'new_reference_model', 'efiling_model']);
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
            'ADVOCATE' => array('advocate_dashboard', 'filed_cases', 'file_new_case', 'my_profile', 'store_new_reference'),
            'PARTY' => array('party_dashboard', 'filed_cases', 'file_new_case', 'my_profile', 'store_new_reference'),
            'ARBITRATOR' => array('arbitrator_dashboard', 'my_profile'),
            'ADVOCATE_ARBITRATOR' => array('advocateandarbitrator_dashboard', 'filed_cases', 'file_new_case', 'my_profile'),
        );

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('efiling/logout');
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

    // public function dashboard()
    // {
    //     $sidebar['menu_item'] = 'Dashboard';
    //     $sidebar['menu_group'] = 'Dashboard';
    //     $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
    //     $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

    //     if ($page_status != 0) {
    //         $data['page_title'] = 'Dashboard';
    //         $this->load->view('templates/efiling/efiling-header', $data);
    //         $this->load->view('efiling/dashboard');
    //         $this->load->view('templates/efiling/efiling-footer');
    //     } else {
    //         $this->load->view('templates/page_maintenance');
    //     }
    // }
    // ///// all users dashboards//// 445
    public function advocate_dashboard()
    {
        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Dashboard';
            // Get all data
            $data['dashboard_data'] = $this->efiling_model->get_dashboard_data();

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/dashboard/advocate_dashboard');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
    //  8989898989
    public function party_dashboard()
    {
        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Party Dashboard';

            // Get all data
            $data['dashboard_data'] = $this->efiling_model->get_dashboard_data();

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/dashboard/party_dashboard');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
    // 9898989898
    public function arbitrator_dashboard()
    {
        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Arbitrator Dashboard';
            // Get all data
            $data['dashboard_data'] = $this->efiling_model->get_dashboard_data();

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/dashboard/arbitrator_dashboard');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function advocateandarbitrator_dashboard()
    {
        $data['menu_item'] = 'Dashboard';
        $data['menu_group'] = 'Dashboard';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Advocate and Arbitrator Dashboard';
            // Get all data
            $data['dashboard_data'] = $this->efiling_model->get_dashboard_data();

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/dashboard/advocateandarbitrator_dashboard');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    // public function filed_cases()
    // {
    //     $data['menu_item'] = 'New References';
    //     $data['menu_group'] = 'New References';
    //     $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

    //     $page_status = page_status($data['sidebar'], $data['menu_item']);

    //     if ($page_status != 0) {
    //         $data['page_title'] = 'New References';
    //         $this->load->view('templates/efiling/efiling-header', $data);
    //         $this->load->view('efiling/filed_cases');
    //         $this->load->view('templates/efiling/efiling-footer');
    //     } else {
    //         $this->load->view('templates/page_maintenance');
    //     }
    // }

    // public function file_new_case()
    // {
    //     $data['menu_item'] = 'New References';
    //     $data['menu_group'] = 'New References';
    //     $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

    //     $page_status = page_status($data['sidebar'], $data['menu_item']);

    //     if ($page_status != 0) {

    //         $data['page_title'] = 'File New Reference';
    //         $this->load->view('templates/efiling/efiling-header', $data);
    //         $this->load->view('efiling/file_new_case');
    //         $this->load->view('templates/efiling/efiling-footer');
    //     } else {
    //         $this->load->view('templates/page_maintenance');
    //     }
    // }

    // public function store_new_reference()
    // {
    //     $inputCsrfToken = $_POST['csrf_frm_token'];
    //     if ($inputCsrfToken != '') {
    //         if (checkToken($inputCsrfToken, 'frm')) {

    //             // Validation Code ===============
    //             $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required');
    //             $this->form_validation->set_rules('case_type', 'Case Type', 'required');
    //             $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required');
    //             $this->form_validation->set_rules('arb_total_fees', 'Arbitrator Fees', 'required');
    //             $this->form_validation->set_rules('arb_your_share_fees', 'Arbitrator Fees (Your share)', 'required');
    //             $this->form_validation->set_rules('adm_charges', 'Administrative Charges', 'required');
    //             $this->form_validation->set_rules('your_payable_share', 'Your payable share', 'required');

    //             // Check if the validation passed or not
    //             if ($this->form_validation->run() == TRUE) {

    //                 // Check the row count from table
    //                 $rowCount = $this->document_model->checkCount();
    //                 $diaryNumber = generateDiaryNumber($rowCount);

    //                 $data_arr = array(
    //                     'diary_number' => $diaryNumber,
    //                     'type_of_arbitration' => $this->input->post('type_of_arbitration'),
    //                     'case_type' => $this->input->post('case_type'),
    //                     'claim_amount' => $this->input->post('claim_amount'),
    //                     'arb_total_fees' => $this->input->post('arb_total_fees'),
    //                     'arb_your_share_fees' => $this->input->post('arb_your_share_fees'),
    //                     'adm_charges' => $this->input->post('adm_charges'),
    //                     'your_payable_share' => $this->input->post('your_payable_share'),
    //                     'document' => '',
    //                     'remarks' => $this->input->post('remarks'),
    //                     'application_status' => 2, // 2 for pending
    //                     'created_by' => $this->session->userdata('user_code'),
    //                     'created_on' => currentDateTimeStamp(),
    //                     'updated_by' => $this->session->userdata('user_code'),
    //                     'updated_on' => currentDateTimeStamp(),
    //                 );

    //                 // Upload the file ==============================================
    //                 if ($_FILES['upload_document']['name'] != '') {
    //                     $this->load->library('fileupload');
    //                     // Upload files ==============
    //                     $upload_document_result = $this->fileupload->uploadSingleFile($_FILES['upload_document'], [
    //                         'raw_file_name' => 'upload_document',
    //                         'file_name' => 'NEW_REFERENCE_' . time(),
    //                         'file_move_path' => EFILING_NEW_REFERENCE_UPLOADS_FOLDER,
    //                         'allowed_file_types' => FILE_FORMATS_ALLOWED,
    //                         'allowed_mime_types' => array('application/pdf')
    //                     ]);

    //                     // After getting result of file upload
    //                     if ($upload_document_result['status'] == false) {
    //                         $this->db->trans_rollback();
    //                         return $upload_document_result;
    //                     } else {
    //                         $data_arr['document'] = $upload_document_result['file'];
    //                     }
    //                 } else {
    //                     echo  json_encode([
    //                         'status' => false,
    //                         'msg' => 'Please upload document before submitting form.'
    //                     ]);
    //                     return;
    //                 }

    //                 $result = $this->new_reference_model->insert($data_arr);
    //                 if ($result) {
    //                     echo  json_encode([
    //                         'status' => true,
    //                         'redirect_link' => base_url('efiling/new-reference/check-verify')
    //                     ]);
    //                 } else {
    //                     echo  json_encode([
    //                         'status' => false,
    //                         'msg' => 'Server failed while saving data'
    //                     ]);
    //                 }
    //             } else {
    //                 echo  json_encode([
    //                     'status' => 'validation_error',
    //                     'msg' => validation_errors()
    //                 ]);
    //             }
    //         } else {
    //             $data = array(
    //                 'status' => false,
    //                 'msg' => 'Invalid Security Token'
    //             );
    //             echo json_encode($data);
    //         }
    //     } else {
    //         $data = array(
    //             'status' => false,
    //             'msg' => 'Empty Security Token'
    //         );
    //         echo json_encode($data);
    //     }
    // }

    public function my_profile()
    {
        $data['menu_item'] = 'My Profile';
        $data['menu_group'] = 'My Profile';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['details'] = $this->common_model->getDashboardDetails();
            $data['details2'] = $this->common_model->getDashboardDetails2();
            $data['page_title'] = 'My Profile';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/my_profile', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
}
