<?php

class Admin_model extends CI_model {

    private $role;
	private $_batchImport;
    function __construct() {
        parent::__construct();
        $this->load->helper('date');

        if (ENVIRONMENT == 'production') {
            $this->db->save_queries = FALSE;
        }
        date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());
        
        $this->role 		= $this->session->userdata('role');
        $this->user_name 	= $this->session->userdata('user_name');
    }
    /**
     * 	Generate random registration_no 
     */
    public function rand_number($length) {
        $chars = "0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
    public function setBatchImport($batchImport) {
      	$this->_batchImport = $batchImport ;
    }
    public function admin($data, $op, $stage = null) {
    	date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());
		
        switch ($op) {
			case 'get_emailprovider_data':            
			 	$order = ''; 
			    $Ocolumn = '';
			    $Odir = '';
				$order = $this->input->post('order');
				if ( $order )
					{
						foreach($order as $row) {
							$Ocolumn= $row['column'];
							$Odir=  $row['dir'];
						}
						$this->db->order_by($Ocolumn,$Odir);
					}else{
						$this->db->order_by(1,"ASC");
					}
			 	$search = $this->input->post('search');
			 	$header = array('provider_name','host_name','email_id');
			 	
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				
			    $iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');
				
                $this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('email_provider_setup');
                $this->db->select('provider_id,provider_name,host_name,port_no,email_id,password,smtp_auth,smtp_secure,record_status');	
            
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				
				$header = array('provider_name','host_name','email_id');
				
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
              	$this->db->from('email_provider_setup');
                $this->db->select('provider_id,provider_name,host_name,port_no,email_id,password,smtp_auth,smtp_secure,record_status');	
                
				$res1 = $this->db->get();
				$output["draw"] = intval( $this->input->post('draw') );
				$output['iTotalRecords'] = $res1->num_rows(); 
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = $iDisplayStart+1;
				foreach($query as $aRow)
				{
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach($aRow as $key=>$value)
					{
						
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
			case 'add_provider':
				$this->db->trans_begin();
				try{
					$id_Qry = $this->db->query("SELECT CASE  p_id 
						WHEN 1 THEN CONCAT('P000',p_val) 
						WHEN 2 THEN CONCAT('P00',p_val) 
						WHEN 3 THEN CONCAT('P0',p_val)
						WHEN 4 THEN CONCAT('P',p_val)
						WHEN 5 THEN CONCAT('P',p_val) END AS provider_id FROM (
						SELECT LENGTH(IFNULL(MAX(CAST(SUBSTRING(provider_id,3) AS SIGNED )),0)+1) AS p_id ,
						IFNULL(MAX(CAST(SUBSTRING(provider_id,3) AS SIGNED )),0)+1 AS p_val 
						FROM email_provider_setup) a");
	                $result = $id_Qry->result_array();
	                $row1 = array_shift($result);
					
					$data = array( "provider_id" =>$row1['provider_id'],
								"provider_name" => $this->security->xss_clean($this->input->post('txtProvidername')),
								"host_name" => $this->security->xss_clean($this->input->post('txtHostName')),
								"port_no" => $this->security->xss_clean($this->input->post('txtPort')),
								"email_id" => $this->security->xss_clean($this->input->post('txt_Email')),
								"password" => $this->security->xss_clean($this->input->post('txt_password')),
								"smtp_auth" => $this->security->xss_clean($this->input->post('cmb_smptauth')),
								"smtp_secure" => $this->security->xss_clean($this->input->post('cmb_smptsecure')),
								"ip_address" =>$this->input->ip_address(),
								"created_by" => $this->user_name,
								"created_on" => $date,
								"record_status" =>$this->security->xss_clean($this->input->post('cmbStatus'))
							);
					$insert_provider = $this->db->insert('email_provider_setup',$data);
					if( ! $insert_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
						$dbmessage = 'Provider Setup successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			
			case 'edit_provider':
				$this->db->trans_begin();
				try{
					$data = array( "provider_name" => $this->security->xss_clean($this->input->post('txtProvidername')),
								"host_name" => $this->security->xss_clean($this->input->post('txtHostName')),
								"port_no" => $this->security->xss_clean($this->input->post('txtPort')),
								"email_id" => $this->security->xss_clean($this->input->post('txt_Email')),
								"password" => $this->security->xss_clean($this->input->post('txt_password')),
								"smtp_auth" => $this->security->xss_clean($this->input->post('cmb_smptauth')),
								"smtp_secure" => $this->security->xss_clean($this->input->post('cmb_smptsecure')),
								"ip_address" =>$this->input->ip_address(),
								"updated_by" => $this->user_name,
								"updated_on" => $date,
								"record_status" =>$this->security->xss_clean($this->input->post('cmbStatus'))
							);
					$this->db->where('provider_id',$this->input->post('hideemail_provider_id'));
					
					$update_provider = $this->db->update('email_provider_setup',$data);
					if(!$update_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'Provider update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'delete_emailprovider':
				$this->db->trans_begin();
				try{
					$this -> db -> where('provider_id', $this->input->post('provider_id'));
	  				$delete_provider = $this -> db -> delete('email_provider_setup');
					if(!$delete_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'Email Provider Data Delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* logic: To operate data for Email Provider Master
			* case:get_resourcedata,add_resource,add_role,delete_role,edit_resource,delete_resource
			* author:Debashish jyotish
			* date :18/02/2018
			*/
			case 'get_emailsetup_data':
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
	                $header = array('subject','content','provider_name','institute_code');//search filter will work on this column
	                if ($search['value'] != '') {
	                    for ($i = 0; $i < count($header); $i++) {
	                        $this->db->or_like($header[$i], $search['value']);
	                    }
	                }
	                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
	                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)
	                $this->db->limit($iDisplayLength, $iDisplayStart);
	                $this->db->from('email_setup');
                	$this->db->select('email_setup_id,email_type,subject,content,provider_name,institute_code,status');	
	               	$this->db->order_by('email_setup_id','DESC');
	               	$res = $this->db->get();
	                $query = $res->result_array();
	                $output = array("aaData" => array());
					/*----FOR PAGINATION-----*/
	                if ($search['value'] != '') {
	                    for ($i = 0; $i < count($header); $i++) {
	                        $this->db->or_like($header[$i], $search['value']);
	                    }
	                }
	                $this->db->from('email_setup');
                	$this->db->select('email_setup_id,email_type,subject,content,provider_name,institute_code,status');	
                	$this->db->order_by('email_setup_id','DESC');
	                $res1 = $this->db->get();
	                $output["draw"] = intval($this->input->post('draw'));
	                $output['iTotalRecords'] = $res1->num_rows();
	                $output['iTotalDisplayRecords'] = $res1->num_rows();
	                $slno = $iDisplayStart+1;
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
			case 'add_email':
				$this->db->trans_begin();
				try{
					$data = array(
							"email_type" => $this->security->xss_clean($this->input->post('txtMailType')),
							"subject" => $this->security->xss_clean($this->input->post('txtSubject')),
							"content" => $this->security->xss_clean($this->input->post('txtContent')),
							"provider_name" => $this->security->xss_clean($this->input->post('txtProvider')),
							"institute_code" => 'STL',
							"status" => $this->security->xss_clean($this->input->post('cmbEmailStatus')),
							"created_by" => $this->user_name,
							"created_on" => $date
						);
					$insert_email_setup = $this->db->insert('email_setup',$data);
					if( ! $insert_email_setup){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
						$dbmessage = 'Email Setup successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'edit_email':
				$this->db->trans_begin();
				try{
					$data = array(
				 		"email_type" => $this->security->xss_clean($this->input->post('txtMailType')),
						"subject" => $this->security->xss_clean($this->input->post('txtSubject')),
						"content" => $this->security->xss_clean($this->input->post('txtContent')),
						"provider_name" => $this->security->xss_clean($this->input->post('txtProvider')),
						"institute_code" => 'STL',
						"status" => $this->security->xss_clean($this->input->post('cmbEmailStatus')),
						"updated_by" => $this->user_name,
						"updated_on" => $date
					);
					$this->db->where('email_setup_id',$this->input->post('hidemail_setup_id'));
					$update_provider = $this->db->update('email_setup',$data);
					if(!$update_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'delete_EmailSetup':
				$this->db->trans_begin();
				try{
					$this->db->where('email_setup_id',$this->input->post('email_setup_id'));
					$delete_smsSetup = $this->db->delete('email_setup');
					if(!$delete_smsSetup){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* logic: To operate data for Email Provider Master
			* case:get_resourcedata,add_resource,add_role,delete_role,edit_resource,delete_resource
			* author:Debashish jyotish
			* date :19/02/2018
			*/
			case 'get_smsprovider_data':            
			 	$order = ''; 
			    $Ocolumn = '';
			    $Odir = '';
				$order = $this->input->post('order');
				if ( $order )
					{
						foreach($order as $row) {
							$Ocolumn= $row['column'];
							$Odir=  $row['dir'];
						}
						$this->db->order_by($Ocolumn,$Odir);
					}else{
						$this->db->order_by(1,"ASC");
					}
			 	$search = $this->input->post('search');
			 	$header = array('provider_name','sms_url','user_name');
			 	
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				
			    $iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');
				
                $this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('sms_provider_setup');
                $this->db->select('provider_id,provider_name,sms_url,user_name,password,sender,record_status');	
             	$this->db->order_by('id','DESC');
				
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				
				$header = array('provider_name','sms_url','user_name');
				
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
              	$this->db->from('sms_provider_setup');
                $this->db->select('provider_id,provider_name,sms_url,user_name,password,sender,record_status');	
                $this->db->order_by('id','DESC');
        
                
				$res1 = $this->db->get();
				$output["draw"] = intval( $this->input->post('draw') );
				$output['iTotalRecords'] = $res1->num_rows(); 
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = $iDisplayStart+1;
				foreach($query as $aRow)
				{
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach($aRow as $key=>$value)
					{
						
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
			case 'add_smsprovider':
				$this->db->trans_begin();
				try{
					$id_Qry = $this->db->query("SELECT CASE  p_id 
						WHEN 1 THEN CONCAT('S000',p_val) 
						WHEN 2 THEN CONCAT('S00',p_val) 
						WHEN 3 THEN CONCAT('S0',p_val)
						WHEN 4 THEN CONCAT('S',p_val)
						WHEN 5 THEN CONCAT('S',p_val) END AS sms_id FROM (
						SELECT LENGTH(IFNULL(MAX(CAST(SUBSTRING(provider_id,3) AS SIGNED )),0)+1) AS p_id ,
						IFNULL(MAX(CAST(SUBSTRING(provider_id,3) AS SIGNED )),0)+1 AS p_val 
						FROM sms_provider_setup) a");
	                $result = $id_Qry->result_array();
	                $row1 = array_shift($result);
					
					$data = array( "provider_id" => $row1['sms_id'],
							"provider_name" 	 => $this->security->xss_clean($this->input->post('txtProviderName')),
							"sms_url" 			 => $this->security->xss_clean($this->input->post('txtSMSUrl')),
							"user_name" 		 => $this->security->xss_clean($this->input->post('txt_UserName')),
							"password" 			 => $this->security->xss_clean($this->input->post('txt_password')),
							"sender" 			 => $this->security->xss_clean( $this->input->post('txt_Sender')),
							"ip_address" 		 => $this->input->ip_address(),
							"created_by" 		 => $this->user_name,
							"created_on" 		 => $date,
							"record_status" 	 => $this->security->xss_clean( $this->input->post('cmbSMSProviderstatus'))
						);
					$insert_provider = $this->db->insert('sms_provider_setup',$data);
					if( ! $insert_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Saving';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
						$dbmessage = 'Setup successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			
			case 'edit_smsprovider':
				$this->db->trans_begin();
				try{
					$data = array( 
							"provider_name" 	 => $this->security->xss_clean($this->input->post('txtProviderName')),
							"sms_url" 			 => $this->security->xss_clean($this->input->post('txtSMSUrl')),
							"user_name" 		 => $this->security->xss_clean($this->input->post('txt_UserName')),
							"password" 			 => $this->security->xss_clean($this->input->post('txt_password')),
							"sender" 			 => $this->security->xss_clean($this->input->post('txt_Sender')),
							"ip_address" 		 =>	$this->input->ip_address(),
							"updated_by" 		=>  $this->user_name,
							"updated_on" 		=>  $date,
							"record_status" 	=>  $this->security->xss_clean( $this->input->post('cmbSMSProviderstatus'))
						);
					$this->db->where('provider_id',$this->security->xss_clean($this->input->post('hidesms_provider_id')));
					$update_provider = $this->db->update('sms_provider_setup',$data);
					if(!$update_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Editing';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'delete_smsprovider':
				$this->db->trans_begin();
				try{
					$this -> db -> where('provider_id', $this->security->xss_clean($this->input->post('provider_id')));
	  				$delete_provider = $this -> db -> delete('sms_provider_setup');
					if(!$delete_provider){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'get_smssetup':            
			 	$order = ''; 
			    $Ocolumn = '';
			    $Odir = '';
				$order = $this->input->post('order');
				if ( $order )
					{
						foreach($order as $row) {
							$Ocolumn= $row['column'];
							$Odir=  $row['dir'];
						}
						$this->db->order_by($Ocolumn,$Odir);
					}else{
						$this->db->order_by(1,"ASC");
					}
			 	$search = $this->input->post('search');
			 	$header = array('sms_type','subject','content','provider_name');
			 	
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
				
			    $iDisplayLength = $this->input->post('length');
				$iDisplayStart = $this->input->post('start');
				
                $this->db->limit($iDisplayLength, $iDisplayStart);
				$this->db->from('sms_setup');
                $this->db->select('sms_setup_id,sms_type,subject,content,provider_name,status');	
                $this->db->order_by('sms_setup_id','DESC');
				$res = $this->db->get();
				$query = $res->result_array();
				$output = array("aaData" => array());
				
				$header = array('sms_type','subject','content','provider_name');
				
			 	if($search['value'] != ''){
					for($i=0;$i <count($header);$i++ ){
						$this->db->or_like($header[$i], $search['value']);
					}
				}
              	$this->db->from('sms_setup');
                $this->db->select('sms_setup_id,sms_type,subject,content,provider_name,status');	
               	$this->db->order_by('sms_setup_id','DESC');
				$res1 = $this->db->get();
				$output["draw"] = intval( $this->input->post('draw') );
				$output['iTotalRecords'] = $res1->num_rows(); 
				$output['iTotalDisplayRecords'] =  $res1->num_rows();
				$slno = 1;
				foreach($query as $aRow)
				{
					$row[0] = $slno;
					$row['sl_no'] = $slno;
					$i = 1;
					foreach($aRow as $key=>$value)
					{
						
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
			
			case 'countrydata':
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
                $header = array('country_code','country_name');//search filter will work on this column
               	$filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }

                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                $this->db->from('country_master','country_code');
                $this->db->select("country_code,country_name,record_status");
                $this->db->order_by("updated_on",'DESC');
            	if($filter_string!=''){
					$this->db->where($filter_string);
				}
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                
                $this->db->from('country_master');
                $this->db->select("country_code,country_name,record_status");
                if($filter_string!=''){
					$this->db->where($filter_string);
				}
                $this->db->order_by("updated_on",'DESC');
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_country':
            	$this->db->trans_begin();
				try{
	                $data = array("country_code" => $this->security->xss_clean($this->input->post('country_code')),
	                    "country_name" => $this->security->xss_clean($this->input->post('country_name')),
	                    "record_status" => $this->security->xss_clean($this->input->post('country_status')),
	                    "created_by" => $this->user_name,
	                    "created_on" => $date,
	                    "updated_by" => $this->user_name,
	                    "updated_on" => $date
	                );
	                $insert_country = $this->db->insert('country_master',$data);
	                if( ! $insert_country){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_country':
            	$this->db->trans_begin();
				try{
	                $data = array( "country_name" => $this->security->xss_clean($this->input->post('country_name')),
	                            "record_status" => $this->security->xss_clean($this->input->post('country_status')),
	                            "updated_by" => $this->user_name,
	                            "updated_on" => $date
	                        );
	                $this->db->where('country_code',$this->security->xss_clean($this->input->post('country_code')));
	                $edit_country = $this->db->update('country_master',$data);
	                if(!$edit_country){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_country':
            	$this->db->trans_begin();
				try{
	            	$this->db->where('country_code', $this->security->xss_clean($this->input->post('country_code')));
	      			$delete_country = $this->db->delete('country_master'); 
	                if (!$delete_country){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data deleted successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status' => $dbstatus, 'msg' => $dbmessage);
            break;
            case 'GET_GENCODE_GROUP':
                $this->db->distinct('gen_code_group');
                $this->db->select('gen_code_group');
				$this->db->where('status','1');
                $res = $this->db->get('gen_code_desc');
                return $res->result_array();
            break;
            case 'gencode_data':
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
                $header = array('gen_code_group','gen_code','description');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }

                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                $this->db->from('gen_code_desc');
                $this->db->select("id,gen_code_group,gen_code,description,sl_no,status");
                $this->db->order_by('id','DESC');
                if($filter_string!=''){
					$this->db->where($filter_string);
				}
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                $this->db->from('gen_code_desc');
                $this->db->select("id,gen_code_group,gen_code,description,sl_no,status");
                 if($filter_string!=''){
					$this->db->where($filter_string);
				}
                 $this->db->order_by('id','DESC');
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_gen_code':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	            		"gen_code_group" => $this->security->xss_clean($this->input->post('gen_code_group')),
	                    "gen_code" 		 => $this->security->xss_clean($this->input->post('gen_code')),
	                    "description" 	 => $this->security->xss_clean($this->input->post('description')),
	                    "sl_no" 		 => $this->security->xss_clean($this->input->post('sl_no')),
	                    "status" 		 => $this->security->xss_clean($this->input->post('status')),
	                    "created_by" 	 => $this->user_name,
	                    "created_on"     => $date
	                );
	                $insert_gencode = $this->db->insert('gen_code_desc',$data);
	                if( ! $insert_gencode){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_gen_code':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	                	"description" => $this->security->xss_clean($this->input->post('description')),
	                    "sl_no" 	  => $this->security->xss_clean($this->input->post('sl_no')),
	                    "status" 	  => $this->security->xss_clean($this->input->post('status')),
	                    "updated_by"  => $this->user_name,
	                    "updated_on"  => $date
	                );
	                $this->db->where('id',$this->security->xss_clean($this->input->post('hid_id')));
	                $edit_gencode = $this->db->update('gen_code_desc',$data);
	                if(!$edit_gencode){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data updated successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_genCodeDesc':
				$this->db->trans_begin();
				try{
					$this -> db -> where('gen_code_group', $this->input->post('gen_code_group'));
					$this -> db -> where('gen_code', $this->input->post('gen_code'));
	  				$delete_genCodeDesc = $this -> db -> delete('gen_code_desc');
					if(!$delete_genCodeDesc){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'Deleted successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* Modify By : Subhashree Jena
			* date :29-08-2019
			* Purpose : Block Master
			* @var 
			* 
			*/
			case 'block_master':
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
                $header = array('a.block_name','a.block_code','b.district_name','c.state_name', 'd.country_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }

                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                $this->db->from('block_master a');
                $this->db->select("a.block_name,a.block_code,b.district_name,c.state_name,d.country_name,a.record_status,a.fk_district_code,a.fk_state_code,a.fk_country_code");
                $this->db->join('district_master b','a.fk_district_code = b.district_code','left');
                $this->db->join('state_master c','a.fk_state_code = c.state_code','left');
                $this->db->join('country_master d','a.fk_country_code = d.country_code','left');
                if($data['cmb_district_filter']!=''){
					$this->db->where('a.fk_district_code',$data['cmb_district_filter']);
				}
				if($filter_string!=''){
							$this->db->where($filter_string);
						}
                $this->db->order_by('a.updated_on','DESC');
                
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                $this->db->from('block_master a');
                $this->db->select("a.block_name,b.district_name,c.state_name,d.country_name,a.record_status,a.block_code,a.fk_district_code,a.fk_state_code,a.fk_country_code");
                $this->db->join('district_master b','a.fk_district_code = b.district_code','left');
                $this->db->join('state_master c','a.fk_state_code = c.state_code','left');
                $this->db->join('country_master d','a.fk_country_code = d.country_code','left');
                $this->db->order_by('a.updated_on','DESC');
              	 if($data['cmb_district_filter']!=''){
					$this->db->where('a.fk_district_code',$data['cmb_district_filter']);
				}
				if($filter_string!=''){
							$this->db->where($filter_string);
						}
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_block_master':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	            		"block_code" 		=> $this->security->xss_clean($this->input->post('block_code')),
	                    "block_name" 		=> $this->security->xss_clean($this->input->post('block_name')),
	                    "fk_district_code" 	=> $this->security->xss_clean($this->input->post('cmb_district')),
	                    "fk_state_code" 	=> $this->security->xss_clean($this->input->post('cmb_state')),
	                    "fk_country_code" 	=> $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" 	=> $this->security->xss_clean($this->input->post('block_status')),
	                    "created_by" 	 	=> $this->user_name,
	                    "created_on"     	=> $date,
	                    "updated_by" 	 	=> $this->user_name,
	                    "updated_on"     	=> $date
	                );
	                $insert_block_master = $this->db->insert('block_master',$data);
	                if( ! $insert_block_master){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_block_master':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	                    "block_name" 		=> $this->security->xss_clean($this->input->post('block_name')),
	                    "fk_district_code" 	=> $this->security->xss_clean($this->input->post('cmb_district')),
	                    "fk_state_code" 	=> $this->security->xss_clean($this->input->post('cmb_state')),
	                    "fk_country_code" 	=> $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" 	=> $this->security->xss_clean($this->input->post('block_status')),
	                    "created_by" 	 	=> $this->user_name,
	                    "created_on"     	=> $date
	                );
	                $this->db->where('block_code',$this->security->xss_clean($this->input->post('block_code')));
	                $update_block_master = $this->db->update('block_master',$data);
	                if( ! $update_block_master){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_block':
				$this->db->trans_begin();
				try{
					$this->db->where('block_code',$this->security->xss_clean($this->input->post('block_code')));
	  				$delete_block_master = $this->db-> delete('block_master');
					if(!$delete_block_master){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'Deleted successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* Modify By : Subhashree Jena
			* date :29-08-2019
			* Purpose : GP Master
			* @var 
			* 
			*/
			case 'gp_master':
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
                    //$this->db->order_by(1, "ASC");
                }
                $search = $this->input->post('search');
                $header = array('a.gp_name','a.pk_gp_code','b.block_name','c.district_name','d.state_name', 'e.country_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }
                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                $this->db->from('gp_master a');
                $this->db->select("a.gp_name,a.pk_gp_code, b.block_name,a.fk_district_census_code,c.district_name, d.state_name, e.country_name, a.status, a.fk_block_code, a.fk_district_code, a.fk_state_code, a.fk_country_code");
                $this->db->join('block_master b','a.fk_block_code = b.block_code','left');
                $this->db->join('district_master c','a.fk_district_code = c.district_code','left');
                $this->db->join('state_master d','a.fk_state_code = d.state_code','left');
                $this->db->join('country_master e','a.fk_country_code = e.country_code','left');
                if($data['cmb_district_filter']!=''){
					$this->db->where('a.fk_district_code',$data['cmb_district_filter']);
				}
				if($data['cmb_block_filter']!=''){
					$this->db->where('a.fk_block_code',$data['cmb_block_filter']);
				}
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
                $this->db->order_by('a.updated_on','DESC');
                $res = $this->db->get();
                $query = $res->result_array();
                //print_r($this->db->last_query());die();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                
                $this->db->from('gp_master a');
                $this->db->select("a.gp_name, b.block_name,a.fk_district_census_code,c.district_name, d.state_name, e.country_name, a.status,a.pk_gp_code, a.fk_block_code, a.fk_district_code, a.fk_state_code, a.fk_country_code");
                $this->db->join('block_master b','a.fk_block_code = b.block_code','left');
                $this->db->join('district_master c','a.fk_district_code = c.district_code','left');
                $this->db->join('state_master d','a.fk_state_code = d.state_code','left');
                $this->db->join('country_master e','a.fk_country_code = e.country_code','left');
                if($data['cmb_district_filter']!=''){
					$this->db->where('a.fk_district_code',$data['cmb_district_filter']);
				}
				if($data['cmb_block_filter']!=''){
					$this->db->where('a.fk_block_code',$data['cmb_block_filter']);
				}
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
                //$this->db->order_by('a.id','DESC');
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_gp_master':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	                    "pk_gp_code" => $this->security->xss_clean($data['gp_code']),
	                    "gp_name" => $this->security->xss_clean($data['gp_name']),
	                    "fk_block_code" => $this->security->xss_clean($data['cmb_block']),
	                    "fk_district_code" => $this->security->xss_clean($data['cmb_dist']),
	                    "fk_district_census_code" => $this->security->xss_clean($data['district_census_code']),
	                    "fk_state_code" => $this->security->xss_clean($data['cmb_state']),
	                    "fk_country_code" => $this->security->xss_clean($data['cmb_country']),
	                    "status" => $this->security->xss_clean($data['gp_status']),
	                    "created_by"=> $this->user_name,
	                    "created_on"=>$date,
	                    "created_by"=> $this->user_name,
	                    "updated_on"=>$date
	                );
	                $insert_circle = $this->db->insert('gp_master',$data);
	                if(!$insert_circle){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_gp_master':
            	$this->db->trans_begin();
				try{
	                $edit_data = array(  
	                	"gp_name" => $this->security->xss_clean($data['gp_name']),
	                    "fk_block_code" => $this->security->xss_clean($data['cmb_block']),
	                    "fk_district_code" => $this->security->xss_clean($data['cmb_dist']),
	                    "fk_district_census_code" => $this->security->xss_clean($data['district_census_code']),
	                    "fk_state_code" => $this->security->xss_clean($data['cmb_state']),
	                    "fk_country_code" => $this->security->xss_clean($data['cmb_country']),
	                    "status" => $this->security->xss_clean($data['gp_status']),
	                    'created_by'=> $this->user_name,
	                    'created_on'=>$date
	                );
	                $this->db->where('pk_gp_code',$this->security->xss_clean($data['gp_code']));
	                $update_circle = $this->db->update('gp_master',$edit_data);
	                if(!$update_circle){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data updated successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_gp':
				$this->db->trans_begin();
				try{
					$this->db->where('pk_gp_code',$this->security->xss_clean($data['gp_code']));
	  				$delete_gp = $this->db->delete('gp_master');
					if(!$delete_gp){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'Gram Panchayat data deleted successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* Modify By : Subhashree Jena
			* date :29-08-2019
			* Purpose : Village Master
			* @var 
			* 
			*/
			case 'village_master':
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
                $header = array('a.village_name','b.gp_name','c.block_name','d.district_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }
                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)
                $this->db->limit($iDisplayLength, $iDisplayStart);
                $this->db->from('village_master a');
                $this->db->select("a.pk_village_code,a.village_name,b.gp_name,c.block_name,d.district_name,a.status,a.gp_code,c.block_code,d.district_code,sm.state_code");
                $this->db->join('gp_master b','b.pk_gp_code = a.gp_code','inner');
                $this->db->join('block_master c','c.block_code= b.fk_block_code','inner');
                $this->db->join('district_master d','d.district_code = c.fk_district_code','inner');
                $this->db->join('state_master sm','sm.state_code = d.state_code','inner');
                if($data['cmb_district_filter']!=''){
					$this->db->where('c.fk_district_code',$data['cmb_district_filter']);
				}
				 if( $data['cmb_block_filter']!='' ){
					$this->db->where('b.fk_block_code',$data['cmb_block_filter']);
				}
				 if( $data['cmb_gp_filter']!=''){
					$this->db->where('a.gp_code',$data['cmb_gp_filter']);
				}
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
                $this->db->order_by('a.updated_on','DESC');
                $res = $this->db->get();
                $query = $res->result_array();
                //print_r($this->db->last_query());die();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
               
                $this->db->from('village_master a');
                $this->db->select("a.pk_village_code");
                $this->db->join('gp_master b','b.pk_gp_code = a.gp_code','inner');
                $this->db->join('block_master c','c.block_code= b.fk_block_code','inner');
                $this->db->join('district_master d','d.district_code = c.fk_district_code','inner');
                $this->db->join('state_master sm','sm.state_code = d.state_code','inner');
               	if($data['cmb_district_filter']!=''){
					$this->db->where('c.fk_district_code',$data['cmb_district_filter']);
				}
				 if( $data['cmb_block_filter']!='' ){
					$this->db->where('b.fk_block_code',$data['cmb_block_filter']);
				}
				 if( $data['cmb_gp_filter']!=''){
					$this->db->where('a.gp_code',$data['cmb_gp_filter']);
				}
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
                //$this->db->order_by('a.id','DESC');
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_smssetup':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	                        "sms_type" => $this->security->xss_clean($this->input->post('txtSMSType')),
	                        "subject" => $this->security->xss_clean($this->input->post('txtSubject')),
	                        "content" => $this->security->xss_clean($this->input->post('txtSMSContent')),
	                        "provider_name" => $this->security->xss_clean($this->input->post('txtSMSProvider')),
	                        "institute_code" => 'STL',
	                        'created_by'=> $this->user_name,
	                        'created_on'=>$date,
	                        'status'=>$this->security->xss_clean($this->input->post('cmbSMSstatus'))
	                    );
	                $insert_sms = $this->db->insert('sms_setup',$data);
	                if(!$insert_sms){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
             case 'edit_smssetup':
             	$this->db->trans_begin();
				try{
	                $updatedata = array(  
		    				"sms_type" => $this->security->xss_clean($this->input->post('txtSMSType')),
	                        "subject" => $this->security->xss_clean($this->input->post('txtSubject')),
	                        "content" => $this->security->xss_clean($this->input->post('txtSMSContent')),
	                        "provider_name" => $this->security->xss_clean($this->input->post('txtSMSProvider')),
		                    "institute_code" => 'STL',
		                    "updated_by" => $this->user_name,
		                    'updated_on'=>$date,
		                    'status'=>$this->security->xss_clean($this->input->post('cmbSMSstatus'))
		                );
	                $this->db->where('sms_setup_id',$this->security->xss_clean($this->input->post('sms_setup_id')));
	                $edit_user = $this->db->update('sms_setup',$updatedata);
	                if(!$edit_user){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_smssetup':
            	$this->db->trans_begin();
				try{
					$this->db->where('sms_setup_id',$this->security->xss_clean($this->input->post('sms_setup_id')));
					$delete_smsSetup = $this->db->delete('sms_setup');
					if(!$delete_smsSetup){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
           	case 'get_state_master':
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
                $header = array('state_master.state_code','state_master.state_name', 'country_master.country_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }

                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                
                $this->db->from('state_master');
                $this->db->select("state_master.state_code,state_master.state_name,country_master.country_name,state_master.record_status,state_master.country_code");
                $this->db->join('country_master','country_master.country_code=state_master.country_code','left');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('state_master.updated_on','DESC');	
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                
                $this->db->from('state_master');
                $this->db->select("state_master.state_code,state_master.state_name,country_master.country_name,state_master.record_status,state_master.country_code");
                $this->db->join('country_master','country_master.country_code=state_master.country_code','left');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('state_master.updated_on','DESC');
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_state_master':
            	$this->db->trans_begin();
				try{
	                $data = array( 
	                    "state_code" => $this->security->xss_clean($this->input->post('state_code')),
	                    "state_name" => $this->security->xss_clean($this->input->post('state_name')),
	                    "country_code" => $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" => $this->security->xss_clean($this->input->post('state_status')),
	                    "created_by"=> $this->user_name,
	                    "created_on"=>$date,
	                    "updated_by"=> $this->user_name,
	                    "updated_on"=>$date
	                );
	                $insert_user = $this->db->insert('state_master',$data);
	                if(!$insert_user){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
					
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'state_master_edit':
            	$this->db->trans_begin();
				try{
	                $edit_data = array(
	                    "state_name" => $this->security->xss_clean($this->input->post('state_name')),
	                    "country_code" => $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" => $this->security->xss_clean($this->input->post('state_status')),
	                    'updated_by'=> $this->user_name,
	                    'updated_on'=>$date
	                );
	                $this->db->where('state_code',$this->security->xss_clean($this->input->post('state_code')));
	                $update_user = $this->db->update('state_master',$edit_data);
	                if(!$update_user){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_state':
				$this->db->trans_begin();
				try{
					$this->db->where('state_code',$this->security->xss_clean($data['state_code']));
	  				$delete_state = $this->db->delete('state_master');
					if(!$delete_state){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
            case 'district_master':
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
                $header = array('a.district_name','b.state_name','c.country_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }
                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                
                $this->db->from('district_master a');
                $this->db->select('a.dist_census_code,a.district_name,b.state_name,c.country_name,a.record_status,a.country_code,a.state_code,a.district_code');
                $this->db->join('state_master b','a.state_code = b.state_code','left');
                $this->db->join('country_master c','a.country_code = c.country_code','left');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('a.updated_on','DESC');	
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                
                $this->db->from('district_master a');
                $this->db->select('a.dist_census_code,a.district_name,b.state_name,c.country_name,a.record_status,a.country_code,a.state_code,a.district_code');
                $this->db->join('state_master b','a.state_code = b.state_code','left');
                $this->db->join('country_master c','a.country_code = c.country_code','left');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('a.updated_on','DESC');
				
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
            case 'add_district_master':
             	$this->db->trans_begin();
				try{
	                $data = array( 
	                    "district_code" => $this->security->xss_clean($this->input->post('district_code')),
	                    "dist_census_code" => $this->security->xss_clean($this->input->post('txtDistCensusCode')),
	                    "district_name" => $this->security->xss_clean($this->input->post('district_name')),
	                    "state_code" => $this->security->xss_clean($this->input->post('cmb_state')),
	                    "country_code" => $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" => $this->security->xss_clean($this->input->post('district_status')),
	                    'created_by'=> $this->user_name,
	                    'created_on'=>$date,
	                    'updated_by'=> $this->user_name,
	                    'updated_on'=>$date
	                );
	                $insert_district = $this->db->insert('district_master',$data);
	                if(!$insert_district){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_district_master':
            	$this->db->trans_begin();
				try{
	                $edit_data = array(
	                    "district_name" => $this->security->xss_clean($this->input->post('district_name')),
	                    "state_code" => $this->security->xss_clean($this->input->post('cmb_state')),
	                    "country_code" => $this->security->xss_clean($this->input->post('cmb_country')),
	                    "record_status" => $this->security->xss_clean($this->input->post('district_status')),
	                    'updated_by'=> $this->user_name,
	                    'updated_on'=>$date
	                );
	                $this->db->where('district_code',$this->security->xss_clean($this->input->post('district_code')));
	                $update_district = $this->db->update('district_master',$edit_data);
	                if(!$update_district){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_district':
				$this->db->trans_begin();
				try{
					$this->db->where('district_code',$this->security->xss_clean($this->input->post('district_code')));
	  				$district_state = $this->db->delete('district_master');
					if(!$district_state){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			
			case 'add_village_master':
             	$this->db->trans_begin();
				try{
	                $data = array( 
	                    "pk_village_code" => $this->security->xss_clean($this->input->post('txtCensusCode')),
	                    "village_name" => $this->security->xss_clean($this->input->post('txtVillageName')),
	                    "gp_code" => $this->security->xss_clean($this->input->post('cmb_gp')),
	                    "status" => $this->security->xss_clean($this->input->post('village_status')),
	                    'created_by'=> $this->user_name,
	                    'created_on'=>$date,
	                    'updated_by'=> $this->user_name,
	                    'updated_on'=>$date
	                );
	                $insert_village = $this->db->insert('village_master',$data);
	                if(!$insert_village){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'edit_village_master':
            	$this->db->trans_begin();
				try{
	                $edit_data = array(
	                    "village_name" => $this->security->xss_clean($this->input->post('txtVillageName')),
	                    "gp_code" => $this->security->xss_clean($this->input->post('cmb_gp')),
	                    "status" => $this->security->xss_clean($this->input->post('village_status')),
	                    'updated_by'=> $this->user_name,
	                    'updated_on'=>$date
	                );
	                $this->db->where('pk_village_code',$this->security->xss_clean($this->input->post('txtCensusCode')));
	                $update_village = $this->db->update('village_master',$edit_data);
	                if(!$update_village){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'delete_village':
				$this->db->trans_begin();
				try{
					$this->db->where('pk_village_code',$this->security->xss_clean($this->input->post('village_code')));
	  				$district_state = $this->db->delete('village_master');
					if(!$district_state){
						$this->db->trans_rollback();
						$dbstatus = FALSE;
						$dbmessage = 'Error While Delete';
					}else{
						$this->db->trans_commit();
						$dbstatus = TRUE;
	        			$dbmessage = 'delete successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			/**
			* Name :Subhashree Jena
			* Date :17-09-2019
			* Purpose: Operation For IMEI Setup 
			* @var 
			* 
			*/
			case 'get_imei_setup':
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
                $header = array('um.user_display_name','rm.role_name');//search filter will work on this column
                $filter_string="";
                if ($search['value'] != '') {
                    for ($i = 0; $i < count($header); $i++) {
                       $filter_string.=' or '.$header[$i]." LIKE '%".$search['value']."%'";
                    }
                    $filter_string=substr($filter_string,3);
                    $filter_string='('.$filter_string.')';
                }
                $iDisplayLength = $this->input->post('length');//to shw number of record to be shown
                $iDisplayStart = $this->input->post('start');//to start from that position (ex: offset)

                $this->db->limit($iDisplayLength, $iDisplayStart);
                
                $this->db->from('imei_setup im');
                $this->db->select('um.user_display_name,rm.role_name,im.imei1,im.imei2,im.rec_status, im.user_id,um.user_code,rm.role_code');
                $this->db->join('user_master um','um.user_code = im.user_id');
                $this->db->join('role_master rm','rm.role_code = um.primary_role');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('im.updated_on','DESC');	
                $res = $this->db->get();
                $query = $res->result_array();
                $output = array("aaData" => array());
                /*----FOR PAGINATION-----*/
                
                $this->db->from('imei_setup im');
                $this->db->select('um.user_display_name,rm.role_name,im.imei1,im.imei2,im.rec_status, im.user_id,um.user_code,rm.role_code');
                $this->db->join('user_master um','um.user_code = im.user_id');
                $this->db->join('role_master rm','rm.role_code = um.primary_role');
				if($filter_string!=''){
					$this->db->where($filter_string);
				}
				$this->db->order_by('im.updated_on','DESC');	
                $res1 = $this->db->get();
                
                $output["draw"] = intval($this->input->post('draw'));
                $output['iTotalRecords'] = $res1->num_rows();
                $output['iTotalDisplayRecords'] = $res1->num_rows();
                $slno = $iDisplayStart+1;
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
			case 'ADD_IMEI_SETUP':
            	$this->db->trans_begin();
				try{
					$data = array(
	                	"user_id" => $this->security->xss_clean($this->input->post('cmb_username')),
	                    "imei1" => $this->security->xss_clean($this->input->post('txt_imeiOne')),
	                    "imei2" => $this->security->xss_clean($this->input->post('txt_imeiTwo')),
	                    "rec_status" => 1,
	                    "created_by" => $this->user_name,
	                    "created_on" => $date,
	                    "updated_by" => $this->user_name,
	                    "updated_on" => $date
	                );
	                $insert_country = $this->db->insert('imei_setup',$data);
	                if( ! $insert_country){
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
	                }else{
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data saved successfully';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;
            case 'UPDATE_IMEI_SETUP':
            	$this->db->trans_begin();
				try{
					$user_id = $this->security->xss_clean($this->input->post('hidden_userid'));
	               	$data = array(
	                    "imei1" => $this->security->xss_clean($this->input->post('txt_imeiOne')),
	                    "imei2" => $this->security->xss_clean($this->input->post('txt_imeiTwo')),
	                    "rec_status" => $this->security->xss_clean($this->input->post('cmb_recStatus')),
	                    "created_by" => $this->user_name,
	                    "created_on" => $date,
	                    "updated_by" => $this->user_name,
	                    "updated_on" => $date
	                );
	                $this->db->where('user_id',$user_id);
	                $update_imei_setup = $this->db->update('imei_setup',$data);
	                if($update_imei_setup){
	                	$this->db->trans_commit();
						$dbstatus = TRUE;
	                	$dbmessage = 'Data update successfully';
	                }else{
	                	$this->db->trans_rollback();
	                    $dbstatus = FALSE;
	                    $dbmessage = 'Error While Saving';
					}
				}catch(Exception $e){
			      	$this->db->trans_rollback();
			      	$dbstatus = FALSE;
			      	$dbmessage = $e->getMessage();
			    }
                return array('status'=>$dbstatus,'msg'=>$dbmessage);
            break;

            case 'ADD_USER_ROLE_ASSIGN':

				// User role assign details
 				$this->form_validation->set_rules('urg_user_code', 'User code', 'required|xss_clean');
 				$this->form_validation->set_rules('urg_role_group', 'Role group code', 'required|xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$user_code = $this->security->xss_clean($this->input->post('urg_user_code'));
	 					$role_group_code = $this->security->xss_clean($this->input->post('urg_role_group'));

	 					// Get the role code based on role group code
	 					$this->db->select('role_code');
						$this->db->where('role_group_code', $role_group_code);
						$get_role = $this->db->get('role_group_mapping');
						$res_role = $get_role->row_array();
						
						if($res_role){
							// Update the primary role of user
							$update_data =array('primary_role'=>$res_role['role_code']);
				     		$this->db->where('user_code',$user_code);
				     		$upd_res = $this->db->update('user_master',$update_data);

				     		if($upd_res){
				     			// Get max id from user role group map table
			 					$qry = $this->db->query("SELECT MAX(id)+1 AS max_id FROM user_role_group_map");
								$result = $qry->row_array();

								if($result){
									$data = array(
							              'user_rolegroup_code' 		=> 'urg'.$result['max_id'].rand(100,9999),
							              'user_code' 					=>  $user_code,
							              'role_group_code' 			=>  $role_group_code,
							           	  'created_by'					=>	$this->user_name, 
										  'created_on'					=>	$date,
										  'record_status'				=>	1
							        );

							        // Insert user role assign details
				 					$result2 = $this->db->insert('user_role_group_map', $data);

				 					if($result2){
				 						$this->db->trans_commit();
				 						$dbstatus = true;
				 						$dbmessage = "Record saved successfully";
				 					}
				 					else{
				 						$this->db->trans_rollback();
				 						$dbstatus = false;
				 						$dbmessage = "Something went wrong. Please try again.1";
				 					}
								}
								else{
									$this->db->trans_rollback();
			 						$dbstatus = false;
			 						$dbmessage = "Something went wrong. Please try again.2";
								}
				     		}
				     		else{
				     			$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Server is not responding. Please try again.3";
				     		}
						}
						else{
							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.4";
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


			case 'EDIT_USER_ROLE_ASSIGN':

				// User role assign details
			$this->form_validation->set_rules('urg_hidden_id', 'Hidden id', 'required|xss_clean');
 				$this->form_validation->set_rules('urg_user_code', 'User code', 'required|xss_clean');
 				$this->form_validation->set_rules('urg_role_group', 'Role group code', 'required|xss_clean');
 				
 				if($this->form_validation->run()){
 					$this->db->trans_begin();
	 				try{

	 					$hidden_id = $this->security->xss_clean($this->input->post('urg_hidden_id'));
	 					$user_code = $this->security->xss_clean($this->input->post('urg_user_code'));
	 					$role_group_code = $this->security->xss_clean($this->input->post('urg_role_group'));

	 					// Get the role code based on role group code
	 					$this->db->select('role_code');
						$this->db->where('role_group_code', $role_group_code);
						$get_role = $this->db->get('role_group_mapping');
						$res_role = $get_role->row_array();
						
						if($res_role){
							// Update the primary role of user
							$update_data =array('primary_role'=>$res_role['role_code']);
				     		$this->db->where('user_code',$user_code);
				     		$upd_res = $this->db->update('user_master',$update_data);

				     		if($upd_res){
								$data = array(
						              'user_code' 					=>  $user_code,
						              'role_group_code' 			=>  $role_group_code,
						           	  'updated_by'					=>	$this->user_name, 
									  'updated_on'					=>	$date,
									  'record_status'				=>	1
						        );

						        // Update user role assign details
			 					$result2 = $this->db->where('id', $hidden_id)->update('user_role_group_map', $data);

			 					if($result2){
			 						$this->db->trans_commit();
			 						$dbstatus = true;
			 						$dbmessage = "Record saved successfully";
			 					}
			 					else{
			 						$this->db->trans_rollback();
			 						$dbstatus = false;
			 						$dbmessage = "Something went wrong. Please try again.1";
			 					}
								
				     		}
				     		else{
				     			$this->db->trans_rollback();
		 						$dbstatus = false;
		 						$dbmessage = "Server is not responding. Please try again.3";
				     		}
						}
						else{
							$this->db->trans_rollback();
	 						$dbstatus = false;
	 						$dbmessage = "Something went wrong. Please try again.4";
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

            default :
            	return array('status' => FALSE, 'msg' =>'Unable to load.Contact Support');
        }
    }

	public function importData($op){
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s', time());
		switch ($op) {
			case 'COUNTRY_DATA':
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('country_master');
						$this->db->select('country_code,country_name');
				        $this->db->where('country_code',$data[$i]['country_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('country_master');
							$this->db->select('country_code,country_name');
					        $this->db->where('country_code',$data[$i]['country_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
									"country_name" => $data[$i]['country_name'],
									"updated_by" 	=> 	$this->user_name,
									"updated_on" 	=> 	$date
							);
							$this->db->where('country_code',$row2['country_code']);
							$update_country_data = $this->db->update('country_master',$update_data);
					 		if($update_country_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array( "country_code"=>$data[$i]['country_code'],
										'country_name' =>$data[$i]['country_name'],
										'created_by'=> $this->user_name, 
										'created_on'=>$date,
										'record_status'=>1
									);
							$insert_country = $this->db->insert('country_master',$insert_data);
					    	if($insert_country){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'STATE_DATA':
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('state_master');
						$this->db->select('state_code,state_name,country_code');
				        $this->db->where('state_code',$data[$i]['state_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('state_master');
							$this->db->select('state_code,state_name,country_code');
					        $this->db->where('state_code',$data[$i]['state_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
									"state_name" 	=> $data[$i]['state_name'],
									"country_code" 	=> $data[$i]['country_code'],
									"updated_by" 	=> 	$this->user_name,
									"updated_on" 	=> 	$date
							);
							$this->db->where('state_code',$row2['state_code']);
							$update_state_data = $this->db->update('state_master',$update_data);
					 		if($update_state_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array( "state_code"=>$data[$i]['state_code'],
										'state_name' =>$data[$i]['state_name'],
										'country_code' =>$data[$i]['country_code'],
										'created_by'=> $this->user_name, 
										'created_on'=>$date,
										'record_status'=>1
									);
							$insert_country = $this->db->insert('state_master',$insert_data);
					    	if($insert_country){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'DISTRICT_DATA':
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('district_master');
						$this->db->select('district_code,district_name,state_code,country_code,dist_census_code');
				        $this->db->where('district_code',$data[$i]['district_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('district_master');
							$this->db->select('district_code,district_name,state_code,country_code,dist_census_code');
					        $this->db->where('district_code',$data[$i]['district_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
									"district_name" => $data[$i]['district_name'],
									"state_code" 	=> $data[$i]['state_code'],
									"country_code" 	=> $data[$i]['country_code'],
									"dist_census_code" 	=> $data[$i]['dist_census_code'],
									"updated_by" 	=> 	$this->user_name,
									"updated_on" 	=> 	$date
							);
							$this->db->where('district_code',$row2['district_code']);
							$update_district_data = $this->db->update('district_master',$update_data);
					 		if($update_district_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array( "district_code"=>$data[$i]['district_code'],
										"district_name" => $data[$i]['district_name'],
										"state_code" 	=> $data[$i]['state_code'],
										"country_code" 	=> $data[$i]['country_code'],
										"dist_census_code" 	=> $data[$i]['dist_census_code'],
										'created_by'	=> $this->user_name, 
										'created_on'	=>$date,
										'record_status	'=>1
									);
							$insert_district = $this->db->insert('district_master',$insert_data);
					    	if($insert_district){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'BLOCK_DATA':
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('block_master');
						$this->db->select('block_code,block_name,fk_district_code,fk_state_code,fk_country_code');
				        $this->db->where('block_code',$data[$i]['block_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('block_master');
							$this->db->select('block_code,block_name,fk_district_code,fk_state_code,fk_country_code');
					       	$this->db->where('block_code',$data[$i]['block_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
								"block_name" => $data[$i]['block_name'],
								"fk_district_code" 	=> $data[$i]['district_code'],
								"fk_state_code" 	=> $data[$i]['state_code'],
								"fk_country_code" 	=> $data[$i]['country_code'],
								"updated_by" 	=> 	$this->user_name,
								"updated_on" 	=> 	$date
							);
							$this->db->where('block_code',$row2['block_code']);
							$update_block_data = $this->db->update('block_master',$update_data);
					 		if($update_block_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array( "block_code"=>$data[$i]['block_code'],
										"block_name" => $data[$i]['block_name'],
										"fk_district_code" 	=> $data[$i]['district_code'],
										"fk_state_code" 	=> $data[$i]['state_code'],
										"fk_country_code" 	=> $data[$i]['country_code'],
										'created_by'	=> $this->user_name, 
										'created_on'	=>$date,
										'record_status	'=>1
									);
							$insert_block = $this->db->insert('block_master',$insert_data);
					    	if($insert_block){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'GP_DATA':
				ini_set('max_execution_time', 1000000);
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('gp_master');
						$this->db->select('pk_gp_code,gp_name,fk_block_code,fk_district_census_code,fk_district_code,fk_state_code,fk_country_code');
				        $this->db->where('pk_gp_code',$data[$i]['gp_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('gp_master');
							$this->db->select('pk_gp_code,gp_name,fk_block_code,fk_district_census_code,fk_district_code,fk_state_code,fk_country_code');
					       	$this->db->where('pk_gp_code',$data[$i]['gp_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
								"gp_name" 		=> $data[$i]['gp_name'],
								"fk_block_code" 	=> $data[$i]['block_code'],
								"fk_district_census_code" 	=> $data[$i]['district_census_code'],
								"fk_district_code" 	=> $data[$i]['district_code'],
								"fk_state_code" 	=> $data[$i]['state_code'],
								"fk_country_code" 	=> $data[$i]['country_code'],
								"updated_by" 		=> 	$this->user_name,
								"updated_on" 		=> 	$date
							);
							$this->db->where('pk_gp_code',$row2['pk_gp_code']);
							$update_gp_data = $this->db->update('gp_master',$update_data);
					 		if($update_gp_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array( "pk_gp_code"=>$data[$i]['gp_code'],
								"gp_name" 		=> $data[$i]['gp_name'],
								"fk_block_code" 	=> $data[$i]['block_code'],
								"fk_district_census_code" 	=> $data[$i]['district_census_code'],
								"fk_district_code" 	=> $data[$i]['district_code'],
								"fk_state_code" 	=> $data[$i]['state_code'],
								"fk_country_code" 	=> $data[$i]['country_code'],
								'created_by'	=> $this->user_name, 
								'created_on'	=>$date,
								'status	'=>1
							);
							$insert_gp = $this->db->insert('gp_master',$insert_data);
					    	if($insert_gp){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;
			case 'VILLAGE_DATA':
				ini_set('max_execution_time', 1000000);
				$dbstatus = TRUE;
		        $dbmessage = '';
		        
		        $data = $this->_batchImport;
			    for($i=0;$i<sizeof($data);$i++){
			    	$this->db->trans_begin();
					try{
				    	$this->db->from('village_master');
						$this->db->select('pk_village_code,village_name,gp_code');
				        $this->db->where('pk_village_code',$data[$i]['pk_village_code']);
				    	$res = $this->db->get();
						$query = $res->result_array();
						if($res->num_rows()>0){
							$this->db->from('village_master');
							$this->db->select('pk_village_code,village_name,gp_code');
					        $this->db->where('pk_village_code',$data[$i]['pk_village_code']);
					    	$res = $this->db->get();
							$query = $res->result_array();
							$row2 = array_shift($query);
					    	
					    	$update_data = array(
								"village_name" 		=> $data[$i]['village_name'],
								"gp_code" 			=> $data[$i]['gp_code'],
								"updated_by" 		=> 	$this->user_name,
								"updated_on" 		=> 	$date
							);
							$this->db->where('pk_village_code',$row2['pk_village_code']);
							$update_gp_data = $this->db->update('village_master',$update_data);
					 		if($update_gp_data){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Updated Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}else{
							$insert_data = array(
								"pk_village_code"	=> $data[$i]['pk_village_code'],
								"village_name" 		=> $data[$i]['village_name'],
								"gp_code" 			=> $data[$i]['gp_code'],
								'created_by'		=> $this->user_name, 
								'created_on'		=> $date,
								'status	'			=> 1
							);
							$insert_gp = $this->db->insert('village_master',$insert_data);
					    	if($insert_gp){
								$this->db->trans_commit();
								$dbstatus = TRUE;
								$dbmessage = 'Data Upload Sucessfully';
							}else{
								$this->db->trans_rollback();
								$dbstatus = FALSE;
								$dbmessage = 'Error While Saving';
							}
						}
					}catch(Exception $e){
				      	$this->db->trans_rollback();
				      	$dbstatus = FALSE;
				      	$dbmessage = $e->getMessage();
			    	}
				}
   				return array('status'=>$dbstatus,'msg'=>$dbmessage);
			break;

			case 'GET_USER_ROLE_GROUP_MAP':

				// Check if the user is superadmin only then show the superadmin data
				$user_data = $this->db->get_where('user_master as um2', ['um2.user_name' => $this->user_name])->row();
				if($user_data){
					$user_primary_role = $user_data->primary_role;
				}
				
				// Get all the user role map data
				$select_column = array("user_rolegroup_code", "user_code", "role_group_code");
				$order_column = array("user_rolegroup_code", "user_code", "role_group_code");

				$this->db->select("urg.id, urg.user_rolegroup_code, urg.user_code, urg.role_group_code, um.user_display_name as user_display_name, um.user_name, rgm.name as user_role_group_name, um.primary_role as um_primary_role");
				$this->db->from('user_role_group_map AS urg');
				$this->db->join('user_master as um', 'urg.user_code = um.user_code');
				$this->db->join('role_group_mapping as rgm', 'urg.role_group_code = rgm.role_group_code');

				if(isset($_POST["search"]["value"])){
					$count = 1;
					$like_clause = "(";
					foreach ($select_column as $sc) {
						if($count == 1){
							$like_clause .= 'urg.'.$sc. " LIKE '%".$_POST['search']['value']."%'";
							// $this->db->like($sc, $_POST['search']['value']);	
						}
						else{
							$like_clause .= " OR ". 'urg.'.$sc. " LIKE '%".$_POST['search']['value']."%'";
							// $this->db->or_like($sc, $_POST['search']['value']);
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
					$this->db->order_by('urg.id', 'DESC');
				}

				if($_POST['length'] != -1){
					$this->db->limit($_POST['length'], $_POST['start']);
				}

				if($user_primary_role != 'SUPERADMIN'){
					$this->db->where('um.primary_role !=', 'SUPERADMIN');
				}

				$this->db->where('urg.record_status', 1);
				$query = $this->db->get();
				// print_r($this->db->last_query()); 
				$fetch_data = $query->result();

		    	// Filter records
		 		$this->db->where('um.record_status', 1)->select("*");
		 		if($user_primary_role != 'SUPERADMIN'){
					$this->db->where('um.primary_role !=', 'SUPERADMIN');
				}
				$this->db->from('user_role_group_map AS urg');
				$this->db->join('user_master as um', 'urg.user_code = um.user_code');
				$this->db->join('role_group_mapping as rgm', 'urg.role_group_code = rgm.role_group_code');
		 		$recordsFiltered = $this->db->count_all_results();

		 		// Records total
		 		$this->db->where('um.record_status', 1)->select("*");
		 		if($user_primary_role != 'SUPERADMIN'){
					$this->db->where('um.primary_role !=', 'SUPERADMIN');
				}
				$this->db->from('user_role_group_map AS urg');
				$this->db->join('user_master as um', 'urg.user_code = um.user_code');
				$this->db->join('role_group_mapping as rgm', 'urg.role_group_code = rgm.role_group_code');
		 		$recordsTotal = $this->db->count_all_results();

		 		// Output
		    	$output = array(
		    		"draw" => intval($_POST['draw']),
		    		"recordsTotal" => $recordsTotal,
		    		"recordsFiltered" => $recordsFiltered,
		    		"data" => $fetch_data
		    	);
		    	return $output;

			break;

			case 'GET_ALL_USERS':
				$users = $this->db->select('user_code, user_name ,user_display_name, job_title, primary_role')
						->from('user_master')
						->order_by('user_display_name', 'ASC')
						->get()
						->result_array();

				return $users;
			break;

			case 'GET_ALL_ROLE_GROUP':
				$users = $this->db->select('role_name, role_code')
						->from('role_master')
						->order_by('role_name', 'ASC')
						->get()
						->result_array();

				return $users;
			break;

			default :
            	return array('status' => FALSE, 'msg' =>'Unable to load.Contact Support');
		}
	}
}
