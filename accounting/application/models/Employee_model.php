<?php

class Employee_model extends CI_Model {

    public function get_all()
    {
        $this->db->where('IsDeleted', 0);

        return $this->db->get('employee')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('employee', $data);
    }
}