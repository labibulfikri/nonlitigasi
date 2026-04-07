<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_arsip extends CI_Model
{

    public function get_combined_arsip($limit, $start, $keyword = null)
    {
        $search = $this->db->escape_like_str($keyword);

        // 1. Query ASING (db_perkara)
        $this->db->select("'ASING' as sumber, perkara_id as id_data, perkara_no as nomor, perkara_penggugat as nama_pihak, perkara_alamat as lokasi, penyimpanan_rak as id_rak", FALSE);
        $this->db->from('db_perkara.t_perkara');
        if ($keyword) {
            $this->db->group_start()
                ->like('perkara_no', $search)->or_like('perkara_penggugat', $search)->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q1 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // 2. Query NONLIT
        $this->db->select("'NONLIT' as sumber, id as id_data, register_baru as nomor, permohonan_nonlit as nama_pihak, alamat as lokasi, penyimpanan_rak as id_rak", FALSE);
        $this->db->from('nonlits');
        if ($keyword) {
            $this->db->group_start()
                ->like('register_baru', $search)->or_like('permohonan_nonlit', $search)->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q2 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // 3. Query LAPORAN POLISI (Disesuaikan tabel Anda)
        $this->db->select("'POLISI' as sumber, id_laporan_polisi as id_data, nomor_polisi as nomor, pelapor as nama_pihak, alamat_laporan_polisi as lokasi, penyimpanan_rak as id_rak", FALSE);
        $this->db->from('laporan_polisi');
        if ($keyword) {
            $this->db->group_start()
                ->like('nomor_polisi', $search)->or_like('pelapor', $search)->or_like('judul_laporan_polisi', $search)->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q3 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // 4. Query MASALAH (Disesuaikan tabel Anda)
        $this->db->select("'MASALAH' as sumber, id_masalah as id_data, '-' as nomor, nama_masalah as nama_pihak, alamat_masalah as lokasi, penyimpanan_rak as id_rak", FALSE);
        $this->db->from('masalah');
        if ($keyword) {
            $this->db->group_start()
                ->like('nama_masalah', $search)->or_like('alamat_masalah', $search)->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q4 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // 5. Query BERKAS UMUM
        $this->db->select("'UMUM' as sumber, id_berkas_umum as id_data, '-' as nomor, nama_berkas_umum as nama_pihak, keterangan as lokasi, penyimpanan_rak as id_rak", FALSE);
        $this->db->from('berkas_umum');
        if ($keyword) {
            $this->db->group_start()
                ->like('nama_berkas_umum', $search)->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q5 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Gabungkan Semua dengan UNION ALL
        $sql = "SELECT * FROM (
                    ($q1) UNION ALL ($q2) UNION ALL ($q3) UNION ALL ($q4) UNION ALL ($q5)
                ) as gabungan 
                ORDER BY id_data DESC 
                LIMIT " . (int)$start . ", " . (int)$limit;

        $res = $this->db->query($sql);
        return ($res) ? $res->result() : [];
    }

    public function count_all_combined($keyword = null)
    {
        $search = $this->db->escape_like_str($keyword);

        $w1 = $keyword ? "WHERE perkara_no LIKE '%$search%' OR perkara_penggugat LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";
        $w2 = $keyword ? "WHERE register_baru LIKE '%$search%' OR permohonan_nonlit LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";
        $w3 = $keyword ? "WHERE nomor_polisi LIKE '%$search%' OR pelapor LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";
        $w4 = $keyword ? "WHERE nama_masalah LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";
        $w5 = $keyword ? "WHERE nama_berkas_umum LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";

        $sql = "SELECT (
            (SELECT COUNT(*) FROM db_perkara.t_perkara $w1) +
            (SELECT COUNT(*) FROM nonlits $w2) +
            (SELECT COUNT(*) FROM laporan_polisi $w3) +
            (SELECT COUNT(*) FROM masalah $w4) +
            (SELECT COUNT(*) FROM berkas_umum $w5)
        ) as total";

        $query = $this->db->query($sql);
        return ($query) ? $query->row()->total : 0;
    }
    // public function get_combined_arsip($limit, $start, $keyword = null)
    // {
    //     // 1. Query Tabel ASING (db_perkara)
    //     // Kita ambil 'perkara_penggugat' karena 'perkara_pihak' di DB kamu NULL
    //     $this->db->select("
    //     'ASING' as sumber, 
    //     perkara_id as id_data, 
    //     perkara_no as nomor, 
    //     perkara_penggugat as nama_pihak, 
    //     perkara_alamat as lokasi, 
    //     penyimpanan_rak as id_rak
    // ", FALSE);
    //     $this->db->from('db_perkara.t_perkara');

    //     if ($keyword) {
    //         $this->db->group_start();
    //         $this->db->like('perkara_no', $keyword);
    //         $this->db->or_like('perkara_penggugat', $keyword); // Cari berdasarkan penggugat
    //         $this->db->or_like('perkara_tergugat', $keyword);  // Cari berdasarkan tergugat
    //         $this->db->or_like('penyimpanan_rak', $keyword);
    //         $this->db->group_end();
    //     }
    //     $query1 = $this->db->get_compiled_select();
    //     $this->db->reset_query();

    //     // 2. Query Tabel NONLIT (Tetap seperti sebelumnya)
    //     $this->db->select("
    //     'NONLIT' as sumber, 
    //     id as id_data, 
    //     register_baru as nomor, 
    //     permohonan_nonlit as nama_pihak, 
    //     alamat as lokasi, 
    //     penyimpanan_rak as id_rak
    // ", FALSE);
    //     $this->db->from('nonlits');

    //     if ($keyword) {
    //         $this->db->group_start();
    //         $this->db->like('register_baru', $keyword);
    //         $this->db->or_like('permohonan_nonlit', $keyword);
    //         $this->db->or_like('penyimpanan_rak', $keyword);
    //         $this->db->group_end();
    //     }
    //     $query2 = $this->db->get_compiled_select();

    //     // 3. Gabungkan
    //     $sql = "(" . trim($query1) . ") UNION ALL (" . trim($query2) . ") 
    //         ORDER BY id_data DESC 
    //         LIMIT " . (int)$start . ", " . (int)$limit;

    //     $result = $this->db->query($sql);
    //     return ($result) ? $result->result() : [];
    // }
    // // 2. Fungsi yang Error/Hilang tadi: Hitung Total Baris
    // public function count_all_combined($keyword = null)
    // {
    //     $search = $this->db->escape_like_str($keyword);
    //     $where_asing = $keyword ? "WHERE perkara_no LIKE '%$search%' OR perkara_pihak LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";
    //     $where_nonlit = $keyword ? "WHERE register_baru LIKE '%$search%' OR permohonan_nonlit LIKE '%$search%' OR penyimpanan_rak LIKE '%$search%'" : "";

    //     $sql = "SELECT COUNT(*) as total FROM (
    //                 (SELECT perkara_id FROM db_perkara.t_perkara $where_asing)
    //                 UNION ALL
    //                 (SELECT id FROM nonlits $where_nonlit)
    //             ) as gabungan";

    //     $res = $this->db->query($sql);
    //     return ($res) ? $res->row()->total : 0;
    // }

    // 3. Update Lokasi Rak
    public function update_penyimpanan($sumber, $id_data, $id_rak)
    {
        if ($sumber === 'ASING') {
            $this->db->where('perkara_id', $id_data);
            return $this->db->update('db_perkara.t_perkara', ['penyimpanan_rak' => $id_rak]);
        } else {
            $this->db->where('id', $id_data);
            return $this->db->update('nonlits', ['penyimpanan_rak' => $id_rak]);
        }
    }

    // public function get_detail_berkas($sumber, $id_data)
    // {
    //     if ($sumber === 'ASING') {
    //         $data = $this->db->where('perkara_id', $id_data)->get('db_perkara.t_perkara')->row();
    //         $lampiran = $this->db->where('berkas_perkara_id', $id_data)->get('db_perkara.t_upload')->result();

    //         // MENGGUNAKAN result() karena putusan bisa lebih dari satu
    //         $detail = $this->db->where('perkaradet_perkara_id', $id_data)
    //             ->order_by('perkaradet_id', 'ASC')
    //             ->get('db_perkara.t_perkara_detail')
    //             ->result();

    //         return [
    //             'data' => $data,
    //             'lampiran' => $lampiran,
    //             'detail_tambahan' => $detail // Ini sekarang berupa array of objects
    //         ];
    //     } else {
    //         $data = $this->db->where('id', $id_data)->get('nonlits')->row();
    //         $lampiran = $this->db->where('id_nonlit', $id_data)->get('berkas_lampiran')->result();

    //         // MENGGUNAKAN result() karena rapat bisa lebih dari satu kali
    //         $detail = $this->db->where('id_nonlit', $id_data)
    //             ->order_by('tgl_rapat', 'DESC')
    //             ->get('nonlit_det')
    //             ->result();

    //         return [
    //             'data' => $data,
    //             'lampiran' => $lampiran,
    //             'detail_tambahan' => $detail // Ini sekarang berupa array of objects
    //         ];
    //     }
    // }

    public function get_detail_berkas($sumber, $id_data)
    {
        $data     = null;
        $lampiran = [];
        $detail   = [];

        if ($sumber === 'ASING') {
            // Asumsi tabel di db_perkara tetap seperti sebelumnya
            $data     = $this->db->where('perkara_id', $id_data)->get('db_perkara.t_perkara')->row();
            $lampiran = $this->db->where('berkas_perkara_id', $id_data)->get('db_perkara.t_upload')->result();
            $detail   = $this->db->where('perkaradet_perkara_id', $id_data)->order_by('perkaradet_id', 'ASC')->get('db_perkara.t_perkara_detail')->result();
        } else if ($sumber === 'NONLIT') {
            // Sesuai TABLE `nonlits` (PK: id)
            $data     = $this->db->where('id', $id_data)->get('nonlits')->row();
            // Sesuai TABLE `berkas_lampiran` (FK: id_nonlit)
            $lampiran = $this->db->where('id_nonlit', $id_data)->get('berkas_lampiran')->result();
            // Sesuai TABLE `nonlit_det` (FK: id_nonlit)
            $detail   = $this->db->where('id_nonlit', $id_data)->order_by('tgl_rapat', 'DESC')->get('nonlit_det')->result();
        } else if ($sumber === 'POLISI') {
            // Sesuai TABLE `laporan_polisi` (PK: id_laporan_polisi)
            $data     = $this->db->where('id_laporan_polisi', $id_data)->get('laporan_polisi')->row();
            // Sesuai TABLE `laporan_polisi_det` (FK: id_laporan_polisi)
            $lampiran = $this->db->where('id_laporan_polisi', $id_data)->get('laporan_polisi_det')->result();
        } else if ($sumber === 'UMUM') {
            // Sesuai TABLE `berkas_umum` (PK: id_berkas_umum)
            $data     = $this->db->where('id_berkas_umum', $id_data)->get('berkas_umum')->row();
            // Sesuai TABLE `berkas_umum_det` (FK: id_berkas_umum)
            $lampiran = $this->db->where('id_berkas_umum', $id_data)->get('berkas_umum_det')->result();
        }

        return [
            'status'          => ($data ? true : false),
            'data'            => $data,
            'lampiran'        => $lampiran,
            'detail_tambahan' => $detail
        ];
    }
    public function get_detail_berkas2($sumber, $id_data)
    {
        if ($sumber === 'ASING') {
            $data     = $this->db->where('perkara_id', $id_data)->get('db_perkara.t_perkara')->row();
            $lampiran = $this->db->where('berkas_perkara_id', $id_data)->get('db_perkara.t_upload')->result();
            $detail   = $this->db->where('perkaradet_perkara_id', $id_data)->order_by('perkaradet_id', 'ASC')->get('db_perkara.t_perkara_detail')->result();
        } else if ($sumber === 'NONLIT') {
            $data     = $this->db->where('id', $id_data)->get('nonlits')->row();
            $lampiran = $this->db->where('id_nonlit', $id_data)->get('berkas_lampiran')->result();
            $detail   = $this->db->where('id_nonlit', $id_data)->order_by('tgl_rapat', 'DESC')->get('nonlit_det')->result();
        } else if ($sumber === 'POLISI') {
            $data     = $this->db->where('id_laporan_polisi', $id_data)->get('laporan_polisi')->row();
            // Pastikan nama tabel detail lampiran polisi benar, contoh: laporan_polisi_det
            $lampiran = $this->db->where('id_laporan_polisi', $id_data)->get('laporan_polisi_det')->result();
            $detail   = []; // Kosongkan jika belum ada tabel detail kronologi polisi
        } else if ($sumber === 'MASALAH') {
            $data     = $this->db->where('id_masalah', $id_data)->get('masalah')->row();
            // Pastikan nama tabel detail lampiran masalah benar, contoh: masalah_det
            $lampiran = $this->db->where('id_masalah', $id_data)->get('masalah_det')->result();
            $detail   = []; // Kosongkan jika belum ada tabel detail kronologi masalah
        } else if ($sumber === 'UMUM') {
            $data     = $this->db->where('id_berkas_umum', $id_data)->get('berkas_umum')->row();
            $lampiran = $this->db->where('id_berkas_umum', $id_data)->get('berkas_umum_det')->result();
            $detail   = [];
        }

        return [
            'data'            => $data,
            'lampiran'        => $lampiran,
            'detail_tambahan' => $detail
        ];
    }
}
