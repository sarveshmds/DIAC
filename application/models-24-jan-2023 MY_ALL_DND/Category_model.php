
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model
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
			case 'GET_ALL_PANEL_CATEGORY':
				$this->db->from('panel_category_tbl');
				$this->db->select('id, category_name');
				$this->db->where('status', 1);
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'GET_ALL_PURPOSE_CATEGORY':
				$this->db->from('purpose_category_tbl');
				$this->db->select('id, category_name');
				$this->db->where('status', 1);
				$res = $this->db->get();
				return $res->result_array();
				break;

			case 'ALL_PANEL_CATEGORY':

				$select_column = array("category_name");
				$order_column = array("category_name");


				$this->db->select("id, category_name");
				$this->db->from('panel_category_tbl');

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

				$this->db->where('status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('status', 1)->select("*")->from('panel_category_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('panel_category_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;


			case 'ALL_PURPOSE_CATEGORY':

				$select_column = array("category_name");
				$order_column = array("category_name");


				$this->db->select("id, category_name");
				$this->db->from('purpose_category_tbl');

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

				$this->db->where('status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('status', 1)->select("*")->from('purpose_category_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('purpose_category_tbl')->count_all_results();

				// Output
				$output = array(
					"draw" => intval($_POST['draw']),
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $fetch_data
				);

				return $output;
				break;

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function post($data, $op)
	{
		switch ($op) {
			case 'ADD_PANEL_CATEGORY':

				// Panel Category
				$this->form_validation->set_rules('pc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$category_name = $this->security->xss_clean($data['pc_category_name']);
						$fee_ref_data = array(
							'category_name' => $category_name,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert panel category
						$result = $this->db->insert('panel_category_tbl', $fee_ref_data);

						if ($result) {

							$table_id = $this->db->insert_id();

							$table_name = 'panel_category_tbl';
							$message = 'A new panel category ' . $category_name . ' is added.';
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

			case 'EDIT_PANEL_CATEGORY':

				// Panel category
				$this->form_validation->set_rules('pc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
				$this->form_validation->set_rules('pc_hidden_id', 'Category id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$pc_id = $this->security->xss_clean($data['pc_hidden_id']);
						$category_name = $this->security->xss_clean($data['pc_category_name']);

						$pc_data = array(
							'category_name' => $category_name,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update case fee refund
						$result = $this->db->where('id', $pc_id)->update('panel_category_tbl', $pc_data);

						if ($result) {

							// Update the data logs table for data tracking
							$table_name = 'panel_category_tbl';
							$table_id = $pc_id;
							$message = 'Panel Category ' . $category_name . ' is updated.';
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

			case 'DELETE_PANEL_CATEGORY':

				$this->form_validation->set_rules('id', 'Category id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('panel_category_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, category_name')->from('panel_category_tbl')->where('id', $id)->get()->row_array();
						$table_name = 'panel_category_tbl';
						$table_id = $id;
						$message = 'Panel Category ' . $data['category_name'] . ' is deleted.';
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

			case 'ADD_PURPOSE_CATEGORY':

				// Purpose Category
				$this->form_validation->set_rules('puc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$category_name = $this->security->xss_clean($data['puc_category_name']);
						$puc_data = array(
							'category_name' => $category_name,
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert purpose category
						$result = $this->db->insert('purpose_category_tbl', $puc_data);

						if ($result) {

							// Update the data logs table for data tracking
							$table_id = $this->db->insert_id();
							$table_name = 'purpose_category_tbl';
							$message = 'A new purpose category ' . $category_name . ' is added.';
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

			case 'EDIT_PURPOSE_CATEGORY':

				// Purpose category
				$this->form_validation->set_rules('puc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
				$this->form_validation->set_rules('puc_hidden_id', 'Category id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$puc_id = $this->security->xss_clean($data['puc_hidden_id']);
						$category_name = $this->security->xss_clean($data['puc_category_name']);
						$puc_data = array(
							'category_name' => $category_name,
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update purpose category
						$result = $this->db->where('id', $puc_id)->update('purpose_category_tbl', $puc_data);

						if ($result) {

							// Update the data logs table for data tracking
							$table_name = 'purpose_category_tbl';
							$table_id = $puc_id;
							$message = 'Details of category ' . $category_name . ' is updated.';
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

			case 'DELETE_PURPOSE_CATEGORY':

				$this->form_validation->set_rules('id', 'Category id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('purpose_category_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, category_name')->from('purpose_category_tbl')->where('id', $id)->get()->row_array();
						$table_name = 'cs_arbitral_tribunal_tbl';
						$table_id = $id;
						$message = 'Category ' . $data['category_name'] . ' is deleted.';
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

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}
}
