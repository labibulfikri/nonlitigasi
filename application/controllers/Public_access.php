<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Public_access extends CI_Controller
{


public function view($token) {
    // 1. Ambil token dari database utama
    $share = $this->db->get_where('share_links', ['token' => $token])->row();

    if (!$share || strtotime($share->expired_at) < time()) {
        die("Link akses tidak valid atau sudah kadaluarsa.");
    }

    $sumber = strtoupper($share->sumber);
    $id_data = $share->id_data;

    $result = [
        'data' => null,
        'detail_tambahan' => [],
        'lampiran' => []
    ];

    if ($sumber === 'NONLIT' || $sumber === 'PERMASALAHAN' || $sumber === 'LAPORAN_POLISI'  ) {
        $result['data'] = $this->db->get_where('nonlits', ['id' => $id_data])->row();
        $result['detail_tambahan'] = $this->db->get_where('nonlit_det', ['id_nonlit' => $id_data])->result();
        $result['lampiran'] = $this->db->get_where('berkas_lampiran', ['id_nonlit' => $id_data ])->result();

    } elseif ($sumber === 'ASING') {
        // 2. Load database kedua (db_perkara)
        // Pastikan 'db_perkara' sudah didaftarkan di config/database.php
        $db2 = $this->load->database('db_perkara', TRUE); 

        if ($db2->conn_id) { // Pastikan koneksi DB berhasil
            // Query t_perkara
            $query_data = $db2->get_where('t_perkara', ['perkara_id' => $id_data]);
            $result['data'] = $query_data ? $query_data->row() : null;

            if ($result['data']) {
                // Query t_perkara_detail
                $query_det = $db2->get_where('t_perkara_detail', ['perkaradet_perkara_id' => $id_data]);
                $result['detail_tambahan'] = $query_det ? $query_det->result() : [];

                // Query t_upload
                $query_file = $db2->get_where('t_upload', ['berkas_perkara_id' => $id_data]);
                $result['lampiran'] = $query_file ? $query_file->result() : [];
            }
        } else {
            die("Gagal menyambung ke database perkara.");
        }
    }

    // 3. Validasi Akhir sebelum ke View
    if (empty($result['data'])) {
        die("Data perkara tidak ditemukan di sistem.");
    }

    $data = [
        'sumber' => $sumber,
        'result' => $result
    ];

    $this->load->view('arsip/public_share_view', $data);
}

    public function view2($token)
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
