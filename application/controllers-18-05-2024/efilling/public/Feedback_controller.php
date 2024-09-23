<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('templates/efiling/efiling-auth-header');
        $this->load->view('efiling/public/feedback');
        $this->load->view('templates/efiling/efiling-auth-footer');
	}

	public function feedback_save()
	{
		$inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'csrf_feedback_form')) {
                // Validation
	            $this->form_validation->set_rules('name_of_arb', 'Name of Arbitrator', 'required');
				$this->form_validation->set_rules('diac_case_no', 'DIAC Case Number', 'required');
				$this->form_validation->set_rules('name_of_adv', 'Name of Advocate', 'required');
				$this->form_validation->set_rules('enroll_no_of_adv', 'Enrollment Number of Advocate', 'required');
				$this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|min_length[10]|max_length[10]');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
				$this->form_validation->set_rules('understanding_dispute', 'Understanding of the dispute', 'required');
				$this->form_validation->set_rules('manage_arb_process', 'Management of the arbitration process', 'required');
				$this->form_validation->set_rules('substantive_law', 'Expertise in procedural and substantive law', 'required');
				$this->form_validation->set_rules('treatment_of_party', 'Equal Treatment of the parties', 'required');
				$this->form_validation->set_rules('diac_ambience_clean', 'Ambience, cleanliness and overall upkeep of the Centre', 'required');
				$this->form_validation->set_rules('diac_assist_dc_cm', 'Assistance rendered by Deputy Counsels/Case Managers in case management', 'required');
				$this->form_validation->set_rules('diac_assist_st_other', 'Assistance rendered by stenographers and other DIAC staff', 'required');
				$this->form_validation->set_rules('diac_it_infra', 'Functioning of Information Technology infrastructure at the Centre', 'required');


                if ($this->form_validation->run() == TRUE) {

                	$feedback_data = array(
                		'code' => generateCode(),
					    'name_of_arbitrator' => $this->input->post('name_of_arb'),
					    'diac_case_number' => $this->input->post('diac_case_no'),
					    'name_of_advocate' => $this->input->post('name_of_adv'),
					    'enroll_number_of_advocate' => $this->input->post('enroll_no_of_adv'),
					    'mobile' => $this->input->post('mobile'),
					    'email' => $this->input->post('email'),
					    'understanding_dispute' => $this->input->post('understanding_dispute'),
					    'manage_arb_process' => $this->input->post('manage_arb_process'),
					    'substantive_law' => $this->input->post('substantive_law'),
					    'treatment_of_party' => $this->input->post('treatment_of_party'),
					    'diac_ambience_clean' => $this->input->post('diac_ambience_clean'),
					    'diac_assist_dc_cm' => $this->input->post('diac_assist_dc_cm'),
					    'diac_assist_st_other' => $this->input->post('diac_assist_st_other'),
					    'diac_it_infra' => $this->input->post('diac_it_infra'),
					    'created_by' => $this->input->post('email'),
					    'updated_by' => $this->input->post('email'),
					);

                	$result = $this->db->insert('feedback_tbl', $feedback_data);
                	if ($result) {
	                	echo  json_encode([
                            'status' => true,
                            'msg' => 'Your Feedback Form has been submmited! Thank you for providing your feedback.'
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

/* End of file Feedback_controller.php */
/* Location: ./application/controllers/Feedback_controller.php */