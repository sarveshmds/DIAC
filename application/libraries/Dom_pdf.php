<?php

defined('BASEPATH') or exit('No direct scripts are allowed');

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Class for pdf export
 */
class Dom_pdf extends Dompdf
{

	function __construct()
	{
		parent::__construct();
	}

	public function convert_into_pdf($data, $filename = 'DIAC', $orientation = 'landscape')
	{
		// Initialize Dompdf with options
		$options = new Options();

		$dompdf = new Dompdf($options);
		$dompdf->set_option('isHtml5ParserEnabled', true);

		$dompdf->set_paper('A4', $orientation);
		$options->set('isRemoteEnabled', true); // Enable remote content fetching
		$dompdf->loadHtml($data);
		$dompdf->render();

		$today_date = date('d-m-Y');
		$dompdf->stream($filename . '-' . $today_date, array("Attachment" => false));
	}

	public function save_to_folder($data, $filename = 'DIAC', $orientation = 'portrait', $filepath = '')
	{
		$dompdf = new Dompdf();
		$dompdf->set_option('isHtml5ParserEnabled', true);

		$dompdf->set_paper('A4', $orientation);

		$dompdf->loadHtml($data);
		$dompdf->render();

		$today_date = time() . rand(99, 9999);
		$new_file_name = $filename . '-' . $today_date;

		// $dompdf->stream($new_file_name, array("Attachment" => false));
		$full_file_path = $filepath . $new_file_name . '.pdf';
		header('Content-Type: application/pdf');


		$output = $dompdf->output();

		file_put_contents(FCPATH . $full_file_path, $output);
		return $full_file_path;
	}

	public function generate_and_download_pdf($data, $filename = 'DIAC', $orientation = 'portrait', $filepath = '')
	{
		// Initialize Dompdf with options
		$options = new Options();

		$dompdf = new Dompdf($options);
		$dompdf->set_option('isHtml5ParserEnabled', true);

		$dompdf->set_paper('A4', $orientation);
		$options->set('isRemoteEnabled', true); // Enable remote content fetching
		$dompdf->loadHtml($data);
		$dompdf->render();

		$today_date = date('d-m-Y');
		$dompdf->stream($filename . '-' . $today_date, array("Attachment" => false));
	}
}
