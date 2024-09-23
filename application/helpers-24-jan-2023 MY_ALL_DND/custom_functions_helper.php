<?php

function getTodaysReminders()
{
    // Get a reference to the controller object
    $CI = get_instance();
    $CI->load->model('notification_model');

    // Call a function of the model
    return $CI->notification_model->get('', 'GET_TODAYS_USER_REMINDERS');
}

function formatDate($date)
{
    if ($date) {
        return date('Y-m-d', strtotime($date));
    }
    return null;
}

function formatDatepickerDate($date)
{
    if ($date) {
        return date('d-m-Y', strtotime($date));
    }
    return '';
}

function formatReadableDate($date)
{
    if ($date) {
        return date('d-M, Y', strtotime($date));
    }
    return '';
}

function formatReadableNumberDate($date)
{
    return date('d-m-Y', strtotime($date));
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
    $text_password = implode($pass);
    //$db = db_connect();
    $ci = &get_instance();
    $res_pass = $ci->db->query("SELECT SHA2(CONCAT('" . $user_name . "', '#', '" . $text_password . "'), 512)  as secreatepassword")->row_array();
    return array(
        'password' => $text_password,
        'secreatepassword' => $res_pass['secreatepassword']
    ); //turn the array into a string
    // encode(digest('".$user_name."'||'#'||'".implode($pass)."', 'sha512'),'hex')
}

function currentDateTimeStamp()
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d H:i:s');
}


function currentDate()
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d');
}

function generateCode()
{
    return rand(99, 999) . time() . rand(99, 999);
}

function generate_txn_id()
{
    return 'TXN' . rand(99, 999) . time() . rand(99, 999);
}

function money_round_off($amount)
{
    return ceil($amount);
}

function get_current_dollar_to_rupee()
{
    $req_url = 'https://api.exchangerate.host/convert?from=USD&to=INR';
    $response_json = file_get_contents($req_url);
    if (false !== $response_json) {
        try {
            $response = json_decode($response_json, true);
            if ($response['success'] === true) {
                return $response['result'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
            // throw new Error('Service is not available. Please contact support team');
            // die;
        }
    }
}

function dollar_to_rupee($amount)
{
    $amount = $amount;
    $current_dollar_in_rupee = get_current_dollar_to_rupee();
    if ($current_dollar_in_rupee) {
        return money_round_off($amount * $current_dollar_in_rupee);
    }
    throw new Error('DOLLAR TO RUPEE: Service is not available. Please contact support team');
    die;
}

function rupee_to_dollar($amount)
{
    $amount = $amount;
    $current_dollar_in_rupee = get_current_dollar_to_rupee();
    if ($current_dollar_in_rupee) {
        return money_round_off($amount / $current_dollar_in_rupee);
    }
    throw new Error('RUPEE TO DOLLAR: Service is not available. Please contact support team');
    die;
}

function rupee_to_dollar_using_existing_dollar($amount, $dollar_price)
{
    return money_round_off($amount / $dollar_price);
}

function current_dollar_price()
{
    return round(get_current_dollar_to_rupee(), 2);
}

function current_dollar_price_text()
{
    $price = get_current_dollar_to_rupee();
    if ($price) {
        return '1$ = ' . round($price, 2) . ' Rs.';
    }
    return 'Not Found';
}


function INDMoneyFormat($amount)
{
    $fmt = new NumberFormatter($locale = 'en_IN', NumberFormatter::CURRENCY);
    return $fmt->format($amount);
}


function dollarMoneyFormat($amount)
{
    $fmt = new NumberFormatter($locale = 'en_US', NumberFormatter::CURRENCY);
    return $fmt->format($amount);
}

function calculateBalance($amount1 = 0, $amount2 = 0)
{
    $amount1 = ($amount1) ? $amount1 : 0;
    $amount2 = ($amount2) ? $amount2 : 0;

    if ($amount1 >= $amount2) {
        return $amount1 - $amount2;
    }
    return 0;
}

function calculateExcess($amount1 = 0, $amount2 = 0)
{
    $amount1 = ($amount1) ? $amount1 : 0;
    $amount2 = ($amount2) ? $amount2 : 0;

    if ($amount1 < $amount2) {
        return $amount1 - $amount2;
    }
    return 0;
}

function symbol_checker($type_of_arbitration, $amount)
{
    if ($type_of_arbitration == 'DOMESTIC') {
        return INDMoneyFormat($amount);
    }
    if ($type_of_arbitration == 'INTERNATIONAL') {
        return dollarMoneyFormat($amount);
    }
    return $amount;
}

function dollar_converstion_if_international($type_of_arbitration, $amount, $current_dollar_price = '')
{
    if ($type_of_arbitration == 'INTERNATIONAL') {
        return dollarMoneyFormat(rupee_to_dollar_using_existing_dollar($amount, $current_dollar_price));
    }
    return '';
}

function edit_selected($array, $array_value, $option_value)
{
    if (!$array) {
        return '';
    }
    if ($array_value == $option_value) {
        return 'selected';
    }
    return '';
}

function edit_value($array, $index)
{
    if (!$array) {
        return '';
    }
    if (isset($array[$index])) {
        return $array[$index];
    }
    return '';
}

function otp_expiration_time()
{
    return date("Y-m-d H:i:s", strtotime('+2 hours'));
}

function generate_diac_reg_number()
{
    $CI = &get_instance();
    $CI->db->select('*');
    $case_count = $CI->db->from('cs_case_details_tbl')->count_all_results();

    $case_count = $case_count + 1;

    if (strlen($case_count) == 1) {
        $case_number = '000' . $case_count;
    }
    if (strlen($case_count) == 2) {
        $case_number = '00' . $case_count;
    }
    if (strlen($case_count) == 3) {
        $case_number = '0' . $case_count;
    }

    $diac_reg_number = 'DIAC/' . date('Y-m') . '/' . $case_number;
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

    $case_ref_no_data = $CI->db->query("SELECT b.`user_code`,`user_name` 
                            FROM master_case_ref_no_tbl a 
                            LEFT JOIN user_master b ON a.code=b.`case_ref_no`
                            WHERE `type`='" . $type . "' 
                            AND start_index<= CAST(SUBSTRING('" . $case_no . "',-2) AS UNSIGNED) AND end_index>=CAST(SUBSTRING('" . $case_no . "',-2) AS UNSIGNED)")
        ->row_array();

    return $case_ref_no_data;
}
