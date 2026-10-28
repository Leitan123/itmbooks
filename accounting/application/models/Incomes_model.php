<?php

class Incomes_model extends CI_Model
{
    private $table = 'incomes';

    public function get_all()
    {
        $this->db->select('
            incomes.*,
            chart_of_accounts.AccountName,
            banks.BankName
        ');

        $this->db->from('incomes');

        $this->db->join(
            'chart_of_accounts',
            'chart_of_accounts.AccountID = incomes.AccountID',
            'left'
        );

        $this->db->join(
            'banks',
            'banks.BankID = incomes.BankID',
            'left'
        );

        $this->db->order_by(
            'IncomeID',
            'DESC'
        );

        return $this->db->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_income_accounts()
    {
        $this->db->select('*');
        $this->db->from('chart_of_accounts');

        $this->db->where('AccountTypeID', 4);

        return $this->db->get()->result();
    }
}