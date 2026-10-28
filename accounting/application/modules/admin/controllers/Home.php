<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model', 'user');
        $this->load->model('Customer_payments_model');
        $this->load->model('Vendor_payments_model');
        $this->load->model('Customer_model');
        $this->load->model('Sales_model');
        $this->load->database();
    }

    public function index()
    {
        if (!$this->session->has_userdata('user'))
        {
            $data['error'] = "";
            $this->form_validation->set_rules('username', '', 'required');
            $this->form_validation->set_rules('password', '', 'required');

            if ($this->form_validation->run())
            {
                $user = $this->user->get_by([
                    'Username' => $this->input->post('username'),
                    'Password' => sha1($this->input->post('password'))
                ]);

                if ($user)
                {
                    $time = time();
                    $login_token = sha1($user->UserId . $user->Password . $time);
                    $this->session->set_userdata("user", $user);
                    
                    $array = [
                        'login' => true,
                        'login_token' => $login_token,
                        'logged' => ['id' => $user->UserId, 'time' => $time]
                    ];
                    
                    $this->session->set_userdata($array);

                    if ($this->session->userdata('url'))
                    {
                        redirect($this->session->userdata('url'));
                    }
                    
                    redirect(base_url('admin'));
                }
                else
                {
                    $data['error'] = "Invalid Username or Password";
                }
            }

            $this->load->view('login', $data);
            return;
        }

        // =====================================
        // DASHBOARD DATA
        // =====================================
        
        $customers = $this->Customer_payments_model->get_all_customers_with_due();
        $vendors = $this->Vendor_payments_model->get_all_vendors_with_due();

        $totalSales = 0;
        $totalPaidAmount = 0;
        $totalDueAmount = 0;

        foreach ($customers as $customer)
        {
            $totalSales += $customer->TotalSaleAmount;
            $totalPaidAmount += $customer->TotalPaidAmount;
            $totalDueAmount += $customer->PaymentDue;
        }

        // =====================================
        // PENDING PAYMENT (CUSTOMER DUE)
        // =====================================
        $pendingPayment = $totalDueAmount;

        // =====================================
        // CUSTOMER PENDING PAYMENTS WITH DAYS OVERDUE
        // =====================================
        
        $customerPendingPayments = $this->db
            ->select('
                customer.CustomerId,
                customer.CompanyName,
                customer.CustomerName,
                customer.MobileNo,
                sales.SaleID,
                sales.SaleDate,
                sales.TotalSaleAmount,
                COALESCE(SUM(customer_payments.PaidAmount), 0) as PaidAmount,
                (sales.TotalSaleAmount - COALESCE(SUM(customer_payments.PaidAmount), 0)) as DueAmount
            ')
            ->from('sales')
            ->join('customer', 'customer.CustomerId = sales.CustomerId', 'left')
            ->join('customer_payments', 'customer_payments.SaleID = sales.SaleID AND customer_payments.IsDeleted = 0', 'left')
            ->where('sales.IsDeleted', 0)
            ->group_by('sales.SaleID')
            ->having('DueAmount > 0')
            ->order_by('DueAmount', 'DESC')
            ->get()
            ->result();

        $pendingPaymentsList = array();
        foreach($customerPendingPayments as $row) {
            $dueDate = date('Y-m-d', strtotime($row->SaleDate . ' + 30 days'));
            $today = date('Y-m-d');
            $daysOverdue = 0;
            
            $dueDateTime = new DateTime($dueDate);
            $todayDateTime = new DateTime($today);
            if ($dueDateTime < $todayDateTime) {
                $daysOverdue = $todayDateTime->diff($dueDateTime)->days;
            }
            
            $pendingPaymentsList[] = (object)[
                'CompanyName' => $row->CompanyName ?: $row->CustomerName,
                'DueAmount' => $row->DueAmount,
                'DaysOverdue' => $daysOverdue,
                'SaleDate' => $row->SaleDate,
                'MobileNo' => $row->MobileNo
            ];
        }

        usort($pendingPaymentsList, function($a, $b) {
            return $b->DaysOverdue - $a->DaysOverdue;
        });

        $pendingPaymentsList = array_slice($pendingPaymentsList, 0, 10);

        // Get Cash Account ID dynamically
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

        $allTimeInflow = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details')
            ->where('AccountID', $cashAccountId)
            ->get()
            ->row()
            ->total;

        $allTimeOutflow = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details')
            ->where('AccountID', $cashAccountId)
            ->get()
            ->row()
            ->total;

        $cashFlow = $allTimeInflow - $allTimeOutflow;

        $expenseRow = $this->db->select('IFNULL(SUM(Amount),0) as total')->where('IsDeleted', 0)->get('expenses')->row();
        $totalExpenses = $expenseRow->total;
        $netProfit = $totalSales - $totalExpenses;

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

        // Monthly Sales (current year only)
        $currentYear = date('Y');
        $monthlySales = array();
        $monthlyExpenses = array();
        $monthlyProfit = array();

        for($m = 1; $m <= 12; $m++)
        {
            $sale = $this->db->select('IFNULL(SUM(TotalSaleAmount),0) as total')
                ->where('YEAR(SaleDate)', $currentYear)
                ->where('MONTH(SaleDate)', $m)
                ->where('IsDeleted', 0)
                ->get('sales')
                ->row();
            $monthlySales[] = $sale->total;
            
            $expense = $this->db->select('IFNULL(SUM(Amount),0) as total')
                ->where('YEAR(ExpenseDate)', $currentYear)
                ->where('MONTH(ExpenseDate)', $m)
                ->where('IsDeleted', 0)
                ->get('expenses')
                ->row();
            $monthlyExpenses[] = $expense->total;
            
            $monthlyProfit[] = $monthlySales[$m-1] - $monthlyExpenses[$m-1];
        }

        // Top Customers
        $topCustomers = $this->db
            ->select('customer.CompanyName, SUM(sales.TotalSaleAmount) AS TotalSales')
            ->from('sales')
            ->join('customer', 'customer.CustomerId = sales.CustomerId', 'left')
            ->where('sales.IsDeleted', 0)
            ->group_by('sales.CustomerId')
            ->order_by('TotalSales', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        // Top Products
        $topProducts = $this->db
            ->select('sale_items.ItemID, items.ItemName, SUM(sale_items.Quantity) AS TotalQty')
            ->from('sale_items')
            ->join('items', 'items.ItemID = sale_items.ItemID', 'left')
            ->group_by('sale_items.ItemID')
            ->order_by('TotalQty', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        // =====================================
        // BANK BALANCES
        // =====================================
        
        $bankBalances = array();
        $totalBankBalance = 0;

        $banks = $this->db->where('IsDeleted',0)->order_by('BankName','ASC')->get('banks')->result();

        foreach($banks as $bank)
        {
            $balance = floatval($bank->OpeningBalance);
            
            if(!empty($bank->AccountID))
            {
                $journalCredits = $this->db->select('IFNULL(SUM(jd.CreditAmount),0) Amount')
                    ->from('journal_entry_details jd')
                    ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                    ->where('jd.AccountID',$bank->AccountID)
                    ->not_like('je.ReferenceNo', 'SAL-', 'after')
                    ->get()
                    ->row()
                    ->Amount;
                $balance -= floatval($journalCredits);
                
                $journalDebits = $this->db->select('IFNULL(SUM(jd.DebitAmount),0) Amount')
                    ->from('journal_entry_details jd')
                    ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                    ->where('jd.AccountID',$bank->AccountID)
                    ->not_like('je.ReferenceNo', 'SAL-', 'after')
                    ->get()
                    ->row()
                    ->Amount;
                $balance += floatval($journalDebits);
            }
            
            $bankBalances[] = array(
                'BankID'        => $bank->BankID,
                'BankName'      => $bank->BankName,
                'AccountNumber' => $bank->AccountNumber,
                'AccountName'   => $bank->AccountName,
                'Balance'       => $balance
            );
            
            $totalBankBalance += $balance;
        }

        // =====================================
        // CASH IN HAND
        // =====================================
        
        $cashInHand = $cashFlow;

        $totalCustomers = $this->Customer_model->get_total_customers();
        $totalVendors = count($vendors);

        // Calculate Total Vendor Due (Accounts Payable)
        $totalVendorDue = 0;
        $vendorPendingList = array();
        foreach ($vendors as $vendor) {
            $vendorDue = floatval($vendor->PaymentDue);
            $totalVendorDue += $vendorDue;
            if ($vendorDue > 0) {
                $vendorPendingList[] = (object)[
                    'VendorName' => $vendor->VendorName,
                    'DueAmount' => $vendorDue
                ];
            }
        }
        
        // Sort and slice vendor pending payments
        usort($vendorPendingList, function($a, $b) {
            return $b->DueAmount <=> $a->DueAmount;
        });
        $vendorPendingList = array_slice($vendorPendingList, 0, 10);

        // =====================================
        // ANNUAL TURNOVER (HISTORICAL + CURRENT)
        // =====================================
        
        // Get current year
        $currentYear = (int)date('Y');

        // Dynamically fetch annual turnover and payments from the database
        $salesByYear = $this->db
            ->select('YEAR(SaleDate) as year, SUM(TotalSaleAmount) as total')
            ->where('IsDeleted', 0)
            ->group_by('YEAR(SaleDate)')
            ->order_by('YEAR(SaleDate)', 'ASC')
            ->get('sales')
            ->result();

        $paymentsByYear = $this->db
            ->select('YEAR(PaymentDate) as year, SUM(PaidAmount) as total')
            ->where('IsDeleted', 0)
            ->group_by('YEAR(PaymentDate)')
            ->order_by('YEAR(PaymentDate)', 'ASC')
            ->get('customer_payments')
            ->result();

        $turnoverData = [];
        $paymentsData = [];
        $yearsList = [];

        foreach ($salesByYear as $row) {
            $y = (int)$row->year;
            if ($y > 0) {
                $yearsList[] = $y;
                $turnoverData[$y] = (float)$row->total;
            }
        }
        foreach ($paymentsByYear as $row) {
            $y = (int)$row->year;
            if ($y > 0) {
                $yearsList[] = $y;
                $paymentsData[$y] = (float)$row->total;
            }
        }
        $yearsList = array_unique($yearsList);
        sort($yearsList);

        foreach ($yearsList as $y) {
            if (!isset($turnoverData[$y])) $turnoverData[$y] = 0.0;
            if (!isset($paymentsData[$y])) $paymentsData[$y] = 0.0;
        }

        if (empty($yearsList)) {
            $yearsList = [$currentYear];
            $turnoverData[$currentYear] = 0.0;
            $paymentsData[$currentYear] = 0.0;
        }

        $chartYears = array_keys($turnoverData);
        $chartValues = array_values($turnoverData);
        $chartPaymentsValues = array_values($paymentsData);

        // Calculate current year totals dynamically from the database
        $currentYearTurnover = isset($turnoverData[$currentYear]) ? $turnoverData[$currentYear] : 0.0;
        $currentYearPayments = isset($paymentsData[$currentYear]) ? $paymentsData[$currentYear] : 0.0;
        $historicalJanMay2026 = 0.0;
        $historicalJanMayPayments2026 = 0.0;

        // Fetch monthly income for the last 5 years dynamically
        $startYear = $currentYear - 4;
        $monthlyIncomeByYear = [];
        for ($y = $startYear; $y <= $currentYear; $y++) {
            $monthlyData = [];
            for ($m = 1; $m <= 12; $m++) {
                $sum = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')
                    ->where('IsDeleted', 0)
                    ->where('YEAR(PaymentDate)', $y)
                    ->where('MONTH(PaymentDate)', $m)
                    ->get('customer_payments')
                    ->row()->total;
                $monthlyData[] = $sum;
            }
            // Only include year if it has records or is the current year
            if (array_sum($monthlyData) > 0 || $y === $currentYear) {
                $monthlyIncomeByYear[$y] = $monthlyData;
            }
        }

        // Prepare multi-year dataset for line chart
        $colors = ['#d97706', '#8b5cf6', '#0284c7', '#ef4444', '#10b981'];
        $chartDatasets = [];
        $pieLabels = [];
        $pieData = [];
        $pieColors = [];

        krsort($monthlyIncomeByYear);
        $idx = 0;
        foreach ($monthlyIncomeByYear as $y => $monthlyData) {
            $color = $colors[$idx % count($colors)];
            $chartDatasets[] = [
                'label' => (string)$y,
                'data' => array_map(function($v) { return $v > 0 ? (float)$v : null; }, $monthlyData),
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'borderWidth' => ($y === $currentYear) ? 3 : 2,
                'tension' => 0.3,
                'spanGaps' => true
            ];
            $pieLabels[] = (string)$y;
            $pieData[] = array_sum($monthlyData);
            $pieColors[] = $color;
            $idx++;
        }

        // Backward compatible monthly income for current year
        $incomeData2026 = isset($monthlyIncomeByYear[$currentYear]) ? $monthlyIncomeByYear[$currentYear] : array_fill(0, 12, 0.0);

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

        // Period Received
        $receivedMonth = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')->where('IsDeleted', 0)->where('PaymentDate >=', date('Y-m-01'))->where('PaymentDate <=', date('Y-m-t'))->get('customer_payments')->row()->total;
        $receivedYear  = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')->where('IsDeleted', 0)->where('PaymentDate >=', date('Y-01-01'))->where('PaymentDate <=', date('Y-12-31'))->get('customer_payments')->row()->total;
        $receivedPrevMonth = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')->where('IsDeleted', 0)->where('PaymentDate >=', date('Y-m-01', strtotime('-1 month')))->where('PaymentDate <=', date('Y-m-t', strtotime('-1 month')))->get('customer_payments')->row()->total;

        // Cash Account Inflows (Debits to Cash Account)
        $cashInflowMonth = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01'))
            ->where('je.JournalDate <=', date('Y-m-t'))
            ->get()
            ->row()
            ->total;

        $cashInflowYear  = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-01-01'))
            ->where('je.JournalDate <=', date('Y-12-31'))
            ->get()
            ->row()
            ->total;

        $cashInflowPrevMonth = (float)$this->db->select('IFNULL(SUM(DebitAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01', strtotime('-1 month')))
            ->where('je.JournalDate <=', date('Y-m-t', strtotime('-1 month')))
            ->get()
            ->row()
            ->total;

        // Period Expenses (from expenses table)
        $expensesMonth = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')->where('IsDeleted', 0)->where('ExpenseDate >=', date('Y-m-01'))->where('ExpenseDate <=', date('Y-m-t'))->get('expenses')->row()->total;
        $expensesYear  = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')->where('IsDeleted', 0)->where('ExpenseDate >=', date('Y-01-01'))->where('ExpenseDate <=', date('Y-12-31'))->get('expenses')->row()->total;
        $expensesPrevMonth = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')->where('IsDeleted', 0)->where('ExpenseDate >=', date('Y-m-01', strtotime('-1 month')))->where('ExpenseDate <=', date('Y-m-t', strtotime('-1 month')))->get('expenses')->row()->total;

        // Cash Account Outflows (Credits to Cash Account)
        $cashOutflowMonth = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-m-01'))
            ->where('je.JournalDate <=', date('Y-m-t'))
            ->get()
            ->row()
            ->total;

        $cashOutflowYear  = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
            ->from('journal_entry_details jed')
            ->join('journal_entries je', 'je.JournalID = jed.JournalID', 'inner')
            ->where('jed.AccountID', $cashAccountId)
            ->where('je.JournalDate >=', date('Y-01-01'))
            ->where('je.JournalDate <=', date('Y-12-31'))
            ->get()
            ->row()
            ->total;

        $cashOutflowPrevMonth = (float)$this->db->select('IFNULL(SUM(CreditAmount), 0) as total')
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
        $cashFlowMonth = $cashInflowMonth - $cashOutflowMonth;
        $cashFlowYear  = $cashInflowYear - $cashOutflowYear;
        $cashFlowPrevMonth = $cashInflowPrevMonth - $cashOutflowPrevMonth;

        // Prepare arrays for chart
        $chartYears = array_keys($turnoverData);
        $chartValues = array_values($turnoverData);
        $chartPaymentsValues = array_values($paymentsData);

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

        $data = array(
            "user" => $this->session->userdata('user'),
            "total_customers" => $totalCustomers,
            "total_vendors" => $totalVendors,
            "totalSales" => $totalSales,
            "totalPaidAmount" => $totalPaidAmount,
            "totalDueAmount" => $totalDueAmount,
            "totalVendorDue" => $totalVendorDue,
            "pendingPayment" => $pendingPayment,
            "pendingPaymentsList" => $pendingPaymentsList,
            "vendorPendingList" => $vendorPendingList,
            "pendingQuotationsList" => $pendingQuotationsList,
            "totalPendingQuotationsCount" => $totalPendingQuotationsCount,
            "totalPendingQuotationsAmount" => $totalPendingQuotationsAmount,
            "totalExpenses" => $totalExpenses,
            "netProfit" => $netProfit,
            "cashFlow" => $cashFlow,
            
            "todaySalesTotal" => $todaySalesTotal,
            "todayCashTotal" => $todayCashTotal,
            "todayCreditTotal" => $todayCreditTotal,
            
            // Period metrics
            "salesMonth" => $salesMonth,
            "salesYear"  => $salesYear,
            "salesPrevMonth" => $salesPrevMonth,
            "receivedMonth" => $receivedMonth,
            "receivedYear"  => $receivedYear,
            "receivedPrevMonth" => $receivedPrevMonth,
            "expensesMonth" => $expensesMonth,
            "expensesYear"  => $expensesYear,
            "expensesPrevMonth" => $expensesPrevMonth,
            "netProfitMonth" => $netProfitMonth,
            "netProfitYear"  => $netProfitYear,
            "netProfitPrevMonth" => $netProfitPrevMonth,
            "cashFlowMonth" => $cashFlowMonth,
            "cashFlowYear"  => $cashFlowYear,
            "cashFlowPrevMonth" => $cashFlowPrevMonth,

            "monthlySales" => json_encode($monthlySales),
            "monthlyProfit" => json_encode($monthlyProfit),
            "topCustomers" => $topCustomers,
            "topProducts" => $topProducts,
            "bankBalances" => $bankBalances,
            "totalBankBalance" => $totalBankBalance,
            "cashInHand" => $cashInHand,
            // Annual turnover data
            "chartYears" => json_encode($chartYears),
            "chartTurnover" => json_encode($chartValues),
            "chartPayments" => json_encode($chartPaymentsValues),
            "currentYearTurnover" => $currentYearTurnover,
            "currentYearPayments" => $currentYearPayments,
            "systemTurnover" => $currentYearTurnover,
            "systemPayments" => $currentYearPayments,
            "historicalJanMay2026" => $historicalJanMay2026,
            "historicalJanMayPayments2026" => $historicalJanMayPayments2026,
            "turnoverData" => $turnoverData,
            "paymentsData" => $paymentsData,
            "incomeData2026" => $incomeData2026,
            "chartDatasets" => json_encode($chartDatasets),
            "pieLabels" => json_encode($pieLabels),
            "pieData" => json_encode($pieData),
            "pieColors" => json_encode($pieColors)
        );

        $this->load->view('dashboard', $data);
    }

    public function bank_ledger($bankID)
    {
        $bank = $this->db->where('BankID',$bankID)->get('banks')->row();

        if(!$bank)
        {
            show_404();
        }

        $selected_month = $this->input->get('month');
        $selected_year = $this->input->get('year');
        
        // If not set, default to current month and year
        if ($selected_month === null && $selected_year === null) {
            $selected_month = date('m');
            $selected_year = date('Y');
        }

        $balance = floatval($bank->OpeningBalance);
        $ledger = array();
        
        if(!empty($bank->AccountID))
        {
            if ($selected_month !== 'all') {
                $start_date = "$selected_year-$selected_month-01 00:00:00";
                $end_date = date("Y-m-t 23:59:59", strtotime($start_date));
                
                // Get opening balance before this month
                $prevCredits = $this->db->select('IFNULL(SUM(jd.CreditAmount),0) Amount')
                    ->from('journal_entry_details jd')
                    ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                    ->where('jd.AccountID', $bank->AccountID)
                    ->where('je.JournalDate <', $start_date)
                    ->not_like('je.ReferenceNo', 'SAL-', 'after')
                    ->get()
                    ->row()
                    ->Amount;
                
                $prevDebits = $this->db->select('IFNULL(SUM(jd.DebitAmount),0) Amount')
                    ->from('journal_entry_details jd')
                    ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                    ->where('jd.AccountID', $bank->AccountID)
                    ->where('je.JournalDate <', $start_date)
                    ->not_like('je.ReferenceNo', 'SAL-', 'after')
                    ->get()
                    ->row()
                    ->Amount;
                
                $balance = $balance + floatval($prevDebits) - floatval($prevCredits);
            }
            
            // Build query for transactions
            $this->db->select('je.JournalDate, je.ReferenceNo, je.Description, jd.DebitAmount, jd.CreditAmount')
                ->from('journal_entry_details jd')
                ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                ->where('jd.AccountID', $bank->AccountID)
                ->not_like('je.ReferenceNo', 'SAL-', 'after');

            if ($selected_month !== 'all') {
                $this->db->where('je.JournalDate >=', $start_date);
                $this->db->where('je.JournalDate <=', $end_date);
            }

            $journals = $this->db->order_by('je.JournalDate', 'ASC')->get()->result();
            
            foreach($journals as $journal)
            {
                $debitAmount = floatval($journal->DebitAmount);
                $creditAmount = floatval($journal->CreditAmount);
                
                if($debitAmount > 0)
                {
                    $ledger[] = array(
                        'Date' => $journal->JournalDate,
                        'Description' => ($journal->ReferenceNo ? $journal->ReferenceNo . ' - ' : '') . ($journal->Description ?: 'Debit Entry'),
                        'Debit' => $debitAmount,
                        'Credit' => 0
                    );
                }
                elseif($creditAmount > 0)
                {
                    $ledger[] = array(
                        'Date' => $journal->JournalDate,
                        'Description' => ($journal->ReferenceNo ? $journal->ReferenceNo . ' - ' : '') . ($journal->Description ?: 'Credit Entry'),
                        'Debit' => 0,
                        'Credit' => $creditAmount
                    );
                }
            }
        }
        
        usort($ledger, function($a, $b) {
            return strtotime($a['Date']) - strtotime($b['Date']);
        });
        
        $running = $balance;
        foreach($ledger as $key => $row)
        {
            $running = $running + $row['Debit'] - $row['Credit'];
            $ledger[$key]['Balance'] = $running;
        }
        
        $data['bank'] = $bank;
        $data['transactions'] = $ledger;
        $data['selected_month'] = $selected_month;
        $data['selected_year'] = $selected_year;
        $data['bank_opening_balance'] = $balance;

        $this->load->view('pages/banks/ledger', $data);
    }

    public function dashboard()
    {
        redirect(base_url('admin'));
    }

    public function monthly_financials($year = null)
    {
        if (!$this->session->has_userdata('user')) {
            redirect(base_url('admin'));
        }

        $year = $year ?: date('Y');
        
        $monthly_data = array();
        
        for ($m = 1; $m <= 12; $m++) {
            $month_name = date('F', mktime(0, 0, 0, $m, 1));
            
            // Sales (Income)
            $sales = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(SaleDate)', $year)
                ->where('MONTH(SaleDate)', $m)
                ->get('sales')
                ->row()->total;
                
            // Other Income
            $other_income = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')
                ->where('YEAR(IncomeDate)', $year)
                ->where('MONTH(IncomeDate)', $m)
                ->get('incomes')
                ->row()->total;
                
            // Total Income
            $total_income = $sales + $other_income;
            
            // Received Payments (from customer_payments table)
            $received = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(PaymentDate)', $year)
                ->where('MONTH(PaymentDate)', $m)
                ->get('customer_payments')
                ->row()->total;
            
            // Expenses (from expenses table)
            $expenses = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(ExpenseDate)', $year)
                ->where('MONTH(ExpenseDate)', $m)
                ->get('expenses')
                ->row()->total;
                
            $monthly_data[] = array(
                'month_num' => $m,
                'month_name' => $month_name,
                'sales' => $sales,
                'other_income' => $other_income,
                'total_income' => $total_income,
                'received' => $received,
                'expenses' => $expenses,
                'profit' => $total_income - $expenses
            );
        }
        
        // Fetch available years for the dropdown
        $min_sales_year = $this->db->select('MIN(YEAR(SaleDate)) as min_year')->where('IsDeleted', 0)->get('sales')->row()->min_year;
        $min_income_year = $this->db->select('MIN(YEAR(IncomeDate)) as min_year')->get('incomes')->row()->min_year;
        $min_expense_year = $this->db->select('MIN(YEAR(ExpenseDate)) as min_year')->where('IsDeleted', 0)->get('expenses')->row()->min_year;
        
        $min_year = min(
            $min_sales_year ?: date('Y'),
            $min_income_year ?: date('Y'),
            $min_expense_year ?: date('Y')
        );
        $max_year = date('Y');
        
        $years = array();
        for ($y = $max_year; $y >= $min_year; $y--) {
            $years[] = $y;
        }
        
        $data = array(
            'year' => $year,
            'monthly_data' => $monthly_data,
            'years' => $years
        );
        
        $this->load->view('pages/reports/monthly_financials', $data);
    }

    public function export_monthly_financials($year = null)
    {
        if (!$this->session->has_userdata('user')) {
            redirect(base_url('admin'));
        }

        $year = $year ?: date('Y');
        
        // Output CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Monthly_Financial_Report_'.$year.'.csv');
        
        $output = fopen('php://output', 'w');
        
        // CSV Header row
        fputcsv($output, array('Monthly Financial Summary Report - ' . $year));
        fputcsv($output, array(''));
        fputcsv($output, array('Month', 'Sales Income (LKR)', 'Other Income (LKR)', 'Total Income (LKR)', 'Received Payment (LKR)', 'Expenses (LKR)', 'Net Profit / Loss (LKR)'));
        
        $totalSalesYear = 0;
        $totalOtherIncomeYear = 0;
        $totalIncomeYear = 0;
        $totalReceivedYear = 0;
        $totalExpensesYear = 0;
        $totalProfitYear = 0;
        
        for ($m = 1; $m <= 12; $m++) {
            $month_name = date('F', mktime(0, 0, 0, $m, 1));
            
            // Sales
            $sales = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(SaleDate)', $year)
                ->where('MONTH(SaleDate)', $m)
                ->get('sales')
                ->row()->total;
                
            // Other Income
            $other_income = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')
                ->where('YEAR(IncomeDate)', $year)
                ->where('MONTH(IncomeDate)', $m)
                ->get('incomes')
                ->row()->total;
                
            $total_income = $sales + $other_income;
            
            // Received Payments
            $received = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(PaymentDate)', $year)
                ->where('MONTH(PaymentDate)', $m)
                ->get('customer_payments')
                ->row()->total;
                
            // Expenses
            $expenses = (float)$this->db->select('IFNULL(SUM(Amount), 0) as total')
                ->where('IsDeleted', 0)
                ->where('YEAR(ExpenseDate)', $year)
                ->where('MONTH(ExpenseDate)', $m)
                ->get('expenses')
                ->row()->total;
                
            $profit = $total_income - $expenses;
            
            $totalSalesYear += $sales;
            $totalOtherIncomeYear += $other_income;
            $totalIncomeYear += $total_income;
            $totalReceivedYear += $received;
            $totalExpensesYear += $expenses;
            $totalProfitYear += $profit;
            
            fputcsv($output, array(
                $month_name,
                number_format($sales, 2, '.', ''),
                number_format($other_income, 2, '.', ''),
                number_format($total_income, 2, '.', ''),
                number_format($received, 2, '.', ''),
                number_format($expenses, 2, '.', ''),
                number_format($profit, 2, '.', '')
            ));
        }
        
        // Total row
        fputcsv($output, array(''));
        fputcsv($output, array(
            'TOTAL',
            number_format($totalSalesYear, 2, '.', ''),
            number_format($totalOtherIncomeYear, 2, '.', ''),
            number_format($totalIncomeYear, 2, '.', ''),
            number_format($totalReceivedYear, 2, '.', ''),
            number_format($totalExpensesYear, 2, '.', ''),
            number_format($totalProfitYear, 2, '.', '')
        ));
        
        fclose($output);
        exit;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        echo json_encode(array('logout' => true));
    }

    public function mobile_preview()
    {
        $this->load->view('pages/mobile_preview');
    }
}