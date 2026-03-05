<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('security');
		$this->load->library('form_validation');
		$this->load->model('m_nonlit');
		$this->load->model('m_home');

		$this->output->set_header('X-Frame-Options: SOMEORIGIN');
	}

	function gethari($hari)
	{
		$harinya = $hari;

		switch ($harinya) {
			case 'Sun':
				$getHari = "Minggu";
				break;
			case 'Mon':
				$getHari = "Senin";
				break;
			case 'Tue':
				$getHari = "Selasa";
				break;
			case 'Wed':
				$getHari = "Rabu";
				break;
			case 'Thu':
				$getHari = "Kamis";
				break;
			case 'Fri':
				$getHari = "Jumat";
				break;
			case 'Sat':
				$getHari = "Sabtu";
				break;
			default:
				$getHari = "Salah";
				break;
		}
		return $getHari;
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
				'content' => 'home/home3',
				// 'content' => 'home/home2',
				// 'footer' => 'layout/footer',
				'title' => 'Daftar Nonlitigasi'
			);
			$this->load->view($data['masterpage'], $data);
		}
	}

	// public function get_dashboard_data()
	// {
	// 	cek_csrf();
	// 	$tahun  = $this->input->post('tahun');
	// 	$status = $this->input->post('status');

	// 	$result = $this->m_home->get_dashboard_filtered($tahun, $status);

	// 	echo json_encode($result);
	// }
	//////////////////////
	// public function get_data()
	// {
	// 	$tahun = $this->input->post('tahun');
	// 	$status = $this->input->post('status');

	// 	// Query jumlah per team & status
	// 	$this->db->select('team_nonlit, status, COUNT(*) as total');
	// 	$this->db->from('nonlits');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	if ($status != 'all') {
	// 		$this->db->where('status', $status);
	// 	}
	// 	$this->db->group_by(['team_nonlit', 'status']);
	// 	$summary = $this->db->get()->result();

	// 	// Total summary (untuk angka di dashboard)
	// 	$totalSelesai = 0;
	// 	$totalProses = 0;
	// 	foreach ($summary as $row) {
	// 		if (strtolower($row->status) == 'selesai') {
	// 			$totalSelesai += $row->total;
	// 		} else {
	// 			$totalProses += $row->total;
	// 		}
	// 	}

	// 	echo json_encode([
	// 		'summary' => $summary,
	// 		'totalSelesai' => $totalSelesai,
	// 		'totalProses' => $totalProses
	// 	]);
	// }

	// public function get_detail()
	// {
	// 	$tahun = $this->input->post('tahun');
	// 	$status = $this->input->post('status');
	// 	$team   = $this->input->post('team');

	// 	$this->db->from('nonlits');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	if ($status != 'all') {
	// 		$this->db->where('status', $status);
	// 	}
	// 	if (!empty($team)) {
	// 		$this->db->where('team_nonlit', $team);
	// 	}
	// 	$detail = $this->db->get()->result();

	// 	echo json_encode($detail);
	// }

	public function get_data_chart()
	{
		cek_csrf();
		$tahun  = $this->input->post('tahun');
		$status = $this->input->post('status');

		// Query total
		$this->db->select('COUNT(*) as total');
		if ($tahun != 'all') {
			$this->db->where('YEAR(tgl_nonlit)', $tahun);
		}
		if ($status != 'all') {
			$this->db->where('status', $status);
		}
		$total = $this->db->get('nonlits')->row()->total;

		// Query total selesai
		$this->db->select('COUNT(*) as total');
		if ($tahun != 'all') {
			$this->db->where('YEAR(tgl_nonlit)', $tahun);
		}
		if ($status == 'all' || $status == 'selesai') {
			$this->db->where('status', 'selesai');
			$total_selesai = $this->db->get('nonlits')->row()->total;
		} else {
			$total_selesai = 0;
		}

		// Query total proses
		$this->db->select('COUNT(*) as total');
		if ($tahun != 'all') {
			$this->db->where('YEAR(tgl_nonlit)', $tahun);
		}
		if ($status == 'all' || $status == 'proses') {
			$this->db->where('status', 'proses');
			$total_proses = $this->db->get('nonlits')->row()->total;
		} else {
			$total_proses = 0;
		}

		// Chart bar: jumlah per tim
		$sql = "
        SELECT team_nonlit, 
            SUM(CASE WHEN status='selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status='proses' THEN 1 ELSE 0 END) as proses
        FROM nonlits
    ";
		$where = [];
		if ($tahun != 'all') $where[] = "YEAR(tgl_nonlit) = " . $this->db->escape($tahun);
		if ($status != 'all') $where[] = "status = " . $this->db->escape($status);
		if (!empty($where)) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}
		$sql .= " GROUP BY team_nonlit";
		$query_team = $this->db->query($sql)->result();

		// Chart pie: selesai vs proses
		$query_pie = [
			'selesai' => $total_selesai,
			'proses'  => $total_proses
		];

		echo json_encode([
			'total'         => $total,
			'total_selesai' => $total_selesai,
			'total_proses'  => $total_proses,
			'bar'           => $query_team,
			'pie'           => $query_pie
		]);
	}

	// Endpoint detail (lebih ringan & dinamis)
	public function get_data_detail()
	{
		cek_csrf();
		$tahun  = $this->input->post('tahun');
		$status = $this->input->post('status');
		$team   = $this->input->post('team_nonlit');

		$this->db->select('*');
		if ($tahun != 'all') {
			$this->db->where('YEAR(tgl_nonlit)', $tahun);
		}
		if ($status != 'all') {
			$this->db->where('status', $status);
		}
		if (!empty($team)) {
			$this->db->where('team_nonlit', $team);
		}

		$detail = $this->db->get('nonlits')->result();
		echo json_encode($detail);
	}

	// public function get_data_chart()
	// {
	// 	cek_csrf();
	// 	$tahun  = $this->input->post('tahun');
	// 	$status = $this->input->post('status');

	// 	// Query total
	// 	$this->db->select('COUNT(*) as total');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	if ($status != 'all') {
	// 		$this->db->where('status', $status);
	// 	}
	// 	$total = $this->db->get('nonlits')->row()->total;

	// 	// Query total selesai
	// 	$this->db->select('COUNT(*) as total');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	$this->db->where('status', 'selesai');
	// 	$total_selesai = $this->db->get('nonlits')->row()->total;

	// 	// Query total proses
	// 	$this->db->select('COUNT(*) as total');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	$this->db->where('status', 'proses');
	// 	$total_proses = $this->db->get('nonlits')->row()->total;

	// 	// Chart bar: jumlah per tim
	// 	$query_team = $this->db->query("
	//     SELECT team_nonlit, 
	//         SUM(CASE WHEN status='selesai' THEN 1 ELSE 0 END) as selesai,
	//         SUM(CASE WHEN status='proses' THEN 1 ELSE 0 END) as proses
	//     FROM nonlits
	//     " . ($tahun != 'all' ? "WHERE YEAR(tgl_nonlit) = $tahun" : "") . "
	//     GROUP BY team_nonlit
	// ")->result();

	// 	// Chart pie: selesai vs proses
	// 	$query_pie = [
	// 		'selesai' => $total_selesai,
	// 		'proses'  => $total_proses
	// 	];

	// 	// Data detail (untuk modal)
	// 	$this->db->select('*');
	// 	if ($tahun != 'all') {
	// 		$this->db->where('YEAR(tgl_nonlit)', $tahun);
	// 	}
	// 	if ($status != 'all') {
	// 		$this->db->where('status', $status);
	// 	}
	// 	$detail = $this->db->get('nonlits')->result();

	// 	echo json_encode([
	// 		'total'         => $total,
	// 		'total_selesai' => $total_selesai,
	// 		'total_proses'  => $total_proses,
	// 		'bar'           => $query_team,
	// 		'pie'           => $query_pie,
	// 		'detail'        => $detail
	// 	]);
	// }

	/////////////////////////

	function fetch_nonlit_tahun()
	{
		cek_csrf();
		$tahun = $this->input->post('tahun', true);
		$status = $this->input->post('status', true);

		if ($tahun == "" || $tahun == null) {
			$tahun = "2024";
		}

		if ($status == "" || $status == "all") {
			$status = null;
		}

		$fetch_data = $this->m_home->make_datatables($tahun, $status);

		$data = array();
		$no = $_POST['start'];
		foreach ($fetch_data as $row) {

			$no++;
			$sub_array = array();
			$sub_array['no'] = $no;
			$sub_array['permohonan_nonlit'] = "<a target='_blank' href='" . base_url('nonlit/detail/' . $row->id) . "' > $row->permohonan_nonlit</a>";
			$sub_array['keterangan'] = $row->keterangan;
			$sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
			$sub_array['bidang'] = $row->bidang;
			$sub_array['status'] = $row->status;
			$sub_array['team_nonlit'] = $row->team_nonlit;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"                      =>     intval($_POST["draw"]),
			"recordsTotal"              =>     $this->m_home->get_all_data($tahun, $status),
			"recordsFiltered"           =>     $this->m_home->get_filtered_data($tahun, $status),
			"data"                      =>     $data
		);
		echo json_encode($output);
	}


	function fetch_nonlit_kejati()
	{
		cek_csrf();

		$tahun = $this->input->post('tahun', true);
		$jaksa = $this->input->post('jaksa', true);


		if ($tahun == "" || $tahun == null) {
			$tahun = "2024";
		}


		$fetch_data = $this->m_home->make_datatables_status($tahun, $jaksa);
		$data = array();
		$no = $_POST['start'];
		foreach ($fetch_data as $row) {

			$no++;
			$sub_array = array();
			$sub_array['no'] = $no;
			$sub_array['permohonan_nonlit'] = $row->permohonan_nonlit;
			$sub_array['keterangan'] = $row->keterangan;
			$sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
			$sub_array['bidang'] = $row->bidang;
			$sub_array['status'] = $row->status;
			$sub_array['team_nonlit'] = $row->team_nonlit;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"                      =>     intval($_POST["draw"]),
			"recordsTotal"              =>     $this->m_home->get_all_data_status($tahun, $jaksa),
			"recordsFiltered"           =>     $this->m_home->get_filtered_data_status($tahun, $jaksa),
			"data"                      =>     $data
		);
		echo json_encode($output);
	}

	function filter_bytahun()
	{
		$this->form_validation->set_rules('tahun', 'harus di isi', 'required');

		if ($this->form_validation->run() == FALSE) {
			cek_csrf();
		} else {
			cek_csrf();
			$tahun = $this->input->post('tahun', TRUE);
			$status = $this->input->post('status', TRUE);

			if ($status == "all") {
				$status = null;
			}




			$byTahunKejatiProses = $this->m_home->getAllByTahunStatus($tahun, 'kejati', 'proses');
			$byTahunKejatiSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejati', 'selesai');
			$byTahunKejarisbyProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', 'proses');
			$byTahunKejarisbySelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', 'selesai');
			$byTahunKejariperakProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', 'proses');
			$byTahunKejariperakSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', 'selesai');
			// $byTahunKejatiProses = $this->m_home->getAllByTahunStatus($tahun, 'kejati', $status);
			// $byTahunKejatiSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejati', $status);
			// $byTahunKejarisbyProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', $status);
			// $byTahunKejarisbySelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', $status);
			// $byTahunKejariperakProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', $status);
			// $byTahunKejariperakSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', $status);

			$output = array(
				"kejati_proses"                      =>     $byTahunKejatiProses,
				"kejati_selesai"                      =>     $byTahunKejatiSelesai,
				"kejarisby_proses"                      =>     $byTahunKejarisbyProses,
				"kejarisby_selesai"                      =>     $byTahunKejarisbySelesai,
				"kejariperak_proses"                      =>     $byTahunKejariperakProses,
				"kejariperak_selesai"                      =>     $byTahunKejariperakSelesai,
			);

			echo json_encode($output);
		}
	}
	function filter_bytahun2()
	{
		$this->form_validation->set_rules('tahun', 'harus di isi', 'required');

		if ($this->form_validation->run() == FALSE) {
			cek_csrf();
		} else {
			cek_csrf();
			$tahun = $this->input->post('tahun', TRUE);
			$status = $this->input->post('status', TRUE);



			if ($status == "all") {
				$status = null;
			}

			$byTahunKejatiProses = $this->m_home->getAllByTahunStatus($tahun, 'kejati', 'proses');
			$byTahunKejatiSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejati', 'selesai');
			$byTahunKejarisbyProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', 'proses');
			$byTahunKejarisbySelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', 'selesai');
			$byTahunKejariperakProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', 'proses');
			$byTahunKejariperakSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', 'selesai');
			// $byTahunKejatiProses = $this->m_home->getAllByTahunStatus($tahun, 'kejati', $status);
			// $byTahunKejatiSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejati', $status);
			// $byTahunKejarisbyProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', $status);
			// $byTahunKejarisbySelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_sby', $status);
			// $byTahunKejariperakProses = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', $status);
			// $byTahunKejariperakSelesai = $this->m_home->getAllByTahunStatus($tahun, 'kejari_perak', $status);

			$output = array(
				"kejati_proses"                      =>     $byTahunKejatiProses,
				"kejati_selesai"                      =>     $byTahunKejatiSelesai,
				"kejarisby_proses"                      =>     $byTahunKejarisbyProses,
				"kejarisby_selesai"                      =>     $byTahunKejarisbySelesai,
				"kejariperak_proses"                      =>     $byTahunKejariperakProses,
				"kejariperak_selesai"                      =>     $byTahunKejariperakSelesai,
			);

			echo json_encode($output);
		}
	}

	function fetch_nonlit_bar()
	{
		cek_csrf();

		$tahun = $this->input->post('tahun', true);
		$jaksa = $this->input->post('jaksa', true);
		$status = $this->input->post('status', true);
		if ($jaksa == "KEJAKSAAN NEGERI SURABAYA") {
			$jaksa = 'kejari_sby';
		} else if ($jaksa == "KEJAKSAAN NEGERI PERAK") {
			$jaksa = 'kejari_perak';
		} else {
			$jaksa = 'kejati';
		}


		if ($tahun == "" || $tahun == null) {
			$tahun = "2024";
		}


		$fetch_data = $this->m_home->make_datatables_status2($tahun, $jaksa, $status);
		$data = array();
		$no = $_POST['start'];
		foreach ($fetch_data as $row) {

			$no++;
			$sub_array = array();
			$sub_array['no'] = $no;
			$sub_array['permohonan_nonlit'] = $row->permohonan_nonlit;
			$sub_array['keterangan'] = $row->keterangan;
			$sub_array['tgl_nonlit'] = date('d-m-Y', strtotime($row->tgl_nonlit));
			$sub_array['bidang'] = $row->bidang;
			$sub_array['status'] = $row->status;
			$sub_array['team_nonlit'] = $row->team_nonlit;
			$sub_array['btn'] = "<a target='_blank' href='" . base_url('nonlit/detail/' . $row->id) . "'  class='btn btn-primary'> Detail </a>";;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"                      =>     intval($_POST["draw"]),
			"recordsTotal"              =>     $this->m_home->get_all_data_status2($tahun, $jaksa, $status),
			"recordsFiltered"           =>     $this->m_home->get_filtered_data_status2($tahun, $jaksa, $status),
			"data"                      =>     $data
		);
		echo json_encode($output);
	}
}
