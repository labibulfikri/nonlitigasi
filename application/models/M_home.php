
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_home extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function make_query($tahun, $status)
    {

        $table = "nonlits";
        $select_column = "
         id, permohonan_nonlit, tgl_nonlit, team_nonlit,status, keterangan, bidang";
        $this->db->select($select_column);
        $this->db->from($table);
        if ($tahun != "all") {
            $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
        }
        if ($status != "" || $status != null) {
            $this->db->where('status', $status);
        }
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
    }

    function make_datatables($tahun, $status)
    {

        $this->make_query($tahun, $status);

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_filtered_data($tahun, $status)
    {
        $this->make_query($tahun, $status);
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();

        return $query->num_rows();
    }
    function get_all_data($tahun, $status)
    {
        $this->make_query($tahun, $status);
        $query = $this->db->get();
        return $this->db->count_all_results();
    }
    //////////////////////////////////////////////////////////////

    function make_query_status($tahun, $jaksa)
    {

        $table = "nonlits";
        $select_column = "
     id, permohonan_nonlit, tgl_nonlit, team_nonlit,status, keterangan, bidang, status";
        $this->db->select($select_column);
        $this->db->from($table);

        if ($tahun != "all") {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }
        $this->db->where('team_nonlit', $jaksa);
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
    }

    function make_datatables_status($tahun, $jaksa)
    {

        $this->make_query_status($tahun, $jaksa);

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_filtered_data_status($tahun, $jaksa)
    {
        $this->make_query_status($tahun, $jaksa);
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $query = $this->db->get();

        return $query->num_rows();
    }
    function get_all_data_status($tahun, $jaksa)
    {
        $this->make_query_status($tahun, $jaksa);
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $this->db->count_all_results();
    }
    /////////////////////////////////////////////////////////////

    function getAllByTahunStatus($tahun, $team, $status)
    {
        $tahun = $tahun;
        $team = $team;
        $status = $status;

        $table = "nonlits";
        $select_column = array(
            "count(nonlits.id) as status",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        // $this->db->join('nonlit_det', 'nonlit_det.id_nonlit = nonlits.id');

        if ($tahun != "all") {

            $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
        }

        if ($status != null) {

            // $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
            $this->db->where('status', $status);
        }
        $this->db->where('team_nonlit', $team);
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->row_array();
        // return $this->db->;
    }

    ///////////////////////////////////////////////////////////

    function make_query_status2($tahun, $jaksa, $status)
    {

        $table = "nonlits";
        $select_column = "
     id, permohonan_nonlit, tgl_nonlit, team_nonlit,status, keterangan, bidang, status";
        $this->db->select($select_column);
        $this->db->from($table);

        if ($tahun != "all") {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }

        if ($status != null) {
            // $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
            $this->db->where('status', $status);
        }
        if ($jaksa != null) {

            // $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
            // $this->db->where('status', $status);
            $this->db->where('team_nonlit', $jaksa);
        }
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
    }

    function make_datatables_status2($tahun, $jaksa, $status)
    {

        $this->make_query_status2($tahun, $jaksa, $status);

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_filtered_data_status2($tahun, $jaksa, $status)
    {
        $this->make_query_status2($tahun, $jaksa, $status);
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $query = $this->db->get();

        return $query->num_rows();
    }
    function get_all_data_status2($tahun, $jaksa, $status)
    {
        $this->make_query_status2($tahun, $jaksa, $status);
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $this->db->count_all_results();
    }
    ///////////////////////////////////////////////////////////


    public function get_dashboard_filtered($tahun, $status)
    {
        // Total permohonan
        $this->db->select('COUNT(*) as total');
        $this->db->from('nonlits');
        if ($tahun != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }
        if ($status != 'all') {
            $this->db->where('status', $status);
        }
        $total = $this->db->get()->row()->total;

        // Total proses
        $this->db->select('COUNT(*) as proses');
        $this->db->from('nonlits');
        if ($tahun != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }
        $this->db->where('status', 'proses');
        $proses = $this->db->get()->row()->proses;

        // Total selesai
        $this->db->select('COUNT(*) as selesai');
        $this->db->from('nonlits');
        if ($tahun != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }
        $this->db->where('status', 'selesai');
        $selesai = $this->db->get()->row()->selesai;

        // Grafik per team & status
        $this->db->select('team_nonlit, status, COUNT(*) as jumlah');
        $this->db->from('nonlits');
        if ($tahun != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $tahun);
        }
        if ($status != 'all') {
            $this->db->where('status', $status);
        }
        $this->db->group_by(['team_nonlit', 'status']);
        $grafik = $this->db->get()->result();

        return [
            'total'   => $total,
            'proses'  => $proses,
            'selesai' => $selesai,
            'grafik'  => $grafik
        ];
    }
}
