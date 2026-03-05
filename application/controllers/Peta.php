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
    public function index()
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
            'navbar2' => 'layout/navbar2',
            'navbar_bawah' => 'layout/navbar_bawah2',
            'list' => $decoded_data,
            'polygons' => json_encode($polygons),
            'content' => 'nonlit/peta_all',
            'footer' => 'layout/footer',
            'tab' => 'layout/tab_detail',
            'title' => 'Daftar Nonlitigasi'


        );
        $this->load->view($data['masterpage'], $data);
    }
    public function search()
    {
        $search = $this->input->get('search');
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
            'navbar2' => 'layout/navbar2',
            'navbar_bawah' => 'layout/navbar_bawah2',
            'content' => 'nonlit/edit_peta',
            'footer' => 'layout/footer',
            'tab' => 'layout/tab_detail',
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
            'navbar2' => 'layout/navbar2',
            'navbar_bawah' => 'layout/navbar_bawah2',
            'content' => 'nonlit/edit_peta2',
            'footer' => 'layout/footer',
            'tab' => 'layout/tab_detail',
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

    public function save_edited_data()
    {
        // Mengambil data JSON yang dikirimkan
        $data = file_get_contents('php://input');
        $decoded_data = json_decode($data, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $id = $decoded_data['id']; // Ambil ID dari data yang dikirim
            $geojsons = $decoded_data['geojson'];
            // var_dump($geojsons);
            // exit();
            foreach ($geojsons as $geojson) {
                $this->m_peta->update_geojson($geojson, $id);
            }

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        }
    }

    public function save_new_data()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Debug data GeoJSON
        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            return;
        }

        // Proses penyimpanan data ke database
        $result = $this->m_peta->insert_geojson($data['geojson'], $data['id']);

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
    }
    public function delete_data()
    {
        // Ambil data POST yang dikirim dari frontend
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $id = $data['id']; // Ambil ID dari data yang dikirim


            $geojsons = $data['geojson'];

            foreach ($geojsons as $geojson) {
                $this->m_peta->delete_geojson($geojson, $id);
            }

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
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
