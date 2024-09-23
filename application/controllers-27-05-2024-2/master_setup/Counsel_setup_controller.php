<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Counsel_setup_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'common_model', 'category_model', 'getter_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('index', 'add_counsel', 'get_datatable_data', 'update_counsel', 'delete', 'add_counsel_page', 'add', 'get_datatable_data_unappr', 'edit_counsel', 'update_counsel'),
            'CASE_FILER' => array('index', 'add_counsel', 'get_datatable_data', 'update_counsel', 'delete', 'add_counsel_page', 'add', 'get_datatable_data_unappr', 'edit_counsel', 'update_counsel'),
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

    public function index()
    {
        $sidebar['menu_item'] = 'Counsel Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Counsel Setup';
            $data['approved_counsel'] = $this->getter_model->get('', 'get_approved_counsel');
            $data['unapproved_counsel'] = $this->getter_model->get('', 'get_unapproved_counsel');

            $this->load->view('master_setup/counsel/index', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add_counsel_page()
    {
        $sidebar['menu_item'] = 'Counsel Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Add Counsel Master';
            $data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');

            $this->load->view('master_setup/counsel/add_counsel_master', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add()
    {

        $this->form_validation->set_rules('name', 'Name of counsel', 'required|xss_clean');
        $this->form_validation->set_rules('enrollment_no', 'Enrollment Number', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone number', 'required|xss_clean|numeric');
        $this->form_validation->set_rules('perm_add_1', 'Permanent Address 1', 'required|xss_clean');
        $this->form_validation->set_rules('perm_country', 'Permanent Country', 'required|xss_clean');
        $this->form_validation->set_rules('perm_state', 'Permanent State', 'required|xss_clean');
        $this->form_validation->set_rules('perm_pincode', 'Permanent Pincode', 'required|xss_clean');
        $this->form_validation->set_rules('corr_add_1', 'Correspondence Address 1', 'required|xss_clean');
        $this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
        $this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
        $this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                $check = array('enrollment_no' => $this->input->post('enrollment_no'), 'email' => $this->input->post('email'), 'phone_number' => $this->input->post('phone_number'));

                $check_enroll = $this->common_model->check_counsel_enroll($check);
                if ($check_enroll) {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Enrollment number allready registered";
                    echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                    die;
                }

                $check_email = $this->common_model->check_counsel_email($check);
                if ($check_email) {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Email address allready registered";
                    echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                    die;
                }

                $check_contact = $this->common_model->check_counsel_contact($check);

                if ($check_contact) {
                    $this->db->trans_rollback();
                    $dbstatus = false;
                    $dbmessage = "Phone number allready registered";
                    echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                    die;
                }

                $data = array(
                    'code' => generateCode(),
                    'name' => $this->input->post('name'),
                    'enrollment_no' => $this->input->post('enrollment_no'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'approved' => $this->input->post('approved'),
                    'perm_address_1' => $this->input->post('perm_add_1'),
                    'perm_address_2' => $this->input->post('perm_add_2'),
                    'perm_country' => $this->input->post('perm_country'),
                    'perm_state' => $this->input->post('perm_state'),
                    'perm_pincode' => $this->input->post('perm_pincode'),
                    'corr_address_1' => $this->input->post('corr_add_1'),
                    'corr_address_2' => $this->input->post('corr_add_2'),
                    'corr_country' => $this->input->post('corr_country'),
                    'corr_state' => $this->input->post('corr_state'),
                    'corr_pincode' => $this->input->post('corr_pincode'),
                    'record_status' => 1,
                    'created_by' => $this->user_code,
                    'created_at' => currentDateTimeStamp(),
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                // Insert panel category
                $result = $this->db->insert('master_counsels_tbl', $data);

                if ($result) {

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

    public function get_datatable_data()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("mct.id,mct.name,mct.email,mct.phone_number,mct.enrollment_no,mct.code,mct.perm_address_1,mct.perm_address_2,ct.name as perm_country_name,st.name as perm_state_name,mct.perm_pincode,mct.corr_address_1,mct.corr_address_2,ct_2.name as corr_country_name,st_2.name as corr_state_name,mct.corr_pincode");
        $this->db->from('master_counsels_tbl as mct');
        $this->db->join('countries as ct', 'ct.iso2 = mct.perm_country', 'left');
        $this->db->join('states as st', 'st.id = mct.perm_state', 'left');
        $this->db->join('countries as ct_2', 'ct_2.iso2 = mct.corr_country', 'left');
        $this->db->join('states as st_2', 'st_2.id = mct.corr_state', 'left');
        $this->db->where('mct.record_status', 1);

        // if ($this->input->post('approved') && $this->input->post('approved') == 'APPROVED') {
        //     $this->db->where('mct.approved', 1);
        // } else {
        //     $this->db->where('mct.approved', 1);
        // }

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('master_counsels_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    public function get_datatable_data_unappr()
    {
        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("mct.id,mct.name,mct.email,mct.phone_number,mct.enrollment_no,mct.code,mct.perm_address_1,mct.perm_address_2,ct.name as perm_country_name,st.name as perm_state_name,mct.perm_pincode,mct.corr_address_1,mct.corr_address_2,ct_2.name as corr_country_name,st_2.name as corr_state_name,mct.corr_pincode");
        $this->db->from('master_counsels_tbl as mct');
        $this->db->join('countries as ct', 'ct.iso2 = mct.perm_country', 'left');
        $this->db->join('states as st', 'st.id = mct.perm_state', 'left');
        $this->db->join('countries as ct_2', 'ct_2.iso2 = mct.corr_country', 'left');
        $this->db->join('states as st_2', 'st_2.id = mct.corr_state', 'left');
        $this->db->where('mct.record_status', 1);

        // if ($this->input->post('approved') && $this->input->post('approved') == 'APPROVED') {
        //     $this->db->where('mct.approved', 0);
        // } else {
        //     $this->db->where('mct.approved', 0);
        // }

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('master_counsels_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }

    public function edit_counsel($code)
    {
        $data['edit_form'] = true;
        $sidebar['menu_item'] = 'Counsel Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {
            $counsel_details = $this->common_model->get_counsel_details($code);
            $get_perm_state = $this->common_model->get_states($counsel_details['perm_country']);
            $get_corr_state = $this->common_model->get_states($counsel_details['corr_country']);

            $data['counsel_details'] = $counsel_details;
            $data['get_perm_state'] = $get_perm_state;
            $data['get_corr_state'] = $get_corr_state;

            $data['page_title'] = 'Edit Counsel Master';
            $data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');


            $this->load->view('master_setup/counsel/add_counsel_master', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function update_counsel()
    {
        $this->form_validation->set_rules('crn_hidden_id', 'ID', 'required|xss_clean');
        $this->form_validation->set_rules('name', 'Name of counsel', 'required|xss_clean');
        $this->form_validation->set_rules('enrollment_no', 'Enrollment Number', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone number', 'required|xss_clean|numeric');
        $this->form_validation->set_rules('perm_add_1', 'Permanent Address 1', 'required|xss_clean');
        $this->form_validation->set_rules('perm_country', 'Permanent Country', 'required|xss_clean');
        $this->form_validation->set_rules('perm_state', 'Permanent State', 'required|xss_clean');
        $this->form_validation->set_rules('perm_pincode', 'Permanent Pincode', 'required|xss_clean');
        $this->form_validation->set_rules('corr_add_1', 'Correspondence Address 1', 'required|xss_clean');
        $this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
        $this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
        $this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                if ($this->input->post('enrollment_no')) {
                    $this->db->select('enrollment_no');
                    $this->db->from('master_counsels_tbl');
                    $this->db->where('id !=', $this->input->post('crn_hidden_id'));
                    $this->db->where('enrollment_no', $this->input->post('enrollment_no'));
                    $enroll_check = $this->db->get()->num_rows();
                    if ($enroll_check > 0) {
                        $this->db->trans_rollback();
                        $dbstatus = false;
                        $dbmessage = "Enrollment number is allready registered";
                        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                        die;
                    }
                }
                if ($this->input->post('email')) {
                    $this->db->select('email');
                    $this->db->from('master_counsels_tbl');
                    $this->db->where('id !=', $this->input->post('crn_hidden_id'));
                    $this->db->where('email', $this->input->post('email'));
                    $enroll_check = $this->db->get()->num_rows();
                    if ($enroll_check > 0) {
                        $this->db->trans_rollback();
                        $dbstatus = false;
                        $dbmessage = "Email address is allready registered";
                        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                        die;
                    }
                }
                if ($this->input->post('phone_number')) {
                    $this->db->select('phone_number');
                    $this->db->from('master_counsels_tbl');
                    $this->db->where('id !=', $this->input->post('crn_hidden_id'));
                    $this->db->where('phone_number', $this->input->post('phone_number'));
                    $enroll_check = $this->db->get()->num_rows();
                    if ($enroll_check > 0) {
                        $this->db->trans_rollback();
                        $dbstatus = false;
                        $dbmessage = "Phone number is allready registered";
                        echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                        die;
                    }
                }

                $data = array(
                    'name' => $this->input->post('name'),
                    'enrollment_no' => $this->input->post('enrollment_no'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'approved' => $this->input->post('approved'),
                    'perm_address_1' => $this->input->post('perm_add_1'),
                    'perm_address_2' => $this->input->post('perm_add_2'),
                    'perm_country' => $this->input->post('perm_country'),
                    'perm_state' => $this->input->post('perm_state'),
                    'perm_pincode' => $this->input->post('perm_pincode'),
                    'corr_address_1' => $this->input->post('corr_add_1'),
                    'corr_address_2' => $this->input->post('corr_add_2'),
                    'corr_country' => $this->input->post('corr_country'),
                    'corr_state' => $this->input->post('corr_state'),
                    'corr_pincode' => $this->input->post('corr_pincode'),
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                $this->db->where('id', $this->input->post('crn_hidden_id'));
                $result = $this->db->update('master_counsels_tbl', $data);

                if ($result) {
                    $this->db->trans_commit();
                    $dbstatus = true;
                    $dbmessage = "Record Updated successfully";
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

    public function delete()
    {
        $this->form_validation->set_rules('code', 'ID', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                $code = $this->input->post('code');
                if (isset($code) && !empty($code)) {
                    $data = array(
                        'record_status' => 0,
                        'updated_by' => $this->user_code,
                        'updated_at' => currentDateTimeStamp()
                    );

                    // Insert panel category
                    $this->db->where('code', $code);
                    $result = $this->db->update('master_counsels_tbl', $data);

                    if ($result) {

                        $this->db->trans_commit();
                        $dbstatus = true;
                        $dbmessage = "Record Delete successfully";
                    } else {
                        $this->db->trans_rollback();
                        $dbstatus = false;
                        $dbmessage = "Something went wrong.";
                    }
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
}
