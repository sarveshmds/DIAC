<?php defined('BASEPATH') or exit('No direct script access allowed');

class Register_model extends CI_Model
{
    public function insertUser_master($data_arr)
    {
        return $this->db->insert('user_master', $data_arr);
    }
    public function insertUser_details_master($data_arr2)
    {
        return $this->db->insert('user_details_master', $data_arr2);
    }
}
