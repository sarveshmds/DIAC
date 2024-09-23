<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_ajax_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());

        # models
        $this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'refferal_request_model', 'master_setup/arbitrator_setup_model', 'panel_category_model', 'master_setup/courts_setup_model', 'master_setup/country_model', 'arbitral_tribunal_model', 'master_setup/case_types_model', 'claimant_respondent_model', 'reports_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
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
            'DIAC' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'CASE_FILER' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'DEPUTY_COUNSEL' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'CASE_MANAGER' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'ACCOUNTS' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'COORDINATOR' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
            'HEAD_COORDINATOR' => array('get_arbitrator_case_count_report_datatable', 'get_arbitrator_case_status_count_report_datatable'),
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

    /**
     * Function to get the arbitrator case count report datatable
     */
    public function get_arbitrator_case_count_report_datatable()
    {
        $limit = "";
        if ($_POST['length'] != -1) {
            $length = $_POST['length'];
            $offset = $_POST['start'];
            $limit = "LIMIT $length OFFSET $offset";
        }

        $sql = "SELECT a.name_of_arbitrator, a.email,a.contact_no, pct.category_name, count(b.id) as case_count FROM  `master_arbitrators_tbl` a 
        left join `cs_arbitral_tribunal_tbl` b on a.code=b.arbitrator_code
        left join `panel_category_tbl` pct on pct.code=a.category 
        GROUP BY a.code
        ORDER BY case_count DESC
        $limit
        ";

        // Get the results
        $query = $this->db->query($sql);
        $fetch_data = $query->result();

        // For Pagination
        $sql = "SELECT a.name_of_arbitrator, a.email,a.contact_no, pct.category_name, count(b.id) as case_count FROM  `master_arbitrators_tbl` a 
        left join `cs_arbitral_tribunal_tbl` b on a.code=b.arbitrator_code
        left join `panel_category_tbl` pct on pct.code=a.category 
        GROUP BY a.code
        ORDER BY case_count DESC
        ";

        // Filter records
        $recordsFiltered = count($this->db->query($sql)->result_array());

        // Records total
        $recordsTotal = count($this->db->query($sql)->result_array());

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );

        echo json_encode($output);
        die;
    }

    /**
     * Function to get the arbitrator case status count report datatable
     */
    public function get_arbitrator_case_status_count_report_datatable()
    {
        $limit = "";
        if ($_POST['length'] != -1) {
            $length = $_POST['length'];
            $offset = $_POST['start'];
            $limit = "LIMIT $length OFFSET $offset";
        }

        $where = "";
        if ($this->input->post('arbitrator_name')) {
            $where .= "WHERE a.name_of_arbitrator LIKE '%" . $this->input->post('arbitrator_name') . "%'";
        }

        $sql = "SELECT a.name_of_arbitrator,a.email,a.contact_no, pct.category_name, count(b.id) as case_count, c.case_status from  `master_arbitrators_tbl` a 
        LEFT JOIN `cs_arbitral_tribunal_tbl` b ON  a.code=b.arbitrator_code 
        LEFT JOIN cs_case_details_tbl c ON b.case_no=c.slug
        left join `panel_category_tbl` pct on pct.code=a.category 
        $where
        GROUP BY a.code,c.case_status
        ORDER BY a.name_of_arbitrator DESC
        $limit
        ";

        // Get the results
        $query = $this->db->query($sql);
        $fetch_data = $query->result();

        // For Pagination
        $sql = "SELECT a.name_of_arbitrator,a.email,a.contact_no, pct.category_name, count(b.id) as case_count, c.case_status from  `master_arbitrators_tbl` a 
        LEFT JOIN `cs_arbitral_tribunal_tbl` b ON  a.code=b.arbitrator_code 
        LEFT JOIN cs_case_details_tbl c ON b.case_no=c.slug
        left join `panel_category_tbl` pct on pct.code=a.category 
        $where
        GROUP BY a.code,c.case_status
        ORDER BY a.name_of_arbitrator DESC
        ";

        // Filter records
        $recordsFiltered = count($this->db->query($sql)->result_array());

        // Records total
        $recordsTotal = count($this->db->query($sql)->result_array());

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );

        echo json_encode($output);
        die;
    }
}
