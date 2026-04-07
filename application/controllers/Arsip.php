<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Arsip extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_arsip');
        $this->load->library('pagination');
    }


    public function index()
    {
        // 1. Ambil keyword pencarian dari URL (?search=...)
        $keyword = $this->input->get('search', TRUE);

        // 2. Definisi Limit & Start untuk Pagination
        $limit = 10;
        // Mengambil offset dari URI segment ke-3, jika kosong set ke 0
        $start = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // 3. Konfigurasi Pagination (DaisyUI / Tailwind Style)
        $config['base_url']             = base_url('arsip/index');
        $config['total_rows']           = $this->m_arsip->count_all_combined($keyword);
        $config['per_page']             = $limit;
        $config['uri_segment']          = 3;
        $config['reuse_query_string']   = TRUE;

        // Styling Pagination agar sesuai dengan DaisyUI Join Group
        $config['full_tag_open']    = '<div class="join shadow-sm border border-base-300">';
        $config['full_tag_close']   = '</div>';
        $config['first_link']       = '«';
        $config['first_tag_open']   = '<button class="join-item btn btn-sm btn-ghost font-bold">';
        $config['first_tag_close']  = '</button>';
        $config['last_link']        = '»';
        $config['last_tag_open']    = '<button class="join-item btn btn-sm btn-ghost font-bold">';
        $config['last_tag_close']   = '</button>';
        $config['next_tag_open']    = '<button class="join-item btn btn-sm btn-ghost">';
        $config['next_tag_close']   = '</button>';
        $config['prev_tag_open']    = '<button class="join-item btn btn-sm btn-ghost">';
        $config['prev_tag_close']   = '</button>';
        $config['num_tag_open']     = '<button class="join-item btn btn-sm btn-ghost">';
        $config['num_tag_close']    = '</button>';
        $config['cur_tag_open']     = '<button class="join-item btn btn-sm btn-primary font-black text-white">';
        $config['cur_tag_close']    = '</button>';

        $this->pagination->initialize($config);

        // 4. Ambil data gabungan dari Model (Pastikan urutan parameter benar)
        $data['arsip'] = $this->m_arsip->get_combined_arsip($limit, $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();

        // 5. Data Pendukung untuk Modal (Saran Rak & PIC)
        $sql_rak = "SELECT penyimpanan_rak FROM db_perkara.t_perkara 
                UNION 
                SELECT penyimpanan_rak FROM nonlits 
                WHERE penyimpanan_rak IS NOT NULL 
                GROUP BY penyimpanan_rak";
        $data['saran_rak'] = $this->db->query($sql_rak)->result();

        $data['pic_list'] = $this->db->select('nama_pic as pic')
            ->from('master_pic')
            ->where('nama_pic !=', '')
            ->group_by('nama_pic')
            ->order_by('nama_pic', 'ASC')
            ->get()
            ->result();

        // 6. Metadata Layout
        $data['title']      = 'Penyimpanan Berkas Terpusat';
        $data['content']    = 'arsip/index';

        // 7. Load View
        $this->load->view('layout/layout2', $data);
    }

    public function update_rak()
    {
        $sumber = $this->input->post('sumber', TRUE);
        $id_data = $this->input->post('id_data', TRUE);
        $id_rak = $this->input->post('id_rak', TRUE);

        if ($this->m_arsip->update_penyimpanan($sumber, $id_data, $id_rak)) {
            $this->session->set_flashdata('success', 'Lokasi rak berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
        redirect('arsip');
    }

    public function get_detail_json()
    {
        cek_csrf(); // Validasi Token

        $sumber  = $this->input->post('sumber');
        $id_data = $this->input->post('id_data');

        $result = $this->m_arsip->get_detail_berkas($sumber, $id_data);

        // Regenerate Token Baru
        $new_token = hash('sha1', time() . mt_rand());
        $this->session->set_userdata('csrf_token', $new_token);

        $result['new_token'] = $new_token;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function get_detail_json2()
    {
        // 1. Validasi Token via Helper Manual
        cek_csrf();

        $sumber  = $this->input->post('sumber');
        $id_data = $this->input->post('id_data');

        $data = null;
        $lampiran = [];

        // Query spesifik berdasarkan sumber
        if ($sumber === 'UMUM') {
            $data = $this->db->get_where('berkas_umum', ['id_berkas_umum' => $id_data])->row();
            $lampiran = $this->db->get_where('berkas_umum_det', ['id_berkas_umum' => $id_data])->result();
        } else if ($sumber === 'POLISI') {
            $data = $this->db->get_where('laporan_polisi', ['id_laporan_polisi' => $id_data])->row();
            $lampiran = $this->db->get_where('laporan_polisi_det', ['id_laporan_polisi' => $id_data])->result();
        } else if ($sumber === 'MASALAH') {
            $data = $this->db->get_where('masalah', ['id_masalah' => $id_data])->row();
            $lampiran = $this->db->get_where('masalah_det', ['id_masalah' => $id_data])->result();
        } else if ($sumber === 'NONLIT') {
            $data = $this->db->get_where('nonlits', ['id' => $id_data])->row();
            $lampiran = $this->db->get_where('nonlit_det', ['id_nonlit' => $id_data])->result();
        } else if ($sumber === 'ASING') {
            $data = $this->db->get_where('t_perkara', ['perkara_id' => $id_data])->row();
            $lampiran = $this->db->get_where('t_perkara_detail', ['perkara_id' => $id_data])->result();
        }

        // 2. REGENERATE TOKEN (PENTING!)
        $new_token = hash('sha1', time() . mt_rand());
        $this->session->set_userdata('csrf_token', $new_token);

        // 3. Kirim JSON
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'    => ($data ? true : false),
                'data'      => $data,
                'lampiran'  => $lampiran,
                'new_token' => $new_token
            ]));
    }


    public function generate_share_link()
    {
        cek_csrf(); // Validasi token yang dikirim

        $sumber = $this->input->post('sumber');
        $id_data = $this->input->post('id_data');
        $durasi = $this->input->post('durasi');

        // Logic simpan ke table share_links
        $token_publik = bin2hex(random_bytes(32));
        $expired = date('Y-m-d H:i:s', strtotime("+$durasi hours"));
        $this->db->insert('share_links', [
            'sumber' => $sumber,
            'id_data' => $id_data,
            'token' => $token_publik,
            'expired_at' => $expired
        ]);

        // --- REGENERASI TOKEN CSRF BARU ---
        $new_csrf = hash('sha1', time() . mt_rand());
        $this->session->set_userdata('csrf_token', $new_csrf);

        echo json_encode([
            'status' => true,
            'url' => base_url("public_access/view/$token_publik"),
            'expired' => date('d M Y, H:i', strtotime($expired)) . ' WIB',
            'new_token' => $new_csrf // Kirim token baru ke JS
        ]);
    }
}
