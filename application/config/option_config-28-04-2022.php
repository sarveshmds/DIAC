<?php
	$config['is_notify'] 				= array(1=>'TRUE', 0=>'FALSE');
	$config['smtp_auth'] 				= array('TRUE'=>'TRUE', 'FALSE'=>'FALSE');
	$config['status'] 					= array(1=>'Active', 0=>'Inactive');
	$config['is_maintenance'] 			= array(1=>'YES', 0=>'NO');
	$config['participant_designation'] 	= array('STUD'=>'Student', 'NON_STUD'=>'Non Student');
	$config['food_status'] 				= array('VEG'=>'Veg', 'NON_VEG'=>'Non Veg');
	$config['track_size'] 				= array('S'=>'S','M'=>'M','L'=>'L','XL'=>'XL');
	
	$config['case_parties']				= array('claimant' => 'Claimant', 'respondant' => 'Respondant');
	$config['case_category']				= array('domestic' => 'Domestic', 'international' => 'International');
	
	$config['is_selected'] 				= array('yes' => 'Yes', 'no' => 'No');

	// For notification only for DIAC
	$config['notification_check_roles'] = array('CASE_MANAGER', 'CASE_FILER', 'ACCOUNTS', 'COORDINATOR', 'DEPUTY_COUNSEL');

	$config['jwt_key'] = "some435$^7657random&^@string";

	// All Roles in application
	$config['roles'] = ['ADMIN', 'DIAC', 'CASE_MANAGER', 'CASE_FILER', 'COORDINATOR', 'ACCOUNTS', 'CAUSE_LIST_MANAGER', 'POA_MANAGER'];	

	$config['check_bounce'] 				= array('yes' => 'Yes', 'no' => 'No');
?>