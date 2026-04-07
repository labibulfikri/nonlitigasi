<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_masalah extends CI_Model
{

    public function get_all_masalah($search = '', $limit = 10, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from('masalah'); // Pastikan nama tabel Anda adalah 'masalah'

        // Logika Pencarian (Search)
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_masalah', $search);
            $this->db->or_like('alamat_masalah', $search);
            $this->db->or_like('pic_masalah', $search);
            $this->db->or_like('penyimpanan_rak', $search);
            $this->db->group_end();
        }

        // Urutkan berdasarkan data terbaru
        $this->db->order_by('id_masalah', 'DESC');

        // Batasan data untuk load more / pagination
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_all($search)
    {
        if ($search) {
            $this->db->like('nama_masalah', $search);
        }
        return $this->db->count_all_results('masalah');
    }

    public function insert($data)
    {
        $this->db->insert('masalah', $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        return $this->db->delete('masalah', ['id_masalah' => $id]);
    }
    public function insert_detail($data)
    {
        return $this->db->insert('masalah_det', $data);
    }

    public function get_datatables($search = null)
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_masalah', $search);
            $this->db->or_like('pic_masalah', $search);
            $this->db->group_end();
        }
        $this->db->order_by('id_masalah', 'DESC');
        return $this->db->get('masalah')->result();
    }

    public function get_details($id_masalah)
    {
        return $this->db->where('id_masalah', $id_masalah)
            ->order_by('tgl_masalah_det', 'DESC')
            ->get('masalah_det')
            ->result();
    }
}
