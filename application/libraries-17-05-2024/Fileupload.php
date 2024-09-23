<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fileupload
{

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	// Function to upload single file
	public function uploadSingleFile($file, $configuration)
	{

		$file_name = $file['name'];
		$allowed_mime_type_arr = $configuration['allowed_mime_types'];
		$mime = get_mime_by_extension($file_name);
		$dot_count 	= substr_count($file_name, '.');
		$zero_count = substr_count($file_name, "%0");

		if (in_array($mime, $allowed_mime_type_arr)) {
			if ($zero_count == 0 && $dot_count == 1) {
				$file_move_path  = $configuration['file_move_path'];
				if (!is_dir($file_move_path)) {
					mkdir($file_move_path, 0777, true);
				}
				$config['upload_path'] 		= $file_move_path;
				$config['file_name'] 		= $configuration['file_name'];
				$config['allowed_types'] 	= $configuration['allowed_file_types'];
				$config['max_size']         = (isset($configuration['max_size']) && !empty($configuration['max_size'])) ? $configuration['max_size'] : FILE_MAX_SIZE;
				$config['overwrite'] 		= TRUE;

				$this->CI->load->library('upload', $config);
				$this->CI->upload->initialize($config);

				if ($this->CI->upload->do_upload($configuration['raw_file_name'])) {
					$upload_data = array('upload_data' => $this->CI->upload->data());
					if ($upload_data) {
						$dbstatus 	= true;
						$dbmessage 	= 'File Uploaded successfully';
						$file =  $upload_data['upload_data']['file_name'];
					} else {
						$dbstatus = false;
						$dbmessage = 'Error while uploading file';
					}
				} else {
					$dbstatus = false;
					$dbmessage = $this->CI->upload->display_errors();
					$file =  '';
				}
			} else {
				$dbstatus = false;
				$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
				$file =  '';
			}
		} else {
			$dbstatus = false;
			$dbmessage = 'Please select only allowed format.';
			$file =  '';
		}

		// $insert_log_detail = array(
		// 	'user_code'		=> $this->CI->user_code,
		// 	'ip_address'	=> $this->CI->input->ip_address(),
		// 	'role_code'		=> $this->CI->role,
		// 	'session_id'	=> $this->CI->session->userdata('sess_id'),
		// 	'doc_type'      =>$configuration['allowed_file_types'],
		// 	'created_by'	=> $this->CI->session->userdata('user_name'),

		// 	'last_attempt'  =>date('Y-m-d H:i:s', time()),
		// 	'doc_status'	=> $dbstatus,
		// );
		// $this->CI->db->insert('upload_doc_log', $insert_log_detail);
		return array('status' => $dbstatus, 'msg' => $dbmessage, 'file' => $file);
	}

	// Function to upload multiple files
	public function uploadMultipleFiles($files, $configuration)
	{
		$filesArray = [];
		$flag = false;
		for ($i = 0; $i < count($files['name']); $i++) {
			if (!empty($files['name'][$i])) {

				$_FILES['file']['name'] = $files['name'][$i];
				$_FILES['file']['type'] = $files['type'][$i];
				$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
				$_FILES['file']['error'] = $files['error'][$i];
				$_FILES['file']['size'] = $files['size'][$i];

				$file_name = $_FILES['file']['name'];
				$file_move_path  = $configuration['file_move_path'];
				$allowed_mime_type_arr = $configuration['allowed_mime_types'];
				$mime = get_mime_by_extension($file_name);
				$dot_count 	= substr_count($file_name, '.');
				$zero_count = substr_count($file_name, "%0");


				if (in_array($mime, $allowed_mime_type_arr)) {
					if ($zero_count == 0 && $dot_count == 1) {

						if (!is_dir($file_move_path)) {
							mkdir($file_move_path, 0777, true);
						}

						$config['upload_path'] 		= $file_move_path;
						$config['file_name'] 		= $configuration['file_name'];
						$config['allowed_types'] 	= $configuration['allowed_file_types'];
						$config['max_size']         = (isset($configuration['max_size']) && !empty($configuration['max_size'])) ? $configuration['max_size'] : FILE_MAX_SIZE;
						$config['overwrite'] 		= TRUE;

						$this->CI->load->library('upload', $config);
						$this->CI->upload->initialize($config);

						if ($this->CI->upload->do_upload('file')) {
							$upload_data = array('upload_data' => $this->CI->upload->data());
							if ($upload_data) {
								array_push($filesArray, $upload_data['upload_data']['file_name']);
								$flag = true;

								// $insert_log_detail = array(
								// 	'user_code'		=> $this->CI->user_code,
								// 	'ip_address'	=> $this->CI->input->ip_address(),
								// 	'role_code'		=> $this->CI->role,
								// 	'session_id'	=> $this->CI->session->userdata('sess_id'),
								// 	'doc_type'      =>$configuration['allowed_file_types'],
								// 	'created_by'	=> $this->CI->session->userdata('user_name'),

								// 	'last_attempt'  =>date('Y-m-d H:i:s', time()),
								// 	'doc_status'	=> $dbstatus,
								// );
								// $result = $this->CI->db->insert('upload_doc_log', $insert_log_detail);

							} else {
								$flag = false;
								return array(
									'status' => false,
									'msg' => 'Error while uploading file',
									'files' => ''
								);
							}
						} else {
							$flag = false;
							return array(
								'status' => false,
								'msg' => $this->CI->upload->display_errors(),
								'files' => ''
							);
						}
					} else {
						$flag = false;
						return array(
							'status' => false,
							'msg' => 'Invalid Image format.It should not contain multiple dot(.) or 0)',
							'files' => ''
						);
					}
				} else {
					$flag = false;
					return array(
						'status' => false,
						'msg' => 'Please select only allowed format.',
						'files' => ''
					);
				}
			}
		}

		if ($flag == true) {
			return array(
				'status' => true,
				'msg' => 'Files uploaded successfully',
				'files' => $filesArray
			);
		} else {
			return array(
				'status' => false,
				'msg' => 'Server failed while upload files',
				'files' => ''
			);
		}
	}
}
