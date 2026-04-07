<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_polisi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_laporan_polisi');
        $this->load->model('m_pic');
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
                'content' => 'laporan_polisi/data_laporan_polisi',
                'list_pic' => $data_pic,

                // 'footer' => 'layout/footer',
                'title' => 'Daftar Laporan Polisi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    // public function fetch_data()
    // {
    //     cek_csrf();
    //     $search = $this->input->post('search');
    //     $page = $this->input->post('page') ?: 0;
    //     $limit = 5;
    //     $offset = $page * $limit;

    //     $results = $this->m_laporan_polisi->get_all_laporan($search, $limit, $offset);
    //     $total_data = $this->m_laporan_polisi->count_all($search);

    //     $html = '';
    //     // Cek apakah ini halaman terakhir berdasarkan total data
    //     $is_last_page = ($offset + $limit) >= $total_data;

    //     if ($results) {
    //         foreach ($results as $row) {
    //             $tanggal = ($row->tgl_laporan_polisi) ? date('d/m/Y', strtotime($row->tgl_laporan_polisi)) : '-';

    //             $html .= '
    //         <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/50 transition-all mb-4 overflow-hidden group rounded-[2rem]">
    //             <div class="flex flex-col lg:flex-row items-center w-full p-6 gap-6">
    //                 <div class="flex flex-row lg:flex-col gap-2 w-full lg:w-36 items-center lg:items-start shrink-0 lg:border-r border-base-200 lg:pr-6">
    //                     <div class="badge badge-primary font-black uppercase text-[10px] tracking-widest px-4 py-3 italic border-none shadow-lg shadow-primary/20 w-full lg:w-auto justify-center">
    //                         ' . ($row->status_laporan_polisi ?: "LIDIK") . '
    //                     </div>
    //                      <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
    //                             <i class="mdi mdi-archive-arrow-down text-secondary text-sm"></i>
    //                             <span class="text-[9px] font-black opacity-50 uppercase">RAK:</span>
    //                             <span class="text-[10px] font-mono font-black uppercase">' . ($row->penyimpanan_rak ?: '-') . '</span>
    //                         </div>
    //                 </div>

    //                 <div class="flex-grow space-y-3">
    //                     <h2 class="font-black text-2xl text-primary uppercase italic tracking-tighter group-hover:translate-x-1 transition-transform duration-300 leading-none">
    //                         ' . $row->judul_laporan_polisi . '
    //                     </h2>

    //                     <div class="flex flex-wrap gap-x-6 gap-y-2 text-[10px] font-black opacity-60 uppercase tracking-widest">
    //                         <span class="flex items-center gap-1.5"><i class="mdi mdi-file-certificate text-error text-base"></i> NO. POL: <strong class="text-base-content">' . $row->nomor_polisi . '</strong></span>
    //                         <span class="flex items-center gap-1.5"><i class="mdi mdi-account-group text-primary text-base"></i> PELAPOR: ' . ($row->pelapor ?: "-") . '</span>
    //                         <span class="flex items-center gap-1.5"><i class="mdi mdi-map-marker-radius text-success text-base"></i> ' . ($row->alamat_laporan_polisi ?: "-") . '</span>
    //                     </div>

    //                     <div class="flex flex-wrap gap-x-4 gap-y-2 pt-2 border-t border-base-100">
    //                         <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
    //                             <i class="mdi mdi-shield-account text-info text-sm"></i>
    //                             <span class="text-[9px] font-black opacity-50 uppercase">UNIT:</span>
    //                             <span class="text-[10px] font-black uppercase italic">' . ($row->team_polisi ?: 'BELUM DIVALIDASI') . '</span>
    //                         </div>

    //                         <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
    //                             <i class="mdi mdi-calendar-clock text-warning text-sm"></i>
    //                             <span class="text-[10px] font-black uppercase">' . $tanggal . '</span>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class="flex lg:flex-col gap-2 shrink-0 lg:pl-6 lg:border-l border-base-200">
    //                     <a href="' . base_url('laporan_polisi/detail/' . $row->id_laporan_polisi) . '" 
    //                        class="btn btn-primary btn-sm rounded-xl font-black italic uppercase text-[10px] gap-2 px-5 shadow-md shadow-primary/20">
    //                         <i class="mdi mdi-eye-outline text-lg"></i> Detail
    //                     </a>

    //                     <div class="flex gap-2">
    //                         <button onclick="editLp(' . $row->id_laporan_polisi . ')" 
    //                                 class="btn btn-square btn-ghost btn-sm text-warning hover:bg-warning/10 transition-colors">
    //                             <i class="mdi mdi-pencil-outline text-xl"></i>
    //                         </button>

    //                         <button onclick="hapus_master(' . $row->id_laporan_polisi . ')" 
    //                                 class="btn btn-square btn-ghost btn-sm text-error hover:bg-error/10 transition-colors">
    //                             <i class="mdi mdi-trash-can-outline text-xl"></i>
    //                         </button>
    //                         <button onclick=\'cetak_label_lp(' .
    //                 json_encode($row->nomor_polisi ?: "-") . ', ' .
    //                 json_encode($row->penyimpanan_rak ?: "-") . ', ' .
    //                 json_encode($row->judul_laporan_polisi ?: "-") . ', ' .
    //                 json_encode($row->status_laporan_polisi ?: "-") . ')\' 
    //     class="btn btn-square btn-ghost btn-sm text-success tooltip" data-tip="Cetak Label">
    //     <i class="mdi mdi-printer text-xl"></i>
    // </button>


    //                     </div>
    //                 </div>
    //             </div>
    //         </div>';
    //         }
    //     } else {
    //         if ($page == 0) {
    //             $html = '
    //         <div class="flex flex-col items-center justify-center p-24 border-4 border-dashed border-base-300 rounded-[4rem] opacity-20 bg-base-200/10">
    //             <i class="mdi mdi-text-search text-8xl mb-6"></i>
    //             <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Berkas Tidak Ditemukan</p>
    //         </div>';
    //         }
    //     }

    //     echo json_encode([
    //         'html' => $html,
    //         'is_last_page' => $is_last_page,
    //         'total_data' => $total_data
    //     ]);
    // }


    public function fetch_data()
    {
        cek_csrf();

        $search = $this->input->post('search', TRUE);

        $page = (int) $this->input->post('page');

        $limit = 5;
        $offset = $page * $limit;
        $results    = $this->m_laporan_polisi->get_all_laporan($search, $limit, $offset);
        $total_data = $this->m_laporan_polisi->count_all($search);

        $html = '';
        $is_last_page = ($offset + $limit) >= $total_data;

        if ($results) {
            foreach ($results as $row) {
                $tanggal = ($row->tgl_laporan_polisi) ? date('d/m/Y', strtotime($row->tgl_laporan_polisi)) : '-';

                $html .= '
                <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/50 transition-all mb-4 overflow-hidden group rounded-[2rem]">
                    <div class="flex flex-col lg:flex-row items-center w-full p-6 gap-6">
                        <div class="flex flex-row lg:flex-col gap-2 w-full lg:w-36 items-center lg:items-start shrink-0 lg:border-r border-base-200 lg:pr-6">
                            <div class="badge badge-primary font-black uppercase text-[10px] tracking-widest px-4 py-3 italic border-none shadow-lg shadow-primary/20 w-full lg:w-auto justify-center">
                                ' . ($row->status_laporan_polisi ?: "LIDIK") . '
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
                                <i class="mdi mdi-archive-arrow-down text-secondary text-sm"></i>
                                <span class="text-[9px] font-black opacity-50 uppercase">RAK:</span>
                                <span class="text-[10px] font-mono font-black uppercase">' . ($row->penyimpanan_rak ?: '-') . '</span>
                            </div>
                        </div>

                        <div class="flex-grow space-y-3">
                            <h2 class="font-black text-2xl text-primary uppercase italic tracking-tighter group-hover:translate-x-1 transition-transform duration-300 leading-none">
                                ' . $row->judul_laporan_polisi . '
                            </h2>
                            
                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-[10px] font-black opacity-60 uppercase tracking-widest">
                                <span class="flex items-center gap-1.5"><i class="mdi mdi-file-certificate text-error text-base"></i> NO. POL: <strong class="text-base-content">' . $row->nomor_polisi . '</strong></span>
                                <span class="flex items-center gap-1.5"><i class="mdi mdi-account-group text-primary text-base"></i> PELAPOR: ' . ($row->pelapor ?: "-") . '</span>
                                <span class="flex items-center gap-1.5"><i class="mdi mdi-map-marker-radius text-success text-base"></i> ' . ($row->alamat_laporan_polisi ?: "-") . '</span>
                            </div>

                            <div class="flex flex-wrap gap-x-4 gap-y-2 pt-2 border-t border-base-100">
                                <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
                                    <i class="mdi mdi-shield-account text-info text-sm"></i>
                                    <span class="text-[9px] font-black opacity-50 uppercase">UNIT:</span>
                                    <span class="text-[10px] font-black uppercase italic">' . ($row->team_polisi ?: 'BELUM DIVALIDASI') . '</span>
                                </div>
                                <div class="flex items-center gap-2 px-3 py-1 bg-base-200/50 rounded-lg border border-base-300">
                                    <i class="mdi mdi-calendar-clock text-warning text-sm"></i>
                                    <span class="text-[10px] font-black uppercase">' . $tanggal . '</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex lg:flex-col gap-2 shrink-0 lg:pl-6 lg:border-l border-base-200">
                            <a href="' . base_url('laporan_polisi/detail/' . $row->id_laporan_polisi) . '" 
                               class="btn btn-primary btn-sm rounded-xl font-black italic uppercase text-[10px] gap-2 px-5 shadow-md shadow-primary/20">
                                <i class="mdi mdi-eye-outline text-lg"></i> Detail
                            </a>
                            <div class="flex gap-2">
                                <button onclick="editLp(' . $row->id_laporan_polisi . ')" class="btn btn-square btn-ghost btn-sm text-warning hover:bg-warning/10 transition-colors">
                                    <i class="mdi mdi-pencil-outline text-xl"></i>
                                </button>
                                <button onclick="hapus_master(' . $row->id_laporan_polisi . ')" class="btn btn-square btn-ghost btn-sm text-error hover:bg-error/10 transition-colors">
                                    <i class="mdi mdi-trash-can-outline text-xl"></i>
                                </button>
                                <button onclick=\'cetak_label_lp(' .
                    json_encode($row->nomor_polisi ?: "-") . ', ' .
                    json_encode($row->penyimpanan_rak ?: "-") . ', ' .
                    json_encode($row->judul_laporan_polisi ?: "-") . ', ' .
                    json_encode($row->status_laporan_polisi ?: "-") . ')\' 
                                    class="btn btn-square btn-ghost btn-sm text-success tooltip" data-tip="Cetak Label">
                                    <i class="mdi mdi-printer text-xl"></i>
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
                    <i class="mdi mdi-text-search text-8xl mb-6"></i>
                    <p class="font-black uppercase tracking-[0.6em] italic text-lg text-center">Data Berkas Tidak Ditemukan</p>
                </div>';
            }
        }

        echo json_encode([
            'html' => $html,
            'is_last_page' => $is_last_page,
            'csrf_hash' => $this->security->get_csrf_hash() // Update Token
        ]);
    }

    public function get_master_by_id($id)
    {
        $data = $this->m_laporan_polisi->get_master_by_id($id);
        echo json_encode($data);
    }

    public function delete_master()
    {
        cek_csrf();
        $id = $this->input->post('id', TRUE);
        $delete = $this->m_laporan_polisi->delete_master($id);

        echo json_encode([
            'status' => $delete
        ]);
    }
    // public function fetch_data()
    // {
    //     $search = $this->input->post('search');
    //     $page = $this->input->post('page') ?: 0;
    //     $limit = 10;

    //     $results = $this->m_laporan_polisi->get_all_laporan($search, $limit, $page);
    //     $html = '';

    //     if ($results) {
    //         foreach ($results as $row) {
    //             $html .= '
    //         <div class="card card-side bg-base-100 shadow-sm border border-base-300 hover:border-primary transition-all mb-3 overflow-hidden group">
    //             <div class="flex flex-col md:flex-row items-center w-full p-4 gap-4">

    //                 <div class="flex flex-row md:flex-col gap-2 w-full md:w-32 items-center md:items-start shrink-0">
    //                     <span class="badge badge-primary badge-sm font-black uppercase text-[9px] tracking-tighter">' . $row->status_laporan_polisi . '</span>
    //                     <span class="text-[10px] font-mono font-bold opacity-40 uppercase truncate">ID: LP-' . $row->id_laporan_polisi . '</span>
    //                 </div>

    //                 <div class="flex-grow">
    //                     <h2 class="font-black text-lg text-primary uppercase italic tracking-tighter group-hover:translate-x-1 transition-transform">' . $row->judul_laporan_polisi . '</h2>
    //                     <div class="flex flex-wrap gap-x-6 gap-y-1 text-[11px] font-bold opacity-60 mt-1 uppercase">
    //                         <span><i class="mdi mdi-file-document mr-1 text-error"></i> No. Pol: <strong class="text-base-content">' . $row->nomor_polisi . '</strong></span>
    //                         <span><i class="mdi mdi-account-circle mr-1"></i> Pelapor: ' . $row->pelapor . '</span>
    //                         <span><i class="mdi mdi-map-marker mr-1"></i> ' . $row->alamat_laporan_polisi . '</span>
    //                     </div>
    //                 </div>

    //                 <div class="flex gap-2 shrink-0 border-l pl-4 border-base-200">
    //                     <a href="' . base_url('laporan_polisi/detail/' . $row->id_laporan_polisi) . '" 
    //                        class="btn btn-square btn-ghost btn-sm text-info tooltip" data-tip="Buka Detail">
    //                         <i class="mdi mdi-eye text-xl"></i>
    //                     </a>
    //                     <button onclick="hapus_master(' . $row->id_laporan_polisi . ')" 
    //                             class="btn btn-square btn-ghost btn-sm text-error tooltip" data-tip="Hapus Laporan">
    //                         <i class="mdi mdi-delete-outline text-xl"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         </div>';
    //         }
    //     } else {
    //         $html = '
    //     <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-base-300 rounded-[2rem] opacity-30">
    //         <i class="mdi mdi-folder-alert text-6xl mb-2"></i>
    //         <p class="font-black uppercase tracking-widest">Data Laporan Tidak Ditemukan</p>
    //     </div>';
    //     }

    //     echo json_encode(['html' => $html]);
    // }


    // Simpan atau Update Master Laporan
    public function save_master()
    {

        cek_csrf();
        $id = $this->input->post('id_laporan_polisi', TRUE);
        $data = [
            'judul_laporan_polisi'  => $this->input->post('judul'),
            'nomor_polisi'          => $this->input->post('nomor'),
            'pelapor'               => $this->input->post('pelapor'),
            'terlapor'              => $this->input->post('terlapor'),
            'alamat_laporan_polisi' => $this->input->post('alamat'),
            'pic_laporan_polisi'    => $this->input->post('pic'),
            'tgl_laporan_polisi'    => $this->input->post('tgl'),
            'status_laporan_polisi' => $this->input->post('status'),
            'penyimpanan_rak'        => $this->input->post('rak'),
            'team_polisi'             => $this->input->post('team_polisi'),
        ];

        if ($id) {
            $this->db->where('id_laporan_polisi', $id)->update('laporan_polisi', $data);
        } else {
            $this->db->insert('laporan_polisi', $data);
        }
        redirect('laporan_polisi');
    }

    // Simpan atau Update Detail Agenda

    public function detail($id)
    {
        // 1. Ambil data Master dari Model
        $laporan = $this->m_laporan_polisi->get_laporan_by_id($id);

        // Proteksi jika data tidak ditemukan
        if (!$laporan) {
            show_404();
        }

        // 2. Ambil data Histori/Detail dari Model
        $histori = $this->m_laporan_polisi->get_detail_by_master($id);

        // 3. Ambil data PIC (jika dibutuhkan untuk dropdown edit di detail)
        // Pastikan $this->m_laporan_polisi->get_all_pic() sudah ada di model Anda
        $list_pic = $this->db->get('users')->result();

        $data = array(
            'masterpage' => 'layout/layout2',
            'content'    => 'laporan_polisi/detail_laporan_polisi',
            'title'      => 'Detail Laporan - ' . $laporan->nomor_polisi,

            // Data yang dikirim ke View
            'laporan'    => $laporan,
            'histori'    => $histori,
            'list_pic'   => $list_pic
        );

        $this->load->view($data['masterpage'], $data);
    }
    // public function get_agenda_content()
    // {
    //     $id = $this->input->post('id');
    //     $data = $this->db->get_where('laporan_polisi_det', ['id_laporan_polisi_det' => $id])->row();

    //     if ($data) {
    //         echo '
    //     <div class="flex justify-between items-start mb-10">
    //         <div>
    //             <h2 class="text-3xl font-black text-primary uppercase italic tracking-tighter">' . $data->agenda_laporan_polisi_det . '</h2>
    //             <p class="text-xs font-bold opacity-40 mt-1"><i class="mdi mdi-calendar"></i> ' . date('d M Y', strtotime($data->tgl_agenda_laporan_polisi_det)) . '</p>
    //         </div>
    //         <div class="flex gap-2">
    //             <button onclick="editAgenda(' . $data->id_laporan_polisi_det . ')" class="btn btn-warning btn-sm rounded-xl font-bold text-[10px] px-4">
    //                 <i class="mdi mdi-pencil mr-1"></i> Edit
    //             </button>
    //             <button onclick="hapusAgenda(' . $data->id_laporan_polisi_det . ')" class="btn btn-error btn-sm rounded-xl font-bold text-[10px] px-4 text-white">
    //                 <i class="mdi mdi-delete mr-1"></i> Hapus
    //             </button>
    //         </div>
    //     </div>

    //     <div class="space-y-8">
    //         <div>
    //             <h4 class="text-[10px] font-black uppercase opacity-30 tracking-[0.3em] mb-4 border-b pb-2">Uraian / Kesimpulan:</h4>
    //             <div class="text-sm font-medium leading-relaxed opacity-80">' . nl2br($data->kesimpulan) . '</div>
    //         </div>

    //         <div>
    //             <h4 class="text-[10px] font-black uppercase opacity-30 tracking-[0.3em] mb-4">Berkas Pendukung:</h4>
    //             <div class="bg-base-200 rounded-2xl h-96 flex items-center justify-center border-2 border-dashed border-base-300">
    //                 <p class="text-[10px] font-bold opacity-30 uppercase">Preview Dokumen Belum Tersedia</p>
    //             </div>
    //         </div>
    //     </div>';
    //     }
    // }
    // AJAX: Load Konten Detail Per Baris Agenda
    public function get_agenda_content()
    {
        $id = $this->input->post('id', TRUE);
        $data = $this->db->get_where('laporan_polisi_det', ['id_laporan_polisi_det' => $id])->row();

        if ($data) {
            // Logika Preview Berkas
            $preview = '';
            if ($data->berkas_laporan) {
                $ext = pathinfo($data->berkas_laporan, PATHINFO_EXTENSION);
                if ($ext == 'pdf') {
                    $preview = '<iframe src="' . base_url('assets/berkas_laporan/' . $data->berkas_laporan) . '" class="w-full h-[500px] rounded-2xl border border-base-300"></iframe>';
                } else {
                    $preview = '<img src="' . base_url('assets/berkas_laporan/' . $data->berkas_laporan) . '" class="w-full rounded-2xl border border-base-300 shadow-sm">';
                }
            } else {
                $preview = '<div class="bg-base-200 rounded-2xl h-60 flex flex-col items-center justify-center border-2 border-dashed border-base-300 opacity-30 italic">
                            <i class="mdi mdi-file-hidden text-5xl"></i>
                            <p class="text-[10px] font-bold uppercase mt-2">Belum Ada Berkas Terlampir</p>
                        </div>';
            }

            echo '
        <div class="flex justify-between items-start mb-8 animate-in fade-in duration-300">
            <div>
                <h2 class="text-3xl font-black text-primary uppercase italic tracking-tighter">' . $data->agenda_laporan_polisi_det . '</h2>
                <p class="text-[10px] font-bold opacity-40 mt-1 uppercase italic tracking-widest">' . date('d F Y', strtotime($data->tgl_agenda_laporan_polisi_det)) . '</p>
            </div>
            <div class="flex gap-2">
                <button onclick="editAgenda(' . $data->id_laporan_polisi_det . ')" class="btn btn-sm btn-warning rounded-xl px-4 font-black uppercase text-[10px] italic shadow-sm">Edit</button>
                <button onclick="hapusAgenda(' . $data->id_laporan_polisi_det . ')" class="btn btn-sm btn-error rounded-xl px-4 font-black uppercase text-[10px] text-white italic shadow-sm">Hapus</button>
            </div>
        </div>

        <div class="space-y-8">
            <section>
                <h4 class="text-[10px] font-black uppercase opacity-30 tracking-[0.3em] mb-3 border-l-4 border-primary pl-3">Uraian / Kesimpulan:</h4>
                <div class="text-sm font-medium leading-relaxed opacity-80 pl-4">' . nl2br($data->kesimpulan) . '</div>
            </section>

            <section>
                <h4 class="text-[10px] font-black uppercase opacity-30 tracking-[0.3em] mb-4 border-l-4 border-secondary pl-3">Berkas Pendukung:</h4>
                <div class="pl-4">' . $preview . '</div>
            </section>
        </div>';
        }
    }

    // AJAX: Ambil data untuk Modal Edit
    public function get_det_by_id($id)
    {
        $data = $this->db->get_where('laporan_polisi_det', ['id_laporan_polisi_det' => $id])->row();
        echo json_encode($data);
    }

    // AJAX: Hapus Data Detail
    public function hapus_det()
    {
        $id = $this->input->post('id');
        $this->db->where('id_laporan_polisi_det', $id)->delete('laporan_polisi_det');
        echo json_encode(['status' => true]);
    }


    public function save_detail()
    {
        $id = $this->input->post('id_laporan_polisi_det');
        $id_master = $this->input->post('id_laporan_polisi');

        $data = [
            'id_laporan_polisi'             => $id_master,
            'agenda_laporan_polisi_det'     => $this->input->post('agenda'),
            'tgl_agenda_laporan_polisi_det' => $this->input->post('tgl_agenda'),
            'kesimpulan'                    => $this->input->post('kesimpulan')
        ];

        // Logika Upload Berkas
        if (!empty($_FILES['berkas']['name'])) {
            $config['upload_path']   = './assets/berkas_laporan/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['file_name']     = 'LP_DET_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('berkas')) {
                $upload_data = $this->upload->data();
                $data['berkas_laporan'] = $upload_data['file_name']; // Pastikan kolom ini ada di DB
            }
        }

        if ($id) {
            $this->db->where('id_laporan_polisi_det', $id)->update('laporan_polisi_det', $data);
        } else {
            $this->db->insert('laporan_polisi_det', $data);
        }

        redirect('laporan_polisi/detail/' . $id_master);
    }
}
