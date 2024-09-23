<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empanellment_model extends CI_Model
{

	public function store_arb_emp_registration($data)
	{
		$this->db->trans_begin();
		$result = $this->db->insert('arb_emp_registration_tbl', $data);
		if ($result) {

			$id = $this->db->insert_id();
			$this->db->trans_commit();

			return $id;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function update_arb_emp_registration($data, $id)
	{
		return $this->db->where('id', $id)->update('arb_emp_registration_tbl', $data);
	}

	public function fetch_arb_registration_information($id)
	{
		return $this->db->select('*')->where([
			'id' =>  $id,
			'record_status' => 1
		])->from('arb_emp_registration_tbl')->get()->row_array();
	}

	public function insert_personal_information($data)
	{
		return $this->db->insert('empanelment_personal_info', $data);
	}

	public function update_personal_information($data, $id)
	{
		return $this->db->where('id', $id)->update('empanelment_personal_info', $data);
	}

	public function fetch_arb_personal_information_using_arb_id($arb_id)
	{
		return $this->db->where('arb_id', $arb_id)->select('*')->from('empanelment_personal_info')->get()->row_array();
	}

	public function insert_professional_information($data)
	{
		return $this->db->insert('empanelment_professional_info', $data);
	}

	public function update_professional_information($data, $id)
	{
		return $this->db->where('arb_id', $id)->update('empanelment_professional_info', $data);
	}

	public function fetch_arb_professional_information_using_arb_id($arb_id)
	{
		return $this->db->where('arb_id', $arb_id)->select('*')->from('empanelment_professional_info')->get()->row_array();
	}

	public function fetch_arb_documents_information_using_arb_id($arb_id)
	{
		return $this->db->where('arb_id', $arb_id)->select('*')->from('empanelment_documents_tbl')->get()->row_array();
	}

	public function insert_documents_information($data)
	{
		return $this->db->insert('empanelment_documents_tbl', $data);
	}

	public function update_documents_information($data, $id)
	{
		return $this->db->where('id', $id)->update('empanelment_documents_tbl', $data);
	}

	public function update_form_steps($steps, $id)
	{
		return $this->db->where('id', $id)->update('arb_emp_registration_tbl', $steps);
	}

	public function get_arb_info($arb_id)
	{

		return $this->db->where('id', $arb_id)->where('record_status', 1)->get('arb_emp_registration_tbl')->row_array();
	}

	public function get_arb_personal_info($arb_id)
	{
		$this->db->select('pers.*,pct.category_name as empanellment_category_name,st.name as correspondance_state_name,cnt.name as correspondance_country_name,st_2.name as resident_state_name,cnt_2.name as resident_country_name,st_3.name as office_state_name,cnt_3.name as office_country_name, sal.title as sal_title,nat.name as nationality_name');
		$this->db->from('empanelment_personal_info as pers');
		$this->db->join('countries as cnt', 'cnt.iso2 = pers.resident_country', 'left');
		$this->db->join('states as st', 'st.id = pers.resident_state', 'left');
		$this->db->join('countries as cnt_2', 'cnt_2.iso2 = pers.correspondance_country', 'left');
		$this->db->join('states as st_2', 'st_2.id = pers.correspondance_state', 'left');
		$this->db->join('countries as cnt_3', 'cnt_3.iso2 = pers.office_country', 'left');
		$this->db->join('states as st_3', 'st_3.id = pers.office_state', 'left');
		$this->db->join('panel_category_tbl as pct', 'pct.category_code = pers.empanellment_category', 'left');
		$this->db->join('salutations_tbl as sal', 'sal.id = pers.salutation', 'left');
		$this->db->join('nationality_tbl as nat', 'nat.iso2 = pers.nationality');

		$this->db->where('pers.arb_id', $arb_id);
		$this->db->where('pers.record_status', 1);
		return $this->db->get()->row_array();
	}

	public function get_arb_prof_info($arb_id)
	{
		$this->db->from('empanelment_professional_info');
		$this->db->where('arb_id', $arb_id);
		$this->db->where('record_status', 1);
		return $this->db->get()->row_array();
	}

	public function get_arb_doc_info($arb_id)
	{
		$this->db->from('empanelment_documents_tbl');
		$this->db->where('arb_id', $arb_id);
		$this->db->where('record_status', 1);
		return $this->db->get()->row_array();
	}

	public function final_submit($data, $id)
	{
		return $this->db->where('id', $id)->update('arb_emp_registration_tbl', $data);
	}

	public function insert_arb_other_institute($data)
	{
		return $this->db->insert_batch('already_empanelled_as_arb_tbl', $data);
	}
	public function fetch_arb_other_empanel_info($arb_id)
	{
		return $this->db->where('arb_id', $arb_id)->where('record_status', 1)->select('*')->from('already_empanelled_as_arb_tbl')->get()->result_array();
	}
}

/* End of file Empanellment_model.php */
/* Location: ./application/models/Empanellment_model.php */
