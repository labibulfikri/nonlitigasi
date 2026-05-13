<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Peta extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_nonlit');
        $this->load->model('m_peta');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }
    
    public function index() {
        $raw = $this->get_all_layers();
        
        $data['polygons'] = $this->clean_geojson($raw['polygon']);
        $data['points']   = $this->clean_geojson($raw['point']);
        $data['lines']    = $this->clean_geojson($raw['garis']);
        
        $this->load->view('nonlit/peta_fullscreen', $data);
    }

    public function get_all_layers() {
        $db_peta = "sertifikasi_v2"; 
        $db_nonlit = "nonlit";
        
        // Query Polygon dengan pengecekan status masalah
        $sql_poly = "SELECT s.id_aset, s.alamat, s.register_baru, s.geometry, n.permohonan_nonlit,
                    (CASE WHEN n.register_baru IS NOT NULL THEN 'bermasalah' ELSE 'aman' END) as status_aset
                    FROM `$db_peta`.peta_gis s
                    LEFT JOIN `$db_nonlit`.nonlits n ON n.register_baru REGEXP CONCAT('[[:<:]]', TRIM(s.register_baru), '[[:>:]]')";
        
        return [
            'polygon' => $this->db->query($sql_poly)->result_array(),
            'point'   => $this->db->query("SELECT id, Name, FolderPath, geometry FROM `$db_peta`.peta_point")->result_array(),
            'garis'   => $this->db->query("SELECT id, Name, FolderPath, geometry FROM `$db_peta`.peta_garis")->result_array()
        ];
    }

    public function ajax_search() {
        $keyword = $this->input->post('keyword');
        $db_peta = "sertifikasi_v2"; 
        $db_nonlit = "nonlit";

        $sql = "SELECT register_baru, permohonan_nonlit FROM `$db_nonlit`.nonlits 
                WHERE register_baru LIKE ? OR permohonan_nonlit LIKE ? LIMIT 10";
        $results = $this->db->query($sql, ["%$keyword%", "%$keyword%"])->result_array();

        $dropdown_data = [];
        foreach ($results as $row) {
            $regs = array_map('trim', explode(';', $row['register_baru']));
            foreach ($regs as $r) {
                if (stripos($r, $keyword) !== false || stripos($row['permohonan_nonlit'], $keyword) !== false) {
                    // Pastikan nama kolom di peta_gis adalah register_baru
                    $cek_map = $this->db->get_where("$db_peta.peta_gis", ['register_baru' => $r])->num_rows();
                    $dropdown_data[] = [
                        'register' => $r,
                        'permohonan' => $row['permohonan_nonlit'],
                        'has_map' => ($cek_map > 0)
                    ];
                }
            }
        }
        echo json_encode($dropdown_data);
    }

    private function clean_geojson($data) {
        $features = [];
        foreach ($data as $row) {
            if (!empty($row['geometry'])) {
                $decoded = json_decode($row['geometry'], true);
                $geom = isset($decoded['geometry']) ? $decoded['geometry'] : $decoded;

                if (isset($geom['type']) && isset($geom['coordinates'])) {
                    if ($geom['type'] === 'Point') {
                        $geom['coordinates'] = [(float)$geom['coordinates'][0], (float)$geom['coordinates'][1]];
                    } elseif ($geom['type'] === 'LineString' || $geom['type'] === 'Polygon') {
                        $geom['coordinates'] = $this->recursive_clean($geom['coordinates']);
                    }
                }

                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "nama" => $row['Name'] ?? ($row['register_baru'] ?? 'Tanpa Nama'),
                        "alamat" => $row['alamat'] ?? '',
                        "folder" => $row['FolderPath'] ?? '',
                        "simbada" => $row['register_baru'] ?? '',
                        "status_aset" => $row['status_aset'] ?? (!empty($row['permohonan_nonlit']) ? 'bermasalah' : 'aman')
                    ],
                    "geometry" => $geom
                ];
            }
        }
        return json_encode(["type" => "FeatureCollection", "features" => $features]);
    }

    private function recursive_clean($coords) {
        if (!is_array($coords[0])) {
            return [(float)$coords[0], (float)$coords[1]];
        }
        return array_map([$this, 'recursive_clean'], $coords);
    }

    public function index2()
    {
        $json_string2 = $this->m_peta->getAll();
       


        $polygons = [];
        foreach ($json_string2 as $row) {
            $json_string = isset($row['kordinat']) ? $row['kordinat'] : '{}';

            // Decode JSON string ke array
            $decoded_data = json_decode($json_string, true);

            // Periksa jika decoding berhasil dan formatnya benar
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data) && isset($decoded_data['geometry']['coordinates'])) {
                $polygons[] = $decoded_data;
            }
        }


        $data = array(

            'masterpage' => 'layout/layout2',
            // 'navbar2' => 'layout/navbar2',
            // 'navbar_bawah' => 'layout/navbar_bawah2',
            'list' => $decoded_data,
            'polygons' => json_encode($polygons),
            'content' => 'nonlit/peta_all',
            'footer' => 'layout/footer',
            'tab' => 'layout/tab_detail',
            'title' => 'Daftar Nonlitigasi'


        );
        $this->load->view($data['masterpage'], $data);
    }

    public function search() {
    $keyword = $this->input->get('search');
    $results = $this->m_peta->searchByAlamat($keyword);
 
    $features = [];
    foreach ($results as $row) {
        $geometry = null;

        // 1. Cek dulu apakah ada geometri dari tabel Sertifikasi (Simbada)
        if (!empty($row['geom_sertifikasi'])) {
            $geometry = json_decode($row['geom_sertifikasi'], true);
        } 
        // 2. Jika Sertifikasi kosong, gunakan koordinat dari Nonlit sebagai cadangan
        elseif (!empty($row['geom_nonlit'])) {
            $dec = json_decode($row['geom_nonlit'], true);
            // Jika isi geom_nonlit adalah format Feature lengkap, ambil bagian geometry-nya saja
            $geometry = (isset($dec['geometry'])) ? $dec['geometry'] : $dec;
        }

        if ($geometry) {
            $features[] = [
                "type" => "Feature",
                "properties" => [
                    "id" => $row['id'],
                    "nama" => $row['permohonan_nonlit'],
                    "alamat" => $row['alamat'] ?? 'Alamat tidak terdaftar di Simbada',
                    "register" => $row['register_b'] ?? 'N/A'
                ],
                "geometry" => $geometry
            ];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($features);
}
    public function search3()
    {
        $search = $this->input->get('search', true);
        $this->load->model('m_peta');

        // Ambil data berdasarkan alamat
        $polygons = $this->m_peta->get_by_alamat($search);

        // Kirim data dalam format JSON
        echo json_encode($polygons);
    }

    function map_by_id($id)
    {
        $list = $this->m_peta->by_id($id);
        $fetch = $this->m_nonlit->get_byid_nonlit($id);

        $data = array(
            'master' => $fetch,
            'id' => $id,
            'list' => $list,
            'polygon' => json_encode($list[0]['kordinat']),
            'masterpage' => 'layout/layout2',
            // 'navbar2' => 'layout/navbar2',
            // 'navbar_bawah' => 'layout/navbar_bawah2',
            'content' => 'nonlit/edit_peta',
            'footer' => 'layout/footer',
            'tab' => 'nonlit/tab_detail',
            'title' => 'Daftar Nonlitigasi'


        );
        $this->load->view($data['masterpage'], $data);
    }

    function edit($id)
    {
        // Ambil data GeoJSON dan data tambahan berdasarkan ID
        $id = $id;
        $json_string = $this->m_peta->get_geojson($id);


        // $list = $this->m_peta->by_id($id);

        $json_string2 = $this->m_peta->get_geojson($id);
        // Pastikan $json_string2 tidak null dan memiliki key 'kordinat'
        $json_string = isset($json_string2['kordinat']) ? $json_string2['kordinat'] : '{}';

        // Decode JSON string ke array
        $decoded_data = json_decode($json_string, true);

        // Periksa jika decoding berhasil
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
            // Memastikan format data GeoJSON
            $polygon = isset($decoded_data['geometry']['coordinates']) ? $decoded_data : [];
        } else {
            $polygon = [];
            // echo 'JSON Decode Error: ' . json_last_error_msg();
        }

        $fetch = $this->m_nonlit->get_byid_nonlit($id);

        $data = array(
            'master' => $fetch,
            'id' => $id,
            'list' => $decoded_data,
            'polygon' => json_encode($polygon),
            'masterpage' => 'layout/layout2',
            // 'navbar2' => 'layout/navbar2',
            // 'navbar_bawah' => 'layout/navbar_bawah2',
            'content' => 'nonlit/edit_peta2',
            'footer' => 'layout/footer',
            'tab' => 'nonlit/tab_detail',
            'title' => 'Daftar Nonlitigasi'
        );
        $this->load->view($data['masterpage'], $data);
    }
    // private function convertGeoJSONToLatLng($json_string)

    // {
    //     // Decode JSON string ke array
    //     $decoded_data = json_decode($json_string, true);
    //     var_dump($decoded_data);
    //     exit();
    //     // Periksa jika decoding berhasil
    //     if (is_array($decoded_data) && isset($decoded_data['kordinat'])) {
    //         $coordinates = $decoded_data['kordinat']; // Ini benar jika data sudah dalam bentuk array
    //     } else {
    //         $coordinates = []; // Atur default jika decoding gagal
    //     }
    //     $coordinates = $decoded_data['geometry']['coordinates'][0];
    //     $latLngData = array();

    //     foreach ($coordinates as $coord) {
    //         $latLngData[] = array(
    //             'lat' => $coord[1],
    //             'lng' => $coord[0]
    //         );
    //     }

    //     return $latLngData;
    // }
    function update_peta()
    {
        $id = $this->input->post('id');

        $update = array(

            'kordinat' => $this->input->post('kordinat'),
        );
        $exe = $this->m_peta->update_peta($update, $id);

        if ($exe > 0) {
            $response = array('status' => 'success', 'message' => 'Data berhasil disimpan.');
            echo json_encode($response);
        }
    }

    // public function save_edited_data()
    // {
    //     // Mengambil data JSON yang dikirimkan
    //     $data = file_get_contents('php://input');
    //     $decoded_data = json_decode($data, true);

    //     if (json_last_error() === JSON_ERROR_NONE) {
    //         $id = $decoded_data['id']; // Ambil ID dari data yang dikirim
    //         $geojsons = $decoded_data['geojson'];
    //         // var_dump($geojsons);
    //         // exit();
    //         foreach ($geojsons as $geojson) {
    //             $this->m_peta->update_geojson($geojson, $id);
    //         }

    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    //     }
    // }

    // public function save_new_data()
    // {
    //     $input = file_get_contents('php://input');
    //     $data = json_decode($input, true);

    //     // Debug data GeoJSON
    //     if (!$data) {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    //         return;
    //     }

    //     // Proses penyimpanan data ke database
    //     $result = $this->m_peta->insert_geojson($data['geojson'], $data['id']);

    //     if ($result) {
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    //     }
    // }
    // public function delete_data()
    // {
    //     // Ambil data POST yang dikirim dari frontend
    //     $input = file_get_contents('php://input');
    //     $data = json_decode($input, true);

    //     if (json_last_error() === JSON_ERROR_NONE) {
    //         $id = $data['id']; // Ambil ID dari data yang dikirim


    //         $geojsons = $data['geojson'];

    //         foreach ($geojsons as $geojson) {
    //             $this->m_peta->delete_geojson($geojson, $id);
    //         }

    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    //     }
    // }
    public function save_new_data()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data || !isset($data['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        // Pastikan data GeoJSON dikonversi kembali ke string JSON sebelum masuk ke database
        $geojson_string = json_encode($data['geojson']);

        // Gunakan fungsi update karena ID sudah ada di database
        $result = $this->m_peta->update_geojson($geojson_string, $data['id']);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database']);
        }
    }

    // Gunakan fungsi yang sama untuk edit karena logic-nya identik
    public function save_edited_data()
    {
        $this->save_new_data();
    }

    public function delete_data()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (isset($data['id'])) {
            // Logika hapus koordinat: Set kolom geojson menjadi NULL atau string kosong
            // Kita tidak perlu foreach karena kita menghapus berdasarkan ID baris
            $result = $this->m_peta->clear_geojson($data['id']);

            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus koordinat']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        }
    }

    function search2()
    {
        $search = $this->input->post('search');

        // Mengambil hasil pencarian dari model
        $results = $this->m_peta->search_suggestions($search);

        // Mengirimkan hasil dalam bentuk JSON
        echo json_encode($results);
    }
}
