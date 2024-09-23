
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class common_model extends CI_Model{

	public $date;
	function __construct(){
		// Call the Model constructor
		parent::__construct();
		
		$this->load->helper('date');

	    if (ENVIRONMENT == 'production') {
	        $this->db->save_queries = FALSE;
	    }
	    date_default_timezone_set('Asia/Kolkata');
		$this->date = date('Y-m-d H:i:s', time());

	    $this->role 		= $this->session->userdata('role');
	    $this->user_name 	= $this->session->userdata('user_name');
 	}


 	public function get($data, $op){
 		switch($op){

 			case 'GET_DATA_FOR_DASHBOARD':

 				$todays_date = date('d-m-Y');

 				$total_case_count = $this->db->where('status', 1)
				 							->count_all_results('cs_case_details_tbl');

 				$total_rooms = $this->db->where('active_status', 1)
				 						->count_all_results('rooms_tbl');

 				$total_hearings_today = $this->db->where(array(
					 	'active_status' => 1,
					  	'date' => $todays_date
					))
				 	->count_all_results('cause_list_tbl');

 				$total_poa = $this->db->where('status', 1)
				 						->count_all_results('panel_of_arbitrator_tbl');

				$allRegisteredCases = $this->db->from('cs_case_details_tbl')
												->select('*')
												->where('status', 1)
												->get()
												->result_array();

				// Get Yearwise case count
				$yearWiseData = $this->db->query("SELECT 
				COUNT(*) AS cnt ,YEAR(STR_TO_DATE(registered_on,'%d-%m-%Y')) AS registered_year 
				FROM cs_case_details_tbl
				GROUP BY YEAR(STR_TO_DATE(registered_on,'%d-%m-%Y')) ORDER BY registered_year DESC")->result_array();

				// Get monthwise case count
				$monthWiseData = $this->db->query("SELECT month_name,COUNT(cd.id) AS cnt ,MONTH(STR_TO_DATE(cd.registered_on,'%d-%m-%Y')) AS registered_month 
				FROM month_table AS mt LEFT JOIN  cs_case_details_tbl AS cd ON  YEAR(STR_TO_DATE(registered_on,'%d-%m-%Y'))= '".$data['current_year']."' AND MONTH(STR_TO_DATE(cd.registered_on,'%d-%m-%Y')) = mt.month_number
				GROUP BY month_name ORDER BY mt.id ASC")->result_array();

 				return array(
 					'total_case_count' => $total_case_count,
 					'total_rooms' => $total_rooms,
 					'total_hearings_today' => $total_hearings_today,
 					'total_poa' => $total_poa,
					'allRegisteredCases' => $allRegisteredCases,
					'yearWiseData' => $yearWiseData,
					'monthWiseData' => $monthWiseData
 				);

 			break;

 			case 'GET_DEPOSITED_TOWARDS_GENCODE_DESC':
            	$this->db->from('gen_code_desc');
                $this->db->select('gen_code,description');
                $this->db->where('gen_code_group', $data);
                $this->db->where('status',1);
                $this->db->order_by('sl_no','ASC');
                $res = $this->db->get();
                return $res->result_array();
            break;

            case 'GET_GENCODE_DESC':
            	$this->db->from('gen_code_desc');
                $this->db->select('gen_code,description');
                $this->db->where('gen_code_group', $data);
                $this->db->where('status',1);
                $this->db->order_by('sl_no','ASC');
                $res = $this->db->get();
                return $res->result_array();
            break;

            case 'GET_ALL_PANEL_CATEGORY':
            	$this->db->from('panel_category_tbl');
                $this->db->select('id, category_name');
                $this->db->where('status',1);
                $res = $this->db->get();
                return $res->result_array();
            break;

            case 'GET_ALL_REGISTERED_CASES':
            	$this->db->from('cs_case_details_tbl');
                $this->db->select('id, slug, case_no, case_title');
                $this->db->where('status',1);
                $res = $this->db->get();
                return $res->result_array();
            break;


            case 'DATA_LOGS_LIST':

 				$select_column = array("message", "created_by", "created_at");
				$order_column = array(null, "created_by", "created_at");


				$this->db->select("dlt.message, dlt.created_at as date, dlt.created_by, um.user_display_name as alter_by_user");
				$this->db->from('data_logs_tbl as dlt');
				$this->db->join('user_master as um', 'um.user_name = dlt.created_by');
				
				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'dlt.'.$sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".'dlt.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('dlt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('dlt.status', 1);
				$query = $this->db->get();
				// print_r($this->db->last_query()); 
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status',1)->select("*")->from('data_logs_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status',1)->select("*")->from('data_logs_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

            case 'CHECK_NOTIFICATION':
            	$result = $this->db->from('notification_tbl')
            			 ->where(array('notification_to' => $this->user_name,'seen' => 0, 'status' => 1))
            			 ->get()
            			 ->result_array();

            	if($result){
            		
            		$notification_data = array();
            		$notification_type = '';

            		foreach ($result as $key => $res) {
            			
            			if($res['type_table'] == 'cs_noting_tbl'){
            				$result2 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at, cnt.case_no as cnt_case_slug, cdt.case_no')
            							->where('cnt.id', $res['type_id'])
            							->from($res['type_table'].' as cnt')
            							->join('user_master as um', 'um.user_name = cnt.marked_by', 'left')
            							->join('user_master as um2', 'um2.user_name = cnt.marked_to', 'left')
            							->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no', 'left')
            							->get()
            							->row_array();
							// echo "<pre>";
            				// print_r($result2); die();
            				if($result2){
								$notification_text = $result2['marked_by_user'].' marked you in noting of case '.$result2['case_no'].' at '. date('d M, Y - h:i A', strtotime($result2['marked_at']));

								$notification_data[] = $notification_text;
								$notification_type = 'NOTING';
							}
            			}
            		}

            		$output = array(
            			'status' => true,
            			'msg' => 'You are mentioned in a noting.',
            			'count' => count($result),
            			'notification_data' => $notification_data,
            			'notification_type' => $notification_type
            		);
            	}
            	else{
            		$output = array(
            			'status' => false,
            			'msg' => 'No new notification.',
            			'count' => '',
            			'notification_data' => '',
            			'notification_type' => ''
            		);
            	}

            	return $output;

            break;

            case 'GET_NOTIFICATIONS':

            	$select_column = array("type_table", "type_id", "notification_from", "notification_to", "seen", "created_at");
				$order_column = array(null, null, "notification_from", "notification_to", null, "created_at");


				$result = $this->db->from('notification_tbl')
            			 ->where(array('notification_to' => $this->user_name,'status' => 1))
            			 ->get()
            			 ->result_array();
            		
        		$notification_data = array();
        		$notification_type = '';

        		foreach ($result as $key => $res) {

        			$notification_item = array();

        			if($res['type_table'] == 'cs_noting_tbl'){
        				$result2 = $this->db->select('um.user_display_name as marked_by_user, um2.user_display_name as marked_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
        							->where('cnt.id', $res['type_id'])
        							->from($res['type_table'].' as cnt')
        							->join('user_master as um', 'um.user_name = cnt.marked_by')
        							->join('user_master as um2', 'um2.user_name = cnt.marked_to')
        							->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no')
        							->get()
        							->row_array();

        				// print_r($result2); die();
        				if($result2){
							$notification_text = $result2['marked_by_user'].' marked you in noting of case <b>'.$result2['cdt_case_no'].'</b> at '. date('d M, Y - h:i A', strtotime($result2['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'NOTING';
							$notification_item['id'] = $res['id'];
						}
        			}
        			elseif($res['type_table'] == 'cs_case_allotment_tbl'){
        				$result2 = $this->db->select('um.user_display_name as alloted_by_user, um2.user_display_name as alloted_to_user, cnt.created_at as marked_at, cdt.case_no as cdt_case_no')
        							->where('cnt.id', $res['type_id'])
        							->from($res['type_table'].' as cnt')
        							->join('user_master as um', 'um.user_name = cnt.alloted_by')
        							->join('user_master as um2', 'um2.user_name = cnt.alloted_to')
        							->join('cs_case_details_tbl as cdt', 'cdt.slug = cnt.case_no')
        							->get()
        							->row_array();

        				// print_r($result2); die();
        				if($result2){
							$notification_text = 'Case number: <b>'.$result2['cdt_case_no'].'</b> is allotted to you at '. date('d M, Y - h:i A', strtotime($result2['marked_at']));

							$notification_item['text'] = $notification_text;
							$notification_item['type'] = 'CASE_ALLOTMENT';
							$notification_item['id'] = $res['id'];
						}
        			}

        			$notification_data[] = $notification_item;
        		}


				// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('notification_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('notification_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => [$notification_data]
		    	);
		    	return $output;

            break;

            case 'GET_ALL_PURPOSE_CATEGORY':
            	$this->db->from('purpose_category_tbl');
                $this->db->select('id, category_name');
                $this->db->where('status',1);
                $res = $this->db->get();
                return $res->result_array();
            break;

 			case 'ALL_ROOMS_LIST':
 				$q = $this->db->from('rooms_tbl')
 						 ->where('active_status',1)
 						 ->order_by('room_no', 'ASC')
 						 ->get();
 				return $q->result_array();
			break;

			case 'ALL_DIAC_USERS':
 				$q = $this->db->select('user_code, user_name, user_display_name, job_title')
 						 ->from('user_master')
 						 ->where('primary_role', 'CASE_MANAGER')
 						 ->or_where('primary_role', 'ACCOUNTS')
 						 ->where('record_status',1)
 						 ->order_by('user_display_name', 'ASC')
 						 ->get();
 				return $q->result_array();
			break;

 			case 'ALL_CAUSE_LIST':

				$select_column = array("case_no", "arbitrator_name", "title_of_case", "date", "time_from", "time_to", "purpose_cat_id", "room_no_id", "remarks");
				$order_column = array(null, "case_no", "title_of_case", "arbitrator_name", "purpose_cat_id", "date", "time_from", "time_to", null, null, null);

				$this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, clt.remarks, clt.active_status, CASE WHEN clt.active_status='2' THEN 'Cancelled' WHEN clt.active_status='1' THEN 'Active' END as active_status_desc ");
				$this->db->from('cause_list_tbl AS clt');
				$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
				$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('clt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('clt.active_status !=', 0);

				// If case no is set
				if(isset($_POST['case_no']) && !empty($_POST['case_no'])){
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->where('clt.case_no', $case_no);
				}

				// If date is set
				if(isset($_POST['date']) && !empty($_POST['date'])){
					$date = $this->security->xss_clean($_POST['date']);
					$this->db->where('clt.date', $date);
				}

				// If room number is set
				if(isset($_POST['room_no']) && !empty($_POST['room_no'])){
					$room_no_id = $this->security->xss_clean($_POST['room_no']);
					$this->db->where('clt.room_no_id', $room_no_id);
				}

				$query = $this->db->get();
				
				$fetch_data = $query->result();

				// Filter records
		 		$recordsFiltered = $this->db->where('active_status !=', 1)->select("*")->from('cause_list_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('active_status', 1)->select("*")->from('cause_list_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;
 			break;

			//  11-01-2021
			case 'GET_DISPLAY_BOARD_LIST':

				$todays_date = date('d-m-Y');
				$select_column = array("room_no");
				$order_column = array(null, null, "room_no", "room_name", null);

				// $this->db->select("cdb.id as cdb_id, cdb.case_no, cdb.arbitrator_name, cdb.room_status, cdb.todays_date, rt.room_name, rt.room_no, cdb.room_id, rt.id");
				// $this->db->from('cs_display_board_tbl AS cdb');
				// $this->db->join('rooms_tbl as rt', 'cdb.room_id = rt.id', 'left');
				$this->db->select("cdb.id as cdb_id, cdb.case_no, cdb.arbitrator_name, cdb.room_status, cdb.todays_date, rt.room_name, rt.room_no, cdb.room_id, rt.id as rt_id");
				$this->db->from('rooms_tbl as rt');
				$this->db->join("(SELECT * FROM cs_display_board_tbl WHERE todays_date = '$todays_date') as cdb", 'cdb.room_id = rt.id', 'left');
				$this->db->where('rt.active_status', 1);

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'rt.'.$sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".'rt.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('rt.room_no', 'ASC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				// If case no is set
				// if(isset($_POST['case_no']) && !empty($_POST['case_no'])){
				// 	$case_no = $this->security->xss_clean($_POST['case_no']);
				// 	$this->db->where('clt.case_no', $case_no);
				// }

				// // If date is set
				// if(isset($_POST['date']) && !empty($_POST['date'])){
				// 	$date = $this->security->xss_clean($_POST['date']);
				// 	$this->db->where('clt.date', $date);
				// }

				// // If room number is set
				// if(isset($_POST['room_no']) && !empty($_POST['room_no'])){
				// 	$room_no_id = $this->security->xss_clean($_POST['room_no']);
				// 	$this->db->where('clt.room_no_id', $room_no_id);
				// }

				$query = $this->db->get();
				// echo $this->db->last_query();
				$fetch_data = $query->result();

				// Filter records
		 		$recordsFiltered = $this->db->select("*")->from('cs_display_board_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->select("*")->from('cs_display_board_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;
			break;
			 
 			case 'HEARINGS_TODAY_LIST':

				$select_column = array("case_no", "arbitrator_name", "title_of_case", "date", "time_from", "time_to", "purpose_cat_id", "room_no_id", "remarks");
				$order_column = array(null, "case_no", "title_of_case", "arbitrator_name", "purpose_cat_id", "date", "time_from", "time_to", null, null, null);


				$this->db->select("clt.id, case_no, arbitrator_name, title_of_case, date, time_from, time_to, purpose_cat_id, rt.room_no, clt.room_no_id, puc.category_name as purpose_category_name, remarks");
				$this->db->from('cause_list_tbl AS clt');
				$this->db->join('rooms_tbl as rt', 'rt.id = clt.room_no_id', 'left');
				$this->db->join('purpose_category_tbl as puc', 'puc.id = clt.purpose_cat_id', 'left');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('clt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('clt.date', date('d-m-Y'));
				$this->db->where('clt.active_status', 1);

				$query = $this->db->get();
				
				// print_r($this->db->last_query()); 
				
				$fetch_data = $query->result();

				// Filter records
		 		$recordsFiltered = $this->db->where('date', date('d-m-Y'))->where('active_status', 1)->select("*")->from('cause_list_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('date', date('d-m-Y'))->where('active_status', 1)->select("*")->from('cause_list_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;
 			break;

 			case 'ALL_CASE_LIST':

				$select_column = array("case_no", "reffered_on", "registered_on");
				$order_column = array(null, "case_no", null, "reffered_on", null, null, "registered_on", null, null);


				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left');
				$this->db->where('gc2.gen_code_group', 'REFFERED_BY');
				$this->db->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('cdt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if(isset($_POST['case_no']) && !empty($_POST['case_no'])){
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->where('cdt.case_no', $case_no);
				}

				// If type of arbitration is set
				if(isset($_POST['toa']) && !empty($_POST['toa'])){
					$toa = $this->security->xss_clean($_POST['toa']);
					$this->db->where('cdt.type_of_arbitration', $toa);
				}

				// If case status is set
				if(isset($_POST['case_status']) && !empty($_POST['case_status'])){
					$case_status = $this->security->xss_clean($_POST['case_status']);
					$this->db->where('cdt.case_status', $case_status);
				}

				// If registered on is set
				if(isset($_POST['registered_on']) && !empty($_POST['registered_on'])){
					$registered_on = $this->security->xss_clean($_POST['registered_on']);
					$this->db->where('cdt.registered_on', $registered_on);
				}

				// If reffered by(court/direct) is set
				if(isset($_POST['reffered_by']) && !empty($_POST['reffered_by'])){
					$reffered_by = $this->security->xss_clean($_POST['reffered_by']);
					$this->db->where('cdt.reffered_by', $reffered_by);
				}

				// If name of judge is set
				if(isset($_POST['name_of_judge']) && !empty($_POST['name_of_judge'])){
					$name_of_judge = $this->security->xss_clean($_POST['name_of_judge']);
					$this->db->where("cdt.name_of_judge LIKE '%$name_of_judge%'");
				}

				/*
					Filters End ======================
				*/

				$this->db->where('cdt.status', 1);
				$query = $this->db->get();
				//print_r($this->db->last_query()); 
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;
 			break;


 			case 'ALL_REGISTERED_CASE_LIST':

				$select_column = array("case_no", "reffered_on", "registered_on");
				$order_column = array(null, "case_no", "reffered_on", "registered_on", null, null, null, null);


				$this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc");
				$this->db->from('cs_case_details_tbl AS cdt');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left');
				$this->db->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left');
				$this->db->where('gc2.gen_code_group', 'REFFERED_BY');
				$this->db->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('cdt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if(isset($_POST['case_no']) && !empty($_POST['case_no'])){
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->where('cdt.case_no', $case_no);
				}

				// If type of arbitration is set
				if(isset($_POST['toa']) && !empty($_POST['toa'])){
					$toa = $this->security->xss_clean($_POST['toa']);
					$this->db->where('cdt.type_of_arbitration', $toa);
				}

				// If case status is set
				if(isset($_POST['case_status']) && !empty($_POST['case_status'])){
					$case_status = $this->security->xss_clean($_POST['case_status']);
					$this->db->where('cdt.case_status', $case_status);
				}

				// If registered on is set
				if(isset($_POST['registered_on']) && !empty($_POST['registered_on'])){
					$registered_on = $this->security->xss_clean($_POST['registered_on']);
					$this->db->where('cdt.registered_on', $registered_on);
				}

				// If reffered by(court/direct) is set
				if(isset($_POST['reffered_by']) && !empty($_POST['reffered_by'])){
					$reffered_by = $this->security->xss_clean($_POST['reffered_by']);
					$this->db->where('cdt.reffered_by', $reffered_by);
				}

				// If name of judge is set
				if(isset($_POST['name_of_judge']) && !empty($_POST['name_of_judge'])){
					$name_of_judge = $this->security->xss_clean($_POST['name_of_judge']);
					$this->db->where("cdt.name_of_judge LIKE '%$name_of_judge%'");
				}

				/*
					Filters End ======================
				*/

				$this->db->where('cdt.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_details_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;
 			break;


 			case 'CHECK_CASE_NUMBER':

				$this->form_validation->set_rules('ecn_case_no', 'Case Number', 'required|xss_clean');
				$this->form_validation->set_rules('ecn_type', 'Type', 'required|xss_clean');

				if($this->form_validation->run()){
					
					$case_no = $this->security->xss_clean($this->input->post('ecn_case_no'));
					$type = $this->security->xss_clean($this->input->post('ecn_type'));

					$query = $this->db->get_where('cs_case_details_tbl', array('case_no' => $case_no, 'status' => 1));
					$count = $query->num_rows();

					if($count == 1){

						// Get case details to use it's slug
						$case_details = $this->db->get_where('cs_case_details_tbl', array('case_no' => $case_no, 'status' => 1))->row_array();
						
						if($case_details){

							$case_slug = $case_details['slug'];

							// Check if the case is allotted to the user ot not
							$check_result = $this->check_user_case_allotment($case_slug, $type);

							// If check result is true
							if($check_result){

								// Check for the type and redirect on the required page
								if($type == 'STATUS_OF_PLEADINGS'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'status-of-pleadings/'.$case_slug
									);
								}
								elseif($type == 'CLAIMANT_RESPONDANT_DETAILS'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'claimant-respondant-details/'.$case_slug
									);
								}
								elseif($type == 'COUNSELS'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'counsels/'.$case_slug
									);
								}
								elseif($type == 'ARBITRAL_TRIBUNAL'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'arbitral-tribunal/'.$case_slug
									);
								}
								elseif($type == 'CASE_FEES_DETAILS'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'case-fees-details/'.$case_slug
									);
								}
								elseif($type == 'AWARD_TERMINATION'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'award-termination/'.$case_slug
									);
								}
								elseif($type == 'NOTING'){
									$output = array(
										'status' => true,
										'redirect_url' => base_url().'noting/'.$case_slug
									);
								}
								else{
									$output = array(
										'status' => false,
										'msg' => 'Invalid type is provided. Please contact support.'
									);
								}
							}
							else{
								$output = array(
									'status' => false,
									'msg' => 'Case is not alloted to you.'
								);
							}
							
							
						}
						else{
							$output = array(
									'status' => false,
									'msg' => 'Something went wrong. Please try again.'
								);
						}
					}
					else{
						$output = array(
							'status' => false,
							'msg' => 'Case number not found. Please check your case number.'
						);
					}
				}
				else{
					$output = array(
						'status' => 'validationerror',
						'msg' => validation_errors()
					);
				}
				
		    	return $output;
 			break;

 			case 'GET_CASE_BASIC_DATA':
 				$this->db->select("cdt.slug as cdt_slug ,cdt.case_no as case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge, cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration,cdt.case_status, cdt.remarks");
				$this->db->from('cs_case_details_tbl AS cdt');
				
				$this->db->where('cdt.slug', $data['slug']);
				$this->db->where('cdt.status', 1);
				$output = $this->db->get()->row_array();

				return $output;
 			break;

 			case 'GET_CASE_NUMBER_USING_SLUG':
 				$this->db->select("cdt.case_no as case_no");
				$this->db->from('cs_case_details_tbl AS cdt');
				
				$this->db->where('cdt.slug', $data['case_no']);
				$this->db->where('cdt.status', 1);
				$output = $this->db->get()->row_array();

				return $output;
 			break;

 			case 'ALL_CASE_DATA':
				
				$case_data = $this->db->select("cdt.id as cdt_id, cdt.slug as cdt_slug, cdt.case_no, cdt.case_title, cdt.reffered_on, cdt.reffered_by, cdt.reffered_by_judge, cdt.arbitration_petition, cdt.name_of_court, cdt.name_of_judge,cdt.recieved_on, cdt.registered_on, cdt.type_of_arbitration, cdt.case_status, cdt.remarks, gc.description as case_status_dec, gc2.description as reffered_by_desc, gc3.description as type_of_arbitration_desc, cdt.created_on, cdt.updated_on")
									->from('cs_case_details_tbl as cdt')
									->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status', 'left')
									->join('gen_code_desc as gc2', 'gc2.gen_code = cdt.reffered_by', 'left')
									->join('gen_code_desc as gc3', 'gc3.gen_code = cdt.type_of_arbitration', 'left')
									->where('gc2.gen_code_group', 'REFFERED_BY')
									->where('gc3.gen_code_group', 'TYPE_OF_ARBITRATION')
									->where('cdt.slug', $data['slug'])
									->where('cdt.status', 1)
									->get()
									->row_array();

				// Get all claimant of case
				$claimant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name, crt.created_on, crt.updated_on")
						 ->from('cs_claimant_respondant_details_tbl as crt')
						 ->join('country_master as cm', 'cm.country_code = crt.country', 'left')
						 ->where('case_no', $data['slug'])
						 ->where('type', 'claimant')
						 ->where('status', 1)
						 ->order_by('count_number', 'ASC')
						 ->get()
						 ->result_array();


				// Get all respondant of case
				$respondant_data = $this->db->select("crt.id, crt.case_no, crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed,CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name, crt.created_on, crt.updated_on")
						 ->from('cs_claimant_respondant_details_tbl as crt')
						 ->join('country_master as cm', 'cm.country_code = crt.country', 'left')
						 ->where('crt.case_no', $data['slug'])
						 ->where('crt.type', 'respondant')
						 ->where('crt.status', 1)
						 ->order_by('count_number', 'ASC')
						 ->get()
						 ->result_array();

												  
				// Get the status of  pleadings of data
				$sop_data = $this->db->select("id, claim_invited_on, rem_to_claim_on, rem_to_claim_on_2, claim_filed_on, res_served_on, sod_filed_on, sod_pol, sod_dod, rej_stat_def_filed_on, counter_claim, dof_counter_claim, reply_counter_claim_on, reply_counter_claim_pol, reply_counter_claim_dod, rej_reply_counter_claim_on, app_section, dof_app, reply_app_on, remarks, created_at, updated_at")
						 ->from('cs_status_of_pleadings_tbl')
						 ->where('case_no', $data['slug'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

				// Get the other pleadings data
				$sop_op_data = $this->db->select("id, case_no, details, date_of_filing, filed_by, created_at, updated_at")
						 ->from('cs_other_pleadings_tbl')
						 ->where('case_no', $data['slug'])
						 ->where('status', 1)
						 ->get()
						 ->result_array();

				// Get the other correspondance data
				$sop_oc_data = $this->db->select("id, case_no, details, date_of_correspondance, send_by, sent_to, created_at, updated_at")
						 ->from('cs_other_correspondance_tbl')
						 ->where('case_no', $data['slug'])
						 ->where('status', 1)
						 ->get()
						 ->result_array();


				// Arbitral tribunals
				$arbitral_tribunal = $this->db->select("cat.id, cat.name_of_arbitrator, cat.whether_on_panel,CASE WHEN cat.whether_on_panel = 'yes' THEN 'Yes' WHEN cat.whether_on_panel = 'no' THEN 'No' ELSE '' END as whether_on_panel_desc, cat.at_cat_id, cat.appointed_by, cat.date_of_appointment, cat.date_of_declaration, cat.arb_terminated,CASE WHEN cat.arb_terminated = 'yes' THEN 'Yes' WHEN cat.arb_terminated = 'no' THEN 'No' ELSE '' END as arb_terminated_desc, cat.date_of_termination, cat.reason_of_termination, pct.category_name, gc.description as appointed_by_desc, cat.created_at, cat.updated_at")
									->from('cs_arbitral_tribunal_tbl as cat')
									->join('panel_category_tbl as pct', 'pct.id = cat.at_cat_id', 'left')
									->join('gen_code_desc as gc', 'gc.gen_code = cat.appointed_by', 'left')
									->where('gc.gen_code_group', 'APPOINTED_BY')
									->where('cat.case_no', $data['slug'])
									->where('cat.status', 1)
									->get()
									->result_array();

				// Counsels
				$counsels = $this->db->select("ct.id, ct.case_no, ct.enrollment_no, ct.name, ct.appearing_for, ct.address, ct.email, ct.phone, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, ct.date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, ct.created_at, ct.updated_at")
									->from('cs_counsels_tbl as ct')
									->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = ct.appearing_for', 'left')
				 					->where('ct.case_no', $data['slug'])
									->where('ct.status', 1)
									->get()
									->result_array();

				// cost deposit
				$cost_deposit = $this->db->select("cd.id, cd.case_no, cd.date_of_deposit, cd.deposited_by, cd.name_of_depositor, cd.cost_imposed_dated,cd.mode_of_deposit, cd.details_of_deposit, cd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc2.description mod_description, cd.created_at, cd.updated_at")
									->from('cs_cost_deposit_tbl as cd')
									->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = cd.deposited_by')
									->join('gen_code_desc as gc2', 'gc2.gen_code = cd.mode_of_deposit')
									->where('cd.case_no', $data['slug'])
									->where('cd.status', 1)
									->get()
									->result_array();

				// Fee Deposit
				$fee_deposit = $this->db->select("fd.id, fd.case_no, fd.date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description, fd.created_at, fd.updated_at")
									->from('cs_fee_deposit_tbl as fd')
									->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by')
									->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards')
									->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit')

									->where('fd.case_no', $data['slug'])
									->where('fd.status', 1)
									->get()
									->result_array();



				// Fee Refund
				$fee_refund = $this->db->select("fr.id, fr.case_no, fr.date_of_refund, fr.refunded_to, fr.name_of_party, fr.refunded_towards, fr.mode_of_refund, fr.details_of_refund, fr.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description refunded_towards_description, gc2.description mod_refund, fr.created_at, fr.updated_at")
									->from('cs_fee_refund_tbl as fr')
									->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fr.refunded_to')
									->join('gen_code_desc as gc', 'gc.gen_code = fr.refunded_towards')
									->join('gen_code_desc as gc2', 'gc2.gen_code = fr.mode_of_refund')

									->where('fr.case_no', $data['slug'])
									->where('fr.status', 1)
									->get()
									->result_array();

				// Fee released

				$fee_released = $this->db->select("fr.id, fr.case_no, fr.date_of_fee_released, fr.released_to, fr.mode_of_payment, fr.details_of_fee_released, fr.amount, gc.description as mop_description, fr.created_at, fr.updated_at")
									->from('cs_at_fee_released_tbl as fr')
									->join('gen_code_desc as gc', 'gc.gen_code = fr.mode_of_payment')
									->where('fr.case_no', $data['slug'])
									->where('fr.status', 1)
									->get()
									->result_array();

				// Noting
				$notings = $this->db->select("nt.id, nt.case_no, nt.noting, nt.noting_date, nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um2.user_display_name marked_by_user, nt.created_at, nt.updated_at")
									->from('cs_noting_tbl as nt')
									->join('user_master as um', 'um.user_name = nt.marked_to')
									->join('user_master as um2', 'um2.user_name = nt.marked_by')
									->where('nt.case_no', $data['slug'])
									->where('nt.status', 1)
									->order_by('nt.created_at', 'DESC')
									->get()
									->result_array();

				// Get the award termination data
				$award_term_data = $this->db->select("id, type, date_of_award, nature_of_award, addendum_award, award_served_claimaint_on, award_served_respondent_on, date_of_termination, reason_for_termination, factsheet_prepared, CASE WHEN factsheet_prepared = 'yes' THEN 'Yes' WHEN factsheet_prepared = 'no' THEN 'No' END as factsheet_prepared_desc, created_at, updated_at")
						 ->from('cs_award_term_tbl')
						 ->where('case_no', $data['slug'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

				// Get the fee and cost details
				$fee_cost_data = $this->db->select("id, c_cc_asses_sep, sum_in_dispute, sum_in_dispute_claim, sum_in_dispute_cc, asses_date, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, CASE WHEN c_cc_asses_sep = 'yes' THEN 'Yes' WHEN c_cc_asses_sep = 'no' THEN 'No' END as c_cc_asses_sep_desc, created_at, updated_at")
						 ->from('cs_fee_details_tbl')
						 ->where('case_no', $data['slug'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

 				// Get total amount and balanace amount
				$this->db->select("cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, (cs_arb_fees + cs_adminis_fees + rs_arb_fees + rs_adminis_fee) as total_amount, created_at, updated_at");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['slug']);
				$this->db->where('status', 1);
				$query = $this->db->get(); 
				$ta_data = $query->row_array();

				// Get fee deposited
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['slug']);
				$this->db->where('status', 1);
				$query = $this->db->get(); 
				$fd_data = $query->row_array();

				$amount_n_balance['total_amount'] = (isset($ta_data['total_amount']) && $ta_data['total_amount'])? $ta_data['total_amount']: 0;
				$amount_n_balance['deposit_amount'] = (isset($fd_data['fee_deposit']))? $fd_data['fee_deposit']: 0;
				$amount_n_balance['balance'] = $amount_n_balance['total_amount'] - $amount_n_balance['deposit_amount'];


				/*
				* Last updated details list of all tables
				*/

				$last_updated_details = $this->get_last_updated_datetime($data['slug']);


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
					'amount_n_balance' => $amount_n_balance,
					'last_updated' => $last_updated_details
				);

				// print_r($output); die();

		    	return $output;
 			break;

 			case 'GET_STATUS_OF_PLEADINGS':
 				// Get the status of  pleadings of data
				$sop_data = $this->db->select("id, claim_invited_on, rem_to_claim_on, rem_to_claim_on_2, claim_filed_on, res_served_on, sod_filed_on, sod_pol, sod_dod, rej_stat_def_filed_on, counter_claim, dof_counter_claim, reply_counter_claim_on, reply_counter_claim_pol, reply_counter_claim_dod, rej_reply_counter_claim_on, app_section, dof_app, reply_app_on, remarks")
						 ->from('cs_status_of_pleadings_tbl')
						 ->where('case_no', $data['case_no'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

				return $sop_data;
 			break;

 			case 'GET_AWARD_TERMINATION':
 				// Get the award termination data
				$award_term_data = $this->db->select("id, type, date_of_award, nature_of_award, addendum_award, award_served_claimaint_on, award_served_respondent_on, date_of_termination, reason_for_termination, factsheet_prepared")
						 ->from('cs_award_term_tbl')
						 ->where('case_no', $data['case_no'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

				return $award_term_data;
 			break;

 			case 'GET_ALL_CLAIMANT_RESPONDENT':
 				// Get all claimant and respondant of case
				$claim_res_data = $this->db->select("*")
						 ->from('cs_claimant_respondant_details_tbl')
						 ->where('case_no', $data['case_no'])
						 ->where('status', 1)
						 ->order_by('count_number', 'ASC')
						 ->get()
						 ->result_array();
				return $claim_res_data;
 			break;

 			case 'GET_FEE_COST_DATA':
 				// Get the fee and cost details
				$fee_cost_data = $this->db->select("id, c_cc_asses_sep, sum_in_dispute, sum_in_dispute_claim, sum_in_dispute_cc, asses_date, total_arb_fees, cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee")
						 ->from('cs_fee_details_tbl')
						 ->where('case_no', $data['case_no'])
						 ->where('status', 1)
						 ->get()
						 ->row_array();

				return $fee_cost_data;
 			break;


 			case 'CASE_ARBITRAL_TRIBUNAL_LIST':

 				$select_column = array("name_of_arbitrator", "whether_on_panel", "at_cat_id", " appointed_by", "date_of_appointment", "date_of_declaration", "arb_terminated", "date_of_termination", "reason_of_termination");
				$order_column = array(null, "name_of_arbitrator", "whether_on_panel", "at_cat_id", " appointed_by", "date_of_appointment", "date_of_declaration", "arb_terminated", null);


				$this->db->select("cat.id, cat.name_of_arbitrator, cat.whether_on_panel,CASE WHEN cat.whether_on_panel = 'yes' THEN 'Yes' WHEN cat.whether_on_panel = 'no' THEN 'No' ELSE '' END as whether_on_panel_desc, cat.at_cat_id, cat.appointed_by, cat.date_of_appointment, cat.date_of_declaration, cat.arb_terminated,CASE WHEN cat.arb_terminated = 'yes' THEN 'Yes' WHEN cat.arb_terminated = 'no' THEN 'No' ELSE '' END as arb_terminated_desc, cat.date_of_termination, cat.reason_of_termination, pct.category_name, gc.description as appointed_by_desc");
				$this->db->from('cs_arbitral_tribunal_tbl as cat');
				$this->db->join('panel_category_tbl as pct', 'pct.id = cat.at_cat_id');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cat.appointed_by');
				$this->db->where('gc.gen_code_group', 'APPOINTED_BY');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'cat.'.$sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR cat.".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('cat.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('cat.status', 1)->where('cat.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = count($fetch_data);

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_arbitral_tribunal_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'CASE_CLAIMANT_LIST':


 				$select_column = array("type", "name", "count_number", " contact", "email", "removed");
				$order_column = array(null, "name", "count_number", " contact", "email", null);


				$this->db->select("crt.id, crt.case_no,crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed, CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name");
				$this->db->from('cs_claimant_respondant_details_tbl as crt');
				$this->db->join('country_master as cm', 'cm.country_code = crt.country', 'left');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no'])->where('type', 'claimant');
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('type', 'claimant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('type', 'claimant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'CASE_RESPONDANT_LIST':

 				$select_column = array("type", "name", "count_number", " contact", "email", "removed");
				$order_column = array(null, "name", "count_number", " contact", "email", null);


				$this->db->select("crt.id, crt.case_no,crt.type, crt.name, crt.count_number, crt.contact, crt.email, crt.flat_no, crt.locality, crt.district, crt.city, crt.state, crt.country, crt.pin, crt.removed, CASE WHEN crt.removed = 0 THEN 'No' ELSE 'Yes' END as removed_desc, cm.country_name");
				$this->db->from('cs_claimant_respondant_details_tbl as crt');
				$this->db->join('country_master as cm', 'cm.country_code = crt.country', 'left');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no'])->where('type', 'respondant');
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('type', 'respondant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('type', 'respondant')->where('status', 1)->select("*")->from('cs_claimant_respondant_details_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_OTHER_PLEADING_LIST':

 				$select_column = array("details", "date_of_filing", "filed_by");
				$order_column = array(null, "date_of_filing", "filed_by");


				$this->db->select("id, case_no, details, date_of_filing, filed_by");
				$this->db->from('cs_other_pleadings_tbl');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_pleadings_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_pleadings_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_OTHER_CORRESPONDANCE_LIST':

 				$select_column = array("details", "date_of_correspondance", "send_by", "sent_to");
				$order_column = array(null, "date_of_filing", null, null);


				$this->db->select("id, case_no, details, date_of_correspondance, send_by, sent_to");
				$this->db->from('cs_other_correspondance_tbl');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1)->where('case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_correspondance_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_other_correspondance_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_FEE_RELEASED_LIST':

 				$select_column = array("date_of_fee_released", "released_to", "mode_of_payment", "details_of_fee_released", "amount");
				$order_column = array("date_of_fee_released", "released_to", "mode_of_payment");


				$this->db->select("fr.id, fr.case_no, fr.date_of_fee_released, fr.released_to, fr.mode_of_payment, fr.details_of_fee_released, fr.amount, gc.description as mop_description");
				$this->db->from('cs_at_fee_released_tbl as fr');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fr.mode_of_payment');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'fr.'.$sc. " LIKE '%".$search_value."%'";
						}
						else{
							$like_clause .= " OR ". 'fr.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('fr.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fr.status', 1)->where('fr.case_no', $data['case_no']);
				$query = $this->db->get(); 
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_at_fee_released_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_at_fee_released_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_COUNSELS_LIST':

 				$select_column = array("name", "appearing_for", "address", "email", "phone", 'date_of_discharge');
				$order_column = array("name", "appearing_for", null, "email", "phone", 'date_of_discharge');


				$this->db->select("ct.id, ct.case_no, ct.enrollment_no, ct.name, ct.appearing_for, ct.address, ct.email, ct.phone, ct.discharge, CASE WHEN ct.discharge = 1 THEN 'Yes' WHEN ct.discharge = 0 THEN 'No' END as discharged, ct.date_of_discharge,  cl_res.name cl_res_name, cl_res.count_number cl_res_count_number");
				$this->db->from('cs_counsels_tbl as ct');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = ct.appearing_for');
				

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'ct.'.$sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".'ct.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('ct.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('ct.status', 1)->where('ct.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_counsels_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_counsels_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_FEE_DEPOSIT_LIST':

 				$select_column = array("date_of_deposit", "deposited_by", "name_of_depositor", "deposited_towards", "mode_of_deposit", "details_of_deposit");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null);


				$this->db->select("fd.id, fd.case_no, fd.date_of_deposit, fd.deposited_by, fd.name_of_depositor, fd.deposited_towards, fd.mode_of_deposit, fd.details_of_deposit, fd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description as mod_description");
				$this->db->from('cs_fee_deposit_tbl as fd');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fd.deposited_by');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fd.deposited_towards');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fd.mode_of_deposit');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('fd.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fd.status', 1)->where('fd.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_deposit_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_deposit_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_COST_DEPOSIT_LIST':

 				$select_column = array("date_of_deposit", "deposited_by", "name_of_depositor", "cost_imposed_dated", "mode_of_deposit", "details_of_deposit");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null);


				$this->db->select("cd.id, cd.case_no, cd.date_of_deposit, cd.deposited_by, cd.name_of_depositor, cd.cost_imposed_dated,cd.mode_of_deposit, cd.details_of_deposit, cd.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc2.description mod_description");
				$this->db->from('cs_cost_deposit_tbl as cd');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = cd.deposited_by');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = cd.mode_of_deposit');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('cd.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('cd.status', 1)->where('cd.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_cost_deposit_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_cost_deposit_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'CASE_FEE_REFUND_LIST':

 				$select_column = array("date_of_refund", "refunded_to", "name_of_party", "refunded_towards", "mode_of_refund", "details_of_refund", "amount");
				$order_column = array("date_of_deposit", null, "name_of_depositor", null, "mode_of_deposit", null, "amount") ;


				$this->db->select("fr.id, fr.case_no, fr.date_of_refund, fr.refunded_to, fr.name_of_party, fr.refunded_towards, fr.mode_of_refund, fr.details_of_refund, fr.amount, cl_res.name cl_res_name, cl_res.count_number cl_res_count_number, gc.description dep_by_description, gc2.description mod_refund");
				$this->db->from('cs_fee_refund_tbl as fr');
				$this->db->join('cs_claimant_respondant_details_tbl as cl_res', 'cl_res.id = fr.refunded_to');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = fr.refunded_towards');
				$this->db->join('gen_code_desc as gc2', 'gc2.gen_code = fr.mode_of_refund');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('fr.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('fr.status', 1)->where('fr.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_refund_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_fee_refund_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_AMOUNT_DETAILS':

 				$output = array(
 					'total_amount' => '',
 					'deposit_amount' => '',
 					'balance' => ''
 				);

 				// Get total amount
				$this->db->select("cs_arb_fees, cs_adminis_fees, rs_arb_fees, rs_adminis_fee, (cs_arb_fees + cs_adminis_fees + rs_arb_fees + rs_adminis_fee) as total_amount");
				$this->db->from('cs_fee_details_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$query = $this->db->get(); 
				$ta_data = $query->row_array();

				// Get fee deposited
				$this->db->select("SUM(amount) as fee_deposit");
				$this->db->from('cs_fee_deposit_tbl');
				$this->db->where('case_no', $data['case_no']);
				$this->db->where('status', 1);
				$query = $this->db->get(); 
				$fd_data = $query->row_array();

				$output['total_amount'] = (isset($ta_data['total_amount']) && !empty($ta_data['total_amount']))? $ta_data['total_amount']: 0;
				$output['deposit_amount'] = (isset($fd_data['fee_deposit']) && !empty($fd_data['fee_deposit']))? $fd_data['fee_deposit']: 0;
				$output['balance'] = $output['total_amount'] - $output['deposit_amount'];

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => 1,
		    		"recordsFiltered" => 1,
		    		"data" => [$output]
		    	);
		    	return $output;
 			break;


 			case 'ALL_PANEL_CATEGORY':

 				$select_column = array("category_name");
				$order_column = array("category_name");


				$this->db->select("id, category_name");
				$this->db->from('panel_category_tbl');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('panel_category_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('panel_category_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'ALL_PURPOSE_CATEGORY':

 				$select_column = array("category_name");
				$order_column = array("category_name");


				$this->db->select("id, category_name");
				$this->db->from('purpose_category_tbl');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('purpose_category_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('purpose_category_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'ALL_POA_LIST':

 				$select_column = array("name", "category_id", "contact_details", "experience", "enrollment_no");
				$order_column = array("name", null, null, null, null) ;


				$this->db->select("poa.id, poa.name, poa.category_id, pc.category_name as category_name, poa.contact_details, poa.email_details, poa.address_details, poa.remarks, poa.experience, poa.enrollment_no");
				$this->db->from('panel_of_arbitrator_tbl as poa');
				$this->db->join('panel_category_tbl as pc', 'pc.id = poa.category_id');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= $sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('poa.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				// If category is set
				if(isset($_POST['category']) && !empty($_POST['category'])){
					$category = $this->security->xss_clean($_POST['category']);
					$this->db->where('poa.category_id', $category);
				}

				// If name is set
				if(isset($_POST['name']) && !empty($_POST['name'])){
					$name = $this->security->xss_clean($_POST['name']);
					$this->db->where("poa.name LIKE '%$name%'");
				}


				$this->db->where('poa.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('panel_of_arbitrator_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('panel_of_arbitrator_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			case 'CASE_NOTING_LIST':

 				$select_column = array("noting", "noting_date", "marked_to", "next_date");
				$order_column = array(null, "marked_to", null, null, "noting_date", "next_date");


				$this->db->select("nt.id, nt.case_no, nt.noting, nt.noting_date, nt.next_date,nt.marked_to, nt.marked_by, um.user_display_name as marked_to_user, um2.user_display_name marked_by_user");
				$this->db->from('cs_noting_tbl as nt');
				$this->db->join('user_master as um', 'um.user_name = nt.marked_to');
				$this->db->join('user_master as um2', 'um2.user_name = nt.marked_by');
				

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'nt.'.$sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".'nt.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('nt.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('nt.status', 1)->where('nt.case_no', $data['case_no']);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_noting_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('case_no', $data['case_no'])->where('status', 1)->select("*")->from('cs_noting_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'CASE_ALLOTMENT_LIST':

 				$select_column = array("case_no");
				$order_column = array("case_no", "alloted_to", null);


				$this->db->select("ca.id, ca.case_no, cdt.case_no as case_no_desc, ca.alloted_to, ca.alloted_by, um.user_display_name as alloted_to_name, DATE_FORMAT(ca.created_at, '%M %d, %Y') as alloted_on");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no');
				$this->db->join('user_master as um', 'um.user_name = ca.alloted_to');
				

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'cdt.'.$sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".'cdt.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('ca.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				/*
					Filters Start ===================
				*/

				// If case number is set
				if(isset($_POST['case_no']) && !empty($_POST['case_no'])){
					$case_no = $this->security->xss_clean($_POST['case_no']);
					$this->db->where('cdt.case_no', $case_no);
				}

				// If allotted to is set
				if(isset($_POST['alloted_to']) && !empty($_POST['alloted_to'])){
					$alloted_to = $this->security->xss_clean($_POST['alloted_to']);
					$this->db->where('ca.alloted_to', $alloted_to);
				}

				/*
					Filters End ===================
				*/

				$this->db->where('ca.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;

 			case 'ALLOTTED_CASE_LIST':

 				$select_column = array("case_no", "alloted_to", "alloted_by");
				$order_column = array("case_no", "alloted_to", null);


				$this->db->select("ca.id, ca.case_no, cdt.case_no as case_no_desc, cdt.case_title as case_title, gc.description as case_status,ca.alloted_to, ca.alloted_by, um.user_display_name as alloted_to_name, DATE_FORMAT(ca.created_at, '%M %d, %Y') as alloted_on");
				$this->db->from('cs_case_allotment_tbl as ca');
				$this->db->join('cs_case_details_tbl as cdt', 'cdt.slug = ca.case_no');
				$this->db->join('user_master as um', 'um.user_name = ca.alloted_to');
				$this->db->join('gen_code_desc as gc', 'gc.gen_code = cdt.case_status');
				

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$search_value = str_replace(['\'', '"'], ['', ''], $this->security->xss_clean($_POST['search']['value']));

					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'ca.'.$sc. " LIKE '%".$search_value."%'";	
						}
						else{
							$like_clause .= " OR ".'ca.'.$sc. " LIKE '%".$search_value."%'";
						}
						$count++;
					}
					$like_clause .= ")";
					$this->db->where($like_clause);
				}

				if(isset($_POST["order"])){
					$this->db->order_by($order_column[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
				}
				else{
					$this->db->order_by('ca.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				$this->db->where('ca.status', 1);
				$query = $this->db->get();
				$fetch_data = $query->result();

		    	// Filter records
		 		$recordsFiltered = $this->db->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

		 		// Records total
		 		$recordsTotal = $this->db->where('status', 1)->select("*")->from('cs_case_allotment_tbl')->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);

		    	return $output;
 			break;


 			default:
					return array('status'=>false, 'msg'=>NO_OPERATION);
 		}
 	}

 	public function post($data, $op){
 		switch($op){
 			case 'ADD_ROOM':

 				$this->form_validation->set_rules('room_name', 'Room Name', 'required|regex_match[/^[a-zA-Z\s\']+$/]|xss_clean');
 				$this->form_validation->set_rules('room_no', 'Room Number', 'required|regex_match[/^[0-9]+$/]|numeric|xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$room_name = $this->security->xss_clean($data['room_name']);

	 					$insert_data = array(
	 						'room_name' => $room_name,
	 						'room_no' => $this->security->xss_clean($data['room_no']),
	 						'created_at' => $this->date
	 					);

	 					$result = $this->db->insert('rooms_tbl', $insert_data);
	 					if($result){

							 $table_id = $this->db->insert_id();
							 
							// Insert room id into display board table
							$insert_display_board_data = array(
								'room_id' => $table_id,
								'room_status' => 'Not In Session',
								'created_by' => $this->user_name,
								'created_at' => $this->date
							);
   
							$display_board_result = $this->db->insert('cs_display_board_tbl', $insert_display_board_data);

							if($display_board_result){
								// Update the data logs table for data tracking
								$table_name = 'rooms_tbl';
								$message = 'A new room '. $room_name .' is added in rooms list.';
								$this->update_data_logs($table_name, $table_id, $message);
   
								$this->db->trans_commit();
								$dbstatus = true;
								$dbmessage = "Record saved successfully";
							}
							else{
								$this->db->trans_rollback();
								$dbstatus = false;
								$dbmessage = "Error while saving  data. Please contact support.";
							}
							
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

 			case 'EDIT_ROOM':

 				$this->form_validation->set_rules('room_name', 'Room Name', 'required|regex_match[/^[a-zA-Z\s\']+$/]|xss_clean');
 				$this->form_validation->set_rules('room_no', 'Room Number', 'required|numeric|regex_match[/^[0-9]+$/]|xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$room_name = $this->security->xss_clean($data['room_name']);

	 					$update_data = array(
	 						'room_name' => $room_name,
	 						'room_no' => $this->security->xss_clean($data['room_no']),
	 						'updated_at' => $this->date
	 					);

	 					$id = $this->security->xss_clean($data['hidden_id']);
	 					$result = $this->db->where('id',$id)->update('rooms_tbl', $update_data);
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'rooms_tbl';
	 						$table_id = $id;
	 						$message = 'Details of room '. $room_name .' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_ROOM': 
				$this->db->trans_begin();
				$this->form_validation->set_rules('id', 'Room Id', 'required');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('rooms_tbl', array('active_status' => 0));

					if($r){

						// Delete room from display board table also
						$delete_dbr_result = $this->db->from('cs_display_board_tbl')->where('room_id', $id)->delete();
						if($delete_dbr_result){
							// Update the data logs table for data tracking
							$data = $this->db->select('id, room_name')->from('rooms_tbl')->where('id', $id)->get()->row_array();
							$table_name = 'rooms_tbl';
							$table_id = $id;
							$message = 'Room '. $data['room_name'] .' is deleted.';
							$this->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();

							$dbstatus = TRUE;
							$dbmessage = 'Record deleted successfully';
						}
						else{
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Error while saving data. Please try again.';
						}
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_CAUSE_LIST_FORM':
				$this->form_validation->set_rules('case_no', 'Case number', 'required|xss_clean|regex_match[/^[a-zA-Z0-9\/_-]+$/]');
 				$this->form_validation->set_rules('title_of_case', 'Title of case', 'required|xss_clean|trim|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('arbitrator_name', 'Arbitrator name', 'required|xss_clean|trim|regex_match[/^[a-zA-Z\s.\'`]+$/]');
 				$this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
 				$this->form_validation->set_rules('time_from', 'Time (From)', 'xss_clean');
 				$this->form_validation->set_rules('time_to', 'Time (To)', 'xss_clean');
 				$this->form_validation->set_rules('purpose', 'Purpose', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('room_no', 'Room number', 'xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['case_no']);

	 					$insert_data = array(
	 						'case_no' => $case_no,
	 						'title_of_case' => $this->security->xss_clean($data['title_of_case']),
	 						'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
	 						'date' => $this->security->xss_clean($data['date']),
	 						'time_from' => $this->security->xss_clean($data['time_from']),
	 						'time_to' => $this->security->xss_clean($data['time_to']),
	 						'purpose_cat_id' => $this->security->xss_clean($data['purpose']),
	 						'room_no_id' => $this->security->xss_clean($data['room_no']),
	 						'remarks' => $this->security->xss_clean($data['remarks']),
	 						'created_at' => $this->date,
	 						'created_by' => $this->user_name
	 					);

	 					$result = $this->db->insert('cause_list_tbl', $insert_data);
	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$table_name = 'cause_list_tbl';
	 						$message = 'A new cause list for case '.$case_no.' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CAUSE_LIST_FORM':
				$this->form_validation->set_rules('case_no', 'Case number', 'required|xss_clean|regex_match[/^[a-zA-Z0-9\/_-]+$/]');
 				$this->form_validation->set_rules('title_of_case', 'Title of case', 'required|xss_clean|trim|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('arbitrator_name', 'Arbitrator name', 'required|xss_clean|trim|regex_match[/^[a-zA-Z\s.\'`]+$/]');
 				$this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
 				$this->form_validation->set_rules('time_from', 'Time (From)', 'xss_clean');
 				$this->form_validation->set_rules('time_to', 'Time (To)', 'xss_clean');
 				$this->form_validation->set_rules('purpose', 'Purpose', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('room_no', 'Room number', 'xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$id = $this->security->xss_clean($data['hidden_id']);
	 					$case_no = $this->security->xss_clean($data['case_no']);

	 					$update_data = array(
	 						'case_no' => $case_no,
	 						'title_of_case' => $this->security->xss_clean($data['title_of_case']),
	 						'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
	 						'date' => $this->security->xss_clean($data['date']),
	 						'time_from' => $this->security->xss_clean($data['time_from']),
	 						'time_to' => $this->security->xss_clean($data['time_to']),
	 						'purpose_cat_id' => $this->security->xss_clean($data['purpose']),
	 						'room_no_id' => $this->security->xss_clean($data['room_no']),
	 						'remarks' => $this->security->xss_clean($data['remarks']),
	 						'updated_at' => $this->date,
	 						'updated_by' => $this->user_name
	 					);

	 					$result = $this->db->where('id', $id)->update('cause_list_tbl', $update_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'cause_list_tbl';
	 						$table_id = $id;
	 						$message = 'Details of cause list for case '.$case_no.' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CAUSE_LIST': 

				$this->form_validation->set_rules('id', 'Cause List Id', 'required');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cause_list_tbl', array('active_status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, case_no')->from('cause_list_tbl')->where('id', $id)->get()->row_array();
 						$table_name = 'cause_list_tbl';
 						$table_id = $id;
 						$message = 'Cause list for case '.$data['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_CASE_DETAILS':

				// Case details
				$this->form_validation->set_rules('cd_case_no', 'Case number', 'required|is_unique[cs_case_details_tbl.case_no]|xss_clean|regex_match[/^[a-zA-Z0-9\/_-]+$/]');
				$this->form_validation->set_rules('cd_case_title', 'Case title', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('cd_reffered_on', 'Reffered on', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_reffered_by', 'Reffered by (Direct/Court)', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_reffered_by_judge', 'Reffered by (Judge/Justice)', 'xss_clean|regex_match[/^[a-zA-Z\s,\'`.]+$/]');
 				$this->form_validation->set_rules('cd_case_arb_pet', 'Arbitration Petition', 'xss_clean');
 				$this->form_validation->set_rules('cd_name_of_court', 'Name of court', 'xss_clean|regex_match[/^[a-zA-Z0-9\s`\',._-]+$/]');
 				$this->form_validation->set_rules('cd_name_of_judge', 'Name of judge', 'xss_clean|regex_match[/^[a-zA-Z\s,\'`.]+$/]');
 				$this->form_validation->set_rules('cd_registered_on', 'Registered on', 'xss_clean');
 				$this->form_validation->set_rules('cd_recieved_on', 'Recieved on', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_toa', 'Type of arbitration', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_status', 'Status', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_remakrs', 'Remarks', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['cd_case_no']);
	 					$slug = md5($case_no);

	 					$case_details_data = array(
	 						'slug' => $slug,
	 						'case_no' => $case_no,
	 						'case_title' => $this->security->xss_clean($data['cd_case_title']),
	 						'reffered_on' => $this->security->xss_clean($data['cd_reffered_on']),
	 						'reffered_by' => $this->security->xss_clean($data['cd_reffered_by']),
	 						'reffered_by_judge' => $this->security->xss_clean($data['cd_reffered_by_judge']),
	 						'arbitration_petition' => $this->security->xss_clean($data['cd_case_arb_pet']),
	 						'name_of_court' => $this->security->xss_clean($data['cd_name_of_court']),
	 						'name_of_judge' => $this->security->xss_clean($data['cd_name_of_judge']),
	 						'recieved_on' => $this->security->xss_clean($data['cd_recieved_on']),
	 						'registered_on' => $this->security->xss_clean($data['cd_registered_on']),
	 						'type_of_arbitration' => $this->security->xss_clean($data['cd_toa']),
	 						'case_status' => $this->security->xss_clean($data['cd_status']),
	 						'remarks' => $this->security->xss_clean($data['cd_remarks']),
	 						'created_by' => $this->user_name,
	 						'created_on' => $this->date
	 					);

	 					// Insert case details
	 					$result = $this->db->insert('cs_case_details_tbl', $case_details_data);
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'cs_case_details_tbl';
	 						$table_id = $this->db->insert_id();
	 						$message = 'A new case '.$case_no.' is added.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record saved successfully',
		 						'redirect_url' => base_url().'add-new-case/'.$slug
		 					);

	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;

			case 'EDIT_CASE_DETAILS':

				// Case details
				$this->form_validation->set_rules('hidden_case', 'Case SLug', 'required|xss_clean');
				$this->form_validation->set_rules('cd_case_no', 'Case number', 'xss_clean');
				$this->form_validation->set_rules('cd_case_title', 'Case title', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('cd_reffered_on', 'Reffered on', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_reffered_by', 'Reffered by (Direct/Court)', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_reffered_by_judge', 'Reffered by (Judge/Justice)', 'xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
 				$this->form_validation->set_rules('cd_case_arb_pet', 'Arbitration Petition', 'xss_clean');
 				$this->form_validation->set_rules('cd_name_of_court', 'Name of court', 'xss_clean');
 				$this->form_validation->set_rules('cd_name_of_judge', 'Name of judge', 'xss_clean|regex_match[/^[a-zA-Z\s,]+$/]');
 				$this->form_validation->set_rules('cd_recieved_on', 'Recieved on', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_registered_on', 'Registered on', 'xss_clean');
 				$this->form_validation->set_rules('cd_toa', 'Type of arbitration', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_status', 'Status', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_remakrs', 'Remarks', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_slug = $this->security->xss_clean($data['hidden_case']);

	 					$case_details_data = array(
	 						'case_title' => $this->security->xss_clean($data['cd_case_title']),
	 						'reffered_on' => $this->security->xss_clean($data['cd_reffered_on']),
	 						'reffered_by' => $this->security->xss_clean($data['cd_reffered_by']),
	 						'reffered_by_judge' => $this->security->xss_clean($data['cd_reffered_by_judge']),
	 						'arbitration_petition' => $this->security->xss_clean($data['cd_case_arb_pet']),
	 						'name_of_court' => $this->security->xss_clean($data['cd_name_of_court']),
	 						'name_of_judge' => $this->security->xss_clean($data['cd_name_of_judge']),
	 						'recieved_on' => $this->security->xss_clean($data['cd_recieved_on']),
	 						'registered_on' => $this->security->xss_clean($data['cd_registered_on']),
	 						'type_of_arbitration' => $this->security->xss_clean($data['cd_toa']),
	 						'case_status' => $this->security->xss_clean($data['cd_status']),
	 						'remarks' => $this->security->xss_clean($data['cd_remarks']),
	 						'updated_by' => $this->user_name,
	 						'updated_on' => $this->date
	 					);

	 					// Update case details
	 					$result = $this->db->where('slug', $case_slug)->update('cs_case_details_tbl', $case_details_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_slug)->get()->row_array();

	 						$table_name = 'cs_case_details_tbl';
	 						$table_id = $data['id'];
	 						$message = 'Details of case '.$data['case_no'].' is updated.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record updated successfully',
		 						'redirect_url' => base_url().'add-new-case/'.$case_slug
		 					);
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;

			case 'DELETE_CASE': 

				$this->form_validation->set_rules('id', 'Case Id', 'required');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cs_case_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('id', $id)->get()->row_array();

 						$table_name = 'cs_case_details_tbl';
 						$table_id = $data['id'];
 						$message = 'Case '.$data['case_no'].' is deleted.';

 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_CLAIMANT_DETAILS':

				// Claimant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
 				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\s]+$/]');
 				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('car_flat_no', 'House/flat no.', 'xss_clean');
 				$this->form_validation->set_rules('car_locality', 'Locality', 'xss_clean');
 				$this->form_validation->set_rules('car_district', 'District', 'xss_clean');
 				$this->form_validation->set_rules('car_city', 'City', 'xss_clean');
 				$this->form_validation->set_rules('car_state', 'State', 'xss_clean');
 				$this->form_validation->set_rules('car_country', 'Country', 'xss_clean');
 				$this->form_validation->set_rules('car_pin', 'Pin', 'xss_clean');
 				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$claimant_name = $this->security->xss_clean($data['car_name']);
	 					$claimant_count = $this->security->xss_clean($data['car_number']);

	 					$claimant_details_data = array(
	 						'case_no' => $case_no,
	 						'type' => $this->security->xss_clean($data['hidden_car_type']),
	 						'name' => $claimant_name,
	 						'count_number' => $claimant_count,
	 						'contact' => $this->security->xss_clean($data['car_contact_no']),
	 						'email' => $this->security->xss_clean($data['car_email']),
	 						'flat_no' => $this->security->xss_clean($data['car_flat_no']),
	 						'locality' => $this->security->xss_clean($data['car_locality']),
	 						'district' => $this->security->xss_clean($data['car_district']),
	 						'city' => $this->security->xss_clean($data['car_city']),
	 						'state' => $this->security->xss_clean($data['car_state']),
	 						'country' => $this->security->xss_clean($data['car_country']),
	 						'pin' => $this->security->xss_clean($data['car_pin']),
	 						'removed' => $this->security->xss_clean($data['car_removed']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_on' => $this->date
	 					);

	 					// Insert Claimant details
	 					$result = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);
	 					if($result){

	 						$table_id = $this->db->insert_id();

	 						// Update the data logs table for data tracking
	 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

	 						$table_name = 'cs_claimant_respondant_details_tbl';
	 						$message = 'A new claimant '. $claimant_name . ' ('.$claimant_count.') ' .' of case '.$data['case_no'].' is added.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record saved successfully'
		 					);
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;

			case 'EDIT_CLAIMANT_DETAILS':

				// Claimant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_id', 'Id', 'required|xss_clean');

				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
 				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\s]+$/]');
 				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('car_flat_no', 'House/flat no.', 'xss_clean');
 				$this->form_validation->set_rules('car_locality', 'Locality', 'xss_clean');
 				$this->form_validation->set_rules('car_district', 'District', 'xss_clean');
 				$this->form_validation->set_rules('car_city', 'City', 'xss_clean');
 				$this->form_validation->set_rules('car_state', 'State', 'xss_clean');
 				$this->form_validation->set_rules('car_country', 'Country', 'xss_clean');
 				$this->form_validation->set_rules('car_pin', 'Pin', 'xss_clean');
 				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$car_id = $this->security->xss_clean($data['hidden_car_id']);
	 					$claimant_name = $this->security->xss_clean($data['car_name']);
	 					$claimant_count = $this->security->xss_clean($data['car_number']);

	 					$claimant_details_data = array(
	 						'type' => $this->security->xss_clean($data['hidden_car_type']),
	 						'name' => $claimant_name,
	 						'count_number' => $claimant_count,
	 						'contact' => $this->security->xss_clean($data['car_contact_no']),
	 						'email' => $this->security->xss_clean($data['car_email']),
	 						'flat_no' => $this->security->xss_clean($data['car_flat_no']),
	 						'locality' => $this->security->xss_clean($data['car_locality']),
	 						'district' => $this->security->xss_clean($data['car_district']),
	 						'city' => $this->security->xss_clean($data['car_city']),
	 						'state' => $this->security->xss_clean($data['car_state']),
	 						'country' => $this->security->xss_clean($data['car_country']),
	 						'pin' => $this->security->xss_clean($data['car_pin']),
	 						'removed' => $this->security->xss_clean($data['car_removed']),
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_on' => $this->date
	 					);

	 					// Insert Claimant details
	 					$result = $this->db->where('case_no', $case_no)->where('id', $car_id)->update('cs_claimant_respondant_details_tbl', $claimant_details_data);
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

	 						$table_name = 'cs_claimant_respondant_details_tbl';
	 						$table_id = $car_id;
	 						$message = 'Details of claimant '. $claimant_name . ' ('.$claimant_count.') ' .' of case '.$data['case_no'].' is updated.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record updated successfully'
		 					);
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;

			case 'DELETE_CLAIMANT': 

				$this->form_validation->set_rules('id', 'Claimant Id', 'required');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cs_claimant_respondant_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$car_data = $this->db->select('id, name, count_number, case_no')->from('cs_claimant_respondant_details_tbl')->where('id', $id)->get()->row_array();

 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $car_data['case_no'])->get()->row_array();

 						$table_name = 'cs_claimant_respondant_details_tbl';
 						$table_id = $id;
 						$message = 'Claimant '. $car_data['name'] . ' ('.$car_data['count_number'].') ' .' of case '.$data['case_no'].' is deleted.';

 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_RESPONDANT_DETAILS':

				// Respondant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
 				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\s]+$/]');
 				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('car_flat_no', 'House/flat no.', 'xss_clean');
 				$this->form_validation->set_rules('car_locality', 'Locality', 'xss_clean');
 				$this->form_validation->set_rules('car_district', 'District', 'xss_clean');
 				$this->form_validation->set_rules('car_city', 'City', 'xss_clean');
 				$this->form_validation->set_rules('car_state', 'State', 'xss_clean');
 				$this->form_validation->set_rules('car_country', 'Country', 'xss_clean');
 				$this->form_validation->set_rules('car_pin', 'Pin', 'xss_clean');
 				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$respondent_name = $this->security->xss_clean($data['car_name']);
	 					$count_number = $this->security->xss_clean($data['car_number']);
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);

	 					$claimant_details_data = array(
	 						'case_no' => $case_no,
	 						'type' => $this->security->xss_clean($data['hidden_car_type']),
	 						'name' => $respondent_name,
	 						'count_number' => $count_number,
	 						'contact' => $this->security->xss_clean($data['car_contact_no']),
	 						'email' => $this->security->xss_clean($data['car_email']),
	 						'flat_no' => $this->security->xss_clean($data['car_flat_no']),
	 						'locality' => $this->security->xss_clean($data['car_locality']),
	 						'district' => $this->security->xss_clean($data['car_district']),
	 						'city' => $this->security->xss_clean($data['car_city']),
	 						'state' => $this->security->xss_clean($data['car_state']),
	 						'country' => $this->security->xss_clean($data['car_country']),
	 						'pin' => $this->security->xss_clean($data['car_pin']),
	 						'removed' => $this->security->xss_clean($data['car_removed']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_on' => $this->date
	 					);

	 					// Insert Claimant details
	 					$result = $this->db->insert('cs_claimant_respondant_details_tbl', $claimant_details_data);
	 					if($result){

	 						$table_id = $this->db->insert_id();

	 						// Update the data logs table for data tracking
	 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

	 						$table_name = 'cs_claimant_respondant_details_tbl';
	 						$message = 'A new respondent '. $respondent_name . ' ('.$count_number.') ' .' of case '.$data['case_no'].' is added.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record saved successfully'
		 					);
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;

			case 'EDIT_RESPONDANT_DETAILS':

				// Respondant details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_car_id', 'Id', 'required|xss_clean');

				$this->form_validation->set_rules('hidden_car_type', 'Type', 'required|xss_clean');
 				$this->form_validation->set_rules('car_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('car_number', 'Number', 'xss_clean|regex_match[/^[a-zA-Z0-9\s]+$/]');
 				$this->form_validation->set_rules('car_contact_no', 'Contact number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('car_email', 'Email id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('car_flat_no', 'House/flat no.', 'xss_clean');
 				$this->form_validation->set_rules('car_locality', 'Locality', 'xss_clean');
 				$this->form_validation->set_rules('car_district', 'District', 'xss_clean');
 				$this->form_validation->set_rules('car_city', 'City', 'xss_clean');
 				$this->form_validation->set_rules('car_state', 'State', 'xss_clean');
 				$this->form_validation->set_rules('car_country', 'Country', 'xss_clean');
 				$this->form_validation->set_rules('car_pin', 'Pin', 'xss_clean');
 				$this->form_validation->set_rules('car_removed', 'Removed or not', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$car_id = $this->security->xss_clean($data['hidden_car_id']);
	 					$respondent_name = $this->security->xss_clean($data['car_name']);
	 					$count_number = $this->security->xss_clean($data['car_number']);

	 					$claimant_details_data = array(
	 						'type' => $this->security->xss_clean($data['hidden_car_type']),
	 						'name' => $respondent_name,
	 						'count_number' => $count_number,
	 						'contact' => $this->security->xss_clean($data['car_contact_no']),
	 						'email' => $this->security->xss_clean($data['car_email']),
	 						'flat_no' => $this->security->xss_clean($data['car_flat_no']),
	 						'locality' => $this->security->xss_clean($data['car_locality']),
	 						'district' => $this->security->xss_clean($data['car_district']),
	 						'city' => $this->security->xss_clean($data['car_city']),
	 						'state' => $this->security->xss_clean($data['car_state']),
	 						'country' => $this->security->xss_clean($data['car_country']),
	 						'pin' => $this->security->xss_clean($data['car_pin']),
	 						'removed' => $this->security->xss_clean($data['car_removed']),
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_on' => $this->date
	 					);

	 					// Insert Claimant details
	 					$result = $this->db->where('case_no', $case_no)->where('id', $car_id)->update('cs_claimant_respondant_details_tbl', $claimant_details_data);
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $case_no)->get()->row_array();

	 						$table_name = 'cs_claimant_respondant_details_tbl';
	 						$table_id = $car_id;
	 						$message = 'Details of respondent '. $respondent_name . ' ('.$count_number.') ' .' of case '.$data['case_no'].' is updated.';

	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$output = array(
		 						'status' => true,
		 						'msg' => 'Record updated successfully'
		 					);
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$output = array(
		 						'status' => false,
		 						'msg' => 'Something went wrong'
		 					);
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$output = array(
	 						'status' => false,
	 						'msg' => 'Something went wrong'
	 					);
	 				}
 				}
 				else{
 					$output = array(
 						'status' => 'validationerror',
 						'msg' => validation_errors()
 					);
 				}
 				return $output;
			break;


			case 'DELETE_RESPONDANT': 

				$this->form_validation->set_rules('id', 'Respondant Id', 'required');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cs_claimant_respondant_details_tbl', array('status' => 0, 'updated_on' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$car_data = $this->db->select('id, name, count_number, case_no')->from('cs_claimant_respondant_details_tbl')->where('id', $id)->get()->row_array();

 						$data = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $car_data['case_no'])->get()->row_array();

 						$table_name = 'cs_claimant_respondant_details_tbl';
 						$table_id = $id;
 						$message = 'Respondent '. $car_data['name'] . ' ('.$car_data['count_number'].') ' .' of case '.$data['case_no'].' is deleted.';

 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_STATUS_PLEADINGS':

				// Status of pleadings
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean|is_unique[cs_status_of_pleadings_tbl.case_no]');
 				$this->form_validation->set_rules('claim_invited_on', 'Claim invited on', 'xss_clean');
 				$this->form_validation->set_rules('rem_to_claim', 'Reminder to claim', 'xss_clean');
 				$this->form_validation->set_rules('rem_to_claim_2', 'Reminder to claim 2', 'xss_clean');
 				$this->form_validation->set_rules('claim_filed_on', 'Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('res_served_on', 'Respondent served on', 'xss_clean');
 				$this->form_validation->set_rules('sod_filed_on', 'Statement of defence filed on', 'xss_clean');
 				$this->form_validation->set_rules('p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');

 				$this->form_validation->set_rules('no_of_dod', 'Number of days of delay', 'numeric|xss_clean');
 				$this->form_validation->set_rules('sop_rejoin_sod_filed_on', 'Rejoinder to Statement of Defence filed on', 'xss_clean');
 				$this->form_validation->set_rules('counter_claim', 'Counter claim', 'xss_clean');
 				$this->form_validation->set_rules('sop_dof_cc', 'Date of filing of Counter Claim', 'xss_clean');
 				$this->form_validation->set_rules('sop_rt_cc_filed_on', 'Reply to Counter Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_cc_p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');
 				$this->form_validation->set_rules('sop_cc_no_of_dod', 'Number of days of delay', 'numeric|xss_clean');
 				$this->form_validation->set_rules('sop_rej_cc_filed_on', 'Rejoinder to Reply of Counter Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_app_act', 'Application under Section 17 of A&C Act', 'xss_clean');
 				$this->form_validation->set_rules('sop_dof_app', 'Date of filing of Application', 'xss_clean');
 				$this->form_validation->set_rules('sop_rep_app_filed_on', 'Reply to the Application filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_remarks', 'Remarks', 'xss_clean');

 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$case_details_data = array(
	 						'case_no' => $case_no,
	 						'claim_invited_on' => $this->security->xss_clean($data['claim_invited_on']),
	 						'rem_to_claim_on' => $this->security->xss_clean($data['rem_to_claim']),
	 						'rem_to_claim_on_2' => '',
	 						'claim_filed_on' => $this->security->xss_clean($data['claim_filed_on']),
	 						'res_served_on' => $this->security->xss_clean($data['res_served_on']),
	 						'sod_filed_on' => $this->security->xss_clean($data['sod_filed_on']),
	 						'sod_pol' => $this->security->xss_clean($data['sod_p_of_limitation']),
	 						'sod_dod' => '',
	 						'rej_stat_def_filed_on' => $this->security->xss_clean($data['sop_rejoin_sod_filed_on']),
	 						'counter_claim' => $this->security->xss_clean($data['counter_claim']),
	 						'dof_counter_claim' => '',
	 						'reply_counter_claim_on' => '',
	 						'reply_counter_claim_pol' => '',
	 						'reply_counter_claim_dod' => '',
	 						'rej_reply_counter_claim_on' => '',
	 						'app_section' => $this->security->xss_clean($data['sop_app_act']),
	 						'dof_app' => '',
	 						'reply_app_on' => '',
	 						'remarks' => $this->security->xss_clean($data['sop_remarks']),
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					if(isset($data['rem_to_claim_2']) && !empty($data['rem_to_claim_2'])){
	 						$case_details_data['rem_to_claim_on_2'] = $this->security->xss_clean($data['rem_to_claim_2']);
	 					}

	 					if($data['sod_p_of_limitation'] == 'yes'){
							$case_details_data['sod_dod'] = $this->security->xss_clean($data['sod_no_of_dod']);
	 					}

	 					if($data['counter_claim'] == 'yes'){
	 						$case_details_data['dof_counter_claim'] = $this->security->xss_clean($data['sop_dof_cc']);
	 						$case_details_data['reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rt_cc_filed_on']);
	 						$case_details_data['reply_counter_claim_pol'] = $this->security->xss_clean($data['sop_cc_p_of_limitation']);
	 						$case_details_data['reply_counter_claim_dod'] = $this->security->xss_clean($data['sop_cc_no_of_dod']);
	 						$case_details_data['rej_reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rej_cc_filed_on']);
	 					}

	 					if($data['sop_app_act'] == 'yes'){
	 						$case_details_data['dof_app'] = $this->security->xss_clean($data['sop_dof_app']);
	 						$case_details_data['reply_app_on'] = $this->security->xss_clean($data['sop_rep_app_filed_on']);
	 					}
	 					

	 					// Insert case details
	 					$result = $this->db->insert('cs_status_of_pleadings_tbl', $case_details_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_status_of_pleadings_tbl';
	 						$message = 'Status of pleadings of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

 							$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
 						}
 						else{
 							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.";
 						}
	 					

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'EDIT_CASE_STATUS_PLEADINGS':

				// Status of pleadings
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_sop_id', 'Status of pleadings id', 'required|xss_clean');
 				$this->form_validation->set_rules('claim_invited_on', 'Claim invited on', 'xss_clean');
 				$this->form_validation->set_rules('rem_to_claim', 'Reminder to claim', 'xss_clean');
 				$this->form_validation->set_rules('rem_to_claim_2', 'Reminder to claim 2', 'xss_clean');
 				$this->form_validation->set_rules('claim_filed_on', 'Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('res_served_on', 'Respondent served on', 'xss_clean');
 				$this->form_validation->set_rules('sod_filed_on', 'Statement of defence filed on', 'xss_clean');
 				$this->form_validation->set_rules('p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');

 				$this->form_validation->set_rules('no_of_dod', 'Number of days of delay', 'xss_clean');
 				$this->form_validation->set_rules('sop_rejoin_sod_filed_on', 'Rejoinder to Statement of Defence filed on', 'xss_clean');
 				$this->form_validation->set_rules('counter_claim', 'Counter claim', 'xss_clean');
 				$this->form_validation->set_rules('sop_dof_cc', 'Date of filing of Counter Claim', 'xss_clean');
 				$this->form_validation->set_rules('sop_rt_cc_filed_on', 'Reply to Counter Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_cc_p_of_limitation', 'Whether filed beyond period of limitation', 'xss_clean');
 				$this->form_validation->set_rules('sop_cc_no_of_dod', 'Number of days of delay', 'xss_clean');
 				$this->form_validation->set_rules('sop_rej_cc_filed_on', 'Rejoinder to Reply of Counter Claim filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_app_act', 'Application under Section 17 of A&C Act', 'xss_clean');
 				$this->form_validation->set_rules('sop_dof_app', 'Date of filing of Application', 'xss_clean');
 				$this->form_validation->set_rules('sop_rep_app_filed_on', 'Reply to the Application filed on', 'xss_clean');
 				$this->form_validation->set_rules('sop_remarks', 'Remarks', 'xss_clean');

 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$sop_id = $this->security->xss_clean($data['hidden_sop_id']);

	 					$case_details_data = array(
	 						'claim_invited_on' => $this->security->xss_clean($data['claim_invited_on']),
	 						'rem_to_claim_on' => $this->security->xss_clean($data['rem_to_claim']),
	 						'rem_to_claim_on_2' => '',
	 						'claim_filed_on' => $this->security->xss_clean($data['claim_filed_on']),
	 						'res_served_on' => $this->security->xss_clean($data['res_served_on']),
	 						'sod_filed_on' => $this->security->xss_clean($data['sod_filed_on']),
	 						'sod_pol' => $this->security->xss_clean($data['sod_p_of_limitation']),
	 						'sod_dod' => '',
	 						'rej_stat_def_filed_on' => $this->security->xss_clean($data['sop_rejoin_sod_filed_on']),
	 						'counter_claim' => $this->security->xss_clean($data['counter_claim']),
	 						'dof_counter_claim' => '',
	 						'reply_counter_claim_on' => '',
	 						'reply_counter_claim_pol' => '',
	 						'reply_counter_claim_dod' => '',
	 						'rej_reply_counter_claim_on' => '',
	 						'app_section' => $this->security->xss_clean($data['sop_app_act']),
	 						'dof_app' => '',
	 						'reply_app_on' => '',
	 						'remarks' => $this->security->xss_clean($data['sop_remarks']),
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					if(isset($data['rem_to_claim_2']) && !empty($data['rem_to_claim_2'])){
	 						$case_details_data['rem_to_claim_on_2'] = $this->security->xss_clean($data['rem_to_claim_2']);
	 					}

	 					if($data['sod_p_of_limitation'] == 'yes'){
							$case_details_data['sod_dod'] = $this->security->xss_clean($data['sod_no_of_dod']);
	 					}

	 					if($data['counter_claim'] == 'yes'){
	 						$case_details_data['dof_counter_claim'] = $this->security->xss_clean($data['sop_dof_cc']);
	 						$case_details_data['reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rt_cc_filed_on']);
	 						$case_details_data['reply_counter_claim_pol'] = $this->security->xss_clean($data['sop_cc_p_of_limitation']);
	 						$case_details_data['reply_counter_claim_dod'] = $this->security->xss_clean($data['sop_cc_no_of_dod']); // condition
	 						$case_details_data['rej_reply_counter_claim_on'] = $this->security->xss_clean($data['sop_rej_cc_filed_on']);
	 					}

	 					if($data['sop_app_act'] == 'yes'){
	 						$case_details_data['dof_app'] = $this->security->xss_clean($data['sop_dof_app']);
	 						$case_details_data['reply_app_on'] = $this->security->xss_clean($data['sop_rep_app_filed_on']);
	 					}
	 					

	 					// Insert case details
	 					$result = $this->db->where('id' , $sop_id)->update('cs_status_of_pleadings_tbl', $case_details_data);

	 					
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_status_of_pleadings_tbl';
	 						$table_id = $sop_id;
	 						$message = 'Details of status of pleadings of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
 						}
 						else{
 							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.";
 						}
	 					

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_CASE_ARBITRAL_TRIBUNAL':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('at_arb_name', 'Arbitrator name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
 				$this->form_validation->set_rules('at_whe_on_panel', 'Wheather on panel', 'required|xss_clean');
 				$this->form_validation->set_rules('at_category', 'Category', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('at_appointed_by', 'Appointed by', 'required|xss_clean');
 				$this->form_validation->set_rules('at_doa', 'Date of appointment', 'required|xss_clean');
 				$this->form_validation->set_rules('at_dod', 'Date of declaration', 'required|xss_clean');

 				$this->form_validation->set_rules('at_terminated', 'Terminated or not', 'required|xss_clean');
 				$this->form_validation->set_rules('at_dot', 'Date of termination', 'xss_clean');
 				$this->form_validation->set_rules('at_rot', 'Reason for termination', 'xss_clean');

 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$name_of_arbitrator = $this->security->xss_clean($data['at_arb_name']);

	 					$at_data = array(
	 						'case_no' => $case_no,
	 						'name_of_arbitrator' => $name_of_arbitrator,
	 						'whether_on_panel' => $this->security->xss_clean($data['at_whe_on_panel']),
	 						'at_cat_id' => $this->security->xss_clean($data['at_category']),
	 						'appointed_by' => $this->security->xss_clean($data['at_appointed_by']),
	 						'date_of_appointment' => $this->security->xss_clean($data['at_doa']),
	 						'date_of_declaration' => $this->security->xss_clean($data['at_dod']),
	 						'arb_terminated' => $this->security->xss_clean($data['at_terminated']),
	 						'date_of_termination' => '',
	 						'reason_of_termination' => '',
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					if(isset($data['at_terminated']) && $data['at_terminated'] == 'yes'){
	 						$at_data['date_of_termination'] = $this->security->xss_clean($data['at_dot']);
	 						$at_data['reason_of_termination'] = $this->security->xss_clean($data['at_rot']);
	 					}

	 					// Insert case details
	 					$result = $this->db->insert('cs_arbitral_tribunal_tbl', $at_data);
	 					if($result){

	 						$table_id = $this->db->insert_id();

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_arbitral_tribunal_tbl';
	 						$message = 'A new arbitrator '. $name_of_arbitrator .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);


	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_ARBITRAL_TRIBUNAL':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('at_arb_name', 'Arbitrator name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
 				$this->form_validation->set_rules('at_whe_on_panel', 'Wheather on panel', 'required|xss_clean');
 				$this->form_validation->set_rules('at_category', 'Category', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('at_appointed_by', 'Appointed by', 'required|xss_clean');
 				$this->form_validation->set_rules('at_doa', 'Date of appointment', 'required|xss_clean');
 				$this->form_validation->set_rules('at_dod', 'Date of declaration', 'required|xss_clean');

 				$this->form_validation->set_rules('at_terminated', 'Terminated or not', 'required|xss_clean');
 				$this->form_validation->set_rules('at_dot', 'Date of termination', 'xss_clean');
 				$this->form_validation->set_rules('at_rot', 'Reason for termination', 'xss_clean');

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$arbitrator_name = $this->security->xss_clean($data['at_arb_name']);

	 					$at_data = array(
	 						'case_no' => $case_no,
	 						'name_of_arbitrator' => $arbitrator_name,
	 						'whether_on_panel' => $this->security->xss_clean($data['at_whe_on_panel']),
	 						'at_cat_id' => $this->security->xss_clean($data['at_category']),
	 						'appointed_by' => $this->security->xss_clean($data['at_appointed_by']),
	 						'date_of_appointment' => $this->security->xss_clean($data['at_doa']),
	 						'date_of_declaration' => $this->security->xss_clean($data['at_dod']),
	 						'arb_terminated' => $this->security->xss_clean($data['at_terminated']),
	 						'date_of_termination' => '',
	 						'reason_of_termination' => '',
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					if(isset($data['at_terminated']) && $data['at_terminated'] == 'yes'){
	 						$at_data['date_of_termination'] = $this->security->xss_clean($data['at_dot']);
	 						$at_data['reason_of_termination'] = $this->security->xss_clean($data['at_rot']);
	 					}

	 					// Insert case details
	 					$result = $this->db->where('id', $data['hidden_at_id'])->update('cs_arbitral_tribunal_tbl', $at_data);
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_arbitral_tribunal_tbl';
	 						$table_id = $data['hidden_at_id'];
	 						$message = 'Details of arbitrator '. $arbitrator_name .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_ARB_TRI_LIST': 

				$this->form_validation->set_rules('id', 'Case Arbitral Tribunal Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);
					$r = $this->db->where('id', $id)->update('cs_arbitral_tribunal_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, name_of_arbitrator, case_no')->from('cs_arbitral_tribunal_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_arbitral_tribunal_tbl';
 						$table_id = $id;
 						$message = 'Arbitrator '. $data['name_of_arbitrator'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);


						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_AWARD_TERMINATION':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				// $this->form_validation->set_rules('hidden_award_term_id', 'Award/termination', 'xss_clean');
 				$this->form_validation->set_rules('award_term_select', 'Award/termination select', 'xss_clean');
 				$this->form_validation->set_rules('award_term_doa', 'Date of award', 'xss_clean');
 				$this->form_validation->set_rules('award_term_nature', 'Nature of award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('award_term_addendum', 'Addendum Award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('award_term_served_claimant', 'Award served to Claimant ontatus', 'xss_clean');

 				$this->form_validation->set_rules('award_term_served_res', 'Award served to Respondent on', 'xss_clean');
 				$this->form_validation->set_rules('award_term_dot', 'Date of termination', 'xss_clean');
 				$this->form_validation->set_rules('award_term_rft', 'Reason for termination', 'xss_clean');

 				$this->form_validation->set_rules('award_term_factsheet', 'Factsheet prepared', 'xss_clean');
 				$this->form_validation->set_rules('award_term_fee_rel', 'Fee released', 'xss_clean');
 				$this->form_validation->set_rules('award_term_amt_fee', 'Amount of Fee Released', 'xss_clean');
 				$this->form_validation->set_rules('award_term_det_fee', 'Details of fee released', 'xss_clean');

 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$award_term_type = $this->security->xss_clean($data['award_term_select']);
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);

	 					$at_data = array(
	 						'case_no' => $case_no,
	 						'type' => $award_term_type,
	 						'date_of_award' => '',
	 						'nature_of_award' => '',
	 						'addendum_award' => '',
	 						'award_served_claimaint_on' => '',
	 						'award_served_respondent_on' => '',
	 						'date_of_termination' => '',
	 						'reason_for_termination' => '',
	 						'factsheet_prepared' => $this->security->xss_clean($data['award_term_factsheet']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Check for the award type
	 					// And according to that store the corresponding fields
	 					if(isset($award_term_type) && !empty($award_term_type)){
	 						if($award_term_type == 'award'){
		 						$at_data['date_of_award'] = $this->security->xss_clean($data['award_term_doa']);
		 						$at_data['nature_of_award'] = $this->security->xss_clean($data['award_term_nature']);
		 						$at_data['addendum_award'] = $this->security->xss_clean($data['award_term_addendum']);
		 						$at_data['award_served_claimaint_on'] = $this->security->xss_clean($data['award_term_served_claimant']);
		 						$at_data['award_served_respondent_on'] = $this->security->xss_clean($data['award_term_served_res']);
		 					}
		 					elseif ($award_term_type == 'termination') {
		 						$at_data['date_of_termination'] = $this->security->xss_clean($data['award_term_dot']);
		 						$at_data['reason_for_termination'] = $this->security->xss_clean($data['award_term_rft']);
		 					}
	 					}


	 					// Insert case details
	 					$result = $this->db->insert('cs_award_term_tbl', $at_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();
							
							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_award_term_tbl';
	 						$message = 'Award & termination of '. $case_det['case_no'] .' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_AWARD_TERMINATION':

				// Case details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('hidden_award_term_id', 'Award/termination', 'required|xss_clean');
 				$this->form_validation->set_rules('award_term_select', 'Award/termination select', 'xss_clean');
 				$this->form_validation->set_rules('award_term_doa', 'Date of award', 'xss_clean');
 				$this->form_validation->set_rules('award_term_nature', 'Nature of award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('award_term_addendum', 'Addendum Award', 'xss_clean|regex_match[/^[a-zA-Z\s`\',._-]+$/]');
 				$this->form_validation->set_rules('award_term_served_claimant', 'Award served to Claimant ontatus', 'xss_clean');

 				$this->form_validation->set_rules('award_term_served_res', 'Award served to Respondent on', 'xss_clean');
 				$this->form_validation->set_rules('award_term_dot', 'Date of termination', 'xss_clean');
 				$this->form_validation->set_rules('award_term_rft', 'Reason for termination', 'xss_clean');

 				$this->form_validation->set_rules('award_term_factsheet', 'Factsheet prepared', 'xss_clean');
 				$this->form_validation->set_rules('award_term_fee_rel', 'Fee released', 'xss_clean');
 				$this->form_validation->set_rules('award_term_amt_fee', 'Amount of Fee Released', 'xss_clean');
 				$this->form_validation->set_rules('award_term_det_fee', 'Details of fee released', 'xss_clean');

 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$hidden_award_term_id = $this->security->xss_clean($data['hidden_award_term_id']);
	 					$hidden_case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$award_term_type = $this->security->xss_clean($data['award_term_select']);


	 					$at_data = array(
	 						'type' => $award_term_type,
	 						'date_of_award' => '',
	 						'nature_of_award' => '',
	 						'addendum_award' => '',
	 						'award_served_claimaint_on' => '',
	 						'award_served_respondent_on' => '',
	 						'date_of_termination' => '',
	 						'reason_for_termination' => '',
	 						'factsheet_prepared' => $this->security->xss_clean($data['award_term_factsheet']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Check for the award type
	 					// And according to that store the corresponding fields
	 					if(isset($award_term_type) && !empty($award_term_type)){
	 						if($award_term_type == 'award'){
		 						$at_data['date_of_award'] = $this->security->xss_clean($data['award_term_doa']);
		 						$at_data['nature_of_award'] = $this->security->xss_clean($data['award_term_nature']);
		 						$at_data['addendum_award'] = $this->security->xss_clean($data['award_term_addendum']);
		 						$at_data['award_served_claimaint_on'] = $this->security->xss_clean($data['award_term_served_claimant']);
		 						$at_data['award_served_respondent_on'] = $this->security->xss_clean($data['award_term_served_res']);
		 					}
		 					elseif ($award_term_type == 'termination') {
		 						$at_data['date_of_termination'] = $this->security->xss_clean($data['award_term_dot']);
		 						$at_data['reason_for_termination'] = $this->security->xss_clean($data['award_term_rft']);
		 					}
	 					}

	 					// Insert case details
	 					$result = $this->db->where('case_no', $hidden_case_no)->where('id', $hidden_award_term_id)->update('cs_award_term_tbl', $at_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($hidden_case_no);
	 						$table_name = 'cs_arbitral_tribunal_tbl';
	 						$table_id = $hidden_award_term_id;
	 						$message = 'Details of award & termination of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_CASE_OTHER_PLEADINGS':

				// Other pleadings details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('op_details', 'Details', 'required|xss_clean');
 				$this->form_validation->set_rules('op_dof', 'Date of filed', 'required|xss_clean');
 				$this->form_validation->set_rules('op_filed_by', 'Filed by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$filed_by = $this->security->xss_clean($data['op_filed_by']);
	 					$at_data = array(
	 						'case_no' => $case_no,
	 						'details' => $this->security->xss_clean($data['op_details']),
	 						'date_of_filing' => $this->security->xss_clean($data['op_dof']),
	 						'filed_by' => $filed_by,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case other pleadings details
	 					$result = $this->db->insert('cs_other_pleadings_tbl', $at_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();
							
							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_other_pleadings_tbl';
	 						$message = 'A new other pleadings of case '.$case_det['case_no'].' which is filed by '.$filed_by.' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_OTHER_PLEADINGS':

				// Other pleadings details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_op_id', 'Other Pleadings id', 'required|xss_clean');
 				$this->form_validation->set_rules('op_details', 'Details', 'required|xss_clean');
 				$this->form_validation->set_rules('op_dof', 'Date of filed', 'required|xss_clean');
 				$this->form_validation->set_rules('op_filed_by', 'Filed by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$op_id = $this->security->xss_clean($data['hidden_op_id']);
	 					$filed_by = $this->security->xss_clean($data['op_filed_by']);

	 					$at_data = array(
	 						'details' => $this->security->xss_clean($data['op_details']),
	 						'date_of_filing' => $this->security->xss_clean($data['op_dof']),
	 						'filed_by' => $filed_by,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Insert case other pleadings details
	 					$result = $this->db->where('id', $op_id)->where('case_no', $case_no)->update('cs_other_pleadings_tbl', $at_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_other_pleadings_tbl';
	 						$table_id = $op_id;
	 						$message = 'Details of other pleadings of case '.$case_det['case_no'].' which is filed by '.$filed_by.' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_OTHER_PLEADINGS': 

				$this->form_validation->set_rules('id', 'Other Pleadings Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_other_pleadings_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, filed_by, case_no')->from('cs_other_pleadings_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_other_pleadings_tbl';
 						$table_id = $id;
 						$message = 'Other pleadings of '. $data['filed_by'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;



			case 'ADD_CASE_OTHER_CORRESPONDANCE':

				// Other correspondance details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_details', 'Details', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_doc', 'Date of correspondance', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_send_by', 'Send by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('oc_sent_to', 'Sent to', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);

	 					$at_data = array(
	 						'case_no' => $case_no,
	 						'details' => $this->security->xss_clean($data['oc_details']),
	 						'date_of_correspondance' => $this->security->xss_clean($data['oc_doc']),
	 						'send_by' => $this->security->xss_clean($data['oc_send_by']),
	 						'sent_to' => $this->security->xss_clean($data['oc_sent_to']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case other correspondance details
	 					$result = $this->db->insert('cs_other_correspondance_tbl', $at_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_other_correspondance_tbl';
	 						$message = 'Other correspondance of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_OTHER_CORRESPONDANCE':

				// Other correspondance details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_oc_id', 'Other Correspondance id', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_details', 'Details', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_doc', 'Date of correspondance', 'required|xss_clean');
 				$this->form_validation->set_rules('oc_send_by', 'Send by', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('oc_sent_to', 'Sent to', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$oc_id = $this->security->xss_clean($data['hidden_oc_id']);
	 					$send_by = $this->security->xss_clean($data['oc_send_by']);

	 					$oc_data = array(
	 						'details' => $this->security->xss_clean($data['oc_details']),
	 						'date_of_correspondance' => $this->security->xss_clean($data['oc_doc']),
	 						'send_by' => $send_by,
	 						'sent_to' => $this->security->xss_clean($data['oc_sent_to']),
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Insert case other pleadings details
	 					$result = $this->db->where('id', $oc_id)->where('case_no', $case_no)->update('cs_other_correspondance_tbl', $oc_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_other_correspondance_tbl';
	 						$table_id = $oc_id;
	 						$message = 'Details of other correspondance of '. $send_by .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_OTHER_CORRESPONDANCE': 

				$this->form_validation->set_rules('id', 'Other Correspondance Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_other_correspondance_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, send_by, case_no')->from('cs_other_correspondance_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_other_correspondance_tbl';
 						$table_id = $id;
 						$message = 'Other correspondance of '. $data['send_by'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_FEE_RELEASED':

				// Fee released details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_dofr', 'Date of fee released', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_released_to', 'Released to', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fr_mode', 'Mode of payment', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_details', 'Details of fee released', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$amount = $this->security->xss_clean($data['fr_amount']);
	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					
	 					$fr_data = array(
	 						'case_no' => $case_no,
	 						'date_of_fee_released' => $this->security->xss_clean($data['fr_dofr']),
	 						'released_to' => $this->security->xss_clean($data['fr_released_to']),
	 						'mode_of_payment' => $this->security->xss_clean($data['fr_mode']),
	 						'details_of_fee_released' => $this->security->xss_clean($data['fr_details']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case fee released details
	 					$result = $this->db->insert('cs_at_fee_released_tbl', $fr_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_at_fee_released_tbl';
	 						$message = 'A new fee released of'. $amount .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_FEE_RELEASED':

				// Fee Released details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_fr_id', 'Fee released id', 'required|xss_clean');
				$this->form_validation->set_rules('fr_dofr', 'Date of fee released', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_released_to', 'Released to', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fr_mode', 'Mode of payment', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_details', 'Details of fee released', 'required|xss_clean');
 				$this->form_validation->set_rules('fr_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$fr_id = $this->security->xss_clean($data['hidden_fr_id']);
	 					$amount = $this->security->xss_clean($data['fr_amount']);

	 					$fr_data = array(
	 						'date_of_fee_released' => $this->security->xss_clean($data['fr_dofr']),
	 						'released_to' => $this->security->xss_clean($data['fr_released_to']),
	 						'mode_of_payment' => $this->security->xss_clean($data['fr_mode']),
	 						'details_of_fee_released' => $this->security->xss_clean($data['fr_details']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update case fee released details
	 					$result = $this->db->where('id', $fr_id)->where('case_no', $case_no)->update('cs_at_fee_released_tbl', $fr_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_at_fee_released_tbl';
	 						$table_id = $fr_id;
	 						$message = 'Details of fee released of amount '. $amount .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_FEE_RELEASED': 

				$this->form_validation->set_rules('id', 'Fee Released Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_at_fee_released_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_at_fee_released_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_at_fee_released_tbl';
 						$table_id = $id;
 						$message = 'Fee released of amount '. $data['amount'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_COUNSEL':

				// Counsels details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'required|xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
 				$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('counsel_address', 'Address', 'xss_clean');
 				$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
 				$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$name = $this->security->xss_clean($data['counsel_name']);

	 					$counsel_data = array(
	 						'case_no' => $case_no,
	 						'enrollment_no' => $this->security->xss_clean($data['counsel_enroll_no']),
	 						'name' => $name,
	 						'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
	 						'email' => $this->security->xss_clean($data['counsel_email']),
	 						'phone' => $this->security->xss_clean($data['counsel_contact']),
	 						'address' => $this->security->xss_clean($data['counsel_address']),
	 						'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
	 						'date_of_discharge' => '',
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					if($counsel_data['discharge'] == 1){
	 						$counsel_data['date_of_discharge'] = $this->security->xss_clean($data['counsel_dodis']);
	 					}

	 					// Insert case counsel details
	 					$result = $this->db->insert('cs_counsels_tbl', $counsel_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_counsels_tbl';
	 						$message = 'A new counsel '. $name .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_COUNSEL':

				// Counsels details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_counsel_id', 'Counsel id', 'required|xss_clean');
				$this->form_validation->set_rules('counsel_enroll_no', 'Enrollment Number', 'required|xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('counsel_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('counsel_appearing_for', 'Appearing for', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('counsel_email', 'Email Id', 'valid_email|xss_clean');
 				$this->form_validation->set_rules('counsel_contact', 'Contact Number', 'xss_clean|regex_match[/^[0-9+-]+$/]');
 				$this->form_validation->set_rules('counsel_address', 'Address', 'xss_clean');
 				$this->form_validation->set_rules('counsel_dis_check', 'Discharge or not', 'xss_clean');
 				$this->form_validation->set_rules('counsel_dodis', 'Date of discharge', 'xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$counsel_id = $this->security->xss_clean($data['hidden_counsel_id']);
	 					$name = $this->security->xss_clean($data['counsel_name']);

	 					$counsel_data = array(
	 						'enrollment_no' => $this->security->xss_clean($data['counsel_enroll_no']),
	 						'name' => $name,
	 						'appearing_for' => $this->security->xss_clean($data['counsel_appearing_for']),
	 						'email' => $this->security->xss_clean($data['counsel_email']),
	 						'phone' => $this->security->xss_clean($data['counsel_contact']),
	 						'address' => $this->security->xss_clean($data['counsel_address']),
	 						'discharge' => $this->security->xss_clean($data['counsel_dis_check']),
	 						'date_of_discharge' => '',
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					if($counsel_data['discharge'] == 1){
	 						$counsel_data['date_of_discharge'] = $this->security->xss_clean($data['counsel_dodis']);
	 					}

	 					// Update case counsel details
	 					$result = $this->db->where('id', $counsel_id)->where('case_no', $case_no)->update('cs_counsels_tbl', $counsel_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_counsels_tbl';
	 						$table_id = $counsel_id;
	 						$message = 'Details of counsel '. $name .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_COUNSEL': 

				$this->form_validation->set_rules('id', 'Counsel Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_counsels_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, name, case_no')->from('cs_counsels_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_counsels_tbl';
 						$table_id = $id;
 						$message = 'Counsel '. $data['name'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_FEE_DEPOSIT':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_deposited_by', 'Deposited by', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('fd_name_of_depositor', 'Name of depositor', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fd_deposited_towards', 'Deposited towards', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$amount = $this->security->xss_clean($data['fd_amount']);

	 					$fd_data = array(
	 						'case_no' => $case_no,
	 						'date_of_deposit' => $this->security->xss_clean($data['fd_date_of_deposit']),
	 						'deposited_by' => $this->security->xss_clean($data['fd_deposited_by']),
	 						'name_of_depositor' => $this->security->xss_clean($data['fd_name_of_depositor']),
	 						'deposited_towards' => $this->security->xss_clean($data['fd_deposited_towards']),
	 						'mode_of_deposit' => $this->security->xss_clean($data['fd_mode_of_deposit']),
	 						'details_of_deposit' => $this->security->xss_clean($data['fd_details_of_deposit']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case fee deposit
	 					$result = $this->db->insert('cs_fee_deposit_tbl', $fd_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_deposit_tbl';
	 						$message = 'Fee deposit of amount '. $amount .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_FEE_DEPOSIT':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_deposited_by', 'Deposited by', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('fd_name_of_depositor', 'Name of depositor', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fd_deposited_towards', 'Deposited towards', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('fd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$fd_id = $this->security->xss_clean($data['hidden_fd_id']);
	 					$amount = $this->security->xss_clean($data['fd_amount']);

	 					$fd_data = array(
	 						'date_of_deposit' => $this->security->xss_clean($data['fd_date_of_deposit']),
	 						'deposited_by' => $this->security->xss_clean($data['fd_deposited_by']),
	 						'name_of_depositor' => $this->security->xss_clean($data['fd_name_of_depositor']),
	 						'deposited_towards' => $this->security->xss_clean($data['fd_deposited_towards']),
	 						'mode_of_deposit' => $this->security->xss_clean($data['fd_mode_of_deposit']),
	 						'details_of_deposit' => $this->security->xss_clean($data['fd_details_of_deposit']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update case counsel details
	 					$result = $this->db->where('id', $fd_id)->where('case_no', $case_no)->update('cs_fee_deposit_tbl', $fd_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_deposit_tbl';
	 						$table_id = $fd_id;
	 						$message = 'Details of fee deposit of '. $amount .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_FEE_DEPOSIT': 

				$this->form_validation->set_rules('id', 'Fee deposit id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_fee_deposit_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_fee_deposit_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_fee_deposit_tbl';
 						$table_id = $id;
 						$message = 'Fee Deposit of amount '. $data['amount'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_COST_DEPOSIT':

				// Cost Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_deposited_by', 'Deposited by', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('cd_name_of_depositor', 'Name of depositor', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('cd_cost_imposed_dated', 'Cost imposed vide order dated', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$amount = $this->security->xss_clean($data['cd_amount']);

	 					$cd_data = array(
	 						'case_no' => $case_no,
	 						'date_of_deposit' => $this->security->xss_clean($data['cd_date_of_deposit']),
	 						'deposited_by' => $this->security->xss_clean($data['cd_deposited_by']),
	 						'name_of_depositor' => $this->security->xss_clean($data['cd_name_of_depositor']),
	 						'cost_imposed_dated' => $this->security->xss_clean($data['cd_cost_imposed_dated']),
	 						'mode_of_deposit' => $this->security->xss_clean($data['cd_mode_of_deposit']),
	 						'details_of_deposit' => $this->security->xss_clean($data['cd_details_of_deposit']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case cost deposit
	 					$result = $this->db->insert('cs_cost_deposit_tbl', $cd_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_cost_deposit_tbl';
	 						$message = 'Cost deposit of amount '. $amount .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_COST_DEPOSIT':

				// Cost Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_date_of_deposit', 'Date of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_deposited_by', 'Deposited by', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('cd_name_of_depositor', 'Name of depositor', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('cd_cost_imposed_dated', 'Cost imposed vide order dated', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_mode_of_deposit', 'Mode of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_details_of_deposit', 'Details of deposit', 'required|xss_clean');
 				$this->form_validation->set_rules('cd_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$cd_id = $this->security->xss_clean($data['hidden_cd_id']);
	 					$amount = $this->security->xss_clean($data['cd_amount']);

	 					$cd_data = array(
	 						'date_of_deposit' => $this->security->xss_clean($data['cd_date_of_deposit']),
	 						'deposited_by' => $this->security->xss_clean($data['cd_deposited_by']),
	 						'name_of_depositor' => $this->security->xss_clean($data['cd_name_of_depositor']),
	 						'cost_imposed_dated' => $this->security->xss_clean($data['cd_cost_imposed_dated']),
	 						'mode_of_deposit' => $this->security->xss_clean($data['cd_mode_of_deposit']),
	 						'details_of_deposit' => $this->security->xss_clean($data['cd_details_of_deposit']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update case cost deposit details
	 					$result = $this->db->where('id', $cd_id)->where('case_no', $case_no)->update('cs_cost_deposit_tbl', $cd_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_cost_deposit_tbl';
	 						$table_id = $cd_id;
	 						$message = 'Cost deposit of amount '. $amount .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_COST_DEPOSIT': 

				$this->form_validation->set_rules('id', 'Cost deposit id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_cost_deposit_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_cost_deposit_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_cost_deposit_tbl';
 						$table_id = $id;
 						$message = 'Cost deposit of amount '. $data['amount'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_FEE_REFUND':

				// Fee Refund
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_date_of_refund', 'Date of refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_refunded_to', 'Refunded to', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('fee_ref_name_of_party', 'Name of party', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fee_ref_refunded_towards', 'Refunded towards', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_mode_of_refund', 'Mode of Refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_details_of_refund', 'Details of refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{	

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$amount = $this->security->xss_clean($data['fee_ref_amount']);

	 					$fee_ref_data = array(
	 						'case_no' => $case_no,
	 						'date_of_refund' => $this->security->xss_clean($data['fee_ref_date_of_refund']),
	 						'refunded_to' => $this->security->xss_clean($data['fee_ref_refunded_to']),
	 						'name_of_party' => $this->security->xss_clean($data['fee_ref_name_of_party']),
	 						'refunded_towards' => $this->security->xss_clean($data['fee_ref_refunded_towards']),
	 						'mode_of_refund' => $this->security->xss_clean($data['fee_ref_mode_of_refund']),
	 						'details_of_refund' => $this->security->xss_clean($data['fee_ref_details_of_refund']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case fee deposit
	 					$result = $this->db->insert('cs_fee_refund_tbl', $fee_ref_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_refund_tbl';
	 						$message = 'Fee refund of amount '. $amount .' of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_FEE_REFUND':

				// Fee Deposit
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('fee_ref_date_of_refund', 'Date of refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_refunded_to', 'Refunded to', 'required|xss_clean|regex_match[/^[0-9]+$/]');
 				$this->form_validation->set_rules('fee_ref_name_of_party', 'Name of party', 'required|xss_clean|regex_match[/^[a-zA-Z\s.]+$/]');
 				$this->form_validation->set_rules('fee_ref_refunded_towards', 'Refunded towards', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_mode_of_refund', 'Mode of Refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_details_of_refund', 'Details of refund', 'required|xss_clean');
 				$this->form_validation->set_rules('fee_ref_amount', 'Amount', 'required|xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$fee_ref_id = $this->security->xss_clean($data['hidden_fee_ref_id']);
	 					$amount = $this->security->xss_clean($data['fee_ref_amount']);

	 					$fee_ref_data = array(
	 						'date_of_refund' => $this->security->xss_clean($data['fee_ref_date_of_refund']),
	 						'refunded_to' => $this->security->xss_clean($data['fee_ref_refunded_to']),
	 						'name_of_party' => $this->security->xss_clean($data['fee_ref_name_of_party']),
	 						'refunded_towards' => $this->security->xss_clean($data['fee_ref_refunded_towards']),
	 						'mode_of_refund' => $this->security->xss_clean($data['fee_ref_mode_of_refund']),
	 						'details_of_refund' => $this->security->xss_clean($data['fee_ref_details_of_refund']),
	 						'amount' => $amount,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update case fee refund
	 					$result = $this->db->where('id', $fee_ref_id)->where('case_no', $case_no)->update('cs_fee_refund_tbl', $fee_ref_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_refund_tbl';
	 						$table_id = $fee_ref_id;
	 						$message = 'Fee refund of amount '. $amount .' of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_FEE_REFUND': 

				$this->form_validation->set_rules('id', 'Fee refund id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_fee_refund_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, amount, case_no')->from('cs_fee_refund_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$table_name = 'cs_fee_refund_tbl';
 						$table_id = $id;
 						$message = 'Fee refund of amount '. $data['amount'] .' of case '.$case_det['case_no'].' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_FEE_COST_DETAILS':

				// Fee and cost details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean|is_unique[cs_fee_details_tbl.case_no]');
 				$this->form_validation->set_rules('fc_c_cc_assessed_sep', 'Whether Claims or Counter Claims assessed separately', 'xss_clean');
 				$this->form_validation->set_rules('fc_sum_despute', 'Sum in Dispute', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_sum_despute_cc', 'Sum in Dispute (Counter Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_sum_despute_claim', 'Sum in Dispute (Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_pro_assesment', 'As per provisional assessment dated', 'xss_clean');
 				$this->form_validation->set_rules('fc_total_arb_fees', 'Total Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

 				$this->form_validation->set_rules('fc_cs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_cs_adm_fees', 'Administrative Expenses ', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_rs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_rs_adm_fees', 'Administrative Expenses', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$fee_cost_data = array(
	 						'case_no' => $case_no,
	 						'c_cc_asses_sep' => $this->security->xss_clean($data['fc_c_cc_assessed_sep']),
	 						'sum_in_dispute' => '',
	 						'sum_in_dispute_claim' => '',
	 						'sum_in_dispute_cc' => '',
	 						'asses_date' => $this->security->xss_clean($data['fc_pro_assesment']),
	 						'total_arb_fees' => $this->security->xss_clean($data['fc_total_arb_fees']),
	 						'cs_arb_fees' => $this->security->xss_clean($data['fc_cs_arb_fees']),
	 						'cs_adminis_fees' => $this->security->xss_clean($data['fc_cs_adm_fees']),
	 						'rs_arb_fees' => $this->security->xss_clean($data['fc_rs_arb_fees']),
	 						'rs_adminis_fee' => $this->security->xss_clean($data['fc_rs_adm_fees']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					if($data['fc_c_cc_assessed_sep'] == 'yes'){
	 						$fee_cost_data['sum_in_dispute_claim'] = $this->security->xss_clean($data['fc_sum_despute_claim']);
	 						$fee_cost_data['sum_in_dispute_cc'] = $this->security->xss_clean($data['fc_sum_despute_cc']);
	 					}

	 					if($data['fc_c_cc_assessed_sep'] == 'no'){
	 						$fee_cost_data['sum_in_dispute'] = $this->security->xss_clean($data['fc_sum_despute']);
	 					}

	 					// Insert case fee and cost details
	 					$result = $this->db->insert('cs_fee_details_tbl', $fee_cost_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_details_tbl';
	 						$message = 'Fee details of case '.$case_det['case_no'].' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

 							$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
 						}
 						else{
 							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.";
 						}
	 					

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'EDIT_CASE_FEE_COST_DETAILS':

				// Fees and cost details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('hidden_fee_cost_id', 'Fee Cost Id', 'required|xss_clean');
				$this->form_validation->set_rules('fc_c_cc_assessed_sep', 'Whether Claims or Counter Claims assessed separately', 'xss_clean');
 				$this->form_validation->set_rules('fc_sum_despute', 'Sum in Dispute', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_sum_despute_cc', 'Sum in Dispute (Counter Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_sum_despute_claim', 'Sum in Dispute (Claims)', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_pro_assesment', 'As per provisional assessment dated', 'xss_clean');
 				$this->form_validation->set_rules('fc_total_arb_fees', 'Total Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));

 				$this->form_validation->set_rules('fc_cs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_cs_adm_fees', 'Administrative Expenses ', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_rs_arb_fees', 'Arbitrators Fees', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				$this->form_validation->set_rules('fc_rs_adm_fees', 'Administrative Expenses', 'xss_clean|numeric|regex_match[/^\d+(\.\d{2})?$/]', array('regex_match' => 'Invalid {field}. The {field} should contain only two digits after decimal.'));
 				

 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
	 					$fee_cost_id = $this->security->xss_clean($data['hidden_fee_cost_id']);

	 					$fee_cost_data = array(
	 						'c_cc_asses_sep' => $this->security->xss_clean($data['fc_c_cc_assessed_sep']),
	 						'sum_in_dispute' => '',
	 						'sum_in_dispute_claim' => '',
	 						'sum_in_dispute_cc' => '',
	 						'asses_date' => $this->security->xss_clean($data['fc_pro_assesment']),
	 						'total_arb_fees' => $this->security->xss_clean($data['fc_total_arb_fees']),
	 						'cs_arb_fees' => $this->security->xss_clean($data['fc_cs_arb_fees']),
	 						'cs_adminis_fees' => $this->security->xss_clean($data['fc_cs_adm_fees']),
	 						'rs_arb_fees' => $this->security->xss_clean($data['fc_rs_arb_fees']),
	 						'rs_adminis_fee' => $this->security->xss_clean($data['fc_rs_adm_fees']),
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

						if($data['fc_c_cc_assessed_sep'] == 'yes'){
	 						$fee_cost_data['sum_in_dispute_claim'] = $this->security->xss_clean($data['fc_sum_despute_claim']);
	 						$fee_cost_data['sum_in_dispute_cc'] = $this->security->xss_clean($data['fc_sum_despute_cc']);
	 					}

	 					if($data['fc_c_cc_assessed_sep'] == 'no'){
	 						$fee_cost_data['sum_in_dispute'] = $this->security->xss_clean($data['fc_sum_despute']);
	 					}	 					

	 					// Update case fee and cost details
	 					$result = $this->db->where('id' , $fee_cost_id)->update('cs_fee_details_tbl', $fee_cost_data);

	 					
	 					if($result){

	 						// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$table_name = 'cs_fee_details_tbl';
	 						$table_id = $fee_cost_id;
	 						$message = 'Fee Details of case '.$case_det['case_no'].' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

							$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
 						}
 						else{
 							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.";
 						}
	 					

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_PANEL_CATEGORY':

				// Panel Category
				$this->form_validation->set_rules('pc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$category_name = $this->security->xss_clean($data['pc_category_name']);
	 					$fee_ref_data = array(
	 						'category_name' => $category_name,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert panel category
	 					$result = $this->db->insert('panel_category_tbl', $fee_ref_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();

	 						$table_name = 'panel_category_tbl';
	 						$message = 'A new panel category '. $category_name .' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_PANEL_CATEGORY':

				// Panel category
				$this->form_validation->set_rules('pc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
				$this->form_validation->set_rules('pc_hidden_id', 'Category id', 'required|xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$pc_id = $this->security->xss_clean($data['pc_hidden_id']);
	 					$category_name = $this->security->xss_clean($data['pc_category_name']);

	 					$pc_data = array(
	 						'category_name' => $category_name,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update case fee refund
	 					$result = $this->db->where('id', $pc_id)->update('panel_category_tbl', $pc_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'panel_category_tbl';
	 						$table_id = $pc_id;
	 						$message = 'Panel Category '. $category_name .' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_PANEL_CATEGORY': 

				$this->form_validation->set_rules('id', 'Category id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('panel_category_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, category_name')->from('panel_category_tbl')->where('id', $id)->get()->row_array();
 						$table_name = 'panel_category_tbl';
 						$table_id = $id;
 						$message = 'Panel Category '. $data['category_name'] .' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'ADD_POA_FORM':

				// Panel of arbitrator
				$this->form_validation->set_rules('poa_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
				$this->form_validation->set_rules('poa_category', 'Category', 'required|xss_clean');
				$this->form_validation->set_rules('poa_experience', 'Experience', 'alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('poa_enrollment_no', 'Enrollment No.', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('poa_contact_details', 'Contact Details', 'xss_clean');
				$this->form_validation->set_rules('poa_email_details', 'Email Details', 'xss_clean');
				$this->form_validation->set_rules('poa_address_details', 'Address Details', 'xss_clean');
				$this->form_validation->set_rules('poa_remarks', 'Remarks', 'xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$name = $this->security->xss_clean($data['poa_name']);
	 					$poa_data = array(
	 						'name' => $name,
	 						'category_id' => $this->security->xss_clean($data['poa_category']),
	 						'contact_details' => $this->security->xss_clean($data['poa_contact_details']),
	 						'email_details' => $this->security->xss_clean($data['poa_email_details']),
	 						'address_details' => $this->security->xss_clean($data['poa_address_details']),
	 						'remarks' => $this->security->xss_clean($data['poa_remarks']),
	 						'experience' => $this->security->xss_clean($data['poa_experience']),
	 						'enrollment_no' => $this->security->xss_clean($data['poa_enrollment_no']),
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert panel category
	 					$result = $this->db->insert('panel_of_arbitrator_tbl', $poa_data);

	 					if($result){

	 						$table_id = $this->db->insert_id();
	 						$table_name = 'panel_of_arbitrator_tbl';
	 						$message = 'A new arbitrator '. $name .' is added in panel.';
	 						$this->update_data_logs($table_name, $table_id, $message);


	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_POA_FORM':

				// Panel of arbitrator
				$this->form_validation->set_rules('poa_hidden_id', 'Panel of arbitrator id', 'required|xss_clean');
				$this->form_validation->set_rules('poa_name', 'Name', 'required|xss_clean|regex_match[/^[a-zA-Z\s.\'`]+$/]');
				$this->form_validation->set_rules('poa_category', 'Category', 'required|xss_clean');
				$this->form_validation->set_rules('poa_experience', 'Experience', 'alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('poa_enrollment_no', 'Enrollment No.', 'xss_clean|regex_match[/^[a-zA-Z0-9\/\s_-]+$/]');
				$this->form_validation->set_rules('poa_contact_details', 'Contact Details', 'xss_clean');
				$this->form_validation->set_rules('poa_email_details', 'Email Details', 'xss_clean');
				$this->form_validation->set_rules('poa_address_details', 'Address Details', 'xss_clean');
				$this->form_validation->set_rules('poa_remarks', 'Remarks', 'xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$poa_id = $this->security->xss_clean($data['poa_hidden_id']);
	 					$name = $this->security->xss_clean($data['poa_name']);

	 					$poa_data = array(
	 						'name' => $name,
	 						'category_id' => $this->security->xss_clean($data['poa_category']),
	 						'contact_details' => $this->security->xss_clean($data['poa_contact_details']),
	 						'email_details' => $this->security->xss_clean($data['poa_email_details']),
	 						'address_details' => $this->security->xss_clean($data['poa_address_details']),
	 						'remarks' => $this->security->xss_clean($data['poa_remarks']),
	 						'experience' => $this->security->xss_clean($data['poa_experience']),
	 						'enrollment_no' => $this->security->xss_clean($data['poa_enrollment_no']),
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update panel of arbitrator
	 					$result = $this->db->where('id', $poa_id)->update('panel_of_arbitrator_tbl', $poa_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'panel_of_arbitrator_tbl';
	 						$table_id = $poa_id;
	 						$message = 'Details of arbitrator '. $name .' of panel is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_POA': 

				$this->form_validation->set_rules('id', 'Panel of arbitrator id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('panel_of_arbitrator_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, name')->from('panel_of_arbitrator_tbl')->where('id', $id)->get()->row_array();
 						$table_name = 'panel_of_arbitrator_tbl';
 						$table_id = $id;
 						$message = 'Arbitrator '. $data['name'] .' of panel is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_PURPOSE_CATEGORY':

				// Purpose Category
				$this->form_validation->set_rules('puc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$category_name = $this->security->xss_clean($data['puc_category_name']);
	 					$puc_data = array(
	 						'category_name' => $category_name,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert purpose category
	 					$result = $this->db->insert('purpose_category_tbl', $puc_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_id = $this->db->insert_id();
	 						$table_name = 'purpose_category_tbl';
	 						$message = 'A new purpose category '. $category_name .' is added.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record saved successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_PURPOSE_CATEGORY':

				// Purpose category
				$this->form_validation->set_rules('puc_category_name', 'Category name', 'required|xss_clean|regex_match[/^[a-zA-Z\s`\',.]+$/]');
				$this->form_validation->set_rules('puc_hidden_id', 'Category id', 'required|xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$puc_id = $this->security->xss_clean($data['puc_hidden_id']);
	 					$category_name = $this->security->xss_clean($data['puc_category_name']);
	 					$puc_data = array(
	 						'category_name' => $category_name,
	 						'status' => 1,
	 						'updated_by' => $this->user_name,
	 						'updated_at' => $this->date
	 					);

	 					// Update purpose category
	 					$result = $this->db->where('id', $puc_id)->update('purpose_category_tbl', $puc_data);

	 					if($result){

	 						// Update the data logs table for data tracking
	 						$table_name = 'purpose_category_tbl';
	 						$table_id = $puc_id;
	 						$message = 'Details of category '. $category_name .' is updated.';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						$this->db->trans_commit();
	 						$dbstatus = true;
	 						$dbmessage = "Record updated successfully";
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_PURPOSE_CATEGORY': 

				$this->form_validation->set_rules('id', 'Category id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('purpose_category_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, category_name')->from('purpose_category_tbl')->where('id', $id)->get()->row_array();
 						$table_name = 'cs_arbitral_tribunal_tbl';
 						$table_id = $id;
 						$message = 'Category '. $data['category_name'] .' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;


			case 'ADD_CASE_NOTING':

				// Noting details
				$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
				$this->form_validation->set_rules('noting_text', 'Noting text', 'required|xss_clean');
 				$this->form_validation->set_rules('noting_marked_to', 'Marked to', 'required|xss_clean');
 				$this->form_validation->set_rules('noting_date', 'Date', 'required|xss_clean');
 				$this->form_validation->set_rules('noting_next_date', 'Next Date', 'required|xss_clean');
 				
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$case_no  = $this->security->xss_clean($data['hidden_case_no']);
	 					$marked_to = $this->security->xss_clean($data['noting_marked_to']);

	 					$noting_data = array(
	 						'case_no' => $case_no,
	 						'noting' => $this->security->xss_clean($data['noting_text']),
	 						'noting_date' => date('d-m-Y'),
	 						'next_date' => $this->security->xss_clean($data['noting_next_date']),
	 						'marked_to' => $marked_to,
	 						'marked_by' => $this->user_name,
	 						'status' => 1,
	 						'created_by' => $this->user_name,
	 						'created_at' => $this->date
	 					);

	 					// Insert case noting details
	 					$result = $this->db->insert('cs_noting_tbl', $noting_data);

	 					// Get the last inserted id
	 					$last_inserted_id = $this->db->insert_id();

	 					if($result){

							// Update the data logs table for data tracking
	 						$case_det = $this->get_case_details_from_slug($case_no);
	 						$user = $this->get_user_details($marked_to);

	 						$table_name = 'cs_noting_tbl';
	 						$table_id = $last_inserted_id;
	 						$message = 'A new noting of case '.$case_det['case_no'].' is added for '.$user['user_display_name']. ' ('.$user['job_title'].')';
	 						$this->update_data_logs($table_name, $table_id, $message);

	 						// Add notification to notify the users
	 						$notification_data = array(
	 							'type_table' => 'cs_noting_tbl',
	 							'type_id' => $last_inserted_id,
	 							'notification_from' => $this->user_name,
	 							'notification_to' => $this->security->xss_clean($data['noting_marked_to']),
	 							'status' => 1,
	 							'created_at' => $this->date,
	 							'created_by' => $this->user_name
	 						);

	 						$result2 = $this->db->insert('notification_tbl', $notification_data);

	 						if($result2){
	 							$this->db->trans_commit();
		 						$dbstatus = true;
		 						$dbmessage = "Record saved successfully";
	 						}
	 						else{
	 							$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Something went wrong.";
	 						}
	 					}
	 					else{
	 						$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong.";
	 					}

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_NOTING':

				if($this->role == 'DIAC'){
					// Noting details
					$this->form_validation->set_rules('hidden_case_no', 'Case number', 'required|xss_clean');
					$this->form_validation->set_rules('hidden_noting_id', 'Noting id', 'required|xss_clean');
					$this->form_validation->set_rules('noting_text', 'Noting text', 'required|xss_clean');
	 				$this->form_validation->set_rules('noting_marked_to', 'Marked to', 'required|xss_clean');
	 				$this->form_validation->set_rules('noting_date', 'Date', 'required|xss_clean');
	 				$this->form_validation->set_rules('noting_next_date', 'Next Date', 'required|xss_clean');
	 				
	 				if($this->form_validation->run()){
	 					$this->db->trans_begin();
		 				try{

		 					$case_no = $this->security->xss_clean($data['hidden_case_no']);
		 					$noting_id = $this->security->xss_clean($data['hidden_noting_id']);
		 					$noting_date = date('d-m-Y');
		 					$marked_to = $this->security->xss_clean($data['noting_marked_to']);

		 					$noting_data = array(
		 						'case_no' => $this->security->xss_clean($data['hidden_case_no']),
		 						'noting' => $this->security->xss_clean($data['noting_text']),
		 						'noting_date' => $noting_date,
		 						'next_date' => $this->security->xss_clean($data['noting_next_date']),
		 						'marked_to' => $marked_to,
		 						'marked_by' => $this->user_name,
		 						'status' => 1,
		 						'created_by' => $this->user_name,
		 						'created_at' => $this->date
		 					);


		 					// Update case noting details
		 					$result = $this->db->where('id', $noting_id)->where('case_no', $case_no)->update('cs_noting_tbl', $noting_data);

		 					if($result){

		 						// Update the data logs table for data tracking
		 						$case_det = $this->get_case_details_from_slug($case_no);
		 						$user = $this->get_user_details($marked_to);

		 						$table_name = 'cs_arbitral_tribunal_tbl';
		 						$table_id = $noting_id;
		 						$message = 'Details of noting dated '. $noting_date .' of case '.$case_det['case_no'].' for '.$user['user_display_name']. ' ('.$user['job_title'].') is updated.';
		 						$this->update_data_logs($table_name, $table_id, $message);

		 						$this->db->trans_commit();
		 						$dbstatus = true;
		 						$dbmessage = "Record updated successfully";
		 					}
		 					else{
		 						$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Something went wrong.";
		 					}

		 				}
		 				catch(Exception $e){
		 					$this->db->trans_rollback();
		 					$dbstatus = false;
		 					$dbmessage = 'Something went wrong';
		 				}
	 				}
	 				else{
	 					$dbstatus = 'validationerror';
	 					$dbmessage = validation_errors();
	 				}
				}
				else{
					$dbstatus = false;
 					$dbmessage = 'You are not authorized to perform this action.';
				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_NOTING': 

				if($this->role == 'DIAC'){
					$this->form_validation->set_rules('id', 'Noting Id', 'required|xss_clean');

					if($this->form_validation->run()){
						$id = $this->security->xss_clean($data['id']);

						$r = $this->db->where('id', $id)->update('cs_noting_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

						if($r){

							// Update the data logs table for data tracking
							$data = $this->db->select('id, noting_date, marked_to, case_no')->from('cs_noting_tbl')->where('id', $id)->get()->row_array();

	 						$case_det = $this->get_case_details_from_slug($data['case_no']);
	 						$user = $this->get_user_details($data['marked_to']);

	 						$table_name = 'cs_noting_tbl';
	 						$table_id = $id;
	 						$message = 'Noting dated '. $data['noting_date'] .' of case '.$case_det['case_no'].' for '.$user['user_display_name'].' ('.$user['job_title'].') is deleted.';
	 						$this->update_data_logs($table_name, $table_id, $message);

							$dbstatus = TRUE;
							$dbmessage = 'Record deleted successfully';
						}
						else{
							$dbstatus = FALSE;
							$dbmessage = 'Something went wrong. Please try again.';
						}
					}
					else{
						$dbstatus = 'validationerror';
						$dbmessage = validation_errors();
					}
				}
				else{
					$dbstatus = false;
					$dbmessage = 'You are not authorized to perform this action.';
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;



			case 'ADD_CASE_ALLOTMENT':

				// Case Allotment details
				$this->form_validation->set_rules('ca_case_no', 'Case No.', 'required|xss_clean');
				$this->form_validation->set_rules('ca_allotted_to', 'Allotted To', 'required|xss_clean');
 				
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$case_no = $this->security->xss_clean($data['ca_case_no']);
	 					$alloted_to = $this->security->xss_clean($data['ca_allotted_to']);

	 					// Check if the case is already alloted or not
 						// if alloted, return with error message
 						$res = $this->check_case_allotted($case_no, $alloted_to);
 						
 						if($res){
 							$dbstatus = false;
	 						$dbmessage = "Case is already allotted.";
 						}
 						else{
 							// else allot the case
 							$ca_data = array(
		 						'case_no' => $case_no,
		 						'alloted_to' => $alloted_to,
		 						'alloted_by' => $this->user_name,
		 						'status' => 1,
		 						'created_by' => $this->user_name,
		 						'created_at' => $this->date
		 					);

		 					// Insert case allotment details
		 					$result = $this->db->insert('cs_case_allotment_tbl', $ca_data);

		 					if($result){

		 						$table_id = $this->db->insert_id();

								// Update the data logs table for data tracking
		 						$case_det = $this->get_case_details_from_slug($case_no);
		 						$user = $this->get_user_details($alloted_to);

		 						$table_name = 'cs_case_allotment_tbl';
		 						$message = 'A case '.$case_det['case_no'].' is alloted to '.$user['user_display_name'].' ('.$user['job_title'].')';
		 						$this->update_data_logs($table_name, $table_id, $message);

	 							$this->db->trans_commit();
		 						$dbstatus = true;
		 						$dbmessage = "Record saved successfully";
		 						
		 					}
		 					else{
		 						$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Something went wrong.";
		 					}
		 				}
	 						


	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'EDIT_CASE_ALLOTMENT':

				// Case allotment
				$this->form_validation->set_rules('hidden_ca_id', 'Case allotment id', 'required|xss_clean');
				$this->form_validation->set_rules('ca_case_no', 'Case No.', 'required|xss_clean');
				$this->form_validation->set_rules('ca_allotted_to', 'Allotted To', 'required|xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{
	 					$ca_id = $this->security->xss_clean($data['hidden_ca_id']);
	 					$case_no = $this->security->xss_clean($data['ca_case_no']);
	 					$alloted_to = $this->security->xss_clean($data['ca_allotted_to']);

	 					// Check if the case is already alloted or not
 						// if alloted, return with error message
 						$res = $this->check_case_allotted($case_no, $alloted_to);
 						
 						if($res){
 							$dbstatus = false;
	 						$dbmessage = "Case is already allotted.";
 						}
 						else{
 							$alloted_to = $this->security->xss_clean($data['ca_allotted_to']);

 							$ca_data = array(
		 						'case_no' => $this->security->xss_clean($data['ca_case_no']),
		 						'alloted_to' => $alloted_to,
		 						'alloted_by' => $this->user_name,
		 						'status' => 1,
		 						'created_by' => $this->user_name,
		 						'created_at' => $this->date
		 					);


		 					// Update case allotment details
		 					$result = $this->db->where('id', $ca_id)->update('cs_case_allotment_tbl', $ca_data);

		 					if($result){

		 						// Update the data logs table for data tracking
		 						$case_det = $this->get_case_details_from_slug($case_no);
		 						$user = $this->get_user_details($alloted_to);

		 						$table_name = 'cs_case_allotment_tbl';
		 						$table_id = $ca_id;
		 						$message = 'Details of case allotment of case '. $case_det['case_no'] .' allotted to '.$user['user_display_name']. ' ('.$user['job_title'].')'.' is updated.';
		 						$this->update_data_logs($table_name, $table_id, $message);

		 						$this->db->trans_commit();
		 						$dbstatus = true;
		 						$dbmessage = "Record updated successfully";
		 					}
		 					else{
		 						$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Something went wrong.";
		 					}
 						}
	 					

	 				}
	 				catch(Exception $e){
	 					$this->db->trans_rollback();
	 					$dbstatus = false;
	 					$dbmessage = 'Something went wrong';
	 				}
 				}
 				else{
 					$dbstatus = 'validationerror';
 					$dbmessage = validation_errors();
 				}
 				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_CASE_ALLOTMENT': 

				$this->form_validation->set_rules('id', 'Case allotment id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('cs_case_allotment_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, alloted_to, case_no')->from('cs_case_allotment_tbl')->where('id', $id)->get()->row_array();

 						$case_det = $this->get_case_details_from_slug($data['case_no']);
 						$user = $this->get_user_details($data['alloted_to']);

 						$table_name = 'cs_case_allotment_tbl';
 						$table_id = $id;
 						$message = 'Case allotment of case '. $case_det['case_no'] .' allotted to '.$user['user_display_name']. ' ('.$user['job_title'].')'.' is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'DELETE_NOTIFICATION': 

				$this->form_validation->set_rules('id', 'Notification Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$id = $this->security->xss_clean($data['id']);

					$r = $this->db->where('id', $id)->update('notification_tbl', array('status' => 0, 'updated_at' => $this->date, 'updated_by' => $this->user_name));

					if($r){

						// Update the data logs table for data tracking
						$data = $this->db->select('id, notification_to')->from('notification_tbl')->where('id', $id)->get()->row_array();

 						$user = $this->get_user_details($data['notification_to']);

 						$table_name = 'notification_tbl';
 						$table_id = $id;
 						$message = 'Notification for '. $user['user_display_name'] .' ('.$user['job_title'].') is deleted.';
 						$this->update_data_logs($table_name, $table_id, $message);

						$dbstatus = TRUE;
						$dbmessage = 'Record deleted successfully';
					}
					else{
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'MARK_NOTIFICATION_SEEN':
				$this->db->where('notification_to', $this->user_name)->update('notification_tbl', array('seen' => 1));

				return true;
			break;

			case 'UPDATE_DISPLAY_BOARD_LIST': 

				$this->form_validation->set_rules('hidden_room_id', 'Id', 'required|xss_clean');
				$this->form_validation->set_rules('room_no', 'Room No.', 'required|xss_clean');
				$this->form_validation->set_rules('case_no', 'Case No.', 'required|xss_clean');
				$this->form_validation->set_rules('arbitrator_name', 'Arbitrator Name', 'required|xss_clean');

				if($this->form_validation->run()){
					$this->db->trans_begin();

					$hidden_room_id = $this->security->xss_clean($data['hidden_room_id']);
					$insert_data = [
						'case_no' => $this->security->xss_clean($data['case_no']),
						'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
						'room_status' => 'In Session',
						'todays_date' => date('d-m-Y'),
						'updated_by' => $this->user_name,
						'updated_at' => $this->date
					];

					$result = $this->db->where('room_id', $hidden_room_id)->update('cs_display_board_tbl', $insert_data);

					if($result){

						$insert_data2 = [
							'room_id' => $this->security->xss_clean($data['hidden_room_id']),
							'case_no' => $this->security->xss_clean($data['case_no']),
							'arbitrator_name' => $this->security->xss_clean($data['arbitrator_name']),
							'room_status' => 'In Session',
							'todays_date' => date('d-m-Y'),
							'created_by' => $this->user_name,
							'created_at' => $this->date,
							'updated_by' => $this->user_name,
							'updated_at' => $this->date
						];

						$result2 = $this->db->insert('cs_display_board_history_tbl', $insert_data2);

						if($result2){
							$this->db->trans_commit();
							$dbstatus = TRUE;
							$dbmessage = 'Record updated successfully';
						}
						else{
							$this->db->trans_rollback();
							$dbstatus = FALSE;
							$dbmessage = 'Error while saving. Please contact support.';
						}
					}
					else{
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$this->db->trans_rollback();
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'REMOVE_DISPLAY_BOARD_CASE': 

				$this->form_validation->set_rules('room_id', 'Room Id', 'required|xss_clean');

				if($this->form_validation->run()){
					$this->db->trans_begin();

					$room_id = $this->security->xss_clean($data['room_id']);
					$update_data = [
						'case_no' => '',
						'arbitrator_name' => '',
						'room_status' => 'Not In Session',
						'todays_date' => '',
						'updated_by' => $this->user_name,
						'updated_at' => $this->date
					];

					$result = $this->db->where('room_id', $room_id)->update('cs_display_board_tbl', $update_data);

					if($result){
						$this->db->trans_commit();
						$dbstatus = TRUE;
						$dbmessage = 'Record removed from display board successfully';
					}
					else{
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$this->db->trans_rollback();
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

			case 'CANCEL_CAUSE_LIST': 

				$this->form_validation->set_rules('hidden_id', 'Id', 'required|xss_clean');
				$this->form_validation->set_rules('cancel_remarks', 'Remarks', 'required|xss_clean|max_length[200]');

				if($this->form_validation->run()){
					$this->db->trans_begin();

					$hidden_id = $this->security->xss_clean($data['hidden_id']);
					$update_data = [
						'active_status' => 2,
						'remarks' => $this->security->xss_clean($data['cancel_remarks']),
						'updated_by' => $this->user_name,
						'updated_at' => $this->date
					];

					$result = $this->db->where('id', $hidden_id)->update('cause_list_tbl', $update_data);

					if($result){
						$this->db->trans_commit();
						$dbstatus = TRUE;
						$dbmessage = 'Cause list cancelled successfully';
					}
					else{
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Something went wrong. Please try again.';
					}
				}
				else{
					$this->db->trans_rollback();
					$dbstatus = 'validationerror';
					$dbmessage = validation_errors();
				}
				return array('status' => $dbstatus, 'msg' => $dbmessage);
			break;

 			default:
					return array('status'=>false, 'msg'=>NO_OPERATION);
 		}
 	}

 	/*
	* Function to if the case is alloted to user or not
 	*/
 	public function check_case_allotment($case_slug){
 		$result = $this->db->get_where('cs_case_allotment_tbl', array('case_no' => $case_slug, 'alloted_to' => $this->user_name, 'status' => 1));

 		if($result && $result->num_rows() == 1){
 			return true;
 		}
 		else{
 			return false;
 		}
 	}

 	/*
	* Function to if the case is already alloted to user or not
 	*/
 	public function check_case_allotted($case_slug, $alloted_to){
 		$result = $this->db->get_where('cs_case_allotment_tbl', array('case_no' => $case_slug, 'alloted_to' => $alloted_to, 'status' => 1));

 		if($result && $result->num_rows() > 0){
 			return true;
 		}
 		else{
 			return false;
 		}
 	}

 	// check if the current user has ADMIN or DIAC roles
	// If any of these roles they have.
	// They can access every edit page.
 	public function check_user_case_allotment($case_slug, $type = ''){

		$check_user_role = $this->db->get_where('user_master', array('user_name' => $this->user_name));
		
		if($check_user_role){
			$get_user_details = $check_user_role->row_array();
			
			if(in_array($get_user_details['primary_role'], array('DIAC', 'ADMIN', 'COORDINATOR'))){
				$check_result = true;
			}
			else{
				// Check if the case is allotted to the user ot not
				$check_result = $this->check_case_allotment($case_slug);
			}
			

			if($check_result){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
 	}

 	// Function to check if case number is exist or not
 	public function check_case_number($slug){
 		$slug = $this->security->xss_clean($slug);
		$query = $this->db->get_where('cs_case_details_tbl', array('slug' => $slug, 'status' => 1));
		$count = $query->num_rows();

		if($count == 1){
			return true;
		}
		else{
			return false;
		}
 	}


 	// Get case details using case number
 	public function get_case_details_ucn($case_no){
 		$q = $this->db->get_where('cs_case_details_tbl', ['case_no' => $case_no]);
 		return $q->row_array();
 	}
 	

 	/*
	* Function to get the last updated time in all case tables
 	*/
 	public function get_last_updated_datetime($case_slug){
 		
 		$case_details_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_on as last_date
				FROM cs_case_details_tbl
				WHERE slug = '$case_slug'
				
				UNION ALL

				SELECT updated_on as last_date
				FROM cs_case_details_tbl
				WHERE slug = '$case_slug'
			) AS t

			")->row_array();

 		$claim_res_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_on as last_date
				FROM cs_claimant_respondant_details_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_on as last_date
				FROM cs_claimant_respondant_details_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();


 		// Notings
 		$noting_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_noting_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_noting_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

 		// counsels
 		$counsels_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_counsels_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_counsels_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

 		// arbitral tribunal
 		$arb_tri_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_arbitral_tribunal_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_arbitral_tribunal_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

 		// award_termination
 		$award_term_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_award_term_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_award_term_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

 		// Status of pleadings
		$sop_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_status_of_pleadings_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_status_of_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_other_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_other_pleadings_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_other_correspondance_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_other_correspondance_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		// fees and cost
		$fee_cost_last_updated = $this->db->query("
			SELECT
			MAX(t.last_date) AS last_updated_at
			FROM (
				SELECT created_at as last_date
				FROM cs_fee_details_tbl
				WHERE case_no = '$case_slug'
				
				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_details_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_fee_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_cost_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_cost_deposit_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_fee_refund_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_fee_refund_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT created_at as last_date
				FROM cs_at_fee_released_tbl
				WHERE case_no = '$case_slug'

				UNION ALL

				SELECT updated_at as last_date
				FROM cs_at_fee_released_tbl
				WHERE case_no = '$case_slug'
			) AS t

			")->row_array();

		return array(
			'case_details_last_updated' => $case_details_last_updated,
			'claim_res_last_updated' => $claim_res_last_updated,
			'sop_last_updated' => $sop_last_updated,
			'counsels_last_updated' => $counsels_last_updated,
			'arb_tri_last_updated' => $arb_tri_last_updated,
			'fee_cost_last_updated' => $fee_cost_last_updated,
			'award_term_last_updated' => $award_term_last_updated,
			'noting_last_updated' => $noting_last_updated
		);
 	}

 	/*
	* Function to update the data logs table
 	*/
 	public function update_data_logs($table_name, $table_id, $message){
 		$result = $this->db->insert('data_logs_tbl', [
 			'table_name' => $table_name,
 			'table_id' => $table_id,
 			'message' => $message,
 			'role_code' => $this->role,
 			'created_by' => $this->user_name,
 			'created_at' => $this->date
 		]);

 		if($result){
 			return true;
 		}
 		else{
 			return false;
 		}

 	}


 	/*
	* Get case number from slug
 	*/
 	public function get_case_details_from_slug($slug){
 		$case_det = $this->db->select('id, case_no')->from('cs_case_details_tbl')->where('slug', $slug)->get()->row_array();

 		return $case_det;
 	}

 	/*
	* Function to get the user details with the username
 	*/
 	public function get_user_details($username){
 		$user = $this->db->select('user_code, user_name, user_display_name, job_title, primary_role')->where(array('user_name' => $username))->from('user_master')->get()->row_array();

 		return $user;
 	}

}