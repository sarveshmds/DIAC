<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Dashboard Controller
class Dashboard_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model(['dashboard_model', 'Getter_model', 'fees_model', 'case_model', 'work_status_model']);

		// Get notification
		$data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
	}
	public function page_not_found()
	{
		$this->load->view('templates/404');
	}
	public function _remap($method)
	{
		$class = $this->router->class;
		$role = $this->session->userdata('role');
		$check_user = $this->getter_model->get(null, 'get_user_check');
		$role_action_auth = array(
			'SUPERADMIN' => array('superadmin_dashboard'),
			'ADMIN' => array('diac_dashboard'),
			'DIAC' => array('diac_dashboard'),
			'HEAD_COORDINATOR' => array('head_coordinator_dashboard'),
			'DEPUTY_COUNSEL' => array('diac_dashboard'),
			'CASE_MANAGER' => array('diac_dashboard'),
			'CASE_FILER' => array('diac_dashboard'),
			'COORDINATOR' => array('diac_dashboard'),
			'ACCOUNTS' => array('diac_dashboard'),
			'CAUSE_LIST_MANAGER' => array('diac_dashboard'),
			'POA_MANAGER' => array('diac_dashboard'),
			'DEO' => array('deo_dashboard')
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
				redirect('logout');
			}
		}
	}
	public function superadmin_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		$data['get_rolewise_user'] = $this->dashboard_model->get(null, 'GET_ROLEWISE_USER');
		$data['get_login_current_date'] = $this->dashboard_model->get(null, 'GET_LOGIN_CURRENT_DATE');
		if ($page_status != 0) {
			$this->load->view('dashboard/superadmin_dashboard', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function admin_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		$data['get_rolewise_user'] = $this->dashboard_model->get(null, 'GET_ROLEWISE_USER');
		$data['get_login_current_date'] = $this->dashboard_model->get(null, 'GET_LOGIN_CURRENT_DATE');
		if ($page_status != 0) {
			$this->load->view('dashboard/superadmin_dashboard', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function diac_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Check if the year is set for filter on not
			if ($this->input->get('year')) {
				$year = base64_decode($this->input->get('year'));
			} else {
				$year = date('Y');
			}
			$data['current_year'] = $year;
			// Get all data
			$data['dashboard_data'] = $this->dashboard_model->get(['current_year' => $year], 'GET_DATA_FOR_DASHBOARD');

			// Get all the latest notifications
			$data['notifications'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

			// Get efiling data
			$data['pending_efiling'] = $this->dashboard_model->check_pending_efilings();

			// Fees deficiency
			$data['fees_deficiency'] = $this->fees_model->calculate_case_fee_deficiency();

			// Get efiling pending messages
			$data['pending_efiling_messages'] = $this->dashboard_model->check_pending_efilings_messages();

			// Get pending fees assessment
			$data['new_fees_assessment'] = $this->dashboard_model->check_pending_fees_asses();

			$data['toa_data'] = $this->dashboard_model->fetch_toa_percentage();
			$data['toc_data'] = $this->dashboard_model->fetch_toc_percentage();

			if (in_array($this->session->userdata('role'), ALLOTMENT_ROLES)) {
				$data['alloted_cases'] = $this->case_model->get('', 'CASE_ALLOTMENT_USING_USER_CODE');
			}

			if (in_array($this->session->userdata('role'), ['CASE_MANAGER', 'DEPUTY_COUNSEL', 'COORDINATOR'])) {
				$data['pending_works_list'] = $this->work_status_model->get_pending_works_for_calender($this->session->userdata('user_code'));
				$data['completed_works_list'] = $this->work_status_model->get_completed_works($this->session->userdata('user_code'));
			}

			$this->load->view('dashboard/diac-dashboard', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function head_coordinator_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Check if the year is set for filter on not
			if ($this->input->get('year')) {
				$year = base64_decode($this->input->get('year'));
			} else {
				$year = date('Y');
			}
			$data['current_year'] = $year;
			// Get all data
			$data['dashboard_data'] = $this->dashboard_model->get(['current_year' => $year], 'GET_DATA_FOR_DASHBOARD');

			// Get all the latest notifications
			$data['notifications'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

			// Get efiling data
			$data['pending_efiling'] = $this->dashboard_model->check_pending_efilings();

			// Fees deficiency
			$data['fees_deficiency'] = $this->fees_model->calculate_case_fee_deficiency();

			// Get efiling pending messages
			$data['pending_efiling_messages'] = $this->dashboard_model->check_pending_efilings_messages();

			// Get pending fees assessment
			$data['new_fees_assessment'] = $this->dashboard_model->check_pending_fees_asses();

			$data['toa_data'] = $this->dashboard_model->fetch_toa_percentage();
			$data['toc_data'] = $this->dashboard_model->fetch_toc_percentage();

			$this->load->view('dashboard/head_coordinator_dashboard', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function deo_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';

		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {
			$this->load->view('dashboard/deo_dashboard');
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
}
