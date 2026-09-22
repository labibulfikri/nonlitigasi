<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    // function make_query($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit)
    // {

    //     $table = "nonlits";
    //     $select_column = "
    //      nonlits.id, permohonan_nonlit, updated_by,tgl_nonlit, penyimpanan_rak, team_nonlit,status, keterangan, bidang, status, register_baru, luas, pic, username,alamat,updated_by, updated_at";
    //     $this->db->select($select_column);
    //     $this->db->join('users', 'users.id = nonlits.updated_by', 'left');

    //     if ($tahun != null || $tahun != "") {
    //         $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
    //     }

    //     if ($status != null || $status != "") {
    //         $this->db->where('status', $status);
    //     }
    //     if ($bidang != null || $bidang != "") {
    //         $this->db->where('bidang', $bidang);
    //     }
    //     if ($pic != null || $pic != "") {
    //         $this->db->where('pic', $pic);
    //     }
    //     if ($permohonan_nonlit != null || $permohonan_nonlit != "") {
    //         $this->db->where('permohonan_nonlit', $permohonan_nonlit);
    //     }
    //     if ($team != null || $team != "") {
    //         $this->db->where('team_nonlit', $team);
    //     }

    //     $this->db->from($table);

    //     $i = 0;
    //     $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit', 'register_baru', 'luas', 'pic', 'alamat');
    //     foreach ($column_search as $item) // loop column 
    //     {
    //         if (@$_POST['search']['value']) // if datatable send POST for search
    //         {

    //             if ($i === 0) // first loop
    //             {
    //                 $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
    //                 $this->db->group_by('id');
    //                 // $this->db->group_by('m_aset_baru.id_aset');

    //                 $this->db->order_by('id', 'asc');
    //                 $this->db->like($item, $_POST['search']['value']);
    //             } else {

    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }

    //             if (count($column_search) - 1 == $i) //last loop 
    //                 $this->db->group_end(); //close bracket
    //         }
    //         $i++;
    //     }
    // }

    function make_datatables($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit)
    {

        $this->make_query($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit);

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    // function get_filtered_data($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit)
    // {
    //     $this->make_query($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit);
    //     $i = 0;
    //     $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit', 'register_baru');
    //     foreach ($column_search as $item) // loop column 
    //     {
    //         if (@$_POST['search']['value']) // if datatable send POST for search
    //         {

    //             if ($i === 0) // first loop
    //             {
    //                 $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
    //                 $this->db->group_by('id');
    //                 // $this->db->group_by('m_aset_baru.id_aset');

    //                 $this->db->order_by('id', 'asc');
    //                 $this->db->like($item, $_POST['search']['value']);
    //             } else {

    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }

    //             if (count($column_search) - 1 == $i) //last loop 
    //                 $this->db->group_end(); //close bracket
    //         }
    //         $i++;
    //     }
    //     $query = $this->db->get();



    //     return $query->num_rows();
    // }
    // function get_all_data($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit)
    // {
    //     $this->make_query($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit);
    //     $query = $this->db->get();
    //     return $this->db->count_all_results();
    // }
    var $table = 'nonlits';
    var $column_order = array(null, 'permohonan_nonlit', 'pic', 'tgl_nonlit', 'jenis', 'status');
    var $column_search = array('permohonan_nonlit', 'pic', 'team_nonlit');
    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        // Filter Tahun
        // $tahun = $this->input->post('tahun', true);
        // if ($tahun && $tahun != 'all') {
        //     $this->db->where('YEAR(tgl_nonlit)', $tahun);
        // }

        // Filter Tahun (Multi-Select Support)
        $tahun = $this->input->post('tahun', true);
        if (!empty($tahun) && is_array($tahun)) {
            $this->db->where_in('YEAR(tgl_nonlit)', $tahun);
        } elseif (!empty($tahun) && $tahun != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }

        // Filter Status (Multi Select)
        $status = $this->input->post('status', true);
        if (!empty($status) && is_array($status)) {
            $this->db->where_in('status', $status);
        } elseif (!empty($status) && !is_array($status)) {
            $this->db->where('status', $status);
        }

        // Filter Team (Multi Select)
        $team = $this->input->post('team', true);
        if (!empty($team) && is_array($team)) {
            $this->db->where_in('team_nonlit', $team);
        } elseif (!empty($team) && !is_array($team)) {
            $this->db->where('team_nonlit', $team);
        }

        // Filter PIC (Multi Select)
        $pic = $this->input->post('pic', true);
        if (!empty($pic) && is_array($pic)) {
            $this->db->where_in('pic', $pic);
        } elseif (!empty($pic) && !is_array($pic)) {
            $this->db->where('pic', $pic);
        }

        // Filter Search Text
        $permohonan = $this->input->post('permohonan_nonlit', true);
        if ($permohonan) {
            $this->db->like('permohonan_nonlit', $permohonan);
        }

        // Sorting
        if (isset($_POST['order'])) {
            $columnIndex = $_POST['order']['0']['column'];
            $columnDir = $_POST['order']['0']['dir'];
            if (isset($this->column_order[$columnIndex])) {
                $this->db->order_by($this->column_order[$columnIndex], $columnDir);
            }
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();

        // Cek jika query gagal untuk menghindari Fatal Error
        if (!$query) {
            return array();
        }

        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }

    // PERBAIKAN UTAMA: Perhitungan summary langsung lewat agregasi SQL
    public function get_summary_counts()
    {
        $this->_get_datatables_query();

        $this->db->select("
        COUNT(*) as total,
        SUM(CASE WHEN LOWER(status) = 'proses' THEN 1 ELSE 0 END) as proses,
        SUM(CASE WHEN LOWER(status) = 'selesai' THEN 1 ELSE 0 END) as selesai
    ");

        $query = $this->db->get();

        if ($query && $row = $query->row()) {
            return [
                'total'   => (int) $row->total,
                'proses'  => (int) $row->proses,
                'selesai' => (int) $row->selesai
            ];
        }

        return ['total' => 0, 'proses' => 0, 'selesai' => 0];
    }

    public function get_list_pic()
    {
        $this->db->select('DISTINCT(pic) as nama_pic');
        $this->db->from('nonlits');
        $this->db->where('pic !=', '');
        $this->db->order_by('pic', 'ASC');
        return $this->db->get()->result();
    }


    public function get_log_stats($tanggal)
    {
        $q = $this->db->select("
            COUNT(id) as total_log,
            SUM(CASE WHEN action = 'CREATE' THEN 1 ELSE 0 END) as total_create,
            SUM(CASE WHEN action = 'UPDATE' THEN 1 ELSE 0 END) as total_update,
            SUM(CASE WHEN action = 'DELETE' THEN 1 ELSE 0 END) as total_delete,
            COUNT(DISTINCT user_id) as total_user_aktif
        ")
            ->from('activity_logs')
            ->where('DATE(created_at)', $tanggal)
            ->get();

        return ($q && is_object($q)) ? $q->row() : (object)[
            'total_log' => 0,
            'total_create' => 0,
            'total_update' => 0,
            'total_delete' => 0,
            'total_user_aktif' => 0
        ];
    }

    // Mengambil riwayat detail log aktivitas
    public function get_logs($tanggal, $user_id = null, $action = null, $module = null)
    {
        $this->db->select('a.*, u.username, u.role');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->where('DATE(a.created_at)', $tanggal);

        if (!empty($user_id)) {
            $this->db->where('a.user_id', $user_id);
        }
        if (!empty($action)) {
            $this->db->where('a.action', $action);
        }
        if (!empty($module)) {
            $this->db->where('a.module', $module);
        }

        $this->db->order_by('a.created_at', 'DESC');
        $q = $this->db->get();

        return ($q && is_object($q)) ? $q->result() : [];
    }

    public function get_users()
    {
        return $this->db->order_by('username', 'ASC')->get('users')->result();
    }

    ////////////////

    // Mengambil ringkasan jumlah aktivitas per jenis pada tanggal tertentu
    public function get_daily_summary($tanggal)
    {
        $this->db->select("
            COUNT(id) as total_aktivitas,
            SUM(CASE WHEN action = 'CREATE' THEN 1 ELSE 0 END) as total_tambah,
            SUM(CASE WHEN action = 'UPDATE' THEN 1 ELSE 0 END) as total_update,
            SUM(CASE WHEN action = 'SCAN' THEN 1 ELSE 0 END) as total_scan,
            COUNT(DISTINCT user_id) as total_petugas_aktif
        ");
        $this->db->from('activity_logs');
        $this->db->where('DATE(created_at)', $tanggal);
        return $this->db->get()->row();
    }

    // Mengambil daftar log detail per tanggal & petugas
    public function get_activity_logs($tanggal, $user_id = null)
    {
        $this->db->select('a.*, u.username, u.role');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->where('DATE(a.created_at)', $tanggal);

        if (!empty($user_id)) {
            $this->db->where('a.user_id', $user_id);
        }

        $this->db->order_by('a.created_at', 'DESC');
        return $this->db->get()->result();
    }
    // Ambil rincian detail berkas yang di-upload/update oleh user tertentu pada tanggal tertentu
    public function get_user_upload_detail($user_id, $tanggal, $kategori)
    {
        $this->db->select('a.*, u.username');
        $this->db->from('activity_logs a');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->where('a.user_id', $user_id);
        $this->db->where('DATE(a.created_at)', $tanggal);
        $this->db->where_in('a.action', ['CREATE', 'UPDATE']);

        if ($kategori === 'asing') {
            $this->db->where_in('a.module', ['t_upload', 't_perkara_detail']);
        } else {
            $this->db->where_in('a.module', ['nonlit_det', 'berkas_lampiran']);
        }

        $this->db->order_by('a.created_at', 'DESC');
        $q = $this->db->get();

        return ($q && is_object($q)) ? $q->result() : [];
    }
    //////////////
    // Mengambil ringkasan progres scan harian & akumulasi
    // public function get_progres_scan($tanggal_pilihan = null)
    // {
    //     $tgl_target = $tanggal_pilihan ? $tanggal_pilihan : date('Y-m-d');

    //     // 1. Hitung Aktivitas Non-Litigasi Hari Ini (C/U/D pada nonlit_det & berkas_lampiran)
    //     $q_nonlit_today = $this->db->select("
    //         COUNT(id) as total_aksi,
    //         SUM(CASE WHEN module = 'nonlit_det' THEN 1 ELSE 0 END) as total_det,
    //         SUM(CASE WHEN module = 'berkas_lampiran' THEN 1 ELSE 0 END) as total_lampiran
    //     ")
    //         ->from('activity_logs')
    //         ->where_in('module', ['nonlit_det', 'berkas_lampiran'])
    //         ->where_in('action', ['CREATE', 'UPDATE', 'DELETE'])
    //         ->where('DATE(created_at)', $tgl_target)
    //         ->get()->row();

    //     // 2. Hitung Aktivitas ASING / Litigasi Hari Ini (C/U/D pada t_upload & t_perkara_detail)
    //     $q_asing_today = $this->db->select("
    //         COUNT(id) as total_aksi
    //     ")
    //         ->from('activity_logs')
    //         ->where_in('module', ['t_upload', 't_perkara_detail'])
    //         ->where_in('action', ['CREATE', 'UPDATE', 'DELETE'])
    //         ->where('DATE(created_at)', $tgl_target)
    //         ->get()->row();

    //     // 3. Akumulasi Total Seluruh Berkas Terscan yang Ada di Database
    //     // Total Berkas Non-Litigasi (nonlit_det + berkas_lampiran)
    //     $q_nonlit_total = $this->db->query("
    //         SELECT COUNT(id) as total FROM (
    //             SELECT id FROM nonlit_det WHERE berkas IS NOT NULL AND berkas != ''
    //             UNION ALL
    //             SELECT id FROM berkas_lampiran WHERE nama_berkas IS NOT NULL AND nama_berkas != ''
    //         ) as total_files
    //     ")->row();

    //     // Total Berkas ASING (t_upload)
    //     $q_asing_total = $this->db->query("
    //         SELECT COUNT(id_berkas) as total 
    //         FROM db_perkara.t_upload 
    //         WHERE name_berkas IS NOT NULL AND name_berkas != ''
    //     ")->row();

    //     return (object) [
    //         'tgl_laporan'   => $tgl_target,
    //         'nonlit_today'  => $q_nonlit_today->total_aksi ?: 0,
    //         'asing_today'   => $q_asing_today->total_aksi ?: 0,  // Properti untuk Litigasi ASING
    //         'litigasi_today' => $q_asing_today->total_aksi ?: 0,  // Alias jika ingin dipanggil dengan litigasi_today
    //         'total_today'   => ($q_nonlit_today->total_aksi + $q_asing_today->total_aksi),
    //         'nonlit_total'  => $q_nonlit_total->total ?: 0,
    //         'asing_total'   => $q_asing_total->total ?: 0,       // Properti untuk Litigasi ASING
    //         'litigasi_total' => $q_asing_total->total ?: 0,       // Alias jika ingin dipanggil dengan litigasi_total
    //         'grand_total'   => ($q_nonlit_total->total + $q_asing_total->total)
    //     ];
    // }

    public function get_progres_scan($tanggal_pilihan = null)
    {
        $tgl_target = $tanggal_pilihan ? $tanggal_pilihan : date('Y-m-d');

        // 1. Hitung Aktivitas Scan Hari Ini per Kategori (HANYA CREATE & UPDATE, HAPUS/DELETE TIDAK DIHITUNG)
        $q_nonlit_today = $this->db->select("COUNT(id) as total_aksi")
            ->from('activity_logs')
            ->where_in('module', ['nonlit_det', 'berkas_lampiran'])
            ->where_in('action', ['CREATE', 'UPDATE']) // Exclude DELETE
            ->where('DATE(created_at)', $tgl_target)
            ->get();
        $total_nonlit_today = ($q_nonlit_today && is_object($q_nonlit_today)) ? $q_nonlit_today->row()->total_aksi : 0;

        $q_asing_today = $this->db->select("COUNT(id) as total_aksi")
            ->from('activity_logs')
            ->where_in('module', ['t_upload', 't_perkara_detail'])
            ->where_in('action', ['CREATE', 'UPDATE']) // Exclude DELETE
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
            'nonlit_today'    => $total_nonlit_today,
            'asing_today'     => $total_asing_today,
            'total_today'     => ($total_nonlit_today + $total_asing_today),
            'nonlit_total'    => $total_nonlit_acc,
            'asing_total'     => $total_asing_acc,
            'grand_total'     => ($total_nonlit_acc + $total_asing_acc),
            'user_nonlit'     => $user_nonlit,
            'user_asing'      => $user_asing
        ];
    }
}
