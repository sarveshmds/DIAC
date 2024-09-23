
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This controller is for refferal and request
 * Previously known as miscellaneous model
 * Table Name: miscellaneous_tbl
 */
class Refferal_request_model extends CI_Model
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


    public function store($data)
    {
        $result = $this->db->insert('miscellaneous_tbl', $data);
        return $result;
    }

    public function update($data, $condition)
    {
        $result = $this->db->where($condition)->update('miscellaneous_tbl', $data);
        return $result;
    }

    public function getHighestDiaryNumber()
    {
        $query = $this->db->select_max('diary_number_seq')
            ->get('cs_diary_numbers_tbl');
        $maxValue = $query->row()->diary_number_seq;
        return $maxValue;
    }

    public function getHighestCode()
    {
        $query = $this->db->select_max('m_code')
            ->get('miscellaneous_tbl');
        $maxValue = $query->row()->m_code;
        return $maxValue;
    }

    public function getRefferalReqDataUsingCode($code)
    {
        $this->db->select("m.*, cdt.*, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, gc4.description as arbitrator_status_desc, gc5.description as di_type_of_arbitration_desc, comt.court_name");
        $this->db->from('miscellaneous_tbl as m');
        $this->db->join('cs_case_details_tbl as cdt', 'cdt.reference_code = m.m_code', 'left');
        $this->db->join('courts_master_tbl as comt', 'comt.code = cdt.reffered_by_court', 'left');

        $this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
        $this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');
        $this->db->join('gen_code_desc as gc4', 'gc4.gen_code = cdt.arbitrator_status AND gc4.gen_code_group = "ARBITRATOR_STATUS"', 'left');
        $this->db->join('gen_code_desc as gc5', 'gc5.gen_code = cdt.di_type_of_arbitration AND gc5.gen_code_group = "DI_TYPE_OF_ARBITRATION"', 'left');


        $this->db->where('m.m_code', $code);
        $this->db->where('m.record_status', 1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert_claimant_details($rowCountArray, $data, $case_code, $type, $operation = 'ADD')
    {
        if ($operation == "UPDATE") {
            $claimants_temp_count = $this->db->from('cs_claimant_respondant_details_tbl')->where([
                'case_no' => $case_code,
                'status' => 1,
                'type' => 'claimant'
            ])->count_all_results();
            $claimants_count = $claimants_temp_count + 1;
        }

        if (count($rowCountArray) > 0) {
            $cl_data = [];
            foreach ($rowCountArray as $key => $row) {
                if (!empty($data['cl_name_' . $row]) || !empty($data['cl_address_one_' . $row]) || !empty($data['cl_email_' . $row]) || !empty($data['cl_mobile_number_' . $row]) || !empty($data['cl_pincode_' . $row])) {

                    if ($operation == 'ADD') {
                        $count_number = $key + 1;
                    } else {
                        $count_number = $claimants_count;
                    }

                    array_push($cl_data, array(
                        'code' => generateCode(),
                        'case_no' => $case_code,
                        'type' => $type,
                        'name' => $this->security->xss_clean($data['cl_name_' . $row]),
                        'count_number' => $count_number,

                        'perm_address_1' => $this->security->xss_clean($data['cl_address_one_' . $row]),
                        'perm_address_2' => $this->security->xss_clean($data['cl_address_two_' . $row]),
                        'perm_country' => $this->security->xss_clean($data['cl_country_' . $row]),
                        'perm_state' => $this->security->xss_clean($data['cl_state_' . $row]),
                        'perm_city' => $this->security->xss_clean($data['cl_city_' . $row]),
                        'perm_pincode' => $this->security->xss_clean($data['cl_pincode_' . $row]),

                        'email' => $this->security->xss_clean($data['cl_email_' . $row]),
                        'contact' => $this->security->xss_clean($data['cl_mobile_number_' . $row]),

                        'created_by' => $this->session->userdata('user_code'),
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp()
                    ));

                    if ($operation == "UPDATE") {
                        $count_number++;
                    }
                }
            }
            if (count($cl_data) > 0) {
                $result = $this->db->insert_batch('cs_claimant_respondant_details_tbl', $cl_data);
                if (!$result) {
                    $this->db->trans_rollback();
                    return array(
                        'status' => false,
                        'msg' => 'Error while saving claimant details. Please try again.'
                    );
                } else {
                    return array(
                        'status' => true
                    );
                }
            } else {
                return array(
                    'status' => true
                );
            }
        }
    }

    public function insert_respondant_details($rowCountArray, $data, $case_code, $type, $operation = 'ADD')
    {
        if ($operation == "UPDATE") {
            $claimants_temp_count = $this->db->from('cs_claimant_respondant_details_tbl')->where([
                'case_no' => $case_code,
                'status' => 1,
                'type' => 'respondant'
            ])->count_all_results();
            $claimants_count = $claimants_temp_count + 1;
        }

        if (count($rowCountArray) > 0) {
            $res_data = [];
            foreach ($rowCountArray as $key => $row) {
                if (!empty($data['res_name_' . $row]) || !empty($data['res_address_one_' . $row]) || !empty($data['res_email_' . $row]) || !empty($data['res_mobile_number_' . $row]) || !empty($data['res_pincode_' . $row])) {

                    if ($operation == 'ADD') {
                        $count_number = $key + 1;
                    } else {
                        $count_number = $claimants_count;
                    }

                    array_push($res_data, array(
                        'code' => generateCode(),
                        'case_no' => $case_code,
                        'type' => $type,
                        'name' => $this->security->xss_clean($data['res_name_' . $row]),
                        'count_number' => $count_number,

                        'perm_address_1' => $this->security->xss_clean($data['res_address_one_' . $row]),
                        'perm_address_2' => $this->security->xss_clean($data['res_address_two_' . $row]),
                        'perm_country' => $this->security->xss_clean($data['res_country_' . $row]),
                        'perm_state' => $this->security->xss_clean($data['res_state_' . $row]),
                        'perm_city' => $this->security->xss_clean($data['res_city_' . $row]),
                        'perm_pincode' => $this->security->xss_clean($data['res_pincode_' . $row]),

                        'email' => $this->security->xss_clean($data['res_email_' . $row]),
                        'contact' => $this->security->xss_clean($data['res_mobile_number_' . $row]),

                        'created_by' => $this->session->userdata('user_code'),
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->session->userdata('user_code'),
                        'updated_on' => currentDateTimeStamp()
                    ));

                    if ($operation == "UPDATE") {
                        $count_number++;
                    }
                }
            }
            if (count($res_data) > 0) {
                $result = $this->db->insert_batch('cs_claimant_respondant_details_tbl', $res_data);
                if (!$result) {
                    $this->db->trans_rollback();
                    return array(
                        'status' => false,
                        'msg' => 'Error while saving respondant details. Please try again.'
                    );
                } else {
                    return array(
                        'status' => true
                    );
                }
            } else {
                return array(
                    'status' => true
                );
            }
        }
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
