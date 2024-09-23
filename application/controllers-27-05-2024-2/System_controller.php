<?php defined('BASEPATH') OR exit('No direct script access allowed');
include APPPATH . 'third_party'.DIRECTORY_SEPARATOR.'dompdf'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use Dompdf\Dompdf;

class System_controller extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();
		
		date_default_timezone_set('Asia/Kolkata');

		// Get notification
		$data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

		# views
		$data['title'] = $this->getter_model->get(null,'get_title');
		$this->load->view('templates/header',$data);
	}	

	public function _remap($method){
		$class = $this->router->class;
		$role = $this->session->userdata('role');
		$check_user = $this->getter_model->get(null,'get_user_check');
		$role_action_auth = array( 
			'DIAC'=>array('data_logs'),
			'ADMIN'=>array('data_logs'),
			'SUPERADMIN' => array('data_logs')
		);
		if( $role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user){
			self::page_not_found();
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
	
	public function data_logs(){
		$sidebar['menu_item'] = 'Data Logs';
		$sidebar['menu_group'] = 'Data Logs';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar,'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null,'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null,'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null,'GET_TOTAL_CASES_CURRENT_MONTH');
		
		$this->load->view('templates/side_menu',$sidebar);
		$page_status = page_status($sidebar['sidebar'],$sidebar['menu_item']);

		$data['page_title'] = 'Data Logs';

		if($page_status!= 0) {

			$this->load->view('diac-admin/data-logs.php',$data);

		}else{
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function page_not_found(){
		$this->load->view('templates/404');
	}
}