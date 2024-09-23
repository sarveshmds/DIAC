<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_controller extends CI_Controller
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
            'DIAC' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'CASE_FILER' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'DEPUTY_COUNSEL' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'CASE_MANAGER' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'ACCOUNTS' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'COORDINATOR' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
            'HEAD_COORDINATOR' => array('index', 'arbitrator_case_count_report', 'arbitrator_case_status_count_report'),
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
        $sidebar['menu_item'] = 'Reports';
        $sidebar['menu_group'] = 'Reports';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Reports';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            $this->load->view('reports/index', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to get the arbitrator case count report
     */
    public function arbitrator_case_count_report()
    {
        $sidebar['menu_item'] = 'Reports';
        $sidebar['menu_group'] = 'Reports';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Arbitrator Case Count Report';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            $this->load->view('reports/arbitrator_case_count_report', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to get the arbitrator case status count report
     */
    public function arbitrator_case_status_count_report()
    {
        $sidebar['menu_item'] = 'Reports';
        $sidebar['menu_group'] = 'Reports';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Arbitrator Case Status Count Report';

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);

        if ($page_status != 0) {
            $this->load->view('reports/arbitrator_case_status_count_report', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }
}
