<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_nonlit extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


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
    function make_query()
    {
        $table = "nonlits";

        // Select column
        $select_column = "
        nonlits.id, 
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
        det.tgl_rapat as tgl_update_progres";

        $this->db->select($select_column);
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

        // Filter dari Pencarian Filter Atas
        if ($this->input->post('tahun') && $this->input->post('tahun') != 'all') {
            $this->db->where('YEAR(tgl_nonlit)', $this->input->post('tahun'));
        }
        if ($this->input->post('status')) {
            $this->db->where('status', $this->input->post('status'));
        }
        if ($this->input->post('pic')) {
            $this->db->where('pic', $this->input->post('pic'));
        }

        // --- PERBAIKAN DI SINI ---
        $i = 0;
        // Ganti 'det.progres_terakhir' menjadi 'det.kesimpulan' agar sesuai dengan alias join
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

    function make_datatables()
    {

        $this->make_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $i = 0;
        $column_search = array('team_nonlit', 'permohonan_nonlit', 'status', 'keterangan', 'tgl_nonlit', 'register_baru', 'penyimpanan_rak');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $query = $this->db->get();



        return $query->num_rows();
    }
    function get_all_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $this->db->count_all_results();
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

    function upload_berkas_nonlit($data)
    {
        date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

        $this->db->insert('berkas_lampiran', $data);
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


    function update_nonlit_det($data, $id)
    {

        date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 
        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('nonlit_det', $data);

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
    function update_nonlit_lampiran($data, $id)
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
}
