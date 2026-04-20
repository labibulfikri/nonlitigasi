<?php
class M_berkas_umum extends CI_Model
{

    public function get_all()
    {
        return $this->db->order_by('id_berkas_umum', 'DESC')->get('berkas_umum')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('berkas_umum', ['id_berkas_umum' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('berkas_umum', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_berkas_umum', $id);
        return $this->db->update('berkas_umum', $data);
    }

    public function delete($id)
    {
        // Menghapus record di database
        $this->db->where('id_berkas_umum', $id);
        return $this->db->delete('berkas_umum');
    }



    public function get_detail($id_parent)
    {
        return $this->db->where('id_berkas_umum', $id_parent)
            ->order_by('id_berkas_umum_det', 'DESC')
            ->get('berkas_umum_det')->result();
    }

    public function get_det_by_id($id_det)
    {
        return $this->db->get_where('berkas_umum_det', ['id_berkas_umum_det' => $id_det])->row();
    }

    public function insert_det($data)
    {
        return $this->db->insert('berkas_umum_det', $data);
    }

    public function update_det($id_det, $data)
    {
        $this->db->where('id_berkas_umum_det', $id_det);
        return $this->db->update('berkas_umum_det', $data);
    }

    public function delete_det($id_det)
    {
        $this->db->where('id_berkas_umum_det', $id_det);
        return $this->db->delete('berkas_umum_det');
    }
}
