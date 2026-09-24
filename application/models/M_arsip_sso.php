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
    // public function get_detail_arsip($sumber, $id)
    // {
    //     if ($sumber === 'ASING') {
    //         // 1. Data Utama Perkara
    //         $perkara = $this->db->select("
    //             'ASING' as sumber,
    //             p.perkara_id as id_data,
    //             p.perkara_no as nomor,
    //             p.perkara_penggugat as nama_pihak,
    //             p.perkara_tergugat as tergugat,
    //             p.perkara_pihak as para_pihak,
    //             p.perkara_jenis as jenis_perkara,
    //             p.perkara_alamat as lokasi,
    //             p.penyimpanan_rak as id_rak,
    //             p.perkara_status as status_perkara
    //         ", FALSE)
    //             ->from('db_perkara.t_perkara p')
    //             ->where('p.perkara_id', $id)
    //             ->get()->row();

    //         if (!$perkara) return null;

    //         // 2. Ambil SELURUH Riwayat Tingkat Putusan (PN, Banding, Kasasi, PK, dll)
    //         $riwayat = $this->db->select("
    //             perkaradet_id as id_detail,
    //             perkaradet_no as nomor_perkara_tingkat,
    //             perkaradet_tingkat as tingkat_proses,
    //             perkaradet_status as status_putusan,
    //             perkaradet_tgl_putusan as tgl_putusan,
    //             perkaradet_keterangan as amar_putusan,
    //             perkaradet_pihak as pihak_terkait,
    //             perkaradet_inkrah as status_inkrah
    //         ")
    //             ->from('db_perkara.t_perkara_detail')
    //             ->where('perkaradet_perkara_id', $id)
    //             ->order_by('perkaradet_id', 'ASC')
    //             ->get()->result();

    //         $perkara->riwayat_perkara = $riwayat;

    //         // 3. Ambil SELURUH Berkas Lampiran dari db_perkara.t_upload
    //         $files = $this->db->select("
    //             id_berkas,
    //             name_berkas as nama_file,
    //             type_file,
    //             size as ukuran_file,
    //             CONCAT('" . base_url('uploads/asing/') . "', name_berkas) as file_url
    //         ")
    //             ->from('db_perkara.t_upload')
    //             ->where('berkas_perkara_id', $id)
    //             ->order_by('id_berkas', 'DESC')
    //             ->get()->result();

    //         $perkara->lampiran_berkas = $files;
    //         return $perkara;
    //     } else if (in_array($sumber, ['NONLIT', 'POLISI', 'MASALAH', 'UMUM'])) {
    //         // Data Utama Nonlit
    //         $nonlit = $this->db->select("
    //             '$sumber' as sumber,
    //             id as id_data,
    //             register_baru as nomor,
    //             permohonan_nonlit as nama_pihak,
    //             alamat as lokasi,
    //             penyimpanan_rak as id_rak,
    //             keterangan as amar_putusan,
    //             status as status_terakhir,
    //             tgl_nonlit as tgl_putusan
    //         ")
    //             ->from('nonlits')
    //             ->where('id', $id)
    //             ->get()->row();

    //         if (!$nonlit) return null;

    //         // Karena Nonlit tidak memiliki tabel detail berjenjang seperti perkara,
    //         // kita buatkan riwayat_perkara versi default/tunggal agar format JSON tetap konsisten
    //         $nonlit->riwayat_perkara = [
    //             [
    //                 'id_detail' => $nonlit->id_data,
    //                 'nomor_perkara_tingkat' => $nonlit->nomor,
    //                 'tingkat_proses' => $sumber,
    //                 'status_putusan' => $nonlit->status_terakhir,
    //                 'tgl_putusan' => $nonlit->tgl_putusan,
    //                 'amar_putusan' => $nonlit->amar_putusan,
    //                 'pihak_terkait' => $nonlit->nama_pihak,
    //                 'status_inkrah' => null
    //             ]
    //         ];

    //         // Seluruh Lampiran Berkas dari berkas_lampiran
    //         $files = $this->db->select("
    //             id,
    //             nama_berkas as nama_file,
    //             file_type as type_file,
    //             NULL as ukuran_file,
    //             CONCAT('" . base_url('uploads/nonlit/') . "', nama_berkas) as file_url
    //         ")
    //             ->from('berkas_lampiran')
    //             ->where('id_nonlit', $id)
    //             ->order_by('id', 'DESC')
    //             ->get()->result();

    //         $nonlit->lampiran_berkas = $files;
    //         return $nonlit;
    //     }
    //     // else if ($sumber === 'UMUM') {
    //     //     $umum = $this->db->select("
    //     //         'UMUM' as sumber,
    //     //         id_berkas_umum as id_data,
    //     //         '-' as nomor,
    //     //         nama_berkas_umum as nama_pihak,
    //     //         keterangan as lokasi,
    //     //         penyimpanan_rak as id_rak,
    //     //         NULL as amar_putusan,
    //     //         NULL as status_terakhir,
    //     //         NULL as tgl_putusan
    //     //     ")
    //     //         ->from('berkas_umum')
    //     //         ->where('id_berkas_umum', $id)
    //     //         ->get()->row();

    //     //     if ($umum) {
    //     //         $umum->riwayat_perkara = [];
    //     //         $umum->lampiran_berkas = [];
    //     //     }
    //     //     return $umum;
    //     // }

    //     return null;
    // }
    public function get_detail_arsip($sumber, $id)
    {
        $sumber_lower = strtolower($sumber);

        if ($sumber_lower === 'asing') {
            // 1. Data Utama Perkara ASING
            $q_perkara = $this->db->select("
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
                ->get();

            if (!$q_perkara || $q_perkara->num_rows() === 0) return null;
            $perkara = $q_perkara->row();

            // 2. Riwayat Tingkat Putusan
            $q_riwayat = $this->db->select("
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
                ->get();

            $perkara->riwayat_perkara = ($q_riwayat && is_object($q_riwayat)) ? $q_riwayat->result() : [];

            // 3. Lampiran Berkas ASING
            $q_files = $this->db->select("
            id_berkas as id,
            name_berkas as nama_file,
            type_file,
            size as ukuran_file,
            CONCAT('https://assistdpbt.surabaya.go.id/asing/assets/upload/', name_berkas) as file_url
        ")
                ->from('db_perkara.t_upload')
                ->where('berkas_perkara_id', $id)
                ->order_by('id_berkas', 'DESC')
                ->get();

            $perkara->lampiran_berkas = ($q_files && is_object($q_files)) ? $q_files->result() : [];
            return $perkara;
        } else if (in_array($sumber_lower, ['nonlit', 'polisi', 'laporan_polisi', 'masalah', 'permasalahan', 'umum', 'data_umum'])) {

            // 1. Data Utama dari Tabel nonlits
            $q_nonlit = $this->db->select("
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
                ->get();

            if (!$q_nonlit || $q_nonlit->num_rows() === 0) return null;
            $nonlit = $q_nonlit->row();

            // 2. Riwayat Perkara Default
            $nonlit->riwayat_perkara = [
                [
                    'id_detail'             => $nonlit->id_data,
                    'nomor_perkara_tingkat' => $nonlit->nomor,
                    'tingkat_proses'        => strtoupper($sumber),
                    'status_putusan'        => $nonlit->status_terakhir,
                    'tgl_putusan'           => $nonlit->tgl_putusan,
                    'amar_putusan'          => $nonlit->amar_putusan,
                    'pihak_terkait'         => $nonlit->nama_pihak,
                    'status_inkrah'         => null
                ]
            ];

            // 3. Berkas Gabungan dari nonlit_det dan berkas_lampiran
            $sql_files = "
            SELECT 
                id,
                berkas AS nama_file,
                'pdf' AS type_file,
                NULL AS ukuran_file,
                CONCAT('https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_nonlit/', berkas) AS file_url
            FROM nonlit_det
            WHERE id_nonlit = ? AND berkas IS NOT NULL AND berkas != ''
            
            UNION ALL
            
            SELECT 
                id,
                nama_berkas AS nama_file,
                file_type AS type_file,
                NULL AS ukuran_file,
                CONCAT('https://assistdpbt.surabaya.go.id/nonlitigasi/assets/berkas_lampiran/', nama_berkas) AS file_url
            FROM berkas_lampiran
            WHERE id_nonlit = ? AND nama_berkas IS NOT NULL AND nama_berkas != ''
            ORDER BY id DESC
        ";

            $q_files = $this->db->query($sql_files, array($id, $id));
            $nonlit->lampiran_berkas = ($q_files && is_object($q_files)) ? $q_files->result() : [];

            return $nonlit;
        }

        return null;
    }



    public function get_progres_scan($tanggal_pilihan = null)
    {
        $tgl_target = $tanggal_pilihan ? $tanggal_pilihan : date('Y-m-d');

        // 1. Hitung Aktivitas Scan Hari Ini per Kategori (HANYA CREATE & UPDATE)
        $q_nonlit_today = $this->db->select("COUNT(id) as total_aksi")
            ->from('activity_logs')
            ->where_in('module', ['nonlit_det', 'berkas_lampiran'])
            ->where_in('action', ['CREATE', 'UPDATE'])
            ->where('DATE(created_at)', $tgl_target)
            ->get();
        $total_nonlit_today = ($q_nonlit_today && is_object($q_nonlit_today)) ? $q_nonlit_today->row()->total_aksi : 0;

        $q_asing_today = $this->db->select("COUNT(id) as total_aksi")
            ->from('activity_logs')
            ->where_in('module', ['t_upload', 't_perkara_detail'])
            ->where_in('action', ['CREATE', 'UPDATE'])
            ->where('DATE(created_at)', $tgl_target)
            ->get();
        $total_asing_today = ($q_asing_today && is_object($q_asing_today)) ? $q_asing_today->row()->total_aksi : 0;

        // 2. Total Akumulasi Terscan di Database
        $q_nonlit_total = $this->db->query("
            SELECT COUNT(id) as total FROM (
                SELECT id FROM nonlit_det WHERE berkas IS NOT NULL AND berkas != ''
                UNION ALL
                SELECT id FROM berkas_lampiran WHERE nama_berkas IS NOT NULL AND nama_berkas != ''
            ) as total_files
        ");
        $total_nonlit_acc = ($q_nonlit_total && is_object($q_nonlit_total)) ? $q_nonlit_total->row()->total : 0;

        $q_asing_total = $this->db->query("
            SELECT COUNT(id_berkas) as total 
            FROM db_perkara.t_upload 
            WHERE name_berkas IS NOT NULL AND name_berkas != ''
        ");
        $total_asing_acc = ($q_asing_total && is_object($q_asing_total)) ? $q_asing_total->row()->total : 0;

        // 3. Breakdown SELURUH USER untuk Non-Litigasi (Hanya menghitung CREATE & UPDATE)
        $sql_user_nonlit = "
            SELECT 
                u.id as user_id,
                u.username,
                u.role,
                COALESCE(act.scan_today, 0) as scan_today,
                COALESCE(act.scan_total, 0) as scan_total
            FROM users u
            LEFT JOIN (
                SELECT 
                    user_id,
                    SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as scan_today,
                    COUNT(id) as scan_total
                FROM activity_logs 
                WHERE module IN ('nonlit_det', 'berkas_lampiran') 
                  AND action IN ('CREATE', 'UPDATE')
                  AND user_id IS NOT NULL
                GROUP BY user_id
            ) act ON act.user_id = u.id
            ORDER BY scan_today DESC, scan_total DESC, u.username ASC
        ";
        $q_user_nonlit = $this->db->query($sql_user_nonlit, array($tgl_target));
        $user_nonlit = ($q_user_nonlit && is_object($q_user_nonlit)) ? $q_user_nonlit->result() : [];

        // 4. Breakdown SELURUH USER untuk Litigasi ASING (Hanya menghitung CREATE & UPDATE)
        $sql_user_asing = "
            SELECT 
                u.id as user_id,
                u.username,
                u.role,
                COALESCE(act.scan_today, 0) as scan_today,
                COALESCE(act.scan_total, 0) as scan_total
            FROM users u
            LEFT JOIN (
                SELECT 
                    user_id,
                    SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as scan_today,
                    COUNT(id) as scan_total
                FROM activity_logs 
                WHERE module IN ('t_upload', 't_perkara_detail') 
                  AND action IN ('CREATE', 'UPDATE')
                  AND user_id IS NOT NULL
                GROUP BY user_id
            ) act ON act.user_id = u.id
            ORDER BY scan_today DESC, scan_total DESC, u.username ASC
        ";
        $q_user_asing = $this->db->query($sql_user_asing, array($tgl_target));
        $user_asing = ($q_user_asing && is_object($q_user_asing)) ? $q_user_asing->result() : [];

        return (object) [
            'tgl_laporan'     => $tgl_target,
            'nonlit_today'    => (int)$total_nonlit_today,
            'asing_today'     => (int)$total_asing_today,
            'total_today'     => (int)($total_nonlit_today + $total_asing_today),
            'nonlit_total'    => (int)$total_nonlit_acc,
            'asing_total'     => (int)$total_asing_acc,
            'grand_total'     => (int)($total_nonlit_acc + $total_asing_acc),
            'user_nonlit'     => $user_nonlit,
            'user_asing'      => $user_asing
        ];
    }
}
