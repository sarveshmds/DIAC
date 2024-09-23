
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Case_model extends CI_Model
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
		$this->load->model(['new_reference_model', 'document_model', 'vakalatnama_model', 'consent_model', 'application_model']);
	}


	public function get($data, $op)
	{
		switch ($op) {
			case 'GET_ALL_CLAIMANT_RESPONDENT_USING_CASE_SLUG':

				return $this->db->select("crt.*")
					->from('cs_claimant_respondant_details_tbl as crt')
					// ->join('country_master as cm', 'cm.country_code = crt.country', 'left')
					->where('crt.case_no', $data['slug'])
					->where('crt.status', 1)
					->order_by('crt.count_number', 'ASC')
					->get()
					->result_array();
				break;
			case 'CASE_ALL_FILES':

				// Get miscellaneous files
				$this->db->select('mt.document');
				$this->db->from('miscellaneous_tbl as mt');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.reference_code = mt.m_code AND cdt.case_type="REFERRALS_REQUESTS"', 'left');
				$this->db->where('cdt.slug', $data['case_no']);
				$this->db->where('status', 1);
				$misc_files = $this->db->get()->row_array();

				$this->db->from('cs_case_details_tbl');
				$this->db->select('case_file');
				$this->db->where('slug', $data['case_no']);
				$this->db->where('status', 1);
				$case_reg_file = $this->db->get()->row_array();

				$this->db->from('cs_noting_tbl');
				$this->db->select('noting_file');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$this->db->order_by('id', 'ASC');
				$noting_files = $this->db->get()->result_array();

				$this->db->from('cs_arbitral_tribunal_tbl');
				$this->db->select('name_of_arbitrator, termination_files');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$this->db->order_by('id', 'ASC');
				$arbitrator_files = $this->db->get()->result_array();

				$this->db->from('cs_fee_details_tbl');
				$this->db->select('assessment_sheet_doc');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$this->db->order_by('id', 'ASC');
				$assessment_files = $this->db->get()->result_array();

				$this->db->from('cs_award_term_tbl');
				$this->db->select('award_term_award_file, factsheet_file');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$award_term_files = $this->db->get()->row_array();

				return [
					'case_reg_file' => $case_reg_file,
					'noting_files' => $noting_files,
					'arbitrator_files' => $arbitrator_files,
					'assessment_files' => $assessment_files,
					'award_term_files' => $award_term_files,
					'misc_files' => $misc_files
				];
				break;

			case 'ALL_DIAC_COORDINATORS':
				$q = $this->db->select('user_code, user_name, user_display_name, job_title')
					->from('user_master')
					->where('record_status', 1)
					->where_in('primary_role', ['COORDINATOR'])
					->order_by('user_display_name', 'ASC')
					->get();
				return $q->result_array();
				break;

			case 'GET_CASEWISE_ARBITRATORS':
				$this->db->select('catt.*,pct.category_name');
				$this->db->from('cs_arbitral_tribunal_tbl as catt');
				$this->db->join('panel_category_tbl as pct', 'pct.id = catt.at_cat_id', 'left');
				$this->db->where('catt.status', 1);
				$this->db->where('catt.case_no', $data['slug']);
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'CHECK_CASE_NO_ALREADY_ENTERED':
				$this->form_validation->set_rules('case_no', 'Case Number', 'required|xss_clean');
				if ($this->form_validation->run()) {

					$case_no = $this->security->xss_clean($this->input->post('case_no'));
					$query = $this->db->get_where('cs_case_details_tbl', array('case_no' => $case_no, 'status' => 1));
					$count = $query->num_rows();

					if ($count > 0) {
						$output = array(
							'status' => true,
							'msg' => 'DIAC registration number is already registered with another case.'
						);
					} else {
						$output = array(
							'status' => false,
							'msg' => 'Case number not found. Please check your case number.'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}

				return $output;
				break;

			case 'GET_ALL_REGISTERED_CASES':
				$this->db->from('cs_case_details_tbl');
				$this->db->select('*');
				$this->db->where('status', 1);
				$this->db->order_by('created_on', 'DESC');
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'ALL_DIAC_USERS':
				$this->db->select('user_code, user_name, user_display_name, job_title');
				$this->db->from('user_master');
				$this->db->where('record_status', 1);
				if (isset($data['role'])) {
					$this->db->where('primary_role', $data['role']);
				} else {
					$this->db->where_in('primary_role', ['CASE_MANAGER', 'ACCOUNTS', 'COORDINATOR', 'DEPUTY_COUNSEL']);
				}
				$this->db->order_by('user_display_name', 'ASC');
				$q = $this->db->get();
				return $q->result_array();
				break;

			case 'ALL_CASE_LIST':

				$select_column = array("case_no", "reffered_on", "registered_on");
				$order_column = array(null, "case_no", null, "reffered_on", null, null, "registered_on", null, null);


				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, DATE_FORMAT(cdt.reffered_on, '%d-%m-%Y') as reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, DATE_FORMAT(cdt.recieved_on, '%d-%m-%Y') as recieved_on, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.case_file, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = cdt.slug', 'left');
				}

				// If date of award from is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$this->db->join('cs_award_term_tbl as catt', 'catt.case_no = cdt.slug', 'left');
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$this->db->join('cs_counsels_tbl as cct', 'cct.case_no = cdt.slug', 'left');
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->like('cdt.case_no', $case_no);
				}

				// If case title is set
				if (isset($_POST['case_title']) && !empty($_POST['case_title'])) {
					$case_title = $this->security->xss_clean($_POST['case_title']);
					$this->db->like('cdt.case_title', $case_title);
				}

				// If arbitration petition number is set
				if (isset($_POST['arbitration_petition']) && !empty($_POST['arbitration_petition'])) {
					$arbitration_petition = $this->security->xss_clean($_POST['arbitration_petition']);
					$this->db->like("cdt.arbitration_petition", $arbitration_petition);
				}

				// If type of arbitration is set
				if (isset($_POST['toa']) && !empty($_POST['toa'])) {
					$toa = $this->security->xss_clean($_POST['toa']);
					$this->db->where('cdt.type_of_arbitration', $toa);
				}

				// If case status is set
				if (isset($_POST['case_status']) && !empty($_POST['case_status'])) {
					$case_status = $this->security->xss_clean($_POST['case_status']);
					$this->db->where('cdt.case_status', $case_status);
				}

				// If registered on from is set ================
				if (isset($_POST['registered_on_from']) && !empty($_POST['registered_on_from'])) {
					$registered_on_from = $this->security->xss_clean($_POST['registered_on_from']);
					$this->db->where("cdt.registered_on >=", date('Y-m-d', strtotime($registered_on_from)));
				}

				// If registered on to is set ================
				if (isset($_POST['registered_on_to']) && !empty($_POST['registered_on_to'])) {
					$registered_on_to = $this->security->xss_clean($_POST['registered_on_to']);
					$this->db->where("cdt.registered_on <=", date('Y-m-d', strtotime($registered_on_to)));
				}


				// If reffered by(court/direct) is set
				if (isset($_POST['reffered_by']) && !empty($_POST['reffered_by'])) {
					$reffered_by = $this->security->xss_clean($_POST['reffered_by']);
					$this->db->where('cdt.reffered_by', $reffered_by);
				}

				// If refered by judge is set
				if (isset($_POST['reffered_by_judge']) && !empty($_POST['reffered_by_judge'])) {
					$reffered_by_judge = $this->security->xss_clean($_POST['reffered_by_judge']);
					$this->db->like('cdt.reffered_by_judge', $reffered_by_judge);
				}

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$name_of_arbitrator = $this->security->xss_clean($_POST['name_of_arbitrator']);
					$this->db->like('cat.name_of_arbitrator', $name_of_arbitrator);
					$this->db->where('cat.status', 1);
				}

				// If date of award is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$date_of_award_from = $this->security->xss_clean($_POST['date_of_award_from']);
					$this->db->where('catt.date_of_award >=', date('Y-m-d', strtotime($date_of_award_from)));
					$this->db->where('catt.status', 1);

					// If data of award to is set
					if (isset($_POST['date_of_award_to']) && !empty($_POST['date_of_award_to'])) {
						$date_of_award_to = $this->security->xss_clean($_POST['date_of_award_to']);
						$this->db->where('catt.date_of_award <=', date('Y-m-d', strtotime($date_of_award_to)));
						$this->db->where('catt.status', 1);
					}
				}

				// If referred on to is set ================
				if (isset($_POST['referred_on']) && !empty($_POST['referred_on'])) {
					$referred_on = $this->security->xss_clean($_POST['referred_on']);
					$this->db->where("cdt.reffered_on", date('Y-m-d', strtotime($referred_on)));
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$name_of_counsel = $this->security->xss_clean($_POST['name_of_counsel']);
					$this->db->like('cct.name', $name_of_counsel);
					$this->db->where('cct.status', 1);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					if ($_POST['sorting_by']) {
						$this->db->order_by('cdt.' . $_POST['sorting_by'], $_POST['sort_to']);
					} else {
						$this->db->order_by('cdt.case_no', 'ASC');
					}
				}


				/*
				Filters End ======================
				*/

				$this->db->where('cdt.status', 1);


				// Clone the db instance
				$tempDb = clone $this->db;

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$query = $this->db->get();
				$fetch_data = $query->result();

				// ==========================================================
				// Pagination
				// ==========================================================
				// Filter records
				$recordsFiltered = $tempDb->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);
				return $output;
				break;


			case 'ALL_REGISTERED_CASE_LIST':

				// $select_column = array(null, "reffered_on", "registered_on");
				$order_column = array(null, null, null, null, null, null, null, null);


				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, CONCAT(cdt.case_no_prefix, cdt.case_no) as case_no_desc, cdt.case_title, DATE_FORMAT(cdt.reffered_on, '%d-%m-%Y') as reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court,  DATE_FORMAT(cdt.recieved_on, '%d-%m-%Y') as recieved_on, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, cdt.case_file, cdt.di_type_of_arbitration, cdt.arbitrator_status,  gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc, gc5.description as di_type_of_arbitration_desc, cdt.reference_code, CASE WHEN cdt.`case_type` = 'REFERRALS_REQUESTS' THEN mt.diary_number WHEN  `cdt`.`case_type` = 'NEW_REFERENCE' THEN enrt.diary_number ELSE 0 END AS diary_number");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
				$this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
				$this->db->join('gen_code_desc as gc5', 'gc5.gen_code = cdt.di_type_of_arbitration AND gc5.gen_code_group = "DI_TYPE_OF_ARBITRATION"', 'left');

				$this->db->join('efiling_new_reference_tbl as enrt', 'enrt.nr_code = cdt.reference_code AND cdt.case_type = "NEW_REFERENCE"', 'left');

				$this->db->join('miscellaneous_tbl as mt', 'mt.m_code = cdt.reference_code AND cdt.case_type = "REFERRALS_REQUESTS"', 'left');

				// $this->db->join('cs_miscellaneous_case_mapping_tbl as cmcmt', 'cmcmt.case_no = cdt.slug', 'left');
				// $this->db->join('miscellaneous_tbl as mt', 'mt.id = cmcmt.misc_id', 'left');

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = cdt.slug', 'left');
				}

				// If date of award from is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$this->db->join('cs_award_term_tbl as catt', 'catt.case_no = cdt.slug', 'left');
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$this->db->join('cs_counsels_tbl as cct', 'cct.case_no = cdt.slug', 'left');
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->like('cdt.case_no', $case_no);
				}

				// If case title is set
				if (isset($_POST['case_title']) && !empty($_POST['case_title'])) {
					$case_title = $this->security->xss_clean($_POST['case_title']);
					$this->db->like('cdt.case_title', $case_title);
				}

				// If arbitration petition number is set
				if (isset($_POST['arbitration_petition']) && !empty($_POST['arbitration_petition'])) {
					$arbitration_petition = $this->security->xss_clean($_POST['arbitration_petition']);
					$this->db->like("cdt.arbitration_petition", $arbitration_petition);
				}

				// If type of arbitration is set
				if (isset($_POST['toa']) && !empty($_POST['toa'])) {
					$toa = $this->security->xss_clean($_POST['toa']);
					$this->db->where('cdt.type_of_arbitration', $toa);
				}

				// If case status is set
				if (isset($_POST['case_status']) && !empty($_POST['case_status'])) {
					$case_status = $this->security->xss_clean($_POST['case_status']);
					$this->db->where('cdt.case_status', $case_status);
				}

				// If registered on from is set ================
				if (isset($_POST['registered_on_from']) && !empty($_POST['registered_on_from'])) {
					$registered_on_from = $this->security->xss_clean($_POST['registered_on_from']);
					$this->db->where("cdt.registered_on >=", date('Y-m-d', strtotime($registered_on_from)));
				}

				// If registered on to is set ================
				if (isset($_POST['registered_on_to']) && !empty($_POST['registered_on_to'])) {
					$registered_on_to = $this->security->xss_clean($_POST['registered_on_to']);
					$this->db->where("cdt.registered_on <=", date('Y-m-d', strtotime($registered_on_to)));
				}


				// If reffered by(court/direct) is set
				if (isset($_POST['reffered_by']) && !empty($_POST['reffered_by'])) {
					$reffered_by = $this->security->xss_clean($_POST['reffered_by']);
					$this->db->where('cdt.reffered_by', $reffered_by);
				}

				// If refered by judge is set
				if (isset($_POST['reffered_by_judge']) && !empty($_POST['reffered_by_judge'])) {
					$reffered_by_judge = $this->security->xss_clean($_POST['reffered_by_judge']);
					$this->db->like('cdt.reffered_by_judge', $reffered_by_judge);
				}

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$name_of_arbitrator = $this->security->xss_clean($_POST['name_of_arbitrator']);
					$this->db->like('cat.name_of_arbitrator', $name_of_arbitrator);
					$this->db->where('cat.status', 1);
				}

				// If date of award is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$date_of_award_from = $this->security->xss_clean($_POST['date_of_award_from']);
					$this->db->where('catt.date_of_award >=', date('Y-m-d', strtotime($date_of_award_from)));
					$this->db->where('catt.status', 1);

					// If data of award to is set
					if (isset($_POST['date_of_award_to']) && !empty($_POST['date_of_award_to'])) {
						$date_of_award_to = $this->security->xss_clean($_POST['date_of_award_to']);
						$this->db->where('catt.date_of_award <=', date('Y-m-d', strtotime($date_of_award_to)));
						$this->db->where('catt.status', 1);
					}
				}

				// If referred on to is set ================
				if (isset($_POST['referred_on']) && !empty($_POST['referred_on'])) {
					$referred_on = $this->security->xss_clean($_POST['referred_on']);
					$this->db->where("cdt.reffered_on", date('Y-m-d', strtotime($referred_on)));
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$name_of_counsel = $this->security->xss_clean($_POST['name_of_counsel']);
					$this->db->like('cct.name', $name_of_counsel);
					$this->db->where('cct.status', 1);
				}

				// If referred on to is set ================
				if (isset($_POST['case_type']) && !empty($_POST['case_type'])) {
					$case_type = $this->security->xss_clean($_POST['case_type']);
					$this->db->where("cdt.di_type_of_arbitration", $case_type);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					if ($_POST['sorting_by']) {
						$this->db->order_by('cdt.' . $_POST['sorting_by'], $_POST['sort_to']);
					} else {
						$this->db->order_by('cdt.case_no', 'DESC');
					}
				}


				/*
				Filters End ======================
				*/

				$this->db->where('cdt.is_registered', 1);
				$this->db->where('cdt.status', 1);

				// Clone the db instance
				$tempDb = clone $this->db;

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$query = $this->db->get();
				// echo $this->db->last_query();
				// die;
				$fetch_data = $query->result();



				$recordsFiltered = $tempDb->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);
				return $output;
				break;


			case 'CHECK_CASE_NUMBER':

				$this->form_validation->set_rules('ecn_case_no', 'Case Number', 'required|xss_clean');
				$this->form_validation->set_rules('ecn_type', 'Type', 'required|xss_clean');

				if ($this->form_validation->run()) {

					$case_no = $this->security->xss_clean($this->input->post('ecn_case_no'));
					$type = $this->security->xss_clean($this->input->post('ecn_type'));

					$query = $this->db->get_where('cs_case_details_tbl', array('slug' => $case_no, 'status' => 1));
					$count = $query->num_rows();

					if ($count == 1) {

						// Get case details to use it's slug
						$case_details = $this->db->get_where('cs_case_details_tbl', array('slug' => $case_no, 'status' => 1))->row_array();

						if ($case_details) {

							$case_slug = $case_details['slug'];

							// Check if the case is allotted to the user ot not
							$check_result = $this->common_model->check_user_case_allotment($case_slug, $type);

							// If check result is true
							if ($check_result) {

								// Check for the type and redirect on the required page
								if ($type == 'STATUS_OF_PLEADINGS') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'status-of-pleadings/' . $case_slug
									);
								} elseif ($type == 'CLAIMANT_RESPONDANT_DETAILS') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'claimant-respondant-details/' . $case_slug
									);
								} elseif ($type == 'COUNSELS') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'counsels/' . $case_slug
									);
								} elseif ($type == 'ARBITRAL_TRIBUNAL') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'arbitral-tribunal/' . $case_slug
									);
								} elseif ($type == 'CASE_FEES_DETAILS') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'case-fees-details/' . $case_slug
									);
								} elseif ($type == 'AWARD_TERMINATION') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'termination/' . $case_slug
									);
								} elseif ($type == 'NOTING') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'noting/' . $case_slug
									);
								} elseif ($type == 'CASE_ORDERS') {
									$output = array(
										'status' => true,
										'redirect_url' => base_url() . 'case-order/' . $case_slug
									);
								} else {
									$output = array(
										'status' => false,
										'msg' => 'Invalid type is provided. Please contact support.'
									);
								}
							} else {
								$output = array(
									'status' => false,
									'msg' => 'Case is not alloted to you.'
								);
							}
						} else {
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong. Please try again.'
							);
						}
					} else {
						$output = array(
							'status' => false,
							'msg' => 'Case number not found. Please check your case number.'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}

				return $output;
				break;

			case 'GET_CASE_BASIC_DATA':
				$this->db->select("cdt.slug as cdt_slug ,cdt.case_no as case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court,  cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration,cdt.case_status, cdt.remarks, cdt.case_file, cdt.arbitrator_status, crd.name as case_title_claimant_name, crd2.name as case_title_respondent_name, cdt.di_type_of_arbitration, cdt.case_type, cdt.reference_code");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('cs_claimant_respondant_details_tbl as crd', 'crd.id = cdt.case_title_claimant', 'left');
				$this->db->join('cs_claimant_respondant_details_tbl as crd2', 'crd2.id = cdt.case_title_respondent', 'left');

				$this->db->where('cdt.slug', $data['slug']);
				$this->db->where('cdt.status', 1);
				$output = $this->db->get()->row_array();

				return $output;
				break;

			case 'GET_CASE_NUMBER_USING_SLUG':
				$this->db->select("cdt.*");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->where('cdt.slug', $data['case_no']);
				$this->db->where('cdt.status', 1);
				$output = $this->db->get()->row_array();

				return $output;
				break;

			case 'VIEW_REGISTER_CASE_DATA':

				return $this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.di_type_of_arbitration, cdt.case_status, cdt.case_file, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, cdt.created_on, cdt.updated_on, gc4.description as di_type_of_arbitration_desc, gc5.description as arbitration_status_desc, enrt.soc_document, enrt.urgency_app_document, enrt.proof_of_service_doc, enrt.document as nr_case_document")
					->from('cs_case_details_tbl as cdt')
					->join('efiling_new_reference_tbl as enrt', 'enrt.nr_code = cdt.reference_code AND cdt.case_type="NEW_REFERENCE"', 'left')
					->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left')
					->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left')
					->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left')
					->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.di_type_of_arbitration AND gc4.gen_code_group = "DI_TYPE_OF_ARBITRATION"', 'left')
					->join('gen_code_desc as gc5', 'gc5.gen_code = cdt.arbitrator_status AND gc5.gen_code_group = "ARBITRATOR_STATUS"', 'left')
					// ->where('gc2.gen_code_group', 'REFFERED_BY')
					// ->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION')
					// ->where('gc4.gen_code_group', 'DI_TYPE_OF_ARBITRATION')
					// ->where('gc5.gen_code_group', 'ARBITRATOR_STATUS')
					->where('cdt.slug', $data['slug'])
					->where('cdt.status', 1)
					->get()
					->row_array();

				break;

			case 'ALL_CASE_DATA':

				$case_data = $this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, cdt.created_on, cdt.updated_on, gc4.description as di_type_of_arbitration_desc, gc5.description as arbitration_status_desc")
					->from('cs_case_details_tbl as cdt')
					->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left')
					->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left')
					->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left')
					->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.di_type_of_arbitration AND gc4.gen_code_group = "DI_TYPE_OF_ARBITRATION"', 'left')
					->join('gen_code_desc as gc5', 'gc5.gen_code = cdt.arbitrator_status AND gc5.gen_code_group = "ARBITRATOR_STATUS"', 'left')
					// ->where('gc2.gen_code_group', 'REFFERED_BY')
					// ->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION')
					// ->where('gc4.gen_code_group', 'DI_TYPE_OF_ARBITRATION')
					// ->where('gc5.gen_code_group', 'ARBITRATOR_STATUS')
					->where('cdt.slug', $data['slug'])
					->where('cdt.status', 1)
					->get()
					->row_array();

				// Get all claimant of case
				$claimant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc,  crt.created_on, crt.updated_on")
					->from('cs_claimant_respondant_details_tbl as crt')
					->where('case_no', $data['slug'])
					->where('type', 'claimant')
					->where('status', 1)
					->order_by('count_number', 'ASC')
					->get()
					->result_array();


				// Get all respondant of case
				$respondant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc,  crt.created_on, crt.updated_on")
					->from('cs_claimant_respondant_details_tbl as crt')
					->where('crt.case_no', $data['slug'])
					->where('crt.type', 'respondant')
					->where('crt.status', 1)
					->order_by('count_number', 'ASC')
					->get()
					->result_array();


				// Get the status of  pleadings of data
				$sop_data = $this->db->select("*")
					->from('cs_status_of_pleadings_tbl')
					->where('case_no', $data['slug'])
					->where('status', 1)
					->get()
					->row_array();

				// Get the other pleadings data
				$sop_op_data = $this->db->select("id, case_no, details, date_of_filing, filed_by, created_at, updated_at")
					->from('cs_other_pleadings_tbl')
					->where('case_no', $data['slug'])
					->where('status', 1)
					->get()
					->result_array();


				// Arbitral tribunals
				$arbitral_tribunal = $this->db->select("cat.id, amt.name_of_arbitrator, amt.whether_on_panel,CASE WHEN amt.whether_on_panel = 'yes' THEN 'Yes' WHEN amt.whether_on_panel = 'no' THEN 'No' ELSE '' END as whether_on_panel_desc, amt.category, cat.appointed_by, cat.date_of_appointment, cat.date_of_declaration, cat.arb_terminated,CASE WHEN cat.arb_terminated = 'yes' THEN 'Yes' WHEN cat.arb_terminated = 'no' THEN 'No' ELSE '' END as arb_terminated_desc, cat.date_of_termination, cat.reason_of_termination, pct.category_name, gc.description as appointed_by_desc, cat.created_at, cat.updated_at, gc2.description as arbitrator_type_desc")
					->from('cs_arbitral_tribunal_tbl as cat')
					->join('master_arbitrators_tbl as amt', 'amt.code = cat.name_of_arbitrator', 'left')
					->join('panel_category_tbl as pct', 'pct.id = amt.category', 'left')
					->join('gen_code_desc as gc', 'gc.gen_code = cat.appointed_by AND gc.gen_code_group = "APPOINTED_BY"', 'left')
					->join('gen_code_desc as gc2', 'gc2.gen_code = cat.arbitrator_type AND gc2.gen_code_group = "ARBITRATOR_TYPE"', 'left')
					->where('cat.case_no', $data['slug'])
					->where('cat.status', 1)
					->get()
					->result_array();

				// Counsels
				$counsels = $this->db->select("ct.id, ct.case_no, mct.enrollment_no, mct.name, ct.appearing_for, mct.email, mct.phone_number, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, ct.date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, ct.created_at, ct.updated_at")
					->from('cs_counsels_tbl as ct')
					->join('master_counsels_tbl as mct', 'mct.code = ct.name_code', 'left')
					->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = ct.appearing_for', 'left')
					->where('ct.case_no', $data['slug'])
					->where('ct.status', 1)
					->get()
					->result_array();


				// Hearings
				$hearings = $this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc,mat.name_of_arbitrator as arbitrator")
					->from('cause_list_tbl AS clt')
					->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left')
					->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left')
					->join('master_arbitrators_tbl as mat', 'mat.code = clt.arbitrator_name', 'left')
					->where('clt.slug', $data['slug'])
					->where('clt.active_status', 1)
					->get()
					->result_array();

				// cost deposit
				$cost_deposit = $this->db->select("cd.id, cd.case_no, cd.date_of_deposit, cd.deposited_by, cd.name_of_depositor, cd.cost_imposed_dated,cd.mode_of_deposit, cd.details_of_deposit, cd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc2.description mod_description, cd.created_at, cd.updated_at")
					->from('cs_cost_deposit_tbl as cd')
					->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = cd.deposited_by')
					->join('gen_code_desc as gc2', 'gc2.gen_code = cd.mode_of_deposit')
					->where('cd.case_no', $data['slug'])
					->where('cd.status', 1)
					->get()
					->result_array();

				// Fee Deposit
				$fee_deposit = $this->db->select("fd.id, fd.case_no, fd.date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description, fd.created_at, fd.updated_at")
					->from('cs_fee_deposit_tbl as fd')
					->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by')
					->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards')
					->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit')

					->where('fd.case_no', $data['slug'])
					->where('fd.status', 1)
					->get()
					->result_array();



				// Fee Refund
				$fee_refund = $this->db->select("fr.id, fr.case_no, fr.date_of_refund, fr.refunded_to, fr.name_of_party, fr.refunded_towards, fr.mode_of_refund, fr.details_of_refund, fr.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description refunded_towards_description, gc2.description mod_refund, fr.created_at, fr.updated_at")
					->from('cs_fee_refund_tbl as fr')
					->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fr.refunded_to')
					->join('gen_code_desc as gc', 'gc.gen_code = fr.refunded_towards')
					->join('gen_code_desc as gc2', 'gc2.gen_code = fr.mode_of_refund')

					->where('fr.case_no', $data['slug'])
					->where('fr.status', 1)
					->get()
					->result_array();

				// Fee released

				$fee_released = $this->db->select("fr.id, fr.case_no, fr.date_of_fee_released, fr.released_to, fr.mode_of_payment, fr.details_of_fee_released, fr.amount, gc.description as mop_description, fr.created_at, fr.updated_at,mat.name_of_arbitrator as arbitrator")
					->from('cs_at_fee_released_tbl as fr')
					->join('gen_code_desc as gc', 'gc.gen_code = fr.mode_of_payment')
					->join('cs_arbitral_tribunal_tbl as art', 'art.at_code = fr.released_to', 'left')
					->join('master_arbitrators_tbl as mat', 'mat.code = art.name_of_arbitrator', 'left')
					->where('fr.case_no', $data['slug'])
					->where('fr.status', 1)
					->get()
					->result_array();

				// Noting
				$notings = $this->db->select("nt.id, nt.case_no, nt.noting, nt.noting_date, nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um2.user_display_name marked_by_user, nt.created_at, nt.updated_at, gcd.description as noting_text")
					->from('cs_noting_tbl as nt')
					->join('user_master as um', 'um.user_code = nt.marked_to')
					->join('user_master as um2', 'um2.user_code = nt.marked_by')
					->join('gen_code_desc as gcd', 'gcd.gen_code = nt.noting_text_code AND gcd.gen_code_group = "NOTING_SMART_TEXT"', 'left')
					->where('nt.case_no', $data['slug'])
					->where('nt.status', 1)
					->order_by('nt.created_at', 'DESC')
					->get()
					->result_array();

				// Get the award termination data
				$award_term_data = $this->db->select("cat.*, gc.description as nature_of_award_desc, gc.description as termination_reason_desc")
					->from('cs_award_term_tbl cat')
					->join('gen_code_desc as gc', 'gc.gen_code = cat.nature_of_award AND gc.gen_code_group="TERM_NATURE_OF_AWARD"', 'left')
					->join('gen_code_desc as gc2', 'gc2.gen_code = cat.nature_of_award AND gc2.gen_code_group="CASE_TERMINATION_REASON"', 'left')
					->where('cat.case_no', $data['slug'])
					->where('cat.status', 1)
					->get()
					->row_array();

				// Get the fee and cost details
				$fee_cost_data = $this->db->select("*")
					->from('cs_fee_details_tbl')
					->where('case_no', $data['slug'])
					->where('status', 1)
					->get()
					->result_array();

				// Get total amount and balanace amount
				$this->db->select("cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, (cs_arb_fees + cs_adminis_fees + rs_arb_fees + rs_adminis_fee) as total_amount, created_at, updated_at");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['slug']);
				$this->db->where('status', 1);
				$query = $this->db->get();
				$ta_data = $query->row_array();

				// Get fee deposited
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['slug']);
				$this->db->where('status', 1);
				$query = $this->db->get();
				$fd_data = $query->row_array();

				$amount_n_balance['total_amount'] = (isset($ta_data['total_amount']) && $ta_data['total_amount']) ? $ta_data['total_amount'] : 0;
				$amount_n_balance['deposit_amount'] = (isset($fd_data['fee_deposit'])) ? $fd_data['fee_deposit'] : 0;
				$amount_n_balance['balance'] = $amount_n_balance['total_amount'] - $amount_n_balance['deposit_amount'];


				// E-filing Documents
				$e_documents = $this->document_model->get_all_documents_list($data['slug']);

				// E-filing Vakalatnama
				$e_vakalatnamas = $this->vakalatnama_model->get_all_vakalatnama_list($data['slug']);

				// E-filing Concent
				$e_concents = $this->consent_model->get_all_concent_list($data['slug']);

				// E-filing Applications
				$e_applications = $this->application_model->get_all_applications_list($data['slug']);


				/*
				* Last updated details list of all tables
				*/

				$last_updated_details = $this->common_model->get_last_updated_datetime($data['slug']);


				$output = array(
					'case_data' => $case_data,
					'claimant_data' => $claimant_data,
					'respondant_data' => $respondant_data,
					'arbitral_tribunal' => $arbitral_tribunal,
					'counsels' => $counsels,
					'cost_deposit' => $cost_deposit,
					'fee_deposit' => $fee_deposit,
					'fee_released' => $fee_released,
					'fee_refund' => $fee_refund,
					'sop_data' => $sop_data,
					'sop_op_data' => $sop_op_data,
					'fee_cost_data' => $fee_cost_data,
					'award_term_data' => $award_term_data,
					'notings' => $notings,
					'amount_n_balance' => $amount_n_balance,
					'last_updated' => $last_updated_details,
					'e_documents' => $e_documents,
					'e_vakalatnamas' => $e_vakalatnamas,
					'e_concents' => $e_concents,
					'e_applications' => $e_applications,
					'hearings' => $hearings,
				);

				return $output;
				break;

			case 'GET_STATUS_OF_PLEADINGS':
				// Get the status of  pleadings of data
				$sop_data = $this->db->select("*")
					->from('cs_status_of_pleadings_tbl')
					->where('case_no', $data['case_no'])
					->where('status', 1)
					->get()
					->row_array();

				return $sop_data;
				break;

			case 'GET_AWARD_TERMINATION':
				// Get the award termination data
				$award_term_data = $this->db->select("*")
					->from('cs_award_term_tbl')
					->where('case_no', $data['case_no'])
					->where('status', 1)
					->get()
					->row_array();

				return $award_term_data;
				break;

			case 'GET_ALL_CLAIMANT_RESPONDENT':
				// Get all claimant and respondant of case
				$claim_res_data = $this->db->select("*")
					->from('cs_claimant_respondant_details_tbl')
					->where('case_no', $data['case_no'])
					->where('status', 1)
					->order_by('count_number', 'ASC')
					->get()
					->result_array();
				return $claim_res_data;
				break;

			case 'GET_FEE_COST_DATA':
				// Get the fee and cost details
				$fee_cost_data = $this->db->select("id, c_cc_asses_sep, sum_in_dispute, sum_in_dispute_claim, sum_in_dispute_cc, asses_date, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee")
					->from('cs_fee_details_tbl')
					->where('case_no', $data['case_no'])
					->where('status', 1)
					->get()
					->row_array();

				return $fee_cost_data;
				break;


			case 'CASE_ARBITRAL_TRIBUNAL_LIST':

				$select_column = array("name_of_arbitrator");


				$this->db->select("cat.id,cat.at_code, cat.arbitrator_type, cat.name_of_arbitrator as name_of_arbitrator_code, amt.name_of_arbitrator, amt.email, amt.contact_no, amt.whether_on_panel,CASE WHEN amt.whether_on_panel = 'yes' THEN 'Yes' WHEN amt.whether_on_panel = 'no' THEN 'No' ELSE '' END as whether_on_panel_desc,amt.category, cat.appointed_by, DATE_FORMAT(cat.date_of_appointment, '%d-%m-%Y') as date_of_appointment, DATE_FORMAT(cat.date_of_declaration, '%d-%m-%Y') as date_of_declaration, cat.arb_terminated,CASE WHEN cat.arb_terminated = 'yes' THEN 'Yes' WHEN cat.arb_terminated = 'no' THEN 'No' ELSE '' END as arb_terminated_desc, DATE_FORMAT(cat.date_of_termination, '%d-%m-%Y') as date_of_termination, cat.reason_of_termination, pct.category_name, gc.description as appointed_by_desc, gc2.description as arbitrator_type_desc, cat.at_termination_by, cat.termination_files,pct.code as category_code,amt.perm_address_1,amt.perm_address_2,amt.perm_country,amt.perm_state,amt.perm_pincode,amt.corr_address_1,amt.corr_address_2,amt.corr_country,amt.corr_state,amt.corr_pincode");
				$this->db->from('cs_arbitral_tribunal_tbl as cat');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cat.appointed_by AND gc.gen_code_group="APPOINTED_BY"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cat.arbitrator_type AND gc2.gen_code_group="ARBITRATOR_TYPE"', 'left');
				$this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.name_of_arbitrator', 'left');
				$this->db->join('panel_category_tbl as pct', 'pct.code = amt.category', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'cat.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR cat." . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}


				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->order_by('cat.id', 'DESC');
				$this->db->where('cat.status', 1)->where('cat.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = count($fetch_data);

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_arbitral_tribunal_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_CLAIMANT_LIST':


				$select_column = array("type", "name", "count_number", " contact", "email", "removed");
				$order_column = array(null, "name", "count_number", " contact", "email", null);


				$this->db->select("crt.*, CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc");
				$this->db->from('cs_claimant_respondant_details_tbl as crt');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no'])->where('type', 'claimant');
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('type', 'claimant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('type', 'claimant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_RESPONDANT_LIST':

				$select_column = array("type", "name", "count_number", " contact", "email", "removed");
				$order_column = array(null, "name", "count_number", " contact", "email", null);


				$this->db->select("crt.*, CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc,  crt.counter_claimant");
				$this->db->from('cs_claimant_respondant_details_tbl as crt');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no'])->where('type', 'respondant');
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('type', 'respondant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('type', 'respondant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_OTHER_PLEADING_LIST':

				$select_column = array("details", "date_of_filing", "filed_by");
				$order_column = array(null, "date_of_filing", "filed_by");


				$this->db->select("id, case_no, details, date_of_filing, filed_by");
				$this->db->from('cs_other_pleadings_tbl');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_pleadings_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_pleadings_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_OTHER_CORRESPONDANCE_LIST':

				$select_column = array("details", "date_of_correspondance", "send_by", "sent_to");
				$order_column = array(null, "date_of_filing", null, null);


				$this->db->select("id, case_no, details, date_of_correspondance, send_by, sent_to");
				$this->db->from('cs_other_correspondance_tbl');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_correspondance_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_correspondance_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_COUNSELS_LIST':

				$select_column = array("name");
				// $order_column = array("name", "appearing_for", "email", "phone", 'date_of_discharge');


				$this->db->select("ct.id,ct.case_no, mct.enrollment_no, mct.name, ct.appearing_for,mct.email, mct.phone_number, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, DATE_FORMAT(ct.date_of_discharge, '%d-%m-%Y') date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, cl_res.type as cl_res_type, mct.perm_address_1,mct.perm_address_2,cnt.name as perm_country_name,st.name as perm_state_name,mct.perm_pincode,mct.corr_address_1,mct.corr_address_2,cnt_2.name as corr_country_name,st_2.name as corr_state_name,mct.corr_pincode,ct.code,mct.perm_country as perm_country_code,mct.perm_state as perm_state_code,mct.corr_country as corr_country_code,mct.corr_state as corr_state_code");
				$this->db->from('cs_counsels_tbl as ct');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.code = ct.appearing_for', 'left');
				$this->db->join('master_counsels_tbl as mct', 'mct.code = ct.name_code', 'left');
				$this->db->join('countries as cnt', 'cnt.iso2 = mct.perm_country', 'left');
				$this->db->join('states as st', 'st.id = mct.perm_state', 'left');

				$this->db->join('countries as cnt_2', 'cnt_2.iso2 = mct.corr_country', 'left');
				$this->db->join('states as st_2', 'st_2.id = mct.corr_state', 'left');


				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'mct.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . 'mct.' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('ct.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('ct.status', 1)->where('ct.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_counsels_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_counsels_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

				// this function is created by ameen
			case 'CASE_ORDER_LIST':

				$select_column = array("order_given_by", "document", "created_at");
				$order_column = array("order_given_by", "document", "created_at");

				$this->db->select("cot.order_given_by, cot.document, cot.created_at, cot.code, um.user_display_name, um.job_title");
				$this->db->from('case_orders_tbl cot');
				$this->db->join('user_master as um', 'um.user_code = cot.user_code', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= '' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . '' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				$this->db->order_by('cot.id', 'DESC');

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('cot.record_status', 1)->where('case_code', $data['case_code']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where([
					'case_code' => $data['case_code'],
					'record_status' => 1
				])->select("*")->from('case_orders_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where([
					'case_code' => $data['case_code'],
					'record_status' => 1
				])->select("*")->from('case_orders_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;


			case 'GET_FEES_ASSESSMENT':
				$select_column = array("cdt.case_no", "cdt.case_title");
				$order_column = array("cdt.case_no", "cdt.case_title");

				$this->db->select("cdt.case_no,cdt.case_title, fdt.sum_in_dispute, fdt.asses_date,fdt.code as case_code,fdt.assessment_approved,CASE WHEN fdt.assessment_approved = '0' THEN 'Pending' WHEN fdt.assessment_approved = '2' THEN 'Reject' END as assessment_approved_desc ");
				$this->db->from('cs_fee_details_tbl as fdt');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = fdt.case_no', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= '' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . '' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				$this->db->order_by('fdt.id', 'DESC');

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fdt.status', 1)->where('assessment_approved', 0);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// echo "<pre>";
				// print_r($fetch_data);
				// echo "</pre>";
				// die;

				// Filter records
				$recordsFiltered = $this->db->where([
					'status' => 1
				])->select("*")->from('cs_fee_details_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where([
					'status' => 1
				])->select("*")->from('cs_fee_details_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;




			case 'CASE_NOTING_LIST':

				$select_column = array("noting", "noting_date", "marked_to", "next_date");
				$order_column = array(null, "marked_to", null, null, "noting_date", "next_date");


				$this->db->query('SET @row_number = 0');
				$this->db->select("nt.id, nt.case_no, nt.noting, DATE_FORMAT(nt.noting_date, '%d-%m-%Y') as noting_date, DATE_FORMAT(nt.next_date, '%d-%m-%Y') as next_date,nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um.job_title as marked_to_job_title, um2.user_display_name marked_by_user, um2.job_title as marked_by_job_title, nt.noting_file, (@row_number:=@row_number + 1) AS serial_no, gcd.description as noting_text");
				$this->db->from('cs_noting_tbl as nt');
				$this->db->join('user_master as um', 'um.user_code = nt.marked_to');
				$this->db->join('user_master as um2', 'um2.user_code = nt.marked_by');
				$this->db->join('gen_code_desc as gcd', 'gcd.gen_code = nt.noting_text_code AND gcd.gen_code_group = "NOTING_SMART_TEXT"', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'nt.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . 'nt.' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				// $this->db->order_by('nt.id', 'DESC');
				$this->db->order_by('serial_no', 'DESC');

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('nt.status', 1)->where('nt.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_noting_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_noting_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_ALLOTMENT_LIST':

				$select_column = array("case_no");
				$order_column = array("case_no", "alloted_to", null);

				$this->db->select("ca.id, ca.case_no, CONCAT(cdt.case_no_prefix, cdt.case_no) as case_no_desc, cdt.case_title, ca.alloted_to, ca.alloted_by, um.user_display_name as alloted_to_name, DATE_FORMAT(ca.created_at, '%d-%m-%Y') as alloted_on, ca.user_role, rm.role_name as user_role_desc");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no', 'left');
				$this->db->join('user_master as um', 'um.user_code = ca.alloted_to', 'left');
				$this->db->join('role_master as rm', 'rm.role_code = ca.user_role', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'cdt.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . 'cdt.' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('ca.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->like('cdt.case_no', $case_no);
				}

				// If allotted to is set
				if (isset($_POST['alloted_to']) && !empty($_POST['alloted_to'])) {
					$alloted_to = $this->security->xss_clean($_POST['alloted_to']);
					$this->db->where('ca.alloted_to', $alloted_to);
				}

				/*
					Filters End ===================
				*/

				$this->db->where('ca.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$this->db->select("ca.id, ca.case_no, CONCAT(cdt.case_no_prefix, cdt.case_no) as case_no_desc, ca.alloted_to, ca.alloted_by, um.user_display_name as alloted_to_name, DATE_FORMAT(ca.created_at, '%d-%m-%Y') as alloted_on, ca.user_role, rm.role_name as user_role_desc");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no', 'left');
				$this->db->join('user_master as um', 'um.user_code = ca.alloted_to', 'left');
				$this->db->join('role_master as rm', 'rm.role_code = ca.user_role', 'left');
				$this->db->where('ca.status', 1);
				/*
					Filters Start ===================
				*/

				// If case number is set
				if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->like('cdt.case_no', $case_no);
				}

				// If allotted to is set
				if (isset($_POST['alloted_to']) && !empty($_POST['alloted_to'])) {
					$alloted_to = $this->security->xss_clean($_POST['alloted_to']);
					$this->db->where('ca.alloted_to', $alloted_to);
				}

				/*
					Filters End ===================
				*/
				$recordsFiltered = $this->db->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'ALLOTTED_CASE_LIST':

				$select_column = array("case_no");
				$order_column = array("case_no", null, null);

				$this->db->select("ca.id, ca.case_no, cdt.slug as case_slug, CONCAT(cdt.case_no_prefix, cdt.case_no) as case_no_desc, cdt.case_title as case_title, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, gc.description as case_status,ca.alloted_to, ca.alloted_by, um.user_display_name as alloted_to_name, DATE_FORMAT(ca.created_at, '%d-%m-%Y') as alloted_on, cdt.arbitrator_status, gc2.description as arbitrator_status_desc,  cdt.type_of_arbitration");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no');
				$this->db->join('user_master as um', 'um.user_code = ca.alloted_to');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status AND gc.gen_code_group = "CASE_STATUS"');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.arbitrator_status AND gc2.gen_code_group = "ARBITRATOR_STATUS"');

				$this->db->where('ca.alloted_to', $this->user_code);
				$this->db->where('ca.status', 1);

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = cdt.slug', 'left');
				}

				// If date of award from is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$this->db->join('cs_award_term_tbl as catt', 'catt.case_no = cdt.slug', 'left');
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$this->db->join('cs_counsels_tbl as cct', 'cct.case_no = cdt.slug', 'left');
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->like('cdt.case_no', $case_no);
				}

				// If case title is set
				if (isset($_POST['case_title']) && !empty($_POST['case_title'])) {
					$case_title = $this->security->xss_clean($_POST['case_title']);
					$this->db->like('cdt.case_title', $case_title);
				}

				// If arbitration petition number is set
				if (isset($_POST['arbitration_petition']) && !empty($_POST['arbitration_petition'])) {
					$arbitration_petition = $this->security->xss_clean($_POST['arbitration_petition']);
					$this->db->like("cdt.arbitration_petition", $arbitration_petition);
				}

				// If type of arbitration is set
				if (isset($_POST['toa']) && !empty($_POST['toa'])) {
					$toa = $this->security->xss_clean($_POST['toa']);
					$this->db->where('cdt.type_of_arbitration', $toa);
				}

				// If case status is set
				if (isset($_POST['case_status']) && !empty($_POST['case_status'])) {
					$case_status = $this->security->xss_clean($_POST['case_status']);
					$this->db->where('cdt.case_status', $case_status);
				}

				// If registered on from is set ================
				if (isset($_POST['registered_on_from']) && !empty($_POST['registered_on_from'])) {
					$registered_on_from = $this->security->xss_clean($_POST['registered_on_from']);
					$this->db->where("cdt.registered_on >=", date('Y-m-d', strtotime($registered_on_from)));
				}

				// If registered on to is set ================
				if (isset($_POST['registered_on_to']) && !empty($_POST['registered_on_to'])) {
					$registered_on_to = $this->security->xss_clean($_POST['registered_on_to']);
					$this->db->where("cdt.registered_on <=", date('Y-m-d', strtotime($registered_on_to)));
				}


				// If reffered by(court/direct) is set
				if (isset($_POST['reffered_by']) && !empty($_POST['reffered_by'])) {
					$reffered_by = $this->security->xss_clean($_POST['reffered_by']);
					$this->db->where('cdt.reffered_by', $reffered_by);
				}

				// If refered by judge is set
				if (isset($_POST['reffered_by_judge']) && !empty($_POST['reffered_by_judge'])) {
					$reffered_by_judge = $this->security->xss_clean($_POST['reffered_by_judge']);
					$this->db->like('cdt.reffered_by_judge', $reffered_by_judge);
				}

				// If name of arbitrator is set
				if (isset($_POST['name_of_arbitrator']) && !empty($_POST['name_of_arbitrator'])) {
					$name_of_arbitrator = $this->security->xss_clean($_POST['name_of_arbitrator']);
					$this->db->like('cat.name_of_arbitrator', $name_of_arbitrator);
					$this->db->where('cat.status', 1);
				}

				// If date of award is set
				if (isset($_POST['date_of_award_from']) && !empty($_POST['date_of_award_from'])) {
					$date_of_award_from = $this->security->xss_clean($_POST['date_of_award_from']);
					$this->db->where('catt.date_of_award >=', date('Y-m-d', strtotime($date_of_award_from)));
					$this->db->where('catt.status', 1);

					// If data of award to is set
					if (isset($_POST['date_of_award_to']) && !empty($_POST['date_of_award_to'])) {
						$date_of_award_to = $this->security->xss_clean($_POST['date_of_award_to']);
						$this->db->where('catt.date_of_award <=', date('Y-m-d', strtotime($date_of_award_to)));
						$this->db->where('catt.status', 1);
					}
				}

				// If referred on to is set ================
				if (isset($_POST['referred_on']) && !empty($_POST['referred_on'])) {
					$referred_on = $this->security->xss_clean($_POST['referred_on']);
					$this->db->where("cdt.reffered_on", date('Y-m-d', strtotime($referred_on)));
				}

				// If counsel is set
				if (isset($_POST['name_of_counsel']) && !empty($_POST['name_of_counsel'])) {
					$name_of_counsel = $this->security->xss_clean($_POST['name_of_counsel']);
					$this->db->like('cct.name', $name_of_counsel);
					$this->db->where('cct.status', 1);
				}

				/*
					Filters End ======================
				*/
				// Clone the db instance
				$tempDb = clone $this->db;

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('ca.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $tempDb->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('alloted_to', $this->user_code)->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'ALL_RESPONSE_FORMAT':

				$select_column = array("body", "subject", "email_to");
				$order_column = array(null, null, null, null);

				$this->db->select("rf.id, rf.case_no, rf.subject, rf.email_to,rf.status, CONCAT(cdt.case_no_prefix, cdt.case_no) as case_no_desc, DATE_FORMAT(rf.created_at, '%d-%m-%Y') as created_at_date, rf.response_status");
				$this->db->from('cs_response_format_tbl as rf');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = rf.case_no');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'rf.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . 'rf.' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('rf.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('rf.status', 1)->where('rf.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_response_format_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_response_format_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'GET_FEES_STATUS':
				// Get the fees
				$feesDetails = $this->getter_model->get(['case_no' => $this->security->xss_clean(strip_tags($this->input->post('case_no')))], 'GET_CASE_FEE_DETAILS');

				// Get fees deposited
				$feesDetails = $this->getter_model->get(['case_no' => $this->security->xss_clean(strip_tags($this->input->post('case_no')))], 'GET_CASE_FEE_DEPOSIT_DETAILS');

				// Get the balance remaining
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['slug']);
				$this->db->where('status', 1);
				$query = $this->db->get();
				$fd_data = $query->row_array();

				$amount_n_balance['total_amount'] = (isset($ta_data['total_amount']) && $ta_data['total_amount']) ? $ta_data['total_amount'] : 0;
				$amount_n_balance['deposit_amount'] = (isset($fd_data['fee_deposit'])) ? $fd_data['fee_deposit'] : 0;
				$amount_n_balance['balance'] = $amount_n_balance['total_amount'] - $amount_n_balance['deposit_amount'];

				break;

			case 'GET_ARBITRATOR_EMAIL':
				$this->db->select('name_of_arbitrator,email,contact_no');
				$this->db->from('master_arbitrators_tbl');
				$this->db->where('code', $data);
				return $this->db->get()->row_array();
				break;

			case 'get_arbitrators_list':
				$this->db->select('arb.at_code,mat.name_of_arbitrator,pct.category_name');
				$this->db->from('cs_arbitral_tribunal_tbl arb');
				$this->db->join('master_arbitrators_tbl as mat', 'mat.code = arb.name_of_arbitrator', 'left');
				$this->db->join('panel_category_tbl pct', 'pct.code = mat.category', 'left');
				$this->db->where('arb.case_no', $data);
				$this->db->where('arb.status', 1);
				return $this->db->get()->result_array();
				break;

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function post($data, $op)
	{
		switch ($op) {

			case 'UPDATE_FEES_ASSES':
				$code = $this->security->xss_clean($data['asses_code']);
				$this->form_validation->set_rules('appr_status', 'Select a option', 'required|xss_clean');
				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$code = $this->security->xss_clean($data['asses_code']);

						$asses_data = array(
							'assessment_approved' => $this->security->xss_clean($data['appr_status']),
							'remarks' => $this->security->xss_clean($data['remarks']),
							'updated_by' => $this->user_code,
							'updated_at' => $this->date,

						);

						// Update details
						$result = $this->db->where('code', $code)->update('cs_fee_details_tbl', $asses_data);

						if ($result) {
							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);

				break;

			case 'ADD_CASE_DETAILS':
				// Case details
				$this->form_validation->set_rules('cd_diary_number', 'Diary number', 'required|xss_clean');
				// $this->form_validation->set_rules('cd_case_no', 'Case number', 'required|is_unique[cs_case_details_tbl.case_no]|xss_clean');
				$this->form_validation->set_rules('cd_case_title_claimant', 'Case Claimant', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_title_respondent', 'Case Respondent', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_on', 'Reffered on', 'xss_clean');
				$this->form_validation->set_rules('cd_reffered_by', 'Reffered by (Direct/Court)', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_by_judge', 'Reffered by (Judge/Justice)', 'xss_clean');
				$this->form_validation->set_rules('cd_case_arb_pet', 'Arbitration Petition', 'xss_clean');
				$this->form_validation->set_rules('cd_name_of_court', 'Name of court', 'xss_clean');
				$this->form_validation->set_rules('cd_name_of_judge', 'Name of judge', 'xss_clean');
				$this->form_validation->set_rules('cd_registered_on', 'Registered on', 'xss_clean');
				$this->form_validation->set_rules('cd_recieved_on', 'Recieved on', 'xss_clean');
				$this->form_validation->set_rules('cd_toa', 'Type of arbitration', 'required|xss_clean');
				$this->form_validation->set_rules('cd_status', 'Status', 'required|xss_clean');
				// $this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'required|xss_clean');
				$this->form_validation->set_rules('cd_remakrs', 'Remarks', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						// Upload files ==============
						$caseFileName = [];
						if (!empty(array_filter($_FILES['cd_case_file']['name']))) {
							$this->load->library('fileupload');
							$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_case_file'], [
								'file_name' => 'CASE_FILES_' . time(),
								'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($file_result['status'] == false) {
								$this->db->trans_rollback();
								return $file_result;
							} else {
								$caseFileName = $file_result['files'];
							}
						}

						// Generate the diac registration no.
						$case_no = generate_diac_reg_number();

						$slug = md5($case_no);

						$case_title = $this->security->xss_clean($data['cd_case_title_claimant']) . ' vs ' . $this->security->xss_clean($data['cd_case_title_respondent']);

						// Check ======================
						// If case is from efiling
						if ($this->input->post('cd_case_type') == 'NEW_REFERENCE') {
							$reference_code = $this->input->post('cd_diary_number');

							$nr_data = $this->new_reference_model->get_single_new_reference_data_using_code($reference_code);

							$nr_claimant  = $this->new_reference_model->get_new_reference_claimants_using_code($nr_data['nr_code']);

							if (count($nr_claimant) > 0) {
								foreach ($nr_claimant as $key => $cl) {
									$cl_code = generateCode();

									// Add claimant data ========================
									$claimant_details_data = array(
										'code' => $cl_code,
										'case_no' => $slug,
										'type' => 'claimant',
										'name' => $cl['name'],
										'contact' => $cl['phone_number'],
										'email' => $cl['email_id'],
										'count_number' => $key + 1,
										'status' => 1,
										'created_by' => $this->user_code,
										'created_on' => $this->date
									);

									// Insert Claimant details
									$claimantResult = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);

									if (!$claimantResult) {
										$this->db->trans_rollback();
										echo json_encode(array(
											'status' => false,
											'msg' => 'Error while saving claimant data'
										));
										die;
									}

									if ($key == 0) {
										$claimant_table_id = $cl_code;
									}

									// Insert address
									$address_result = $this->common_model->insert_address([
										'person_type' => 'CLAIMANT',
										'type_code' => $cl_code,
										'address_one' => $cl['address_one'],
										'address_two' => $cl['address_two'],
										'state' => $cl['state'],
										'country' => $cl['country'],
										'pincode' => $cl['pincode'],
										'status' => 1,
										'created_by' => $this->user_code,
										'created_at' => $this->date,
										'updated_by' => $this->user_code,
										'updated_at' => $this->date
									]);

									if (!$address_result) {
										$this->db->trans_rollback();
										echo json_encode(array(
											'status' => false,
											'msg' => 'Error while saving claimant address'
										));
										die;
									}
								}
							}

							$nr_respondant  = $this->new_reference_model->get_new_reference_respondants_using_code($nr_data['nr_code']);

							if (count($nr_respondant) > 0) {
								foreach ($nr_respondant as $key => $res) {
									$res_code = generateCode();

									// Add respondant data ========================
									$respondent_details_data = array(
										'code' => $res_code,
										'case_no' => $slug,
										'type' => 'respondant',
										'name' => $res['name'],
										'contact' => $res['phone_number'],
										'email' => $res['email_id'],
										'count_number' => $key + 1,
										'status' => 1,
										'created_by' => $this->user_code,
										'created_on' => $this->date
									);

									// Insert respondant details
									$respondentResult = $this->db->insert('cs_claimant_respondant_details_tbl', $respondent_details_data);

									if (!$respondentResult) {
										$this->db->trans_rollback();
										echo json_encode(array(
											'status' => false,
											'msg' => 'Error while saving respondant data'
										));
										die;
									}

									if ($key == 0) {
										$respondent_table_id = $res_code;
									}

									// Insert address
									$address_result = $this->common_model->insert_address([
										'person_type' => 'RESPONDENT',
										'type_code' => $res_code,
										'address_one' => $res['address_one'],
										'address_two' => $res['address_two'],
										'state' => $res['state'],
										'country' => $res['country'],
										'pincode' => $res['pincode'],
										'status' => 1,
										'created_by' => $this->user_code,
										'created_at' => $this->date,
										'updated_by' => $this->user_code,
										'updated_at' => $this->date
									]);

									if (!$address_result) {
										$this->db->trans_rollback();
										echo json_encode(array(
											'status' => false,
											'msg' => 'Error while saving respodant address'
										));
										die;
									}
								}
							}

							// Get transaction details of new reference
							$txn_data = $this->common_model->fetch_success_transaction_using_type('NEW_REFERENCE', $nr_data['nr_code']);

							// Get user details
							$user_details = $this->getter_model->get(['user_code' => $nr_data['user_code']], 'get_user_details_using_user_code');

							// Enter the fee deposit details in table
							// First: Insert the arbitrator fees
							$arb_fd_data = array(
								'case_no' => $slug,
								'date_of_deposit' => formatDate($txn_data['txn_date']),
								'deposited_by' => $claimant_table_id,
								'name_of_depositor' => $user_details['user_display_name'],
								'deposited_towards' => 'ARB_CS',
								'mode_of_deposit' => 'ONLINE',
								'details_of_deposit' => $txn_data['txn_id'],
								'check_bounce' => '',
								'amount' => $nr_data['arb_your_share_fees_payable'],
								'status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date,
							);

							$arb_fees_result = $this->fees_model->insert_case_fees_deposit($arb_fd_data);

							if (!$arb_fees_result) {
								$this->db->trans_rollback();
								echo json_encode(array(
									'status' => false,
									'msg' => 'Error while saving arbitrator fees of claimant'
								));
								die;
							}

							// Second: Insert the administration charges
							$adm_fd_data = array(
								'case_no' => $slug,
								'date_of_deposit' => formatDate($txn_data['txn_date']),
								'deposited_by' => $claimant_table_id,
								'name_of_depositor' => $user_details['user_display_name'],
								'deposited_towards' => 'ADM_CS',
								'mode_of_deposit' => 'ONLINE',
								'details_of_deposit' => $txn_data['txn_id'],
								'check_bounce' => '',
								'amount' => $nr_data['adm_charges'],
								'status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date,
							);

							$adm_fees_result = $this->fees_model->insert_case_fees_deposit($adm_fd_data);

							if (!$adm_fees_result) {
								$this->db->trans_rollback();
								echo json_encode(array(
									'status' => false,
									'msg' => 'Error while saving administration charges of claimant'
								));
								die;
							}

							// Update the new reference pending to approve ===========
							$nr_result = $this->new_reference_model->update_using_code([
								'application_status' => 1,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date
							], $nr_data['nr_code']);

							if (!$nr_result) {
								$this->db->trans_rollback();
								echo json_encode(array(
									'status' => false,
									'msg' => 'Error while approving the new reference.'
								));
								die;
							}
						} else {
							// Add claimant data ========================
							$claimant_details_data = array(
								'code' => generateCode(),
								'case_no' => $slug,
								'type' => 'claimant',
								'name' => $this->security->xss_clean($data['cd_case_title_claimant']),
								'count_number' => '1',
								'status' => 1,
								'created_by' => $this->user_code,
								'created_on' => $this->date
							);

							// Add respondent data ===================
							$respondent_details_data = array(
								'code' => generateCode(),
								'case_no' => $slug,
								'type' => 'respondant',
								'name' => $this->security->xss_clean($data['cd_case_title_respondent']),
								'count_number' => '1',
								'status' => 1,
								'created_by' => $this->user_code,
								'created_on' => $this->date
							);

							// Insert Claimant details
							$claimantResult = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);
							$claimant_table_id = $this->db->insert_id();

							// Insert Respondent details
							$respondentResult = $this->db->insert('cs_claimant_respondant_details_tbl', $respondent_details_data);
							$respondent_table_id = $this->db->insert_id();
						}


						if ($claimantResult && $respondentResult) {
							$reffered_by = $this->security->xss_clean($data['cd_reffered_by']);
							$arbitrator_status = 2;

							$reffered_by_judge = '';
							$name_of_department = '';

							if ($reffered_by && $reffered_by == 'COURT') {
								$reffered_by_judge = $this->security->xss_clean($data['cd_reffered_by_judge']);
							}
							if ($reffered_by && $reffered_by == 'OTHER') {
								$name_of_department = $this->security->xss_clean($data['cd_name_of_court']);
							}

							// If arbitrator status is appointed
							if ($arbitrator_status == 1) {
								$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));
								if (count($rowCountArray) < 1) {
									return [
										'status' => false,
										'msg' => 'Name of arbitrator is required if arbitrator is appointed.'
									];
								}

								$at_data = [];
								foreach ($rowCountArray as $row) {
									array_push($at_data, array(
										'at_code' => generateCode(),
										'case_no' => $slug,
										'name_of_arbitrator' => $this->security->xss_clean($data['cd_name_of_judge_' . $row]),
										'at_cat_id' => ($this->security->xss_clean($data['cd_name_of_judge_category_' . $row])) ? $this->security->xss_clean($data['cd_name_of_judge_category_' . $row]) : null,
										'arb_terminated' => 'no',
										'date_of_termination' => '',
										'reason_of_termination' => '',
										'created_by' => $this->user_code,
										'created_at' => $this->date
									));
								}
								// Insert case details
								$result = $this->db->insert_batch('cs_arbitral_tribunal_tbl', $at_data);

								if (!$result) {
									return [
										'status' => false,
										'msg' => 'Server failed while saving data.'
									];
								}
							}

							$case_details_data = array(
								'slug' => $slug,
								'case_type' => $this->input->post('cd_case_type'),
								'reference_code' => $this->input->post('cd_diary_number'),
								'case_no' => $case_no,
								'case_title' => $case_title,
								'case_title_claimant' => $claimant_table_id,
								'case_title_respondent' => $respondent_table_id,
								'reffered_on' => formatDate($this->security->xss_clean($data['cd_reffered_on'])),
								'reffered_by' => $this->security->xss_clean($data['cd_reffered_by']),
								'reffered_by_judge' => $reffered_by_judge,
								'arbitration_petition' => $this->security->xss_clean($data['cd_case_arb_pet']),
								'name_of_court' => $name_of_department,
								'arbitrator_status' => 2,
								// 'name_of_judge' => $this->security->xss_clean($data['cd_name_of_judge']),
								'recieved_on' => formatDate($this->security->xss_clean($data['cd_recieved_on'])),
								'registered_on' => formatDate($this->security->xss_clean($data['cd_registered_on'])),
								'type_of_arbitration' => $this->security->xss_clean($data['cd_toa']),
								'di_type_of_arbitration' => ($this->security->xss_clean($data['cd_toa'])) ? $this->security->xss_clean($data['cd_di_toa']) : '',
								'case_status' => $this->security->xss_clean($data['cd_status']),
								'case_file' => (count($caseFileName) > 0) ? json_encode($caseFileName) : '',
								'remarks' => $this->security->xss_clean($data['cd_remarks']),
								'created_by' => $this->user_code,
								'created_on' => $this->date
							);

							// Insert case details
							$result = $this->db->insert('cs_case_details_tbl', $case_details_data);

							if ($result) {

								// Allot the case to respective case managers and deputy counsels
								$al_result = automatic_case_allotment($case_no, 'DEPUTY_COUNSEL');
								$al_result2 = automatic_case_allotment($case_no, 'CASE_MANAGER');

								$dc_allotment_user_code = $al_result['user_code'];
								$cm_allotment_user_code = $al_result2['user_code'];

								$allotment_data = [];

								array_push($allotment_data, [
									'case_no' => $slug,
									'alloted_by' => $this->user_code,
									'alloted_to' => $dc_allotment_user_code,
									'user_role' => 'DEPUTY_COUNSEL',
									'status' => 1,
									'created_at' => currentDateTimeStamp(),
									'created_by' => $this->user_code,
									'updated_at' => currentDateTimeStamp(),
									'updated_by' => $this->user_code,
								]);

								array_push($allotment_data, [
									'case_no' => $slug,
									'alloted_by' => $this->user_code,
									'alloted_to' => $cm_allotment_user_code,
									'user_role' => 'CASE_MANAGER',
									'status' => 1,
									'created_at' => currentDateTimeStamp(),
									'created_by' => $this->user_code,
									'updated_at' => currentDateTimeStamp(),
									'updated_by' => $this->user_code,
								]);

								$allotment_res = $this->db->insert_batch('cs_case_allotment_tbl', $allotment_data);

								if (!$allotment_res) {
									$this->db->trans_rollback();
									return array(
										'status' => false,
										'msg' => 'Error while automatic case allotment. ' . SERVER_ERROR
									);
								}

								// Map the miscellaneous number with case no.
								// if ($data['cd_misc_number']) {
								// 	$misc_case_data = array(
								// 		'case_no' => $slug,
								// 		'misc_id' => $data['cd_misc_number'],
								// 		'created_by' => $this->user_code,
								// 		'created_at' => $this->date,
								// 		'updated_by' => $this->user_code,
								// 		'updated_at' => $this->date,
								// 	);

								// 	// Insert case details
								// 	$result2 = $this->db->insert('cs_miscellaneous_case_mapping_tbl', $misc_case_data);
								// 	if (!$result2) {
								// 		$this->db->trans_rollback();
								// 		return array(
								// 			'status' => false,
								// 			'msg' => "Server failed while saving the miscellaneous number."
								// 		);
								// 	}
								// }


								// Update the data logs table for data tracking
								$table_name = 'cs_case_details_tbl';
								$table_id = $this->db->insert_id();
								$message = 'A new case ' . $case_no . ' is added.';

								$dataLogResult = $this->common_model->update_data_logs($table_name, $table_id, $message);

								if ($dataLogResult) {

									// Send mails to users
									if ($this->input->post('cd_case_type') == 'NEW_REFERENCE') {

										foreach ($nr_claimant as $claimant) :
											if ($claimant['email_id']) :
												case_registered_claimant($case_details_data, $claimant);
											endif;
										endforeach;

										foreach ($nr_respondant as $respondant) :
											if ($respondant['email_id']) :
												case_registered_respondant($case_details_data, $respondant);
											endif;
										endforeach;
									}

									$this->db->trans_commit();
									$output = array(
										'status' => true,
										// 'msg' => 'Case registered successfully and case allotted to respective counsels and case managers',
										'msg' => 'Case registered and DIAC registration no. alloted successfully.
										Do you want to add arbitrator in the case ?',
										'redirect_url' => base_url() . 'arbitral-tribunal/' . $slug
									);
								} else {
									$this->db->trans_rollback();
									$output = array(
										'status' => false,
										'msg' => SERVER_ERROR
									);
								}
							} else {
								$this->db->trans_rollback();
								$output = array(
									'status' => false,
									'msg' => SERVER_ERROR
								);
							}
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => SERVER_ERROR
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => SERVER_DOWN_ERROR
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;

			case 'EDIT_CASE_DETAILS':

				// Case details
				$this->form_validation->set_rules('hidden_case', 'Case SLug', 'required|xss_clean');
				$this->form_validation->set_rules('cd_diary_number', 'Diary number', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_no', 'Case number', 'xss_clean');
				$this->form_validation->set_rules('cd_case_title_claimant', 'Case Claimant', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_title_respondent', 'Case Respondent', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_on', 'Reffered on', 'xss_clean');
				$this->form_validation->set_rules('cd_reffered_by', 'Reffered by (Direct/Court)', 'required|xss_clean');
				$this->form_validation->set_rules('cd_reffered_by_judge', 'Reffered by (Judge/Justice)', 'xss_clean');
				$this->form_validation->set_rules('cd_case_arb_pet', 'Arbitration Petition', 'xss_clean');
				$this->form_validation->set_rules('cd_name_of_court', 'Name of court', 'xss_clean');
				$this->form_validation->set_rules('cd_name_of_judge', 'Name of judge', 'xss_clean');
				$this->form_validation->set_rules('cd_recieved_on', 'Recieved on', 'xss_clean');
				$this->form_validation->set_rules('cd_registered_on', 'Registered on', 'xss_clean');
				$this->form_validation->set_rules('cd_toa', 'Type of arbitration', 'required|xss_clean');
				$this->form_validation->set_rules('cd_status', 'Status', 'required|xss_clean');
				$this->form_validation->set_rules('cd_arbitrator_status', 'Arbitrator Status', 'xss_clean');
				$this->form_validation->set_rules('cd_remakrs', 'Remarks', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						// Upload files ==============
						$caseFileName = [];
						if (!empty(array_filter($_FILES['cd_case_file']['name']))) {
							$this->load->library('fileupload');
							$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_case_file'], [
								'file_name' => 'CASE_FILES_' . time(),
								'file_move_path' => CASE_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($file_result['status'] == false) {
								$this->db->trans_rollback();
								return $file_result;
							} else {
								$caseFileName = $file_result['files'];
							}
						}

						$case_slug = $this->security->xss_clean($data['hidden_case']);
						$case_data = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_DETAILS');
						$case_title = $this->security->xss_clean($data['cd_case_title_claimant']) . ' vs ' . $this->security->xss_clean($data['cd_case_title_respondent']);

						// Update Claimant details =====================
						$claimant_details_data = array(
							'name' => $this->security->xss_clean($data['cd_case_title_claimant']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						$claimantResult = $this->db->where([
							'id' => $case_data['case_title_claimant']
						])->update('cs_claimant_respondant_details_tbl', $claimant_details_data);

						// Update Respondent details =====================
						$respondent_details_data = array(
							'name' => $this->security->xss_clean($data['cd_case_title_respondent']),
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						$respondentResult = $this->db->where([
							'id' => $case_data['case_title_respondent']
						])->update('cs_claimant_respondant_details_tbl', $respondent_details_data);

						if ($claimantResult && $respondentResult) {

							$reffered_by = $this->security->xss_clean($data['cd_reffered_by']);
							$arbitrator_status = $this->security->xss_clean($data['cd_arbitrator_status']);

							$reffered_by_judge = '';
							$name_of_department = '';

							if ($reffered_by && $reffered_by == 'COURT') {
								$reffered_by_judge = $this->security->xss_clean($data['cd_reffered_by_judge']);
							}
							if ($reffered_by && $reffered_by == 'OTHER') {
								$name_of_department = $this->security->xss_clean($data['cd_name_of_court']);
							}

							$case_details_data = array(
								'case_type' => $this->input->post('cd_case_type'),
								'diary_number' => $this->input->post('cd_diary_number'),
								'case_title' => $case_title,
								'reffered_on' => formatDate($this->security->xss_clean($data['cd_reffered_on'])),
								'reffered_by' => $this->security->xss_clean($data['cd_reffered_by']),
								'reffered_by_judge' => $reffered_by_judge,
								'arbitration_petition' => $this->security->xss_clean($data['cd_case_arb_pet']),
								'name_of_court' => $name_of_department,
								'arbitrator_status' => $arbitrator_status,
								'recieved_on' => formatDate($this->security->xss_clean($data['cd_recieved_on'])),
								'registered_on' => formatDate($this->security->xss_clean($data['cd_registered_on'])),
								'type_of_arbitration' => $this->security->xss_clean($data['cd_toa']),
								'di_type_of_arbitration' => ($this->security->xss_clean($data['cd_toa'])) ? $this->security->xss_clean($data['cd_di_toa']) : '',
								'case_status' => $this->security->xss_clean($data['cd_status']),
								'remarks' => $this->security->xss_clean($data['cd_remarks']),
								'updated_by' => $this->user_code,
								'updated_on' => $this->date
							);

							// Check if the new file is uploaded
							if (count($caseFileName) > 0) {
								$case_details_data['case_file'] = json_encode($caseFileName);
							}

							// Update case details
							$result = $this->db->where('slug', $case_slug)->update('cs_case_details_tbl', $case_details_data);

							if ($result) {

								// If previously arbitrator status is To be appointed, only then update arbitrator status
								if ($case_data['arbitrator_status'] == 2) {
									// If arbitrator status is appointed
									if ($arbitrator_status == 1) {
										$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));
										if (count($rowCountArray) < 1) {
											return [
												'status' => false,
												'msg' => 'Name of arbitrator is required if arbitrator is appointed.'
											];
										}

										$at_data = [];
										foreach ($rowCountArray as $row) {
											array_push($at_data, array(
												'case_no' => $case_slug,
												'name_of_arbitrator' => $this->security->xss_clean($data['cd_name_of_judge_' . $row]),
												'at_cat_id' => ($this->security->xss_clean($data['cd_name_of_judge_category_' . $row])) ? $this->security->xss_clean($data['cd_name_of_judge_category_' . $row]) : null,
												'arb_terminated' => 'no',
												'date_of_termination' => null,
												'reason_of_termination' => '',
												'created_by' => $this->user_code,
												'created_at' => $this->date
											));
										}
										// Insert case details
										$result = $this->db->insert_batch('cs_arbitral_tribunal_tbl', $at_data);

										if (!$result) {
											return [
												'status' => false,
												'msg' => 'Server failed while saving data.'
											];
										}
									}
								}

								// If arbitrator status is set to To be appointed then remove all the arbitrators
								if ($arbitrator_status == 2) {

									// Insert case details
									$arbResult = $this->db->where([
										'case_no' => $case_slug
									])->update('cs_arbitral_tribunal_tbl', [
										'status' => 0,
										'updated_by' => $this->user_code,
										'updated_at' => $this->date
									]);

									if (!$arbResult) {
										return [
											'status' => false,
											'msg' => 'Server failed while saving data.'
										];
									}
								}

								// Update the Mapping of miscellaneous number with case no.
								// if ($data['cd_misc_number']) {
								// 	// Check if mapping is available or not
								// 	$misc_case_mapping = $this->db->select('*')->where('case_no', $case_slug)->from('cs_miscellaneous_case_mapping_tbl')->count_all_results();
								// 	$misc_case_data = array(
								// 		'misc_id' => $data['cd_misc_number'],
								// 		'updated_by' => $this->user_code,
								// 		'updated_at' => $this->date,
								// 	);

								// 	if ($misc_case_mapping) {
								// 		// Update case details
								// 		$result2 = $this->db->where('case_no', $case_slug)->update('cs_miscellaneous_case_mapping_tbl', $misc_case_data);
								// 		if (!$result2) {
								// 			$this->db->trans_rollback();
								// 			return array(
								// 				'status' => false,
								// 				'msg' => "Server failed while updating the miscellaneous number."
								// 			);
								// 		}
								// 	} else {
								// 		$misc_case_data['case_no'] = $case_slug;
								// 		// Insert case details
								// 		$result2 = $this->db->insert('cs_miscellaneous_case_mapping_tbl', $misc_case_data);
								// 		if (!$result2) {
								// 			$this->db->trans_rollback();
								// 			return array(
								// 				'status' => false,
								// 				'msg' => "Server failed while saving the miscellaneous number."
								// 			);
								// 		}
								// 	}
								// }


								// Update the data logs table for data tracking
								$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_slug)->get()->row_array();

								$table_name = 'cs_case_details_tbl';
								$table_id = $data['id'];
								$message = 'Details of case ' . $data['case_no'] . ' is updated.';

								$this->common_model->update_data_logs($table_name, $table_id, $message);

								$this->db->trans_commit();
								$output = array(
									'status' => true,
									'msg' => 'Record updated successfully',
									'redirect_url' => base_url() . 'all-registered-case'
								);
							} else {
								$this->db->trans_rollback();
								$output = array(
									'status' => false,
									'msg' => 'Server is not responding. Please try again.'
								);
							}
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Server is not responding. Please try again.'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;

			case 'DELETE_CASE':
				$this->db->trans_begin();
				$this->form_validation->set_rules('id', 'Case Id', 'required');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);
					$case_data = $this->getter_model->get(['id' => $id], 'GET_CASE_DETAILS_USING_ID');

					$r = $this->db->where('id', $id)->update('cs_case_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {
						// Update Claimant details =====================
						$claimant_details_data = array(
							'status' => 0,
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						$claimantResult = $this->db->where([
							'id' => $case_data['case_title_claimant']
						])->update('cs_claimant_respondant_details_tbl', $claimant_details_data);

						// Update Respondent details =====================
						$respondent_details_data = array(
							'status' => 0,
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						$respondentResult = $this->db->where([
							'id' => $case_data['case_title_respondent']
						])->update('cs_claimant_respondant_details_tbl', $respondent_details_data);

						if ($claimantResult && $respondentResult) {
							// Update the data logs table for data tracking
							$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('id', $id)->get()->row_array();

							$table_name = 'cs_case_details_tbl';
							$table_id = $data['id'];
							$message = 'Case ' . $data['case_no'] . ' is deleted.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = TRUE;
							$dbmessage = 'Record deleted successfully';
						} else {
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Something went wrong. Please try again.';
						}
					} else {
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$this->db->trans_rollback();
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_CLAIMANT_DETAILS':

				// Claimant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean');
				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[0-9\s]+$/]');
				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$claimant_name = $this->security->xss_clean($data['car_name']);
						$claimant_count = $this->security->xss_clean($data['car_number']);
						$cr_code = generateCode();

						// Check if the email id or phone number is already available for that case number or not
						if ($data['car_contact_no']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('(contact ="' . $this->security->xss_clean($data['car_contact_no']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}
						if ($data['car_email']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('(email="' . $this->security->xss_clean($data['car_email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Email id is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						$claimant_details_data = array(
							'code' => $cr_code,
							'case_no' => $case_no,
							'type' => $this->security->xss_clean($data['hidden_car_type']),
							'name' => $claimant_name,
							'count_number' => $claimant_count,
							'contact' => $this->security->xss_clean($data['car_contact_no']),
							'additonal_contact' => $this->security->xss_clean($data['car_additional_contact_no']),
							'email' => $this->security->xss_clean($data['car_email']),
							'additional_email' => $this->security->xss_clean($data['car_additional_email']),
							'removed' => $this->security->xss_clean($data['car_removed']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_on' => $this->date
						);

						// Insert Claimant details
						$result = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);
						if ($result) {
							$table_id = $this->db->insert_id();

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $cr_code, 'CLAIMANT');

							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

							$table_name = 'cs_claimant_respondant_details_tbl';
							$message = 'A new claimant ' . $claimant_name . ' (' . $claimant_count . ') ' . ' of case ' . $data['case_no'] . ' is added.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$output = array(
								'status' => true,
								'msg' => 'Record saved successfully'
							);
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;

			case 'EDIT_CLAIMANT_DETAILS':

				// Claimant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_id', 'Id', 'required|xss_clean');

				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean');
				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[0-9\s]+$/]');
				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$car_id = $this->security->xss_clean($data['hidden_car_id']);
						$car_code = $this->security->xss_clean($data['hidden_car_code']);
						$claimant_name = $this->security->xss_clean($data['car_name']);
						$claimant_count = $this->security->xss_clean($data['car_number']);

						// Check if the email id or phone number is already available for that case number or not
						if ($data['car_contact_no']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('code !=', $car_code)
								->where('status', 1)
								->where('(contact ="' . $this->security->xss_clean($data['car_contact_no']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}
						if ($data['car_email']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('code !=', $car_code)
								->where('status', 1)
								->where('(email="' . $this->security->xss_clean($data['car_email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Email id is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						$claimant_details_data = array(
							'type' => $this->security->xss_clean($data['hidden_car_type']),
							'name' => $claimant_name,
							'count_number' => $claimant_count,
							'contact' => $this->security->xss_clean($data['car_contact_no']),
							'email' => $this->security->xss_clean($data['car_email']),
							'additonal_contact' => $this->security->xss_clean($data['car_additional_contact_no']),
							'additional_email' => $this->security->xss_clean($data['car_additional_email']),
							'removed' => $this->security->xss_clean($data['car_removed']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						// Insert Claimant details
						$result = $this->db->where('case_no', $case_no)->where('code', $car_code)->update('cs_claimant_respondant_details_tbl', $claimant_details_data);
						if ($result) {

							$table_id = $car_id;

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));
							$addressResult = $this->insert_addresses($rowCountArray, $data, $table_id, 'CLAIMANT');
							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

							$table_name = 'cs_claimant_respondant_details_tbl';
							$message = 'Details of claimant ' . $claimant_name . ' (' . $claimant_count . ') ' . ' of case ' . $data['case_no'] . ' is updated.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$output = array(
								'status' => true,
								'msg' => 'Record updated successfully'
							);
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;

			case 'DELETE_CLAIMANT':

				$this->form_validation->set_rules('code', 'Claimant Id', 'required');

				if ($this->form_validation->run()) {
					$code = $this->security->xss_clean($data['code']);

					$r = $this->db->where('code', $code)->update('cs_claimant_respondant_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$car_data = $this->db->select('id, name, count_number, case_no')->from('cs_claimant_respondant_details_tbl')->where('code', $code)->get()->row_array();

						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $car_data['case_no'])->get()->row_array();

						$table_name = 'cs_claimant_respondant_details_tbl';
						$table_id = $car_data['id'];

						$message = 'Claimant ' . $car_data['name'] . ' (' . $car_data['count_number'] . ') ' . ' of case ' . $data['case_no'] . ' is deleted.';

						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_RESPONDANT_DETAILS':

				// Respondant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean');
				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[0-9\s]+$/]');
				$this->form_validation->set_rules('car_counter_claimant', 'Counter Claimant', 'xss_clean');
				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$respondent_name = $this->security->xss_clean($data['car_name']);
						$count_number = $this->security->xss_clean($data['car_number']);
						$case_no = $this->security->xss_clean($data['hidden_case_no']);

						$cr_code = generateCode();

						// Check if the email id or phone number is already available for that case number or not
						if ($data['car_contact_no']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('(contact ="' . $this->security->xss_clean($data['car_contact_no']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}
						if ($data['car_email']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('(email="' . $this->security->xss_clean($data['car_email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Email id is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						$claimant_details_data = array(
							'code' => $cr_code,
							'case_no' => $case_no,
							'type' => $this->security->xss_clean($data['hidden_car_type']),
							'name' => $respondent_name,
							'count_number' => $count_number,
							'counter_claimant' => $this->security->xss_clean($data['car_counter_claimant']),
							'contact' => $this->security->xss_clean($data['car_contact_no']),
							'email' => $this->security->xss_clean($data['car_email']),
							'additonal_contact' => $this->security->xss_clean($data['car_additional_contact_no']),
							'additional_email' => $this->security->xss_clean($data['car_additional_email']),
							'removed' => $this->security->xss_clean($data['car_removed']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_on' => $this->date
						);

						// Insert Claimant details
						$result = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);
						if ($result) {

							$table_id = $this->db->insert_id();

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $cr_code, 'RESPONDENT');

							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

							$table_name = 'cs_claimant_respondant_details_tbl';
							$message = 'A new respondent ' . $respondent_name . ' (' . $count_number . ') ' . ' of case ' . $data['case_no'] . ' is added.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$output = array(
								'status' => true,
								'msg' => 'Record saved successfully'
							);
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;

			case 'EDIT_RESPONDANT_DETAILS':

				// Respondant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_id', 'Id', 'required|xss_clean');

				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean');
				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[0-9\s]+$/]');
				$this->form_validation->set_rules('car_counter_claimant', 'Counter Claimant', 'xss_clean');
				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$case_no = $this->security->xss_clean($data['hidden_case_no']);

						$car_id = $this->security->xss_clean($data['hidden_car_id']);
						$car_code = $this->security->xss_clean($data['hidden_car_code']);

						$respondent_name = $this->security->xss_clean($data['car_name']);
						$count_number = $this->security->xss_clean($data['car_number']);

						// Check if the email id or phone number is already available for that case number or not
						if ($data['car_contact_no']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('code !=', $car_code)
								->where('status', 1)
								->where('(contact ="' . $this->security->xss_clean($data['car_contact_no']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}
						if ($data['car_email']) {
							$checkContact = $this->db->select('*')
								->from('cs_claimant_respondant_details_tbl')
								->where('case_no', $case_no)
								->where('code !=', $car_code)
								->where('status', 1)
								->where('(email="' . $this->security->xss_clean($data['car_email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Email id is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						$claimant_details_data = array(
							'type' => $this->security->xss_clean($data['hidden_car_type']),
							'name' => $respondent_name,
							'count_number' => $count_number,
							'counter_claimant' => $this->security->xss_clean($data['car_counter_claimant']),
							'contact' => $this->security->xss_clean($data['car_contact_no']),
							'email' => $this->security->xss_clean($data['car_email']),
							'additonal_contact' => $this->security->xss_clean($data['car_additional_contact_no']),
							'additional_email' => $this->security->xss_clean($data['car_additional_email']),
							'removed' => $this->security->xss_clean($data['car_removed']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_on' => $this->date
						);

						// Insert Claimant details
						$result = $this->db->where('case_no', $case_no)->where('code', $car_code)->update('cs_claimant_respondant_details_tbl', $claimant_details_data);
						if ($result) {
							$table_id = $car_id;

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $car_code, 'RESPONDENT');

							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

							$table_name = 'cs_claimant_respondant_details_tbl';
							$message = 'Details of respondent ' . $respondent_name . ' (' . $count_number . ') ' . ' of case ' . $data['case_no'] . ' is updated.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$output = array(
								'status' => true,
								'msg' => 'Record updated successfully'
							);
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;


			case 'DELETE_RESPONDANT':

				$this->form_validation->set_rules('code', 'Respondant Id', 'required');

				if ($this->form_validation->run()) {
					$code = $this->security->xss_clean($data['code']);

					$r = $this->db->where('code', $code)->update('cs_claimant_respondant_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$car_data = $this->db->select('id, name, count_number, case_no')->from('cs_claimant_respondant_details_tbl')->where('code', $code)->get()->row_array();

						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $car_data['case_no'])->get()->row_array();

						$table_name = 'cs_claimant_respondant_details_tbl';
						$table_id = $car_data['id'];

						$message = 'Respondent ' . $car_data['name'] . ' (' . $car_data['count_number'] . ') ' . ' of case ' . $data['case_no'] . ' is deleted.';

						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;


			case 'ADD_CASE_STATUS_PLEADINGS':

				// Status of pleadings
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean|is_unique[cs_status_of_pleadings_tbl.case_no]');
				$this->form_validation->set_rules('claim_invited_on', 'Claim invited on', 'xss_clean');
				$this->form_validation->set_rules('rem_to_claim', 'Reminder to claim', 'xss_clean');
				$this->form_validation->set_rules('rem_to_claim_2', 'Reminder to claim 2', 'xss_clean');
				$this->form_validation->set_rules('claim_filed_on', 'Claim filed on', 'xss_clean');
				$this->form_validation->set_rules('res_served_on', 'Respondent served on', 'xss_clean');
				$this->form_validation->set_rules('sod_filed_on', 'Statement of defence filed on', 'xss_clean');
				$this->form_validation->set_rules('sop_rejoin_sod_filed_on', 'Rejoinder to Statement of Defence filed on', 'xss_clean');
				$this->form_validation->set_rules('counter_claim', 'Counter claim', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_cc', 'Date of filing of Counter Claim', 'xss_clean');
				$this->form_validation->set_rules('sop_rt_cc_filed_on', 'Reply to Counter Claim filed on', 'xss_clean');
				$this->form_validation->set_rules('sop_rej_cc_filed_on', 'Rejoinder to Reply of Counter Claim filed on', 'xss_clean');
				$this->form_validation->set_rules('sop_app_act', 'Application under Section 17 of A&C Act', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_app', 'Date of filing of Application', 'xss_clean');
				$this->form_validation->set_rules('sop_rep_app_filed_on', 'Reply to the Application filed on', 'xss_clean');

				$this->form_validation->set_rules('sop_app_act_16', 'Application under Section 16 of A&C Act', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_app_16', 'Date of filing of Application (16)', 'xss_clean');
				$this->form_validation->set_rules('sop_rep_app_filed_on_16', 'Reply to the Application filed on (16)', 'xss_clean');

				$this->form_validation->set_rules('sop_remarks', 'Remarks', 'xss_clean');

				// $this->form_validation->set_rules('p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');
				// $this->form_validation->set_rules('no_of_dod', 'Number of days of delay', 'numeric|xss_clean');
				//  $this->form_validation->set_rules('sop_cc_p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');
				// $this->form_validation->set_rules('sop_cc_no_of_dod', 'Number of days of delay', 'numeric|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$case_details_data = array(
							'case_no' => $case_no,
							'claim_invited_on' => $this->security->xss_clean($data['claim_invited_on']),
							'rem_to_claim_on' => $this->security->xss_clean($data['rem_to_claim']),
							'rem_to_claim_on_2' => '',
							'claim_filed_on' => $this->security->xss_clean($data['claim_filed_on']),
							'res_served_on' => $this->security->xss_clean($data['res_served_on']),
							'sod_filed_on' => $this->security->xss_clean($data['sod_filed_on']),
							// 'sod_pol' => $this->security->xss_clean($data['sod_p_of_limitation']),
							// 'sod_dod' => '',
							'rej_stat_def_filed_on' => $this->security->xss_clean($data['sop_rejoin_sod_filed_on']),
							'counter_claim' => $this->security->xss_clean($data['counter_claim']),
							'dof_counter_claim' => '',
							'reply_counter_claim_on' => '',
							// 'reply_counter_claim_pol' => '',
							// 'reply_counter_claim_dod' => '',
							'rej_reply_counter_claim_on' => '',
							'app_section' => $this->security->xss_clean($data['sop_app_act']),
							'dof_app' => '',
							'reply_app_on' => '',
							'app_section_16' => $this->security->xss_clean($data['sop_app_act_16']),
							'dof_app_16' => '',
							'reply_app_on_16' => '',
							'remarks' => $this->security->xss_clean($data['sop_remarks']),
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						if (isset($data['rem_to_claim_2']) && !empty($data['rem_to_claim_2'])) {
							$case_details_data['rem_to_claim_on_2'] = $this->security->xss_clean($data['rem_to_claim_2']);
						}

						// if($data['sod_p_of_limitation'] == 'yes'){
						// 	$case_details_data['sod_dod'] = $this->security->xss_clean($data['sod_no_of_dod']);
						// }

						if ($data['counter_claim'] == 'yes') {
							$case_details_data['dof_counter_claim'] = $this->security->xss_clean($data['sop_dof_cc']);
							$case_details_data['reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rt_cc_filed_on']);
							// $case_details_data['reply_counter_claim_pol'] = $this->security->xss_clean($data['sop_cc_p_of_limitation']);
							// $case_details_data['reply_counter_claim_dod'] = $this->security->xss_clean($data['sop_cc_no_of_dod']);
							$case_details_data['rej_reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rej_cc_filed_on']);
						}

						if ($data['sop_app_act'] == 'yes') {
							$case_details_data['dof_app'] = $this->security->xss_clean($data['sop_dof_app']);
							$case_details_data['reply_app_on'] = $this->security->xss_clean($data['sop_rep_app_filed_on']);
						}

						if ($data['sop_app_act_16'] == 'yes') {
							$case_details_data['dof_app_16'] = $this->security->xss_clean($data['sop_dof_app_16']);
							$case_details_data['reply_app_on_16'] = $this->security->xss_clean($data['sop_rep_app_filed_on_16']);
						}

						// Insert case details
						$result = $this->db->insert('cs_status_of_pleadings_tbl', $case_details_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_status_of_pleadings_tbl';
							$message = 'Status of pleadings of case ' . $case_det['case_no'] . ' is added.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record saved successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong. Please try again.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;


			case 'EDIT_CASE_STATUS_PLEADINGS':

				// Status of pleadings
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_sop_id', 'Status of pleadings id', 'required|xss_clean');
				$this->form_validation->set_rules('claim_invited_on', 'Claim invited on', 'xss_clean');
				$this->form_validation->set_rules('rem_to_claim', 'Reminder to claim', 'xss_clean');
				$this->form_validation->set_rules('rem_to_claim_2', 'Reminder to claim 2', 'xss_clean');
				$this->form_validation->set_rules('claim_filed_on', 'Claim filed on', 'xss_clean');
				$this->form_validation->set_rules('res_served_on', 'Respondent served on', 'xss_clean');
				$this->form_validation->set_rules('sod_filed_on', 'Statement of defence filed on', 'xss_clean');
				// $this->form_validation->set_rules('p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');

				// $this->form_validation->set_rules('no_of_dod', 'Number of days of delay', 'xss_clean');
				$this->form_validation->set_rules('sop_rejoin_sod_filed_on', 'Rejoinder to Statement of Defence filed on', 'xss_clean');
				$this->form_validation->set_rules('counter_claim', 'Counter claim', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_cc', 'Date of filing of Counter Claim', 'xss_clean');
				$this->form_validation->set_rules('sop_rt_cc_filed_on', 'Reply to Counter Claim filed on', 'xss_clean');
				// $this->form_validation->set_rules('sop_cc_p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');
				// $this->form_validation->set_rules('sop_cc_no_of_dod', 'Number of days of delay', 'xss_clean');
				$this->form_validation->set_rules('sop_rej_cc_filed_on', 'Rejoinder to Reply of Counter Claim filed on', 'xss_clean');
				$this->form_validation->set_rules('sop_app_act', 'Application under Section 17 of A&C Act', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_app', 'Date of filing of Application', 'xss_clean');
				$this->form_validation->set_rules('sop_rep_app_filed_on', 'Reply to the Application filed on', 'xss_clean');

				$this->form_validation->set_rules('sop_app_act_16', 'Application under Section 16 of A&C Act', 'xss_clean');
				$this->form_validation->set_rules('sop_dof_app_16', 'Date of filing of Application (16)', 'xss_clean');
				$this->form_validation->set_rules('sop_rep_app_filed_on_16', 'Reply to the Application filed on (16)', 'xss_clean');

				$this->form_validation->set_rules('sop_remarks', 'Remarks', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$sop_id = $this->security->xss_clean($data['hidden_sop_id']);

						$case_details_data = array(
							'claim_invited_on' => $this->security->xss_clean($data['claim_invited_on']),
							'rem_to_claim_on' => $this->security->xss_clean($data['rem_to_claim']),
							'rem_to_claim_on_2' => '',
							'claim_filed_on' => $this->security->xss_clean($data['claim_filed_on']),
							'res_served_on' => $this->security->xss_clean($data['res_served_on']),
							'sod_filed_on' => $this->security->xss_clean($data['sod_filed_on']),
							// 'sod_pol' => $this->security->xss_clean($data['sod_p_of_limitation']),
							// 'sod_dod' => '',
							'rej_stat_def_filed_on' => $this->security->xss_clean($data['sop_rejoin_sod_filed_on']),
							'counter_claim' => $this->security->xss_clean($data['counter_claim']),
							'dof_counter_claim' => '',
							'reply_counter_claim_on' => '',
							// 'reply_counter_claim_pol' => '',
							// 'reply_counter_claim_dod' => '',
							'rej_reply_counter_claim_on' => '',
							'app_section' => $this->security->xss_clean($data['sop_app_act']),
							'dof_app' => '',
							'reply_app_on' => '',
							'app_section_16' => $this->security->xss_clean($data['sop_app_act_16']),
							'dof_app_16' => '',
							'reply_app_on_16' => '',
							'remarks' => $this->security->xss_clean($data['sop_remarks']),
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						if (isset($data['rem_to_claim_2']) && !empty($data['rem_to_claim_2'])) {
							$case_details_data['rem_to_claim_on_2'] = $this->security->xss_clean($data['rem_to_claim_2']);
						}

						if ($data['sod_p_of_limitation'] == 'yes') {
							$case_details_data['sod_dod'] = $this->security->xss_clean($data['sod_no_of_dod']);
						}

						if ($data['counter_claim'] == 'yes') {
							$case_details_data['dof_counter_claim'] = $this->security->xss_clean($data['sop_dof_cc']);
							$case_details_data['reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rt_cc_filed_on']);
							// $case_details_data['reply_counter_claim_pol'] = $this->security->xss_clean($data['sop_cc_p_of_limitation']);
							// $case_details_data['reply_counter_claim_dod'] = $this->security->xss_clean($data['sop_cc_no_of_dod']); // condition
							$case_details_data['rej_reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rej_cc_filed_on']);
						}

						if ($data['sop_app_act'] == 'yes') {
							$case_details_data['dof_app'] = $this->security->xss_clean($data['sop_dof_app']);
							$case_details_data['reply_app_on'] = $this->security->xss_clean($data['sop_rep_app_filed_on']);
						}

						if ($data['sop_app_act_16'] == 'yes') {
							$case_details_data['dof_app_16'] = $this->security->xss_clean($data['sop_dof_app_16']);
							$case_details_data['reply_app_on_16'] = $this->security->xss_clean($data['sop_rep_app_filed_on_16']);
						}


						// Insert case details
						$result = $this->db->where('id', $sop_id)->update('cs_status_of_pleadings_tbl', $case_details_data);


						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_status_of_pleadings_tbl';
							$table_id = $sop_id;
							$message = 'Details of status of pleadings of case ' . $case_det['case_no'] . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong. Please try again.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;


			case 'ADD_CASE_ARBITRAL_TRIBUNAL':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				if ($this->security->xss_clean($data['at_arb_name']) == 'OTHER') {
					$this->form_validation->set_rules('at_arb_name', 'Arbitrator name', 'required|xss_clean');
					$this->form_validation->set_rules('at_arb_email', 'Email Address', 'required|xss_clean');
					$this->form_validation->set_rules('at_arb_contact', 'Contact Number', 'required|xss_clean');
					$this->form_validation->set_rules('at_arb_type', 'Arbitrator Type', 'required|xss_clean');
					$this->form_validation->set_rules('at_whe_on_panel', 'Wheather on panel', 'required|xss_clean');
					$this->form_validation->set_rules('at_category', 'Category', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
					$this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1', 'required|xss_clean');
					$this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
					$this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
					$this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');
				}
				$this->form_validation->set_rules('at_appointed_by', 'Appointed by', 'required|xss_clean');
				$this->form_validation->set_rules('at_doa', 'Date of appointment', 'required|xss_clean');
				$this->form_validation->set_rules('at_dod', 'Date of declaration', 'required|xss_clean');

				$this->form_validation->set_rules('at_terminated', 'Terminated or not', 'required|xss_clean');
				$this->form_validation->set_rules('at_dot', 'Date of termination', 'xss_clean');
				$this->form_validation->set_rules('at_rot', 'Reason for termination', 'xss_clean');


				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$name_of_arbitrator = $this->security->xss_clean($data['at_arb_name']);
						$arbitrator_code = generateCode();

						// !===========================================================

						if ($name_of_arbitrator == 'OTHER') {

							// Check if the email id or phone number is already available for that case number or not
							if ($data['at_arb_email']) {
								$checkContact = $this->db->select('*')
									->from('master_arbitrators_tbl')
									// ->where('case_no', $case_no)
									->where('record_status', 1)
									->where('(email="' . $this->security->xss_clean($data['at_arb_email']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Email is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							if ($data['at_arb_contact']) {
								$checkContact = $this->db->select('*')
									->from('master_arbitrators_tbl')
									// ->join('cs_arbitral_tribunal_tbl as cat','cat.name_of_arbitrator = amt.code')
									->where('record_status', 1)
									->where('contact_no', $this->security->xss_clean($data['at_arb_contact']))
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							$at_data = array(
								'at_code' => generateCode(),
								'case_no' => $case_no,
								'is_empanelled' => $this->input->post('is_empanelled'),
								'arbitrator_code' => $arbitrator_code,
								'arbitrator_type' => $this->security->xss_clean($data['at_arb_type']),
								'appointed_by' => $this->security->xss_clean($data['at_appointed_by']),
								'date_of_appointment' => ($data['at_doa']) ? formatDate($this->security->xss_clean($data['at_doa'])) : null,
								'date_of_declaration' => ($data['at_dod']) ? formatDate($this->security->xss_clean($data['at_dod'])) : null,
								'arb_terminated' => $this->security->xss_clean($data['at_terminated']),
								'at_termination_by' => '',
								'date_of_termination' => null,
								'reason_of_termination' => '',
								'termination_files' => '',
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);

							$arb_master_data = array(
								'code' => $arbitrator_code,
								'name_of_arbitrator' => $this->security->xss_clean($data['at_new_arb_name']),
								'email' => $this->security->xss_clean($data['at_arb_email']),
								'contact_no' => $this->security->xss_clean($data['at_arb_contact']),
								'category' => $this->security->xss_clean($data['at_category']),
								'whether_on_panel' => $this->security->xss_clean($data['at_whe_on_panel']),
								'perm_address_1' => $this->input->post('permanent_address_1'),
								'perm_address_2' => $this->input->post('permanent_address_2'),
								'perm_country' => $this->input->post('permanent_country'),
								'perm_state' => $this->input->post('permanent_state'),
								'perm_pincode' => $this->input->post('permanent_pincode'),
								'corr_address_1' => $this->input->post('corr_address_1'),
								'corr_address_2' => $this->input->post('corr_address_2'),
								'corr_country' => $this->input->post('corr_country'),
								'corr_state' => $this->input->post('corr_state'),
								'corr_pincode' => $this->input->post('corr_pincode'),
								'saved_from' => 'OTHER',
								'approved' => 0,
								'record_status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);

							$email_data = [
								'name_of_arbitrator' => $this->security->xss_clean($data['at_new_arb_name']),
								'email' => $this->security->xss_clean($data['at_arb_email']),
								'contact_no' => $this->security->xss_clean($data['at_arb_contact']),

							];

							$result2 = $this->db->insert('master_arbitrators_tbl', $arb_master_data);

							if (!$result2) {

								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
						} else {

							// If arbitrator is already appointed then do not add the arbitrator
							if ($name_of_arbitrator) {
								$checkContact = $this->db->select('*')
									->from('cs_arbitral_tribunal_tbl')
									->where('case_no', $case_no)
									->where('arb_terminated', 'no')
									->where('status', 1)
									->where('name_of_arbitrator', $name_of_arbitrator)
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Arbitrator is already registered against this case number.'
									);
									return $output;
								}
							}

							$email_data = $this->get($name_of_arbitrator, 'GET_ARBITRATOR_EMAIL');

							$at_data = array(
								'at_code' => generateCode(),
								'case_no' => $case_no,
								'name_of_arbitrator' => $name_of_arbitrator,
								'arbitrator_type' => $this->security->xss_clean($data['at_arb_type']),
								'appointed_by' => $this->security->xss_clean($data['at_appointed_by']),
								'date_of_appointment' => ($data['at_doa']) ? formatDate($this->security->xss_clean($data['at_doa'])) : null,
								'date_of_declaration' => ($data['at_dod']) ? formatDate($this->security->xss_clean($data['at_dod'])) : null,
								'arb_terminated' => $this->security->xss_clean($data['at_terminated']),
								'at_termination_by' => '',
								'date_of_termination' => null,
								'reason_of_termination' => '',
								'termination_files' => '',
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);
						}
						if (isset($data['at_terminated']) && $data['at_terminated'] == 'yes') {
							// Upload files ==============
							$terminationFileName = [];
							if (!empty(array_filter($_FILES['cd_termination_file']['name']))) {
								$this->load->library('fileupload');
								$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_termination_file'], [
									'file_name' => 'TERMINATION_FILES_' . time(),
									'file_move_path' => AT_TERMINATION_FILE_UPLOADS_FOLDER,
									'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
									'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
								]);

								// After getting result of file upload
								if ($file_result['status'] == false) {
									$this->db->trans_rollback();
									return $file_result;
								} else {
									$terminationFileName = $file_result['files'];
								}
							}

							$at_data['date_of_termination'] = ($data['at_dot']) ? formatDate($this->security->xss_clean($data['at_dot'])) : null;
							$at_data['reason_of_termination'] = $this->security->xss_clean($data['at_rot']);
							$at_data['at_termination_by'] = $this->security->xss_clean($data['at_termination_by']);
							$at['termination_files'] = (count($terminationFileName) > 0) ? json_encode($terminationFileName) : '';
						}

						// Insert case details
						$result = $this->db->insert('cs_arbitral_tribunal_tbl', $at_data);
						if ($result) {

							// Check the arbitrator status of case. If the arbitrator status is To be Appointed, then change it to Appointed.
							$checkAtStatus = $this->db->select('*')
								->from('cs_case_details_tbl')
								->where('slug', $case_no)
								->get()
								->row_array();

							if ($checkAtStatus && ($checkAtStatus['arbitrator_status'] == 0 || $checkAtStatus['arbitrator_status'] == 2)) {
								// Update the arbitrator status to 1
								$result = $this->db->where('slug', $case_no)
									->update('cs_case_details_tbl', [
										'arbitrator_status' => 1
									]);

								if (!$result) {
									$this->db->trans_rollback();
									return [
										'status' => false,
										'msg' => 'Error while updating the arbitrator status of case.'
									];
								}
							}

							// Send Mail to arbitrator ==================================
							// $at_mail_res = send_arbitrator_for_consent($email_data, '');

							// if ($at_mail_res['status'] == false) {
							// 	$this->db->trans_rollback();
							// 	return [
							// 		'status' => false,
							// 		'msg' => 'Error while sending mail to arbitrator. Please try again or contact support team. Error: ' . $at_mail_res['msg']
							// 	];
							// }

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_arbitral_tribunal_tbl';
							$message = 'A new arbitrator ' . $name_of_arbitrator . ' of case ' . $case_det['case_no'] . ' is added.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);


							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record saved successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_ARBITRAL_TRIBUNAL':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('at_arb_type', 'Arbitrator Type', 'required|xss_clean');
				$this->form_validation->set_rules('at_appointed_by', 'Appointed by', 'required|xss_clean');
				$this->form_validation->set_rules('at_doa', 'Date of appointment', 'required|xss_clean');
				$this->form_validation->set_rules('at_dod', 'Date of declaration', 'required|xss_clean');

				if ($this->input->post('at_arb_name') == 'OTHER') {
					$this->form_validation->set_rules('at_arb_name', 'Arbitrator name', 'required|xss_clean');
					$this->form_validation->set_rules('at_arb_email', 'Email Address', 'required|xss_clean');
					$this->form_validation->set_rules('at_arb_contact', 'Contact Number', 'required|xss_clean');
					$this->form_validation->set_rules('at_whe_on_panel', 'Wheather on panel', 'xss_clean');
					$this->form_validation->set_rules('at_category', 'Category', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
					$this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1', 'required|xss_clean');
					$this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
					$this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
					$this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');
				}

				$this->form_validation->set_rules('at_terminated', 'Terminated or not', 'xss_clean');
				$this->form_validation->set_rules('at_dot', 'Date of termination', 'xss_clean');
				$this->form_validation->set_rules('at_rot', 'Reason for termination', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$at_code = $this->security->xss_clean($data['hidden_at_id']);
						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$arbitrator_name = $this->security->xss_clean($data['at_arb_name']);

						$master_arb_code = '';

						if ($this->input->post('at_arb_name') == 'OTHER') {
							$master_arb_code = generateCode();

							// Check if the phone number or email id is already registered or not
							$check_count = $this->db->select('id')->from('master_arbitrators_tbl')->where([
								'record_status' => 1,
							])->where("email = '" . $data['at_arb_email'] . "' OR contact_no='" . $data['at_arb_contact'] . "'")->get()->num_rows();

							if ($check_count > 0) {
								$this->db->trans_rollback();
								return [
									'status' => false,
									'msg' => 'Email ID or phone number is already registered.'
								];
							}

							// Insert into master table
							$arb_master_data = array(
								'code' => $master_arb_code,
								'name_of_arbitrator' => $this->security->xss_clean($data['at_new_arb_name']),
								'email' => $this->security->xss_clean($data['at_arb_email']),
								'contact_no' => $this->security->xss_clean($data['at_arb_contact']),
								'category' => $this->security->xss_clean($data['at_category']),
								'whether_on_panel' => $this->security->xss_clean($data['at_whe_on_panel']),
								'perm_address_1' => $this->input->post('permanent_address_1'),
								'perm_address_2' => $this->input->post('permanent_address_2'),
								'perm_country' => $this->input->post('permanent_country'),
								'perm_state' => $this->input->post('permanent_state'),
								'perm_pincode' => $this->input->post('permanent_pincode'),
								'corr_address_1' => $this->input->post('corr_address_1'),
								'corr_address_2' => $this->input->post('corr_address_2'),
								'corr_country' => $this->input->post('corr_country'),
								'corr_state' => $this->input->post('corr_state'),
								'corr_pincode' => $this->input->post('corr_pincode'),
								'saved_from' => 'OTHER',
								'approved' => 0,
								'record_status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);

							$master_result = $this->db->insert('master_arbitrators_tbl', $arb_master_data);

							if (!$master_result) {
								$this->db->trans_rollback();
								return [
									'status' => false,
									'msg' => 'Error while saving the data into master table.'
								];
							}
						} else {
							// Check if the selected arbitrator is already appointed or not
							$check_count = $this->db->select('id')->from('cs_arbitral_tribunal_tbl')->where('id !=', $at_code)->where([
								'name_of_arbitrator' => $this->security->xss_clean($data['at_arb_name']),
								'case_no' => $case_no,
								'arb_terminated' => 'no',
								'status' => 1,
							])->get()->num_rows();


							if ($check_count > 0) {
								$this->db->trans_rollback();
								return [
									'status' => false,
									'msg' => 'This arbitrator is already appointed in this case.'
								];
							}
						}

						// ===================================
						if ($this->input->post('at_arb_name') == 'OTHER') {
							$name_of_arbitrator_code = $master_arb_code;
						} else {
							$name_of_arbitrator_code = $this->security->xss_clean($data['at_arb_name']);
						}

						// ===================================
						$at_data = array(
							'name_of_arbitrator' => $name_of_arbitrator_code,
							'arbitrator_type' => $this->security->xss_clean($data['at_arb_type']),
							'appointed_by' => $this->security->xss_clean($data['at_appointed_by']),
							'date_of_appointment' => ($data['at_doa']) ? formatDate($this->security->xss_clean($data['at_doa'])) : null,
							'date_of_declaration' => ($data['at_dod']) ? formatDate($this->security->xss_clean($data['at_dod'])) : null,
							'arb_terminated' => $this->security->xss_clean($data['at_terminated']),
							'at_termination_by' => '',
							'date_of_termination' => null,
							'reason_of_termination' => '',
							'termination_files' => '',
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						if (isset($data['at_terminated']) && $data['at_terminated'] == 'yes') {
							// Upload files ==============
							$terminationFileName = [];
							if (!empty(array_filter($_FILES['cd_termination_file']['name']))) {
								$this->load->library('fileupload');
								$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_termination_file'], [
									'file_name' => 'TERMINATION_FILES_' . time(),
									'file_move_path' => AT_TERMINATION_FILE_UPLOADS_FOLDER,
									'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
									'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
								]);

								// After getting result of file upload
								if ($file_result['status'] == false) {
									$this->db->trans_rollback();
									return $file_result;
								} else {
									$terminationFileName = $file_result['files'];
									$at_data['termination_files'] = (count($terminationFileName) > 0) ? json_encode($terminationFileName) : '';
								}
							}

							$at_data['date_of_termination'] = ($data['at_dot']) ? formatDate($this->security->xss_clean($data['at_dot'])) : null;
							$at_data['reason_of_termination'] = $this->security->xss_clean($data['at_rot']);
							$at_data['at_termination_by'] = $this->security->xss_clean($data['at_termination_by']);
						}

						// Insert case details
						$result = $this->db->where('id', $at_code)->update('cs_arbitral_tribunal_tbl', $at_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_arbitral_tribunal_tbl';
							$table_id = $data['hidden_at_id'];

							$message = 'Details of arbitrator ' . $arbitrator_name . ' of case ' . $case_det['case_no'] . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_CASE_ARB_TRI_LIST':

				$this->form_validation->set_rules('at_code', 'Case Arbitral Tribunal Id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$at_code = $this->security->xss_clean($data['at_code']);

					$r = $this->db->where('at_code', $at_code)->update('cs_arbitral_tribunal_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, name_of_arbitrator, case_no')->from('cs_arbitral_tribunal_tbl')->where('at_code', $at_code)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_arbitral_tribunal_tbl';
						$table_id = $data['id'];
						$message = 'Arbitrator ' . $data['name_of_arbitrator'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);


						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'UPDATE_ARB_EMP':
				$this->form_validation->set_rules('arb_emp_id', 'Arbitrator Empanelment Id ', 'required|xss_clean');
				$this->form_validation->set_rules('approved_status', 'Approved Status ', 'required|xss_clean');
				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$id = $this->security->xss_clean($data['arb_emp_id']);
						$this->db->select('*')->from('arbitrator_empanellment_tbl')->where('id', $id);
						$get_data = $this->db->get()->row_array();

						// Check Arbitrator Email id is already register
						if ($get_data['email']) {
							$checkContact = $this->db->select('email')
								->from('master_arbitrators_tbl')
								->where('record_status', 1)
								->where('(email="' . $this->security->xss_clean($get_data['email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Arbitrator email is already registered. Please use another email address.'
								);
								return $output;
							}
						}

						// Check Arbitrator Phone number is already register
						if ($get_data['phone_number']) {
							$checkContact = $this->db->select('contact_no')
								->from('master_arbitrators_tbl')
								->where('record_status', 1)
								->where('(contact_no="' . $this->security->xss_clean($get_data['phone_number']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Arbitrator phone number is already registered. Please use another phone number.'
								);
								return $output;
							}
						}
						if ($this->security->xss_clean($data['approved_status']) == 1) {
							$master_data = array(
								'code' 	=>	generateCode(),
								'name_of_arbitrator' =>	$get_data['name'],
								'email' =>	$get_data['email'],
								'contact_no' =>	$get_data['phone_number'],
								'category' =>	$get_data['empanellment_category'],
								'whether_on_panel' =>	'yes',
								'perm_address_1' =>	$get_data['perm_address_1'],
								'perm_address_2' =>	$get_data['perm_address_2'],
								'perm_country' =>	$get_data['perm_country'],
								'perm_state' =>	$get_data['perm_state'],
								'perm_pincode' =>	$get_data['perm_pincode'],
								'corr_address_1' =>	$get_data['corr_address_1'],
								'corr_address_2' =>	$get_data['corr_address_2'],
								'corr_country' =>	$get_data['corr_country'],
								'corr_state' =>	$get_data['corr_state'],
								'corr_pincode' =>	$get_data['corr_pincode'],
								'saved_from' =>	'OTHER',
								'approved' =>	1,
								'record_status' =>	1,
								'created_by' => $this->user_code,
								'created_at' => $this->date,
							);

							$master_result = $this->db->insert('master_arbitrators_tbl', $master_data);
							if (!$master_result) {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong in master data.";
							}
						}



						$data = array(
							'approved' => $this->security->xss_clean($data['approved_status']),
							'updated_by' => $this->user_code,
							'updated_at' => $this->date,

						);

						// Update empanelment arbitrator details
						$result = $this->db->where('id', $id)->update('arbitrator_empanellment_tbl', $data);

						if ($result) {
							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record Updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;


			case 'ADD_CASE_AWARD_TERMINATION':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				// $this->form_validation->set_rules('hidden_award_term_id', 'Award/termination', 'xss_clean');
				$this->form_validation->set_rules('award_term_select', 'Award/termination select', 'xss_clean');
				$this->form_validation->set_rules('award_term_doa', 'Date of award', 'xss_clean');
				$this->form_validation->set_rules('award_term_nature', 'Nature of award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
				$this->form_validation->set_rules('award_term_addendum', 'Addendum Award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
				$this->form_validation->set_rules('award_term_served_claimant', 'Award served to Claimant ontatus', 'xss_clean');

				$this->form_validation->set_rules('award_term_served_res', 'Award served to Respondent on', 'xss_clean');
				$this->form_validation->set_rules('award_term_dot', 'Date of termination', 'xss_clean');
				$this->form_validation->set_rules('award_term_rft', 'Reason for termination', 'xss_clean');

				$this->form_validation->set_rules('award_term_fee_rel', 'Fee released', 'xss_clean');
				$this->form_validation->set_rules('award_term_amt_fee', 'Amount of Fee Released', 'xss_clean');
				$this->form_validation->set_rules('award_term_det_fee', 'Details of fee released', 'xss_clean');

				$this->form_validation->set_rules('award_term_factsheet', 'Factsheet prepared', 'xss_clean');
				$this->form_validation->set_rules('award_term_dofp', 'Date of factsheet prepared', 'xss_clean');
				$this->form_validation->set_rules('note', 'Note', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$award_term_type = $this->security->xss_clean($data['award_term_select']);
						$case_no = $this->security->xss_clean($data['hidden_case_no']);

						$at_data = array(
							'case_no' => $case_no,
							'type' => $award_term_type,
							'date_of_award' => null,
							'nature_of_award' => null,
							'addendum_award' => null,
							'award_served_claimaint_on' => null,
							'award_served_respondent_on' => null,
							'date_of_termination' => null,
							'reason_for_termination' => null,
							'factsheet_prepared' => null,
							'date_of_factsheet' => null,
							'factsheet_file' => null,
							'note' => $this->security->xss_clean($data['note']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Check for the award type
						// And according to that store the corresponding fields
						if (isset($award_term_type) && !empty($award_term_type)) {
							if ($award_term_type == 'award') {
								if ($_FILES['award_term_award_file']['name'] != '') {
									$this->load->library('fileupload');
									// Upload files ==============
									$award_file_result = $this->fileupload->uploadSingleFile($_FILES['award_term_award_file'], [
										'raw_file_name' => 'award_term_award_file',
										'file_name' => 'AWARD_FILES_' . time(),
										'file_move_path' => AWARD_FILE_UPLOADS_FOLDER,
										'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
										'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
									]);

									// After getting result of file upload
									if ($award_file_result['status'] == false) {
										$this->db->trans_rollback();
										return $award_file_result;
									} else {
										$at_data['award_term_award_file'] = $award_file_result['file'];
									}
								}

								$at_data['date_of_award'] = formatDate($this->security->xss_clean($data['award_term_doa']));
								$at_data['nature_of_award'] = $this->security->xss_clean($data['award_term_nature']);
								$at_data['addendum_award'] = $this->security->xss_clean($data['award_term_addendum']);
								$at_data['award_served_claimaint_on'] = formatDate($this->security->xss_clean($data['award_term_served_claimant']));
								$at_data['award_served_respondent_on'] = formatDate($this->security->xss_clean($data['award_term_served_res']));
							}
							//  If Termination by is other
							elseif ($award_term_type == 'other') {
								$at_data['date_of_termination'] = $this->security->xss_clean($data['award_term_dot']);
								$at_data['reason_for_termination'] = $this->security->xss_clean($data['award_term_rft']);
							}
						}

						if ($this->security->xss_clean($data['award_term_factsheet']) == 'yes') {
							if ($_FILES['award_term_factsheet_file']['name'] != '') {
								$this->load->library('fileupload');
								// Upload files ==============
								$award_file_result = $this->fileupload->uploadSingleFile($_FILES['award_term_factsheet_file'], [
									'raw_file_name' => 'award_term_factsheet_file',
									'file_name' => 'FACTSHEET_FILES_' . time(),
									'file_move_path' => FACTSHEET_FILE_UPLOADS_FOLDER,
									'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
									'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
								]);

								// After getting result of file upload
								if ($award_file_result['status'] == false) {
									$this->db->trans_rollback();
									return $award_file_result;
								} else {
									$at_data['factsheet_file'] = $award_file_result['file'];
								}
							}

							$at_data['factsheet_prepared'] = $this->security->xss_clean($data['award_term_factsheet']);
							$at_data['date_of_factsheet'] = ($this->security->xss_clean($data['award_term_dofp'])) ? formatDate($this->security->xss_clean($data['award_term_dofp'])) : null;
						}


						// Insert case details
						$result = $this->db->insert('cs_award_term_tbl', $at_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_award_term_tbl';
							$message = 'Award & termination of ' . $case_det['case_no'] . ' is added.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record saved successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_AWARD_TERMINATION':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_award_term_id', 'Award/termination', 'required|xss_clean');
				$this->form_validation->set_rules('award_term_select', 'Award/termination select', 'xss_clean');
				$this->form_validation->set_rules('award_term_doa', 'Date of award', 'xss_clean');
				$this->form_validation->set_rules('award_term_nature', 'Nature of award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
				$this->form_validation->set_rules('award_term_addendum', 'Addendum Award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
				$this->form_validation->set_rules('award_term_served_claimant', 'Award served to Claimant ontatus', 'xss_clean');

				$this->form_validation->set_rules('award_term_served_res', 'Award served to Respondent on', 'xss_clean');
				$this->form_validation->set_rules('award_term_dot', 'Date of termination', 'xss_clean');
				$this->form_validation->set_rules('award_term_rft', 'Reason for termination', 'xss_clean');

				$this->form_validation->set_rules('award_term_fee_rel', 'Fee released', 'xss_clean');
				$this->form_validation->set_rules('award_term_amt_fee', 'Amount of Fee Released', 'xss_clean');
				$this->form_validation->set_rules('award_term_det_fee', 'Details of fee released', 'xss_clean');

				$this->form_validation->set_rules('award_term_factsheet', 'Factsheet prepared', 'xss_clean');
				$this->form_validation->set_rules('award_term_dofp', 'Date of factsheet prepared', 'xss_clean');
				$this->form_validation->set_rules('note', 'Note', 'xss_clean');



				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$hidden_award_term_id = $this->security->xss_clean($data['hidden_award_term_id']);
						$hidden_case_no = $this->security->xss_clean($data['hidden_case_no']);
						$award_term_type = $this->security->xss_clean($data['award_term_select']);


						$at_data = array(
							'case_no' => $hidden_case_no,
							'type' => $award_term_type,
							'date_of_award' => null,
							'nature_of_award' => null,
							'addendum_award' => null,
							'award_served_claimaint_on' => null,
							'award_served_respondent_on' => null,
							'date_of_termination' => null,
							'reason_for_termination' => null,
							'factsheet_prepared' => null,
							'date_of_factsheet' => null,
							'note' => $this->security->xss_clean($data['note']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Check for the award type
						// And according to that store the corresponding fields
						if (isset($award_term_type) && !empty($award_term_type)) {
							if ($award_term_type == 'award') {
								if ($_FILES['award_term_award_file']['name'] != '') {
									$this->load->library('fileupload');
									// Upload files ==============
									$award_file_result = $this->fileupload->uploadSingleFile($_FILES['award_term_award_file'], [
										'raw_file_name' => 'award_term_award_file',
										'file_name' => 'AWARD_FILES_' . time(),
										'file_move_path' => AWARD_FILE_UPLOADS_FOLDER,
										'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
										'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
									]);

									// After getting result of file upload
									if ($award_file_result['status'] == false) {
										$this->db->trans_rollback();
										return $award_file_result;
									} else {
										$at_data['award_term_award_file'] = $award_file_result['file'];
									}
								}

								$at_data['date_of_award'] = formatDate($this->security->xss_clean($data['award_term_doa']));
								$at_data['nature_of_award'] = $this->security->xss_clean($data['award_term_nature']);
								$at_data['addendum_award'] = $this->security->xss_clean($data['award_term_addendum']);
								$at_data['award_served_claimaint_on'] = formatDate($this->security->xss_clean($data['award_term_served_claimant']));
								$at_data['award_served_respondent_on'] = formatDate($this->security->xss_clean($data['award_term_served_res']));
							}
							//  If Termination by is other
							elseif ($award_term_type == 'other') {
								$at_data['date_of_termination'] = $this->security->xss_clean($data['award_term_dot']);
								$at_data['reason_for_termination'] = $this->security->xss_clean($data['award_term_rft']);
							}
						}

						if ($this->security->xss_clean($data['award_term_factsheet']) == 'yes') {

							if ($_FILES['award_term_factsheet_file']['name'] != '') {
								$this->load->library('fileupload');
								// Upload files ==============
								$award_file_result = $this->fileupload->uploadSingleFile($_FILES['award_term_factsheet_file'], [
									'raw_file_name' => 'award_term_factsheet_file',
									'file_name' => 'FACTSHEET_FILES_' . time(),
									'file_move_path' => FACTSHEET_FILE_UPLOADS_FOLDER,
									'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
									'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
								]);

								// After getting result of file upload
								if ($award_file_result['status'] == false) {
									$this->db->trans_rollback();
									return $award_file_result;
								} else {
									$at_data['factsheet_file'] = $award_file_result['file'];
								}
							}

							$at_data['factsheet_prepared'] = $this->security->xss_clean($data['award_term_factsheet']);
							$at_data['date_of_factsheet'] = formatDate($this->security->xss_clean($data['award_term_dofp']));
						}

						// Update termination data
						$result = $this->db->where('case_no', $hidden_case_no)->where('id', $hidden_award_term_id)->update('cs_award_term_tbl', $at_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($hidden_case_no);
							$table_name = 'cs_arbitral_tribunal_tbl';
							$table_id = $hidden_award_term_id;
							$message = 'Details of award & termination of case ' . $case_det['case_no'] . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_CASE_OTHER_PLEADINGS':

				// Other pleadings details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('op_details', 'Details', 'required|xss_clean');
				$this->form_validation->set_rules('op_dof', 'Date of filed', 'required|xss_clean');
				$this->form_validation->set_rules('op_filed_by', 'Filed by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$filed_by = $this->security->xss_clean($data['op_filed_by']);
						$at_data = array(
							'case_no' => $case_no,
							'details' => $this->security->xss_clean($data['op_details']),
							'date_of_filing' => $this->security->xss_clean($data['op_dof']),
							'filed_by' => $filed_by,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert case other pleadings details
						$result = $this->db->insert('cs_other_pleadings_tbl', $at_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_other_pleadings_tbl';
							$message = 'A new other pleadings of case ' . $case_det['case_no'] . ' which is filed by ' . $filed_by . ' is added.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record saved successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_OTHER_PLEADINGS':

				// Other pleadings details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_op_id', 'Other Pleadings id', 'required|xss_clean');
				$this->form_validation->set_rules('op_details', 'Details', 'required|xss_clean');
				$this->form_validation->set_rules('op_dof', 'Date of filed', 'required|xss_clean');
				$this->form_validation->set_rules('op_filed_by', 'Filed by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$op_id = $this->security->xss_clean($data['hidden_op_id']);
						$filed_by = $this->security->xss_clean($data['op_filed_by']);

						$at_data = array(
							'details' => $this->security->xss_clean($data['op_details']),
							'date_of_filing' => $this->security->xss_clean($data['op_dof']),
							'filed_by' => $filed_by,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Insert case other pleadings details
						$result = $this->db->where('id', $op_id)->where('case_no', $case_no)->update('cs_other_pleadings_tbl', $at_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_other_pleadings_tbl';
							$table_id = $op_id;
							$message = 'Details of other pleadings of case ' . $case_det['case_no'] . ' which is filed by ' . $filed_by . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_CASE_OTHER_PLEADINGS':

				$this->form_validation->set_rules('id', 'Other Pleadings Id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_other_pleadings_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, filed_by, case_no')->from('cs_other_pleadings_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_other_pleadings_tbl';
						$table_id = $id;
						$message = 'Other pleadings of ' . $data['filed_by'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_CASE_COUNSEL':

				// Counsels details
				$counsels_type = $this->security->xss_clean($data['counsels_list']);
				if ($counsels_type == 'OTHER') {
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
					$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
					$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
					$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
					$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
					$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
					$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
					$this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1 ', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
					$this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1 ', 'required|xss_clean');
					$this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
					$this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
					$this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');
				} else {
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
					$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
					$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
					$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
					$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
					$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
					$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
				}


				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$c_code = generateCode();
						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$name = $this->security->xss_clean($data['counsel_name']);

						if ($counsels_type == 'OTHER') {

							$name_code = generateCode();

							// Check if the email id or phone number is already available for that case number or not
							if ($data['counsel_enroll_no']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(enrollment_no ="' . $this->security->xss_clean($data['counsel_enroll_no']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Enrollment number is already registered. Please use another Enrollment Number.'
									);
									return $output;
								}
							}

							if ($data['counsel_email']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(email="' . $this->security->xss_clean($data['counsel_email']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Email id is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							if ($data['counsel_contact']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(phone_number ="' . $this->security->xss_clean($data['counsel_contact']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							$master_counsel_data = array(
								'code' => $name_code,
								'name' => $name,
								'enrollment_no' => $this->security->xss_clean($data['counsel_enroll_no']),
								'email' => $this->security->xss_clean($data['counsel_email']),

								'phone_number' => $this->security->xss_clean($data['counsel_contact']),

								'approved' => 0,
								'perm_address_1' => $this->security->xss_clean($data['permanent_address_1']),
								'perm_address_2' => $this->security->xss_clean($data['permanent_address_2']),
								'perm_country' => $this->security->xss_clean($data['permanent_country']),
								'perm_state' => $this->security->xss_clean($data['permanent_state']),
								'perm_pincode' => $this->security->xss_clean($data['permanent_pincode']),
								'corr_address_1' => $this->security->xss_clean($data['corr_address_1']),
								'corr_address_2' => $this->security->xss_clean($data['corr_address_2']),
								'corr_country' => $this->security->xss_clean($data['corr_country']),
								'corr_state' => $this->security->xss_clean($data['corr_state']),
								'corr_pincode' => $this->security->xss_clean($data['corr_pincode']),
								'record_status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);
							$master_result = $this->db->insert('master_counsels_tbl', $master_counsel_data);
							if (!$master_result) {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
						} else {
							$name_code = $this->security->xss_clean($data['counsels_code']);
							// Check if the email id or phone number is already available for that case number or not
							if ($data['counsels_code']) {
								$checkContact = $this->db->select('*')
									->from('cs_counsels_tbl')
									->where('case_no', $case_no)
									->where('status', 1)
									->where('(name_code ="' . $this->security->xss_clean($data['counsels_code']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Counsels is already registered against this case. Please use another Counsels.'
									);
									return $output;
								}
							}
						}

						$counsel_data = array(
							'code' => $c_code,
							'case_no' => $case_no,
							'name_code' => $name_code,
							'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
							'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
							'date_of_discharge' => null,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						if ($counsel_data['discharge'] == 1) {
							$counsel_data['date_of_discharge'] = ($data['counsel_dodis']) ? formatDate($this->security->xss_clean($data['counsel_dodis'])) : null;
						}

						// Insert case counsel details
						$result = $this->db->insert('cs_counsels_tbl', $counsel_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $c_code, 'COUNSEL');
							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_counsels_tbl';
							$message = 'A new counsel ' . $name . ' of case ' . $case_det['case_no'] . ' is added.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record saved successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'UPDATE_CASE_COUNSEL':
				// Counsels details
				$counsels_type = $this->security->xss_clean($data['counsels_list']);
				if ($counsels_type == 'OTHER') {
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
					$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
					$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
					$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
					$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
					$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
					$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
					$this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1 ', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
					$this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
					$this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1 ', 'required|xss_clean');
					$this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
					$this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
					$this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');
				} else {
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
					$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
					$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
					$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
					$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
					$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
					$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
				}

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$c_code = generateCode();
						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$name = $this->security->xss_clean($data['counsel_name']);

						$counsel_code = $this->security->xss_clean($data['hidden_counsel_code']);
						$counsel_id = $this->security->xss_clean($data['hidden_counsel_id']);

						if ($counsels_type == 'OTHER') {

							$name_code = generateCode();
							// Check if the email id or phone number is already available for that case number or not
							if ($data['counsel_enroll_no']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(enrollment_no ="' . $this->security->xss_clean($data['counsel_enroll_no']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Enrollment number is already registered. Please use another Enrollment Number.'
									);
									return $output;
								}
							}

							if ($data['counsel_email']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(email="' . $this->security->xss_clean($data['counsel_email']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Email id is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							if ($data['counsel_contact']) {
								$checkContact = $this->db->select('*')
									->from('master_counsels_tbl')
									->where('record_status', 1)
									->where('(phone_number ="' . $this->security->xss_clean($data['counsel_contact']) . '")')
									->get()
									->num_rows();

								if ($checkContact > 0) {
									$output = array(
										'status' => 'validationerror',
										'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
									);
									return $output;
								}
							}

							$master_counsel_data = array(
								'code' => $name_code,
								'name' => $name,
								'enrollment_no' => $this->security->xss_clean($data['counsel_enroll_no']),
								'email' => $this->security->xss_clean($data['counsel_email']),

								'phone_number' => $this->security->xss_clean($data['counsel_contact']),

								'approved' => 0,
								'perm_address_1' => $this->security->xss_clean($data['permanent_address_1']),
								'perm_address_2' => $this->security->xss_clean($data['permanent_address_2']),
								'perm_country' => $this->security->xss_clean($data['permanent_country']),
								'perm_state' => $this->security->xss_clean($data['permanent_state']),
								'perm_pincode' => $this->security->xss_clean($data['permanent_pincode']),
								'corr_address_1' => $this->security->xss_clean($data['corr_address_1']),
								'corr_address_2' => $this->security->xss_clean($data['corr_address_2']),
								'corr_country' => $this->security->xss_clean($data['corr_country']),
								'corr_state' => $this->security->xss_clean($data['corr_state']),
								'corr_pincode' => $this->security->xss_clean($data['corr_pincode']),
								'record_status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);

							$master_result = $this->db->insert('master_counsels_tbl', $master_counsel_data);
							if (!$master_result) {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
							$counsel_data = array(
								'name_code' => $name_code,
								'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
								'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
								'date_of_discharge' => null,
								'status' => 1,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date
							);
						} else {

							$counsel_data = array(
								'name_code' => $this->security->xss_clean($data['counsels_code']),
								'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
								'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
								'date_of_discharge' => null,
								'status' => 1,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date
							);
						}
						if ($counsel_data['discharge'] == 1) {
							$counsel_data['date_of_discharge'] = ($data['counsel_dodis']) ? formatDate($this->security->xss_clean($data['counsel_dodis'])) : null;
						}
						$result = $this->db->where('code', $counsel_code)->update('cs_counsels_tbl', $counsel_data);
						if ($result) {

							$table_id = $counsel_id;

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $counsel_code, 'COUNSEL');

							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_counsels_tbl';
							$message = 'Details of counsel ' . $name . ' of case ' . $case_det['case_no'] . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

				// this function created by Ameen on dated 26-april-2023
			case 'ADD_CASE_ORDER':
				$this->form_validation->set_rules('order_given_by', 'Order Given By', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$noting_data = array(
							'code' => generateCode(),
							'case_code' => $this->security->xss_clean($data['case_code']),
							'order_given_by' => $this->security->xss_clean($data['order_given_by']),
							'created_by' => $this->user_code,
							'created_at' => $this->date,

						);

						// print_r($_FILES['case_order_doc']);
						// 	die;
						// Upload files ==============
						$notingFileName = [];
						if ($_FILES['case_order_doc']['name'] != '') {
							$this->load->library('fileupload');
							// Upload files ==============
							$notingFileName = $this->fileupload->uploadSingleFile($_FILES['case_order_doc'], [
								'raw_file_name' => 'case_order_doc',
								'file_name' => 'CASE_ORDERS_' . time(),
								'file_move_path' => CASE_ORDER_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($notingFileName['status'] == false) {
								$this->db->trans_rollback();
								return $notingFileName;
							} else {
								$noting_data['document'] = $notingFileName['file'];
							}
						} else {
							echo json_encode([
								'status' => false,
								'msg' => 'Document is required to submit the form.'
							]);
							die;
						}


						// Update case noting details
						$result = $this->db->insert('case_orders_tbl', $noting_data);

						if ($result) {
							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record inserted successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_ORDER':
				// Case details
				$this->form_validation->set_rules('hidden_code', 'code', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$code = $this->security->xss_clean($data['hidden_code']);

						$noting_data = array(
							'order_given_by' => $this->security->xss_clean($data['order_given_by']),
							'record_status' => 1,
							// 'created_by' => $this->user_code,
							// 'created_at' => $this->date,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date

						);

						// Upload files ==============
						$notingFileName = [];
						if ($_FILES['case_order_doc']['name'] != '') {
							$this->load->library('fileupload');
							// Upload files ==============
							$notingFileName = $this->fileupload->uploadSingleFile($_FILES['case_order_doc'], [
								'raw_file_name' => 'case_order_doc',
								'file_name' => 'CASE_ORDERS_' . time(),
								'file_move_path' => CASE_ORDER_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($notingFileName['status'] == false) {
								$this->db->trans_rollback();
								return $notingFileName;
							} else {
								$noting_data['document'] = $notingFileName['file'];
							}
						}


						// Update case noting details
						$result = $this->db->where('code', $code)->update('case_orders_tbl', $noting_data);

						if ($result) {
							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_CASE_ORDER':
				$this->form_validation->set_rules('code', 'Code', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$code = $this->security->xss_clean($data['code']);

					$r = $this->db->where('code', $code)->update('case_orders_tbl', array('record_status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// // Update the data logs table for data tracking
						// $data = $this->db->select('id, name, case_no')->from('cs_counsels_tbl')->where('code', $code)->get()->row_array();

						// $case_det = $this->common_model->get_case_details_from_slug($data['case_no']);

						// $table_name = 'cs_counsels_tbl';
						// $table_id = $data['id'];
						// $message = 'Counsel ' . $data['name'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
						// $this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_COUNSEL':

				// Counsels details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_counsel_id', 'Counsel id', 'required|xss_clean');
				$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
				$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
				$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
				$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
				$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
				$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
				$this->form_validation->set_rules('permanent_address_1', 'Permanent Address 1 ', 'required|xss_clean');
				$this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required|xss_clean');
				$this->form_validation->set_rules('permanent_state', 'Permanent State', 'required|xss_clean');
				$this->form_validation->set_rules('permanent_pincode', 'Permanent Pincode', 'required|xss_clean');
				$this->form_validation->set_rules('corr_address_1', 'Correspondence Address 1 ', 'required|xss_clean');
				$this->form_validation->set_rules('corr_country', 'Correspondence Country', 'required|xss_clean');
				$this->form_validation->set_rules('corr_state', 'Correspondence State', 'required|xss_clean');
				$this->form_validation->set_rules('corr_pincode', 'Correspondence Pincode', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$counsel_id = $this->security->xss_clean($data['hidden_counsel_id']);
						$counsel_code = $this->security->xss_clean($data['hidden_counsel_code']);
						$name = $this->security->xss_clean($data['counsel_name']);

						// Check if the email id or phone number is already available for that case number or not 

						if ($data['counsel_enroll_no']) {
							$checkContact = $this->db->select('*')
								->from('cs_counsels_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('code !=', $counsel_code)
								->where('(enrollment_no ="' . $this->security->xss_clean($data['counsel_enroll_no']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Enrollment number is already registered. Please use another Enrollment Number.'
								);
								return $output;
							}
						}

						if ($data['counsel_contact']) {
							$checkContact = $this->db->select('*')
								->from('cs_counsels_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('code !=', $counsel_code)
								->where('(phone ="' . $this->security->xss_clean($data['counsel_contact']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Phone number is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						if ($data['counsel_email']) {
							$checkContact = $this->db->select('*')
								->from('cs_counsels_tbl')
								->where('case_no', $case_no)
								->where('status', 1)
								->where('code !=', $counsel_code)
								->where('(email="' . $this->security->xss_clean($data['counsel_email']) . '")')
								->get()
								->num_rows();

							if ($checkContact > 0) {
								$output = array(
									'status' => 'validationerror',
									'msg' => 'Email id is already registered against this case number. Please use another contact details.'
								);
								return $output;
							}
						}

						$counsel_data = array(
							'enrollment_no' => $this->security->xss_clean($data['counsel_enroll_no']),
							'name' => $name,
							'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
							'email' => $this->security->xss_clean($data['counsel_email']),
							'phone' => $this->security->xss_clean($data['counsel_contact']),
							'additonal_contact' => $this->security->xss_clean($data['counsel_additional_contact']),
							'additional_email' => $this->security->xss_clean($data['counsel_additional_email']),
							'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
							'date_of_discharge' => null,
							'perm_address_1' => $this->security->xss_clean($data['permanent_address_1']),
							'perm_address_2' => $this->security->xss_clean($data['permanent_address_2']),
							'perm_country' => $this->security->xss_clean($data['permanent_country']),
							'perm_state' => $this->security->xss_clean($data['permanent_state']),
							'perm_pincode' => $this->security->xss_clean($data['permanent_pincode']),
							'corr_address_1' => $this->security->xss_clean($data['corr_address_1']),
							'corr_address_2' => $this->security->xss_clean($data['corr_address_2']),
							'corr_country' => $this->security->xss_clean($data['corr_country']),
							'corr_state' => $this->security->xss_clean($data['corr_state']),
							'corr_pincode' => $this->security->xss_clean($data['corr_pincode']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						if ($counsel_data['discharge'] == 1) {
							$counsel_data['date_of_discharge'] = ($data['counsel_dodis']) ? formatDate($this->security->xss_clean($data['counsel_dodis'])) : null;
						}

						// Update case counsel details
						$result = $this->db->where('code', $counsel_code)->where('case_no', $case_no)->update('cs_counsels_tbl', $counsel_data);

						if ($result) {
							$table_id = $counsel_id;

							// Insert the address ================================
							$rowCountArray = json_decode($this->security->xss_clean($data['row_count_array']));

							$addressResult = $this->insert_addresses($rowCountArray, $data, $counsel_code, 'COUNSEL');

							if (isset($addressResult['status']) && $addressResult['status'] == false) {
								return $addressResult;
							}

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_counsels_tbl';
							$message = 'Details of counsel ' . $name . ' of case ' . $case_det['case_no'] . ' is updated.';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = "Record updated successfully";
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_CASE_COUNSEL':

				$this->form_validation->set_rules('code', 'Counsel Id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$code = $this->security->xss_clean($data['code']);

					$r = $this->db->where('code', $code)->update('cs_counsels_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, case_no')->from('cs_counsels_tbl')->where('code', $code)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);

						$table_name = 'cs_counsels_tbl';
						$table_id = $data['id'];
						$message = 'Counsel  of case ' . $case_det['case_no'] . ' is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_CASE_NOTING':

				// Noting details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');

				$this->form_validation->set_rules('noting_marked_to', 'Marked to', 'required|xss_clean');
				// $this->form_validation->set_rules('noting_date', 'Date', 'required|xss_clean');
				$this->form_validation->set_rules('noting_next_date', 'Next Date', 'xss_clean');

				// Check for noting text
				if ($this->input->post('noting_text') == '' && $this->input->post('noting_text_code') == '') {
					return array('status' => false, 'msg' => 'Please enter noting text or select the noting text from dropdown.');
				}


				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no  = $this->security->xss_clean($data['hidden_case_no']);
						$marked_to = $this->security->xss_clean($data['noting_marked_to']);

						$noting_data = array(
							'case_no' => $case_no,
							'noting' => $this->security->xss_clean($data['noting_text']),
							'noting_text_code' => $this->security->xss_clean($data['noting_text_code']),
							'noting_date' => formatDate(date('d-m-Y')),
							'next_date' => formatDate($this->security->xss_clean($data['noting_next_date'])),
							'marked_to' => $marked_to,
							'marked_by' => $this->user_code,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Upload files ==============
						$notingFileName = [];
						if (!empty(array_filter($_FILES['cd_noting_file']['name']))) {
							$this->load->library('fileupload');
							$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_noting_file'], [
								'file_name' => 'NOTING_FILES_' . time(),
								'file_move_path' => NOTING_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => NOTING_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => NOTING_FILE_ALLOWED_MIME_TYPES
							]);

							// After getting result of file upload
							if ($file_result['status'] == false) {
								$this->db->trans_rollback();
								return $file_result;
							} else {
								$notingFileName = $file_result['files'];
								$noting_data['noting_file'] = (count($notingFileName) > 0) ? json_encode($notingFileName) : '';
							}
						}
						// Insert case noting details
						$result = $this->db->insert('cs_noting_tbl', $noting_data);

						// Get the last inserted id
						$last_inserted_id = $this->db->insert_id();

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$user = $this->common_model->get_user_details_using_usercode($marked_to);

							$table_name = 'cs_noting_tbl';
							$table_id = $last_inserted_id;
							$message = 'A new noting of case ' . $case_det['case_no'] . ' is added for ' . $user['user_display_name'] . ' (' . $user['job_title'] . ')';
							$this->common_model->update_data_logs($table_name, $table_id, $message);

							// Insert notification =============================
							$result2 = $this->notification_model->insertNotification($last_inserted_id, $table_name, $this->security->xss_clean($data['noting_marked_to']), $case_no);

							if ($result2) {
								$this->db->trans_commit();
								$dbstatus = true;
								$dbmessage = "Record saved successfully";
							} else {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = "Something went wrong.";
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_NOTING':

				if ($this->role == 'DIAC') {

					$this->form_validation->set_rules('hidden_noting_id', 'Noting id', 'required|xss_clean');
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('noting_text', 'Noting text', 'required|xss_clean');
					$this->form_validation->set_rules('noting_marked_to', 'Marked to', 'required|xss_clean');
					$this->form_validation->set_rules('noting_date', 'Date', 'required|xss_clean');
					$this->form_validation->set_rules('noting_next_date', 'Next Date', 'xss_clean');

					if ($this->form_validation->run()) {
						$this->db->trans_begin();
						try {

							$case_no = $this->security->xss_clean($data['hidden_case_no']);
							$noting_id = $this->security->xss_clean($data['hidden_noting_id']);
							$noting_date = $this->security->xss_clean($data['noting_date']);
							$marked_to = $this->security->xss_clean($data['noting_marked_to']);

							$noting_data = array(
								'case_no' => $this->security->xss_clean($data['hidden_case_no']),
								'noting' => $this->security->xss_clean($data['noting_text']),
								'noting_date' => formatDate($noting_date),
								'next_date' => formatDate($this->security->xss_clean($data['noting_next_date'])),
								'marked_to' => $marked_to,
								'marked_by' => $this->user_code,
								'status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date,
								'updated_by' => $this->user_code,
								'updated_at' => $this->date
							);

							// Upload files ==============
							$notingFileName = [];
							if (!empty(array_filter($_FILES['cd_noting_file']['name']))) {
								$this->load->library('fileupload');
								$file_result = $this->fileupload->uploadMultipleFiles($_FILES['cd_noting_file'], [
									'file_name' => 'TERMINATION_FILES_' . time(),
									'file_move_path' => NOTING_FILE_UPLOADS_FOLDER,
									'allowed_file_types' => NOTING_FILE_FORMATS_ALLOWED,
									'allowed_mime_types' => NOTING_FILE_ALLOWED_MIME_TYPES
								]);

								// After getting result of file upload
								if ($file_result['status'] == false) {
									$this->db->trans_rollback();
									return $file_result;
								} else {
									$notingFileName = $file_result['files'];
									$noting_data['noting_file'] = (count($notingFileName) > 0) ? json_encode($notingFileName) : '';
								}
							}


							// Update case noting details
							$result = $this->db->where('id', $noting_id)->where('case_no', $case_no)->update('cs_noting_tbl', $noting_data);

							if ($result) {

								// Update the data logs table for data tracking
								$case_det = $this->common_model->get_case_details_from_slug($case_no);
								$user = $this->common_model->get_user_details_using_usercode($marked_to);

								$table_name = 'cs_arbitral_tribunal_tbl';
								$table_id = $noting_id;
								$message = 'Details of noting dated ' . $noting_date . ' of case ' . $case_det['case_no'] . ' for ' . $user['user_display_name'] . ' (' . $user['job_title'] . ') is updated.';
								$this->common_model->update_data_logs($table_name, $table_id, $message);

								$this->db->trans_commit();
								$dbstatus = true;
								$dbmessage = "Record updated successfully";
							} else {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
						} catch (Exception $e) {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = 'Something went wrong';
						}
					} else {
						$dbstatus = 'validationerror';
						$dbmessage = validation_errors();
					}
				} else {
					$dbstatus = false;
					$dbmessage = 'You are not authorized to perform this action.';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'ADD_CASE_ALLOTMENT':

				// Case Allotment details
				$this->form_validation->set_rules('ca_case_no', 'Case No.', 'required|xss_clean');
				// $this->form_validation->set_rules('ar_role', 'Allotment Role', 'required|xss_clean');
				// $this->form_validation->set_rules('ca_allotted_to[]', 'Allotted To', 'required|xss_clean');


				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					$flag = false;
					try {
						$case_no = $this->security->xss_clean($data['ca_case_no']);
						$ca_allotted_to_dc = (isset($data['ca_allotted_to_dc'])) ? $this->security->xss_clean($data['ca_allotted_to_dc']) : [];
						$ca_allotted_to_cm = (isset($data['ca_allotted_to_cm'])) ? $this->security->xss_clean($data['ca_allotted_to_cm']) : [];
						// $allotment_role = $this->security->xss_clean($data['ar_role']);

						if (count($ca_allotted_to_dc) < 1 && count($ca_allotted_to_cm) < 1) {
							$this->db->trans_rollback();
							return array(
								'status' => false,
								'msg' => "Please select deputy counsel or case manager."
							);
						}

						if (count($ca_allotted_to_dc) > 0) {
							foreach ($ca_allotted_to_dc as $key => $user) {
								// Check if the case is already alloted or not
								// if alloted, return with error message
								$res = $this->common_model->check_case_allotted($case_no, $user);

								if ($res) {
									$this->db->trans_rollback();
									return array(
										'status' => false,
										'msg' => "Case is already allotted to " . $user
									);
								}

								// Else allot the case to user
								$ca_data = array(
									'case_no' => $case_no,
									'alloted_to' => $user,
									'user_role' => 'DEPUTY_COUNSEL',
									'alloted_by' => $this->user_code,
									'status' => 1,
									'created_by' => $this->user_code,
									'created_at' => $this->date
								);

								// Insert case allotment details
								$result = $this->db->insert('cs_case_allotment_tbl', $ca_data);

								if ($result) {

									$table_id = $this->db->insert_id();

									// Update the data logs table for data tracking
									$case_det = $this->common_model->get_case_details_from_slug($case_no);
									$user = $this->common_model->get_user_details_using_usercode($user);

									$table_name = 'cs_case_allotment_tbl';
									$message = 'A case ' . $case_det['case_no'] . ' is alloted to ' . $user['user_display_name'] . ' (' . $user['job_title'] . ')';
									$this->common_model->update_data_logs($table_name, $table_id, $message);

									// Insert notification =============================
									$result2 = $this->notification_model->insertNotification($table_id, $table_name, $user['user_code'], $case_no);

									if (!$result2) {
										$this->db->trans_rollback();
										return array(
											'status' => false,
											'msg' => "Server failed while saving data. Please try again or contact support."
										);
									}
								} else {
									$this->db->trans_rollback();
									return array(
										'status' => false,
										'msg' => "Server failed while saving data. Please try again or contact support."
									);
								}
							}
						}

						if (count($ca_allotted_to_cm) > 0) {
							foreach ($ca_allotted_to_cm as $key => $user) {
								// Check if the case is already alloted or not
								// if alloted, return with error message
								$res = $this->common_model->check_case_allotted($case_no, $user);

								if ($res) {
									$this->db->trans_rollback();
									return array(
										'status' => false,
										'msg' => "Case is already allotted to " . $user
									);
								}

								// Else allot the case to user
								$ca_data = array(
									'case_no' => $case_no,
									'alloted_to' => $user,
									'user_role' => 'CASE_MANAGER',
									'alloted_by' => $this->user_code,
									'status' => 1,
									'created_by' => $this->user_code,
									'created_at' => $this->date
								);

								// Insert case allotment details
								$result = $this->db->insert('cs_case_allotment_tbl', $ca_data);

								if ($result) {

									$table_id = $this->db->insert_id();

									// Update the data logs table for data tracking
									$case_det = $this->common_model->get_case_details_from_slug($case_no);
									$user = $this->common_model->get_user_details_using_usercode($user);

									$table_name = 'cs_case_allotment_tbl';
									$message = 'A case ' . $case_det['case_no'] . ' is alloted to ' . $user['user_display_name'] . ' (' . $user['job_title'] . ')';
									$this->common_model->update_data_logs($table_name, $table_id, $message);

									// Insert notification =============================
									$result2 = $this->notification_model->insertNotification($table_id, $table_name, $user['user_code'], $case_no);

									if (!$result2) {
										$this->db->trans_rollback();
										return array(
											'status' => false,
											'msg' => "Server failed while saving data. Please try again or contact support."
										);
									}
								} else {
									$this->db->trans_rollback();
									return array(
										'status' => false,
										'msg' => "Server failed while saving data. Please try again or contact support."
									);
								}
							}
						}

						$this->db->trans_commit();
						return array(
							'status' => true,
							'msg' => "Record saved successfully"
						);
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'EDIT_CASE_ALLOTMENT':

				// Case allotment
				$this->form_validation->set_rules('hidden_ca_id', 'Case allotment id', 'required|xss_clean');
				$this->form_validation->set_rules('ca_case_no', 'Case No.', 'required|xss_clean');
				// $this->form_validation->set_rules('ar_role', 'Allotment Role', 'required|xss_clean');
				// $this->form_validation->set_rules('ca_allotted_to[]', 'Allotted To', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$ca_id = $this->security->xss_clean($data['hidden_ca_id']);
						$case_no = $this->security->xss_clean($data['ca_case_no']);

						$alloted_to = '';

						if (isset($data['ca_allotted_to_cm'])) {
							$alloted_to = $this->security->xss_clean($data['ca_allotted_to_cm'][0]);
						}
						if (isset($data['ca_allotted_to_dc'])) {
							$alloted_to = $this->security->xss_clean($data['ca_allotted_to_dc'][0]);
						}

						if (!$alloted_to) {
							$this->db->trans_rollback();
							return array(
								'status' => false,
								'msg' => 'Allotted to field is required.',
							);
						}

						// $allotment_role = $this->security->xss_clean($data['ar_role']);

						// Check if the case is already alloted or not
						// if alloted, return with error message
						$res = $this->common_model->check_case_allotted($case_no, $alloted_to);

						if ($res) {
							$dbstatus = false;
							$dbmessage = "Case is already allotted.";
						} else {

							$ca_data = array(
								'case_no' => $this->security->xss_clean($data['ca_case_no']),
								'alloted_to' => $alloted_to,
								'alloted_by' => $this->user_code,
								'status' => 1,
								'created_by' => $this->user_code,
								'created_at' => $this->date
							);


							// Update case allotment details
							$result = $this->db->where('id', $ca_id)->update('cs_case_allotment_tbl', $ca_data);

							if ($result) {

								// Update the data logs table for data tracking
								$case_det = $this->common_model->get_case_details_from_slug($case_no);
								$user = $this->common_model->get_user_details_using_usercode($alloted_to);

								$table_name = 'cs_case_allotment_tbl';
								$table_id = $ca_id;
								$message = 'Details of case allotment of case ' . $case_det['case_no'] . ' allotted to ' . $user['user_display_name'] . ' (' . $user['job_title'] . ')' . ' is updated.';
								$this->common_model->update_data_logs($table_name, $table_id, $message);

								$this->db->trans_commit();
								$dbstatus = true;
								$dbmessage = "Record updated successfully";
							} else {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Something went wrong.";
							}
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Something went wrong';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_CASE_ALLOTMENT':

				$this->form_validation->set_rules('id', 'Case allotment id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_case_allotment_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, alloted_to, case_no')->from('cs_case_allotment_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$user = $this->common_model->get_user_details_using_usercode($data['alloted_to']);

						$table_name = 'cs_case_allotment_tbl';
						$table_id = $id;
						$message = 'Case allotment of case ' . $case_det['case_no'] . ' allotted to ' . $user['user_display_name'] . ' (' . $user['job_title'] . ')' . ' is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;



			case 'EDIT_ADDRESS':
				$this->form_validation->set_rules('hidden_address_id', 'Id', 'required|xss_clean');
				$this->form_validation->set_rules('address_address_one', 'Address 1', 'xss_clean');
				$this->form_validation->set_rules('address_address_two', 'Address 2', 'xss_clean');
				$this->form_validation->set_rules('address_state', 'State', 'xss_clean');
				$this->form_validation->set_rules('address_country', 'Country', 'xss_clean');
				$this->form_validation->set_rules('address_pincode', 'Pincode', 'xss_clean');
				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {
						$id = $this->security->xss_clean($data['hidden_address_id']);

						$data = array(
							'address_one' => $this->security->xss_clean($data['address_address_one']),
							'address_two' => $this->security->xss_clean($data['address_address_two']),
							'state' => $this->security->xss_clean($data['address_state']),
							'country' => $this->security->xss_clean($data['address_country']),
							'pincode' => $this->security->xss_clean($data['address_pincode']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						$result = $this->db->where('id', $id)->update('cs_addresses_tbl', $data);
						if ($result) {

							$table_name = 'cs_addresses_tbl';
							$table_id = $id;
							$message = 'Address is updated.';

							$this->common_model->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
							$output = array(
								'status' => true,
								'msg' => 'Address updated successfully'
							);
						} else {
							$this->db->trans_rollback();
							$output = array(
								'status' => false,
								'msg' => 'Something went wrong'
							);
						}
					} catch (Exception $e) {
						$this->db->trans_rollback();
						$output = array(
							'status' => false,
							'msg' => 'Something went wrong'
						);
					}
				} else {
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				return $output;
				break;


			case 'DELETE_ADDRESS':

				$this->form_validation->set_rules('id', 'Id', 'required');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cs_addresses_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {
						$table_name = 'cs_addresses_tbl';
						$table_id = $id;
						$message = 'Address is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Address deleted successfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				} else {
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	protected function insert_addresses($rowCountArray, $data, $typeId, $personType)
	{
		if (count($rowCountArray) > 0) {
			$addresses = [];
			foreach ($rowCountArray as $row) {
				if (!empty($data['common_address_one_' . $row]) || !empty($data['common_address_two_' . $row]) || !empty($data['common_address_state_' . $row]) || !empty($data['common_address_country_' . $row]) || !empty($data['common_address_pincode_' . $row])) {
					array_push($addresses, array(
						'person_type' => $personType,
						'type_code' => $typeId,
						'address_one' => $this->security->xss_clean($data['common_address_one_' . $row]),
						'address_two' => $this->security->xss_clean($data['common_address_two_' . $row]),
						'state' => $this->security->xss_clean($data['common_address_state_' . $row]),
						'country' => $this->security->xss_clean($data['common_address_country_' . $row]),
						'pincode' => $this->security->xss_clean($data['common_address_pincode_' . $row]),
						'created_by' => $this->user_code,
						'created_at' => $this->date
					));
				}
			}
			if (count($addresses) > 0) {
				$result = $this->db->insert_batch('cs_addresses_tbl', $addresses);
				if (!$result) {
					$this->db->trans_rollback();
					return array(
						'status' => false,
						'msg' => 'Error while saving address. Please try again.'
					);
				} else {
					return array(
						'status' => true
					);
				}
			}
		}
	}

	protected function uploadSingleFiles($file, $configuration)
	{
		$date = date('Y-m-d H:i:s', time());
		$unique = mt_rand(100, 999);

		$upload_file_name = $file['name'];

		$file_name = $upload_file_name;
		$allowed_mime_type_arr = $configuration['allowed_mime_types'];
		$mime = get_mime_by_extension($file_name);
		$dot_count 	= substr_count($file_name, '.');
		$zero_count = substr_count($file_name, "%0");

		if (in_array($mime, $allowed_mime_type_arr)) {
			if ($zero_count == 0 && $dot_count == 1) {
				$file_move_path  = $configuration['file_move_path'];
				if (!is_dir($file_move_path)) {
					mkdir($file_move_path, 0777, true);
				}
				$config['upload_path'] 		= $file_move_path;
				$config['file_name'] 		= $configuration['file_name'];
				$config['allowed_types'] 	= $configuration['allowed_file_types'];
				$config['max_size']         = FILE_MAX_SIZE;
				$config['overwrite'] 		= TRUE;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload($configuration['raw_file_name'])) {
					$upload_data = array('upload_data' => $this->upload->data());
					if ($upload_data) {
						$dbstatus 	= true;
						$dbmessage 	= 'File Uploaded successfully';
						$file =  $upload_data['upload_data']['file_name'];
					} else {
						$dbstatus = false;
						$dbmessage = 'Error while uploading file';
					}
				} else {
					$dbstatus = false;
					$dbmessage = $this->upload->display_errors();
					$file =  '';
				}
			} else {
				$dbstatus = false;
				$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
				$file =  '';
			}
		} else {
			$dbstatus = false;
			$dbmessage = 'Please select only ' . $file_type . ' format.';
			$file =  '';
		}

		$insert_log_detail = array(
			'user_code'		=> $this->user_code,
			'ip_address'	=> $this->input->ip_address(),
			'role_code'		=> $this->role,
			'session_id'	=> $this->session->userdata('sess_id'),
			'doc_type'      => $configuration['allowed_file_types'],
			'created_by'	=> $this->user_code,

			'last_attempt'  => date('Y-m-d H:i:s', time()),
			'doc_status'	=> $dbstatus,
		);
		$this->db->insert('upload_doc_log', $insert_log_detail);
		return array('status' => $dbstatus, 'msg' => $dbmessage, 'filename' => $file);
	}

	protected function uploadMultipleFiles($files, $configuration)
	{
		for ($i = 0; $i < count($files['name']); $i++) {
			if (!empty($files['name'][$i])) {
				$_FILES['file']['name'] = $files['name'][$i];
				$_FILES['file']['type'] = $files['type'][$i];
				$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
				$_FILES['file']['error'] = $files['error'][$i];
				$_FILES['file']['size'] = $files['size'][$i];

				$date = date('Y-m-d H:i:s', time());
				$file_name = $_FILES['file']['name'];
				$file_move_path  = $configuration['file_move_path'];
				$allowed_mime_type_arr = $configuration['allowed_mime_types'];
				$mime = get_mime_by_extension($file_name);
				$dot_count 	= substr_count($file_name, '.');
				$zero_count = substr_count($file_name, "%0");

				if (in_array($mime, $allowed_mime_type_arr)) {
					if ($zero_count == 0 && $dot_count == 1) {

						if (!is_dir($file_move_path)) {
							mkdir($file_move_path, 0777, true);
						}

						$config['upload_path'] 		= $file_move_path;
						$config['file_name'] 		= $configuration['file_name'];
						$config['allowed_types'] 	= $configuration['allowed_file_types'];
						$config['max_size']         = FILE_MAX_SIZE;
						$config['overwrite'] 		= TRUE;

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if ($this->upload->do_upload('file')) {
							$upload_data = array('upload_data' => $this->upload->data());
							if ($upload_data) {

								$dbstatus 	= true;
								$dbmessage 	= 'File Uploaded successfully';
								$file =  $upload_data['upload_data']['file_name'];

								// $insert_log_detail = array(
								// 	'user_code'		=> $this->user_code,
								// 	'ip_address'	=> $this->input->ip_address(),
								// 	'role_code'		=> $this->role,
								// 	'session_id'	=> $this->session->userdata('sess_id'),
								// 	'doc_type'      =>$configuration['allowed_file_types'],
								// 	'created_by'	=> $this->user_code,

								// 	'last_attempt'  =>date('Y-m-d H:i:s', time()),
								// 	'doc_status'	=> $dbstatus,
								// );
								// $result = $this->db->insert('upload_doc_log', $insert_log_detail);

							} else {
								$dbstatus = false;
								$dbmessage = 'Error while uploading file';
							}
						} else {
							$dbstatus = false;
							$dbmessage = $this->upload->display_errors();
							$file =  '';
						}
					} else {
						$dbstatus = false;
						$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
						$file =  '';
					}
				} else {
					$dbstatus = false;
					$dbmessage = 'Please select only allowed format.';
					$file =  '';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage, 'filename' => $file);
			}
		}
	}

	// Function to count the cases based on case type
	public function get_cases_counts()
	{
		$general_cases_count = $this->db->select('*')->where([
			'status' => 1,
			'is_registered' => 1,
			'di_type_of_arbitration' => 'GENERAL'
		])->from('cs_case_details_tbl')->count_all_results();

		$emergency_cases_count = $this->db->select('*')->where([
			'status' => 1,
			'is_registered' => 1,
			'di_type_of_arbitration' => 'EMERGENCY'
		])->from('cs_case_details_tbl')->count_all_results();

		return [
			'general_cases_count' => $general_cases_count,
			'emergency_cases_count' => $emergency_cases_count,
		];
	}
}
