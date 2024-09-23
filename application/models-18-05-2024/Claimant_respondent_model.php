<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Claimant_respondent_model extends CI_Model
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

    public function get_cla_res_basic_info_using_case_and_slug($case_no, $type)
    {
        return $this->db->select("name, count_number, email, contact")
            ->from('cs_claimant_respondant_details_tbl')
            ->where('case_no', $case_no)
            ->where('type', $type)
            ->where('status', 1)
            ->get()
            ->result_array();
    }

    public function get_claimant_respondant_using_case_and_slug($case_no, $type)
    {
        return $this->db->select("crt.*,cnt.name as country_name,st.name as state_name")
            ->from('cs_claimant_respondant_details_tbl as crt')
            ->join('countries as cnt', 'cnt.iso2 = crt.perm_country', 'left')
            ->join('states as st', 'st.id = crt.perm_state', 'left')
            ->where('crt.case_no', $case_no)
            ->where('crt.type', $type)
            ->where('crt.status', 1)
            ->order_by('crt.count_number', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_claimant_respondant_using_code_and_type($code, $type)
    {
        return $this->db->select("crt.*,cnt.name as country_name,st.name as state_name")
            ->from('cs_claimant_respondant_details_tbl as crt')
            ->join('countries as cnt', 'cnt.iso2 = crt.perm_country', 'left')
            ->join('states as st', 'st.id = crt.perm_state', 'left')
            ->where('crt.code', $code)
            ->where('crt.type', $type)
            ->where('crt.status', 1)
            ->order_by('crt.count_number', 'ASC')
            ->get()
            ->row_array();
    }
}
