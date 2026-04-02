<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_arsip_umum extends CI_Model
{

    public function simpan_arsip($data_header, $data_files)
    {
        $this->db->trans_start();

        // Simpan ke tabel utama berkas_umum
        $this->db->insert('berkas_umum', $data_header);
        $id_utama = $this->db->insert_id();

        // Simpan ke tabel detail jika ada file yang diupload
        if (!empty($data_files)) {
            foreach ($data_files as $file) {
                $this->db->insert('berkas_umum_det', [
                    'id_berkas_umum' => $id_utama,
                    'nama_file'      => $file,
                    'tgl_upload'     => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
