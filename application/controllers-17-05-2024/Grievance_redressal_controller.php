<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grievance_redressal_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');

        $this->load->model(['superadmin_model', 'common_model', 'getter_model', 'grievance_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
	}

	public function index()
	{
		$sidebar['menu_item'] = 'All Grievance';
        $sidebar['menu_group'] = 'All Grievance';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'All Grievance';

            $this->load->view('diac-admin/grievance_redressal', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
	}

	public function GetDatatableData()
	{
		// $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $this->db->select("*");
        $this->db->from('grievance_redressal_tbl');
        $this->db->where('record_status', 1);

        // Clone the db instance
        $tempDb = clone $this->db;

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die;
        $fetch_data = $query->result();

        $recordsFiltered = $tempDb->count_all_results();

        // Records total
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('grievance_redressal_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
	}

	public function viewSingleRecord()
	{
		$data['grievance_code'] = $this->input->get('code');
		// print_r($data);
		// die;

        // CHECK IF THE CODE IS AVAILABLE OR NOT
        if (!$data['grievance_code']) {
            return redirect('all-grievance');
        }

        // FETCH THE DATA USING ID
        $data['grievance_data'] = $this->grievance_model->check_grievance_is_reg($data['grievance_code']);
        if (!$data['grievance_data']) {
            return redirect('arbitrator-empanelment');
        }

       	$sidebar['menu_item'] = 'All Grievance';
        $sidebar['menu_group'] = 'All Grievance';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['grievance_options_data'] = $this->grievance_model->get_options_data($data['grievance_code']);

            $data['page_title'] = 'View Grievance';

            $this->load->view('diac-admin/view_grievance', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
	}

}

/* End of file Grievance_redressal_controller.php */
/* Location: ./application/controllers/Grievance_redressal_controller.php */