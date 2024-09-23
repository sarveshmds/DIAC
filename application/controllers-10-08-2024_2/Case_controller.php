<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Case_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");

		# models
		$this->load->model(array('common_model', 'case_model', 'category_model', 'fees_model', 'notification_model', 'refferal_request_model', 'panel_category_model', 'claimant_respondent_model', 'arbitral_tribunal_model', 'master_setup/arbitrator_setup_model', 'response_model', 'master_setup/minor_stages_setup_model', 'master_setup/case_types_model', 'master_setup/courts_setup_model'));

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
				'index', 'all_registered_case', 'all_cases', 'add_new_case', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files', 'view_registered_case', 'add_case_fee_assessment'
			),
			'CASE_MANAGER' => array(
				'index', 'all_cases', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'view_case', 'allotted_case', 'response_format', 'add_response_format', 'view_fees_details', 'case_all_files', 'add_counsels', 'edit_counsels'
			),
			'CASE_FILER' => array(
				'index', 'all_registered_case', 'all_cases', 'add_new_case', 'view_case', 'claimant_respondant_details', 'arbitral_tribunal', 'case_allotment', 'view_fees_details', 'case_all_files', 'view_registered_case', 'add_counsels', 'edit_counsels'
			),
			'HEAD_COORDINATOR' => array(
				'index', 'all_cases', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files', 'case_fees_details', 'fees_assessment', 'view_fees_assessment', 'allotted_case', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal'
			),
			'COORDINATOR' => array(
				'index', 'all_cases', 'noting', 'case_allotment', 'view_case', 'view_fees_details', 'case_all_files', 'case_fees_details', 'fees_assessment', 'view_fees_assessment', 'allotted_case', 'arbitral_tribunal', 'case_order', 'status_of_pleadings', 'claimant_respondant_details', 'counsels'
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
				'index', 'all_cases', 'status_of_pleadings', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'case_fees_details', 'termination', 'noting', 'view_case', 'allotted_case', 'view_fees_details', 'case_all_files', 'add_case_fee_assessment', 'case_order', 'get_arbitrators_data', 'add_counsels', 'edit_counsels', 'edit_case_details', 'update_case_details'
			),
			'DEO' => array(
				'all_cases', 'claimant_respondant_details', 'counsels', 'arbitral_tribunal', 'view_case', 'allotted_case', 'view_fees_details', 'edit_case_details', 'update_case_details', 'add_counsels', 'edit_counsels', 'add_backlog_case_details', 'store_case_details'
			),
			'STENO' => array(
				'index', 'all_cases', 'view_case', 'case_all_files', 'case_order'
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

	/**
	 * Function: Used to add the case details of backlog entries
	 */
	public function add_backlog_case_details()
	{

		$sidebar['menu_item'] = 'Add Backlog Cases';
		$sidebar['menu_group'] = 'Add Backlog Cases';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Arbitrator Status
			$data['arbitrator_status'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_STATUS'], 'GET_GENCODE_DESC');

			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'FILER_CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
			$data['miscellaneous'] = $this->refferal_request_model->get('', 'GET_ALL_MISCELLANEOUS_DATA');
			$data['case_types'] = $this->getter_model->get(['gen_code_group' => 'CASE_TYPE'], 'GET_GENCODE_DESC');

			$data['page_title'] = 'Add Backlog Case';

			$data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');
			// Case Types
			$data['case_types'] = $this->case_types_model->getAllCaseTypes();
			// Courts Lists
			$data['courts_list'] = $this->courts_setup_model->getAllCourtsList();

			$this->load->view('diac-admin/add-backlog-cases', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	/**
	 * Function: Used to edit the case details
	 */
	public function edit_case_details()
	{
		$slug = $this->uri->segment(2);

		if (!$slug) {
			return redirect('page-not-found');
		}

		$sidebar['menu_item'] = 'All Cases';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			// Arbitrator Status
			$data['arbitrator_status'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_STATUS'], 'GET_GENCODE_DESC');

			$data['get_case_status'] = $this->getter_model->get(['gen_code_group' => 'FILER_CASE_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['di_type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'DI_TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
			$data['miscellaneous'] = $this->refferal_request_model->get('', 'GET_ALL_MISCELLANEOUS_DATA');
			$data['case_types'] = $this->getter_model->get(['gen_code_group' => 'CASE_TYPE'], 'GET_GENCODE_DESC');

			if (isset($slug) && !empty($slug)) {
				$data['edit_form'] = true;

				// Get the case details
				$case_data = $this->case_model->get(array('slug' => $slug), 'GET_CASE_BASIC_DATA');

				$data['page_title'] = 'Edit Case: ' . $case_data['case_no'];

				if ($case_data) {
					$data['case_data'] = $case_data;

					$data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');

					// Case Types
					$data['case_types'] = $this->case_types_model->getAllCaseTypes();

					// Courts Lists
					$data['courts_list'] = $this->courts_setup_model->getAllCourtsList();
					// $data['case_arbitrators_list'] = $this->case_model->get(array('slug' => $slug), 'GET_CASEWISE_ARBITRATORS');

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

	/**
	 * Function: Store the case details of backlog entry
	 */
	public function store_case_details()
	{
		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_det_form')) {

				$this->form_validation->set_rules('cd_case_no_prefix', 'Case No. Prefix', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_no_year', 'Case No. Year', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_no', 'Case No.', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_title', 'Message', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_by', 'Mode of reference', 'required|xss_clean');
				$this->form_validation->set_rules('cd_toa', 'Nature of arbitration', 'required|xss_clean');
				$this->form_validation->set_rules('cd_di_toa', 'Type of Arbitration', 'required|xss_clean');

				$this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'required|xss_clean');

				if ($this->input->post('cd_arbitrator_status') == 1) {
					$this->form_validation->set_rules('cd_arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
				}

				if ($this->input->post('cd_reffered_by') == 'COURT') {
					$this->form_validation->set_rules('cd_reffered_by_court_case_type', 'Case Type', 'required|xss_clean');
					$this->form_validation->set_rules('cd_reffered_by_court_case_year', 'Year', 'required|xss_clean');
					$this->form_validation->set_rules('cd_case_arb_pet', 'Reference No.', 'required|xss_clean');
				}


				if ($this->form_validation->run()) {
					$this->db->trans_begin();

					try {

						// Check if case number is already added or not
						$check_case_count = $this->db->where('case_no', $this->input->post('cd_case_no'))->where('status', 1)->count_all_results('cs_case_details_tbl');
						if ($check_case_count > 0) {
							failure_response('Case number is already registered with us.', false, true);
						}

						$slug = md5($this->input->post('case_no') . '-' . rand(999, 9999999));

						// ============================================
						// update the data into case details table
						// ============================================

						$case_details_data = array(
							'slug' => $slug,
							'case_no_prefix' => $this->input->post('cd_case_no_prefix'),
							'case_no' => $this->input->post('cd_case_no'),
							'case_no_year' => $this->input->post('cd_case_no_year'),
							'case_title' => $this->input->post('cd_case_title'),
							'reffered_by' => $this->security->xss_clean($this->input->post('cd_reffered_by')),
							'reffered_on' => formatDate($this->security->xss_clean($this->input->post('cd_reffered_on'))),
							'arbitration_petition' => $this->security->xss_clean($this->input->post('cd_case_arb_pet')),
							'recieved_on' => formatDate($this->security->xss_clean($this->input->post('cd_recieved_on'))),
							'registered_on' => formatDate($this->security->xss_clean($this->input->post('cd_registered_on'))),
							'type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_toa')),
							'di_type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_di_toa')),

							'reffered_by_judge' => '',
							'reffered_by_court' => '',
							'name_of_court' => '',
							'reffered_by_court_case_type' => '',
							'reffered_by_court_case_year' => '',
							'arbitrator_status' => $this->security->xss_clean($this->input->post('cd_arbitrator_status')),
							'arbitral_tribunal_strength' => '',

							'case_status' => 'UNDER_PROCESS',
							'status_changed_on' => date('Y-m-d'),
							'remarks' => $this->security->xss_clean($this->input->post('refferal_req_message')),
							'is_registered' => 1,
							'created_by' => $this->session->userdata('user_code'),
							'created_on' => currentDateTimeStamp(),
							'updated_by' => $this->session->userdata('user_code'),
							'updated_on' => currentDateTimeStamp()
						);

						if ($this->input->post('cd_reffered_by') == 'COURT') {
							$case_details_data['reffered_by_judge'] = $this->security->xss_clean($this->input->post('cd_reffered_by_judge'));
							$case_details_data['reffered_by_court'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court'));

							$case_details_data['reffered_by_court_case_type'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_type'));
							$case_details_data['reffered_by_court_case_year'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_year'));
						}

						if ($this->input->post('cd_reffered_by') == 'OTHER') {
							// name_of_court = name_of_authority 
							$case_details_data['name_of_court'] = $this->security->xss_clean($this->input->post('cd_name_of_court'));
						}

						if ($this->input->post('cd_arbitrator_status') == 1) {
							$case_details_data['arbitral_tribunal_strength'] = $this->input->post('cd_arbitral_tribunal_strength');
						}

						// Update case details
						$cs_result = $this->db->insert('cs_case_details_tbl', $case_details_data);

						if (!$cs_result) {
							failure_response('Error while saving data into case', false, true);
						}


						// ============================================
						// Upload the documents if any
						// ============================================
						if (!empty(array_filter($_FILES['refferal_req_documents']['name']))) {
							$this->load->library('fileupload');

							$file_result = $this->fileupload->uploadMultipleFiles($_FILES['refferal_req_documents'], [
								'file_name' => 'CASE_FILES_' . time(),
								'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_DOCUMENTS_FORMAT_ALLOWED,
								'allowed_mime_types' => CASE_DOCUMENTS_MIME_ALLOWED,
								'max_size' => CASE_DOCUMENTS_SIZE_ALLOWED
							]);

							// After getting result of file upload
							if ($file_result['status'] == false) {
								failure_response('Documents: ' . $file_result['msg'], false, true);
							} else {
								$caseFileName = $file_result['files'];

								if (count($caseFileName) > 0) {
									foreach ($caseFileName as $file) {
										$csFileResult = $this->db->insert('cs_case_supporting_documents_tbl', [
											'case_code' => $slug,
											'file_name' => $file,
											'record_status' => 1,
											'created_by' => $this->session->userdata('user_code'),
											'created_at' => currentDateTimeStamp(),
											'updated_by' => $this->session->userdata('user_code'),
											'updated_at' => currentDateTimeStamp(),
										]);

										if (!$csFileResult) {
											failure_response($file . ': Error while saving document', false, true);
										}
									}
								} else {
									failure_response('The file you are trying to upload is not uploaded', false, true);
								}
							}
						}

						// ============================================
						// Commit the changes
						success_response('Case added successfully.', false, true);
					} catch (Exception $e) {
						failure_response('Something went wrong, please try again or contact support team', false, true);
					}
				} else {
					failure_response(validation_errors(), false, false);
				}
			} else {
				failure_response('Invalid Security Token', false, false);
			}
		} else {
			failure_response('Empty Security Token', false, false);
		}
	}

	/**
	 * Function: Update the case details
	 */
	public function update_case_details()
	{
		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_det_form')) {

				$this->form_validation->set_rules('hidden_case', 'Case No.', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_title', 'Message', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_by', 'Mode of reference', 'required|xss_clean');
				$this->form_validation->set_rules('cd_toa', 'Nature of arbitration', 'required|xss_clean');
				$this->form_validation->set_rules('cd_di_toa', 'Type of Arbitration', 'required|xss_clean');

				$this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'required|xss_clean');

				if ($this->input->post('cd_arbitrator_status') == 1) {
					$this->form_validation->set_rules('cd_arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
				}

				if ($this->input->post('cd_reffered_by') == 'COURT') {
					$this->form_validation->set_rules('cd_reffered_by_court_case_type', 'Case Type', 'required|xss_clean');
					$this->form_validation->set_rules('cd_reffered_by_court_case_year', 'Year', 'required|xss_clean');
					$this->form_validation->set_rules('cd_case_arb_pet', 'Reference No.', 'required|xss_clean');
				}


				if ($this->form_validation->run()) {
					$this->db->trans_begin();

					try {
						$slug = $this->input->post('hidden_case');

						// ============================================
						// update the data into case details table
						// ============================================

						$case_details_data = array(
							'case_title' => $this->input->post('cd_case_title'),
							'reffered_by' => $this->security->xss_clean($this->input->post('cd_reffered_by')),
							'reffered_on' => formatDate($this->security->xss_clean($this->input->post('cd_reffered_on'))),
							'arbitration_petition' => $this->security->xss_clean($this->input->post('cd_case_arb_pet')),
							'recieved_on' => formatDate($this->security->xss_clean($this->input->post('cd_recieved_on'))),
							'registered_on' => formatDate($this->security->xss_clean($this->input->post('cd_registered_on'))),
							'type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_toa')),
							'di_type_of_arbitration' => $this->security->xss_clean($this->input->post('cd_di_toa')),

							'reffered_by_judge' => '',
							'reffered_by_court' => '',
							'name_of_court' => '',
							'reffered_by_court_case_type' => '',
							'reffered_by_court_case_year' => '',
							'arbitrator_status' => $this->security->xss_clean($this->input->post('cd_arbitrator_status')),
							'arbitral_tribunal_strength' => '',

							'remarks' => $this->security->xss_clean($this->input->post('refferal_req_message')),
							'updated_by' => $this->session->userdata('user_code'),
							'updated_on' => currentDateTimeStamp()
						);

						if ($this->input->post('cd_reffered_by') == 'COURT') {
							$case_details_data['reffered_by_judge'] = $this->security->xss_clean($this->input->post('cd_reffered_by_judge'));
							$case_details_data['reffered_by_court'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court'));

							$case_details_data['reffered_by_court_case_type'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_type'));
							$case_details_data['reffered_by_court_case_year'] = $this->security->xss_clean($this->input->post('cd_reffered_by_court_case_year'));
						}

						if ($this->input->post('cd_reffered_by') == 'OTHER') {
							// name_of_court = name_of_authority 
							$case_details_data['name_of_court'] = $this->security->xss_clean($this->input->post('cd_name_of_court'));
						}

						if ($this->input->post('cd_arbitrator_status') == 1) {
							$case_details_data['arbitral_tribunal_strength'] = $this->input->post('cd_arbitral_tribunal_strength');
						}

						// Update case details
						$cs_result = $this->db->where([
							'slug' => $this->input->post('hidden_case'),
						])->update('cs_case_details_tbl', $case_details_data);

						if (!$cs_result) {
							failure_response('Error while saving data into case', false, true);
						}


						// ============================================
						// Upload the documents if any
						// ============================================
						if (!empty(array_filter($_FILES['refferal_req_documents']['name']))) {
							$this->load->library('fileupload');

							$file_result = $this->fileupload->uploadMultipleFiles($_FILES['refferal_req_documents'], [
								'file_name' => 'CASE_FILES_' . time(),
								'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_DOCUMENTS_FORMAT_ALLOWED,
								'allowed_mime_types' => CASE_DOCUMENTS_MIME_ALLOWED,
								'max_size' => CASE_DOCUMENTS_SIZE_ALLOWED
							]);

							// After getting result of file upload
							if ($file_result['status'] == false) {
								failure_response('Documents: ' . $file_result['msg'], false, true);
							} else {
								$caseFileName = $file_result['files'];

								if (count($caseFileName) > 0) {
									foreach ($caseFileName as $file) {
										$csFileResult = $this->db->insert('cs_case_supporting_documents_tbl', [
											'case_code' => $slug,
											'file_name' => $file,
											'record_status' => 1,
											'created_by' => $this->session->userdata('user_code'),
											'created_at' => currentDateTimeStamp(),
											'updated_by' => $this->session->userdata('user_code'),
											'updated_at' => currentDateTimeStamp(),
										]);

										if (!$csFileResult) {
											failure_response($file . ': Error while saving document', false, true);
										}
									}
								} else {
									failure_response('The file you are trying to upload is not uploaded', false, true);
								}
							}
						}

						// ============================================
						// Commit the changes
						success_response('Data updated successfully.', false, true);
					} catch (Exception $e) {
						failure_response('Something went wrong, please try again or contact support team', false, true);
					}
				} else {
					failure_response(validation_errors(), false, false);
				}
			} else {
				failure_response('Invalid Security Token', false, false);
			}
		} else {
			failure_response('Empty Security Token', false, false);
		}
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
					$data['case_details'] = $this->case_model->getCaseDetailsUsingCaseCode($case_slug);

					// echo '<pre>';
					// print_r($data['case_data']);
					// die;

					// Get the case files
					$data['case_supporting_docs'] = $this->common_model->get_case_supporting_doc_using_case_code($case_slug);

					$data['page_title'] = 'Case Details: ' . get_full_case_number($data['case_details']);

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

			$data['cases_counts'] = $this->case_model->get_cases_counts();

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
					// Get other informations
					$data['case_details'] = $this->case_model->getCaseDetailsUsingCaseCode($case_slug);

					// get claimants and respondants details 
					$data['claimants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($case_slug, 'claimant');
					$data['respondants_list'] = $this->claimant_respondent_model->get_claimant_respondant_using_case_and_slug($case_slug, 'respondant');

					// get arbitrators details
					if ($data['case_details']['arbitrator_status'] == 1) {
						$data['arbitrator_list'] = $this->arbitrator_setup_model->getArbitratorsLists($case_slug);
					}

					// Get case arbitrator list
					$data['arbitrators'] = $this->arbitral_tribunal_model->getCaseArbitratorsUsingCaseCode($case_slug);

					// Get the case files
					$data['case_supporting_docs'] = $this->common_model->get_case_supporting_doc_using_case_code($case_slug);

					$data['page_title'] = 'Case Details: ' . get_full_case_number($data['case_details']);

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

	/**
	 * This functoin is not in used, do not use this function.
	 */
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
			$data['miscellaneous'] = $this->refferal_request_model->get('', 'GET_ALL_MISCELLANEOUS_DATA');
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
				$data['all_counsels_list'] = $this->getter_model->get(null, 'GET_ALL_COUNSELS_LIST');

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

	public function add_counsels()
	{
		$case_code = $this->input->get('case_code');
		$data['case_no'] = $case_code;
		$data['page_title'] = 'Add Counsels';
		if (!$case_code) {
			return redirect('page-not-found');
		}

		// Check if the case is alloted or not
		$check_case_allotment = $this->common_model->check_user_case_allotment($case_code);

		if (!$check_case_allotment) {
			return redirect('page-not-found');
		}

		$sidebar['menu_item'] = 'Counsels';
		$sidebar['menu_group'] = 'Case Management';

		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');
		$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {
			$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
			$data['all_counsels_list'] = $this->getter_model->get(null, 'GET_ALL_COUNSELS_LIST');

			$this->load->view('diac-admin/add-counsels', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function edit_counsels()
	{
		$data['page_title'] = 'Update Counsels';
		$code = $this->input->get('code');
		$case_no = $this->input->get('case');
		$data['counsels_code'] = $code;
		$data['case_no'] = $case_no;
		$data['edit_form'] = true;
		$cs_field['code'] = $code;
		$cs_field['case_no'] = $case_no;

		$counsels_data = $this->getter_model->get($cs_field, 'get_counsels_data');

		if (!$counsels_data) {
			return redirect('page-not-found');
		}

		if (!$code) {
			return redirect('page-not-found');
		}

		// Check if the case is alloted or not
		$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

		if (!$check_case_allotment) {
			return redirect('page-not-found');
		}

		$sidebar['menu_item'] = 'Counsels';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');
		$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {
			$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
			$data['all_counsels_list'] = $this->getter_model->get(null, 'GET_ALL_COUNSELS_LIST');

			$data['counsels_data'] = $counsels_data;
			$get_perm_state = $this->common_model->get_states($counsels_data['perm_country_code']);
			$get_corr_state = $this->common_model->get_states($counsels_data['corr_country_code']);
			$data['get_perm_state'] = $get_perm_state;
			$data['get_corr_state'] = $get_corr_state;

			$this->load->view('diac-admin/add-counsels', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	// Function created by Ameen on Dated 26-April-2023
	public function case_order($case_no = '')
	{
		$sidebar['menu_item'] = 'Case Order';
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
				$data['page_title'] = 'Case Order';
				$data['case_no'] = $case_no;
				$data['claim_res_data'] = $this->case_model->get($data, 'GET_ALL_CLAIMANT_RESPONDENT');
				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');

				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);

				if ($check_case_allotment) {
					$this->load->view('diac-admin/case_order.php', $data);
				} else {
					redirect('case-order');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Case Orders';
				$data['type'] = 'CASE_ORDERS';
				$this->load->view('diac-admin/enter-case-no.php', $data);
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	// End

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
				$data['arbitrators_name'] = $this->getter_model->get([
					'is_empanelled' => 1
				], 'get_arbitrators_name');
				$data['not_emp_arbitrators_name'] = $this->getter_model->get([
					'is_empanelled' => 2
				], 'get_arbitrators_name');


				$data['page_title'] = 'All Arbitral Tribunals';
				$data['case_no'] = $case_no;
				$data['get_appointed_by_list'] = $this->getter_model->get(['gen_code_group' => 'APPOINTED_BY'], 'GET_GENCODE_DESC');
				$data['arbitrator_types'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_TYPE'], 'GET_GENCODE_DESC');
				$data['get_termination_by_list'] = $this->getter_model->get(['gen_code_group' => 'AT_TERMINATION_BY'], 'GET_GENCODE_DESC');

				$data['panel_category'] = $this->panel_category_model->getAllPanelCategory();

				$data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');
				$data['countries'] = $this->getter_model->get(null, 'GET_ALL_COUNTRIES');
				$check_case_allotment = $this->common_model->check_user_case_allotment($case_no);
				if ($check_case_allotment) {
					$this->load->view('diac-admin/arbitral-tribunal.php', $data);
				} else {
					redirect('arbitral-tribunal');
				}
			} else {
				$data['case_lists'] = $this->common_model->get_all_alloted_case_list();
				$data['page_title'] = 'Arbitral Tribunals';
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

				// echo '<pre>';
				// print_r($data['fee_cost_data']);
				// die;
				$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($case_no);

				$data['fee_prayers'] = $this->fees_model->get($data, 'GET_FEES_PRAYERS_OF_CASE');

				$arb_rel_list = $this->arbitral_tribunal_model->getCaseArbitratorsUsingCaseCode($case_no);
				$data['release_arbitrators_list'] = $arb_rel_list;
				// echo "<pre>";
				// print_r($data['release_arbitrators_list']);
				// echo "</pre>";
				// die;

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

					$data['assessment'] =  $this->fees_model->get(['fee_code' => customURIDecode($this->input->get('id'))], 'GET_SINGLE_ASSESSMENT_FEE_DETAILS');

					// Prayers
					$data['prayers_details'] = $this->getter_model->get(customURIDecode($this->input->get('id')), 'GET_PRAYERS_DETAILS_FOR_EDIT');

					$data['cc_prayers_details'] = $this->getter_model->get(customURIDecode($this->input->get('id')), 'GET_CC_PRAYERS_DETAILS_FOR_EDIT');
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

				$data['fee_prayers'] = $this->fees_model->get($data, 'GET_FEES_PRAYERS_OF_CASE');

				$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($case_no);
				$data['fee_cost_data'] = $this->fees_model->get($data, 'GET_FEE_COST_DATA');
				$data['fee_deposited_data'] = $this->fees_model->get($data, 'GET_FEES_DEPOSITED_LIST');

				// echo '<pre>';
				// print_r($data['fee_cost_data']);
				// die;

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

				// Get pre defined noting text
				$data['noting_texts'] = $this->getter_model->get(['gen_code_group' => 'NOTING_SMART_TEXT'], 'GET_GENCODE_DESC');

				// Get case related users
				$data['case_users'] = $this->common_model->get_all_users_related_to_case($case_no);

				// Get issued letters in the case
				$data['response_formats'] = $this->response_model->get_all_response_formats_in_case($case_no);

				$data['major_case_status'] = $this->getter_model->get(['gen_code_group' => 'CASE_STATUS'], 'GET_GENCODE_DESC');
				$data['minor_case_status'] = $this->minor_stages_setup_model->get_all_stages();

				$data['case_status_timelines'] = $this->common_model->get_case_status_timeline($case_no);

				// print_r($data['case_status_timelines']);
				// die;

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
			$data['all_coordinators'] = $this->case_model->get(['role' => 'COORDINATOR'], 'ALL_DIAC_USERS');


			$data['all_diac_users'] = $this->case_model->get('', 'ALL_DIAC_USERS');
			// $data['allotment_roles'] = [
			// 	'CASE_MANAGER' => 'Case Manager',
			// 	'DEPUTY_COUNSEL' => 'Deupty Counsel',
			// ];

			$this->load->view('case_allotment/case-allotment.php', $data);
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
			$data['get_minor_case_status'] = $this->minor_stages_setup_model->get_all_stages();
			$data['arbitrator_status'] = $this->getter_model->get(['gen_code_group' => 'ARBITRATOR_STATUS'], 'GET_GENCODE_DESC');
			$data['type_of_arbitration'] = $this->getter_model->get(['gen_code_group' => 'TYPE_OF_ARBITRATION'], 'GET_GENCODE_DESC');
			$data['reffered_by'] = $this->getter_model->get(['gen_code_group' => 'REFFERED_BY'], 'GET_GENCODE_DESC');
			$data['alloted_cases_list'] = $this->common_model->get_all_alloted_case_list();
			// print_r($data['arbitrator_status']);
			// die;

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

	public function fees_assessment()
	{
		$sidebar['menu_item'] = 'Fees Assessment';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {
			$data['page_title'] = 'Fees Assessment';
			$this->load->view('diac-admin/fees_assessment', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function view_fees_assessment()
	{
		$sidebar['menu_item'] = 'Fees Assessment';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {
			$code = $this->uri->segment(2);
			if (isset($code) && !empty($code)) {
				$data['page_title'] = 'View Fees Assessment';
				$check_asses = $this->getter_model->get($code, 'CHECK_FEES_ASSESMENT');
				if ($check_asses) {
					$get_asses_details = $this->getter_model->get($code, 'GET_FEES_ASSESSMENT');
					$data['case_data'] = $this->common_model->get_case_registered_details_from_slug($get_asses_details['case_no']);
					$data['asses_details'] = $get_asses_details;
					$data['prayers_details'] = $this->getter_model->get($code, 'GET_PRAYERS_DETAILS');
					$data['cc_prayers_details'] = $this->getter_model->get($code, 'GET_CC_PRAYERS_DETAILS');


					$this->load->view('diac-admin/view_fees_assessment', $data);
				} else {
					return redirect('page-not-found');
				}
			} else {
				return redirect('page-not-found');
			}
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
}
