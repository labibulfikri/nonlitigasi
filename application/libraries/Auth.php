<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth
{

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('session');
    }

    public function authenticate()
    {
        $headers = $this->CI->input->request_headers();
        if (!isset($headers['Authorization'])) {
            $this->send_unauthorized('Authorization header missing');
            return false;
        }

        list($type, $data) = explode(" ", $headers['Authorization'], 2);

        if (strcasecmp($type, 'Basic') != 0) {
            $this->send_unauthorized('Invalid authorization type');
            return false;
        }

        $credentials = base64_decode($data);
        if ($credentials === false) {
            $this->send_unauthorized('Invalid credentials encoding');
            return false;
        }

        list($username, $password) = explode(":", $credentials, 2);

        if ($username === false || $password === false) {
            $this->send_unauthorized('Invalid credentials format');
            return false;
        }

        if ($username === 'labibul' && $password === 'fikri') {
            return true;
        } else {
            $this->send_unauthorized('Invalid username or password');
            return false;
        }
    }

    private function send_unauthorized($message = 'Unauthorized')
    {
        $CI = &get_instance();
        $CI->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => $message,
                'data' => null
            ]));
        exit;
    }
}
