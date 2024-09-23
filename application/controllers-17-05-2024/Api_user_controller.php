<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 328600");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Controller to handle all the api request
 */
class Api_user_controller extends CI_Controller
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
    
    public $user_role = '';
	public $user_data = '';
	
	function __construct()
	{
		parent::__construct();

		// Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization', 'api']);

		// Load model 
		$this->load->model(['api_model', 'getter_model']);

        // verify token before accessing all the get request
        // It will also give the decrypted data of token
        $jwt_decrypted_data = verify_request($this->input->request_headers());
        
        if(!$jwt_decrypted_data){
            $message = self::HTTP_UNAUTHORIZED_MSG;
            response(false, self::HTTP_UNAUTHORIZED, '', $message);
        }
        else{
            if(in_array($jwt_decrypted_data->role, ['CLAIMANT_RESPONDENT', 'COUNSEL', 'ARBITRATOR'])){
				$this->user_data = $jwt_decrypted_data;
            }
            else{
                $message = self::HTTP_FORBIDDEN_MSG;
                response(false, self::HTTP_FORBIDDEN, '', $message);
            }
        }
	}
	
	public function get_case_list(){
		$result = $this->api_model->all_users_case_list($this->user_data);
        $message = self::HTTP_OK_MSG;
        $data = ['results' => $result];
        response(true, self::HTTP_OK, $data, $message);
	}

	public function get_case_detail(){
		if($this->input->get('case_number')){
			$result = $this->api_model->user_case_detail($this->user_data, $this->input->get('case_number'));
			$message = self::HTTP_OK_MSG;
			$data = ['results' => $result];
			response(true, self::HTTP_OK, $data, $message);
		}
		else{
			response(false, self::HTTP_NOT_FOUND, '', 'Page not found.');
		}
	}

    public function get_cause_list(){
		$result = $this->api_model->all_users_cause_list($this->user_data);
        $message = self::HTTP_OK_MSG;
        $data = ['results' => $result];
        response(true, self::HTTP_OK, $data, $message);
	}

	/*
	* Function to handle all the get request
	*/
	function get(){
		
		
		try{
			// verify token before accessing all the get request
			// It will also give the decrypted data of token
			$jwt_decrypted_data = verify_request($this->input->request_headers());
			
			if(!$jwt_decrypted_data){
				$message = self::HTTP_UNAUTHORIZED_MSG;
				response(false, self::HTTP_UNAUTHORIZED, '', $message);
			}
			else{
				
				if(in_array($jwt_decrypted_data->role, ['ADMIN', 'DIAC', 'CASE_MANAGER', 'CASE_FILER', 'COORDINATOR', 'ACCOUNTS', 'CAUSE_LIST_MANAGER', 'POA_MANAGER', 'GUEST'])){
					
					// Get type (it is to get which data user wants)
					$type = $this->input->get('type', TRUE);
					$action = $this->input->get('action', TRUE);
	
					switch($type){
						case 'cause_list':
							
							if($action == 'all'){
	
								// Get all cause list
								$result = $this->api_model->all_cause_list();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
	
							}
							elseif($action == 'today'){
								// Get todays cause list
								$result = $this->api_model->today_cause_list();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
							}
							else{
								$message = self::HTTP_INVALID_ACTION;
								response(false, self::HTTP_NOT_FOUND, '', $message);
							}
	
						break;
	
						case 'case_list':
							if($action == 'all'){
								// Get all case list
								$result = $this->api_model->all_case_list();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
							}
							elseif($action == 'claimant_respondant'){
								// Get all case list
								$result = $this->api_model->case_list_car();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
							}
							elseif($action == 'counsel'){
								// Get all case list
								$result = $this->api_model->case_list_counsels();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
							}
							else{
								$message = self::HTTP_INVALID_ACTION;
								response(false, self::HTTP_NOT_FOUND, '', $message);	
							}
						break;
	
						case 'panel_category':
							if($action == 'all'){
								// Get all panel category list
								$result = $this->api_model->all_panel_category_list();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
							}
							else{
								$message = self::HTTP_INVALID_ACTION;
								response(false, self::HTTP_NOT_FOUND, '', $message);
							}
						break;
	
						case 'panel_of_arbitrator':
							if($action == 'all'){
	
								// Get all panel of arbitrator list
								$result = $this->api_model->all_poa_list();
								$message = self::HTTP_OK_MSG;
								$data = ['results' => $result];
								response(true, self::HTTP_OK, $data, $message);
	
							}
							elseif($action == 'cat_id'){
								// Get panel of arbitrator list according to category id
								$cat_id = $this->security->xss_clean($this->input->get('cat_id'));
								if(empty($cat_id)){
									$message = 'Category id is empty.';
									response(false, self::HTTP_NOT_FOUND, '', $message);
	
								}
								elseif(!is_numeric($cat_id)){
									$message = 'Category id can only be number.';
									response(false, self::HTTP_NOT_FOUND, '', $message);
								}
								else{
	
									// Get the role
									$role = $jwt_decrypted_data->role;
									// Get the result on role based
									$result = $this->api_model->poa_list_with_cat_id($cat_id, $role);
									
									$message = self::HTTP_OK_MSG;
									$data = ['results' => $result];
									response(true, self::HTTP_OK, $data, $message);
								}
	
							}
							else{
								$message = self::HTTP_INVALID_ACTION;
								response(false, self::HTTP_NOT_FOUND, '', $message);
							}
						break;
	
						default:
							$message = self::HTTP_INVALID_TYPE;
							response(false, self::HTTP_NOT_FOUND, '', $message);
						break;
					}
				}
				else{
					$message = self::HTTP_FORBIDDEN_MSG;
					response(false, self::HTTP_FORBIDDEN, '', $message);
				}
	
			}
		}
		catch(Exception $e){
			$message = self::HTTP_INTERNAL_SERVER_ERROR_MSG;
			response(false, self::HTTP_INTERNAL_SERVER_ERROR, '', $message);
		}
	
	}
}

?>