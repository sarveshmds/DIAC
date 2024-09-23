<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Poa_controller extends CI_Controller  {
	public function __construct(){
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");
		
		# models
		$this->load->model(array('common_model', 'poa_model', 'category_model'));
		
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
			'ADMIN' => array('panel_of_arbitrator'),
			'DIAC' => array('panel_of_arbitrator'),
			'CASE_MANAGER' => array('panel_of_arbitrator'),
			'CASE_FILER' => array('panel_of_arbitrator'),
			'COORDINATOR' => array('panel_of_arbitrator'),
			'ACCOUNTS' => array('panel_of_arbitrator'),
			'CAUSE_LIST_MANAGER' => array('panel_of_arbitrator'),
			'POA_MANAGER' => array('panel_of_arbitrator'),
			'DEPUTY_COUNSEL' => array('panel_of_arbitrator'),
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

	public function panel_of_arbitrator(){
		$sidebar['menu_item'] = 'Panel of Arbitrator';
		$sidebar['menu_group'] = 'Panel of Arbitrator';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		$data['page_title'] = 'Panel of Arbitrator';

		if($page_status!= 0) {
			$data['panel_category'] = $this->category_model->get('', 'GET_ALL_PANEL_CATEGORY');
			$this->load->view('diac-admin/panel-of-arbitrator.php',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

}