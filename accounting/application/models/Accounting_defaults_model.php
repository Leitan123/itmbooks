<?php

class Accounting_defaults_model extends CI_Model
{
    public function get_record()
    {
        return $this->db
            ->limit(1)
            ->get('accounting_defaults')
            ->row();
    }

    public function get_accounts()
    {
        return $this->db
            ->order_by('AccountCode')
            ->get('chart_of_accounts')
            ->result();
    }
}