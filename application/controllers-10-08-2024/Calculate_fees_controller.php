<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Calculate_fees_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        # libraries
        $this->load->library('form_validation');
        $this->load->library('user_agent');
        # helper
        $this->load->helper('custom_page');
        # models
        $this->load->model('admin_model');
        $this->load->model('getter_model');
        $this->load->library('calculate_fees');
    }

    public function new_reference_caluclate_fees()
    {
        $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required|xss_clean');
        $this->form_validation->set_rules('arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
        $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required|xss_clean');
        $this->form_validation->set_rules('case_type', 'Case Type', 'required|xss_clean');

        if (!$this->form_validation->run()) {
            echo json_encode(array(
                'status' => 'validation_error',
                'msg' => validation_errors()
            ));
            die;
        }

        $claim_amount = $this->input->post('claim_amount');
        $arbitral_tribunal_strength = $this->input->post('arbitral_tribunal_strength');
        $type_of_arbitration = $this->input->post('type_of_arbitration');
        $case_type = $this->input->post('case_type');

        $fees = $this->calculate_fees->new_reference_caluclate_fees($claim_amount, $arbitral_tribunal_strength, $type_of_arbitration, $case_type);

        if ($fees == false) {
            echo json_encode([
                'status' => false,
                'msg' => 'Invalid claim amount'
            ]);
            die;
        }

        echo json_encode([
            'status' => true,
            'data' => $fees
        ]);
        die;
    }

    public function case_caluclate_fees()
    {
        $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required|xss_clean');
        $this->form_validation->set_rules('arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
        $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required|xss_clean');

        if (!$this->form_validation->run()) {
            $output = array(
                'status' => 'validation_error',
                'msg' => validation_errors()
            );
            die;
        }

        $claim_amount = $this->input->post('claim_amount');
        $arbitral_tribunal_strength = $this->input->post('arbitral_tribunal_strength');
        $type_of_arbitration = $this->input->post('type_of_arbitration');

        $fees = $this->calculate_fees->case_caluclate_fees($claim_amount, $arbitral_tribunal_strength, $type_of_arbitration);

        if ($fees == false) {
            echo json_encode([
                'status' => false,
                'msg' => 'Invalid claim amount'
            ]);
            die;
        }

        echo json_encode([
            'status' => true,
            'data' => $fees
        ]);
        die;
    }

    public function case_caluclate_fees_seperate_assessed()
    {
        $this->form_validation->set_rules('claim_amount', 'Claim Amount', 'required|xss_clean');
        $this->form_validation->set_rules('counter_claim_amount', 'Counter Claim Amount', 'required|xss_clean');
        $this->form_validation->set_rules('arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');
        $this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required|xss_clean');

        if (!$this->form_validation->run()) {
            $output = array(
                'status' => 'validation_error',
                'msg' => validation_errors()
            );
            echo json_encode($output);
            die;
        }

        $claim_amount = $this->input->post('claim_amount');
        $counter_claim_amount = $this->input->post('counter_claim_amount');
        $arbitral_tribunal_strength = $this->input->post('arbitral_tribunal_strength');
        $type_of_arbitration = $this->input->post('type_of_arbitration');

        $fees = $this->calculate_fees->case_caluclate_fees_seperate_assessed($claim_amount, $counter_claim_amount, $arbitral_tribunal_strength, $type_of_arbitration);

        if ($fees == false) {
            echo json_encode([
                'status' => false,
                'msg' => 'Invalid claim or counter amount'
            ]);
            die;
        }

        echo json_encode([
            'status' => true,
            'data' => $fees
        ]);
        die;
    }
}
