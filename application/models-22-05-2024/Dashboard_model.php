<?php
class Dashboard_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		if (ENVIRONMENT == 'production') {
			$this->db->save_queries = FALSE;
		}
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());
		$this->role 		= $this->session->userdata('role');
		$this->user_name 	= $this->session->userdata('user_name');
		$this->user_code 	= $this->session->userdata('user_code');
		$this->sess_id 		= $this->session->userdata('sess_id');

		$sql = "SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));";
		$this->db->query($sql);
	}

	public function get($data, $op)
	{
		switch ($op) {
			case 'GET_ROLEWISE_USER':
				$this->db->select('b.role_name,COUNT(a.user_code) as cnt');
				$this->db->join('role_master b', 'a.primary_role=b.role_code', 'INNER');
				$this->db->where('a.record_status', '1');
				$this->db->group_by('b.role_name');
				$res = $this->db->get('user_master a');
				return $res->result_array();
				break;
			case 'GET_LOGIN_CURRENT_DATE':
				$this->db->select('b.user_display_name,DATE_FORMAT(a.created_on,"%d-%m-%Y %h:%i:%s") AS created_on');
				$this->db->join('user_master b', 'a.login_id=b.user_code', 'INNER');
				$this->db->where('b.record_status', '1');
				$this->db->where('DATE(a.created_on)', date('Y-m-d'));
				$res = $this->db->get('login_detail a');
				return $res->result_array();
				break;
			case 'GET_DATA_FOR_DASHBOARD':

				$todays_date = date('d-m-Y');

				$pending_refferal_req_count = $this->db->where([
					'record_status' => 1,
					'is_approved' => 0,
					'is_submitted' => 1,
				])->count_all_results('miscellaneous_tbl');

				$approved_refferal_req_count = $this->db->where([
					'record_status' => 1,
					'is_approved' => 1
				])->count_all_results('miscellaneous_tbl');

				$total_case_count = $this->db->where('status', 1)->where('is_registered', 1)
					->count_all_results('cs_case_details_tbl');

				$total_rooms = $this->db->where('active_status', 1)
					->count_all_results('rooms_tbl');

				$total_hearings_today = $this->db->where(array(
					'active_status' => 1,
					'date' => $todays_date
				))
					->count_all_results('cause_list_tbl');

				$total_poa = $this->db->where('status', 1)
					->count_all_results('panel_of_arbitrator_tbl');

				$allRegisteredCases = $this->db->from('cs_case_details_tbl')
					->select('*')
					->where('status', 1)
					->get()
					->result_array();

				// Get Yearwise case count
				$yearWiseData = $this->db->query("SELECT 
				   COUNT(*) AS cnt ,YEAR(STR_TO_DATE(registered_on,'%Y-%m-%d')) AS registered_year 
				   FROM cs_case_details_tbl
				   GROUP BY YEAR(STR_TO_DATE(registered_on,'%Y-%m-%d')) ORDER BY registered_year DESC")->result_array();

				// Get monthwise case count
				$monthWiseData = $this->db->query("SELECT mt.month_name,COUNT(cd.id) AS cnt ,MONTH(STR_TO_DATE(cd.registered_on,'%Y-%m-%d')) AS registered_month 
				   FROM month_table AS mt LEFT JOIN  cs_case_details_tbl AS cd ON  YEAR(STR_TO_DATE(registered_on,'%Y-%m-%d'))= '" . $data['current_year'] . "' AND MONTH(STR_TO_DATE(cd.registered_on,'%Y-%m-%d')) = mt.month_number
				   GROUP BY mt.month_number ORDER BY mt.id ASC")->result_array();

				return array(
					'total_case_count' => $total_case_count,
					'total_rooms' => $total_rooms,
					'total_hearings_today' => $total_hearings_today,
					'total_poa' => $total_poa,
					'allRegisteredCases' => $allRegisteredCases,
					'yearWiseData' => $yearWiseData,
					'monthWiseData' => $monthWiseData,
					'pending_refferal_req_count' => $pending_refferal_req_count,
					'approved_refferal_req_count' => $approved_refferal_req_count,
				);

				break;
			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function check_pending_efilings()
	{
		$data['new_reference'] = $this->db->from('efiling_new_reference_tbl')->where([
			'status' => 1,
			'application_status' => 2
		])->count_all_results();

		$data['document'] = $this->db->from('efiling_document_tbl')->where([
			'status' => 1,
			'application_status' => 2
		])->count_all_results();

		$data['application'] = $this->db->from('efiling_application_tbl')->where([
			'status' => 1,
			'application_status' => 2
		])->count_all_results();

		$data['request'] = $this->db->from('efiling_requests_tbl')->where([
			'status' => 1,
			'application_status' => 2
		])->count_all_results();

		return $data;
	}

	public function check_pending_efilings_messages()
	{
		$this->db->from('other_notings_messages_tbl as onmt');
		$this->db->select('onmt.*, um.user_display_name as sent_by_desc, ont.noting_group, ont.type_code, ont.type_name');
		$this->db->join('other_notings_tbl as ont', 'ont.code = onmt.ont_code', 'left');
		$this->db->join('user_master as um', 'um.user_code = onmt.sent_by', 'left');
		$this->db->where([
			'onmt.sent_to' => $this->user_code,
			'onmt.record_status' => 1,
			'onmt.is_readed' => 0,
		]);

		$messages = $this->db->get()->result_array();

		return $messages;
	}

	public function check_pending_fees_asses()
	{
		$data['new_fees_assessment'] = $this->db->from('cs_fee_details_tbl')->where([
			'status' => 1,
			'assessment_approved' => 0
		])->count_all_results();

		return $data;
	}

	public function fetch_toa_percentage()
	{
		$result = $this->db->query('SELECT type_of_arbitration, COUNT(*) AS total_cases,
    ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM cs_case_details_tbl), 2) AS percentage FROM cs_case_details_tbl GROUP BY type_of_arbitration')->result_array();



		$toa_data = [];
		foreach ($result as $row) {
			if ($row['type_of_arbitration'] == 'DOMESTIC') {
				$toa_data['domestic'] = $row['percentage'];
			}
			if ($row['type_of_arbitration'] == 'INTERNATIONAL') {
				$toa_data['international'] = $row['percentage'];
			}
		}

		return $toa_data;
	}

	public function fetch_toc_percentage()
	{
		$result = $this->db->query('SELECT di_type_of_arbitration, COUNT(*) AS total_cases, ROUND(COUNT(* ) * 100 / (SELECT COUNT(*) FROM cs_case_details_tbl), 2) AS percentage FROM cs_case_details_tbl GROUP BY di_type_of_arbitration')->result_array();

		$toc_data = [];

		foreach ($result as $row) {
			if ($row['di_type_of_arbitration'] == 'EMERGENCY') {
				$toc_data['emergency'] = $row['percentage'];
			}
			if ($row['di_type_of_arbitration'] == 'FASTTRACK') {
				$toc_data['fasttrack'] = $row['percentage'];
			}
			if ($row['di_type_of_arbitration'] == 'GENERAL') {
				$toc_data['general'] = $row['percentage'];
			}
		}

		return $toc_data;
	}
}
