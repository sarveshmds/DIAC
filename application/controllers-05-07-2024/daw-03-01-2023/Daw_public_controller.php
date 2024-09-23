<?php

defined('BASEPATH') or exit('No direct script access allowed');

// DOMPDF: For greater than php 7 =================
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'dompdf_php7' . DIRECTORY_SEPARATOR . 'autoload.inc.php';

use Dompdf\Dompdf;

class Daw_public_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('my_encryption');
        # models
        $this->load->model(array('daw_model'));
    }

    public function show_person_details($id)
    {
        $data['encrypted_id'] = $id;
        $id =  $this->my_encryption->safe_b64decode($id);
        $data['person'] = $this->daw_model->get_single_registration_data($id);
        $data['person_qr_code'] = $this->daw_model->generate_qr_code($data['person']);

        $this->load->view('daw/person_registration_info', $data);
    }

    public function generate_public_single_pdf($id)
    {
        try {
            ini_set('max_execution_time', 1200);
            $data['encrypted_id'] = $id;
            $id =  $this->my_encryption->safe_b64decode($id);

            $data['page_title'] = 'DAW Registrations - 2023';

            $data['person'] = $this->daw_model->get_single_registration_data($id);

            $data['person_qr_code'] = $this->daw_model->generate_qr_code($data['person']);

            $pdf_data = $this->load->view('daw/export/header', $data, true);
            $pdf_data .= $this->load->view('daw/export/daw_persons_registrations', $data, true);
            $pdf_data .= $this->load->view('daw/export/footer', $data, true);
            $this->convert_into_pdf($pdf_data, 'DAW Registrations', 'Portrait');
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'msg' => 'Server failed while generating PDF.'
            ]);
        }
    }

    public function convert_into_pdf($data, $filename = 'SM', $mode = 'Portrait')
    {
        //print_r($data);die;
        $dompdf = new Dompdf();
        //$dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->load_html(html_entity_decode($data));

        $dompdf->set_paper('A4', $mode);

        $dompdf->render();
        //print_r($data);die;
        $today_date = date('d-m-Y');
        $dompdf->stream($filename . '-' . $today_date, array("Attachment" => false));
    }
}
