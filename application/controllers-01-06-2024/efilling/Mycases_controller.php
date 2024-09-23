<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mycases_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Kolkata');
        # helpers
        $this->load->helper(array('form'));

        # libraries
        $this->load->library('encryption');

        # models
        $this->load->model(['superadmin_model', 'getter_model', 'common_model', 'fees_model', 'case_model', 'efiling_model']);
    }

    /*
	*	purpose : to check whether the method is correct or not
	*/

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'ADVOCATE' => array('my_cases', 'view_case_details', 'getDatatableList', 'view_case_fees', 'case_hearings', 'get_case_hearings'),
            'PARTY' => array('my_cases', 'view_case_details', 'getDatatableList', 'view_case_fees', 'case_hearings', 'get_case_hearings'),
            'ARBITRATOR' => array('my_cases', 'view_case_details', 'getDatatableList', 'view_case_fees', 'case_hearings', 'get_case_hearings'),
            'ADVOCATE_ARBITRATOR' => array('my_cases', 'view_case_details', 'getDatatableList', 'view_case_fees'),
        );

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('efiling/logout');
        } else {
            if (in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
                $uri = $this->uri->segment_array();
                unset($uri[1]);
                unset($uri[2]);
                call_user_func_array(array($this, $method), $uri);
            } else {
                return redirect('page-not-found');
            }
        }
    }

    public function my_cases()
    {
        $data['menu_item'] = 'My Cases';
        $data['menu_group'] = 'My Cases';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'My Cases';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/case/my_cases');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function view_case_details()
    {
        $case_slug = $this->uri->segment(3);

        if (!$case_slug) {
            return redirect('page-not-found');
        }

        $data['menu_item'] = 'My Cases';
        $data['menu_group'] = 'My Cases';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['page_title'] = 'Case Details';

            $parameters = array('slug' => $case_slug);
            // Get all data
            $data['case_data'] = $this->case_model->get($parameters, 'ALL_CASE_DATA');
            // print_r($data['case_data']);
            // die;
            $data['page_title'] = "Case Details: " . $data['case_data']['case_data']['case_no'];

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/case/view_case_details');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function getDatatableList()
    {
        $order = '';
        $Ocolumn = '';
        $Odir = '';
        $order = $this->input->post('order');
        if ($order) {
            foreach ($order as $row) {
                $Ocolumn = $row['column'];
                $Odir = $row['dir'];
            }
            $this->db->order_by($Ocolumn, $Odir);
        } else {
            $this->db->order_by(1, "ASC");
        }
        $search = $this->input->post('search');
        $header = array('case_title', 'case_no'); //search filter will work on this column
        if ($search['value'] != '') {
            for ($i = 0; $i < count($header); $i++) {
                $this->db->or_like($header[$i], $search['value']);
            }
        }
        $iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
        $iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

        // Get current user details
        $currentUser = $this->getter_model->get('', 'get_current_loggedin_user');

        $sqlResult = $this->db->query("SELECT a.*,a.case_no as case_no_desc,b.* FROM `cs_case_details_tbl` a INNER JOIN
            (SELECT case_no,`type` FROM `cs_claimant_respondant_details_tbl` WHERE contact='" . $currentUser['phone_number'] . "'
            UNION ALL
            SELECT case_no,'Advocate' FROM `master_arbitrators_tbl` WHERE contact_no='" . $currentUser['phone_number'] . "'
            UNION ALL
            SELECT case_no,'Arbitrator' FROM `master_counsels_tbl` WHERE phone_number='" . $currentUser['phone_number'] . "') b ON a.slug=b.case_no");

        // $this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, DATE_FORMAT(cdt.reffered_on, '%d-%m-%Y') as reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,DATE_FORMAT(cdt.recieved_on, '%d-%m-%Y') as recieved_on, DATE_FORMAT(cdt.registered_on, '%d-%m-%Y') as registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.case_file, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
        // $this->db->from('cs_case_details_tbl AS cdt');
        // $this->db->join('gen_code_desc as gc', 'gc.gen_code = a.case_status AND gc.gen_code_group = "CASE_STATUS"', 'left');
        // $this->db->join('gen_code_desc as gc2', 'gc2.gen_code = a.reffered_by AND gc2.gen_code_group = "REFFERED_BY"', 'left');
        // $this->db->join('gen_code_desc as gc3', 'gc3.gen_code = a.type_of_arbitration AND gc3.gen_code_group = "TYPE_OF_ARBITRATION"', 'left');

        // $tempDb = clone $this->db;
        $this->db->limit($iDisplayLength, $iDisplayStart);

        // $res = $this->db->get();
        $query = $sqlResult->result_array();
        $output = array("aaData" => array());

        /*----FOR PAGINATION-----*/
        // $res1 = $tempDb->get();
        $output["draw"] = intval($this->input->post('draw'));
        $output['iTotalRecords'] = $sqlResult->num_rows();
        $output['iTotalDisplayRecords'] = $sqlResult->num_rows();
        $slno = $iDisplayStart + 1;

        foreach ($query as $aRow) {
            $row[0] = $slno;
            $row['sl_no'] = $slno;
            $i = 1;
            foreach ($aRow as $key => $value) {

                $row[$i] = $value;
                $row[$key] = $value;
                $i++;
            }
            $output['aaData'][] = $row;
            $slno++;
            unset($row);
        }
        echo json_encode($output);
        die;
    }

    public function view_case_fees()
    {
        // Get uri segment
        $case_no = $this->uri->segment(3);

        if (isset($case_no) && !empty($case_no)) {
            $data['menu_item'] = 'My Cases';
            $data['menu_group'] = 'My Cases';
            $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

            $page_status = page_status($data['sidebar'], $data['menu_item']);

            if ($page_status != 0) {
                $data['page_title'] = 'View Case Fees';

                $data['case_no'] = $case_no;

                $data['case_details'] = $this->case_model->get($data, 'GET_CASE_NUMBER_USING_SLUG');

                $data['case_amount_type'] = 'ARRAY_LIST';

                $data['balance_amount_data'] = $this->fees_model->get($data, 'CASE_AMOUNT_DETAILS');

                $data['fee_cost_data'] = $this->fees_model->get($data, 'GET_FEE_COST_DATA');
                $data['fee_deposited_data'] = $this->fees_model->get($data, 'GET_FEES_DEPOSITED_LIST');

                $data['case_detail'] = $this->common_model->get_efiling_user_case_using_case_slug($case_no);

                if ($this->session->userdata('role') == 'ADVOCATE') {
                    // Check for whom the advocate is fighting the case
                    $user = $this->common_model->get_user_details_using_user_code($this->session->userdata('user_code'));
                    $data['appearing_for'] = $this->efiling_model->check_advocate_appearing_for($user);
                }

                $this->load->view('templates/efiling/efiling-header', $data);
                $this->load->view('efiling/case/view_case_fees', $data);
                $this->load->view('templates/efiling/efiling-footer');
            } else {
                $this->load->view('templates/page_maintenance');
            }
        }
    }

    public function case_hearings()
    {
        // Get uri segment
        $case_no = $this->input->get('case_no');

        if (!isset($case_no) && empty($case_no)) {
            return redirect(base_url('efiling/my-cases'));
        }

        $data['menu_item'] = 'My Cases';
        $data['menu_group'] = 'My Cases';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {
            $data['case_details'] = $this->case_model->get(['case_no' => $case_no], 'GET_CASE_NUMBER_USING_SLUG');

            $data['page_title'] = 'Case Hearings';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/case/case_hearings.php');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function get_case_hearings()
    {
        $order = '';
        $Ocolumn = '';
        $Odir = '';
        $order = $this->input->post('order');
        if ($order) {
            foreach ($order as $row) {
                $Ocolumn = $row['column'];
                $Odir = $row['dir'];
            }
            $this->db->order_by($Ocolumn, $Odir);
        } else {
            $this->db->order_by(1, "ASC");
        }
        $search = $this->input->post('search');
        $header = array('case_title', 'case_no'); //search filter will work on this column
        if ($search['value'] != '') {
            for ($i = 0; $i < count($header); $i++) {
                $this->db->or_like($header[$i], $search['value']);
            }
        }
        $iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
        $iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

        // Get current user details
        // $currentUser = $this->getter_model->get('', 'get_current_loggedin_user');

        $this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc ");
        $this->db->from('cause_list_tbl AS clt');
        $this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
        $this->db->where('clt.case_no', $this->input->post('hidden_case_no'));
        $this->db->order_by('clt.id', 'DESC');
        $sqlResult = $this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');

        $tempDb = clone $this->db;
        $this->db->limit($iDisplayLength, $iDisplayStart);

        // $res = $this->db->get();
        $query = $sqlResult->get()->result_array();
        $output = array("aaData" => array());

        /*----FOR PAGINATION-----*/
        // $res1 = $tempDb->get();
        $output["draw"] = intval($this->input->post('draw'));
        $output['iTotalRecords'] = count($query);
        $output['iTotalDisplayRecords'] = $tempDb->count_all_results();
        $slno = $iDisplayStart + 1;

        foreach ($query as $aRow) {
            $row[0] = $slno;
            $row['sl_no'] = $slno;
            $i = 1;
            foreach ($aRow as $key => $value) {

                $row[$i] = $value;
                $row[$key] = $value;
                $i++;
            }
            $output['aaData'][] = $row;
            $slno++;
            unset($row);
        }
        echo json_encode($output);
        die;
    }
}
