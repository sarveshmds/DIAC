<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_controller extends CI_Controller{
	
	public function __construct() {
		parent::__construct();
		
		# Model
		$this->load->model(['superadmin_model', 'admin_model']);

		// Get notification
		$data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');
		
		# views
		$data['title'] = $this->getter_model->get(null,'get_title');
		$this->load->view('templates/header',$data);

	}

	/*
	*	purpose : Handle page not found
	*/
	public function page_not_found()
	{
		$this->load->view('templates/404.php');
		$this->load->view('templates/admin_footer');
	}
	public function _remap($method){

		$class = $this->router->class;
		$role = $this->session->userdata('role');
		$check_user = $this->getter_model->get(null,'get_user_check');
		$role_action_auth = array( 
			'SUPERADMIN'=>array('dashboard','my_account','manage_resource','manage_user','manage_group','title_setup','audit_logs','page_creation','approval_user_create'),
			'ADMIN'=>array('my_account','approval_user_create','audit_logs','manage_user'),
			'DIAC'=>array('my_account', 'manage_user'),
			'ACCOUNTS'=>array('my_account'),
			'CASE_FILER'=>array('my_account'),
			'CASE_MANAGER'=>array('my_account'),
			'CAUSE_LIST_MANAGER'=>array('my_account'),
			'COORDINATOR'=>array('my_account'),
			'POA_MANAGER'=>array('my_account'),
			'DEPUTY_COUNSEL'=>array('my_account')
			
		);
		if( $role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user){
			redirect('logout');
		}else{
			if (in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))){
				$uri = $this->uri->segment_array();
				// print_r($uri);die;
				unset($uri[1]);
				unset($uri[2]);
				call_user_func_array(array($this, $method), $uri);
			}else{
				self::page_not_found();
			}
		}
	}
	
	/**
	*	purpose : User Login
	*/
	public function dashboard(){
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		$data['get_rolewise_user'] = $this->superadmin_model->superadmin(null,'GET_ROLEWISE_USER');
		$data['get_login_current_date'] = $this->superadmin_model->superadmin(null,'GET_LOGIN_CURRENT_DATE');
		if($page_status!= 0) {
			$this->load->view('super_admin/dashboard',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	
 	public function manage_resource(){
  		$sidebar['menu_item'] = 'Manage Resource';
  		$sidebar['menu_group'] = 'Setting';
  		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
  		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/manage_resource');
		}else{
			$this->load->view('templates/page_maintenance');
		}
  		$this->load->view('templates/footer');
 	}
 	public function manage_user(){
		$sidebar['menu_item'] = 'Manage User';
		if($this->session->userdata('role') != 'SUPERADMIN'){
			$sidebar['menu_group'] = 'Manage User';
		}
		else{
			$sidebar['menu_group'] = 'Setting';
		}
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$this->load->view('templates/side_menu',$sidebar);
		$data['all_state_list'] = $this->getter_model->get(array('country_code'=>'IND'),'get_state_name');
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$data['all_role_data'] = $this->admin_model->importData('GET_ALL_ROLE_GROUP');
			$this->load->view('super_admin/manage_user',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function manage_group(){
  		$sidebar['menu_item'] = 'Manage Group';
		$sidebar['menu_group'] = 'Setting';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
  		$viewdata['table_data'] = $this->superadmin_model->superadmin(NULL,'get_table');
		$viewdata['all_role_data'] = $this->superadmin_model->superadmin(NULL,'get_role');
  		$viewdata['all_user_data'] = $this->superadmin_model->superadmin(NULL,'get_user_code');
  		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/manage_group',$viewdata);
		}else{
			$this->load->view('templates/page_maintenance');
		}
  		$this->load->view('templates/footer');
 	}
 	public function title_setup(){
  		$sidebar['menu_item'] = 'Title Setup';
		$sidebar['menu_group'] = 'Setting';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
  		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/title_setup');
		}else{
			$this->load->view('templates/page_maintenance');
		}
  		$this->load->view('templates/footer');
 	}
 	public function my_account(){
		$sidebar['menu_item'] 	= '';
		$sidebar['menu_group'] 	= '';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
		$data['get_dept_details']		= $this->superadmin_model->superadmin(null,'GET_ACCOUNT_DETAILS');
		$this->load->view('super_admin/my_account',$data);
		$this->load->view('templates/footer');
	}
	public function audit_logs(){
		$sidebar['menu_item'] 	= 'Audit Logs';
		$sidebar['menu_group'] 	= 'Audit Logs';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
  		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/login_details');
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function page_creation(){
		$sidebar['menu_item'] = 'Page Creation';
		$sidebar['menu_group'] = 'Setting';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$this->load->view('templates/side_menu',$sidebar);
		$data['get_view_folder_details']= $this->superadmin_model->superadmin(null,'GET_VIEW_FOLDER_DETAILS');
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/page_creation',$data);
		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	
	public function approval_user_create(){
		$sidebar['menu_item'] = 'Approval User Create';
		$sidebar['menu_group'] = 'Setting';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
  		$this->load->view('templates/side_menu',$sidebar);
  		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);
		if($page_status!= 0) {
			$this->load->view('super_admin/approval_user_create');
		}else{
			$this->load->view('templates/page_maintenance');
		}
  		$this->load->view('templates/footer');
	}
}