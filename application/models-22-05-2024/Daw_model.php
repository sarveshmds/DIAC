
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daw_model extends CI_Model
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

    function get_all_registrations()
    {
        $this->db->select("drt.*, s.name as state_name, c.name as country_name, gcd.description as salutation_desc, gcd2.description as registrant_category_desc, gcd3.description as hear_about_event_desc, c1.phonecode as  mobile_no_country_code_desc");
        $this->db->from('daw_registrations_tbl drt');
        $this->db->join('countries c', 'c.id = drt.country', 'left');
        $this->db->join('countries c1', 'c1.id = drt.mobile_no_country_code', 'left');
        $this->db->join('states s', 's.id = drt.state', 'left');
        $this->db->join('gen_code_desc gcd', 'gcd.gen_code = drt.title AND gcd.gen_code_group="SALUTATION"', 'left');
        $this->db->join('gen_code_desc gcd2', 'gcd2.gen_code = drt.registrant_category AND gcd2.gen_code_group="REGISTRANT_CATEGORY"', 'left');
        $this->db->join('gen_code_desc gcd3', 'gcd3.gen_code = drt.hear_about_event AND gcd3.gen_code_group="HEAR_ABOUT_EVENT"', 'left');

        if (isset($_POST['f_name']) && !empty($_POST['f_name'])) {
            $this->db->where("drt.first_name LIKE '%" . $_POST['f_name'] . "%' OR drt.last_name LIKE '%" . $_POST['f_name'] . "%'");
        }

        if (isset($_POST['f_mobile_no']) && !empty($_POST['f_mobile_no'])) {
            $this->db->where("drt.mobile_no", $_POST['f_mobile_no']);
        }

        if (isset($_POST['f_registrant_category']) && !empty($_POST['f_registrant_category'])) {
            $this->db->where("drt.registrant_category", $_POST['f_registrant_category']);
        }

        if (isset($_POST['f_country']) && !empty($_POST['f_country'])) {
            $this->db->where("drt.country", $_POST['f_country']);
        }

        if (isset($_POST['dt_application_status']) && !empty($_POST['dt_application_status'])) {
            if ($_POST['dt_application_status'] == 'APPROVED') {
                $this->db->where("drt.application_status", 1);
            }
            if ($_POST['dt_application_status'] == 'REJECTED') {
                $this->db->where("drt.application_status", 2);
            }

            if ($_POST['dt_application_status'] == 'PENDING') {
                $this->db->where("drt.application_status", 0);
            }
            if ($_POST['dt_application_status'] == 'PAYMENT_PENDING') {
                $this->db->where("drt.application_status", 3);
            }
            if ($_POST['dt_application_status'] == 'PAYMENT_RECEIVED') {
                $this->db->where("drt.application_status", 4);
            }
        }

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('drt.id', 'DESC');

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->select("*")->from('daw_registrations_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        return $output;
    }

    public function get_single_registration_data($id)
    {
        $this->db->select("drt.*, s.name as state_name, c.name as country_name, gcd.description as salutation_desc, gcd2.description as registrant_category_desc, gcd3.description as hear_about_event_desc, c1.phonecode as  mobile_no_country_code_desc");
        $this->db->from('daw_registrations_tbl drt');
        $this->db->join('countries c', 'c.id = drt.country', 'left');
        $this->db->join('countries c1', 'c1.id = drt.mobile_no_country_code', 'left');
        $this->db->join('states s', 's.id = drt.state', 'left');
        $this->db->join('gen_code_desc gcd', 'gcd.gen_code = drt.title AND gcd.gen_code_group="SALUTATION"', 'left');
        $this->db->join('gen_code_desc gcd2', 'gcd2.gen_code = drt.registrant_category AND gcd2.gen_code_group="REGISTRANT_CATEGORY"', 'left');
        $this->db->join('gen_code_desc gcd3', 'gcd3.gen_code = drt.hear_about_event AND gcd3.gen_code_group="HEAR_ABOUT_EVENT"', 'left');
        $this->db->where('drt.id', $id);
        return  $this->db->get()->row_array();
    }

    public function get_last_registration_data()
    {
        $this->db->select("drt.*");
        $this->db->from('daw_registrations_tbl drt');
        $this->db->order_by('drt.id', 'DESC');
        return  $this->db->get()->row_array();
    }

    public function get_countries()
    {
        $this->db->from('countries');
        $this->db->select('*');
        return $this->db->get()->result_array();
    }

    public function get_states_using_country($country_id)
    {
        $this->db->from('states');
        $this->db->select('*');
        $this->db->order_by('name');
        $this->db->where('country_id', $country_id);
        return $this->db->get()->result_array();
    }

    public function get_gen_code_data($gen_code_group)
    {
        $this->db->from('gen_code_desc');
        $this->db->select('gen_code,description');
        $this->db->where('gen_code_group', $gen_code_group);
        $this->db->where('status', 1);
        $this->db->order_by('sl_no', 'ASC');
        $res = $this->db->get();
        return $res->result_array();
    }

    public function get_registrations_export_data($filter)
    {
        $this->db->select("drt.*, s.name as state_name, c.name as country_name, gcd.description as salutation_desc, gcd2.description as registrant_category_desc, gcd3.description as hear_about_event_desc, c1.phonecode as  mobile_no_country_code_desc");
        $this->db->from('daw_registrations_tbl drt');
        $this->db->join('countries c', 'c.id = drt.country', 'left');
        $this->db->join('countries c1', 'c1.id = drt.mobile_no_country_code', 'left');
        $this->db->join('states s', 's.id = drt.state', 'left');
        $this->db->join('gen_code_desc gcd', 'gcd.gen_code = drt.title AND gcd.gen_code_group="SALUTATION"', 'left');
        $this->db->join('gen_code_desc gcd2', 'gcd2.gen_code = drt.registrant_category AND gcd2.gen_code_group="REGISTRANT_CATEGORY"', 'left');
        $this->db->join('gen_code_desc gcd3', 'gcd3.gen_code = drt.hear_about_event AND gcd3.gen_code_group="HEAR_ABOUT_EVENT"', 'left');

        if (isset($filter['f_name']) && !empty($filter['f_name'])) {
            $this->db->where("drt.first_name LIKE '%" . $filter['f_name'] . "%' OR drt.last_name LIKE '%" . $filter['f_name'] . "%'");
        }

        if (isset($filter['f_mobile_no']) && !empty($filter['f_mobile_no'])) {
            $this->db->where('drt.mobile_no', $filter['f_mobile_no']);
        }
        if (isset($filter['f_mobile_no']) && !empty($filter['f_mobile_no'])) {
            $this->db->where('drt.mobile_no', $filter['f_mobile_no']);
        }
        if (isset($filter['f_registrant_category']) && !empty($filter['f_registrant_category'])) {
            $this->db->where('registrant_category', $filter['f_registrant_category']);
        }
        if (isset($filter['f_country']) && !empty($filter['f_country'])) {
            $this->db->where('country', $filter['f_country']);
        }

        if (isset($filter['dt_application_status']) && !empty($filter['dt_application_status'])) {
            if ($filter['dt_application_status'] == 'APPROVED') {
                $this->db->where("drt.application_status", 1);
            }
            if ($filter['dt_application_status'] == 'REJECTED') {
                $this->db->where("drt.application_status", 2);
            }
            if ($filter['dt_application_status'] == 'PENDING') {
                $this->db->where("drt.application_status", 0);
            }
            if ($filter['dt_application_status'] == 'PAYMENT_PENDING') {
                $this->db->where("drt.application_status", 3);
            }
            if ($filter['dt_application_status'] == 'PAYMENT_RECEIVED') {
                $this->db->where("drt.application_status", 4);
            }
        }

        if (isset($filter['f_reg_number']) && !empty($filter['f_reg_number'])) {
            $this->db->where('reg_number', $filter['f_reg_number']);
        }

        return  $this->db->get()->result_array();
    }

    public function generate_qr_code($person_data)
    {
        $this->load->library(['ciqrcode', 'my_encryption']);
        $encoded_id =  $this->my_encryption->safe_b64encode($person_data['id']);

        // echo  $this->encryption->safe_b64decode($encoded_url_safe_string);

        // $decoded_url_safe_string = rawurldecode($encoded_url_safe_string, $key);
        // echo $encoded_id;
        // die;
        $content = base_url('daw/person/') . $encoded_id;

        $qr_image = $person_data['id'] . time() . rand() . '.png';
        $data['img_url'] = "";
        $params['data'] = $content;
        $params['level'] = 'H';
        $params['size'] = 2;
        $params['savename'] = FCPATH . "public/upload/daw/qr_image/" . $qr_image;

        $qr_image_path = "public/upload/daw/qr_image/" . $qr_image;

        if ($this->ciqrcode->generate($params)) {
            $data['img_url'] = $qr_image;
        }

        return $qr_image_path;
    }

    public function get_session_dates()
    {
        return $this->db->from('daw_session_dates_category')->select('*')->where('record_status', 1)->get()->result_array();
    }

    function get_daw_all_live_sessions()
    {
        $this->db->select("dlsdt.*, dsdc.session_date, dsdc.date_sup, dsdc.date_month, dsdc.code as dsdc_code");
        $this->db->from('daw_live_sessions_details_tbl dlsdt');
        $this->db->join('daw_session_dates_category dsdc', 'dsdc.code = dlsdt.session_date_code', 'left');

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $this->db->order_by('dlsdt.id', 'DESC');

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->select("*")->from('daw_live_sessions_details_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        return $output;
    }
}
