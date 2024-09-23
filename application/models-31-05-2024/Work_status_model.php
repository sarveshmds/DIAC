<?php defined('BASEPATH') or exit('No direct script access allowed');

class Work_status_model extends CI_Model
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

    public function get_pending_works($user_code)
    {
        // Subquery to get the latest noting date for each case where marked_by is the specified user
        $subquery = $this->db
            ->select('case_no, MAX(noting_date) as latest_noting_date')
            ->from('cs_noting_tbl')
            ->where('marked_to', $user_code)
            ->group_by('case_no')
            ->get_compiled_select();

        // Main query to join the original table with the subquery results
        $this->db->select('
            t1.case_no as case_no_slug,
            t1.marked_to,
            t1.marked_by,
            t1.noting,
            gcd.description as noting_text,
            t1.noting_date,
            marked_by_user.user_display_name as marked_by_display_name,
            marked_by_user.job_title as marked_by_job_title,
            marked_to_user.user_display_name as marked_to_display_name,
            marked_to_user.job_title as marked_to_job_title,
            case_details.case_no,
            case_details.case_no_prefix,
            case_details.case_no_year
        ');
        $this->db->from('cs_noting_tbl t1');
        $this->db->join("($subquery) t2", 't1.case_no = t2.case_no AND t1.noting_date = t2.latest_noting_date', 'inner');
        $this->db->join('user_master marked_by_user', 't1.marked_by = marked_by_user.user_code', 'left');
        $this->db->join('user_master marked_to_user', 't1.marked_to = marked_to_user.user_code', 'left');
        $this->db->join('cs_case_details_tbl case_details', 't1.case_no = case_details.slug', 'left');
        $this->db->join('gen_code_desc as gcd', 'gcd.gen_code = t1.noting_text_code AND gcd.gen_code_group = "NOTING_SMART_TEXT"', 'left');
        $this->db->where('t1.marked_to', $user_code);

        $query = $this->db->get();

        // Return the results
        return $query->result_array();
    }

    public function get_completed_works($user_code)
    {
        // Subquery to get the latest noting date for each case where marked_by is the specified user
        $subquery = $this->db
            ->select('case_no, MAX(noting_date) as latest_noting_date')
            ->from('cs_noting_tbl')
            ->where('marked_by', $user_code)
            ->group_by('case_no')
            ->get_compiled_select();

        // Main query to join the original table with the subquery results
        $this->db->select('
            t1.case_no as case_no_slug,
            t1.marked_to,
            t1.marked_by,
            t1.noting,
            gcd.description as noting_text,
            t1.noting_date,
            marked_by_user.user_display_name as marked_by_display_name,
            marked_by_user.job_title as marked_by_job_title,
            marked_to_user.user_display_name as marked_to_display_name,
            marked_to_user.job_title as marked_to_job_title,
            case_details.case_no,
            case_details.case_no_prefix,
            case_details.case_no_year
        ');
        $this->db->from('cs_noting_tbl t1');
        $this->db->join("($subquery) t2", 't1.case_no = t2.case_no AND t1.noting_date = t2.latest_noting_date', 'inner');
        $this->db->join('user_master marked_by_user', 't1.marked_by = marked_by_user.user_code', 'left');
        $this->db->join('user_master marked_to_user', 't1.marked_to = marked_to_user.user_code', 'left');
        $this->db->join('cs_case_details_tbl case_details', 't1.case_no = case_details.slug', 'left');
        $this->db->join('gen_code_desc as gcd', 'gcd.gen_code = t1.noting_text_code AND gcd.gen_code_group = "NOTING_SMART_TEXT"', 'left');
        $this->db->where('t1.marked_by', $user_code);

        $query = $this->db->get();

        // Return the results
        return $query->result_array();
    }
}
