<?php defined('BASEPATH') or exit('No direct script access allowed');

class Vakalatnama_model extends CI_Model
{
    public function insert($data_arr)
    {
        return $this->db->insert('efiling_vakalatnama_tbl', $data_arr);
    }

    public function checkCount()
    {
        $this->db->select('*');
        $this->db->from('efiling_vakalatnama_tbl');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_single_request_using_id($id)
    {
        return $this->db->from('efiling_vakalatnama_tbl')->where('id', $id)->get()->row_array();
    }

    public function update($data_arr, $id)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('efiling_vakalatnama_tbl', $data_arr);
        return $query;
    }
    public function get_all_vakalatnama_list($case_no)
    {
        $this->db->select('eat.*, cdt.case_no as case_no_desc, cdt.case_title as case_title_desc, ccrdt.name as claimant_respondent_name, gcd.description as behalf_of_desc');
        $this->db->from('efiling_vakalatnama_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.claimant_respondent_id", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.behalf AND gcd.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->order_by('eat.id', 'DESC');
        $this->db->where('eat.case_no', $case_no);
        $this->db->where('eat.application_status', 1);
        return $this->db->get()->result_array();
    }
}
