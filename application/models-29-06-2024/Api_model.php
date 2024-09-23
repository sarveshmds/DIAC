<?php

class Api_model extends CI_model {

	function login($user_type, $email = '', $mobile_number = ''){
		$flag = false;
		if($user_type == 'CLAIMANT_RESPONDENT'){
			$this->db->select('id, name, count_number, contact as phone, email');
			$this->db->from('cs_claimant_respondant_details_tbl as crd');
			$this->db->where('status', 1);
			$this->db->where('(email ="'.$email.'" OR contact="'.$mobile_number.'")');
			$q = $this->db->get();
			if($q->num_rows() > 0){
				$res = $q->row();
				$flag = true;
			}
		}
		if($user_type == 'COUNSEL'){
			$this->db->select('id, name, phone, email');
			$this->db->from('cs_counsels_tbl');
			$this->db->where('status', 1);
			$this->db->where('(email ="'.$email.'" OR phone="'.$mobile_number.'")');
			$q = $this->db->get();
			if($q->num_rows() > 0){
				$res = $q->row();
				$flag = true;
			}
		}
		if($user_type == 'ARBITRATOR'){
			$this->db->select('id, name_of_arbitrator as name, phone, email');
			$this->db->from('cs_arbitral_tribunal_tbl');
			$this->db->where('status', 1);
			$this->db->where('(email ="'.$email.'" OR phone="'.$mobile_number.'")');
			$q = $this->db->get();
			if($q->num_rows() > 0){
				$res = $q->row();
				$flag = true;
			}
		}

		if($flag == true){
			$otp = rand(99999, 999999);

			$data = [
				'otp' => $otp,
				'expiration_time' => date("Y-m-d H:i:s", strtotime('+2 hours')),
				'email' => $email,
				'phone_number' => $mobile_number,
				'status' => 1,
				'created_by' => ($email)?$email:$mobile_number 
			];
			// Store otp in database
			if($this->db->insert('cs_otp_verification_tbl', $data)){
				// Send the otp
				// If email id is provided
				if($email){
					$to_email = $email;
					$subject = 'One time password for login in DIAC mobile app.';
					$body = '<b>Hello,</b>
					<p>Below is your one time password for log in.</p>
					<p>OTP: <b>'.$otp.'</b></p>
					<p>This otp is only valid for 2 hours.</p>
					<p>Thanks & Regards</p>
					<p>Delhi International Arbitration Center,</p>
					<p>New Delhi</p>
					';
					$this->load->helper('send_email');
					$result = sendEmails($to_email, $subject, $body, '','', []);
					
					if($result['status']){
						return $res;
					}
					else{
						return false;
					}
				}
				else{
					return 'INVALID_MOBILE_SERVICE';
				}
			}
			else{
				return false;
			}

		}
		else{
			return false;
		}

	}

