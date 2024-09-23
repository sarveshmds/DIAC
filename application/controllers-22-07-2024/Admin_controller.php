<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Admin_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		# libraries
		$this->load->library('form_validation');
		$this->load->library('user_agent');
		# helper
		$this->load->helper('custom_page');
		# models
		$this->load->model('admin_model');
		$this->load->model('getter_model');

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
			'ADMIN' => array('gen_code_master', 'country_master', 'state_master', 'district_master', 'block_master', 'gp_master', 'village_master', 'email_setup', 'sms_setup', 'country_preview', 'state_preview', 'district_preview', 'block_preview', 'gp_preview', 'village_preview', 'imei_setup', 'user_role_assign'),
			'SUPERADMIN' => array('gen_code_master', 'country_master', 'state_master', 'district_master', 'block_master', 'gp_master', 'village_master', 'email_setup', 'sms_setup', 'country_preview', 'state_preview', 'district_preview', 'block_preview', 'gp_preview', 'village_preview', 'imei_setup'),
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
				self::page_not_found();
			}
		}
	}
	public function page_not_found()
	{
		$this->load->view('templates/404.php');
		$this->load->view('templates/admin_footer');
	}

	public function index()
	{
		redirect('admin-dashboard');
	}

	public function gen_code_master()
	{
		$sidebar['menu_item'] = 'Gencode Setup';
		$sidebar['menu_group'] = 'Master Setup';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$data['get_gencode_group'] = $this->admin_model->admin(null, 'GET_GENCODE_GROUP');
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/gen_code_setup', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function country_master()
	{
		$sidebar['menu_item'] = 'Country Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/country_setup');
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function state_master()
	{
		$sidebar['menu_item'] = 'State Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$data['county_data'] = $this->admin_model->admin(null, 'get_county_name');
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/state_master', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function district_master()
	{
		$sidebar['menu_item'] = 'District Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/district_master');
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	/********Modify By: Subhashree Date : 29-08-2019************/
	public function block_master()
	{
		$sidebar['menu_item'] = 'Block Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['all_dist_list'] 	  = $this->getter_model->get(array('state_code' => '21'), 'get_dist_name');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/block_master', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	/********Modify By: Subhashree Date : 29-08-2019************/
	public function gp_master()
	{
		$sidebar['menu_item'] = 'GP Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		$data['all_dist_list'] 	  = $this->getter_model->get(array('state_code' => '21'), 'get_dist_name');
		if ($page_status != 0) {
			$this->load->view('admin/gp_master', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	/********Modify By: Subhashree Date : 29-08-2019************/

	public function village_master()
	{
		$sidebar['menu_item'] = 'Village Master';
		$sidebar['menu_group'] = 'Location';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		$data['all_dist_list'] 	  = $this->getter_model->get(array('state_code' => '21'), 'get_dist_name');
		if ($page_status != 0) {
			$this->load->view('admin/village_master', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	public function email_setup()
	{
		$sidebar['menu_item'] = 'Email Setup';
		$sidebar['menu_group'] = 'Configuration';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/email_setup');
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
	public function sms_setup()
	{
		$sidebar['menu_item'] = 'SMS Setup';
		$sidebar['menu_group'] = 'Configuration';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/sms_setup');
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}

	/**
	 * Country Upload
	 */
	public function country_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/country_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('country_code', 'country_name');
					$makeArray = array('country_code' => 'country_code', 'country_name' => 'country_name');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$country_code = $SheetDataKey['country_code'];
							$country_name = $SheetDataKey['country_name'];
							$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
							$country_name = filter_var(trim($allDataInSheet[$i][$country_name]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'country_code' => $country_code, 'country_name' => $country_name);
						}
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'Country Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/country_setup', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['error'] = $fetchData;
						$sidebar['menu_item'] = 'Country Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/country_setup', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('country-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only msexcel/xls.');
				redirect('country-master');
			}
		}
	}
	/**
	 * State Upload
	 */
	public function state_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/state_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('state_code', 'state_name', 'country_code');
					$makeArray = array('state_code' => 'state_code', 'state_name' => 'state_name', 'country_code' => 'country_code');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$state_code = $SheetDataKey['state_code'];
							$state_name = $SheetDataKey['state_name'];
							$country_code = $SheetDataKey['country_code'];
							$state_code = filter_var(trim($allDataInSheet[$i][$state_code]), FILTER_SANITIZE_STRING);
							$state_name = filter_var(trim($allDataInSheet[$i][$state_name]), FILTER_SANITIZE_STRING);
							$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'state_code' => $state_code, 'state_name' => $state_name, 'country_code' => $country_code);
						}
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'State Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/state_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['error'] = $fetchData;
						$sidebar['menu_item'] = 'State Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/state_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('state-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only msexcel/xls.');
				redirect('state-master');
			}
		}
	}
	/**
	 * District Upload
	 */
	public function district_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/district_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('district_code', 'district_name', 'state_code', 'country_code', 'dist_census_code');
					$makeArray = array('district_code' => 'district_code', 'district_name' => 'district_name', 'state_code' => 'state_code', 'country_code' => 'country_code', 'dist_census_code' => 'dist_census_code');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$district_code = $SheetDataKey['district_code'];
							$district_name = $SheetDataKey['district_name'];
							$state_code = $SheetDataKey['state_code'];
							$country_code = $SheetDataKey['country_code'];
							$dist_census_code = $SheetDataKey['dist_census_code'];
							$district_code = filter_var(trim($allDataInSheet[$i][$district_code]), FILTER_SANITIZE_STRING);
							$district_name = filter_var(trim($allDataInSheet[$i][$district_name]), FILTER_SANITIZE_STRING);
							$state_code = filter_var(trim($allDataInSheet[$i][$state_code]), FILTER_SANITIZE_STRING);
							$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
							$dist_census_code = filter_var(trim($allDataInSheet[$i][$dist_census_code]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'district_code' => $district_code, 'district_name' => $district_name, 'state_code' => $state_code, 'country_code' => $country_code, 'dist_census_code' => $dist_census_code);
						}
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'District Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/district_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['error'] = $fetchData;
						$sidebar['menu_item'] = 'District Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/district_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('district-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only msexcel/xls.');
				redirect('district-master');
			}
		}
	}
	/**
	 * Block Upload
	 */
	public function block_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/block_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('block_code', 'block_name', 'district_code', 'state_code', 'country_code');
					$makeArray = array('block_code' => 'block_code', 'block_name' => 'block_name', 'district_code' => 'district_code', 'state_code' => 'state_code', 'country_code' => 'country_code');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$block_code = $SheetDataKey['block_code'];
							$block_name = $SheetDataKey['block_name'];
							$district_code = $SheetDataKey['district_code'];
							$state_code = $SheetDataKey['state_code'];
							$country_code = $SheetDataKey['country_code'];

							$block_code = filter_var(trim($allDataInSheet[$i][$block_code]), FILTER_SANITIZE_STRING);
							$block_name = filter_var(trim($allDataInSheet[$i][$block_name]), FILTER_SANITIZE_STRING);
							$district_code = filter_var(trim($allDataInSheet[$i][$district_code]), FILTER_SANITIZE_STRING);
							$state_code = filter_var(trim($allDataInSheet[$i][$state_code]), FILTER_SANITIZE_STRING);
							$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'block_code' => $block_code, 'block_name' => $block_name, 'district_code' => $district_code, 'state_code' => $state_code, 'country_code' => $country_code);
						}
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'Block Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/block_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['error'] = $fetchData;
						$sidebar['menu_item'] = 'Block Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/block_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('block-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only ms excel/xls.');
				redirect('block-master');
			}
		}
	}
	/**
	 * Circle Upload
	 */
	public function gp_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/gp_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('gp_code', 'gp_name', 'block_code', 'district_census_code', 'district_code', 'state_code', 'country_code');
					$makeArray = array('gp_code' => 'gp_code', 'gp_name' => 'gp_name', 'block_code' => 'block_code', 'district_census_code' => 'district_census_code', 'district_code' => 'district_code', 'state_code' => 'state_code', 'country_code' => 'country_code');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$gp_code = $SheetDataKey['gp_code'];
							$gp_name = $SheetDataKey['gp_name'];
							$block_code = $SheetDataKey['block_code'];
							$district_census_code = $SheetDataKey['district_census_code'];
							$district_code = $SheetDataKey['district_code'];
							$state_code = $SheetDataKey['state_code'];
							$country_code = $SheetDataKey['country_code'];

							$gp_code = filter_var(trim($allDataInSheet[$i][$gp_code]), FILTER_SANITIZE_STRING);
							$gp_name = filter_var(trim($allDataInSheet[$i][$gp_name]), FILTER_SANITIZE_STRING);
							$block_code = filter_var(trim($allDataInSheet[$i][$block_code]), FILTER_SANITIZE_STRING);
							$district_census_code = filter_var(trim($allDataInSheet[$i][$district_census_code]), FILTER_SANITIZE_STRING);
							$district_code = filter_var(trim($allDataInSheet[$i][$district_code]), FILTER_SANITIZE_STRING);
							$state_code = filter_var(trim($allDataInSheet[$i][$state_code]), FILTER_SANITIZE_STRING);
							$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'gp_code' => $gp_code, 'gp_name' => $gp_name, 'block_code' => $block_code, 'district_census_code' => $district_census_code, 'district_code' => $district_code, 'state_code' => $state_code, 'country_code' => $country_code);
						}
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'GP Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/gp_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['error'] = $fetchData;
						$sidebar['menu_item'] = 'GP Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/gp_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('gp-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only ms excel/xls.');
				redirect('gp-master');
			}
		}
	}
	/**
	 * Village Preview
	 */
	public function village_preview()
	{
		$data = '';
		if ($this->input->post('importfile')) {
			$VALID = TRUE;
			$allowed_mime_type_arr = array('application/msexcel', 'application/xls', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-xls');
			$mime = get_mime_by_extension($_FILES['userfile']['name']);
			$dot_count 	= substr_count($_FILES['userfile']['name'], '.');
			$zero_count = substr_count($_FILES['userfile']['name'], "%0");
			if (in_array($mime, $allowed_mime_type_arr)) {
				if ($zero_count == 0 && $dot_count == 1) {
					$path = FCPATH . 'public/upload/excel_files/';
					$config['upload_path'] = $path;
					$config['allowed_types'] = 'xlsx|xls';
					$config['remove_spaces'] = TRUE;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('userfile')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$data = array('upload_data' => $this->upload->data());
					}
					if (!empty($data['upload_data']['file_name'])) {
						$import_xls_file = $data['upload_data']['file_name'];
					} else {
						$import_xls_file = 0;
					}
					$inputFileName = $path . $import_xls_file;
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

					$arrayCount = count($allDataInSheet);
					for ($c = 'A'; $c <= 'A'; $c++) {
						if (!isset($allDataInSheet[1]["$c"])) {
							$fetchData[] = array('error_msg' => 'Missing column.');
							$VALID = FALSE;
						}
					}
					if ($VALID) {
						//2. check for record exist
						$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
						if ($arrayCount <= 1) {
							$fetchData[] = array('error_msg' => 'Excel file contains no records.');
							$VALID = FALSE;
						}
					}
					if ($VALID) { //4. check for cell empty for mandatory columns
						$arrayCount = count($allDataInSheet);
						$mandator_columns = array('A');
						for ($r = 2; $r <= $arrayCount; $r++) {
							foreach ($mandator_columns as $c) {
								if (trim($allDataInSheet[$r]["$c"]) == '') {
									$fetchData[] = array('error_msg' => 'Blank cell found at row.' . $r);
									$VALID = FALSE;
								}
							}
						}
					}
					if ($VALID) { //3. check for column sequence against the template
						$inputFileName1 = FCPATH . '/public/excel_templates/village_master.xls';
						$objPHPExcel = $objReader->load($inputFileName1);
						$allDataInSheet2 = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
						for ($c = 'A'; $c <= 'A'; $c++) {
							if (trim($allDataInSheet[1]["$c"]) != trim($allDataInSheet2[1]["$c"])) {
								$fetchData[] = array('error_msg' => 'Column headings are not same as in template.');
								$VALID = FALSE;
							}
						}
					}
					$flag = 0;
					$createArray = array('pk_village_code', 'village_name', 'gp_code');
					$makeArray = array('pk_village_code' => 'pk_village_code', 'village_name' => 'village_name', 'gp_code' => 'gp_code');
					$SheetDataKey = array();

					foreach ($allDataInSheet as $dataInSheet) {
						foreach ($dataInSheet as $key => $value) {
							$value = preg_replace('/\s+/', '', $value);
							if (in_array($value, $createArray)) {
								$value = preg_replace('/\s+/', '', $value);
								$SheetDataKey[$value] = $key;
							} else {
							}
						}
					}
					$data = array_diff_key($makeArray, $SheetDataKey);

					if (empty($data)) {
						$flag = 1;
					}
					if ($flag == 1 and $VALID == TRUE) {
						for ($i = 2; $i <= $arrayCount; $i++) {
							$addresses = array();
							$pk_village_code = $SheetDataKey['pk_village_code'];
							$village_name = $SheetDataKey['village_name'];
							$gp_code = $SheetDataKey['gp_code'];

							$pk_village_code = filter_var(trim($allDataInSheet[$i][$pk_village_code]), FILTER_SANITIZE_STRING);
							$village_name = filter_var(trim($allDataInSheet[$i][$village_name]), FILTER_SANITIZE_STRING);
							$gp_code = filter_var(trim($allDataInSheet[$i][$gp_code]), FILTER_SANITIZE_STRING);
							$fetchData[] = array('importFileName' => $inputFileName, 'pk_village_code' => $pk_village_code, 'village_name' => $village_name, 'gp_code' => $gp_code);
						}

						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'Village Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/village_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					} else {
						$data['dataInfo'] = $fetchData;
						$sidebar['menu_item'] = 'Village Master';
						$sidebar['menu_group'] = 'Location';
						$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
						$this->load->view('templates/side_menu', $sidebar);
						$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
						if ($page_status != 0) {
							$this->load->view('admin/village_master', $data);
						} else {
							$this->load->view('templates/page_maintenance');
						}
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('error', 'Invalid Excel format.It should not contain multiple dot(.) or 0)');
					redirect('villager-master');
				}
			} else {
				$this->session->set_flashdata('error', 'Please select only ms excel/xls.');
				redirect('village-master');
			}
		}
	}
	/**
	 * Name: Subhashree Jena
	 * Date :17-09-2019
	 * Purpose : IMEI Setup
	 * @return
	 */
	public function imei_setup()
	{
		$sidebar['menu_item'] = 'IMEI Setup';
		$sidebar['menu_group'] = 'IMEI Setup';
		$sidebar['sidebar'] = $this->getter_model->get($sidebar, 'get_sidebar');
		$data['role_code'] = $this->getter_model->get(null, 'get_role_code');
		$this->load->view('templates/side_menu', $sidebar);
		$page_status = page_status($sidebar['sidebar'], $sidebar['menu_item']);
		if ($page_status != 0) {
			$this->load->view('admin/imei_setup', $data);
		} else {
			$this->load->view('templates/page_maintenance');
		}
		$this->load->view('templates/footer');
	}
}
