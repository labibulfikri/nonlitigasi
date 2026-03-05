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
                // 'navbar2' => 'layout/navbar2',
                // 'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'nonlit/data_nonlit',
                // 'footer' => 'layout/footer',
                'title' => 'Daftar Nonlitigasi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }


    function fetch_nonlit()
    {
        // 1. Cek Keamanan
    cek_csrf();

    // 2. Ambil data dari model (tetap gunakan fungsi yang sudah ada)
    $fetch_data = $this->m_nonlit->make_datatables();

    $data = array();
    $no = $_POST['start'] ?? 0;

    foreach ($fetch_data as $row) {
        $no++;
        $sub_array = array();
        
        // Kirim data mentah (gunakan strip_tags agar tidak ada <strong> atau <p>)
        $sub_array['no']                = $no;
        $sub_array['id']                = $row->id; 
        $sub_array['register_baru']     = $row->register_baru;
        $sub_array['alamat']     = $row->alamat;
        $sub_array['kesimpulan']     = $row->kesimpulan;
        $sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit)); // Untuk tampilan di Card
    $sub_array['tgl_nonlit_raw'] = date('Y-m-d', strtotime($row->tgl_nonlit)); // UNTUK INPUT MODAL
        $sub_array['bidang']            = $row->bidang;
        $sub_array['luas']            = $row->luas;
        $sub_array['permohonan_nonlit'] = strtoupper(strip_tags($row->permohonan_nonlit)); // Paksa Uppercase biar tegas
