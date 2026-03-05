<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notfound extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }

    public function show_404()
    {
        $this->output->set_status_header('404');
        $this->load->view('not_found');
    }
    public function show_405()
    {
        $this->output->set_status_header('405');
        $this->load->view('405');
    }


    public function show_403()
    {
        $this->output->set_status_header('403');
        $this->load->view('405');
    }
}
