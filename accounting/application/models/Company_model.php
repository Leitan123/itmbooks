<?php

class Company_model extends CI_Model
{
    public function get_company()
    {
        return $this->db
            ->limit(1)
            ->get('company_settings')
            ->row();
    }
}