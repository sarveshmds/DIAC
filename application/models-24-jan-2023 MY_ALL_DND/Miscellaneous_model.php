
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Miscellaneous_model extends CI_Model
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

        $this->role         = $this->session->userdata('role');
        $this->user_code         = $this->session->userdata('user_code');
        $this->user_name     = $this->session->userdata('user_name');
    }


    public function get($data, $op)
    {
        switch ($op) {

            case 'GET_ALL_MISCELLANEOUS_DATA':
                $this->db->select("m.*");
                $this->db->from('miscellaneous_tbl as m');
                $this->db->where('m.record_status', 1);
                $query = $this->db->get();
                return $query->result_array();
                break;

            case 'ALL_MISCELLANEOUS_LIST':

                $select_column = array("document", "message");
                $order_column = array("document", null, null, null, null);


                $this->db->select("m.*");
                $this->db->from('miscellaneous_tbl as m');

                if ($this->role == 'COORDINATOR') {
                    $this->db->join('miscellaneous_noting_tbl as mnt', "mnt.miscellaneous_id = m.id AND mnt.marked_to_user_code = '" . $this->user_code . "'", 'left');
                }
                // $this->db->join('panel_category_tbl as pc', 'pc.id = poa.category_id');

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

                if ($this->role == 'COORDINATOR') {
                    $this->db->where('mnt.record_status', 1);
                }
                $this->db->where('m.record_status', 1);
                // Clone the db instance
                $tempDb = clone $this->db;

                if (isset($_POST["order"])) {
                    $this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
                } else {
                    $this->db->order_by('m.id', 'DESC');
                }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $query = $this->db->get();
                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = $tempDb->count_all_results();

                // Records total
                $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('miscellaneous_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );

                return $output;
                break;

            case 'GET_MISCELLANEOUS_DATA_USING_ID':
                $this->db->select("m.*");
                $this->db->from('miscellaneous_tbl as m');
                $this->db->where('m.id', $data['id']);
                $this->db->where('m.record_status', 1);
                $query = $this->db->get();
                return $query->row_array();
                break;

            case 'GET_MISCELLANEOUS_DATA_USING_CODE':
                $this->db->select("m.*");
                $this->db->from('miscellaneous_tbl as m');
                $this->db->where('m.m_code', $data);
                $this->db->where('m.record_status', 1);
                $query = $this->db->get();
                return $query->row_array();
                break;

            case 'GET_MISCELLANEOUS_MARKED_TO_USING_ID':
                $this->db->select("mnt.*");
                $this->db->from('miscellaneous_noting_tbl as mnt');
                $this->db->where('mnt.miscellaneous_id', $data['id']);
                $this->db->where('mnt.record_status', 1);
                $query = $this->db->get();
                return $query->result_array();
                break;

            case 'ALL_MISCELLANEOUS_MARKED_USERS':

                $this->db->select("mnt.*, um.user_code, um.user_display_name, um.job_title");
                $this->db->from('miscellaneous_noting_tbl as mnt');
                $this->db->join('user_master as um', 'um.user_code = mnt.marked_from_user_code');
                $this->db->where('mnt.miscellaneous_id', $data['miscellaneous_id']);
                $this->db->where('mnt.record_status', 1);
                $this->db->group_by('mnt.marked_from_user_code');
                $markedFromUser = $this->db->get()->result_array();

                $this->db->select("mnt.*, um.user_code, um.user_display_name, um.job_title");
                $this->db->from('miscellaneous_noting_tbl as mnt');
                $this->db->join('user_master as um', 'um.user_code = mnt.marked_to_user_code');
                $this->db->where('mnt.miscellaneous_id', $data['miscellaneous_id']);
                $this->db->where('mnt.record_status', 1);
                $markedToUser = $this->db->get()->result_array();

                $users = array_merge($markedToUser, $markedFromUser);

                return $users;
                break;

            case 'GET_MISCELLANEOUS_REPLIES_LIST_USING_ID':
                $this->db->select("mrt.id, mrt.miscellaneous_id, mrt.reply, mrt.reply_from, mrt.reply_to, mrt.record_status, mrt.created_at, um.user_display_name as reply_to_user, um.job_title as reply_to_job_title, um2.user_display_name reply_from_user, um2.job_title as reply_from_job_title");
                $this->db->from('miscellaneous_replies_tbl as mrt');
                $this->db->join('user_master as um', 'um.user_code = mrt.reply_to', 'left');
                $this->db->join('user_master as um2', 'um2.user_code = mrt.reply_from', 'left');
                $this->db->where(['mrt.miscellaneous_id' => $data['miscellaneous_id'], 'mrt.record_status' => 1]);
                $this->db->order_by('mrt.id', 'ASC');
                $query = $this->db->get();
                return $query->result_array();
                break;

            case 'ALL_MISCELLANEOUS_REPLIES_LIST':

                $query = $this->db->query("
                SELECT a.* , `um`.`user_display_name` AS `reply_to_user`,   `um`.`job_title` AS `reply_to_job_title`, `um2`.`user_display_name` `reply_from_user`,
                  `um2`.`job_title` AS `reply_from_job_title`  FROM
                ( 
                  SELECT (@row_number:=@row_number - 1) AS serial_no, `mrt`.*
                  -- , `um`.`user_display_name` as `reply_to_user`,   `um`.`job_title` as `reply_to_job_title`, `um2`.`user_display_name` `reply_from_user`,
                 -- `um2`.`job_title` as `reply_from_job_title`  
                 FROM `miscellaneous_replies_tbl` AS `mrt` 
                
                  JOIN ( SELECT @row_number := (SELECT COUNT(1) FROM miscellaneous_replies_tbl WHERE miscellaneous_id='" . $this->input->post('miscellaneous_id') . "'))r
                 WHERE `mrt`.`miscellaneous_id` = '" . $this->input->post('miscellaneous_id') . "'
                
                ORDER BY mrt.id DESC  )a  
                 LEFT JOIN `user_master` AS `um` ON `um`.`user_code` = a.`reply_to` AND a.`miscellaneous_id` = '" . $this->input->post('miscellaneous_id') . "'
                  LEFT JOIN `user_master` AS `um2` ON `um2`.`user_code` = a.reply_from
                ORDER BY a.id DESC
                LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "
                ");

                // Pagination
                $paginationQuery = $this->db->query("
                SELECT a.* , `um`.`user_display_name` AS `reply_to_user`,   `um`.`job_title` AS `reply_to_job_title`, `um2`.`user_display_name` `reply_from_user`,
                  `um2`.`job_title` AS `reply_from_job_title`  FROM
                ( 
                  SELECT (@row_number:=@row_number - 1) AS serial_no, `mrt`.*
                  -- , `um`.`user_display_name` as `reply_to_user`,   `um`.`job_title` as `reply_to_job_title`, `um2`.`user_display_name` `reply_from_user`,
                 -- `um2`.`job_title` as `reply_from_job_title`  
                 FROM `miscellaneous_replies_tbl` AS `mrt` 
                
                  JOIN ( SELECT @row_number := (SELECT COUNT(1) FROM miscellaneous_replies_tbl WHERE miscellaneous_id='" . $this->input->post('miscellaneous_id') . "'))r
                 WHERE `mrt`.`miscellaneous_id` = '" . $this->input->post('miscellaneous_id') . "'
                
                ORDER BY mrt.id DESC  )a  
                 LEFT JOIN `user_master` AS `um` ON `um`.`user_code` = a.`reply_to` AND a.`miscellaneous_id` = '" . $this->input->post('miscellaneous_id') . "'
                  LEFT JOIN `user_master` AS `um2` ON `um2`.`user_code` = a.reply_from
                ORDER BY a.id DESC
                ");

                // $this->db->query('SET @row_number = 0');
                // $this->db->select("(@row_number:=@row_number + 1) AS serial_no, mrt.id, mrt.miscellaneous_id, mrt.reply, mrt.reply_from, mrt.reply_to, mrt.record_status, DATE_FORMAT(mrt.created_at, '%d-%m-%Y') as reply_on,  um.user_display_name as reply_to_user, um.job_title as reply_to_job_title, um2.user_display_name reply_from_user, um2.job_title as reply_from_job_title");
                // $this->db->from('miscellaneous_replies_tbl as mrt');
                // $this->db->join('user_master as um', 'um.user_code = mrt.reply_to');
                // $this->db->join('user_master as um2', 'um2.user_code = mrt.reply_from');

                // $this->db->order_by('serial_no', 'DESC');

                // $this->db->where(['mrt.miscellaneous_id' => $this->input->post('miscellaneous_id'), 'mrt.record_status' => 1]);

                // Clone the db instance
                // $tempDb = clone $this->db;

                // if ($_POST['length'] != -1) {
                //     $this->db->limit($_POST['length'], );
                // }

                // $query = $this->db->get();
                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = count($paginationQuery->result());

                // Records total
                $recordsTotal = $this->db->where('record_status', 1)->where('miscellaneous_id', $this->input->post('miscellaneous_id'))->select("*")->from('miscellaneous_replies_tbl')->count_all_results();

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

            case 'ADD_MISCELLANEOUS':

                $this->form_validation->set_rules('miscellaneous_message', 'Message', 'required|xss_clean');
                $this->form_validation->set_rules('miscellaneous_name', 'Name', 'xss_clean');
                $this->form_validation->set_rules('miscellaneous_phone_number', 'Phone Number', 'xss_clean');
                $this->form_validation->set_rules('miscellaneous_marked_to', 'Marked To', 'xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    try {
                        // Upload files ==============
                        $files = [];
                        if (!empty(array_filter($_FILES['miscellaneous_documents']['name']))) {
                            $this->load->library('fileupload');
                            $file_result = $this->fileupload->uploadMultipleFiles($_FILES['miscellaneous_documents'], [
                                'file_name' => 'MISCELLANEOUS_FILES_' . time(),
                                'file_move_path' => MISCELLANEOUS_FILE_UPLOADS_FOLDER,
                                'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
                                'allowed_mime_types' => CASE_FILE_ALLOWED_MIME_TYPES
                            ]);

                            // After getting result of file upload
                            if ($file_result['status'] == false) {
                                $this->db->trans_rollback();
                                return $file_result;
                            } else {
                                $files = $file_result['files'];
                            }
                        }

                        $miscData = $this->db->select('*')->from('miscellaneous_tbl')->count_all_results();
                        $diary_number = generateDiaryNumber($miscData, 'RR');

                        $mtData = array(
                            'm_code' => generateCode(),
                            'user_code' => $this->user_code,
                            'diary_number' => $diary_number,
                            'document' => (count($files) > 0) ? json_encode($files) : '',
                            'name' => $this->security->xss_clean($data['miscellaneous_name']),
                            'phone_number' => $this->security->xss_clean($data['miscellaneous_phone_number']),
                            'message' => $this->security->xss_clean($data['miscellaneous_message']),
                            'record_status' => 1,
                            'created_by' => $this->user_code,
                            'created_at' => $this->date,
                            'updated_by' => $this->user_code,
                            'updated_at' => $this->date
                        );

                        // Insert panel category
                        $result = $this->db->insert('miscellaneous_tbl', $mtData);

                        if ($result) {
                            $table_id = $this->db->insert_id();

                            // Marked to
                            if (count($data['miscellaneous_marked_to']) > 0) {
                                $mmtData = [];
                                foreach ($data['miscellaneous_marked_to'] as $mmt) {
                                    array_push($mmtData, array(
                                        'miscellaneous_id' => $table_id,
                                        'marked_from_user_code' => $this->user_code,
                                        'marked_to_user_code' => $mmt,
                                        'record_status' => 1,
                                        'created_by' => $this->user_code,
                                        'created_at' => $this->date,
                                        'updated_by' => $this->user_code,
                                        'updated_at' => $this->date
                                    ));
                                }
                                if (count($mmtData) > 0) {
                                    $mmtResult = $this->db->insert_batch('miscellaneous_noting_tbl', $mmtData);
                                    if (!$mmtResult) {
                                        $this->db->trans_rollback();
                                        return array('status' => false, 'msg' => "Server failed while saving data.");
                                    }
                                }

                                foreach ($data['miscellaneous_marked_to'] as $mmt) {
                                    // Get users username
                                    $user = $this->common_model->get_user_details_using_user_code($mmt);

                                    // Insert Notification
                                    $this->notification_model->insertNotification($table_id, 'miscellaneous_noting_tbl', $user['user_code'], '');
                                }
                            }

                            $table_name = 'miscellaneous_tbl';
                            $message = 'A new miscellaneous is added in panel.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);

                            $this->db->trans_commit();
                            return array('status' => true, 'msg' => "Referrals/Requests saved successfully", 'redirect_url' => base_url() . 'referral-requests');
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

            case 'EDIT_MISCELLANEOUS':

                $this->form_validation->set_rules('miscellaneous_message', 'Message', 'xss_clean');
                $this->form_validation->set_rules('miscellaneous_name', 'Name', 'xss_clean');
                $this->form_validation->set_rules('miscellaneous_phone_number', 'Phone Number', 'xss_clean');
                $this->form_validation->set_rules('miscellaneous_marked_to', 'Marked To', 'xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    try {
                        $id = $this->input->post('hidden_miscellaneous_id');

                        // Upload files ==============
                        $files = [];
                        if (!empty(array_filter($_FILES['miscellaneous_documents']['name']))) {
                            $this->load->library('fileupload');
                            $file_result = $this->fileupload->uploadMultipleFiles($_FILES['miscellaneous_documents'], [
                                'file_name' => 'MISCELLANEOUS_FILES_' . time(),
                                'file_move_path' => MISCELLANEOUS_FILE_UPLOADS_FOLDER,
                                'allowed_file_types' => CASE_FILE_FORMATS_ALLOWED,
                                'allowed_mime_types' => CASE_FILE_ALLOWED_MIME_TYPES
                            ]);

                            // After getting result of file upload
                            if ($file_result['status'] == false) {
                                $this->db->trans_rollback();
                                return $file_result;
                            } else {
                                $files = $file_result['files'];
                                $files = (count($files) > 0) ? json_encode($files) : '';
                            }
                        } else {
                            $files = $this->input->post('hidden_miscellaneous_documents');
                        }

                        $mtData = array(
                            'document' => $files,
                            'name' => $this->security->xss_clean($data['miscellaneous_name']),
                            'phone_number' => $this->security->xss_clean($data['miscellaneous_phone_number']),
                            'message' => $this->security->xss_clean($data['miscellaneous_message']),
                            'record_status' => 1,
                            'created_by' => $this->user_code,
                            'created_at' => $this->date,
                            'updated_by' => $this->user_code,
                            'updated_at' => $this->date
                        );

                        // Update
                        $result = $this->db->where('id', $id)->update('miscellaneous_tbl', $mtData);

                        if ($result) {
                            $table_id = $id;

                            // Delete all the marked noting and create new ones.
                            $mmtDelResult = $this->db->where('miscellaneous_id', $id)->update('miscellaneous_noting_tbl', [
                                'record_status' => 0,
                                'updated_by' => $this->user_code,
                                'updated_at' => $this->date
                            ]);

                            if (!$mmtDelResult) {
                                $this->db->trans_rollback();
                                return array('status' => false, 'msg' => "Server failed while saving data.");
                            }

                            // Marked to
                            if (count($data['miscellaneous_marked_to']) > 0) {
                                $mmtData = [];
                                foreach ($data['miscellaneous_marked_to'] as $mmt) {
                                    array_push($mmtData, array(
                                        'miscellaneous_id' => $table_id,
                                        'user_code' => $mmt,
                                        'record_status' => 1,
                                        'created_by' => $this->user_code,
                                        'created_at' => $this->date,
                                        'updated_by' => $this->user_code,
                                        'updated_at' => $this->date
                                    ));
                                }
                                if (count($mmtData) > 0) {
                                    $mmtResult = $this->db->insert_batch('miscellaneous_noting_tbl', $mmtData);
                                    if (!$mmtResult) {
                                        $this->db->trans_rollback();
                                        return array('status' => false, 'msg' => "Server failed while saving data.");
                                    }
                                }
                            }

                            $table_name = 'miscellaneous_tbl';
                            $message = 'A miscellaneous is updated in panel.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);

                            $this->db->trans_commit();
                            return array('status' => true, 'msg' => "Referrals/Requests updated successfully", 'redirect_url' => base_url() . 'miscellaneous');
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

            case 'DELETE_MISCELLANEOUS':

                $this->form_validation->set_rules('id', 'Id', 'required|xss_clean');

                if ($this->form_validation->run()) {
                    $id = $this->security->xss_clean($data['id']);

                    $r = $this->db->where('id', $id)->update('miscellaneous_tbl', array('record_status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

                    if ($r) {

                        // Update the data logs table for data tracking
                        $table_name = 'miscellaneous_tbl';
                        $table_id = $id;
                        $message = 'A miscellaneous is deleted.';
                        $this->common_model->update_data_logs($table_name, $table_id, $message);

                        $dbstatus = TRUE;
                        $dbmessage = 'Referrals/Requests deleted successfully';
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

            case 'ADD_MISCELLANEOUS_REPLIES':

                $this->form_validation->set_rules('miscellaneous_reply', 'Reply', 'required|xss_clean');
                $this->form_validation->set_rules('miscellaneous_reply_to', 'required|Reply To', 'xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    try {

                        $mtData = array(
                            'miscellaneous_id' => $this->security->xss_clean($data['hidden_miscellaneous_id']),
                            'reply' => $this->security->xss_clean($data['miscellaneous_reply']),
                            'reply_from' => $this->user_code,
                            'reply_to' => $this->security->xss_clean($data['miscellaneous_reply_to']),
                            'record_status' => 1,
                            'created_by' => $this->user_code,
                            'created_at' => $this->date,
                            'updated_by' => $this->user_code,
                            'updated_at' => $this->date
                        );

                        // Insert panel category
                        $result = $this->db->insert('miscellaneous_replies_tbl', $mtData);

                        if ($result) {
                            $table_id = $this->db->insert_id();

                            $table_name = 'miscellaneous_tbl';
                            $message = 'A new miscellaneous reply is added in panel.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);

                            // Get users username
                            $user = $this->common_model->get_user_details_using_user_code($mtData['reply_to']);

                            // Insert Notification
                            $this->notification_model->insertNotification($table_id, 'miscellaneous_replies_tbl', $user['user_code'], $mtData['miscellaneous_id']);

                            $this->db->trans_commit();
                            return array('status' => true, 'msg' => "Reply sent successfully", 'redirect_url' => base_url() . 'miscellaneous');
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

            default:
                return array('status' => false, 'msg' => NO_OPERATION);
        }
    }
}
