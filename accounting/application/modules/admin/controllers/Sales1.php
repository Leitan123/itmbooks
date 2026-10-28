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

    // Create a new sale
    function create()
    {
        $this->_form();
    }

    // Handle the sale form (create/edit)
    function _form($data = [], $id = null) {
        // Load all necessary data
        if (empty($data['customers'])) {
            $data['customers'] = $this->customer->get_all();
        }

        if (empty($data['categories'])) {
            $data['categories'] = $this->categories->get_all();
        }

        if (empty($data['items'])) {
            $data['items'] = $this->items->get_all();
        }

        // Handle sale data
        if ($id) {
            $data['sale'] = $this->model->get_sale_by_id($id);
            $data['sale_items'] = $this->model->get_sale_items($id);
        } else {
            $data['sale'] = null;
            $data['sale_items'] = []; // Initialize empty array for new sales
        }

        // Form validation
        $this->form_validation->set_rules("form[CustomerId]", "Customer", "required");
        $this->form_validation->set_rules("form[SaleDate]", "Sale Date", "required");
        $this->form_validation->set_rules("form[items]", "Items", "required");

        if ($this->form_validation->run()) {
            $this->save_sale();
            return;
        }

        // Pass all data to view
        $this->view([
            'page' => "$this->page/form",
            'customers' => $data['customers'],
            'items' => $data['items'],
            'categories' => $data['categories'],
            'sale' => $data['sale'],
            'sale_items' => $data['sale_items'] // Pass sale items to view
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

        /* =========================
           GET LOGGED-IN USER
           ========================= */
        $user = $this->session->userdata('user');

        if (!is_object($user) || !isset($user->UserId)) {
            $this->session->set_flashdata('notification', array(
                "alert" => "danger",
                "text"  => "<strong>User session expired. Please login again.</strong>"
            ));
            redirect('admin/Sales');
            return;
        }

        $userId = $user->UserId;

        /* =========================
           CALCULATE GRAND TOTAL
           ========================= */
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {

                $qty   = isset($item['Quantity']) ? (float)$item['Quantity'] : 0;
                $price = isset($item['SalePrice']) ? (float)$item['SalePrice'] : 0;

                $subTotal = $qty * $price;

                $discountPercent = isset($item['DiscountPercent']) ? (float)$item['DiscountPercent'] : 0;
                $discountAmount  = ($subTotal * $discountPercent) / 100;

                $totalSaleAmount += ($subTotal - $discountAmount);
            }
        }

        /* =========================
           SALES MASTER DATA
           ========================= */
        $saleData = array(
            'UserId'          => $userId,
            'CustomerId'      => $data['CustomerId'],
            'SaleDate'        => $data['SaleDate'],
            'PONo'            => !empty($data['PONo']) ? $data['PONo'] : null,
            'RefNo'           => !empty($data['RefNo']) ? $data['RefNo'] : null,
            'TotalSaleAmount' => $totalSaleAmount,
            'UpdatedAt'       => date('Y-m-d H:i:s')
        );

        if ($saleID) {
            // UPDATE SALE
            $this->db->where('SaleID', $saleID)->update('sales', $saleData);
            $this->db->where('SaleID', $saleID)->delete('sale_items');
            $message = 'updated';
        } else {
            // INSERT SALE
            $saleData['CreatedAt'] = date('Y-m-d H:i:s');
            $this->db->insert('sales', $saleData);
            $saleID = $this->db->insert_id();
            $message = 'added';
        }

        /* =========================
           INSERT SALE ITEMS
           ========================= */
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {

                $qty   = (float)$item['Quantity'];
                $price = (float)$item['SalePrice'];

                $subTotal = $qty * $price;

                $discountPercent = isset($item['DiscountPercent']) ? (float)$item['DiscountPercent'] : 0;
                $discountAmount  = ($subTotal * $discountPercent) / 100;

                $this->db->insert('sale_items', array(
                    'SaleID'          => $saleID,
                    'ItemID'          => $item['ItemID'],
                    'Quantity'        => $qty,
                    'QtyType'         => $item['QtyType'],
                    'SalePrice'       => $price,
                    'DiscountPercent' => $discountPercent,
                    'DiscountAmount'  => $discountAmount,
                    'TotalAmount'     => $subTotal - $discountAmount
                ));
            }
        }

        /* =========================
           SUCCESS MESSAGE
           ========================= */
        $this->session->set_flashdata('notification', array(
            "alert" => "success",
            "text"  => "<strong>Sale {$message} successfully!</strong>"
        ));

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

    // Load view
    $data['sales'] = $sale;
    $this->load->view('admin/pages/sales/print_invoice', $data);
}


    public function get_customer_details()
{
    $customerId = $this->input->post('customerId');

    if (!$customerId) {
        echo json_encode(['error' => 'Invalid Customer ID']);
        return;
    }

    // Load customer
    $customer = $this->db->get_where('customer', ['CustomerId' => $customerId])->row();

    if (!$customer) {
        echo json_encode(['error' => 'Customer not found']);
        return;
    }

    // Total Sales
    $this->db->select_sum('TotalSaleAmount');
    $this->db->where('CustomerId', $customerId);
    $salesTotal = (float) $this->db->get('sales')->row()->TotalSaleAmount;

    // Total Payments
    $this->db->select_sum('PaidAmount');
    $this->db->where('CustomerId', $customerId);
    $paidTotal = (float) $this->db->get('customer_payments')->row()->PaidAmount;

    // Outstanding & Balance Calculation
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









}
