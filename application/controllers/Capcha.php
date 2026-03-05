<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller
{
    function create_captcha()
    {
        $data = array(
            'img_path' => './captcha',
            'img_url' => base_url('captcha'),
            'img_width' => '150',
            'img_height' => '30',
            'expiration' => 7200
        );

        $captcha = create_captcha($data);
        $image = $captcha['image'];

        $this->session->set_userdata('captchaword', $captcha['word']);

        return $image;
    }
    function check_captcha()
    {
        if ($this->input->post('captcha') == $this->session->userdata('captchaword')) {

            return true;
        } else {

            $this->form_validation->set_message('check_captcha', 'Captcha tidak sama');

            return false;
        }
    }
}
