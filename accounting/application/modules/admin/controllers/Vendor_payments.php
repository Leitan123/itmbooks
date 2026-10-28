<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Vendor_payments extends MY_Controller
{
    var $page = "vendor_payments";

    function __construct()
    {
        parent::__construct();
        $this->load->model('vendor_payments_model', 'model');
        $this->load->model('vendor_model', 'vendor');
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
        // Load vendors
        $data['vendors'] = $this->vendor->get_all();
        $this->load->model('Banks_model', 'banks_model');
        $data['banks'] = $this->banks_model->get_all();

        // Set form validation rules
        $this->form_validation->set_rules("form[VendorID]", "Vendor", "required");
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
            'vendors' => $data['vendors'],
            'banks' => $data['banks'],
        ]);
    }

    public function save_payment() {
        $data = $this->input->post('form');

        // Check if vendor exists
        $vendor = $this->db->where('VendorID', $data['VendorID'])->get('vendor')->row();
        if (!$vendor) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>Invalid Vendor ID.</strong>'
            ]);
            redirect('admin/Vendor_payments');
            return;
        }

        // Ensure purchases are selected
        if (!isset($data['purchases']) || empty($data['purchases'])) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>No purchases selected for payment.</strong>'
            ]);
            redirect('admin/Vendor_payments');
            return;
        }

        // Start DB transaction
        $this->db->trans_start();

        foreach ($data['purchases'] as $purchaseID => $payNow) {
            $payNow = floatval($payNow);
            if ($payNow <= 0) continue; // Skip zero payments

            // Get purchase details
            $purchase = $this->db->where('PurchaseID', $purchaseID)
                                 ->where('IsDeleted', 0)
                                 ->get('purchases')
                                 ->row();

            if (!$purchase) continue; // Skip invalid purchases

            // Get total paid so far
            // Fetch total purchase amount


            // Total already paid for this purchase

            $rowPaid = $this->db
                ->select_sum('PaidAmount')
                ->where('PurchaseID', $purchaseID)
                ->where('IsDeleted', 0)
                ->get('vendor_payments')
                ->row();

            $totalPaidSoFar = ($rowPaid && $rowPaid->PaidAmount)
                ? $rowPaid->PaidAmount
                : 0;

            // Calculate remaining due
            $remainingDue =
            $purchase->TotalPurchaseAmount
            -
            $totalPaidSoFar
            -
            $payNow;
            if ($remainingDue < 0) $remainingDue = 0;

            // Prepare payment data
            $paymentData = [
                'VendorID' => $data['VendorID'],
                'BankID' => !empty($data['BankID'])
                            ? $data['BankID']
                            : NULL,                
                'PurchaseID' => $purchaseID,
                'PaidAmount' => $payNow,
                'PaymentDue' => $remainingDue,
                'PaymentDate' => $data['PaymentDate'],
                'PaymentMethod' => $data['PaymentMethod'],
                'ManualReceiptNo' => !empty($data['ManualReceiptNo']) 
                                            ? $data['ManualReceiptNo'] 
                                            : null,
                'ChequeNumber' => isset($data['ChequeNumber']) ? $data['ChequeNumber'] : null,
                'ChequeDate' => isset($data['ChequeDate']) ? $data['ChequeDate'] : null,
                'CreatedBy' => $this->session->userdata('user_id'),
                'CreatedDate' => date('Y-m-d H:i:s'),
                'UserId'        => (int) $this->session->userdata('user')->UserId,
            ];

            // Insert payment
            $this->model->insert($paymentData);

            if ($data['PaymentMethod'] != 'Vendor Credit') {
                // ======================================
                // AUTO SAVE ISSUED CHEQUE
                // ======================================

                if($data['PaymentMethod'] == 'Cheque')
                {

                    $bankId = 0;

                    // FIND BANK USING BANK NAME
                    if(isset($data['BankName']))
                    {

                        $bank = $this->db
                            ->get_where(
                                'bank_accounts',
                                [
                                    'BankName' =>
                                        $data['BankName']
                                ]
                            )
                            ->row();

                        if($bank)
                        {
                            $bankId = $bank->BankID;
                        }
                    }


                    $issuedCheque = [

                        'VendorID' =>
                            $data['VendorID'],

                        'BankID' =>
                            $bankId,

                        'ChequeNo' =>
                            isset($data['ChequeNumber'])
                            ? $data['ChequeNumber']
                            : '',

                        'ChequeDate' =>
                            isset($data['ChequeDate'])
                            ? $data['ChequeDate']
                            : '',

                        'Amount' =>
                            $payNow,

                        'Status' =>
                            'Issued',

                        'Notes' =>
                            'Vendor Payment Cheque'

                    ];


                    $this->db->insert(
                        'issued_cheques',
                        $issuedCheque
                    );
                }

                // ======================================
                // AUTO ACCOUNTING ENTRY - VENDOR PAYMENT
                // ======================================

                $paymentID = $this->db->insert_id();

                $journal = [

                    'JournalDate' => $data['PaymentDate'],

                    'ReferenceNo' => 'VP-'.$paymentID,

                    'Description' => 'Vendor Payment'

                ];

                $this->db->insert('journal_entries', $journal);

                $journalID = $this->db->insert_id();


                // ======================================
                // DEBIT ACCOUNTS PAYABLE
                // AccountID = 5
                // ======================================

                $this->db->insert('journal_entry_details', [

                    'JournalID' => $journalID,

                    'AccountID' => 5,

                    'DebitAmount' => $payNow,

                    'CreditAmount' => 0

                ]);


                // Reduce Payable Balance
                $this->db->set(
                    'CurrentBalance',
                    'CurrentBalance - '.$payNow,
                    FALSE
                );

                $this->db->where('AccountID', 5);

                $this->db->update('chart_of_accounts');


                // ======================================
                // CREDIT CASH ACCOUNT
                // AccountID = 1
                // ======================================

                // Determine Credit Account

                if(
                    $data['PaymentMethod'] == 'Bank Transfer'
                    || $data['PaymentMethod'] == 'Cheque'
                )
                {
                    $creditAccount = 2; // Bank
                    if(!empty($data['BankID'])) {
                        $bank_row = $this->db->select('AccountID')->where('BankID', $data['BankID'])->get('banks')->row();
                        if($bank_row) {
                            $creditAccount = $bank_row->AccountID;
                        }
                    }
                }
                else
                {
                    $creditAccount = 1; // Cash
                }


                // Credit Cash / Bank

                $this->db->insert('journal_entry_details', [

                    'JournalID'    => $journalID,
                    'AccountID'    => $creditAccount,
                    'DebitAmount'  => 0,
                    'CreditAmount' => $payNow

                ]);


                // Reduce Cash Balance
                $this->db->set(
                    'CurrentBalance',
                    'CurrentBalance - '.$payNow,
                    FALSE
                );

                $this->db->where('AccountID', $creditAccount);

                $this->db->update('chart_of_accounts');
            }


        }

        // Complete transaction
        $this->db->trans_complete();

        // Check transaction result
        if ($this->db->trans_status()) {
            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => '<strong>Payment(s) added successfully!</strong>'
            ]);
        } else {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => '<strong>Failed to add payment(s).</strong>'
            ]);
        }

        redirect('admin/Vendor_payments');
    }




    // Fetch TotalSaleAmount based on selected vendor
    public function get_total_due_amount($vendorId) {
        // Fetch total paid amount
        $queryPaid = $this->db->select_sum('PaidAmount')
            ->where('VendorID', $vendorId)
            ->where('IsDeleted', 0) // Exclude deleted payments
            ->get('vendor_payments');
        $totalPaid = $queryPaid->row() ? $queryPaid->row()->PaidAmount : 0;

        // Fetch total purchase amount
        $queryPurchases = $this->db->select_sum('TotalPurchaseAmount')
            ->where('VendorID', $vendorId)
            ->where('IsDeleted', 0) // Exclude deleted purchases
            ->get('purchases');
        $totalPurchases = $queryPurchases->row() ? $queryPurchases->row()->TotalPurchaseAmount : 0;

        // Calculate PaymentDue
        $paymentDue = $totalPurchases - $totalPaid;

        // Ensure no negative values
        if ($paymentDue < 0) {
            $paymentDue = 0;
        }

        // Send the response as JSON
        echo json_encode(['PaymentDue' => $paymentDue]);
    }




    private function get_total_paid($vendorId) {
        $totalPaid = $this->db->select_sum('PaidAmount')
            ->where('VendorID', $vendorId)
            ->get('vendor_payments')
            ->row()
            ->PaidAmount;

        return $totalPaid ? $totalPaid : 0;
    }


    public function balance_sheet() {
        $records = $this->model->get_all_vendors_with_due();
        $d = [
            'page' => "$this->page/balance_sheet",
            'records' => $records
        ];
        $this->view($d);
    }


    function edit($_id = 0)
    {
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


    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Delete Record or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }


   public function get_vendor_purchases($vendorId)
    {
        $query = $this->db->query("
            SELECT 
                p.PurchaseID,
                p.PurchaseDate,
                p.PONo,
                p.RefNo,
                p.TotalPurchaseAmount AS TotalAmount,
                IFNULL(vp.PaidAmount, 0) AS PaidAmount,
                (p.TotalPurchaseAmount - IFNULL(vp.PaidAmount, 0)) AS due
            FROM purchases p
            LEFT JOIN (
                SELECT PurchaseID, SUM(PaidAmount) AS PaidAmount
                FROM vendor_payments
                WHERE IsDeleted = 0
                GROUP BY PurchaseID
            ) vp ON vp.PurchaseID = p.PurchaseID
            WHERE p.VendorID = ? AND p.IsDeleted = 0
            HAVING due > 0
            ORDER BY p.PurchaseDate ASC
        ", [$vendorId]);

        $results = $query->result();

        // Ensure JSON numbers are formatted correctly
        foreach ($results as &$row) {
            $row->TotalAmount = (float) $row->TotalAmount;
            $row->PaidAmount  = (float) $row->PaidAmount;
            $row->due         = (float) $row->due;
        }

        echo json_encode($results);
    }



    //reports

    // Summary Report
    public function summary() {

        $records = $this->model->get_summary();

        // ADD THIS
        $vendors = $this->vendor->get_all();

        $this->view([
            'page' => 'vendor_payments/summary',
            'records' => $records,
            'vendors' => $vendors // ADD THIS
        ]);
    }


    // Detailed Report
    public function detailed($vendorId = 0) {
        if (!$vendorId) redirect('admin/Vendor_payments');

        $vendor = $this->vendor->get($vendorId);
        $records = $this->model->get_detailed($vendorId);

        $d = [
            'page' => "$this->page/detailed",
            'vendor' => $vendor,
            'records' => $records
        ];

        $this->view($d);
    }

    // Vendor Ledger
    public function ledger($vendorId = 0) {
        if (!$vendorId) redirect('admin/Vendor_payments');

        $vendor = $this->vendor->get($vendorId);
        $records = $this->model->get_ledger($vendorId);

        // Calculate running balance
        $balance = 0;
        foreach($records as $r) {
            $paid = isset($r->PaidAmount) ? $r->PaidAmount : 0; // PHP 5.6 compatible
            $total = isset($r->TotalAmount) ? $r->TotalAmount : 0;
            $balance += ($total - $paid);
            $r->Balance = $balance;
        }

        $d = [
            'page' => "$this->page/ledger",
            'vendor' => $vendor,
            'records' => $records
        ];

        $this->view($d);
    }



        
}

?>
