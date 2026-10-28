<?php

class Bank_balance extends CI_Controller
{
    public function index()
    {
        $banks = $this->db
            ->where('IsDeleted',0)
            ->get('banks')
            ->result();

        foreach($banks as $bank)
        {
            // Income

            $income = $this->db
                ->select_sum('PaidAmount')
                ->where('BankName',$bank->BankName)
                ->get('customer_payments')
                ->row();

            $totalIncome =
                $income->PaidAmount ?: 0;

            // Expense

            $expense = $this->db
                ->select_sum('Amount')
                ->where('BankID',$bank->BankID)
                ->where('PaymentMethod','Bank')
                ->get('expenses')
                ->row();

            $totalExpense =
                $expense->Amount ?: 0;

            $bank->CurrentBalance =
                $bank->OpeningBalance
                +
                $totalIncome
                -
                $totalExpense;
        }

        $data['banks'] = $banks;

        $this->load->view(
            'pages/bank_balance/index',
            $data
        );
    }
}