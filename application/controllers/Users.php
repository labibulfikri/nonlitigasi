<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->library('session', 'form_validation');
        $this->load->helper('captcha', 'array', 'form', 'url');
        $this->output->set_header('X-Frame-Options: SOMEORIGIN');
    }

    public function index()
    {

        if ($this->session->userdata('status') != 'login') {

            redirect('auth/logout');
        } else {
            $data = array(
                'masterpage' => 'layout/layout2',
                'navbar2' => 'layout/navbar2',
                'navbar_bawah' => 'layout/navbar_bawah2',
                'content' => 'users/data_user',
                'footer' => 'layout/footer',
                'title' => 'Daftar Users'
            );
            $this->load->view($data['masterpage'], $data);
        }
    }


    function fetch_users()
    {
        cek_csrf();
        $fetch_data = $this->m_user->make_datatables();

        $data = array();
        $no = $_POST['start'];
        foreach ($fetch_data as $row) {

            $no++;
            $sub_array = array();
            $sub_array['no'] = $no;
            $sub_array['username'] = $row->username;
            $sub_array['password'] = $row->password;
            $sub_array['role'] = $row->role;
            $sub_array['id'] = "<button data-bs-toggle='modal' data-bs-target='#modal_edit_role' id='editButtonUser' data-id='$row->id' data-username='$row->username' data-role='$row->role' class='btn btn-warning'> Edit Role </button>| 
            <button type='button' class='btn btn-sm btn-danger tombol_hapus_user' id='$row->id'> <i data-toggle='tooltip' title='Hapus' class='icofont-trash'></i> Hapus </button>";

            $data[] = $sub_array;
        }

        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->m_user->get_all_data(),
            "recordsFiltered"           =>     $this->m_user->get_filtered_data(),
            "data"                      =>     $data,
        );
        echo json_encode($output);
    }


    function tambah_data_user()
    {

        // Aturan validasi
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_valid_password');
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');


        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
            $errors = array(
                'username' => form_error('username'),
                'password' => form_error('password'),
                'password_confirm' => form_error('password_confirm')
            );


            echo json_encode(array('status' => 'gagal', 'errors' => $errors));
        } else {
            cek_csrf();
            $data = array(
                'username' => $this->input->post('username', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role' => $this->input->post('role', TRUE),
            );
            $exe = $this->m_user->insertdata($data);
            if ($exe > 0) {
                $response = array('status' => 'success', 'message' => 'Data berhasil disimpan.');
                echo json_encode($response);
            }
        }
    }

    // Custom callback untuk validasi password
    public function valid_password($password)
    {
        if (!preg_match('/[A-Z]/', $password)) {
            $this->form_validation->set_message('valid_password', 'Password harus mengandung minimal satu huruf besar.');
            return FALSE;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->form_validation->set_message('valid_password', 'Password harus mengandung minimal satu angka.');
            return FALSE;
        }
        if (!preg_match('/[\W]/', $password)) {
            $this->form_validation->set_message('valid_password', 'Password harus mengandung minimal satu karakter simbol.');
            return FALSE;
        }
        return TRUE;
    }

    function update_data()
    {


        $this->form_validation->set_rules('id', 'Harus Di Isi', 'required');
        $this->form_validation->set_rules('role', 'Harus Di Isi', 'required');


        if ($this->form_validation->run() == FALSE) {
            cek_csrf();
            echo json_encode(array('status' => 'gagal', 'message' => 'Harus di isi.'));
        } else {
            cek_csrf();

            date_default_timezone_set('Asia/Jakarta'); // Untuk WIB 

            $id = $this->input->post('id', TRUE);

            $role = $this->input->post('role', TRUE);


            $data  = array(
                "role" => $role,
                "id" => $id,
            );


            $exe = $this->m_user->update($data, $id);
            if ($exe > 0) {
                $response = array('status' => 'success', 'message' => 'Data berhasil disimpan.');
                echo json_encode($response);
            }
        }
    }

    //Untuk menghapus foto
    function remove_user()
    {

        //Ambil token foto
        $id = $this->input->post('id_user');
        $this->db->delete('users', array('id' => $id));

        //}
        echo "{}";
    }
}
