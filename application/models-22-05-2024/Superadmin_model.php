<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use \Firebase\JWT\JWT;
//require_once APPPATH."third_party/phpmailer/class.phpmailer.php";

class Superadmin_model extends CI_model
{

	private $role;

	function __construct()
	{
		parent::__construct();

		# helper
		$this->load->helper('date');
		$this->load->helper('send_email');

		if (ENVIRONMENT == 'production') {
			$this->db->save_queries = FALSE;
		}
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());

		$this->role 		= $this->session->userdata('role');
		$this->user_name 	= $this->session->userdata('user_name');

		require(APPPATH . 'libraries/php_jwt/ExpiredException.php');
		require(APPPATH . 'libraries/php_jwt/BeforeValidException.php');
		require(APPPATH . 'libraries/php_jwt/SignatureInvalidException.php');
		require(APPPATH . 'libraries/php_jwt/JWT.php');
		require(APPPATH . 'libraries/constants.php');


		/**
		 * The function generates jwt token,accept two parameter phone number and institute code.
		 * If it is needed we can make it reusable. 
		 * 
		 * @param undefined $user_id
		 * 
		 * @return
		 */
		function generateJWT($user_id, $user_name, $key = JWT_KEY)
		{

			$tokenId    = base64_encode(mcrypt_create_iv(32));
			$issuedAt   = time();
			$notBefore  = $issuedAt;
			$expire     = $notBefore + (1 * 60 * 30);            // Adding 60 seconds 
			$serverName = "server";
			$data = [
				'iat'  => $issuedAt,         // Issued at: time when the token was generated
				'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
				'iss'  => $serverName,       // Issuer
				'nbf'  => $notBefore,        // Not before
				'exp'  => $expire,           // Expire
				'data' => [                  // Data related to the signer user
					'id'   => $user_id, // userid from the users table
					'ins' => $user_name
				]
			];
			$secretKey = $key;

			$jwt = JWT::encode(
				$data,      //Data to be encoded in the JWT
				$secretKey, // The signing key
				'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
			);
			//print_r($jwt);die();
			return $jwt;
		}
	}



	public function diff_in_month($from, $to)
	{
		$frmDate = date_create($from);
		$toDate = date_create($to);
		$difference = date_diff($toDate, $frmDate, true);
		$month = $difference->format("%a") / 30;
		return $month;
	}
	//to get group data
	public function group_data()
	{
		$this->db->select('*');
		$this->db->from('group_master');
		$this->db->where('group_code', $this->session->userdata('group_code'));
		$this->db->where('record_status', 1);
		$result = $this->db->get();
		$row = $result->result_array();
		$operation_tbl = $row[0]['operation_tbl'];
		$operation_col = $row[0]['operation_col'];
		$exicution_col = $row[0]['exicution_col'];
		$this->db->select('d.' . $exicution_col . ' as id');
		$this->db->from('group_master gm');
		$this->db->join('group_mapping gmap', 'gm.group_code = gmap.group_code', 'inner');
		$this->db->join($operation_tbl . ' as d', 'gmap.map_value_code = d.' . $operation_col, 'inner');
		$this->db->where('gm.record_status', 1);
		$this->db->where('gm.group_code', $this->session->userdata('group_code'));
		$result1 = $this->db->get();
		$row_val = $result1->result_array();
		$id = '';
		foreach ($row_val as $row1) {
			$id = $id . "'" . $row1['id'] . "',";
		}
		$id = rtrim($id, ", ");
		return $id;
	}
	/**
	 * 	Generate random registration_no 
	 */
	public function rand_number($length)
	{
		$chars = "0123456789";
		return substr(str_shuffle($chars), 0, $length);
	}

	public function superadmin($data, $op, $stage = null)
	{
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());

		switch ($op) {
			case 'GET_ACCOUNT_DETAILS':
				$this->db->from('user_master a');
				$this->db->select('a.*,b.*');
				$this->db->join('user_details_master b', 'a.user_code=b.fk_user_code', 'inner');
				$this->db->where('user_code', $this->session->userdata('user_code'));

				$res = $this->db->get();
				return $res->result_array();
				break;
			case 'OPERATION_PROFILE_DETAILS':
				$user_code = $this->security->xss_clean($this->input->post('hid_user_code'));
				if (empty($_FILES['txt_Logo']['name'])) {
					$update_user_details_master_without_img = array(
						"description" 	=> $this->security->xss_clean($this->input->post('dept_desc')),
						"address" 		=> $this->security->xss_clean($this->input->post('txt_dept_address')),
						"updated_by" 	=> $this->security->xss_clean($this->user_name),
						"updated_on" 	=> $date,
						"record_status" => 1
					);
					$this->db->where('fk_user_code', $user_code);
					$result_update_user_details_master_without_img = $this->db->update('user_details_master', $update_user_details_master_without_img);
					if (!$result_update_user_details_master_without_img) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					} else {
						$update_without_img = array(
							"email" 		=> $this->security->xss_clean($this->input->post('txt_email')),
							"phone_number"	=> $this->security->xss_clean($this->input->post('txt_contact')),
							"updated_by" 	=> $this->security->xss_clean($this->user_name),
							"updated_on" 	=> $date,
							"record_status" => 1
						);
						$this->db->where('user_code', $user_code);
						$update_dept_nimg = $this->db->update('user_master', $update_without_img);
						if (!$update_dept_nimg) {
							$dbstatus = FALSE;
							$dbmessage = 'Error While Saving';
						} else {
							$dbstatus = TRUE;
							$dbmessage = 'update successfully';
						}
					}
				} else {
					//$this->load->helper('file');
					$allowed_mime_type_arr = array('image/jpg', 'image/png', 'image/jpeg');
					$mime = get_mime_by_extension($_FILES['txt_Logo']['name']);
					$dot_count 	= substr_count($_FILES['txt_Logo']['name'], '.');
					$zero_count = substr_count($_FILES['txt_Logo']['name'], "%0");
					if (in_array($mime, $allowed_mime_type_arr)) {
						if ($zero_count == 0 && $dot_count == 1) {
							$file_move_path  = FCPATH . 'public/upload/profile/' . $user_code;
							if (!is_dir($file_move_path))
								mkdir($file_move_path, 0777, true);

							$config['upload_path'] 		= $file_move_path;
							$config['file_name'] 		= $_FILES['txt_Logo']['name'];
							$config['allowed_types'] 	= 'jpg|png|jpeg';
							$config['max_size']     	= 1024; //sizein KB form
							$config['overwrite'] 		= TRUE;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('txt_Logo')) {
								$data = array('upload_data' => $this->upload->data());
								$store_path  = 'public/upload/profile/' . $user_code . '/' . $data['upload_data']['file_name'];

								$update_user_details_master_with_img = array(
									"user_logo" => $this->security->xss_clean($store_path),
									"description" 	=> $this->security->xss_clean($this->input->post('dept_desc')),
									"address" 		=> $this->security->xss_clean($this->input->post('txt_dept_address')),
									"updated_by" 	=> $this->security->xss_clean($this->user_name),
									"updated_on" 	=> $date,
									"record_status" => 1
								);
								$this->db->where('fk_user_code', $this->security->xss_clean($this->input->post('hid_user_code')));
								$result_update_user_details_master_with_img = $this->db->update('user_details_master', $update_user_details_master_with_img);
								if (!$result_update_user_details_master_with_img) {
									$dbstatus = FALSE;
									$dbmessage = 'Error While Saving';
								} else {
									$update_with_img = array(
										"email" 		=> $this->security->xss_clean($this->input->post('txt_email')),
										"phone_number"	=> $this->security->xss_clean($this->input->post('txt_contact')),
										"updated_by" 	=> $this->security->xss_clean($this->user_name),
										"updated_on" 	=> $date,
										"record_status" => 1
									);
									$this->db->where('user_code', $this->input->post('hid_user_code'));
									$update_dept_img = $this->db->update('user_master', $update_with_img);
									if (!$update_dept_img) {
										$dbstatus = FALSE;
										$dbmessage = 'Error While Saving';
									} else {
										$this->session->unset_userdata('user_logo');
										$this->session->set_userdata('user_logo', $store_path);
										$dbstatus = TRUE;
										$dbmessage = 'update successfully';
									}
								}
							} else {
								$dbstatus = FALSE;
								$dbmessage = $this->upload->display_errors();
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Please select only jpg/jpeg/png format.';
					}
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'new_user_register':
				$this->db->trans_begin();
				try {
					$this->db->select('email,phone_number');
					$this->db->where('email', $this->security->xss_clean($this->input->post('txtEmailId')));
					$this->db->or_where('phone_number', $this->security->xss_clean($this->input->post('txtMobileNo')));
					$unique_id_cheak = $this->db->get('user_master');
					$no_records = $unique_id_cheak->num_rows();
					if ($no_records == 0) {
						$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_master");
						$Sresult = $qry->result_array();
						$row2 = array_shift($Sresult);
						$user_code = $row2['max_id'] . rand(1000, 99999);
						$display_name = $this->security->xss_clean($this->input->post('txtFirstName')) . " " . $this->security->xss_clean($this->input->post('txtLastName'));
						$data = array(
							"user_code" 	=> $user_code,
							"user_name" 			=> $this->security->xss_clean($this->input->post('user_id')),
							"user_display_name" 	=> $display_name,
							"email" 				=> $this->security->xss_clean($this->input->post('txtEmailId')),
							"phone_number" 			=> $this->security->xss_clean($this->input->post('txtMobileNo')),
							"password" 				=> $this->security->xss_clean($this->input->post('secreatecode')),
							"primary_role" 			=> 'USER',
							"record_status" 		=> $this->security->xss_clean($this->input->post('user_status')),
							"record_status" 		=> 1,
							"is_new_record" 		=> 0,
							"created_by" 			=> $display_name,
							"created_on" 			=> $date
						);
						$insert_user = $this->db->insert('user_master', $data);
						if ($insert_user) {
							$user_master_details = array(
								"fk_user_code" 	=> $user_code,
								"dob" 			=> date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('txtDOB')))),
								"aadhaar_no" 	=> $this->security->xss_clean($this->input->post('txtAadhaarNumber')),
								"pancard_no" 	=> $this->security->xss_clean($this->input->post('txtPanCard')),
								"user_logo" 	=> 'public/upload/profile/defult.png',
								"profile_img" 	=> 'public/upload/profile/defult.png',
								"address" 		=> 'Arunachal Pradesh',
								"description" 	=> 'TEST',
								"record_status" => 1,
								"created_by" 	=> $display_name,
								"created_on" 	=> $date
							);
							$insert_user_master_details = $this->db->insert('user_details_master', $user_master_details);
							if ($insert_user_master_details) {
								$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_role_group_map");
								$Sresult = $qry->result_array();
								$row2 = array_shift($Sresult);

								$this->db->select('role_group_code,name,group_code');
								$this->db->where('role_code', 'USER');
								$this->db->get('role_group_mapping');
								$result = $this->db->get('role_group_mapping');
								$get_res = $result->result_array()[0];

								$insertdata = array(
									"user_rolegroup_code" => 'urg' . $row2['max_id'] . rand(100, 9999),
									"user_code" 		  => $user_code,
									"role_group_code" 	  => $get_res['role_group_code'],
									"created_by" 		  => $display_name,
									"created_on" 		  => $date
								);
								$insert_user_role_group_map = $this->db->insert('user_role_group_map', $insertdata);
								if ($insert_user_role_group_map) {
									$toMail1 = $this->input->post('txtEmailId');
									$subject = "New User Create";
									$body = "Dear " . $display_name . ",<br>
									<p>A temporary password has been sent to the email address you have provided.
									Please log in with the temporary password and change it to a password of your choice as soon as possible.</p> 
									<p>UserId :" . $this->security->xss_clean($this->input->post('user_id')) . "</p>
									<p>Password : password </p>";
									$ccMail2 = "";
									$bccMail3 = "";
									// $output = sendEmail($toMail1, $ccMail2, $bccMail3, $subject, $body);
									if (true) {
										$insert_mail = array(
											"transaction_id" 	=> $user_code,
											'mail_type' 		=> 'NEW_USER_CREATE',
											"to_mail" 			=> $this->security->xss_clean($this->input->post('txtEmailId')),
											"subject" 			=> $subject,
											"body" 				=> $body,
											"status" 			=> $output,
											"created_by" 		=> $this->user_name,
											"created_at" 		=> $date
										);
										$result_mail = $this->db->insert('support_mail_record', $insert_mail);
										if ($result_mail) {
											$this->db->trans_commit();
											$dbstatus = TRUE;
											$dbmessage = 'A temporary password has been sent to the email address.Please check your Email';
										} else {
											$this->db->trans_rollback();
											$dbstatus = false;
											$dbmessage = 'Error While Sending';
										}
									} else {
										$this->db->trans_rollback();
										$dbstatus = FALSE;
										$dbmessage = 'Error While Sending';
									}
								} else {
									$this->db->trans_rollback();
									$dbstatus = false;
									$dbmessage = 'Error While Saving';
								}
							} else {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = 'Error While Saving';
							}
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = 'Error While Saving';
						}
					} else {
						$this->db->trans_rollback();
						$dbstatus = false;
						$dbmessage = 'Email Id or Mobile Number is already registered';
					}
				} catch (Exception $e) {
					$dbstatus = false;
					$dbmessage = $e->getMessage();
					$this->db->trans_rollback();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'get_userdata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir = $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('a.user_name', 'a.user_display_name', 'a.email', 'a.phone_number'); //search filter will work on this column
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
				$iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('user_master a');
				$this->db->select("a.user_code, a.user_name, a.user_display_name, a.primary_role, a.email, a.phone_number, a.job_title, c.state_name, a.record_status, a.fk_state_code, b.profile_img, a.case_ref_no, crn.start_index as start_index, crn.end_index as end_index, crn2.start_index as start_index2, crn2.end_index as end_index2, crn3.start_index as start_index3, crn3.end_index as end_index3");
				$this->db->join("user_details_master b", "a.user_code = b.fk_user_code", "left");
				$this->db->join("state_master c", "a.fk_state_code = c.state_code", "left");
				$this->db->join("master_case_ref_no_tbl crn", "crn.code = a.case_ref_no AND crn.type = 'DC_CASE_REF_NO'", "left");
				$this->db->join("master_case_ref_no_tbl crn2", "crn2.code = a.case_ref_no AND crn2.type = 'COORDINATOR_CASE_REF_NO'", "left");
				$this->db->join("master_case_ref_no_tbl crn3", "crn3.code = a.case_ref_no AND crn3.type = 'CM_CASE_REF_NO'", "left");

				$this->db->where_in('a.primary_role', CMS_ROLES);
				$this->db->order_by('a.user_name', 'ASC');

				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				/*----FOR PAGINATION-----*/
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$this->db->from('user_master a');
				$this->db->select("a.user_name");
				$this->db->join("user_details_master b", "a.user_code = b.fk_user_code", "left");
				$this->db->join("state_master c", "a.fk_state_code = c.state_code", "left");
				$this->db->where_in('a.primary_role', CMS_ROLES);
				$res1 = $this->db->get();

				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] = $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}
					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'add_user':

				// Get the max id
				$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_master");
				$Sresult = $qry->result_array();

				$row2 = array_shift($Sresult);
				$user_code = $row2['max_id'] . rand(1000, 99999);

				$role = $this->input->post('cmb_user_role');

				$data = array(
					"user_code" 	=> $user_code,
					"user_name" 			=> $this->security->xss_clean($this->input->post('txtUserName')),
					"user_display_name" 	=> $this->security->xss_clean($this->input->post('txtDisplayName')),
					"fk_state_code" 	=> $this->security->xss_clean($this->input->post('cmb_state_code')),
					"email" 				=> $this->security->xss_clean($this->input->post('txtEmailId')),
					"phone_number" 			=> $this->security->xss_clean($this->input->post('txtPhoneNumber')),
					"primary_role" 			=> $role,
					"job_title" 			=> $this->security->xss_clean($this->input->post('txtJobTitle')),
					'case_ref_no'			=> '',
					"password" 				=> $this->security->xss_clean($this->input->post('secreatecode')),
					"record_status" 		=> $this->security->xss_clean($this->input->post('user_status')),
					"created_by" 			=> $this->user_name,
					"created_on" 			=> $date
				);

				if ($role == 'DEPUTY_COUNSEL' || $role == 'CASE_MANAGER' || $role == 'COORDINATOR') {
					$data['case_ref_no'] = $this->security->xss_clean($this->input->post('case_ref_no'));
				}

				$insert_user = $this->db->insert('user_master', $data);
				if ($insert_user) {
					$insert_user_details_master = $this->db->insert('user_details_master', array("fk_user_code" => $user_code, "user_logo" => 'public/upload/profile/defult.png'));
					if ($insert_user_details_master) {

						$toMail1 = $this->input->post('txtEmailId');
						$subject = "DIAC: New User Create";
						$body = "Dear " . $this->input->post('txtDisplayName') . ",<br>
							<p>A temporary password has been sent to the email address you have provided.
							Please log in with the temporary password and change it to a password of your choice as soon as possible.</p> 
							<p>UserId :" . $this->input->post('txtUserName') . "</p>
							<p>Password : password </p>
							<p>To proceed login. Please <a href='" . LOGIN_URL . "'>Click Here</a></p>";
						$ccMail2 = "";
						$bccMail3 = "";
						// $output = sendEmails($toMail1, $subject, $body, $ccMail2, $bccMail3);

						// $output['status']
						if (true) {
							$insert_mail = array(
								"transaction_id" 	=> $user_code,
								'mail_type' 		=> 'USER_CREATE',
								"to_mail" 			=> $toMail1,
								"subject" 			=> $subject,
								"body" 				=> $body,
								"status" 			=> 'SUCCESS', //$output['msg']
								"created_by" 		=> $this->user_name,
								"created_at" 		=> $date
							);
							$result_mail = $this->db->insert('support_mail_record', $insert_mail);

							if ($result_mail) {
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'A temporary password has been sent to the email address.Please check your Email';
							} else {
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = 'Error While saving records';
							}
						} else {
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Error While Sending E-mail';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving 2';
					}
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving 1';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'edit_user':

				$role = $this->security->xss_clean($this->input->post('cmb_user_role'));

				$data = array(
					"user_name" => $this->input->post('txtUserName'),
					"user_display_name" => $this->input->post('txtDisplayName'),
					"fk_state_code" 	=> $this->security->xss_clean($this->input->post('cmb_state_code')),
					"email" => $this->input->post('txtEmailId'),
					"primary_role" 			=> $role,
					"phone_number" => $this->input->post('txtPhoneNumber'),
					"job_title" 	=> $this->security->xss_clean($this->input->post('txtJobTitle')),
					"record_status" => $this->input->post('user_status'),
					"updated_by" => $this->user_name
				);

				if ($role == 'DEPUTY_COUNSEL' || $role == 'CASE_MANAGER' || $role == 'COORDINATOR') {
					$data['case_ref_no'] = $this->security->xss_clean($this->input->post('case_ref_no'));
				}

				$this->db->where('user_code', $this->input->post('hiduser_code'));
				$update_user = $this->db->update('user_master', $data);
				if ($update_user) {
					$this->db->where('fk_user_code', $this->input->post('hiduser_code'));
					$update_user_details_master = $this->db->update('user_details_master', array("user_logo" => 'public/upload/profile/defult.png'));
					if ($update_user_details_master) {
						$dbstatus = TRUE;
						$dbmessage = 'Data Update Sucessfully';
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'sa_reset_password':
				$data = array(
					"password" => $this->input->post('secreatecode'),
					"record_status" => 1,
					"is_new_record" => 0,
					"updated_by" => $this->role,
					"updated_on" => $date
				);
				$this->db->where('user_code', $this->input->post('hiduser_code'));
				$update_password = $this->db->update('user_master', $data);
				if ($update_password) {
					$toMail1 = $this->input->post('email');
					$subject = "DIAC: Password Changed";
					$body = "Dear " . $this->input->post('user_display_name') . ",<br>
						<p>A temporary password has been sent to the email address you have provided.
						Please log in with the temporary password and change it to a password of your choice as soon as possible.</p> 
						<p>UserId :" . $this->input->post('user_name') . "</p>
						<p>Password : password </p>";
					$ccMail2 = "";
					$bccMail3 = "";
					$output = sendEmail($toMail1, $ccMail2, $bccMail3, $subject, $body);
					if ($output['status']) {
						$insert_mail = array(
							"transaction_id" 	=> $this->input->post('hiduser_code'),
							'mail_type' 		=> 'RESET_PASSWORD',
							"to_mail" 			=> $this->security->xss_clean($this->input->post('txtEmailId')),
							"subject" 			=> $subject,
							"body" 				=> $body,
							"status" 			=> $output,
							"created_by" 		=> $this->user_name,
							"created_at" 		=> $date
						);
						$result_mail = $this->db->insert('support_mail_record', $insert_mail);
						if ($result_mail) {
							$this->db->trans_commit();
							$dbstatus = TRUE;
							$dbmessage = 'A temporary password has been sent to the email address.Please check your Email';
						} else {
							$this->db->trans_rollback();
							$dbstatus = false;
							$dbmessage = 'Error While Sending';
						}
					} else {
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Sending';
					}
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Error While updating';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'delete_user':

				$this->form_validation->set_rules('user_code', 'User Code', 'required|xss_clean');

				if ($this->form_validation->run()) {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
					$new_data = array(
						"record_status" => 0,
						"updated_by" => $this->user_name
					);
					//print_r($new_data);die();
					$this->db->where('user_code', $this->security->xss_clean($this->input->post('user_code')));
					$deleteuser = $this->db->update('user_master', $new_data);
					if ($this->db->affected_rows() == 0) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}
				} else {
					$dbstatus = false;
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'get_role':
				$this->db->select('*');
				$this->db->from('role_master');
				$this->db->where("record_status", 1);
				$role_res = $this->db->get();
				$role_data = '';
				if ($role_res->num_rows() > 0) {
					$role_data = $role_res->result_array();
					foreach ($role_data as $key => $role) :
						$this->db->select('mm.menu_id,mm.resource_code,mm.menu_name as resource_name');
						$this->db->where("mm.role_code", $role['role_code']);
						$this->db->where("mm.resource_code", '#');
						$this->db->from('menu_master mm');
						$this->db->join("resource_master rm", "mm.resource_code = rm.resource_code", "Left");
						$parent_res = $this->db->get();
						//$parent_res = $this->db->get('menu_master');
						if ($parent_res->num_rows() > 0) {
							$parent_data = $parent_res->result_array();
							$role_data[$key]['parent_data'] = $parent_data;
						}
					endforeach;
				}
				return $role_data;
				break;
			case 'get_url_link':
				$output = array("aaData" => array());
				$this->db->select("resource_code,resource_name");
				$this->db->from("resource_master");
				$this->db->where("record_status", 1);
				$this->db->order_by("id");
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;
			case 'get_parent_menu':
				$output = array("aaData" => array());
				$role = $data['cmbRole'];
				$this->db->select("menu_id,resource_code");
				$this->db->from("menu_master");
				$this->db->where("record_status", 1);
				$this->db->where("role_code", $role);
				$this->db->where("parent_id", 0);
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;
			case 'get_menudata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir = $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('m1.menu_name'); //search filter will work on this column
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
				$iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->select("r.role_name,m1.menu_name,IFNULL(rm.resource_name,'#') AS resource_name,m1.parent_id,IFNULL(b.menu_name, '#') AS parent_name,
									m1.sl_no,
									CASE WHEN m1.has_child = 1 THEN 'Yes' 
									WHEN m1.has_child = 0 THEN 'No' 
									ELSE '' END AS has_child,
									CASE WHEN m1.is_last_child = 1 THEN 'Yes' 
									WHEN m1.is_last_child = 0 THEN 'No' 
									ELSE '' END AS is_last_child,m1.access_type,m1.icon_class,
									m1.menu_id,m1.role_code,m1.target,m1.resource_code");
				$this->db->from("role_master r,menu_master m1");
				$this->db->join("(SELECT menu_name,parent_id,menu_id, sl_no 
							FROM menu_master 
							WHERE record_status=1) b", "b.menu_id = m1.parent_id", "LEFT");
				$this->db->join("resource_master rm", "m1.resource_code = rm.resource_code", "LEFT");
				$this->db->where(" m1.role_code", $data['menu_role']);
				$this->db->where("m1.role_code = r.role_code");
				$this->db->order_by('b.sl_no', 'ASC');
				$this->db->where("m1.record_status", 1);
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				/*----FOR PAGINATION -----*/
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->select("r.role_name,m1.menu_name,IFNULL(rm.resource_name,'#') AS resource_name,m1.parent_id,IFNULL(b.menu_name, '#') AS parent_name,
									m1.sl_no,
									CASE WHEN m1.has_child = 1 THEN 'Yes' 
									WHEN m1.has_child = 0 THEN 'No' 
									ELSE '' END AS has_child,
									CASE WHEN m1.is_last_child = 1 THEN 'Yes' 
									WHEN m1.is_last_child = 0 THEN 'No' 
									ELSE '' END AS is_last_child,m1.access_type,m1.icon_class,
									m1.menu_id,m1.role_code,m1.target,m1.resource_code");
				$this->db->from("role_master r,menu_master m1");
				$this->db->join("(SELECT menu_name,parent_id,menu_id 
							FROM menu_master 
							WHERE record_status=1) b", "b.menu_id = m1.parent_id", "LEFT");
				$this->db->join("resource_master rm", "m1.resource_code = rm.resource_code", "LEFT");
				$this->db->where(" m1.role_code", $data['menu_role']);
				$this->db->where("m1.role_code = r.role_code");
				$this->db->where("m1.record_status", 1);

				$res1 = $this->db->get();

				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] = $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}
					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output; // Data Send to the controller then datatable
				break;
			case 'add_menu':
				$dbstatus = TRUE;
				$dbmessage = 'Data saved successfully';
				$linktext = ($this->input->post('txtmenulinktext') != null) ? $this->input->post('txtmenulinktext') : '';
				$role = ($this->input->post('cmbMenuRole') != null) ? $this->input->post('cmbMenuRole') : '';
				$resource = ($this->input->post('cmbMenuLinkURL') != null) ? $this->input->post('cmbMenuLinkURL') : '';
				$parent_id = ($this->input->post('cmbMenuParent') != null) ? $this->input->post('cmbMenuParent') : '';
				$sl_no = ($this->input->post('txtMenuslno') != null) ? $this->input->post('txtMenuslno') : '';
				$chkHasChild = ($this->input->post('txtHaschild') != null) ? $this->input->post('txtHaschild') : '';
				$chkIsLastChild = ($this->input->post('txtislastchild') != null) ? $this->input->post('txtislastchild') : '';
				$iconClass = ($this->input->post('txtIconClass') != null) ? $this->input->post('txtIconClass') : '';
				$menuAccess = ($this->input->post('cmbMenuAccess') != null) ? $this->input->post('cmbMenuAccess') : '';
				$target = ($this->input->post('txtNewWindow') != null) ? $this->input->post('txtNewWindow') : '';

				$hasChild = ($chkHasChild == "Yes") ? "1" : "0";
				$isLastChild = ($chkIsLastChild == "Yes") ? "1" : "0";
				if ($hasChild == 1) {
					$resource = '#';
				}
				$new_data = array(
					'menu_name' => $linktext,
					'role_code' => $role,
					'resource_code' => $resource,
					'parent_id' => $parent_id,
					'sl_no' => $sl_no,
					'has_child' => $hasChild,
					'is_last_child' => $isLastChild,
					'icon_class' => $iconClass,
					'access_type' => $menuAccess,
					'target' => $target,
					'created_by' => $this->user_name,
					'created_on' => 'NOW()'
				);
				if ($this->db->insert('menu_master', $new_data))
					return array('status' => $dbstatus, 'msg' => $dbmessage);
				else
					return array('status' => FALSE, 'msg' => 'Error While Saving');
				break;
			case 'edit_menu':
				$dbstatus = TRUE;
				$dbmessage = 'Data updated successfully';
				$linktext = ($this->input->post('txtmenulinktext') != null) ? $this->input->post('txtmenulinktext') : '';
				$role = ($this->input->post('cmbMenuRole') != null) ? $this->input->post('cmbMenuRole') : '';
				$resource = ($this->input->post('cmbMenuLinkURL') != null) ? $this->input->post('cmbMenuLinkURL') : '';
				$parent_id = ($this->input->post('cmbMenuParent') != null) ? $this->input->post('cmbMenuParent') : '';
				$sl_no = ($this->input->post('txtMenuslno') != null) ? $this->input->post('txtMenuslno') : '';
				$chkHasChild = ($this->input->post('txtHaschild') != null) ? $this->input->post('txtHaschild') : '';
				$chkIsLastChild = ($this->input->post('txtislastchild') != null) ? $this->input->post('txtislastchild') : '';
				$iconClass = ($this->input->post('txtIconClass') != null) ? $this->input->post('txtIconClass') : '';
				$menuAccess = ($this->input->post('cmbMenuAccess') != null) ? $this->input->post('cmbMenuAccess') : '';
				$target = ($this->input->post('txtNewWindow') != null) ? $this->input->post('txtNewWindow') : '';
				$hasChild = ($chkHasChild == "Yes") ? "1" : "0";
				$isLastChild = ($chkIsLastChild == "Yes") ? "1" : "0";
				if ($hasChild == 1) {
					$resource = '#';
				}
				$new_data = array(
					'menu_name' => $linktext,
					'role_code' => $role,
					'resource_code' => $resource,
					'parent_id' => $parent_id,
					'sl_no' => $sl_no,
					'has_child' => $hasChild,
					'is_last_child' => $isLastChild,
					'icon_class' => $iconClass,
					'access_type' => $menuAccess,
					'target' => $target,
					'updated_by' => $this->user_name,
					'updated_on' => 'NOW()'
				);
				$this->db->where('menu_id', $this->input->post('hidMenuId'));
				$this->db->update('menu_master', $new_data);
				if ($this->db->affected_rows())
					return array('status' => $dbstatus, 'msg' => $dbmessage);
				else
					return array('status' => 'FAILURE', 'msg' => 'Error while updating');
				break;
			case 'delete_menu':
				$dbstatus = TRUE;
				$dbmessage = 'Data deleted successfully';
				$data_delete = array('record_status' => 0);
				$this->db->where('menu_id', $this->input->post('menu_id'));
				$this->db->update('menu_master', $data_delete);
				if ($this->db->affected_rows())
					return array('status' => $dbstatus, 'msg' => $dbmessage);
				else
					return array('status' => 'FAILURE', 'msg' => 'Error while deleting');
				break;
			case 'copy_menu':
				$dbstatus = TRUE;
				$dbmessage = 'Data copied successfully';
				$cmbRole = ($this->input->post('cmbRole') != null) ? $this->input->post('cmbRole') : '';
				$cmbCopyRole = ($this->input->post('cmbCopyRole') != null) ? $this->input->post('cmbCopyRole') : '';
				$sql = "SELECT COUNT(menu_id) AS counting
						FROM 
						menu_master
						WHERE 
						role_code = '$cmbCopyRole'
						AND record_status = 1";
				$query = $this->db->query($sql);
				$result = $query->row_array();
				$counting = $result['counting'];
				if ($counting == 0) {
					$sql = "INSERT INTO menu_master 
					(role_code,menu_name,resource_code,parent_id,sl_no,has_child,
					is_last_child,icon_class,target,access_type,created_by,created_on)
					SELECT '$cmbCopyRole' AS role_code,menu_name,resource_code,parent_id,sl_no,has_child,
					is_last_child,icon_class,target,access_type,created_by,created_on FROM menu_master
					WHERE 
						record_status= 1 
						AND role_code = '$cmbRole'";
					$query = $this->db->query($sql);

					if ($this->db->affected_rows())
						return array('status' => $dbstatus, 'dbMessage' => $dbmessage);
					else
						return array('status' => 'FAILURE', 'dbMessage' => "Data already exists for this role. Please delete all the data to copy from other role.");
				}
				break;

			case 'get_resource':
				$output = array("aaData" => array());
				$this->db->select("resource_code,resource_name");
				$this->db->from("resource_master");
				$this->db->where("record_status", 1);
				$this->db->group_by("resource_code", 1);
				$this->db->order_by("id");
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;

			case 'get_roledata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('role_code', 'role_name', 'index_page_url');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->select('rom.`role_code`,rom.`role_name`, rem.resource_name  `index_page_url`, rem.resource_code  `index_page_code`');
				$this->db->from('role_master rom');
				$this->db->join('resource_master rem', 'rom.index_page_url = rem.resource_code', 'LEFT');
				$this->db->where('rom.`record_status`', 1);
				$this->db->where('rem.`record_status`', 1);
				$this->db->group_by('role_code');

				$res = $this->db->get();
				$query = $res->result_array();

				$output = array("aaData" => array());
				$header = array('role_code', 'role_name', 'index_page_url');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->select('rom.`role_code`,rom.`role_name`, rem.resource_name  `index_page_url`,rem.resource_code  `index_page_code`');
				$this->db->from('role_master rom');
				$this->db->join('resource_master rem', 'rom.index_page_url = rem.resource_code', 'LEFT');
				$this->db->where('rom.`record_status`', 1);
				$this->db->where('rem.`record_status`', 1);
				$this->db->group_by('role_code');
				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;

			case 'edit_role':
				$txtrolecode = $this->security->xss_clean($this->input->post('txtrolecode'));
				$txtroleName = $this->security->xss_clean($this->input->post('txtroleName'));
				$txtLandingPage = $this->security->xss_clean($this->input->post('txtLandingPage'));

				$output = array();
				$update_data = array(
					'role_name' 					=> $txtroleName,
					'index_page_url' 				=> $txtLandingPage,
					'created_by'					=> $this->user_name,
					'created_on'					=> $date,
					'updated_by'					=> $this->user_name,
					'updated_on' 					=> $date,
					'record_status'					=> 1
				);
				$this->db->where('role_code', $txtrolecode);
				$insert_user = $this->db->update('role_master', $update_data);

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data update successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'add_role':
				$txtrolecode = $this->security->xss_clean($this->input->post('txtrolecode'));
				$txtroleName = $this->security->xss_clean($this->input->post('txtroleName'));
				$txtLandingPage = $this->security->xss_clean($this->input->post('txtLandingPage'));

				$output = array();
				$insert_data = array(
					'role_code' 					=> $txtrolecode,
					'role_name' 					=> $txtroleName,
					'index_page_url' 				=> $txtLandingPage,
					'created_by'					=> $this->user_name,
					'created_on'					=> $date,
					'updated_by'					=> $this->user_name,
					'updated_on' 					=> $date,
					'record_status'					=> 1
				);
				$insert_user = $this->db->insert('role_master', $insert_data);
				if (!$insert_user) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data saved successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'delete_role':
				$rolecode = $this->security->xss_clean($this->input->post('rolecode'));
				$output = array();

				$this->db->where('role_code', $rolecode);
				$delete_user = $this->db->update('role_master', array('record_status' => 0, 'updated_by' => $this->user_name, 'updated_on' => $date));
				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting' . $this->db->_error_message();
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'get_resourcedata':

				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('resource_code', 'resource_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('resource_master');
				$this->db->select('resource_code,resource_link,resource_name,id,is_maintenance,record_status');
				$this->db->group_by('resource_code');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('resource_code', 'resource_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('resource_master');
				$this->db->select('resource_code,resource_link,resource_name,id,is_maintenance,record_status');
				$this->db->group_by('resource_code');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;


			case 'add_resource':
				$txtresourcelink = $this->security->xss_clean($this->input->post('txtresourcelink'));
				$txtresourceName = $this->security->xss_clean($this->input->post('txtresourceName'));
				$is_maintenance = $this->security->xss_clean($this->input->post('is_maintenance'));
				$record_status  = $this->security->xss_clean($this->input->post('resource_status'));

				$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM resource_master");
				$Sresult = $qry->result_array();
				$row2 = array_shift($Sresult);
				$txtresourcecode = "R" . $row2['max_id'] . rand(1000, 9999);

				$output = array();
				$insert_data = array(
					'resource_code' 				=> $txtresourcecode,
					'resource_link' 				=> $txtresourcelink,
					'resource_name' 				=> $txtresourceName,
					'is_maintenance' 				=> $is_maintenance,
					'record_status' 				=> $record_status,
					'created_by'					=> $this->user_name,
					'created_on'					=> $date,
					'updated_by'					=> $this->user_name,
					'updated_on' 					=> $date
				);
				$insert_user =  $this->db->insert('resource_master', $insert_data);
				if (!$insert_user) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data saved successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'edit_resource':
				$txtresourcecode = $this->security->xss_clean($this->input->post('resource_code'));
				$txtresourcelink = $this->security->xss_clean($this->input->post('txtresourcelink'));
				$txtresourceName = $this->security->xss_clean($this->input->post('txtresourceName'));
				$is_maintenance  = $this->security->xss_clean($this->input->post('is_maintenance'));
				$record_status   = $this->security->xss_clean($this->input->post('resource_status'));
				$output = array();

				$update_data = array(
					'resource_name' 				=> $txtresourceName,
					'resource_link' 				=> $txtresourcelink,
					'is_maintenance' 				=> $is_maintenance,
					'record_status' 				=> $record_status,
					'created_by'					=> $this->user_name,
					'created_on'					=> $date,
					'updated_by'					=> $this->user_name,
					'updated_on' 					=> $date
				);
				$this->db->where('resource_code', $txtresourcecode);
				$this->db->update('resource_master', $update_data);

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data Update successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'delete_resource':
				$resourcecode = $_POST['resourcecode'];
				$output = array();

				$this->db->where('resource_code', $resourcecode);
				$this->db->update('resource_master', array('record_status' => 0));

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting' . $this->db->_error_message();
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'get_groupdata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('gm.group_name', 'tblop.table_name', 'gm.group_code', 'tblop.table_code', 'gm.operation_tbl', 'gm.operation_col', 'gm.exicution_col');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('group_master as gm');
				$this->db->select('`gm.group_code`, `gm.group_name`,`tblop.table_name`,`tblop.table_code`,gm.operation_tbl,gm.operation_col,gm.exicution_col');
				$this->db->join('table_operation as tblop', 'gm.table_code = tblop.table_code', 'inner');
				$this->db->where('gm.record_status', 1);
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());



				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('group_master as gm');
				$this->db->select('`gm.group_code`, `gm.group_name`,`tblop.table_name`,`tblop.table_code`,gm.operation_tbl,gm.operation_col,gm.exicution_col');
				$this->db->join('table_operation as tblop', 'gm.table_code = tblop.table_code', 'inner');
				$this->db->where('gm.record_status', 1);

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'get_table':
				$result = $this->db->get('table_operation');
				return $result->result_array();
				break;
			case 'get_tablevalue':
				$table_code = $this->security->xss_clean($this->input->post('table_name'));
				$group_code = $this->security->xss_clean($this->input->post('group_code'));
				$this->db->from('table_operation');
				$this->db->select('`column_code`, `column_name`');
				$this->db->where('table_code', $table_code);
				$result = $this->db->get();
				foreach ($result->result_array() as $row) {
					$column_code = $row['column_code'];
					$column_name = $row['column_name'];
				}
				$output = array("optiondata" => array());
				$this->db->from($table_code);
				$this->db->select($column_code . " as 'code'," . $column_name . " as 'name'");
				$this->db->where('record_status', 1);
				$this->db->where($column_code . ' not in (SELECT map_value_code FROM group_mapping WHERE group_code = "' . $group_code . '")');
				$result1 = $this->db->get();
				$output['optiondata'] = $result1->result_array();
				return $output;
				break;
			case 'get_mapping_dept':
				$this->db->select('role_code,name');
				$this->db->where('role_group_code', $data['role_group_code']);
				$dept_mapping = $this->db->get('role_group_mapping');
				return $dept_mapping->result_array();
				break;
			case 'get_tablemapvalue':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('gmas.group_name', 'map_value_code');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				if ($this->input->post('group_code')) {
					$this->db->where('gmap.group_code', $this->input->post('group_code'));
				}
				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('group_mapping AS gmap');
				$this->db->select('gmas.group_name,map_value_code,gmap.id');
				$this->db->join('group_master AS gmas', 'gmap.group_code = gmas.group_code', 'inner');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());



				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				if ($this->input->post('group_code')) {
					$this->db->where('gmap.group_code', $this->input->post('group_code'));
				}
				$this->db->from('group_mapping AS gmap');
				$this->db->select('gmas.group_name,map_value_code,gmap.id');
				$this->db->join('group_master AS gmas', 'gmap.group_code = gmas.group_code', 'inner');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'delete_group':

				$dbstatus = TRUE;
				$dbmessage = 'Data deleted successfully';

				$group_code = $data['group_code'];
				$output = array();
				$new_data = array(
					'record_status'					=> 0
				);
				$this->db->where('group_code', $group_code);
				$this->db->update('group_master', $new_data);

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting' . $this->db->_error_message();
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;
			case 'delete_groupmapdata':
				$id = $data['id'];
				$output = array();
				$this->db->where('id', $id);
				$this->db->delete('group_mapping');
				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting' . $this->db->_error_message();
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;
			case 'add_group':
				$txtGroupName = $this->security->xss_clean($data['txtGroupName']);
				$cmbtable 	  = $this->security->xss_clean($data['cmbtable']);

				$cmbtablevalue = array();
				$cmbtablevalue = isset($data['cmbtablevalue']) ? $data['cmbtablevalue'] : [];
				if (in_array('multiselect-all', $cmbtablevalue)) {
					$index = array_search('multiselect-all', $cmbtablevalue);
					if ($index !== FALSE) {
						unset($cmbtablevalue[$index]);
						$cmbtablevalue = array_values($cmbtablevalue);
					}
				}
				$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM group_master");
				$Sresult = $qry->result_array();
				$row2 = array_shift($Sresult);
				$newid = "G" . $row2['max_id'] . rand(1000, 9999);
				$data = array(
					"group_code" => $newid,
					"group_name" => $this->security->xss_clean($this->input->post('txtGroupName')),
					"table_code" => $this->security->xss_clean($this->input->post('cmbtable')),
					"operation_tbl" => $this->security->xss_clean($this->input->post('txtoperTable')),
					"operation_col" => $this->security->xss_clean($this->input->post('txtoperColumn')),
					"exicution_col" => $this->security->xss_clean($this->input->post('txtExColumn')),
					"created_by" => $this->user_name,
					"created_on" => $date
				);
				$insert_group = $this->db->insert('group_master', $data);
				if (!$insert_group) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$newarray = array();
					$insert_group_map = TRUE;
					for ($i = 0; $i < sizeof($cmbtablevalue); $i++) {
						$newarray[] = array(
							'group_code' => $newid,
							'map_value_code' => $cmbtablevalue[$i],
							'created_by' => $this->user_name,
							'created_on' => date('Y-m-d H:i:s', now())
						);
					}
					if (sizeof($cmbtablevalue) != 0)
						$insert_group_map = $this->db->insert_batch('group_mapping', $newarray);
					if (!$insert_group_map) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					} else {
						$dbstatus = TRUE;
						$dbmessage = 'Data saved successfully';
					}
				}
				$output = array();
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;
			case 'edit_group':
				$txtGroupName = $this->security->xss_clean($data['txtGroupName']);
				$cmbtable 	  = $this->security->xss_clean($data['cmbtable']);
				$hidgroupcode 	  = $this->security->xss_clean($data['hidgroupcode']);
				$cmbtablevalue = array();
				$cmbtablevalue = isset($data['cmbtablevalue']) ? $data['cmbtablevalue'] : [];
				if (in_array('multiselect-all', $cmbtablevalue)) {
					$index = array_search('multiselect-all', $cmbtablevalue);
					if ($index !== FALSE) {
						unset($cmbtablevalue[$index]);
						$cmbtablevalue = array_values($cmbtablevalue);
					}
				}
				$data = array(
					"group_name" => $this->security->xss_clean($this->input->post('txtGroupName')),
					"operation_tbl" => $this->security->xss_clean($this->input->post('txtoperTable')),
					"operation_col" => $this->security->xss_clean($this->input->post('txtoperColumn')),
					"exicution_col" => $this->security->xss_clean($this->input->post('txtExColumn')),
					"updated_by" => $this->user_name,
					"updated_on" => $date
				);
				$this->db->where("group_code", $hidgroupcode);
				$insert_group = $this->db->update('group_master', $data);
				if (!$insert_group) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$newarray = array();
					$insert_group_map = TRUE;
					for ($i = 0; $i < sizeof($cmbtablevalue); $i++) {
						$newarray[] = array(
							'group_code' => $hidgroupcode,
							'map_value_code' => $cmbtablevalue[$i],
							'created_by' => $this->user_name,
							'created_on' => $date
						);
					}
					if (sizeof($cmbtablevalue) != 0)
						$insert_group_map = $this->db->insert_batch('group_mapping', $newarray);
					if (!$insert_group_map) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					} else {
						$dbstatus = TRUE;
						$dbmessage = 'Data update successfully';
					}
				}
				$output = array();
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;

			case 'get_group':

				$this->db->select("group_code,group_name");
				$this->db->from("group_master");
				$this->db->where("record_status", 1);
				$this->db->group_by("group_code,group_name");
				$this->db->order_by("id");
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;

			case 'get_role_group_data':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('rm.role_name', 'gm.group_name', 'rgm.name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->select('rgm.name,rm.role_name,gm.group_name,rm.role_code,gm.group_code,rgm.role_group_code');
				$this->db->from('role_group_mapping  rgm');
				$this->db->join('role_master rm', 'rgm.role_code = rm.role_code', 'LEFT');
				$this->db->join('group_master gm', 'rgm.group_code = gm.group_code', 'LEFT');
				$this->db->where('rgm.record_status', 1);
				$this->db->where('rm.record_status', 1);
				$this->db->where('gm.record_status', 1);
				$this->db->group_by('rgm.name,rm.role_name,gm.group_name,rm.role_code,gm.group_code,rgm.role_group_code');
				//$this->db->group_by('gm.group_code');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('rm.role_name', 'gm.group_name', 'rgm.name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$this->db->select('`rm.role_name`, `gm.group_name`,`rgm.name`,`rm.role_code`,`gm.group_code`');
				$this->db->from('role_group_mapping  rgm');
				$this->db->join('role_master rm', 'rgm.role_code = rm.role_code', 'LEFT');
				$this->db->join('group_master gm', 'rgm.group_code = gm.group_code', 'LEFT');
				$this->db->where('rgm.record_status', 1);
				$this->db->where('rm.record_status', 1);
				$this->db->where('gm.record_status', 1);
				$this->db->group_by('rgm.name,rm.role_name,gm.group_name,rm.role_code,gm.group_code,rgm.role_group_code');
				//$this->db->group_by('gm.group_code');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;

			case 'add_role_group':

				date_default_timezone_set('Asia/Kolkata');
				$date = date('Y-m-d H:i:s', time());

				$dbstatus = TRUE;
				$dbmessage = 'Data saved successfully';

				$role_code = $_POST['cmbrolecode'];
				$group_code = $_POST['cmbgroupcode'];
				$role_group_name = $_POST['txtRoleGroup'];

				$this->db->select('`role_code`, `group_code`');
				$this->db->from('role_group_mapping');
				$this->db->where('role_code', $role_code);
				$this->db->where('group_code', $group_code);
				$this->db->where('record_status', 1);
				$res = $this->db->get();
				$query = $res->result_array();

				if ($res->num_rows() > 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error Duplicate Record Entry';
				} else {
					$this->db->select_max('id');
					$this->db->from('role_group_mapping');
					$res = $this->db->get();
					$query = $res->result_array();

					foreach ($query as $aRow) {
						$max_id = $aRow['id'];
					}
					if ($max_id == '') {
						$max_id = 0;
					} else {
						$max_id = $max_id;
					}

					$role_group_code = $role_code . '_' . $group_code . $max_id;

					$output = array();
					$new_data = array(
						'role_group_code' 				=> $role_group_code,
						'name' 							=> $role_group_name,
						'role_code' 					=> $role_code,
						'group_code' 					=> $group_code,
						'created_by'					=> $this->user_name,
						'created_on'					=> $date,
						'updated_by'					=> $this->user_name,
						'updated_on' 					=> $date,
						'record_status'					=> 1
					);
					$insert_user =  $this->db->insert('role_group_mapping', $new_data);
					if (!$insert_user) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'edit_role_group':
				$hidtxtRole_group_code = $this->security->xss_clean($_POST['hidtxtRole_group_code']);
				$role_code = $this->security->xss_clean($_POST['cmbrolecode']);
				$group_code = $this->security->xss_clean($_POST['cmbgroupcode']);
				$role_group_name = $this->security->xss_clean($_POST['txtRoleGroup']);


				$hid_role_code = $this->security->xss_clean($_POST['hidtxtrolecode']);
				$hid_group_code = $this->security->xss_clean($_POST['hidtxtgroupcode']);

				$output = array();

				if ($hid_role_code == $role_code && $hid_group_code == $group_code) {
					$new_data = array(
						'name' 							=> $role_group_name,
						'created_by'					=> $this->user_name,
						'created_on'					=> $date,
						'updated_by'					=> $this->user_name,
						'updated_on' 					=> $date,
						'record_status'					=> 1
					);
					$this->db->where('role_group_code', $hidtxtRole_group_code);
					$update_data = $this->db->update('role_group_mapping', $new_data);
					if ($this->db->affected_rows() == 0) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}
				} else {
					$this->db->select('`role_code`, `group_code`');
					$this->db->from('role_group_mapping');
					$this->db->where('role_code', $role_code);
					$this->db->where('group_code', $group_code);
					$this->db->where('record_status', 1);
					$res = $this->db->get();
					$query = $res->result_array();

					if ($res->num_rows() > 0) {
						$dbstatus = FALSE;
						$dbmessage = 'Error Duplicate Record ';
					} else {
						$new_data = array(
							'name' 							=> $role_group_name,
							'role_code' 					=> $role_code,
							'group_code' 					=> $group_code,
							'created_by'					=> $this->user_name,
							'created_on'					=> $date,
							'updated_by'					=> $this->user_name,
							'updated_on' 					=> $date,
							'record_status'					=> 1
						);
						$this->db->where('role_group_code', $hidtxtRole_group_code);
						$update_data = $this->db->update('role_group_mapping', $new_data);
						if ($this->db->affected_rows() == 0) {
							$dbstatus = FALSE;
							$dbmessage = 'Error While Saving';
						} else {
							$dbstatus = TRUE;
							$dbmessage = 'Data saved successfully';
						}
					}
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'delete_role_group':
				$role_group_code = $_POST['rolegroupcode'];
				$output = array();
				$this->db->where('role_group_code', $role_group_code);
				$this->db->update('role_group_mapping', array('record_status' => 0));

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'get_rolegroup_code':
				$output = array("aaData" => array());
				$this->db->select("role_group_code,name");
				$this->db->from("role_group_mapping");
				$this->db->where("record_status", 1);
				$this->db->group_by("role_group_code,name");
				/*$this->db->order_by("id");*/
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;

			case 'get_user_code':
				$output = array("aaData" => array());
				$this->db->select("user_code,user_name");
				$this->db->from("user_master");
				$this->db->where("record_status", 1);
				$this->db->group_by("user_code", 1);
				$this->db->order_by("id");
				$result = $this->db->get();
				$output_data = $result->result_array();
				return $output_data;
				break;

			case 'get_user_role_group_data':

				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('urgp.`user_rolegroup_code', 'um.user_name', 'rgm.`name`');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				if ($this->input->post('cmbrolegroup')) {
					$this->db->where('urgp.role_group_code', $this->input->post('cmbrolegroup'));
				}
				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->select('um.`user_code`,rgm.`role_group_code`,um.user_name,rgm.`name`,urgp.`user_rolegroup_code`');
				$this->db->from('user_role_group_map urgp ');
				$this->db->join('user_master um', 'urgp.user_code = um.user_code', 'LEFT');
				$this->db->join('role_group_mapping rgm', 'urgp.role_group_code = rgm.role_group_code', 'LEFT');
				$this->db->where('rgm.record_status', 1);
				$this->db->where('um.record_status', 1);
				$this->db->where('urgp.record_status', 1);
				$this->db->group_by('um.`user_code`,rgm.`role_group_code`,um.user_name,rgm.`name`,urgp.`user_rolegroup_code`');
				//$this->db->group_by('um.user_code');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('urgp.`user_rolegroup_code', 'um.user_name', 'rgm.`name`');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				if ($this->input->post('cmbrolegroup')) {
					$this->db->where('urgp.role_group_code', $this->input->post('cmbrolegroup'));
				}
				$this->db->select('um.`user_code`,rgm.`role_group_code`,urgp.`user_rolegroup_code`,um.user_name,rgm.`name`');
				$this->db->from('user_role_group_map urgp ');
				$this->db->join('user_master um', 'urgp.user_code = um.user_code', 'LEFT');
				$this->db->join('role_group_mapping rgm', 'urgp.role_group_code = rgm.role_group_code', 'LEFT');
				$this->db->where('rgm.record_status', 1);
				$this->db->where('um.record_status', 1);
				$this->db->where('urgp.record_status', 1);
				$this->db->group_by('um.`user_code`,rgm.`role_group_code`,um.user_name,rgm.`name`,urgp.`user_rolegroup_code`');
				// $this->db->group_by('um.user_code');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;

			case 'add_user_role_group':
				$txtrole_group = $this->security->xss_clean($_POST['txtrole_group']);
				$cmbuser_name = $this->security->xss_clean($_POST['cmbuser_name']);
				if (in_array('multiselect-all', $cmbuser_name)) {
					$index = array_search('multiselect-all', $cmbuser_name);
					if ($index !== FALSE) {
						unset($cmbuser_name[$index]);
						$cmbuser_name = array_values($cmbuser_name);
					}
				}
				$newarray = array();
				$insert_user_role_group_map = TRUE;
				for ($i = 0; $i < sizeof($cmbuser_name); $i++) {
					$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_role_group_map");
					$Sresult = $qry->result_array();
					$row2 = array_shift($Sresult);
					if (!empty($data['cmb_department'])) {
						$exp_dept 		= explode("@", $data['cmb_department']);
						$dept_code     	= $exp_dept[0];
						$primary_role   = $exp_dept[1];

						$update_data = array('dept_code' => $dept_code, 'primary_role' => $primary_role);
						$this->db->where('user_code', $cmbuser_name[$i]);
						$this->db->update('user_master', $update_data);
					} else {
						$this->db->select('role_code');
						$this->db->where('role_group_code', $txtrole_group);
						$get_role = $this->db->get('role_group_mapping');
						$res_role = $get_role->result_array()[0];

						$update_data = array('primary_role' => $res_role['role_code']);
						$this->db->where('user_code', $cmbuser_name[$i]);
						$this->db->update('user_master', $update_data);
					}
					$newarray[] = array(
						'user_rolegroup_code' 		=> 'urg' . $row2['max_id'] . rand(100, 9999),
						'user_code' 					=> $cmbuser_name[$i],
						'role_group_code' 			=> $txtrole_group,
						'created_by'					=> $this->user_name,
						'created_on'					=> $date,
						'updated_by'					=> $this->user_name,
						'updated_on' 					=> $date,
						'record_status'				=> 1
					);
				}
				if (sizeof($cmbuser_name) != 0)
					$insert_user_role_group_map = $this->db->insert_batch('user_role_group_map', $newarray);
				if (!$insert_user_role_group_map) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data saved successfully';
				}

				$output = array('status' => $dbstatus, 'msg' => $dbmessage);
				return $output;
				break;

			case 'delete_user_rolegroup':
				$user_role_group_code = $data['userrolegroupcode'];
				$output = array();
				$this->db->where('user_rolegroup_code', $user_role_group_code);
				$this->db->delete('user_role_group_map');

				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;
			case 'delete_rolegroup':
				$rolegroupcode = $data['rolegroupcode'];
				$output = array();

				$this->db->where('role_group_code', $rolegroupcode);
				$this->db->update('role_group_mapping', array('record_status' => 0));
				if ($this->db->affected_rows() == 0) {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Deleting';
				} else {
					$dbstatus = TRUE;
					$dbmessage = 'Data deleted successfully';
				}
				$output = array('status' => $dbstatus, 'msg' => $dbmessage);

				return $output;
				break;
			case 'get_datatabledata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';

				$id = $this->group_data();
				//echo $id;

				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('`id`,`name`,`country`,`department`,`qualification`');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->select('id,name,country,department,qualification');
				$this->db->from('datatable');
				//$this->db->join('datatable');
				//$this->db->where_in('id',$id);	
				$this->db->where('id in (' . $id . ')');

				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('`id`,`name`,`country`,`department`,`qualification`');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$this->db->select('id,name,country,department,qualification');
				$this->db->from('datatable');
				$this->db->where('id in (' . $id . ')');


				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'get_Organisationdata':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir = $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('org_name', 'org_display_name'); //search filter will work on this column
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
				$iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('org_master');
				$this->db->select("org_code,org_name,org_display_name,website_address,logo_url,location");

				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				/*----FOR PAGINATION-----*/
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$this->db->from('org_master');
				$this->db->select("org_code,org_name,org_display_name,website_address,logo_url,location");
				$res1 = $this->db->get();

				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] = $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}
					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'op_type_register':
				$user_display_name = $this->input->post('txtFirstname') . " " . $this->input->post('txtLastname');
				$dbstatus = TRUE;
				$dbmessage = 'Registered successfully';

				$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_master");
				$Sresult = $qry->result_array();
				$row2 = array_shift($Sresult);
				$user_code = $row2['max_id'] . rand(1000, 99999);
				$data1 = array(
					"user_code" => $user_code,
					"user_name" => $this->input->post('txtUsername'),
					"user_display_name" => $user_display_name,
					"password" => $this->input->post('secreatecode'),
					"record_status" => 1,
					"created_by" => $this->user_name,
					"created_on" => date('Y-m-d H:i:s', now())
				);
				$insert_user = $this->db->insert('user_master', $data1);
				if ($insert_user) {
					$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_role_group_map");
					$Sresult = $qry->result_array();
					$row2 = array_shift($Sresult);
					$user_rolegroup_code = 'urg' . $row2['max_id'] . rand(100, 9999);

					$data2 = array(
						"user_rolegroup_code" => $user_rolegroup_code,
						"user_code" => $user_code,
						"role_group_code" => $this->input->post('txtRoleGroupCode'),
						"record_status" => 1,
						"created_by" => 'USER',
						"created_on" => date('Y-m-d H:i:s', now())
					);
					$insert_role = $this->db->insert('user_role_group_map', $data2);
					if ($insert_role) {
						return array('status' => $dbstatus, 'msg' => $dbmessage);
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}
				} else {
					$dbstatus = FALSE;
					$dbmessage = 'Error While Saving';
				}

				break;
			case 'op_type_mail_link':
				try {

					$emailId 	= $this->security->xss_clean($this->input->post('txtEmailId'));
					$Username  	= $this->security->xss_clean($this->input->post('txtUsername'));
					$txt_captcha = $this->security->xss_clean($this->input->post('txt_captcha'));

					if ($this->session->userdata('captchaword') == $txt_captcha) {
						// Unset captcha session data
						$this->session->unset_userdata('captchaword');

						$this->db->select('user_code,email,user_name,user_display_name,password');
						$this->db->from('user_master');
						$this->db->where('record_status', 1);
						$this->db->where('email', $emailId);
						$this->db->where('user_name', $Username);

						$res    = $this->db->get();
						$result = $res->row_array();

						if ($res->num_rows() > 0) {
							$user_code 			= $result['user_code'];
							$user_name 			= $result['user_name'];
							$email 				= $result['email'];
							$user_display_name 	= $result['user_display_name'];
							$password 			= $result['password'];

							$token = generateJWT($user_code, $user_name, $password);

							$insert_data = array(
								"user_name" 	=> $Username,
								"email_id" 		=> $emailId,
								"ip_address" 	=> $this->input->ip_address(),
								"status"		=> 'SUCCESS',
								"created_by" 	=> $Username,
								"created_on" 	=> date('Y-m-d H:i:s', now())
							);
							$insert_forgot_password_history = $this->db->insert('forgot_password_history', $insert_data);

							$this->db->where('record_status', 1);
							$res = $this->db->get('email_provider_setup');
							$email_rec = $res->result_array()[0];

							$actual_link = base_url() . 'user/reset_password/' . $token;


							$link = "<a href='$actual_link'>Click on this link</a>";
							$toMail1  = $email;
							$ccMail2  = "";
							$bccMail3  = "";
							$subject  = "Forgot Password";
							$body    = "Dear " . $user_display_name . ",<br>
								<p style='margin-left:100px'>Before we get started, we would like to verify your mail id and create your individual password.</p> 
								<p>" . $link . " to confirm your mail id. Through this link you can create your password.</p>";
							$output = sendEmail($toMail1, $ccMail2, $bccMail3, $subject, $body);

							if ($output['status']) {
								$dbstatus = TRUE;
								$dbmessage = 'Confirmation email has been sent to your registered email id, 
				            	please check your email id by clicking on the link and create new password';
							} else {
								$dbStatus = FALSE;
								$dbMessage = 'Unable to sent Mail. Please Contact for Support';
							}
						} else {
							$insert_data = array(
								"user_name" 	=> $Username,
								"email_id" 		=> $emailId,
								"ip_address" 	=> $this->input->ip_address(),
								"status"		=> 'FAILED',
								"created_by" 	=> $Username,
								"created_on" 	=> date('Y-m-d H:i:s', now())
							);
							$insert_forgot_password_history = $this->db->insert('forgot_password_history', $insert_data);

							$dbstatus = FALSE;
							$dbmessage = 'Email id not registered. Please check your mail id or contact to system admin';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Invalid Captcha.';
					}
				} catch (Exception $e) {
					$dbstatus = FALSE;
					$dbmessage = 'Failed';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'get_password':
				try {
					$token = $data;
					$token_parts = explode(".", $token);
					$token_obj = json_decode(base64_decode($token_parts[1]), true);
					$pass = '';
					$this->db->select('password');
					$this->db->from('user_master');
					$this->db->where('user_code', $token_obj["data"]["id"]);
					$this->db->where('record_status', 1);
					$result = $this->db->get();
					$passarray =  $result->result_array();
					foreach ($passarray as $row) {
						$pass = $row['password'];
					}

					$jwt_array = (array)JWT::decode($token, $pass, array('HS512'));
					$id = $jwt_array['data']->id;
					$ins1 = $jwt_array['data']->ins;
					$output = array('ins' => $ins1, 'id' => $id, 'status' => 'success');
				} catch (Exception $e) {
					$output = array('status' => 'failed');
				}
				return $output;
				break;
			case 'op_type_reset_password':
				$this->db->trans_begin();
				try {

					// Check if password is matched with previous passwords.

					// By default set it to false
					$reset_log_password_exist = false;

					// Get data from front end
					$txtUserCode = $this->security->xss_clean($this->input->post('txtUserCode'));
					$new_password = $this->security->xss_clean($this->input->post('txtPass'));

					// Get data from log reset password history
					$log_reset_query = $this->db->select('*')->from('log_reset_password_history')->where(['user_code' => $txtUserCode])->order_by('id', 'DESC')->limit(4)->get();

					// Check if any result found in table
					if ($log_reset_query && $log_reset_query->num_rows() > 0) {
						$log_reset_results = $log_reset_query->result_array();

						foreach ($log_reset_results as $log) {
							if ($log['password'] == $new_password) {
								$reset_log_password_exist = true;
							}
						}
					}

					// If yes then do not update password
					if ($reset_log_password_exist) {
						$dbstatus = FALSE;
						$dbmessage = 'Your new password should not be same with prevoius passwords. Try new password.';
						return array('status' => $dbstatus, 'msg' => $dbmessage);
					}
					// Else update password
					else {
						$data = array(
							"password" => $new_password,
							"updated_by" => 'USER',
							"updated_on" => date('Y-m-d H:i:s', now())
						);
						$this->db->where('user_code', $txtUserCode);
						$update_user = $this->db->update('user_master', $data);

						$this->db->where('user_code', $this->input->post('txtUserCode'));
						$this->db->select('primary_role');
						$res = $this->db->get('user_master');
						$role_code = $res->result_array()[0];

						if ($update_user) {
							$log_rp = array(
								"role_code" 	=> 	$role_code['primary_role'],
								"user_code"		=>	$this->input->post('txtUserCode'),
								"ip_address"	=>	$this->input->ip_address(),
								"password"		=>	$this->input->post('txtPassword'),
								"created_by"	=>  $this->user_name,
								"last_attempt"  =>  $date,
								"status"		=>  1
							);
							$insert_log_rp = $this->db->insert('log_reset_password_history', $log_rp);
							if ($insert_log_rp) {
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Your password has been reset successfully!';
							} else {
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'error while reset password';
							}
						} else {
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Error While Reset';
						}
					}
				} catch (Exception $e) {
					$this->db->trans_rollback();
					return array('status' => false, 'msg' => $e->getMessage());
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'op_type_set_password':
				$this->db->trans_begin();
				try {
					$data = array(
						"password" => $this->input->post('txtPassword'),
						"is_new_record" => 1,
						"updated_by" => 'USER',
						"updated_on" => $date
					);
					$this->db->where('user_code', $this->input->post('txtUserCode'));
					$update_user = $this->db->update('user_master', $data);
					if ($update_user) {
						$log_rp = array(
							"role_code" 	=> 	$this->session->userdata('txtUserName'),
							"user_code"		=>	$this->input->post('txtUserCode'),
							"ip_address"	=>	$this->input->ip_address(),
							"password"		=>	$this->input->post('txtPassword'),
							"created_by"	=>  $this->user_name,
							"last_attempt"  =>  $date,
							"status"		=>  1
						);
						$insert_log_rp = $this->db->insert('log_reset_password_history', $log_rp);
						if ($insert_log_rp) {
							$this->db->trans_commit();
							$dbstatus = TRUE;
							$dbmessage = 'Your password has been reset successfully!';
						} else {
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Your password has been not reset';
						}
					} else {
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Reset';
					}
				} catch (Exception $e) {
					$this->db->trans_rollback();
					return array('status' => false, 'msg' => $e->getMessage());
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'logindetails':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$page = $data['page'];
				if ($page ==  'ALL') {
					$this->db->from('login_detail a');
					$this->db->select("b.user_display_name, c.role_name,a.ip_address,DATE_FORMAT(a.created_on,'%d-%m-%Y %H:%i:%s %p') AS created_on, a.message,b.phone_number, b.job_title, b.email, a.record_status");
					$this->db->join('user_master b', 'a.login_id = b.user_code', 'left');
					$this->db->join('role_master c', 'a.login_role = c.role_code', 'left');
					$this->db->order_by('a.id', 'DESC');
					$res = $this->db->get();
					$query = $res->result_array();
				} else {
					$order = $this->input->post('order');
					if ($order) {
						foreach ($order as $row) {
							$Ocolumn = $row['column'];
							$Odir = $row['dir'];
						}
						$this->db->order_by($Ocolumn, $Odir);
					} else {
						$this->db->order_by('a.id', "DESC");
					}
					$search = $this->input->post('search');
					$header = array('user_display_name', 'role_name', 'ip_address', 'created_on'); //search filter will work on this column
					if ($search['value'] != '') {
						for ($i = 0; $i < count($header); $i++) {
							$this->db->or_like($header[$i], $search['value']);
						}
					}

					$iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
					$iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

					$this->db->limit($iDisplayLength, $iDisplayStart);
					$this->db->from('login_detail a');
					$this->db->select("b.user_display_name,c.role_name,a.ip_address,DATE_FORMAT(a.created_on,'%d-%M-%Y , %H:%i:%s %p') AS created_on , a.message, b.phone_number, b.job_title, b.email, a.record_status");
					$this->db->join('user_master b', 'a.login_id = b.user_code', 'left');
					$this->db->join('role_master c', 'a.login_role = c.role_code', 'left');
					$this->db->order_by('a.id', 'DESC');
					$res = $this->db->get();
					$query = $res->result_array();
					$output = array("aaData" => array());
					/*----FOR PAGINATION-----*/
					if ($search['value'] != '') {
						for ($i = 0; $i < count($header); $i++) {
							$this->db->or_like($header[$i], $search['value']);
						}
					}
					$this->db->from('login_detail a');
					$this->db->select("b.user_display_name,c.role_name, ,a.ip_address,DATE_FORMAT(a.created_on,'%d-%M-%Y , %H:%i:%s %p') AS created_on, a.message, b.phone_number, b.job_title, b.email, a.record_status");
					$this->db->join('user_master b', 'a.login_id = b.user_code', 'left');
					$this->db->join('role_master c', 'a.login_role = c.role_code', 'left');
					$this->db->order_by('a.id', 'DESC');
					$res1 = $this->db->get();

					$output["draw"] = intval($this->input->post('draw'));
					$output['iTotalRecords'] = $res1->num_rows();
					$output['iTotalDisplayRecords'] = $res1->num_rows();
				}
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}
					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'OPERATION_CHANGE_PASSWORD':
				try {
					$this->db->select('user_code,password');
					$this->db->from('user_master');
					$this->db->where('record_status', 1);
					$this->db->where('user_code', $this->security->xss_clean($this->input->post('hid_user_code_cp')));
					$result = $this->db->get();
					$res = $result->result_array()[0];

					if ($this->security->xss_clean($data['hid_old_password']) == $this->security->xss_clean($res['password'])) {

						// Check if password is matched with previous passwords.

						// By default set it to false
						$reset_log_password_exist = false;

						// Get data from front end
						$hid_user_code_cp = $this->security->xss_clean($this->input->post('hid_user_code_cp'));
						$hid_old_password = $this->security->xss_clean($this->input->post('hid_old_password'));
						$hid_password = $this->security->xss_clean($this->input->post('hid_password'));

						// Get data from log reset password history
						$log_reset_query = $this->db->select('*')->from('log_reset_password_history')->where(['user_code' => $hid_user_code_cp])->order_by('id', 'DESC')->limit(4)->get();

						// Check if any result found in table
						if ($log_reset_query && $log_reset_query->num_rows() > 0) {
							$log_reset_results = $log_reset_query->result_array();

							foreach ($log_reset_results as $log) {
								if ($log['password'] == $hid_password) {
									$reset_log_password_exist = true;
								}
							}
						}

						// If yes then do not update password
						if ($reset_log_password_exist) {
							$dbstatus = FALSE;
							$dbmessage = 'Your new password should not be same with prevoius passwords. Try new password.';
							return array('status' => $dbstatus, 'msg' => $dbmessage);
						}
						// Else update password
						else {
							$this->db->trans_begin();
							$data = array(
								"password" 		=> $this->security->xss_clean($this->input->post('hid_password')),
								"updated_by" 	=> $this->security->xss_clean($this->input->post('hid_user_name')),
								"updated_on" 	=> $date,
								"record_status" => 1
							);

							$this->db->where('user_code', $this->security->xss_clean($this->input->post('hid_user_code_cp')));
							$update_user_master = $this->db->update('user_master', $data);

							if ($update_user_master) {
								$log_rp = array(
									"role_code" 	=> 	$this->security->xss_clean($this->session->userdata('role')),
									"user_code"		=>	$this->security->xss_clean($this->input->post('hid_user_code_cp')),
									"ip_address"	=>	$this->input->ip_address(),
									"password"		=>	$this->security->xss_clean($this->input->post('hid_password')),
									"created_by"	=>  $this->security->xss_clean($this->input->post('hid_user_name')),
									"last_attempt"  =>  $date,
									"status"		=>  1
								);
								$insert_log_rp = $this->db->insert('log_reset_password_history', $log_rp);
								if ($insert_log_rp) {
									$this->db->trans_commit();
									$dbstatus = TRUE;
									$dbmessage = 'Your password has been reset successfully!';
								} else {
									$this->db->trans_rollback();
									$dbstatus = FALSE;
									$dbmessage = 'Something went wrong. Please try again.';
								}
							} else {
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error password update';
							}
						}
					} else {
						$dbstatus 	= false;
						$dbmessage 	= 'The old password you have entered is incorrect';
					}
				} catch (Exception $e) {
					$dbstatus = FALSE;
					$dbmessage = 'Something went wrong. Please try again or contact support.';
					return array('status' => $dbstatus, 'msg' => $dbmessage);
				}

				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'get_title':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir = $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('title_name'); //search filter will work on this column
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length'); //to shw number of record to be shown
				$iDisplayStart = $this->input->post('start'); //to start from that position (ex: offset)

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('title_setup');
				$this->db->select("title_name,title_desc,title_image,status,title_id");
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				/*----FOR PAGINATION-----*/
				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('title_setup');
				$this->db->select("title_name,title_desc,title_image,status,title_id");
				$res1 = $this->db->get();

				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] = $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}
					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'add_title_setup':
				if (empty($_FILES['txt_title_img']['name'])) {
					$store_path  = 'public/upload/title/defult.png';
					$insert_title = array(
						"title_name" 	=> $this->security->xss_clean($this->input->post('txt_title_name')),
						"title_image" 	=> $this->security->xss_clean($store_path),
						"title_desc" 	=> $this->security->xss_clean($this->input->post('txt_desc')),
						"created_by" 	=> $this->user_name,
						"created_on" 	=> $date,
						"status" 		=> $this->security->xss_clean($this->input->post('title_status'))
					);
					$insert_title_setup = $this->db->insert('title_setup', $insert_title);
					if (!$insert_title_setup) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					} else {
						$dbstatus = TRUE;
						$dbmessage = 'insert successfully';
					}
				} else {
					$allowed_mime_type_arr = array('image/jpg', 'image/png', 'image/jpeg,image/x-icon');
					$mime = get_mime_by_extension($_FILES['txt_title_img']['name']);
					$dot_count 	= substr_count($_FILES['txt_title_img']['name'], '.');
					$zero_count = substr_count($_FILES['txt_title_img']['name'], "%0");

					if (in_array($mime, $allowed_mime_type_arr)) {
						if ($zero_count == 0 && $dot_count == 1) {
							$file_move_path  = FCPATH . 'public/upload/title';
							if (!is_dir($file_move_path))
								mkdir($file_move_path, 0777, true);

							$config['upload_path'] 		= $file_move_path;
							$config['file_name'] 		= $_FILES['txt_title_img']['name'];
							$config['allowed_types'] 	= 'jpg|png|jpeg';
							$config['max_size']     	= 1024; //sizein KB form
							$config['overwrite'] 		= TRUE;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('txt_title_img')) {
								$data = array('upload_data' => $this->upload->data());
								$store_path  = 'public/upload/title/' . $data['upload_data']['file_name'];

								$insert_title = array(
									"title_name" 	=> $this->security->xss_clean($this->input->post('txt_title_name')),
									"title_image" 	=> $this->security->xss_clean($store_path),
									"title_desc" 	=> $this->security->xss_clean($this->input->post('txt_desc')),
									"created_by" 	=> $this->user_name,
									"created_on" 	=> $date,
									"status" 		=> $this->security->xss_clean($this->input->post('title_status'))
								);
								$insert_title_setup = $this->db->insert('title_setup', $insert_title);
								if (!$insert_title_setup) {
									$dbstatus = FALSE;
									$dbmessage = 'Error While Saving';
								} else {
									$dbstatus = TRUE;
									$dbmessage = 'insert successfully';
								}
							} else {
								$dbstatus = FALSE;
								$dbmessage = $this->upload->display_errors();
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Please select only jpg/jpeg/png format.';
					}
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			case 'edit_title_setup':
				if (empty($_FILES['txt_title_img']['name'])) {
					$store_path  = 'public/upload/title/defult.png';
					$update_title = array(
						"title_name" 	=> $this->security->xss_clean($this->input->post('txt_title_name')),
						"title_image" 	=> $this->security->xss_clean($store_path),
						"title_desc" 	=> $this->security->xss_clean($this->input->post('txt_desc')),
						"created_by" 	=> $this->user_name,
						"created_on" 	=> $date,
						"status" 		=> $this->security->xss_clean($this->input->post('title_status'))
					);
					$this->db->where('title_id', $this->security->xss_clean($this->input->post('title_code')));
					$update_title_setup = $this->db->update('title_setup', $update_title);
					if (!$update_title_setup) {
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					} else {
						$dbstatus = TRUE;
						$dbmessage = 'update successfully';
					}
				} else {
					$allowed_mime_type_arr = array('image/jpg', 'image/png', 'image/jpeg,image/x-icon');
					$mime = get_mime_by_extension($_FILES['txt_title_img']['name']);
					$dot_count 	= substr_count($_FILES['txt_title_img']['name'], '.');
					$zero_count = substr_count($_FILES['txt_title_img']['name'], "%0");

					if (in_array($mime, $allowed_mime_type_arr)) {
						if ($zero_count == 0 && $dot_count == 1) {
							$file_move_path  = FCPATH . 'public/upload/title';
							if (!is_dir($file_move_path))
								mkdir($file_move_path, 0777, true);

							$config['upload_path'] 		= $file_move_path;
							$config['file_name'] 		= $_FILES['txt_title_img']['name'];
							$config['allowed_types'] 	= 'jpg|png|jpeg';
							$config['max_size']     	= 1024; //sizein KB form
							$config['overwrite'] 		= TRUE;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('txt_title_img')) {
								$data = array('upload_data' => $this->upload->data());
								$store_path  = 'public/upload/title/' . $data['upload_data']['file_name'];

								$update_title = array(
									"title_name" 	=> $this->security->xss_clean($this->input->post('txt_title_name')),
									"title_image" 	=> $this->security->xss_clean($store_path),
									"title_desc" 	=> $this->security->xss_clean($this->input->post('txt_desc')),
									"created_by" 	=> $this->user_name,
									"created_on" 	=> $date,
									"status" 		=> $this->security->xss_clean($this->input->post('title_status'))
								);
								$this->db->where('title_id', $this->security->xss_clean($this->input->post('title_code')));
								$update_title_setup = $this->db->update('title_setup', $update_title);
								if (!$update_title_setup) {
									$dbstatus = FALSE;
									$dbmessage = 'Error While Saving';
								} else {
									$dbstatus = TRUE;
									$dbmessage = 'update successfully';
								}
							} else {
								$dbstatus = FALSE;
								$dbmessage = $this->upload->display_errors();
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'Invalid Image format.It should not contain multiple dot(.) or 0)';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'Please select only jpg/jpeg/png format.';
					}
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'get_controller':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('controller_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('controller_master');
				$this->db->select('controller_code,controller_name,created_on');
				$this->db->order_by('id', 'DESC');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('controller_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('controller_master');
				$this->db->select('controller_code,controller_name,created_on');
				$this->db->order_by('id', 'DESC');
				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'add_controller':
				$this->db->trans_begin();
				try {
					$this->db->select('controller_code,controller_name');
					$this->db->from('controller_master');
					$res_controller = $this->db->get();
					$result = $res_controller->result_array();
					foreach ($result as $res) :
						$controller_code = $res['controller_code'];
						$controller_name = $res['controller_name'];
					endforeach;

					if ($controller_code != $this->security->xss_clean($this->input->post('txt_controller_code'))) {
						if ($controller_name != $this->security->xss_clean($this->input->post('txt_controller_name'))) {
							$data = array(
								"controller_code" 	=> $this->security->xss_clean($this->input->post('txt_controller_code')),
								"controller_name" 	=> $this->security->xss_clean($this->input->post('txt_controller_name')),
								"created_by" 		=> $this->user_name,
								"created_on" 		=> $date,
								"status" 			=>	1
							);
							$insert_controller = $this->db->insert('controller_master', $data);
							if (!$insert_controller) {
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							} else {
								$this->db->trans_commit();
								$this->load->helper('pages_creator');
								create_controller_page($this->input->post('txt_controller_name'));
								$dbstatus = TRUE;
								$dbmessage = 'page create successfully';
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'controller name already exists';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'controller code already exists';
					}
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$dbstatus = FALSE;
					$dbmessage = $e->getMessage();
				}

				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'model_data':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('model_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('model_master');
				$this->db->select('model_code,model_name,created_on');
				$this->db->order_by('id', 'DESC');

				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('controller_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('model_master');
				$this->db->select('model_code,model_name,created_on');
				$this->db->order_by('id', 'DESC');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;

			case 'add_model':
				$this->db->trans_begin();
				try {
					$this->db->select('model_code,model_name');
					$this->db->from('model_master');
					$res_controller = $this->db->get();
					$result = $res_controller->result_array();
					foreach ($result as $res) :
						$model_code = $res['model_code'];
						$model_name = $res['model_name'];
					endforeach;

					if ($model_code != $this->security->xss_clean($this->input->post('txt_model_code'))) {
						if ($model_name != $this->security->xss_clean($this->input->post('txtModelName'))) {
							$data = array(
								"model_code" 	=> $this->security->xss_clean($this->input->post('txt_model_code')),
								"model_name" 	=> $this->security->xss_clean($this->input->post('txtModelName')),
								"created_by" 		=> $this->user_name,
								"created_on" 		=> $date,
								"status" 			=>	1
							);
							$insert_model = $this->db->insert('model_master', $data);
							if (!$insert_model) {
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							} else {
								$this->db->trans_commit();
								$this->load->helper('pages_creator');
								create_model_page($this->security->xss_clean($this->input->post('txtModelName')));
								$dbstatus = TRUE;
								$dbmessage = 'page create successfully';
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'model name already exists';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'model code already exists';
					}
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$dbstatus = FALSE;
					$dbmessage = $e->getMessage();
				}

				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;
			case 'view_data':
				$order = '';
				$Ocolumn = '';
				$Odir = '';
				$order = $this->input->post('order');
				if ($order) {
					foreach ($order as $row) {
						$Ocolumn = $row['column'];
						$Odir =  $row['dir'];
					}
					$this->db->order_by($Ocolumn, $Odir);
				} else {
					$this->db->order_by(1, "ASC");
				}
				$search = $this->input->post('search');
				$header = array('model_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}

				$iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');

				$this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('view_master');
				$this->db->select('view_code,view_name,view_path,created_on');
				$this->db->order_by('id', 'DESC');

				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());

				$header = array('controller_name');

				if ($search['value'] != '') {
					for ($i = 0; $i < count($header); $i++) {
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				$this->db->from('view_master');
				$this->db->select('view_code,view_name,view_path,created_on');
				$this->db->order_by('id', 'DESC');

				$res1 = $this->db->get();
				$output["draw"] = intval($this->input->post('draw'));
				$output['iTotalRecords'] = $res1->num_rows();
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach ($query as $aRow) {
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach ($aRow as $key => $value) {

						$row[$i] = $value;
						$row[$key] = $value;
						$i++;
					}

					$output['aaData'][] = $row;
					$slno++;
					unset($row);
				}
				return $output;
				break;
			case 'GET_VIEW_FOLDER_DETAILS':
				$this->db->distinct('view_path');
				$this->db->select('view_path');
				$this->db->where('status', '1');
				$res = $this->db->get('view_master');
				return $res->result_array();
				break;
			case 'add_view':
				$this->db->trans_begin();
				try {
					$this->db->select('view_code,view_name');
					$this->db->from('view_master');
					$res_controller = $this->db->get();
					$result = $res_controller->result_array();
					foreach ($result as $res) :
						$view_code = $res['view_code'];
						$view_name = $res['view_name'];
					endforeach;

					if ($view_code != $this->security->xss_clean($this->input->post('txt_view_code'))) {
						if ($view_name != $this->security->xss_clean($this->input->post('txt_view_name'))) {
							$data = array(
								"view_code" 	=> $this->security->xss_clean($this->input->post('txt_view_code')),
								"view_name" 	=> $this->security->xss_clean($this->input->post('txt_view_name')),
								"view_path" 	=> $this->security->xss_clean($this->input->post('cmb_view_path')),
								"created_by" 	=> $this->user_name,
								"created_on" 	=> $date,
								"status" 		=>	1
							);
							$insert_model = $this->db->insert('view_master', $data);
							if (!$insert_model) {
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							} else {
								$this->db->trans_commit();
								$this->load->helper('pages_creator');
								create_view_page($this->security->xss_clean($this->input->post('txt_view_name')), $this->security->xss_clean($this->input->post('cmb_view_path')));
								$dbstatus = TRUE;
								$dbmessage = 'View create successfully';
							}
						} else {
							$dbstatus = FALSE;
							$dbmessage = 'model name already exists';
						}
					} else {
						$dbstatus = FALSE;
						$dbmessage = 'model code already exists';
					}
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$dbstatus = FALSE;
					$dbmessage = $e->getMessage();
				}

				return array('status' => $dbstatus, 'msg' => $dbmessage);
				break;

			default:
				return array('status' => FALSE, 'msg' => 'Unable to load.Contact Support');
		}
	}
}
