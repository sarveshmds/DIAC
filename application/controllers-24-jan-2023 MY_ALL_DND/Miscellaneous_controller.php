<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Miscellaneous_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        header("Access-Control-Allow-Origin: domain.com");

        # models
        $this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'miscellaneous_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
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
            'SUPERADMIN' => array('data_logs'),
            'ADMIN' => array('all_miscellaneous', 'add_miscellaneous'),
            'DIAC' => array('all_miscellaneous', 'add_miscellaneous'),
            'CASE_FILER' => array('all_miscellaneous', 'add_miscellaneous', 'miscellaneous_reply'),
            'COORDINATOR' => array('all_miscellaneous', 'miscellaneous_reply'),
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

    public function all_miscellaneous()
    {
        $sidebar['menu_item'] = 'Referrals/Requests';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Referrals/Requests';

        if ($page_status != 0) {
            // Update the notings notifications
            $this->notification_model->post([
                'type_table' => 'miscellaneous_noting_tbl',
            ], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');

            $this->load->view('diac-admin/miscellaneous', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add_miscellaneous()
    {
        $sidebar['menu_item'] = 'Referrals/Requests';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'New Referrals/Requests';

        if ($page_status != 0) {

            if ($this->input->get('id')) {
                $data['id'] = $this->input->get('id');
                $data['miscellaneous'] = $this->miscellaneous_model->get(['id' => $data['id']], 'GET_MISCELLANEOUS_DATA_USING_ID');
                if (count($data['miscellaneous']) < 1) {
                    return redirect('page-not-found');
                }
                $data['miscellaneous_marked_to'] = $this->miscellaneous_model->get(['id' => $data['id']], 'GET_MISCELLANEOUS_MARKED_TO_USING_ID');
            }

            $data['users'] = $this->case_model->get('', 'ALL_DIAC_COORDINATORS');
            $this->load->view('diac-admin/add-miscellaneous', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function miscellaneous_reply()
    {
        $sidebar['menu_item'] = 'Referrals/Requests';
        $sidebar['menu_group'] = 'Referrals/Requests';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
        $data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
        $data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
        $data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Referrals/Requests Reply';

        if ($page_status != 0) {

            if (!$this->input->get('miscellaneous_id')) {
                return redirect('page-not-found');
            }
            $data['miscellaneous_id'] = $this->input->get('miscellaneous_id');

            // Update the notings notifications
            $this->notification_model->post([
                'type_table' => 'miscellaneous_replies_tbl',
                'reference_id' => $data['miscellaneous_id']
            ], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');

            $data['miscellaneous'] = $this->miscellaneous_model->get(['id' => $data['miscellaneous_id']], 'GET_MISCELLANEOUS_DATA_USING_ID');
            if (count($data['miscellaneous']) < 1) {
                return redirect('page-not-found');
            }
            $data['miscellaneous_marked_to'] = $this->miscellaneous_model->get(['id' => $data['miscellaneous_id']], 'GET_MISCELLANEOUS_MARKED_TO_USING_ID');

            $data['reply_to_user'] = $this->miscellaneous_model->get(['miscellaneous_id' => $data['miscellaneous_id']], 'ALL_MISCELLANEOUS_MARKED_USERS');
            $this->load->view('diac-admin/miscellaneous-reply', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }
}
