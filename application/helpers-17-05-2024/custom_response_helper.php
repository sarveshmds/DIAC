<?php
function success_response($message, $return = false, $commit = false)
{
    $message = ($message) ? $message : 'Something went wrong, please try again';

    // Check if the response wants to commit the query
    if ($commit == true) {
        $CI = &get_instance();
        $CI->db->trans_commit();
    }

    // Check if the function wants return
    if ($return == true) {
        return json_encode([
            'status' => true,
            'msg' => $message,
        ]);
    }

    // Echo the response
    // Note: Generally for json response for ajax
    echo json_encode([
        'status' => true,
        'msg' => $message,
    ]);
    die;
}

function success_response_with_data($message, $data, $return = false, $commit = false)
{
    $message = ($message) ? $message : 'Something went wrong, please try again';

    // Check if the response wants to commit the query
    if ($commit == true) {
        $CI = &get_instance();
        $CI->db->trans_commit();
    }

    // Check if the function wants return
    if ($return == true) {
        return json_encode([
            'status' => true,
            'msg' => $message,
            'data' => $data
        ]);
    }

    // Echo the response
    // Note: Generally for json response for ajax
    echo json_encode([
        'status' => true,
        'msg' => $message,
        'data' => $data
    ]);
    die;
}

function failure_response($message, $return = false, $rollback = false)
{
    $message = ($message) ? $message : COMMON_ERROR;

    // Check if the response wants to commit the query
    if ($rollback == true) {
        $CI = &get_instance();
        $CI->db->trans_rollback();
    }

    // Check if the function wants return
    if ($return == true) {
        return json_encode([
            'status' => false,
            'msg' => $message,
        ]);
    }

    // Echo the response
    // Note: Generally for json response for ajax
    echo json_encode([
        'status' => false,
        'msg' => $message,
    ]);
    die;
}
