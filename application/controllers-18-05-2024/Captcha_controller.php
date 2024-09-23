
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Captcha_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('captcha_generator');
    }

    //This Function is used to refresh the Captcha //
    public function refresh_captcha()
    {
        $captcha_data = $this->captcha_generator->refresh_captcha();
        echo $captcha_data;
    }
}
