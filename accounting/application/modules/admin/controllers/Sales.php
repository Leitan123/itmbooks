<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Sales extends MY_Controller
{
    var $page = "sales";

    function __construct()
    {
        parent::__construct();
        $this->load->model('sales_model', 'model');
        $this->load->model('items_model', 'items');
        $this->load->model('customer_model', 'customer');
        $this->load->model('categories_model', 'categories');
        $this->controller = get_class($this);
        $this->load->database();
        if (!$this->db->field_exists('Description', 'sale_items')) {
            $this->db->query("ALTER TABLE sale_items ADD Description TEXT DEFAULT NULL");
        }
    }

    // Display the sales index page
    public function index()
    {
        $records = $this->model->get_all();
        $d = [
            'page' => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }



    // Edit a specific sale by ID
    public function edit($_id = 0) {
        if ($_id) {
            $data['sale'] = $this->model->get_sale_by_id($_id);
            $data['sale_items'] = $this->model->get_sale_items($_id);
            $data['customers'] = $this->customer->get_all();
            $data['categories'] = $this->categories->get_all();
            $data['items'] = $this->items->get_all();

            $this->_form($data, $_id);
        } else {
            $this->index();
        }
    }

    public function create_from_quotation($QuotationID = 0)
    {
        $data = [];

        $data['customers'] = $this->customer->get_all();
        $data['categories'] = $this->categories->get_all();
        $data['items'] = $this->items->get_all();

        $data['sale'] = null;
        $data['sale_items'] = [];
        $data['jobcard'] = null;

        if ($QuotationID > 0) {
            $quotation = $this->db->get_where('quotation', ['QuotationID' => $QuotationID])->row();
            if ($quotation) {
                // Populate sales data
                $data['sale'] = (object)[
                    'CustomerId'      => $quotation->CustomerID,
                    'TotalSaleAmount' => $quotation->GrandTotal,
                    'DiscountAmount'  => $quotation->DiscountAmount,
                    'Remarks'         => $quotation->Remarks,
                ];

                // Load quotation items
                $quotationItems = $this->db->get_where('quotation_items', ['QuotationID' => $QuotationID])->result();
                foreach ($quotationItems as $qitem) {
                    $qty = isset($qitem->Qty) ? $qitem->Qty : 1;
                    $price = isset($qitem->UnitPrice) ? $qitem->UnitPrice : 0;
                    $totalAmount = $qty * $price;

                    $data['sale_items'][] = (object)[
                        'ItemID'          => $qitem->ItemID,
                        'Description'     => isset($qitem->Description) ? $qitem->Description : '',
                        'Quantity'        => $qty,
                        'QtyType'         => 'Pcs',
                        'SalePrice'       => $price,
                        'DiscountPercent' => 0,
                        'DiscountAmount'  => 0,
                        'TotalAmount'     => $totalAmount
                    ];
                }
            }
        }

        $this->view([
            'page' => "$this->page/form",
            'customers' => $data['customers'],
            'items' => $data['items'],
            'categories' => $data['categories'],
            'sale' => $data['sale'],
            'sale_items' => $data['sale_items'],
            'jobcard' => null,
            'quotation_id' => $QuotationID
        ]);
    }

    // Create a new sale
    function create($JobCardID = 0)
    {
        $data = [];

        $data['customers'] = $this->customer->get_all();
        $data['categories'] = $this->categories->get_all();
        $data['items'] = $this->items->get_all();

        $data['sale'] = null;
        $data['sale_items'] = [];
        $data['jobcard'] = null;

        if($JobCardID > 0)
        {
            $data['jobcard'] = $this->db
                ->get_where(
                    'job_card',
                    [
                        'JobCardID' => $JobCardID
                    ]
                )
                ->row();

            if($data['jobcard'])
            {
                /*
                LOAD QUOTATION
                */

                $quotation = $this->db
                    ->get_where(
                        'quotation',
                        [
                            'QuotationID' =>
                            $data['jobcard']->QuotationID
                        ]
                    )
                    ->row();

                if($quotation)
                {
                    $data['jobcard']->QuotationDiscount =
                        $quotation->DiscountAmount;
                }

                /*
                SET SALES TOTAL FROM JOB CARD
                */

                $data['sale'] = (object)[

                    'TotalSaleAmount' =>
                        $data['jobcard']->EstimatedAmount,

                    'Remarks' =>
                        ($quotation && isset($quotation->Remarks)) ? $quotation->Remarks : ''

                ];

                /*
                LOAD QUOTATION ITEMS
                */

                $quotationItems = $this->db
                    ->get_where(
                        'quotation_items',
                        [
                            'QuotationID' =>
                            $data['jobcard']->QuotationID
                        ]
                    )
                    ->result();

                foreach($quotationItems as $qitem)
                {
                    $qty = isset($qitem->Qty)
                        ? $qitem->Qty
                        : 1;

                    $price = isset($qitem->UnitPrice)
                        ? $qitem->UnitPrice
                        : 0;

                    $totalAmount =
                        ($qty * $price);

                    $data['sale_items'][] = (object)[

                        'ItemID' =>
                            $qitem->ItemID,

                        'Description' =>
                            isset($qitem->Description) ? $qitem->Description : '',

                        'Quantity' =>
                            $qty,

                        'QtyType' =>
                            'Pcs',

                        'SalePrice' =>
                            $price,

                        'DiscountPercent' =>
                            0,

                        'DiscountAmount' =>
                            0,

                        'TotalAmount' =>
                            $totalAmount

                    ];
                }
            }
        }

        $this->view([
            'page' => "$this->page/form",
            'customers' => $data['customers'],
            'items' => $data['items'],
            'categories' => $data['categories'],
            'sale' => $data['sale'],
            'sale_items' => $data['sale_items'],
            'jobcard' => $data['jobcard']
        ]);
    }


    // Handle the sale form (create/edit)
    function _form($data = [], $id = null)
    {
        if (empty($data['customers'])) {
            $data['customers'] = $this->customer->get_all();
        }

        if (empty($data['categories'])) {
            $data['categories'] = $this->categories->get_all();
        }

        if (empty($data['items'])) {
            $data['items'] = $this->items->get_all();
        }

        if (!isset($data['jobcard'])) {
            $data['jobcard'] = null;
        }

        if ($id) {

            $data['sale'] = $this->model->get_sale_by_id($id);

            $data['sale_items'] = $this->model->get_sale_items($id);

        } else {

            if (!isset($data['sale'])) {
                $data['sale'] = null;
            }

            if (!isset($data['sale_items'])) {
                $data['sale_items'] = [];
            }
        }

    $this->form_validation->set_rules(
        "form[CustomerId]",
        "Customer",
        "required"
    );

    $this->form_validation->set_rules(
        "form[SaleDate]",
        "Sale Date",
        "required"
    );

    if ($this->form_validation->run()) {
        $this->save_sale();
        return;
    } else {
        if ($this->input->post()) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text" => "<strong>Form validation failed:</strong> " . validation_errors()
            ]);
            redirect(current_url());
            return;
        }
    }

    $this->view([
        'page' => "$this->page/form",
        'customers' => $data['customers'],
        'items' => $data['items'],
        'categories' => $data['categories'],
        'sale' => $data['sale'],
        'sale_items' => $data['sale_items'],
        'jobcard' => $data['jobcard']
    ]);
}

    // Delete a sale by ID
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

    // Fetch items by category, returned as JSON
    public function get_items_by_category($categoryId)
    {
        $items = $this->db->where('CategoryID', $categoryId)
                          ->get('items')
                          ->result();

        echo json_encode($items); // Return JSON for use in AJAX
    }


    // Save multiple items for a sale
    public function save_sale()
{
    $data   = $this->input->post('form');
    $saleID = $this->input->post('SaleID');

    $totalSaleAmount = 0;

    if(empty($data['items']) || !is_array($data['items']))
    {
        $this->session->set_flashdata(
            'notification',
            [
                "alert" => "danger",
                "text"  => "<strong>Please add items.</strong>"
            ]
        );

        redirect('admin/Sales');
        return;
    }

    $user = $this->session->userdata('user');

    if(!is_object($user) || !isset($user->UserId))
    {
        $this->session->set_flashdata(
            'notification',
            [
                "alert" => "danger",
                "text"  => "<strong>Please login again.</strong>"
            ]
        );

        redirect('admin/Sales');
        return;
    }

    $userId = $user->UserId;

    /*
    CALCULATE GRAND TOTAL
    */

    foreach($data['items'] as $item)
    {
        $qty = isset($item['Quantity'])
            ? (float)$item['Quantity']
            : 0;

        $price = isset($item['SalePrice'])
            ? (float)$item['SalePrice']
            : 0;

        $discountAmount = isset($item['DiscountAmount'])
            ? (float)$item['DiscountAmount']
            : 0;

        $subTotal = ($qty * $price);

        $totalSaleAmount +=
            ($subTotal - $discountAmount);
    }

    /*
    SALES MASTER
    */

    $totalDiscount = 0;

    foreach($data['items'] as $item)
    {
        $totalDiscount += isset($item['DiscountAmount'])
            ? (float)$item['DiscountAmount']
            : 0;
    }

    $saleData = [

        'UserId'          => $userId,
        'CustomerId'      => $data['CustomerId'],
        'SaleDate'        => $data['SaleDate'],
        'PONo'            => !empty($data['PONo']) ? $data['PONo'] : NULL,
        'RefNo'           => !empty($data['RefNo']) ? $data['RefNo'] : NULL,
        'Remarks'         => !empty($data['Remarks']) ? $data['Remarks'] : NULL,
        'JobCardID'       => !empty($data['JobCardID']) ? $data['JobCardID'] : NULL,

        'TotalDiscount'   => $totalDiscount,
        'TotalSaleAmount' => $totalSaleAmount,

        'IsDeleted'       => 0,
        'UpdatedAt'       => date('Y-m-d H:i:s')

    ];

    if($saleID)
    {
        $this->db
            ->where('SaleID', $saleID)
            ->update('sales', $saleData);

        $this->db
            ->where('SaleID', $saleID)
            ->delete('sale_items');

        $message = 'updated';
    }
    else
    {
        $saleData['CreatedAt'] =
            date('Y-m-d H:i:s');

        $this->db->insert(
            'sales',
            $saleData
        );

        $saleID =
            $this->db->insert_id();

        if(!empty($data['JobCardID']))
        {
            $this->db
                ->where(
                    'JobCardID',
                    $data['JobCardID']
                );

            $this->db->update(
                'job_card',
                [
                    'Status' => 'Completed'
                ]
            );
        }

        $message = 'added';
    }

    /*
    SAVE SALES ITEMS
    */

    foreach($data['items'] as $item)
    {
        $qty = isset($item['Quantity'])
            ? (float)$item['Quantity']
            : 0;

        $price = isset($item['SalePrice'])
            ? (float)$item['SalePrice']
            : 0;

        $discountAmount = isset($item['DiscountAmount'])
            ? (float)$item['DiscountAmount']
            : 0;

        $subTotal =
            ($qty * $price);

        $totalAmount =
            ($subTotal - $discountAmount);

        $saleItemData = [

            'SaleID'           => $saleID,
            'ItemID'           => !empty($item['ItemID']) ? $item['ItemID'] : NULL,
            'Description'      => isset($item['Description']) ? $item['Description'] : NULL,
            'Quantity'         => $qty,
            'QtyType'          => $item['QtyType'],
            'SalePrice'        => $price,

            'DiscountPercent' => 0,
            'DiscountAmount'  => $discountAmount,
            'Discount'        => $discountAmount,

            'TotalAmount'     => $totalAmount

        ];

        $this->db->insert(
            'sale_items',
            $saleItemData
        );
    }

    /*
    HANDLE SAVE INVOICE (CREDIT) VS PAY RECEIPT (CASH RECEIVED)
    */
    $submitAction = $this->input->post('submit_action'); // 'save_invoice' or 'pay_receipt'

    if ($submitAction === 'pay_receipt') {
        $existingPayment = $this->db->where('SaleID', $saleID)->where('IsDeleted', 0)->get('customer_payments')->row();
        if ($existingPayment) {
            $diffAmount = $totalSaleAmount - $existingPayment->PaidAmount;
            
            // Update payment amount
            $this->db->where('PaymentID', $existingPayment->PaymentID)->update('customer_payments', [
                'PaidAmount' => $totalSaleAmount,
                'PaymentDue' => 0.00
            ]);
            
            // Find journal entry associated with CP-PaymentID
            $journal = $this->db->where('ReferenceNo', 'CP-'.$existingPayment->PaymentID)->get('journal_entries')->row();
            if ($journal) {
                // Update Debit details (Account 1 - Cash)
                $this->db->where('JournalID', $journal->JournalEntryID)->where('AccountID', 1)->update('journal_entry_details', [
                    'DebitAmount' => $totalSaleAmount
                ]);
                
                // Update Credit details (Account 3 - Accounts Receivable)
                $this->db->where('JournalID', $journal->JournalEntryID)->where('AccountID', 3)->update('journal_entry_details', [
                    'CreditAmount' => $totalSaleAmount
                ]);
            }
            
            if ($diffAmount != 0) {
                // Update Cash
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$diffAmount, FALSE);
                $this->db->where('AccountID', 1);
                $this->db->update('chart_of_accounts');
                
                // Update Accounts Receivable
                $this->db->set('CurrentBalance', 'CurrentBalance - '.$diffAmount, FALSE);
                $this->db->where('AccountID', 3);
                $this->db->update('chart_of_accounts');
            }
        } else {
            // Retrieve user details
            $user = $this->session->userdata('user');
            $userId = (is_object($user) && isset($user->UserId)) ? $user->UserId : NULL;

            // Prepare payment data
            $paymentData = [
                'CustomerId'    => $data['CustomerId'],
                'SaleID'        => $saleID,
                'UserId'        => $userId,
                'BankID'        => NULL,
                'PaidAmount'    => $totalSaleAmount,
                'PaymentDue'    => 0.00,
                'PaymentDate'   => $data['SaleDate'] . ' ' . date('H:i:s'),
                'PaymentMethod' => 'Cash',
                'ChequeNumber'  => '',
                'ChequeDate'    => '0000-00-00',
                'CreatedBy'     => $userId,
                'CreatedDate'   => date('Y-m-d H:i:s')
            ];

            $this->db->insert('customer_payments', $paymentData);
            $paymentID = $this->db->insert_id();

            // Auto accounting entry - customer payment
            $journal = [
                'JournalDate' => $data['SaleDate'],
                'ReferenceNo' => 'CP-'.$paymentID,
                'Description' => 'Customer Payment Received (Pay Receipt)'
            ];
            $this->db->insert('journal_entries', $journal);
            $journalID = $this->db->insert_id();

            // Debit Cash: AccountID = 1
            $this->db->insert('journal_entry_details', [
                'JournalID'    => $journalID,
                'AccountID'    => 1,
                'DebitAmount'  => $totalSaleAmount,
                'CreditAmount' => 0
            ]);

            // Increase Cash
            $this->db->set('CurrentBalance', 'CurrentBalance + '.$totalSaleAmount, FALSE);
            $this->db->where('AccountID', 1);
            $this->db->update('chart_of_accounts');

            // Credit Accounts Receivable: AccountID = 3
            $this->db->insert('journal_entry_details', [
                'JournalID'    => $journalID,
                'AccountID'    => 3,
                'DebitAmount'  => 0,
                'CreditAmount' => $totalSaleAmount
            ]);

            // Reduce Receivable
            $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalSaleAmount, FALSE);
            $this->db->where('AccountID', 3);
            $this->db->update('chart_of_accounts');
        }
    } else if ($submitAction === 'save_invoice') {
        $existingPayment = $this->db->where('SaleID', $saleID)->where('IsDeleted', 0)->get('customer_payments')->row();
        if ($existingPayment) {
            // Delete/Mark deleted the payment
            $this->db->where('PaymentID', $existingPayment->PaymentID)->update('customer_payments', ['IsDeleted' => 1]);
            
            // Revert balances
            $this->db->set('CurrentBalance', 'CurrentBalance - '.$existingPayment->PaidAmount, FALSE);
            $this->db->where('AccountID', 1);
            $this->db->update('chart_of_accounts');
            
            $this->db->set('CurrentBalance', 'CurrentBalance + '.$existingPayment->PaidAmount, FALSE);
            $this->db->where('AccountID', 3);
            $this->db->update('chart_of_accounts');
        }
    }

    $this->session->set_flashdata(
        'notification',
        [
            "alert" => "success",
            "text"  =>
                "<strong>Sale {$message} successfully!</strong>"
        ]
    );

    redirect('admin/Sales');
}


    // Display stock report
    public function stock()
    {
        // Fetch stock data from the model
        $stockData = $this->model->get_stock_report();

        // Pass the stock data to the view
        $this->view([
            'page' => 'sales/stock',
            'stockData' => $stockData
        ]);
    }

    // Print invoice for a specific sale
    public function print_invoice($saleID)
    {
        if (empty($saleID)) {
            show_error('Invalid Sale ID');
        }

        // Get sale with user & customer details
        $sale = $this->model->get_sale_invoice($saleID);

        if (empty($sale)) {
            show_error('Invoice not found');
        }

        // Load items with details (names & descriptions)
        $items = $this->model->get_sale_items_with_details($saleID);

        // Load view
        $data['sales'] = $sale;
        $data['items'] = $items;
        $this->load->view('admin/pages/sales/print_invoice', $data);
    }



