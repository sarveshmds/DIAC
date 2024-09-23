<?php
class Ims_efiling_model extends CI_model
{
    function __construct()
    {
        parent::__construct();
        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }

        $this->role         = $this->session->userdata('role');
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
        $this->sess_id         = $this->session->userdata('sess_id');
    }

    public function check_noting_availability($noting_group, $type_code, $type_name)
    {
        return $this->db->from('other_notings_tbl')
            ->where([
                'noting_group' => $noting_group,
                'type_code' => $type_code,
                'type_name' => $type_name,
                'record_status' => 1,
            ])->count_all_results();
    }

    public function insert_other_noting($noting_group, $type_code, $type_name)
    {
        $result =  $this->db->insert('other_notings_tbl', [
            'code' => generateCode(),
            'noting_group' => $noting_group,
            'type_code' => $type_code,
            'type_name' => $type_name,
            'record_status' => 1,
            'created_by' => $this->user_code,
            'created_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
        ]);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function fetch_other_noting_using_types($noting_group, $type_code, $type_name)
    {
        return $this->db->from('other_notings_tbl')
            ->where([
                'noting_group' => $noting_group,
                'type_code' => $type_code,
                'type_name' => $type_name,
                'record_status' => 1,
            ])->get()->row_array();
    }

    public function get_all_other_noting_messages_datatable($on_code)
    {

        // $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("ont.*, um.user_display_name as sent_by_desc, um2.user_display_name as sent_to_desc");
        $this->db->from('other_notings_messages_tbl AS ont');
        $this->db->join('user_master as um', 'um.user_code = ont.sent_by', 'left');
        $this->db->join('user_master as um2', 'um2.user_code = ont.sent_to', 'left');

        $this->db->where('ont.ont_code', $on_code);
        $this->db->where('ont.record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('ont.id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('other_notings_messages_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        return $output;
    }

    public function insert_other_noting_message()
    {
        $result =  $this->db->insert('other_notings_messages_tbl', [
            'ont_code' => $this->input->post('hidden_on_code'),
            'message' => $this->input->post('message_text'),
            'message_date' => currentDateTimeStamp(),
            'is_readed' => 0,
            'sent_by' => $this->user_code,
            'sent_to' => $this->input->post('send_to'),
            'record_status' => 1,
            'created_by' => $this->user_code,
            'created_at' => currentDateTimeStamp(),
            'updated_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
        ]);
        if (!$result) {
            return false;
        }

        return true;
    }
}
