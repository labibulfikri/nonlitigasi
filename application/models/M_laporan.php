<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    function make_query($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit)
    {

        $table = "nonlits";
        $select_column = "
         nonlits.id, permohonan_nonlit, updated_by,tgl_nonlit, penyimpanan_rak, team_nonlit,status, keterangan, bidang, status, register_baru, luas, pic, username,alamat,updated_by, updated_at";
        $this->db->select($select_column);
        $this->db->join('users', 'users.id = nonlits.updated_by', 'left');

        if ($tahun != null || $tahun != "") {
            $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
        }

        if ($status != null || $status != "") {
            $this->db->where('status', $status);
        }
        if ($bidang != null || $bidang != "") {
            $this->db->where('bidang', $bidang);
        }
        if ($pic != null || $pic != "") {
            $this->db->where('pic', $pic);
        }
        if ($permohonan_nonlit != null || $permohonan_nonlit != "") {
            $this->db->where('permohonan_nonlit', $permohonan_nonlit);
        }
        if ($team != null || $team != "") {
            $this->db->where('team_nonlit', $team);
        }

        $this->db->from($table);

        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit', 'register_baru', 'luas', 'pic', 'alamat');
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

    function get_filtered_data($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit)
    {
        $this->make_query($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit);
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit', 'register_baru');
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
    function get_all_data($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit)
    {
        $this->make_query($status, $bidang, $pic, $team,   $tahun, $permohonan_nonlit);
        $query = $this->db->get();
        return $this->db->count_all_results();
    }
}
