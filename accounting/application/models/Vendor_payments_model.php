<?php
class Vendor_payments_model extends MY_Model
{
    protected $_table = "vendor_payments";
    protected $primary_key = "PaymentID";


    // Define before_create and before_update hooks
    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Method to handle data before creation (must be public)
    public function prop_data_before_create($data)
    {
        // Add logic here if needed before creating a vendor
        return $data;
    }

    // Method to handle data before update (must be public)
    public function prop_data_before_update($data)
    {
        // Add logic here if needed before updating a vendor
        return $data;
    }


    public function get_vendors() {
        return $this->db->get('vendor')->result();
    }

    public function get_purchases() {
        return $this->db->get('purchases')->result();
    }

    public function get_all()
    {
        $this->db->select('
            vendor_payments.*,
            vendor.VendorName,
            purchases.PurchaseID,
            purchases.PONo,
            purchases.RefNo,
            purchases.TotalPurchaseAmount,
            user_tab.UserId,
            user_tab.Name AS UserName
        ');
        $this->db->from('vendor_payments');
        $this->db->join('vendor', 'vendor_payments.VendorID = vendor.VendorID', 'left');
        $this->db->join('purchases', 'vendor_payments.PurchaseID = purchases.PurchaseID', 'left');
        $this->db->join('user_tab', 'vendor_payments.UserId = user_tab.UserId', 'left');
        $this->db->where('vendor_payments.IsDeleted', 0);
        $this->db->order_by('vendor_payments.PaymentID', 'DESC');
        return $this->db->get()->result();
    }

    public function get_payment_by_id($id) {
        $this->db->select('vendor_payments.*, vendor.VendorName, purchases.TotalPurchaseAmount');
        $this->db->from('vendor_payments');
        $this->db->join('vendor', 'vendor_payments.VendorID = vendor.VendorID', 'left');
        $this->db->join('purchases', 'vendor_payments.PurchaseID = purchases.PurchaseID', 'left');
        $this->db->where('vendor_payments.PaymentID', $id);
        return $this->db->get()->row();
    }

    public function get_payments_by_vendor($VendorID) {
        $this->db->where('VendorID', $VendorID);
        return $this->db->get('vendor_payments')->result();
    }


    public function get_vendor_details($VendorID) {
        // Query to get the payment and invoice details for the selected vendor
        $this->db->select('purchases.PurchaseDate, purchases.InvoiceNo, purchases.TotalPurchaseAmount, vendor_payments.PaymentDate, vendor_payments.ReceiptNo, vendor_payments.PaidAmount, vendor.VendorID, vendor.VendorName');
        $this->db->from('purchases');
        $this->db->join('vendor_payments', 'purchases.VendorID = vendor_payments.VendorID AND purchases.PurchaseID = vendor_payments.PurchaseID', 'left');
        $this->db->join('vendor', 'purchases.VendorID = vendor.VendorID', 'left');
        $this->db->where('purchases.VendorID', $VendorID);

        $query = $this->db->get();

        // Send the result as JSON to the frontend
        echo json_encode($query->result());
    }


    public function get_all_vendors_with_due()
    {
        // Subquery to calculate total purchase amount for each vendor (excluding deleted purchases)
        $subquery_purchases = '(SELECT purchases.VendorID, 
                                       SUM(purchases.TotalPurchaseAmount) as TotalPurchaseAmount 
                                FROM purchases 
                                WHERE purchases.IsDeleted = 0 
                                GROUP BY purchases.VendorID) as purchases_table';

        // Subquery to calculate total paid amount for each vendor (excluding deleted payments if applicable)
        $subquery_payments = '(SELECT vendor_payments.VendorID, 
                                      SUM(vendor_payments.PaidAmount) as TotalPaidAmount 
                               FROM vendor_payments 
                               WHERE vendor_payments.IsDeleted = 0 
                               GROUP BY vendor_payments.VendorID) as payments_table';

