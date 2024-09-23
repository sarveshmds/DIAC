<?php

/*
	* Name: Manoj Kumar Yadav
	* Date : 24-March-2020
	*/
function response($status, $status_code, $data = '', $message)
{
	$output = array(
		'status' => $status,
		'status_code' => $status_code,
		'data' => $data,
		'message' => $message
	);

	echo json_encode($output);
	exit();
}

/*
	* Function to generate JWT token
	*/
function generate_token($data)
{
	return AUTHORIZATION::generateToken($data);
}

/*
	* Function to verify the JWT token
	*/
function verify_request($headers)
{

	// Use try-catch
	// JWT library throws exception if the token is not valid
	try {
		if (isset($headers['Authorization'])) {
			// Extract the token
			$token = $headers['Authorization'];

			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				return false;
			} else {
				return $data;
			}
		}
	} catch (Exception $e) {
		return false;
	}
}

/*
	* Function to generate random string
	*/
function generate_string()
{
	$str = rand(0, 999);
	$str = uniqid(md5($str));
	return $str;
}

/*
	* Function to decode the token
	*/
function decode_token($headers)
{
	$CI = &get_instance();
	// Get all the headers
	$headers = $CI->input->request_headers();

	// Extract the token
	$token = $headers['Authorization'];

	return JWT::decode($token, $CI->config->item('jwt_key'));
}

function check_post_request()
{
	$CI = &get_instance();
	// Check request method
	if ($CI->input->method(TRUE) != 'POST') {
		return false;
	}

	return true;
}


function check_get_request()
{
	$CI = &get_instance();

	// Check request method
	if ($CI->input->method(TRUE) != 'GET') {
		return false;
	}
	return true;
}

function success_message($message)
{
	$CI = &get_instance();
	return $CI->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'status' => true,
			'message' => $message
		)));
}

function failure_message($message)
{
	$CI = &get_instance();
	return $CI->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'status' => false,
			'message' => $message
		)));
}

function success_data($data)
{
	$CI = &get_instance();
	return $CI->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'status' => true,
			'data' => $data
		)));
}
