<?php defined('BASEPATH') or exit('No direct script access allowed');

class Document_model extends CI_Model
{
    public function insert($data_arr)
    {
        return $this->db->insert('efiling_document_tbl', $data_arr);
    }

    public function checkCount()
    {
        $this->db->select('*');
        $this->db->from('efiling_document_tbl');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_single_request_using_id($id)
    {
        return $this->db->from('efiling_document_tbl')->where('id', $id)->get()->row_array();
    }

    public function update($data_arr, $id)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('efiling_document_tbl', $data_arr);
        return $query;
    }

    public function get_single_doc_data_using_code($code)
    {
        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, gcd.description as type_of_document_desc, gcd2.description as behalf_of_desc, cdt.case_title');
        $this->db->from('efiling_document_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.name_on_behalf_of", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.type_of_document AND gcd.gen_code_group = 'TYPE_OF_DOCUMENT'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf_of AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        return $this->db->where('d_code', $code)->get()->row_array();
    }

    public function get_all_documents_list($case_no)
    {
        $this->db->select('eat.*, ccrdt.name, cdt.case_no as case_no_desc, gcd.description as type_of_document_desc, gcd2.description as behalf_of_desc, cdt.case_title');
        $this->db->from('efiling_document_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->join("cs_claimant_respondant_details_tbl as ccrdt", "ccrdt.id = eat.name_on_behalf_of", 'left');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = eat.type_of_document AND gcd.gen_code_group = 'TYPE_OF_DOCUMENT'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = eat.behalf_of AND gcd2.gen_code_group = 'BEHALF_OF'", 'left');
        $this->db->order_by('eat.id', 'DESC');
        $this->db->where('eat.case_no', $case_no);
        $this->db->where('eat.application_status', 1);
        return $this->db->get()->result_array();
    }
}