	function verify_otp($otp, $user_type, $email = '', $mobile_number = ''){
		$verification_result = $this->db->select('*')
										->from('cs_otp_verification_tbl')
										->where('otp', $otp)
										->where('(email ="'.$email.'" OR phone_number="'.$mobile_number.'")')
										->where('otp_used', 0)
										->get()
										->row_array();
		
		if($verification_result && strtotime($verification_result['expiration_time']) > strtotime(date('Y-m-d H:i:s'))){
			$result = $this->db->update('cs_otp_verification_tbl', [
				'otp_used' => 1,
				'updated_by' => ($email)?$email:$mobile_number
			]);

			if($result){
				$flag = false;
				if($user_type == 'CLAIMANT_RESPONDENT'){
					$this->db->select('id, name, count_number, contact as phone, email');
					$this->db->from('cs_claimant_respondant_details_tbl as crd');
					$this->db->where('status', 1);
					$this->db->where('(email ="'.$email.'" OR contact="'.$mobile_number.'")');
					$q = $this->db->get();
					if($q->num_rows() > 0){
						$res = $q->row();
						$flag = true;
					}
				}
				if($user_type == 'COUNSEL'){
					$this->db->select('id, name, phone, email');
					$this->db->from('cs_counsels_tbl');
					$this->db->where('status', 1);
					$this->db->where('(email ="'.$email.'" OR phone="'.$mobile_number.'")');
					$q = $this->db->get();
					if($q->num_rows() > 0){
						$res = $q->row();
						$flag = true;
					}
				}
				if($user_type == 'ARBITRATOR'){
					$this->db->select('id, name_of_arbitrator as name, phone, email');
					$this->db->from('cs_arbitral_tribunal_tbl');
					$this->db->where('status', 1);
					$this->db->where('(email ="'.$email.'" OR phone="'.$mobile_number.'")');
					$q = $this->db->get();
					if($q->num_rows() > 0){
						$res = $q->row();
						$flag = true;
					}
				}

				if($flag){
					return [
						'status' => true,
						'result' => $res
					];
				}
				else{
					return [
						'status' => false,
						'message' => 'Server failed while responding.'
					];
				}
			}
			else{
				return [
					'status' => false,
					'message' => 'Server failed while responding.'
				];	
			}
		}
		else{
			return [
				'status' => false,
				'message' => 'Invalid OTP or OTP is expired.'
			];
		}

	}

	/*
	* Function to get all the cause list
	*/
	function all_cause_list(){
		$pageNo = $this->input->get('page');
		//print_r($pageNo);die();
		
		// Row per page
		$rowperpage = 10;
		// Row position
		if($pageNo != 0){
			$pageNo = ($pageNo-1) * $rowperpage;
		}

		$this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, rt.room_no, puc.category_name as purpose_category_name, remarks");
		$this->db->from('cause_list_tbl AS clt');
		$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id');
		$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id');
		$this->db->order_by('clt.id', 'DESC');
		$this->db->limit($rowperpage, $pageNo);

		//Get filters
		$case_no = $this->input->post('case_no');
		$case_title = $this->input->post('case_title');
		$date = $this->input->post('date');
		$arbitrator_name = $this->input->post('arbitrator_name');

		/* If case number is set */
		if(isset($case_no) && !empty($case_no)){
			$this->db->like('clt.case_no', $case_no);
		}

		/* If date is set */
		if(isset($date) && !empty($date)){
			$this->db->where('clt.date', $date);
		}

		/* If arbitrator name is set */
		if(isset($arbitrator_name)){
			$arb_string = explode(' ', $arbitrator_name);
			$like = '';
			$count = 1;
			foreach ($arb_string as $key => $value) {
				
				if($count < count($arb_string)){
					$like .= "clt.arbitrator_name LIKE '%$value%' OR ";
				}
				else{
					$like .= "clt.arbitrator_name LIKE '%$value%'";
				}

				$count++;	
			}

			$this->db->where($like);

		}

		$this->db->where('clt.active_status', 1);

		return $this->db->get()->result_array();
	}

	/*
	* Function to get todays cause list
	*/
	function today_cause_list(){
		
		$this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, remarks");
		$this->db->from('cause_list_tbl AS clt');
		$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id');
		$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id');

		$this->db->where('clt.date', date('d-m-Y'));
		$this->db->order_by('clt.id', 'DESC');
		$this->db->where('clt.active_status', 1);

		return $this->db->get()->result_array();
	}

