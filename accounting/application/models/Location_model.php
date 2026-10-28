<?php
class Location_model extends MY_Model
{
    protected $_table = "location";  // Adjust according to your actual table name
    protected $primary_key = "LocationId";

    // Define before_create and before_update hooks
    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Method to handle data before creation (must be public)
    public function prop_data_before_create($data)
    {
        // Add logic here if needed before creating a location
        return $data;
    }

    // Method to handle data before update (must be public)
    public function prop_data_before_update($data)
    {
        // Add logic here if needed before updating a location
        return $data;
    }

    // Fetch all customers
    public function get_all()
    {
        $this->db->where('IsDeleted', 0); // Assuming '0' means not deleted
        return $this->db->get($this->_table)->result();
    }


}
?>
