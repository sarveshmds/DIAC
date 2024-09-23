<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Captcha_generator
{

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('captcha');
    }

    //This function is used for creating captcha//
    public function create_captcha()
    {
        // we will first load the helper. We will not be using autoload because we only need it here
        $capache_config = array(
            'word' => rand(99999, 999999),
            'img_path' => 'public/upload/captcha/',
            'img_url' => base_url() . 'public/upload/captcha/',
            'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
            'img_width' => '200',
            'word_length' => 6,
        );

        $cap = create_captcha($capache_config);
        // we will store the image html code in a variable
        $image = $cap['image'];

        // AUDIO CAPTCHA START ===========================
        /* new file name */
        // $file = './public/upload/captcha/audio_captcha/AC_' . time() . rand(999, 9999) . '.mp3';

        /* open a voice file as writable */
        // $voice = fopen($file, 'a+');
        // for ($i = 0; $i < $capache_config['word_length']; $i++) {
        //     /* get audio file name base on splitted captcha word */
        //     $audio_file = FCPATH . 'public/upload/captcha/audio/' . mb_substr($cap['word'], $i, 1) . '.mp3';
        //     /* open audio file as readonly */
        //     $audio = fopen($audio_file, 'r');
        //     /* read audio data */
        //     $data = fread($audio, filesize($audio_file));
        //     /* write audio data to the end of the voice file (append data to prev. data) */
        //     fwrite($voice, $data);
        //     /* close audio file */
        //     fclose($audio);
        // }
        // /* close voice file */
        // fclose($voice);
        // $audio_captcha = '<audio src="' . base_url($file) . '" id="audio_captcha" controls></audio>';
        // AUDIO CAPTCHA END ===========================

        // ...and store the captcha word in a session
        $this->CI->session->set_userdata('captchaword', $cap['word']);
        // we will return the image html code
        return $image;
    }

    //This Function is used to refresh the Captcha //
    public function refresh_captcha()
    {
        $this->CI->load->helper('captcha');
        // Captcha configuration
        $capache_config = array(
            'word' => rand(99999, 999999),
            'img_path' => 'public/upload/captcha/',
            'img_url' => base_url() . 'public/upload/captcha/',
            'font_path' => FCPATH . 'public/upload/captcha/Roboto-Bold.ttf',
            'img_width' => '218',
            'word_length' => 6,
        );

        $cap = create_captcha($capache_config);
        $image = $cap['image'];

        // AUDIO CAPTCHA START ===========================
        /* new file name */
        // $file = './public/upload/captcha/audio_captcha/AC_' . time() . rand(999, 9999) . '.mp3';

        // /* open a voice file as writable */
        // $voice = fopen($file, 'a+');
        // for ($i = 0; $i < $capache_config['word_length']; $i++) {
        //     /* get audio file name base on splitted captcha word */
        //     $audio_file = FCPATH . 'public/upload/captcha/audio/' . mb_substr($cap['word'], $i, 1) . '.mp3';
        //     /* open audio file as readonly */
        //     $audio = fopen($audio_file, 'r');
        //     /* read audio data */
        //     $data = fread($audio, filesize($audio_file));
        //     /* write audio data to the end of the voice file (append data to prev. data) */
        //     fwrite($voice, $data);
        //     /* close audio file */
        //     fclose($audio);
        // }
        // /* close voice file */
        // fclose($voice);
        // $audio_captcha = '<audio src="' . base_url($file) . '" id="audio_captcha" controls></audio>';
        // AUDIO CAPTCHA END ===========================

        // Unset previous captcha and store new captcha word
        $this->CI->session->unset_userdata('captchaword', $cap['word']);
        $this->CI->session->set_userdata('captchaword', $cap['word']);

        // Display captcha image
        // echo  $image;
        return $image;
    }
}
