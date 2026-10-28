<?php

class Opening_balance_model extends CI_Model
{
    public function get_record()
    {
        return $this->db
            ->limit(1)
            ->get('opening_balances')
            ->row();
    }
}