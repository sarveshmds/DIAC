<?php
defined('BASEPATH') or exit("No direct scripts are allowed");
class Fees_calculator_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fees_calculator_view()
    {
        $this->load->view('templates/efiling/efiling-auth-header');
        $this->load->view('efiling/public/fees_calculator/fees_calculator');
        $this->load->view('templates/efiling/efiling-auth-footer');
    }

    public function fees_calculator_nov_2022()
    {
        $this->load->view('templates/efiling/efiling-auth-header');
        $this->load->view('efiling/public/fees_calculator/fees_calculator_nov_2022');
        $this->load->view('templates/efiling/efiling-auth-footer');
    }

    public function fees_calculator_latest()
    {
        $this->load->view('templates/efiling/efiling-auth-header');
        $this->load->view('efiling/public/fees_calculator/fees_calculator_latest');
        $this->load->view('templates/efiling/efiling-auth-footer');
    }
}
