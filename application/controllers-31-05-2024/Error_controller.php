<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Error_controller extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');

		# views
		$data['title'] = $this->getter_model->get(null,'get_title');
	}	

	public function page_not_found(){
		$this->load->view('templates/404');
	}
}