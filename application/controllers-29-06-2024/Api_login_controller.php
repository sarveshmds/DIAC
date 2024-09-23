<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Credentials : false");
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH");
header("Access-Control-Max-Age: 328600");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Controller to handle all the api request
 */
class Api_login_controller extends CI_Controller
{

	// Status Codes
	const HTTP_OK = 200;
	const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    

	// Status Error Message
    const HTTP_OK_MSG = 'OK';
    const HTTP_AUTH_SUCCESS = 'AUTHENTICATION SUCCESSFULL';
    const HTTP_INVALID_REQUEST_METHOD = 'INVALID REQUEST METHOD';
    const HTTP_INVALID_ACTION = 'INVALID ACTION';
    const HTTP_INVALID_TYPE = 'INVALID TYPE';
    const HTTP_CREATED_MSG = 'CREATED';
    const HTTP_BAD_REQUEST_MSG = 'BAD REQUEST';
    const HTTP_UNAUTHORIZED_MSG = 'UNAUTHORIZED USER';
    const HTTP_FORBIDDEN_MSG = 'FORBIDDEN';
    const HTTP_NOT_FOUND_MSG = 'NOT FOUND';
    const HTTP_INTERNAL_SERVER_ERROR_MSG = 'INTERNAL SERVER ERROR';
    
    
	
	function __construct()
	{
		parent::__construct();

		// Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization', 'api']);

		// Load model 
		$this->load->model('api_model');
	}

	/*
	* Login method: To authenticate the users
	* 
	*/
	public function login(){
		if($this->input->method() == 'post'){
			// Get username, passwords and roles
			$email = $this->input->post('email', TRUE);
			$mobile_number = $this->input->post('mobile_number', TRUE);
			$user_type = $this->input->post('user_type', TRUE);

			// verify token
			if(($email || $mobile_number) && $user_type){
				$user = $this->api_model->login($user_type, $email, $mobile_number);
				if($user == 'INVALID_MOBILE_SERVICE'){
					$message = 'Mobile service is not integrated for now. Please try after sometime or use email id';
					response(false, self::HTTP_FORBIDDEN, '', $message);
				}
				elseif($user){
					$message = 'Login success, please verify otp to proceed.';
					response(true, self::HTTP_OK, '', $message);
				}
				else{
					$message = self::HTTP_UNAUTHORIZED_MSG.' or something went wrong. Please try again.';
					response(false, self::HTTP_UNAUTHORIZED, '', $message);	
				}
			}
			else{
				$message = 'Validation error: Please provide all the necessary details.';
				response(false, self::HTTP_UNAUTHORIZED, '', $message);
			}
		}
		else{
			$message = self::HTTP_INVALID_REQUEST_METHOD;
			response(false, self::HTTP_BAD_REQUEST, '', $message);
		}
	}

	public function verify_otp(){
		if($this->input->method() == 'post'){
			// Get username, passwords and roles
			$email = $this->input->post('email', TRUE);
			$mobile_number = $this->input->post('mobile_number', TRUE);
			$otp = $this->input->post('otp', TRUE);
			$user_type = $this->input->post('user_type', TRUE);

			// verify token
			if(($email || $mobile_number) && $otp && $user_type){
				$result = $this->api_model->verify_otp($otp, $user_type, $email, $mobile_number);
				if($result['status']){
					$user = $result['result'];
					$user_data = array(
						'id' => $user->id,
						'name' => $user->name,
						'phone_number' => $user->phone,
						'email' => $user->email,
						'role' => $user_type
					);
					$token = generate_token($user_data);
					$message = self::HTTP_AUTH_SUCCESS;
					$data = [
						'token' => $token,
						'user_data' => $user_data
					];
					response(true, self::HTTP_OK, $data, $message);
				}
				else{
					$message = $result['message'];
					response(false, self::HTTP_UNAUTHORIZED, '', $message);	
				}
			}
			else{
				$message = 'Validation error: Please provide all the necessary details.';
				response(false, self::HTTP_UNAUTHORIZED, '', $message);
			}
		}
		else{
			$message = self::HTTP_INVALID_REQUEST_METHOD;
			response(false, self::HTTP_BAD_REQUEST, '', $message);
		}
	}

	public function resend_otp(){
		if($this->input->method() == 'post'){
			// Get username, passwords and roles
			$email = $this->input->post('email', TRUE);
			$mobile_number = $this->input->post('mobile_number', TRUE);
			$user_type = $this->input->post('user_type', TRUE);

			// verify token
			if(($email || $mobile_number) && $user_type){
				$result = $this->api_model->login($user_type, $email, $mobile_number);
				if($result){
					response(true, self::HTTP_OK, '', 'OTP resent successfully.');
				}
				else{
					$message = 'Unauthorized user or Something went wrong. Please try again or contact support.';
					response(false, self::HTTP_INTERNAL_SERVER_ERROR, '', $message);	
				}
			}
			else{
				$message = 'Validation error: Please provide all the necessary details.';
				response(false, self::HTTP_UNAUTHORIZED, '', $message);
			}
		}
		else{
			$message = self::HTTP_INVALID_REQUEST_METHOD;
			response(false, self::HTTP_BAD_REQUEST, '', $message);
		}
	}

}

?>