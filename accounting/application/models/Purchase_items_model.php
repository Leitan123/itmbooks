<?php
class Purchase_items_model extends MY_Model
{
    protected $_table = "purchase_items";
    protected $primary_key = "PurchaseItemID";

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
}
?>
