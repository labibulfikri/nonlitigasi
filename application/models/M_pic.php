<?php
class M_pic extends CI_Model
{

    var $table = 'master_pic'; // Nama tabel Anda
    var $column_order = array(null, 'nama_pic', 'bidang_pic', 'status_pic');
    var $column_search = array('nama_pic', 'bidang_pic');
    var $order = array('id' => 'desc');

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables_pic()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered_pic()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_pic()
    {
        return $this->db->count_all_results($this->table);
    }

    // CRUD Standar
    public function insert_pic($data)
    {
        return $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0;
    }

    public function update_pic($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
        return $this->db->affected_rows() >= 0;
    }

    public function get_pic_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    public function get_all_pic()
    {
        return $this->db->get($this->table)->result();
    }

    public function delete_pic($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
