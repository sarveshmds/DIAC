<?php defined('BASEPATH') or exit('No direct script access allowed');

class Profile_model extends CI_Model
{
    public function updateUser_master($data_arr, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('user_master', $data_arr);
    }
    public function updateUser_details_master($data_arr2, $user_code)
    {
        $this->db->where('fk_user_code', $user_code);
        return $this->db->update('user_details_master', $data_arr2);
    }
}
