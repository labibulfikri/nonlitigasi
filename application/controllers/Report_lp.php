<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_lp extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load model dan library yang dibutuhkan
        $this->load->model('M_report_laporan_polisi');
        $this->load->helper('url');
    }

    public function index()
    {
        // Data pendukung untuk dropdown filter
        $data['list_pic']   =  $this->M_report_laporan_polisi->get_unique_pic();

        // Metadata Page
        $data['title']      = 'Laporan Polisi';
        $data['content']    = 'report/report_lp'; // Nama file view Anda
        $data['masterpage'] = 'layout/layout2';   // Master template

        $this->load->view($data['masterpage'], $data);
    }

    /**
     * Fungsi untuk melayani request DataTables (Server-Side)
     */
    public function fetch_lp()
    {
        cek_csrf();
        // Ambil parameter filter dari POST (DataTables)
        $filter = [
            'tahun'  => $this->input->post('tahun'),
            'status' => $this->input->get('status'), // Jika dikirim lewat GET/POST
            'nomor'  => $this->input->post('nomor'),
            'pic'    => $this->input->post('pic')
        ];

        // Custom logic: Ambil data dari model khusus untuk datatables
        $list = $this->M_report_laporan_polisi->get_datatables($filter);
        $data = array();
        $no   = $this->input->post('start');

        foreach ($list as $lp) {
            $no++;
            $row = array();
            $row['no']    = $no;

            // Gabungkan Nomor dan Judul untuk tampilan UI yang bersih
            $row['nomor_judul'] = '
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-indigo-600">' . $lp->nomor_polisi . '</span>
                    <span class="font-black uppercase text-slate-700 leading-tight">' . $lp->judul_laporan_polisi . '</span>
                </div>';

            $row['pelapor'] = '<span class="font-bold">' . $lp->pelapor . '</span>';
            $row['team_polisi'] = '<span class="text-xs">' . $lp->team_polisi . '</span>';
            $row['pic_laporan_polisi'] = $lp->pic_laporan_polisi;
            $row['status_laporan_polisi'] = $lp->status_laporan_polisi;

            // Tombol Aksi (Detail)
            // $row['action'] = '
            //     <button onclick="viewDetail(\'POLISI\', ' . $lp->id_laporan_polisi . ')" class="btn btn-xs btn-ghost text-indigo-600 font-black italic tracking-tighter">
            //         <i class="mdi mdi-eye mr-1"></i> DETAIL
            //     </button>';
            $row['action'] = '
    <a href="' . base_url('laporan_polisi/detail/' . $lp->id_laporan_polisi) . '" 
       class="btn btn-xs btn-primary rounded-lg font-black italic tracking-tighter px-4">
       <i class="mdi mdi-eye mr-1"></i> DETAIL
    </a>';

            $data[] = $row;
        }

        $output = array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->M_report_laporan_polisi->count_all(),
            "recordsFiltered" => $this->M_report_laporan_polisi->count_filtered($filter),
            "data"            => $data,
        );

        echo json_encode($output);
    }

    /**
     * Fungsi Export Excel
     */
    public function export_excel()
    {
        // Ambil filter dari URL (GET)
        $filter = [
            'tahun'      => $this->input->get('tahun'),
            'status'     => $this->input->get('status'),
            'nomor'      => $this->input->get('nomor'),
            'pic'        => $this->input->get('pic')
        ];

        // Ambil data hasil filter
        $data['results'] = $this->M_report_laporan_polisi->get_report_data($filter);
        $data['title']   = "LAPORAN_POLISI_" . date('Ymd');

        // Load view format excel (tanpa layout)
        $this->load->view('report/export_excel_lp', $data);
    }
}
