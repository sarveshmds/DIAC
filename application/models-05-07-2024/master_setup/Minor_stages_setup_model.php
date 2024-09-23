
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Minor_stages_setup_model extends CI_Model
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
     * Function to check for unique code in stages
     */
    public function checkForUniqueCode($code)
    {
        $this->db->select('*');
        $this->db->from('master_minor_stages_table');
        $this->db->where('code', $code);
        $this->db->order_by('sl_no', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }

    /**
     * Function to get all minor stages
     */
    public function get_all_stages()
    {
        $this->db->select('*');
        $this->db->from('master_minor_stages_table');
        $this->db->order_by('sl_no', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }

    /**
     * Function to get minor stages using major stage
     */
    public function get_stages_using_major_stage($major_stage_code)
    {
        $this->db->select('*');
        $this->db->from('master_minor_stages_table');
        $this->db->where('major_stage_code', $major_stage_code);
        $this->db->order_by('sl_no', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }
}
