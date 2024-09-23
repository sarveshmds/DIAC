<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_controller extends CI_Controller  {
	public function __construct(){
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");
		
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
			'ADMIN' => array('notifications'),
			'DIAC' => array('notifications'),
			'CASE_MANAGER' => array('notifications'),
			'CASE_FILER' => array('notifications'),
			'COORDINATOR' => array('notifications'),
			'ACCOUNTS' => array('notifications'),
			'CAUSE_LIST_MANAGER' => array('notifications'),
			'POA_MANAGER' => array('notifications'),
			'DEPUTY_COUNSEL' => array('notifications'),
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

	public function notifications(){
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		$data['page_title'] = 'Notifications';

		if($page_status!= 0) {

			// Mark all notification seen on loading the notification page for the current login user
			$this->notification_model->post('', 'MARK_NOTIFICATION_SEEN');

			$this->load->view('diac-admin/notifications.php',$data);

		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

}