public function get_customer_details()
{
    $customerId = $this->input->post('customerId');

    if (!$customerId) {
        echo json_encode(['error' => 'Invalid Customer ID']);
        return;
    }

    $customer = $this->db->get_where('customer', [
        'CustomerId' => $customerId
    ])->row();

    if (!$customer) {
        echo json_encode(['error' => 'Customer not found']);
        return;
    }

    // ✅ Total Sales (ONLY NOT DELETED)
    $this->db->select_sum('TotalSaleAmount');
    $this->db->where('CustomerId', $customerId);
    $this->db->where('IsDeleted', 0); // IMPORTANT
    $salesTotal = (float) $this->db->get('sales')->row()->TotalSaleAmount;

    // Total Payments
    $this->db->select_sum('PaidAmount');
    $this->db->where('CustomerId', $customerId);
    $paidTotal = (float) $this->db->get('customer_payments')->row()->PaidAmount;

    $totalOutstanding = $salesTotal - $paidTotal;
    $creditBalance = $customer->CreditLimit - $totalOutstanding;

    echo json_encode([
        'error' => false,
        'CustomerType' => $customer->CustomerType,
        'CreditLimit' => number_format($customer->CreditLimit, 2, '.', ''),
        'CreditBalance' => number_format($creditBalance, 2, '.', '')
    ]);
}



    public function credit_note($saleID)
{
    $data['title'] = 'Create Credit Note';
    
    // Use the correct model alias 'model'
    $data['sale'] = $this->model->get_sale_details($saleID);
    
    $this->load->view('admin/pages/sales/credit_note', $data);
}

