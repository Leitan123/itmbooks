<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Purchase_returns extends MY_Controller {

    var $page = "purchase_returns";

    public function __construct()
    {
        parent::__construct();
        $this->controller = get_class($this);
    }

    public function index()
    {
        // Fetch all purchase returns with applied vendor credit amounts
        $records = $this->db->select('pr.*, v.VendorName, COALESCE(applied.amount, 0) AS AppliedAmount')
            ->from('purchase_returns pr')
            ->join('vendor v', 'v.VendorID = pr.VendorID', 'left')
            ->join('(SELECT PurchaseReturnID, SUM(PaidAmount) AS amount FROM vendor_payments WHERE IsDeleted = 0 GROUP BY PurchaseReturnID) applied', 'applied.PurchaseReturnID = pr.PurchaseReturnID', 'left')
            ->where('pr.IsDeleted', 0)
            ->order_by('pr.PurchaseReturnID', 'DESC')
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
        $data['vendors'] = $this->db
            ->get_where('vendor', ['IsDeleted' => 0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items', ['IsDeleted' => 0])
            ->result();

        $d = [
            'page' => "$this->page/form",
            'vendors' => $data['vendors'],
            'items' => $data['items'],
            'obj' => null
        ];
        $this->view($d);
    }

    public function save()
    {
        $form = $this->input->post('form');
        $returnID = $this->input->post('PurchaseReturnID');
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
            'VendorID' => $form['VendorID'],
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

            // Update purchase return
            $this->db->where('PurchaseReturnID', $returnID)->update('purchase_returns', $returnData);

            // Delete old items
            $this->db->where('PurchaseReturnID', $returnID)->delete('purchase_return_items');

            $message = 'updated';
        } else {
            $returnData['CreatedAt'] = date('Y-m-d H:i:s');
            $returnData['IsDeleted'] = 0;

            $this->db->insert('purchase_returns', $returnData);
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
                    'PurchaseReturnID' => $returnID,
                    'ItemID' => $item['ItemID'],
                    'Quantity' => $qty,
                    'QtyType' => isset($item['QtyType']) ? $item['QtyType'] : 'Pcs',
                    'RefundPrice' => $price,
                    'TotalAmount' => $totalAmount,
                    'IsDamaged' => isset($item['IsDamaged']) ? 1 : 0
                ];
                $this->db->insert('purchase_return_items', $itemData);
            }
        }

        // Create accounting entry
        $this->apply_accounting($returnID, $form['VendorID'], $form['ReturnDate'], $totalReturnAmount);

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Purchase Return {$message} successfully!</strong>"
        ]);

        redirect('admin/Purchase_returns');
    }

    public function edit($returnID)
    {
        $obj = $this->db->get_where('purchase_returns', ['PurchaseReturnID' => $returnID])->row();
        if (!$obj) {
            show_404();
        }

        $data['obj'] = $obj;
        $data['return_items'] = $this->db->get_where('purchase_return_items', ['PurchaseReturnID' => $returnID])->result();
        $data['vendors'] = $this->db->get_where('vendor', ['IsDeleted' => 0])->result();
        $data['items'] = $this->db->get_where('items', ['IsDeleted' => 0])->result();

        $d = [
            'page' => "$this->page/form",
            'vendors' => $data['vendors'],
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
        $this->db->where('PurchaseReturnID', $returnID)->update('purchase_returns', ['IsDeleted' => 1]);

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Purchase Return deleted successfully!</strong>"
        ]);

        redirect('admin/Purchase_returns');
    }

    private function apply_accounting($returnID, $vendorId, $returnDate, $totalAmount)
    {
        if ($totalAmount <= 0) return;

        // Auto accounting entry - purchase return (Vendor Credit)
        $journal = [
            'JournalDate' => $returnDate,
            'ReferenceNo' => 'PR-'.$returnID,
            'Description' => 'Purchase Return (Vendor Credit)'
        ];
        $this->db->insert('journal_entries', $journal);
        $journalID = $this->db->insert_id();

        // Debit Accounts Payable: AccountID = 5 (reduce payable)
        $this->db->insert('journal_entry_details', [
            'JournalID'    => $journalID,
            'AccountID'    => 5,
            'DebitAmount'  => $totalAmount,
            'CreditAmount' => 0
        ]);

        // Decrease Accounts Payable balance
        $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalAmount, FALSE);
        $this->db->where('AccountID', 5);
        $this->db->update('chart_of_accounts');

        // Credit Inventory: AccountID = 4 (reduce asset value)
        $this->db->insert('journal_entry_details', [
            'JournalID'    => $journalID,
            'AccountID'    => 4,
            'DebitAmount'  => 0,
            'CreditAmount' => $totalAmount
        ]);

        // Reduce Inventory balance
        $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalAmount, FALSE);
        $this->db->where('AccountID', 4);
        $this->db->update('chart_of_accounts');
    }

    private function revert_accounting($returnID)
    {
        // Find journal entry associated with PR-PurchaseReturnID
        $journal = $this->db->where('ReferenceNo', 'PR-'.$returnID)->get('journal_entries')->row();
        if ($journal) {
            // Find Debit detail (Account 5)
            $debitDetail = $this->db->where('JournalID', $journal->JournalID)->where('AccountID', 5)->get('journal_entry_details')->row();
            if ($debitDetail) {
                // Revert Accounts Payable balance: increase it back
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$debitDetail->DebitAmount, FALSE);
                $this->db->where('AccountID', 5);
                $this->db->update('chart_of_accounts');
            }

            // Find Credit detail (Account 4)
            $creditDetail = $this->db->where('JournalID', $journal->JournalID)->where('AccountID', 4)->get('journal_entry_details')->row();
            if ($creditDetail) {
                // Revert Inventory balance: increase it back
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$creditDetail->CreditAmount, FALSE);
                $this->db->where('AccountID', 4);
                $this->db->update('chart_of_accounts');
            }

            // Delete journal entry and details
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entry_details');
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entries');
        }
    }

    public function apply($returnID)
    {
        $return = $this->db->select('pr.*, v.VendorName')
            ->from('purchase_returns pr')
            ->join('vendor v', 'v.VendorID = pr.VendorID', 'left')
            ->where('pr.PurchaseReturnID', $returnID)
            ->where('pr.IsDeleted', 0)
            ->get()
            ->row();

        if (!$return) {
            show_404();
        }

        // Get total applied amount (excluding previous allocations to see baseline unused credit)
        $applied = $this->db->select_sum('PaidAmount')
            ->where('PurchaseReturnID', $returnID)
            ->where('IsDeleted', 0)
            ->get('vendor_payments')
            ->row()
            ->PaidAmount;
        $applied = $applied ?: 0;

        $unusedCredit = $return->TotalReturnAmount - $applied;

        // Get vendor's unpaid bills (purchases)
        $sql = "SELECT 
                    p.PurchaseID,
                    p.ReferenceNo,
                    p.PurchaseDate,
                    p.TotalAmount,
                    IFNULL(vp.paid, 0) AS PaidAmount,
                    (p.TotalAmount - IFNULL(vp.paid, 0)) AS due
                FROM purchases p
                LEFT JOIN (
                    SELECT PurchaseID, SUM(PaidAmount) AS paid
                    FROM vendor_payments
                    WHERE IsDeleted = 0
                    GROUP BY PurchaseID
                ) vp ON vp.PurchaseID = p.PurchaseID
                WHERE p.VendorID = ?
                  AND p.IsDeleted = 0
                  AND (p.TotalAmount - IFNULL(vp.paid, 0)) > 0";
        $bills = $this->db->query($sql, [$return->VendorID])->result();

        // Get currently applied bills under this return
        $allocations = $this->db->where('PurchaseReturnID', $returnID)
            ->where('IsDeleted', 0)
            ->get('vendor_payments')
            ->result();

        $d = [
            'page' => "$this->page/apply",
            'return' => $return,
            'unusedCredit' => $unusedCredit,
            'bills' => $bills,
            'allocations' => $allocations
        ];
        $this->view($d);
    }

    public function apply_save()
    {
        $returnID = $this->input->post('PurchaseReturnID');
        $amounts = $this->input->post('amounts'); // array [PurchaseID => Amount]

        $return = $this->db->get_where('purchase_returns', ['PurchaseReturnID' => $returnID, 'IsDeleted' => 0])->row();
        if (!$return) {
            show_404();
        }

        $this->db->trans_start();

        // Delete previous allocations for this PurchaseReturnID
        $this->db->where('PurchaseReturnID', $returnID)->delete('vendor_payments');

        $totalApplied = 0;
        if (!empty($amounts)) {
            foreach ($amounts as $purchaseID => $amt) {
                $amt = floatval($amt);
                if ($amt <= 0) continue;

                $purchase = $this->db->get_where('purchases', ['PurchaseID' => $purchaseID, 'IsDeleted' => 0])->row();
                if (!$purchase) continue;

                // Calculate due for this bill (since we deleted previous allocations for this return, prevPaid does not include them)
                $prevPaid = $this->db->select_sum('PaidAmount')
                    ->where('PurchaseID', $purchaseID)
                    ->where('IsDeleted', 0)
                    ->get('vendor_payments')
                    ->row()
                    ->PaidAmount;
                $prevPaid = $prevPaid ?: 0;
                $due = $purchase->TotalAmount - $prevPaid;

                if ($amt > $due) {
                    $amt = $due;
                }

                $totalApplied += $amt;

                $user = $this->session->userdata('user');
                $userId = (is_object($user) && isset($user->UserId)) ? $user->UserId : NULL;

                $paymentData = [
                    'VendorID'       => $return->VendorID,
                    'PurchaseID'     => $purchaseID,
                    'UserId'         => $userId,
                    'PaidAmount'     => $amt,
                    'PaymentDue'     => $due - $amt,
                    'PaymentDate'    => date('Y-m-d'),
                    'PaymentMethod'  => 'Vendor Credit',
                    'PurchaseReturnID' => $returnID,
                    'CreatedBy'      => $userId,
                    'CreatedDate'    => date('Y-m-d H:i:s')
                ];
                $this->db->insert('vendor_payments', $paymentData);
            }
        }

        // Validate that totalApplied does not exceed return value
        if ($totalApplied > $return->TotalReturnAmount) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text"  => "<strong>Failed:</strong> Allocated credit amount (" . number_format($totalApplied, 2) . ") exceeds total return value (" . number_format($return->TotalReturnAmount, 2) . ")."
            ]);
            redirect('admin/Purchase_returns/apply/' . $returnID);
            return;
        }

        $this->db->trans_complete();

        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text"  => "<strong>Vendor Credit applied to bills successfully!</strong>"
        ]);
        redirect('admin/Purchase_returns');
    }
}
