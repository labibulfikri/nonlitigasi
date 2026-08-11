<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Laporan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_nonlit');
        $this->load->model('m_laporan');
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
            $data_pic = $this->m_pic->get_all_pic();
            $data = array(
                'masterpage' => 'layout/layout2',
                // 'navbar2' => 'layout/navbar2',
                // 'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'laporan/data_laporan',
                // 'footer' => 'layout/footer',
                'list_pic' => $data_pic,
                'title' => 'Daftar Nonlitigasi'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }

    // public function fetch_nonlit() {
    //     $list = $this->m_laporan->get_datatables();
    //     $data = array();
    //     $no = $_POST['start'];

    //     foreach ($list as $field) {
    //         $no++;
    //         $row = array();
    //         $row['no'] = $no;

    //         // Format Informasi Perkara + Instansi
    //         $team_label = !empty($field->team_nonlit) ? strtoupper(str_replace('_', ' ', $field->team_nonlit)) : 'INTERNAL';
    //         $row['permohonan_nonlit'] = '
    //             <div class="flex flex-col">
    //                 <span class="font-black text-slate-700 leading-tight">'.strtoupper($field->permohonan_nonlit).'</span>
    //                 <div class="flex items-center gap-2 mt-1">
    //                     <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold uppercase italic">
    //                         <i class="fa-solid fa-building-shield mr-1"></i>'.$team_label.'
    //                     </span>
    //                 </div>
    //             </div>';

    //         $row['pic'] = $field->pic;
    //         $row['tgl_nonlit'] = date('d/m/Y', strtotime($field->tgl_nonlit));
    //         $row['bidang'] = '<span class="uppercase font-bold text-[10px] text-slate-500">'.$field->jenis.'</span>';
    //         $row['status'] = $field->status;

    //         // Tombol Aksi Detail
    //         $row['action'] = '
    //             <div class="flex justify-end">
    //                 <a href="'.base_url('nonlit/detail/'.$field->id).'" class="btn btn-sm btn-ghost bg-slate-50 hover:bg-slate-900 hover:text-white rounded-xl shadow-none border-none group">
    //                     <i class="fa-solid fa-eye text-indigo-500 group-hover:text-white mr-2"></i> Detail
    //                 </a>
    //             </div>';

    //         $data[] = $row;
    //     }

    //     $output = array(
    //         "draw" => $_POST['draw'],
    //         "recordsTotal" => $this->m_laporan->count_all(),
    //         "recordsFiltered" => $this->m_laporan->count_filtered(),
    //         "data" => $data,
    //     );
    //     echo json_encode($output);
    // }

    public function fetch_nonlit()
    {
        $list = $this->m_laporan->get_datatables();
        $data = array();
        $no = $this->input->post('start');

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row['no'] = $no;

            // Format Informasi Perkara + Instansi
            $team_label = !empty($field->team_nonlit) ? strtoupper(str_replace('_', ' ', $field->team_nonlit)) : 'INTERNAL';
            $row['permohonan_nonlit'] = '
            <div class="flex flex-col">
                <span class="font-black text-slate-700 leading-tight">' . strtoupper($field->permohonan_nonlit) . '</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold uppercase italic">
                        <i class="fa-solid fa-building-shield mr-1"></i>' . $team_label . '
                    </span>
                </div>
            </div>';
            $row['team_nonlit'] = !empty($field->team_nonlit) ? strtoupper(str_replace('_', ' ', $field->team_nonlit)) : 'INTERNAL';

            $row['pic'] = $field->pic;
            $row['register_baru'] = $field->register_baru; // Menampilkan register_baru
            $row['tgl_nonlit'] = !empty($field->tgl_nonlit) ? date('d/m/Y', strtotime($field->tgl_nonlit)) : '-';
            $row['bidang'] = '<span class="uppercase font-bold text-[10px] text-slate-500">' . $field->jenis . '</span>';
            $row['status'] = $field->status;

            // Tombol Aksi Detail
            $row['action'] = '
            <div class="flex justify-end">
                <a href="' . base_url('nonlit/detail/' . $field->id) . '" class="btn btn-sm btn-ghost bg-slate-50 hover:bg-slate-900 hover:text-white rounded-xl shadow-none border-none group">
                    <i class="fa-solid fa-eye text-indigo-500 group-hover:text-white mr-2"></i> Detail
                </a>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->m_laporan->count_all(),
            "recordsFiltered" => $this->m_laporan->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }



    // public function export_excel()
    // {
    //     $tahun    = $this->input->get('tahun');
    //     $status   = $this->input->get('status');
    //     $team     = $this->input->get('team');
    //     $pic      = $this->input->get('pic');

    //     // Query untuk mengambil data nonlit dan progres terakhirnya
    //     $this->db->select('
    //     n.*, 
    //     d.kesimpulan, 
    //     d.tgl_rapat as tgl_progres_terakhir
    // ');
    //     $this->db->from('nonlits n');

    //     // Join dengan subquery untuk mengambil detail paling akhir berdasarkan tgl_rapat
    //     $this->db->join('(
    //     SELECT id_nonlit, kesimpulan, tgl_rapat
    //     FROM nonlit_det
    //     WHERE id IN (
    //         SELECT MAX(id) 
    //         FROM nonlit_det 
    //         GROUP BY id_nonlit
    //     )
    // ) d', 'n.id = d.id_nonlit', 'left');

    //     if ($tahun && $tahun != 'all') $this->db->where('YEAR(n.tgl_nonlit)', $tahun);
    //     if ($status) $this->db->where('n.status', $status);
    //     if ($team)   $this->db->where('n.team_nonlit', $team);
    //     if ($pic)    $this->db->where('n.pic', $pic);

    //     $query = $this->db->get();

    //     if (!$query) {
    //         die("Database Error: " . $this->db->error()['message']);
    //     }

    //     $data_laporan = $query->result_array();

    //     // Header Excel
    //     $filename = "Laporan_NonLitigasi_" . date('Ymd_His') . ".xls";
    //     header("Content-Type: application/vnd.ms-excel");
    //     header("Content-Disposition: attachment; filename=\"$filename\"");

    //     $this->load->view('laporan/excel_template', ['data' => $data_laporan]);
    // }

    public function export_excel()
    {
        $tahun      = $this->input->get('tahun');
        $status     = $this->input->get('status');
        $team       = $this->input->get('team');
        $pic        = $this->input->get('pic');
        $permohonan = $this->input->get('permohonan_nonlit', true);

        // 1. Query Utama dengan LEFT JOIN
        $this->db->select('
        n.*, 
        d.kesimpulan, 
        d.tgl_rapat as tgl_progres_terakhir
    ');
        $this->db->from('nonlits n');

        $this->db->join('(
        SELECT max_det.id_nonlit, max_det.kesimpulan, max_det.tgl_rapat
        FROM nonlit_det max_det
        INNER JOIN (
            SELECT id_nonlit, MAX(id) as max_id 
            FROM nonlit_det 
            GROUP BY id_nonlit
        ) latest ON max_det.id = latest.max_id
    ) d', 'd.id_nonlit = n.id', 'left');

        // --- FILTER TAHUN (Multi-Select Support) ---
        if (!empty($tahun)) {
            if (is_array($tahun)) {
                $this->db->where_in('YEAR(n.tgl_nonlit)', $tahun);
            } elseif ($tahun != 'all') {
                $this->db->where('YEAR(n.tgl_nonlit)', $tahun);
            }
        }

        // --- FILTER STATUS ---
        if (!empty($status)) {
            if (is_array($status)) {
                $this->db->where_in('n.status', $status);
            } else {
                $this->db->where('n.status', $status);
            }
        }

        // --- FILTER TEAM ---
        if (!empty($team)) {
            if (is_array($team)) {
                $this->db->where_in('n.team_nonlit', $team);
            } else {
                $this->db->where('n.team_nonlit', $team);
            }
        }

        // --- FILTER PIC ---
        if (!empty($pic)) {
            if (is_array($pic)) {
                $this->db->where_in('n.pic', $pic);
            } else {
                $this->db->where('n.pic', $pic);
            }
        }

        // --- FILTER NAMA PERMOHONAN ---
        if ($permohonan) {
            $this->db->like('n.permohonan_nonlit', $permohonan);
        }

        $this->db->order_by('n.tgl_nonlit', 'DESC');
        $query = $this->db->get();

        if (!$query) {
            die("Gagal mengambil data: " . $this->db->error()['message']);
        }

        $result = $query->result_array();

        foreach ($result as $key => $val) {
            if (empty($val['kesimpulan'])) {
                $result[$key]['kesimpulan'] = '-';
            }
            if (empty($val['tgl_progres_terakhir'])) {
                $result[$key]['tgl_progres_terakhir'] = '-';
            }
        }

        $data['data'] = $result;

        // 2. Set Header Download Excel
        $filename = "Laporan_Nonlit_" . date('Ymd_His') . ".xls";
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('laporan/excel_template', $data);
    }
    public function export_excel2()
    {
        $tahun   = $this->input->get('tahun', true);
        $status  = $this->input->get('status', true);
        $team    = $this->input->get('team', true);
        $pic     = $this->input->get('pic', true); // Ini berisi ID dari dropdown

        // Query utama
        $this->db->select('
        n.*, 
        p.nama_pic, 
        d.kesimpulan, 
        d.tgl_rapat as tgl_progres_terakhir
    ');
        $this->db->from('nonlits n');

        // Join Master PIC untuk mendapatkan Nama
        $this->db->join('master_pic p', 'p.id = n.id_pic', 'left');

        // Join dengan subquery progres terakhir
        $this->db->join('(
        SELECT id_nonlit, kesimpulan, tgl_rapat
        FROM nonlit_det
        WHERE id IN (
            SELECT MAX(id) 
            FROM nonlit_det 
            GROUP BY id_nonlit
        )
    ) d', 'n.id = d.id_nonlit', 'left');

        // Apply Filter
        if ($tahun && $tahun != 'all') {
            $this->db->where('YEAR(n.tgl_nonlit)', $tahun);
        }
        if ($status) {
            $this->db->where('n.status', $status);
        }
        if ($team) {
            $this->db->where('n.team_nonlit', $team);
        }
        // Perbaikan: Filter berdasarkan ID PIC
        if ($pic) {
            $this->db->where('n.id_pic', $pic);
        }

        $this->db->order_by('n.tgl_nonlit', 'DESC');
        $query = $this->db->get();

        if (!$query) {
            die("Database Error: " . $this->db->error()['message']);
        }

        $data_laporan = $query->result_array();

        // Header Excel
        $filename = "Laporan_NonLitigasi_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        // Load view template excel
        $this->load->view('laporan/excel_template', ['data' => $data_laporan]);
    }
}
