<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Work_status_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'getter_model', 'notification_model', 'work_status_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');
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
            'CASE_MANAGER' => array('pending_work_status', 'completed_work_status'),
            'DEPUTY_COUNSEL' => array('pending_work_status', 'completed_work_status'),
            'COORDINATOR' => array('pending_work_status', 'completed_work_status'),
            'ACCOUNTS' => array('pending_work_status', 'completed_work_status'),
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
     * Function to show the pending work status of the user based on the noting
     */
    public function pending_work_status()
    {
        $sidebar['menu_item'] = 'Pending';
        $sidebar['menu_group'] = 'Work Status';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Pending Work Status';

        if ($page_status != 0) {
            $data['works_list'] = $this->work_status_model->get_pending_works($this->session->userdata('user_code'));
            $this->load->view('work_status/pending', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to show the completed work status of the user based on the noting
     */
    public function completed_work_status()
    {
        $sidebar['menu_item'] = 'Completed';
        $sidebar['menu_group'] = 'Work Status';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Completed Work Status';

        if ($page_status != 0) {
            $data['works_list'] = $this->work_status_model->get_completed_works($this->session->userdata('user_code'));

            // echo '<pre>';
            // print_r($data['works_list']);
            // die;

            $this->load->view('work_status/completed', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }
}
