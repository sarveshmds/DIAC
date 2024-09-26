<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Arbitrator_setup_controller extends CI_Controller
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
            'DIAC' => array('all_arbitrator', 'add_arbitrator', 'get_datatable_data_appr', 'get_datatable_data_unappr', 'update_arbitrator', 'delete'),
            'CASE_FILER' => array('all_arbitrator', 'add_arbitrator', 'get_datatable_data_appr', 'get_datatable_data_unappr', 'update_arbitrator', 'delete'),
            'DEO' => array('all_arbitrator', 'add_arbitrator', 'get_datatable_data_appr', 'get_datatable_data_unappr', 'update_arbitrator', 'delete'),
            'DEPUTY_COUNSEL' => array('all_arbitrator', 'add_arbitrator', 'get_datatable_data_appr', 'get_datatable_data_unappr', 'update_arbitrator', 'delete'),
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

    public function all_arbitrator()
    {
        $sidebar['menu_item'] = 'Arbitrator Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
        $data['approved_arb'] = $this->getter_model->get('', 'GET_APPROVED_ARB');
        $data['unapproved_arb'] = $this->getter_model->get('', 'GET_UNAPPROVED_ARB');
        $data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
        // print_r($data['countries']);
        // die;

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Empanelled Arbitrator Setup';

            $this->load->view('master_setup/all_arbitrators', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add_arbitrator()
    {

        $this->form_validation->set_rules('name_of_arbitrator', 'Name of arbitrator', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean');
        $this->form_validation->set_rules('contact_no', 'Contact number', 'xss_clean|numeric');
        $this->form_validation->set_rules('category', 'Category', 'required|xss_clean');
        // $this->form_validation->set_rules('wt_on_panel', 'Whether on panel', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                // $check = array('email' => $this->input->post('email'), 'contact_no' => $this->input->post('contact_no'));

                // $check_email = $this->common_model->check_arbitrator_email($check);
                // if ($check_email) {
                //     $this->db->trans_rollback();
                //     $dbstatus = false;
                //     $dbmessage = "Email address exists";
                //     echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                //     die;
                // }

                // $check_contact = $this->common_model->check_arbitrator_contact($check);

                // if ($check_contact) {
                //     $this->db->trans_rollback();
                //     $dbstatus = false;
                //     $dbmessage = "Contact number exists";
                //     echo json_encode(array('status' => $dbstatus, 'msg' => $dbmessage));
                //     die;
                // }

                $data = array(
                    'code' => generateCode(),
                    'name_of_arbitrator' => $this->input->post('name_of_arbitrator'),
                    'email' => $this->input->post('email'),
                    'contact_no' => $this->input->post('contact_no'),
                    'category' => $this->input->post('category'),
                    'whether_on_panel' => $this->input->post('wt_on_panel'),
                    'dob' => ($this->input->post('dob')) ? formatDate($this->input->post('dob')) : null,
                    'perm_address_1' => $this->input->post('permanent_address_1'),
                    'perm_address_2' => $this->input->post('permanent_address_2'),
                    'perm_country' => $this->input->post('permanent_country'),
                    'perm_state' => $this->input->post('permanent_state'),
                    'perm_pincode' => $this->input->post('permanent_pincode'),
                    'corr_address_1' => $this->input->post('corr_address_1'),
                    'corr_address_2' => $this->input->post('corr_address_2'),
                    'corr_country' => $this->input->post('corr_country'),
                    'corr_state' => $this->input->post('corr_state'),
                    'corr_pincode' => $this->input->post('corr_pincode'),
                    'record_status' => 1,
                    'created_by' => $this->user_code,
                    'created_at' => currentDateTimeStamp()
                );

                // Insert panel category
                $result = $this->db->insert('master_arbitrators_tbl', $data);

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

    public function update_arbitrator()
    {
        $this->form_validation->set_rules('crn_hidden_id', 'ID', 'required|xss_clean');
        $this->form_validation->set_rules('name_of_arbitrator', 'Name of arbitrator', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email');
        $this->form_validation->set_rules('contact_no', 'Contact number', 'xss_clean|max_length[20]');
        $this->form_validation->set_rules('category', 'Category', 'required|xss_clean');
        // $this->form_validation->set_rules('wt_on_panel', 'Whether on panel', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
        // $this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
        // $this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                $data = array(
                    'name_of_arbitrator' => $this->input->post('name_of_arbitrator'),
                    'email' => $this->input->post('email'),
                    'contact_no' => $this->input->post('contact_no'),
                    'category' => $this->input->post('category'),
                    'dob' => ($this->input->post('dob')) ? formatDate($this->input->post('dob')) : null,
                    'whether_on_panel' => $this->input->post('wt_on_panel'),
                    'perm_address_1' => $this->input->post('permanent_address_1'),
                    'perm_address_2' => $this->input->post('permanent_address_2'),
                    'perm_country' => $this->input->post('permanent_country'),
                    'perm_state' => $this->input->post('permanent_state'),
                    'perm_pincode' => $this->input->post('permanent_pincode'),
                    'corr_address_1' => $this->input->post('corr_address_1'),
                    'corr_address_2' => $this->input->post('corr_address_2'),
                    'corr_country' => $this->input->post('corr_country'),
                    'corr_state' => $this->input->post('corr_state'),
                    'corr_pincode' => $this->input->post('corr_pincode'),
                    'updated_by' => $this->user_code,
                    'updated_at' => currentDateTimeStamp()
                );

                $this->db->where('id', $this->input->post('crn_hidden_id'));
                $result = $this->db->update('master_arbitrators_tbl', $data);

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
        $this->form_validation->set_rules('id', 'ID', 'required|xss_clean');

        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            try {
                $id = $this->input->post('id');
                if (isset($id) && !empty($id)) {
                    $data = array(
                        'record_status' => 0,
                        'updated_by' => $this->user_code,
                        'updated_at' => currentDateTimeStamp()
                    );

                    // Insert panel category
                    $this->db->where('id', $id);
                    $result = $this->db->update('master_arbitrators_tbl', $data);

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
    
    public function get_datatable_data_appr()
{
    // Selecting required fields and aggregating case details group
    $this->db->select("amt.id, amt.name_of_arbitrator, amt.email, amt.contact_no, amt.whether_on_panel,
                    pct.category_name as category, amt.category as category_code,
                    amt.perm_address_1, amt.perm_address_2, amt.perm_country,
                    amt.perm_state, amt.perm_pincode, amt.corr_address_1,
                    amt.corr_address_2, amt.corr_country, amt.corr_state,
                    amt.corr_pincode, DATE_FORMAT(amt.dob, '%d-%m-%Y') as dob,
                    GROUP_CONCAT(CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year)
                    ORDER BY CAST(SUBSTRING(cdt.case_no_year, -2) AS UNSIGNED) ASC, cdt.case_no ASC SEPARATOR ', ') as case_no_desc,
                    GROUP_CONCAT(cdt.case_status ORDER BY CAST(SUBSTRING(cdt.case_no_year, -2) AS UNSIGNED) ASC, cdt.case_no ASC SEPARATOR ', ') as case_status");
    $this->db->from('master_arbitrators_tbl as amt');
    $this->db->join('panel_category_tbl as pct', 'pct.code = amt.category', 'left');
    $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.arbitrator_code = amt.code', 'left');
    $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = cat.case_no', 'left');
    $this->db->where('amt.record_status', 1);
    $this->db->where('amt.approved', 1); 

    // Filter by arbitrator name if provided
    if ($this->input->post('f_arbitrator_name')) {
        $f_arbitrator_name = $this->input->post('f_arbitrator_name');
        $this->db->like('amt.name_of_arbitrator', $f_arbitrator_name);
    }

    // Filter by whether on panel
    if ($this->input->post('f_whether_on_panel')) {
        $f_whether_on_panel = $this->input->post('f_whether_on_panel');
        $this->db->like('amt.whether_on_panel', $f_whether_on_panel);
    }

    // Group by arbitrator ID to avoid repetition
    $this->db->group_by('amt.id');

    // Clone the db instance before limit
    $tempDb = clone $this->db;

    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    // Execute the query to get data
    $query = $this->db->get();
    $fetch_data = $query->result();

    // Get filtered count (DISTINCT arbitrators)
    $this->db->select("COUNT(DISTINCT amt.id) as recordsFiltered");
    $this->db->from('master_arbitrators_tbl as amt');
    $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.arbitrator_code = amt.code', 'left');
    $this->db->where('amt.record_status', 1);
    $this->db->where('amt.approved', 1);
    if ($this->input->post('f_arbitrator_name')) {
        $this->db->like('amt.name_of_arbitrator', $this->input->post('f_arbitrator_name'));
    }
    if ($this->input->post('f_whether_on_panel')) {
        $this->db->like('amt.whether_on_panel', $this->input->post('f_whether_on_panel'));
    }
    $recordsFilteredQuery = $this->db->get();
    $recordsFiltered = $recordsFilteredQuery->row()->recordsFiltered;

    // Get total distinct arbitrators count
    $this->db->select("COUNT(DISTINCT amt.id) as recordsTotal");
    $this->db->from('master_arbitrators_tbl as amt');
    $this->db->where('amt.record_status', 1);
    $this->db->where('amt.approved', 1);
    $recordsTotalQuery = $this->db->get();
    $recordsTotal = $recordsTotalQuery->row()->recordsTotal;

    // Output the data
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

        $this->db->select("amt.id,amt.name_of_arbitrator,amt.email,amt.contact_no,amt.whether_on_panel,pct.category_name as category, amt.category as category_code,amt.perm_address_1,amt.perm_address_2,amt.perm_country,amt.perm_state,amt.perm_pincode,amt.corr_address_1,amt.corr_address_2,amt.corr_country,amt.corr_state,amt.corr_pincode");
        $this->db->from('master_arbitrators_tbl as amt');
        $this->db->join('panel_category_tbl as pct', 'pct.code = amt.category', 'left');
        $this->db->where('record_status', 1);
        $this->db->where('approved', 0);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('master_arbitrators_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
    }
}

/* End of file arbitrator_setup_controller.php */
/* Location: ./application/controllers/arbitrator_setup_controller.php */