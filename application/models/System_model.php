
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class System_model extends CI_Model
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

            case 'DATA_LOGS_LIST':

                $select_column = array("message", "created_by", "created_at");
                $order_column = array(null, "created_by", "created_at");


                $this->db->select("dlt.message, dlt.created_at as date, dlt.created_by, um.user_display_name as alter_by_user");
                $this->db->from('data_logs_tbl as dlt');
                $this->db->join('user_master as um', 'um.user_code = dlt.created_by');

                if (isset($_POST["search"]["value"])) {
                    $count = 1;
                    $search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

                    $like_clause = "(";
                    foreach ($select_column as $sc) {
                        if ($count == 1) {
                            $like_clause .= 'dlt.' . $sc . " LIKE '%" . $search_value . "%'";
                        } else {
                            $like_clause .= " OR " . 'dlt.' . $sc . " LIKE '%" . $search_value . "%'";
                        }
                        $count++;
                    }
                    $like_clause .= ")";
                    $this->db->where($like_clause);
                }

                if (isset($_POST["order"])) {
                    $this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
                } else {
                    $this->db->order_by('dlt.id', 'DESC');
                }

                if ($_POST['length'] != -1) {
                    $this->db->limit($_POST['length'], $_POST['start']);
                }

                $this->db->where('dlt.status', 1);
                $query = $this->db->get();
                // print_r($this->db->last_query()); 
                $fetch_data = $query->result();

                // Filter records
                $recordsFiltered = $this->db->where('status', 1)->select("*")->from('data_logs_tbl')->count_all_results();

                // Records total
                $recordsTotal = $this->db->where('status', 1)->select("*")->from('data_logs_tbl')->count_all_results();

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

            default:
                return array('status' => false, 'msg' => NO_OPERATION);
        }
    }
}
