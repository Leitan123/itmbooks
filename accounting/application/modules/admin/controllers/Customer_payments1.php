<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Customer_payments extends MY_Controller
{
    var $page = "customer_payments";

    function __construct()
    {
        parent::__construct();
        $this->load->model('customer_payments_model', 'model');
        $this->load->model('customer_model', 'customer');
        $this->controller = get_class($this);
    }

    public function index() {
        $records = $this->model->get_all();
        $d = [
            'page' => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }

    public function create() {
        $this->_form();
    }

    function _form($data = []) {
        // Load customers
        $data['customers'] = $this->customer->get_all();

        // Set form validation rules
        $this->form_validation->set_rules("form[CustomerId]", "Customer", "required");
        $this->form_validation->set_rules("form[PaidAmount]", "Paid Amount", "required|numeric");
        $this->form_validation->set_rules("form[PaymentDate]", "Payment Date", "required");
        $this->form_validation->set_rules("form[PaymentMethod]", "Payment Method", "required");

        if ($this->form_validation->run()) {
            $this->save_payment(); // Call save function
            return; // Prevent further execution
        }

        // Pass data to the view
        $this->view([
            'page' => "$this->page/form",
            'customers' => $data['customers'],
        ]);
    }

    public function save_payment() {
        $data = $this->input->post('form');

        if (empty($data['CustomerId'])) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>Please select a customer.</strong>'
            ]);
            redirect('admin/Customer_payments');
            return;
        }

        $customer = $this->db->where('CustomerId', $data['CustomerId'])->get('customer')->row();
        if (!$customer) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>Invalid customer.</strong>'
            ]);
            redirect('admin/Customer_payments');
            return;
        }

        if (empty($data['invoices']) || !is_array($data['invoices'])) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>No invoices selected for payment.</strong>'
            ]);
            redirect('admin/Customer_payments');
            return;
        }

        $totalPaid = 0;
        foreach ($data['invoices'] as $saleID => $paidAmount) {
            $paidAmount = floatval($paidAmount);
            if ($paidAmount <= 0) continue; // Skip 0 payments

            // Fetch total sale amount and previous paid for this SaleID
            $sale = $this->db->where('SaleID', $saleID)->get('sales')->row();
            if (!$sale) continue;

            $prevPaid = $this->db->select_sum('PaidAmount')
                ->where('SaleID', $saleID)
                ->where('IsDeleted', 0)
                ->get('customer_payments')
                ->row()
                ->PaidAmount;
            $prevPaid = $prevPaid ?: 0;

            $due = $sale->TotalSaleAmount - $prevPaid;
            if ($paidAmount > $due) $paidAmount = $due; // Prevent overpayment

            $paymentData = [
                'CustomerId' => $data['CustomerId'],
                'SaleID' => $saleID,
                'PaidAmount' => $paidAmount,
                'PaymentDue' => $due - $paidAmount,
                'CreditBalance' => $customer->CreditLimit - ($due - $paidAmount),
                'PaymentDate' => $data['PaymentDate'],
                'PaymentMethod' => $data['PaymentMethod'],
                'ChequeNumber' => isset($data['ChequeNumber']) ? $data['ChequeNumber'] : '',
                'ChequeDate' => isset($data['ChequeDate']) ? $data['ChequeDate'] : '',
                'BankName' => isset($data['BankName']) ? $data['BankName'] : '',
            ];

            $this->db->insert('customer_payments', $paymentData);
            $totalPaid += $paidAmount;
        }

        if ($totalPaid > 0) {
            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => "<strong>Payment of {$totalPaid} added successfully!</strong>"
            ]);
        } else {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => "<strong>No payment recorded.</strong>"
            ]);
        }

        redirect('admin/Customer_payments');
    }


    public function get_total_due_amount($customerId)
    {   

        // Fetch customer details
        $customer = $this->db->where('CustomerId', $customerId)->get('customer')->row();

        if (!$customer) {
            echo json_encode(['error' => 'Invalid Customer']);
            return;
        }

        // Get total paid amount for the customer
        $totalPaid = $this->db->select_sum('PaidAmount')
            ->where('CustomerId', $customerId)
            ->where('IsDeleted', 0) // Exclude deleted payments
            ->get('customer_payments')
            ->row()
            ->PaidAmount;

        // Get total sales amount for the customer
        $totalSales = $this->db->select_sum('TotalSaleAmount')
            ->where('CustomerId', $customerId)
            ->where('IsDeleted', 0) // Exclude deleted sales
            ->get('sales')
            ->row()
            ->TotalSaleAmount;

        // Handle null values with default 0
        $totalPaid = $totalPaid ? $totalPaid : 0;
        $totalSales = $totalSales ? $totalSales : 0;

        // Calculate payment due
        $paymentDue = $totalSales - $totalPaid;

        // Ensure PaymentDue is not negative
        $paymentDue = max(0, $paymentDue);

        // Calculate PaymentDue and CreditBalance
        $paymentDue = ($totalSales ?: 0) - ($totalPaid ?: 0);
        $creditBalance = $customer->CreditLimit - $paymentDue;

        // Return as JSON
        echo json_encode([
            'PaymentDue' => $paymentDue,
            'CreditBalance' => $creditBalance
        ]);
    }


    private function get_total_paid($customerId) {
        $totalPaid = $this->db->select_sum('PaidAmount')
            ->where('CustomerId', $customerId)
            ->where('IsDeleted', 0) // Exclude deleted payments
            ->get('customer_payments')
            ->row()
            ->PaidAmount;

        return $totalPaid ? $totalPaid : 0;
    }

    public function balance_sheet() {
        $records = $this->model->get_all_customers_with_due();
        $d = [
            'page' => "$this->page/balance_sheet",
            'records' => $records
        ];
        $this->view($d);
    }

    function edit($_id = 0) {
        if ($_id) {
            $obj = $this->model->get($_id);
            $d = [
                'page' => "$this->page/form",
                'obj' => $obj
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function delete($_id = 0) {
        if ($_id) {
            // Update the IsDeleted field instead of deleting the record
            $this->db->set('IsDeleted', 1)
                     ->where('PaymentId', $_id)
                     ->update('customer_payments');

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record Marked as Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Mark as Deleted or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

    public function print_receipt($paymentID) {
        // Load the model
        $this->load->model('customer_payments_model');

        // Fetch specific payment data
        $data['customer_payments'] = $this->customer_payments_model->get_payment_by_id($paymentID);

        // Check if the record exists
        if (!$data['customer_payments']) {
            show_404(); // Show 404 if not found
        }

        // Load the view for printing receipt
        $this->load->view('admin/pages/customer_payments/print_receipt', $data);
    }

    public function get_customer_invoices($customerId)
    {
        // Fetch all sales for this customer
        $query = $this->db->query("
            SELECT 
                s.SaleID, 
                s.SaleDate, 
                s.TotalSaleAmount,
                IFNULL(p.PaidAmount, 0) AS PaidAmount,
                (s.TotalSaleAmount - IFNULL(p.PaidAmount,0)) AS due
            FROM sales s
            LEFT JOIN (
                SELECT SaleID, SUM(PaidAmount) AS PaidAmount
                FROM customer_payments
                WHERE IsDeleted = 0
                GROUP BY SaleID
            ) p ON s.SaleID = p.SaleID
            WHERE s.CustomerId = ?
            HAVING due > 0
            ORDER BY s.SaleDate ASC
        ", [$customerId]);

        $invoices = $query->result();

        // Return as JSON
        echo json_encode($invoices);
    }


    public function get_customer_payments($customerId)
    {
        $payments = $this->db->where('CustomerId', $customerId)
                             ->where('IsDeleted', 0)
                             ->get('customer_payments')
                             ->result();
        echo json_encode($payments);
    }


    public function payment_history() {
        $data['customers'] = $this->customer->get_all();

        $this->view([
            'page' => 'customer_payments/customer_payment_history',
            'customers' => $data['customers']
        ]);
    }

    public function get_customer_invoices_all($customerId)
    {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $sql = "SELECT s.SaleID, s.SaleDate, s.TotalSaleAmount,
                       IFNULL(p.paid,0) AS PaidAmount,
                       (s.TotalSaleAmount - IFNULL(p.paid,0)) AS due
                FROM sales s
                LEFT JOIN (
                    SELECT SaleID, SUM(PaidAmount) paid
                    FROM customer_payments
                    GROUP BY SaleID
                ) p ON p.SaleID = s.SaleID
                WHERE s.CustomerId = ?";

        $params = [$customerId];

        if ($from) {
            $sql .= " AND s.SaleDate >= ?";
            $params[] = $from;
        }
        if ($to) {
            $sql .= " AND s.SaleDate <= ?";
            $params[] = $to;
        }

        $query = $this->db->query($sql, $params);
        echo json_encode($query->result());
    }


    // 1) Ledger page (UI)
    public function ledger()
    {
        $this->load->model('customer_model', 'customer');
        $data['customers'] = $this->customer->get_all();
        $this->view([
            'page' => 'customer_payments/customer_ledger',
            'customers' => $data['customers']
        ]);
    }

    // 2) AJAX: return ledger JSON
    public function get_customer_ledger()
    {
        $customerId = $this->input->post('customerId');
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        if (!$customerId) {
            echo json_encode(['error' => 'Customer ID required']);
            return;
        }

        $this->load->model('customer_payments_model', 'cpmodel');
        $ledger = $this->cpmodel->get_customer_ledger($customerId, $from, $to);

        echo json_encode($ledger);
    }


    // Show the SOA selection page
    public function statement()
    {
        // load customers for select
        $this->load->model('customer_model', 'customer');
        $data['customers'] = $this->customer->get_all(); // or your method
        $data['page'] = 'customer_payments/statement'; // view path
        $this->view($data);
    }

    // AJAX: return statement data as JSON
    public function get_statement_data()
    {
        $customerId = $this->input->get_post('customerId');
        $from = $this->input->get_post('from');
        $to = $this->input->get_post('to');
        $show_all = $this->input->get_post('show_all'); // expected '1'/'0' or true/false

        if (!$customerId) {
            echo json_encode(array('error' => 'Invalid CustomerId'));
            return;
        }

        $showAllFlag = ($show_all == '1' || $show_all === 1 || $show_all === 'true') ? true : false;

        $this->load->model('customer_payments_model', 'cpay');
        $result = $this->cpay->get_customer_statement($customerId, $from, $to, $showAllFlag);

        echo json_encode($result);
    }

    /**
     * Get customer statement (SOA)
     *
     * @param int $customerId
     * @param string|null $from    // 'Y-m-d' or any strtotime format
     * @param string|null $to      // 'Y-m-d' or any strtotime format
     * @param bool $show_all       // true => include all invoices, false => include only invoices with due > 0
     * @return array ['opening_balance' => float, 'entries' => array]
     */
    public function get_customer_statement($customerId, $from = null, $to = null, $show_all = true)
    {
        // Normalize dates (to Y-m-d) or null
        $from_date = $from ? date('Y-m-d', strtotime($from)) : null;
        $to_date   = $to   ? date('Y-m-d', strtotime($to))   : null;

        // ---------------------------
        // 1) Opening balances BEFORE $from_date
        // ---------------------------
        $totalSalesBefore = 0.0;
        $totalPaidBefore  = 0.0;

        if ($from_date) {
            // Total invoiced before from_date
            $this->db->select_sum('TotalSaleAmount', 'sum_sales_before');
            $this->db->from('sales');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(SaleDate) <', $from_date);
            $row = $this->db->get()->row();
            $totalSalesBefore = $row ? (float)$row->sum_sales_before : 0.0;

            // Total payments before from_date
            $this->db->select_sum('PaidAmount', 'sum_paid_before');
            $this->db->from('customer_payments');
            $this->db->where('CustomerId', $customerId);
            $this->db->where('IsDeleted', 0);
            $this->db->where('DATE(PaymentDate) <', $from_date);
            $row2 = $this->db->get()->row();
            $totalPaidBefore = $row2 ? (float)$row2->sum_paid_before : 0.0;
        }

        $opening_balance = round($totalSalesBefore - $totalPaidBefore, 2);

        // ---------------------------
        // 2) Fetch invoices (sales) in range (or all if no range)
        // ---------------------------
        $this->db->select("SaleID, SaleDate, TotalSaleAmount");
        $this->db->from('sales');
        $this->db->where('CustomerId', $customerId);
        $this->db->where('IsDeleted', 0);
        if ($from_date) $this->db->where('DATE(SaleDate) >=', $from_date);
        if ($to_date)   $this->db->where('DATE(SaleDate) <=', $to_date);
        $this->db->order_by('SaleDate', 'ASC');
        $invoices = $this->db->get()->result_array();

        $invoice_entries = array();
        foreach ($invoices as $inv) {
            $saleId = $inv['SaleID'];

            // Sum payments applied to this SaleID (respect IsDeleted and to_date)
            $this->db->select_sum('PaidAmount', 'paid');
            $this->db->from('customer_payments');
            $this->db->where('SaleID', $saleId);
            $this->db->where('IsDeleted', 0);
            if ($to_date) {
                $this->db->where('DATE(PaymentDate) <=', $to_date);
            }
            $paid_row = $this->db->get()->row();
            $paid_for_invoice = $paid_row ? (float)$paid_row->paid : 0.0;

            $due = (float)$inv['TotalSaleAmount'] - $paid_for_invoice;
            if ($due < 0) $due = 0.0;

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
                'paid' => round($paid_for_invoice, 2),
                'due' => round($due, 2)
            );
        }

        // ---------------------------
        // 3) Fetch payments in date range (payments are credits)
        // ---------------------------
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

        // ---------------------------
        // 4) Merge invoice_entries and payment_entries and sort by date
        // ---------------------------
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

        // ---------------------------
        // 5) Compute running balance
        // ---------------------------
        $running = (float)$opening_balance;
        for ($i = 0; $i < count($entries); $i++) {
            $e = &$entries[$i];

            $debitVal = isset($e['debit']) ? (float)$e['debit'] : 0.0;
            $creditVal = isset($e['credit']) ? (float)$e['credit'] : 0.0;

            $running = $running + $debitVal - $creditVal;
            $e['balance'] = round($running, 2);

            // normalize sums
            $e['debit']  = round($debitVal, 2);
            $e['credit'] = round($creditVal, 2);
            if (isset($e['paid'])) $e['paid'] = round($e['paid'], 2);
            if (isset($e['due']))  $e['due']  = round($e['due'], 2);
        }
        unset($e);

        // ---------------------------
        // 6) Return result
        // ---------------------------
        return array(
            'opening_balance' => round($opening_balance, 2),
            'entries' => $entries
        );
    }



    public function print_statement()
    {
        $customerId = $this->input->get('customerId');
        $from       = $this->input->get('from');
        $to         = $this->input->get('to');
        $show_all   = $this->input->get('show_all');

        if(!$customerId) { show_404(); }

        $this->load->model('Customer_payments_model');

        $data['customer'] = $this->Customer_payments_model->get_customer($customerId);

        $data['records']  = $this->Customer_payments_model->get_statement($customerId, $from, $to, $show_all);

        $this->load->view('pages/customer_payments/print_statement', $data);
    }


    public function invoice_aging() {
        $customerId = $this->input->get('customerId'); // selected customer
        $from = $this->input->get('from');
        $to = $this->input->get('to');

        // Use the alias 'customer' you loaded
        $customers = $this->customer->get_all(); // fetch all customers

        $customer = null;
        if($customerId) {
            $customer = $this->customer->get_by_id($customerId); // fetch selected customer
        }

        // Get invoices with filters
        $records = $this->model->get_invoices($customerId, $from, $to); // implement this in customer_payments_model

        $data = [
            'customers' => $customers,
            'customer' => $customer,
            'records' => $records,
            'from' => $from,
            'to' => $to,
        ];

        $this->load->view('pages/customer_payments/invoice_aging', $data);
    }



    public function receivables_summary()
    {
        $data = [];
        $this->load->model('Customer_payments_model', 'model');

        $data['summary'] = $this->model->get_receivables_summary();

        $this->load->view('includes/header_start');
        $this->load->view('includes/header_end');
        $this->load->view('pages/customer_payments/receivables_summary', $data);
        $this->load->view('includes/footer_start');
        $this->load->view('includes/footer_end');
    }


    public function daily_cash_collection()
    {
        $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

        $this->load->model('Customer_payments_model');
        $data['date'] = $date;
        $data['summary'] = $this->Customer_payments_model->get_daily_cash_collection($date);

        $this->load->view('pages/customer_payments/daily_cash_collection', $data);
    }


    public function cheque_register()
    {
        $this->load->model('Customer_payments_model');

        // Optional filter by date range
        $from = $this->input->get('from') ? $this->input->get('from') : '';
        $to = $this->input->get('to') ? $this->input->get('to') : '';

        $data['from'] = $from;
        $data['to'] = $to;
        $data['cheques'] = $this->Customer_payments_model->get_cheque_register($from, $to);

        $this->load->view('pages/customer_payments/cheque_register', $data);
    }




}

?>
