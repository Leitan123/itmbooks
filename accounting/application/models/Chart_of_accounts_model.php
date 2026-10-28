<?php
class Chart_of_accounts_model extends CI_Model
{
    private $table = 'chart_of_accounts';

    public function get_all()
    {
        $this->db->select('chart_of_accounts.*, account_types.AccountTypeName');
        $this->db->from('chart_of_accounts');
        $this->db->join('account_types', 'account_types.AccountTypeID = chart_of_accounts.AccountTypeID');
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_account_types()
    {
        return $this->db->get('account_types')->result();
    }
}