	/*
	* Function to get all the case list
	*/
	function all_case_list(){
		$pageNo = $this->input->get('page');
		//print_r($pageNo);die();
		
		// Row per page
		$rowperpage = 10;
		// Row position
		if($pageNo != 0){
			$pageNo = ($pageNo-1) * $rowperpage;
		}
		
		$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
		$this->db->from('cs_case_details_tbl AS cdt');
		$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status');
		$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by');
		$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration');
		$this->db->where('gc2.gen_code_group', 'REFFERED_BY');
		$this->db->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION');
		$this->db->order_by('cdt.id', 'DESC');
		$this->db->limit($rowperpage, $pageNo);

		// Get filters
		$case_no = $this->input->post('case_no');
		$case_title = $this->input->post('case_title');
		$type_of_arbitration = $this->input->post('type_of_arbitration');

		if(isset($case_no) && !empty($case_no)){
			$this->db->like('cdt.case_no', $case_no);
		}

		if(isset($case_title) && !empty($case_title)){
			$this->db->like('cdt.case_title', $case_title);
		}

		if(isset($arbitrator_name) && !empty($arbitrator_name)){
			$this->db->like('cdt.name_of_judge', $arbitrator_name);
		}

		if(isset($type_of_arbitration) && !empty($type_of_arbitration)){
			$this->db->where('cdt.type_of_arbitration', $type_of_arbitration);
		}

		$this->db->where('cdt.status', 1);

		return $this->db->get()->result_array();
	}

