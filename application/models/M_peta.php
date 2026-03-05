<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_peta extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function by_id($id)
    {

        $table = "nonlits";
        $select_column = array(
            "id",
            // "nama_gis",
            // "alamat",
            // "kelurahan",
            "kordinat"
        );

        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->where('id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }

    function getAll()
    {

        $table = "nonlits";
        $select_column = array(
            "id",
            // "nama_gis",
            // "alamat",
            // "kelurahan",
            "kordinat"
        );

        $this->db->select($select_column);
        $this->db->from($table);

        // $this->db->where('id', $id);

        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result_array();
    }

    public function get_by_alamat($alamat)
    {
        $this->db->select('kordinat');
        $this->db->from('nonlits');
        // $this->db->like('kordinat->>"$.properties.ALAMAT"', $alamat); // Search in JSON field
        $this->db->like('permohonan_nonlit', $alamat); // Search in JSON field
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result_array();
    }
    function update_peta($data, $id)
    {

        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('nonlits', $data);
        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }


    public function insert_geojson($geojson, $id)
    {
        // Menyimpan data GeoJSON baru ke database
        // Sesuaikan nama tabel dan kolom sesuai kebutuhan Anda
        $data = array(
            'kordinat' => json_encode($geojson),

        );

        // Jika Anda ingin memasukkan data baru
        // return $this->db->insert('polygons', $data);

        // Atau jika Anda ingin memperbarui data yang ada
        $this->db->where('id', $id);
        return $this->db->update('nonlits', $data);
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
    }

    public function update_geojson($geojson, $id)
    {
        $geojson_data = json_encode($geojson);

        // Query untuk memperbarui data di database
        $query = $this->db->where('id', $id); // Gunakan kolom ID yang sesuai
        $query = $this->db->update('nonlits', ['kordinat' => $geojson_data]);
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
    }
    public function search_suggestions($search)
    {
        $this->db->like('permohonan_nonlit', $search); // Pencarian berdasarkan kolom 'name'
        $this->db->limit(10); // Batasi hasil pencarian
        $query = $this->db->get('nonlits'); // Ganti dengan nama tabel yang sesuai
        return $query->result();
    }
    public function delete_geojson($geojson, $id)
    {


        // Query untuk memperbarui data di database
        $query = $this->db->where('id', $id); // Gunakan kolom ID yang sesuai
        $query = $this->db->update('nonlits', ['kordinat' => '']);
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
    }

    public function get_geojson($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('nonlits');
        return $query->row_array();  // Mengembalikan data sebagai array
    }
}
