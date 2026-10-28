<?php

class Expenses_model extends MY_Model
{
    protected $_table = "expenses";
    protected $primary_key = "ExpenseID";

    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    public function prop_data_before_create($data)
    {
        return $data;
    }

    public function prop_data_before_update($data)
    {
        return $data;
    }

    public function get_all()
    {
        $this->db->select('
            expenses.*,
            chart_of_accounts.AccountCode,
            chart_of_accounts.AccountName
        ');

        $this->db->from('expenses');

        $this->db->join(
            'chart_of_accounts',
            'chart_of_accounts.AccountID = expenses.AccountID',
            'left'
        );


        $this->db->join(
            'banks',
            'banks.BankID = expenses.BankID',
            'left'
        );

        $this->db->join(
            'employee',
            'employee.EmployeeID = expenses.EmployeeID',
            'left'
        );

        $this->db->join(
            'customer',
            'customer.CustomerId = expenses.CustomerId',
            'left'
        );

        $this->db->select('
            expenses.*,
            chart_of_accounts.AccountName,
            customer.CompanyName as CustomerName,
            customer.DomainName,
            employee.EmployeeName,
            banks.BankName,
            banks.AccountNumber
            
        ');

        

        $this->db->order_by('expenses.ExpenseID', 'DESC');

        return $this->db->get()->result();
    }

    public function get_expense_accounts()
    {
        return $this->db
            ->where('AccountTypeID', 5)
            ->order_by('AccountName', 'ASC')
            ->get('chart_of_accounts')
            ->result();
    }
}