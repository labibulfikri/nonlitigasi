<<<<<<< HEAD
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_pic'); // Load model master
    }

    // Menampilkan halaman View
    public function index()
    {
        $data = array(

            'masterpage' => 'layout/layout2',
            // 'navbar2' => 'layout/navbar2',
            // 'navbar_bawah' => 'layout/navbar_bawah2',  
            'content' => 'pic/pic',
            'title' => 'Daftar PIC'


        );
        $this->load->view($data['masterpage'], $data);
    }

    // Ambil data untuk DataTables
    public function fetch_pic()
    {
        $list = $this->m_pic->get_datatables_pic();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row['no'] = $no;
            $row['nama'] = $field->nama_pic;
            $row['bidang'] = $field->bidang_pic;
            $row['status'] = $field->status_pic;

            // Tombol Aksi
            $row['action'] = '
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn-edit w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all" data-id="' . $field->id . '">
                        <i class="mdi mdi-pencil text-sm"></i>
                    </button>
                    <button type="button" onclick="deleteData(' . $field->id . ')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                        <i class="mdi mdi-trash-can-outline text-sm"></i>
                    </button>
                </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_pic->count_all_pic(),
            "recordsFiltered" => $this->m_pic->count_filtered_pic(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    // Simpan atau Update Data
    public function save_pic()
    {

        cek_csrf();
        $id = $this->input->post('id');
        $data = array(
            'nama_pic'    => $this->input->post('nama_pic', true),
            'bidang_pic'  => $this->input->post('bidang_pic', true),
            'status_pic'  => $this->input->post('status_pic', true),
        );

        if (!empty($id)) {
            $result = $this->m_pic->update_pic($id, $data);
            $msg = "Data PIC berhasil diperbarui";
        } else {
            $result = $this->m_pic->insert_pic($data);
            $msg = "PIC baru berhasil ditambahkan";
        }

        // Perbaikan: Hanya kirim success jika $result bernilai TRUE
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => $msg]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan ke database']);
        }
    }

    public function delete_pic($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        // Panggil model untuk hapus
        $hapus = $this->m_pic->delete_pic($id);

        if ($hapus) {
            echo json_encode(['status' => 'success', 'message' => 'Data PIC berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }
    }
    // Ambil 1 data untuk Edit
    public function get_pic_by_id($id)
    {
        $data = $this->m_pic->get_pic_by_id($id);
        echo json_encode([
            'id'      => $data->id,
            'nama'    => $data->nama_pic,
            'bidang'  => $data->bidang_pic,
            'status'  => $data->status_pic,
        ]);
    }
}
=======
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_pic'); // Load model master
    }

    // Menampilkan halaman View
    public function index()
    {
        $data = array(

            'masterpage' => 'layout/layout2',
            // 'navbar2' => 'layout/navbar2',
            // 'navbar_bawah' => 'layout/navbar_bawah2',  
            'content' => 'pic/pic',
            'title' => 'Daftar PIC'


        );
        $this->load->view($data['masterpage'], $data);
    }

    // Ambil data untuk DataTables
    public function fetch_pic()
    {
        $list = $this->m_pic->get_datatables_pic();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row['no'] = $no;
            $row['nama'] = $field->nama_pic;
            $row['bidang'] = $field->bidang_pic;
            $row['status'] = $field->status_pic;

            // Tombol Aksi
            $row['action'] = '
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn-edit w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all" data-id="' . $field->id . '">
                        <i class="mdi mdi-pencil text-sm"></i>
                    </button>
                    <button type="button" onclick="deleteData(' . $field->id . ')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                        <i class="mdi mdi-trash-can-outline text-sm"></i>
                    </button>
                </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_pic->count_all_pic(),
            "recordsFiltered" => $this->m_pic->count_filtered_pic(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    // Simpan atau Update Data
    public function save_pic()
    {

        cek_csrf();
        $id = $this->input->post('id');
        $data = array(
            'nama_pic'    => $this->input->post('nama_pic', true),
            'bidang_pic'  => $this->input->post('bidang_pic', true),
            'status_pic'  => $this->input->post('status_pic', true),
        );

        if (!empty($id)) {
            $result = $this->m_pic->update_pic($id, $data);
            $msg = "Data PIC berhasil diperbarui";
        } else {
            $result = $this->m_pic->insert_pic($data);
            $msg = "PIC baru berhasil ditambahkan";
        }

        // Perbaikan: Hanya kirim success jika $result bernilai TRUE
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => $msg]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan ke database']);
        }
    }

    public function delete_pic($id)
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        // Panggil model untuk hapus
        $hapus = $this->m_pic->delete_pic($id);

        if ($hapus) {
            echo json_encode(['status' => 'success', 'message' => 'Data PIC berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }
    }
    // Ambil 1 data untuk Edit
    public function get_pic_by_id($id)
    {
        $data = $this->m_pic->get_pic_by_id($id);
        echo json_encode([
            'id'      => $data->id,
            'nama'    => $data->nama_pic,
            'bidang'  => $data->bidang_pic,
            'status'  => $data->status_pic,
        ]);
    }
}
>>>>>>> Initial commit dari server
