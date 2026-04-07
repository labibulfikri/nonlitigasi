<<<<<<< HEAD
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Public_access extends CI_Controller
{

    public function view($token)
    {
        // 1. Cek apakah token ada dan belum expired
        $link = $this->db->get_where('share_links', [
            'token' => $token,
            'expired_at >' => date('Y-m-d H:i:s')
        ])->row();

        if (!$link) {
            die("Link sudah tidak berlaku atau tidak ditemukan.");
        }

        // 2. Ambil data asli menggunakan model m_arsip yang sudah Anda punya
        $this->load->model('m_arsip');
        $data['result'] = $this->m_arsip->get_detail_berkas($link->sumber, $link->id_data);
        $data['sumber'] = $link->sumber;

        // 3. Tampilkan view khusus (tanpa sidebar/navbar admin)
        $this->load->view('arsip/public_share_view', $data);
    }
}
=======
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Public_access extends CI_Controller
{

    public function view($token)
    {
        // 1. Cek apakah token ada dan belum expired
        $link = $this->db->get_where('share_links', [
            'token' => $token,
            'expired_at >' => date('Y-m-d H:i:s')
        ])->row();

        if (!$link) {
            die("Link sudah tidak berlaku atau tidak ditemukan.");
        }

        // 2. Ambil data asli menggunakan model m_arsip yang sudah Anda punya
        $this->load->model('m_arsip');
        $data['result'] = $this->m_arsip->get_detail_berkas($link->sumber, $link->id_data);
        $data['sumber'] = $link->sumber;

        // 3. Tampilkan view khusus (tanpa sidebar/navbar admin)
        $this->load->view('arsip/public_share_view', $data);
    }
}
>>>>>>> Initial commit dari server
