<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grievance_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([ 'Getter_model']);
	}

	public function index()
	{
		$data['arb_opt'] =$this->getter_model->get(null, 'GET_GRIEVANCE_ARB_OPT');
		$data['dc_cm_st_opt'] =$this->getter_model->get(null, 'GET_GRIEVANCE_DC_CM_ST_OPT');
		$data['pantry_others_opt'] =$this->getter_model->get(null, 'GET_GRIEVANCE_PANTRY_OTHER');
		// print_r($data['dc_cm_st_opt']);
		// die;
		$this->load->view('templates/efiling/efiling-auth-header');
        $this->load->view('efiling/public/grievance',$data);
        $this->load->view('templates/efiling/efiling-auth-footer');
	}

	public function store_grievance_form()
	{
		$inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'csrf_grievance_form')) {
                // Validation
                $this->form_validation->set_value('name_of_arb','Name of Arbitrator','required');
                $this->form_validation->set_value('diac_case_no','DIAC Case Number','required');
                $this->form_validation->set_value('name_of_form_filled_by','Name of person filling the Feedback form','required');
                $this->form_validation->set_value('participated_arb_counsel','Whether you have participated in arbitration as a counsel or party','required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('phone', 'Phone Number', 'required|min_length[10]|max_length[12]');
                $this->form_validation->set_value('arb_grievance_opt','Grievance Arbitrator ticks','required');
                $this->form_validation->set_value('grievance_dc_cm_st','Grievance Deputy Counsel’s/Case Manager’s/Stenographers ticks','required');
                $this->form_validation->set_value('grievance_pantry_other','Grievance Pantry and other staff ticks','required');
                $this->form_validation->set_value('grievance_comments','Name of Arbitrator','required');

                if ($this->form_validation->run() == TRUE) {

                	$ticket_no = 'DIAC-'.date('Y-m').'-'.rand(9,99).date("is");
                	$code_for_grv = generateCode();

                	// Grievance Redressal table data
                	$grievance_tbl_data=array(
                		'code' => $code_for_grv,
                		'ticket_id' => $ticket_no,
                		'name_of_arbitrator' => $this->input->post('name_of_arb'),
                		'diac_case_no' => $this->input->post('diac_case_no'),
                		'name_of_form_filled_by' => $this->input->post('name_of_form_filled_by'),
                		'whether_participated_in_arbitration' => $this->input->post('participated_arb_counsel'),
                		'phone' => $this->input->post('phone'),
                		'email' => $this->input->post('email'),
                		'comments' => $this->input->post('grievance_comments'),
                		'created_by' => $this->input->post('email'),
                	);

                	$result = $this->db->insert('grievance_redressal_tbl', $grievance_tbl_data);
                	if ($result) {

	                	// Arbitrators options 
	                	$grievance_arb_opt = $this->input->post('arb_grievance_opt');
	                	if (count($grievance_arb_opt) > 0) {
	                		$grievance_arb_data = [];
	                		foreach ($grievance_arb_opt as $arb) {
	                		 	array_push($grievance_arb_data,[
	                		 		'code' => generateCode(),
	                		 		'grievance_code' => $code_for_grv,
	                		 		'type' => 'GRIEVANCE_ARB_OPTIONS',
	                		 		'options' => $arb,
	                		 	]);
	                		 } 
	                		 if (count($grievance_arb_data) > 0) {
	                		 	$grievance_arb_re = $this->db->insert_batch('grievance_options_tbl', $grievance_arb_data);
	                		 	if (!$grievance_arb_re) {
	                		 		echo  json_encode([
			                            'status' => false,
			                            'msg' => 'Grievance Arbitrator checkbox error!']);
	                		 	}
	                		 }
	                	}
		
	                	// Dc Mc St Options
	                	$grievance_dc_opt = $this->input->post('grievance_dc_cm_st');
	                	if (count($grievance_dc_opt) > 0) {
	                		$grievance_dc_data = [];
	                		foreach ($grievance_dc_opt as $arb) {
	                		 	array_push($grievance_dc_data,[
	                		 		'code' => generateCode(),
	                		 		'grievance_code' => $code_for_grv,
	                		 		'type' => 'GRIEVANCE_DC_CM_ST_OPT',
	                		 		'options' => $arb,
	                		 	]);
	                		 } 
	                		 if (count($grievance_dc_data) > 0) {
	                		 	$grievance_arb_re = $this->db->insert_batch('grievance_options_tbl', $grievance_dc_data);
	                		 	if (!$grievance_arb_re) {
	                		 		echo  json_encode([
			                            'status' => false,
			                            'msg' => 'Grievance Deputy Counsel’s/Case Manager’s/Stenographers checkbox error!']);
	                		 	}
	                		 }
	                	}

	                	// Pantry and Other's Options
	                	$grievance_pantry_opt = $this->input->post('grievance_pantry_other');
	                	if (count($grievance_pantry_opt) > 0) {
	                		$grievance_pantry_data = [];
	                		foreach ($grievance_pantry_opt as $arb) {
	                		 	array_push($grievance_pantry_data,[
	                		 		'code' => generateCode(),
	                		 		'grievance_code' => $code_for_grv,
	                		 		'type' => 'GRIEVANCE_PANTRY_OTHER',
	                		 		'options' => $arb,
	                		 	]);
	                		 } 
	                		 if (count($grievance_pantry_data) > 0) {
	                		 	$grievance_arb_re = $this->db->insert_batch('grievance_options_tbl', $grievance_pantry_data);
	                		 	if (!$grievance_arb_re) {
	                		 		echo  json_encode([
			                            'status' => false,
			                            'msg' => 'Grievance Arbitrator checkbox error!']);
	                		 	}
	                		 	// echo "<pre>";
			                	// print_r($grievance_arb_data);
	                		 	// echo "</pre>";
			                	// die;
	                		 }
	                	}

	                	echo  json_encode([
                            'status' => true,
                            'msg' => 'Your Grievance Redressal Form has been submmited and your Ticket Id is '.$ticket_no
                        ]);
                		
                	}
                	else
                	{
                		echo  json_encode([
                            'status' => false,
                            'msg' => 'Server failed while saving data'
                        ]);
                	}

                } else {
                    echo  json_encode([
                        'status' => 'validation_error',
                        'msg' => validation_errors()
                    ]);
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
	}

}

/* End of file Grievance_controller.php */
/* Location: ./application/controllers/Grievance_controller.php */
