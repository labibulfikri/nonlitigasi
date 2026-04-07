<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_report_laporan_polisi extends CI_Model
{

    public function get_unique_pic()
    {
        $this->db->select('pic_laporan_polisi'); // PASTIKAN kolom ini ada di tabel
        $this->db->distinct();
        $this->db->from('laporan_polisi'); // PASTIKAN nama tabel ini benar
        $this->db->where('pic_laporan_polisi !=', '');
        $this->db->order_by('pic_laporan_polisi', 'ASC');

        $query = $this->db->get();

        // Cek apakah query berhasil (tidak false)
        if ($query) {
            return $query->result();
        } else {
            // Jika gagal, kembalikan array kosong agar tidak error di Controller
            return [];
        }
    }

    // Fungsi untuk Query Utama DataTables & Export
    public function _get_datatables_query($filter)
    {
        $this->db->from('laporan_polisi');

        if (!empty($filter['tahun'])) {
            $this->db->where('YEAR(tgl_laporan_polisi)', $filter['tahun']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('status_laporan_polisi', $filter['status']);
        }
        if (!empty($filter['pic'])) {
            $this->db->where('pic_laporan_polisi', $filter['pic']);
        }
        if (!empty($filter['nomor'])) {
            $this->db->group_start();
            $this->db->like('nomor_polisi', $filter['nomor']);
            $this->db->or_like('judul_laporan_polisi', $filter['nomor']);
            $this->db->group_end();
        }
    }

    public function get_datatables($filter)
    {
        $this->_get_datatables_query($filter);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered($filter)
    {
        $this->_get_datatables_query($filter);
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all_results('laporan_polisi');
    }

    // Fungsi untuk Export Excel
    public function get_report_data($filter)
    {
        $this->_get_datatables_query($filter);
        return $this->db->get()->result();
    }
}
