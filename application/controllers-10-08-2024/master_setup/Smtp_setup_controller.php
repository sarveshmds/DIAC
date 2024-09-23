<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Smtp_setup_controller extends CI_Controller
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
        $this->load->model(['superadmin_model', 'common_model', 'category_model', 'getter_model', 'master_setup/smtp_setup_model']);

        $this->user_code = $this->session->userdata('user_code');
        $this->user_name = $this->session->userdata('user_name');
    }

    public function _remap($method)
    {
        $class = $this->router->class;
        $role = $this->session->userdata('role');
        $check_user = $this->getter_model->get(null, 'get_user_check');
        $role_action_auth = array(
            'DIAC' => array('index', 'add', 'store', 'get_datatable_data', 'edit', 'update'),
        );

        if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
            redirect('logout');
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

    public function index()
    {
        $sidebar['menu_item'] = 'SMTP Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'SMTP Setup';

            $this->load->view('master_setup/smtp_setup/smtp_setup', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $sidebar['menu_item'] = 'SMTP Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Add New SMTP Setup';

            $this->load->view('master_setup/smtp_setup/add_smtp_setup', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function store()
    {

        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'add_form')) {
                $config = array(
                    array(
                        'field' => 'provider_name',
                        'label' => 'Provider Name',
                        'rules' => 'required|xss_clean|max_length[50]'
                    ),
                    array(
                        'field' => 'host_name',
                        'label' => 'Host Name',
                        'rules' => 'required|xss_clean|max_length[100]'
                    ),
                    array(
                        'field' => 'port_no',
                        'label' => 'Port No',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'email_id',
                        'label' => 'Email ID',
                        'rules' => 'required|xss_clean|valid_email|max_length[100]'
                    ),
                    array(
                        'field' => 'username',
                        'label' => 'Username',
                        'rules' => 'required|xss_clean|max_length[100]'
                    ),
                    array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required|xss_clean|max_length[50]'
                    ),
                    array(
                        'field' => 'smtp_auth',
                        'label' => 'SMTP Auth',
                        'rules' => 'required|xss_clean|max_length[10]'
                    ),
                    array(
                        'field' => 'smtp_secure',
                        'label' => 'SMTP Secure',
                        'rules' => 'required|xss_clean|max_length[10]'
                    )
                );

                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE) {
                    failure_response(validation_errors());
                } else {

                    $this->db->trans_begin();

                    // ========================================
                    $data = array(
                        'provider_name' => custom_xss_clean($this->input->post('provider_name')),
                        'host_name' => custom_xss_clean($this->input->post('host_name')),
                        'port_no' => custom_xss_clean($this->input->post('port_no')),
                        'email_id' => custom_xss_clean($this->input->post('email_id')),
                        'username' => custom_xss_clean($this->input->post('username')),
                        'password' => custom_xss_clean($this->input->post('password')),
                        'smtp_auth' => custom_xss_clean($this->input->post('smtp_auth')),
                        'smtp_secure' => custom_xss_clean($this->input->post('smtp_secure')),
                        'cc_email_id' => custom_xss_clean($this->input->post('cc_email_id')),
                        'ip_address' => "",
                        'institute_code' => "N/A",
                        'record_status' => custom_xss_clean($this->input->post('status')),
                        'created_by' => $this->user_code,
                        'created_on' => currentDateTimeStamp(),
                        'updated_by' => $this->user_code,
                        'updated_on' => currentDateTimeStamp(),
                    );

                    $result = $this->db->insert('email_provider_setup', $data);

                    if (!$result) {
                        failed_response_with_rollback('Server failed while saving record');
                    }

                    success_response_with_commit('Record saved successfully', [
                        'redirect_url' => base_url('smtp-setup')
                    ]);
                }
            } else {
                failure_response(INVALID_TOKEN_ERROR, false, false);
            }
        } else {
            failure_response(EMPTY_TOKEN_ERROR, false, false);
        }
    }

    public function get_datatable_data()
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
        $header = array('category_name'); //search filter will work on this column
        if ($search['value'] != '') {
            for ($i = 0; $i < count($header); $i++) {
                $this->db->or_like($header[$i], $search['value']);
            }
        }
        $iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
        $iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

        $this->db->select('*');
        $this->db->from('email_provider_setup');
        $this->db->order_by('created_on DESC');

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

    public function edit()
    {
        $provider_id = $this->input->get('provider_id');
        if ($provider_id == '') {
            return redirect(base_url('master-setup/smtp-setup'));
        }

        $data['email_setup'] = $this->smtp_setup_model->get_smtp_setup_using_id($provider_id);

        if (!$data['email_setup']) {
            return redirect(base_url('master-setup/smtp-setup'));
        }

        $sidebar['menu_item'] = 'SMTP Setup';
        $sidebar['menu_group'] = 'Master Setup';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        # views
        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);

        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
        if ($page_status != 0) {

            $data['page_title'] = 'Edit SMTP Setup';

            $this->load->view('master_setup/smtp_setup/add_smtp_setup', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    public function update()
    {
        $inputCsrfToken = $_POST['csrf_frm_token'];
        if ($inputCsrfToken != '') {
            if (checkToken($inputCsrfToken, 'add_form')) {
                $config = array(
                    array(
                        'field'   => 'hidden_provider_id',
                        'label'   => 'ID',
                        'rules'   => 'required|xss_clean'
                    ),
                    array(
                        'field' => 'provider_name',
                        'label' => 'Provider Name',
                        'rules' => 'required|xss_clean|max_length[50]'
                    ),
                    array(
                        'field' => 'host_name',
                        'label' => 'Host Name',
                        'rules' => 'required|xss_clean|max_length[100]'
                    ),
                    array(
                        'field' => 'port_no',
                        'label' => 'Port No',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'email_id',
                        'label' => 'Email ID',
                        'rules' => 'required|xss_clean|valid_email|max_length[100]'
                    ),
                    array(
                        'field' => 'username',
                        'label' => 'Username',
                        'rules' => 'required|xss_clean|max_length[100]'
                    ),
                    array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required|xss_clean|max_length[50]'
                    ),
                    array(
                        'field' => 'smtp_auth',
                        'label' => 'SMTP Auth',
                        'rules' => 'required|xss_clean|max_length[10]'
                    ),
                    array(
                        'field' => 'smtp_secure',
                        'label' => 'SMTP Secure',
                        'rules' => 'required|xss_clean|max_length[10]'
                    )
                );

                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE) {
                    failure_response(validation_errors());
                } else {

                    $this->db->trans_begin();

                    // -----------------------------------------
                    if ($this->input->post('status') == 1) {
                        // Set the all other SMTP inactive
                        $upResult = $this->db->update('email_provider_setup', [
                            'record_status' => 0
                        ]);

                        if (!$upResult) {
                            failed_response_with_rollback('Error while updating records');
                        }
                    }

                    $data = [
                        'provider_name' => custom_xss_clean($this->input->post('provider_name')),
                        'host_name' => custom_xss_clean($this->input->post('host_name')),
                        'port_no' => custom_xss_clean($this->input->post('port_no')),
                        'email_id' => custom_xss_clean($this->input->post('email_id')),
                        'username' => custom_xss_clean($this->input->post('username')),
                        'password' => custom_xss_clean($this->input->post('password')),
                        'smtp_auth' => custom_xss_clean($this->input->post('smtp_auth')),
                        'smtp_secure' => custom_xss_clean($this->input->post('smtp_secure')),
                        'cc_email_id' => custom_xss_clean($this->input->post('cc_email_id')),
                        'ip_address' => "",
                        'institute_code' => "N/A",
                        'record_status' => custom_xss_clean($this->input->post('status')),
                        'updated_by' => $this->user_code,
                        'updated_on' => currentDateTimeStamp(),
                    ];

                    $result = $this->db->where('provider_id', $this->input->post('hidden_provider_id'))->update('email_provider_setup', $data);

                    if (!$result) {
                        failed_response_with_rollback('Server failed while saving record');
                    }

                    success_response_with_commit('Record updated successfully', [
                        'redirect_url' => base_url('smtp-setup')
                    ]);
                }
            } else {
                failure_response(INVALID_TOKEN_ERROR);
            }
        } else {
            failure_response(EMPTY_TOKEN_ERROR);
        }
    }
}
