<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function display_board()
	{
		$data['display_board_list'] = $this->getter_model->get('', 'GET_DISPLAY_BOARD_DATA');
		$this->load->view('show-display-board', $data);
	}

	public function get_display_board_data(){
		$display_board_list = $this->getter_model->get('', 'GET_DISPLAY_BOARD_DATA');
		echo json_encode([
			'status' => true,
			'data' => $display_board_list
		]);
	}
}
