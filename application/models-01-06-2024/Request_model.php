<?php defined('BASEPATH') or exit('No direct script access allowed');

class Request_model extends CI_Model
{
    public function insert($data_arr)
    {
        return $this->db->insert('efiling_requests_tbl', $data_arr);
    }

    public function fetchData()
    {
        $query = $this->db->get('efiling_requests_tbl');
        return $query->result_array();
    }

    public function get_single_request_using_id($id)
    {
        return $this->db->from('efiling_requests_tbl')->where('id', $id)->get()->row_array();
    }
    public function update($data_arr, $id)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('efiling_requests_tbl', $data_arr);
        return $query;
    }
    public function checkCount()
    {
        $this->db->select('*');
        $this->db->from('efiling_requests_tbl');
        $query = $this->db->get();
        return $query->num_rows();
    }
}
