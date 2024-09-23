
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nationality_model extends CI_Model
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

    public function getNationalityData()
    {
        $this->db->from('nationality_tbl');
        $this->db->select('*');
        $this->db->where('record_status', 1);
        return $this->db->get()->result_array();
    }
}
