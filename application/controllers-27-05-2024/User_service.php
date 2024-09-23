<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class User_service extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		# helpers
		$this->load->helper(array('form'));

		#models
		$this->load->model(array('superadmin_model', 'admin_model', 'user_model'));
	}

	/*
		*	purpose : if request is not an ajax request then show error
		*/
	public function _remap($method)
	{
		self::$method();
	}

	/**
	 * Purpose: Function to login
	 */

	public function login()
	{
		$inputCsrfToken = $_POST['csrf_login_form_token'];
		if ($inputCsrfToken != '') {

			if (checkToken($inputCsrfToken, 'login_form')) {
				$config = array(
					array(
						'field'   => 'txtUsername',
						'label'   => 'Username',
						'rules'   => 'required|xss_clean'
					),
					array(
						'field'   => 'txtPassword',
						'label'   => 'Password',
						'rules'   => 'required|xss_clean'
					),
					array(
						'field'   => 'txt_captcha',
						'label'   => 'Captcha',
						'rules'   => 'required|xss_clean'
					),
				);

				$this->form_validation->set_rules($config);
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
					echo json_encode($data);
				} else {
					echo json_encode($this->user_model->login());
				}
			} else {
				$data = array(
					'status' => false,
					'msg' => 'Invalid Security Token'
				);
				echo json_encode($data);
			}
		} else {
			$data = array(
				'status' => 'FALSE',
				'msg' => 'Empty Security Token'
			);
			echo json_encode($data);
		}
	}

	/**
	 *	purpose : get extension 
	 */
	private function getExtension($pFileName = null)
	{
		$i = strrpos($pFileName, ".");
		if (!$i) {
			return false;
		}
		$nameLength = strlen($pFileName) - $i;
		return substr($pFileName, $i + 1, $nameLength);
	}

	/**
	 *	purpose : Generate unique id
	 */
	private function get_unique_id()
	{
		return $id = md5(strtotime(date('Y-m-d H:i:s'))) . uniqid();
	}


	public function forgot_password()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$config = array(
				array(
					'field'   => 'txtUsername',
					'label'   => 'User Id',
					'rules'   => 'required|xss_clean'
				),
				array(
					'field'   => 'txtEmailId',
					'label'   => 'Email',
					'rules'   => 'required|regex_match[/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([com net org]{3}(?:\.[a-z]{6})?)$/i]|xss_clean'
				),
				array(
					'field'   => 'txt_captcha',
					'label'   => 'Captcha',
					'rules'   => 'required|xss_clean'
				),
			);

			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'status' => 'validationerror',
					'msg' => validation_errors()
				);
				echo json_encode($data);
			} else {
				echo json_encode($this->superadmin_model->superadmin($_POST, 'op_type_mail_link'));
			}
		}
	}
	public function reset_password()
	{
		$config = array(
			array(
				'field'   => 'txtPass',
				'label'   => 'Password',
				'rules'   => 'required|xss_clean'
			),
			array(
				'field'   => 'txtConPassword',
				'label'   => 'Confirm Password',
				'rules'   => 'required|matches[txtPass]|xss_clean'
			)
		);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'status' => 'validationerror',
				'msg' => validation_errors()
			);
			echo json_encode($data);
		} else {
			echo json_encode($this->superadmin_model->superadmin($_POST, 'op_type_reset_password'));
		}
	}
	public function set_password()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_setpassword_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'setPasswordForm')) {
					$config = array(
						array(
							'field'   => 'txtUserCode',
							'label'   => 'User Code',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtUserName',
							'label'   => 'Username',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtPassword',
							'label'   => 'Password',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtConPassword',
							'label'   => 'Confirm Password',
							'rules'   => 'required|matches[txtPassword]|xss_clean'
						)
					);
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, 'op_type_set_password'));
					}
				} else {
					$data = array(
						'status' => false,
						'msg' => 'Invalid Security Token'
					);
					echo json_encode($data);
				}
			} else {
				$data = array(
					'status' => false,
					'msg' => 'Empty Security Token'
				);
				echo json_encode($data);
			}
		}
	}
	public function user_register()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['hidCsrfToken'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'user_register_form')) {
					$config = array(
						array(
							'field'   => 'txtFirstName',
							'label'   => 'First Name',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtLastName',
							'label'   => 'Last Name',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtEmailId',
							'label'   => 'Email',
							'rules'   => 'required|regex_match[/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([com net org]{3}(?:\.[a-z]{6})?)$/i]|xss_clean'
						),
						array(
							'field'   => 'txtMobileNo',
							'label'   => 'Mobile Number',
							'rules'   => 'required|regex_match[/^[1-9][0-9]{0,9}$/]|xss_clean'
						),
						array(
							'field'   => 'txtDOB',
							'label'   => 'Date of Birth',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtPanCard',
							'label'   => 'Pan Card',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtAadhaarNumber',
							'label'   => 'Aadhaar Number',
							'rules'   => 'required|xss_clean'
						)
					);
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, 'new_user_register'));
					}
				} else {
					$data = array(
						'status' => false,
						'msg' => 'Invalid Security Token'
					);
					echo json_encode($data);
				}
			} else {
				$data = array(
					'status' => false,
					'msg' => 'Empty Security Token'
				);
				echo json_encode($data);
			}
		}
	}
}
