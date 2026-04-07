<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_auth extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    function verify($tabel, $where)
    {
        $this->db->where($where);
        $this->db->from($tabel);
        $query = $this->db->get();

        // $str = $this->db->last_query();
        // print_r($str);
        // exit();

        return $query;
    }

    function do_edit($data, $id)
    {
        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('users', $data);
        // $str = $this->db->last_query();
        // print_r($str);
        // exit();

        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }


    function user_by_email($table, $email)
    {


        $this->db->where('email', $email);
        $this->db->from($table);
        return $query = $this->db->get();
    }

    function update_password($id_user, $password)
    {

        $exe = $this->db->where('id_user', $id_user);
        $exe = $this->db->set('password', $password);
        $exe = $this->db->update('user');

        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }
}
