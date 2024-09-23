<?php

function getTodaysReminders()
{
    // Get a reference to the controller object
    $CI = get_instance();
    $CI->load->model('notification_model');

    // Call a function of the model
    return $CI->notification_model->get('', 'GET_TODAYS_USER_REMINDERS');
}

function generateDiaryNumberPrefix()
{
    return 'DIAC';
}

function generateDiaryNumberDigits($rowCount)
{
    if ($rowCount && $rowCount > 0) {
        $number =  $rowCount + 1;
    } else {
        $number =  10000 + 1;
    }
    return $number;
}

function generateMaxCountNumber($rowCount)
{
    if ($rowCount && $rowCount > 0) {
        $number =  $rowCount + 1;
    } else {
        $number =  1000 + 1;
    }
    return $number;
}

function generateDiaryNumber($rowCount, $initials = 'NR')
{
    $number =  1000 + $rowCount + 1;
    return 'DNO/' . date('m-y') . '/DIAC/' . $initials . $number;
}

function randomPassword($user_name)
{
    $alphabet = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    // $text_password = implode($pass);
    $text_password = 'password';
    //$db = db_connect();
    $ci = &get_instance();
    $res_pass = $ci->db->query("SELECT SHA2(CONCAT('" . $user_name . "', '#', '" . $text_password . "'), 512)  as secreatepassword")->row_array();
    return array(
        'password' => $text_password,
        'secreatepassword' => $res_pass['secreatepassword']
    ); //turn the array into a string
    // encode(digest('".$user_name."'||'#'||'".implode($pass)."', 'sha512'),'hex')
}

function generateUserCode()
{
    return rand(99, 999) . time();
}

function generateCode()
{
    return rand(99, 999) . time() . rand(99, 999);
}

function generate_txn_id()
{
    return 'TXN' . rand(99, 999) . time() . rand(99, 999);
}

function otp_expiration_time()
{
    return date("Y-m-d H:i:s", strtotime('+2 hours'));
}

function generate_diac_reg_number_prefix()
{
    return 'DIAC';
}

function generate_diac_int_reg_number_prefix()
{
    return 'DIAC/I';
}

function generate_diac_reg_number()
{
    $CI = &get_instance();
    $case_count = 0;

    $CI->db->select_max('case_no');
    $query = $CI->db->get('cs_case_details_tbl');
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
        $case_count = $result['case_no'];

        if ($case_count) {
            // $case_count = $case_count;
            $case_count = $case_count + 1;

            if (strlen($case_count) == 1) {
                $case_count = '000' . $case_count;
            }
            if (strlen($case_count) == 2) {
                $case_count = '00' . $case_count;
            }
            if (strlen($case_count) == 3) {
                $case_count = '0' . $case_count;
            }
        } else {
            $case_count = 8377 + 1;
            // $case_count = 0;
        }
    }

    $case_number = $case_count;

    $diac_reg_number = $case_number;
    return $diac_reg_number;
}

function automatic_case_allotment($case_no, $role)
{
    $CI = &get_instance();

    if ($role == 'DEPUTY_COUNSEL') {
        $type = 'DC_CASE_REF_NO';
    }
    if ($role == 'COORDINATOR') {
        $type = 'COORDINATOR_CASE_REF_NO';
    }
    if ($role == 'CASE_MANAGER') {
        $type = 'CM_CASE_REF_NO';
    }

    $case_ref_no_data = $CI->db->query("SELECT b.user_code, b.user_name, b.email, b.phone_number,b.user_display_name 
                            FROM master_case_ref_no_tbl a 
                            LEFT JOIN user_master b ON a.code=b.`case_ref_no`
                            WHERE `type`='" . $type . "' 
                            AND start_index<= CAST(SUBSTRING('" . $case_no . "',-2) AS UNSIGNED) AND end_index>=CAST(SUBSTRING('" . $case_no . "',-2) AS UNSIGNED)")
        ->row_array();

    return $case_ref_no_data;
}


function generate_internship_ref_no($total_count)
{
    $new_count = $total_count + 1;
    $reference_no = 'REF/' . date('Y') . '/' . date('m') . '/' . $new_count;
    return $reference_no;
}

function get_full_case_number($case)
{
    return $case['case_no_prefix'] . '/' . $case['case_no'] . '/' . $case['case_no_year'];
}
