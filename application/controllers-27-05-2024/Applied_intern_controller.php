<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applied_intern_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$sidebar['menu_item'] = 'Applied Internship';
        $sidebar['menu_group'] = 'Applied Internship';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Applied Internship';

            $this->load->view('diac-admin/applied_internship', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
	}

	function getDatatableData()
	{

		// $select_column = array(null, "reffered_on", "registered_on");
        $order_column = array(null, null, null, null, null, null, null, null);

        $query = $this->db->select("*");
        $query = $this->db->from('internship_tbl');
        $query = $this->db->where('record_status', 1);

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
        $recordsTotal = $this->db->where('record_status', 1)->select("*")->from('internship_tbl')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );
        echo json_encode($output);
	}

	function viewSingleRecord()
	{
		$data['intern_code'] = $this->input->get('code');

        // CHECK IF THE CODE IS AVAILABLE OR NOT
        if (!$data['intern_code']) {
            return redirect('applied-internship');
        }

        // FETCH THE DATA USING ID
        $this->db->select('*');
        $this->db->from('internship_tbl');
        $this->db->where('code', $data['intern_code']);
        $this->db->where('record_status', 1);
        $intern_row_data = $this->db->get()->row_array();

        if (!$intern_row_data) {
            // If no data is found, redirect to 'arbitrator-empanelment'
            return redirect('applied-internship');
        }

        // Assign the intern data to the $data array
        $data['intern_data'] = $intern_row_data;


		$sidebar['menu_item'] = 'Applied Internship';
        $sidebar['menu_group'] = 'Applied Internship';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'View Applied Internship';

            $this->load->view('diac-admin/view_applied_internship', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
	}

}

/* End of file Applied_intern_controller.php */
/* Location: ./application/controllers/Applied_intern_controller.php */