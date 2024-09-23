<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Causelist_controller extends CI_Controller  {
	public function __construct(){
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");
		
		# models
		$this->load->model(array('common_model', 'causelist_model', 'category_model'));
		
		// Get notification
		$data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

		# views
		$data['title'] = $this->getter_model->get(null,'get_title');
		$this->load->view('templates/header',$data);
		
	}

	/*
	*	purpose : to check whether the method is correct or not
	*/

	public function _remap($method){
		$class = $this->router->class;
		$role = $this->session->userdata('role');
		$check_user = $this->getter_model->get(null,'get_user_check');
		$role_action_auth = array(
			'ADMIN' => array('cause_list', 'rooms_list', 'hearings_today', 'display_board'),
			'DIAC' => array('cause_list', 'rooms_list', 'hearings_today', 'display_board'),
			'CASE_MANAGER' => array('cause_list', 'hearings_today'),
			'CASE_FILER' => array('cause_list', 'hearings_today'),
			'COORDINATOR' => array('cause_list', 'hearings_today'),
			'ACCOUNTS' => array('cause_list', 'hearings_today'),
			'CAUSE_LIST_MANAGER' => array('cause_list', 'rooms_list', 'hearings_today', 'display_board'),
			'POA_MANAGER' => array('cause_list', 'hearings_today'),
			'DEPUTY_COUNSEL' => array('cause_list', 'hearings_today'),
		);
		
		if( $role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user){
			redirect('logout');
			//self::page_not_found();
		}else{
			if (in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))){
				$uri = $this->uri->segment_array();
				unset($uri[1]);
				unset($uri[2]);
				call_user_func_array(array($this, $method), $uri);
			}else{
				return redirect('page-not-found');
			}
		}
	}

	public function page_not_found(){
		$this->load->view('templates/404');
	}

	public function cause_list(){
		$sidebar['menu_item'] = 'Cause List';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		if($page_status!= 0) {

			$data['page_title'] = 'Cause List';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');

			$this->load->view('diac-admin/cause-list.php',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function hearings_today(){
		$sidebar['menu_item'] = 'Hearings Today';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		if($page_status!= 0) {

			$data['page_title'] = 'Hearings Today';

			$this->load->view('diac-admin/hearings-today.php',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function display_board(){
		$sidebar['menu_item'] = 'Display Board';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		if($page_status!= 0) {

			$data['page_title'] = 'Display Board for ('. date('d-M, Y').')';
			$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');
			$data['purpose_category'] = $this->category_model->get('', 'GET_ALL_PURPOSE_CATEGORY');

			$this->load->view('diac-admin/display-board.php',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}


	public function rooms_list(){
		$sidebar['menu_item'] = 'Rooms List';
		$sidebar['menu_group'] = 'Rooms Availability';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		$data['page_title'] = 'Rooms List';
		$data['count'] = 1;
		$data['all_rooms_list'] = $this->causelist_model->get('', 'ALL_ROOMS_LIST');

		if($page_status!= 0) {
			$this->load->view('diac-admin/rooms-list.php',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

}