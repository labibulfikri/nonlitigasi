<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Laporan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_nonlit');
        $this->load->model('m_laporan');
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
                'content' => 'laporan/data_laporan',
                // 'footer' => 'layout/footer',
                'title' => 'Daftar Nonlitigasi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    public function fetch_nonlit()
    {
        // Cek CSRF (Pastikan fungsi ini ada di Base_Controller atau helper Anda)
        // cek_csrf(); 

        // Ambil data POST dari DataTables
        $status = $this->input->post('status', true);
        $pic = $this->input->post('pic', true);
        $bidang = $this->input->post('bidang', true);
        $team = $this->input->post('team', true);
        $tahun = $this->input->post('tahun', true);
        $permohonan_nonlit = $this->input->post('permohonan_nonlit', true);

        // Normalisasi Filter: Jika "all", kosongkan string untuk query SQL
        $status = ($status == "all") ? "" : $status;
        $pic    = ($pic == "all")    ? "" : $pic;
        $bidang = ($bidang == "all") ? "" : $bidang;
        $team   = ($team == "all")   ? "" : $team;
        $tahun  = ($tahun == "all")  ? "" : $tahun;

        // Panggil Model untuk ambil data
        $fetch_data = $this->m_laporan->make_datatables($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit);

        $data = array();
        $no = $this->input->post('start');

        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();

            $sub_array['no'] = $no;

            // Kolom Permohonan dengan desain teks bertingkat
            $sub_array['permohonan_nonlit'] = "
            <div class='flex flex-col'>
                <span class='font-bold text-slate-800 leading-tight'>$row->permohonan_nonlit</span>
                <span class='text-[10px] text-slate-400 mt-1 uppercase'>ID: $row->register_baru</span>
            </div>
        ";

            $sub_array['pic'] = $row->pic;
            $sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
            $sub_array['bidang'] = "<span class='badge badge-ghost badge-sm font-bold'>$row->bidang</span>";
            $sub_array['status'] = $row->status;
            $sub_array['team'] = $row->team_nonlit;

            // Tombol Aksi
            $sub_array['action'] = "
            <div class='flex justify-end'>
                <a href='" . base_url('nonlit/detail/' . $row->id) . "' class='btn btn-primary btn-xs rounded-lg px-4 shadow-sm'>
                    Detail
                </a>
            </div>
        ";

            $data[] = $sub_array;
        }

        $output = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $this->m_laporan->get_all_data($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit),
            "recordsFiltered" => $this->m_laporan->get_filtered_data($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit),
            "data"            => $data,
        );

        echo json_encode($output);
    }


    public function export_excel()
    {
        $tahun    = $this->input->get('tahun');
        $status   = $this->input->get('status');
        $team     = $this->input->get('team');
        $pic      = $this->input->get('pic');

        // Query untuk mengambil data nonlit dan progres terakhirnya
        $this->db->select('
        n.*, 
        d.kesimpulan, 
        d.tgl_rapat as tgl_progres_terakhir
    ');
        $this->db->from('nonlits n');

        // Join dengan subquery untuk mengambil detail paling akhir berdasarkan tgl_rapat
        $this->db->join('(
        SELECT id_nonlit, kesimpulan, tgl_rapat
        FROM nonlit_det
        WHERE id IN (
            SELECT MAX(id) 
            FROM nonlit_det 
            GROUP BY id_nonlit
        )
    ) d', 'n.id = d.id_nonlit', 'left');

        if ($tahun && $tahun != 'all') $this->db->where('YEAR(n.tgl_nonlit)', $tahun);
        if ($status) $this->db->where('n.status', $status);
        if ($team)   $this->db->where('n.team_nonlit', $team);
        if ($pic)    $this->db->where('n.pic', $pic);

        $query = $this->db->get();

        if (!$query) {
            die("Database Error: " . $this->db->error()['message']);
        }

        $data_laporan = $query->result_array();

        // Header Excel
        $filename = "Laporan_NonLitigasi_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $this->load->view('laporan/excel_template', ['data' => $data_laporan]);
    }
}
