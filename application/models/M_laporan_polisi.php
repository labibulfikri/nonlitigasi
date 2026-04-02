<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan_polisi extends CI_Model
{

    public function get_all_laporan($search = '', $limit = 0, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from('laporan_polisi');

        if ($search) {
            $this->db->group_start();
            $this->db->like('judul_laporan_polisi', $search);
            $this->db->or_like('nomor_polisi', $search);
            $this->db->group_end();
        }

        $this->db->order_by('id_laporan_polisi', 'DESC');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function count_all($search = '')
    {
        $this->db->from('laporan_polisi');

        if ($search) {
            $this->db->group_start();
            $this->db->like('judul_laporan_polisi', $search);
            $this->db->or_like('nomor_polisi', $search);
            $this->db->or_like('pelapor', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil Data Master berdasarkan ID
    public function get_laporan_by_id($id)
    {
        return $this->db->get_where('laporan_polisi', ['id_laporan_polisi' => $id])->row();
    }
    public function get_master_by_id($id)
    {
        return $this->db->get_where('laporan_polisi', ['id_laporan_polisi' => $id])->row();
    }
    public function insert_master($data)
    {
        return $this->db->insert('laporan_polisi', $data);
    }
    // Ambil Data Histori (Detail) berdasarkan ID Master
    public function get_detail_by_master($id_master)
    {
        return $this->db->where('id_laporan_polisi', $id_master)
            ->order_by('tgl_agenda_laporan_polisi_det', 'DESC')
            ->get('laporan_polisi_det')
            ->result();
    }
    public function update_master($id, $data)
    {
        return $this->db->where('id_laporan_polisi', $id)->update('laporan_polisi', $data);
    }
    public function delete_master($id)
    {
        // Hapus berkas fisiknya dulu jika ada di detail
        $details = $this->get_detail_by_master($id);
        foreach ($details as $d) {
            if ($d->berkas_pendukung) {
                @unlink('./assets/berkas_laporan/' . $d->berkas_pendukung);
            }
        }
        // Hapus data di database (Cascade jika foreign key diset, jika tidak hapus manual)
        $this->db->where('id_laporan_polisi', $id)->delete('laporan_polisi_det');
        return $this->db->where('id_laporan_polisi', $id)->delete('laporan_polisi');
    }
}
