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

    private function _form($data = [])
    {
        $data['customers'] = $this->customer->get_all();

        $this->load->model('Banks_model', 'banks_model');
        $data['banks'] = $this->banks_model->get_all();

        $this->form_validation->set_rules(
            "form[CustomerId]",
            "Customer",
            "required"
        );

        $this->form_validation->set_rules(
            "form[PaidAmount]",
            "Paid Amount",
            "required|numeric"
        );

        $this->form_validation->set_rules(
            "form[PaymentDate]",
            "Payment Date",
            "required"
        );

        $this->form_validation->set_rules(
            "form[PaymentMethod]",
            "Payment Method",
            "required"
        );

        if ($this->form_validation->run())
        {
            $this->save_payment();
            return;
        }

        $this->view([
            'page'      => "$this->page/form",
            'customers' => $data['customers'],
            'banks'     => $data['banks']
        ]);
    }

    public function save_payment() {
        
        $data = $this->input->post('form');

        if (empty($data['CustomerId'])) {
            $this->session->set_flashdata('notification', ["alert" => "danger", "text" => '<strong>Please select a customer.</strong>']);
            redirect('admin/Customer_payments');
            return;
        }

        $customer = $this->db->where('CustomerId', $data['CustomerId'])->where('IsDeleted',0)->get('customer')->row();
        if (!$customer) {
            $this->session->set_flashdata('notification', ["alert" => "danger", "text" => '<strong>Invalid customer.</strong>']);
            redirect('admin/Customer_payments');
            return;
        }

        if (empty($data['invoices']) || !is_array($data['invoices'])) {
            $this->session->set_flashdata('notification', ["alert" => "danger", "text" => '<strong>No invoices selected for payment.</strong>']);
            redirect('admin/Customer_payments');
            return;
        }

        $totalPaid = 0;
        $isRenewalPayment = false;
        $renewalAmount = 0;
        
        foreach ($data['invoices'] as $saleID => $paidAmount) {
            $paidAmount = floatval($paidAmount);
            if ($paidAmount <= 0) continue;

            $sale = $this->db->where('SaleID', $saleID)->where('IsDeleted',0)->get('sales')->row();
            if (!$sale) continue;

            // Check if this is a subscription renewal (check item/service type)
            $saleItems = $this->db
                ->select('items.ItemName, items.ItemType')
                ->from('sale_items')
                ->join('items', 'items.ItemID = sale_items.ItemID')
                ->where('sale_items.SaleID', $saleID)
                ->get()
                ->result();
                
            foreach($saleItems as $item) {
                if(stripos($item->ItemName, 'Subscription') !== false || 
                   stripos($item->ItemName, 'Renewal') !== false ||
                   stripos($item->ItemName, 'Service') !== false) {
                    $isRenewalPayment = true;
                    $renewalAmount += $paidAmount;
                }
            }

            $prevPaid = $this->db->select_sum('PaidAmount')->where('SaleID', $saleID)->where('IsDeleted',0)->get('customer_payments')->row()->PaidAmount;
            $prevPaid = $prevPaid ?: 0;

            $due = $sale->TotalSaleAmount - $prevPaid;
            if ($paidAmount > $due) $paidAmount = $due;

            $user = $this->session->userdata('user');
            $userId = (is_object($user) && isset($user->UserId)) ? $user->UserId : NULL;

            $paymentData = [
                'CustomerId'    => $data['CustomerId'],
                'SaleID'        => $saleID,
                'UserId'        => $userId,
                'BankID'        => !empty($data['BankID']) ? $data['BankID'] : NULL,
                'PaidAmount'    => $paidAmount,
                'PaymentDue'    => $due - $paidAmount,
                'PaymentDate'   => $data['PaymentDate'],
                'PaymentMethod' => $data['PaymentMethod'],
                'ChequeNumber'  => isset($data['ChequeNumber']) ? $data['ChequeNumber'] : '',
                'ChequeDate'    => isset($data['ChequeDate']) ? $data['ChequeDate'] : '',
                'CreatedBy'     => $userId,
                'CreatedDate'   => date('Y-m-d H:i:s')
            ];

            $this->db->insert('customer_payments', $paymentData);
            $paymentID = $this->db->insert_id();

            // Check if this payment fully settles the invoice
            if (($due - $paidAmount) <= 0) {
                $this->db->where('CustomerId', $data['CustomerId'])->update('customer', [
                    'SubscriptionStatus' => 'Paid'
                ]);
            }

            // ======================================
            // AUTO SAVE RECEIVED CHEQUE
            // ======================================
            if($data['PaymentMethod'] == 'Cheque') {
                $bankId = !empty($data['BankID']) ? $data['BankID'] : 0;
                
                $chequeData = [
                    'CustomerID'   => $data['CustomerId'],
                    'BankID'       => $bankId,
                    'ChequeNo'     => $data['ChequeNumber'],
                    'ChequeDate'   => $data['ChequeDate'],
                    'DepositDate'  => date('Y-m-d'),
                    'Amount'       => $paidAmount,
                    'Status'       => 'Pending',
                    'Notes'        => 'Customer Payment Cheque'
                ];
                $this->db->insert('received_cheques', $chequeData);
            }

            if ($data['PaymentMethod'] != 'Credit Note') {
                // ======================================
                // AUTO ACCOUNTING ENTRY - CUSTOMER PAYMENT
                // ======================================
                $journal = [
                    'JournalDate' => $data['PaymentDate'],
                    'ReferenceNo' => 'CP-'.$paymentID,
                    'Description' => 'Customer Payment Received'
                ];
                $this->db->insert('journal_entries', $journal);
                $journalID = $this->db->insert_id();

                // Determine Debit Account
                if($data['PaymentMethod'] == 'Cash') {
                    $debitAccount = 1; // Cash
                } elseif(($data['PaymentMethod'] == 'Bank Transfer' || $data['PaymentMethod'] == 'Cheque') && !empty($data['BankID'])) {
                    $bank_row = $this->db->select('AccountID')->where('BankID', $data['BankID'])->get('banks')->row();
                    $debitAccount = $bank_row ? $bank_row->AccountID : 2;
                } else {
                    $debitAccount = 2;
                }

                // Debit Cash / Bank
                $this->db->insert('journal_entry_details', [
                    'JournalID'    => $journalID,
                    'AccountID'    => $debitAccount,
                    'DebitAmount'  => $paidAmount,
                    'CreditAmount' => 0
                ]);

                // Increase Cash
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$paidAmount, FALSE);
                $this->db->where('AccountID', 1);
                $this->db->update('chart_of_accounts');

                // Credit Accounts Receivable
                $this->db->insert('journal_entry_details', [
                    'JournalID' => $journalID,
                    'AccountID' => 3,
                    'DebitAmount' => 0,
                    'CreditAmount' => $paidAmount
                ]);

                // Reduce Receivable
                $this->db->set('CurrentBalance', 'CurrentBalance - '.$paidAmount, FALSE);
                $this->db->where('AccountID', 3);
                $this->db->update('chart_of_accounts');
            }

            $totalPaid += $paidAmount;
        }
        
        // ======================================
        // UPDATE SUBSCRIPTION EXPIRY IF RENEWAL PAYMENT
        // ======================================
        if($isRenewalPayment && $renewalAmount > 0) {
            $this->update_subscription_expiry($data['CustomerId'], $renewalAmount);
        }

        $this->session->set_flashdata('notification', [
            "alert" => $totalPaid > 0 ? "success" : "danger",
            "text"  => $totalPaid > 0 ? "<strong>Payment of " . number_format($totalPaid, 2) . " added successfully!</strong>" . ($isRenewalPayment ? "<br>Subscription has been renewed!" : "") : "<strong>No payment recorded.</strong>"
        ]);

        redirect('admin/Customer_payments');
    }
    
    // ======================================
    // UPDATE SUBSCRIPTION EXPIRY DATE
    // ======================================
    private function update_subscription_expiry($customerId, $amount) {
        // Get customer current expiry
        $customer = $this->db->select('ServiceExpiry, SubscriptionStatus')
            ->where('CustomerId', $customerId)
            ->get('customer')
            ->row();
            
        if($customer) {
            $currentExpiry = $customer->ServiceExpiry;
            $today = date('Y-m-d');
            
            // Calculate new expiry date
            if($currentExpiry && $currentExpiry != '0000-00-00' && $currentExpiry != '1970-01-01') {
                // If expiry date exists, add 1 year from current expiry
                $newExpiry = date('Y-m-d', strtotime($currentExpiry . ' + 1 year'));
            } else {
                // If no expiry date, add 1 year from today
                $newExpiry = date('Y-m-d', strtotime($today . ' + 1 year'));
            }
            
            // Update customer subscription
            $this->db->where('CustomerId', $customerId);
            $this->db->update('customer', [
                'ServiceExpiry' => $newExpiry,
                'SubscriptionStatus' => 'Paid',
                'LastRenewalDate' => date('Y-m-d'),
                'RenewalNotified' => 0
            ]);
            
            // Log renewal in subscription_renewals table
            $this->db->insert('subscription_renewals', [
                'CustomerId' => $customerId,
                'OldExpiryDate' => ($currentExpiry && $currentExpiry != '0000-00-00') ? $currentExpiry : NULL,
                'NewExpiryDate' => $newExpiry,
                'RenewalDate' => date('Y-m-d'),
                'AmountPaid' => $amount,
                'PaymentMethod' => $this->input->post('form[PaymentMethod]'),
                'CreatedDate' => date('Y-m-d H:i:s')
            ]);
        }
    }

    // ======================================
    // GET INVOICES FOR RENEWAL (including subscription items)
    // ======================================
    public function get_customer_invoices($customerId) {
        $query = $this->db->query("
            SELECT 
                s.SaleID,
                s.RefNo,
                s.PONo,
                s.SaleDate,
                s.TotalSaleAmount,
                IFNULL(p.PaidAmount,0) AS PaidAmount,
                (s.TotalSaleAmount - IFNULL(p.PaidAmount,0)) AS due,
                (SELECT GROUP_CONCAT(items.ItemName SEPARATOR ', ') 
                 FROM sale_items si 
                 JOIN items ON items.ItemID = si.ItemID 
                 WHERE si.SaleID = s.SaleID) AS items
            FROM sales s
            LEFT JOIN (
                SELECT SaleID, SUM(PaidAmount) AS PaidAmount
                FROM customer_payments
                WHERE IsDeleted = 0
                GROUP BY SaleID
            ) p ON s.SaleID = p.SaleID
            WHERE s.CustomerId = ?
              AND s.IsDeleted = 0
            HAVING due > 0
            ORDER BY s.SaleDate ASC
        ", [$customerId]);

        echo json_encode($query->result());
    }

    public function get_total_due_amount($customerId) {
        $customer = $this->db->where('CustomerId', $customerId)->where('IsDeleted',0)->get('customer')->row();
        if (!$customer) { echo json_encode(['error'=>'Invalid Customer']); return; }

        $totalPaid = $this->db->select_sum('PaidAmount')->where('CustomerId',$customerId)->where('IsDeleted',0)->get('customer_payments')->row()->PaidAmount ?: 0;
        $totalSales = $this->db->select_sum('TotalSaleAmount')->where('CustomerId',$customerId)->where('IsDeleted',0)->get('sales')->row()->TotalSaleAmount ?: 0;

        $paymentDue = max(0, $totalSales - $totalPaid);
        $creditBalance = $customer->CreditLimit - $paymentDue;

        echo json_encode(['PaymentDue'=>$paymentDue,'CreditBalance'=>$creditBalance]);
    }

    private function get_total_paid($customerId) {
        $totalPaid = $this->db->select_sum('PaidAmount')->where('CustomerId',$customerId)->where('IsDeleted',0)->get('customer_payments')->row()->PaidAmount;
        return $totalPaid ?: 0;
    }

    public function balance_sheet() {
        $records = $this->model->get_all_customers_with_due();
        $this->view(['page'=>"$this->page/balance_sheet",'records'=>$records]);
    }

    public function edit($_id = 0) {
        if ($_id) {
            $obj = $this->model->get($_id);
            $this->_form(['obj'=>$obj], $_id);
        } else $this->index();
    }

    public function delete($_id = 0) {
        if ($_id) {
            $this->db->set('IsDeleted',1)->where('PaymentId',$_id)->update('customer_payments');
            $this->session->set_flashdata('notification', [
                "alert" => $this->db->affected_rows() ? "success":"danger",
                "text" => $this->db->affected_rows() ? '<strong>Record Marked as Deleted Successfully.</strong>':'<strong>Failed or Not Found.</strong>'
            ]);
        }
        redirect(base_url("admin/$this->controller"));
    }

    public function print_receipt($paymentID) {
        $data['customer_payments'] = $this->model->get_payment_by_id($paymentID);
        if (!$data['customer_payments']) show_404();
        $this->load->view('admin/pages/customer_payments/print_receipt', $data);
    }

    public function get_customer_payments($customerId) {
        echo json_encode($this->db->where('CustomerId',$customerId)->where('IsDeleted',0)->get('customer_payments')->result());
    }

    public function payment_history() {
        $this->view(['page'=>'customer_payments/customer_payment_history','customers'=>$this->customer->get_all()]);
    }

    public function get_customer_invoices_all($customerId) {
        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $sql = "SELECT 
                    s.SaleID,
                    s.RefNo,
                    s.SaleDate,
                    s.TotalSaleAmount,
                    IFNULL(p.paid, 0) AS PaidAmount,
                    (s.TotalSaleAmount - IFNULL(p.paid, 0)) AS due
                FROM sales s
                LEFT JOIN (
                    SELECT SaleID, SUM(PaidAmount) AS paid
                    FROM customer_payments
                    WHERE IsDeleted = 0
                    GROUP BY SaleID
                ) p ON p.SaleID = s.SaleID
                WHERE s.CustomerId = ?
                  AND s.IsDeleted = 0";

        $params = [$customerId];

        if ($from) {
            $sql .= " AND s.SaleDate >= ?";
            $params[] = $from;
        }

        if ($to) {
            $sql .= " AND s.SaleDate <= ?";
            $params[] = $to;
        }

        echo json_encode($this->db->query($sql, $params)->result());
    }

    public function ledger() {
        $this->view(['page'=>'customer_payments/customer_ledger','customers'=>$this->customer->get_all()]);
    }

    public function get_customer_ledger() {
        $customerId = $this->input->post('customerId');
        if (!$customerId){ echo json_encode(['error'=>'Customer ID required']); return; }
        echo json_encode($this->model->get_customer_ledger($customerId));
    }

    public function statement() {
        $this->view(['page'=>'customer_payments/statement','customers'=>$this->customer->get_all()]);
    }

    public function get_statement_data() {
        $customerId = $this->input->get_post('customerId');
        $from = $this->input->get_post('from');
        $to = $this->input->get_post('to');
        $show_all = $this->input->get_post('show_all')==='1'?true:false;
        if (!$customerId){echo json_encode(['error'=>'Invalid CustomerId']);return;}
        echo json_encode($this->model->get_customer_statement($customerId,$from,$to,$show_all));
    }

    public function print_statement()
    {
        $customerId = $this->input->get('customerId');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $show_all = $this->input->get('show_all');

        if (empty($customerId)) {
            show_error('Customer not selected');
        }

        $customer = $this->model->get_customer_by_id($customerId);
        $statement = $this->model->get_customer_statement($customerId, $from, $to, $show_all);

        $opening_balance = isset($statement['opening_balance']) ? $statement['opening_balance'] : 0;
        $entries = isset($statement['entries']) ? $statement['entries'] : [];

        $data = [
            'customer' => $customer,
            'opening_balance' => $opening_balance,
            'entries' => $entries
        ];

        $this->load->view('pages/customer_payments/print_statement', $data);
    }

    public function invoice_aging() {
        $customerId = $this->input->get('customerId'); 
        $from = $this->input->get('from'); 
        $to = $this->input->get('to');
        $customer = $customerId?$this->customer->get_by_id($customerId):null;
        $records = $this->model->get_invoices($customerId,$from,$to);
        $this->load->view('pages/customer_payments/invoice_aging',[
            'customers'=>$this->customer->get_all(),
            'customer'=>$customer,
            'records'=>$records,
            'from'=>$from,
            'to'=>$to
        ]);
    }

    public function receivables_summary() {
        $data['summary']=$this->model->get_receivables_summary();
        $this->load->view('includes/header_start'); 
        $this->load->view('includes/header_end');
        $this->load->view('pages/customer_payments/receivables_summary',$data);
        $this->load->view('includes/footer_start'); 
        $this->load->view('includes/footer_end');
    }

    public function daily_cash_collection() {
        $date = $this->input->get('date') ?: date('Y-m-d');
        $data['date']=$date;
        $data['summary']=$this->model->get_daily_cash_collection($date);
        $this->load->view('pages/customer_payments/daily_cash_collection',$data);
    }

    public function cheque_register() {
        $from = $this->input->get('from') ?: '';
        $to = $this->input->get('to') ?: '';
        $data['from']=$from; 
        $data['to']=$to;
        $data['cheques']=$this->model->get_cheque_register($from,$to);
        $this->load->view('pages/customer_payments/cheque_register',$data);
    }

    public function rebuild_customer_payment_journals()
    {
        $payments = $this->db->get('customer_payments')->result();

        foreach($payments as $payment)
        {
            $this->db->insert('journal_entries', [
                'JournalDate' => $payment->PaymentDate,
                'ReferenceNo' => 'CP-'.$payment->PaymentId,
                'Description' => 'Customer Payment Received'
            ]);

            $journalID = $this->db->insert_id();

            // Determine account
            if($payment->PaymentMethod == 'Cash')
            {
                $debitAccount = 1;
            }
            else
            {
                $debitAccount = 2;
                if(!empty($payment->BankID)) {
                    $bank_row = $this->db->select('AccountID')->where('BankID', $payment->BankID)->get('banks')->row();
                    if($bank_row) {
                        $debitAccount = $bank_row->AccountID;
                    }
                }
            }

            // Debit Cash/Bank
            $this->db->insert('journal_entry_details', [
                'JournalID' => $journalID,
                'AccountID' => $debitAccount,
                'DebitAmount' => $payment->PaidAmount,
                'CreditAmount' => 0
            ]);

            // Credit Accounts Receivable
            $this->db->insert('journal_entry_details', [
                'JournalID' => $journalID,
                'AccountID' => 3,
                'DebitAmount' => 0,
                'CreditAmount' => $payment->PaidAmount
            ]);
        }

        echo "Customer Payments Rebuilt Successfully";
    }

    public function rebuild_expense_journals()
    {
        $expenses = $this->db->get('expenses')->result();

        foreach($expenses as $expense)
        {
            $this->db->insert('journal_entries', [
                'JournalDate' => $expense->ExpenseDate,
                'ReferenceNo' => 'EXP-'.$expense->ExpenseID,
                'Description' => $expense->Description
            ]);

            $journalID = $this->db->insert_id();

            $cashAccount = ($expense->PaymentMethod == 'Cash') ? 1 : 2;

            // Debit Expense
            $this->db->insert('journal_entry_details', [
                'JournalID' => $journalID,
                'AccountID' => $expense->AccountID,
                'DebitAmount' => $expense->Amount,
                'CreditAmount' => 0
            ]);

            // Credit Cash/Bank
            $this->db->insert('journal_entry_details', [
                'JournalID' => $journalID,
                'AccountID' => $cashAccount,
                'DebitAmount' => 0,
                'CreditAmount' => $expense->Amount
            ]);
        }

        echo "Expenses Rebuilt Successfully";
    }
}