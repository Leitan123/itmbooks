<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        // Models
        $this->load->model('Reload_model');
        $this->load->model('Customer_payments_model');
        $this->load->model('Vendor_payments_model');
    }

    public function index()
    {

        // =====================================
        // RELOAD RECORDS
        // =====================================

        $data['reloadRecords'] =
            $this->Reload_model
            ->get_reload_data();


        // =====================================
        // CUSTOMER DATA
        // =====================================

        $customers =
            $this->Customer_payments_model
            ->get_all_customers_with_due();


        // =====================================
        // VENDOR DATA
        // =====================================

        $vendors =
            $this->Vendor_payments_model
            ->get_all_vendors_with_due();


        // =====================================
        // INITIALIZE TOTALS
        // =====================================

        $totalSales = 0;

        $totalPaidAmount = 0;

        $totalDueAmount = 0;

        $totalExpenses = 0;


        // =====================================
        // CUSTOMER TOTALS
        // =====================================

        foreach ($customers as $customer)
        {

            $totalSales +=
                $customer->TotalSaleAmount;

            $totalPaidAmount +=
                $customer->TotalPaidAmount;

            $totalDueAmount +=
                $customer->PaymentDue;
        }


        // =====================================
        // VENDOR TOTALS
        // =====================================

        foreach ($vendors as $vendor)
        {

            $totalExpenses +=
                $vendor->TotalPaidAmount;
        }


        // =====================================
        // NET PROFIT
        // =====================================

        $netProfit =
            $totalSales - $totalExpenses;


        // =====================================
        // CASH FLOW
        // =====================================

        $cashFlow =
            $totalPaidAmount - $totalExpenses;


        // =====================================
        // TOTAL CUSTOMERS
        // =====================================

        $data['total_customers'] =
            count($customers);


        // =====================================
        // TOTAL VENDORS
        // =====================================

        $data['total_vendors'] =
            count($vendors);


        // =====================================
        // MONTHLY SALES
        // =====================================

        $monthlySales = [];

        for($m = 1; $m <= 12; $m++)
        {

            $this->db->select_sum(
                'TotalSaleAmount'
            );

            $this->db->where(
                'MONTH(SaleDate)',
                $m
            );

            $sale =
                $this->db->get('sales')->row();

            $monthlySales[] =
                $sale->TotalSaleAmount
                ? $sale->TotalSaleAmount
                : 0;
        }


        // =====================================
        // MONTHLY EXPENSES
        // =====================================

        $monthlyExpenses = [];

        for($m = 1; $m <= 12; $m++)
        {

            $this->db->select_sum('Amount');

            $this->db->where(
                'MONTH(ExpenseDate)',
                $m
            );

            $expense =
                $this->db->get('expenses')->row();

            $monthlyExpenses[] =
                $expense->Amount
                ? $expense->Amount
                : 0;
        }


        // =====================================
        // MONTHLY PROFIT
        // =====================================

        $monthlyProfit = [];

        for($m = 1; $m <= 12; $m++)
        {

            $profit =
                $monthlySales[$m - 1]
                -
                $monthlyExpenses[$m - 1];

            $monthlyProfit[] =
                $profit;
        }


        // =====================================
        // TOP 5 CUSTOMERS
        // =====================================

        $this->db->select(
            'CustomerName,
             SUM(TotalSaleAmount)
             AS TotalSales'
        );

        $this->db->from('sales');

        $this->db->group_by('CustomerName');

        $this->db->order_by(
            'TotalSales',
            'DESC'
        );

        $this->db->limit(5);

        $data['topCustomers'] =
            $this->db->get()->result();


        // =====================================
        // TOP 5 PRODUCTS
        // =====================================

        $this->db->select(
            'ItemName,
             SUM(Quantity)
             AS TotalQty'
        );

        $this->db->from('sale_items');

        $this->db->group_by('ItemName');

        $this->db->order_by(
            'TotalQty',
            'DESC'
        );

        $this->db->limit(5);

        $data['topProducts'] =
            $this->db->get()->result();


        // =====================================
        // PERIOD & TARGET METRICS CALCULATIONS
        // =====================================
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth   = date('Y-m-t 23:59:59');
        $startOfYear  = date('Y-01-01 00:00:00');
        $endOfYear    = date('Y-12-31 23:59:59');
        
        $startOfPrevMonth = date('Y-m-01 00:00:00', strtotime('-1 month'));
        $endOfPrevMonth   = date('Y-m-t 23:59:59', strtotime('-1 month'));

        // Period Sales
        $salesMonth = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')->where('IsDeleted', 0)->where('SaleDate >=', $startOfMonth)->where('SaleDate <=', $endOfMonth)->get('sales')->row()->total;
        $salesYear  = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')->where('IsDeleted', 0)->where('SaleDate >=', $startOfYear)->where('SaleDate <=', $endOfYear)->get('sales')->row()->total;
        $salesPrevMonth = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')->where('IsDeleted', 0)->where('SaleDate >=', $startOfPrevMonth)->where('SaleDate <=', $endOfPrevMonth)->get('sales')->row()->total;

        // Find Cash Account ID dynamically
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

        // Period Received (Debits to Cash Account)
        $receivedMonth = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01'))
            ->where('je.JournalDate <=', date('Y-m-t'))
            ->get()
            ->row()
            ->total;

        $receivedYear  = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-01-01'))
            ->where('je.JournalDate <=', date('Y-12-31'))
            ->get()
            ->row()
            ->total;

        $receivedPrevMonth = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01', strtotime('-1 month')))
            ->where('je.JournalDate <=', date('Y-m-t', strtotime('-1 month')))
            ->get()
            ->row()
            ->total;

        // Period Expenses (Credits to Cash Account)
        $expensesMonth = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01'))
            ->where('je.JournalDate <=', date('Y-m-t'))
            ->get()
            ->row()
            ->total;

        $expensesYear  = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-01-01'))
            ->where('je.JournalDate <=', date('Y-12-31'))
            ->get()
            ->row()
            ->total;

        $expensesPrevMonth = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01', strtotime('-1 month')))
            ->where('je.JournalDate <=', date('Y-m-t', strtotime('-1 month')))
            ->get()
            ->row()
            ->total;

        // Period Net Profit
        $netProfitMonth = $salesMonth - $expensesMonth;
        $netProfitYear  = $salesYear - $expensesYear;
        $netProfitPrevMonth = $salesPrevMonth - $expensesPrevMonth;

        // Period Cash Flow
        $cashFlowMonth = $receivedMonth - $expensesMonth;
        $cashFlowYear  = $receivedYear - $expensesYear;
        $cashFlowPrevMonth = $receivedPrevMonth - $expensesPrevMonth;

        // =====================================
        // TODAY'S SALES (TOTAL, CASH, CREDIT)
        // =====================================
        $todayStr = date('Y-m-d');
        $todaySalesRow = $this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')
            ->where('IsDeleted', 0)
            ->where('DATE(SaleDate)', $todayStr)
            ->get('sales')
            ->row();
        $todaySalesTotal = $todaySalesRow->total;

        $todayCashRow = $this->db->select('IFNULL(SUM(customer_payments.PaidAmount), 0) as cash')
            ->from('sales')
            ->join('customer_payments', 'customer_payments.SaleID = sales.SaleID AND customer_payments.IsDeleted = 0')
            ->where('sales.IsDeleted', 0)
            ->where('DATE(sales.SaleDate)', $todayStr)
            ->get()
            ->row();
        $todayCashTotal = $todayCashRow->cash;
        $todayCreditTotal = $todaySalesTotal - $todayCashTotal;

        // =====================================
        // FINAL DATA
        // =====================================

        $data['totalSales'] = $totalSales;
        $data['totalPaidAmount'] = $totalPaidAmount;
        $data['totalDueAmount'] = $totalDueAmount;
        $data['totalExpenses'] = $totalExpenses;
        $data['netProfit'] = $netProfit;
        $data['cashFlow'] = $cashFlow;
        
        $data['todaySalesTotal'] = $todaySalesTotal;
        $data['todayCashTotal'] = $todayCashTotal;
        $data['todayCreditTotal'] = $todayCreditTotal;

        $data['salesMonth'] = $salesMonth;
        $data['salesYear']  = $salesYear;
        $data['salesPrevMonth'] = $salesPrevMonth;

        $data['receivedMonth'] = $receivedMonth;
        $data['receivedYear']  = $receivedYear;
        $data['receivedPrevMonth'] = $receivedPrevMonth;

        $data['expensesMonth'] = $expensesMonth;
        $data['expensesYear']  = $expensesYear;
        $data['expensesPrevMonth'] = $expensesPrevMonth;

        $data['netProfitMonth'] = $netProfitMonth;
        $data['netProfitYear']  = $netProfitYear;
        $data['netProfitPrevMonth'] = $netProfitPrevMonth;

        $data['cashFlowMonth'] = $cashFlowMonth;
        $data['cashFlowYear']  = $cashFlowYear;
        $data['cashFlowPrevMonth'] = $cashFlowPrevMonth;

        $data['monthlySales'] = json_encode($monthlySales);
        $data['monthlyExpenses'] = json_encode($monthlyExpenses);
        $data['monthlyProfit'] = json_encode($monthlyProfit);


        // =====================================
        // PENDING QUOTATIONS WITH DAYS
        // =====================================
        $pendingQuotationsRaw = $this->db
            ->select('
                quotation.QuotationID,
                quotation.QuotationNo,
                quotation.QuotationDate,
                quotation.GrandTotal,
                quotation.Status,
                customer.CompanyName,
                customer.CustomerName,
                customer.DomainName,
                GROUP_CONCAT(items.ItemName SEPARATOR ", ") AS ItemName
            ')
            ->from('quotation')
            ->join('customer', 'customer.CustomerId = quotation.CustomerID', 'left')
            ->join('quotation_items', 'quotation_items.QuotationID = quotation.QuotationID', 'left')
            ->join('items', 'items.ItemID = quotation_items.ItemID', 'left')
            ->where('quotation.IsDeleted', 0)
            ->where('quotation.Status', 'Pending')
            ->group_by('quotation.QuotationID')
            ->order_by('quotation.QuotationDate', 'ASC')
            ->get()
            ->result();

        $pendingQuotationsList = array();
        $totalPendingQuotationsCount = 0;
        $totalPendingQuotationsAmount = 0;

        foreach($pendingQuotationsRaw as $qRow) {
            $qDate = new DateTime(date('Y-m-d', strtotime($qRow->QuotationDate)));
            $today = new DateTime(date('Y-m-d'));
            $diff = $qDate->diff($today);
            $pendingDays = $diff->invert ? 0 : $diff->days;

            $totalPendingQuotationsCount++;
            $totalPendingQuotationsAmount += floatval($qRow->GrandTotal);

            $pendingQuotationsList[] = (object)[
                'QuotationID' => $qRow->QuotationID,
                'QuotationNo' => $qRow->QuotationNo,
                'QuotationDate' => $qRow->QuotationDate,
                'CompanyName' => $qRow->CompanyName ?: $qRow->CustomerName,
                'DomainName' => $qRow->DomainName,
                'ItemName' => $qRow->ItemName,
                'GrandTotal' => $qRow->GrandTotal,
                'Status' => $qRow->Status,
                'PendingDays' => $pendingDays
            ];
        }

        usort($pendingQuotationsList, function($a, $b) {
            return $b->PendingDays - $a->PendingDays;
        });

        $data['pendingQuotationsList'] = $pendingQuotationsList;
        $data['totalPendingQuotationsCount'] = $totalPendingQuotationsCount;
        $data['totalPendingQuotationsAmount'] = $totalPendingQuotationsAmount;

        // =====================================
        // LOAD DASHBOARD VIEW
        // =====================================

        $this->load->view(
            'admin/dashboard',
            $data
        );
    }
}