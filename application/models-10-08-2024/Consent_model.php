<?php defined('BASEPATH') or exit('No direct script access allowed');

class Consent_model extends CI_Model
{
    public function insert($data_arr)
    {
        return $this->db->insert('efiling_consent_tbl', $data_arr);
    }

    public function fetchData()
    {
        $query = $this->db->get('efiling_consent_tbl');
        return $query->result_array();
    }
    public function get_single_consent_using_id($id)
    {
        $this->db->from('efiling_consent_tbl ect');
        $this->db->select('ect.*, cdt.case_no as case_no_desc, cdt.case_title');
        $this->db->join('cs_case_details_tbl cdt', 'cdt.slug = ect.case_no', 'left');
        $this->db->where('ect.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_single_consent_using_code($code)
    {
        $this->db->from('efiling_consent_tbl ect');
        $this->db->select('ect.*, cdt.case_no as case_no_desc, cdt.case_title');
        $this->db->join('cs_case_details_tbl cdt', 'cdt.slug = ect.case_no', 'left');
        $this->db->where('ect.c_code', $code);
        return $this->db->get()->row_array();
    }

    public function update($data_arr, $id)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('efiling_consent_tbl', $data_arr);
        return $query;
    }

    public function get_all_concent_list($case_no)
    {
        $this->db->select('eat.*, cdt.case_no as case_no_desc, cdt.case_title');
        $this->db->from('efiling_consent_tbl eat');
        $this->db->join("cs_case_details_tbl as cdt", "cdt.slug = eat.case_no", 'left');
        $this->db->order_by('eat.id', 'DESC');
        $this->db->where('eat.case_no', $case_no);
        $this->db->where('eat.application_status', 1);
        $this->db->where('eat.status', 1);
        return $this->db->get()->result_array();
    }
}
