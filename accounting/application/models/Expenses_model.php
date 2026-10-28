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
    $this->db->select("
        expenses.*,
        chart_of_accounts.AccountCode,
        chart_of_accounts.AccountName,  
        employee.EmployeeName,
        banks.BankName,
        banks.AccountNumber,
        (
            SELECT GROUP_CONCAT(
                CONCAT(
                    c.CompanyName,
                    '<br><small>',
                    CONCAT_WS(', ', c.DomainName, (SELECT GROUP_CONCAT(cd.DomainName SEPARATOR ', ') FROM customer_domains cd WHERE cd.CustomerID = c.CustomerId)),
                    '</small>'
                )
                SEPARATOR '<br>'
            )
            FROM expense_customers ec
            INNER JOIN customer c
                ON c.CustomerId = ec.CustomerId
            WHERE ec.ExpenseID = expenses.ExpenseID
        ) AS Domains
    ");

    $this->db->from('expenses');
    $this->db->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID', 'left');
    $this->db->join('banks', 'banks.BankID = expenses.BankID', 'left');
    $this->db->join('employee', 'employee.EmployeeID = expenses.EmployeeID', 'left');
    $this->db->where('expenses.IsDeleted', 0);
    
    // Sort by ExpenseDate DESC (newest first), then by ExpenseID DESC
    $this->db->order_by('expenses.ExpenseDate', 'DESC');
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

    // ADD THIS METHOD
    public function get_total_amount()
    {
        return $this->db
            ->select('IFNULL(SUM(Amount),0) as total')
            ->where('IsDeleted', 0)
            ->get('expenses')
            ->row()
            ->total;
    }


    public function get_all_by_date()
    {
        $this->db->select("
            expenses.*,

            chart_of_accounts.AccountCode,
            chart_of_accounts.AccountName,  

            employee.EmployeeName,

            banks.BankName,
            banks.AccountNumber,

            (
                SELECT GROUP_CONCAT(
                    CONCAT(
                        c.CompanyName,
                        '<br><small>',
                        CONCAT_WS(', ', c.DomainName, (SELECT GROUP_CONCAT(cd.DomainName SEPARATOR ', ') FROM customer_domains cd WHERE cd.CustomerID = c.CustomerId)),
                        '</small>'
                    )
                    SEPARATOR '<br>'
                )
                FROM expense_customers ec
                INNER JOIN customer c
                    ON c.CustomerId = ec.CustomerId
                WHERE ec.ExpenseID = expenses.ExpenseID
            ) AS Domains

        ");

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

        $this->db->order_by('expenses.ExpenseDate', 'DESC');
        $this->db->order_by('expenses.ExpenseID', 'DESC');

        return $this->db->get()->result();
    }
}
?>