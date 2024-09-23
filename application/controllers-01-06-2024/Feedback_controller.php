<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$sidebar['menu_item'] = 'All Feedbacks';
        $sidebar['menu_group'] = 'All Feedbacks';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'All Feedbacks';

            $this->load->view('diac-admin/all_feedbacks', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
	}

	public function GetDatatableData()
	{
        $name_of_arbitrator = $this->input->post('name_of_arbitrator');
        $name_of_advocate = $this->input->post('name_of_advocate');
        $diac_case_number = $this->input->post('diac_case_number');

		// $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $query = $this->db->select("*");
        $query = $this->db->from('feedback_tbl');
        $query = $this->db->where('status', 1);

        if (!empty($name_of_arbitrator)) {
            $query = $this->db->like('name_of_arbitrator',$name_of_arbitrator);
        }

        if (!empty($name_of_advocate)) {
            $query = $this->db->like('name_of_advocate',$name_of_advocate);
        }

        if (!empty($diac_case_number)) {
            $query = $this->db->like('diac_case_number',$diac_case_number);
        }

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
        $recordsTotal = $this->db->where('status', 1)->select("*")->from('feedback_tbl')->count_all_results();

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
        $data['feedback_code'] = $this->input->get('code');

        // CHECK IF THE CODE IS AVAILABLE OR NOT
        if (!$data['feedback_code']) {
            return redirect('all-feedback');
        }

        // FETCH THE DATA USING ID
        $this->db->select('*');
        $this->db->from('feedback_tbl');
        $this->db->where('code', $data['feedback_code']);
        $this->db->where('status', 1);
        $feedback_row_data = $this->db->get()->row_array();

        if (!$feedback_row_data) {
            // If no data is found, redirect to 'arbitrator-empanelment'
            return redirect('all-feedback');
        }

        // Assign the feedback data to the $data array
        $data['feedback_data'] = $feedback_row_data;

        $sidebar['menu_item'] = 'All Feedbacks';
        $sidebar['menu_group'] = 'All Feedbacks';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'View Feedbacks';

            $this->load->view('diac-admin/view_feedbacks', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

}

/* End of file Feedback_controller.php */
/* Location: ./application/controllers/Feedback_controller.php */