	public function all_users_case_list($user_data){
		
		if($user_data->role == 'CLAIMANT_RESPONDENT'){
			$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, crd.removed");
			$this->db->from('cs_claimant_respondant_details_tbl AS crd');
			$this->db->join('cs_case_details_tbl AS cdt', 'crd.case_no = cdt.slug AND (crd.contact="'.$user_data->phone_number.'" OR crd.email="'.$user_data->email.'")', 'left');
		}
		elseif($user_data->role == 'COUNSEL'){
			$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, cct.discharge");
			$this->db->from('cs_counsels_tbl AS cct');
			$this->db->join('cs_case_details_tbl AS cdt', 'cct.case_no = cdt.slug AND (cct.phone="'.$user_data->phone_number.'" OR cct.email="'.$user_data->email.'")', 'left');
		}
		elseif($user_data->role == 'ARBITRATOR'){
			$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
			$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, catt.arb_terminated");
			$this->db->from('cs_arbitral_tribunal_tbl AS catt');
			$this->db->join('cs_case_details_tbl AS cdt', 'catt.case_no = cdt.slug AND (catt.phone="'.$user_data->phone_number.'" OR catt.email="'.$user_data->email.'")', 'left');
		}
		
		$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left');
		$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left');
		$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left');
		$this->db->where('gc2.gen_code_group', 'REFFERED_BY', 'left');
		$this->db->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION', 'left');

		if($user_data->role == 'CLAIMANT_RESPONDENT'){
			$this->db->where('(crd.contact ="'.$user_data->phone_number.'" OR crd.email="'.$user_data->email.'")');
		}
		elseif($user_data->role == 'COUNSEL'){
			$this->db->where('(cct.phone ="'.$user_data->phone_number.'" OR cct.email="'.$user_data->email.'")');
		}
		elseif($user_data->role == 'ARBITRATOR'){
			$this->db->where('(catt.phone ="'.$user_data->phone_number.'" OR catt.email="'.$user_data->email.'")');
		}
		
		
		$this->db->order_by('cdt.id', 'DESC');

		// Get filters
		$case_no = $this->input->post('case_no');

		if(isset($case_no) && !empty($case_no)){
			$this->db->like('cdt.case_no', $case_no);
		}

		$this->db->where('cdt.status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function all_users_cause_list($user_data){
		$clt_join_condition = '';
		
		$this->db->select("clt.id, clt.case_no, clt.arbitrator_name, clt.title_of_case, clt.date, clt.time_from, clt.time_to, clt.purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks");

		if($user_data->role == 'CLAIMANT_RESPONDENT'){
			$this->db->from('cs_claimant_respondant_details_tbl AS crd');
			$this->db->join('cs_case_details_tbl AS cdt', 'crd.case_no = cdt.slug AND (crd.contact="'.$user_data->phone_number.'" OR crd.email="'.$user_data->email.'")', 'left');
		}
		elseif($user_data->role == 'COUNSEL'){
			$this->db->from('cs_counsels_tbl AS cct');
			$this->db->join('cs_case_details_tbl AS cdt', 'cct.case_no = cdt.slug AND (cct.phone="'.$user_data->phone_number.'" OR cct.email="'.$user_data->email.'")', 'left');
		}
		elseif($user_data->role == 'ARBITRATOR'){
			$this->db->from('cs_arbitral_tribunal_tbl AS catt');
			$this->db->join('cs_case_details_tbl AS cdt', 'catt.case_no = cdt.slug AND (catt.phone="'.$user_data->phone_number.'" OR catt.email="'.$user_data->email.'")', 'left');
		}

		if($this->input->get('type') && $this->input->get('type') == 'TODAYS_HEARINGS'){
			$clt_join_condition = 'AND clt.date="'.date('d-m-Y').'"';
		}

		$this->db->join('cause_list_tbl AS clt', 'clt.case_no = cdt.case_no '.$clt_join_condition, 'left');
		$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id');
		$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id');

		$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left');
		$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left');
		$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left');
		$this->db->where('gc2.gen_code_group', 'REFFERED_BY', 'left');
		$this->db->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION', 'left');

		if($user_data->role == 'CLAIMANT_RESPONDENT'){
			$this->db->where('(crd.contact = "'.$user_data->phone_number.'" OR crd.email="'.$user_data->email.'")');
		}
		elseif($user_data->role == 'COUNSEL'){
			$this->db->where('(cct.phone = "'.$user_data->phone_number.'" OR cct.email="'.$user_data->email.'")');
		}
		elseif($user_data->role == 'ARBITRATOR'){
			$this->db->where('(catt.phone = "'.$user_data->phone_number.'" OR catt.email="'.$user_data->email.'")');
		}
		
		$this->db->order_by('cdt.id', 'DESC');

		// Get filters
		$case_no = $this->input->post('case_no');

		if(isset($case_no) && !empty($case_no)){
			$this->db->like('cdt.case_no', $case_no);
		}

		$this->db->where('cdt.status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function user_case_detail($user_data, $case_no){		
		$case_data = $this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, cdt.created_on, cdt.updated_on")
							->from('cs_case_details_tbl as cdt')
							->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left')
							->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left')
							->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left')
							->where('gc2.gen_code_group', 'REFFERED_BY')
							->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION')
							->where('cdt.slug', $case_no)
							->where('cdt.status', 1)
							->get()
							->row_array();

		// Get all claimant of case
		$claimant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name, crt.created_on, crt.updated_on")
					->from('cs_claimant_respondant_details_tbl as crt')
					->join('country_master as cm', 'cm.country_code = crt.country', 'left')
					->where('case_no', $case_no)
					->where('type', 'claimant')
					->where('status', 1)
					->order_by('count_number', 'ASC')
					->get()
					->result_array();


		// Get all respondant of case
		$respondant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name, crt.created_on, crt.updated_on")
					->from('cs_claimant_respondant_details_tbl as crt')
					->join('country_master as cm', 'cm.country_code = crt.country', 'left')
					->where('crt.case_no', $case_no)
					->where('crt.type', 'respondant')
					->where('crt.status', 1)
					->order_by('count_number', 'ASC')
					->get()
					->result_array();

											
		// Get the status of  pleadings of data
		$sop_data = $this->db->select("id, claim_invited_on, rem_to_claim_on, rem_to_claim_on_2, claim_filed_on, res_served_on, sod_filed_on, rej_stat_def_filed_on, counter_claim, dof_counter_claim, reply_counter_claim_on, rej_reply_counter_claim_on, app_section, dof_app, reply_app_on, remarks, created_at, updated_at")
					->from('cs_status_of_pleadings_tbl')
					->where('case_no', $case_no)
					->where('status', 1)
					->get()
					->row_array();

		// Get the other pleadings data
		$sop_op_data = $this->db->select("id, case_no, details, date_of_filing, filed_by, created_at, updated_at")
					->from('cs_other_pleadings_tbl')
					->where('case_no', $case_no)
					->where('status', 1)
					->get()
					->result_array();

		// Get the other correspondance data
		$sop_oc_data = $this->db->select("id, case_no, details, date_of_correspondance, send_by, sent_to, created_at, updated_at")
					->from('cs_other_correspondance_tbl')
					->where('case_no', $case_no)
					->where('status', 1)
					->get()
					->result_array();


		// Arbitral tribunals
		$arbitral_tribunal = $this->db->select("cat.id, cat.name_of_arbitrator, cat.whether_on_panel,CASE WHEN cat.whether_on_panel = 'yes' THEN 'Yes' WHEN cat.whether_on_panel = 'no' THEN 'No' ELSE '' END as whether_on_panel_desc, cat.at_cat_id, cat.appointed_by, cat.date_of_appointment, cat.date_of_declaration, cat.arb_terminated,CASE WHEN cat.arb_terminated = 'yes' THEN 'Yes' WHEN cat.arb_terminated = 'no' THEN 'No' ELSE '' END as arb_terminated_desc, cat.date_of_termination, cat.reason_of_termination, pct.category_name, gc.description as appointed_by_desc, cat.created_at, cat.updated_at")
							->from('cs_arbitral_tribunal_tbl as cat')
							->join('panel_category_tbl as pct', 'pct.id = cat.at_cat_id', 'left')
							->join('gen_code_desc as gc', 'gc.gen_code = cat.appointed_by', 'left')
							->where('gc.gen_code_group', 'APPOINTED_BY')
							->where('cat.case_no', $case_no)
							->where('cat.status', 1)
							->get()
							->result_array();

		// Counsels
		$counsels = $this->db->select("ct.id, ct.case_no, ct.enrollment_no, ct.name, ct.appearing_for, ct.address, ct.email, ct.phone, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, ct.date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, ct.created_at, ct.updated_at")
							->from('cs_counsels_tbl as ct')
							->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = ct.appearing_for', 'left')
								->where('ct.case_no', $case_no)
							->where('ct.status', 1)
							->get()
							->result_array();

		// cost deposit
		$cost_deposit = $this->db->select("cd.id, cd.case_no, cd.date_of_deposit, cd.deposited_by, cd.name_of_depositor, cd.cost_imposed_dated,cd.mode_of_deposit, cd.details_of_deposit, cd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc2.description mod_description, cd.created_at, cd.updated_at")
							->from('cs_cost_deposit_tbl as cd')
							->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = cd.deposited_by')
							->join('gen_code_desc as gc2', 'gc2.gen_code = cd.mode_of_deposit')
							->where('cd.case_no', $case_no)
							->where('cd.status', 1)
							->get()
							->result_array();

		// Fee Deposit
		$fee_deposit = $this->db->select("fd.id, fd.case_no, fd.date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description, fd.created_at, fd.updated_at")
							->from('cs_fee_deposit_tbl as fd')
							->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by')
							->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards')
							->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit')

							->where('fd.case_no', $case_no)
							->where('fd.status', 1)
							->get()
							->result_array();



		// Fee Refund
		$fee_refund = $this->db->select("fr.id, fr.case_no, fr.date_of_refund, fr.refunded_to, fr.name_of_party, fr.refunded_towards, fr.mode_of_refund, fr.details_of_refund, fr.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description refunded_towards_description, gc2.description mod_refund, fr.created_at, fr.updated_at")
							->from('cs_fee_refund_tbl as fr')
							->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fr.refunded_to')
							->join('gen_code_desc as gc', 'gc.gen_code = fr.refunded_towards')
							->join('gen_code_desc as gc2', 'gc2.gen_code = fr.mode_of_refund')

							->where('fr.case_no', $case_no)
							->where('fr.status', 1)
							->get()
							->result_array();

		// Fee released

		$fee_released = $this->db->select("fr.id, fr.case_no, fr.date_of_fee_released, fr.released_to, fr.mode_of_payment, fr.details_of_fee_released, fr.amount, gc.description as mop_description, fr.created_at, fr.updated_at")
							->from('cs_at_fee_released_tbl as fr')
							->join('gen_code_desc as gc', 'gc.gen_code = fr.mode_of_payment')
							->where('fr.case_no', $case_no)
							->where('fr.status', 1)
							->get()
							->result_array();

		// Noting
		$notings = $this->db->select("nt.id, nt.case_no, nt.noting, nt.noting_date, nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um2.user_display_name marked_by_user, nt.created_at, nt.updated_at")
							->from('cs_noting_tbl as nt')
							->join('user_master as um', 'um.user_name = nt.marked_to')
							->join('user_master as um2', 'um2.user_name = nt.marked_by')
							->where('nt.case_no', $case_no)
							->where('nt.status', 1)
							->order_by('nt.created_at', 'DESC')
							->get()
							->result_array();

		// Get the award termination data
		$award_term_data = $this->db->select("id, type, date_of_award, nature_of_award, addendum_award, award_served_claimaint_on, award_served_respondent_on, date_of_termination, reason_for_termination, factsheet_prepared, CASE WHEN factsheet_prepared = 'yes' THEN 'Yes' WHEN factsheet_prepared = 'no' THEN 'No' END as factsheet_prepared_desc, created_at, updated_at")
					->from('cs_award_term_tbl')
					->where('case_no', $case_no)
					->where('status', 1)
					->get()
					->row_array();

		// Get the fee and cost details
		$fee_cost_data = $this->db->select("id, c_cc_asses_sep, sum_in_dispute, sum_in_dispute_claim, sum_in_dispute_cc, asses_date, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, CASE WHEN c_cc_asses_sep = 'yes' THEN 'Yes' WHEN c_cc_asses_sep = 'no' THEN 'No' END as c_cc_asses_sep_desc, created_at, updated_at")
					->from('cs_fee_details_tbl')
					->where('case_no', $case_no)
					->where('status', 1)
					->get()
					->row_array();

			// Get total amount and balanace amount
		$this->db->select("cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, (cs_arb_fees + cs_adminis_fees + rs_arb_fees + rs_adminis_fee) as total_amount, created_at, updated_at");
		$this->db->from('cs_fee_details_tbl');
		$this->db->where('case_no', $case_no);
		$this->db->where('status', 1);
		$query = $this->db->get(); 
		$ta_data = $query->row_array();

		// Get fee deposited
		$this->db->select("SUM(amount) as fee_deposit");
		$this->db->from('cs_fee_deposit_tbl');
		$this->db->where('case_no', $case_no);
		$this->db->where('status', 1);
		$query = $this->db->get(); 
		$fd_data = $query->row_array();

		$amount_n_balance['total_amount'] = (isset($ta_data['total_amount']) && $ta_data['total_amount'])? $ta_data['total_amount']: 0;
		$amount_n_balance['deposit_amount'] = (isset($fd_data['fee_deposit']))? $fd_data['fee_deposit']: 0;
		$amount_n_balance['balance'] = $amount_n_balance['total_amount'] - $amount_n_balance['deposit_amount'];


		$output = array(
			'case_data' => $case_data,
			'claimant_data' => $claimant_data,
			'respondant_data' => $respondant_data,
			'arbitral_tribunal' => $arbitral_tribunal,
			'counsels' => $counsels,
			'cost_deposit' => $cost_deposit,
			'fee_deposit' => $fee_deposit,
			'fee_released' => $fee_released,
			'fee_refund' => $fee_refund,
			'sop_data' => $sop_data,
			'sop_op_data' => $sop_op_data,
			'sop_oc_data' => $sop_oc_data,
			'fee_cost_data' => $fee_cost_data,
			'award_term_data' => $award_term_data,
			'notings' => $notings,
			'amount_n_balance' => $amount_n_balance
		);

		return $output;
	}

}
