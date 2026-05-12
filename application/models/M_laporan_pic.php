<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan_pic extends CI_Model
{
protected $table      = 'users'; // Tabel user/pegawai
    protected $primaryKey = 'id';
    
public function getSummaryPerPic($tahun = null) {
   $this->db->select('u.id, u.nama_pic as nama_lengkap');
    $this->db->from('master_pic u');
$filter = "";
    if (!empty($tahun)) {
        // Asumsi menggunakan kolom updated_at atau tgl_nonlit untuk filter
        $filter = " AND YEAR(tgl_nonlit) = " . $this->db->escape($tahun);
    }

    // Subquery dengan Filter Tahun
    $this->db->select("COALESCE((SELECT GROUP_CONCAT(permohonan_nonlit SEPARATOR '|') FROM nonlits WHERE id_pic = u.id AND jenis = 'nonlit' $filter), '') as list_nonlit");
    $this->db->select("COALESCE((SELECT GROUP_CONCAT(permohonan_nonlit SEPARATOR '|') FROM nonlits WHERE id_pic = u.id AND jenis = 'laporan_polisi' $filter), '') as list_lp");
    $this->db->select("COALESCE((SELECT GROUP_CONCAT(permohonan_nonlit SEPARATOR '|') FROM nonlits WHERE id_pic = u.id AND jenis = 'permasalahan' $filter), '') as list_masalah");

    $this->db->select("(SELECT COUNT(*) FROM nonlits WHERE id_pic = u.id AND jenis = 'nonlit' $filter) as total_nonlit");
    $this->db->select("(SELECT COUNT(*) FROM nonlits WHERE id_pic = u.id AND jenis = 'laporan_polisi' $filter) as total_lp");
    $this->db->select("(SELECT COUNT(*) FROM nonlits WHERE id_pic = u.id AND jenis = 'permasalahan' $filter) as total_masalah");
    $query = $this->db->get();

    if ($query) {
        return $query->result_array();
    } else {
        // Ini akan memunculkan pesan error SQL aslinya jika masih gagal
        $error = $this->db->error();
        die("Kesalahan Query: " . $error['message']);
    }
}
    public function getDetailProject($id_pic)
    {
        // Mengambil semua data project untuk satu PIC tertentu
        return $this->db->table('nonlits')
            ->where('id_pic', $id_pic)
            ->orderBy('jenis', 'ASC')
            ->get()->getResultArray();
    }


   public function getDetailByPic($id, $tahun = null) {
    // 1. Definisikan subquery untuk menghitung rapat agar tidak bentrok dengan join
    $this->db->select('n.*');
    $this->db->select("(SELECT COUNT(*) FROM nonlit_det WHERE id_nonlit = n.id) as total_rapat");
    $this->db->from('nonlits n');
    
    // 2. Filter berdasarkan ID PIC
    $this->db->where('n.id_pic', $id);
    
    // 3. Filter berdasarkan Tahun (jika dipilih)
    if (!empty($tahun)) {
        // Gunakan kolom tgl_nonlit atau updated_at sesuai kebutuhan filter Anda
        $this->db->where("YEAR(n.tgl_nonlit) =", $tahun);
    }

    $this->db->order_by('n.tgl_nonlit', 'DESC');
    
    $query = $this->db->get();

    // 4. Cek apakah query berhasil dijalankan
    if ($query) {
        return $query->result_array();
    } else {
        // Menampilkan pesan error SQL yang sebenarnya untuk debugging
        $error = $this->db->error();
        die("Database Error: " . $error['message']);
    }
}

}
