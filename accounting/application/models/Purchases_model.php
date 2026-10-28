<?php
class Purchases_model extends MY_Model
{
    protected $_table = "purchases";
    protected $primary_key = "PurchaseID";

    public $before_create = ['calculate_total_before_create'];

    public function calculate_total_before_create($data)
    {
        // Calculate TotalPurchaseAmount from items
        $total = 0;
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $total += $item['Quantity'] * $item['PurchasePrice'];
            }
        }
        $data['TotalPurchaseAmount'] = $total;
        return $data;
    }

    public function get_vendors() {
        return $this->db->get('vendor')->result();
    }
 

    public function get_all() {
        $this->db->select('purchases.*, 
                           vendor.VendorName,
                           user_tab.UserId,
                           user_tab.Name as UserName,
                           GROUP_CONCAT(items.ItemName) as ItemNames, 
                           GROUP_CONCAT(categories.CategoryName) as CategoryNames, 
                           GROUP_CONCAT(brand.BrandName) as BrandNames');
        $this->db->from('purchases');
        $this->db->join('vendor', 'purchases.VendorID = vendor.VendorID', 'left');
        $this->db->join('purchase_items', 'purchase_items.PurchaseID = purchases.PurchaseID', 'left');
        $this->db->join('items', 'purchase_items.ItemID = items.ItemID', 'left');
        $this->db->join('categories', 'items.CategoryID = categories.CategoryID', 'left');
        $this->db->join('brand', 'items.BrandID = brand.BrandID', 'left'); // Join the brands table
        $this->db->join('user_tab', 'purchases.UserId = user_tab.UserId', 'left'); // Join user_tab to get UserId and Name
        $this->db->where('purchases.IsDeleted', 0);
        $this->db->group_by('purchases.PurchaseID');
        $this->db->order_by('purchases.PurchaseID', 'DESC');
        return $this->db->get()->result();
    }


    // Fetch items by category, returned as JSON
    public function get_items_by_category($CategoryID) {
        $items = $this->db->where('CategoryID', $CategoryID)
                          ->get('items')
                          ->result();

        echo json_encode($items); // Return JSON for use in AJAX
    }


    public function get_purchase_by_id($id) {
        $this->db->select('purchases.*, 
                           vendor.VendorName,
                           GROUP_CONCAT(items.ItemName) as ItemNames, 
                           GROUP_CONCAT(purchase_items.Quantity) as Quantities,
                           GROUP_CONCAT(purchase_items.QtyType) as QtyTypes,
                           GROUP_CONCAT(purchase_items.PurchasePrice) as Prices');
        $this->db->from('purchases');
        $this->db->join('vendor', 'vendor.VendorID = purchases.VendorID', 'left');
        $this->db->join('purchase_items', 'purchase_items.PurchaseID = purchases.PurchaseID', 'left');
        $this->db->join('items', 'items.ItemID = purchase_items.ItemID', 'left'); // Join items table
        $this->db->where('purchases.PurchaseID', $id);
        $this->db->group_by('purchases.PurchaseID');
        return $this->db->get()->row();
    }




    public function get_categories() {
        return $this->db->get('categories')->result(); // Adjust the table name if necessary
    }

    public function get_stock_report() {
        $query = "
            SELECT 
                items.ItemName,
                items.ItemDescription,
                categories.CategoryName,
                (COALESCE(purchases.Qty, 0) - COALESCE(purchase_returns.Qty, 0) - COALESCE(sales.Qty, 0) + COALESCE(sales_returns.Qty, 0)) AS TotalQuantity
            FROM items
            LEFT JOIN categories ON items.CategoryID = categories.CategoryID
            LEFT JOIN (
                SELECT ItemID, SUM(Quantity) AS Qty 
                FROM purchase_items 
                GROUP BY ItemID
            ) purchases ON items.ItemID = purchases.ItemID
            LEFT JOIN (
                SELECT ItemID, SUM(Quantity) AS Qty 
                FROM sale_items 
                GROUP BY ItemID
            ) sales ON items.ItemID = sales.ItemID
            LEFT JOIN (
                SELECT ItemID, SUM(Quantity) AS Qty 
                FROM sales_return_items 
                WHERE IsDamaged = 0
                GROUP BY ItemID
            ) sales_returns ON items.ItemID = sales_returns.ItemID
            LEFT JOIN (
                SELECT ItemID, SUM(Quantity) AS Qty 
                FROM purchase_return_items 
                GROUP BY ItemID
            ) purchase_returns ON items.ItemID = purchase_returns.ItemID
            WHERE items.IsDeleted = 0
        ";
        return $this->db->query($query)->result();
    }



    public function save_purchase($vendorID, $totalAmount) {
        $data = [
            'VendorID' => $vendorID,
            'TotalAmount' => $totalAmount,
            'PurchaseDate' => date('Y-m-d'),
        ];

        $this->db->insert('purchases', $data);
        return $this->db->insert_id();
    }

    public function save_purchase_item($purchaseID, $itemID, $quantity, $qtytype, $price) {
        $data = [
            'PurchaseID' => $purchaseID,
            'ItemID' => !empty($itemID) ? $itemID : NULL,
            'Quantity' => $quantity,
            'QtyType' => $qtytype,
            'Price' => $price,
        ];

        $this->db->insert('purchase_items', $data);
    }


    public function get_purchase_items($purchaseID) {
     return $this->db->select('purchase_items.*, items.ItemName, items.CategoryID, items.BrandID')
                    ->from('purchase_items')
                    ->join('items', 'items.ItemID = purchase_items.ItemID', 'left')
                    ->where('purchase_items.PurchaseID', $purchaseID)
                    ->get()
                    ->result();
    }
    
    public function get_items() {
        $this->db->select('items.*, 
                           IFNULL(categories.CategoryName, "No Category") as CategoryName, 
                           IFNULL(brand.BrandName, "No Brand") as BrandName');
        $this->db->from('items');
        $this->db->join('categories', 'items.CategoryID = categories.CategoryID', 'left');
        $this->db->join('brand', 'items.BrandID = brand.BrandID', 'left'); // Join the brand table
        return $this->db->get()->result();
    }

 

 


}
?>
