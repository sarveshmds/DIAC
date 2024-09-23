
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Arbitrator_setup_model extends CI_Model
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
    public function getAllEmpanelledArbitrator()
    {
        $this->db->select('mat.*, pct.category_name');
        $this->db->from('master_arbitrators_tbl as mat');
        $this->db->join('panel_category_tbl as pct', 'pct.code = mat.category', 'left');
        $this->db->where('mat.whether_on_panel', 1);
        $this->db->where('mat.approved', 1);
        $this->db->order_by('mat.name_of_arbitrator', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }

    /**
     * Get all empanelled arbitrator details using code
     */
    public function getArbitratorDetailsUsingCode($code)
    {
        $this->db->select('mat.*, pct.category_name');
        $this->db->from('master_arbitrators_tbl as mat');
        $this->db->join('panel_category_tbl as pct', 'pct.code = mat.category', 'left');
        $this->db->where('mat.code', $code);
        $res = $this->db->get();
        return $res->row_array();
    }

    function getArbitratorsLists($case_slug)
    {
        $this->db->select('mat.*, pct.category_name');
        $this->db->from('cs_arbitral_tribunal_tbl as at');
        $this->db->join('master_arbitrators_tbl as mat', 'mat.code = at.arbitrator_code', 'left');
        $this->db->join('panel_category_tbl as pct', 'pct.code = mat.category', 'left');
        // $this->db->where('mat.whether_on_panel', 1);
        $this->db->where('mat.approved', 1);
        $this->db->where('at.status', 1);
        $this->db->where('at.case_no', $case_slug);
        $result = $this->db->get();
        return $result->result_array();
    }
}
