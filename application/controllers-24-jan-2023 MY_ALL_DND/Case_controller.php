<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Case_controller extends CI_Controller
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
			'ADMIN' => array('index', 'all_registered_case', 'all_cases', 'add_new_case', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files'),
			'DIAC' => array(
				'index', 'all_registered_case', 'all_cases', 'add_new_case', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files', 'view_registered_case'
			),
			'CASE_MANAGER' => array(
				'index', 'all_cases', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'view_case', 'allotted_case', 'response_format', 'add_response_format', 'view_fees_details', 'case_all_files'
			),
			'CASE_FILER' => array(
				'index', 'all_registered_case', 'all_cases', 'add_new_case', 'view_case', 'claimant_respondant_details', 'arbitral_tribunal', 'case_allotment', 'view_fees_details', 'case_all_files', 'view_registered_case'
			),
			'COORDINATOR' => array(
				'index', 'all_cases', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files'
			),
			'ACCOUNTS' => array(
				'index', 'all_cases', 'case_fees_details', 'noting', 'view_case', 'allotted_case', 'view_fees_details', 'case_all_files'
			),
			'CAUSE_LIST_MANAGER' => array(
				'index', 'all_cases', 'view_case', 'view_fees_details', 'case_all_files'
			),
			'POA_MANAGER' => array(
				'index', 'all_cases', 'view_case'
			),
			'DEPUTY_COUNSEL' => array(
				'index', 'all_cases', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'view_case', 'allotted_case', 'view_fees_details', 'case_all_files', 'add_case_fee_assessment'
			),
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

	public function page_not_found()
	{
		$this->load->view('templates/404');
	}

	public function view_case()
	{
		$case_slug = $this->uri->segment(2);
		if ($case_slug) {
			$sidebar['menu_item'] = 'All Cases';
			$sidebar['menu_group'] = 'Case Management';
			$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
			$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
			$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
			$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

			$this->load->view('templates/side_menu', $sidebar);
			$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

			if ($page_status != 0) {

				// check if case is exist or not
				$check_case = $this->common_model->check_case_number($case_slug);
				// echo $check_case; die;
				if ($check_case) {
					$parameters = array('slug' => $case_slug);
					// Get all data
					$data['case_data'] = $this->case_model->get($parameters, 'ALL_CASE_DATA');

					$data['page_title'] = "Case Details: " . $data['case_data']['case_data']['case_no'];

					$this->load->view('diac-admin/view-case', $data);
				} else {
					redirect('all-cases');
				}
			} else {
				$this->load->view('templates/page_maintenance');
			}
			$this->load->view('templates/footer');
		} else {
			redirect('diac-admin-dashboard');
		}
	}

	public function all_registered_case()
	{
		$sidebar['menu_item'] = 'Register Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'All Registered Cases';

		if ($page_status != 0) {
			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
			$this->load->view('diac-admin/all-registered-case', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function view_registered_case()
	{
		$case_slug = $this->input->get('case_no');
		if ($case_slug) {
			$sidebar['menu_item'] = 'Register Cases';
			$sidebar['menu_group'] = 'Case Management';
			$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
			$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
			$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
			$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

			$this->load->view('templates/side_menu', $sidebar);
			$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

			if ($page_status != 0) {

				// check if case is exist or not
				$check_case = $this->common_model->check_case_number($case_slug);
				// echo $check_case; die;
				if ($check_case) {
					$parameters = array('slug' => $case_slug);
					// Get all data
					$data['case_data'] = $this->case_model->get($parameters, 'VIEW_REGISTER_CASE_DATA');

					$data['page_title'] = "Case Details: " . $data['case_data']['case_no'];

					$this->load->view('diac-admin/view_case_details', $data);
				} else {
					redirect('all-registered-case');
				}
			} else {
				$this->load->view('templates/page_maintenance');
			}
			$this->load->view('templates/footer');
		} else {
			redirect('diac-admin-dashboard');
		}
	}

	public function all_cases()
	{
		$sidebar['menu_item'] = 'All Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'All Cases';

		if ($page_status != 0) {
			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');

			$this->load->view('diac-admin/all-cases', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function add_new_case($slug = '')
	{

		$sidebar['menu_item'] = 'Register Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$slug = $this->uri->segment(2);

			$data['get_arbitrator_status'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_STATUS'], 'GET_GENCODE_DESC');
			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'FILER_CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
			$data['miscellaneous'] = $this->miscellaneous_model->get('', 'GET_ALL_MISCELLANEOUS_DATA');
			$data['case_types'] = $this->getter_model->get(['gen_code_group' => 'CASE_TYPE'], 'GET_GENCODE_DESC');

			if (isset($slug) && !empty($slug)) {
				$data['edit_form'] = true;

				// Get the case details
				$case_data = $this->case_model->get(array('slug' => $slug), 'GET_CASE_BASIC_DATA');

				$data['page_title'] = 'Edit Case: ' . $case_data['case_no'];

				if ($case_data) {
					$data['case_data'] = $case_data;

					$data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');

					$data['case_arbitrators_list'] = $this->case_model->get(array('slug' => $slug), 'GET_CASEWISE_ARBITRATORS');

					$this->load->view('diac-admin/add-case', $data);
				} else {
					redirect('add-new-case');
				}
			} else {
				$data['edit_form'] = false;
				$data['page_title'] = 'Add Case';
				$this->load->view('diac-admin/add-case', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function status_of_pleadings($case_no = '')
	{
		$sidebar['menu_item'] = 'Status of Pleadings';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Status Of Pleadings';

				// This is referring to slug
				$data['case_no'] = $case_no;

				$data['sop_data'] = $this->case_model->get($data, 'GET_STATUS_OF_PLEADINGS');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/status-of-pleadings.php', $data);
				} else {
					redirect('status-of-pleadings');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Status Of Pleadings';
				$data['type'] = 'STATUS_OF_PLEADINGS';
				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function claimant_respondant_details($case_no = '')
	{
		$sidebar['menu_item'] = 'Claimant & Respondant';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Claimant & Respondent';
				$data['case_no'] = $case_no;
				$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/claimant-respondent-details.php', $data);
				} else {
					redirect('claimant-respondant-details');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();

				$data['page_title'] = 'Claimant & Respondent';
				$data['type'] = 'CLAIMANT_RESPONDANT_DETAILS';

				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function counsels($case_no = '')
	{
		$sidebar['menu_item'] = 'Counsels';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'All Counsels';
				$data['case_no'] = $case_no;
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');

				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/counsels.php', $data);
				} else {
					redirect('counsels');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'All Counsels';
				$data['type'] = 'COUNSELS';
				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function arbitral_tribunal($case_no = '')
	{
		$sidebar['menu_item'] = 'Arbitral Tribunal';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'All Arbitral Tribunals';
				$data['case_no'] = $case_no;
				$data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');
				$data['get_termination_by_list'] = $this->getter_model->get(['gen_code_group' => 'AT_TERMINATION_BY'], 'GET_GENCODE_DESC');
				$data['arbitrator_types'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_TYPE'], 'GET_GENCODE_DESC');

				$data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');

				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);
				if ($check_case_allotment) {
					$this->load->view('diac-admin/arbitral-tribunal.php', $data);
				} else {
					redirect('arbitral-tribunal');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'All Arbitral Tribunals';
				$data['type'] = 'ARBITRAL_TRIBUNAL';
				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function case_fees_details($case_no = '')
	{
		$sidebar['menu_item'] = 'Case Fee';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Case Fee';
				$data['case_no'] = $case_no;

				$data['assessment_editable'] = true;
				$data['fee_cost_data'] = $this->fees_model->get($data, 'GET_FEE_COST_DATA');
				$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($case_no);

				// $data['currency'] = '';
				// if ($data['case_data']['type_of_arbitration'] == 'DOMESTIC') {
				// 	$data['currency'] = 'Rs.';
				// }
				// if ($data['case_data']['type_of_arbitration'] == 'INTERNATIONAL') {
				// 	$data['currency'] = '$';
				// }

				$data['get_dt_gencode_group'] = $this->getter_model->get(['gen_code_group' => 'CASE_DEPOSITED_TOWAR'], 'GET_DEPOSITED_TOWARDS_GENCODE_DESC');
				$data['get_mop_list'] = $this->getter_model->get(['gen_code_group' => 'MODE_OF_PAYMENT'], 'GET_GENCODE_DESC');
				$data['nature_of_awards'] = $this->getter_model->get(['gen_code_group' => 'NATURE_OF_AWARD'], 'GET_GENCODE_DESC');
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

				$type = 'CASE_FEES_DETAILS';
				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no, $type);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/case-fees-details.php', $data);
				} else {
					redirect('case-fees-details');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Case Fee';
				$data['type'] = 'CASE_FEES_DETAILS';
				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function add_case_fee_assessment($case_no)
	{
		$sidebar['menu_item'] = 'Case Fee';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(3);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Add Assessment';
				$data['case_no'] = $case_no;

				$data['assessment'] = false;
				if ($this->input->get('id')) {
					$data['assessment'] =  $this->fees_model->get(['fee_id' => customURIDecode($this->input->get('id'))], 'GET_SINGLE_ASSESSMENT_FEE_DETAILS');
				}

				$data['fee_cost_data'] = $this->fees_model->get($data, 'GET_FEE_COST_DATA');
				$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($case_no);

				if ($data['case_data']['type_of_arbitration'] == 'DOMESTIC') {
					$data['currency'] = 'Rs.';
				}
				if ($data['case_data']['type_of_arbitration'] == 'INTERNATIONAL') {
					$data['currency'] = '$';
				}

				$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
				$data['get_dt_gencode_group'] = $this->getter_model->get(['gen_code_group' => 'CASE_DEPOSITED_TOWAR'], 'GET_DEPOSITED_TOWARDS_GENCODE_DESC');
				$data['get_mop_list'] = $this->getter_model->get(['gen_code_group' => 'MODE_OF_PAYMENT'], 'GET_GENCODE_DESC');
				$data['nature_of_awards'] = $this->getter_model->get(['gen_code_group' => 'NATURE_OF_AWARD'], 'GET_GENCODE_DESC');
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

				$type = 'CASE_FEES_DETAILS';
				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no, $type);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/add-case-fee-assessment.php', $data);
				} else {
					redirect('case-fees-details');
				}
			} else {
				return redirect('page-not-found');
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function view_fees_details($case_no = '')
	{
		$sidebar['menu_item'] = 'All Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Fee Details';
				$data['case_no'] = $case_no;

				$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($case_no);
				$data['fee_cost_data'] = $this->fees_model->get($data, 'GET_FEE_COST_DATA');
				$data['fee_deposited_data'] = $this->fees_model->get($data, 'GET_FEES_DEPOSITED_LIST');

				// Fees deficiency
				$data['fees_deficiency'] = $this->fees_model->calculate_case_fee_deficiency($case_no);

				$data['case_amount_type'] = 'ARRAY_LIST';
				$data['balance_amount_data'] = $this->fees_model->get($data, 'CASE_AMOUNT_DETAILS');

				$data['get_dt_gencode_group'] = $this->getter_model->get(['gen_code_group' => 'CASE_DEPOSITED_TOWAR'], 'GET_DEPOSITED_TOWARDS_GENCODE_DESC');
				$data['get_mop_list'] = $this->getter_model->get(['gen_code_group' => 'MODE_OF_PAYMENT'], 'GET_GENCODE_DESC');
				$data['nature_of_awards'] = $this->getter_model->get(['gen_code_group' => 'NATURE_OF_AWARD'], 'GET_GENCODE_DESC');
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

				$this->load->view('diac-admin/views-fees-details.php', $data);
			} else {
				return redirect('page-not-found');
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function termination($case_no = '')
	{
		$sidebar['menu_item'] = 'Termination';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Termination';

				$data['case_no'] = $case_no;
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$data['award_term_data'] = $this->case_model->get($data, 'GET_AWARD_TERMINATION');
				$data['termination_reasons'] = $this->getter_model->get(['gen_code_group' => 'CASE_TERMINATION_REASON'], 'GET_GENCODE_DESC');
				$data['term_nature_of_awards'] = $this->getter_model->get(['gen_code_group' => 'TERM_NATURE_OF_AWARD'], 'GET_GENCODE_DESC');

				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);
				if ($check_case_allotment) {
					$this->load->view('diac-admin/termination.php', $data);
				} else {
					redirect('termination');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Termination';
				$data['type'] = 'AWARD_TERMINATION';

				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function noting($case_no = '')
	{
		$sidebar['menu_item'] = 'Noting';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Noting';

				// Update the notings notifications
				$this->notification_model->post([
					'type_table' => 'cs_noting_tbl',
					'reference_id' => $case_no
				], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');

				$data['case_no'] = $case_no;
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$data['all_diac_users'] = $this->case_model->get($data, 'ALL_DIAC_USERS');

				$data['noting_texts'] = $this->getter_model->get(['gen_code_group' => 'NOTING_SMART_TEXT'], 'GET_GENCODE_DESC');

				$data['case_users'] = $this->common_model->get_all_users_related_to_case($case_no);

				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);
				if ($check_case_allotment) {
					$this->load->view('diac-admin/noting.php', $data);
				} else {
					redirect('noting');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Noting';
				$data['type'] = 'NOTING';

				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function case_allotment()
	{
		$sidebar['menu_item'] = 'Case Allotment';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'Case Allotment';

		if ($page_status != 0) {

			$data['all_registered_cases'] = $this->case_model->get('', 'GET_ALL_REGISTERED_CASES');
			$data['all_cms'] = $this->case_model->get(['role' => 'CASE_MANAGER'], 'ALL_DIAC_USERS');
			$data['all_dcs'] = $this->case_model->get(['role' => 'DEPUTY_COUNSEL'], 'ALL_DIAC_USERS');

			$data['all_diac_users'] = $this->case_model->get('', 'ALL_DIAC_USERS');
			// $data['allotment_roles'] = [
			// 	'CASE_MANAGER' => 'Case Manager',
			// 	'DEPUTY_COUNSEL' => 'Deupty Counsel',
			// ];

			$this->load->view('diac-admin/case-allotment.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function allotted_case()
	{
		$sidebar['menu_item'] = 'Alloted Case';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'Allotted Case';

		if ($page_status != 0) {
			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');

			// Update the notings notifications
			$this->notification_model->post([
				'type_table' => 'cs_case_allotment_tbl',
			], 'MARK_CATEGORYWISE_NOTIFICATION_SEEN');
			$this->load->view('diac-admin/allotted-case.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function case_all_files($case_no = '')
	{
		$sidebar['menu_item'] = 'All Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Get uri segment
			$case_no = $this->uri->segment(2);

			if (isset($case_no) && !empty($case_no)) {
				$data['page_title'] = 'Case Files';
				$data['case_no'] = $case_no;

				$data['case_files'] = $this->case_model->get($data, 'CASE_ALL_FILES');
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

				$this->load->view('diac-admin/case-all-files.php', $data);
			} else {
				return redirect('page-not-found');
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
}
