<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_arsip_sso extends CI_Model
{

    /**
     * 1. GET LIST DATA RINGKAS (Untuk Tabel/Pencarian Utama)
     */
    public function get_combined_arsip_sso($limit, $start, $keyword = null)
    {
        $search = $this->db->escape_like_str($keyword);

        // A. Query ASING (db_perkara.t_perkara)
        $this->db->select("
            'ASING' as sumber, 
            p.perkara_id as id_data, 
            p.perkara_no as nomor, 
            p.perkara_penggugat as nama_pihak, 
            p.perkara_alamat as lokasi, 
            p.penyimpanan_rak as id_rak
        ", FALSE);
        $this->db->from('db_perkara.t_perkara p');
        if ($keyword) {
            $this->db->group_start()
                ->like('p.perkara_no', $search)
                ->or_like('p.perkara_penggugat', $search)
                ->or_like('p.penyimpanan_rak', $search)
                ->group_end();
        }
        $q1 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // B. Query NONLIT
        $this->db->select("
            'NONLIT' as sumber, 
            n.id as id_data, 
            n.register_baru as nomor, 
            n.permohonan_nonlit as nama_pihak, 
            n.alamat as lokasi, 
            n.penyimpanan_rak as id_rak
        ", FALSE);
        $this->db->from('nonlits n');
        if ($keyword) {
            $this->db->group_start()
                ->like('n.register_baru', $search)
                ->or_like('n.permohonan_nonlit', $search)
                ->or_like('n.penyimpanan_rak', $search)
                ->group_end();
        }
        $q2 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // C. Query LAPORAN POLISI
        $this->db->select("
            'POLISI' as sumber, 
            lp.id_laporan_polisi as id_data, 
            lp.nomor_polisi as nomor, 
            lp.pelapor as nama_pihak, 
            lp.alamat_laporan_polisi as lokasi, 
            lp.penyimpanan_rak as id_rak
        ", FALSE);
        $this->db->from('laporan_polisi lp');
        if ($keyword) {
            $this->db->group_start()
                ->like('lp.nomor_polisi', $search)
                ->or_like('lp.pelapor', $search)
                ->or_like('lp.penyimpanan_rak', $search)
                ->group_end();
        }
        $q3 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // D. Query MASALAH
        $this->db->select("
            'MASALAH' as sumber, 
            m.id_masalah as id_data, 
            '-' as nomor, 
            m.nama_masalah as nama_pihak, 
            m.alamat_masalah as lokasi, 
            m.penyimpanan_rak as id_rak
        ", FALSE);
        $this->db->from('masalah m');
        if ($keyword) {
            $this->db->group_start()
                ->like('m.nama_masalah', $search)
                ->or_like('m.penyimpanan_rak', $search)
                ->group_end();
        }
        $q4 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // E. Query BERKAS UMUM
        $this->db->select("
            'UMUM' as sumber, 
            id_berkas_umum as id_data, 
            '-' as nomor, 
            nama_berkas_umum as nama_pihak, 
            keterangan as lokasi, 
            penyimpanan_rak as id_rak
        ", FALSE);
        $this->db->from('berkas_umum');
        if ($keyword) {
            $this->db->group_start()
                ->like('nama_berkas_umum', $search)
                ->or_like('penyimpanan_rak', $search)
                ->group_end();
        }
        $q5 = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Union All Execution
        $sql = "SELECT * FROM (
                    ($q1) UNION ALL ($q2) UNION ALL ($q3) UNION ALL ($q4) UNION ALL ($q5)
                ) as gabungan 
                ORDER BY id_data DESC 
                LIMIT " . (int)$start . ", " . (int)$limit;

        $res = $this->db->query($sql);
        return ($res) ? $res->result() : [];
    }

    /**
     * TOTAL ROWS UNTUK PAGINATION LIST
     */
    public function count_all_combined_sso($keyword = null)
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

    /**
     * 2. GET DETAIL DATA & DAFTAR LAMPIRAN BERKAS (Untuk Pop-up Modal Detail)
     */
    /**
     * GET DETAIL DATA & DAFTAR LAMPIRAN BERKAS (Lengkap dengan Riwayat Perkara)
     */
    public function get_detail_arsip($sumber, $id)
    {
        if ($sumber === 'ASING') {
            // 1. Data Utama Perkara
            $perkara = $this->db->select("
                'ASING' as sumber,
                p.perkara_id as id_data,
                p.perkara_no as nomor,
                p.perkara_penggugat as nama_pihak,
                p.perkara_tergugat as tergugat,
                p.perkara_pihak as para_pihak,
                p.perkara_jenis as jenis_perkara,
                p.perkara_alamat as lokasi,
                p.penyimpanan_rak as id_rak,
                p.perkara_status as status_perkara
            ", FALSE)
                ->from('db_perkara.t_perkara p')
                ->where('p.perkara_id', $id)
                ->get()->row();

            if (!$perkara) return null;

            // 2. Ambil SELURUH Riwayat Tingkat Putusan (PN, Banding, Kasasi, PK, dll)
            $riwayat = $this->db->select("
                perkaradet_id as id_detail,
                perkaradet_no as nomor_perkara_tingkat,
                perkaradet_tingkat as tingkat_proses,
                perkaradet_status as status_putusan,
                perkaradet_tgl_putusan as tgl_putusan,
                perkaradet_keterangan as amar_putusan,
                perkaradet_pihak as pihak_terkait,
                perkaradet_inkrah as status_inkrah
            ")
                ->from('db_perkara.t_perkara_detail')
                ->where('perkaradet_perkara_id', $id)
                ->order_by('perkaradet_id', 'ASC')
                ->get()->result();

            $perkara->riwayat_perkara = $riwayat;

            // 3. Ambil SELURUH Berkas Lampiran dari db_perkara.t_upload
            $files = $this->db->select("
                id_berkas,
                name_berkas as nama_file,
                type_file,
                size as ukuran_file,
                CONCAT('" . base_url('uploads/asing/') . "', name_berkas) as file_url
            ")
                ->from('db_perkara.t_upload')
                ->where('berkas_perkara_id', $id)
                ->order_by('id_berkas', 'DESC')
                ->get()->result();

            $perkara->lampiran_berkas = $files;
            return $perkara;
        } else if (in_array($sumber, ['NONLIT', 'POLISI', 'MASALAH'])) {
            // Data Utama Nonlit
            $nonlit = $this->db->select("
                '$sumber' as sumber,
                id as id_data,
                register_baru as nomor,
                permohonan_nonlit as nama_pihak,
                alamat as lokasi,
                penyimpanan_rak as id_rak,
                keterangan as amar_putusan,
                status as status_terakhir,
                tgl_nonlit as tgl_putusan
            ")
                ->from('nonlits')
                ->where('id', $id)
                ->get()->row();

            if (!$nonlit) return null;

            // Karena Nonlit tidak memiliki tabel detail berjenjang seperti perkara,
            // kita buatkan riwayat_perkara versi default/tunggal agar format JSON tetap konsisten
            $nonlit->riwayat_perkara = [
                [
                    'id_detail' => $nonlit->id_data,
                    'nomor_perkara_tingkat' => $nonlit->nomor,
                    'tingkat_proses' => $sumber,
                    'status_putusan' => $nonlit->status_terakhir,
                    'tgl_putusan' => $nonlit->tgl_putusan,
                    'amar_putusan' => $nonlit->amar_putusan,
                    'pihak_terkait' => $nonlit->nama_pihak,
                    'status_inkrah' => null
                ]
            ];

            // Seluruh Lampiran Berkas dari berkas_lampiran
            $files = $this->db->select("
                id,
                nama_berkas as nama_file,
                file_type as type_file,
                NULL as ukuran_file,
                CONCAT('" . base_url('uploads/nonlit/') . "', nama_berkas) as file_url
            ")
                ->from('berkas_lampiran')
                ->where('id_nonlit', $id)
                ->order_by('id', 'DESC')
                ->get()->result();

            $nonlit->lampiran_berkas = $files;
            return $nonlit;
        } else if ($sumber === 'UMUM') {
            $umum = $this->db->select("
                'UMUM' as sumber,
                id_berkas_umum as id_data,
                '-' as nomor,
                nama_berkas_umum as nama_pihak,
                keterangan as lokasi,
                penyimpanan_rak as id_rak,
                NULL as amar_putusan,
                NULL as status_terakhir,
                NULL as tgl_putusan
            ")
                ->from('berkas_umum')
                ->where('id_berkas_umum', $id)
                ->get()->row();

            if ($umum) {
                $umum->riwayat_perkara = [];
                $umum->lampiran_berkas = [];
            }
            return $umum;
        }

        return null;
    }
}
