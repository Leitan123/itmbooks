<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Sales_returns extends MY_Controller {

    var $page = "sales_returns";

    public function __construct()
    {
        parent::__construct();
        $this->controller = get_class($this);
    }

    public function index()
    {
        // Fetch all sales returns with applied credit note amounts
        $records = $this->db->select('sr.*, c.CompanyName, c.CustomerName, COALESCE(applied.amount, 0) AS AppliedAmount')
            ->from('sales_returns sr')
            ->join('customer c', 'c.CustomerId = sr.CustomerId', 'left')
            ->join('(SELECT SalesReturnID, SUM(PaidAmount) AS amount FROM customer_payments WHERE IsDeleted = 0 GROUP BY SalesReturnID) applied', 'applied.SalesReturnID = sr.SalesReturnID', 'left')
            ->where('sr.IsDeleted', 0)
            ->order_by('sr.SalesReturnID', 'DESC')
            ->get()
            ->result();

        $d = [
            'page' => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }

    public function create()
    {
        $data['customers'] = $this->db
            ->get_where('customer', ['IsDeleted' => 0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items', ['IsDeleted' => 0])
            ->result();

        $d = [
            'page' => "$this->page/form",
            'customers' => $data['customers'],
            'items' => $data['items'],
            'obj' => null
        ];
        $this->view($d);
    }

    public function save()
    {
        $form = $this->input->post('form');
        $returnID = $this->input->post('SalesReturnID');
        $totalReturnAmount = 0;

        // Calculate total amount from items
        if (!empty($form['items'])) {
            foreach ($form['items'] as $item) {
                if (empty($item['ItemID'])) continue;
                $qty = isset($item['Quantity']) ? floatval($item['Quantity']) : 0;
                $price = isset($item['RefundPrice']) ? floatval($item['RefundPrice']) : 0;
                $totalReturnAmount += ($qty * $price);
            }
        }

        $returnData = [
            'CustomerId' => $form['CustomerId'],
            'ReturnDate' => $form['ReturnDate'],
            'TotalReturnAmount' => $totalReturnAmount,
            'ReferenceNo' => !empty($form['ReferenceNo']) ? $form['ReferenceNo'] : NULL,
            'Notes' => !empty($form['Notes']) ? $form['Notes'] : NULL,
            'UpdatedAt' => date('Y-m-d H:i:s')
        ];

        // Start db transaction
        $this->db->trans_start();

        if ($returnID) {
            // Revert old journal entry and chart of accounts balances if editing
            $this->revert_accounting($returnID);

            // Update sales return
            $this->db->where('SalesReturnID', $returnID)->update('sales_returns', $returnData);

            // Delete old items
            $this->db->where('SalesReturnID', $returnID)->delete('sales_return_items');

            $message = 'updated';
        } else {
            $returnData['CreatedAt'] = date('Y-m-d H:i:s');
            $returnData['IsDeleted'] = 0;

            $this->db->insert('sales_returns', $returnData);
            $returnID = $this->db->insert_id();

            $message = 'added';
        }

        // Save items
        if (!empty($form['items'])) {
            foreach ($form['items'] as $item) {
                if (empty($item['ItemID'])) continue;
                $qty = isset($item['Quantity']) ? floatval($item['Quantity']) : 0;
                $price = isset($item['RefundPrice']) ? floatval($item['RefundPrice']) : 0;
                $totalAmount = $qty * $price;

                $itemData = [
                    'SalesReturnID' => $returnID,
                    'ItemID' => $item['ItemID'],
                    'Quantity' => $qty,
                    'QtyType' => isset($item['QtyType']) ? $item['QtyType'] : 'Pcs',
                    'RefundPrice' => $price,
                    'TotalAmount' => $totalAmount,
                    'IsDamaged' => isset($item['IsDamaged']) ? 1 : 0
                ];
                $this->db->insert('sales_return_items', $itemData);
            }
        }

        // Create accounting entry
        $this->apply_accounting($returnID, $form['CustomerId'], $form['ReturnDate'], $totalReturnAmount);

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Sales Return {$message} successfully!</strong>"
        ]);

        redirect('admin/Sales_returns');
    }

    public function edit($returnID)
    {
        $obj = $this->db->get_where('sales_returns', ['SalesReturnID' => $returnID])->row();
        if (!$obj) {
            show_404();
        }

        $data['obj'] = $obj;
        $data['return_items'] = $this->db->get_where('sales_return_items', ['SalesReturnID' => $returnID])->result();
        $data['customers'] = $this->db->get_where('customer', ['IsDeleted' => 0])->result();
        $data['items'] = $this->db->get_where('items', ['IsDeleted' => 0])->result();

        $d = [
            'page' => "$this->page/form",
            'customers' => $data['customers'],
            'items' => $data['items'],
            'obj' => $obj,
            'return_items' => $data['return_items']
        ];
        $this->view($d);
    }

    public function delete($returnID)
    {
        $this->db->trans_start();

        // Revert accounting entries
        $this->revert_accounting($returnID);

        // Mark deleted
        $this->db->where('SalesReturnID', $returnID)->update('sales_returns', ['IsDeleted' => 1]);

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Sales Return deleted successfully!</strong>"
        ]);

        redirect('admin/Sales_returns');
    }

    private function apply_accounting($returnID, $customerId, $returnDate, $totalAmount)
    {
        if ($totalAmount <= 0) return;

        // Auto accounting entry - sales return (Credit Note)
        $journal = [
            'JournalDate' => $returnDate,
            'ReferenceNo' => 'SR-'.$returnID,
            'Description' => 'Sales Return (Credit Note)'
        ];
        $this->db->insert('journal_entries', $journal);
        $journalID = $this->db->insert_id();

        // Debit Sales Income: AccountID = 7 (reduce income)
        $this->db->insert('journal_entry_details', [
            'JournalID'    => $journalID,
            'AccountID'    => 7,
            'DebitAmount'  => $totalAmount,
            'CreditAmount' => 0
        ]);

        // Decrease Sales Income balance
        $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalAmount, FALSE);
        $this->db->where('AccountID', 7);
        $this->db->update('chart_of_accounts');

        // Credit Accounts Receivable: AccountID = 3 (reduce customer's receivable balance)
        $this->db->insert('journal_entry_details', [
            'JournalID'    => $journalID,
            'AccountID'    => 3,
            'DebitAmount'  => 0,
            'CreditAmount' => $totalAmount
        ]);

        // Reduce Accounts Receivable balance (meaning customer owes us less)
        $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalAmount, FALSE);
        $this->db->where('AccountID', 3);
        $this->db->update('chart_of_accounts');
    }

    private function revert_accounting($returnID)
    {
        // Find journal entry associated with SR-SalesReturnID
        $journal = $this->db->where('ReferenceNo', 'SR-'.$returnID)->get('journal_entries')->row();
        if ($journal) {
            // Find Debit detail (Account 7)
            $debitDetail = $this->db->where('JournalID', $journal->JournalID)->where('AccountID', 7)->get('journal_entry_details')->row();
            if ($debitDetail) {
                // Revert Sales Income balance: increase it back
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$debitDetail->DebitAmount, FALSE);
                $this->db->where('AccountID', 7);
                $this->db->update('chart_of_accounts');
            }

            // Find Credit detail (Account 3)
            $creditDetail = $this->db->where('JournalID', $journal->JournalID)->where('AccountID', 3)->get('journal_entry_details')->row();
            if ($creditDetail) {
                // Revert Accounts Receivable balance: increase it back (more outstanding)
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$creditDetail->CreditAmount, FALSE);
                $this->db->where('AccountID', 3);
                $this->db->update('chart_of_accounts');
            }

            // Delete journal entry and details
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entry_details');
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entries');
        }
    }

    public function apply($returnID)
    {
        $return = $this->db->select('sr.*, c.CompanyName, c.CustomerName')
            ->from('sales_returns sr')
            ->join('customer c', 'c.CustomerId = sr.CustomerId', 'left')
            ->where('sr.SalesReturnID', $returnID)
            ->where('sr.IsDeleted', 0)
            ->get()
            ->row();

        if (!$return) {
            show_404();
        }

        // Get total applied amount (excluding previous allocations to see baseline unused credit)
        $applied = $this->db->select_sum('PaidAmount')
            ->where('SalesReturnID', $returnID)
            ->where('IsDeleted', 0)
            ->get('customer_payments')
            ->row()
            ->PaidAmount;
        $applied = $applied ?: 0;

        $unusedCredit = $return->TotalReturnAmount - $applied;

        // Get customer's unpaid invoices
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
                  AND s.IsDeleted = 0
                  AND (s.TotalSaleAmount - IFNULL(p.paid, 0)) > 0";
        $invoices = $this->db->query($sql, [$return->CustomerId])->result();

        // Get currently applied invoices under this return
        $allocations = $this->db->where('SalesReturnID', $returnID)
            ->where('IsDeleted', 0)
            ->get('customer_payments')
            ->result();

        $d = [
            'page' => "$this->page/apply",
            'return' => $return,
            'unusedCredit' => $unusedCredit,
            'invoices' => $invoices,
            'allocations' => $allocations
        ];
        $this->view($d);
    }

    public function apply_save()
    {
        $returnID = $this->input->post('SalesReturnID');
        $amounts = $this->input->post('amounts'); // array [SaleID => Amount]

        $return = $this->db->get_where('sales_returns', ['SalesReturnID' => $returnID, 'IsDeleted' => 0])->row();
        if (!$return) {
            show_404();
        }

        $this->db->trans_start();

        // Delete previous allocations for this SalesReturnID
        $this->db->where('SalesReturnID', $returnID)->delete('customer_payments');

        $totalApplied = 0;
        if (!empty($amounts)) {
            foreach ($amounts as $saleID => $amt) {
                $amt = floatval($amt);
                if ($amt <= 0) continue;

                $sale = $this->db->get_where('sales', ['SaleID' => $saleID, 'IsDeleted' => 0])->row();
                if (!$sale) continue;

                // Calculate due for this invoice (since we deleted previous allocations for this return, prevPaid does not include them)
                $prevPaid = $this->db->select_sum('PaidAmount')
                    ->where('SaleID', $saleID)
                    ->where('IsDeleted', 0)
                    ->get('customer_payments')
                    ->row()
                    ->PaidAmount;
                $prevPaid = $prevPaid ?: 0;
                $due = $sale->TotalSaleAmount - $prevPaid;

                if ($amt > $due) {
                    $amt = $due;
                }

                $totalApplied += $amt;

                $user = $this->session->userdata('user');
                $userId = (is_object($user) && isset($user->UserId)) ? $user->UserId : NULL;

                $paymentData = [
                    'CustomerId'    => $return->CustomerId,
                    'SaleID'        => $saleID,
                    'UserId'        => $userId,
                    'PaidAmount'    => $amt,
                    'PaymentDue'    => $due - $amt,
                    'PaymentDate'   => date('Y-m-d'),
                    'PaymentMethod' => 'Credit Note',
                    'SalesReturnID' => $returnID,
                    'CreatedBy'     => $userId,
                    'CreatedDate'   => date('Y-m-d H:i:s')
                ];
                $this->db->insert('customer_payments', $paymentData);
            }
        }

        // Validate that totalApplied does not exceed return value
        if ($totalApplied > $return->TotalReturnAmount) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text"  => "<strong>Failed:</strong> Allocated credit amount (" . number_format($totalApplied, 2) . ") exceeds total return value (" . number_format($return->TotalReturnAmount, 2) . ")."
            ]);
            redirect('admin/Sales_returns/apply/' . $returnID);
            return;
        }

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Credit Note applied to invoices successfully!</strong>"
        ]);
        redirect('admin/Sales_returns');
    }
}
