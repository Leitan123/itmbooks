<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Purchases extends MY_Controller
{
    var $page = "purchases";

    function __construct() {
        parent::__construct();
        $this->controller = "Purchases";
        $this->load->model('Purchases_model', 'model');
        $this->load->model('Vendor_model', 'vendor');
        $this->load->model('Categories_model', 'categories');
        $this->load->model('Brand_model', 'brand');
        $this->load->model('Items_model', 'items');
        $this->load->library('form_validation');
        $this->load->database();

        // Self-healing database migration for missing Description column
        if (!$this->db->field_exists('Description', 'purchase_items')) {
            $this->db->query("ALTER TABLE purchase_items ADD Description VARCHAR(255) DEFAULT NULL");
        }

        // Ensure CategoryID, BrandID, and ItemID allow NULL values on the live server
        $this->db->query("ALTER TABLE purchase_items MODIFY CategoryID INT DEFAULT NULL");
        $this->db->query("ALTER TABLE purchase_items MODIFY BrandID INT DEFAULT NULL");
        $this->db->query("ALTER TABLE purchase_items MODIFY ItemID INT DEFAULT NULL");
    }

    public function index() {
        $records = $this->model->get_all();
        $d = [
            'page' => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }


    public function edit($_id = 0) {
    if ($_id) {
        $data['purchase'] = $this->model->get_purchase_by_id($_id);
        $data['purchase_items'] = $this->model->get_purchase_items($_id);
        $data['vendors'] = $this->vendor->get_all();
        $data['categories'] = $this->categories->get_all();
        $data['brand'] = $this->brand->get_all();
        $data['items'] = $this->items->get_all();

        $this->_form($data, $_id);
    } else {
        $this->index();
    }
    }



    function create() {
        $this->_form();
    }

    function _form($data = [], $id = null)
{
    // Load all necessary data
    if (empty($data['vendors'])) {
        $data['vendors'] = $this->vendor->get_all();
    }

    if (empty($data['categories'])) {
        $data['categories'] = $this->categories->get_all();
    }

    if (empty($data['brand'])) {
        $data['brand'] = $this->brand->get_all();
    }

    if (empty($data['items'])) {
        $data['items'] = $this->items->get_all();
    }

    // Handle purchase data
    if ($id) {
        $data['purchase'] = $this->model->get_purchase_by_id($id);
        $data['purchase_items'] = $this->model->get_purchase_items($id);
    } else {
        $data['purchase'] = null;
        $data['purchase_items'] = []; // Initialize empty array for new purchases
    }

    // Form validation
    $this->form_validation->set_rules("form[VendorID]", "Vendor", "required");
    $this->form_validation->set_rules("form[PurchaseDate]", "Purchase Date", "required");
    $this->form_validation->set_rules("form[items]", "Items", "required");

    if ($this->form_validation->run()) {
        $this->save_purchase();
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

    // Pass all data to view
    $this->view([
        'page' => "$this->page/form",
        'vendors' => $data['vendors'],
        'items' => $data['items'],
        'categories' => $data['categories'],
        'brand' => $data['brand'],
        'purchase' => $data['purchase'],
        'purchase_items' => $data['purchase_items'] // This will contain individual items
    ]);
}


    function delete($_id = 0) {
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
    public function get_items_by_category($CategoryID) {
        $items = $this->db->where('CategoryID', $CategoryID)
                          ->get('items')
                          ->result();

        echo json_encode($items); // Return JSON for use in AJAX
    }


    // Save multiple items for a purchase
    public function save_purchase() {
        $data = $this->input->post('form');
        $purchaseID = $this->input->post('PurchaseID'); // Get the PurchaseID if editing
        $totalPurchaseAmount = 0;

        // Get logged-in user
        $user = $this->session->userdata('user');
        if (!is_object($user) || !isset($user->UserId)) {
            $this->session->set_flashdata('notification', [
                "alert" => "danger",
                "text"  => "<strong>User session expired. Please login again.</strong>"
            ]);
            redirect('admin/Purchases');
            return;
        }
        $userId = $user->UserId;

        // Calculate total from items with discount
        foreach ($data['items'] as $item) {
            $discount = isset($item['Discount']) ? floatval($item['Discount']) : 0;
            $itemTotal = ($item['Quantity'] * $item['PurchasePrice']) - 
             (($item['Quantity'] * $item['PurchasePrice']) * ($discount / 100));
            $totalPurchaseAmount += $itemTotal;
        }

        // Prepare purchase data
        $purchaseData = [
            'VendorID' => $data['VendorID'],
            'PurchaseDate' => $data['PurchaseDate'],
            'PONo' => $data['PONo'],
            'RefNo' => $data['RefNo'],
            'Remarks' => isset($data['Remarks']) ? $data['Remarks'] : '',
            'TotalPurchaseAmount' => $totalPurchaseAmount,
            'UserId' => $userId,             // <-- Add UserId here
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ];

        if ($purchaseID) {
            // Update existing purchase
            $this->db->where('PurchaseID', $purchaseID)
                     ->update('purchases', $purchaseData);

            // Delete existing purchase items
            $this->db->where('PurchaseID', $purchaseID)
                     ->delete('purchase_items');
        } else {
            // Create new purchase
            $purchaseData['CreatedAt'] = date('Y-m-d H:i:s');
            $this->db->insert('purchases', $purchaseData);
            $purchaseID = $this->db->insert_id();

            // ======================================
// AUTO ACCOUNTING ENTRY - PURCHASE
// ======================================

$journal = [

    'JournalDate' => $data['PurchaseDate'],

    'ReferenceNo' => 'PUR-'.$purchaseID,

    'Description' => 'Purchase Entry'

];

$this->db->insert('journal_entries', $journal);

$journalID = $this->db->insert_id();


// ======================================
// DEBIT INVENTORY ACCOUNT
// AccountID = 4
// ======================================

$this->db->insert('journal_entry_details', [

    'JournalID' => $journalID,

    'AccountID' => 4,

    'DebitAmount' => $totalPurchaseAmount,

    'CreditAmount' => 0

]);


// Increase Inventory Balance
$this->db->set(
    'CurrentBalance',
    'CurrentBalance + '.$totalPurchaseAmount,
    FALSE
);

$this->db->where('AccountID', 4);

$this->db->update('chart_of_accounts');


// ======================================
// CREDIT ACCOUNTS PAYABLE
// AccountID = 5
// ======================================

$this->db->insert('journal_entry_details', [

    'JournalID' => $journalID,

    'AccountID' => 5,

    'DebitAmount' => 0,

    'CreditAmount' => $totalPurchaseAmount

]);


// Increase Payable Balance
$this->db->set(
    'CurrentBalance',
    'CurrentBalance + '.$totalPurchaseAmount,
    FALSE
);

$this->db->where('AccountID', 5);

$this->db->update('chart_of_accounts');

        }

        // Save purchase items with individual discount
        foreach ($data['items'] as $item) {
            $discount = isset($item['Discount']) ? floatval($item['Discount']) : 0;
            $itemTotal = ($item['Quantity'] * $item['PurchasePrice']) - $discount;

            $itemObj = null;
            if (!empty($item['ItemID'])) {
                $itemObj = $this->db->where('ItemID', $item['ItemID'])->get('items')->row();
            }

            $purchaseItemData = [
                'PurchaseID' => $purchaseID,
                'ItemID' => !empty($item['ItemID']) ? $item['ItemID'] : NULL,
                'Description' => isset($item['Description']) ? $item['Description'] : '',
                'Quantity' => $item['Quantity'],
                'QtyType' => $item['QtyType'],
                'PurchasePrice' => $item['PurchasePrice'],
                'Discount' => $discount,
                'TotalAmount' => $itemTotal,
                'CategoryID' => $itemObj ? $itemObj->CategoryID : NULL,
                'BrandID' => $itemObj ? $itemObj->BrandID : NULL,
            ];

            if (!$this->db->insert('purchase_items', $purchaseItemData)) {
                $db_error = $this->db->error();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => "<strong>Database error inserting items:</strong> " . $db_error['message'] . " (Code: " . $db_error['code'] . ")"
                ]);
                redirect('admin/Purchases');
                return;
            }
        }

        /*
        HANDLE SAVE BILL (CREDIT) VS PAY CASH (CASH PAID)
        */
        $submitAction = $this->input->post('submit_action'); // 'save_bill' or 'pay_cash'

        if ($submitAction === 'pay_cash') {
            $existingPayment = $this->db->where('PurchaseID', $purchaseID)->where('IsDeleted', 0)->get('vendor_payments')->row();
            if ($existingPayment) {
                $diffAmount = $totalPurchaseAmount - $existingPayment->PaidAmount;

                // Update payment amount
                $this->db->where('PaymentID', $existingPayment->PaymentID)->update('vendor_payments', [
                    'PaidAmount' => $totalPurchaseAmount,
                    'PaymentDue' => 0.00
                ]);

                // Find journal entry associated with VP-PaymentID
                $journal = $this->db->where('ReferenceNo', 'VP-'.$existingPayment->PaymentID)->get('journal_entries')->row();
                if ($journal) {
                    // Update Debit details (Account 5 - Accounts Payable)
                    $this->db->where('JournalID', $journal->JournalEntryID)->where('AccountID', 5)->update('journal_entry_details', [
                        'DebitAmount' => $totalPurchaseAmount
                    ]);

                    // Update Credit details (Account 1 - Cash)
                    $this->db->where('JournalID', $journal->JournalEntryID)->where('AccountID', 1)->update('journal_entry_details', [
                        'CreditAmount' => $totalPurchaseAmount
                    ]);
                }

                if ($diffAmount != 0) {
                    // Reduce Payable Balance (AccountID = 5)
                    $this->db->set('CurrentBalance', 'CurrentBalance - '.$diffAmount, FALSE);
                    $this->db->where('AccountID', 5);
                    $this->db->update('chart_of_accounts');

                    // Reduce Cash Balance (AccountID = 1)
                    $this->db->set('CurrentBalance', 'CurrentBalance - '.$diffAmount, FALSE);
                    $this->db->where('AccountID', 1);
                    $this->db->update('chart_of_accounts');
                }
            } else {
                // Prepare payment data
                $paymentData = [
                    'VendorID' => $data['VendorID'],
                    'BankID' => NULL,
                    'PurchaseID' => $purchaseID,
                    'PaidAmount' => $totalPurchaseAmount,
                    'PaymentDue' => 0.00,
                    'PaymentDate' => $data['PurchaseDate'],
                    'PaymentMethod' => 'Cash',
                    'ManualReceiptNo' => null,
                    'ChequeNumber' => null,
                    'ChequeDate' => null,
                    'CreatedBy' => $userId,
                    'CreatedDate' => date('Y-m-d H:i:s'),
                    'UserId' => $userId,
                    'IsDeleted' => 0
                ];

                $this->db->insert('vendor_payments', $paymentData);
                $paymentID = $this->db->insert_id();

                // Auto accounting entry - vendor payment
                $journal = [
                    'JournalDate' => $data['PurchaseDate'],
                    'ReferenceNo' => 'VP-'.$paymentID,
                    'Description' => 'Vendor Payment (Pay Cash)'
                ];
                $this->db->insert('journal_entries', $journal);
                $journalID = $this->db->insert_id();

                // Debit Accounts Payable (AccountID = 5)
                $this->db->insert('journal_entry_details', [
                    'JournalID' => $journalID,
                    'AccountID' => 5,
                    'DebitAmount' => $totalPurchaseAmount,
                    'CreditAmount' => 0
                ]);

                // Reduce Payable Balance
                $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalPurchaseAmount, FALSE);
                $this->db->where('AccountID', 5);
                $this->db->update('chart_of_accounts');

                // Credit Cash (AccountID = 1)
                $this->db->insert('journal_entry_details', [
                    'JournalID' => $journalID,
                    'AccountID' => 1,
                    'DebitAmount' => 0,
                    'CreditAmount' => $totalPurchaseAmount
                ]);

                // Reduce Cash Balance
                $this->db->set('CurrentBalance', 'CurrentBalance - '.$totalPurchaseAmount, FALSE);
                $this->db->where('AccountID', 1);
                $this->db->update('chart_of_accounts');
            }
        } else if ($submitAction === 'save_bill') {
            $existingPayment = $this->db->where('PurchaseID', $purchaseID)->where('IsDeleted', 0)->get('vendor_payments')->row();
            if ($existingPayment) {
                // Delete/Mark deleted the payment
                $this->db->where('PaymentID', $existingPayment->PaymentID)->update('vendor_payments', ['IsDeleted' => 1]);

                // Revert balances
                // Revert Payable (AccountID = 5)
                $this->db->set('CurrentBalance', 'CurrentBalance + '.$existingPayment->PaidAmount, FALSE);
                $this->db->where('AccountID', 5);
                $this->db->update('chart_of_accounts');

                // Revert Cash (AccountID = 1)
                $this->db->set('CurrentBalance', 'CurrentBalance - '.$existingPayment->PaidAmount, FALSE);
                $this->db->where('AccountID', 1);
                $this->db->update('chart_of_accounts');
            }
        }

        // Set success message
        $this->session->set_flashdata('notification', [
            "alert" => "success",
            "text" => '<strong>Purchase '.($purchaseID ? 'updated' : 'added').' successfully!</strong>'
        ]);

        redirect('admin/Purchases');
    }




    public function stock() {
        // Fetch stock data from the model
        $stockData = $this->model->get_stock_report();

        // Pass the stock data to the view
        $this->view([
            'page' => 'purchases/stock',
            'stockData' => $stockData
        ]);
    }




    public function form() {
        // Load necessary models
        $this->load->model('Purchase_model');
        $this->load->model('Vendor_model');
        $this->load->model('Category_model');
        $this->load->model('Brand_model');

        // Fetch data for the form
        $data['vendors'] = $this->Vendor_model->get_all_vendors();
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['brand'] = $this->Brand_model->get_all_brand();

        // Load the form view
        $this->load->view('admin/pages/purchases/form', $data);
    }

    public function save() {
        // Gather form data
        $vendorID = $this->input->post('vendor_id');
        $items = $this->input->post('item_ids');
        $quantities = $this->input->post('quantities');
        $qtytypes = $this->input->post('qtytypes');
        $prices = $this->input->post('prices');
        $totalAmount = $this->input->post('total_amount');

        // Save purchase details
        $purchaseID = $this->Purchase_model->save_purchase($vendorID, $totalAmount);

        // Save each purchased item
        foreach ($items as $index => $itemID) {
            $this->Purchase_model->save_purchase_item($purchaseID, !empty($itemID) ? $itemID : NULL, $quantities[$index], $qtytypes[$index], $prices[$index]);
        }

        // Redirect or show success message
        redirect('admin/purchases');
    }

    public function getItemsByCategory() {
        $categoryID = $this->input->post('category_id');
        $this->load->model('Item_model');
        $items = $this->Item_model->get_items_by_category($categoryID);

        $options = '<option value="">Select Item</option>';
        foreach ($items as $item) {
            $options .= '<option value="' . $item->ItemID . '" data-price="' . $item->Price . '">' . $item->ItemName . '</option>';
        }

        echo $options;
    }


    public function print_purchase($purchaseID)
    {
        // Load the Sale details
        $this->load->model('Purchases_model');
        $purchase = $this->Purchases_model->get_purchase_by_id($purchaseID);  // Ensure this returns purchase data
        $items = $this->Purchases_model->get_purchase_items($purchaseID); // Ensure this fetches purchase items related to the sale

        // Check if purchase and items are retrieved
        if ($purchase && $items) {
            $purchase->items = $items; // Add the items to the purchase object
        } else {
            $purchase->items = []; // Fallback if no items are found
        }

        // Load the view
        $data['purchases'] = $purchase;  // Ensure that this array contains 'sales'
        $this->load->view('admin/pages/purchases/print_purchase', $data);
    }


}
?>
