<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tag_cases_model extends CI_Model
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

    public function get_all_other_noting_messages_datatable()
    {

        $this->db->select("ctc.*, cdt.case_no_prefix, cdt.case_no, cdt.case_no_year, cdt.case_title as master_case_title, cdt2.case_no as tagged_case_desc, cdt2.case_title as tagged_case_title, GROUP_CONCAT(cdt2.case_no_prefix, '/', cdt2.case_no, '/', cdt2.case_no_year)  as tagged_cases, um.user_display_name, um.job_title");
        $this->db->from('cs_tagged_cases_tbl AS ctc');
        $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ctc.master_case', 'left');
        $this->db->join('cs_case_details_tbl as cdt2', 'cdt2.slug = ctc.tagged_case', 'left');
        $this->db->join('user_master as um', 'um.user_code = ctc.created_by', 'left');
        $this->db->where('ctc.record_status', 1);
        $this->db->group_by('ctc.master_case');
        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        $fetch_data = $query->result();

        // ==========================================================
        // Pagination
        // ==========================================================
        // Filter records
        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('cs_tagged_cases_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        return $output;
    }

    public function insert_tagged_case()
    {
        $data = [];
        foreach ($this->input->post('tc_tagged_case') as $key => $tagged_case) {
            array_push($data, [
                'code' => generateCode(),
                'master_case' => $this->input->post('tc_master_case'),
                'tagged_case' => $tagged_case,
                'created_by' => $this->user_code,
                'created_at' => $this->date,
                'updated_by' => $this->user_code,
                'updated_at' => $this->date
            ]);
        }

        return $this->db->insert_batch('cs_tagged_cases_tbl', $data);
    }

    public function get_tagged_case_details_using_master_case($master_case)
    {
        $this->db->select("ctc.*, cdt.case_no as master_case_desc, cdt.case_title as master_case_title, cdt2.case_no as tagged_case_desc, cdt2.case_title as tagged_case_title");
        $this->db->from('cs_tagged_cases_tbl AS ctc');
        $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ctc.master_case', 'left');
        $this->db->join('cs_case_details_tbl as cdt2', 'cdt2.slug = ctc.tagged_case', 'left');
        $this->db->where('ctc.record_status', 1);
        $this->db->where('ctc.master_case', $master_case);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function check_tag_cases($update_case = false)
    {
        $master_case = $this->input->post('tc_master_case');
        $tagged_cases = $this->input->post('tc_tagged_case');

        foreach ($tagged_cases as $key => $tagged_case) {
            // Check if any of the tagged case is in master case
            $mc_result = $this->db->select('id')->where([
                'master_case' => $tagged_case,
                'record_status' => 1,
            ])->from('cs_tagged_cases_tbl')->count_all_results();

            if ($mc_result > 0) {
                return [
                    'status' => false,
                    'msg' => 'Tagged case is already a master case. You can not add a tagged case as a master case'
                ];
            }

            if ($update_case == false) {
                // Check if the case is already tagged with master case
                $mc_result2 = $this->db->select('id')->where([
                    'master_case' => $master_case,
                    'tagged_case' => $tagged_case,
                    'record_status' => 1,
                ])->from('cs_tagged_cases_tbl')->count_all_results();

                if ($mc_result2 > 0) {
                    return [
                        'status' => false,
                        'msg' => 'Master case is already tagged with the case. You can not tag a case twice.'
                    ];
                }
            }
        }

        // Check if master case is in tagged case
        $mc_result3 = $this->db->select('id')->where([
            'tagged_case' => $master_case,
            'record_status' => 1,
        ])->from('cs_tagged_cases_tbl')->count_all_results();

        if ($mc_result3 > 0) {
            return [
                'status' => false,
                'msg' => 'Master case is already tagged in a case. You can not add a master case as a tagged case'
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function delete_tagged_cases_using_master_case($master_case)
    {
        return $this->db->where('master_case', $master_case)->update('cs_tagged_cases_tbl', ['record_status' => 0]);
    }
}
