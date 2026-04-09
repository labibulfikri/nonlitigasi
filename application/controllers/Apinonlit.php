<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apinonlit extends CI_Controller
{
    // Kredensial untuk Basic Auth
    private $username = 'assist';
    private $password = 'P4ssw0rd@123';

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_nonlit');
        $this->load->library('session');
        $this->load->helper('security');

        // Mencegah clickjacking
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }

    /**
     * Fungsi Autentikasi Internal
     */
    private function _authenticate()
    {
        $headers = $this->input->request_headers();
        $auth = null;

        // Tambahkan pengecekan X-Authorization
        if (isset($headers['X-Authorization'])) {
            $auth = $headers['X-Authorization'];
        } elseif (isset($headers['x-authorization'])) {
            $auth = $headers['x-authorization'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!$auth) {
            return $this->_send_unauthorized("Header X-Authorization tidak ditemukan di server");
        }

        if (strpos($auth, 'Basic ') !== 0) {
            return $this->_send_unauthorized("Format harus Basic");
        }

        $decoded = base64_decode(substr($auth, 6));

        // Keamanan: Cek apakah decode berhasil dan mengandung titik dua
        if (strpos($decoded, ':') === false) {
            return $this->_send_unauthorized("Kredensial tidak valid");
        }

        list($user, $pass) = explode(':', $decoded);

        if (trim($user) === $this->username && trim($pass) === $this->password) {
            return true;
        }

        return $this->_send_unauthorized("Login gagal: Username/Password salah");
    }

    /**
     * Endpoint Utama
     */
    public function index($register = null)
    {
        // ================= CORS =================
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Authorization, X-Requested-With");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Methods: GET, OPTIONS");
        // header("Access-Control-Allow-Headers: Content-Type, Authorization");
        // header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Authorization, X-Requested-With");
        // header("Access-Control-Allow-Credentials: true");
        // if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        //     http_response_code(200);
        //     exit();
        // }

        // ================= AUTH =================
        if (!$this->_authenticate()) {
            return;
        }

        try {
            $data = $this->m_nonlit->m_apinonlit_id($register);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'data' => $data
                ]));
        } catch (Exception $e) {
            $this->_send_error($e->getMessage());
        }
    }

    private function _send_unauthorized($msg = "Unauthorized")
    {
        $this->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => false,
                'message' => $msg
            ]));
        // Penting: Hentikan eksekusi script
        $this->output->_display();
        exit;
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
        $this->output->_display();
        exit;
    }
}