        // Main query to get vendor details along with calculated dues
        $this->db->select('vendor.VendorName, 
                           COALESCE(purchases_table.TotalPurchaseAmount, 0) AS TotalPurchaseAmount, 
                           COALESCE(payments_table.TotalPaidAmount, 0) AS TotalPaidAmount,
                           (COALESCE(purchases_table.TotalPurchaseAmount, 0) - COALESCE(payments_table.TotalPaidAmount, 0)) AS PaymentDue');
        $this->db->from('vendor');
        $this->db->join($subquery_purchases, 'vendor.VendorID = purchases_table.VendorID', 'left');
        $this->db->join($subquery_payments, 'vendor.VendorID = payments_table.VendorID', 'left');
        $this->db->where('vendor.IsDeleted', 0); // Ensure only non-deleted vendors are included
        $this->db->group_by('vendor.VendorID');

        return $this->db->get()->result();
    }

    
   public function get_total_due_amount($vendorId) {
        // Subquery for purchases with IsDeleted = 0
        $subquery_purchases = '(SELECT VendorID, 
                                       COALESCE(SUM(TotalPurchaseAmount), 0) AS TotalPurchaseAmount 
                                FROM purchases 
                                WHERE IsDeleted = 0 
                                GROUP BY VendorID) AS purchases_table';

        // Subquery for vendor payments with IsDeleted = 0
        $subquery_payments = '(SELECT VendorID, 
                                      COALESCE(SUM(PaidAmount), 0) AS TotalPaidAmount 
                               FROM vendor_payments 
                               WHERE IsDeleted = 0 
                               GROUP BY VendorID) AS payments_table';

        // Main query
        $this->db->select('(COALESCE(purchases_table.TotalPurchaseAmount, 0) - COALESCE(payments_table.TotalPaidAmount, 0)) AS PaymentDue');
        $this->db->from('vendor');
        $this->db->join($subquery_purchases, 'vendor.VendorID = purchases_table.VendorID', 'left');
        $this->db->join($subquery_payments, 'vendor.VendorID = payments_table.VendorID', 'left');
        $this->db->where('vendor.VendorID', $vendorId);
        $this->db->where('vendor.IsDeleted', 0); // Exclude deleted vendors

        $result = $this->db->get()->row();

        // Return the due amount or 0 if not found
        return $result ? max(0, $result->PaymentDue) : 0;
    }


    //reports

    // =========================
    // 1. Summary: All vendors with total purchase, total paid, total due
    // =========================
    public function get_summary() {
        $this->db->select('v.VendorID, v.VendorName, 
            IFNULL(SUM(p.TotalPurchaseAmount),0) as TotalPurchase, 
            IFNULL(SUM(vp.PaidAmount),0) as TotalPaid,
            (IFNULL(SUM(p.TotalPurchaseAmount),0) - IFNULL(SUM(vp.PaidAmount),0)) as TotalDue'
        );
        $this->db->from('vendor v');
        $this->db->join('purchases p', 'p.VendorID = v.VendorID AND p.IsDeleted=0', 'left');
        $this->db->join('vendor_payments vp', 'vp.VendorID = v.VendorID AND vp.IsDeleted=0', 'left');
        $this->db->group_by('v.VendorID');
        $query = $this->db->get();
        return $query->result();
    }

    // =========================
    // 2. Detailed: Purchase-wise payments for a vendor
    // =========================
    public function get_detailed($vendorId) {
        $this->db->select('p.PurchaseID, p.PurchaseDate, p.TotalPurchaseAmount as TotalAmount, 
            IFNULL(SUM(vp.PaidAmount),0) as PaidAmount, 
            (p.TotalPurchaseAmount - IFNULL(SUM(vp.PaidAmount),0)) as DueAmount');
        $this->db->from('purchases p');
        $this->db->join('vendor_payments vp', 'vp.PurchaseID = p.PurchaseID AND vp.IsDeleted=0', 'left');
        $this->db->where('p.VendorID', $vendorId);
        $this->db->where('p.IsDeleted', 0);
        $this->db->group_by('p.PurchaseID');
        $this->db->order_by('p.PurchaseDate', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // =========================
    // 3. Ledger: Vendor's transaction ledger
    // =========================
    public function get_ledger($vendorId) {
        // Get purchases
        $purchases = $this->db->select('PurchaseID, PurchaseDate as Date, TotalPurchaseAmount as TotalAmount, "" as Description, 0 as PaidAmount')
            ->where('VendorID', $vendorId)
            ->where('IsDeleted', 0)
            ->get('purchases')
            ->result();

        // Get payments
        $payments = $this->db->select('PurchaseID, PaymentDate as Date, 0 as TotalAmount, PaymentMethod as Description, PaidAmount')
            ->where('VendorID', $vendorId)
            ->where('IsDeleted', 0)
            ->get('vendor_payments')
            ->result();

        // Merge and sort by date
        $ledger = array_merge($purchases, $payments);
        usort($ledger, function($a, $b) {
            return strtotime($a->Date) - strtotime($b->Date);
        });

        return $ledger;
    }

    


}
?>
