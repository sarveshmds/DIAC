<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Causelist_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");

		# models
		$this->load->model(array('common_model', 'causelist_model', 'category_model', 'case_model', 'claimant_respondent_model', 'arbitral_tribunal_model'));

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
			'DIAC' => array(
				'cause_list', 'fetch_hearings', 'rooms_list', 'hearings_today', 'display_board', 'check_rooms_availability', 'all_cause_list'
			),
			'CASE_MANAGER' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'check_rooms_availability'
			),
			'CASE_FILER' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'check_rooms_availability', 'all_cause_list'
			),
			'HEAD_COORDINATOR' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'check_rooms_availability', 'all_cause_list'
			),
			'COORDINATOR' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'check_rooms_availability'
			),
			'ACCOUNTS' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'all_cause_list'
			),
			'CAUSE_LIST_MANAGER' => array(
				'cause_list', 'fetch_hearings', 'rooms_list', 'hearings_today', 'display_board', 'check_rooms_availability'
			),
			'POA_MANAGER' => array(
				'cause_list', 'fetch_hearings', 'hearings_today'
			),
			'DEPUTY_COUNSEL' => array(
				'cause_list', 'fetch_hearings', 'hearings_today', 'add_cause_list', 'check_rooms_availability'
			),
		);

		if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
			redirect('logout');
			//self::page_not_found();
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

	public function cause_list()
	{
		$sidebar['menu_item'] = 'Cause List';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$data['page_title'] = 'Cause List';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');
			$data['all_case_list'] = $this->causelist_model->get('', 'get_all_cases_list');
			$data['arbitrators_list'] = $this->causelist_model->get(null, 'get_arbitrators_list');
			// Get all data
			$data['cases_list'] = $this->causelist_model->get('', 'get_all_cases_list');
			$data['alloted_cases_list'] = $this->common_model->get_all_alloted_case_list();
			// echo '<pre>';
			// print_r($data['cases_list']);
			// die;

			$this->load->view('cause_list/cause-list.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function add_cause_list()
	{
		$sidebar['menu_item'] = 'Cause List';
		$sidebar['menu_group'] = 'Case Management';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$data['page_title'] = 'Add Cause List';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');
			// $data['all_case_list'] = $this->causelist_model->get('', 'get_all_cases_list');
			$data['arbitrators_list'] = $this->causelist_model->get(null, 'get_arbitrators_list');
			$data['alloted_cases_list'] = $this->common_model->get_all_alloted_case_list();

			$this->load->view('cause_list/add-cause-list.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function hearings_today()
	{
		$sidebar['menu_item'] = 'Hearings Today';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$data['page_title'] = 'Hearings Today';

			$this->load->view('cause_list/hearings-today.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function display_board()
	{
		$sidebar['menu_item'] = 'Display Board';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$data['page_title'] = 'Display Board for (' . date('d-M, Y') . ')';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');

			$this->load->view('cause_list/display-board.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function rooms_list()
	{
		$sidebar['menu_item'] = 'Rooms List';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'Rooms List';
		$data['count'] = 1;
		$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');

		if ($page_status != 0) {
			$this->load->view('cause_list/rooms-list.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function fetch_hearings()
	{
		$fetch_data =  $this->causelist_model->get('', 'FETCH_HEARINGS_LIST');

		echo json_encode([
			'status' => true,
			'hearings' => $fetch_data
		]);
		die;
	}

	public function check_rooms_availability()
	{
		$date = $this->input->post('date');
		$mode_of_hearing = $this->input->post('mode_of_hearing');
		$time_from = $this->input->post('time_from');
		$time_to = $this->input->post('time_to');

		if (in_array($mode_of_hearing, ['PHYSICAL', 'HYBRID'])) {
			// Now fetch which rooms are available for that slot
			// Subquery to get the list of booked room numbers
			$this->db->select('clt.room_no_id');
			$this->db->from('cause_list_tbl AS clt');
			$this->db->where('clt.active_status', 1);
			$this->db->where('clt.date', date('Y-m-d', strtotime($date)));
			$this->db->where("NOT ('$time_to' <= clt.time_from OR '$time_from' >= clt.time_to)");
			$this->db->group_by('clt.room_no_id');
			$booked_rooms_query = $this->db->get_compiled_select();

			// Main query to select available rooms
			$this->db->select('id, room_no, room_name');
			$this->db->from('rooms_tbl');
			$this->db->where("id NOT IN ($booked_rooms_query)", NULL, FALSE);
			$this->db->where('active_status', 1);
			$available_rooms_query = $this->db->get();

			$available_rooms = $available_rooms_query->result_array();

			if (count($available_rooms) > 0) {
				echo json_encode([
					'status' => true,
					'msg' => 'Slots is available for the listed rooms in dropdown.',
					'available_rooms' => $available_rooms
				]);
				die;
			} else {
				echo json_encode([
					'status' => true,
					'msg' => 'Rooms are not available for the selected time slot.'
				]);
				die;
			}
		} else {
			echo json_encode([
				'status' => true,
				'msg' => ''
			]);
			die;
		}

		// $this->db->select("clt.id, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc,mat.name_of_arbitrator as arbitrator ");
		// $this->db->from('cause_list_tbl AS clt');
		// $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
		// $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
		// $this->db->join('master_arbitrators_tbl as mat', 'mat.code = clt.arbitrator_name', 'left');
		// $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');

		// $this->db->where('clt.active_status !=', 0);

		// $this->db->where('clt.date', date('Y-m-d', strtotime($date)));
		// // $this->db->where("'$time_from' BETWEEN clt.time_from AND clt.time_to");
		// // $this->db->where("'$time_to' BETWEEN clt.time_from AND clt.time_to");

		// $this->db->where("NOT ('$time_to' <= clt.time_from OR '$time_from' >= clt.time_to)");

		// $this->db->order_by('rt.room_no', 'asc');

		// $query = $this->db->get();

		// $fetch_data = $query->result_array();

		// // echo '<pre>';
		// // print_r($fetch_data);
		// // die;

		// if (count($fetch_data) < 1) {
		// 	// Slot is available

		// } else {
		// 	echo json_encode([
		// 		'status' => false,
		// 		'msg' => 'This slot is not available, please select another slot.'
		// 	]);
		// 	die;
		// }
	}


	// all cause list function
	function all_cause_list()
	{
		if ($this->session->userdata('role') == 'DIAC') {
			$sidebar['menu_item'] = 'Cause List';
			$sidebar['menu_group'] = 'Rooms Availability';
		} else {
			$sidebar['menu_item'] = 'All Cause List';
			$sidebar['menu_group'] = 'Case Management';
		}
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		if ($page_status != 0) {

			$data['page_title'] = 'All Cause List';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');
			$data['all_case_list'] = $this->causelist_model->get('', 'get_all_cases_list');
			$data['arbitrators_list'] = $this->causelist_model->get(null, 'get_arbitrators_list');
			// Get all data
			$data['cases_list'] = $this->causelist_model->get('', 'get_all_cases_list');
			// echo '<pre>';
			// print_r($data['cases_list']);
			// die;

			$this->load->view('cause_list/all-cause-list.php', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
}
