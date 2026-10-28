<?php
// Customer Payments Model
class Customer_payments_model extends MY_Model
{
    protected $_table = "customer_payments";
    protected $primary_key = "PaymentID";

    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Hook before creating a record
    public function prop_data_before_create($data)
    {
        return $data;
    }

    // Hook before updating a record
    public function prop_data_before_update($data)
    {
        return $data;
    }

    // Get all active customers
    public function get_customers()
    {
        $this->db->where('IsDeleted', 0);
        return $this->db->get('customer')->result();
    }

    // Get all active sales
    public function get_sales()
    {
        $this->db->where('IsDeleted', 0);
        return $this->db->get('sales')->result();
    }

    // Get all payments with customer and sale info
    public function get_all()
    {
        $this->db->select('
            cp.*,
            c.CustomerName,
            c.CompanyName,
            c.DomainName,
            s.TotalSaleAmount,
            s.PONo,
            s.RefNo,
            u.UserName,
            b.BankName
        ');

        $this->db->from('customer_payments cp');

        $this->db->join(
            'customer c',
            'cp.CustomerId = c.CustomerId',
            'left'
        );

        $this->db->join(
            'sales s',
            'cp.SaleID = s.SaleID',
            'left'
        );

        $this->db->join(
            'user_tab u',
            'u.UserId = cp.UserId',
            'left'
        );

        $this->db->join(
            'banks b',
            'b.BankID = cp.BankID',
            'left'
        );

        $this->db->where('cp.IsDeleted', 0);

        $this->db->order_by('cp.PaymentID','DESC');

        return $this->db->get()->result();
    }


    // Get payment by PaymentID
    public function get_payment_by_id($id)
    {
        $this->db->select('
            customer_payments.*,
            customer.CustomerName,
            sales.TotalSaleAmount,
            user_tab.Username,
            banks.BankName
        ');

        $this->db->from('customer_payments');

        $this->db->join(
            'customer',
            'customer_payments.CustomerId = customer.CustomerId',
            'left'
        );

        $this->db->join(
            'sales',
            'customer_payments.SaleID = sales.SaleID',
            'left'
        );

        $this->db->join(
            'user_tab',
            'user_tab.UserId = customer_payments.UserId',
            'left'
        );

        $this->db->join(
            'banks',
            'banks.BankID = customer_payments.BankID',
            'left'
        );

        $this->db->where(
            'customer_payments.PaymentID',
            $id
        );

        $this->db->where(
            'customer_payments.IsDeleted',
            0
        );

        return $this->db->get()->row();
    }


    // Get customer details with sales, total paid and due
    public function get_customer_details($customerId)
    {
        $this->db->select("
            customer.CustomerName,
            customer.MobileNo,
            customer.Address,
            customer.Email,
            sales.SaleID,
            sales.SaleDate,
            sales.TotalSaleAmount,
            IFNULL(SUM(customer_payments.PaidAmount),0) AS TotalPaid,
            (sales.TotalSaleAmount - IFNULL(SUM(customer_payments.PaidAmount),0)) AS TotalDue
        ");
        $this->db->from("sales");
        $this->db->join("customer", "customer.CustomerId = sales.CustomerId", "left");
        $this->db->join(
            "customer_payments",
            "sales.CustomerId = customer_payments.CustomerId 
             AND sales.SaleID = customer_payments.SaleID
             AND customer_payments.IsDeleted = 0",
            "left"
        );
        $this->db->where("sales.CustomerId", $customerId);
        $this->db->where("sales.IsDeleted", 0);
        $this->db->group_by("sales.SaleID");
        $this->db->order_by("sales.SaleDate", "DESC");

        return $this->db->get()->result();
    }

    // Get total due amount for a customer
    public function get_total_due_amount($customerId)
    {
        $subquery_sales = "(SELECT sales.CustomerId, COALESCE(SUM(sales.TotalSaleAmount), 0) AS TotalSaleAmount 
                            FROM sales WHERE sales.IsDeleted = 0 GROUP BY sales.CustomerId)";
        $subquery_payments = "(SELECT customer_payments.CustomerId, COALESCE(SUM(customer_payments.PaidAmount), 0) AS TotalPaidAmount 
                               FROM customer_payments WHERE customer_payments.IsDeleted = 0 GROUP BY customer_payments.CustomerId)";

        $this->db->select("
            COALESCE(sales_table.TotalSaleAmount, 0) AS TotalSaleAmount,
            COALESCE(payments_table.TotalPaidAmount, 0) AS TotalPaidAmount,
            COALESCE(sales_table.TotalSaleAmount, 0) - COALESCE(payments_table.TotalPaidAmount, 0) AS CreditBalance
        ");
        $this->db->from('customer');
        $this->db->join("($subquery_sales) AS sales_table", 'customer.CustomerId = sales_table.CustomerId', 'left');
        $this->db->join("($subquery_payments) AS payments_table", 'customer.CustomerId = payments_table.CustomerId', 'left');
        $this->db->where('customer.CustomerId', $customerId);
        $this->db->where('customer.IsDeleted', 0);

        $result = $this->db->get()->row();

        return [
            'TotalSaleAmount' => $result->TotalSaleAmount ?: 0,
            'TotalPaidAmount' => $result->TotalPaidAmount ?: 0,
            'CreditBalance'   => $result->CreditBalance ?: 0,
        ];
    }

    // Get all customers with due amount
    public function get_all_customers_with_due()
    {
        $subquery_sales = '(SELECT sales.CustomerId, SUM(sales.TotalSaleAmount) as TotalSaleAmount 
                            FROM sales WHERE sales.IsDeleted = 0 
                            GROUP BY sales.CustomerId) as sales_table';
        $subquery_payments = '(SELECT customer_payments.CustomerId, SUM(customer_payments.PaidAmount) as TotalPaidAmount 
                               FROM customer_payments WHERE customer_payments.IsDeleted = 0 
                               GROUP BY customer_payments.CustomerId) as payments_table';

        $this->db->select('customer.CustomerName, 
                           customer.CompanyName, 
                           customer.DomainName,
                           (
                               SELECT GROUP_CONCAT(DISTINCT items.ItemName SEPARATOR ", ")
                               FROM sales s
                               JOIN sale_items si ON s.SaleID = si.SaleID
                               JOIN items ON si.ItemID = items.ItemID
                               LEFT JOIN (
                                   SELECT cp.SaleID, SUM(cp.PaidAmount) as PaidAmount
                                   FROM customer_payments cp
                                   WHERE cp.IsDeleted = 0
                                   GROUP BY cp.SaleID
                               ) p ON s.SaleID = p.SaleID
                               WHERE s.CustomerId = customer.CustomerId 
                                 AND s.IsDeleted = 0 
                                 AND (s.TotalSaleAmount - COALESCE(p.PaidAmount, 0)) > 0
                           ) AS ServiceNames,
                           (
                               SELECT DATEDIFF(CURDATE(), MIN(s.SaleDate))
                               FROM sales s
                               LEFT JOIN (
                                   SELECT cp.SaleID, SUM(cp.PaidAmount) as PaidAmount
                                   FROM customer_payments cp
                                   WHERE cp.IsDeleted = 0
                                   GROUP BY cp.SaleID
                               ) p ON s.SaleID = p.SaleID
                               WHERE s.CustomerId = customer.CustomerId 
                                 AND s.IsDeleted = 0 
                                 AND (s.TotalSaleAmount - COALESCE(p.PaidAmount, 0)) > 0
                           ) AS DaysPending,
                           COALESCE(sales_table.TotalSaleAmount, 0) AS TotalSaleAmount, 
                           COALESCE(payments_table.TotalPaidAmount, 0) AS TotalPaidAmount,
                           (COALESCE(sales_table.TotalSaleAmount, 0) - COALESCE(payments_table.TotalPaidAmount, 0)) AS PaymentDue', FALSE);
        $this->db->from('customer');
        $this->db->join($subquery_sales, 'customer.CustomerId = sales_table.CustomerId', 'left');
        $this->db->join($subquery_payments, 'customer.CustomerId = payments_table.CustomerId', 'left');
        $this->db->where('customer.IsDeleted', 0);
        $this->db->group_by('customer.CustomerId');

        return $this->db->get()->result();
    }

    // Get customer credit info
    public function get_customer_with_credit($customerId)
    {
        $this->db->select('CustomerName, CreditLimit');
        $this->db->from('customer');
        $this->db->where('CustomerId', $customerId);
        return $this->db->get()->row();
    }

    public function get_credit_balance($customerId)
    {
        $this->db->select('CreditBalance');
        $this->db->where('CustomerId', $customerId);
        $this->db->order_by('CreatedDate', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get('customer_payments')->row();

        return $result ? $result->CreditBalance : 0.00;
    }

    // Customer ledger
    public function get_customer_ledger($customerId, $from = null, $to = null)
    {
        // Normalize dates
        $from_date = $from ? date('Y-m-d', strtotime($from)) : null;
        $to_date = $to ? date('Y-m-d', strtotime($to)) : null;

        // 1) Opening balances BEFORE $from (if from provided)
        if ($from_date) {
            $this->db->select_sum('TotalSaleAmount', 'sum_sales_before');
            $this->db->from('sales');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(SaleDate) <', $from_date);
            $sales_before_row = $this->db->get()->row();
            $totalSalesBefore = $sales_before_row ? (float)$sales_before_row->sum_sales_before : 0;

            $this->db->select_sum('PaidAmount', 'sum_paid_before');
            $this->db->from('customer_payments');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(PaymentDate) <', $from_date);
            $paid_before_row = $this->db->get()->row();
            $totalPaidBefore = $paid_before_row ? (float)$paid_before_row->sum_paid_before : 0;
        } else {
            $totalSalesBefore = 0;
            $totalPaidBefore = 0;
        }

        $opening_balance = $totalSalesBefore - $totalPaidBefore;

        // 2) Fetch invoices (sales) in range
        $this->db->select("SaleID, SaleDate as date, TotalSaleAmount as amount");
        $this->db->from('sales');
        $this->db->where('CustomerId', $customerId);
        $this->db->where('IsDeleted', 0);
        if ($from_date) $this->db->where('DATE(SaleDate) >=', $from_date);
        if ($to_date)   $this->db->where('DATE(SaleDate) <=', $to_date);
        $invoices = $this->db->get()->result_array();

        $invoice_map = array();
        foreach ($invoices as $inv) {
            $saleId = $inv['SaleID'];

            // Sum payments applied to this SaleID (respect IsDeleted and date filters)
            $this->db->select_sum('PaidAmount', 'paid');
            $this->db->from('customer_payments');
            $this->db->where('SaleID', $saleId);
            $this->db->where('IsDeleted', 0);
            if ($to_date) {
                $this->db->where('DATE(PaymentDate) <=', $to_date);
            }
            $paid_row = $this->db->get()->row();
            $paid_for_invoice = $paid_row ? (float)$paid_row->paid : 0;

            $due = (float)$inv['amount'] - $paid_for_invoice;
            if ($due < 0) $due = 0;

            $invoice_map[] = array(
                'ref' => $saleId,
                'date' => $inv['date'],
                'type' => 'invoice',
                'description' => "Invoice #{$saleId}",
                'debit' => (float)$inv['amount'],
                'credit' => 0.0,
                'paid' => $paid_for_invoice,
                'due' => $due,
            );
        }

        // 3) Fetch payments in range
        $this->db->select("PaymentID, PaymentDate as date, PaidAmount as amount, SaleID, PaymentMethod, ChequeNumber, ChequeDate, BankName");
        $this->db->from('customer_payments');
        $this->db->where('CustomerId', $customerId);
        $this->db->where('IsDeleted', 0);
        if ($from_date) $this->db->where('DATE(PaymentDate) >=', $from_date);
        if ($to_date)   $this->db->where('DATE(PaymentDate) <=', $to_date);
        $payments_raw = $this->db->get()->result_array();

        $payments = array();
        foreach ($payments_raw as $p) {
            $payments[] = array(
                'ref' => $p['PaymentID'],
                'date' => $p['date'],
                'type' => 'payment',
                'description' => "Payment (Sale #{$p['SaleID']})",
                'debit' => 0.0,
                'credit' => (float)$p['amount'],
                'payment_method' => $p['PaymentMethod'],
                'sale_id' => $p['SaleID'],
                'cheque_number' => $p['ChequeNumber'],
                'cheque_date' => $p['ChequeDate'],
                'bank_name' => $p['BankName'],
            );
        }

        // 4) Merge and sort entries by date then type (invoices first on same date)
        $entries = array_merge($invoice_map, $payments);
        usort($entries, function($a, $b) {
            $da = strtotime($a['date']);
            $db = strtotime($b['date']);
            if ($da === $db) {
                if ($a['type'] === $b['type']) return 0;
                return ($a['type'] === 'invoice') ? -1 : 1;
            }
            return $da < $db ? -1 : 1;
        });

        // 5) Compute running balance starting from opening_balance
        $running = $opening_balance;
        for ($i = 0; $i < count($entries); $i++) {
            $e = &$entries[$i];

            $debitVal = isset($e['debit']) ? $e['debit'] : 0;
            $creditVal = isset($e['credit']) ? $e['credit'] : 0;

            $running = $running + $debitVal - $creditVal;
            $e['balance'] = round($running, 2);

            // Normalize number formats
            $e['debit'] = round($debitVal, 2);
            $e['credit'] = round($creditVal, 2);
            $e['paid'] = isset($e['paid']) ? round($e['paid'], 2) : null;
            $e['due'] = isset($e['due']) ? round($e['due'], 2) : null;
        }
        unset($e);

        return array(
            'opening_balance' => round($opening_balance, 2),
            'entries' => $entries
        );
    }


    // Customer statement / SOA
    public function get_customer_statement($customerId, $from = null, $to = null, $show_all = true)
    {
        // Normalize dates
        $from_date = $from ? date('Y-m-d', strtotime($from)) : null;
        $to_date = $to ? date('Y-m-d', strtotime($to)) : null;

        // 1) Opening balances before $from_date
        $totalSalesBefore = 0;
        $totalPaidBefore = 0;

        if ($from_date) {
            // Total sales before from_date
            $this->db->select_sum('TotalSaleAmount', 'sum_sales_before');
            $this->db->from('sales');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(SaleDate) <', $from_date);
            $row = $this->db->get()->row();
            $totalSalesBefore = $row ? (float)$row->sum_sales_before : 0;

            // Total payments before from_date
            $this->db->select_sum('PaidAmount', 'sum_paid_before');
            $this->db->from('customer_payments');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(PaymentDate) <', $from_date);
            $row2 = $this->db->get()->row();
            $totalPaidBefore = $row2 ? (float)$row2->sum_paid_before : 0;
        }

        $opening_balance = $totalSalesBefore - $totalPaidBefore;

        // 2) Get invoices (sales) in range (or all if no range)
        $this->db->select("SaleID, SaleDate, TotalSaleAmount");
        $this->db->from('sales');
        $this->db->where('CustomerId', $customerId);
        $this->db->where('IsDeleted', 0);
        if ($from_date) $this->db->where('DATE(SaleDate) >=', $from_date);
        if ($to_date)   $this->db->where('DATE(SaleDate) <=', $to_date);
        $this->db->order_by('SaleDate', 'ASC');
        $invoices = $this->db->get()->result_array();

        // 3) For each invoice, compute paid (respecting to_date)
        $invoice_entries = array();
        foreach ($invoices as $inv) {
            $saleId = $inv['SaleID'];

            $this->db->select_sum('PaidAmount', 'paid');
            $this->db->from('customer_payments');
            $this->db->where('SaleID', $saleId);
            $this->db->where('IsDeleted', 0);
            if ($to_date) {
                $this->db->where('DATE(PaymentDate) <=', $to_date);
            }
            $paid_row = $this->db->get()->row();
            $paid_for_invoice = $paid_row ? (float)$paid_row->paid : 0;

            $due = (float)$inv['TotalSaleAmount'] - $paid_for_invoice;
            if ($due < 0) $due = 0;

            // If show_all is false, skip fully paid invoices
            if (!$show_all && $due <= 0) {
                continue;
            }

            $invoice_entries[] = array(
                'ref' => $saleId,
                'date' => $inv['SaleDate'],
                'type' => 'invoice',
                'description' => 'Invoice #' . $saleId,
                'debit' => (float)$inv['TotalSaleAmount'],
                'credit' => 0.0,
                'paid' => $paid_for_invoice,
                'due' => $due
            );
        }

        // 4) Fetch payments in date range (payments are credits)
        $this->db->select("PaymentID, PaymentDate, PaidAmount, SaleID, PaymentMethod, ChequeNumber, ChequeDate, BankName");
        $this->db->from('customer_payments');
        $this->db->where('CustomerId', $customerId);
        $this->db->where('IsDeleted', 0);
        if ($from_date) $this->db->where('DATE(PaymentDate) >=', $from_date);
        if ($to_date)   $this->db->where('DATE(PaymentDate) <=', $to_date);
        $this->db->order_by('PaymentDate', 'ASC');
        $payments_raw = $this->db->get()->result_array();

        $payment_entries = array();
        foreach ($payments_raw as $p) {
            $payment_entries[] = array(
                'ref' => $p['PaymentID'],
                'date' => $p['PaymentDate'],
                'type' => 'payment',
                'description' => 'Payment (Sale #' . $p['SaleID'] . ')',
                'debit' => 0.0,
                'credit' => (float)$p['PaidAmount'],
                'payment_method' => $p['PaymentMethod'],
                'sale_id' => $p['SaleID'],
                'cheque_number' => $p['ChequeNumber'],
                'cheque_date' => $p['ChequeDate'],
                'bank_name' => $p['BankName']
            );
        }

        // 5) Merge invoice_entries and payment_entries and sort by date
        $entries = array_merge($invoice_entries, $payment_entries);
        usort($entries, function($a, $b) {
            $da = strtotime($a['date']);
            $db = strtotime($b['date']);
            if ($da == $db) {
                // put invoices before payments on same date
                if ($a['type'] == $b['type']) return 0;
                return ($a['type'] == 'invoice') ? -1 : 1;
            }
            return ($da < $db) ? -1 : 1;
        });

        // 6) Compute running balance
        $running = (float)$opening_balance;
        for ($i = 0; $i < count($entries); $i++) {
            $e = &$entries[$i];
            $debitVal = isset($e['debit']) ? (float)$e['debit'] : 0.0;
            $creditVal = isset($e['credit']) ? (float)$e['credit'] : 0.0;

            $running = $running + $debitVal - $creditVal;
            $e['balance'] = round($running, 2);

            $e['debit'] = round($debitVal, 2);
            $e['credit'] = round($creditVal, 2);
            if (isset($e['paid'])) $e['paid'] = round($e['paid'], 2);
            if (isset($e['due'])) $e['due'] = round($e['due'], 2);
        }
        unset($e);

        return array(
            'opening_balance' => round($opening_balance, 2),
            'entries' => $entries
        );
    }


    // Other utility functions
    public function get_customer($customerId)
    {
        return $this->db->where('CustomerId', $customerId)->get('customer')->row();
    }

    public function get_statement($customerId, $from = null, $to = null, $show_all = false)
    {
        $this->db->select("
            s.SaleID,
            s.SaleDate,
            s.TotalSaleAmount,
            COALESCE(SUM(cp.PaidAmount), 0) AS PaidAmount
        ");
        $this->db->from("sales s");
        $this->db->join("customer_payments cp", "cp.SaleID = s.SaleID AND cp.CustomerId = s.CustomerId", "left");
        $this->db->where("s.CustomerId", $customerId);

        // Date filter only if selected
        if (!empty($from)) {
            $this->db->where("s.SaleDate >=", $from);
        }
        if (!empty($to)) {
            $this->db->where("s.SaleDate <=", $to);
        }

        $this->db->group_by("s.SaleID, s.SaleDate, s.TotalSaleAmount");
        $this->db->order_by("s.SaleDate", "ASC");

        $query = $this->db->get();
        $result = $query->result();

        // Calculate Due Amount and filter results
        $final = [];
        foreach ($result as $r) {
            $r->DueAmount = $r->TotalSaleAmount - $r->PaidAmount;

            // Show all invoices or only pending due
            if ($show_all == true || $r->DueAmount > 0) {
                $final[] = $r;
            }
        }

        return $final;
    }

    public function get_invoice_aging($customerId = null)
    {
        $this->db->select("s.SaleID, s.SaleDate, s.TotalSaleAmount, 
                           IFNULL(paid.PaidAmount, 0) AS PaidAmount,
                           (s.TotalSaleAmount - IFNULL(paid.PaidAmount, 0)) AS DueAmount,
                           DATEDIFF(CURDATE(), s.SaleDate) AS DaysOutstanding");
        $this->db->from("sales s");
        $this->db->join("(SELECT SaleID, SUM(PaidAmount) AS PaidAmount 
                          FROM customer_payments 
                          GROUP BY SaleID) paid", "paid.SaleID = s.SaleID", "left");

        if ($customerId) $this->db->where("s.CustomerId", $customerId);
        $this->db->order_by("s.SaleDate", "ASC");

        return $this->db->get()->result();
    }

    public function get_invoices($customerId = null, $from = null, $to = null)
    {
        $this->db->select("s.SaleID, s.SaleDate, s.TotalSaleAmount, 
                           IFNULL(paid.PaidAmount,0) AS PaidAmount,
                           (s.TotalSaleAmount - IFNULL(paid.PaidAmount,0)) AS DueAmount,
                           DATEDIFF(CURDATE(), s.SaleDate) AS DaysOutstanding");
        $this->db->from("sales s");
        $this->db->join("(SELECT SaleID, SUM(PaidAmount) AS PaidAmount 
                          FROM customer_payments GROUP BY SaleID) paid", "paid.SaleID = s.SaleID", "left");

        if ($customerId) $this->db->where("s.CustomerId", $customerId);
        if ($from) $this->db->where("s.SaleDate >=", $from);
        if ($to) $this->db->where("s.SaleDate <=", $to);
        $this->db->order_by("s.SaleDate", "ASC");

        return $this->db->get()->result();
    }

    public function get_receivables_summary()
    {
        $this->db->select("c.CustomerId, c.CustomerName, c.CompanyName,
                           IFNULL(SUM(s.TotalSaleAmount),0) AS TotalInvoiced,
                           IFNULL(SUM(cp.PaidAmount),0) AS TotalPaid,
                           (IFNULL(SUM(s.TotalSaleAmount),0) - IFNULL(SUM(cp.PaidAmount),0)) AS TotalDue");
        $this->db->from("customer c");
        $this->db->join("sales s", "s.CustomerId = c.CustomerId", "left");
        $this->db->join("(SELECT SaleID, SUM(PaidAmount) AS PaidAmount FROM customer_payments GROUP BY SaleID) cp", "cp.SaleID = s.SaleID", "left");
        $this->db->group_by("c.CustomerId");
        $this->db->order_by("TotalDue", "DESC");

        return $this->db->get()->result();
    }

    public function get_daily_cash_collection($date)
    {
        $this->db->select("PaymentMethod, 
                           IFNULL(SUM(PaidAmount),0) AS TotalAmount,
                           GROUP_CONCAT(CONCAT('Invoice:', SaleID, ' | Amount:', PaidAmount, ' | Cheque:', ChequeNumber, ' | Bank:', BankName, ' | Date:', ChequeDate) SEPARATOR '<br>') as Details");
        $this->db->from('customer_payments');
        $this->db->where('DATE(PaymentDate)', $date);
        $this->db->group_by('PaymentMethod');
        $this->db->order_by('PaymentMethod', 'ASC');

        return $this->db->get()->result();
    }

    public function get_cheque_register($from = null, $to = null)
    {
        $this->db->select("cp.PaymentID, cp.PaymentDate, c.CustomerName, c.CompanyName, cp.PaidAmount, cp.BankName, cp.ChequeNumber, cp.ChequeDate, cp.ChequeStatus");
        $this->db->from('customer_payments cp');
        $this->db->join('customer c', 'c.CustomerId = cp.CustomerId', 'left');
        $this->db->where('cp.PaymentMethod', 'Cheque');

        if ($from) $this->db->where('cp.PaymentDate >=', $from);
        if ($to) $this->db->where('cp.PaymentDate <=', $to);

        $this->db->order_by('cp.PaymentDate', 'DESC');
        return $this->db->get()->result();
    }


    public function get_statement_data($customerId, $from = null, $to = null, $show_all = 1)
    {
        $opening_balance = 0; // default
        $entries = [];        // default

        if (empty($customerId)) {
            return ['opening_balance' => $opening_balance, 'entries' => $entries];
        }

        // Your logic to calculate opening_balance and entries
        // For example:
        $opening_balance = $this->calculate_opening_balance($customerId, $from);
        $entries = $this->get_entries($customerId, $from, $to, $show_all);

        return ['opening_balance' => $opening_balance, 'entries' => $entries];
    }


    // Get single customer by ID (for print_statement only)
    public function get_customer_by_id($customerId)
    {
        if (empty($customerId)) {
            return null;
        }

        return $this->db
            ->where('CustomerId', $customerId)
            ->where('IsDeleted', 0)
            ->get('customer')
            ->row();
    }






}
?>
