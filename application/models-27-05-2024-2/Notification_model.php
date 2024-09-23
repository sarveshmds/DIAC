
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model
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

			case 'CHECK_NOTIFICATION':
				$result = $this->db->from('notification_tbl')
					->select('*')
					->where(array('notification_to' => $this->user_code, 'seen' => 0, 'status' => 1))
					->order_by('id', 'desc')
					->get()
					->result_array();

				if ($result) {

					$notification_data = array();

					foreach ($result as $key => $res) {

						$notification_item = array();

						if ($res['type_table'] == 'cs_noting_tbl') {
							$result2 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
								->from($res['type_table'] . ' as cnt')
								->join('user_master as um', 'um.user_code = cnt.marked_by', 'left')
								->join('user_master as um2', 'um2.user_code = cnt.marked_to', 'left')
								->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no', 'left')
								->where('cnt.id', $res['type_id'])
								->get()
								->row_array();

							if ($result2) {
								$notification_text = $result2['marked_by_user'] . ' marked you in noting of case <b>' . $result2['cdt_case_no'] . '</b> at ' . date('d M, Y - h:i A', strtotime($result2['marked_at']));

								$notification_item['text'] = $notification_text;
								$notification_item['type'] = 'NOTING';
								$notification_item['id'] = $res['id'];
								$notification_item['reference_id'] = $res['reference_id'];
								$notification_item['reference_link'] = base_url() . 'noting/' . $res['reference_id'];
								$notification_item['popup'] = $res['popup'];

								// $notification_data[] = $notification_item;
								array_push($notification_data, $notification_item);
							}
						}
						if ($res['type_table'] == 'cs_case_allotment_tbl') {
							$result2 = $this->db->select('um.user_display_name as alloted_by_user, um2.user_display_name as alloted_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
								->where('cnt.id', $res['type_id'])
								->from($res['type_table'] . ' as cnt')
								->join('user_master as um', 'um.user_code = cnt.alloted_by', 'left')
								->join('user_master as um2', 'um2.user_code = cnt.alloted_to', 'left')
								->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no', 'left')
								->get()
								->row_array();

							// print_r($result2); die();
							if ($result2) {
								$notification_text = 'Case number: <b>' . $result2['cdt_case_no'] . '</b> is allotted to you at ' . date('d M, Y - h:i A', strtotime($result2['marked_at']));

								$notification_item['text'] = $notification_text;
								$notification_item['type'] = 'CASE_ALLOTMENT';
								$notification_item['id'] = $res['id'];
								$notification_item['reference_id'] = $res['reference_id'];
								$notification_item['reference_link'] = base_url() . 'allotted-case';
								$notification_item['popup'] = $res['popup'];

								array_push($notification_data, $notification_item);
							}
						}
						if ($res['type_table'] == 'miscellaneous_noting_tbl') {
							$result3 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at')
								->from($res['type_table'] . ' as cnt')
								->join('user_master as um', 'um.user_code = cnt.marked_from_user_code', 'left')
								->join('user_master as um2', 'um2.user_code = cnt.marked_to_user_code', 'left')
								->where('cnt.id', $res['type_id'])
								->get()
								->row_array();

							// print_r($result3); die();
							if ($result3) {
								$notification_text = 'A Referrals/Requests is added for you by ' . $result3['marked_by_user'] . ' at ' . date('d M, Y - h:i A', strtotime($result3['marked_at']));

								$notification_item['text'] = $notification_text;
								$notification_item['type'] = 'MISCELLANEOUS';
								$notification_item['id'] = $res['id'];
								$notification_item['reference_id'] = $res['reference_id'];
								$notification_item['reference_link'] = base_url() . 'referral-requests';
								$notification_item['popup'] = $res['popup'];

								array_push($notification_data, $notification_item);
							}
						}

						if ($res['type_table'] == 'miscellaneous_replies_tbl') {
							$result3 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at')
								->from($res['type_table'] . ' as cnt')
								->join('user_master as um', 'um.user_code = cnt.reply_from', 'left')
								->join('user_master as um2', 'um2.user_code = cnt.reply_to', 'left')
								->where('cnt.id', $res['type_id'])
								->get()
								->row_array();

							// print_r($result3); die();
							if ($result3) {
								$notification_text = 'A reply is given on Referrals/Requests for you by ' . $result3['marked_by_user'] . ' at ' . date('d M, Y - h:i A', strtotime($result3['marked_at']));

								$notification_item['text'] = $notification_text;
								$notification_item['type'] = 'MISCELLANEOUS_REPLY';
								$notification_item['id'] = $res['id'];
								$notification_item['reference_id'] = $res['reference_id'];
								$notification_item['reference_link'] = base_url() . 'miscellaneous-reply?miscellaneous_id=' . $res['reference_id'];
								$notification_item['popup'] = $res['popup'];

								array_push($notification_data, $notification_item);
							}
						}
					}

					$output = array(
						'status' => true,
						'count' => count($notification_data),
						'notification_data' => $notification_data,
					);

					return $output;
				} else {
					return array(
						'status' => false,
						'count' => 0,
						'notification_data' => [],
					);
				}

				break;

			case 'GET_NOTIFICATIONS':

				$result = $this->db->from('notification_tbl')
					->where(array('notification_to' => $this->user_code, 'status' => 1))
					->get()
					->result_array();

				$notification_data = array();
				$notification_type = '';
				foreach ($result as $key => $res) {
					$notification_item = array();

					if ($res['type_table'] == 'cs_noting_tbl') {
						$result2 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
							->from('cs_noting_tbl as cnt')
							->join('user_master as um', 'um.user_code = cnt.marked_by', 'left')
							->join('user_master as um2', 'um2.user_code = cnt.marked_to', 'left')
							->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no', 'left')
							->where('cnt.id', $res['type_id'])
							->get()
							->row_array();

						if ($result2) {
							$notification_text = $result2['marked_by_user'] . ' marked you in noting of case <b>' . $result2['cdt_case_no'] . '</b> at ' . date('d M, Y - h:i A', strtotime($result2['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'NOTING';
							$notification_item['id'] = $res['id'];
							$notification_item['reference_id'] = $res['reference_id'];
							$notification_item['reference_link'] = base_url() . 'noting/' . $res['reference_id'];

							// $notification_data[] = $notification_item;
							array_push($notification_data, $notification_item);
						}
					}
					if ($res['type_table'] == 'cs_case_allotment_tbl') {
						$result2 = $this->db->select('um.user_display_name as alloted_by_user, um2.user_display_name as alloted_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
							->where('cnt.id', $res['type_id'])
							->from($res['type_table'] . ' as cnt')
							->join('user_master as um', 'um.user_code = cnt.alloted_by', 'left')
							->join('user_master as um2', 'um2.user_code = cnt.alloted_to', 'left')
							->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no', 'left')
							->get()
							->row_array();

						// print_r($result2); die();
						if ($result2) {
							$notification_text = 'Case number: <b>' . $result2['cdt_case_no'] . '</b> is allotted to you at ' . date('d M, Y - h:i A', strtotime($result2['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'CASE_ALLOTMENT';
							$notification_item['id'] = $res['id'];
							$notification_item['reference_id'] = $res['reference_id'];
							$notification_item['reference_link'] = base_url() . 'allotted-case';

							array_push($notification_data, $notification_item);
						}
					}
					if ($res['type_table'] == 'miscellaneous_noting_tbl') {
						$result3 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at')
							->from($res['type_table'] . ' as cnt')
							->join('user_master as um', 'um.user_code = cnt.marked_from_user_code', 'left')
							->join('user_master as um2', 'um2.user_code = cnt.marked_to_user_code', 'left')
							->where('cnt.id', $res['type_id'])
							->get()
							->row_array();

						// print_r($result3); die();
						if ($result3) {
							$notification_text = 'A Referrals/Requests is added for you by ' . $result3['marked_by_user'] . ' at ' . date('d M, Y - h:i A', strtotime($result3['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'MISCELLANEOUS';
							$notification_item['id'] = $res['id'];
							$notification_item['reference_id'] = $res['reference_id'];
							$notification_item['reference_link'] = base_url() . 'miscellaneous';

							array_push($notification_data, $notification_item);
						}
					}

					if ($res['type_table'] == 'miscellaneous_replies_tbl') {
						$result3 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at')
							->from($res['type_table'] . ' as cnt')
							->join('user_master as um', 'um.user_code = cnt.reply_from', 'left')
							->join('user_master as um2', 'um2.user_code = cnt.reply_to', 'left')
							->where('cnt.id', $res['type_id'])
							->get()
							->row_array();

						// print_r($result3); die();
						if ($result3) {
							$notification_text = 'A reply is given on Referrals/Requests for you by ' . $result3['marked_by_user'] . ' at ' . date('d M, Y - h:i A', strtotime($result3['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'MISCELLANEOUS_REPLY';
							$notification_item['id'] = $res['id'];
							$notification_item['reference_id'] = $res['reference_id'];
							$notification_item['reference_link'] = base_url() . 'miscellaneous-reply?miscellaneous_id=' . $res['reference_id'];

							array_push($notification_data, $notification_item);
						}
					}
				}

				// Output
				$output = array(
					"data" => $notification_data
				);
				return $output;
				break;

			case 'GET_TODAYS_USER_REMINDERS':
				return $this->db->select('*')
					->from('reminders_tbl')
					->where('user_code', $this->user_code)
					->where('status', 1)
					->where('date', date('d-m-Y'))
					->get()
					->result_array();


				break;
			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function post($data, $op)
	{
		switch ($op) {

			case 'DELETE_NOTIFICATION':

				$this->form_validation->set_rules('id', 'Notification Id', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('notification_tbl', array('status' => 0, 'updated_at' => currentDateTimeStamp(), 'updated_by' => $this->user_code));

					if ($r) {

						// Update the data logs table for data tracking
						$data = $this->db->select('id, notification_to')->from('notification_tbl')->where('id', $id)->get()->row_array();

						$user = $this->common_model->get_user_details($data['notification_to']);

						$table_name = 'notification_tbl';
						$table_id = $id;
						$message = 'Notification for ' . $user['user_display_name'] . ' (' . $user['job_title'] . ') is deleted.';
						$this->common_model->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Notification deleted successfully';
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

			case 'DELETE_SELECTED_NOTIFICATION':

				$ids = $this->security->xss_clean($data['ids']);

				if (count($ids) < 1) {
					return array('status' => false, 'msg' => 'Please select to delete notifications');
				}

				for ($i = 0; $i < count($ids); $i++) {
					$this->db->where('id', $ids[$i]);
					$r = $this->db->update('notification_tbl', array('status' => 0, 'updated_at' => currentDateTimeStamp(), 'updated_by' => $this->user_code));
				}

				if ($r) {

					// Update the data logs table for data tracking
					$user = $this->common_model->get_user_details_using_usercode($this->user_code);

					$table_name = 'notification_tbl';
					$table_id = '';
					$message = 'Notification for ' . $user['user_display_name'] . ' (' . $user['job_title'] . ') is deleted.';
					$this->common_model->update_data_logs($table_name, $table_id, $message);

					$dbstatus = TRUE;
					$dbmessage = 'Selected notification deleted successfully';
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Something went wrong. Please try again.';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'DELETE_ALL_NOTIFICATION':

				$r = $this->db->update('notification_tbl', array('status' => 0, 'updated_at' => currentDateTimeStamp(), 'updated_by' => $this->user_code));

				if ($r) {

					// Update the data logs table for data tracking
					$user = $this->common_model->get_user_details_using_usercode($this->user_code);

					$table_name = 'notification_tbl';
					$table_id = '';
					$message = 'All Notification for ' . $user['user_display_name'] . ' (' . $user['job_title'] . ') is deleted.';
					$this->common_model->update_data_logs($table_name, $table_id, $message);

					$dbstatus = TRUE;
					$dbmessage = 'All notifications deleted successfully';
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Something went wrong. Please try again.';
				}

				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'MARK_NOTIFICATION_SEEN':
				$this->db->where('notification_to', $this->user_code)->update('notification_tbl', array('seen' => 1));
				return true;
				break;

			case 'MARK_CATEGORYWISE_NOTIFICATION_SEEN':
				$this->db->where([
					'notification_to' => $this->user_code,
				])->where($data)->update('notification_tbl', array('seen' => 1));
				return true;
				break;

			case 'MARK_NOTIFICATION_POPUP':
				$this->db->where([
					'notification_to' => $this->user_code,
				])->update('notification_tbl', array('popup' => 1));
				return true;
				break;

			case 'ADD_REMINDER':
				$this->form_validation->set_rules('reminder_date', 'Date', 'required|xss_clean');
				$this->form_validation->set_rules('reminder_note', 'Note', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$this->db->trans_begin();
					try {

						$case_details_data = array(
							'user_code' => $this->user_code,
							'date' => $this->security->xss_clean($data['reminder_date']),
							'note' => $this->security->xss_clean($data['reminder_note']),
							'status' => 1,
							'created_by' => $this->user_code,
							'created_at' => currentDateTimeStamp(),
							'updated_by' => $this->user_code,
							'updated_at' => currentDateTimeStamp()
						);

						// Insert case details
						$result = $this->db->insert('reminders_tbl', $case_details_data);
						if ($result) {

							// Update the data logs table for data tracking
							$table_name = 'reminders_tbl';
							$table_id = $this->db->insert_id();
							$message = 'A new reminder is added.';

							$dataLogResult = $this->common_model->update_data_logs($table_name, $table_id, $message);

							if ($dataLogResult) {
								$this->db->trans_commit();
								$output = array(
									'status' => true,
									'msg' => 'Record saved successfully',
									'redirect_url' => base_url() . 'all-registered-case'
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

			default:
				return array('status' => false, 'msg' => NO_OPERATION);
		}
	}

	public function insertNotification($type_id, $type_table, $notification_to, $reference_id)
	{
		$notification_data = array(
			'type_table' => $type_table,
			'type_id' => $type_id,
			'reference_id' => $reference_id,
			'notification_from' => $this->user_code,
			'notification_to' => $notification_to,
			'status' => 1,
			'created_at' => currentDateTimeStamp(),
			'created_by' => $this->user_code
		);

		return $this->db->insert('notification_tbl', $notification_data);
	}
}
