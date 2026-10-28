<?php
// Sales Model
class Sales_model extends MY_Model
{
    protected $_table = "sales";
    protected $primary_key = "SaleID";

    public $before_create = ['calculate_total_before_create'];

    public function calculate_total_before_create($data)
    {
        $total = 0;
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $total += $item['Quantity'] * $item['SalePrice'];
            }
        }
        $data['TotalSaleAmount'] = $total;
        return $data;
    }

    public function get_customers()
    {
        return $this->db->get('customer')->result();
    }

    public function get_all()
{
    $this->db->select('sales.*, customer.CustomerName, user_tab.UserName,
                       GROUP_CONCAT(DISTINCT IFNULL(items.ItemName, "") SEPARATOR ", ") AS ItemNames,
                       GROUP_CONCAT(DISTINCT IFNULL(categories.CategoryName, "") SEPARATOR ", ") AS CategoryNames');
    $this->db->from('sales');
    $this->db->join('customer', 'sales.CustomerId = customer.CustomerId', 'left');
    $this->db->join('sale_items', 'sale_items.SaleID = sales.SaleID', 'left');
    $this->db->join('items', 'sale_items.ItemID = items.ItemID', 'left');
    $this->db->join('categories', 'items.CategoryID = categories.CategoryID', 'left');
    $this->db->join('user_tab', 'sales.UserId = user_tab.UserId', 'left'); // <-- Added
    $this->db->where('sales.IsDeleted', 0);
    $this->db->group_by('sales.SaleID');
    $this->db->order_by('sales.SaleID', 'DESC');
    return $this->db->get()->result();
}


    public function get_sale_by_id($id) {
        $this->db->select('
            sales.*, 
            customer.CustomerName,
            GROUP_CONCAT(items.ItemName) as ItemNames, 
            GROUP_CONCAT(sale_items.Quantity) as Quantities,
            GROUP_CONCAT(sale_items.QtyType) as QtyTypes, 
            GROUP_CONCAT(sale_items.SalePrice) as Prices,
            GROUP_CONCAT(sale_items.Discount) as Discounts
        ');
        $this->db->from('sales');
        $this->db->join('customer', 'customer.CustomerId = sales.CustomerId', 'left');
        $this->db->join('sale_items', 'sale_items.SaleID = sales.SaleID', 'left');
        $this->db->join('items', 'items.ItemID = sale_items.ItemID', 'left'); // Join items table
        $this->db->where('sales.SaleID', $id);
        $this->db->group_by('sales.SaleID');
        return $this->db->get()->row();
    }



    public function get_stock_report()
    {
        $query = "
            SELECT 
                items.ItemName,
                items.ItemDescription,
                categories.CategoryName,
                COALESCE(purchases.Qty, 0) AS TotalPurchases,
                COALESCE(sales.Qty, 0) AS TotalSales,
                (COALESCE(purchases.Qty, 0) - COALESCE(purchase_returns.Qty, 0) - COALESCE(sales.Qty, 0) + COALESCE(sales_returns.Qty, 0)) AS Stock
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


    // Fetch sale items related to the sale
    public function get_sale_items($saleID) {
        return $this->db->where('SaleID', $saleID)
                        ->get('sale_items')
                        ->result();  // Fetch related items as an array of objects
    }

    // Add method to calculate today's sales

    public function get_today_sales()
    {
        $this->db->select_sum('TotalSaleAmount', 'todays_sales');
        $this->db->where('DATE(SaleDate)', date('Y-m-d'));
        $query = $this->db->get('sales');
        log_message('debug', 'Today Sales Query: ' . $this->db->last_query());
        $result = $query->row();
        return $result ? $result->todays_sales : 0;
    }


    public function get_customer_details($customerId)
    {
        $this->db->select('CreditLimit, CustomerType');
        $this->db->from('customer');
        $this->db->where('CustomerId', $customerId);
        $result = $this->db->get()->row_array();

        // Add debugging log
        log_message('debug', 'Model Query Result: ' . json_encode($result));
        return $result;
    }


    public function get_credit_balance($customerId)
    {
        $this->db->select('c.CreditLimit, IFNULL(SUM(cp.PaidAmount), 0) AS TotalPaidAmount, (c.CreditLimit - IFNULL(SUM(cp.PaidAmount), 0)) AS CreditBalance');
        $this->db->from('customer c');
        $this->db->join('customer_payments cp', 'c.CustomerId = cp.CustomerId', 'left');
        $this->db->where('c.CustomerId', $customerId);
        $this->db->where('c.IsDeleted', 0);
        $this->db->group_by('c.CustomerId');
        $query = $this->db->get();
        return $query->row_array();
    }


   public function get_sale_invoice($saleId)
    {
        $this->db->select("
            sales.*,
            customer.CustomerName,
            user_tab.Name AS UserName,
            GROUP_CONCAT(items.ItemName) AS ItemNames,
            GROUP_CONCAT(sale_items.Quantity) AS Quantities,
            GROUP_CONCAT(sale_items.QtyType) AS QtyTypes,
            GROUP_CONCAT(sale_items.SalePrice) AS Prices,
            GROUP_CONCAT(IFNULL(sale_items.Discount,0)) AS Discounts
        ");

        $this->db->from('sales');
        $this->db->join('customer', 'customer.CustomerId = sales.CustomerId', 'left');
        $this->db->join('user_tab', 'user_tab.UserId = sales.UserId', 'left');
        $this->db->join('sale_items', 'sale_items.SaleId = sales.SaleID', 'left');
        $this->db->join('items', 'items.ItemId = sale_items.ItemId', 'left');

        $this->db->where('sales.SaleID', $saleId);
        $this->db->group_by('sales.SaleID');

        return $this->db->get()->row();
    }


    public function get_sale_details($saleID)
    {
        $this->db->select('
            s.SaleID,
            s.SaleDate,
            s.TotalSaleAmount,
            c.CustomerID,
            c.CustomerName
        ');
        $this->db->from('sales s');
        $this->db->join('customer c', 'c.CustomerID = s.CustomerID');
        $this->db->where('s.SaleID', $saleID);

        return $this->db->get()->row();
    }






}