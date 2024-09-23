<?php
class User_model extends CI_model
{

	function __construct()
	{
		parent::__construct();

		if (ENVIRONMENT == 'production') {
			$this->db->save_queries = FALSE;
		}
		date_default_timezone_set('Asia/Kolkata');
	}

	// make md5 hash
	private function getHash($string)
	{
		return md5($string);
	}

	/**
	 *	Generate random password 
	 */
	public function rand_string($length)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars), 0, $length);
	}

	public function forgot_password()
	{
		$email = $this->input->post('email');
		$this->db->select('tbl_users.id');
		$this->db->from('tbl_users');
		$this->db->join('tbl_students', 'tbl_users.id = tbl_students.fk_user_id', 'left');
		$this->db->where('tbl_students.email', $email);	// for student
		$query = $this->db->get();

		if ($query->num_rows == 1) {
			$result = $query->result_array()[0];
			$randomPassword = self::rand_string(8);;
			$data = array(
				'password' => md5($randomPassword),
			);

			$this->db->where('id', $result['id']);
			if ($this->db->update('tbl_users', $data))
				return array('status' => 1, 'msg' => $randomPassword);
			else
				return array('status' => 0, 'msg' => 'Your request can not be processed. Try again later');
		} else {
			return array('status' => 0, 'msg' => "Please enter correct email id.");
		}
	}
	/*
		*	authenticate a user with username & password
		*/
	public function login()
	{

		//Storing values through POST method
		$data['non_xss'] = array(
			'key'			=> $this->session->userdata('key'),
			'user_name' 	=> $this->input->post('txtUsername'),
			'user_password' => $this->input->post('txtPassword'),
			'txt_captcha' => $this->input->post('txt_captcha')
		);
		// Apply the xss_clean() of "security" library, which filtered data from passing through <script> tag.
		$data['xss_data'] = $this->security->xss_clean($data['non_xss']);

		$key 		= $data['xss_data']['key'];
		// $this->session->unset_userdata('key');
		$UserName 	= $data['xss_data']['user_name'];
		$password 	= $data['xss_data']['user_password'];
		$txt_captcha 	= $data['xss_data']['txt_captcha'];

		$this->db->from('user_master um');
		$this->db->select('um.user_code, um.user_name, um.user_display_name, um.job_title, um.record_status, resom.resource_link, resom.is_maintenance, um.is_new_record, udm.user_logo, rm.role_name, rm.role_code, um.fk_state_code');
		$this->db->join('user_details_master udm', 'um.user_code = udm.fk_user_code', 'inner');
		$this->db->join('role_master rm', 'um.primary_role = rm.role_code', 'inner');
		$this->db->join('resource_master resom', 'rm.index_page_url = resom.resource_code', 'inner');
		$this->db->where('rm.record_status', 1);
		$this->db->where('um.user_name', $this->db->escape_str($UserName));
		$this->db->where("SHA2(CONCAT(um.password,'#','$key'),512)", $password);
		$userRecord = $this->db->get();
		$userDataArr = $userRecord->result_array();

		if ($userRecord->num_rows() == 1) {
			if ($this->session->userdata('captchaword') == $txt_captcha) {
				if ($userDataArr[0]['record_status'] != 0) {

					$this->session->set_userdata('logged_in', 'yes');
					$this->session->set_userdata('user_code', $userDataArr[0]['user_code']);
					$this->session->set_userdata('user_name', $userDataArr[0]['user_name']);
					$this->session->set_userdata('user_display_name', $userDataArr[0]['user_display_name']);
					$this->session->set_userdata('user_logo', $userDataArr[0]['user_logo']);
					$this->session->set_userdata('role', $userDataArr[0]['role_code']);
					$this->session->set_userdata('role_name', $userDataArr[0]['role_name']);
					$this->session->set_userdata('job_title', $userDataArr[0]['job_title']);
					$this->session->set_userdata('sess_id', session_id());

					// check if user is already login or no
					$login_detail = $this->db->get_where('login_detail', ['login_id' => $this->session->userdata('user_code'), 'record_status' => 'ACTIVE'])->row_array();


					// If login details exist then inactive the previous data
					if ($login_detail) {
						// Update the login status to inactive
						$this->db->where('login_id', $this->session->userdata('user_code'))->update('login_detail', array('record_status' => 'INACTIVE'));
					}

					// Insert the login details in login details table
					$insert_login_detail = array(
						'login_id'		=> $this->session->userdata('user_code'),
						'login_role'	=> $this->session->userdata('role'),
						'ip_address'	=> $this->input->ip_address(),
						'role_code'		=> $this->session->userdata('role'),
						'session_id'	=> $this->session->userdata('sess_id'),
						'created_by'	=> $this->session->userdata('user_name'),
						'created_on'	=> date('Y-m-d H:i:s', time()),
						'record_status'	=> 'ACTIVE',
						'message'       => 'Login Successfully.'
					);
					$this->db->insert('login_detail', $insert_login_detail);


					if ($userDataArr[0]['is_new_record'] != 0) {
						$resource_link = $userDataArr[0]['resource_link'];
					} else {
						$resource_link = 'set-password';
					}
					if ($userDataArr[0]['is_maintenance'] != 0) {
						return array('status' => true, 'msg' => 'Login success', 'index_page' => $resource_link);
					} else {
						return array('status' => false, 'msg' => 'Page is Under Maintenance');
					}
				} else {
					return array('status' => false, 'msg' => 'Your account has been blocked please contact administrator', 'logoutopt' => 'NO');
				}
			} else {
				return array('status' => false, 'msg' => 'Invalid Captcha.Please Try Again', 'logoutopt' => 'NO');
			}
		} else {
			return array('status' => false, 'msg' => 'Invalid username or password', 'logoutopt' => 'NO');
		}
	}

	public function logout()
	{

		// Insert log out details
		$result = $this->insert_logout_details();

		if ($result) {
			return array('status' => true);
		} else {
			return array('status' => false);
		}
	}

	public function insert_logout_details()
	{
		$today = date('Y-m-d H:i:s', now());

		$this->db->trans_begin();

		// Insert the logout details in login details table
		$insert_logout_details = array(
			'login_id'		=> $this->session->userdata('user_code'),
			'login_role'	=> $this->session->userdata('role'),
			'ip_address'	=> $this->input->ip_address(),
			'role_code'		=> $this->session->userdata('role'),
			'session_id'	=> $this->session->userdata('sess_id'),
			'created_by'	=> $this->session->userdata('user_name'),
			'created_on'	=> date('Y-m-d H:i:s', time()),
			'record_status'	=> 'INACTIVE',
			'message'       => 'Logout Successfully.'
		);

		$result = $this->db->where('login_id', $this->session->userdata('user_code'))->insert('login_detail', $insert_logout_details);

		if ($result) {
			$this->db->trans_commit();
			return true;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}
}
