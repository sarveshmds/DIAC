<?php defined('BASEPATH') or exit('No direct script access allowed');

class New_reference_model extends CI_Model
{
    public function insert($data_arr)
    {
        return $this->db->insert('efiling_new_reference_tbl', $data_arr);
    }

    public function update($data_arr, $id)
    {
        return $this->db->where('id', $id)->update('efiling_new_reference_tbl', $data_arr);
    }

    public function update_using_code($data_arr, $nr_code)
    {
        return $this->db->where('nr_code', $nr_code)->update('efiling_new_reference_tbl', $data_arr);
    }

    public function get_single_new_reference_data_using_id($id)
    {
        $this->db->select('ert.*, gcd.description as type_of_arbitration_desc, gcd2.description as case_type_desc,  gcd3.description as payment_status_desc');
        $this->db->from('efiling_new_reference_tbl ert');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = ert.payment_status AND gcd3.gen_code_group = 'PAYMENT_STATUS'", 'left');

        $this->db->where('ert.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_single_new_reference_data_using_code($code)
    {
        $this->db->select('ert.*, gcd.description as type_of_arbitration_desc, gcd2.description as case_type_desc,  gcd3.description as payment_status_desc');
        $this->db->from('efiling_new_reference_tbl ert');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = ert.payment_status AND gcd3.gen_code_group = 'PAYMENT_STATUS'", 'left');
        $this->db->where('ert.nr_code', $code);
        return $this->db->get()->row_array();
    }

    public function get_single_new_reference_data_using_diary_number($diary_number)
    {
        $this->db->select('ert.*, gcd.description as type_of_arbitration_desc, gcd2.description as case_type_desc');
        $this->db->from('efiling_new_reference_tbl ert');
        $this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
        $this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
        $this->db->where('ert.diary_number', $diary_number);
        return $this->db->get()->row_array();
    }

    public function insert_new_ref_claimant($rowCountArray, $data, $new_reference_code, $type)
    {
        if (count($rowCountArray) > 0) {
            $cl_data = [];
            foreach ($rowCountArray as $row) {
                if (!empty($data['cl_name_' . $row]) || !empty($data['cl_address_' . $row]) || !empty($data['cl_email_' . $row]) || !empty($data['cl_mobile_number_' . $row]) || !empty($data['common_address_pincode_' . $row])) {
                    array_push($cl_data, array(
                        'new_reference_code' => $new_reference_code,
                        'type' => $type,
                        'name' => $this->security->xss_clean($data['cl_name_' . $row]),
                        'address_one' => $this->security->xss_clean($data['cl_address_one_' . $row]),
                        'address_two' => $this->security->xss_clean($data['cl_address_two_' . $row]),
                        'state' => $this->security->xss_clean($data['cl_state_' . $row]),
                        'country' => $this->security->xss_clean($data['cl_country_' . $row]),
                        'pincode' => $this->security->xss_clean($data['cl_pincode_' . $row]),
                        'email_id' => $this->security->xss_clean($data['cl_email_' . $row]),
                        'phone_number' => $this->security->xss_clean($data['cl_mobile_number_' . $row]),
                        'created_by' => $this->session->userdata('user_code'),
                        'created_at' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_at' => currentDateTimeStamp()
                    ));
                }
            }
            if (count($cl_data) > 0) {
                $result = $this->db->insert_batch('efiling_new_ref_claimant_respondant_tbl', $cl_data);
                if (!$result) {
                    $this->db->trans_rollback();
                    return array(
                        'status' => false,
                        'msg' => 'Error while saving claimant details. Please try again.'
                    );
                } else {
                    return array(
                        'status' => true
                    );
                }
            } else {
                return array(
                    'status' => true
                );
            }
        }
    }

    public function insert_new_ref_respondant($rowCountArray, $data, $new_reference_code, $type)
    {
        if (count($rowCountArray) > 0) {
            $res_data = [];
            foreach ($rowCountArray as $row) {
                if (!empty($data['res_name_' . $row]) || !empty($data['res_address_' . $row]) || !empty($data['res_email_' . $row]) || !empty($data['res_mobile_number_' . $row]) || !empty($data['common_address_pincode_' . $row])) {
                    array_push($res_data, array(
                        'new_reference_code' => $new_reference_code,
                        'type' => $type,
                        'name' => $this->security->xss_clean($data['res_name_' . $row]),
                        'address_one' => $this->security->xss_clean($data['res_address_one_' . $row]),
                        'address_two' => $this->security->xss_clean($data['res_address_two_' . $row]),
                        'state' => $this->security->xss_clean($data['res_state_' . $row]),
                        'country' => $this->security->xss_clean($data['res_country_' . $row]),
                        'pincode' => $this->security->xss_clean($data['res_pincode_' . $row]),
                        'email_id' => $this->security->xss_clean($data['res_email_' . $row]),
                        'phone_number' => $this->security->xss_clean($data['res_mobile_number_' . $row]),
                        'created_by' => $this->session->userdata('user_code'),
                        'created_at' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_at' => currentDateTimeStamp()
                    ));
                }
            }
            if (count($res_data) > 0) {
                $result = $this->db->insert_batch('efiling_new_ref_claimant_respondant_tbl', $res_data);
                if (!$result) {
                    $this->db->trans_rollback();
                    return array(
                        'status' => false,
                        'msg' => 'Error while saving respondant details. Please try again.'
                    );
                } else {
                    return array(
                        'status' => true
                    );
                }
            } else {
                return array(
                    'status' => true
                );
            }
        }
    }

    public function checkCount()
    {
        $this->db->select('*');
        $this->db->from('efiling_new_reference_tbl');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_new_reference_claimants_using_code($code)
    {
        $this->db->select('ect.*, c.name as country_name, s.name as state_name');
        $this->db->from('efiling_new_ref_claimant_respondant_tbl ect');
        $this->db->join('countries as c', 'c.iso2 = ect.country', 'left');
        $this->db->join('states as s', 's.id = ect.state', 'left');
        $this->db->where('ect.new_reference_code', $code);
        $this->db->where('ect.type', 1);
        return $this->db->get()->result_array();
    }

    public function get_new_reference_respondants_using_code($code)
    {
        $this->db->select('ert.*, c.name as country_name, s.name as state_name');
        $this->db->from('efiling_new_ref_claimant_respondant_tbl ert');
        $this->db->join('countries as c', 'c.iso2 = ert.country', 'left');
        $this->db->join('states as s', 's.id = ert.state', 'left');
        $this->db->where('ert.new_reference_code', $code);
        $this->db->where('ert.type', 2);
        return $this->db->get()->result_array();
    }
}
