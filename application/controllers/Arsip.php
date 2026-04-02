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
        $keyword = $this->input->get('search');

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
        $sumber = $this->input->post('sumber');
        $id_data = $this->input->post('id_data');
        $id_rak = $this->input->post('id_rak');

        if ($this->m_arsip->update_penyimpanan($sumber, $id_data, $id_rak)) {
            $this->session->set_flashdata('success', 'Lokasi rak berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
        redirect('arsip');
    }

    public function get_detail_json()
    {
        $sumber  = $this->input->post('sumber');
        $id_data = $this->input->post('id_data');

        $detail  = $this->m_arsip->get_detail_berkas($sumber, $id_data);

        // Kirim header JSON agar browser mengerti formatnya
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($detail));
    }
}
