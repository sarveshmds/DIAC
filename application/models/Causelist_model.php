
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Causelist_model extends CI_Model
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
        $this->user_name     = $this->session->userdata('user_name');
        $this->user_code     = $this->session->userdata('user_code');
    }


    public function get($data, $op)
    {
        switch ($op) {

            case 'ALL_ROOMS_LIST':
                $q = $this->db->from('rooms_tbl')
                    ->where('active_status', 1)
                    ->order_by('room_no', 'ASC')
                    ->get();
                return $q->result_array();
                break;

            case 'FETCH_HEARINGS_LIST':
                $this->db->select("clt.id, clt.slug,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing, clt.hearing_meeting_link");
                $this->db->from('cause_list_tbl AS clt');
                $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
                $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
                $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug  AND cat.status = 1', 'left');
                $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

                $this->db->where('clt.active_status !=', 0);

                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.case_no', $case_no);
                }

                // If date is set
                if (isset($_POST['date']) && !empty($_POST['date'])) {
                    $date = $this->security->xss_clean($_POST['date']);
                    $this->db->where('clt.date', date('Y-m-d', strtotime($date)));
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->order_by('rt.room_no', 'asc');
                $this->db->order_by('clt.time_from', 'asc');

                $this->db->group_by('clt.id');
                $query = $this->db->get();

                return $query->result_array();
                break;

            case 'ALL_CAUSE_LIST':

                $select_column = array("clt.case_no", "arbitrator_name", "title_of_case", "date", "time_from", "time_to", "purpose_cat_id", "room_no_id", "clt.remarks");
                $order_column = array(null, "clt.case_no", "title_of_case", "arbitrator_name", "purpose_cat_id", "date", "time_from", "time_to", null, null, null);

                $this->db->select("clt.id, clt.slug,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, amt.name_of_arbitrator as arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing, clt.hearing_meeting_link");
                $this->db->from('cause_list_tbl AS clt');
                $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
                $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
                $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug  AND cat.status = 1', 'left');
                $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

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
                    $this->db->order_by('clt.id', 'DESC');
                }

                $this->db->where('clt.active_status !=', 0);

                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.slug', $case_no);
                }

                // If date is set
                if (isset($_POST['date']) && !empty($_POST['date'])) {
                    $date = $this->security->xss_clean($_POST['date']);
                    $this->db->where('clt.date', date('Y-m-d', strtotime($date)));
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->group_by('clt.id');
                $tempdb = clone $this->db;

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $query = $this->db->get();

                $fetch_data = $query->result();

                // Filter records =======================================================
                $recordsFiltered = $tempdb->count_all_results();

                // Records total ==================================================
                $recordsTotal = $this->db->where('active_status !=', 0)->select("id")->from('cause_list_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );
                return $output;
                break;

            case 'USER_TODAYS_CAUSE_LIST':

                $case_no_array = [];
                $alloted_cases = $this->common_model->get_all_alloted_cases();
                if (count($alloted_cases) > 0) {
                    $case_no_array = array_column($alloted_cases, 'case_no');
                }

                $select_column = array("clt.case_no", "arbitrator_name", "title_of_case", "date", "time_from", "time_to", "purpose_cat_id", "room_no_id", "clt.remarks");
                $order_column = array(null, "clt.case_no", "title_of_case", "arbitrator_name", "purpose_cat_id", "date", "time_from", "time_to", null, null, null);

                $this->db->select("clt.id, clt.slug, clt.date,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing, clt.hearing_meeting_link");
                $this->db->from('cause_list_tbl AS clt');
                $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
                $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
                $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug AND cat.status = 1', 'left');
                $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

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
                    $this->db->order_by('clt.id', 'DESC');
                }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $this->db->where('clt.active_status !=', 0);

                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.slug', $case_no);
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->where('clt.date', date('Y-m-d'));
                $this->db->where_in('clt.slug', $case_no_array);

                $this->db->group_by('clt.id');
                $query = $this->db->get();

                $fetch_data = $query->result();

                // Filter records =======================================================
                $this->db->select("id");
                $this->db->from('cause_list_tbl as clt');
                $this->db->where('clt.active_status !=', 0);
                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.case_no', $case_no);
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->where('clt.date', date('Y-m-d'));
                $this->db->where_in('clt.slug', $case_no_array);

                $recordsFiltered = $this->db->count_all_results();

                // Records total ==================================================
                $recordsTotal = $this->db->where('active_status !=', 0)->where('date', date('Y-m-d'))->where_in('slug', $case_no_array)->select("id")->from('cause_list_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );
                return $output;
                break;

            case 'USER_ALL_CAUSE_LIST':

                $case_no_array = [];
                $alloted_cases = $this->common_model->get_all_alloted_cases();
                if (count($alloted_cases) > 0) {
                    $case_no_array = array_column($alloted_cases, 'case_no');
                }

                $select_column = array("clt.case_no", "arbitrator_name", "title_of_case", "date", "time_from", "time_to", "purpose_cat_id", "room_no_id", "clt.remarks");
                $order_column = array(null, "clt.case_no", "title_of_case", "arbitrator_name", "purpose_cat_id", "date", "time_from", "time_to", null, null, null);

                $this->db->select("clt.id, clt.slug,clt.date, clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing, clt.hearing_meeting_link");
                $this->db->from('cause_list_tbl AS clt');
                $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
                $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
                $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug AND cat.status = 1', 'left');
                $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

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
                    $this->db->order_by('clt.id', 'DESC');
                }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $this->db->where('clt.active_status !=', 0);

                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.slug', $case_no);
                }

                // If date is set
                if (isset($_POST['date']) && !empty($_POST['date'])) {
                    $date = $this->security->xss_clean($_POST['date']);
                    $this->db->where('clt.date', date('Y-m-d', strtotime($date)));
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->where_in('clt.slug', $case_no_array);

                $this->db->group_by('clt.id');
                $query = $this->db->get();

                $fetch_data = $query->result();

                // Filter records =======================================================
                $this->db->select("id");
                $this->db->from('cause_list_tbl as clt');
                $this->db->where('clt.active_status !=', 0);
                // If case no is set
                if (isset($_POST['case_no']) && !empty($_POST['case_no'])) {
                    $case_no = $this->security->xss_clean($_POST['case_no']);
                    $this->db->where('clt.case_no', $case_no);
                }

                // If date is set
                if (isset($_POST['date']) && !empty($_POST['date'])) {
                    $date = $this->security->xss_clean($_POST['date']);
                    $this->db->where('clt.date', $date);
                }

                // If time is set
                if (isset($_POST['time_from']) && !empty($_POST['time_from'])) {
                    $time_from = $this->security->xss_clean($_POST['time_from']);
                    $this->db->where('clt.time_from', $time_from);
                }
                if (isset($_POST['time_to']) && !empty($_POST['time_to'])) {
                    $time_to = $this->security->xss_clean($_POST['time_to']);
                    $this->db->where('clt.time_to', $time_to);
                }

                // If room number is set
                if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
                    $room_no_id = $this->security->xss_clean($_POST['room_no']);
                    $this->db->where('clt.room_no_id', $room_no_id);
                }

                $this->db->where_in('clt.slug', $case_no_array);
                $recordsFiltered = $this->db->count_all_results();

                // Records total ==================================================
                $recordsTotal = $this->db->where('active_status !=', 0)->where_in('slug', $case_no_array)->select("id")->from('cause_list_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );
                return $output;
                break;

            case 'GET_DISPLAY_BOARD_LIST':

                $todays_date = date('Y-m-d');
                $select_column = array("room_no");
                $order_column = array(null, null, "room_no", "room_name", null);

                $this->db->select("cdb.id as cdb_id, cdb.case_no, cdb.arbitrator_name, cdb.room_status, cdb.todays_date, rt.room_name, rt.room_no, cdb.room_id, rt.id as rt_id");
                $this->db->from('rooms_tbl as rt');
                $this->db->join("(SELECT * FROM cs_display_board_tbl WHERE todays_date = '$todays_date') as cdb", 'cdb.room_id = rt.id', 'left');
                $this->db->where('rt.active_status', 1);

                if (isset($_POST["search"]["value"])) {
                    $count = 1;
                    $search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

                    $like_clause = "(";
                    foreach ($select_column as $sc) {
                        if ($count == 1) {
                            $like_clause .= 'rt.' . $sc . " LIKE '%" . $search_value . "%'";
                        } else {
                            $like_clause .= " OR " . 'rt.' . $sc . " LIKE '%" . $search_value . "%'";
                        }
                        $count++;
                    }
                    $like_clause .= ")";
                    $this->db->where($like_clause);
                }

                if (isset($_POST["order"])) {
                    $this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
                } else {
                    $this->db->order_by('rt.room_no', 'ASC');
                }

                $tempdb = $this->db;

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $query = $this->db->get();
                // echo $this->db->last_query();
                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = $tempdb->count_all_results();

                // Records total
                $recordsTotal = $this->db->select("*")->from('cs_display_board_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );
                return $output;
                break;

            case 'HEARINGS_TODAY_LIST':

                $select_column = array("clt.case_no",  "title_of_case", "clt.arbitrator_name", "date", "time_from", "rt.room_no");
                $order_column = array(null, "clt.case_no", "title_of_case", "clt.arbitrator_name", null, "date", "time_from", "rt.room_no", null);


                $this->db->select("clt.id, clt.slug,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator");
                $this->db->from('cause_list_tbl AS clt');
                $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
                $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
                $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
                $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug AND cat.status = 1', 'left');
                $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

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
                    $this->db->order_by('time_from', 'ASC');
                }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $this->db->where('clt.date', date('Y-m-d'));
                $this->db->where('clt.active_status', 1);

                $this->db->group_by('clt.id');
                $query = $this->db->get();

                // print_r($this->db->last_query()); 

                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = $this->db->where('date', date('Y-m-d'))->where('active_status', 1)->select("*")->from('cause_list_tbl')->count_all_results();

                // Records total
                $recordsTotal = $this->db->where('date', date('Y-m-d'))->where('active_status', 1)->select("*")->from('cause_list_tbl')->count_all_results();

                // Output
                $output = array(
                    "draw" => intval($_POST['draw']),
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $fetch_data
                );
                return $output;
                break;

            case 'get_all_cases_list':
                $this->db->select('*');
                $this->db->from('cs_case_details_tbl');
                $this->db->where('status', 1);
                return $this->db->get()->result_array();
                break;

            case 'get_single_case_details':
                $this->db->select('*');
                $this->db->from('cs_case_details_tbl');
                $this->db->where('slug', $data);
                $this->db->where('status', 1);
                return $this->db->get()->row_array();
                break;

            case 'get_arbitrators_list':
                $this->db->select('mat.code,mat.name_of_arbitrator,pnt.category_name');
                $this->db->from('master_arbitrators_tbl mat');
                $this->db->join('panel_category_tbl as pnt', 'pnt.code = mat.category', 'left');
                $this->db->where('mat.record_status', 1);
                return $this->db->get()->result_array();
                break;


            default:
                return array('status' => false, 'msg' => NO_OPERATION);
        }
    }

    public function post($data, $op)
    {
        switch ($op) {

            case 'ADD_ROOM':

                $this->form_validation->set_rules('room_name', 'Room Name', 'required|regex_match[/^[a-zA-Z\s\']+$/]|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room Number', 'required|regex_match[/^[0-9]+$/]|numeric|xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();
                    try {
                        $room_name = $this->security->xss_clean($data['room_name']);

                        $insert_data = array(
                            'room_name' => $room_name,
                            'room_no' => $this->security->xss_clean($data['room_no']),
                            'created_at' => $this->date
                        );

                        $result = $this->db->insert('rooms_tbl', $insert_data);
                        if ($result) {

                            $table_id = $this->db->insert_id();

                            // Insert room id into display board table
                            $insert_display_board_data = array(
                                'room_id' => $table_id,
                                'room_status' => 'Not In Session',
                                'created_by' => $this->user_code,
                                'created_at' => $this->date
                            );

                            $display_board_result = $this->db->insert('cs_display_board_tbl', $insert_display_board_data);

                            if ($display_board_result) {
                                // Update the data logs table for data tracking
                                $table_name = 'rooms_tbl';
                                $message = 'A new room ' . $room_name . ' is added in rooms list.';
                                $this->common_model->update_data_logs($table_name, $table_id, $message);

                                $this->db->trans_commit();
                                $dbstatus = true;
                                $dbmessage = "Record saved successfully";
                            } else {
                                $this->db->trans_rollback();
                                $dbstatus = false;
                                $dbmessage = "Error while saving  data. Please contact support.";
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

            case 'EDIT_ROOM':

                $this->form_validation->set_rules('room_name', 'Room Name', 'required|regex_match[/^[a-zA-Z\s\']+$/]|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room Number', 'required|numeric|regex_match[/^[0-9]+$/]|xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();
                    try {

                        $room_name = $this->security->xss_clean($data['room_name']);

                        $update_data = array(
                            'room_name' => $room_name,
                            'room_no' => $this->security->xss_clean($data['room_no']),
                            'updated_at' => $this->date
                        );

                        $id = $this->security->xss_clean($data['hidden_id']);
                        $result = $this->db->where('id', $id)->update('rooms_tbl', $update_data);
                        if ($result) {

                            // Update the data logs table for data tracking
                            $table_name = 'rooms_tbl';
                            $table_id = $id;
                            $message = 'Details of room ' . $room_name . ' is updated.';
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

            case 'DELETE_ROOM':
                $this->db->trans_begin();
                $this->form_validation->set_rules('id', 'Room Id', 'required');

                if ($this->form_validation->run()) {
                    $id = $this->security->xss_clean($data['id']);
                    $r = $this->db->where('id', $id)->update('rooms_tbl', array('active_status' => 0));

                    if ($r) {

                        // Delete room from display board table also
                        $delete_dbr_result = $this->db->from('cs_display_board_tbl')->where('room_id', $id)->delete();
                        if ($delete_dbr_result) {
                            // Update the data logs table for data tracking
                            $data = $this->db->select('id, room_name')->from('rooms_tbl')->where('id', $id)->get()->row_array();
                            $table_name = 'rooms_tbl';
                            $table_id = $id;
                            $message = 'Room ' . $data['room_name'] . ' is deleted.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);

                            $this->db->trans_commit();

                            $dbstatus = TRUE;
                            $dbmessage = 'Record deleted successfully';
                        } else {
                            $this->db->trans_rollback();
                            $dbstatus = FALSE;
                            $dbmessage = 'Error while saving data. Please try again.';
                        }
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

            case 'ADD_CAUSE_LIST_FORM':
                $this->form_validation->set_rules('case_list', 'Case List', 'required|xss_clean');
                $this->form_validation->set_rules('case_no', 'Case number', 'required|xss_clean');
                $this->form_validation->set_rules('title_of_case', 'Title of case', 'required|xss_clean|trim');
                // $this->form_validation->set_rules('arbitrator_name', 'Arbitrator name', 'required|xss_clean|trim');
                $this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
                $this->form_validation->set_rules('mode_of_hearing', 'Mode of hearing', 'required|xss_clean');
                $this->form_validation->set_rules('time_from', 'Time (From)', 'xss_clean');
                $this->form_validation->set_rules('time_to', 'Time (To)', 'xss_clean');
                $this->form_validation->set_rules('purpose', 'Purpose', 'required|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room number', 'xss_clean');
                $this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean');

                if (in_array($data['mode_of_hearing'], ['ONLINE', 'HYBRID'])) {
                    $this->form_validation->set_rules('hearing_meeting_link', 'Hearing meeting link', 'required|xss_clean');
                }

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();
                    try {

                        $case_no = $this->security->xss_clean($this->input->post('case_no'));
                        $case_no_slug = $this->input->post('case_list');

                        // check if the hearing is scheduled for the same date
                        $check_dup_hearing_result = $this->db->select('*')->where([
                            'slug' => $case_no_slug,
                            'date' => date('Y-m-d', strtotime($data['date'])),
                            'active_status' => 1
                        ])->from('cause_list_tbl')->get()->result_array();

                        if (count($check_dup_hearing_result) > 0) {
                            failure_response('Hearing is already scheduled for the same date.', false, true);
                            die;
                        }

                        // Check if the arbitrator is added or not
                        // ================================================
                        // Get arbitrators of the case
                        $check_case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);
                        if (count($check_case_arbitrators) < 1) {
                            failure_response('Arbitrator is not appointed in the case. Appoint arbitrator to schedule the hearing.', false, true);
                            die;
                        }

                        $date = date('Y-m-d', strtotime($data['date']));
                        $mode_of_hearing = $data['mode_of_hearing'];
                        $time_from = date('H:i', strtotime($data['time_from']));
                        $time_to = date('H:i', strtotime($data['time_to']));

                        $room = $this->security->xss_clean($data['room_no']);

                        // Check for room availablity
                        if (in_array($mode_of_hearing, ['PHYSICAL', 'HYBRID'])) {
                            // check hearing room is available or not
                            $check = $this->db->select('*')
                                ->from('cause_list_tbl')
                                ->where('date', $date)
                                ->where("time_from" >= $time_from and "time_to" <= $time_to)
                                ->where('room_no_id', $room)
                                ->get()
                                ->num_rows();

                            if ($check > 0) {
                                $dbstatus = false;
                                $dbmessage = "Hearing room is not available.";
                                return array('status' => $dbstatus, 'msg' => $dbmessage);
                            }
                        }

                        // Check, more than 18 hearings should not be schedules at the same date and time slots
                        // check hearing room is available or not
                        $checkTotalHearings = $this->db->select('*')
                            ->from('cause_list_tbl')
                            ->where('date', $date)
                            ->where("time_from" >= $time_from and "time_to" <= $time_to)
                            ->get()
                            ->num_rows();

                        if ($checkTotalHearings > 17) {
                            $dbstatus = false;
                            $dbmessage = "No more hearings can be scheduled for this date and time slot.";
                            return array('status' => $dbstatus, 'msg' => $dbmessage);
                        }

                        // ===============================================
                        // ===============================================

                        $insert_data = array(
                            // 'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
                            'date' => $this->security->xss_clean(date('Y-m-d', strtotime($data['date']))),
                            'time_from' => $time_from,
                            'time_to' => $time_to,
                            'purpose_cat_id' => $this->security->xss_clean($data['purpose']),
                            'room_no_id' => '',
                            'mode_of_hearing' => $mode_of_hearing,
                            'hearing_meeting_link' => '',
                            'remarks' => $this->security->xss_clean($data['remarks']),
                            'created_at' => $this->date,
                            'created_by' => $this->user_code
                        );

                        $insert_data['case_no'] = $case_no;
                        $insert_data['slug'] = $this->security->xss_clean($this->input->post('case_list'));
                        $insert_data['title_of_case'] = $this->security->xss_clean($data['title_of_case']);

                        if (in_array($mode_of_hearing, ['PHYSICAL', 'HYBRID'])) {
                            $insert_data['room_no_id'] = $this->security->xss_clean($data['room_no']);
                        }

                        if (in_array($mode_of_hearing, ['HYBRID', 'ONLINE'])) {
                            $insert_data['hearing_meeting_link'] = $this->security->xss_clean($data['hearing_meeting_link']);
                        }

                        $hearing_data = $insert_data;

                        // $this->db->select('*');
                        // $this->db->from('cause_list_tbl');
                        // $this->db->where('date', $this->security->xss_clean($data['date']));
                        // $this->db->where("time_from" >= $this->security->xss_clean($data['time_from']) AND "time_to" <= $this->security->xss_clean($data['time_to']));
                        // $this->db->where('room_no_id', $this->security->xss_clean($data['room_no']));
                        // $check = $this->db->get();

                        // if ($check->num_rows() > 0) {
                        //     $dbstatus = false;
                        //     $dbmessage = "Hearing room is not available.";
                        // }

                        $result = $this->db->insert('cause_list_tbl', $insert_data);
                        if ($result) {

                            $cases_slugs_list = [$case_no_slug];

                            // ===========================================
                            // Get the tagged matters

                            $tagged_cases = $this->tag_cases_model->get_tagged_case_details_using_master_case($case_no_slug);
                            if (count($tagged_cases) > 0) {
                                // Insert the hearing for the tagged cases
                                foreach ($tagged_cases as $key => $tc) {
                                    $insert_data['case_no'] = $tc['tagged_case_no_prefix'] . '/' . $tc['tagged_case_no'] . '/' . $tc['tagged_case_no_year'];
                                    $insert_data['slug'] = $tc['tagged_case'];
                                    $insert_data['title_of_case'] = $tc['tagged_case_title'];

                                    $result = $this->db->insert('cause_list_tbl', $insert_data);

                                    if (!$result) {
                                        // Commit the changes
                                        failure_response('Error while saving records in tagged cases for hearing.', false, true);
                                        die;
                                    }

                                    array_push($cases_slugs_list, $tc['tagged_case']);
                                }
                            }

                            // ===========================================
                            $table_id = $this->db->insert_id();

                            // Update the data logs table for data tracking
                            $table_name = 'cause_list_tbl';
                            $message = 'A new cause list for case ' . $case_no . ' is added.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);


                            // ===========================================
                            // Get the room details
                            $room_info = $this->common_model->get_room_info_using_id($data['room_no']);

                            $email_error_count = 0;

                            // print_r($cases_slugs_list);
                            // die;

                            if (count($cases_slugs_list) > 0) {
                                foreach ($cases_slugs_list as $key => $case_no_slug) {
                                    # code...
                                    // Loop through the case and send the email and messages
                                    $case_details_data = $this->getter_model->get([
                                        'case_slug' => $case_no_slug
                                    ], 'GET_CASE_DETAILS');


                                    // ================================================
                                    // Send the email notification to everyone.
                                    // ============================================
                                    // Get the claimant and respondent data
                                    $case_claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, CLAIMANT_TYPE_CONSTANT);
                                    $claimants = array_filter($case_claimants, function ($claimant) {
                                        return !empty($claimant['email']);
                                    });
                                    $claimants_emails_temp = array_column($claimants, 'email');
                                    // $claimants_emails = implode(',', $claimants_emails_temp);

                                    // Phone Number
                                    $claimants_for_phone = array_filter($case_claimants, function ($claimant) {
                                        return !empty($claimant['contact']);
                                    });
                                    $claimants_phone_temp = array_column($claimants_for_phone, 'contact');

                                    // ============================================
                                    // Get the COUNSELS details of claimants
                                    $case_claimants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, CLAIMANT_TYPE_CONSTANT);

                                    $claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
                                        return !empty($counsel['email']);
                                    });
                                    $claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

                                    // Phone Number
                                    $claimant_counsels_for_phone = array_filter($case_claimants_counsels, function ($counsel) {
                                        return !empty($counsel['phone_number']);
                                    });
                                    $claimant_counsels_phone_temp = array_column($claimant_counsels_for_phone, 'phone_number');

                                    // ================================================
                                    // Respondents =====================
                                    $case_respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, RESPONDANT_TYPE_CONSTANT);
                                    $respondants = array_filter($case_respondants, function ($claimant) {
                                        return !empty($claimant['email']);
                                    });

                                    $respondants_emails_temp = array_column($respondants, 'email');
                                    // $respondants_emails = implode(',', $respondants_emails_temp);

                                    // Phone Number
                                    $respondants_for_phone = array_filter($case_respondants, function ($claimant) {
                                        return !empty($claimant['contact']);
                                    });
                                    $respondants_phone_temp = array_column($respondants_for_phone, 'contact');

                                    // ============================================
                                    // Get the COUNSELS details of claimants
                                    $case_respondants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, RESPONDANT_TYPE_CONSTANT);

                                    $respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
                                        return !empty($counsel['email']);
                                    });
                                    $respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

                                    // Phone Number
                                    $respondant_counsels_for_phone = array_filter($case_respondants_counsels, function ($counsel) {
                                        return !empty($counsel['phone_number']);
                                    });
                                    $respondant_counsels_phone_temp = array_column($respondant_counsels_for_phone, 'phone_number');

                                    // ================================================
                                    // Get arbitrators of the case
                                    $case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);

                                    // Email
                                    $arbitrators = array_filter($case_arbitrators, function ($arbitrator) {
                                        return !empty($arbitrator['email']);
                                    });
                                    $arbitrators_emails_temp = array_column($arbitrators, 'email');
                                    // $arbitrators_emails = implode(',', $arbitrators_emails_temp);

                                    // Phone Number
                                    $arbitrators_for_phone = array_filter($case_arbitrators, function ($arbitrator) {
                                        return !empty($arbitrator['contact_no']);
                                    });
                                    $arbitrators_phone_temp = array_column($arbitrators_for_phone, 'contact_no');

                                    // ================================================
                                    // Fetch all users allocated in the case
                                    // ================================================
                                    $users = $this->common_model->get_all_users_alloted_to_case($case_no_slug);

                                    if ($users && count($users) < 1) {
                                        failure_response('No users are not allocated', true, true);
                                    }

                                    // Fetch deputy counsel details
                                    $deputy_counsel_details = $this->common_model->get_deputy_counsel_alloted_to_case($case_no_slug);
                                    $deputy_counsel_smtp_email = (isset($deputy_counsel_details['email']) && !empty($deputy_counsel_details['email'])) ? $deputy_counsel_details['email'] : DEFAULT_SMTP_EMAIL_ID;

                                    // Email
                                    $users = array_filter($users, function ($user) {
                                        return !empty($user['email']);
                                    });
                                    $users_emails_temp = array_column($users, 'email');

                                    // Phone Number
                                    $users_for_phone = array_filter($users, function ($user) {
                                        return !empty($user['phone_number']);
                                    });
                                    $users_phone_temp = array_column($users_for_phone, 'phone_number');

                                    // Merge all email arrays into a single array
                                    $all_emails = array_merge(
                                        $claimants_emails_temp,
                                        $claimant_counsels_emails_temp,
                                        $respondants_emails_temp,
                                        $respondant_counsels_emails_temp,
                                        $arbitrators_emails_temp,
                                        $users_emails_temp
                                    );

                                    // Implode the merged array with commas
                                    $all_emails_string = implode(',', $all_emails);

                                    // =====================================================
                                    // =====================================================

                                    $additional_phones = [];
                                    // Merge all email arrays into a single array
                                    $all_phone_numbers = array_merge(
                                        $claimants_phone_temp,
                                        $claimant_counsels_phone_temp,
                                        $respondants_phone_temp,
                                        $respondant_counsels_phone_temp,
                                        $arbitrators_phone_temp,
                                        $users_phone_temp
                                    );

                                    // Implode the merged array with commas
                                    // print_r($all_phone_numbers);
                                    if (count($all_phone_numbers) > 0) {
                                        $full_case_no = get_full_case_number($case_details_data);

                                        foreach ($all_phone_numbers as $key => $phone_number) {
                                            // ======================================================
                                            // SEND THE WHATSAPP MESSAGE WHO ARE IN THE CASE
                                            $whatsapp_result = wa_case_hearing_notification($phone_number, $full_case_no, $case_details_data, $hearing_data);

                                            // Insert WhatsApp Log Data
                                            $insert_whatsapp_log = [
                                                "transaction_id"     => $whatsapp_result['template_id'],
                                                'type'               => 'HEARING_SCHEDULED',
                                                "phone_number"       => $phone_number,
                                                "message"            => $whatsapp_result['msg'],
                                                "status"             => ($whatsapp_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                                "created_by"         => $this->session->userdata('user_code'),
                                                "created_at"         => currentDateTimeStamp()
                                            ];
                                            $result_whatsapp = $this->db->insert('support_whatsapp_record', $insert_whatsapp_log);
                                        }
                                    }

                                    // ========================================================
                                    // Create an array of all the persons who are involved in the case
                                    $persons = [
                                        'claimants' => $case_claimants,
                                        'case_claimants_counsels' => $case_claimants_counsels,
                                        'respondants' => $case_respondants,
                                        'case_respondants_counsels' => $case_respondants_counsels,
                                        'arbitrators' => $case_arbitrators,
                                        'users' => $users,
                                    ];

                                    // Load the data
                                    $view_data['persons'] = $persons;
                                    $view_data['case_details_data'] = $case_details_data;
                                    $view_data['hearing_data'] = $hearing_data;
                                    $view_data['room_info'] = $room_info;

                                    // Send emails to everyone
                                    $subject = 'Hearing scheduled for case number: ' . get_full_case_number($case_details_data) . ' (' . $case_details_data['case_title'] . ')';

                                    $email_html_body = $this->load->view('emails/hearing_notice', $view_data, true);

                                    $email_send_result = hearing_notice_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);

                                    // Update the error log count
                                    if ($email_send_result['status'] == false) {
                                        $email_error_count += 1;
                                    }

                                    // Email log data
                                    $insert_mail_log = [
                                        "transaction_id"     => $case_no_slug,
                                        'mail_type'         => 'HEARING_SCHEDULED',
                                        "to_mail"             => strtolower($all_emails_string),
                                        "subject"             => $subject,
                                        "body"                 => $email_html_body,
                                        "status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                        "created_by"         => $this->session->userdata('user_code'),
                                        "created_at"         => currentDateTimeStamp()
                                    ];
                                    $result_mail = $this->db->insert('support_mail_record', $insert_mail_log);
                                }
                            }


                            if ($email_error_count > 0) {
                                // ============================================
                                // Commit the changes with the send email failed error
                                success_response('Hearing scheduled successfully  but email sending in ' . $email_error_count . ' cases failed due to SMTP server availability. Please check log for more information.', false, true);
                            } else {
                                // ============================================
                                // Commit the changes
                                success_response('Hearing scheduled successfully and email sent to everyone related to case.', false, true);
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

            case 'EDIT_CAUSE_LIST_FORM':
                $this->form_validation->set_rules('case_list', 'Case List', 'required|xss_clean');
                $this->form_validation->set_rules('case_no', 'Case number', 'required|xss_clean');
                $this->form_validation->set_rules('title_of_case', 'Title of case', 'required|xss_clean|trim');
                $this->form_validation->set_rules('arbitrator_name', 'Arbitrator name', 'required|xss_clean|trim');
                $this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
                $this->form_validation->set_rules('time_from', 'Time (From)', 'xss_clean');
                $this->form_validation->set_rules('time_to', 'Time (To)', 'xss_clean');
                $this->form_validation->set_rules('purpose', 'Purpose', 'required|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room number', 'xss_clean');
                $this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();
                    try {
                        $id = $this->security->xss_clean($data['hidden_id']);
                        $case_no = $this->security->xss_clean($data['case_no']);

                        $update_data = array(
                            'case_no' => $case_no,
                            'slug' => $this->security->xss_clean($data['case_list']),
                            'title_of_case' => $this->security->xss_clean($data['title_of_case']),
                            'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
                            'date' => $this->security->xss_clean($data['date']),
                            'time_from' => $this->security->xss_clean($data['time_from']),
                            'time_to' => $this->security->xss_clean($data['time_to']),
                            'purpose_cat_id' => $this->security->xss_clean($data['purpose']),
                            'room_no_id' => $this->security->xss_clean($data['room_no']),
                            'remarks' => $this->security->xss_clean($data['remarks']),
                            'updated_at' => $this->date,
                            'updated_by' => $this->user_code
                        );

                        $result = $this->db->where('id', $id)->update('cause_list_tbl', $update_data);

                        if ($result) {

                            // Update the data logs table for data tracking
                            $table_name = 'cause_list_tbl';
                            $table_id = $id;
                            $message = 'Details of cause list for case ' . $case_no . ' is updated.';
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

            case 'DELETE_CAUSE_LIST':

                $this->form_validation->set_rules('id', 'Cause List Id', 'required');

                if ($this->form_validation->run()) {
                    $id = $this->security->xss_clean($data['id']);
                    $r = $this->db->where('id', $id)->update('cause_list_tbl', array('active_status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_code));

                    if ($r) {

                        // Update the data logs table for data tracking
                        $data = $this->db->select('id, case_no')->from('cause_list_tbl')->where('id', $id)->get()->row_array();
                        $table_name = 'cause_list_tbl';
                        $table_id = $id;
                        $message = 'Cause list for case ' . $data['case_no'] . ' is deleted.';
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

            case 'UPDATE_DISPLAY_BOARD_LIST':

                $this->form_validation->set_rules('hidden_room_id', 'Id', 'required|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room No.', 'required|xss_clean');
                $this->form_validation->set_rules('case_no', 'Case No.', 'required|xss_clean');
                $this->form_validation->set_rules('arbitrator_name', 'Arbitrator Name', 'required|xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    $hidden_room_id = $this->security->xss_clean($data['hidden_room_id']);
                    $insert_data = [
                        'case_no' => $this->security->xss_clean($data['case_no']),
                        'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
                        'room_status' => 'In Session',
                        'todays_date' => date('d-m-Y'),
                        'updated_by' => $this->user_code,
                        'updated_at' => $this->date
                    ];

                    // Note: If the display board is not working, check the table, It should not be empty. Rooms ID should be in display_board_tbl
                    $result = $this->db->where('room_id', $hidden_room_id)->update('cs_display_board_tbl', $insert_data);

                    if ($result) {

                        $insert_data2 = [
                            'room_id' => $this->security->xss_clean($data['hidden_room_id']),
                            'case_no' => $this->security->xss_clean($data['case_no']),
                            'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
                            'room_status' => 'In Session',
                            'todays_date' => date('d-m-Y'),
                            'created_by' => $this->user_code,
                            'created_at' => $this->date,
                            'updated_by' => $this->user_code,
                            'updated_at' => $this->date
                        ];

                        $result2 = $this->db->insert('cs_display_board_history_tbl', $insert_data2);

                        if ($result2) {
                            $this->db->trans_commit();
                            $dbstatus = TRUE;
                            $dbmessage = 'Record updated successfully';
                        } else {
                            $this->db->trans_rollback();
                            $dbstatus = FALSE;
                            $dbmessage = 'Error while saving. Please contact support.';
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

            case 'REMOVE_DISPLAY_BOARD_CASE':

                $this->form_validation->set_rules('room_id', 'Room Id', 'required|xss_clean');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    $room_id = $this->security->xss_clean($data['room_id']);
                    $update_data = [
                        'case_no' => '',
                        'arbitrator_name' => '',
                        'room_status' => 'Not In Session',
                        'todays_date' => '',
                        'updated_by' => $this->user_code,
                        'updated_at' => $this->date
                    ];

                    $result = $this->db->where('room_id', $room_id)->update('cs_display_board_tbl', $update_data);

                    if ($result) {
                        $this->db->trans_commit();
                        $dbstatus = TRUE;
                        $dbmessage = 'Record removed from display board successfully';
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

            case 'CANCEL_CAUSE_LIST':

                $this->form_validation->set_rules('hidden_id', 'Id', 'required|xss_clean');
                $this->form_validation->set_rules('cancel_remarks', 'Remarks', 'required|xss_clean|max_length[200]');

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();

                    $hidden_id = $this->security->xss_clean($data['hidden_id']);
                    // $case_slug = $this->security->xss_clean($data['cancel_case_no']);
                    // echo $case_slug;
                    // die;
                    $update_data = [
                        'active_status' => 2,
                        'remarks' => $this->security->xss_clean($data['cancel_remarks']),
                        'updated_by' => $this->user_code,
                        'updated_at' => $this->date
                    ];

                    $result = $this->db->where('id', $hidden_id)->update('cause_list_tbl', $update_data);

                    if ($result) {

                        // Get the cause list details using id
                        $causelist_details = $this->causelist_model->get_causelist_using_id($hidden_id);
                        $case_no_slug = $causelist_details['slug'];

                        $cases_slugs_list = [$case_no_slug];

                        $cd_update_result = $this->db->where('id', $hidden_id)->update('cause_list_tbl', [
                            'active_status' => 2,
                            'updated_at' => $this->date,
                            'updated_by' => $this->user_code
                        ]);

                        if (!$cd_update_result) {
                            failure_response('Error while changing the status of cause list to re-scheduled', false, true);
                            die;
                        }

                        // ===========================================
                        // Get the tagged matters
                        $tagged_cases = $this->tag_cases_model->get_tagged_case_details_using_master_case($case_no_slug);
                        if (count($tagged_cases) > 0) {
                            // Insert the hearing for the tagged cases
                            foreach ($tagged_cases as $key => $tc) {
                                // ==========================================================================
                                // Set the status to re-scheduled of the tagged cases cause list
                                $cd_update_result = $this->db->where([
                                    'slug' => $tc['tagged_case'],
                                    'date' => $causelist_details['date'],
                                    'time_from' => $causelist_details['time_from'],
                                    'time_to' => $causelist_details['time_to']
                                ])->update('cause_list_tbl', [
                                    'active_status' => 2,
                                    'remarks' => $this->security->xss_clean($data['cancel_remarks']),
                                    'updated_at' => $this->date,
                                    'updated_by' => $this->user_code
                                ]);

                                // if (!$cd_update_result) {
                                //     failure_response('Error while changing the status of tagged cases cause list to re-scheduled', false, true);
                                //     die;
                                // }
                                array_push($cases_slugs_list, $tc['tagged_case']);
                            }
                        }

                        $hearing_data = $this->db->from('cause_list_tbl')->where('id', $hidden_id)->select('*')->get()->row_array();
                        $case_slug = $hearing_data['slug'];
                        $hearing_data['cancel_hearing_remarks'] = $this->security->xss_clean($data['cancel_remarks']);

                        $email_error_count = 0;

                        if (count($cases_slugs_list) > 0) {
                            foreach ($cases_slugs_list as $key => $case_no_slug) {

                                // Fetch the case details
                                $case_details_data = $this->case_model->get(['slug' => $case_slug], 'GET_CASE_BASIC_DATA');

                                // ================================================
                                // Send the email notification to everyone.
                                // ============================================
                                // Get the claimant and respondent data
                                $claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_slug, CLAIMANT_TYPE_CONSTANT);
                                $claimants = array_filter($claimants, function ($claimant) {
                                    return !empty($claimant['email']);
                                });
                                $claimants_emails_temp = array_column($claimants, 'email');
                                // $claimants_emails = implode(',', $claimants_emails_temp);

                                // Phone Number
                                $claimants_for_phone = array_filter($claimants, function ($claimant) {
                                    return !empty($claimant['contact']);
                                });
                                $claimants_phone_temp = array_column($claimants_for_phone, 'contact');

                                // ============================================
                                // Get the COUNSELS details of claimants
                                $case_claimants_counsels = $this->common_model->get_counsels_of_case($case_slug, CLAIMANT_TYPE_CONSTANT);

                                $claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
                                    return !empty($counsel['email']);
                                });
                                $claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

                                // Phone Number
                                $claimant_counsels_for_phone = array_filter($case_claimants_counsels, function ($counsel) {
                                    return !empty($counsel['phone_number']);
                                });
                                $claimant_counsels_phone_temp = array_column($claimant_counsels_for_phone, 'phone_number');

                                // ================================================
                                // Respondents =====================
                                $respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_slug, RESPONDANT_TYPE_CONSTANT);
                                $respondants = array_filter($respondants, function ($claimant) {
                                    return !empty($claimant['email']);
                                });

                                $respondants_emails_temp = array_column($respondants, 'email');
                                // $respondants_emails = implode(',', $respondants_emails_temp);

                                // Phone Number
                                $respondants_for_phone = array_filter($respondants, function ($claimant) {
                                    return !empty($claimant['contact']);
                                });
                                $respondants_phone_temp = array_column($respondants_for_phone, 'contact');

                                // ============================================
                                // Get the COUNSELS details of claimants
                                $case_respondants_counsels = $this->common_model->get_counsels_of_case($case_slug, RESPONDANT_TYPE_CONSTANT);

                                $respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
                                    return !empty($counsel['email']);
                                });
                                $respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

                                // Phone Number
                                $respondant_counsels_for_phone = array_filter($case_respondants_counsels, function ($counsel) {
                                    return !empty($counsel['phone_number']);
                                });
                                $respondant_counsels_phone_temp = array_column($respondant_counsels_for_phone, 'phone_number');

                                // ================================================
                                // Get arbitrators of the case
                                $arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_slug);
                                $arbitrators = array_filter($arbitrators, function ($arbitrator) {
                                    return !empty($arbitrator['email']);
                                });
                                $arbitrators_emails_temp = array_column($arbitrators, 'email');
                                // $arbitrators_emails = implode(',', $arbitrators_emails_temp);

                                // Phone Number
                                $arbitrators_for_phone = array_filter($arbitrators, function ($arbitrator) {
                                    return !empty($arbitrator['contact_no']);
                                });
                                $arbitrators_phone_temp = array_column($arbitrators_for_phone, 'contact_no');

                                // Users
                                $users = $this->common_model->get_all_users_alloted_to_case($case_slug);

                                if ($users && count($users) < 1) {
                                    failure_response('No users are not allocated in the case', true, true);
                                }

                                // Fetch deputy counsel details
                                $deputy_counsel_details = $this->common_model->get_deputy_counsel_alloted_to_case($case_slug);
                                $deputy_counsel_smtp_email = (isset($deputy_counsel_details['email']) && !empty($deputy_counsel_details['email'])) ? $deputy_counsel_details['email'] : DEFAULT_SMTP_EMAIL_ID;
                                // if ($users && count($users) < 1) {
                                //     failure_response('No deputy counsel is allocated', true, true);
                                // }

                                $users = array_filter($users, function ($user) {
                                    return !empty($user['email']);
                                });
                                $users_emails_temp = array_column($users, 'email');
                                // $users_emails = implode(',', $users_emails_temp);

                                // Phone Number
                                $users_for_phone = array_filter($users, function ($user) {
                                    return !empty($user['phone_number']);
                                });
                                $users_phone_temp = array_column($users_for_phone, 'phone_number');

                                // Merge all email arrays into a single array
                                $all_emails = array_merge(
                                    $claimants_emails_temp,
                                    $claimant_counsels_emails_temp,
                                    $respondants_emails_temp,
                                    $respondant_counsels_emails_temp,
                                    $arbitrators_emails_temp,
                                    $users_emails_temp
                                );

                                // Implode the merged array with commas
                                $all_emails_string = implode(',', $all_emails);

                                // =====================================================
                                // =====================================================

                                $additional_phones = [];
                                // Merge all email arrays into a single array
                                $all_phone_numbers = array_merge(
                                    $claimants_phone_temp,
                                    $claimant_counsels_phone_temp,
                                    $respondants_phone_temp,
                                    $respondant_counsels_phone_temp,
                                    $arbitrators_phone_temp,
                                    $users_phone_temp
                                );


                                // Implode the merged array with commas
                                // print_r($all_phone_numbers);
                                if (count($all_phone_numbers) > 0) {
                                    $full_case_no = get_full_case_number($case_details_data);

                                    foreach ($all_phone_numbers as $key => $phone_number) {
                                        // ======================================================
                                        // SEND THE WHATSAPP MESSAGE WHO ARE IN THE CASE
                                        $whatsapp_result = wa_cancel_hearing_notification($phone_number, $full_case_no, $case_details_data, $hearing_data);

                                        // Insert WhatsApp Log Data
                                        $insert_whatsapp_log = [
                                            "transaction_id"     => $whatsapp_result['template_id'],
                                            'type'               => 'HEARING_CANCELLED',
                                            "phone_number"       => $phone_number,
                                            "message"            => $whatsapp_result['msg'],
                                            "status"             => ($whatsapp_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                            "created_by"         => $this->session->userdata('user_code'),
                                            "created_at"         => currentDateTimeStamp()
                                        ];
                                        $result_whatsapp = $this->db->insert('support_whatsapp_record', $insert_whatsapp_log);
                                    }
                                }

                                // ========================================================
                                // Create an array of all the persons who are involved in the case
                                $persons = [
                                    'claimants' => $claimants,
                                    'case_claimants_counsels' => $case_claimants_counsels,
                                    'respondants' => $respondants,
                                    'case_respondants_counsels' => $case_respondants_counsels,
                                    'arbitrators' => $arbitrators,
                                    'users' => $users,
                                ];

                                // Load the data
                                $view_data['persons'] = $persons;
                                $view_data['case_details_data'] = $case_details_data;
                                $view_data['hearing_data'] = $hearing_data;


                                // Send emails to everyone
                                $subject = 'Hearing scheduled for case number: ' . get_full_case_number($case_details_data) . ' (' . $case_details_data['case_title'] . ')';

                                $email_html_body = $this->load->view('emails/cancelled_hearing_notice', $view_data, true);

                                $email_send_result = common_email_sending_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);

                                // Update the error log count
                                if ($email_send_result['status'] == false) {
                                    $email_error_count += 1;
                                }

                                // $this->db->trans_rollback();
                                // print_r($email_send_result);
                                // die;
                                // Email log data
                                $insert_mail_log = [
                                    "transaction_id"     => $case_slug,
                                    'mail_type'         => 'HEARING_CANCELLED',
                                    "to_mail"             => strtolower($all_emails_string),
                                    "subject"             => $subject,
                                    "body"                 => $email_html_body,
                                    "status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                    "created_by"         => $this->session->userdata('user_code'),
                                    "created_at"         => currentDateTimeStamp()
                                ];
                                $result_mail = $this->db->insert('support_mail_record', $insert_mail_log);
                            }
                        }

                        if ($email_error_count > 0) {
                            // ============================================
                            // Commit the changes with the send email failed error
                            success_response('Hearing cancelled successfully  but email sending in ' . $email_error_count . ' cases failed due to SMTP server availability. Please check log for more information.', false, true);
                        } else {
                            // ============================================
                            // Commit the changes
                            success_response('Hearing cancelled successfully and email sent to everyone related to case.', false, true);
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

            case 'RESCHEDULE_CAUSE_LIST_FORM':
                $this->form_validation->set_rules('cause_list_hidden_id', 'ID', 'required|xss_clean');
                $this->form_validation->set_rules('case_list', 'Case List', 'required|xss_clean');
                $this->form_validation->set_rules('case_no', 'Case number', 'required|xss_clean');
                $this->form_validation->set_rules('title_of_case', 'Title of case', 'required|xss_clean|trim');
                // $this->form_validation->set_rules('arbitrator_name', 'Arbitrator name', 'required|xss_clean|trim');
                $this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
                $this->form_validation->set_rules('mode_of_hearing', 'Mode of hearing', 'required|xss_clean');
                $this->form_validation->set_rules('time_from', 'Time (From)', 'xss_clean');
                $this->form_validation->set_rules('time_to', 'Time (To)', 'xss_clean');
                $this->form_validation->set_rules('purpose', 'Purpose', 'required|xss_clean');
                $this->form_validation->set_rules('room_no', 'Room number', 'xss_clean');
                $this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean');

                if (in_array($data['mode_of_hearing'], ['ONLINE', 'HYBRID'])) {
                    $this->form_validation->set_rules('hearing_meeting_link', 'Hearing meeting link', 'required|xss_clean');
                }

                if ($this->form_validation->run()) {
                    $this->db->trans_begin();
                    try {

                        $case_no = $this->security->xss_clean($this->input->post('case_no'));
                        $case_no_slug = $this->input->post('case_list');
                        $causelist_id = $this->input->post('cause_list_hidden_id');

                        // check if the hearing is scheduled for the same date
                        // $check_dup_hearing_result = $this->db->select('*')->where([
                        //     'slug' => $case_no_slug,
                        //     'date' => date('Y-m-d', strtotime($data['date'])),
                        //     'active_status' => 1
                        // ])->from('cause_list_tbl')->get()->result_array();

                        // if (count($check_dup_hearing_result) > 0) {
                        //     failure_response('Hearing is already scheduled for the same date.', false, true);
                        //     die;
                        // }

                        // Check if the arbitrator is added or not
                        // ================================================
                        // Get arbitrators of the case
                        $check_case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);
                        if (count($check_case_arbitrators) < 1) {
                            failure_response('Arbitrator is not appointed in the case. Appoint arbitrator to schedule the hearing.', false, true);
                            die;
                        }

                        $date = date('Y-m-d', strtotime($data['date']));
                        $mode_of_hearing = $data['mode_of_hearing'];
                        $time_from = date('H:i', strtotime($data['time_from']));
                        $time_to = date('H:i', strtotime($data['time_to']));

                        $room = $this->security->xss_clean($data['room_no']);

                        if (in_array($mode_of_hearing, ['PHYSICAL', 'HYBRID'])) {
                            // check hearing room is available or not
                            $check = $this->db->select('*')
                                ->from('cause_list_tbl')
                                ->where('date', $date)
                                ->where("time_from" >= $time_from and "time_to" <= $time_to)
                                ->where('room_no_id', $room)
                                ->get()
                                ->num_rows();

                            if ($check > 0) {
                                $dbstatus = false;
                                $dbmessage = "Hearing room is not available.";
                                return array('status' => $dbstatus, 'msg' => $dbmessage);
                            }
                        }

                        // Check, more than 18 hearings should not be schedules at the same date and time slots
                        // check hearing room is available or not
                        $checkTotalHearings = $this->db->select('*')
                            ->from('cause_list_tbl')
                            ->where('date', $date)
                            ->where("time_from" >= $time_from and "time_to" <= $time_to)
                            ->get()
                            ->num_rows();

                        if ($checkTotalHearings > 17) {
                            $dbstatus = false;
                            $dbmessage = "No more hearings can be scheduled for this date and time slot.";
                            return array('status' => $dbstatus, 'msg' => $dbmessage);
                        }

                        // ===============================================
                        // ===============================================

                        $insert_data = array(
                            // 'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
                            'date' => $this->security->xss_clean(date('Y-m-d', strtotime($data['date']))),
                            'time_from' => $time_from,
                            'time_to' => $time_to,
                            'purpose_cat_id' => $this->security->xss_clean($data['purpose']),
                            'room_no_id' => '',
                            'mode_of_hearing' => $mode_of_hearing,
                            'hearing_meeting_link' => '',
                            'remarks' => $this->security->xss_clean($data['remarks']),
                            'created_at' => $this->date,
                            'created_by' => $this->user_code
                        );

                        $insert_data['case_no'] = $case_no;
                        $insert_data['slug'] = $this->security->xss_clean($this->input->post('case_list'));
                        $insert_data['title_of_case'] = $this->security->xss_clean($data['title_of_case']);

                        if (in_array($mode_of_hearing, ['PHYSICAL', 'HYBRID'])) {
                            $insert_data['room_no_id'] = $this->security->xss_clean($data['room_no']);
                        }

                        if (in_array($mode_of_hearing, ['HYBRID', 'ONLINE'])) {
                            $insert_data['hearing_meeting_link'] = $this->security->xss_clean($data['hearing_meeting_link']);
                        }

                        $hearing_data = $insert_data;

                        // $this->db->select('*');
                        // $this->db->from('cause_list_tbl');
                        // $this->db->where('date', $this->security->xss_clean($data['date']));
                        // $this->db->where("time_from" >= $this->security->xss_clean($data['time_from']) AND "time_to" <= $this->security->xss_clean($data['time_to']));
                        // $this->db->where('room_no_id', $this->security->xss_clean($data['room_no']));
                        // $check = $this->db->get();

                        // if ($check->num_rows() > 0) {
                        //     $dbstatus = false;
                        //     $dbmessage = "Hearing room is not available.";
                        // }

                        $result = $this->db->insert('cause_list_tbl', $insert_data);
                        if ($result) {

                            $cases_slugs_list = [$case_no_slug];

                            // Get the cause list details using id
                            $causelist_details = $this->causelist_model->get_causelist_using_id($causelist_id);
                            $cd_update_result = $this->db->where('id', $causelist_id)->update('cause_list_tbl', [
                                'active_status' => 3,
                                'updated_at' => $this->date,
                                'updated_by' => $this->user_code
                            ]);

                            if (!$cd_update_result) {
                                failure_response('Error while changing the status of cause list to re-scheduled', false, true);
                                die;
                            }

                            // ===========================================
                            // Get the tagged matters
                            $tagged_cases = $this->tag_cases_model->get_tagged_case_details_using_master_case($case_no_slug);
                            if (count($tagged_cases) > 0) {
                                // Insert the hearing for the tagged cases
                                foreach ($tagged_cases as $key => $tc) {
                                    // ==========================================================================
                                    // Set the status to re-scheduled of the tagged cases cause list
                                    $cd_update_result = $this->db->where([
                                        'slug' => $tc['tagged_case'],
                                        'date' => $causelist_details['date'],
                                        'time_from' => $causelist_details['time_from'],
                                        'time_to' => $causelist_details['time_to']
                                    ])->update('cause_list_tbl', [
                                        'active_status' => 3,
                                        'updated_at' => $this->date,
                                        'updated_by' => $this->user_code
                                    ]);

                                    // if (!$cd_update_result) {
                                    //     failure_response('Error while changing the status of tagged cases cause list to re-scheduled', false, true);
                                    //     die;
                                    // }

                                    $insert_data['case_no'] = $tc['tagged_case_no_prefix'] . '/' . $tc['tagged_case_no'] . '/' . $tc['tagged_case_no_year'];
                                    $insert_data['slug'] = $tc['tagged_case'];
                                    $insert_data['title_of_case'] = $tc['tagged_case_title'];

                                    $result = $this->db->insert('cause_list_tbl', $insert_data);

                                    if (!$result) {
                                        // Commit the changes
                                        failure_response('Error while saving records in tagged cases for hearing.', false, true);
                                        die;
                                    }

                                    array_push($cases_slugs_list, $tc['tagged_case']);
                                }
                            }

                            // ==========================================================================
                            $table_id = $this->db->insert_id();

                            // Update the data logs table for data tracking
                            $table_name = 'cause_list_tbl';
                            $message = 'A  cause list is re-scheduled for case ' . $case_no . '.';
                            $this->common_model->update_data_logs($table_name, $table_id, $message);


                            // ===========================================
                            // Get the room details
                            $room_info = $this->common_model->get_room_info_using_id($data['room_no']);

                            $email_error_count = 0;

                            // print_r($cases_slugs_list);
                            // die;

                            if (count($cases_slugs_list) > 0) {
                                foreach ($cases_slugs_list as $key => $case_no_slug) {
                                    # code...
                                    // Loop through the case and send the email and messages
                                    $case_details_data = $this->getter_model->get([
                                        'case_slug' => $case_no_slug
                                    ], 'GET_CASE_DETAILS');


                                    // ================================================
                                    // Send the email notification to everyone.
                                    // ============================================
                                    // Get the claimant and respondent data
                                    $case_claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, CLAIMANT_TYPE_CONSTANT);
                                    $claimants = array_filter($case_claimants, function ($claimant) {
                                        return !empty($claimant['email']);
                                    });
                                    $claimants_emails_temp = array_column($claimants, 'email');
                                    // $claimants_emails = implode(',', $claimants_emails_temp);

                                    // Phone Number
                                    $claimants_for_phone = array_filter($case_claimants, function ($claimant) {
                                        return !empty($claimant['contact']);
                                    });
                                    $claimants_phone_temp = array_column($claimants_for_phone, 'contact');

                                    // ============================================
                                    // Get the COUNSELS details of claimants
                                    $case_claimants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, CLAIMANT_TYPE_CONSTANT);

                                    $claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
                                        return !empty($counsel['email']);
                                    });
                                    $claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

                                    // Phone Number
                                    $claimant_counsels_for_phone = array_filter($case_claimants_counsels, function ($counsel) {
                                        return !empty($counsel['phone_number']);
                                    });
                                    $claimant_counsels_phone_temp = array_column($claimant_counsels_for_phone, 'phone_number');

                                    // ================================================
                                    // Respondents =====================
                                    $case_respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, RESPONDANT_TYPE_CONSTANT);
                                    $respondants = array_filter($case_respondants, function ($claimant) {
                                        return !empty($claimant['email']);
                                    });

                                    $respondants_emails_temp = array_column($respondants, 'email');
                                    // $respondants_emails = implode(',', $respondants_emails_temp);

                                    // Phone Number
                                    $respondants_for_phone = array_filter($case_respondants, function ($claimant) {
                                        return !empty($claimant['contact']);
                                    });
                                    $respondants_phone_temp = array_column($respondants_for_phone, 'contact');

                                    // ============================================
                                    // Get the COUNSELS details of claimants
                                    $case_respondants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, RESPONDANT_TYPE_CONSTANT);

                                    $respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
                                        return !empty($counsel['email']);
                                    });
                                    $respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

                                    // Phone Number
                                    $respondant_counsels_for_phone = array_filter($case_respondants_counsels, function ($counsel) {
                                        return !empty($counsel['phone_number']);
                                    });
                                    $respondant_counsels_phone_temp = array_column($respondant_counsels_for_phone, 'phone_number');

                                    // ================================================
                                    // Get arbitrators of the case
                                    $case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);

                                    // Email
                                    $arbitrators = array_filter($case_arbitrators, function ($arbitrator) {
                                        return !empty($arbitrator['email']);
                                    });
                                    $arbitrators_emails_temp = array_column($arbitrators, 'email');
                                    // $arbitrators_emails = implode(',', $arbitrators_emails_temp);

                                    // Phone Number
                                    $arbitrators_for_phone = array_filter($case_arbitrators, function ($arbitrator) {
                                        return !empty($arbitrator['contact_no']);
                                    });
                                    $arbitrators_phone_temp = array_column($arbitrators_for_phone, 'contact_no');

                                    // ================================================
                                    // Fetch all users allocated in the case
                                    // ================================================
                                    $users = $this->common_model->get_all_users_alloted_to_case($case_no_slug);

                                    if ($users && count($users) < 1) {
                                        failure_response('No users are not allocated', true, true);
                                    }

                                    // Fetch deputy counsel details
                                    $deputy_counsel_details = $this->common_model->get_deputy_counsel_alloted_to_case($case_no_slug);
                                    $deputy_counsel_smtp_email = (isset($deputy_counsel_details['email']) && !empty($deputy_counsel_details['email'])) ? $deputy_counsel_details['email'] : DEFAULT_SMTP_EMAIL_ID;

                                    // Email
                                    $users = array_filter($users, function ($user) {
                                        return !empty($user['email']);
                                    });
                                    $users_emails_temp = array_column($users, 'email');

                                    // Phone Number
                                    $users_for_phone = array_filter($users, function ($user) {
                                        return !empty($user['phone_number']);
                                    });
                                    $users_phone_temp = array_column($users_for_phone, 'phone_number');

                                    // Merge all email arrays into a single array
                                    $all_emails = array_merge(
                                        $claimants_emails_temp,
                                        $claimant_counsels_emails_temp,
                                        $respondants_emails_temp,
                                        $respondant_counsels_emails_temp,
                                        $arbitrators_emails_temp,
                                        $users_emails_temp
                                    );

                                    // Implode the merged array with commas
                                    $all_emails_string = implode(',', $all_emails);

                                    // =====================================================
                                    // =====================================================

                                    $additional_phones = [];
                                    // Merge all email arrays into a single array
                                    $all_phone_numbers = array_merge(
                                        $claimants_phone_temp,
                                        $claimant_counsels_phone_temp,
                                        $respondants_phone_temp,
                                        $respondant_counsels_phone_temp,
                                        $arbitrators_phone_temp,
                                        $users_phone_temp
                                    );

                                    // Implode the merged array with commas
                                    // print_r($all_phone_numbers);
                                    if (count($all_phone_numbers) > 0) {
                                        $full_case_no = get_full_case_number($case_details_data);

                                        foreach ($all_phone_numbers as $key => $phone_number) {
                                            // ======================================================
                                            // SEND THE WHATSAPP MESSAGE WHO ARE IN THE CASE
                                            $whatsapp_result = wa_case_hearing_reschedule_notification($phone_number, $full_case_no, $case_details_data, $hearing_data);

                                            // Insert WhatsApp Log Data
                                            $insert_whatsapp_log = [
                                                "transaction_id"     => $whatsapp_result['template_id'],
                                                'type'               => 'HEARING_RESCHEDULED',
                                                "phone_number"       => $phone_number,
                                                "message"            => $whatsapp_result['msg'],
                                                "status"             => ($whatsapp_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                                "created_by"         => $this->session->userdata('user_code'),
                                                "created_at"         => currentDateTimeStamp()
                                            ];
                                            $result_whatsapp = $this->db->insert('support_whatsapp_record', $insert_whatsapp_log);
                                        }
                                    }

                                    // ========================================================
                                    // Create an array of all the persons who are involved in the case
                                    $persons = [
                                        'claimants' => $case_claimants,
                                        'case_claimants_counsels' => $case_claimants_counsels,
                                        'respondants' => $case_respondants,
                                        'case_respondants_counsels' => $case_respondants_counsels,
                                        'arbitrators' => $case_arbitrators,
                                        'users' => $users,
                                    ];

                                    // Load the data
                                    $view_data['persons'] = $persons;
                                    $view_data['case_details_data'] = $case_details_data;
                                    $view_data['hearing_data'] = $hearing_data;
                                    $view_data['room_info'] = $room_info;

                                    // Send emails to everyone
                                    $subject = 'Hearing re-scheduled for case number: ' . get_full_case_number($case_details_data) . ' (' . $case_details_data['case_title'] . ')';

                                    $email_html_body = $this->load->view('emails/hearing_reschedule_notice', $view_data, true);

                                    $email_send_result = hearing_notice_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);

                                    // Update the error log count
                                    if ($email_send_result['status'] == false) {
                                        $email_error_count += 1;
                                    }

                                    // Email log data
                                    $insert_mail_log = [
                                        "transaction_id"     => $case_no_slug,
                                        'mail_type'         => 'HEARING_RESCHEDULED',
                                        "to_mail"             => strtolower($all_emails_string),
                                        "subject"             => $subject,
                                        "body"                 => $email_html_body,
                                        "status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
                                        "created_by"         => $this->session->userdata('user_code'),
                                        "created_at"         => currentDateTimeStamp()
                                    ];
                                    $result_mail = $this->db->insert('support_mail_record', $insert_mail_log);
                                }
                            }


                            if ($email_error_count > 0) {
                                // ============================================
                                // Commit the changes with the send email failed error
                                success_response('Hearing scheduled successfully  but email sending in ' . $email_error_count . ' cases failed due to SMTP server availability. Please check log for more information.', false, true);
                            } else {
                                // ============================================
                                // Commit the changes
                                success_response('Hearing scheduled successfully and email sent to everyone related to case.', false, true);
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

            default:
                return array('status' => false, 'msg' => NO_OPERATION);
        }
    }

    public function get_causelist_using_id($id)
    {
        $this->db->select("clt.id, clt.slug,clt.case_no, CONCAT(cdt.case_no_prefix,'/', cdt.case_no, '/', cdt.case_no_year) as case_no_desc, cdt.case_title, amt.name_of_arbitrator as arbitrator_name, amt.email as amt_email, amt.contact_no as amt_contact_no, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, rt.room_name, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' WHEN clt.active_status='3' THEN 'Re-scheduled' END as active_status_desc, GROUP_CONCAT(amt.name_of_arbitrator) as amt_name_of_arbitrator, clt.mode_of_hearing, clt.hearing_meeting_link");
        $this->db->from('cause_list_tbl AS clt');
        $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
        $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');
        $this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = clt.slug', 'left');
        $this->db->join('cs_arbitral_tribunal_tbl as cat', 'cat.case_no = clt.slug  AND cat.status = 1', 'left');
        $this->db->join('master_arbitrators_tbl as amt', 'amt.code = cat.arbitrator_code', 'left');

        $this->db->where('clt.id', $id);

        $query = $this->db->get();
        return $query->row_array();
    }
}
