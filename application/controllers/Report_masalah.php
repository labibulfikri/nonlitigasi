<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_masalah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_report_masalah');
    }

    public function index()
    {
        $data = [
            'list_pic'   => $this->M_report_masalah->get_unique_pic(),
            'title'      => 'Laporan Permasalahan',
            'content'    => 'report/report_masalah',
            'masterpage' => 'layout/layout2'
        ];
        $this->load->view($data['masterpage'], $data);
    }

    public function fetch_masalah()
    {
        cek_csrf();
        $filter = [
            'tahun'   => $this->input->post('tahun'),
            'status'  => $this->input->post('status'),
            'pic'     => $this->input->post('pic'),
            'keyword' => $this->input->post('keyword')
        ];

        $list = $this->M_report_masalah->get_datatables($filter);
        $data = array();
        $no   = $this->input->post('start');

        foreach ($list as $ms) {
            $no++;
            $row = array();
            $row['no'] = $no;
            $row['info_masalah'] = '
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-rose-600">ID: MSL-' . $ms->id_masalah . '</span>
                    <span class="font-black uppercase text-slate-700 leading-tight">' . $ms->nama_masalah . '</span>
                </div>';
            $row['alamat_masalah'] = '<span class="text-xs font-medium text-slate-500 italic">' . $ms->alamat_masalah . '</span>';
            $row['pic_masalah'] = '<span class="font-bold">' . $ms->pic_masalah . '</span>';
            $row['status_masalah'] = $ms->status_masalah;
            $row['action'] = '
                <a href="' . base_url('masalah/detail/' . $ms->id_masalah) . '" class="btn btn-xs btn-primary rounded-lg font-black italic tracking-tighter px-4">
                    DETAIL
                </a>';

            $data[] = $row;
        }

        $output = [
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->M_report_masalah->count_all(),
            "recordsFiltered" => $this->M_report_masalah->count_filtered($filter),
            "data"            => $data,
        ];
        echo json_encode($output);
    }

    public function export_excel()
    {


        $filter = [
            'tahun'   => $this->input->get('tahun'),
            'status'  => $this->input->get('status'),
            'pic'     => $this->input->get('pic'),
            'keyword' => $this->input->get('keyword')
        ];
        $data['results'] = $this->M_report_masalah->get_report_data($filter);
        $data['title']   = "LAPORAN_PERMASALAHAN_" . date('Ymd');
        $this->load->view('report/export_masalah_excel', $data);
    }
}
