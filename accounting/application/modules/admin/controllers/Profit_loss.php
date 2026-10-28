<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profit_loss extends CI_Controller
{
    public function index()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        // Sales Income
        $salesQuery = $this->db->select_sum('TotalSaleAmount')->where('IsDeleted', 0);
        if ($fromDate) $salesQuery->where('SaleDate >=', $fromDate . ' 00:00:00');
        if ($toDate) $salesQuery->where('SaleDate <=', $toDate . ' 23:59:59');
        $sales = $salesQuery->get('sales')->row();

        $data['sales_income'] = $sales->TotalSaleAmount ? floatval($sales->TotalSaleAmount) : 0;

        // Other Income
        $incomeQuery = $this->db->select_sum('Amount');
        if ($fromDate) $incomeQuery->where('IncomeDate >=', $fromDate);
        if ($toDate) $incomeQuery->where('IncomeDate <=', $toDate);
        $income = $incomeQuery->get('incomes')->row();

        $data['other_income'] = $income->Amount ? floatval($income->Amount) : 0;

        // Total Income
        $data['income'] = $data['sales_income'] + $data['other_income'];

        // Expense Breakdown
        $this->db->select('
            chart_of_accounts.AccountID,
            chart_of_accounts.AccountName,
            SUM(expenses.Amount) AS Amount
        ');
        $this->db->from('expenses');
        $this->db->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID');
        $this->db->where('expenses.IsDeleted', 0);
        if ($fromDate) $this->db->where('expenses.ExpenseDate >=', $fromDate);
        if ($toDate) $this->db->where('expenses.ExpenseDate <=', $toDate);
        $this->db->group_by('expenses.AccountID');
        $data['expense_accounts'] = $this->db->get()->result();

        // Total Expenses
        $expenseQuery = $this->db->select_sum('Amount')->where('IsDeleted', 0);
        if ($fromDate) $expenseQuery->where('ExpenseDate >=', $fromDate);
        if ($toDate) $expenseQuery->where('ExpenseDate <=', $toDate);
        $expense = $expenseQuery->get('expenses')->row();

        $data['expense'] = $expense->Amount ? floatval($expense->Amount) : 0;
        
        $data['from_date'] = $fromDate;
        $data['to_date'] = $toDate;

        $this->load->view('pages/profit_loss/index', $data);
    }

    public function export_csv()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        // Calculate same figures
        $salesQuery = $this->db->select_sum('TotalSaleAmount')->where('IsDeleted', 0);
        if ($fromDate) $salesQuery->where('SaleDate >=', $fromDate . ' 00:00:00');
        if ($toDate) $salesQuery->where('SaleDate <=', $toDate . ' 23:59:59');
        $sales = $salesQuery->get('sales')->row()->TotalSaleAmount ?: 0;

        $incomeQuery = $this->db->select_sum('Amount');
        if ($fromDate) $incomeQuery->where('IncomeDate >=', $fromDate);
        if ($toDate) $incomeQuery->where('IncomeDate <=', $toDate);
        $income = $incomeQuery->get('incomes')->row()->Amount ?: 0;

        $totalIncome = $sales + $income;

        $this->db->select('chart_of_accounts.AccountName, SUM(expenses.Amount) AS Amount');
        $this->db->from('expenses');
        $this->db->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID');
        $this->db->where('expenses.IsDeleted', 0);
        if ($fromDate) $this->db->where('expenses.ExpenseDate >=', $fromDate);
        if ($toDate) $this->db->where('expenses.ExpenseDate <=', $toDate);
        $this->db->group_by('expenses.AccountID');
        $expense_accounts = $this->db->get()->result();

        $expenseQuery = $this->db->select_sum('Amount')->where('IsDeleted', 0);
        if ($fromDate) $expenseQuery->where('ExpenseDate >=', $fromDate);
        if ($toDate) $expenseQuery->where('ExpenseDate <=', $toDate);
        $totalExpenses = $expenseQuery->get('expenses')->row()->Amount ?: 0;

        $netProfit = $totalIncome - $totalExpenses;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Profit_Loss_Statement.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array('Profit & Loss Statement'));
        if ($fromDate || $toDate) {
            fputcsv($output, array('Period: ' . ($fromDate ?: 'Start') . ' to ' . ($toDate ?: 'End')));
        }
        fputcsv($output, array(''));

        fputcsv($output, array('INCOME'));
        fputcsv($output, array('Sales Income', number_format($sales, 2, '.', '')));
        fputcsv($output, array('Other Income', number_format($income, 2, '.', '')));
        fputcsv($output, array('TOTAL INCOME', number_format($totalIncome, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('EXPENSES'));
        foreach ($expense_accounts as $acc) {
            fputcsv($output, array($acc->AccountName, number_format($acc->Amount, 2, '.', '')));
        }
        fputcsv($output, array('TOTAL EXPENSES', number_format($totalExpenses, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('NET PROFIT / LOSS', number_format($netProfit, 2, '.', '')));

        fclose($output);
        exit;
    }
}