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
            $data = array(
                'masterpage' => 'layout/layout2',
                'content' => 'data_berkas_umum/data_berkas_umum',
                'berkas' => $data['berkas'],

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
}
