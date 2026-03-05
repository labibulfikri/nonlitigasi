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

    function fetch_nonlit()
    {
        cek_csrf();

        $status = $this->input->post('status', true);
        $pic = $this->input->post('pic', true);
        $bidang = $this->input->post('bidang', true);
        $team = $this->input->post('team', true);
        $tahun = $this->input->post('tahun', true);

        if ($pic == "all") {
            $pic = "";
        }
        if ($status == "all") {
            $status = "";
        }
        if ($bidang == "all") {
            $bidang = "";
        }
        if ($team == "all") {
            $team = "";
        }
        if ($tahun) {
            $tahun = "";
        }
        $permohonan_nonlit = $this->input->post('permohonan_nonlit', true);

        $fetch_data = $this->m_laporan->make_datatables($status, $bidang, $pic, $team,  $tahun, $permohonan_nonlit);

        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {

            $no++;
            $sub_array = array();
            $sub_array['no'] = $no;
            $sub_array['permohonan_nonlit'] = "<strong> Permohonan Nonlit : </strong> <p>$row->permohonan_nonlit </p><br/> <strong> Nomor Register : </strong> $row->register_baru <br/> <strong> Team: </strong> <p> $row->team_nonlit</p>";
            $sub_array['register_baru'] = $row->register_baru;
            $sub_array['keterangan'] = substr($row->keterangan, 0, 100) . '...';
            $sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
            $sub_array['bidang'] = $row->bidang;
            $sub_array['pic'] = $row->pic;
            $sub_array['updated_by'] = "terakhir diupdate <strong>" . $row->updated_at . "</strong> - Oleh :" .  $row->username;
            $sub_array['updated_at'] = $row->updated_at;
            $sub_array['status'] = $row->status;
            $sub_array['team_nonlit'] = $row->team_nonlit;
            $sub_array['id'] = "<a href='" . base_url('nonlit/detail/' . $row->id) . "' class='btn btn-primary btn-sm'> Detail </a>";
            // $sub_array[] = '';


            $data[] = $sub_array;
        }
        // <a class='btn btn-sm btn-primary' href=' " . base_url('peta/map_by_id/' . $row->id) . " '>Edit Peta GIS</a>
        // <a class='btn btn-sm btn-primary' href='https://sigis.surabaya.go.id/popup/simbada/show-no-reg/$row->register_baru' target='_blank' rel='noopener noreferrer' > peta</a>

        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->m_laporan->get_all_data($status, $bidang, $pic, $team,  $tahun, $permohonan_nonlit),
            "recordsFiltered"           =>     $this->m_laporan->get_filtered_data($status, $bidang, $pic, $team, $tahun, $permohonan_nonlit),
            "data"                      =>     $data,
        );
        echo json_encode($output);
    }


    public function export_excel() {
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

    if($tahun && $tahun != 'all') $this->db->where('YEAR(n.tgl_nonlit)', $tahun);
    if($status) $this->db->where('n.status', $status);
    if($team)   $this->db->where('n.team_nonlit', $team);
    if($pic)    $this->db->where('n.pic', $pic);

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
