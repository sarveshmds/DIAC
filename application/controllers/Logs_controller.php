<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logs_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        # models
        $this->load->model(array('common_model', 'getter_model', 'notification_model', 'logs_model'));

        // Get notification
        $data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');
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
            'DIAC' => array('email_logs', 'get_datatable_email_logs', 'whatsapp_logs', 'get_datatable_whatsapp_logs'),
            'COORDINATOR' => array('email_logs', 'get_datatable_email_logs', 'whatsapp_logs', 'get_datatable_whatsapp_logs'),
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

    /**
     * Function to show the email logs
     */
    public function email_logs()
    {
        $sidebar['menu_item'] = 'Email Logs';
        $sidebar['menu_group'] = 'Logs';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Email Logs';

        if ($page_status != 0) {
            $this->load->view('logs/email_logs', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to fetch for the datatable
     */
    public function get_datatable_email_logs()
    {
        $select_column = array("smr.mail_type", "smr.to_mail", "smr.subject", "smr.status", "smr.created_at");
        $order_column = array(null, "smr.mail_type", "smr.to_mail", "smr.subject", "smr.status", "smr.created_at");

        $this->db->select("smr.*, DATE_FORMAT(smr.created_at, '%d-%m-%Y') as sent_on");
        $this->db->from('support_mail_record smr');

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
            $this->db->order_by('smr.id', 'DESC');
        }

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        $fetch_data = $query->result();

        // Filter records
        $recordsFiltered = $this->db->select("*")->from('support_mail_record')->count_all_results();

        // Records total
        $recordsTotal = $this->db->select("*")->from('support_mail_record')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );

        echo json_encode($output);
        die;
    }

    /**
     * Function to show the whatsapp logs
     */
    public function whatsapp_logs()
    {
        $sidebar['menu_item'] = 'Whatsapp Logs';
        $sidebar['menu_group'] = 'Logs';
        $sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');

        $data['title'] = $this->getter_model->get(null, 'get_title');
        $this->load->view('templates/header', $data);
        $this->load->view('templates/side_menu', $sidebar);
        $page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

        $data['page_title'] = 'Whatsapp Logs';

        if ($page_status != 0) {
            $this->load->view('logs/whatsapp_logs', $data);
        } else {
            $this->load->view('templates/page_maintenance');
        }
        $this->load->view('templates/footer');
    }

    /**
     * Function to fetch for the datatable
     */
    public function get_datatable_whatsapp_logs()
    {
        $select_column = array("smr.type", "smr.phone_number", "smr.message", "smr.status", "smr.created_at");
        $order_column = array(null, "smr.type", "smr.phone_number", "smr.message", "smr.status", "smr.created_at");

        $this->db->select("smr.*, DATE_FORMAT(smr.created_at, '%d-%m-%Y') as sent_on");
        $this->db->from('support_whatsapp_record smr');

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
            $this->db->order_by('smr.id', 'DESC');
        }

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        $fetch_data = $query->result();

        // Filter records
        $recordsFiltered = $this->db->select("*")->from('support_whatsapp_record')->count_all_results();

        // Records total
        $recordsTotal = $this->db->select("*")->from('support_whatsapp_record')->count_all_results();

        // Output
        $output = array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $fetch_data
        );

        echo json_encode($output);
        die;
    }
}
