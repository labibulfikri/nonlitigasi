<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Nonlit extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_nonlit');
        $this->load->model('m_home');
        $this->load->model('m_peta');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }
    public function index()
    {

        if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else {
            $data = array(
                'masterpage' => 'layout/layout2',
                'navbar2' => 'layout/navbar2',
                'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'nonlit/data_nonlit',
                'footer' => 'layout/footer',
                'title' => 'Daftar Nonlitigasi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }


    function fetch_nonlit()
    {
        cek_csrf();



        $fetch_data = $this->m_nonlit->make_datatables();

        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {

            $no++;
            $sub_array = array();
            $sub_array['no'] = $no;
            $sub_array['permohonan_nonlit'] = "<strong> Permohonan Nonlit : </strong> <p>$row->permohonan_nonlit </p><br/> <strong> Nomor Register : </strong> $row->register_baru <br/> <strong> Team: </strong> <p> $row->team_nonlit</p>";
            $sub_array['register_baru'] = $row->register_baru;
            $sub_array['keterangan'] = $row->keterangan;
            $sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
            $sub_array['bidang'] = $row->bidang;
            $sub_array['pic'] = $row->pic;
            $sub_array['updated_by'] = "terakhir diupdate <strong>" . $row->updated_at . "</strong> - Oleh :" .  $row->username;
            $sub_array['updated_at'] = $row->updated_at;
            $sub_array['status'] = $row->status;
            $sub_array['team_nonlit'] = $row->team_nonlit;
            $sub_array['id'] = "<button data-bs-toggle='modal' data-bs-target='#modal_edit' id='editButton' data-id='$row->id' data-tglnonlit='$row->tgl_nonlit' data-penyimpananrak='$row->penyimpanan_rak' data-permohonan='$row->permohonan_nonlit' data-alamat='$row->alamat' data-luas='$row->luas' data-status='$row->status' data-bidang='$row->bidang' data-pic='$row->pic' data-registerbaru='$row->register_baru'  data-keterangan='$row->keterangan' data-team='$row->team_nonlit' class='btn btn-warning'> Edit </button>| <a href='" . base_url('nonlit/detail/' . $row->id) . "' class='btn btn-primary btn-sm'> Detail </a>|
            <a class='btn btn-sm btn-primary' href=' " . base_url('peta/edit/' . $row->id) . " '>Edit Peta </a>
            <button type='button' class='btn btn-sm btn-danger tombol_hapus' id_nonlit='$row->id'> <i data-toggle='tooltip' title='Hapus' class='icofont-trash'></i> Hapus </button>";
            // $sub_array[] = '';


            $data[] = $sub_array;
        }
        // <a class='btn btn-sm btn-primary' href=' " . base_url('peta/map_by_id/' . $row->id) . " '>Edit Peta GIS</a>
        // <a class='btn btn-sm btn-primary' href='https://sigis.surabaya.go.id/popup/simbada/show-no-reg/$row->register_baru' target='_blank' rel='noopener noreferrer' > peta</a>

        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->m_nonlit->get_all_data(),
            "recordsFiltered"           =>     $this->m_nonlit->get_filtered_data(),
            "data"                      =>     $data,
        );
        echo json_encode($output);
    }



    function tambah_data_nonlit()
    {

        $this->form_validation->set_rules('permohonan_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('tgl_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('team_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('status', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('bidang', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('keterangan', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('register_baru', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('luas', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('pic', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('penyimpanan_rak', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('alamat', 'Harus Di Isi', 'required');
        // // echo "<script type='text/javascript'>
        // //     alert(' Harus di isi semua field ');
        // //     window.location.href ='" . base_url('nonlit') . "';
        // //     </script>";

        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
            echo json_encode(array('status' => 'gagal', 'message' => 'Harus di isi.'));
        } else {
            cek_csrf();
            $data = array(
                'permohonan_nonlit' => $this->input->post('permohonan_nonlit', TRUE),
                // 'token' => $this->input->post('token', TRUE),
                'bidang' => $this->input->post('bidang', TRUE),
                'status' => $this->input->post('status', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'team_nonlit' => $this->input->post('team_nonlit', TRUE),
                'tgl_nonlit' => $this->input->post('tgl_nonlit', TRUE),
                'register_baru' => $this->input->post('register_baru', TRUE),
                'luas' => $this->input->post('luas', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'pic' => $this->input->post('pic', TRUE),
                'penyimpanan_rak' => $this->input->post('penyimpanan_rak', TRUE),
            );



            $exe = $this->m_nonlit->insertdata($data);
            if ($exe > 0) {
                $response = array('status' => 'success', 'message' => 'Data berhasil disimpan.');
                echo json_encode($response);
            }
        }
    }


    function update_data()
    {


        $this->form_validation->set_rules('id', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('permohonan_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('tgl_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('team_nonlit', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('bidang', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('status', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('register_baru', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('pic', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('alamat', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('luas', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('penyimpanan_rak', 'Harus Di Isi', 'required');


        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
            echo json_encode(array('status' => 'gagal', 'message' => 'Harus di isi.'));
        } else {
            cek_csrf();

            date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

            $id = $this->input->post('id', TRUE);
            $permohonan_nonlit = $this->input->post('permohonan_nonlit', TRUE);
            $tgl_nonlit = $this->input->post('tgl_nonlit', TRUE);
            $team_nonlit = $this->input->post('team_nonlit', TRUE);
            $bidang = $this->input->post('bidang', TRUE);
            $status = $this->input->post('status', TRUE);
            $register_baru = $this->input->post('register_baru', TRUE);
            $keterangan = $this->input->post('keterangan', TRUE);
            $luas = $this->input->post('luas', TRUE);
            $pic = $this->input->post('pic', TRUE);
            $alamat = $this->input->post('alamat', TRUE);
            $penyimpanan_rak = $this->input->post('penyimpanan_rak', TRUE);
            $updated_at = date('Y-m-d H:i:s');
            $updated_by = $this->session->userdata('id');



            $data  = array(
                "permohonan_nonlit" => $permohonan_nonlit,
                "tgl_nonlit" => $tgl_nonlit,
                "bidang" => $bidang,
                "keterangan" => $keterangan,
                "status" => $status,
                "register_baru" => $register_baru,
                "pic" => $pic,
                "id" => $id,
                "alamat" => $alamat,
                "penyimpanan_rak" => $penyimpanan_rak,
                "luas" => $luas,
                "team_nonlit" => $team_nonlit,
                "updated_at" => $updated_at,
                "updated_by" => $updated_by
            );


            $exe = $this->m_nonlit->update($data, $id);
            if ($exe > 0) {
                $response = array('status' => 'success', 'message' => 'Data berhasil disimpan.');
                echo json_encode($response);
            }
        }
    }


    public function detail($id)
    {

        if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else {

            $id = $id;

            $fetch_detail = $this->m_nonlit->get_byid($id);
            $fetch = $this->m_nonlit->get_byid_nonlit($id);
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

            $data = array(
                'master' => $fetch,
                'id' => $id,
                'det' => $fetch_detail,
                'list' => $decoded_data,
                'polygon' => json_encode($polygon),
                'masterpage' => 'layout/layout2',
                'navbar2' => 'layout/navbar2',
                'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'nonlit/detail',
                'peta' => 'nonlit/peta_detail',
                'footer' => 'layout/footer',
                'tab' => 'layout/tab_detail',
                'title' => 'Daftar Nonlitigasi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }


    public function tab_kronologi($id)
    {

        if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else {

            $id = $id;
            $lampiran = $this->m_nonlit->berkas_lampiran_by_id($id);

            $fetch_detail = $this->m_nonlit->get_byid($id);
            $fetch = $this->m_nonlit->get_byid_nonlit($id);
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

            $data = array(
                'masterpage' => 'layout/layout2',
                'navbar2' => 'layout/navbar2',
                'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'nonlit/tab_kronologi',
                'footer' => 'layout/footer',
                'master' => $fetch,
                'id' => $id,
                'lampiran' => $lampiran,
                'list' => $decoded_data,
                'polygon' => json_encode($polygon),
                'peta' => 'nonlit/peta_detail',
                'title' => 'Daftar Nonlitigasi',
                'tab' => 'layout/tab_detail'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    function get_content()
    {
        $this->form_validation->set_rules('id', 'Harus Di Isi', 'required');
        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();
            $id = $this->input->post('id', TRUE);

            $get_det = $this->m_nonlit->get_det($id);


            $content = ' 
            <div class="card"> 
            <div class="card-header">
                            <h4 class="card-header">Hasil Rapat</h4> 
            <button data-bs-toggle="modal" data-bs-target="#modal_edit_nonlit_det" id="btnEditDet" data-id="' . $get_det['id'] . '" data-tglrapat="' . $get_det['tgl_rapat'] . '" data-judulrapat="' . $get_det['judul_rapat'] . '" data-kesimpulan="' . $get_det['kesimpulan'] . '" data-berkas="' . $get_det['berkas'] . '" data-idnonlit="' . $get_det['id_nonlit'] . '"  class="btn btn-warning "> edit</button> 
            | <button role="button" class="hapus_det btn btn-danger"  id="' . $get_det['id'] . '" data-idnonlit="' . $get_det['id_nonlit'] . '"> Hapus </button>
            </div>            
            <div class="card-body">
              
            <div class="form-group">
            <label for="exampleInputUsername1">Tanggal Rapat</label>
            
            <input hidden type="text" value="' . $get_det['id'] . '" class="form-control">
            <input disabled type="text" value="' . $get_det['tgl_rapat'] . '" class="form-control">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Judul Rapat</label>
            <input type="text" value="' . $get_det['judul_rapat'] . '"  class="form-control" disabled>
          </div> 

          <div class="form-group">
          <label for="exampleInputEmail1">Kesimpulan </label>
          <span class="form-control">' . $get_det['kesimpulan'] . '</span>
          </div>  
          <hr />
          <h4 class="card-title"> Hasil Rapat</h4>';
            if ($get_det['berkas'] == null || !$get_det['berkas']) {
                $content .= '<h1 class="text-center"> TIdak ada data </h1>';
            } else {

                $content .= '<iframe class="mb-20" id="frame" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen="" src="' . base_url('assets/berkas_nonlit/' . $get_det['berkas']) . '"></iframe>';
            }
            $content .= '
            
            <br />
            <br />
          <br />
          <br />
          </div>
          </div>
          
          <br />
          <br />
          ';

            //   <textarea class="form-control" disabled> "' . $get_det['kesimpulan'] . '" </textarea>

            echo $content;
        }
    }
    function get_content_berkas()
    {
        $this->form_validation->set_rules('id', 'Harus Di Isi', 'required');
        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();
            $id = $this->input->post('id', TRUE);

            $get_det = $this->m_nonlit->get_det_berkas($id);



            $content = ' 
            <div class="card"> 
            <div class="card-header">
                            <h4 class="card-header"> Lampiran Berkas</h4> 
            <button data-bs-toggle="modal" data-bs-target="#modal_edit_lampiran" id="btnEditLampiran" data-idnonlit="' . $get_det['id_nonlit'] . '" data-id="' . $get_det['id'] . '" data-judul_berkas="' . $get_det['judul_berkas'] . '" data-nama_berkas="' . $get_det['nama_berkas'] . '"   class="btn btn-warning "> edit</button> 
            | <button role="button" class="hapus_lampiran btn btn-danger"  id="' . $get_det['id'] . '" data-idnonlit="' . $get_det['id_nonlit'] . '"> Hapus </button>
            </div>            
            <div class="card-body">
              
            <div class="form-group">
            <label >Judul Berkas </label>
            
            <input hidden type="text" value="' . $get_det['id'] . '" class="form-control">
            <input disabled type="text" value="' . $get_det['judul_berkas'] . '" class="form-control">
          </div>
           
          <h4 class="card-title"> Lampiran </h4>';
            if ($get_det['nama_berkas'] == null || !$get_det['nama_berkas']) {
                $content .= '<h1 class="text-center"> TIdak ada data </h1>';
            } else {

                $content .= '<iframe class="mb-20" id="frame" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen="" src="' . base_url('assets/berkas_lampiran/' . $get_det['nama_berkas']) . '"></iframe>';
            }
            $content .= '
           
          <br />
          <br />
          <br />
          <br />
          </div>
          </div>
          
          <br />
          <br />
          ';


            echo $content;
        }
    }

    function upload_berkas()
    {
        $this->form_validation->set_rules('id_nonlit', 'Harus Di Isi', 'required');

        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();

            $id_nonlit = $this->input->post('id_nonlit', TRUE);

            $config['upload_path'] = './assets/berkas_nonlit/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 10000;
            $config['file_name'] = 'NONLIT-' . date('dmY') . '-' . substr(
                md5(rand()),
                0,
                10
            );


            $this->load->library('upload', $config);
            if ($this->upload->do_upload('file')) {

                $fileData = $this->upload->data();

                $tgl_rapat = $this->input->post('tgl_rapat', TRUE);
                $id_nonlit = $this->input->post('id_nonlit', TRUE);
                $judul_rapat = $this->input->post('judul_rapat', TRUE);
                $kesimpulan = $this->input->post('kesimpulan', TRUE);

                $data  = array(
                    "id_nonlit" => $id_nonlit,
                    "tgl_rapat" => $tgl_rapat,
                    "judul_rapat" => $judul_rapat,
                    "kesimpulan" => $kesimpulan,
                    "berkas" => $this->upload->data('file_name')
                );


                $dt = $this->m_nonlit->upload_nonlit($data);


                if ($dt > 1) {
                    echo "<script type='text/javascript'>
                        alert(' Berhasil, Berhasil Menambahkan Data :) ');
                        window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                        </script>";
                } else {
                    echo "<script type='text/javascript'>
                        alert(' Gagal, Filenya Harus PDF lohh :( ');
                        window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                        </script>";
                }
            } else {
                $tgl_rapat = $this->input->post('tgl_rapat', TRUE);
                $id_nonlit = $this->input->post('id_nonlit', TRUE);
                $judul_rapat = $this->input->post('judul_rapat', TRUE);
                $kesimpulan = $this->input->post('kesimpulan', TRUE);

                $data  = array(
                    "id_nonlit" => $id_nonlit,
                    "tgl_rapat" => $tgl_rapat,
                    "judul_rapat" => $judul_rapat,
                    "kesimpulan" => $kesimpulan
                );
                $dt = $this->m_nonlit->upload_nonlit($data);
                echo "<script type='text/javascript'>
                alert(' Berhasil, Berhasil Menambahkan Data :)');
                window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                </script>";
            }
        }
    }
    function upload_berkas_lampiran()
    {
        $this->form_validation->set_rules('id_nonlit', 'Harus Di Isi', 'required');

        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();

            $id_nonlit = $this->input->post('id_nonlit', TRUE);

            $config['upload_path'] = './assets/berkas_lampiran/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 10000;
            $config['file_name'] = 'BERKAS-NONLIT-' . date('dmY') . '-' . substr(
                md5(rand()),
                0,
                10
            );


            $this->load->library('upload', $config);
            if ($this->upload->do_upload('file')) {

                $fileData = $this->upload->data();

                $nama_berkas =  $this->upload->data('file_name');
                $id_nonlit = $this->input->post('id_nonlit', TRUE);
                $keterangan = $this->input->post('keterangan', TRUE);
                $judul_berkas = $this->input->post('judul_berkas', TRUE);


                $data  = array(
                    "id_nonlit" => $id_nonlit,
                    "nama_berkas" => $nama_berkas,
                    "keterangan" => $keterangan,
                    "judul_berkas" => $judul_berkas,
                    // "berkas" => $this->upload->data('file_name')
                );



                $dt = $this->m_nonlit->upload_berkas_nonlit($data);


                if ($dt > 1) {
                    echo "<script type='text/javascript'>
                        alert(' Berhasil, Berhasil Menambahkan Data :) ');
                        window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
                        </script>";
                } else {
                    echo "<script type='text/javascript'>
                        alert(' Gagal, Filenya Harus PDF lohh :( ');
                        window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
                        </script>";
                }
            } else {
                $nama_berkas = $this->upload->data('file_name');
                $id_nonlit = $this->input->post('id_nonlit', TRUE);
                $keterangan = $this->input->post('keterangan', TRUE);
                $judul_berkas = $this->input->post('judul_berkas', TRUE);
                $data  = array(
                    "id_nonlit" => $id_nonlit,
                    "nama_berkas" => $nama_berkas,
                    "keterangan" => $keterangan,
                    "judul_berkas" => $judul_berkas,
                );
                $dt = $this->m_nonlit->upload_berkas_nonlit($data);
                echo "<script type='text/javascript'>
                alert(' Berhasil, Berhasil Menambahkan Data :)');
                window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
                </script>";
            }
        }
    }

    function update_nonlit_det()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id', 'harus di isi', 'required');
        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();

            $config['upload_path'] = './assets/berkas_nonlit/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5000;
            $config['file_name'] = 'NONLIT-' . date('dmY') . '-' . substr(
                md5(rand()),
                0,
                10
            );

            $id_nonlit = $this->input->post('id_nonlit', TRUE);
            $id = $this->input->post('id', TRUE);
            $tgl_rapat = $this->input->post('tgl_rapat', TRUE);
            $judul_rapat = $this->input->post('judul_rapat', TRUE);
            $kesimpulan = $this->input->post('kesimpulan', TRUE);
            $old_image = $this->input->post('old_image');




            $this->load->library('upload', $config);

            if ($_FILES['new_image']['name'] != null) {

                if ($this->upload->do_upload('new_image')) {

                    if (file_exists("./assets/berkas_nonlit/" . $old_image)) {
                        unlink("./assets/berkas_nonlit/" . $old_image);
                    }

                    $data = array(
                        'id' => $id,
                        'id_nonlit' => $id_nonlit,
                        'tgl_rapat' => $tgl_rapat,
                        'judul_rapat' => $judul_rapat,
                        'kesimpulan' => $kesimpulan,
                        'berkas' => $this->upload->data('file_name')
                    );
                    $this->m_nonlit->update_nonlit_det($data, $id);

                    echo "<script type='text/javascript'>
                            alert(' Berhasil ');
                            window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                </script>";
                }
            } else {
                $data = array(
                    'id' => $id,
                    'id_nonlit' => $id_nonlit,
                    'tgl_rapat' => $tgl_rapat,
                    'judul_rapat' => $judul_rapat,
                    'kesimpulan' => $kesimpulan,
                );
                $this->m_nonlit->update_nonlit_det($data, $id);

                echo "<script type='text/javascript'>
                            alert(' Berhasil ');
                            
                    window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                            </script>";
            }
        }
    }



    function update_berkas_lampiran()
    {

        $this->form_validation->set_rules('id', 'harus di isi', 'required');
        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
        } else {
            cek_csrf();

            $config['upload_path'] = './assets/berkas_lampiran/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5000;
            $config['file_name'] = 'NONLIT-' . date('dmY') . '-' . substr(
                md5(rand()),
                0,
                10
            );

            $id_nonlit = $this->input->post('id_nonlit', TRUE);


            $id = $this->input->post('id', TRUE);
            $judul_berkas = $this->input->post('judul_berkas', TRUE);
            // $nama_berkas = $this->input->post('nama_berkas', TRUE);
            $old_image = $this->input->post('old_image');




            $this->load->library('upload', $config);

            if ($_FILES['new_image']['name'] != null) {

                if ($this->upload->do_upload('new_image')) {

                    if (file_exists("./assets/berkas_lampiran/" . $old_image)) {
                        unlink("./assets/berkas_lampiran/" . $old_image);
                    }

                    $data = array(
                        'id' => $id,
                        'id_nonlit' => $id_nonlit,

                        'judul_berkas' => $judul_berkas,
                        'nama_berkas' => $this->upload->data('file_name')
                    );
                    $this->m_nonlit->update_nonlit_lampiran($data, $id);

                    echo "<script type='text/javascript'>
                            alert(' Berhasil ');
                            window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
                </script>";
                }
            } else {

                $data = array(
                    'id' => $id,
                    'id_nonlit' => $id_nonlit,
                    'judul_berkas' => $judul_berkas
                );
                $this->m_nonlit->update_nonlit_lampiran($data, $id);

                echo "<script type='text/javascript'>
                        alert(' Berhasil ');
                        window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
            </script>";
            }
        }
    }


    function hapus_det()
    {
        cek_csrf();
        $id = $this->input->post('id');
        $id_nonlit = $this->input->post('id_nonlit');
        $exe = $this->m_nonlit->hapus_data_det($id);

        if ($exe > 0) {
            echo "<script type='text/javascript'>
                            alert(' Berhasil ');
                            
                    window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
                            </script>";
        }
    }




    function hapus_lampiran()
    {
        cek_csrf();
        $id = $this->input->post('id');
        $id_nonlit = $this->input->post('id_nonlit');
        $exe = $this->m_nonlit->hapus_data_lampiran($id);

        if ($exe > 0) {
            echo "<script type='text/javascript'>
                            alert(' Berhasil ');
                            
                    window.location.href ='" . base_url('nonlit/tab_kronologi/' . $id_nonlit) . "';
                            </script>";
        }
    }


    //Untuk menghapus foto
    function remove_nonlit()
    {

        //Ambil token foto
        $id_nonlit = $this->input->post('id_nonlit');



        //$foto=$this->db->get_where('t_nonlit',array('id_nonlit'=>$token));


        //if($foto->num_rows()>0){

        $this->db->delete('nonlits', array('id' => $id_nonlit));

        //}
        echo "{}";
    }
}
