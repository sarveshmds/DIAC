<?php

defined('BASEPATH') or exit('No direct script access allowed');
ob_start();

// For php 5 and less than php 7
//  include APPPATH . 'third_party'.DIRECTORY_SEPARATOR.'dompdf'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

// For greater than php 7
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'dompdf_php7' . DIRECTORY_SEPARATOR . 'autoload.inc.php';

use Dompdf\Dompdf;

// Php spreadsheet
include APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'phpspreadsheet' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// include APPPATH . 'third_party'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';

class Export_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata('user_name')) {
			redirect(base_url());
		}

		# models
		$this->load->model(array('common_model', 'case_model', 'miscellaneous_model'));
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
			'ADMIN' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel'),
			'DIAC' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateRegisteredCaseExcel', 'generateAllCasesExcel', 'generateMiscellaneousRepliesPdf'),
			'CASE_MANAGER' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateResponseFormat', 'generateAllCasesExcel', 'inviting_claim_and_seeking_names_of_arbitrator', 'claiming_deficient_fee_from_parties', 'inviting_claim_when_arbitrator_already_appointed', 'seeking_consent_from_arbitrator'),
			'CASE_FILER' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateRegisteredCaseExcel', 'generateAllCasesExcel', 'generateMiscellaneousRepliesPdf'),
			'COORDINATOR' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel', 'generateMiscellaneousRepliesPdf'),
			'ACCOUNTS' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel'),
			'CAUSE_LIST_MANAGER' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel'),
			'POA_MANAGER' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel'),
			'DEPUTY_COUNSEL' => array('print_case', 'generateRegisteredCasePdf', 'generateOtherPleadingsPdf', 'generateOtherCorrespondancePdf', 'generateNotingsPdf', 'generatePanelOfArbitratorsPdf', 'generateCauseListPdf', 'generateAllCasesPdf', 'generateCaseDetailsPdf', 'generateAllCasesExcel'),
		);

		if ($role == false || !isset($role_action_auth[$role]) || !in_array($method, $role_action_auth[$role]) || !$check_user) {
			redirect('logout');
			// self::page_not_found();
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

	public function print_case()
	{
		$case_slug = $this->uri->segment(2);
		$parameters = array('slug' => $case_slug);
		// Get all data
		$data['case_data'] = $this->case_model->get($parameters, 'ALL_CASE_DATA');

		$data['title'] = 'Print Case Details';
		$data['page_title'] = 'Print Case Details';
		$data['export_pdf'] = false;

		$this->load->view('diac-admin/print-case.php', $data);
	}

	public function export_pdf()
	{

		$data['export_pdf'] = true;

		$pdf_data_html = $this->load->view('diac-admin/print-case.php', $data, TRUE);

		// ==============================================================
		// TCPDF
		// create new PDF document
		$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('DIAC');
		$pdf->SetTitle('DIAC Case Details');

		// set default header data
		$pdf->SetHeaderData('', '', 'DIAC', 'Delhi International Arbitration Center');

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins('7', '20', '7');

		$pdf->SetHeaderMargin('10');
		$pdf->SetFooterMargin('0');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 20);


		// set font
		$pdf->SetFont('helvetica', '', 10);
		$pdf->addPage();


		// output the HTML content
		$pdf->writeHTML($pdf_data_html, true, false, false, false, '');
		ob_end_clean();
		//Close and output PDF document
		$pdf->Output('Case Details.pdf', 'I');
	}

	public function generateRegisteredCasePdf()
	{
		$data['page_title'] = 'Registered Cases List';

		$data['allRegisteredCasesList'] = $this->getter_model->get('', 'GET_ALL_REGISTERED_CASES_LIST');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/registered-cases-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'landscape');
	}

	public function generateCaseDetailsPdf()
	{
		$data['page_title'] = 'Case Details';

		$case_slug = $this->uri->segment(3);
		$parameters = array('slug' => $case_slug);

		// Get all data
		$data['case_data'] = $this->case_model->get($parameters, 'ALL_CASE_DATA');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/case-details-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function generateOtherPleadingsPdf()
	{

		$data['page_title'] = 'Other Pleadings';

		$case_slug = $this->uri->segment(3);
		$data['caseDetails'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_DETAILS');
		$data['caseOtherPleadingsList'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_OTHER_PLEADINGS');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/case-other-pleadings-list-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function generateOtherCorrespondancePdf()
	{
		$data['page_title'] = 'Other Correspondance';

		$case_slug = $this->uri->segment(3);
		$data['caseDetails'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_DETAILS');
		$data['caseOtherCorrespondanceList'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_OTHER_CORRESPONDANCE');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/case-other-correspondance-list-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function generateNotingsPdf()
	{
		$data['page_title'] = 'Notings';

		$case_slug = $this->uri->segment(3);
		$data['caseDetails'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_DETAILS');
		$data['caseNotingsList'] = $this->getter_model->get(['case_slug' => $case_slug], 'GET_CASE_NOTINGS');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/case-notings-list-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function generateMiscellaneousRepliesPdf()
	{
		$data['page_title'] = 'Miscellaneous Notings';

		$miscellaneous_id = $this->uri->segment(3);
		$data['miscellaneous'] = $this->miscellaneous_model->get(['id' => $miscellaneous_id], 'GET_MISCELLANEOUS_DATA_USING_ID');
		$data['miscellaneous_replies_list'] = $this->miscellaneous_model->get(['miscellaneous_id' => $miscellaneous_id], 'GET_MISCELLANEOUS_REPLIES_LIST_USING_ID');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/miscellaneous-replies-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function generatePanelOfArbitratorsPdf()
	{
		$data['page_title'] = 'Panel Of Arbitrators';

		$data['panelOfArbitratorsList'] = $this->getter_model->get('', 'GET_ALL_PANEL_OF_ARBITRATORS');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/panel-of-arbitrators-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'landscape');
	}

	public function generateCauseListPdf()
	{

		if ($this->input->get('type')) {
			$data['page_title'] = 'Today\'s Cause List ';
			$data['type'] = base64_decode($this->input->get('type'));
			if ($data['type'] == 'today') {
				$data['causeList'] = $this->getter_model->get(['todays_date' => date('d-m-Y')], 'GET_CAUSE_LIST');
			} else {
				return redirect('page-not-found');
			}
		} else {
			$data['page_title'] = 'All Cause List';
			$data['causeList'] = $this->getter_model->get('', 'GET_CAUSE_LIST');
		}

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/cause-list-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'landscape');
	}

	public function generateAllCasesPdf()
	{
		$data['page_title'] = 'All Cases List';

		$data['allRegisteredCasesList'] = $this->getter_model->get('', 'GET_ALL_REGISTERED_CASES_LIST');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/all-cases-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'landscape');
	}

	// ================================================================
	/* Response formats export */
	public function claiming_deficient_fee_from_parties()
	{
		$data['page_title'] = 'Claiming deficient fee from the parties - Response Format';
		$caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

		$data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
		$data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/response_format/claiming_deficient_fee_from_parties_pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function inviting_claim_and_seeking_names_of_arbitrator()
	{
		$data['page_title'] = 'Inviting claim and seeking names of Arbitrator - Response Format';
		$caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

		$data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
		$data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/response_format/inviting_claim_seeking_names_of_arbitrator_pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function inviting_claim_when_arbitrator_already_appointed()
	{
		$data['page_title'] = 'Inviting claim when Arbitrator already appointed by Court - Response Format';
		$caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

		$data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
		$data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/response_format/inviting_claim_when_arbitrator_already_appointed_pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	public function seeking_consent_from_arbitrator()
	{
		$data['page_title'] = 'Seeking consent from Ld. Arbitrator - Response Format';
		$caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));

		$data['case_data'] = $this->case_model->get(['slug' => $caseNo], 'GET_CASE_BASIC_DATA');
		$data['parties'] = $this->case_model->get(['case_no' => $caseNo], 'GET_ALL_CLAIMANT_RESPONDENT');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/response_format/seeking_consent_from_arbitrator_pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}

	// ================================================================

	public function generateResponseFormat()
	{
		$data['page_title'] = 'Response Format';
		$caseNo = $this->security->xss_clean(strip_tags($this->input->get('case_no')));
		$responseId = $this->security->xss_clean(strip_tags($this->input->get('response_id')));
		$data['response_format'] = $this->getter_model->get(['case_no' => $caseNo, 'response_id' => urldecode($responseId)], 'GET_SINGLE_RESPONSE_FORMAT');

		$print_data = $this->load->view('system/templates/header', $data, true);
		$print_data .= $this->load->view('system/response-format-pdf', $data, true);
		$print_data .= $this->load->view('system/templates/footer', $data, true);
		$this->convert_into_pdf($print_data, $data['page_title'], 'portrait');
	}



	// Convert into pdf
	public function convert_into_pdf($data, $filename = 'DIAC', $orientation = 'landscape')
	{
		$dompdf = new Dompdf();
		$dompdf->set_option('isHtml5ParserEnabled', true);

		$dompdf->set_paper('A4', $orientation);

		$dompdf->loadHtml($data);
		$dompdf->render();

		$today_date = date('d-m-Y');
		$dompdf->stream($filename . '-' . $today_date, array("Attachment" => false));
	}


	public function generateRegisteredCaseExcel()
	{
		$sNo = 1;
		$rowCount = 4;
		$allRegisteredCases = $this->getter_model->get('', 'GET_ALL_REGISTERED_CASES_LIST_FOR_EXCEL');

		// echo '<pre>';
		// print_r($allRegisteredCases);
		// die;

		// Create new instance of spreadsheet
		$objPHPExcel = new Spreadsheet();

		// Add some data to the second sheet, resembling some different data types
		$objPHPExcel->setActiveSheetIndex(0);

		// Set the getActiveSheet instance
		$getActiveSheet = $objPHPExcel->getActiveSheet();

		$this->mergeCells($getActiveSheet, 'A1:N2');
		$this->setCellValue($getActiveSheet, 'A1', 'Delhi International Arbitration Center, Delhi');
		$this->setStyle($getActiveSheet, 'A1', array(
			'font'  => array('bold'  => true, 'size' => '14'),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'A3', 'Sl No');
		$this->setStyle($getActiveSheet, 'A3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'B3', 'DIAC Registration No.');
		$this->setStyle($getActiveSheet, 'B3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'C3', 'Case Title');
		$this->setStyle($getActiveSheet, 'C3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'D3', 'Date of reference order');
		$this->setStyle($getActiveSheet, 'D3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'E3', 'Mode of reference');
		$this->setStyle($getActiveSheet, 'E3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'F3', 'Name of Judge');
		$this->setStyle($getActiveSheet, 'F3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'G3', 'Reference No.');
		$this->setStyle($getActiveSheet, 'G3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'H3', 'Name Of Court/Department');
		$this->setStyle($getActiveSheet, 'H3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'I3', 'Name Of Arbitrators');
		$this->setStyle($getActiveSheet, 'I3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'J3', 'Ref. Received On');
		$this->setStyle($getActiveSheet, 'J3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'K3', 'Date of registration');
		$this->setStyle($getActiveSheet, 'K3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'L3', 'Type of arbitration');
		$this->setStyle($getActiveSheet, 'L3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'M3', 'Arbitrator Status');
		$this->setStyle($getActiveSheet, 'M3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'N3', 'Case Status');
		$this->setStyle($getActiveSheet, 'N3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		foreach ($allRegisteredCases as $case) {

			$this->setCellValue($getActiveSheet, 'A' . $rowCount, $sNo);
			$this->setCellValue($getActiveSheet, 'B' . $rowCount, $case['case_no']);
			$getActiveSheet->getColumnDimension('B')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'C' . $rowCount, $case['case_title']);
			$getActiveSheet->getColumnDimension('C')->setWidth(25);
			$this->setCellValue($getActiveSheet, 'D' . $rowCount, formatReadableDate($case['reffered_on']));
			$getActiveSheet->getColumnDimension('D')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'E' . $rowCount, $case['reffered_by_desc']);
			$getActiveSheet->getColumnDimension('E')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'F' . $rowCount, $case['reffered_by_judge']);
			$getActiveSheet->getColumnDimension('F')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'G' . $rowCount, $case['arbitration_petition']);
			$getActiveSheet->getColumnDimension('G')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'H' . $rowCount, $case['name_of_court']);
			$getActiveSheet->getColumnDimension('H')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'I' . $rowCount, $case['name_of_arbitrator']);
			$getActiveSheet->getColumnDimension('I')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'J' . $rowCount, formatReadableDate($case['recieved_on']));
			$getActiveSheet->getColumnDimension('J')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'K' . $rowCount, formatReadableDate($case['registered_on']));
			$getActiveSheet->getColumnDimension('K')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'L' . $rowCount, $case['type_of_arbitration_desc']);
			$getActiveSheet->getColumnDimension('L')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'M' . $rowCount, $case['arbitrator_status_desc']);
			$getActiveSheet->getColumnDimension('M')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'N' . $rowCount, $case['case_status_dec']);
			$getActiveSheet->getColumnDimension('N')->setWidth(15);

			$sNo++;
			$rowCount++;
		}

		$objPHPExcel->getActiveSheet()->setTitle("All registered cases"); //EXCEL SHEET NAME

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="All registered cases' . '.xls"'); //EXCEL FILE NAME
		header('Cache-Control: max-age=0');
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}

	public function generateAllCasesExcel()
	{
		$sNo = 1;
		$rowCount = 4;
		$allCasesList = $this->getter_model->get('', 'GET_ALL_CASES_LIST_FOR_EXCEL');

		// Create new instance of spreadsheet
		$objPHPExcel = new Spreadsheet();

		// Add some data to the second sheet, resembling some different data types
		$objPHPExcel->setActiveSheetIndex(0);

		// Set the getActiveSheet instance
		$getActiveSheet = $objPHPExcel->getActiveSheet();

		$this->mergeCells($getActiveSheet, 'A1:N2');
		$this->setCellValue($getActiveSheet, 'A1', 'Delhi International Arbitration Center, Delhi');
		$this->setStyle($getActiveSheet, 'A1', array(
			'font'  => array('bold'  => true, 'size' => '14'),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		$this->setCellValue($getActiveSheet, 'A3', 'Sl No');
		$this->setStyle($getActiveSheet, 'A3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'B3', 'DIAC Registration No');
		$this->setStyle($getActiveSheet, 'B3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'C3', 'Case Title');
		$this->setStyle($getActiveSheet, 'C3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'D3', 'Date of reference order');
		$this->setStyle($getActiveSheet, 'D3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'E3', 'Mode of reference');
		$this->setStyle($getActiveSheet, 'E3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'F3', 'Name of Judge');
		$this->setStyle($getActiveSheet, 'F3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'G3', 'Reference No.');
		$this->setStyle($getActiveSheet, 'G3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'H3', 'Name Of Court/Department');
		$this->setStyle($getActiveSheet, 'H3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'I3', 'Name Of Arbitrators');
		$this->setStyle($getActiveSheet, 'I3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'J3', 'Ref. Received On');
		$this->setStyle($getActiveSheet, 'J3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'K3', 'Date of registration');
		$this->setStyle($getActiveSheet, 'K3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'L3', 'Type of arbitration');
		$this->setStyle($getActiveSheet, 'L3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'M3', 'Arbitrator Status');
		$this->setStyle($getActiveSheet, 'M3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));
		$this->setCellValue($getActiveSheet, 'N3', 'Case Status');
		$this->setStyle($getActiveSheet, 'N3', array(
			'font'  => array('bold'  => true),
			'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
		));

		foreach ($allCasesList as $case) {

			$this->setCellValue($getActiveSheet, 'A' . $rowCount, $sNo);
			$this->setCellValue($getActiveSheet, 'B' . $rowCount, $case['case_no']);
			$getActiveSheet->getColumnDimension('B')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'C' . $rowCount, $case['case_title']);
			$getActiveSheet->getColumnDimension('C')->setWidth(25);
			$this->setCellValue($getActiveSheet, 'D' . $rowCount, formatReadableDate($case['reffered_on']));
			$getActiveSheet->getColumnDimension('D')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'E' . $rowCount, $case['reffered_by_desc']);
			$getActiveSheet->getColumnDimension('E')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'F' . $rowCount, $case['reffered_by_judge']);
			$getActiveSheet->getColumnDimension('F')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'G' . $rowCount, $case['arbitration_petition']);
			$getActiveSheet->getColumnDimension('G')->setWidth(20);
			$this->setCellValue($getActiveSheet, 'H' . $rowCount, $case['name_of_court']);
			$getActiveSheet->getColumnDimension('H')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'I' . $rowCount, $case['name_of_arbitrator']);
			$getActiveSheet->getColumnDimension('I')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'J' . $rowCount, formatReadableDate($case['recieved_on']));
			$getActiveSheet->getColumnDimension('J')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'K' . $rowCount, formatReadableDate($case['registered_on']));
			$getActiveSheet->getColumnDimension('K')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'L' . $rowCount, $case['type_of_arbitration_desc']);
			$getActiveSheet->getColumnDimension('L')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'M' . $rowCount, $case['arbitrator_status_desc']);
			$getActiveSheet->getColumnDimension('M')->setWidth(15);
			$this->setCellValue($getActiveSheet, 'N' . $rowCount, $case['case_status_dec']);
			$getActiveSheet->getColumnDimension('N')->setWidth(15);

			$sNo++;
			$rowCount++;
		}

		$objPHPExcel->getActiveSheet()->setTitle("All Cases"); //EXCEL SHEET NAME

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="All Cases' . '.xls"'); //EXCEL FILE NAME
		header('Cache-Control: max-age=0');
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
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
