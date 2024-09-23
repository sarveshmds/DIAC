
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Poa_model extends CI_Model
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

			case 'ALL_POA_LIST':

				$select_column = array("name", "category_id", "contact_details", "experience", "enrollment_no");
				$order_column = array("name", null, null, null, null);


				$this->db->select("poa.id, poa.name, poa.category_id, pc.category_name as category_name, poa.contact_details, poa.email_details, poa.address_details, poa.remarks, poa.experience, poa.enrollment_no");
				$this->db->from('panel_of_arbitrator_tbl as poa');
				$this->db->join('panel_category_tbl as pc', 'pc.id = poa.category_id');

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
					$this->db->order_by('poa.id', 'DESC');
				}

				if ($_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				// If category is set
				if (isset($_POST['category']) && !empty($_POST['category'])) {
					$category = $this->security->xss_clean($_POST['category']);
					$this->db->where('poa.category_id', $category);
				}

				// If name is set
				if (isset($_POST['name']) && !empty($_POST['name'])) {
					$name = $this->security->xss_clean($_POST['name']);
					$this->db->where("poa.name LIKE '%$name%'");
				}


				$this->db->where('poa.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

				// Filter records
				$recordsFiltered = $this->db->where('status', 1)->select("*")->from('panel_of_arbitrator_tbl')->count_all_results();

				// Records total
				$recordsTotal = $this->db->where('status', 1)->select("*")->from('panel_of_arbitrator_tbl')->count_all_results();

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

			case 'ADD_POA_FORM':

				// Panel of arbitrator
				$this->form_validation->set_rules('poa_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
				$this->form_validation->set_rules('poa_category', 'Category', 'required|xss_clean');
				$this->form_validation->set_rules('poa_experience', 'Experience', 'alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('poa_enrollment_no', 'Enrollment No.', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('poa_contact_details', 'Contact Details', 'xss_clean');
				$this->form_validation->set_rules('poa_email_details', 'Email Details', 'xss_clean');
				$this->form_validation->set_rules('poa_address_details', 'Address Details', 'xss_clean');
				$this->form_validation->set_rules('poa_remarks', 'Remarks', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$name = $this->security->xss_clean($data['poa_name']);
						$poa_data = array(
							'name' => $name,
							'category_id' => $this->security->xss_clean($data['poa_category']),
							'contact_details' => $this->security->xss_clean($data['poa_contact_details']),
							'email_details' => $this->security->xss_clean($data['poa_email_details']),
							'address_details' => $this->security->xss_clean($data['poa_address_details']),
							'remarks' => $this->security->xss_clean($data['poa_remarks']),
							'experience' => $this->security->xss_clean($data['poa_experience']),
							'enrollment_no' => $this->security->xss_clean($data['poa_enrollment_no']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => $this->date
						);

						// Insert panel category
						$result = $this->db->insert('panel_of_arbitrator_tbl', $poa_data);

						if ($result) {

							$table_id = $this->db->insert_id();
							$table_name = 'panel_of_arbitrator_tbl';
							$message = 'A new arbitrator ' . $name . ' is added in panel.';
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

			case 'EDIT_POA_FORM':

				// Panel of arbitrator
				$this->form_validation->set_rules('poa_hidden_id', 'Panel of arbitrator id', 'required|xss_clean');
				$this->form_validation->set_rules('poa_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
				$this->form_validation->set_rules('poa_category', 'Category', 'required|xss_clean');
				$this->form_validation->set_rules('poa_experience', 'Experience', 'alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('poa_enrollment_no', 'Enrollment No.', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('poa_contact_details', 'Contact Details', 'xss_clean');
				$this->form_validation->set_rules('poa_email_details', 'Email Details', 'xss_clean');
				$this->form_validation->set_rules('poa_address_details', 'Address Details', 'xss_clean');
				$this->form_validation->set_rules('poa_remarks', 'Remarks', 'xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$poa_id = $this->security->xss_clean($data['poa_hidden_id']);
						$name = $this->security->xss_clean($data['poa_name']);

						$poa_data = array(
							'name' => $name,
							'category_id' => $this->security->xss_clean($data['poa_category']),
							'contact_details' => $this->security->xss_clean($data['poa_contact_details']),
							'email_details' => $this->security->xss_clean($data['poa_email_details']),
							'address_details' => $this->security->xss_clean($data['poa_address_details']),
							'remarks' => $this->security->xss_clean($data['poa_remarks']),
							'experience' => $this->security->xss_clean($data['poa_experience']),
							'enrollment_no' => $this->security->xss_clean($data['poa_enrollment_no']),
							'status' => 1,
							'updated_by' => $this->user_code,
							'updated_at' => $this->date
						);

						// Update panel of arbitrator
						$result = $this->db->where('id', $poa_id)->update('panel_of_arbitrator_tbl', $poa_data);

						if ($result) {

							// Update the data logs table for data tracking
							$table_name = 'panel_of_arbitrator_tbl';
							$table_id = $poa_id;
							$message = 'Details of arbitrator ' . $name . ' of panel is updated.';
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

			case 'DELETE_POA':

				$this->form_validation->set_rules('id', 'Panel of arbitrator id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('panel_of_arbitrator_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, name')->from('panel_of_arbitrator_tbl')->where('id', $id)->get()->row_array();
						$table_name = 'panel_of_arbitrator_tbl';
						$table_id = $id;
						$message = 'Arbitrator ' . $data['name'] . ' of panel is deleted.';
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
