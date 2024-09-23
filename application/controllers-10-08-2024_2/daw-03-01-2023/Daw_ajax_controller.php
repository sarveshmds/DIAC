<?php

defined('BASEPATH') or exit('No direct script access allowed');

// DOMPDF: For greater than php 7 =================
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'dompdf_php7' . DIRECTORY_SEPARATOR . 'autoload.inc.php';

use Dompdf\Dompdf;

class Daw_ajax_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        # models
        $this->load->model(array('daw_model'));

        $this->user_code = $this->session->userdata('user_code');
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
            'DIAC' => array('get_all_registrations', 'registration_approval'),
            'DAW' => array('get_all_registrations', 'registration_approval'),
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

    public function page_not_found()
    {
        $this->load->view('templates/404');
    }

    public function get_all_registrations()
    {
        echo json_encode($this->daw_model->get_all_registrations());
    }

    public function registration_approval()
    {
        $this->form_validation->set_rules('hidden_id', 'ID', 'required|xss_clean');
        $this->form_validation->set_rules('application_status', 'Application Status', 'required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'status' => false,
                'msg' => validation_errors()
            ));
            die;
        }

        $application_status = $this->input->post('application_status');

        // Get the data
        $data = [
            'application_status' => $application_status,
            'updated_by' => $this->user_code,
            'updated_at' => currentDateTimeStamp(),
        ];

        $result = $this->db->where('id', $this->input->post('hidden_id'))->update('daw_registrations_tbl', $data);

        if ($result) {

            // Fetch person details
            $data['person'] = $this->daw_model->get_single_registration_data($this->input->post('hidden_id'));

            $data['page_title'] = 'DAW Registrations - 2023';

            $msg = '';
            if ($application_status == 1) {
                $msg = 'Registration approved and mail sent successfully.';

                // Generate document
                $data['person_qr_code'] = $this->daw_model->generate_qr_code($data['person']);
                $print_data[] = $this->load->view('daw/export/daw_persons_registrations', $data, true);

                $pdf_data = $this->load->view('daw/export/header', $data, true);
                $pdf_data .= implode('', $print_data);
                $pdf_data .= $this->load->view('daw/export/footer', $data, true);

                $filename = 'PER_REG_' . time() . rand(999, 9999) . '.pdf';

                $sent_status = $this->convert_into_pdf_and_store($pdf_data, DAW_REG_MAIL_PDF_STORAGE_PATH, $filename, 'Portrait');

                if ($sent_status == false) {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'Server failed while generating file.'
                    ]);
                    die;
                }

                // Send mail
                $sendStatus = send_daw_registration_approval_mail($data['person'], DAW_REG_MAIL_PDF_STORAGE_PATH . $filename);

                if ($sendStatus == false) {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'Error while sending confirmation email.'
                    ]);
                    die;
                }
            }

            if ($application_status == 2) {
                $msg = 'Registration rejected and mail sent successfully.';

                // Send mail
                $sendStatus = send_daw_registration_rejection_mail($data['person']);

                if ($sendStatus == false) {
                    echo json_encode([
                        'status' => false,
                        'msg' => 'Error while sending rejection email.'
                    ]);
                    die;
                }
            }

            echo json_encode(array(
                'status' => true,
                'msg' => $msg
            ));
            die;
        }

        echo json_encode(array(
            'status' => false,
            'msg' => 'Server failed while saving data'
        ));
        die;
    }

    public function convert_into_pdf_and_store($data, $storage_path, $filename, $orientation = 'landscape')
    {
        if (!$filename) {
            $filename = time() . rand() . '.pdf';
        }

        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);

        $dompdf->set_paper('A4', $orientation);

        $dompdf->loadHtml($data);
        $dompdf->render();

        $today_date = date('d-m-Y');

        $output = $dompdf->output();
        $storage_path = $storage_path;

        file_put_contents($storage_path . $filename, $output);
        return true;
        try {
        } catch (Exception $e) {
            return false;
        }

        // $dompdf->stream($filename . '-' . $today_date, array("Attachment" => false));
    }
}
