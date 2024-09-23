<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model
{

	public $date;
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->load->helper('date');

		if (ENVIRONMENT == 'production') {
			$this->db->save_queries = FALSE;
		}
		date_default_timezone_set('Asia/Kolkata');
		$this->date = date('Y-m-d H:i:s', time());

		$this->role 		= $this->session->userdata('role');
		$this->user_name 	= $this->session->userdata('user_name');
		$this->user_code 	= $this->session->userdata('user_code');

		$this->load->model('getter_model');
	}

	/*
	* Function to if the case is alloted to user or not
 	*/
	public function get_all_alloted_cases()
	{
		$result = $this->db->get_where('cs_case_allotment_tbl', array('alloted_to' => $this->user_code, 'status' => 1));
		return $result->result_array();
	}

	/*
	* Function to if the case is alloted to user or not
 	*/
	public function check_case_allotment($case_slug)
	{
		$result = $this->db->get_where('cs_case_allotment_tbl', array('case_no' => $case_slug, 'alloted_to' => $this->user_code, 'status' => 1));

		if ($result && $result->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/*
	* Function to if the case is already alloted to user or not
 	*/
	public function check_case_allotted($case_slug, $alloted_to)
	{
		$result = $this->db->get_where('cs_case_allotment_tbl', array('case_no' => $case_slug, 'alloted_to' => $alloted_to, 'status' => 1));

		if ($result && $result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// check if the current user has following roles as mentioned in array
	// If any of these roles they have.
	// They can access every edit page.
	public function check_user_case_allotment($case_slug, $type = '')
	{

		$check_user_role = $this->db->get_where('user_master', array('user_code' => $this->user_code));

		if ($check_user_role) {
			$get_user_details = $check_user_role->row_array();

			if (in_array($get_user_details['primary_role'], FULL_ACCESS_ROLES)) {
				$check_result = true;
			} else {
				// Check if the case is allotted to the user ot not
				$check_result = $this->check_case_allotment($case_slug);
			}


			if ($check_result) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	// Function to check if case number is exist or not
	public function check_case_number($slug)
	{
		$slug = $this->security->xss_clean($slug);
		$query = $this->db->get_where('cs_case_details_tbl', array('slug' => $slug, 'status' => 1));
		$count = $query->num_rows();

		if ($count == 1) {
			return true;
		} else {
			return false;
		}
	}


	// Get case details using case number
	public function get_case_details_ucn($case_no)
	{
		$q = $this->db->get_where('cs_case_details_tbl', ['case_no' => $case_no]);
		return $q->row_array();
	}


	/*
	* Function to get the last updated time in all case tables
 	*/
	public function get_last_updated_datetime($case_slug)
	{

		$case_details_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_on as last_date
				FROM cs_case_details_tbl
				WHERE slug = '$case_slug'
				
				UNION ALL

				SELECT updated_on as last_date
				FROM cs_case_details_tbl
				WHERE slug = '$case_slug'
			) AS t

			")->row_array();

		$claim_res_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_on as last_date
				FROM cs_claimant_respondant_details_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_on as last_date
				FROM cs_claimant_respondant_details_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();


		// Notings
		$noting_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_noting_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_noting_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// counsels
		$counsels_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_counsels_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_counsels_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// hearings
		$hearings_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cause_list_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cause_list_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// arbitral tribunal
		$arb_tri_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_arbitral_tribunal_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_arbitral_tribunal_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// award_termination
		$award_term_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_award_term_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_award_term_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// Status of pleadings
		$sop_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_status_of_pleadings_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_status_of_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_other_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_other_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_other_correspondance_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_other_correspondance_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// fees and cost
		$fee_cost_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_fee_details_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_details_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_fee_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_cost_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_cost_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_fee_refund_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_refund_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_at_fee_released_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_at_fee_released_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		return array(
			'case_details_last_updated' => $case_details_last_updated,
			'claim_res_last_updated' => $claim_res_last_updated,
			'sop_last_updated' => $sop_last_updated,
			'counsels_last_updated' => $counsels_last_updated,
			'arb_tri_last_updated' => $arb_tri_last_updated,
			'fee_cost_last_updated' => $fee_cost_last_updated,
			'award_term_last_updated' => $award_term_last_updated,
			'noting_last_updated' => $noting_last_updated
		);
	}

	/*
	* Function to update the data logs table
 	*/
	public function update_data_logs($table_name, $table_id, $message)
	{
		$result = $this->db->insert('data_logs_tbl', [
			'table_name' => $table_name,
			'table_id' => $table_id,
			'message' => $message,
			'role_code' => $this->role,
			'created_by' => $this->user_code,
			'created_at' => $this->date
		]);

		if ($result) {
			return true;
		} else {
			return false;
		}
	}


	/*
	* Get case number from slug
 	*/
	public function get_case_details_from_slug($slug)
	{
		$case_det = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $slug)->get()->row_array();

		return $case_det;
	}

	/*
	* Get case registered details from slug
 	*/
	public function get_case_registered_details_from_slug($slug)
	{
		$case_det = $this->db->select('*')->from('cs_case_details_tbl')->where('slug', $slug)->get()->row_array();

		return $case_det;
	}

	/*
	* Function to get the user details with the username
 	*/
	public function get_user_details($username)
	{
		$user = $this->db->select('user_code, user_name, user_display_name, job_title, primary_role')->where(array('user_name' => $username))->from('user_master')->get()->row_array();

		return $user;
	}

	/*
	* Function to get the user details with the username
 	*/
	public function get_user_details_using_usercode($user_code)
	{
		$user = $this->db->select('user_code, user_name, user_display_name, email, phone_number, job_title, primary_role')->where(array('user_code' => $user_code))->from('user_master')->get()->row_array();

		return $user;
	}

	/*
	* Function to get the user details with the username
 	*/
	public function get_user_details_using_user_code($user_code)
	{
		$user = $this->db->select('user_code, user_name, user_display_name, job_title, primary_role, phone_number')->where(array('user_code' => $user_code))->from('user_master')->get()->row_array();

		return $user;
	}

	//  Get all the alloted case list
	public function get_all_alloted_case_list()
	{

		if (in_array($this->role, FULL_ACCESS_ROLES)) {
			return $this->db->from('cs_case_details_tbl')
				->select('slug, case_no_prefix, case_no, case_no_year, case_title')
				->where('status', 1)
				->get()
				->result_array();
		} else {
			return $this->db->from('cs_case_allotment_tbl as cat')
				->select('cat.alloted_to, cdt.slug, cdt.case_no_prefix, cdt.case_no, cdt.case_no_year, cdt.case_title')
				->join('cs_case_details_tbl as cdt', 'cdt.slug = cat.case_no', 'left')
				->where(array('cat.alloted_to' => $this->user_code, 'cat.status' => 1))
				->get()
				->result_array();
		}
	}

	// Get all users related to particular case
	public function get_all_users_related_to_case($case_no)
	{
		$this->db->from('cs_case_allotment_tbl as cat');
		$this->db->select('um.*');
		$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = cat.case_no', 'left');
		$this->db->join('user_master as um', 'um.user_code = cat.alloted_to', 'left');
		$this->db->where(array('cat.case_no' => $case_no, 'cat.status' => 1));
		$alloted_users = $this->db->get()->result_array();

		$this->db->from('user_master as um');
		$this->db->select('um.*');
		$this->db->where_in('um.primary_role', ['COORDINATOR', 'ACCOUNTS']);
		$this->db->where(array('um.record_status' => 1));
		$full_access_users = $this->db->get()->result_array();

		$users = array_merge($alloted_users, $full_access_users);

		return $users;
	}

	// Get all users alloted to particular case
	public function get_all_users_alloted_to_case($case_no)
	{
		$this->db->from('cs_case_allotment_tbl as cat');
		$this->db->select('um.*');
		$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = cat.case_no', 'left');
		$this->db->join('user_master as um', 'um.user_code = cat.alloted_to', 'left');
		$this->db->where(array('cat.case_no' => $case_no, 'cat.status' => 1));
		$alloted_users = $this->db->get()->result_array();

		return $alloted_users;
	}

	//  Get all the claimant and respondent
	public function get_claimant_respondent_separately($case_no)
	{
		$claim_data = $this->db->select("*")
			->from('cs_claimant_respondant_details_tbl')
			->where('case_no', $case_no)
			->where('type', 'claimant')
			->where('status', 1)
			->order_by('count_number', 'ASC')
			->get()
			->result_array();

		$res_data = $this->db->select("*")
			->from('cs_claimant_respondant_details_tbl')
			->where('case_no', $case_no)
			->where('type', 'respondant')
			->where('status', 1)
			->order_by('count_number', 'ASC')
			->get()
			->result_array();

		return [
			'claimants' => $claim_data,
			'respondents' => $res_data
		];
	}

	public function getDashboardDetails()
	{
		$this->db->where('user_code', $this->session->userdata('user_code'));
		$query = $this->db->get('user_master');
		return $query->row();
	}
	public function getDashboardDetails2()
	{
		$this->db->where('fk_user_code', $this->session->userdata('user_code'));
		$query = $this->db->get('user_details_master');
		return $query->row();
	}

	public function get_diary_number($type)
	{
		if ($type == 'REFERRALS_REQUESTS') {
			$results = $this->db->select('m_code as code, diary_number')
				->from('miscellaneous_tbl mt')
				->join('cs_case_details_tbl cdt', 'mt.m_code = cdt.reference_code AND cdt.case_type="REFERRALS_REQUESTS"', 'left')
				->where('cdt.reference_code is null')
				->where('mt.record_status', 1)
				->order_by('mt.created_at', 'DESC')
				->get()->result_array();
			return [
				'status' => true,
				'results' => $results
			];
		}
		if ($type == 'NEW_REFERENCE') {
			$results = $this->db->select('enrt.nr_code as code, enrt.diary_number')
				->from('efiling_new_reference_tbl enrt')
				->join('cs_case_details_tbl cdt', 'enrt.nr_code = cdt.reference_code AND cdt.case_type="NEW_REFERENCE"', 'left')
				->where('enrt.application_status !=', 0)
				->where('enrt.status', 1)
				->where('cdt.reference_code is null')
				->order_by('enrt.created_at', 'DESC')
				->get()
				->result_array();
			return [
				'status' => true,
				'results' => $results
			];
		}

		return [
			'status' => false,
			'msg' => 'Undefined type provided.'
		];
	}

	public function get_new_reference_parties($nr_code)
	{


		$data['claimant'] = $this->db->from('efiling_new_ref_claimant_respondant_tbl as enrcrt')
			->select('enrcrt.type, enrcrt.name')
			->join('efiling_new_reference_tbl as enrt', 'enrt.nr_code = enrcrt.new_reference_code', 'left')
			->where('enrcrt.type', 1)
			->where('enrcrt.new_reference_code', $nr_code)
			->order_by('enrcrt.id', 'ASC')
			->get()
			->row_array();

		$data['respondent'] = $this->db->from('efiling_new_ref_claimant_respondant_tbl as enrcrt')
			->select('enrcrt.type, enrcrt.name')
			->join('efiling_new_reference_tbl as enrt', 'enrt.nr_code = enrcrt.new_reference_code', 'left')
			->where('enrcrt.type', 2)
			->where('enrcrt.new_reference_code', $nr_code)
			->order_by('enrcrt.id', 'ASC')
			->get()
			->row_array();


		$this->db->select('ert.*, gcd.description as type_of_arbitration_desc, gcd2.description as case_type_desc,  gcd3.description as payment_status_desc');
		$this->db->from('efiling_new_reference_tbl ert');
		$this->db->join("gen_code_desc as gcd", "gcd.gen_code = ert.type_of_arbitration AND gcd.gen_code_group = 'TYPE_OF_ARBITRATION'", 'left');
		$this->db->join("gen_code_desc as gcd2", "gcd2.gen_code = ert.case_type AND gcd2.gen_code_group = 'DI_TYPE_OF_ARBITRATION'", 'left');
		$this->db->join("gen_code_desc as gcd3", "gcd3.gen_code = ert.payment_status AND gcd3.gen_code_group = 'PAYMENT_STATUS'", 'left');
		$this->db->where('ert.nr_code', $nr_code);
		$data['new_reference'] = $this->db->get()->row_array();

		return [
			'status' => true,
			'results' => [
				'claimant_name' => $data['claimant']['name'],
				'respondent_name' => $data['respondent']['name'],
				'new_reference' => $data['new_reference']
			]
		];
	}

	function get_rolewise_users_list($role)
	{
		$q = $this->db->select('user_code, user_name, user_display_name, job_title')
			->from('user_master')
			->where('record_status', 1)
			->where('primary_role', $role)
			->order_by('user_display_name', 'ASC')
			->get();
		return $q->result_array();
	}

	function insert_transaction($txn_data)
	{
		$result = $this->db->insert('transaction_tbl', $txn_data);
		if (!$result) {
			return false;
		}
		return true;
	}

	function fetch_transaction_using_type($type, $type_code)
	{
		$result = $this->db->select('tt.*, gcd.description as payment_status_desc')
			->from('transaction_tbl tt')
			->join("gen_code_desc as gcd", "gcd.gen_code = tt.payment_status AND gcd.gen_code_group = 'PAYMENT_STATUS'", 'left')
			->where([
				'type' => $type,
				'type_code' => $type_code,
				'record_status' => 1,
			])
			->get()->row_array();

		return $result;
	}

	function fetch_success_transaction_using_type($type, $type_code)
	{
		$result = $this->db->select('tt.*, gcd.description as payment_status_desc')
			->from('transaction_tbl tt')
			->join("gen_code_desc as gcd", "gcd.gen_code = tt.payment_status AND gcd.gen_code_group = 'PAYMENT_STATUS'", 'left')
			->where([
				'type' => $type,
				'type_code' => $type_code,
				'record_status' => 1,
				'payment_status' => 1
			])
			->get()->row_array();

		return $result;
	}

	function other_noting_message_set_to_read($on_code)
	{
		$result = $this->db->where([
			'ont_code' => $on_code,
			'sent_to' => $this->session->userdata('user_code')
		])->update('other_notings_messages_tbl', [
			'is_readed' => 1,
			'updated_by' => $this->user_code,
			'updated_at' => currentDateTimeStamp()
		]);
		if (!$result) {
			return false;
		}
		return true;
	}

	function insert_address($data)
	{
		$result = $this->db->insert('cs_addresses_tbl', $data);
		if (!$result) {
			return false;
		}
		return true;
	}

	function get_efiling_user_case_using_case_slug($slug)
	{
		$currentUser = $this->getter_model->get('', 'get_current_loggedin_user');

		$sqlResult = $this->db->query("SELECT a.*,a.case_no as case_no_desc,b.* FROM `cs_case_details_tbl` a INNER JOIN
            (SELECT case_no,`type` FROM `cs_claimant_respondant_details_tbl` WHERE contact='" . $currentUser['phone_number'] . "'
            UNION ALL
            SELECT case_no,'Advocate' FROM `cs_counsels_tbl` WHERE phone='" . $currentUser['phone_number'] . "'
            UNION ALL
            SELECT case_no,'Arbitrator' FROM `cs_arbitral_tribunal_tbl` WHERE phone='" . $currentUser['phone_number'] . "') b ON a.slug=b.case_no WHERE slug='" . $slug . "'");

		return $sqlResult->row_array();
	}

	public function check_arbitrator_email($data)
	{
		$this->db->select('email');
		$this->db->from('master_arbitrators_tbl');
		$this->db->where('email', $data['email']);
		return $this->db->get()->row_array();
	}
	public function check_arbitrator_contact($data)
	{
		$this->db->select('id');
		$this->db->from('master_arbitrators_tbl');
		$this->db->where('contact_no', $data['contact_no']);
		return $this->db->get()->row_array();
	}

	public function check_counsel_enroll($data)
	{
		$this->db->select('enrollment_no');
		$this->db->from('master_counsels_tbl');
		$this->db->where('enrollment_no', $data['enrollment_no']);
		return $this->db->get()->row_array();
	}
	public function check_counsel_email($data)
	{
		$this->db->select('email');
		$this->db->from('master_counsels_tbl');
		$this->db->where('email', $data['email']);
		return $this->db->get()->row_array();
	}
	public function check_counsel_contact($data)
	{
		$this->db->select('id');
		$this->db->from('master_counsels_tbl');
		$this->db->where('phone_number', $data['phone_number']);
		return $this->db->get()->row_array();
	}

	public function get_counsel_details($code)
	{
		$this->db->select('*');
		$this->db->from('master_counsels_tbl');
		$this->db->where('code', $code);
		return $this->db->get()->row_array();
	}
	public function get_states($data)
	{
		$this->db->select('*');
		$this->db->from('states');
		$this->db->where('country_code', $data);
		$this->db->order_by('name', 'asc');
		$country_res = $this->db->get();
		return $country_res->result_array();
	}

	public function update_arbitrator_emp($data)
	{
		// code...
	}

	public function get_case_supporting_doc_using_case_code($case_code)
	{
		$this->db->from('cs_case_supporting_documents_tbl');
		$this->db->select('*');
		$this->db->where('case_code', $case_code);
		return $this->db->get()->result_array();
	}

	public function get_room_info_using_id($room_no_id)
	{
		$q = $this->db->from('rooms_tbl')
			->where('active_status', 1)
			->where('id', $room_no_id)
			->order_by('room_no', 'ASC')
			->get();
		return $q->row_array();
	}
}
