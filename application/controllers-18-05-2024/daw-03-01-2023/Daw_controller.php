<?php
defined('BASEPATH') or exit('No direct script access allowed');

// DOMPDF: For greater than php 7 =================
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'dompdf_php7' . DIRECTORY_SEPARATOR . 'autoload.inc.php';

use Dompdf\Dompdf;

// Php spreadsheet =============
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'phpspreadsheet' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Daw_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		header("Access-Control-Allow-Origin: domain.com");

		# models
		$this->load->model(array('daw_model'));

		// Get notification
		$data['notification'] = $this->notification_model->get('', 'CHECK_NOTIFICATION');

		# views
		$data['title'] = $this->getter_model->get(null, 'get_title');
		$this->load->view('templates/header', $data);
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
			'DIAC' => array('all_registrations', 'view_registration_details', 'get_all_registrations', 'generate_registrations_excel', 'generate_registrations_pdf', 'generate_registrations_single_pdf'),
			'DAW' => array('all_registrations', 'view_registration_details', 'get_all_registrations', 'generate_registrations_excel', 'generate_registrations_pdf', 'generate_registrations_single_pdf', 'daw_dashboard', 'approved_registrations', 'rejected_registrations'),
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

	public function daw_dashboard()
	{
		$sidebar['menu_item'] = 'Dashboard';
		$sidebar['menu_group'] = 'Dashboard';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'All DAW Registrations';

		if ($page_status != 0) {

			$data['registrant_categories'] = $this->daw_model->get_gen_code_data('REGISTRANT_CATEGORY');
			$data['countries'] = $this->daw_model->get_countries();

			$this->load->view('daw/backend/daw_dashboard', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function view_registration_details()
	{
		$id = $this->input->get('id');
		if ($id) {
			$sidebar['menu_item'] = 'All Registrations';
			$sidebar['menu_group'] = 'DAW';
			$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
			$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
			$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
			$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

			$this->load->view('templates/side_menu', $sidebar);
			$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

			if ($page_status != 0) {
				$data['registration_data'] = $this->daw_model->get_single_registration_data($id);

				$data['page_title'] = 'Reg. No.: ' . $data['registration_data']['reg_number'];

				$this->load->view('daw/backend/view_registration_details', $data);
			} else {
				$this->load->view('templates/page_maintenance');
			}
			$this->load->view('templates/footer');
		} else {
			redirect('diac-admin-dashboard');
		}
	}

	public function all_registrations()
	{
		$sidebar['menu_item'] = 'All Registrations';
		$sidebar['menu_group'] = 'DAW';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'All DAW Registrations';

		if ($page_status != 0) {
			$data['application_status'] = 'ALL';
			$data['registrant_categories'] = $this->daw_model->get_gen_code_data('REGISTRANT_CATEGORY');
			$data['countries'] = $this->daw_model->get_countries();

			$this->load->view('daw/backend/all_registrations', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function approved_registrations()
	{
		$sidebar['menu_item'] = 'Approved Registrations';
		$sidebar['menu_group'] = 'DAW';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'Approved Registrations';

		if ($page_status != 0) {
			$data['application_status'] = 'APPROVED';
			$data['registrant_categories'] = $this->daw_model->get_gen_code_data('REGISTRANT_CATEGORY');
			$data['countries'] = $this->daw_model->get_countries();

			$this->load->view('daw/backend/all_registrations', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function rejected_registrations()
	{
		$sidebar['menu_item'] = 'Rejected Registrations';
		$sidebar['menu_group'] = 'DAW';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['total_cases'] = $this->getter_model->get(null, 'GET_TOTAL_CASES');
		$data['total_action'] = $this->getter_model->get(null, 'GET_TOTAL_ACTION');
		$data['total_cases_month'] = $this->getter_model->get(null, 'GET_TOTAL_CASES_CURRENT_MONTH');

		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);

		$data['page_title'] = 'Rejected Registrations';

		if ($page_status != 0) {
			$data['application_status'] = 'REJECTED';
			$data['registrant_categories'] = $this->daw_model->get_gen_code_data('REGISTRANT_CATEGORY');
			$data['countries'] = $this->daw_model->get_countries();

			$this->load->view('daw/backend/all_registrations', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function get_all_registrations()
	{
		echo json_encode($this->daw_model->get_all_registrations());
	}

	public function generate_registrations_pdf()
	{
		try {
			ini_set('max_execution_time', 1200);

			$data['page_title'] = 'DAW Registrations - 2023';
			$filter_data = json_decode($this->input->post('filter_data'), true);


			// $farmer_id_list = json_decode($this->input->post('farmers_id_list'));
			$print_data = array();
			$data['persons'] = $this->daw_model->get_registrations_export_data($filter_data['filter_data']);

			// echo '<pre>';
			// print_r($data['persons']);
			// die;

			foreach ($data['persons'] as $row) {
				$data['person'] = $this->daw_model->get_single_registration_data($row['id']);

				$data['person_qr_code'] = $this->daw_model->generate_qr_code($data['person']);

				$print_data[] = $this->load->view('daw/export/daw_persons_registrations', $data, true);
			}

			$pdf_data = $this->load->view('daw/export/header', $data, true);
			$pdf_data .= implode('', $print_data);
			$pdf_data .= $this->load->view('daw/export/footer', $data, true);
			$this->convert_into_pdf($pdf_data, 'DAW Registrations', 'Portrait');
		} catch (Exception $e) {
			echo json_encode([
				'status' => false,
				'msg' => 'Server failed while generating PDF.'
			]);
		}
	}

	public function generate_registrations_excel()
	{
		$sNo = 1;
		$rowCount = 4;

		$filter_data = json_decode($this->input->post('filter_data'), true);

		$persons_data = $this->daw_model->get_registrations_export_data($filter_data['filter_data']);



		// Create new instance of spreadsheet
		$objPHPExcel = new Spreadsheet();

		// Add some data to the second sheet, resembling some different data types
		$objPHPExcel->setActiveSheetIndex(0);

		// Set the getActiveSheet instance
		$getActiveSheet = $objPHPExcel->getActiveSheet();

		$this->mergeCells($getActiveSheet, 'A1:S2');
		$this->setCellValue($getActiveSheet, 'A1', 'DAW Registrations - 2023, DIAC');
		$this->setStyle($getActiveSheet, 'A1', array(
			'font'  => array('bold'  => true, 'size' => '14'),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'A3', 'Sl No');
		$this->setStyle($getActiveSheet, 'A3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'B3', 'Title');
		$this->setStyle($getActiveSheet, 'B3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'C3', 'First Name');
		$this->setStyle($getActiveSheet, 'C3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'D3', 'Last Name');
		$this->setStyle($getActiveSheet, 'D3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'E3', 'Nickname/Preferred Name');
		$this->setStyle($getActiveSheet, 'E3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'F3', 'Email Address');
		$this->setStyle($getActiveSheet, 'F3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'G3', 'Registrant Category');
		$this->setStyle($getActiveSheet, 'G3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'H3', 'Organization');
		$this->setStyle($getActiveSheet, 'H3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'I3', 'Country');
		$this->setStyle($getActiveSheet, 'I3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'J3', 'State');
		$this->setStyle($getActiveSheet, 'J3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'K3', 'City');
		$this->setStyle($getActiveSheet, 'K3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'L3', 'Address Line 1');
		$this->setStyle($getActiveSheet, 'L3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'M3', 'Address Line 2');
		$this->setStyle($getActiveSheet, 'M3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'N3', 'Address Line 3');
		$this->setStyle($getActiveSheet, 'N3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'O3', 'Zip Code/Postal Code');
		$this->setStyle($getActiveSheet, 'O3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'P3', 'Mobile Number');
		$this->setStyle($getActiveSheet, 'P3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'Q3', 'Telephone');
		$this->setStyle($getActiveSheet, 'Q3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'R3', 'Remarks');
		$this->setStyle($getActiveSheet, 'R3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'S3', 'Heared about this event from?');
		$this->setStyle($getActiveSheet, 'S3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		foreach ($persons_data as $case) {

			$this->setCellValue($getActiveSheet, 'A' . $rowCount, $sNo);
			$this->setCellValue($getActiveSheet, 'B' . $rowCount, $case['salutation_desc']);
			$getActiveSheet->getColumnDimension('B')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'C' . $rowCount, $case['first_name']);
			$getActiveSheet->getColumnDimension('C')->setWidth(25);
			$this->setCellValue($getActiveSheet, 'D' . $rowCount, $case['last_name']);
			$getActiveSheet->getColumnDimension('D')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'E' . $rowCount, $case['nick_name']);
			$getActiveSheet->getColumnDimension('E')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'F' . $rowCount, $case['email_address']);
			$getActiveSheet->getColumnDimension('F')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'G' . $rowCount, $case['registrant_category_desc']);
			$getActiveSheet->getColumnDimension('G')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'H' . $rowCount, $case['organization']);
			$getActiveSheet->getColumnDimension('H')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'I' . $rowCount, $case['country_name']);
			$getActiveSheet->getColumnDimension('I')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'J' . $rowCount, $case['state_name']);
			$getActiveSheet->getColumnDimension('J')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'K' . $rowCount, $case['city']);
			$getActiveSheet->getColumnDimension('K')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'L' . $rowCount, $case['address_line_1']);
			$getActiveSheet->getColumnDimension('L')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'M' . $rowCount, $case['address_line_2']);
			$getActiveSheet->getColumnDimension('M')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'N' . $rowCount, $case['address_line_3']);
			$getActiveSheet->getColumnDimension('N')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'O' . $rowCount, $case['pincode']);
			$getActiveSheet->getColumnDimension('O')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'P' . $rowCount, $case['mobile_no']);
			$getActiveSheet->getColumnDimension('P')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'Q' . $rowCount, $case['telephone']);
			$getActiveSheet->getColumnDimension('Q')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'R' . $rowCount, $case['remarks']);
			$getActiveSheet->getColumnDimension('R')->setWidth(15);

			$this->setCellValue($getActiveSheet, 'S' . $rowCount, $case['hear_about_event_desc']);
			$getActiveSheet->getColumnDimension('S')->setWidth(15);

			$sNo++;
			$rowCount++;
		}

		$objPHPExcel->getActiveSheet()->setTitle("DAW Registrations"); //EXCEL SHEET NAME

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="DAW Registrations' . '.xls"'); //EXCEL FILE NAME
		header('Cache-Control: max-age=0');
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
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

	protected function setStyle($getActiveSheet, $cellIndex, $stylesArray)
	{
		$getActiveSheet->getStyle($cellIndex)->applyFromArray($stylesArray);
	}

	protected function mergeCells($getActiveSheet, $cellIndex)
	{
		$getActiveSheet->mergeCells($cellIndex);
	}

	protected function setCellValue($getActiveSheet, $cellIndex, $value)
	{
		$getActiveSheet->setCellValue($cellIndex, $value);
	}
}
