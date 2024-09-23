<?php defined('BASEPATH') or exit('No direct script access allowed');

class Arbitral_tribunal_model extends CI_Model
{
    public function getArbitratorMaxCode()
    {
        $query = $this->db->select_max('at_code')
            ->get('cs_arbitral_tribunal_tbl');
        $maxValue = $query->row()->at_code;
        return $maxValue;
    }

    public function getBasicArbitratorData($case_code)
    {
        $this->db->select('catt.at_code, catt.whether_on_panel, catt.case_no, mat.name_of_arbitrator as mat_name_of_arbitrator, mat.contact_no, IF(catt.whether_on_panel = 1, mat.email, catt.email) as email, IF(catt.whether_on_panel = 1, mat.name_of_arbitrator, catt.name_of_arbitrator) as name_of_arbitrator');

        $this->db->from('cs_arbitral_tribunal_tbl as catt');
        $this->db->join('master_arbitrators_tbl as mat', 'mat.code = catt.arbitrator_code AND catt.whether_on_panel = 1', 'left');
        $this->db->where('case_no', $case_code);
        $country_res = $this->db->get();
        return $country_res->result_array();
    }

    /**
     * Get case arbitrator list
     */
    public function getCaseArbitratorsUsingCaseCode($case_code)
    {
        $this->db->select('catt.*, gc.description as appointed_by_desc, gc2.description as arbitrator_type_desc, pct.category_name, mat.name_of_arbitrator as mat_name_of_arbitrator, mat.email as mat_email, mat.contact_no, mat.category as mat_category, mat.perm_address_1 mat_perm_address_1, mat.perm_address_2 as perm_address_2, mat.perm_country as mat_perm_country, mat.perm_state as mat_perm_state, mat.perm_pincode as mat_perm_pincode, pct2.category_name as mat_category_name, gc3.description as is_empanelled_desc');
        $this->db->from('cs_arbitral_tribunal_tbl as catt');
        $this->db->join('master_arbitrators_tbl as mat', 'mat.code = catt.arbitrator_code AND catt.whether_on_panel = 1', 'left');
        $this->db->join('gen_code_desc as gc', 'gc.gen_code = catt.appointed_by AND gc.gen_code_group = "APPOINTED_BY"', 'left');
        $this->db->join('gen_code_desc as gc2', 'gc2.gen_code = catt.arbitrator_type AND gc2.gen_code_group = "ARBITRATOR_TYPE"', 'left');
        $this->db->join('gen_code_desc as gc3', 'gc3.gen_code = catt.whether_on_panel AND gc3.gen_code_group = "ARB_IS_EMPANELLED"', 'left');

        $this->db->join('panel_category_tbl as pct', 'pct.code = catt.category', 'left');
        $this->db->join('panel_category_tbl as pct2', 'pct2.code = mat.category', 'left');

        $this->db->where('catt.case_no', $case_code);
        $this->db->order_by('catt.id', 'ASC');
        $country_res = $this->db->get();
        return $country_res->result_array();
    }
}
