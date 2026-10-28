<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_flow extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function get_cash_account_id()
    {
        $cashAccountId = 1;
        $defaultRecord = $this->db->limit(1)->get('accounting_defaults')->row();
        if ($defaultRecord && !empty($defaultRecord->CashAccountID)) {
            $cashAccountId = $defaultRecord->CashAccountID;
        } else {
            $cashAccount = $this->db->select('AccountID')
                ->where('AccountCode', '1001')
                ->or_where('AccountName', 'Cash Account')
                ->get('chart_of_accounts')
                ->row();
            if ($cashAccount) {
                $cashAccountId = $cashAccount->AccountID;
            }
        }
        return $cashAccountId;
    }

    public function index()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        $cashAccountId = $this->get_cash_account_id();

        // Query all Cash Account ledger entries
        $this->db->select("
            jed.*,
            je.JournalDate as Date,
            je.ReferenceNo,
            je.Description,
            (SELECT GROUP_CONCAT(CONCAT(c.AccountCode, ' - ', c.AccountName) SEPARATOR ', ')
             FROM journal_entry_details j2
             JOIN chart_of_accounts c ON c.AccountID = j2.AccountID
             WHERE j2.JournalID = je.JournalID AND j2.AccountID != jed.AccountID) as OpposingAccount
        ")
        ->from('journal_entry_details jed')
        ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
        ->where('jed.AccountID', $cashAccountId);

        if ($fromDate) $this->db->where('je.JournalDate >=', $fromDate);
        if ($toDate) $this->db->where('je.JournalDate <=', $toDate);

        $cashTransactions = $this->db->order_by('je.JournalDate', 'DESC')
            ->order_by('je.JournalID', 'DESC')
            ->get()
            ->result();

        $customerPaymentAmount = 0;
        $customerPaymentDetails = [];
        $incomeAmount = 0;
        $incomeDetails = [];
        $vendorPaymentAmount = 0;
        $vendorPaymentDetails = [];
        $expenseAmount = 0;
        $expenseDetails = [];
        $otherInflowAmount = 0;
        $otherInflowDetails = [];
        $otherOutflowAmount = 0;
        $otherOutflowDetails = [];

        foreach ($cashTransactions as $tx) {
            $txDate = $tx->Date;
            $txAmount = $tx->DebitAmount > 0 ? (float)$tx->DebitAmount : (float)$tx->CreditAmount;
            $txName = $tx->OpposingAccount ?: 'General Ledger Entry';
            $ref = $tx->ReferenceNo;

            if (strpos($ref, 'CP-') === 0) {
                $customerPaymentAmount += $txAmount;
                $customerPaymentDetails[] = (object)[
                    'Date' => $txDate,
                    'Name' => $txName,
                    'Amount' => $txAmount,
                    'PaymentMethod' => 'Cash',
                    'PaymentID' => substr($ref, 3)
                ];
            } elseif (strpos($ref, 'INC-') === 0) {
                $incomeAmount += $txAmount;
                $incomeDetails[] = (object)[
                    'Date' => $txDate,
                    'Name' => $txName,
                    'Amount' => $txAmount,
                    'PaymentMethod' => 'Cash',
                    'IncomeID' => substr($ref, 4)
                ];
            } elseif (strpos($ref, 'VP-') === 0) {
                $vendorPaymentAmount += $txAmount;
                $vendorPaymentDetails[] = (object)[
                    'Date' => $txDate,
                    'Name' => $txName,
                    'Amount' => $txAmount,
                    'PaymentMethod' => 'Cash',
                    'PaymentID' => substr($ref, 3)
                ];
            } elseif (strpos($ref, 'EXP-') === 0) {
                $expenseAmount += $txAmount;
                $expenseDetails[] = (object)[
                    'Date' => $txDate,
                    'Name' => $txName,
                    'Amount' => $txAmount,
                    'PaymentMethod' => 'Cash',
                    'ExpenseID' => substr($ref, 4)
                ];
            } else {
                // Manual journal entries or other transactions
                if ($tx->DebitAmount > 0) {
                    $otherInflowAmount += $txAmount;
                    $otherInflowDetails[] = (object)[
                        'Date' => $txDate,
                        'Name' => $txName,
                        'Amount' => $txAmount,
                        'PaymentMethod' => 'Cash',
                        'ReferenceNo' => $ref,
                        'Description' => $tx->Description
                    ];
                } else {
                    $otherOutflowAmount += $txAmount;
                    $otherOutflowDetails[] = (object)[
                        'Date' => $txDate,
                        'Name' => $txName,
                        'Amount' => $txAmount,
                        'PaymentMethod' => 'Cash',
                        'ReferenceNo' => $ref,
                        'Description' => $tx->Description
                    ];
                }
            }
        }

        // TOTALS
        $totalInflow = $customerPaymentAmount + $incomeAmount + $otherInflowAmount;
        $totalOutflow = $vendorPaymentAmount + $expenseAmount + $otherOutflowAmount;
        $netCashFlow = $totalInflow - $totalOutflow;

        $data = [
            'customerPayments' => $customerPaymentAmount,
            'income' => $incomeAmount,
            'otherInflow' => $otherInflowAmount,
            'vendorPayments' => $vendorPaymentAmount,
            'expenses' => $expenseAmount,
            'otherOutflow' => $otherOutflowAmount,
            'totalInflow' => $totalInflow,
            'totalOutflow' => $totalOutflow,
            'netCashFlow' => $netCashFlow,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'customerPaymentDetails' => $customerPaymentDetails,
            'incomeDetails' => $incomeDetails,
            'otherInflowDetails' => $otherInflowDetails,
            'vendorPaymentDetails' => $vendorPaymentDetails,
            'expenseDetails' => $expenseDetails,
            'otherOutflowDetails' => $otherOutflowDetails
        ];

        $this->load->view('pages/cash_flow/index', $data);
    }

    public function export_csv()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        $cashAccountId = $this->get_cash_account_id();

        // Query all Cash Account ledger entries
        $this->db->select("
            jed.*,
            je.JournalDate as Date,
            je.ReferenceNo,
            je.Description,
            (SELECT GROUP_CONCAT(CONCAT(c.AccountCode, ' - ', c.AccountName) SEPARATOR ', ')
             FROM journal_entry_details j2
             JOIN chart_of_accounts c ON c.AccountID = j2.AccountID
             WHERE j2.JournalID = je.JournalID AND j2.AccountID != jed.AccountID) as OpposingAccount
        ")
        ->from('journal_entry_details jed')
        ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
        ->where('jed.AccountID', $cashAccountId);

        if ($fromDate) $this->db->where('je.JournalDate >=', $fromDate);
        if ($toDate) $this->db->where('je.JournalDate <=', $toDate);

        $cashTransactions = $this->db->order_by('je.JournalDate', 'DESC')
            ->order_by('je.JournalID', 'DESC')
            ->get()
            ->result();

        $customerPaymentAmount = 0;
        $incomeAmount = 0;
        $vendorPaymentAmount = 0;
        $expenseAmount = 0;
        $otherInflowAmount = 0;
        $otherOutflowAmount = 0;

        foreach ($cashTransactions as $tx) {
            $txAmount = $tx->DebitAmount > 0 ? (float)$tx->DebitAmount : (float)$tx->CreditAmount;
            $ref = $tx->ReferenceNo;

            if (strpos($ref, 'CP-') === 0) {
                $customerPaymentAmount += $txAmount;
            } elseif (strpos($ref, 'INC-') === 0) {
                $incomeAmount += $txAmount;
            } elseif (strpos($ref, 'VP-') === 0) {
                $vendorPaymentAmount += $txAmount;
            } elseif (strpos($ref, 'EXP-') === 0) {
                $expenseAmount += $txAmount;
            } else {
                if ($tx->DebitAmount > 0) {
                    $otherInflowAmount += $txAmount;
                } else {
                    $otherOutflowAmount += $txAmount;
                }
            }
        }

        // TOTALS
        $totalInflow = $customerPaymentAmount + $incomeAmount + $otherInflowAmount;
        $totalOutflow = $vendorPaymentAmount + $expenseAmount + $otherOutflowAmount;
        $netCashFlow = $totalInflow - $totalOutflow;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Cash_Flow_Statement.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array('Cash Flow Statement (Cash Only)'));
        if ($fromDate || $toDate) {
            fputcsv($output, array('Period: ' . ($fromDate ?: 'Start') . ' to ' . ($toDate ?: 'End')));
        }
        fputcsv($output, array(''));

        fputcsv($output, array('CASH INFLOW'));
        fputcsv($output, array('Customer Payments received', number_format($customerPaymentAmount, 2, '.', '')));
        fputcsv($output, array('Other Incomes', number_format($incomeAmount, 2, '.', '')));
        fputcsv($output, array('Other Inflows (Journals)', number_format($otherInflowAmount, 2, '.', '')));
        fputcsv($output, array('TOTAL INFLOW', number_format($totalInflow, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('CASH OUTFLOW'));
        fputcsv($output, array('Vendor Payments made', number_format($vendorPaymentAmount, 2, '.', '')));
        fputcsv($output, array('Operating Expenses paid', number_format($expenseAmount, 2, '.', '')));
        fputcsv($output, array('Other Outflows (Journals)', number_format($otherOutflowAmount, 2, '.', '')));
        fputcsv($output, array('TOTAL OUTFLOW', number_format($totalOutflow, 2, '.', '')));
        fputcsv($output, array(''));

        fputcsv($output, array('NET CASH FLOW', number_format($netCashFlow, 2, '.', '')));

        fclose($output);
        exit;
    }
}