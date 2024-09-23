<?php defined('BASEPATH') or exit('No direct script access allowed');

class Application_model extends CI_Model
{
    public function case()
    {
        $this->db->select('*');
        $query = $this->db->get('cs_case_details_tbl');
        return $query->result();
    }

    public function insert($data_arr)
    {
        return $this->db->insert('efiling_application_tbl', $data_arr);
    }

    public function fetchData()
    {
        $query = $this->db->get('efiling_application_tbl');
        return $query->result_array();
    }

    public function editData($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('efiling_application_tbl');
        return $query->result();
    }

    public function checkCount()
    {
        $this->db->select('*');
        $this->db->from('efiling_application_tbl');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_single_request_using_id($id)
    {
        return $this->db->from('efiling_application_tbl')->where('id', $id)->get()->row_array();
    }

    public function update($data_arr, $id)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('efiling_application_tbl', $data_arr);
        return $query;
    }

    public function get_single_application_using_code($code)
    {
        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, gcd.description as filing_type_desc, gcd2.description as behalf_of_desc, gcd3.description as app_type_desc, cdt.case_title');
        $this->db->from('efiling_application_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.claimant_respondent_id", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.filing_type AND gcd.gen_code_group = 'FILING_TYPE'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = eat.app_type AND gcd3.gen_code_group = 'APPLICATION_TYPE'", 'left');

        return $this->db->where('eat.a_code', $code)->get()->row_array();
    }

    public function get_all_applications_list($case_no)
    {
        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, gcd.description as filing_type_desc, gcd2.description as behalf_of_desc, gcd3.description as app_type_desc, cdt.case_title');
        $this->db->from('efiling_application_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.claimant_respondent_id", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.filing_type AND gcd.gen_code_group = 'FILING_TYPE'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = eat.app_type AND gcd3.gen_code_group = 'APPLICATION_TYPE'", 'left');
        $this->db->order_by('eat.id', 'DESC');
        $this->db->where('eat.case_no', $case_no);
        $this->db->where('eat.application_status', 1);
        return $this->db->get()->result_array();
    }
}