$sub_array['pic'] = $row->pic ?: 'N/A';
$sub_array['penyimpanan_rak'] = $row->penyimpanan_rak ?: 'BELUM DIATUR';
        $sub_array['status']            = strtolower($row->status);
        $sub_array['team_nonlit']       = $row->team_nonlit;
        $sub_array['keterangan']        = strip_tags($row->keterangan ?? '');

        $data[] = $sub_array;
    }

    $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsTotal"    => $this->m_nonlit->get_all_data(),
        "recordsFiltered" => $this->m_nonlit->get_filtered_data(),
        "data"            => $data,
    );

    header('Content-Type: application/json');
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
        // $this->form_validation->set_rules('alamat', 'Harus Di Isi', 'required');
        // $this->form_validation->set_rules('luas', 'Harus Di Isi', 'required');
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
                // 'navbar2' => 'layout/navbar2',
                // 'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'nonlit/detail',
                'peta' => 'nonlit/peta_detail',
                'footer' => 'layout/footer',
                'tab' => 'nonlit/tab_detail',
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
                'tab' => 'nonlit/tab_detail'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    public function get_content()
{
    $this->form_validation->set_rules('id', 'ID Harus Di Isi', 'required');
    cek_csrf(); // Panggil sekali di atas untuk efisiensi

    if ($this->form_validation->run() == FALSE) {
        echo '<div class="alert alert-error">ID tidak ditemukan.</div>';
    } else {
        $id = $this->input->post('id', TRUE);
        $get_det = $this->m_nonlit->get_det($id);

        if (!$get_det) {
            echo '<div class="alert alert-warning text-sm uppercase font-bold">Data tidak ditemukan.</div>';
            return;
        }

        // Mulai Generate Content dengan DaisyUI
        $content = '
        <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden animate-in fade-in duration-500">
            <div class="bg-base-200/50 p-4 border-b border-base-200 flex flex-wrap justify-between items-center gap-2">
                <div class="flex items-center gap-3">
                    <div class="bg-primary text-primary-content p-2 rounded-lg">
                        <i class="mdi mdi-text-box-search text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-700 uppercase tracking-tight">Detail Notulensi Rapat</h3>
                        <p class="text-[10px] opacity-60 uppercase font-bold tracking-widest">ID: #'.$get_det['id'].'</p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button data-bs-toggle="modal" data-bs-target="#modal_edit_nonlit_det" id="btnEditDet" 
                        data-id="' . $get_det['id'] . '" 
                        data-tglrapat="' . $get_det['tgl_rapat'] . '" 
                        data-judulrapat="' . $get_det['judul_rapat'] . '" 
                        data-kesimpulan="' . htmlspecialchars($get_det['kesimpulan']) . '" 
                        data-berkas="' . $get_det['berkas'] . '" 
                        data-idnonlit="' . $get_det['id_nonlit'] . '"  
                        class="btn btn-warning btn-sm shadow-md text-white rounded-xl">
                        <i class="mdi mdi-pencil"></i> Edit
                    </button>
                    <button type="button" class="hapus_det btn btn-error btn-sm shadow-md text-white rounded-xl" 
                        id="' . $get_det['id'] . '" 
                        data-idnonlit="' . $get_det['id_nonlit'] . '"> 
                        <i class="mdi mdi-trash-can"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="card-body p-6 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase text-slate-500">Tanggal Rapat</span></label>
                        <div class="flex items-center gap-3 bg-base-200 p-3 rounded-2xl border border-base-300">
                            <i class="mdi mdi-calendar text-primary"></i>
                            <span class="font-bold text-slate-800">' . date('d F Y', strtotime($get_det['tgl_rapat'])) . '</span>
                        </div>
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase text-slate-500">Judul / Acara Rapat</span></label>
                        <div class="flex items-center gap-3 bg-base-200 p-3 rounded-2xl border border-base-300">
                            <i class="mdi mdi-tag-outline text-primary"></i>
                            <span class="font-black text-slate-800 uppercase">' . $get_det['judul_rapat'] . '</span>
                        </div>
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase text-slate-500">Kesimpulan Utama</span></label>
                    <div class="bg-primary/5 border-l-4 border-primary p-4 rounded-r-2xl text-slate-700 leading-relaxed italic shadow-inner prose prose-sm max-w-full">
                        ' . $get_det['kesimpulan'] . '
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="mdi mdi-file-pdf-box text-2xl text-error"></i>
                        <h4 class="font-black text-slate-700 uppercase text-sm tracking-wider">Lampiran Berkas Digital</h4>
                    </div>';

        if ($get_det['berkas'] == null || !$get_det['berkas']) {
            $content .= '
                <div class="flex flex-col items-center justify-center p-10 bg-base-200 rounded-3xl border-2 border-dashed border-base-300 opacity-50">
                    <i class="mdi mdi-file-hidden text-5xl mb-2"></i>
                    <p class="font-bold uppercase text-xs">Dokumen Belum Diupload</p>
                </div>';
        } else {
            $content .= '
                <div class="rounded-3xl overflow-hidden border border-base-300 shadow-2xl bg-base-300">
                    <iframe id="frame" class="w-full h-[600px]" allowfullscreen src="' . base_url('assets/berkas_nonlit/' . $get_det['berkas']) . '"></iframe>
                </div>';
        }

        $content .= '
                </div>
            </div>
        </div>';

        echo $content;
    }
}
    public function get_content_berkas()
{
    $this->form_validation->set_rules('id', 'Harus Di Isi', 'required');
    cek_csrf();

    if ($this->form_validation->run() == FALSE) {
        echo '<div class="alert alert-error shadow-lg">ID tidak valid.</div>';
    } else {
        $id = $this->input->post('id', TRUE);
        $get_det = $this->m_nonlit->get_det_berkas($id);

        if (!$get_det) {
            echo '<div class="alert alert-warning shadow-lg">Data berkas tidak ditemukan.</div>';
            return;
        }

        // Mulai membangun konten dengan DaisyUI
        $content = '
        <div class="card bg-base-100 shadow-xl border border-base-200 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-base-200/50 p-4 border-b border-base-200 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-warning text-warning-content p-2 rounded-xl shadow-sm">
                        <i class="mdi mdi-paperclip text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-700 uppercase tracking-tight">Lampiran Dokumen</h3>
                        <div class="badge badge-outline badge-sm opacity-50 uppercase font-bold tracking-widest text-[10px]">Detail Lampiran</div>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button id="btnEditLampiran" 
                        data-idnonlit="' . $get_det['id_nonlit'] . '" 
                        data-id="' . $get_det['id'] . '" 
                        data-judul_berkas="' . $get_det['judul_berkas'] . '" 
                        data-nama_berkas="' . $get_det['nama_berkas'] . '"  
                        class="btn btn-warning btn-sm shadow-md text-white rounded-xl lowercase hover:scale-105 transition-transform">
                        <i class="mdi mdi-pencil mr-1"></i> edit
                    </button>
                    <button type="button" class="hapus_lampiran btn btn-error btn-sm shadow-md text-white rounded-xl lowercase hover:scale-105 transition-transform" 
                        id="' . $get_det['id'] . '" 
                        data-idnonlit="' . $get_det['id_nonlit'] . '"> 
                        <i class="mdi mdi-trash-can mr-1"></i> hapus
                    </button>
                </div>
            </div>

            <div class="card-body p-6">
                <div class="form-control w-full mb-6">
                    <label class="label"><span class="label-text font-bold text-xs uppercase text-slate-500">Judul Berkas / Nama Dokumen</span></label>
                    <div class="bg-base-200/80 p-4 rounded-2xl border border-base-300 flex items-center gap-3">
                        <i class="mdi mdi-file-document text-warning text-xl"></i>
                        <span class="font-black text-slate-800 uppercase italic tracking-tighter">' . $get_det['judul_berkas'] . '</span>
                    </div>
                </div>

                <div class="divider text-xs font-bold uppercase opacity-30">Pratinjau Dokumen</div>';

        // Logika Preview File
        if ($get_det['nama_berkas'] == null || !$get_det['nama_berkas']) {
            $content .= '
                <div class="flex flex-col items-center justify-center p-16 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 opacity-50">
                    <i class="mdi mdi-file-hidden text-6xl text-slate-300 mb-4"></i>
                    <p class="font-black uppercase tracking-widest text-sm italic">File tidak tersedia</p>
                </div>';
        } else {
            $content .= '
                <div class="rounded-3xl overflow-hidden border-4 border-base-300 shadow-inner bg-base-300 relative group">
                    <div class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="' . base_url('assets/berkas_lampiran/' . $get_det['nama_berkas']) . '" target="_blank" class="btn btn-circle btn-sm btn-primary">
                            <i class="mdi mdi-open-in-new"></i>
                        </a>
                    </div>
                    <iframe class="w-full h-[600px] bg-white" id="frame" allowfullscreen webkitallowfullscreen 
                        src="' . base_url('assets/berkas_lampiran/' . $get_det['nama_berkas']) . '"></iframe>
                </div>';
        }

        $content .= '
            </div>
        </div>';

        echo $content;
    }
}


    public function upload_berkas2()
    {
        // Pastikan ini hanya bisa diakses dengan metode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        // Ambil data dari form
        $id_nonlit     = $this->input->post('id_nonlit', true);
        $tgl_rapat     = $this->input->post('tgl_rapat', true);
        $judul_rapat   = $this->input->post('judul_rapat', true);
        $kesimpulan    = $this->input->post('kesimpulan', false); // CKEditor bisa mengandung HTML

        // Validasi input
        if (empty($tgl_rapat) || empty($judul_rapat) || empty($kesimpulan)) {
            $response = ['status' => 'error', 'message' => 'Harap isi semua field wajib!'];
            echo json_encode($response);
            return;
        }

        // Cek apakah ada file yang diunggah
        $file_name = null;
        if (!empty($_FILES['file']['name'])) {
            $config['upload_path']   = './uploads/berkas_rapat/'; // Pastikan folder ini ada
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png';
            $config['max_size']      = 2048; // Maksimal 2MB
            $config['encrypt_name']  = true; // Supaya nama file unik

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $file_name = $uploadData['file_name'];
            } else {
                $response = ['status' => 'error', 'message' => $this->upload->display_errors()];
                echo json_encode($response);
                return;
            }
        }

        // Data untuk disimpan ke database
        $data = [
            'id_nonlit'   => $id_nonlit,
            'tgl_rapat'   => $tgl_rapat,
            'judul_rapat' => $judul_rapat,
            'kesimpulan'  => $kesimpulan,
            'file'        => $file_name, // Jika ada file, simpan nama file
            'created_at'  => date('Y-m-d H:i:s')
        ];

        // Simpan ke database menggunakan model
        $insert = $this->m_nonlit->upload_nonlit($data);
        if ($insert) {
            $response = ['status' => 'success', 'message' => 'Data berhasil ditambahkan'];
        } else {
            $response = ['status' => 'error', 'message' => 'Gagal menyimpan data'];
        }

        echo json_encode($response);
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
                    // $response = ['status' => 'success', 'message' => 'Data berhasil ditambahkan'];
                    echo "<script type='text/javascript'>
                    alert(' Berhasil ');
                    window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
        </script>";
                } else {
                    // $response = ['status' => 'error', 'message' => 'Gagal menyimpan data'];
                    echo "<script type='text/javascript'>
                    alert(' gagal ');
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
                // $response = ['status' => 'success', 'message' => 'Data berhasil ditambahkan'];
                echo "<script type='text/javascript'>
                alert(' Berhasil ');
                window.location.href ='" . base_url('nonlit/detail/' . $id_nonlit) . "';
    </script>";
            }
            echo json_encode($response);
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
        cek_csrf(); 
        $id_nonlit = $this->input->post('id_nonlit');
 
        $this->db->delete('nonlits', array('id' => $id_nonlit));

        //}
        echo "{}";
    }

    public function apinonlitigasi()
    {
        // $fetch_data = $this->m_nonlit->m_apinonlit();
        $data = $this->m_nonlit->get_all_data_siswa();

        // Format respon
        $response = [
            'success' => true,
            'message' => 'Get All Data Nonlits',
            'data' => $data
        ];

        // Kirimkan respon dalam format JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }
    public function apinonlit_id($register = null)
    {
        // $fetch_data = $this->m_nonlit->m_apinonlit();
        $data = $this->m_nonlit->m_apinonlit_id($register);

        // Format respon
        $response = [
            'success' => true,
            'message' => 'Get All Data Nonlits',
            'data' => $data
        ];

        // Kirimkan respon dalam format JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }
}