public function save_credit_note()
{
    $post = $this->input->post();

    // Prevent over-credit
    if ($post['CreditAmount'] > $post['TotalSaleAmount']) {
        $this->session->set_flashdata('error', 'Credit amount cannot exceed sale amount');
        redirect('admin/sales');
    }

    $data = array(
        'SaleID'           => $post['SaleID'],
        'CreditNoteNo'     => 'CN-' . time(),
        'CreditDate'       => date('Y-m-d'),
        'CustomerID'       => $post['CustomerID'],
        'TotalSaleAmount'  => $post['TotalSaleAmount'],
        'CreditAmount'     => $post['CreditAmount'],
        'Reason'           => $post['Reason'],
        // Use correct session user ID
        'CreatedBy'        => $this->session->userdata('user')->UserId
    );

    $this->db->insert('credit_notes', $data);

    $this->session->set_flashdata('success', 'Credit Note Created Successfully');
    redirect('admin/sales');
}

public function print_credit_note($creditNoteID)
{
    $this->load->model('Credit_notes_model', 'credit');
    $data['sale'] = $this->credit->get_by_id($creditNoteID); // Fetch saved credit note
    $this->load->view('admin/pages/sales/print_credit_note', $data);
}

public function pos()
{
    $data['customers'] = $this->customer->get_all();
    $data['categories'] = $this->categories->get_all();
    $data['items'] = $this->items->get_items_with_categories();
    $data['banks'] = $this->db->where('IsDeleted', 0)->get('banks')->result();
    $data['page'] = "$this->page/pos";
    $this->view($data);
}

