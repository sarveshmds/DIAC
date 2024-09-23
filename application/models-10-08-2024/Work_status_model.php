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

    public function get_pending_works_for_calender($user_code)
    {
        // Subquery to get the latest noting date for each case where marked_by is the specified user
        $subquery = $this->db->query("SELECT a.case_no AS case_no_slug, a.next_date,a.marked_to, a.noting_date,
        marked_by_user.user_display_name AS marked_by_display_name,
                    marked_by_user.job_title AS marked_by_job_title,
                    marked_to_user.user_display_name AS marked_to_display_name,
                    marked_to_user.job_title AS marked_to_job_title,
                    case_details.case_no,
                    case_details.case_no_prefix,
                    case_details.case_no_year,  gcd.description AS noting_text
        FROM `cs_noting_tbl` a INNER JOIN 
        (SELECT MAX(id) AS idd,case_no FROM cs_noting_tbl GROUP BY case_no)b
        ON a.id=b.idd 
        LEFT JOIN user_master AS marked_by_user ON marked_by_user.`user_code` = a.`marked_by`
        LEFT JOIN user_master AS marked_to_user ON marked_to_user.`user_code` = a.`marked_to`
        LEFT JOIN cs_case_details_tbl AS case_details ON case_details.slug = a.`case_no`
        LEFT JOIN gen_code_desc AS gcd ON gcd.gen_code = a.noting_text_code AND gcd.gen_code_group = 'NOTING_SMART_TEXT'
        WHERE marked_to = '" . $user_code . "' AND a.task_done=0 ORDER BY a.noting_date DESC")->result_array();

        // Return the results
        return $subquery;
    }

    public function get_pending_works($user_code)
    {
        // Subquery to get the latest noting date for each case where marked_by is the specified user
        $subquery = $this->db->query("SELECT a.case_no AS case_no_slug, a.noting, a.next_date,a.marked_to, a.noting_date,
        marked_by_user.user_display_name AS marked_by_display_name,
                    marked_by_user.job_title AS marked_by_job_title,
                    marked_to_user.user_display_name AS marked_to_display_name,
                    marked_to_user.job_title AS marked_to_job_title,
                    case_details.case_no,
                    case_details.case_no_prefix,
                    case_details.case_no_year,  gcd.description AS noting_text
        FROM `cs_noting_tbl` a INNER JOIN 
        (SELECT MAX(id) AS idd,case_no FROM cs_noting_tbl GROUP BY case_no)b
        ON a.id=b.idd 
        LEFT JOIN user_master AS marked_by_user ON marked_by_user.`user_code` = a.`marked_by`
        LEFT JOIN user_master AS marked_to_user ON marked_to_user.`user_code` = a.`marked_to`
        LEFT JOIN cs_case_details_tbl AS case_details ON case_details.slug = a.`case_no`
        LEFT JOIN gen_code_desc AS gcd ON gcd.gen_code = a.noting_text_code AND gcd.gen_code_group = 'NOTING_SMART_TEXT'
        WHERE marked_to = '" . $user_code . "' AND a.task_done=0 ORDER BY a.noting_date DESC")->result_array();

        // Return the results
        return $subquery;
    }

    public function get_completed_works($user_code)
    {
        $subquery = $this->db->query("SELECT a.case_no AS case_no_slug, a.noting, a.next_date,a.marked_to, 
        a.noting_date,
        marked_by_user.user_display_name AS marked_by_display_name,
                    marked_by_user.job_title AS marked_by_job_title,
                    marked_to_user.user_display_name AS marked_to_display_name,
                    marked_to_user.job_title AS marked_to_job_title,
                    case_details.case_no,
                    case_details.case_no_prefix,
                    case_details.case_no_year,  gcd.description AS noting_text
        FROM `cs_noting_tbl` a INNER JOIN 
        (SELECT MAX(id) AS idd,case_no FROM cs_noting_tbl WHERE marked_by = '" . $user_code . "' GROUP BY case_no)b ON a.id=b.idd 
        LEFT JOIN user_master AS marked_by_user ON marked_by_user.`user_code` = a.`marked_by`
        LEFT JOIN user_master AS marked_to_user ON marked_to_user.`user_code` = a.`marked_to`
        LEFT JOIN cs_case_details_tbl AS case_details ON case_details.slug = a.`case_no`
        LEFT JOIN gen_code_desc AS gcd ON gcd.gen_code = a.noting_text_code AND gcd.gen_code_group = 'NOTING_SMART_TEXT'
        WHERE marked_by = '" . $user_code . "' ORDER BY a.noting_date DESC")->result_array();

        // Return the results
        return $subquery;
    }
}
