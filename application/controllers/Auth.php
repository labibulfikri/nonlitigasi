<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_auth');
        $this->load->library('session', 'form_validation');
        $this->load->helper('captcha', 'array', 'form', 'url');
        $this->output->set_header('X-Frame-Options: SOMEORIGIN');
    }

    public function index()
    {
        $options = array(
            'img_path' => './assets/captcha/', #folder captcha yg sudah dibuat tadi
            'img_url' => base_url('/assets/captcha'), #ini arahnya juga ke folder captcha
            'img_width' => '145', #lebar image captcha
            'img_height' => '45', #tinggi image captcha
            'expiration' => 7200, #waktu expired
            'word_length'   => 5,
            // 'word'          => $cap['word'],
            'font_path' => FCPATH . 'assets/font/coolvetica.ttf', #load font jika mau ganti fontnya
            'pool' => '0123456789', #tipe captcha (angka/huruf, atau kombinasi dari keduanya)

            # atur warna captcha-nya di sini ya.. gunakan kode RGB
            'colors' => array(
                'background' => array(242, 242, 242),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($options);
        $data['image'] = $cap['image'];
        $this->session->set_userdata('mycaptcha', $cap['word']);
        $data['word'] = $this->session->userdata('mycaptcha');

        $this->load->view('login/login', $data);
    }

    function create_captcha()
    {
        $options = array(
            'img_path' => './assets/captcha/', #folder captcha yg sudah dibuat tadi
            'img_url' => base_url('./assets/captcha'), #ini arahnya juga ke folder captcha
            'img_width' => '145', #lebar image captcha
            'img_height' => '45', #tinggi image captcha
            'expiration' => 7200, #waktu expired
            'word_length'   => 5,
            // 'word'          => $cap['word'],
            'font_path' => FCPATH . 'assets/font/coolvetica.ttf', #load font jika mau ganti fontnya
            'pool' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', #tipe captcha (angka/huruf, atau kombinasi dari keduanya)

            # atur warna captcha-nya di sini ya.. gunakan kode RGB
            'colors' => array(
                'background' => array(242, 242, 242),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($options);
        $data['image'] = $cap['image'];
        $this->session->set_userdata('mycaptcha', $cap['word']);
        $data['word'] = $this->session->userdata('mycaptcha');

        return $data['image'];
    }

    public function reload_captcha()
    {
        $new_captcha = $this->create_captcha();
        echo "" . $new_captcha;
    }

    function check_captcha()
    {
        $this->load->helper('form');
        if ($this->input->post('captcha') == $this->session->userdata('mycaptcha')) {
            $this->cek_login();
        } else {
            $this->form_validation->set_message('check_captcha', 'Captcha tidak sama');
            echo $this->session->set_flashdata('pesan', "<div class='alert alert-danger alert-dismissable' style='margin-top:20px'>
            Captcha Salah !</h4> 
          </div>");

            redirect(base_url("Auth"));
        }
    }
    public function cek_login()
    {


        // $secretKey = "6LdglnMpAAAAADGgCPByePFtyuA1VrzP3PWgeyTX";
        // $response = $_POST['g-recaptcha-response'];

        // $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response";

        // $verification = file_get_contents($verifyUrl);
        // $verificationData = json_decode($verification);

        // if ($verificationData->success == true) {
        $this->form_validation->set_rules('username', 'Harus Di Isi', 'required|trim');
        $this->form_validation->set_rules('password', 'Harus Di Isi', 'required');
        if ($this->form_validation->run() == FALSE) {
            redirect(base_url('auth'));
        } else {
            cek_csrf();
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);


            $cek  = $this->db->get_where('users', ['username' => $username]);


            if ($cek->num_rows() > 0) {
                // kita ambil isi dari record
                $hasil = $cek->row();

                // var_dump($hasil->password);
                // var_dump($password);
                // exit();

                if (password_verify($password, $hasil->password)) {


                    // membuat session
                    $this->session->set_userdata('id', $hasil->id);
                    $this->session->set_userdata('id_level', $hasil->id_level);
                    $this->session->set_userdata('is_login', TRUE);
                    $this->session->set_userdata('status', "login");
                    $this->session->set_userdata('username', $hasil->username);
                    $this->session->set_userdata('role', $hasil->role);

                    // redirect ke admin
                    redirect(base_url('home'));
                } else {

                    // jika password salah
                    echo $this->session->set_flashdata('pesan', "<div class='alert alert-danger alert-dismissable' style='margin-top:20px'>
                           Password Salah, Coba Lagi !</h4> 
                           </div>");
                    redirect(base_url('auth'));
                }
            } else {

                echo $this->session->set_flashdata('pesan', "<div class='alert alert-danger alert-dismissable' style='margin-top:20px'>
                            Username Salah , Coba Lagi !</h4> 
                            </div>");
                redirect(base_url("auth"));
            }
        }
        // } else {
        //     redirect(base_url('auth'));
        // }
    }

    // public function login_sso()
    // {
    //     // SSO
    //     $clien_id = "9b56b8ea-4295-4d6b-97ec-2e8040c9756e";
    //     $client_secret = "XSnSqvr6BfNjsBbNDXCYgEKy9gN0GjsGF1OEZT2s";

    //     $url = "URL YANG MENGARAH KE FUNGSI CALLBACK YANG DIDAFTARKAN PADA KANTORKU"; // URL yang sudah didaftarkan sebelumnya di Kantorku
    //     $query = http_build_query([
    //         'client_id' => $clientId,
    //         'redirect_uri' => $url,
    //         'response_type' => 'code',
    //         'scope' => '',
    //     ]);
    //     //REDIRECT KE KANTORKU
    //     return redirect('https://kantorku.surabaya.go.id/oauth/authorize?' . $query);
    // }


    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url("auth"));
    }

    function lupa_password()
    {
        $this->load->view('user/forgot_password');
    }

    function cek_username()
    {
        $this->form_validation->set_rules('username', 'username', 'required|trim|valid_username', array(
            'required'      => 'Harus Di isi.',
            'valid_username'   =>  'username Salah'
        ));
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('user/user_login');
        } else {
            $username = $this->input->post('username');

            $cek = $this->m_auth->user_by_username("user", $username)->num_rows();
            $data['dt'] = $this->m_auth->user_by_username("user", $username)->row();


            if ($cek >= 1) {
                $this->load->view('user/reset_password', $data);
            } else {
                echo "<script>
      alert('username Belum Terdaftar');
      window.location.href='" . base_url('auth/lupa_password') . "';
      </script>";

                // $this->load->view('user/lupa_password');
            }
        }
    }

    function ad_reset_password()
    {

        $this->form_validation->set_rules('password1', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password2', 'Password', 'required|matches[password1]', array(
            'required'      => 'Harus Di isi.',
            'matches'     => 'Password Harus sama.'
        ));

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('user/lupa_password');
            // $this->load->view('user/reset_password', $data);
        } else {

            $id_user = $this->input->post('id_user');
            $password = md5($this->input->post('password1'));

            $update = $this->m_auth->update_password($id_user, $password);

            if ($update) {
                echo "<script>
      alert('Password berhasil diperbarui');
      window.location.href = ' " . base_url('auth/logout') . "';
      </script>";

                // redirect(base_url("Auth"));
            } else {
            }
        }
    }

    function edit_pass($id)
    {



        // $cek  = $this->db->get_where('t_user', ['id_user' => $id_user])->num_rows();
        $cek  = $this->db->get_where('users', ['id' => $id])->row();

        $data = array(
            'masterpage' => 'layout/layout2',
            'navbar2' => 'layout/navbar2',
            'navbar_bawah' => 'layout/navbar_bawah2',
            'content' => 'edit_password',
            'footer' => 'layout/footer',
            'title' => 'Edit',
            'user' => $cek,
            'id_user' => $id


        );
        $this->load->view($data['masterpage'], $data);
    }

    function do_edit_password()
    {
        //     $id_user = $this->input->post('id_user', true);
        //     $where = array(
        //         'id' => $this->input->post('id_user', true)
        //     );
        //     $cek  = $this->m_auth->verify('users', $where);

        //     if ($cek->num_rows() > 0) {
        //         // kita ambil isi dari record
        //         $hasil = $cek->row();

        //         if (password_verify($this->input->post('password'), $hasil->password)) {

        //             $this->form_validation->set_rules(
        //                 'passwordBaru',
        //                 'Password',
        //                 // 'required|min_length[8]|regex_match[/^(?=.*[0-9])(?=.*[a-zA-Z])\S+$/]',
        //                 'required|min_length[12]|callback_strong_password',
        //                 array(
        //                     'required' => 'Password harus diisi.',
        //                     'min_length' => 'Password harus memiliki setidaknya 12 karakter.',
        //                     'regex_match' => 'Password harus mengandung setidaknya satu angka dan satu karakter.'
        //                 )
        //             );

        //             if ($this->form_validation->run() == FALSE) {
        //                 // Validasi gagal, tampilkan pesan kesalahan
        //                 // $this->load->view('form_view');
        //                $this->session->set_flashdata('error', validation_errors());
        //                 redirect('auth/edit_pass/' . $id_user);
        //             } else {
        //                 $update  = array(
        //                     'id_user' => $this->input->post('id_user', true),
        //                     'password' => password_hash($this->input->post('passwordBaru', true), PASSWORD_DEFAULT),
        //                 );
        //                 $edit = $this->m_auth->do_edit($update, $this->input->post('id_user', true));
        //                 if ($edit > 0) {
        //                     echo "<script type='text/javascript'>
        //         alert(' Berhasil di Update ');
        //         window.location.href ='" . base_url('auth/edit_pass/' . $id_user) . "';
        //         </script>";
        //                 } else {
        //                     echo "<script type='text/javascript'>
        //         alert(' gagal ');
        //         window.location.href ='" . base_url('auth/edit_pass/' . $id_user) . "';
        //         </script>";
        //                 }
        //             }
        //         } else {
        //             echo "<script type='text/javascript'>
        //             alert(' Password Salah ');
        //             window.location.href ='" . base_url('auth/edit_pass/' . $id_user) . "';
        //             </script>";
        //         }
        //     } else {

        //         // jika username salah
        //         echo "<script type='text/javascript'>
        //         alert(' gagal ');
        //         window.location.href ='" . base_url('auth/edit_pass/' . $id_user) . "';
        //         </script>";
        //     }

        $id_user = $this->input->post('id_user', true);

        // Validasi form untuk password baru
        $this->form_validation->set_rules(
            'passwordBaru',
            'Password',
            'required|min_length[8]|callback_strong_password',
            array(
                'required' => 'Password harus diisi.',
                'min_length' => 'Password harus memiliki setidaknya 8 karakter.',
            )
        );

        if ($this->form_validation->run() == FALSE) {
            // Validasi gagal, tampilkan pesan kesalahan


            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/edit_pass/' . $id_user);
        } else {
            $where = array('id' => $id_user);
            $cek = $this->m_auth->verify('users', $where);

            if ($cek->num_rows() > 0) {
                // Kita ambil isi dari record
                $hasil = $cek->row();

                if (password_verify($this->input->post('password'), $hasil->password)) {
                    // Update password baru
                    $update = array(
                        'password' => password_hash($this->input->post('passwordBaru', true), PASSWORD_DEFAULT),
                    );
                    $edit = $this->m_auth->do_edit($update, $id_user);

                    if ($edit) {
                        // Redirect dengan pesan sukses
                        $this->session->set_flashdata('success', 'Password berhasil diubah.');
                        redirect('auth/edit_pass/' . $id_user);
                    } else {
                        // Redirect dengan pesan gagal update
                        $this->session->set_flashdata('error', 'Gagal memperbarui password.');
                        redirect('auth/edit_pass/' . $id_user);
                    }
                } else {
                    // Password lama salah
                    $this->session->set_flashdata('error', 'Password lama salah.');
                    redirect('auth/edit_pass/' . $id_user);
                }
            } else {
                // Pengguna tidak ditemukan
                $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
                redirect('auth/edit_pass/' . $id_user);
            }
        }
    }

    public function strong_password($password)
    {

        if (
            preg_match('/[A-Z]/', $password) && // Huruf besar
            preg_match('/[a-z]/', $password) && // Huruf kecil
            preg_match('/[0-9]/', $password) && // Angka
            preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
        ) { // Simbol
            return TRUE;
        } else {
            $this->form_validation->set_message('strong_password', 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu simbol.');
            return FALSE;
        }
    }
}
