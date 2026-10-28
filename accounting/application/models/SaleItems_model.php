<?php
class SaleItems_model extends MY_Model
{
    protected $_table = "sale_items"; // Table name
    protected $primary_key = "SaleItemID"; // Assuming this is the primary key

    // Method to get sale items by SaleID
    public function get_items_by_sale($saleID) {
        return $this->db->where('SaleID', $saleID)->get($this->_table)->result();
    }

    // Method to insert multiple items for a sale
    public function insert_items($items) {
        return $this->db->insert_batch($this->_table, $items);
    }

    // Other methods related to sale items can be added here
}
    