public function save_pos()
{
    header('Content-Type: application/json');
    
    $data = $this->input->post('form');
    if (empty($data)) {
        $decoded = json_decode($this->input->raw_input_stream, true);
        $data = isset($decoded['form']) ? $decoded['form'] : $decoded;
    }

    if (empty($data['items']) || !is_array($data['items'])) {
        echo json_encode(['success' => false, 'message' => 'Please add at least one item.']);
        return;
    }

    $user = $this->session->userdata('user');
    if (!is_object($user) || !isset($user->UserId)) {
        echo json_encode(['success' => false, 'message' => 'Please login again.']);
        return;
    }
    $userId = $user->UserId;

    $totalSaleAmount = 0;
    $totalDiscount = 0;

    foreach ($data['items'] as $item) {
        $qty = isset($item['Quantity']) ? (float)$item['Quantity'] : 0;
        $price = isset($item['SalePrice']) ? (float)$item['SalePrice'] : 0;
        $discountAmount = isset($item['DiscountAmount']) ? (float)$item['DiscountAmount'] : 0;
        $subTotal = ($qty * $price);
        $totalSaleAmount += ($subTotal - $discountAmount);
        $totalDiscount += $discountAmount;
    }

    $cashReceived = isset($data['PaidAmount']) ? (float)$data['PaidAmount'] : 0;
    if ($cashReceived < $totalSaleAmount) {
        $cashReceived = $totalSaleAmount;
    }
    $changeGiven = 0;
    if ($cashReceived > $totalSaleAmount) {
        $changeGiven = $cashReceived - $totalSaleAmount;
    }
    $actualPaid = min($totalSaleAmount, $cashReceived);

    $this->db->trans_start();

    // Check if Walk-in Customer exists or create one if CustomerId is empty
    $customerId = isset($data['CustomerId']) ? $data['CustomerId'] : '';
    if (empty($customerId)) {
        $walkIn = $this->db->where('CompanyName', 'Walk-in Customer')->where('IsDeleted', 0)->get('customer')->row();
        if ($walkIn) {
            $customerId = $walkIn->CustomerId;
        } else {
            $count = $this->db->count_all('customer');
            $customerNo = sprintf('%05d', $count + 1);
            $sid = mt_rand(10000, 99999);

            $customerData = [
                'CompanyName' => 'Walk-in Customer',
                'CustomerName' => 'Walk-in Customer',
                'CustomerNo' => $customerNo,
                'Sid' => $sid,
                'RegDate' => date('Y-m-d'),
                'ContactPerson' => 'Cash Customer',
                'Phone' => '-',
                'MobileNo' => '-',
                'Email' => '-',
                'Address' => '-',
                'CreatedDate' => date('Y-m-d H:i:s'),
                'IsDeleted' => 0
            ];
            $this->db->insert('customer', $customerData);
            $customerId = $this->db->insert_id();
        }
    }

    $saleData = [
        'UserId'          => $userId,
        'CustomerId'      => $customerId,
        'SaleDate'        => date('Y-m-d H:i:s'),
        'TotalDiscount'   => $totalDiscount,
        'TotalSaleAmount' => $totalSaleAmount,
        'CashReceived'    => $cashReceived,
        'ChangeGiven'     => $changeGiven,
        'IsDeleted'       => 0,
        'CreatedAt'       => date('Y-m-d H:i:s'),
        'UpdatedAt'       => date('Y-m-d H:i:s')
    ];

    $this->db->insert('sales', $saleData);
    $saleID = $this->db->insert_id();

    foreach ($data['items'] as $item) {
        $qty = isset($item['Quantity']) ? (float)$item['Quantity'] : 0;
        $price = isset($item['SalePrice']) ? (float)$item['SalePrice'] : 0;
        $discountAmount = isset($item['DiscountAmount']) ? (float)$item['DiscountAmount'] : 0;
        $subTotal = ($qty * $price);
        $totalAmount = ($subTotal - $discountAmount);

        $saleItemData = [
            'SaleID'           => $saleID,
            'ItemID'           => $item['ItemID'],
            'Quantity'         => $qty,
            'QtyType'          => isset($item['QtyType']) ? $item['QtyType'] : 'Pcs',
            'SalePrice'        => $price,
            'DiscountPercent'  => 0,
            'DiscountAmount'   => $discountAmount,
            'Discount'         => $discountAmount,
            'TotalAmount'      => $totalAmount
        ];

        $this->db->insert('sale_items', $saleItemData);
    }

    // Handle Payment Details
    $paymentMethod = isset($data['PaymentMethod']) ? $data['PaymentMethod'] : 'Cash';
    $bankId = !empty($data['BankID']) ? $data['BankID'] : NULL;

    if ($actualPaid > 0) {
        $paymentData = [
            'CustomerId'    => $customerId,
            'SaleID'        => $saleID,
            'UserId'        => $userId,
            'BankID'        => $bankId,
            'PaidAmount'    => $actualPaid,
            'PaymentDue'    => max(0, $totalSaleAmount - $actualPaid),
            'PaymentDate'   => date('Y-m-d H:i:s'),
            'PaymentMethod' => $paymentMethod,
            'ChequeNumber'  => isset($data['ChequeNumber']) ? $data['ChequeNumber'] : '',
            'ChequeDate'    => isset($data['ChequeDate']) ? $data['ChequeDate'] : '',
            'CreatedBy'     => $userId,
            'CreatedDate'   => date('Y-m-d H:i:s')
        ];

        $this->db->insert('customer_payments', $paymentData);

        if ($paymentMethod == 'Cheque') {
            $chequeData = [
                'CustomerID'   => $customerId,
                'BankID'       => $bankId ? $bankId : 0,
                'ChequeNo'     => isset($data['ChequeNumber']) ? $data['ChequeNumber'] : '',
                'ChequeDate'   => isset($data['ChequeDate']) ? $data['ChequeDate'] : '',
                'DepositDate'  => date('Y-m-d'),
                'Amount'       => $actualPaid,
                'Status'       => 'Pending',
                'Notes'        => 'POS Cash Sale Cheque'
            ];
            $this->db->insert('received_cheques', $chequeData);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        echo json_encode(['success' => false, 'message' => 'Database transaction failed.']);
    } else {
        echo json_encode(['success' => true, 'SaleID' => $saleID, 'message' => 'POS Sale processed successfully!']);
    }
}

}
