
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class State_model extends CI_Model
{

    public $date;
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->load->helper('date');

        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }

        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());

        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
    }

    public function getAllStatesUsingCountryId($country_code)
    {
        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('country_code', $country_code);
        $this->db->order_by('name', 'asc');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }
}
