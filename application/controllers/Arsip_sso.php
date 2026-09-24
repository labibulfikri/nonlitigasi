<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip_sso extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_arsip_sso');

        // CORS Headers agar bisa dipanggil lintas domain
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header('Content-Type: application/json');
    }

    /**
     * Helper Privat untuk Validasi Basic Auth
     */
    private function _check_basic_auth()
    {
        $valid_username = 'admin_arsip';
        $valid_password = 'Samudera123';

        $auth_user = $this->input->server('PHP_AUTH_USER');
        $auth_pw   = $this->input->server('PHP_AUTH_PW');

        if ($auth_user !== $valid_username || $auth_pw !== $valid_password) {
            header('WWW-Authenticate: Basic realm="Akses API Arsip Terbatas"');
            $this->output
                ->set_status_header(401)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Unauthorized: Username atau Password API salah'
                ]));
            exit();
        }
    }

    /**
     * ENDPOINT 1: Get List Data Ringkas
     * URL: GET https://domain-anda.com/arsip_api/get_data?search=...&limit=10&page=1
     */
    public function get_data()
    {
        $this->_check_basic_auth();

        $keyword = $this->input->get('search', TRUE);
        $limit   = $this->input->get('limit', TRUE) ? (int)$this->input->get('limit', TRUE) : 10;
        $page    = $this->input->get('page', TRUE) ? (int)$this->input->get('page', TRUE) : 1;
        $start   = ($page - 1) * $limit;

        $total_data = $this->m_arsip_sso->count_all_combined_sso($keyword);
        $data_arsip = $this->m_arsip_sso->get_combined_arsip_sso($limit, $start, $keyword);

        $response = [
            'status'     => true,
            'message'    => 'Data arsip berhasil diambil',
            'pagination' => [
                'total_rows'   => (int)$total_data,
                'per_page'     => $limit,
                'current_page' => $page,
                'total_pages'  => ceil($total_data / $limit)
            ],
            'data'       => $data_arsip
        ];

        $this->output
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    /**
     * ENDPOINT 2: Get Detail Data + Lampiran Berkas
     * URL: GET https://domain-anda.com/arsip_api/detail?sumber=ASING&id=102
     */
    public function detail()
    {
        $this->_check_basic_auth();

        $sumber  = strtoupper($this->input->get('sumber', TRUE));
        $id_data = $this->input->get('id', TRUE);

        if (!$sumber || !$id_data) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Parameter "sumber" dan "id" wajib diisi'
                ]));
            return;
        }

        $detail = $this->m_arsip_sso->get_detail_arsip($sumber, $id_data);

        if ($detail) {
            $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'data'   => $detail
                ]));
        } else {
            $this->output
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Data detail tidak ditemukan'
                ]));
        }
    }


    /**
     * ENDPOINT 3: Get Progres Scan (Fitur Baru)
     * URL: GET https://domain-anda.com/arsip_sso/progres_scan?tanggal=YYYY-MM-DD
     */
    public function progres_scan()
    {
        $this->_check_basic_auth();

        $tanggal = $this->input->get('tanggal', TRUE);

        // Validasi format tanggal jika diisi (YYYY-MM-DD)
        if ($tanggal && !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $tanggal)) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.'
                ]));
            return;
        }

        $data_progres = $this->m_arsip_sso->get_progres_scan($tanggal);

        if ($data_progres) {
            $this->output
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => 'Data progres scan berhasil diambil',
                    'data'    => $data_progres
                ]));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Gagal mengambil data progres scan'
                ]));
        }
    }
}
