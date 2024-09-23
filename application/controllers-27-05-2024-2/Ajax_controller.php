
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ajax_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        # helpers
        $this->load->helper(array('form'));

        # libraries
        $this->load->library('encryption');

        # models
        $this->load->model(['superadmin_model', 'common_model']);
    }

    // Get all claimant and respondant details
    public function get_claimant_respondent_separately()
    {
        echo json_encode($this->common_model->get_claimant_respondent_separately($this->input->post('case_no')));
    }



    // public function edit($id)
    // {
    //     $data['page_title'] = 'Edit Application';
    //     $this->load->view('templates/efiling/efiling-header', $data);
    //     $this->load->model('Add_application_model');
    //     $data2['application'] = $this->Add_application_model->case();
    //     $this->load->view('efiling/editapplication_view', $data2);
    //     $this->load->view('templates/efiling/efiling-footer');
    // }
}
