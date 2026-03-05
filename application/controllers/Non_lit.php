<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Non_lit extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_aset');
        $this->load->model('m_home');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->output->set_header('X-Frame-Options: SAMEORIGIN');
    }
    public function index()
    {
        echo "Haloo";
        exit();
    }
}
