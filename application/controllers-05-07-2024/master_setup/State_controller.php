<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class State_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'common_model', 'category_model', 'getter_model', 'master_setup/state_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('index'),
            'PUBLIC' => array('fetchStatesUsingCountryId')
        );

        if ($role == false) {
            $role = 'PUBLIC';
            $check_user = true;
        }

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('logout');
        } else {
            if (in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
                $uri = $this->uri->segment_array();
                unset($uri[1]);
                unset($uri[2]);
                call_user_func_array(array($this, $method), $uri);
            } else {
                return redirect('page-not-found');
            }
        }
    }

    public function fetchStatesUsingCountryId()
    {
        if ($this->input->post('country_code')) {
            $states = $this->state_model->getAllStatesUsingCountryId($this->input->post('country_code'));
            echo json_encode([
                'status' => true,
                'data' => $states
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'msg' => 'Country is required to fetch the states'
            ]);
        }
    }
}
