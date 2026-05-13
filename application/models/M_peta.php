<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_peta extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
private function safe_query($sql, $params = []) {
        $query = $this->db->query($sql, $params);
        if (!$query) {
            $error = $this->db->error();
            log_message('error', 'Query Error: ' . $error['message']);
            return [];
        }
        return $query->result_array();
    }

    public function get_all_layers() {
        $db_peta = "sertifikasi_v2"; 
        $db_nonlit = "nonlit";
        
        // Gunakan REGEXP untuk mencocokkan register tunggal di string register_baru (123; 456)
        $sql_poly = "SELECT s.id_aset, s.alamat, s.register_baru as simbada, s.geometry, n.permohonan_nonlit,
                    (CASE WHEN n.register_baru REGEXP CONCAT('[[:<:]]', TRIM(s.register_baru), '[[:>:]]') THEN 'bermasalah' ELSE 'aman' END) as status_aset
                    FROM `$db_peta`.peta_gis s
                    LEFT JOIN `$db_nonlit`.nonlits n ON n.register_baru REGEXP CONCAT('[[:<:]]', TRIM(s.register_baru), '[[:>:]]')";
        
        return [
            'polygon' => $this->safe_query($sql_poly),
            'point'   => $this->safe_query("SELECT id, Name, geometry FROM `$db_peta`.peta_point"),
            'garis'   => $this->safe_query("SELECT id, Name, geometry FROM `$db_peta`.peta_garis")
        ];
    }

    public function search_asset($keyword) {
        $db_peta = "sertifikasi_v2"; 
        $db_nonlit = "nonlit";
        $sql = "SELECT s.id_aset, s.alamat, s.register_baru as simbada, IFNULL(s.geometry, n.kordinat) as geometry, 
                n.permohonan_nonlit, n.register_baru
                FROM `$db_nonlit`.nonlits n
                LEFT JOIN `$db_peta`.peta_gis s ON n.register_baru REGEXP CONCAT('[[:<:]]', TRIM(s.register_baru), '[[:>:]]')
                WHERE n.permohonan_nonlit LIKE ? OR n.register_baru LIKE ? OR s.register_baru LIKE ? OR s.alamat LIKE ? LIMIT 10";

        return $this->safe_query($sql, array_fill(0, 4, "%$keyword%"));
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
   public function getAll() {// Nama database sesuai penjelasan Anda
        $db_peta = "sertifikasi_v2";
        $db_nonlit = "nonlit";

        $sql = "SELECT 
                    s.id_aset, 
                    s.alamat, 
                    s.register_baru as simbada_sertif, 
                    s.geometry as geom_sertif,
                    n.register_baru as simbada_nonlit,
                    n.kordinat as geom_nonlit, -- Kolom kordinat di tabel nonlits
                    n.permohonan_nonlit,
                    (CASE WHEN n.register_baru IS NOT NULL THEN 'bermasalah' ELSE 'aman' END) as status_aset
                FROM `$db_peta`.peta_gis s
                LEFT JOIN `$db_nonlit`.nonlits n ON TRIM(s.register_baru) = TRIM(n.register_baru)
                
                UNION -- Menggabungkan data yang ada di nonlits tapi tidak ada di peta_gis
                
                SELECT 
                    NULL as id_aset, 
                    NULL as alamat, 
                    NULL as simbada_sertif, 
                    NULL as geom_sertif,
                    n.register_baru as simbada_nonlit,
                    n.kordinat as geom_nonlit,
                    n.permohonan_nonlit,
                    'bermasalah' as status_aset
                FROM `$db_nonlit`.nonlits n
                WHERE n.register_baru NOT IN (SELECT register_baru FROM `$db_peta`.peta_gis WHERE register_baru IS NOT NULL)";

        return $this->db->query($sql)->result_array();
}
// public function search_asset($keyword) {
//     $db_peta = "sertifikasi_v2";
//     $db_nonlit = "nonlit";

//     $sql = "SELECT 
//                 s.id_aset, 
//                 s.alamat, 
//                 s.register_baru as simbada, 
//                 s.geometry, 
//                 n.permohonan_nonlit,
//                 n.register_baru
//             FROM `$db_peta`.peta_gis s
//             LEFT JOIN `$db_nonlit`.nonlits n ON TRIM(s.register_baru) = TRIM(n.register_baru)
//             WHERE (TRIM(s.register_baru) LIKE ? 
//                OR TRIM(n.register_baru) LIKE ? 
//                OR s.alamat LIKE ? 
//                OR n.permohonan_nonlit LIKE ?)
//             LIMIT 10";

//     $bind = "%" . trim($keyword) . "%";
//     $query = $this->db->query($sql, [$bind, $bind, $bind, $bind]);

//     return ($query) ? $query->result_array() : [];
// }


// public function search_asset($keyword) {
//     $db_peta = "sertifikasi_v2";
//     $db_nonlit = "nonlit";

//     // Mencari di tabel sertifikasi yang terhubung ke nonlit
//     // DAN mencari di tabel nonlit yang mungkin belum ada di sertifikasi
//     $sql = "SELECT 
//                 s.id_aset, s.alamat, s.register_baru as simbada, 
//                 IFNULL(s.geometry, n.kordinat) as geometry, 
//                 n.permohonan_nonlit, n.register_baru
//             FROM `$db_nonlit`.nonlits n
//             LEFT JOIN `$db_peta`.peta_gis s ON TRIM(n.register_baru) = TRIM(s.register_b)
//             WHERE n.permohonan_nonlit LIKE ? 
//                OR n.register_baru LIKE ? 
//                OR s.register_baru LIKE ? 
//                OR s.alamat LIKE ?
//             LIMIT 10";

//     $bind = "%$keyword%";
//     return $this->db->query($sql, [$bind, $bind, $bind, $bind])->result_array();
// }

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

    // public function update_geojson($geojson, $id)
    // {
    //     $geojson_data = json_encode($geojson);

    //     // Query untuk memperbarui data di database
    //     $query = $this->db->where('id', $id); // Gunakan kolom ID yang sesuai
    //     $query = $this->db->update('nonlits', ['kordinat' => $geojson_data]);
    //     // $a = $this->db->last_query($query);
    //     // print_r($a);
    //     // exit();
    // }
    public function update_geojson($geojson, $id)
    {
        $this->db->set('kordinat', $geojson); // Asumsi nama kolom Anda adalah 'kordinat'
        $this->db->where('id', $id); // Asumsi nama primary key Anda adalah 'id'
        return $this->db->update('nonlits'); // Ganti dengan nama tabel Anda
    }

    public function clear_geojson($id)
    {
        $this->db->set('kordinat', NULL);
        $this->db->where('id', $id);
        return $this->db->update('nonlits');
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
