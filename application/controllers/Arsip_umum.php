<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip_umum extends CI_Controller
{

    public function simpan()
    {
        $this->load->model('m_arsip_umum');
        $this->load->library('upload');

        // Menyiapkan data header sesuai nama kolom di tabel berkas_umum
        $data_header = [
            'nama_berkas_umum' => $this->input->post('nama_berkas_umum'),
            'penyimpanan_rak'  => $this->input->post('penyimpanan_rak'),
            'pic'              => $this->input->post('pic'),
            'keterangan'       => $this->input->post('keterangan')
        ];

        $uploaded_files = [];
        $files = $_FILES['files'];

        // Proses Multiple Upload
        if (!empty($files['name'][0])) {
            foreach ($files['name'] as $key => $val) {
                $_FILES['file']['name']     = $files['name'][$key];
                $_FILES['file']['type']     = $files['type'][$key];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['file']['error']    = $files['error'][$key];
                $_FILES['file']['size']     = $files['size'][$key];

                $config['upload_path']   = './assets/berkas_umum/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                $config['encrypt_name']  = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $fileData = $this->upload->data();
                    $uploaded_files[] = $fileData['file_name'];
                }
            }
        }

        if ($this->m_arsip_umum->simpan_arsip($data_header, $uploaded_files)) {
            $this->session->set_flashdata('success', 'Digitalisasi berkas umum berhasil disimpan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses penyimpanan berkas.');
        }
        redirect('arsip');
    }

    public function append_file()
    {
        $this->load->library('upload');
        $id_data = $this->input->post('id_berkas_umum');
        $uploaded_files = [];
        $files = $_FILES['files'];

        if (!empty($files['name'][0])) {
            foreach ($files['name'] as $key => $val) {
                $_FILES['file']['name']     = $files['name'][$key];
                $_FILES['file']['type']     = $files['type'][$key];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['file']['error']    = $files['error'][$key];
                $_FILES['file']['size']     = $files['size'][$key];

                $config['upload_path']   = './assets/berkas_umum/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                $config['encrypt_name']  = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $fileData = $this->upload->data();
                    // Simpan langsung ke detail per file
                    $this->db->insert('berkas_umum_det', [
                        'id_berkas_umum' => $id_data,
                        'nama_file'      => $fileData['file_name'],
                        'tgl_upload'     => date('Y-m-d H:i:s')
                    ]);
                }
            }
            // Mengirim response balik ke Javascript agar modal bisa refresh
            echo json_encode(['status' => true, 'id_data' => $id_data]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Tidak ada file dipilih']);
        }
    }
}
