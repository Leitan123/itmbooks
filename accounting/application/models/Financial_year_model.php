<?php

class Financial_year_model extends CI_Model
{
    protected $table = 'financial_year';

    public function get_all()
    {
        return $this->db
            ->where('IsDeleted', 0)
            ->order_by('YearID', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_all_fields()
    {
        return $this->db->list_fields($this->table);
    }
}