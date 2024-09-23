<?php defined('BASEPATH') or exit('No direct script access allowed');

class Smtp_setup_model extends CI_Model
{
    public $date;
    function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }
        date_default_timezone_set('Asia/Kolkata');
        $this->date = date('Y-m-d H:i:s', time());

        $this->role         = $this->session->userdata('role');
        $this->user_code         = $this->session->userdata('user_code');
        $this->user_name     = $this->session->userdata('user_name');
    }

    public function get_smtp_setup_using_id($provider_id)
    {
        $result = $this->db->select('*')->where('provider_id', $provider_id)->from('email_provider_setup')->get()->row_array();
        return $result;
    }
}
