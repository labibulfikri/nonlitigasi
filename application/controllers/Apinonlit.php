<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Apinonlit extends CI_Controller
{
    private $username = 'assist'; // Ganti dengan username yang Anda inginkan
    private $password = 'P4ssw0rd@123'; // Ganti dengan password yang Anda inginkan


    function __construct()
    {
        parent::__construct();

        // $this->load->library('Auth'); // Memuat library Auth
        $this->load->model('m_nonlit');
        $this->load->model('m_home');
        $this->load->model('m_peta');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }

    private function _authenticate()
    {
        $headers = $this->input->request_headers();
        if (!isset($headers['Authorization'])) {
            $this->_send_unauthorized();
            return false;
        }

        list($type, $data) = explode(" ", $headers['Authorization'], 2);

        if (strcasecmp($type, 'Basic') != 0) {
            $this->_send_unauthorized();
            return false;
        }

        $credentials = base64_decode($data);
        list($username, $password) = explode(":", $credentials, 2);

        if ($username == $this->username && $password == $this->password) {
            return true;
        } else {
            $this->_send_unauthorized();
            return false;
        }
    }

    private function _send_unauthorized()
    {
        log_message('debug', 'Entering _send_unauthorized()');

        $response = json_encode([
            'success' => false,
            'message' => 'Unauthorized',
            'data' => null
        ], JSON_PRETTY_PRINT);

        $this->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output($response);


        exit;
    }




    public function index($register = null)
    {
        // Autentikasi pengguna
        if (!$this->_authenticate()) {
            return;
        }

        try {

            // Ambil data dari model dengan parameter 'register'


            $data = $this->m_nonlit->m_apinonlit_id($register);


            // Format respon
            $response = [
                'success' => true,
                // 'message' => 'Get Data Nonlits',
                'data' => $data
            ];

            // Kirimkan respon dalam format JSON
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        } catch (Exception $e) {

            $this->_send_error($e->getMessage());
            // $response = json_encode([
            //     'success' => false,
            //     'message' => 'Unauthorized',
            //     'data' => null
            // ], JSON_PRETTY_PRINT);

            // return $this->output
            //     ->set_status_header(401)
            //     ->set_content_type('application/json')
            //     ->set_output($response);
        }
    }
    private function _send_error($message)
    {
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => $message,
                'data' => null
            ], JSON_PRETTY_PRINT));
        exit;
    }
}
