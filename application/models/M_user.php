<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function make_query()
    {

        $table = "users";
        $select_column = "
         id, username,password,role";
        $this->db->select($select_column);
        $this->db->from($table);
        $i = 0;

        $column_search = array('username', 'role');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
    }

    function make_datatables()
    {

        $this->make_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        // $a = $this->db->last_query($query);
        // print_r($a);
        // exit();
        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $i = 0;

        $column_search = array('username', 'role');
        foreach ($column_search as $item) // loop column 
        {
            if (@$_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_by('id');
                    // $this->db->group_by('m_aset_baru.id_aset');

                    $this->db->order_by('id', 'asc');
                    $this->db->like($item, $_POST['search']['value']);
                } else {

                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) //last loop 
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $query = $this->db->get();



        return $query->num_rows();
    }


    function update($data, $id)
    {

        $exe = $this->db->where('id', $id);
        $exe = $this->db->update('users', $data);
        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }

    function get_all_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $this->db->count_all_results();
    }

    function insertdata($data)
    {
        $exe = $this->db->insert('users', $data);
        $id = $this->db->insert_id();

        if ($exe) {
            return '1';
        } else {
            return '0';
        }
    }
}
