<?php defined('BASEPATH') or exit('No direct script access allowed');

class Request_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'application_model', 'request_model']);
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'ADVOCATE' => array('requests', 'add_requests', 'edit_requests', 'getDatatableList', 'store_request', 'update_request'),
            'PARTY' => array('requests', 'add_requests', 'edit_requests', 'getDatatableList', 'store_request', 'update_request'),
            'ARBITRATOR' => array('requests', 'add_requests', 'edit_requests', 'getDatatableList', 'store_request', 'update_request'),
            'ADVOCATE_ARBITRATOR' => array('requests', 'add_requests', 'edit_requests', 'getDatatableList', 'store_request', 'update_request'),
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

    public function requests()
    {
        $data['menu_item'] = 'Requests';
        $data['menu_group'] = 'Requests';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Requests';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/requests/requests');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function add_requests()
    {
        $data['menu_item'] = 'Requests';
        $data['menu_group'] = 'Requests';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Make Requests';
            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/requests/add_requests');
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }

    public function store_request()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm3')) {
                $rowCount = $this->request_model->checkCount();
                $diaryNumber = generateDiaryNumber($rowCount, 'RE');

                $data_arr = array(
                    'r_code' => generateCode(),
                    'user_code' => $this->session->userdata('user_code'),
                    'diary_number' => $diaryNumber,
                    'request_title' => $this->input->post('request_title'),
                    'case_number' => $this->input->post('case_number'),
                    'message' => $this->input->post('message'),
                    'document' => '',
                    'application_status' => 2, // 2 for pending
                    'created_by' => 'USER',
                    'created_on' => now(),
                    'updated_by' => 'USER',
                    'updated_on' => now(),
                );

                // Upload the file ==============================================
                if ($_FILES['upload_document']['name'] != '') {
                    $this->load->library('fileupload');
                    // Upload files ==============
                    $upload_document_result = $this->fileupload->uploadSingleFile($_FILES['upload_document'], [
                        'raw_file_name' => 'upload_document',
                        'file_name' => 'REQUEST_' . time(),
                        'file_move_path' => EFILING_REQUEST_UPLOADS_FOLDER,
                        'allowed_file_types' => FILE_FORMATS_ALLOWED,
                        'allowed_mime_types' => array('application/pdf')
                    ]);

                    // After getting result of file upload
                    if ($upload_document_result['status'] == false) {
                        $this->db->trans_rollback();
                        return $upload_document_result;
                    } else {
                        $data_arr['document'] = $upload_document_result['file'];
                    }
                }

                $result = $this->request_model->insert($data_arr);
                if ($result) {
                    echo  json_encode([
                        'status' => true,
                        'msg' => 'Request sent successfully.'
                    ]);
                } else {
                    echo  json_encode([
                        'status' => false,
                        'msg' => 'Server failed while saving data'
                    ]);
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
        }
    }

    public function edit_requests()
    {
        if ($this->input->get('id')) {
            return redirect('page-not-found');
        }

        $data['request'] = $this->request_model->get_single_request_using_id($this->input->get('id'));

        if (!$data['request'] && $data['request']['application_status'] != 0) {
            return redirect(base_url('efiling/requests'));
        }

        $data['menu_item'] = 'Requests';
        $data['menu_group'] = 'Requests';
        $data['sidebar'] = $this->getter_model->get($data, 'get_sidebar');

        $page_status = page_status($data['sidebar'], $data['menu_item']);

        if ($page_status != 0) {

            $data['page_title'] = 'Edit Requests';

            $this->load->view('templates/efiling/efiling-header', $data);
            $this->load->view('efiling/requests/add_requests', $data);
            $this->load->view('templates/efiling/efiling-footer');
        } else {
            $this->load->view('templates/page_maintenance');
        }
    }
    public function update_request()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'frm3')) {

                // Validation

                $id = $this->input->post('hidden_id');

                $data_arr = array(
                    'request_title' => $this->input->post('request_title'),
                    'case_number' => $this->input->post('case_number'),
                    'message' => $this->input->post('message'),
                    'application_status' => 2, // 2 for pending
                    'updated_by' => 'USER',
                    'updated_on' => now(),
                );

                $result = $this->request_model->update($data_arr, $id);
                if ($result) {
                    echo  json_encode([
                        'status' => true,
                        'msg' => 'Data update successfully.'
                    ]);
                } else {
                    echo  json_encode([
                        'status' => false,
                        'msg' => 'Server failed while saving data'
                    ]);
                }
            } else {
                $data = array(
                    'status' => false,
                    'msg' => 'Invalid Security Token'
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                'status' => false,
                'msg' => 'Empty Security Token'
            );
            echo json_encode($data);
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
        $header = array('app_type'); //search filter will work on this column
        if ($search['value'] != '') {
            for ($i = 0; $i < count($header); $i++) {
                $this->db->or_like($header[$i], $search['value']);
            }
        }
        $iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
        $iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

        $this->db->from('efiling_requests_tbl eat');
        $this->db->select('eat.*');
        $this->db->where('eat.user_code', $this->session->userdata('user_code'));
        $this->db->order_by('eat.id', 'DESC');

        $tempDb = clone $this->db;
        $this->db->limit($iDisplayLength, $iDisplayStart);

        $res = $this->db->get();
        $query = $res->result_array();
        $output = array("aaData" => array());

        /*----FOR PAGINATION-----*/
        $res1 = $tempDb->get();
        $output["draw"] = intval($this->input->post('draw'));
        $output['iTotalRecords'] = $res1->num_rows();
        $output['iTotalDisplayRecords'] = $res1->num_rows();
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
