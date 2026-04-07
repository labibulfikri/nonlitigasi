<<<<<<< HEAD
<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Masalah extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_masalah');
        $this->load->model('m_pic');
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
            //list pic
            $data_pic = $this->m_pic->get_all_pic();

            $data = array(
                'masterpage' => 'layout/layout2',
                'content' => 'data_masalah/data_masalah',
                'list_pic' => $data_pic,

                // 'footer' => 'layout/footer',
                'title' => 'Daftar Permasalahan'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    // Ambil data untuk Card List (AJAX)
    //     public function fetch_data()
    //     {
    //         $search = $this->input->post('search');
    //         $page = $this->input->post('page') ?: 0;
    //         $limit = 10;

    //         $results = $this->m_masalah->get_datatables($search, $limit, $page);
    //         $html = '';

    //         if ($results) {
    //             foreach ($results as $row) {
    //                 // Escape quotes untuk data JavaScript agar tidak error jika ada tanda petik
    //                 $safe_rak = addslashes($row->penyimpanan_rak);
    //                 $safe_nama = addslashes($row->nama_masalah);
    //                 $safe_alamat = addslashes($row->alamat_masalah);

    //                 $html .= '
    //             <div class="card card-side bg-base-100 shadow-sm border border-base-300 hover:border-primary transition-all mb-3">
    //                 <div class="flex flex-col md:flex-row items-center w-full p-4 gap-4">

    //                     <div class="flex flex-row md:flex-col gap-2 w-full md:w-32 items-center md:items-start shrink-0">
    //                         <span class="badge badge-primary badge-sm font-semibold uppercase">' . $row->status_masalah . '</span>
    //                         <span class="badge badge-ghost badge-sm font-mono">Rak: ' . $row->penyimpanan_rak . '</span>
    //                     </div>

    //                     <div class="flex-grow">
    //                         <h2 class="font-bold text-lg text-primary">' . $row->nama_masalah . '</h2>
    //                         <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm opacity-70 mt-1">
    //                             <span><i class="fas fa-user-circle mr-1"></i> PIC: <strong>' . $row->pic_masalah . '</strong></span>
    //                             <span><i class="fas fa-calendar-alt mr-1"></i> Tgl: ' . $row->tgl_masalah . '</span>
    //                             <span><i class="fas fa-map-marker-alt mr-1"></i> ' . $row->alamat_masalah . '</span>
    //                         </div>
    //                     </div>

    //                     <div class="flex gap-2 shrink-0 border-l pl-4 border-base-200">
    //                         <button onclick="cetak_label(\'' . $safe_rak . '\', \'' . $safe_nama . '\', \'' . $safe_alamat . '\')" 
    //                                 class="btn btn-square btn-ghost btn-sm tooltip text-success" data-tip="Cetak Label">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    //                             </svg>
    //                         </button>

    //                         <button onclick="edit_data(' . $row->id_masalah . ')" class="btn btn-square btn-ghost btn-sm tooltip text-warning" data-tip="Edit">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 14.207H7v-5z" />
    //                             </svg>
    //                         </button>

    //                         <button onclick="delete_data(' . $row->id_masalah . ')" class="btn btn-square btn-ghost btn-sm tooltip text-error" data-tip="Hapus">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    //                             </svg>
    //                         </button>

    //    <a href="' . base_url('masalah/detail/' . $row->id_masalah) . '" class="btn btn-square btn-ghost btn-sm text-info tooltip" data-tip="Buka Detail">
    //     <i class="mdi mdi-eye-outline text-xl"></i>
    // </a>
    //                     </div>
    //                 </div>
    //             </div>';
    //             }
    //         } else {
    //             $html = '<div class="alert alert-info shadow-sm"><span>Data tidak ditemukan.</span></div>';
    //         }

    //         echo json_encode(['html' => $html]);
    //     }
    public function fetch_data()
    {
        $search = $this->input->post('search', TRUE);
        $page = $this->input->post('page');
        $limit = 5; // Samakan limit agar konsisten
        $offset = $page * $limit;

        // Load model dan ambil data
        $results = $this->m_masalah->get_all_masalah($search, $limit, $offset);
        $total_data = $this->m_masalah->count_all($search);

        $html = '';
        $is_last_page = ($offset + $limit) >= $total_data;

        if ($results) {
            foreach ($results as $row) {
                // Perbaikan agar tidak error di PHP 8 jika tanggal null
                $tanggal = ($row->tgl_masalah) ? date('d M Y', strtotime($row->tgl_masalah)) : '-';

                $html .= '
            <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-secondary/50 transition-all mb-4 overflow-hidden group rounded-[2rem]">
                <div class="flex flex-col lg:flex-row items-center w-full p-6 gap-6">
                    
                    <div class="flex flex-row lg:flex-col gap-2 w-full lg:w-36 items-center lg:items-start shrink-0 lg:border-r border-base-200 lg:pr-6">
                        <div class="badge badge-secondary font-black uppercase text-[10px] tracking-widest px-4 py-3 italic border-none shadow-lg shadow-secondary/20 w-full lg:w-auto justify-center">
                            ' . ($row->status_masalah ?: "PENDING") . '
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-2 pt-2 border-t border-base-100">
                            <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
                                <i class="mdi mdi-archive-outline text-secondary text-sm"></i>
                                <span class="text-[9px] font-black opacity-50 uppercase">POSISI RAK:</span>
                                <span class="text-[10px] font-mono font-black uppercase text-secondary">' . ($row->penyimpanan_rak ?: 'BELUM DIARSIP') . '</span>
                            </div> 
                        </div>
                    </div>

                    <div class="flex-grow space-y-3">
                        <h2 class="font-black text-2xl text-secondary uppercase italic tracking-tighter group-hover:translate-x-1 transition-transform duration-300 leading-none">
                            ' . $row->nama_masalah . '
                        </h2>
                        
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-[10px] font-black opacity-60 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-map-marker-radius text-error text-base"></i> LOKASI: <strong class="text-base-content">' . ($row->alamat_masalah ?: "-") . '</strong></span>
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-account-tie text-primary text-base"></i> PIC: ' . ($row->pic_masalah ?: "-") . '</span>
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-clock-outline text-success text-base"></i> TGL: ' . $tanggal . '</span>
                        </div>
                    </div>

                    <div class="flex lg:flex-col gap-2 shrink-0 lg:pl-6 lg:border-l border-base-200">
                        <a href="' . base_url('masalah/detail/' . $row->id_masalah) . '" 
                           class="btn btn-secondary btn-sm rounded-xl font-black italic uppercase text-[10px] gap-2 px-5 shadow-md shadow-secondary/20">
                            <i class="mdi mdi-eye-outline text-lg"></i> Detail
                        </a>

                        <div class="flex gap-2 justify-center">
                            <button onclick="edit_data(' . $row->id_masalah . ')" 
                                    class="btn btn-square btn-ghost btn-sm text-warning hover:bg-warning/10">
                                <i class="mdi mdi-pencil-outline text-xl"></i>
                            </button>

                            <button onclick="delete_data(' . $row->id_masalah . ')" 
                                    class="btn btn-square btn-ghost btn-sm text-error hover:bg-error/10">
                                <i class="mdi mdi-trash-can-outline text-xl"></i>
                            </button>
 
                            <button onclick="cetak_label(\'' . $row->penyimpanan_rak . '\', \'' . $row->nama_masalah . '\', \'' . $row->alamat_masalah . '\')" 
                                    class="btn btn-square btn-ghost btn-sm tooltip text-success" data-tip="Cetak Label">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            }
        } else {
            if ($page == 0) {
                $html = '
            <div class="flex flex-col items-center justify-center p-24 border-4 border-dashed border-base-300 rounded-[4rem] opacity-20 bg-base-200/10">
                <i class="mdi mdi-alert-circle-outline text-8xl mb-6 animate-pulse"></i>
                <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Permasalahan Tidak Ditemukan</p>
            </div>';
            }
        }

        echo json_encode([
            'html' => $html,
            'is_last_page' => $is_last_page
        ]);
    }
    public function get_by_id($id)
    {
        // Ambil satu baris data berdasarkan ID
        $data = $this->db->get_where('masalah', ['id_masalah' => $id])->row();
        echo json_encode($data);
    }
    public function simpan()
    {


        cek_csrf();
        $data = [
            'nama_masalah'   => $this->input->post('nama_masalah'),
            'alamat_masalah' => $this->input->post('alamat_masalah'),
            'pic_masalah'    => $this->input->post('pic_masalah'),
            'tgl_masalah'    => $this->input->post('tgl_masalah'),
            'status_masalah' => $this->input->post('status_masalah'),
            'penyimpanan_rak' => $this->input->post('penyimpanan_rak'),
        ];

        $insert_id = $this->m_masalah->insert($data);

        // // Jika ada detail & berkas
        // if (!empty($this->input->post('deskripsi'))) {
        //     $config['upload_path']   = './uploads/berkas/';
        //     $config['allowed_types'] = 'pdf|jpg|png|doc|docx';
        //     $config['encrypt_name']  = TRUE;

        //     $this->load->library('upload', $config);

        //     $file_name = '';
        //     if ($this->upload->do_upload('berkas')) {
        //         $file_name = $this->upload->data('file_name');
        //     }

        //     // $det = [
        //     //     'id_masalah'        => $insert_id,
        //     //     'judul_masalah_det' => 'Input Awal',
        //     //     'deskripsi'         => $this->input->post('deskripsi'),
        //     //     'tgl_masalah_det'   => date('Y-m-d'),
        //     //     'berkas'            => $file_name
        //     // ];
        //     $this->db->insert('masalah_det', $det);
        // }

        echo json_encode(['status' => TRUE]);
    }
    public function detail($id)
    {
        // Ambil data header
        $data['masalah'] = $this->db->get_where('masalah', ['id_masalah' => $id])->row();

        // Ambil data kronologi/rapat dari masalah_det
        $this->db->order_by('tgl_masalah_det', 'DESC');
        $data['details'] = $this->db->get_where('masalah_det', ['id_masalah' => $id])->result();

        $data['title'] = "Detail Permasalahan: " . $data['masalah']->nama_masalah;

        // Sesuaikan dengan template layout Anda
        $this->load->view('layout/layout2', [
            'content' => 'data_masalah/detail_masalah',
            'title' => $data['title'],
            'masalah' => $data['masalah'],
            'details' => $data['details']
        ]);
    }
    public function hapus($id)
    {
        cek_csrf();
        $delete = $this->m_masalah->delete($id);
        $new_token = hash('sha1', time() . mt_rand());
        $this->session->set_userdata('csrf_token', $new_token);
        echo json_encode([
            'status' => $delete,
            'new_token' => $new_token
        ]);
    }
    // Fungsi untuk memuat konten detail via AJAX
    public function get_detail_content()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();

        if ($data) {
            // Kita langsung buatkan HTML-nya di sini agar simpel
            echo '
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-black text-primary uppercase">' . $data->judul_masalah_det . '</h2>
                <p class="text-xs opacity-50 italic"><i class="fas fa-calendar-alt"></i> ' . date('d M Y', strtotime($data->tgl_masalah_det)) . '</p>
            </div>
            <div class="flex gap-2">
                <button onclick="editDet(' . $data->id_masalah_det . ')" class="btn btn-sm btn-ghost text-warning"> 
            <i class="mdi mdi-pencil mr-1"></i> Edit 
          </button>
          <button onclick="hapusDet(' . $data->id_masalah_det . ')" class="btn btn-error btn-sm">
    <i class="mdi mdi-trash-can"></i> Hapus
</button>      
          </div>
        </div>
        <div class="divider"></div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="prose max-w-none">
                <h3 class="text-sm font-bold uppercase opacity-50">Uraian / Kesimpulan:</h3>
                <p class="text-base-content">' . nl2br($data->deskripsi) . '</p>
            </div> 
            <div class="lg:divider-horizontal"></div>
            <div>
                <h3 class="text-sm font-bold uppercase opacity-50 mb-3">Berkas Pendukung:</h3>';

            if ($data->berkas) {
                $ext = pathinfo($data->berkas, PATHINFO_EXTENSION);
                $path = base_url('assets/berkas_permasalahan/' . $data->berkas);

                echo '<div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-[400px] overflow-hidden">
                            <iframe src="' . $path . '" class="w-full h-full"></iframe>
                          </div>
                          <a href="' . $path . '" target="_blank" class="btn btn-block btn-sm mt-2 btn-ghost"><i class="fas fa-external-link-alt"></i> Buka Fullscreen</a>';
            } else {
                echo '<div class="h-40 flex items-center justify-center border-2 border-dashed rounded-2xl opacity-30 italic">Tidak ada berkas</div>';
            }

            echo '</div></div>';
        }
    }

    public function simpan_detail()
    {
        $id_masalah = $this->input->post('id_masalah'); // ID Relasi ke tabel induk

        // Konfigurasi Upload Berkas
        $config['upload_path']   = './assets/berkas_permasalahan/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name']  = TRUE; // Nama file diacak agar aman
        $config['max_size']      = 5000; // 5MB

        $this->load->library('upload', $config);

        $nama_berkas = null;
        if ($this->upload->do_upload('berkas')) {
            $file_data = $this->upload->data();
            $nama_berkas = $file_data['file_name'];
        }

        $data = [
            'id_masalah'         => $id_masalah,
            'judul_masalah_det'  => $this->input->post('judul_masalah_det'),
            'tgl_masalah_det'    => $this->input->post('tgl_masalah_det'),
            'deskripsi'          => $this->input->post('deskripsi'),
            'berkas'             => $nama_berkas
        ];

        $insert = $this->m_masalah->insert_detail($data);

        if ($insert) {
            // Setelah simpan, balik lagi ke halaman detail masalah tersebut
            redirect('masalah/detail/' . $id_masalah);
        } else {
            echo "Gagal menyimpan data.";
        }
    }

    // Ambil data detail untuk diisi ke Modal Edit
    public function get_det_by_id($id)
    {
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();
        echo json_encode($data);
    }

    // Update Data Detail
    public function update_detail()
    {
        cek_csrf();
        $id = $this->input->post('id_masalah_det');
        $id_masalah = $this->input->post('id_masalah');
        $old_file = $this->input->post('old_berkas');

        $config['upload_path']   = './assets/berkas_permasalahan/';
        $config['allowed_types'] = 'pdf|jpg|png|jpeg';
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        $file_name = $old_file; // Default pake file lama

        if ($this->upload->do_upload('berkas')) {
            $file_name = $this->upload->data('file_name');
            // Hapus file lama jika ada file baru
            if ($old_file && file_exists('./assets/berkas_permasalahan/' . $old_file)) {
                unlink('./assets/berkas_permasalahan/' . $old_file);
            }
        }

        $data = [
            'judul_masalah_det' => $this->input->post('judul_masalah_det'),
            'tgl_masalah_det'   => $this->input->post('tgl_masalah_det'),
            'deskripsi'         => $this->input->post('deskripsi'),
            'berkas'            => $file_name
        ];

        $this->db->where('id_masalah_det', $id)->update('masalah_det', $data);
        redirect('masalah/detail/' . $id_masalah);
    }

    public function hapus_det()
    {
        $id = $this->input->post('id');

        // 1. Ambil data untuk hapus file fisik di folder uploads
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();
        if ($data->berkas && file_exists('./uploads/berkas/' . $data->berkas)) {
            unlink('./uploads/berkas/' . $data->berkas);
        }

        // 2. Hapus data dari database
        $delete = $this->db->where('id_masalah_det', $id)->delete('masalah_det');

        if ($delete) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }
}
=======
<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Masalah extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_masalah');
        $this->load->model('m_pic');
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
            //list pic
            $data_pic = $this->m_pic->get_all_pic();

            $data = array(
                'masterpage' => 'layout/layout2',
                'content' => 'data_masalah/data_masalah',
                'list_pic' => $data_pic,

                // 'footer' => 'layout/footer',
                'title' => 'Daftar Permasalahan'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    // Ambil data untuk Card List (AJAX)
    //     public function fetch_data()
    //     {
    //         $search = $this->input->post('search');
    //         $page = $this->input->post('page') ?: 0;
    //         $limit = 10;

    //         $results = $this->m_masalah->get_datatables($search, $limit, $page);
    //         $html = '';

    //         if ($results) {
    //             foreach ($results as $row) {
    //                 // Escape quotes untuk data JavaScript agar tidak error jika ada tanda petik
    //                 $safe_rak = addslashes($row->penyimpanan_rak);
    //                 $safe_nama = addslashes($row->nama_masalah);
    //                 $safe_alamat = addslashes($row->alamat_masalah);

    //                 $html .= '
    //             <div class="card card-side bg-base-100 shadow-sm border border-base-300 hover:border-primary transition-all mb-3">
    //                 <div class="flex flex-col md:flex-row items-center w-full p-4 gap-4">

    //                     <div class="flex flex-row md:flex-col gap-2 w-full md:w-32 items-center md:items-start shrink-0">
    //                         <span class="badge badge-primary badge-sm font-semibold uppercase">' . $row->status_masalah . '</span>
    //                         <span class="badge badge-ghost badge-sm font-mono">Rak: ' . $row->penyimpanan_rak . '</span>
    //                     </div>

    //                     <div class="flex-grow">
    //                         <h2 class="font-bold text-lg text-primary">' . $row->nama_masalah . '</h2>
    //                         <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm opacity-70 mt-1">
    //                             <span><i class="fas fa-user-circle mr-1"></i> PIC: <strong>' . $row->pic_masalah . '</strong></span>
    //                             <span><i class="fas fa-calendar-alt mr-1"></i> Tgl: ' . $row->tgl_masalah . '</span>
    //                             <span><i class="fas fa-map-marker-alt mr-1"></i> ' . $row->alamat_masalah . '</span>
    //                         </div>
    //                     </div>

    //                     <div class="flex gap-2 shrink-0 border-l pl-4 border-base-200">
    //                         <button onclick="cetak_label(\'' . $safe_rak . '\', \'' . $safe_nama . '\', \'' . $safe_alamat . '\')" 
    //                                 class="btn btn-square btn-ghost btn-sm tooltip text-success" data-tip="Cetak Label">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    //                             </svg>
    //                         </button>

    //                         <button onclick="edit_data(' . $row->id_masalah . ')" class="btn btn-square btn-ghost btn-sm tooltip text-warning" data-tip="Edit">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 14.207H7v-5z" />
    //                             </svg>
    //                         </button>

    //                         <button onclick="delete_data(' . $row->id_masalah . ')" class="btn btn-square btn-ghost btn-sm tooltip text-error" data-tip="Hapus">
    //                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    //                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    //                             </svg>
    //                         </button>

    //    <a href="' . base_url('masalah/detail/' . $row->id_masalah) . '" class="btn btn-square btn-ghost btn-sm text-info tooltip" data-tip="Buka Detail">
    //     <i class="mdi mdi-eye-outline text-xl"></i>
    // </a>
    //                     </div>
    //                 </div>
    //             </div>';
    //             }
    //         } else {
    //             $html = '<div class="alert alert-info shadow-sm"><span>Data tidak ditemukan.</span></div>';
    //         }

    //         echo json_encode(['html' => $html]);
    //     }
    public function fetch_data()
    {
        $search = $this->input->post('search');
        $page = $this->input->post('page') ?: 0;
        $limit = 5; // Samakan limit agar konsisten
        $offset = $page * $limit;

        // Load model dan ambil data
        $results = $this->m_masalah->get_all_masalah($search, $limit, $offset);
        $total_data = $this->m_masalah->count_all($search);

        $html = '';
        $is_last_page = ($offset + $limit) >= $total_data;

        if ($results) {
            foreach ($results as $row) {
                // Perbaikan agar tidak error di PHP 8 jika tanggal null
                $tanggal = ($row->tgl_masalah) ? date('d M Y', strtotime($row->tgl_masalah)) : '-';

                $html .= '
            <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-secondary/50 transition-all mb-4 overflow-hidden group rounded-[2rem]">
                <div class="flex flex-col lg:flex-row items-center w-full p-6 gap-6">
                    
                    <div class="flex flex-row lg:flex-col gap-2 w-full lg:w-36 items-center lg:items-start shrink-0 lg:border-r border-base-200 lg:pr-6">
                        <div class="badge badge-secondary font-black uppercase text-[10px] tracking-widest px-4 py-3 italic border-none shadow-lg shadow-secondary/20 w-full lg:w-auto justify-center">
                            ' . ($row->status_masalah ?: "PENDING") . '
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-2 pt-2 border-t border-base-100">
                            <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
                                <i class="mdi mdi-archive-outline text-secondary text-sm"></i>
                                <span class="text-[9px] font-black opacity-50 uppercase">POSISI RAK:</span>
                                <span class="text-[10px] font-mono font-black uppercase text-secondary">' . ($row->penyimpanan_rak ?: 'BELUM DIARSIP') . '</span>
                            </div> 
                        </div>
                    </div>

                    <div class="flex-grow space-y-3">
                        <h2 class="font-black text-2xl text-secondary uppercase italic tracking-tighter group-hover:translate-x-1 transition-transform duration-300 leading-none">
                            ' . $row->nama_masalah . '
                        </h2>
                        
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-[10px] font-black opacity-60 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-map-marker-radius text-error text-base"></i> LOKASI: <strong class="text-base-content">' . ($row->alamat_masalah ?: "-") . '</strong></span>
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-account-tie text-primary text-base"></i> PIC: ' . ($row->pic_masalah ?: "-") . '</span>
                            <span class="flex items-center gap-1.5"><i class="mdi mdi-clock-outline text-success text-base"></i> TGL: ' . $tanggal . '</span>
                        </div>
                    </div>

                    <div class="flex lg:flex-col gap-2 shrink-0 lg:pl-6 lg:border-l border-base-200">
                        <a href="' . base_url('masalah/detail/' . $row->id_masalah) . '" 
                           class="btn btn-secondary btn-sm rounded-xl font-black italic uppercase text-[10px] gap-2 px-5 shadow-md shadow-secondary/20">
                            <i class="mdi mdi-eye-outline text-lg"></i> Detail
                        </a>

                        <div class="flex gap-2 justify-center">
                            <button onclick="edit_data(' . $row->id_masalah . ')" 
                                    class="btn btn-square btn-ghost btn-sm text-warning hover:bg-warning/10">
                                <i class="mdi mdi-pencil-outline text-xl"></i>
                            </button>

                            <button onclick="delete_data(' . $row->id_masalah . ')" 
                                    class="btn btn-square btn-ghost btn-sm text-error hover:bg-error/10">
                                <i class="mdi mdi-trash-can-outline text-xl"></i>
                            </button>
 
                            <button onclick="cetak_label(\'' . $row->penyimpanan_rak . '\', \'' . $row->nama_masalah . '\', \'' . $row->alamat_masalah . '\')" 
                                    class="btn btn-square btn-ghost btn-sm tooltip text-success" data-tip="Cetak Label">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            }
        } else {
            if ($page == 0) {
                $html = '
            <div class="flex flex-col items-center justify-center p-24 border-4 border-dashed border-base-300 rounded-[4rem] opacity-20 bg-base-200/10">
                <i class="mdi mdi-alert-circle-outline text-8xl mb-6 animate-pulse"></i>
                <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Permasalahan Tidak Ditemukan</p>
            </div>';
            }
        }

        echo json_encode([
            'html' => $html,
            'is_last_page' => $is_last_page
        ]);
    }
    public function get_by_id($id)
    {
        // Ambil satu baris data berdasarkan ID
        $data = $this->db->get_where('masalah', ['id_masalah' => $id])->row();
        echo json_encode($data);
    }
    public function simpan()
    {


        cek_csrf();
        $data = [
            'nama_masalah'   => $this->input->post('nama_masalah'),
            'alamat_masalah' => $this->input->post('alamat_masalah'),
            'pic_masalah'    => $this->input->post('pic_masalah'),
            'tgl_masalah'    => $this->input->post('tgl_masalah'),
            'status_masalah' => $this->input->post('status_masalah'),
            'penyimpanan_rak' => $this->input->post('penyimpanan_rak'),
        ];

        $insert_id = $this->m_masalah->insert($data);

        // // Jika ada detail & berkas
        // if (!empty($this->input->post('deskripsi'))) {
        //     $config['upload_path']   = './uploads/berkas/';
        //     $config['allowed_types'] = 'pdf|jpg|png|doc|docx';
        //     $config['encrypt_name']  = TRUE;

        //     $this->load->library('upload', $config);

        //     $file_name = '';
        //     if ($this->upload->do_upload('berkas')) {
        //         $file_name = $this->upload->data('file_name');
        //     }

        //     // $det = [
        //     //     'id_masalah'        => $insert_id,
        //     //     'judul_masalah_det' => 'Input Awal',
        //     //     'deskripsi'         => $this->input->post('deskripsi'),
        //     //     'tgl_masalah_det'   => date('Y-m-d'),
        //     //     'berkas'            => $file_name
        //     // ];
        //     $this->db->insert('masalah_det', $det);
        // }

        echo json_encode(['status' => TRUE]);
    }
    public function detail($id)
    {
        // Ambil data header
        $data['masalah'] = $this->db->get_where('masalah', ['id_masalah' => $id])->row();

        // Ambil data kronologi/rapat dari masalah_det
        $this->db->order_by('tgl_masalah_det', 'DESC');
        $data['details'] = $this->db->get_where('masalah_det', ['id_masalah' => $id])->result();

        $data['title'] = "Detail Permasalahan: " . $data['masalah']->nama_masalah;

        // Sesuaikan dengan template layout Anda
        $this->load->view('layout/layout2', [
            'content' => 'data_masalah/detail_masalah',
            'title' => $data['title'],
            'masalah' => $data['masalah'],
            'details' => $data['details']
        ]);
    }
    public function hapus($id)
    {
        cek_csrf();
        $delete = $this->m_masalah->delete($id);
        $new_token = hash('sha1', time() . mt_rand());
        $this->session->set_userdata('csrf_token', $new_token);
        echo json_encode([
            'status' => $delete,
            'new_token' => $new_token
        ]);
    }
    // Fungsi untuk memuat konten detail via AJAX
    public function get_detail_content()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();

        if ($data) {
            // Kita langsung buatkan HTML-nya di sini agar simpel
            echo '
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-black text-primary uppercase">' . $data->judul_masalah_det . '</h2>
                <p class="text-xs opacity-50 italic"><i class="fas fa-calendar-alt"></i> ' . date('d M Y', strtotime($data->tgl_masalah_det)) . '</p>
            </div>
            <div class="flex gap-2">
                <button onclick="editDet(' . $data->id_masalah_det . ')" class="btn btn-sm btn-ghost text-warning"> 
            <i class="mdi mdi-pencil mr-1"></i> Edit 
          </button>
          <button onclick="hapusDet(' . $data->id_masalah_det . ')" class="btn btn-error btn-sm">
    <i class="mdi mdi-trash-can"></i> Hapus
</button>      
          </div>
        </div>
        <div class="divider"></div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="prose max-w-none">
                <h3 class="text-sm font-bold uppercase opacity-50">Uraian / Kesimpulan:</h3>
                <p class="text-base-content">' . nl2br($data->deskripsi) . '</p>
            </div> 
            <div class="lg:divider-horizontal"></div>
            <div>
                <h3 class="text-sm font-bold uppercase opacity-50 mb-3">Berkas Pendukung:</h3>';

            if ($data->berkas) {
                $ext = pathinfo($data->berkas, PATHINFO_EXTENSION);
                $path = base_url('assets/berkas_permasalahan/' . $data->berkas);

                echo '<div class="rounded-2xl border-2 border-dashed border-base-300 bg-base-200 h-[400px] overflow-hidden">
                            <iframe src="' . $path . '" class="w-full h-full"></iframe>
                          </div>
                          <a href="' . $path . '" target="_blank" class="btn btn-block btn-sm mt-2 btn-ghost"><i class="fas fa-external-link-alt"></i> Buka Fullscreen</a>';
            } else {
                echo '<div class="h-40 flex items-center justify-center border-2 border-dashed rounded-2xl opacity-30 italic">Tidak ada berkas</div>';
            }

            echo '</div></div>';
        }
    }

    public function simpan_detail()
    {
        $id_masalah = $this->input->post('id_masalah'); // ID Relasi ke tabel induk

        // Konfigurasi Upload Berkas
        $config['upload_path']   = './assets/berkas_permasalahan/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['encrypt_name']  = TRUE; // Nama file diacak agar aman
        $config['max_size']      = 5000; // 5MB

        $this->load->library('upload', $config);

        $nama_berkas = null;
        if ($this->upload->do_upload('berkas')) {
            $file_data = $this->upload->data();
            $nama_berkas = $file_data['file_name'];
        }

        $data = [
            'id_masalah'         => $id_masalah,
            'judul_masalah_det'  => $this->input->post('judul_masalah_det'),
            'tgl_masalah_det'    => $this->input->post('tgl_masalah_det'),
            'deskripsi'          => $this->input->post('deskripsi'),
            'berkas'             => $nama_berkas
        ];

        $insert = $this->m_masalah->insert_detail($data);

        if ($insert) {
            // Setelah simpan, balik lagi ke halaman detail masalah tersebut
            redirect('masalah/detail/' . $id_masalah);
        } else {
            echo "Gagal menyimpan data.";
        }
    }

    // Ambil data detail untuk diisi ke Modal Edit
    public function get_det_by_id($id)
    {
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();
        echo json_encode($data);
    }

    // Update Data Detail
    public function update_detail()
    {
        cek_csrf();
        $id = $this->input->post('id_masalah_det');
        $id_masalah = $this->input->post('id_masalah');
        $old_file = $this->input->post('old_berkas');

        $config['upload_path']   = './assets/berkas_permasalahan/';
        $config['allowed_types'] = 'pdf|jpg|png|jpeg';
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        $file_name = $old_file; // Default pake file lama

        if ($this->upload->do_upload('berkas')) {
            $file_name = $this->upload->data('file_name');
            // Hapus file lama jika ada file baru
            if ($old_file && file_exists('./assets/berkas_permasalahan/' . $old_file)) {
                unlink('./assets/berkas_permasalahan/' . $old_file);
            }
        }

        $data = [
            'judul_masalah_det' => $this->input->post('judul_masalah_det'),
            'tgl_masalah_det'   => $this->input->post('tgl_masalah_det'),
            'deskripsi'         => $this->input->post('deskripsi'),
            'berkas'            => $file_name
        ];

        $this->db->where('id_masalah_det', $id)->update('masalah_det', $data);
        redirect('masalah/detail/' . $id_masalah);
    }

    public function hapus_det()
    {
        $id = $this->input->post('id');

        // 1. Ambil data untuk hapus file fisik di folder uploads
        $data = $this->db->get_where('masalah_det', ['id_masalah_det' => $id])->row();
        if ($data->berkas && file_exists('./uploads/berkas/' . $data->berkas)) {
            unlink('./uploads/berkas/' . $data->berkas);
        }

        // 2. Hapus data dari database
        $delete = $this->db->where('id_masalah_det', $id)->delete('masalah_det');

        if ($delete) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }
}
>>>>>>> Initial commit dari server
