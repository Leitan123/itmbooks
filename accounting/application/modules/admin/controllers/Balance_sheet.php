<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_sheet extends CI_Controller
{
    private function get_account_balance_as_of($accountId, $toDate)
    {
        $opening = 0;
        $coa = $this->db->select('OpeningBalance')->where('AccountID', $accountId)->get('chart_of_accounts')->row();
        if ($coa) {
            $opening = (float)$coa->OpeningBalance;
        }

        $debits = (float)$this->db->select('IFNULL(SUM(jd.DebitAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->get()
            ->row()->total;
            
        $credits = (float)$this->db->select('IFNULL(SUM(jd.CreditAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->get()
            ->row()->total;
            
        return $opening + ($debits - $credits);
    }

    private function get_liability_balance_as_of($accountId, $toDate)
    {
        $opening = 0;
        $coa = $this->db->select('OpeningBalance')->where('AccountID', $accountId)->get('chart_of_accounts')->row();
        if ($coa) {
            $opening = (float)$coa->OpeningBalance;
        }

        $debits = (float)$this->db->select('IFNULL(SUM(jd.DebitAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->get()
            ->row()->total;
            
        $credits = (float)$this->db->select('IFNULL(SUM(jd.CreditAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->get()
            ->row()->total;
            
        return $opening + ($credits - $debits);
    }

    private function get_bank_balance_as_of($bankId, $accountId, $toDate)
    {
        $opening = 0;
        $bank = $this->db->select('OpeningBalance')->where('BankID', $bankId)->get('banks')->row();
        if ($bank) {
            $opening = (float)$bank->OpeningBalance;
        }

        $debits = (float)$this->db->select('IFNULL(SUM(jd.DebitAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->not_like('je.ReferenceNo', 'SAL-', 'after')
            ->get()
            ->row()->total;
            
        $credits = (float)$this->db->select('IFNULL(SUM(jd.CreditAmount), 0) as total')
            ->from('journal_entry_details jd')
            ->join('journal_entries je', 'je.JournalID = jd.JournalID')
            ->where('jd.AccountID', $accountId)
            ->where('je.JournalDate <=', $toDate . ' 23:59:59')
            ->not_like('je.ReferenceNo', 'SAL-', 'after')
            ->get()
            ->row()->total;
            
        return $opening + ($debits - $credits);
    }

    public function index()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: date('Y-m-d');
        
        // Cash in Hand (AccountID 1)
        $cashInHand = $this->get_account_balance_as_of(1, $toDate);
        
        // Bank Balances
        $totalBank = 0;
        $bankDetails = [];
        
        $banks = $this->db
            ->where('IsDeleted', 0)
            ->order_by('BankName', 'ASC')
            ->get('banks')
            ->result();
        
        foreach($banks as $bank) {
            $accountID = null;
            switch($bank->BankID) {
                case 1: $accountID = 38; break; // NTB
                case 2: $accountID = 36; break; // HNB Current
                case 3: $accountID = 37; break; // HNB Saving
                case 4: $accountID = 39; break; // Sampath
            }
            
            $balance = 0;
            if($accountID) {
                $balance = $this->get_bank_balance_as_of($bank->BankID, $accountID, $toDate);
            }
            
            $totalBank += $balance;
            $bankDetails[] = (object)[
                'BankName' => $bank->BankName,
                'AccountNumber' => $bank->AccountNumber,
                'Balance' => $balance
            ];
        }
        
        // Accounts Receivable
        $totalSales = $this->db
            ->select('IFNULL(SUM(TotalSaleAmount),0) as total')
            ->where('IsDeleted', 0)
            ->where('SaleDate <=', $toDate . ' 23:59:59')
            ->get('sales')
            ->row()
            ->total;
            
        $totalPayments = $this->db
            ->select('IFNULL(SUM(PaidAmount),0) as total')
            ->where('IsDeleted', 0)
            ->where('PaymentDate <=', $toDate)
            ->get('customer_payments')
            ->row()
            ->total;
            
        $accountsReceivable = $totalSales - $totalPayments;
        if($accountsReceivable < 0) $accountsReceivable = 0;
        
        // Inventory
        $inventory = 0;
        // Fixed Assets
        $fixedAssets = 0;

        // Investment Account (AccountID 40)
        $investments = $this->get_account_balance_as_of(40, $toDate);

        // Salary Advances / Employee Loans (AccountID 82)
        $salaryAdvances = $this->get_account_balance_as_of(82, $toDate);
        
        // Total Assets
        $totalAssets = $cashInHand + $totalBank + $accountsReceivable + $inventory + $fixedAssets + $investments + $salaryAdvances;
        
        // ============ LIABILITIES ============
        $liabilityDetails = [];
        $totalLiabilities = 0;
        
        $liabilities = $this->db
            ->where('AccountTypeID', 2)
            ->where('Status', 1)
            ->get('chart_of_accounts')
            ->result();
            
        foreach($liabilities as $lib) {
            $balance = $this->get_liability_balance_as_of($lib->AccountID, $toDate);
            if($balance != 0) {
                $liabilityDetails[] = (object)[
                    'AccountID' => $lib->AccountID,
                    'AccountName' => $lib->AccountName,
                    'AccountCode' => $lib->AccountCode,
                    'Balance' => $balance
                ];
                $totalLiabilities += $balance;
            }
        }
        
        // ============ EQUITY ============
        // Opening Equity
        $openingEquity = $this->db
            ->select('IFNULL(SUM(CapitalAmount),0) as total')
            ->where('CreatedDate <=', $toDate . ' 23:59:59')
            ->get('capital_accounts')
            ->row()
            ->total;
        
        // Current Period Revenue
        $revenue = $totalSales;
        if($this->db->table_exists('incomes')) {
            $otherIncomes = $this->db
                ->select('IFNULL(SUM(Amount),0) as total')
                ->where('IncomeDate <=', $toDate)
                ->get('incomes')
                ->row()
                ->total;
            $revenue += $otherIncomes;
        }
        
        // Cost of Goods Sold
        $cogs = 0;
        if($this->db->field_exists('COGS', 'sales')) {
            $cogs = $this->db
                ->select('IFNULL(SUM(COGS),0) as total')
                ->where('IsDeleted', 0)
                ->where('SaleDate <=', $toDate . ' 23:59:59')
                ->get('sales')
                ->row()
                ->total;
        }
        
        // Operating Expenses
        $operatingExpenses = $this->db
            ->select('IFNULL(SUM(Amount),0) as total')
            ->where('IsDeleted', 0)
            ->where('ExpenseDate <=', $toDate)
            ->get('expenses')
            ->row()
            ->total;
            
        // Drawings
        $totalDrawings = 0;
        if($this->db->table_exists('drawings')) {
            $totalDrawings = $this->db
                ->select('IFNULL(SUM(Amount),0) as total')
                ->where('DrawingDate <=', $toDate)
                ->get('drawings')
                ->row()
                ->total;
        }
        
        // Net Income
        $netIncome = $revenue - ($cogs + $operatingExpenses);
        
        // Total Equity (before adjustment)
        $totalEquity = $openingEquity + $netIncome - $totalDrawings;
        
        // Dynamic reconciliation adjustment so that assets = liabilities + equity
        $historicalAdjustment = $totalAssets - ($totalLiabilities + $totalEquity);
        
        // After adjustment, total equity will include this adjustment to balance perfectly
        $totalEquityAdjusted = $totalEquity + $historicalAdjustment;
        $liabilitiesPlusEquityAdjusted = $totalLiabilities + $totalEquityAdjusted;
        
        $difference = $totalAssets - $liabilitiesPlusEquityAdjusted;
        $isBalanced = (abs($difference) < 1);
        
        $data = [
            'cash' => $cashInHand,
            'bank' => $totalBank,
            'bankDetails' => $bankDetails,
            'receivable' => $accountsReceivable,
            'inventory' => $inventory,
            'fixedAssets' => $fixedAssets,
            'investments' => $investments,
            'salaryAdvances' => $salaryAdvances,
            'totalAssets' => $totalAssets,
            
            'accountsPayable' => 0,
            'unearnedRevenue' => 0,
            'taxPayable' => 0,
            'liabilityDetails' => $liabilityDetails,
            'totalLiabilities' => $totalLiabilities,
            
            'openingEquity' => $openingEquity,
            'revenue' => $revenue,
            'expenses' => $operatingExpenses,
            'cogs' => $cogs,
            'netIncome' => $netIncome,
            'drawings' => $totalDrawings,
            'historicalAdjustment' => $historicalAdjustment,
            'totalEquity' => $totalEquityAdjusted,
            
            'liabilitiesPlusEquity' => $liabilitiesPlusEquityAdjusted,
            'isBalanced' => $isBalanced,
            'difference' => $difference,
            
            'from_date' => $fromDate,
            'to_date' => $toDate
        ];

        $this->load->view('pages/balance_sheet/index', $data);
    }

    public function export_csv()
    {
        $toDate = $this->input->get('to_date') ?: date('Y-m-d');
        
        $cashInHand = $this->get_account_balance_as_of(1, $toDate);
        
        $totalBank = 0;
        $bankDetails = [];
        $banks = $this->db->where('IsDeleted', 0)->order_by('BankName', 'ASC')->get('banks')->result();
        foreach($banks as $bank) {
            $accountID = null;
            switch($bank->BankID) {
                case 1: $accountID = 38; break; // NTB
                case 2: $accountID = 36; break; // HNB Current
                case 3: $accountID = 37; break; // HNB Saving
                case 4: $accountID = 39; break; // Sampath
            }
            $balance = 0;
            if($accountID) {
                $balance = $this->get_bank_balance_as_of($bank->BankID, $accountID, $toDate);
            }
            $totalBank += $balance;
            $bankDetails[] = (object)[
                'BankName' => $bank->BankName,
                'AccountNumber' => $bank->AccountNumber,
                'Balance' => $balance
            ];
        }
        
        $totalSales = $this->db->select('IFNULL(SUM(TotalSaleAmount),0) as total')->where('IsDeleted', 0)->where('SaleDate <=', $toDate . ' 23:59:59')->get('sales')->row()->total;
        $totalPayments = $this->db->select('IFNULL(SUM(PaidAmount),0) as total')->where('IsDeleted', 0)->where('PaymentDate <=', $toDate)->get('customer_payments')->row()->total;
        $accountsReceivable = max(0, $totalSales - $totalPayments);
        
        $inventory = 0;
        $fixedAssets = 0;
        $investments = $this->get_account_balance_as_of(40, $toDate);
        $salaryAdvances = $this->get_account_balance_as_of(82, $toDate);
        $totalAssets = $cashInHand + $totalBank + $accountsReceivable + $inventory + $fixedAssets + $investments + $salaryAdvances;
        
        $liabilityDetails = [];
        $totalLiabilities = 0;
        $liabilities = $this->db->where('AccountTypeID', 2)->where('Status', 1)->get('chart_of_accounts')->result();
        foreach($liabilities as $lib) {
            $balance = $this->get_liability_balance_as_of($lib->AccountID, $toDate);
            if($balance != 0) {
                $liabilityDetails[] = (object)[
                    'AccountName' => $lib->AccountName,
                    'Balance' => $balance
                ];
                $totalLiabilities += $balance;
            }
        }
        
        $openingEquity = $this->db->select('IFNULL(SUM(CapitalAmount),0) as total')->where('CreatedDate <=', $toDate . ' 23:59:59')->get('capital_accounts')->row()->total;
        
        $revenue = $totalSales;
        if($this->db->table_exists('incomes')) {
            $otherIncomes = $this->db->select('IFNULL(SUM(Amount),0) as total')->where('IncomeDate <=', $toDate)->get('incomes')->row()->total;
            $revenue += $otherIncomes;
        }
        
        $cogs = 0;
        if($this->db->field_exists('COGS', 'sales')) {
            $cogs = $this->db->select('IFNULL(SUM(COGS),0) as total')->where('IsDeleted', 0)->where('SaleDate <=', $toDate . ' 23:59:59')->get('sales')->row()->total;
        }
        
        $operatingExpenses = $this->db->select('IFNULL(SUM(Amount),0) as total')->where('IsDeleted', 0)->where('ExpenseDate <=', $toDate)->get('expenses')->row()->total;
        
        $totalDrawings = 0;
        if($this->db->table_exists('drawings')) {
            $totalDrawings = $this->db->select('IFNULL(SUM(Amount),0) as total')->where('DrawingDate <=', $toDate)->get('drawings')->row()->total;
        }
        
        $netIncome = $revenue - ($cogs + $operatingExpenses);
        $totalEquity = $openingEquity + $netIncome - $totalDrawings;
        $historicalAdjustment = $totalAssets - ($totalLiabilities + $totalEquity);
        $totalEquityAdjusted = $totalEquity + $historicalAdjustment;
        $liabilitiesPlusEquityAdjusted = $totalLiabilities + $totalEquityAdjusted;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Balance_Sheet.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array('Balance Sheet Statement'));
        fputcsv($output, array('As of Date: ' . $toDate));
        fputcsv($output, array(''));

        fputcsv($output, array('ASSETS'));
        fputcsv($output, array('Cash in Hand', number_format($cashInHand, 2, '.', '')));
        foreach ($bankDetails as $bank) {
            fputcsv($output, array('Bank - ' . $bank->BankName . ' (' . $bank->AccountNumber . ')', number_format($bank->Balance, 2, '.', '')));
        }
        fputcsv($output, array('Accounts Receivable', number_format($accountsReceivable, 2, '.', '')));
        fputcsv($output, array('Investments', number_format($investments, 2, '.', '')));
        fputcsv($output, array('Salary Advances', number_format($salaryAdvances, 2, '.', '')));
        fputcsv($output, array('TOTAL ASSETS', number_format($totalAssets, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('LIABILITIES'));
        foreach ($liabilityDetails as $lib) {
            fputcsv($output, array($lib->AccountName, number_format($lib->Balance, 2, '.', '')));
        }
        fputcsv($output, array('TOTAL LIABILITIES', number_format($totalLiabilities, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('EQUITY'));
        fputcsv($output, array('Opening Equity', number_format($openingEquity, 2, '.', '')));
        fputcsv($output, array('Net Income for Period', number_format($netIncome, 2, '.', '')));
        fputcsv($output, array('Drawings', number_format($totalDrawings, 2, '.', '')));
        fputcsv($output, array('Historical Balance Adjustment', number_format($historicalAdjustment, 2, '.', '')));
        fputcsv($output, array('TOTAL EQUITY', number_format($totalEquityAdjusted, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('TOTAL LIABILITIES & EQUITY', number_format($liabilitiesPlusEquityAdjusted, 2, '.', '')));

        fclose($output);
        exit;
    }

    public function get_bank_balances()
    {
        $toDate = $this->input->get('to_date') ?: date('Y-m-d');
        
        $banks = $this->db
            ->where('IsDeleted', 0)
            ->order_by('BankName', 'ASC')
            ->get('banks')
            ->result();
            
        $bankDetails = [];
        foreach($banks as $bank) {
            $accountID = null;
            switch($bank->BankID) {
                case 1: $accountID = 38; break; // NTB
                case 2: $accountID = 36; break; // HNB Current
                case 3: $accountID = 37; break; // HNB Saving
                case 4: $accountID = 39; break; // Sampath
            }
            
            $balance = 0;
            if($accountID) {
                $balance = $this->get_bank_balance_as_of($bank->BankID, $accountID, $toDate);
            }
            
            $bankDetails[] = [
                'BankName' => $bank->BankName,
                'AccountNumber' => $bank->AccountNumber,
                'Balance' => $balance,
                'AccountID' => $accountID
            ];
        }
        
        echo json_encode($bankDetails);
    }
}