<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_report_masalah extends CI_Model
{

    public function get_unique_pic()
    {
        $this->db->select('pic_masalah');
        $this->db->distinct();
        $this->db->from('masalah');
        $this->db->where('pic_masalah !=', '');
        $this->db->order_by('pic_masalah', 'ASC');
        $query = $this->db->get();
        return $query ? $query->result() : [];
    }

    public function _get_datatables_query($filter)
    {
        $this->db->from('masalah');

        if (!empty($filter['tahun'])) {
            $this->db->where('YEAR(tgl_masalah)', $filter['tahun']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('status_masalah', $filter['status']);
        }
        if (!empty($filter['pic'])) {
            $this->db->where('pic_masalah', $filter['pic']);
        }
        if (!empty($filter['keyword'])) {
            $this->db->group_start();
            $this->db->like('nama_masalah', $filter['keyword']);
            $this->db->or_like('alamat_masalah', $filter['keyword']);
            $this->db->group_end();
        }
    }

    public function get_datatables($filter)
    {
        $this->_get_datatables_query($filter);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $this->db->order_by('id_masalah', 'DESC');
        return $this->db->get()->result();
    }

    public function count_filtered($filter)
    {
        $this->_get_datatables_query($filter);
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all_results('masalah');
    }

    public function get_report_data($filter)
    {
        $this->_get_datatables_query($filter);
        return $this->db->get()->result();
    }
}
