<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_nonlit extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    var $table = 'nonlits'; // Sesuaikan nama tabel Anda
    var $column_order  = array(null, 'permohonan_nonlit', 'pic', 'tgl_nonlit', 'status');
    var $column_search = array('permohonan_nonlit', 'alamat', 'pic', 'register_baru');

    //     function make_query()
    // {
    //     $table = "nonlits";

    //     // Select column ditambah progres terakhir dari join subquery
    //     $select_column = "
    //         nonlits.id, 
    //         permohonan_nonlit, 
    //         tgl_nonlit, 
    //         penyimpanan_rak, 
    //         users.username, 
    //         team_nonlit, 
    //         status, 
    //         keterangan, 
    //         bidang, 
    //         pic, 
    //         alamat, 
    //         register_baru,
    //         luas,
    //         updated_at,
    //         det.kesimpulan,
    //         det.tgl_rapat as tgl_update_progres";

    //     $this->db->select($select_column);
    //     $this->db->from($table);
    //     $this->db->join('users', 'users.id = nonlits.updated_by', 'left');

    //     // Join Subquery untuk mengambil detail paling akhir berdasarkan tgl_rapat
    //     $this->db->join('(
    //         SELECT id_nonlit, kesimpulan, tgl_rapat
    //         FROM nonlit_det
    //         WHERE id IN (
    //             SELECT MAX(id) 
    //             FROM nonlit_det 
    //             GROUP BY id_nonlit
    //         )
    //     ) det', 'nonlits.id = det.id_nonlit', 'left');

    //     // Filter dari Pencarian Filter Atas (Jika ada)
    //     if ($this->input->post('tahun') && $this->input->post('tahun') != 'all') {
    //         $this->db->where('YEAR(tgl_nonlit)', $this->input->post('tahun'));
    //     }
    //     if ($this->input->post('status')) {
    //         $this->db->where('status', $this->input->post('status'));
    //     }
    //     if ($this->input->post('pic')) {
    //         $this->db->where('pic', $this->input->post('pic'));
    //     }

    //     // Logic Search Bawaan Datatable
    //     $i = 0;
    //     $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'pic', 'det.progres_terakhir');

    //     foreach ($column_search as $item) {
    //         if (@$_POST['search']['value']) {
    //             if ($i === 0) {
    //                 $this->db->group_start();
    //                 $this->db->like($item, $_POST['search']['value']);
    //             } else {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
    //             if (count($column_search) - 1 == $i)
    //                 $this->db->group_end();
    //         }
    //         $i++;
    //     }

    //     // Order Default
    //     $this->db->order_by('nonlits.id', 'desc');
    // }

    private function _get_datatables_query($search = "")
    {
        $this->db->from($this->table);

        if (!empty($search)) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search);
                } else {
                    $this->db->or_like($item, $search);
                }
                if (count($this->column_search) - 1 == $i) $this->db->group_end();
                $i++;
            }
        }
        $this->db->order_by('id', 'DESC');
    }
    public function get_count_by_jenis($search = "")
    {
        $table = "nonlits";
        $this->db->select('jenis, COUNT(*) as jumlah');
        $this->db->from($table);

        if ($search) {
            $this->db->group_start();
            $this->db->like('permohonan_nonlit', $search);
            $this->db->or_like('alamat', $search);
            $this->db->group_end();
        }

        $this->db->group_by('jenis');
        $result = $this->db->get()->result();

        // Format agar mudah dibaca JS
        $counts = ['nonlit' => 0, 'laporan_polisi' => 0, 'permasalahan' => 0, 'data_umum' => 0];
        foreach ($result as $row) {
            if (isset($counts[$row->jenis])) {
                $counts[$row->jenis] = (int)$row->jumlah;
            }
        }
        return $counts;
    }
    function make_query()
    {
        $table = "nonlits";

        // TAMBAHKAN 'jenis' KE DALAM SELECT COLUMN DI BAWAH INI
        $select_column = "
        nonlits.id, 
        nonlits.jenis, 
        permohonan_nonlit, 
        tgl_nonlit, 
        penyimpanan_rak, 
        users.username, 
        team_nonlit, 
        status, 
        keterangan, 
        bidang, 
        pic, 
        alamat, 
        register_baru,
        luas,
        updated_at,
        det.kesimpulan,
        det.tgl_rapat as tgl_update_progres,
        (SELECT COUNT(id) FROM nonlit_det WHERE nonlit_det.id_nonlit = nonlits.id) AS total_det,
        (SELECT COUNT(id) FROM berkas_lampiran WHERE berkas_lampiran.id_nonlit = nonlits.id) AS total_berkas
    ";

        $this->db->select($select_column, FALSE);
        $this->db->from($table);
        $this->db->join('users', 'users.id = nonlits.updated_by', 'left');

        $this->db->join('(
        SELECT id_nonlit, kesimpulan, tgl_rapat
        FROM nonlit_det
        WHERE id IN (
            SELECT MAX(id) 
            FROM nonlit_det 
            GROUP BY id_nonlit
        )
    ) det', 'nonlits.id = det.id_nonlit', 'left');

        // Filter Atas
        if ($this->input->post('tahun') && $this->input->post('tahun') != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $this->input->post('tahun'));
        }
        if ($this->input->post('status')) {
            $this->db->where('status', $this->input->post('status'));
        }
        if ($this->input->post('pic')) {
            $this->db->where('pic', $this->input->post('pic'));
        }

        // Pencarian Global DataTables
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'pic', 'det.kesimpulan', 'register_baru');

        foreach ($column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        $this->db->order_by('nonlits.id', 'desc');
    }

    function make_datatables($search = '', $start = 0, $length = 10)
    {
        $this->make_query();
        if ($length != -1) $this->db->limit($length, $start);
        return $this->db->get()->result();
    }

    function get_filtered_data($search = '')
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    function get_all_data()
    {
        return $this->db->count_all_results('nonlits');
    }


    function insertdata($data)
    {
        $exe = $this->db->insert('nonlits', $data);
        $id = $this->db->insert_id();

        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }

    function update($data, $id)
    {

        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('nonlits', $data);
        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }

    function get_byid($id)
    {


        $id = $id;

        $table = "nonlits";
        $select_column = array(
            "nonlits.id",
            "nonlits.permohonan_nonlit",
            "nonlits.tgl_nonlit",
            "nonlits.team_nonlit",
            "nonlits.keterangan",
            "nonlit_det.id",
            "nonlit_det.id_nonlit",
            "nonlit_det.resume_rapat",
            "nonlit_det.judul_rapat",
            "nonlit_det.tgl_rapat",
            "nonlit_det.kesimpulan",
            "nonlit_det.berkas",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->join('nonlit_det', 'nonlit_det.id_nonlit = nonlits.id', 'left');

        $this->db->where('nonlit_det.id_nonlit', $id);
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function getAllByTahun($tahun)
    {


        $tahun = $tahun;

        $table = "nonlits";
        $select_column = array(
            "nonlits.id",
            "nonlits.permohonan_nonlit",
            "nonlits.tgl_nonlit",
            "nonlits.team_nonlit",
            "nonlits.keterangan",
            "nonlit_det.id",
            "nonlit_det.id_nonlit",
            "nonlit_det.resume_rapat",
            "nonlit_det.judul_rapat",
            "nonlit_det.tgl_rapat",
            "nonlit_det.kesimpulan",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->join('nonlit_det', 'nonlit_det.id_nonlit = nonlits.id', 'left');

        $this->db->where('YEAR(nonlits.tgl_nonlit)', $tahun);
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }


    function berkas_lampiran_by_id($id)
    {


        $id = $id;

        $table = "berkas_lampiran";
        $select_column = array(
            "id",
            "id_nonlit",
            "nama_berkas",
            "judul_berkas",
            "keterangan",
            "file_type",

        );
        $this->db->select($select_column);
        $this->db->from($table);
        $this->db->where('berkas_lampiran.id_nonlit', $id);
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_byid_nonlit($id)
    {


        $id = $id;

        $table = "nonlits";
        $select_column = array(
            "nonlits.id",
            "nonlits.permohonan_nonlit",
            "nonlits.tgl_nonlit",
            "nonlits.team_nonlit",
            "nonlits.keterangan",
            "nonlits.register_baru",
            "nonlits.penyimpanan_rak",
            "nonlits.kordinat",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->where('nonlits.id', $id);
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->row_array();
    }


    function get_det($id)
    {
        $id = $id;

        $table = "nonlit_det";
        $select_column = array(

            "nonlit_det.id",
            "nonlit_det.id_nonlit",
            "nonlit_det.resume_rapat",
            "nonlit_det.judul_rapat",
            "nonlit_det.tgl_rapat",
            "nonlit_det.kesimpulan",
            "nonlit_det.berkas",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->where('nonlit_det.id', $id);
        $query = $this->db->get();

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->row_array();
    }
    function get_det_berkas($id)
    {
        $id = $id;

        $table = "berkas_lampiran";
        $select_column = array(

            "berkas_lampiran.id",
            "berkas_lampiran.id_nonlit",
            "berkas_lampiran.judul_berkas",
            "berkas_lampiran.nama_berkas",

        );
        $this->db->select($select_column);
        $this->db->from($table);

        $this->db->where('berkas_lampiran.id', $id);
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->row_array();
    }

    function berkas_upload($id)
    {
        $id = $id;

        $table = "berkas_nonlit";
        $select_column = array(

            "nonlit_det.id",
            "nonlit_det.id_nonlit",
            "nonlit_det.resume_rapat",
            "nonlit_det.judul_rapat",
            "nonlit_det.tgl_rapat",
            "nonlit_det.kesimpulan",
            "berkas_nonlit.name",
            "berkas_nonlit.type_file",

        );
        $this->db->select($select_column);
        $this->db->from($table);
        $this->db->join('nonlit_det', 'nonlit_det.id = berkas_nonlit.id_nonlit_det', 'left');


        $this->db->where('nonlit_det.id', $id);
        $query = $this->db->get();

        return $query->row_array();
    }


    function upload_nonlit($data)
    {

        date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

        $this->db->insert('nonlit_det', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {

            $datanya = array(
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id')
            );
            $exe = $this->db->where('id', $data['id_nonlit']);
            $exe = $this->db->update('nonlits', $datanya);
        }

        return  $insert_id;
    }


    public function upload_berkas_nonlit($data)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->db->insert('berkas_lampiran', $data);
        $insert_id = $this->db->insert_id(); // Ambil ID berkas_lampiran yang baru di-insert

        if ($insert_id) {
            $datanya = array(
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id')
            );
            $this->db->where('id', $data['id_nonlit']);
            $this->db->update('nonlits', $datanya);
        }

        return $insert_id; // Kembalikan ID berkas lampiran ke controller
    }

    // function upload_berkas_nonlit($data)
    // {
    //     date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

    //     $this->db->insert('berkas_lampiran', $data);
    //     $insert_id = $this->db->insert_id();

    //     if ($insert_id) {

    //         $datanya = array(
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'updated_by' => $this->session->userdata('id')
    //         );
    //         $exe = $this->db->where('id', $data['id_nonlit']);
    //         $exe = $this->db->update('nonlits', $datanya);
    //     }

    //     return  $insert_id;
    // }


    function update_nonlit_det($data, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Ambil id_nonlit untuk update tabel induk
        $id_nonlit = $data['id_nonlit'];
        unset($data['id_nonlit']); // Hapus agar tidak ikut masuk ke tabel nonlit_det

        $this->db->where('id', $id);
        $exe = $this->db->update('nonlit_det', $data);

        if ($exe) {
            $datanya = array(
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id')
            );

            $this->db->where('id', $id_nonlit); // Gunakan variabel yang sudah di-unset
            $this->db->update('nonlits', $datanya);
            return '1';
        }
        return '0';
    }

    public function update_nonlit_lampiran($data, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Update lampiran
        $this->db->where('id', $id);
        $exe = $this->db->update('berkas_lampiran', $data);

        if ($exe) {
            // Update timestamp induk
            $datanya = [
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id')
            ];
            $this->db->where('id', $data['id_nonlit']);
            $this->db->update('nonlits', $datanya);
            return '1';
        }
        return '0';
    }

    public function get_berkas_by_id($id)
    {
        return $this->db->get_where('berkas_lampiran', ['id' => $id])->row();
    }

    function update_nonlit_lampiran2($data, $id)
    {
        date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('berkas_lampiran', $data);

        if ($exe) {
            $datanya = array(
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('id')
            );

            $exe2 = $this->db->where('id', $data['id_nonlit']);
            $exe2 = $this->db->update('nonlits', $datanya);
            return '1';
        } else {
            return '0';
        }
    }

    function hapus_data_det($id)
    {
        $id = $id;
        $exe = $this->db->where('id', $id);
        $exe = $this->db->delete('nonlit_det');
        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }
    function hapus_data_lampiran($id)
    {
        $id = $id;
        $exe = $this->db->where('id', $id);
        $exe = $this->db->delete('berkas_lampiran');
        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }


    function m_apinonlit_id($register_baru)
    {

        if ($register_baru == null) {
            $this->db->select('
        id as nonlits_id, 
        permohonan_nonlit, 
        tgl_nonlit, 
        penyimpanan_rak, 
        pic, 
        register_baru, 
        bidang, 
        status, 
        team_nonlit, 
        keterangan');
            // berkas.nama_berkas,
            // berkas.id as berkas_id, 
            // berkas.judul_berkas , 
            // det.id as id_det,  
            // det.id as nonlitdet_id, 
            // det.judul_rapat, 
            // det.berkas as detail_berkas
            $this->db->from('nonlits');
            // $this->db->join('nonlit_det det', 'det.id_nonlit = n.id', 'left');
            // $this->db->join('berkas_lampiran berkas', 'berkas.id_nonlit = n.id', 'left');
            $query = $this->db->get();

            // Organisasi data dalam format yang diinginkan
            return $query->result_array();
        } else {
            // Ambil data dari tabel nonlits
            $this->db->select('id, permohonan_nonlit, keterangan, register_baru, tgl_nonlit, 
        penyimpanan_rak, 
        pic,  
        bidang, 
        status, 
        team_nonlit');
            $this->db->from('nonlits');
            $this->db->where('register_baru', $register_baru);
            $query_nonlits = $this->db->get();
            $nonlits_data = $query_nonlits->result_array();

            // Ambil data dari tabel nonlit_det
            $this->db->select('id_nonlit, id as detail_id,tgl_rapat, judul_rapat, berkas as detail_berkas');
            $this->db->from('nonlit_det');
            $query_nonlit_det = $this->db->get();
            $nonlit_det_data = $query_nonlit_det->result_array();

            // Ambil data dari tabel berkas_lampiran
            $this->db->select('id_nonlit, id as berkas_id, nama_berkas, judul_berkas');
            $this->db->from('berkas_lampiran');
            $query_berkas = $this->db->get();
            $berkas_data = $query_berkas->result_array();

            // Organisasi data dalam format yang diinginkan
            $data = [];
            foreach ($nonlits_data as $row) {
                $nonlits_id = $row['id'];
                $data[$nonlits_id] = [
                    'permohonan_nonlit' => $row['permohonan_nonlit'],
                    'team_nonlit' => $row['team_nonlit'],
                    'keterangan' => $row['keterangan'],
                    'penyimpanan_rak' => $row['penyimpanan_rak'],
                    'tgl_nonlit' => $row['tgl_nonlit'],
                    'register_baru' => $row['register_baru'],
                    'pic' => $row['pic'],
                    'bidang' => $row['bidang'],
                    'status' => $row['status'],
                    'berkas' => [],
                    'detail' => []
                ];
            }

            foreach ($nonlit_det_data as $row) {
                $nonlits_id = $row['id_nonlit'];
                if (isset($data[$nonlits_id])) {
                    $data[$nonlits_id]['detail'][] = [
                        'id' => $row['detail_id'],
                        'judul' => $row['judul_rapat'],
                        'tgl_rapat' => $row['tgl_rapat'],
                        'berkas' => $row['detail_berkas'],
                        'file_detail' => base_url('assets/berkas_nonlit/' . $row['detail_berkas'])

                    ];
                }
            }

            foreach ($berkas_data as $row) {
                $nonlits_id = $row['id_nonlit'];
                if (isset($data[$nonlits_id])) {
                    $data[$nonlits_id]['berkas'][] = [
                        'id' => $row['berkas_id'],
                        'berkas' => $row['nama_berkas'],
                        'judul_berkas' => $row['judul_berkas'],
                        'file_detail' => base_url('assets/berkas_lampiran/' . $row['nama_berkas'])
                    ];
                }
            }

            // Kembalikan data dalam format array
            return array_values($data);
        }
    }



    public function get_all_data_nonlit()
    {
        // Query untuk mengambil data dengan join
        $this->db->select('
        n.id as nonlits_id, 
        n.permohonan_nonlit, 
        n.keterangan,
        berkas.nama_berkas,
        berkas.id as berkas_id, 
        berkas.judul_berkas , 
        det.id as id_det,  
        det.id as nonlitdet_id, 
        det.judul_rapat, 
        det.berkas as detail_berkas');
        $this->db->from('nonlits n');
        $this->db->join('nonlit_det det', 'det.id_nonlit = n.id', 'left');
        $this->db->join('berkas_lampiran berkas', 'berkas.id_nonlit = n.id', 'left');
        $query = $this->db->get();

        // Organisasi data dalam format yang diinginkan
        return $query->result_array();
        // // $a = $this->db->last_query($query);
        // // print_r($a);
        // // exit();
        // // Struktur data seperti di JSON
        // $data = [];
        // foreach ($result as $row) {
        //     $nonlits_id = $row['nonlits_id'];
        //     if (!isset($data[$nonlits_id])) {
        //         $data[$nonlits_id] = [
        //             'permohonan_nonlit' => $row['permohonan_nonlit'],
        //             'keterangan' => $row['keterangan'],
        //             'berkas' => [],
        //             'detail' => []
        //         ];
        //     }

        //     if ($row['berkas_id']) {
        //         $data[$nonlits_id]['berkas'][] = [
        //             'id_nonlit' => $row['nonlits_id'],
        //             'id_det' => $row['id_det'],
        //             'berkas' => $row['nama_berkas'],
        //         ];
        //     }

        //     if ($row['nonlitdet_id']) {
        //         $data[$nonlits_id]['detail'][] = [
        //             'id_nonlit' => $row['nonlits_id'],
        //             'id_berkas_nonlit' => $row['nonlitdet_id'],
        //             'judul' => $row['judul_rapat'],
        //             'berkas' => $row['detail_berkas'],
        //         ];
        //     }
        // }

        // // Kembalikan data dalam format array
        // return array_values($data);
    }




    /**
     * Perpustakaan Berkas - Global Search
     */
    public function cari_pustaka_berkas($keyword = '', $kategori = 'ALL', $kriteria = 'ALL', $limit = 20, $offset = 0)
    {
        if (empty(trim($keyword))) {
            return [];
        }

        // Ambil nama database utama yang sedang aktif di CI (misal: db_nonlit)
        $db_main = $this->db->database;

        $keyword_param = '%' . $this->db->escape_like_str($keyword) . '%';
        $unions = [];
        $params = [];

        // 1. NONLIT_DET
        if ($kategori === 'ALL' || $kategori === 'NONLIT') {
            $sql_nonlit = "
                SELECT 
                    nd.id as id_file,
                    'NONLIT' as kategori,
                    n.id as id_perkara,
                    n.register_baru as nomor_register,
                    n.permohonan_nonlit as nama_pihak,
                    COALESCE(NULLIF(nd.judul_rapat, ''), nd.berkas) as label_berkas,
                    nd.berkas as nama_file,
                    'pdf' as tipe_file,
                    CONCAT('assets/berkas_nonlit/', nd.berkas) as file_path
                FROM `{$db_main}`.nonlit_det nd
                JOIN `{$db_main}`.nonlits n ON CAST(n.id AS CHAR) = nd.id_nonlit
                WHERE nd.berkas IS NOT NULL AND nd.berkas != ''
            ";

            if ($kriteria === 'PIHAK') {
                $sql_nonlit .= " AND n.permohonan_nonlit LIKE ?";
                $params[] = $keyword_param;
            } else if ($kriteria === 'NOMOR') {
                $sql_nonlit .= " AND n.register_baru LIKE ?";
                $params[] = $keyword_param;
            } else if ($kriteria === 'MASALAH') {
                $sql_nonlit .= " AND (n.keterangan LIKE ? OR nd.kesimpulan LIKE ?)";
                array_push($params, $keyword_param, $keyword_param);
            } else if ($kriteria === 'BERKAS') {
                $sql_nonlit .= " AND (nd.berkas LIKE ? OR nd.judul_rapat LIKE ?)";
                array_push($params, $keyword_param, $keyword_param);
            } else {
                // ALL (Semua Field)
                $sql_nonlit .= " AND (nd.berkas LIKE ? OR nd.judul_rapat LIKE ? OR n.register_baru LIKE ? OR n.permohonan_nonlit LIKE ? OR n.keterangan LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param, $keyword_param, $keyword_param);
            }

            $unions[] = $sql_nonlit;
        }

        // 2. BERKAS_LAMPIRAN
        if ($kategori === 'ALL' || $kategori === 'LAMPIRAN') {
            $sql_lampiran = "
                SELECT 
                    bl.id as id_file,
                    'LAMPIRAN' as kategori,
                    n.id as id_perkara,
                    n.register_baru as nomor_register,
                    n.permohonan_nonlit as nama_pihak,
                    COALESCE(NULLIF(bl.judul_berkas, ''), bl.nama_berkas) as label_berkas,
                    bl.nama_berkas as nama_file,
                    bl.file_type as tipe_file,
                    CONCAT('assets/berkas_lampiran/', bl.nama_berkas) as file_path
                FROM `{$db_main}`.berkas_lampiran bl
                JOIN `{$db_main}`.nonlits n ON CAST(n.id AS CHAR) = bl.id_nonlit
                WHERE bl.nama_berkas IS NOT NULL AND bl.nama_berkas != ''
            ";

            if ($kriteria === 'PIHAK') {
                $sql_lampiran .= " AND n.permohonan_nonlit LIKE ?";
                $params[] = $keyword_param;
            } else if ($kriteria === 'NOMOR') {
                $sql_lampiran .= " AND n.register_baru LIKE ?";
                $params[] = $keyword_param;
            } else if ($kriteria === 'MASALAH') {
                $sql_lampiran .= " AND (n.keterangan LIKE ? OR bl.keterangan LIKE ?)";
                array_push($params, $keyword_param, $keyword_param);
            } else if ($kriteria === 'BERKAS') {
                $sql_lampiran .= " AND (bl.nama_berkas LIKE ? OR bl.judul_berkas LIKE ?)";
                array_push($params, $keyword_param, $keyword_param);
            } else {
                // ALL (Semua Field)
                $sql_lampiran .= " AND (bl.nama_berkas LIKE ? OR bl.judul_berkas LIKE ? OR n.register_baru LIKE ? OR n.permohonan_nonlit LIKE ? OR n.keterangan LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param, $keyword_param, $keyword_param);
            }

            $unions[] = $sql_lampiran;
        }

        // 3. LITIGASI ASING (db_perkara.t_upload)
        if ($kategori === 'ALL' || $kategori === 'ASING') {
            $sql_asing = "
                SELECT 
                    tu.id_berkas as id_file,
                    'ASING' as kategori,
                    tp.perkara_id as id_perkara,
                    COALESCE(NULLIF(tpd.perkaradet_no, ''), tp.perkara_no, tp.register_baru) as nomor_register,
                    CONCAT(COALESCE(tp.perkara_penggugat, ''), ' vs ', COALESCE(tp.perkara_tergugat, '')) as nama_pihak,
                    COALESCE(NULLIF(tpd.perkaradet_no, ''), tu.name_berkas, tp.perkara_no) as label_berkas,
                    tu.name_berkas as nama_file,
                    tu.type_file as tipe_file,
                    CONCAT('https://assistdpbt.surabaya.go.id/asing/assets/upload/', tu.name_berkas) as file_path
                FROM db_perkara.t_upload tu
                LEFT JOIN db_perkara.t_perkara tp ON CAST(tp.perkara_id AS CHAR) = tu.berkas_perkara_id
                LEFT JOIN db_perkara.t_perkara_detail tpd ON CAST(tpd.perkaradet_id AS CHAR) = tu.berkas_perkaradet_id
                WHERE tu.name_berkas IS NOT NULL AND tu.name_berkas != ''
            ";

            if ($kriteria === 'PIHAK') {
                $sql_asing .= " AND (tp.perkara_penggugat LIKE ? OR tp.perkara_tergugat LIKE ? OR tp.perkara_pihak LIKE ? OR tpd.perkaradet_pihak LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param, $keyword_param);
            } else if ($kriteria === 'NOMOR') {
                $sql_asing .= " AND (tp.perkara_no LIKE ? OR tp.register_baru LIKE ? OR tpd.perkaradet_no LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param);
            } else if ($kriteria === 'MASALAH') {
                $sql_asing .= " AND (tp.perkara_objek LIKE ? OR tp.perkara_jenis LIKE ? OR tpd.perkaradet_keterangan LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param);
            } else if ($kriteria === 'BERKAS') {
                $sql_asing .= " AND tu.name_berkas LIKE ?";
                $params[] = $keyword_param;
            } else {
                // ALL (Semua Field)
                $sql_asing .= " AND (tu.name_berkas LIKE ? OR tp.perkara_no LIKE ? OR tp.register_baru LIKE ? OR tpd.perkaradet_no LIKE ? OR tp.perkara_penggugat LIKE ? OR tp.perkara_tergugat LIKE ? OR tp.perkara_objek LIKE ?)";
                array_push($params, $keyword_param, $keyword_param, $keyword_param, $keyword_param, $keyword_param, $keyword_param, $keyword_param);
            }

            $unions[] = $sql_asing;
        }

        if (empty($unions)) {
            return [];
        }

        $full_sql = "SELECT * FROM (" . implode(" UNION ALL ", $unions) . ") as global_search ORDER BY id_file DESC LIMIT ? OFFSET ?";
        array_push($params, (int)$limit, (int)$offset);

        $query = $this->db->query($full_sql, $params);

        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return ($query && is_object($query)) ? $query->result() : [];
    }
}
