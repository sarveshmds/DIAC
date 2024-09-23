<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grievance_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function check_grievance_is_reg($code)
	{
		return $this->db->select('*')->where([
			'code' =>  $code,
			'record_status' => 1
		])->from('grievance_redressal_tbl')->get()->row_array();

	}

	public function get_options_data($code)
	{
		$this->db->select('got.*,gcd.description as options');
		$this->db->from('grievance_options_tbl as got');
		$this->db->join('gen_code_desc gcd', 'gcd.gen_code = got.options', 'left');
		$this->db->where('got.record_status',1);
		$this->db->where('got.grievance_code',$code);
		return $this->db->get()->result_array();		
	}

}

/* End of file Grievance_model.php */
/* Location: ./application/models/Grievance_model.php */