<?php

/**
 * Function to send the case registration notificaiton on whatsapp
 */
function wa_case_registration_info($phone_number, $full_case_no, $case_details, $deputy_counsel)
{
    $template_id = '1042455669';
    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $dc_name = $deputy_counsel['user_display_name'];
    $dc_email = $deputy_counsel['email'];
    $dc_phone_number = $deputy_counsel['phone_number'];

    $template_info = "$template_id~$full_case_no~$case_title~$dc_name~$dc_phone_number~$dc_email";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}

/**
 * Function to send the case scheduled hearing notificaiton on whatsapp
 */
function wa_case_hearing_notification($phone_number, $full_case_no, $case_details, $hearing_details)
{
    $template_id = '1042457419';

    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $date = ($hearing_details['date']) ? date('d-m-Y', strtotime($hearing_details['date'])) : 'NA';
    $time = $hearing_details['time_from'] . '-' . $hearing_details['time_to'];
    $mode = $hearing_details['mode_of_hearing'];

    $template_info = "$template_id~$full_case_no~$case_title~$date~$time~$mode";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}

/**
 * Function to send the case re scheduled hearing notificaiton on whatsapp
 */
function wa_case_hearing_reschedule_notification($phone_number, $full_case_no, $case_details, $hearing_details)
{
    $template_id = '1042562208';

    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $date = ($hearing_details['date']) ? date('d-m-Y', strtotime($hearing_details['date'])) : 'NA';
    $time = $hearing_details['time_from'] . '-' . $hearing_details['time_to'];
    $mode = $hearing_details['mode_of_hearing'];

    $template_info = "$template_id~$full_case_no~$case_title~$date~$time~$mode";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}



/**
 * Function to send the cancel hearing notificaiton on whatsapp
 */
function wa_cancel_hearing_notification($phone_number, $full_case_no, $case_details, $hearing_details)
{
    $template_id = '1042455665';
    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $date = ($hearing_details['date']) ? date('d-m-Y', strtotime($hearing_details['date'])) : 'NA';

    $template_info = "$template_id~$full_case_no~$case_title~$date";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}


function wa_arbitral_tribunal_notification($phone_number, $full_case_no, $case_details, $deputy_counsel, $case_arbitrators)
{

    $template_id = '1042562205';
    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $dc_name = $deputy_counsel['user_display_name'];
    $dc_email = $deputy_counsel['email'];
    $dc_phone_number = $deputy_counsel['phone_number'];

    $arbitrator_details = '';
    if (count($case_arbitrators) > 0) {
        foreach ($case_arbitrators as $key => $arbitrator) {
            $arbitrator_details .= ($key + 1) . '. (Name: ' . $arbitrator['mat_name_of_arbitrator'] . ', Contact Number: ' . $arbitrator['contact_no'] . ', Email Id:' . $arbitrator['email'] . ')';
        }
    }

    $template_info = "$template_id~$full_case_no~$case_title~$dc_name~$dc_phone_number~$dc_email~$arbitrator_details";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}

/**
 * Function to send the case scheduled hearing notificaiton on whatsapp
 */
function wa_case_hearing_reminder_notification($phone_number, $full_case_no, $case_details, $hearing_details)
{
    $template_id = '1042525620';

    $full_case_no = $full_case_no;

    $phone_number = '91' . $phone_number;
    $case_title = $case_details['case_title'];
    $date = ($hearing_details['date']) ? date('d-m-Y', strtotime($hearing_details['date'])) : 'NA';
    $time = $hearing_details['time_from'] . '-' . $hearing_details['time_to'];
    $mode = $hearing_details['mode_of_hearing'];

    $template_info = "$template_id~$full_case_no~$case_title~$date~$time~$mode";
    // echo $template_info;
    // die;

    // Define the API URL
    $url = 'https://api.myvfirst.com/psms/servlet/psms.JsonEservice';

    // Create the JSON data
    $data = array(
        "@VER" => "1.2",
        "USER" => array(
            "@CH_TYPE" => "4",
            "@UNIXTIMESTAMP" => ""
        ),
        "SMS" => array(
            array(
                "@UDH" => "0",
                "@CODING" => "",
                "@TEXT" => "",
                "@TEMPLATEINFO" => "$template_info",
                "@PROPERTY" => "0",
                "@MSGTYPE" => "1",
                "@ID" => "",
                "ADDRESS" => array(
                    array(
                        "@FROM" => "917669422001",
                        "@TO" => "$phone_number",
                        "@SEQ" => "",
                        "@TAG" => ""
                    )
                )
            )
        )
    );

    // Encode the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set the options for cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJkaWFjYXBpd2EiLCJleHAiOjMyOTYwNDQ1ODV9.QxHBGQ6ftYaUj6YVZTUW5mraUOCVyLs64THF371GWMc'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Check for cURL errors
    if ($response === false) {
        return [
            'status' => false,
            'template_id' => $template_id,
            'msg' => 'Error while sending whatsapp message'
        ];
    } else {
        // Return the response from the API
        return [
            'status' => true,
            'template_id' => $template_id,
            'msg' => 'Message sent successfully'
        ];
    }
}
