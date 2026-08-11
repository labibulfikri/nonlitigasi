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
}
