<?php defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
		# helpers
		$this->load->helper(array('form'));

		# libraries
		$this->load->library('encryption');

		# models
		$this->load->model(['user_model', 'superadmin_model']);

		$this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

		if ($this->session->userdata('key') == '')
			$this->session->set_userdata('key', uniqid());
	}

	/*
	*	purpose : Handle page not found
	*/
	public function page_not_found()
	{
		$this->load->view('templates/404.php');
		$this->load->view('templates/admin_footer');
	}


	public function efiling_index()
	{
		$this->load->view('efiling/efiling_landing_page');
	}

	/**
	 *	purpose : Entry point of user controller
	 */
	public function index()
	{
		redirect('login');
	}
	//This function is used for creating captcha//
	private function create_captcha()
	{
		// we will first load the helper. We will not be using autoload because we only need it here
		$this->load->helper('captcha');
		$capache_config = array(
			'word' => rand(99999, 999999),
			'img_path' => 'public/upload/captcha/',
			'img_url' => base_url() . 'public/upload/captcha/',
			'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
			'img_width' => '200',
		);
		// we will set all the variables needed to create the captcha image
		/*$options = array('img_path'=>FCPATH.'captcha/','img_url'=>site_url().'captcha/','img_width'=>'150','img_height'=>'40','expiration'=>7200,'word_length' => 6,'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ','font_size' => 46,'colors' => array( 'background' => array(255,255,255), 'border' => array(153,102,102), 'text' => array(204,153,153), 'grid' => array(255,182,182)) );*/
		//now we will create the captcha by using the helper function create_captcha()
		$cap = create_captcha($capache_config);
		// we will store the image html code in a variable
		$image = $cap['image'];

		// ...and store the captcha word in a session
		$this->session->set_userdata('captchaword', $cap['word']);
		// we will return the image html code
		return $image;
	}

	//This Function is used to refresh the Captcha //
	public function refresh_captcha()
	{
		$this->load->helper('captcha');
		// Captcha configuration
		$capache_config = array(
			'word' => rand(99999, 999999),
			'img_path' => 'public/upload/captcha/',
			'img_url' => base_url() . 'public/upload/captcha/',
			'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
			'img_width' => '218',
		);
		$captcha = create_captcha($capache_config);
		$image = $captcha['image'];

		// Unset previous captcha and store new captcha word
		$this->session->unset_userdata('captchaword', $captcha['word']);
		$this->session->set_userdata('captchaword', $captcha['word']);

		// Display captcha image
		echo  $image;
	}
	/**
	 *	purpose : User Login
	 */
	public function login()
	{
		$data['image'] = $this->create_captcha();
		$data['page_title'] = 'Login';
		$this->load->view('user/login', $data);
	}
	// logout user 
	public function logout()
	{
		$result = $this->user_model->logout();
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('user_code');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_display_name');
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('role_name');
		$this->session->unset_userdata('group_code');
		$this->session->unset_userdata('dept_code');
		$this->session->unset_userdata('captchaword');
		$this->session->unset_userdata('sess_id');
		$this->session->unset_userdata('state_code');
		$this->session->sess_destroy();
		// redirect to login
		$this->session->set_flashdata('info', 'User logout');
		redirect('login');
	}
	public function forgot_password()
	{
		$data['image'] = $this->create_captcha();
		$this->load->view('user/forgot_password', $data);
	}
	public function reset_password()
	{
		try {
			$token = $this->uri->segment(3);
			if ($token) {
				$output = $this->superadmin_model->superadmin($token, 'get_password');
				if ($output['status'] == 'success') {
					$data['user_code'] = $output['id'];
					$data['user_name'] = $output['ins'];
					$this->load->view('user/reset_password', $data);
				} else {
					$this->load->view('errors/html/session_expire');
				}
			} else {
				$this->load->view('errors/html/session_expire');
			}
		} catch (Exception $e) {
			$this->load->view('errors/html/session_expire');
		}
	}
	public function set_password()
	{
		$this->load->view('user/set_password');
	}

	public function user_register()
	{
		$this->load->view('user/user_register');
	}
}
