
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Case_types_model extends CI_Model
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

    /**
     * Get all empanelled arbitrator list from master table
     */
    public function getAllCaseTypes()
    {
        $this->db->select('*');
        $this->db->from('cs_case_types_tbl');
        $this->db->where('record_status', 1);
        $this->db->order_by('type_name', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }
}
