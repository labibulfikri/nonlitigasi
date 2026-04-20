<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Berkas_umum extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_berkas_umum');
        $this->load->model('m_pic');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }
    public function index()
    {

        if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else {
            //list pic

            $data['berkas'] = $this->m_berkas_umum->get_all();

            // Ambil data Master PIC (asumsi nama tabel: master_pic)
            $data['master_pic'] = $this->db->get('master_pic')->result();

            // Ambil daftar Rak yang unik dari tabel berkas_umum untuk datalist
            $data['list_rak'] = $this->db->select('penyimpanan_rak')
                ->group_by('penyimpanan_rak')
                ->get('berkas_umum')
                ->result();
            $data = array(
                'masterpage' => 'layout/layout2',
                'content' => 'data_berkas_umum/data_berkas_umum',
                'berkas' => $data['berkas'],
                'master_pic' => $data['master_pic'],
                'list_rak' => $data['list_rak'],
                // 'footer' => 'layout/footer',
                'title' => 'Daftar Berkas Umum'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    //detail berkas umum
    public function detail($id)
    {

        $data['parent'] = $this->m_berkas_umum->get_by_id($id);
        $data['files'] = $this->m_berkas_umum->get_detail($id);

        $data = array(
            'masterpage' => 'layout/layout2',
            'content' => 'data_berkas_umum/detail_berkas_umum',
            'parent' => $data['parent'],
            'files' => $data['files'],
            'title' => 'Detail Berkas Umum'
        );
        $this->load->view($data['masterpage'], $data);
    }

    public function simpan()
    {

        $id = $this->input->post('id_berkas_umum');

        $data = [
            'nama_berkas_umum' => $this->input->post('nama_berkas_umum'),
            'keterangan'       => $this->input->post('keterangan'),
            'penyimpanan_rak'       => $this->input->post('penyimpanan_rak'),
            'pic'           => $this->input->post('pic')
        ];

        if ($id) {
            // Jika ada ID, maka Update
            $this->m_berkas_umum->update($id, $data);
            echo "success_update";
        } else {
            // Jika tidak ada ID, maka Insert
            $this->m_berkas_umum->insert($data);
            echo "success_insert";
        }
    }
    public function get_edit($id)
    {
        $data = $this->m_berkas_umum->get_by_id($id);
        echo json_encode($data);
    }
    public function hapus($id)
    {
        $berkas = $this->m_berkas_umum->get_by_id($id);
        if ($berkas) {
            unlink('./assets/berkas_umum/' . $berkas->nama_berkas);
            $this->m_berkas_umum->delete($id);
        }
        redirect('berkas_umum');
    }

    // Fungsi simpan detail (Upload Baru)
    public function upload_detail_file()
    {
        $id_parent = $this->input->post('id_berkas_umum');
        $judul = $this->input->post('judul_file');

        $path = './assets/berkas_umum/detail/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['upload_path']   = $path;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
        // Menghasilkan nama file unik berdasarkan judul
        $config['file_name']     = url_title($judul, 'dash', TRUE) . '-' . time();

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file_upload')) {
            $uploadData = $this->upload->data();
            $data = [
                'id_berkas_umum'    => $id_parent,
                'judul_berkas_umum' => $judul,      // Sesuai kolom DB Anda
                'nama_file'         => $uploadData['file_name'], // Sesuai kolom DB Anda
                'tgl_upload'        => date('Y-m-d H:i:s')
            ];
            $this->db->insert('berkas_umum_det', $data);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // Fungsi Update Nama Saja
    public function update_nama_detail()
    {
        $id = $this->input->post('id_berkas_umum_det');
        $data = ['judul_berkas_umum' => $this->input->post('judul_file')];
        $this->db->where('id_berkas_umum_det', $id)->update('berkas_umum_det', $data);
        echo "success";
    }

    // Fungsi Hapus
    public function hapus_detail($id)
    {
        $file = $this->db->get_where('berkas_umum_det', ['id_berkas_umum_det' => $id])->row();
        if ($file) {
            $path = './assets/berkas_umum/detail/' . $file->nama_file;
            if (file_exists($path)) unlink($path);
            $this->db->where('id_berkas_umum_det', $id)->delete('berkas_umum_det');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function generate_share_folder($id_data)
    {
        $sumber = 'berkas_umum'; // Penanda bahwa ini dari folder umum

        // 1. Cek apakah link sudah ada di tabel share_links
        $cek = $this->db->get_where('share_links', [
            'sumber'  => $sumber,
            'id_data' => $id_data
        ])->row();

        if ($cek) {
            $token = $cek->token;
        } else {
            // 2. Jika belum ada, buat token baru
            $token = bin2hex(random_bytes(16));
            $this->db->insert('share_links', [
                'sumber'     => $sumber,
                'id_data'    => $id_data,
                'token'      => $token,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+7 days')) // Contoh: expired dalam 7 hari
            ]);
        }

        echo base_url('berkas_umum/folder/' . $token);
    }

    public function folder($token)
    {
        // 1. Cari token di tabel share_links
        $link = $this->db->get_where('share_links', ['token' => $token])->row();

        // 2. Validasi: Ada tidak? Expired tidak?
        if (!$link || ($link->expired_at && $link->expired_at < date('Y-m-d H:i:s'))) {
            show_error('Link sudah kadaluarsa atau tidak valid.', 404);
            return;
        }

        // 3. Ambil data asli berdasarkan id_data di tabel share_links
        $parent = $this->db->get_where('berkas_umum', ['id_berkas_umum' => $link->id_data])->row();

        if (!$parent) {
            show_404();
            return;
        }

        $data['parent'] = $parent;
        $data['files']  = $this->db->get_where('berkas_umum_det', ['id_berkas_umum' => $parent->id_berkas_umum])->result();

        $this->load->view('data_berkas_umum/public_folder_share', $data);
    }
}
