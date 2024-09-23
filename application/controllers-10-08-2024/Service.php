<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		# helpers
		$this->load->helper(array('form'));

		#models
		$this->load->model(array('superadmin_model', 'admin_model', 'getter_model', 'common_model', 'case_model', 'causelist_model', 'fees_model', 'category_model', 'poa_model', 'system_model', 'refferal_request_model', 'ims_efiling_model', 'response_model', 'master_setup/country_model', 'master_setup/state_model', 'master_setup/arbitrator_setup_model', 'claimant_respondent_model', 'arbitral_tribunal_model', 'tag_cases_model'));
	}

	/*
		*	purpose : if request is not an ajax request then show error
		*/
	public function _remap($method)
	{
		if (!$this->input->is_ajax_request()) {
			redirect('page-not-found');
		} else {
			$check_user = $this->getter_model->get(null, 'get_user_check');
			if ($check_user) {
				self::$method();
			} else {
				echo json_encode(['status' => false, 'msg' => 'Unauthorized access']);
				die();
			}
		}
	}

	public function regexCheck($str)
	{
		// If empty then return true
		if (empty($str)) {
			return true;
		}

		$pattern = "/^[a-zA-Z0-9\s`\',._\\\\()\\/@|\-&]+$/";
		if (preg_match($pattern, $str)) {
			return true;
		}
		$this->form_validation->set_message('regexCheck', 'The %s field is not in valid format.');
		return false;
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
	/**
	 * For Admin setup
	 * Author   		: Debashish Jyotish
	 * Function 		: get_datatable_data
	 * purpose  		: Get Resource data
	 * Date     		: 18/05/2018
	 * Remark   		:  Get data from model and forward data as JSON   
	 */
	public function logout_all_system()
	{
		echo json_encode($this->getter_model->get($_POST, 'logout_all_system'));
	}
	public function get_logindetails_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_login_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblLoginDetails')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	 * Author   		: Debashish Jyotish
	 * Function 		: get_dept_details
	 * purpose  		: Get Profile Setup
	 * Date     		: 10/07/2018
	 * Remark   		: Get data from model and forward data as JSON   
	 */
	public function get_account_details()
	{
		$user_code = $this->uri->segment(3);
		echo json_encode($this->superadmin_model->superadmin($user_code, 'GET_ACCOUNT_DETAILS'));
	}
	public function operation_profile_details()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_profile_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_profile_modification')) {
					$config = array(
						array(
							'field'   => 'dept_desc',
							'label'   => 'Profile Description',
							'rules'   => 'xss_clean'
						),
						array(
							'field'   => 'txt_dept_address',
							'label'   => 'Address',
							'rules'   => 'xss_clean'
						),
						array(
							'field'   => 'txt_email',
							'label'   => 'Email',
							'rules'   => 'required|regex_match[/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([com net org]{3}(?:\.[a-z]{6})?)$/i]|xss_clean'
						),
						array(
							'field'   => 'txt_contact',
							'label'   => 'contact',
							'rules'   => 'required|regex_match[/^[0-9]{10}$/]|xss_clean'
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
						echo json_encode($this->superadmin_model->superadmin($_POST, 'OPERATION_PROFILE_DETAILS'));
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
	public function operation_change_password()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_cp_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_change_password')) {
					$config = array(
						array(
							'field'   => 'txt_old_password',
							'label'   => 'old password',
							'rules'   => 'trim|required|xss_clean'
						),
						array(
							'field'   => 'txt_confrim_password',
							'label'   => 'confrim password',
							'rules'   => 'trim|required|matches[txt_new_password]|xss_clean'
						),
						array(
							'field'   => 'txt_new_password',
							'label'   => 'new password',
							'rules'   => 'trim|required|xss_clean'
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
						echo json_encode($this->superadmin_model->superadmin($_POST, 'OPERATION_CHANGE_PASSWORD'));
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
	/**
	 * Author   		: Debashish Jyotish
	 * Function 		: get_datatable_data
	 * purpose  		: Get Resource data
	 * Date     		: 18/05/2018
	 * Remark   		:  Get data from model and forward data as JSON   
	 */
	public function get_datatable_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblresourcemaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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

	public function operation_resourcedata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_resource')) {
					$config = array(
						array(
							'field'   => 'txtresourcelink',
							'label'   => 'Resource Link',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtresourceName',
							'label'   => 'Resource Name',
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
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_resource']));
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

	public function get_datatable_role_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidroleCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblrolemaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function get_dropdown_data()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
	}

	public function operation_roledata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_role_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_role')) {
					if ($_POST['op_type_role'] == 'edit_role') {
						$config = array(
							array(
								'field'   => 'txtroleName',
								'label'   => 'Role Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'txtLandingPage',
								'label'   => 'Landing Page',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'txtrolecode',
								'label'   => 'Role Code',
								'rules'   => 'trim|required|is_unique[role_master.role_code]|xss_clean'
							),
							array(
								'field'   => 'txtroleName',
								'label'   => 'Role Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'txtLandingPage',
								'label'   => 'Landing Page',
								'rules'   => 'required|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_role']));
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
			}
		}
	}
	public function delete_data()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
	}


	/**
	 * Author   		: Debashish Jyotish
	 * Function 		: get_manage_user_data
	 * purpose  		: Get Manage User Data
	 * Date     		: 18/05/2018
	 * Remark   		: Get data from model and forward data as JSON   
	 */
	public function get_manage_user_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_user_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'user_table')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_userdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_add_user_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_user')) {
					if ($_POST['hiduser_name'] == $_POST['txtUserName'] && $_POST['op_type'] == 'edit_user') {
						$config = array(
							array(
								'field'   => 'txtDisplayName',
								'label'   => 'Display Name',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'txtUserName',
								'label'   => 'User Name',
								'rules'   => 'trim|required|is_unique[user_master.user_name]|xss_clean'
							),
							array(
								'field'   => 'txtDisplayName',
								'label'   => 'Display Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'txtEmailId',
								'label'   => 'Email',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'diac_serial_no',
								'label'   => 'DIAC Serial No.',
								'rules'   => 'required|xss_clean'
							),
							// array(
							// 	'field'   => 'txtEmailId',
							// 	'label'   => 'Email',
							// 	'rules'   => 'required|xss_clean|is_unique[user_master.email]'
							// ),
							// array(
							// 	'field'   => 'txtPhoneNumber',
							// 	'label'   => 'Mobile Number',
							// 	'rules'   => 'required|regex_match[/^[1-9][0-9]{0,9}$/]|xss_clean|is_unique[user_master.phone_number]'
							// ),
							array(
								'field'   => 'txtPhoneNumber',
								'label'   => 'Mobile Number',
								'rules'   => 'required|regex_match[/^[1-9][0-9]{0,9}$/]|xss_clean'
							),

							array(
								'field'   => 'cmb_user_role',
								'label'   => 'User Role',
								'rules'   => 'required|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
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

	/* Author   		: Debashish Jyotish
		* Function 		: get_group_setup
		* purpose  		: Get Resource data
		* Date     		: 18/05/2018
		* Remark   		:  Get data from model and forward data as JSON   
		*/
	public function get_group_setup()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_group_setup_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblgroupmaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function get_tablevalue()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, 'get_tablevalue'));
	}
	public function get_mapping_dept()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, 'get_mapping_dept'));
	}
	public function operation_groupdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_add_group_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_group')) {
					$config = array(
						array(
							'field'   => 'txtGroupName',
							'label'   => 'User Name',
							'rules'   => 'trim|required|xss_clean'
						),
						array(
							'field'   => 'cmbtable',
							'label'   => 'Display Name',
							'rules'   => 'trim|required|xss_clean'
						),
						array(
							'field'   => 'txtoperTable',
							'label'   => 'Operational Table',
							'rules'   => 'trim|xss_clean'
						),
						array(
							'field'   => 'txtoperColumn',
							'label'   => 'Operational Column',
							'rules'   => 'trim|xss_clean'
						),
						array(
							'field'   => 'txtExColumn',
							'label'   => 'Exicution Column',
							'rules'   => 'trim|required|xss_clean'
						)
					);

					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
						//echo validation_errors();
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_group']));
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
	public function get_editgroup_data()
	{
		$type = $this->uri->segment(3);
		echo json_encode($this->Superadmin_model->superadmin($_POST, $type));
	}
	public function get_mapgroupdata_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_group_mapping_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblgroupmapping')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function get_rolegroupmapping_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_role_group_mapping_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblrolegroupmapping')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_role_group_data()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_role_group_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_role_group')) {
					$config = array(
						array(
							'field'   => 'cmbrolecode',
							'label'   => 'Role Name',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'cmbgroupcode',
							'label'   => 'Group Name',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txtRoleGroup',
							'label'   => 'Role Group Name',
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
						//echo validation_errors();
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_role_group']));
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
	public function get_user_rolegroup_mapping_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_userrole_group_mapping_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblUser_Role_group_mapping')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_user_rolegroup_data()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_userrolegroup_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_userrolegroup')) {
					$config = array(
						array(
							'field'   => 'txtrole_group',
							'label'   => 'Role-Group Name',
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
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_user_role_group']));
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

	/* Author   	: Debashish Jyotish
		* Function 		: get_title_setup
		* purpose  		: Get Title Setup
		* Date     		: 09/07/2018
		* Remark   		:  Get data from model and forward data as JSON   
		*/
	public function get_title_setup()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_title_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtbl_title_setup')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_titlesetup()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_title_setup_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_title_setup')) {
					$config = array(
						array(
							'field'   => 'txt_title_name',
							'label'   => 'Title Name',
							'rules'   => 'required|xss_clean'
						),
						array(
							'field'   => 'txt_title_img',
							'label'   => 'Title Image',
							'rules'   => 'xss_clean'
						),
						array(
							'field'   => 'txt_desc',
							'label'   => 'Title Description',
							'rules'   => 'xss_clean'
						),
						array(
							'field'   => 'title_status',
							'label'   => 'Title Status',
							'rules'   => 'xss_clean'
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
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_title_type']));
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

	public function get_datatable_menu_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidmenuCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblMenu')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function sa_reset_password()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
	}

	public function delete_user()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, 'delete_user'));
	}

	public function operation_menudata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['hidmenuInsertCsrfToken'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frmMenu')) {
					if ($_POST['op_type'] != 'copy_menu') {
						if ($_POST['op_type'] == 'edit_menu') {
							$config = array(
								array(
									'field'   => 'txtmenulinktext',
									'label'   => 'Link Text',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'cmbMenuLinkURL',
									'label'   => 'Link Url',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'cmbMenuParent',
									'label'   => 'Parent',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'txtMenuslno',
									'label'   => 'Menu Sl No',
									'rules'   => 'trim|required'
								)
							);
						} else {
							$config = array(
								array(
									'field'   => 'txtmenulinktext',
									'label'   => 'Link Text',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'cmbMenuLinkURL',
									'label'   => 'Link Url',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'cmbMenuParent',
									'label'   => 'Parent',
									'rules'   => 'trim|required'
								),
								array(
									'field'   => 'txtMenuslno',
									'label'   => 'Menu Sl No',
									'rules'   => 'trim|required'
								)
							);
						}

						$this->form_validation->set_rules($config);
						if ($this->form_validation->run() == FALSE) {
							$data = array(
								'status' => 'validationerror',
								'msg' => validation_errors()
							);
							echo json_encode($data);
						} else {
							echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
						}
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function get_all_department_list()
	{
		$inputCsrfToken = $_POST['hidDeptCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblDepartmentMaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
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
	public function get_all_vendor_list()
	{
		$inputCsrfToken = $_POST['hidVendorCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblVendorMaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
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
	public function get_all_technician_list()
	{
		$inputCsrfToken = $_POST['hidTechCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblTechnicianMaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
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
	public function ApprovalUserCreate()
	{
		echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type']));
	}
	/*
			@For Admin setup
			@This below function is used for inserting and updating the menu setup data
			@Date	 :17-07-2018
		*/

	public function get_gencode_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_gencode_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'gen_code_table')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_gencode_data()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_gencode_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_gen_code')) {
					if ($_POST['op_type'] == 'edit_gen_code') {
						$config = array(
							array(
								'field'   => 'hid_id',
								'label'   => 'GenCode Id',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'description',
								'label'   => 'GenCode Description',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'sl_no',
								'label'   => 'Serial Number',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'status',
								'label'   => 'Status',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'gen_code_group',
								'label'   => 'GenCode Group',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'gen_code',
								'label'   => 'GenCode',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'description',
								'label'   => 'GenCode Description',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'sl_no',
								'label'   => 'Serial Number',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'status',
								'label'   => 'Status',
								'rules'   => 'required|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_genCode()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_country_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_country_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'country_table')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_countrydata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_country_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_country')) {
					if ($_POST['op_type'] == 'edit_country') {
						$config = array(
							array(
								'field'   => 'country_name',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'country_status',
								'label'   => 'Status',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'country_code',
								'label'   => 'Country Code',
								'rules'   => 'trim|required|is_unique[country_master.country_code]|xss_clean'
							),
							array(
								'field'   => 'country_name',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'country_status',
								'label'   => 'Status',
								'rules'   => 'required|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_data_country()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_statemaster_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_state_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblState')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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

	public function op_state_master()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_state_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_state')) {
					if (!empty($_POST['op_type'])) {
						$config = array(
							array(
								'field'   => 'state_code',
								'label'   => 'State Code',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'state_name',
								'label'   => 'State Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_country',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'state_status',
								'label'   => 'State Status',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type is blank'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}

	public function delete_state_data()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_country_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_state_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_dist_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_block_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_gp_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_village_dropdown()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function check_duplicate_village_data()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}

	public function get_form_type()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_approval_status()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}
	public function get_area_type()
	{
		echo json_encode($this->getter_model->get(null, $_POST['op_type']));
	}
	public function get_gp_master()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_Gp_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblGp')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_gpdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_circle_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_circle')) {
					if (!empty($_POST['op_type'])) {
						$config = array(
							array(
								'field'   => 'gp_code',
								'label'   => 'Gram Panchayat Code',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'gp_name',
								'label'   => 'Gram Panchayat Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_country',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_state',
								'label'   => 'State Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_dist',
								'label'   => 'District Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_block',
								'label'   => 'Block Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'gp_status',
								'label'   => 'Gram Panchayat Status',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type is blank'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_gp()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_village_master()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_village_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblVillage')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_villagedata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_village_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_village')) {
					if (!empty($_POST['op_type'])) {
						$config = array(
							array(
								'field'   => 'txtCensusCode',
								'label'   => 'Census Code',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'txtVillageName',
								'label'   => 'Village Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_state',
								'label'   => 'State Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_dist',
								'label'   => 'District Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_block',
								'label'   => 'Block Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_gp',
								'label'   => 'Gram Panchayat Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'village_status',
								'label'   => 'Village Status',
								'rules'   => 'required|xss_clean|trim'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type is blank'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_village()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_district_master()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_district_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'district_table')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_districtdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_district_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_district')) {
					if (!empty($_POST['op_type'])) {
						$config = array(
							array(
								'field'   => 'district_code',
								'label'   => 'District Code',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'txtDistCensusCode',
								'label'   => 'Census Code',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'district_name',
								'label'   => 'District Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_country',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'cmb_state',
								'label'   => 'State Name',
								'rules'   => 'required|xss_clean|trim'
							),
							array(
								'field'   => 'district_status',
								'label'   => 'District Status',
								'rules'   => 'required|xss_clean|trim'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type is blank'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_district()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_block_master()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_block_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'block_table')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_blockdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_block_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_block')) {
					if (!empty($_POST['op_type'])) {
						$config = array(
							array(
								'field'   => 'block_code',
								'label'   => 'Block Code',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'block_name',
								'label'   => 'Block Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_country',
								'label'   => 'Country Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_state',
								'label'   => 'State Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'cmb_district',
								'label'   => 'District Name',
								'rules'   => 'required|xss_clean'
							),
							array(
								'field'   => 'block_status',
								'label'   => 'Block Status',
								'rules'   => 'required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type is blank'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_block()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_amount_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_amount_setup_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'amount_setup_table')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_amount_setup()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_op_gencode_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_amt_setup')) {
					if ($_POST['op_type'] == 'add_amount_setup') {
						$config = array(
							array(
								'field'   => 'cmb_licence_for',
								'label'   => 'Licence For',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'cmb_appl_type',
								'label'   => 'Application Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'cmb_applied_for',
								'label'   => 'Applied For',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'cmb_area_type',
								'label'   => 'Area Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txt_amount',
								'label'   => 'Amount',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'status',
								'label'   => 'Status',
								'rules'   => 'trim|required|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'hidden_licence_for',
								'label'   => 'Licence For',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'hidden_appl_type',
								'label'   => 'Application Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'hidden_applied_for',
								'label'   => 'Applied For',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'hidden_area_type',
								'label'   => 'Area Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txt_amount',
								'label'   => 'Amount',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'status',
								'label'   => 'Status',
								'rules'   => 'trim|required|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_amount_setup()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	/**
	 * Author   		: Debashish Jyotish
	 * Function 		: get_emailsetup_data,
	 * purpose  		: To show data in datatable, 
	 * List of data 	: User, Menu, Role, Resource, Group
	 * Date     		: 19-07-2018
	 * Remark   		: Get data from model and forward data as JSON 
	 */

	public function get_emailsetup_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidemailCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblprovidermaster')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_providerdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_provider_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_provider')) {
					if ($_POST['op_type'] == 'edit_provider') {
						$config = array(
							array(
								'field'   => 'txtProvidername',
								'label'   => 'Provider Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtHostName',
								'label'   => 'Host Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtPort',
								'label'   => 'Port',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txt_Email',
								'label'   => 'Email',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txt_password',
								'label'   => 'Password',
								'rules'   => 'required|trim|xss_clean'
							),

							array(
								'field'   => 'cmb_smptauth',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmb_smptsecure',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmbStatus',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							)

						);
					} else {
						$config = array(
							array(
								'field'   => 'txtProvidername',
								'label'   => 'Provider Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtHostName',
								'label'   => 'Host Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtPort',
								'label'   => 'Port',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txt_Email',
								'label'   => 'Email',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txt_password',
								'label'   => 'Password',
								'rules'   => 'required|trim|xss_clean'
							),

							array(
								'field'   => 'cmb_smptauth',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmb_smptsecure',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmbStatus',
								'label'   => 'SMTP',
								'rules'   => 'required|trim|xss_clean'
							),
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_provider()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_setupemail_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['emailSetupCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblemailsetup')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_emaildata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_email_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_email')) {
					if ($_POST['op_type_email'] == 'edit_email') {
						$config = array(
							array(
								'field'   => 'txtMailType',
								'label'   => 'Provider Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtSubject',
								'label'   => 'Host Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtContent',
								'label'   => 'Port',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtProvider',
								'label'   => 'Email',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmbEmailStatus',
								'label'   => 'Password',
								'rules'   => 'required|trim|xss_clean'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'txtMailType',
								'label'   => 'Provider Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtSubject',
								'label'   => 'Host Name',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtContent',
								'label'   => 'Port',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'txtProvider',
								'label'   => 'Email',
								'rules'   => 'required|trim|xss_clean'
							),
							array(
								'field'   => 'cmbEmailStatus',
								'label'   => 'Password',
								'rules'   => 'required|trim|xss_clean'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type_email']));
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
			}
		}
	}
	public function delete_emailsetup()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_smsprovider_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidsmsCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblsmsmaster')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_smsproviderdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_smsprovider_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_smsprovider')) {
					if ($_POST['op_type'] == 'edit_smsprovider') {
						$config = array(
							array(
								'field'   => 'txtProviderName',
								'label'   => 'Provider Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txtSMSUrl',
								'label'   => 'SMS Url Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_UserName',
								'label'   => 'User Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_password',
								'label'   => 'Password',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_Sender',
								'label'   => 'SMPT Authentication',
								'rules'   => 'required'
							),
							array(
								'field'   => 'cmbSMSProviderstatus',
								'label'   => 'SMS Provider Status',
								'rules'   => 'required'
							)
						);
					} else {
						$config = array(
							array(
								'field'   => 'txtProviderName',
								'label'   => 'Provider Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txtSMSUrl',
								'label'   => 'SMS Url Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_UserName',
								'label'   => 'User Name',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_password',
								'label'   => 'Password',
								'rules'   => 'required'
							),
							array(
								'field'   => 'txt_Sender',
								'label'   => 'SMPT Authentication',
								'rules'   => 'required'
							),
							array(
								'field'   => 'cmbSMSProviderstatus',
								'label'   => 'SMS Provider Status',
								'rules'   => 'required'
							)
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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
			}
		}
	}
	public function delete_smsprovider()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	public function get_smssetup_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidsmsSetupCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblsmssetup')) {
				echo json_encode($this->admin_model->admin($_POST, $type));
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
	public function operation_smsdata()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_smssetup_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'frm_sms_setup')) {
					if ($_POST['op_type_sms'] == 'edit_smssetup') {
						$config = array(
							array(
								'field'   => 'txtSMSType',
								'label'   => 'SMS Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSubject',
								'label'   => 'Subject',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSMSContent',
								'label'   => 'SMS Content',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSMSProvider',
								'label'   => 'SMS Provider',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'cmbSMSstatus',
								'label'   => 'SMS status',
								'rules'   => 'trim|required|xss_clean'
							),
						);
					} else {
						$config = array(
							array(
								'field'   => 'txtSMSType',
								'label'   => 'SMS Type',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSubject',
								'label'   => 'Subject',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSMSContent',
								'label'   => 'SMS Content',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtSMSProvider',
								'label'   => 'SMS Provider',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'cmbSMSstatus',
								'label'   => 'SMS status',
								'rules'   => 'trim|required|xss_clean'
							),
						);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->admin_model->admin($_POST, $_POST['op_type_sms']));
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
			}
		}
	}
	public function delete_smssetup()
	{
		echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
	}
	/**
	 * 
	 * purpose  		: Page Creation, 
	 * Date     		: 25-07-2018
	 * Remark   		: Create Contoller,Model and View Page
	 */
	public function get_controller_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidControllerCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblControllerMaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_controller()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_controller_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_controller')) {
					if ($_POST['op_controller'] == 'add_controller') {
						$config = array(
							array(
								'field'   => 'txt_controller_code',
								'label'   => 'Controller Code',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txt_controller_name',
								'label'   => 'Controller Name',
								'rules'   => 'trim|required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type Error'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_controller']));
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
			}
		}
	}
	public function get_model_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidmodelCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblmodelmaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_model()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_model_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_model')) {
					if ($_POST['op_type_model'] == 'add_model') {
						$config = array(
							array(
								'field'   => 'txt_model_code',
								'label'   => 'Model Code',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txtModelName',
								'label'   => 'Model Name',
								'rules'   => 'trim|required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type Error'
						);
						echo json_encode($data);
					}

					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_type_model']));
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
			}
		}
	}
	public function get_view_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['hidViewCsrfToken'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dtblviewmaster')) {
				echo json_encode($this->superadmin_model->superadmin($_POST, $type));
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
	public function operation_view()
	{
		$temp_rule = array("&lt", "&gt", "<", ">", "(", "=");
		if (hasXSS($_POST, $temp_rule)) {
			$data = array(
				'status' => false,
				'msg' => 'Special chararacters like <,>,=,(,),&lt;,&gt; are not allowed'
			);
			echo json_encode($data);
		} else {
			$input_csrf_token = $_POST['csrf_view_token'];
			if (!empty($input_csrf_token)) {
				if (checkToken($input_csrf_token, 'form_view')) {
					if ($_POST['op_view'] == 'add_view') {
						$config = array(
							array(
								'field'   => 'cmb_view_path',
								'label'   => 'View path',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txt_view_code',
								'label'   => 'View Code',
								'rules'   => 'trim|required|xss_clean'
							),
							array(
								'field'   => 'txt_view_name',
								'label'   => 'View Name',
								'rules'   => 'trim|required|xss_clean'
							)
						);
					} else {
						$data = array(
							'status' => false,
							'msg' => 'Operation Type Error'
						);
						echo json_encode($data);
					}
					$this->form_validation->set_rules($config);
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'status' => 'validationerror',
							'msg' => validation_errors()
						);
						echo json_encode($data);
					} else {
						echo json_encode($this->superadmin_model->superadmin($_POST, $_POST['op_view']));
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
			}
		}
	}
	/**Page Creation end**/
	/**
	 * Function 		: import_countrydata,
	 * purpose  		: Upload the country excel data to database, 
	 * Date     		: 20-07-2018
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_countrydata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';

		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
				for ($i = 2; $i <= $arrayCount; $i++) {
					$addresses = array();
					$country_code = $SheetDataKey['country_code'];
					$country_name = $SheetDataKey['country_name'];
					$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
					$country_name = filter_var(trim($allDataInSheet[$i][$country_name]), FILTER_SANITIZE_STRING);
					$fetchData[] = array('country_code' => $country_code, 'country_name' => $country_name);
				}
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('COUNTRY_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}

	/**
	 * Function 		: import_Statedata,
	 * purpose  		: Upload the state excel data to database, 
	 * Date     		: 20-07-2018
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_statedata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';

		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
				for ($i = 2; $i <= $arrayCount; $i++) {
					$addresses = array();
					$state_code = $SheetDataKey['state_code'];
					$state_name = $SheetDataKey['state_name'];
					$country_code = $SheetDataKey['country_code'];
					$state_code = filter_var(trim($allDataInSheet[$i][$state_code]), FILTER_SANITIZE_STRING);
					$state_name = filter_var(trim($allDataInSheet[$i][$state_name]), FILTER_SANITIZE_STRING);
					$country_code = filter_var(trim($allDataInSheet[$i][$country_code]), FILTER_SANITIZE_STRING);
					$fetchData[] = array('state_code' => $state_code, 'state_name' => $state_name, 'country_code' => $country_code);
				}
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('STATE_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}
	/**
	 * Function 		: import_districtdata,
	 * purpose  		: Upload the District excel data to database, 
	 * Date     		: 21-07-2018
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_districtdata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';

		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
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
					$fetchData[] = array('district_code' => $district_code, 'district_name' => $district_name, 'state_code' => $state_code, 'country_code' => $country_code, 'dist_census_code' => $dist_census_code);
				}
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('DISTRICT_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}
	/**
	 * Function 		: import_blockdata,
	 * purpose  		: Upload the Circle excel data to database, 
	 * Date     		: 21-07-2018
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_blockdata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';

		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
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
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('BLOCK_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}
	/**
	 * Function 		: import_gpdata,
	 * purpose  		: Upload the Circle excel data to database, 
	 * Date     		: 21-07-2018
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_gpdata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';
		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
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
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('GP_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}
	/**
	 * Function 		: import_villagedata,
	 * purpose  		: Upload the Village excel data to database, 
	 * Date     		: 13-04-2019
	 * Remark   		: Get data from excel and Insert into the database
	 */
	public function import_villagedata()
	{
		require_once APPPATH . '/third_party/phpexcel/PHPExcel.php';
		$data = '';
		if ($this->input->post('importfile')) {
			$path = FCPATH . 'public/upload/excel_files/';
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			if (!empty($this->input->post('importfile'))) {
				$import_xls_file = $this->input->post('importfile');
			} else {
				$import_xls_file = 0;
			}
			$inputFileName = $import_xls_file;
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$arrayCount = count($allDataInSheet);
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
			if ($flag == 1) {
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
				$data['employeeInfo'] = $fetchData;
				$this->admin_model->setBatchImport($fetchData);
				echo json_encode($this->admin_model->importData('VILLAGE_DATA'));
			} else {
				echo "Error in excel  file";
			}
		}
	}
	public function get_getter_model()
	{
		echo json_encode($this->getter_model->get($_POST, $_POST['op_type']));
	}

	public function operation_add_complaint_form()
	{
		$input_csrf_token = $_POST['csrf_complaint_form_token'];
		if (!empty($input_csrf_token)) {
			if (checkToken($input_csrf_token, 'form_add_complaintform')) {
				if (!empty($_POST['op_type'])) {
					$config = array(
						array(
							'field'   => 'txtcomplaintcaseno',
							'label'   => 'Complaint Case No',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtcomplaintname',
							'label'   => 'Complaint Name',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtoppositename',
							'label'   => 'Oppostion Name',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtregdate',
							'label'   => 'Registration date',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtpetitiondate',
							'label'   => 'Petition Date',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtaction',
							'label'   => 'Action Taken',
							'rules'   => 'required|xss_clean|trim'
						)
					);
				} else {
					$data = array(
						'status' => false,
						'msg' => 'Operation Type is blank'
					);
					echo json_encode($data);
				}
				$this->form_validation->set_rules($config);
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
					echo json_encode($data);
				} else {
					echo json_encode($this->lokayukta_model->post($_POST, $_POST['op_type']));
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
		}
	}

	public function get_all_complaint_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableComplaintform')) {
				echo json_encode($this->lokayukta_model->get($_POST, $type));
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

	public function operation_add_case_form()
	{
		$input_csrf_token = $_POST['csrf_case_form_token'];
		if (!empty($input_csrf_token)) {
			if (checkToken($input_csrf_token, 'form_add_caseform')) {
				if (!empty($_POST['op_type'])) {
					$config = array(
						array(
							'field'   => 'txtCaseNo',
							'label'   => 'Case No',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtinstitutiondate',
							'label'   => 'Institution Date',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtcomplaintname',
							'label'   => 'Complaint Name',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtcomplaintat',
							'label'   => 'Complaint Address',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtcomplaintpo',
							'label'   => 'Complaint Address',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtcomplaintps',
							'label'   => 'Complaint Address',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'cmb_complaint_district',
							'label'   => 'Complaint District',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtpublicname',
							'label'   => 'Name',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtpublicaddress',
							'label'   => 'Address',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'cmb_public_district',
							'label'   => 'Complaint District',
							'rules'   => 'required|xss_clean|trim'
						),
						array(
							'field'   => 'txtbrieffacts',
							'label'   => 'Brief Facts',
							'rules'   => 'required|xss_clean|trim'
						)
					);
				} else {
					$data = array(
						'status' => false,
						'msg' => 'Operation Type is blank'
					);
					echo json_encode($data);
				}
				$this->form_validation->set_rules($config);
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
					echo json_encode($data);
				} else {
					echo json_encode($this->lokayukta_model->post($_POST, $_POST['op_type']));
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
		}
	}
	public function get_all_case_data()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseform')) {
				//echo json_encode($this->lokayukta_model->get($_POST, $type));
				echo json_encode(array('0' => ''));
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

	public function get_all_case_report()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseReport')) {
				echo json_encode($this->lokayukta_model->get($_POST, $type));
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


	/*
		* Name: Manoj Kumar Yadav
		* Date: 06 january 2019
		* Title: Add Rooms
		*/
	public function rooms_operation()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_add_rooms')) {
				echo json_encode($this->causelist_model->post($_POST, $_POST['op_type']));
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

	// Delete the room
	public function delete_room()
	{
		$type = 'DELETE_ROOM';
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableRoomform')) {
				echo json_encode($this->causelist_model->post($_POST, $type));
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

	/*
		* Function to get the display board list
		* * Date: 11-01-2021
		*/
	public function get_display_board_list()
	{
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableDisplayBoard')) {
				echo json_encode($this->causelist_model->get('', 'GET_DISPLAY_BOARD_LIST'));
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

	/*
		* Function to update the display board list
		* Date: 11-01-2021
		*/
	public function update_display_board()
	{
		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_display_board')) {
				echo json_encode($this->causelist_model->post($_POST, 'UPDATE_DISPLAY_BOARD_LIST'));
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

	/*
		* Function to remove the case from display board list
		* Date: 12-01-2021
		*/
	public function remove_display_board_case()
	{
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableDisplayBoard')) {
				echo json_encode($this->causelist_model->post($_POST, 'REMOVE_DISPLAY_BOARD_CASE'));
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

	/*
		* Function to get the hearings today list
		*/
	public function get_hearings_today_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableHTList')) {
				echo json_encode($this->causelist_model->get('', $type));
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

	/*
		* Function to get the cause list
		*/
	public function get_all_cause_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCauseList')) {
				echo json_encode($this->causelist_model->get('', $type));
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

	// Function to perform create and edit cause list
	public function cause_list_operation()
	{
		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_add_cause_list')) {
				echo json_encode($this->causelist_model->post($_POST, $_POST['op_type']));
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

	// Delete the cause list
	public function delete_cause_list()
	{
		$type = 'DELETE_CAUSE_LIST';
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCauseList')) {
				echo json_encode($this->causelist_model->post($_POST, $type));
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

	/*
		* Function to get the cases list
		*/
	public function get_all_case_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseRegisteredList')) {
				echo json_encode($this->case_model->get('', $type));
			} else {
				$data = array(
					"status" => false,
					"msg" => 'Invalid Security Token'
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


	/*
		* Function to get the registered cases list
		*/
	public function get_all_registered_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseform')) {
				echo json_encode($this->case_model->get('', $type));
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

	/*
		* Function to get all arbitral tribunnal list
		*/
	public function get_all_case_at_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableArbTriList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	public function get_all_arb_case_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'datatableArbList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Function to perform create and edit cases
	public function add_case_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_det_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Function used for update Arbitrator Empanelment
	public function update_arb_emp()
	{

		$inputCsrfToken = $_POST['csrf_emp_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'arb_emp_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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


	// Delete the case 
	public function delete_registered_case()
	{
		$type = 'DELETE_CASE';
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseform')) {
				echo json_encode($this->case_model->post($_POST, $type));
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

	// Delete the case arbitral tribunal list
	public function delete_case_at_list()
	{
		$type = 'DELETE_CASE_ARB_TRI_LIST';
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableArbTriList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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

	// Send notification of arbitral tribunal to all persons in the matter
	public function arbitral_tribunal_notification_to_everyone()
	{
		$case_no = $this->input->post('case_no');
		if ($case_no) {
			// =======================================
			// SEND THE NOTIFICATION TO EVERYONE START
			// =======================================

			// UNCOMMENT WHEN YOU NEED TO SEND THE MAILS TO ARBITRATORS

			$case_no_slug = $case_no;

			// Fetch the case details
			$case_details_data = $this->case_model->get(['slug' => $case_no_slug], 'GET_CASE_BASIC_DATA');

			// ============================================
			// Get the claimant and respondent data
			$case_claimants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, CLAIMANT_TYPE_CONSTANT);

			$claimants = array_filter($case_claimants, function ($claimant) {
				return !empty($claimant['email']);
			});
			$claimants_emails_temp = array_column($claimants, 'email');

			// Phone Number
			$claimants_for_phone = array_filter($case_claimants, function ($claimant) {
				return !empty($claimant['contact']);
			});
			$claimants_phone_temp = array_column($claimants_for_phone, 'contact');

			// ============================================
			// Get the COUNSELS details of claimants
			$case_claimants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, CLAIMANT_TYPE_CONSTANT);

			$claimant_counsels = array_filter($case_claimants_counsels, function ($counsel) {
				return !empty($counsel['email']);
			});
			$claimant_counsels_emails_temp = array_column($claimant_counsels, 'email');

			// Phone Number
			$claimant_counsels_for_phone = array_filter($case_claimants_counsels, function ($counsel) {
				return !empty($counsel['phone_number']);
			});
			$claimant_counsels_phone_temp = array_column($claimant_counsels_for_phone, 'phone_number');

			// ================================================
			// Respondents =====================
			$case_respondants = $this->claimant_respondent_model->get_cla_res_basic_info_using_case_and_slug($case_no_slug, RESPONDANT_TYPE_CONSTANT);
			$respondants = array_filter($case_respondants, function ($claimant) {
				return !empty($claimant['email']);
			});

			$respondants_emails_temp = array_column($respondants, 'email');

			// Phone Number
			$respondants_for_phone = array_filter($case_respondants, function ($claimant) {
				return !empty($claimant['contact']);
			});
			$respondants_phone_temp = array_column($respondants_for_phone, 'contact');

			// ============================================
			// Get the COUNSELS details of claimants
			$case_respondants_counsels = $this->common_model->get_counsels_of_case($case_no_slug, RESPONDANT_TYPE_CONSTANT);

			$respondant_counsels = array_filter($case_respondants_counsels, function ($counsel) {
				return !empty($counsel['email']);
			});
			$respondant_counsels_emails_temp = array_column($respondant_counsels, 'email');

			// Phone Number
			$respondant_counsels_for_phone = array_filter($case_respondants_counsels, function ($counsel) {
				return !empty($counsel['phone_number']);
			});
			$respondant_counsels_phone_temp = array_column($respondant_counsels_for_phone, 'phone_number');

			// ================================================
			// Get arbitrators of the case
			$case_arbitrators = $this->arbitral_tribunal_model->getBasicArbitratorData($case_no_slug);

			// print_r($arbitrators);
			// die;
			$arbitrators = array_filter($case_arbitrators, function ($arbitrator) {
				return !empty($arbitrator['email']);
			});
			$arbitrators_emails_temp = array_column($arbitrators, 'email');

			// Phone Number
			$arbitrators_for_phone = array_filter($case_arbitrators, function ($arbitrator) {
				return !empty($arbitrator['contact_no']);
			});
			$arbitrators_phone_temp = array_column($arbitrators_for_phone, 'contact_no');

			// ================================================
			// Get all the email ids
			// ==================================
			// Merge all the emails
			// Additional email arrays
			$additional_emails = [];

			// Fetch all users allocated in the case
			$users = $this->common_model->get_all_users_alloted_to_case($case_no_slug);

			if ($users && count($users) < 1) {
				failure_response('No users are not allocated', true, true);
			}

			// Fetch deputy counsel details
			$deputy_counsel_details = $this->common_model->get_deputy_counsel_alloted_to_case($case_no_slug);
			$deputy_counsel_smtp_email = (isset($deputy_counsel_details['email']) && !empty($deputy_counsel_details['email'])) ? $deputy_counsel_details['email'] : DEFAULT_SMTP_EMAIL_ID;

			// if ($users && count($users) < 1) {
			//     failure_response('No deputy counsel is allocated', true, true);
			// }

			// Email
			$users = array_filter($users, function ($user) {
				return !empty($user['email']);
			});
			$users_emails_temp = array_column($users, 'email');

			// Phone Number
			$users_for_phone = array_filter($users, function ($user) {
				return !empty($user['phone_number']);
			});
			$users_phone_temp = array_column($users_for_phone, 'phone_number');
			// $users_emails = implode(',', $users_emails_temp);

			// Merge all email arrays into a single array
			$all_emails = array_merge(
				$claimants_emails_temp,
				$claimant_counsels_emails_temp,
				$respondants_emails_temp,
				$respondant_counsels_emails_temp,
				$arbitrators_emails_temp,
				$users_emails_temp,
				$additional_emails
			);

			// Implode the merged array with commas
			$all_emails_string = implode(',', $all_emails);

			// =====================================================
			// =====================================================

			$additional_phones = [];
			// Merge all email arrays into a single array
			$all_phone_numbers = array_merge(
				$claimants_phone_temp,
				$claimant_counsels_phone_temp,
				$respondants_phone_temp,
				$respondant_counsels_phone_temp,
				$arbitrators_phone_temp,
				$users_phone_temp
			);


			// Implode the merged array with commas
			// print_r($all_phone_numbers);
			if (count($all_phone_numbers) > 0) {
				$full_case_no = get_full_case_number($case_details_data);

				foreach ($all_phone_numbers as $key => $phone_number) {
					// ======================================================
					// SEND THE WHATSAPP MESSAGE WHO ARE IN THE CASE
					$whatsapp_result = wa_arbitral_tribunal_notification($phone_number, $full_case_no, $case_details_data, $deputy_counsel_details, $case_arbitrators);

					// Insert WhatsApp Log Data
					$insert_whatsapp_log = [
						"transaction_id"     => $whatsapp_result['template_id'],
						'type'               => 'ARBITRAL_TRIBUNAL_ADDED',
						"phone_number"       => $phone_number,
						"message"            => $whatsapp_result['msg'],
						"status"             => ($whatsapp_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
						"created_by"         => $this->session->userdata('user_code'),
						"created_at"         => currentDateTimeStamp()
					];
					$result_whatsapp = $this->db->insert('support_whatsapp_record', $insert_whatsapp_log);
				}
			}

			// ========================================================
			// Create an array of all the persons who are involved in the case
			$persons = [
				'claimants' => $case_claimants,
				'case_claimants_counsels' => $case_claimants_counsels,
				'respondants' => $case_respondants,
				'case_respondants_counsels' => $case_respondants_counsels,
				'arbitrators' => $case_arbitrators,
				'users' => $users,
				'deputy_counsel' => $deputy_counsel_details
			];

			// Load the data
			$view_data['persons'] = $persons;
			$view_data['case_details_data'] = $case_details_data;
			$view_data['full_case_no'] = get_full_case_number($case_details_data);

			// echo '<pre>';
			// print_r($view_data);
			// die;

			// Send emails to everyone
			$subject = 'Arbitrator added in case :-' . get_full_case_number($case_details_data);
			$email_html_body = $this->load->view('emails/case_arbitrator_added', $view_data, true);

			// echo '<pre>';
			// echo $email_html_body;
			// die;

			$email_send_result = common_email_sending_info_to_all(strtolower($all_emails_string), $subject, $email_html_body, $deputy_counsel_smtp_email);


			// Email log data
			$insert_mail_log = [
				"transaction_id"     => $case_no_slug,
				'mail_type'         => 'ARBITRATOR_ADDED',
				"to_mail"             => strtolower($all_emails_string),
				"subject"             => $subject,
				"body"                 => $email_html_body,
				"status"             => ($email_send_result['status']) ? 'SUCCESS' : 'FAILED', //$output['msg']
				"created_by"         => $this->session->userdata('user_code'),
				"created_at"         => currentDateTimeStamp()
			];
			$result_mail = $this->db->insert('support_mail_record', $insert_mail_log);

			// =======================================
			// SEND THE NOTIFICATION TO EVERYONE START
			// =======================================

			$this->db->trans_commit();
			$data = array(
				'status' => true,
				'msg' => 'Notification sent successfully to everyone.'
			);
			echo json_encode($data);
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Case number is required'
			);
			echo json_encode($data);
		}
	}

	// Get all claimant and respondents list
	public function get_all_claimant_respondent_list()
	{
		echo json_encode([
			'status' => true,
			'data' => $this->case_model->get([
				'case_no' => $this->input->post('case_no')
			], 'GET_ALL_CLAIMANT_RESPONDENT')
		]);
	}


	// Get all claimant details
	public function get_all_claimant_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableClaimantList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Get all respondant details
	public function get_all_respondant_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableRespondantList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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


	// Function to perform create and edit claimant and respondant
	public function add_car_operation()
	{

		$inputCsrfToken = $_POST['csrf_car_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_car_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Function to perform create and edit claimant and respondant
	public function add_car_operation_for_refferal_request()
	{

		$inputCsrfToken = $_POST['csrf_car_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'car_refferal_request_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Delete the claimant from claimant list
	public function fetch_claimant_respondant_det_using_code_type()
	{
		$code = $this->input->post('code');
		$type = $this->input->post('type');
		if ($code && $type) {
			echo json_encode([
				'status' => true,
				'data' => $this->claimant_respondent_model->get_claimant_respondant_using_code_and_type($code, $type)
			]);
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Invalid data provided'
			);
			echo json_encode($data);
		}
	}

	// Delete the claimant from claimant list
	public function delete_claimant()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableClaimantList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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


	// Delete the respondant from respondant list
	public function delete_respondant()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableRespondantList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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


	// Get all other pleadings
	public function get_all_other_pleading_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableOPList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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


	// Function to perform create and edit of other pleadings of case
	public function add_case_op_operation()
	{

		$inputCsrfToken = $_POST['csrf_op_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_op_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Delete the case other pleadings 
	public function delete_case_op_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableOPList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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



	// Get all other correspondance
	public function get_all_other_correspondance_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableOCList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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


	// Function to perform create and edit of other correpondance of case
	public function add_case_oc_operation()
	{

		$inputCsrfToken = $_POST['csrf_oc_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_oc_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Delete the case other correspondance 
	public function delete_case_oc_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableOCList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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


	// Get all fee released
	public function get_all_fee_released_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFRList')) {
				echo json_encode($this->fees_model->get($_POST, $type));
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

	// Function to perform create and edit of fee released of case award/termination
	public function add_case_fr_operation()
	{

		$inputCsrfToken = $_POST['csrf_fr_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_fr_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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


	// Delete the case fee released
	public function delete_case_fr_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFRList')) {
				echo json_encode($this->fees_model->post($_POST, $type));
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


	// Get all case counsels details
	public function get_all_counsels_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCounselsList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Get all case counsels details
	public function get_all_counsels_list_for_dropdown()
	{
		echo json_encode([
			'status' => true,
			'data' => $this->getter_model->get(null, 'GET_ALL_COUNSELS_LIST')
		]);
		die;
	}

	// get case order list function is created by Ameen on dated 28-april-2023
	public function get_case_orders()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_case_order_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseOrder')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Function to perform create and edit of case counsels details
	public function add_case_counsel_operation()
	{

		$inputCsrfToken = $_POST['csrf_counsel_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_counsel_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Add Case order function created by ameen on 26-april-2023
	public function add_case_order_operation()
	{

		// echo '<pre>';
		// print_r($_FILES['case_order_doc']);
		// die;
		$inputCsrfToken = $_POST['csrf_case_order_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_order_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	public function delete_case_order()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseOrder')) {
				echo json_encode($this->case_model->post($_POST, $type));
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




	// Delete the case counsel details
	public function delete_case_counsel()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCounselsList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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

	/*
		* Fee Deposit
		*/

	// Get all fee deposit list
	public function get_all_fee_deposit_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFDList')) {
				echo json_encode($this->fees_model->get($_POST, $type));
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

	// Function to get all fee assessment
	public function fees_assessment()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'fees_asses_tbl')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	public function fees_assessment_update()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'fees_asses_token')) {
				echo json_encode($this->case_model->post($_POST, $type));
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

	// Function to perform create and edit of fee deposit of case
	public function add_case_fd_operation()
	{

		$inputCsrfToken = $_POST['csrf_fd_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_fd_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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

	// Delete the case fee ddeposit
	public function delete_case_fd_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFDList')) {
				echo json_encode($this->fees_model->post($_POST, $type));
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


	/*
		* Cost Deposit
		*/

	// Get all cost deposit list
	public function get_all_cost_deposit_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCDList')) {
				echo json_encode($this->fees_model->get($_POST, $type));
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


	// Function to perform create and edit of cost deposit of case
	public function add_case_cd_operation()
	{

		$inputCsrfToken = $_POST['csrf_cd_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_cd_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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

	// Delete the case cost ddeposit
	public function delete_case_cd_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCDList')) {
				echo json_encode($this->fees_model->post($_POST, $type));
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


	/*
		* Fee Refund
		*/

	// Get all fee refund list
	public function get_all_fee_refund_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFeeRefList')) {
				echo json_encode($this->fees_model->get($_POST, $type));
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


	// Function to perform create and edit of fee refund of case
	public function add_case_fee_ref_operation()
	{

		$inputCsrfToken = $_POST['csrf_fee_ref_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_fee_ref_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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

	// Delete the case fee refund
	public function delete_case_fee_ref_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableFeeRefList')) {
				echo json_encode($this->fees_model->post($_POST, $type));
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


	// Function to perform create and edit of fees and cost details of case
	public function add_case_fee_cost_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_fee_cost_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_fee_cost_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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


	// Get all amount details of case
	public function get_all_amount_details()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableAmountDetails')) {
				echo json_encode($this->fees_model->get($_POST, $type));
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


	// Get all panel category
	public function get_all_panel_category_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePanelCategoryList')) {
				echo json_encode($this->category_model->get($_POST, $type));
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


	// Function to perform create and edit of fees and cost details of case
	public function panel_category_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_add_panel_category')) {
				echo json_encode($this->category_model->post($_POST, $_POST['op_type']));
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


	// Delete the panel category
	public function delete_panel_category()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePanelCategoryList')) {
				echo json_encode($this->category_model->post($_POST, $type));
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


	// Get all panel of arbitrator
	public function get_all_poa_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePOAList')) {
				echo json_encode($this->poa_model->get($_POST, $type));
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


	// Function to perform create and edit of panel of arbitrator
	// Updated during HTTP parameter pollution solution
	// Date: 30-Aug-2020
	public function poa_operation()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				// print_r($_REQUEST);die;
				$inputCsrfToken = $_POST['csrf_case_form_token'];
				if ($inputCsrfToken != '') {
					if (checkToken($inputCsrfToken, 'form_add_poa')) {
						echo json_encode($this->poa_model->post($_POST, $_POST['op_type']));
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
			} else {
				$data = array(
					'status' => 'FALSE',
					'msg' => 'Invalid Method'
				);
				echo json_encode($data);
			}
		} else {
			$data = array(
				'status' => 'FALSE',
				'msg' => 'Unauthorized Access'
			);
			echo json_encode($data);
		}
	}


	// Delete the panel of arbitrator
	public function delete_poa()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePOAList')) {
				echo json_encode($this->poa_model->post($_POST, $type));
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


	// Get all purpose category
	public function get_all_purpose_category_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePurposeCategoryList')) {
				echo json_encode($this->category_model->get($_POST, $type));
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


	// Function to perform create and edit purpose category
	public function purpose_category_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_add_purpose_category')) {
				echo json_encode($this->category_model->post($_POST, $_POST['op_type']));
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


	// Delete the purpose category
	public function delete_purpose_category()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTablePurposeCategoryList')) {
				echo json_encode($this->category_model->post($_POST, $type));
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


	// Get all user role assign
	public function get_all_user_role_assign()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'user_role_assign_table')) {
				echo json_encode($this->admin_model->importData($type));
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


	// Function to perform create and edit user role assign
	public function add_urg_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'user_role_assign_form')) {
				echo json_encode($this->admin_model->admin($_POST, $_POST['op_type']));
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

	// Function to perform the check of case number is exist or not
	public function check_case_number()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_ecn_form')) {
				echo json_encode($this->case_model->get($_POST, $_POST['op_type']));
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



	// Get all case noting details
	public function get_all_noting_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotingList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Function to perform create and edit of case noting details
	public function add_case_noting_operation()
	{

		$inputCsrfToken = $_POST['csrf_noting_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_noting_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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


	// Delete the case noting details
	public function delete_case_noting()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotingList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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


	// Function to perform the check the notification
	public function check_notification()
	{

		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'CSRF_UNIVERSAL_TOKEN')) {
				echo json_encode($this->notification_model->get($_POST, 'CHECK_NOTIFICATION'));
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

	public function mark_notification_popup()
	{

		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'CSRF_UNIVERSAL_TOKEN')) {
				echo json_encode($this->notification_model->post($_POST, 'MARK_NOTIFICATION_POPUP'));
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


	// Get all case allotment details
	public function get_all_case_allotment()
	{
		$type = $this->uri->segment(3);

		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseAllotmentList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Function to perform create and edit of case allotment
	public function add_case_allotment_operation()
	{

		$inputCsrfToken = $_POST['csrf_ca_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_ca_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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


	// Delete the case allotment
	public function delete_case_allotment()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableCaseAllotmentList')) {
				echo json_encode($this->case_model->post($_POST, $type));
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

	// Get all notifications
	public function get_all_notifications()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotificationsList')) {
				echo json_encode($this->notification_model->get($_POST, $type));
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

	// Delete the notification
	public function delete_notification()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotificationsList')) {
				echo json_encode($this->notification_model->post($_POST, $type));
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

	// Delete all notification
	public function delete_all_notification()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotificationsList')) {
				echo json_encode($this->notification_model->post($_POST, $type));
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


	// Get all allotted case details
	public function get_all_allotted_case()
	{
		$type = $this->uri->segment(3);

		$inputCsrfToken = $_POST['csrf_trans_token'];

		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableAllottedCaseList')) {
				echo json_encode($this->case_model->get($_POST, $type));
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

	// Get all data logs details
	public function get_all_data_logs_list()
	{

		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableDataLogsList')) {
				echo json_encode($this->system_model->get($_POST, $type));
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

	// Function to perform cancel the cause list
	public function cancel_cause_list_operation()
	{

		$inputCsrfToken = $_POST['csrf_case_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'form_cancel_cause_list')) {
				echo json_encode($this->causelist_model->post($_POST, 'CANCEL_CAUSE_LIST'));
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

	// Response Format
	public function operation_response_format()
	{
		$inputCsrfToken = $_POST['csrf_response_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'response_format_form')) {
				echo json_encode($this->response_model->operation_response_format($_POST));
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

	public function get_all_response_format()
	{
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'datatableResponseFormat')) {
				echo json_encode($this->case_model->get($_POST, 'ALL_RESPONSE_FORMAT'));
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

	public function get_case_fee_deposit_details()
	{
		$inputCsrfToken = $_POST['csrf_fees_status_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'getFeesStatus')) {
				echo json_encode([
					'status' => true,
					'data' => $this->getter_model->get($_POST, 'GET_CASE_FEE_DEPOSIT_DETAILS')
				]);
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

	// Function to perform insert operation for reminders
	public function add_reminder_operation()
	{

		$inputCsrfToken = $_POST['csrf_reminder_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'reminder_form')) {
				echo json_encode($this->notification_model->post($_POST, $_POST['reminder_op_type']));
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

	// Function to perform the check of case number is already entered or not
	public function check_case_number_already_entered()
	{
		echo json_encode($this->case_model->get($_POST, 'CHECK_CASE_NO_ALREADY_ENTERED'));
	}

	// Function to get the role wise users list
	public function get_rolewise_users_list()
	{
		echo json_encode($this->getter_model->get($_POST, 'GET_ROLEWISE_USERS_LIST'));
	}

	// Function to get the single assessment sheet
	public function get_single_assessment_fee_details()
	{
		echo json_encode($this->fees_model->get($_POST, 'GET_SINGLE_ASSESSMENT_FEE_DETAILS'));
	}

	// Function to delete the assessment details
	public function delete_assessment_fee_details()
	{
		echo json_encode($this->fees_model->post($_POST, 'DELETE_CASE_FEE_COST_DETAILS'));
	}

	// Function to get the addresses
	public function get_addresses()
	{
		echo json_encode($this->getter_model->get($_POST, 'GET_ADDRESSES'));
	}

	// Function to get the singleaddress
	public function get_single_address()
	{
		echo json_encode($this->getter_model->get($_POST, 'GET_SINGLE_ADDRESS'));
	}

	public function get_all_countries()
	{
		$data = $this->country_model->getAllCountries();

		[$data[0], $data[102]] = [$data[102], $data[0]];

		echo json_encode($data);
	}

	public function get_states_using_country_code()
	{
		$data = $this->state_model->getAllStatesUsingCountryId($_POST['country_code']);
		echo json_encode($data);
	}

	// Perform add and edit operation on address
	public function address_operation()
	{
		$inputCsrfToken = $_POST['csrf_address_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'address_form')) {
				echo json_encode($this->case_model->post($_POST, $_POST['op_type']));
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

	// Delete address
	public function delete_address()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'tableEditAddress')) {
				echo json_encode($this->case_model->post($_POST, 'DELETE_ADDRESS'));
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

	public function get_case_arbitrators_list()
	{
		if ($this->input->post('case_slug')) {
			echo json_encode([
				'status' => true,
				'data' => $this->case_model->get([
					'slug' => $this->input->post('case_slug')
				], 'GET_CASEWISE_ARBITRATORS')
			]);
		} else {
			echo json_encode(['status' => false]);
		}
	}

	// Miscelleneous
	// public function get_all_miscellaneous_list()
	// {
	// 	$type = $this->uri->segment(3);
	// 	$inputCsrfToken = $_POST['csrf_trans_token'];
	// 	if ($inputCsrfToken != '') {
	// 		if (checkToken($inputCsrfToken, 'dataTableMiscellaneous')) {
	// 			echo json_encode($this->refferal_request_model->get('', $type));
	// 		} else {
	// 			$data = array(
	// 				'status' => false,
	// 				'msg' => 'Invalid Security Token'
	// 			);
	// 			echo json_encode($data);
	// 		}
	// 	} else {
	// 		$data = array(
	// 			'status' => 'FALSE',
	// 			'msg' => 'Empty Security Token'
	// 		);
	// 		echo json_encode($data);
	// 	}
	// }

	public function get_arbitrator_categories()
	{
		echo json_encode($this->category_model->get('', 'GET_ALL_PANEL_CATEGORY'));
	}

	public function get_all_miscellaneous_replies_list()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableMiscellaneousReply')) {
				echo json_encode($this->refferal_request_model->get('', $type));
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

	// Function to perform create and edit miscellaneous
	public function miscellaneous_reply_operation()
	{

		$inputCsrfToken = $_POST['csrf_miscellaneous_reply_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'miscellaneous_reply_form')) {
				echo json_encode($this->refferal_request_model->post($_POST, $_POST['op_type']));
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

	// Get all claimant and respondant details
	public function get_claimant_respondent_separately()
	{
		echo json_encode($this->common_model->get_claimant_respondent_separately($this->input->post('case_no')));
	}

	// Get diary number
	public function get_diary_number()
	{
		if ($this->input->post('type')) {
			echo json_encode($this->common_model->get_diary_number($this->input->post('type')));
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Type is required'
			);
			echo json_encode($data);
		}
	}

	// Get parties of new references
	public function get_new_reference_parties()
	{
		if ($this->input->post('diary_number') && $this->input->post('type') && $this->input->post('type') == 'NEW_REFERENCE') {
			echo json_encode($this->common_model->get_new_reference_parties($this->input->post('diary_number')));
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Diary number is required'
			);
			echo json_encode($data);
		}
	}

	// Get parties of new references
	public function get_all_other_noting_messages_datatable()
	{
		if ($this->input->post('hidden_on_code')) {
			echo json_encode($this->ims_efiling_model->get_all_other_noting_messages_datatable($this->input->post('hidden_on_code')));
			die;
		} else {
			$data = array(
				'status' => false,
				'msg' => 'Code is required'
			);
			echo json_encode($data);
		}
	}

	// Function to perform create and edit of other noting details
	public function other_noting_message_operation()
	{

		$inputCsrfToken = $_POST['csrf_message_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_message_form')) {
				$config = array(
					array(
						'field'   => 'hidden_on_code',
						'label'   => 'On Code',
						'rules'   => 'required|xss_clean'
					),
					array(
						'field'   => 'message_text',
						'label'   => 'Message',
						'rules'   => 'required|xss_clean'
					),
					array(
						'field'   => 'send_to',
						'label'   => 'Send To',
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
					echo json_encode($this->ims_efiling_model->insert_other_noting_message());
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

	// Function to perform create and edit of case assessment
	public function add_case_assessment()
	{

		$inputCsrfToken = $_POST['csrf_assessment_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'assessment_form')) {
				echo json_encode($this->fees_model->post($_POST, $_POST['op_type']));
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

	public function get_arbitrators_data()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->post('arb_code');
			// print_r($data);
			// die;
			$result = $this->getter_model->get($data, 'get_arbitrators_data');

			if ($result) {

				$output = array('status' => true, 'data' => $result);
			} else {
				$output = array('status' => false, 'msg' =>	'Server failed while fetching data');
			}
		}
		echo json_encode($output);
	}

	public function get_counsel_details()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->post('counsel_code');
			$result = $this->getter_model->get($data, 'GET_COUNSEL_DETAILS');

			if ($result) {

				$output = array('status' => true, 'data' => $result);
			} else {
				$output = array('status' => false, 'msg' =>	'Server failed while fetching data');
			}
		}
		echo json_encode($output);
	}

	public function get_state_name()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->post('country_code');
			$result = $this->getter_model->get($data, 'GET_STATE_NAME');

			if ($result) {

				$output = array('status' => true, 'data' => $result);
			} else {
				$output = array('status' => false, 'msg' =>	'Server failed while fetching data');
			}
		}
		echo json_encode($output);
	}

	public function get_case_list_details()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->post('case_type');
			// print_r($data);
			// die;
			$result = $this->causelist_model->get($data, 'get_single_case_details');

			if ($result) {
				$arbitrators = $this->arbitrator_setup_model->getArbitratorsLists($result['slug']);
				$output = array('status' => true, 'data' => $result, 'arbitrators' => $arbitrators);
			} else {
				$output = array('status' => false, 'msg' =>	'Server failed while fetching data');
			}
		}
		echo json_encode($output);
	}

	// get case order list function is created by Ameen on dated 28-april-2023
	public function change_case_status()
	{
		$type = $this->uri->segment(3);
		$inputCsrfToken = $_POST['csrf_mcs_form_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_stage_change_form')) {
				echo json_encode($this->case_model->post('', 'CHANGE_CASE_STATUS'));
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

	// Function to re-allocate the case
	public function re_allocate_all_case()
	{
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'case_re_allocation_value')) {
				$config = array(
					array(
						'field'   => 'type',
						'label'   => 'Type',
						'rules'   => 'required|xss_clean'
					),
					array(
						'field'   => 'role',
						'label'   => 'Role',
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
					echo json_encode($this->case_model->re_allocate_all_cases());
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

	// Function to update the task done for the noting
	public function noting_task_done()
	{
		$inputCsrfToken = $_POST['csrf_trans_token'];
		if ($inputCsrfToken != '') {
			if (checkToken($inputCsrfToken, 'dataTableNotingList')) {
				echo json_encode($this->case_model->post($_POST, 'TASK_DONE_CASE_NOTING_UPDATE'));
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
}
