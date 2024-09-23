
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fees_model extends CI_Model
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
	}


	public function get($data, $op)
	{
		switch ($op) {

			case 'GET_FEE_COST_DATA':
				// Get the fee and cost details
				$fee_cost_data = $this->db->select("*")
					->from('cs_fee_details_tbl')
					->where('case_no', $data['case_no'])
					->where('status', 1)
					->get()
					->result_array();

				return $fee_cost_data;
				break;

			case 'GET_SINGLE_ASSESSMENT_FEE_DETAILS':
				$fee_cost_data = $this->db->select("*")
					->from('cs_fee_details_tbl')
					->where('code', $data['fee_code'])
					->where('status', 1)
					->get()
					->row_array();

				return $fee_cost_data;
				break;

			case 'CASE_FEE_RELEASED_LIST':

				$select_column = array("date_of_fee_released", "released_to", "mode_of_payment", "details_of_fee_released", "amount");
				$order_column = array("date_of_fee_released", "released_to", "mode_of_payment");


				$this->db->select("fr.nature_of_award, fr.id, fr.case_no, DATE_FORMAT(fr.date_of_fee_released, '%d-%m-%Y') date_of_fee_released,mat.name_of_arbitrator as arbitrator_name, fr.mode_of_payment, fr.details_of_fee_released, fr.amount, gc.description as mop_description, gc2.description as nature_of_award_desc, DATE_FORMAT(fr.date_of_fee_released_note, '%d-%m-%Y') date_of_fee_released_note,fr.released_to");
				$this->db->from('cs_at_fee_released_tbl as fr');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fr.mode_of_payment AND gc.gen_code_group = "MODE_OF_PAYMENT"', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fr.nature_of_award AND gc2.gen_code_group = "NATURE_OF_AWARD"', 'left');
				$this->db->join('cs_arbitral_tribunal_tbl as art', 'art.at_code = fr.released_to', 'left');
				$this->db->join('master_arbitrators_tbl as mat', 'mat.code = art.name_of_arbitrator', 'left');

				if (isset($_POST["search"]["value"])) {
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if ($count == 1) {
							$like_clause .= 'fr.' . $sc . " LIKE '%" . $search_value . "%'";
						} else {
							$like_clause .= " OR " . 'fr.' . $sc . " LIKE '%" . $search_value . "%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if (isset($_POST["order"])) {
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				} else {
					$this->db->order_by('fr.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fr.status', 1)->where('fr.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_at_fee_released_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_at_fee_released_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_FEE_DEPOSIT_LIST':

				$select_column = array("date_of_deposit", "deposited_by", "name_of_depositor", "deposited_towards", "mode_of_deposit", "details_of_deposit");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null);


				$this->db->select("fd.id, fd.case_no, DATE_FORMAT(fd.date_of_deposit, '%d-%m-%Y') date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, fd.diac_txn_id, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description, fd.check_bounce");
				$this->db->from('cs_fee_deposit_tbl as fd');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by', 'left');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit', 'left');

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
					$this->db->order_by('fd.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fd.status', 1)->where('fd.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_deposit_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_deposit_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;


			case 'CASE_COST_DEPOSIT_LIST':

				$select_column = array("date_of_deposit", "deposited_by", "name_of_depositor", "cost_imposed_dated", "mode_of_deposit", "details_of_deposit");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null);


				$this->db->select("cd.id, cd.case_no, DATE_FORMAT(cd.date_of_deposit, '%d-%m-%Y') date_of_deposit, cd.deposited_by, cd.name_of_depositor, cd.cost_imposed_dated, cd.cost_imposed, cd.mode_of_deposit, cd.details_of_deposit, cd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc2.description mod_description");
				$this->db->from('cs_cost_deposit_tbl as cd');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = cd.deposited_by');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cd.mode_of_deposit');

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
					$this->db->order_by('cd.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('cd.status', 1)->where('cd.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_cost_deposit_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_cost_deposit_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'CASE_FEE_REFUND_LIST':

				$select_column = array("date_of_refund", "refunded_to", "name_of_party", "refunded_towards", "mode_of_refund", "details_of_refund", "amount");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null, "amount");


				$this->db->select("fr.id, fr.case_no, DATE_FORMAT(fr.date_of_refund, '%d-%m-%Y') date_of_refund, fr.refunded_to, fr.name_of_party, fr.refunded_towards, fr.mode_of_refund, fr.details_of_refund, fr.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description mod_refund");
				$this->db->from('cs_fee_refund_tbl as fr');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fr.refunded_to');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fr.refunded_towards');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fr.mode_of_refund');

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
					$this->db->order_by('fr.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fr.status', 1)->where('fr.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_refund_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_refund_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			case 'GET_FEES_DEPOSITED_LIST':
				$this->db->select("fd.id, fd.case_no, fd.date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description, fd.check_bounce");
				$this->db->from('cs_fee_deposit_tbl as fd');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit');

				$this->db->where('fd.status', 1)->where('fd.case_no', $data['case_no']);
				$query = $this->db->get();
				return $query->result_array();
				break;

			case 'CASE_AMOUNT_DETAILS':

				// Get the latest assessment
				$this->db->select("*");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				// Order by id DESC to get the latest assessment
				$this->db->order_by('id', 'DESC');
				$query = $this->db->get();
				$latest_assessment = $query->row_array();

				// Get the claimant fees towards arbitrator share deposited fees.
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('deposited_towards', 'ARB_CS');
				$this->db->where('check_bounce !=', 'yes');
				$this->db->where('status', 1);
				$claimant_arb_fees = $this->db->get()->row_array();

				// Get the claimant fees towards administration share deposited fees.
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('deposited_towards', 'ADM_CS');
				$this->db->where('check_bounce !=', 'yes');
				$this->db->where('status', 1);
				$claimant_adm_fees = $this->db->get()->row_array();

				// Get the respondent fees towards arbitrator share deposited fees.
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('deposited_towards', 'ARB_RS');
				$this->db->where('check_bounce !=', 'yes');
				$this->db->where('status', 1);
				$respondent_arb_fees = $this->db->get()->row_array();

				// Get the respondent fees towards administration share deposited fees.
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('deposited_towards', 'ADM_RS');
				$this->db->where('check_bounce !=', 'yes');
				$this->db->where('status', 1);
				$respondent_adm_fees = $this->db->get()->row_array();

				return array(
					'latest_assessment' => $latest_assessment,
					'claimant_arb_fees' => $claimant_arb_fees,
					'claimant_adm_fees' => $claimant_adm_fees,
					'respondent_arb_fees' => $respondent_arb_fees,
					'respondent_adm_fees' => $respondent_adm_fees
				);

				// // Get total amount
				// $this->db->select("cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, (cs_arb_fees + cs_adminis_fees + rs_arb_fees + rs_adminis_fee) as total_amount");
				// $this->db->from('cs_fee_details_tbl');
				// $this->db->where('case_no', $data['case_no']);
				// $this->db->where('status', 1);
				// $query = $this->db->get();
				// $ta_data = $query->row_array();

				// // Get fee deposited
				// $this->db->select("SUM(amount) as fee_deposit");
				// $this->db->from('cs_fee_deposit_tbl');
				// $this->db->where('case_no', $data['case_no']);
				// $this->db->where('status', 1);
				// $query = $this->db->get();
				// $fd_data = $query->row_array();

				// $output['total_amount'] = (isset($ta_data['total_amount']) && !empty($ta_data['total_amount'])) ? $ta_data['total_amount'] : 0;
				// $output['deposit_amount'] = (isset($fd_data['fee_deposit']) && !empty($fd_data['fee_deposit'])) ? $fd_data['fee_deposit'] : 0;
				// $remaining_amount = $output['total_amount'] - $output['deposit_amount'];

				// $output['balance'] = 0;
				// $output['excess'] = 0;

				// if ($remaining_amount > 0) {
				// 	$output['balance'] = $remaining_amount;
				// }
				// if ($remaining_amount < 0) {
				// 	$output['excess'] = $remaining_amount;
				// }

				// // If we want data in array format only; return the result from here.
				// if (isset($data['case_amount_type']) && $data['case_amount_type'] == 'ARRAY_LIST') {
				// 	return $output;
				// }

				// // Output
				// $output = array(
				// 	"draw" => intval($_POST['draw']),
				// 	"recordsTotal" => 1,
				// 	"recordsFiltered" => 1,
				// 	"data" => [$output]
				// );
				// return $output;
				break;

			case 'GET_FEES_PRAYERS_OF_CASE':
				$result['claim_prayers'] = $this->db->select('*')
					->from('cs_assessment_prayers_tbl')
					->where('case_code', $data['case_no'])
					->where('type', 'CLAIM')
					->where('record_status', 1)
					->get()
					->result_array();

				$result['cc_claim_prayers'] = $this->db->select('*')
					->from('cs_assessment_prayers_tbl')
					->where('case_code', $data['case_no'])
					->where('type', 'COUNTER_CLAIM')
					->where('record_status', 1)
					->get()
					->result_array();

				return $result;
				break;

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function post($data, $op)
	{
		switch ($op) {

			case 'ADD_CASE_FEE_RELEASED':

				// Fee released details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fr_nature_of_award', 'Nature of award', 'required|xss_clean');
				$this->form_validation->set_rules('fr_dofr', 'Date of fee released', 'required|xss_clean');
				$this->form_validation->set_rules('fr_released_to', 'Released to', 'required|xss_clean');
				$this->form_validation->set_rules('fr_mode', 'Mode of payment', 'required|xss_clean');
				$this->form_validation->set_rules('fr_details', 'Details of fee released', 'required|xss_clean');
				$this->form_validation->set_rules('fr_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fr_dofr_note', 'Date of fee released note', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$amount = $this->security->xss_clean($data['fr_amount']);
						$case_no = $this->security->xss_clean($data['hidden_case_no']);

						$fr_data = array(
							'case_no' => $case_no,
							'nature_of_award' => $this->security->xss_clean($data['fr_nature_of_award']),
							'date_of_fee_released' => formatDate($this->security->xss_clean($data['fr_dofr'])),
							'released_to' => $this->security->xss_clean($data['fr_released_to']),
							'mode_of_payment' => $this->security->xss_clean($data['fr_mode']),
							'details_of_fee_released' => $this->security->xss_clean($data['fr_details']),
							'amount' => $amount,
							'date_of_fee_released_note' => formatDate($this->security->xss_clean($data['fr_dofr_note'])),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert case fee released details
						$result = $this->db->insert('cs_at_fee_released_tbl', $fr_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_at_fee_released_tbl';
							$message = 'A new fee released of' . $amount . ' of case ' . $case_det['case_no'] . ' is added.';
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

			case 'EDIT_CASE_FEE_RELEASED':

				// Fee Released details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fr_nature_of_award', 'Nature of award', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_fr_id', 'Fee released id', 'required|xss_clean');
				$this->form_validation->set_rules('fr_dofr', 'Date of fee released', 'required|xss_clean');
				$this->form_validation->set_rules('fr_released_to', 'Released to', 'required|xss_clean');
				$this->form_validation->set_rules('fr_mode', 'Mode of payment', 'required|xss_clean');
				$this->form_validation->set_rules('fr_details', 'Details of fee released', 'required|xss_clean');
				$this->form_validation->set_rules('fr_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$fr_id = $this->security->xss_clean($data['hidden_fr_id']);
						$amount = $this->security->xss_clean($data['fr_amount']);

						$fr_data = array(
							'nature_of_award' => $this->security->xss_clean($data['fr_nature_of_award']),
							'date_of_fee_released' => formatDate($this->security->xss_clean($data['fr_dofr'])),
							'released_to' => $this->security->xss_clean($data['fr_released_to']),
							'mode_of_payment' => $this->security->xss_clean($data['fr_mode']),
							'details_of_fee_released' => $this->security->xss_clean($data['fr_details']),
							'amount' => $amount,
							'date_of_fee_released_note' => formatDate($this->security->xss_clean($data['fr_dofr_note'])),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update case fee released details
						$result = $this->db->where('id', $fr_id)->where('case_no', $case_no)->update('cs_at_fee_released_tbl', $fr_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_at_fee_released_tbl';
							$table_id = $fr_id;
							$message = 'Details of fee released of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is updated.';
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

			case 'DELETE_CASE_FEE_RELEASED':

				$this->form_validation->set_rules('id', 'Fee Released Id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_at_fee_released_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_at_fee_released_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_at_fee_released_tbl';
						$table_id = $id;
						$message = 'Fee released of amount ' . $data['amount'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
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

			case 'ADD_CASE_FEE_DEPOSIT':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_deposited_by', 'Deposited by', 'required|xss_clean');
				$this->form_validation->set_rules('fd_name_of_depositor', 'Name of depositor', 'required|xss_clean');
				$this->form_validation->set_rules('fd_deposited_towards', 'Deposited towards', 'required|xss_clean');
				$this->form_validation->set_rules('fd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_check_bounce', 'Details of deposit', 'xss_clean');
				$this->form_validation->set_rules('fd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$amount = $this->security->xss_clean($data['fd_amount']);

						$fd_data = array(
							'case_no' => $case_no,
							'date_of_deposit' => formatDate($this->security->xss_clean($data['fd_date_of_deposit'])),
							'deposited_by' => $this->security->xss_clean($data['fd_deposited_by']),
							'name_of_depositor' => $this->security->xss_clean($data['fd_name_of_depositor']),
							'deposited_towards' => $this->security->xss_clean($data['fd_deposited_towards']),
							'mode_of_deposit' => $this->security->xss_clean($data['fd_mode_of_deposit']),
							'diac_txn_id' => $this->security->xss_clean($data['fd_diac_txn_id']),
							'details_of_deposit' => $this->security->xss_clean($data['fd_details_of_deposit']),
							'check_bounce' => $this->security->xss_clean($data['fd_check_bounce']),
							'amount' => $amount,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert case fee deposit
						$result = $this->db->insert('cs_fee_deposit_tbl', $fd_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_fee_deposit_tbl';
							$message = 'Fee deposit of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is added.';
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

			case 'EDIT_CASE_FEE_DEPOSIT':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_deposited_by', 'Deposited by', 'required|xss_clean');
				$this->form_validation->set_rules('fd_name_of_depositor', 'Name of depositor', 'required|xss_clean');
				$this->form_validation->set_rules('fd_deposited_towards', 'Deposited towards', 'required|xss_clean');
				$this->form_validation->set_rules('fd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('fd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fd_check_bounce', 'Details of deposit', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$fd_id = $this->security->xss_clean($data['hidden_fd_id']);
						$amount = $this->security->xss_clean($data['fd_amount']);

						$fd_data = array(
							'date_of_deposit' => formatDate($this->security->xss_clean($data['fd_date_of_deposit'])),
							'deposited_by' => $this->security->xss_clean($data['fd_deposited_by']),
							'name_of_depositor' => $this->security->xss_clean($data['fd_name_of_depositor']),
							'deposited_towards' => $this->security->xss_clean($data['fd_deposited_towards']),
							'mode_of_deposit' => $this->security->xss_clean($data['fd_mode_of_deposit']),
							'diac_txn_id' => $this->security->xss_clean($data['fd_diac_txn_id']),
							'details_of_deposit' => $this->security->xss_clean($data['fd_details_of_deposit']),
							'check_bounce' => $this->security->xss_clean($data['fd_check_bounce']),
							'amount' => $amount,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update case counsel details
						$result = $this->db->where('id', $fd_id)->where('case_no', $case_no)->update('cs_fee_deposit_tbl', $fd_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_fee_deposit_tbl';
							$table_id = $fd_id;
							$message = 'Details of fee deposit of ' . $amount . ' of case ' . $case_det['case_no'] . ' is updated.';
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

			case 'DELETE_CASE_FEE_DEPOSIT':

				$this->form_validation->set_rules('id', 'Fee deposit id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_fee_deposit_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_fee_deposit_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_fee_deposit_tbl';
						$table_id = $id;
						$message = 'Fee Deposit of amount ' . $data['amount'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
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


			case 'ADD_CASE_COST_DEPOSIT':

				// Cost Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('cd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_deposited_by', 'Deposited by', 'required|xss_clean');
				$this->form_validation->set_rules('cd_name_of_depositor', 'Name of depositor', 'required|xss_clean');
				$this->form_validation->set_rules('cd_cost_imposed_dated', 'Cost imposed vide order dated', 'xss_clean');
				$this->form_validation->set_rules('cd_cost_imposed', 'Cost imposed', 'xss_clean');
				$this->form_validation->set_rules('cd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$amount = $this->security->xss_clean($data['cd_amount']);

						$cd_data = array(
							'case_no' => $case_no,
							'date_of_deposit' => formatDate($this->security->xss_clean($data['cd_date_of_deposit'])),
							'deposited_by' => $this->security->xss_clean($data['cd_deposited_by']),
							'name_of_depositor' => $this->security->xss_clean($data['cd_name_of_depositor']),
							'cost_imposed_dated' => formatDate($this->security->xss_clean($data['cd_cost_imposed_dated'])),
							'cost_imposed' => $this->security->xss_clean($data['cd_cost_imposed']),
							'mode_of_deposit' => $this->security->xss_clean($data['cd_mode_of_deposit']),
							'details_of_deposit' => $this->security->xss_clean($data['cd_details_of_deposit']),
							'amount' => $amount,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert case cost deposit
						$result = $this->db->insert('cs_cost_deposit_tbl', $cd_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_cost_deposit_tbl';
							$message = 'Cost deposit of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is added.';
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

			case 'EDIT_CASE_COST_DEPOSIT':

				// Cost Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('cd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_deposited_by', 'Deposited by', 'required|xss_clean');
				$this->form_validation->set_rules('cd_name_of_depositor', 'Name of depositor', 'required|xss_clean');
				$this->form_validation->set_rules('cd_cost_imposed_dated', 'Cost imposed vide order dated', 'xss_clean');
				$this->form_validation->set_rules('cd_cost_imposed', 'Cost imposed', 'xss_clean');
				$this->form_validation->set_rules('cd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
				$this->form_validation->set_rules('cd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$cd_id = $this->security->xss_clean($data['hidden_cd_id']);
						$amount = $this->security->xss_clean($data['cd_amount']);

						$cd_data = array(
							'date_of_deposit' => formatDate($this->security->xss_clean($data['cd_date_of_deposit'])),
							'deposited_by' => $this->security->xss_clean($data['cd_deposited_by']),
							'name_of_depositor' => $this->security->xss_clean($data['cd_name_of_depositor']),
							'cost_imposed_dated' => formatDate($this->security->xss_clean($data['cd_cost_imposed_dated'])),
							'cost_imposed' => $this->security->xss_clean($data['cd_cost_imposed']),
							'mode_of_deposit' => $this->security->xss_clean($data['cd_mode_of_deposit']),
							'details_of_deposit' => $this->security->xss_clean($data['cd_details_of_deposit']),
							'amount' => $amount,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update case cost deposit details
						$result = $this->db->where('id', $cd_id)->where('case_no', $case_no)->update('cs_cost_deposit_tbl', $cd_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_cost_deposit_tbl';
							$table_id = $cd_id;
							$message = 'Cost deposit of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is updated.';
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

			case 'DELETE_CASE_COST_DEPOSIT':

				$this->form_validation->set_rules('id', 'Cost deposit id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_cost_deposit_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_cost_deposit_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_cost_deposit_tbl';
						$table_id = $id;
						$message = 'Cost deposit of amount ' . $data['amount'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
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


			case 'ADD_CASE_FEE_REFUND':

				// Fee Refund
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_date_of_refund', 'Date of refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_refunded_to', 'Refunded to', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_name_of_party', 'Name of party', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_refunded_towards', 'Refunded towards', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_mode_of_refund', 'Mode of Refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_details_of_refund', 'Details of refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$amount = $this->security->xss_clean($data['fee_ref_amount']);

						$fee_ref_data = array(
							'case_no' => $case_no,
							'date_of_refund' => formatDate($this->security->xss_clean($data['fee_ref_date_of_refund'])),
							'refunded_to' => $this->security->xss_clean($data['fee_ref_refunded_to']),
							'name_of_party' => $this->security->xss_clean($data['fee_ref_name_of_party']),
							'refunded_towards' => $this->security->xss_clean($data['fee_ref_refunded_towards']),
							'mode_of_refund' => $this->security->xss_clean($data['fee_ref_mode_of_refund']),
							'details_of_refund' => $this->security->xss_clean($data['fee_ref_details_of_refund']),
							'amount' => $amount,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert case fee deposit
						$result = $this->db->insert('cs_fee_refund_tbl', $fee_ref_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_fee_refund_tbl';
							$message = 'Fee refund of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is added.';
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

			case 'EDIT_CASE_FEE_REFUND':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_date_of_refund', 'Date of refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_refunded_to', 'Refunded to', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_name_of_party', 'Name of party', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_refunded_towards', 'Refunded towards', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_mode_of_refund', 'Mode of Refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_details_of_refund', 'Details of refund', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$fee_ref_id = $this->security->xss_clean($data['hidden_fee_ref_id']);
						$amount = $this->security->xss_clean($data['fee_ref_amount']);

						$fee_ref_data = array(
							'date_of_refund' => formatDate($this->security->xss_clean($data['fee_ref_date_of_refund'])),
							'refunded_to' => $this->security->xss_clean($data['fee_ref_refunded_to']),
							'name_of_party' => $this->security->xss_clean($data['fee_ref_name_of_party']),
							'refunded_towards' => $this->security->xss_clean($data['fee_ref_refunded_towards']),
							'mode_of_refund' => $this->security->xss_clean($data['fee_ref_mode_of_refund']),
							'details_of_refund' => $this->security->xss_clean($data['fee_ref_details_of_refund']),
							'amount' => $amount,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update case fee refund
						$result = $this->db->where('id', $fee_ref_id)->where('case_no', $case_no)->update('cs_fee_refund_tbl', $fee_ref_data);

						if ($result) {

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_fee_refund_tbl';
							$table_id = $fee_ref_id;
							$message = 'Fee refund of amount ' . $amount . ' of case ' . $case_det['case_no'] . ' is updated.';
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

			case 'DELETE_CASE_FEE_REFUND':

				$this->form_validation->set_rules('id', 'Fee refund id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_fee_refund_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_fee_refund_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_fee_refund_tbl';
						$table_id = $id;
						$message = 'Fee refund of amount ' . $data['amount'] . ' of case ' . $case_det['case_no'] . ' is deleted.';
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


			case 'ADD_CASE_FEE_COST_DETAILS':

				// Fee and cost details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');

				$this->form_validation->set_rules('type_of_arbitration', 'Type of arbitration', 'required|xss_clean');
				$this->form_validation->set_rules('arbitral_tribunal_strength', 'Arbitral Tribunal Strength', 'required|xss_clean');

				$this->form_validation->set_rules('fc_c_cc_assessed_sep', 'Whether Claims or Counter Claims assessed separately', 'xss_clean');

				if ($this->input->post('fc_c_cc_assessed_sep') && $this->input->post('fc_c_cc_assessed_sep') == 'no') {
					$this->form_validation->set_rules('fc_sum_despute', 'Sum in Dispute', 'required|xss_clean|numeric');
					$this->form_validation->set_rules('claimant_prayers', 'Claimant Prayers', 'required|xss_clean');
				}

				if ($this->input->post('fc_c_cc_assessed_sep') && $this->input->post('fc_c_cc_assessed_sep') == 'yes') {

					$this->form_validation->set_rules('fc_sum_despute', 'Sum in Dispute', 'required|xss_clean|numeric');
					$this->form_validation->set_rules('fc_sum_despute_cc', 'Sum in Dispute (Counter Claims)', 'required|xss_clean|numeric');

					$this->form_validation->set_rules('claimant_prayers', 'Claimant Prayers', 'required|xss_clean');
					$this->form_validation->set_rules('respondent_prayers', 'Respondent Prayers', 'required|xss_clean');

					// $this->form_validation->set_rules('fc_sum_despute_claim', 'Sum in Dispute (Claims)', 'required|xss_clean|numeric');
				}

				$this->form_validation->set_rules('fc_pro_assesment', 'As per provisional assessment dated', 'xss_clean');

				$this->form_validation->set_rules('fc_basic_arb_fees', 'Basic Arbitrator Fees', 'required|xss_clean');
				$this->form_validation->set_rules('fc_actual_excess_amount', 'Excess Amount', 'required|xss_clean');

				$this->form_validation->set_rules('fc_excess_percentage', 'Excess Amount Percentage', 'required|xss_clean');
				$this->form_validation->set_rules('fc_excess_amount', 'Excess Amount Percent', 'required|xss_clean');

				if ($this->input->post('arbitral_tribunal_strength') == 1) {
					$this->form_validation->set_rules('fc_additional_arb_percentage', 'Additional Arbitrator Percentage', 'required|xss_clean');
					$this->form_validation->set_rules('fc_additional_arb_amount', 'Additional Arbitrator Amount', 'required|xss_clean');
				}

				if ($this->input->post('type_of_arbitration') && $this->input->post('type_of_arbitration') == 'DOMESTIC') {
					$this->form_validation->set_rules('fc_total_arb_fees', 'Total Arbitrators Fees', 'required|xss_clean|numeric');

					$this->form_validation->set_rules('fc_cs_arb_fees', 'Arbitrators Fees', 'required|xss_clean|numeric');

					$this->form_validation->set_rules('fc_cs_adm_fees', 'Administrative Expenses ', 'required|xss_clean|numeric');

					$this->form_validation->set_rules('fc_rs_arb_fees', 'Arbitrators Fees', 'required|xss_clean|numeric');

					$this->form_validation->set_rules('fc_rs_adm_fees', 'Administrative Expenses', 'required|xss_clean|numeric');
				}

				if ($this->input->post('type_of_arbitration') && $this->input->post('type_of_arbitration') == 'INTERNATIONAL') {
					$this->form_validation->set_rules('current_dollar_price', 'Current dollar price', 'required|xss_clean');
				}


				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);

						$fee_cost_data = array(
							'case_no' => $case_no,
							'arbitral_tribunal_strength' => $this->input->post('arbitral_tribunal_strength'),
							'c_cc_asses_sep' => $this->input->post('fc_c_cc_assessed_sep'),
							'sum_in_dispute' => '',
							// 'sum_in_dispute_claim' => '',
							'sum_in_dispute_cc' => '',
							// 'asses_date' => formatDate($this->security->xss_clean($data['fc_pro_assesment'])),
							'asses_date' => currentDate(),
							'total_arb_fees' => '',
							'cs_arb_fees' => '',
							'cs_adminis_fees' => '',
							'rs_arb_fees' => '',
							'rs_adminis_fee' => '',
							// 'assessment_sheet_doc' => '',
							'assessment_approved' => 1,
							'current_dollar_price' => '',
							'int_arb_total_fees_dollar' => '',
							'int_arb_total_fees_rupee' => '',
							'int_total_adm_charges_dollar' => '',
							'int_total_adm_charges_rupee' => '',
							'int_arb_claim_share_fees_dollar' => '',
							'int_arb_claim_share_fees_rupee' => '',
							'int_claim_adm_charges_dollar' => '',
							'int_claim_adm_charges_rupee' => '',
							'int_arb_res_share_fees_dollar' => '',
							'int_arb_res_share_fees_rupee' => '',
							'int_res_adm_charges_dollar' => '',
							'int_res_adm_charges_rupee' => '',
							'remarks' => $this->input->post('remarks'),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// =================================================================
						// Basic Amount
						$fee_cost_data['basic_amount'] = $this->input->post('fc_basic_amount');

						// Basic Arbitrator Fees
						$fee_cost_data['basic_arb_fees'] = $this->input->post('fc_basic_arb_fees');

						// Excess Amount Details
						$fee_cost_data['actual_excess_amount'] = $this->input->post('fc_actual_excess_amount');
						$fee_cost_data['excess_percentage'] = $this->input->post('fc_excess_percentage');
						$fee_cost_data['excess_amount'] = $this->input->post('fc_excess_amount');

						// Additional Arbitrator’s Fees (If arbitral tribunal strength is 1)
						if ($this->input->post('arbitral_tribunal_strength') == 1) {
							$fee_cost_data['additional_arb_percentage'] = $this->input->post('fc_additional_arb_percentage');
							$fee_cost_data['additional_arb_amount'] = $this->input->post('fc_additional_arb_amount');
						} else {
							$fee_cost_data['additional_arb_percentage'] = 0;
							$fee_cost_data['additional_arb_amount'] = 0;
						}

						// =================================================================
						// If assessed seperately then add counter claim details calculations
						if ($data['fc_c_cc_assessed_sep'] == 'yes') {
							// Basic Amount
							$fee_cost_data['cc_basic_amount'] = $this->input->post('fc_cc_basic_amount');

							// Basic Arbitrator Fees
							$fee_cost_data['cc_basic_arb_fees'] = $this->input->post('fc_cc_basic_arb_fees');

							// Excess Amount Details
							$fee_cost_data['cc_actual_excess_amount'] = $this->input->post('fc_cc_actual_excess_amount');
							$fee_cost_data['cc_excess_percentage'] = $this->input->post('fc_cc_excess_percentage');
							$fee_cost_data['cc_excess_amount'] = $this->input->post('fc_cc_excess_amount');

							// Additional Arbitrator’s Fees (If arbitral tribunal strength is 1)
							if ($this->input->post('arbitral_tribunal_strength') == 1) {
								$fee_cost_data['cc_additional_arb_percentage'] = $this->input->post('fc_cc_additional_arb_percentage');
								$fee_cost_data['cc_additional_arb_amount'] = $this->input->post('fc_cc_additional_arb_amount');
							} else {
								$fee_cost_data['cc_additional_arb_percentage'] = 0;
								$fee_cost_data['cc_additional_arb_amount'] = 0;
							}

							$fee_cost_data['cc_total_arb_fees'] = $this->security->xss_clean($data['fc_cc_total_arb_fees']);
						}

						if (!$this->input->post('hidden_assessment_code')) {
							$assessment_code = generateCode();
							$fee_cost_data['code'] = $assessment_code;
						} else {
							// $assessment_code = $this->input->post('hidden_assessment_code');
							$assessment_code = customURIDecode($this->input->post('hidden_assessment_code'));
						}

						if ($this->input->post('assessment_sheet_data')) {
							$fee_cost_data['assessment_sheet_data'] = $this->input->post('assessment_sheet_data');
						}

						// ====================================================
						// DELETE: Delete the old prayers, so that they do not conflick with new records =============
						$cp_update_result  = $this->db->where([
							'assessment_code' => $assessment_code,
						])->update('cs_assessment_prayers_tbl', [
							'record_status' => 0,
						]);

						if (!$cp_update_result) {
							return array('status' => false, 'msg' => 'Server Error while saving prayers.');
						}

						// If fees is not assessed seprately or assessed seperately (Claim is required) ======================
						if ($data['fc_c_cc_assessed_sep'] == 'no' || $data['fc_c_cc_assessed_sep'] == 'yes') {
							$fee_cost_data['sum_in_dispute'] = $this->security->xss_clean($data['fc_sum_despute']);

							// Insert new claimant prayers ==================
							// Get and store claimant prayers data
							$claimant_prayers = json_decode($data['claimant_prayers'], true);
							if (count($claimant_prayers) > 0) {

								// Get the claimant prayers data
								$claimant_prayers_data = [];
								foreach ($claimant_prayers as $cp) {

									// Applicable
									if ($cp['applicable'] == 1 || $cp['applicable'] == 2) {
										$applicable = $cp['applicable'];
									} else {
										$applicable = null;
									}

									// Is Quantified
									if ($cp['is_quantified'] == 1 || $cp['is_quantified'] == 2) {
										$is_quantified = $cp['is_quantified'];
									} else {
										$is_quantified = null;
									}

									// Amount
									if ($cp['is_quantified'] == 1) {
										$amount = $cp['amount'];
									} else {
										$amount = null;
									}

									array_push($claimant_prayers_data, [
										'code' => generateCode(),
										'type' => $cp['type'],
										'assessment_code' => $assessment_code,
										'case_code' => $case_no,
										'prayer_name' => $cp['name'],
										'applicable' => $applicable,
										'is_quantified' => $is_quantified,
										'prayer_amount' => $cp['amount'],
										'record_status' => 1,
										'created_by' => $this->user_code,
										'created_at' => $this->date,
										'updated_by' => $this->user_code,
										'updated_at' => $this->date
									]);
								}

								if (count($claimant_prayers_data) > 0) {
									$cp_result = $this->db->insert_batch('cs_assessment_prayers_tbl', $claimant_prayers_data);

									if (!$cp_result) {
										return array('status' => false, 'msg' => 'Error while saving claimant prayers.');
									}
								}
							}
						}

						// If fees is assessed seprately (Counter claim is required) ======================
						if ($data['fc_c_cc_assessed_sep'] == 'yes') {
							$fee_cost_data['sum_in_dispute_cc'] = $this->security->xss_clean($data['fc_sum_despute_cc']);

							// Get the prayers and store it
							$respondent_prayers = json_decode($data['respondent_prayers'], true);

							if (count($respondent_prayers) > 0) {

								// Get and store respondent prayers data
								$respondent_prayers_data = [];
								foreach ($respondent_prayers as $cp) {

									// Applicable
									if ($cp['applicable'] == 1 || $cp['applicable'] == 2) {
										$applicable = $cp['applicable'];
									} else {
										$applicable = null;
									}

									// Is Quantified
									if ($cp['is_quantified'] == 1 || $cp['is_quantified'] == 2) {
										$is_quantified = $cp['is_quantified'];
									} else {
										$is_quantified = null;
									}

									// Amount
									if ($cp['is_quantified'] == 1) {
										$amount = $cp['amount'];
									} else {
										$amount = null;
									}

									array_push($respondent_prayers_data, [
										'code' => generateCode(),
										'type' => $cp['type'],
										'assessment_code' => $assessment_code,
										'case_code' => $case_no,
										'prayer_name' => $cp['name'],
										'applicable' => $applicable,
										'is_quantified' => $is_quantified,
										'prayer_amount' => $cp['amount'],
										'created_by' => $this->user_code,
										'created_at' => $this->date,
										'updated_by' => $this->user_code,
										'updated_at' => $this->date
									]);
								}

								if (count($respondent_prayers_data) > 0) {
									$cp_result = $this->db->insert_batch('cs_assessment_prayers_tbl', $respondent_prayers_data);

									if (!$cp_result) {
										return array('status' => false, 'msg' => 'Error while saving claimant prayers.');
									}
								}
							}
						}

						// ==========================================================
						// WHEN: Type of arbitration is domestic
						if ($this->input->post('type_of_arbitration') == 'DOMESTIC') {
							$fee_cost_data['total_arb_fees'] = $this->security->xss_clean($data['fc_total_arb_fees']);
							$fee_cost_data['cs_arb_fees'] = $this->security->xss_clean($data['fc_cs_arb_fees']);
							$fee_cost_data['cs_adminis_fees'] = $this->security->xss_clean($data['fc_cs_adm_fees']);
							$fee_cost_data['rs_arb_fees'] = $this->security->xss_clean($data['fc_rs_arb_fees']);
							$fee_cost_data['rs_adminis_fee'] = $this->security->xss_clean($data['fc_rs_adm_fees']);
						}

						// WHEN: Type of arbitration is international
						if ($this->input->post('type_of_arbitration') == 'INTERNATIONAL') {

							// =================================================================
							// Basic Amount
							$fee_cost_data['adm_basic_amount'] = $this->input->post('fc_adm_basic_amount');

							// Basic Arbitrator Fees
							$fee_cost_data['adm_basic_adm_fees'] = $this->input->post('fc_adm_basic_adm_fees');

							// Excess Amount Details
							$fee_cost_data['adm_actual_excess_amount'] = $this->input->post('fc_adm_actual_excess_amount');
							$fee_cost_data['adm_excess_percentage'] = $this->input->post('fc_adm_excess_percentage');
							$fee_cost_data['adm_excess_amount'] = $this->input->post('fc_adm_excess_amount');

							// If assessed seperately then add the respondents counter claim administrations charges and also arbitrator fees
							if ($data['fc_c_cc_assessed_sep'] == 'yes') {
								// =================================================================
								// Basic Amount
								$fee_cost_data['cc_adm_basic_amount'] = $this->input->post('fc_cc_adm_basic_amount');

								// Basic Arbitrator Fees
								$fee_cost_data['cc_adm_basic_adm_fees'] = $this->input->post('fc_cc_adm_basic_adm_fees');

								// Excess Amount Details
								$fee_cost_data['cc_adm_actual_excess_amount'] = $this->input->post('fc_cc_adm_actual_excess_amount');
								$fee_cost_data['cc_adm_excess_percentage'] = $this->input->post('fc_cc_adm_excess_percentage');
								$fee_cost_data['cc_adm_excess_amount'] = $this->input->post('fc_cc_adm_excess_amount');
							}

							// ==================================================================

							$fee_cost_data['current_dollar_price'] = $this->security->xss_clean($data['current_dollar_price']);

							// Claimant (Claim) Total Arbitrator and administration charges
							$fee_cost_data['int_arb_total_fees_dollar'] = $this->security->xss_clean($data['int_arb_total_fees_dollar']);
							$fee_cost_data['int_arb_total_fees_rupee'] = $this->security->xss_clean($data['int_arb_total_fees_rupee']);
							$fee_cost_data['int_total_adm_charges_dollar'] = $this->security->xss_clean($data['int_total_adm_charges_dollar']);
							$fee_cost_data['int_total_adm_charges_rupee'] = $this->security->xss_clean($data['int_total_adm_charges_rupee']);

							// Claimant (Claim) Arbitrator and administration charges
							$fee_cost_data['int_arb_claim_share_fees_dollar'] = $this->security->xss_clean($data['int_arb_claim_share_fees_dollar']);
							$fee_cost_data['int_arb_claim_share_fees_rupee'] = $this->security->xss_clean($data['int_arb_claim_share_fees_rupee']);

							$fee_cost_data['int_claim_adm_charges_dollar'] = $this->security->xss_clean($data['int_claim_adm_charges_dollar']);
							$fee_cost_data['int_claim_adm_charges_rupee'] = $this->security->xss_clean($data['int_claim_adm_charges_rupee']);

							// Respondent (Counter Claim) Total Arbitrator and administration charges
							if ($data['fc_c_cc_assessed_sep'] == 'yes') {
								$fee_cost_data['cc_int_arb_total_fees_dollar'] = $this->security->xss_clean($data['cc_int_arb_total_fees_dollar']);
								$fee_cost_data['cc_int_arb_total_fees_rupee'] = $this->security->xss_clean($data['cc_int_arb_total_fees_rupee']);
								$fee_cost_data['cc_int_total_adm_charges_dollar'] = $this->security->xss_clean($data['cc_int_total_adm_charges_dollar']);
								$fee_cost_data['cc_int_total_adm_charges_rupee'] = $this->security->xss_clean($data['cc_int_total_adm_charges_rupee']);
							}

							// Respondent (Counter Claim) Arbitrator and administration charges
							$fee_cost_data['int_arb_res_share_fees_dollar'] = $this->security->xss_clean($data['int_arb_res_share_fees_dollar']);
							$fee_cost_data['int_arb_res_share_fees_rupee'] = $this->security->xss_clean($data['int_arb_res_share_fees_rupee']);

							$fee_cost_data['int_res_adm_charges_dollar'] = $this->security->xss_clean($data['int_res_adm_charges_dollar']);
							$fee_cost_data['int_res_adm_charges_rupee'] = $this->security->xss_clean($data['int_res_adm_charges_rupee']);
						}

						// =================================================================
						// Upload files of assessment sheet ==============
						$fee_cost_data['assessment_sheet_doc'] = '';
						// if ($_FILES['assessment_pdf_file']['name'] != '') {

						// 	$this->load->library('fileupload');
						// 	$award_file_result = $this->fileupload->uploadSingleFile($_FILES['assessment_pdf_file'], [
						// 		'raw_file_name' => 'assessment_pdf_file',
						// 		'file_name' => 'FEE_FILES_' . time(),
						// 		'file_move_path' => FEE_FILE_UPLOADS_FOLDER,
						// 		'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
						// 		'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
						// 	]);

						// 	// After getting result of file upload
						// 	if ($award_file_result['status'] == false) {
						// 		$this->db->trans_rollback();
						// 		return $award_file_result;
						// 	} else {
						// 		$fee_cost_data['assessment_sheet_doc'] = $award_file_result['file'];
						// 	}
						// }

						// Upload if any supporting document is available ==============
						$fee_cost_data['assessment_supporting_doc'] = '';
						if ($_FILES['fc_assesment_supporting_doc']['name'] != '') {

							$this->load->library('fileupload');
							$award_file_result = $this->fileupload->uploadSingleFile($_FILES['fc_assesment_supporting_doc'], [
								'raw_file_name' => 'fc_assesment_supporting_doc',
								'file_name' => 'CS_ASD_' . time(),
								'file_move_path' => ASSESSMENT_SUPPORTING_DOC_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($award_file_result['status'] == false) {
								$this->db->trans_rollback();
								return $award_file_result;
							} else {
								$fee_cost_data['assessment_supporting_doc'] = $award_file_result['file'];
							}
						}
						// else {
						// 	 $fee_cost_data['assessment_sheet_doc'] = $this->input->post('hidden_fc_assesment_sheet_file_name');
						// }

						if ($this->input->post('hidden_assessment_code')) {
							$message = 'Assessment updated successfully';
							$result = $this->db->where('code', $assessment_code)->update('cs_fee_details_tbl', $fee_cost_data);
						} else {
							// Insert case fee and cost details
							$message = 'Assessment saved successfully';
							$result = $this->db->insert('cs_fee_details_tbl', $fee_cost_data);
						}

						if ($result) {
							$this->db->trans_commit();
							$dbstatus = true;
							$dbmessage = $message;
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


			case 'EDIT_CASE_FEE_COST_DETAILS':

				// Fee and cost details
				$this->form_validation->set_rules('hidden_id', 'ID', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fc_c_cc_assessed_sep', 'Whether Claims or Counter Claims assessed separately', 'xss_clean');
				$this->form_validation->set_rules('fc_sum_despute', 'Sum in Dispute', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_sum_despute_cc', 'Sum in Dispute (Counter Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_sum_despute_claim', 'Sum in Dispute (Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_pro_assesment', 'As per provisional assessment dated', 'xss_clean');
				$this->form_validation->set_rules('fc_total_arb_fees', 'Total Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				$this->form_validation->set_rules('fc_cs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_cs_adm_fees', 'Administrative Expenses ', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_rs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
				$this->form_validation->set_rules('fc_rs_adm_fees', 'Administrative Expenses', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_no = $this->security->xss_clean($data['hidden_case_no']);
						$fee_cost_data = array(
							'case_no' => $case_no,
							'c_cc_asses_sep' => $this->security->xss_clean($data['fc_c_cc_assessed_sep']),
							'sum_in_dispute' => '',
							'sum_in_dispute_claim' => '',
							'sum_in_dispute_cc' => '',
							// 'asses_date' => formatDate($this->security->xss_clean($data['fc_pro_assesment'])),
							'total_arb_fees' => $this->security->xss_clean($data['fc_total_arb_fees']),
							'cs_arb_fees' => $this->security->xss_clean($data['fc_cs_arb_fees']),
							'cs_adminis_fees' => $this->security->xss_clean($data['fc_cs_adm_fees']),
							'rs_arb_fees' => $this->security->xss_clean($data['fc_rs_arb_fees']),
							'rs_adminis_fee' => $this->security->xss_clean($data['fc_rs_adm_fees']),
							// 'assessment_approved' => $this->security->xss_clean($data['fc_assesment_approved']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						if ($data['fc_c_cc_assessed_sep'] == 'yes') {
							$fee_cost_data['sum_in_dispute_claim'] = $this->security->xss_clean($data['fc_sum_despute_claim']);
							$fee_cost_data['sum_in_dispute_cc'] = $this->security->xss_clean($data['fc_sum_despute_cc']);
						}

						if ($data['fc_c_cc_assessed_sep'] == 'no') {
							$fee_cost_data['sum_in_dispute'] = $this->security->xss_clean($data['fc_sum_despute']);
						}


						// Upload files of assessment sheet ==============
						$fee_cost_data['assessment_sheet_doc'] = '';
						if ($_FILES['fc_assesment_sheet']['name'] != '') {

							$this->load->library('fileupload');
							$award_file_result = $this->fileupload->uploadSingleFile($_FILES['fc_assesment_sheet'], [
								'raw_file_name' => 'fc_assesment_sheet',
								'file_name' => 'FEE_FILES_' . time(),
								'file_move_path' => FEE_FILE_UPLOADS_FOLDER,
								'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
								'allowed_mime_types' => array('application/pdf', 'image/jpeg', 'image/jpeg')
							]);

							// After getting result of file upload
							if ($award_file_result['status'] == false) {
								$this->db->trans_rollback();
								return $award_file_result;
							} else {
								$fee_cost_data['assessment_sheet_doc'] = $award_file_result['file'];
							}
						}

						// Insert case fee and cost details
						$result = $this->db->where([
							'id' => $this->input->post('hidden_id')
						])->update('cs_fee_details_tbl', $fee_cost_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
							$case_det = $this->common_model->get_case_details_from_slug($case_no);
							$table_name = 'cs_fee_details_tbl';
							$message = 'Fee details of case ' . $case_det['case_no'] . ' is updated.';
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

			case 'DELETE_CASE_FEE_COST_DETAILS':

				$this->form_validation->set_rules('fee_id', 'Fee id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['fee_id']);

					$r = $this->db->where('id', $id)->update('cs_fee_details_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, case_no')->from('cs_fee_details_tbl')->where('id', $id)->get()->row_array();

						$case_det = $this->common_model->get_case_details_from_slug($data['case_no']);
						$table_name = 'cs_fee_refund_tbl';
						$table_id = $id;
						$message = 'Fees Assessment of case ' . $case_det['case_no'] . ' is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = true;
						$dbmessage = 'Record deleted successfully';
					} else {
						$dbstatus = false;
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

	public function insert_case_fees_deposit($data)
	{
		$this->db->trans_begin();
		try {

			// Insert case fee deposit
			$result = $this->db->insert('cs_fee_deposit_tbl', $data);

			if ($result) {
				$this->db->trans_commit();
				return true;
			} else {
				$this->db->trans_rollback();
				return false;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	// calculate case fees deficiency
	public function calculate_case_fee_deficiency($case_no = '')
	{
		$where = '';
		$cdt_where = '';
		if ($case_no) {
			$where = " WHERE case_no='" . $case_no . "'";
			$cdt_where = " WHERE cdt.slug='" . $case_no . "'";
		}

		$sql = "SELECT a.case_no, cdt.case_no AS case_no_desc, cdt.case_title, current_dollar_price,cs_arb_cs_tot,cs_adm_tot,rs_arb_tot,rs_adm_tot,ADM_CS,ADM_RS,ARB_CS,ARB_RS,
		IF((cs_arb_cs_tot-ARB_CS)>0,cs_arb_cs_tot-ARB_CS,0) AS arb_cs_remaining,IF((cs_adm_tot-ADM_CS)>0,cs_adm_tot-ADM_CS,0) AS adm_cs_remaining,
		IF((rs_arb_tot-ARB_RS)>0,rs_arb_tot-ARB_RS,0) AS arb_rs_remaining,IF((rs_adm_tot-ADM_RS)>0,rs_adm_tot-ADM_RS,0) AS adm_rs_remaining,
		IF((cs_arb_cs_tot-ARB_CS)<0,ARB_CS-cs_arb_cs_tot,0) AS excess_arb_cs,IF((cs_adm_tot-ADM_CS)<0,ADM_CS-cs_adm_tot,0) AS excess_adm_cs,
		IF((rs_arb_tot-ARB_RS)<0,ARB_RS-rs_arb_tot,0) AS excess_arb_rs,IF((rs_adm_tot-ADM_RS)<0,ADM_RS-rs_adm_tot,0) AS excess_adm_rs

		FROM

		cs_case_details_tbl AS cdt LEFT JOIN
		(SELECT case_no,(cs_arb_fees+int_arb_claim_share_fees_rupee) AS cs_arb_cs_tot,(cs_adminis_fees+int_claim_adm_charges_rupee)AS cs_adm_tot,
		(rs_arb_fees+int_arb_res_share_fees_rupee) AS rs_arb_tot,(rs_adminis_fee+int_res_adm_charges_rupee) AS rs_adm_tot, current_dollar_price
		FROM cs_fee_details_tbl WHERE id IN 
		(SELECT MAX(id) AS id FROM cs_fee_details_tbl  " . $where . "   GROUP BY case_no ) 
		) a ON  cdt.slug =  a.case_no   LEFT JOIN 
		
		(SELECT `case_no`,SUM(CASE WHEN `deposited_towards`='ADM_CS' THEN (`amount`) ELSE 0 END )AS ADM_CS,
		SUM(CASE WHEN `deposited_towards`='ADM_RS' THEN (`amount`) ELSE 0 END) AS ADM_RS,SUM(CASE WHEN `deposited_towards`='ARB_CS' THEN (`amount`) ELSE 0 END) AS ARB_CS,
		SUM(CASE WHEN `deposited_towards`='ARB_RS' THEN (`amount`) ELSE 0 END) AS ARB_RS
		FROM 
		(SELECT case_no,deposited_towards, SUM(amount) AS amount FROM `cs_fee_deposit_tbl` 
		" . $where . " GROUP BY case_no,deposited_towards) a
		GROUP BY case_no)b ON a.case_no=b.case_no

		" . $cdt_where . "
		
		";

		if ($case_no) {
			return $this->db->query($sql)->row_array();
		} else {
			return $this->db->query($sql)->result_array();
		}
	}
}
