<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pic extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_laporan_pic');
        // Load necessary models or libraries if needed
    }

    public function index() {
       if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else { 

        $this->load->model('m_laporan_pic');


        $tahun = $this->input->get('tahun', true);
        
        // Kirim tahun ke model
        $reports = $this->m_laporan_pic->getSummaryPerPic($tahun); 

        // Sortir Rank: Total (Nonlit + LP + Masalah) terbanyak di atas
    usort($reports, function($a, $b) {
        $totalA = $a['total_nonlit'] + $a['total_lp'] + $a['total_masalah'];
        $totalB = $b['total_nonlit'] + $b['total_lp'] + $b['total_masalah'];
        return $totalB <=> $totalA;
    });

        $data = array(
            'masterpage' => 'layout/layout2',
            'content'    => 'laporan_pic/index',
            'list_pic'   => $reports,
            'title'      => 'Ranking Performa PIC'
        );
        $this->load->view($data['masterpage'], $data);
    }
    }
public function detail($id) {
    if ($this->session->userdata('status') != 'login') {
        redirect('auth/logout');
    } else {
        $this->load->model('m_laporan_pic');
        
        // Ambil tahun dari filter jika ada
        $tahun = $this->input->get('tahun');
        
        // Ambil data project beserta jumlah rapatnya
        // Pastikan model getDetailByPic mendukung filter tahun dan count rapat
        $projects = $this->m_laporan_pic->getDetailByPic($id, $tahun);
        $pic = $this->db->get_where('master_pic', ['id' => $id])->row_array();

        if (!$pic) show_404();

        // Inisialisasi counter statistik
        $stats = [
            'nonlit' => ['proses' => 0, 'selesai' => 0],
            'lp'     => ['proses' => 0, 'selesai' => 0],
            'masalah'=> ['proses' => 0, 'selesai' => 0]
        ];

        foreach ($projects as $p) {
            $status = (strtolower($p['status']) == 'selesai') ? 'selesai' : 'proses';
            if ($p['jenis'] == 'nonlit') $stats['nonlit'][$status]++;
            if ($p['jenis'] == 'laporan_polisi') $stats['lp'][$status]++;
            if ($p['jenis'] == 'permasalahan') $stats['masalah'][$status]++;
        }

        $data = array(
            'masterpage' => 'layout/layout2',
            'content'    => 'laporan_pic/detail',
            'pic_id'     => $id,
            'pic_name'   => $pic['nama_pic'],
            'projects'   => $projects,
            'stats'      => $stats,
            'title'      => 'Detail Performa PIC'
        );
        $this->load->view($data['masterpage'], $data);
    }
}
    // Add other methods for report generation, filtering, etc.
}