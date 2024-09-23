<?php
class Getter_model extends CI_model
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
		$this->sess_id 	= $this->session->userdata('sess_id');
		$this->state_code 	= $this->session->userdata('state_code');
	}

	public function get($data, $op)
	{
		switch ($op) {
			case 'logout_all_system':
				$this->db->select('user_code');
				$this->db->where('user_name', $this->input->post('txtUserName'));
				$this->db->where('record_status', 1);
				$res = $this->db->get('user_master');
				$result = $res->result_array()[0];
				if ($result) {
					$this->db->where('login_id', $result['user_code']);
					$this->db->where('record_status', 'ACTIVE');
					$output = $this->db->update('login_detail', array('record_status' => 'INACTIVE'));
					return $output;
				}
				break;
			case 'get_user_check':

				$num_rows = 0;
				$this->db->select('*');
				$this->db->from('login_detail');
				$this->db->where('login_id', $this->user_code);
				$this->db->where('session_id', $this->sess_id);
				$this->db->where('record_status', 'ACTIVE');

				$get_result = $this->db->get();
				$num_rows = $get_result->num_rows();

				return $num_rows;
				break;

			case 'get_title':
				$this->db->from('title_setup');
				$this->db->select('title_name,title_desc,title_image');
				$this->db->where('status', 1);
				$result = $this->db->get();
				return $result->result_array()[0];
				break;
			case 'get_sidebar':
				$this->db->select('mm.*,rm.resource_link,rm.is_maintenance');
				$this->db->from('menu_master as mm');
				$this->db->join('resource_master as rm', 'mm.resource_code = rm.resource_code  AND rm.record_status = 1', 'left');
				$this->db->where('mm.role_code', $this->role);
				$this->db->where('mm.record_status', 1);
				$this->db->order_by('mm.sl_no', 'asc');
				$result = $this->db->get();
				return $result->result_array();
				break;
			case 'get_county_name':
				$this->db->select('country_code,country_name');
				$this->db->from('country_master');
				$this->db->order_by('country_name', 'asc');
				$country_res = $this->db->get();
				return $country_res->result_array();
				break;

			case 'get_state_name':
				$this->db->from('state_master');
				$this->db->select('state_code,state_name');
				$this->db->where('country_code', $data['country_code']);
				$this->db->order_by('state_name', 'asc');
				$state_res = $this->db->get();
				return $state_res->result_array();
				break;
			case 'get_dist_name':
				$this->db->from('district_master');
				$this->db->select('district_code,district_name,dist_census_code');
				$this->db->where('state_code', '21');
				$this->db->order_by('district_code', 'asc');
				$dist_res = $this->db->get();
				return $dist_res->result_array();
				break;
			case 'GET_ALL_COUNTRIES':
				$this->db->select('*');
				$this->db->from('countries');
				$this->db->order_by('name', 'asc');
				$country_res = $this->db->get();
				return $country_res->result_array();
				break;
			case 'GET_ALL_STATES':
				$this->db->select('*');
				$this->db->from('states');
				$this->db->where('country_code', $data['country_code']);
				$this->db->order_by('name', 'asc');
				$country_res = $this->db->get();
				return $country_res->result_array();
				break;
			case 'GET_GENCODE_DESC':
				$this->db->from('gen_code_desc');
				$this->db->select('gen_code,description');
				$this->db->where('gen_code_group', $data['gen_code_group']);
				$this->db->where('status', 1);
				$this->db->order_by('sl_no', 'ASC');
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'GET_DEPOSITED_TOWARDS_GENCODE_DESC':
				$this->db->from('gen_code_desc');
				$this->db->select('gen_code,description');
				$this->db->where('gen_code_group', $data['gen_code_group']);
				$this->db->where('status', 1);
				$this->db->order_by('sl_no', 'ASC');
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'GET_DISPLAY_BOARD_DATA':
				$todays_date = date('d-m-Y');
				$this->db->select("cdb.id as cdb_id, cdb.case_no, cdb.arbitrator_name, cdb.room_status, cdb.todays_date, rt.room_name, rt.room_no, cdb.room_id, rt.id as rt_id");
				$this->db->from('rooms_tbl as rt');
				$this->db->join("(SELECT * FROM cs_display_board_tbl WHERE todays_date = '$todays_date') as cdb", 'cdb.room_id = rt.id', 'left');
				$this->db->where('rt.active_status', 1);
				$this->db->order_by('rt.room_no', 'ASC');
				$query = $this->db->get();
				$fetch_data = $query->result_array();
				return $fetch_data;
				break;

			case 'GET_ALL_REGISTERED_CASES_LIST':
				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, DATE_FORMAT(cdt.reffered_on, '%d-%m-%Y') as reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, DATE_FORMAT(cdt.recieved_on, '%d-%m-%Y') as recieved_on, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.case_file, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
				$this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
				$this->db->where('cdt.status', 1);
				$this->db->order_by('cdt.case_no', 'ASC');
				return $this->db->get()->result_array();
				break;

			case 'GET_CASE_OTHER_PLEADINGS':
				$this->db->select("id, case_no, details, date_of_filing, filed_by");
				$this->db->from('cs_other_pleadings_tbl');
				$this->db->where('status', 1)->where('case_no', $data['case_slug']);
				return $this->db->get()->result_array();

				break;

			case 'GET_CASE_OTHER_CORRESPONDANCE':
				$this->db->select("id, case_no, details, date_of_correspondance, send_by, sent_to");
				$this->db->from('cs_other_correspondance_tbl');
				$this->db->where('status', 1)->where('case_no', $data['case_slug']);
				return $this->db->get()->result_array();
				break;

			case 'GET_CASE_NOTINGS':
				$this->db->select("nt.id, nt.case_no, nt.noting, nt.noting_date, nt.next_date,nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um.job_title as marked_to_job_title, um2.user_display_name marked_by_user, um2.job_title as marked_by_job_title, gcd.description as noting_text");
				$this->db->from('cs_noting_tbl as nt');
				$this->db->join('user_master as um', 'um.user_code = nt.marked_to');
				$this->db->join('user_master as um2', 'um2.user_code = nt.marked_by');
				$this->db->join('gen_code_desc as gcd', 'gcd.gen_code = nt.noting_text_code AND gcd.gen_code_group = "NOTING_SMART_TEXT"', 'left');
				$this->db->where('nt.status', 1)->where('nt.case_no', $data['case_slug']);
				$this->db->order_by('id', 'ASC');
				return $this->db->get()->result_array();
				break;

			case 'GET_CASE_DETAILS':
				$this->db->select("cdt.slug as cdt_slug , cdt.*");
				$this->db->from('cs_case_details_tbl AS cdt');

				$this->db->where('cdt.slug', $data['case_slug']);
				$this->db->where('cdt.status', 1);
				return $this->db->get()->row_array();
				break;

			case 'GET_ALL_PANEL_OF_ARBITRATORS':
				$this->db->select("poa.id, poa.name, poa.category_id, pc.category_name as category_name, poa.contact_details, poa.email_details, poa.address_details, poa.remarks, poa.experience, poa.enrollment_no");
				$this->db->from('panel_of_arbitrator_tbl as poa');
				$this->db->join('panel_category_tbl as pc', 'pc.id = poa.category_id');

				$this->db->where('poa.status', 1);
				return $this->db->get()->result_array();
				break;

			case 'GET_CAUSE_LIST':
				$this->db->select("clt.id, clt.slug,clt.case_no, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator");
				$this->db->from('cause_list_tbl AS clt');
				$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
				$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
				$this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug', 'left');
				// $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code AND cat.whether_on_panel = 1', 'left');
				$this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

				$this->db->where('clt.active_status !=', 0);

				if (isset($data['todays_date']) && $data['todays_date']) {
					$this->db->where('clt.date', date('Y-m-d', strtotime($data['todays_date'])));
				}
				$this->db->group_by('clt.id');
				$this->db->order_by('clt.date', 'DESC');
				return $this->db->get()->result_array();
				break;

			case 'GET_SINGLE_RESPONSE_FORMAT':
				$this->db->select("case_no, subject, body, email_to, status");
				$this->db->from('cs_response_format_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('id', $data['response_id']);
				$this->db->where('status', 1);
				return $this->db->get()->row_array();
				break;

			case 'GET_CASE_NUMBERS_LIST_FOR_FORMAT_RESPONSE':
				$this->db->select("ca.case_no, ca.alloted_to, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.slug, cdt.case_title");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no', 'left');
				$this->db->where('ca.alloted_to', $this->user_code);
				$this->db->where('ca.status', 1);
				$this->db->order_by('cdt.case_no', 'DESC');
				return $this->db->get()->result_array();
				break;

			case 'GET_CASE_FEE_DETAILS':
				$this->db->select("case_no, c_cc_asses_sep, sum_in_dispute, sum_in_dispute_claim, sum_in_dispute_cc,asses_date, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				return $this->db->get()->row_array();
				break;

			case 'GET_CASE_FEE_DEPOSIT_DETAILS':
				$this->db->select("case_no, date_of_deposit, deposited_by, name_of_depositor, deposited_towards, mode_of_deposit, details_of_deposit, amount, status");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$allDepositedFees = $this->db->get()->result_array();

				$this->db->select("case_no, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, status");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$caseFeesDetails = $this->db->get()->row_array();

				$shareWiseDepositedFees = $this->db->query("SELECT MAX(CASE WHEN deposited_towards='ADM_RS' THEN tot_amt ELSE 0 END) AS tot_amt_ADM_RS,
					MAX(CASE WHEN deposited_towards='ADM_CS' THEN tot_amt ELSE 0 END) AS tot_amt_ADM_CS, 
					MAX(CASE WHEN deposited_towards='ARB_CS' THEN tot_amt ELSE 0 END) AS tot_amt_ARB_CS,
					 MAX(CASE WHEN deposited_towards='ARB_RS' THEN tot_amt ELSE 0 END) AS tot_amt_ARB_RS FROM (
				   	SELECT deposited_towards, SUM(amount) AS tot_amt FROM`cs_fee_deposit_tbl` 
				   	WHERE case_no = '" . $data['case_no'] . "' GROUP BY deposited_towards) a")->row_array();

				return [
					'allDepositedFees' => $allDepositedFees,
					'shareWiseDepositedFees' => $shareWiseDepositedFees,
					'caseFeesDetails' => $caseFeesDetails
				];

				break;

			case 'GET_ALL_CASES_LIST':
				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
				$this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
				$this->db->where('cdt.status', 1);
				$this->db->order_by('cdt.case_no', 'ASC');
				return $this->db->get()->result_array();
				break;

			case 'GET_CASE_DETAILS_USING_ID':
				$this->db->select("cdt.id, cdt.slug as cdt_slug ,cdt.case_no as case_no, cdt.case_title, cdt.case_title_claimant, cdt.case_title_respondent,, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge, cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration,cdt.case_status, cdt.remarks");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->where('cdt.id', $data['id']);
				$this->db->where('cdt.status', 1);
				return $this->db->get()->row_array();
				break;

			case 'GET_ROLEWISE_USERS_LIST':
				$q = $this->db->select('user_code, user_name, user_display_name, job_title')
					->from('user_master')
					->where('record_status', 1)
					->where_in('primary_role', $this->security->xss_clean($this->input->post('role')))
					->order_by('user_display_name', 'ASC')
					->get();
				return $q->result_array();
				break;

			case 'GET_ADDRESSES':
				$q = $this->db->select('cat.*, c.name as country_name, s.name as state_name')
					->from('cs_addresses_tbl cat')
					->join('countries as c', 'c.iso2 = cat.country', 'left')
					->join('states as s', 's.id = cat.state', 'left')
					->where('cat.status', 1)
					->where('cat.person_type', $this->security->xss_clean($this->input->post('person_type')))
					->where('cat.type_code', $this->security->xss_clean($this->input->post('type_code')))
					->get();
				return $q->result_array();
				break;

			case 'GET_SINGLE_ADDRESS':
				$q = $this->db->select('*')
					->from('cs_addresses_tbl')
					->where('status', 1)
					->where('id', $this->security->xss_clean($this->input->post('id')))
					->get();
				return $q->row_array();
				break;

			case 'GET_ALL_REGISTERED_CASES_LIST_FOR_EXCEL':
				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc,  GROUP_CONCAT(CONCAT(catt.name_of_arbitrator, '(',  pct.category_name),')' SEPARATOR ',') name_of_arbitrator");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
				$this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
				$this->db->join('cs_arbitral_tribunal_tbl as catt', 'catt.case_no = cdt.slug', 'left');
				$this->db->join('panel_category_tbl as pct', 'pct.id = catt.at_cat_id', 'left');
				$this->db->where('cdt.status', 1);
				$this->db->order_by('cdt.case_no', 'ASC');
				$this->db->group_by("cdt.case_no");
				return $this->db->get()->result_array();
				break;

			case 'GET_ALL_CASES_LIST_FOR_EXCEL':
				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, DATE_FORMAT(cdt.reffered_on, '%d-%m-%Y') as reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, DATE_FORMAT(cdt.recieved_on, '%d-%m-%Y') as recieved_on, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.case_file, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc, GROUP_CONCAT(CONCAT(catt.name_of_arbitrator, '(',  pct.category_name),')' SEPARATOR ',') name_of_arbitrator");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
				$this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
				$this->db->join('cs_arbitral_tribunal_tbl as catt', 'catt.case_no = cdt.slug', 'left');
				$this->db->join('panel_category_tbl as pct', 'pct.id = catt.at_cat_id', 'left');
				$this->db->where('cdt.status', 1);
				$this->db->order_by('cdt.case_no', 'ASC');
				$this->db->group_by("cdt.case_no");
				return $this->db->get()->result_array();
				break;

			case 'get_current_loggedin_user':
				$this->db->select('*');
				$this->db->where('user_code', $this->user_code);
				$this->db->where('record_status', 1);
				$res = $this->db->get('user_master');
				return $res->row_array();
				break;

			case 'get_current_efiling_user_case_list':
				$currentUser = $this->get('', 'get_current_loggedin_user');

				$res = $this->db->query("SELECT a.*, a.case_no as case_no_desc,b.* FROM `cs_case_details_tbl` a INNER JOIN
					(SELECT case_no,`type` FROM `cs_claimant_respondant_details_tbl` WHERE contact='" . $currentUser['phone_number'] . "'
					UNION ALL
					SELECT case_no,'Advocate' FROM `cs_counsels_tbl` WHERE phone='" . $currentUser['phone_number'] . "'
					UNION ALL
					SELECT case_no,'Arbitrator' FROM `cs_arbitral_tribunal_tbl` WHERE phone='" . $currentUser['phone_number'] . "') b ON a.slug=b.case_no");
				return $res->result_array();
				break;

			case 'get_user_details_using_user_code':
				$this->db->select('*');
				$this->db->where('user_code', $data['user_code']);
				$this->db->where('record_status', 1);
				$res = $this->db->get('user_master');
				return $res->row_array();
				break;

			case 'get_dc_cm_users_list':
				$this->db->from('user_master a');
				$this->db->select("a.user_name");
				$this->db->join("user_details_master b", "a.user_code = b.fk_user_code", "left");
				$this->db->join("state_master c", "a.fk_state_code = c.state_code", "left");
				$this->db->where_in('a.primary_role', ['DEPUTY_COUNSEL', 'CASE_MANAGER']);
				return $this->db->get()->result_array();
				break;

			case 'get_case_ref_no_list':
				$this->db->from('master_case_ref_no_tbl');
				$this->db->select("*");
				if (isset($data['type'])) {
					$this->db->where('type', $data['type']);
				}
				$this->db->where('record_status', 1);
				return $this->db->get()->result_array();
				break;

			case 'get_arbitrators_name':
				$this->db->select('code,name_of_arbitrator');
				$this->db->from('master_arbitrators_tbl');
				if (isset($data['is_empanelled']) && $data['is_empanelled'] == 1) {
					$this->db->where('whether_on_panel', 1);
				}
				if (isset($data['is_empanelled']) && $data['is_empanelled'] == 2) {
					$this->db->where('whether_on_panel', 2);
				}
				$this->db->where('record_status', 1);
				return $this->db->get()->result_array();
				break;

			case 'get_arbitrators_data':
				$this->db->select('*');
				$this->db->from('master_arbitrators_tbl');
				$this->db->where('code', $data);
				return $this->db->get()->row_array();
				break;
			case 'GET_ALL_COUNSELS_LIST':
				$this->db->select('code,name,enrollment_no');
				$this->db->from('master_counsels_tbl');
				$this->db->where('record_status', 1);
				return $this->db->get()->result_array();
				break;

			case 'GET_COUNSEL_DETAILS':
				$this->db->select('*');
				$this->db->from('master_counsels_tbl');
				$this->db->where('code', $data);
				return $this->db->get()->row_array();
				break;

			case 'GET_APPROVED_ARB':
				$this->db->select('*');
				$this->db->from('master_arbitrators_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('approved', 1);
				return $this->db->get()->num_rows();
				break;

			case 'GET_UNAPPROVED_ARB':
				$this->db->select('*');
				$this->db->from('master_arbitrators_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('approved', 0);
				return $this->db->get()->num_rows();
				break;
			case 'get_approved_counsel':
				$this->db->select('*');
				$this->db->from('master_counsels_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('approved', 1);
				return $this->db->get()->num_rows();
				break;
			case 'get_unapproved_counsel':
				$this->db->select('*');
				$this->db->from('master_counsels_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('approved', 0);
				return $this->db->get()->num_rows();
				break;
			case 'get_counsels_data':
				$this->db->select("ct.id,ct.case_no, mct.enrollment_no, mct.name,mct.email, mct.phone_number, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, DATE_FORMAT(ct.date_of_discharge, '%d-%m-%Y') date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number,mct.perm_address_1,mct.perm_address_2,cnt.name as perm_country_name,st.name as perm_state_name,mct.perm_pincode,mct.corr_address_1,mct.corr_address_2,cnt_2.name as corr_country_name,st_2.name as corr_state_name,mct.corr_pincode,ct.code,mct.perm_country as perm_country_code,mct.perm_state as perm_state_code,mct.corr_country as corr_country_code,mct.corr_state as corr_state_code,mct.code as m_code,ct.appearing_for");
				$this->db->from('cs_counsels_tbl as ct');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = ct.appearing_for');
				$this->db->join('master_counsels_tbl as mct', 'mct.code = ct.name_code', 'left');
				$this->db->join('countries as cnt', 'cnt.iso2 = mct.perm_country', 'left');
				$this->db->join('states as st', 'st.id = mct.perm_state', 'left');

				$this->db->join('countries as cnt_2', 'cnt_2.iso2 = mct.corr_country', 'left');
				$this->db->join('states as st_2', 'st_2.id = mct.corr_state', 'left');
				$this->db->where('ct.status', 1);
				$this->db->where('ct.code', $data['code']);
				$this->db->where('ct.case_no', $data['case_no']);
				$result = $this->db->get()->row_array();
				if ($result !== null) {
					return $result;
				} else {
					return false;
				}
				break;

			case 'GET_FEES_ASSESSMENT':
				$this->db->select('*');
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('status', 1);
				$this->db->where('code', $data);
				return $this->db->get()->row_array();
				break;

			case 'CHECK_FEES_ASSESMENT':
				$this->db->select('id');
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('status', 1);
				$this->db->where('assessment_approved', 0);
				$this->db->where('code', $data);
				return $this->db->get()->row_array();
				break;

			case 'GET_PRAYERS_DETAILS':
				$this->db->select('*');
				$this->db->from('cs_assessment_prayers_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('assessment_code', $data);
				$this->db->where('type', 'CLAIM');
				return $this->db->get()->result_array();
				break;

			case 'GET_CC_PRAYERS_DETAILS':
				$this->db->select('*');
				$this->db->from('cs_assessment_prayers_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('assessment_code', $data);
				$this->db->where('type', 'COUNTER_CLAIM');
				return $this->db->get()->result_array();
				break;

			case 'GET_PRAYERS_DETAILS_FOR_EDIT':
				$this->db->select('prayer_name as name, prayer_amount as amount, type, assessment_code, code');
				$this->db->from('cs_assessment_prayers_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('assessment_code', $data);
				$this->db->where('type', 'CLAIM');
				return $this->db->get()->result_array();
				break;

			case 'GET_CC_PRAYERS_DETAILS_FOR_EDIT':
				$this->db->select('prayer_name as name, prayer_amount as amount, type, assessment_code, code');
				$this->db->from('cs_assessment_prayers_tbl');
				$this->db->where('record_status', 1);
				$this->db->where('assessment_code', $data);
				$this->db->where('type', 'COUNTER_CLAIM');
				return $this->db->get()->result_array();
				break;

			case 'GET_GRIEVANCE_ARB_OPT':
				$this->db->select('*');
				$this->db->from('gen_code_desc');
				$this->db->where('status', 1);
				$this->db->where('gen_code_group', 'GRIEVANCE_ARB_OPTIONS');
				$this->db->order_by('sl_no', 'asc');
				return $this->db->get()->result_array();
				break;

			case 'GET_GRIEVANCE_DC_CM_ST_OPT':
				$this->db->select('*');
				$this->db->from('gen_code_desc');
				$this->db->where('status', 1);
				$this->db->where('gen_code_group', 'GRIEVANCE_DC_CM_ST_OPT');
				$this->db->order_by('sl_no', 'asc');
				return $this->db->get()->result_array();
				break;

			case 'GET_GRIEVANCE_PANTRY_OTHER':
				$this->db->select('*');
				$this->db->from('gen_code_desc');
				$this->db->where('status', 1);
				$this->db->where('gen_code_group', 'GRIEVANCE_PANTRY_OTHER');
				$this->db->order_by('sl_no', 'asc');
				return $this->db->get()->result_array();
				break;

			case 'GET_GENDER_FOR_INTERN':
				$this->db->select('*');
				$this->db->from('gen_code_desc');
				$this->db->where('status', 1);
				$this->db->where('gen_code_group', 'GENDER');
				// $this->db->where('sl_no !=', 3);
				$this->db->order_by('sl_no', 'asc');
				return $this->db->get()->result_array();
				break;

			case 'GET_TOTAL_INTERNS_COUNT':
				return $this->db->count_all('internship_tbl');
				break;

			default:
				return array('status' => false, 'msg' => 'NO_OPERATION');
		}
	}
}
